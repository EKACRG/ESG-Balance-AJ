<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Registrazione</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">

        <div class="card shadow" style="width: 480px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Crea il tuo account</small>
            </div>
            <div class="card-body p-4">

                <?php if(isset($messaggio_errore)): ?>
                    <div class="alert alert-danger py-2"><?= $messaggio_errore ?></div>
                <?php endif; ?>

                <form method="POST" action="/autenticazione/signup" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Inserisci username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Inserisci password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Codice Fiscale</label>
                        <input type="text" name="cf" class="form-control text-uppercase"
                            maxlength="16" minlength="16" pattern="[A-Z0-9]{16}"
                            placeholder="16 caratteri alfanumerici"
                            title="Il codice fiscale deve contenere esattamente 16 caratteri alfanumerici" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data di nascita</label>
                        <input type="date" name="dataNascita" class="form-control" max="<?= date('Y-m-d', strtotime('yesterday')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Luogo di nascita</label>
                        <input type="text" name="luogoNascita" class="form-control" placeholder="Es. Roma" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Inserisci email" required>
                    </div>
                    <div id="emailAggiuntive" class="mb-2"></div>
                    <button type="button" id="aggiungiEmail" class="btn btn-outline-secondary btn-sm mb-3">+ Aggiungi email</button>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo utente</label>
                        <select name="tipoUtente" id="tipoUtente" class="form-select">
                            <option value="amministratore">Amministratore</option>
                            <option value="revisore">Revisore</option>
                            <option value="responsabile">Responsabile</option>
                        </select>
                    </div>
                    <div id="cv" class="mb-3 d-none">
                        <label class="form-label fw-semibold">Curriculum vitae (PDF)</label>
                        <input type="file" name="curriculumVitae" class="form-control" accept=".pdf">
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mt-1">Registrati</button>
                </form>

                <p class="text-center text-muted mt-4 mb-0 small">
                    Hai già un account? <a href="/autenticazione/login" class="text-decoration-none fw-semibold">Accedi</a>
                </p>
            </div>
        </div>

        <script>
            const tipoUtente = document.getElementById("tipoUtente");
            const cv = document.getElementById("cv");
            const aggiungiEmail = document.getElementById("aggiungiEmail");

            tipoUtente.addEventListener("change", function() {
                cv.classList.toggle("d-none", this.value !== "responsabile");
            });

            aggiungiEmail.addEventListener("click", function() {
                const emailAggiuntive = document.getElementById("emailAggiuntive");
                const input = document.createElement("input");
                input.type = "email";
                input.name = "emailAggiuntive[]";
                input.className = "form-control mb-2";
                input.placeholder = "Email aggiuntiva";
                emailAggiuntive.appendChild(input);
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
