<?php 
namespace Controllers;
use Services\GestoreErroreDb;

class AutenticazioneController {
    private $dbModel;
    private $logModel;

    public function __construct($dbModel, $logModel) {
        $this->dbModel = $dbModel;
        $this->logModel = $logModel;
    }

    public function login() {
        $messaggio_errore = null;

        // Se già loggato redirect alla sua pagina utente
        if ($this->dbModel->isLoggedIn()) {
            $tipo = $_SESSION["tipo_utente"] ?? null;
            header("Location: /" . $tipo . "/index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Recupero dati dal form
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";

            if ($this->dbModel->login($username, $password)) {
                // Login riuscito
                $tipoUtente = $_SESSION["tipo_utente"];
                header("Location: /" . $tipoUtente . "/index");
                exit;
            }
            else {
                $messaggio_errore = "Username o password errati";
            }
        }

        include __DIR__ . "/../Views/Autenticazione/LoginView.php";
    }

    public function signup() {
        $messaggio_errore = null;

        if ($this->dbModel->isLoggedIn()) {
            $tipo = $_SESSION["tipo_utente"] ?? null;
            header("Location: /" . $tipo . "/index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Recupero dati dal form
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";
            $cf = $_POST["cf"] ?? "";
            $dataNascita = $_POST["dataNascita"] ?? "";
            $luogoNascita = $_POST["luogoNascita"] ?? "";
            $email = $_POST["email"] ?? "";
            $tipoUtente = $_POST["tipoUtente"] ?? "";
            $emailAggiuntive = $_POST["emailAggiuntive"] ?? [];
            $curriculumVitaePath = "";
            
            try {
                // Gestione upload CV solo per i responsabili
                if ($tipoUtente === "responsabile") {
                    if (!isset($_FILES["curriculumVitae"]) || $_FILES["curriculumVitae"]["error"] !== UPLOAD_ERR_OK) {
                    throw new \Exception("Curriculum non caricato correttamente.");
                }
                if ($_FILES["curriculumVitae"]["type"] !== "application/pdf") {
                    throw new \Exception("Il curriculum deve essere un file PDF.");
                }
                 // Costruzione path con timestamp per evitare conflitti di nome
                $filename = time() . "_" . basename($_FILES["curriculumVitae"]["name"]);
                $destination = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $filename;
                if (!move_uploaded_file($_FILES["curriculumVitae"]["tmp_name"], $destination)) {
                    throw new \Exception("Errore upload file.");
                }
                $curriculumVitaePath = "/uploads/" . $filename;
            }
                // Registrazione utente nel DB
                $this->dbModel->signup(
                    $username,
                    $password,
                    $cf,
                    $dataNascita,
                    $luogoNascita,
                    $email,
                    $tipoUtente,
                    $curriculumVitaePath,
                    $emailAggiuntive
                );

                // Log dell'evento
                $this->logModel->logEvento("Registrazione nuovo utente '$username' come $tipoUtente");
                header("Location: /autenticazione/login");
                exit;
            } catch (\PDOException $e) {
                // Elimina il CV caricato se presente
                if ($curriculumVitaePath !== "" && file_exists($_SERVER['DOCUMENT_ROOT'] . $curriculumVitaePath)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $curriculumVitaePath);
                }
                $messaggio_errore = GestoreErroreDb::handle($e, "utente");
            }
        }

        include __DIR__ . "/../Views/Autenticazione/SignupView.php";
    }

    public function logout() {
        $this->dbModel->logout();
        header("Location: /autenticazione/login");
        exit;
    }
}
?>
