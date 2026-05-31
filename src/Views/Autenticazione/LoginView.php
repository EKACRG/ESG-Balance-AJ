<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

        <div class="card shadow" style="width: 400px;">
            <div class="card-header bg-dark text-white p-4">
                <h4 class="mb-1 fw-bold">ESG Balance</h4>
                <small class="opacity-75">Accedi alla tua area riservata</small>
            </div>
            <div class="card-body p-4">

                <?php if(isset($messaggio_errore)): ?>
                    <div class="alert alert-danger py-2" role="alert"><?= $messaggio_errore ?></div>
                <?php endif; ?>

                <form method="POST" action="/autenticazione/login">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Inserisci username" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Inserisci password" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Accedi</button>
                </form>

                <p class="text-center text-muted mt-4 mb-0 small">
                    Non hai un account? <a href="/autenticazione/signup" class="text-decoration-none fw-semibold">Registrati</a>
                </p>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
