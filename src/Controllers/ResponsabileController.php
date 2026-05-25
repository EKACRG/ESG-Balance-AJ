<?php
namespace Controllers;

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


    public function Azienda(){
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_azienda'])) {

            $usernameResponsabile = $_SESSION['username'];
            $ragioneSociale = $_POST['ragione_sociale'];
            $nome = $_POST['nome_azienda'];
            $partitaIva = $_POST['partita_iva'];
            $settore = $_POST['settore'];
            $numDipendenti = $_POST['num_dipendenti'];

            $logoPath = "";
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];
            if (!isset($_FILES["logo"]) || $_FILES["logo"]["error"] !== UPLOAD_ERR_OK || !in_array($_FILES["logo"]["type"], $tipi_consentiti)) {
                $data['messaggio_errore'] = "Logo non valido.";
            } else {
                $logoPath = "/uploads/" . basename($_FILES["logo"]["name"]);
                move_uploaded_file($_FILES["logo"]["tmp_name"], BASE_PATH . $logoPath);

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

                    $data['messaggio_successo'] = "Azienda registrata!";
                } catch (\PDOException $e) {
                    $data['messaggio_errore'] = "Errore DB: " . $e->getMessage();
                }
            }
        }

        include BASE_PATH . '/Views/Responsabile/AziendaView.php';
    }

    public function bilancio(){
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore' => null,
            'aziende' => [],
            'voci_template' => []
        ];

        $username = $_SESSION['username'];

        $data['aziende'] = $this->dbModel->getAziendeByResponsabile($username);
        $data['voci_template'] = $this->dbModel->getVociTemplate();

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

                $data['messaggio_successo'] = "Bilancio creato!";
            } catch (\PDOException $e) {
                $data['messaggio_errore'] = "Errore DB: " . $e->getMessage();
            }
        }

        include BASE_PATH . '/Views/Responsabile/BilancioView.php';
    }

    public function esgIndicatore(){
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore' => null,
            'indicatori' => [],
            'voci_bilancio' => []
        ];

        $username = $_SESSION['username'];

        $data['indicatori'] = $this->dbModel->getIndicatoriESG();
        $data['voci_bilancio'] = $this->dbModel->getVociBilancioResponsabile($username);

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

                $data['messaggio_successo'] = "Valore ESG salvato!";
            } catch (\PDOException $e) {
                $data['messaggio_errore'] = "Errore DB: " . $e->getMessage();
            }
        }

        include BASE_PATH . '/Views/Responsabile/EsgView.php';
    }
}
?>