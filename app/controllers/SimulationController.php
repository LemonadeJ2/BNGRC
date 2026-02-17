<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Simulation;
use app\models\Ville;
use app\models\Besoin;

class SimulationController
{
    protected Engine $app;
    private $simulationModel;
    private $villeModel;
    private $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->simulationModel = new Simulation(Flight::db());
        $this->villeModel = new Ville(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
    }

    public function index()
    {
        $userId = $_SESSION['admin'] ?? session_id();
        $simulationEnCours = $this->simulationModel->getDerniereSauvegarde($userId);

        $villes = $this->villeModel->getAllVilles();
        $besoins = $this->besoinModel->getAllBesoins();

        $donnees = [
            'villes' => $villes,
            'besoins' => $besoins,
            'simulation' => null,
            'resultats' => [],
            'donsDispos' => [],
            'besoinsRestants' => []
        ];

        if ($simulationEnCours) {
            $donnees['simulation'] = $simulationEnCours;

            // Récupérer les résultats
            $donnees['resultats'] = $this->simulationModel->getResultatsBySave($simulationEnCours['id']);

            // DÉBOGAGE
            error_log("=== CHARGEMENT PAGE SIMULATION ===");
            error_log("Simulation ID: " . $simulationEnCours['id']);
            error_log("Nombre de résultats trouvés: " . count($donnees['resultats']));

            // Récupérer les dons
            $donnees['donsDispos'] = $this->simulationModel->getDonsBySave($simulationEnCours['id']);

            // Calculer les besoins restants
            $tousBesoins = $this->simulationModel->getBesoinsBySave($simulationEnCours['id']);
            $besoinsRestants = [];
            foreach ($tousBesoins as $besoin) {
                $quantiteRestante = $besoin['quantite'];
                foreach ($donnees['resultats'] as $resultat) {
                    if (
                        $resultat['id_ville'] == $besoin['id_ville'] &&
                        $resultat['id_besoin'] == $besoin['id_besoin']
                    ) {
                        $quantiteRestante -= $resultat['quantite_proposee'];
                    }
                }
                if ($quantiteRestante > 0) {
                    $besoinsRestants[] = $besoin;
                }
            }
            $donnees['besoinsRestants'] = $besoinsRestants;
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('simulation.php', [
            'donnees' => $donnees,
            'admin' => $admin
        ]);
    }
    public function simuler()
    {
        $userId = $_SESSION['admin'] ?? session_id();
        $description = $_POST['description'] ?? 'Simulation du ' . date('d/m/Y H:i');

        try {
            // 1. Sauvegarder l'état actuel
            $saveId = $this->simulationModel->sauvegarderEtatAvant($userId, $description);

            if (!$saveId) {
                throw new \Exception("Échec de la sauvegarde");
            }

            // 2. Récupérer les données pour la simulation
            $dons = $this->simulationModel->getDonsBySave($saveId);
            $besoins = $this->simulationModel->getBesoinsBySave($saveId);

            // 3. Algorithme de distribution (simplifié pour test)
            foreach ($besoins as $besoin) {
                $quantiteRestante = $besoin['quantite'];

                foreach ($dons as $don) {
                    if ($quantiteRestante <= 0)
                        break;

                    if ($don['quantite'] > 0 && $don['type_besoin'] == $besoin['type_besoin']) {
                        $quantitePrelevee = min($don['quantite'], $quantiteRestante);

                        $this->simulationModel->sauvegarderResultatSimulation(
                            $saveId,
                            $besoin['id_ville'],
                            $besoin['id_besoin'],
                            $quantitePrelevee,
                            "Don de " . ($don['nom_donneur'] ?? 'Anonyme'),
                            $quantitePrelevee * $besoin['prix_unitaire']
                        );

                        $quantiteRestante -= $quantitePrelevee;
                        // Note: on ne modifie pas $don ici car c'est une copie
                    }
                }
            }

            $_SESSION['message'] = 'Simulation terminée avec succès';
            $_SESSION['message_type'] = 'success';

        } catch (\Exception $e) {
            error_log("Erreur simulation: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            $_SESSION['message'] = 'Erreur lors de la simulation: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }

    public function valider()
    {
        $sessionId = session_id();
        $simulation = $this->simulationModel->getDerniereSauvegarde($sessionId);

        if (!$simulation) {
            $_SESSION['message'] = 'Aucune simulation à valider';
            $_SESSION['message_type'] = 'danger';
            $this->app->redirect('/simulation');
            return;
        }

        $result = $this->simulationModel->validerSimulation($simulation['id']);

        if ($result) {
            $_SESSION['message'] = 'Distribution validée avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la validation';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }

    public function reinitialiser()
    {
        $sessionId = session_id();
        $result = $this->simulationModel->reinitialiserDerniereValidation($sessionId);

        if ($result) {
            $_SESSION['message'] = 'Retour à l\'état précédent effectué';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la réinitialisation';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }
}
?>
