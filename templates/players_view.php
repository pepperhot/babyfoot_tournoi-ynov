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
            📊 <?= $total_participants ?> Participant(s)
        </div>
    </div>

    <?php if (!empty($message)): 
        list($type, $text) = explode('|', $message);
    ?>
        <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
            <strong><?= $type === 'success' ? '✅' : '⚠️' ?></strong>
            <?= htmlspecialchars($text) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($pending_invitations)): ?>
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #fff5f5, #ffe0e0); border-left: 4px solid #e74c3c;">
        <h3>📨 Invitations Reçues (<?= count($pending_invitations) ?>)</h3>
        <div style="margin-top: 15px;">
            <?php foreach ($pending_invitations as $inv): ?>
            <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div>
                    <strong style="color: #667eea;">👤 <?= htmlspecialchars($inv['sender_name']) ?></strong>
                    <p style="margin: 5px 0; color: #7f8c8d;"><?= htmlspecialchars($inv['message']) ?></p>
                    <small style="color: #95a5a6;">⏰ <?= date('d/m/Y H:i', strtotime($inv['created_at'])) ?></small>
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
    </div>
    <?php endif; ?>

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
        
        <form method="POST" id="inviteForm">
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

.btn-invite:hover:not(:disabled) {
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

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('inviteModal');
    if (event.target == modal) {
        closeInviteModal();
    }
}

// Animation de disparition des messages de succès
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
