<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Dashboard Revisore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow" style="width: 480px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Dashboard Revisore</small>
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-4">Benvenuto, <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></strong>!</p>

            <div class="d-grid gap-2">
                <a href="/stats/index" class="btn btn-outline-dark">Visualizza Statistiche</a>
                <a href="/revisore/Competenze" class="btn btn-outline-secondary">Inserisci Nuova Competenza</a>

                <?php if ($sbloccaValutazioni): ?>
                    <a href="/revisore/Note" class="btn btn-outline-secondary">Inserisci Nota su Voce di Bilancio</a>
                    <a href="/revisore/Giudizio" class="btn btn-outline-secondary">Emetti Giudizio Complessivo</a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary" disabled>Inserisci Nota — Non disponibile</button>
                    <button class="btn btn-outline-secondary" disabled>Emetti Giudizio — Non disponibile</button>
                    <small class="text-danger text-center">* Nessun bilancio assegnato al momento dall'Amministratore</small>
                <?php endif; ?>
            </div>

            <hr>
            <a href="/autenticazione/logout" class="btn btn-outline-danger w-100">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
