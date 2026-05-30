<?php
namespace Controllers;
use Services\GestoreErroreDb;

class ResponsabileController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;

        // Controllo utente loggato e tipo utente
        if (!$this->dbModel->isLoggedIn() || $_SESSION["tipo_utente"] !== "responsabile") {
            header("Location: /autenticazione/login");
            exit;
        }
    }

    public function index() {
        $username = $_SESSION['username'];
        $haAziende = $this->dbModel->haAlmenoUnAzienda($username);
        $haBilanci = $this->dbModel->haAlmenoUnBilancio($username);

        include BASE_PATH . '/Views/Responsabile/ResponsabileView.php';
    }


    public function azienda(){
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_azienda'])) {
            $usernameResponsabile = $_SESSION['username'];
            $ragioneSociale = $_POST['ragione_sociale'];
            $nome = $_POST['nome_azienda'];
            $partitaIva = $_POST['partita_iva'];
            if (strlen($partitaIva) < 11) {
                $messaggio_errore = "La partita IVA è troppo corta. Deve contenere 11 cifre.";
            }
            elseif (strlen($partitaIva) > 11) {
                $messaggio_errore = "La partita IVA è troppo lunga. Deve contenere 11 cifre.";
            }
            elseif (!ctype_digit($partitaIva)) {
                $messaggio_errore = "La partita IVA deve contenere solo numeri.";
            }
            $settore = $_POST['settore'];
            $numDipendenti = $_POST['num_dipendenti'];

            $logoPath = "";
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];
            if (!isset($_FILES["logo"]) || $_FILES["logo"]["error"] !== UPLOAD_ERR_OK ){
                    $messaggio_errore = "Logo non valido.";
            }
            elseif (!in_array($_FILES["logo"]["type"], $tipi_consentiti)) {
                $messaggio_errore = "Tipo file non consentito.";
            }
            else {
                $logoPath = "/uploads/" . time() . "_" . basename($_FILES["logo"]["name"]);
            }
            if ($messaggio_errore === null) {
                try {
                    $this->dbModel->registraAzienda(
                        $usernameResponsabile,
                        $ragioneSociale,
                        $nome,
                        $partitaIva,
                        $settore,
                        $numDipendenti,
                        $logoPath
                    );

                    if ($logoPath !== "") {
                        move_uploaded_file($_FILES["logo"]["tmp_name"], BASE_PATH . $logoPath);
                    }

                    $messaggio_successo = "Azienda registrata!";
                } 
                catch (\PDOException $e) {
                    $messaggio_errore = GestoreErroreDb::handle($e, "azienda");
                }
            }
        }

        include BASE_PATH . '/Views/Responsabile/AziendaView.php';
    }

    public function bilancio(){
        $messaggio_errore = null;
        $messaggio_successo = null;
        $aziende = [];
        $voci_template = [];
        $username = $_SESSION['username'];
        $aziende = $this->dbModel->getAziendeByResponsabile($username);
        $voci_template = $this->dbModel->getVociTemplate();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_bilancio'])) {
            try {
                $this->dbModel->creaBilancio(
                    $_POST['ragione_sociale'],
                    $_POST['data_creazione']
                );

                foreach ($_POST['voci'] as $voce => $valore) {
                    if ($valore !== "") {
                        $this->dbModel->popolaVoceBilancio(
                            $voce,
                            $_POST['ragione_sociale'],
                            $_POST['data_creazione'],
                            (float)$valore
                        );
                    }
                }

                $messaggio_successo = "Bilancio creato!";
            } 
            catch (\PDOException $e) {
                $messaggio_errore = GestoreErroreDb::handle($e, "bilancio");
            }
        }

        include BASE_PATH . '/Views/Responsabile/BilancioView.php';
    }

    public function esgIndicatore(){
        $messaggio_errore = null;
        $messaggio_successo = null;
        $indicatori = [];
        $voci_bilancio = [];
        $username = $_SESSION['username'];
        $indicatori = $this->dbModel->getIndicatoriESG();
        $voci_bilancio = $this->dbModel->getVociBilancioResponsabile($username);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_esg'])) {
            $chiavi = explode('|', $_POST['voce_bilancio_keys']);

            try {
                $this->dbModel->inserisciValoreESG(
                    $_POST['nome_indicatore'],
                    $chiavi[0],
                    $chiavi[1],
                    $chiavi[2],
                    (float)$_POST['valore'],
                    $_POST['fonte'],
                    $_POST['data_rilevazione']
                );

                $messaggio_successo = "Valore ESG salvato!";
            } 
            catch (\PDOException $e) {
                $messaggio_errore = GestoreErroreDb::handle($e, "esg");
            }
        }

        include BASE_PATH . '/Views/Responsabile/EsgView.php';
    }
}
?>
