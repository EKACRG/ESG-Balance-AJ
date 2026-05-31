<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Indicatori ESG</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

        <div class="card shadow" style="width: 500px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Inserimento Indicatore ESG</small>
            </div>
            <div class="card-body p-4">

                <?php if(isset($messaggio_errore)): ?>
                    <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
                <?php endif; ?>
                <?php if (!empty($messaggio_successo)): ?>
                    <div class="alert alert-success py-2"><?= $messaggio_successo ?></div>
                <?php endif; ?>

                <form method="POST" action="/amministratore/indicatoriESG" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Indicatore</label>
                        <input type="text" name="indicatore" class="form-control" placeholder="Es. Emissioni CO2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Immagine</label>
                        <input type="file" name="immagine" class="form-control" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rilevanza (0–10)</label>
                        <input type="number" name="rilevanza" class="form-control" min="0" max="10"
                            title="La rilevanza deve essere un numero compreso tra 0 e 10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo Indicatore</label>
                        <select name="tipoIndicatore" id="tipoIndicatore" class="form-select" required>
                            <option value="Altro">Altro</option>
                            <option value="Ambientale">Ambientale</option>
                            <option value="Sociale">Sociale</option>
                        </select>
                    </div>
                    <div id="ambientale" class="mb-3 d-none">
                        <label class="form-label fw-semibold">Codice Normativa</label>
                        <input type="text" name="codiceNormativa" class="form-control" placeholder="Es. ISO 14001">
                    </div>
                    <div id="sociale" class="mb-3 d-none">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ambito Sociale</label>
                            <input type="text" name="ambitoSociale" class="form-control" placeholder="Es. Parità di genere">
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Frequenza Rilevazione</label>
                            <input type="text" name="frequenzaRilevazione" class="form-control" placeholder="Es. Annuale">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mt-2">Inserisci</button>
                </form>

                <div class="text-center mt-3">
                    <a href="/amministratore/index" class="text-decoration-none small">← Torna alla dashboard</a>
                </div>
            </div>
        </div>

        <script>
            const tipoIndicatore = document.getElementById("tipoIndicatore");
            const ambientale = document.getElementById("ambientale");
            const sociale = document.getElementById("sociale");

            tipoIndicatore.addEventListener("change", function() {
                ambientale.classList.toggle("d-none", this.value !== "Ambientale");
                sociale.classList.toggle("d-none", this.value !== "Sociale");
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
