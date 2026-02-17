<?php
namespace app\models;

class Ville
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /// CRUD
    public function getAllVilles()
    {
        $sql = "SELECT id, nom FROM ville ORDER BY nom";  
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();

        error_log("getAllVilles() retourne " . count($result) . " villes");

        return $result;
    }

    public function getVilleById($id)
    {
        $sql = "SELECT * FROM ville WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result->fetch();
    }

    public function createVille($name)
    {
        $sql = "INSERT INTO ville (nom) VALUES (?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$name]);

        return $result;
    }

    public function updateVille($name, $id)
    {
        $sql = "UPDATE ville SET nom = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$name, $id]);

        return $result;
    }

    public function deleteVille($id)
    {
        $sql = "DELETE FROM ville WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }
}
?>