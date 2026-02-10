
<div class="animate-fade-in">
    <div class="flex-between align-center" style="margin-bottom: 2rem;">
        <h2>🛠️ Panneau d'Administration</h2>
    </div>

    <div class="alert alert-success">
        <strong>👋 Bonjour Admin !</strong> Sélectionnez une section ci-dessous pour gérer l'application.
    </div>

    <div class="dashboard-columns">
        <!-- Card Gestion Utilisateurs -->
        <a href="users.php" class="card dashboard-col" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; text-align: center; transition: transform 0.2s;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
            <h3 style="border: none; margin-bottom: 0.5rem;">Utilisateurs</h3>
            <p class="text-muted">Gérer les comptes, modifier les pseudos et voir les statistiques.</p>
        </a>

        <!-- Card Gestion Tournois -->
        <a href="tournaments.php" class="card dashboard-col" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; text-align: center; transition: transform 0.2s;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🏆</div>
            <h3 style="border: none; margin-bottom: 0.5rem;">Tournois</h3>
            <p class="text-muted">Créer, modifier et supprimer les tournois officiels.</p>
        </a>
    </div>
</div>
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
