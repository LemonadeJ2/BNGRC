CREATE DATABASE BNGRC;
USE BNGRC;

CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL
);

CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
);

CREATE TABLE don(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    nom_donneur VARCHAR(100) NOT NULL,
    quantite INT NOT NULL,
    date_don DATE NOT NULL,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
)

CREATE TABLE ville_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    dateB DATE NOT NULL,
    quantite INT NOT NULL,
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
);

