USE esgbalance;

# Mostrare il Numero di aziende registrate in piattaforma
CREATE OR REPLACE VIEW NUM_AZIENDE AS
SELECT COUNT(*) AS num_aziende
FROM AZIENDA;

# Mostrare il Numero di revisori ESG registrati in piattaforma
CREATE OR REPLACE VIEW NUM_REVISORI_ESG AS
SELECT COUNT(*) AS num_revisori_esg
FROM REVISORE_ESG;
