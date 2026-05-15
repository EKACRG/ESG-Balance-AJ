<?php 
namespace Controllers;

class RevisoreController {
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }
    
    // Questo metodo mostra il menu principale con i 3 bottoni
    public function index() {

        // Recuperiamo l'username dalla sessione. Usiamo ?? come "paracadute" se la sessione non fosse settata.
        $username = $_SESSION['username'] ?? 'revisore1'; 

        $sbloccaValutazioni = $this->dbModel->haBilanciDaValutare($username);

        include BASE_PATH . '/Views/RevisoreView.php';
    }
}
?>