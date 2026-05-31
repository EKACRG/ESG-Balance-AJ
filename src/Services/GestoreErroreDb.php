<?php

namespace Services;

class GestoreErroreDb {

    public static function handle(\PDOException $e, string $context = ""): string {

        $code = $e->getCode();
        $msg = $e->errorInfo[2] ?? "";

        /*
        ======================================================
        1. ERRORI PERSONALIZZATI DAL DATABASE (SIGNAL SQLSTATE)
        ======================================================
        */
        if ($code === "45000") {
            return $msg ?: "Errore di validazione dati";
        }

        /*
        ======================================================
        2. VIOLAZIONE VINCOLI (DUPLICATI / UNIQUE / PK)
        ======================================================
        */
        if ($code === "23000") {

            // UTENTI
            if ($context === "utente") {
                if (str_contains($msg, "Username")) {
                    return "Username già in uso";
                }
                if (str_contains($msg, "CF")) {
                    return "Codice fiscale già registrato";
                }
                return "Utente già esistente";
            }

            // EMAIL
            if ($context === "email") {
                return "Email già associata a un utente";
            }

            // AZIENDA
            if ($context === "azienda") {
                if (str_contains($msg, "PartitaIVA")) {
                    return "Partita IVA già registrata";
                }
                if (str_contains($msg, "PRIMARY")) {
                    return "Azienda già esistente";
                }
                return "Azienda già presente nel sistema";
            }

            // VOCE CONTABILE
            if ($context === "voce_contabile") {
                return "Voce contabile già presente";
            }

            // INDICATORE ESG
            if ($context === "indicatore_esg") {
                return "Indicatore ESG già esistente";
            }

            // COMPETENZA
            if ($context === "competenza") {
                return "Competenza già inserita";
            }

            // BILANCIO
            if ($context === "bilancio") {
                return "Bilancio già esistente per questa data";
            }

            // VOCE BILANCIO
            if ($context === "voce_bilancio") {
                return "Voce già inserita in questo bilancio";
            }

            // ANNOTAZIONE
            if ($context === "annotazione") {
                return "Annotazione già presente per questa voce";
            }

            // MEMORIZZA (ESG VALUES)
            if ($context === "esg") {
                return "Valore ESG già inserito per questa voce";
            }

            // VALUTA_GIUDIZIO
            if ($context === "giudizio") {
                return "Giudizio già assegnato a questo bilancio";
            }

            // POSSIEDE (REVISORE - COMPETENZA)
            if ($context === "possiede") {
                return "Competenza già assegnata a questo revisore";
            }

            return "Violazione vincolo del database (dato già presente o relazione non valida)";
        }

        /*
        ======================================================
        3. FOREIGN KEY ERROR
        ======================================================
        */
        if ($code === "1452") {

            if ($context === "azienda") {
                return "Responsabile non valido";
            }

            if ($context === "bilancio") {
                return "Azienda non esistente";
            }

            if ($context === "voce_bilancio") {
                return "Voce contabile o bilancio non valido";
            }

            if ($context === "esg") {
                return "Riferimento ESG non valido";
            }

            if ($context === "giudizio") {
                return "Revisore o bilancio non valido";
            }

            return "Riferimento non valido nel database";
        }

        /*
        ======================================================
        4. FALLBACK
        ======================================================
        */
        error_log($e->getMessage());
        return "Errore imprevisto, riprova più tardi";
    }
}