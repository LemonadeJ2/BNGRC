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
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
        }

        .text-success {
            color: #10b981;
        }

        .text-warning {
            color: #f59e0b;
        }

        .text-danger {
            color: #ef4444;
        }

        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .comparaison {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avant,
        .apres {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        .avant {
            background-color: #f1f5f9;
        }

        .apres {
            background-color: var(--primary-light);
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
    <div class="dashboard-wrapper">
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
                    <span>Simulations</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card">
                    <p class="user-label">Utilisateur</p>
                    <div class="user-info">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-BQEaeqadqYV_rHUDoRWHl5FlgJ7tGDeLrC-WjewY2YfXn8GJ7_hE0ifle9JTUd2Nx1aQh8nvxZmDHebyKjKiPkzE-8XjG5cFp91F_EUiM6wZ1P2ufP8REYOvBFvVskpWCLBNOnk2MGOTPDK9liL94-G4zSQE6Ym_qVbU2LKrWIMs2CGmwgVQOrhfAOxRMgBOa__mv9LEmRec2jusOGQS1AO5WrLu9yH5caOtT5J-RZk5joc3jJACj1JOQJtrrOFQLsc5ggFPXk"
                            alt="Admin" class="user-avatar">
                        <div class="user-details">
                            <p class="user-name"><?= $admin ?></p>
                            <p class="user-role">Admin Central</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <h2>Simulation de distribution</h2>
                    <span class="badge badge-live">Test & Validation</span>
                </div>
                <!-- <div class="header-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="header-divider"></div>
                    <a href="/logout" class="settings-link text-decoration-none">
                        <span>Déconnexion</span>
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div> -->
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Messages flash -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                endif;
                ?>

                <!-- Barre d'actions -->
                <div class="action-bar">
                    <form method="post" action="/simulation/simuler" style="display: inline;">
                        <input type="hidden" name="description" value="Simulation <?= date('d/m/Y H:i') ?>">
                        <button type="submit" class="btn-simuler">
                            <i class="bi bi-play-fill"></i>
                            Lancer la simulation
                        </button>
                    </form>

                    <?php if (isset($donnees['simulation'])): ?>
                        <form method="post" action="/simulation/valider" style="display: inline;">
                            <button type="submit" class="btn-valider">
                                <i class="bi bi-check-lg"></i>
                                Valider la distribution
                            </button>
                        </form>

                        <a href="/simulation/reinitialiser" class="btn-reinit"
                            onclick="return confirm('Réinitialiser la simulation ?')">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Réinitialiser
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (isset($donnees['simulation'])): ?>
                    <!-- En-tête de simulation -->
                    <div class="simulation-card">
                        <div class="simulation-header">
                            <h3>
                                <i class="bi bi-diagram-3 me-2" style="color: var(--primary-color);"></i>
                                Simulation en cours
                            </h3>
                            <span class="badge-simulation">
                                <?= date('d/m/Y H:i', strtotime($donnees['simulation']['date_save'])) ?>
                            </span>
                        </div>
                        <div class="simulation-body">
                            <p><?= htmlspecialchars($donnees['simulation']['description']) ?></p>
                        </div>
                    </div>

                    <!-- Statistiques de la simulation -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Dons disponibles</div>
                            <div class="stat-value">
                                <?= number_format(array_sum(array_column($donnees['donsDispos'] ?? [], 'montant')), 0, ',', ' ') ?>
                                Ar
                            </div>
                            <div class="stat-detail"><?= count($donnees['donsDispos'] ?? []) ?> dons</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Besoins restants</div>
                            <div class="stat-value">
                                <?= count($donnees['besoinsRestants'] ?? []) ?>
                            </div>
                            <div class="stat-detail">Non satisfaits</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Distribution proposée</div>
                            <div class="stat-value">
                                <?= count($donnees['resultats'] ?? []) ?>
                            </div>
                            <div class="stat-detail">Actions</div>
                        </div>
                    </div>

                    <!-- Résultats de la simulation par ville -->
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

                    <!-- Dons disponibles -->
                    <div class="simulation-card mt-4">
                        <div class="simulation-header">
                            <h3>Dons disponibles après simulation</h3>
                        </div>
                        <div class="simulation-body">
                            <div class="table-responsive">
                                <table class="table-simulation">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Donneur</th>
                                            <th>Montant initial</th>
                                            <th>Montant restant</th>
                                            <th>Utilisation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donnees['donsDispos'] as $don): ?>
                                            <tr>
                                                <td><?= $don['type_besoin'] ?? 'N/A' ?></td>
                                                <td><?= htmlspecialchars($don['nom_donneur'] ?? 'Anonyme') ?></td>
                                                <td><?= number_format($don['montant'], 0, ',', ' ') ?> Ar</td>
                                                <td class="text-success">
                                                    <?= number_format($don['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                                <td>
                                                    <?php
                                                    $pourcentage = ($don['montant'] - $don['montant_restant']) / $don['montant'] * 100;
                                                    ?>
                                                    <div class="progress-simulation">
                                                        <div class="progress-fill" style="width: <?= $pourcentage ?>%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($donnees['donsDispos'])): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    Aucun don disponible
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
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
                                    Commencer une simulation
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Aperçu des données actuelles -->
                    <div class="stats-grid mt-4">
                        <?php
                        $totalBesoins = 0;
                        $totalDons = 0;
                        foreach ($donnees['villes'] as $ville) {
                            // À remplacer par de vraies données
                            $totalBesoins += rand(1000000, 5000000);
                        }
                        foreach ($donnees['besoins'] as $besoin) {
                            $totalDons += rand(500000, 2000000);
                        }
                        ?>
                        <div class="stat-card">
                            <div class="stat-label">Besoins totaux</div>
                            <div class="stat-value"><?= number_format($totalBesoins, 0, ',', ' ') ?> Ar</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Dons disponibles</div>
                            <div class="stat-value"><?= number_format($totalDons, 0, ',', ' ') ?> Ar</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Villes concernées</div>
                            <div class="stat-value"><?= count($donnees['villes']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // Rafraîchissement automatique (optionnel)
        setTimeout(function () {
            location.reload();
        }, 300000); // 5 minutes
    </script>
</body>

</html>
