<?php 
namespace Controllers;

class GiudizioController {
    private $dbModel;

    public function __construct($dbModel){         
        $this->dbModel = $dbModel;     
    }

    public function index(){     
        if (!isset($_SESSION['username'])) {
    die("Utente non loggato");
}
        $usernameRevisore = $_SESSION['username'];

        $data = [
            'messaggio_successo' => null,
            'messaggio_errore'   => null,
            'bilanci_assegnati' => []
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_giudizio'])) {                         
            
            $esito            = $_POST['esito'];
            $dataGiudizio     = $_POST['data_giudizio'];
            $campoRilievi     = trim($_POST['campo_rilievi']); // trim rimuove gli spazi vuoti extra

            $parts = explode('|', $_POST['bilancio_selezionato']);

            $ragioneSociale = $parts[0] ?? '';
            $dataCreazione = $parts[1] ?? '';

            $esiti_validi = ['approvazione', 'approvazione con rilievi', 'respingimento'];
            
            
            if (!in_array($esito, $esiti_validi)) {
                $data['messaggio_errore'] = "⚠ Errore: Esito del giudizio non valido. Scegli un'opzione corretta.";
            } else {
                try {                 
                    $this->dbModel->salvaGiudizio($usernameRevisore, $ragioneSociale, $dataCreazione, $esito, $dataGiudizio, $campoRilievi);
                    
                    $data['messaggio_successo'] = "Operazione completata: Giudizio salvato con successo!";             
                
                } catch (\PDOException $e) {                 
                    $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();             
                }
            }             
        }

        $data['bilanci_assegnati'] = $this->dbModel->getBilanciRevisore($usernameRevisore);


        include BASE_PATH . '/Views/GiudizioView.php';
    }
}
?>
