<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Registrazione</title>
    </head>
    <body>
        <h1>Registrazione</h1>

        <?php if(isset($errore)): ?>
            <p style="color: red;"><?= $errore ?></p>
        <?php endif; ?>

        <form method="POST" action="/auth/signup" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Codice Fiscale:</label>
            <input type="text" name="cf" required>

            <label>Data di nascita:</label>
            <input type="date" name="dataNascita" required>

            <label>Luogo di nascita:</label>
            <input type="text" name="luogoNascita" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Tipo utente:</label>
            <select name="tipoUtente" id="tipoUtente">
                <option value="amministratore">Amministratore</option>
                <option value="revisore">Revisore</option>
                <option value="responsabile">Responsabile</option>
            </select>

            <!-- Visibile solo se viene selezionato tipo utente "responsabile" -->
            <div id="cv" style="display: none;">
                <label>Curriculum vitae (PDF):</label>
                <input type="file" name="curriculumVitae" accept=".pdf">
            </div>

            <button type="submit">Registrati</button>
        </form>

        <p>Hai già un account? <a href="/auth/login">Accedi</a></p>

        <script>
            const tipoUtente = document.getElementById("tipoUtente");
            const cv = document.getElementById("cv");

            tipoUtente.addEventListener("change", function() {
                cv.style.display = this.value === "responsabile" ? "block" : "none";
            });

        </script>
    </body>
</html>
