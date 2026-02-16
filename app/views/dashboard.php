
<!DOCTYPE html>
<html lang="fr">
<head>
    
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC Dashboard Overview</title>
    
    <!-- Bootstrap CSS Local -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons Local -->
    <link href="/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/assets/style.css" rel="stylesheet">
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
                <a href="#" class="nav-item active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-building"></i>
                    <span>Villes Impactées</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Besoins Recensés</span>
                </a>
                <a href="#" class="nav-item">
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
                            <p class="user-name">Cdt. Rakotoarisoa</p>
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
                                <h3 class="kpi-value">344.7M Ar</h3>
                                <div class="kpi-footer">
                                    <span class="kpi-trend trend-up">
                                        <i class="bi bi-arrow-up"></i>
                                        <span>+12%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div>
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
                                <h3 class="kpi-value">205.3M Ar</h3>
                                <div class="kpi-footer">
                                    <span class="kpi-trend trend-up">
                                        <i class="bi bi-arrow-up"></i>
                                        <span>+24%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div>
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
                                <h3 class="kpi-value">139.4M Ar</h3>
                                <div class="kpi-footer">
                                    <span class="kpi-trend trend-down">
                                        <i class="bi bi-arrow-down"></i>
                                        <span>-8%</span>
                                    </span>
                                    <span class="kpi-period">vs. mois dernier</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Satisfaction Rate -->
                    <div class="col-12 col-md-6 col-lg-3">
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
                    </div>
                    
                </div>
                
                <!-- Districts Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vue par District</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover districts-table mb-0">
                                <thead>
                                    <tr>
                                        <th>District</th>
                                        <th>Besoins Totaux</th>
                                        <th>Dons Reçus</th>
                                        <th>Reste à Combler</th>
                                        <th>Satisfaction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row 1 -->
                                    <tr>
                                        <td>
                                            <div class="district-cell">
                                                <div class="district-avatar">AN</div>
                                                <span class="district-name">Antananarivo</span>
                                            </div>
                                        </td>
                                        <td>120,500,000</td>
                                        <td>98,200,000</td>
                                        <td class="text-muted">-22,300,000</td>
                                        <td>
                                            <span class="badge satisfaction-high">81.5%</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-action">Consulter</button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Row 2 -->
                                    <tr>
                                        <td>
                                            <div class="district-cell">
                                                <div class="district-avatar">TO</div>
                                                <span class="district-name">Toamasina</span>
                                            </div>
                                        </td>
                                        <td>85,200,000</td>
                                        <td>42,600,000</td>
                                        <td class="text-danger fw-medium">-42,600,000</td>
                                        <td>
                                            <span class="badge satisfaction-medium">50.0%</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-action">Consulter</button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Row 3 -->
                                    <tr>
                                        <td>
                                            <div class="district-cell">
                                                <div class="district-avatar">FI</div>
                                                <span class="district-name">Fianarantsoa</span>
                                            </div>
                                        </td>
                                        <td>94,000,000</td>
                                        <td>22,500,000</td>
                                        <td class="text-danger fw-bold">-71,500,000</td>
                                        <td>
                                            <span class="badge satisfaction-low">23.9%</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-action">Consulter</button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Row 4 -->
                                    <tr>
                                        <td>
                                            <div class="district-cell">
                                                <div class="district-avatar">MA</div>
                                                <span class="district-name">Mahajanga</span>
                                            </div>
                                        </td>
                                        <td>45,000,000</td>
                                        <td>42,000,000</td>
                                        <td class="text-muted">-3,000,000</td>
                                        <td>
                                            <span class="badge satisfaction-high">93.3%</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-action">Consulter</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="pagination-info">
                            <p>Affichage de 4 sur 22 districts</p>
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS Local -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>