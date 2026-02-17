<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Besoin;

class RecapController
{
    protected Engine $app;

    private Besoin $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->besoinModel = new Besoin(Flight::db());
    }

    private function buildData(): array
    {
        $parVille = $this->besoinModel->recapitulatifParVille();

        $villes = [];
        $totalBesoins = 0.0;
        $totalSatisfaits = 0.0;

        foreach ($parVille as $row) {
            $besoin = (float) ($row['montant_besoin'] ?? 0);
            $satisfait = (float) ($row['montant_satisfait'] ?? 0);
            $couverture = $besoin > 0 ? ($satisfait / $besoin) * 100 : 0;
            $restant = max(0, $besoin - $satisfait);

            $totalBesoins += $besoin;
            $totalSatisfaits += $satisfait;

            $villes[] = [
                'ville' => $row['ville'],
                'besoin_total' => $besoin,
                'montant_satisfait' => $satisfait,
                'montant_restant' => $restant,
                'taux_couverture' => $couverture,
                'taux_restant' => $besoin > 0 ? ($restant / $besoin) * 100 : 0,
                'statut' => $this->statutLabel($couverture),
                'statut_tone' => $this->statutTone($couverture),
            ];
        }

        $totalRestant = max(0, $totalBesoins - $totalSatisfaits);
        $tauxCouverture = $totalBesoins > 0 ? ($totalSatisfaits / $totalBesoins) * 100 : 0;

        return [
            'lastUpdate' => date('d/m/Y à H:i'),
            'global' => [
                'besoins_totaux' => $totalBesoins,
                'besoins_satisfaits' => $totalSatisfaits,
                'besoins_restants' => $totalRestant,
                'taux_couverture' => $tauxCouverture
            ],
            'villes' => $villes,
            'totaux' => [
                'besoins_totaux' => $totalBesoins,
                'besoins_satisfaits' => $totalSatisfaits,
                'besoins_restants' => $totalRestant,
                'taux_couverture' => $tauxCouverture
            ]
        ];
    }

    private function statutLabel(float $taux): string
    {
        if ($taux >= 99.5) {
            return 'Complet';
        }

        if ($taux < 35) {
            return 'Critique';
        }

        return 'Partiel';
    }

    private function statutTone(float $taux): string
    {
        if ($taux >= 99.5) {
            return 'success';
        }

        if ($taux < 35) {
            return 'danger';
        }

        return 'warning';
    }

    public function showRecap()
    {
        $data = $this->buildData();
        $admin = $_SESSION['admin'] ?? 'Admin';

        $this->app->render('recap_dashboard.php', [
            'recapData' => $data,
            'admin' => $admin
        ]);
    }

    public function dataApi()
    {
        $data = $this->buildData();
        $this->app->json($data);
    }
}
?>
