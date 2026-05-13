<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O8 - Registrazione Azienda</title>
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
    <a href="/Responsabile" class="btn-back">⬅ Torna alla Dashboard</a>
    <h2>Registra una Nuova Azienda</h2>

    <?php if (!empty($data['messaggio_successo'])): ?>
        <h3 style="color: green;"><?= $data['messaggio_successo'] ?></h3>
    <?php endif; ?>

    <?php if (!empty($data['messaggio_errore'])): ?>
        <h3 style="color: red;"><?= $data['messaggio_errore'] ?></h3>
    <?php endif; ?>

    <!-- L'attributo enctype è fondamentale per inviare il Logo! -->
    <form action="" method="POST" enctype="multipart/form-data">
        
        <fieldset>
            <legend>Dati Responsabile</legend>
            <label>Il tuo Username:</label><br>
            <input type="text" name="username_responsabile" required><br><br>
        </fieldset><br>

        <fieldset>
            <legend>Dati Aziendali</legend>
            <label>Ragione Sociale:</label><br>
            <input type="text" name="ragione_sociale" placeholder="Es. Rossi & Co. S.p.A." required><br><br>

            <label>Nome Commerciale:</label><br>
            <input type="text" name="nome_azienda" required><br><br>

            <label>Partita IVA:</label><br>
            <input type="text" name="partita_iva" required><br><br>

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