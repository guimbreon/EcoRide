<?php
require_once "lib/nusoap.php";

function nomeutilizador($lat, $lon)
{
	$dbhost="appserver-01.alunos.di.fc.ul.pt";
	$dbuser="asw12";	$dbpass="grupinho12";	$dbname="asw12";
	//Cria a ligação à BD
	$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	//Verifica a ligação à BD
	if(mysqli_connect_error()){die("Database connection failed:".mysqli_connect_error());}

	$sql = "SELECT V.id,
			V.condutor_id,
			V.carro_id,
			L1.nome as origem,
			L2.nome as destino,
			V.lugares,
			V.data_hora,
			V.preco

	 FROM Viagens V
			JOIN Local L1 ON V.origem = L1.id
			JOIN Local L2 ON V.destino = L2.id
			WHERE (ABS(L1.latitude - $lat) < 0.01 AND ABS(L1.longitude - $lon) < 0.01)
			   OR (ABS(L2.latitude - $lat) < 0.01 AND ABS(L2.longitude - $lon) < 0.01)";
	mysqli_set_charset($conn, "utf8mb4");
	$result = mysqli_query($conn, $sql);

	// Initialize table header
	$header = [];
	if ($result && $row = mysqli_fetch_assoc($result)) {
		$header[] = "<tr>";
		foreach (array_keys($row) as $colName) {
			$header[] = "<th>" . htmlspecialchars($colName) . "</th>";
		}
		$header[] = "</tr>";
		$html = $header;

		// Output first row
		$html[] = "<tr>";
		foreach ($row as $cell) {
			$html[] = "<td>" . htmlspecialchars($cell) . "</td>";
		}
		$html[] = "</tr>";

		// Output remaining rows
		while ($row = mysqli_fetch_assoc($result)) {
			$html[] = "<tr>";
			foreach ($row as $cell) {
				$html[] = "<td>" . htmlspecialchars($cell) . "</td>";
			}
			$html[] = "</tr>";
		}
	} else {
		$html = ["<tr><td>No results found</td></tr>"];
	}
	$html="<table>".implode("\n",$html)."</table>";	
	// echo $html;
	mysqli_close($conn);
	return $html;
}

$server = new soap_server();
$server->configureWSDL('cumpwsdl', 'urn:cumpwsdl');
$server->register("nomeutilizador", // nome metodo
array('nome' => 'xsd:string'), // input
array('return' => 'xsd:string'), // output
	'uri:cumpwsdl', // namespace
	'urn:cumpwsdl#nomeutilizador', // SOAPAction
	'rpc', // estilo
	'encoded' // uso
);

@$server->service(file_get_contents("php://input"));

?>