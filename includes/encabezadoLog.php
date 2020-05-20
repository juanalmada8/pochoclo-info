<?php require_once 'bd.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Pochoclo Info | <?php echo $pag; ?></title>
		<link rel="stylesheet" href="styles/style.css">
	</head>
	
<body>		
	<div id="wrap">	
		<div id="logo">
			<a href="index.php">
			<img src="img/pochoclin.png" alt="logo">

			</a>
		</div>
		<nav id="nav">
			<a href="index.php" class="<?php echo ($pag=='Home')?'selected':''; ?>"> Home </a>
			<?php if ($_SESSION['admin']) {?>
				<a href="abm.php" class="<?php echo ($pag=='Administrar películas')?'selected':''; ?>">Administrar Películas</a>
			<?php } ?>
			<?php if (!$_SESSION['logueado']) {?> 

			<a href="login.php" class="<?php echo ($pag=='LogIn')?'selected':''; ?>"> Iniciar Sesión </a>

			<a href="registro.php" class="<?php echo ($pag=='Registro')?'selected':''; ?>"> Registrarse </a>
		

			<?php } else { ?>

				<a href="logout.php"> Cerrar Sesión </a> <?php } ?>

			
			
		</nav>