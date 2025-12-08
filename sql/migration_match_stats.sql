-- Migration pour ajouter les colonnes goals et gamelles à la table scores

-- Ajouter la colonne goals
ALTER TABLE scores ADD COLUMN goals INT DEFAULT 0 AFTER match_type;

-- Ajouter la colonne gamelles  
ALTER TABLE scores ADD COLUMN gamelles INT DEFAULT 0 AFTER goals;

-- Modifier le match_type pour inclure 'match'
ALTER TABLE scores MODIFY COLUMN match_type ENUM('entrainement', 'tournoi', 'match') DEFAULT 'entrainement';