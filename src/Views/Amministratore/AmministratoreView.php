<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Home Amministratore</title>
    </head>
    <body>
        <h1>Home Amministratore</h1>

        <p>Benvenuto <?= $_SESSION["username"] ?>!</p>

        <a href="/amministratore/indicatoriESG">Inserisci Indicatori ESG</a>
        <a href="/amministratore/template">Inserisci Template Bilancio</a>
        <a href="/amministratore/associazione">Associa Revisore</a>

        <hr>

        <a href="/stats/index">Visualizza Statistiche</a>
        <a href="/auth/logout">Logout</a>
    </body>
</html>
