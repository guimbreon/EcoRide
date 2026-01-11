<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: register.php");
  exit();
}
$user = $_SESSION['user'];

$errors = [];
if (isset($_GET['success']) && $_GET['success'] == 1) {
  echo "<div class='alert alert-success'>Viagem criada com sucesso!</div>";
}

$origem_lat = $_GET['origem_lat'] ?? '';
$origem_lon = $_GET['origem_lon'] ?? '';
$destino_lat = $_GET['destino_lat'] ?? '';
$destino_lon = $_GET['destino_lon'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "GET" ) {
  // salvar coordenadas
  $origem_lat = floatval($_GET['origem_lat']);
  $origem_lon = floatval($_GET['origem_lon']);
  $destino_lat = floatval($_GET['destino_lat']);
  $destino_lon = floatval($_GET['destino_lon']);
  $carro_id = $_GET['carro_id'] ?? '';
  $origem_outro = $_GET['origem_outro'] ?? '';
  $destino_outro = $_GET['destino_outro'] ?? '';

  include "scripts/abreconexao.php";
  // Set connection charset to utf8mb4 for full Unicode support


  
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
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
    .local-text {
      color: #555;
    }
    .local-vazio {
      color: #aaa;
      font-style: italic;
    }
  </style>
</head>
<body>
<main class="main" id="top">
  <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block">
    <div class="container">
      <a class="navbar-brand" href="index.php"><h1>ECORIDE</h1></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto pt-2 pt-lg-0">
          <?php include 'scripts/checkLogin.php'; ?>
        </ul>
      </div>
    </div>
  </nav>

  <section style="padding-top: 7rem;">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center py-6">
          <h3>Selecione Origem e Destino clicando no mapa</h3>
          <div class="mapa-wrapper">
            <div id="mapa"></div>
            <div id="infoPainel">
              <h5>Pesquisar Localidade:</h5>
              <input type="text" id="pesquisaLocalidade" placeholder="Lisboa, Porto..." class="form-control">
              <p></p>
              <button onclick="procurarLocalidade()" class="btn btn-outline-secondary btn-sm btn-pesquisa">Procurar</button>


              <hr>
              <h5>Origem:</h5>
              <p id="painelOrigem" class="local-vazio">Por marcar</p>
              <h5>Destino:</h5>
              <p id="painelDestino" class="local-vazio">Por marcar</p>
              <form id="formMapa" action="criarViagem.php" method="GET">
                <input type="hidden" name="origem_lat" id="origem_lat" value="">
                <input type="hidden" name="origem_lon" id="origem_lon" value="">
                <input type="hidden" name="destino_lat" id="destino_lat" value="">
                <input type="hidden" name="destino_lon" id="destino_lon" value="">

                <!-- dados para manter o estado -->
                <input type="hidden" name="carro_id" value="<?php echo htmlspecialchars($_GET['carro_id'] ?? ''); ?>">
                <input type="hidden" name="origem_outro" value="<?php echo htmlspecialchars($_GET['origem_outro'] ?? ''); ?>">
                <input type="hidden" name="destino_outro" value="<?php echo htmlspecialchars($_GET['destino_outro'] ?? ''); ?>">

                <div style="text-align: center; margin: 20px">
                  <button type="submit" class="btn btn-success">Confirmar e Voltar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


<script>
const map = L.map('mapa').setView([38.74, -9.14], 11);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '&copy; OpenStreetMap'
}).addTo(map);

let marcadorOrigem = null;
let marcadorDestino = null;

