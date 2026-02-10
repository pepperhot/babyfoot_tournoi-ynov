<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/db.php';
session_start();

// Auth admin
if (!isset($_SESSION['user_id'])) { header('Location: /index.php'); exit(); }
$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user || !$user['is_admin']) { header('Location: /dashboard.php'); exit(); }

$message = '';

// Supprimer un tournoi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    try {
        // Supprimer les inscriptions liées
        $stmt = $pdo->prepare('DELETE FROM tournament_registrations WHERE tournament_id = ?');
        $stmt->execute([$id]);

        // Supprimer le tournoi
        $stmt = $pdo->prepare('DELETE FROM tournaments WHERE id = ?');
        $stmt->execute([$id]);

        $message = "Tournoi et inscriptions supprimés avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Ajouter un tournoi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tournament_name'])) {
    $name = $_POST['tournament_name'];
    $start_date = $_POST['start_date'];
    $event_date = $start_date . ' 00:00:00';
    try {
        $stmt = $pdo->prepare('INSERT INTO tournaments (name, event_date, start_date) VALUES (?, ?, ?)');
        $stmt->execute([$name, $event_date, $start_date]);
        $message = "Tournoi '$name' créé avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la création du tournoi.";
    }
}

// Récupérer tous les tournois futurs ou en cours
$stmt = $pdo->prepare('SELECT * FROM tournaments WHERE start_date >= CURDATE() ORDER BY start_date ASC');
$stmt->execute();
$tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclusion templates
require_once '../../templates/header.php';
require_once '../../templates/admin_tournaments_view.php';
require_once '../../templates/footer.php';
?>
