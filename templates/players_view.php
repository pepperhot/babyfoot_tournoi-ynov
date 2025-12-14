<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - Babyfoot</title>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <h2>👥 Liste des Participants</h2>
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 10px 20px; border-radius: 20px; font-weight: 600; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
            📊 <?= $total_participants-1 ?> Participant(s)
        </div>
    </div>

    <?php
    // TENTATIVE DE RECUPERATION DE L'ID DU MATCH (FIX pour le contrôleur manquant)
    // Si on vient d'accepter une invitation mais que $match_id n'est pas défini
    if (!isset($match_id) && !isset($game_id) && $_SERVER['REQUEST_METHOD'] === 'POST' && 
        isset($_POST['action']) && $_POST['action'] === 'respond' && $_POST['response'] === 'accepted') {
        
        // On essaie d'accéder à la base de données si disponible pour trouver le match
        global $pdo; 
        if (isset($pdo) && isset($_SESSION['user_id'])) {
            try {
                // On cherche le dernier match créé impliquant l'utilisateur (créé il y a moins de 10 secondes idéalement)
                $stmt = $pdo->prepare("SELECT id FROM matches WHERE (player1_id = ? OR player2_id = ?) ORDER BY created_at DESC LIMIT 1");
                $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
                $last_match = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($last_match) {
                    $match_id = $last_match['id'];
                    // On ajoute un indicateur au message
                    $message = ($message ?? "") . "|Match #$match_id prêt !";
                }
            } catch (Exception $e) {
                // Silencieux si erreur
            }
        }
    }
    
    // Définition de l'ID de redirection final
    $redirect_id = $match_id ?? $game_id ?? null;
    ?>

    <!-- DEBUG COMPLET : Pour comprendre pourquoi les notifs ne s'affichent pas -->
    <div style="background: #fff3cd; border: 2px solid #ffecb5; padding: 15px; margin-bottom: 20px; border-radius: 10px; color: #856404;">
        <h4 style="margin-top: 0;">🛠️ DIAGNOSTIC NOTIFICATIONS & REDIRECTION</h4>
        <ul>
            <li><strong>Utilisateur connecté (ID):</strong> <?= $_SESSION['user_id'] ?? 'Non connecté' ?></li>
            <li><strong>Variable $pending_invitations:</strong> 
                <?php 
                if (isset($pending_invitations)) {
                    echo count($pending_invitations) . " invitation(s) trouvée(s).";
                } else {
                    echo "⚠️ NON DÉFINIE (Le problème vient du contrôleur/DB)";
                }
                ?>
            </li>
            <li><strong>Message flash ($message):</strong> <?= !empty($message) ? htmlspecialchars($message) : 'Aucun message' ?></li>
            <li><strong>Redirection Match:</strong> <?= $redirect_id ? "✅ ID TROUVÉ: $redirect_id" : '⚠️ Variable $match_id manquante (Impossible de rediriger)' ?></li>
        </ul>
    </div>

    <?php if (!empty($message)): 
        // Modification pour éviter l'erreur si le message n'a pas de "|" et forcer l'affichage
        $parts = explode('|', $message);
        $type = count($parts) > 1 ? $parts[0] : 'info';
        $text = count($parts) > 1 ? $parts[1] : $message;
    ?>
        <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
            <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
            <?= htmlspecialchars($text) ?>
            
            <?php if ($redirect_id): ?>
                <div style="margin-top: 15px; text-align: center;">
                    <a href="index.php?page=game&id=<?= $redirect_id ?>" class="btn-invite" style="display: inline-block; text-decoration: none; background: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; font-size: 1.1em;">
                        🎮 ACCÉDER AU MATCH MAINTENANT
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- J'ai retiré la condition if (!empty($pending_invitations)) pour forcer l'affichage du bloc et voir s'il est vide -->
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #fff5f5, #ffe0e0); border-left: 4px solid #e74c3c;">
        <h3>📨 Invitations Reçues (<?= !empty($pending_invitations) ? count($pending_invitations) : 0 ?>)</h3>
        
        <?php if (empty($pending_invitations)): ?>
            <p style="padding: 10px; background: rgba(255,255,255,0.5); border-radius: 5px;">
                Aucune invitation en attente. <br>
                <small>Si vous devriez en voir une, vérifiez la table 'invitations' dans la base de données.</small>
            </p>
        <?php else: ?>
            <div style="margin-top: 15px;">
                <?php foreach ($pending_invitations as $inv): ?>
                <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div>
                        <strong style="color: #667eea;">👤 <?= htmlspecialchars($inv['sender_name'] ?? 'Inconnu') ?></strong>
                        <p style="margin: 5px 0; color: #7f8c8d;"><?= htmlspecialchars($inv['message'] ?? '') ?></p>
                        <small style="color: #95a5a6;">⏰ <?= isset($inv['created_at']) ? date('d/m/Y H:i', strtotime($inv['created_at'])) : '--/--' ?></small>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="response" value="accepted">
                            <button type="submit" style="background: linear-gradient(135deg, #2ecc71, #27ae60); padding: 8px 16px;">
                                ✅ Accepter
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="response" value="declined">
                            <button type="submit" class="btn-cancel" style="background: linear-gradient(135deg, #95a5a6, #7f8c8d); padding: 8px 16px;">
                                ❌ Refuser
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>👥 Tous les Participants (<?= count($users) ?>)</h3>
        <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 20px;">
            Liste complète de tous les joueurs inscrits sur la plateforme
        </p>

        <?php if (empty($users)): ?>
            <div style="text-align: center; padding: 40px; color: #95a5a6;">
                <p style="font-size: 3rem;">🎮</p>
                <p>Aucun autre participant inscrit pour le moment</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach ($users as $player): ?>
                <div class="player-card" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; border: 2px solid #ecf0f1;">
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span style="font-size: 1.5rem;">👤</span>
                            <h4 style="margin: 0; color: #2c3e50; font-size: 1.1rem;">
                                <?= htmlspecialchars($player['display_name']) ?>
                            </h4>
                        </div>
                        <div style="color: #7f8c8d; font-size: 0.85rem; margin-bottom: 5px;">
                            📧 <?= htmlspecialchars($player['email']) ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px; color: #7f8c8d; font-size: 0.9rem; margin-top: 10px;">
                            <span style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 5px 12px; border-radius: 15px; font-weight: 600;">
                                🏆 <?= $player['total_points'] ?? 0 ?> pts
                            </span>
                            <span>
                                🎮 <?= $player['total_matches'] ?? 0 ?> match(s)
                            </span>
                        </div>
                    </div>
                    
                    <button 
                        onclick="openInviteModal(<?= $player['id'] ?>, '<?= htmlspecialchars($player['display_name'], ENT_QUOTES) ?>')"
                        class="btn-invite"
                        style="width: 100%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);"
                    >
                        ⚡ Inviter à jouer
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal d'invitation -->
<div id="inviteModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeInviteModal()" title="Fermer">&times;</span>
        <h2 style="margin-bottom: 10px; color: #1e3c72;">⚡ Inviter un joueur</h2>
        <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 25px;">
            Envoyez une invitation à <strong id="playerName"></strong>
        </p>
        
        <!-- Ajout de l'action explicite pour être sûr du chemin d'envoi -->
        <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" id="inviteForm">
            <input type="hidden" name="action" value="invite">
            <input type="hidden" name="receiver_id" id="receiver_id">
            <input type="hidden" name="receiver_name" id="receiver_name">
            
            <div class="form-group">
                <label for="message">💬 Message d'invitation</label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="3" 
                    placeholder="Ex: Hey ! Tu veux jouer un match rapide ?"
                    style="resize: vertical; min-height: 80px;"
                >Viens jouer un match de babyfoot !</textarea>
                <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 5px;">
                    Personnalisez votre invitation pour motiver votre adversaire !
                </small>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn-save" style="flex: 1;">📨 Envoyer l'invitation</button>
                <button type="button" class="btn-cancel" onclick="closeInviteModal()" style="flex: 0 0 auto;">❌ Annuler</button>
            </div>
        </form>
    </div>
