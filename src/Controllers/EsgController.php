<?php 
namespace Controllers;

class EsgController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }

    public function index(){
        $data = [
            'messaggio_successo' => null, 
            'messaggio_errore' => null,
            'indicatori' => [],
            'voci_bilancio' => []
        ];

        // Simuliamo l'utente loggato (da sostituire con $_SESSION['username'])
        $usernameResponsabile = "responsabile1"; 

        // Recuperiamo i dati per popolare i menu a tendina
        $data['indicatori'] = $this->dbModel->getIndicatoriESG();
        $data['voci_bilancio'] = $this->dbModel->getVociBilancioResponsabile($usernameResponsabile);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_esg'])) {
            $nomeIndicatore = $_POST['nome_indicatore'];
            
            // Separiamo la chiave composta (NomeVoce | RagioneSociale | DataCreazione)
            $chiaviVoce = explode('|', $_POST['voce_bilancio_keys']);
            $nomeVoce = $chiaviVoce[0];
            $ragioneSociale = $chiaviVoce[1];
            $dataCreazione = $chiaviVoce[2];

            $valore = (float)$_POST['valore'];
            $fonte = $_POST['fonte'];
            $dataRilevazione = $_POST['data_rilevazione'];

            try {
                $this->dbModel->inserisciValoreESG($nomeIndicatore, $nomeVoce, $ragioneSociale, $dataCreazione, $valore, $fonte, $dataRilevazione);
                $data['messaggio_successo'] = "Valore ESG associato con successo alla voce di bilancio!";                
            } catch (\PDOException $e) {
                $data['messaggio_errore'] = "Errore DB: " . $e->getMessage();
            }
        }
        
        include BASE_PATH . '/Views/EsgView.php';
    }
}
?>