<?php 
namespace Controllers;

class NoteController {
    private $dbModel;

    public function __construct($dbModel){         
        $this->dbModel = $dbModel;     
    }

    public function index(){         
        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null
        ];

        // Ascolto se il form è stato inviato (tramite il name del bottone HTML)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_nota'])) {                         
            
            // Recupero le 6 variabili necessarie per la tabella ANNOTA
            $username = $_POST['username'];             
            $nomeVoce = $_POST['nome_voce'];             
            $ragioneSociale = $_POST['ragione_sociale'];
            $dataCreazione = $_POST['data_creazione'];
            $dataNota = $_POST['data_nota'];
            $testo = $_POST['testo'];

            try {                 
                $this->dbModel->inserisciNota($username, $nomeVoce, $ragioneSociale, $dataCreazione, $dataNota, $testo);
                $data['messaggio_successo'] = "Operazione completata: Nota aggiunta con successo alla voce di bilancio!";             
            } catch (\PDOException $e) {                 
                $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();             
            }             
        }

        include __DIR__ . '/../Views/NoteView.php';
    }
}
?>
