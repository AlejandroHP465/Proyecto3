<!-- index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form id="loginForm" class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Inicia Sesión</h2>
        <div class="mb-4">
            <label for="usuario" class="block text-gray-700">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Contraseña</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Ingresar</button>
        <div id="loginMessage" class="mt-2 text-red-500 text-center"></div>
    </form>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const usuario = document.getElementById('usuario').value;
            const password = document.getElementById('password').value;
            const loginMessage = document.getElementById('loginMessage');

            // Validación simple de campos vacíos
            if (!usuario || !password) {
                loginMessage.innerText = 'Por favor, complete todos los campos.';
                return;
            }

            // Enviar datos al servidor
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ usuario, password })
                });
                
                const result = await response.json();

                if (result.success) {
                    // Redirigir si el login es exitoso
                    window.location.href = 'listado.php';
                } else {
                    // Mostrar mensaje de error
                    loginMessage.innerText = result.message || 'Ha ocurrido un error, por favor intenta de nuevo.';
                }
            } catch (error) {
                loginMessage.innerText = 'Hubo un problema con la conexión. Intenta de nuevo más tarde.';
            }
        });
    </script>
</body>
</html>
