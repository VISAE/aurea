<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4 jueves, 22 de septiembre de 2022
--- La intencionalidad de esta libreria es devolver los nombres de los diferentes ids usados en reportes.
*/
// -- Nombre de una persona.
function f111_NombreTercero($idTercero, $objDB, $bHTML = false)
{
	$sNomTercero = '{' . $idTercero . '}';
	$sTipoDoc = '';
	$sDocumento = '';
	$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla11 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla11) > 0) {
		$fila11 = $objDB->sf($tabla11);
		if ($bHTML) {
			$sNomTercero = cadena_notildes($fila11['unad11razonsocial']);
		} else {
			$sNomTercero = $fila11['unad11razonsocial'];
		}
		$sTipoDoc = $fila11['unad11tipodoc'];
		$sDocumento = $fila11['unad11doc'];
	}
	return array($sNomTercero, $sTipoDoc, $sDocumento);
}
//
function f140_NombreCurso($idCurso, $objDB, $bHTML = false)
{
	$sNomCurso = '{' . $idCurso . '}';
	$sTitulo = '';
	$sSQL = 'SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla11 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla11) > 0) {
		$fila11 = $objDB->sf($tabla11);
		if ($bHTML) {
			$sNomCurso = cadena_notildes($fila11['unad40nombre']);
		} else {
			$sNomCurso = $fila11['unad40nombre'];
		}
		$sTitulo = $fila11['unad40titulo'];
	}
	return array($sNomCurso, $sTitulo);
}
//
function f146_NombrePeriodo($idPeriodo, $objDB, $bHTML = false)
{
	$sNomPeriodo = '{' . $idPeriodo . '}';
	$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		if ($bHTML) {
			$sNomPeriodo = cadena_notildes($filae['exte02nombre']) . ' [' . $idPeriodo . ']';
		} else {
			$sNomPeriodo = $filae['exte02nombre'];
		}
	}
	return array($sNomPeriodo);
}
//
function f123_NombreZona($idZona, $objDB, $bHTML = false, $iModo = 0)
{
	$sNomZona = '{' . $idZona . '}';
	$sSQL = 'SELECT unad23nombre, unad23codigo, unad23sigla FROM unad23zona WHERE unad23id=' . $idZona . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		switch ($iModo) {
			case 1: // Solo la sigla
				if ($bHTML) {
					$sNomZona = cadena_notildes($filae['unad23sigla']);
				} else {
					$sNomZona = $filae['unad23sigla'];
				}
				break;
			case 2: // Nombre y sigla
				if ($bHTML) {
					$sNomZona = cadena_notildes($filae['unad23nombre'] . ' - ' . $filae['unad23sigla']);
				} else {
					$sNomZona = $filae['unad23nombre'] . ' - ' . $filae['unad23sigla'];
				}
				break;
			default:
				if ($bHTML) {
					$sNomZona = cadena_notildes($filae['unad23nombre']);
				} else {
					$sNomZona = $filae['unad23nombre'];
				}
				break;
		}
	}
	return array($sNomZona);
}
// Centro
function f124_NombreCentro($idCentro, $objDB, $bHTML = false, $iModo = 0)
{
	$sNomCentro = '{' . $idCentro . '}';
	$sSQL = 'SELECT unad24nombre, unad24codigo FROM unad24sede WHERE unad24id=' . $idCentro . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		switch ($iModo) {
			case 1: // Solo la sigla
				if ($bHTML) {
					$sNomCentro = cadena_notildes($filae['unad24codigo']);
				} else {
					$sNomCentro = $filae['unad24codigo'];
				}
				break;
			case 2: // Nombre y sigla
				if ($bHTML) {
					$sNomCentro = cadena_notildes($filae['unad24nombre'] . ' - ' . $filae['unad24codigo']);
				} else {
					$sNomCentro = $filae['unad24nombre'] . ' - ' . $filae['unad24codigo'];
				}
				break;
			default:
				if ($bHTML) {
					$sNomCentro = cadena_notildes($filae['unad24nombre']);
				} else {
					$sNomCentro = $filae['unad24nombre'];
				}
				break;
		}
	}
	return array($sNomCentro);
}
// OIL - 21
function f2161_NombreAsginacion($olab61id, $objDB) 
{
	$sNomAsigna = '{' . $olab61id . '}';
	$sSQL = 'SELECT TB.olab61id, TB.olab61idtanda, T63.olab63consec, TB.olab61idperiodo, TB.olab61curso 
	FROM olab61simuladorasigna AS TB, olab63tandas AS T63 
	WHERE TB.olab61id=' . $olab61id . ' AND TB.olab61idtanda=T63.olab63id 
	ORDER BY T63.olab63consec, TB.olab61idperiodo, TB.olab61curso';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNomAsigna = 'Tanda ' . $fila['olab63consec'] . ' - Curso ' . $fila['olab61curso'] . ' Periodo ' . $fila['olab61idperiodo'] . '';
	}
	return array($sNomAsigna);
}
function f2163_NombreTanda($idTanda, $objDB)
{
	$sNomTanda = '{' . $idTanda . '}';
	$sSQL = 'SELECT olab63consec, olab63fechaini, olab63fechafin, olab63numcupos 
	FROM olab63tandas 
	WHERE olab63id=' . $idTanda . '';
	$tabla11 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla11) > 0) {
		$fila11 = $objDB->sf($tabla11);
		$sNomTanda = $fila11['olab63consec'] . ' - Desde ' . fecha_desdenumero($fila11['olab63fechaini']) . ' hasta ' . fecha_desdenumero($fila11['olab63fechafin']) . ' - Cupos ' . formato_numero($fila11['olab63numcupos']);
	}
	return array($sNomTanda);
}
//
function f2209_NombrePrograma($idPrograma, $objDB, $bHTML = false, $iModo = 0)
{
	$sNomPrograma = '{' . $idPrograma . '}';
	$sSQL = 'SELECT core09nombre, core09codigo FROM core09programa WHERE core09id=' . $idPrograma . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		switch ($iModo) {
			case 1: // Solo la sigla
				if ($bHTML) {
					$sNomPrograma = cadena_notildes($filae['core09codigo']);
				} else {
					$sNomPrograma = $filae['core09codigo'];
				}
				break;
			case 2: // Nombre y sigla
				if ($bHTML) {
					$sNomPrograma = cadena_notildes($filae['core09nombre'] . ' - ' . $filae['core09codigo']);
				} else {
					$sNomPrograma = $filae['core09nombre'] . ' - ' . $filae['core09codigo'];
				}
				break;
			default:
				if ($bHTML) {
					$sNomPrograma = cadena_notildes($filae['core09nombre']);
				} else {
					$sNomPrograma = $filae['core09nombre'];
				}
				break;
		}
	}
	return array($sNomPrograma);
}
//
function f2212_NombreEscuela($idEscuela, $objDB, $bHTML = false, $iModo = 0)
{
	$sNomEscuela = '{' . $idEscuela . '}';
	$sSQL = 'SELECT core12nombre, core12sigla FROM core12escuela WHERE core12id=' . $idEscuela . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		switch ($iModo) {
			case 1: // Solo la sigla
				if ($bHTML) {
					$sNomEscuela = cadena_notildes($filae['core12sigla']);
				} else {
					$sNomEscuela = $filae['core12sigla'];
				}
				break;
			case 2: // Nombre y sigla
				if ($bHTML) {
					$sNomEscuela = cadena_notildes($filae['core12nombre'] . ' - ' . $filae['core12sigla']);
				} else {
					$sNomEscuela = $filae['core12nombre'] . ' - ' . $filae['core12sigla'];
				}
				break;
			default:
				if ($bHTML) {
					$sNomEscuela = cadena_notildes($filae['core12nombre']);
				} else {
					$sNomEscuela = $filae['core12nombre'];
				}
				break;
		}
	}
	return array($sNomEscuela);
}
//Lineas de profundización
function f2221_NombreLinea($idLinea, $objDB, $bHTML = false)
{
	if ((int)$idLinea == 0) {
		return array('');
	}
	$sNomLinea = '{' . $idLinea . '}';
	$sSQL = 'SELECT core21nombre FROM core21lineaprof WHERE core21id=' . $idLinea . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		if ($bHTML) {
			$sNomLinea = cadena_notildes($filae['core21nombre']);
		} else {
			$sNomLinea = $filae['core21nombre'];
		}
	}
	return array($sNomLinea);
}
// 2278 --- Programas SNIES
function f2278_NombrePrograma($idPrograma, $objDB, $bHTML = false) 
{
	if ((int)$idPrograma == 0) {
		return array('');
	}
	$sTituloVersion = '{' . $idPrograma . '}';
	$sSQL = 'SELECT TB.core78codigoprog, TB.core78nombre 
	FROM core78iesprograma AS TB 
	WHERE TB.core78id=' . $idPrograma . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		if ($bHTML) {
			$sTituloVersion = $filae['core78codigoprog'] . ' - ' . cadena_notildes($filae['core78nombre']);
		} else {
			$sTituloVersion = $filae['core78codigoprog'] . ' - ' . $filae['core78nombre'];
		}
	}
	return array($sTituloVersion);
}
// 3643 --- Versionado de metas
function f3643_TituloVersion($idVersion, $objDB, $bHTML = false)
{
	if ((int)$idVersion == 0) {
		return array('');
	}
	$sTituloVersion = '{' . $idVersion . '}';
	$sSQL = 'SELECT CONCAT(TB.plan43idvigencia, " - ", T12.core12sigla, " - ", TB.plan43numero) AS nombre 
	FROM plan43metaversion AS TB, core12escuela AS T12 
	WHERE TB.plan43id=' . $idVersion . ' AND TB.plan43idescuela=T12.core12id';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		if ($bHTML) {
			$sTituloVersion = cadena_notildes($filae['nombre']);
		} else {
			$sTituloVersion = $filae['nombre'];
		}
	}
	return array($sTituloVersion);
}

