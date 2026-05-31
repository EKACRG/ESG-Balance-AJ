<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>ESG Balance - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <h1>Login</h1>

        <?php if(isset($messaggio_errore)): ?>
            <p style="color: red;"><?= $messaggio_errore ?></p>
        <?php endif; ?>

        <form method="POST" action="/autenticazione/login">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Accedi</button>
        </form>

        <p>Non hai un account? <a href="/autenticazione/signup">Registrati</a></p>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
