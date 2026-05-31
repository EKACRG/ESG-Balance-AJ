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
    IN Password VARCHAR(255),
    IN CodiceFiscale CHAR(16),
    IN DataNascita DATE,
    IN LuogoNascita VARCHAR(30),
    IN IndirizzoEmail VARCHAR(30)
)
BEGIN
    -- Controllo username già in uso
    IF EXISTS (SELECT 1 FROM UTENTI WHERE UsernameUtente = UTENTI.Username) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Username esistente';
    END IF;

    -- Controllo CF esattametne 16 caratteri
    IF CHAR_LENGTH(CodiceFiscale) != 16 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Codice fiscale deve essere esattamente di 16 caratteri';
    END IF;

    -- Controllo CF già in uso
    IF EXISTS (SELECT 1 FROM UTENTI WHERE CodiceFiscale = UTENTI.CF) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Codice fiscale esistente';
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
    IF LOWER(CurriculumVitae) NOT LIKE '%.pdf' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Curriculum Vitae deve essere in formato PDF (.pdf)';
    END IF;

    INSERT INTO RESPONSABILE_AZIENDALE (Username, CurriculumVitae) VALUES (UsernameUtente, CurriculumVitae);
END |
DELIMITER ;

-- Inserisci email aggiuntive
DROP PROCEDURE IF EXISTS InserisciEmail;
DELIMITER |
CREATE PROCEDURE InserisciEmail(IN UsernameUtente VARCHAR(30), IN IndirizzoEmail VARCHAR(30))
BEGIN
    IF EXISTS (SELECT 1 FROM EMAIL WHERE IndirizzoEmail = EMAIL.IndirizzoEmail) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Email esistente';
    END IF;

    INSERT INTO EMAIL (Username, IndirizzoEmail) VALUES (UsernameUtente, IndirizzoEmail);
END |
DELIMITER ;

-- Popolamento della lista degli indicatori ESG
DROP PROCEDURE IF EXISTS InserisciIndicatoreESG;
DELIMITER |
CREATE PROCEDURE InserisciIndicatoreESG(
    IN Indicatore VARCHAR(100),
    IN UsernameAmministratore VARCHAR(30),
    IN Immagine VARCHAR(255),
    IN Rilevanza INT,
    IN TipoIndicatore VARCHAR(50),
    IN CodiceNormativa VARCHAR(100),
    IN AmbitoSociale VARCHAR(100),
    IN FrequenzaRilevazione VARCHAR(50)
)
BEGIN
    -- Controllo nome indicatore già in uso
    IF EXISTS (SELECT 1 FROM INDICATORE_ESG WHERE Indicatore = INDICATORE_ESG.NomeIndicatore) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Indicatore ESG esistente';
    END IF;

    -- Controllo coerenza campi per tipo Ambiente
    IF TipoIndicatore = 'Ambientale' AND CodiceNormativa IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: CodiceNormativa è obbligatorio per indicatori ambientali';
    END IF;

    -- Controllo coerenza campi per tipo Sociale
    IF TipoIndicatore = 'Sociale' AND (AmbitoSociale IS NULL OR FrequenzaRilevazione IS NULL) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: AmbitoSociale e FrequenzaRilevazione sono obbligatori per indicatori sociali';
    END IF;

    INSERT INTO INDICATORE_ESG (NomeIndicatore, Username_Amministratore, Immagine, Rilevanza, TipoIndicatore, CodiceNormativa, AmbitoSociale, FrequenzaRilevazione) VALUES (Indicatore, UsernameAmministratore, Immagine, Rilevanza, TipoIndicatore, CodiceNormativa, AmbitoSociale, FrequenzaRilevazione);
END |
DELIMITER ;

-- Creazione del “template” di bilancio di esercizio
DROP PROCEDURE IF EXISTS InserisciVoceContabile;
DELIMITER |
CREATE PROCEDURE InserisciVoceContabile(IN VoceContabile VARCHAR(30), IN UsernameAmministratore VARCHAR(30), IN Descrizione TEXT)
BEGIN
    -- Controllo nome voce contabile già in uso
    IF EXISTS (SELECT 1 FROM VOCE_CONTABILE WHERE VoceContabile = VOCE_CONTABILE.NomeVoceContabile) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Nome voce contabile esistente'; 
    END IF;

    INSERT INTO VOCE_CONTABILE (NomeVoceContabile, Username_Amministratore, Descrizione) VALUES (VoceContabile, UsernameAmministratore, Descrizione);
END |
DELIMITER ;

