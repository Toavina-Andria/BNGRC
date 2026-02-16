CREATE DATABASE bngrc;
USE bngrc;

CREATE TABLE IF NOT EXISTS region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    population INT NOT NULL,
    id_region INT,
    FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE IF NOT EXISTS sinistre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_sinistres INT NOT NULL,
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES ville(id)
);

CREATE TABLE IF NOT EXISTS categorie_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS sinistre_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sinistre INT,
    id_categorie_besoin INT,
    description VARCHAR(255),
    quantite INT NOT NULL,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id),
    FOREIGN KEY (id_categorie_besoin) REFERENCES categorie_besoin(id)
);