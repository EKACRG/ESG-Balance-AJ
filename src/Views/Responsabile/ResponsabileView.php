<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Dashboard Responsabile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow" style="width: 480px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Dashboard Responsabile</small>
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-4">Benvenuto, <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></strong>!</p>

            <div class="d-grid gap-2">
                <a href="/stats/index" class="btn btn-outline-dark">Visualizza Statistiche</a>
                <a href="/responsabile/azienda" class="btn btn-outline-secondary">Registra nuova azienda</a>

                <?php if ($haAziende): ?>
                    <a href="/responsabile/Bilancio" class="btn btn-outline-secondary">Creazione nuovo bilancio</a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary" disabled>Creazione bilancio — Non disponibile</button>
                    <small class="text-danger text-center">* Registra prima almeno un'azienda</small>
                <?php endif; ?>

                <?php if ($haBilanci): ?>
                    <a href="/responsabile/esgIndicatore" class="btn btn-outline-secondary">Inserimento valori indicatori ESG</a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary" disabled>Inserimento valori ESG — Non disponibile</button>
                    <small class="text-danger text-center">* Crea prima un bilancio per sbloccare questa funzione</small>
                <?php endif; ?>
            </div>

            <hr>
            <a href="/autenticazione/logout" class="btn btn-outline-danger w-100">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
