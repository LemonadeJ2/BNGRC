-- Tables pour conserver les résultats de simulation sans modifier les données existantes
CREATE TABLE IF NOT EXISTS simulation_run (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mode VARCHAR(32) NOT NULL,
    created_at DATETIME NOT NULL,
    stock_initial_json TEXT,
    stock_restant_json TEXT,
    total_attribue DECIMAL(12,2) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS simulation_allocation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    run_id INT NOT NULL,
    id_ville INT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    id_besoin INT NOT NULL,
    besoin VARCHAR(100) NOT NULL,
    demande DECIMAL(12,2) NOT NULL,
    attribue DECIMAL(12,2) NOT NULL,
    restant DECIMAL(12,2) NOT NULL,
    date_demande DATE NULL,
    FOREIGN KEY (run_id) REFERENCES simulation_run(id) ON DELETE CASCADE
);
