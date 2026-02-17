<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Simulation de distribution</title>

    <!-- Bootstrap CSS Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/style.css" rel="stylesheet">

    <style>
        .simulation-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .simulation-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .simulation-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .simulation-body {
            padding: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stat-value small {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--text-light);
        }

        .btn-simuler {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-simuler:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-valider {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-valider:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-reinit {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-reinit:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .action-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .badge-simulation {
            background-color: var(--primary-light);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .table-simulation {
            width: 100%;
        }

        .table-simulation th {
            padding: 1rem 1.5rem;
            font-size: 0.625rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8fafc;
        }

        .table-simulation td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: var(--text-dark);
            border-bottom: 1px solid #f1f5f9;
        }

        .table-simulation tbody tr:hover {
            background-color: #f8fafc;
        }

        .progress-simulation {
            height: 8px;
            background-color: #f1f5f9;
            border-radius: 4px;
            overflow: hidden;
            width: 100px;
        }

        .progress-fill {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            height: 100%;
            transition: width 0.3s ease;
        }

        .stat-detail {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-bar {
                flex-direction: column;
            }

            .btn-simuler,
            .btn-valider,
            .btn-reinit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="sidebar-title">
                    <h1>BNGRC</h1>
                    <p>Gestion des Risques</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="/dashboard" class="nav-item">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/villes-impactees" class="nav-item">
                    <i class="bi bi-building"></i>
                    <span>Villes Impactées</span>
                </a>
                <a href="/achats" class="nav-item">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Besoins Recensés</span>
                </a>
                <a href="/gestion-dons" class="nav-item">
                    <i class="bi bi-heart-fill"></i>
                    <span>Gestion des Dons</span>
                </a>
                <a href="/simulation" class="nav-item active">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Simulations </span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card">
                    <p class="user-label">Utilisateur</p>
                    <div class="user-info">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-BQEaeqadqYV_rHUDoRWHl5FlgJ7tGDeLrC-WjewY2YfXn8GJ7_hE0ifle9JTUd2Nx1aQh8nvxZmDHebyKjKiPkzE-8XjG5cFp91F_EUiM6wZ1P2ufP8REYOvBFvVskpWCLBNOnk2MGOTPDK9liL94-G4zSQE6Ym_qVbU2LKrWIMs2CGmwgVQOrhfAOxRMgBOa__mv9LEmRec2jusOGQS1AO5WrLu9yH5caOtT5J-RZk5joc3jJACj1JOQJtrrOFQLsc5ggFPXk"
                            alt="Admin" class="user-avatar">
                        <div class="user-details">
                            <p class="user-name">
                                <?= $admin ?>
                            </p>
                            <p class="user-role">Admin Central</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!-- Main content -->
        <main class="main-content">
            <div class="container-fluid p-4">
                <!-- Header -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                    <div>
                        <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">Simulation de distribution</h1>
                        <p style="margin: 0.5rem 0 0 0; color: var(--text-light);">Testez différents scénarios de
                            distribution avant validation</p>
                    </div>
                </div>

                <!-- Messages d'alerte -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show"
                        role="alert">
                        <?= $_SESSION['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                <?php endif; ?>

                <!-- État de la simulation -->
                <?php if (isset($donnees['simulation']) && $donnees['simulation']): ?>
                    <!-- Simulation en cours -->
                    <div class="simulation-card">
                        <div class="simulation-header">
                            <div>
                                <h3>Simulation en cours</h3>
                                <small style="color: var(--text-light);">
                                    Lancée le <?= date('d/m/Y à H:i', strtotime($donnees['simulation']['date_save'])) ?>
                                </small>
                            </div>
                            <span
                                class="badge-simulation"><?= htmlspecialchars($donnees['simulation']['description']) ?></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="action-bar">
                        <form method="post" action="/simulation/simuler" style="display: inline;">
                            <input type="hidden" name="description"
                                value="<?= htmlspecialchars($donnees['simulation']['description']) ?>">
                            <button type="submit" class="btn-simuler">
                                <i class="bi bi-arrow-repeat"></i>
                                Relancer la simulation
                            </button>
                        </form>

                        <?php if (!empty($donnees['resultats'])): ?>
                            <form method="post" action="/simulation/valider" style="display: inline;">
                                <button type="submit" class="btn-valider"
                                    onclick="return confirm('Êtes-vous sûr de vouloir valider cette distribution?');">
                                    <i class="bi bi-check-circle"></i>
                                    Valider la distribution
                                </button>
                            </form>

                            <a href="/simulation/reinitialiser" class="btn-reinit"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette simulation?');">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Annuler la simulation
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Statistiques -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Besoins totaux</div>
                            <div class="stat-value">
                                <?php
                                $totalBesoins = 0;
                                foreach ($donnees['besoinsRestants'] as $b) {
                                    $totalBesoins += $b['quantite'] * $b['prix_unitaire'];
                                }
                                echo number_format($totalBesoins, 0, ',', ' ');
                                ?>
                                <small>Ar</small>
                            </div>
                            <div class="stat-detail"><?= count($donnees['besoinsRestants']) ?? 0 ?> besoins restants</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Dons disponibles</div>
                            <div class="stat-value">
                                <?php
                                $totalDons = 0;
                                foreach ($donnees['donsDispos'] as $d) {
                                    $totalDons += $d['montant'];
                                }
                                echo number_format($totalDons, 0, ',', ' ');
                                ?>
                                <small>Ar</small>
                            </div>
                            <div class="stat-detail"><?= count($donnees['donsDispos'] ?? []) ?> dons</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Distribution proposée</div>
                            <div class="stat-value">
                                <?= count($donnees['resultats'] ?? []) ?>
                            </div>
                            <div class="stat-detail">Actions de distribution</div>
                        </div>
                    </div>

                    <!-- Résultats de la simulation par ville -->
                    <div class="simulation-card">
                        <div class="simulation-header">
                            <h3>Distribution proposée par ville</h3>
                            <?php if (isset($donnees['resultats']) && !empty($donnees['resultats'])): ?>
                                <span class="badge-simulation"><?= count($donnees['resultats']) ?> distributions</span>
                            <?php endif; ?>
                        </div>
                        <div class="simulation-body">
                            <?php if (isset($donnees['resultats']) && !empty($donnees['resultats'])): ?>
                                <div class="table-responsive">
                                    <table class="table-simulation">
                                        <thead>
                                            <tr>
                                                <th>Ville</th>
                                                <th>Besoin</th>
                                                <th>Quantité proposée</th>
                                                <th>Provenance</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Créer des tableaux associatifs pour les noms
                                            $villesMap = [];
                                            foreach ($donnees['villes'] as $v) {
                                                $villesMap[$v['id']] = $v['nom'];
                                            }
                                            $besoinsMap = [];
                                            foreach ($donnees['besoins'] as $b) {
                                                $besoinsMap[$b['id']] = $b['nom'];
                                            }
                                            ?>

                                            <?php foreach ($donnees['resultats'] as $resultat): ?>
                                                <tr>
                                                    <td><?= $villesMap[$resultat['id_ville']] ?? 'N/A' ?></td>
                                                    <td><?= $besoinsMap[$resultat['id_besoin']] ?? 'N/A' ?></td>
                                                    <td><?= number_format($resultat['quantite_proposee'], 0, ',', ' ') ?></td>
                                                    <td><?= htmlspecialchars($resultat['provenance']) ?></td>
                                                    <td><?= number_format($resultat['montant_utilise'], 0, ',', ' ') ?> Ar</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-3">Aucune distribution proposée. Lancez une simulation.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Dons disponibles après simulation -->
                    <div class="simulation-card mt-4">
                        <div class="simulation-header">
                            <h3>Dons disponibles</h3>
                        </div>
                        <div class="simulation-body">
                            <?php if (!empty($donnees['donsDispos'])): ?>
                                <div class="table-responsive">
                                    <table class="table-simulation">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Donneur</th>
                                                <th>Montant initial</th>
                                                <th>Montant utilisé</th>
                                                <th>Montant restant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donnees['donsDispos'] as $don): ?>
                                                <tr>
                                                    <td><?= $don['type_besoin'] ?? 'N/A' ?></td>
                                                    <td><?= htmlspecialchars($don['nom_donneur'] ?? 'Anonyme') ?></td>
                                                    <td><?= number_format($don['montant'], 0, ',', ' ') ?> Ar</td>
                                                    <td>
                                                        <?php
                                                        $montantUtilise = 0;
                                                        foreach ($donnees['resultats'] as $res) {
                                                            if (strpos($res['provenance'], $don['nom_donneur']) !== false) {
                                                                $montantUtilise += $res['montant_utilise'];
                                                            }
                                                        }
                                                        echo number_format($montantUtilise, 0, ',', ' ') . ' Ar';
                                                        ?>
                                                    </td>
                                                    <td class="text-success">
                                                        <?php
                                                        $montantRestant = $don['montant'] - $montantUtilise;
                                                        echo number_format(max(0, $montantRestant), 0, ',', ' ') . ' Ar';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-3">Aucun don disponible</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- État initial (pas de simulation) -->
                    <div class="simulation-card">
                        <div class="simulation-body text-center py-5">
                            <i class="bi bi-calculator" style="font-size: 4rem; color: var(--text-light);"></i>
                            <h4 class="mt-3">Aucune simulation en cours</h4>
                            <p class="text-muted">Cliquez sur "Lancer la simulation" pour tester différentes distributions
                            </p>
                            <form method="post" action="/simulation/simuler" style="display: inline;">
                                <input type="hidden" name="description" value="Simulation <?= date('d/m/Y H:i') ?>">
                                <button type="submit" class="btn-simuler mt-3">
                                    <i class="bi bi-play-fill"></i>
                                    Lancer la simulation
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Aperçu des données actuelles -->
                    <?php
                    // Récupérer les vraies données pour l'aperçu
                    $totalBesoins = 0;
                    $totalDons = 0;

                    // Calculer les vrais totaux depuis la base de données
                    try {
                        $db = Flight::db();

                        // Total des besoins
                        $sqlBesoins = "SELECT SUM(vb.quantite * b.prix) as total FROM ville_besoin vb JOIN besoin b ON vb.id_besoin = b.id";
                        $stmtBesoins = $db->query($sqlBesoins);
                        $resultBesoins = $stmtBesoins->fetch();
                        $totalBesoins = $resultBesoins['total'] ?? 0;

                        // Total des dons
                        $sqlDons = "SELECT SUM(d.quantite * b.prix) as total FROM don d JOIN besoin b ON d.id_besoin = b.id";
                        $stmtDons = $db->query($sqlDons);
                        $resultDons = $stmtDons->fetch();
                        $totalDons = $resultDons['total'] ?? 0;
                    } catch (\Exception $e) {
                        error_log("Erreur récupération données: " . $e->getMessage());
                    }
                    ?>

                    <div class="stats-grid mt-4">
                        <div class="stat-card">
                            <div class="stat-label">Besoins totaux</div>
                            <div class="stat-value">
                                <?= number_format($totalBesoins, 0, ',', ' ') ?>
                                <small>Ar</small>
                            </div>
                            <div class="stat-detail">À satisfaire</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Dons disponibles</div>
                            <div class="stat-value">
                                <?= number_format($totalDons, 0, ',', ' ') ?>
                                <small>Ar</small>
                            </div>
                            <div class="stat-detail"><?= count($donnees['villes'] ?? []) ?> villes concernées</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Reste à couvrir</div>
                            <div class="stat-value">
                                <?= number_format(max(0, $totalBesoins - $totalDons), 0, ',', ' ') ?>
                                <small>Ar</small>
                            </div>
                            <div class="stat-detail">Écart</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // Rafraîchissement automatique (optionnel) - désactivé pour test
        // setTimeout(function () {
        //     location.reload();
        // }, 300000); // 5 minutes
    </script>
</body>

</html>