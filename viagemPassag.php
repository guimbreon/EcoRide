<?php
include 'popUpReserva.php';
function renderViagemCard($date, $origem, $destino, $localidade, $preco, $pontoRecolha, $idReserva = null, $hasNotificacao = false) {
  $date = htmlspecialchars($date);
  $origem = htmlspecialchars($origem);
  $destino = htmlspecialchars($destino);
  $localidade = htmlspecialchars($localidade) . '.jpg';
  

  $isHistoricoPage = basename($_SERVER['REQUEST_URI']);
  if (strpos($isHistoricoPage, 'historico.php') !== false) {
    $isHistoricoPage = true;
  } else {
    $isHistoricoPage = false;
  }

  // Adiciona o badge de notificação se hasNotificacao for true
  $notificacaoBadge = '';
  if ($hasNotificacao) {
    $notificacaoBadge = '
      <span style="
        position: absolute;
        top: 10px;
        right: 18px;
        width: 18px;
        height: 18px;
        background: red;
        border-radius: 50%;
        border: 2px solid #fff;
        z-index: 2;
        display: inline-block;
      "></span>
    ';
  }

  return '
  <div class="col-md-4 mb-4">
    <div class="card overflow-hidden shadow" style="position: relative;">
      ' . $notificacaoBadge . '
      <div style="height: 150px; overflow: hidden;">
        <img class="card-img-top" src="assets/img/'. $localidade .'" alt="'. $localidade .'" style="object-fit: cover; width: 100%; height: 100%;" />
      </div>
      <div class="card-body py-4 px-3">
        <div class="d-flex flex-column justify-content-between mb-3">
          <h4 class="text-secondary fw-medium">' . $date . '</h4>
          <h4 class="text-secondary fw-medium">' . $origem . ' -> ' . $destino . '</h4>
          <h4 class="fs-1 fw-small">Ponto de Recolha: ' . $pontoRecolha . '</h4>
          <div class="fs-1 fw-medium">Preço: &nbsp;' . $preco . ' €</div>
          ' . (!$isHistoricoPage ? '<a href="chat.php?idRes=' . urlencode($idReserva) . '" class="btn btn-outline-info btn-lg rounded-pill mt-2 shadow-sm">
            <i class="fas fa-comments me-2"></i> Chat
          </a>' : '') . '
        </div>
        ' . ($isHistoricoPage ? '<a href="avaliacao.php?idRes=' . urlencode($idReserva) . '" class="btn btn-primary mt-3">Deixe a sua avaliação</a>' : '') . '
      </div>
    </div>
  </div>';
}
?>