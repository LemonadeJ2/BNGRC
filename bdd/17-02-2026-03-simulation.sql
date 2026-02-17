-- Script d'exemple: Données pour tester la simulation
-- Exécuter APRÈS avoir créé la base de données BNGRC

-- ============================================
-- 1. VÉRIFIER LES DONNÉES EXISTANTES
-- ============================================

-- Vérifier les types de besoins
SELECT * FROM type_besoin;

-- Vérifier les besoins
SELECT * FROM besoin;

-- Vérifier les villes
SELECT * FROM ville;

-- Vérifier les dons existants
SELECT COUNT(*) as nb_dons_actuels FROM don;

-- Vérifier les besoins par ville
SELECT COUNT(*) as nb_besoins_par_ville FROM ville_besoin;




-- Suppression des anciens dons
DELETE FROM don;

-- Insertion des nouveaux dons (ville par défaut: Toamasina id=1)
INSERT INTO don (id_ville, id_besoin, nom_donneur, quantite, date_don) VALUES
-- argent
    (1, 10, 'Donateur', 5000000, '2026-02-16'),
    (1, 10, 'Donateur', 3000000, '2026-02-16'),
    (1, 10, 'Donateur', 4000000, '2026-02-17'),
    (1, 10, 'Donateur', 1500000, '2026-02-17'),
    (1, 10, 'Donateur', 6000000, '2026-02-17'),
-- nature
    (1, 1, 'Donateur', 400, '2026-02-16'),
    (1, 2, 'Donateur', 600, '2026-02-16'),
    (1, 4, 'Donateur', 100, '2026-02-17'),
    (1, 1, 'Donateur', 2000, '2026-02-18'),
    (1, 2, 'Donateur', 5000, '2026-02-18'),
    (1, 4, 'Donateur', 88, '2026-02-17'),
-- materiel
    (1, 5, 'Donateur', 50, '2026-02-17'),
    (1, 6, 'Donateur', 70, '2026-02-17'),
    (1, 5, 'Donateur', 300, '2026-02-18'),
    (1, 6, 'Donateur', 500, '2026-02-19');

-- ================= NOUVELLES DONNÉES DE SIMULATION =================
-- Les besoins par ville (ville, date, ordre, categorie, libelle, prix_unitaire, quantite)
-- On suppose que les tables ville, besoin, type_besoin existent déjà

-- Nettoyage des anciennes données
DELETE FROM ville_besoin;
DELETE FROM besoin;
DELETE FROM ville;
DELETE FROM type_besoin;

-- Catégories/types
INSERT INTO type_besoin (id, type_besoin) VALUES
    (1, 'nature'),
    (2, 'materiel'),
    (3, 'argent');

-- Villes
INSERT INTO ville (id, nom) VALUES
    (1, 'Toamasina'),
    (2, 'Mananjary'),
    (3, 'Farafangana'),
    (4, 'Nosy Be'),
    (5, 'Morondava');

-- Besoins (libelle, prix, type)
INSERT INTO besoin (id, nom, prix, id_type_besoin) VALUES
    (1, 'Riz (kg)', 3000, 1),
    (2, 'Eau (L)', 1000, 1),
    (3, 'Huile (L)', 6000, 1),
    (4, 'Haricots', 4000, 1),
    (5, 'Tôle', 25000, 2),
    (6, 'Bâche', 15000, 2),
    (7, 'Clous (kg)', 8000, 2),
    (8, 'Bois', 10000, 2),
    (9, 'groupe', 6750000, 2),
    (10, 'Argent', 1, 3);

-- Besoins par ville (ville, date, ordre, categorie, libelle, prix_unitaire, quantite)
INSERT INTO ville_besoin (id_ville, id_besoin, dateB, quantite) VALUES
-- Toamasina
    (1, 1, '2026-02-16', 800),
    (1, 2, '2026-02-15', 1500),
    (1, 5, '2026-02-16', 120),
    (1, 6, '2026-02-15', 200),
    (1, 10, '2026-02-16', 12000000),
    (1, 9, '2026-02-15', 3),
-- Mananjary
    (2, 1, '2026-02-15', 500),
    (2, 3, '2026-02-16', 120),
    (2, 5, '2026-02-15', 80),
    (2, 7, '2026-02-16', 60),
    (2, 10, '2026-02-15', 6000000),
-- Farafangana
    (3, 1, '2026-02-16', 600),
    (3, 2, '2026-02-15', 1000),
    (3, 6, '2026-02-16', 150),
    (3, 8, '2026-02-15', 100),
    (3, 10, '2026-02-16', 8000000),
-- Nosy Be
    (4, 1, '2026-02-15', 300),
    (4, 4, '2026-02-16', 200),
    (4, 5, '2026-02-15', 40),
    (4, 7, '2026-02-16', 30),
    (4, 10, '2026-02-15', 4000000),
-- Morondava
    (5, 1, '2026-02-16', 700),
    (5, 2, '2026-02-15', 1200),
    (5, 6, '2026-02-16', 180),
    (5, 8, '2026-02-15', 150),
    (5, 10, '2026-02-16', 10000000);

