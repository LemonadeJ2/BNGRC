<?php
function abrevVille($nomVille)
{
    $nomVille = strtoupper($nomVille);
    return substr($nomVille, 0, 3);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC Dashboard Overview</title>

    <!-- Bootstrap CSS Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1./font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Sélectionner tous les liens "+ X autres"
            const toggleLinks = document.querySelectorAll(".needs-detail a.text-primary");

            toggleLinks.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();

                    // Le conteneur parent des besoins
                    const container = link.parentElement;
                    // Tous les <small> sauf les 3 premiers
                    const hiddenItems = container.querySelectorAll("small.hidden");

                    if (hiddenItems.length === 0) {
                        // Masquer tous les items au-delà du 3e
                        const items = container.querySelectorAll("small.d-block");
                        items.forEach((item, index) => {
                            if (index >= 3) item.classList.add("hidden");
                        });
                    }

                    // Basculer l'affichage des éléments cachés
                    hiddenItems.forEach(item => {
                        item.classList.toggle("hidden");
                    });

                    // Changer le texte du lien
                    if (link.textContent.includes("+")) {
                        link.textContent = "Masquer";
                    } else {
                        // Remettre le texte original
                        const count = hiddenItems.length > 0 ? hiddenItems.length : container.querySelectorAll("small.d-block").length - 3;
                        link.textContent = `+ 5 autres`;
                    }
                });
            });
        });
    </script>

    <style>
        small.hidden {
            display: none;
        }
    </style>

</head>

