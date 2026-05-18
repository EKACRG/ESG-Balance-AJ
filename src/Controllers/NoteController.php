<?php 
namespace Controllers;

class NoteController {
    private $dbModel;

    public function __construct($dbModel){         
        $this->dbModel = $dbModel;     
    }

    public function index(){       
        $username = $_SESSION['username'];

        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null,
            'bilanci_assegnati'  => [],
            'voci_contabili'     => []
        ];

        // Ascolto se il form è stato inviato (tramite il name del bottone HTML)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_nota'])) {                         
            
                        
            $nomeVoce = $_POST['nome_voce'];             

            $bilancioSelezionato = $_POST['bilancio_selezionato'] ?? '';

            $parts = explode('|', $bilancioSelezionato);

            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';

            $dataNota = $_POST['data_nota'];
            $testo = $_POST['testo'];

            try {                 
                $this->dbModel->inserisciNota($username, $nomeVoce, $ragioneSociale, $dataCreazione, $dataNota, $testo);
                $data['messaggio_successo'] = "Operazione completata: Nota aggiunta con successo alla voce di bilancio!";             
            } catch (\PDOException $e) {

                if ($e->getCode() == 23000) {
                    $data['messaggio_errore'] = "⚠ Hai già inserito questa nota per questa voce di bilancio.";
                } else {
                    $data['messaggio_errore'] = "Errore database: " . $e->getMessage();
                }
            }
        }
        // Preparo i dati per le tendine
            try {
                $data['bilanci_assegnati'] = $this->dbModel->getBilanciRevisore($username);
                $data['voci_contabili'] = $this->dbModel->getVociContabili();
            } catch (\PDOException $e) {
            $data['messaggio_errore'] = "Impossibile caricare le tendine: " . $e->getMessage();
            }

        include __DIR__ . '/../Views/NoteView.php';
    }
}
?>
