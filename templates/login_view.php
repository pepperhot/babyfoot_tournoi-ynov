<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Tournoi Babyfoot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="align-items: center; justify-content: center;">
    <div class="container" style="max-width: 450px; flex: initial;">
        <div class="card animate-fade-in" style="padding: 40px;">
            <h1>Babyfoot ⚽</h1>
            <h2 class="text-center" style="border: none; display: block; font-size: 1.5rem; margin-top: 10px;">Se Connecter</h2>

            <?php
            // Affiche l'erreur si la variable $error a été définie par public/index.php (mauvais mot de passe, etc.)
            if (!empty($error)):
            ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/index.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="nom@exemple.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" style="width: 100%;">🚀 Se connecter</button>
            </form>

            <p style="margin-top: 20px; text-align: center; color: var(--text-muted);">
                Pas encore inscrit ? <a href="/register.php">Créer un compte</a>
            </p>
        </div>
