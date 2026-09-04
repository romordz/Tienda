<?php require __DIR__ . '/../php/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="<?= urlFor('css/SLoRe.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
   
    <div class="register-container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php
                    if ($_GET['error'] === 'wrong_password') {
                        echo 'Contraseña incorrecta.';
                    } elseif ($_GET['error'] === 'user_not_found') {
                        echo 'El usuario no existe.';
                    }
                ?>
            </div>
        <?php endif; ?>
        <form action="<?= urlFor('php/sesion/process_login.php') ?><?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST">
            <div class="form-group">
                <label for="user">Correo Electronico:</label>
                <input type="user" id="user" name="user" required>
                <span id="email-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <span id="password-error" class="error-message"></span>
            </div>

            <button type="submit" class="btn-register">Iniciar Sesión</button>

            <div class="login-link">
                <p>¿No tienes cuenta? <a href="<?= urlFor('pantallas/Registro.php') ?>">Regístrate aquí</a></p>
            </div>
        </form>
    </div>
    
    <script src="<?= urlFor('js/JLogin.js') ?>"></script>
</body>
</html>
