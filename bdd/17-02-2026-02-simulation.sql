-- Table pour sauvegarder l'état avant simulation
CREATE TABLE sim_save (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_save DATETIME NOT NULL,
    user_id VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

-- Sauvegarde des dons au moment de la simulation
CREATE TABLE sim_don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_don INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    nom_donneur VARCHAR(100),
    quantite DECIMAL(10,2) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type_besoin VARCHAR(50),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE
);

-- Sauvegarde des besoins au moment de la simulation
CREATE TABLE sim_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville_besoin INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    type_besoin VARCHAR(50),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE
);

-- Sauvegarde des achats/dépenses au moment de la simulation
CREATE TABLE sim_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    type_source ENUM('don_argent', 'don_nature', 'don_materiel') NOT NULL,
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE
);

-- Résultats de la simulation (dispatch proposé)
CREATE TABLE sim_resultat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_save INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_proposee INT NOT NULL,
    provenance VARCHAR(255), -- "Don de X", "Achat avec argent de Y"
    montant_utilise DECIMAL(10,2),
    FOREIGN KEY (id_save) REFERENCES sim_save(id) ON DELETE CASCADE
);