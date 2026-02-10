<div class="animate-fade-in">
    <div class="flex-between align-center" style="margin-bottom: 2rem;">
        <h2>👥 Gestion des Utilisateurs</h2>
        <a href="index.php" class="btn btn-cancel">← Retour Dashboard</a>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?>">
            <strong><?= $message['type'] === 'success' ? '✅' : '⚠️' ?></strong>
            <?= htmlspecialchars($message['text']) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="flex-between flex-wrap align-center" style="margin-bottom: 1.5rem;">
            <p class="text-muted">
                📊 Total : <strong><?= count($users) ?></strong> utilisateur(s) · 
                🛡️ Admins : <strong><?= count(array_filter($users, fn($u) => $u['is_admin'])) ?></strong>
            </p>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>📧 Email</th>
                        <th>👤 Pseudo</th>
                        <th class="text-center">Score</th>
                        <th>📅 Inscrit le</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="text-center text-muted">Aucun utilisateur trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                            <td class="text-center">
                                <span class="badge warning"><?= $u['total_points'] ?> pts</span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td class="text-center">
                                <?php if ($u['is_admin']): ?>
                                    <span class="badge" style="background: var(--primary); color: white;">ADMIN</span>
                                <?php else: ?>
                                    <span class="badge" style="background: var(--bg-input); color: var(--text-muted);">Joueur</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="users.php?edit_id=<?= $u['id'] ?>" class="btn btn-primary btn-sm">
                                    ✏️ Modifier
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #95a5a6;">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $index => $user): ?>
                        <tr data-user-id="<?= $user['id'] ?>">
                            <td style="font-weight: 600; color: #667eea;">#<?= htmlspecialchars($user['id']) ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 8px; height: 8px; background: #2ecc71; border-radius: 50%; display: inline-block;"></span>
                                    <?= htmlspecialchars($user['email']) ?>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($user['pseudo'] ?? 'Non défini') ?></strong>
                            </td>
                            <td style="text-align: center;">
                                <span style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                                    <?= htmlspecialchars($user['score'] ?? 0) ?> pts
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            <td style="text-align: center;">
                                <span class="badge badge-<?= $user['is_admin'] ? 'danger' : 'info' ?>">
                                    <?= $user['is_admin'] ? '🛡️ ADMIN' : '🎮 Joueur' ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <button class="btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($user)) ?>)" title="Modifier l'utilisateur">
                                    ✏️ Modifier
                                </button>
                                <form method="POST" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ?\n\nCette action est irréversible !');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn-delete" title="Supprimer l'utilisateur">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de modification -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()" title="Fermer">&times;</span>
        <h2 style="margin-bottom: 10px; color: #1e3c72;">✏️ Modifier l'utilisateur</h2>
        <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 25px;">
            Vous pouvez modifier toutes les informations de l'utilisateur
        </p>
        
        <form method="POST" id="editForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" id="edit_user_id">
            
            <div class="form-group">
                <label for="edit_email">📧 Adresse Email</label>
                <input type="email" id="edit_email" name="email" required placeholder="exemple@email.com">
                <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 5px;">
                    ✏️ Modifiable par l'administrateur
                </small>
            </div>
            
            <div class="form-group">
                <label for="edit_pseudo">👤 Pseudo</label>
                <input type="text" id="edit_pseudo" name="pseudo" required placeholder="Nom d'utilisateur" maxlength="50">
                <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 5px;">
                    ✏️ Modifiable par l'administrateur
                </small>
            </div>
            
            <div class="form-group">
                <label for="edit_score">🏆 Score Total</label>
                <input type="number" id="edit_score" name="score" min="0" max="999999" required placeholder="0">
                <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 5px;">
                    ✏️ Le score représente le total de points gagnés (modifiable manuellement)
                </small>
            </div>
            
            <div class="form-group" style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid #667eea;">
                <label style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                    <input type="checkbox" id="edit_is_admin" name="is_admin" value="1" style="width: auto; margin-right: 10px;">
                    <span style="font-size: 1rem;">🛡️ <strong>Administrateur</strong></span>
                </label>
                <small style="color: #7f8c8d; font-size: 0.85rem; display: block; margin-top: 8px; margin-left: 30px;">
                    Les administrateurs ont accès au panneau d'administration
                </small>
            </div>
<style>
/* ========== STYLES PERSONNALISÉS POUR LE CRUD ========== */

/* Boutons d'action dans le tableau */
.btn-edit {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(52, 152, 219, 0.5);
}

.btn-delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    border: none;
    padding: 8px 12px;
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

/* Boutons du formulaire */
.btn-save {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    border: none;
    padding: 14px 30px;
    cursor: pointer;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(39, 174, 96, 0.5);
}

.btn-cancel {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
    border: none;
    padding: 14px 24px;
    cursor: pointer;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
}

.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(149, 165, 166, 0.5);
}

/* Animation des lignes du tableau */
tbody tr {
    transition: all 0.3s ease;
}

tbody tr:hover {
    transform: translateX(5px);
    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Badge animé pour les admins */
.badge-danger {
    animation: pulse 2s ease infinite;
}

/* Close button du modal */
.close {
    position: absolute;
    right: 25px;
    top: 15px;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
    color: #95a5a6;
    transition: all 0.3s ease;
    line-height: 1;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close:hover {
    color: #e74c3c;
    background: #f8f9fa;
    transform: rotate(90deg);
}

/* Responsive pour les actions */
@media (max-width: 768px) {
    .btn-edit, .btn-delete {
        display: block;
        width: 100%;
        margin: 5px 0;
    }
    
    table th, table td {
        font-size: 0.8rem;
        padding: 10px 8px;
    }
    
    .modal-content {
        margin: 2% auto;
        max-width: 95%;
    }
}
</style>-size: 28px;
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
