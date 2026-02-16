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

        public function createVille($id_besoin, $name, $nb_sinistres){
            $sql = "INSERT INTO ville (id_besoin, nom, nb_sinistres) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id_besoin, $name, $nb_sinistres]);

            return $result;
        }

        public function updateVille($id, $id_besoin, $name, $nb_sinistres){
            $sql = "UPDATE ville SET id_besoin = ?, nom = ?, nb_sinistres = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id_besoin, $name, $nb_sinistres, $id]);

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