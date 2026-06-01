<?php
namespace Controllers;
use Services\GestoreErroreDb;

class ResponsabileController {
    private $dbModel;
    private $logModel;

    public function __construct($dbModel, $logModel) {
        $this->dbModel = $dbModel;
        $this->logModel = $logModel;

        // Controllo utente loggato e tipo utente
        if (!$this->dbModel->isLoggedIn() || $_SESSION["tipo_utente"] !== "responsabile") {
            header("Location: /autenticazione/login");
            exit;
        }
    }

    public function index() {
        $username = $_SESSION['username'];

        // Controllo se il responsabile ha già aziende e bilanci per sbloccare le funzionalità
        $haAziende = $this->dbModel->haAlmenoUnAzienda($username);
        $haBilanci = $this->dbModel->haAlmenoUnBilancio($username);

        include BASE_PATH . '/Views/Responsabile/ResponsabileView.php';
    }

    public function azienda() {
        $messaggio_errore = null;
        $messaggio_successo = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_azienda'])) {
            // Recupero dati dal form
            $usernameResponsabile = $_SESSION['username'];
            $ragioneSociale = $_POST['ragione_sociale'];
            $nome = $_POST['nome_azienda'];
            $partitaIva = $_POST['partita_iva'];

            // Validazione partita iva
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

            // Validazione logo
            $logoPath = "";
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];
            if (!isset($_FILES["logo"]) || $_FILES["logo"]["error"] !== UPLOAD_ERR_OK) {
                $messaggio_errore = "Logo non valido.";
            }
            elseif (!in_array($_FILES["logo"]["type"], $tipi_consentiti)) {
                $messaggio_errore = "Tipo file non consentito.";
            }
            else {
                // Costruzione path con timestamp per evitare conflitti di nome
                $logoPath = "/uploads/" . time() . "_" . basename($_FILES["logo"]["name"]);
            }
            
            // Inserimento nel db se non ci sono errori
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

                    // Spostamento del file nella cartella uploads
                    if ($logoPath !== "") {
                        move_uploaded_file($_FILES["logo"]["tmp_name"], BASE_PATH . $logoPath);
                    }

                    // Log dell'evento
                    $this->logModel->logEvento("Registrazione azienda '$ragioneSociale' da parte di $usernameResponsabile");
                    $messaggio_successo = "Azienda registrata!";
                } 
                catch (\PDOException $e) { // Gestione errori
                    $messaggio_errore = GestoreErroreDb::handle($e, "azienda");
                }
            }
        }

        include BASE_PATH . '/Views/Responsabile/AziendaView.php';
    }

    public function bilancio() {
        $messaggio_errore = null;
        $messaggio_successo = null;
        $aziende = [];
        $voci_template = [];
        $username = $_SESSION['username'];
        $aziende = $this->dbModel->getAziendeByResponsabile($username);
        $voci_template = $this->dbModel->getVociTemplate();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_bilancio'])) {
            // Recupero dati dal form    
            $ragione_sociale = $_POST['ragione_sociale'];
            $data_creazione = $_POST['data_creazione'];

            try {
                $this->dbModel->creaBilancio(
                    $ragione_sociale,
                    $data_creazione
                );

                foreach ($_POST['voci'] as $voce => $valore) {
                    if ($valore !== "") {
                        $this->dbModel->popolaVoceBilancio(
                            $voce,
                            $ragione_sociale,
                            $data_creazione,
                            (float)$valore
                        );
                    }
                }

                // Log dell'evento
                $this->logModel->logEvento("Creazione bilancio per azienda '$ragione_sociale' in data $data_creazione da parte di $username");
                $messaggio_successo = "Bilancio creato!";
            } 
            catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "bilancio");
            }
        }

        include BASE_PATH . '/Views/Responsabile/BilancioView.php';
    }

    public function esgIndicatore() {
        $messaggio_errore = null;
        $messaggio_successo = null;
        $indicatori = [];
        $voci_bilancio = [];
        $username = $_SESSION['username'];
        $indicatori = $this->dbModel->getIndicatoriESG();
        $voci_bilancio = $this->dbModel->getVociBilancioResponsabile($username);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_esg'])) {
            // Recupero dati dal form
            $chiavi = explode('|', $_POST['voce_bilancio_keys']);
            $nome_indicatore = $_POST['nome_indicatore'];
            $valore = (float)$_POST['valore'];
            $fonte = $_POST['fonte'];
            $data_rilevazione = $_POST['data_rilevazione'];

            try {
                // Inserimento nel db
                $this->dbModel->inserisciValoreESG(
                    $nome_indicatore,
                    $chiavi[0],
                    $chiavi[1],
                    $chiavi[2],
                    $valore,
                    $fonte,
                    $data_rilevazione
                );

                // Log dell'evento
                $this->logModel->logEvento("Inserimento valore ESG '$nome_indicatore' per azienda '$chiavi[1]' da parte di $username");
                $messaggio_successo = "Valore ESG salvato!";
            } 
            catch (\PDOException $e) { // Gestione errori
                $messaggio_errore = GestoreErroreDb::handle($e, "esg");
            }
        }

        include BASE_PATH . '/Views/Responsabile/EsgView.php';
    }
}
?>
