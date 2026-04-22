<?php
    $conn = new mysqli("mysql", "aj", "esamebasi_aj_01", "esgbalance");

    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }

    // Estrazione dati O11: Totale Aziende
    $queryO11 = "SELECT * FROM NUM_AZIENDE"; 
    $risultatoO11 = $conn->query($queryO11);
    
    if ($risultatoO11 === false){
        echo "<p>Errore query O11: " . $conn->error . "</p>";
    } else {
        $rigaO11 = $risultatoO11->fetch_assoc();
        if ($rigaO11 === null){
            echo "<h1>Nessun dato disponibile sulle aziende</h1>";   
        } else {
            $totale_aziende = $rigaO11['num_aziende']; 
            echo "<h1>Il numero totale di aziende è: " . $totale_aziende . "</h1>";
        }
    }

    // Estrazione dati O12: Totale revisori ESG
    $queryO12 = "SELECT * FROM NUM_REVISORI_ESG";
    $risultatoO12 = $conn->query($queryO12);

    if ($risultatoO12 === false) {
        echo "<p>Errore query O12: " . $conn->error . "</p>";
    } else {
    $rigaO12 = $risultatoO12->fetch_assoc();
        if ($rigaO12 === null) {
            echo "<h2>Nessun dato disponibile sui revisori</h2>";
        } else {
            $totale_revisori = $rigaO12['num_revisori_esg'];
            echo "<h2>Il numero totale dei revisori esg è: " . $totale_revisori . "</h2>";
        }
    }

    // Estrazione dati O13: Azienda con il valore di affidabilità più alto
    $queryO13 = "SELECT * FROM AZIENDA_PIU_AFFIDABILE";
    $risultatoO13 = $conn->query($queryO13);

    if ($risultatoO13 === false) {
        echo "<p>Errore query O13: " . $conn->error . "</p>";
    } else {
        $rigaO13 = $risultatoO13->fetch_assoc();
        if ($rigaO13 === null) {
            echo "<h3>Nessuna azienda valutata al momento</h3>";
        } else {
            $azienda_top = $rigaO13['RagioneSociale'];
            $punteggio = $rigaO13['affidabilita']; 
            echo "<h3>L'azienda più affidabile è: " . $azienda_top . " con un punteggio del " . $punteggio . "%</h3>";
        }
    }

    // Estrazione dati O14: Classifica bilanci aziendali
    
    echo "<h2>Classifica dei Bilanci (Indicatori ESG)</h2>";

    $queryO14 = "SELECT * FROM CLASSIFICA_BILANCI"; 
    $risultatoO14 = $conn->query($queryO14);

    if ($risultatoO14 === false) {
        echo "<p>Errore query O14: " . $conn->error . "</p>";
    } else {
        if ($risultatoO14->num_rows === 0) {
            echo "<p>Nessun bilancio disponibile per la classifica.</p>";
        } else {
            echo "<table border='1'><tr><th>Azienda</th><th>Data Bilancio</th><th>Totale Indicatori</th></tr>";

            while($rigaO14 = $risultatoO14->fetch_assoc()){
                echo "<tr>";

                echo "<td>" . $rigaO14['RagioneSociale'] . "</td>";
                echo "<td>" . $rigaO14['DataCreazione'] . "</td>";
                echo "<td>" . $rigaO14['tot_indicatori'] . "</td>";

                echo "</tr>";           
            }

            echo "</table>";
            }
        }
?>
