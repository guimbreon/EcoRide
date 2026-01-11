<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}
$user = $_SESSION['user'];
if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'reserva') {
  echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
      var section = document.getElementById('destination');
      if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
      }
    });
  </script>";
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
      <section style="padding-top: 7rem;">
        <div class="bg-holder" style="background-image:url(assets/img/hero/hero-bg.jpg);">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row align-items-center">
        <div class="col-md-12 text-center py-6">
          <h1 class="hero-title">Bem-vindo,<br><?php echo htmlspecialchars($user['nome']); ?>!</h1>
          <p class="mb-4 fw-medium">Estamos felizes em tê-lo de volta. Explore nossos serviços e aproveite sua viagem com EcoRide.</p>
          <div class="text-center">
            <?php
          // Check the user type
            if (isset($user['tipo']) && $user['tipo'] == 'Condutores') {
            echo '<a class="btn btn-primary btn-lg me-md-4 mb-3 mb-md-0 border-0 primary-btn-shadow" href="regCarros.php" role="button">Os Meus Carros</a>';
            echo '<a class="btn btn-primary btn-lg me-md-4 mb-3 mb-md-0 border-0 primary-btn-shadow" href="criarViagem.php" role="button">Criar Viagem</a>';
            } elseif (isset($user['tipo']) && $user['tipo'] == 'Passageiros') {
            echo '<a class="btn btn-primary btn-lg me-md-4 mb-3 mb-md-0 border-0 primary-btn-shadow" href="historico.php" role="button">Histórico</a>';
            }
            ?>
            <a class="btn btn-primary btn-lg me-md-4 mb-3 mb-md-0 border-0 primary-btn-shadow" href="about.php" role="button">Sobre nós</a>
            <a class="btn btn-secondary btn-lg me-md-4 mb-3 mb-md-0 border-0 primary-btn-shadow" href="scripts/logout.php" role="button">Logout</a>
          </div>
        </div>
          </div>
        </div>
      </section>



      <!-- ============================================-->
      <!-- <section> Viagens Passageiros begin ============================-->
      <?php
      if (isset($user['tipo']) && $user['tipo'] == 'Passageiros') {
      ?>
      <section class="pt-5" id="destination">

        <div class="container">
          <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
          <div class="mb-7 text-center">
            <h5 class="text-secondary">Viagens Futuras</h5>
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Viagens Futuras</h3>
          </div>
            <div class="row">
              <?php
              include 'scripts/viagemPassag.php';
              include 'scripts/abreconexao.php'; // Assuming this file contains the database connection logic

              // Get the user email from the session
              $userEmail = $_SESSION['user']['email'];

                // Query to fetch all reservations for the logged-in user with a date after the current date
                $query = "
                SELECT 
                r.idRes AS idReserva, 
                r.avaliacao, 
                r.preco, 
                v.data_hora, 
                r.pontoRecolha,
                pr.nome AS PontoRecolhaNome,
                l1.nome AS origem, 
                l2.nome AS destino, 
                v.lugares,
                l1.localidade AS localidade
                FROM 
                Reservas r
                INNER JOIN 
                Viagens v ON r.viagem_id = v.id
                INNER JOIN 
                Local l1 ON v.origem = l1.id
                INNER JOIN 
                Local l2 ON v.destino = l2.id
                INNER JOIN
                Local pr ON r.pontoRecolha = pr.id
                WHERE 
                r.idPass = ? AND v.data_hora > NOW()
                ORDER BY 
                v.data_hora DESC
                ";
              $conn->set_charset("utf8mb4");

              $stmt = $conn->prepare($query);
              $stmt->bind_param("s", $userEmail);
              $stmt->execute();
              $result = $stmt->get_result();

              // Render each reservation as a card
              while ($row = $result->fetch_assoc()) {
              $date = date("d/m/Y - H:i", strtotime($row['data_hora']));
              $origem = $row['origem'];
              $destino = $row['destino'];
              $localidade = $row['localidade']; // Add localidade if needed
              $lugares = $row['lugares'];
              $preco = $row['preco']; // Fetch the price
              $pontoRecolha = $row['PontoRecolhaNome']; // Fetch the pickup point
              $idReserva = $row['idReserva'];
              
              
              echo renderViagemCard($date, $origem, $destino, $localidade, $preco, $pontoRecolha, $idReserva);

              }
              ?>
              <?php
              // Guarda o tempo atual na sessão para ser usado pelo reservaUpdates.php
              $_SESSION['last_check'] = time();
              ?>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                  console.log('Reserva update script loaded'); // LOG: Script loaded
                  let lastCheck = Math.floor(Date.now() / 1000);
                  setInterval(function() {
                  console.log('Calling reservaUpdates.php with lastCheck:', lastCheck); // LOG: Before fetch
                  fetch('scripts/reservaUpdates.php?lastCheck=' + lastCheck)
                    .then(response => response.json())
                    .then(data => {
                    console.log('Received data:', data); // LOG: After fetch
                    if (Array.isArray(data.ids)) {
                      data.ids.forEach(id => {
                      // Procura o botão de chat com o idRes correspondente
                      const chatBtn = document.querySelector(`a[href="chat.php?idRes=${id}"]`);
                      if (chatBtn) {
                        // Sobe até o card (ajuste se necessário para o seu HTML)
                        let cardDiv = chatBtn.closest('.col-md-4.mb-4');
                        if (cardDiv) {
                        // Busca o HTML atualizado do card
                        console.log('Fetching updated card for idRes:', id); // LOG: Before card fetch
                        fetch(`scripts/renderViagemCard.php?idRes=${id}`)
                          .then(resp => resp.text())
                          .then(cardHtml => {
                          console.log('Updating card HTML for idRes:', id); // LOG: After card fetch
                          cardDiv.outerHTML = cardHtml;
                          });
                        }
                      }
                      });
                    }
                    if (typeof data.now === 'number') {
                      lastCheck = data.now;
                    }
                    });
                  }, 1000);
                });
                </script>
              <?php
              $stmt->close();
              $conn->close();
              ?>
            </div>
        </div><!-- end of .container-->

      </section>
      <?php
      }
      ?>
      <!-- <section> close ============================-->



      <!-- <section> Viagens Condutores begin ============================-->
      <?php
      if (isset($user['tipo']) && $user['tipo'] == 'Condutores') {
      ?>
      <section class="pt-5" id="destination">

        <div class="container">
          <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
          <div class="mb-7 text-center">
            <h5 class="text-secondary">Veja as reservas das suas viagens</h5>
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Reservas das suas Viagens</h3>
          </div>
            <div class="row" id="viagensCondutor">
              <?php
              include 'scripts/viagemPassag.php';
              include 'scripts/abreconexao.php'; // Assuming this file contains the database connection logic

              // Get the user email from the session
              $userEmail = $_SESSION['user']['email'];

              // Query to fetch all reservations for the logged-in user with a date after the current date
              $query = "
              SELECT 
                r.idRes AS idReserva, 
                r.avaliacao, 
                r.preco, 
                v.data_hora,
                v.condutor_id,
                r.pontoRecolha,
                pr.nome AS PontoRecolhaNome,
                l1.nome AS origem, 
                l2.nome AS destino, 
                v.lugares,
                l1.localidade AS localidade,
                r.updated_at
              FROM 
                Reservas r
              INNER JOIN 
                Viagens v ON r.viagem_id = v.id
              INNER JOIN 
                Local l1 ON v.origem = l1.id
              INNER JOIN 
                Local l2 ON v.destino = l2.id
              INNER JOIN
                Local pr ON r.pontoRecolha = pr.id
              WHERE 
                v.condutor_id = ? AND v.data_hora > NOW()
              ORDER BY 
                r.updated_at DESC
                , v.data_hora 
              ";
              $conn->set_charset("utf8mb4");

              $stmt = $conn->prepare($query);
              $stmt->bind_param("s", $userEmail);
              $stmt->execute();
              $result = $stmt->get_result();

              // Render each reservation as a card
              while ($row = $result->fetch_assoc()) {
                $date = date("d/m/Y - H:i", strtotime($row['data_hora']));
                $origem = $row['origem'];
                $destino = $row['destino'];
                $localidade = $row['localidade'];
                $lugares = $row['lugares'];
                $preco = $row['preco'];
                $pontoRecolha = $row['PontoRecolhaNome'];
                $idReserva = $row['idReserva'];

                $ultimo_login = date('Y-m-d H:i:s');
                $updated_at = $row['updated_at'];
                // Verifica se a reserva foi atualizada entre o último login e agora
                echo "<script>console.log('Último login do condutor: " . addslashes($ultimo_login) . "');</script>";
                // Considera nova reserva se updated_at for nos últimos 10 segundos
                if ($updated_at !== NULL) {
                    $novaReserva = (strtotime($updated_at) >= (time() + 3600));
                } else {
                  $novaReserva = false;
                }

                echo renderViagemCard($date, $origem, $destino, $localidade, $preco, $pontoRecolha, $idReserva, $novaReserva);
              }

              $stmt->close();
              $conn->close();
              ?>
              <script>
              document.addEventListener('DOMContentLoaded', function() {
                console.log('Reserva update script loaded'); // LOG: Script loaded
                // Set lastCheck to current time in Lisbon (Europe/Lisbon timezone)
                let nowLisbon = new Date().toLocaleString("en-US", { timeZone: "Europe/Lisbon" });
                let lastCheck = Math.floor(new Date(nowLisbon).getTime() / 1000);
                console.log('Initial lastCheck:', lastCheck);
                function doUpdate() {
                  console.log('Calling reservaUpdates.php with lastCheck:', lastCheck); // LOG: Before fetch
                  fetch('scripts/reservaUpdates.php?lastCheck=' + lastCheck)
                    .then(response => response.json())
                    .then(data => {
                      console.log('Received data:', data); // LOG: After fetch
                      if (Array.isArray(data.ids)) {
                        data.ids.forEach(id => {
                          // Procura o botão de chat com o idRes correspondente
                          const chatBtn = document.querySelector(`a[href="chat.php?idRes=${id}"]`);
                          if (chatBtn) {
                            // Sobe até o card (ajuste se necessário para o seu HTML)
                            let cardDiv = chatBtn.closest('.col-md-4.mb-4');
                            if (cardDiv) {
                              // Busca o HTML atualizado do card
                              console.log('Fetching updated card for idRes:', id); // LOG: Before card fetch
                              fetch(`scripts/renderViagemCard.php?idRes=${id}`)
                                .then(resp => resp.text())
                                .then(cardHtml => {
                                  console.log('Updating card HTML for idRes:', id); // LOG: After card fetch
                                  // Substitui o card existente pelo novo HTML
                                  cardDiv.outerHTML = cardHtml;
                                });
                            }
                          } else {
                            // Se não existe, renderiza um novo card e adiciona ao início da lista
                            const row = document.querySelector('#viagensCondutor');
                            fetch(`scripts/renderViagemCard.php?idRes=${id}`)
                              .then(resp => resp.text())
                              .then(cardHtml => {
                                if (row) {
                                  // Insere o novo card no início
                                  row.insertAdjacentHTML('afterbegin', cardHtml);
                                }
                              });
                          }
                        });

                        // Salva os ids retornados para comparar depois
                        if (window._lastIds && window._lastIds.length > 0) {
                          // Verifica se há algum id diferente (novo ou removido)
                          const newIds = data.ids.filter(x => !window._lastIds.includes(x));
                          const removedIds = window._lastIds.filter(x => !data.ids.includes(x));
                          // Se houver algum removido, chama renderViagemCardFalse.php para cada um
                          removedIds.forEach(id => {
                            fetch(`scripts/renderViagemCardFalse.php?idRes=${id}`)
                              .then(resp => resp.text())
                              .then(cardHtml => {
                                // Atualiza ou remove o card correspondente
                                const chatBtn = document.querySelector(`a[href="chat.php?idRes=${id}"]`);
                                if (chatBtn) {
                                  let cardDiv = chatBtn.closest('.col-md-4.mb-4');
                                  if (cardDiv) {
                                    cardDiv.outerHTML = cardHtml;
                                  }
                                }
                              });
                          });
                        }
                        window._lastIds = data.ids;
                      }

                      if (typeof data.now === 'number') {
                        lastCheck = data.now; // update lastCheck for next run
                      }
                    })
                    .finally(() => {
                      setTimeout(doUpdate, 5000); // schedule next run after current finishes
                    });
                }

                doUpdate(); // start the first run
              });
              </script>
            </div>
        </div><!-- end of .container-->

      </section>
      <?php
      }
      ?>
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