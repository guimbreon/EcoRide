<?php
session_start();
include "abreconexao.php";

// Função para inserir local personalizado
function inserirLocal($conn, $local_outro) {
    $partes = array_map('trim', explode(',', $local_outro));
    if (count($partes) < 4) return false;
    list($nome, $rua, $nmr, $localidade) = $partes;
    $latitude = mt_rand(-90000000, 90000000) / 1000000;
    $longitude = mt_rand(-180000000, 180000000) / 1000000;
    $stmt = $conn->prepare("INSERT INTO Local (nome, rua, nmr, localidade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    $conn->set_charset("utf8mb4");
    $stmt->bind_param("ssssdd", $nome, $rua, $nmr, $localidade, $latitude, $longitude);
    if ($stmt->execute()) {
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }
    $stmt->close();
    return false;
}

// Validação dos dados
$viagem_id = intval($_POST["viagemId"] ?? 0);
$origem = $_POST["origem"] ?? null;
$destino = $_POST["destino"] ?? null;
$lugares = intval($_POST["lugares"] ?? 0);
$data_hora = trim(htmlspecialchars($_POST["data_hora"] ?? ''));
$preco = floatval($_POST["preco"] ?? 0);

$errors = [];
if ($viagem_id <= 0) $errors[] = "ID da viagem inválido.";
if ($lugares <= 0) $errors[] = "Por favor, insira um número válido de lugares.";
if (empty($data_hora)) $errors[] = "Por favor, insira a data e hora.";
if ($preco < 0) $errors[] = "Por favor, insira um preço válido.";

if ($errors) {
    foreach ($errors as $error) echo "<p>$error</p>";
    exit();
}

// Inserção de locais personalizados, se necessário
$origem_outro = trim($_POST['origem_outro'] ?? '');
if ($origem_outro) {
    $novo_id = inserirLocal($conn, $origem_outro);
    if ($novo_id) $origem = $novo_id;
    else $errors[] = "Erro ao inserir a origem personalizada.";
}

$destino_outro = trim($_POST['destino_outro'] ?? '');
if ($destino_outro) {
    $novo_id = inserirLocal($conn, $destino_outro);
    if ($novo_id) $destino = $novo_id;
    else $errors[] = "Erro ao inserir o destino personalizado.";
}

if ($errors) {
    foreach ($errors as $error) echo "<p>$error</p>";
    exit();
}

// Monta query dinamicamente
$fields = ["lugares = ?", "data_hora = ?", "preco = ?"];
$params = [$lugares, $data_hora, $preco];
$types = "isd";

if ($origem) {
    $fields[] = "origem = ?";
    $params[] = intval($origem);
    $types .= "i";
}
if ($destino) {
    $fields[] = "destino = ?";
    $params[] = intval($destino);
    $types .= "i";
}

$params[] = $viagem_id;
$types .= "i";

$sql = "UPDATE Viagens SET " . implode(", ", $fields) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) die("Prepare failed: " . $conn->error);
date_default_timezone_set('Europe/Lisbon');
$now = date('Y-m-d H:i:s');
$update_reservas = $conn->prepare("UPDATE Reservas SET updated_at = ? WHERE viagem_id = ?");
$update_reservas->bind_param("si", $now, $viagem_id);
$update_reservas->execute();
$update_reservas->close();

$stmt->bind_param($types, ...$params);
$stmt->execute();
header("Location: ../criarViagem.php?success=1");
exit();

$stmt->close();
$conn->close();
?>
