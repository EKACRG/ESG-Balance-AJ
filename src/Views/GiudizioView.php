<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O7 - Inserimento Giudizio Complessivo</title>
    <style>
    .btn-back {
            display: inline-block;
            padding: 10px 15px;
            background-color: #6c757d; /* Grigio neutro per non confondersi col tasto Salva */
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px; /* Spazio per staccarlo dal form sottostante */
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <a href="/Revisore" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Emetti il tuo Giudizio sul Bilancio Aziendale</h2>

    <?php if (!empty($data['messaggio_successo'])): ?>
        <h3 style="color: green;"><?= $data['messaggio_successo'] ?></h3>
    <?php endif; ?>

    <?php if (!empty($data['messaggio_errore'])): ?>
        <h3 style="color: red;"><?= $data['messaggio_errore'] ?></h3>
    <?php endif; ?>

    <form action="" method="POST">
        
        <fieldset>
            <legend>Identificativi della Revisione</legend>
            <label>Il tuo Username (Revisore):</label><br>
            <input type="text" name="username_revisore" required><br><br>

            <label>Ragione Sociale dell'Azienda valutata:</label><br>
            <input type="text" name="ragione_sociale" required><br><br>

            <label>Data di Creazione del Bilancio:</label><br>
            <input type="date" name="data_creazione" required><br><br>
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
</body>
</html>