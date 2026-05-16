<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Errore</title>
    </head>
    <body>
        <h1>Si è verificato un errore...</h1>
        <h2><?= isset($errore) ? $errore : "Errore sconosciuto" ?></h2>
        <a href="/<?= $_SESSION["tipo_utente"] ?>/index">Torna alla home</a>
    </body>
</html>
