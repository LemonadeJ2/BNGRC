<?php

namespace app\models;

class Don
{

    protected $db;

    public function _construct($db)
    {
        $this->db = $db;
    }

/// CRUD
    public function getAllDons()
    {
        $sql = "SELECT * FROM don";
        $stmt = $this->db->query();

        $result = $stmt->execute($sql);

        return $result->fetchAll();
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
        $sql = "INSERT INTO don (montant, donateur) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$montant, $donateur]);

        return $result;
    }

    public function updateDon($id, $montant, $donateur)
    {
        $sql = "UPDATE don SET montant = ?, donateur = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$montant, $donateur, $id]);

        return $result;
    }

    public function deleteDon($id)
    {
        $sql = "DELETE FROM don WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }

/// Insertion de dons
    public function saisieDons($id_besoin, $donateur, $quantite, $date_don)
    {
        $sql = "INSERT INTO don (id_besoin, nom_donneur, quantite, date_don) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id_besoin, $donateur, $quantite, $date_don]);

        return $result;
    }
}
?>