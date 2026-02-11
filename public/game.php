<?php
require_once '../config/db.php';
session_start();

// Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Vérifier s'il y a une partie en cours (invitation acceptée)
$game = null;
$opponent = null;

try {
    $stmt = $pdo->prepare("
        SELECT mi.*, 
               sender.username as sender_name, 
               receiver.username as receiver_name,
               sender.id as sender_id,
               receiver.id as receiver_id
        FROM match_invitations mi
        JOIN users sender ON mi.sender_id = sender.id
        JOIN users receiver ON mi.receiver_id = receiver.id
        WHERE mi.status = 'accepted'
          AND (mi.sender_id = ? OR mi.receiver_id = ?)
        LIMIT 1
    ");
    $stmt->execute([$user_id, $user_id]);
    $game = $stmt->fetch();
    
    if ($game) {
        // Déterminer l'adversaire
        $opponent = ($game['sender_id'] == $user_id) 
            ? ['id' => $game['receiver_id'], 'name' => $game['receiver_name']]
            : ['id' => $game['sender_id'], 'name' => $game['sender_name']];
    } else {
        // Pas de partie en cours, redirection vers le dashboard
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    $message = "error|Erreur lors de la récupération de la partie.";
}

require_once '../templates/header.php';
require_once '../templates/game_view.php';
require_once '../templates/footer.php';
?>
