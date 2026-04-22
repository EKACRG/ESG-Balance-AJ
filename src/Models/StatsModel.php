<?php
namespace Models;

class StatsModel {
    private $pdo;

    public function __construct() {
        include_once __DIR__ . "/../config/db.php";
        $this->pdo = $pdo;
    }

    public function getNumAziende() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_AZIENDE");
        return $stmt->fetch();
    }

    public function getNumRevisori() {
        $stmt = $this->pdo->query("SELECT * FROM NUM_REVISORI_ESG");
        return $stmt->fetch();
    }

    public function getAziendaPiuAffidabile() {
        $stmt = $this->pdo->query("SELECT * FROM AZIENDA_PIU_AFFIDABILE");
        return $stmt->fetch();
    }

    public function getClassificaBilanci() {
        $stmt = $this->pdo->query("SELECT * FROM CLASSIFICA_BILANCI");
        return $stmt->fetchAll();
    }
}
?>
