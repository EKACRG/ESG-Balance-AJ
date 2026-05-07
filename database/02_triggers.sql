USE esgbalance;

# Implementazione dei trigger
DELIMITER |
CREATE TRIGGER CambioStato
AFTER INSERT ON VALUTA_GIUDIZIO
FOR EACH ROW
BEGIN
	UPDATE BILANCIO
    SET Stato = 'In revisione'
    WHERE (RagioneSociale = NEW.RagioneSociale) AND (DataCreazione = NEW.DataCreazione);
END |
DELIMITER ;

DELIMITER |
CREATE TRIGGER ValutazioneFinale
AFTER UPDATE ON VALUTA_GIUDIZIO
FOR EACH ROW
BEGIN
	IF NEW.Esito = 'respingimento' THEN
    UPDATE BILANCIO
    SET Stato = 'respinto'
    WHERE (RagioneSociale = NEW.RagioneSociale) AND (DataCreazione = NEW.DataCreazione);
    ELSEIF NEW.Esito = 'approvazione' OR  NEW.Esito = 'approvazione con rilievi' THEN
    UPDATE BILANCIO
    SET Stato = 'approvato'
    WHERE (RagioneSociale = NEW.RagioneSociale) AND (DataCreazione = NEW.DataCreazione) AND (Stato != 'respinto');
    END IF;
END |
DELIMITER ;

# Implementazione calcolo dei bilanci
DELIMITER |
CREATE TRIGGER CalcoloBilancio
AFTER INSERT ON BILANCIO
FOR EACH ROW
BEGIN
	UPDATE AZIENDA
    SET NumBilanci = NumBilanci + 1
    WHERE (RagioneSociale = NEW.RagioneSociale);
END |
DELIMITER ;

DELIMITER |
CREATE TRIGGER AggiornaStatisticheRevisore
AFTER INSERT ON VALUTA_GIUDIZIO
FOR EACH ROW
BEGIN
    DECLARE nuovo_indice DECIMAL(5,2);
    -- 1. Calcolo la nuova affidabilità basata sullo storico (Regola di Derivazione 3)
    -- In questo esempio: Percentuale di bilanci approvati (con o senza rilievi) rispetto al totale
    SELECT 
        (SUM(CASE WHEN ESITO IN ('approvazione', 'approvazione con rilievi') THEN 1 ELSE 0 END) / COUNT(*)) * 100
    INTO nuovo_indice
    FROM VALUTA_GIUDIZIO
    WHERE Username_Revisore = NEW.Username_Revisore;

    -- 2. Aggiorno la tabella REVISORE_ESG (Regola di Derivazione 2 e 3)
    UPDATE REVISORE_ESG
    SET 
        NumeroRevisioni = NumeroRevisioni + 1,
        IndiceAffidabilita = nuovo_indice          
    WHERE Username = NEW.Username_Revisore;
END; |
DELIMITER ;