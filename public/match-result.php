<?php
require_once '../config/db.php';
session_start();

// Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$invitation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";

// Vérifier que l'invitation existe et est acceptée
$invitation = null;
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
        WHERE mi.id = ? AND mi.status = 'accepted'
          AND (mi.sender_id = ? OR mi.receiver_id = ?)
    ");
    $stmt->execute([$invitation_id, $user_id, $user_id]);
    $invitation = $stmt->fetch();
    
    if ($invitation) {
        // Déterminer l'adversaire
        $opponent = ($invitation['sender_id'] == $user_id) 
            ? ['id' => $invitation['receiver_id'], 'name' => $invitation['receiver_name']]
            : ['id' => $invitation['sender_id'], 'name' => $invitation['sender_name']];
    }
} catch (PDOException $e) {
    $message = "error|Erreur lors de la récupération de l'invitation.";
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $invitation) {
    $my_score = intval($_POST['my_score']);
    $opponent_score = intval($_POST['opponent_score']);
    $my_goals = intval($_POST['my_goals'] ?? 0);
    $opponent_goals = intval($_POST['opponent_goals'] ?? 0);
    $my_gamelles = intval($_POST['my_gamelles'] ?? 0);
    $opponent_gamelles = intval($_POST['opponent_gamelles'] ?? 0);
    
    try {
        $pdo->beginTransaction();
        
        // Enregistrer les scores pour moi
        $stmt = $pdo->prepare("
            INSERT INTO scores (user_id, points, match_type, goals, gamelles, created_at) 
            VALUES (?, ?, 'match', ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $my_score, $my_goals, $my_gamelles]);
        
        // Enregistrer les scores pour l'adversaire
        $stmt = $pdo->prepare("
            INSERT INTO scores (user_id, points, match_type, goals, gamelles, created_at) 
            VALUES (?, ?, 'match', ?, ?, NOW())
        ");
        $stmt->execute([$opponent['id'], $opponent_score, $opponent_goals, $opponent_gamelles]);
        
        // Marquer l'invitation comme terminée
        $stmt = $pdo->prepare("UPDATE match_invitations SET status = 'completed', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$invitation_id]);
        
        $pdo->commit();
        
        header("Location: dashboard.php?msg=match_saved");
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $message = "error|Erreur lors de l'enregistrement des résultats : " . $e->getMessage();
    }
}

require_once '../templates/header.php';
require_once '../templates/match_result_view.php';
require_once '../templates/footer.php';
?>