-- ============================================
-- 3. CALCULER LES STATISTIQUES ACTUELLES
-- ============================================

-- Total des dons en montant
SELECT 
    SUM(d.quantite * b.prix) as total_dons_montant
FROM don d
JOIN besoin b ON d.id_besoin = b.id;
u;s
-- Total des besoins en montant
SELECT 
    SUM(vb.quantite * b.prix) as total_besoins_montant
FROM ville_besoin vb
JOIN besoin b ON vb.id_besoin = b.id;

-- Distribution des dons par type
SELECT 
    t.type_besoin,
    COUNT(d.id) as nb_dons,
    SUM(d.quantite * b.prix) as montant_total
FROM don d
JOIN besoin b ON d.id_besoin = b.id
JOIN type_besoin t ON b.id_type_besoin = t.id
GROUP BY b.id_type_besoin, t.type_besoin;

-- Distribution des besoins par type
SELECT 
    t.type_besoin,
    COUNT(vb.id) as nb_besoins,
    SUM(vb.quantite * b.prix) as montant_total
FROM ville_besoin vb
JOIN besoin b ON vb.id_besoin = b.id
JOIN type_besoin t ON b.id_type_besoin = t.id
GROUP BY b.id_type_besoin, t.type_besoin;

-- ============================================
-- 4. VISUALISER LES DONNÉES DE SIMULATION
-- ============================================

-- Tous les dons avec détails
SELECT 
    d.id,
    v.nom as ville,
    b.nom as besoin,
    t.type_besoin,
    d.nom_donneur,
    d.quantite,
    b.prix,
    (d.quantite * b.prix) as montant,
    d.date_don
FROM don d
JOIN ville v ON d.id_ville = v.id
JOIN besoin b ON d.id_besoin = b.id
LEFT JOIN type_besoin t ON b.id_type_besoin = t.id
ORDER BY d.date_don DESC;

-- Tous les besoins par ville avec détails
SELECT 
    v.nom as ville,
    b.nom as besoin,
    t.type_besoin,
    vb.quantite,
    b.prix,
    (vb.quantite * b.prix) as montant_total,
    vb.dateB
FROM ville_besoin vb
JOIN ville v ON vb.id_ville = v.id
JOIN besoin b ON vb.id_besoin = b.id
LEFT JOIN type_besoin t ON b.id_type_besoin = t.id
ORDER BY v.nom, b.nom;

-- ============================================
-- 5. SIMULER MANUELLEMENT AVANT D'UTILISER L'APP
-- ============================================

-- Voir les dons par type
SELECT 
    type_besoin,
    GROUP_CONCAT(CONCAT(nom_donneur, ' (', quantite, ')') SEPARATOR ', ') as dons
FROM (
    SELECT 
        t.type_besoin,
        d.nom_donneur,
        d.quantite,
        d.id
    FROM don d
    JOIN besoin b ON d.id_besoin = b.id
    JOIN type_besoin t ON b.id_type_besoin = t.id
    ORDER BY t.type_besoin, d.id
) as sub
GROUP BY type_besoin;

-- Voir les besoins par type et ville
SELECT 
    v.nom as ville,
    t.type_besoin,
    GROUP_CONCAT(CONCAT(b.nom, ' (', vb.quantite, ')') SEPARATOR ', ') as besoins
FROM ville_besoin vb
JOIN ville v ON vb.id_ville = v.id
JOIN besoin b ON vb.id_besoin = b.id
JOIN type_besoin t ON b.id_type_besoin = t.id
GROUP BY v.nom, t.type_besoin
ORDER BY v.nom, t.type_besoin;

-- ============================================
-- 6. VÉRIFIER APRÈS SIMULATION
-- ============================================

-- État de sim_save après simulation
SELECT * FROM sim_save ORDER BY id DESC LIMIT 1;

-- Nombre de dons sauvegardés
SELECT 
    COUNT(*) as nb_dons_sauvegardes,
    SUM(montant) as total_montant
FROM sim_don 
WHERE id_save = (SELECT MAX(id) FROM sim_save);

-- Nombre de besoins sauvegardés
SELECT 
    COUNT(*) as nb_besoins_sauvegardes,
    SUM(quantite * prix_unitaire) as total_montant
FROM sim_besoin 
WHERE id_save = (SELECT MAX(id) FROM sim_save);

-- Nombre de résultats de simulation
SELECT 
    COUNT(*) as nb_distributions,
    SUM(montant_utilise) as total_monte_utilise
FROM sim_resultat 
WHERE id_save = (SELECT MAX(id) FROM sim_save);

-- Détail des distributions proposées
SELECT 
    sr.id,
    v.nom as ville,
    b.nom as besoin,
    sr.quantite_proposee,
    sr.provenance,
    sr.montant_utilise
