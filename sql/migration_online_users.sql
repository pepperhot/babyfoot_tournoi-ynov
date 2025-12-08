-- Ajouter les colonnes pour la gestion de présence en ligne
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS last_activity TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS is_online BOOLEAN DEFAULT FALSE;

-- Index pour optimiser les requêtes sur les utilisateurs en ligne
CREATE INDEX IF NOT EXISTS idx_users_online ON users(is_online, last_activity);

-- Table pour les invitations de match
CREATE TABLE IF NOT EXISTS match_invitations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Index pour optimiser les requêtes d'invitations
CREATE INDEX IF NOT EXISTS idx_invitations_receiver ON match_invitations(receiver_id, status);
CREATE INDEX IF NOT EXISTS idx_invitations_sender ON match_invitations(sender_id);

SELECT '✅ Tables pour la gestion des joueurs en ligne créées avec succès !' as message;
