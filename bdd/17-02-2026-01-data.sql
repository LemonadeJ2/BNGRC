-- Table pour suivre le solde des dons en argent
CREATE TABLE don_argent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    montant_initial DECIMAL(10,2) NOT NULL,
    montant_restant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_don) REFERENCES don(id) ON DELETE CASCADE
);

-- Table pour les achats
CREATE TABLE achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT NOT NULL,
    montant_achat DECIMAL(10,2) NOT NULL,
    frais_pourcentage DECIMAL(5,2) NOT NULL,
    frais_montant DECIMAL(10,2) NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    date_achat DATE NOT NULL,
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
);

-- Table des paramètres
CREATE TABLE parametres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_achat DECIMAL(5,2) NOT NULL DEFAULT 10.00
);


INSERT INTO parametres (frais_achat) VALUES (10.00);


-- Initialiser les dons en argent existants
INSERT INTO don_argent (id_don, montant_initial, montant_restant)
SELECT 
    d.id,
    d.quantite * b.prix AS montant,
    d.quantite * b.prix AS montant_restant
FROM don d
JOIN besoin b ON d.id_besoin = b.id
WHERE b.id_type_besoin = 3;  -- Type 'Argent'