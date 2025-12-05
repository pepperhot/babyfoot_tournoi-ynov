<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>admin dashboard Babyfoot</title>

    <!-- CHEMIN CORRECT -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="container">
    <h2>Gestion des Tournois</h2>

    <!-- CHEMIN CORRECT -->
    <p><a href="/admin/index.php">← Retour au Dashboard Admin</a></p>

    <?php if ($message): ?>
        <p class="success-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="form-card">
        <h3>Créer un Nouveau Tournoi</h3>

        <!-- CHEMIN CORRECT -->
        <form method="POST" action="/admin/tournaments.php">
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
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tournaments as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['id']) ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
                <td><?= htmlspecialchars($t['start_date']) ?></td>
                <td><span class="status-<?= $t['status'] ?>"><?= strtoupper($t['status']) ?></span></td>
                <td>

                    <!-- OK car même dossier -->
                    <a href="edit_tournament.php?id=<?= $t['id'] ?>" class="btn btn-small">Modifier</a>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
.form-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 25px; }
.btn-primary { background-color: #3498db; color: white; }
.status-open { background: #2ecc71; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
.status-in_progress { background: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
.status-finished { background: #95a5a6; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
.success-message { color: #2ecc71; background: #e8f8f5; border: 1px solid #2ecc71; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
</style>
</body>
</html>
