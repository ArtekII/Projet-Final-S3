-- Données de test pour BNGRC
-- À exécuter après 20260216-01-table.sql et 20260217-01-achat.sql
-- Date : 17 février 2026

USE bngrc_db;

-- =====================================================
-- TYPES (table type : id, nom)
-- =====================================================
INSERT INTO type (nom) VALUES 
('argent'),      -- id 1
('nature'),      -- id 2
('materiaux');   -- id 3

-- =====================================================
-- REGIONS
-- =====================================================
INSERT INTO regions (nom) VALUES 
('Atsinanana'),        -- id 1  (Toamasina)
('Vatovavy'),          -- id 2  (Mananjary)
('Atsimo-Atsinanana'), -- id 3  (Farafangana)
('Diana'),             -- id 4  (Nosy Be)
('Menabe');             -- id 5  (Morondava)

-- =====================================================
-- VILLES
-- =====================================================
INSERT INTO villes (nom, region_id) VALUES 
('Toamasina', 1),      -- id 1
('Mananjary', 2),      -- id 2
('Farafangana', 3),    -- id 3
('Nosy Be', 4),        -- id 4
('Morondava', 5);      -- id 5

-- =====================================================
-- BESOINS DES VILLES SINISTREES
-- type_id : 1=argent, 2=nature, 3=materiaux
-- =====================================================
INSERT INTO besoins (ville_id, type_id, designation, quantite_demandee, quantite_recue, prix_unitaire, date_besoin, ordre) VALUES 
-- Toamasina (ville_id: 1)
(1, 2, 'Riz (kg)', 800.00, 0.00, 3000.00, '2026-02-16', 17),          -- besoin 1
(1, 2, 'Eau (L)', 1500.00, 0.00, 1000.00, '2026-02-15', 4),           -- besoin 2
(1, 3, 'Tôle', 120.00, 0.00, 25000.00, '2026-02-16', 23),             -- besoin 3
(1, 3, 'Bâche', 200.00, 0.00, 15000.00, '2026-02-15', 1),             -- besoin 4
(1, 1, 'Argent', 12000000.00, 0.00, 1.00, '2026-02-16', 12),          -- besoin 5
(1, 3, 'groupe', 3.00, 0.00, 6750000.00, '2026-02-15', 16),           -- besoin 6

-- Mananjary (ville_id: 2)
(2, 2, 'Riz (kg)', 500.00, 0.00, 3000.00, '2026-02-15', 9),           -- besoin 7
(2, 2, 'Huile (L)', 120.00, 0.00, 6000.00, '2026-02-16', 25),         -- besoin 8
(2, 3, 'Tôle', 80.00, 0.00, 25000.00, '2026-02-15', 6),               -- besoin 9
(2, 3, 'Clous (kg)', 60.00, 0.00, 8000.00, '2026-02-16', 19),         -- besoin 10
(2, 1, 'Argent', 6000000.00, 0.00, 1.00, '2026-02-15', 3),            -- besoin 11

-- Farafangana (ville_id: 3)
(3, 2, 'Riz (kg)', 600.00, 0.00, 3000.00, '2026-02-16', 21),          -- besoin 12
(3, 2, 'Eau (L)', 1000.00, 0.00, 1000.00, '2026-02-15', 14),          -- besoin 13
(3, 3, 'Bâche', 150.00, 0.00, 15000.00, '2026-02-16', 8),             -- besoin 14
(3, 3, 'Bois', 100.00, 0.00, 10000.00, '2026-02-15', 26),             -- besoin 15
(3, 1, 'Argent', 8000000.00, 0.00, 1.00, '2026-02-16', 10),           -- besoin 16

-- Nosy Be (ville_id: 4)
(4, 2, 'Riz (kg)', 300.00, 0.00, 3000.00, '2026-02-15', 5),           -- besoin 17
(4, 2, 'Haricots', 200.00, 0.00, 4000.00, '2026-02-16', 18),          -- besoin 18
(4, 3, 'Tôle', 40.00, 0.00, 25000.00, '2026-02-15', 2),               -- besoin 19
(4, 3, 'Clous (kg)', 30.00, 0.00, 8000.00, '2026-02-16', 24),         -- besoin 20
(4, 1, 'Argent', 4000000.00, 0.00, 1.00, '2026-02-15', 7),            -- besoin 21

