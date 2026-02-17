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
    // Dans Don.php, ajoutez/modifiez cette méthode
    public function saisieDons($id_ville, $id_besoin, $donateur, $quantite, $date_don)
    {
        try {
            $this->db->beginTransaction();

            // Insérer le don
            $sql = "INSERT INTO don (id_ville, id_besoin, nom_donneur, quantite, date_don) 
                VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$id_ville, $id_besoin, $donateur, $quantite, $date_don]);

            if (!$result) {
                throw new \Exception("Erreur lors de l'insertion du don");
            }

            $id_don = $this->db->lastInsertId();

            // Si c'est un don en argent, l'ajouter à don_argent
            $sql = "SELECT b.id_type_besoin, b.prix 
                FROM besoin b 
                WHERE b.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_besoin]);
            $besoin = $stmt->fetch();

            if ($besoin && $besoin['id_type_besoin'] == 3) { // Type 'Argent'
                $montant = $quantite * $besoin['prix'];
                $sql = "INSERT INTO don_argent (id_don, montant_initial, montant_restant) 
                    VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id_don, $montant, $montant]);
            }

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur saisie don: " . $e->getMessage());
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