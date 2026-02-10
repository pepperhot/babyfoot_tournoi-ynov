<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Babyfoot Manager</title>
    <!-- Utilisation de variables pour le chemin CSS -->
    <link rel="stylesheet" href="<?= isset($css_path) ? $css_path : 'css/style.css' ?>">
</head>
<body>
    
    <nav class="nav-admin">
        <div class="container flex-between align-center">
            <div class="nav-brand">⚽ Babyfoot Manager</div>
            <div class="nav-links">
                <?php $root = (isset($css_path) && strpos($css_path, '..') !== false) ? '../' : ''; ?>
                <a href="<?= $root ?>dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">🏠 Accueil</a>
                <a href="<?= $root ?>players.php" class="<?= basename($_SERVER['PHP_SELF']) == 'players.php' ? 'active' : '' ?>">👥 Joueurs</a>
                <a href="<?= $root ?>tournament.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tournament.php' ? 'active' : '' ?>">🏆 Tournois</a>
                <a href="<?= $root ?>profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">👤 Profil</a>
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="<?= $root ?>admin/index.php" class="<?= strpos($_SERVER['PHP_SELF'], 'admin') !== false ? 'active' : '' ?>">🔧 Admin</a>
                <?php endif; ?>
            </div>
            <a href="<?= $root ?>logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </nav>

    <!-- Bottom Navigation pour Mobile -->
    <nav class="bottom-nav">
        <a href="<?= $root ?>dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Accueil</span>
        </a>
        <a href="<?= $root ?>players.php" class="<?= basename($_SERVER['PHP_SELF']) == 'players.php' ? 'active' : '' ?>">
            <span class="nav-icon">👥</span>
            <span class="nav-label">Joueurs</span>
        </a>
        <a href="<?= $root ?>tournament.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tournament.php' ? 'active' : '' ?>">
            <span class="nav-icon">🏆</span>
            <span class="nav-label">Tournois</span>
        </a>
        <a href="<?= $root ?>profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profil</span>
        </a>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="<?= $root ?>admin/index.php" class="<?= strpos($_SERVER['PHP_SELF'], 'admin') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🔧</span>
                <span class="nav-label">Admin</span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="container main-content">
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
