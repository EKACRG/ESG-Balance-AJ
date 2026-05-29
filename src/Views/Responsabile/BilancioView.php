<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O9 - Crea Bilancio</title>
</head>
<body>
    <a href="/Responsabile" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Creazione Nuovo Bilancio di Esercizio</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>
    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <form action="/responsabile/bilancio" method="POST">
        <fieldset>
            <legend>Dati del Bilancio</legend>
            <label>Seleziona la tua Azienda:</label><br>
            <select name="ragione_sociale" required>
                <option value="" disabled selected>-- Scegli Azienda --</option>
                <?php foreach ($aziende as $azienda): ?>
                    <option value="<?= $azienda['RagioneSociale'] ?>"><?= $azienda['Nome'] ?> (<?= $azienda['RagioneSociale'] ?>)</option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Data di Creazione:</label><br>
            <!-- Precompilato con la data odierna come buona pratica per l'utente -->
            <input type="date" name="data_creazione" value="<?= date('Y-m-d') ?>" required><br><br>
            <p><em>Lo stato iniziale sarà impostato automaticamente su "bozza" in ottemperanza ai vincoli di sistema.</em></p>
        </fieldset><br>

        <fieldset>
            <legend>Popolamento Voci Contabili (Template)</legend>
            <!-- Generazione dinamica delle voci provenienti dal database -->
            <?php foreach ($voci_template as $voce): ?>
                <label>
                    <strong><?= $voce['NomeVoceContabile'] ?></strong><br>
                    <small><?= $voce['Descrizione'] ?></small>
                </label><br>
                <input type="number" step="0.01" name="voci[<?= $voce['NomeVoceContabile'] ?>]" placeholder="0.00" required><br><br>
            <?php endforeach; ?>
        </fieldset><br>

        <button type="submit" name="salva_bilancio">Salva Bilancio</button>
    </form>
</body>
</html>