CREATE TABLE bn_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('argent', 'nature') NOT NULL,
    donateur VARCHAR(100),
    date_don DATETIME NOT NULL,
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES bn_ville(id)
);

CREATE TABLE bn_don_argent (
    id_don INT PRIMARY KEY,
    montant DECIMAL(12,2) NOT NULL,
    montant_restant DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_don) REFERENCES bn_don(id)
);

CREATE TABLE bn_don_nature (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_don INT,
    id_categorie_besoin INT,
    description VARCHAR(255),
    quantite INT NOT NULL,
    FOREIGN KEY (id_don) REFERENCES bn_don(id),
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id)
);

CREATE TABLE bn_achat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_don_argent INT,
    id_sinistre_besoin INT,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    frais_pourcentage DECIMAL(5,2) NOT NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    date_achat DATETIME NOT NULL,
    FOREIGN KEY (id_don_argent) REFERENCES bn_don_argent(id_don),
    FOREIGN KEY (id_sinistre_besoin) REFERENCES bn_sinistre_besoin(id)
);
