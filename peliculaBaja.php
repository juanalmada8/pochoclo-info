<?php require 'includes/session.php';
validarLogin(); 
include 'bd.php';

if ($_SESSION['admin']) {
	
	if (isset($_GET['id'])) {

		$id = $_GET['id'];
		
		$del = "DELETE FROM `comentarios`
					WHERE `peliculas_id`=$id;";
	
		accion($del);
	
		$query="DELETE FROM `peliculas` 
					WHERE `id`=$id;";
		accion($query);
		header('Location:abm.php?e=5');
	}
} else { header("location:index.php");}


