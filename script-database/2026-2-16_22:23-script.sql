CREATE OR REPLACE TABLE bn_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_categorie_besoin INT NOT NULL,
    donateur VARCHAR(100),
    description TEXT,
    quantite INT NOT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie_besoin) REFERENCES bn_categorie_besoin(id) ON DELETE CASCADE
);
