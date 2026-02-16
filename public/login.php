<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC | Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>
<div class="page">
    <aside class="hero">
        <div class="hero__logo">
            <div class="hero__shield"><span class="material-icons">security</span></div>
            <div>
                <div class="hero__brand">BNGRC</div>
                <div class="hero__subtitle">Gestion des Risques</div>
            </div>
        </div>
        <div class="hero__content">
            <h1>Système de Suivi des Dons</h1>
            <p>Surveillez les besoins, les dons mobilisés et les distributions en temps réel.</p>
            <div class="hero__stats">
                <div class="stat">
                    <span class="stat__label">Total besoins estimés</span>
                    <span class="stat__value">452,500 Ar</span>
                </div>
                <div class="stat">
                    <span class="stat__label">Dons reçus</span>
                    <span class="stat__value">312,800 Ar</span>
                </div>
                <div class="stat">
                    <span class="stat__label">Satisfaction</span>
                    <span class="stat__value">69.1%</span>
                </div>
            </div>
        </div>
    </aside>
    <main class="panel">
        <div class="panel__card">
            <div class="panel__header">
                <div class="badge">Live</div>
                <p class="panel__hint">Accédez au tableau de bord sécurisé du BNGRC.</p>
            </div>
            <h2>Connexion</h2>
            <form class="form" method="post" action="/dashboard">
                <label class="field">
                    <span class="field__label">Adresse e-mail</span>
                    <div class="field__input">
                        <span class="material-icons">mail</span>
                        <input type="email" name="email" placeholder="admin@bngrc.gov.mg" required>
                    </div>
                </label>
                <label class="field">
                    <span class="field__label">Mot de passe</span>
                    <div class="field__input">
                        <span class="material-icons">lock</span>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                </label>
                <div class="form__actions">
                    <label class="remember">
                        <input type="checkbox" name="remember" checked>
                        <span>Rester connecté</span>
                    </label>
                    <a class="link" href="#">Mot de passe oublié ?</a>
                </div>
                <button class="btn" type="submit">Se connecter</button>
            </form>
            <p class="panel__footer">Accès réservé au personnel autorisé BNGRC.</p>
        </div>
    </main>
</div>
</body>
</html>
