<?php
/*
--- © Omar Augusto Bautista MOra - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- lunes, 28 de noviembre de 2022
--- El proposito de esta libreria es redirigir a las opciones de consulta o radicado de PQRS
*/
function f3005_HTMLOpcionInicial($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	// $objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	// if ($APP->dbpuerto != '') {
	// 	$objDB->dbPuerto = $APP->dbpuerto;
	// }

	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($_SESSION['unad_id_tercero']) == 0) {
		$_SESSION['unad_id_tercero'] = 0;
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = '';
	}
	$sError = '';
	$sHTML = '';
	$sScript = '';
	$sSepara = ', ';
	$enSesion = false;
	$idTercero = $aParametros[100];
	$iOpcion = numeros_validar($aParametros[101]);
	if ($_SESSION['unad_id_tercero'] > 0) {
		if ($idTercero == $_SESSION['unad_id_tercero']) {
			$enSesion = true;
		} else {
			$sError = $ERR['saiu05idtercero'] . $sSepara . $sError;
		}
	}
	if ($iOpcion == '') {
		$sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
	}
	if ($sError == '') {
		if ($iOpcion == 1) {
			$sHTML = $sHTML . '<form id="frmcodigo" name="frmcodigo" method="post" action="" autocomplete="off">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="saui05numref" class="text-center">' . $ETI['ing_campo'] . $ETI['saiu05refdoc'] . '</label>
                    <input id="saui05numref" name="saui05numref" class="form-control form-control-lg text-center" type="text" value="" maxlength="20" placeholder="' . $ETI['digite'] . '" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-light px-4 float-right" title="' . $ETI['bt_regresar'] . '" value="' . $ETI['bt_regresar'] . '" onclick="window.location.reload()">
                    <input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_consultar'] . '" value="' . $ETI['bt_consultar'] . '" onclick="enviacodigo()">
                </div>
            </div>
        </form>';
		} else if ($iOpcion == 2) {
			if ($enSesion) {
				$sScript = $sScript . 'window.location.href="saipqrs.php";';
			} else {
				$sHTML = $sHTML . '<div class="form-group row">
                    <div class="col-sm-12 pt-2">' . $ETI['msg_iracampus'] . '</div>
                    <div class="col-sm-12">
                        <a id="cmdIrACampus" name="cmdIrACampus" class="btn btn-aurea w-50" title="' . $ETI['bt_campus'] . '" href="https://campus0a.unad.edu.co/campus/saipqrs.php" target="_blank" >' . $ETI['bt_campus'] . '</a>
                    </div>
					<div class="col-sm-12 pt-2">' . $ETI['msg_irainscribirse'] . '</div>
                    <div class="col-sm-12">
                        <a id="cmdIrAInscribirse" name="cmdIrAInscribirse" class="btn btn-aurea w-50" title="' . $ETI['bt_inscribirse'] . '" href="https://campus0d.unad.edu.co/campus/inscripcion.php">' . $ETI['bt_inscribirse'] . '</a>
                    </div>
					<div class="col-sm-12 pt-2">' . $ETI['msg_iranonimo'] . '</div>
                    <div class="col-sm-12">
                        <a id="cmdIrAAnonimo" name="cmdIrAAnonimo" class="btn btn-aurea w-50" title="' . $ETI['bt_anonimo'] . '" href="javascript:;" onclick="ingresaanonimo();">' . $ETI['bt_anonimo'] . '</a>
                    </div>
                </div>
				<div class="form-group row">
					<div class="col-sm-12">
						<input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-light px-4 float-right" title="' . $ETI['bt_regresar'] . '" value="' . $ETI['bt_regresar'] . '" onclick="window.location.reload()">
					</div>
            	</div>';
			}
		} else {
			$sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
		}
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->script($sScript);
		$objResponse->assign('div_saiu05rutaspqrs', 'innerHTML', $sHTML);
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f3005_IngresaAnonimo()
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	// $objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	// if ($APP->dbpuerto != '') {
	// 	$objDB->dbPuerto = $APP->dbpuerto;
	// }
	$sError = '';
	$sScript = '';
	if (isset($_SESSION['unad_id_tercero']) == 0) {
		$_SESSION['unad_id_tercero'] = 0;
	}
	if ($_SESSION['unad_id_tercero'] == 0) {
		$_SESSION['unad_id_tercero'] = 1;
		$sScript = $sScript . 'window.location.href="saipqrs.php";';
	} else {
		$sError = $sError . $ETI['sesioniniciada'] . '';
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->script($sScript);
	} else {
		$objResponse->call('muestramensajes("warning", "' . $sError . '")');
	}
	return $objResponse;
}
function f3005_ConsultaCodigo($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3005 = 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3005;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	$sHTML = '';
	$sHTMLMsg = '';
	$sTipoMsg = '';
	$sSepara = ' ';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	$saui05numref = trim(cadena_Validar($aParametros[100], true));
	if (true) {
		if ($saui05numref == '') {
			$sTipoMsg = 'danger';
			$sHTMLMsg = $ERR['codigo'];
			$sError = $ERR['saiu05refdoc'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	if ($sError == '') {
		$aCodigo = explode('-', $saui05numref);
		if (count($aCodigo) < 3) {
			$sTipoMsg = 'danger';
			$sHTMLMsg = $ERR['codigo'];
			$sError = $ERR['codigo'];
		} else {
			$sTabla05 = 'saiu05solicitud_' . $aCodigo[0];
			$sIdSolicitiud = $aCodigo[1];
			if (!$objDB->bexistetabla($sTabla05)) {
				$sTipoMsg = 'danger';
				$sHTMLMsg = $ERR['contenedor'];
				$sError = $ERR['contenedor'];
			} else {
				list($sErrorR, $sDebugR) = f3005_RevisarTabla($aCodigo[0], $objDB);
				if ($sErrorR == '') {
					$sSQL = 'SELECT saiu05estado, saiu05fecharespdef, saiu05horarespdef, saiu05minrespdef, 
						saiu05respuesta, saiu05idorigen, saiu05idarchivo
						FROM ' . $sTabla05 . ' WHERE saiu05id = ' . $sIdSolicitiud . ' AND saiu05numref = "' . $saui05numref . '"';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$saiu05estado = $fila['saiu05estado'];
						$saiu05fecharespdef = $fila['saiu05fecharespdef'];
						$saiu05horarespdef = $fila['saiu05horarespdef'];
						$saiu05minrespdef = $fila['saiu05minrespdef'];
						$saiu05respuesta = nl2br($fila['saiu05respuesta']);
						$saiu05idorigen = $fila['saiu05idorigen'];
						$saiu05idarchivo = $fila['saiu05idarchivo'];
						$sTablaAnotacion05 = 'saiu06solanotacion_' . $aCodigo[0];
						$sSQL = 'SELECT saiu06consec, saiu06anotacion, saiu06fecha, saiu06hora, saiu06minuto, saiu06idorigen, saiu06idarchivo, saiu06visible 
						FROM ' . $sTablaAnotacion05 . ' 
						WHERE saiu06idsolicitud = ' . $sIdSolicitiud . '';
						$tabla = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla) > 0) {
							$sHTML = $sHTML . '<table class="table table-striped table-hover"><thead>';
							$sHTML = $sHTML . '<tr>
							<th>Consecutivo</th>
							<th>Anotaci&oacute;n</th>
							<th>Fecha</th>						
							<th>Documento</th>						
							</tr></thead><tbody>';
							while ($fila = $objDB->sf($tabla)) {
								if ($fila['saiu06visible'] == 'S') {
								$sHtmlLnkArchivo = '&nbsp;';
								$sTitulo = 'Descargar';
								if ((int)$fila['saiu06idorigen'] != 0) {
									$sHtmlLnkArchivo = '<a href="https://aurea.unad.edu.co/dp.php?u=' . url_encode((int)$fila['saiu06idorigen'] . '|' . (int)$fila['saiu06idarchivo']) . '" target="_blank" class="lnkresalte">' . $sTitulo . '</a>';
								}
								$sHTML = $sHTML . '<tr>
								<td>' . $fila['saiu06consec'] . '</td>
								<td>' . $fila['saiu06anotacion'] . '</td>
								<td>' . formato_FechaLargaDesdeNumero($fila['saiu06fecha']) . '</td>
								<td>' . $sHtmlLnkArchivo . '</td>
								</tr>';
								}
							}
							$sHTML = $sHTML . '</tbody></table>';
							if ($saiu05estado == 7) {
								$sHTML = $sHTML . '<div class="card bg-light mb-3">
									<h5 class="card-header">Respuesta</h5>
									<div class="card-body">
										<h5 class="card-title">' . formato_FechaLargaDesdeNumero($saiu05fecharespdef, true) . '</h5>
										<p class="card-text text-justify">' . $saiu05respuesta . '</p>';
								if ($saiu05idarchivo > 0) {
									$sTitulo = 'Descargar anexo de respuesta';
									$sHTML = $sHTML . '<a href="https://aurea.unad.edu.co/dp.php?u=' . url_encode((int)$saiu05idorigen . '|' . (int)$saiu05idarchivo) . '" target="_blank" class="btn btn-primary">' . $sTitulo . '</a>';
								}
								$sHTML = $sHTML . '</div>
								</div>';
							}
							$sHTML = $sHTML . '<div class="form-group row">
								<div class="col-sm-12">
									<input type="button" id="cmdContinuar" name="cmdContinuar" class="btn btn-aurea w-25 mt-2" title="' . $ETI['bt_continuar'] . '" value="' . $ETI['bt_continuar'] . '" onclick="window.location.href=\'index.php\';">
								</div>
							</div>';
						} else {
							$sError = $ERR['encontrado'];
						}
						$sTablaSolicitud05 = 'saiu05solicitud_' . $aCodigo[0];
						$sSQL = 'SELECT TB.saiu05dia, TB.saiu05hora, TB.saiu05minuto, T1.`saiu11nombre`
						FROM ' . $sTablaSolicitud05 . ' AS TB, saiu11estadosol AS T1
						WHERE TB.saiu05estado=T1.saiu11id AND TB.saiu05id = ' . $sIdSolicitiud . '';
						$tabla = $objDB->ejecutasql($sSQL);
						if ($fila = $objDB->sf($tabla)) {
							$sEstado = $fila['saiu11nombre'];
							if ($fila['saiu05dia'] < 10) {
								$fila['saiu05dia'] = '0' . $fila['saiu05dia'];
							}
							$sFechaRad = formato_FechaLargaDesdeNumero($aCodigo[0] . $fila['saiu05dia']);
							$sTipoMsg = 'success';
							$sHTMLMsg = 'La PQRS se encuentra en estado ' . $sEstado . '<br>Fecha de radicado: ' . $sFechaRad . '';
						}
					} else {
						$sTipoMsg = 'danger';
						$sHTMLMsg = $sErrorR;
						$sError = $sErrorR;
					}
				} else {
					$sTipoMsg = 'danger';
					$sHTMLMsg = $ERR['codigo'];
					$sError = $ERR['codigo'];
				}
			}
		}
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		// $objResponse->script($sScript);
		$objResponse->assign('div_saiu05rutaspqrs', 'innerHTML', $sHTML);
		$sError = $ERR['encontrado'];
	}
	$objResponse->call('muestramensajes("' . $sTipoMsg . '", "' . $sError . '")');
	$objResponse->assign('div_mensajes', 'className', 'p-3 mb-2 bg-' . $sTipoMsg . ' text-white text-center');
	$objResponse->assign('div_mensajes', 'innerHTML', $sHTMLMsg);
	return $objResponse;
}
function f3005_db_Guardar($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 3005;
	$bAudita[2] = true;
	$bAudita[3] = true;
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
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu05consec'])==0){$DATA['saiu05consec']='';}
	if (isset($DATA['saiu05id'])==0){$DATA['saiu05id']='';}
	if (isset($DATA['saiu05dia'])==0){$DATA['saiu05dia']='';}
	if (isset($DATA['saiu05idtiposolorigen'])==0){$DATA['saiu05idtiposolorigen']='';}
	if (isset($DATA['saiu05idtemaorigen'])==0){$DATA['saiu05idtemaorigen']='';}
	if (isset($DATA['saiu05idinteresado'])==0){$DATA['saiu05idinteresado']='';}
	if (isset($DATA['saiu05tipointeresado'])==0){$DATA['saiu05tipointeresado']='';}
	if (isset($DATA['saiu05rptaforma'])==0){$DATA['saiu05rptaforma']='';}
	if (isset($DATA['saiu05rptacorreo'])==0){$DATA['saiu05rptacorreo']='';}
	if (isset($DATA['saiu05rptadireccion'])==0){$DATA['saiu05rptadireccion']='';}
	if (isset($DATA['saiu05costogenera'])==0){$DATA['saiu05costogenera']='';}
	if (isset($DATA['saiu05costovalor'])==0){$DATA['saiu05costovalor']='';}
	if (isset($DATA['saiu05detalle'])==0){$DATA['saiu05detalle']='';}
	if (isset($DATA['saiu05idresponsable'])==0){$DATA['saiu05idresponsable']='';}
	if (isset($DATA['saiu05idmoduloproc'])==0){$DATA['saiu05idmoduloproc']='';}
	if (isset($DATA['saiu05identificadormod'])==0){$DATA['saiu05identificadormod']='';}
	if (isset($DATA['saiu05numradicado'])==0){$DATA['saiu05numradicado']='';}
	*/
	$DATA['saiu05consec'] = numeros_validar($DATA['saiu05consec']);
	$DATA['saiu05hora'] = numeros_validar($DATA['saiu05hora']);
	$DATA['saiu05minuto'] = numeros_validar($DATA['saiu05minuto']);
	$DATA['saiu05idtiposolorigen'] = numeros_validar($DATA['saiu05idtiposolorigen']);
	$DATA['saiu05idtemaorigen'] = numeros_validar($DATA['saiu05idtemaorigen']);
	$DATA['saiu05tipointeresado'] = numeros_validar($DATA['saiu05tipointeresado']);
	$DATA['saiu05rptaforma'] = numeros_validar($DATA['saiu05rptaforma']);
	$DATA['saiu05rptacorreo'] = cadena_Validar(trim($DATA['saiu05rptacorreo']));
	$DATA['saiu05rptadireccion'] = cadena_Validar(trim($DATA['saiu05rptadireccion']));
	$DATA['saiu05costogenera'] = numeros_validar($DATA['saiu05costogenera']);
	$DATA['saiu05costovalor'] = numeros_validar($DATA['saiu05costovalor'], true);
	$DATA['saiu05costorefpago'] = cadena_Validar(trim($DATA['saiu05costorefpago']));
	$DATA['saiu05numref'] = cadena_Validar(trim($DATA['saiu05numref']));
	$DATA['saiu05detalle'] = cadena_Validar(trim($DATA['saiu05detalle']));
	$DATA['saiu05infocomplemento'] = cadena_Validar(trim($DATA['saiu05infocomplemento']));
	$DATA['saiu05respuesta'] = cadena_Validar(trim($DATA['saiu05respuesta']));
	$DATA['saiu05idmoduloproc'] = numeros_validar($DATA['saiu05idmoduloproc']);
	$DATA['saiu05identificadormod'] = numeros_validar($DATA['saiu05identificadormod']);
	$DATA['saiu05numradicado'] = numeros_validar($DATA['saiu05numradicado']);
	$DATA['saiu05idcategoria'] = numeros_validar($DATA['saiu05idcategoria']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu05origenid'] == '') {
		$DATA['saiu05origenid'] = 0;
	}
	//if ($DATA['saiu05hora']==''){$DATA['saiu05hora']=0;}
	//if ($DATA['saiu05minuto']==''){$DATA['saiu05minuto']=0;}
	//if ($DATA['saiu05estado']==''){$DATA['saiu05estado']=-1;}
	if ($DATA['saiu05idmedio'] == '') {
		$DATA['saiu05idmedio'] = 0;
	}
	//if ($DATA['saiu05idtiposolorigen']==''){$DATA['saiu05idtiposolorigen']=0;}
	//if ($DATA['saiu05idtemaorigen']==''){$DATA['saiu05idtemaorigen']=0;}
	//if ($DATA['saiu05tipointeresado']==''){$DATA['saiu05tipointeresado']=0;}
	if ($DATA['saiu05rptaforma'] == '') {
		$DATA['saiu05rptaforma'] = 0;
	}
	//if ($DATA['saiu05costogenera']==''){$DATA['saiu05costogenera']=0;}
	//if ($DATA['saiu05costovalor']==''){$DATA['saiu05costovalor']=0;}
	if ($DATA['saiu05idmoduloproc'] == '') {
		$DATA['saiu05idmoduloproc'] = 0;
	}
	if ($DATA['saiu05identificadormod'] == '') {
		$DATA['saiu05identificadormod'] = 0;
	}
	if ($DATA['saiu05agno'] == '') {
		$DATA['saiu05agno'] = 0;
	}
	if ($DATA['saiu05mes'] == '') {
		$DATA['saiu05mes'] = 0;
	}
	//if ($DATA['saiu05numradicado']==''){$DATA['saiu05numradicado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		/*
		if ($DATA['saiu05numradicado']==''){$sError=$ERR['saiu05numradicado'].$sSepara.$sError;}
		if ($DATA['saiu05identificadormod']==''){$sError=$ERR['saiu05identificadormod'].$sSepara.$sError;}
		if ($DATA['saiu05idmoduloproc']==''){$sError=$ERR['saiu05idmoduloproc'].$sSepara.$sError;}
		//if (!fecha_esvalida($DATA['saiu05fecharespprob'])){
			//$DATA['saiu05fecharespprob']='00/00/0000';
			//$sError=$ERR['saiu05fecharespprob'].$sSepara.$sError;
			//}
		if ($DATA['saiu05idresponsable']==0){$sError=$ERR['saiu05idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu05costovalor']==''){$sError=$ERR['saiu05costovalor'].$sSepara.$sError;}
		if ($DATA['saiu05costogenera']==''){$sError=$ERR['saiu05costogenera'].$sSepara.$sError;}
		if ($DATA['saiu05rptaforma']==''){$sError=$ERR['saiu05rptaforma'].$sSepara.$sError;}
		if ($DATA['saiu05tipointeresado']==''){$sError=$ERR['saiu05tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu05idinteresado']==0){$sError=$ERR['saiu05idinteresado'].$sSepara.$sError;}
		if ($DATA['saiu05idsolicitante']==0){$sError=$ERR['saiu05idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu05dia']==0){
			//$DATA['saiu05dia']=fecha_DiaMod();
			$sError=$ERR['saiu05dia'].$sSepara.$sError;
			}
		*/
		if ($DATA['saiu05detalle'] == '') {
			$sError = $ERR['saiu05detalle'] . $sSepara . $sError;
		}
		if ($DATA['saiu05idtiposolorigen'] == '') {
			$sError = $ERR['saiu05idtiposolorigen_2'] . $sSepara . $sError;
		} else {
			if ($DATA['saiu05idtemaorigen'] == '') {
				$sError = $ERR['saiu05idtemaorigen_2'] . $sSepara . $sError;
			}
		}
		if ($DATA['saiu05rptaforma'] == 1) {
			if (correo_VerificarDireccion($DATA['saiu05rptacorreo'])) {
			} else {
				$sError = $ERR['saiu05rptacorreo'] . $sSepara . $sError;
			}
		}
		if ($DATA['saiu05rptaforma'] == 2) {
			if ($DATA['saiu05rptadireccion'] == '') {
				$sError = $ERR['saiu05rptadireccion'] . $sSepara . $sError;
			}
		}
		if ($DATA['saiu05idmedio'] == '') {
			$sError = $ERR['saiu05idmedio'] . $sSepara . $sError;
		}
		if ($DATA['saiu05idcategoria'] == '') {
			$sError = $ERR['saiu05idcategoria'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	if ($DATA['saiu05rptaforma'] == 0) {
		$DATA['saiu05rptacorreo'] = '';
		$DATA['saiu05rptadireccion'] = '';
	}
	$iFechaHoy = fecha_DiaMod();
	$saiu05idzona = 0;
	$saiu05cead = 0;
	$saiu05idescuela = 0;
	$saiu05idprograma = 0;
	$saiu05tiemprespdias = 0;
	$saiu05tiempresphoras = 0;
	$saiu05fecharespprob = 0;
	$saiu05fecharespdef = 0;
	$saiu05horarespdef = 0;
	$saiu05minrespdef = 0;
	$saiu05idunidadresp = $DATA['saiu05idunidadresp'];
	$saiu05idequiporesp = $DATA['saiu05idequiporesp'];
	$saiu05idsupervisor = $DATA['saiu05idsupervisor'];
	$saiu05idresponsable = $DATA['saiu05idresponsable'];
	$sContenedor = fecha_ArmarAgnoMes($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla05 = 'saiu05solicitud' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$sTabla09 = 'saiu09cambioestado' . f3000_Contenedor($DATA['saiu05agno'], $DATA['saiu05mes']);
	$saiu05estadoorigen = -1;
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu05idresponsable_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idresponsable_td'], $DATA['saiu05idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu05idinteresado_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idinteresado_td'], $DATA['saiu05idinteresado_doc'], $objDB, 'El tercero Interesado ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idinteresado'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['saiu05idsolicitante_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['saiu05idsolicitante_td'], $DATA['saiu05idsolicitante_doc'], $objDB, 'El tercero Solicitante ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu05idsolicitante'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//El codigo no es posible que sea puesto por nadie.
			//$bQuitarCodigo=true;
			$DATA['saiu05consec'] = tabla_consecutivo($sTabla05, 'saiu05consec', 'saiu05agno=' . $DATA['saiu05agno'] . ' AND saiu05mes=' . $DATA['saiu05mes'] . ' AND saiu05tiporadicado=' . $DATA['saiu05tiporadicado'] . '', $objDB);
			if ($DATA['saiu05consec'] == -1) {
				$sError = $objDB->serror;
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sTabla05 . ' WHERE saiu05agno=' . $DATA['saiu05agno'] . ' AND saiu05mes=' . $DATA['saiu05mes'] . ' AND saiu05tiporadicado=' . $DATA['saiu05tiporadicado'] . ' AND saiu05consec=' . $DATA['saiu05consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					// if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			}
		} else {
			// if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
		}
	}
	$bQuitarCodigo = false;
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu05id'] = tabla_consecutivo($sTabla05, 'saiu05id', '', $objDB);
			if ($DATA['saiu05id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		if ($DATA['saiu05estado'] == 0) {
			list($aParametros, $sError, $iTipoError, $sDebugF) = f3000_ConsultaResponsable($DATA['saiu05idtemaorigen'], $DATA['saiu05idzona'], $DATA['saiu05cead'], $objDB, $bDebug);
			if ($sError == '') {
				$saiu05idunidadresp = $aParametros['idunidad'];
				$saiu05idequiporesp = $aParametros['idequipo'];
				$saiu05idsupervisor = $aParametros['idsupervisor'];
				$saiu05idresponsable = $aParametros['idresponsable'];
				$saiu05tiemprespdias = $aParametros['tiemprespdias'];
				$saiu05tiempresphoras = $aParametros['tiempresphoras'];
				if ($saiu05tiemprespdias > 0) {
					$saiu05fecharespprob = fecha_NumSumarDias($iFechaHoy, $saiu05tiemprespdias);
				}
			}
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT core01idzona AS idzona, core011idcead AS idcead, core01idescuela AS idescuela, core01idprograma AS idprograma
		FROM core01estprograma
		WHERE core01idtercero = ' . $DATA['saiu05idsolicitante'] . '
		UNION
		SELECT unad11idzona AS idzona, unad11idcead AS idcead, unad11idescuela AS idescuela, unad11idprograma AS idprograma
		FROM unad11terceros
		WHERE unad11id = ' . $DATA['saiu05idsolicitante'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$fila = $objDB->sf($result);
			$saiu05idzona = $fila['idzona'];
			$saiu05cead = $fila['idcead'];
			$saiu05idescuela = $fila['idescuela'];
			$saiu05idprograma = $fila['idprograma'];
		}
	}
	$bCalularTotales = false;
	if ($sError == '') {
		$DATA['saiu05detalle'] = stripslashes($DATA['saiu05detalle']);
		//Si el campo saiu05detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05detalle=addslashes($DATA['saiu05detalle']);
		$saiu05detalle = str_replace('"', '\"', $DATA['saiu05detalle']);
		$DATA['saiu05infocomplemento'] = stripslashes($DATA['saiu05infocomplemento']);
		//Si el campo saiu05infocomplemento permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05infocomplemento=addslashes($DATA['saiu05infocomplemento']);
		$saiu05infocomplemento = str_replace('"', '\"', $DATA['saiu05infocomplemento']);
		$DATA['saiu05respuesta'] = stripslashes($DATA['saiu05respuesta']);
		//Si el campo saiu05respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05respuesta=addslashes($DATA['saiu05respuesta']);
		$saiu05respuesta = str_replace('"', '\"', $DATA['saiu05respuesta']);
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			$DATA['saiu05agno'] = (int)fecha_agno();
			$DATA['saiu05mes'] = (int)fecha_mes();
			$DATA['saiu05dia'] = (int)fecha_dia();
			$DATA['saiu05origenagno'] = 0;
			$DATA['saiu05origenmes'] = 0;
			$DATA['saiu05origenid'] = 0;
			$DATA['saiu05hora'] = fecha_hora();
			$DATA['saiu05minuto'] = fecha_minuto();
			$DATA['saiu05estado'] = -1; //Guarda Borrador
			//$DATA['saiu05idmedio']=0;
			$DATA['saiu05idtemafin'] = 0;
			$DATA['saiu05idtiposolfin'] = 0;
			//$DATA['saiu05idsolicitante']=0; //$_SESSION['u_idtercero'];
			$DATA['saiu05costogenera'] = 0;
			$DATA['saiu05costorefpago'] = '';
			$DATA['saiu05prioridad'] = 0;
			$DATA['saiu05idzona'] = $saiu05idzona;
			$DATA['saiu05cead'] = $saiu05cead;
			$DATA['saiu05numref'] = $sContenedor . '-' . $DATA['saiu05id'] . '-' . strtoupper(substr(str_shuffle(md5($DATA['saiu05id'])), 0, 5));
			$DATA['saiu05idescuela'] = $saiu05idescuela;
			$DATA['saiu05idprograma'] = $saiu05idprograma;
			$DATA['saiu05idperiodo'] = 0;
			$DATA['saiu05idcurso'] = 0;
			$DATA['saiu05idgrupo'] = 0;
			$DATA['saiu05tiemprespdias'] = $saiu05tiemprespdias;
			$DATA['saiu05tiempresphoras'] = $saiu05tiempresphoras;
			$DATA['saiu05fecharespprob'] = $saiu05fecharespprob; //fecha_hoy();
			$DATA['saiu05numradicado'] = 0;
			// $DATA['saiu05idcategoria']=0;
			$DATA['saiu05idunidadresp'] = $saiu05idunidadresp;
			$DATA['saiu05idequiporesp'] = $saiu05idequiporesp;
			$DATA['saiu05idsupervisor'] = $saiu05idsupervisor;
			$DATA['saiu05idresponsable'] = $saiu05idresponsable;
			$sCampos3005 = 'saiu05agno, saiu05mes, saiu05tiporadicado, saiu05consec, saiu05id, 
saiu05origenagno, saiu05origenmes, saiu05origenid, saiu05dia, saiu05hora, 
saiu05minuto, saiu05estado, saiu05idmedio, saiu05idtiposolorigen, saiu05idtemaorigen, 
saiu05idtemafin, saiu05idtiposolfin, saiu05idsolicitante, saiu05idinteresado, saiu05tipointeresado, 
saiu05rptaforma, saiu05rptacorreo, saiu05rptadireccion, saiu05costogenera, saiu05costovalor, 
saiu05costorefpago, saiu05prioridad, saiu05idzona, saiu05cead, saiu05numref, 
saiu05detalle, saiu05infocomplemento, saiu05idunidadresp, saiu05idequiporesp, saiu05idsupervisor, saiu05idresponsable, saiu05idescuela, saiu05idprograma, 
saiu05idperiodo, saiu05idcurso, saiu05idgrupo, saiu05tiemprespdias, saiu05tiempresphoras, 
saiu05fecharespprob, saiu05respuesta, saiu05idmoduloproc, saiu05identificadormod, saiu05numradicado, saiu05idcategoria';
			$sValores3005 = '' . $DATA['saiu05agno'] . ', ' . $DATA['saiu05mes'] . ', ' . $DATA['saiu05tiporadicado'] . ', ' . $DATA['saiu05consec'] . ', ' . $DATA['saiu05id'] . ', 
' . $DATA['saiu05origenagno'] . ', ' . $DATA['saiu05origenmes'] . ', ' . $DATA['saiu05origenid'] . ', ' . $DATA['saiu05dia'] . ', ' . $DATA['saiu05hora'] . ', 
' . $DATA['saiu05minuto'] . ', ' . $DATA['saiu05estado'] . ', ' . $DATA['saiu05idmedio'] . ', ' . $DATA['saiu05idtiposolorigen'] . ', ' . $DATA['saiu05idtemaorigen'] . ', 
' . $DATA['saiu05idtemafin'] . ', ' . $DATA['saiu05idtiposolfin'] . ', ' . $DATA['saiu05idsolicitante'] . ', ' . $DATA['saiu05idinteresado'] . ', ' . $DATA['saiu05tipointeresado'] . ', 
' . $DATA['saiu05rptaforma'] . ', "' . $DATA['saiu05rptacorreo'] . '", "' . $DATA['saiu05rptadireccion'] . '", ' . $DATA['saiu05costogenera'] . ', ' . $DATA['saiu05costovalor'] . ', 
"' . $DATA['saiu05costorefpago'] . '", ' . $DATA['saiu05prioridad'] . ', ' . $DATA['saiu05idzona'] . ', ' . $DATA['saiu05cead'] . ', "' . $DATA['saiu05numref'] . '", 
"' . $saiu05detalle . '", "' . $saiu05infocomplemento . '", ' . $DATA['saiu05idunidadresp'] . ', ' . $DATA['saiu05idequiporesp'] . ', ' . $DATA['saiu05idsupervisor'] . ', ' . $DATA['saiu05idresponsable'] . ', ' . $DATA['saiu05idescuela'] . ', ' . $DATA['saiu05idprograma'] . ', 
' . $DATA['saiu05idperiodo'] . ', ' . $DATA['saiu05idcurso'] . ', ' . $DATA['saiu05idgrupo'] . ', ' . $DATA['saiu05tiemprespdias'] . ', ' . $DATA['saiu05tiempresphoras'] . ', 
"' . $DATA['saiu05fecharespprob'] . '", "' . $saiu05respuesta . '", ' . $DATA['saiu05idmoduloproc'] . ', ' . $DATA['saiu05identificadormod'] . ', ' . $DATA['saiu05numradicado'] . ', ' . $DATA['saiu05idcategoria'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla05 . ' (' . $sCampos3005 . ') VALUES (' . cadena_codificar($sValores3005) . ');';
				$sdetalle = $sCampos3005 . '[' . cadena_codificar($sValores3005) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla05 . ' (' . $sCampos3005 . ') VALUES (' . $sValores3005 . ');';
				$sdetalle = $sCampos3005 . '[' . $sValores3005 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'saiu05dia';
			$scampo[2] = 'saiu05idcategoria';
			$scampo[3] = 'saiu05idtiposolorigen';
			$scampo[4] = 'saiu05idtemaorigen';
			$scampo[5] = 'saiu05tipointeresado';
			$scampo[6] = 'saiu05rptaforma';
			$scampo[7] = 'saiu05rptacorreo';
			$scampo[8] = 'saiu05rptadireccion';
			$scampo[9] = 'saiu05detalle';
			$scampo[10] = 'saiu05idunidadresp';
			$scampo[11] = 'saiu05idequiporesp';
			$scampo[12] = 'saiu05idsupervisor';
			$scampo[13] = 'saiu05idresponsable';
			$scampo[14] = 'saiu05estado';
			$scampo[15] = 'saiu05respuesta';
			$scampo[16] = 'saiu05fecharespdef';
			$scampo[17] = 'saiu05horarespdef';
			$scampo[18] = 'saiu05minrespdef';
			$sdato[1] = $DATA['saiu05dia'];
			$sdato[2] = $DATA['saiu05idcategoria'];
			$sdato[3] = $DATA['saiu05idtiposolorigen'];
			$sdato[4] = $DATA['saiu05idtemaorigen'];
			$sdato[5] = $DATA['saiu05tipointeresado'];
			$sdato[6] = $DATA['saiu05rptaforma'];
			$sdato[7] = $DATA['saiu05rptacorreo'];
			$sdato[8] = $DATA['saiu05rptadireccion'];
			$sdato[9] = $saiu05detalle;
			$sdato[10] = $saiu05idunidadresp;
			$sdato[11] = $saiu05idequiporesp;
			$sdato[12] = $saiu05idsupervisor;
			$sdato[13] = $saiu05idresponsable;
			$sdato[14] = $DATA['saiu05estado'];
			$sdato[15] = '';
			$sdato[16] = $saiu05fecharespdef;
			$sdato[17] = $saiu05horarespdef;
			$sdato[18] = $saiu05minrespdef;
			$numcmod = 18;
			$sWhere = 'saiu05id=' . $DATA['saiu05id'] . '';
			$sSQL = 'SELECT * FROM ' . $sTabla05 . ' WHERE ' . $sWhere;
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
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bpasa) {
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3005] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu05id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				list($sErrorC, $sDebugC) = f3007_CargarDocumentos($DATA['saiu05agno'], $DATA['saiu05mes'], $DATA['saiu05id'], $objDB, $bDebug);
				if ($DATA['saiu05estado'] != $saiu05estadoorigen) {
					$saiu09consec = tabla_consecutivo($sTabla09, 'saiu09consec', '', $objDB);
					if ($saiu09consec == -1) {
						$sError = $objDB->serror;
					}
					$saiu09id = tabla_consecutivo($sTabla09, 'saiu09id', '', $objDB);
					if ($saiu09id == -1) {
						$sError = $objDB->serror;
					}
					if ($sError == '') {
						$iHoy = fecha_DiaMod();
						$iHora = fecha_hora();
						$iMinuto = fecha_minuto();
						$sCampos3009 = 'saiu09idsolicitud,saiu09consec,saiu09id,saiu09idestadoorigen,saiu09idestadofin,
						saiu09idusuario,saiu09fecha,saiu09hora,saiu09minuto';
						$sValores3009 = $DATA['saiu05id'] . ',' . $saiu09consec . ',' . $saiu09id . ',' . $saiu05estadoorigen . ',' . $DATA['saiu05estado'] . ',
						' . $DATA['saiu05idsolicitante'] . ',' . $iHoy . ',' . $iHora . ',' . $iMinuto . '';
						$sSQL = 'INSERT INTO ' . $sTabla09 . '(' . $sCampos3009 . ') VALUES (' . $sValores3009 . ')';
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {
							$sError = $ERR['falla_guardar'] . ' [3009] ..<!-- ' . $sSQL . ' -->';
						}
					}
					switch ($DATA['saiu05estado']) {
						case 0:
						case 7:
							if ($saiu05estadoorigen == -1) {
								list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitudExt($DATA, $sContenedor, $objDB, $bDebug, true);
							}
							list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitudExt($DATA, $sContenedor, $objDB, $bDebug);
							$sError = $sError . $sErrorE;
							$sDebug = $sDebug . $sDebugE;
							break;
					}
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3005 ' . $sSQL . '<br>';
					$sDebug = $sDebug . $sDebugC;
				}
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu05id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
				$bCalularTotales = true;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		$DATA['paso'] = $DATA['paso'] - 10;
	}
	if ($bQuitarCodigo) {
		$DATA['saiu05consec'] = '';
	}
	if ($bCalularTotales) {
		list($sErrorT, $sDebugT) = f3000_CalcularTotales($DATA['saiu05idsolicitante'], $DATA['saiu05agno'], (int)$DATA['saiu05mes'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugT;
	}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3005_EnviaCorreosSolicitudExt($DATA, $sContenedor, $objDB, $bDebug = false, $bResponsable = false, $bForzar = false)
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
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bEntra = false;
	$idTercero = 0;
	$sCorreoDestino = '';
	if ($DATA['saiu05rptaforma'] == 1) {
		$bEntra = true;
	} else {
		$bEntra = $bForzar;
	}
	if ($bEntra) {
		$bEntra = false;
		if (isset($DATA['saiu05idsolicitante']) != 0) {
			$idTercero = numeros_validar($DATA['saiu05idsolicitante']);
			if ($idTercero == $DATA['saiu05idsolicitante']) {
				if ((int)$idTercero != 0) {
					$bEntra = true;
					if (isset($DATA['saiu05rptacorreo']) != 0) {
						$sCorreoDestino = $DATA['saiu05rptacorreo'];
					}
				}
			}
		}
	}
	if ($bResponsable) {
		$bEntra = false;
		$sCorreoDestino = '';
		if (isset($DATA['saiu05idsupervisor']) != 0) {
			$idTercero = numeros_validar($DATA['saiu05idsupervisor']);
			if ((int)$idTercero != 0) {
				$bEntra = true;
			}
		}
		if ($bEntra) {
			if (isset($DATA['saiu05idresponsable']) != 0) {
				$saiu05idresponsable = numeros_validar($DATA['saiu05idresponsable']);
				if ((int)$saiu05idresponsable != 0) {
					$idTercero = $saiu05idresponsable;
				}
			}
		}
	}
	if ($bEntra) {
		list($bCorreoValido, $sDebugC) = correo_VerificarV2($sCorreoDestino);
		if ($bCorreoValido) {
			$sCorreoMensajes = $sCorreoDestino;
		} else {
			list($sCorreoMensajes, $unad11idgrupocorreo, $sError, $sDebugN) = AUREA_CorreoNotificaV2($idTercero, $objDB, $bDebug);
			if ($sError == '') {
				$bCorreoValido = true;
			}
		}
		if ($bCorreoValido) {
			list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud($sContenedor, $objDB, $bDebug);
			$sError = $sError . $sErrorR;
			$sDebug = $sDebug . $sDebugR;
			if ($sError == '') {
				$sNomEntidad = '';
				$sMailSeguridad = '';
				$sURLCampus = '';
				$sURLEncuestas = '';
				$idEntidad = Traer_Entidad();
				switch ($idEntidad) {
					case 1: // UNAD FLORIDA
						$sNomEntidad = 'UNAD FLORIDA INC';
						$sMailSeguridad = 'aluna@unad.us';
						$sURLCampus = 'http://unad.us/campus/';
						$sURLEncuestas = 'http://unad.us/aurea/';
						break;
					default: // UNAD Colombia
						$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
						$sMailSeguridad = 'soporte.campus@unad.edu.co';
						$sURLCampus = 'https://campus0c.unad.edu.co/campus/';
						$sURLEncuestas = 'https://aurea.unad.edu.co/satisfaccion/';
						break;
				}
				$sCorreoCopia = '';
				$iFechaServicio = $sContenedor . $DATA['saiu05dia'];
				$sFechaLarga = formato_FechaLargaDesdeNumero($iFechaServicio, true);
				$sRutaImg = 'https://datateca.unad.edu.co/img/';
				$sURLDestino = 'https://aurea.unad.edu.co/sai';
				$URL = url_encode('' . $DATA['saiu05numref']);
				$sURLDestinoEnc = 'https://aurea.unad.edu.co/encuesta';
				$sURL = '' . $URL . '';
				$sConRespuesta = '';
				$sMes = date('Ym');
				$sTabla = 'aure01login' . $sMes;
				list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
				$objMail = new clsMail_Unad($objDB);
				$objMail->TraerSMTP($idSMTP);
				list($unad11razonsocial, $sErrorDet) = tabla_campoxid('unad11terceros', 'unad11razonsocial', 'unad11id', $idTercero, '{' . 'An&oacute;nimo' . '}', $objDB);
				if ($bResponsable) {
					$sTituloMensaje = $ETI['mail_asig_titulo'] . ' ' . $sNomEntidad . '';
					$et_NumSol = f3000_NumSolicitud($DATA['saiu05agno'], $DATA['saiu05mes'], $DATA['saiu05consec']);
					$sCuerpo = 'Cordial saludo.<br>
					Estimado(a) <b>' . $unad11razonsocial . '</b><br><br>
					El Sistema de Atenci&oacute;n Integral (SAI) le informa que le ha sido asignada una PQRS radicada el d&iacute;a ' . $sFechaLarga . '; 
					con el n&uacute;mero de solicitud: <span style="color: rgb(255, 0, 0); font-size: 16px;"><strong>' . $et_NumSol . '</strong></span>.<br><br>
					Le invitamos a ingresar al m&oacute;dulo de Peticiones, Quejas, Reclamos y Sugerencias para iniciar el tr&aacute;mite de la solicitud.<br><br>';
				} else {
					if ($DATA['saiu05estado'] == 0) {
						$sTituloMensaje = $ETI['mail_solic_titulo'] . ' ' . $sNomEntidad . '';
						$sConRespuesta = $sConRespuesta . ' ';
					} else if ($DATA['saiu05estado'] == 7) {
						$sTituloMensaje = $ETI['mail_resp_titulo'] . ' ' . $sNomEntidad . '';
						$sConRespuesta = $sConRespuesta . ' la respuesta a ';
					}
					$sCuerpo = 'Cordial saludo.<br>
					Estimado(a) <b>' . $unad11razonsocial . '</b><br><br>
					Para la universidad Nacional Abierta y a Distancia - UNAD es muy importante atender sus solicitudes. 
					Acorde con lo anterior le informamos que' . $sConRespuesta . 'su solicitud radicada el día ' . $sFechaLarga . '; 
					puede ser consultada en el siguiente enlace:<br><a href="' . $sURLDestino . '" target="_blank">' . $sURLDestino . '</a><br>
					usando el código de radicado: <span style="color: rgb(255, 0, 0); font-size: 16px;"><strong>' . $DATA['saiu05numref'] . '</strong></span><br><br>';
					if ($DATA['saiu05estado'] == 7) {
						$sCuerpo = $sCuerpo . '<hr><p style="padding:0 5px;">' . $ETI['mail_enc'] . '</p>

				<table border="0" cellpadding="10" cellspacing="0" width="80%" style="width: 80%; max-width: 80%; min-width: 80%;">
					<tbody>
						<tr>
							<td align="center" bgcolor="#F0B429" style="font-size:22px;">
								<font face="Arial, Helvetica, sans-serif" color="#005883">
									<a style="padding: 10px 20px; color: #005883; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
									href="' . $sURLDestinoEnc . '?u=' . $sURL . '">
										<span style="font-size: 24px;">RESPONDER</span>
									</a>
								</font>
							</td>
						</tr>
						<tr>
							<td height="5">
							</td>
						</tr>
					</tbody>
				</table>

				<table border="0" cellpadding="10" cellspacing="0" width="60%" style="width: 60%; max-width: 60%; min-width: 60%;">
					<tbody>
						<tr>
							<td align="center" bgcolor="#005883" style="font-size:14px;">
								<font face="Arial, Helvetica, sans-serif" color="#ffffff">
									<a style="padding: 10px 20px; color: #ffffff; font-size: 12px; text-decoration: none; word-wrap: break-word;" target="_blank"
									href="' . $sURLDestinoEnc . '?n=' . $sURL . '">
										Si no desea responder, por favor haga clic aqu&iacute;
									</a>
								</font>
							</td>
						</tr>
						<tr>
							<td height="5">
							</td>
						</tr>
					</tbody>
				</table>

				<font face="Arial, Helvetica, sans-serif">
					<p>
						En caso de que no pueda acceder desde este correo, por favor ingrese a<br>
						<a style="padding: 10px 20px; color: #005883; word-wrap: break-word;" target="_blank"
							href="' . $sURLDestinoEnc . '">' . $sURLDestinoEnc . '
						</a><br>
						digite su n&uacute;mero de documento <br> y el c&oacute;digo <b>' . $DATA['saiu05numref'] . '</b>
					</p>
					<br>
				</font>';
					}
				}
				$sCuerpo = $sCuerpo . 'Cordialmente,<br>
				<b>Sistema de Atención Integral - SAI</b><br>';
				$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_NoResponder() . AUREA_NotificaPieDePagina() . AUREA_HTML_PieCorreo();
				$objMail->sAsunto = cadena_codificar($sTituloMensaje);
				$sMensaje = 'Se notifica al correo ' . $sCorreoMensajes;
				$objMail->addCorreo($sCorreoMensajes, $sCorreoMensajes);
				if ($sCorreoCopia != '') {
					$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');
					$sMensaje = $sMensaje . ' con copia a ' . $sCorreoCopia;
				}
				if ($sError == '') {
					$objMail->sCuerpo = $sCuerpo;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Enviando respuesta de solicitud a : ' . $sCorreoMensajes . '<br>';
					}
					list($sErrorM, $sDebugM) = $objMail->EnviarV2($bDebug);
					$sError = $sError . $sErrorM;
					$sDebug = $sDebug . $sDebugM;
					if ($sError != '') {
						$sMensaje = $ERR['mail_resp_error'];
					}
				}
			}
		} else {
			$sError = 'No se ha definido un correo electr&oacute;nico v&aacute;lidado para notificar el evento.';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Noficando Radicaci&oacute;n de PQRS</b>: No aplica para notificar.<br>';
		}
	}
	return array($sMensaje, $sError, $sDebug);
}
