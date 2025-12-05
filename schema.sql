-- 🚀 Migration Rapide - Ajouter les colonnes manquantes
-- Copiez-collez ce code dans phpMyAdmin (onglet SQL) et exécutez

-- 1. Ajouter pseudo et score à la table users
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS pseudo VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS score INT DEFAULT 0;

-- 2. Ajouter event_date aux tournois  
ALTER TABLE tournaments 
ADD COLUMN IF NOT EXISTS event_date DATETIME DEFAULT NULL;

-- 3. Mettre à jour les pseudos vides avec le username
UPDATE users SET pseudo = username WHERE pseudo IS NULL OR pseudo = '';

-- 4. Créer les index pour optimiser les performances
CREATE INDEX IF NOT EXISTS idx_users_pseudo ON users(pseudo);
CREATE INDEX IF NOT EXISTS idx_users_score ON users(score);

-- ✅ Migration terminée !
SELECT '✅ Migration terminée avec succès !' as message;
