<?php
/*  
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.mauroavellaneda.com
--- Modelo Version 0.1.0 martes, 28 de enero de 2014
Marzo 31 se agrega cadena_utf8
--- 31 de Octubre de 2019 - Se ajusta la funcion de fecha_esvalida
--- 10 de Septiembre de 2021 - Se ajusta la funcion fecha_ArmarNumero
*/
define('TONO_AMARILLO', '');
define('TONO_VERDE', '');
define('TONO_ROJO', '');

function archivo_eliminar($stabla, $scampoid, $scidorigen, $scidarchivo, $vrid, $objDB)
{
	$sSQL = 'SELECT ' . $scidorigen . ', ' . $scidarchivo . ' FROM ' . $stabla . ' WHERE ' . $scampoid . '=' . $vrid;
	$arbol = $objDB->ejecutasql($sSQL);
	if ($arbol != false) {
		if ($objDB->nf($arbol) > 0) {
			$fila = $objDB->sf($arbol);
			$dbo = $fila[0];
			$idar = $fila[1];
			$sSQL = 'UPDATE ' . $stabla . ' SET ' . $scidorigen . '=0, ' . $scidarchivo . '=0 WHERE ' . $scampoid . '=' . $vrid;
			$arbol = $objDB->ejecutasql($sSQL);
			if ($arbol != false) {
				if ($dbo == 0) {
					$objDBar = $objDB;
				} else {
					$objDBar = DBalterna_Traer($dbo, $objDB);
				}
				if ($objDBar != NULL) {
					$sSQL = 'DELETE FROM unad51archivos WHERE unad51id=' . $idar . ';';
					$arbol = $objDBar->ejecutasql($sSQL);
				}
			}
		}
	}
}
function archivos_Iguales($sRuta1, $sRuta2)
{
	$bSonIguales = false;
	$md51 = md5_file($sRuta1);
	$md52 = md5_file($sRuta2);
	if ($md51 != false) {
		if ($md51 == $md52) {
			$bSonIguales = true;
		}
	}
	return $bSonIguales;
}
function archivos_ImagenPropiedades($sRutaImagen)
{
	$sMime = '';
	$iAncho = 0;
	$iAlto = 0;
	$sError = '';
	if (!file_exists($sRutaImagen)) {
		$sError = 'No existe el archivo ' . $sRutaImagen;
	}
	if ($sError == '') {
		$tamanos = getimagesize($sRutaImagen);
		$iAncho = $tamanos[0];
		$iAlto = $tamanos[1];
		$sMime = image_type_to_mime_type($tamanos[2]);
	}
	return array($sMime, $iAncho, $iAlto, $sError);
}
function archivos_MaxSubida()
{
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	return $upload_mb;
}
function archivos_rutaservidor($sruta, $ssepara = '')
{
	$res = $sruta;
	if ($ssepara == '') {
		$ssepara = archivos_separador($sruta);
	}
	if (substr($sruta, strlen($sruta) - 1) != $ssepara) {
		$res = $sruta . $ssepara;
	}
	return $res;
}
function archivos_separador($sruta)
{
	$res = "\\";
	$pos = strpos($sruta, $res);
	if ($pos === false) {
		$res = '/';
	}
	return $res;
}
function archivos_Carpetas($idArchivo)
{
	$sFolder1 = '000';
	$sFolder2 = '000';
	if ($idArchivo > 999999) {
		$iMiles = (int)($idArchivo / 1000000);
		$sFolder1 = formato_anchofijo($iMiles, 3, '0', true);
		$idArchivo = $idArchivo - ($iMiles * 1000000);
	}
	if ($idArchivo > 999) {
		$iMiles = (int)($idArchivo / 1000);
		$sFolder2 = formato_anchofijo($iMiles, 3, '0', true);
		$idArchivo = $idArchivo - ($iMiles * 1000);
	}
	$sArchivo = formato_anchofijo($idArchivo, 3, '0', true) . '.dat';
	return array($sFolder1, $sFolder2, $sArchivo);
}
function archivos_Tamano($sRuta, $iFormato = 0)
{
	$iRes = 0;
	$sRes = '';
	if (file_exists($sRuta)) {
		$iRes = filesize($sRuta);
		if ($iRes > 1024) {
			$bRes = round($iRes / 1024, 2);
			if ($bRes > 1024) {
				$cRes = round($bRes / 1024, 2);
				if ($cRes > 1024) {
					$dRes = round($cRes / 1024, 2);
					$sRes = formato_numero($dRes, 2) . ' Gb';
				} else {
					$sRes = formato_numero($cRes, 2) . ' Mb';
				}
			} else {
				$sRes = formato_numero($bRes, 2) . ' Kb';
			}
		} else {
			$sRes = formato_numero($iRes) . ' b';
		}
	}
	return array($iRes, $sRes);
}
// -- Manejo de cadenas
function cadena_aletoria($iLargo = 8)
{
	$sPermitidos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$sRes = '';
	//Hacemos una cadena aleatoria segun la longitud que se quiera
	for ($i = 0; $i < $iLargo; $i++) {
		//obtenemos un caracter aleatorio escogido de la cadena de caracteres
		$sRes = $sRes . substr($sPermitidos, rand(0, 62), 1);
	}
	return $sRes;
}
// Abril 27 de 2023 - Se ajusta el modelo de codificacion para PHP 8
function cadena_codificar($sCadena)
{
	//return utf8_encode($sCadena);
	if (cadena_esISO($sCadena)) {
		return $sCadena;
	} else {
		//return $sCadena;
		$sResultado = iconv('UTF-8', 'ISO-8859-1', $sCadena);
		return $sResultado;
	}
}
function cadena_decodificar($sCadena)
{
	//return utf8_decode($sCadena);
	if (cadena_esISO($sCadena)) {
		return iconv('ISO-8859-1', 'UTF-8', $sCadena);
	} else {
		return $sCadena;
	}
}
function cadena_contiene($sBase, $sDato)
{
	$ipos = strpos($sBase, $sDato);
	if ($ipos == 0) {
		if (substr($sBase, 0, strlen($sDato)) != $sDato) {
			$ipos = -1;
		}
	}
	if ($ipos == -1) {
		return false;
	} else {
		return true;
	}
}
function cadena_esutf8($string)
{
	$sBase = substr($string, 0, 250);
	return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]
        | [\xC2-\xDF][\x80-\xBF]
        |  \xE0[\xA0-\xBF][\x80-\xBF]
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
        |  \xED[\x80-\x9F][\x80-\xBF]
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}
        | [\xF1-\xF3][\x80-\xBF]{3}
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}
    )*$%xs', $sBase);
}
function cadena_esISO($sCadena)
{
	return mb_detect_encoding($sCadena, "ISO-8859-1", true) == "ISO-8859-1" ? true : false;
}
function cadena_letras($semilla, $adicionales = '')
{
	$cf = '';
	$permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . $adicionales;
	$largo = strlen($semilla);
	for ($k = 0; $k < $largo; $k++) {
		$una = substr($semilla, $k, 1);
		$bEsTilde = true;
		switch ($una) {
			case 'á':
				$sMuestra = 'a';
				break;
			case 'é':
				$sMuestra = 'e';
				break;
			case 'í':
				$sMuestra = 'i';
				break;
			case 'ó':
				$sMuestra = 'o';
				break;
			case 'ú':
				$sMuestra = 'u';
				break;
			case 'Á':
				$sMuestra = 'A';
				break;
			case 'É':
				$sMuestra = 'E';
				break;
			case 'Í':
				$sMuestra = 'I';
				break;
			case 'Ó':
				$sMuestra = 'O';
				break;
			case 'Ú':
				$sMuestra = 'U';
				break;
			default:
				$bEsTilde = false;
				break;
		}
		if ($bEsTilde) {
			$cf = $cf . $sMuestra;
		} else {
			$lugar = strpos($permitidos, $una);
			if ($lugar === false) {
			} else {
				$sMuestra = substr($permitidos, $lugar, 1);
				$cf = $cf . $sMuestra;
			}
		}
	}
	return $cf;
}
function cadena_letrasynumeros($semilla, $adicionales = '')
{
	$cf = '';
	$permitidos = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890' . $adicionales;
	$largo = strlen($semilla);
	for ($k = 0; $k < $largo; $k++) {
		$una = substr($semilla, $k, 1);
		$lugar = strpos($permitidos, $una);
		if ($lugar === false) {
		} else {
			$cf = $cf . $una;
		}
	}
	return $cf;
}
function cadena_limpiar($semilla, $permitidos)
{
	$cf = '';
	if (strlen($permitidos) > 0) {
		$largo = strlen($semilla);
		for ($k = 0; $k < $largo; $k++) {
			$una = substr($semilla, $k, 1);
			$lugar = strpos($permitidos, $una);
			if ($lugar === false) {
			} else {
				$cf = $cf . $una;
			}
		}
	}
	return $cf;
}
function cadena_LimpiarNombreArchivo($semilla, $adicionales = '')
{
	$permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-' . $adicionales;
	$cf = '';
	if (strlen($permitidos) > 0) {
		$largo = strlen($semilla);
		for ($k = 0; $k < $largo; $k++) {
			$una = substr($semilla, $k, 1);
			switch ($una) {
				case ' ':
					$una = '_';
					break;
				case 'ñ':
					$una = 'n';
					break;
				case 'Ñ':
					$una = 'N';
					break;
			}
			$lugar = strpos($permitidos, $una);
			if ($lugar === false) {
			} else {
				$cf = $cf . $una;
			}
		}
	}
	return $cf;
}
function cadena_LetrasEspeciales($bSensibles = false)
{
	$sRes = '._-/*+=()%&$#[]{}@!|°,;:" ';
	if ($bSensibles) {
		$sRes = $sRes . '<>' . "'";
	}
	return $sRes;
}
// 18 de Abril de 2023 -- Cuando no queda de otra, toca quitar las tildes.
function cadena_LimpiarTildes($semilla, $adicionales = '', $sComodin = '?')
{
	$sEspeciales = cadena_LetrasEspeciales(true);
	$permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' . $sEspeciales . $adicionales;
	$cf = '';
	if (strlen($permitidos) > 0) {
		$largo = strlen($semilla);
		for ($k = 0; $k < $largo; $k++) {
			$una = substr($semilla, $k, 1);
			switch ($una) {
				case 'á':
					$una = 'a';
					break;
				case 'Á':
					$una = 'A';
					break;
				case 'é':
					$una = 'e';
					break;
				case 'É':
					$una = 'E';
					break;
				case 'í':
					$una = 'i';
					break;
				case 'Í':
					$una = 'I';
					break;
				case 'ó':
					$una = 'o';
					break;
				case 'Ó':
					$una = 'O';
					break;
				case 'ú':
					$una = 'u';
					break;
				case 'Ú':
					$una = 'U';
					break;
				case 'ñ':
					$una = 'n';
					break;
				case 'Ñ':
					$una = 'N';
					break;
				case '–': //U + 2013
					$una = '-';
					break;
				case "\r":
				case chr(13):
				case chr(7):
				case '	':
				case '
':
					$una = ' ';
					break;
				case "\n":
				case chr(10):
					$una = '';
					break;
			}
			if ($una != '') {
				$lugar = strpos($permitidos, $una);
				if ($lugar === false) {
					$cf = $cf . $sComodin;
				} else {
					$cf = $cf . $una;
				}
			}
		}
	}
	return $cf;
}
//Limpiar UTF8
function cadena_LimpiarEspecial($semilla, $adicionales = '', $sComodin = '?')
{
	return cadena_LimpiarXAJAX($semilla, $adicionales, $sComodin);
}
function cadena_LimpiarXAJAX($semilla, $adicionales = '', $sComodin = '?')
{
	$permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-/*+=()%&$#[]{}@!|°,;:" ' . $adicionales;
	$permitidos = $permitidos . 'áéíóúñüÁÉÍÓÚÑÜ';
	$cf = '';
	if (strlen($permitidos) > 0) {
		$largo = strlen($semilla);
		for ($k = 0; $k < $largo; $k++) {
			$una = substr($semilla, $k, 1);
			switch ($una) {
				case "\r":
				case chr(13):
				case chr(7):
				case '	':
				case '
':
					$una = ' ';
					break;
				case "\n":
				case chr(10):
					$una = '';
					break;
			}
			if ($una != '') {
				$lugar = strpos($permitidos, $una);
				if ($lugar === false) {
					$cf = $cf . $sComodin;
				} else {
					$cf = $cf . $una;
				}
			}
		}
	}
	return $cf;
}
//
function cadena_notildes($origen, $butf8 = false)
{
	$nuevo = $origen;
	/*
	if (cadena_esutf8($origen)){
		//$nuevo=utf8_decode($origen);
		}else{
		$nuevo=$origen;
		}
	*/
	$sT = array(
		'á', 'é', 'í', 'ó', 'ú', 'è', 'ì', 'ò', 'ñ', 'Ñ',
		'Á', 'É', 'Í', 'Ó', 'Ú', '¿', '¬', '°', 'Â', 'Ç',
		'©', '¡', 'ª', '­', '–', '™', 'ê', 'ã', 'ç', 'â',
		'õ', '“', '”', '´', 'ü', 'Ü', '∩', 'π', '^', '’'
	);
	$sH = array(
		'&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&egrave;', '&igrave;', '&ograve;', '&ntilde;', '&Ntilde;',
		'&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&iquest;', '&not;', '&deg;', '&Acirc;', '&Ccedil;',
		'&copy;', '&iexcl;', '&ordf;', '&shy;', '&ndash;', '&trade;', '&ecirc;', '&atilde;', '&ccedil;', '&acirc;',
		'&otilde;', '&ldquo;', '&rdquo;', '&acute;', '&uuml;', '&Uuml;', '&cap;', '&pi;', '&#94;', '&#8217;'
	);
	$iTotal = 39;
	for ($k = 0; $k <= $iTotal; $k++) {
		$nuevo = str_replace($sT[$k], $sH[$k], $nuevo);
	}
	//$sResultado = $nuevo;
	//Junio 5 de 2023 - hubo un problema con la db y lo que vamos a hacer es forzar en este lugar que no vayan tildes.
	$sResultado = cadena_LimpiarTildes($nuevo, ' ');
	return $sResultado;
}
//La inversa a cadena_notildes
function cadena_tildes($origen, $butf8 = false)
{
	$nuevo = $origen;
	$sH = array(
		'á', 'é', 'í', 'ó', 'ú', 'è', 'ì', 'ò', 'ñ', 'Ñ',
		'Á', 'É', 'Í', 'Ó', 'Ú', '¿', '¬', '°', 'Â', 'Ç',
		'©', '¡', 'ª', '­', '–', '™', 'ê', 'ã', 'ç', 'â',
		'õ', '“', '”', '´', 'ü', 'Ü', '∩', 'π', '^'
	);
	$sT = array(
		'&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&egrave;', '&igrave;', '&ograve;', '&ntilde;', '&Ntilde;',
		'&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&iquest;', '&not;', '&deg;', '&Acirc;', '&Ccedil;',
		'&copy;', '&iexcl;', '&ordf;', '&shy;', '&ndash;', '&trade;', '&ecirc;', '&atilde;', '&ccedil;', '&acirc;',
		'&otilde;', '&ldquo;', '&rdquo;', '&acute;', '&uuml;', '&Uuml;', '&cap;', '&pi;', '&#94;'
	);
	$iTotal = 38;
	for ($k = 0; $k <= $iTotal; $k++) {
		$nuevo = str_replace($sT[$k], $sH[$k], $nuevo);
	}
	return $nuevo;
}
function cadena_NoTildesJS($origen, $butf8 = false)
{
	$nuevo = $origen;
	$sT = array(
		'á', 'é', 'í', 'ó', 'ú', 'è', 'ì', 'ò', 'ñ', 'Ñ',
		'Á', 'É', 'Í', 'Ó', 'Ú', '¿', '¬', '°', 'Â', 'Ç',
		'©', '¡', 'ª', '­', '–', '™', 'ê', 'ã', 'ç', 'â',
		'õ', '“', '”', '´', 'ü', 'Ü'
	);
	/*
\u00e1 = á
\u00e9 = é
\u00ed = í
\u00f3 = ó
\u00fa = ú
\u00c1 = Á
\u00c9 = É
\u00cd = Í
\u00d3 = Ó
\u00da = Ú
\u00f1 = ñ
\u00d1 = Ñ 	*/
	$sH = array(
		'\u00e1', '\u00e9', '\u00ed', '\u00f3', '\u00fa', '&egrave;', '&igrave;', '&ograve;', '\u00f1', '\u00d1',
		'\u00c1', '\u00c9', '\u00cd', '\u00d3', '\u00da', '&iquest;', '&not;', '&deg;', '&Acirc;', '&Ccedil;',
		'&copy;', '&iexcl;', '&ordf;', '&shy;', '&ndash;', '&trade;', '&ecirc;', '&atilde;', '&ccedil;', '&acirc;',
		'&otilde;', '&ldquo;', '&rdquo;', '&acute;', '&uuml;', '&Uuml;'
	);
	$iTotal = 15;
	for ($k = 0; $k <= $iTotal; $k++) {
		$nuevo = str_replace($sT[$k], $sH[$k], $nuevo);
	}
	return $nuevo;
}
function cadena_numerosyletras($semilla, $adicionales = '')
{
	return cadena_letrasynumeros($semilla, $adicionales);
}
function cadena_partir($sbase, $sinicio, $sfin)
{
	$ssup = '';
	$smedio = '';
	$sinf = '';
	$ipos = strpos($sbase, $sinicio);
	if ($ipos == 0) {
		if (substr($sbase, 0, strlen($sinicio)) != $sinicio) {
			$ipos = -1;
		}
	}
	if ($ipos == -1) {
		$ssup = $sbase;
	} else {
		$ssup = substr($sbase, 0, $ipos);
		$scola = substr($sbase, $ipos + strlen($sinicio));
		if ($sfin == '') {
			$sinf = $scola;
		} else {
			$ipos = strpos($scola, $sfin);
			if ($ipos == 0) {
				if (substr($scola, 0, strlen($sfin)) != $sfin) {
					$ipos = -1;
				}
			}
			if ($ipos == -1) {
				$sinf = $scola;
			} else {
				$smedio = substr($scola, 0, $ipos);
				$sinf = substr($scola, $ipos + strlen($sfin));
			}
		}
		$smedio = ' ' . $smedio;
	}
	return array($ssup, $smedio, $sinf);
}
// Junio 30 de 2014 - Se crea para hacer reemplazos recurrentes.
function cadena_Reemplazar($sBase, $sBusca, $sCambia)
{
	$sRes = str_replace($sBusca, $sCambia, $sBase);
	while (substr_count($sRes, $sBusca) > 0) {
		$sRes = str_replace($sBusca, $sCambia, $sRes);
	}
	return $sRes;
}
function cadena_ResuelveParaHTML($sBase)
{
	$res = cadena_notildes($sBase);
	$res = str_replace("[[", "&lt;", $res);
	$res = str_replace("]]", "&gt;", $res);
	return $res;
}
function cadena_Validar($semilla, $bTolerante = false)
{
	$sSignos = '.,;()!¡$=+-_$?¿|°*[]{}~"@:%' . "'";
	$permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890ñáéíóúüÑÁÉÍÓÚÜ ' . $sSignos;
	if ($bTolerante) {
		$permitidos = $permitidos . '-<>/';
	}
	$cf = '';
	if (strlen($permitidos) > 0) {
		$largo = strlen($semilla);
		for ($k = 0; $k < $largo; $k++) {
			$una = substr($semilla, $k, 1);
			$lugar = strpos($permitidos, $una);
			if ($lugar === false) {
			} else {
				$cf = $cf . $una;
			}
		}
	}
	return $cf;
}
function cadena_utf8($cadena)
{
	if (cadena_esutf8($cadena)) {
		return $cadena;
	} else {
		return cadena_codificar($cadena);
	}
}
// -- Funciones de correo electrónico.
function correo_VerificarDireccion($email)
{
	list($res, $sDebug) = correo_VerificarV2($email);
	return $res;
}
function correo_VerificarV2($email, $bDebug = false)
{
	$mail_correcto = false;
	$sError = '';
	$sDebug = '';
	/*compruebo unas cosas primeras*/
	if ((strlen($email) >= 6) && (substr_count($email, "@") == 1) && (substr($email, 0, 1) != "@") && (substr($email, strlen($email) - 1, 1) != "@")) {
		if ((!strstr($email, "'")) && (!strstr($email, "\"")) && (!strstr($email, "\\")) && (!strstr($email, "\$")) && (!strstr($email, " "))) {
			/*miro si tiene caracter .*/
			if (substr_count($email, ".") >= 1) {
				/*obtengo la terminacion del dominio*/
				$term_dom = substr(strrchr($email, '.'), 1);
				/*compruebo que la terminación del dominio sea correcta*/
				if (strlen($term_dom) > 1 && strlen($term_dom) < 6 && (!strstr($term_dom, "@"))) {
					/*compruebo que lo de antes del dominio sea correcto*/
					$antes_dom = substr($email, 0, strlen($email) - strlen($term_dom) - 1);
					$caracter_ult = substr($antes_dom, strlen($antes_dom) - 1, 1);
					if ($caracter_ult != "@" && $caracter_ult != ".") {
						$mail_correcto = true;
					} else {
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Validar correo <b>' . $email . '</b> No pasa el quinto control<br>';
						}
					}
				} else {
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Validar correo <b>' . $email . '</b> No pasa el cuarto control [' . $term_dom . ']<br>';
					}
				}
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Validar correo <b>' . $email . '</b> No pasa el tercer control<br>';
				}
			}
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Validar correo <b>' . $email . '</b> No pasa el segundo control<br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Validar correo <b>' . $email . '</b> No pasa el primer control<br>';
		}
	}
	return array($mail_correcto, $sDebug);
}
//Este es un resago evolutimo del ERP
function dato_SistemaInstalado($idSistema, $objDB)
{
	$bRes = true;
	return $bRes;
}
// -- funciones con datos
function dato_spredet($numtabla, $objDB, $sconsulta = '')
{
	$res = '';
	$sSQL = '';
	if ($sconsulta == '') {
		switch ($numtabla) {
				//case 11:$sSQL='SELECT int11id FROM int11estadocivil WHERE int11predeterm="S"';break;
		}
	} else {
		$sSQL = $sconsulta;
	}
	if ($sSQL != '') {
		$tabla = $objDB->ejecutasql($sSQL);
		if ($tabla != false) {
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$res = $fila[0];
			}
		}
	}
	return $res;
}

