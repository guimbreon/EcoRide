<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
}
$user = $_SESSION['user'];

$errors = [];
//ESTE É GET
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['origem_lat'], $_GET['destino_lat'], $_GET['origem_outro'], $_GET['destino_outro'])) {
  include "scripts/abreconexao.php";

  $origem_lat = floatval($_GET['origem_lat']);
  $origem_lon = floatval($_GET['origem_lon']);
  $destino_lat = floatval($_GET['destino_lat']);
  $destino_lon = floatval($_GET['destino_lon']);

  $origem_outro = $_GET['origem_outro'];
  $destino_outro = $_GET['destino_outro'];

  // Inserir origem
  $partes = array_map('trim', explode(',', $origem_outro));
  if (count($partes) >= 4) {
    $stmt_check = $conn->prepare("SELECT id FROM Local WHERE nome = ? AND rua = ? AND nmr = ? AND localidade = ? AND latitude = ? AND longitude = ?");
    $conn->set_charset("utf8mb4");
    $stmt_check->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $origem_lat, $origem_lon);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
      $stmt_check->bind_result($origem);
      $stmt_check->fetch();
      $_SESSION['origem_id'] = $origem;
      $stmt_check->close();
    }else{
      $stmt_check->close();
      $stmt = $conn->prepare("INSERT INTO Local (nome, rua, nmr, localidade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
      $conn->set_charset("utf8mb4");
      $stmt->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $origem_lat, $origem_lon);
      $stmt->execute();
      $origem = $conn->insert_id;
      $_SESSION['origem_id'] = $origem;
      $stmt->close();

    }
  }

  // Inserir destino
  $partes = array_map('trim', explode(',', $destino_outro));
  if (count($partes) >= 4) {
    $stmt_check = $conn->prepare("SELECT id FROM Local WHERE nome = ? AND rua = ? AND nmr = ? AND localidade = ? AND latitude = ? AND longitude = ?");
    $conn->set_charset("utf8mb4");
    $stmt_check->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $destino_lat, $destino_lon);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
      $stmt_check->bind_result($destino);
      $stmt_check->fetch();
      $_SESSION['destino_id'] = $destino;
      $stmt_check->close();
    }else{
      $stmt_check->close();
      $stmt = $conn->prepare("INSERT INTO Local (nome, rua, nmr, localidade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
      $conn->set_charset("utf8mb4");
      $stmt->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $destino_lat, $destino_lon);
      $stmt->execute();
      $destino = $conn->insert_id;
      $_SESSION['destino_id'] = $destino;
      $stmt->close();

    }
  }
  // Remover origem_lat e origem_lon do $_GET após inserção
  unset($_GET['origem_lat'], $_GET['origem_lon']);
  $conn->close();
}


