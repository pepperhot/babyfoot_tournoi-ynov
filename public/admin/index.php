<?php
// public/admin/index.php

require_once '../../config/db.php'; // Chemin vers db.php (OK)

session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); 
    exit();
}

try {
    $stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !$user['is_admin']) {
        header('Location: ../dashboard.php'); 
        exit();
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

$page_title = "Admin Dashboard";
$css_path = '../css/style.css'; // Chemin révisé du CSS (de public/admin/ -> ../css/)

require_once '../../templates/header.php'; 
require_once '../../templates/admin_dashboard_view.php';
require_once '../../templates/footer.php';
?>
