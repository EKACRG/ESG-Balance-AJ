<?php 
namespace Controllers;

class AuthController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;
    }

    public function login() {
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
}

?>
