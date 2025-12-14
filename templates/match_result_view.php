<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match en cours - Babyfoot</title>
</head>
<body>
<div class="container">
    <?php if (!$invitation): ?>
        <div class="card" style="text-align: center; padding: 40px;">
            <h2 style="color: #e74c3c;">❌ Match introuvable</h2>
            <p style="color: #7f8c8d; margin: 20px 0;">
                Ce match n'existe pas ou a expiré.
            </p>
            <a href="/players.php" class="btn-cancel">← Retour</a>
        </div>
    <?php else: ?>
        
        <!-- Interface de Match VS -->
        <div class="card" style="text-align: center; margin-bottom: 30px; background: linear-gradient(135deg, #1e3c72, #2a5298); color: white;">
            <h1 style="margin-bottom: 30px; font-size: 2.5rem; text-transform: uppercase; letter-spacing: 2px;">⚔️ DUEL EN COURS ⚔️</h1>
            
            <div style="display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap;">
                <!-- Joueur 1 (Moi) -->
                <div style="flex: 1; min-width: 200px;">
                    <div style="width: 100px; height: 100px; background: white; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 3rem; border: 4px solid #667eea; color: #667eea;">
                        👤
                    </div>
                    <h2 style="margin: 0; font-size: 1.5rem;">MOI</h2>
                    <p style="opacity: 0.8; margin-top: 5px;"><?= htmlspecialchars($_SESSION['username'] ?? 'Joueur') ?></p>
                </div>

                <!-- VS Badge -->
                <div style="font-size: 3rem; font-weight: 900; color: #f39c12; text-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                    VS
                </div>

                <!-- Joueur 2 (Adversaire) -->
                <div style="flex: 1; min-width: 200px;">
                    <div style="width: 100px; height: 100px; background: white; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 3rem; border: 4px solid #e74c3c; color: #e74c3c;">
                        😈
                    </div>
                    <h2 style="margin: 0; font-size: 1.5rem;"><?= htmlspecialchars($opponent['name']) ?></h2>
                    <p style="opacity: 0.8; margin-top: 5px;">Adversaire</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 style="margin-bottom: 20px; text-align: center; color: #2c3e50;">📝 Saisir les Scores</h2>
            <p style="text-align: center; color: #7f8c8d; margin-bottom: 30px;">
                Le match est terminé ? Entrez les résultats ci-dessous.
            </p>

            <?php if (!empty($message)): 
                $parts = explode('|', $message);
                $type = count($parts) > 1 ? $parts[0] : 'info';
                $text = count($parts) > 1 ? $parts[1] : $message;
            ?>
                <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
                    <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
                    <?= htmlspecialchars($text) ?>
                </div>
            <?php endif; ?>

            <form method="POST" style="margin-top: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                    <!-- Mes scores -->
                    <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; border-top: 5px solid #667eea;">
                        <h3 style="color: #667eea; margin-bottom: 20px; text-align: center;">
                            Mon Score
                        </h3>
                        
                        <div class="form-group">
                            <label>🏆 Score (0-10)</label>
                            <input type="number" name="my_score" min="0" max="10" required placeholder="0" style="font-size: 2rem; text-align: center; font-weight: bold; height: 60px;">
                        </div>

                        <div class="form-group">
                            <label>⚽ Buts</label>
                            <input type="number" name="my_goals" min="0" value="0" style="text-align: center;">
                        </div>

                        <div class="form-group">
                            <label>🍳 Gamelles</label>
                            <input type="number" name="my_gamelles" min="0" value="0" style="text-align: center;">
                        </div>
                    </div>

                    <!-- Scores adversaire -->
                    <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; border-top: 5px solid #e74c3c;">
                        <h3 style="color: #e74c3c; margin-bottom: 20px; text-align: center;">
                            Score Adversaire
                        </h3>
                        
                        <div class="form-group">
                            <label>🏆 Score (0-10)</label>
                            <input type="number" name="opponent_score" min="0" max="10" required placeholder="0" style="font-size: 2rem; text-align: center; font-weight: bold; height: 60px;">
                        </div>

                        <div class="form-group">
                            <label>⚽ Buts</label>
                            <input type="number" name="opponent_goals" min="0" value="0" style="text-align: center;">
                        </div>

                        <div class="form-group">
                            <label>🍳 Gamelles</label>
                            <input type="number" name="opponent_gamelles" min="0" value="0" style="text-align: center;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" class="btn-save" style="flex: 1; max-width: 300px; font-size: 1.2rem; padding: 15px;">
                        💾 Valider le Match
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
