<?php
namespace Controllers;
use Services\GestoreErroreDb;

class RevisoreController {
    private $dbModel;
    private $logModel;

    public function __construct($dbModel, $logModel) {
        $this->dbModel = $dbModel;
        $this->logModel = $logModel;
                
        // Controllo utente loggato e tipo utente
        if (!$this->dbModel->isLoggedIn() || $_SESSION["tipo_utente"] !== "revisore") {
            header("Location: /autenticazione/login");
            exit;
        }
    }

    // Dashboard revisore
    public function index() {
        $username = $_SESSION['username'];

        // Controllo se il revisore ha bilanci da valutare per sbloccare le funzionalità
        $sbloccaValutazioni = $this->dbModel->haBilanciDaValutare($username);
        include BASE_PATH . '/Views/Revisore/RevisoreView.php';
    }

    // Gestione competenze
    public function competenze() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_competenza'])) {
            // Recupero dati dal form
            $username = $_SESSION["username"];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            // Validazione livello competenza
            if ($livello < 0 || $livello > 5) {
                $messaggio_errore = "Livello non valido (0-5)";
            } else {
                try {
                    // Inserimento nel db
                    $this->dbModel->inserisciCompetenza($username, $competenza, $livello);
                    // Log dell'evento
                    $this->logModel->logEvento("Inserimento competenza '$competenza' da parte di $username");
                    $messaggio_successo = "Competenza salvata con successo!";
                } 
                catch (\PDOException $e) { // Gestione errori
                    $messaggio_errore = GestoreErroreDb::handle($e, "possiede");
                }
            }
        }

        include BASE_PATH . '/Views/Revisore/CompetenceView.php';
    }

    // Gestione note
    public function note() {
        $username = $_SESSION['username'];
        $messaggio_successo = null;
        $messaggio_errore = null;
        $bilanci_assegnati = [];
        $voci_contabili = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_nota'])) {
            // Recupero dati dal form
            $nomeVoce = $_POST['nome_voce'];
            $bilancioSelezionato = $_POST['bilancio_selezionato'] ?? '';
            $parts = explode('|', $bilancioSelezionato);
            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';
            $dataNota = $_POST['data_nota'];
            $testo = $_POST['testo'];

            try {
                // Inserimento nel db
                $this->dbModel->inserisciNota(
                    $username, 
                    $nomeVoce, 
                    $ragioneSociale,
                    $dataCreazione, 
                    $dataNota, 
                    $testo
                );

                // Log dell'evento
                $this->logModel->logEvento("Inserimento nota su voce '$nomeVoce' del bilancio '$ragioneSociale' da parte di $username");
                $messaggio_successo = "Nota aggiunta con successo!";
            } 
            catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "annotazione");
            }
        }

        try {
            // Recupero bilanci assegnati e voci contabili per popolare il form
            $bilanci_assegnati = $this->dbModel->getBilanciRevisore($username);
            $voci_contabili = $this->dbModel->getVociContabili();
        } 
        catch (\PDOException $e) {
            $messaggio_errore = "Errore durante il caricamento dei dati. Riprova.";
        }

        include BASE_PATH . '/Views/Revisore/NoteView.php';
    }

    // Gestione giudizi
    public function giudizio() {
        $usernameRevisore = $_SESSION['username'];
        $messaggio_successo = null;
        $messaggio_errore = null;
        $bilanci_assegnati = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_giudizio'])) {
            // Recupero dati dal form
            $esito = $_POST['esito'];
            $dataGiudizio = $_POST['data_giudizio'];
            $campoRilievi = trim($_POST['campo_rilievi']);
            $parts = explode('|', $_POST['bilancio_selezionato']);
            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';
            $esiti_validi = ['approvazione', 'approvazione con rilievi', 'respingimento'];

            // Validazione esito
            if (!in_array($esito, $esiti_validi)) {
                $messaggio_errore = "Esito non valido.";
            } else {
                try {
                    // Salvataggio nel db
                    $this->dbModel->salvaGiudizio(
                        $usernameRevisore, 
                        $ragioneSociale,
                        $dataCreazione, 
                        $esito, 
                        $dataGiudizio, 
                        $campoRilievi
                    );

                    // Log dell'evento
                    $this->logModel->logEvento("Salvataggio giudizio '$esito' sul bilancio '$ragioneSociale' da parte di $usernameRevisore");
                    $messaggio_successo = "Giudizio salvato con successo!";
                } catch (\PDOException $e) {
                    $messaggio_errore = GestoreErroreDb::handle($e, "giudizio");
                }
            }
        }

        // Recupero bilanci assegnati per popolare il form
        $bilanci_assegnati = $this->dbModel->getBilanciRevisore($usernameRevisore);

        include BASE_PATH . '/Views/Revisore/GiudizioView.php';
    }
}
?>
