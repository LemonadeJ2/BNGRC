<?php
    namespace app\models;

    class Besoin{

        protected $db;

        public function _construct($db){
            $this->db = $db;
        }

/// CRUD
        public function getAllBesoins(){
            $sql = "SELECT * FROM besoin";
            $stmt = $this->db->query();

            $result = $stmt->execute($sql);

            return $result->fetchAll();
        }

        public function getBesoinById($id){
            $sql = "SELECT * FROM besoin WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id]);

            return $result->fetch();
        }

        public function createBesoin($prix, $nom){
            $sql = "INSERT INTO besoin (nom, prix) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$nom, $prix]);

            return $result;
        }

        public function updateBesoin($id, $prix, $nom){

            $sql = "UPDATE besoin SET nom = ?, prix = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$nom, $prix, $id]);

            return $result;
        }

        public function deleteBesoin($id){
            $sql = "DELETE FROM besoin WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$id]);

            return $result;
        }

        public function totalBesoins()
        {
            $sql = "SELECT SUM(prix) AS total FROM besoin";
            $stmt = $this->db->query();

            $result = $stmt->execute($sql);

            return $result->fetch()['total'];
        }

/// Besoins des sinistrés par ville
        public function saisieBesoinsSinistresParVille($villeId, $besoinId, $quantite){
            $sql = "INSERT INTO besoins_sinistres (ville_id, besoin_id, quantite) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([$villeId, $besoinId, $quantite]);

            return $result;
        }
    }
?>