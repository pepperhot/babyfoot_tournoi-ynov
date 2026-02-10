<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Babyfoot</title>
</head>
<body>
<div class="animate-fade-in">
    <h2>👤 Mon Profil</h2>
    
    <?php if (!empty($message)): 
        list($type, $text) = explode('|', $message);
    ?>
        <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>">
            <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
            <?= htmlspecialchars($text) ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-columns">
        <!-- Statistiques et Classement -->
        <div class="card dashboard-col">
            <h3>📊 Mes Statistiques</h3>
            <div class="flex-col">
                <div class="flex-between" style="padding: 15px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 10px; color: white;">
                    <span style="font-size: 0.9rem;">🏆 Points Totaux</span>
                    <strong style="font-size: 1.3rem;"><?= $stats['total_points'] ?? 0 ?></strong>
                </div>
                <div class="flex-between" style="padding: 15px; background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); border-radius: 10px; color: white;">
                    <span style="font-size: 0.9rem;">🎮 Matchs Joués</span>
                    <strong style="font-size: 1.3rem;"><?= $stats['total_matches'] ?? 0 ?></strong>
                </div>
                <div class="flex-between" style="padding: 15px; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border-radius: 10px; color: white;">
                    <span style="font-size: 0.9rem;">📅 Membre depuis</span>
                    <strong style="font-size: 1.1rem;"><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
                </div>
                
                <?php if ($userRank): ?>
                <div class="flex-between" style="padding: 15px; background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); border-radius: 10px; color: white; position: relative; overflow: hidden;">
                    <?php if ($userRank['ranking'] == 1): ?>
                        <div style="position: absolute; top: -10px; right: -10px; font-size: 4rem; opacity: 0.2;">👑</div>
                    <?php endif; ?>
                    <span style="font-size: 0.9rem;">📈 Classement</span>
                    <strong style="font-size: 1.3rem; position: relative; z-index: 1;">
                        #<?= $userRank['ranking'] ?> / <?= $totalPlayers ?>
                        <?php if ($userRank['ranking'] == 1): ?>
                            👑
                        <?php elseif ($userRank['ranking'] == 2): ?>
                            🥈
                        <?php elseif ($userRank['ranking'] == 3): ?>
                            🥉
                        <?php endif; ?>
                    </strong>
                </div>
                <?php else: ?>
                <div class="flex-center" style="padding: 15px; background: var(--text-muted); border-radius: 10px; color: white;">
                    <span style="font-size: 0.9rem;">🎯 Jouez des matchs pour être classé !</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="card dashboard-col" style="flex: 2;">
            <h3>✏️ Modifier mes informations</h3>
            
            <form method="POST">
                <div class="form-group">
                    <label for="pseudo">👤 Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($user['pseudo'] ?? $user['username']) ?>" required maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border-color);">

                <h4 style="color: var(--primary); margin-bottom: 20px;">🔒 Changer le mot de passe (optionnel)</h4>
                
                <div class="form-group">
                    <label for="current_password">🔑 Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Laissez vide pour ne pas changer">
                </div>
                
                <div class="form-group">
                    <label for="new_password">🆕 Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 caractères" minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">✅ Confirmer le nouveau mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Retapez le nouveau mot de passe">
                </div>

                <button type="submit" style="width: 100%; margin-top: 20px;">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Validation côté client
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // Si un nouveau mot de passe est saisi
    if (newPassword) {
        if (!currentPassword) {
            e.preventDefault();
            alert('⚠️ Veuillez saisir votre mot de passe actuel pour changer de mot de passe.');
            document.getElementById('current_password').focus();
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('⚠️ Les mots de passe ne correspondent pas !');
            document.getElementById('confirm_password').focus();
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('⚠️ Le mot de passe doit contenir au moins 6 caractères !');
            document.getElementById('new_password').focus();
            return false;
        }
    }
});

// Auto-hide success messages
<?php if (!empty($message) && strpos($message, 'success') === 0): ?>
setTimeout(function() {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);
<?php endif; ?>
</script>
</body>
</html>
