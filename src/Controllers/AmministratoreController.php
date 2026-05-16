<?php
namespace Controllers;

class AmministratoreController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;

        // Controllo utente loggato e tipo utente
        if (!$this->dbModel->isLoggedIn() || $_SESSION["tipo_utente"] !== "amministratore") {
            header("Location: /auth/login");
            exit;
        }
    }

    // Dashboard amministratore
    public function index() {
        include BASE_PATH . "/Views/Amministratore/AmministratoreView.php";
    }

    // Inserimento indicatori ESG
    public function indicatoriESG() {
        $errore = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $indicatore = $_POST["indicatore"] ?? "";
            $usernameAmministratore = $_SESSION["username"];
            $immagine = $_POST["immagine"] ?? "";
            $rilevanza = $_POST["rilevanza"] ?? 0;
            $tipoIndicatore = $_POST["tipoIndicatore"] ?? "";
            $codiceNormativa = $_POST["codiceNormativa"] ?? null;
            $ambitoSociale = $_POST["ambitoSociale"] ?? null;
            $frequenzaRilevazione = $_POST["frequenzaRilevazione"] ?? null;

            try {
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
                header("Location: /amministratore/indicatoriESG");
                exit();
            } catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $errore = $e->errorInfo[2];
                } else {
                    error_log($e->getMessage());
                    $errore = "Errore durante la l'inserimento dell'indicatore ESG. Riprova.";
                }
            }
        }

        include BASE_PATH . "/Views/Amministratore/IndicatoriESGView.php";
    }

    // Inserire voci contabili (template)
    public function template() {
        $errore = null;

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
                    $errore = $e->errorInfo[2];
                }
                else {
                    error_log($e->getMessage());
                    $errore = "Errore durante la l'inserimento della voce contabile. Riprova.";
                }
            }
        }

        include BASE_PATH . "/Views/Amministratore/TemplateView.php";
    }

    // Associare revisore ESG ad un bilancio aziendale
    public function associazione() {
        $errore = null;

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
                    $errore = $e->errorInfo[2];
                }
                else {
                    error_log($e->getMessage());
                    $errore = "Errore durante l'associazione. Riprova.";
                }
            }
        }

        $revisori = $this->dbModel->getRevisori();
        $bilanci = $this->dbModel->getBilanci();

        include BASE_PATH . "/Views/Amministratore/AssociazioneView.php";
    }
}
?>
