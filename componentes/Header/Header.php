<?php
if (!function_exists('urlFor')) {
    require_once __DIR__ . '/../../php/config.php';
}
?>

<head>
    <link rel="stylesheet" href="<?= urlFor('componentes/Header/SHeader.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1><?php echo $page_title ?? 'Tienda'; ?></h1>
    <nav>
        <ul>
            <li><a href="<?= urlFor('pantallas/Principal.php') ?>">Inicio</a></li>
            <li><a href="<?= urlFor('pantallas/Productos.php') ?>">productos</a></li>
            <li><a href="<?= urlFor('pantallas/Categorias.php') ?>">Categorías</a></li>
            <li><a href="<?= urlFor('pantallas/carrito.php') ?>" onclick="return checkSession('<?= urlFor('pantallas/carrito.php') ?>');">Carrito</a></li>
            <li><a href="<?= urlFor('php/sesion/cerrar_sesion.php') ?>">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <div class="profile-container">
        <div class="user-profile" onclick="toggleDropdown(event)">
            <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $_SESSION['avatar'] :  urlFor('/Recursos/default.jpg'); ?>"
                alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <p class="profile-name">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                </p>
                <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
            </div>
        </div>
        <div id="profile-dropdown" class="profile-dropdown">
            <a href="<?= urlFor('pantallas/Perfil.php') ?>" onclick="return checkSession('<?= urlFor('pantallas/Perfil.php') ?>');">Mi perfil</a>
            <a href="<?= urlFor('pantallas/mensajes.php') ?>" onclick="return checkSession('<?= urlFor('pantallas/mensajes.php') ?>');">Mensajes</a>
            <a href="<?= urlFor('php/sesion/cerrar_sesion.php') ?>">Mensajes</a>
        </div>
    </div>
</header>

<script src="<?= urlFor('js/sessionCheck.js') ?>"></script>