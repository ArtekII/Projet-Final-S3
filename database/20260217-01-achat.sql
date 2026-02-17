CREATE TABLE achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    ville_id INT NOT NULL,
    besoin_id INT NOT NULL,
    montant_achat DECIMAL(15,2) NOT NULL,
    frais_percent DECIMAL(5,2) NOT NULL,
    montant_total DECIMAL(15,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id),
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);



-- ================= PARAMETRES =================
CREATE TABLE parametres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frais_achat_percent DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    mode_distribution VARCHAR(50) DEFAULT 'date'

);