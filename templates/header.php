<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babyfoot App</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
<nav>
    <a href="/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
        🏠 Accueil
    </a>
    <a href="/tournament.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tournament.php' ? 'active' : '' ?>">
        🏆 Tournois
    </a>
    <?php 
    // Compter le nombre total de participants
    $total_users = 0;
    try {
        $stmt_count = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result_count = $stmt_count->fetch();
        $total_users = $result_count['count'] ?? 0;
    } catch (PDOException $e) {
        $total_users = 0;
    }
    
    // Compter les invitations en attente pour l'utilisateur connecté
    $pending_count = 0;
    if (isset($_SESSION['user_id'])) {
        try {
            $stmt_inv = $pdo->prepare("SELECT COUNT(*) as count FROM match_invitations WHERE receiver_id = ? AND status = 'pending'");
            $stmt_inv->execute([$_SESSION['user_id']]);
            $result_inv = $stmt_inv->fetch();
            $pending_count = $result_inv['count'] ?? 0;
        } catch (PDOException $e) {
            $pending_count = 0;
        }
    }
    ?>
    <a href="/players.php" class="<?= basename($_SERVER['PHP_SELF']) == 'players.php' ? 'active' : '' ?>">
        👥 Joueurs
        <?php if ($pending_count > 0): ?>
            <span class="badge badge-notification"><?= $pending_count ?></span>
        <?php elseif ($total_users > 0): ?>
            <span class="badge badge-count"><?= $total_users - 1 ?></span>
        <?php endif; ?>
    </a>
    <a href="/profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
        👤 Profil
    </a>
    <?php if (isset($_SESSION['user_id'])): 
        $stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $currentUser = $stmt->fetch();
        if ($currentUser && $currentUser['is_admin']):
    ?>
    <a href="/admin/index.php" class="nav-admin">🛡️ Admin</a>
    <?php endif; endif; ?>
    <a href="/logout.php" class="nav-logout">🚪 Déco</a>
</nav>
