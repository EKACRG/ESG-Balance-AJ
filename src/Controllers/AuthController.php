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
                header("Location: /stats/index");
                exit;
            }
            else {
                $errore = "Username o password errati";
            }
        }

        include __DIR__ . "/../Views/LoginView.php";
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

            if ($this->dbModel->signup($username, $password, $cf, $dataNascita, $luogoNascita, $email, $tipoUtente, $curriculumVitae)) {
                header("Location: /auth/login");
                exit;
            }
            else {
                $errore = "Errore nella creazione dell'utente";
            }
        }

        include __DIR__ . "/../Views/SignupView.php";
    }
}
?>
