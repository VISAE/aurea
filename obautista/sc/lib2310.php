<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.2 martes, 17 de julio de 2018
--- 2310 Preguntas de la prueba
*/
function f2310_HTMLComboV2_cara10idbloque($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara10idbloque', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'revisaf2310()';
	$res = $objCombos->html('SELECT cara07id AS id, cara07nombre AS nombre FROM cara07bloqueeval', $objDB);
	return $res;
}
function f2310_db_Guardar($valores, $objDB, $bDebug = false)
{
	$iCodModulo = 2310;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2310)) {
		$mensajes_2310 = 'lg/lg_2310_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2310;
	$sError = '';
	$sDebug = '';
	$binserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$cara10idcara = numeros_validar($valores[1]);
	$cara10idbloque = numeros_validar($valores[2]);
	$cara10consec = numeros_validar($valores[3]);
	$cara10id = numeros_validar($valores[4], true);
	$cara10idpregunta = numeros_validar($valores[5]);
	$cara10idrpta = numeros_validar($valores[6]);
	$cara10puntaje = numeros_validar($valores[7]);
	$cara10nivelpregunta = numeros_validar($valores[8]);
	//if ($cara10idpregunta==''){$cara10idpregunta=0;}
	//if ($cara10idrpta==''){$cara10idrpta=0;}
	//if ($cara10puntaje==''){$cara10puntaje=0;}
	//if ($cara10nivelpregunta==''){$cara10nivelpregunta=0;}
	$sSepara = ', ';
	if ($cara10nivelpregunta == '') {
		$sError = $ERR['cara10nivelpregunta'] . $sSepara . $sError;
	}
	if ($cara10puntaje == '') {
		$sError = $ERR['cara10puntaje'] . $sSepara . $sError;
	}
	if ($cara10idrpta == '') {
		$sError = $ERR['cara10idrpta'] . $sSepara . $sError;
	}
	if ($cara10idpregunta == '') {
		$sError = $ERR['cara10idpregunta'] . $sSepara . $sError;
	}
	//if ($cara10id==''){$sError=$ERR['cara10id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($cara10consec==''){$sError=$ERR['cara10consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($cara10idbloque == '') {
		$sError = $ERR['cara10idbloque'] . $sSepara . $sError;
	}
	if ($cara10idcara == '') {
		$sError = $ERR['cara10idcara'] . $sSepara . $sError;
	}
	if ($sError == '') {
		if ((int)$cara10id == 0) {
			if ((int)$cara10consec == 0) {
				$cara10consec = tabla_consecutivo('cara10pregprueba', 'cara10consec', 'cara10idcara=' . $cara10idcara . ' AND cara10idbloque=' . $cara10idbloque . '', $objDB);
				if ($cara10consec == -1) {
					$sError = $objDB->serror;
				}
			} else {
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT cara10idcara FROM cara10pregprueba WHERE cara10idcara=' . $cara10idcara . ' AND cara10idbloque=' . $cara10idbloque . ' AND cara10consec=' . $cara10consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$sError = $ERR['2'];
					}
				}
			}
			if ($sError == '') {
				$cara10id = tabla_consecutivo('cara10pregprueba', 'cara10id', '', $objDB);
				if ($cara10id == -1) {
					$sError = $objDB->serror;
				}
				$binserta = true;
				$iAccion = 2;
			}
		} else {
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($binserta) {
			$cara10idrpta = 0;
			$cara10puntaje = 0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
			//$fila=$objDB->sf($tabla);
			//$sCampo=$fila['sCampo'];
			//}
			$sError = 'INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
		}
	}
	if ($sError == '') {
		if ($binserta) {
			$scampos = 'cara10idcara, cara10idbloque, cara10consec, cara10id, cara10idpregunta, 
cara10idrpta, cara10puntaje, cara10nivelpregunta';
			$svalores = '' . $cara10idcara . ', ' . $cara10idbloque . ', ' . $cara10consec . ', ' . $cara10id . ', ' . $cara10idpregunta . ', 
' . $cara10idrpta . ', ' . $cara10puntaje . ', ' . $cara10nivelpregunta . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cara10pregprueba (' . $scampos . ') VALUES (' . cadena_codificar($svalores) . ');';
			} else {
				$sSQL = 'INSERT INTO cara10pregprueba (' . $scampos . ') VALUES (' . $svalores . ');';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' {Preguntas de la prueba}.<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara10id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo2310[1] = 'cara10idpregunta';
			$scampo2310[2] = 'cara10nivelpregunta';
			$svr2310[1] = $cara10idpregunta;
			$svr2310[2] = $cara10nivelpregunta;
			$inumcampos = 2;
			$sWhere = 'cara10id=' . $cara10id . '';
			//$sWhere='cara10idcara='.$cara10idcara.' AND cara10idbloque='.$cara10idbloque.' AND cara10consec='.$cara10consec.'';
			$sSQL = 'SELECT * FROM cara10pregprueba WHERE ' . $sWhere;
			$sdatos = '';
			$bpasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $inumcampos; $k++) {
					if ($filaorigen[$scampo2310[$k]] != $svr2310[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo2310[$k] . '="' . $svr2310[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE cara10pregprueba SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE cara10pregprueba SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Preguntas de la prueba}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cara10id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $cara10id, $sDebug);
}
function f2310_db_Eliminar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 2310;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2310)) {
		$mensajes_2310 = 'lg/lg_2310_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2310;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$cara10idcara = numeros_validar($aParametros[1]);
	$cara10idbloque = numeros_validar($aParametros[2]);
	$cara10consec = numeros_validar($aParametros[3]);
	$cara10id = numeros_validar($aParametros[4]);
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2310';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $cara10id . ' LIMIT 0, 1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sError = $filaor['mensaje'];
				if ($filaor['etiqueta'] != '') {
					if (isset($ERR[$filaor['etiqueta']]) != 0) {
						$sError = $ERR[$filaor['etiqueta']];
					}
				}
				break;
			}
		}
	}
	if ($sError == '') {
		//acciones previas
		$sWhere = 'cara10id=' . $cara10id . '';
		//$sWhere='cara10idcara='.$cara10idcara.' AND cara10idbloque='.$cara10idbloque.' AND cara10consec='.$cara10consec.'';
		$sSQL = 'DELETE FROM cara10pregprueba WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2310 Preguntas de la prueba}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara10id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2310_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2310)) {
		$mensajes_2310 = 'lg/lg_2310_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2310;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = 0;
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = 0;
	}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$sDebug = '';
	$cara01id = numeros_validar($aParametros[0]);
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$idBloque = numeros_validar($aParametros[100]);
	$bEstudiante = false;
	if ($aParametros[103] == 1) {
		$bEstudiante = true;
	}
	$babierta = false;
	$sSQL = 'SELECT cara01completa FROM cara01encuesta WHERE cara01id=' . $cara01id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['cara01completa'] != 'S') {
			$babierta = true;
		}
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sLeyenda = '';
	if (false) {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		<b>Importante:</b> Mensaje al usuario
		<div class="salto1px"></div>
		</div>';
	}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$iPiel = iDefinirPiel($APP, 2);
	$objCombos = new clsHtmlCombos('n');
	$sTitulos = 'Cara, Bloque, Consec, Id, Pregunta, Rpta, Puntaje, Nivelpregunta';
	$sSQL = 'SELECT TB.cara10idcara, TB.cara10consec, TB.cara10id, T5.cara08cuerpo, TB.cara10idrpta, 
	TB.cara10puntaje, TB.cara10nivelpregunta, TB.cara10idbloque, TB.cara10idpregunta, T5.cara08idgrupo,
	T5.cara08retroalimenta 
	FROM cara10pregprueba AS TB, cara08pregunta AS T5 
	WHERE ' . $sSQLadd1 . ' TB.cara10idcara=' . $cara01id . ' AND TB.cara10idbloque=' . $idBloque . ' AND TB.cara10idpregunta=T5.cara08id ' . $sSQLadd . '
	ORDER BY T5.cara08idgrupo, TB.cara10consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2310" name="consulta_2310" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2310" name="titulos_2310" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2310: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array(cadena_codificar($sErrConsulta . '<input id="paginaf2310" name="paginaf2310" type="hidden" value="' . $pagina . '"/><input id="lppf2310" name="lppf2310" type="hidden" value="' . $lineastabla . '"/>'), $sDebug);
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
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<tbody>';
	$tlinea = 1;
	$idGrupo = 0;
	$iColumnas = 4;
	$bPrimera = true;
	$sClass = '';
	if (!$bEstudiante) {
		$iColumnas = 6;
	}
	while ($filadet = $objDB->sf($tabladetalle)) {
		if (!$bPrimera) {
			$res = $res . '<tr' . $sClass . '>
			<td colspan="5">&nbsp;</td>
			</tr>';
		}
		$bPrimera = false;
		if ($filadet['cara08idgrupo'] != $idGrupo) {
			$idGrupo = $filadet['cara08idgrupo'];
			if ($filadet['cara08idgrupo'] != 0) {
				//Mostar el grupo.
				$sSQL = 'SELECT cara06cuerpo FROM cara06grupopreg WHERE cara06id=' . $filadet['cara08idgrupo'] . '';
				$tabla6 = $objDB->ejecutasql($sSQL);
				if ($fila6 = $objDB->nf($tabla6) > 0) {
					$fila6 = $objDB->sf($tabla6);
					$res = $res . '<tr>';
					$res = $res . '<td colspan="' . $iColumnas . '">' . $fila6['cara06cuerpo'] . '</td>';
					$res = $res . '</tr>';
				}
			}
		}
		//Ahora is la pregunta.
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		if (!$bEstudiante) {
			if ($filadet['cara10puntaje'] != 0) {
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
			}
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_cara10consec = $sPrefijo . $filadet['cara10consec'] . $sSufijo;
		$et_cara10idpregunta = $sPrefijo . cadena_notildes($filadet['cara08cuerpo']) . $sSufijo;
		$et_cara10idrpta = $ETI['msg_ninguna'];
		$sClassRpta = $sClass;
		if (!$bEstudiante) {
			$et_cara10puntaje = '<td align="center">&nbsp;' . $sPrefijo . $filadet['cara10puntaje'] . $sSufijo . '&nbsp;</td>';
			$et_cara10nivelpregunta = 'Basica';
			if ($filadet['cara10nivelpregunta'] == 1) {
				$et_cara10nivelpregunta = 'Profundizaci&oacute;n';
			}
			$et_cara10nivelpregunta = '<td>' . $sPrefijo . $et_cara10nivelpregunta . $sSufijo . '</td>';
			if ($filadet['cara10idrpta'] != 0) {
				$et_cara10idrpta = $sPrefijo . '{' . $filadet['cara10idrpta'] . '}' . $sSufijo;
				$sSQL = 'SELECT cara09contenido FROM cara09pregrpta WHERE cara09id=' . $filadet['cara10idrpta'] . '';
				$tabla9 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla9) > 0) {
					$fila9 = $objDB->sf($tabla9);
					$et_cara10idrpta = $sPrefijo . '' . $fila9['cara09contenido'] . '' . $sSufijo;
				}
			}
		} else {
			$et_cara10consec = '';
			$et_cara10puntaje = '';
			$et_cara10nivelpregunta = '';
			//Tenemos que traer la opcion de respuesta...
			if ($babierta) {
				if ($filadet['cara10idrpta'] == 0) {
					$sClassRpta = ' style="background-color:red;padding: 5px;"';
				} else {
					$sClassRpta = ' style="background-color:green;padding: 5px;"';
				}
				$objCombos->nuevo('cara10idrpta_' . $filadet['cara10id'], $filadet['cara10idrpta'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
				$objCombos->iAncho = 450;
				$objCombos->sAccion = 'guardarpregunta(' . $filadet['cara10id'] . ', this.value);';
				$sSQL = 'SELECT cara09id, cara09contenido 
				FROM cara09pregrpta WHERE cara09idpregunta=' . $filadet['cara10idpregunta'] . ' ORDER BY cara09consec';
				$tabla9 = $objDB->ejecutasql($sSQL);
				while ($fila9 = $objDB->sf($tabla9)) {
					$objCombos->addItem($fila9['cara09id'], cadena_notildes($fila9['cara09contenido']));
				}
				$et_cara10idrpta = $objCombos->html('', $objDB);
			} else {
				$et_cara10idrpta = $sPrefijo . cadena_notildes(f2310_TituloRespuesta($filadet['cara10idrpta'], $objDB));
				$et_cara08retroalimenta = '';
				if ($filadet['cara08retroalimenta'] != '') {
					$et_cara08retroalimenta = '<div class="GrupoCamposAyuda"><b>' . $ETI['cara10retroalimenta'] . ':</b> ' . cadena_notildes($filadet['cara08retroalimenta']) . '</div>';
				}
				$et_cara10idrpta = $et_cara10idrpta . $et_cara08retroalimenta . $sSufijo;
			}
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_cara10consec . '</td>';
		$res = $res . '<td colspan="4">'; 
		$sSQL = 'SELECT cara17idorigen, cara17idanexo, cara17nombre FROM cara17preganexo WHERE cara17idpregunta=' . $filadet['cara10idpregunta'] . ' ORDER BY cara17consec';
		$tabla17 = $objDB->ejecutasql($sSQL);
		while ($fila17 = $objDB->sf($tabla17)) {
			$cara17nombre = strtolower($fila17['cara17nombre']);
			$cara17idanexo = $fila17['cara17idanexo'];
			$bEsImagen = cadena_contiene($cara17nombre, 'imagen');
			$bEsAudio = cadena_contiene($cara17nombre, 'audio');
			if ($cara17idanexo > 0) {
				$res = $res . '<p align="center"><i>' . $fila17['cara17nombre'] . '</i></p>';
				$res = $res . '<p align="center">';
				$urlArchivo = url_encode($fila17['cara17idorigen'] . '|' . $fila17['cara17idanexo']);
				if ($bEsImagen) {
					$res = $res . '<img src="verarchivo.php?u=' . $urlArchivo . '" alt="' . $fila17['cara17nombre'] . '" />';
				}
				if ($bEsAudio) {
					$res = $res . '<audio src="verarchivo.php?u=' . $urlArchivo . '" controls="controls" type="audio/mpeg" preload="preload">Su navegador no soporta el elemento de audio.</audio>';
				}
				$res = $res . '</p>';
			}
		}
		$res = $res . '<p align="justify">' . $et_cara10idpregunta . '</p>';
		$res = $res . '</td></tr>';
		$res = $res . '<tr id="fila_rpta_' . $filadet['cara10id'] . '"' . $sClassRpta . '>';
		$res = $res . '<td></td>';
		$res = $res . '<td align="left">' . $et_cara10idrpta . '</td>';
		$res = $res . $et_cara10puntaje;
		$res = $res . $et_cara10nivelpregunta;
		$res = $res . '<td><div id="div_preg' . $filadet['cara10id'] . '"></div></td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array($res, $sDebug);
}
function f2310_Clonar($cara10idcara, $cara10idcaraPadre, $objDB)
{
	$sError = '';
	$cara10consec = tabla_consecutivo('cara10pregprueba', 'cara10consec', 'cara10idcara=' . $cara10idcara . '', $objDB);
	if ($cara10consec == -1) {
		$sError = $objDB->serror;
	}
	$cara10id = tabla_consecutivo('cara10pregprueba', 'cara10id', '', $objDB);
	if ($cara10id == -1) {
		$sError = $objDB->serror;
	}
	if ($sError == '') {
		$sCampos2310 = 'cara10idcara, cara10idbloque, cara10consec, cara10id, cara10idpregunta, cara10idrpta, cara10puntaje, cara10nivelpregunta';
		$sValores2310 = '';
		$sSQL = 'SELECT * FROM cara10pregprueba WHERE cara10idcara=' . $cara10idcaraPadre . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($sValores2310 != '') {
				$sValores2310 = $sValores2310 . ', ';
			}
			$sValores2310 = $sValores2310 . '(' . $cara10idcara . ', ' . $fila['cara10idbloque'] . ', ' . $cara10consec . ', ' . $cara10id . ', ' . $fila['cara10idpregunta'] . ', ' . $fila['cara10idrpta'] . ', ' . $fila['cara10puntaje'] . ', ' . $fila['cara10nivelpregunta'] . ')';
			$cara10consec++;
			$cara10id++;
		}
		if ($sValores2310 != '') {
			$sSQL = 'INSERT INTO cara10pregprueba(' . $sCampos2310 . ') VALUES ' . $sValores2310 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return $sError;
}
// -- 2310 Preguntas de la prueba XAJAX 
function f2310_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$bHayDb = false;
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		list($sError, $iAccion, $cara10id, $sDebugGuardar) = f2310_db_Guardar($valores, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f2310_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2310detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
		//$objResponse->call('cargaridf2310('.$cara10id.')');
		//}else{
		$objResponse->call('limpiaf2310');
		//}
		$objResponse->call("MensajeAlarmaV2('" . $ETI['msg_itemguardado'] . "', 1)");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0)");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2310_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$cara10idcara = numeros_validar($aParametros[1]);
		$cara10idbloque = numeros_validar($aParametros[2]);
		$cara10consec = numeros_validar($aParametros[3]);
		if (($cara10idcara != '') && ($cara10idbloque != '') && ($cara10consec != '')) {
			$besta = true;
		}
	} else {
		$cara10id = $aParametros[103];
		if ((int)$cara10id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$besta = false;
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'cara10idcara=' . $cara10idcara . ' AND cara10idbloque=' . $cara10idbloque . ' AND cara10consec=' . $cara10consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'cara10id=' . $cara10id . '';
		}
		$sSQL = 'SELECT * FROM cara10pregprueba WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		if (isset($APP->piel) == 0) {
			$APP->piel = 1;
		}
		$iPiel = $APP->piel;
		list($cara10idbloque_nombre, $serror_det) = tabla_campoxid('cara07bloqueeval', 'cara07nombre', 'cara07id', $fila['cara10idbloque'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_cara10idbloque = html_oculto('cara10idbloque', $fila['cara10idbloque'], $cara10idbloque_nombre);
		$objResponse->assign('div_cara10idbloque', 'innerHTML', $html_cara10idbloque);
		$cara10consec_nombre = '';
		$html_cara10consec = html_oculto('cara10consec', $fila['cara10consec'], $cara10consec_nombre);
		$objResponse->assign('div_cara10consec', 'innerHTML', $html_cara10consec);
		$cara10id_nombre = '';
		$html_cara10id = html_oculto('cara10id', $fila['cara10id'], $cara10id_nombre);
		$objResponse->assign('div_cara10id', 'innerHTML', $html_cara10id);
		$objResponse->assign('cara10idpregunta', 'value', $fila['cara10idpregunta']);
		$cara10idrpta_eti = $fila['cara10idrpta'];
		$html_cara10idrpta = html_oculto('cara10idrpta', $fila['cara10idrpta'], $cara10idrpta_eti);
		$objResponse->assign('div_cara10idrpta', 'innerHTML', $html_cara10idrpta);
		$cara10puntaje_eti = $fila['cara10puntaje'];
		$html_cara10puntaje = html_oculto('cara10puntaje', $fila['cara10puntaje'], $cara10puntaje_eti);
		$objResponse->assign('div_cara10puntaje', 'innerHTML', $html_cara10puntaje);
		$objResponse->assign('cara10nivelpregunta', 'value', $fila['cara10nivelpregunta']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2310','block')");
	} else {
		if ($paso == 1) {
			$objResponse->assign('cara10idbloque', 'value', $cara10idbloque);
			$objResponse->assign('cara10consec', 'value', $cara10consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $cara10id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2310_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
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
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f2310_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2310_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2310detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2310');
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
function f2310_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2310_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2310detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2310_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	if (isset($APP->piel) == 0) {
		$APP->piel = 1;
	}
	$iPiel = $APP->piel;
	$objCombos = new clsHtmlCombos('n');
	$html_cara10idbloque = f2310_HTMLComboV2_cara10idbloque($objDB, $objCombos, 0);
	$html_cara10consec = '<input id="cara10consec" name="cara10consec" type="text" value="" onchange="revisaf2310()" class="cuatro"/>';
	$html_cara10id = '<input id="cara10id" name="cara10id" type="hidden" value=""/>';
	$html_cara10idrpta = html_oculto('cara10idrpta', '');
	$html_cara10puntaje = html_oculto('cara10puntaje', '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara10idbloque', 'innerHTML', $html_cara10idbloque);
	$objResponse->assign('div_cara10consec', 'innerHTML', $html_cara10consec);
	$objResponse->assign('div_cara10id', 'innerHTML', $html_cara10id);
	$objResponse->assign('div_cara10idrpta', 'innerHTML', $html_cara10idrpta);
	$objResponse->assign('div_cara10puntaje', 'innerHTML', $html_cara10puntaje);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2310_GuardarRespuesta($valores)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$bHayDb = false;
	//$opts=$aParametros;
	//if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	//if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	if (isset($valores[0]) == 0) {
		$valores[0] = '';
	}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		//Aqui la operacion.
		$idRpta = $valores[1];
		$iPuntaje = 0;
		if ($idRpta != 0) {
			//Buscamos la data de la respuesta.
			$sSQL = 'SELECT cara09valor FROM cara09pregrpta WHERE cara09id=' . $idRpta . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$iPuntaje = $fila['cara09valor'];
			}
		}
		$sSQL = 'UPDATE cara10pregprueba SET cara10idrpta=' . $idRpta . ', cara10puntaje=' . $iPuntaje . ' WHERE cara10id=' . $valores[0] . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_preg' . $valores[0], 'innerHTML', '<span class="verde">Guardado</span>');
	//$objResponse->call("MensajeAlarmaV2('".$valores[0]." - ".$valores[1]."', 0)");
	/*
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2310_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2310('.$cara10id.')');
			//}else{
			$objResponse->call('limpiaf2310');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	*/
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f2310_TituloRespuesta($id, $objDB)
{
	$res = $id;
	$sSQL = 'SELECT cara09contenido, cara09valor FROM cara09pregrpta WHERE cara09id=' . $id . '';
	$tabla9 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla9) > 0) {
		$fila9 = $objDB->sf($tabla9);
		$sChequeo = '<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>';
		if ($fila9['cara09valor'] > 0) {
			$sChequeo = '<i class="fa fa-check" aria-hidden="true" style="color:green;"></i>';
		}
		$res = $fila9['cara09contenido'] . $sChequeo;
	}
	return $res;
}
function f2310_TablaDetalleV2Ajusta($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2310)) {
		$mensajes_2310 = 'lg/lg_2310_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2310;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$sDebug = '';
	$cara01id = $aParametros[0];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$babierta = false;
	$sSQL = 'SELECT cara01completa FROM cara01encuesta WHERE cara01id=' . $cara01id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['cara01completa'] != 'S') {
			$babierta = true;
		}
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sLeyenda = '';
	if (false) {
		$sLeyenda = '<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
	}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[104] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara10idbloque=' . $aParametros[104] . ' AND ';
	}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Cara, Bloque, Consec, Id, Pregunta, Rpta, Puntaje, Nivelpregunta';
	$sSQL = 'SELECT TB.cara10idcara, T2.cara07nombre, TB.cara10consec, TB.cara10id, T5.cara08titulo, TB.cara10idrpta, TB.cara10puntaje, TB.cara10nivelpregunta, TB.cara10idbloque, TB.cara10idpregunta 
FROM cara10pregprueba AS TB, cara07bloqueeval AS T2, cara08pregunta AS T5 
WHERE ' . $sSQLadd1 . ' TB.cara10idcara=' . $cara01id . ' AND TB.cara10idbloque=T2.cara07id AND TB.cara10idpregunta=T5.cara08id ' . $sSQLadd . '
ORDER BY TB.cara10idbloque, TB.cara10consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2310" name="consulta_2310" type="hidden" value="' . $sSQLlista . '"/>
<input id="titulos_2310" name="titulos_2310" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2310: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2310" name="paginaf2310" type="hidden" value="'.$pagina.'"/><input id="lppf2310" name="lppf2310" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>' . $ETI['cara10consec'] . '</b></td>
<td><b>' . $ETI['cara10idpregunta'] . '</b></td>
<td><b>' . $ETI['cara10idrpta'] . '</b></td>
<td><b>' . $ETI['cara10puntaje'] . '</b></td>
<td><b>' . $ETI['cara10nivelpregunta'] . '</b></td>
<td align="right">
' . html_paginador('paginaf2310', $registros, $lineastabla, $pagina, 'paginarf2310()') . '
' . html_lpp('lppf2310', $lineastabla, 'paginarf2310()') . '
</td>
</tr>';
	$tlinea = 1;
	$idBloque = -1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idBloque != $filadet['cara10idbloque']) {
			$idBloque = $filadet['cara10idbloque'];
			$res = $res . '<tr class="fondoazul">
<td colspan="6">' . $ETI['cara10idbloque'] . ' <b>' . cadena_notildes($filadet['cara07nombre']) . '</b></td>
</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		$et_cara10idrpta = '';
		if ($filadet['cara10idrpta'] != 0) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			$et_cara10idrpta = $sPrefijo . $filadet['cara10idrpta'] . $sSufijo;
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_cara10consec = $sPrefijo . $filadet['cara10consec'] . $sSufijo;
		$et_cara10idpregunta = $sPrefijo . cadena_notildes($filadet['cara08titulo']) . $sSufijo;
		$et_cara10puntaje = $sPrefijo . $filadet['cara10puntaje'] . $sSufijo;
		$et_cara10nivelpregunta = $sPrefijo . $filadet['cara10nivelpregunta'] . $sSufijo;
		if ($babierta) {
			$sLink = '<a href="javascript:quitaridf2310(' . $filadet['cara10id'] . ')" class="lnkresalte">' . $ETI['lnk_quitar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
<td>' . $et_cara10consec . '</td>
<td>' . $et_cara10idpregunta . '</td>
<td>' . $et_cara10idrpta . '</td>
<td>' . $et_cara10puntaje . '</td>
<td>' . $et_cara10nivelpregunta . '</td>
<td>' . $sLink . '</td>
</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}

function f2310_HtmlTablaAjusta($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2310_TablaDetalleV2Ajusta($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2310detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2310_db_Quitar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 2310;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2310)) {
		$mensajes_2310 = 'lg/lg_2310_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2310;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$cara10id = numeros_validar($aParametros[4]);
	if ($sError == '') {
		if (!seg_revisa_permiso(2312, 3, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		//acciones previas
		$sWhere = 'cara10id=' . $cara10id . '';
		//$sWhere='cara10idcara='.$cara10idcara.' AND cara10idbloque='.$cara10idbloque.' AND cara10consec='.$cara10consec.'';
		$sSQL = 'DELETE FROM cara10pregprueba WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {2310 Preguntas de la prueba}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara10id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2310_Quitar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
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
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f2310_db_Quitar($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f2310_TablaDetalleV2Ajusta($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f2310detalle', 'innerHTML', $sDetalle);
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
