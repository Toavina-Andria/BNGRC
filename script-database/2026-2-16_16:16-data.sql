-- Sample data for BNGRC database
-- Regions
INSERT INTO bn_region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava'),
('Sofia'),
('Boeny');

-- Cities
INSERT INTO bn_ville (nom, population, id_region) VALUES
('Antananarivo', 1400000, 1),
('Antsirabe', 257000, 2),
('Miarinarivo', 200000, 3),
('Tsiroanomandidy', 26000, 4),
('Antsohihy', 37000, 5),
('Mahajanga', 273000, 6);

-- Disasters (Sinistres)
INSERT INTO bn_sinistre (nombre_sinistres, id_ville) VALUES
(150, 1),  -- Cyclone in Antananarivo
(80, 2),   -- Flood in Antsirabe
(45, 3),   -- Drought in Miarinarivo
(25, 4);   -- Landslide in Tsiroanomandidy

-- Categories of Needs
INSERT INTO bn_categorie_besoin (nom) VALUES
('Food'),
('Water'),
('Shelter'),
('Medical Supplies'),
('Clothing'),
('Hygiene Kits');

-- Needs for Disasters
INSERT INTO bn_sinistre_besoin (id_sinistre, id_categorie_besoin, description, quantite) VALUES
(1, 1, 'Rice and canned goods for affected families', 5000),
(1, 2, 'Bottled water and purification tablets', 10000),
(1, 3, 'Temporary tents and tarps', 200),
(1, 4, 'First aid kits and medicines', 300),
(1, 5, 'Blankets and warm clothing', 1000),
(1, 6, 'Soap, toothpaste, and sanitary products', 800),
(2, 1, 'Non-perishable food items', 2000),
(2, 2, 'Clean drinking water supplies', 4000),
(2, 3, 'Emergency shelter materials', 100),
(2, 4, 'Medical assistance supplies', 150),
(3, 1, 'Food aid for drought-affected areas', 1500),
(3, 2, 'Water distribution support', 3000),
(3, 6, 'Hygiene and sanitation supplies', 400),
(4, 3, 'Shelter reconstruction materials', 50),
(4, 1, 'Emergency food rations', 500),
(4, 4, 'Medical care for injured', 75);