function DBalterna_Traer($idAlterna, $objDB)
{
	$objalterna = NULL;
	if ($idAlterna == 0) {
		$objalterna = $objDB;
	} else {
		$sSQL = 'SELECT unad50server, unad50puerto, unad50usuario, unad50pwd, unad50db, unad50modelo FROM unad50dbalterna WHERE unad50id=' . $idAlterna . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$row = $objDB->sf($result);
			switch ($row['unad50modelo']) {
				case 'O': //ODBC
				case 'M': //MySQL
					$objalterna = new clsdbadmin($row['unad50server'], $row['unad50usuario'], $row['unad50pwd'], $row['unad50db'], $row['unad50modelo']);
					if ($row['unad50puerto'] != '') {
						$objalterna->dbPuerto = $row['unad50puerto'];
					}
					$objalterna->conectar();
					break;
					break;
				case 'D': //Directorio
				case 'S': //SFTP
					$objalterna = $objDB;
					break;
			}
		}
	}
	return $objalterna;
}
//Modulo de tareas
//Termina el modulo de tareas
function f00_Leer($sOpcion, $objDB, $sPredet = '')
{
	$sRes = $sPredet;
	$sSQL = 'SELECT unad00valor FROM unad00config WHERE unad00codigo="' . $sOpcion . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sRes = $fila['unad00valor'];
	}
	return $sRes;
}
// -- Funciones para manejo de fechas
function fecha_adate($sFecha)
{
	$fecha = explode("/", $sFecha);
	$nueva = mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]);
	$sfinal = date("d/m/Y", $nueva);
	return $sfinal;
}
function fecha_agno()
{
	return date('Y');
}
function fecha_AgnoMes()
{
	$iMes = date('Ym');
	if ($iMes == 201811) {
		return 2018;
	} else {
		return date('Ym');
	}
}
function fecha_armar($idia = '', $imes = '', $iagno = '')
{
	$res = '';
	if ($idia == '') {
		$idia = date('j');
	} else {
		$idia = (int)$idia;
	}
	if ($imes == '') {
		$imes = date('n');
	} else {
		$imes = (int)$imes;
	}
	if ($iagno == '') {
		$iagno = date('Y');
	}
	if ($idia < 10) {
		$res = '0';
	}
	$res = $res . $idia . '/';
	if ($imes < 10) {
		$res = $res . '0';
	}
	$res = $res . $imes . '/' . $iagno;
	return $res;
}
function fecha_ArmarAgnoMes($iagno = '', $imes = '')
{
	$res = '';
	if ($imes == '') {
		$imes = date('n');
	} else {
		$imes = (int)$imes;
	}
	if ($iagno == '') {
		$iagno = date('Y');
	}
	if ($imes < 10) {
		$res = $res . '0';
	}
	$res = $iagno . $res . $imes;
	return $res;
}
function fecha_ArmarNumero($idia = '', $imes = '', $iagno = '', $bEstricto = false)
{
	$res = '';
	$idia = (int)($idia);
	$imes = (int)($imes);
	$iagno = (int)($iagno);
	if ($idia != 0) {
		if ($idia > 31) {
			$idia = 0;
		}
		if ($idia < 1) {
			$idia = 0;
		}
	}
	if ($imes != 0) {
		if ($imes > 12) {
			$imes = 0;
		}
		if ($imes < 1) {
			$imes = 0;
		}
	}
	if ($iagno != 0) {
		if ($iagno > 3000) {
			$iagno = 0;
		}
		if ($iagno < 1) {
			$iagno = 0;
		}
	}
	if (!$bEstricto) {
		if ($idia == 0) {
			$idia = date('d');
		}
		if ($imes == 0) {
			$imes = date('m');
		}
		if ($iagno == 0) {
			$iagno = date('Y');
		}
	}
	if (checkdate($imes, $idia, $iagno)) {
		return ((int)$iagno * 10000) + ((int)$imes * 100) + (int)$idia;
	} else {
		return 0;
	}
}
function fecha_desdenumero($iNumero, $sVacio = '00/00/0000')
{
	$res = $sVacio;
	if ($iNumero === 0) {
		return $res;
	}
	list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iNumero);
	if ($iAgno > 0) {
		$res = fecha_armar($iDia, $iMes, $iAgno);
	}
	return $res;
}
function fecha_Dividir($sFecha, $bConCero = false)
{
	$iDia = 0;
	$iMes = 0;
	$iAgno = 0;
	if (fecha_esvalida($sFecha)) {
		$aFecha = explode("/", $sFecha);
		$iDia = $aFecha[0];
		$iMes = $aFecha[1];
		$iAgno = $aFecha[2];
	}
	if ($bConCero) {
		if ($iDia < 10) {
			$iDia = '0' . $iDia;
		}
		if ($iMes < 10) {
			$iMes = '0' . $iMes;
		}
		if ($iAgno == 0) {
			$iAgno = '0000';
		} else {
			if ($iAgno < 1000) {
				$sAgno = '0' . $iAgno;
				if ($iAgno < 100) {
					$sAgno = '00' . $iAgno;
					if ($iAgno < 10) {
						$sAgno = '000' . $iAgno;
					}
				}
				$iAgno = $sAgno;
			}
		}
	}
	return array($iDia, $iMes, $iAgno);
}
function fecha_DividirNumero($sNumero, $bConCero = false)
{
	$iDia = 0;
	$iMes = 0;
	$iAgno = 0;
	$iNumero = numeros_validar($sNumero);
	if ($iNumero == '') {
		$iNumero = 0;
	}
	if ($iNumero > 0) {
		$iAgno = (int)($iNumero / 10000);
		if ($iAgno > 0) {
			$iNumero = $iNumero - ($iAgno * 10000);
			$iMes = (int)($iNumero / 100);
			$iDia = $iNumero - ($iMes * 100);
		}
	}
	if ($bConCero) {
		if ($iDia < 10) {
			$iDia = '0' . $iDia;
		}
		if ($iMes < 10) {
			$iMes = '0' . $iMes;
		}
		if ($iAgno == 0) {
			$iAgno = '0000';
		} else {
			if ($iAgno < 1000) {
				$sAgno = '0' . $iAgno;
				if ($iAgno < 100) {
					$sAgno = '00' . $iAgno;
					if ($iAgno < 10) {
						$sAgno = '000' . $iAgno;
					}
				}
				$iAgno = $sAgno;
			}
		}
	}
	return array($iDia, $iMes, $iAgno);
}
function fecha_mmddaaaa($sBase)
{
	$res = substr($sBase, 3, 2) . '/' . substr($sBase, 0, 2) . '/' . substr($sBase, 6);
	return $res;
}
function fecha_ddmmmyyyy($sBase)
{
	$mes = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
	$res = substr($sBase, 0, 3) . $mes[(int)substr($sBase, 3, 2)] . substr($sBase, 5);
	return $res;
}
function fecha_dia()
{
	return date('d');
}
function fecha_diaFinMes($iAgno = '', $iMes = '')
{
	$iRes = 30;
	if ($iMes == '') {
		$iMes = date('n');
	}
	if ($iAgno == '') {
		$iAgno = date('Y');
	}
	$iAgnoSigue = $iAgno;
	$iMesSigue = $iMes + 1;
	if ($iMesSigue > 12) {
		$iMesSigue = 1;
		$iAgnoSigue = $iAgno + 1;
	}
	$nueva = mktime(0, 0, 0, $iMesSigue, 1, $iAgnoSigue) + 24 * 60 * 60 * (-1);
	$iRes = date('d', $nueva);
	return $iRes;
}
function fecha_dia_nombre($iDiaSem)
{
	$res = '{' . $iDiaSem . '}';
	$iData = (int)$iDiaSem;
	if (($iData > -1) && ($iData < 7)) {
		$sDias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
		$res = $sDias[$iData];
	}
	return $res;
}
function fecha_diasemana($sfecha)
{
	$res = -1;
	if (fecha_esvalida($sfecha)) {
		$fecha = explode("/", $sfecha);
		$res = date('w', mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]));
	}
	return $res;
}
function fecha_edad($sFechaIni, $sFechaActual = '')
{
	$iEdad = 0;
	$iMedida = 0;
	$bPasa = false;
	if ($sFechaActual == '') {
		$iDiaA = fecha_dia();
		$iMesA = fecha_mes();
		$iAgnoA = fecha_agno();
		$bPasa = true;
	} else {
		list($sError, $iDiaA, $iMesA, $iAgnoA) = fecha_Validar($sFechaActual);
		if ($sError == '') {
			$bPasa = true;
		}
	}
	if ($bPasa) {
		list($sError, $iDiaI, $iMesI, $iAgnoI) = fecha_Validar($sFechaIni);
		if ($sError != '') {
			$bPasa = false;
		}
	}
	if ($bPasa) {
		$annos = abs($iAgnoA - $iAgnoI);
		$meses = $iMesA - $iMesI;
		$dias = $iDiaA - $iDiaI;
		if ($meses < 0) {
			$annos--;
		} else {
			if (($meses == 0) && ($dias < 0)) {
				$annos--;
			}
		}
		$iEdad = $annos;
		$iMedida = 1;
		if ($iEdad == 0) {
			$meses = $iMesA - $iMesI;
			$dias = $iDiaA - $iDiaI;
			if ($meses < 0) {
				$meses = $meses + 12;
			}
			if ($dias < 0) {
				$meses--;
				$dias = $dias + 30;
			}
			if ($meses == 0) {
				$iEdad = $dias;
				$iMedida = 3;
			} else {
				$iEdad = $meses;
				$iMedida = 2;
			}
		}
	}
	return array($iEdad, $iMedida);
}
function fecha_EnNumero($sFecha)
{
	$iRes = -1;
	$fecha = explode('/', $sFecha);
	if (count($fecha) == 3) {
		$iRes = $fecha[0] + ($fecha[1] * 100) + ($fecha[2] * 10000);
	}
	return $iRes;
}
function fecha_EdadNombreTipo($iCantidad, $iTipo)
{
	$sRes = '&nbsp;';
	if ($iCantidad > 0) {
		$sRes = 'A&ntilde;os';
		if ($iCantidad == 1) {
			$sRes = 'A&ntilde;o';
		}
		switch ($iTipo) {
			case 2:
				$sRes = 'Meses';
				if ($iCantidad == 1) {
					$sRes = 'Mes';
				}
				break;
			case 3:
				$sRes = 'D&iacute;as';
				if ($iCantidad == 1) {
					$sRes = 'D&iacute;a';
				}
				break;
		}
	}
	return $sRes;
}
function fecha_esmenor($sfecha, $sfechatope)
{
	$bres = false;
	if (fecha_esvalida($sfecha)) {
		if (fecha_esvalida($sfechatope)) {
			$fecha = explode('/', $sfecha);
			$fecha2 = explode('/', $sfechatope);
			$f1 = mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]);
			$f2 = mktime(0, 0, 0, $fecha2[1], $fecha2[0], $fecha2[2]);
			$dias = ($f1 - $f2) / (60 * 60 * 24);
			if ($dias < 0) {
				$bres = true;
			}
		}
	}
	return $bres;
}
function fecha_esmenoroigualahoy($sFecha)
{
	$bres = false;
	if (fecha_esvalida($sFecha)) {
		$hoy = gregoriantojd(date("m"), date("d"), date("Y"));
		$fparam = gregoriantojd(substr($sFecha, 3, 2), substr($sFecha, 0, 2), substr($sFecha, 6, 4));
		if ($hoy >= $fparam) {
			$bres = true;
		}
	}
	return $bres;
}
function fecha_esmayor($sfecha, $sfechatope)
{
	$bres = false;
	if (fecha_esvalida($sfecha)) {
		if (fecha_esvalida($sfechatope)) {
			$fecha = explode("/", $sfecha);
			$fecha2 = explode("/", $sfechatope);
			$f1 = mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]);
			$f2 = mktime(0, 0, 0, $fecha2[1], $fecha2[0], $fecha2[2]);
			$dias = ($f2 - $f1) / (60 * 60 * 24);
			if ($dias < 0) {
				$bres = true;
			}
		}
	}
	return $bres;
}
function fecha_esvalida($sFecha)
{
	list($sError) = fecha_Validar($sFecha);
	if ($sError == '') {
		return true;
	} else {
		return false;
	}
}
function fecha_Validar($sFecha, $sFormato = 'dd/mm/YYYY')
{
	/* -------- Nota Curiosa --------
Esta es la primera funcion modificada por solicitud de las personas que aprendieron a usar la plataforma AUREA, 
estos fueron Saul Alexander Hernandez Albarración y Omar Augusto Bautista Mora el 31 de Octubre de 2019.
*/
	$sError = '';
	$iDia = 0;
	$iMes = 0;
	$iAgno = 0;
	$sSeparador = '/';
	$iPosA = 2;
	$iPosM = 1;
	$iPosD = 0;
	$iTamano = 10;
	switch ($sFormato) {
		case 'dd/mm/YYYY':
			break;
		case 'YYYY/mm/dd':
			$iPosA = 0;
			$iPosD = 2;
			break;
		case 'dd-mm-YYYY':
			$sSeparador = '-';
			break;
		case 'YYYY-mm-dd':
			$sSeparador = '-';
			$iPosA = 0;
			$iPosD = 2;
			break;
		default:
			$sError = 'No se reconoce el formato de fecha solicitado.';
			break;
	}
	if ($sError == '') {
		if (strlen($sFecha) != $iTamano) {
			$sError = 'La fecha no tiene el tamaño esperado.';
		}
	}
	if ($sError == '') {
		$aFecha = explode($sSeparador, $sFecha);
		if (count($aFecha) != 3) {
			$sError = 'La fecha no usa el separador ' . $sSeparador;
		}
	}
	if ($sError == '') {
		$iDia = $aFecha[$iPosD];
		$iMes = $aFecha[$iPosM];
		$iAgno = $aFecha[$iPosA];
		if (!checkdate($iMes, $iDia, $iAgno)) {
			$sError = 'Fecha incorrecta';
		}
	}
	return array($sError, $iDia, $iMes, $iAgno);
}
function fecha_hora()
{
	return date('H');
}
function fecha_hoy($separador = '/')
{
	return date('d' . $separador . 'm' . $separador . 'Y');
}
function fecha_mes()
{
	return date('m');
}
function fecha_mesNumDias($iMes, $iAgno)
{
	$iNumDiasMes = 31;
	switch ($iMes) {
		case 2:
			$iNumDiasMes = 28;
			if (checkdate($iMes, 29, $iAgno)) {
				$iNumDiasMes = 29;
			}
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			$iNumDiasMes = 30;
			break;
	}
	return $iNumDiasMes;
}
function fecha_mes_nombre($iMes, $sIdioma = 'es')
{
	$res = '{' . $iMes . '}';
	$iData = (int)$iMes;
	if (($iData > 0) && ($iData < 13)) {
		$smeses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$res = $smeses[$iData];
	}
	return $res;
}
function fecha_microtiempo($iFormato = 0, $bMostrarMemoria = false)
{
	$iBase = microtime(true);
	$iSegundos = floor($iBase);
	$iMili = floor(($iBase - $iSegundos) * 1000);
	$iMemoria = 0;
	$sUnidadMemoria = '';
	if ($bMostrarMemoria) {
		$iMemoria = memory_get_usage();
		$sUnidadMemoria = 'Bytes';
		if ($iMemoria > 1023.99) {
			$sUnidadMemoria = 'KB';
			$iMemoria = round($iMemoria / 1024, 2);
			if ($iMemoria > 1023.99) {
				$sUnidadMemoria = 'MB';
				$iMemoria = round($iMemoria / 1024, 2);
			}
		}
	}
	if ($iFormato == 0) {
		$sMili = ':' . $iMili;
		if ($iMili < 100) {
			if ($iMili < 10) {
				$sMili = ':00' . $iMili;
			} else {
				$sMili = ':0' . $iMili;
			}
		}
		$sMemoria = '';
		if ($bMostrarMemoria) {
			$sMemoria = ' [' . $iMemoria . ' ' . $sUnidadMemoria . '] ';
		}
		return date('H:i:s') . $sMili . $sMemoria;
	} else {
		$iHoras = date('H');
		$iMinutos = date('i');
		$iSegundos = date('s');
		return array($iHoras, $iMinutos, $iSegundos, $iMili, $iMemoria, $sUnidadMemoria);
	}
}
function fecha_minuto()
{
	return date('i');
}
function fecha_MinutoEnNumero()
{
	return fecha_MinutoMod();
}
function fecha_DiaMod()
{
	return (date('Y') * 10000) + (date('m') * 100) + date('d');
}
function fecha_MinutoMod()
{
	return (date('H') * 60) + date('i');
}
function fecha_segundo()
{
	return date('s');
}
function fecha_SegundoMod()
{
	return (date('H') * 60 * 60) + (date('i') * 60) + date('s');
}
// Devuelve la cantidad de tiempo que son una cantidad x de segundos
function fecha_TiempoDesdeSegundos($iSegundos)
{
	$sRes = '';
	if (is_numeric($iSegundos)) {
		$sHoras = '';
		$sMinutos = '';
		$sSeg = '';
		$iHoraEnSegundos = 60 * 60;
		if ($iSegundos > ($iHoraEnSegundos - 1)) {
			$iHoras = (int)($iSegundos / $iHoraEnSegundos);
			$iReduce = $iHoras * $iHoraEnSegundos;
			$iSegundos = $iSegundos - $iReduce;
			if ($iHoras == 1) {
				$sHoras = '1 Hora';
			} else {
				$sHoras = formato_numero($iHoras) . ' Horas';
			}
		}
		if ($iSegundos > 59) {
			$iMinutos = (int)($iSegundos / 60);
			$iReduce = $iMinutos * 60;
			$iSegundos = $iSegundos - $iReduce;
			if ($iMinutos == 1) {
				$sMinutos = ' 1 Minuto';
			} else {
				$sMinutos = ' ' . formato_numero($iMinutos) . ' Minutos';
			}
		}
		if ($iSegundos > 0) {
			if ($iSegundos == 1) {
				$sSeg = ' 1 Segundo';
			} else {
				$sSeg = ' ' . formato_numero($iSegundos) . ' Segundos';
			}
		}
		$sRes = $sHoras . $sMinutos . $sSeg;
	}
	return $sRes;
}
//
function fecha_numdiasentrefechas($sfechaini, $sfechafin)
{
	$fecha = explode("/", $sfechaini);
	$fecha2 = explode("/", $sfechafin);
	$nueva = (mktime(0, 0, 0, $fecha2[1], $fecha2[0], $fecha2[2]) / 86400) - (mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]) / 86400);
	return floor($nueva + (1 / 2));
}
function fecha_DiasEntreFechasDesdeNumero($iFechaIni, $iFechaFin)
{
	list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iFechaIni);
	list($iDiaF, $iMesF, $iAgnoF) = fecha_DividirNumero($iFechaFin);
	$nueva = (mktime(0, 0, 0, $iMesF, $iDiaF, $iAgnoF) / 86400) - (mktime(0, 0, 0, $iMes, $iDia, $iAgno) / 86400);
	return floor($nueva + (1 / 2));
}
function fecha_NumDiaSemana($iFecha)
{
	$res = -1;
	list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iFecha);
	if ($iDia != 0) {
		$res = date('w', mktime(0, 0, 0, $iMes, $iDia, $iAgno));
	}
	return $res;
}
function fecha_NumSumarDias($iFechaBase, $iDias)
{
	if ($iFechaBase == 0) {
		$iFechaBase = fecha_DiaMod();
	}
	$iFinal = $iFechaBase;
	if ($iDias != 0) {
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iFechaBase);
		$nueva = mktime(0, 0, 0, $iMes, $iDia, $iAgno) + 24 * 60 * 60 * $iDias;
		$iFinal = (date('Y', $nueva) * 10000) + (date('m', $nueva) * 100) + (date('d', $nueva) * 1);
	}
	return $iFinal;
}
function fecha_NumValido($iFecha)
{
	$res = false;
	list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iFecha);
	if ($iDia != 0) {
		$res = checkdate($iMes, $iDia, $iAgno);
	}
	return $res;
}
function fecha_sumardias($sfechaini, $idias)
{
	//Para numero es fecha_NumSumarDias
	if ($idias != 0) {
		$fecha = explode('/', $sfechaini);
		$nueva = mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]) + 24 * 60 * 60 * $idias;
		$sfinal = date('d/m/Y', $nueva);
	} else {
		$sfinal = $sfechaini;
	}
	return $sfinal;
}
function fecha_tiempoenminutos($sfechaini, $ihoraini, $iminutoini, $sfechafin, $ihorafin, $iminutofin)
{
	$res = 0;
	$res = (($ihorafin * 60) + $iminutofin) - (($ihoraini * 60) + $iminutoini);
	if ($sfechaini != $sfechafin) {
		$idias = idiasentrefechas($sfechaini, $sfechafin);
		$res = $res + ($idias * 1440);
	}
	return $res;
}
function fecha_yyyy_mm_dd($sBase, $separador = '/')
{
	$res = substr($sBase, 6, 4) . $separador . substr($sBase, 3, 2) . $separador . substr($sBase, 0, 2);
	return $res;
}

