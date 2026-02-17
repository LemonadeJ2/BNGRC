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
        
        error_log("=== CHARGEMENT PAGE SIMULATION ===");
        error_log("userId: $userId");

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
            error_log("Simulation en cours trouvée: id={$simulationEnCours['id']}");
            
            $donnees['simulation'] = $simulationEnCours;

            // Récupérer les résultats
            $donnees['resultats'] = $this->simulationModel->getResultatsBySave($simulationEnCours['id']);
            error_log("Nombre de résultats trouvés: " . count($donnees['resultats']));

            // Récupérer les dons disponibles
            $donnees['donsDispos'] = $this->simulationModel->getDonsBySave($simulationEnCours['id']);
            error_log("Nombre de dons disponibles: " . count($donnees['donsDispos']));

            // Calculer les besoins restants après distribution
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
                    $besoinsRestants[] = [
                        'id_ville' => $besoin['id_ville'],
                        'id_besoin' => $besoin['id_besoin'],
                        'quantite' => $quantiteRestante,
                        'prix_unitaire' => $besoin['prix_unitaire'],
                        'type_besoin' => $besoin['type_besoin']
                    ];
                }
            }
            
            $donnees['besoinsRestants'] = $besoinsRestants;
            error_log("Besoins restants: " . count($besoinsRestants));
        } else {
            error_log("Aucune simulation en cours");
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
            error_log("=== DÉMARRAGE SIMULATION ===");
            error_log("userId: $userId");
            error_log("description: $description");

            // 1. Sauvegarder l'état actuel (dons, besoins, achats)
            $saveId = $this->simulationModel->sauvegarderEtatAvant($userId, $description);

            if (!$saveId) {
                throw new \Exception("Échec de la sauvegarde de l'état");
            }

            error_log("État sauvegardé avec saveId: $saveId");

            // 2. Lancer l'algorithme de distribution
            $result = $this->simulationModel->lancerSimulation($saveId);

            if (!$result) {
                throw new \Exception("Échec de l'algorithme de simulation");
            }

            // Récupérer les résultats pour afficher un message
            $resultats = $this->simulationModel->getResultatsBySave($saveId);
            $nombreDistributions = count($resultats);

            $_SESSION['message'] = "Simulation terminée avec succès! $nombreDistributions distributions proposées.";
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
        $userId = $_SESSION['admin'] ?? session_id();

        try {
            error_log("=== VALIDATION SIMULATION ===");
            
            $simulation = $this->simulationModel->getDerniereSauvegarde($userId);

            if (!$simulation) {
                throw new \Exception("Aucune simulation à valider");
            }

            error_log("Simulation trouvée: id={$simulation['id']}");

            $result = $this->simulationModel->validerSimulation($simulation['id']);

            if (!$result) {
                throw new \Exception("Erreur lors de la validation");
            }

            $_SESSION['message'] = 'Distribution validée avec succès! Les dons et besoins ont été mis à jour.';
            $_SESSION['message_type'] = 'success';

            // Nettoyer la sauvegarde actuelle pour permettre une nouvelle simulation
            // On ne supprime pas, on laisse juste la page de simulation réinitialiser

        } catch (\Exception $e) {
            error_log("Erreur validation: " . $e->getMessage());
            $_SESSION['message'] = 'Erreur lors de la validation: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }

    public function reinitialiser()
    {
        $userId = $_SESSION['admin'] ?? session_id();

        try {
            error_log("=== RÉINITIALISATION SIMULATION ===");

            $result = $this->simulationModel->reinitialiserDerniereValidation($userId);

            if (!$result) {
                throw new \Exception("Erreur lors de la réinitialisation");
            }

            $_SESSION['message'] = 'Retour à l\'état précédent effectué avec succès!';
            $_SESSION['message_type'] = 'success';

        } catch (\Exception $e) {
            error_log("Erreur réinitialisation: " . $e->getMessage());
            $_SESSION['message'] = 'Erreur lors de la réinitialisation: ' . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }
}
?>
