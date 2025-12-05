<?php
/**
 * Script de migration de la base de données
 * Ajoute les colonnes manquantes (pseudo, score, event_date)
 */

require_once 'config/db.php';

echo "<h2>Migration de la base de données</h2>";

try {
    // 1. Ajouter la colonne pseudo si elle n'existe pas
    echo "<p>Vérification de la colonne 'pseudo'...</p>";
    $pdo->exec("ALTER TABLE users ADD COLUMN pseudo VARCHAR(100) DEFAULT NULL");
    echo "<p style='color: green;'>✓ Colonne 'pseudo' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'pseudo' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 2. Ajouter la colonne score si elle n'existe pas
    echo "<p>Vérification de la colonne 'score'...</p>";
    $pdo->exec("ALTER TABLE users ADD COLUMN score INT DEFAULT 0");
    echo "<p style='color: green;'>✓ Colonne 'score' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'score' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 3. Ajouter la colonne event_date aux tournois
    echo "<p>Vérification de la colonne 'event_date' dans tournaments...</p>";
    $pdo->exec("ALTER TABLE tournaments ADD COLUMN event_date DATETIME DEFAULT NULL");
    echo "<p style='color: green;'>✓ Colonne 'event_date' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'event_date' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 4. Mettre à jour les pseudos vides
    echo "<p>Mise à jour des pseudos vides...</p>";
    $stmt = $pdo->exec("UPDATE users SET pseudo = username WHERE pseudo IS NULL OR pseudo = ''");
    echo "<p style='color: green;'>✓ $stmt pseudos mis à jour</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
}

try {
    // 5. Créer les index
    echo "<p>Création des index...</p>";
    $pdo->exec("CREATE INDEX idx_users_pseudo ON users(pseudo)");
    echo "<p style='color: green;'>✓ Index 'idx_users_pseudo' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_users_pseudo' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    $pdo->exec("CREATE INDEX idx_users_score ON users(score)");
    echo "<p style='color: green;'>✓ Index 'idx_users_score' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_users_score' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ Migration terminée !</h3>";
echo "<p><a href='public/dashboard.php'>→ Retour au dashboard</a></p>";
echo "<p><a href='public/admin/users.php'>→ Gestion des utilisateurs (Admin)</a></p>";

// Vérifier la structure finale
echo "<hr><h3>Structure de la table users :</h3>";
$stmt = $pdo->query("DESCRIBE users");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
foreach ($columns as $col) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
