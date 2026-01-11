<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
$user = $_SESSION['user'];

include "scripts/abreconexao.php";
// Obter o email do usuário logado
$userEmail = $user["email"];

// Configurar a conexão para usar UTF-8 para suportar caracteres especiais e acentos
$conn->set_charset("utf8mb4");

// Consulta para buscar as reservas e informações das viagens associadas
$query = "
  SELECT 
    r.idRes AS idRes,
    v.data_hora AS data,
    l_origem.nome AS origem,
    l_destino.nome AS destino,
    l_destino.localidade AS localidade,
    v.lugares AS lugares,
    r.preco AS preco,
    l_ponto_recolha.nome AS pontoRecolha,
    r.comentario AS comentario,
    r.avaliacao AS avaliacao
  FROM Reservas r
  INNER JOIN Viagens v ON r.viagem_id = v.id
  INNER JOIN Local l_origem ON v.origem = l_origem.id
  INNER JOIN Local l_destino ON v.destino = l_destino.id
  INNER JOIN Local l_ponto_recolha ON r.pontoRecolha = l_ponto_recolha.id
  WHERE r.idPass = ?
  AND v.data_hora < NOW()
  ORDER BY v.data_hora DESC
";
$stmt = $conn->prepare($query);

// Verificar se a consulta foi preparada corretamente
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Passar o email como parâmetro (tipo string)
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// Armazenar os dados das viagens em um array
$viagens = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $viagens[] = $row;
    }
}


$stmt->close();
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
          <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
          <div class="mb-7 text-center">
            <h5 class="text-secondary">Histórico</h5>
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Histórico de viagens</h3>
                      <?php 
              if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo "<div class='alert alert-success'>Viagem avaliada com sucesso!</div>";
              }?>
          </div>
          <div class="row">
                    <?php
                    include "scripts/viagemPassag.php";
                    if (!empty($viagens)) {
                        foreach ($viagens as $viagem) {
                          $isRated = !empty($viagem["comentario"]) && !empty($viagem["avaliacao"]);
                          
                          echo renderViagemCard($viagem['data'], $viagem['origem'], $viagem['destino'], $viagem['localidade'], $viagem['preco'], $viagem['pontoRecolha'],$viagem['idRes'], false, $isRated, $viagem["comentario"], $viagem["avaliacao"]);
                          
                        }
                    } else {
                        echo "<p class='text-center'>Nenhuma viagem encontrada no histórico.</p>";
                    }
                    ?>
                </div>
                <div id="reviewOverlay" class="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000;"></div>
                <div id="reviewPopup" class="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; width: 50%; max-width: 600px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); z-index: 1001; border-radius: 10px;">
                  <h4>Deixe a sua avaliação</h4>
                  <div class="stars">
                    <input type="radio" name="rating" id="star5" value="1">
                    <label for="star5" title="5 estrelas"></label>

                    <input type="radio" name="rating" id="star4" value="2">
                    <label for="star4" title="4 estrelas"></label>

                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3" title="3 estrelas"></label>

                    <input type="radio" name="rating" id="star2" value="4">
                    <label for="star2" title="2 estrelas"></label>

                    <input type="radio" name="rating" id="star1" value="5">
                    <label for="star1" title="1 estrela"></label>
                  </div>
                  <textarea class="form-control mb-3" rows="6" name="comentario" placeholder="Escreva o seu comentário aqui..."></textarea>
                  <div class="d-flex justify-content-end">
                    <button class="btn btn-primary me-2" type="submit" name="enviar_avaliacao" onclick="submitReview()">Enviar</button>
                    <button class="btn btn-secondary" type="button" onclick="closeReviewPopup()">Fechar</button>
                  </div>
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