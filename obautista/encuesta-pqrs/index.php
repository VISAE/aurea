<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.0 lunes, 29 de marzo de 2023
--- Modelo Versión 2.0 miércoles, 15 de mayo de 2024
*/
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
$sError = '';
$html_encuesta = '';
$saiuid = '05';
$saiulib = '05';

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

if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = 0;
}

if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Paso ' . $_REQUEST['paso'] . '<br>';
}

if ($_REQUEST['paso'] == 0) {
	$sURL = '';
	if (isset($_GET['u']) != 0) {
		$sURL = url_decode_simple($_GET['u']);
	} else if (isset($_GET['n']) != 0) {
		$sURL = url_decode_simple($_GET['n']);
	}
	if ($sURL != '') {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Dato de llegada: ' . $sURL . ' <br>';
		}
		$aURL = explode('-', $sURL);
		if (count($aURL) < 3) {
			$sError = $sError . 'Codigo incorrecto.';
		} else {
			$sContenedor = $aURL[0];
			$saiuNNid = $aURL[1];
			$idRespuesta = $aURL[2];
		}
		if ($sError=='') {
			switch ($idRespuesta) {
				case '18':
				case '19':
				case '20':
				case '21':
					$saiuid = $idRespuesta;
					$saiulib = '00';
					break;
				default:
					$saiuid = '05';
					$saiulib = '05';
					$idRespuesta = 0;
					break;
			}
		}
	}
}

// -- Se cargan los archivos de idioma
if (isset($_SESSION['unad_idioma']) == 0) {
	$_SESSION['unad_idioma'] = 'es';
}
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_30NN = $APP->rutacomun . 'lg/lg_30'.$saiulib.'_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_30NN)) {
	$mensajes_30NN = $APP->rutacomun . 'lg/lg_30'.$saiulib.'_es.php';
}
require $mensajes_todas;
require $mensajes_30NN;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
//PROCESOS DE LA PAGINA
require $APP->rutacomun . 'lib30'.$saiulib.'.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f30'.$saiulib.'_BuscaCodigoEncuesta');
$xajax->register(XAJAX_FUNCTION, 'f30'.$saiulib.'_GuardaEncuesta');
$xajax->processRequest();
$xajax->printJavascript($APP->rutacomun . 'xajax/');

if (isset($_REQUEST['saui05numref']) == 0) {
	$_REQUEST['saui05numref'] = '';
}

$_REQUEST['saui05numref'] = trim(cadena_Validar($_REQUEST['saui05numref'], true));

