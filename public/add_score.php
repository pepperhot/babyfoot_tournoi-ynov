<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $points = (int)$_POST['points'];
    $type = $_POST['match_type']; // 'entrainement' ou 'tournoi'

    $stmt = $pdo->prepare("INSERT INTO scores (user_id, points, match_type) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $points, $type]);
}

header("Location: dashboard.php");
exit;
?>
