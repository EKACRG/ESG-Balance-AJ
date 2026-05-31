<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Errore</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

        <div class="card shadow text-center" style="width: 480px;">
            <div class="card-header bg-danger text-white p-4">
                <h4 class="mb-0 fw-bold">Si è verificato un errore</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4"><?= isset($messaggio_errore) ? $messaggio_errore : "Errore sconosciuto" ?></p>
                <a href="javascript:history.back()" class="btn btn-outline-secondary">← Torna indietro</a>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
