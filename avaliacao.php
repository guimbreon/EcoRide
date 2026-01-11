<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_avaliacao'])) {
    include 'scripts/abreconexao.php';

    // Obter os dados do formulário
    $reservaId = intval($_POST['idRes']);
    $comentario = trim($_POST['comentario']);
    $avaliacao = intval($_POST['avaliacao']);

    // Verificar se os dados são válidos
    if ($reservaId > 0 && $avaliacao >= 1 && $avaliacao <= 5 && !empty($comentario)) {
        $conn->set_charset("utf8mb4");

        // Atualizar a tabela Reservas
        $stmt = $conn->prepare("UPDATE Reservas SET comentario = ?, avaliacao = ? WHERE idRes = ?");
        if ($stmt) {
            $stmt->bind_param("sii", $comentario, $avaliacao, $reservaId);
            if ($stmt->execute()) {
                header("Location: historico.php?success=1");
              exit();
            } else {
                echo "<script>alert('Erro ao enviar avaliação.');</script>";
            }
            $stmt->close();
        } else {
            echo "<div class='text-danger'>Erro na preparação da consulta: " . htmlspecialchars($conn->error) . "</div>";
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos corretamente.');</script>";
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
    <style>
    .stars {
      direction: rtl;
      unicode-bidi: bidi-override;
      justify-content: flex-start;
      display: flex;
      flex-direction: row;
    }
    .stars input[type="radio"] {
      display: none;
    }
    .stars label {
      font-size: 2rem;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
      order: 0;
    }
    .stars label::before {
      content: "★";
    }
    .stars label:hover,
    .stars label:hover ~ label,
    .stars input[type="radio"]:checked ~ label {
      color: #f1a501;
    }
    </style>

    </style>

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
          <h2 class="hero-title" style="font-size:1.5rem;">Deixe a sua avaliação</h2>
        </div>
          </div>
        </div>
      </section>
        <div class="container py-5">
          <div class="row">
            <!-- Coluna: Detalhes da Reserva -->
            <div class="col-md-4 border-end" style="height:500px;overflow-y:auto;" id="user-list">
              <?php
                include 'scripts/abreconexao.php';

                if (!$conn || $conn->connect_error) {
                  echo '<div class="text-danger">Erro na conexão com a base de dados: ' . htmlspecialchars($conn->connect_error) . '</div>';
                } else {
                  $reservaId = isset($_GET['idRes']) ? intval($_GET['idRes']) : null;

                  if ($reservaId) {
                    $conn->set_charset("utf8mb4");
                    $stmt = $conn->prepare(
                      "SELECT 
                        ori.nome AS origem_nome,
                        dest.nome AS destino_nome,
                        v.data_hora,
                        c.nome AS nome_condutor
                      FROM Reservas r
                      INNER JOIN Viagens v ON r.viagem_id = v.id
                      INNER JOIN Utilizadores p ON r.idPass = p.email
                      INNER JOIN Utilizadores c ON v.condutor_id = c.email
                      INNER JOIN Local dest ON v.destino = dest.id
                      INNER JOIN Local ori ON v.origem = ori.id
                      WHERE r.idRes = ?"
                    );
                    if ($stmt) {
                      $stmt->bind_param("i", $reservaId);
                      $stmt->execute();
                      $result = $stmt->get_result();

                      if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<div class="mb-3 p-2 border rounded bg-light">';
                        echo '<strong>Condutor:</strong> ' . htmlspecialchars($row['nome_condutor']) . '<br>';
                        echo '<strong>Origem:</strong> ' . htmlspecialchars($row['origem_nome']) . '<br>';
                        echo '<strong>Destino:</strong> ' . htmlspecialchars($row['destino_nome']) . '<br>';
                        echo '<strong>Data/Hora:</strong> ' . date('d/m/Y H:i', strtotime($row['data_hora'])) . '<br>';
                        echo '</div>';
                      } else {
                        echo '<div class="text-muted">Reserva não encontrada.</div>';
                      }
                      $stmt->close();
                    } else {
                      echo '<div class="text-danger">Erro na preparação da consulta: ' . htmlspecialchars($conn->error) . '</div>';
                    }
                  } else {
                    echo '<div class="text-muted">Nenhuma reserva selecionada.</div>';
                  }
                  $conn->close();
                }
              ?>
            </div>

            <!-- Coluna: Avaliação -->
            <div class="col-md-8">
              <form method="POST" action="avaliacao.php">
                <input type="hidden" name="idRes" value="<?php echo htmlspecialchars($_GET['idRes']); ?>">
                <label for="avaliacao" class="form-label">Avaliação</label>
                <div class="mb-3">
                  <div class="stars d-flex mt-2 justify-content-start" style="justify-content: flex-start !important;">
                    <input type="radio" name="avaliacao" id="star5" value="5">
                    <label for="star5" title="5 Estrelas" class="me-2"></label>

                    <input type="radio" name="avaliacao" id="star4" value="4">
                    <label for="star4" title="4 Estrelas" class="me-3"></label>

                    <input type="radio" name="avaliacao" id="star3" value="3">
                    <label for="star3" title="3 Estrelas" class="me-3"></label>

                    <input type="radio" name="avaliacao" id="star2" value="2">
                    <label for="star2" title="2 Estrelas" class="me-3"></label>

                    <input type="radio" name="avaliacao" id="star1" value="1">
                    <label for="star1" title="1 Estrela" class="me-3"></label>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="comentario" class="form-label">Avaliação</label>
                  <textarea class="form-control" id="comentario" name="comentario" rows="6" placeholder="Escreva o seu comentário aqui..."></textarea>
                </div>
                <div class="d-flex justify-content-end">
                  <button class="btn btn-primary me-2" type="submit" name="enviar_avaliacao">Enviar</button>
                </div>
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
    <script>
      document.querySelector('form').addEventListener('submit', function (e) {
        const avaliacao = document.querySelector('input[name="avaliacao"]:checked');
        const comentario = document.getElementById('comentario').value.trim();
        if (!avaliacao || comentario === '') {
          e.preventDefault();
          alert('Por favor, preencha todos os campos antes de enviar.');
        }
      });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  </body>

</html>