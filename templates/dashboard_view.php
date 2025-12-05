<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Babyfoot</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<h2>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?> !</h2>

	<div style="display: flex; gap: 20px;">
	    	<div style="flex: 1;">
	        <h3>Enregistrer un résultat</h3>
        	<form action="add_score.php" method="POST" style="background:#eee; padding:15px; border-radius:5px;">
	            <div class="form-group">
        	        <label>Points gagnés</label>
                	<input type="number" name="points" placeholder="Ex: 10" min="11" required>
	            </div>
        	    <div class="form-group">
                	<label>Type</label>
	                <select name="match_type">
        	            <option value="entrainement">Entraînement</option>
                	    <option value="tournoi">Match de Tournoi</option>
	                </select>
        	    </div>
	            <button type="submit">Ajouter</button>
        	</form>

	        <h3>Vos 5 derniers scores</h3>
        	<ul>
	            <?php foreach($history as $h): ?>
        	        <li><?= $h['points'] ?> pts (<?= $h['match_type'] ?>) - <?= date('d/m H:i', strtotime($h['created_at'])) ?></li>
	            <?php endforeach; ?>
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
