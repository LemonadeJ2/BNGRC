<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Achat;
use app\models\Ville;
use app\models\Besoin;

class AchatController
{
    protected Engine $app;
    private $achatModel;
    private $villeModel;
    private $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->achatModel = new Achat(Flight::db());
        $this->villeModel = new Ville(Flight::db());
        $this->besoinModel = new Besoin(Flight::db());
    }

    public function pageAchats()
    {
        $besoinsRestants = $this->achatModel->getBesoinsRestants();
        $donsRestants = $this->achatModel->getDonsRestants();
        $totalDonsArgent = $this->achatModel->getTotalDonsArgent();
        $totalAchats = $this->achatModel->getTotalAchats();
        $frais = $this->achatModel->getFraisAchat();
        $villes = $this->villeModel->getAllVilles();

        // Récupérer les achats (filtrés si nécessaire)
        $ville_filter = $_GET['ville'] ?? null;
        $achats = $this->achatModel->getAchatsFiltrables($ville_filter);

        $admin = $_SESSION['admin'] ?? null;

        $this->app->render('achats.php', [
            'besoinsRestants' => $besoinsRestants,
            'donsRestants' => $donsRestants,
            'totalDonsArgent' => $totalDonsArgent,
            'totalAchats' => $totalAchats,
            'frais' => $frais,
            'villes' => $villes,
            'achats' => $achats,
            'ville_filter' => $ville_filter,
            'admin' => $admin
        ]);
    }

    public function updateFrais()
    {
        $nouveau_frais = $_POST['frais'] ?? 10.00;

        if ($nouveau_frais < 0 || $nouveau_frais > 100) {
            $_SESSION['message'] = 'Le frais doit être entre 0 et 100%';
            $_SESSION['message_type'] = 'danger';
            $this->app->redirect('/achats');
            return;
        }

        $result = $this->achatModel->updateFraisAchat($nouveau_frais);

        if ($result) {
            $_SESSION['message'] = 'Frais mis à jour avec succès';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Erreur lors de la mise à jour';
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/achats');
    }

    public function effectuerAchat()
    {
        $id_ville = $_POST['id_ville'] ?? null;
        $id_besoin = $_POST['id_besoin'] ?? null;
        $quantite = $_POST['quantite'] ?? 0;
        $frais = $this->achatModel->getFraisAchat();

        if (!$id_ville || !$id_besoin || $quantite <= 0) {
            $_SESSION['message'] = 'Veuillez remplir tous les champs';
            $_SESSION['message_type'] = 'danger';
            $this->app->redirect('/achats');
            return;
        }

        $result = $this->achatModel->effectuerAchat($id_ville, $id_besoin, $quantite, $frais);

        if ($result['success']) {
            $_SESSION['message'] = $result['message'] . ' - Montant: ' . number_format($result['montant_total'], 0, ',', ' ') . ' Ar';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = $result['message'];
            $_SESSION['message_type'] = 'danger';
        }

        $this->app->redirect('/achats');
    }
}
?>