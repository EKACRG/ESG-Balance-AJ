<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Giudizio Complessivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="card shadow" style="width: 540px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Emetti Giudizio sul Bilancio Aziendale</small>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($messaggio_successo)): ?>
                <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
            <?php endif; ?>
            <?php if (!empty($messaggio_errore)): ?>
                <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
            <?php endif; ?>

            <form action="/revisore/giudizio" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bilancio da valutare</label>
                    <select name="bilancio_selezionato" class="form-select" required>
                        <option value="">— Scegli un bilancio —</option>
                        <?php foreach ($bilanci_assegnati as $bilancio): ?>
                            <option value="<?= $bilancio['RagioneSociale'] . '|' . $bilancio['DataCreazione'] ?>">
                                <?= $bilancio['RagioneSociale'] ?> (<?= $bilancio['DataCreazione'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Data di emissione</label>
                    <input type="date" name="data_giudizio" class="form-control" max="<?= date('Y-m-d', strtotime('yesterday')) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Esito</label>
                    <select name="esito" class="form-select" required>
                        <option value="" disabled selected>— Seleziona un esito —</option>
                        <option value="approvazione">Approvazione</option>
                        <option value="approvazione con rilievi">Approvazione con rilievi</option>
                        <option value="respingimento">Respingimento</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Note e Rilievi</label>
                    <textarea name="campo_rilievi" class="form-control" rows="4"
                        placeholder="Inserisci le motivazioni del giudizio..."></textarea>
                </div>
                <button type="submit" name="salva_giudizio" class="btn btn-dark w-100">Salva Giudizio</button>
            </form>

            <div class="text-center mt-3">
                <a href="/Revisore" class="text-decoration-none small">← Torna alla dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
