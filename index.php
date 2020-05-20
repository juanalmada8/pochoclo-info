<?php session_start(); ?>
<?php $pag='Home'; ?>
<?php require 'includes/encabezadoLog.php';
$msj=false;
if (isset($_GET['e'])) {
		$msj = $_GET['e']; } else { $msj=false; }
		$mensajeTipo=array();
		$mensajeTipo[1]="Bienvenido";?>
<?php 

	$entrar = false;
	$nombre = false;
	$genero = false;
	$anio = false;

	if ($_GET['ord']=='ASC' || $_GET['ord']=='DESC') {
		$tipo = $_GET['ord'];
	}

	if (isset($_GET['nombre'])) {
		$nombre = $_GET['nombre'];
		$entrar = true;
	}

	if ($_GET['gen']!=0) {
		$genero = $_GET['gen']; 
		$entrar = true;
	}

	if ($_GET['anio']!=0) {
		$anio = $_GET['anio'];
		$entrar = true;
		
	}

	if ($_GET['crit']=='anio' || $_GET['crit']=='nombre') {
		$crit = $_GET['crit'];
	} else { $crit=false; }


	$filtros = ($nombre || $genero!=0 || $anio!=0) ? ' WHERE ' : ' ';

	if ($entrar) {
		$contador = 0;

		if ($nombre) {
			$filtros .= "p.nombre like '%$nombre%'";
			$contador++;
		}

		if ($genero) {
			$filtros .= ($contador!=0)? ' AND ': '';
			$filtros .= "p.generos_id = $genero";
			$contador++;
		}

		if ($anio) {
			$filtros .= ($contador!=0)? ' AND ': '';
			$filtros .= "p.anio = $anio";
			$contador++;
		}

		if  ($tipo!='ASC' && $tipo!='DESC' ){ 
			$tipo='ASC'; 
		}

		if(!$crit){
			$tipo='ASC';
		}

		if ($crit=='nombre') { 
			$orden = " ORDER BY p.$crit $tipo";
		} else if ($crit=='anio') { 
			$orden = " ORDER BY p.$crit $tipo";
		} 

		else {
			$orden = " ORDER BY p.id $tipo";
		}
	}

	$start=0;
	

	if (isset($_GET['s'])) {
		$start=$_GET['s'];
	}

	

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
	   			$filtros
				$orden
				LIMIT $start,5;";
	   			
	$datos = fetch($query);?>
	
	<div class="menu">
			<?php if ($msj) {?>
				<h4 class="mensaje"><?php echo $mensajeTipo[$msj].' '.$_SESSION['nom']; ?></h4>
			<?php } ?>
		<h3 class="titulo">Pochoclo Info</h3>
		<section id="filtro">
			<form method="GET" action="index.php">
				<p> <!--<label>Filtrar:</label>-->
				<input type="text" name="nombre" placeholder="Palabra o nombre" value="<?php if (isset($_GET['nombre'])) { echo $_GET['nombre']; } ?>">
				<select name="gen"> 
					<option value="0" selected>Género</option>
					<?php $genero = fetch ('SELECT * FROM generos');
										foreach ($genero as $rowG) { ?>
					<option value="<?php echo $rowG['id']; ?>" <?php echo ($_GET['gen']!=0 && $_GET['gen']==$rowG['id']) ? "selected" : '' ;?> ><?php echo $rowG['genero']; ?>
					</option>
					<?php } ?>
					</select>
					<select name="anio">
						<option value="0" selected>Año</option>
						<?php $anio = fetch ('SELECT DISTINCT anio FROM peliculas
												ORDER BY anio desc;');
						foreach ($anio as $rowA) { ?>
							<option value="<?php echo $rowA['anio']; ?>"<?php echo ($_GET['anio']!=0 && $_GET['anio']==$rowA['anio']) ? "selected" : '' ;?>><?php echo $rowA['anio']; ?></option>
						<?php } ?>
					</select>&nbsp;&nbsp;
					<select name="crit" id="crit" required>
						<option value="0" selected>Ordenar</option>									
						<option value="anio" <?php echo ($_GET['crit']=="anio" ) ? "selected" : '' ;?> >Año</option>			
						<option value="nombre" <?php echo ($_GET['crit']=="nombre" ) ? "selected" : '' ;?>>Nombre</option>							
					</select>
					<select name="ord" id="ordenar" required>
						<option value="0" selected>Seleccione</option>									
						<option value="ASC" <?php echo ($_GET['ord']=="ASC" ) ? "selected" : '' ;?> >Ascendente</option>					
						<option value="DESC" <?php echo ($_GET['ord']=="DESC" ) ? "selected" : '' ;?>>Descendente</option>							
					</select>
					<button>Buscar</button></p>
			</form>
		</section>
	<?php  if (!empty($datos)) { ?>			
		<section id="main">
		<?php foreach ($datos as $row) { 
				
				$idP=$row['id'];
				
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
				
				$cal = fetch($queryCalificacion); 
					
					/* 	[resultado] => 4.33
       					[entero] => 4
					*/

				foreach ($cal as $value) {
					$resultado=$value['resultado'];
					$entero=$value['entero'];
				}?>

				<div class="pelicula">				
					<div class="imagenP">
					<a href="pelicula.php?id=<?php echo $row['id'] ?>">
					<img src="<?php 
					echo $row['contenidoimagen'];?>"></a><br>
					</div>
					<div class="contenidoP">
					<div class="tituloP">
						<a  href="pelicula.php?id=<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?> (<?php echo $row['anio'];?>)</a>
					</div>
					<p><?php echo $row['sinopsis']; ?></p>
					<p><?php echo $row['genero']; ?></p>
					<?php if ($resultado>0) { ?>
						<div class="calificacion">
							<p><?php for ($i=0; $i < $entero; $i++) { ?>
								<img src="img/estrelladorada.png">
							<?php } ?>(<?php echo $resultado ?>)</p>
						</div>
					<?php } ?>
					<a class="masP" href="pelicula.php?id=<?php echo $row['id']; ?>">Ver más</a>
					</div>
				</div><br>

			<?php } ?>
		<?php } else {?>
		<div id="vacio">
		<?php  	echo "<h4 class='error'>No hay películas para mostrar :( </h4>";?>
		</div>
	<?php }?>
<div id="paginator">
	<?php 
	$cont = "SELECT 
						count(p.id) as cnt
					FROM
		   				peliculas AS p
		    		LEFT JOIN
		   				generos AS g ON g.id = p.generos_id
		   			$filtros
					$orden";

	$count = fetch($cont,true);
//$count['cnt']=$count['cnt']-1;

		$paginas = $count['cnt']/5;
		$redondeo = (is_int($paginas))? true: false;
		$loop = ($redondeo)? $paginas: $paginas;

		//nombre=&gen=0&anio=0&crit=0&ord=0

		$urlRec="";

			//$urlRec.=(strlen($urlRec)>0)? $urlRec+'&':$urlRec;
		if (isset($_GET['nombre'])) { $urlRec.="nombre=".$_GET['nombre']."&"; }
		if (isset($_GET['gen'])) { $urlRec.="gen=".$_GET['gen']."&"; }
		if (isset($_GET['anio'])) { $urlRec.="anio=".$_GET['anio']."&"; }
		if (isset($_GET['crit'])) { $urlRec.="crit=".$_GET['crit']."&"; }
		if (isset($_GET['ord'])) { $urlRec.="ord=".$_GET['ord']."&"; }


		for ($i=0; $i < $loop; $i++) { ?>
			<a href="index.php?<?php echo "$urlRec"; ?>s=<?php echo ($i*5); ?>"> <?php echo $i+1; ?></a>
	<?php }
	 ?>
	 </div>
	</section>
</div>

<?php require 'includes/footer.php'; ?>