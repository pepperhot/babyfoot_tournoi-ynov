<div class="animate-fade-in">
    <h2>🏆 Tournois Disponibles</h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
            <table>
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
                                <td data-label="Nom"><strong><?= htmlspecialchars($t['name']) ?></strong></td>
                                <td data-label="Date"><?= htmlspecialchars($t['start_date']) ?></td>
                                <td data-label="Inscription">
                                    <?= $t['is_registered'] 
                                        ? '<span class="status-badge status-success">Inscrit</span>' 
                                        : '<span class="status-badge status-warning">Non Inscrit</span>' ?>
                                </td>
                                <td data-label="Action">
                                    <?php if (!$t['is_registered']): ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="tournament_id" value="<?= $t['id'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">S’inscrire</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="unregister_id" value="<?= $t['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Se désinscrire</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-muted text-center">Aucun tournoi disponible pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>