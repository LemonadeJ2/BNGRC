<?php
function formatAr($value)
{
    return number_format((float) $value, 0, ',', ' ') . ' Ar';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Récapitulatif</title>
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
<div class="dashboard-wrapper">
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
            <a href="/recapitulatif" class="nav-item active">
                <i class="bi bi-kanban"></i>
                <span>Récapitulatif</span>
            </a>
            <a href="/simulation" class="nav-item">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Simulations d'Impact</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <p class="user-label">Utilisateur</p>
                <div class="user-info">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-BQEaeqadqYV_rHUDoRWHl5FlgJ7tGDeLrC-WjewY2YfXn8GJ7_hE0ifle9JTUd2Nx1aQh8nvxZmDHebyKjKiPkzE-8XjG5cFp91F_EUiM6wZ1P2ufP8REYOvBFvVskpWCLBNOnk2MGOTPDK9liL94-G4zSQE6Ym_qVbU2LKrWIMs2CGmwgVQOrhfAOxRMgBOa__mv9LEmRec2jusOGQS1AO5WrLu9yH5caOtT5J-RZk5joc3jJACj1JOQJtrrOFQLsc5ggFPXk" alt="Admin" class="user-avatar">
                    <div class="user-details">
                        <p class="user-name"><?= htmlspecialchars($admin ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="user-role">Admin Central</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <h2>Récapitulatif Général</h2>
                <span class="badge badge-live">Live Updates</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted">Dernière mise à jour</small>
                <span class="fw-semibold" id="last-update"><?= $recapData['lastUpdate'] ?></span>
                <button id="refresh-btn" class="btn btn-primary btn-sm ms-2">
                    <i class="bi bi-arrow-repeat"></i> Actualiser
                </button>
            </div>
        </header>

        <div class="content-area">
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card kpi-card">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Besoins Totaux</span>
                                <i class="bi bi-exclamation-triangle kpi-icon text-warning"></i>
                            </div>
                            <h3 class="kpi-value" id="kpi-besoins"><?= formatAr($recapData['global']['besoins_totaux']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card kpi-card">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Besoins Satisfaits</span>
                                <i class="bi bi-check-circle kpi-icon text-success"></i>
                            </div>
                            <h3 class="kpi-value" id="kpi-satisfaits"><?= formatAr($recapData['global']['besoins_satisfaits']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card kpi-card">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Besoins Restants</span>
                                <i class="bi bi-x-circle kpi-icon text-danger"></i>
                            </div>
                            <h3 class="kpi-value" id="kpi-restants"><?= formatAr($recapData['global']['besoins_restants']) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card kpi-card">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Taux de Couverture</span>
                                <i class="bi bi-graph-up kpi-icon text-info"></i>
                            </div>
                            <h3 class="kpi-value" id="kpi-taux"><?= round($recapData['global']['taux_couverture']) ?>%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Récapitulatif par Ville</h5>
                    <span class="text-muted">Satisfait / Total / Restant</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recap-table">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th class="text-end">Besoins Totaux</th>
                                    <th class="text-end">Satisfaits</th>
                                    <th class="text-end">Restants</th>
                                    <th class="text-center">Couverture</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recapData['villes'] as $ville): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ville['ville'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-end"><?= formatAr($ville['besoin_total']) ?></td>
                                        <td class="text-end text-success"><?= formatAr($ville['montant_satisfait']) ?></td>
                                        <td class="text-end text-danger"><?= formatAr($ville['montant_restant']) ?></td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar" style="width: <?= round($ville['taux_couverture']) ?>%" aria-valuenow="<?= round($ville['taux_couverture']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="fw-semibold d-block mt-1"><?= round($ville['taux_couverture']) ?>%</small>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $tone = $ville['statut_tone'];
                                            $badgeClass = 'bg-warning';
                                            if ($tone === 'success') $badgeClass = 'bg-success';
                                            elseif ($tone === 'danger') $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $ville['statut'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <th>Total</th>
                                    <th class="text-end" id="ft-besoins"><?= formatAr($recapData['totaux']['besoins_totaux']) ?></th>
                                    <th class="text-end" id="ft-satisfaits"><?= formatAr($recapData['totaux']['besoins_satisfaits']) ?></th>
                                    <th class="text-end" id="ft-restants"><?= formatAr($recapData['totaux']['besoins_restants']) ?></th>
                                    <th class="text-center" id="ft-taux"><?= round($recapData['totaux']['taux_couverture']) ?>%</th>
                                    <th class="text-center">Global</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const numberFormatter = new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 });

    function formatArJS(value) {
        const safe = Number.isFinite(value) ? value : 0;
        return `${numberFormatter.format(safe)} Ar`;
    }

    function updateCards(global) {
        document.getElementById('kpi-besoins').textContent = formatArJS(global.besoins_totaux);
        document.getElementById('kpi-satisfaits').textContent = formatArJS(global.besoins_satisfaits);
        document.getElementById('kpi-restants').textContent = formatArJS(global.besoins_restants);
        document.getElementById('kpi-taux').textContent = `${Math.round(global.taux_couverture)}%`;
    }

    function buildRow(ville) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${ville.ville}</td>
            <td class="text-end">${formatArJS(ville.besoin_total)}</td>
            <td class="text-end text-success">${formatArJS(ville.montant_satisfait)}</td>
            <td class="text-end text-danger">${formatArJS(ville.montant_restant)}</td>
            <td class="text-center">
                <div class="progress" style="height:8px;">
                    <div class="progress-bar" role="progressbar" style="width:${Math.round(ville.taux_couverture)}%" aria-valuenow="${Math.round(ville.taux_couverture)}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="fw-semibold d-block mt-1">${Math.round(ville.taux_couverture)}%</small>
            </td>
            <td class="text-center">
                <span class="badge ${ville.statut_tone === 'success' ? 'bg-success' : ville.statut_tone === 'danger' ? 'bg-danger' : 'bg-warning'}">${ville.statut}</span>
            </td>
        `;
        return tr;
    }

    function updateTable(data) {
        const tbody = document.querySelector('#recap-table tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        data.villes.forEach((ville) => {
            tbody.appendChild(buildRow(ville));
        });

        document.getElementById('ft-besoins').textContent = formatArJS(data.totaux.besoins_totaux);
        document.getElementById('ft-satisfaits').textContent = formatArJS(data.totaux.besoins_satisfaits);
        document.getElementById('ft-restants').textContent = formatArJS(data.totaux.besoins_restants);
        document.getElementById('ft-taux').textContent = `${Math.round(data.totaux.taux_couverture)}%`;
    }

    async function refreshData() {
        const btn = document.getElementById('refresh-btn');
        btn?.setAttribute('disabled', 'disabled');
        try {
            const res = await fetch('/recapitulatif/data');
            if (!res.ok) throw new Error('Erreur de chargement');
            const payload = await res.json();
            updateCards(payload.global);
            updateTable(payload);
            const lu = document.getElementById('last-update');
            if (lu) lu.textContent = payload.lastUpdate;
        } catch (e) {
            console.error(e);
            alert('Impossible de rafraîchir les données.');
        } finally {
            btn?.removeAttribute('disabled');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('refresh-btn');
        btn?.addEventListener('click', (e) => {
            e.preventDefault();
            refreshData();
        });
    });
</script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