// -- Funciones para el formateo de datos
function formato_anchofijo($scadena, $ilargo, $sprevio = ' ', $bizquierda = true)
{
	$res = $scadena;
	while (strlen($res) < $ilargo) {
		if ($bizquierda) {
			$res = $sprevio . $res;
		} else {
			$res = $res . $sprevio;
		}
	}
	return $res;
}
function formato_CorreoOculto($sCorreo)
{
	$sRes = 'Direcci&oacute;n de correo incorrecta.';
	if (correo_VerificarDireccion($sCorreo)) {
		list($ssup, $smedio, $sinf) = cadena_partir($sCorreo, '@', '.');
		$iLargo = strlen($ssup);
		$sRes = substr($ssup, 0, 1);
		if ($iLargo < 6) {
			for ($k = 2; $k <= $iLargo; $k++) {
				$sRes = $sRes . '*';
			}
		} else {
			//si la cadena es larga, mostramos 3, ocultamos 3
			$iPaso = 0;
			$bMuestra = false;
			$sRes = substr($ssup, 0, 3);
			for ($k = 4; $k <= $iLargo; $k++) {
				$sLetra = '*';
				if ($bMuestra) {
					$sLetra = substr($ssup, $k - 1, 1);
				}
				$sRes = $sRes . $sLetra;
				$iPaso++;
				if ($iPaso > 2) {
					$iPaso = 0;
					$bMuestra = !$bMuestra;
				}
			}
		}
		$sRes = $sRes . '@' . trim($smedio) . '.' . $sinf;
	}
	return $sRes;
}
function formato_fechalarga($sFecha, $bConDia = false)
{
	$res = 'Fecha incorrecta {' . $sFecha . '}';
	if ($sFecha == '00/00/0000') {
		$res = 'Sin fecha';
		$sFecha = '';
	}
	if (strlen($sFecha) == 10) {
		$idia = (int)substr($sFecha, 0, 2);
		$imes = (int)substr($sFecha, 3, 2);
		$iagno = (int)substr($sFecha, 6, 4);
		if (checkdate($imes, $idia, $iagno)) {
			$res = $idia . ' de ';
			$res = $res . fecha_mes_nombre($imes);
			$res = $res . ' de ' . $iagno;
			if ($bConDia) {
				$res = fecha_dia_nombre(fecha_diasemana($sFecha)) . ' ' . $res;
			}
		}
	}
	return $res;
}
function formato_FechaLargaDesdeNumero($iFecha, $bConDia = false)
{
	$res = 'Fecha incorrecta {' . $iFecha . '}';
	$bPasa = true;
	if ($iFecha == 0) {
		$res = 'Sin fecha';
		$bPasa = false;
	}
	if ($iFecha < 10000) {
		$bPasa = false;
	}
	if ($iFecha > 99999999) {
		$bPasa = false;
	}
	if ($bPasa) {
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($iFecha);
		if (checkdate($iMes, $iDia, $iAgno)) {
			$res = $iDia . ' de ';
			$res = $res . fecha_mes_nombre($iMes);
			$res = $res . ' de ' . $iAgno;
			if ($bConDia) {
				$sFecha = fecha_armar($iDia, $iMes, $iAgno);
				$res = fecha_dia_nombre(fecha_diasemana($sFecha)) . ' ' . $res;
			}
		}
	}
	return $res;
}
function formato_hora($ivalor)
{
	$sfinal = (int)$ivalor;
	if ($sfinal < 10) {
		$sfinal = '0' . $sfinal;
	}
	return $sfinal;
}
function formato_horaminuto($ihora, $iminuto)
{
	return html_TablaHoraMin($ihora, $iminuto);
}
function formato_moneda($dValor, $iDecimales = 2, $sPref = '$ ')
{
	$dValor2 = numeros_validar($dValor, true, $iDecimales);
	if ($dValor2 == '') {
		$dValor2 = 0;
	}
	//$sfinal="$ ".number_format($ivalor2,$idecimales,",","."); // Posicion anterior
	$sFinal = $sPref . number_format($dValor2, $iDecimales, '.', ',');
	return $sFinal;
}
function formato_numero($dValor, $iDecimales = 0, $bVacio = false)
{
	$dValor2 = numeros_validar($dValor, true, $iDecimales);
	if ($dValor2 == '') {
		if ($bVacio) {
			return '';
		}
		$dValor2 = 0;
	}
	$sFinal = number_format($dValor2, $iDecimales, '.', ',');
	return $sFinal;
}

function formato_porcentaje($dValor, $iDecimales = 2, $bVacio = false)
{
	$dValor2 = 1 / 3;
	$dValor2 = numeros_validar($dValor, true, $iDecimales);
	if ($dValor2 == '') {
		if ($bVacio) {
			return '';
		}
		$dValor2 = 0;
	}
	$sFinal = number_format($dValor2, $iDecimales, '.', ',') . ' %';
	return $sFinal;
}

function formato_sino($svalor, $spred = 'No')
{
	switch ($svalor) {
		case '1':
		case 's':
		case 'S':
			$sfinal = 'Si';
			break;
		case '0':
		case 'n':
		case 'N':
			$sfinal = 'No';
			break;
		default:
			$sfinal = $spred;
	}
	return $sfinal;
}
function formato_UrlLimpia($sLink)
{
	$sRes = $sLink;
	if (cadena_contiene($sLink, '?')) {
		$iPos = strpos($sLink, '?');
		$sRes = substr($sLink, 0, $iPos);
	}
	return $sRes;
}
function formato_RutaBase($sRutaUri)
{
	$sRes = $sRutaUri;
	if (cadena_contiene($sRutaUri, '/')) {
		$iPos = strrpos($sRutaUri, '/') + 1;
		$sRes = substr($sRutaUri, 0, $iPos);
		//$sRes='--'.$iPos;
	}
	return $sRes;
}
// -- Funciones que devuelven codigo HTML
// -- Marzo 25 de 2015 Se crea para poder hacer el pintar llaves en forma sencilla
function html_DivTercero($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $idAccion = 0, $sPlaceHolder = '', $bConBotones = true)
{
	$sRes = '';
	if ($bOculto) {
		$sRes = html_oculto($sNombreCampo . '_td', $sTipoDoc) . ' ' . html_oculto($sNombreCampo . '_doc', $sDoc);
	} else {
		$sAdd = '';
		if ($sPlaceHolder != '') {
			$sAdd = ' placeholder="' . $sPlaceHolder . '"';
		}
		$sRes = html_tipodocV2($sNombreCampo . '_td', $sTipoDoc, "ter_muestra('" . $sNombreCampo . "', " . $idAccion . ")", false);
		$sRes = $sRes . '<input id="' . $sNombreCampo . '_doc" name="' . $sNombreCampo . '_doc" type="text" value="' . $sDoc . '" onchange="ter_muestra(\'' . $sNombreCampo . '\',' . $idAccion . ')" maxlength="20" onclick="revfoco(this);"' . $sAdd . '/>';
		$sRes = $sRes . '</label>';
		if ($bConBotones) {
			$sRes = $sRes . '<label class="Label30">';
			$sRes = $sRes . '<input type="button" name="b' . $sNombreCampo . '" value="Buscar" class="btMiniBuscar" onclick="ter_busca(\'' . $sNombreCampo . '\',' . $idAccion . ')" title="Buscar Tercero"/>';
		}
	}
	return '<label class="Label350">' . $sRes . '</label>';
}
function html_check($sNombre, $sEtiqueta, $valor, $bMarcado, $accion = '', $sSepara = '<br>')
{
	$sTemp = '';
	$sCheck = '';
	if ($accion != '') {
		$sTemp = ' onChange="' . $accion . '"';
	}
	if ($bMarcado != '') {
		$sCheck = ' checked="checked"';
	}
	$res = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="checkbox"' . $sCheck . ' value="' . $valor . '"' . $sTemp . ' />' . $sEtiqueta . $sSepara;
	return $res;
}
// -- Marzo 19 de 2014 Se agrega opcion de enviar multiples valores "vacio" separados por una barra |
function html_combo($nombre, $cod_, $nom_, $tab_, $cond_, $ord_, $valor_, $objDB, $accion = '', $bvacio = false, $etvacio = '', $vrvacio = '', $bConDebug = false)
{
	$sSQL = "SELECT " . $cod_ . ", " . $nom_ . " FROM " . $tab_;
	if ($cond_ != '') {
		$sSQL = $sSQL . " WHERE " . $cond_;
	}
	if ($ord_ != '') {
		$sSQL = $sSQL . " ORDER BY " . $ord_;
	}
	$result = $objDB->ejecutasql($sSQL);
	//echo $objDB->nf($result);
	$html_accion = '';
	if ($accion != '') {
		$html_accion = ' onChange="' . $accion . '"';
	}
	$sClaseI = '';
	//if ($valor_==''){$sClaseI=' style="color:#FF0000"';}
	//if ($html_accion==''){$html_accion=' onChange="combo_tono(this);"';}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '"' . $html_accion . $sClaseI . '>
';
	if ($bvacio) {
		$aEtiquetas = explode('|', $etvacio);
		$aValores = explode('|', $vrvacio);
		$iCant = count($aEtiquetas);
		for ($l = 1; $l <= $iCant; $l++) {
			$sEtiq = $aEtiquetas[$l - 1];
			$sVal = '';
			$ssel = '';
			$sClaseI = '';
			if (isset($aValores[$l - 1]) != 0) {
				$sVal = $aValores[$l - 1];
			}
			if ($sVal == $valor_) {
				$ssel = ' Selected';
			}
			if ($sVal == '') {
				$sClaseI = ' style="color:#FF0000"';
			}
			$res = $res . '<option value="' . $sVal . '"' . $ssel . $sClaseI . '>' . $sEtiq . '</option>
';
		}
	}
	if ($result != false) {
		//existe la posiblidad de que el campo nombre venga con la clausula AS por lo cual debe ser buscado.
		list($sIni, $sMed, $sFin) = cadena_partir($nom_, ' AS ', ' ');
		if (trim($sFin) == '') {
			list($sIni, $sMed, $sFin) = cadena_partir($nom_, ')AS ', ' ');
		}
		if (trim($sFin) != '') {
			$sNombreCampo = trim($sFin);
		} else {
			$sNombreCampo = trim($nom_);
		}
		while ($fila = $objDB->sf($result)) {
			$ssel = '';
			if ($fila[$cod_] == $valor_) {
				$ssel = ' Selected';
			}
			$sNombreOpcion = cadena_notildes($fila[$sNombreCampo]);
			//$sNombreOpcion = cadena_LimpiarTildes($sNombreOpcion, ' ');
			$res = $res . '<option value="' . $fila[$cod_] . '"' . $ssel . '>' . $sNombreOpcion . '</option>';
		}
	}
	$res = $res . '</select>';
	if ($result == false) {
		if ($bConDebug) {
			$res = $sSQL . '<br>' . $res;
		} else {
			$res = $res . '..<!-- 
' . $sSQL . '
 -->';
		}
	} else {
		if ($bConDebug) {
			$res = $sSQL . '<br>' . $res;
		}
	}
	return $res;
}
//combo aceptada.
function html_comboaceptada($nombre, $valor, $accion = '', $etaceptada = 'Aceptada', $etno = 'No Aceptada', $etpendiente = '')
{
	$stemp = '';
	$ssels = '';
	$sseln = '';
	$sselp = '';
	if ($accion != '') {
		$stemp = ' onChange="' . $accion . '"';
	}
	switch ($valor) {
		case 1:
			$ssels = ' Selected';
			break;
		case 0:
			$sseln = ' Selected';
			break;
		default:
			$sselp = ' Selected';
	}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '"' . $stemp . '>
<option value="-1"' . $sselp . '>' . $etpendiente . '</option>
<option value="1"' . $ssels . '>' . $etaceptada . '</option>
<option value="0"' . $sseln . '>' . $etno . '</option>
</select>
';
	return $res;
}
function html_combobma($nombre, $valor, $accion = '', $etb = 'Bajo', $etm = 'Medio', $eta = 'Alto', $etpendiente = '')
{
	$stemp = '';
	$ssela = '';
	$sselm = '';
	$sselb = '';
	$sselp = '';
	if ($accion != '') {
		$stemp = ' onChange="' . $accion . '"';
	}
	switch ($valor) {
		case 2:
			$ssela = ' Selected';
			break;
		case 1:
			$sselm = ' Selected';
			break;
		case 0:
			$sselb = ' Selected';
			break;
		default:
			$sselp = ' Selected';
	}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '"' . $stemp . '>
<option value="-1"' . $sselp . '>' . $etpendiente . '</option>
<option value="0"' . $sselb . '>' . $etb . '</option>
<option value="1"' . $sselm . '>' . $etm . '</option>
<option value="2"' . $ssela . '>' . $eta . '</option>
</select>
';
	return $res;
}
//manda un arreglo y lo convierte en combo.
function html_combo_arreglo($nombre, $valor, $contenido, $con_nulo = false, $accion = '', $sNombreNulo = '', $sValorNulo = '0')
{
	$saccion = '';
	$ssel = '';
	if ($accion != '') {
		$saccion = ' onChange="' . $accion . '"';
	}
	if (($con_nulo) && ($valor == "0")) {
		$ssel = " selected";
	}
	$res = '<select name="' . $nombre . '" id="' . $nombre . '"' . $saccion . '>';
	if ($con_nulo) {
		$res = $res . '<option value="' . $sValorNulo . '"' . $ssel . '>' . $sNombreNulo . '</option>';
	}
	$total = count($contenido);
	for ($k = 1; $k <= $total; $k++) {
		$ssel = '';
		if ($valor == $k) {
			$ssel = " selected";
		}
		$res = $res . '<option value="' . $k . '"' . $ssel . '>' . $contenido[$k] . '</option>';
	}
	$res = $res . '</select>';
	return $res;
}
function html_ComboDia($nombre, $valor, $con_nulo = false, $accion = '')
{
	$sAccion = '';
	if ($accion != '') {
		$sAccion = ' onchange="' . $accion . '"';
	}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '" class="cbo_dia"' . $sAccion . '>';
	if ($con_nulo) {
		$res = $res . '<option value="00"></option>';
	}
	for ($size = 1; $size <= 31; $size++) {
		$svr = $size;
		$ssel = '';
		if ($size == $valor) {
			$ssel = ' selected';
		}
		if ($size < 10) {
			$svr = '0' . $svr;
		}
		$res = $res . '<option' . $ssel . ' value="' . $svr . '">' . $size . '</option>';
	}
	$res = $res . '</select>';
	return $res;
}
function html_ComboMes($nombre, $valor, $con_nulo = false, $accion = '')
{
	$smeses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	$sAccion = '';
	if ($accion != '') {
		$sAccion = ' onchange="' . $accion . '"';
	}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '" class="cbo_mes"' . $sAccion . '>';
	if ($con_nulo) {
		$res = $res . '<option value="00"></option>';
	}
	for ($size = 1; $size <= 12; $size++) {
		$svr = $size;
		$ssel = '';
		if ($size == $valor) {
			$ssel = ' selected';
		}
		if ($size < 10) {
			$svr = '0' . $svr;
		}
		$res = $res . '<option' . $ssel . ' value="' . $svr . '">' . $smeses[$size] . '</option>';
	}
	$res = $res . '</select>';
	return $res;
}
function html_combo_nums($nombre, $iini, $ifin, $valor_, $accion = '', $bvacio = false, $etvacio = '', $vrvacio = '', $descendente = false)
{
	$html_accion = '';
	if ($accion != '') {
		$html_accion = ' onChange="' . $accion . '"';
	}
	$res = '<select name="' . $nombre . '" id="' . $nombre . '"' . $html_accion . '>';
	if ($bvacio) {
		$res = $res . '<option value="' . $vrvacio . '">' . $etvacio . '</option>';
	}
	if ($descendente) {
		for ($k = $ifin; $k >= $iini; $k--) {
			$ssel = '';
			if ($k == $valor_) {
				$ssel = ' Selected';
			}
			$res = $res . '<option value="' . $k . '"' . $ssel . '>' . $k . '</option>';
		}
	} else {
		for ($k = $iini; $k <= $ifin; $k++) {
			$ssel = '';
			if ($k == $valor_) {
				$ssel = ' Selected';
			}
			$res = $res . '<option value="' . $k . '"' . $ssel . '>' . $k . '</option>';
		}
	}
	$res = $res . '</select>';
	return $res;
}
function html_combo_opcion($opcion, $valor, $etiqueta)
{
	$ssel = '';
	if ($opcion == $valor) {
		$ssel = ' selected';
	}
	$res = '<option value="' . $opcion . '" ' . $ssel . '>' . $etiqueta . '</option>';
	return $res;
}

