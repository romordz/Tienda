<head>
    <link rel="stylesheet" href="SHeader.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<!-- <header>
        <h1>Bienvenido a Nuestra Tienda</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Productos.php">productos</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
            </ul>
        </nav>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Buscar productos...">
            <button id="search-button" onclick="realizarBusqueda()">Buscar</button>
        </div>
        <div class="profile-container">
            <div class="user-profile" onclick="toggleDropdown(event)">
                <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? 'data:image/jpeg;base64,' . $_SESSION['avatar'] : 'Recursos/default.jpg'; ?>"
                    alt="Avatar" class="profile-avatar">
                <div class="profile-info">
                    <p class="profile-name">
                        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                    </p>
                    <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
                </div>
            </div>
            <div id="profile-dropdown" class="profile-dropdown">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="Perfil.php" onclick="return checkSession('Perfil.php')">Revisar perfil</a>
                    <a href="Mensajes.php" onclick="return checkSession('Mensajes.php')">mensajes</a>
                    <a href="php/cerrar_sesion.php">Cerrar sesión</a>
                <?php else: ?>
                    <a href="Login.php">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header> -->

<header>
    <h1><?php echo $page_title ?? 'Tienda'; ?></h1>
    <nav>
        <ul>
            <li><a href="Principal.php">Inicio</a></li>
            <li><a href="Productos.php">productos</a></li>
            <li><a href="Categorias.php">Categorías</a></li>
            <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
            <li><a href="php/cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <div class="profile-container">
        <div class="user-profile" onclick="toggleDropdown(event)">
            <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'Recursos/default.jpg'; ?>"
                alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <p class="profile-name">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                </p>
                <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
            </div>
        </div>
        <div id="profile-dropdown" class="profile-dropdown">
            <a href="Perfil.php" onclick="return checkSession('../Perfil.php')">Revisar perfil</a>
            <a href="Mensajes.php" onclick="return checkSession('../Mensajes.php')">mensajes</a>
            <a href="php/cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </div>
</header>

<script src="js/sessionCheck.js"></script>