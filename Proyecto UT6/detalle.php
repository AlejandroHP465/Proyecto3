<?php
// detalle.php
require 'conexion.php';
$id = $_GET['id'] ?? 0;

// Verificar que el ID es válido
if (!is_numeric($id) || $id <= 0) {
    echo "ID inválido.";
    exit;
}

$query = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
$query->execute(['id' => $id]);
$producto = $query->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el producto, mostrar un mensaje de error
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Detalle del Producto</title>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Detalle del Producto</h1>
    
    <div class="bg-white shadow-md rounded p-4">
        <p><strong>ID:</strong> <?php echo $producto['id']; ?></p>
        <p><strong>Nombre:</strong> <?php echo $producto['nombre']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $producto['descripcion'] ?? 'No disponible'; ?></p>
        
        <!-- Si tienes una imagen del producto, puedes mostrarla así -->
        <!-- <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del producto" class="w-full mt-4"> -->
    </div>
    
    <div class="mt-6">
        <a href="listado.php" class="bg-red-500 text-white px-4 py-2 rounded">Volver al listado</a>
    </div>
</body>
</html>
