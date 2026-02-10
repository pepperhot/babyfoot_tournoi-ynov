<div class="animate-fade-in">
    <div class="flex-between flex-wrap" style="margin-bottom: 1.5rem;">
        <h2>👥 Liste des Participants</h2>
        <div class="badge" style="background: var(--primary); color: white; padding: 0.5rem 1rem; font-size: 1rem;">
            📊 <?= $total_participants-1 ?> Adversaire(s) potentiel(s)
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
    <div class="card animate-fade-in" style="border-left: 4px solid var(--danger);">
        <h3 style="color: var(--danger);">📨 Invitations Reçues (<?= count($pending_invitations) ?>)</h3>
        
        <div class="flex-col">
            <?php foreach ($pending_invitations as $inv): ?>
            <div class="card shadow-sm" style="margin-bottom: 0;">
                <div class="flex-between flex-wrap gap-sm">
                    <div>
                        <strong style="color: var(--primary); font-size: 1.1rem;">👤 <?= htmlspecialchars($inv['sender_name'] ?? 'Inconnu') ?></strong>
                        <p class="text-muted" style="margin: 0.5rem 0;">&laquo; <?= htmlspecialchars($inv['message'] ?? '') ?> &raquo;</p>
                        <small class="text-muted">⏰ <?= isset($inv['created_at']) ? date('d/m/Y H:i', strtotime($inv['created_at'])) : '--/--' ?></small>
                    </div>
                    <div class="flex-row gap-sm flex-small-row">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="response" value="accepted">
                            <button type="submit" class="btn-success btn-sm">
                                ✅ Accepter
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="response" value="declined">
                            <button type="submit" class="btn-danger btn-sm">
                                ❌ Refuser
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <h3>👥 Tous les Participants (<?= count($users) ?>)</h3>
        <p class="text-muted" style="margin-bottom: 20px;">
            Cliquez sur "Inviter" pour défier un joueur.
        </p>

        <?php if (empty($users)): ?>
            <div class="text-center" style="padding: 3rem; color: var(--text-muted);">
                <p style="font-size: 4rem;">🎮</p>
                <p>Aucun autre participant inscrit pour le moment</p>
            </div>
        <?php else: ?>
            <div class="grid-cards">
                <?php foreach ($users as $player): ?>
                <div class="card" style="margin: 0; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--border-color);">
                    <div style="margin-bottom: 1rem;">
                        <div class="flex-row gap-sm align-center" style="margin-bottom: 1rem;">
                            <div style="background: var(--bg-input); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">👤</div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-main);">
                                <?= htmlspecialchars($player['display_name']) ?>
                            </h4>
                        </div>
                        <div class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem; word-break: break-all;">
                            📧 <?= htmlspecialchars($player['email']) ?>
                        </div>
                        <div class="flex-row gap-sm" style="font-size: 0.9rem;">
                            <span class="badge warning">
                                🏆 <?= $player['total_points'] ?? 0 ?> pts
                            </span>
                            <span class="badge" style="background: var(--bg-input); color: var(--text-muted);">
                                🎮 <?= $player['total_matches'] ?? 0 ?> matchs
                            </span>
                        </div>
                    </div>
                    
                    <button 
                        onclick="openInviteModal(<?= $player['id'] ?>, '<?= htmlspecialchars($player['display_name'], ENT_QUOTES) ?>')"
                        class="btn-primary"
                        style="width: 100%; margin-top: 1rem;"
                    >
                        ⚡ Inviter
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal d'invitation -->
<div id="inviteModal" class="modal">
    <div class="modal-content animate-fade-in">
        <span class="close" onclick="closeInviteModal()" title="Fermer">&times;</span>
        <h2 style="margin-bottom: 1rem; color: var(--primary);">⚡ Inviter un joueur</h2>
        <p class="text-muted" style="margin-bottom: 1.5rem;">
            Envoyez une invitation à <strong id="playerName" style="color: var(--text-main);"></strong>
        </p>
        
        <form method="POST" action="players.php" id="inviteForm">
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
                    Un petit mot sympa augmente vos chances !
                </small>
            </div>
            
            <div class="flex-row gap-sm" style="margin-top: 1.5rem;">
                <button type="submit" class="btn-primary" style="flex: 1;">📨 Envoyer</button>
                <button type="button" class="btn-cancel" onclick="closeInviteModal()">Annuler</button>
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

window.onclick = function(event) {
    if (event.target == document.getElementById('inviteModal')) {
        closeInviteModal();
    }
}
</script>