<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Ville;
use app\models\Besoin;

class BesoinVilleController
{
    protected Engine $app;
    private $villeModel;
    private $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new Ville(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
    }

    public function detailVilleBesoins()
    {
        // Récupérer toutes les villes
        $villes = $this->villeModel->getAllVilles();

        // Récupérer tous les besoins
        $besoins = $this->besoinModel->getAllBesoins();

        // Récupérer les besoins par ville (à implémenter dans votre modèle)
        $villesData = [];
        foreach ($villes as $ville) {
            $besoinsVille = $this->besoinModel->getBesoinsByVille($ville['id']);
            $villesData[] = [
                'id' => $ville['id'],
                'nom' => $ville['nom'],
                'besoins' => $besoinsVille
            ];
        }

        // Compter le total des besoins
        $totalBesoinsVilles = 0;
        foreach ($villesData as $ville) {
            $totalBesoinsVilles += count($ville['besoins']);
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('detail_ville_besoins.php', [
            'villes' => $villes,
            'besoins' => $besoins,
            'villesData' => $villesData,
            'totalBesoinsVilles' => $totalBesoinsVilles,
            'admin' => $admin
        ]);
    }

    public function ajouterBesoinVille()
    {
        $id_ville = $_POST['id_ville'] ?? null;
        $id_besoin = $_POST['id_besoin'] ?? null;
        $quantite = $_POST['quantite'] ?? 0;
        $dateB = $_POST['dateB'] ?? date('Y-m-d');

        error_log("Ajout besoin: ville=$id_ville, besoin=$id_besoin, quantite=$quantite, date=$dateB");

        if (!$id_ville || !$id_besoin || !$quantite) {
            $_SESSION['message'] = 'Tous les champs sont obligatoires';
            $_SESSION['message_type'] = 'danger';
            // Rediriger vers la page des villes impactées
            $this->app->redirect('/villes-impactees');
            return;
        }

        $result = $this->besoinModel->ajouterBesoinVille($id_ville, $id_besoin, $quantite, $dateB);

        if ($result) {
            $_SESSION['message'] = 'Besoin ajouté avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de l\'ajout';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/villes-impactees');
    }

    public function supprimerBesoinVille($id)
    {
        error_log("Suppression besoin ville ID: $id");
        $result = $this->besoinModel->supprimerBesoinVille($id);

        if ($result) {
            $_SESSION['message'] = 'Besoin supprimé avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la suppression';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/villes-impactees');
    }
}
?>