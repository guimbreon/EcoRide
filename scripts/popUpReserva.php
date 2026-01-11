<?php
function popUpReserva() {
  return '<div id="popupReserva" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background-color:white; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,0.2); z-index:1000;">
      <h2>Reserva</h2>
      <p>Preencha os detalhes da sua reserva abaixo:</p>
      <form action="processaReserva.php" method="post">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="data">Data:</label><br>
        <input type="date" id="data" name="data" required><br><br>
        <label for="hora">Hora:</label><br>
        <input type="time" id="hora" name="hora" required><br><br>
        <button type="submit">Confirmar</button>
        <button type="button" onclick="document.getElementById(\'popupReserva\').style.display=\'none\';">Cancelar</button>
      </form>
      </div>
      <script>
      function abrirPopupReserva() {
        document.getElementById("popupReserva").style.display = "block";
      }
      </script>';
}
?>