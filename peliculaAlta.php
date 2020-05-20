<?php require 'includes/session.php';
validarLogin(); 
$pag='Administrar películas'; ?>
<?php require 'includes/encabezadoLog.php'; ?>

<?php if ($_SESSION['admin']) {
	
	$msjTipo = array();
	$msjTipo[1] = 'Ingrese una imagen válida'; ?>
	
	<?php if (!empty($_POST)){
	
		if (isset($_POST['nombre']) && isset($_POST['sinopsis'])
		&& isset($_POST['anio'])&& isset($_POST['genero'])
		&& count($_FILES)){
		
			$nombre = $_POST['nombre'];
			$sinopsis = $_POST['sinopsis'];
			$anio = $_POST['anio'];
			$generoid = $_POST['genero'];
			

			$imgTmp = $_FILES['img']['tmp_name'];
			$img = $_FILES['img']['name'];
			$tipo = $_FILES['img']['type']; //image/png
			$tipoEx = explode('/', $tipo);
			$tipoArchivo = $tipoEx[0];
			$ext = $tipoEx[1];
			$imgFull="uploads/".time()."$img";
			move_uploaded_file($imgTmp, $imgFull);
			
			if ($tipoArchivo!='image') {
				#header("location:peliculaAlta.php?e=1");
				$papita=1;
			} else {
				$query = "INSERT INTO 
							`peliculas` (`nombre`, `sinopsis`, `anio`, `generos_id`, `contenidoimagen`, `tipoimagen`) VALUES ('$nombre', '$sinopsis', '$anio', '$generoid', '$imgFull', '$ext');";
				accion($query);
				header("location:abm.php?e=4");
			}
		}else { $papita=1;}
 	} ?>
 	<!--<?php// if (isset($_SESSION['logueado'])){ ?>-->
		<div>
		<?php if (isset($papita)) {
			$error = $papita; } else { $error=false;} ?>
			<section class="menu">
			<?php if ($error) {?>
				<h2 class="mensaje"> 
				<?php echo $msjTipo[$error]; ?>	
				</h2>
			<?php } ?>
		
				<h3 class="titulo">Agregar Película</h3>
				<form method="POST" action="peliculaAlta.php" enctype="multipart/form-data" >
					<div class="contenidoPelicula">
						<label>Título: </label>
						<input type="text" name="nombre" id="nombre" required placeholder="Ingrese el título" value="<?php echo (isset($_POST['nombre'])) ? $_POST['nombre'] : '' ; ?>"><br><br>	
						<div class="textarea">
							<label>Sinopsis:
							<textarea name="sinopsis" rows="4" cols="40"required placeholder="Ingrese la sinopsis de la película."><?php echo (isset($_POST['sinopsis'])) ? $_POST['sinopsis'] : '' ; ?></textarea></label><br><br>
						</div>	
						<label>Año: </label>
						<input type="text" name="anio" id="anio" pattern="[0-9]{4}" maxlength="4" placeholder="AAAA" value="<?php echo (isset($_POST['anio'])) ? $_POST['anio'] : '' ; ?>" required><br><br>
						<label>Imagen</label>					
						<input name="img" type="file" accept="image/*"><br><br>
						<label>Género:</label><br><br>	
						<div class="genero">
							<?php $generos = fetch('SELECT * FROM generos');
							foreach ($generos as $rowG) { ?>
								<input type="radio" name="genero" value="<?php echo $rowG['id'] ?>" <?php echo (isset($_POST['genero']) && $_POST['genero']==$rowG['id']) ? 'checked' : '' ; ?>><?php echo $rowG['genero']; ?><br><br>
							<?php } ?>
						</div><br><br>
						<button id="boton">Agregar</button>
					</div>		
				</form>		
			</section>
		</div>
<?php } else { header("location:index.php");} ?>
		
<?php require 'includes/footer.php'; ?>