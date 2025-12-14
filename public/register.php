<?php
require_once '../config/db.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hachage du mot de passe (Sécurité indispensable)
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash]);
        header("Location: index.php"); // Redirection vers login
        exit;
    } catch (PDOException $e) {
        $message = "Erreur : Email probablement déjà utilisé.";
    }
}

// Inclusion de la vue
require_once '../templates/register_view.php';
?>
