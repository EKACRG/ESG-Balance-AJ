<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Associazione Revisore</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <h1>Associazione Revisore ESG a Bilancio</h1>

        <?php if(isset($messaggio_errore)): ?>
            <p style="color: red;"><?= $messaggio_errore ?></p>
        <?php endif; ?>

        <?php if (!empty($messaggio_successo)): ?>
            <p style="color: green;"><?= $messaggio_successo ?></p>
        <?php endif; ?>

        <form method="POST" action="/amministratore/associazione">
            <label>Revisore ESG:</label>
            <select name="usernameRevisore" required>
                <?php foreach($revisori as $revisore): ?>
                    <option value="<?= $revisore["Username"] ?>">
                        <?= $revisore["Username"] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Bilancio:</label>
            <select name="bilancio" required>
                <?php foreach($bilanci as $bilancio): ?>
                    <option value="<?= $bilancio["RagioneSociale"] ?>|<?= $bilancio["DataCreazione"] ?>">
                        <?= $bilancio["RagioneSociale"] ?> -
                        <?= $bilancio["DataCreazione"] ?> -
                        <?= $bilancio["Stato"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Associa</button>
        </form>

        <a href="/amministratore/index">Torna alla dashboard</a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
