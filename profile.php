<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}

include "scripts/abreconexao.php"; // Conectar ao banco de dados
$user = $_SESSION['user'];
$email = $user['email'];

// Preparar consulta SQL para buscar informações do usuário
$stmt = $conn->prepare("SELECT nome, NIF, telemovel, email, ft_perfil FROM Utilizadores WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se o usuário foi encontrado
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc(); // Obter dados do usuário
} else {
    die("Erro: Usuário não encontrado.");
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
$user = $_SESSION['user'];
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
            <div class="col-md-10">
                    </div>
                        <div class="row">
                            <!-- Coluna dos Botões -->
                            <div class="col-md-3 d-flex flex-column align-items-start mt-4">
                              <a href="editProfile.php" class="btn btn-primary mb-3 w-100">Editar Perfil</a>
                              <a href="scripts/logout.php" class="btn btn-danger w-100">Logout</a>
                            </div>

                            <!-- Linha de Separação -->
                            <div class="col-md-1 d-flex justify-content-center">
                                <div style="border-right: 1px solid #ddd; height: 100%;"></div>
                            </div>

                           <!-- Coluna do Perfil -->
                    <div class="col-md-8 text-center">
                        <!-- Título do Perfil -->
                        <h2>Perfil do Usuário</h2>

                        <!-- Foto do Perfil -->
                        <div class="mb-3">
                            <?php if (!empty($userData['ft_perfil'])): ?>
                                <img src="<?php echo htmlspecialchars($userData['ft_perfil']); ?>" alt="Foto de Perfil" 
                                    class="rounded-circle shadow" style="width: 150px; height: 150px;">
                            <?php else: ?>
                                <img src="assets/img/default-profile.png" alt="Foto Padrão" 
                                    class="rounded-circle shadow" style="width: 150px; height: 150px;">
                            <?php endif; ?>
                        </div>

                        <!-- Informações do Usuário -->
                        <h4><?php echo htmlspecialchars($userData['nome']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($userData['email']); ?></p>
                        <p><strong>Telemóvel:</strong> <?php echo htmlspecialchars($userData['telemovel']); ?></p>
                        <p><strong>NIF:</strong> <?php echo htmlspecialchars($userData['NIF']); ?></p>
                        <?php
                        // Verifica se o utilizador é condutor
                        include "scripts/abreconexao.php";
                        $stmtCondutor = $conn->prepare("SELECT aval FROM Condutores WHERE id = ?");
                        $stmtCondutor->bind_param("s", $email);
                        $stmtCondutor->execute();
                        $resultCondutor = $stmtCondutor->get_result();
                        if ($resultCondutor->num_rows > 0) {
                          $condutor = $resultCondutor->fetch_assoc();
                          $avaliacao = $condutor['aval'];
                          echo '<p><strong>Avaliação:</strong> ';
                          if ($avaliacao !== null) {
                            echo number_format($avaliacao, 1) . ' / 5';
                          } else {
                            echo 'Sem avaliações';
                          }
                          echo '</p>';
                        }
                        $stmtCondutor->close();
                        $conn->close();
                        ?>
                    </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      </section>



      <!-- ============================================-->
      
      <!-- ============================================-->
      <!-- <footer> begin ============================-->
      <footer class="bg-dark text-white pt-5 pb-4 fixed-bottom" style="width:100%;">
        <div class="container text-center text-md-left">
          <div class="row text-center text-md-left">
        <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3

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