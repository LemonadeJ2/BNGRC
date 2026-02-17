-- Vérification et création des tables de simulation
-- Exécuter ce script si les tables n'existent pas

-- 1. Table de sauvegarde principal
CREATE TABLE IF NOT EXISTS sim_save (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_save DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    INDEX idx_user_date (user_id, date_save DESC)
);

-- 2. Table des dons sauvegardés
CREATE TABLE IF NOT EXISTS sim_don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_don INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    nom_donneur VARCHAR(100),
    quantite DECIMAL(10,2) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type_besoin VARCHAR(50),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE,
    FOREIGN KEY (id_don) REFERENCES don(id) ON DELETE CASCADE,
    INDEX idx_save (id_save),
    INDEX idx_type (type_besoin)
);

-- 3. Table des besoins sauvegardés
CREATE TABLE IF NOT EXISTS sim_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville_besoin INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    type_besoin VARCHAR(50),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ville_besoin) REFERENCES ville_besoin(id) ON DELETE CASCADE,
    INDEX idx_save (id_save),
    INDEX idx_type (type_besoin)
);

-- 4. Table des achats sauvegardés
CREATE TABLE IF NOT EXISTS sim_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    type_source ENUM('don_argent', 'don_nature', 'don_materiel') NOT NULL,
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE,
    INDEX idx_save (id_save)
);

-- 5. Table des résultats de simulation
CREATE TABLE IF NOT EXISTS sim_resultat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_proposee DECIMAL(10,2) NOT NULL,
    provenance VARCHAR(255),
    montant_utilise DECIMAL(10,2),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE,
    INDEX idx_save (id_save),
    INDEX idx_ville_besoin (id_ville, id_besoin)
);

-- Vérification: afficher les tables créées
SELECT TABLE_NAME 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME LIKE 'sim_%'
ORDER BY TABLE_NAME;

-- Afficher les structures (si besoin de vérification)
-- DESCRIBE sim_save;
-- DESCRIBE sim_don;
-- DESCRIBE sim_besoin;
-- DESCRIBE sim_achat;
-- DESCRIBE sim_resultat;