<?php
// login.php
session_start();
header('Content-Type: application/json');
require 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$usuario = $data['usuario'] ?? '';
$password = $data['password'] ?? '';

// Consulta sin cifrado de contraseñas
$query = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND password = :password");
$query->execute(['usuario' => $usuario, 'password' => $password]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['esAdmin'] = ($user['usuario'] === 'admin');
    echo json_encode(['success' => true, 'message' => 'Login exitoso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales erróneas']);
}

?>
