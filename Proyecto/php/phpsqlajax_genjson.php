<?php
require("phpsqlajax_dbinfo.php");


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

 

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
   //JSON
	 $data[] = array('row'=>$row);
}

header('Content-type: application/json');
echo json_enconde(array('data'=>$data));

?>