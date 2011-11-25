<?php
require("phpsqlajax_dbinfo.php");

// Start XML file, create parent node
$doc = new DOMDocument("1.0");
$node = $doc->createElement("puntos");
$parnode = $doc->appendChild($node);

// Opens a connection to a MySQL server
$connection=mysql_connect ($host, $username, $password);
if (!$connection) {
  die('No conectado : ' . mysql_error() );
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('No se puede usar la BD : ' . mysql_error());
}

// Select all the rows in the markers table
$query = "SELECT * FROM Punto WHERE 1";
$result = mysql_query($query);

if (!$result) {
  die('Query invalido: ' . mysql_error());
}

//$query1 = "SELECT CategoriaPunto.tipo FROM CategoriaPunto,Punto WHERE Punto.idPunto=CategoriaPunto.idPunto";
//$result1 = mysql_query($query1);
//if (!$result1){
 // die('Query 2 invalido: ' . mysql_error());
//} 

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $doc->createElement("punto");
  $newnode = $parnode->appendChild($node);
  
  $newnode->setAttribute("padre", utf8_encode($row['idMapa']));
  $newnode->setAttribute("nombre", utf8_encode($row['nombre']));
  $newnode->setAttribute("direccion",utf8_encode($row['direccion']));
  $newnode->setAttribute("latitud", utf8_encode($row['latitud']));
  $newnode->setAttribute("longitud", utf8_encode($row['longitud']));
  $newnode->setAttribute("telefono", utf8_encode($row['telefono']));
  $newnode->setAttribute("website", utf8_encode($row['website']));
  
  //agregar tipo
  //while ($row1 = @mysql_fetch_assoc($result1))
  //{
//	if ($row['idPunto'] == $row1['idPunto'])
//	{
//	$node1 = $doc->createElement("tipos");
//	$newnode1 = $node->appendChild($node1);
//	$newnode1->setAttribute("tipo", utf8_encode($row1['tipo']));
//	}
 // }
  
}

echo $doc->saveXML();

?>