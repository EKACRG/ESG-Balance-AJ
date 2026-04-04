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
WITH TOTALI AS (
	SELECT RagioneSociale, COUNT(*) AS totale
    FROM VALUTA_GIUDIZIO
    GROUP BY RagioneSociale
),
APPROVATI AS (
	SELECT RagioneSociale, COUNT(*) AS approvati
    FROM VALUTA_GIUDIZIO
    WHERE Esito = 'approvazione'
    GROUP BY RagioneSociale
)
SELECT 
	TOTALI.RagioneSociale, 
    ROUND((APPROVATI.approvati / TOTALI.totale) * 100, 2) AS affidabilita
FROM TOTALI, APPROVATI
WHERE TOTALI.RagioneSociale = APPROVATI.RagioneSociale
ORDER BY affidabilita DESC
LIMIT 1;

-- Classifica dei bilanci aziendali, ordinati in base al numero totale 
-- di indicatori ESG connessi alle singole voci contabili
CREATE OR REPLACE VIEW CLASSIFICA_BILANCI AS
SELECT RagioneSociale, DataCreazione, COUNT(*) AS tot_indicatori
FROM MEMORIZZA
GROUP BY RagioneSociale, DataCreazione
ORDER BY tot_indicatori DESC;
