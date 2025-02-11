<?php
// update.php
session_start();

// Verificar que el usuario es administrador
if (!isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexion.php';

    $id = $_POST['id'] ?? 0;
    $nombre = $_POST['nombre'] ?? '';

    // Validación simple
    if (!$id || !$nombre) {
        echo "ID del producto o nombre no válido.";
        exit;
    }

    // Actualizar el producto
    $update = $pdo->prepare("UPDATE productos SET nombre = :nombre WHERE id = :id");
    $update->execute(['nombre' => $nombre, 'id' => $id]);

    // Redirigir a listado después de actualizar
    header('Location: listado.php');
    exit;
} elseif (isset($_GET['id'])) {
    require 'conexion.php';

    // Obtener el producto actual
    $id = $_GET['id'];
    $query = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
    $query->execute(['id' => $id]);
    $producto = $query->fetch(PDO::FETCH_ASSOC);

    // Verificar si el producto existe
    if (!$producto) {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "ID del producto no especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Actualizar Producto</title>
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Actualizar Producto</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
        <label for="nombre" class="block text-gray-700">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Actualizar Producto</button>
        <a href="listado.php" class="bg-red-500 text-white px-4 py-2 mt-4 rounded">Volver atrás</a>
    </form>
</body>

</html>
