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
        $allBesoins = $this->besoinModel->getAllBesoins();

        // Organiser les dons par ville
        $donsParVille = [];
        foreach ($donsAttribues as $don) {
            $donsParVille[$don['id']][] = $don;
        }

        // Récupérer tous les achats
        $achats = $this->achatModel->getAchatsFiltrables();
        
        // Organiser les achats par ville
        $achatsParVille = [];
        foreach ($achats as $achat) {
            $achatsParVille[$achat['id_ville']][] = $achat;
        }

        $villesData = [];

        foreach ($villes as $ville) {
            // Récupérer les besoins originaux pour cette ville
            $besoinsOriginaux = $this->besoinModel->listeBesoinsParVille($ville['id']);
            
            // Récupérer les achats pour cette ville
            $achatsVille = $achatsParVille[$ville['id']] ?? [];

            $besoinsData = [];

            foreach ($besoinsOriginaux as $b) {
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
                foreach ($achatsVille as $achat) {
                    // Récupérer le nom du besoin pour cet achat
                    $sql = "SELECT nom FROM besoin WHERE id = ?";
                    $stmt = Flight::db()->prepare($sql);
                    $stmt->execute([$achat['id_besoin']]);
                    $besoinAchat = $stmt->fetch();
                    
                    if ($besoinAchat && $besoinAchat['nom'] == $b['besoin']) {
                        $achatQuantite += $achat['quantite'];
                    }
                }

                $quantite_originale = $b['quantite_prevue'] ?? 0;
                
                // La quantité restante = original - dons reçus - achats
                $quantite_restante = max(0, $quantite_originale - $donRecu - $achatQuantite);
                
                // La quantité déjà satisfaite = dons reçus + achats
                $quantite_satisfaite = $donRecu + $achatQuantite;

                $besoinsData[] = [
                    'nom' => $b['besoin'],
                    'quantite_originale' => $quantite_originale,
                    'quantite_satisfaite' => $quantite_satisfaite,
                    'quantite_restante' => $quantite_restante,
                    'don_recu' => $donRecu,
                    'achat_quantite' => $achatQuantite,
                    'reste' => $quantite_restante
                ];
            }

            // Calculer le total des dons pour cette ville
            $totalDonsVille = 0;
            if (isset($donsParVille[$ville['id']])) {
                foreach ($donsParVille[$ville['id']] as $don) {
                    $totalDonsVille += $don['quantite'];
                }
            }
            
            // Calculer le total des achats pour cette ville
            $totalAchatsVille = 0;
            foreach ($achatsVille as $achat) {
                $totalAchatsVille += $achat['quantite'];
            }

            $villesData[] = [
                'ville_id' => $ville['id'],
                'ville' => $ville['nom'],
                'besoins' => $besoinsData,
                'totalBesoins' => count($besoinsData),
                'totalBesoinsOriginaux' => count($besoinsOriginaux),
                'totalDons' => $totalDonsVille,
                'totalAchats' => $totalAchatsVille,
                'totalSatisfait' => $totalDonsVille + $totalAchatsVille
            ];
        }

        // Statistiques globales (mises à jour avec les achats)
        $totalBesoinsMontant = $this->besoinModel->totalBesoins() ?? 0;
        $totalDonsMontant = $this->donModel->totalDons() ?? 0;
        $totalAchatsMontant = $this->achatModel->getTotalAchats() ?? 0;
        
        // Les dons réellement disponibles pour les besoins
        $donsUtilisables = $totalDonsMontant; // Les dons en nature + argent
        
        $stats = [
            'totalBesoins' => $totalBesoinsMontant,
            'totalDons' => $totalDonsMontant,
            'totalAchats' => $totalAchatsMontant,
            'resteACombler' => max(0, $totalBesoinsMontant - $totalDonsMontant - $totalAchatsMontant),
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
}
?>