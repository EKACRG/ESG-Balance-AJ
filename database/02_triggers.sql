USE esgbalance;

-- Implementazione dei trigger
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
    
    DECLARE totale_revisori INT;
    DECLARE totale_giudizi INT;
    DECLARE totale_respinti INT;

    -- Controllo che l'update riguardi effettivamente l'inserimento di un giudizio
    IF NEW.Esito IS NOT NULL THEN
        
        -- Conto quanti revisori totali sono stati assegnati a questo specifico bilancio
        SELECT COUNT(*) INTO totale_revisori
        FROM VALUTA_GIUDIZIO
        WHERE RagioneSociale = NEW.RagioneSociale AND DataCreazione = NEW.DataCreazione;

        -- Conto quanti di questi revisori hanno già emesso il giudizio
        SELECT COUNT(*) INTO totale_giudizi
        FROM VALUTA_GIUDIZIO
        WHERE RagioneSociale = NEW.RagioneSociale AND DataCreazione = NEW.DataCreazione AND Esito IS NOT NULL;

        -- Controllo se TUTTI i revisori hanno votato
        IF totale_revisori = totale_giudizi THEN
            
            -- Conto quanti voti di "respingimento" ci sono
            SELECT COUNT(*) INTO totale_respinti
            FROM VALUTA_GIUDIZIO
            WHERE RagioneSociale = NEW.RagioneSociale AND DataCreazione = NEW.DataCreazione AND Esito = 'respingimento';

            -- Eseguo l'aggiornamento dello stato sul BILANCIO
            IF totale_respinti > 0 THEN
                UPDATE BILANCIO
                SET Stato = 'respinto'
                WHERE RagioneSociale = NEW.RagioneSociale AND DataCreazione = NEW.DataCreazione;
            ELSE
                UPDATE BILANCIO
                SET Stato = 'approvato'
                WHERE RagioneSociale = NEW.RagioneSociale AND DataCreazione = NEW.DataCreazione;
            END IF;

        END IF;
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
    -- Calcolo la nuova affidabilità basata sullo storico
    -- Percentuale di bilanci approvati (con o senza rilievi) rispetto al totale
    SELECT 
        (SUM(CASE WHEN ESITO IN ('approvazione', 'approvazione con rilievi') THEN 1 ELSE 0 END) / COUNT(*)) * 100
    INTO nuovo_indice
    FROM VALUTA_GIUDIZIO
    WHERE Username_Revisore = NEW.Username_Revisore;
    
    UPDATE REVISORE_ESG
    SET 
        NumeroRevisioni = NumeroRevisioni + 1,
        IndiceAffidabilita = nuovo_indice          
    WHERE Username = NEW.Username_Revisore;
END; |
DELIMITER ;