<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ESG Balance - Dashboard Responsabile ESG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <a href="/autenticazione/logout" class="btn-logout">⬅ Esci - Logout</a>
    <!-- Stampiamo l'username in modo sicuro usando htmlspecialchars come consigliato dal manuale -->
    <h2>Benvenuto nella tua Area Riservata, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h2>
    <p>Seleziona l'operazione che desideri effettuare:</p>

    <div class="dashboard-menu">
        <a href="/stats/index" class="btn-stats">
            Statistiche
        </a>
        <a href="/responsabile/azienda" class="btn">
            Registra nuova azienda
        </a>
        
        <?php if ($haAziende): ?>
            <a href="/responsabile/Bilancio" class="btn">
                Creazione e popolamento di un nuovo bilancio
            </a>
        <?php else: ?>
            <a href="#" class="btn btn-disabled">
                Creazione bilancio e popolamento di un nuovo bilancio - Non disponibile
            </a>
            <small style="color:red;">
                * Registra prima almeno un'azienda
            </small>
        <?php endif; ?>
        
        <!-- Logica IF per bottone Indicatore ESG -->
        <?php if ($haBilanci): ?>
            <a href="/responsabile/esgIndicatore" class="btn">
                Inserimento valori degli indicatori ESG
            </a>
        <?php else: ?>
            <a href="#" class="btn btn-disabled">
                Inserimento valori degli indicatori ESG - Non disponibile
            </a>
            <!-- Aggiungiamo il messaggio ben visibile sotto il bottone -->
            <small style="color:red">
                * Crea prima un bilancio per sbloccare questa funzione
            </small>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>