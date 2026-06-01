<?php
namespace Controllers;
use Services\GestoreErroreDb;

class AmministratoreController {
    private $dbModel;
    private $logModel;

    public function __construct($dbModel, $logModel) {
        $this->dbModel = $dbModel;
        $this->logModel = $logModel;

        // Controllo utente loggato e tipo utente
        if (!$this->dbModel->isLoggedIn() || $_SESSION["tipo_utente"] !== "amministratore") {
            header("Location: /autenticazione/login");
            exit;
        }
    }

    // Dashboard amministratore
    public function index() {
        include BASE_PATH . "/Views/Amministratore/AmministratoreView.php";
    }

    // Inserimento indicatori ESG
    public function indicatoriESG() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Recupero dati dal form
            $indicatore = $_POST["indicatore"] ?? "";
            $usernameAmministratore = $_SESSION["username"];
            $rilevanza = $_POST["rilevanza"] ?? 0;
            $tipoIndicatore = $_POST["tipoIndicatore"] ?? "";
            $codiceNormativa = $_POST["codiceNormativa"] ?? null;
            $ambitoSociale = $_POST["ambitoSociale"] ?? null;
            $frequenzaRilevazione = $_POST["frequenzaRilevazione"] ?? null;

            // Validazione immagine
            $immagine = "";
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];
            if (!isset($_FILES["immagine"]) || $_FILES["immagine"]["error"] !== UPLOAD_ERR_OK) {
                $messaggio_errore = "Immagine non caricata correttamente.";
            }
            elseif (!in_array($_FILES["immagine"]["type"], $tipi_consentiti)) {
                    $messaggio_errore = "Tipo immagine non consentito.";
            } else {
                    $immagine = "/uploads/" . time() . "_" . basename($_FILES["immagine"]["name"]);
            }

            // Inserimento nel db solo se non ci sono errori di validazione
            if ($messaggio_errore === null) try {
                $this->dbModel->inserisciIndicatoriESG(
                    $indicatore,
                    $usernameAmministratore,
                    $immagine,
                    $rilevanza,
                    $tipoIndicatore,
                    $codiceNormativa,
                    $ambitoSociale,
                    $frequenzaRilevazione
                );

                // Spostamento del file nella cartella uploads
                if ($immagine !== "") {
                    move_uploaded_file($_FILES["immagine"]["tmp_name"], BASE_PATH . $immagine);
                }

                // Log dell'evento
                $this->logModel->logEvento("Inserimento indicatore ESG '$indicatore' da parte di $usernameAmministratore");
                $messaggio_successo = "Indicatore ESG inserito con successo!";
            } catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "indicatore_esg");
            }
        }
        
        include BASE_PATH . "/Views/Amministratore/IndicatoriESGView.php";
    }

    // Inserire voci contabili (template)
    public function template() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Recupero dati dal form
            $voceContabile = $_POST["voceContabile"] ?? "";
            $usernameAmministratore = $_SESSION["username"];
            $descrizione = $_POST["descrizione"] ?? "";

            try {
                // Inserimento nel db
                $this->dbModel->inserisciVoceContabile($voceContabile, $usernameAmministratore, $descrizione);
                // Log dell'evento
                $this->logModel->logEvento("Inserimento voce contabile '$voceContabile' da parte di $usernameAmministratore");
                $messaggio_successo = "Voce contabile inserito con successo!";
            }
            catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "voce_contabile");
            }
        }

        include BASE_PATH . "/Views/Amministratore/TemplateView.php";
    }

    // Associare revisore ESG ad un bilancio aziendale
    public function associazione() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Recupero dati dal form
            $usernameRevisore = $_POST["usernameRevisore"] ?? "";
            $bilancio = $_POST["bilancio"] ?? "";
            [$ragioneSociale, $dataCreazione] = explode("|", $bilancio); 

            try {
                // Inserimento nel db
                $this->dbModel->associaRevisore($usernameRevisore, $ragioneSociale, $dataCreazione);
                // Log dell'evento
                $this->logModel->logEvento("Associazione revisore $usernameRevisore a bilancio '$ragioneSociale'");
                $messaggio_successo = "associazione revisore con successo!";
            }
            catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "giudizio");
            }
        }

        $revisori = $this->dbModel->getRevisori();
        $bilanci = $this->dbModel->getBilanci();

        include BASE_PATH . "/Views/Amministratore/AssociazioneView.php";
    }
}
?>
