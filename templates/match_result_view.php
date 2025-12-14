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
                
                <!-- 1. QUI A GAGNÉ ? -->
                <div style="margin-bottom: 40px;">
                    <h3 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.3rem;">🏆 Qui a remporté le match ?</h3>
                    <div style="display: flex; gap: 20px; justify-content: center;">
                        <label style="cursor: pointer; flex: 1; max-width: 250px;">
                            <input type="radio" name="winner" value="me" checked style="display: none;" onchange="updateWinnerUI(this)">
                            <div class="winner-card selected" id="card-me" style="border: 3px solid #e0e0e0; border-radius: 15px; padding: 20px; transition: all 0.3s ease;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">😎</div>
                                <div style="font-weight: bold; font-size: 1.2rem;">MOI</div>
                                <div style="color: #2ecc71; font-weight: bold; margin-top: 5px;">VAINQUEUR</div>
                            </div>
                        </label>

                        <label style="cursor: pointer; flex: 1; max-width: 250px;">
                            <input type="radio" name="winner" value="opponent" style="display: none;" onchange="updateWinnerUI(this)">
                            <div class="winner-card" id="card-opponent" style="border: 3px solid #e0e0e0; border-radius: 15px; padding: 20px; transition: all 0.3s ease; opacity: 0.7;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">😈</div>
                                <div style="font-weight: bold; font-size: 1.2rem;"><?= htmlspecialchars($opponent['name']) ?></div>
                                <div style="color: #7f8c8d; font-weight: bold; margin-top: 5px;">PERDANT</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. SCORES -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                    <!-- Score Gagnant -->
                    <div style="background: #e8f8f5; padding: 20px; border-radius: 15px; border: 2px solid #2ecc71;">
                        <h3 style="color: #27ae60; margin-bottom: 15px; text-align: center;">
                            🥇 Score du Vainqueur
                        </h3>
                        <div class="form-group">
                            <input type="number" name="winner_score" min="0" max="10" value="10" required 
                                   style="font-size: 2.5rem; text-align: center; font-weight: bold; height: 70px; color: #27ae60; border: 2px solid #2ecc71;">
                        </div>
                    </div>

                    <!-- Score Perdant -->
                    <div style="background: #fdedec; padding: 20px; border-radius: 15px; border: 2px solid #e74c3c;">
                        <h3 style="color: #c0392b; margin-bottom: 15px; text-align: center;">
                            🥈 Score du Perdant
                        </h3>
                        <div class="form-group">
                            <input type="number" name="loser_score" min="0" max="9" value="0" required 
                                   style="font-size: 2.5rem; text-align: center; font-weight: bold; height: 70px; color: #c0392b; border: 2px solid #e74c3c;">
                        </div>
                    </div>
                </div>

                <!-- 3. GAMELLES -->
                <div style="background: #f9f9f9; padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid #ddd;">
                    <h3 style="color: #7f8c8d; margin-bottom: 20px; font-size: 1.1rem;">🍳 Gamelles (Optionnel)</h3>
                    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 150px;">
                            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Gamelles prises par <strong>MOI</strong></label>
                            <input type="number" name="my_gamelles" min="0" value="0" style="text-align: center; font-size: 1.2rem;">
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Gamelles prises par <strong><?= htmlspecialchars($opponent['name']) ?></strong></label>
                            <input type="number" name="opponent_gamelles" min="0" value="0" style="text-align: center; font-size: 1.2rem;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" class="btn-save" style="flex: 1; max-width: 300px; font-size: 1.2rem; padding: 15px; background: linear-gradient(135deg, #2ecc71, #27ae60); box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);">
                        💾 Valider le Match
                    </button>
                </div>
            </form>

            <script>
            function updateWinnerUI(radio) {
                const cardMe = document.getElementById('card-me');
                const cardOpponent = document.getElementById('card-opponent');
                
                // Reset styles
                cardMe.style.borderColor = '#e0e0e0';
                cardMe.style.opacity = '0.7';
                cardMe.querySelector('div:last-child').textContent = 'PERDANT';
                cardMe.querySelector('div:last-child').style.color = '#7f8c8d';
                
                cardOpponent.style.borderColor = '#e0e0e0';
                cardOpponent.style.opacity = '0.7';
                cardOpponent.querySelector('div:last-child').textContent = 'PERDANT';
                cardOpponent.querySelector('div:last-child').style.color = '#7f8c8d';

                // Apply active style
                if (radio.value === 'me') {
                    cardMe.style.borderColor = '#2ecc71';
                    cardMe.style.opacity = '1';
                    cardMe.style.backgroundColor = '#e8f8f5';
                    cardMe.querySelector('div:last-child').textContent = 'VAINQUEUR';
                    cardMe.querySelector('div:last-child').style.color = '#2ecc71';
                    
                    cardOpponent.style.backgroundColor = 'white';
                } else {
                    cardOpponent.style.borderColor = '#2ecc71';
                    cardOpponent.style.opacity = '1';
                    cardOpponent.style.backgroundColor = '#e8f8f5';
                    cardOpponent.querySelector('div:last-child').textContent = 'VAINQUEUR';
                    cardOpponent.querySelector('div:last-child').style.color = '#2ecc71';
                    
                    cardMe.style.backgroundColor = 'white';
                }
            }
            
            // Init UI
            updateWinnerUI(document.querySelector('input[name="winner"]:checked'));
            </script>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
