<?php
require_once '../config/db.php';
session_start();

// Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = "";
$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: logout.php");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    try {
        // Vérifier si l'email est déjà utilisé par un autre compte
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $message = "error|Cet email est déjà utilisé par un autre compte.";
        } else {
            // Si changement de mot de passe demandé
            if (!empty($new_password)) {
                // Vérifier le mot de passe actuel
                if (!password_verify($current_password, $user['password'])) {
                    $message = "error|Le mot de passe actuel est incorrect.";
                } elseif ($new_password !== $confirm_password) {
                    $message = "error|Les nouveaux mots de passe ne correspondent pas.";
                } elseif (strlen($new_password) < 6) {
                    $message = "error|Le nouveau mot de passe doit contenir au moins 6 caractères.";
                } else {
                    // Mettre à jour avec nouveau mot de passe
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET email = ?, pseudo = ?, password = ? WHERE id = ?");
                    $stmt->execute([$email, $pseudo, $password_hash, $user_id]);
                    $_SESSION['username'] = $pseudo;
                    $message = "success|Profil et mot de passe mis à jour avec succès !";
                }
            } else {
                // Mise à jour sans changement de mot de passe
                $stmt = $pdo->prepare("UPDATE users SET email = ?, pseudo = ? WHERE id = ?");
                $stmt->execute([$email, $pseudo, $user_id]);
                $_SESSION['username'] = $pseudo;
                $message = "success|Profil mis à jour avec succès !";
            }
            
            // Recharger les données utilisateur
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    } catch (PDOException $e) {
        $message = "error|Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

// Récupérer les statistiques de l'utilisateur
$stmt = $pdo->prepare("SELECT COUNT(*) as total_matches, SUM(points) as total_points FROM scores WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

// Si pas de stats, initialiser à 0
if (!$stats || $stats['total_points'] === null) {
    $stats = ['total_matches' => 0, 'total_points' => 0];
}

// Récupérer le classement de l'utilisateur
try {
    // Récupérer tous les joueurs qui ont des scores
    $sqlRank = "
        SELECT s.user_id, u.username, COALESCE(u.pseudo, u.username) as pseudo, SUM(s.points) as total_points
        FROM scores s
        JOIN users u ON s.user_id = u.id
        GROUP BY s.user_id, u.username, u.pseudo
        ORDER BY total_points DESC
    ";
    $stmtRank = $pdo->query($sqlRank);
    $rankings = $stmtRank->fetchAll();

    // Trouver la position de l'utilisateur actuel
    $userRank = null;
    $totalPlayers = count($rankings);
    $position = 1;
    
    foreach ($rankings as $rank) {
        if ($rank['user_id'] == $user_id) {
            $userRank = $rank;
            $userRank['ranking'] = $position;
            break;
        }
        $position++;
    }
} catch (PDOException $e) {
    // En cas d'erreur SQL, initialiser les variables
    error_log("Erreur classement: " . $e->getMessage());
    $userRank = null;
    $totalPlayers = 0;
}

require_once '../templates/header.php';
require_once '../templates/profile_view.php';
require_once '../templates/footer.php';
?>
