<?php
// listado.php

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require 'conexion.php';

// Consulta de productos
$query = $pdo->query("SELECT * FROM productos");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);

// Verificar si el usuario es admin
$esAdmin = $_SESSION['esAdmin'] ?? false;

// Función para generar íconos de estrellas con media estrella usando FontAwesome
function mostrarEstrellas($media) {
    $media = round($media * 2) / 2;
    $estrellas = '';
    for ($i = 0; $i < floor($media); $i++) {
        $estrellas .= '<i class="fas fa-star estrella"></i>';
    }
    if ($media - floor($media) == 0.5) {
        $estrellas .= '<i class="fas fa-star-half-alt estrella"></i>';
    }
    for ($i = 0; $i < (5 - ceil($media)); $i++) {
        $estrellas .= '<i class="far fa-star estrella"></i>';
    }
    return $estrellas;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Listado de Productos</title>
    <!-- Enlace a FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .estrella {
            color: #f59e0b;
            font-size: 1.2rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Listado de Productos</h1>
    <div class="flex justify-between items-center mb-4">
        <?php if ($esAdmin) { ?>
            <a href="crear.php" class="bg-green-500 text-white px-4 py-2 rounded">Crear Producto</a>
        <?php } ?>
        <a href="index.php" class="bg-red-500 text-white px-4 py-2 rounded">Cerrar Sesión</a>
    </div>
    <table class="w-full bg-white shadow-md rounded">
        <thead>
            <tr>
                <th class="border px-4 py-2">Código</th>
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Valoración</th>
                <th class="border px-4 py-2">Valorar</th>
                <?php if ($esAdmin) { ?>
                    <th class="border px-4 py-2">Acciones</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) { ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $producto['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $producto['nombre']; ?></td>
                    <td class="border px-4 py-2" id="valoracion-<?php echo $producto['id']; ?>">
                        <?php
                        // Obtener la valoración promedio y total de votos
                        $statement = $pdo->prepare('SELECT AVG(cantidad) AS media, COUNT(*) AS total FROM votos WHERE idPr = :id_producto');
                        $statement->execute(['id_producto' => $producto['id']]);
                        $result = $statement->fetch(PDO::FETCH_ASSOC);
                        $totalVotos = $result['total'] ?? 0;
                        $valoracion = $result['media'] ?? 0;
                        
                        // Mostrar las estrellas y los votos
                        echo "<span class='estrella'>" . mostrarEstrellas($valoracion) . "</span> <span class='text-sm' id='votos-{$producto['id']}'>({$totalVotos} " . ($totalVotos != 1 ? "votos" : "voto") . ")</span>";
                        ?>
                    </td>
                    <td class="border px-4 py-2">
                        <select class="valoracion-select" data-producto-id="<?php echo $producto['id']; ?>">
                            <option value="">Selecciona...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </td>
                    <?php if ($esAdmin) { ?>
                        <td class="border px-4 py-2">
                            <a href="update.php?id=<?php echo $producto['id']; ?>" class="text-blue-500">Editar</a> |
                            <a href="borrar.php?id=<?php echo $producto['id']; ?>" class="text-red-500">Eliminar</a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Código JavaScript usando fetch -->
    <script>
    document.querySelectorAll('.valoracion-select').forEach(select => {
        select.addEventListener('change', async function () {
            const productoId = this.dataset.productoId;
            const voto = this.value;

            // Enviar la valoración al servidor mediante fetch
            const response = await fetch('valorar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ productoId, voto })
            });
            const result = await response.json();

            // Si la respuesta es exitosa, actualizamos la visualización
            if (result.success) {
                document.getElementById(`valoracion-${productoId}`).innerHTML =
                    `<span class="estrella">${result.estrellas}</span> <span class="text-sm" id="votos-${productoId}">${result.votos}</span>`;
            } else {
                alert(result.message);
            }
        });
    });
    </script>
</body>
</html>
