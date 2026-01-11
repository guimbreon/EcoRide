<?php
session_start();
error_reporting(0); // Prevent PHP warnings from breaking JSON output
include 'abreconexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$user = $_SESSION['user'];
$userEmail = $user['email'];
$userTipo = $user['tipo'];
$lastCheck = isset($_GET['lastCheck']) ? intval($_GET['lastCheck']) : 0;
$reservasAtualizadas = [];

if ($userTipo === 'Condutores') {
    $query = "
        SELECT r.idRes AS idReserva, UNIX_TIMESTAMP(r.updated_at) AS reserva_ts
        FROM Reservas r
        INNER JOIN Viagens v ON r.viagem_id = v.id
        WHERE v.condutor_id = ?
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['error' => 'Erro ao preparar statement: ' . $conn->error]);
        $conn->close();
        exit;
    }
    if (!$stmt->bind_param("s", $userEmail)) {
        echo json_encode(['error' => 'Erro ao associar parâmetros: ' . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    $query = "
        SELECT r.idRes AS idReserva, UNIX_TIMESTAMP(r.updated_at) AS reserva_ts
        FROM Reservas r
        WHERE r.idPass = ?
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['error' => 'Erro ao preparar statement: ' . $conn->error]);
        $conn->close();
        exit;
    }
    if (!$stmt->bind_param("s", $userEmail)) {
        echo json_encode(['error' => 'Erro ao associar parâmetros: ' . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }
}

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Erro ao executar statement: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}
$result = $stmt->get_result();
if (!$result) {
    echo json_encode(['error' => 'Erro ao obter resultado: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}
while ($row = $result->fetch_assoc()) {
    if ($row['reserva_ts'] > $lastCheck) {
        $reservasAtualizadas[] = $row['idReserva'];
    }
}
$stmt->close();
echo json_encode([
    'ids' => $reservasAtualizadas,
    'now' => time()
]);

$conn->close();
?>
