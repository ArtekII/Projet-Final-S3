CREATE DATABASE IF NOT EXISTS bngrc_db;
USE bngrc_db;

-- ================= REGIONS =================
CREATE TABLE regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- ================= VILLES =================
CREATE TABLE villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id)
);

-- ================= BESOINS =================
CREATE TABLE besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_id INT NOT NULL,
    designation VARCHAR(100) NULL,
    quantite_demandee DECIMAL(15,2) NOT NULL,
    quantite_recue DECIMAL(15,2) DEFAULT 0,
    prix_unitaire DECIMAL(15,2) NOT NULL,
    date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (type_id) REFERENCES type(id)
);

-- ================= DONS =================
CREATE TABLE dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL, -- argent / nature / materiaux
    designation VARCHAR(100) NULL, -- nom spécifique du don (riz, tente, etc.)
    montant DECIMAL(15,2) NULL,
    quantite DECIMAL(15,2) NULL,
    restant DECIMAL(15,2) NOT NULL,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dispatched BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (type_id) REFERENCES type(id)
);

-- ================= DISPATCH =================
CREATE TABLE dispatch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee DECIMAL(15,2) NOT NULL,
    date_dispatch TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);