function html_fecha($nomcampo, $valor = '', $bvacio = false, $accion = '', $iagnoini = 0, $iagnofin = 0, $idiafijo = 0, $imesfijo = 0, $sClass = '')
{
	if (!$bvacio) {
		if (trim($valor) == '') {
			$valor = date('d/m/Y');
		}
	}
	$_dia = (int)numeros_validar(substr($valor, 0, 2));
	$_mes = (int)numeros_validar(substr($valor, 3, 2));
	$_agno = (int)numeros_validar(substr($valor, 6, 4));
	if ($iagnoini == 0) {
		$iagnoini = 2000;
	}
	if ($iagnofin == 0) {
		if ($_agno == 0) {
			$iagnofin = date("Y") + 5;
		} else {
			$iagnofin = $_agno + 5;
		}
	}
	$res = '';
	if ($idiafijo == 0) {
		$res = html_ComboDia($nomcampo . '_dia', $_dia, $bvacio, 'fecha_ajusta(\'' . $nomcampo . "','" . $accion . '\');');
	} else {
		$svr = $idiafijo;
		if ($idiafijo < 10) {
			$svr = '0' . $svr;
		}
		$res = $res . '<input id="' . $nomcampo . '_dia" name="' . $nomcampo . '_dia" type="hidden" value="' . $svr . '"/>&nbsp;<b>' . $svr . '/</b>';
	}
	$res = $res . ' ' . html_ComboMes($nomcampo . '_mes', $_mes, $bvacio, 'fecha_ajusta(' . "'" . $nomcampo . "','" . $accion . "'" . ')') . ' ';
	if ($iagnofin < $iagnoini) {
		$iagnofin = $iagnoini;
	}
	$bconagno = true;
	if ($iagnofin == $iagnoini) {
		$bconagno = false;
	}
	if ($bconagno) {
		$res = $res . '<select id="' . $nomcampo . '_agno" name="' . $nomcampo . '_agno" class="cbo_agno"' . $sClass . ' onchange="fecha_ajusta(' . "'" . $nomcampo . "','" . $accion . "'" . ')">';
		if ($bvacio) {
			$res = $res . '<option value="0000"></option>';
		}
		for ($size = $iagnofin; $size >= $iagnoini; $size--) {
			$ssel = '';
			if ($size == $_agno) {
				$ssel = ' selected';
			}
			$res = $res . '<option' . $ssel . ' value="' . $size . '">' . $size . '</option>';
		}
		$res = $res . '</select>';
	} else {
		$res = $res . '<input id="' . $nomcampo . '_agno" name="' . $nomcampo . '_agno" type="hidden" value="' . $iagnoini . '"/>&nbsp;<b>/' . $iagnoini . '</b>';
	}
	if (trim($valor) == '') {
		$valor = '00/00/0000';
	}
	$res = $res . '<input id="' . $nomcampo . '" name="' . $nomcampo . '" type="hidden" value="' . $valor . '"/>
	<input id="' . $nomcampo . '_prev" name="' . $nomcampo . '_prev" type="hidden" value="' . $valor . '"/>';
	return $res;
}
function html_HoraMin($sNomCampoHora, $iHora, $sNomCampoMin, $iMin, $bOculto = false, $iFormato = 1)
{
	$sVN = (int)$iHora;
	$iMin = (int)$iMin;
	$sVC = 'A';
	$res = '';
	$sAdd = '';
	if ($bOculto) {
		$res = '<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>
<input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="hidden" value="' . $iMin . '"/>';
		if (((int)$iHora + (int)$iMin) == 0) {
			$iFormato = 0;
		}
		if ($iFormato == 1) {
			$sVC = 'AM';
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'PM';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			$res = $res . '<b>' . formato_hora($sVN) . ':' . formato_hora($iMin) . ' ' . $sVC . '</b>';
		} else {
			if (((int)$iHora + (int)$iMin) == 0) {
				$res = $res . '&nbsp;';
			} else {
				$res = $res . '<b>' . formato_hora($iHora) . ':' . formato_hora($iMin) . '</b>';
			}
		}
	} else {
		$sSelA = ' Selected';
		$sSelP = '';
		if ($iFormato == 1) {
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'P';
				$sSelA = '';
				$sSelP = ' Selected';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			if (((int)$iHora + (int)$iMin) == 0) {
				$sVN = '';
			}
			$sAdd = '<label class="Label30"><select id="' . $sNomCampoHora . '_Ciclo" name="' . $sNomCampoHora . '_Ciclo" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');">
<option value="A"' . $sSelA . '>AM</option>
<option value="P"' . $sSelP . '>PM</option>
</select></label>
<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>';
			$res = '<label class="Label30"><input id="' . $sNomCampoHora . '_Num" name="' . $sNomCampoHora . '_Num" type="text" value="' . $sVN . '" class="dos" maxlength="2" placeholder="00" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');"/></label>';
		} else {
			//Hora militar
			$res = '<label class="Label30"><input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="text" value="' . $iHora . '" class="dos" maxlength="2" placeholder="00"/></label>';
		}
		$sValorMin = $iMin;
		if (((int)$iHora + (int)$iMin) == 0) {
			$sValorMin = '';
		}
		$res = $res . '<label style="width:7px;"><b>:</b></label><label style="width:35px;"><input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="text" value="' . $sValorMin . '" class="dos" maxlength="2" placeholder="00"/></label>' . $sAdd;
	}
	return $res;
}
function html_HoraMinV2($sNomCampoHora, $iHora, $sNomCampoMin, $iMin, $bOculto = false, $iFormato = 1)
{
	$sVN = (int)$iHora;
	$iMin = (int)$iMin;
	$sVC = 'A';
	$res = '';
	$sAdd = '';
	if ($bOculto) {
		$res = '<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>
<input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="hidden" value="' . $iMin . '"/>';
		if (((int)$iHora + (int)$iMin) == 0) {
			$iFormato = 0;
		}
		if ($iFormato == 1) {
			$sVC = 'AM';
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'PM';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			$res = $res . '<b>' . formato_hora($sVN) . ':' . formato_hora($iMin) . ' ' . $sVC . '</b>';
		} else {
			if (((int)$iHora + (int)$iMin) == 0) {
				$res = $res . '&nbsp;';
			} else {
				$res = $res . '<b>' . formato_hora($iHora) . ':' . formato_hora($iMin) . '</b>';
			}
		}
	} else {
		$sSelA = ' Selected';
		$sSelP = '';
		if ($iFormato == 1) {
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'P';
				$sSelA = '';
				$sSelP = ' Selected';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			if (((int)$iHora + (int)$iMin) == 0) {
				$sVN = '';
			}
			$sAdd = '<select class="w-8" id="' . $sNomCampoHora . '_Ciclo" name="' . $sNomCampoHora . '_Ciclo" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');">
<option value="A"' . $sSelA . '>AM</option>
<option value="P"' . $sSelP . '>PM</option>
</select>
<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>';
			$res = '<input id="' . $sNomCampoHora . '_Num" name="' . $sNomCampoHora . '_Num" type="text" value="' . $sVN . '" class="dos" maxlength="2" placeholder="00" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');"/>';
		} else {
			//Hora militar
			$res = '<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="text" value="' . $iHora . '" class="dos" maxlength="2" placeholder="00"/>';
		}
		$sValorMin = $iMin;
		if (((int)$iHora + (int)$iMin) == 0) {
			$sValorMin = '';
		}
		$res = $res . '<b>:</b><input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="text" value="' . $sValorMin . '" class="dos" maxlength="2" placeholder="00"/>' . $sAdd;
	}
	return $res;
}
function html_HoraMinSeg($sNomCampoHora, $iHora, $sNomCampoMin, $iMin, $sNomCampoSeg, $iSeg, $bOculto = false, $iFormato = 1)
{
	$sVN = (int)$iHora;
	$sVC = 'A';
	$res = '';
	$sAdd = '';
	if ($bOculto) {
		$res = '<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>
<input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="hidden" value="' . $iMin . '"/>
<input id="' . $sNomCampoSeg . '" name="' . $sNomCampoSeg . '" type="hidden" value="' . $iSeg . '"/>';
		if (($iHora + $iMin + $iSeg) == 0) {
			$iFormato = 0;
		}
		if ($iFormato == 1) {
			$sVC = 'AM';
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'PM';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			$res = $res . '<b>' . formato_hora($sVN) . ':' . formato_hora($iMin) . ':' . formato_hora($iSeg) . ' ' . $sVC . '</b>';
		} else {
			$res = $res . '<b>' . formato_hora($iHora) . ':' . formato_hora($iMin) . ':' . formato_hora($iSeg) . '</b>';
		}
	} else {
		$sSelA = ' Selected';
		$sSelP = '';
		if ($iFormato == 1) {
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'P';
				$sSelA = '';
				$sSelP = ' Selected';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			if (($iHora + $iMin) == 0) {
				$sVN = '';
			}
			$sAdd = '<label class="Label30"><select id="' . $sNomCampoHora . '_Ciclo" name="' . $sNomCampoHora . '_Ciclo" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');">
<option value="A"' . $sSelA . '>AM</option>
<option value="P"' . $sSelP . '>PM</option>
</select></label>
<input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="hidden" value="' . $iHora . '"/>';
			$res = '<label class="Label30"><input id="' . $sNomCampoHora . '_Num" name="' . $sNomCampoHora . '_Num" type="text" value="' . $sVN . '" class="dos" maxlength="2" placeholder="00" onchange="javascript:hora_ajusta(\'' . $sNomCampoHora . '\');"/></label>';
		} else {
			//Hora militar
			$res = '<label class="Label30"><input id="' . $sNomCampoHora . '" name="' . $sNomCampoHora . '" type="text" value="' . $iHora . '" class="dos" maxlength="2" placeholder="00"/></label>';
		}
		$sValorMin = $iMin;
		$sValorSeg = $iSeg;
		if (($iHora + $iMin + $iSeg) == 0) {
			$sValorMin = '';
			$sValorSeg = '';
		}
		$res = $res . '<label style="width:7px;"><b>:</b></label><label style="width:35px;"><input id="' . $sNomCampoMin . '" name="' . $sNomCampoMin . '" type="text" value="' . $sValorMin . '" class="dos" maxlength="2" placeholder="00"/></label>
<label style="width:7px;"><b>:</b></label><label style="width:35px;"><input id="' . $sNomCampoSeg . '" name="' . $sNomCampoSeg . '" type="text" value="' . $sValorSeg . '" class="dos" maxlength="2" placeholder="00"/></label>' . $sAdd;
	}
	return $res;
}
function html_idioma($nombre, $valor, $accion = '', $bvacio = false, $etvacio = '', $vrvacio = '')
{
	$stemp = '';
	$sES = '';
	$sEN = '';
	$sPT = '';
	if (strtolower($valor) == 'es') {
		$sES = ' Selected';
	}
	if (strtolower($valor) == 'en') {
		$sEN = ' Selected';
	}
	if (strtolower($valor) == 'pt') {
		$sPT = ' Selected';
	}
	if ($accion != '') {
		$stemp = ' onChange="' . $accion . '"';
	}
	$res = '<select id="' . $nombre . '" name="' . $nombre . '"' . $stemp . '>
';
	if ($bvacio) {
		$res = $res . '<option value="' . $vrvacio . '">' . $etvacio . '</option>
';
	}
	$res = $res . '<option value="es"' . $sES . '>Espa&ntilde;ol</option>
<option value="en"' . $sEN . '>English</option>
<option value="pt"' . $sPT . '>Portugu&ecirc;s</option>
</select>
';
	return $res;
}
function html_lnkarchivo($origen, $id, $titulo = 'Descargar')
{
	$res = '&nbsp;';
	if ($id != 0) {
		$res = '<a href="verarchivo.php?u=' . url_encode($origen . '|' . $id) . '" target="_blank" class="lnkresalte">' . $titulo . '</a>';
	}
	return $res;
}
// -- Combos ...
function html_lpp($nombre, $iactual, $saccion, $iTope = 50)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$res = '<select name="' . $nombre . '" id="' . $nombre . '" onChange="' . $saccion . '">';
	$iPaso = 4;
	switch ($iTope) {
		case 100:
			$iPaso = 5;
			break;
		case 200:
			$iPaso = 6;
			break;
		case 500:
			$iPaso = 7;
			break;
		case 1000:
			$iPaso = 8;
			break;
	}
	for ($k = 1; $k <= $iPaso; $k++) {
		$ssel = '';
		switch ($k) {
			case 1:
				$fila = 5;
				break;
			case 2:
				$fila = 10;
				break;
			case 3:
				$fila = 20;
				break;
			case 4:
				$fila = 50;
				break;
			case 5:
				$fila = 100;
				break;
			case 6:
				$fila = 200;
				break;
			case 7:
				$fila = 500;
				break;
			case 8:
				$fila = 1000;
				break;
		}
		if ($iactual == $fila) {
			$ssel = ' selected';
		}
		$res = $res . '<option' . $ssel . ' value="' . $fila . '">' . $fila . '</option>';
	}
	$res = $res . '</select>';
	$sClass = 'Label60';
	if ($iPiel == 2) {
		$sClass = 'w-10';
	}
	$res = '<label class="' . $sClass . '">' . $res . '</label>';
	return $res;
}
function html_menu_grupo($grupo, $idsistema, $objDB, $completo = true)
{
	require './app.php';
	if (isset($APP->piel) == 0) {
		$APP->piel = 0;
	}
	$iPiel = $APP->piel;
	list($sRes, $sDebug) = html_MenuGrupoV3($grupo, $idsistema, $iPiel, $objDB, $completo, false);
	return $sRes;
}
function html_MenuGrupoV2($grupo, $idsistema, $objDB, $completo = true, $bDebug = false)
{
	require './app.php';
	if (isset($APP->piel) == 0) {
		$APP->piel = 0;
	}
	$iPiel = $APP->piel;
	list($sRes, $sDebug) = html_MenuGrupoV3($grupo, $idsistema, $iPiel, $objDB, $completo, $bDebug);
	return $sRes;
}
function html_MenuGrupoV3($grupo, $idsistema, $iPiel, $objDB, $completo = true, $bDebug = false, $idTercero = 0)
{
	$bentra = false;
	$sDebug = '';
	$sadd = '';
	$res = '';
	if (($idsistema == 10) && ($grupo == 99)) {
		$sadd = '99,';
	}
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	//20 de Enero de 2020 - La consulta de grupos falla por el group by, por tanto hay que traer primero los id y luego si la consulta
	/*
	$sSQL='SELECT T9.unad09nombre, T9.unad09pagina, T9.unad09nombre_en, T9.unad09nombre_pt 
	FROM unad07usuarios as T7, unad06perfilmodpermiso AS T6, unad09modulomenu AS T9, unad02modulos AS T2 
	WHERE T7.unad07idtercero='.$idTercero.' AND T7.unad07vigente="S" AND T7.unad07idperfil=T6.unad06idperfil AND T6.unad06vigente="S" AND T6.unad06idpermiso=1 AND T6.unad06idmodulo=T9.unad09idmodulo AND T9.unad09grupo='.$grupo.' AND T6.unad06idmodulo=T2.unad02id AND T2.unad02idsistema IN (0,'.$sadd.$idsistema.') 
	GROUP BY T9.unad09nombre, T9.unad09pagina, T9.unad09nombre_en, T9.unad09nombre_pt 
	ORDER BY T9.unad09orden';
	*/
	$sId09 = '-99';
	$sSQL = 'SELECT T6.unad06idmodulo 
	FROM unad07usuarios as T7, unad06perfilmodpermiso AS T6, unad09modulomenu AS T9, unad02modulos AS T2 
	WHERE T7.unad07idtercero=' . $idTercero . ' AND T7.unad07vigente="S" 
	AND T7.unad07idperfil=T6.unad06idperfil 
	AND T6.unad06vigente="S" AND T6.unad06idpermiso=1 
	AND T6.unad06idmodulo=T9.unad09idmodulo AND T9.unad09grupo=' . $grupo . ' 
	AND T6.unad06idmodulo=T2.unad02id AND T2.unad02idsistema IN (0,' . $sadd . $idsistema . ') 
	GROUP BY T6.unad06idmodulo ';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta id modulos grupo ' . $grupo . ' App ' . $idsistema . ' ' . $sSQL . '<br>';
	}
	$resultm = $objDB->ejecutasql($sSQL);
	while ($filam = $objDB->sf($resultm)) {
		$sId09 = $sId09 . ',' . $filam['unad06idmodulo'];
	}
	$sSQL = 'SELECT T9.unad09nombre, T9.unad09pagina, T9.unad09nombre_en, T9.unad09nombre_pt 
	FROM unad09modulomenu AS T9 
	WHERE T9.unad09idmodulo IN (' . $sId09 . ') 
	ORDER BY T9.unad09orden';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Modulos a mostrar grupo ' . $grupo . ' App ' . $idsistema . ' ' . $sSQL . '<br>';
	}
	$resultm = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($resultm) > 0) {
		$bentra = true;
	}
	//$sSQL='SELECT sys44url, sys44target, sys44etiqueta FROM sys44menus WHERE sys44activo="S" AND sys44idsistema IN (0,'.$idsistema.') AND sys44grupomenu='.$grupo.' ORDER BY sys44orden, sys44consec';
	//$resultm2=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($resultm2)>0){$bentra=true;}
	if ($bentra) {
		$bc = false;
		$slin1 = '';
		$slin2 = '';
		if ($completo) {
			$bc = true;
		}
		if ((!$completo) && ($grupo == 0)) {
			$bc = true;
		}
		$sClaseLinkBase = '';
		$sInicioBloque = '';
		$sFinBloque = '';
		if ($iPiel == 0) {
			$sClaseLinkBase = ' class="ppal"';
			$sInicioBloque = '<ul><li class="ini"></li>';
			$sFinBloque = '</ul>';
			if ($bc) {
				$slin1 = '<li>';
				$slin2 = '</li>';
				$sClaseLinkBase = '';
			}
		}
		$bPielTipoUno = false;
		switch ($iPiel) {
			case 1:
			case 2:
				$bPielTipoUno = true;
				break;
		}
		if ($bPielTipoUno) {
			$sClaseLinkBase = ' class="dropdown-item"';
			$sInicioBloque = '<div class="dropdown-menu">';
			$sFinBloque = '</div>';
		}
		if ($completo) {
			$res = $res . $sInicioBloque;
		}
		while ($filamenu = $objDB->sf($resultm)) {
			if (trim($filamenu['unad09pagina']) != '') {
				if ($filamenu['unad09nombre'] == '-') {
					$res = $res . $slin1 . '<hr>' . $slin2;
				} else {
					$eti = cadena_notildes($filamenu['unad09nombre']);
					switch ($_SESSION['unad_idioma']) {
						case 'en':
							if (trim($filamenu['unad09nombre_en']) != '') {
								$eti = $filamenu['unad09nombre_en'];
							}
							break;
						case 'pt':
							if (trim($filamenu['unad09nombre_pt']) != '') {
								$eti = cadena_notildes($filamenu['unad09nombre_pt']);
							}
							break;
					}
					$res = $res . $slin1 . '<a href="' . trim($filamenu['unad09pagina']) . '"' . $sClaseLinkBase . '>' . $eti . '</a>' . $slin2;
				}
			}
		}
		if ($completo) {
			$res = $res . $sFinBloque;
		}
	}
	return array($res, $sDebug);
}
function html_menu($idsistema, $objDB, $iPiel = 1)
{
	list($sHTML, $sDebug) = html_menuV2($idsistema, $objDB, $iPiel);
	return $sHTML;
}
function html_menuV2($idsistema, $objDB, $iPiel = 1, $bDebug = false, $idTercero = 0)
{
	//if (isset($_SESSION['ent_chat'])==0){$_SESSION['ent_chat']='N';}
	require './app.php';
	$bpasa = true;
	$sDebug = '';
	$idEntidad = 0;
	if (isset($APP->entidad) != 0) {
		if ($APP->entidad == 1) {
			$idEntidad = 1;
		}
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sDebug = sesion_actualizar_v2($objDB, $bDebug);
	$et_ini = 'Inicio';
	$et_entorno = 'Entorno de trabajo';
	$et_panel = 'Panel';
	$et_chat = 'Chat';
	$et_dp = 'Datos Personales';
	$et_pwd = 'Contrase&ntilde;a';
	$et_inisesion = 'Iniciar Sesi&oacute;n';
	$et_ayuda = 'Ayuda';
	$et_acerca = 'Acerca de...';
	$et_erp = 'SIGAF';
	$et_gestion = 'Gesti&oacute;n';
	$et_manuales = 'Manuales';
	$et_miperfil = 'Mi perfil';
	// (M&oacute;dulos)
	$et_modulos = 'Acad&eacute;mico';
	$et_salir = 'Salir';
	switch ($_SESSION['unad_idioma']) {
		case 'en':
			$et_ini = 'Home';
			$et_panel = 'Panel';
			$et_dp = 'Personal Information';
			$et_pwd = 'Password';
			$et_inisesion = 'Login';
			$et_ayuda = 'Help';
			$et_acerca = 'About...';
			$et_miperfil = 'My profile';
			$et_salir = 'Exit';
			break;
		case 'pt':
			$et_ini = 'Home';
			$et_panel = 'Painel';
			$et_chat = 'Bate Papo';
			$et_dp = 'Dados Pessoais';
			$et_pwd = 'Sehna';
			$et_inisesion = 'Login';
			$et_ayuda = 'Ajuda';
			$et_acerca = 'Sobre...';
			$et_miperfil = 'Meu perfil';
			$et_salir = 'Sair';
			break;
	}
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '';
	$sFinBloque = '';
	$sInicioItem = '';
	$sFinItem = '';
	if ($iPiel == 0) {
		$sHTML = '
		<div class="menuapp">
		<ul id="navmenu-h">';
		$sClaseLinkBase = ' class="ppal"';
		$sInicioBloque = '<ul><li class="ini"></li>';
		$sFinBloque = '</ul>';
		$sInicioItem = '<li>';
		$sFinItem = '</li>';
	}
	$bPielTipoUno = false;
	switch ($iPiel) {
		case 1:
		case 2:
			$bPielTipoUno = true;
			break;
	}
	if ($bPielTipoUno) {
		$sHTML = '<ul class="nav nav-tabs">';
		$sClaseLinkBase = ' class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
		$sClaseLinkItem = ' class="dropdown-item"';
		$sClaseLiBase = ' class="nav-item dropdown"';
		$sInicioBloque = '<div class="dropdown-menu">';
		$sFinBloque = '</div>';
	}
	$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="index.php"' . $sClaseLinkBase . '><span>' . $et_ini . '</span></a>';
	if (($idTercero != 0)) {
		if ($_SESSION['cfg_movil'] == 1) {
			$bpasa = false;
		}
		if ($bpasa) {
			$sHTML = $sHTML . $sInicioBloque;
			$sHTML = $sHTML . $sInicioItem . '<a href="index.php"' . $sClaseLinkItem . '><span>' . $et_ini . '</span></a>' . $sFinItem;
			$bEntraGrupoCero = false;
			if ($idsistema > 0) {
				if ($idsistema != 51) {
					$bEntraGrupoCero = true;
				}
			}
			if ($bEntraGrupoCero) {
				list($sgrupo, $sDebugG) = html_MenuGrupoV3(0, $idsistema, $iPiel, $objDB, false, $bDebug, $idTercero);
				$sDebug = $sDebug . $sDebugG;
				$sHTML = $sHTML . $sgrupo;
			}
			$sHTML = $sHTML . $sInicioItem . '<a href="miperfil.php"' . $sClaseLinkItem . '><span>' . $et_miperfil . '</span></a>' . $sFinItem;
			$sHTML = $sHTML . $sInicioItem . '<a href="unadentorno.php"' . $sClaseLinkItem . '><span>' . $et_entorno . '</span></a>' . $sFinItem;
			if ($bPielTipoUno) {
				$sHTML = $sHTML . $sInicioItem . '<a href="salir.php"' . $sClaseLinkItem . '><span>' . $et_salir . '</span></a>' . $sFinItem;
			}
			//if ($_SESSION['ent_chat']=='S')
			$sHTML = $sHTML . $sFinBloque;
		}
		$sHTML = $sHTML . '</li>';
		//traer los encabezados que estan disponible para ese sistema.
		$sIds = '-99';
		$sSQL = 'SELECT T9.unad09grupo 
		FROM unad07usuarios AS T7, unad06perfilmodpermiso AS T6, unad02modulos AS T2, unad09modulomenu AS T9 
		WHERE T7.unad07idtercero=' . $idTercero . ' AND T7.unad07vigente="S" 
		AND T7.unad07idperfil=T6.unad06idperfil AND T6.unad06idpermiso=1 AND T6.unad06vigente="S" 
		AND T6.unad06idmodulo=T2.unad02id AND T2.unad02idsistema IN (0,' . $idsistema . ') 
		AND T6.unad06idmodulo=T9.unad09idmodulo AND T9.unad09grupo>0 AND T9.unad09grupo NOT IN (99) 
		GROUP BY T9.unad09grupo ';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Grupos del menu: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['unad09grupo'];
		}
		if ($sIds != '-99') {
			$sSQL = 'SELECT unad08nombre, unad08pagina, unad08id, unad08nombre_en, unad08nombre_pt
			FROM unad08grupomenu 
			WHERE unad08id IN (' . $sIds . ')
			ORDER BY unad08id';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				echo '...<!-- ' . $sSQL . '<br>' . $objDB->serror . ' -->';
			}
			while ($row = $objDB->sf($result)) {
				if (trim($row['unad08pagina']) != '') {
					$eti = cadena_notildes($row['unad08nombre']);
					switch ($_SESSION['unad_idioma']) {
						case 'en':
							if (trim($row['unad08nombre_en']) != '') {
								$eti = $row['unad08nombre_en'];
							}
							break;
						case 'pt':
							if (trim($row['unad08nombre_pt']) != '') {
								$eti = cadena_notildes($row['unad08nombre_pt']);
							}
							break;
					}
					$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="' . trim($row['unad08pagina']) . '"' . $sClaseLinkBase . '><span>' . $eti . '</span></a>';
					$sHTMLgrupo = '';
					if ($bpasa) {
						list($sHTMLgrupo, $sDebugG) = html_MenuGrupoV3($row['unad08id'], $idsistema, $iPiel, $objDB, true, $bDebug, $idTercero);
						$sDebug = $sDebug . $sDebugG;
					}
					$sHTML = $sHTML . $sHTMLgrupo . '</li>';
				}
			}
		}
	} else {
		//no hay tercero
		$sRutaLogin = 'http://campus.unad.edu.co';
		if ($idEntidad == 1) {
			$sRutaLogin = 'http://aurea.unad.us/campus/';
		}
		$sHTML = $sHTML . $sInicioBloque . $sInicioItem . '<a href="' . $sRutaLogin . '"' . $sClaseLinkItem . '><span>' . $et_inisesion . '</span></a>' . $sFinItem . $sFinBloque;
	}
	//Acceso a los modulos en los que tiene permiso.
	$bConModulos = false;
	$bConERP = false;
	$bConGestion = false;
	$sPerfiles = '-99';
	$sSQL = 'SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sPerfiles = $sPerfiles . ',' . $fila['unad07idperfil'];
	}
	$sSistema = '-99';
	//, unad01orden
	$sSQL = 'SELECT T1.unad02idsistema, TS.unad01orden 
	FROM unad06perfilmodpermiso AS TB, unad02modulos AS T1, unad01sistema AS TS 
	WHERE TB.unad06idperfil IN (' . $sPerfiles . ') AND TB.unad06idpermiso=1 AND TB.unad06vigente="S" AND TB.unad06idmodulo=T1.unad02id AND T1.unad02idsistema NOT IN (99, ' . $idsistema . ')
	AND T1.unad02idsistema=TS.unad01id 
	GROUP BY T1.unad02idsistema, TS.unad01orden';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($fila['unad01orden'] < 70) {
			$bConModulos = true;
		} else {
			if ($fila['unad01orden'] < 100) {
				$bConERP = true;
			} else {
				$bConGestion = true;
			}
		}
		$sSistema = $sSistema . ',' . $fila['unad02idsistema'];
	}
	if ($bConModulos) {
		//Vemos los temas asociados a modulos academicos y eventos.
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden<70 
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_modulos . '</span></a>' . $sInicioBloque;
		}
		while ($fila = $objDB->sf($tabla)) {
			$sHTML = $sHTML . $sInicioItem . '<a href="' . $fila['unad01ruta'] . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
		}
		$sHTML = $sHTML . $sFinBloque . '</li>';
	}
	if ($bConERP) {
		//ERP
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>69 AND unad01orden<100  
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bConERP = true;
			$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_erp . '</span></a>' . $sInicioBloque;
		}
		while ($fila = $objDB->sf($tabla)) {
			$sHTML = $sHTML . $sInicioItem . '<a href="' . $fila['unad01ruta'] . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
		}
		$sHTML = $sHTML . $sFinBloque . '</li>';
	}
	if ($bConGestion) {
		//Modulos de gestion.
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>99  
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bConGestion = true;
			$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_gestion . '</span></a>' . $sInicioBloque;
		}
		while ($fila = $objDB->sf($tabla)) {
			$sHTML = $sHTML . $sInicioItem . '<a href="' . $fila['unad01ruta'] . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
		}
		$sHTML = $sHTML . $sFinBloque . '</li>';
	}
	//Termina de revisar el acceso.
	$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_ayuda . '</span></a>' . $sInicioBloque;
	$sHTML = $sHTML . $sInicioItem . '<a href="unadayudas.php"' . $sClaseLinkItem . '><span>' . $et_manuales . '</span></a>' . $sFinItem;
	$sHTML = $sHTML . $sInicioItem . '<a href="acercade.php"' . $sClaseLinkItem . '><span>' . $et_acerca . '</span></a>' . $sFinItem;
	$sHTML = $sHTML . $sFinBloque . '</li>';
	if ($iPiel == 0) {
		$sHTML = $sHTML . '</ul>
		</div>';
	}
	if ($bPielTipoUno) {
		$sHTML = $sHTML . '</ul>';
	}
	return array($sHTML, $sDebug);
}
function html_oculto($snombre, $svalor, $setiqueta = '')
{
	if ($setiqueta == '') {
		$setiqueta = $svalor;
	}
	$res = '<input name="' . $snombre . '" type="hidden" id="' . $snombre . '" value="' . $svalor . '" /><b>' . $setiqueta . '</b>';
	return $res;
}
function html_paginadorV1($nombre, $iregistros, $filasxpag, $ipagactual, $saccion)
{
	$pendientes = $iregistros;
	$fila_ = 1;
	$ini_ = 1;
	if ($iregistros > $filasxpag) {
		$fin_ = $filasxpag;
		if ($fin_ > $iregistros) {
			$fin_ = $iregistros;
		}
		$res = '<select name="' . $nombre . '" id="' . $nombre . '" onChange="' . $saccion . '">
';
		while ($pendientes > 0) {
			$sSel = '';
			if ($ipagactual == $fila_) {
				$sSel = ' selected';
			}
			$res = $res . '<option value="' . $fila_ . '"' . $sSel . '>' . $ini_ . ' - ' . $fin_ . '</option>
';
			$pendientes = $pendientes - $filasxpag;
			$fila_++;
			$ini_ = $fin_ + 1;
			$fin_ = $fin_ + $filasxpag;
			if ($fin_ > $iregistros) {
				$fin_ = $iregistros;
			}
		}
		$res = $res . '</select>
';
	} else {
		if ($iregistros == 0) {
			$ini_ = 0;
		}
		$res = '<input name="' . $nombre . '" type="hidden" id="' . $nombre . '" value="1"/>{<b>' . $ini_ . ' - ' . $iregistros . '</b>}
';
	}
	return $res;
}
function html_paginador($nombre, $iregistros, $filasxpag, $ipagactual, $saccion)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$pendientes = $iregistros;
	$filasxpag = numeros_validar($filasxpag);
	if ($filasxpag == '') {
		$filasxpag = 20;
	}
	$fila_ = 1;
	$ini_ = 1;
	if ($iregistros > $filasxpag) {
		//Calcular cuantos son en total.
		$iTotalFilas = floor($iregistros / $filasxpag);
		if (($iTotalFilas * $filasxpag) < $iregistros) {
			$iTotalFilas++;
		}
		//$fin_=$filasxpag;
		//if ($fin_>$iregistros){$fin_=$iregistros;}
		$res = '<select id="' . $nombre . '" name="' . $nombre . '" onChange="' . $saccion . '">';
		if ($iTotalFilas < 22) {
			for ($t = 1; $t <= $iTotalFilas; $t++) {
				$sSel = '';
				if ($ipagactual == $t) {
					$sSel = ' selected';
				}
				if ($t == $iTotalFilas) {
					$fin_ = $iregistros;
				} else {
					$fin_ = $ini_ + $filasxpag - 1;
				}
				$res = $res . '<option value="' . $t . '"' . $sSel . '>' . formato_numero($ini_) . ' - ' . formato_numero($fin_) . '</option>';
				$ini_ = $ini_ + $filasxpag;
			}
		} else {
			$iPrimera = $ipagactual - 11;
			$iUltima = $iPrimera + 21;
			$sFinal = '';
			if ($iPrimera < 1) {
				$iPrimera = 1;
			} else {
				$ini_ = ($filasxpag * ($iPrimera - 1)) + 1;
				$res = $res . '<option value="1">1 - ' . $filasxpag . '</option>';
			}
			if ($iUltima < $iTotalFilas) {
				$iUltPag = (($iTotalFilas - 1) * $filasxpag) + 1;
				$sFinal = '<option value="' . $iTotalFilas . '">' . formato_numero($iUltPag) . ' - ' . formato_numero($iregistros) . '</option>';
			} else {
				$iUltima = $iTotalFilas;
			}
			for ($t = $iPrimera; $t <= $iUltima; $t++) {
				$sSel = '';
				if ($ipagactual == $t) {
					$sSel = ' selected';
				}
				if ($t == $iTotalFilas) {
					$fin_ = $iregistros;
				} else {
					$fin_ = $ini_ + $filasxpag - 1;
				}
				$res = $res . '<option value="' . $t . '"' . $sSel . '>' . formato_numero($ini_) . ' - ' . formato_numero($fin_) . '</option>';
				$ini_ = $ini_ + $filasxpag;
			}
			$res = $res . $sFinal;
		}
		$res = $res . '</select>';
	} else {
		if ($iregistros == 0) {
			$ini_ = 0;
		}
		$res = '<input name="' . $nombre . '" type="hidden" id="' . $nombre . '" value="1"/><b>{' . $ini_ . ' - ' . $iregistros . '}</b>';
	}
	$sClass = 'Label60';
	if ($iPiel == 2) {
		$sClass = 'w-10';
	}
	$res = '<label class="' . $sClass . '">' . $res . '</label>';
	return $res;
}
function html_pregunta($sNombre, $sValor, $sAccion, $iTipo, $bOculto = false, $iValorTope = 10)
{
	$res = '';
	if ($bOculto) {
		$sEti = $sValor;
		switch ($iTipo) {
			case 1:
				switch ($sValor) {
					case 0:
						$sEti = 'No';
						break;
					case 1:
						$sEti = 'Si';
						break;
					default:
						$sEti = '{Pendiente}';
						break;
				}
				$res = html_oculto($sNombre, $sValor, $sEti);
				break;
			case 2:
				switch ($sValor) {
					case 0:
						$sEti = 'Bajo';
						break;
					case 1:
						$sEti = 'Medio';
						break;
					case 2:
						$sEti = 'Alto';
						break;
					default:
						$sEti = '{Pendiente}';
						break;
				}
				$res = html_oculto($sNombre, $sValor, $sEti);
				break;
			case 3:
				if ($sValor == -1) {
					$sEti = '{Pendiente}';
				} else {
					$sEti = (int)$sValor . ' (Max ' . $iValorTope . ')';
				}
				$res = html_oculto($sNombre, $sValor, $sEti);
				break;
		}
	} else {
		switch ($iTipo) {
			case 1:
				$res = html_sino($sNombre, $sValor, true, '', '-1', $sAccion, 'Si', 'No', '1', '0');
				break;
			case 2:
				$res = html_combobma($sNombre, $sValor, $sAccion);
				break;
			case 3:
				if ($iValorTope > 10) {
					$iValorTope = 10;
				}
				$res = html_combo_nums($sNombre, 0, $iValorTope, $sValor, $sAccion, true, '', '-1');
				break;
		}
	}
	return $res;
}
function html_Radio($sNombre, $sValor, $sLista, $sEtiquetas, $sAccion = '', $iDerecha = true)
{
	$res = '';
	$sOpciones = explode('|', $sLista);
	$sEtiquetasControl = explode('|', $sEtiquetas);
	$iTotal = count($sOpciones);
	$sEjecuta = '';
	if ($sAccion != '') {
		$sEjecuta = ' onclick="' . $sAccion . '"';
	}
	for ($p = 0; $p < $iTotal; $p++) {
		$sEstado = '';
		$sValorOpcion = $sOpciones[$p];
		if ($sValorOpcion == $sValor) {
			$sEstado = ' checked="checked"';
		}
		$sMuestra = '';
		if (isset($sEtiquetasControl[$p]) == 0) {
			$sMuestra = $sValorOpcion;
		} else {
			$sMuestra = $sEtiquetasControl[$p];
		}
		$sSalto = '<br>';
		if ($p == 0) {
			$sSalto = '';
		}
		$sPrevio = '';
		if (!$iDerecha) {
			$sPrevio = $sMuestra;
			$sMuestra = '';
		}
		$res = $res . $sSalto . $sPrevio . '<input id="' . $sNombre . '" name="' . $sNombre . '" type="radio" value="' . $sValorOpcion . '"' . $sEstado . $sEjecuta . ' />' . $sMuestra;
	}
	return $res;
}
function html_sino($nombre, $valor, $bvacio = false, $etvacio = '', $vrvacio = '', $accion = '', $etsi = 'Si', $etno = 'No', $vrsi = 'S', $vrno = 'N')
{
	$stemp = '';
	$ssels = '';
	$sseln = '';
	if ($accion != '') {
		$stemp = ' onChange="' . $accion . '"';
	}
	if ($valor == $vrsi) {
		$ssels = ' Selected';
	}
	if ($valor == $vrno) {
		$sseln = ' Selected';
	}
	$res = '<select name="' . $nombre . '" id="' . $nombre . '"' . $stemp . '>
';
	if ($bvacio) {
		$res = $res . '<option value="' . $vrvacio . '">' . $etvacio . '</option>
';
	}
	$res = $res . '<option value="' . $vrsi . '" ' . $ssels . '>' . $etsi . '</option>
<option value="' . $vrno . '" ' . $sseln . '>' . $etno . '</option>
</select>
';
	return $res;
}
function html_TablaFecha($sFecha, $iFormato = 0)
{
	$res = '';
	if (fecha_esvalida($sFecha)) {
		switch ($iFormato) {
			case 1: // Fecha larga
				$res = formato_fechalarga($sFecha);
				break;
			case 2: // Fecha larga con dia.
				$res = formato_fechalarga($sFecha, true);
				break;
			default:
				$res = $sFecha;
		}
	}
	return $res;
}
function html_TablaHoraMin($iHora, $iMin, $iFormato = 1)
{
	$res = '00:00';
	if ($iHora < 0) {
		$iHora = 0;
	}
	if ($iMin < 0) {
		$iMin = 0;
	}
	if (($iHora + $iMin) == 0) {
	} else {
		$sVN = (int)$iHora;
		$sVC = 'AM';
		$sAdd = '';
		if ($iFormato == 1) {
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'PM';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			$res = formato_hora($sVN) . ':' . formato_hora($iMin) . ' ' . $sVC;
		} else {
			$res = formato_hora($iHora) . ':' . formato_hora($iMin);
		}
	}
	return $res;
}
function html_TablaHoraMinDesdeNumero($iHoraMin, $iFormato = 1)
{
	$iHoraMin = numeros_validar($iHoraMin);
	$iHora = 0;
	$iMinuto = 0;
	if ($iHoraMin == '') {
		return '';
	}
	if ($iHoraMin == 0) {
		return '00:00';
	}
	if ($iHoraMin > 1440) {
		$iDias = (int)($iHoraMin / 1440);
		$iHoraMin = $iHoraMin - ($iDias * 1440);
	}
	$iHora = (int)($iHoraMin / 60);
	$iMinuto = $iHoraMin - ($iHora * 60);
	return html_TablaHoraMin($iHora, $iMinuto, $iFormato);
}
function html_TablaHoraMinSeg($iHora, $iMin, $iSeg, $iFormato = 1)
{
	$res = '';
	if ($iHora + $iMin + $iSeg == 0) {
	} else {
		$sVN = (int)$iHora;
		$sVC = 'AM';
		$sAdd = '';
		if ($iFormato == 1) {
			if ($iHora > 11) {
				$sVN = $iHora - 12;
				$sVC = 'PM';
			}
			if (($sVN > 12) || ($sVN < 1)) {
				$sVN = 12;
			}
			$res = formato_hora($sVN) . ':' . formato_hora($iMin) . ':' . formato_hora($iSeg) . ' ' . $sVC;
		} else {
			$res = formato_hora($iHora) . ':' . formato_hora($iMin) . ':' . formato_hora($iSeg);
		}
	}
	return $res;
}
function html_TablaTiempo($iHora, $iMin, $iSeg, $iMilecimas = 2)
{
	$res = '';
	if ($iHora + $iMin + $iSeg == 0) {
	} else {
		$sAdd = '';
		$sSeg = formato_numero($iSeg, $iMilecimas);
		if ($iSeg < 10) {
			$sSeg = '0' . $sSeg;
		}
		$res = formato_hora($iHora) . ':' . formato_hora($iMin) . ':' . $sSeg;
	}
	return $res;
}
//Junio 04 de 2019 - Esta funcion se deprecia, se deberia usar la libreria clsHtmlTercero->Traer
//Septiembre 9 de 2020 - Se agrega el parametro especial incialmente para incluir necesidades especiales (Seguro esta depreciada??? )
function html_tercero($sTipoDoc, $sDoc, $id, $iModelo, $objDB, $iEspecial = 0)
{
	$sHTML = '';
	$sCondi = '';
	if ($iModelo == 1) {
		$sCondi = $sCondi . ' unad11tipodoc="' . $sTipoDoc . '" AND unad11doc="' . $sDoc . '"';
	} else {
		$sCondi = $sCondi . ' unad11id=' . $id . '';
	}
	$bConNecesidadesEsp = false;
	switch ($iEspecial) {
		case 1:
			$bConNecesidadesEsp = true;
			break;
	}
	$sSQL = 'SELECT unad11razonsocial, unad11direccion, unad11telefono, unad11id, unad11tipodoc, 
	unad11doc, unad11necesidadesp, unad11estado 
	FROM unad11terceros 
	WHERE ' . $sCondi . ' AND unad11id>0';
	$tablater = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablater) > 0) {
		$filater = $objDB->sf($tablater);
		$sPrefijo = '<b>';
		$sSufijo = '</b>';
		$sNombre = cadena_notildes($filater['unad11razonsocial']);
		switch ($filater['unad11estado']) {
			case 0:
				break;
			case 3:
				$sNombre = '[Derecho al olvido]';
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				$bConNecesidadesEsp = false;
				break;
			default:
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span> [Inactivo]';
				break;
		}
		$sHTML = $sPrefijo . $sNombre . $sSufijo;
		if ($bConNecesidadesEsp) {
			if ($filater['unad11necesidadesp'] != '') {
				$sHTML = $sHTML . '<br>' . cadena_notildes($filater['unad11necesidadesp']);
			}
		}
		$id = $filater['unad11id'];
		$sTipoDoc = $filater['unad11tipodoc'];
		$sDoc = $filater['unad11doc'];
	}
	return array($sHTML, $id, $sTipoDoc, $sDoc);
}
function html_Texto($sNombre, $sValor, $sAccion = '', $sComplemento = '')
{
	if ($sAccion != '') {
		$sAccion = 'onChange="' . $sAccion . '" ';
	}
	$sRes = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="text" value="' . $sValor . '" ' . $sAccion . $sComplemento . '/>';
	return $sRes;
}
function html_tipodoc($nombre, $valor, $con_nulo = false, $accion = '')
{
	echo html_tipodocV2($nombre, $valor, $accion, $con_nulo);
}
function html_tipodocV2($nombre, $valor, $accion = '', $con_nulo = false, $bConEspeciales = true)
{
	$saccion = '';
	if ($accion != '') {
		$saccion = ' onChange="' . $accion . '"';
	}
	$ssel = '';
	$res = '<label class="Label60">';
	$res = $res . '<select name="' . $nombre . '" id="' . $nombre . '"' . $saccion . '>';
	if ($con_nulo) {
		$res = $res . html_combo_opcion('', $valor, '');
	}
	//unad45tipodoc
	//switch ($_SESSION['u_pais']){
	//	case 1: //USA
	//	$res=$res.html_combo_opcion('LC',$valor,'LC');
	//	break;
	//	case 54: //ARGENTINA
	//$res=$res.html_combo_opcion('DN',$valor,'DNI');
	//	break;
	//	case 57: //COLOMBIA
	$res = $res . html_combo_opcion('CC', $valor, 'CC');
	$res = $res . html_combo_opcion('CE', $valor, 'CE');
	$res = $res . html_combo_opcion('TI', $valor, 'TI');
	$res = $res . html_combo_opcion('DN', $valor, 'DNI');
	$res = $res . html_combo_opcion('RC', $valor, 'RC');
	$res = $res . html_combo_opcion('PA', $valor, 'PA');
	$res = $res . html_combo_opcion('NI', $valor, 'NIT');
	//	break;
	//	case 58: //VENEZUELA
	//$res=$res.html_combo_opcion('CI',$valor,'CI');
	//	break;
	//	case 502: //GUATEMALA
	//$res=$res.html_combo_opcion('DPI',$valor,'DPI');
	//	break;
	//	}
	//$res=$res.html_combo_opcion('MO',$valor,'MOO');
	$res = $res . html_combo_opcion('SS', $valor, 'SS');
	$res = $res . html_combo_opcion('__', $valor, '__');
	if ($bConEspeciales) {
		$res = $res . html_combo_opcion('FL', $valor, 'FL');
	}
	$res = $res . '</select></label>';
	return $res;
}
// Tema de licencia
function licencia_PuedeSeguir($idTercero, $idSistema, $objDB)
{
	$bContinua = true;
	$sError = '';
	$iTipoLicencia = 1001;
	return array($bContinua, $sError, $iTipoLicencia);
}
function licencia_AgregarAccion($idTercero, $idSistema, $objDB)
{

}
// Fin del tema de licencia
function login_activaperfil($idtercero, $idperfil, $sestado, $objDB, $fechalimite = '00/00/0000')
{
	if ($idtercero < 1) {
		return true;
	} else {
		$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idtercero=' . $idtercero . ' AND unad07idperfil=' . $idperfil . '';
		$result = $objDB->ejecutasql($sSQL);
		$sSQL = '';
		if ($objDB->nf($result) == 0) {
			if ($sestado == 'S') {
				$sSQL = 'INSERT INTO unad07usuarios (unad07idperfil, unad07idtercero, unad07vigente, unad07fechavence) VALUES (' . $idperfil . ', ' . $idtercero . ', "S", "' . $fechalimite . '")';
			}
		} else {
			$temp = 'N';
			if ($sestado == 'S') {
				$temp = 'S';
			}
			$sSQL = 'UPDATE unad07usuarios SET unad07vigente="' . $temp . '" WHERE unad07idperfil=' . $idperfil . ' AND unad07idtercero=' . $idtercero . ' AND unad07vigente<>"' . $temp . '"';
		}
		if ($sSQL != '') {
			$result = $objDB->ejecutasql($sSQL);
		}
		return true;
	}
}
function login_cerrarsesion_v2($idsesion, $objDB, $bDebug = false)
{
	$sDebug = '';
	if ((int)$idsesion != 0) {
		if (isset($_SESSION['unad_agno']) == 0) {
			$_SESSION['unad_agno'] = '';
		}
		$sTabla = 'unad71sesion' . fecha_AgnoMes();
		if (!tabla_existe($sTabla, $objDB)) {
			$sTabla = 'unad71sesion';
		}
		$sSQL = 'SELECT unad71fechaini, unad71horaini, unad71minutoini, unad71fechafin, unad71horafin, unad71minutofin 
		FROM ' . $sTabla . ' 
		WHERE unad71id=' . $idsesion . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$row = $objDB->sf($result);
			$itotal = fecha_tiempoenminutos($row['unad71fechaini'], $row['unad71horaini'], $row['unad71minutoini'], $row['unad71fechafin'], $row['unad71horafin'], $row['unad71minutofin']) + 1;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Tiempo de la sesion ' . $itotal . '.<br>';
			}
			$sSQL = 'UPDATE ' . $sTabla . ' SET unad71tiempototal=' . $itotal . ' WHERE unad71id=' . $idsesion . ';';
			$result = $objDB->ejecutasql($sSQL);
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No hay sesion a cerrar.<br>';
		}
	}
	return $sDebug;
}
function login_iniciarsesion($objDB, $bDebug = false)
{
	$sDebug = '';
	if (isset($_SESSION['unad_agno']) == 0) {
		$_SESSION['unad_agno'] = fecha_agno();
	}
	if (isset($_SESSION['unad_id_sesion']) == 0) {
		$_SESSION['unad_geo_intentos'] = 0;
		$_SESSION['unad_geo_lat'] = '';
		$_SESSION['unad_geo_lon'] = '';
		$_SESSION['unad_id_sesion'] = 0;
		$_SESSION['u_ipusuario'] = '';
		$_SESSION['u_visual'] = 0;
	}
	if (isset($_SESSION['unad_idcentro']) == 0) {
		$_SESSION['unad_idcentro'] = 0;
	}
	$iAgnoMes = fecha_AgnoMes();
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Sesiones de ' . $iAgnoMes . '<br>';
		$sTabla71 = 'unad71sesion' . $iAgnoMes;
		if (!tabla_existe($sTabla71, $objDB)) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existe la tabla <b>unad71sesion' . $iAgnoMes . '</b><br>';
		}
	}
	if ($_SESSION['unad_id_sesion'] == 0) {
		$scampos = 'unad71id, unad71idtercero, unad71iporigen, unad71fechaini, unad71horaini, 
		unad71minutoini, unad71fechafin, unad71horafin, unad71minutofin, unad71tiempototal, 
		unad71navegador, unad71sistoperativo, unad71latgrados, unad71latdecimas, unad71longrados, 
		unad71longdecimas, unad71proximidad, unad71estado, unad71hostname';
		$dirip = sys_traeripreal();
		$fechaini = fecha_DiaMod();
		$horaini = fecha_hora();
		$minutoini = fecha_minuto();
		$sTabla71 = 'unad71sesion' . $iAgnoMes;
		if (!tabla_existe($sTabla71, $objDB)) {
			if (!tabla_crear(71, $iAgnoMes, '', $objDB)) {
				$sTabla71 = 'unad71sesion';
			}
		}
		$sNavegador = substr($_SERVER['HTTP_USER_AGENT'], 0, 100);
		$unad71sistoperativo = substr(gethostbyaddr($_SERVER['REMOTE_ADDR']), 0, 50);
		$sHost = $dirip;
		//Mayo 29 de 2018 - usamos la cookie que se debio de haber generado en el index.php
		if (isset($_COOKIE['idPC']) != 0) {
			$sHost = $_COOKIE['idPC'];
		}
		$iIntentos = 0;
		$bCreoSesion = false;
		$bImposible = false;
		while (!$bCreoSesion) {
			$id71 = tabla_consecutivo($sTabla71, 'unad71id', '', $objDB);
			$svalores = '' . $id71 . ', ' . $_SESSION['unad_id_tercero'] . ', "' . $dirip . '", "' . $fechaini . '", ' . $horaini . ', 
			' . $minutoini . ', "' . $fechaini . '", ' . $horaini . ', ' . $minutoini . ', 0,
			"' . $sNavegador . '","' . $unad71sistoperativo . '", 0, "", 0, 
			"", 0, 0, "' . $sHost . '"';
			$sSQL = 'INSERT INTO ' . $sTabla71 . '(' . $scampos . ') VALUES (' . $svalores . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$iIntentos++;
				sleep(1);
				if ($iIntentos > 10) {
					$bCreoSesion = true;
					$bImposible = true;
					seg_rastro(17, 6, 0, $_SESSION['unad_id_tercero'], 'Imposible crear sesion, se cancela el inicio de sesion en ' . $sTabla71 . '', $objDB);
				}
			} else {
				$bCreoSesion = true;
			}
		}
		if ($bImposible) {
			$bCreoSesion = false;
		}
		if ($bCreoSesion) {
			//Ahora si creamos los datos de la sesion.
			$_SESSION['u_ipusuario'] = $dirip;
			$_SESSION['unad_id_sesion'] = $id71;
			$_SESSION['unad_sesion_minuto'] = ($horaini * 60) + $minutoini;
			//Junio 14 2018 agregamos la ultima fecha de acceso al sistema.
			$sSQL = 'UPDATE unad11terceros SET unad11fechaultingreso=' . $fechaini . '  WHERE unad11id=' . $_SESSION['unad_id_tercero'] . '';
			$result = $objDB->ejecutasql($sSQL);
			seg_rastro(17, 1, 0, $_SESSION['unad_id_tercero'], 'Inicia sesion ' . formato_numero($id71) . ' en ' . $sTabla71 . '', $objDB);
			//Junio 28 de 2019 - Se cargan los parametros del tercero para la sesion
			$sSQL = 'SELECT unad11idioma, unad11idvisual FROM unad11terceros WHERE unad11id=' . $_SESSION['unad_id_tercero'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$_SESSION['unad_idioma'] = $fila['unad11idioma'];
				$_SESSION['u_visual'] = $fila['unad11idvisual'];
			}
			//Enero 23 de 2019 - Se incluye la revision de perfiles que se deja en la libdatos.
			list($sError, $sDebugG) = f107_VerificarPerfiles($_SESSION['unad_id_tercero'], '', $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugG;
		}
	}
	return $sDebug;
}
function login_revisa_grupos_v2($idtercero, $objDB)
{
}
function login_validar_v3($std, $sid, $spw, $idsistema, $objDB)
{
}
function login_valida_usuario_v3($susuario, $spw, $idsistema, $objDB)
{
	$res = '';
	$sError = '';
	if ($spw == "") {
		$sError = "Se necesita una contrase&ntilde;a";
	}
	if ($susuario == "") {
		$sError = "Se necesita un nombre de usuario";
	}
	if ($sError == '') {
		$sSQL = "SELECT unad11tipodoc, unad11doc, unad11clave FROM unad11terceros WHERE unad11usuario='" . $susuario . "'";
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$row = $objDB->sf($result);
			if (md5($spw) == $row['unad11clave']) {
				$res = login_validar_v3($row['unad11tipodoc'], $row['unad11doc'], $spw, $idsistema, $objDB);
			} else {
				$res = 'Documento no encontrado o contrase&ntilde;a incorrecta.';
			}
		} else {
			$res = 'Documento no encontrado o contrase&ntilde;a incorrecta';
		}
	} else {
		$res = '' . $sError . '';
	}
	return $res;
}

