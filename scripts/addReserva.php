<?php
session_start();
include "abreconexao.php";

// Check connection
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the request
$idPass = $_SESSION["user"]["email"];
$viagemId = $_POST["viagemId"];
$pontoRecolha = (int)$_POST["pontoRecolha"];
$numLugares = isset($_POST["numLugares"]) ? (int)$_POST["numLugares"] : 1;
$avaliacao = null; // Default value for `avaliacao`

// Validate that viagemId exists in the Viagens table and get lugares disponiveis
$viagemCheckStmt = $conn->prepare("SELECT origem, destino, carro_id, preco, lugares FROM Viagens WHERE id = ?");
if ($viagemCheckStmt === false) {
        die("Prepare failed: " . $conn->error);
}
$viagemCheckStmt->bind_param("i", $viagemId);
$viagemCheckStmt->execute();


$viagemCheckStmt->bind_result($origemId, $destinoId, $carroId, $preco, $lugaresDisponiveis);
if (!$viagemCheckStmt->fetch()) {
        die("Erro: O ID da viagem não existe.");
}
$viagemCheckStmt->close();

// Verifica se há lugares suficientes
if ($numLugares < 1 || $numLugares > $lugaresDisponiveis) {
        die("Erro: Número de lugares inválido ou não disponível.");
}

// Prepare and bind the statement for inserting into Reservas
$stmt = $conn->prepare("INSERT INTO Reservas (idPass, viagem_id, pontoRecolha, avaliacao, preco) VALUES (?, ?, ?, ?, ?)");
if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
}

for ($i = 0; $i < $numLugares; $i++) {
        $stmt->bind_param("siisd", $idPass, $viagemId, $pontoRecolha, $avaliacao, $preco);
        if (!$stmt->execute()) {
                echo "Erro ao adicionar reserva: " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
        }
}

// Execute the statement
if ($stmt->execute()) {
        // Atualiza os lugares disponíveis na viagem
        $updateStmt = $conn->prepare("UPDATE Viagens SET lugares = lugares - ? WHERE id = ?");
        $updateStmt->bind_param("ii", $numLugares, $viagemId);
        $updateStmt->execute();
        $updateStmt->close();

        header("Location: ../home.php?sucesso='reserva'");
        exit();
} else {
        echo "Erro ao adicionar reserva: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
