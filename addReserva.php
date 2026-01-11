<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
}
$user = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include "scripts/abreconexao.php";

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $idPass = $_SESSION["user"]["email"];
  $viagemId = isset($_GET["idVi"]) ? (int)$_GET["idVi"] : NULL;
  $pontoRecolha = isset($_POST["pontoRecolha"]) ? (int)$_POST["pontoRecolha"] : 0;
  $numLugares = isset($_POST["numLugares"]) ? (int)$_POST["numLugares"] : 1;
  $avaliacao = null;

  // Validar viagem
  $viagemCheckStmt = $conn->prepare("SELECT origem, destino, carro_id, preco, lugares FROM Viagens WHERE id = ?");
  if ($viagemCheckStmt === false) {
    die("Prepare failed: " . $conn->error);
  }
  $viagemCheckStmt->bind_param("i", $viagemId);
  $viagemCheckStmt->execute();
  $viagemCheckStmt->bind_result($origemId, $destinoId, $carroId, $preco, $lugaresDisponiveis);
  if (!$viagemCheckStmt->fetch()) {
    $viagemCheckStmt->close();
    $conn->close();
    die("Erro: O ID da viagem não existe.");
  }
  $viagemCheckStmt->close();

  if ($numLugares < 1 || $numLugares > $lugaresDisponiveis) {
    $conn->close();
    die("Erro: Número de lugares inválido ou não disponível.");
  }

  $stmt = $conn->prepare("INSERT INTO Reservas (idPass, viagem_id, pontoRecolha, avaliacao, preco) VALUES (?, ?, ?, ?, ?)");
  if ($stmt === false) {
    $conn->close();
    die("Prepare failed: " . $conn->error);
  }

  for ($i = 1; $i <= $numLugares; $i++) {
    $stmt->bind_param("siisd", $idPass, $viagemId, $pontoRecolha, $avaliacao, $preco);
    if (!$stmt->execute()) {
      $stmt->close();
      $conn->close();
      die("Erro ao adicionar reserva: " . $stmt->error);
    }
  }

  $stmt->close();
  $conn->close();
  header("Location: home.php?sucesso=reserva");
  exit();
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
    <section class="container my-5">
      <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-body">
          <h2 class="card-title mb-4 text-center">Reserva de Viagem</h2>
          <p class="mb-4 text-center">Preencha os detalhes da sua reserva abaixo:</p>
            <form action="addReserva.php?idVi=<?php echo isset($_GET['idVi']) ? htmlspecialchars($_GET['idVi'], ENT_QUOTES, 'UTF-8') : ''; ?>" method="post" class="text-start">
            <input type="hidden" id="viagemId" name="viagemId" value="">
            <div class="mb-3">
              <label for="pontoRecolha" class="form-label">Selecione o ponto de recolha:</label>
              <select id="pontoRecolha" name="pontoRecolha" class="form-select" required>
                <option value="" disabled selected>Escolha uma opção</option>
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
            <?php
            // Obter o número máximo de lugares disponíveis para a viagem selecionada
            $maxLugares = 10; // valor padrão
            if (isset($_GET['idVi'])) {
              include "scripts/abreconexao.php";
              $viagemId = (int)$_GET['idVi'];
              $stmt = $conn->prepare("SELECT lugares FROM Viagens WHERE id = ?");
              if ($stmt) {
                $stmt->bind_param("i", $viagemId);
                $stmt->execute();
                $stmt->bind_result($lugaresDisponiveis);
                if ($stmt->fetch()) {
                  $maxLugares = (int)$lugaresDisponiveis;
                }
                $stmt->close();
              }
              $conn->close();
            }
            ?>
            <div class="mb-3">
              <label for="numLugares" class="form-label">Quantos lugares deseja reservar?</label>
              <input type="number" id="numLugares" name="numLugares" class="form-control" min="1" max="<?php echo $maxLugares; ?>" value="1" required>
              <small class="form-text text-muted">Máximo disponível: <?php echo $maxLugares; ?></small>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
              <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </section>
  
      <!-- <footer> begin ============================-->
      <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-left">
          <div class="row text-center text-md-left">
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 font-weight-bold text-warning">EcoRide</h5>
              <p>EcoRide é a alternativa de transporte mais eficiente e sustentável. Partilhe a viagem e reduza a pegada de carbono!</p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Links</h5>
              <p><a href="index.php" class="text-white text-decoration-none">Home</a></p>
              <p><a href="about.php" class="text-white text-decoration: none;">Sobre nós</a></p>
              <p><a href="contact.php" class="text-white text-decoration: none;">Contactos</a></p>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contactos</h5>
              <p><i class="fas fa-home mr-3"></i> Lisboa, Portugal</p>
              <p><i class="fas fa-envelope mr-3"></i> info@ecoride.com</p>
              <p><i class="fas fa-phone mr-3"></i> +351 234 567 890</p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Siga-nos</h5>
              <a href="#" class="text-white text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="text-white text-decoration: none;"><i class="fab fa-twitter"></i></a>
              <a href="#" class="text-white text-decoration: none;"><i class="fab fa-instagram"></i></a>
            </div>
          </div>

          <div class="row align-items-center mt-3">
            <div class="col-md-7 col-lg-8">
              <p class="text-center text-md-left">© 2023 EcoRide. Todos os direitos reservados.</p>
            </div>
          </div>
        </div>
      </footer>
      <!-- <footer> close ============================-->


  </main>
  <script src="vendors/@popperjs/popper.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.min.js"></script>
  <script src="vendors/is/is.min.js"></script>
  <script src="assets/js/theme.js"></script>
</body>

</html>
