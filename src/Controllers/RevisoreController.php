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
        $sbloccaValutazioni = $this->dbModel->haBilanciDaValutare($username);
        include BASE_PATH . '/Views/Revisore/RevisoreView.php';
    }

    // Gestione competenze
    public function competenze() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_competenza'])) {
            $username = $_SESSION["username"];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            if ($livello < 0 || $livello > 5) {
                $messaggio_errore = "Livello non valido (0-5)";
            } else {
                try {
                    $this->dbModel->inserisciCompetenza($username, $competenza, $livello);
                    $messaggio_successo = "Competenza salvata con successo!";
                } 
                catch (\PDOException $e) {
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
            $nomeVoce = $_POST['nome_voce'];
            $bilancioSelezionato = $_POST['bilancio_selezionato'] ?? '';
            $parts = explode('|', $bilancioSelezionato);
            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';
            $dataNota = $_POST['data_nota'];
            $testo = $_POST['testo'];

            try {
                $this->dbModel->inserisciNota(
                    $username,
                    $nomeVoce,
                    $ragioneSociale,
                    $dataCreazione,
                    $dataNota,
                    $testo
                );

                $messaggio_successo = "Nota aggiunta con successo!";
            } 
            catch (\PDOException $e) {
                $messaggio_errore = GestoreErroreDb::handle($e, "annotazione");
            }
        }

        try {
            $bilanci_assegnati = $this->dbModel->getBilanciRevisore($username);
            $voci_contabili = $this->dbModel->getVociContabili();
        } 
        catch (\PDOException $e) {
            error_log($e->getMessage());
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
            $esito = $_POST['esito'];
            $dataGiudizio = $_POST['data_giudizio'];
            $campoRilievi = trim($_POST['campo_rilievi']);
            $parts = explode('|', $_POST['bilancio_selezionato']);
            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';
            $esiti_validi = [
                'approvazione',
                'approvazione con rilievi',
                'respingimento'
            ];

            if (!in_array($esito, $esiti_validi)) {
                $messaggio_errore = "Esito non valido.";
            } 
            else {
                try {
                    $this->dbModel->salvaGiudizio(
                        $usernameRevisore,
                        $ragioneSociale,
                        $dataCreazione,
                        $esito,
                        $dataGiudizio,
                        $campoRilievi
                    );

                    $messaggio_successo = "Giudizio salvato con successo!";
                } catch (\PDOException $e) {
                    $messaggio_errore = GestoreErroreDb::handle($e, "giudizio");
                }
            }
        }

        $bilanci_assegnati = $this->dbModel->getBilanciRevisore($usernameRevisore);

        include BASE_PATH . '/Views/Revisore/GiudizioView.php';
    }
}
?>