//-- Funciones de conversion
function medida_mm_a_pixel($iMM)
{
	return round(($iMM / (0.26458333)) + (0.49), 0);
}
function medida_pixel_a_mm($iPixeles, $iDecimales)
{
	return round($iPixeles * (0.26458333), $iDecimales);
}
// -- Funciones de noticias.
function noticias_pendientes($objDB)
{
	$bRes = false;
	return $bRes;
}
// -- Funciones con números
function numeros_dv($semilla)
{
	$primos = array(3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71);
	$base = numeros_validar($semilla);
	$res = '';
	$sumandos = 0;
	for ($k = 1; $k < 16; $k++) {
		if ($k > strlen($base)) {
			break;
		} else {
			$dig[$k - 1] = (int)substr($base, strlen($base) - $k, 1);
		}
		$sumandos = $sumandos + ($dig[$k - 1] * $primos[$k - 1]);
	}
	$res = $sumandos - ((int)($sumandos / 11) * 11);
	switch ($res) {
		case 0:
		case 1:
			break;
		default:
			$res = 11 - $res;
	}
	return $res;
}
// -- 19 de Febrero de 2014 - Se elimina la coma como factor permitido
function numeros_validar($semilla, $decimal = false, $idecimales = 0, $permitircomas = false)
{
	$cn = '';
	$cd = '';
	$permitidos = '-1234567890';
	//se elimina la coma como factor permitido
	if ($decimal) {
		$permitidos = $permitidos . '.';
		if ($permitircomas) {
			$permitidos = $permitidos . ',';
		}
	}
	$signo = '';
	$punto = '';
	$largo = strlen($semilla);
	for ($k = 0; $k < $largo; $k++) {
		$una = substr($semilla, $k, 1);
		$lugar = strpos($permitidos, $una);
		if ($lugar === false) {
		} else {
			switch ($una) {
				case '-':
					if (($cn == '') && ($cd == '')) {
						$signo = '-';
					}
					break;
				case '.':
				case ',':
					if ($punto == '') {
						$punto = '.';
						if ($cn == '') {
							$punto = '0.';
						}
					}
					break;
				default:
					if ($punto == '') {
						$cn = $cn . $una;
					} else {
						$cd = $cd . $una;
					}
			}
		}
	}
	if (strlen($cd) > 0) {
		if ($idecimales > 0) {
			$cd = substr($cd, 0, $idecimales);
		}
		if ((int)$cd == 0) {
			$cd = '';
		} else {
			$muestra = substr($cd, strlen($cd) - 1);
			while ($muestra == '0') {
				$cd = substr($cd, 0, strlen($cd) - 1);
				if (strlen($cd) == 0) {
					break;
				}
				$muestra = substr($cd, strlen($cd) - 1);
			}
		}
	}
	if ($cd == '') {
		$punto = '';
	}
	return $signo . $cn . $punto . $cd;
}
function Perfiles_OIL($idTercero, $objDB, $bDebug = false)
{
	//El contenido de esta función se lleva para la libdatos f107_VerificarPerfiles
	$sDebug = '';
	return $sDebug;
}
// -- Funciones para le manejo generico de registros.
function registro_duplicar($stabla, $scamposclave, $scampoid, $svrid, $svrclave, $objDB)
{
	$nclaves = explode("|", $scamposclave);
	$vclaves = explode("|", $svrclave);
	$sError = '';
	$nuevoid = $objDB->db_consec($stabla, $scampoid);
	if ($nuevoid == -1) {
		$sError = $objDB->serror;
	}
	if ($sError == '') {
		if (count($nclaves) != count($vclaves)) {
			$sError = 'Los valores para el nuevo registro no coinciden con las claves.';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $stabla . ' WHERE ' . $scampoid . '=' . $svrid;
		$torigen = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($torigen) != 0) {
			$forigen = $objDB->sf($torigen);
			$scampos = '';
			$svalores = '';
			$sSQL = 'DESCRIBE ' . $stabla;
			$test = $objDB->ejecutasql($sSQL);
			while ($fest = $objDB->sf($test)) {
				if ($scampos != '') {
					$scampos = $scampos . ',';
					$svalores = $svalores . ',';
				}
				$scampos = $scampos . $fest['Field'];
				$besclave = false;
				for ($k = 0; $k < count($nclaves); $k++) {
					if ($fest['Field'] == $nclaves[$k]) {
						$svalores = $svalores . '"' . $vclaves[$k] . '"';
						$besclave = true;
					}
				}
				if ($fest['Field'] == $scampoid) {
					$svalores = $svalores . $nuevoid;
					$besclave = true;
				}
				if (!$besclave) {
					$svalores = $svalores . '"' . $forigen[$fest['Field']] . '"';
				}
			}
			$sSQL = 'INSERT INTO ' . $stabla . ' (' . $scampos . ') VALUES (' . $svalores . ')';
			$tfin = $objDB->ejecutasql($sSQL);
			if ($tfin == false) {
				$sError = $sSQL;
			}
		} else {
			$sError = 'No se encontro el registro de origen Ref {' . $svrid . '}';
		}
	}
	return array($nuevoid, $sError);
}

