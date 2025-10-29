<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.3 martes, 18 de abril de 2023
*/
$dm_usergent = array(
	'AvantGo' => 'AvantGo',
	'BlackBerry' => 'BlackBerry',
	'iPhone' => 'iPhone',
	'Minimo' => 'Minimo',
	'Mobiles' => 'Mobile',
	'Motorola' => 'mot-',
	'MozillaMobile' => 'Mobile',
	'NetFront' => 'NetFront',
	'Nokia' => 'Nokia',
	'OperaMini' => 'Minimo',
	'Palm' => 'Palm',
	'PDA' => 'PDA',
	'PIE4' => 'compatible; MSIE 4.01; Windows CE; PPC; 240x320',
	'PIE4_Smartphone' => 'compatible; MSIE 4.01; Windows CE; Smartphone;',
	'PIE6' => 'compatible; MSIE 6.0; Windows CE;',
	'PPC' => 'PPC',
	'Plucker' => 'Plucker',
	'Smartphone' => 'Smartphone',
	'SonyEricsson' => 'SonyEricsson',
	'WindowsMobile' => 'Windows CE'
);
function obtenerNavegador($useragents, $useragent)
{
	foreach ($useragents as $nav => $ua) {
		if (strstr($useragent, $ua) != false) {
			return $nav;
		}
	}
	return 'Desconocido';
}
function iDefinirPiel($APP, $iMaxima = 2)
{
	$iPiel = 1;
	if (isset($APP->piel) != 0) {
		$iPiel = $APP->piel;
	}
	if ($iPiel > $iMaxima) {
		$iPiel = $iMaxima;
	}
	if (isset($_SESSION['u_visual']) == 0) {
		$_SESSION['u_visual'] = 0;
	}
	if ($_SESSION['u_visual'] != 0) {
		$iPiel = $_SESSION['u_visual'];
	}
	return $iPiel;
}
function iDefinirIdioma($sIdioma)
{
	switch ($sIdioma) {
		case 'en':
		case 'es':
		case 'pt':
			$_SESSION['unad_idioma'] = $sIdioma;
			break;
		default:
			$_SESSION['unad_idioma'] = 'es';
			break;
	}
}
/**
 * Define la clase de la tabla.
 *
 * Esta función permite definir la clase CSS que se aplicará a una tabla
 * según la piel seleccionada.
 *
 * @param int $iNivel La jerarquia
 * @param int $iVariante Cambios de color
 * @return string La clase de la tabla.
 */
function DefinirClaseTabla($APP, $iNivel = 1, $iVariante = 0)
{
	$sClase = '';
	switch ($APP->piel) {
		case 2:
			switch ($iNivel) {
				case 1:
					$sClase = 'table--primary'; // Padre
					break;
				case 2:
					$sClase = 'table--secondary'; // Hija
					break;
			}
			break;
		default:
			$sClase = 'tablaapp';
			break;
	}
	return $sClase;
}
function iminutoavance()
{
	return (date('W') * 1440) + (date('H') * 60) + date('i');
}
if (isset($_SERVER['HTTP_USER_AGENT']) != 0) {
	if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
		@session_cache_limiter('public');
	}
}
@session_start();
if (isset($_SESSION['u_ultimominuto']) == 0) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
}
if (isset($_SESSION['unad_id_tercero']) == 0) {
	$_SESSION['unad_id_tercero'] = 0;
}
if ($_SESSION['unad_id_tercero'] != 0) {
	//Esto hace que se salga de la sesion si lleva mucho tiempo inactivo e intentan acceder a la pagina.
	$iavance = iminutoavance();
	if (isset($_SESSION['unad_tiempo_sesion']) == 0) {
		$_SESSION['unad_tiempo_sesion'] = 20;
	}
	if (isset($APP->tiempolimite) != 0) {
		if ($APP->tiempolimite > $_SESSION['unad_tiempo_sesion']) {
			$_SESSION['unad_tiempo_sesion'] = $APP->tiempolimite;
		}
	}
	$itiempomax = $_SESSION['unad_tiempo_sesion'];
	if ($iavance > ($_SESSION['u_ultimominuto'] + $itiempomax)) {
		header('Location:salir.php');
		die();
	}
}
if (isset($_SESSION['unad_sesion_id']) == 0) {
	$_SESSION['unad_sesion_id'] = 0;
}
if (isset($_SESSION['unad_idcentro']) == 0) {
	$_SESSION['unad_idcentro'] = 0;
}
if (isset($_SESSION['unad_centrocosto']) == 0) {
	$_SESSION['unad_centrocosto'] = 0;
}
if (isset($_SESSION['unad_tipodoctercero']) == 0) {
	$_SESSION['unad_tipodoctercero'] = 'CC';
}
if (isset($_SESSION['unad_doctercero']) == 0) {
	$_SESSION['unad_doctercero'] = '';
}
$idEntidad = 0;
if (isset($APP->entidad) != 0) {
	if ($APP->entidad == 1) {
		$idEntidad = 1;
	}
}
// Se replica la inicializacion en libaurea AUREA_Idioma($idEntidad)
switch ($idEntidad) {
	case 1: // Unad florida
		if (isset($_SESSION['unad_pais']) == 0) {
			$_SESSION['unad_pais'] = '001';
		}
		if (isset($_SESSION['unad_idioma']) == 0) {
			$_SESSION['unad_idioma'] = 'en';
		}
		break;
	default:
		if (isset($_SESSION['unad_pais']) == 0) {
			$_SESSION['unad_pais'] = '057';
		}
		if (isset($_SESSION['unad_idioma']) == 0) {
			$_SESSION['unad_idioma'] = 'es';
		}
		break;
}
// 2024-03-22 Se incluye switch para evitar que un usuario malintencionado cambie el valor de la variable
switch ($_SESSION['unad_idioma']) {
	case 'en':
	case 'es':
	case 'pt':
		break;
	default:
		$_SESSION['unad_idioma'] = 'es';
		break;
}
if (isset($_SESSION['cfg_movil']) == 0) {
	if (isset($_SERVER['HTTP_USER_AGENT']) != 0) {
		$navegador = obtenerNavegador($dm_usergent, $_SERVER['HTTP_USER_AGENT']);
	} else {
		$navegador = 'Desconocido';
	}
	if ($navegador != 'Desconocido') {
		$_SESSION['cfg_movil'] = 1;
	} else {
		$_SESSION['cfg_movil'] = 0;
	}
}
