<?php 
namespace Controllers;

class AuthController {
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
            $curriculumVitae = $_POST["curriculumVitae"] ?? null;
            $emailAggiuntive = $_POST["emailAggiuntive"] ?? [];

            try {
                $this->dbModel->signup($username, $password, $cf, $dataNascita, $luogoNascita, $email, $tipoUtente, $curriculumVitae, $emailAggiuntive);
                header("Location: /auth/login");
                exit;
            }
            catch (\PDOException $e) {
                if ($e->getCode() === "45000") {
                    $errore = $e->errorInfo[2];
                }
                else {
                    error_log($e->getMessage());
                    $errore = "Errore durante la registazione. Riprova.";
                }
            }
        }

        include __DIR__ . "/../Views/Autenticazione/SignupView.php";
    }

    public function logout() {
        $this->dbModel->logout();
        header("Location: /auth/login");
        exit;
    }
}
?>
