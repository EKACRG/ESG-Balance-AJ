<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Dashboard Amministratore</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <h1>Home Amministratore</h1>

        <p>Benvenuto <?= $_SESSION["username"] ?>!</p>

        <a href="/stats/index" class="btn-stats">
            Statistiche
        </a>
        <a href="/amministratore/indicatoriESG">Inserisci Indicatori ESG</a>
        <a href="/amministratore/template">Inserisci Template Bilancio</a>
        <a href="/amministratore/associazione">Associa Revisore</a>

        <hr>

        <a href="/stats/index">Visualizza Statistiche</a>
        <a href="/autenticazione/logout">Logout</a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
