
<div class="container">
    <h2>Gestion des Tournois</h2>

    <?php if (!empty($message)): ?>
        <p class="success-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="form-card">
        <h3>Créer un Nouveau Tournoi</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="tournament_name">Nom du Tournoi</label>
                <input type="text" id="tournament_name" name="tournament_name" required>
            </div>
            <div class="form-group">
                <label for="start_date">Date de Début</label>
                <input type="date" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>

    <h3>Tournois Existants</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Date de Début</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tournaments)): ?>
                <?php foreach ($tournaments as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['id']) ?></td>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= htmlspecialchars($t['start_date']) ?></td>
                        <td>
                            <form method="POST" action="" onsubmit="return confirm('Supprimer ce tournoi ?')">
                                <input type="hidden" name="delete_id" value="<?= $t['id'] ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun tournoi trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.form-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 25px; }
.btn-primary { background-color: #3498db; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
.btn-danger { background-color: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.data-table th, .data-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
.success-message { color: #2ecc71; background: #e8f8f5; border: 1px solid #2ecc71; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
</style>
