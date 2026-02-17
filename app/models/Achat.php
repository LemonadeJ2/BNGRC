<?php
namespace app\models;

use PDO;

class Achat
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère le pourcentage de frais d'achat configuré
     */
    public function getFraisAchat()
    {
        $sql = "SELECT frais_achat FROM parametres ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['frais_achat'] ?? 10.00;
    }

    /**
     * Met à jour le pourcentage de frais d'achat
     */
    public function updateFraisAchat($frais)
    {
        $sql = "INSERT INTO parametres (frais_achat) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$frais]);
    }

    /**
     * Calcule le total des dons en argent reçus
     * (en utilisant votre table don existante)
     */
    public function getTotalDonsArgent()
    {
        $sql = "SELECT SUM(d.quantite * b.prix) AS total
                FROM don d
                JOIN besoin b ON d.id_besoin = b.id
                WHERE b.id_type_besoin = 3";  // Type 'Argent'

        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Calcule le total des achats déjà effectués
     */
    public function getTotalAchats()
    {
        $sql = "SELECT SUM(montant_total) AS total FROM achat";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Calcule les dons restants disponibles (dons en argent - achats déjà effectués)
     */
    public function getDonsRestants()
    {
        $totalDonsArgent = $this->getTotalDonsArgent();
        $totalAchats = $this->getTotalAchats();

        return $totalDonsArgent - $totalAchats;
    }

    /**
     * Récupère les besoins restants (nature et matériaux non encore achetés)
     */
    public function getBesoinsRestants()
    {
        $sql = "SELECT 
                    v.id AS ville_id,
                    v.nom AS ville,
                    b.id AS besoin_id,
                    b.nom AS besoin,
                    b.prix AS prix_unitaire,
                    b.id_type_besoin,
                    t.type_besoin,
                    vb.quantite AS quantite_besoin,
                    COALESCE(SUM(a.quantite), 0) AS quantite_achetee,
                    (vb.quantite - COALESCE(SUM(a.quantite), 0)) AS quantite_restante,
                    ((vb.quantite - COALESCE(SUM(a.quantite), 0)) * b.prix) AS montant_restant
                FROM ville v
                INNER JOIN ville_besoin vb ON v.id = vb.id_ville
                INNER JOIN besoin b ON vb.id_besoin = b.id
                LEFT JOIN type_besoin t ON b.id_type_besoin = t.id
                LEFT JOIN achat a ON v.id = a.id_ville AND b.id = a.id_besoin
                WHERE b.id_type_besoin IN (1, 2)  -- Seulement Nature et Matériel
                GROUP BY v.id, b.id, vb.quantite, b.prix, t.type_besoin
                HAVING quantite_restante > 0
                ORDER BY v.nom, b.nom";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Effectue un achat
     */
    public function effectuerAchat($id_ville, $id_besoin, $quantite, $frais_pourcentage)
    {
        try {
            $this->db->beginTransaction();

            // Récupérer le prix unitaire du besoin
            $sql = "SELECT prix FROM besoin WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_besoin]);
            $besoin = $stmt->fetch();

            if (!$besoin) {
                throw new \Exception("Besoin non trouvé");
            }

            $prix_unitaire = $besoin['prix'];
            $montant_achat = $prix_unitaire * $quantite;
            $frais_montant = $montant_achat * ($frais_pourcentage / 100);
            $montant_total = $montant_achat + $frais_montant;

            // Vérifier si les dons restants sont suffisants
            $dons_restants = $this->getDonsRestants();
            if ($montant_total > $dons_restants) {
                throw new \Exception("Fonds insuffisants. Disponible: " . number_format($dons_restants, 0, ',', ' ') . " Ar, Nécessaire: " . number_format($montant_total, 0, ',', ' ') . " Ar");
            }

            // Vérifier que la quantité ne dépasse pas le besoin restant
            $besoinsRestants = $this->getBesoinsRestants();
            $besoinValide = false;
            $quantiteRestante = 0;

            foreach ($besoinsRestants as $br) {
                if ($br['ville_id'] == $id_ville && $br['besoin_id'] == $id_besoin) {
                    $besoinValide = true;
                    $quantiteRestante = $br['quantite_restante'];
                    break;
                }
            }

            if (!$besoinValide) {
                throw new \Exception("Ce besoin n'est pas disponible pour cette ville");
            }

            if ($quantite > $quantiteRestante) {
                throw new \Exception("Quantité demandée ($quantite) dépasse le besoin restant ($quantiteRestante)");
            }

            // Insérer l'achat
            $sql = "INSERT INTO achat (id_ville, id_besoin, quantite, montant_achat, 
                                       frais_pourcentage, frais_montant, montant_total, date_achat) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $id_ville,
                $id_besoin,
                $quantite,
                $montant_achat,
                $frais_pourcentage,
                $frais_montant,
                $montant_total,
                date('Y-m-d')
            ]);

            if (!$result) {
                throw new \Exception("Erreur lors de l'insertion de l'achat");
            }

            $this->db->commit();
            return [
                'success' => true,
                'message' => 'Achat effectué avec succès',
                'montant_total' => $montant_total
            ];

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur achat: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Récupère la liste des achats, filtrable par ville
     */
    public function getAchatsFiltrables($ville_id = null)
    {
        $sql = "SELECT 
                    a.*,
                    v.nom AS ville,
                    b.nom AS besoin,
                    b.prix AS prix_unitaire,
                    t.type_besoin
                FROM achat a
                JOIN ville v ON a.id_ville = v.id
                JOIN besoin b ON a.id_besoin = b.id
                LEFT JOIN type_besoin t ON b.id_type_besoin = t.id";

        if ($ville_id) {
            $sql .= " WHERE a.id_ville = ?";
            $sql .= " ORDER BY a.date_achat DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ville_id]);
        } else {
            $sql .= " ORDER BY a.date_achat DESC";
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchAll();
    }
}
?>