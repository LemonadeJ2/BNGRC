<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Besoin;
use app\models\Ville;

class BesoinController
{
    protected Engine $app;
    private $besoinModel;
    private $villeModel;

    private $donController;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->besoinModel = new Besoin(Flight::db());
        $this->villeModel = new Ville(Flight::db());
        $this->donController = new DonController($app);
    }

    public function getAllBesoins()
    {
        $besoins = $this->besoinModel->getAllBesoins();
        $villes = $this->villeModel->getAllVilles();
        $this->app->render('besoins.php', ['besoins' => $besoins, 'villes' => $villes]);
    }

    public function insertBesoinsSinistresParVille()
    {
        $villeId = $_POST['ville_id'] ?? null;
        $besoinId = $_POST['besoin_id'] ?? null;
        $quantite = $_POST['quantite'] ?? null;

        $result = $this->besoinModel->saisieBesoinsSinistresParVille($villeId, $besoinId, $quantite);

        if ($result) {
            $this->app->render('message.php', ['message' => 'Besoins saisis avec succès.']);
        } else {
            $this->app->render('message.php', ['message' => 'Erreur lors de la saisie des besoins.']);
        }
    }

    public function besoinTotal()
    {
        $total = $this->besoinModel->totalBesoins();
        return $total;
    }

    public function dashboardWithTotal()
    {
        $totalBesoins = $this->besoinModel->totalBesoins();
        $totalDons = $this->donController->donRecus();
        $resteACombler = $this->besoinModel->resteAcombler();
        $donsAttribues = $this->donController->listeDonsAttribuesParVille();

        foreach ($donsAttribues as &$ville) {
            $nb = $this->besoinModel->nbBesoinsParVille($ville['id']);
            $ville['nb_besoins'] = $nb['nb_besoins'] ?? 0;
            $ville['liste_besoins'] = $this->besoinModel->listeBesoinsParVille($ville['id']);
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('dashboard.php', [
            'totalBesoins' => $totalBesoins,
            'totalDons' => $totalDons,
            'resteACombler' => $resteACombler,
            'donParVille' => $donsAttribues,
            'admin' => $admin
        ]);
    }
}
?>