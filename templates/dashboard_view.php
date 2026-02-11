<div class="animate-fade-in">
    <h2>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?> !</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'match_saved'): ?>
        <div class="alert alert-success">
            <strong>✅ Résultats enregistrés avec succès !</strong>
        </div>
    <?php endif; ?>

    <?php if (!empty($pending_invitations)): ?>
    <div class="card" style="border-left: 4px solid var(--danger);">
        <div class="flex-between flex-wrap">
            <h3 style="margin: 0; color: var(--danger);">📨 Invitations en Attente (<?= count($pending_invitations) ?>)</h3>
            <a href="players.php" class="btn btn-primary btn-sm">
                Voir toutes →
            </a>
        </div>
        <div class="flex-col" style="margin-top: 15px;">
            <?php foreach ($pending_invitations as $inv): ?>
            <div class="card shadow-sm" style="margin-bottom: 0;">
                <div class="flex-between flex-wrap gap-sm">
                    <div style="flex: 1;">
                        <strong style="color: var(--primary);">👤 <?= htmlspecialchars($inv['sender_name']) ?></strong>
                        <p class="text-muted"><?= htmlspecialchars($inv['message']) ?></p>
                        <small class="text-muted">⏰ <?= date('d/m/Y H:i', strtotime($inv['created_at'])) ?></small>
                    </div>
                    <div class="flex-row gap-sm flex-small-row">
                        <form method="POST" action="players.php">
                            <input type="hidden" name="action" value="respond">
                            <input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
                            <input type="hidden" name="response" value="accepted">
                            <button type="submit" class="btn-success btn-sm">
                                ✅ Accepter
                            </button>
                        </form>
                        <form method="POST" action="players.php">
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

    <div class="dashboard-columns">
        <div class="card dashboard-col">
            <h3>Vos 5 derniers scores</h3>
            <ul>
                <?php if (empty($history)): ?>
                    <li class="text-muted" style="padding: 10px;">Aucun score enregistré</li>
                <?php else: ?>
                    <?php foreach($history as $h): ?>
                        <li class="flex-between" style="padding: 10px; border-bottom: 1px solid var(--border-color);">
                            <span class="badge warning"><?= $h['points'] ?> pts</span>
                            <span class="text-muted"><?= $h['match_type'] ?> - <?= date('d/m H:i', strtotime($h['created_at'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="card dashboard-col">
            <h3>Classement Général 🏆</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Joueur</th>
                            <th>Points Totaux</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($leaderboard as $rank): ?>
                        <tr>
                            <td data-label="Joueur"><?= htmlspecialchars($rank['username']) ?></td>
                            <td data-label="Points Totaux"><strong><?= $rank['total_points'] ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>