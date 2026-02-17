<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Achats</title>

    <!-- Bootstrap CSS Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/style.css" rel="stylesheet">

    <style>
        .card-achat {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-custom h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .card-body-custom {
            padding: 1.5rem;
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

        .filter-select {
            padding: 0.5rem 2rem 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-dark);
            background-color: white;
            cursor: pointer;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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

        .frais-form {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .frais-input {
            width: 100px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            text-align: right;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
        }

        .btn-success-custom {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-success-custom:hover {
            background: #059669;
        }

        .table-custom {
            width: 100%;
        }

        .table-custom th {
            padding: 1rem 1.5rem;
            font-size: 0.625rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8fafc;
        }

        .table-custom td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: var(--text-dark);
            border-bottom: 1px solid #f1f5f9;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .montant-restant {
            font-weight: 600;
            color: #059669;
        }

        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
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

            .frais-form {
                flex-direction: column;
                align-items: flex-start;
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
                    <h2>Achats - Utilisation des dons</h2>
                    <span class="badge badge-live">Gestion des achats</span>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="header-divider"></div>
                    <a href="/logout" class="settings-link text-decoration-none">
                        <span>Déconnexion</span>
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
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
                        <div class="stat-label">Dons disponibles</div>
                        <div class="stat-value"><?= number_format($donsRestants, 0, ',', ' ') ?> Ar</div>
                        <div class="stat-detail">Montant total des dons reçus</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Configuration des frais</div>
                        <div class="stat-value">
                            <form class="frais-form" method="post" action="/update-frais">
                                <input type="number" class="frais-input" name="frais" value="<?= $frais ?>" step="0.1"
                                    min="0" max="100" required>
                                <span>%</span>
                                <button type="submit" class="btn-primary-custom">
                                    <i class="bi bi-save"></i> Mettre à jour
                                </button>
                            </form>
                        </div>
                        <div class="stat-detail">Frais d'achat configurables</div>
                    </div>
                </div>

                <!-- Besoins restants par ville -->
                <div class="card-achat">
                    <div class="card-header-custom">
                        <h3><i class="bi bi-exclamation-triangle me-2" style="color: var(--primary-color);"></i>Besoins
                            restants par ville</h3>
                        <span class="badge bg-info"><?= count($besoinsRestants) ?> besoins</span>
                    </div>

                    <div class="card-body-custom">
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité restante</th>
                                        <th>Montant restant</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoinsRestants as $besoin): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($besoin['ville']) ?></td>
                                            <td><?= htmlspecialchars($besoin['besoin']) ?></td>
                                            <td><?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td><?= number_format($besoin['quantite_restante'], 0, ',', ' ') ?></td>
                                            <td class="montant-restant">
                                                <?= number_format($besoin['montant_restant'], 0, ',', ' ') ?> Ar</td>
                                            <td>
                                                <button class="btn-success-custom" onclick="openAchatModal(
                                                <?= $besoin['ville_id'] ?>,
                                                <?= $besoin['besoin_id'] ?>,
                                                '<?= htmlspecialchars($besoin['ville']) ?>',
                                                '<?= htmlspecialchars($besoin['besoin']) ?>',
                                                <?= $besoin['prix_unitaire'] ?>,
                                                <?= $besoin['quantite_restante'] ?>
                                            )">
                                                    <i class="bi bi-cart-plus"></i> Acheter
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($besoinsRestants)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Aucun besoin restant à satisfaire
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Achats effectués -->
                <div class="card-achat mt-4">
                    <div class="card-header-custom">
                        <h3><i class="bi bi-cart-check me-2" style="color: var(--primary-color);"></i>Achats effectués
                        </h3>
                    </div>

                    <div class="card-body-custom">
                        <!-- Filtre par ville -->
                        <div class="filter-bar mb-3">
                            <div class="search-box">
                                <i class="bi bi-filter"></i>
                                <select class="filter-select" id="villeFilter" onchange="filterByVille()"
                                    style="width: 100%; padding-left: 2.5rem;">
                                    <option value="">Toutes les villes</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>" <?= ($ville_filter == $ville['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ville['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Quantité</th>
                                        <th>Montant achat</th>
                                        <th>Frais (<?= $frais ?>%)</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($achats as $achat): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($achat['date_achat'])) ?></td>
                                            <td><?= htmlspecialchars($achat['ville']) ?></td>
                                            <td><?= htmlspecialchars($achat['besoin']) ?></td>
                                            <td><?= number_format($achat['quantite'], 0, ',', ' ') ?></td>
                                            <td><?= number_format($achat['montant_achat'], 0, ',', ' ') ?> Ar</td>
                                            <td><?= number_format(($frais / 100) * $achat['montant_achat'], 0, ',', ' ') ?> Ar</td>
                                            <td><strong><?= number_format($achat['montant_total'], 0, ',', ' ') ?>
                                                    Ar</strong></td>
                                            <td>
                                                <span class="badge-success">
                                                    <i class="bi bi-check-circle"></i> Effectué
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($achats)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Aucun achat effectué
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL POUR EFFECTUER UN ACHAT -->
    <div id="achatModal" class="modal-overlay"
        style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); align-items: center; justify-content: center;">
        <div class="modal-container"
            style="background-color: white; border-radius: 1rem; width: 90%; max-width: 500px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header"
                style="padding: 1.5rem 1.5rem 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Effectuer un achat</h2>
                    <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.25rem 0 0 0;">Utiliser les dons
                        pour acheter des besoins</p>
                </div>
                <button class="modal-close" onclick="closeAchatModal()"
                    style="background: none; border: none; font-size: 1.75rem; cursor: pointer;">&times;</button>
            </div>

            <div class="modal-body" style="padding: 1rem 1.5rem 1.5rem;">
                <form class="form" method="post" action="/effectuer-achat" id="achatForm">
                    <input type="hidden" name="id_ville" id="modal_id_ville">
                    <input type="hidden" name="id_besoin" id="modal_id_besoin">

                    <div class="field">
                        <label>Ville</label>
                        <div class="form-control-plaintext" id="modal_ville"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem;"></div>
                    </div>

                    <div class="field">
                        <label>Besoin</label>
                        <div class="form-control-plaintext" id="modal_besoin"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem;"></div>
                    </div>

                    <div class="field">
                        <label>Prix unitaire</label>
                        <div class="form-control-plaintext" id="modal_prix"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem;"></div>
                    </div>

                    <div class="field">
                        <label for="quantite">Quantité à acheter</label>
                        <input type="number" id="modal_quantite" name="quantite" min="1" max="" required
                            style="width: 100%; padding: 0.625rem 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem;"
                            onchange="calculerTotal()">
                        <small class="text-muted">Maximum: <span id="modal_max"></span></small>
                    </div>

                    <div class="field">
                        <label>Frais d'achat (<?= $frais ?>%)</label>
                        <div class="form-control-plaintext" id="modal_frais"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem;">0 Ar</div>
                    </div>

                    <div class="field">
                        <label>Montant total (avec frais)</label>
                        <div class="form-control-plaintext" id="modal_total"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem; font-weight: bold; color: var(--primary-color);">
                            0 Ar</div>
                    </div>

                    <div class="field">
                        <label>Dons disponibles</label>
                        <div class="form-control-plaintext"
                            style="padding: 0.625rem 0.75rem; background: #f8fafc; border-radius: 0.5rem;">
                            <?= number_format($donsRestants, 0, ',', ' ') ?> Ar
                        </div>
                    </div>

                    <div class="modal-footer"
                        style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                        <button type="button" class="ghost" onclick="closeAchatModal()"
                            style="padding: 0.625rem 1.25rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: transparent;">Annuler</button>
                        <button type="submit" class="primary"
                            style="padding: 0.625rem 1.25rem; background: var(--primary-color); color: white; border: none; border-radius: 0.5rem; font-weight: 600;">Confirmer
                            l'achat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Local -->
    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        let frais = <?= $frais ?>;
        let donsRestants = <?= $donsRestants ?>;

        function openAchatModal(villeId, besoinId, villeNom, besoinNom, prixUnitaire, maxQuantite) {
            document.getElementById('modal_id_ville').value = villeId;
            document.getElementById('modal_id_besoin').value = besoinId;
            document.getElementById('modal_ville').textContent = villeNom;
            document.getElementById('modal_besoin').textContent = besoinNom;
            document.getElementById('modal_prix').textContent = prixUnitaire.toLocaleString('fr-FR') + ' Ar';
            document.getElementById('modal_max').textContent = maxQuantite;

            document.getElementById('modal_quantite').max = maxQuantite;
            document.getElementById('modal_quantite').value = 1;

            calculerTotal(prixUnitaire);

            document.getElementById('achatModal').style.display = 'flex';
        }

        function closeAchatModal() {
            document.getElementById('achatModal').style.display = 'none';
        }

        function calculerTotal() {
            const quantite = parseInt(document.getElementById('modal_quantite').value) || 0;
            const prixText = document.getElementById('modal_prix').textContent;
            const prix = parseInt(prixText.replace(/[^0-9]/g, '')) || 0;

            const montantAchat = quantite * prix;
            const montantFrais = montantAchat * (frais / 100);
            const montantTotal = montantAchat + montantFrais;

            document.getElementById('modal_frais').textContent = montantFrais.toLocaleString('fr-FR') + ' Ar';
            document.getElementById('modal_total').textContent = montantTotal.toLocaleString('fr-FR') + ' Ar';

            // Vérifier si les dons sont suffisants
            if (montantTotal > donsRestants) {
                document.getElementById('modal_total').style.color = '#dc2626';
            } else {
                document.getElementById('modal_total').style.color = 'var(--primary-color)';
            }
        }

        function filterByVille() {
            const villeId = document.getElementById('villeFilter').value;
            if (villeId) {
                window.location.href = '/achats?ville=' + villeId;
            } else {
                window.location.href = '/achats';
            }
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('achatModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeAchatModal();
            }
        });
    </script>
</body>

</html>