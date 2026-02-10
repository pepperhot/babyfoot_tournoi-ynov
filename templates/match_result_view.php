<div class="animate-fade-in">
    <?php if (!$invitation): ?>
        <div class="card text-center" style="padding: 40px;">
            <h2 style="color: var(--danger); border: none;">❌ Match introuvable</h2>
            <p class="text-muted" style="margin: 20px 0;">
                Ce match n'existe pas ou a expiré.
            </p>
            <a href="players.php" class="btn btn-cancel">← Retour</a>
        </div>
    <?php else: ?>
        
        <!-- Interface de Match VS -->
        <div class="match-vs-container" style="background: linear-gradient(135deg, var(--bg-card), #F3F4F6);">
            <div style="flex: 100%; text-align: center;">
                <h1 style="margin-bottom: 20px; font-size: 2rem; color: var(--primary); margin-top: 0;">⚔️ DUEL EN COURS</h1>
            </div>
            
            <!-- Joueur 1 (Moi) -->
            <div style="text-align: center;">
                <div class="player-avatar-large" style="border-color: var(--primary);">
                    👤
                </div>
                <h2>MOI</h2>
                <p class="text-muted"><?= htmlspecialchars($_SESSION['username'] ?? 'Joueur') ?></p>
            </div>

            <!-- VS Badge -->
            <div style="font-size: 3rem; font-weight: 900; color: var(--warning); padding: 0 20px;">
                VS
            </div>

            <!-- Joueur 2 (Adversaire) -->
            <div style="text-align: center;">
                <div class="player-avatar-large" style="border-color: var(--danger);">
                    😈
                </div>
                <h2><?= htmlspecialchars($opponent['name']) ?></h2>
                <p class="text-muted">Adversaire</p>
            </div>
        </div>

        <div class="card">
            <h2 class="text-center" style="margin-bottom: 20px; border: none;">📝 Saisir les Scores</h2>
            <p class="text-center text-muted" style="margin-bottom: 30px;">
                Le match est terminé ? Entrez les résultats ci-dessous.
            </p>

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

            <form method="POST" style="margin-top: 30px;">
                
                <!-- 1. QUI A GAGNÉ ? -->
                <div style="margin-bottom: 40px;">
                    <h3 class="text-center" style="margin-bottom: 20px; font-size: 1.3rem;">🏆 Qui a remporté le match ?</h3>
                    <div class="flex-row justify-between" style="gap: 20px;">
                        <label style="cursor: pointer; flex: 1;">
                            <input type="radio" name="winner" value="me" checked style="display: none;" onchange="updateWinnerUI(this)">
                            <div class="winner-card selected bg-white" id="card-me" style="border: 3px solid var(--border-color); border-radius: 15px; padding: 20px; transition: all 0.3s ease; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">😎</div>
                                <div style="font-weight: bold; font-size: 1.2rem;">MOI</div>
                                <div style="color: var(--success); font-weight: bold; margin-top: 5px;" class="result-text">VAINQUEUR</div>
                            </div>
                        </label>

                        <label style="cursor: pointer; flex: 1;">
                            <input type="radio" name="winner" value="opponent" style="display: none;" onchange="updateWinnerUI(this)">
                            <div class="winner-card bg-white" id="card-opponent" style="border: 3px solid var(--border-color); border-radius: 15px; padding: 20px; transition: all 0.3s ease; opacity: 0.7; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 10px;">😈</div>
                                <div style="font-weight: bold; font-size: 1.2rem;"><?= htmlspecialchars($opponent['name']) ?></div>
                                <div style="color: var(--text-muted); font-weight: bold; margin-top: 5px;" class="result-text">PERDANT</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. SCORES -->
                <div class="dashboard-columns" style="margin-bottom: 30px;">
                    <!-- Score Gagnant -->
                    <div class="dashboard-col" style="background: #ECFDF5; padding: 20px; border-radius: 15px; border: 2px solid var(--success);">
                        <h3 style="color: var(--success); margin-bottom: 15px; text-align: center;">
                            🥇 Score du Vainqueur
                        </h3>
                        <div class="form-group">
                            <input type="number" name="winner_score" min="0" max="10" value="10" required 
                                   style="font-size: 2.5rem; text-align: center; font-weight: bold; height: 70px; color: var(--success); border: 2px solid var(--success);">
                        </div>
                    </div>

                    <!-- Score Perdant -->
                    <div class="dashboard-col" style="background: #FEF2F2; padding: 20px; border-radius: 15px; border: 2px solid var(--danger);">
                        <h3 style="color: var(--danger); margin-bottom: 15px; text-align: center;">
                            🥈 Score du Perdant
                        </h3>
                        <div class="form-group">
                            <input type="number" name="loser_score" min="0" max="9" value="0" required 
                                   style="font-size: 2.5rem; text-align: center; font-weight: bold; height: 70px; color: var(--danger); border: 2px solid var(--danger);">
                        </div>
                    </div>
                </div>

                <!-- 3. GAMELLES -->
                <div style="background: #F9FAFB; padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid var(--border-color);">
                    <h3 class="text-center text-muted" style="margin-bottom: 20px; font-size: 1.1rem;">🍳 Gamelles (Optionnel)</h3>
                    <div class="flex-row justify-between flex-wrap" style="gap: 20px;">
                        <div class="flex-1">
                            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem; color: var(--success);">Gamelles prises par le <strong>VAINQUEUR</strong></label>
                            <input type="number" name="winner_gamelles" min="0" value="0" style="text-align: center; font-size: 1.2rem; border-color: var(--success);">
                        </div>
                        <div class="flex-1">
                            <label style="display: block; margin-bottom: 5px; font-size: 0.9rem; color: var(--danger);">Gamelles prises par le <strong>PERDANT</strong></label>
                            <input type="number" name="loser_gamelles" min="0" value="0" style="text-align: center; font-size: 1.2rem; border-color: var(--danger);">
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-primary" style="font-size: 1.2rem; padding: 15px 30px;">
                        💾 Valider le Match
                    </button>
                </div>
            </form>

            <script>
            function updateWinnerUI(radio) {
                const cardMe = document.getElementById('card-me');
                const cardOpponent = document.getElementById('card-opponent');
                
                // Reset styles
                cardMe.style.borderColor = 'var(--border-color)';
                cardMe.style.opacity = '0.7';
                cardMe.querySelector('.result-text').textContent = 'PERDANT';
                cardMe.querySelector('.result-text').style.color = 'var(--text-muted)';
                
                cardOpponent.style.borderColor = 'var(--border-color)';
                cardOpponent.style.opacity = '0.7';
                cardOpponent.querySelector('.result-text').textContent = 'PERDANT';
                cardOpponent.querySelector('.result-text').style.color = 'var(--text-muted)';

                // Apply active style
                if (radio.value === 'me') {
                    cardMe.style.borderColor = 'var(--success)';
                    cardMe.style.opacity = '1';
                    cardMe.style.backgroundColor = '#ECFDF5';
                    cardMe.querySelector('.result-text').textContent = 'VAINQUEUR';
                    cardMe.querySelector('.result-text').style.color = 'var(--success)';
                    
                    cardOpponent.style.backgroundColor = 'white';
                } else {
                    cardOpponent.style.borderColor = 'var(--success)';
                    cardOpponent.style.opacity = '1';
                    cardOpponent.style.backgroundColor = '#ECFDF5';
                    cardOpponent.querySelector('.result-text').textContent = 'VAINQUEUR';
                    cardOpponent.querySelector('.result-text').style.color = 'var(--success)';
                    
                    cardMe.style.backgroundColor = 'white';
                }
            }
            
            // Init UI
            updateWinnerUI(document.querySelector('input[name="winner"]:checked'));
            </script>
        </div>
    <?php endif; ?>
</div>