<div class="container">
    <h2>Tournois Disponibles</h2>

    <?php if (!empty($msg)): ?>
        <p class="success-message"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Date</th>
                <th>Inscription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tournaments)): ?>
                <?php foreach ($tournaments as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= htmlspecialchars($t['start_date']) ?></td>
                        <td>
                            <?= $t['is_registered'] ? '<span style="color:green;">Oui</span>' : '<span style="color:red;">Non</span>' ?>
                        </td>
                        <td>
                            <?php if (!$t['is_registered']): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="tournament_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-primary">S’inscrire</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="unregister_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Se désinscrire</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun tournoi disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.container { max-width: 900px; margin: 20px auto; }
.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.data-table th, .data-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
.success-message { color: #2ecc71; background: #e8f8f5; border: 1px solid #2ecc71; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
.btn-primary { background-color: #3498db; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
.btn-danger { background-color: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
</style>
