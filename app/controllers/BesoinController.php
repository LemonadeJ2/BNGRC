<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Besoin;
use app\models\Ville;
use \app\models\Achat;
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

        // Récupérer TOUTES les villes
        $toutesLesVilles = $this->villeModel->getAllVilles();

        // Récupérer les dons par ville
        $donsAttribues = $this->donController->listeDonsAttribuesParVille();

        // Organiser les dons par ID de ville (plusieurs dons par ville possible)
        $donsParVille = [];
        foreach ($donsAttribues as $don) {
            $villeId = $don['id'];
            if (!isset($donsParVille[$villeId])) {
                $donsParVille[$villeId] = [
                    'dons' => [],
                    'total_quantite' => 0
                ];
            }
            $donsParVille[$villeId]['dons'][] = $don;
            $donsParVille[$villeId]['total_quantite'] += $don['quantite'];
        }

        // Construire le tableau $donParVille avec TOUTES les villes
        $donParVille = [];
        foreach ($toutesLesVilles as $ville) {
            $villeId = $ville['id'];

            // Récupérer les besoins pour cette ville
            $nb = $this->besoinModel->nbBesoinsParVille($villeId);
            $listeBesoins = $this->besoinModel->getDetailBesoinVille($villeId);

            // Préparer les informations de dons
            if (isset($donsParVille[$villeId])) {
                // La ville a des dons
                foreach ($donsParVille[$villeId]['dons'] as $don) {
                    $donParVille[] = [
                        'id' => $villeId,
                        'ville' => $ville['nom'],
                        'nb_besoins' => $nb['nb_besoins'] ?? 0,
                        'liste_besoins' => $listeBesoins,
                        'besoin' => $don['besoin'],
                        'quantite' => $don['quantite']
                    ];
                }
            } else {
                // La ville n'a pas de dons
                $donParVille[] = [
                    'id' => $villeId,
                    'ville' => $ville['nom'],
                    'nb_besoins' => $nb['nb_besoins'] ?? 0,
                    'liste_besoins' => $listeBesoins,
                    'besoin' => 'Aucun don',
                    'quantite' => 0
                ];
            }
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('dashboard.php', [
            'totalBesoins' => $totalBesoins,
            'totalDons' => $totalDons,
            'resteACombler' => $resteACombler,
            'donParVille' => $donParVille,
            'admin' => $admin
        ]);
    }
}

?>