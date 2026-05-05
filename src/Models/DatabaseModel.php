<?php 
namespace Models;

class DatabaseModel {
    private $pdo;

    public function __construct() {
        include __DIR__ . "/../config/db.php";
        $this->pdo = $pdo;
    }

    // STATS
    public function getNumAziende() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_AZIENDE");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getNumRevisori() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_REVISORI_ESG");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAziendaPiuAffidabile() {
        $stmt = $this->pdo->query("SELECT * FROM AZIENDA_PIU_AFFIDABILE");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getClassificaBilanci() {
        $stmt = $this->pdo->query("SELECT * FROM CLASSIFICA_BILANCI");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // AUTH
    // Login (verifica credenziali e crea sessione)
    public function login(string $username, string $password): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM UTENTI WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($user) && password_verify($password, $user["Password"])) {
            session_regenerate_id();
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $user["Password"];
            $_SESSION["tipo_utente"] = $this->getTipoUtente($username);
            return true;
        }

        return false;
    }

    // Controlla se l'utente è loggato
    public function isLoggedIn(): bool {
        if (!isset($_SESSION["username"], $_SESSION["password"])) {
            return false;
        }

        // Confronto tra dati salvati in sessione con quelli nel db
        // Gestisce due casi: utente eliminato e password cambiata
        $stmt = $this->pdo->prepare("SELECT * FROM UTENTI WHERE Username = :username");
        $stmt->execute([":username" => $_SESSION["username"]]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!empty($user) && password_verify($_SESSION["password"], $user["Password"])) {
            return true;
        }

        $this->logout();
        return false;
    }

    // Logout e rimozione sessione
    public function logout() {
        $_SESSION = [];
        session_destroy();
    }

    // Registrazione
    public function signup(
        string $username,
        string $password,
        string $cf,
        string $dataNascita,
        string $luogoNascita,
        string $email,
        string $tipoUtente,
        ?string $curriculumVitae = null
    ): bool {
        // Per non salvare la password in chiaro
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("CALL RegistraUtente(:username, :password, :cf, :dataNascita, :luogoNascita, :email)");
        $stmt->execute([
            ":username" => $username,
            ":password" => $passwordHash,
            ":cf" => $cf,
            ":dataNascita" => $dataNascita,
            ":luogoNascita" => $luogoNascita,
            ":email" => $email
        ]);

        // Chiamata alla sp specifica per tipo utente
        if ($tipoUtente === "amministratore") {
            $stmt = $this->pdo->prepare("CALL RegistraAmministratore(:username)");
            $stmt->execute([":username" => $username]);
        } 
        elseif ($tipoUtente === "revisore") {
            $stmt = $this->pdo->prepare("CALL RegistraRevisore(:username)");
            $stmt->execute([":username" => $username]);
        } 
        elseif ($tipoUtente === "responsabile") {
            $stmt = $this->pdo->prepare("CALL RegistraResponsabile(:username, :curriculumVitae)");
            $stmt->execute([
                ":username" => $username,
                ":curriculumVitae" => $curriculumVitae
            ]);
        }

        return true;
    }

    // Restituisce il tipo di utente
    private function getTipoUtente(string $username): string {
        $stmt = $this->pdo->prepare("SELECT 1 FROM AMMINISTRATORE WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        if ($stmt->fetch()) {
            return "amministratore";
        }

        $stmt = $this->pdo->prepare("SELECT 1 FROM REVISORE_ESG WHERE Username = :username");
        $stmt->execute([":username" => $username]);
        if ($stmt->fetch()) {
            return "revisore";
        }

        return "responsabile";
    }
}
?>
