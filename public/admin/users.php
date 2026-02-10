<?php
// public/admin/users.php

require_once '../../config/db.php';
session_start();

// --- Logique d'authentification Admin ---
if (!isset($_SESSION['user_id'])) { header('Location: ../index.php'); exit(); }
$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user || !$user['is_admin']) { header('Location: ../dashboard.php'); exit(); }
// ----------------------------------------

$message = null;

// TRAITEMENT DES ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? 0;
    
    try {
        if ($action === 'update' && $user_id > 0) {
            // Mise à jour de l'utilisateur
            $email = $_POST['email'] ?? '';
            $pseudo = $_POST['pseudo'] ?? '';
            $score = intval($_POST['score'] ?? 0);
            $is_admin = isset($_POST['is_admin']) ? 1 : 0;
            
            // Ne pas permettre de se retirer soi-même les droits admin
            if ($user_id == $_SESSION['user_id'] && $is_admin == 0) {
                $message = ['type' => 'danger', 'text' => 'Vous ne pouvez pas vous retirer vos propres droits administrateur !'];
            } else {
                $stmt = $pdo->prepare('UPDATE users SET email = ?, pseudo = ?, score = ?, is_admin = ? WHERE id = ?');
                $stmt->execute([$email, $pseudo, $score, $is_admin, $user_id]);
                $message = ['type' => 'success', 'text' => 'Utilisateur modifié avec succès !'];
            }
            
        } elseif ($action === 'delete' && $user_id > 0) {
            // Suppression de l'utilisateur
            if ($user_id == $_SESSION['user_id']) {
                $message = ['type' => 'danger', 'text' => 'Vous ne pouvez pas supprimer votre propre compte !'];
            } else {
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$user_id]);
                $message = ['type' => 'success', 'text' => 'Utilisateur supprimé avec succès !'];
            }
            
        } elseif ($action === 'toggle_admin' && $user_id > 0) {
            // Inverser le statut admin
            $stmt = $pdo->prepare('UPDATE users SET is_admin = 1 - is_admin WHERE id = ?');
            $stmt->execute([$user_id]);
            $message = ['type' => 'success', 'text' => 'Statut administrateur modifié !'];
        }
        
    } catch (PDOException $e) {
        $message = ['type' => 'danger', 'text' => 'Erreur : ' . $e->getMessage()];
    }
    
    header('Location: users.php');
    exit();
}

// RÉCUPÉRATION DE TOUS LES UTILISATEURS avec leurs scores
$stmt = $pdo->query('SELECT id, email, pseudo, score, is_admin, created_at FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Gestion des Utilisateurs";
$css_path = '../css/style.css'; // Le chemin est le même que index.php

require_once '../../templates/header.php';
require_once '../../templates/admin_users_view.php';
require_once '../../templates/footer.php';
?>
