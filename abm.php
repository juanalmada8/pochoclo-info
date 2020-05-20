<?php require 'includes/session.php';
validarLogin(); 
$pag='Administrar películas'; ?>
<?php require 'includes/encabezadoLog.php';?>
<?php if ($_SESSION['admin']) { 
	if (isset($_GET['e'])) {
		$msj = $_GET['e']; } else { $msj=false; }
		
		$mensajeTipo=array();
		$mensajeTipo[1]="Ingrese una imágen válida";
		$mensajeTipo[2]="Modificación exitosa";
		$mensajeTipo[3]="Campos vacíos, modificación no realizada";
		$mensajeTipo[4]="Película agregada exitosamente";
		$mensajeTipo[5]="Película eliminada exitosamente";


	
	if ( isset($_GET['op']) && $_GET['op']=='mod' ) {
		$id=$_GET['id']; ?>
		<div class="menu">
				<?php if ($msj) {?>
					<h2 class="mensaje"> 
					<?php echo $mensajeTipo[$msj]; ?>	
					</h2>
				<?php } ?>
			<h3 class="titulo">Modificar película</h3>
			<a class="volver" href="abm.php">Volver</a><br>	<br>	
			
			<div class="contenidoPelicula">
				<a class="volver" href="abm.php?op=foto&id=<?php echo $id ?>">Cambiar Imagen</a><br>
				<?php $query ="SELECT 
							 		p.id,
						   			p.nombre, 
						   			p.sinopsis, 
						   			p.anio, 
						   			p.contenidoimagen,
						   			g.id AS generoId, 
					   				g.genero
								FROM
						   			peliculas AS p
						    	LEFT JOIN
						   			generos AS g ON g.id = p.generos_id
						   		WHERE p.id=$id;";
					
					$pelicula=fetch($query);
					/*
						[id] => 3
					    [nombre] => Hola
					    [sinopsis] => testeo
					    [anio] => 2345
					    [contenidoimagen] => fdsfffd
					    [generoId] => 1
					    [genero] => Drama
					 */
					 
					foreach($pelicula as $row) { 
						$nombre = $row['nombre'];
						$sinopsis = $row['sinopsis'];
						$anio = $row['anio'];	
						$genId = $row['generoId'];
						$img = $row['contenidoimagen'];?>

				<div class="imagen">
						<img src="<?php 
						echo $img;?>">
				</div>
				<div class="info">
					<form action="peliculaMod.php" method="POST">
									<label>Título: </label>
									<input type="text" name="nombre" id="nombre" required value="<?php echo $nombre; ?>"><br><br>	
									<div class="textarea">
										<label>Sinopsis:
										<textarea name="sinopsis" rows="4" cols="40"required><?php echo $sinopsis; ?></textarea></label><br><br>
									</div>	
									<label>Año: </label>
									<input type="text" name="anio" id="anio" pattern="[0-9]{4}" required value="<?php echo $anio; ?>"><br><br>
									<!--<label>Imagen:</label>					
									<input name="img" type="file" accept="image/*" value="<?php echo $img ?>"><br><br>-->
									<label>Género:</label><br><br><br>	
									<div class="genero">
								
										<?php $generos = fetch('SELECT * FROM generos');
										foreach ($generos as $rowG) { ?>

										<!--[id] => 3
										    [nombre] => Hola
										    [sinopsis] => testeo
										    [anio] => 2345
										    [contenidoimagen] => fdsfffd
										    [generoId] => 1
										    [genero] => Drama -->

											<input type="radio" name="genero" value="<?php echo $rowG['id']; ?>" <?php if ($rowG['id']==$genId){ echo "checked"; } ?>><?php echo $rowG['genero']; ?><br><br>
										<?php } ?>
									</div><br><br>
									<input type="hidden" name="id" value="<?php echo $id; ?>">
									<button id="boton">Modificar Película</button>
					</form>
				</div>
				<?php } ?>
			</div>
		</div>
	<?php }

	else if ( isset($_GET['op']) && $_GET['op']=='del' ) {
		$idP= $_GET['id'];
		header('Location:peliculaBaja.php?id='.$_GET['id']);
	}else if (isset($_GET['op']) && $_GET['op']=='foto') {
		
		$id=$_GET['id']; ?>
	
		<div class="menu">
		<?php  if ($msj) {?>
			<h2 class="mensaje"> 
			<?php echo $mensajeTipo[$msj]; ?></h2>
		<?php } ?>
			<h3 class="titulo">Pochoclo Info</h3>
					<div id="agregarP">
						<a href="abm.php?op=mod&id=<?php echo $id; ?>">Volver</a>
					</div>
		
			<div class="contenidoPelicula">
				 <form action="peliculaMod.php?opc=foto" method="POST" enctype="multipart/form-data">
				 	<label>Imagen:</label>					
					<input name="img" type="file" accept="image/*" value="<?php echo $img ?>"><br><br>
					<input type="hidden" name="id" value="<?php echo $id ?>">
				 	<button id="botoncito">Cambiar</button>
				 </form>
			 </div>
		</div> 
	<?php  }  else { ?>
			<?php $datos = fetch ("SELECT 
				   						 p.id,
				   						 p.nombre, 
				   						 p.sinopsis, 
				   						 p.anio, 
				   						 p.contenidoimagen, 
				   						 g.genero
										FROM
				   						 peliculas AS p
				    					LEFT JOIN
				   						 generos AS g ON g.id = p.generos_id;"); ?>
			<div class="menu">
				<?php if ($msj) {?>
					<h2 class="mensaje"> 
					<?php echo $mensajeTipo[$msj]; ?>	
					</h2>
				<?php } ?>
				<h3 class="titulo">Pochoclo Info</h3>
				<div id="agregarP">
					<a href="peliculaAlta.php">Agregar película</a>
				</div>
				<form method="GET" action="abm.php">
					<div class="marcoPel">
						<?php foreach ($datos as $row) { ?>
						<div class="peliculaABM">
							<div id="imgM">
							<img src="<?php	echo $row['contenidoimagen'];?>">
							</div>
							<div class="parrafo">
								<p>Título: <?php echo $row['nombre'];?></p>
								<p>Año: <?php echo $row['anio']; ?></p>
								<p>Género: <?php echo $row['genero']; ?></p>
							</div>
							<div class="etiqueta">
								<a href="abm.php?op=mod&id=<?php echo $row['id']; ?>">Modificar</a>

								<a href="abm.php?op=del&id=<?php echo $row['id']; ?>">Eliminar</a>
							</div>
						</div>
						<?php } ?>
					</div>
				</form>
			</div>		
		
	<?php } ?>
<?php } else { header("location:index.php");} ?>
	<?php require 'includes/footer.php'; ?>