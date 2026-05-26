<?php
namespace Controllers;

class AmministratoreController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;

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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $indicatore = $_POST["indicatore"] ?? "";
            $usernameAmministratore = $_SESSION["username"];
            $rilevanza = $_POST["rilevanza"] ?? 0;
            $tipoIndicatore = $_POST["tipoIndicatore"] ?? "";
            $codiceNormativa = $_POST["codiceNormativa"] ?? null;
            $ambitoSociale = $_POST["ambitoSociale"] ?? null;
            $frequenzaRilevazione = $_POST["frequenzaRilevazione"] ?? null;

            $immagine = "";
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];
            if (isset($_FILES["immagine"]) && $_FILES["immagine"]["error"] === UPLOAD_ERR_OK) {
                if (!in_array($_FILES["immagine"]["type"], $tipi_consentiti)) {
                    $messaggio_errore = "Tipo immagine non consentito.";
                } else {
                    $immagine = "/uploads/" . time() . "_" . basename($_FILES["immagine"]["name"]);
                }
            }

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

                if ($immagine !== "") {
                    move_uploaded_file($_FILES["immagine"]["tmp_name"], BASE_PATH . $immagine);
                }

                header("Location: /amministratore/indicatoriESG");
                exit();
            } catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $messaggio_errore = $e->errorInfo[2];
                } else {
                    error_log($e->getMessage());
                    $messaggio_errore = "Errore durante l'inserimento dell'indicatore ESG. Riprova.";
                }
            }
        }

        include BASE_PATH . "/Views/Amministratore/IndicatoriESGView.php";
    }

    // Inserire voci contabili (template)
    public function template() {
        $messaggio_errore = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $voceContabile = $_POST["voceContabile"] ?? "";
            $usernameAmministratore = $_SESSION["username"];
            $descrizione = $_POST["descrizione"] ?? "";

            try {
                $this->dbModel->inserisciVoceContabile($voceContabile, $usernameAmministratore, $descrizione);
                header("Location: /amministratore/template");
                exit;
            }
            catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $messaggio_errore = $e->errorInfo[2];
                }
                else {
                    error_log($e->getMessage());
                    $messaggio_errore = "Errore durante l'inserimento della voce contabile. Riprova.";
                }
            }
        }

        include BASE_PATH . "/Views/Amministratore/TemplateView.php";
    }

    // Associare revisore ESG ad un bilancio aziendale
    public function associazione() {
        $messaggio_errore = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usernameRevisore = $_POST["usernameRevisore"] ?? "";
            $bilancio = $_POST["bilancio"] ?? "";
            [$ragioneSociale, $dataCreazione] = explode("|", $bilancio); 

            try {
                $this->dbModel->associaRevisore($usernameRevisore, $ragioneSociale, $dataCreazione);
                header("Location: /amministratore/associazione");
                exit;
            }
            catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $messaggio_errore = $e->errorInfo[2];
                }
                else {
                    error_log($e->getMessage());
                    $messaggio_errore = "Errore durante l'associazione. Riprova.";
                }
            }
        }

        $revisori = $this->dbModel->getRevisori();
        $bilanci = $this->dbModel->getBilanci();

        include BASE_PATH . "/Views/Amministratore/AssociazioneView.php";
    }
}
?>
