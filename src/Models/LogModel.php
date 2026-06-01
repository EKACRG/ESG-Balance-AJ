<?php
namespace Models;

class LogModel {
    private $collection;

    public function __construct() {
        // Caricamento di Composer per la libreria mongodb
        require_once BASE_PATH . '/vendor/autoload.php';
        // Connessione a MongoDB
        include __DIR__ . "/../config/mongo.php";
        // Selezione della collection eventi
        $this->collection = $mongoDB->selectCollection("eventi");
    }

    // Inserimento nel db log dell'evento con timestamp
    public function logEvento(string $testo): void {
        $this->collection->insertOne(["evento" => $testo, "timestamp" => date("d/m/Y H:i:s")]);
    }
}
?>
