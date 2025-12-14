<?php
/**
 * Script de migration de la base de données
 * Ajoute les colonnes manquantes (pseudo, score, event_date, last_activity, is_online)
 * Et crée la table des invitations
 */

require_once '../config/db.php';

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
    // 4. Ajouter last_activity pour le suivi d'activité
    echo "<p>Vérification de la colonne 'last_activity'...</p>";
    $pdo->exec("ALTER TABLE users ADD COLUMN last_activity TIMESTAMP NULL DEFAULT NULL");
    echo "<p style='color: green;'>✓ Colonne 'last_activity' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'last_activity' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 5. Ajouter is_online pour le statut en ligne
    echo "<p>Vérification de la colonne 'is_online'...</p>";
    $pdo->exec("ALTER TABLE users ADD COLUMN is_online BOOLEAN DEFAULT FALSE");
    echo "<p style='color: green;'>✓ Colonne 'is_online' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'is_online' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 6. Créer la table des invitations
    echo "<p>Création de la table 'match_invitations'...</p>";
    $pdo->exec("
        CREATE TABLE match_invitations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT,
            status ENUM('pending', 'accepted', 'declined', 'completed') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "<p style='color: green;'>✓ Table 'match_invitations' créée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "<p style='color: orange;'>ℹ Table 'match_invitations' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    // 7. Mettre à jour les pseudos vides
    echo "<p>Mise à jour des pseudos vides...</p>";
    $stmt = $pdo->exec("UPDATE users SET pseudo = username WHERE pseudo IS NULL OR pseudo = ''");
    echo "<p style='color: green;'>✓ $stmt pseudos mis à jour</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
}

try {
    // 8. Créer les index
    echo "<p>Création des index...</p>";
    $pdo->exec("CREATE INDEX idx_users_pseudo ON users(pseudo)");
    echo "<p style='color: green;'>✓ Index 'idx_users_pseudo' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_users_pseudo' existe déjà</p>";
    }
}

try {
    $pdo->exec("CREATE INDEX idx_users_score ON users(score)");
    echo "<p style='color: green;'>✓ Index 'idx_users_score' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_users_score' existe déjà</p>";
    }
}

try {
    $pdo->exec("CREATE INDEX idx_users_online ON users(is_online, last_activity)");
    echo "<p style='color: green;'>✓ Index 'idx_users_online' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_users_online' existe déjà</p>";
    }
}

try {
    $pdo->exec("CREATE INDEX idx_invitations_receiver ON match_invitations(receiver_id, status)");
    echo "<p style='color: green;'>✓ Index 'idx_invitations_receiver' créé</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate key') !== false) {
        echo "<p style='color: orange;'>ℹ Index 'idx_invitations_receiver' existe déjà</p>";
    }
}

try {
    // 9. Ajouter les colonnes goals et gamelles à scores
    echo "<p>Vérification de la colonne 'goals' dans scores...</p>";
    $pdo->exec("ALTER TABLE scores ADD COLUMN goals INT DEFAULT 0 AFTER match_type");
    echo "<p style='color: green;'>✓ Colonne 'goals' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'goals' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    echo "<p>Vérification de la colonne 'gamelles' dans scores...</p>";
    $pdo->exec("ALTER TABLE scores ADD COLUMN gamelles INT DEFAULT 0 AFTER goals");
    echo "<p style='color: green;'>✓ Colonne 'gamelles' ajoutée</p>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "<p style='color: orange;'>ℹ Colonne 'gamelles' existe déjà</p>";
    } else {
        echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
    }
}

try {
    echo "<p>Modification du type 'match_type' pour inclure 'match'...</p>";
    $pdo->exec("ALTER TABLE scores MODIFY COLUMN match_type ENUM('entrainement', 'tournoi', 'match') DEFAULT 'entrainement'");
    echo "<p style='color: green;'>✓ Type 'match_type' modifié</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ Migration terminée !</h3>";
echo "<p><a href='dashboard.php'>→ Retour au dashboard</a></p>";
echo "<p><a href='players.php' style='color: #667eea; font-weight: bold;'>→ Voir les participants (NOUVEAU !)</a></p>";
echo "<p><a href='admin/users.php'>→ Gestion des utilisateurs (Admin)</a></p>";

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

echo "<hr><h3>Structure de la table scores :</h3>";
$stmt = $pdo->query("DESCRIBE scores");
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

echo "<hr><h3>Tables créées :</h3>";
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "<ul>";
foreach ($tables as $table) {
    echo "<li>" . htmlspecialchars($table) . "</li>";
}
echo "</ul>";
?>
