<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O5 - Inserisci Competenza</title>
</head>
<body>
    <h2>Aggiungi una nuova competenza (Revisore ESG)</h2>
    <form action ="" method="POST">

        <label>Username Revisore:</label><br>
        <input type="text" name="username_revisore" required><br><br>

        <label>Nome Competenza:</label><br>
        <input type="text" name="nome_competenza" placeholder="Es. Sostenibilità" required><br><br>

        <label>Livello (0-5):</label><br>
        <!-- Applichiamo il vincolo 1 direttamente nell'interfaccia -->
        <input type="number" name="livello" min="0" max="5" required><br><br>

        <!-- Il 'name' di questo bottone è il segnale che il PHP aspetta -->
        <button type="submit" name="salva_competenza">Salva Competenza</button>
        
    </form> 
</body>
</html>    

<?php
// Controlla se il form è stato inviato
if(isset($_POST['salva_competenza'])){

    // Connessione al database 
    $conn = new mysqli("mysql", "aj", "esamebasi_aj_01", "esgbalance");

    if ($conn->connect_error) {
        die("Errore di connessione: " . $conn->connect_error);
    }

    // Recupero dei dati digitati nel form
    $username = $_POST['username_revisore'];
    $competenza = $_POST['nome_competenza'];
    $livello = $_POST['livello'];

    $query05 = $conn->prepare("CALL InserimentoCompetenze(?,?,?)");
    $query05->bind_param("ssi", $username, $competenza, $livello);

    // TEST: Stampiamo a schermo se l'operazione è andata a buon fine o meno
    try{
        $query05->execute();
        echo "<h3 style='color:green;'>Operazione completata: Competenza salvata con successo!</h3>";
    } catch(mysqli_sql_exception $e){
        echo "<h3 style='color:red;'>Errore dal Database: " . $e->getMessage() . "</h3>";
    }
    

}