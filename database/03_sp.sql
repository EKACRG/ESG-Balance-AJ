-- Parte anouar

-- Inserimento da parte dei revisori le proprie competenze
DELIMITER |
CREATE PROCEDURE InserimentoCompetenze ( IN P_Username_Revisore VARCHAR(30), IN P_NomeCompetenza VARCHAR(100),IN P_Livello INT)
BEGIN
		IF p_Livello >= 0 AND p_Livello <= 5 THEN
        INSERT INTO POSSIEDE (Username_Revisore, NomeCompetenza, Livello)
        VALUES (P_Username_Revisore,P_NomeCompetenza,P_Livello);
        ELSE
			SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Errore: l livello di competenza deve essere compreso tra 0 e 5';
		END IF;
END |
DELIMITER ;

DELIMITER |
CREATE PROCEDURE InserimentoAnnotazione (IN  P_Username_Revisore VARCHAR(30), IN P_NomeVoceBilancio VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE, IN P_Data DATE, IN P_Testo TEXT)
BEGIN
        INSERT INTO POSSIEDE (Username_Revisore, NomeVoceBilancio, RagioneSociale, DataCreazione, Data, Testo)
        VALUES (P_Username_Revisore,P_NomeVoceBilancio,P_RagioneSociale, P_DataCreazione, P_Data,P_Testo);
END |
DELIMITER ;

DELIMITER |
CREATE PROCEDURE InserimentoGiudizio (IN  P_Username_Revisore VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE, IN P_Esito VARCHAR(30), IN P_CampoRilievi TEXT, IN P_Data DATE)
BEGIN
		IF P_Esito IN ('approvazione', 'approvazione con rilievi', 'respingimento') THEN
			INSERT INTO VALUTA_GIUDIZIO (Username_Revisore, RagioneSociale, DataCreazione, Esito, CampoRilievi, Data)
			VALUES (P_Username_Revisore,P_RagioneSociale, P_DataCreazione, P_Esito,P_CampoRilievi,P_Data);
		ELSE
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Errore: Esito non valido. Deve essere: approvazione, approvazione con rilievi, o respingimento';
    END IF;

END |
DELIMITER ;