USE esgbalance;

-- Mostrare il Numero di aziende registrate in piattaforma
CREATE OR REPLACE VIEW NUM_AZIENDE AS
SELECT COUNT(*) AS num_aziende
FROM AZIENDA;

-- Mostrare il Numero di revisori ESG registrati in piattaforma
CREATE OR REPLACE VIEW NUM_REVISORI_ESG AS
SELECT COUNT(*) AS num_revisori_esg
FROM REVISORE_ESG;

-- Mostrare lazienda con il valore più alto di affidabilità. Quest’ultima è definita come la
-- percentuale di bilanci approvati dai revisori senza rilievi (esito: “approvazione”)
CREATE OR REPLACE VIEW AZIENDA_PIU_AFFIDABILE AS
WITH totali AS (
	SELECT RagioneSociale, COUNT(*) AS Totale
    FROM VALUTA_GIUDIZIO
    GROUP BY RagioneSociale
),
approvati AS (
	SELECT RagioneSociale, COUNT(*) as Approvati
    FROM VALUTA_GIUDIZIO
    WHERE Esito = 'approvazione'
    GROUP BY RagioneSociale
)
SELECT 
	totali.RagioneSociale, 
    ROUND((approvati.Approvati / totali.Totale) * 100, 2) AS Affidabilita
FROM totali, approvati
WHERE totali.RagioneSociale = approvati.RagioneSociale
ORDER BY Affidabilita DESC
LIMIT 1;
