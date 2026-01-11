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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block shadow" style="background-color: #f8f9fa;" data-navbar-on-scroll="data-navbar-on-scroll">
      <div class="container"><a class="navbar-brand" href="index.php"><h1>ECORIDE - ADMIN</h1></a>
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
        </div>
      </div>
    </nav>
    <section style="padding-top: 7rem;">
      <div class="bg-holder" style="background-image:url(assets/img/hero/hero-bg.jpg);">
      </div>
      <!--/.bg-holder-->
    </section>

    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <section class="pt-5" id="destination">
      <div class="container">
      <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4" style="z-index: -1;"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
      <div class="mb-7 text-center">
      <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Estatísticas</h3>
      </div>

      <?php
      include "scripts/abreconexao.php";
      // Query para contar o número total de utilizadores
      $sql = "SELECT COUNT(*) AS total FROM Utilizadores";
      if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        echo '<div class="mb-4 text-start p-3" style="background-color: rgba(233, 247, 239, 0.5); border: 1px solid #d4edda; border-radius: 5px;">
          <h3 class="text-secondary">Número total de Utilizadores &nbsp;<span style="color: green;">' . $row['total'] . '</span></h3>
          </div>';
          $result->free();
      }

      // Query para contar o número total de viagens
      $sql = "SELECT COUNT(*) AS total FROM Viagens";
      if ($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        echo '<div class="mb-4 text-start p-3" style="background-color: rgba(233, 247, 239, 0.5); border: 1px solid #d4edda; border-radius: 5px;">
          <h3 class="text-secondary">Número total de Viagens &nbsp;<span style="color: green;">' . $row['total'] . '</span></h3>
          </div>';
          $result->free();
      }

      // Query para obter o top 3 de viagens com base no local
      $sql = "SELECT L.localidade, COUNT(*) AS total 
          FROM Viagens V
          JOIN Local L ON V.destino = L.id
          GROUP BY L.localidade
          ORDER BY total DESC
          LIMIT 3";
          $conn->set_charset("utf8mb4");
      if ($result = $conn->query($sql)) {
        echo '<div class="mb-4 text-start p-3" style="background-color: rgba(233, 247, 239, 0.5); border: 1px solid #d4edda; border-radius: 5px;">
          <h3 class="text-secondary">Top 3 Locais com Mais Viagens</h3><br>';
        while ($row = $result->fetch_assoc()) {
            echo '<p class="text-secondary"><strong>' . ucfirst($row['localidade']) . '</strong>&nbsp; <span style="color: green;">' . $row['total'] . ' viagens</span></p>';
        }
        echo '</div><br>';
        $result->free();
      }

      $conn->close();
      ?>

      <h5 class="text-secondary">Viagens</h5>
      <br>
      <div class="row">
      <div class="col-md-12">
      <form method="GET" action="admin.php" class="d-flex mb-4">
      <select class="form-control me-2" name="localidade" style="width: 25%;">
      <option value="all">Todas as localidades</option>
      <?php
      include "scripts/abreconexao.php";
      $sql = "SELECT DISTINCT localidade FROM Local ORDER BY localidade";
      $conn->set_charset("utf8mb4");
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
      echo '<option value="' . $row['localidade'] . '">' . $row['localidade'] . '</option>';
      }
      $conn->close();
      ?>
      </select>
      <select class="form-control me-2" name="hora" style="width: 25%;">
      <option value="all">Qualquer hora</option>
      <?php
      for ($i = 0; $i < 24; $i++) {
      $hora_inicio = str_pad($i, 2, '0', STR_PAD_LEFT) . ":00";
      $hora_fim = str_pad(($i + 1) % 24, 2, '0', STR_PAD_LEFT) . ":00";
      echo '<option value="' . $hora_inicio . '">' . $hora_inicio . ' -> ' . $hora_fim . '</option>';
      }
      ?>
      </select>
      <input type="date" class="form-control me-2" name="data" style="width: 25%;" />
      <button class="btn btn-outline-success" type="submit">Pesquisar</button>
      </form>
      </div>
      </div>
      <?php if ((isset($_GET['localidade']) && !empty($_GET['localidade'])) || (isset($_GET['hora']) && !empty($_GET['hora'])) || (isset($_GET['data']) && !empty($_GET['data']))): ?>
      <div class="row">
      <?php
      include 'scripts/viagemAdmin.php';
      include "scripts/abreconexao.php";

      $localidade = $_GET['localidade'];
      $hora = $_GET['hora'];
      $data = $_GET['data'];

      // Query para selecionar dados da tabela Viagens com base na localidade, hora e data
      $sql = "SELECT V.id, V.data_hora,  V.lugares, V.preco,
      L1.nome AS origem_nome, L2.nome AS destino_nome, L2.localidade AS localidade, V.preco AS preco
      FROM Viagens V
      JOIN Local L1 ON V.origem = L1.id
      JOIN Local L2 ON V.destino = L2.id
      WHERE 1=1";

      if (isset($_GET['localidade']) && !empty($_GET['localidade']) && $_GET['localidade'] !== "all") {
        $sql .= " AND L2.localidade LIKE '$localidade' ";
      }

      if (isset($_GET['hora']) && !empty($_GET['hora']) && $_GET['hora'] !== "all") {
        $sql .= " AND DATE_FORMAT(V.data_hora, '%H:00') = '$hora' ";
      }

      if (isset($_GET['data']) && !empty($_GET['data'])) {
        $sql .= " AND DATE(V.data_hora) = '$data' ";
      }

      $conn->set_charset("utf8mb4");
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
      // Exibe os dados de cada linha
      while ($row = $result->fetch_assoc()) {
        echo renderViagemCard($row["data_hora"], $row["origem_nome"], $row["destino_nome"], $row["lugares"], $row["localidade"], $row["preco"], $row["id"]);
      }
      } else {
        echo "<script>alert('Não existem viagens com esses parâmetros.');</script>";
      }

      $conn->close();
      ?>
      </div>
      <?php endif; ?>
      </div>

      <div class="container">
      <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4" style="z-index: -1;"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
      <h5 class="text-secondary">Utilizadores</h5>
      <br>
      <div class="row">
      <div class="col-md-12">
      <form method="GET" action="admin.php" class="d-flex mb-4">
      <select class="form-control me-2" name="email" style="width: 50%;" placeholder="Selecione o email do utilizador">
      <option value="all">Todos os utilizadores</option>
      <?php
      include "scripts/abreconexao.php";
      $sql = "SELECT DISTINCT email FROM Utilizadores ORDER BY email";
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
      echo '<option value="' . $row['email'] . '">' . $row['email'] . '</option>';
      }
      $conn->close();
      ?>
      </select>
      <button class="btn btn-outline-success" type="submit">Pesquisar</button>
      </form>
      </div>
      </div>
      <?php if (isset($_GET['email']) && !empty($_GET['email'])): ?>
      <div class="row">
      <?php
      include "scripts/abreconexao.php";
      include "scripts/utilizador.php";

      $email = $_GET['email'];

      if ($email === "all") {
      // Query para selecionar todos os utilizadores
      $sql = "SELECT email, nome, telemovel, NIF, ft_perfil FROM Utilizadores";
      } else {
      // Query para selecionar dados da tabela Utilizadores com base no email
      $sql = "SELECT email, nome, telemovel, NIF, ft_perfil FROM Utilizadores WHERE email LIKE '$email'";
      }

      $conn->set_charset("utf8mb4");
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
      // Exibe os dados de cada linha
      while ($row = $result->fetch_assoc()) {
      echo renderUtilizadorCard($row['email'], $row['NIF'], $row['nome'], $row['telemovel'], $row['ft_perfil']);
      }
      } else {
      echo "Nenhum utilizador encontrado.";
      }

      $conn->close();
      ?>
      </div>
      <?php endif; ?>
      </div>

      <div class="container">
      <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4" style="z-index: -1;"><img src="assets/img/dest/shape.svg" alt="destination" /></div>
      <h5 class="text-secondary">Visualizar viagens suspeitas</h5>
      <br>
      <div class="row">
      <div class="col-md-12">
      <form method="GET" action="admin.php" class="d-flex mb-4">
      <select class="form-control me-2" name="filtro" style="width: 50%;">
        <option value="todas_viagens_sus">Todas as viagens suspeitas</option>
        <option value="precos_anomalos">Preços anómalos</option>
        <option value="datas_invalidas">Datas inválidas</option>
      </select>
      <button class="btn btn-outline-success" type="submit">Pesquisar</button>
      </form>
      </div>
      </div>
      <?php if (isset($_GET['filtro']) && !empty($_GET['filtro'])): ?>
      <div class="row">
      <?php
      include 'scripts/viagemAdmin.php';
      include "scripts/abreconexao.php";

      $sus = $_GET['filtro'];

      // Query para selecionar dados da tabela Viagens com base na localidade, hora e data
      $sql = "SELECT V.id, V.data_hora AS data_hora,  V.lugares, V.preco,
      L1.nome AS origem_nome, L2.nome AS destino_nome, L2.localidade AS localidade, V.preco AS preco
      FROM Viagens V
      JOIN Local L1 ON V.origem = L1.id
      JOIN Local L2 ON V.destino = L2.id
      WHERE 1=1";

      if (isset($_GET['filtro']) && !empty($_GET['filtro']) && $sus == 'todas_viagens_sus') {
        $sql .= " AND (
          preco < (
        SELECT AVG(preco) - 2 * STD(preco) FROM Viagens
          ) 
          OR preco > (
        SELECT AVG(preco) + 2 * STD(preco) FROM Viagens
          )
          OR data_hora < NOW()
        )";
      }

      if (isset($_GET['filtro']) && !empty($_GET['filtro']) && $sus == 'precos_anomalos') {
        $sql .= " AND (
          preco < (
        SELECT AVG(preco) - 2 * STDDEV(preco) FROM Viagens
          ) 
          OR preco > (
        SELECT AVG(preco) + 2 * STDDEV(preco) FROM Viagens
          )
        )";
      }

      if (isset($_GET['filtro']) && !empty($_GET['filtro']) && $sus == 'datas_invalidas') {
        $sql .= " AND V.data_hora < NOW() ";
      }

      $conn->set_charset("utf8mb4");
      $result = $conn->query($sql);

      $conn->set_charset("utf8mb4");
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Exibe os dados de cada linha
        while ($row = $result->fetch_assoc()) {
          echo renderViagemCard($row["data_hora"], $row["origem_nome"], $row["destino_nome"], $row["lugares"], $row["localidade"], $row["preco"], $row["id"]);
        }
        } else {
          echo "<script>alert('Não existem viagens com esses parâmetros.');</script>";
        }

      $conn->close();
      ?>
      </div>
      <?php endif; ?>
      </div>
    </section>
    <!-- <section> close ============================-->
    <!-- ============================================-->
    <!-- <section> begin ============================-->

    <script>
      function deletarViagem(id) {
        if (confirm('Tem a certeza que deseja eliminar esta viagem?')) {
          // Redireciona para o script de exclusão com o ID da viagem
          window.location.href = `scripts/deletarViagem.php?id=${id}`;
        }
      }
    </script>


    <!-- <section> close ============================-->
    <!-- ============================================-->

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
            <input type="number" step="0.01" class="form-control" id="editarPreco" name="preco" required>
            </div>
          <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" onclick="fecharPopupEditarViagem();">Cancelar</button>
          </div>
        </form>
      </div>
      <div id="popupOverlayEditar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8); z-index:1000;"></div>
      <script>
        function editarViagem(viagemId, origemId, destinoId, dataHora, lugares, preco) {
          document.getElementById('editarViagemId').value = viagemId;

          // Define o valor selecionado dinamicamente
          const origemSelect = document.getElementById('editarOrigem');
          const destinoSelect = document.getElementById('editarDestino');
          origemSelect.innerHTML = `<option value="NULL" selected>${origemId}</option>` + origemSelect.innerHTML;
          destinoSelect.innerHTML = `<option value="NULL" selected>${destinoId}</option>` + destinoSelect.innerHTML;

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
            <p><a href="index.php" class="text-white" style="text-decoration: none;">Home</a></p>
            <p><a href="about.php" class="text-white" style="text-decoration: none;">Sobre nós</a></p>
            <p><a href="contact.php" class="text-white" style="text-decoration: none;">Contactos</a></p>
          </div>

          <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
            <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contactos</h5>
            <p><i class="fas fa-home mr-3"></i> Lisboa, Portugal</p>
            <p><i class="fas fa-envelope mr-3"></i> info@ecoride.com</p>
            <p><i class="fas fa-phone mr-3"></i> +351 234 567 890</p>
          </div>

          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
            <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Siga-nos</h5>
            <a href="#" class="text-white" style="text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white" style="text-decoration: none;"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white" style="text-decoration: none;"><i class="fab fa-instagram"></i></a>
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
    <!-- ============================================-->

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