<?php session_start(); ?>
<?php $pag='Pelicula'; ?>
<?php require 'includes/encabezadoLog.php'; 

if (isset($_GET['id'])) {
	$idP=$_GET['id'];
		$query = "SELECT 
					p.id,
		   			p.nombre, 
		   			p.sinopsis, 
		   			p.anio, 
		   			p.contenidoimagen,
					g.genero
				FROM
	   				peliculas AS p
	    		LEFT JOIN
	   				generos AS g ON g.id = p.generos_id
	   			WHERE
	   				p.id='$idP';";


	$datos = fetch($query);

		/*	[nombre] => El Fantasma de la Opera
            [sinopsis] => Un extraño sujeto que .....
            [anio] => 2015
            [contenidoimagen] => uploads/1495837131operahousemaskstand.png
            [genero] => Drama 
         */

    $query = "SELECT 
				    c.comentario, 
				    c.fecha, 
				    c.calificacion, 
				    u.nombre, 
				    u.apellido
				FROM
				    peliculas AS p
				LEFT JOIN
				    comentarios as c ON c.peliculas_id = p.id
				LEFT JOIN
				    usuarios AS u ON u.id = c.usuarios_id
				WHERE
				    p.id='$idP'
				    ORDER BY c.fecha DESC;";
    $com = fetch($query); 
    
    	/*	[comentario] => 
            [fecha] => 
            [calificacion] => 
            [nombre] => 
            [apellido] => 
         */
    $queryCalificacion="SELECT 
							ROUND(suma / cuenta,2) as resultado,
							ROUND(suma / cuenta,0) as entero
						from
						    (SELECT 
						        count(*) as cuenta, 
								sum(calificacion) as suma
						    from
						        comentarios as c
						    where
						        c.peliculas_id = '$idP') as total;";
	$cal =fetch($queryCalificacion);

		/* 	[resultado] => 4.33
       		[entero] => 4
		*/	

		            
 ?>  
 <?php 
	 if (isset($_GET['m'])) {
			$msj = $_GET['m']; } else { $msj=false; }
	$mensajeTipo=array();
	$mensajeTipo[1]="Elija una calificación"; 
?>
	<div class="menu">
				<?php if ($msj) {?>
					<h2 class="mensaje"> 
					<?php echo $mensajeTipo[$msj]; ?>	
					</h2>
				<?php } ?>
	 	<h3 class="titulo">Pochoclo Info</h3>
	 	<section id="main">
	 	<?php if ($_SESSION['logueado'] && $_SESSION['admin']) {?>
	 	<div id="opciones">
			<a href="abm.php?op=mod&id=<?php echo $idP?>">Modificar</a> <br><br><br>
			<a href="abm.php?op=del&id=<?php echo $idP?>">Eliminar</a>		 		
	 	</div>
	
		 <?php } ?>
	 		<?php foreach ($datos as $row) { 
	 			foreach ($cal as $value) {
					$resultado=$value['resultado'];
					$entero=$value['entero'];
				}?>	 

				<div id="peliculaU">
					<img src="<?php	echo $row['contenidoimagen'];?>">
					<h3><?php echo $row['nombre'];?> (<?php echo $row['anio'];?>)</h3>	
					<?php if ($resultado>0) {?>	
						<div id="calificacionU">
							<p><?php for ($i=0; $i < $entero; $i++) { ?>
								<img src="img/estrelladorada.png">
							<?php } ?>(<?php echo $resultado ?>)</p>
						</div>
					<?php } ?>
					
					<p><?php echo $row['sinopsis']; ?></p>
					<p>Género: <?php echo($row['genero']); ?></p>
					
				</div>
			<?php } ?>

			<div id="com">
				<h3>Comentarios</h4>
				<?php if ($_SESSION['logueado']) {?>
					<form action="comentarioAlta.php" method="POST">
						<div class="textarea">
							<label>Comentar:
							<textarea name="comentario" rows="4" cols="40"required placeholder="Escriba su comentario." required=""></textarea></label><br>
						</div>
						<div id="cal">
							<p>Calificación: 
							<?php for ($i=1; $i < 6; $i++) { ?>
									<input type="radio" name="cal" value="<?php echo $i;?>"><?php echo $i;?>
							<?php }?></p>
						</div>
						<input type="hidden" name="idP" value="<?php echo $idP ?>">
						<button>Enviar</button>
					</form><hr>
				<?php } ?>
				<?php foreach ($com as $data) { ?>
					<?php if ($data['comentario']!=NULL){?>
							
							<?php $amd=$data['fecha'];
							$split=explode('-', $amd);
							$fecha=$split[2].'/'.$split[1].'/'.$split[0]; ?>
						
							<div class="comentario">
								<p><?php echo $data['nombre'].' '.$data['apellido'];?> - <?php echo $fecha?><br><br>
								<?php echo $data['comentario']; ?></p>
							</div>
							<div class="calificacionCom">
								<p><?php for ($i=0; $i < $data['calificacion']; $i++) { ?>
								<img src="img/estrelladorada.png"><?php } ?>(<?php echo $data['calificacion'] ?>)</p>
							</div>	
							<hr>
						
							
						<?php } else { echo "No hay comentarios para mostrar";} ?>
			<?php } ?>
			</div>		
		</section>
	</div>
<?php } ?>	
<?php require 'includes/footer.php'; ?>


