-- Données de test pour BNGRC (incluant achats et paramètres)
-- À exécuter après 20260216-01-table.sql et 20260217-01-achat.sql
-- Date : 17 février 2026

-- USE bngrc_db;

-- =====================================================
-- REGIONS
-- =====================================================
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

-- =====================================================
-- VILLES
-- =====================================================
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
-- Antananarivo (ville_id: 1)
(1, 'Riz', 5000.00, 0.00, 2500.00),           -- besoin 1
(1, 'Huile', 1000.00, 0.00, 8000.00),          -- besoin 2
(1, 'Couvertures', 500.00, 0.00, 25000.00),    -- besoin 3

-- Antsirabe (ville_id: 2)
(2, 'Riz', 3000.00, 0.00, 2500.00),            -- besoin 4
(2, 'Eau potable', 10000.00, 0.00, 500.00),    -- besoin 5
(2, 'Médicaments', 200.00, 0.00, 15000.00),    -- besoin 6

-- Miarinarivo (ville_id: 3)
(3, 'Tôle', 800.00, 0.00, 45000.00),           -- besoin 7
(3, 'Riz', 2000.00, 0.00, 2500.00),            -- besoin 8
(3, 'Savon', 1500.00, 0.00, 2000.00),          -- besoin 9

-- Tsiroanomandidy (ville_id: 4)
(4, 'Riz', 4000.00, 0.00, 2500.00),            -- besoin 10
(4, 'Sucre', 500.00, 0.00, 4000.00),           -- besoin 11
(4, 'Huile', 800.00, 0.00, 8000.00),           -- besoin 12

-- Antsohihy (ville_id: 5)
(5, 'Tente', 150.00, 0.00, 200000.00),         -- besoin 13
(5, 'Riz', 6000.00, 0.00, 2500.00),            -- besoin 14
(5, 'Eau potable', 15000.00, 0.00, 500.00),    -- besoin 15

-- Mahajanga (ville_id: 6)
(6, 'Riz', 8000.00, 0.00, 2500.00),            -- besoin 16
(6, 'Couvertures', 1000.00, 0.00, 25000.00),   -- besoin 17
(6, 'Médicaments', 500.00, 0.00, 15000.00),    -- besoin 18

-- Maevatanana (ville_id: 7)
(7, 'Tôle', 500.00, 0.00, 45000.00),           -- besoin 19
(7, 'Riz', 2500.00, 0.00, 2500.00),            -- besoin 20

-- Maintirano (ville_id: 8)
(8, 'Riz', 3500.00, 0.00, 2500.00),            -- besoin 21
(8, 'Huile', 600.00, 0.00, 8000.00),           -- besoin 22
(8, 'Tente', 100.00, 0.00, 200000.00),         -- besoin 23

-- Ambatondrazaka (ville_id: 9)
(9, 'Riz', 7000.00, 0.00, 2500.00),            -- besoin 24
(9, 'Eau potable', 20000.00, 0.00, 500.00),    -- besoin 25
(9, 'Savon', 2000.00, 0.00, 2000.00),          -- besoin 26

-- Toamasina (ville_id: 10)
(10, 'Riz', 10000.00, 0.00, 2500.00),          -- besoin 27
(10, 'Tôle', 1500.00, 0.00, 45000.00),         -- besoin 28
(10, 'Médicaments', 800.00, 0.00, 15000.00),   -- besoin 29
(10, 'Couvertures', 2000.00, 0.00, 25000.00);  -- besoin 30

-- =====================================================
-- DONS REÇUS (type_don: argent / nature / materiaux)
-- Pour les dons en argent: montant renseigné, quantite NULL
-- Pour les dons nature/materiaux: quantite renseignée, montant NULL
-- restant = montant ou quantite initialement (ce qui reste à dispatcher)
-- =====================================================

-- ---------- Dons en ARGENT ----------
INSERT INTO dons (type_don, montant, quantite, restant, date_don, dispatched) VALUES 
('argent', 50000000.00, NULL, 50000000.00, '2026-02-01 08:00:00', FALSE),   -- don 1 : 50M Ar
('argent', 25000000.00, NULL, 25000000.00, '2026-02-03 10:30:00', FALSE),   -- don 2 : 25M Ar
('argent', 100000000.00, NULL, 100000000.00, '2026-02-06 09:00:00', FALSE), -- don 3 : 100M Ar
('argent', 15000000.00, NULL, 15000000.00, '2026-02-09 14:00:00', FALSE),   -- don 4 : 15M Ar
('argent', 75000000.00, NULL, 75000000.00, '2026-02-12 11:30:00', FALSE);   -- don 5 : 75M Ar

