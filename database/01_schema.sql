USE esgbalance;

-- Creazione delle tabelle

CREATE TABLE IF NOT EXISTS UTENTI (
  Username VARCHAR(30) PRIMARY KEY,
  Password VARCHAR(255) NOT NULL,
  CF CHAR(16) UNIQUE NOT NULL,
  DataNascita DATE NOT NULL,
  LuogoNascita VARCHAR(30)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS COMPETENZA (
 NomeCompetenza VARCHAR(100) PRIMARY KEY
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS AMMINISTRATORE (
    Username VARCHAR(30) PRIMARY KEY,
    FOREIGN KEY (Username) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS REVISORE_ESG (
    Username VARCHAR(30) PRIMARY KEY,
    NumeroRevisioni INT DEFAULT 0,
    IndiceAffidabilita DECIMAL(5,2) DEFAULT 0.00,
    FOREIGN KEY (Username) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS RESPONSABILE_AZIENDALE (
    Username VARCHAR(30) PRIMARY KEY,
    CurriculumVitae VARCHAR(255) NOT NULL, 
    FOREIGN KEY (Username) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS  VOCE_CONTABILE (
 NomeVoceContabile VARCHAR(30) PRIMARY KEY,
 Username_Amministratore VARCHAR(30),
 Descrizione TEXT NOT NULL,
 FOREIGN KEY (Username_Amministratore) REFERENCES AMMINISTRATORE(Username) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS INDICATORE_ESG (
	NomeIndicatore VARCHAR(100) PRIMARY KEY,
    Username_Amministratore VARCHAR(30),
    Immagine VARCHAR(255) NOT NULL,
    Rilevanza INT NOT NULL CHECK (Rilevanza >= 0 AND Rilevanza <= 10),
    TipoIndicatore VARCHAR(50) NOT NULL,
    CodiceNormativa VARCHAR(100),
    AmbitoSociale VARCHAR(100),
    FrequenzaRilevazione VARCHAR(50),
	FOREIGN KEY (Username_Amministratore) REFERENCES AMMINISTRATORE(Username) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS EMAIL (
 Username VARCHAR(30),
 IndirizzoEmail VARCHAR(30),
 PRIMARY KEY (Username, IndirizzoEmail),
 FOREIGN KEY (Username) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS POSSIEDE (
    Username_Revisore VARCHAR(30),
    NomeCompetenza VARCHAR(100),
    Livello INT CHECK (Livello >= 0 AND Livello <= 5), 
    PRIMARY KEY (Username_Revisore, NomeCompetenza),
    FOREIGN KEY (Username_Revisore) REFERENCES REVISORE_ESG(Username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (NomeCompetenza) REFERENCES COMPETENZA(NomeCompetenza) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS AZIENDA (
    RagioneSociale VARCHAR(100) PRIMARY KEY,
    Nome VARCHAR(30) NOT NULL,
    PartitaIVA CHAR(11) UNIQUE NOT NULL,
    Settore VARCHAR(100) NOT NULL,
    NumDipendenti INT NOT NULL,
    Logo VARCHAR(50),
    NumBilanci INT DEFAULT 0,
    Username_Responsabile VARCHAR(30) NOT NULL,
    FOREIGN KEY (Username_Responsabile) REFERENCES RESPONSABILE_AZIENDALE(Username) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS BILANCIO (
    RagioneSociale VARCHAR(100),
    DataCreazione DATE,
    Stato VARCHAR(50) NOT NULL, 
    PRIMARY KEY (RagioneSociale, DataCreazione),
    FOREIGN KEY (RagioneSociale) REFERENCES AZIENDA(RagioneSociale) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS VALUTA_GIUDIZIO (
    Username_Revisore VARCHAR(30),
    RagioneSociale VARCHAR(100),
    DataCreazione DATE,
    Esito VARCHAR(30) NULL, 
    CampoRilievi TEXT NULL,
    Data DATE NULL,
    PRIMARY KEY (Username_Revisore, RagioneSociale, DataCreazione),
    FOREIGN KEY (Username_Revisore) REFERENCES REVISORE_ESG(Username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (RagioneSociale, DataCreazione) REFERENCES BILANCIO(RagioneSociale, DataCreazione) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS VOCE_BILANCIO (
    NomeVoceBilancio VARCHAR(30),
    RagioneSociale VARCHAR(100),
    DataCreazione DATE,
    ValoreNumerico DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (NomeVoceBilancio, RagioneSociale, DataCreazione),
    FOREIGN KEY (NomeVoceBilancio) REFERENCES VOCE_CONTABILE(NomeVoceContabile) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (RagioneSociale, DataCreazione) REFERENCES BILANCIO(RagioneSociale, DataCreazione) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ANNOTAZIONE (
    Username_Revisore VARCHAR(30),
    NomeVoceBilancio VARCHAR(30),
    RagioneSociale VARCHAR(100),
    DataCreazione DATE,
    Data DATE NOT NULL,
    Testo TEXT NOT NULL,
    PRIMARY KEY (Username_Revisore, NomeVoceBilancio, RagioneSociale, DataCreazione),
    FOREIGN KEY (Username_Revisore) REFERENCES REVISORE_ESG(Username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (NomeVoceBilancio, RagioneSociale, DataCreazione) REFERENCES VOCE_BILANCIO(NomeVoceBilancio, RagioneSociale, DataCreazione) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS MEMORIZZA (
    NomeIndicatore VARCHAR(100),
    NomeVoceBilancio VARCHAR(30),
    RagioneSociale VARCHAR(100),
    DataCreazione DATE,
    Valore DECIMAL(15,2) NOT NULL,
    Fonte VARCHAR(255),
    Data DATE NOT NULL,
    PRIMARY KEY (NomeIndicatore, NomeVoceBilancio, RagioneSociale, DataCreazione),
    FOREIGN KEY (NomeIndicatore) REFERENCES INDICATORE_ESG(NomeIndicatore) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (NomeVoceBilancio, RagioneSociale, DataCreazione) REFERENCES VOCE_BILANCIO(NomeVoceBilancio, RagioneSociale, DataCreazione) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO UTENTI (Username, Password, CF, DataNascita, LuogoNascita)
VALUES ('resp1', 'pass123', 'RSSMRA80A01H501X', '1985-05-10', 'Milano');

INSERT INTO RESPONSABILE_AZIENDALE (Username, CurriculumVitae)
VALUES ('resp1', 'CV esempio');

INSERT INTO AZIENDA (RagioneSociale, Nome, PartitaIVA, Settore, NumDipendenti, Logo, NumBilanci, Username_Responsabile)
VALUES ('ESG SRL', 'ESG', '12345678901', 'Finanza', 50, NULL, 0, 'resp1');

INSERT INTO BILANCIO (RagioneSociale, DataCreazione, Stato)
VALUES ('ESG SRL', '2026-01-01', 'In revisione');

INSERT INTO UTENTI (Username, Password, CF, DataNascita, LuogoNascita)
VALUES ('revisore1', 'pass123', 'RSSMRA80A01H501Y', '1990-01-01', 'Roma');

INSERT INTO REVISORE_ESG (Username, NumeroRevisioni, IndiceAffidabilita)
VALUES ('revisore1', 0, 0.00);

INSERT INTO VALUTA_GIUDIZIO (Username_Revisore, RagioneSociale, DataCreazione)
VALUES ('revisore1', 'ESG SRL', '2026-01-01');

SELECT * FROM VALUTA_GIUDIZIO;

-- Creazione amministratore per il template contabile
INSERT INTO UTENTI (Username, Password, CF, DataNascita, LuogoNascita)
VALUES ('admin1', 'pass123', 'RSSMRA80A01H501Z', '1980-01-01', 'Napoli');

INSERT INTO AMMINISTRATORE (Username)
VALUES ('admin1');

-- Creazione voce contabile del template
INSERT INTO VOCE_CONTABILE (
    NomeVoceContabile,
    Username_Amministratore,
    Descrizione
)
VALUES (
    'Ricavi',
    'admin1',
    'Ricavi annuali aziendali'
);

-- Popolamento della voce nel bilancio
INSERT INTO VOCE_BILANCIO (NomeVoceBilancio, RagioneSociale, DataCreazione, ValoreNumerico)
VALUES ('Ricavi', 'ESG SRL', '2026-01-01', 150000.00);

-- Verifica
SELECT * FROM UTENTI;
