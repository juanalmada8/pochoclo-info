
<?php 
require 'includes/session.php';
validarLogin(); 
include 'bd.php'; ?>
<?php if ($_SESSION['admin']) {

	if (count($_FILES)) {
		
		$id=$_POST['id'];
		
		$imgTmp = $_FILES['img']['tmp_name'];
		$img = $_FILES['img']['name'];
		$tipo = $_FILES['img']['type']; //image/png
		$tipoEx = explode('/', $tipo);
		$tipoArchivo = $tipoEx[0];
		$ext = $tipoEx[1];
		$imgFull="uploads/".time()."$img";
		move_uploaded_file($imgTmp, $imgFull);

		if ($tipoArchivo!='image') {
			header("location:abm.php?op=foto&id=$id&e=1");
		} else {

			$query= "UPDATE peliculas 
					SET 
						`contenidoimagen`='$imgFull',
						`tipoimagen`='$ext' 
					WHERE `id`='$id';";

			accion($query);
			header('location:abm.php?e=2');
		}

		
	} else if (isset($_POST['nombre']) && isset($_POST['sinopsis']) && isset($_POST['anio']) &&
	isset($_POST['genero']) /*&& count($_FILES)*/) {
		
		$id = $_POST['id'];

		$nombre = $_POST['nombre'];
		$sinopsis = $_POST['sinopsis'];
		$anio = $_POST['anio'];
		$generoid = $_POST['genero'];
	/*	
		$_POST
		[nombre] => Comedia
    	[sinopsis] => Super graciosa
    	[anio] => 2013
    	[genero] => 1
    	[id] => 1

    $_FILE
    	 [name] => aboveaveragehomegrownbeetroot.png
         [type] => image/png
         [tmp_name] => C:\xampp\tmp\php806E.tmp
         [error] => 0
         [size] => 9747

     */
   
	//$con = conexion();

        $query="SELECT *
				FROM peliculas
				WHERE id='$id';";

		$imagenPel=fetch($query,true);
		$imgFull=$imagenPel['contenidoimagen'];
		$ext=$imagenPel['tipoimagen'];

	

				
		/*$imgTmp = $_FILES['img']['tmp_name'];
		$img = $_FILES['img']['name'];
		$tipo = $_FILES['img']['type']; //image/png
		$tipoEx = explode('/', $tipo);
		$tipoArchivo = $tipoEx[0];
		$ext = $tipoEx[1];
		$imgFull="uploads/".time()."$img";
		move_uploaded_file($imgTmp, $imgFull);

		if ($tipoArchivo!='image') {
			header("location:abm.php?op=mod&id=$id&e=1");
		} else {*/
				$query= "UPDATE peliculas 
							SET `nombre`='$nombre', 
								`sinopsis`='$sinopsis', 
								`anio`='$anio', 
								`generos_id`='$generoid',
								`contenidoimagen`='$imgFull',
								`tipoimagen`='$ext'	
							WHERE `id`='$id';";
				
				accion($query);

				header('location:abm.php?e=2');
		/*} */

	} else { header('location:abm.php?e=3'); }
} else { header("location:index.php");}

