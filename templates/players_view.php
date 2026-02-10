<div class="animate-fade-in">
    <div class="flex-between flex-wrap" style="margin-bottom: 20px;">
        <h2>👥 Liste des Participants</h2>
        <div class="badge-count" style="padding: 10px 20px; border-radius: 20px; font-weight: 600; width: auto; height: auto; position: static; box-shadow: none;">
            📊 <?= $total_participants-1 ?> Participant(s)
        </div>
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

    <?php if (!empty($pending_invitations)): ?>
    <div class="card animate-fade-in" style="border-left: 4px solid var(--danger); background-color: #FEF2F2;">
        <h3>📨 Invitations Reçues (<?= count($pending_invitations) ?>)</h3>
        
        <div class="flex-col">
            <?php foreach ($pending_invitations as $inv): ?>
            <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);" class="flex-between flex-wrap gap-sm">
                <div>
                    <strong style="color: var(--primary);">👤 <?= htmlspecialchars($inv['sender_name'] ?? 'Inconnu') ?></strong>
                    <p style="margin: 5px 0; color: var(--text-muted);"><?= htmlspecialchars($inv['message'] ?? '') ?></p>
                    <small style="color: var(--text-muted);">⏰ <?= isset($inv['created_at']) ? date('d/m/Y H:i', strtotime($inv['created_at'])) : '--/--' ?></small>
                </div>
                <div class="flex-row gap-sm flex-small-row">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="respond">
                        <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                        <input type="hidden" name="response" value="accepted">
                        <button type="submit" class="btn-success">
                            ✅ Accepter
                        </button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="respond">
                        <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                        <input type="hidden" name="response" value="declined">
                        <button type="submit" class="btn-danger">
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
        <p class="text-muted" style="margin-bottom: 20px;">
            Liste complète de tous les joueurs inscrits sur la plateforme
        </p>

        <?php if (empty($users)): ?>
            <div class="text-center" style="padding: 40px; color: var(--text-muted);">
                <p style="font-size: 3rem;">🎮</p>
                <p>Aucun autre participant inscrit pour le moment</p>
            </div>
        <?php else: ?>
            <div class="grid-cards">
                <?php foreach ($users as $player): ?>
                <div class="card" style="margin: 0; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="margin-bottom: 15px;">
                        <div class="flex-row gap-sm align-center" style="margin-bottom: 8px;">
                            <span style="font-size: 1.5rem;">👤</span>
                            <h4 style="margin: 0; font-size: 1.1rem;">
                                <?= htmlspecialchars($player['display_name']) ?>
                            </h4>
                        </div>
                        <div class="text-muted" style="font-size: 0.85rem; margin-bottom: 5px;">
                            📧 <?= htmlspecialchars($player['email']) ?>
                        </div>
                        <div class="flex-row gap-sm" style="font-size: 0.9rem; margin-top: 10px;">
                            <span class="badge warning" style="background: var(--warning); color: white;">
                                🏆 <?= $player['total_points'] ?? 0 ?> pts
                            </span>
                            <span class="text-muted">
                                🎮 <?= $player['total_matches'] ?? 0 ?> match(s)
                            </span>
                        </div>
                    </div>
                    
                    <button 
                        onclick="openInviteModal(<?= $player['id'] ?>, '<?= htmlspecialchars($player['display_name'], ENT_QUOTES) ?>')"
                        class="btn-primary"
                        style="width: 100%;"
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
<div id="inviteModal" class="modal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div class="modal-content animate-fade-in" style="background: white; margin: 10% auto; padding: 20px; width: 90%; max-width: 500px; border-radius: var(--radius-lg); position: relative;">
        <span class="close" onclick="closeInviteModal()" title="Fermer" style="position: absolute; right: 15px; top: 15px; cursor: pointer; font-size: 1.5rem;">&times;</span>
        <h2 style="margin-bottom: 10px; color: var(--primary);">⚡ Inviter un joueur</h2>
        <p class="text-muted" style="margin-bottom: 25px;">
            Envoyez une invitation à <strong id="playerName"></strong>
        </p>
        
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
                <small class="text-muted">
                    Personnalisez votre invitation pour motiver votre adversaire !
                </small>
            </div>
            
            <div class="flex-row gap-sm">
                <button type="submit" class="btn-primary flex-1">📨 Envoyer l'invitation</button>
                <button type="button" class="btn-cancel" onclick="closeInviteModal()">❌ Annuler</button>
            </div>
        </form>
    </div>
</div>

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
    if (event.target == document.getElementById('inviteModal')) {
        closeInviteModal();
    }
}
</script>