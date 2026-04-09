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