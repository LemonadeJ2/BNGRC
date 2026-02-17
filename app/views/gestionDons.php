<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des Dons</title>
    
    <!-- Bootstrap CSS Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons Local -->
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/crud.css">

    <style>
        .donation-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .donation-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .donation-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .donation-section {
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

        .filter-options {
            display: flex;
            gap: 0.75rem;
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

        .table-dons {
            width: 100%;
        }

        .table-dons th {
            padding: 1rem 1.5rem;
            font-size: 0.625rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8fafc;
        }

        .table-dons td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: var(--text-dark);
            border-bottom: 1px solid #f1f5f9;
        }

        .table-dons tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-action {
            color: var(--primary-color);
            background-color: transparent;
            border: none;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            transition: all var(--transition-speed);
        }

        .btn-action:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn-action-danger {
            color: #dc2626;
        }

        .btn-action-danger:hover {
            background-color: rgba(220, 38, 38, 0.1);
            color: #b91c1c;
        }

        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Modal styles - Popup qui floute l'arrière */
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

        .btn-donation {
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

        .btn-donation:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Style pour les chips (supprimer seulement) */
        .chip {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #f1f5f9;
            color: var(--text-dark);
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed);
            text-decoration: none;
        }

        .chip--danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .chip--danger:hover {
            background-color: rgba(239, 68, 68, 0.2);
        }

        /* Style pour le formulaire */
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

        .field input, .field select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all var(--transition-speed);
        }

        .field input:focus, .field select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .field.two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
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
            
            .table-dons {
                font-size: 0.75rem;
            }
            
            .table-dons th, .table-dons td {
                padding: 0.75rem;
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
                <a href="/villes-impactees" class="nav-item">
                    <i class="bi bi-building"></i>
                    <span>Villes Impactées</span>
                </a>
                <a href="/achats" class="nav-item">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Besoins Recensés</span>
                </a>
                <a href="/gestion-dons" class="nav-item active">
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
                             alt="Admin" 
                             class="user-avatar">
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
                    <h2>Gestion des Dons</h2>
                    <span class="badge badge-live"><?= $nombreDons ?> Dons</span>
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
                
                <!-- Summary Stats -->
                <div class="summary-stats">
                    <div class="stat-card">
                        <div class="stat-label">Total Dons</div>
                        <div class="stat-value"><?= number_format($totalDons, 0, ',', ' ') ?> Ar</div>
                        <div class="stat-detail">Valeur totale des dons</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Nombre de Dons</div>
                        <div class="stat-value"><?= $nombreDons ?></div>
                        <div class="stat-detail">Transactions enregistrées</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Villes Bénéficiaires</div>
                        <div class="stat-value"><?= count($statsParVille) ?></div>
                        <div class="stat-detail">Villes ayant reçu des dons</div>
                    </div>
                </div>
                
                <!-- Bouton Faire une donation -->
                <button class="btn-donation" onclick="openDonationModal()">
                    <i class="bi bi-plus-circle"></i>
                    Faire une donation
                </button>
                
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchDon" placeholder="Rechercher un don..." onkeyup="filterDons()">
                    </div>
                    <div class="filter-options">
                        <select class="filter-select" id="filterVille" onchange="filterByVille()">
                            <option value="all">Toutes les villes</option>
                            <?php foreach ($villes as $ville): ?>
                            <option value="<?= strtolower($ville['nom']) ?>"><?= $ville['nom'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Tableau des dons -->
                <div class="donation-card">
                    <div class="donation-header">
                        <h3>Liste des dons enregistrés</h3>
                    </div>
                    
                    <div class="donation-section">
                        <div class="table-responsive">
                            <table class="table-dons" id="donsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ville</th>
                                        <th>Besoin</th>
                                        <th>Donneur</th>
                                        <th>Quantité</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dons as $don): ?>
                                    <tr class="don-row" 
                                        data-ville="<?= strtolower($don['ville']) ?>"
                                        data-besoin="<?= strtolower($don['besoin']) ?>"
                                        data-donneur="<?= strtolower($don['nom_donneur']) ?>">
                                        <td><?= $don['id'] ?></td>
                                        <td><?= $don['ville'] ?></td>
                                        <td><?= $don['besoin'] ?></td>
                                        <td><?= $don['nom_donneur'] ?></td>
                                        <td><?= number_format($don['quantite'], 2, ',', ' ') ?></td>
                                        <td><?= date('d/m/Y', strtotime($don['date_don'])) ?></td>
                                        <td>
                                            <a href="/supprimer-don/<?= $don['id'] ?>" 
                                               class="chip chip--danger"
                                               onclick="return confirm('Supprimer ce don ?')">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if (empty($dons)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Aucun don enregistré
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Récapitulatif par ville -->
                <div class="donation-card mt-4">
                    <div class="donation-header">
                        <h3>Récapitulatif des dons par ville</h3>
                    </div>
                    
                    <div class="donation-section">
                        <div class="table-responsive">
                            <table class="table-dons">
                                <thead>
                                    <tr>
                                        <th>Ville</th>
                                        <th>Nombre de dons</th>
                                        <th>Montant total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statsParVille as $stat): ?>
                                    <tr>
                                        <td><?= $stat['ville'] ?></td>
                                        <td><?= $stat['nombre_dons'] ?></td>
                                        <td><?= number_format($stat['montant_total'], 0, ',', ' ') ?> Ar</td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if (empty($statsParVille)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Aucun don enregistré
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

    <!-- MODAL POPUP POUR AJOUTER UN DON (floute l'arrière-plan) -->
    <div id="donationModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2>Ajouter une donation</h2>
                    <p>Remplissez le formulaire pour enregistrer un nouveau don</p>
                </div>
                <button class="modal-close" onclick="closeDonationModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <form class="form" method="post" action="/ajouter-don" id="donationForm">
                    <!-- AJOUT DU CHAMP VILLE (obligatoire) -->
                    <div class="field">
                        <label for="id_ville">Ville bénéficiaire</label>
                        <select id="id_ville" name="id_ville" required>
                            <option value="">Choisir une ville…</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?php echo htmlspecialchars($ville['id']); ?>">
                                    <?php echo htmlspecialchars($ville['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="id_besoin">Besoin</label>
                        <select id="id_besoin" name="id_besoin" required>
                            <option value="">Choisir un besoin…</option>
                            <?php foreach ($besoins as $besoin): ?>
                                <option value="<?php echo htmlspecialchars($besoin['id']); ?>">
                                    <?php echo htmlspecialchars($besoin['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="nom_donneur">Donneur</label>
                        <input id="nom_donneur" name="nom_donneur" type="text" maxlength="100" placeholder="Nom du donateur" required>
                    </div>

                    <div class="field two">
                        <div>
                            <label for="quantite">Quantité</label>
                            <input id="quantite" name="quantite" type="number" min="1" placeholder="0" required>
                        </div>
                        <div>
                            <label for="date_don">Date</label>
                            <input id="date_don" name="date_don" type="date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="ghost" onclick="closeDonationModal()">Annuler</button>
                        <button type="submit" class="primary">Enregistrer le don</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Local -->
    <script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Modal functions
        function openDonationModal() {
            document.getElementById('donationModal').style.display = 'flex';
            document.getElementById('donationForm').reset();
            
            // Mettre la date du jour par défaut
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_don').value = today;
        }

        function closeDonationModal() {
            document.getElementById('donationModal').style.display = 'none';
        }

        // Close modal when clicking outside the modal container
        document.getElementById('donationModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDonationModal();
            }
        });

        // Filtrer les dons par recherche texte
        function filterDons() {
            const searchText = document.getElementById('searchDon').value.toLowerCase();
            const rows = document.querySelectorAll('.don-row');
            
            rows.forEach(row => {
                const ville = row.getAttribute('data-ville');
                const besoin = row.getAttribute('data-besoin');
                const donneur = row.getAttribute('data-donneur');
                
                if (ville.includes(searchText) || besoin.includes(searchText) || donneur.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Filtrer les dons par ville
        function filterByVille() {
            const selectedVille = document.getElementById('filterVille').value;
            const rows = document.querySelectorAll('.don-row');
            
            rows.forEach(row => {
                const ville = row.getAttribute('data-ville');
                
                if (selectedVille === 'all' || ville === selectedVille) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>