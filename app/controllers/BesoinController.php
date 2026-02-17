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

        // Récupérer tous les achats
        $achatModel = new Achat(Flight::db());
        $achats = $achatModel->getAchatsFiltrables();

        // Organiser les achats par ville et par besoin
        $achatsParVille = [];
        foreach ($achats as $achat) {
            $villeId = $achat['id_ville'];
            $besoinId = $achat['id_besoin'];
            if (!isset($achatsParVille[$villeId])) {
                $achatsParVille[$villeId] = [];
            }
            if (!isset($achatsParVille[$villeId][$besoinId])) {
                $achatsParVille[$villeId][$besoinId] = 0;
            }
            $achatsParVille[$villeId][$besoinId] += $achat['quantite'];
        }

        // Organiser les dons par ville et par besoin
        $donsParVille = [];
        foreach ($donsAttribues as $don) {
            $villeId = $don['id'];
            $besoinNom = $don['besoin'];
            if (!isset($donsParVille[$villeId])) {
                $donsParVille[$villeId] = [];
            }
            if (!isset($donsParVille[$villeId][$besoinNom])) {
                $donsParVille[$villeId][$besoinNom] = 0;
            }
            $donsParVille[$villeId][$besoinNom] += $don['quantite'];
        }

        // Construire le tableau $donParVille avec TOUTES les villes
        $donParVille = [];

        foreach ($toutesLesVilles as $ville) {
            $villeId = $ville['id'];

            // Récupérer TOUS les besoins pour cette ville
            $tousBesoins = $this->besoinModel->getDetailBesoinVille($villeId);

            // Construire la liste des besoins avec les quantités originales (pour le visuel)
            // mais on va ajuster l'affichage en javascript plus tard si besoin
            $besoinsListe = [];
            foreach ($tousBesoins as $besoin) {
                $besoinsListe[] = [
                    'besoin' => $besoin['besoin'],
                    'quantite_prevue' => $besoin['quantite_prevue']  // Garder la valeur originale pour le visuel
                ];
            }

            // Compter les besoins restants après achats (pour le badge)
            $besoinsRestants = 0;
            foreach ($tousBesoins as $besoin) {
                $besoinNom = $besoin['besoin'];
                $besoinId = $besoin['id_besoin'];
                $quantite_originale = $besoin['quantite_prevue'] ?? 0;

                $donRecu = $donsParVille[$villeId][$besoinNom] ?? 0;
                $achatQuantite = $achatsParVille[$villeId][$besoinId] ?? 0;

                if ($quantite_originale - $donRecu - $achatQuantite > 0) {
                    $besoinsRestants++;
                }
            }

            // Récupérer le premier don pour cette ville (pour la colonne Dons attribués)
            $premierDon = null;
            $quantiteDon = 0;
            if (isset($donsParVille[$villeId])) {
                foreach ($donsAttribues as $don) {
                    if ($don['id'] == $villeId) {
                        $premierDon = $don['besoin'];
                        $quantiteDon = $don['quantite'];
                        break;
                    }
                }
            }

            $donParVille[] = [
                'id' => $villeId,
                'ville' => $ville['nom'],
                'nb_besoins' => $besoinsRestants,  // Ici le nombre de besoins ENCORE nécessaires
                'liste_besoins' => $besoinsListe,  // Liste complète des besoins (pour le visuel)
                'besoin' => $premierDon ?? 'Aucun don',
                'quantite' => $quantiteDon ?? 0
            ];
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('dashboard.php', [
            'totalBesoins' => $totalBesoins,
            'totalDons' => $totalDons,
            'resteACombler' => $resteACombler,
            'donParVille' => $donParVille,  // Structure identique à avant
            'admin' => $admin
        ]);
    }
}

?>