function resolvercolor($entrada)
{
	$res[0] = 0;
	$res[1] = 0;
	$res[2] = 0;
	if (strlen($entrada) == 6) {
		$res[0] = resolverhexa(substr($entrada, 0, 2));
		$res[1] = resolverhexa(substr($entrada, 2, 2));
		$res[2] = resolverhexa(substr($entrada, 4, 2));
	}
	return $res;
}
function resolverhexa($semilla)
{
	$res = 0;
	$largo = strlen($semilla);
	for ($k = $largo - 1; $k >= 0; $k--) {
		$det = 0;
		$b1 = substr($semilla, $k, 1);
		switch ($b1) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
				$det = $b1;
				break;
			case "a":
			case "A":
				$det = 10;
				break;
			case "b":
			case "B":
				$det = 11;
				break;
			case "c":
			case "C":
				$det = 12;
				break;
			case "d":
			case "D":
				$det = 13;
				break;
			case "e":
			case "E":
				$det = 14;
				break;
			case "f":
			case "F":
				$det = 15;
				break;
		}
		if ($det != 0) {
			$factor = 1;
			for ($l = 1; $l <= ($largo - $k - 1); $l++) {
				$factor = ($factor * 16);
			}
			$res = $res + ($det * $factor);
			//echo 'Puesto='.$k.' Valor='.$det.' Factor='.$factor.'<br>';
		}
	}
	return $res;
}

