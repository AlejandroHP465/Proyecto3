<?php
// valorar.php
session_start();
header('Content-Type: application/json');
require 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$productoId = $data['productoId'] ?? 0;
$voto = $data['voto'] ?? 0;

$usuarioId = $_SESSION['usuario'] ?? '';

// Verificar si el usuario ya votó este producto
$stmt = $pdo->prepare("SELECT * FROM votos WHERE idPr = ? AND idUs = ?");
$stmt->execute([$productoId, $usuarioId]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Ya has votado este producto.']);
    exit;
}

// Insertar el nuevo voto
$stmt = $pdo->prepare("INSERT INTO votos (idPr, idUs, cantidad) VALUES (?, ?, ?)");
$stmt->execute([$productoId, $usuarioId, $voto]);

// Calcular la nueva media y el total de votos
$stmt = $pdo->prepare("SELECT AVG(cantidad) AS media, COUNT(*) AS total FROM votos WHERE idPr = ?");
$stmt->execute([$productoId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Función para generar íconos de estrellas con media estrella usando FontAwesome
function mostrarEstrellas($media) {
    // Redondear al 0.5 más cercano
    $media = round($media * 2) / 2;
    $estrellas = '';

    // Estrellas completas
    for ($i = 0; $i < floor($media); $i++) {
        $estrellas .= '<i class="fas fa-star estrella"></i>';
    }

    // Media estrella, si corresponde
    if ($media - floor($media) == 0.5) {
        $estrellas .= '<i class="fas fa-star-half-alt estrella"></i>';
    }

    // Estrellas vacías para completar 5
    for ($i = 0; $i < (5 - ceil($media)); $i++) {
        $estrellas .= '<i class="far fa-star estrella"></i>';
    }

    return $estrellas;
}

echo json_encode([
    'success'   => true,
    'media'     => round($result['media'], 1),
    'total'     => $result['total'],
    'estrellas' => mostrarEstrellas($result['media']),
    'votos'     => "({$result['total']} " . ($result['total'] != 1 ? 'votos' : 'voto') . ")"
]);
?>
