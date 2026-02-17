<?php
namespace app\models;

class Besoin
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Retourne les besoins d'une ville (id, nom, quantite, date)
    public function getBesoinsByVille($id_ville)
    {
        $sql = "SELECT b.id, b.nom, b.prix, b.id_type_besoin, vb.quantite, vb.dateB FROM ville_besoin vb INNER JOIN besoin b ON b.id = vb.id_besoin WHERE vb.id_ville = ? ORDER BY vb.dateB DESC, b.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll();
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
        // Total des besoins (en valeur)
        $sqlBesoins = "SELECT SUM(vb.quantite * b.prix) AS total_besoins 
                   FROM ville_besoin vb
                   JOIN besoin b ON vb.id_besoin = b.id";
        $stmt = $this->db->query($sqlBesoins);
        $totalBesoins = $stmt->fetch()['total_besoins'] ?? 0;

        // Total des dons reçus (en valeur)
        $sqlDons = "SELECT SUM(d.quantite * b.prix) AS total_dons 
                FROM don d
                JOIN besoin b ON d.id_besoin = b.id";
        $stmt = $this->db->query($sqlDons);
        $totalDons = $stmt->fetch()['total_dons'] ?? 0;

        // Total des achats effectués (en valeur)
        $sqlAchats = "SELECT SUM(montant_total) AS total_achats FROM achat";
        $stmt = $this->db->query($sqlAchats);
        $totalAchats = $stmt->fetch()['total_achats'] ?? 0;

        // Ce qui reste = besoins - (dons utilisables pour achats)
        // Les dons en argent sont convertis en achats
        return max(0, $totalBesoins - $totalAchats);
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

    public function getDetailBesoinVille($ville_id = null)
    {
        if ($ville_id) {
            $sql = "SELECT * FROM detail_besoin_ville WHERE ville_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ville_id]);
            return $stmt->fetchAll();
        } else {
            $sql = "SELECT * FROM detail_besoin_ville";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
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

    public function recapitulatifParVille()
    {
        $sql = "SELECT v.id,
                       v.nom AS ville,
                       COALESCE(besoins.montant_besoins, 0) AS montant_besoin,
                       COALESCE(dons.montant_dons, 0) AS montant_satisfait
                FROM ville v
                LEFT JOIN (
                    SELECT vb.id_ville, SUM(vb.quantite * b.prix) AS montant_besoins
                    FROM ville_besoin vb
                    JOIN besoin b ON b.id = vb.id_besoin
                    GROUP BY vb.id_ville
                ) besoins ON besoins.id_ville = v.id
                LEFT JOIN (
                    SELECT d.id_ville, SUM(d.quantite * b.prix) AS montant_dons
                    FROM don d
                    JOIN besoin b ON b.id = d.id_besoin
                    GROUP BY d.id_ville
                ) dons ON dons.id_ville = v.id
                ORDER BY v.nom";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function besoinsParVilleChrono()
    {
        $sql = "SELECT vb.id_ville,
                       v.nom AS ville,
                       vb.id_besoin,
                       b.nom AS besoin,
                       vb.quantite,
                       vb.dateB
                FROM ville_besoin vb
                JOIN ville v ON v.id = vb.id_ville
                JOIN besoin b ON b.id = vb.id_besoin
                ORDER BY vb.dateB ASC, v.nom";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>