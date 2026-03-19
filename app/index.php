<?php
    $conn = new mysqli("mysql", "aj", "esamebasi_aj", "esgbalance");

    if ($conn->connect_error) {
        echo "MySQL - " . $conn->connect_error;
    }
    else {
        echo "MySQL connesso";
    }
?>