//ESTE É POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  include "scripts/abreconexao.php";

  $carro_id = trim(htmlspecialchars($_POST["carro_id"] ?? ''));
  $origem = trim(htmlspecialchars($_POST["origem"] ?? ''));
  $destino = trim(htmlspecialchars($_POST["destino"] ?? ''));
  $lugares = trim(htmlspecialchars($_POST["lugares"] ?? ''));
  $preco = trim(htmlspecialchars($_POST["preco"] ?? ''));
  $data_hora = trim(htmlspecialchars($_POST["data_hora"] ?? ''));


  $origem_outro = $_POST['origem_outro'] ?? '';
  $destino_outro = $_POST['destino_outro'] ?? '';

  // Se origem personalizada (sem coordenadas)
  if (!empty($origem_outro)) {
    $partes = array_map('trim', explode(',', $origem_outro));
    $latitude = mt_rand(-90000000, 90000000) / 1000000;
    $longitude = mt_rand(-180000000, 180000000) / 1000000;

    $stmt_check = $conn->prepare("SELECT id FROM Local WHERE nome = ? AND rua = ? AND nmr = ? AND localidade = ?");
    $conn->set_charset("utf8mb4");
    $stmt_check->bind_param("ssss", $partes[0], $partes[1], $partes[2], $partes[3]);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
      $stmt_check->bind_result($origem);
      $stmt_check->fetch();
      $stmt_check->close();
    } else {
      $stmt_check->close();
      $stmt = $conn->prepare("INSERT INTO Local (nome, rua, nmr, localidade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
      $conn->set_charset("utf8mb4");
      $stmt->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $latitude, $longitude);
      if ($stmt->execute()) {
      $origem = $conn->insert_id;
      } else {
      $errors[] = "Erro ao inserir a origem personalizada.";
      }
      $stmt->close();
    }
  }
  if (!empty($destino_outro)) {
    $partes = array_map('trim', explode(',', $destino_outro));
    $latitude = mt_rand(-90000000, 90000000) / 1000000;
    $longitude = mt_rand(-180000000, 180000000) / 1000000;

    // Verifica se já existe o destino
    $stmt_check = $conn->prepare("SELECT id FROM Local WHERE nome = ? AND rua = ? AND nmr = ? AND localidade = ?");
    $conn->set_charset("utf8mb4");
    $stmt_check->bind_param("ssss", $partes[0], $partes[1], $partes[2], $partes[3]);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
      $stmt_check->bind_result($destino);
      $stmt_check->fetch();
      $stmt_check->close();
    } else {
      $stmt_check->close();
      $stmt = $conn->prepare("INSERT INTO Local (nome, rua, nmr, localidade, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
      $conn->set_charset("utf8mb4");
      $stmt->bind_param("ssssdd", $partes[0], $partes[1], $partes[2], $partes[3], $latitude, $longitude);
      if ($stmt->execute()) {
        $destino = $conn->insert_id;
      } else {
        $errors[] = "Erro ao inserir o destino personalizado.";
      }
      $stmt->close();
    }
  }

  if (empty($carro_id)) $errors[] = "Por favor, insira o ID do carro.";
  if (empty($origem) || !is_numeric($origem)) $errors[] = "Por favor, insira um ID de origem válido.";
  if (empty($destino) || !is_numeric($destino)) $errors[] = "Por favor, insira um ID de destino válido.";
  if ($origem === $destino) $errors[] = "A origem e o destino não podem ser iguais.";
  if (empty($lugares) || !is_numeric($lugares) || $lugares <= 0) $errors[] = "Por favor, insira um número válido de lugares.";
  if (empty($preco) || !is_numeric($preco) || $preco < 0) $errors[] = "Por favor, insira um preço válido.";
  if (empty($data_hora) || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $data_hora)) $errors[] = "Por favor, insira uma data e hora válidas.";

  if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO Viagens (condutor_id, carro_id, origem, destino, lugares, preco, data_hora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiids", $user['email'], $carro_id, $origem, $destino, $lugares, $preco, $data_hora);
    if ($stmt->execute()) {
      header("Location: criarViagem.php?success=1");
      exit();
    } else {
      $errors[] = "Erro ao criar a viagem.";
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
                  <div class="d-flex align-items-center">
                  <select class="form-select" id="origem" name="origem" required onchange="mostrarOutroOrigem(this)">
                    <option value="" disabled selected>Selecione a origem</option>
                    <?php
                    include "scripts/abreconexao.php";
                    $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $selectedOrigem = $_SESSION['origem_id'] ?? ($_POST['origem'] ?? '');
                    while ($row = $result->fetch_assoc()) {
                      $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                      $nome = htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8');
                      $rua = htmlspecialchars(utf8_encode($row['rua']), ENT_QUOTES, 'UTF-8');
                      $nmr = htmlspecialchars($row['nmr'], ENT_QUOTES, 'UTF-8');
                      $localidade = htmlspecialchars(utf8_encode($row['localidade']), ENT_QUOTES, 'UTF-8');
                      // Se ?success está definido, não seleciona nada automaticamente
                      if (isset($_GET['success']) && $_GET['success'] == 1) {
                        $selected = '';
                      } else {
                       $selected = ($id == $selectedOrigem) ? 'selected' : '';
                      }
                      echo "<option value='$id' $selected>$nome, $rua, $nmr, $localidade</option>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                    <option value="outro" <?php echo (empty($selectedOrigem) && !empty($_POST['origem_outro'])) ? 'selected' : ''; ?>>Outro</option>
                  </select>
                    <a href="pontoMapa.php">
                      <img src="assets/img/placeholder.png" alt="Mapa" style="width: 24px; height: 24px;">
                    </a>
                  
                  </div>
                <input type="text" class="form-control mt-2" id="origemOutro" name="origem_outro" placeholder="ex:. Nome, Rua, Numero, Localidade" style="display:none;" value="<?php echo isset($_POST['origem_outro']) ? htmlspecialchars($_POST['origem_outro']) : ''; ?>" />

                <script>
                function mostrarOutroOrigem(select) {
                  var outroInput = document.getElementById('origemOutro');
                  if (select.value === 'outro') {
                    outroInput.style.display = 'block';
                    outroInput.required = true;
                  } else {
                    outroInput.style.display = 'none';
                    outroInput.required = false;
                    outroInput.value = '';
                  }
                }
                </script>
                </div>
                <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                  <div class="d-flex align-items-center">

                    <select class="form-select" id="destino" name="destino" required onchange="mostrarOutroDestino(this)">
                      <option value="" disabled selected>Selecione o destino</option>
                      <?php
                      include "scripts/abreconexao.php";
                      $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $selectedDestino = $_SESSION['destino_id'] ?? ($_POST['destino'] ?? '');
                      while ($row = $result->fetch_assoc()) {
                      $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                      $nome = htmlspecialchars(utf8_encode($row['nome']), ENT_QUOTES, 'UTF-8');
                      $rua = htmlspecialchars(utf8_encode($row['rua']), ENT_QUOTES, 'UTF-8');
                      $nmr = htmlspecialchars($row['nmr'], ENT_QUOTES, 'UTF-8');
                      $localidade = htmlspecialchars(utf8_encode($row['localidade']), ENT_QUOTES, 'UTF-8');
                      // Se ?success está definido, não seleciona nada automaticamente
                      
                      if (isset($_GET['success']) && $_GET['success'] == 1) {
                        $selected = '';
                      } else {
                       $selected = ($id == $selectedDestino) ? 'selected' : '';
                      }
                      echo "<option value='$id' $selected>$nome, $rua, $nmr, $localidade</option>";
                      }
                      $stmt->close();
                      $conn->close();
                      ?>
                      <option value="outro" <?php echo (empty($selectedDestino) && !empty($_POST['destino_outro']) && $_GET['success']) ? 'selected' : ''; ?>>Outro</option>
                    </select>
                    <a href="pontoMapa.php">
                      <img src="assets/img/placeholder.png" alt="Mapa" style="width: 24px; height: 24px;">
                    </a>
                  </div>

                <input type="text" class="form-control mt-2" id="destinoOutro" name="destino_outro" placeholder="ex:. Nome, Rua, Numero, Localidade" style="display:none;" value="<?php echo isset($_POST['destino_outro']) ? htmlspecialchars($_POST['destino_outro']) : ''; ?>"/>
                <script>
                function mostrarOutroDestino(select) {
                  var outroInput = document.getElementById('destinoOutro');
                  if (select.value === 'outro') {
                    outroInput.style.display = 'block';
                    outroInput.required = true;
                  } else {
                    outroInput.style.display = 'none';
                    outroInput.required = false;
                    outroInput.value = '';
                  }
                }
                </script>
            
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
          <?php 
            if (isset($_GET['success']) && $_GET['success'] == 1) {
              echo "<div class='alert alert-success' id='alert-success'>Viagem criada ou alterada com sucesso!</div>";
              echo "<script>
              window.addEventListener('DOMContentLoaded', function() {
                var alertDiv = document.getElementById('alert-success');
                if (alertDiv) {
                alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
              });
              </script>";
            }
          ?>
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
    <script>
      function deletarViagem(id) {
        if (confirm('Tem a certeza que deseja eliminar esta viagem?')) {
          // Redireciona para o script de exclusão com o ID da viagem
          window.location.href = `scripts/deletarViagem.php?id=${id}`;
        }
      }
    </script>


  </main>
  <script src="vendors/@popperjs/popper.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.min.js"></script>
  <script src="vendors/is/is.min.js"></script>
  <script src="assets/js/theme.js"></script>
  <script>
  function enviarParaMapa() {
    document.getElementById('map_carro_id').value = document.getElementById('carro_id').value;
    document.getElementById('map_origem_outro').value = document.getElementById('origemOutro')?.value || '';
    document.getElementById('map_destino_outro').value = document.getElementById('destinoOutro')?.value || '';
    document.getElementById('mapForm').submit();
  }
  </script>
</body>

</html>
