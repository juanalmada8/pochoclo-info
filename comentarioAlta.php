<?php 
require 'includes/session.php'; 
include 'bd.php';

$idP = $_POST['idP'];
if (isset($_POST['comentario']) && isset($_POST['cal'])) {
			
	$com = ($_POST['comentario']);
	$fecha = date("Y-m-d");
	$idU = $_SESSION['idUsuario'];
	$cal = $_POST['cal']; 

	?> 
	
	<?php  
	$query = "INSERT INTO `comentarios` 
					(`comentario`, `fecha`, `peliculas_id`, `usuarios_id`, `calificacion`) 
				VALUES ('$com', '$fecha', '$idP', '$idU', '$cal');";
	accion($query);
	header("location:pelicula.php?id=$idP");
} else if (!isset($_POST['cal'])) {
	header("location:pelicula.php?id=$idP&m=1");
}
?>