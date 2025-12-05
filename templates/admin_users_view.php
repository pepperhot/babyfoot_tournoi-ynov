<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>users admin Babyfoot</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Gestion des Utilisateurs</h2>

    <p><a href="admin/index.php">← Retour au Dashboard Admin</a></p>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?= $message['type'] ?>">
            <?= htmlspecialchars($message['text']) ?>
        </div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Pseudo</th>
                <th>Score Total</th>
                <th>Inscrit le</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['pseudo'] ?? 'Non défini') ?></td>
                <td><?= htmlspecialchars($user['score'] ?? 0) ?></td>
                <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                <td>
                    <span class="badge badge-<?= $user['is_admin'] ? 'danger' : 'success' ?>">
                        <?= $user['is_admin'] ? 'ADMIN' : 'Joueur' ?>
                    </span>
                </td>
                <td>
                    <button class="btn btn-primary" onclick="openEditModal(<?= htmlspecialchars(json_encode($user)) ?>)">
                        ✏️ Modifier
                    </button>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-admin">🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de modification -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content card">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Modifier l'utilisateur</h2>
        <form method="POST" id="editForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" id="edit_user_id">
            
            <div class="form-group">
                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="edit_pseudo">Pseudo:</label>
                <input type="text" id="edit_pseudo" name="pseudo" required>
            </div>
            
            <div class="form-group">
                <label for="edit_score">Score Total:</label>
                <input type="number" id="edit_score" name="score" min="0" required>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="edit_is_admin" name="is_admin" value="1">
                    Administrateur
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
            <button type="button" class="btn" onclick="closeEditModal()">Annuler</button>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
}

.modal-content {
    position: relative;
    margin: 5% auto;
    max-width: 500px;
    animation: slideIn 0.3s;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #000;
}
</style>

<script>
function openEditModal(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_pseudo').value = user.pseudo || '';
    document.getElementById('edit_score').value = user.score || 0;
    document.getElementById('edit_is_admin').checked = user.is_admin == 1;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeEditModal();
    }
}
</script>
</body>
</html>
