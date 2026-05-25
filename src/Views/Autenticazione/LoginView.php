<!DOCTYPE html>
<html>
    <head>
        <title>ESG Balance - Login</title>
    </head>
    <body>
        <h1>Login</h1>

        <?php if(isset($errore)): ?>
            <p style="color: red;"><?= $errore ?></p>
        <?php endif; ?>

        <form method="POST" action="/autenticazione/login">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Accedi</button>
        </form>

        <p>Non hai un account? <a href="/autenticazione/signup">Registrati</a></p>
    </body>
</html>
