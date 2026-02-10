<div class="animate-fade-in">
    <h2>👤 Mon Profil</h2>
    
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
                
                <div class="text-right">
                    <button type="submit" class="btn-primary">💾 Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>