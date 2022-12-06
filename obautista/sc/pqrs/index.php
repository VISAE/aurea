<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b lunes, 24 de octubre de 2022
*/
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['debug']) != 0) {
	if ($_REQUEST['debug'] == 1) {
		$bDebug = true;
	}
}
if ($bDebug) {
	$iSegIni = microtime(true);
	$iSegundos = floor($iSegIni);
	$sMili = floor(($iSegIni - $iSegundos) * 1000);
	if ($sMili < 100) {
		if ($sMili < 10) {
			$sMili = ':00' . $sMili;
		} else {
			$sMili = ':0' . $sMili;
		}
	} else {
		$sMili = ':' . $sMili;
	}
	$sDebug = $sDebug . date('H:i:s') . $sMili . ' Inicia pagina <br>';
}
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
// -- Se cargan los archivos de idioma
if (isset($_SESSION['unad_idioma']) == 0) {
	$_SESSION['unad_idioma'] = 'es';
}
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3005)) {
	$mensajes_3005 = 'lg/lg_3005_es.php';
}
require $mensajes_todas;
require $mensajes_3005;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
//PROCESOS DE LA PAGINA
require 'lib3005_externa.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f3005_HTMLOpcionInicial');
$xajax->processRequest();
$xajax->printJavascript($APP->rutacomun . 'xajax/');

$sError = '';
$html_encuesta = '';

if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = 0;
}

if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Paso ' . $_REQUEST['paso'] . '<br>';
}
if ($_REQUEST['paso'] == 0) {
	
}
//Crear los controles que requieran llamado a base de datos
// $objCombos = new clsHtmlCombos();
// $objCombos->nuevo('aure73tipodoc', $_REQUEST['aure73tipodoc'], true, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($aaure73tipodoc, $iaure73tipodoc);
// $html_aure73tipodoc = $objCombos->html('', $objDB);

?>
<!doctype html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

	<meta name="description" content="Plataforma Aurea, Universidad Abierta y a Distancia UNAD de Colombia">
	<meta name="author" content="Universidad Abierta y a Distancia UNAD de Colombia">

	<link rel="icon" type="image/svg+xml" href="../img/favicon/favicon.svg">
	<link rel="icon" type="image/png" href="../img/favicon/favicon.png">

	<title>Plataforma Aurea, Universidad Abierta y a Distancia UNAD de Colombia</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css" media="all" />
	<link rel="stylesheet" href="../css/font-awesome.min.css" media="all" />

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		function enviaopcionini(id) {
			let $aParametros = new Array();
			$aParametros[100] = id;
			xajax_f3005_HTMLOpcionInicial($aParametros);
		}

		function muestramensajes(tipo, texto) {
			htmlToast.className = htmlToast.className.replace(/bg-\w+/g, 'bg-' + tipo);
			document.getElementsByClassName('toast-body')[0].innerHTML = texto;
			objToast.show();
		}
	</script>
</head>

<body>

	<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
		<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
	</form>
	<?php
	echo $sError;
	?>


	<!-- HEADER -->
	<header class="header">
		<div class="container">
			<a href="/">
				<img src="../img/unad.svg" alt="Logo Universidad Abierta y a Distancia - UNAD - Colombia">
			</a>
			<div class="header__title">
				<h1>Portal de Servicios</h1>
			</div>
		</div>
	</header>
	<!-- end HEADER -->


	<!-- MAIN -->
	<main class="main">
		<!-- slider images  -->
		<section class="images">
			<div class="logo">
				<img src="../img/plataforma-aurea.png" alt="Plataforma Aurea" />
			</div>
			<span></span>
			<div class="preload">
				<img src="../img/slide/001.jpg" />
				<img src="../img/slide/002.jpg" />
				<img src="../img/slide/003.jpg" />
				<img src="../img/slide/004.jpg" />
			</div>
			<div class="slide">
				<div style="background-image:url(../img/slide/001.jpg)"></div>
				<div style="background-image:url(../img/slide/002.jpg)"></div>
				<div style="background-image:url(../img/slide/003.jpg)"></div>
				<div style="background-image:url(../img/slide/004.jpg)"></div>
			</div>
		</section>
		<!-- end slider images  -->

		<!-- dates area -->
		<section class="dates">
			<!-- menu -->
			<nav class="navbar navbar-expand-xl navbar-dark">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="../">
							<i class="fa fa-home"></i>
							Inicio
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="./">
							<i class="fa fa-list"></i>
							<?php echo $ETI['sigla_3005']; ?>
						</a>
					</li>
				</ul>
			</nav>
			<!-- end menu -->

			<hr />

			<article class="article">
				<h2>
					<span><i class="fa fa-list"></i> <?php echo $ETI['titulo']; ?></span>
				</h2>
				<div class="article__area row">
					<!-- form area -->
					<div class="col-12">

						<?php
						$sStyleRutas = 'style="display:none;"';
						if ($sError == '') {
							$sStyleRutas = 'style="display:block;"';
						} else {
						?>
							<div class="alert alert-danger" role="alert"><strong>Ha ocurrido un error</strong></div>
						<?php
						}
						?>
						<div id="div_saiu05rutaspqrs" class="shadow p-3 mb-5 bg-white rounded text-center" <?php echo $sStyleRutas; ?>>
							<div class="form-group row">
								<div class="col-sm-12">
									<input type="button" id="cmdConsultar" name="cmdConsultar" class="btn btn-aurea w-50" title="<?php echo $ETI['bt_consultar']; ?>" value="<?php echo $ETI['bt_consultar']; ?>" onclick="enviaopcionini(1)">
								</div>
								<div class="col-sm-12">
									<input type="button" id="cmdRadicar" name="cmdRadicar" class="btn btn-aurea w-50 mt-2" title="<?php echo $ETI['bt_radicar']; ?>" value="<?php echo $ETI['bt_radicar']; ?>" onclick="enviaopcionini(2)">
								</div>
							</div>
						</div>
						<?php
						?>
					</div>
					<!-- end form area -->
				</div>
				<!-- toast -->
				<div style="position: fixed; top: 80%; right: 0; padding:5px;">
					<div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
						<div class="d-flex">
							<div class="toast-body">
							</div>
							<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				<!-- end toast -->
			</article>
			<?php
			if ($sDebug != '') {
				$iSegFin = microtime(true);
				if (isset($iSegIni) == 0) {
					$iSegIni = $iSegFin;
				}
				$iSegundos = $iSegFin - $iSegIni;
				echo '<article>
  <div class="GrupoCampos" id="div_debug">
  ' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '
  </div>
  </article>';
			}
			?>
		</section>
		<!-- end dates area -->
		<!-- footer -->
		<footer class="footer">
			<p>Universidad Nacional Abierta y a Distancia UNAD de Colombia - &copy; Copyright UNAD 2022</p>
		</footer>
		<!-- end footer -->
	</main>
	<!-- end MAIN -->
	<script>
		let htmlToast = document.getElementById('liveToast');
		let objToast = new bootstrap.Toast(htmlToast);
		// $(document).ready(() => {
		// 	$('select').addClass("form-control");
		// });
	</script>
</body>

</html>
