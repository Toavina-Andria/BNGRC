--view sinstres details
CREATE OR REPLACE VIEW sinistre_details AS
SELECT
    s.id AS sinistre_id,
    s.nombre_sinistres,
    v.nom AS ville_nom,
    v.population AS ville_population,
    r.nom AS region_nom,
    cb.nom AS categorie_besoin_nom,
    sb.description AS besoin_description,
    sb.quantite AS besoin_quantite
FROM bn_sinistre s
JOIN bn_ville v ON s.id_ville = v.id
JOIN bn_region r ON v.id_region = r.id
LEFT JOIN bn_sinistre_besoin sb ON s.id = sb.id_sinistre
LEFT JOIN bn_categorie_besoin cb ON sb.id_categorie_besoin = cb.id;