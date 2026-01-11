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
    <style>
    .mapa-wrapper {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      gap: 20px;
    }
    #mapa {
      flex: 1;
      height: 600px;
    }
    #infoPainel {
      flex-basis: 300px;
      background: #fff;
      padding: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      height: fit-content;
    }
    #infoPainel h5 {
      margin-bottom: 10px;
    }
    .local-vazio {
      color: #aaa;
      font-style: italic;
    }
    .btn-pesquisa {
      margin-top: 10px;
    }
  </style>
    <!-- ===============================================-->
  <!--    Leaflet-->
  <!-- ===============================================-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>

<body>

  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top" >
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block shadow" style="background-color: #f8f9fa; z-index: 1000;" data-navbar-on-scroll="data-navbar-on-scroll">
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
        </div>
      </div>
    </nav>
    <section style="padding-top: 7rem;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center py-6">
                    <h3>Reservar Viagem com o Mapa</h3>
                    <div class="mapa-wrapper">

                        <!-- Mapa -->
                        <div id="mapa"></div>

                        <!-- Painel de Informação -->
                        <div id="infoPainel">

                            <h5>Pesquisar Localidade:</h5>
                            <input type="text" id="pesquisaLocalidade" placeholder="Lisboa, Porto..." class="form-control">
                            <button onclick="procurarLocalidade()" class="btn btn-outline-secondary btn-sm btn-pesquisa">Procurar</button>


                            <hr>
                            <div id="painelInfo">
                                <p>Clique numa viagem para ver os seus detalhes.</p>
                            </div>

                            <form id="formReserva" action="addReserva.php" method="GET" style="display: none;">
                                <input type="hidden" name="idVi" id="idVi">
                                <button type="submit" class="btn btn-success mt-3 w-100">Confirmar Reserva</button>
                                <button type="button" class="btn btn-cancel mt-3 w-100" onclick="cancelarSelecao()">Cancelar</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
  </main>

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
    
<!-- Scripts do Mapa -->
<script>
const origemIcon = L.icon({
  iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
  iconSize: [30, 30],
  iconAnchor: [15, 30],
  popupAnchor: [0, -30]
});

const destinoIcon = L.icon({
  iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149059.png',
  iconSize: [30, 30],
  iconAnchor: [15, 30],
  popupAnchor: [0, -30]
});

const map = L.map('mapa').setView([38.74, -9.14], 11);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);


