<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
}
$user = $_SESSION['user'];

$errors = [];
$file_path = '';

if (!empty($_SESSION['isCarSet'])) {

  header("Location: #destination");
  unset($_SESSION['isCarSet']);
  exit();

} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
  include "scripts/abreconexao.php"; // Include your database connection file

  $marca = trim(htmlspecialchars($_POST["marca"] ?? ''));
  $modelo = trim(htmlspecialchars($_POST["modelo"] ?? ''));
  $cor = trim(htmlspecialchars($_POST["cor"] ?? ''));
  $matricula = trim(htmlspecialchars($_POST["matricula"] ?? ''));
  $combustivel = trim(htmlspecialchars($_POST["combustivel"] ?? ''));

  // Validate required fields
  if (empty($marca) || !is_string($marca) || mysqli_real_escape_string($conn, $marca) != $marca) {
    $errors[] = "Por favor, insira uma marca válida do carro.";
  }
  if (empty($modelo) || !is_string($modelo) || mysqli_real_escape_string($conn, $modelo) != $modelo) {
    $errors[] = "Por favor, insira um modelo válido do carro.";
  }
  if (empty($cor) || !is_string($cor) || mysqli_real_escape_string($conn, $cor) != $cor) {
    $errors[] = "Por favor, insira uma cor válida do carro.";
  }
  if (empty($matricula) || !preg_match('/^([A-Z]{2}-\d{2}-[A-Z]{2}|\d{2}-[A-Z]{2}-[A-Z]{2}|[A-Z]{2}-[A-Z]{2}-\d{2})$/', $matricula) || mysqli_real_escape_string($conn, $matricula) != $matricula) {
    $errors[] = "Por favor, insira uma matrícula válida do carro (exemplo: AA-12-BB, 12-AA-AA ou AA-AA-11).";
  }
  if (empty($combustivel) || !in_array($combustivel, ["Gasolina", "Diesel", "Eletrico", "Hibrido"])) {
    $errors[] = "Por favor, selecione um tipo de combustível válido.";
  }

  // Handle file upload
  if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists and rename if necessary
    $file_base_name = pathinfo($target_file, PATHINFO_FILENAME);
    $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
    $counter = 1;

    while (file_exists($target_file)) {
      $target_file = $target_dir . $file_base_name . "($counter)." . $file_extension;
      $counter++;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 2000000) {
      $errors[] = "Desculpe, o arquivo é muito grande. O tamanho máximo permitido é de 2MB.";
    }

    // Validate file type
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($file_type, $allowed_types)) {
      $errors[] = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    }

    // Move uploaded file
    if (empty($errors) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $file_path = $target_file;
    } else {
      $errors[] = "Erro ao fazer upload do arquivo.";
    }
  }

  // Check if the email already exists in the database
  if (empty($errors)) {
    $stmt = $conn->prepare("SELECT matricula FROM Carros WHERE matricula = ?");
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "A matrícula já está registrada. Por favor, registre outro carro.";
    }

    $stmt->close();
  }

  // If no errors, redirect to scripts/addCar.php
  if (empty($errors)) {
    $data = [
      'marca' => $marca,
      'modelo' => $modelo,
      'cor' => $cor,
      'matricula' => $matricula,
      'combustivel' => $combustivel,
      'ft_carro' => $file_path
    ];
    header("Location: scripts/addCar.php?" . http_build_query($data));
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>EcoRide</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />

  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="index.php"><h1>ECORIDE</h1></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
            <?php include 'scripts/checkLogin.php'; ?>
            </ul>
              <li class="nav-item dropdown px-3 px-lg-0"> <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">EN</a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:0.3rem;" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#!">PT</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <section style="padding-top: 7rem;">
        <div class="bg-holder" style="background-image:url(assets/img/hero/hero-bg.jpg);">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row align-items-center">
        <div class="col-md-12 text-center py-6">
          <h1 class="hero-title">Registro de Carros</h1>
          <p class="mb-4 fw-medium">Adicione e gerencie os seus veículos para oferecer viagens com EcoRide.</p>
            <div class="text-center">
            <h2 class="mb-4">Adicionar Novo Carro</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="text-start mx-auto" style="max-width: 400px;">
              <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" required>
              </div>
              <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required>
              </div>
              <div class="mb-3">
                <label for="cor" class="form-label">Cor</label>
                <input type="text" class="form-control" id="cor" name="cor" required>
              </div>
              <div class="mb-3">
                <label for="matricula" class="form-label">Matrícula</label>
                <input type="text" class="form-control" id="matricula" name="matricula" required>
              </div>
                <div class="mb-3">
                <label for="combustivel" class="form-label">Combustível</label>
                <select class="form-select" id="combustivel" name="combustivel" required>
                  <option value="" disabled selected>Selecione o tipo de combustível</option>
                  <option value="Gasolina">Gasolina</option>
                  <option value="Diesel">Diesel</option>
                  <option value="Eletrico">Elétrico</option>
                  <option value="Hibrido">Híbrido</option>
                </select>
                </div>
              <div class="d-flex justify-content-center">
                <div class="mb-3" style="width: 100%; max-width: 550px;">
                    <label for="fileToUpload" class="form-label">Selecione uma imagem:</label>
                    <input type="file" class="form-control form-control-sm" id="fileToUpload" name="fileToUpload">
                    <div class="invalid-feedback">
                        Por favor, selecione um arquivo válido.
                    </div>
                </div>
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
              <button type="submit" class="btn btn-primary w-100">Adicionar Carro</button>
            </form>

            </div>
        </div>
          </div>
        </div>
      </section>



      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="pt-5" id="destination">

        <div class="container">
          <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
          <div class="mb-7 text-center">
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Os Meus Carros</h3>
          </div>

          <div class="row">
                <?php
                include 'scripts/carro.php';
                include "scripts/abreconexao.php"; // Include your database connection file

                $stmt = $conn->prepare("SELECT marca, modelo, cor, matricula, ft_carro, combustivel FROM Carros WHERE id_dono = ? ORDER BY matricula ASC");
                $stmt->bind_param("s", $user['email']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                  while ($carro = $result->fetch_assoc()) {
                  $ft_carro = !empty($carro['ft_carro']) ? $carro['ft_carro'] : 'assets/img/ecoRide.png'; // Provide a default image if ft_carro is empty
                  echo renderCarroCard($carro['marca'], $carro['modelo'], $carro['cor'], $carro['matricula'], $carro['combustivel'], $ft_carro);
                  }
                } else {
                  echo "<p class='text-center'>Nenhum carro registrado.</p>";
                }

                $stmt->close();
                $conn->close();
                
                ?>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->
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

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  </body>

</html>