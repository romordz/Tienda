<?php
function renderProductoCard(array $producto, string $modo = 'thumb', bool $usarOnclick = true): void
{
    $imagenes = !empty($producto['imagenes_json'])
        ? json_decode($producto['imagenes_json'], true)
        : [];

    $primeraImagen = $imagenes[0] ?? null;

    $src = $primeraImagen
        ? (str_starts_with($primeraImagen, 'http')
            ? htmlspecialchars($primeraImagen)
            : 'data:image/jpeg;base64,' . htmlspecialchars($primeraImagen))
        : urlFor('/Recursos/default.jpg');

    $nombre = htmlspecialchars($producto['nombre'] ?? '');
    $destino = 'producto_detalle.php?id=' . urlencode($producto['id']);
    $claseModo = $modo === 'grid' ? 'producto-card--grid' : 'producto-card--thumb';

    $accionClick = $usarOnclick
        ? "checkSession('{$destino}')"
        : "window.location.href='" . htmlspecialchars($destino) . "'";
    ?>
    <div class="producto-card <?= $claseModo ?>" onclick="<?= $accionClick ?>" style="cursor: pointer;">
        <div class="producto-card-img-wrap">
            <img src="<?= $src ?>" alt="Imagen de <?= $nombre ?>">
        </div>

        <?php if ($modo === 'grid'): ?>
            <h3><?= $nombre ?></h3>
            <p class="description"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></p>
            <?php if (($producto['para_cotizar'] ?? 0) == 1): ?>
                <p class="price">Cotización disponible</p>
            <?php else: ?>
                <p class="price">$<?= number_format($producto['precio'] ?? 0, 2) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}
function renderTodasImagenes(array $producto, int $anchoPx = 100): void
{
    $imagenes = !empty($producto['imagenes_json'])
        ? json_decode($producto['imagenes_json'], true)
        : [];

    if (empty($imagenes)) {
        echo '<p>No disponible</p>';
        return;
    }

    $nombre = htmlspecialchars($producto['nombre'] ?? '');

    foreach ($imagenes as $imagen) {
        $src = str_starts_with($imagen, 'http')
            ? htmlspecialchars($imagen)
            : 'data:image/jpeg;base64,' . htmlspecialchars($imagen);
        ?>
        <img src="<?= $src ?>" alt="<?= $nombre ?>"
            style="width: <?= (int)$anchoPx ?>px; height: auto; margin-right: 5px;">
        <?php
    }
}