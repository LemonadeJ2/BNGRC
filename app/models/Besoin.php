<?php
namespace app\models;

class Besoin
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    /// CRUD
    public function getAllBesoins()
    {
        $sql = "SELECT * FROM besoin";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getBesoinById($id)
    {
        $sql = "SELECT * FROM besoin WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }

    public function createBesoin($prix, $nom)
    {
        $sql = "INSERT INTO besoin (nom, prix) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$nom, $prix]);

        return $result;
    }

    public function updateBesoin($id, $prix, $nom)
    {

        $sql = "UPDATE besoin SET nom = ?, prix = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$nom, $prix, $id]);

        return $result;
    }

    public function deleteBesoin($id)
    {
        $sql = "DELETE FROM besoin WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }

    public function totalBesoins()
    {
        $sql = "SELECT SUM(prix) AS total FROM besoin";
        $stmt = $this->db->query($sql);

        $result = $stmt->fetch();

        return $result['total'];
    }

    public function resteAcombler()
    {
        $totalBesoins = $this->totalBesoins();

        $sql = "SELECT (d.total_dons - $totalBesoins) AS reste_a_combler
                FROM valeur_don d";
        $stmt = $this->db->query($sql);

        $result = $stmt->fetch();
        $totalDons = $result['reste_a_combler'] ?? 0;

        return max(0, $totalBesoins - $totalDons);
    }

    /// Besoins des sinistrés par ville
    public function saisieBesoinsSinistresParVille($villeId, $besoinId, $quantite)
    {
        $sql = "INSERT INTO besoins_sinistres (ville_id, besoin_id, quantite) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$villeId, $besoinId, $quantite]);

        return $result;
    }

    public function nbBesoinsParVille($id_ville)
    {
        $sql = "SELECT * FROM nb_besoin_par_ville WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id_ville]);

        $result = $stmt->fetch();

        return $result;
    }

    public function listeBesoinsParVille($id_ville)
    {
        $sql = "SELECT 
                b.nom AS besoin,
                vb.quantite AS quantite_prevue
            FROM ville_besoin vb
            INNER JOIN besoin b ON b.id = vb.id_besoin
            WHERE vb.id_ville = ?
            ORDER BY b.nom";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_ville]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getBesoinsByVille($villeId)
    {
        $sql = "SELECT 
                vb.id,
                b.nom,
                vb.quantite,
                vb.dateB
            FROM ville_besoin vb
            JOIN besoin b ON vb.id_besoin = b.id
            WHERE vb.id_ville = ?
            ORDER BY vb.dateB DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }

    public function ajouterBesoinVille($id_ville, $id_besoin, $quantite, $dateB)
    {
        $sql = "INSERT INTO ville_besoin (id_ville, id_besoin, quantite, dateB) 
            VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_ville, $id_besoin, $quantite, $dateB]);
    }

    public function supprimerBesoinVille($id)
    {
        $sql = "DELETE FROM ville_besoin WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>