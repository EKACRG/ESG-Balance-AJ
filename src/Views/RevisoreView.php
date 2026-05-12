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
    </style>
</head>
<body>
    <!-- Stampiamo l'username in modo sicuro usando htmlspecialchars come consigliato dal manuale -->
    <h2>Benvenuto nella tua Area Riservata, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h2>
    <p>Seleziona l'operazione che desideri effettuare:</p>

    <div class="dashboard-menu">
        <a href="/Competence" class="btn">
            Inserisci Nuova Competenza
        </a>
        
        <a href="/Note" class="btn">
            Inserisci Nota su Voce di Bilancio
        </a>
        
        <a href="/Giudizio" class="btn">
            Emetti Giudizio Complessivo
        </a>
    </div>
</body>
</html>