<?php
namespace Controllers;

class Sp05Controller{
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }

    public function index(){
        $data = ['messaggio_successo' => null,'messaggio_errore'   => null];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_competenza'])) {
            
            $username = $_POST['username_revisore'];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            if ($livello < 0 || $livello > 5) {
                $data['messaggio_errore'] = "Livello non valido (0-5)";
                
            }else{
                try {
                    $this->dbModel->inserisciCompetenza($username, $competenza, $livello);
                    $data['messaggio_successo'] = "Operazione completata: Competenza salvata con successo!";
                } catch (\PDOException $e) {
                    $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();
                }
            }
        }

        include __DIR__ . '/../Views/CompetenceViews.php';
    }
}