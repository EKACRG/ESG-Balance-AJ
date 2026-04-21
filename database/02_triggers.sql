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
AFTER INSERT ON VALUTA_GIUDIZIO
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