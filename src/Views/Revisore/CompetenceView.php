<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O5 - Inserisci Competenza</title>
</head>
<body>
    <a href="/Revisore" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Aggiungi una nuova competenza</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>

    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <form action ="" method="POST">

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