-- Ajouter les colonnes pseudo et score si elles n'existent pas déjà
ALTER TABLE users ADD COLUMN IF NOT EXISTS pseudo VARCHAR(100);
ALTER TABLE users ADD COLUMN IF NOT EXISTS score INT DEFAULT 0;

-- Index pour optimiser les recherches
CREATE INDEX IF NOT EXISTS idx_users_pseudo ON users(pseudo);
CREATE INDEX IF NOT EXISTS idx_users_score ON users(score);
