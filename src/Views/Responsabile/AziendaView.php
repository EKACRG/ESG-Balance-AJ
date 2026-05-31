<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Registrazione Azienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="card shadow" style="width: 540px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Registra una Nuova Azienda</small>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($messaggio_successo)): ?>
                <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
            <?php endif; ?>
            <?php if (!empty($messaggio_errore)): ?>
                <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
            <?php endif; ?>

            <form action="/responsabile/azienda" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ragione Sociale</label>
                    <input type="text" name="ragione_sociale" class="form-control" placeholder="Es. Rossi & Co. S.p.A." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome Commerciale</label>
                    <input type="text" name="nome_azienda" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Partita IVA</label>
                    <input type="text" name="partita_iva" class="form-control"
                        maxlength="11" minlength="11" pattern="[0-9]{11}"
                        placeholder="11 cifre numeriche"
                        title="La partita IVA deve contenere esattamente 11 cifre" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Settore di Appartenenza</label>
                    <input type="text" name="settore" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Numero Dipendenti</label>
                    <input type="number" name="num_dipendenti" class="form-control" min="1" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Logo Aziendale</label>
                    <input type="file" name="logo" class="form-control" accept="image/png, image/jpeg, image/gif" required>
                </div>
                <button type="submit" name="salva_azienda" class="btn btn-dark w-100">Registra Azienda</button>
            </form>

            <div class="text-center mt-3">
                <a href="/Responsabile" class="text-decoration-none small">← Torna alla dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
