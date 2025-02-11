<?php
// crear.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexion.php';
    $nombre = trim($_POST['nombre'] ?? '');

    // Validación de datos
    if (empty($nombre)) {
        echo "El nombre del producto no puede estar vacío.";
        exit;
    }

    try {
        // Verificar si el producto ya existe
        $check = $pdo->prepare("SELECT * FROM productos WHERE nombre = :nombre");
        $check->execute(['nombre' => $nombre]);
        if ($check->fetch(PDO::FETCH_ASSOC)) {
            header('Location: crear.php?error=producto_existente');
            exit;
        }

        // Insertar el nuevo producto
        $insert = $pdo->prepare("INSERT INTO productos (nombre) VALUES (:nombre)");
        $insert->execute(['nombre' => $nombre]);

        // Redirigir a la página de listado después de la creación
        header('Location: listado.php');
        exit;
    } catch (PDOException $e) {
        echo "Error al crear el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Crear Producto</title>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Crear Producto</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500">Ya existe un producto con ese nombre.</p>
    <?php endif; ?>

    <form method="POST">
        <label for="nombre" class="block text-gray-700">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" class="w-full p-2 border rounded" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Crear Producto</button>
        <a href="listado.php" class="bg-red-500 text-white px-4 py-2 mt-4 rounded inline-block">Volver atrás</a>
    </form>
</body>
</html>
