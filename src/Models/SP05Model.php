<?php
namespace Models;

class SP05Model{
    private $pdo;

    public function __construct(){
        include_once __DIR__ . '/../config/db.php';
        $this->pdo = $pdo;
    }

    public function getSP05($username, $competenza, $livello){
        $query05 = $this->pdo->prepare("CALL InserimentoCompetenze(?,?,?)");
        $query05 -> execute([$username, $competenza, $livello]);
    }
}