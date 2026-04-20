USE esgbalance;

-- Login utente sulla piattaforma
DROP PROCEDURE IF EXISTS LoginUtente;
DELIMITER |
CREATE PROCEDURE LoginUtente(IN UsernameUtente VARCHAR(30), IN PasswordUtente VARCHAR(30))
BEGIN
	SELECT Username
    FROM UTENTI
    WHERE UsernameUtente = UTENTI.Username AND PasswordUtente = UTENTI.Password;
END |
DELIMITER ; 

-- Registrazione Utente (campi comuni a tutti i tipi di utenti)
DROP PROCEDURE IF EXISTS RegistraUtente;
DELIMITER |
CREATE PROCEDURE RegistraUtente(
    IN UsernameUtente VARCHAR(30),
    IN Password VARCHAR(30),
    IN CodiceFiscale CHAR(16),
    IN DataNascita DATE,
    IN LuogoNascita VARCHAR(30),
    IN IndirizzoEmail VARCHAR(30)
)
BEGIN
    -- Controllo username già in uso
    IF EXISTS (SELECT 1 FROM UTENTI WHERE UsernameUtente = UTENTI.Username) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Username già in uso';
    END IF;

    -- Controllo CF esattametne 16 caratteri
    IF CHAR_LENGTH(CodiceFiscale) != 16 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Codice Fiscale deve essere esattamente di 16 caratteri';
    END IF;

    -- Controllo CF già in uso
    IF EXISTS (SELECT 1 FROM UTENTI WHERE CodiceFiscale = UTENTI.CF) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Codice Fiscale già in uso';
    END IF;

    INSERT INTO UTENTI (Username, Password, CF, DataNascita, LuogoNascita) VALUES (UsernameUtente, Password, CodiceFiscale, DataNascita, LuogoNascita);
    INSERT INTO EMAIL (Username, IndirizzoEmail) VALUES (UsernameUtente, IndirizzoEmail);
END |
DELIMITER ;

-- Registrazione Amministratore
DROP PROCEDURE IF EXISTS RegistraAmministratore;
DELIMITER |
CREATE PROCEDURE RegistraAmministratore(IN UsernameUtente VARCHAR(30))
BEGIN
    INSERT INTO AMMINISTRATORE (Username) VALUES (UsernameUtente);
END |
DELIMITER ;

-- Registrazione Revisore
DROP PROCEDURE IF EXISTS RegistraRevisore;
DELIMITER |
CREATE PROCEDURE RegistraRevisore(IN UsernameUtente VARCHAR(30))
BEGIN
    INSERT INTO REVISORE_ESG (Username, NumeroRevisioni, IndiceAffidabilita) VALUES (UsernameUtente, 0, 0.00);
END |
DELIMITER ;

-- Registra Responsabile
DROP PROCEDURE IF EXISTS RegistraResponsabile;
DELIMITER |
CREATE PROCEDURE RegistraResponsabile(IN UsernameUtente VARCHAR(30), IN CurriculumVitae VARCHAR(255))
BEGIN
    IF CurriculumVitae NOT LIKE '%.pdf' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: CurriculumVitae deve essere in formato PDF (.pdf)';
    END IF;

    INSERT INTO RESPONSABILE_AZIENDALE (Username, CurriculumVitae) VALUES (UsernameUtente, CurriculumVitae);
END |
DELIMITER ;

-- Inserimento da parte dei revisori le proprie competenze
DROP PROCEDURE IF EXISTS InserimentoCompetenze;
DELIMITER |
CREATE PROCEDURE InserimentoCompetenze ( IN P_Username_Revisore VARCHAR(30), IN P_NomeCompetenza VARCHAR(100),IN P_Livello INT)
BEGIN
		IF p_Livello >= 0 AND p_Livello <= 5 THEN
        INSERT INTO POSSIEDE (Username_Revisore, NomeCompetenza, Livello)
        VALUES (P_Username_Revisore,P_NomeCompetenza,P_Livello);
        ELSE
			SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Errore: il livello di competenza deve essere compreso tra 0 e 5';
		END IF;
