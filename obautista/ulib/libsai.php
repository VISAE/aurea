<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
*/
function unad11_Mostrar_v2SAI($params)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if ($tipodoc!='CE'){$doc=solonumeros($doc);}
	$respuesta = '';
	$idTercero = 0;
	if (!is_array($params)) {
		$params = json_decode(str_replace('\"', '"', $params), true);
	}
	if (isset($params[4]) == 0) {
		$params[4] = '';
	}
	if (isset($params[5]) == 0) {
		$params[5] = '';
	}
	if (isset($params[6]) == 0) {
		$params[6] = '';
	}
	$tipodoc = $params[0];
	$doc = trim($params[1]);
	$objid = $params[2];
	$sdiv = $params[3];
	$idModulo = $params[6];
	$bXajax = true;
	$bExiste = false;
	$bHayDB = false;
	$bConAdicionales = false;
	$iTipoInteresado = 4; // Aspirante.
	$idEscuela = '';
	$idPrograma = '';
	$idZona = '';
	$idCead = '';
	$bEnviarCorreo = false;
	$sCampoCorreo = '';
	$sCampoDireccion = '';
	$sCorreo = '';
	$sDireccion = '';
	switch ($idModulo) {
		case 3005:
			$sCampoPais = 'saiu05idzona';
			$sCampoDepto = 'saiu05coddepto';
			$sCampoCiudad = 'saiu05codciudad';
			$sCampoTelefono = 'saiu05numorigen';
			$sCampoTipoInteresado = 'saiu05tipointeresado';
			$sCampoZona = 'saiu05idzona';
			$sCampoCentro = 'saiu05idcentro';
			$sCampoEscuela = 'saiu05idescuela';
			$sCampoPrograma = 'saiu05idprograma';
			$sCampoPeriodo = 'saiu05idperiodo';
			$bEnviarCorreo = true;
			$sCampoCorreo = 'saiu05rptacorreo';
			$sCampoDireccion = 'saiu05rptadireccion';
			break;
		case 3018:
			$sCampoPais = 'saiu18idzona';
			$sCampoDepto = 'saiu18coddepto';
			$sCampoCiudad = 'saiu18codciudad';
			$sCampoTelefono = 'saiu18numorigen';
			$sCampoTipoInteresado = 'saiu18tipointeresado';
			$sCampoZona = 'saiu18idzona';
			$sCampoCentro = 'saiu18idcentro';
			$sCampoEscuela = 'saiu18idescuela';
			$sCampoPrograma = 'saiu18idprograma';
			$sCampoPeriodo = 'saiu18idperiodo';
			break;
		case 3019:
			$sCampoPais = 'saiu19idzona';
			$sCampoDepto = 'saiu19coddepto';
			$sCampoCiudad = 'saiu19codciudad';
			$sCampoTelefono = 'saiu19numorigen';
			$sCampoTipoInteresado = 'saiu19tipointeresado';
			$sCampoZona = 'saiu19idzona';
			$sCampoCentro = 'saiu19idcentro';
			$sCampoEscuela = 'saiu19idescuela';
			$sCampoPrograma = 'saiu19idprograma';
			$sCampoPeriodo = 'saiu19idperiodo';
			break;
		case 3020:
			$sCampoPais = 'saiu20idzona';
			$sCampoDepto = 'saiu20coddepto';
			$sCampoCiudad = 'saiu20codciudad';
			$sCampoTelefono = 'saiu20numorigen';
			$sCampoTipoInteresado = 'saiu20tipointeresado';
			$sCampoZona = 'saiu20idzona';
			$sCampoCentro = 'saiu20idcentro';
			$sCampoEscuela = 'saiu20idescuela';
			$sCampoPrograma = 'saiu20idprograma';
			$sCampoPeriodo = 'saiu20idperiodo';
			break;
		case 3021:
			$sCampoPais = 'saiu21idzona';
			$sCampoDepto = 'saiu21coddepto';
			$sCampoCiudad = 'saiu21codciudad';
			$sCampoTelefono = 'saiu21numorigen';
			$sCampoTipoInteresado = 'saiu21tipointeresado';
			$sCampoZona = 'saiu21idzona';
			$sCampoCentro = 'saiu21idcentro';
			$sCampoEscuela = 'saiu21idescuela';
			$sCampoPrograma = 'saiu21idprograma';
			$sCampoPeriodo = 'saiu21idperiodo';
			break;
		case 3028: //Mesa de ayuda.
			$sCampoPais = 'saiu28idzona';
			$sCampoDepto = 'saiu28coddepto';
			$sCampoCiudad = 'saiu28codciudad';
			$sCampoTelefono = 'saiu28numorigen';
			$sCampoTipoInteresado = 'saiu28tipointeresado';
			$sCampoZona = 'saiu28idzona';
			$sCampoCentro = 'saiu28idcentro';
			$sCampoEscuela = 'saiu28idescuela';
			$sCampoPrograma = 'saiu28idprograma';
			$sCampoPeriodo = 'saiu28idperiodo';
			break;
		default:
			$objResponse = new xajaxResponse();
			$objResponse->call("MensajeAlarmaV2('Modulo desconocido Ref:" . $idModulo . "', 0)");
			return $objResponse;
			die();
			break;
	}
	if ($doc != '') {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDB = true;
		list($respuesta, $idTercero, $tipodoc, $doc) = html_tercero($tipodoc, $doc, 0, 1, $objDB);
		if ($respuesta == '') {
			//IMPORTACION AUTOMATICA DE TERCEROS - MARZO 19 DE 2014
			// Ver si esta en la tabla mdl_user
			if ($tipodoc == 'CC') {
				unad11_importar_V2($doc, '', $objDB);
				list($respuesta, $idTercero) = tabla_terceros_info($tipodoc, $doc, $objDB);
			}
			//-- FIN DE LA IMPORTACION AUTOMATICA
			if ($respuesta == '') {
				$respuesta = '<font class="rojo">{' . $tipodoc . ' ' . $doc . ' No encontrado}</font>';
			} else {
				$bExiste = true;
			}
		} else {
			$bExiste = true;
		}
	} else {
		$respuesta = '&nbsp;';
	}
	if ($bExiste) {
		$sCodPais = '057';
		$sCodDepto = '';
		$sCodCiudad = '';
		$sTelefono = '';
		$sCorreo = '';
		$objCombos = new clsHtmlCombos();
		$sSQL = 'SELECT unad11pais, unad11deptoorigen, unad11ciudadorigen, unad11telefono, unad11correofuncionario, unad11correo, unad11direccion 
		FROM unad11terceros 
		WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sCodPais = $fila['unad11pais'];
			$sCodDepto = $fila['unad11deptoorigen'];
			$sCodCiudad = $fila['unad11ciudadorigen'];
			$sTelefono = $fila['unad11telefono'];
			list($bCorreoValido, $sDebug) = correo_VerificarV2($fila['unad11correofuncionario']);
			if ($bCorreoValido) {
				$sCorreo = $fila['unad11correofuncionario'];
			} else {
				$sCorreo = $fila['unad11correo'];
			}
			$sDireccion = $fila['unad11direccion'];
		}
		//Ahora buscamos si ha sido estudiante.
		$sSQL = 'SELECT core01idestado, core01idescuela, core01idprograma, core01idzona, core011idcead 
		FROM core01estprograma 
		WHERE core01idtercero=' . $idTercero . ' AND core01idestado NOT IN (11, 12) 
		ORDER BY core01id DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idEscuela = $fila['core01idescuela'];
			$idPrograma = $fila['core01idprograma'];
			$idZona = $fila['core01idzona'];
			$idCead = $fila['core011idcead'];
			$bConAdicionales = true;
			switch ($fila['core011idcead']) {
				case -2: // Candidato
				case -1: // Admitido
				case 9: // Retirado
				case 98: // Candidato Desinteresado
				case 99: // Inadmitido
					$iTipoInteresado = 4; // Aspirante.
					break;
				case 10: // Graduado
					$iTipoInteresado = 5; // Egresado
					break;
				default:
					$iTipoInteresado = 1; //Estudiante
					break;
			}
		}
		//Ahora buscamos a ver si ha sido tutor.
		if (!$bConAdicionales) {
			$sSQL = 'SELECT core20idzona, core20idcentro 
			FROM core20asignacion 
			WHERE core20idtutor=' . $idTercero . ' 
			ORDER BY core20idperaca DESC
			LIMIT 0, 1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idZona = $fila['core20idzona'];
				$idCead = $fila['core20idcentro'];
				$bConAdicionales = true;
				$iTipoInteresado = 3;
			}
		}
	}
	if ($bExiste) {
		switch ($idModulo) {
			case 3005: //PQRS
				$html_depto = f3005_HTMLComboV2_saiu05coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3005_HTMLComboV2_saiu05codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
			case 3018: //Telefonico
				$html_depto = f3018_HTMLComboV2_saiu18coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3018_HTMLComboV2_saiu18codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
			case 3019: //Chat
				$html_depto = f3019_HTMLComboV2_saiu19coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
			case 3020: //Correo
				$html_depto = f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
			case 3021: //Presencial
				$html_depto = f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
			case 3028:
				$html_depto = f3028_HTMLComboV2_saiu28coddepto($objDB, $objCombos, $sCodDepto, $sCodPais);
				$html_ciudad = f3028_HTMLComboV2_saiu28codciudad($objDB, $objCombos, $sCodCiudad, $sCodDepto);
				break;
		}
	}
	if ($bConAdicionales) {
		switch ($idModulo) {
			case 3005:
				$html_programa = f3005_HTMLComboV2_saiu05idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3005_HTMLComboV2_saiu05idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
			case 3018:
				$html_programa = f3018_HTMLComboV2_saiu18idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3018_HTMLComboV2_saiu18idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
			case 3019:
				$html_programa = f3019_HTMLComboV2_saiu19idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3019_HTMLComboV2_saiu19idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
			case 3020:
				$html_programa = f3020_HTMLComboV2_saiu20idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
			case 3021:
				$html_programa = f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
			case 3028:
				$html_programa = f3028_HTMLComboV2_saiu28idprograma($objDB, $objCombos, $idPrograma, $idEscuela);
				$html_centro = f3028_HTMLComboV2_saiu28idcentro($objDB, $objCombos, $idCead, $idZona);
				break;
		}
	}
	if ($bHayDB) {
		$objDB->CerrarConexion();
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', utf8_encode($respuesta));
	$objResponse->assign($objid . '_doc', 'value', $doc);
	$objResponse->assign($objid, 'value', $idTercero);
	if ($bExiste) {
		$objResponse->assign($sCampoPais, 'value', $sCodPais);
		$objResponse->assign($sCampoTelefono, 'value', $sTelefono);
		$objResponse->assign('div_' . $sCampoDepto, 'innerHTML', $html_depto);
		$objResponse->call('$("#' . $sCampoDepto . '").chosen()');
		$objResponse->assign('div_' . $sCampoCiudad, 'innerHTML', $html_ciudad);
		$objResponse->call('$("#' . $sCampoCiudad . '").chosen()');
	}
	$objResponse->assign($sCampoTipoInteresado, 'value', $iTipoInteresado);
	if ($bConAdicionales) {
		$objResponse->assign($sCampoZona, 'value', $idZona);
		$objResponse->assign($sCampoEscuela, 'value', $idEscuela);
		$objResponse->assign('div_' . $sCampoPrograma, 'innerHTML', $html_programa);
		$objResponse->call('$("#' . $sCampoPrograma . '").chosen()');
		$objResponse->assign('div_' . $sCampoCentro, 'innerHTML', $html_centro);
		$objResponse->call('$("#' . $sCampoCentro . '").chosen()');
	}
	if ($idTercero == 0) {
		if ($params[5] != '') {
			$objResponse->call($params[5]);
		}
	} else {
		if ($params[4] != '') {
			$objResponse->call($params[4]);
		}
	}
	if ($bEnviarCorreo) {
		$objResponse->assign($sCampoCorreo, 'value', $sCorreo);
		$objResponse->assign($sCampoDireccion, 'value', $sDireccion);
	}
	$objResponse->call('paginarf3000()');
	return $objResponse;
}
function unad11_TraerXidSAI($params)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$respuesta = '';
	if (!is_array($params)) {
		$params = json_decode(str_replace('\"', '"', $params), true);
	}
	$idTercero = $params[0];
	$tipodoc = 'CC';
	$doc = '';
	$objid = $params[1];
	// El parametro 4 es la funciona que se llamará si esta el tercero y la 5 la se llamara en caso de que no.
	if (isset($params[4]) == 0) {
		$params[4] = '';
	}
	if (isset($params[5]) == 0) {
		$params[5] = '';
	}
	if (isset($params[6]) == 0) {
		$params[6] = '';
	}
	$idModulo = $params[6];
	switch ($idModulo) {
		case 3005:
		case 3018:
		case 3019:
		case 3028: //Mesa de ayuda.
			break;
		default:
			$objResponse = new xajaxResponse();
			$objResponse->call("MensajeAlarmaV2('Modulo desconocido Ref:" . $idModulo . "', 0)");
			return $objResponse;
			die();
			break;
	}
	if ((int)$idTercero != 0) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT unad11tipodoc, unad11doc FROM unad11terceros WHERE unad11id=';
		list($id, $tipodoc, $doc, $respuesta) = tabla_terceros_traer($idTercero, '', '', $objDB);
		if ($respuesta == '') {
			$idTercero = 0;
			$tipodoc = $APP->tipo_doc;
			$respuesta = '<font class="rojo">{Ref: ' . $idTercero . ' No encontrado}</font>';
		}
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($objid, 'value', $idTercero);
	$objResponse->assign($objid . '_td', 'value', $tipodoc);
	$objResponse->assign($objid . '_doc', 'value', $doc);
	$objResponse->assign('div_' . $objid, 'innerHTML', $respuesta);
	if ($id == 0) {
		if ($params[5] != '') {
			$objResponse->call($params[5]);
		}
	} else {
		if ($params[4] != '') {
			$objResponse->call($params[4]);
		}
	}
	return $objResponse;
}

