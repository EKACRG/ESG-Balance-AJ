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
        <a href="/Competence" class="btn">
            Inserisci Nuova Competenza
        </a>
        
        <!-- LOGICA IF PER LE VALUTAZIONI -->
        <?php if ($sbloccaValutazioni): ?>
            <!-- Se ha bilanci assegnati, bottoni blu cliccabili -->
            <a href="/Note" class="btn">
                Inserisci Nota su Voce di Bilancio
            </a>
            
            <a href="/Giudizio" class="btn">
                Emetti Giudizio Complessivo
            </a>
        <?php else: ?>
            <!-- Se non ha bilanci, bottoni grigi bloccati -->
            <a href="#" class="btn btn-disabled">
                Inserisci Nota su Voce di Bilancio - Non disponibile
            </a>
            
            <a href="#" class="btn btn-disabled">
                Emetti Giudizio Complessivo - Non disponibile
            </a>
            <!-- Messaggio di avviso per il revisore -->
            <small style="color: #d9534f; margin-top: -10px; font-weight: bold;">
                * Nessun bilancio assegnato al momento dall'Amministratore
            </small>
        <?php endif; ?>
    </div>
</body>
</html>