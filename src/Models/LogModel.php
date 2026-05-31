<?php
namespace Models;

class LogModel {
    private $collection;

    public function __construct() {
        require_once BASE_PATH . '/vendor/autoload.php';
        include __DIR__ . "/../config/mongo.php";
        $this->collection = $mongoDB->selectCollection("eventi");
    }

    public function logEvento(string $testo): void {
        $this->collection->insertOne(["evento" => $testo, "timestamp" => date("d/m/Y H:i:s")]);
    }
}
?>
