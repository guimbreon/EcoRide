<?php
session_start();
include 'viagemPassag.php';
include 'abreconexao.php';

if (!isset($_GET['idRes'])) {
    http_response_code(400);
    exit('Missing idRes');
}

$idRes = intval($_GET['idRes']);

// Busque os dados da reserva pelo idRes
$query = "SELECT 
    r.idRes AS idReserva, 
    r.avaliacao, 
    r.preco, 
    v.data_hora, 
    r.pontoRecolha,
    pr.nome AS PontoRecolhaNome,
    l1.nome AS origem, 
    l2.nome AS destino, 
    v.lugares,
    l1.localidade AS localidade
    FROM Reservas r
    INNER JOIN Viagens v ON r.viagem_id = v.id
    INNER JOIN Local l1 ON v.origem = l1.id
    INNER JOIN Local l2 ON v.destino = l2.id
    INNER JOIN Local pr ON r.pontoRecolha = pr.id
    WHERE r.idRes = ?
    LIMIT 1";

$conn->set_charset("utf8mb4");
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idRes);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $date = date("d/m/Y - H:i", strtotime($row['data_hora']));
    $origem = $row['origem'];
    $destino = $row['destino'];
    $localidade = $row['localidade'];
    $lugares = $row['lugares'];
    $preco = $row['preco'];
    $pontoRecolha = $row['PontoRecolhaNome'];
    $idReserva = $row['idReserva'];

    // Passa true para mostrar a bola vermelha
    echo renderViagemCard($date, $origem, $destino, $localidade, $preco, $pontoRecolha, $idReserva, false);
} else {
    http_response_code(404);
    echo "Reserva não encontrada";
}

$stmt->close();
$conn->close();
?>