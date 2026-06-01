<?php
namespace Controllers;

class StatsController {
    private $dbModel;

    public function __construct($dbModel) {
        $this->dbModel = $dbModel;
    }

    // Visualizzazione statistiche
    public function index() {
        $data = [
            "num_aziende" => $this->dbModel->getNumAziende(),
            "num_revisori" => $this->dbModel->getNumRevisori(),
            "azienda_piu_affidabile" => $this->dbModel->getAziendaPiuAffidabile(),
            "classifica_bilanci" => $this->dbModel->getClassificaBilanci()
        ];

        include __DIR__ . "/../Views/StatsView.php";
    }
}
?>
