<?php

    try{
        
        $conn = new mysqli("mysql", "aj", "esamebasi_aj_01", "esgbalance");

        // Estrazione dati O11: Totale Aziende
        $queryO11 = "SELECT * FROM NUM_AZIENDE"; 
        $risultatoO11 = $conn->query($queryO11);
        $rigaO11 = $risultatoO11->fetch_assoc();
        $totale_aziende = $rigaO11['num_aziende']; 

        echo "<h1>Il numero totale di aziende è: " . $totale_aziende . "</h1>";

        // Estrazione dati O12: Totale revisori ESG
        $queryO12 = "SELECT * FROM NUM_REVISORI_ESG";
        $risultatoO12 = $conn->query($queryO12);
        $rigaO12 = $risultatoO12->fetch_assoc();
        $totale_revisori = $rigaO12['num_revisori_esg'];

        echo "<h2>Il numero totale dei revisori esg è: " . $totale_revisori . "</h2>";


    } catch (mysqli_sql_exception $e){

        die("Errore del Database: " . $e->getMessage());
    }
?>
