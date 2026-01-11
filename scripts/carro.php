<?php
function renderCarroCard($marca, $modelo, $cor, $matricula, $combustivel, $ft_carro) {
  $marca = htmlspecialchars($marca);
  $modelo = htmlspecialchars($modelo);
  $cor = htmlspecialchars($cor);
  $matricula = htmlspecialchars($matricula);
  $ft_carro = htmlspecialchars($ft_carro);

  return '
  <div class="col-md-4 mb-4">
    <div class="card overflow-hidden shadow">
    <img class="card-img-top" src="'. $ft_carro .'" alt="'. $marca . ' ' . $modelo .'" />
    <div class="card-body py-4 px-3">
      <div class="d-flex flex-column justify-content-between mb-3">
      <h4 class="text-secondary fw-medium">' . $marca . '</h4>
      <h4 class="text-secondary fw-medium">' . $modelo .'</h4>
      <h4 class="text-secondary fw-medium">' . $cor .'</h4>
      <h4 class="text-secondary fw-medium">' . $matricula .'</h4>
      <h4 class="text-secondary fw-medium">' . $combustivel .'</h4>
      </div>
    </div>
    </div>
  </div>';
}
?>
