<?php
// Demo data for front-only CRUD mockup
$besoins = $besoins ?? [
    ['id' => 1, 'nom' => 'Eau potable'],
    ['id' => 2, 'nom' => 'Vivres'],
    ['id' => 3, 'nom' => 'Médicaments'],
];
$villes = $villes ?? [
    ['id' => 10, 'nom' => 'Antananarivo'],
    ['id' => 11, 'nom' => 'Toamasina'],
    ['id' => 12, 'nom' => 'Mahajanga'],
];
$villeBesoins = $villeBesoins ?? [
    ['id' => 1, 'ville' => 'Antananarivo', 'besoin' => 'Eau potable', 'quantite' => 300, 'dateB' => '2026-02-07'],
    ['id' => 2, 'ville' => 'Toamasina', 'besoin' => 'Vivres', 'quantite' => 150, 'dateB' => '2026-02-09'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins par ville - CRUD</title>
    <link rel="stylesheet" href="/assets/crud.css">
</head>
<body>
<div class="page">
    <header class="topbar">
        <div>
            <p class="eyebrow">Affectation des besoins</p>
            <h1>Besoins par ville</h1>
        </div>
        <a class="ghost" href="/">Retour</a>
    </header>

    <main class="grid">
        <section class="card">
            <div class="card__head">
                <h2>Créer / modifier</h2>
                <p class="muted">Front uniquement : à connecter au backend.</p>
            </div>
            <form class="form" method="post" action="#">
                <div class="field">
                    <label for="id_ville">Ville</label>
                    <select id="id_ville" name="id_ville" required>
                        <option value="">Choisir…</option>
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
                        <option value="">Choisir…</option>
                        <?php foreach ($besoins as $besoin): ?>
                            <option value="<?php echo htmlspecialchars($besoin['id']); ?>">
                                <?php echo htmlspecialchars($besoin['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field two">
                    <div>
                        <label for="quantite">Quantité</label>
                        <input id="quantite" name="quantite" type="number" min="0" placeholder="0">
                    </div>
                    <div>
                        <label for="dateB">Date</label>
                        <input id="dateB" name="dateB" type="date" required>
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
                <h2>Liste des besoins par ville</h2>
                <p class="muted">Actions en démonstration côté front.</p>
            </div>
            <div class="table">
                <div class="table__row table__row--head">
                    <span>ID</span>
                    <span>Ville</span>
                    <span>Besoin</span>
                    <span>Quantité</span>
                    <span>Date</span>
                    <span>Actions</span>
                </div>
                <?php foreach ($villeBesoins as $item): ?>
                    <div class="table__row">
                        <span><?php echo htmlspecialchars($item['id']); ?></span>
                        <span><?php echo htmlspecialchars($item['ville']); ?></span>
                        <span><?php echo htmlspecialchars($item['besoin']); ?></span>
                        <span><?php echo htmlspecialchars($item['quantite']); ?></span>
                        <span><?php echo htmlspecialchars($item['dateB']); ?></span>
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
