create database if not exists bngrc_db;
use bngrc_db;


CREATE TABLE IF NOT EXISTS regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);


CREATE TABLE IF NOT EXISTS villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id)
);

CREATE TABLE IF NOT EXISTS besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_besoin VARCHAR(50) NOT NULL,
    quantite_demandee DECIMAL(15, 2) NOT NULL,
    quantite_recue DECIMAL(15, 2) DEFAULT 0,
    prix_unitaire DECIMAL(15, 2) NOT NULL,
    date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
);


CREATE TABLE IF NOT EXISTS dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    quantite DECIMAL(15, 2) NULL,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dispatched BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS dispatch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee DECIMAL(15, 2) NOT NULL,
    date_dispatch TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE
);


INSERT INTO type_besoins (id, nom, description) VALUES 
(1, 'Nature', 'Produits alimentaires et de première nécessité'),
(2, 'Matériaux', 'Matériaux de construction et de réparation'),
(3, 'Argent', 'Dons en espèces');


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


INSERT INTO villes (nom, region_id, population_sinistree) VALUES 
('Antananarivo', 1, 500),
('Antsirabe', 2, 300),
('Miarinarivo', 3, 150),
('Tsiroanomandidy', 4, 200),
('Antsohihy', 5, 180),
('Mahajanga', 6, 400),
('Maevatanana', 7, 120),
('Maintirano', 8, 90),
('Ambatondrazaka', 9, 250),
('Toamasina', 10, 350);


INSERT INTO articles (nom, type_besoin_id, prix_unitaire, unite) VALUES 
('Riz', 1, 2500, 'kg'),
('Huile', 1, 8000, 'litre'),
('Sucre', 1, 4000, 'kg'),
('Sel', 1, 1500, 'kg'),
('Eau potable', 1, 500, 'litre'),
('Savon', 1, 2000, 'pièce'),
('Couverture', 1, 25000, 'pièce');


INSERT INTO articles (nom, type_besoin_id, prix_unitaire, unite) VALUES 
('Tôle', 2, 45000, 'feuille'),
('Clou', 2, 100, 'pièce'),
('Bois', 2, 15000, 'planche'),
('Ciment', 2, 35000, 'sac'),
('Bâche', 2, 20000, 'pièce'),
('Corde', 2, 5000, 'mètre');


INSERT INTO articles (nom, type_besoin_id, prix_unitaire, unite) VALUES 
('Don en argent', 3, 1, 'Ariary');