//FUNCIONES RELATIVAS A SEGURIDAD 
function seg_auditar($idmodulo, $idtercero, $idaccion, $idregistro, $sdetalle, $objDB, $bDebug = false)
{
	$res = false;
	$sDebug = '';
	//Alistar las variables.
	$sRutaApp = './app.php';
	if (!file_exists($sRutaApp)) {
		$sDirBase = __DIR__ . '/';
		$sRutaApp = $sDirBase . 'app.php';
	}
	require $sRutaApp;
	if (isset($APP->idsistema) == 0) {
		$APP->idsistema = 0;
	}
	//ubicar la tabla de auditoria.
	$stabla = 'unad52auditoria' . date('Ym');
	$bexiste = $objDB->bexistetabla($stabla);
	if ($objDB->dbmodelo == 'M') {
		if (!$bexiste) {
			//crear la tabla
			//OJO PROBLEMA... EL USUARIO NO TIENE PERMISOS....
			$sSQL = "CREATE TABLE " . $stabla . " (unad52id int AUTO_INCREMENT PRIMARY KEY, unad52idistema int NULL, unad52codmodulo int NULL, unad52idtercero int NULL, unad52fecha varchar(10) NULL, unad52hora int NULL, unad52minuto int NULL, unad52segundo int NULL, unad52codaccion int NULL, unad52idregistro int NULL, unad52detalle Text NULL)";
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$objDB->serror = 'No es posible iniciar la auditoria para el a&ntilde;o ' . date('Ym') . ' mes ' . date('m') . '';
			} else {
				$sSQL = 'ALTER TABLE ' . $stabla . ' ADD INDEX unad52auditoria_modulo(unad52codmodulo)';
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = 'ALTER TABLE ' . $stabla . ' ADD INDEX unad52auditoria_sistema(unad52idistema)';
				$result = $objDB->ejecutasql($sSQL);
			}
		}
		$sInfoAud = '';
		$aPartes = explode('\"', $sdetalle);
		$iPartes = count($aPartes);
		for ($k = 0; $k < $iPartes; $k++) {
			if ($k != 0) {
				$sInfoAud = $sInfoAud . '\"';
			}
			$sInfoAud = $sInfoAud . str_replace('"', '\"', $aPartes[$k]);
		}
		$sSQL = 'INSERT INTO ' . $stabla . ' (unad52idistema, unad52codmodulo, unad52idtercero, unad52fecha, unad52hora, unad52minuto, unad52segundo, unad52codaccion, unad52idregistro, unad52detalle) VALUES (' . $APP->idsistema . ', ' . $idmodulo . ', ' . $idtercero . ', "' . date('d/m/Y') . '", ' . date('G') . ', ' . date('i') . ', ' . date('s') . ', ' . $idaccion . ', ' . $idregistro . ', "' . $sInfoAud . '")';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>AUDITORIA</b>: ' . $sSQL . '';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>AUDITORIA</b>: Modelo de base de datos no reconocido [' . $objDB->dbmodelo . ']';
		}
	}
	return array($res, $sDebug);
}
function seg_auditaingreso($idmodulo, $idtercero, $objDB)
{
	return seg_auditar($idmodulo, $idtercero, 1, 0, '', $objDB);
}
function seg_rastro($unad93codmodulo, $unad93codaccion, $unad93idcurso, $unad93idusuario, $unad93detalle, $objDB, $bDebug = false, $unad93idtercero = 0)
{
	list($res, $sDebug) = seg_rastroV2($unad93codmodulo, $unad93codaccion, 0, $unad93idcurso, $unad93idusuario, $unad93detalle, $objDB, $bDebug, $unad93idtercero);
	return array($res, $sDebug);
}
function seg_rastroV2($unad93codmodulo, $unad93codaccion, $unad93peraca, $unad93idcurso, $unad93idusuario, $unad93detalle, $objDB, $bDebug = false, $unad93idtercero = 0)
{
	$res = false;
	$sDebug = '';
	$sTabla = 'unad93rastros' . date('Ym');
	$bexiste = $objDB->bexistetabla($sTabla);
	if (!$bexiste) {
		$sSQL = "CREATE TABLE " . $sTabla . " (unad93id int NOT NULL, unad93idtercero int NULL DEFAULT 0, unad93fecha int NULL DEFAULT 0, unad93hora int NULL DEFAULT 0, unad93minuto int NULL DEFAULT 0, unad93segundo int NULL DEFAULT 0, unad93url varchar(250) NULL, unad93codmodulo int NULL DEFAULT 0, unad93codaccion int NULL DEFAULT 0, unad93peraca int NULL DEFAULT 0, unad93idcurso int NULL DEFAULT 0, unad93idusuario int NULL DEFAULT 0, unad93detalle Text NULL, unad93idsesion int NULL DEFAULT 0)";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$objDB->serror = 'No es posible iniciar el seguimiento para el a&ntilde;o ' . date('Y') . ' mes ' . date('m') . '';
		} else {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(unad93id)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX unad93rastros_tercero(unad93idtercero)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX unad93rastros_fecha(unad93fecha)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX unad93rastros_url(unad93url)";
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX unad93rastros_accion(unad93codaccion)";
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	$unad93id = tabla_consecutivo($sTabla, 'unad93id', '', $objDB);
	if ($unad93idtercero == 0) {
		if ($_SESSION['unad_id_tercero'] == 0) {
			$unad93idtercero = $unad93idusuario;
		} else {
			$unad93idtercero = $_SESSION['unad_id_tercero'];
		}
	}
	$unad93fecha = fecha_DiaMod();
	$unad93hora = fecha_hora();
	$unad93minuto = fecha_minuto();
	$unad93segundo = fecha_segundo();
	$unad93idsesion = 0;
	if (isset($_SESSION['unad_id_sesion']) != 0) {
		$unad93idsesion = $_SESSION['unad_id_sesion'];
	}
	//$_SESSION['unad_id_sesion']
	$unad93url = '';
	if (isset($_SERVER['SERVER_NAME']) != 0) {
		$unad93url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	if (cadena_contiene($unad93url, "servicios/wsrcb.php")) {
		$unad93url = '[ChatBot]';
	}
	$unad93detalle_final = addslashes($unad93detalle);
	$sSQL = 'INSERT INTO ' . $sTabla . ' 
	(unad93id, unad93idtercero, unad93fecha, unad93hora, unad93minuto, 
	unad93segundo, unad93url, unad93codmodulo, unad93codaccion, unad93peraca, 
	unad93idcurso, unad93idusuario, unad93detalle, unad93idsesion) VALUES 
	(' . $unad93id . ', ' . $unad93idtercero . ', ' . $unad93fecha . ', ' . $unad93hora . ', ' . $unad93minuto . ', 
	' . $unad93segundo . ', "' . $unad93url . '", ' . $unad93codmodulo . ', ' . $unad93codaccion . ', ' . $unad93peraca . ', 
	' . $unad93idcurso . ', ' . $unad93idusuario . ', "' . $unad93detalle_final . '", ' . $unad93idsesion . ')';
	$result = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' RASTRO: ' . $sSQL . '<br>';
	}
	return array($res, $sDebug);
}
// -- Revisión de permisos de usuario por modulo.
function seg_revisa_permiso($idModulo, $idPermiso, $objDB)
{
	$devuelve = false;
	if (isset($_SESSION['unad_id_tercero']) != 0) {
		list($devuelve, $sDebug) = seg_revisa_permisoV3($idModulo, $idPermiso, $_SESSION['unad_id_tercero'], $objDB);
	}
	return $devuelve;
}
function seg_revisa_permisoV2($idModulo, $idPermiso, $idTercero, $objDB)
{
	list($devuelve, $sDebug) = seg_revisa_permisoV3($idModulo, $idPermiso, $idTercero, $objDB, false);
	return $devuelve;
}
function seg_revisa_permisoV3($idModulo, $idPermiso, $idTercero, $objDB, $bDebug = false)
{
	$devuelve = false;
	$sDebug = '';
	if (($idTercero != 0) && ($idModulo != 0)) {
		$sSQL = 'SELECT 1 
		FROM unad07usuarios AS TB, unad06perfilmodpermiso AS T6 
		WHERE TB.unad07idtercero=' . $idTercero . ' AND TB.unad07vigente="S" AND TB.unad07idperfil=T6.unad06idperfil AND T6.unad06vigente="S" AND T6.unad06idmodulo=' . $idModulo . ' AND T6.unad06idpermiso=' . $idPermiso . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Revision del permiso [' . $idPermiso . ']: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$devuelve = true;
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No se ha recibido tercero [' . $idTercero . '] o modulo [' . $idModulo . '] al revisar el permiso ' . $idPermiso . '<br>';
		}
	}
	return array($devuelve, $sDebug);
}
// -- Funciones para le manejo de sesiones.
function sesion_actualizar_v2($objDB, $bDebug = false)
{
	$bHayDB = false;
	$sDebug = '';
	$bEntra = false;
	if (isset($_SESSION['unad_id_sesion']) != 0) {
		$bEntra = true;
		if (isset($_SESSION['unad_sesion_minuto']) == 0) {
			$_SESSION['unad_sesion_minuto'] = 0;
		}
		$hora = fecha_hora();
		$minuto = fecha_minuto();
		if ($_SESSION['unad_sesion_minuto'] == (($hora * 60) + $minuto)) {
			$bEntra = false;
		}
	}
	if ($bEntra) {
		if ($objDB == NULL) {
			$bHayDB = true;
			require './app.php';
			if (!class_exists('clsdbadmin')) {
				require_once $APP->rutacomun . 'libs/clsdbadmin.php';
			}
			$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
			if ($APP->dbpuerto != '') {
				$objDB->dbPuerto = $APP->dbpuerto;
			}
			$objDB->xajax();
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Conectando la DB<br>';
			}
		}
		$sTabla71 = 'unad71sesion' . fecha_AgnoMes();
		$sSQL = 'UPDATE ' . $sTabla71 . ' SET unad71horafin=' . $hora . ', unad71minutofin=' . $minuto . ' WHERE unad71id=' . $_SESSION['unad_id_sesion'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando la sesion ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($bHayDB) {
			$objDB->CerrarConexion();
		}
	}
	return $sDebug;
}
// -- Funciones relacionadas con los modulos del sistema
// -- Funciones del sistema
function sys_traeripreal()
{
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) == 0) {
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '';
	}
	if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
		$client_ip =
			(!empty($_SERVER['REMOTE_ADDR'])) ?
			$_SERVER['REMOTE_ADDR']
			: ((!empty($_ENV['REMOTE_ADDR'])) ?
				$_ENV['REMOTE_ADDR']
				:
				"sin_ip");
		// los proxys van añadiendo al final de esta cabecera
		// las direcciones ip que van "ocultando". Para localizar la ip real
		// del usuario se comienza a mirar por el principio hasta encontrar 
		// una dirección ip que no sea del rango privado. En caso de no 
		// encontrarse ninguna se toma como valor el REMOTE_ADDR
		$entries = preg_split('/[, ]/', $_SERVER['HTTP_X_FORWARDED_FOR']);
		reset($entries);
		while (list(, $entry) = each($entries)) {
			$entry = trim($entry);
			if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
				// http: //www.faqs.org/rfcs/rfc1918.html
				$private_ip = array(
					'/^0\./',
					'/^127\.0\.0\.1/',
					'/^192\.168\..*/',
					'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
					'/^10\..*/'
				);
				$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
				if ($client_ip != $found_ip) {
					$client_ip = $found_ip;
					break;
				}
			}
		}
	} else {
		$client_ip =
			(!empty($_SERVER['REMOTE_ADDR'])) ?
			$_SERVER['REMOTE_ADDR']
			: ((!empty($_ENV['REMOTE_ADDR'])) ?
				$_ENV['REMOTE_ADDR']
				:
				"sin_ip");
	}
	return $client_ip;
}

