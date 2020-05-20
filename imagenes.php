<?php 
require 'includes/session.php';
include 'bd.php';

if (isset($_GET['id'])) {
	
$peliculaId = mysql_real_escape_string($_GET['id']);

$query="SELECT 
		   contenidoimagen AS img,
		   tipoimagen
		FROM
		    peliculas
		WHERE 
			id = $peliculaId";

$variable = fetch($query,true);

$img = $variable['img'];
$tipo = $variable['tipoimagen'];

header('content-type: image/'.$tipo);
echo $img;

} else return false;