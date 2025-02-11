<?php
// borrar.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conexion.php';
    $id = intval($_POST['id'] ?? 0);

    // Validar que el ID es un número entero positivo
    if ($id <= 0) {
        echo "ID no válido.";
        exit;
    }

    try {
        // Verificar si el producto existe antes de eliminar
        $check = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $check->execute(['id' => $id]);
        $producto = $check->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            echo "Producto no encontrado.";
            exit;
        }

        // Eliminar el producto
        $delete = $pdo->prepare("DELETE FROM productos WHERE id = :id");
        $delete->execute(['id' => $id]);

        // Redirigir con mensaje de confirmación
        header('Location: listado.php?mensaje=producto_eliminado');
        exit;

    } catch (PDOException $e) {
        echo "Error al eliminar el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Eliminar Producto</title>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Eliminar Producto</h1>
    
    <!-- Formulario de confirmación -->
    <form method="POST">
        <label for="id" class="block text-gray-700">ID del Producto a eliminar:</label>
        <input type="number" id="id" name="id" class="w-full p-2 border rounded" required>
        
        <button type="submit" class="bg-red-500 text-white px-4 py-2 mt-4 rounded">
            Confirmar Eliminación
        </button>
    </form>

    <!-- Mensaje de confirmación (puedes agregar un JS para esto si lo prefieres) -->
    <div id="confirmacion" class="mt-4">
        <p class="text-gray-700">¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function (e) {
            if (!confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
