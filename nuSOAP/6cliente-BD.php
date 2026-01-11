 <?php
require_once "lib/nusoap.php";

$client = new nusoap_client(
	'http://appserver-01.alunos.di.fc.ul.pt/~asw12/projeto/nuSOAP/5serve-nome.php'
);
$error = $client->getError();
$result = $client->call('nomeutilizador', array('nome' => 'Admin15'));	//handle errors

echo "<h2>Pedido</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Resposta</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";
echo "<h2>$result</h2>";
if ($client->fault)
{   //check faults
}
else {    $error = $client->getError();		 //handle errors
   		 echo "<h2>$result</h2>";
}
?>