FROM sim_resultat sr
JOIN ville v ON sr.id_ville = v.id
JOIN besoin b ON sr.id_besoin = b.id
WHERE sr.id_save = (SELECT MAX(id) FROM sim_save)
ORDER BY v.nom, b.nom;

-- ============================================
-- 7. VÉRIFIER APRÈS VALIDATION
-- ============================================

-- État des dons après validation
SELECT 
    d.id,
    v.nom as ville,
    b.nom as besoin,
    d.quantite as quantite_actuelle,
    sd.quantite as quantite_initiale,
    (sd.quantite - d.quantite) as quantite_utilisee
FROM don d
JOIN sim_don sd ON d.id = sd.id_don
JOIN ville v ON d.id_ville = v.id
JOIN besoin b ON d.id_besoin = b.id
WHERE sd.id_save = (SELECT MAX(id) FROM sim_save)
AND d.quantite < sd.quantite;

-- État des besoins après validation
SELECT 
    v.nom as ville,
    b.nom as besoin,
    vb.quantite as quantite_restante,
    sb.quantite as quantite_initiale,
    (sb.quantite - vb.quantite) as quantite_satisfaite
FROM ville_besoin vb
JOIN sim_besoin sb ON vb.id = sb.id_ville_besoin
JOIN ville v ON vb.id_ville = v.id
JOIN besoin b ON vb.id_besoin = b.id
WHERE sb.id_save = (SELECT MAX(id) FROM sim_save)
AND vb.quantite < sb.quantite;

-- ============================================
-- 8. STATISTIQUES GLOBALES
-- ============================================

-- Avant/Après simulation pour chaque ville
SELECT 
    v.nom as ville,
    
    -- Besoins
    SUM(CASE WHEN b.id_type_besoin IN (1,2) THEN vb.quantite * b.prix ELSE 0 END) as besoins_nature_materiel,
    
    -- Dons reçus
    (SELECT SUM(d.quantite * b.prix) 
     FROM don d 
     JOIN besoin b ON d.id_besoin = b.id 
     WHERE d.id_ville = v.id) as dons_montant,
    
    -- Achats effectués
    (SELECT SUM(montant_total) FROM achat WHERE id_ville = v.id) as achats_montant
FROM ville_besoin vb
JOIN ville v ON vb.id_ville = v.id
JOIN besoin b ON vb.id_besoin = b.id
GROUP BY v.id, v.nom
ORDER BY v.nom;

-- Résumé général
SELECT 
    COUNT(DISTINCT v.id) as nb_villes,
    COUNT(DISTINCT b.id) as nb_besoins,
    COUNT(DISTINCT d.id) as nb_dons,
    SUM(vb.quantite * b.prix) as total_besoins,
    SUM(d.quantite * b2.prix) as total_dons,
    SUM(vb.quantite * b.prix) - SUM(d.quantite * b2.prix) as besoin_restant
FROM ville v
JOIN ville_besoin vb ON v.id = vb.id_ville
JOIN besoin b ON vb.id_besoin = b.id
LEFT JOIN don d ON TRUE
LEFT JOIN besoin b2 ON d.id_besoin = b2.id;

-- ============================================
-- 9. NETTOYER (OPTIONNEL)
-- ============================================

-- Supprimer les anciennes simulations (garder les 3 dernières)
DELETE FROM sim_save 
WHERE id NOT IN (
    SELECT id FROM (
        SELECT id FROM sim_save 
        ORDER BY date_save DESC 
        LIMIT 3
    ) as sub
);

-- Note: CASCADE supprimera aussi les sim_don, sim_besoin, sim_achat, sim_resultat

-- ============================================
-- 10. COMMANDES UTILES POUR TESTER
-- ============================================

-- Réinitialiser complètement une simulation
-- (à utiliser avec précaution - supprime l'historique)

DELETE FROM sim_resultat WHERE id_save = [SAVE_ID];
DELETE FROM sim_achat WHERE id_save = [SAVE_ID];
DELETE FROM sim_don WHERE id_save = [SAVE_ID];
DELETE FROM sim_besoin WHERE id_save = [SAVE_ID];
DELETE FROM sim_save WHERE id = [SAVE_ID];

-- Restaurer les données à l'état initial
-- (avant toute validation)

-- Récupérer les valeurs initiales depuis sim_don
UPDATE don d
SET d.quantite = (
    SELECT sd.quantite FROM sim_don sd 
    WHERE sd.id_don = d.id 
    LIMIT 1
)
WHERE d.id IN (
    SELECT DISTINCT id_don FROM sim_don 
    WHERE id_save = (SELECT MAX(id) FROM sim_save)
);

-- Récupérer les valeurs initiales depuis sim_besoin
UPDATE ville_besoin vb
SET vb.quantite = (
    SELECT sb.quantite FROM sim_besoin sb 
    WHERE sb.id_ville_besoin = vb.id 
    LIMIT 1
)
WHERE vb.id IN (
    SELECT DISTINCT id_ville_besoin FROM sim_besoin 
    WHERE id_save = (SELECT MAX(id) FROM sim_save)
);