INSERT INTO besoin (nom, prix) VALUES
('Riz 50kg', 180000.00),
('Huile alimentaire 5L', 25000.00),
('Eau potable 20L', 15000.00),
('Tente familiale', 350000.00),
('Kit médical', 120000.00),
('Couverture', 20000.00),
('Savon 1kg', 5000.00);

INSERT INTO ville (nom) VALUES
('Antananarivo'),
('Toamasina'),
('Mahajanga'),
('Toliara'),
('Fianarantsoa'),
('Mananjary'),
('Antsiranana');

INSERT INTO don (id_besoin, nom_donneur, quantite, date_don) VALUES
(1, 'ONG CARE Madagascar', 200, '2026-02-01'),
(3, 'UNICEF Madagascar', 500, '2026-02-02'),
(4, 'Groupe TELMA', 50, '2026-02-03'),
(6, 'Particulier Rakoto', 100, '2026-02-04'),
(2, 'Entreprise STAR', 300, '2026-02-05'),
(5, 'Croix Rouge Malagasy', 80, '2026-02-06');

INSERT INTO ville_besoin (id_ville, id_besoin, dateB, quantite) VALUES
(2, 1, '2026-01-28', 400),  -- Toamasina a besoin de riz
(2, 3, '2026-01-28', 600),  -- Toamasina eau potable
(6, 4, '2026-01-29', 150),  -- Mananjary tentes
(6, 6, '2026-01-29', 300),  -- Mananjary couvertures
(1, 5, '2026-01-30', 120),  -- Antananarivo kits médicaux
(3, 1, '2026-01-30', 200),  -- Mahajanga riz
(4, 3, '2026-01-31', 250);  -- Toliara eau
