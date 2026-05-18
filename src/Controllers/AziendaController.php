<?php 
namespace Controllers;

class AziendaController {
    private $dbModel;
 
    public function __construct($dbModel){         
        $this->dbModel = $dbModel;     
    }

    public function index(){         
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_azienda'])) {                         
            
            $usernameResponsabile = $_SESSION['username'];             
            $ragioneSociale       = $_POST['ragione_sociale'];
            $nome                 = $_POST['nome_azienda'];
            $partitaIva           = $_POST['partita_iva'];
            $settore              = $_POST['settore'];
            $numDipendenti        = $_POST['num_dipendenti'];

            // Gestione del logo
            $logo = $_FILES['logo'];
            $tipi_consentiti = ['image/jpeg', 'image/png', 'image/gif'];

            // Verifico se c'è stato un errore nel caricamento o se il tipo non è un'immagine
            if ($logo['error'] !== UPLOAD_ERR_OK || !in_array($logo['type'], $tipi_consentiti)) {
                $data['messaggio_errore'] = "Errore: Il logo deve obbligatoriamente essere un'immagine (JPG, PNG o GIF).";
            } else {
                try {                 
                    $logoPath = "/uploads/loghi/" . basename($logo['name']);

                    // Recupero le 7 variabili necessarie per la tabella AZIENDA
                    $this->dbModel->registraAzienda(
                        $usernameResponsabile, 
                        $ragioneSociale, 
                        $nome, 
                        $partitaIva, 
                        $settore, 
                        $numDipendenti, 
                        $logoPath
                    );
                    
                    $data['messaggio_successo'] = "Azienda registrata con successo e associata al tuo profilo!";             
                
                } catch (\PDOException $e) {                 
                    if ($e->errorInfo[1] == 1062) {
                        $msg = $e->getMessage();

                        if (str_contains($msg, 'PartitaIVA')) {
                            $data['messaggio_errore'] = "Partita IVA già esistente.";
                        } elseif (str_contains($msg, 'PRIMARY')) {
                            $data['messaggio_errore'] = "Azienda già registrata.";
                        }
                    } else {
                        $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();
                    }
                }
            }             
        }

        include BASE_PATH . '/Views/AziendaView.php';
    }
}
?>