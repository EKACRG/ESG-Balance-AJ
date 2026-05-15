<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Revisore ESG</title>
</head>
<body>
    <a href="/auth/logout" class="btn-logout">⬅ Esci - Logout</a>
    <!-- Stampiamo l'username in modo sicuro usando htmlspecialchars come consigliato dal manuale -->
    <h2>Benvenuto nella tua Area Riservata, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h2>
    <p>Seleziona l'operazione che desideri effettuare:</p>

    <div class="dashboard-menu">
        <a href="/Azienda" class="btn">
            Registra nuova azienda
        </a>
        
        <a href="/Bilancio" class="btn">
            Creazione e popolamento di un nuovo bilancio
        </a>
        
        <!-- LOGICA IF PER IL TERZO BOTTONE -->
        <?php if ($bottoneSbloccato): ?>
            <a href="/Esg" class="btn">
                Inserimento valori degli indicatori ESG
            </a>
        <?php else: ?>
            <a href="#" class="btn btn-disabled">
                Inserimento valori degli indicatori ESG - Non disponibile
            </a>
            <!-- Aggiungiamo il messaggio ben visibile sotto il bottone -->
            <small style="color: #d9534f; margin-top: -10px; font-weight: bold;">
                * Crea prima un bilancio per sbloccare questa funzione
            </small>
        <?php endif; ?>
    </div>
</body>
</html>