-- ---------- Dons en NATURE ----------
INSERT INTO dons (type_don, montant, quantite, restant, date_don, dispatched) VALUES 
('nature', NULL, 5000.00, 5000.00, '2026-02-01 09:00:00', FALSE),    -- don 6 : Riz 5000kg
('nature', NULL, 3000.00, 3000.00, '2026-02-03 11:00:00', FALSE),    -- don 7 : Riz 3000kg
('nature', NULL, 8000.00, 8000.00, '2026-02-05 14:00:00', FALSE),    -- don 8 : Riz 8000kg
('nature', NULL, 500.00, 500.00, '2026-02-02 11:00:00', FALSE),      -- don 9 : Huile 500L
('nature', NULL, 800.00, 800.00, '2026-02-07 16:45:00', FALSE),      -- don 10 : Huile 800L
('nature', NULL, 1000.00, 1000.00, '2026-02-06 10:00:00', FALSE),    -- don 11 : Couvertures
('nature', NULL, 500.00, 500.00, '2026-02-11 11:00:00', FALSE),      -- don 12 : Couvertures
('nature', NULL, 10000.00, 10000.00, '2026-02-02 08:30:00', FALSE),  -- don 13 : Eau potable
('nature', NULL, 200.00, 200.00, '2026-02-05 09:45:00', FALSE),      -- don 14 : Médicaments
('nature', NULL, 1000.00, 1000.00, '2026-02-03 14:00:00', FALSE),    -- don 15 : Savon
('nature', NULL, 300.00, 300.00, '2026-02-04 16:00:00', FALSE),      -- don 16 : Sucre
('nature', NULL, 50.00, 50.00, '2026-02-06 08:00:00', FALSE);        -- don 17 : Tentes

-- ---------- Dons en MATERIAUX ----------
INSERT INTO dons (type_don, montant, quantite, restant, date_don, dispatched) VALUES 
('materiaux', NULL, 300.00, 300.00, '2026-02-04 09:00:00', FALSE),   -- don 18 : Tôle 300 pièces
('materiaux', NULL, 500.00, 500.00, '2026-02-08 13:30:00', FALSE),   -- don 19 : Tôle 500 pièces
('materiaux', NULL, 80.00, 80.00, '2026-02-13 14:30:00', FALSE),     -- don 20 : Tentes 80 unités
('materiaux', NULL, 2000.00, 2000.00, '2026-02-10 09:15:00', FALSE); -- don 21 : Riz 2000kg

-- =====================================================
-- PARAMETRES (frais d'achat par défaut)
-- =====================================================
INSERT INTO parametres (frais_achat_percent) VALUES (10.00);

-- =====================================================
-- DISPATCH (exemples de dons nature/materiaux déjà dispatchés)
-- =====================================================

-- Don 6 (nature, 5000 riz) → Antananarivo besoin 1 (riz 5000)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(6, 1, 5000.00, '2026-02-02 10:00:00');
UPDATE besoins SET quantite_recue = 5000.00 WHERE id = 1;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 6;

-- Don 9 (nature, 500 huile) → Antananarivo besoin 2 (huile 1000)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(9, 2, 500.00, '2026-02-03 09:00:00');
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 2;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 9;

-- Don 11 (nature, 1000 couvertures) → Antananarivo 500 + Mahajanga 500
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(11, 3, 500.00, '2026-02-07 11:00:00'),
(11, 17, 500.00, '2026-02-07 11:30:00');
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 3;
UPDATE besoins SET quantite_recue = 500.00 WHERE id = 17;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 11;

-- Don 13 (nature, 10000 eau potable) → Antsirabe 10000
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(13, 5, 10000.00, '2026-02-04 14:00:00');
UPDATE besoins SET quantite_recue = 10000.00 WHERE id = 5;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 13;

-- Don 14 (nature, 200 médicaments) → Antsirabe besoin 6
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(14, 6, 200.00, '2026-02-06 10:00:00');
UPDATE besoins SET quantite_recue = 200.00 WHERE id = 6;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 14;

-- Don 18 (materiaux, 300 tôle) → Miarinarivo besoin 7
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(18, 7, 300.00, '2026-02-05 10:00:00');
UPDATE besoins SET quantite_recue = 300.00 WHERE id = 7;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 18;

-- Don 15 (nature, 1000 savon) → Miarinarivo besoin 9
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, date_dispatch) VALUES 
(15, 9, 1000.00, '2026-02-05 15:00:00');
UPDATE besoins SET quantite_recue = 1000.00 WHERE id = 9;
UPDATE dons SET restant = 0.00, dispatched = TRUE WHERE id = 15;

-- =====================================================
-- ACHATS (utilisation de dons en argent pour acheter des besoins)
-- montant_achat = quantité × prix_unitaire
-- frais_percent = taux de frais (tiré de parametres)
-- montant_total = montant_achat × (1 + frais_percent/100)
-- =====================================================

