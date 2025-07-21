<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2024 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.0 lunes, 8 de julio de 2024
--- 3005 saiu05solconsulta
*/

/** Archivo lib3005campus.php.
 * Libreria 3005 saiu05solicitud.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @date lunes, 8 de julio de 2024
 */
function f3005_TablaDetalleCampus($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
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
	$iNumVariables = 113;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sError = '';
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$sNombre = cadena_Validar(trim($aParametros[103]));
	$iAgno = numeros_validar($aParametros[104]);
	$iEstado = numeros_validar($aParametros[105]);
	$bListar = numeros_validar($aParametros[106]);
	$bdoc = cadena_Validar(trim($aParametros[107]));
	$btipo = numeros_validar($aParametros[108]);
	$bcategoria = numeros_validar($aParametros[109]);
	$btema = numeros_validar($aParametros[110]);
	$bref = cadena_Validar(trim($aParametros[111]));
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3073" name="paginaf3005" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3073" name="lppf3005" type="hidden" value="' . $lineastabla . '"/>';
	if ($iAgno == '') {
		$sLeyenda = $sLeyenda . 'No ha seleccionado un a&ntilde;o a consultar';
	}
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aTemas = array();
	$sSQL = 'SELECT saiu03id, saiu03titulo FROM saiu03temasol WHERE saiu03activo="S"' . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		while ($fila = $objDB->sf($tabla)) {
			$aTemas[$fila['saiu03id']] = cadena_notildes($fila['saiu03titulo']);
		}
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($iEstado !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05estado=' . $iEstado . '';
	}
	switch ($bListar) {
		case 1:
			$sSQLadd = $sSQLadd . ' AND TB.saiu05idresponsable=' . $idTercero . '';
			break;
		case 2:
			$aEquipos = array();
			$sEquipos = '';
			$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				while ($fila = $objDB->sf($tabla)) {
					$aEquipos[] = $fila['bita27id'];
				}
			} else {
				$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita28idequipotrab'];
					}
				}
			}
			$sEquipos = implode(',', $aEquipos);
			if ($sEquipos != '') {
				$sSQLadd = $sSQLadd . ' AND TB.saiu05idequiporesp IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu05idresponsable=' . $idTercero . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Lider o Colaborador: ' . $sSQL . '<br>';
			}
			break;
	}
	if ($sNombre != '') {
		$sBase = mb_strtoupper($sNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
    	$sSQLadd = $sSQLadd . ' AND TB.saiu05idsolicitante=' . $idTercero . '';
	if ($bdoc !== '') {
		$sSQLadd = $sSQLadd . ' AND T11.unad11doc LIKE "%' . $bdoc . '%"';
	}
	if ($btipo !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idcategoria=' . $btipo . '';
	}
	if ($bcategoria !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idtiposolorigen=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05idtemaorigen=' . $btema . '';
	}
	if ($bref !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu05numref="' . $bref . '"';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Agno, Mes, Dia, Consecutivo, Estado, Hora, Minuto';
	//Las solicitudes no estan en una tabla en contenedores...
	$aTablas = array();
	$iTablas = 0;
	$iNumSolicitudes = 0;
	$tabladetalle = 0;
	$sSQL = 'SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
	FROM saiu15historico 
	WHERE saiu15agno=' . $iAgno . ' AND saiu15tiporadicado=1
	GROUP BY saiu15agno, saiu15mes';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Historico: ' . $sSQL . '<br>';
	}
	$tabla15 = $objDB->ejecutasql($sSQL);
	while ($fila15 = $objDB->sf($tabla15)) {
		$iNumSolicitudes = $iNumSolicitudes + $fila15['Solicitudes'];
		if ($fila15['saiu15mes'] < 10) {
			$sContenedor = $fila15['saiu15agno'] . '0' . $fila15['saiu15mes'];
		} else {
			$sContenedor = $fila15['saiu15agno'] . $fila15['saiu15mes'];
		}
		$iTablas++;
		$aTablas[$iTablas] = $sContenedor;
	}
	$registros = $iNumSolicitudes;
	$sLimite = '';
	$sSQL = '';
	for ($k = 1; $k <= $iTablas; $k++) {
		if ($k != 1) {
			$sSQL = $sSQL . ' UNION ';
		}
		$sContenedor = $aTablas[$k];
		$sCampos = 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, 
		TB.saiu05hora, TB.saiu05minuto, TB.saiu05id, TB.saiu05estado, T11.unad11tipodoc, 
		T11.unad11doc, T11.unad11razonsocial, T13.saiu68nombre, TB.saiu05idtemaorigen, TB.saiu05fecharespprob, 
		TB.saiu05tiemprespdias, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05raddesphab';
		$sConsulta = 'FROM saiu05solicitud_' . $sContenedor . ' AS TB, saiu11estadosol AS T12, unad11terceros AS T11, saiu68categoria AS T13 
		WHERE TB.saiu05tiporadicado=1 AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idsolicitante=T11.unad11id AND TB.saiu05idcategoria=T13.saiu68id ' . $sSQLadd . '';
		$sSQL = $sSQL . $sCampos . ' ' . $sConsulta;
	}
	if ($sSQL != '') {
		$sOrden = 'ORDER BY saiu05estado, saiu05fecharespprob, saiu05agno, saiu05mes, saiu05dia';
		$sSQL = $sSQL . ' ' . $sOrden;
		$sSQLlista = str_replace("'", "|", $sSQL);
		$sSQLlista = str_replace('"', "|", $sSQLlista);
		$sErrConsulta = '<input id="consulta_3005" name="consulta_3005" type="hidden" value="' . $sSQLlista . '"/>';
		$sErrConsulta = $sErrConsulta . '<input id="titulos_3005" name="titulos_3005" type="hidden" value="' . $sTitulos . '"/>';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3005: ' . $sSQL . '<br>VARIABLES: $iEstado: ' . $iEstado . ' - $bListar: ' . $bListar . ' - $iTablas: ' . $iTablas . '<br>';
		}
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($tabladetalle == false) {
			$registros = 0;
			$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
			//$sLeyenda=$sSQL;
		} else {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				//return array($sErrConsulta . $sBotones, $sDebug);
			}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
			}
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['msg_numsolicitud'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idcategoria'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idtemaorigen'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idsolicitante'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05razonsocial'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05fecharad'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf3005', $registros, $lineastabla, $pagina, 'paginarf3005()');
	$res = $res . html_lpp('lppf3005', $lineastabla, 'paginarf3005()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	if ($sSQL != '') {
		$tlinea = 1;
		$iBorradores = 0;
		$iHoy = fecha_DiaMod();
		$iDiasLimiteBorrador = 3;
		while ($filadet = $objDB->sf($tabladetalle)) {
			$sPrefijo = '';
			$sSufijo = '';
			$sClass = ' class="resaltetabla"';
			$sLink = '';
			$sTema = '';
			if (false) {
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
			}
			if (($tlinea % 2) != 0) {
				$sClass = '';
			}
			$tlinea++;
			$iFechaSol = fecha_ArmarNumero($filadet['saiu05dia'], $filadet['saiu05mes'], $filadet['saiu05agno']);
			$et_NumSol = f3000_NumSolicitud($filadet['saiu05agno'], $filadet['saiu05mes'], $filadet['saiu05consec']);
			$et_saiu05dia = $ETI['et_estadorad'];
			if ($filadet['saiu05estado'] != -1) {				
				$iFechaRad = fecha_NumSumarDias($iFechaSol, $filadet['saiu05raddesphab']);
				$et_saiu05dia = fecha_desdenumero($iFechaRad);
			}
			//$et_saiu05fecharespprob='';
			//if ($filadet['saiu05fecharespprob']!='00/00/0000'){$et_saiu05fecharespprob=$filadet['saiu05fecharespprob'];}
			if ($bAbierta) {
				$sLink = '<a href="javascript:cargaridf3005(' . $filadet['saiu05agno'] . ', ' . $filadet['saiu05mes'] . ', ' . $filadet['saiu05id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			}
			if (isset($aTemas[$filadet['saiu05idtemaorigen']]) != 0) {
				$sTema = $aTemas[$filadet['saiu05idtemaorigen']];
			}
			if ($filadet['saiu05estado'] == -1) {
				$sPrefijo = '<span style="color:#FF6600"><b>';
				$sSufijo = '</b></span>';
				$iBorradores = $iBorradores + 1;
			}
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td>' . $sPrefijo . $et_NumSol . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu68nombre']) . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . $sTema . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad11tipodoc']) . cadena_notildes($filadet['unad11doc']) . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad11razonsocial']) . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . $et_saiu05dia . $sSufijo . '</td>';
			$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>';
			$res = $res . '<td>' . $sLink . '</td>';
			$res = $res . '</tr>';
		}
		if ($iBorradores > 0) {
			$sError = $sError . $ETI['msg_alertaborradores'] . $iBorradores . '<br>';
		}
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sError, $sDebug);
}
function f3005_HtmlTablaCampus($aParametros)
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
	list($sDetalle, $sErrorT, $sDebugTabla) = f3005_TablaDetalleCampus($aParametros, $objDB, $bDebug);
	$sError = $sError . $sErrorT;
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3005detalle', 'innerHTML', $sDetalle);
	if ($sError != '') {
		$objResponse->assign('div_alarma', 'innerHTML', $sError);
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}