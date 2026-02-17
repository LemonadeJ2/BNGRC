<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Don;
use app\models\Besoin;
use app\models\Ville;

class DonController
{

    protected Engine $app;

    private $donModel;
    private $besoinModel;
    private $villeModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->donModel = new Don(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
        $this->villeModel = new Ville(Flight::db());
    }

    public function donRecus()
    {
        $dons = $this->donModel->totalDons();
        return $dons;
    }

    public function listeDonsAttribuesParVille()
    {
        $donsAttribues = [];
        $donsAttribues = $this->donModel->donsAttribuesParVille();

        return $donsAttribues;
    }

    public function gestionDons()
    {
        // Récupérer tous les dons avec détails
        $dons = $this->donModel->getAllDonsWithDetails();

        // Récupérer les villes et besoins pour les formulaires
        $villes = $this->villeModel->getAllVilles();
        $besoins = $this->besoinModel->getAllBesoins();

        // Statistiques des dons
        $totalDons = $this->donModel->totalDons();
        $nombreDons = count($dons);

        // Dons par ville pour le récapitulatif
        $donsParVille = $this->donModel->donsAttribuesParVille();

        // Organiser les dons par ville pour l'affichage
        $statsParVille = [];
        foreach ($donsParVille as $don) {
            $villeId = $don['id'];
            if (!isset($statsParVille[$villeId])) {
                $statsParVille[$villeId] = [
                    'ville' => $don['ville'],
                    'nombre_dons' => 0,
                    'montant_total' => 0
                ];
            }
            $statsParVille[$villeId]['nombre_dons']++;
            $statsParVille[$villeId]['montant_total'] += $don['montant_total'] ?? 0;
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('gestionDons.php', [
            'dons' => $dons,
            'villes' => $villes,
            'besoins' => $besoins,
            'totalDons' => $totalDons,
            'nombreDons' => $nombreDons,
            'statsParVille' => $statsParVille,
            'admin' => $admin
        ]);
    }

    public function ajouterDon()
    {
        $id_ville = $_POST['id_ville'] ?? null;
        $id_besoin = $_POST['id_besoin'] ?? null;
        $nom_donneur = $_POST['nom_donneur'] ?? '';
        $quantite = $_POST['quantite'] ?? 0;
        $date_don = $_POST['date_don'] ?? date('Y-m-d');

        if (!$id_ville || !$id_besoin || !$nom_donneur || !$quantite) {
            error_log("ERREUR: Champs manquants");
            $_SESSION['message'] = 'Tous les champs sont obligatoires';
            $_SESSION['message_type'] = 'danger';
            $this->app->redirect('/gestion-dons');
            return;
        }

        // Vérifier que le besoin existe et récupérer son type
        try {
            $sql = "SELECT b.*, t.type_besoin 
                FROM besoin b 
                LEFT JOIN type_besoin t ON b.id_type_besoin = t.id 
                WHERE b.id = ?";
            $stmt = Flight::db()->prepare($sql);
            $stmt->execute([$id_besoin]);
            $besoin = $stmt->fetch();

            error_log("Besoin trouvé: " . print_r($besoin, true));
        } catch (\Exception $e) {
            error_log("Erreur vérification besoin: " . $e->getMessage());
        }

        $result = $this->donModel->saisieDons($id_ville, $id_besoin, $nom_donneur, $quantite, $date_don);

        if ($result) {
            error_log("SUCCÈS: Don ajouté");
            $_SESSION['message'] = 'Don ajouté avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            error_log("ÉCHEC: Don non ajouté");
            $_SESSION['message'] = 'Erreur lors de l\'ajout du don';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/gestion-dons');
    }
    public function supprimerDon($id)
    {
        $result = $this->donModel->deleteDon($id);

        if ($result) {
            $_SESSION['message'] = 'Don supprimé avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la suppression';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/gestion-dons');
    }
}


?>