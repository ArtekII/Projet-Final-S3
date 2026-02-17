-- Données de test pour BNGRC
-- À exécuter après 20260216-01-table.sql

-- USE bngrc_db;

-- =====================================================
-- REGIONS (déjà insérées dans 01-table.sql)
-- =====================================================

-- Données initiales : Régions
INSERT INTO regions (nom) VALUES 
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava'),
('Sofia'),
('Boeny'),
('Betsiboka'),
('Melaky'),
('Alaotra-Mangoro'),
('Atsinanana');


-- Données initiales : Villes
INSERT INTO villes (nom, region_id) VALUES 
('Antananarivo', 1),
('Antsirabe', 2),
('Miarinarivo', 3),
('Tsiroanomandidy', 4),
('Antsohihy', 5),
('Mahajanga', 6),
('Maevatanana', 7),
('Maintirano', 8),
('Ambatondrazaka', 9),
('Toamasina', 10);

INSERT INTO villes (nom, region_id) VALUES 
-- Analamanga (région 1)
('Ambohidratrimo', 1),
('Andramasina', 1),
('Anjozorobe', 1),
-- Vakinankaratra (région 2)
('Ambatolampy', 2),
('Betafo', 2),
-- Itasy (région 3)
('Arivonimamo', 3),
('Soavinandriana', 3),
-- Sofia (région 5)
('Bealanana', 5),
('Mandritsara', 5),
-- Boeny (région 6)
('Marovoay', 6),
('Mitsinjo', 6),
-- Alaotra-Mangoro (région 9)
('Moramanga', 9),
('Andilamena', 9),
-- Atsinanana (région 10)
('Brickaville', 10),
('Vatomandry', 10);

-- =====================================================
-- BESOINS DES VILLES SINISTREES
-- =====================================================
INSERT INTO besoins (ville_id, type_besoin, quantite_demandee, quantite_recue, prix_unitaire) VALUES 
-- Antananarivo (id: 1)
(1, 'Riz', 5000.00, 0.00, 2500.00),
(1, 'Huile', 1000.00, 0.00, 8000.00),
(1, 'Couvertures', 500.00, 0.00, 25000.00),

-- Antsirabe (id: 2)
(2, 'Riz', 3000.00, 0.00, 2500.00),
(2, 'Eau potable', 10000.00, 0.00, 500.00),
(2, 'Médicaments', 200.00, 0.00, 15000.00),

-- Miarinarivo (id: 3)
(3, 'Tôle', 800.00, 0.00, 45000.00),
(3, 'Riz', 2000.00, 0.00, 2500.00),
(3, 'Savon', 1500.00, 0.00, 2000.00),

-- Tsiroanomandidy (id: 4)
(4, 'Riz', 4000.00, 0.00, 2500.00),
(4, 'Sucre', 500.00, 0.00, 4000.00),
(4, 'Huile', 800.00, 0.00, 8000.00),

-- Antsohihy (id: 5)
(5, 'Tente', 150.00, 0.00, 200000.00),
(5, 'Riz', 6000.00, 0.00, 2500.00),
(5, 'Eau potable', 15000.00, 0.00, 500.00),

-- Mahajanga (id: 6)
(6, 'Riz', 8000.00, 0.00, 2500.00),
(6, 'Couvertures', 1000.00, 0.00, 25000.00),
(6, 'Médicaments', 500.00, 0.00, 15000.00),

-- Maevatanana (id: 7)
(7, 'Tôle', 500.00, 0.00, 45000.00),
(7, 'Riz', 2500.00, 0.00, 2500.00),

-- Maintirano (id: 8)
(8, 'Riz', 3500.00, 0.00, 2500.00),
(8, 'Huile', 600.00, 0.00, 8000.00),
(8, 'Tente', 100.00, 0.00, 200000.00),

