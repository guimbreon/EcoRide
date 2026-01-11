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
      <div class="container">
          <a class="navbar-brand" href="index.php">
              <h1>ECORIDE</h1>
          </a>
          <!-- Botão do navbar-toggler -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Conteúdo do navbar -->
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
                  <?php include 'scripts/checkLogin.php'; ?>
                  <li class="nav-item dropdown px-3 px-lg-0">
                      <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          EN
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:0.3rem;" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item" href="#!">PT</a></li>
                      </ul>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
      <section style="padding-top: 6rem; padding-bottom: 1rem;">
        <div class="container">
          <div class="row">
        <div class="col-md-12 text-center py-2">
          <h2 class="hero-title" style="font-size:1.5rem;">Chat</h2>
        </div>
          </div>
        </div>
      </section>
      <!-- <section> begin ============================-->
      <div class="container py-5">
        <div class="row">
          <!-- Main: Chat -->
          <div class="col-md-9 d-flex flex-column" style="height:500px;">
            <div class="flex-grow-1 border-bottom mb-2" id="chat-messages" style="overflow-y:auto;">
              
            </div>
            <form id="chat-form" class="d-flex mt-2">
              <input type="text" class="form-control me-2" id="chat-input" placeholder="Type a message..." autocomplete="off">
              <button class="btn btn-primary" type="submit">Send</button>
            </form>
          </div>
        </div>
      </div>

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
    <script src="scripts/navbar.js"></script>
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  </body>

</html>