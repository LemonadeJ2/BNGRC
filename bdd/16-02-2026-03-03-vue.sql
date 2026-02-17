CREATE
OR REPLACE VIEW valeur_don AS
SELECT
    SUM(b.prix * d.quantite) AS total_dons
FROM
    don d
    JOIN besoin b ON d.id_besoin = b.id;




CREATE
OR REPLACE VIEW dons_par_ville AS
SELECT
    v.id,
    v.nom AS ville,
    b.nom AS besoin,
    d.nom_donneur,
    d.quantite,
    b.prix,
    (d.quantite * b.prix) AS montant_total,
    d.date_don
FROM
    don d
    JOIN ville v ON d.id_ville = v.id
    JOIN besoin b ON d.id_besoin = b.id
ORDER BY
    v.nom,
    d.date_don DESC;




CREATE
OR REPLACE VIEW nb_besoin_par_ville AS
SELECT
    v.id,
    v.nom AS ville,
    COUNT(vb.id) AS nb_besoins
FROM
    ville v
    LEFT JOIN ville_besoin vb ON v.id = vb.id_ville
GROUP BY
    v.id,
    v.nom;





CREATE
OR REPLACE VIEW detail_besoin_ville AS
SELECT
    v.id AS ville_id,
    v.nom AS ville,
    b.nom AS besoin,
    b.id AS id_besoin,
    vb.quantite AS quantite_prevue
FROM
    ville v
    INNER JOIN ville_besoin vb ON vb.id_ville = v.id
    INNER JOIN besoin b ON b.id = vb.id_besoin
ORDER BY
    v.nom,
    b.nom;




CREATE
OR REPLACE VIEW detail_don AS
SELECT
    d.id,
    v.nom AS ville,
    b.nom AS besoin,
    d.nom_donneur,
    d.quantite,
    b.prix,
    (d.quantite * b.prix) AS montant,
    d.date_don
FROM
    don d
    JOIN ville v ON d.id_ville = v.id
    JOIN besoin b ON d.id_besoin = b.id
ORDER BY
    d.date_don DESC;