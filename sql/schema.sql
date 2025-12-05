-- sql/schema.sql

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS tournaments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status ENUM('open', 'in_progress', 'finished') DEFAULT 'open',
    start_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de liaison pour les inscriptions aux tournois
CREATE TABLE tournament_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tournament_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id),
    UNIQUE(user_id, tournament_id) -- Un utilisateur ne s'inscrit qu'une fois
);

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL, -- Ex: 10 pour une victoire, 3 pour un nul
    match_type ENUM('entrainement', 'tournoi') DEFAULT 'entrainement',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insertion d'un tournoi exemple
INSERT INTO tournaments (name, event_date) VALUES ('Grand Tournoi d\'Hiver', '2024-12-25 14:00:00');
