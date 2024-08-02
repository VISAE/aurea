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
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = '';
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	if (isset($aParametros[108]) == 0) {
		$aParametros[108] = '';
	}
	if (isset($aParametros[109]) == 0) {
		$aParametros[109] = '';
	}
	if (isset($aParametros[110]) == 0) {
		$aParametros[110] = '';
	}
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero = $aParametros[100];
	$sDebug = '';
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$sNombre = $aParametros[103];
	$iAgno = $aParametros[104];
	$iEstado = $aParametros[105];
	$bListar = $aParametros[106];
	$bdoc = $aParametros[107];
	$btipo = $aParametros[108];
	$bcategoria = $aParametros[109];
	$btema = $aParametros[110];
	$bref = $aParametros[111];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	if ($iAgno == '') {
		$sLeyenda = 'No ha seleccionado un a&ntilde;o a consultar';
	}
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . '<input id="paginaf3005" name="paginaf3005" type="hidden" value="' . $pagina . '"/><input id="lppf3005" name="lppf3005" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
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
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
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
	/*
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Medio, Tiposolorigen, Temaorigen, Temafin, Tiposolfin, Solicitante, Interesado, Tipointeresado, Rptaforma, Rptacorreo, Rptadireccion, Costogenera, Costovalor, Costorefpago, Prioridad, Zona, Cead, Numref, Detalle, Infocomplemento, Responsable, Escuela, Programa, Periodo, Curso, Grupo, Tiemprespdias, Tiempresphoras, Fecharespprob, Respuesta, Moduloproc, Entificadormod, Numradicado';
	$sSQL='SELECT TB.saiu05agno, TB.saiu05mes, T3.saiu16nombre, TB.saiu05consec, TB.saiu05id, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05origenid, TB.saiu05dia, TB.saiu05hora, TB.saiu05minuto, T12.saiu11nombre, T13.bita01nombre, T14.saiu02titulo, T15.saiu03titulo, T16.saiu03titulo, T17.saiu02titulo, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.bita07nombre, T21.saiu12nombre, TB.saiu05rptacorreo, TB.saiu05rptadireccion, TB.saiu05costogenera, TB.saiu05costovalor, TB.saiu05costorefpago, T27.bita03nombre, T28.unad23nombre, T29.unad24nombre, TB.saiu05numref, TB.saiu05detalle, TB.saiu05infocomplemento, T33.unad11razonsocial AS C33_nombre, T34.core12nombre, T35.core09nombre, T36.exte02nombre, T37.unad40nombre, T38.core06consec, TB.saiu05tiemprespdias, TB.saiu05tiempresphoras, TB.saiu05fecharespprob, TB.saiu05respuesta, TB.saiu05idmoduloproc, TB.saiu05identificadormod, TB.saiu05numradicado, TB.saiu05tiporadicado, TB.saiu05estado, TB.saiu05idmedio, TB.saiu05idtiposolorigen, TB.saiu05idtemaorigen, TB.saiu05idtemafin, TB.saiu05idtiposolfin, TB.saiu05idsolicitante, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.saiu05idinteresado, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.saiu05tipointeresado, TB.saiu05rptaforma, TB.saiu05prioridad, TB.saiu05idzona, TB.saiu05cead, TB.saiu05idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc, TB.saiu05idescuela, TB.saiu05idprograma, TB.saiu05idperiodo, TB.saiu05idcurso, TB.saiu05idgrupo 
	FROM saiu05solicitud AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, bita01tiposolicitud AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, saiu03temasol AS T16, saiu02tiposol AS T17, unad11terceros AS T18, unad11terceros AS T19, bita07tiposolicitante AS T20, saiu12formarespuesta AS T21, bita03prioridad AS T27, unad23zona AS T28, unad24sede AS T29, unad11terceros AS T33, core12escuela AS T34, core09programa AS T35, exte02per_aca AS T36, unad40curso AS T37, core06grupos AS T38 
	WHERE '.$sSQLadd1.' TB.saiu05tiporadicado=T3.saiu16id AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idmedio=T13.bita01id AND TB.saiu05idtiposolorigen=T14.saiu02id AND TB.saiu05idtemaorigen=T15.saiu03id AND TB.saiu05idtemafin=T16.saiu03id AND TB.saiu05idtiposolfin=T17.saiu02id AND TB.saiu05idsolicitante=T18.unad11id AND TB.saiu05idinteresado=T19.unad11id AND TB.saiu05tipointeresado=T20.bita07id AND TB.saiu05rptaforma=T21.saiu12id AND TB.saiu05prioridad=T27.bita03id AND TB.saiu05idzona=T28.unad23id AND TB.saiu05cead=T29.unad24id AND TB.saiu05idresponsable=T33.unad11id AND TB.saiu05idescuela=T34.core12id AND TB.saiu05idprograma=T35.core09id AND TB.saiu05idperiodo=T36.exte02id AND TB.saiu05idcurso=T37.unad40id AND TB.saiu05idgrupo=T38.core06id '.$sSQLadd.'
	ORDER BY TB.saiu05agno, TB.saiu05mes, TB.saiu05tiporadicado, TB.saiu05consec';
	*/
	//Las solicitudes no estan en una tabla en contenedores...
	$aTablas = array();
	$iTablas = 0;
	$iNumSolicitudes = 0;
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
	$limite = '';
	$sTitulos = 'Agno, Mes, Dia, Consecutivo, Estado, Hora, Minuto';
	$sSQL = '';
	$sErrConsulta = '';
	$sWhere = '';
    $sWhere = $sWhere . ' AND TB.saiu05estado > -1';
	switch ($bListar) {
		case 1:
			$sWhere = $sWhere . ' AND TB.saiu05idresponsable=' . $idTercero . '';
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
				$sWhere = $sWhere . ' AND TB.saiu05idequiporesp IN (' . $sEquipos . ')';
			} else {
				$sWhere = $sWhere . ' AND TB.saiu05idresponsable=' . $idTercero . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Lider o Colaborador: ' . $sSQL . '<br>';
			}
			break;
	}
	if ($sNombre !== '') {
		//$sWhere = $sWhere . ' AND T11.unad11razonsocial LIKE "%' . $sNombre . '%"';
		$sBase = mb_strtoupper($sNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sWhere = $sWhere . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
    $sWhere = $sWhere . ' AND TB.saiu05idsolicitante=' . $idTercero . '';
	if ($bdoc !== '') {
		$sWhere = $sWhere . ' AND T11.unad11doc LIKE "%' . $bdoc . '%"';
	}
	if ($btipo !== '') {
		$sWhere = $sWhere . ' AND TB.saiu05idcategoria=' . $btipo . '';
	}
	if ($bcategoria !== '') {
		$sWhere = $sWhere . ' AND TB.saiu05idtiposolorigen=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sWhere = $sWhere . ' AND TB.saiu05idtemaorigen=' . $btema . '';
	}
	if ($bref !== '') {
		$sWhere = $sWhere . ' AND TB.saiu05numref="' . $bref . '"';
	}
	$sSQL = '';
	$aTemas = array();
	$sSQL = $sSQL . 'SELECT saiu03id, saiu03titulo FROM saiu03temasol WHERE saiu03activo="S"' . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		while ($fila = $objDB->sf($tabla)) {
			$aTemas[$fila['saiu03id']] = cadena_notildes($fila['saiu03titulo']);
		}
	}
	$sSQL = '';
	for ($k = 1; $k <= $iTablas; $k++) {
		if ($k != 1) {
			$sSQL = $sSQL . ' UNION ';
		}
		$sContenedor = $aTablas[$k];
		$sSQL = $sSQL . 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, TB.saiu05hora, 
		TB.saiu05minuto, TB.saiu05id, TB.saiu05estado, T11.unad11tipodoc, T11.unad11doc, 
		T11.unad11razonsocial, T13.saiu68nombre, TB.saiu05idtemaorigen
		FROM saiu05solicitud_' . $sContenedor . ' AS TB, saiu11estadosol AS T12, unad11terceros AS T11, saiu68categoria AS T13 
		WHERE TB.saiu05tiporadicado=1 AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idsolicitante=T11.unad11id AND TB.saiu05idcategoria=T13.saiu68id ' . $sWhere . '';
	}
	if ($sSQL != '') {
		$sSQL = $sSQL . ' ORDER BY saiu05agno DESC, saiu05mes DESC, saiu05consec DESC' . $limite;
		$sSQLlista = str_replace("'", "|", $sSQL);
		$sSQLlista = str_replace('"', "|", $sSQLlista);
		$sErrConsulta = '<input id="consulta_3005" name="consulta_3005" type="hidden" value="' . $sSQLlista . '"/>
		<input id="titulos_3005" name="titulos_3005" type="hidden" value="' . $sTitulos . '"/>';
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
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf2301" name="paginaf2301" type="hidden" value="'.$pagina.'"/><input id="lppf2301" name="lppf2301" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>' . $ETI['msg_numsolicitud'] . '</b></td>
	<td><b>' . $ETI['saiu05idcategoria'] . '</b></td>
	<td><b>' . $ETI['saiu05idtemaorigen'] . '</b></td>
	<td><b>' . $ETI['saiu05idsolicitante'] . '</b></td>
	<td><b>' . $ETI['saiu05razonsocial'] . '</b></td>
	<td colspan="2"><b>' . $ETI['saiu05dia'] . '</b></td>
	<td><b>' . $ETI['saiu05estado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3005', $registros, $lineastabla, $pagina, 'paginarf3005()') . '
	' . html_lpp('lppf3005', $lineastabla, 'paginarf3005()') . '
	</td>
	</tr>';
	if ($sSQL != '') {
		$tlinea = 1;
		while ($filadet = $objDB->sf($tabladetalle)) {
			$sPrefijo = '';
			$sSufijo = '';
			$sClass = '';
			$sLink = '';
			$sTema = '';
			if (false) {
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
			}
			if (($tlinea % 2) == 0) {
				$sClass = ' class="resaltetabla"';
			}
			$tlinea++;
			$et_NumSol = f3000_NumSolicitud($filadet['saiu05agno'], $filadet['saiu05mes'], $filadet['saiu05consec']);
			$et_saiu05dia = '';
			$et_saiu05dia = fecha_armar($filadet['saiu05dia'], $filadet['saiu05mes'], $filadet['saiu05agno']);
			$et_saiu05hora = html_TablaHoraMin($filadet['saiu05hora'], $filadet['saiu05minuto']);
			//$et_saiu05fecharespprob='';
			//if ($filadet['saiu05fecharespprob']!='00/00/0000'){$et_saiu05fecharespprob=$filadet['saiu05fecharespprob'];}
			if ($bAbierta) {
				$sLink = '<a href="javascript:cargaridf3005(' . $filadet['saiu05agno'] . ', ' . $filadet['saiu05mes'] . ', ' . $filadet['saiu05id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			}
			if (isset($aTemas[$filadet['saiu05idtemaorigen']]) != 0) {
				$sTema = $aTemas[$filadet['saiu05idtemaorigen']];
			}
			$res = $res . '<tr' . $sClass . '>
			<td>' . $sPrefijo . $et_NumSol . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['saiu68nombre']) . $sSufijo . '</td>
			<td>' . $sPrefijo . $sTema . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['unad11tipodoc']) . cadena_notildes($filadet['unad11doc']) . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['unad11razonsocial']) . $sSufijo . '</td>
			<td>' . $sPrefijo . $et_saiu05dia . $sSufijo . '</td>
			<td>' . $sPrefijo . $et_saiu05hora . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo . '</td>
			<td>' . $sLink . '</td>
			</tr>';
		}
		$objDB->liberar($tabladetalle);
	}
	$res = $res . '</table>';
	return array(cadena_codificar($res), $sDebug);
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
	list($sDetalle, $sDebugTabla) = f3005_TablaDetalleCampus($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3005detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}