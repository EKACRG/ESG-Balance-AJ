<?php
    $conn = new mysqli("mysql", "aj", "esamebasi_aj_01", "esgbalance");

    if ($conn->connect_error) {
        echo "MySQL - " . $conn->connect_error;
    }
    else {
        echo "MySQL connesso";
    }
?>
