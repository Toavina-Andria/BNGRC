CREATE DATABASE bngrc;
USE bngrc;

CREATE TABLE IF NOT EXISTS bn_region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS bn_ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    population INT NOT NULL,
    id_region INT,
    FOREIGN KEY (id_region) REFERENCES bn_region(id)
);

CREATE TABLE IF NOT EXISTS bn_sinistre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_sinistres INT NOT NULL,
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES bn_ville(id)
);

CREATE TABLE IF NOT EXISTS bn_categorie_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bn_sinistre_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sinistre INT,
    id_categorie_besoin INT,
    description VARCHAR(255),
    quantite INT NOT NULL,
    prix_unitaite DECIMAL(10, 2),
    FOREIGN KEY (id_sinistre) REFERENCES bn_sinistre(id),
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id)
);

CREATE OR REPLACE TABLE bn_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie_besoin INT NOT NULL,
    donateur VARCHAR(100),
    description TEXT,
    quantite INT NOT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id) ON DELETE CASCADE
);
