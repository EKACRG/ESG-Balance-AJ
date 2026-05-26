<?php 
namespace Controllers;

class AutenticazioneController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;
    }

    public function login() {
        $messaggio_errore = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";

            if ($this->dbModel->login($username, $password)) {
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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";
            $cf = $_POST["cf"] ?? "";
            $dataNascita = $_POST["dataNascita"] ?? "";
            $luogoNascita = $_POST["luogoNascita"] ?? "";
            $email = $_POST["email"] ?? "";
            $tipoUtente = $_POST["tipoUtente"] ?? "";
            $emailAggiuntive = $_POST["emailAggiuntive"] ?? [];
            
            $curriculumVitaePath = "";
            if ($tipoUtente === "responsabile" && isset($_FILES["curriculumVitae"]) && $_FILES["curriculumVitae"]["error"] === UPLOAD_ERR_OK) {
                if ($_FILES["curriculumVitae"]["type"] !== "application/pdf") {
                    $messaggio_errore = "Il curriculum deve essere un file PDF.";
                } else {
                    $curriculumVitaePath = "/uploads/" . time() . "_" . basename($_FILES["curriculumVitae"]["name"]);
                }
            }

            if ($messaggio_errore === null) try {
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

                if ($curriculumVitaePath !== "") {
                    move_uploaded_file($_FILES["curriculumVitae"]["tmp_name"], BASE_PATH . $curriculumVitaePath);
                }

                header("Location: /autenticazione/login");
                exit;
            } catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $messaggio_errore = $e->errorInfo[2];
                } else {
                    error_log($e->getMessage());
                    $messaggio_errore = "Errore durante la registrazione. Riprova.";
                }
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
