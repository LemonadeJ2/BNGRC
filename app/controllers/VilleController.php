<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Ville;
use app\models\Besoin;
use app\models\Don;

class VilleController
{
    protected Engine $app;
    protected $villeModel;
    protected $besoinModel;
    protected $donModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new Ville(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
        $this->donModel = new Don(Flight::db());
    }

    public function villesImpactees()
    {
        // Récupérer toutes les villes
        $villes = $this->villeModel->getAllVilles();
        $allBesoins = $this->besoinModel->getAllBesoins();

        // Récupérer tous les dons attribués par ville
        $donsAttribues = $this->donModel->donsAttribuesParVille();

        // Organiser les dons par ville
        $donsParVille = [];
        foreach ($donsAttribues as $don) {
            $donsParVille[$don['id']][] = $don;
        }

        $villesData = [];

        foreach ($villes as $ville) {
            // Récupérer les besoins pour cette ville
            $besoins = $this->besoinModel->listeBesoinsParVille($ville['id']);

            $besoinsData = [];

            foreach ($besoins as $b) {
                // Calcul du total reçu pour ce besoin
                $donRecu = 0;
                if (isset($donsParVille[$ville['id']])) {
                    foreach ($donsParVille[$ville['id']] as $don) {
                        if ($don['besoin'] == $b['besoin']) {
                            $donRecu += $don['quantite'];
                        }
                    }
                }

                $quantite_prevue = $b['quantite_prevue'] ?? 0;
                $reste = max(0, $quantite_prevue - $donRecu);

                $besoinsData[] = [
                    'nom' => $b['besoin'],
                    'quantite_prevue' => $quantite_prevue,
                    'don_recu' => $donRecu,
                    'reste' => $reste
                ];
            }

            // Calculer le total des dons pour cette ville
            $totalDonsVille = 0;
            if (isset($donsParVille[$ville['id']])) {
                foreach ($donsParVille[$ville['id']] as $don) {
                    $totalDonsVille += $don['quantite'];
                }
            }

            $villesData[] = [
                'ville_id' => $ville['id'],
                'ville' => $ville['nom'],
                'besoins' => $besoinsData,
                'totalBesoins' => count($besoins),
                'totalDons' => $totalDonsVille
            ];
        }

        // Statistiques globales
        $stats = [
            'totalBesoins' => $this->besoinModel->totalBesoins() ?? 0,
            'totalDons' => $this->donModel->totalDons() ?? 0,
            'resteACombler' => $this->besoinModel->resteAcombler() ?? 0,
            'typesBesoins' => count($this->besoinModel->getAllBesoins() ?? [])
        ];

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('detail_ville_besoins.php', [
            'villesData' => $villesData,
            'stats' => $stats,
            'allBesoins' => $allBesoins,
            'admin' => $admin
        ]);
    }

    // Méthode pour obtenir les détails d'une ville spécifique 
    public function detailVille($id)
    {
        $ville = $this->villeModel->getVilleById($id);
        $besoins = $this->besoinModel->listeBesoinsParVille($id);
        $dons = $this->donModel->donsAttribuesParVille();

        // Filtrer les dons pour cette ville
        $donsVille = array_filter($dons, function ($don) use ($id) {
            return $don['ville_id'] == $id;
        });

        $this->app->render('detail_ville_besoins.php', [
            'ville' => $ville,
            'besoins' => $besoins,
            'dons' => $donsVille,
            'admin' => $_SESSION['admin'] ?? null
        ]);
    }
}
?>