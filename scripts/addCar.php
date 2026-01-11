<?php
  session_start();
  
  include "abreconexao.php";

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
// Retrieve data from the request
$user = $_SESSION["user"];
$email = $user["email"];
$marca = $_GET["marca"];
$modelo = $_GET["modelo"];
$cor = $_GET["cor"];
$matricula = $_GET["matricula"];
$combustivel = $_GET["combustivel"];
$ft_carro = $_GET["ft_carro"] ?: 'assets/img/ecoRide.png'; // Assuming the file path is passed as a parameter

// Prepare and bind the statement for inserting into Carros
$stmt = $conn->prepare("INSERT INTO Carros (matricula, id_dono, marca, modelo, cor, combustivel, ft_carro) VALUES (?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssssss", $matricula, $email, $marca, $modelo, $cor, $combustivel, $ft_carro);

// Execute the statement
if ($stmt->execute()) {
        $_SESSION['isCarSet'] = $matricula;
        echo "Car successfully registered!";
        header("Location: ../regCarros.php");
        exit();
} else {
        echo "Error inserting car: " . $stmt->error;
}

  $stmt->close();
  $conn->close();
?>
