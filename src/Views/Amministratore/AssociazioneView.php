<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Associazione Revisore</title>
    </head>
    <body>
        <h1>Associazione Revisore ESG a Bilancio</h1>

        <?php if(isset($errore)): ?>
            <p style="color: red;"><?= $errore ?></p>
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
            <select name="ragioneSociale" id="bilancio" required>
                <?php foreach($bilanci as $bilancio): ?>
                    <option value="<?= $bilancio["RagioneSociale"] ?>|<?= $bilancio["RagioneSociale"] ?>">
                        <?= $bilancio["RagioneSociale"] ?> -
                        <?= $bilancio["DataCreazione"] ?> -
                        <?= $bilancio["Stato"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Associa</button>
        </form>

        <a href="/amministratore/index">Torna alla dashboard</a>
    </body>
</html>
