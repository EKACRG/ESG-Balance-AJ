<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Inserisci Nota a Bilancio</title>
</head>
<body>
    <a href="/Revisore" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Aggiungi una nota su una voce di bilancio</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>

    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <form action="/revisore/note" method="POST">

        <!-- Identificazione del Bilancio-->
        <fieldset>
            <legend>Dati del Bilancio da revisionare</legend>
             <div class="mb-3">
                <label class="form-label">Seleziona il Bilancio:</label>
                <select name="bilancio_selezionato" class="form-select" required>
                    <option value="">-- Scegli un bilancio --</option>
                    <?php foreach ($bilanci_assegnati as $bilancio): ?>
                        <!-- Mostriamo all'utente Ragione Sociale e Data, passiamo al server la Ragione Sociale -->
                        <option value="<?= $bilancio['RagioneSociale'] . '|' . $bilancio['DataCreazione'] ?>">
                            <?= $bilancio['RagioneSociale'] ?> (del <?= $bilancio['DataCreazione'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset><br>

        <!-- Identificazione della Voce Contabile -->
        <div class="mb-3">
            <label class="form-label">Voce Contabile:</label>
            <select name="nome_voce" class="form-select" required>
                <option value="">-- Scegli la voce --</option>
                <?php foreach ($voci_contabili as $voce): ?>
                    <option value="<?= $voce['NomeVoceContabile'] ?>"><?= $voce['NomeVoceContabile'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Dati della Nota -->
        <fieldset>
            <legend>La tua Annotazione</legend>
            <label>Data della Nota:</label><br>
            <input type="date" name="data_nota" required><br><br>

            <label>Testo della Nota:</label><br>
            <textarea name="testo" rows="4" cols="50" placeholder="Scrivi qui il tuo commento..." required></textarea><br><br>
        </fieldset><br>

        <!-- Il 'name' del bottone deve corrispondere all'isset() del Controller -->
        <button type="submit" name="salva_nota">Salva Nota</button>
        
    </form> 
</body>
</html>