<?php
require __DIR__ . '/../php/sesion/init.php';
require __DIR__ . '/../php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . urlFor('pantallas/Login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'])));
    exit();
}

include __DIR__ . '/../php/perfil/Perfil_process.php';

$session_user_id = $_SESSION['user_id'];
$profile_user_id = $_GET['id'] ?? $session_user_id;


$sql = "SELECT privacidad FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_user_id]);
$profile_privacy = $stmt->fetchColumn();
$is_private = $profile_privacy === 'privado' && $session_user_id != $profile_user_id;


$listas = [];
require __DIR__ . '/../php/listas/obtener_listas.php';
$page_title = "Detalle del Producto";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SPerfil.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <main>
        <section class="profile-section">
            <div class="profile-card">
                <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : $basePath . '/Recursos/default.jpg'; ?>"
                    alt="Avatar de Usuario" class="profile-avatar">
                <h2><?php echo isset($profile_username) ? $profile_username : 'Nombre del Usuario'; ?></h2>

                <?php if ($is_private): ?>
                    <p class="private-message">🔒 Este perfil es privado</p>
                <?php else: ?>
                    <p><strong>Email:</strong>
                        <?php echo isset($profile_email) ? $profile_email : 'usuario@email.com'; ?></p>
                    <p><strong>Fecha de Nacimiento:</strong>
                        <?php echo isset($profile_birthdate) ? date('d/m/Y', strtotime($profile_birthdate)) : '01/01/1990'; ?>
                    </p>
                    <p><strong>Género:</strong>
                        <?php echo isset($profile_gender) ? $profile_gender : 'Masculino'; ?></p>
                    <p><strong>Rol:</strong> <?php echo isset($profile_role) ? $profile_role : 'Rol'; ?></p>
                <?php endif; ?>

                <?php if ($session_user_id == $profile_user_id): ?>
                    <button class="btn-edit" id="btn-edit">Editar Perfil</button>
                    <?php if ($_SESSION['role'] === 'cliente'): ?>
                        <br>
                        <button id="btn-crear-lista" class="btn-crear-lista">Crear Lista</button>
                    <?php endif; ?>
                <?php endif; ?>


                <?php if ($_SESSION['role'] === 'vendedor' && $session_user_id == $profile_user_id): ?>
                    <br>
                    <a href="subir_producto.php" class="btn-upload">Subir Producto</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'administrador'): ?>
                    <br>
                    <a href="aprobar_productos.php" class="btn-approve">Aprobar productos</a>
                <?php endif; ?>
            </div>

            <?php if ($session_user_id == $profile_user_id): ?>
                <div id="edit-form" style="display: none;">
                    <h2>Editar Perfil</h2>
                    <form method="POST" action="<?= urlFor('php/perfil/actualizar_perfil.php') ?>"
                        enctype="multipart/form-data">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>"
                            data-current-username="<?php echo $_SESSION['username']; ?>" required
                            onblur="validateUsername()">
                        <span id="username-error" class="error-message"></span>

                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>"
                            data-current-email="<?php echo $_SESSION['email']; ?>" required onblur="validateEmail()">
                        <span id="email-error" class="error-message"></span>

                        <label for="birthdate">Fecha de Nacimiento</label>
                        <input type="date" id="birthdate" name="birthdate" value="<?php echo $_SESSION['birthdate']; ?>"
                            required onblur="validateBirthdate()">
                        <span id="birthdate-error" class="error-message"></span>

                        <label for="gender">Género</label>
                        <select id="gender" name="gender">
                            <option value="masculino" <?php echo ($_SESSION['gender'] == 'masculino') ? 'selected' : ''; ?>>
                                Masculino</option>
                            <option value="femenino" <?php echo ($_SESSION['gender'] == 'femenino') ? 'selected' : ''; ?>>
                                Femenino</option>
                            <option value="otro" <?php echo ($_SESSION['gender'] == 'otro') ? 'selected' : ''; ?>>Otro
                            </option>
                        </select>

                        <label for="avatar">Cambiar Avatar</label>
                        <input type="file" id="image" name="avatar" accept="image/jpeg, image/png, image/webp"
                            onblur="validateImage()">
                        <span id="photo-error" class="error-message"></span>

                        <label for="privacy">Privacidad</label>
                        <select id="privacy" name="privacy">
                            <option value="público" <?php echo ($_SESSION['privacy'] == 'publico') ? 'selected' : ''; ?>>
                                Público</option>
                            <option value="privado" <?php echo ($_SESSION['privacy'] == 'privado') ? 'selected' : ''; ?>>
                                Privado</option>
                        </select>

                        <button type="submit" class="btn-save">Guardar Cambios</button>
                    </form>
                </div>
            <?php endif; ?>
            <!-- Popup para Crear Lista -->
            <div id="popup-crear-lista" class="popup" style="display: none;">
                <div class="popup-content">
                    <span class="close" id="close-popup">&times;</span>
                    <div class="create-list-section">
                        <h2>Crear Nueva Lista</h2>
                        <form method="POST" action="<?= urlFor('php/listas/procesar_crear_lista.php') ?>">
                            <label for="nombre_lista">Nombre de la Lista:</label>
                            <input type="text" id="nombre_lista" name="nombre_lista" required>

                            <label for="descripcion">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" required></textarea>

                            <label for="privacidad">Privacidad:</label>
                            <select id="privacidad" name="privacidad">
                                <option value="publica">Pública</option>
                                <option value="privada">Privada</option>
                            </select>
                            <br>

                            <button type="submit">Crear Lista</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Popup para Editar Lista -->
            <div id="popup-editar-lista" class="popup" style="display: none;">
                <div class="popup-content">
                    <span class="close"
                        onclick="document.getElementById('popup-editar-lista').style.display='none'">&times;</span>
                    <div class="create-list-section">
                        <h2>Editar Lista</h2>
                        <form id="form-editar-lista" onsubmit="event.preventDefault(); guardarEdicionLista();">
                            <input type="hidden" id="edit-lista-id" name="lista_id">
                            <label>Nombre:</label>
                            <input type="text" id="edit-nombre" name="nombre_lista" required>
                            <label>Descripción:</label>
                            <textarea id="edit-descripcion" name="descripcion" required></textarea>
                            <label>Privacidad:</label>
                            <select id="edit-privacidad" name="privacidad">
                                <option value="publica">Pública</option>
                                <option value="privada">Privada</option>
                            </select>
                            <br><br>
                            <button type="submit" class="btn-save">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['role'] === 'cliente'): ?>
                <h2>listas Creadas</h2>
                <div id="listas-container">
                    <?php if ($is_private && $session_user_id != $profile_user_id): ?>
                        <p>🔒 Este perfil es privado, no puedes ver sus listas.</p>
                    <?php else: ?>
                        <?php if (empty($listas)): ?>
                            <p>No hay listas aún.</p>
                        <?php else: ?>
                            <?php foreach ($listas as $lista): ?>
                                <div class="lista-preview" onclick="mostrarDetallesLista(<?php echo $lista['id']; ?>)">
                                    <div class="lista-header">
                                        <h3><?php echo htmlspecialchars($lista['nombre_lista']); ?></h3>

                                        <?php if ($session_user_id == $profile_user_id): ?>
                                            <div class="lista-actions" onclick="event.stopPropagation();">
                                                <button class="btn-icon btn-edit-list"
                                                    onclick="editarLista(<?php echo $lista['id']; ?>, '<?php echo htmlspecialchars($lista['nombre_lista'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lista['descripcion'], ENT_QUOTES); ?>', '<?php echo $lista['privacidad']; ?>')"
                                                    title="Editar">
                                                    <!-- Icono de Lápiz -->
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                    </svg>
                                                </button>

                                                <button class="btn-icon btn-delete-list" onclick="borrarLista(<?php echo $lista['id']; ?>)"
                                                    title="Borrar">
                                                    <!-- Icono de Papelera -->
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <p class="lista-privacidad">
                                        <?php if ($lista['privacidad'] === 'privada'): ?>
                                            <!-- Icono SVG de Candado -->
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                            Privada
                                        <?php else: ?>
                                            <!-- Icono SVG de Globo (Pública) -->
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                                <path
                                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                                </path>
                                            </svg>
                                            Pública
                                        <?php endif; ?>
                                    </p>

                                    <p><?php echo htmlspecialchars($lista['descripcion']); ?></p>
                                    <div class="lista-imagenes">
                                        <?php
                                        $imagenes = isset($lista['imagen_preview']) ? json_decode($lista['imagen_preview'], true) : ['/Recursos/default.jpg'];

                                        foreach ($imagenes as $imagen) {
                                            echo "<img src='data:image/jpeg;base64," . $imagen . "' alt=''>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div id="popup-detalle-lista" class="popup" style="display: none;">
                <div class="popup-content popup-content--wide">
                    <span class="close" id="close-popup-detalle">&times;</span>
                    <div class="detalle-lista-section">
                        <h2>Detalles de la Lista</h2>
                        <div id="contenido-detalle-lista"></div>
                    </div>
                </div>
            </div>
        </section>

        <div>
            <?php if ($session_user_id != $profile_user_id): ?>
                <?php
                $sql = "SELECT rol FROM usuarios WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$profile_user_id]);
                $perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <?php if ($perfil_usuario['rol'] === 'vendedor'): ?>
                    <h3>productos Publicados</h3>
                    <?php if ($is_private): ?>
                        <p class="private-message">🔒 Este perfil es privado</p>
                    <?php else: ?>
                        <?php
                        $vendedor_id = $profile_user_id;
                        include __DIR__ . '/../php/productos/mostrar_productos_vendedor.php';
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($session_user_id == $profile_user_id): ?>
                <?php
                $sql = "SELECT rol FROM usuarios WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$profile_user_id]);
                $perfil_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <?php if ($perfil_usuario['rol'] === 'vendedor'): ?>
                    <h3>productos Publicados</h3>
                    <?php
                    $vendedor_id = $profile_user_id;
                    include __DIR__ . '/../php/productos/mostrar_productos_vendedor.php';
                    ?>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'administrador'): ?>
                    <h3>productos Aprobados</h3>
                    <?php include __DIR__ . '/../php/productos/mostrar_productos_aprobados.php'; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>
    <script src="<?= urlFor('js/sessionCheck.js') ?>"></script>
    <script src="<?= urlFor('js/JEditPerfil.js') ?>"></script>
    <script src="<?= urlFor('js/JValidaciones.js') ?>"></script>
    <script src="<?= urlFor('js/Listas.js') ?>"></script>
</body>

</html>