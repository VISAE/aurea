<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0 martes, 25 de agosto de 2026
*/
if (!file_exists('./app.php')) {
    echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
    die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_sesion.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libmail.php';
require $APP->rutacomun . 'lib1205.php';
require $APP->rutacomun . 'lib1207.php';
require $APP->rutacomun . 'lib1208.php';
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
    $objDB->dbPuerto = $APP->dbpuerto;
}
$bDebug = false;
$sError = '';
$sDebug = '';
$sMensaje = '';
$bloque = '';
$masi05id = 0;
$masi05estado = 0;
$masi05fecha = 0;
$masi05hora = 0;
$masi05min = 0;
$masi05asunto = '';
$masi05cuerpo = '';
$masi05firma = 0;
$masi05admiterpta = 0;
$masi05correorpta = '';
$iEstadoOrigen = 0;
$iHoy = fecha_DiaMod();
$iHora = fecha_hora();
$iMin = fecha_minuto();
if ($sError == '') {
    $sSQL = 'SHOW TABLES LIKE "masi05%"';
    $tablac = $objDB->ejecutasql($sSQL);
    while($filac = $objDB->sf($tablac)) {
        $bloque = substr($filac[0], 15);
        list($sTabla1205, $sError) = f1205_NombreTabla($bloque, $objDB);
        list($sTabla1207, $sErrorH) = f1207_NombreTabla($bloque, $objDB);
        $sError = $sError . $sErrorH;
        list($sTabla1208, $sErrorH) = f1208_NombreTabla($bloque, $objDB);
        $sError = $sError . $sErrorH;
        if ($sError == '') {
            $sSQL = 'SELECT TB.masi05id, TB.masi05estado, TB.masi05asunto, TB.masi05cuerpo, TB.masi05firma, 
            TB.masi05fecha, TB.masi05hora, TB.masi05min, TB.masi05admiterpta, TB.masi05correorpta 
            FROM ' . $sTabla1205 . ' AS TB
            WHERE TB.masi05estado=3 AND TB.masi05fecha=' . $iHoy . '
            ORDER BY TB.masi05fecha, TB.masi05hora, TB.masi05min ';
            $tabla = $objDB->ejecutasql($sSQL);
            if ($objDB->nf($tabla) > 0) {
                $filabase = $objDB->sf($tabla);
                $masi05id = $filabase['masi05id'];
                $masi05estado = $filabase['masi05estado'];
                $masi05asunto = $filabase['masi05asunto'];
                $masi05cuerpo = $filabase['masi05cuerpo'];
                $masi05firma = $filabase['masi05firma'];
                $masi05fecha = $filabase['masi05fecha'];
                $masi05hora = $filabase['masi05hora'];
                $masi05min = $filabase['masi05min'];
                $masi05admiterpta = $filabase['masi05admiterpta'];
                $masi05correorpta = $filabase['masi05correorpta'];
                break;
            } else {
                $sError = 'No se han programado masivos';
            }
        }
    }
    if ($bloque == '') {
        $sError = 'No existe la tabla de mensajes';
    }
}
if ($sError == '') {
    $iTiempoMinutos = fecha_tiempoenminutos($iHoy, $iHora, $iMin, $masi05fecha, $masi05hora, $masi05min);
    if (($iTiempoMinutos >= 0) && ($iTiempoMinutos < 30)) {
        $iEstadoDestino = 5; // en proceso
        list($sError, $sDebug, $sMensaje) = f1205_CambiaEstado($masi05id, $bloque, $masi05estado, $iEstadoDestino, '', 2, $objDB);
    } else {
        $sError = 'La fecha y hora para env&iacute;o no es v&aacute;lida.';
    }
}
if ($sError == '') {
    $iEstadoOrigen = $iEstadoDestino;
    $sCuerpo = '';
    $sFirma = '';
    $sAnexos = '';
    $sResponde = '';
    $sCorreoCopia = '';
    $aMensajeDest = array();
    $iConteo = 0;
    $iCantidad = 50;
    $bResponde = false;
    $sMes = date('Ym');
    $sTabla = 'aure01login' . $sMes;
    list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
    $objMail = new clsMail_Unad($objDB);
    $objMail->TraerSMTP($idSMTP);
    $objMail->sAsunto = cadena_codificar($masi05asunto);
    if ($masi05firma > 0) {
        $sSQL = 'SELECT masi09cuerpo FROM masi09firma WHERE masi09id=' . $masi05firma . '';
        $tabla = $objDB->ejecutasql($sSQL);
        if ($fila = $objDB->sf($tabla)){
            $sFirma = '<br><br>' . $fila['masi09cuerpo'] . '<br><br>';
        }
    }
    if ($masi05admiterpta == 1) {
        $bResponde = correo_VerificarDireccion($masi05correorpta);
    } 
    if (!$bResponde) {
        $sResponde = AUREA_HTML_NoResponderSII();
    }    
    $sSQL = 'SELECT masi07titulo, masi07idorigen, masi07idarchivo FROM ' . $sTabla1207 . ' WHERE masi07idmensaje=' . $masi05id . ' AND masi07idorigen>0 AND masi07idarchivo>0';
    $tabla = $objDB->ejecutasql($sSQL);
    if ($objDB->nf($tabla) > 0) {
        $iFechaTopeDescarga = fecha_DiaMod() -1;
        $sAnexos = $sAnexos . '<hr>';
        $sAnexos = $sAnexos . '<h3>Adjuntos</h3>';
        $sAnexos = $sAnexos . '<ul>';
        while ($fila = $objDB->sf($tabla)){
            list($sLink, $sInfoAnexo) = html_LnkArchivoPublico((int)$fila['masi07idorigen'], (int)$fila['masi07idarchivo'], $iFechaTopeDescarga, $fila['masi07titulo']);
            $sAnexos = $sAnexos . '<li>' . $sLink . ' ' . $sInfoAnexo . '</li>';
        }
        $sAnexos = $sAnexos . '</ul>';
        $sAnexos = $sAnexos . '<hr>';
    }
    $sCuerpo = $masi05cuerpo . $sAnexos . $sFirma . $sResponde . AUREA_HTML_PieCorreo();
    $objMail->sCuerpo = $sCuerpo;
    $sSQL = 'SELECT masi08id, masi08idtercero FROM ' . $sTabla1208 . ' WHERE masi08idmensaje=' . $masi05id . ' AND masi08fechaenvio=0';
    $tabla = $objDB->ejecutasql($sSQL);
    while ($fila = $objDB->sf($tabla)){
        $sErrorE = '';
        $iHoy = fecha_DiaMod();
        $iHora = fecha_hora();
        $iMin = fecha_minuto();
        if ($iConteo == $iCantidad) {
            $sErrorE = $objMail->Enviar($bDebug);
            if ($sErrorE == '') {
                foreach ($aMensajeDest as $masi08id) {
                    $sSQL = 'UPDATE ' . $sTabla1208 . ' SET masi08fechaenvio=' . $iHoy . ', masi08horaenvio=' . $iHora . ', masi08minenvio=' . $iMin . ', masi08idsmtp=' . $idSMTP . ' WHERE masi08id=' . $masi08id . '';
                    $result = $objDB->ejecutasql($sSQL);
                }
                $sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05total_envios=masi05total_envios + ' . $iConteo;
                $result = $objDB->ejecutasql($sSQL);
            }
            $objMail->NuevoMensaje();
            $iConteo = 0;
        }
        $masi08id = $fila['masi08id'];
        $idInteresado = $fila['masi08idtercero'];
        list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idInteresado, $objDB, $bDebug);
        if ($sCorreoUsuario == '') {
            $sErrorE = 'El usuario no registra correo de notificaciones.';
        }
        if ($sErrorE == '') {
            $objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario, 'O');
            $aMensajeDest[] = $masi08id;
            $iConteo++;
        }
    }
    if ($iConteo > 0) {
        $sErrorE = $objMail->Enviar($bDebug);
        if ($sErrorE == '') {
            foreach ($aMensajeDest as $masi08id) {
                $sSQL = 'UPDATE ' . $sTabla1208 . ' SET masi08fechaenvio=' . $iHoy . ', masi08horaenvio=' . $iHora . ', masi08minenvio=' . $iMin . ', masi08idsmtp=' . $idSMTP . ' WHERE masi08id=' . $masi08id . '';
                $result = $objDB->ejecutasql($sSQL);
            }
            $sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05total_envios=masi05total_envios + ' . $iConteo;
            $result = $objDB->ejecutasql($sSQL);
        }
    }
}
if ($sError == '') {
    $iEstadoDestino = 7; // cerrado
    list($sError, $sDebug, $sMensaje) = f1205_CambiaEstado($masi05id, $bloque, $iEstadoOrigen, $iEstadoDestino, '', 2, $objDB);
}
echo $sError;
$objDB->CerrarConexion();