<body>

    <!-- Layout Wrapper -->
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
                <a href="/dashboard" class="nav-item active">
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
                <a href="#" class="nav-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Simulations d'Impact</span>
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

        <!-- Main Content Area -->
        <main class="main-content">

            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <h2>Système de Suivi des Dons</h2>
                    <span class="badge badge-live">Live Updates</span>
                </div>
                <!-- <div class="header-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="settings-link">
                        <span>Paramètres</span>
                        <i class="bi bi-gear"></i>
                    </div>
                </div> -->
            </header>

            <!-- Dashboard Content -->
            <div class="content-area">

                <!-- KPI Summary Cards -->
                <div class="row g-4 mb-4">

                    <!-- Total Needs -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card kpi-card">
                            <div class="card-body">
                                <div class="kpi-header">
                                    <span class="kpi-label">Besoins Totaux</span>
                                    <i class="bi bi-exclamation-triangle kpi-icon text-warning"></i>
                                </div>
                                <h3 class="kpi-value"><?= $totalBesoins ?> Ar</h3>
                                <!-- <div class="kpi-footer">
                                    <span class="kpi-trend trend-up">
                                        <i class="bi bi-arrow-up"></i>
                                        <span>+12%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Donations Received -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card kpi-card">
                            <div class="card-body">
                                <div class="kpi-header">
                                    <span class="kpi-label">Dons Reçus</span>
                                    <i class="bi bi-check-circle kpi-icon text-success"></i>
                                </div>
                                <h3 class="kpi-value"><?= $totalDons ?> Ar</h3>
                                <!-- <div class="kpi-footer">
                                    <span class="kpi-trend trend-up">
                                        <i class="bi bi-arrow-up"></i>
                                        <span>+24%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Remaining Gap -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card kpi-card">
                            <div class="card-body">
                                <div class="kpi-header">
                                    <span class="kpi-label">Reste à Combler</span>
                                    <i class="bi bi-x-circle kpi-icon text-danger"></i>
                                </div>
                                <h3 class="kpi-value"><?= $resteACombler ?> Ar</h3>
                                <!-- <div class="kpi-footer">
                                    <span class="kpi-trend trend-down">
                                        <i class="bi bi-arrow-down"></i>
                                        <span>-8%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Satisfaction Rate -->
                    <!-- <div class="col-12 col-md-6 col-lg-3">
                        <div class="card kpi-card">
                            <div class="card-body">
                                <div class="kpi-header">
                                    <span class="kpi-label">Taux de Satisfaction</span>
                                    <i class="bi bi-bar-chart kpi-icon text-info"></i>
                                </div>
                                <h3 class="kpi-value">59.6%</h3>
                                <div class="kpi-footer">
                                    <span class="kpi-trend trend-up">
                                        <i class="bi bi-arrow-up"></i>
                                        <span>+5.2%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>

                <!-- Districts Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Villes et leurs besoins - Dons attribués</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover districts-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Ville</th>
                                        <th>Besoins</th>
                                        <th>Détails besoins</th>
                                        <th>Dons attribués</th>
                                        <!-- <th>Reste à couvrir</th> -->
                                        <!-- <th>Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Antananarivo avec détails des besoins -->
                                    <?php foreach ($donParVille as $liste): ?>
                                        <tr>
                                            <td>
                                                <div class="district-cell">
                                                    <div class="district-avatar"><?= abrevVille($liste['ville']) ?></div>
                                                    <span class="district-name"><?= $liste['ville'] ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= $liste['nb_besoins'] ?> besoin(s)</span>
                                            </td>
                                            <td>
                                                <div class="needs-detail">
                                                    <?php foreach ($liste['liste_besoins'] as $besoin): ?>
                                                        <small class="d-block">
                                                            <?= $besoin['besoin'] ?>:
                                                            <?= number_format($besoin['quantite_prevue'], 0, ',', ' ') ?>
                                                        </small>
                                                    <?php endforeach; ?>
                                                    <!-- <small class="d-block"><i class="bi bi-tools"></i> Tôles: 1,000
                                                        unités</small>
                                                    <small class="d-block"><i class="bi bi-cash"></i> Argent: 50,000,000
                                                        Ar</small> -->
                                                    <?php if ($liste['nb_besoins'] != 0) { ?>
                                                        <a href="#" class="text-primary small">+ 5 autres</a>
                                                    <?php } else { ?>
                                                        <small>Aucun besoin.</small>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="donations-detail">
                                                    <small class="d-block text-success"><i class="bi bi-basket"></i>
                                                        <?= $liste['besoin'] ?>:
                                                        <?= $liste['quantite'] ?></small>
                                                    <!-- <small class="d-block text-success"><i class="bi bi-tools"></i> Tôles:
                                                        300 unités</small>
                                                    <small class="d-block text-success"><i class="bi bi-cash"></i> Argent:
                                                        22.5M Ar</small> -->
                                                </div>
                                            </td>
                                            <!-- <td>
                                                <span class="text-danger fw-bold">-27.8M Ar</span>
                                            </td> -->
                                            <!-- <td>
                                                <button class="btn btn-sm btn-action">Détails</button>
                                            </td> -->
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- Toamasina -->
                                    <!-- <tr>
                                        <td>
                                            <div class="district-cell">
                                                <div class="district-avatar">TOAM</div>
                                                <span class="district-name">Toamasina</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">5 besoins</span>
                                        </td>
                                        <td>
                                            <div class="needs-detail">
                                                <small class="d-block"><i class="bi bi-droplet"></i> Huile: 1,000
                                                    litres</small>
                                                <small class="d-block"><i class="bi bi-pin-angle"></i> Clous: 500
                                                    kg</small>
                                                <small class="d-block"><i class="bi bi-cash"></i> Argent: 25,000,000
                                                    Ar</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="donations-detail">
                                                <small class="d-block text-success"><i class="bi bi-droplet"></i> Huile:
                                                    800 litres</small>
                                                <small class="d-block text-success"><i class="bi bi-pin-angle"></i>
                                                    Clous: 125 kg</small>
                                                <small class="d-block text-success"><i class="bi bi-cash"></i> Argent:
                                                    10M Ar</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-danger fw-bold">-15.3M Ar</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-action">Détails</button>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Bootstrap JS Local -->
    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>