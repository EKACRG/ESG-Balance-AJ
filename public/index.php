<?php

    try{
        
        $conn = new mysqli("mysql", "aj", "esamebasi_aj_01", "esgbalance");

        // Estrazione DAti O11: Totale Aziende
        $queryO11 = "SELECT * FROM NUM_AZIENDE"; 
        $risultatoO11 = $conn->query($queryO11);
        $rigaO11 = $risultatoO11->fetch_assoc();
        $totale_aziende = $rigaO11['num_aziende']; 

        echo "<h1>Il numero totale di aziende è: " . $totale_aziende . "</h1>";


    } catch (mysqli_sql_exception $e){

        die("Errore del Database: " . $e->getMessage());
    }
?>
