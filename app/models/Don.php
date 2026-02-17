<?php

namespace app\models;

class Don
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /// CRUD
    public function getAllDons()
    {
        $sql = "SELECT * FROM don";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getDonById($id)
    {
        $sql = "SELECT * FROM don WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result->fetch();
    }

    public function createDon($montant, $donateur)
    {
        $sql = "INSERT INTO don (quantite, donateur) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$montant, $donateur]);

        return $result;
    }

    public function updateDon($id, $quantite, $donateur)
    {
        $sql = "UPDATE don SET quantite = ?, donateur = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$quantite, $donateur, $id]);

        return $result;
    }

    public function deleteDon($id)
    {
        $sql = "DELETE FROM don WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }

    public function totalDons()
    {
        $sql = "SELECT * FROM valeur_don";
        $stmt = $this->db->query($sql);

        $result = $stmt->fetch();

        return $result['total_dons'] ?? 0;
    }

    /// Insertion de dons
    public function saisieDons($id_ville, $id_besoin, $donateur, $quantite, $date_don)
    {
        try {
            // Récupérer le type de besoin
            $sql = "SELECT b.id_type_besoin, b.prix FROM besoin b WHERE b.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_besoin]);
            $besoin = $stmt->fetch();

            if (!$besoin) {
                error_log("Besoin non trouvé");
                return false;
            }

            // Si c'est un don en argent (type 3)
            // if ($besoin['id_type_besoin'] == 3) {
            //     $montant = $quantite; 

            //     // Calculer la quantité équivalente en unités de besoin
            //     $prix_unitaire_besoin = $besoin['prix'];
            //     $quantite_unite = $montant / $prix_unitaire_besoin;

            //     error_log("Don en argent: montant=$montant Ar, équivalent=$quantite_unite unités");

            //     // Insérer avec la quantité calculée
            //     $sql = "INSERT INTO don (id_ville, id_besoin, nom_donneur, quantite, date_don) 
            //         VALUES (?, ?, ?, ?, ?)";
            //     $stmt = $this->db->prepare($sql);

            //     // Stocker la quantité en tant que DECIMAL dans la base
            //     $result = $stmt->execute([$id_ville, $id_besoin, $donateur, $quantite_unite, $date_don]);

            // } else {
                // Don normal (nature ou matériel)
                $sql = "INSERT INTO don (id_ville, id_besoin, nom_donneur, quantite, date_don) 
                    VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$id_ville, $id_besoin, $donateur, $quantite, $date_don]);
            

            if ($result) {
                error_log("Don inséré avec succès");
                return true;
            } else {
                error_log("Échec insertion: " . print_r($stmt->errorInfo(), true));
                return false;
            }

        } catch (\PDOException $e) {
            error_log("Exception PDO: " . $e->getMessage());
            return false;
        }
    }
    public function donsAttribuesParVille()
    {
        $sql = "SELECT * FROM dons_par_ville";
        $stmt = $this->db->query($sql);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getAllDonsWithDetails()
    {
        $sql = "SELECT * FROM detail_don";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getDonsByVille($villeId)
    {
        $sql = "SELECT * FROM detail_don WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }
}
?>