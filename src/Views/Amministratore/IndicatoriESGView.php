<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Indicatori ESG</title>
    </head>
    <body>
        <h1>Inserimento Indicatore ESG</h1>

        <?php if(isset($messaggio_errore)): ?>
            <p style="color: red;"><?= $messaggio_errore ?></p>
        <?php endif; ?>

        <?php if (!empty($messaggio_successo)): ?>
            <p style="color: green;"><?= $messaggio_successo ?></p>
        <?php endif; ?>

        <form method="POST" action="/amministratore/indicatoriESG" enctype="multipart/form-data">
            <label>Nome Indicatore:</label>
            <input type="text" name="indicatore" required>

            <label>Immagine:</label>
            <input type="file" name="immagine" accept=".jpg,.jpeg,.png" required>

            <label>Rilevanza (0-10):</label>
            <input type="number" name="rilevanza" min="0" max="10" required>

            <label>Tipo Indicatore:</label>
            <select name="tipoIndicatore" id="tipoIndicatore" required>
                <option value="Altro">Altro</option>
                <option value="Ambientale">Ambientale</option>
                <option value="Sociale">Sociale</option>
            </select>

            <div id="ambientale" style="display: none;">
                <label>Codice Normativa:</label>
                <input type="text" name="codiceNormativa">
            </div>

            <div id="sociale" style="display: none;">
                <label>Ambito Sociale:</label>
                <input type="text" name="ambitoSociale">

                <label>Frequenza Rilevazione:</label>
                <input type="text" name="frequenzaRilevazione">
            </div>
            
            <button type="submit">Inserisci</button>
        </form>

        <a href="/amministratore/index">Torna alla dashboard</a>

        <script>
            const tipoIndicatore = document.getElementById("tipoIndicatore");
            const ambientale = document.getElementById("ambientale");
            const sociale = document.getElementById("sociale");
            
            tipoIndicatore.addEventListener("change", function() {
                ambientale.style.display = tipoIndicatore.value === "Ambientale" ? "block" : "none";
                sociale.style.display = tipoIndicatore.value === "Sociale" ? "block" : "none";
            });
        </script>
    </body>
</html>
