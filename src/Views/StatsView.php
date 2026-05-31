<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Statistiche</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light py-5">

        <div class="card shadow mx-auto" style="width: 620px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-0 fw-bold">Statistiche Piattaforma</h4>
            </div>
            <div class="card-body p-4">

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="card text-center border-0 bg-white shadow-sm p-3">
                            <div class="fs-2 fw-bold">
                                <?= $data["num_aziende"] ? $data["num_aziende"]["num_aziende"] : "—" ?>
                            </div>
                            <small class="text-muted">Aziende registrate</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card text-center border-0 bg-white shadow-sm p-3">
                            <div class="fs-2 fw-bold">
                                <?= $data["num_revisori"] ? $data["num_revisori"]["num_revisori_esg"] : "—" ?>
                            </div>
                            <small class="text-muted">Revisori ESG</small>
                        </div>
                    </div>
                </div>

                <?php if($data["azienda_piu_affidabile"]): ?>
                    <div class="alert alert-success py-2 mb-4">
                        <strong>Azienda più affidabile:</strong>
                        <?= $data["azienda_piu_affidabile"]["RagioneSociale"] ?>
                        (<?= $data["azienda_piu_affidabile"]["affidabilita"] ?>%)
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary py-2 mb-4">Nessun dato sull'affidabilità delle aziende.</div>
                <?php endif; ?>

                <h6 class="fw-bold mb-3">Classifica Bilanci</h6>
                <?php if(!empty($data["classifica_bilanci"])): ?>
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Azienda</th>
                                <th>Data Bilancio</th>
                                <th class="text-center">Totale Indicatori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data["classifica_bilanci"] as $riga): ?>
                                <tr>
                                    <td><?= $riga["RagioneSociale"] ?></td>
                                    <td><?= $riga["DataCreazione"] ?></td>
                                    <td class="text-center"><?= $riga["tot_indicatori"] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Nessun dato disponibile sulla classifica dei bilanci.</p>
                <?php endif; ?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
