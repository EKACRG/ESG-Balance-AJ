<?php 
namespace Controllers;

class ResponsabileController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }
    
    // Questo metodo mostra il menu principale con i 3 bottoni
    public function index() {

        // Recuperiamo l'username dalla sessione. Usiamo ?? come "paracadute" se la sessione non fosse settata.
        $username = $_SESSION['username'] ?? 'Responsabile';
        
        $haAziende = $this->dbModel->haAlmenoUnAzienda($username);
        $haBilanci = $this->dbModel->haAlmenoUnBilancio($username);

        include BASE_PATH . '/Views/ResponsabileView.php';
    }
}
?>