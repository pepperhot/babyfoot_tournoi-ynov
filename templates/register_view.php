<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Babyfoot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="justify-content: center; align-items: center;">
    <div class="container" style="max-width: 450px; flex: initial;">
        <div class="card animate-fade-in" style="padding: 40px;">
            <h1>Babyfoot ⚽</h1>
            <h2 class="text-center" style="border: none; display: block; font-size: 1.5rem; margin-top: 10px;">Créer un compte</h2>

            <?php if (!empty($message)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
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

                <button type="submit" style="width: 100%;">✨ S'inscrire</button>
            </form>

            <p style="margin-top: 20px; text-align: center;">
                <a href="/index.php">Déjà un compte ? Se connecter</a>
            </p>
        </div>
