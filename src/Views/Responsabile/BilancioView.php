<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Creazione Bilancio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

    <div class="card shadow mx-auto" style="width: 580px;">
        <div class="card-header bg-dark text-white p-4">
            <h4 class="mb-1 fw-bold">ESG Balance</h4>
            <small class="opacity-75">Creazione Nuovo Bilancio di Esercizio</small>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($messaggio_successo)): ?>
                <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
            <?php endif; ?>
            <?php if (!empty($messaggio_errore)): ?>
                <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
            <?php endif; ?>

            <form action="/responsabile/bilancio" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Azienda</label>
                    <select name="ragione_sociale" class="form-select" required>
                        <option value="" disabled selected>— Scegli Azienda —</option>
                        <?php foreach ($aziende as $azienda): ?>
                            <option value="<?= $azienda['RagioneSociale'] ?>"><?= $azienda['Nome'] ?> (<?= $azienda['RagioneSociale'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Data di Creazione</label>
                    <input type="date" name="data_creazione" class="form-control"
                        value="<?= date('Y-m-d', strtotime('yesterday')) ?>"
                        max="<?= date('Y-m-d', strtotime('yesterday')) ?>" required>
                    <div class="form-text">Lo stato iniziale sarà impostato automaticamente su "bozza".</div>
                </div>

                <p class="fw-semibold border-top pt-3 mb-3">Voci Contabili</p>
                <?php foreach ($voci_template as $voce): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?= $voce['NomeVoceContabile'] ?></label>
                        <div class="form-text mb-1"><?= $voce['Descrizione'] ?></div>
                        <input type="number" step="0.01" name="voci[<?= $voce['NomeVoceContabile'] ?>]"
                            class="form-control" placeholder="0.00" required>
                    </div>
                <?php endforeach; ?>

                <button type="submit" name="salva_bilancio" class="btn btn-dark w-100 mt-2">Salva Bilancio</button>
            </form>

            <div class="text-center mt-3">
                <a href="/Responsabile" class="text-decoration-none small">← Torna alla dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