function f3912_TituloProceso($comp12id, $objDB)
{
	$sTituloProceso = '';
	$sSQL = 'SELECT comp12consec, comp12valortotal, comp12descripcion 
	FROM comp12procesocompra 
	WHERE comp12id=' . $comp12id . '
	ORDER BY comp12consec DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)) {
		$fila = $objDB->sf($tabla);
		$sTituloProceso = $fila['comp12consec'] . ' - [' . formato_moneda($fila['comp12valortotal']) . '] ' . cadena_notildes($fila['comp12descripcion']);
	}
	return array($sTituloProceso);
}

function f3921_TituloPlanDeCompras($comp21id, $objDB, $bConVigencia = false)
{
	require './app.php';
	$mensajes_3921 = $APP->rutacomun . 'lg/lg_3921_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3921)) {
		$mensajes_3921 = $APP->rutacomun . 'lg/lg_3921_es.php';
	}
	require $mensajes_3921;
	$sTituloProceso = '';
	$sSQL = 'SELECT T3.ppto03codigo, TB.comp21tipo, TB.comp21consec  
	FROM comp21plancompra AS TB, ppto03vigencia AS T3
	WHERE TB.comp21id=' . $comp21id . ' AND TB.comp21vigencia=T3.ppto03id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)) {
		$fila = $objDB->sf($tabla);
		if ($bConVigencia) {
			$sTituloProceso = $fila['ppto03codigo'] . ' - ' . $acomp21tipo[$fila['comp21tipo']];
		} else {
			$sTituloProceso = $acomp21tipo[$fila['comp21tipo']];
		}
		if ($fila['comp21tipo'] != 0) {
			$sTituloProceso = $sTituloProceso . ' - ' . $fila['comp21consec'];
		}
	}
	return array($sTituloProceso);
}
//Lineas de electividad.
function f4717_NombreLineaElectividad($idLinea, $objDB, $bHTML = false)
{
	if ((int)$idLinea == 0) {
		return array('');
	}
	$sNomLinea = '{' . $idLinea . '}';
	$sSQL = 'SELECT corg17nombre FROM corg17plancampo WHERE corg17id=' . $idLinea . '';
	$tablae = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae) > 0) {
		$filae = $objDB->sf($tablae);
		if ($bHTML) {
			$sNomLinea = cadena_notildes($filae['corg17nombre']);
		} else {
			$sNomLinea = $filae['corg17nombre'];
		}
	}
	return array($sNomLinea);
}
