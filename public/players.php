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

// Traitement de l'envoi d'invitation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'invite') {
        $receiver_id = intval($_POST['receiver_id']);
        $message_text = trim($_POST['message'] ?? "Viens jouer un match de babyfoot !");
        
        try {
            // Vérifier qu'on ne s'invite pas soi-même
            if ($receiver_id != $user_id) {
                // Vérifier si la table existe
                try {
                    $stmt = $pdo->prepare("INSERT INTO match_invitations (sender_id, receiver_id, message) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $receiver_id, $message_text]);
                    $message = "success|Invitation envoyée avec succès !";
                } catch (PDOException $e) {
                    // Si la table n'existe pas, on enregistre juste le message
                    $message = "success|Message d'invitation pour " . htmlspecialchars($_POST['receiver_name']) . " : \"" . htmlspecialchars($message_text) . "\"";
                }
            } else {
                $message = "error|Vous ne pouvez pas vous inviter vous-même.";
            }
        } catch (PDOException $e) {
            $message = "error|Erreur lors de l'envoi de l'invitation.";
        }
    } elseif ($_POST['action'] === 'respond') {
        $invitation_id = intval($_POST['invitation_id']);
        $response = $_POST['response']; // 'accepted' ou 'declined'
        
        try {
            $stmt = $pdo->prepare("UPDATE match_invitations SET status = ?, updated_at = NOW() WHERE id = ? AND receiver_id = ?");
            $stmt->execute([$response, $invitation_id, $user_id]);
            
            if ($response === 'accepted') {
                // Rediriger vers la page de résultats du match
                header("Location: match_result.php?id=" . $invitation_id);
                exit;
            } else {
                $message = "success|Invitation déclinée.";
            }
        } catch (PDOException $e) {
            $message = "error|Erreur lors de la réponse à l'invitation.";
        }
    }
}

// Récupérer tous les utilisateurs avec leurs statistiques
try {
    $sqlUsers = "
        SELECT u.id, u.username, u.email,
               COALESCE(u.pseudo, u.username) as display_name,
               COALESCE(u.score, 0) as user_score,
               (SELECT SUM(points) FROM scores WHERE user_id = u.id) as total_points,
               (SELECT COUNT(*) FROM scores WHERE user_id = u.id) as total_matches
        FROM users u
        WHERE u.id != ?
        ORDER BY display_name ASC
    ";
    $stmt = $pdo->prepare($sqlUsers);
    $stmt->execute([$user_id]);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    // Si les colonnes pseudo/score n'existent pas, utiliser la version simple
    $sqlUsers = "
        SELECT u.id, u.username, u.email,
               u.username as display_name,
               0 as user_score,
               (SELECT SUM(points) FROM scores WHERE user_id = u.id) as total_points,
               (SELECT COUNT(*) FROM scores WHERE user_id = u.id) as total_matches
        FROM users u
        WHERE u.id != ?
        ORDER BY u.username ASC
    ";
    $stmt = $pdo->prepare($sqlUsers);
    $stmt->execute([$user_id]);
    $users = $stmt->fetchAll();
}

// Compter le nombre total de participants
$total_participants = count($users) + 1; // +1 pour l'utilisateur connecté

// Récupérer les invitations reçues en attente
$pending_invitations = [];
try {
    // Vérifier si la colonne pseudo existe
    $sqlInvitations = "
        SELECT mi.*, 
               COALESCE(u.pseudo, u.username) as sender_name,
               mi.created_at
        FROM match_invitations mi
        JOIN users u ON mi.sender_id = u.id
        WHERE mi.receiver_id = ? AND mi.status = 'pending'
        ORDER BY mi.created_at DESC
    ";
    $stmt = $pdo->prepare($sqlInvitations);
    $stmt->execute([$user_id]);
    $pending_invitations = $stmt->fetchAll();
} catch (PDOException $e) {
    // Si la table ou la colonne n'existe pas, essayer avec username seulement
    try {
        $sqlInvitations = "
            SELECT mi.*, 
                   u.username as sender_name,
                   mi.created_at
            FROM match_invitations mi
            JOIN users u ON mi.sender_id = u.id
            WHERE mi.receiver_id = ? AND mi.status = 'pending'
            ORDER BY mi.created_at DESC
        ";
        $stmt = $pdo->prepare($sqlInvitations);
        $stmt->execute([$user_id]);
        $pending_invitations = $stmt->fetchAll();
    } catch (PDOException $e2) {
        // Table n'existe pas encore, garder le tableau vide
    }
}

require_once '../templates/header.php';
require_once '../templates/players_view.php';
require_once '../templates/footer.php';
?>
