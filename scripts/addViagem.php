<?php
session_start();

include "abreconexao.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
$carro_id = trim(htmlspecialchars($_POST["carro_id"] ?? ''));
$origem = intval($_POST["origem"] ?? 0);
$destino = intval($_POST["destino"] ?? 0);
$lugares = intval($_POST["lugares"] ?? 0);
$data_hora = trim(htmlspecialchars($_POST["data_hora"] ?? ''));
$preco = floatval($_POST["preco"] ?? 0);

// Validate required fields
$errors = [];
if (empty($carro_id)) {
    $errors[] = "Por favor, selecione um carro.";
}
if ($origem <= 0) {
    $errors[] = "Por favor, insira uma origem válida.";
}
if ($destino <= 0) {
    $errors[] = "Por favor, insira um destino válido.";
}
if ($lugares <= 0) {
    $errors[] = "Por favor, insira um número válido de lugares.";
}
if (empty($data_hora)) {
    $errors[] = "Por favor, insira a data e hora.";
}
if ($preco < 0) {
    $errors[] = "Por favor, insira um preço válido.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    exit();
}

// Insert the new trip into the database
$stmt = $conn->prepare("INSERT INTO Viagens (carro_id, origem, destino, lugares, data_hora, preco) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("siiisd", $carro_id, $origem, $destino, $lugares, $data_hora, $preco);

if ($stmt->execute()) {
    echo "Nova viagem criada com sucesso!";
} else {
    echo "Erro ao criar a viagem: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