// Função de pesquisa por localidade
async function procurarLocalidade() {
  const local = document.getElementById('pesquisaLocalidade').value;
  if (!local) return;

  try {
    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(local)}`);
    const data = await response.json();

    if (data.length > 0) {
      const lat = parseFloat(data[0].lat);
      const lon = parseFloat(data[0].lon);
      map.setView([lat, lon], 14);
    } else {
      alert("Localidade não encontrada.");
    }
  } catch (error) {
    console.error(error);
    alert("Erro ao procurar a localidade.");
  }
}

let todasAsViagens = [];

function addViagens() {
  <?php
  include "scripts/abreconexao.php";
  $sql = "SELECT V.lugares, V.preco, V.data_hora,
          L1.nome AS origem_nome, L1.rua AS origem_rua, L1.nmr AS origem_nmr, L1.localidade AS origem_localidade, L1.latitude AS origem_lat, L1.longitude AS origem_lon,
          L2.nome AS destino_nome, L2.rua AS destino_rua, L2.nmr AS destino_nmr, L2.localidade AS destino_localidade, L2.latitude AS destino_lat, L2.longitude AS destino_lon,
          V.id AS id
          FROM Viagens V
          JOIN Local L1 ON V.origem = L1.id
          JOIN Local L2 ON V.destino = L2.id
          WHERE V.lugares <> 0";
          $conn->set_charset("utf8mb4");
  $res = $conn->query($sql);
  $i = 0;
  while ($row = $res->fetch_assoc()) {
    $oLat = $row['origem_lat'];
    $oLon = $row['origem_lon'];
    $dLat = $row['destino_lat'];
    $dLon = $row['destino_lon'];
    $id = $row['id'];

    $info = "<strong>Origem:</strong> {$row['origem_nome']}, {$row['origem_rua']}, {$row['origem_nmr']}, {$row['origem_localidade']}<br>" .
            "<strong>Destino:</strong> {$row['destino_nome']}, {$row['destino_rua']}, {$row['destino_nmr']}, {$row['destino_localidade']}<br>" .
            "<strong>Lugares:</strong> {$row['lugares']}<br>" .
            "<strong>Preço:</strong> €{$row['preco']}<br>" .
            "<strong>Data/Hora:</strong> " . date("d/m/Y H:i", strtotime($row['data_hora']));

    echo "var origem_$i = L.marker([$oLat, $oLon], {icon: origemIcon}).addTo(map);\n";
    echo "var destino_$i = L.marker([$dLat, $dLon], {icon: destinoIcon}).addTo(map);\n";
    echo "var linha_$i = L.polyline([[$oLat, $oLon], [$dLat, $dLon]], {color: '#666', weight: 2}).addTo(map);\n";

    echo "todasAsViagens.push({ origem: origem_$i, destino: destino_$i, linha: linha_$i });\n";

    echo "linha_$i.on('click', function() {\n";
    echo "  map.fitBounds(linha_$i.getBounds(), { padding: [80, 80] });\n";
    echo "  document.getElementById('painelInfo').innerHTML = `\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/684/684908.png' alt='Origem' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Origem:</strong> {$row['origem_nome']}, {$row['origem_rua']}, {$row['origem_nmr']}, {$row['origem_localidade']}</p>\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/149/149059.png' alt='Destino' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Destino:</strong> {$row['destino_nome']}, {$row['destino_rua']}, {$row['destino_nmr']}, {$row['destino_localidade']}</p>\n";
    echo "    <p><strong>Lugares:</strong> {$row['lugares']}</p>\n";
    echo "    <p><strong>Preço:</strong> €{$row['preco']}</p>\n";
    echo "    <p><strong>Data/Hora:</strong> " . date("d/m/Y H:i", strtotime($row['data_hora'])) . "</p>`;\n";
    echo "  origem_$i.bindPopup('Origem').openPopup();\n";
    echo "  destino_$i.bindPopup('Destino').openPopup();\n";
    echo "  selecionarViagem(origem_$i, destino_$i, linha_$i,'$id');\n";
    echo "});\n";

    // Adiciona evento de clique no marcador de origem
    echo "origem_$i.on('click', function() {\n";
    echo "  map.fitBounds(linha_$i.getBounds(), { padding: [80, 80] });\n";
    echo "  document.getElementById('painelInfo').innerHTML = `\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/684/684908.png' alt='Origem' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Origem:</strong> {$row['origem_nome']}, {$row['origem_rua']}, {$row['origem_nmr']}, {$row['origem_localidade']}</p>\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/149/149059.png' alt='Destino' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Destino:</strong> {$row['destino_nome']}, {$row['destino_rua']}, {$row['destino_nmr']}, {$row['destino_localidade']}</p>\n";
    echo "    <p><strong>Lugares:</strong> {$row['lugares']}</p>\n";
    echo "    <p><strong>Preço:</strong> €{$row['preco']}</p>\n";
    echo "    <p><strong>Data/Hora:</strong> " . date("d/m/Y H:i", strtotime($row['data_hora'])) . "</p>`;\n";
    echo "  selecionarViagem(origem_$i, destino_$i, linha_$i,'$id');\n";
    echo "});\n";

    // Adiciona evento de clique no marcador de destino
    echo "destino_$i.on('click', function() {\n";
    echo "  map.fitBounds(linha_$i.getBounds(), { padding: [80, 80] });\n";
    echo "  document.getElementById('painelInfo').innerHTML = `\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/684/684908.png' alt='Origem' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Origem:</strong> {$row['origem_nome']}, {$row['origem_rua']}, {$row['origem_nmr']}, {$row['origem_localidade']}</p>\n";
    echo "    <p><img src='https://cdn-icons-png.flaticon.com/512/149/149059.png' alt='Destino' style='width: 20px; height: 20px; margin-right: 5px;'>\n";
    echo "    <strong>Destino:</strong> {$row['destino_nome']}, {$row['destino_rua']}, {$row['destino_nmr']}, {$row['destino_localidade']}</p>\n";
    echo "    <p><strong>Lugares:</strong> {$row['lugares']}</p>\n";
    echo "    <p><strong>Preço:</strong> €{$row['preco']}</p>\n";
    echo "    <p><strong>Data/Hora:</strong> " . date("d/m/Y H:i", strtotime($row['data_hora'])) . "</p>`;\n";
    echo "  selecionarViagem(origem_$i, destino_$i, linha_$i,'$id');\n";
    echo "});\n";

    $i++;
  }
  $conn->close();
  ?>
}

function selecionarViagem(origem, destino, linha, idVi) {
  // Ocultar todas as viagens
  todasAsViagens.forEach(viagem => {
    map.removeLayer(viagem.origem);
    map.removeLayer(viagem.destino);
    map.removeLayer(viagem.linha);
  });

  // Mostrar apenas a viagem selecionada
  origem.addTo(map);
  destino.addTo(map);
  linha.addTo(map);


  document.getElementById('idVi').value = idVi; // Atualiza o ID da viagem

  // Mostrar o formulário
  document.getElementById('formReserva').style.display = 'block';
}

function cancelarSelecao() {
  // Remover a viagem selecionada
  todasAsViagens.forEach(viagem => {
    viagem.origem.addTo(map);
    viagem.destino.addTo(map);
    viagem.linha.addTo(map);
  });

  // Limpar os campos do formulário
  document.getElementById('idVi').value = '';

  // Ocultar o formulário
  document.getElementById('formReserva').style.display = 'none';

  // Limpar o painel de informações
  document.getElementById('painelInfo').innerHTML = '<p>Clique numa viagem para ver os seus detalhes.</p>';
}

addViagens();
</script>

</body>
</html>
