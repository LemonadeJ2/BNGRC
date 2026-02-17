<?php
namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\Besoin;
use app\models\Don;
use app\models\Ville;

class SimulationController
{
    protected Engine $app;

    private Besoin $besoinModel;
    private Don $donModel;
    private Ville $villeModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->besoinModel = new Besoin(Flight::db());
        $this->donModel = new Don(Flight::db());
        $this->villeModel = new Ville(Flight::db());
    }

    public function page()
    {
        $result = $this->runSimulation('date');
        $admin = $_SESSION['admin'] ?? 'Admin';

        $this->app->render('simulation.php', [
            'simulation' => $result,
            'mode' => 'date',
            'admin' => $admin
        ]);
    }

    public function simulate()
    {
        $mode = $_POST['mode'] ?? 'date';
        $result = $this->runSimulation($mode);
        $this->app->json($result);
    }

    public function validate()
    {
        $mode = $_POST['mode'] ?? 'date';
        $result = $this->runSimulation($mode);
        $runId = $this->saveSimulation($mode, $result);

        $this->app->json([
            'saved' => true,
            'run_id' => $runId,
            'result' => $result
        ]);
    }

    public function reset()
    {
        $result = $this->runSimulation('date');
        $this->app->json($result);
    }

    private function runSimulation(string $mode): array
    {
        $stock = $this->donModel->stockParBesoin();
        $needs = $this->besoinModel->besoinsParVilleChrono();

        // Regroupe par besoin pour le mode proportionnel
        $needsByBesoin = [];
        foreach ($needs as $need) {
            $idBesoin = $need['id_besoin'];
            if (!isset($needsByBesoin[$idBesoin])) {
                $needsByBesoin[$idBesoin] = [];
            }
            $needsByBesoin[$idBesoin][] = $need;
        }

        $allocations = [];
        $remainingStock = $stock;

        if ($mode === 'proportionnel') {
            foreach ($needsByBesoin as $idBesoin => $items) {
                $stockBesoin = $remainingStock[$idBesoin] ?? 0;
                if ($stockBesoin <= 0) {
                    $this->recordZeroAllocations($allocations, $items);
                    continue;
                }

                $totalNeed = array_reduce($items, function ($carry, $item) {
                    return $carry + (float) $item['quantite'];
                }, 0.0);

                if ($totalNeed <= 0) {
                    $this->recordZeroAllocations($allocations, $items);
                    continue;
                }

                $baseAllocations = [];
                $remainders = [];
                $allocatedSum = 0;

                foreach ($items as $index => $item) {
                    $raw = ($item['quantite'] / $totalNeed) * $stockBesoin;
                    $base = floor($raw);
                    // Ne jamais dépasser le besoin demandé
                    $base = min($base, (float) $item['quantite']);
                    $baseAllocations[$index] = $base;
                    $remainders[$index] = $raw - $base;
                    $allocatedSum += $base;
                }

                $remainingForBesoin = max(0, $stockBesoin - $allocatedSum);

                // Distribuer le reste aux meilleurs restes décimaux
                arsort($remainders);
                foreach ($remainders as $index => $fraction) {
                    if ($remainingForBesoin <= 0) {
                        break;
                    }
                    $needLeft = (float) $items[$index]['quantite'] - $baseAllocations[$index];
                    if ($needLeft <= 0) {
                        continue;
                    }
                    $add = min(1, $remainingForBesoin, $needLeft);
                    $baseAllocations[$index] += $add;
                    $remainingForBesoin -= $add;
                }

                foreach ($items as $index => $item) {
                    $allocations[] = $this->buildAllocationRow($item, $baseAllocations[$index]);
                }

                $remainingStock[$idBesoin] = $remainingForBesoin;
            }
        } else {
            // Mode date : on prend les besoins par ordre chronologique
            foreach ($needs as $item) {
                $idBesoin = $item['id_besoin'];
                $available = $remainingStock[$idBesoin] ?? 0;
                $needed = (float) $item['quantite'];
                $allocated = min($available, $needed);
                $remainingStock[$idBesoin] = max(0, $available - $allocated);

                $allocations[] = $this->buildAllocationRow($item, $allocated);
            }
        }

        $byVille = $this->groupByVille($allocations);

        return [
            'mode' => $mode,
            'byVille' => $byVille,
            'stock' => $remainingStock,
            'summary' => $this->buildSummary($allocations, $stock, $remainingStock)
        ];
    }

    private function recordZeroAllocations(array &$allocations, array $items): void
    {
        foreach ($items as $item) {
            $allocations[] = $this->buildAllocationRow($item, 0);
        }
    }

    private function buildAllocationRow(array $item, float $allocated): array
    {
        $needed = (float) $item['quantite'];
        $remaining = max(0, $needed - $allocated);

        return [
            'ville' => $item['ville'],
            'id_ville' => $item['id_ville'],
            'besoin' => $item['besoin'],
            'id_besoin' => $item['id_besoin'],
            'demande' => $needed,
            'attribue' => $allocated,
            'restant' => $remaining,
            'date' => $item['dateB']
        ];
    }

    private function groupByVille(array $allocations): array
    {
        $grouped = [];
        foreach ($allocations as $row) {
            $ville = $row['ville'];
            if (!isset($grouped[$ville])) {
                $grouped[$ville] = [
                    'ville' => $ville,
                    'items' => [],
                    'total_attribue' => 0,
                    'total_demande' => 0,
                ];
            }
            $grouped[$ville]['items'][] = $row;
            $grouped[$ville]['total_attribue'] += $row['attribue'];
            $grouped[$ville]['total_demande'] += $row['demande'];
        }

        // Trier par nom de ville pour un affichage stable
        ksort($grouped);
        return array_values($grouped);
    }

    private function buildSummary(array $allocations, array $initialStock, array $remainingStock): array
    {
        $totalAttribue = array_reduce($allocations, function ($carry, $row) {
            return $carry + $row['attribue'];
        }, 0.0);

        return [
            'total_attribue' => $totalAttribue,
            'stock_initial' => $initialStock,
            'stock_restant' => $remainingStock
        ];
    }

    private function saveSimulation(string $mode, array $result): int
    {
        $db = Flight::db();
        $db->beginTransaction();

        $sqlRun = "INSERT INTO simulation_run (mode, created_at, stock_initial_json, stock_restant_json, total_attribue)
                   VALUES (?, NOW(), ?, ?, ?)";
        $stmtRun = $db->prepare($sqlRun);
        $stmtRun->execute([
            $mode,
            json_encode($result['summary']['stock_initial'] ?? []),
            json_encode($result['summary']['stock_restant'] ?? []),
            $result['summary']['total_attribue'] ?? 0
        ]);
        $runId = (int) $db->lastInsertId();

        $sqlAlloc = "INSERT INTO simulation_allocation
                      (run_id, id_ville, ville, id_besoin, besoin, demande, attribue, restant, date_demande)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtAlloc = $db->prepare($sqlAlloc);

        foreach ($result['byVille'] as $ville) {
            foreach ($ville['items'] as $item) {
                $stmtAlloc->execute([
                    $runId,
                    $item['id_ville'],
                    $item['ville'],
                    $item['id_besoin'],
                    $item['besoin'],
                    $item['demande'],
                    $item['attribue'],
                    $item['restant'],
                    $item['date']
                ]);
            }
        }

        $db->commit();
        return $runId;
    }
}
?>
