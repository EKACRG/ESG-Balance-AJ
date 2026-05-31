<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Template Bilancio</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

        <div class="card shadow" style="width: 480px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Inserimento Voce Contabile</small>
            </div>
            <div class="card-body p-4">

                <?php if(isset($messaggio_errore)): ?>
                    <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
                <?php endif; ?>
                <?php if (!empty($messaggio_successo)): ?>
                    <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
                <?php endif; ?>

                <form method="POST" action="/amministratore/template">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Voce Contabile</label>
                        <input type="text" name="voceContabile" class="form-control" placeholder="Es. Ricavi operativi" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Descrizione</label>
                        <input type="text" name="descrizione" class="form-control" placeholder="Breve descrizione" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Inserisci</button>
                </form>

                <div class="text-center mt-3">
                    <a href="/amministratore/index" class="text-decoration-none small">← Torna alla dashboard</a>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
