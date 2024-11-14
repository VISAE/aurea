<?php
/*
--- © Omar Augusto Bautista MOra - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- lunes, 28 de octubre de 2024
--- El proposito de esta libreria es redirigir a las opciones de consulta o radicado de Atención Virtual
*/
function f3073_HTMLOpcionInicial($aParametros)
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
		} else if ($iOpcion == 2) {
				$sHTML = $sHTML . '<div class="form-group row">
                    <div class="col-sm-12 pt-2">' . $ETI['msg_iracampus'] . '</div>
                    <div class="col-sm-12">
                        <a id="cmdIrACampus" name="cmdIrACampus" class="btn btn-aurea w-50" title="' . $ETI['bt_campus'] . '" href="https://campus0a.unad.edu.co/campus/saiusolusuario.php" target="_blank" >' . $ETI['bt_campus'] . '</a>
                    </div>
					<div class="col-sm-12 pt-2">' . $ETI['msg_irainscribirse'] . '</div>
                    <div class="col-sm-12">
                        <a id="cmdIrAInscribirse" name="cmdIrAInscribirse" class="btn btn-aurea w-50" title="' . $ETI['bt_inscribirse'] . '" href="https://campus0d.unad.edu.co/campus/inscripcion.php">' . $ETI['bt_inscribirse'] . '</a>
                    </div>
                </div>
				<div class="form-group row">
					<div class="col-sm-12">
						<input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-light px-4 float-right" title="' . $ETI['bt_regresar'] . '" value="' . $ETI['bt_regresar'] . '" onclick="window.location.reload()">
					</div>
            	</div>';
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