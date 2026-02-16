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

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->besoinModel = new Besoin(Flight::db());
        $this->villeModel = new Ville(Flight::db());
    }

    public function getAllBesoins()
    {
        $besoins = $this->besoinModel->getAllBesoins();
        $villes = $this->villeModel->getAllVilles();
        $this->app->render('besoins.php', ['besoins' => $besoins, 'villes' => $villes]);
    }

    public function insertBesoinsSinistresParVille()
    {
        $villeId = $_POST['ville_id'];
        $besoinId = $_POST['besoin_id'];
        $quantite = $_POST['quantite'];

        $result = $this->besoinModel->saisieBesoinsSinistresParVille($villeId, $besoinId, $quantite);

        if ($result) {
            $this->app->render('/', ['message' => 'Besoins saisis avec succès.']);
        } else {
            $this->app->render('/', ['message' => 'Erreur lors de la saisie des besoins.']);
        }
    }
}

?>