function f3000_CalcularTotales($idTercero, $iAgno, $iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iMes = (int)$iMes;
	$sSQL = 'UPDATE saiu15historico SET saiu15numsolicitudes=0, saiu15numsupervisiones=0 WHERE saiu15idinteresado=' . $idTercero . ' AND saiu15agno=' . $iAgno . ' AND saiu15mes=' . $iMes . '';
	$result = $objDB->ejecutasql($sSQL);
	$sSQL = 'SHOW TABLES LIKE "saiu05solicitud' . f3000_Contenedor($iAgno, $iMes) . '"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Lista de bases: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sNomTabla = $fila[0];
		$iSolicitados = 0;
		$iResponsable = 0;
		$sSQL = 'SELECT saiu05tiporadicado, COUNT(saiu05id) AS Total FROM ' . $sNomTabla . ' WHERE saiu05idsolicitante=' . $idTercero . ' GROUP BY saiu05tiporadicado';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el mes: ' . $sSQL . '<br>';
		}
		$tabla5 = $objDB->ejecutasql($sSQL);
		while ($fila5 = $objDB->sf($tabla5)) {
			$iSolicitados = $fila5['Total'];
			$sSQL = 'SELECT saiu15id FROM saiu15historico WHERE saiu15idinteresado=' . $idTercero . ' AND saiu15agno=' . $iAgno . ' AND saiu15mes=' . $iMes . ' AND saiu15tiporadicado=' . $fila5['saiu05tiporadicado'] . '';
			$tabla15 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla15) == 0) {
				$saiu15id = tabla_consecutivo('saiu15historico', 'saiu15id', '', $objDB);
				$sSQL = 'INSERT INTO saiu15historico (saiu15idinteresado, saiu15agno, saiu15mes, saiu15tiporadicado, saiu15id, saiu15numsolicitudes, saiu15numsupervisiones) VALUES (' . $idTercero . ', ' . $iAgno . ', ' . $iMes . ', ' . $fila5['saiu05tiporadicado'] . ', ' . $saiu15id . ', ' . $iSolicitados . ', ' . $iResponsable . ')';
			} else {
				$fila15 = $objDB->sf($tabla15);
				$sSQL = 'UPDATE saiu15historico SET saiu15numsolicitudes=' . $iSolicitados . ', saiu15numsupervisiones=' . $iResponsable . ' WHERE saiu15id=' . $fila15['saiu15id'] . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Actualiza el historico: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
function f3000_Contenedor($iAgno, $iMes)
{
	$iMes = (int)$iMes;
	if ($iMes < 10) {
		$sContenedor = $iAgno . '0' . $iMes;
	} else {
		$sContenedor = $iAgno . $iMes;
	}
	return '_' . $sContenedor . '';
}
function f3000_Estado($idTercero, $objDB, $bHtml = true, $bDebug = false, $bLocal = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$iEnProceso = 0;
	$iACargo = 0;
	$iVencidas = 0;
	$iEnSupervision = 0;
	$sDebug = '';
	$sRes = '';
	$bHayDatos = false;
	//El tema aqui es que son tablas distribuidad, por tanto la busqueda tiene que hacerse en multiples tablas.
	//if($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PENDIENTES SAI<br>';}
	$iACargoMa = 0;
	$iVencidasMa = 0;
	$iEnSupervisionMa = 0;
	$sSQL = 'SHOW TABLES LIKE "saiu28mesaayuda%"';
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$sTabla = $filac[0];
		$sSQL = 'SELECT COUNT(1) AS Total FROM ' . $sTabla . ' WHERE saiu28idresponsable=' . $idTercero . ' AND saiu28estado IN (1,2,3,4)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando Mesa de Ayuda: ' . $sSQL . '<br>';
		}
		$tabla28 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla28) > 0) {
			$fila28 = $objDB->sf($tabla28);
			$iACargoMa = $iACargoMa + $fila28['Total'];
		}
		//Ahora las vencidas.
		$sSQL = 'SELECT COUNT(1) AS Total FROM ' . $sTabla . ' WHERE saiu28idresponsable=' . $idTercero . ' AND saiu28estado IN (1,2,3,4)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando Mesa de Ayuda: ' . $sSQL . '<br>';
		}
		$tabla28 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla28) > 0) {
			$fila28 = $objDB->sf($tabla28);
			$iACargoMa = $iACargoMa + $fila28['Total'];
		}
	}
	if (($iACargoMa + $iEnSupervisionMa) > 0) {
		if ($bHtml) {
			$bHayDatos = true;
			$sModulo = $ETI['titulo_3028'];
			if ($bLocal) {
				$sModulo = '<a href="saiumesaayuda.php">' . $sModulo . '</a>';
			}
			$sRes = $sRes . '<tr>
			<td>' . $sModulo . '</b></td>
			<td>' . $iACargoMa . '</td>
			<td>' . $iVencidasMa . '</td>
			<td>' . $iEnSupervisionMa . '</td>
			</tr>';
		}
		$iACargo = $iACargo + $iACargoMa;
		$iVencidas = $iVencidas + $iVencidasMa;
		$iEnSupervision = $iEnSupervision + $iEnSupervisionMa;
	}
	//Termina la busqueda.
	if ($bHayDatos) {
		$sIni = '<div class="table-responsive">
		<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
		<thead class="fondoazul"><tr>
		<td colspan="4" align="center"><b>' . $ETI['msg_asignacion_tercero'] . '</b></td>
		</tr><tr>
		<td><b>' . $ETI['msg_modulo'] . '</b></td>
		<td><b>' . $ETI['msg_asignadas'] . '</b></td>
		<td><b>' . $ETI['msg_vencidas'] . '</b></td>
		<td><b>' . $ETI['msg_supervisor'] . '</b></td>
		</tr></thead>';
		$sFin = '</table>
		<div class="salto5px"></div>
		</div>';
		$sRes = $sIni . $sRes . $sFin;
	}
	return array($sRes, $iEnProceso, $iACargo, $iVencidas, $iEnSupervision, $sDebug);
}
function f3000_NumSolicitud($iAgno, $iMes, $iConsec)
{
	$sRes = $iAgno . '-';
	$sRes = $sRes . formato_anchofijo($iMes, 2, '0');
	$sRes = $sRes . '-' . formato_anchofijo($iConsec, 8, '0');
	return $sRes;
}
function f3040_RegistrarNoticacion($saiu40idbasecon, $saiu40idtercero, $saiu40vianotifica, $objDB, $saiu40consec = 1, $bDebug = false)
{
	$iCodModulo = 3040;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	//$mensajes_3040=$APP->rutacomun.'lg/lg_3040_'.$_SESSION['unad_idioma'].'.php';
	//if (!file_exists($mensajes_3040)){$mensajes_3040=$APP->rutacomun.'lg/lg_3040_es.php';}
	require $mensajes_todas;
	//require $mensajes_3040;
	$sError = '';
	$sDebug = '';
	$bInserta = false;
	$iAccion = 3;
	$sSepara = ', ';
	$saiu40id = 0;
	//if ($saiu40vianotifica==''){$sError=$ERR['saiu40vianotifica'].$sSepara.$sError;}
	//if ($saiu40id==''){$sError=$ERR['saiu40id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu40consec==''){$sError=$ERR['saiu40consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu40idtercero == 0) {
		$sError = 'Sin tercero' . $sSepara . $sError;
	}
	if ($saiu40idbasecon == '') {
		$sError = 'Sin noticia' . $sSepara . $sError;
	}
	if ($sError == '') {
		list($idContTercero, $sError) = f1011_BloqueTercero($saiu40idtercero, $objDB);
	}
	if ($sError == '') {
		$sTabla40 = 'saiu40baseconotifica_' . $idContTercero;
		if ((int)$saiu40id == 0) {
			if ((int)$saiu40consec == 0) {
				$saiu40consec = tabla_consecutivo($sTabla40, 'saiu40consec', 'saiu40idbasecon=' . $saiu40idbasecon . ' AND saiu40idtercero=' . $saiu40idtercero . '', $objDB);
				if ($saiu40consec == -1) {
					$sError = $objDB->serror;
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla40 . ' WHERE saiu40idbasecon=' . $saiu40idbasecon . ' AND saiu40idtercero=' . $saiu40idtercero . ' AND saiu40consec=' . $saiu40consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				}
			}
			if ($sError == '') {
				$saiu40id = tabla_consecutivo('' . $sTabla40 . '', 'saiu40id', '', $objDB);
				if ($saiu40id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$saiu40fecha = fecha_DiaMod();
			$saiu40hora = fecha_hora();
			$saiu40min = fecha_minuto();
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos3040 = 'saiu40idbasecon, saiu40idtercero, saiu40consec, saiu40id, saiu40fecha, 
			saiu40hora, saiu40min, saiu40vianotifica';
			$sValores3040 = '' . $saiu40idbasecon . ', "' . $saiu40idtercero . '", ' . $saiu40consec . ', ' . $saiu40id . ', "' . $saiu40fecha . '", 
			' . $saiu40hora . ', ' . $saiu40min . ', ' . $saiu40vianotifica . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla40 . ' (' . $sCampos3040 . ') VALUES (' . utf8_encode($sValores3040) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla40 . ' (' . $sCampos3040 . ') VALUES (' . $sValores3040 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3040 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3040].<!-- ' . $sSQL . ' -->';
			}
		}
	}
	return array($sError, $sDebug);
}
//Iniciar dias
function f3000_IniciarDias($iDiaFin, $idGrupo, $objDB, $bDebug = false)
{
	$sDebug = '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Revisando que exista la fecha: ' . $iDiaFin . '<br>';
	}
	//Primero verificamos e dia.
	$sSQL = 'SELECT saiu67dia FROM saiu67dias WHERE saiu67dia=' . $iDiaFin . ' AND saiu67idgrupo=' . $idGrupo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 0) {
		$sCampo3067 = 'INSERT INTO saiu67dias (saiu67dia, saiu67idgrupo, saiu67diasem, saiu67habil, saiu67orden) VALUES ';
		$iDiaIni = 20210101;
		$iDiaSem = 5;
		$iOrden = 1;
		$iHabil = 0;
		$sSQL = 'SELECT saiu67dia, saiu67diasem, saiu67habil, saiu67orden FROM saiu67dias WHERE saiu67dia<' . $iDiaFin . ' AND saiu67idgrupo=' . $idGrupo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iDiaIni = fecha_NumSumarDias($fila['saiu67dia'], 1);
			$iDiaSem = $fila['saiu67diasem'] + 1;
			if ($iDiaSem > 6) {
				$iDiaSem = 0;
			}
			$iOrden = $fila['saiu67orden'];
			if (($iDiaSem > 0) && ($iDiaSem < 6)) {
				$iOrden++;
			}
			$iHabil = 1;
			if (($iDiaSem == 0) || ($iDiaSem == 6)) {
				$iHabil = 0;
			}
		}
		while ($iDiaIni < $iDiaFin) {
			//Insertamos el registro.
			$sSQL = $sCampo3067 . ' (' . $iDiaIni . ', ' . $idGrupo . ', ' . $iDiaSem . ', ' . $iHabil . ', ' . $iOrden . ')';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Agregando dia: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			//Siguiente dia.
			$iDiaIni = fecha_NumSumarDias($iDiaIni, 1);
			$iDiaSem++;
			if ($iDiaSem > 6) {
				$iDiaSem = 0;
			}
			if (($iDiaSem > 0) && ($iDiaSem < 6)) {
				$iOrden++;
			}
			$iHabil = 1;
			if (($iDiaSem == 0) || ($iDiaSem == 6)) {
				$iHabil = 0;
			}
		}
	}
	return array($sDebug);
}
function f3000_SumarDiasHabiles($iFecha, $iHora, $iMinuto, $iNumDias, $objDB, $idGrupo = 0)
{
	$iFechaRes = $iFecha;
	$iHoraRes = $iHora;
	$iMinRes = $iMinuto;
	//Primero traemos el dia orden...
	$sSQL = 'SELECT saiu67orden, saiu67habil FROM saiu67dias WHERE saiu67dia=' . $iFecha . ' AND saiu67idgrupo=' . $idGrupo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iOrden = $fila['saiu67orden'];
		if ($fila['saiu67habil'] == 0) {
			//$iOrden++;
			$iHoraRes = 18;
			$iMinRes = 0;
		} else {
			if ($iHora > 17) {
				//$iOrden++;
				$iHoraRes = 18;
				$iMinRes = 0;
			}
		}
		//Ahora ubicamos el siguiente orden.
		$iOrden = $iOrden + $iNumDias;
		//Ahora traemos la fecha que corresponde.
		$sSQL = 'SELECT saiu67dia FROM saiu67dias WHERE saiu67orden=' . $iOrden . ' AND saiu67idgrupo=' . $idGrupo . ' AND saiu67habil=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iFechaRes = $fila['saiu67dia'];
		}
	}
	return array($iFechaRes, $iHoraRes, $iMinRes);
}
//Calcular el tiempo limite
function f3000_CalcularFechaLimite($iTipoSol, $iFechaIni, $iHoraIni, $iMinIni, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iFecha = 0;
	$iHora = 0;
	$iMinuto = 0;
	$sSQL = 'SELECT TB.saiu03tiposol, TB.saiu03numetapas, TB.saiu03tiemprespdias1, TB.saiu03tiempresphoras1
	FROM saiu03temasol AS TB 
	WHERE TB.saiu03id=' . $iTipoSol . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Tipo de Solicitud: ' . $sSQL . '';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iDiasASumar = 0;
		$iHorasASumar = 0;
		if ($fila['saiu03tiemprespdias1'] != 0) {
			$iDiasASumar = $fila['saiu03tiemprespdias1'];
		} else {
			$iHorasASumar = $fila['saiu03tiempresphoras1'];
		}
		list($iFecha, $iHora, $iMinuto) = f3000_SumarDiasHabiles($iFechaIni, $iHoraIni, $iMinIni, $iDiasASumar, $objDB);
	}
	return array($iFecha, $iHora, $iMinuto, $sError, $sDebug);
}
//Responsables.
function f3000_Responsable($iTipoSol, $idTercero, $bSoloConsulta, $objDB, $bDebug = false)
{
	$idUnidad = 0;
	$idEquipo = 0;
	$idResponsable = 0;
	$sDebug = '';
	$sInfo = '';
	$bPorZona = false;
	$bPorCentro = false;
	$sSQL = 'SELECT TB.saiu03tiposol, TB.saiu03numetapas, TB.saiu03nometapa1, TB.saiu03idunidadresp1, TB.saiu03idequiporesp1, 
	TB.saiu03idliderrespon1, TB.saiu03tiemprespdias1, TB.saiu03tiempresphoras1, TB.saiu03nometapa2, TB.saiu03idunidadresp2, 
	TB.saiu03idequiporesp2, TB.saiu03idliderrespon2, TB.saiu03tiemprespdias2, TB.saiu03tiempresphoras2, TB.saiu03nometapa3, 
	TB.saiu03idunidadresp3, TB.saiu03idequiporesp3, TB.saiu03idliderrespon3, TB.saiu03tiemprespdias3, TB.saiu03tiempresphoras3, 
	TB.saiu03moduloasociado, TB.saiu03nivelrespuesta,  T17.saiu17nombre
	FROM saiu03temasol AS TB, saiu17nivelatencion AS T17 
	WHERE TB.saiu03id=' . $iTipoSol . ' AND TB.saiu03nivelrespuesta=T17.saiu17id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Tipo de Solicitud: ' . $sSQL . '';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idUnidad = $fila['saiu03idunidadresp1'];
		$idEquipo = $fila['saiu03idequiporesp1'];
		$idResponsable = $fila['saiu03idliderrespon1'];
		$iNivelRespuesta = $fila['saiu03nivelrespuesta'];
		//0 Directo a Unidad, 1 Nacional, 2 Zonal, 3 Centro
		if ($bSoloConsulta) {
			$sInfo = $sInfo . 'Nivel de repuesta: <b>' . cadena_notildes($fila['saiu17nombre']) . '</b>';
		}
		if ($idUnidad == 0) {
			$sInfo = $sInfo . '<br><span class="rojo">No se ha definido una unidad responsable para el tipo de solicitud</span>';
		}
		switch ($iNivelRespuesta) {
			case 0:
			case 1:
				break;
			case 2: //Ubicar la zona del requiriente
			case 3: //Ubicar el centro del requiriente
				if ($idTercero != 0) {
					list($idEscuela, $idPrograma, $idZona, $idCentro, $sDebug) = f111_ProgramaCentro($idTercero, $objDB, $bDebug);
					if ($idUnidad > 0) {
						if ($iNivelRespuesta == 2) {
							if ($idZona != 0) {
								$bPorZona = true;
							}
						} else {
							if ($idCentro != 0) {
								$bPorCentro = true;
							}
						}
					}
				}
				if ($bSoloConsulta) {
					if ($idTercero == 0) {
						$sLugar = 'zona';
						if ($iNivelRespuesta == 3) {
							$sLugar = 'centro';
						}
						$sInfo = $sInfo . '<br>Se requiere un usuario para establecer una ' . $sLugar . ' y saber a que unidad se asignar&aacute;';
					} else {
						if ($iNivelRespuesta == 2) {
							if ($idZona > 0) {
								$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $idZona . '';
								$tabla = $objDB->ejecutasql($sSQL);
								if ($objDB->nf($tabla) > 0) {
									$fila = $objDB->sf($tabla);
									$sInfo = $sInfo . '<br>Zona del usuario: <b>' . cadena_notildes($fila['unad23nombre']) . '</b>';
								}
							} else {
								$sInfo = $sInfo . '<br><span class="rojo">No ha sido posible determinar la zona al que el usuario esta asociado</span>';
							}
						} else {
							if ($idCentro > 0) {
								$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $idCentro . '';
								$tabla = $objDB->ejecutasql($sSQL);
								if ($objDB->nf($tabla) > 0) {
									$fila = $objDB->sf($tabla);
									$sInfo = $sInfo . '<br>Centro del usuario: <b>' . cadena_notildes($fila['unad24nombre']) . '</b>';
								}
							} else {
								$sInfo = $sInfo . '<br><span class="rojo">No ha sido posible determinar el centro al que el usuario esta asociado</span>';
							}
						}
					}
				}
				break;
		}
	} else {
		$sInfo = '<span class="rojo">No se ha encontrato el tipo de solicitud solicitado {Ref ' . $iTipoSol . '}</span>';
	}
	if ($bPorZona) {
		//Ubicar la unidad, si es fractal hay que ubicarla pero para la zona.
		$sSQL = 'SELECT unae26consec, unae26fractal, unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $idUnidad . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sNombreUnidad = cadena_notildes($fila['unae26nombre']);
			if ($fila['unae26fractal'] == 'S') {
				$sSQL = 'SELECT unae26id, unae26idresponsable 
				FROM unae26unidadesfun 
				WHERE unae26consec=' . $fila['unae26consec'] . ' AND unae26idzona=' . $idZona . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$idUnidad = $fila['unae26id'];
					if ($fila['unae26idresponsable'] != 0) {
						$idResponsable = $fila['unae26idresponsable'];
					}
					if ($bSoloConsulta) {
						$sInfo = $sInfo . '<br>Se envia al fractal de la unidad en la zona.</b>';
					}
				} else {
					if ($bSoloConsulta) {
						$sInfo = $sInfo . '<br>La unidad ' . $sNombreUnidad . ' NO tiene configurado un fractal para la zona.</b>';
					}
				}
			} else {
				if ($bSoloConsulta) {
					$sInfo = $sInfo . '<br>La unidad ' . $sNombreUnidad . ' responde todo de forma centralizada.</b>';
				}
			}
		}
	}
	if ($bPorCentro) {
		//Buscamos los equipos de trabajo de la unidad.
		$sSQL = 'SELECT bita27id, bita27idlider FROM bita27equipotrabajo WHERE bita27idunidadfunc=' . $idUnidad . ' AND bita27nivelrespuesta=3 AND bita27cead=' . $idCentro . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Equipos de trabajo de la unidad en centro: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idEquipo = $fila['bita27id'];
			$idResponsable = $fila['bita27idlider'];
			if ($bSoloConsulta) {
				$sInfo = $sInfo . '<br><span style="color:#FF6600"><b>Se envia al equipo de trabajo del centro</b></span>.</b>';
			}
		} else {
			//Ahora lo buscamos por la zona
			$sSQL = 'SELECT bita27id, bita27idlider FROM bita27equipotrabajo WHERE bita27idunidadfunc=' . $idUnidad . ' AND bita27nivelrespuesta=2 AND bita27idzona=' . $idZona . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Equipos de trabajo de la unidad en zona: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idEquipo = $fila['bita27id'];
				$idResponsable = $fila['bita27idlider'];
				if ($bSoloConsulta) {
					$sInfo = $sInfo . '<br><span style="color:#FF6600"><b>Se envia al equipo de trabajo de la zona</b></span> [No existe grupo de trabajo para el centro].</b>';
				}
			} else {
				//Vemos si hay un grupo de trabajo nacional...
				$sSQL = 'SELECT bita27id, bita27idlider FROM bita27equipotrabajo WHERE bita27idunidadfunc=' . $idUnidad . ' AND bita27nivelrespuesta=1';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Equipos de trabajo de la unidad a nivel nacional: ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$idEquipo = $fila['bita27id'];
					$idResponsable = $fila['bita27idlider'];
					if ($bSoloConsulta) {
						$sInfo = $sInfo . '<br><span style="color:#FF6600"><b>Se envia al equipo de trabajo de respuesta nacional</b></span> [No existe grupo de trabajo para el centro ni para la zona].</b>';
					}
				} else {
					$sNombreUnidad = '{' . $idUnidad . '}';
					$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $idUnidad . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$sNombreUnidad = cadena_notildes($fila['unae26nombre']);
					}
					if ($bSoloConsulta) {
						$sInfo = $sInfo . '<br>La unidad ' . $sNombreUnidad . ' <span class="rojo">No tiene grupos de trabajo configurados</span>.</b>';
					}
				}
			}
		}
	}
	if ($bSoloConsulta) {
		if ($idUnidad > 0) {
			$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $idUnidad . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sInfo = $sInfo . '<br>Unidad Responsable: <b>' . cadena_notildes($fila['unae26nombre']) . '</b>';
			}
		}
		if ($idEquipo > 0) {
			$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $idEquipo . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Equipo de trabajo: ' . $sSQL . '';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sInfo = $sInfo . '<br>Equipo Responsable: <b>' . cadena_notildes($fila['bita27nombre']) . '</b>';
			}
		} else {
			$sInfo = $sInfo . '<br><span class="rojo">No se ha definido el equipo de trabajo para esta solicitud.</span>.</b>';
		}
		if ($idResponsable > 0) {
			$sSQL = 'SELECT unad11razonsocial, unad11correoinstitucional FROM unad11terceros WHERE unad11id=' . $idResponsable . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sInfo = $sInfo . '<br>Lider Responsable: <b>' . cadena_notildes($fila['unad11razonsocial']) . '</b> ' . $fila['unad11correoinstitucional'] . '';
			}
		}
	}
	return array($idUnidad, $idEquipo, $idResponsable, $sInfo, $sDebug);
}
//
function f3000_InfoContacto($idTercero, $idUsuario, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_111 = $APP->rutacomun . 'lg/lg_111_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_111)) {
		$mensajes_111 = $APP->rutacomun . 'lg/lg_111_es.php';
	}
	require $mensajes_todas;
	require $mensajes_111;
	$sRes = '';
	$sPersonal = '';
	$sError = '';
	$sDebug = '';
	if ($idTercero > 0) {
		//13 de Julio de 2023 - Se considera que el tercero pudo haber solicita ley de olvido - Campo unad11estado en 3
		$sSQL = 'SELECT unad11telefono, unad11correo, unad11correoinstitucional, unad11correofuncionario, 
		unad11idtablero, unad11necesidadesp, unad11carariesgo, unad01autoriza_tel, unad01autoriza_bol, unad01autoriza_mat, 
		unad01autoriza_bien, unad11estado 
		FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$bPuedeConsultar = false;
			//13 de Agosto de 2021, se retira el permiso de consultar terceros y se agrega el boton para ver los datos.
			//list($bPuedeConsultar, $sDebugP)=seg_revisa_permisoV3(111, 1, $idUsuario, $objDB);
			if ($idTercero == $_SESSION['unad_id_tercero']) {
				$bPuedeConsultar = true;
			}
			if (!$bPuedeConsultar) {
				$sSQL = 'SELECT 1 FROM core04matricula_' . $fila['unad11idtablero'] . ' WHERE core04tercero=' . $idTercero . ' AND core04idtutor=' . $idUsuario . '';
				$tabla4 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla4) > 0) {
					$bPuedeConsultar = true;
				}
			}
			if ($fila['unad11estado'] == 3) {
				$bPuedeConsultar = false;
			}
			if ($bPuedeConsultar) {
				//, unad01autoriza_tel, unad01autoriza_mat, unad01autoriza_bien, unad01autoriza_bol
				list ($sComplementoTel, $sComplementoCorreo) = f236_Complementos($fila);
				$sPersonal = $ETI['unad11telefono'] . ' <b>' . $fila['unad11telefono'] . '</b>' . $sComplementoTel;
				if (correo_VerificarDireccion($fila['unad11correo'])) {
					if ($sPersonal != '') {
						$sPersonal = $sPersonal . '<br>';
					}
					$sPersonal = $sPersonal . ' ' . $ETI['unad11correo'] . ' <b>' . $fila['unad11correo'] . '</b>'.$sComplementoCorreo;
				}
			} else {
				if ($fila['unad11estado'] != 3) {
					$sPersonal = '<input id="cmdInfoPersonal" name="cmdInfoPersonal" type="button" class="BotonAzul160" value="Datos personales" onclick="verinfopersonal(' . $idTercero . ');" title="Ver datos personales"/>
					<div class="salto1px"></div>';
				}
			}
			if (correo_VerificarDireccion($fila['unad11correofuncionario'])) {
				if ($sRes != '') {
					$sRes = $sRes . '<br>';
				}
				$sRes = $sRes . '' . $ETI['unad11correofuncionario'] . ' <b>' . $fila['unad11correofuncionario'] . '</b>';
			} else {
				if (correo_VerificarDireccion($fila['unad11correoinstitucional'])) {
					if ($sRes != '') {
						$sRes = $sRes . '<br>';
					}
					$sRes = $sRes . '' . $ETI['unad11correoinstitucional'] . ' <b>' . $fila['unad11correoinstitucional'] . '</b>';
				}
			}
			if ($fila['unad11necesidadesp'] != '') {
				if ($sRes != '') {
					$sRes = $sRes . '<br>';
				}
				$sRes = $sRes . '' . $ETI['unad11necesidadesp'] . ' <b>' . $fila['unad11necesidadesp'] . '</b>';
			}
		} else {
			$sError = 'No se ha encontrado el documento REF {' . $idTercero . '}';
		}
	}
	if ($sError != '') {
		$sRes = '<span class="rojo">' . $sError . '</span>';
	}
	return array($sRes, $sPersonal, $sDebug);
}
function f3041_TablaInfoAcademico($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3041)) {
		$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3041;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$aParametros[104]=numeros_validar($aParametros[104]);
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$idEstudiante = trim($aParametros[103]);
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf304111" name="paginaf304111" type="hidden" value="' . $pagina . '"/>
	<input id="lppf304111" name="lppf304111" type="hidden" value="' . $lineastabla . '"/>';
	if ((int)$idEstudiante == 0) {
		$sLeyenda = 'No se ha seleccionado un estudiante.';
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	//Enero 30 de 2023 - agregamos e cuadro de ultima matricula para que se vea la modalidad. (libcore)
	list($res, $sDebugH) = f2216_HTMLInfoMatricula(0, $idEstudiante, $objDB, true, $bDebug);
	$sDebug = $sDebug . $sDebugH;
	// Termina de mostar los datos de matricula.
	$iBase = 0; //Nos basamos en los terceros.
	$sIds = '-99';
	$sTabla11 = '';
	$sTabla40 = '';
	$sCondiUnion = '';
	$sCondiUnion2 = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	$idConsejero = 0;
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	/*
	*/
	$sTitulos = 'Estudiante, Consec, Id, Tipocontacto, Fecha, Cerrada, Periodo, Curso, Actividad, Tutor, Visiblealest, Contacto_efectivo, Contacto_forma, Contacto_observa, Seretira, Factorprincipaldesc';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM unad11terceros AS TB
		WHERE unad11id IN (' . $sIds . ')';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	$sSQL = 'SELECT TB.core01idprograma, TB.core01idplandeestudios, T9.core09nombre, TB.core01avanceplanest, TB.core01contestado, 
	TB.core01idconsejero, TB.core01contestado, TB.core01id, TB.core01idestado, T2.core02nombre, TB.core01desc_idmodelo, 
	TB.core01desc_probabilidad, TB.core01desc_factorprob, T9.core09aplicacontinuidad 
	FROM core01estprograma AS TB, core09programa AS T9, core02estadoprograma AS T2
	WHERE TB.core01idtercero=' . $idEstudiante . ' AND TB.core01idprograma=T9.core09id AND TB.core01idestado=T2.core02id
	ORDER BY TB.core01fechainicio DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3041" name="consulta_3041" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3041" name="titulos_3041" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3041: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf304111" name="paginaf304111" type="hidden" value="'.$pagina.'"/><input id="lppf304111" name="lppf304111" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
			}
		}
	}
	$res = $res . $sErrConsulta . $sLeyenda;
	$res = $res . $sBotones . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		$bAplicaFactorDesercion = true;
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		switch ($filadet['core01idestado']) {
			case 9: //Retirado
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
		}
		if ($filadet['core09aplicacontinuidad'] == 0) {
			$bAplicaFactorDesercion = false;
		}
		$et_programa = '<b>' . cadena_notildes($filadet['core09nombre']) . '</b> ' . $sPrefijo . '[' . cadena_notildes($filadet['core02nombre']) . ']' . $sSufijo;
		$et_avance = formato_numero($filadet['core01avanceplanest'], 2) . ' %';
		$sSQL = 'SELECT core16fechamatricula, core16peraca 
		FROM core16actamatricula 
		WHERE core16tercero=' . $idEstudiante . ' AND core16idprograma=' . $filadet['core01idprograma'] . ' 
		ORDER BY core16peraca DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		$et_nummatriculas = 'N&deg; de matriculas: <b>' . $objDB->nf($tabla) . '</b>';
		$iTotal = 0;
		while ($fila = $objDB->sf($tabla)) {
			if ($iTotal < 3) {
				if ($fila['core16fechamatricula'] != 0) {
					$et_nummatriculas = $et_nummatriculas . ' ' . fecha_desdenumero($fila['core16fechamatricula']) . ' [' . $fila['core16peraca'] . ']';
				} else {
					$et_nummatriculas = $et_nummatriculas . ' Periodo ' . $fila['core16peraca'] . '';
				}
				$iTotal++;
			}
		}
		$sSQL = 'SELECT core16idconsejero FROM core16actamatricula 
		WHERE core16tercero=' . $idEstudiante . ' AND core16idprograma=' . $filadet['core01idprograma'] . ' AND core16idconsejero<>0
		ORDER BY core16peraca DESC LIMIT 0,1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consejero segun matricula: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idConsejero = $fila['core16idconsejero'];
		}
		if ($idConsejero == 0) {
			//Traer el consejero.
			$sSQL = 'SELECT cara01idconsejero FROM cara01encuesta WHERE cara01idtercero=' . $idEstudiante . ' AND cara01idprograma IN (0, ' . $filadet['core01idprograma'] . ')';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if ($fila['cara01idconsejero'] != 0) {
					$idConsejero = $fila['cara01idconsejero'];
				}
			}
		}
		if ($idConsejero == 0) {
			$idConsejero = $filadet['core01idconsejero'];
		}
		if ($idConsejero == 0) {
			list($iContenedor, $sErrorT) = f1011_BloqueTercero($idEstudiante, $objDB);
			$sSQL = 'SELECT TB.core04idtutor 
			FROM core04matricula_' . $iContenedor . ' AS TB, unad40curso AS T4 
			WHERE TB.core04tercero=' . $idEstudiante . ' AND TB.core04idcurso=T4.unad40id AND T4.unad40catedraunadista<>0
			ORDER BY TB.core04peraca DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idConsejero = $fila['core04idtutor'];
			}
		}
		$res = $res . '<tr class="fondoazul">
		<td colspan="2">' . $et_programa . '</td>
		<td>' . $et_nummatriculas . '</td>
		<td width="10%">' . $et_avance . '</td>
		</tr>';
		// 20 Abril 2024 - Ahora la probabilidad de deserción.
		if ($bAplicaFactorDesercion) {
			if ($filadet['core01desc_idmodelo'] > 0) {
				$sNomFactor = '';
				$sPrefPorc = '<b>';
				$sSuffPorc = '</b>';
				if ($filadet['core01desc_probabilidad'] > 0) {
					$sPrefPorc = '<span class="rojo">';
					$sSuffPorc = '</span>';
					$sNomFactor = ' {' . $filadet['core01desc_factorprob'] . '}';
					$sSQL = 'SELECT cara45titulo FROM cara45mldescfactor WHERE cara45id=' . $filadet['core01desc_factorprob'] . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$sNomFactor = ' - Factor: <b>' . cadena_notildes($fila['cara45titulo']) . '</b>';
					}
				}
				$res = $res . '<tr' . $sClass . '>
				<td colspan="4">' . 'Probabilidad de deserci&oacute;n' . ': ' . $sPrefPorc . formato_porcentaje($filadet['core01desc_probabilidad']) . $sSuffPorc . '</b> ' . $sNomFactor . '</td>
				</tr>';
			} else {
				$res = $res . '<tr' . $sClass . '>
				<td colspan="4">' . 'Pendiente por calcular la probabilidad de deserci&oacute;n' . '</td>
				</tr>';
			}
		} else {
			$res = $res . '<tr' . $sClass . '>
			<td colspan="4">' . 'El programa no aplica a c&aacute;lculo de probabilidad de deserci&oacute;n' . '</td>
			</tr>';
		}
		// Ahora el consejero...
		if ($idConsejero != 0) {
			$sSQL = 'SELECT unad11razonsocial, unad11correoinstitucional FROM unad11terceros WHERE unad11id=' . $idConsejero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$res = $res . '<tr' . $sClass . '>
				<td colspan="4">' . 'Consejero acad&eacute;mico' . ': <b>' . cadena_notildes($fila['unad11razonsocial']) . '</b> ' . $fila['unad11correoinstitucional'] . '</td>
				</tr>';
			}
		}
		if ($filadet['core01contestado'] != 0) {
			$html_continuidad = f2201_TablaContinuidad($filadet['core01id'], $objDB);
			$res = $res . '<tr' . $sClass . '>
			<td colspan="4">' . $html_continuidad . '</td>
			</tr>';
		}
	}
	$res = $res . '</table>';
	$res = $res . '<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f3041_HtmlTablaInfoAcademico($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$idTercero = $aParametros[100];
	$saiu41idestudiante = $aParametros[103];
	$html_personal = '';
	$html_InfoContacto = '';
	if ((int)$saiu41idestudiante != 0) {
		list($html_InfoContacto, $html_personal, $sDebugC) = f3000_InfoContacto($saiu41idestudiante, $idTercero, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugC;
	}
	list($sDetalle, $sDebugTabla) = f3041_TablaInfoAcademico($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Historico del estudiante.
	list($sTabla3042, $sDebugTabla) = f3042_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Historico por consejeria
	list($sTabla2323, $sDebugTabla) = f2323_TablaDetalleV2Res($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;

	$objCombos = new clsHtmlCombos();
	$html_saiu41idperiodo = f3041_HTMLComboV2_saiu41idperiodo($objDB, $objCombos, '', $saiu41idestudiante);
	$html_saiu41idcurso = f3041_HTMLComboV2_saiu41idcurso($objDB, $objCombos, '', '',  $saiu41idestudiante);
	$html_saiu41idactividad = f3041_HTMLComboV2_saiu41idactividad($objDB, $objCombos, '', '', '', $saiu41idestudiante);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_infopersonal', 'innerHTML', $html_personal);
	$objResponse->assign('div_contacto', 'innerHTML', $html_InfoContacto);
	$objResponse->assign('div_f3041academico', 'innerHTML', $sDetalle);
	$objResponse->assign('div_saiu41idperiodo', 'innerHTML', $html_saiu41idperiodo);
	$objResponse->assign('div_saiu41idcurso', 'innerHTML', $html_saiu41idcurso);
	$objResponse->assign('div_saiu41idactividad', 'innerHTML', $html_saiu41idactividad);
	$objResponse->assign('div_f3042detalle', 'innerHTML', $sTabla3042);
	$objResponse->assign('div_f2323detalleRes', 'innerHTML', $sTabla2323);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}

//
function f3046_HtmlComunicado($id46, $idTercero, $objDB, $bDegug = false)
{
	$sRes = '';
	$sDebug = '';
	$sSQL = 'SELECT saiu61cuerpo 
	FROM saiu61comunicados 
	WHERE saiu61id=' . $id46 . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		//Alistamos los datos del tercero.
		$sRazonSocial = '@Nombre';
		$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla11 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11) > 0) {
			$fila11 = $objDB->sf($tabla11);
			$sRazonSocial = $fila11['unad11razonsocial'];
		}
		$sCuerpoComunicado = nl2br($fila['saiu61cuerpo']);
		$sCuerpoComunicado = cadena_Reemplazar($sCuerpoComunicado, '|@NombreUsuario@|', cadena_notildes($sRazonSocial));
		/*
		$sCuerpoComunicado=cadena_Reemplazar($sCuerpoComunicado, '|@Documento@|', $fila['unad11tipodoc'].' '.$fila['unad11doc']);
		$sCuerpoComunicado=cadena_Reemplazar($sCuerpoComunicado, '|@ValorEnLetras@|', $sVrLetras);
		$sCuerpoComunicado=cadena_Reemplazar($sCuerpoComunicado, '|@Valor@|', formato_numero($fila['corf19valor'], 0));
		$sCuerpoComunicado=cadena_Reemplazar($sCuerpoComunicado, '|@BotonAceptar@|', $sBotonAceptar);
		*/
		$sCuerpoComunicado = cadena_Reemplazar($sCuerpoComunicado, '||', '<b>');
		$sCuerpoComunicado = cadena_Reemplazar($sCuerpoComunicado, '|.|', '</b>');
		$sRes = cadena_notildes($sCuerpoComunicado) . '' . $sRes;
	}
	return array($sRes, $sDebug);
}
function f3070_SeleccionaResponsable($idPaso, $idZona, $idCentro, $idEscuela, $idPrograma, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$idunidad = 0;
	$idgrupotrabajo = 0;
	$idresponsable = 0;
	$sSQL = 'SELECT saiu70idunidad, saiu70idgrupotrabajo, saiu70idresponsable
	FROM saiu70responsabletrami AS T
	WHERE T.saiu70numpaso = ' . $idPaso . '
	AND (T.saiu70idzona = 0 OR T.saiu70idzona = ' . $idZona . ')
	AND (T.saiu70idcentro = 0 OR T.saiu70idcentro = ' . $idCentro . ')
	AND (T.saiu70idescuela = 0 OR T.saiu70idescuela = ' . $idEscuela . ')
	AND (T.saiu70idprograma = 0 OR T.saiu70idprograma = ' . $idPrograma . ')
	ORDER BY T.saiu70idzona DESC, T.saiu70idcentro DESC, 
	T.saiu70idescuela DESC, T.saiu70idprograma DESC 
	LIMIT 0,1' . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable tramite ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idunidad = $fila['saiu70idunidad'];
		$idgrupotrabajo = $fila['saiu70idgrupotrabajo'];
		$idresponsable = $fila['saiu70idresponsable'];
	} else {
		$sError = 'No ha sido posible determinar un responsable para la el tr&aacute;mite.<br>';
		$sError = $sError . 'SAI - Configurar - Responsables de tr&aacute;mites<br>';
		$sError = $sError . 'Etapa: ' . $idPaso . ' Zona: ' . $idZona . ' Centro: ' . $idCentro . ' Escuela: ' . $idEscuela . ' Programa: ' . $idPrograma . '';
	}
	return array($idunidad, $idgrupotrabajo, $idresponsable, $sError, $sDebug);
}
//Esta funcion no no se debe usar
function f12206_db_GuardarV2_Depreciar($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 12206;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_12206;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['corf06tiponov'])==0){$DATA['corf06tiponov']='';}
	if (isset($DATA['corf06consec'])==0){$DATA['corf06consec']='';}
	if (isset($DATA['corf06id'])==0){$DATA['corf06id']='';}
	if (isset($DATA['corf06estado'])==0){$DATA['corf06estado']='';}
	if (isset($DATA['corf06idestudiante'])==0){$DATA['corf06idestudiante']='';}
	if (isset($DATA['corf06idperiodo'])==0){$DATA['corf06idperiodo']='';}
	if (isset($DATA['corf06idescuela'])==0){$DATA['corf06idescuela']='';}
	if (isset($DATA['corf06idprograma'])==0){$DATA['corf06idprograma']='';}
	if (isset($DATA['corf06fecha'])==0){$DATA['corf06fecha']='';}
	if (isset($DATA['corf06hora'])==0){$DATA['corf06hora']='';}
	if (isset($DATA['corf06min'])==0){$DATA['corf06min']='';}
	if (isset($DATA['corf06fechaplica'])==0){$DATA['corf06fechaplica']='';}
	if (isset($DATA['corf06idzona'])==0){$DATA['corf06idzona']='';}
	if (isset($DATA['corf06idcentro'])==0){$DATA['corf06idcentro']='';}
	if (isset($DATA['corf06idzonadest'])==0){$DATA['corf06idzonadest']='';}
	if (isset($DATA['corf06idcentrodest'])==0){$DATA['corf06idcentrodest']='';}
	*/
	$DATA['corf06tiponov'] = numeros_validar($DATA['corf06tiponov']);
	$DATA['corf06consec'] = numeros_validar($DATA['corf06consec']);
	$DATA['corf06idperiodo'] = numeros_validar($DATA['corf06idperiodo']);
	$DATA['corf06idescuela'] = numeros_validar($DATA['corf06idescuela']);
	$DATA['corf06idprograma'] = numeros_validar($DATA['corf06idprograma']);
	$DATA['corf06hora'] = numeros_validar($DATA['corf06hora']);
	$DATA['corf06min'] = numeros_validar($DATA['corf06min']);
	$DATA['corf06horaaplica'] = numeros_validar($DATA['corf06horaaplica']);
	$DATA['corf06minaplica'] = numeros_validar($DATA['corf06minaplica']);
	$DATA['corf06idzona'] = numeros_validar($DATA['corf06idzona']);
	$DATA['corf06idcentro'] = numeros_validar($DATA['corf06idcentro']);
	$DATA['corf06idzonadest'] = numeros_validar($DATA['corf06idzonadest']);
	$DATA['corf06idcentrodest'] = numeros_validar($DATA['corf06idcentrodest']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['corf06estado'] == '') {
		$DATA['corf06estado'] = 0;
	}
	//if ($DATA['corf06idperiodo']==''){$DATA['corf06idperiodo']=0;}
	//if ($DATA['corf06idescuela']==''){$DATA['corf06idescuela']=0;}
	//if ($DATA['corf06idprograma']==''){$DATA['corf06idprograma']=0;}
	//if ($DATA['corf06hora']==''){$DATA['corf06hora']=0;}
	//if ($DATA['corf06min']==''){$DATA['corf06min']=0;}
	//if ($DATA['corf06horaaplica']==''){$DATA['corf06horaaplica']=0;}
	//if ($DATA['corf06minaplica']==''){$DATA['corf06minaplica']=0;}
	//if ($DATA['corf06idzona']==''){$DATA['corf06idzona']=0;}
	//if ($DATA['corf06idcentro']==''){$DATA['corf06idcentro']=0;}
	//if ($DATA['corf06idzonadest']==''){$DATA['corf06idzonadest']=0;}
	//if ($DATA['corf06idcentrodest']==''){$DATA['corf06idcentrodest']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['corf06estado'] == 'S') {
		if ($DATA['corf06idcentrodest'] == '') {
			$sError = $ERR['corf06idcentrodest'] . $sSepara . $sError;
		}
		if ($DATA['corf06idzonadest'] == '') {
			$sError = $ERR['corf06idzonadest'] . $sSepara . $sError;
		}
		if ($DATA['corf06idcentro'] == '') {
			$sError = $ERR['corf06idcentro'] . $sSepara . $sError;
		}
		if ($DATA['corf06idzona'] == '') {
			$sError = $ERR['corf06idzona'] . $sSepara . $sError;
		}
		if (!fecha_esvalida($DATA['corf06fechaplica'])) {
			//$DATA['corf06fechaplica']='00/00/0000';
			$sError = $ERR['corf06fechaplica'] . $sSepara . $sError;
		}
		if ($DATA['corf06autoriza2'] == 0) {
			$sError = $ERR['corf06autoriza2'] . $sSepara . $sError;
		}
		if ($DATA['corf06autoriza1'] == 0) {
			$sError = $ERR['corf06autoriza1'] . $sSepara . $sError;
		}
		if ($DATA['corf06min'] == '') {
			$sError = $ERR['corf06min'] . $sSepara . $sError;
		}
		if ($DATA['corf06hora'] == '') {
			$sError = $ERR['corf06hora'] . $sSepara . $sError;
		}
		if ($DATA['corf06fecha'] == 0) {
			//$DATA['corf06fecha']=fecha_DiaMod();
			$sError = $ERR['corf06fecha'] . $sSepara . $sError;
		}
		if ($DATA['corf06idprograma'] == '') {
			$sError = $ERR['corf06idprograma'] . $sSepara . $sError;
		}
		if ($DATA['corf06idescuela'] == '') {
			$sError = $ERR['corf06idescuela'] . $sSepara . $sError;
		}
		if ($DATA['corf06idperiodo'] == '') {
			$sError = $ERR['corf06idperiodo'] . $sSepara . $sError;
		}
		if ($DATA['corf06idestudiante'] == 0) {
			$sError = $ERR['corf06idestudiante'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['corf06estado'] = 'N';
		}
		$sErrorCerrando = $sError;
		$sError = '';
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['corf06tiponov'] == '') {
		$sError = $ERR['corf06tiponov'];
	}
	// -- Tiene un cerrado.
	if ($DATA['corf06estado'] == 1) {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError . $sErrorCerrando != '') {
			$DATA['corf06estado'] = 1;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['corf06autoriza2_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['corf06autoriza2_td'], $DATA['corf06autoriza2_doc'], $objDB, 'El tercero Autoriza2 ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['corf06autoriza2'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['corf06autoriza1_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['corf06autoriza1_td'], $DATA['corf06autoriza1_doc'], $objDB, 'El tercero Autoriza1 ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['corf06autoriza1'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['corf06idestudiante_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['corf06idestudiante_td'], $DATA['corf06idestudiante_doc'], $objDB, 'El tercero Estudiante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['corf06idestudiante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['corf06consec'] == '') {
				$DATA['corf06consec'] = tabla_consecutivo('corf06novedad', 'corf06consec', 'corf06tiponov=' . $DATA['corf06tiponov'] . '', $objDB);
				if ($DATA['corf06consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'corf06consec';
			} else {
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
					$sError = $ERR['8'];
					$DATA['corf06consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM corf06novedad WHERE corf06tiponov=' . $DATA['corf06tiponov'] . ' AND corf06consec=' . $DATA['corf06consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$sError = $ERR['2'];
					}
				}
			}
		} else {
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['corf06id'] = tabla_consecutivo('corf06novedad', 'corf06id', '', $objDB);
			if ($DATA['corf06id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			$DATA['corf06estado'] = 0;
			$corf06fecha = fecha_DiaMod();
			//$DATA['corf06autoriza1']=0; //$_SESSION['u_idtercero'];
			//$DATA['corf06autoriza2']=0; //$_SESSION['u_idtercero'];
			$DATA['corf06fechaplica'] = 0;
			$DATA['corf06horaaplica'] = 0;
			$DATA['corf06minaplica'] = 0;
			$DATA['corf06idsesion'] = 0;
			$sCampos12206 = 'corf06tiponov, corf06consec, corf06id, corf06estado, corf06idestudiante, 
			corf06idperiodo, corf06idescuela, corf06idprograma, corf06fecha, corf06hora, 
			corf06min, corf06autoriza1, corf06autoriza2, corf06fechaplica, corf06horaaplica, 
			corf06minaplica, corf06idsesion, corf06idzona, corf06idcentro, corf06idzonadest, 
			corf06idcentrodest';
			$sValores12206 = '' . $DATA['corf06tiponov'] . ', ' . $DATA['corf06consec'] . ', ' . $DATA['corf06id'] . ', "' . $DATA['corf06estado'] . '", ' . $DATA['corf06idestudiante'] . ', 
			' . $DATA['corf06idperiodo'] . ', ' . $DATA['corf06idescuela'] . ', ' . $DATA['corf06idprograma'] . ', "' . $DATA['corf06fecha'] . '", ' . $DATA['corf06hora'] . ', 
			' . $DATA['corf06min'] . ', ' . $DATA['corf06autoriza1'] . ', ' . $DATA['corf06autoriza2'] . ', "' . $DATA['corf06fechaplica'] . '", ' . $DATA['corf06horaaplica'] . ', 
			' . $DATA['corf06minaplica'] . ', ' . $DATA['corf06idsesion'] . ', ' . $DATA['corf06idzona'] . ', ' . $DATA['corf06idcentro'] . ', ' . $DATA['corf06idzonadest'] . ', 
			' . $DATA['corf06idcentrodest'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO corf06novedad (' . $sCampos12206 . ') VALUES (' . utf8_encode($sValores12206) . ');';
				$sdetalle = $sCampos12206 . '[' . utf8_encode($sValores12206) . ']';
			} else {
				$sSQL = 'INSERT INTO corf06novedad (' . $sCampos12206 . ') VALUES (' . $sValores12206 . ');';
				$sdetalle = $sCampos12206 . '[' . $sValores12206 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'corf06estado';
			$scampo[2] = 'corf06idestudiante';
			$scampo[3] = 'corf06idperiodo';
			$scampo[4] = 'corf06idescuela';
			$scampo[5] = 'corf06idprograma';
			$scampo[6] = 'corf06fecha';
			$scampo[7] = 'corf06hora';
			$scampo[8] = 'corf06min';
			$scampo[9] = 'corf06fechaplica';
			$scampo[10] = 'corf06idzona';
			$scampo[11] = 'corf06idcentro';
			$scampo[12] = 'corf06idzonadest';
			$scampo[13] = 'corf06idcentrodest';
			$sdato[1] = $DATA['corf06estado'];
			$sdato[2] = $DATA['corf06idestudiante'];
			$sdato[3] = $DATA['corf06idperiodo'];
			$sdato[4] = $DATA['corf06idescuela'];
			$sdato[5] = $DATA['corf06idprograma'];
			$sdato[6] = $DATA['corf06fecha'];
			$sdato[7] = $DATA['corf06hora'];
			$sdato[8] = $DATA['corf06min'];
			$sdato[9] = $DATA['corf06fechaplica'];
			$sdato[10] = $DATA['corf06idzona'];
			$sdato[11] = $DATA['corf06idcentro'];
			$sdato[12] = $DATA['corf06idzonadest'];
			$sdato[13] = $DATA['corf06idcentrodest'];
			$numcmod = 13;
			$sWhere = 'corf06id=' . $DATA['corf06id'] . '';
			$sSQL = 'SELECT * FROM corf06novedad WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $numcmod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $numcmod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE corf06novedad SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE corf06novedad SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bpasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12206 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [12206] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['corf06id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['corf06id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		if ($DATA['paso'] == 10) {
			$DATA['paso'] = 0;
		} else {
			$DATA['paso'] = 2;
		}
		$bCerrando = false;
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	$sInfoCierre = '';
	if ($bCerrando) {
		list($sErrorCerrando, $sDebugCerrar) = f12206_Cerrar($DATA['corf06id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
/////
function f12206_AdicionarNota($corf06id, $corf08nota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	require './app.php';
	$corf08fecha = fecha_DiaMod();
	$corf08hora = fecha_hora();
	$corf08min = fecha_minuto();
	$corf08idusuario = $_SESSION['unad_id_tercero'];
	$corf08consec = tabla_consecutivo('corf08novedadnota', 'corf08consec', 'corf08idnovedad=' . $corf06id . '', $objDB);
	$corf08id = tabla_consecutivo('corf08novedadnota', 'corf08id', '', $objDB);
	$sCampos12208 = 'corf08idnovedad, corf08consec, corf08id, corf08fecha, corf08hora, 
	corf08min, corf08nota, corf08idorigenanexo, corf08idarchivoanexo, corf08idusuario';
	$sValores12208 = '' . $corf06id . ', ' . $corf08consec . ', ' . $corf08id . ', ' . $corf08fecha . ', ' . $corf08hora . ', 
	' . $corf08min . ', "' . $corf08nota . '", 0, 0, ' . $corf08idusuario . '';
	if ($APP->utf8 == 1) {
		$sSQL = 'INSERT INTO corf08novedadnota (' . $sCampos12208 . ') VALUES (' . utf8_encode($sValores12208) . ');';
	} else {
		$sSQL = 'INSERT INTO corf08novedadnota (' . $sCampos12208 . ') VALUES (' . $sValores12208 . ');';
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12208 ' . $sSQL . '<br>';
	}
	$result = $objDB->ejecutasql($sSQL);
	return array($sError, $sDebug);
}
function f12206_RegistrarNovedad($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 12206;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	require $mensajes_todas;
	require $mensajes_12206;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($DATA['corf06consec']) == 0) {
		$DATA['corf06consec'] = '';
	}
	if (isset($DATA['corf06id']) == 0) {
		$DATA['corf06id'] = '';
	}
	if (isset($DATA['paso']) == 0) {
		$DATA['paso'] = 10;
	}
	/*
	if (isset($DATA['corf06tiponov'])==0){$DATA['corf06tiponov']='';}
	if (isset($DATA['corf06estado'])==0){$DATA['corf06estado']='';}
	if (isset($DATA['corf06idestudiante'])==0){$DATA['corf06idestudiante']='';}
	if (isset($DATA['corf06idperiodo'])==0){$DATA['corf06idperiodo']='';}
	if (isset($DATA['corf06idescuela'])==0){$DATA['corf06idescuela']='';}
	if (isset($DATA['corf06idprograma'])==0){$DATA['corf06idprograma']='';}
	if (isset($DATA['corf06fecha'])==0){$DATA['corf06fecha']='';}
	if (isset($DATA['corf06min'])==0){$DATA['corf06min']='';}
	if (isset($DATA['corf06fechaplica'])==0){$DATA['corf06fechaplica']='';}
	if (isset($DATA['corf06idzona'])==0){$DATA['corf06idzona']='';}
	if (isset($DATA['corf06idcentro'])==0){$DATA['corf06idcentro']='';}
	*/
	if (isset($DATA['corf06autoriza1']) == 0) {
		$DATA['corf06autoriza1'] = 0;
	}
	if (isset($DATA['corf06autoriza2']) == 0) {
		$DATA['corf06autoriza2'] = 0;
	}
	if (isset($DATA['corf06idzonadest']) == 0) {
		$DATA['corf06idzonadest'] = 0;
	}
	if (isset($DATA['corf06idcentrodest']) == 0) {
		$DATA['corf06idcentrodest'] = 0;
	}
	if (isset($DATA['corf06idestprograma']) == 0) {
		$DATA['corf06idestprograma'] = 0;
	}
	$DATA['corf06tiponov'] = numeros_validar($DATA['corf06tiponov']);
	$DATA['corf06consec'] = numeros_validar($DATA['corf06consec']);
	$DATA['corf06idperiodo'] = numeros_validar($DATA['corf06idperiodo']);
	$DATA['corf06idescuela'] = numeros_validar($DATA['corf06idescuela']);
	$DATA['corf06idprograma'] = numeros_validar($DATA['corf06idprograma']);
	$DATA['corf06hora'] = numeros_validar($DATA['corf06hora']);
	$DATA['corf06min'] = numeros_validar($DATA['corf06min']);
	//$DATA['corf06horaaplica']=numeros_validar($DATA['corf06horaaplica']);
	//$DATA['corf06minaplica']=numeros_validar($DATA['corf06minaplica']);
	$DATA['corf06idzona'] = numeros_validar($DATA['corf06idzona']);
	$DATA['corf06idcentro'] = numeros_validar($DATA['corf06idcentro']);
	$DATA['corf06idzonadest'] = numeros_validar($DATA['corf06idzonadest']);
	$DATA['corf06idcentrodest'] = numeros_validar($DATA['corf06idcentrodest']);
	$DATA['corf06idestprograma'] = numeros_validar($DATA['corf06idestprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['corf06idperiodo']==''){$DATA['corf06idperiodo']=0;}
	//if ($DATA['corf06idescuela']==''){$DATA['corf06idescuela']=0;}
	//if ($DATA['corf06idprograma']==''){$DATA['corf06idprograma']=0;}
	//if ($DATA['corf06hora']==''){$DATA['corf06hora']=0;}
	//if ($DATA['corf06min']==''){$DATA['corf06min']=0;}
	//if ($DATA['corf06horaaplica']==''){$DATA['corf06horaaplica']=0;}
	//if ($DATA['corf06minaplica']==''){$DATA['corf06minaplica']=0;}
	//if ($DATA['corf06idzona']==''){$DATA['corf06idzona']=0;}
	//if ($DATA['corf06idcentro']==''){$DATA['corf06idcentro']=0;}
	if ($DATA['corf06autoriza1'] == '') {
		$DATA['corf06autoriza1'] = 0;
	}
	if ($DATA['corf06autoriza2'] == '') {
		$DATA['corf06autoriza2'] = 0;
	}
	if ($DATA['corf06idzonadest'] == '') {
		$DATA['corf06idzonadest'] = 0;
	}
	if ($DATA['corf06idcentrodest'] == '') {
		$DATA['corf06idcentrodest'] = 0;
	}
	if ($DATA['corf06idestprograma'] == '') {
		$DATA['corf06idestprograma'] = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['corf06idcentro'] == '') {
		$sError = $ERR['corf06idcentro'] . $sSepara . $sError;
	}
	if ($DATA['corf06idzona'] == '') {
		$sError = $ERR['corf06idzona'] . $sSepara . $sError;
	}
	if ($DATA['corf06idprograma'] == '') {
		$sError = $ERR['corf06idprograma'] . $sSepara . $sError;
	}
	if ($DATA['corf06idescuela'] == '') {
		$sError = $ERR['corf06idescuela'] . $sSepara . $sError;
	}
	if ($DATA['corf06idperiodo'] == '') {
		$sError = $ERR['corf06idperiodo'] . $sSepara . $sError;
	}
	if ($DATA['corf06idestudiante'] == 0) {
		$sError = $ERR['corf06idestudiante'] . $sSepara . $sError;
	}
	switch ($DATA['corf06tiponov']) {
		case 1: //Aplazamiento de semestre.
		case 2: //Cancelar semestre.
		case 3: //Aplazamiento de curso.
		case 4: //Cancelación de curso
		case 7: //Aplazamiento extemporaneo.
			break;
		case 6: //Cambio de centro.
			if ((int)$DATA['corf06idestprograma'] == 0) {
				$sError = $ERR['corf06idestprograma'] . $sSepara . $sError;
			}
			if ($DATA['corf06idcentrodest'] == '') {
				$sError = $ERR['corf06idcentrodest'] . $sSepara . $sError;
			}
			if ($DATA['corf06idzonadest'] == '') {
				$sError = $ERR['corf06idzonadest'] . $sSepara . $sError;
			}
			break;
		default:
			$sError = 'Tipo de novedad no permitida';
			break;
	}
	if (false) {
		if (!fecha_esvalida($DATA['corf06fechaplica'])) {
			//$DATA['corf06fechaplica']='00/00/0000';
			$sError = $ERR['corf06fechaplica'] . $sSepara . $sError;
		}
		if ($DATA['corf06autoriza2'] == 0) {
			$sError = $ERR['corf06autoriza2'] . $sSepara . $sError;
		}
		if ($DATA['corf06autoriza1'] == 0) {
			$sError = $ERR['corf06autoriza1'] . $sSepara . $sError;
		}
		if ($DATA['corf06min'] == '') {
			$sError = $ERR['corf06min'] . $sSepara . $sError;
		}
		if ($DATA['corf06hora'] == '') {
			$sError = $ERR['corf06hora'] . $sSepara . $sError;
		}
		if ($DATA['corf06fecha'] == 0) {
			//$DATA['corf06fecha']=fecha_DiaMod();
			$sError = $ERR['corf06fecha'] . $sSepara . $sError;
		}
		$sError = '';
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$DATA['corf06consec'] = tabla_consecutivo('corf06novedad', 'corf06consec', 'corf06tiponov=' . $DATA['corf06tiponov'] . '', $objDB);
			if ($DATA['corf06consec'] == -1) {
				$sError = $objDB->serror;
			}
			$bQuitarCodigo = true;
			$sCampoCodigo = 'corf06consec';
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM corf06novedad WHERE corf06tiponov=' . $DATA['corf06tiponov'] . ' AND corf06consec=' . $DATA['corf06consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				}
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['corf06id'] = tabla_consecutivo('corf06novedad', 'corf06id', '', $objDB);
			if ($DATA['corf06id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			switch ($DATA['corf06tiponov']) {
				case 1: //Aplazamiento de semestre.
				case 2: //Cancelar semestre.
				case 3: //Aplazamiento de curso.
				case 4: //Cancelación de curso
					$DATA['corf06fechaplica'] = fecha_DiaMod();
					$DATA['corf06horaaplica'] = fecha_hora();
					$DATA['corf06minaplica'] = fecha_minuto();
					break;
				default:
					$DATA['corf06fechaplica'] = 0;
					$DATA['corf06horaaplica'] = 0;
					$DATA['corf06minaplica'] = 0;
					break;
			}
			$DATA['corf06idsesion'] = $_SESSION['unad_id_sesion'];
			$sCampos12206 = 'corf06tiponov, corf06consec, corf06id, corf06estado, corf06idestudiante, 
			corf06idperiodo, corf06idescuela, corf06idprograma, corf06fecha, corf06hora, 
			corf06min, corf06autoriza1, corf06autoriza2, corf06fechaplica, corf06horaaplica, 
			corf06minaplica, corf06idsesion, corf06idzona, corf06idcentro, corf06idzonadest, 
			corf06idcentrodest, corf06idestprograma';
			$sValores12206 = '' . $DATA['corf06tiponov'] . ', ' . $DATA['corf06consec'] . ', ' . $DATA['corf06id'] . ', "' . $DATA['corf06estado'] . '", ' . $DATA['corf06idestudiante'] . ', 
			' . $DATA['corf06idperiodo'] . ', ' . $DATA['corf06idescuela'] . ', ' . $DATA['corf06idprograma'] . ', "' . $DATA['corf06fecha'] . '", ' . $DATA['corf06hora'] . ', 
			' . $DATA['corf06min'] . ', ' . $DATA['corf06autoriza1'] . ', ' . $DATA['corf06autoriza2'] . ', "' . $DATA['corf06fechaplica'] . '", ' . $DATA['corf06horaaplica'] . ', 
			' . $DATA['corf06minaplica'] . ', ' . $DATA['corf06idsesion'] . ', ' . $DATA['corf06idzona'] . ', ' . $DATA['corf06idcentro'] . ', ' . $DATA['corf06idzonadest'] . ', 
			' . $DATA['corf06idcentrodest'] . ', ' . $DATA['corf06idestprograma'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO corf06novedad (' . $sCampos12206 . ') VALUES (' . utf8_encode($sValores12206) . ');';
				$sdetalle = $sCampos12206 . '[' . utf8_encode($sValores12206) . ']';
			} else {
				$sSQL = 'INSERT INTO corf06novedad (' . $sCampos12206 . ') VALUES (' . $sValores12206 . ');';
				$sdetalle = $sCampos12206 . '[' . $sValores12206 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		}
		if ($bpasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12206 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [12206] ..<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['corf06id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f12206_AplazarSemestre($id16, $sNota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 1;
	$DATA['corf06estado'] = 7;
	//Revisar la matricula para el periodo.
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, TB.core16idcead, 
	T11.unad11doc, T11.unad11usuario 
	FROM core16actamatricula AS TB, unad11terceros AS T11 
	WHERE TB.core16id=' . $id16 . ' AND TB.core16tercero=T11.unad11id';
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core16tercero'];
		$idPeriodo = $fila16['core16peraca'];
		$sDocumento = $fila16['unad11doc'];
		$sUsuario = $fila16['unad11usuario'];
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core16idescuela'];
		$DATA['corf06idprograma'] = $fila16['core16idprograma'];
		$DATA['corf06idzona'] = $fila16['core16idzona'];
		$DATA['corf06idcentro'] = $fila16['core16idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 2;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		$sListaCursos = '';
		//Agregarle todos los cursos
		list($iContenedor, $sErrorT) = f1011_BloqueTercero($idEstudiante, $objDB);
		$sSQL = 'SELECT TB.core04idcurso 
		FROM core04matricula_' . $iContenedor . ' AS TB 
		WHERE TB.core04tercero=' . $idEstudiante . ' AND TB.core04peraca=' . $idPeriodo . ' AND TB.core04estado IN (0,2,5) AND TB.core04idmatricula IN (0,' . $id16 . ')';
		$tabla4 = $objDB->ejecutasql($sSQL);
		while ($fila4 = $objDB->sf($tabla4)) {
			if ($sListaCursos != '') {
				$sListaCursos = $sListaCursos . '@';
			}
			$sListaCursos = $sListaCursos . '' . $fila4['core04idcurso'];
			$sValores12207 = '' . $corf06id . ', ' . $fila4['core04idcurso'] . ', ' . $corf07id . ', ' . $corf07tipo . '';
			$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				//@@@ Fallo... que haremos... reversar???
			} else {
				$corf07id++;
			}
		}
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
		$sErrorS = frca_AplazarSemestre($sDocumento, $idPeriodo, $sListaCursos);
	}
	return array($corf06id, $sError, $sDebug);
}
function f12206_CancelarSemestre($id16, $sNota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 2;
	$DATA['corf06estado'] = 7;
	//Revisar la matricula para el periodo.
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, TB.core16idcead, 
	T11.unad11doc, T11.unad11usuario 
	FROM core16actamatricula AS TB, unad11terceros AS T11 
	WHERE TB.core16id=' . $id16 . ' AND TB.core16tercero=T11.unad11id';
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core16tercero'];
		$idPeriodo = $fila16['core16peraca'];
		$sDocumento = $fila16['unad11doc'];
		$sUsuario = $fila16['unad11usuario'];
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core16idescuela'];
		$DATA['corf06idprograma'] = $fila16['core16idprograma'];
		$DATA['corf06idzona'] = $fila16['core16idzona'];
		$DATA['corf06idcentro'] = $fila16['core16idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 1;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		$sListaCursos = '';
		//Agregarle todos los cursos
		list($iContenedor, $sErrorT) = f1011_BloqueTercero($idEstudiante, $objDB);
		$sSQL = 'SELECT TB.core04idcurso 
		FROM core04matricula_' . $iContenedor . ' AS TB 
		WHERE TB.core04tercero=' . $idEstudiante . ' AND TB.core04peraca=' . $idPeriodo . ' AND TB.core04estado IN (0,2,5) AND TB.core04idmatricula IN (0,' . $id16 . ')';
		$tabla4 = $objDB->ejecutasql($sSQL);
		while ($fila4 = $objDB->sf($tabla4)) {
			if ($sListaCursos != '') {
				$sListaCursos = $sListaCursos . '@';
			}
			$sListaCursos = $sListaCursos . '' . $fila4['core04idcurso'];
			$sValores12207 = '' . $corf06id . ', ' . $fila4['core04idcurso'] . ', ' . $corf07id . ', ' . $corf07tipo . '';
			$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				//@@@ Fallo... que haremos... reversar???
			} else {
				$corf07id++;
			}
		}
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
		$sErrorS = frca_CancelarSemestre($sDocumento, $idPeriodo, $sListaCursos);
	}
	return array($corf06id, $sError, $sDebug);
}
function f12206_AplazarCurso($id16, $idCurso, $sNota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 3;
	$DATA['corf06estado'] = 7;
	//Revisar la matricula para el periodo.
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, TB.core16idcead, 
	T11.unad11doc, T11.unad11usuario 
	FROM core16actamatricula AS TB, unad11terceros AS T11 
	WHERE TB.core16id=' . $id16 . ' AND TB.core16tercero=T11.unad11id';
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core16tercero'];
		$idPeriodo = $fila16['core16peraca'];
		$sDocumento = $fila16['unad11doc'];
		$sUsuario = $fila16['unad11usuario'];
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core16idescuela'];
		$DATA['corf06idprograma'] = $fila16['core16idprograma'];
		$DATA['corf06idzona'] = $fila16['core16idzona'];
		$DATA['corf06idcentro'] = $fila16['core16idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 2;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		//Agregarle el curso
		$sValores12207 = '' . $corf06id . ', ' . $idCurso . ', ' . $corf07id . ', ' . $corf07tipo . '';
		$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			//@@@ Fallo... que haremos... reversar???
		}
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
		$sErrorS = frca_AplazarCurso($sDocumento, $idPeriodo, $idCurso, $sUsuario);
	}
	return array($corf06id, $sError, $sDebug);
}
function f12206_CancelarCurso($id16, $idCurso, $sNota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 4;
	$DATA['corf06estado'] = 7;
	//Revisar la matricula para el periodo.
	$sSQL = 'SELECT TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, TB.core16idcead, 
	T11.unad11doc, T11.unad11usuario 
	FROM core16actamatricula AS TB, unad11terceros AS T11 
	WHERE TB.core16id=' . $id16 . ' AND TB.core16tercero=T11.unad11id';
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core16tercero'];
		$idPeriodo = $fila16['core16peraca'];
		$sDocumento = $fila16['unad11doc'];
		$sUsuario = $fila16['unad11usuario'];
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core16idescuela'];
		$DATA['corf06idprograma'] = $fila16['core16idprograma'];
		$DATA['corf06idzona'] = $fila16['core16idzona'];
		$DATA['corf06idcentro'] = $fila16['core16idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 1;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		//Agregarle el curso
		$sValores12207 = '' . $corf06id . ', ' . $idCurso . ', ' . $corf07id . ', ' . $corf07tipo . '';
		$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			//@@@ Fallo... que haremos... reversar???
		}
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
		$sErrorS = frca_CancelarCurso($sDocumento, $idPeriodo, $idCurso, $sUsuario);
	}
	return array($corf06id, $sError, $sDebug);
}

function f12206_SolicitaAplazar($id16, $idCurso, $sNota, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	require $mensajes_12206;
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 7;
	$DATA['corf06estado'] = 0;
	//Revisar la matricula para el periodo.
	$sCondi = 'core16id=' . $id16 . '';
	$sSQL = 'SELECT core16peraca, core16tercero, core16idprograma, core16idescuela, core16idzona, core16idcead 
	FROM core16actamatricula WHERE ' . $sCondi;
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core16tercero'];
		$idPeriodo = $fila16['core16peraca'];
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core16idescuela'];
		$DATA['corf06idprograma'] = $fila16['core16idprograma'];
		$DATA['corf06idzona'] = $fila16['core16idzona'];
		$DATA['corf06idcentro'] = $fila16['core16idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
		//Ver que no exista una novedad en proceso para el mismo curso sobre el mismo periodo
		$sSQL = 'SELECT TB.corf06estado, TB.corf06id 
		FROM corf06novedad AS TB, corf07novedadcurso AS T7 
		WHERE TB.corf06idestudiante=' . $idEstudiante . ' AND TB.corf06idperiodo=' . $idPeriodo . ' AND TB.corf06tiponov=7 
		AND TB.corf06id=T7.corf07idnovedad AND T7.corf07idcurso=' . $idCurso . ' AND corf06estado IN (0, 1, 2, 3, 7)';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sEstadoNov = $acorf06estado[$fila['corf06estado']];
			$sError = 'Ya existe una solicitud de aplazamiento para este curso. [Estado: <b>' . $sEstadoNov . '</b>]';
		}
	} else {
		$sError = 'No se encuentra registro de la matricula.';
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 2;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		//Agregarle el curso
		$sValores12207 = '' . $corf06id . ', ' . $idCurso . ', ' . $corf07id . ', ' . $corf07tipo . '';
		$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			//@@@ Fallo... que haremos... reversar???
		}
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
	}
	return array($corf06id, $sError, $sDebug);
}
function f12206_AgregaCursoParaAplazar($id06, $id08, $idCurso, $sNota, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$corf06id = 0;
	$DATA['corf06tiponov'] = 7;
	$DATA['corf06estado'] = 1;
	//Revisar la matricula para el periodo.
	$sSQL = '';
	if ($sError == '') {
		if ((int)$id08 != 0) {
			//Vemos is hay anexo.
			$sSQL = 'SELECT 1 FROM corf08novedadnota WHERE corf08id=' . $id08 . ' AND corf08idarchivoanexo>0';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sSQL = 'SELECT 1 FROM corf06novedad WHERE corf06id=' . $id06 . ' AND corf06estado IN (0, 2)';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					//Si es un borrador ponerlo como solicitado
					$iHoy = fecha_DiaMod();
					$iHora = fecha_hora();
					$iMinuto = fecha_minuto();
					$sSQL = 'UPDATE corf06novedad SET corf06estado=1, corf06fecha=' . $iHoy . ', corf06hora=' . $iHora . ', corf06min=' . $iMinuto . ' WHERE corf06id=' . $id06 . ' AND corf06estado IN (0, 2)';
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
	}
	if ($sError == '') {
		$sCampos12207 = 'corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo = 2;
		$corf07id = tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		//Agregarle el curso
		$sValores12207 = '' . $id06 . ', ' . $idCurso . ', ' . $corf07id . ', ' . $corf07tipo . '';
		$sSQL = 'INSERT INTO corf07novedadcurso (' . $sCampos12207 . ') VALUES (' . $sValores12207 . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 12207 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			//@@@ Fallo... que haremos... reversar???
		}
		if ((int)$id08 != 0) {
			$sSQL = 'UPDATE corf08novedadnota SET corf08nota="' . $sNota . '" WHERE corf08id=' . $id08 . '';
			$result = $objDB->ejecutasql($sSQL);
		} else {
			f12206_AdicionarNota($id06, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
	}
	return array($corf06id, $sError, $sDebug);
}
//
function f12206_TablaDetalleUsuario($aParametros, $objDB, $bDebug = false)
{
	$acorf06estado = array();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	$mensajes_12207 = $APP->rutacomun . 'lg/lg_12207_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12207)) {
		$mensajes_12207 = $APP->rutacomun . 'lg/lg_12207_es.php';
	}
	require $mensajes_todas;
	require $mensajes_12206;
	require $mensajes_12207;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = 0;
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = 0;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	$idTercero = $aParametros[100];
	$sDebug = '';
	$idGrupo = $aParametros[98];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = false;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf12206" name="paginaf12206" type="hidden" value="' . $pagina . '"/>
	<input id="lppf12206" name="lppf12206" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	$sSQL = 'SELECT corf10id, corf10nombre FROM corf10estadonovedad';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$acorf06estado[$fila['corf10id']] = cadena_notildes($fila['corf10nombre']);
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = '';
	//$sTitulos='Tiponov, Consec, Id, Estado, Estudiante, Periodo, Escuela, Programa, Fecha, Hora, Min, Autoriza1, Autoriza2, Fechaplica, Horaaplica, Minaplica, Sesion';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM corf06novedad AS TB, corf09novedadtipo AS T1, unad11terceros AS T5, exte02per_aca AS T6, core12escuela AS T7, core09programa AS T8, unad11terceros AS T12, unad11terceros AS T13 
		WHERE ' . $sSQLadd1 . ' TB.corf06tiponov=T1.corf09id AND TB.corf06idestudiante=T5.unad11id AND TB.corf06idperiodo=T6.exte02id AND TB.corf06idescuela=T7.core12id AND TB.corf06idprograma=T8.core09id AND TB.corf06autoriza1=T12.unad11id AND TB.corf06autoriza2=T13.unad11id ' . $sSQLadd . '';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	switch ($idGrupo) {
		case 6:
			$sTipoNovedad = '6';
			break;
		default:
			$sTipoNovedad = '1,2,3,4,7';
			break;
	}
	$sSQL = 'SELECT T1.corf09nombre, TB.corf06consec, TB.corf06id, TB.corf06estado, T6.exte02nombre, 
	TB.corf06fecha, TB.corf06hora, TB.corf06min, TB.corf06tiponov, TB.corf06idestudiante, 
	TB.corf06idperiodo, TB.corf06idescuela, TB.corf06idprograma, TB.corf06idactoadmin, TB.corf06idactoresposicion 
	FROM corf06novedad AS TB, corf09novedadtipo AS T1, exte02per_aca AS T6 
	WHERE TB.corf06idestudiante=' . $idTercero . ' AND TB.corf06tiponov IN (' . $sTipoNovedad . ') AND ' . $sSQLadd1 . ' TB.corf06tiponov=T1.corf09id AND TB.corf06idperiodo=T6.exte02id ' . $sSQLadd . '
	ORDER BY TB.corf06idperiodo DESC, TB.corf06tiponov, TB.corf06consec';
	$sSQLlista = '';
	//$sSQLlista=str_replace("'","|",$sSQL);
	//$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta = '<input id="consulta_12206" name="consulta_12206" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_12206" name="titulos_12206" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 12206: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				return array(utf8_encode($sErrConsulta . $sBotones), $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
			}
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['corf06fecha'] . '</b></td>
	<td><b>' . $ETI['corf06tiponov'] . '</b></td>
	<td><b>' . $ETI['corf06estado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf12206', $registros, $lineastabla, $pagina, 'paginarf12206()') . '
	' . html_lpp('lppf12206', $lineastabla, 'paginarf12206()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	$idPeriodo = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPeriodo != $filadet['corf06idperiodo']) {
			$idPeriodo = $filadet['corf06idperiodo'];
			if ($idPeriodo != 0) {
				$res = $res . '<tr class="fondoazul">
				<td colspan="5">' . $ETI['corf06idperiodo'] . '<b>' . cadena_notildes($filadet['exte02nombre']) . '</b></td>
				</tr>';
			}
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		$sLinea2 = '';
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$bMuestraActo = false;
		$et_corf06estado = $acorf06estado[$filadet['corf06estado']];
		switch ($filadet['corf06estado']) {
			case 0: //Borrador
			case 2: //Devuelta.
				$et_corf06estado = '<span class="rojo">' . $acorf06estado[$filadet['corf06estado']] . '</span>';
				switch ($filadet['corf06tiponov']) {
					case 7:
						$sLinkSigue = '';
						$sSQL = 'SELECT corf07idcurso FROM corf07novedadcurso WHERE corf07idnovedad=' . $filadet['corf06id'] . ' LIMIT 0,1';
						$tabla = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla) > 0) {
							$fila = $objDB->sf($tabla);
							$sLinkSigue = '<a href="javascript:aplazatardio(' . $idPeriodo . ', ' . $fila['corf07idcurso'] . ')" class="lnkresalte">' . 'haga clic aqu&iacute;' . '</a> para completar la solicitud.';
						}
						$sMensajeCompleta = '<span class="rojo">Importante:</span> Su solicitud no estar&aacute; en firme hasta tanto no se anexen evidencias';
						if ($filadet['corf06estado'] == 2) {
							$sMensajeCompleta = '<span class="rojo">Importante:</span> Las solicitudes devueltas cuentan con 15 d&iacute;as h&aacute;biles a partir del su devoluci&oacute;n para ser completadas.';
						}
						$sLinea2 = '<tr' . $sClass . '>
				<td></td>
				<td colspan="4">' . $sMensajeCompleta . ' ' . $sLinkSigue . '</td>
				</tr>';
						break;
				}
				break;
			case 4:
				switch ($filadet['corf06tiponov']) {
					case 7:
						$bMuestraActo = true;
						break;
				}
				break;
			case 7:
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				switch ($filadet['corf06tiponov']) {
					case 7:
						$bMuestraActo = true;
						break;
				}
				break;
			case 8: //Anulada
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
			case 9: //Negada
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				switch ($filadet['corf06tiponov']) {
					case 7:
						$bMuestraActo = true;
						break;
				}
				break;
		}
		//6 de mayo de 2022 - Se agrega la opcion de desistir.
		if ($filadet['corf06tiponov'] == 7) {
			//Para los aplazamientos tardios puede aplicaar que el estudiante desista.
			switch ($filadet['corf06estado']) {
				case 1: // Solicitada
				case 2: // Devuelta
				case 3: //  En estudio por la escuela
					$sMensajeCompleta = 'Estimado estudiante. Usted puede desistir de su solicitud, parcial o totalmente, indicando los motivos que soportan su decisi&oacute;n, ';
					$sLinkSigue = '<a href="javascript:desistir(' . $filadet['corf06id'] . ')" class="lnkresalte">' . 'solicitar el desistimiento' . '</a> ';
					$sLinea2 = $sLinea2 . '<tr' . $sClass . '>
				<td></td>
				<td colspan="3">' . $sMensajeCompleta . ' </td>
				<td>' . $sLinkSigue . '</td>
				</tr>';
					break;
			}
		}
		$sLinkActo = '';
		$et_corf06fecha = '';
		if ($filadet['corf06fecha'] != 0) {
			$et_corf06fecha = fecha_desdenumero($filadet['corf06fecha']);
		}
		$et_corf06hora = html_TablaHoraMin($filadet['corf06hora'], $filadet['corf06min']);
		if ($bMuestraActo) {
			if ($filadet['corf06idactoadmin'] != 0) {
				$sSQL = 'SELECT TB.corf21idorigen, TB.corf21idarchivo, TB.corf21agno, TB.corf21consec, T12.core12sigla, TB.corf21titulo 
				FROM corf21actosadmin AS TB, core12escuela AS T12 
				WHERE TB.corf21id=' . $filadet['corf06idactoadmin'] . ' AND TB.corf21idescuela=T12.core12id';
				$tabla21 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla21) > 0) {
					$fila21 = $objDB->sf($tabla21);
					if ($fila21['corf21titulo'] != '') {
						$sNomActoAdmin = cadena_notildes($fila21['corf21titulo']);
					} else {
						$sNomActoAdmin = 'Acuerdo ' . $fila21['core12sigla'] . ' ' . $fila21['corf21consec'] . ' del ' . $fila21['corf21agno'] . '';
					}
					$sLinkActo = html_lnkarchivo((int)$fila21['corf21idorigen'], (int)$fila21['corf21idarchivo'], $sNomActoAdmin);
				}
			}
			if ($filadet['corf06idactoresposicion'] != 0) {
				$sSQL = 'SELECT TB.corf21idorigen, TB.corf21idarchivo, TB.corf21agno, TB.corf21consec, T12.core12sigla, TB.corf21titulo 
				FROM corf21actosadmin AS TB, core12escuela AS T12 
				WHERE TB.corf21id=' . $filadet['corf06idactoresposicion'] . ' AND TB.corf21idescuela=T12.core12id';
				$tabla21 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla21) > 0) {
					$fila21 = $objDB->sf($tabla21);
					if ($fila21['corf21titulo'] != '') {
						$sNomActoAdmin = cadena_notildes($fila21['corf21titulo']);
					} else {
						$sNomActoAdmin = 'Acuerdo ' . $fila21['core12sigla'] . ' ' . $fila21['corf21consec'] . ' del ' . $fila21['corf21agno'] . '';
					}
					$sLinkActo = html_lnkarchivo((int)$fila21['corf21idorigen'], (int)$fila21['corf21idarchivo'], $sNomActoAdmin);
					$sLinea2 = $sLinea2 . '<tr' . $sClass . '>
					<td></td>
					<td colspan="4">La siguiente es la respuesta a recurso de reposici&oacute;n: ' . $sLinkActo . '</td>
					</tr>';
				}
			}
		}
		if ($bAbierta) {
			//$sLink='<a href="javascript:cargaridf12206('.$filadet['corf06id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $et_corf06fecha . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_corf06hora . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['corf09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_corf06estado . $sSufijo . '</td>
		<td>' . $sLinkActo . '</td>
		</tr>' . $sLinea2;
		$bConCursos = true;
		if ($filadet['corf06tiponov'] == 1) {
			$bConCursos = false;
		}
		if ($filadet['corf06tiponov'] == 2) {
			$bConCursos = false;
		}
		if ($bConCursos) {
			//Cursos que aplican.
			$sSQL = 'SELECT TB.corf07idnovedad, T2.unad40titulo, T2.unad40nombre, TB.corf07id, TB.corf07tipo, TB.corf07idcurso 
			FROM corf07novedadcurso AS TB, unad40curso AS T2 
			WHERE TB.corf07idnovedad=' . $filadet['corf06id'] . ' AND TB.corf07idcurso=T2.unad40id 
			ORDER BY TB.corf07idcurso';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sDato = 'Curso: <b>' . $fila['unad40titulo'] . '</b> ' . cadena_notildes($fila['unad40nombre']) . '';
				$et_corf07tipo = $acorf07tipo[$fila['corf07tipo']];
				$res = $res . '<tr' . $sClass . '>
				<td></td>
				<td colspan="3">' . $sDato . '</td>
				<td>' . $et_corf07tipo . '</td>
				</tr>';
			}
		}
		//Ahora las anotaciones...
		$sSQL = 'SELECT TB.corf08idnovedad, TB.corf08consec, TB.corf08id, TB.corf08fecha, TB.corf08hora, TB.corf08min, 
		TB.corf08nota, TB.corf08idorigenanexo, TB.corf08idarchivoanexo, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial, TB.corf08idusuario 
		FROM corf08novedadnota AS TB, unad11terceros AS T11 
		WHERE TB.corf08idnovedad=' . $filadet['corf06id'] . ' AND TB.corf08idusuario=T11.unad11id 
		ORDER BY TB.corf08consec DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$et_corf08fecha = '';
			$et_corf08hora = '';
			if ($fila['corf08fecha'] != 0) {
				$et_corf08fecha = $sPrefijo . fecha_desdenumero($fila['corf08fecha']) . $sSufijo;
				$et_corf08hora = html_TablaHoraMin($fila['corf08hora'], $fila['corf08min']);
			}
			$et_corf08nota = $sPrefijo . cadena_notildes($fila['corf08nota']) . $sSufijo;
			$et_corf08idarchivoanexo = '';
			if ($fila['corf08idarchivoanexo'] != 0) {
				//$et_corf08idarchivoanexo='<img src="verarchivo.php?cont='.$filadet['corf08idorigenanexo'].'&id='.$filadet['corf08idarchivoanexo'].'&maxx=150"/>';
				$et_corf08idarchivoanexo = html_lnkarchivo((int)$fila['corf08idorigenanexo'], (int)$fila['corf08idarchivoanexo']);
			}
			$et_corf08idusuario = $sPrefijo . $fila['unad11tipodoc'] . ' ' . $fila['unad11doc'] . ' ' . cadena_notildes($fila['unad11razonsocial']) . $sSufijo;
			$res = $res . '<tr' . $sClass . '>
			<td></td>
			<td>' . $et_corf08idusuario . '</td>
			<td>' . $et_corf08fecha . '</td>
			<td>' . $et_corf08hora . '</td>
			<td>' . $et_corf08idarchivoanexo . '</td>
			</tr>
			<tr' . $sClass . '>
			<td></td>
			<td colspan="4">' . $et_corf08nota . '</td>
			</tr>';
		}
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f12206_HtmlTablaUsuario($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla) = f12206_TablaDetalleUsuario($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f12206detalleusuario', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}


//
function f12206_HtmlPendientes($sListaEscuelas, $sListaProgramas, $objDB, $bDebug = false, $sRuta = '', $sTarget = '')
{
	$sRes = '';
	$sDebug = '';
	$sRutaProceso = ' <a href="' . $sRuta . 'corenovedadmat.php" class="lnkresalte" ' . $sTarget . '>Ingresar</a>';
	if ($sListaEscuelas != '') {
		$sTitulo = '<span class="rojo">Novedades de matricula pendientes por escuela</span>' . $sRutaProceso;
		$sCondi = 'AND TB.corf06idescuela IN (' . $sListaEscuelas . ') ';
	} else {
		$sTitulo = '<span class="rojo">Novedades de matricula pendientes por programa</span>' . $sRutaProceso;
		$sCondi = 'AND TB.corf06idprograma IN (' . $sListaProgramas . ') ';
	}
	$iTotalBorrador = 0;
	$iTotalSolicitadas = 0;
	$iTotalEstudiantes = 0;
	$sSQL = 'SELECT T9.corf09nombre, COUNT(1) AS Total, COUNT(DISTINCT(corf06idestudiante)) AS Estudiantes 
	FROM corf06novedad AS TB, corf09novedadtipo AS T9 
	WHERE TB.corf06estado IN (1) ' . $sCondi . ' AND TB.corf06tiponov=T9.corf09id
	GROUP BY T9.corf09nombre';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$sRes = '<div class="GrupoCampos520">' . html_salto() . '<b>' . $sTitulo . '</b>';
		while ($fila = $objDB->sf($tabla)) {
			$sRes = $sRes . '' . html_salto() . '' . cadena_notildes($fila['corf09nombre']) . ' - Solicitudes: <b>' . $fila['Total'] . '</b> - Estudiantes: <b>' . $fila['Estudiantes'] . '</b>';
		}
		$sRes = $sRes . html_salto() . '</div>';
	}
	return array($sRes, $sDebug);
}

// Cambios de centro
function f12206_CambiarDeCentro($core01id, $idZonaDest, $idCentroDest, $sNota, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	require $mensajes_12206;
	$sError = '';
	$sDebug = '';
	$DATA['corf06tiponov'] = 6;
	$DATA['corf06estado'] = 1;
	$corf06id = 0;
	//Revisar la matricula para el periodo.
	$sSQL = 'SELECT core01idtercero, core01idprograma, core01idescuela, core01idzona, core011idcead FROM core01estprograma WHERE core01id=' . $core01id;
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		$idEstudiante = $fila16['core01idtercero'];
		$idPeriodo = 0;
		$DATA['corf06idestudiante'] = $idEstudiante;
		$DATA['corf06idperiodo'] = $idPeriodo;
		$DATA['corf06idescuela'] = $fila16['core01idescuela'];
		$DATA['corf06idprograma'] = $fila16['core01idprograma'];
		$DATA['corf06idzona'] = $fila16['core01idzona'];
		$DATA['corf06idcentro'] = $fila16['core011idcead'];
		$DATA['corf06fecha'] = fecha_DiaMod();
		$DATA['corf06hora'] = fecha_hora();
		$DATA['corf06min'] = fecha_minuto();
		$DATA['corf06idzonadest'] = $idZonaDest;
		$DATA['corf06idcentrodest'] = $idCentroDest;
		$DATA['corf06idestprograma'] = $core01id;
		//Ver que no exista una novedad en proceso para el mismo curso sobre el mismo periodo
		$sSQL = 'SELECT TB.corf06estado 
		FROM corf06novedad AS TB 
		WHERE TB.corf06idestudiante=' . $idEstudiante . ' AND TB.corf06idestprograma=' . $core01id . ' AND TB.corf06tiponov=6 
		AND corf06estado IN (0, 1, 3)
		ORDER BY TB.corf06consec DESC';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Verificando que no exista una novedad en tramite ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sEstadoNov = $acorf06estado[$fila['corf06estado']];
			$sError = 'Ya existe una solicitud de cambio de centro por favor espere a que la solicitud inicial sea resuelta. [Estado: <b>' . $sEstadoNov . '</b>]';
		}
	} else {
		$sError = 'No se encuentra registro de la matricula.';
	}
	if ($sError == '') {
		list($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugG) = f12206_RegistrarNovedad($DATA, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($sError == '') {
		$corf06id = $DATA['corf06id'];
		/*
		$sCampos12207='corf07idnovedad, corf07idcurso, corf07id, corf07tipo';
		$corf07tipo=2;
		$corf07id=tabla_consecutivo('corf07novedadcurso', 'corf07id', '', $objDB);
		//Agregarle el curso
		$sValores12207=''.$corf06id.', '.$idCurso.', '.$corf07id.', '.$corf07tipo.'';
		$sSQL='INSERT INTO corf07novedadcurso ('.$sCampos12207.') VALUES ('.$sValores12207.');';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 12207 '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			//@@@ Fallo... que haremos... reversar???
			}
		*/
		if (trim($sNota) != '') {
			f12206_AdicionarNota($corf06id, $sNota, $objDB, $bDebug);
		}
		//Aplicar la novedad.
		//$sSQL='UPDATE core01estprograma SET core01idzona='.$_REQUEST['corf06idzonadest'].', core011idcead='.$_REQUEST['corf06idcentrodest'].' WHERE core01id='.$_REQUEST['core01id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
	}
	return array($corf06id, $sError, $sDebug);
}

function f3000_Combobita27equipotrabajo($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bita27equipotrabajo=f3000_HTMLComboV2_bita27equipotrabajo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita27equipotrabajo', 'innerHTML', $html_bita27equipotrabajo);
	$objResponse->call('carga_combo_bita28eqipoparte()');
	$objResponse->call('paginarf3000()');
	// $objResponse->call('jQuery("#bita27equipotrabajo").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	return $objResponse;
}
function f3000_HTMLComboV2_bita27equipotrabajo($objDB, $objCombos, $valor, $vrbita27idunidadfunc){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bita27equipotrabajo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='';
	if ((int)$vrbita27idunidadfunc!=0){
		// $objCombos->iAncho=450;
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre 
			FROM bita27equipotrabajo
			WHERE bita27idunidadfunc="'.$vrbita27idunidadfunc.'"';
	}
	$objCombos->sAccion = 'carga_combo_bita28eqipoparte()';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
}
function f3000_Combobita28eqipoparte($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bita28eqipoparte=f3000_HTMLComboV2_bita28eqipoparte($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita28eqipoparte', 'innerHTML', $html_bita28eqipoparte);
	$objResponse->call('paginarf3000()');
	// $objResponse->call('jQuery("#bita28eqipoparte").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	return $objResponse;
}
function f3000_HTMLComboV2_bita28eqipoparte($objDB, $objCombos, $valor, $vrbita28idequipotrab){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bita28eqipoparte', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='';
	if ((int)$vrbita28idequipotrab!=0){
		// $objCombos->iAncho=450;
		$sCondi='bita28idequipotrab="'.$vrbita28idequipotrab.'"';
		$sSQL='SELECT TB.bita28idtercero AS id, T2.unad11razonsocial AS nombre 
			FROM bita28eqipoparte AS TB, unad11terceros AS T2
			WHERE TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S" AND bita28idequipotrab="'.$vrbita28idequipotrab.'"';
	}
	$objCombos->sAccion = 'paginarf3000()';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
}
function f3000_TablaDetallePQRS($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3000='lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3000)){$mensajes_3000='lg/lg_3000_es.php';}
	require $mensajes_todas;
	require $mensajes_3000;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	// if (isset($aParametros[101])==0){$aParametros[101]=1;}
	// if (isset($aParametros[102])==0){$aParametros[102]=100;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$idTercero=$aParametros[100];
	$idUnidad=numeros_validar($aParametros[103]);
	$idEquipo=numeros_validar($aParametros[104]);
	$idResponsable=numeros_validar($aParametros[105]);
	$iAgno=numeros_validar($aParametros[106]);
	$bListar=numeros_validar($aParametros[107]);
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' bListar: ' . $aParametros[107].'<br>';}
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	//if ((int)$idUnidad==0){$sLeyenda='No ha seleccionado una unidad funcional';}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf3000" name="paginaf3000" type="hidden" value="'.$pagina.'"/><input id="lppf3000" name="lppf3000" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	// $sBotones='<input id="paginaf2300" name="paginaf2300" type="hidden" value="'.$pagina.'"/><input id="lppf2300" name="lppf2300" type="hidden" value="'.$lineastabla.'"/>';
	$sBotones='';
	$sTitulos='-, Borrador, Solicitado, En tramite, Resuelto';
	$asaiu05idcategoria=array();
	$aTablas=array();
	$aVenceRojo=array(0,0);
	$aVenceNaranja=array(0,0);
	$aVenceVerde=array(0,0);
	$aTiempoRojo=array(0,0);
	$aTiempoNaranja=array(0,0);
	$aTiempoVerde=array(0,0);
	$iIndiceSatisf=0;
	$iEncuestas=0;
	$iTablas=0;
	$iNumSolicitudes=0;
	$sSQL='SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
	FROM saiu15historico 
	WHERE saiu15agno='.$iAgno.' AND saiu15tiporadicado=1
	GROUP BY saiu15agno, saiu15mes';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Historico: '.$sSQL.'<br>';}
	$tabla15=$objDB->ejecutasql($sSQL);
	while($fila15=$objDB->sf($tabla15)){
		$iNumSolicitudes=$iNumSolicitudes+$fila15['Solicitudes'];
		if ($fila15['saiu15mes']<10){
			$sContenedor=$fila15['saiu15agno'].'0'.$fila15['saiu15mes'];
			}else{
			$sContenedor=$fila15['saiu15agno'].$fila15['saiu15mes'];
			}
		$iTablas++;
		$aTablas[$iTablas]=$sContenedor;
	}
	$sSQL='';
	$sErrConsulta='';
	$sWhere='';
	if ($idUnidad != '') {
		$sWhere = $sWhere . ' AND saiu05idunidadresp=' . $idUnidad . '';
	}
	if ($idEquipo != '') {
		$sWhere = $sWhere . ' AND saiu05idequiporesp=' . $idEquipo . '';
	}
	if ($idResponsable != '') {
		$sWhere = $sWhere . ' AND saiu05idresponsable=' . $idResponsable . '';		
	}
	if ($bListar != '') {
		switch($bListar) {
			case 1:
				$sWhere = $sWhere . ' AND saiu05idresponsable=' . $idTercero . '';				
				break;
			case 2:
				$aEquipos = array();
				$sEquipos = '';
				$sSQL='SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
				$tabla= $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita27id'];
					}
				} else {
					$sSQL='SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
					$tabla= $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0) {
						while ($fila = $objDB->sf($tabla)) {
							$aEquipos[] = $fila['bita28idequipotrab'];
						}
					}
				}
				$sEquipos = implode(',',$aEquipos);
				if ($sEquipos != '') {
					$sWhere = $sWhere . ' AND saiu05idequiporesp IN (' . $sEquipos . ')';
				} else {
					$sWhere = $sWhere . ' AND saiu05idresponsable=' . $idTercero . '';
				}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lider o Colaborador: '.$sSQL.'<br>';}
				break;
		}
	}
	$sSQL='';
	for ($k=1;$k<=$iTablas;$k++){
		if ($k!=1){$sSQL=$sSQL.' UNION ALL ';}
		$sContenedor=$aTablas[$k];
		$sSQL=$sSQL.'SELECT saiu05idcategoria, saiu05estado, saiu05fecharespprob, saiu05fecharespdef, saiu05evalacepta, 
		saiu05evalfecha, saiu05evalamabilidad, saiu05evalrapidez, saiu05evalclaridad, saiu05evalresolvio, 
		saiu05evalconocimiento, saiu05evalutilidad, saiu05idtiposolorigen 
		FROM saiu05solicitud_' . $sContenedor . '
		WHERE saiu05tiporadicado=1 ' . $sWhere . '';
	}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tabla detalle SQL: ' . $sSQL.'<br>';}
	if ($sSQL != '') {
		$sSQLlista=str_replace("'","|",$sSQL);
		$sSQLlista=str_replace('"',"|",$sSQLlista);
		$sErrConsulta='<input id="consulta_3000" name="consulta_3000" type="hidden" value="'.$sSQLlista.'"/>
		<input id="titulos_3000" name="titulos_3000" type="hidden" value="'.$sTitulos.'"/>';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3000: '.$sSQL.'<br>';}
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			$registros=0;
			$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
			//$sLeyenda=$sSQL;
		}else{
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				$sTabla='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
				<tr class="fondoazul">
				<td align="center"><b>'.'No se registran solicitudes'.'</b></td>
				</tr>
				</table>';
				return array(utf8_encode($sErrConsulta.$sBotones).$sTabla, $aVenceRojo, $aVenceNaranja, $aVenceVerde, $aTiempoRojo, $aTiempoNaranja, $aTiempoVerde, $iIndiceSatisf, $sDebug);
			} 
		}
	}
	$res=$sErrConsulta.$sLeyenda.$sBotones.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td colspan="5" align="center"><b>'.'Consolidado de solicitudes PQRS '. $iAgno .'</b></td>
	</tr>
	<tr class="fondoazul">
	<td><b>'.'Tipo de Solicitud'.'</b></td>
	<td align="center"><b>'.'Borrador'.'</b></td>
	<td align="center"><b>'.'Solicitado'.'</b></td>
	<td align="center"><b>'.'En tr&aacute;mite'.'</b></td>
	<td align="center"><b>'.'Resuelto'.'</b></td>
	<td><b>'.''.'</b></td>
	</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$i_saiu05idcategoria=$filadet['saiu05idcategoria'];
		if (isset($asaiu05idcategoria[$i_saiu05idcategoria])==0){
			$sSQL='SELECT saiu68nombre FROM saiu68categoria WHERE saiu68id='.$i_saiu05idcategoria.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$asaiu05idcategoria[$i_saiu05idcategoria]=array('nombre'=>$filae['saiu68nombre'], 'valores'=>array(-1=>0,0=>0,2=>0,7=>0));
			}else{
				$asaiu05idcategoria[$i_saiu05idcategoria]='';
			}
		}
		if ($asaiu05idcategoria[$i_saiu05idcategoria]!='') {
			$asaiu05idcategoria[$i_saiu05idcategoria]['valores'][$filadet['saiu05estado']]++;
		}
		if ($filadet['saiu05estado'] == 7) {
			// Determina tiempos de respuesta de solicitudes
			if ($filadet['saiu05fecharespprob'] != 0 && $filadet['saiu05fecharespdef'] != 0) {				
				$idias = fecha_DiasEntreFechasDesdeNumero($filadet['saiu05fecharespdef'], $filadet['saiu05fecharespprob']);
				if ($filadet['saiu05idtiposolorigen']==53)  {// Derechos de petición - límite 15 días
					if ($idias >= 10) { // menor o igual a 5 días
						$aTiempoVerde[0]++;
					} else if ($idias >= 6 && $idias < 10) { // Entre 6 y 10 días
						$aTiempoNaranja[0]++;
					} else { // Mayor a 10 días
						$aTiempoRojo[0]++;
					}
				} else {
					if ($filadet['saiu05fecharespprob'] >= $filadet['saiu05fecharespdef']) {
						if ($idias < 2) { // Entre 2 y 3 días
							$aTiempoNaranja[1]++;
						} else { // Menor o igual a 1 dia
							$aTiempoVerde[1]++;
						}
					} else { // Mayor a 4 días
						$aTiempoRojo[1]++;
					}
				}
			}
			if ($filadet['saiu05evalacepta'] == 1 && $filadet['saiu05evalfecha'] != 0) {
				$iVrMaxItem = 5;
				$aItems = array_filter(array($filadet['saiu05evalamabilidad'],$filadet['saiu05evalrapidez'],$filadet['saiu05evalclaridad'],$filadet['saiu05evalresolvio'],$filadet['saiu05evalconocimiento'],$filadet['saiu05evalutilidad']));
				$iNumItems = count($aItems);
				$iSumaVrEval = $filadet['saiu05evalamabilidad']+$filadet['saiu05evalrapidez']+$filadet['saiu05evalclaridad']+$filadet['saiu05evalresolvio']+$filadet['saiu05evalconocimiento']+$filadet['saiu05evalutilidad'];
				$iIndiceSatisf = $iIndiceSatisf + ($iSumaVrEval / $iNumItems);
				$iEncuestas++;
			}
		} else if ($filadet['saiu05estado'] >= 0  && $filadet['saiu05estado'] < 7) {
			// Determina tiempos de vencimiento de solicitudes
			if ($filadet['saiu05fecharespprob'] != 0) {
				$iHoy = fecha_DiaMod();
				if ($filadet['saiu05fecharespprob'] >= $iHoy) {					
					$idias = fecha_DiasEntreFechasDesdeNumero($iHoy, $filadet['saiu05fecharespprob']);
					if ($filadet['saiu05idtiposolorigen']==53)  {// Derechos de petición - límite 15 días
						if ($idias <= 5) {
							$aVenceNaranja[0]++;
						} else {
							$aVenceVerde[0]++;
						}
					} else {
						if ($idias <= 1) {
							$aVenceNaranja[1]++;
						} else {
							$aVenceVerde[1]++;
						}
					}
				} else {
					if ($filadet['saiu05idtiposolorigen']==53)  {// Derechos de petición - límite 15 días
						$aVenceRojo[0]++;
					} else {
						$aVenceRojo[1]++;
					}
				}
			}
		}
	}
	if ($iEncuestas > 0) {
		$iIndiceSatisf = $iIndiceSatisf / $iEncuestas;
	}
	foreach ($asaiu05idcategoria as $aCategoria) {
		if ($aCategoria!='') {		
			$sPrefijo='';
			$sSufijo='';
			$sClass='';
			$sLink='';
			if (true){
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
			$tlinea++;
			if ($babierta){
				//$sLink='<a href="javascript:cargadato('."'".$filadet['cara00id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
			$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($aCategoria['nombre']).$sSufijo.'</td>
<td align="center">'.$aCategoria['valores'][-1].'</td>
<td align="center">'.$aCategoria['valores'][0].'</td>
<td align="center">'.$aCategoria['valores'][2].'</td>
<td align="center">'.$aCategoria['valores'][7].'</td>
</tr>';
		}
	}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $aVenceRojo, $aVenceNaranja, $aVenceVerde, $aTiempoRojo, $aTiempoNaranja, $aTiempoVerde, $iIndiceSatisf, $sDebug);
}
function f3000_HtmlTablaPQRS($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $aVenceRojo, $aVenceNaranja, $aVenceVerde, $aTiempoRojo, $aTiempoNaranja, $aTiempoVerde, $iIndiceSatisf, $sDebugTabla)=f3000_TablaDetallePQRS($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3000detalle', 'innerHTML', $sDetalle);
	$objResponse->assign('div_f3000vencerojo_0', 'innerHTML', $aVenceRojo[0]);
	$objResponse->assign('div_f3000vencenaranja_0', 'innerHTML', $aVenceNaranja[0]);
	$objResponse->assign('div_f3000venceverde_0', 'innerHTML', $aVenceVerde[0]);
	$objResponse->assign('div_f3000tiemporojo_0', 'innerHTML', $aTiempoRojo[0]);
	$objResponse->assign('div_f3000tiemponaranja_0', 'innerHTML', $aTiempoNaranja[0]);
	$objResponse->assign('div_f3000tiempoverde_0', 'innerHTML', $aTiempoVerde[0]);
	$objResponse->assign('div_f3000vencerojo_1', 'innerHTML', $aVenceRojo[1]);
	$objResponse->assign('div_f3000vencenaranja_1', 'innerHTML', $aVenceNaranja[1]);
	$objResponse->assign('div_f3000venceverde_1', 'innerHTML', $aVenceVerde[1]);
	$objResponse->assign('div_f3000tiemporojo_1', 'innerHTML', $aTiempoRojo[1]);
	$objResponse->assign('div_f3000tiemponaranja_1', 'innerHTML', $aTiempoNaranja[1]);
	$objResponse->assign('div_f3000tiempoverde_1', 'innerHTML', $aTiempoVerde[1]);
	if ($iIndiceSatisf == 0) {
		$objResponse->assign('div_f3000indicesatisf', 'innerHTML', '_');
	} else {
		$iIndiceSatisf = number_format($iIndiceSatisf,2,',','');
		$objResponse->assign('div_f3000indicesatisf', 'innerHTML', $iIndiceSatisf . '%');
	}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}

