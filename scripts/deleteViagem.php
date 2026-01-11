<?php
session_start();

include "abreconexao.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the ID from the GET request
$viagem_id = intval($_GET["id"] ?? 0);

// Validate the ID
if ($viagem_id <= 0) {
    die("ID da viagem inválido.");
}

// Prepare the SQL query to delete the record
$sql = "DELETE FROM Viagens WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind the ID parameter
$stmt->bind_param("i", $viagem_id);

// Execute the query
if ($stmt->execute()) {
    echo "Viagem deletada com sucesso!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo "Erro ao deletar a viagem: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
