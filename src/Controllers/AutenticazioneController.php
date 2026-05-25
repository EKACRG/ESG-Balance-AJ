<?php 
namespace Controllers;

class AutenticazioneController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;
    }

    public function login() {
        $errore = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";

            if ($this->dbModel->login($username, $password)) {
                $tipoUtente = $_SESSION["tipo_utente"];
                header("Location: /" . $tipoUtente . "/index");
                exit;
            }
            else {
                $errore = "Username o password errati";
            }
        }

        include __DIR__ . "/../Views/Autenticazione/LoginView.php";
    }

    public function signup() {
        $errore = null;

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
            if (isset($_FILES["curriculumVitae"]) && $_FILES["curriculumVitae"]["error"] === UPLOAD_ERR_OK) {
                $curriculumVitaePath = "/uploads/" . basename($_FILES["curriculumVitae"]["name"]);
                move_uploaded_file($_FILES["curriculumVitae"]["tmp_name"], BASE_PATH . $curriculumVitaePath);
            }

            try {
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

                header("Location: /autenticazione/login");
                exit;

            } catch (\PDOException $e) {
                $errore = $e->errorInfo[2];
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
