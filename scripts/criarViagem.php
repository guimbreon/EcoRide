<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
}
$user = $_SESSION['user'];

$errors = [];
if (isset($_GET['success']) && $_GET['success'] == 1) {
  echo "<div class='alert alert-success'>Viagem criada com sucesso!</div>";
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  include "scripts/abreconexao.php"; // Include your database connection file

  $carro_id = trim(htmlspecialchars($_POST["carro_id"] ?? ''));
  $origem = trim(htmlspecialchars($_POST["origem"] ?? ''));
  $destino = trim(htmlspecialchars($_POST["destino"] ?? ''));
  $lugares = trim(htmlspecialchars($_POST["lugares"] ?? ''));
  $preco = trim(htmlspecialchars($_POST["preco"] ?? ''));
  $data_hora = trim(htmlspecialchars($_POST["data_hora"] ?? ''));

  // Validate required fields
  if (empty($carro_id)) {
    $errors[] = "Por favor, insira o ID do carro.";
  }
  if (empty($origem) || !is_numeric($origem)) {
    $errors[] = "Por favor, insira um ID de origem válido.";
  }
  if (empty($destino) || !is_numeric($destino)) {
    $errors[] = "Por favor, insira um ID de destino válido.";
  }
  if (empty($lugares) || !is_numeric($lugares) || $lugares <= 0) {
    $errors[] = "Por favor, insira um número válido de lugares.";
  }
  if (empty($preco) || !is_numeric($preco) || $preco < 0) {
    $errors[] = "Por favor, insira um preço válido.";
  }
  if (empty($data_hora) || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $data_hora)) {
    $errors[] = "Por favor, insira uma data e hora válidas (formato: YYYY-MM-DDTHH:MM).";
  }
  // If no errors, insert the trip into the database
  if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO Viagens (condutor_id, carro_id, origem, destino, lugares, preco, data_hora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiids", $user['email'], $carro_id, $origem, $destino, $lugares, $preco, $data_hora);
  }

    if ($stmt->execute()) {
      header("Location: criarViagem.php?success=1");
      exit();
    } else {
      $errors[] = "Erro ao criar a viagem. Por favor, tente novamente.";
    }

    $stmt->close();
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EcoRide</title>
  <link href="assets/css/theme.css" rel="stylesheet" />
</head>

