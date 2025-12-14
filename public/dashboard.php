<?php
require_once '../config/db.php';
session_start();

// Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer les invitations en attente
$pending_invitations = [];
try {
    $stmt_inv = $pdo->prepare("
        SELECT mi.id, mi.message, mi.created_at,
               COALESCE(u.pseudo, u.username) as sender_name
        FROM match_invitations mi
        JOIN users u ON mi.sender_id = u.id
        WHERE mi.receiver_id = ? AND mi.status = 'pending'
        ORDER BY mi.created_at DESC
        LIMIT 3
    ");
    $stmt_inv->execute([$_SESSION['user_id']]);
    $pending_invitations = $stmt_inv->fetchAll();
} catch (PDOException $e) {
    try {
        $stmt_inv = $pdo->prepare("
            SELECT mi.id, mi.message, mi.created_at,
                   u.username as sender_name
            FROM match_invitations mi
            JOIN users u ON mi.sender_id = u.id
            WHERE mi.receiver_id = ? AND mi.status = 'pending'
            ORDER BY mi.created_at DESC
            LIMIT 3
        ");
        $stmt_inv->execute([$_SESSION['user_id']]);
        $pending_invitations = $stmt_inv->fetchAll();
    } catch (PDOException $e2) {
        $pending_invitations = [];
    }
}

// 1. Récupérer le classement (Total des points par utilisateur)
$sqlLeaderboard = "
    SELECT u.username, SUM(s.points) as total_points 
    FROM scores s
    JOIN users u ON s.user_id = u.id 
    GROUP BY u.id 
    ORDER BY total_points DESC
";
$stmt = $pdo->query($sqlLeaderboard);
$leaderboard = $stmt->fetchAll();

// 2. Récupérer l'historique personnel
$stmtHist = $pdo->prepare("SELECT points, created_at, match_type FROM scores WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmtHist->execute([$_SESSION['user_id']]);
$history = $stmtHist->fetchAll();

require_once '../templates/header.php';
require_once '../templates/dashboard_view.php';
require_once '../templates/footer.php';
?>