-- Morondava (ville_id: 5)
(5, 2, 'Riz (kg)', 700.00, 0.00, 3000.00, '2026-02-16', 11),          -- besoin 22
(5, 2, 'Eau (L)', 1200.00, 0.00, 1000.00, '2026-02-15', 20),          -- besoin 23
(5, 3, 'Bâche', 180.00, 0.00, 15000.00, '2026-02-16', 15),            -- besoin 24
(5, 3, 'Bois', 150.00, 0.00, 10000.00, '2026-02-15', 22),             -- besoin 25
(5, 1, 'Argent', 10000000.00, 0.00, 1.00, '2026-02-16', 13);          -- besoin 26

-- =====================================================
-- PARAMETRES (frais d'achat par défaut + mode distribution)
-- =====================================================
INSERT INTO parametres (frais_achat_percent, mode_distribution) VALUES (10.00, 'date');

-- =====================================================
-- DONS REÇUS
-- type_id : 1=argent, 2=nature, 3=materiaux
-- Pour argent : montant renseigné, quantite NULL
-- Pour nature/materiaux : quantite renseignée, montant NULL
-- restant = montant ou quantite initialement
-- =====================================================

-- ---------- Dons en ARGENT (type_id=1) ----------
INSERT INTO dons (type_id, designation, montant, quantite, restant, date_don, dispatched) VALUES 
(1, 'Argent', 5000000.00, NULL, 5000000.00, '2026-02-16', FALSE),     -- don 1
(1, 'Argent', 3000000.00, NULL, 3000000.00, '2026-02-16', FALSE),     -- don 2
(1, 'Argent', 4000000.00, NULL, 4000000.00, '2026-02-17', FALSE),     -- don 3
(1, 'Argent', 1500000.00, NULL, 1500000.00, '2026-02-17', FALSE),     -- don 4
(1, 'Argent', 6000000.00, NULL, 6000000.00, '2026-02-17', FALSE),     -- don 5
(1, 'Argent', 20000000.00, NULL, 20000000.00, '2026-02-19', FALSE);   -- don 6

-- ---------- Dons en NATURE (type_id=2) ----------
INSERT INTO dons (type_id, designation, montant, quantite, restant, date_don, dispatched) VALUES 
(2, 'Riz (kg)', NULL, 400.00, 400.00, '2026-02-16', FALSE),           -- don 7
(2, 'Eau (L)', NULL, 600.00, 600.00, '2026-02-16', FALSE),            -- don 8
(2, 'Haricots', NULL, 100.00, 100.00, '2026-02-17', FALSE),           -- don 9
(2, 'Riz (kg)', NULL, 2000.00, 2000.00, '2026-02-18', FALSE),         -- don 10
(2, 'Eau (L)', NULL, 5000.00, 5000.00, '2026-02-18', FALSE),          -- don 11
(2, 'Haricots', NULL, 88.00, 88.00, '2026-02-17', FALSE);             -- don 12

-- ---------- Dons en MATERIAUX (type_id=3) ----------
INSERT INTO dons (type_id, designation, montant, quantite, restant, date_don, dispatched) VALUES 
(3, 'Tôle', NULL, 50.00, 50.00, '2026-02-17', FALSE),                 -- don 13
(3, 'Bâche', NULL, 70.00, 70.00, '2026-02-17', FALSE),                -- don 14
(3, 'Tôle', NULL, 300.00, 300.00, '2026-02-18', FALSE),               -- don 15
(3, 'Bâche', NULL, 500.00, 500.00, '2026-02-19', FALSE);              -- don 16

-- =====================================================
-- STATISTIQUES ATTENDUES APRES IMPORT
-- =====================================================
-- Types            : 3 (argent, nature, materiaux)
-- Régions          : 5
-- Villes           : 5 (Toamasina, Mananjary, Farafangana, Nosy Be, Morondava)
-- Besoins          : 26
-- Dons             : 16 (6 argent + 6 nature + 4 materiaux)
-- Dispatch         : 0
-- Achats           : 0
-- Paramètres       : 1 (frais 10%, mode 'date')
