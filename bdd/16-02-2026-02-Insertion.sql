-- 1. Types de besoins
INSERT INTO type_besoin (type_besoin) VALUES
('Nature'),
('Matériel'),
('Argent');

-- 2. Besoins
INSERT INTO besoin (nom, prix, id_type_besoin) VALUES
('Riz 50kg', 180000.00, 1),
('Eau potable 1L', 500.00, 1),
('Tente de secours', 1500000.00, 2),
('Couverture de survie', 200000.00, 2),
("Kit médical d'urgence", 300000.00, 2),
('Vêtements chauds', 100000.00, 2),
('Argent', 1, 3);

-- 3. Villes
INSERT INTO ville (nom) VALUES
('Antananarivo'),
('Toamasina'),
('Mahajanga'),
('Toliara'),
('Fianarantsoa'),
('Mananjary'),
('Antsiranana');

-- 4. Dons
INSERT INTO don (id_ville, id_besoin, nom_donneur, quantite, date_don) VALUES
(6, 1, 'ONG CARE Madagascar', 200, '2026-02-01'),
(2, 3, 'UNICEF Madagascar', 500, '2026-02-02'),
(1, 4, 'Groupe TELMA', 50, '2026-02-03'),
(3, 6, 'Particulier Rakoto', 100, '2026-02-04'),
(5, 2, 'Entreprise STAR', 300, '2026-02-05'),
(4, 5, 'Croix Rouge Malagasy', 80, '2026-02-06');

-- 5. Besoins par ville
INSERT INTO ville_besoin (id_ville, id_besoin, dateB, quantite) VALUES
(2, 1, '2026-01-28', 400),  -- Toamasina a besoin de riz
(2, 2, '2026-01-28', 600),  -- Toamasina eau potable
(6, 3, '2026-01-29', 150),  -- Mananjary tentes
(6, 4, '2026-01-29', 300),  -- Mananjary couvertures
(1, 5, '2026-01-30', 120),  -- Antananarivo kits médicaux
(3, 1, '2026-01-30', 200),  -- Mahajanga riz
(4, 2, '2026-01-31', 250);  -- Toliara eau