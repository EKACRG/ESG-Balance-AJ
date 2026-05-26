<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O10 - Associa Indicatori ESG</title>
</head>
<body>
    <a href="/Responsabile" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Collega Indicatore ESG a Voce di Bilancio</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>
    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Scegli l'Indicatore ESG:</label><br>
        <select name="nome_indicatore" required>
            <option value="" disabled selected>-- Seleziona --</option>
            <?php foreach ($indicatori as $ind): ?>
                <option value="<?= $ind['NomeIndicatore'] ?>"><?= $ind['NomeIndicatore'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Scegli la Voce del Bilancio da valorizzare:</label><br>
        <select name="voce_bilancio_keys" required>
            <option value="" disabled selected>-- Seleziona Voce / Azienda / Data --</option>
            <?php foreach ($voci_bilancio as $voce): ?>
                <!-- Uniamo le tre chiavi con il separatore | -->
                <option value="<?= $voce['NomeVoceBilancio'] ?>|<?= $voce['RagioneSociale'] ?>|<?= $voce['DataCreazione'] ?>">
                    <?= $voce['NomeVoceBilancio'] ?> (<?= $voce['RagioneSociale'] ?> - <?= $voce['DataCreazione'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Valore Rilevato:</label><br>
        <input type="number" step="0.01" name="valore" required><br><br>

        <label>Fonte del dato:</label><br>
        <input type="text" name="fonte" required><br><br>

        <label>Data Rilevazione:</label><br>
        <input type="date" name="data_rilevazione" required><br><br>

        <button type="submit" name="salva_esg">Salva Dati ESG</button>
    </form>
</body>
</html>
