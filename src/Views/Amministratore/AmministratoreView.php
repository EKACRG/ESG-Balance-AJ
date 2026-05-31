<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Dashboard Amministratore</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

        <div class="card shadow" style="width: 480px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Dashboard Amministratore</small>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Benvenuto, <strong><?= $_SESSION["username"] ?></strong>!</p>

                <div class="d-grid gap-2">
                    <a href="/stats/index" class="btn btn-outline-dark">Visualizza Statistiche</a>
                    <a href="/amministratore/indicatoriESG" class="btn btn-outline-secondary">Inserisci Indicatori ESG</a>
                    <a href="/amministratore/template" class="btn btn-outline-secondary">Inserisci Template Bilancio</a>
                    <a href="/amministratore/associazione" class="btn btn-outline-secondary">Associa Revisore a Bilancio</a>
                </div>

                <hr>
                <a href="/autenticazione/logout" class="btn btn-outline-danger w-100">Logout</a>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
