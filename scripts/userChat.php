<?php
include 'popUpReserva.php';

function renderUserListItem($name, $picturePath) {
  $name = htmlspecialchars($name);
  $picturePath = htmlspecialchars($picturePath);

  return '
  <li class="list-group-item d-flex align-items-center">
    <img src="' . $picturePath . '" alt="' . $name . '" class="rounded-circle me-2" width="32" height="32">
    <span>' . $name . '</span>
  </li>';
}

?>