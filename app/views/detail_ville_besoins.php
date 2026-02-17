<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Villes Impactées</title>

    <!-- Bootstrap CSS Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/style.css" rel="stylesheet">

    <style>
        .city-detail-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .city-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .city-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .needs-section {
            padding: 1.5rem;
        }

        .need-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .need-item:last-child {
            border-bottom: none;
        }

        .need-info {
            min-width: 200px;
        }

        .need-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
        }

        .need-info p {
            font-size: 0.75rem;
            color: var(--text-light);
            margin: 0;
        }

        .need-amounts {
            text-align: right;
            min-width: 180px;
        }

        .need-amounts .amount {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .need-amounts .unit {
            font-size: 0.625rem;
            color: var(--text-light);
            margin-left: 0.25rem;
        }

        .need-amounts .received {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all var(--transition-speed);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .summary-stats {
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

        .stat-detail {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        /* Styles pour le bouton Ajouter */
        .btn-add {
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
            margin-bottom: 1.5rem;
        }

        .btn-add:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Styles pour le modal (popup) */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .modal-container {
            background-color: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem 1.5rem 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: none;
        }

        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .modal-header p {
            font-size: 0.875rem;
            color: var(--text-light);
            margin: 0.25rem 0 0 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.75rem;
            line-height: 1;
            cursor: pointer;
            color: var(--text-light);
            transition: color var(--transition-speed);
        }

        .modal-close:hover {
            color: var(--text-dark);
        }

        .modal-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            border-top: none;
        }

        /* Styles pour le formulaire dans le modal */
        .field {
            margin-bottom: 1.25rem;
        }

        .field label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .field input,
        .field select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all var(--transition-speed);
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .field.two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .primary:hover {
            background: var(--primary-dark);
        }

        .ghost {
            background: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
            text-decoration: none;
        }

        .ghost:hover {
            background: #f8fafc;
        }

        @media (max-width: 768px) {
            .city-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .need-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .need-amounts {
                text-align: left;
                width: 100%;
            }

            .filter-bar {
                flex-direction: column;
                gap: 1rem;
            }

            .search-box {
                width: 100%;
            }

            .summary-stats {
                grid-template-columns: 1fr;
            }

            .field.two {
                grid-template-columns: 1fr;
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
                <a href="/villes-impactees" class="nav-item active">
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
                            <p class="user-name"><?= $_SESSION['admin'] ?? 'Cdt. Rakotoarisoa' ?></p>
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
                    <h2>Villes Impactées</h2>
                    <span class="badge badge-live"><?= count($villesData) ?> Villes</span>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="header-divider"></div>
                    <div class="settings-link">
                        <span>Paramètres</span>
                        <i class="bi bi-gear"></i>
                    </div>
                </div>
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

                <!-- Summary Stats -->
                <div class="summary-stats">
                    <div class="stat-card">
                        <div class="stat-label">Besoins Totaux</div>
                        <div class="stat-value"><?= number_format($stats['totalBesoins'], 0, ',', ' ') ?> Ar</div>
                        <div class="stat-detail">Toutes villes confondues</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Dons Reçus</div>
                        <div class="stat-value"><?= number_format($stats['totalDons'], 0, ',', ' ') ?> Ar</div>
                        <div class="stat-detail">Total des dons attribués</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Reste à Combler</div>
                        <div class="stat-value"><?= number_format($stats['resteACombler'], 0, ',', ' ') ?> Ar</div>
                        <div class="stat-detail">Besoins non satisfaits</div>
                    </div>
                </div>

                <!-- Bouton Ajouter -->
                <button class="btn-add" onclick="openModal()">
                    <i class="bi bi-plus-circle"></i>
                    Ajouter un besoin
                </button>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchVille" placeholder="Rechercher une ville..."
                            onkeyup="filterVilles()">
                    </div>
                </div>

                <!-- Cities List with Needs -->
                <?php foreach ($villesData as $ville): ?>
                    <div class="city-detail-card ville-card" data-nom="<?= strtolower($ville['ville']) ?>">
                        <div class="city-header">
                            <h3>
                                <i class="bi bi-geo-alt-fill me-2" style="color: var(--primary-color);"></i>
                                <?= htmlspecialchars($ville['ville']) ?>
                            </h3>
                            <span class="badge bg-info"><?= count($ville['besoins']) ?> besoins</span>
                        </div>

                        <!-- Besoins des villes -->
                        <div class="needs-section">
                            <?php foreach ($ville['besoins'] as $besoin): ?>
                                <div class="need-item">
                                    <div class="need-info">
                                        <h4><?= htmlspecialchars($besoin['nom']) ?></h4>
                                        <small class="text-muted">Original:
                                            <?= number_format($besoin['quantite_originale'] ?? 0, 0, ',', ' ') ?></small>
                                    </div>

                                    <div class="need-amounts">
                                        <div>
                                            <span
                                                class="amount"><?= number_format($besoin['quantite_restante'] ?? 0, 0, ',', ' ') ?></span>
                                            <span class="unit">restant</span>
                                        </div>
                                        <div class="received">
                                            <span class="text-success">Dons:
                                                <?= number_format($besoin['don_recu'] ?? 0, 0, ',', ' ') ?></span>
                                        </div>
                                        <div class="received">
                                            <span class="text-primary">Achats:
                                                <?= number_format($besoin['achat_quantite'] ?? 0, 0, ',', ' ') ?></span>
                                        </div>
                                        <div class="received text-info">
                                            Total satisfait:
                                            <?= number_format($besoin['quantite_satisfaite'] ?? 0, 0, ',', ' ') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (empty($ville['besoins'])): ?>
                                <div class="text-center text-muted py-3">
                                    Aucun besoin recensé pour cette ville
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <div class="card-footer mt-4">
                    <div class="pagination-info">
                        <p>Affichage de <?= count($villesData) ?> sur <?= count($villesData) ?> villes</p>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL POPUP POUR AJOUTER UN BESOIN -->
    <div id="besoinModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2>Ajouter un besoin</h2>
                    <p>Affecter un besoin à une ville</p>
                </div>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>

            <div class="modal-body">
                <form class="form" method="post" action="/ajouter-besoin-ville" id="besoinForm">
                    <div class="field">
                        <label for="id_ville">Ville</label>
                        <select id="id_ville" name="id_ville" required>
                            <option value="">Choisir une ville…</option>
                            <?php foreach ($villesData as $ville): ?>
                                <option value="<?= $ville['ville_id'] ?>">
                                    <?= htmlspecialchars($ville['ville']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="id_besoin">Besoin</label>
                        <select id="id_besoin" name="id_besoin" required>
                            <option value="">Choisir un besoin…</option>
                            <?php foreach ($allBesoins as $besoin): ?>
                                <option value="<?= $besoin['id'] ?>">
                                    <?= htmlspecialchars($besoin['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field two">
                        <div>
                            <label for="quantite">Quantité</label>
                            <input id="quantite" name="quantite" type="number" min="1" placeholder="Quantité" required>
                        </div>
                        <div>
                            <label for="dateB">Date</label>
                            <input id="dateB" name="dateB" type="date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="ghost" onclick="closeModal()">Annuler</button>
                        <button type="submit" class="primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        function openModal() {
            document.getElementById('besoinModal').style.display = 'flex';
            document.getElementById('besoinForm').reset();

            // Date du jour par défaut
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('dateB').value = today;
        }

        function closeModal() {
            document.getElementById('besoinModal').style.display = 'none';
        }

        document.getElementById('besoinModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeModal();
            }
        });

        // Filtrer les villes par recherche
        function filterVilles() {
            const searchText = document.getElementById('searchVille').value.toLowerCase();
            const villes = document.querySelectorAll('.ville-card');

            villes.forEach(ville => {
                const nomVille = ville.getAttribute('data-nom');
                if (nomVille.includes(searchText)) {
                    ville.style.display = 'block';
                } else {
                    ville.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>