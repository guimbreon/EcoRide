<?php
require_once "lib/nusoap.php";

function ListaViagens($lat, $lon)
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
			WHERE (L1.latitude = $lat AND L1.longitude = $lon)
			   OR (L2.latitude = $lat AND L2.longitude = $lon)";
	mysqli_set_charset($conn, "utf8mb4");
	$result = mysqli_query($conn, $sql);

    // styles.css
    $html = [];
    $html[] = '<link rel="stylesheet" type="text/css" href="../assets/css/theme.css">';
    $html[] = '<table class="table table-striped table-bordered align-middle">';

    // inicio
    $header = [];
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $header[] = "<thead class='table-light'><tr>";
        foreach (array_keys($row) as $colName) {
            $header[] = "<th scope='col'>" . htmlspecialchars($colName) . "</th>";
        }
        $header[] = "</tr></thead><tbody>";
        $html = array_merge($html, $header);

        //colocar bold onde for a FCUL
        $html[] = "<tr>";
        foreach ($row as $cell) {
            if (stripos($cell, 'FCUL') !== false) {
                $html[] = "<td><b>" . htmlspecialchars($cell) . "</b></td>";
            } else {
                $html[] = "<td>" . htmlspecialchars($cell) . "</td>";
            }
        }
        $html[] = "</tr>";

        // output geral
        while ($row = mysqli_fetch_assoc($result)) {
            $html[] = "<tr>";
            foreach ($row as $cell) {
                if (stripos($cell, 'FCUL') !== false) {
                    $html[] = "<td><b>" . htmlspecialchars($cell) . "</b></td>";
                } else {
                    $html[] = "<td>" . htmlspecialchars($cell) . "</td>";
                }
            }
            $html[] = "</tr>";
        }
        $html[] = "</tbody>";
    } else {
        $html[] = "<tr><td colspan='8' class='text-center'>No results found</td></tr>";
    }
    $html[] = "</table>";
    $html = implode("\n", $html);	
	
	mysqli_close($conn);
	return $html;
}

$server = new soap_server();
$server->configureWSDL('cumpwsdl', 'urn:cumpwsdl');
$server->register("ListaViagens", // nome metodo
array('lat' => 'xsd:string', 'lon' => 'xsd:string'), // input
array('return' => 'xsd:string'), // output
	'uri:cumpwsdl', // namespace
	'urn:cumpwsdl#ListaViagens', // SOAPAction
	'rpc', // estilo
	'encoded' // uso
);

@$server->service(file_get_contents("php://input"));

?>