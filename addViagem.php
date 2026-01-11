<?php
session_start();

include "abreconexao.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
$carro_id = trim(htmlspecialchars($_POST["carro_id"] ?? ''));
$origem_formatted = trim(htmlspecialchars($_POST["origem"] ?? ''));
$destino_formatted = trim(htmlspecialchars($_POST["destino"] ?? ''));
$lugares = intval($_POST["lugares"] ?? 0);
$data_hora = trim(htmlspecialchars($_POST["data_hora"] ?? ''));
$preco = floatval($_POST["preco"] ?? 0);

// Extract the IDs for origem and destino
function extractId($formattedString, $conn) {
    $stmt = $conn->prepare("SELECT id FROM Local WHERE CONCAT(nome, ' - ', rua, ', ', nmr, ', ', localidade) = ?");
    $stmt->bind_param("s", $formattedString);
    $stmt->execute();
    $id = null; // Declare the variable before binding the result
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    return $id ?? 0;
}

$origem = extractId($origem_formatted, $conn);
$destino = extractId($destino_formatted, $conn);

// Validate required fields
$errors = [];
if (empty($carro_id)) {
    $errors[] = "Por favor, selecione um carro.";
}
if ($origem <= 0) {
    $errors[] = "Por favor, insira uma origem válida.";
}

if ($origem == $destino){
    $errors[] = "A origem e o destino devem ser diferentes. Por favor, escolha valores distintos.";
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
$stmt->bind_param("siisds", $carro_id, $origem, $destino, $lugares, $data_hora, $preco);

if ($stmt->execute()) {
    echo "Nova viagem criada com sucesso!";
} else {
    echo "Erro ao criar a viagem: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
