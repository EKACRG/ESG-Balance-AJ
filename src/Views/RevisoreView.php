<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Revisore ESG</title>
    <style>
        /* Aggiunto per centrare i testi (titolo e paragrafo) */
        body {
            text-align: center; 
            font-family: Arial, sans-serif;
        }
        
        .dashboard-menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
            /* margin: auto posiziona il blocco dei bottoni esattamente al centro della pagina */
            margin: 20px auto; 
        }
        
        .btn {
            display: inline-block;
            padding: 15px 20px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
            border: 1px solid #004494;
        }
        
        .btn:hover {
            background-color: #004494;
        }
         .btn-disabled {
            background-color: #cccccc;
            border: 1px solid #999999;
            color: #666666;
            pointer-events: none; /* Impedisce il click */
            cursor: not-allowed;
        }

        .btn-logout {
            position: absolute; /* Lo sgancia dal testo centrato */
            top: 20px;          /* Distanza dal soffitto della pagina */
            right: 20px; 
            padding: 10px 15px;
            background-color: #6c757d; /* Grigio neutro per non confondersi col tasto Salva */
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px; /* Spazio per staccarlo dal form sottostante */
        }
        .btn-logout:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <a href="/Logout" class="btn-logout">⬅ Esci - Logout</a>
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