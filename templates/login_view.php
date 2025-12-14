<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Tournoi Babyfoot</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Se Connecter</h2>

        <?php
        // Affiche l'erreur si la variable $error a été définie par public/index.php (mauvais mot de passe, etc.)
        if (!empty($error)):
        ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="/index.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p style="margin-top: 20px;">
            Pas encore inscrit ? <a href="/register.php">Créer un compte</a>
        </p>
    </div>
</body>
</html>
