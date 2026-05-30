<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O8 - Registrazione Azienda</title>
</head>
<body>
    <a href="/Responsabile" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Registra una Nuova Azienda</h2>

    <?php if (!empty($messaggio_successo)): ?>
        <h3 style="color: green;"><?= $messaggio_successo ?></h3>
    <?php endif; ?>

    <?php if (!empty($messaggio_errore)): ?>
        <h3 style="color: red;"><?= $messaggio_errore ?></h3>
    <?php endif; ?>

    <!-- L'attributo enctype è fondamentale per inviare il Logo! -->
    <form action="/responsabile/azienda" method="POST" enctype="multipart/form-data">
        
        <fieldset>
            <legend>Dati Aziendali</legend>
            <label>Ragione Sociale:</label><br>
            <input type="text" name="ragione_sociale" placeholder="Es. Rossi & Co. S.p.A." required><br><br>

            <label>Nome Commerciale:</label><br>
            <input type="text" name="nome_azienda" required><br><br>

            <label>Partita IVA:</label><br>
            <input
                type="text"
                name="partita_iva"
                maxlength="11"
                minlength="11"
                pattern="[0-9]{11}"
                title="La partita IVA deve contenere esattamente 11 cifre"
                required
            ><br><br>

            <label>Settore di Appartenenza:</label><br>
            <input type="text" name="settore" required><br><br>

            <label>Numero Dipendenti:</label><br>
            <input type="number" name="num_dipendenti" min="1" required><br><br>

            <label>Logo Aziendale (Solo Immagini):</label><br>
            <input type="file" name="logo" accept="image/png, image/jpeg, image/gif" required><br><br>
        </fieldset><br>

        <button type="submit" name="salva_azienda">Registra Azienda nel Sistema</button>
        
    </form> 
</body>
</html>