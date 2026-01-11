<?php
function renderUtilizadorCard($email, $NIF, $nome, $telemovel, $ft_perfil) {
  $email = htmlspecialchars($email);
  $NIF = $NIF;
  $nome = htmlspecialchars($nome);
  $telemovel = $telemovel;
  $ft_perfil = htmlspecialchars($ft_perfil);

  return '
  <div class="col-md-4 mb-4">
    <div class="card overflow-hidden shadow">
    <img class="card-img-top" src="'. $ft_perfil .'" alt="'. $ft_perfil .'" />
    <div class="card-body py-4 px-3">
      <div class="d-flex flex-column justify-content-between mb-3">
      <h4 class="text-secondary fw-medium">' . $email . '</h4>
      <h4 class="text-secondary fw-medium">' . $nome . '</h4>
      <div class="fs-1 fw-medium">Telemóvel: &nbsp;' . $telemovel . '</div>
      <div class="fs-1 fw-medium">NIF: &nbsp;' . $NIF . '</div>
      </div>
    </div>
    </div>
  </div>';
}

?>