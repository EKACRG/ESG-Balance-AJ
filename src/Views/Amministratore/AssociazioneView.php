<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Associazione Revisore</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

        <div class="card shadow" style="width: 480px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Associazione Revisore ESG a Bilancio</small>
            </div>
            <div class="card-body p-4">

                <?php if(isset($messaggio_errore)): ?>
                    <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
                <?php endif; ?>
                <?php if (!empty($messaggio_successo)): ?>
                    <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
                <?php endif; ?>

                <form method="POST" action="/amministratore/associazione">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Revisore ESG</label>
                        <select name="usernameRevisore" class="form-select" required>
                            <?php foreach($revisori as $revisore): ?>
                                <option value="<?= $revisore["Username"] ?>"><?= $revisore["Username"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Bilancio</label>
                        <select name="bilancio" class="form-select" required>
                            <?php foreach($bilanci as $bilancio): ?>
                                <option value="<?= $bilancio["RagioneSociale"] ?>|<?= $bilancio["DataCreazione"] ?>">
                                    <?= $bilancio["RagioneSociale"] ?> — <?= $bilancio["DataCreazione"] ?> — <?= $bilancio["Stato"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Associa</button>
                </form>

                <div class="text-center mt-3">
                    <a href="/amministratore/index" class="text-decoration-none small">← Torna alla dashboard</a>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
