<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Match - Babyfoot</title>
</head>
<body>
<div class="container">
    <?php if (!$invitation): ?>
        <div class="card" style="text-align: center; padding: 40px;">
            <h2 style="color: #e74c3c;">❌ Invitation non trouvée</h2>
            <p style="color: #7f8c8d; margin: 20px 0;">
                Cette invitation n'existe pas, n'a pas été acceptée, ou a déjà été complétée.
            </p>
            <a href="/players.php" style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px 30px; border-radius: 10px; text-decoration: none; margin-top: 15px;">
                ← Retour aux participants
            </a>
        </div>
    <?php else: ?>
        <div class="card">
            <h2 style="margin-bottom: 10px;">⚡ Enregistrer le Résultat du Match</h2>
            <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 30px;">
                Match contre <strong style="color: #667eea;"><?= htmlspecialchars($opponent['name']) ?></strong>
            </p>

            <?php if (!empty($message)): 
                list($type, $text) = explode('|', $message);
            ?>
                <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
                    <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
                    <?= htmlspecialchars($text) ?>
                </div>
            <?php endif; ?>

            <form method="POST" style="margin-top: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                    <!-- Mes scores -->
                    <div style="background: linear-gradient(135deg, #667eea20, #764ba220); padding: 25px; border-radius: 15px; border: 2px solid #667eea;">
                        <h3 style="color: #667eea; margin-bottom: 20px; text-align: center;">
                            👤 Mes Résultats
                        </h3>
                        
                        <div class="form-group">
                            <label>🏆 Score Final</label>
                            <input type="number" name="my_score" min="0" max="10" required placeholder="Ex: 10" style="font-size: 1.2rem; text-align: center; font-weight: bold;">
                            <small style="color: #7f8c8d; font-size: 0.85rem;">Score entre 0 et 10</small>
                        </div>

                        <div class="form-group">
                            <label>⚽ Buts marqués</label>
                            <input type="number" name="my_goals" min="0" value="0" placeholder="Ex: 5" style="text-align: center;">
                        </div>

                        <div class="form-group">
                            <label>🍳 Gamelles (défaites 10-0)</label>
                            <input type="number" name="my_gamelles" min="0" value="0" placeholder="Ex: 0" style="text-align: center;">
                            <small style="color: #7f8c8d; font-size: 0.85rem;">Nombre de gamelles infligées à l'adversaire</small>
                        </div>
                    </div>

                    <!-- Scores adversaire -->
                    <div style="background: linear-gradient(135deg, #e74c3c20, #c0392b20); padding: 25px; border-radius: 15px; border: 2px solid #e74c3c;">
                        <h3 style="color: #e74c3c; margin-bottom: 20px; text-align: center;">
                            🎯 Résultats de <?= htmlspecialchars($opponent['name']) ?>
                        </h3>
                        
                        <div class="form-group">
                            <label>🏆 Score Final</label>
                            <input type="number" name="opponent_score" min="0" max="10" required placeholder="Ex: 8" style="font-size: 1.2rem; text-align: center; font-weight: bold;">
                            <small style="color: #7f8c8d; font-size: 0.85rem;">Score entre 0 et 10</small>
                        </div>

                        <div class="form-group">
                            <label>⚽ Buts marqués</label>
                            <input type="number" name="opponent_goals" min="0" value="0" placeholder="Ex: 3" style="text-align: center;">
                        </div>

                        <div class="form-group">
                            <label>🍳 Gamelles (défaites 10-0)</label>
                            <input type="number" name="opponent_gamelles" min="0" value="0" placeholder="Ex: 1" style="text-align: center;">
                            <small style="color: #7f8c8d; font-size: 0.85rem;">Nombre de gamelles infligées à vous</small>
                        </div>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f39c1220, #e67e2220); padding: 20px; border-radius: 10px; margin-bottom: 25px; border-left: 4px solid #f39c12;">
                    <h4 style="margin: 0 0 10px 0; color: #f39c12;">📊 Règles de Scoring</h4>
                    <ul style="margin: 0; padding-left: 20px; color: #7f8c8d; font-size: 0.9rem;">
                        <li>Le score final est entre <strong>0 et 10</strong></li>
                        <li>Une <strong>gamelle</strong> = victoire 10-0</li>
                        <li>Comptez les <strong>buts</strong> pour les statistiques détaillées</li>
                    </ul>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" class="btn-save" style="flex: 1; max-width: 300px; font-size: 1.1rem; padding: 15px;">
                        💾 Enregistrer les Résultats
                    </button>
                    <a href="/players.php" class="btn-cancel" style="display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; text-decoration: none; padding: 15px 25px;">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
