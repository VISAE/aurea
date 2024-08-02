<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.26.2 martes, 15 de junio de 2021
--- 3047 
*/

/** Archivo lib3047.php.
 * Libreria 3047 .
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date martes, 15 de junio de 2021
 */
function f3047_InfoDetalle($saiu47t1idmotivo, $objDB)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$res = '';
	if ((int)$saiu47t1idmotivo != 0) {
		$sSQL = 'SELECT saiu50detalleest FROM saiu50motivotramite WHERE saiu50id=' . $saiu47t1idmotivo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if (trim($fila['saiu50detalleest']) != '') {
				$res = '<div class="GrupoCamposAyuda">' . cadena_notildes($fila['saiu50detalleest']) . html_salto() . '</div>';
			}
		}
	}
	return $res;
}
function f3047_Detsaiu47t1idmotivo($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_saiu47t1idmotivo = f3047_InfoDetalle($aParametros[1], $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_Detsaiu47t1idmotivo', 'innerHTML', $html_saiu47t1idmotivo);
	return $objResponse;
}
function f3047_TablaDetalleV2Tercero($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3047 = 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3047)) {
		$mensajes_3047 = 'lg/lg_3047_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3047;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = 1;
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = $_SESSION['unad_id_tercero'];
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
	$iTipoTramite = $aParametros[97];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$idEstudiante = $aParametros[98];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3047" name="paginaf3047" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3047" name="lppf3047" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$aEstado = array();
	$sSQL = 'SELECT saiu60id, saiu60nombre FROM saiu60estadotramite';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['saiu60id']] = cadena_notildes($fila['saiu60nombre']);
	}
	$aMotivo = array();
	$sSQL = 'SELECT saiu50id, saiu50nombre FROM saiu50motivotramite WHERE saiu50idtipotram='.$iTipoTramite.'';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aMotivo[$fila['saiu50id']] = cadena_notildes($fila['saiu50nombre']);
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
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sBase = '';
	$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($sBase != '') {
			$sBase = $sBase . ' UNION ';
		}
		$sTabla = $fila[0];
		$sBase = $sBase . 'SELECT TB.saiu47agno, TB.saiu47mes, TB.saiu47dia, TB.saiu47consec, TB.saiu47estado, TB.saiu47t1idmotivo, 
		TB.saiu47t1vrsolicitado, TB.saiu47id
		FROM ' . $sTabla . ' AS TB
		WHERE TB.saiu47idsolicitante=' . $idEstudiante . ' AND TB.saiu47tipotramite=' . $iTipoTramite . '';
	}
	$sSQL = '' . $sBase . '
	ORDER BY saiu47agno DESC, saiu47mes DESC, saiu47consec DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3047" name="consulta_3047" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_3047" name="titulos_3047" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3047: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3047" name="paginaf3047" type="hidden" value="'.$pagina.'"/><input id="lppf3047" name="lppf3047" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<td><b>' . $ETI['msg_fecha'] . '</b></td>';
	$res = $res . '<td><b>' . $ETI['saiu47consec'] . '</b></td>';
	$res = $res . '<td><b>' . $ETI['saiu47estado'] . '</b></td>';
	$res = $res . '<td><b>' . $ETI['saiu47t1idmotivo'] . '</b></td>';
	$res = $res . '<td><b>' . $ETI['saiu47t1vrsolicitado'] . '</b></td>';
	$res = $res . '<td align="right">';
	$res = $res . '' . html_paginador('paginaf3047', $registros, $lineastabla, $pagina, 'paginarf3047()') . '';
	$res = $res . '' . html_lpp('lppf3047', $lineastabla, 'paginarf3047()') . '';
	$res = $res . '</td>';
	$res = $res . '</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['saiu47estado']) {
			case 0: //Borrador
			case 5: // Devuelta
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				break;
			case 1:
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				break;
		}
		$et_fecha = $sPrefijo . fecha_Armar($filadet['saiu47dia'], $filadet['saiu47mes'], $filadet['saiu47agno']) . $sSufijo;
		if (isset($aMotivo[$filadet['saiu47t1idmotivo']]) == 0) {
			$aMotivo[$filadet['saiu47t1idmotivo']] = '{' . $filadet['saiu47t1idmotivo'] . '}';
		}
		$et_saiu47t1idmotivo = $sPrefijo . $aMotivo[$filadet['saiu47t1idmotivo']] . $sSufijo;
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3047(' . $filadet['saiu47agno'] . ', ' . $filadet['saiu47id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_fecha . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['saiu47consec'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $aEstado[$filadet['saiu47estado']] . $sSufijo . '</td>';
		$res = $res . '<td>' . $et_saiu47t1idmotivo . '</td>';
		$res = $res . '<td align="right">' . $sPrefijo . formato_moneda($filadet['saiu47t1vrsolicitado']) . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3047_HtmlTablaTercero($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3047_TablaDetalleV2Tercero($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3047detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
