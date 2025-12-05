<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Babyfoot</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">

        <h2>Créer un compte</h2>

        <?php if (!empty($message)) : ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <p><a href="/index.php">Déjà un compte ? Se connecter</a></p>

    </div>
</body>
</html>
