<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>🏆 Gestion des Tournois</h2>
        <a href="index.php" class="btn-secondary" style="text-decoration: none; padding: 10px 20px; border-radius: 8px; background: linear-gradient(135deg, #95a5a6, #7f8c8d); color: white;">← Retour au Dashboard</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success" style="margin-bottom: 25px;">
            <strong>✅</strong> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom: 30px;">
        <h3>➕ Créer un Nouveau Tournoi</h3>
        <form method="POST" action="" style="margin-top: 20px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 2; min-width: 250px;">
                    <label for="tournament_name">🎯 Nom du Tournoi</label>
                    <input type="text" id="tournament_name" name="tournament_name" required placeholder="Ex: Tournoi d'Hiver 2025">
                </div>
                <div class="form-group" style="flex: 1; min-width: 180px;">
                    <label for="start_date">📅 Date de Début</label>
                    <input type="date" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div style="flex: 0 0 auto; display: flex; align-items: flex-end;">
                    <button type="submit" style="white-space: nowrap; height: 52px;">✨ Créer le tournoi</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>📋 Tournois Existants</h3>
            <span style="color: #7f8c8d; font-size: 0.9rem;">
                Total : <strong><?= count($tournaments) ?></strong> tournoi(s)
            </span>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>🎯 Nom du Tournoi</th>
                        <th style="width: 150px;">📅 Date de Début</th>
                        <th style="width: 120px; text-align: center;">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tournaments)): ?>
                        <?php foreach ($tournaments as $index => $t): ?>
                            <tr>
                                <td style="font-weight: 600; color: #667eea;">#<?= htmlspecialchars($t['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($t['name']) ?></strong>
                                </td>
                                <td><?= date('d/m/Y', strtotime($t['start_date'])) ?></td>
                                <td style="text-align: center;">
                                    <form method="POST" action="" style="display: inline-block;" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce tournoi ?\n\nToutes les inscriptions seront également supprimées !')">
                                        <input type="hidden" name="delete_id" value="<?= $t['id'] ?>">
                                        <button type="submit" class="btn-delete" title="Supprimer le tournoi">🗑️ Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #95a5a6;">
                                📭 Aucun tournoi trouvé. Créez-en un ci-dessus !
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.btn-delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(231, 76, 60, 0.5);
}

tbody tr:hover {
    transform: translateX(5px);
    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
}
</style>
