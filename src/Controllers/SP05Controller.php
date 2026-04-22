<?php
namespace Controllers;

use Models\SP05Model;

class SP05Controller{
    private $model;

    public function __construct(){
        $this-> model = new SP05Model();
    }

    public function index(){
        $data = ['messaggio_successo' => null,'messaggio_errore'   => null];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salva_competenza'])) {
            
            $username = $_POST['username_revisore'];
            $competenza = $_POST['nome_competenza'];
            $livello = $_POST['livello'];

            try {
                $data['messaggio_successo'] = "Operazione completata: Competenza salvata con successo!";
            } catch (\PDOException $e) {
                $data['messaggio_errore'] = "Errore dal Database: " . $e->getMessage();
            }
        }

        include __DIR__ . '/../Views/SP05Views.php';
    }
}