-- Associazione di revisore ESG ad un bilancio aziendale
DROP PROCEDURE IF EXISTS AssociaRevisore;
DELIMITER |
CREATE PROCEDURE AssociaRevisore( IN P_Username_Revisore VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE)
BEGIN
    IF EXISTS (
        SELECT 1 
        FROM VALUTA_GIUDIZIO
        WHERE Username_Revisore = P_Username_Revisore AND RagioneSociale = P_RagioneSociale AND DataCreazione = P_DataCreazione
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: associazione revisore-bilancio esistente';
    END IF;

    INSERT INTO VALUTA_GIUDIZIO (Username_Revisore, RagioneSociale, DataCreazione)
    VALUES (P_Username_Revisore, P_RagioneSociale, P_DataCreazione);
END |
DELIMITER ;


-- Inserimento da parte dei revisori le proprie competenze
DROP PROCEDURE IF EXISTS InserisciCompetenze;
DELIMITER |
CREATE PROCEDURE InserisciCompetenze ( IN P_Username_Revisore VARCHAR(30), IN P_NomeCompetenza VARCHAR(100),IN P_Livello INT)
BEGIN
    -- Validazione livello
    IF P_Livello < 0 OR P_Livello > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Livello competenza non valido (deve essere tra 0 e 5)';
    END IF;

    -- Salva la competenza nella tabella principale (ignora se esiste già)
    INSERT IGNORE INTO COMPETENZA (NomeCompetenza) VALUES (P_NomeCompetenza);
    
    INSERT INTO POSSIEDE (Username_Revisore, NomeCompetenza, Livello)
    VALUES (P_Username_Revisore,P_NomeCompetenza,P_Livello);
END |
DELIMITER ;


-- Inserimento di note sulle singole voci di bilancio
DROP PROCEDURE IF EXISTS InserisciAnnotazione;
DELIMITER |
CREATE PROCEDURE InserisciAnnotazione (IN  P_Username_Revisore VARCHAR(30), IN P_NomeVoceBilancio VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE, IN P_Data DATE, IN P_Testo TEXT)
BEGIN
    -- Validazione: nota già inserita
    IF EXISTS (SELECT 1 FROM ANNOTAZIONE WHERE Username_Revisore = P_Username_Revisore AND NomeVoceBilancio = P_NomeVoceBilancio AND RagioneSociale = P_RagioneSociale AND DataCreazione = P_DataCreazione AND Data = P_Data) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Errore: Nota presente per questa voce di bilancio in questa data';
    END IF;

    INSERT INTO ANNOTAZIONE (Username_Revisore, NomeVoceBilancio, RagioneSociale, DataCreazione, Data, Testo)
    VALUES (P_Username_Revisore,P_NomeVoceBilancio,P_RagioneSociale, P_DataCreazione, P_Data,P_Testo);
END |
DELIMITER ;

-- Inserimento del giudizio complessivo
DROP PROCEDURE IF EXISTS InserisciGiudizio;
DELIMITER |
CREATE PROCEDURE InserisciGiudizio (IN  P_Username_Revisore VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE, IN P_Esito VARCHAR(30), IN P_CampoRilievi TEXT, IN P_Data DATE)
BEGIN
    IF P_Esito IN ('approvazione', 'approvazione con rilievi', 'respingimento') THEN
        UPDATE VALUTA_GIUDIZIO 
        SET Esito = P_Esito,
            CampoRilievi = P_CampoRilievi,
            Data = P_Data
        WHERE Username_Revisore = P_Username_Revisore AND
            RagioneSociale = P_RagioneSociale AND
            DataCreazione = P_DataCreazione;
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
	-- Validazione bilancio non esiste già
	IF EXISTS (SELECT 1 FROM BILANCIO WHERE RagioneSociale = P_RagioneSociale AND DataCreazione = P_DataCreazione) THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Errore: Bilancio esistente';
	END IF;

	INSERT INTO BILANCIO (RagioneSociale, DataCreazione, Stato)
    VALUES (P_RagioneSociale, P_DataCreazione, 'bozza');
END |
DELIMITER ;

DROP PROCEDURE IF EXISTS PopolaVoceBilancio;
DELIMITER |
CREATE PROCEDURE PopolaVoceBilancio( IN P_NomeVoce VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE,IN P_ValoreNumerico DECIMAL(12,2))
BEGIN
	-- Validazione voce non già inserita per questo bilancio
	IF EXISTS (SELECT 1 FROM VOCE_BILANCIO WHERE NomeVoceBilancio = P_NomeVoce AND RagioneSociale = P_RagioneSociale AND DataCreazione = P_DataCreazione) THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Errore: Voce esistente per questo bilancio';
	END IF;

	INSERT INTO VOCE_BILANCIO (NomeVoceBilancio, RagioneSociale, DataCreazione, ValoreNumerico) 
    VALUES ( P_NomeVoce, P_RagioneSociale ,  P_DataCreazione , P_ValoreNumerico);
END |
DELIMITER ; 

-- Inserimento dei valori degli indicatori ESG connessi alle singole voci di bilancio
DROP PROCEDURE IF EXISTS InserisciValoreESG;
DELIMITER |
CREATE PROCEDURE InserisciValoreESG( IN P_NomeIndicatore VARCHAR(100), IN P_NomeVoceBilancio VARCHAR(30), IN P_RagioneSociale VARCHAR(100), IN P_DataCreazione DATE,IN P_Valore DECIMAL(15,2),IN P_Fonte VARCHAR(255),IN P_Data DATE)
BEGIN
	-- Validazione valore non negativo (opzionale, ma logico per un indicatore)
	IF P_Valore < 0 THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Errore: Il valore dell''indicatore non può essere negativo';
	END IF;

	INSERT INTO MEMORIZZA (NomeIndicatore, NomeVoceBilancio, RagioneSociale, DataCreazione, Valore, Fonte, Data)
    VALUES (P_NomeIndicatore, P_NomeVoceBilancio, P_RagioneSociale, P_DataCreazione, P_Valore, P_Fonte, P_Data);    
END |
DELIMITER ;
