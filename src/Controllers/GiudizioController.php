<?php 
namespace Controllers;

class GiudizioController {
    private $dbModel;

    public function __construct($dbModel){         
        $this->dbModel = $dbModel;     
    }

    public function index(){         
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_giudizio'])) {                         
            
            // Estrazione dati dal form
            $usernameRevisore = $_POST['username_revisore'];             
            $ragioneSociale   = $_POST['ragione_sociale'];
            $dataCreazione    = $_POST['data_creazione'];
            $esito            = $_POST['esito'];
            $dataGiudizio     = $_POST['data_giudizio'];
            $campoRilievi     = trim($_POST['campo_rilievi']); // trim rimuove gli spazi vuoti extra

            $esiti_validi = ['approvazione', 'approvazione con rilievi', 'respingimento'];
            
            if (!in_array($esito, $esiti_validi)) {
                $data['messaggio_errore'] = "Errore: Esito del giudizio non valido. Scegli un'opzione corretta.";
            } else {
                try {                 
                    $this->dbModel->salvaGiudizio($usernameRevisore, $ragioneSociale, $dataCreazione, $esito, $dataGiudizio, $campoRilievi);
                    
                    $data['messaggio_successo'] = "Giudizio salvato con successo! I trigger hanno aggiornato lo stato del bilancio e le tue statistiche.";             
                
                } catch (\PDOException $e) {                 
                    $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();             
                }
            }             
        }

        include BASE_PATH . '/Views/GiudizioView.php';
    }
}
?>
