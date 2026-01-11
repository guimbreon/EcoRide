<?php
  session_start();
  // echo "Inserir-se como utilizador<br>"; // Commented out to prevent output before header
  
  include "abreconexao.php";

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $email = $_GET["email"];
  $nif = $_GET["nif"];
  $nome = $_GET["nome"];
  $telemovel = $_GET["tlmv"];
  $pass = password_hash($_GET["pass"], PASSWORD_DEFAULT);
  $ft_perfil = $_GET["ft_perfil"] ?: 'assets/img/perfil/1.jpg';
  $type = $_GET["type"];

  // prepare and bind
  $stmt = $conn->prepare("INSERT INTO Utilizadores (email, NIF, nome, telemovel, pass, ft_perfil) VALUES (?, ?, ?, ?, ?, ?)");
  if ($stmt === false) {
      die("Prepare failed: " . $conn->error);
  }

  $stmt->bind_param("ssssss", $email, $nif, $nome, $telemovel, $pass, $ft_perfil);

  // Execute the statement
  if ($stmt->execute()) {
      // echo "Novo utilizador inserido"; // Commented out to prevent output before header
      $stmt2 = $conn->prepare("INSERT INTO $type (id) VALUES (?)");
      $stmt2->bind_param("s", $email);
      $stmt2->execute();
      $stmt2->close();

    $_SESSION['user'] = array(
      'nome' => $nome,
      'email' => $email,
      'tipo' => $type
    );
    header("Location: ../home.php");
    exit();
  } else {
      echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
?>