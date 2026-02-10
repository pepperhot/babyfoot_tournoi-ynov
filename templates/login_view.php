<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Babyfoot Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="justify-content: center; align-items: center; background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);">
    
    <div class="container" style="max-width: 450px;">
        <div class="text-center" style="margin-bottom: 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">⚽</div>
            <h1 class="nav-brand" style="font-size: 2rem;">Babyfoot Manager</h1>
            <p class="text-muted">Gérez vos tournois comme un pro</p>
        </div>

        <div class="card animate-fade-in" style="border-top: 4px solid var(--primary);">
            <h2 class="text-center" style="margin-bottom: 1.5rem; border: none;">Connexion</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input type="email" id="email" name="email" required placeholder="nom@exemple.com">
                </div>

                <div class="form-group">
                    <label for="password">🔑 Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Se connecter</button>
            </form>

            <div class="text-center" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <span class="text-muted">Pas encore de compte ?</span><br>
                <a href="register.php" style="color: var(--primary); font-weight: 600;">Créer un compte gratuitement</a>
            </div>
        </div>
    </div>

</body>
</html>
