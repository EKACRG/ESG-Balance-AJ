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

            // METODO ALTERNATIVO FILE PDF
            $curriculumVitaePath = "";

            if (
                isset($_FILES["curriculumVitae"]) &&
                $_FILES["curriculumVitae"]["error"] === UPLOAD_ERR_OK
            ) {
                $curriculumVitaePath = "/uploads/curriculum/" . basename($_FILES["curriculumVitae"]["name"]);
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
                    $curriculumVitaePath
                );

                header("Location: /auth/login");
                exit;

            } catch (\PDOException $e) {
                $errore = $e->errorInfo[2];
            }
        }

        include __DIR__ . "/../Views/SignupView.php";
    }

    public function logout() {
        $this->dbModel->logout();
        header("Location: /auth/login");
        exit;
    }
}
?>
