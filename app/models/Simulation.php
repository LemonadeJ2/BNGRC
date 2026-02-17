<?php
namespace app\models;

class Simulation
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // === SAUVEGARDE DE L'ÉTAT AVANT SIMULATION ===

    public function sauvegarderEtatAvant($userId, $description = '')
    {
        try {
            error_log("sauvegarderEtatAvant - userId: $userId, description: $description");

            // Créer un point de sauvegarde
            $sql = "INSERT INTO sim_save (date_save, user_id, description) VALUES (NOW(), ?, ?)";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                error_log("Erreur préparation requête: " . print_r($this->db->errorInfo(), true));
                return false;
            }

            $result = $stmt->execute([$userId, $description]);

            if (!$result) {
                error_log("Erreur exécution: " . print_r($stmt->errorInfo(), true));
                return false;
            }

            $saveId = $this->db->lastInsertId();
            error_log("SaveId créé: " . $saveId);

            // ... reste du code

            return $saveId;

        } catch (\Exception $e) {
            error_log("Exception dans sauvegarderEtatAvant: " . $e->getMessage());
            return false;
        }
    }

    // === SAUVEGARDE DES RÉSULTATS DE SIMULATION ===

    public function sauvegarderResultatSimulation($saveId, $idVille, $idBesoin, $quantite, $provenance, $montant)
    {
        $sql = "INSERT INTO sim_resultat (id_save, id_ville, id_besoin, quantite_proposee, provenance, montant_utilise) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$saveId, $idVille, $idBesoin, $quantite, $provenance, $montant]);
    }

    // === RÉCUPÉRATION DES DONNÉES ===

    public function getDonsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_don WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getBesoinsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_besoin WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getAchatsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_achat WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getResultatsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_resultat WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        $resultats = $stmt->fetchAll();

        error_log("getResultatsBySave($saveId) retourne " . count($resultats) . " résultats");

        return $resultats;
    }

    public function getDerniereSauvegarde($userId)
    {
        $sql = "SELECT * FROM sim_save WHERE user_id = ? ORDER BY date_save DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // === VALIDATION (APPLICATION À LA VRAIE BDD) ===

    public function validerSimulation($saveId)
    {
        try {
            $this->db->beginTransaction();

            // Récupérer les résultats
            $resultats = $this->getResultatsBySave($saveId);

            // 1. Mettre à jour les dons (réduire les quantités)
            foreach ($resultats as $resultat) {
                $sql = "UPDATE don SET quantite = quantite - ? 
                        WHERE id_ville = ? AND id_besoin = ? AND quantite >= ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $resultat['quantite_proposee'],
                    $resultat['id_ville'],
                    $resultat['id_besoin'],
                    $resultat['quantite_proposee']
                ]);
            }

            // 2. Mettre à jour les besoins
            foreach ($resultats as $resultat) {
                $sql = "UPDATE ville_besoin SET quantite = quantite - ? 
                        WHERE id_ville = ? AND id_besoin = ? AND quantite >= ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $resultat['quantite_proposee'],
                    $resultat['id_ville'],
                    $resultat['id_besoin'],
                    $resultat['quantite_proposee']
                ]);
            }

            // 3. Créer des achats si nécessaire (provenance contient "Achat")
            foreach ($resultats as $resultat) {
                if (strpos($resultat['provenance'], 'Achat') !== false) {
                    $sql = "INSERT INTO achat (id_ville, id_besoin, quantite, montant_total, date_achat)
                            VALUES (?, ?, ?, ?, NOW())";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        $resultat['id_ville'],
                        $resultat['id_besoin'],
                        $resultat['quantite_proposee'],
                        $resultat['montant_utilise']
                    ]);
                }
            }

            // Ici on ne marque pas la sauvegarde comme validée car votre table n'a pas de champ 'type'
            // Option: Ajouter un champ 'statut' à sim_save ou simplement garder trace ailleurs

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur validation: " . $e->getMessage());
            return false;
        }
    }

    // === RÉINITIALISATION (ROLLBACK) ===

    public function reinitialiserDerniereValidation($userId)
    {
        try {
            $this->db->beginTransaction();

            // Récupérer la dernière sauvegarde
            $sql = "SELECT * FROM sim_save 
                    WHERE user_id = ? 
                    ORDER BY date_save DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $save = $stmt->fetch();

            if (!$save) {
                throw new \Exception("Aucune sauvegarde trouvée");
            }

            // Restaurer les dons
            $sql = "UPDATE don d
                    JOIN sim_don sd ON d.id = sd.id_don AND sd.id_save = ?
                    SET d.quantite = sd.quantite";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$save['id']]);

            // Restaurer les besoins
            $sql = "UPDATE ville_besoin vb
                    JOIN sim_besoin sb ON vb.id = sb.id_ville_besoin AND sb.id_save = ?
                    SET vb.quantite = sb.quantite";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$save['id']]);

            // Supprimer les achats créés après la sauvegarde
            $sql = "DELETE FROM achat WHERE date_achat > ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$save['date_save']]);

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur réinitialisation: " . $e->getMessage());
            return false;
        }
    }

    // === NETTOYAGE ===

    public function nettoyerAnciennesSimulations($userId)
    {
        $sql = "DELETE FROM sim_save WHERE user_id = ? AND date_save < DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

    public function getDonsDisponibles($saveId)
    {
        $sql = "SELECT sd.*, 
                   (sd.montant - COALESCE(SUM(sr.montant_utilise), 0)) AS montant_restant
            FROM sim_don sd
            LEFT JOIN sim_resultat sr ON sr.id_save = sd.id_save 
                AND sr.provenance LIKE CONCAT('%', sd.id_don, '%')
            WHERE sd.id_save = ?
            GROUP BY sd.id
            HAVING montant_restant > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }


}

?>