<?php
$basePath = preg_replace('#/(?:pantallas|php)(?:/.*)?$#', '', $_SERVER['PHP_SELF'] ?? '/');
$basePath = $basePath ?: '';
?>

<head>
    <link rel="stylesheet" href="<?= $basePath ?>/componentes/Header/SHeader.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1><?php echo $page_title ?? 'Tienda'; ?></h1>
    <nav>
        <ul>
            <li><a href="<?= $basePath ?>/pantallas/Principal.php">Inicio</a></li>
            <li><a href="<?= $basePath ?>/pantallas/Productos.php">productos</a></li>
            <li><a href="<?= $basePath ?>/pantallas/Categorias.php">Categorías</a></li>
            <li><a href="<?= $basePath ?>/pantallas/carrito.php" onclick="return checkSession('<?= $basePath ?>/pantallas/carrito.php');">Carrito</a></li>
            <li><a href="<?= $basePath ?>/php/sesion/cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <div class="profile-container">
        <div class="user-profile" onclick="toggleDropdown(event)">
            <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : $basePath . '/Recursos/default.jpg'; ?>"
                alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <p class="profile-name">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                </p>
                <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
            </div>
        </div>
        <div id="profile-dropdown" class="profile-dropdown">
            <a href="<?= $basePath ?>/pantallas/Perfil.php" onclick="return checkSession('<?= $basePath ?>/pantallas/Perfil.php')">Revisar perfil</a>
            <a href="<?= $basePath ?>/pantallas/Mensajes.php" onclick="return checkSession('<?= $basePath ?>/pantallas/Mensajes.php')">mensajes</a>
            <a href="<?= $basePath ?>/php/sesion/cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </div>
</header>

<script src="<?= $basePath ?>/js/sessionCheck.js"></script>