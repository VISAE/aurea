<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo acercade.php.
 * Modulo 500 .
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 27 de junio de 2025
 */
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['deb_doc']) != 0) {
	if (trim($_REQUEST['deb_doc']) != '') {
		$bDebug = true;
	}
} else {
	$_REQUEST['deb_doc'] = '';
}
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
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_sesion2.php';
if (isset($APP->https) == 0) {
	$APP->https = 0;
}
if ($APP->https == 2) {
	$bObliga = false;
	if (isset($_SERVER['HTTPS']) == 0) {
		$bObliga = true;
	} else {
		if ($_SERVER['HTTPS'] != 'on') {
			$bObliga = true;
		}
	}
	if ($bObliga) {
		$pageURL = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header('Location:' . $pageURL);
		die();
	}
}
$bPeticionXAJAX = false;
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libcomp.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
$iConsecutivoMenu = 1;
$iMinVerDB = 8727;
$iCodModulo = 1200;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
require $mensajes_todas;
$mensajes_1200 = 'lg/lg_1200_' . $sIdioma . '.php';
if (!file_exists($mensajes_1200)) {
	$mensajes_1200 = 'lg/lg_1200_es.php';
}
require $mensajes_1200;

$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
// --- Variables para la forma
$bBloqueTitulo = true;
$bDebugMenu = false;
$iPiel = iDefinirPiel($APP, 2);
$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
$sTituloModulo = $ETI['titulo_1200acerca'];
$idTercero = $_SESSION['unad_id_tercero'];
//$idEntidad = Traer_Entidad();
$bMueveScroll = false;
$iSector = 1;
if (isset($_REQUEST['f']) == 0) {
	$_REQUEST['f'] = 0;
}
$_REQUEST['f'] = numeros_validar($_REQUEST['f']);
$bDetalleTotal = false;
if ($_REQUEST['f'] == 1) {
	$bDetalleTotal = true;
}
// -- Manifest del componente
$sManifestError = '';
$sManifestPath = __DIR__ . '/manifest.json';
$sManifestComponentName = $ETI['titulo_1200'];
$sManifestComponentVersion = '1.0.0';
$sManifestDescription = $ETI['titulo_1200'];
$aManifestComponentHistory = array();
$aManifestModules = array();
$aManifestModuleErrors = array();
if (file_exists($sManifestPath)) {
	$sManifestJson = @file_get_contents($sManifestPath);
	if ($sManifestJson !== false) {
		$aManifest = json_decode($sManifestJson, true);
		if (json_last_error() === JSON_ERROR_NONE && isset($aManifest['componente']) && is_array($aManifest['componente'])) {
			$aComponente = $aManifest['componente'];
			if (isset($aComponente['name'])) {
				$sManifestComponentName = $aComponente['name'];
			}
			if (isset($aComponente['version'])) {
				$sManifestComponentVersion = $aComponente['version'];
			}
			if (isset($aComponente['description'])) {
				$sManifestDescription = $aComponente['description'];
			}
			if (isset($aComponente['version-history']) && is_array($aComponente['version-history'])) {
				$aManifestComponentHistory = $aComponente['version-history'];
			}
			if (isset($aComponente['modules']) && is_array($aComponente['modules'])) {
				foreach ($aComponente['modules'] as $vModulo) {
					if (is_string($vModulo)) {
						$sModuloPath = dirname($sManifestPath) . '/json/' . $vModulo;
						if (!file_exists($sModuloPath)) {
							$aManifestModuleErrors[] = 'No se encontró el archivo ' . $vModulo . '.';
							continue;
						}
						$sModuloJson = @file_get_contents($sModuloPath);
						if ($sModuloJson === false) {
							$aManifestModuleErrors[] = 'No fue posible leer el archivo ' . $vModulo . '.';
							continue;
						}
						$aModuloData = json_decode($sModuloJson, true);
						if (json_last_error() !== JSON_ERROR_NONE) {
							$aManifestModuleErrors[] = 'No fue posible interpretar el archivo ' . $vModulo . '.';
							continue;
						}
						if (isset($aModuloData['module']) && is_array($aModuloData['module'])) {
							$aModulo = $aModuloData['module'];
						} else {
							$aModulo = $aModuloData;
						}
						if (!isset($aModulo['code'])) {
							$aModulo['code'] = $vModulo;
						}
						$aManifestModules[] = $aModulo;
					} elseif (is_array($vModulo)) {
						if (!isset($vModulo['code']) && isset($vModulo['_codigo'])) {
							$vModulo['code'] = $vModulo['_codigo'];
						}
						$aManifestModules[] = $vModulo;
					}
				}
			}
			if (empty($aManifestModules)) {
				foreach ($aComponente as $sClave => $aModulo) {
					if (!is_array($aModulo)) {
						continue;
					}
					if (!isset($aModulo['entry'])) {
						continue;
					}
					$aModulo['code'] = $sClave;
					$aManifestModules[] = $aModulo;
				}
			}
		} else {
			$sManifestError = 'No fue posible interpretar el archivo manifest.json.';
		}
	} else {
		$sManifestError = 'No fue posible leer el archivo manifest.json.';
	}
} else {
	$sManifestError = 'No se encontró el archivo manifest.json.';
}

switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('./', $sTituloApp),
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', 0)', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		forma_cabeceraV4b($aRutas, $aBotones, true, $iSector);
		echo $et_menu;
		forma_mitad($idTercero);
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2_2024.php';
		forma_cabeceraV3($xajax, $sTituloModulo);
		echo $et_menu;
		forma_mitad();
		break;
}
?>
<script language="javascript">
</script>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="f" name="f" type="hidden" value="<?php echo $_REQUEST['f']; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<h1><?php echo htmlentities($sManifestDescription); ?> Ver. <?php echo htmlentities($sManifestComponentVersion); ?></h1>
</div>
<?php
	//Termina el bloque titulo
}
?>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($bBloqueTitulo) {
?>
<?php
} else {
?>
<h1><?php echo htmlentities($sManifestDescription); ?> Ver. <?php echo htmlentities($sManifestComponentVersion); ?></h1>
<?php
}
?>
<h2>&copy; Universidad Nacional Abierta y a Distancia - UNAD 2026</h2>
<b>Rector :</b> Ph.D Jaime Alberto Leal Afanador<br />
<a href="http://www.unad.edu.co" target="_blank">http://www.unad.edu.co</a>
<h3>Vicerrectoria de Innovaci&oacute;n y Emprendimiento</h3>
<b>Vicerrector :</b> <br />
Ingeniero Andres Ernesto Salinas Duarte<br />
<b>Factoria de Software</b> <br />
<b>Dise&ntilde;o y arquitectura</b> <br />
Ingeniero Angel Mauro Avellaneda Barreto<br />
<a href="http://www.avellaneda.co" target="_blank"><img src="img/logoMA.gif" alt="Mauro Avellaneda" /></a><br />
<b>Maquetado</b><br />
Ingeniero Edwin Leandro Moreno Guerra<br />
<b>Para soporte t&eacute;cnico :</b> soporte.campus@unad.edu.co<br />
<?php
if ($sManifestError != '') {
?>
<div class="GrupoCamposAyuda">
<?php echo htmlentities($sManifestError); ?>
</div>
<div class="salto1px"></div>
<?php
} else {
	if ($sManifestDescription != '') {
?>
<p><?php echo htmlentities($sManifestDescription); ?></p>
<?php
	}
	if (!empty($aManifestComponentHistory)) {
?>
<div class="GrupoCampos">
<h3>Historial del componente</h3>
<ul>
<?php
		foreach ($aManifestComponentHistory as $aHistorial) {
			$sVer = isset($aHistorial['version']) ? $aHistorial['version'] : 'N/D';
			$sFecha = isset($aHistorial['date']) ? $aHistorial['date'] : '';
			$sNotas = isset($aHistorial['notes']) ? $aHistorial['notes'] : '';
?>
<li><b>v<?php echo htmlentities($sVer); ?></b> <?php echo htmlentities($sFecha); ?> - <?php echo htmlentities($sNotas); ?></li>
<?php
		}
?>
</ul>
</div>
<?php
	}
	if (!empty($aManifestModules)) {
?>
<div class="GrupoCampos">
<h3>Versionamiento por m&oacute;dulo</h3>
<div class="table-responsive">
<table class="tablaapp" cellspacing="2" cellpadding="0">
<thead class="fondoazul">
<tr>
<td><b>C&oacute;digo</b></td>
<td><b>Nombre</b></td>
<td><b>Versi&oacute;n</b></td>
<td><b>Entrada</b></td>
<td><b>&Uacute;ltima actualizaci&oacute;n</b></td>
<td><b>Notas</b></td>
</tr>
</thead>
<tbody>
<?php
		foreach ($aManifestModules as $aModulo) {
			$sCodigo = isset($aModulo['code']) ? $aModulo['code'] : '';
			$sNombre = isset($aModulo['name']) ? $aModulo['name'] : '';
			$sVersion = isset($aModulo['version']) ? $aModulo['version'] : '';
			$sEntrada = isset($aModulo['entry']) ? $aModulo['entry'] : '';
			$sUltimaFecha = isset($aModulo['lastUpdate']) ? $aModulo['lastUpdate'] : '';
			$sNotasModulo = '';
			if (isset($aModulo['version-history']) && is_array($aModulo['version-history']) && count($aModulo['version-history']) > 0) {
				$aHistorialModulo = $aModulo['version-history'];
				$aHistOrdenado = $aHistorialModulo;
				usort($aHistOrdenado, function ($a, $b) {
					$sFechaA = isset($a['date']) ? $a['date'] : '';
					$sFechaB = isset($b['date']) ? $b['date'] : '';
					return strcmp($sFechaB, $sFechaA);
				});
				if ($bDetalleTotal) {
					$aNotasDetalle = array();
					foreach ($aHistOrdenado as $aHist) {
						$sFechaNota = isset($aHist['date']) && $aHist['date'] != '' ? $aHist['date'] : 'N/D';
						$sTextoNota = isset($aHist['notes']) ? $aHist['notes'] : '';
						if ($sTextoNota == '') {
							continue;
						}
						$aNotasDetalle[] = $sFechaNota . ' - ' . $sTextoNota;
					}
					$sNotasModulo = implode("\n", $aNotasDetalle);
				} else {
					$aRegistroNotas = $aHistOrdenado[0];
					if (isset($aRegistroNotas['notes'])) {
						$sNotasModulo = $aRegistroNotas['notes'];
					}
				}
				if (!empty($aHistOrdenado)) {
					$sUltimaFecha = isset($aHistOrdenado[0]['date']) && $aHistOrdenado[0]['date'] != '' ? $aHistOrdenado[0]['date'] : $sUltimaFecha;
				}
			}
?>
<tr>
<td><?php echo htmlentities($sCodigo); ?></td>
<td><?php echo htmlentities($sNombre); ?></td>
<td><?php echo htmlentities($sVersion); ?></td>
<td><?php echo htmlentities($sEntrada); ?></td>
<td><?php echo htmlentities($sUltimaFecha); ?></td>
<td><?php echo nl2br(htmlentities($sNotasModulo)); ?></td>
</tr>
<?php
		}
?>
</tbody>
</table>
</div>
</div>
<?php
	}
}
?>
</div>
</div>
</div>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
</div>
<?php
forma_piedepagina();

