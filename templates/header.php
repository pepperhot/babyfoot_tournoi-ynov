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
    <a href="/players.php" style="position: relative;">
        👥 Participants
        <?php if ($pending_count > 0): ?>
        <span class="badge-notification" style="position: absolute; top: -8px; right: -8px; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 2px 12px rgba(231, 76, 60, 0.8); animation: pulse-notif 2s infinite;">
            <?= $pending_count ?>
        </span>
        <?php elseif ($total_users > 0): ?>
        <span style="position: absolute; top: -5px; right: -5px; background: #667eea; color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.6);">
            <?= $total_users - 1 ?>
        </span>
        <?php endif; ?>
    </a>
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

<style>
@keyframes pulse-notif {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 2px 12px rgba(231, 76, 60, 0.8);
    }
    50% {
        transform: scale(1.1);
        box-shadow: 0 4px 20px rgba(231, 76, 60, 1);
    }
}
</style>
