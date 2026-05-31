<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Inserimento Giudizio Complessivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <a href="/Revisore" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Emetti il tuo Giudizio sul Bilancio Aziendale</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>

    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <form action="/revisore/giudizio" method="POST">
        
        <fieldset>
            <legend>Identificativi della Revisione</legend>
            <label>Bilancio da valutare:</label><br>
            <select name="bilancio_selezionato" required>
                <option value="">-- Scegli un bilancio --</option>
                <?php foreach ($bilanci_assegnati as $bilancio): ?>
                    <option value="<?= $bilancio['RagioneSociale'] . '|' . $bilancio['DataCreazione'] ?>">
                        <?= $bilancio['RagioneSociale'] ?> (<?= $bilancio['DataCreazione'] ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>
        </fieldset><br>

        <fieldset>
            <legend>Valutazione Finale</legend>
            <label>Data di emissione del Giudizio:</label><br>
            <input type="date" name="data_giudizio" required><br><br>

            <label>Esito:</label><br>
            <select name="esito" required>
                <option value="" disabled selected>-- Seleziona un esito --</option>
                <option value="approvazione">Approvazione</option>
                <option value="approvazione con rilievi">Approvazione con rilievi</option>
                <option value="respingimento">Respingimento</option>
            </select><br><br>

            <label>Note e Rilievi:</label><br>
            <textarea name="campo_rilievi" rows="4" cols="50" placeholder="Inserisci le motivazioni del giudizio..."></textarea><br><br>
        </fieldset><br>

        <button type="submit" name="salva_giudizio">Salva Giudizio Complessivo</button>
        
    </form> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>