if ($sError == '') {
	if ($saiuid == '05') {
		if (isset($_GET['u']) != 0) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Trayendo encuesta ' . $sContenedor . ' - ' . $saiuNNid . ' - ' . $idRespuesta . '<br>';
			}
			list($html_encuesta, $sErrorE) = f3005_HTMLForm_Encuesta($sContenedor, $saiuNNid, $idRespuesta, $objDB);
			$sError = $sError . $sErrorE;
		} else if (isset($_GET['n']) != 0) {
			$html_encuesta = f3005_HTMLNoRespondeEncuesta($sContenedor, $saiuNNid, $objDB, $bDebug);
		}
	} else {
		if (isset($_GET['u']) != 0) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Trayendo encuesta ' . $sContenedor . ' - ' . $saiuNNid . ' - ' . $saiuid . '<br>';
			}
			list($sTabla, $sErrorE) = f3000_ValidaTablasAtencion($sContenedor, $saiuid, $objDB);
			$sError = $sError . $sErrorE;
			if ($sError == '') {
				list($html_encuesta, $sErrorE) = f3000_HTMLForm_Encuesta($sTabla, $saiuid, $saiuNNid, $objDB);
				$sError = $sError . $sErrorE;
			}
		} else if (isset($_GET['n']) != 0) {
			list($sTabla, $sErrorE) = f3000_ValidaTablasAtencion($sContenedor, $saiuid, $objDB);
			$sError = $sError . $sErrorE;
			if ($sError == '') {
				$html_encuesta = f3000_HTMLNoRespondeEncuesta($sTabla, $saiuid, $saiuNNid, $objDB, $bDebug);
			}
		}
	}
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

	<!--
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    -->
	<script>
		<?php
		if ($saiuid == '05') {
		?>
		function enviacodigo() {
			let $aParametros = new Array();
			$aParametros[100] = window.document.frmcodigo.saui05numref.value;
			xajax_f3005_BuscaCodigoEncuesta($aParametros);
		}
		<?php
		}
		?>

		function enviaencuesta() {
			let $aParametros = new Array();
			$aParametros[100] = window.document.frmencuesta.sContenedor.value;
			$aParametros[101] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>id'].value;
			$aParametros[102] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalamabilidad'].value;
			$aParametros[103] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalamabmotivo'].value;
			$aParametros[104] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalrapidez'].value;
			$aParametros[105] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalrapidmotivo'].value;
			$aParametros[106] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalclaridad'].value;
			$aParametros[107] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalcalridmotivo'].value;
			$aParametros[108] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalresolvio'].value;
			$aParametros[109] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalsugerencias'].value;
			$aParametros[110] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalconocimiento'].value;
			$aParametros[111] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalconocmotivo'].value;
			$aParametros[112] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalutilidad'].value;
			$aParametros[113] = window.document.frmencuesta['saiu<?php echo $saiuid; ?>evalutilmotivo'].value;
		<?php
		if ($saiuid == '05') {
		?>
			xajax_f3005_GuardaEncuesta($aParametros);
		<?php
		} else {
		?>
			$aParametros[114] = window.document.frmencuesta.sTabla.value;
			$aParametros[115] = window.document.frmencuesta.saiuid.value;
			xajax_f3000_GuardaEncuesta($aParametros);
		<?php
		}
		?>
		}

		function muestramensajes(tipo, texto) {
			htmlToast.className = htmlToast.className.replace(/bg-\w+/g, 'bg-' + tipo);
			document.getElementsByClassName('toast-body')[0].innerHTML = texto;
			objToast.show();
		}

		function muestracomplemento(elemento, valor) {
			sDisplay = 'none';
			if (valor > 0) {
				sDisplay = 'block';
			}
			document.getElementById('p_' + elemento).style.display = sDisplay;
		}
	</script>
</head>

<body>

	<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
		<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
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
							Encuesta
						</a>
					</li>
				</ul>
			</nav>
			<!-- end menu -->

			<hr />

			<article class="article">
				<h2>
					<span><i class="fa fa-list"></i> <?php echo $ETI['titulo_encuesta']; ?></span>
				</h2>
				<div class="article__area row">
					<!-- form area -->
					<div class="col-12">

						<?php
						$sStyleFormCodigo = 'style="display:none;"';
						$sStyleFormEncuesta = 'style="display:none;"';
						if ($sError == '') {
							if (isset($saiuNNid)) {
								$sStyleFormEncuesta = 'style="display:block;"';
							} else {
								$sStyleFormCodigo = 'style="display:block;"';
							}
						} else {
						?>
							<div class="alert alert-danger" role="alert"><strong>No se ha encontrado la encuesta</strong></div>
						<?php
						}
						?>
						<div id="div_saiu05formcodigo" class="shadow p-3 mb-5 bg-white rounded text-center" <?php echo $sStyleFormCodigo; ?>>
							<form id="frmcodigo" name="frmcodigo" method="post" action="" autocomplete="off">
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="saui05numref" class="text-center"><?php echo $ETI['ing_campo'] . $ETI['saiu05codigo']; ?></label>
										<input id="saui05numref" name="saui05numref" class="form-control form-control-lg text-center" type="text" value="<?php echo $_REQUEST['saui05numref']; ?>" maxlength="20" placeholder="<?php echo $ETI['digite']; ?>" />
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-aurea px-4 float-right" title="<?php echo $ETI['bt_enviar']; ?>" value="<?php echo $ETI['bt_enviar']; ?>" onclick="enviacodigo()">
									</div>
								</div>
							</form>
						</div>
						<?php
						?>
						<hr>
						<div id="div_saiu<?php echo $saiuid; ?>formencuesta" <?php echo $sStyleFormEncuesta; ?>>
							<?php echo $html_encuesta; ?>
						</div>
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
			<p>Universidad Nacional Abierta y a Distancia UNAD de Colombia - &copy; Copyright UNAD <?php echo date('Y'); ?></p>
		</footer>
		<!-- end footer -->
	</main>
	<!-- end MAIN -->
	<script>
		let htmlToast = document.getElementById('liveToast');
		let objToast = new bootstrap.Toast(htmlToast);
		$(document).ready(() => {
			$('select').addClass("form-control");
		});
	</script>
</body>

</html>
