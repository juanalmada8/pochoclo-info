<?php session_start(); ?>
<?php $pag='LogIn'; 
require 'includes/encabezadoLog.php'; ?>

<?php
$error=false;
if (!empty($_POST)) {
 	if ( !empty($_POST['usuario']) && !empty($_POST['pass']) ) {
	
	$usuario = $_POST['usuario'];

	$query="SELECT 
				id,
				nombre,
				apellido,
			    password,
			    nombreusuario,
			    administrador
			FROM
			    usuarios
			WHERE
			    nombreusuario = '$usuario';";

	$result = fetch($query,true);
	
	$claveBd = $result['password'];
	$clave = $_POST['pass'];

	if ( $claveBd == $clave) {
		
		$_SESSION['logueado'] = true;
		$_SESSION['idUsuario'] = $result['id'];
		$_SESSION['nombre'] = $result['nombreusuario'];
		$_SESSION['nom'] = $result['nombre'];
		$_SESSION['ape'] = $result['apellido'];
		$_SESSION['admin'] = $result['administrador'];
		header("location:index.php?e=1");

	} else $error = "<h4 class='error'>Datos incorrectos</h4>";
		

	} else if ( !empty($_POST['usuario']) && empty($_POST['pass']) ){
		
		$error = "<h4 class='error'>Escribí tu contraseña</h4>";


	} else if ( empty($_POST['usuario']) && !empty($_POST['pass']) ){
		
		$error = "<h4 class='error'>Escribí tu usuario</h4>";

	} else if ( empty($_POST['usuario']) && empty($_POST['pass']) ){
		$error =  "<h4 class='error'>Rellená los dos campos requeridos</h4";
	}
 }

?>
<div class="menu">
	<h3 class="titulo">Iniciar Sesión</h3>
	<?php 
		if ($error) {
			echo $error;
		}
	?>
	<form method="POST" action="login.php">
		<label>Usuario:
			<input type="usuario" name="usuario" placeholder="Ingrese su usuario" pattern="[a-zA-Z0-9]{6,}" required>
		</label><br><br>	
		<label>Contraseña:
			<input type="password" name="pass" placeholder="Ingrese su contraseña" pattern="{6,}" required>
		</label><br><br>		
		<button>Entrar</button>
	</form>
</div>
<?php require 'includes/footer.php'; ?>