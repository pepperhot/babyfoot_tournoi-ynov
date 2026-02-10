<div class="animate-fade-in">
    <div class="flex-between align-center" style="margin-bottom: 2rem;">
        <h2>👤 Mon Profil</h2>
    </div>
    
    <?php if (!empty($message)): 
        $parts = explode('|', $message);
        $type = count($parts) > 1 ? $parts[0] : 'info';
        $text = count($parts) > 1 ? $parts[1] : $message;
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
                <div class="flex-between align-center" style="padding: 1rem; background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: var(--radius-md);">
                    <span style="color: var(--primary); font-weight: 500;">🏆 Points Totaux</span>
                    <strong style="font-size: 1.5rem; color: var(--text-main);"><?= $stats['total_points'] ?? 0 ?></strong>
                </div>
                <div class="flex-between align-center" style="padding: 1rem; background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: var(--radius-md);">
                    <span style="color: var(--warning); font-weight: 500;">🎮 Matchs Joués</span>
                    <strong style="font-size: 1.5rem; color: var(--text-main);"><?= $stats['total_matches'] ?? 0 ?></strong>
                </div>
                <div class="flex-between align-center" style="padding: 1rem; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: var(--radius-md);">
                    <span style="color: var(--success); font-weight: 500;">📅 Membre depuis</span>
                    <strong style="font-size: 1.1rem; color: var(--text-main);"><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
                </div>
                
                <?php if ($userRank): ?>
                <div class="flex-between align-center" style="padding: 1rem; background: linear-gradient(135deg, var(--primary), #4f46e5); border-radius: var(--radius-md); color: white; margin-top: 1rem;">
                    <span style="font-weight: 500;">📈 Classement</span>
                    <strong style="font-size: 1.5rem;">
                        #<?= $userRank['ranking'] ?> / <?= $totalPlayers ?>
                        <?php if ($userRank['ranking'] == 1): ?>👑<?php endif; ?>
                    </strong>
                </div>
                <?php else: ?>
                <div class="text-center text-muted" style="padding: 1rem;">
                    Non classé
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="card dashboard-col">
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

                <div style="margin: 2rem 0; height: 1px; background-color: var(--border-color);"></div>

                <h4 style="color: var(--secondary); margin-bottom: 1.5rem;">🔒 Sécurité</h4>
                
                <div class="form-group">
                    <label for="current_password">Clé actuelle</label>
                    <input type="password" id="current_password" name="current_password" placeholder="••••••">
                </div>
                
                <div class="form-group">
                    <label for="new_password">Nouvelle clé</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 caractères" minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmation</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Retapez la nouvelle clé">
                </div>
                
                <div class="text-right" style="margin-top: 2rem;">
                    <button type="submit" class="btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>