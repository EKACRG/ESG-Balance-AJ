<?php
$mongoUsername = $_ENV['MONGO_INITDB_ROOT_USERNAME'];
$mongoPassword = $_ENV['MONGO_INITDB_ROOT_PASSWORD'];
$mongoDatabase = $_ENV['MONGO_INITDB_DATABASE'];

$mongoClient = new \MongoDB\Client("mongodb://$mongoUsername:$mongoPassword@mongodb:27017");
$mongoDB = $mongoClient->selectDatabase("esgbalance");
?>
