<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Babyfoot App</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
<nav>
    <a href="/dashboard.php">🏠 Accueil & Scores</a>
    <a href="/tournament.php">🏆 Tournois</a>
    <a href="/profile.php">👤 Mon Profil</a>
    <?php if (isset($_SESSION['user_id'])): 
        $stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $currentUser = $stmt->fetch();
        if ($currentUser && $currentUser['is_admin']):
    ?>
    <a href="/admin/index.php" style="background: rgba(231, 76, 60, 0.2); border: 1px solid rgba(231, 76, 60, 0.5);">🛡️ Admin</a>
    <?php endif; endif; ?>
    <a href="/logout.php" style="margin-left: auto; background: rgba(231, 76, 60, 0.15);">🚪 Déconnexion</a>
</nav>
