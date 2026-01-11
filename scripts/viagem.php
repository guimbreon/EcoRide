<?php
include 'popUpReserva.php';
function renderViagemCard($date, $origem, $destino, $lugares, $localidade, $id, $preco) {
  $date = htmlspecialchars($date);
  $origem = htmlspecialchars($origem);
  $destino = htmlspecialchars($destino);
  $lugares = $lugares;
    if (!in_array($localidade, ['Lisboa', 'Arruda', 'Estoril', 'Santarém'])) {
    $localidade = 'Lisboa';
  }
  $localidade = htmlspecialchars($localidade) . '.jpg'; 
  $preco = $preco;

  return '
  
  <div class="col-md-4 mb-4">
    <div class="card overflow-hidden shadow">
    <div style="height: 150px; overflow: hidden;">
      <img class="card-img-top" src="assets/img/'. $localidade .'" alt="'. $localidade .'" style="object-fit: cover; width: 100%; height: 100%;" />
    </div>
    <div class="card-body py-4 px-3">
      <div class="d-flex flex-column justify-content-between mb-3">
      <h4 class="text-secondary fw-medium">' . $date . '</h4>
      <h4 class="text-secondary fw-medium">' . $origem . ' -> ' . $destino . '</h4>
      <div class="fs-1 fw-medium">Lugares disponíveis: &nbsp;' . $lugares . '</div>
      <div class="fs-1 fw-medium">Preço: &nbsp;' . $preco . '€</div>
      </div>
      <div class="d-flex align-items-center">
      <img src="assets/img/dest/navigation.svg" style="margin-right: 14px" width="20" alt="navigation" />
      <a class="link-900 text-decoration-none stretched-link fs-0 fw-medium" href="addReserva.php?idVi=' . $id . '">Reserve já</a>
      </div>
    </div>
    </div>
  </div>';
}

?>