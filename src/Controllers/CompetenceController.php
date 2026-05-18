<?php
namespace Controllers;

class CompetenceController{
    private $dbModel;

    public function __construct($dbModel){
        $this->dbModel = $dbModel;
    }

    public function index(){
        $data = ['messaggio_successo' => null,'messaggio_errore'   => null];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_competenza'])) {
            
            $username = $_SESSION["username"];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            if ($livello < 0 || $livello > 5) {
                $data['messaggio_errore'] = "Livello non valido (0-5)";
                
            }else{
                try {
                    $this->dbModel->inserisciCompetenza($username, $competenza, $livello);
                    $data['messaggio_successo'] = "Operazione completata: Competenza salvata con successo!";
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $data['messaggio_errore'] = "⚠ Competenza già assegnata a questo revisore.";
                    } else {
                        $data['messaggio_errore'] = "Errore DB: " . $e->getMessage();
                    }
                }
            }
        }

        include __DIR__ . '/../Views/CompetenceViews.php';
    }
}