<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Inserimento Competenza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow" style="width: 480px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Aggiungi una nuova competenza</small>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($messaggio_successo)): ?>
                <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
            <?php endif; ?>
            <?php if (!empty($messaggio_errore)): ?>
                <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
            <?php endif; ?>

            <form action="/revisore/competenze" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome Competenza</label>
                    <input type="text" name="nome_competenza" class="form-control" placeholder="Es. Sostenibilità" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Livello (0–5)</label>
                    <input type="number" name="livello" class="form-control" min="0" max="5" required>
                </div>
                <button type="submit" name="salva_competenza" class="btn btn-dark w-100">Salva Competenza</button>
            </form>

            <div class="text-center mt-3">
                <a href="/Revisore" class="text-decoration-none small">← Torna alla dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
