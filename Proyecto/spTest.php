<?php
header("Content-Type: text/html;charset=utf-8");
/* require the user as the parameter */
if(isset($_GET['sp']) && $_GET['nomMapa']) {

	/* soak in the passed variable or set our own */
    if (isset($_GET['nomMapa']))
    {
        $mapName = $_GET['nomMapa'];
    }
    else {
        $mapName = 'Madrid';
    }
	$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml is the default
	$sp_selected = intval($_GET['sp']); //no default

	/* connect to the db */
	$link = mysql_connect('db388610360.db.1and1.com','dbo388610360','uvg1234') or die('Cannot connect to the DB');
	mysql_select_db('db388610360',$link) or die('Cannot select the DB');

    mysql_query("SET NAMES 'utf8'");

	/* grab the posts from the db */
	if ($sp_selected == 1){
        $query = "SELECT * from mapa where nombre like '%$mapName'";
        $result = mysql_query($query,$link) or die('Errant query:  '.$query);

        /* create one master array of the records */
        $posts = array();
        if(mysql_num_rows($result)) {
            while($post = mysql_fetch_assoc($result)) {
                $posts[] = array('post'=>$post);
            }
        }

        /* output in necessary format */
        if($format == 'json') {
            header('Content-type: application/json');
            echo json_encode(array('rows'=>$posts));
        }
        else {
            header('Content-type: text/xml');
            echo '<rows>';
            foreach($posts as $index => $post) {
                if(is_array($post)) {
                    foreach($post as $key => $value) {
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
	/* disconnect from the db */
	@mysql_close($link);
}
?>