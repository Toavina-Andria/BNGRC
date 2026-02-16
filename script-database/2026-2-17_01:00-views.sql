-- Vue pour afficher les sinistres avec détails 
CREATE OR REPLACE VIEW v_sinistre_details AS
SELECT
    s.id AS sinistre_id,
    s.nombre_sinistres,
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.population AS ville_population,
    r.id AS region_id,
    r.nom AS region_nom,
    sb.id AS besoin_id,
    sb.id_categorie_besoin,
    cb.nom AS categorie_nom,
    sb.description AS besoin_description,
    sb.quantite AS besoin_quantite,
    sb.prix_unitaire,
    (sb.quantite * sb.prix_unitaire) AS montant_besoin
FROM bn_sinistre s
JOIN bn_ville v ON s.id_ville = v.id
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
LEFT JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id;

-- Vue pour les besoins par ville (agrégé)
CREATE OR REPLACE VIEW v_besoins_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.population,
    r.id AS region_id,
    r.nom AS region_nom,
    COUNT(DISTINCT s.id) AS nb_sinistres,
    SUM(s.nombre_sinistres) AS total_sinistres,
    COUNT(sb.id) AS nb_types_besoins,
    SUM(sb.quantite) AS total_quantite_besoins,
    SUM(sb.quantite * sb.prix_unitaire) AS montant_total_besoins
FROM bn_ville v
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre s ON v.id = s.id_ville
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
GROUP BY v.id, v.nom, v.population, r.id, r.nom;

-- Vue pour les dons par ville
CREATE OR REPLACE VIEW v_dons_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    d.id AS don_id,
    d.type AS don_type,
    d.donateur,
    d.date_don,
    CASE 
        WHEN d.type = 'argent' THEN da.montant_restant
        ELSE NULL 
    END AS montant_argent,
    dn.id_categorie_besoin,
    cb.nom AS categorie_nom,
    dn.description AS don_description,
    dn.quantite AS don_quantite
FROM bn_ville v
LEFT JOIN bn_don d ON v.id = d.id_ville
LEFT JOIN bn_don_argent da ON d.id = da.id_don
LEFT JOIN bn_don_nature dn ON d.id = dn.id_don
LEFT JOIN bn_categorie_besoin cb ON dn.id_categorie_besoin = cb.id
WHERE d.id IS NOT NULL;

-- Vue agrégée des dons par ville
CREATE OR REPLACE VIEW v_total_dons_par_ville AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    COUNT(DISTINCT d.id) AS nb_dons,
    SUM(CASE WHEN d.type = 'argent' THEN da.montant_restant ELSE 0 END) AS total_argent_disponible,
    COUNT(CASE WHEN d.type = 'nature' THEN 1 END) AS nb_dons_nature
FROM bn_ville v
LEFT JOIN bn_don d ON v.id = d.id_ville
LEFT JOIN bn_don_argent da ON d.id = da.id_don AND d.type = 'argent'
GROUP BY v.id, v.nom;

-- Vue principale pour le dashboard : ville avec besoins et dons
CREATE OR REPLACE VIEW v_dashboard_ville AS
SELECT 
    bv.ville_id,
    bv.ville_nom,
    bv.population,
    bv.region_id,
    bv.region_nom,
    bv.nb_sinistres,
    bv.total_sinistres,
    bv.nb_types_besoins,
    bv.total_quantite_besoins,
    bv.montant_total_besoins,
    COALESCE(dv.nb_dons, 0) AS nb_dons,
    COALESCE(dv.total_argent_disponible, 0) AS total_argent_disponible,
    COALESCE(dv.nb_dons_nature, 0) AS nb_dons_nature,
    CASE 
        WHEN bv.montant_total_besoins > 0 THEN 
            ROUND((COALESCE(dv.total_argent_disponible, 0) / bv.montant_total_besoins) * 100, 2)
        ELSE 0 
    END AS pourcentage_couverture
FROM v_besoins_par_ville bv
LEFT JOIN v_total_dons_par_ville dv ON bv.ville_id = dv.ville_id
WHERE bv.nb_sinistres > 0
ORDER BY bv.montant_total_besoins DESC;