-- Ambatondrazaka (id: 9)
(9, 'Riz', 7000.00, 0.00, 2500.00),
(9, 'Eau potable', 20000.00, 0.00, 500.00),
(9, 'Savon', 2000.00, 0.00, 2000.00),

-- Toamasina (id: 10)
(10, 'Riz', 10000.00, 0.00, 2500.00),
(10, 'Tôle', 1500.00, 0.00, 45000.00),
(10, 'Médicaments', 800.00, 0.00, 15000.00),
(10, 'Couvertures', 2000.00, 0.00, 25000.00);

-- =====================================================
-- DONS REÇUS (certains déjà dispatchés, d'autres en attente)
-- =====================================================
INSERT INTO dons (type, quantite, date_don, dispatched) VALUES 
-- Dons de riz
('Riz', 5000.00, '2026-02-01 08:00:00', FALSE),
('Riz', 3000.00, '2026-02-03 10:30:00', FALSE),
('Riz', 8000.00, '2026-02-05 14:00:00', FALSE),
('Riz', 2000.00, '2026-02-10 09:15:00', FALSE),

-- Dons d'huile
('Huile', 500.00, '2026-02-02 11:00:00', FALSE),
('Huile', 800.00, '2026-02-07 16:45:00', FALSE),

-- Dons de tôle
('Tôle', 300.00, '2026-02-04 09:00:00', FALSE),
('Tôle', 500.00, '2026-02-08 13:30:00', FALSE),

-- Dons de couvertures
('Couvertures', 1000.00, '2026-02-06 10:00:00', FALSE),
('Couvertures', 500.00, '2026-02-11 11:00:00', FALSE),

-- Dons d'eau potable
('Eau potable', 10000.00, '2026-02-02 08:30:00', FALSE),
('Eau potable', 5000.00, '2026-02-09 15:00:00', FALSE),

-- Dons de médicaments
('Médicaments', 200.00, '2026-02-05 09:45:00', FALSE),
('Médicaments', 300.00, '2026-02-12 10:30:00', FALSE),

-- Dons de savon
('Savon', 1000.00, '2026-02-03 14:00:00', FALSE),
('Savon', 800.00, '2026-02-10 11:45:00', FALSE),

-- Dons de sucre
('Sucre', 300.00, '2026-02-04 16:00:00', FALSE),

-- Dons de tentes
('Tente', 50.00, '2026-02-06 08:00:00', FALSE),
('Tente', 80.00, '2026-02-13 14:30:00', FALSE);

-- =====================================================
-- EXEMPLES DE DISPATCH (optionnel - pour montrer l'historique)
-- =====================================================
-- Note: Vous pouvez utiliser la fonctionnalité de dispatch automatique 
-- dans l'application pour créer ces enregistrements

-- Exemple de dispatch manuel (commenté par défaut)
/*
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
-- Don de riz #1 (5000kg) distribué à Antananarivo (besoin id 1)
(1, 1, 5000.00, '2026-02-02 10:00:00'),

-- Don d'huile #5 (500L) distribué à Antananarivo (besoin id 2)
(5, 2, 500.00, '2026-02-03 09:00:00'),

-- Don de couvertures #9 (1000) distribué partiellement à Antananarivo et Mahajanga
(9, 3, 500.00, '2026-02-07 11:00:00'),
(9, 18, 500.00, '2026-02-07 11:30:00');

-- Mettre à jour les quantités reçues correspondantes
UPDATE besoins SET quantite_recue = 5000.00 WHERE id = 1;
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 2;
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 3;
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 18;

-- Marquer les dons comme dispatchés
UPDATE dons SET dispatched = TRUE WHERE id IN (1, 5, 9);
*/

-- =====================================================
-- STATISTIQUES ATTENDUES APRES IMPORT
-- =====================================================
-- Régions: 10
-- Villes: 25 (10 initiales + 15 supplémentaires)
-- Besoins: 28 types différents
-- Dons en attente: 19
-- Valeur totale des besoins: ~283 750 000 Ar