END |
DELIMITER ;
-- Inserimento di note sulle singole voci di bilancio
DROP PROCEDURE IF EXISTS InserimentoAnnotazione;
DELIMITER |
CREATE PROCEDURE InserimentoAnnotazione (IN  P_Username_Revisore VARCHAR(30), IN P_NomeVoceBilancio VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE, IN P_Data DATE, IN P_Testo TEXT)
BEGIN
        INSERT INTO ANNOTAZIONE (Username_Revisore, NomeVoceBilancio, RagioneSociale, DataCreazione, Data, Testo)
        VALUES (P_Username_Revisore,P_NomeVoceBilancio,P_RagioneSociale, P_DataCreazione, P_Data,P_Testo);
END |
DELIMITER ;

-- Inserimento del giudizio complessivo
DROP PROCEDURE IF EXISTS InserimentoGiudizio;
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

-- Registrazione di una nuova azienda
DROP PROCEDURE IF EXISTS RegistraAzienda;
DELIMITER |
CREATE PROCEDURE RegistraAzienda( IN P_Username_Responsabile VARCHAR(30),IN P_RagioneSociale VARCHAR(100),IN p_Nome VARCHAR(30),IN P_PartitaIVA CHAR(11),IN P_Settore VARCHAR(100),IN P_NumDipendenti INT,IN P_Logo VARCHAR(50))
BEGIN
	IF p_logo LIKE '%.jpg' OR p_logo LIKE '%.jpeg' OR p_logo LIKE '%.png' THEN
		INSERT INTO AZIENDA (Username_Responsabile, RagioneSociale, Nome, PartitaIVA, Settore, NumDipendenti, Logo) 
        VALUES ( P_Username_Responsabile , P_RagioneSociale , p_Nome , P_PartitaIVA , P_Settore , P_NumDipendenti , P_Logo);
	ELSE
		SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Errore: Il logo deve essere un file immagine (.jpg, .jpeg, .png)';
    END IF;
END |
DELIMITER ;

-- Creazione e popolamento di un nuovo bilancio di esercizio
DROP PROCEDURE IF EXISTS CreaBilancio;
DELIMITER |
CREATE PROCEDURE CreaBilancio( IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE)
BEGIN
	INSERT INTO BILANCIO (RagioneSociale, DataCreazione, Stato)
    VALUES (P_RagioneSociale, P_DataCreazione, 'bozza');
END |
DELIMITER ;

DROP PROCEDURE IF EXISTS PopolaVoceBilancio;
DELIMITER |
CREATE PROCEDURE PopolaVoceBilancio( IN P_NomeVoce VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE,IN P_ValoreNumerico DECIMAL(12,2))
BEGIN
	INSERT INTO VOCE_BILANCIO (NomeVoceBilancio, RagioneSociale, DataCreazione, ValoreNumerico) 
    VALUES ( P_NomeVoce, P_RagioneSociale ,  P_DataCreazione , P_ValoreNumerico);
END |
DELIMITER ; 

-- Inserimento dei valori degli indicatori ESG connessi alle singole voci di bilancio
DROP PROCEDURE IF EXISTS InserisciValoreESG;
DELIMITER |
CREATE PROCEDURE InserisciValoreESG( IN P_NomeIndicatore VARCHAR(100), IN P_NomeVoceBilancio VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE,IN P_Valore DECIMAL(15,2),IN P_Fonte VARCHAR(255),IN P_Data DATE)
BEGIN
	INSERT INTO MEMORIZZA (NomeIndicatore, NomeVoceBilancio, RagioneSociale, DataCreazione, Valore, Fonte, Data)
    VALUES (P_NomeIndicatore, P_NomeVoceBilancio, P_RagioneSociale, P_DataCreazione, P_Valore, P_Fonte, P_Data);    
END |
DELIMITER ;