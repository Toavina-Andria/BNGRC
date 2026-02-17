INSERT INTO bn_ville (nom, population, id_region) VALUES
('Morondava', 120000, 3),
('Antsiranana', 180000, 2),
('Manakara', 95000, 1);

INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES
(380, 6),
(720, 7),
(150, 8);

INSERT INTO bn_categorie_besoin (nom) VALUES
('Lampes solaires'),
('Groupes électrogènes'),
('Moustiquaires'),
('Kits scolaires');

INSERT INTO bn_sinistre_besoin
(id_sinistre, id_categorie_besoin, description, quantite, prix_unitaire)
VALUES
(6, 7, 'Lampes solaires rechargeables', 200, 45000),
(6, 9, 'Moustiquaires imprégnées', 350, 12000),
(7, 8, 'Groupes électrogènes 5kVA', 15, 1800000),
(7, 10, 'Kits scolaires complets', 500, 25000),
(8, 3, 'Médicaments anti-paludisme', 600, 8000);

INSERT INTO bn_don (type, donateur, date_don, id_ville) VALUES
('argent', 'Fondation Lumière', '2026-03-01 11:20:00', 6),
('nature', 'ONG Santé Plus', '2026-03-02 09:40:00', 7),
('argent', 'Banque Nationale', '2026-03-03 15:10:00', 8),
('nature', 'Entreprise Solaire Mada', '2026-03-04 13:00:00', 6);

INSERT INTO bn_don_argent (id_don, montant, montant_restant) VALUES
(6, 15000000, 15000000),
(8, 7500000, 7500000);

INSERT INTO bn_don_nature
(id_don, id_categorie_besoin, description, quantite)
VALUES
(7, 3, 'Boîtes médicaments urgence', 400),
(7, 9, 'Moustiquaires famille', 200),
(9, 7, 'Lampes solaires LED', 150);
