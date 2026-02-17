<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Ville;
use app\models\Besoin;
use app\models\Don;
use app\models\Achat;

class VilleController
{
    protected Engine $app;
    protected $villeModel;
    protected $besoinModel;
    protected $donModel;
    protected $achatModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new Ville(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
        $this->donModel = new Don(Flight::db());
        $this->achatModel = new Achat(Flight::db());
    }

    public function villesImpactees()
    {
        // Récupérer toutes les villes
        $villes = $this->villeModel->getAllVilles();

        // Récupérer tous les dons attribués par ville
        $donsAttribues = $this->donModel->donsAttribuesParVille();

        // Organiser les dons par ville
        $donsParVille = [];
        foreach ($donsAttribues as $don) {
            $donsParVille[$don['id']][] = $don;
        }

        // Récupérer tous les achats
        $achatModel = new \app\models\Achat(Flight::db());
        $achats = $achatModel->getAchatsFiltrables();

        // Organiser les achats par ville
        $achatsParVille = [];
        foreach ($achats as $achat) {
            $achatsParVille[$achat['id_ville']][] = $achat;
        }

        $villesData = [];
        $totalBesoinsMontant = 0;
        $totalDonsMontant = 0;

        foreach ($villes as $ville) {
            // Récupérer les besoins pour cette ville
            $besoins = $this->besoinModel->getDetailBesoinVille($ville['id']);

            $besoinsData = [];

            foreach ($besoins as $b) {
                // Récupérer le prix depuis la table besoin
                $besoinInfo = $this->besoinModel->getBesoinById($b['id_besoin']);
                $prix = $besoinInfo['prix'] ?? 0;

                // Calcul du total reçu via dons
                $donRecu = 0;
                if (isset($donsParVille[$ville['id']])) {
                    foreach ($donsParVille[$ville['id']] as $don) {
                        if ($don['besoin'] == $b['besoin']) {
                            $donRecu += $don['quantite'];
                        }
                    }
                }

                // Calcul du total acheté
                $achatQuantite = 0;
                if (isset($achatsParVille[$ville['id']])) {
                    foreach ($achatsParVille[$ville['id']] as $achat) {
                        if ($achat['id_besoin'] == $b['id_besoin']) {
                            $achatQuantite += $achat['quantite'];
                        }
                    }
                }

                $quantite_originale = $b['quantite_prevue'] ?? 0;
                $quantite_restante = max(0, $quantite_originale - $donRecu - $achatQuantite);
                $quantite_satisfaite = $donRecu + $achatQuantite;

                // ICI on passe TOUTES les clés que la vue attend
                $besoinsData[] = [
                    'nom' => $b['besoin'],
                    'quantite_originale' => $quantite_originale,
                    'quantite_prevue' => $quantite_originale,
                    'don_recu' => $donRecu,
                    'achat_quantite' => $achatQuantite,
                    'quantite_restante' => $quantite_restante,
                    'quantite_satisfaite' => $quantite_satisfaite,
                    'reste' => $quantite_restante
                ];

                $totalBesoinsMontant += $quantite_originale * $prix;
                $totalDonsMontant += $donRecu * $prix;
            }

            $villesData[] = [
                'ville_id' => $ville['id'],
                'ville' => $ville['nom'],
                'besoins' => $besoinsData,
                'totalBesoins' => count($besoins)
            ];
        }

        // Statistiques globales
        $stats = [
            'totalBesoins' => $this->besoinModel->totalBesoins() ?? 0,
            'totalDons' => $this->donModel->totalDons() ?? 0,
            'resteACombler' => $this->besoinModel->resteAcombler() ?? 0,
            'typesBesoins' => count($this->besoinModel->getAllBesoins() ?? [])
        ];

        // Récupérer tous les besoins pour le formulaire
        $allBesoins = $this->besoinModel->getAllBesoins();

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('detail_ville_besoins.php', [
            'villesData' => $villesData,
            'stats' => $stats,
            'allBesoins' => $allBesoins,
            'admin' => $admin
        ]);
    }
}
?>