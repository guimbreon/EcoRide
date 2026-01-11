<?php
include 'popUpReserva.php';
function renderViagemCard($date, $origem, $destino, $lugares, $localidade, $preco, $id) {
  $date = htmlspecialchars($date);
  $origem = htmlspecialchars($origem);
  $destino = htmlspecialchars($destino);
  $lugares = $lugares;
  if(!in_array($localidade, ['Lisboa', 'Arruda', 'Estoril'])){
    $localidade = 'Lisboa';
  }
  $localidade = htmlspecialchars($localidade) . '.jpg';
  return '
  
  <div class="col-md-4 mb-4">
    <div class="card overflow-hidden shadow position-relative" style="position: relative;">
    <div style="height: 150px; overflow: hidden;">
      <img class="card-img-top" src="assets/img/'. $localidade .'" alt="'. $localidade .'" style="object-fit: cover; width: 100%; height: 100%;" />
    </div>
    <div class="card-body py-4 px-3">
      <div class="d-flex flex-column justify-content-between mb-3">
      <h4 class="text-secondary fw-medium">' . $date . '</h4>
      <h4 class="text-secondary fw-medium">' . $origem . ' -> ' . $destino . '</h4>
      <div class="fs-1 fw-medium">Lugares disponíveis: &nbsp;' . $lugares . '</div>
      <div class="fs-1 fw-medium">Preço padrão: &nbsp;' . $preco . ' €</div>
      </div>
      <div class="d-flex align-items-center">
      <img src="assets/img/dest/navigation.svg" style="margin-right: 14px" width="20" alt="navigation" />
      <a href="editarViagem.php?id=' . $id . '">Editar Viagem</a>
      </div>
    </div>
    <button class="btn btn-danger position-absolute top-0 end-0 m-2" style="z-index: 1; display: none;" onclick="deletarViagem('. $id .')">Eliminar</button>
    </div>
  </div>
  <style>
    .card:hover .delete-button {
      display: block !important;
    }
  </style>
  <script>
    document.querySelectorAll(".card").forEach(card => {
      card.addEventListener("mouseenter", () => {
        const deleteButton = card.querySelector(".btn-danger");
        if (deleteButton) deleteButton.style.display = "block";
      });
      card.addEventListener("mouseleave", () => {
        const deleteButton = card.querySelector(".btn-danger");
        if (deleteButton) deleteButton.style.display = "none";
      });
    });
  </script>';
}

?>