function tercero_anexos_cargar($idtercero, $objDB)
{
	$id60 = 0;
	$sSQL60 = '';
}
function tercero_historial($idtercero, $sdir, $stel, $smail, $objDB)
{
}

//FUNCIONES RELATIVAS A TRABAJO CON TABLAS
// -- Traer un consecutivo
// Junion 3 de 2015 se incorpora el parametro bNegativo
function tabla_consecutivo($stabla, $scampoconsec, $swhere, $objDB, $bNegativo = false)
{
	$res = 1;
	$sError = '';
	if ($scampoconsec == '') {
		$sError = 'Sin campo a consultar';
	}
	if ($stabla == '') {
		$sError = 'Sin tabla';
	}
	if ($sError == '') {
		$scondi = '';
		if ($swhere != '') {
			$scondi = ' WHERE ' . $swhere;
		}
		if ($bNegativo) {
			$sSQL = 'SELECT MIN(' . $scampoconsec . ') AS Max FROM ' . $stabla . ' ' . $scondi;
			$iPaso = -1;
		} else {
			$sSQL = 'SELECT MAX(' . $scampoconsec . ') AS Max FROM ' . $stabla . ' ' . $scondi;
			$iPaso = 1;
		}
		$hhhh = $objDB->ejecutasql($sSQL);
		if ($hhhh == false) {
			$sError = $sSQL;
			$res = -1;
		} else {
			if ($objDB->nf($hhhh) > 0) {
				$jjjj = $objDB->sf($hhhh);
				$res = (int)$jjjj['Max'] + $iPaso;
			} else {
				$res = $iPaso;
			}
			if ($bNegativo) {
				if ($res > (-100)) {
					$res = -101;
				}
			} else {
				if ($res < 0) {
					$res = 1;
				}
			}
		}
	} else {
		$res = -1;
	}
	return $res;
}
// -- Esta funcion devuelve un campo de una tabla por el id
function tabla_campoxid($stabla, $scamponombre, $scampoid, $svalorid, $svalordefecto, $objDB)
{
	$res = $svalordefecto;
	$sError = '';
	if ($svalorid === '') {
		$sError = 'Sin id';
	}
	if ($stabla == '') {
		$sError = 'Sin tabla';
	}
	if ($scamponombre == '') {
		$sError = 'Sin campos nombre';
	}
	if ($sError == '') {
		$sSQL = 'SELECT ' . $scamponombre . ' FROM ' . $stabla . ' WHERE ' . $scampoid . '=' . $svalorid;
		$result = $objDB->ejecutasql($sSQL);
		if ($result != false) {
			if ($objDB->nf($result) > 0) {
				$row = $objDB->sf($result);
				$sNombreSolo = $scamponombre;
				$aPalabras = explode(' AS ', $scamponombre);
				if (count($aPalabras) > 1) {
					$sNombreSolo = $aPalabras[1];
				}
				$res = $row[$sNombreSolo];
			}
		} else {
			$sError = $sSQL;
		}
	}
	return array($res, $sError);
}
function tabla_crear($idTabla, $sRef1, $sRef2, $objDB)
{
	$bCrea = false;
	switch ($idTabla) {
		case 71: // Sesiones.
			$sTabla = 'unad71sesion' . $sRef1;
			$sSQL = 'CREATE TABLE ' . $sTabla . ' (unad71id int NOT NULL, unad71idtercero int NULL DEFAULT 0, unad71iporigen varchar(50) NULL, unad71fechaini int NULL DEFAULT 0, unad71horaini int NULL DEFAULT 0, unad71minutoini int NULL DEFAULT 0, unad71fechafin int NULL DEFAULT 0, unad71horafin int NULL DEFAULT 0, unad71minutofin int NULL DEFAULT 0, unad71tiempototal int NULL DEFAULT 0, unad71navegador varchar(100) NULL, unad71sistoperativo varchar(50) NULL, unad71latgrados int NULL DEFAULT 0, unad71latdecimas varchar(10) NULL, unad71longrados int NULL DEFAULT 0, unad71longdecimas varchar(10) NULL, unad71proximidad int NULL DEFAULT 0, unad71estado int NULL DEFAULT 0, unad71hostname varchar(100) NULL, unad71horalocaliza int NULL DEFAULT 0, unad71minlocaliza int NULL DEFAULT 0, unad71seglocaliza int NULL DEFAULT 0)';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
			} else {
				$bCrea = true;
				$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(unad71id)';
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'unad71sesion_tercero', 'unad71idtercero');
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'unad71sesion_lat', 'unad71latgrados');
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'unad71sesion_dia', 'unad71fechaini');
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'unad71sesion_ip', 'unad71iporigen');
				$result = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'unad71sesion_host', 'unad71hostname');
				$result = $objDB->ejecutasql($sSQL);
			}
	}
	return $bCrea;
}
function tabla_existe($sTabla, $objDB, $bDebug = false)
{
	$bExiste = false;
	$sSQL = 'SHOW TABLES LIKE "' . $sTabla . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$bExiste = true;
	}
	return $bExiste;
}
// -- La tabla de terceros es muy frecuente usarla, por eso se dejan unas funciones para esta tabla en esta libreria.
// -- Verificar que un tercero exista.
function tabla_terceros_existe($td, $doc, $objDB, $spref = 'El tercero ', $spost = ' no existe')
{
	$res = '';
	$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11tipodoc="' . $td . '" AND unad11doc="' . $doc . '"';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) == 0) {
		$res = $spref . $td . ' ' . $doc . $spost;
	}
	return $res;
}
// -- Para mostrar un tercero
function tabla_terceros_info($td, $doc, $objDB)
{
	$res = '';
	$id = 0;
	$sSQL = 'SELECT unad11razonsocial, unad11direccion, unad11telefono, unad11id FROM unad11terceros WHERE unad11doc="' . $doc . '" AND unad11tipodoc="' . $td . '"';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$row = $objDB->sf($result);
		$res = '<b>' . cadena_notildes($row[0]) . '</b>';
		$id = $row[3];
	}
	return array(cadena_codificar($res), $id);
}

// -- Traer la informacion de un tercero.
function tabla_terceros_traer($id, $td, $doc, $objDB)
{
	list($sHTML, $id, $sTipoDoc, $sDoc) = html_tercero($td, $doc, $id, 0, $objDB);
	return array($id, $sTipoDoc, $sDoc, $sHTML);
}
function tercero_Bloqueado($idTercero, $objDB)
{
	$sError = '';
	$sInfo = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//13 de Julio de 2023 -- Si esta en LEY DE OLVIDO el tercero estará bloqueado - unad11estado en 3
	$sSQL = 'SELECT unad11bloqueado, unad11tipodoc, unad11doc, unad11razonsocial, unad11estado 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	$tabla11 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla11) > 0) {
		$fila11 = $objDB->sf($tabla11);
		if ($fila11['unad11bloqueado'] == 'S') {
			/*
			if (!function_exists('f1075_InfoBloqueo')){
				require $APP->rutacomun.'lib1075.php';
				}
			*/
			$sError = $ERR['tercero_bloqueado1'] . ' ' . $fila11['unad11tipodoc'] . $fila11['unad11doc'] . ' ' . $fila11['unad11razonsocial'] . ' ' . $ERR['tercero_bloqueado2'];
			//$sInfo=f1075_InfoBloqueo($idTercero, $objDB);
		} else {
			switch ($fila11['unad11estado']) {
				case 0:
					break;
				case 3:
					$sError = $ERR['err_leydeolvido'] . ' ' . $fila11['unad11tipodoc'] . $fila11['unad11doc'];
					break;
				default:
					break;
			}
		}
	} else {
		$sError = $ERR['no_tercero'] . ' Ref{' . $idTercero . '}';
	}
	return array($sError, $sInfo);
}

function TraerSistemaOperativo($user_agent)
{
	$plataformas = array(
		'Windows 10' => 'Windows NT 10.0',
		'Windows 8.1' => 'Windows NT 6.3',
		'Windows 8' => 'Windows NT 6.2',
		'Windows 7' => 'Windows NT 6.1',
		'Windows Vista' => 'Windows NT 6.0',
		'Windows XP' => 'Windows NT 5.1',
		'Windows 2003' => 'Windows NT 5.2',
		'Windows' => 'Windows otros',
		'iPhone' => 'iPhone',
		'iPad' => 'iPad',
		'Mac OS X' => '(Mac OS X+)|(CFNetwork+)',
		'Mac otros' => 'Macintosh',
		'Android' => 'Android',
		'BlackBerry' => 'BlackBerry',
		'Linux' => 'Linux',
	);
	foreach ($plataformas as $plataforma => $pattern) {
		if (strstr($user_agent, $pattern) != false) {
			return $plataforma;
		}
	}
	return 'Otras';
}
//Funciones para encriptar y desencriptar URLs
function url_encode($string)
{
	$string = cadena_codificar($string);
	$control = 'encrypt#34!!'; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
	$string = $control . $string . $control; //concateno la llave para encriptar la cadena
	$string = base64_encode($string); //codifico la cadena
	return ($string);
}
function url_decode($string)
{
	$cad = split('[?]', $string); //separo la url desde el ?
	$string = $cad[1]; //capturo la url desde el separador ? en adelante
	$string = base64_decode($string); //decodifico la cadena
	$control = 'encrypt#34!!'; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
	$string = str_replace($control, '', $string); //quito la llave de la cadena

	//procedo a dejar cada variable en el $_GET
	$cad_get = split('[&]', $string); //separo la url por &
	foreach ($cad_get as $value) {
		$val_get = split('[=]', $value); //asigno los valosres al GET
		if (isset($val_get[1]) == 0) {
			$val_get[1] = '';
		}
		//$_GET[$val_get[0]] = utf8_decode($val_get[1]);
	}
}
function url_decode_simple($string)
{
	$string = base64_decode($string); //decodifico la cadena
	$control = 'encrypt#34!!'; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
	$string = str_replace($control, '', $string); //quito la llave de la cadena
	return $string;
}
//Opciones del usuario
function usuario_OpcionLeer($cod_modulo, $cod_opcion, $svalordefecto, $objDB)
{
	$res = $svalordefecto;
	$sError = '';
	if ($cod_modulo == '') {
		$sError = 'Sin codigo de modulo';
	}
	if ($cod_opcion == '') {
		$sError = 'Sin codigo de opcion';
	}
	if ($sError == '') {
		$sSQL = 'SELECT TB.unad59svalor, TB.unad59ivalor, T1.unad60tipo 
		FROM unad59params AS TB, unad60preferencias AS T1 
		WHERE TB.unad59idtercero=' . $_SESSION['unad_id_tercero'] . ' AND TB.unad59idmodulo=' . $cod_modulo . ' AND TB.unad59idpreferencia=' . $cod_opcion . ' AND TB.unad59idmodulo=T1.unad60idmodulo AND TB.unad59idpreferencia=T1.unad60codigo';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['unad60tipo'] == 0) {
				$res = (int)$fila['unad59ivalor'];
			} else {
				$res = $fila['unad59svalor'];
			}
		}
	}
	return $res;
}
function version_upd($objDB)
{
	$iVer = 0;
	$sSQL = "SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iVer = $fila['unad00valor'];
	}
	return $iVer;
}
//--- Funciones Adicionales del ERP
function f3403_TablaDetalle($aParametros, $objDB)
{
	return '';
}
function entidad_Opcion($idEntidad, $sOpcion, $objDB)
{
	$res = '';
	$sSQL = 'SELECT ' . $sOpcion . ' FROM aure99config WHERE aure99id=' . $idEntidad;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$res = $fila[$sOpcion];
	}
	return $res;
}
function reportes_id($idtipo, $objDB)
{
	$res = 0;
	return $res;
}
