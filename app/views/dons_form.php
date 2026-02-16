<?php
// Demo data for front-only CRUD mockup
$besoins = $besoins ?? [
    ['id' => 1, 'nom' => 'Eau potable'],
    ['id' => 2, 'nom' => 'Vivres'],
    ['id' => 3, 'nom' => 'Médicaments'],
];
$dons = $dons ?? [
    ['id' => 1, 'besoin' => 'Eau potable', 'nom_donneur' => 'ONG Terre', 'quantite' => 120, 'date_don' => '2026-02-01'],
    ['id' => 2, 'besoin' => 'Vivres', 'nom_donneur' => 'Donateur X', 'quantite' => 85, 'date_don' => '2026-02-05'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dons - CRUD</title>
    <link rel="stylesheet" href="/assets/crud.css">
</head>
<body>
<div class="page">
    <header class="topbar">
        <div>
            <p class="eyebrow">Gestion des dons</p>
            <h1>Dons</h1>
        </div>
        <a class="ghost" href="/">Retour</a>
    </header>

    <main class="grid">
        <section class="card">
            <div class="card__head">
                <h2>Créer / modifier un don</h2>
                <p class="muted">Front uniquement : le backend est à brancher.</p>
            </div>
            <form class="form" method="post" action="#">
                <div class="field">
                    <label for="id_besoin">Besoin</label>
                    <select id="id_besoin" name="id_besoin" required>
                        <option value="">Choisir…</option>
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
                        <input id="date_don" name="date_don" type="date" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="primary">Enregistrer</button>
                    <button type="reset" class="ghost">Annuler</button>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="card__head">
                <h2>Liste des dons</h2>
                <p class="muted">Les actions sont illustratives côté front.</p>
            </div>
            <div class="table">
                <div class="table__row table__row--head">
                    <span>ID</span>
                    <span>Besoin</span>
                    <span>Donneur</span>
                    <span>Quantité</span>
                    <span>Date</span>
                    <span>Actions</span>
                </div>
                <?php foreach ($dons as $don): ?>
                    <div class="table__row">
                        <span><?php echo htmlspecialchars($don['id']); ?></span>
                        <span><?php echo htmlspecialchars($don['besoin']); ?></span>
                        <span><?php echo htmlspecialchars($don['nom_donneur']); ?></span>
                        <span><?php echo htmlspecialchars($don['quantite']); ?></span>
                        <span><?php echo htmlspecialchars($don['date_don']); ?></span>
                        <span class="chips">
                            <button class="chip">Éditer</button>
                            <button class="chip chip--danger">Supprimer</button>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
