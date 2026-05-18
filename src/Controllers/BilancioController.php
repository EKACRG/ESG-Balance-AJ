<?php 
namespace Controllers;

class BilancioController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }

    public function index(){
        $data = [
            'messaggio_successo' => null, 
            'messaggio_errore' => null,
            'aziende' => [],
            'voci_template' => []
        ];

        // Simuliamo l'utente loggato (da sostituire con $_SESSION['username'])
        $usernameResponsabile = $_SESSION['username']; 
        // Recuperiamo i dati dinamici per la View
        $data['aziende'] = $this->dbModel->getAziendeByResponsabile($usernameResponsabile);
        $data['voci_template'] = $this->dbModel->getVociTemplate();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_bilancio'])) {
            $ragioneSociale = $_POST['ragione_sociale'];
            $dataCreazione = $_POST['data_creazione'];
            $valoriVoci = $_POST['voci']; 

            try {
                $this->dbModel->creaBilancio($ragioneSociale, $dataCreazione);

                foreach ($valoriVoci as $nomeVoce => $valoreNumerico) {
                    if ($valoreNumerico !== "") {
                        $this->dbModel->popolaVoceBilancio($nomeVoce, $ragioneSociale, $dataCreazione, (float)$valoreNumerico);
                    }
                }
                $data['messaggio_successo'] = "Bilancio creato e popolato in stato di Bozza!";
            } catch (\PDOException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $data['messaggio_errore'] = "Esiste già un bilancio per questa azienda in questa data. Scegli una data diversa.";
                } else {
                    $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();
                }
            }
        }
        
        include BASE_PATH . '/Views/BilancioView.php';
    }
}
?>