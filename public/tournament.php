<?php
require_once '../config/db.php';
session_start();

// Redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

// Inscription au tournoi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tournament_id'])) {
        $t_id = (int)$_POST['tournament_id'];
        try {
            $stmt = $pdo->prepare("INSERT INTO tournament_registrations (user_id, tournament_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $t_id]);
            $msg = "Inscription validée !";
        } catch (PDOException $e) {
            $msg = "Vous êtes déjà inscrit à ce tournoi.";
        }
    }

    // Désinscription
    if (isset($_POST['unregister_id'])) {
        $t_id = (int)$_POST['unregister_id'];
        $stmt = $pdo->prepare("DELETE FROM tournament_registrations WHERE user_id = ? AND tournament_id = ?");
        $stmt->execute([$_SESSION['user_id'], $t_id]);
        $msg = "Vous êtes désinscrit de ce tournoi.";
    }
}

// Récupération des tournois futurs
$stmt = $pdo->query("SELECT * FROM tournaments WHERE event_date >= NOW() ORDER BY event_date ASC");
$tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier l’inscription de l’utilisateur pour chaque tournoi
foreach ($tournaments as &$t) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tournament_registrations WHERE user_id = ? AND tournament_id = ?");
    $stmt->execute([$_SESSION['user_id'], $t['id']]);
    $t['is_registered'] = $stmt->fetchColumn() > 0;
}
unset($t); // briser la référence

require_once '../templates/header.php';
require_once '../templates/tournament_view.php';
require_once '../templates/footer.php';
?>
