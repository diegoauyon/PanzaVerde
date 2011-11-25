<?php
require("phpsqlajax_dbinfo.php");
header("Content-Type: text/html;charset=utf-8");
/* require the user as the parameter */
/*
	Dictionary of Commands
	0000 - ADDPOINT
	0001 - GETSPECIFICPOINT
*/

class InvalidCommandException extends Exception{}
class InvalidParametersException extends Exception{}
class EmptyResultSetException extends Exception{}
class BadQueryException extends Exception{}
class NonQueryException extends Exception{}

$validCommands = array("r","0000", "0001");

try{
if(isset($_GET['sp']) ) { //check if post exists
    if (in_array(strtolower($_GET['sp']), $validCommands)){ // validate command
            
            $format = strtolower($_GET['format']) == 'xml' ? 'xml' : 'json'; //json is the default
            $sp_selected = strtolower($_GET['sp']); //no default
            
            /* connect to the db */
            $link = mysql_connect ($host, $username, $password);
			if (!$link){
				die('Cannot connect to the DB'. mysql_error());
			}
			
			// Set the active MySQL database
			$db_selected = mysql_select_db($database, $link);
			if (!$db_selected){
			die ('No se puede usar la BD : ' . mysql_error());
			}

            mysql_query("SET NAMES 'utf8'");

            /* grab the posts from the db */
            if ($sp_selected == "0000"){
                if (isset($_GET['idMapa']) && isset($_GET['nombre']) && isset($_GET['direccion']) && isset($_GET['latitud']) && isset($_GET['longitud'])){
                    $idMapa = $_GET['idMapa'];
                    $nombre = $_GET['nombre'];
                    $telefono = isset($_GET['telefono']) ? $_GET['telefono'] : NULL;
                    $direccion = $_GET['direccion'];
                    $website = isset($_GET['website']) ? $_GET['website']:NULL;
                    $latitud = $_GET['latitud'];
                    $longitud = $_GET['longitud'];
                
                    $query = sprintf("SELECT count(*) as count from Mapa where idMapa = %s", mysql_real_escape_string($idMapa));
                    $result = mysql_query($query, $link) or die('Errant query:'.$query);
                    
                    
                    $row=mysql_fetch_assoc($result);
                        if ($row['count'] == 0){
                            throw new BadQueryException("Map does not exists");
							}
                        else {            
$query = sprintf("INSERT INTO Punto(idMapa, nombre, direccion, telefono, website, latitud, longitud, fechaCreacion, fechaModificacion) values(%s, '%s', '%s', '%s', '%s', '%s', '%s',NOW(), NOW())",mysql_real_escape_string($idMapa), mysql_real_escape_string($nombre),mysql_real_escape_string($direccion),mysql_real_escape_string($telefono), mysql_real_escape_string(website), mysql_real_escape_string($latitud), mysql_real_escape_string($longitud));
                    
                    $result = mysql_query($query,$link) or die('Errant query:  '.$query);
                    }
                }
                else {
                    throw new InvalidParametersException();
                }
                throw new NonQueryException();
            }
            else if($sp_selected == "0001"){
                if (isset($_GET['idPunto']) && isset($_GET['idMapa'])){
                    $idPunto = $_GET['idPunto'];
                    $idMapa = $_GET['idMapa'];
                
                    $query = sprintf("SELECT * from Punto where idPunto ='%s' and idMapa='%s'", mysql_real_escape_string($idPunto), mysql_real_escape_string($idMapa));
                    $result = mysql_query($query, $link) or die('Errant query:  '.$query);
                }
                else {
                    throw new InvalidParametersException();
                }
            }
			else if ($sp_selected == "r")
			{
				// Select all the rows in the markers table
			$query = "SELECT * FROM Punto WHERE 1";
			$result = mysql_query($query,$link);

			if (!$result) 
			{
				die('Query invalido: ' . mysql_error());
			}
			
			}
            
                /* create one master array of the records */
                
                if(mysql_num_rows($result) != NULL) {
                    $rows = array();
                    while($row = mysql_fetch_assoc($result)) {
                        $rows[] = array('punto'=>$row);
                    }
                    

                    /* output in necessary format */
                    if($format == 'json') {
						header('Access-Control-Allow-Origin: *');
                        header('Content-type: application/json');
                        echo json_encode(array('puntos'=>$rows));
                    }
                    else {
                        header('Content-type: text/xml');
                        echo '<rows>';
                        foreach($rows as $index => $row) {
                            if(is_array($row)) {
                                foreach($row as $key => $value) {
                                    echo '<',$key,'>';
                                    if(is_array($value)) {
                                        foreach($value as $tag => $val) {
                                            echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
                                        }
                                    }
                                    echo '</',$key,'>';
                                }
                            }
                        }
                        echo '</rows>';
                }

            }
            else {
               throw new EmptyResultSetException();
            }
            /* disconnect from the db */
            mysql_free_result($result);
            @mysql_close($link);
     }
     else {
        throw new InvalidCommandException();
     }
}
else{
    throw new InvalidParametersException();
}
}catch (InvalidParametersException $ipe){
        echo "{'response':{'code':'1','description':'Invalid Parameters'}}";
}
catch(InvalidCommandException $ice){
        echo "{'response':{'code':'2','description':'Invalid Command'}}";
}
catch(EmptyResultSetException $erse){
        echo "{'response':{'code':'3', 'description':'Empty Result Set'}}";
}
catch(BadQueryException $bqe){
    echo sprintf("{'response':{'code':'4', 'description': '%s'}}", mysql_real_escape_string($bqe->getMessage()));
}
catch(NonQueryException $nqe){
    echo "{'response':{'code':'5','description':'Non Query Succesful'}}";
}

?>