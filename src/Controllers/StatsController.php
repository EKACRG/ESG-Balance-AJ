<?php
namespace Controllers;

use Models\StatsModel;

class StatsController {
    private $statsModel;

    public function __construct() {
        $this->statsModel = new StatsModel();
    }

    public function index() {
        $data = [
            "num_aziende" => $this->statsModel->getNumAziende(),
            "num_revisori" => $this->statsModel->getNumRevisori(),
            "azienda_piu_affidabile" => $this->statsModel->getAziendaPiuAffidabile(),
            "classifica_bilanci" => $this->statsModel->getClassificaBilanci()
        ];

        include __DIR__ . "/../Views/StatsView.php";
    }
}
?>
