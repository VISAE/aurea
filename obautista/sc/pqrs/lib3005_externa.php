<?php
/*
--- © Omar Augusto Bautista MOra - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- lunes, 28 de noviembre de 2022
--- El proposito de esta libreria es redirigir a las opciones de consulta o radicado de PQRS
*/
function f3005_HTMLOpcionInicial($aParametros) {
    $_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}	
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
    $sHTML = '';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
    $iOpcion = numeros_validar($aParametros[100]);
    if ($iOpcion == '') {
        $sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
    }
    if($sError == '') {
        if($iOpcion == 1) {
            $sHTML = $sHTML . '<form id="frmcodigo" name="frmcodigo" method="post" action="" autocomplete="off">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="aure73codigo" class="text-center">' . $ETI['ing_campo'] . $ETI['saiu05refdoc'] . '</label>
                    <input id="aure73codigo" name="aure73codigo" class="form-control form-control-lg text-center" type="text" value="" maxlength="20" placeholder="' . $ETI['saiu05refdoc'] . '" />
                </div>
            </div>
            <input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_consultar'] . '" value="' . $ETI['bt_consultar'] . '" onclick="enviacodigo()">
        </form>';
        } else if ($iOpcion == 2) {
            $sHTML = $sHTML . '<div class="form-group row">
            <div class="col-sm-12">
                <a id="cmdIrACampus" name="cmdIrACampus" class="btn btn-aurea w-50" title="' . $ETI['bt_campus'] . '" href="./login.php">' . $ETI['bt_campus'] . '</a>
            </div>
            <div class="col-sm-12">
                <a id="cmdIrAInscribirse" name="cmdIrAInscribirse" class="btn btn-aurea w-50 mt-2" title="' . $ETI['bt_inscribirse'] . '" href="https://campus0d.unad.edu.co/campus/inscripcion.php">' . $ETI['bt_inscribirse'] . '</a>
            </div>
            <div class="col-sm-12">
                <a id="cmdIrAAnonimo" name="cmdIrAAnonimo" class="btn btn-aurea w-50 mt-2" title="' . $ETI['bt_anonimo'] . '" href="https://softwaretest.unad.edu.co/obautista/sai/saipqrs.php">' . $ETI['bt_anonimo'] . '</a>
            </div>
        </div>';
        } else {
            $sError = $ERR['saiu05opinvalida'] . $sSepara . $sError;
        }
	}
    $objResponse = new xajaxResponse();
    if($sError == '') {
        $objResponse->assign('div_saiu05rutaspqrs', 'innerHTML', $sHTML);
    } else {
        $objResponse->call('muestramensajes("danger", "' . $sError . '")');
    }
    return $objResponse;
}