<div class="animate-fade-in">
    <div class="flex-between align-center" style="margin-bottom: 2rem;">
        <h2>🏆 Gestion des Tournois</h2>
        <a href="index.php" class="btn btn-cancel">← Retour Dashboard</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <strong>✅</strong> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3>➕ Créer un Nouveau Tournoi</h3>
        <form method="POST" action="" class="dashboard-columns align-end">
            <div class="form-group" style="flex: 2;">
                <label for="tournament_name">🎯 Nom du Tournoi</label>
                <input type="text" id="tournament_name" name="tournament_name" required placeholder="Ex: Tournoi d'Hiver 2025">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="start_date">📅 Date de Début</label>
                <input type="date" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group" style="flex: 0;">
                <button type="submit" class="btn-primary" style="height: 50px; margin-bottom: 1px;">✨ Créer</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="flex-between align-center" style="margin-bottom: 1rem;">
            <h3>📋 Tournois Existants</h3>
            <span class="text-muted">Total : <strong><?= count($tournaments) ?></strong></span>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>🎯 Nom du Tournoi</th>
                        <th>📅 Date de Début</th>
                        <th class="text-center">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tournaments)): ?>
                        <?php foreach ($tournaments as $index => $t): ?>
                            <tr>
                                <td data-label="ID" style="color: var(--primary); font-weight: bold;">#<?= htmlspecialchars($t['id']) ?></td>
                                <td data-label="Nom"><strong><?= htmlspecialchars($t['name']) ?></strong></td>
                                <td data-label="Date"><?= date('d/m/Y', strtotime($t['start_date'])) ?></td>
                                <td data-label="Actions" class="text-center">
                                    <form method="POST" action="" style="display: inline-block;" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce tournoi ?\n\nToutes les inscriptions seront également supprimées !')">
                                        <input type="hidden" name="delete_id" value="<?= $t['id'] ?>">
                                        <button type="submit" class="btn-danger btn-sm" title="Supprimer le tournoi">🗑️ Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding: 2rem;">
                                📭 Aucun tournoi trouvé. Créez-en un ci-dessus !
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
}
</style>
