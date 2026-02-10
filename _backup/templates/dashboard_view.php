<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Babyfoot</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<h2>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?> !</h2>

	<?php if (isset($_GET['msg']) && $_GET['msg'] === 'match_saved'): ?>
		<div class="alert alert-success" style="margin-bottom: 20px;">
			<strong>✅ Résultats enregistrés avec succès !</strong>
		</div>
	<?php endif; ?>

	<?php if (!empty($pending_invitations)): ?>
	<div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #fff5f5, #ffe0e0); border-left: 4px solid #e74c3c; animation: slideIn 0.5s ease;">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
			<h3 style="margin: 0;">📨 Invitations en Attente (<?= count($pending_invitations) ?>)</h3>
			<a href="/players.php" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
				Voir toutes →
			</a>
		</div>
		<div style="margin-top: 15px;">
			<?php foreach ($pending_invitations as $inv): ?>
			<div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
				<div style="flex: 1;">
					<strong style="color: #667eea;">👤 <?= htmlspecialchars($inv['sender_name']) ?></strong>
					<p style="margin: 5px 0; color: #7f8c8d;"><?= htmlspecialchars($inv['message']) ?></p>
					<small style="color: #95a5a6;">⏰ <?= date('d/m/Y H:i', strtotime($inv['created_at'])) ?></small>
				</div>
				<div style="display: flex; gap: 10px;">
					<form method="POST" action="/players.php" style="display: inline;">
						<input type="hidden" name="action" value="respond">
						<input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
						<input type="hidden" name="response" value="accepted">
						<button type="submit" style="background: linear-gradient(135deg, #2ecc71, #27ae60); padding: 8px 16px; border: none; border-radius: 8px; color: white; font-weight: 600; cursor: pointer;">
							✅ Accepter
						</button>
					</form>
					<form method="POST" action="/players.php" style="display: inline;">
						<input type="hidden" name="action" value="respond">
						<input type="hidden" name="invitation_id" value="<?= $inv['id'] ?>">
						<input type="hidden" name="response" value="declined">
						<button type="submit" class="btn-cancel" style="background: linear-gradient(135deg, #95a5a6, #7f8c8d); padding: 8px 16px; border: none; border-radius: 8px; color: white; font-weight: 600; cursor: pointer;">
							❌ Refuser
						</button>
					</form>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<div style="display: flex; gap: 20px;">
	    <div style="flex: 1;">
	        <h3>Vos 5 derniers scores</h3>
        	<ul>
	            <?php if (empty($history)): ?>
					<li style="color: #7f8c8d;">Aucun score enregistré</li>
				<?php else: ?>
	            	<?php foreach($history as $h): ?>
        	        	<li><?= $h['points'] ?> pts (<?= $h['match_type'] ?>) - <?= date('d/m H:i', strtotime($h['created_at'])) ?></li>
	            	<?php endforeach; ?>
				<?php endif; ?>
        	</ul>
	    </div>

	    <div style="flex: 1;">
        	<h3>Classement Général 🏆</h3>
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
        	            <td><?= htmlspecialchars($rank['username']) ?></td>
                	    <td><strong><?= $rank['total_points'] ?></strong></td>
	                </tr>
        	        <?php endforeach; ?>
	            </tbody>
	        </table>
	    </div>
	</div>
</body>
</html>