</div>

<style>
.player-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-invite:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

/* Close button du modal */
.close {
    position: absolute;
    right: 25px;
    top: 15px;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
    color: #95a5a6;
    transition: all 0.3s ease;
    line-height: 1;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close:hover {
    color: #e74c3c;
    background: #f8f9fa;
    transform: rotate(90deg);
}
</style>

<script>
function openInviteModal(playerId, playerName) {
    document.getElementById('receiver_id').value = playerId;
    document.getElementById('receiver_name').value = playerName;
    document.getElementById('playerName').textContent = playerName;
    document.getElementById('inviteModal').style.display = 'block';
}

function closeInviteModal() {
    document.getElementById('inviteModal').style.display = 'none';
}

// GESTION DE LA REDIRECTION VERS LE JEU
<?php 
if ($redirect_id): 
?>
    console.log("Match accepté ! Redirection vers le match #<?= $redirect_id ?>");
    
    // Création d'un overlay de redirection pour être sûr que l'utilisateur voit qu'il se passe quelque chose
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0,0,0,0.85)';
    overlay.style.color = 'white';
    overlay.style.display = 'flex';
    overlay.style.flexDirection = 'column';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';
    overlay.style.zIndex = '9999';
    overlay.innerHTML = '<h1 style="font-size: 3em;">🎮 Match trouvé !</h1><p style="font-size: 1.5em;">Redirection vers l\'arène de jeu...</p>';
    document.body.appendChild(overlay);

    setTimeout(function() {
        // Ajustez l'URL ci-dessous selon votre structure (ex: game.php?id=...)
        window.location.href = 'index.php?page=game&id=<?= $redirect_id ?>';
    }, 1500); 
<?php endif; ?>

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('inviteModal');
    if (event.target == modal) {
        closeInviteModal();
    }
}

// J'ai commenté l'auto-hide pour forcer la notification à rester visible
/*
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
*/
</script>
</body>
</html>
