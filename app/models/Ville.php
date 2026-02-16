<?php
    namespace app\models;

    class Ville{

        protected $db;

        public function _construct($db){
            $this->db = $db;
        }

/// CRUD
        public function getAllVilles(){
            $sql = "SELECT * FROM ville";
            $stmt = $this->db->query();

            $result = $stmt->execute($sql);

            return $result->fetchAll();
        }

        public function getVilleById($id){
            $sql = "SELECT * FROM ville WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id]);

            return $result->fetch();
        }

        public function createVille($name){
            $sql = "INSERT INTO ville (nom) VALUES (?)";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$name]);

            return $result;
        }

        public function updateVille($name, $id){
            $sql = "UPDATE ville SET nom = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$name, $id]);

            return $result;
        }

        public function deleteVille($id){
            $sql = "DELETE FROM ville WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id]);

            return $result;
        }
    }
?>