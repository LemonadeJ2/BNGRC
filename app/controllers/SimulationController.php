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
<<<<<<< HEAD
        
        error_log("=== CHARGEMENT PAGE SIMULATION ===");
        error_log("userId: $userId");

=======
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
            error_log("Simulation en cours trouvée: id={$simulationEnCours['id']}");
            
=======
>>>>>>> origin/rova_metier
            $donnees['simulation'] = $simulationEnCours;

            // Récupérer les résultats
            $donnees['resultats'] = $this->simulationModel->getResultatsBySave($simulationEnCours['id']);
<<<<<<< HEAD
            error_log("Nombre de résultats trouvés: " . count($donnees['resultats']));

            // Récupérer les dons disponibles
            $donnees['donsDispos'] = $this->simulationModel->getDonsBySave($simulationEnCours['id']);
            error_log("Nombre de dons disponibles: " . count($donnees['donsDispos']));

            // Calculer les besoins restants après distribution
            $tousBesoins = $this->simulationModel->getBesoinsBySave($simulationEnCours['id']);
            $besoinsRestants = [];
            
            foreach ($tousBesoins as $besoin) {
                $quantiteRestante = $besoin['quantite'];
                
=======

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
>>>>>>> origin/rova_metier
                foreach ($donnees['resultats'] as $resultat) {
                    if (
                        $resultat['id_ville'] == $besoin['id_ville'] &&
                        $resultat['id_besoin'] == $besoin['id_besoin']
                    ) {
                        $quantiteRestante -= $resultat['quantite_proposee'];
                    }
                }
<<<<<<< HEAD
                
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
=======
                if ($quantiteRestante > 0) {
                    $besoinsRestants[] = $besoin;
                }
            }
            $donnees['besoinsRestants'] = $besoinsRestants;
>>>>>>> origin/rova_metier
        }

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('simulation.php', [
            'donnees' => $donnees,
            'admin' => $admin
        ]);
    }
<<<<<<< HEAD

=======
>>>>>>> origin/rova_metier
    public function simuler()
    {
        $userId = $_SESSION['admin'] ?? session_id();
        $description = $_POST['description'] ?? 'Simulation du ' . date('d/m/Y H:i');

        try {
<<<<<<< HEAD
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
=======
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
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
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
=======
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
>>>>>>> origin/rova_metier
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }

    public function reinitialiser()
    {
<<<<<<< HEAD
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
=======
        $sessionId = session_id();
        $result = $this->simulationModel->reinitialiserDerniereValidation($sessionId);

        if ($result) {
            $_SESSION['message'] = 'Retour à l\'état précédent effectué';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la réinitialisation';
>>>>>>> origin/rova_metier
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/simulation');
    }
<<<<<<< HEAD
=======

    public function testSauvegardeEtape()
{
    $userId = $_SESSION['admin'] ?? session_id();
    $description = 'Test étape par étape';
    
    echo "<h3>Test de sauvegarde étape par étape</h3>";
    
    try {
        // Étape 1: Insertion dans sim_save
        echo "<h4>Étape 1: Insertion sim_save</h4>";
        $sql = "INSERT INTO sim_save (date_save, user_id, description) VALUES (NOW(), ?, ?)";
        $stmt = Flight::db()->prepare($sql);
        $result = $stmt->execute([$userId, $description]);
        
        if (!$result) {
            echo "Échec: " . print_r($stmt->errorInfo(), true);
            return;
        }
        
        $saveId = Flight::db()->lastInsertId();
        echo "Succès! saveId = $saveId<br><br>";
        
        // Étape 2: Insertion dans sim_don
        echo "<h4>Étape 2: Insertion sim_don</h4>";
        $sql = "INSERT INTO sim_don (id_save, id_don, id_ville, id_besoin, nom_donneur, quantite, montant, type_besoin)
                SELECT ?, id, id_ville, id_besoin, nom_donneur, quantite, 
                       quantite * (SELECT prix FROM besoin WHERE id = don.id_besoin) AS montant,
                       (SELECT type_besoin FROM type_besoin WHERE id = (SELECT id_type_besoin FROM besoin WHERE id = don.id_besoin))
                FROM don";
        $stmt = Flight::db()->prepare($sql);
        $result = $stmt->execute([$saveId]);
        
        if (!$result) {
            echo "Échec: " . print_r($stmt->errorInfo(), true);
            return;
        }
        
        $count = $stmt->rowCount();
        echo "Succès! $count dons sauvegardés<br><br>";
        
        // Étape 3: Insertion dans sim_besoin
        echo "<h4>Étape 3: Insertion sim_besoin</h4>";
        $sql = "INSERT INTO sim_besoin (id_save, id_ville_besoin, id_ville, id_besoin, quantite, prix_unitaire, type_besoin)
                SELECT ?, id, id_ville, id_besoin, quantite, 
                       (SELECT prix FROM besoin WHERE id = ville_besoin.id_besoin) AS prix_unitaire,
                       (SELECT type_besoin FROM type_besoin WHERE id = (SELECT id_type_besoin FROM besoin WHERE id = ville_besoin.id_besoin))
                FROM ville_besoin";
        $stmt = Flight::db()->prepare($sql);
        $result = $stmt->execute([$saveId]);
        
        if (!$result) {
            echo "Échec: " . print_r($stmt->errorInfo(), true);
            return;
        }
        
        $count = $stmt->rowCount();
        echo "Succès! $count besoins sauvegardés<br><br>";
        
        echo "<h3 style='color:green'>✓ TOUTES LES ÉTAPES RÉUSSIES</h3>";
        
    } catch (\Exception $e) {
        echo "<h3 style='color:red'>Exception: " . $e->getMessage() . "</h3>";
    }
    
    exit;
}
>>>>>>> origin/rova_metier
}
?>
