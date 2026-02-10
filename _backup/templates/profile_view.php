<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Babyfoot</title>
</head>
<body>
<div class="container">
    <h2>👤 Mon Profil</h2>
    
    <?php if (!empty($message)): 
        list($type, $text) = explode('|', $message);
    ?>
        <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>">
            <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
            <?= htmlspecialchars($text) ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 25px; flex-wrap: wrap;">
        <!-- Statistiques et Classement -->
        <div class="card" style="flex: 1; min-width: 300px;">
            <h3>📊 Mes Statistiques</h3>
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white; margin-bottom: 15px;">
                    <span style="font-size: 0.9rem;">🏆 Points Totaux</span>
                    <strong style="font-size: 1.3rem;"><?= $stats['total_points'] ?? 0 ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); border-radius: 10px; color: white; margin-bottom: 15px;">
                    <span style="font-size: 0.9rem;">🎮 Matchs Joués</span>
                    <strong style="font-size: 1.3rem;"><?= $stats['total_matches'] ?? 0 ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); border-radius: 10px; color: white; margin-bottom: 15px;">
                    <span style="font-size: 0.9rem;">📅 Membre depuis</span>
                    <strong style="font-size: 1.1rem;"><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
                </div>
                
                <?php if ($userRank): ?>
                <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border-radius: 10px; color: white; position: relative; overflow: hidden;">
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
                <div style="display: flex; justify-content: center; align-items: center; padding: 15px; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border-radius: 10px; color: white;">
                    <span style="font-size: 0.9rem;">🎯 Jouez des matchs pour être classé !</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="card" style="flex: 2; min-width: 300px;">
            <h3>✏️ Modifier mes informations</h3>
            
            <form method="POST" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="pseudo">👤 Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($user['pseudo'] ?? $user['username']) ?>" required maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <hr style="margin: 30px 0; border: none; border-top: 2px solid #ecf0f1;">

                <h4 style="color: #667eea; margin-bottom: 20px;">🔒 Changer le mot de passe (optionnel)</h4>
                
                <div class="form-group">
                    <label for="current_password">🔑 Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Laissez vide pour ne pas changer">
                </div>
                
                <div class="form-group">
                    <label for="new_password">🆕 Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 caractères" minlength="6">
                    <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 5px;">
                        Minimum 6 caractères
                    </small>
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
