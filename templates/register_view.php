<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Babyfoot Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="justify-content: center; align-items: center; background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);">
    
    <div class="container" style="max-width: 450px;">
        <div class="text-center" style="margin-bottom: 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🚀</div>
            <h1 class="nav-brand" style="font-size: 2rem;">Rejoindre l'Arène</h1>
            <p class="text-muted">Créez votre profil de joueur</p>
        </div>

        <div class="card animate-fade-in" style="border-top: 4px solid var(--success);">
            <h2 class="text-center" style="margin-bottom: 1.5rem; border: none;">Inscription</h2>

            <?php if (!empty($message)) : ?>
                <div class="alert alert-danger">
                    ⚠️ <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>👤 Nom d'utilisateur</label>
                    <input type="text" name="username" required placeholder="Ex: ZidaneDuBaby">
                </div>

                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" name="email" required placeholder="nom@exemple.com">
                </div>

                <div class="form-group">
                    <label>🔑 Mot de passe</label>
                    <input type="password" name="password" required placeholder="Minimum 6 caractères">
                </div>

                <button type="submit" class="btn-success" style="width: 100%; margin-top: 1rem;">S'inscrire</button>
            </form>

            <div class="text-center" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <span class="text-muted">Déjà un compte ?</span><br>
                <a href="index.php" style="color: var(--primary); font-weight: 600;">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
