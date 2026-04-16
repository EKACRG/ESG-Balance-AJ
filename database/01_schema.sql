USE esgbalance;

-- Creazione delle tabelle

CREATE TABLE IF NOT EXISTS UTENTI (
  Username VARCHAR(30) PRIMARY KEY,
  Password VARCHAR(30) NOT NULL,
  CF CHAR(16) UNIQUE NOT NULL,
  DataNascita DATE NOT NULL,
  LuogoNascita VARCHAR(30)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS COMPETENZA (
 NomeCompetenza VARCHAR(100) PRIMARY KEY
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS  VOCE_CONTABILE (
 NomeVoceContabile VARCHAR(30),
 Username_Amministratore VARCHAR(30),
 Descrizione TEXT NOT NULL,
 PRIMARY KEY (NomeVoceContabile, Username_Amministratore),
 FOREIGN KEY (Username_Amministratore) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
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
    PRIMARY KEY (NomeIndicatore, Username_Amministratore),
	FOREIGN KEY (Username_Amministratore) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS EMAIL (
 Username VARCHAR(30),
 IndirizzoEmail VARCHAR(30),
 PRIMARY KEY (Username, IndirizzoEmail),
 FOREIGN KEY (Username) REFERENCES UTENTI(Username) ON UPDATE CASCADE ON DELETE CASCADE
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
    Esito VARCHAR(30) NOT NULL, 
    CampoRilievi TEXT,
    Data DATE NOT NULL,
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

-- Popolamento per test

INSERT IGNORE INTO  UTENTI (Username, Password, CF, DataNascita, LuogoNascita) VALUES
('amministratore', 'pass123', 'CFADMIN001234567', '1980-01-01', 'Roma'),
('revisore1', 'pass123', 'CFREV00001234567', '1985-05-05', 'Milano'),
('revisore2', 'pass123', 'CFREV00002234567', '1990-10-10', 'Torino'),
('responsabile1', 'pass123', 'CFRESP0001234567', '1975-03-03', 'Napoli');

INSERT IGNORE INTO AMMINISTRATORE (Username) VALUES ('amministratore');

INSERT IGNORE INTO REVISORE_ESG (Username, NumeroRevisioni, IndiceAffidabilita) VALUES 
('revisore1', 0, 0.00), 
('revisore2', 0, 0.00);

INSERT IGNORE INTO RESPONSABILE_AZIENDALE (Username, CurriculumVitae) VALUES ('responsabile1', 'cv_mario.pdf');

INSERT IGNORE INTO EMAIL (Username, IndirizzoEmail) VALUES
('amministratore', 'admin@example.com'),
('revisore1', 'revisore1@example.com'),
('revisore2', 'revisore2@example.com'),
('responsabile1', 'resp1@example.com');

INSERT IGNORE INTO VOCE_CONTABILE (NomeVoceContabile, Username_Amministratore , Descrizione) VALUES
('Ricavi Vendite', 'amministratore' ,'Totale dei ricavi derivanti dalla vendita di prodotti e servizi'),
('Costo del Personale', 'amministratore' , 'Costi sostenuti per stipendi e contributi dei dipendenti'),
('Consumi Energetici', 'amministratore' ,'Costi sostenuti per l energia elettrica');

INSERT IGNORE INTO INDICATORE_ESG (NomeIndicatore, Username_Amministratore, Immagine, Rilevanza, TipoIndicatore ,CodiceNormativa, AmbitoSociale, FrequenzaRilevazione) VALUES
('Energia Rinnovabile', 'amministratore' , 'energia.png', 9, 'A', 'ISO50001', NULL, NULL),
('Ore Formazione', 'amministratore' , 'formazione.png',7, 'B', NULL, 'Dipendenti', 'Annuale');

INSERT IGNORE  INTO AZIENDA (RagioneSociale, Nome, PartitaIVA, Settore, NumDipendenti, Logo, NumBilanci, Username_Responsabile) VALUES
('Apple Italia SRL', 'Apple', '12345678901', 'Tecnologia', 500, 'apple_logo.png', 0, 'responsabile1'),
('Barilla SPA', 'Barilla', '10987654321', 'Alimentare', 1200, 'barilla_logo.png', 0, 'responsabile1');

INSERT IGNORE INTO BILANCIO (RagioneSociale, DataCreazione, Stato) VALUES
('Apple Italia SRL', '2025-12-31', 'approvato'),
('Apple Italia SRL', '2024-12-31', 'approvato'),
('Barilla SPA', '2025-12-31', 'bozza');

INSERT IGNORE INTO COMPETENZA (NomeCompetenza) VALUES
('Revisione Contabile'),
('Controllo Qualità');

INSERT IGNORE INTO POSSIEDE (Username_Revisore, NomeCompetenza, Livello) VALUES
('revisore1', 'Revisione Contabile', 5),
('revisore2', 'Controllo Qualità', 4);

INSERT IGNORE INTO TEMPLATE (Username_Amministratore, NomeVoceContabile) VALUES
('amministratore', 'Ricavi Vendite'),
('amministratore', 'Costo del Personale'),
('amministratore', 'Consumi Energetici');

INSERT IGNORE INTO VOCE_BILANCIO (NomeVoceBilancio, RagioneSociale, DataCreazione, ValoreNumerico) VALUES
('Ricavi Vendite', 'Apple Italia SRL', '2025-12-31', 5000000.00),
('Costo del Personale', 'Apple Italia SRL', '2025-12-31', 1500000.00),
('Consumi Energetici', 'Apple Italia SRL', '2025-12-31', 200000.00);

INSERT IGNORE INTO VALUTA_GIUDIZIO (Username_Revisore, RagioneSociale, DataCreazione, Esito, CampoRilievi, Data) VALUES
('revisore1', 'Apple Italia SRL', '2025-12-31', 'approvazione', 'Ottima gestione sostenibile', '2026-01-15'),
('revisore2', 'Apple Italia SRL', '2024-12-31', 'approvazione con rilievi', 'Mancano alcuni dati minori', '2025-01-20');

INSERT IGNORE INTO ANNOTAZIONE (Username_Revisore, NomeVoceBilancio, RagioneSociale, DataCreazione, Data, Testo) VALUES
('revisore1', 'Consumi Energetici', 'Apple Italia SRL', '2025-12-31', '2026-01-10', 'Verificare le fatture del mese di Novembre per i consumi.'),
('revisore1', 'Costo del Personale', 'Apple Italia SRL', '2025-12-31', '2026-01-12', 'I costi dei benefit aziendali sono stati calcolati correttamente.');

INSERT IGNORE INTO MEMORIZZA (NomeIndicatore, NomeVoceBilancio, RagioneSociale, DataCreazione, Valore, Fonte, Data) VALUES
('Energia Rinnovabile', 'Consumi Energetici', 'Apple Italia SRL', '2025-12-31', 85.5, 'Bolletta Enel', '2025-12-31'),
('Ore Formazione', 'Costo del Personale', 'Apple Italia SRL', '2025-12-31', 120.0, 'Registro HR', '2025-12-31');
