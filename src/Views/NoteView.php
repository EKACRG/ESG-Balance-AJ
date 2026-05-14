<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O6 - Inserisci Nota a Bilancio</title>
</head>
<body>
    <a href="/Revisore" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Aggiungi una nota su una voce di bilancio</h2>

    <?php if (!empty($data['messaggio_successo'])): ?>
        <h3 style="color: green;"><?= $data['messaggio_successo'] ?></h3>
    <?php endif; ?>

    <?php if (!empty($data['messaggio_errore'])): ?>
        <h3 style="color: red;"><?= $data['messaggio_errore'] ?></h3>
    <?php endif; ?>

    <form action="" method="POST">

        <!-- Dati del Revisore -->
        <label>Username Revisore:</label><br>
        <input type="text" name="username" required><br><br>

        <!-- Identificazione del Bilancio (Chiavi esterne) -->
        <fieldset>
            <legend>Dati del Bilancio da revisionare</legend>
            <label>Ragione Sociale Azienda:</label><br>
            <input type="text" name="ragione_sociale" placeholder="Es. TechCorp S.p.A." required><br><br>

            <label>Data Creazione Bilancio:</label><br>
            <input type="date" name="data_creazione" required><br><br>
        </fieldset><br>

        <!-- Identificazione della Voce Contabile -->
        <label>Nome Voce Contabile:</label><br>
        <input type="text" name="nome_voce" placeholder="Es. Ricavi vendite" required><br><br>

        <!-- Dati della Nota -->
        <fieldset>
            <legend>La tua Annotazione</legend>
            <label>Data della Nota:</label><br>
            <input type="date" name="data_nota" required><br><br>

            <label>Testo della Nota:</label><br>
            <textarea name="testo" rows="4" cols="50" placeholder="Scrivi qui il tuo commento..." required></textarea><br><br>
        </fieldset><br>

        <!-- Il 'name' del bottone deve corrispondere all'isset() del Controller -->
        <button type="submit" name="salva_nota">Salva Nota</button>
        
    </form> 
</body>
</html>