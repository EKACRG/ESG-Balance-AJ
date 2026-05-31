<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Statistiche</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <h1>Statistiche piattaforma</h1>

        <!-- Numero aziende -->
        <?php if($data["num_aziende"]): ?>
            <p>Numero totale di aziende: <?= $data["num_aziende"]["num_aziende"] ?></p>
        <?php else: ?>
            <p>Nessun dato disponibile sul numero di aziende.</p>
        <?php endif; ?>

        <!-- Numero revisori esg -->
        <?php if($data["num_revisori"]): ?>
            <p>Numero totale di revisori: <?= $data["num_revisori"]["num_revisori_esg"] ?></p>
        <?php else: ?>
            <p>Nessun dato disponibile sul numero di revisori ESG.</p>
        <?php endif; ?>

        <!-- Azienda più affidabile -->
        <?php if($data["azienda_piu_affidabile"]): ?>
            <p>Azienda più affidabile: <?= $data["azienda_piu_affidabile"]["RagioneSociale"] ?>
            (<?= $data["azienda_piu_affidabile"]["affidabilita"] ?>%)</p>
        <?php else: ?>
            <p>Nessun dato disponibile sull'affidabilità delle aziende.</p>
        <?php endif; ?>

        <!-- Classifica bilanci -->
        <?php if(!empty($data["classifica_bilanci"])): ?>
            <table border="1">
                <tr>
                    <th>Azienda</th>
                    <th>Data Bilancio</th>
                    <th>Totale Indicatori</th>
                </tr>
                <?php foreach($data["classifica_bilanci"] as $riga): ?>
                    <tr>
                        <td><?= $riga["RagioneSociale"] ?></td>
                        <td><?= $riga["DataCreazione"] ?></td>
                        <td><?= $riga["tot_indicatori"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nessun dato disponibile sulla classifica dei bilanci.</p>
        <?php endif; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
