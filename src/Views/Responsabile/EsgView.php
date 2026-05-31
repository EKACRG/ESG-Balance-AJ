<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Associazione Indicatori ESG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="card shadow" style="width: 540px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Collega Indicatore ESG a Voce di Bilancio</small>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($messaggio_successo)): ?>
                <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
            <?php endif; ?>
            <?php if (!empty($messaggio_errore)): ?>
                <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
            <?php endif; ?>

            <form action="/responsabile/esgIndicatore" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Indicatore ESG</label>
                    <select name="nome_indicatore" class="form-select" required>
                        <option value="" disabled selected>— Seleziona —</option>
                        <?php foreach ($indicatori as $ind): ?>
                            <option value="<?= $ind['NomeIndicatore'] ?>"><?= $ind['NomeIndicatore'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Voce del Bilancio</label>
                    <select name="voce_bilancio_keys" class="form-select" required>
                        <option value="" disabled selected>— Seleziona Voce / Azienda / Data —</option>
                        <?php foreach ($voci_bilancio as $voce): ?>
                            <option value="<?= $voce['NomeVoceBilancio'] ?>|<?= $voce['RagioneSociale'] ?>|<?= $voce['DataCreazione'] ?>">
                                <?= $voce['NomeVoceBilancio'] ?> (<?= $voce['RagioneSociale'] ?> - <?= $voce['DataCreazione'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Valore Rilevato</label>
                    <input type="number" step="0.01" name="valore" class="form-control" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fonte del dato</label>
                    <input type="text" name="fonte" class="form-control" placeholder="Es. Rapporto interno 2024" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Data Rilevazione</label>
                    <input type="date" name="data_rilevazione" class="form-control" max="<?= date('Y-m-d', strtotime('yesterday')) ?>" required>
                </div>
                <button type="submit" name="salva_esg" class="btn btn-dark w-100">Salva Dati ESG</button>
            </form>

            <div class="text-center mt-3">
                <a href="/Responsabile" class="text-decoration-none small">← Torna alla dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
