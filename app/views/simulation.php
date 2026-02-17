<?php
function formatNumber($value)
{
    return number_format((float) $value, 0, ',', ' ');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Simulation d'Impact</title>
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
            <a href="/recapitulatif" class="nav-item">
                <i class="bi bi-kanban"></i>
                <span>Récapitulatif</span>
            </a>
            <a href="/simulation" class="nav-item active">
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
                <h2>Simulation de Dispatch</h2>
                <span class="badge badge-live">Outils</span>
            </div>
        </header>

        <div class="content-area">
            <div class="card mb-4">
                <div class="card-body d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <label class="form-label fw-semibold mb-1">Mode de simulation</label>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="mode-date" value="date" checked>
                                <label class="form-check-label" for="mode-date">Par date (priorité chronologique)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="mode-prop" value="proportionnel">
                                <label class="form-check-label" for="mode-prop">Proportionnel</label>
                            </div>
                        </div>
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <button id="btn-reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                        </button>
                        <button id="btn-valider" class="btn btn-success">
                            <i class="bi bi-check2"></i> Valider
                        </button>
                        <button id="btn-simuler" class="btn btn-primary">
                            <i class="bi bi-magic"></i> Simuler
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-4">
                    <div class="card kpi-card h-100">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Stock initial attribuable</span>
                                <i class="bi bi-box-seam kpi-icon text-primary"></i>
                            </div>
                            <div id="stock-initial" class="mt-2 small text-muted"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card kpi-card h-100">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Total attribué (simulation)</span>
                                <i class="bi bi-check2-circle kpi-icon text-success"></i>
                            </div>
                            <h3 class="kpi-value" id="kpi-attribue">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card kpi-card h-100">
                        <div class="card-body">
                            <div class="kpi-header">
                                <span class="kpi-label">Stock restant</span>
                                <i class="bi bi-clipboard-x kpi-icon text-danger"></i>
                            </div>
                            <div id="stock-restant" class="mt-2 small text-muted"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Résultat par ville</h5>
                    <small class="text-muted">Affichage des besoins, allocations et restes</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="table-simulation">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Besoin</th>
                                    <th class="text-end">Demandé</th>
                                    <th class="text-end">Attribué</th>
                                    <th class="text-end">Restant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const initialData = <?= json_encode($simulation) ?>;

    function numberFmt(value) {
        return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value || 0);
    }

    function renderStock(containerId, stock) {
        const el = document.getElementById(containerId);
        if (!el) return;
        const entries = Object.entries(stock || {});
        if (entries.length === 0) {
            el.textContent = 'Aucun stock.';
            return;
        }
        el.innerHTML = entries
            .map(([id, value]) => `<span class="badge bg-light text-dark me-1 mb-1">Besoin ${id}: ${numberFmt(value)}</span>`)
            .join(' ');
    }

    function renderTable(byVille) {
        const tbody = document.querySelector('#table-simulation tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        byVille.forEach(ville => {
            ville.items.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.ville}</td>
                    <td>${item.besoin}</td>
                    <td class="text-end">${numberFmt(item.demande)}</td>
                    <td class="text-end text-success">${numberFmt(item.attribue)}</td>
                    <td class="text-end text-danger">${numberFmt(item.restant)}</td>
                    <td>${item.date}</td>
                `;
                tbody.appendChild(tr);
            });
        });
    }

    function renderKPIs(summary) {
        document.getElementById('kpi-attribue').textContent = numberFmt(summary.total_attribue);
        renderStock('stock-initial', summary.stock_initial);
        renderStock('stock-restant', summary.stock_restant);
    }

    function renderAll(data) {
        renderTable(data.byVille || []);
        renderKPIs(data.summary || {});
    }

    async function runSimulation(mode) {
        const btn = document.getElementById('btn-simuler');
        btn?.setAttribute('disabled', 'disabled');
        try {
            const res = await fetch('/simulation/run', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ mode })
            });
            if (!res.ok) throw new Error('Simulation échouée');
            const payload = await res.json();
            renderAll(payload);
        } catch (e) {
            console.error(e);
            alert('Impossible de lancer la simulation.');
        } finally {
            btn?.removeAttribute('disabled');
        }
    }

    async function validateSimulation() {
        const btn = document.getElementById('btn-valider');
        btn?.setAttribute('disabled', 'disabled');
        try {
            const mode = document.querySelector('input[name="mode"]:checked')?.value || 'date';
            const res = await fetch('/simulation/validate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ mode })
            });
            if (!res.ok) throw new Error('Validation échouée');
            const payload = await res.json();
            renderAll(payload.result);
            alert('Simulation validée et enregistrée (ID ' + payload.run_id + ').');
        } catch (e) {
            console.error(e);
            alert('Impossible de valider la simulation.');
        } finally {
            btn?.removeAttribute('disabled');
        }
    }

    async function resetSimulation() {
        const btn = document.getElementById('btn-reset');
        btn?.setAttribute('disabled', 'disabled');
        try {
            // Revenir à la simulation par date par défaut
            document.getElementById('mode-date').checked = true;
            const res = await fetch('/simulation/reset', { method: 'POST' });
            if (!res.ok) throw new Error('Réinitialisation échouée');
            const payload = await res.json();
            renderAll(payload);
        } catch (e) {
            console.error(e);
            alert('Impossible de réinitialiser.');
        } finally {
            btn?.removeAttribute('disabled');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderAll(initialData);
        document.getElementById('btn-simuler')?.addEventListener('click', () => {
            const mode = document.querySelector('input[name="mode"]:checked')?.value || 'date';
            runSimulation(mode);
        });
        document.getElementById('btn-valider')?.addEventListener('click', () => {
            validateSimulation();
        });
        document.getElementById('btn-reset')?.addEventListener('click', () => {
            resetSimulation();
        });
    });
</script>
<script src="<?= BASE_URL ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
