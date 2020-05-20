<?php session_start(); ?>
<?php $pag='Registro'; ?>
<?php require 'includes/encabezadoLog.php'; ?>


<?php if (!empty($_POST)){
	if (isset($_POST['nombre']) && isset($_POST['apellido'])
		&& isset($_POST['usuario'])&& isset($_POST['clave'])
		&& isset($_POST['repClave'])){

			$con = conexion();
			$nombre = mysqli_real_escape_string($con,$_POST['nombre']);
			$apellido = mysqli_real_escape_string($con,$_POST['apellido']);
			$usuario = mysqli_real_escape_string($con,$_POST['usuario']);
			#$email =  mysqli_real_escape_string($con,$_POST['email']);
			$clave = mysqli_real_escape_string($con,$_POST['clave']);
			$repClave = mysqli_real_escape_string($con,$_POST['repClave']);
			$email='';

			if (isset($_POST['email'])) {
				$email =  mysqli_real_escape_string($con,$_POST['email']);
			} 

			$query = "SELECT * FROM usuarios WHERE nombreusuario='$usuario';";
			$result = fetch($query,true);
			
			if (empty($result) && $clave==$repClave) {
				
				$query = "INSERT INTO `usuarios` (`nombre`, `apellido`, `email`, `password`, `nombreusuario`, `administrador`) VALUES ( '$nombre', '$apellido', '$email', '$clave', '$usuario', '0');";

				$res = accion($query, true);
				
				if( $res || $res==0) {
					$lastInsertId = $res;
				}else { die(); }
				
				$_SESSION['logueado'] = true;
				$_SESSION['idUsuario'] = $lastInsertId;
				$_SESSION['nombre'] = $usuario;
				$_SESSION['nom'] = $nombre;
				$_SESSION['ape'] = $apellido;
				$_SESSION['admin'] = 0;
				
				header("location:index.php?e=1");				
			
			}else if ($clave!=$repClave) {
				#$error = "<h4 class='error'>Las contraseñas no coinciden</h4";
				$papita=1;
			} else if (!empty($result)) {
				#$error = "<h4 class='error'>Tu usuario ya ha sido registrado!</h4";
				$papita=2;
			} else if (!isset($_POST['nombre']) && isset($_POST['apellido'])) {
				#$error = "<h4 class='error'>Falta rellenar el campo nombre</h4";
				$papita=3;
			} else if (isset($_POST['nombre']) && !isset($_POST['apellido'])){
				#$error = "<h4 class='error'>Falta rellenar el campo apellido</h4";
				$papita=4;
			} else if (!isset($_POST['nombre']) && !isset($_POST['apellido'])) {
				#$error = "<h4 class='error'>Falta rellenar los campos nombre y apellido</h4";}
				$papita=5; }
	} 
} ?>

<?php if (isset($papita)) {
		$error=$papita; } else { $error= false; }	
 $mensajeTipo=array();
 $mensajeTipo[1]="Las contraseñas no coinciden";
 $mensajeTipo[2]="Tu usuario ya ha sido registrado!";
 $mensajeTipo[3]="Falta rellenar el campo nombre";
 $mensajeTipo[4]="Falta rellenar el campo apellido";
 $mensajeTipo[5]="Falta rellenar los campos nombre y apellido";
 ?>
 
<div class="menu">
	<h3 class="titulo">Registrarse</h3>
	<?php if ($error) {?>
		<h2 class="mensaje"> 
		<?php echo $mensajeTipo[$error]; ?>	
		</h2>
	<?php } ?>
	<form method="POST" action="registro.php">
		<label>Nombre:
		<input type="text" name="nombre" placeholder="Ingrese su nombre" pattern="[a-zA-Z]{2,}" value="<?php echo (isset($_POST['nombre'])) ? $_POST['nombre'] : '' ; ?>" required>
		</label><br><br>
		<label>Apellido:
		<input type="text" name="apellido" placeholder="Ingrese su apellido" pattern="[a-zA-Z]{2,}" value="<?php echo (isset($_POST['apellido'])) ? $_POST['apellido'] : '' ; ?>" required></label><br><br>
		<label>Usuario:
		<input type="text" name="usuario" placeholder="Ingrese su usuario" pattern="[a-zA-Z0-9]{6,}" value="<?php if (isset($_POST['usuario']) && $error!=2) {
			echo $_POST['usuario'];	}?>"  required></label><br><br>
		<label>E-mail:
		<input type="email" name="email" placeholder="Ingrese su email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '' ; ?>" ></label><br><br>
		<label>Contraseña:
		<input type="password" name="clave" placeholder="Ingrese su contraseña" title="La clave debe tener 6 caracteres, letras (mayúsculas y minúsculas), por lo menos un número o un símbolo" pattern="((?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,})" required></label><br><br>
		<label>Repetir contraseña:
		<input type="password" name="repClave" placeholder="Repita su contraseña" required></label><br><br>
		<button id="boton">Registrarme</button>

	</form>
</div>

<?php require 'includes/footer.php'; ?>