<body>
  <main class="main" id="top">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
      <div class="container"><a class="navbar-brand" href="index.php"><h1>ECORIDE</h1></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
        <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
            <?php include 'scripts/checkLogin.php'; ?>
          </ul>
        </div>
      </div>
    </nav>
    <section style="padding-top: 7rem;">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12 text-center py-6">
            <h1 class="hero-title">Criar Nova Viagem</h1>
            <p class="mb-4 fw-medium">Planeje e compartilhe a sua próxima viagem com EcoRide.</p>
            <form action="criarViagem.php" method="POST" class="text-start mx-auto" style="max-width: 400px;">
                <div class="mb-3">
                <label for="carro_id" class="form-label">Matricula do Carro</label>
                <select class="form-select" id="carro_id" name="carro_id" required>
                  <option value="" disabled selected>Selecione um carro</option>
                    <?php
                    include "scripts/abreconexao.php";
                    $stmt = $conn->prepare("SELECT matricula FROM Carros WHERE id_dono = ?");
                    $stmt->bind_param("s", $user['email']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                      echo "<option value='" . htmlspecialchars($row['matricula']) . "'>" . htmlspecialchars($row['matricula']) . "</option>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </select>
                </div>
                <div class="mb-3">
                <label for="origem" class="form-label">Origem</label>
                <select class="form-select" id="origem" name="origem" required>
                  <option value="" disabled selected>Selecione a origem</option>
                  <?php
                  include "scripts/abreconexao.php";
                  $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  while ($row = $result->fetch_assoc()) {
                  $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                  $nome = htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8');
                  $rua = htmlspecialchars(utf8_encode($row['rua']), ENT_QUOTES, 'UTF-8');
                  $nmr = htmlspecialchars($row['nmr'], ENT_QUOTES, 'UTF-8');
                  $localidade = htmlspecialchars(utf8_encode($row['localidade']), ENT_QUOTES, 'UTF-8');
                  echo "<option value='$id'>$nome - $rua, $nmr, $localidade</option>";
                  }
                  $stmt->close();
                  $conn->close();
                  ?>
                </select>
                </div>
                <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <select class="form-select" id="destino" name="destino" required>
                  <option value="" disabled selected>Selecione o destino</option>
                  <?php
                  include "scripts/abreconexao.php";
                  $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  while ($row = $result->fetch_assoc()) {
                  $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                  $nome = htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8');
                  $rua = htmlspecialchars(utf8_encode($row['rua']), ENT_QUOTES, 'UTF-8');
                  $nmr = htmlspecialchars($row['nmr'], ENT_QUOTES, 'UTF-8');
                  $localidade = htmlspecialchars(utf8_encode($row['localidade']), ENT_QUOTES, 'UTF-8');
                  echo "<option value='$id'>$nome - $rua, $nmr, $localidade</option>";
                  }
                  $stmt->close();
                  $conn->close();
                  ?>
                </select>
                </div>
              <div class="mb-3">
                <label for="lugares" class="form-label">Lugares Disponíveis</label>
                <input type="number" class="form-control" id="lugares" name="lugares" required>
              </div>
              <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" class="form-control" id="preco" name="preco" required>
              </div>
              <div class="mb-3">
                <label for="data_hora" class="form-label">Data e Hora</label>
                <input type="datetime-local" class="form-control" id="data_hora" name="data_hora" required>
              </div>

              <?php
              if (!empty($errors)) {
                echo "<div class='alert alert-danger'><ul>";
                foreach ($errors as $error) {
                  echo "<li>$error</li>";
                }
                echo "</ul></div>";
              }
              ?>
              <button type="submit" class="btn btn-primary w-100">Criar Viagem</button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <section class="pt-5" id="my-trips">
      <div class="container">
        <div class="mb-7 text-center">
          <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">As Minhas Viagens</h3>
        </div>
        <div class="row">
          <?php
          include "scripts/abreconexao.php";
          include "scripts/viagemAdmin.php";
            $sql = "SELECT V.id, V.data_hora, V.lugares, V.preco,
              L1.nome AS origem_nome, L2.nome AS destino_nome, L2.localidade AS localidade
              FROM Viagens V
              JOIN Local L1 ON V.origem = L1.id
              JOIN Local L2 ON V.destino = L2.id
              WHERE V.condutor_id = ?
              ORDER BY V.data_hora ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user['email']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
            while ($viagem = $result->fetch_assoc()) {
              $id = htmlspecialchars($viagem['id'], ENT_QUOTES, 'UTF-8');
              $data_hora = htmlspecialchars($viagem['data_hora'], ENT_QUOTES, 'UTF-8');
              $lugares = htmlspecialchars($viagem['lugares'], ENT_QUOTES, 'UTF-8');
              $preco = htmlspecialchars($viagem['preco'], ENT_QUOTES, 'UTF-8');
              $origem = htmlspecialchars(utf8_encode($viagem['origem_nome']), ENT_QUOTES, 'UTF-8');
              $destino = htmlspecialchars(utf8_encode($viagem['destino_nome']), ENT_QUOTES, 'UTF-8');
              $localidade = htmlspecialchars(utf8_encode($viagem['localidade']), ENT_QUOTES, 'UTF-8');
              echo renderViagemCard($data_hora, $origem, $destino, $lugares,$localidade, $preco,  $id);
            }
            } else {
            echo "<p class='text-center'>Nenhuma viagem encontrada.</p>";
            }

          $stmt->close();
          $conn->close();
          ?>
        </div>
      </div>
    </section>

    <section>
      <div id="popupEditarViagem" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background-color:white; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,0.2); z-index:1001;">
      <h2>Editar Viagem</h2>
      <p>Atualize os detalhes da sua viagem abaixo:</p>
      <form action="scripts/editarViagem.php" method="post" class="text-start mx-auto" style="max-width: 400px;">
        <input type="hidden" id="editarViagemId" name="viagemId" value="">
        <div class="mb-3">
        <label for="editarOrigem" class="form-label">Origem:</label>
        <select id="editarOrigem" name="origem" class="form-select" required>
          <option value="" disabled>Escolha uma opção</option>
          <?php
          include "scripts/abreconexao.php";
          $sql = "SELECT id, nome FROM Local ORDER BY nome";
          $result = $conn->query($sql);
          while ($row = $result->fetch_assoc()) {
          echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8') . '</option>';
          }
          $conn->close();
          ?>
        </select>
        </div>
        <div class="mb-3">
        <label for="editarDestino" class="form-label">Destino:</label>
        <select id="editarDestino" name="destino" class="form-select" required>
          <option value="" disabled>Escolha uma opção</option>
          <?php
          include "scripts/abreconexao.php";
          $sql = "SELECT id, nome FROM Local ORDER BY nome";
          $result = $conn->query($sql);
          while ($row = $result->fetch_assoc()) {
          echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8') . '</option>';
          }
          $conn->close();
          ?>
        </select>
        </div>
        <div class="mb-3">
        <label for="editarLugares" class="form-label">Lugares Disponíveis:</label>
        <input type="number" class="form-control" id="editarLugares" name="lugares" required>
        </div>
        <div class="mb-3">
        <label for="editarDataHora" class="form-label">Data e Hora:</label>
        <input type="datetime-local" class="form-control" id="editarDataHora" name="data_hora" required>
        </div>
        <div class="mb-3">
        <label for="editarPreco" class="form-label">Preço:</label>
        <input type="number" class="form-control" id="editarPreco" name="preco" required>
        </div>
        <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <button type="button" class="btn btn-secondary" onclick="fecharPopupEditarViagem();">Cancelar</button>
        </div>
      </form>
      </div>
      <div id="popupOverlayEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8); z-index:1000;"></div>
      <script>
      function editarViagem(viagemId, origem, destino, dataHora, lugares, preco) {
        document.getElementById('editarViagemId').value = viagemId;
        document.getElementById('editarOrigem').value = origem;
        document.getElementById('editarDestino').value = destino;
        document.getElementById('editarDataHora').value = dataHora;
        document.getElementById('editarLugares').value = lugares;
        document.getElementById('editarPreco').value = preco;
        document.getElementById('popupEditarViagem').style.display = "block";
        document.getElementById('popupOverlayEditar').style.display = "block";
        document.body.style.overflow = "hidden"; // Impede o scroll
      }
      function fecharPopupEditarViagem() {
        document.getElementById('popupEditarViagem').style.display = "none";
        document.getElementById('popupOverlayEditar').style.display = "none";
        document.body.style.overflow = "auto"; // Restaura o scroll
      }
      </script>
    </section>


  </main>
  <script src="vendors/@popperjs/popper.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.min.js"></script>
  <script src="vendors/is/is.min.js"></script>
  <script src="assets/js/theme.js"></script>
</body>

</html>
