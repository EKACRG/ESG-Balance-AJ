<?php 
namespace Controllers;

class RevisoreController {
    
    // Questo metodo mostra il menu principale con i 3 bottoni
    public function index() {

        // Recuperiamo l'username dalla sessione. Usiamo ?? come "paracadute" se la sessione non fosse settata.
        $username = $_SESSION['username'] ?? 'Revisore'; 

        include BASE_PATH . '/Views/RevisoreView.php';
    }
}
?>