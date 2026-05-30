<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Template Bilancio</title>
    </head>
    <body>
        <h1>Inserimento Voce Contabile</h1>

        <?php if(isset($messaggio_errore)): ?>
            <p style="color: red;"><?= $messaggio_errore ?></p>
        <?php endif; ?>

        <?php if (!empty($messaggio_successo)): ?>
            <p style="color: green;"><?= $messaggio_successo ?></p>
        <?php endif; ?>

        <form method="POST" action="/amministratore/template">
            <label>Nome Voce Contabile:</label>
            <input type="text" name="voceContabile" required>

            <label>Descrizione:</label>
            <input type="text" name="descrizione" required>
            
            <button type="submit">Inserisci</button>
        </form>

        <a href="/amministratore/index">Torna alla dashboard</a>
    </body>
</html>