map.on('click', async function(e) {
  const lat = e.latlng.lat.toFixed(6);
  const lon = e.latlng.lng.toFixed(6);

  // Chama a API Nominatim para geocodificação reversa
  let endereco = 'Buscando endereço...';
  try {
    const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
    const data = await resp.json();
    if (data.address) {
      const nome = data.name || (data.display_name ? data.display_name.split(',')[0] : 'nome');
      const rua = data.address?.road || nome || 'rua';
      const nmr = data.address?.house_number || Math.floor(Math.random() * 999) + 1;
      const localidade = data.address.town || data.address.village || data.address.city || data.address.hamlet || data.address.suburb || data.address.county || rua || '';

      endereco = `${nome ? nome + ', ' : ''}${rua ? rua : ''}${nmr ? ', ' + nmr : ''}${localidade ? ', ' + localidade : ''}`;
      if (!endereco.trim()) endereco = 'Endereço não encontrado';
    } else {
      endereco = 'Endereço não encontrado';
    }
  } catch (err) {
    endereco = 'Erro ao buscar endereço';
  }

  const popupContent = `
    <strong>Coordenadas Selecionadas</strong><br>
    <small>${lat}, ${lon}</small><br>
    <strong>Endereço:</strong><br>
    <small>${endereco}</small><br><br>
    <button onclick="marcarOrigem('${lat}', '${lon}')">Marcar como Origem</button><br>
    <button onclick="marcarDestino('${lat}', '${lon}')">Marcar como Destino</button>
  `;

  L.popup().setLatLng(e.latlng).setContent(popupContent).openOn(map);
});

function marcarOrigem(lat, lon) {
  fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
    .then(resp => resp.json())
    .then(data => {
      const nome = data.name || (data.display_name ? data.display_name.split(',')[0] : 'nome');
      const rua = data.address?.road || nome || 'rua';
      const nmr = data.address?.house_number || Math.floor(Math.random() * 999) + 1;
      const localidade = data.address.town || data.address.village || data.address.city || data.address.hamlet || data.address.suburb || data.address.county || rua || '';

      document.getElementById("painelOrigem").innerHTML = `
        <strong>Nome:</strong> ${nome}<br>
        <strong>Rua:</strong> ${rua}<br>
        <strong>Número:</strong> ${nmr}<br>
        <strong>Localidade:</strong> ${localidade}<br>
        <strong>Latitude:</strong> ${lat}<br>
        <strong>Longitude:</strong> ${lon}
      `;
      document.getElementById("painelOrigem").className = 'local-text';

      document.querySelector('input[name="origem_outro"]').value = `${nome},${rua},${nmr},${localidade},${lat},${lon}`;
      document.getElementById('origem_lat').value = lat;
      document.getElementById('origem_lon').value = lon;

      if (marcadorOrigem) map.removeLayer(marcadorOrigem);
      marcadorOrigem = L.marker([lat, lon]).addTo(map).bindPopup("Origem").openPopup();
    });
}

function marcarDestino(lat, lon) {
  fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
    .then(resp => resp.json())
    .then(data => {
      const nome = data.name || (data.display_name ? data.display_name.split(',')[0] : 'nome');
      const rua = data.address?.road || nome || 'rua';
      const nmr = data.address?.house_number || Math.floor(Math.random() * 999) + 1;
      const localidade = data.address.town || data.address.village || data.address.city || data.address.hamlet || data.address.suburb || data.address.county || rua || '';


      document.getElementById("painelDestino").innerHTML = `
        <strong>Nome:</strong> ${nome}<br>
        <strong>Rua:</strong> ${rua}<br>
        <strong>Número:</strong> ${nmr}<br>
        <strong>Localidade:</strong> ${localidade}<br>
        <strong>Latitude:</strong> ${lat}<br>
        <strong>Longitude:</strong> ${lon}
      `;
      document.getElementById("painelDestino").className = 'local-text';

      document.querySelector('input[name="destino_outro"]').value = `${nome},${rua},${nmr},${localidade},${lat},${lon}`;
      document.getElementById('destino_lat').value = lat;
      document.getElementById('destino_lon').value = lon;

      if (marcadorDestino) map.removeLayer(marcadorDestino);
      marcadorDestino = L.marker([lat, lon]).addTo(map).bindPopup("Destino").openPopup();
    });
}

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

</script>

</body>
</html>