-- Don 1 (argent, 50M Ar) utilisé pour achats :
-- Achat 1 : Riz pour Antsirabe (besoin 4) → 2000kg × 2500 = 5 000 000 + 10% = 5 500 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(1, 2, 4, 5000000.00, 10.00, 5500000.00, '2026-02-02 14:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 2000.00 WHERE id = 4;
UPDATE dons SET restant = restant - 5500000.00 WHERE id = 1;

-- Achat 2 : Riz pour Tsiroanomandidy (besoin 10) → 3000kg × 2500 = 7 500 000 + 10% = 8 250 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(1, 4, 10, 7500000.00, 10.00, 8250000.00, '2026-02-02 15:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 3000.00 WHERE id = 10;
UPDATE dons SET restant = restant - 8250000.00 WHERE id = 1;

-- Achat 3 : Tente pour Antsohihy (besoin 13) → 50 × 200 000 = 10 000 000 + 10% = 11 000 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(1, 5, 13, 10000000.00, 10.00, 11000000.00, '2026-02-02 16:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 50.00 WHERE id = 13;
UPDATE dons SET restant = restant - 11000000.00 WHERE id = 1;

-- Don 2 (argent, 25M Ar) utilisé pour achats :
-- Achat 4 : Médicaments pour Mahajanga (besoin 18) → 200 × 15 000 = 3 000 000 + 10% = 3 300 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(2, 6, 18, 3000000.00, 10.00, 3300000.00, '2026-02-04 10:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 200.00 WHERE id = 18;
UPDATE dons SET restant = restant - 3300000.00 WHERE id = 2;

-- Achat 5 : Huile pour Tsiroanomandidy (besoin 12) → 400 × 8 000 = 3 200 000 + 10% = 3 520 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(2, 4, 12, 3200000.00, 10.00, 3520000.00, '2026-02-04 11:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 400.00 WHERE id = 12;
UPDATE dons SET restant = restant - 3520000.00 WHERE id = 2;

-- Achat 6 : Riz pour Mahajanga (besoin 16) → 4000 × 2500 = 10 000 000 + 10% = 11 000 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(2, 6, 16, 10000000.00, 10.00, 11000000.00, '2026-02-04 14:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 4000.00 WHERE id = 16;
UPDATE dons SET restant = restant - 11000000.00 WHERE id = 2;

-- Don 3 (argent, 100M Ar) utilisé pour achats :
-- Achat 7 : Tôle pour Toamasina (besoin 28) → 500 × 45 000 = 22 500 000 + 10% = 24 750 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(3, 10, 28, 22500000.00, 10.00, 24750000.00, '2026-02-07 09:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 500.00 WHERE id = 28;
UPDATE dons SET restant = restant - 24750000.00 WHERE id = 3;

-- Achat 8 : Couvertures pour Toamasina (besoin 30) → 800 × 25 000 = 20 000 000 + 10% = 22 000 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(3, 10, 30, 20000000.00, 10.00, 22000000.00, '2026-02-07 10:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 800.00 WHERE id = 30;
UPDATE dons SET restant = restant - 22000000.00 WHERE id = 3;

-- Achat 9 : Riz pour Ambatondrazaka (besoin 24) → 5000 × 2500 = 12 500 000 + 10% = 13 750 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(3, 9, 24, 12500000.00, 10.00, 13750000.00, '2026-02-07 14:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 5000.00 WHERE id = 24;
UPDATE dons SET restant = restant - 13750000.00 WHERE id = 3;

-- Achat 10 : Médicaments pour Toamasina (besoin 29) → 400 × 15 000 = 6 000 000 + 10% = 6 600 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(3, 10, 29, 6000000.00, 10.00, 6600000.00, '2026-02-08 09:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 400.00 WHERE id = 29;
UPDATE dons SET restant = restant - 6600000.00 WHERE id = 3;

-- Don 4 (argent, 15M Ar) utilisé pour achats :
-- Achat 11 : Riz pour Maevatanana (besoin 20) → 2500 × 2500 = 6 250 000 + 10% = 6 875 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(4, 7, 20, 6250000.00, 10.00, 6875000.00, '2026-02-10 10:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 2500.00 WHERE id = 20;
UPDATE dons SET restant = restant - 6875000.00 WHERE id = 4;

-- Achat 12 : Savon pour Ambatondrazaka (besoin 26) → 1500 × 2000 = 3 000 000 + 10% = 3 300 000
INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) VALUES 
(4, 9, 26, 3000000.00, 10.00, 3300000.00, '2026-02-10 14:00:00');
UPDATE besoins SET quantite_recue = quantite_recue + 1500.00 WHERE id = 26;
UPDATE dons SET restant = restant - 3300000.00 WHERE id = 4;

-- =====================================================
-- STATISTIQUES ATTENDUES APRES IMPORT
-- =====================================================
-- Régions          : 10
-- Villes           : 25 (10 initiales + 15 supplémentaires)
-- Besoins          : 30
-- Dons total       : 21 (5 argent + 12 nature + 4 materiaux)
-- Dons dispatchés  : 7 (nature/materiaux via dispatch)
-- Achats effectués : 12 (via dons en argent)
-- Paramètres       : 1 (frais 10%)
--
-- Récapitulatif dons argent après achats :
--   Don 1 (50M)  : restant = 50M - 5.5M - 8.25M - 11M = 25 250 000
--   Don 2 (25M)  : restant = 25M - 3.3M - 3.52M - 11M = 7 180 000
--   Don 3 (100M) : restant = 100M - 24.75M - 22M - 13.75M - 6.6M = 32 900 000
--   Don 4 (15M)  : restant = 15M - 6.875M - 3.3M = 4 825 000
--   Don 5 (75M)  : restant = 75 000 000 (pas encore utilisé)
