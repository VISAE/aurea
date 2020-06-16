<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Wednesday, September 18, 2019
--- Modelo Versión 2.24.0 Sunday, November 24, 2019
--- Modelo Versión 2.24.0 Tuesday, December 17, 2019
--- Modelo Versión 2.24.0 Monday, December 23, 2019
*/
/** Archivo caraacompana.php.
 * Modulo 2323 cara23acompanamento.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date Monday, September 9, 2019
 * @date Wednesday, September 18, 2019
 *
 * Cambios 12 de junio de 2020
 * 1. Se oculta el combo de consecutivo y se realiza la limitación de la cantidad de acompañamientos.
 * 2. Se agrega la información de ubicación, matrícula y datos personales del estudiante.
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
 */
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
    $bDebug=true;
}
if (isset($_REQUEST['debug'])!=0){
    if ($_REQUEST['debug']==1){$bDebug=true;}
}
if ($bDebug){
    $iSegIni=microtime(true);
    $iSegundos=floor($iSegIni);
    $sMili=floor(($iSegIni-$iSegundos)*1000);
    if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
    $sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
}
if (!file_exists('./app.php')){
    echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
    die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
    $bObliga=false;
    if (isset($_SERVER['HTTPS'])==0){
        $bObliga=true;
    }else{
        if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
    }
    if ($bObliga){
        $pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        header('Location:'.$pageURL);
        die();
    }
}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libc2.php';
require $APP->rutacomun.'lib23.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
    // viene por xajax.
    $xajax=new xajax();
    $xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
    $xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
    $xajax->processRequest();
    die();
}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2323;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$APP->rutac2='../c2/';
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2323;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
    $sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
}
if (!$objDB->Conectar()){
    $bCerrado=true;
    if ($bDebug){
        $sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
    }
}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
    header('Location:nopermiso.php');
    die();
}
if (!$bPeticionXAJAX){
    if (noticias_pendientes($objDB)){
        $objDB->CerrarConexion();
        header('Location:noticia.php?ret=caraacompana.php');
        die();
    }
}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
    list($devuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
    //$sDebug=$sDebug.$sDebugP;
    if ($devuelve){
        $sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
        $tabla=$objDB->ejecutasql($sSQL);
        if ($objDB->nf($tabla)>0){
            $fila=$objDB->sf($tabla);
            $idTercero=$fila['unad11id'];
            $bOtroUsuario=true;
            if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
        }else{
            $sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
            $_REQUEST['deb_doc']='';
        }
    }else{
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario [Modulo '.$iCodModulo.' Permiso 1707].<br>';}
        $_REQUEST['deb_doc']='';
    }
    $bDebug=false;
}else{
    $_REQUEST['deb_doc']='';
}
if (isset($_REQUEST['debug'])!=0){
    if ($_REQUEST['debug']==1){$bDebug=true;}
}else{
    $_REQUEST['debug']=0;
}
//PROCESOS DE LA PAGINA
$idEntidad=0;
if (isset($APP->entidad)!=0){
    if ($APP->entidad==1){$idEntidad=1;}
}
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
    $_REQUEST['paso']=-1;
    if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
}
// -- 2323 cara23acompanamento
require 'lib2301acompana.php';
require 'lib2323.php';
require $APP->rutac2.'lib2451.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'formatear_moneda');
$xajax->register(XAJAX_FUNCTION,'f2323_Combocara23idencuesta');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2323_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2323_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2323_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2323_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2301_Combobprograma');
$xajax->register(XAJAX_FUNCTION,'f2301_Combobcead');
$xajax->register(XAJAX_FUNCTION,'f2451_HtmlTabla');
$xajax->processRequest();
if ($bPeticionXAJAX){
    die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2323'])==0){$_REQUEST['paginaf2323']=1;}
if (isset($_REQUEST['lppf2323'])==0){$_REQUEST['lppf2323']=20;}
if (isset($_REQUEST['boculta2323'])==0){$_REQUEST['boculta2323']=0;}
if (isset($_REQUEST['paginaf2451'])==0){$_REQUEST['paginaf2451']=1;}
if (isset($_REQUEST['lppf2451'])==0){$_REQUEST['lppf2451']=50;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca'])==0){$_REQUEST['cara01idperaca']='';}
if (isset($_REQUEST['cara01completa'])==0){$_REQUEST['cara01completa']='N';}
if (isset($_REQUEST['cara01fechaencuesta'])==0){$_REQUEST['cara01fechaencuesta']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara01agnos'])==0){$_REQUEST['cara01agnos']=0;}
if (isset($_REQUEST['unad11telefono'])==0){$_REQUEST['unad11telefono']='';}
if (isset($_REQUEST['unad11correo'])==0){$_REQUEST['unad11correo']='';}
if (isset($_REQUEST['cara01idzona'])==0){$_REQUEST['cara01idzona']=0;}
if (isset($_REQUEST['cara01idcead'])==0){$_REQUEST['cara01idcead']=0;}
if (isset($_REQUEST['core16idescuela'])==0){$_REQUEST['core16idescuela']=0;}
if (isset($_REQUEST['core16idprograma'])==0){$_REQUEST['core16idprograma']=0;}
if (isset($_REQUEST['cara01tipocaracterizacion'])==0){$_REQUEST['cara01tipocaracterizacion']=0;}
if (isset($_REQUEST['cara01idperiodoacompana'])==0){$_REQUEST['cara01idperiodoacompana']=0;}
if (isset($_REQUEST['cara01fechacierreacom'])==0){$_REQUEST['cara01fechacierreacom']=0;}
if (isset($_REQUEST['cara01r1'])==0){$_REQUEST['cara01r1']='';}
if (isset($_REQUEST['cara01r2'])==0){$_REQUEST['cara01r2']='';}
if (isset($_REQUEST['cara01r3'])==0){$_REQUEST['cara01r3']='';}
if (isset($_REQUEST['cara01r4'])==0){$_REQUEST['cara01r4']='';}
if (isset($_REQUEST['cara01factorprincipaldesc'])==0){$_REQUEST['cara01factorprincipaldesc']=0;}
if (isset($_REQUEST['cara01idcursocatedra'])==0){$_REQUEST['cara01idcursocatedra']=0;}
if (isset($_REQUEST['cara01factorprincpermanencia'])==0){$_REQUEST['cara01factorprincpermanencia']='';}
if (isset($_REQUEST['cara23idtercero'])==0){$_REQUEST['cara23idtercero']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['cara23idtercero_td'])==0){$_REQUEST['cara23idtercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara23idtercero_doc'])==0){$_REQUEST['cara23idtercero_doc']='';}
if (isset($_REQUEST['cara23idencuesta'])==0){$_REQUEST['cara23idencuesta']='';}
if (isset($_REQUEST['cara23consec'])==0){$_REQUEST['cara23consec']='';}
if (isset($_REQUEST['cara23consec_nuevo'])==0){$_REQUEST['cara23consec_nuevo']='';}
if (isset($_REQUEST['cara23id'])==0){$_REQUEST['cara23id']='';}
if (isset($_REQUEST['cara23idtipo'])==0){$_REQUEST['cara23idtipo']=0;}
if (isset($_REQUEST['cara23estado'])==0){$_REQUEST['cara23estado']=0;}
if (isset($_REQUEST['cara23asisteinduccion'])==0){$_REQUEST['cara23asisteinduccion']=0;}
if (isset($_REQUEST['cara23asisteinmersioncv'])==0){$_REQUEST['cara23asisteinmersioncv']=0;}
if (isset($_REQUEST['cara23catedra_skype'])==0){$_REQUEST['cara23catedra_skype']='';}
if (isset($_REQUEST['cara23catedra_bler1'])==0){$_REQUEST['cara23catedra_bler1']='';}
if (isset($_REQUEST['cara23catedra_bler2'])==0){$_REQUEST['cara23catedra_bler2']='';}
if (isset($_REQUEST['cara23catedra_webconf'])==0){$_REQUEST['cara23catedra_webconf']='';}
if (isset($_REQUEST['cara23catedra_avance'])==0){$_REQUEST['cara23catedra_avance']='';}
if (isset($_REQUEST['cara23catedra_criterio'])==0){$_REQUEST['cara23catedra_criterio']=0;}
if (isset($_REQUEST['cara23catedra_acciones'])==0){$_REQUEST['cara23catedra_acciones']='';}
if (isset($_REQUEST['cara23catedra_resultados'])==0){$_REQUEST['cara23catedra_resultados']='';}
if (isset($_REQUEST['cara23catedra_segprev'])==0){$_REQUEST['cara23catedra_segprev']='';}
if (isset($_REQUEST['cara23cursos_total'])==0){$_REQUEST['cara23cursos_total']='';}
if (isset($_REQUEST['cara23cursos_siningre'])==0){$_REQUEST['cara23cursos_siningre']='';}
if (isset($_REQUEST['cara23cursos_porcing'])==0){$_REQUEST['cara23cursos_porcing']=0;}
if (isset($_REQUEST['cara23cursos_menor200'])==0){$_REQUEST['cara23cursos_menor200']='';}
if (isset($_REQUEST['cara23cursos_porcperdida'])==0){$_REQUEST['cara23cursos_porcperdida']=0;}
if (isset($_REQUEST['cara23cursos_criterio'])==0){$_REQUEST['cara23cursos_criterio']=0;}
if (isset($_REQUEST['cara23cursos_otros'])==0){$_REQUEST['cara23cursos_otros']='';}
if (isset($_REQUEST['cara23cursos_accionlider'])==0){$_REQUEST['cara23cursos_accionlider']='';}
if (isset($_REQUEST['cara23aler_sociodem'])==0){$_REQUEST['cara23aler_sociodem']='';}
if (isset($_REQUEST['cara23aler_psico'])==0){$_REQUEST['cara23aler_psico']='';}
if (isset($_REQUEST['cara23aler_academ'])==0){$_REQUEST['cara23aler_academ']='';}
if (isset($_REQUEST['cara23aler_econom'])==0){$_REQUEST['cara23aler_econom']='';}
if (isset($_REQUEST['cara23aler_externo'])==0){$_REQUEST['cara23aler_externo']='';}
if (isset($_REQUEST['cara23aler_interno'])==0){$_REQUEST['cara23aler_interno']='';}
if (isset($_REQUEST['cara23aler_nivel'])==0){$_REQUEST['cara23aler_nivel']='';}
if (isset($_REQUEST['cara23aler_criterio'])==0){$_REQUEST['cara23aler_criterio']='';}
if (isset($_REQUEST['cara23comp_digital'])==0){$_REQUEST['cara23comp_digital']=0;}
if (isset($_REQUEST['cara23comp_cuanti'])==0){$_REQUEST['cara23comp_cuanti']=0;}
if (isset($_REQUEST['cara23comp_lectora'])==0){$_REQUEST['cara23comp_lectora']=0;}
if (isset($_REQUEST['cara23comp_ingles'])==0){$_REQUEST['cara23comp_ingles']=0;}
if (isset($_REQUEST['cara23comp_criterio'])==0){$_REQUEST['cara23comp_criterio']=0;}
if (isset($_REQUEST['cara23nivela_digital'])==0){$_REQUEST['cara23nivela_digital']=0;}
if (isset($_REQUEST['cara23nivela_cuanti'])==0){$_REQUEST['cara23nivela_cuanti']=0;}
if (isset($_REQUEST['cara23nivela_lecto'])==0){$_REQUEST['cara23nivela_lecto']=0;}
if (isset($_REQUEST['cara23nivela_ingles'])==0){$_REQUEST['cara23nivela_ingles']=0;}
if (isset($_REQUEST['cara23nivela_exito'])==0){$_REQUEST['cara23nivela_exito']=0;}
if (isset($_REQUEST['cara23contacto_efectivo'])==0){$_REQUEST['cara23contacto_efectivo']='';}
if (isset($_REQUEST['cara23contacto_forma'])==0){$_REQUEST['cara23contacto_forma']='';}
if (isset($_REQUEST['cara23contacto_observa'])==0){$_REQUEST['cara23contacto_observa']='';}
if (isset($_REQUEST['cara23aplaza'])==0){$_REQUEST['cara23aplaza']='';}
if (isset($_REQUEST['cara23seretira'])==0){$_REQUEST['cara23seretira']='';}
if (isset($_REQUEST['cara23factorriesgo'])==0){$_REQUEST['cara23factorriesgo']=0;}
if (isset($_REQUEST['cara23zonal_retro'])==0){$_REQUEST['cara23zonal_retro']='';}
if (isset($_REQUEST['cara23zonal_fecha'])==0){$_REQUEST['cara23zonal_fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara23zonal_idlider'])==0){$_REQUEST['cara23zonal_idlider']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['cara23zonal_idlider_td'])==0){$_REQUEST['cara23zonal_idlider_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara23zonal_idlider_doc'])==0){$_REQUEST['cara23zonal_idlider_doc']='';}
if (isset($_REQUEST['cara23fecha'])==0){$_REQUEST['cara23fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['cara23cursos_ac1'])==0){$_REQUEST['cara23cursos_ac1']='';}
if (isset($_REQUEST['cara23cursos_ac2'])==0){$_REQUEST['cara23cursos_ac2']='';}
if (isset($_REQUEST['cara23cursos_ac3'])==0){$_REQUEST['cara23cursos_ac3']='';}
if (isset($_REQUEST['cara23cursos_ac4'])==0){$_REQUEST['cara23cursos_ac4']='';}
if (isset($_REQUEST['cara23cursos_ac5'])==0){$_REQUEST['cara23cursos_ac5']='';}
if (isset($_REQUEST['cara23cursos_ac6'])==0){$_REQUEST['cara23cursos_ac6']='';}
if (isset($_REQUEST['cara23cursos_ac7'])==0){$_REQUEST['cara23cursos_ac7']='';}
if (isset($_REQUEST['cara23cursos_ac8'])==0){$_REQUEST['cara23cursos_ac8']='';}
if (isset($_REQUEST['cara23cursos_ac9'])==0){$_REQUEST['cara23cursos_ac9']='';}
if (isset($_REQUEST['cara23catedra_aprueba'])==0){$_REQUEST['cara23catedra_aprueba']=-1;}
if (isset($_REQUEST['cara23permanece'])==0){$_REQUEST['cara23permanece']=-1;}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bperaca'])==0){$_REQUEST['bperaca']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=1;}
if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';}
if (isset($_REQUEST['bprograma'])==0){$_REQUEST['bprograma']='';}
if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
if (isset($_REQUEST['bcead'])==0){$_REQUEST['bcead']='';}
if (isset($_REQUEST['btipocara'])==0){$_REQUEST['btipocara']='';}
if (isset($_REQUEST['bpoblacion'])==0){$_REQUEST['bpoblacion']='';}
if (isset($_REQUEST['bconvenio'])==0){$_REQUEST['bconvenio']='';}
if (isset($_REQUEST['bperacamat'])==0){$_REQUEST['bperacamat']='';}
$bTraeAsistencias=false;
$bTraeInfoCursos=false;
$bTraerDatosBase=false;
$bHayData=false;
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
    $_REQUEST['cara23idtercero_td']=$APP->tipo_doc;
    $_REQUEST['cara23idtercero_doc']='';
    $_REQUEST['cara23zonal_idlider_td']=$APP->tipo_doc;
    $_REQUEST['cara23zonal_idlider_doc']='';
    $bTraerDatosBase=true;
    if ($_REQUEST['paso']==1){
        $sSQLcondi='cara23idencuesta='.$_REQUEST['cara23idencuesta'].' AND cara23consec='.$_REQUEST['cara23consec'].'';
    }else{
        $sSQLcondi='cara23id='.$_REQUEST['cara23id'].'';
    }
    $sSQL='SELECT * FROM cara23acompanamento WHERE '.$sSQLcondi;
    $tabla=$objDB->ejecutasql($sSQL);
    if ($objDB->nf($tabla)>0){
        $fila=$objDB->sf($tabla);
        $_REQUEST['cara23idtercero']=$fila['cara23idtercero'];
        $_REQUEST['cara23idencuesta']=$fila['cara23idencuesta'];
        $_REQUEST['cara23consec']=$fila['cara23consec'];
        $_REQUEST['cara23id']=$fila['cara23id'];
        $_REQUEST['cara23idtipo']=$fila['cara23idtipo'];
        $_REQUEST['cara23estado']=$fila['cara23estado'];
        $_REQUEST['cara23asisteinduccion']=$fila['cara23asisteinduccion'];
        $_REQUEST['cara23asisteinmersioncv']=$fila['cara23asisteinmersioncv'];
        $_REQUEST['cara23catedra_skype']=$fila['cara23catedra_skype'];
        $_REQUEST['cara23catedra_bler1']=$fila['cara23catedra_bler1'];
        $_REQUEST['cara23catedra_bler2']=$fila['cara23catedra_bler2'];
        $_REQUEST['cara23catedra_webconf']=$fila['cara23catedra_webconf'];
        $_REQUEST['cara23catedra_avance']=$fila['cara23catedra_avance'];
        $_REQUEST['cara23catedra_criterio']=$fila['cara23catedra_criterio'];
        $_REQUEST['cara23catedra_acciones']=$fila['cara23catedra_acciones'];
        $_REQUEST['cara23catedra_resultados']=$fila['cara23catedra_resultados'];
        $_REQUEST['cara23catedra_segprev']=$fila['cara23catedra_segprev'];
        $_REQUEST['cara23cursos_total']=$fila['cara23cursos_total'];
        $_REQUEST['cara23cursos_siningre']=$fila['cara23cursos_siningre'];
        $_REQUEST['cara23cursos_porcing']=$fila['cara23cursos_porcing'];
        $_REQUEST['cara23cursos_menor200']=$fila['cara23cursos_menor200'];
        $_REQUEST['cara23cursos_porcperdida']=$fila['cara23cursos_porcperdida'];
        $_REQUEST['cara23cursos_criterio']=$fila['cara23cursos_criterio'];
        $_REQUEST['cara23cursos_otros']=$fila['cara23cursos_otros'];
        $_REQUEST['cara23cursos_accionlider']=$fila['cara23cursos_accionlider'];
        $_REQUEST['cara23aler_sociodem']=$fila['cara23aler_sociodem'];
        $_REQUEST['cara23aler_psico']=$fila['cara23aler_psico'];
        $_REQUEST['cara23aler_academ']=$fila['cara23aler_academ'];
        $_REQUEST['cara23aler_econom']=$fila['cara23aler_econom'];
        $_REQUEST['cara23aler_externo']=$fila['cara23aler_externo'];
        $_REQUEST['cara23aler_interno']=$fila['cara23aler_interno'];
        $_REQUEST['cara23aler_nivel']=$fila['cara23aler_nivel'];
        $_REQUEST['cara23aler_criterio']=$fila['cara23aler_criterio'];
        $_REQUEST['cara23comp_digital']=$fila['cara23comp_digital'];
        $_REQUEST['cara23comp_cuanti']=$fila['cara23comp_cuanti'];
        $_REQUEST['cara23comp_lectora']=$fila['cara23comp_lectora'];
        $_REQUEST['cara23comp_ingles']=$fila['cara23comp_ingles'];
        $_REQUEST['cara23comp_criterio']=$fila['cara23comp_criterio'];
        $_REQUEST['cara23nivela_digital']=$fila['cara23nivela_digital'];
        $_REQUEST['cara23nivela_cuanti']=$fila['cara23nivela_cuanti'];
        $_REQUEST['cara23nivela_lecto']=$fila['cara23nivela_lecto'];
        $_REQUEST['cara23nivela_ingles']=$fila['cara23nivela_ingles'];
        $_REQUEST['cara23nivela_exito']=$fila['cara23nivela_exito'];
        $_REQUEST['cara23contacto_efectivo']=$fila['cara23contacto_efectivo'];
        $_REQUEST['cara23contacto_forma']=$fila['cara23contacto_forma'];
        $_REQUEST['cara23contacto_observa']=$fila['cara23contacto_observa'];
        $_REQUEST['cara23aplaza']=$fila['cara23aplaza'];
        $_REQUEST['cara23seretira']=$fila['cara23seretira'];
        $_REQUEST['cara23factorriesgo']=$fila['cara23factorriesgo'];
        $_REQUEST['cara23zonal_retro']=$fila['cara23zonal_retro'];
        $_REQUEST['cara23zonal_fecha']=$fila['cara23zonal_fecha'];
        $_REQUEST['cara23zonal_idlider']=$fila['cara23zonal_idlider'];
        $_REQUEST['cara23fecha']=$fila['cara23fecha'];
        $_REQUEST['cara23cursos_ac1']=$fila['cara23cursos_ac1'];
        $_REQUEST['cara23cursos_ac2']=$fila['cara23cursos_ac2'];
        $_REQUEST['cara23cursos_ac3']=$fila['cara23cursos_ac3'];
        $_REQUEST['cara23cursos_ac4']=$fila['cara23cursos_ac4'];
        $_REQUEST['cara23cursos_ac5']=$fila['cara23cursos_ac5'];
        $_REQUEST['cara23cursos_ac6']=$fila['cara23cursos_ac6'];
        $_REQUEST['cara23cursos_ac7']=$fila['cara23cursos_ac7'];
        $_REQUEST['cara23cursos_ac8']=$fila['cara23cursos_ac8'];
        $_REQUEST['cara23cursos_ac9']=$fila['cara23cursos_ac9'];
        $_REQUEST['cara23catedra_aprueba']=$fila['cara23catedra_aprueba'];
        $_REQUEST['cara23permanece']=$fila['cara23permanece'];
        if ($_REQUEST['cara23estado']==0){
            $bTraeAsistencias=true;
            if ($_REQUEST['cara23idtipo']>1){
                $bTraeInfoCursos=true;
            }
        }else{
            $bHayData=false;
        }
        $bcargo=true;
        $_REQUEST['paso']=2;
        $_REQUEST['boculta2323']=0;
        $bLimpiaHijos=true;
    }else{
        $_REQUEST['paso']=0;
    }
}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
    $_REQUEST['paso']=12;
    $_REQUEST['cara23estado']=7;
    $_REQUEST['cara23fecha']=fecha_DiaMod();
    $bCerrando=true;
}
//Abrir
if ($_REQUEST['paso']==17){
    $_REQUEST['paso']=2;
    //Es posible que deba definir el codigo de permiso para abrir.
    if (!seg_revisa_permiso($iCodModulo, 17, $objDB)){
        $sError=$ERR['3'];
    }
    //Otras restricciones para abrir.
    if ($sError==''){
        //$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
    }
    if ($sError!=''){
        $_REQUEST['cara23estado']=7;
    }else{
        $sSQL='UPDATE cara23acompanamento SET cara23estado=0, cara23fecha=0 WHERE cara23id='.$_REQUEST['cara23id'].'';
        $tabla=$objDB->ejecutasql($sSQL);
        seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cara23id'], 'Abre Acompanamiento', $objDB);
        $_REQUEST['cara23estado']=0;
        $_REQUEST['cara23fecha']=0;
        $sError='<b>El documento ha sido abierto</b>';
        $iTipoError=1;
    }
}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
    $bMueveScroll=true;
    if ($_REQUEST['paso']==10){
        $bTraerDatosBase=true;
        if ($_REQUEST['cara23idtipo']>1){
            $bTraeInfoCursos=true;
        }
    }
    list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar, $bCambiaCatedra)=f2323_db_GuardarV2($_REQUEST, $objDB, $bDebug);
    //($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug)
    $sDebug=$sDebug.$sDebugGuardar;
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Termina el guardar '.$sError.' -- '.$sErrorCerrando.'<br>';}
    if ($sError==''){
        $sError='<b>'.$ETI['msg_itemguardado'].'</b>';
        $iTipoError=1;
        if ($sErrorCerrando!=''){
            $iTipoError=0;
            $sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
        }
        if ($bCerrando){
            $sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
        }else{
            $bTraeAsistencias=true;
        }
        if ($bCambiaCatedra){
            $bTraeInfoCursos=true;
        }
    }else{
        $bTraerDatosBase=false;
        $bTraeInfoCursos=false;
    }
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error al Terminar el guardar '.$sError.'<br>';}
}
if((int)$_REQUEST['paso']==0){
    $sSQL='SELECT * FROM cara01encuesta WHERE cara01id='.$_REQUEST['cara23idencuesta'].'';
    $tabla=$objDB->ejecutasql($sSQL);
    if ($objDB->nf($tabla)>0) {
        $fila = $objDB->sf($tabla);
        $_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
        $_REQUEST['cara23idtercero'] = $fila['cara01idtercero'];
        $_REQUEST['cara01agnos']=$fila['cara01agnos'];
        $_REQUEST['cara01idzona']=$fila['cara01idzona'];
        $_REQUEST['cara01idcead']=$fila['cara01idcead'];
    }
}
if ($bTraerDatosBase){
    list($sErrorE, $aData, $sDebugR)=f2301_InfoEncuesta($_REQUEST['cara23idencuesta'], $objDB, $bDebug);
    $sDebug=$sDebug.$sDebugR;
    //$sError=$sError.$sErrorE;
    if ($sErrorE==''){
        $_REQUEST['cara01idperaca']=$aData['cara01idperaca'];
        $_REQUEST['cara01completa']=$aData['cara01completa'];
        $_REQUEST['cara01fechaencuesta']=$aData['cara01fechaencuesta'];
        $_REQUEST['cara01agnos']=$aData['cara01agnos'];
        $_REQUEST['cara01idzona']=$aData['cara01idzona'];
        $_REQUEST['cara01idcead']=$aData['cara01idcead'];
        $_REQUEST['cara01tipocaracterizacion']=$aData['cara01tipocaracterizacion'];
        $_REQUEST['cara01idperiodoacompana']=$aData['cara01idperiodoacompana'];
        $_REQUEST['cara01fechacierreacom']=$aData['cara01fechacierreacom'];
        $_REQUEST['cara01r1']=$aData['r1'];
        $_REQUEST['cara01r2']=$aData['r2'];
        $_REQUEST['cara01r3']=$aData['r3'];
        $_REQUEST['cara01r4']=$aData['r4'];
        $_REQUEST['bperacamat']=$aData['cara01idperiodoacompana'];
        $_REQUEST['cara01factorprincipaldesc']=$aData['cara01factorprincipaldesc'];
        $_REQUEST['cara01idcursocatedra']=$aData['cara01idcursocatedra'];
        $_REQUEST['cara01factorprincpermanencia']=$aData['cara01factorprincpermanencia'];
        $bHayData=true;
    }
}
if((int)$_REQUEST['paso']==0 || $bTraerDatosBase){
    $sSQL='SELECT unad11id, unad11razonsocial, unad11telefono, unad11correo FROM unad11terceros WHERE unad11id="'.$_REQUEST['cara23idtercero'].'"';
    $tabla=$objDB->ejecutasql($sSQL);
    if ($objDB->nf($tabla)>0){
        $fila=$objDB->sf($tabla);
        $_REQUEST['unad11telefono']=$fila['unad11telefono'];
        $_REQUEST['unad11correo']=$fila['unad11correo'];
    }
    $sSQL='SELECT core16idescuela, core16idprograma FROM core16actamatricula WHERE core16peraca="'.$_REQUEST['cara01idperaca'].'" AND core16tercero="'.$_REQUEST['cara23idtercero'].'"';
    $tabla=$objDB->ejecutasql($sSQL);
    if ($objDB->nf($tabla)>0){
        $fila=$objDB->sf($tabla);
        $_REQUEST['core16idescuela']=$fila['core16idescuela'];
        $_REQUEST['core16idprograma']=$fila['core16idprograma'];
    }
}
if ($bHayData){
    //--- Puntajes de la prueba.
    $_REQUEST['cara23comp_digital']=5;
    if ($aData['cara01niveldigital']>89){
        $_REQUEST['cara23comp_digital']=1;
    }else{
        if ($aData['cara01niveldigital']>49){$_REQUEST['cara23comp_digital']=3;}
    }
    $_REQUEST['cara23comp_cuanti']=5;
    if ($aData['cara01nivelrazona']>69){
        $_REQUEST['cara23comp_cuanti']=1;
    }else{
        if ($aData['cara01nivelrazona']>39){$_REQUEST['cara23comp_cuanti']=3;}
    }
    $_REQUEST['cara23comp_lectora']=5;
    if ($aData['cara01nivellectura']>99){
        $_REQUEST['cara23comp_lectora']=1;
    }else{
        if ($aData['cara01nivellectura']>49){$_REQUEST['cara23comp_lectora']=3;}
    }
    $_REQUEST['cara23comp_ingles']=5;
    if ($aData['cara01nivelingles']>89){
        $_REQUEST['cara23comp_ingles']=1;
    }else{
        if ($aData['cara01nivelingles']>49){$_REQUEST['cara23comp_ingles']=3;}
    }
    $_REQUEST['cara23comp_criterio']=0;
    $iRiesgo=$_REQUEST['cara23comp_digital']+$_REQUEST['cara23comp_cuanti']+$_REQUEST['cara23comp_lectora']+$_REQUEST['cara23comp_ingles'];
    if ($iRiesgo>4){$_REQUEST['cara23comp_criterio']=2;}
    if ($iRiesgo>15){$_REQUEST['cara23comp_criterio']=3;}
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Digital: '.$aData['cara01niveldigital'].' Razonamiento: '.$aData['cara01nivelrazona'].' Lector: '.$aData['cara01nivellectura'].' Ingles: '.$aData['cara01nivelingles'].' Riesgo: '.$iRiesgo.'<br>';}
}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
    $_REQUEST['paso']=2;
    $_REQUEST['cara23consec_nuevo']=numeros_validar($_REQUEST['cara23consec_nuevo']);
    if ($_REQUEST['cara23consec_nuevo']==''){$sError=$ERR['cara23consec'];}
    if ($sError==''){
        if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
            $sError=$ERR['8'];
        }
    }
    if ($sError==''){
        //Ver que el consecutivo no exista.
        $sSQL='SELECT cara23id FROM cara23acompanamento WHERE cara23consec='.$_REQUEST['cara23consec_nuevo'].' AND cara23idencuesta='.$_REQUEST['cara23idencuesta'].'';
        $tabla=$objDB->ejecutasql($sSQL);
        if ($objDB->nf($tabla)>0){
            $sError='El consecutivo '.$_REQUEST['cara23consec_nuevo'].' ya existe';
        }
    }
    if ($sError==''){
        //Aplicar el cambio.
        $sSQL='UPDATE cara23acompanamento SET cara23consec='.$_REQUEST['cara23consec_nuevo'].' WHERE cara23id='.$_REQUEST['cara23id'].'';
        $tabla=$objDB->ejecutasql($sSQL);
        $sDetalle='Cambia el consecutivo de '.$_REQUEST['cara23consec'].' a '.$_REQUEST['cara23consec_nuevo'].'';
        $_REQUEST['cara23consec']=$_REQUEST['cara23consec_nuevo'];
        $_REQUEST['cara23consec_nuevo']='';
        seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['cara23id'], $sDetalle, $objDB);
        $sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
        $iTipoError=1;
    }else{
        $iSector=93;
    }
}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
    $_REQUEST['paso']=2;
    list($sError, $iTipoError, $sDebugElimina)=f2323_db_Eliminar($_REQUEST['cara23id'], $objDB, $bDebug);
    $sDebug=$sDebug.$sDebugElimina;
    if ($sError==''){
        $_REQUEST['paso']=-1;
        $sError=$ETI['msg_itemeliminado'];
        $iTipoError=1;
    }
}
//Confirmar la discapacidad...
if ($_REQUEST['paso']==23){
    require 'lib2301consejero.php';
    $_REQUEST['paso']=2;
    $bMueveScroll=true;
    list($_REQUEST, $sErrorD, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f2301_db_GuardarDiscapacidad($_REQUEST, $objDB, $bDebug);
    $sDebug=$sDebug.$sDebugGuardar;
    if ($sErrorD==''){
        $sError='Se ha ejecutado la confirmacion de los datos de discapacidad';
        $iTipoError=1;
    }else{
        $sError=$sError.$sErrorD;
    }
}
if ($bTraeAsistencias){
    $_REQUEST['cara23asisteinduccion']=0;
    $_REQUEST['cara23asisteinmersioncv']=0;
    $_REQUEST['cara23nivela_digital']=0;
    $_REQUEST['cara23nivela_cuanti']=0;
    $_REQUEST['cara23nivela_lecto']=0;
    $_REQUEST['cara23nivela_ingles']=0;
    $_REQUEST['cara23nivela_exito']=0;
    $sSQL='SELECT TB.cara29idactividad, T2.cara28tipoactividad, T2.cara28formato 
FROM cara29actividadasiste AS TB, cara28actividades AS T2 
WHERE TB.cara29idtercero='.$_REQUEST['cara23idtercero'].' AND TB.cara29estado=7 AND TB.cara29idactividad=T2.cara28id';
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando asistencias: '.$sSQL.'<br>';}
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        switch($fila['cara28tipoactividad']){
            //1, 'Inducción General', 1), (2, 'Inducción Campus Virtual', 1), (3, 'Refuerzo Competencias Digitales', 1), (4, 'Refuerzo Competencias Cuantitativas', 1), (5, 'Refuerzo Competencias Lectoras', 1), (6, 'Refuerzo Competencias Ingles', 1), (7, 'Camino al Exito', 1
            case 1:
                $_REQUEST['cara23asisteinduccion']=1;
                //Asistencia virtual
                if ($fila['cara28formato']==1){$_REQUEST['cara23asisteinduccion']=2;}
                break;
            case 2:
                $_REQUEST['cara23asisteinmersioncv']=1;
                break;
            case 3:
                $_REQUEST['cara23nivela_digital']++;
                break;
            case 4:
                $_REQUEST['cara23nivela_cuanti']++;
                break;
            case 5:
                $_REQUEST['cara23nivela_lecto']++;
                break;
            case 6:
                $_REQUEST['cara23nivela_ingles']++;
                break;
            case 7:
                $_REQUEST['cara23nivela_exito']++;
                break;
        }
    }
}
if ($bTraeInfoCursos){
    $iFechaConsulta=0;
    $idContTercero=0;
    if ($_REQUEST['cara23idtipo']==3){
        if ($_REQUEST['cara01idcursocatedra']!=0){
            //Esta funcion es de la libc2
            list($iPuntaje, $iAprueba, $iNumCreditos, $idContTercero, $sDebugC)=f2400_AvanceEstudianteCurso($_REQUEST['cara23idtercero'], $_REQUEST['cara01idperiodoacompana'], $_REQUEST['cara01idcursocatedra'], $objDB, $idContTercero, $bDebug);
            $sDebug=$sDebug.$sDebugC;
            $_REQUEST['cara23catedra_aprueba']=0;
            if ($iAprueba>0){
                if ($iPuntaje>=$iAprueba){
                    $_REQUEST['cara23catedra_aprueba']=1;
                }
            }
        }
    }
    list($iTotalCursos, $iCursosSinIngreso, $iCursosPerdidos, $iNumCreditos, $iNumAprobados, $sDebugC)=f2400_AvanceEstudiantePeriodo($_REQUEST['cara23idtercero'], $_REQUEST['cara01idperiodoacompana'], $iFechaConsulta, $objDB, $idContTercero, $bDebug);
    $sDebug=$sDebug.$sDebugC;
    $_REQUEST['cara23cursos_total']=$iTotalCursos;
    $_REQUEST['cara23cursos_siningre']=$iCursosSinIngreso;
    $_REQUEST['cara23cursos_menor200']=$iCursosPerdidos;
    if ($_REQUEST['cara23cursos_total']>0){
        $_REQUEST['cara23cursos_porcing']=round((($_REQUEST['cara23cursos_total']-$_REQUEST['cara23cursos_siningre'])/$_REQUEST['cara23cursos_total'])*100, 2);
        $_REQUEST['cara23cursos_porcperdida']=round(($_REQUEST['cara23cursos_menor200']/$_REQUEST['cara23cursos_total'])*100, 2);
        $_REQUEST['cara23cursos_criterio']=1;
    }else{
        $_REQUEST['cara23cursos_porcing']=0;
        $_REQUEST['cara23cursos_porcperdida']=0;
        $_REQUEST['cara23cursos_criterio']=0;
    }
    if ($_REQUEST['cara23cursos_porcperdida']>=50){
        $_REQUEST['cara23cursos_criterio']=3;
    }else{
        if ($_REQUEST['cara23cursos_porcperdida']>=20){$_REQUEST['cara23cursos_criterio']=2;}
    }
    $_REQUEST['cara23factorriesgo']=$_REQUEST['cara23cursos_criterio'];
}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
    $_REQUEST['cara01idperaca']='';
    $_REQUEST['cara01completa']='N';
    $_REQUEST['cara01fechaencuesta']='';//fecha_hoy();
    $_REQUEST['cara01agnos']=0;
    $_REQUEST['unad11telefono']='';
    $_REQUEST['unad11correo']='';
    $_REQUEST['cara01idzona']=0;
    $_REQUEST['cara01idcead']=0;
    $_REQUEST['core16idescuela']=0;
    $_REQUEST['core16idprograma']=0;
    $_REQUEST['cara01tipocaracterizacion']=0;
    $_REQUEST['cara01idperiodoacompana']=0;
    $_REQUEST['cara01fechacierreacom']=0;
    $_REQUEST['cara01r1']='';
    $_REQUEST['cara01r2']='';
    $_REQUEST['cara01r3']='';
    $_REQUEST['cara01r4']='';
    $_REQUEST['cara01factorprincipaldesc']=0;
    $_REQUEST['cara01idcursocatedra']=0;
    $_REQUEST['cara01factorprincpermanencia']=0;
    $_REQUEST['cara23idtercero']=0;//$_SESSION['unad_id_tercero'];
    $_REQUEST['cara23idtercero_td']=$APP->tipo_doc;
    $_REQUEST['cara23idtercero_doc']='';
    $_REQUEST['cara23idencuesta']='';
    $_REQUEST['cara23consec']='';
    $_REQUEST['cara23consec_nuevo']='';
    $_REQUEST['cara23id']='';
    $_REQUEST['cara23idtipo']=1;
    $_REQUEST['cara23estado']=0;
    $_REQUEST['cara23asisteinduccion']=-1;
    $_REQUEST['cara23asisteinmersioncv']=-1;
    $_REQUEST['cara23catedra_skype']='';
    $_REQUEST['cara23catedra_bler1']='';
    $_REQUEST['cara23catedra_bler2']='';
    $_REQUEST['cara23catedra_webconf']='';
    $_REQUEST['cara23catedra_avance']=-1;
    $_REQUEST['cara23catedra_criterio']=0;
    $_REQUEST['cara23catedra_acciones']=-1;
    $_REQUEST['cara23catedra_resultados']=-1;
    $_REQUEST['cara23catedra_segprev']='';
    $_REQUEST['cara23cursos_total']='';
    $_REQUEST['cara23cursos_siningre']='';
    $_REQUEST['cara23cursos_porcing']=0;
    $_REQUEST['cara23cursos_menor200']='';
    $_REQUEST['cara23cursos_porcperdida']=0;
    $_REQUEST['cara23cursos_criterio']=0;
    $_REQUEST['cara23cursos_otros']='';
    $_REQUEST['cara23cursos_accionlider']='';
    $_REQUEST['cara23aler_sociodem']='';
    $_REQUEST['cara23aler_psico']='';
    $_REQUEST['cara23aler_academ']='';
    $_REQUEST['cara23aler_econom']='';
    $_REQUEST['cara23aler_externo']='';
    $_REQUEST['cara23aler_interno']='';
    $_REQUEST['cara23aler_nivel']='';
    $_REQUEST['cara23aler_criterio']='';
    $_REQUEST['cara23comp_digital']=0;
    $_REQUEST['cara23comp_cuanti']=0;
    $_REQUEST['cara23comp_lectora']=0;
    $_REQUEST['cara23comp_ingles']=0;
    $_REQUEST['cara23comp_criterio']=0;
    $_REQUEST['cara23nivela_digital']=-1;
    $_REQUEST['cara23nivela_cuanti']=-1;
    $_REQUEST['cara23nivela_lecto']=-1;
    $_REQUEST['cara23nivela_ingles']=-1;
    $_REQUEST['cara23nivela_exito']=-1;
    $_REQUEST['cara23contacto_efectivo']=0;
    $_REQUEST['cara23contacto_forma']='';
    $_REQUEST['cara23contacto_observa']='';
    $_REQUEST['cara23aplaza']=-1;
    $_REQUEST['cara23seretira']=-1;
    $_REQUEST['cara23factorriesgo']=0;
    $_REQUEST['cara23zonal_retro']='';
    $_REQUEST['cara23zonal_fecha']='';//fecha_hoy();
    $_REQUEST['cara23zonal_idlider']=$_SESSION['unad_id_tercero'];
    $_REQUEST['cara23zonal_idlider_td']=$APP->tipo_doc;
    $_REQUEST['cara23zonal_idlider_doc']='';
    $_REQUEST['cara23fecha']=0;//fecha_hoy();
    $_REQUEST['cara23cursos_ac1']=-1;
    $_REQUEST['cara23cursos_ac2']=-1;
    $_REQUEST['cara23cursos_ac3']=-1;
    $_REQUEST['cara23cursos_ac4']=-1;
    $_REQUEST['cara23cursos_ac5']=-1;
    $_REQUEST['cara23cursos_ac6']=-1;
    $_REQUEST['cara23cursos_ac7']=-1;
    $_REQUEST['cara23cursos_ac8']=-1;
    $_REQUEST['cara23cursos_ac9']=-1;
    $_REQUEST['cara23catedra_aprueba']=-1;
    $_REQUEST['cara23permanece']=-1;
    $_REQUEST['paso']=0;
    $_REQUEST['bperacamat']='';
}
if ($bLimpiaHijos){
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$sRiesgoSD=$_REQUEST['cara01r1'];
$sRiesgoPsico=$_REQUEST['cara01r2'];
$sRiesgoAcademico=$_REQUEST['cara01r3'];
$sRiesgoEconomico=$_REQUEST['cara01r4'];
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($devuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
//if ($devuelve){$seg_6=1;}
//$sDebug=$sDebug.$sDebugP;
$iTipoSeg=$_REQUEST['cara23idtipo'];
//Estas consideraciones son para las validaciones tambien.
$bInducciones=false;
$bPanelCatedra=true;
$bCatedra1=false;
$bCatedra3=false;
$bCursos1=false;
$bCursoAcciones=false;
$bAlertasIniciales=true;
$bSegZonal=false;
if ($_REQUEST['cara23estado']==7){
    $bSegZonal=true;
}
switch($iTipoSeg){
    case 1:
        $bInducciones=true;
        $bSegZonal=false;
        break;
    case 2:
        $bCatedra3=true;
        $bCursos1=true;
        $bCursoAcciones=true;
        $bAlertasIniciales=false;
        break;
    case 3:
        $bPanelCatedra=false;
        $bCursos1=true;
        $bAlertasIniciales=false;
        break;
}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($cara23idtercero_rs, $_REQUEST['cara23idtercero'], $_REQUEST['cara23idtercero_td'], $_REQUEST['cara23idtercero_doc'])=html_tercero($_REQUEST['cara23idtercero_td'], $_REQUEST['cara23idtercero_doc'], $_REQUEST['cara23idtercero'], 0, $objDB);
$cara23estado_nombre=$ETI['msg_borrador'];
if ($_REQUEST['cara23estado']==7){$cara23estado_nombre=$ETI['msg_completo'];}
$html_cara23estado=html_oculto('cara23estado', $_REQUEST['cara23estado'], $cara23estado_nombre);
if ($iTipoSeg==1){
    $objCombos->nuevo('cara23asisteinduccion', $_REQUEST['cara23asisteinduccion'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addArreglo($acara23asisteinduccion, $icara23asisteinduccion);
    $html_cara23asisteinduccion=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23asisteinmersioncv', $_REQUEST['cara23asisteinmersioncv'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addArreglo($acara23asisteinmersioncv, $icara23asisteinmersioncv);
    $html_cara23asisteinmersioncv=$objCombos->html('', $objDB);
}
$objCombos->nuevo('cara23catedra_skype', $_REQUEST['cara23catedra_skype'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23catedra_skype=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23catedra_bler1', $_REQUEST['cara23catedra_bler1'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23catedra_bler1=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23catedra_bler2', $_REQUEST['cara23catedra_bler2'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23catedra_bler2=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23catedra_webconf', $_REQUEST['cara23catedra_webconf'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23catedra_webconf=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23catedra_avance', $_REQUEST['cara23catedra_avance'], true, '{'.$ETI['msg_seleccione'].'}', -1);
$sSQL='SELECT cara24id AS id, cara24titulo AS nombre FROM cara24avancecatedra WHERE cara24id>0 ORDER BY cara24orden, cara24titulo';
// WHERE cara24jornada='.$iTipoSeg.'
$html_cara23catedra_avance=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara23catedra_acciones', $_REQUEST['cara23catedra_acciones'], true, '{'.$ETI['msg_seleccione'].'}', -1);
$sSQL='SELECT cara25id AS id, cara25titulo AS nombre FROM cara25accionescat ORDER BY cara25orden, cara25titulo';
$html_cara23catedra_acciones=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara23catedra_resultados', $_REQUEST['cara23catedra_resultados'], true, '{'.$ETI['msg_seleccione'].'}', -1);
$sSQL='SELECT cara26id AS id, cara26titulo AS nombre FROM cara26resultcat ORDER BY cara26orden, cara26titulo';
$html_cara23catedra_resultados=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara23aler_criterio', $_REQUEST['cara23aler_criterio'], true, ''.$ariesgo[0].'', 0);
$objCombos->addArreglo($ariesgo, $iriesgo);
$html_cara23aler_criterio=$objCombos->html('', $objDB);
if (false){
    $objCombos->nuevo('cara23comp_digital', $_REQUEST['cara23comp_digital'], true, '{'.$aNivelCompetencia[0].'}');
    $objCombos->addArreglo($aNivelCompetencia, $iNivelCompetencia);
    $html_cara23comp_digital=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23comp_cuanti', $_REQUEST['cara23comp_cuanti'], true, '{'.$aNivelCompetencia[0].'}');
    $objCombos->addArreglo($aNivelCompetencia, $iNivelCompetencia);
    $html_cara23comp_cuanti=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23comp_lectora', $_REQUEST['cara23comp_lectora'], true, '{'.$aNivelCompetencia[0].'}');
    $objCombos->addArreglo($aNivelCompetencia, $iNivelCompetencia);
    $html_cara23comp_lectora=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23comp_ingles', $_REQUEST['cara23comp_ingles'], true, '{'.$aNivelCompetencia[0].'}');
    $objCombos->addArreglo($aNivelCompetencia, $iNivelCompetencia);
    $html_cara23comp_ingles=$objCombos->html('', $objDB);
}else{
    $html_cara23comp_digital=html_oculto('cara23comp_digital', $_REQUEST['cara23comp_digital'], $aNivelCompetencia[$_REQUEST['cara23comp_digital']]);
    $html_cara23comp_cuanti=html_oculto('cara23comp_cuanti', $_REQUEST['cara23comp_cuanti'], $aNivelCompetencia[$_REQUEST['cara23comp_cuanti']]);
    $html_cara23comp_lectora=html_oculto('cara23comp_lectora', $_REQUEST['cara23comp_lectora'], $aNivelCompetencia[$_REQUEST['cara23comp_lectora']]);
    $html_cara23comp_ingles=html_oculto('cara23comp_ingles', $_REQUEST['cara23comp_ingles'], $aNivelCompetencia[$_REQUEST['cara23comp_ingles']]);
}
$html_cara23comp_criterio=html_oculto('cara23comp_criterio', $_REQUEST['cara23comp_criterio'], $ariesgo[$_REQUEST['cara23comp_criterio']]);
if (false){
    $objCombos->nuevo('cara23nivela_digital', $_REQUEST['cara23nivela_digital'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    //$objCombos->addArreglo($acara23nivela_digital, $icara23nivela_digital);
    $html_cara23nivela_digital=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23nivela_cuanti', $_REQUEST['cara23nivela_cuanti'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    //$objCombos->addArreglo($acara23nivela_cuanti, $icara23nivela_cuanti);
    $html_cara23nivela_cuanti=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23nivela_lecto', $_REQUEST['cara23nivela_lecto'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    //$objCombos->addArreglo($acara23nivela_lecto, $icara23nivela_lecto);
    $html_cara23nivela_lecto=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23nivela_ingles', $_REQUEST['cara23nivela_ingles'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    //$objCombos->addArreglo($acara23nivela_ingles, $icara23nivela_ingles);
    $html_cara23nivela_ingles=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23nivela_exito', $_REQUEST['cara23nivela_exito'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    //$objCombos->addArreglo($acara23nivela_exito, $icara23nivela_exito);
    $html_cara23nivela_exito=$objCombos->html('', $objDB);
}else{
    $html_cara23nivela_digital=html_oculto('cara23nivela_digital', $_REQUEST['cara23nivela_digital']);
    $html_cara23nivela_cuanti=html_oculto('cara23nivela_cuanti', $_REQUEST['cara23nivela_cuanti']);
    $html_cara23nivela_lecto=html_oculto('cara23nivela_lecto', $_REQUEST['cara23nivela_lecto']);
    $html_cara23nivela_ingles=html_oculto('cara23nivela_ingles', $_REQUEST['cara23nivela_ingles']);
    $html_cara23nivela_exito=html_oculto('cara23nivela_exito', $_REQUEST['cara23nivela_exito']);
}
$objCombos->nuevo('cara23contacto_efectivo', $_REQUEST['cara23contacto_efectivo'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23contacto_efectivo=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23contacto_forma', $_REQUEST['cara23contacto_forma'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, $ETI['msg_nocontacto']);
$sSQL='SELECT cara27id AS id, cara27titulo AS nombre FROM cara27mediocont WHERE cara27id>0 ORDER BY cara27orden, cara27titulo';
$html_cara23contacto_forma=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara23aplaza', $_REQUEST['cara23aplaza'], true, '', -1);
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23aplaza=$objCombos->html('', $objDB);
$objCombos->nuevo('cara23seretira', $_REQUEST['cara23seretira'], true, '', -1);
$objCombos->addItem(0, $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
$html_cara23seretira=$objCombos->html('', $objDB);
if ($_REQUEST['paso']>0){
    $_REQUEST['cara23factorriesgo']=0;
    switch($iTipoSeg){
        case 1:
            $iPuntaje=0;
            if (($_REQUEST['cara23asisteinduccion']==0)&&($_REQUEST['cara23asisteinmersioncv']==0)){
                $iPuntaje=5;
            }
            if ($_REQUEST['cara23catedra_criterio']==1){$iPuntaje=$iPuntaje+1;}
            if ($_REQUEST['cara23catedra_criterio']==2){$iPuntaje=$iPuntaje+3;}
            if ($_REQUEST['cara23catedra_criterio']==3){$iPuntaje=$iPuntaje+5;}
            if ($_REQUEST['cara23aler_criterio']==1){$iPuntaje=$iPuntaje+1;}
            if ($_REQUEST['cara23aler_criterio']==2){$iPuntaje=$iPuntaje+3;}
            if ($_REQUEST['cara23aler_criterio']==3){$iPuntaje=$iPuntaje+5;}
            if ($_REQUEST['cara23comp_criterio']==1){$iPuntaje=$iPuntaje+1;}
            if ($_REQUEST['cara23comp_criterio']==2){$iPuntaje=$iPuntaje+3;}
            if ($_REQUEST['cara23comp_criterio']==3){$iPuntaje=$iPuntaje+5;}
            if ($iPuntaje>0){$_REQUEST['cara23factorriesgo']=1;}
            if ($iPuntaje>4){$_REQUEST['cara23factorriesgo']=2;}
            if ($iPuntaje>9){$_REQUEST['cara23factorriesgo']=3;}
            break;
        case 2:
        case 3:
            $_REQUEST['cara23factorriesgo']=$_REQUEST['cara23cursos_criterio'];
            break;
    }
}
$html_cara23factorriesgo=html_oculto('cara23factorriesgo', $_REQUEST['cara23factorriesgo'], $ariesgo[$_REQUEST['cara23factorriesgo']]);
list($cara23zonal_idlider_rs, $_REQUEST['cara23zonal_idlider'], $_REQUEST['cara23zonal_idlider_td'], $_REQUEST['cara23zonal_idlider_doc'])=html_tercero($_REQUEST['cara23zonal_idlider_td'], $_REQUEST['cara23zonal_idlider_doc'], $_REQUEST['cara23zonal_idlider'], 0, $objDB);
$cara01idperaca_nombre='&nbsp;';
$cara01tipocaracterizacion_nombre='&nbsp;';
$cara01fechacierreacom_nombre='&nbsp;';
$cara01idzona_nombre='&nbsp;';
$cara01idcead_nombre='&nbsp;';
$core16idescuela_nombre='&nbsp;';
$core16idprograma_nombre='&nbsp;';

if ($bCursoAcciones){
    $objCombos->nuevo('cara23cursos_ac1', $_REQUEST['cara23cursos_ac1'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac1=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac2', $_REQUEST['cara23cursos_ac2'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac2=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac3', $_REQUEST['cara23cursos_ac3'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac3=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac4', $_REQUEST['cara23cursos_ac4'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac4=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac5', $_REQUEST['cara23cursos_ac5'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac5=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac6', $_REQUEST['cara23cursos_ac6'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac6=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac7', $_REQUEST['cara23cursos_ac7'], false, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac7=$objCombos->html('', $objDB);
    /*
    $objCombos->nuevo('cara23cursos_ac8', $_REQUEST['cara23cursos_ac8'], true, '', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23cursos_ac8=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23cursos_ac9', $_REQUEST['cara23cursos_ac9'], true, '{'.$ETI['msg_seleccione'].'}');
    $objCombos->addArreglo($acara23cursos_ac9, $icara23cursos_ac9);
    $html_cara23cursos_ac9=$objCombos->html('', $objDB);
    */
}
if ($iTipoSeg==3){
    $objCombos->nuevo('cara23catedra_aprueba', $_REQUEST['cara23catedra_aprueba'], true, '{'.$ETI['msg_na'].'}', -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23catedra_aprueba=$objCombos->html('', $objDB);
    $objCombos->nuevo('cara23permanece', $_REQUEST['cara23permanece'], true, $acara23permanece[0], -1);
    $objCombos->addItem(0, $ETI['no']);
    $objCombos->addItem(1, $ETI['si']);
    $html_cara23permanece=$objCombos->html('', $objDB);
}
if (true){
    $objCombos->nuevo('cara01factorprincipaldesc', $_REQUEST['cara01factorprincipaldesc'], true, '{'.$ETI['msg_ninguno'].'}', 0);
    $sSQL='SELECT cara15id AS id, cara15nombre AS nombre FROM cara15factordeserta WHERE cara15id>0 ORDER BY cara15nombre';
    $html_cara01factorprincipaldesc=$objCombos->html($sSQL, $objDB);
    $sIds40='-99';
    if ($_REQUEST['paso']!=0){
        //Buscar los codigos que tengan la palabra catedra.
        list($iContenedor, $sErrorT)=f1011_BloqueTercero($_REQUEST['cara23idtercero'], $objDB);
        $sSQL='SELECT TB.core04idcurso 
FROM core04matricula_'.$iContenedor.' AS TB, unad40curso AS T4 
WHERE TB.core04peraca='.$_REQUEST['bperacamat'].' AND TB.core04tercero='.$_REQUEST['cara23idtercero'].' 
AND TB.core04idcurso=T4.unad40id AND T4.unad40nombre LIKE "%CATEDRA%"';
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $sIds40=$sIds40.','.$fila['core04idcurso'];
        }
    }
    $objCombos->nuevo('cara01idcursocatedra', $_REQUEST['cara01idcursocatedra'], true, '{'.$ETI['msg_ninguno'].'}', 0);
    $sSQL='SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre FROM unad40curso WHERE unad40id IN ('.$sIds40.') ORDER BY unad40titulo';
    $objCombos->iAncho=300;
    $html_cara01idcursocatedra=$objCombos->html($sSQL, $objDB);
    if ($iTipoSeg==3){
        $objCombos->nuevo('cara01factorprincpermanencia', $_REQUEST['cara01factorprincpermanencia'], true, '{'.$ETI['msg_ninguno'].'}', 0);
        $sSQL='SELECT cara35id AS id, cara35nombre AS nombre FROM cara35factorpermanece WHERE cara35id>0 ORDER BY cara35nombre';
        $html_cara01factorprincpermanencia=$objCombos->html($sSQL, $objDB);
    }
}
if ((int)$_REQUEST['paso']==0){
    $html_cara23idencuesta=f2323_HTMLComboV2_cara23idencuesta($objDB, $objCombos, $_REQUEST['cara23idencuesta'], $_REQUEST['cara23idtercero']);
    $html_cara23consec=html_oculto('cara23consec', $_REQUEST['cara23consec']);
    $objCombos->nuevo('cara23idtipo', $_REQUEST['cara23idtipo'], false, '{'.$ETI['msg_seleccione'].'}');
    $objCombos->sAccion='cambiapagina()';
    $objCombos->addArreglo($acara23idtipo, $icara23idtipo);
    $html_cara23idtipo=$objCombos->html('', $objDB);
    $html_bperacamat=html_oculto('bperacamat', $_REQUEST['bperacamat'], '&nbsp;');
}else{
    //list($cara23idencuesta_nombre, $sErrorDet)=tabla_campoxid('cara01encuesta','cara01idprograma','cara01id',$_REQUEST['cara23idencuesta'],'{'.$ETI['msg_sindato'].'}', $objDB);
    $html_cara23idencuesta=html_oculto('cara23idencuesta', $_REQUEST['cara23idencuesta'], formato_numero($_REQUEST['cara23idencuesta']));
    $html_cara23consec=html_oculto('cara23consec', $_REQUEST['cara23consec']);
    $cara23idtipo_nombre=$acara23idtipo[$_REQUEST['cara23idtipo']];
    $html_cara23idtipo=html_oculto('cara23idtipo', $_REQUEST['cara23idtipo'], $cara23idtipo_nombre);
    list($cara01idperaca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['cara01idperaca'],'{'.$ETI['msg_sindato'].'}', $objDB);
    list($cara01tipocaracterizacion_nombre, $sErrorDet)=tabla_campoxid('cara11tipocaract','cara11nombre','cara11id',$_REQUEST['cara01tipocaracterizacion'],'{'.$ETI['msg_ninguna'].'}', $objDB);
    if ($_REQUEST['cara01fechacierreacom']!=0){
        $cara01fechacierreacom_nombre=fecha_desdenumero($_REQUEST['cara01fechacierreacom']);
    }
    $objCombos->nuevo('bperacamat', $_REQUEST['bperacamat'], false, '{'.$ETI['msg_todos'].'}');
    $objCombos->sAccion='paginarf2451()';
    $sIds='-99';
    $sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$_REQUEST['cara23idtercero'].'';
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        $sIds=$sIds.','.$fila['core16peraca'];
    }
    $sSQL=f146_ConsultaCombo('exte02id IN ('.$sIds.')', $objDB);
    $html_bperacamat=$objCombos->html($sSQL, $objDB);
}
list($cara01idzona_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['cara01idzona'],'{'.$ETI['msg_ninguna'].'}', $objDB);
list($cara01idcead_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['cara01idcead'],'{'.$ETI['msg_ninguna'].'}', $objDB);
list($core16idescuela_nombre, $sErrorDet)=tabla_campoxid('core12escuela','core12nombre','core12id',$_REQUEST['core16idescuela'],'{'.$ETI['msg_ninguna'].'}', $objDB);
list($core16idprograma_nombre, $sErrorDet)=tabla_campoxid('core09programa','core09nombre','core09id',$_REQUEST['core16idprograma'],'{'.$ETI['msg_ninguna'].'}', $objDB);
$html_cara01idperaca=html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
$html_cara01tipocaracterizacion=html_oculto('cara01tipocaracterizacion', $_REQUEST['cara01tipocaracterizacion'], $cara01tipocaracterizacion_nombre);
$html_cara01idzona=html_oculto('cara01idzona', $_REQUEST['cara01idzona'], $cara01idzona_nombre);
$html_cara01idcead=html_oculto('cara01idcead', $_REQUEST['cara01idcead'], $cara01idcead_nombre);
$html_core16idescuela=html_oculto('core16idescuela', $_REQUEST['core16idescuela'], $core16idescuela_nombre);
$html_core16idprograma=html_oculto('core16idprograma', $_REQUEST['core16idprograma'], $core16idprograma_nombre);
$html_cara01idperiodoacompana=html_oculto('cara01idperiodoacompana', $_REQUEST['cara01idperiodoacompana']);
$html_cara01fechacierreacom=html_oculto('cara01fechacierreacom', $_REQUEST['cara01fechacierreacom'], $cara01fechacierreacom_nombre);
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
    if ($_REQUEST['cara23estado']==7){
        //Definir las condiciones que permitirán abrir el registro.
        if (seg_revisa_permiso($iCodModulo, 17, $objDB)){$bPuedeAbrir=true;}
    }
}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if (true){
    $objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
    $objCombos->sAccion='paginarf2301()';
    $objCombos->addItem(1, 'Donde soy consejero');
    $objCombos->addItem(11, 'Encuestas terminadas');
    $objCombos->addItem(12, 'Encuestas incompletas');
    $html_blistar=$objCombos->html('', $objDB);
    $objCombos->nuevo('bperaca', $_REQUEST['bperaca'], true, '{'.$ETI['msg_todos'].'}');
    $objCombos->sAccion='paginarf2301()';
    $sIds='-99';
    $sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        $sIds=$sIds.','.$fila['cara01idperaca'];
    }
    $sSQL=f146_ConsultaCombo('exte02id IN ('.$sIds.')', $objDB);
    $html_bperaca=$objCombos->html($sSQL, $objDB);
    $objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{'.$ETI['msg_todas'].'}');
    $objCombos->sAccion='carga_combo_bprograma()';
    $sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
    $html_bescuela=$objCombos->html($sSQL, $objDB);
    $html_bprograma=f2301_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
    $objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{'.$ETI['msg_todas'].'}');
    $objCombos->sAccion='carga_combo_bcead()';
    $sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
    $html_bzona=$objCombos->html($sSQL, $objDB);
    $html_bcead=f2301_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
    $objCombos->nuevo('btipocara', $_REQUEST['btipocara'], true, '{'.$ETI['msg_todas'].'}');
    $objCombos->sAccion='paginarf2301()';
    $sSQL='SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
    $html_btipocara=$objCombos->html($sSQL, $objDB);
    $objCombos->nuevo('bpoblacion', $_REQUEST['bpoblacion'], true, '{'.$ETI['msg_todas'].'}');
    $objCombos->sAccion='paginarf2301()';
    $objCombos->addItem(1, 'Con necesidades especiales');
    $objCombos->addItem(2, 'Con necesidades especiales [Por confirmar]');
    $html_bpoblacion=$objCombos->html('', $objDB);
    $objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{'.$ETI['msg_todos'].'}');
    $objCombos->sAccion='paginarf2301()';
    $sSQL='SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
    $html_bconvenio=$objCombos->html($sSQL, $objDB);
    //$html_blistar=$objCombos->comboSistema(2301, 1, $objDB, 'paginarf2301()');
}
if ($seg_6==1){}
if (false){
    $objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
    $objCombos->addItem(',', $ETI['msg_coma']);
    $objCombos->addItem(';', $ETI['msg_puntoycoma']);
    $csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
}else{
    $csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime=0;
$iModeloReporte=2323;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
    if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
        $seg_5=1;
    }
    if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
}
//Cargar las tablas de datos
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf2323'];
$aParametros[102]=$_REQUEST['lppf2323'];
$aParametros[103]=$_REQUEST['bdoc'];
$aParametros[104]=$_REQUEST['bnombre'];
$aParametros[105]=$_REQUEST['bperaca'];
$aParametros[106]=$_REQUEST['blistar'];
$aParametros[107]=$_REQUEST['bescuela'];
$aParametros[108]=$_REQUEST['bprograma'];
$aParametros[109]=$_REQUEST['bzona'];
$aParametros[110]=$_REQUEST['bcead'];
$aParametros[111]=$_REQUEST['btipocara'];
$aParametros[112]=$_REQUEST['bpoblacion'];
$aParametros[113]=$_REQUEST['bconvenio'];
list($sTabla2323, $sDebugTabla)=f2323_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2451='';
$sHist['cara23catedra_avance']='';
$sHist['cara23catedra_acciones']='';
$sHist['cara23aler_sociodem']='';
if ($_REQUEST['paso']!=0){
    $aParametros[101]=$_REQUEST['paginaf2451'];
    $aParametros[102]=$_REQUEST['lppf2451'];
    $aParametros[103]=$_REQUEST['cara23idtercero'];
    $aParametros[104]=$_REQUEST['bperacamat'];
    $aParametros[105]=-1;
    list($sTabla2451, $sDebugTabla)=f2451_TablaDetalleV2($aParametros, $objDB, $bDebug);
    $sDebug=$sDebug.$sDebugTabla;
    if ($bPanelCatedra){
        $sSQL='SELECT cara23fecha, cara23estado, cara23catedra_avance, cara23catedra_acciones, cara23aler_sociodem 
FROM cara23acompanamento 
WHERE cara23idencuesta='.$_REQUEST['cara23idencuesta'].' AND cara23id<>'.$_REQUEST['cara23id'].' AND cara23estado=7 
ORDER BY cara23consec';
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $sFecha=fecha_desdenumero($fila['cara23fecha']);
            $sNomCatedra='{'.$fila['cara23catedra_avance'].'}';
            $sSQL='SELECT cara24titulo FROM cara24avancecatedra WHERE cara24id='.$fila['cara23catedra_avance'].'';
            $tabla24=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tabla24)>0){
                $fila24=$objDB->sf($tabla24);
                $sNomCatedra=cadena_notildes($fila24['cara24titulo']);
            }
            $sHist['cara23catedra_avance']=$sHist['cara23catedra_avance'].' '.$sFecha.' - '.$sNomCatedra.html_salto();

//$sSQL='SELECT cara25id AS id, cara25titulo AS nombre FROM cara25accionescat ORDER BY cara25orden, cara25titulo';
            $sCatedraAcciones='{'.$fila['cara23catedra_acciones'].'}';
            $sSQL='SELECT cara25titulo FROM cara25accionescat WHERE cara25id='.$fila['cara23catedra_acciones'].'';
            $tabla24=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tabla24)>0){
                $fila24=$objDB->sf($tabla24);
                $sCatedraAcciones=cadena_notildes($fila24['cara25titulo']);
            }
            $sHist['cara23catedra_acciones']=$sHist['cara23catedra_acciones'].' '.$sFecha.' - '.$sCatedraAcciones.html_salto();
            $sHist['cara23aler_sociodem']=$sHist['cara23aler_sociodem'].' '.$sFecha.' - '.cadena_notildes($fila['cara23aler_sociodem']).''.html_salto();
        }
        //Termina el panel de catedra.
    }
}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2323']);
echo $et_menu;
forma_mitad();
if (false){
    ?>
    <link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
    <link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
    <link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
    <?php
}
?>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
    <script language="javascript">
        <!--
        function limpiapagina(){
            expandesector(98);
            window.document.frmedita.paso.value=-1;
            window.document.frmedita.submit();
        }
        function enviaguardar(){
            window.document.frmedita.iscroll.value=window.pageYOffset;
            expandesector(98);
            var dpaso=window.document.frmedita.paso;
            if (dpaso.value==0){
                dpaso.value=10;
            }else{
                dpaso.value=12;
            }
            window.document.frmedita.submit();
        }
        function cambiapagina(){
            expandesector(98);
            window.document.frmedita.submit();
        }
        function cambiapaginaV2(){
            expandesector(98);
            window.document.frmedita.paso.value=1;
            window.document.frmedita.submit();
        }
        function expandepanel(codigo,estado,valor){
            var objdiv= document.getElementById('div_p'+codigo);
            var objban= document.getElementById('boculta'+codigo);
            var otroestado='none';
            if (estado=='none'){otroestado='block';}
            objdiv.style.display=estado;
            objban.value=valor;
            verboton('btrecoge'+codigo,estado);
            verboton('btexpande'+codigo,otroestado);
        }
        function verboton(idboton,estado){
            var objbt=document.getElementById(idboton);
            objbt.style.display=estado;
        }
        function expandesector(codigo){
            document.getElementById('div_sector1').style.display='none';
            document.getElementById('div_sector2').style.display='none';
            document.getElementById('div_sector93').style.display='none';
            document.getElementById('div_sector95').style.display='none';
            document.getElementById('div_sector96').style.display='none';
            document.getElementById('div_sector97').style.display='none';
            document.getElementById('div_sector98').style.display='none';
            document.getElementById('div_sector'+codigo).style.display='block';
            if (window.document.frmedita.cara23estado.value!=7){
                var sEst='none';
                if (codigo==1){sEst='block';}
                document.getElementById('cmdGuardarf').style.display=sEst;
            }
        }
        function ter_retorna(){
            var sRetorna=window.document.frmedita.div96v2.value;
            if (sRetorna!=''){
                var idcampo=window.document.frmedita.div96campo.value;
                var illave=window.document.frmedita.div96llave.value;
                var did=document.getElementById(idcampo);
                var dtd=document.getElementById(idcampo+'_td');
                var ddoc=document.getElementById(idcampo+'_doc');
                dtd.value=window.document.frmedita.div96v1.value;
                ddoc.value=sRetorna;
                did.value=window.document.frmedita.div96v3.value;
                ter_muestra(idcampo, illave);
            }
            MensajeAlarmaV2('', 0);
            retornacontrol();
        }
        function ter_muestra(idcampo, illave){
            var params=new Array();
            params[1]=document.getElementById(idcampo+'_doc').value;
            if (params[1]!=''){
                params[0]=document.getElementById(idcampo+'_td').value;
                params[2]=idcampo;
                params[3]='div_'+idcampo;
                if (illave==1){
                    params[4]='carga_combo_cara23idencuesta';
                    params[5]='carga_combo_cara23idencuesta';
                }
                xajax_unad11_Mostrar_v2(params);
            }else{
                document.getElementById(idcampo).value=0;
                document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
                if (illave==1){
                    carga_combo_cara23idencuesta();
                }
            }
        }
        function ter_traerxid(idcampo, vrcampo){
            var params=new Array();
            params[0]=vrcampo;
            params[1]=idcampo;
            if (params[0]!=0){
                if (idcampo=='cara01idtercero'){
                    params[4]='carga_combo_cara23idencuesta';
                    params[5]='carga_combo_cara23idencuesta';
                }
                xajax_unad11_TraerXid(params);
            }
        }
        function imprimelista(){
            if (window.document.frmedita.seg_6.value==1){
                window.document.frmlista.consulta.value=window.document.frmedita.consulta_2323.value;
                window.document.frmlista.titulos.value=window.document.frmedita.titulos_2323.value;
                window.document.frmlista.nombrearchivo.value='Acompanamiento';
                window.document.frmlista.submit();
            }else{
                window.alert("<?php echo $ERR['6']; ?>");
            }
        }
        function asignarvariables(){
            //window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
            //window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
            //window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
        }
        function imprimeexcel(){
            var sError='';
            if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
            //if (sError==''){/*Agregar validaciones*/}
            if (sError==''){
                asignarvariables();
                window.document.frmimpp.action='e2323.php';
                window.document.frmimpp.submit();
            }else{
                window.alert(sError);
            }
        }
        function imprimep(){
            if (window.document.frmedita.seg_5.value==1){
                asignarvariables();
                window.document.frmimpp.action='p2323.php';
                window.document.frmimpp.submit();
                <?php
                if ($iNumFormatosImprime>0){
                ?>
                expandesector(1);
                <?php
                }
                ?>
            }else{
                window.alert("<?php echo $ERR['5']; ?>");
            }
        }
        function verrpt(){
            window.document.frmimprime.submit();
        }
        function eliminadato(){
            if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
                expandesector(98);
                window.document.frmedita.paso.value=13;
                window.document.frmedita.submit();
            }
        }
        function RevisaLlave(){
            var datos= new Array();
            datos[1]=window.document.frmedita.cara23idtercero.value;
            datos[2]=window.document.frmedita.cara23idencuesta.value;
            datos[3]=window.document.frmedita.cara23consec.value;
            if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')){
                xajax_f2323_ExisteDato(datos);
            }
        }
        function cargadato(llave1, llave2, llave3){
            window.document.frmedita.cara23idtercero.value=String(llave1);
            document.getElementById('div_cara23idencuesta').innerHTML='<input id="cara23idencuesta" name="cara23idencuesta" type="hidden" value="'+String(llave2)+'" />';
            window.document.frmedita.cara23consec.value=String(llave3);
            window.document.frmedita.paso.value=1;
            window.document.frmedita.submit();
        }
        function cargaridf2301(llave1, llave2){
            if (window.document.frmedita.paso.value==0){
                var params=new Array();
                params[0]=llave1;
                params[1]='cara23idtercero';
                if (params[0]!=0){
                    params[4]='carga_combo_cara23idencuesta('+llave2+')';
                    params[5]='carga_combo_cara23idencuesta('+llave2+')';
                    xajax_unad11_TraerXid(params);
                }
            }
            window.document.frmedita.cara23idtercero.value=String(llave1);
            document.getElementById('div_cara23idencuesta').innerHTML='<input id="cara23idencuesta" name="cara23idencuesta" type="hidden" value="'+String(llave2)+'" />';
            window.document.frmedita.cara23consec.value='';
            window.document.frmedita.paso.value=0;
            window.document.frmedita.submit();
        }
        function cargaridf2323(llave1){
            window.document.frmedita.cara23id.value=String(llave1);
            window.document.frmedita.paso.value=3;
            window.document.frmedita.submit();
        }
        function carga_combo_cara23idencuesta(valor=0){
            var params=new Array();
            params[0]=window.document.frmedita.cara23idtercero.value;
            params[1]=valor;
            xajax_f2323_Combocara23idencuesta(params);
        }
        function paginarf2301(){
            paginarf2323();
        }
        function paginarf2323(){
            var params=new Array();
            params[99]=window.document.frmedita.debug.value;
            params[100]=<?php echo $idTercero; ?>;
            params[101]=window.document.frmedita.paginaf2323.value;
            params[102]=window.document.frmedita.lppf2323.value;
            params[103]=window.document.frmedita.bdoc.value;
            params[104]=window.document.frmedita.bnombre.value;
            params[105]=window.document.frmedita.bperaca.value;
            params[106]=window.document.frmedita.blistar.value;
            params[107]=window.document.frmedita.bescuela.value;
            params[108]=window.document.frmedita.bprograma.value;
            params[109]=window.document.frmedita.bzona.value;
            params[110]=window.document.frmedita.bcead.value;
            params[111]=window.document.frmedita.btipocara.value;
            params[112]=window.document.frmedita.bpoblacion.value;
            params[113]=window.document.frmedita.bconvenio.value;
            //document.getElementById('div_f2323detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2323" name="paginaf2323" type="hidden" value="'+params[101]+'" /><input id="lppf2323" name="lppf2323" type="hidden" value="'+params[102]+'" />';
            xajax_f2323_HtmlTabla(params);
        }
        function enviacerrar(){
            if (confirm('Esta seguro de cerrar el registro?\nluego de cerrado no se permite modificar')){
                expandesector(98);
                window.document.frmedita.paso.value=16;
                window.document.frmedita.submit();
            }
        }
        function enviaabrir(){
            if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')){
                expandesector(98);
                window.document.frmedita.paso.value=17;
                window.document.frmedita.submit();
            }
        }
        function revfoco(objeto){
            setTimeout(function(){objeto.focus();},10);
        }
        function siguienteobjeto(){}
        document.onkeydown=function(e){
            if (document.all){
                if (event.keyCode==13){event.keyCode=9;}
            }else{
                if (e.which==13){siguienteobjeto();}
            }
        }
        function objinicial(){
            document.getElementById("cara23idencuesta").focus();
        }
        function buscarV2016(sCampo){
            window.document.frmedita.iscroll.value=window.pageYOffset;
            expandesector(98);
            window.document.frmedita.scampobusca.value=sCampo;
            var params=new Array();
            params[1]=sCampo;
            //params[2]=window.document.frmedita.iagno.value;
            //params[3]=window.document.frmedita.itipo.value;
            xajax_f2323_Busquedas(params);
        }
        function retornacontrol(){
            expandesector(1);
            window.scrollTo(0, window.document.frmedita.iscroll.value);
        }
        function Devuelve(sValor){
            var sCampo=window.document.frmedita.scampobusca.value;
            if (sCampo=='cara23idtercero'){
                ter_traerxid('cara23idtercero', sValor);
            }
            if (sCampo=='cara23zonal_idlider'){
                ter_traerxid('cara23zonal_idlider', sValor);
            }
            retornacontrol();
        }
        function mantener_sesion(){xajax_sesion_mantenerV4();}
        setInterval ('xajax_sesion_abandona_V2();', 60000);
        function AyudaLocal(sCampo){
            var divAyuda=document.getElementById('div_ayuda_'+sCampo);
            if (typeof divAyuda==='undefined'){
            }else{
                verboton('cmdAyuda_'+sCampo, 'none');
                var sMensaje='Lo que quiera decir.';
                //if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
                divAyuda.innerHTML=sMensaje;
                divAyuda.style.display='block';
            }
        }
        function cierraDiv96(ref){
            var sRetorna=window.document.frmedita.div96v2.value;
            MensajeAlarmaV2('', 0);
            retornacontrol();
        }
        function mod_consec(){
            if (confirm("Esta seguro de cambiar el consecutivo?")){
                expandesector(98);
                window.document.frmedita.paso.value=93;
                window.document.frmedita.submit();
            }
        }
        function carga_combo_bprograma(){
            var params=new Array();
            params[0]=window.document.frmedita.bescuela.value;
            xajax_f2301_Combobprograma(params);
        }
        function carga_combo_bcead(){
            var params=new Array();
            params[0]=window.document.frmedita.bzona.value;
            xajax_f2301_Combobcead(params);
        }
        function confirmadisc(){
            expandesector(98);
            window.document.frmedita.paso.value=23;
            window.document.frmedita.submit();
        }
        function paginarf2451(){
            var params=new Array();
            params[99]=window.document.frmedita.debug.value;
            params[101]=window.document.frmedita.paginaf2451.value;
            params[102]=window.document.frmedita.lppf2451.value;
            params[103]=window.document.frmedita.cara23idtercero.value;
            params[104]=window.document.frmedita.bperacamat.value;
            params[105]=-1;
            //document.getElementById('div_f2451detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2451" name="paginaf2451" type="hidden" value="'+params[101]+'" /><input id="lppf2451" name="lppf2451" type="hidden" value="'+params[102]+'" />';
            xajax_f2451_HtmlTabla(params);
        }
        function irencuesta(){
            window.document.frmencuesta.paso.value=3;
            window.document.frmencuesta.submit();
        }
        // -->
    </script>
<?php
if ($_REQUEST['paso']!=0){
    ?>
    <form id="frmencuesta" name="frmencuesta" method="post" action="caracterizacion.php" target="_blank">
        <input id="paso" name="paso" type="hidden" value="" />
        <input id="cara01id" name="cara01id" type="hidden" value="<?php echo $_REQUEST['cara23idencuesta']; ?>" />
    </form>
    <form id="frmimpp" name="frmimpp" method="post" action="p2323.php" target="_blank">
        <input id="r" name="r" type="hidden" value="2323" />
        <input id="id2323" name="id2323" type="hidden" value="<?php echo $_REQUEST['cara23id']; ?>" />
        <input id="v3" name="v3" type="hidden" value="" />
        <input id="v4" name="v4" type="hidden" value="" />
        <input id="v5" name="v5" type="hidden" value="" />
        <input id="iformato94" name="iformato94" type="hidden" value="0" />
        <input id="separa" name="separa" type="hidden" value="," />
        <input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
        <input id="clave" name="clave" type="hidden" value="" />
    </form>
    <?php
}
?>
    <form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
        <input id="titulos" name="titulos" type="hidden" value="" />
        <input id="consulta" name="consulta" type="hidden" value="" />
        <input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
    </form>
    <div id="interna">
        <form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
            <input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
            <input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
            <input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
            <input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
            <input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
            <input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
            <input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
            <input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
            <input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
            <input id="cara01r1" name="cara01r1" type="hidden" value="<?php echo $_REQUEST['cara01r1']; ?>" />
            <input id="cara01r2" name="cara01r2" type="hidden" value="<?php echo $_REQUEST['cara01r2']; ?>" />
            <input id="cara01r3" name="cara01r3" type="hidden" value="<?php echo $_REQUEST['cara01r3']; ?>" />
            <input id="cara01r4" name="cara01r4" type="hidden" value="<?php echo $_REQUEST['cara01r4']; ?>" />
            <div id="div_sector1">
                <div class="titulos">
                    <div class="titulosD">
                        <input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
                        <?php
                        if ($_REQUEST['paso']==2){
                            if ($_REQUEST['cara23estado']!=7){
                                ?>
                                <input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
                                <?php
                            }
                        }
                        $bHayImprimir=false;
                        $sScript='imprimelista()';
                        $sClaseBoton='btEnviarExcel';
                        if ($seg_6==1){$bHayImprimir=true;}
                        if ($_REQUEST['paso']!=0){
                            if ($seg_5==1){
                                if ($_REQUEST['cara23estado']==7){
                                    //$bHayImprimir=true;
                                    //$sScript='imprimep()';
                                    //if ($iNumFormatosImprime>0){
                                    //$sScript='expandesector(94)';
                                    //}
                                    //$sClaseBoton='btEnviarPDF'; //btUpPrint
                                    //if ($id_rpt!=0){$sScript='verrpt()';}
                                }
                            }
                        }
                        if ($bHayImprimir){
                            ?>
                            <input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
                            <?php
                        }
                        ?>
                        <input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
                        <?php
                        if ($_REQUEST['cara23estado']!=7){
                            ?>
                            <input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
                            <?php
                            if ($_REQUEST['paso']>0){
                                ?>
                                <input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
                                <?php
                            }
                        }else{
                            if ($_REQUEST['paso']>0){
                                if ($bPuedeAbrir){
                                    ?>
                                    <input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir"/>
                                    <?php
                                }
                            }
                        }
                        if (false){
                            ?>
                            <input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="titulosI">
                        <?php
                        echo '<h2>'.$ETI['titulo_2323'].'</h2>';
                        ?>
                    </div>
                </div>
                <div class="areaform">
                    <div class="areatrabajo">
                        <?php
                        //Div para ocultar
                        $bconexpande=true;
                        if ($bconexpande){
                        ?>
                        <div class="ir_derecha" style="width:62px;">
                            <input id="boculta2323" name="boculta2323" type="hidden" value="<?php echo $_REQUEST['boculta2323']; ?>" />
                            <label class="Label30">
                                <input id="btexpande2323" name="btexpande2323" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2323,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2323']==0){echo 'none'; }else{echo 'block';} ?>;"/>
                            </label>
                            <label class="Label30">
                                <input id="btrecoge2323" name="btrecoge2323" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2323,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2323']==0){echo 'block'; }else{echo 'none';} ?>;"/>
                            </label>
                        </div>
                        <div id="div_p2323" style="display:<?php if ($_REQUEST['boculta2323']==0){echo 'block'; }else{echo 'none';} ?>;">
                            <?php
                            }
                            //Mostrar formulario para editar
                            ?>
                            <div class="GrupoCampos450">
                                <label class="TituloGrupo">
                                    <?php
                                    echo $ETI['cara23idtercero'];
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <input id="cara23idtercero" name="cara23idtercero" type="hidden" value="<?php echo $_REQUEST['cara23idtercero']; ?>"/>
                                <div id="div_cara23idtercero_llaves">
                                    <?php
                                    $bOculto=true;
                                    if ($_REQUEST['paso']!=2){$bOculto=false;}
                                    echo html_DivTerceroV2('cara23idtercero', $_REQUEST['cara23idtercero_td'], $_REQUEST['cara23idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
                                    ?>
                                </div>
                                <div class="salto1px"></div>
                                <div id="div_cara23idtercero" class="L"><?php echo $cara23idtercero_rs; ?></div>
                                <div class="salto1px"></div>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23idencuesta'];
                                    ?>
                                </label>
                                <label>
                                    <div id="div_cara23idencuesta">
                                        <?php
                                        echo $html_cara23idencuesta;
                                        ?>
                                    </div>
                                </label>
                                <div class="salto1px"></div>
                            </div>

                            <div class="GrupoCampos520">
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23consec'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <div id="div_cara23consec">
                                        <?php
                                        echo $html_cara23consec;
                                        ?>
                                    </div>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $ETI['cara23id'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo html_oculto('cara23id', $_REQUEST['cara23id'], formato_numero($_REQUEST['cara23id']));
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23idtipo'];
                                    ?>
                                </label>
                                <label class="Label160">
                                    <div id="div_cara23idtipo">
                                        <?php
                                        echo $html_cara23idtipo;
                                        ?>
                                    </div>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $ETI['cara23estado'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <div id="div_cara23estado">
                                        <?php
                                        echo $html_cara23estado;
                                        ?>
                                    </div>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label60">
                                    <?php
                                    echo $ETI['cara23fecha'];
                                    ?>
                                </label>
                                <div class="Campo220">
                                    <?php
                                    //echo html_FechaEnNumero('cara23fecha', $_REQUEST['cara23fecha']);
                                    echo html_oculto('cara23fecha', $_REQUEST['cara23fecha'], formato_FechaLargaDesdeNumero($_REQUEST['cara23fecha']));
                                    ?>
                                </div>
                                <div class="salto1px"></div>
                            </div>
                            <?php
                            $sPrevTitulo='<hr />
<b>';
                            $sPrevTitulo2='<b>';
                            $sSufTitulo='</b>';
                            $_REQUEST['bocultaDatosEncuesta']=1;
                            ?>
                            <div class="salto1px"></div>
                            <?php
                            echo $sPrevTitulo.$ETI['msg_encuesta'].$sSufTitulo;
                            ?>
                            <input id="bocultaDatosEncuesta" name="bocultaDatosEncuesta" type="hidden" value="<?php echo $_REQUEST['bocultaDatosEncuesta']; ?>" />
                            <div class="ir_derecha" style="width:62px;">
                                <!--
                                <label class="Label30">
                                <input id="btexcelDatosEncuesta" name="btexcelDatosEncuesta" type="button" value="Exportar" class="btMiniExcel" onclick="imprimeDatosEncuesta();" title="Exportar"/>
                                </label>
                                -->
                                <label class="Label30">
                                    <input id="btexpandeDatosEncuesta" name="btexpandeDatosEncuesta" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel('DatosEncuesta','block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['bocultaDatosEncuesta']==0){echo 'none'; }else{echo 'block';} ?>;"/>
                                </label>
                                <label class="Label30">
                                    <input id="btrecogeDatosEncuesta" name="btrecogeDatosEncuesta" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel('DatosEncuesta','none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['bocultaDatosEncuesta']==0){echo 'block'; }else{echo 'none';} ?>;"/>
                                </label>
                            </div>
                            <div class="salto1px"></div>
                            <div id="div_pDatosEncuesta" style="display:<?php if ($_REQUEST['bocultaDatosEncuesta']==0){echo 'block'; }else{echo 'none';} ?>;">
                                <div class="GrupoCampos450">
                                    <label class="TituloGrupo">
                                        <?php
                                        echo $ETI['cara01ubicamatricula'];
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara01idperaca'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara01idperaca;
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara01idzona'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara01idzona;
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara01idcead'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara01idcead;
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['core16idescuela'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_core16idescuela;
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['core16idprograma'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_core16idprograma;
                                        ?>
                                    </label>
                                </div>
                                <div class="GrupoCampos520">
                                    <label class="TituloGrupo">
                                        <?php
                                        echo $ETI['cara01datospersonales'];
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara01agnos'];
                                        ?>
                                    </label>
                                    <label class="Label60"><div id="div_cara01agnos">
                                            <?php
                                            echo html_oculto('cara01agnos', $_REQUEST['cara01agnos']);
                                            ?>
                                        </div></label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['unad11telefono'];
                                        ?>
                                    </label>
                                    <label class="Label200"><div id="div_unad11telefono">
                                            <?php
                                            echo html_oculto('unad11telefono', $_REQUEST['unad11telefono']);
                                            ?>
                                        </div></label>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['unad11correo'];
                                        ?>
                                    </label>
                                    <label class="Label320"><div id="div_unad11correo">
                                            <?php
                                            echo html_oculto('unad11correo', $_REQUEST['unad11correo']);
                                            ?>
                                        </div></label>
                                </div>
                            </div>
                            <div class="salto1px"></div>
                            <label class="Label130">
                                <?php
                                echo $ETI['cara01completa'];
                                ?>
                            </label>
                            <label class="Label90">
                                <?php
                                $et_cara01completa='&nbsp;';
                                $et_cara01fechaencuesta='&nbsp;';
                                if ($_REQUEST['paso']>0){
                                    $et_cara01completa=$ETI['msg_abierto'];
                                    if ($_REQUEST['cara01completa']=='S'){
                                        $et_cara01completa=$ETI['msg_cerrado'];
                                        $et_cara01fechaencuesta=fecha_desdenumero($_REQUEST['cara01fechaencuesta']);
                                    }
                                }
                                echo html_oculto('cara01completa', $_REQUEST['cara01completa'], $et_cara01completa);
                                ?>
                            </label>
                            <label class="Label160">
                                <?php
                                echo $ETI['cara01fechaencuesta'];
                                ?>
                            </label>
                            <div class="Campo220">
                                <?php
                                echo html_oculto('cara01fechaencuesta', $_REQUEST['cara01fechaencuesta'], $et_cara01fechaencuesta);
                                ?>
                            </div>
                            <?php
                            if ($_REQUEST['paso']>0){
                                ?>
                                <label class="Label130">
                                    <input id="cmdIrEncuesta" name="cmdIrEncuesta" type="button" class="BotonAzul" onclick="irencuesta();" value="Ver Encuesta" />
                                </label>
                                <?php
                            }
                            ?>
                            <div class="salto1px"></div>
                            <label class="Label220">
                                <?php
                                echo $ETI['cara01tipocaracterizacion'];
                                ?>
                            </label>
                            <label>
                                <?php
                                echo $html_cara01tipocaracterizacion;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label220">
                                <?php
                                echo $ETI['cara01idperiodoacompana'];
                                ?>
                            </label>
                            <label class="Label130">
                                <?php
                                echo $html_cara01idperiodoacompana;
                                ?>
                            </label>
                            <label class="Label200">
                                <?php
                                echo $ETI['cara01fechacierreacom'];
                                ?>
                            </label>
                            <label class="Label220">
                                <?php
                                echo $html_cara01fechacierreacom;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <?php
                            if ($bInducciones){
                                ?>
                                <div class="salto1px"></div>
                                <?php
                                echo $sPrevTitulo.$ETI['msg_inducciones'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <label class="Label160">
                                    <?php
                                    echo $ETI['cara23asisteinduccion'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $html_cara23asisteinduccion;
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $ETI['cara23asisteinmersioncv'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23asisteinmersioncv;
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23asisteinduccion" name="cara23asisteinduccion" type="hidden" value="<?php echo $_REQUEST['cara23asisteinduccion']; ?>"/>
                                <input id="cara23asisteinmersioncv" name="cara23asisteinmersioncv" type="hidden" value="<?php echo $_REQUEST['cara23asisteinmersioncv']; ?>"/>
                                <?php
                            }
                            ?>
                            <div class="salto1px"></div>
                            <hr />
                            <label class="Label200">
                                <?php
                                echo $ETI['cara01idcursocatedra'];
                                ?>
                            </label>
                            <label class="Label350">
                                <?php
                                echo $html_cara01idcursocatedra;
                                ?>
                            </label>
                            <?php
                            if ($iTipoSeg==3){
                                ?>
                                <label class="Label220">
                                    <?php
                                    echo $ETI['cara23catedra_aprueba'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $html_cara23catedra_aprueba;
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23catedra_aprueba" name="cara23catedra_aprueba" type="hidden" value="<?php echo $_REQUEST['cara23catedra_aprueba']; ?>"/>
                                <?php
                            }
                            ?>
                            <div class="salto1px"></div>
                            <hr />
                            <?php
                            if ($bPanelCatedra){
                                ?>
                                <div class="ir_derecha">
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23catedra_criterio'];
                                        ?>
                                    </label>
                                    <label class="Label130"><div id="div_cara23catedra_criterio">
                                            <?php
                                            echo html_oculto('cara23catedra_criterio', $_REQUEST['cara23catedra_criterio'], $ariesgo[$_REQUEST['cara23catedra_criterio']]);
                                            ?>
                                        </div></label>
                                </div>
                                <?php
                                echo $sPrevTitulo2.$ETI['msg_catedra'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <?php
                                if ($bCatedra1){
                                    ?>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23catedra_skype'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara23catedra_skype;
                                        ?>
                                    </label>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23catedra_bler1'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara23catedra_bler1;
                                        ?>
                                    </label>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23catedra_bler2'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara23catedra_bler2;
                                        ?>
                                    </label>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23catedra_webconf'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara23catedra_webconf;
                                        ?>
                                    </label>
                                    <?php
                                }else{
                                    ?>
                                    <input id="cara23catedra_skype" name="cara23catedra_skype" type="hidden" value="<?php echo $_REQUEST['cara23catedra_skype']; ?>"/>
                                    <input id="cara23catedra_bler1" name="cara23catedra_bler1" type="hidden" value="<?php echo $_REQUEST['cara23catedra_bler1']; ?>"/>
                                    <input id="cara23catedra_bler2" name="cara23catedra_bler2" type="hidden" value="<?php echo $_REQUEST['cara23catedra_bler2']; ?>"/>
                                    <input id="cara23catedra_webconf" name="cara23catedra_webconf" type="hidden" value="<?php echo $_REQUEST['cara23catedra_webconf']; ?>"/>
                                    <?php
                                }
                                if ($sHist['cara23catedra_avance']!=''){
                                    echo '<label class="Label200">&nbsp;</label>'.$sHist['cara23catedra_avance'];
                                }
                                ?>
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23catedra_avance'];
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $html_cara23catedra_avance;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                if ($sHist['cara23catedra_acciones']!=''){
                                    echo '<label class="Label200">&nbsp;</label>'.$sHist['cara23catedra_acciones'];
                                }
                                ?>
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23catedra_acciones'];
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $html_cara23catedra_acciones;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23catedra_resultados'];
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $html_cara23catedra_resultados;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                if ($bCatedra3){
                                    ?>
                                    <label class="txtAreaS">
                                        <?php
                                        echo $ETI['cara23catedra_segprev'];
                                        ?>
                                        <textarea id="cara23catedra_segprev" name="cara23catedra_segprev" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara23catedra_segprev']; ?>" disabled="disabled"><?php echo $_REQUEST['cara23catedra_segprev']; ?></textarea>
                                    </label>
                                    <?php
                                }else{
                                    ?>
                                    <input id="cara23catedra_segprev" name="cara23catedra_segprev" type="hidden" value="<?php echo $_REQUEST['cara23catedra_segprev']; ?>"/>
                                    <?php
                                }
                            }else{
                                //No hay panel de catedra... momento final.
                                ?>
                                <?php
                                echo $sPrevTitulo2.$ETI['msg_catedra'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <input id="cara23catedra_criterio" name="cara23catedra_criterio" type="hidden" value="<?php echo $_REQUEST['cara23catedra_criterio']; ?>"/>
                                <input id="cara23catedra_skype" name="cara23catedra_skype" type="hidden" value="<?php echo $_REQUEST['cara23catedra_skype']; ?>"/>
                                <input id="cara23catedra_bler1" name="cara23catedra_bler1" type="hidden" value="<?php echo $_REQUEST['cara23catedra_bler1']; ?>"/>
                                <input id="cara23catedra_bler2" name="cara23catedra_bler2" type="hidden" value="<?php echo $_REQUEST['cara23catedra_bler2']; ?>"/>
                                <input id="cara23catedra_webconf" name="cara23catedra_webconf" type="hidden" value="<?php echo $_REQUEST['cara23catedra_webconf']; ?>"/>
                                <input id="cara23catedra_avance" name="cara23catedra_avance" type="hidden" value="<?php echo $_REQUEST['cara23catedra_avance']; ?>"/>
                                <input id="cara23catedra_acciones" name="cara23catedra_acciones" type="hidden" value="<?php echo $_REQUEST['cara23catedra_acciones']; ?>"/>
                                <input id="cara23catedra_resultados" name="cara23catedra_resultados" type="hidden" value="<?php echo $_REQUEST['cara23catedra_resultados']; ?>"/>
                                <input id="cara23catedra_segprev" name="cara23catedra_segprev" type="hidden" value="<?php echo $_REQUEST['cara23catedra_segprev']; ?>"/>
                                <?php
                            }
                            ?>

                            <div class="salto1px"></div>
                            <hr />
                            <div class="ir_derecha">
                                <label class="Label130">
                                    <?php
                                    echo $ETI['msg_nivelriesgo'];
                                    ?>
                                </label>
                                <label class="Label130"><div id="div_cara23cursos_criterio">
                                        <?php
                                        echo html_oculto('cara23cursos_criterio', $_REQUEST['cara23cursos_criterio'], $ariesgo[$_REQUEST['cara23cursos_criterio']]);
                                        ?>
                                    </div></label>
                            </div>
                            <?php
                            echo $sPrevTitulo2.$ETI['msg_academico'].$sSufTitulo;
                            echo '&nbsp;&nbsp;'.$ETI['cara01idperaca'].' ';
                            echo $html_bperacamat;
                            ?>
                            <div class="salto1px"></div>
                            <div id="div_f2451detalle">
                                <?php
                                echo $sTabla2451;
                                ?>
                            </div>
                            <div class="salto1px"></div>
                            <?php
                            if ($bCursos1){
                                ?>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23cursos_total'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo html_oculto('cara23cursos_total', $_REQUEST['cara23cursos_total']);
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $ETI['cara23cursos_siningre'];
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo html_oculto('cara23cursos_siningre', $_REQUEST['cara23cursos_siningre']);
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23cursos_porcing'];
                                    ?>
                                </label>
                                <label class="Label90">
                                    <?php
                                    echo html_oculto('cara23cursos_porcing', $_REQUEST['cara23cursos_porcing'], formato_numero($_REQUEST['cara23cursos_porcing']).' %');
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label300">
                                    <?php
                                    if ($iTipoSeg==3){
                                        echo $ETI['cara23cursos_menor200_fin'];
                                    }else{
                                        echo $ETI['cara23cursos_menor200'];
                                    }
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo html_oculto('cara23cursos_menor200', $_REQUEST['cara23cursos_menor200']);
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23cursos_porcperdida'];
                                    ?>
                                </label>
                                <label class="Label90">
                                    <?php
                                    echo html_oculto('cara23cursos_porcperdida', $_REQUEST['cara23cursos_porcperdida'], formato_numero($_REQUEST['cara23cursos_porcperdida']).' %');
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23cursos_criterio" name="cara23cursos_criterio" type="hidden" value="<?php echo $_REQUEST['cara23cursos_criterio']; ?>"/>
                                <input id="cara23cursos_total" name="cara23cursos_total" type="hidden" value="<?php echo $_REQUEST['cara23cursos_total']; ?>"/>
                                <input id="cara23cursos_siningre" name="cara23cursos_siningre" type="hidden" value="<?php echo $_REQUEST['cara23cursos_siningre']; ?>"/>
                                <input id="cara23cursos_porcing" name="cara23cursos_porcing" type="hidden" value="<?php echo $_REQUEST['cara23cursos_porcing']; ?>"/>
                                <input id="cara23cursos_menor200" name="cara23cursos_menor200" type="hidden" value="<?php echo $_REQUEST['cara23cursos_menor200']; ?>"/>
                                <input id="cara23cursos_porcperdida" name="cara23cursos_porcperdida" type="hidden" value="<?php echo $_REQUEST['cara23cursos_porcperdida']; ?>"/>
                                <?php
                            }
                            if ($bCursoAcciones){
                                ?>
                                <div class="salto1px"></div>
                                <hr />
                                <?php
                                echo $sPrevTitulo2.$ETI['msg_accionesinterv'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac1'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac1;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac2'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac2;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac3'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac3;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac4'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac4;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac5'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac5;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac6'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac6;
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <label class="Label700">
                                    <?php
                                    echo $ETI['cara23cursos_ac7'];
                                    ?>
                                </label>
                                <label class="Label30">
                                    <?php
                                    echo $html_cara23cursos_ac7;
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23cursos_ac1" name="cara23cursos_ac1" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac1']; ?>"/>
                                <input id="cara23cursos_ac2" name="cara23cursos_ac2" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac2']; ?>"/>
                                <input id="cara23cursos_ac3" name="cara23cursos_ac3" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac3']; ?>"/>
                                <input id="cara23cursos_ac4" name="cara23cursos_ac4" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac4']; ?>"/>
                                <input id="cara23cursos_ac5" name="cara23cursos_ac5" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac5']; ?>"/>
                                <input id="cara23cursos_ac6" name="cara23cursos_ac6" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac6']; ?>"/>
                                <input id="cara23cursos_ac7" name="cara23cursos_ac7" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac7']; ?>"/>
                                <?php
                            }
                            ?>
                            <input id="cara23cursos_ac8" name="cara23cursos_ac8" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac8']; ?>"/>
                            <input id="cara23cursos_ac9" name="cara23cursos_ac9" type="hidden" value="<?php echo $_REQUEST['cara23cursos_ac9']; ?>"/>
                            <input id="cara23cursos_otros" name="cara23cursos_otros" type="hidden" value="<?php echo $_REQUEST['cara23cursos_otros']; ?>"/>
                            <input id="cara23cursos_accionlider" name="cara23cursos_accionlider" type="hidden" value="<?php echo $_REQUEST['cara23cursos_accionlider']; ?>"/>
                            <?php
                            if ($bAlertasIniciales){
                                ?>
                                <div class="salto1px"></div>
                                <hr />
                                <div class="ir_derecha">
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['msg_nivelriesgo'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara23aler_criterio;
                                        ?>
                                    </label>
                                </div>
                                <?php
                                echo $sPrevTitulo2.$ETI['msg_alertas'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <label class="TituloGrupo">
                                    <?php
                                    echo $ETI['cara23aler_sociodem'];
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                echo $sHist['cara23aler_sociodem'];
                                echo $sRiesgoSD;
                                ?>
                                <label class="txtAreaS">
                                    <textarea id="cara23aler_sociodem" name="cara23aler_sociodem" placeholder="<?php echo $ETI['msg_acciones'].$ETI['cara23aler_sociodem']; ?>"><?php echo $_REQUEST['cara23aler_sociodem']; ?></textarea>
                                </label>
                                <div class="salto1px"></div>
                                <label class="TituloGrupo">
                                    <?php
                                    echo $ETI['cara23aler_psico'];
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                echo $sRiesgoPsico;
                                ?>
                                <label class="txtAreaS">
                                    <textarea id="cara23aler_psico" name="cara23aler_psico" placeholder="<?php echo $ETI['msg_acciones'].$ETI['cara23aler_psico']; ?>"><?php echo $_REQUEST['cara23aler_psico']; ?></textarea>
                                </label>
                                <div class="salto1px"></div>
                                <label class="TituloGrupo">
                                    <?php
                                    echo $ETI['cara23aler_academ'];
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                echo $sRiesgoAcademico;
                                ?>
                                <label class="txtAreaS">
                                    <textarea id="cara23aler_academ" name="cara23aler_academ" placeholder="<?php echo $ETI['msg_acciones'].$ETI['cara23aler_academ']; ?>"><?php echo $_REQUEST['cara23aler_academ']; ?></textarea>
                                </label>
                                <div class="salto1px"></div>
                                <label class="TituloGrupo">
                                    <?php
                                    echo $ETI['cara23aler_econom'];
                                    ?>
                                </label>
                                <div class="salto1px"></div>
                                <?php
                                echo $sRiesgoEconomico;
                                ?>
                                <div class="salto1px"></div>
                                <label class="txtAreaS">
                                    <textarea id="cara23aler_econom" name="cara23aler_econom" placeholder="<?php echo $ETI['msg_acciones'].$ETI['cara23aler_econom']; ?>"><?php echo $_REQUEST['cara23aler_econom']; ?></textarea>
                                </label>
                                <label class="txtAreaS">
                                    <?php
                                    echo $ETI['cara23aler_externo'];
                                    ?>
                                    <textarea id="cara23aler_externo" name="cara23aler_externo" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara23aler_externo']; ?>"><?php echo $_REQUEST['cara23aler_externo']; ?></textarea>
                                </label>
                                <label class="txtAreaS">
                                    <?php
                                    echo $ETI['cara23aler_interno'];
                                    ?>
                                    <textarea id="cara23aler_interno" name="cara23aler_interno" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara23aler_interno']; ?>"><?php echo $_REQUEST['cara23aler_interno']; ?></textarea>
                                </label>
                                <?php
                                if (false){
                                    ?>
                                    <label class="txtAreaS">
                                        <?php
                                        echo $ETI['cara23aler_nivel'];
                                        ?>
                                        <textarea id="cara23aler_nivel" name="cara23aler_nivel" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara23aler_nivel']; ?>"><?php echo $_REQUEST['cara23aler_nivel']; ?></textarea>
                                    </label>
                                    <?php
                                }else{
                                    ?>
                                    <input id="cara23aler_nivel" name="cara23aler_nivel" type="hidden" value="<?php echo $_REQUEST['cara23aler_nivel']; ?>"/>
                                    <?php
                                }
                                ?>

                                <div class="salto1px"></div>
                                <hr />
                                <div class="ir_derecha">
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['msg_nivelriesgo'];
                                        ?>
                                    </label>
                                    <label>
                                        <div id="div_cara23comp_criterio">
                                            <?php
                                            echo $html_cara23comp_criterio;
                                            ?>
                                        </div>
                                    </label>
                                </div>
                                <?php
                                echo $sPrevTitulo2.$ETI['msg_competencias'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23comp_digital'];
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $html_cara23comp_digital;
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23comp_cuanti'];
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $html_cara23comp_cuanti;
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23comp_lectora'];
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $html_cara23comp_lectora;
                                    ?>
                                </label>
                                <label class="Label90">
                                    <?php
                                    echo $ETI['cara23comp_ingles'];
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $html_cara23comp_ingles;
                                    ?>
                                </label>

                                <div class="salto1px"></div>
                                <?php
                                echo $sPrevTitulo.$ETI['msg_nivelacion'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23nivela_digital'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23nivela_digital;
                                    ?>
                                </label>
                                <label class="Label220">
                                    <?php
                                    echo $ETI['cara23nivela_cuanti'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23nivela_cuanti;
                                    ?>
                                </label>
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23nivela_lecto'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23nivela_lecto;
                                    ?>
                                </label>
                                <label class="Label160">
                                    <?php
                                    echo $ETI['cara23nivela_ingles'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23nivela_ingles;
                                    ?>
                                </label>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23nivela_exito'];
                                    ?>
                                </label>
                                <label class="Label60">
                                    <?php
                                    echo $html_cara23nivela_exito;
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23aler_criterio" name="cara23aler_criterio" type="hidden" value="<?php echo $_REQUEST['cara23aler_criterio']; ?>"/>
                                <input id="cara23aler_sociodem" name="cara23aler_sociodem" type="hidden" value="<?php echo $_REQUEST['cara23aler_sociodem']; ?>"/>
                                <input id="cara23aler_psico" name="cara23aler_psico" type="hidden" value="<?php echo $_REQUEST['cara23aler_psico']; ?>"/>
                                <input id="cara23aler_academ" name="cara23aler_academ" type="hidden" value="<?php echo $_REQUEST['cara23aler_academ']; ?>"/>
                                <input id="cara23aler_econom" name="cara23aler_econom" type="hidden" value="<?php echo $_REQUEST['cara23aler_econom']; ?>"/>
                                <input id="cara23aler_externo" name="cara23aler_externo" type="hidden" value="<?php echo $_REQUEST['cara23aler_externo']; ?>"/>
                                <input id="cara23aler_interno" name="cara23aler_interno" type="hidden" value="<?php echo $_REQUEST['cara23aler_interno']; ?>"/>
                                <input id="cara23aler_nivel" name="cara23aler_nivel" type="hidden" value="<?php echo $_REQUEST['cara23aler_nivel']; ?>"/>
                                <input id="cara23comp_criterio" name="cara23comp_criterio" type="hidden" value="<?php echo $_REQUEST['cara23comp_criterio']; ?>"/>
                                <input id="cara23comp_digital" name="cara23comp_digital" type="hidden" value="<?php echo $_REQUEST['cara23comp_digital']; ?>"/>
                                <input id="cara23comp_cuanti" name="cara23comp_cuanti" type="hidden" value="<?php echo $_REQUEST['cara23comp_cuanti']; ?>"/>
                                <input id="cara23comp_lectora" name="cara23comp_lectora" type="hidden" value="<?php echo $_REQUEST['cara23comp_lectora']; ?>"/>
                                <input id="cara23comp_ingles" name="cara23comp_ingles" type="hidden" value="<?php echo $_REQUEST['cara23comp_ingles']; ?>"/>
                                <input id="cara23nivela_digital" name="cara23nivela_digital" type="hidden" value="<?php echo $_REQUEST['cara23nivela_digital']; ?>"/>
                                <input id="cara23nivela_cuanti" name="cara23nivela_cuanti" type="hidden" value="<?php echo $_REQUEST['cara23nivela_cuanti']; ?>"/>
                                <input id="cara23nivela_lecto" name="cara23nivela_lecto" type="hidden" value="<?php echo $_REQUEST['cara23nivela_lecto']; ?>"/>
                                <input id="cara23nivela_ingles" name="cara23nivela_ingles" type="hidden" value="<?php echo $_REQUEST['cara23nivela_ingles']; ?>"/>
                                <input id="cara23nivela_exito" name="cara23nivela_exito" type="hidden" value="<?php echo $_REQUEST['cara23nivela_exito']; ?>"/>
                                <?php
                            }
                            ?>
                            <div class="salto1px"></div>
                            <hr />
                            <div class="ir_derecha">
                                <label class="Label200">
                                    <?php
                                    echo $ETI['cara23factorriesgo'];
                                    ?>
                                </label>
                                <label>
                                    <div id="div_cara23factorriesgo">
                                        <?php
                                        echo $html_cara23factorriesgo;
                                        ?>
                                    </div>
                                </label>
                            </div>
                            <?php
                            echo $sPrevTitulo2.$ETI['msg_contacto'].$sSufTitulo;
                            ?>
                            <div class="salto1px"></div>
                            <label>
                                <?php
                                echo $ETI['cara23contacto_efectivo'];
                                ?>
                            </label>
                            <label class="Label60">
                                <?php
                                echo $html_cara23contacto_efectivo;
                                ?>
                            </label>
                            <label class="Label200">
                                <?php
                                echo $ETI['cara23contacto_forma'];
                                ?>
                            </label>
                            <label>
                                <?php
                                echo $html_cara23contacto_forma;
                                ?>
                            </label>
                            <label class="txtAreaS">
                                <?php
                                echo $ETI['cara23contacto_observa'];
                                ?>
                                <textarea id="cara23contacto_observa" name="cara23contacto_observa" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara23contacto_observa']; ?>"><?php echo $_REQUEST['cara23contacto_observa']; ?></textarea>
                            </label>
                            <label class="Label130">
                                <?php
                                echo $ETI['cara23aplaza'];
                                ?>
                            </label>
                            <label class="Label60">
                                <?php
                                echo $html_cara23aplaza;
                                ?>
                            </label>
                            <label class="Label130">
                                <?php
                                echo $ETI['cara23seretira'];
                                ?>
                            </label>
                            <label class="Label60">
                                <?php
                                echo $html_cara23seretira;
                                ?>
                            </label>
                            <?php
                            if ($iTipoSeg==3){
                                ?>
                                <label class="Label130">
                                    <?php
                                    echo $ETI['cara23permanece'];
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $html_cara23permanece;
                                    ?>
                                </label>
                                <?php
                            }else{
                                ?>
                                <input id="cara23permanece" name="cara23permanece" type="hidden" value="<?php echo $_REQUEST['cara23permanece']; ?>"/>
                                <?php
                            }
                            if (true){
                                ?>
                                <div class="salto1px"></div>
                                <label>
                                    <?php
                                    echo $ETI['cara01factorprincipaldesc'];
                                    ?>
                                </label>
                                <label>
                                    <?php
                                    echo $html_cara01factorprincipaldesc;
                                    ?>
                                </label>
                                <?php
                                if ($iTipoSeg==3){
                                    ?>
                                    <div class="salto1px"></div>
                                    <label>
                                        <?php
                                        echo $ETI['cara01factorprincpermanencia'];
                                        ?>
                                    </label>
                                    <label>
                                        <?php
                                        echo $html_cara01factorprincpermanencia;
                                        ?>
                                    </label>
                                    <?php
                                }else{
                                    ?>
                                    <input id="cara01factorprincpermanencia" name="cara01factorprincpermanencia" type="hidden" value="<?php echo $_REQUEST['cara01factorprincpermanencia']; ?>"/>
                                    <?php
                                }
                            }else{
                                ?>
                                <input id="cara01factorprincipaldesc" name="cara01factorprincipaldesc" type="hidden" value="<?php echo $_REQUEST['cara01factorprincipaldesc']; ?>"/>
                                <input id="cara01factorprincpermanencia" name="cara01factorprincpermanencia" type="hidden" value="<?php echo $_REQUEST['cara01factorprincpermanencia']; ?>"/>
                                <?php
                            }
                            if ($bSegZonal){
                                ?>
                                <div class="salto1px"></div>
                                <?php
                                echo $sPrevTitulo.$ETI['msg_zonal'].$sSufTitulo;
                                ?>
                                <div class="salto1px"></div>
                                <div class="GrupoCampos520">
                                    <label class="txtAreaS">
                                        <?php
                                        echo $ETI['cara23zonal_retro'];
                                        ?>
                                        <?php
                                        echo html_oculto('cara23zonal_retro', $_REQUEST['cara23zonal_retro']);
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                </div>

                                <div class="GrupoCampos450">
                                    <label class="TituloGrupo">
                                        <?php
                                        echo $ETI['cara23zonal_idlider'];
                                        ?>
                                    </label>
                                    <div class="salto1px"></div>
                                    <input id="cara23zonal_idlider" name="cara23zonal_idlider" type="hidden" value="<?php echo $_REQUEST['cara23zonal_idlider']; ?>"/>
                                    <div id="div_cara23zonal_idlider_llaves">
                                        <?php
                                        $bOculto=true;
                                        echo html_DivTerceroV2('cara23zonal_idlider', $_REQUEST['cara23zonal_idlider_td'], $_REQUEST['cara23zonal_idlider_doc'], $bOculto, 0, $ETI['ing_doc']);
                                        ?>
                                    </div>
                                    <div class="salto1px"></div>
                                    <div id="div_cara23zonal_idlider" class="L"><?php echo $cara23zonal_idlider_rs; ?></div>
                                    <div class="salto1px"></div>
                                    <label class="Label130">
                                        <?php
                                        echo $ETI['cara23zonal_fecha'];
                                        ?>
                                    </label>
                                    <div class="Campo220">
                                        <?php
                                        $et_cara23zonal_fecha='&nbsp;';
                                        if ((int)$_REQUEST['cara23zonal_fecha']!=0){
                                            $et_cara23zonal_fecha=fecha_desdenumero($_REQUEST['cara23zonal_fecha']);
                                        }
                                        echo html_oculto('cara23zonal_fecha', $_REQUEST['cara23zonal_fecha'], $et_cara23zonal_fecha);
                                        //echo html_FechaEnNumero('cara23zonal_fecha', $_REQUEST['cara23zonal_fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
                                        ?>
                                    </div>
                                    <div class="salto1px"></div>
                                </div>
                                <?php
                            }else{
                                ?>
                                <input id="cara23zonal_retro" name="cara23zonal_retro" type="hidden" value="<?php echo $_REQUEST['cara23zonal_retro']; ?>"/>
                                <input id="cara23zonal_fecha" name="cara23zonal_fecha" type="hidden" value="<?php echo $_REQUEST['cara23zonal_fecha']; ?>"/>
                                <input id="cara23zonal_idlider" name="cara23zonal_idlider" type="hidden" value="<?php echo $_REQUEST['cara23zonal_idlider']; ?>"/>
                                <input id="cara23zonal_idlider_td" name="cara23zonal_idlider_td" type="hidden" value="<?php echo $_REQUEST['cara23zonal_idlider_td']; ?>"/>
                                <input id="cara23zonal_idlider_doc" name="cara23zonal_idlider_doc" type="hidden" value="<?php echo $_REQUEST['cara23zonal_idlider_doc']; ?>"/>
                                <?php
                            }
                            ?>
                            <?php
                            if (false){
                                //Ejemplo de boton de ayuda
                                //echo html_BotonAyuda('NombreCampo');
                                //echo html_DivAyudaLocal('NombreCampo');
                            }
                            if ($bconexpande){
                            //Este es el cierre del div_p2323
                            ?>
                            <div class="salto1px"></div>
                        </div>
                    <?php
                    }
                    //Mostrar el contenido de la tabla
                    ?>
                    </div><!-- CIERRA EL DIV areatrabajo -->
                </div><!-- CIERRA EL DIV areaform -->
                <div class="areaform">
                    <div class="areatitulo">
                        <?php
                        echo '<h3>'.$ETI['bloque1'].'</h3>';
                        ?>
                    </div>
                    <div class="areatrabajo">
                        <div class="ir_derecha">
                            <label class="Label90">
                                Documento
                            </label>
                            <label>
                                <input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2301()" autocomplete="off"/>
                            </label>
                            <label class="Label90">
                                Nombre
                            </label>
                            <label class="Label250">
                                <input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2301()" autocomplete="off"/>
                            </label>
                            <label class="Label60">
                                Listar
                            </label>
                            <label class="Label200">
                                <?php
                                echo $html_blistar;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                Periodo
                            </label>
                            <label class="Label130">
                                <?php
                                echo $html_bperaca;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                Escuela
                            </label>
                            <label class="Label600">
                                <?php
                                echo $html_bescuela;
                                ?>
                            </label>
                            <label class="Label60">
                                Tipo
                            </label>
                            <label>
                                <?php
                                echo $html_btipocara;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                Programa
                            </label>
                            <label class="Label130">
                                <div id="div_bprograma">
                                    <?php
                                    echo $html_bprograma;
                                    ?>
                                </div>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                Zona
                            </label>
                            <label class="Label350">
                                <?php
                                echo $html_bzona;
                                ?>
                            </label>
                            <label class="Label90">
                                Poblaci&oacute;n
                            </label>
                            <label class="Label130">
                                <?php
                                echo $html_bpoblacion;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                CEAD
                            </label>
                            <label class="Label130">
                                <div id="div_bcead">
                                    <?php
                                    echo $html_bcead;
                                    ?>
                                </div>
                            </label>
                            <div class="salto1px"></div>
                            <label class="Label90">
                                Convenio
                            </label>
                            <label class="Label130">
                                <?php
                                echo $html_bconvenio;
                                ?>
                            </label>
                            <div class="salto1px"></div>
                        </div>
                        <div class="salto1px"></div>
                        <?php
                        echo ' '.$csv_separa;
                        ?>
                        <div id="div_f2323detalle">
                            <?php
                            echo $sTabla2323;
                            ?>
                        </div>
                    </div><!-- /div_areatrabajo -->
                </div><!-- /DIV_areaform -->
            </div><!-- /DIV_Sector1 -->


            <div id="div_sector2" style="display:none">
                <div class="titulos">
                    <div class="titulosD">
                        <input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
                        <input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
                    </div>
                    <div class="titulosI">
                        <?php
                        echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
                        ?>
                    </div>
                </div>
                <div id="cargaForm">
                    <div id="area">
                    </div><!-- /div_area -->
                </div><!-- /DIV_cargaForm -->
            </div><!-- /DIV_Sector2 -->


            <div id="div_sector93" style="display:none">
                <?php
                $objForma=new clsHtmlForma($iPiel);
                $objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
                $objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
                echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
                echo $objForma->htmlInicioMarco();
                ?>
                <label class="Label160">
                    <?php
                    echo $ETI['msg_cara23consec'];
                    ?>
                </label>
                <label class="Label90">
                    <?php
                    echo '<b>'.$_REQUEST['cara23consec'].'</b>';
                    ?>
                </label>
                <div class="salto1px"></div>
                <label class="Label160">
                    <?php
                    echo $ETI['msg_cara23consec_nuevo'];
                    // onchange="RevisaConsec()"
                    ?>
                </label>
                <label class="Label90">
                    <input id="cara23consec_nuevo" name="cara23consec_nuevo" type="text" value="<?php echo $_REQUEST['cara23consec_nuevo']; ?>" class="cuatro"/>
                </label>
                <div class="salto1px"></div>
                <label class="Label160">&nbsp;</label>
                <?php
                echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
                echo $objForma->htmlFinMarco();
                ?>
            </div><!-- /DIV_Sector93 -->


            <div id="div_sector95" style="display:none">
                <div id="cargaForm">
                    <div id="div_95cuerpo"></div>
                </div><!-- /DIV_cargaForm -->
            </div><!-- /DIV_Sector95 -->


            <div id="div_sector96" style="display:none">
                <input id="div96v1" name="div96v1" type="hidden" value="" />
                <input id="div96v2" name="div96v2" type="hidden" value="" />
                <input id="div96v3" name="div96v3" type="hidden" value="" />
                <input id="div96campo" name="div96campo" type="hidden" value="" />
                <input id="div96llave" name="div96llave" type="hidden" value="" />
                <input id="titulo_2323" name="titulo_2323" type="hidden" value="<?php echo $ETI['titulo_2323']; ?>" />
                <div class="titulos">
                    <div class="titulosD">
                        <input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
                    </div>
                    <div class="titulosI" id="div_96titulo"></div>
                </div>
                <div id="cargaForm">
                    <div id="div_96cuerpo"></div>
                </div><!-- /DIV_cargaForm -->
            </div><!-- /DIV_Sector96 -->


            <div id="div_sector97" style="display:none">
                <div class="titulos">
                    <div class="titulosD">
                        <input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
                        <input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
                    </div>
                    <div class="titulosI" id="div_97titulo">
                        <?php
                        echo '<h2>'.$ETI['titulo_2323'].'</h2>';
                        ?>
                    </div>
                </div>
                <div id="cargaForm">
                    <div id="area">
                        <div id="div_97params"></div>
                        <div class="salto1px"></div>
                        <div id="div_97tabla"></div>
                    </div><!-- /div_area -->
                </div><!-- /DIV_cargaForm -->
            </div><!-- /DIV_Sector97 -->


            <div id="div_sector98" style="display:none">
                <div class="titulos">
                    <div class="titulosD">
                        <input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
                    </div>
                    <div class="titulosI">
                        <?php
                        echo '<h2>'.$ETI['titulo_2323'].'</h2>';
                        ?>
                    </div>
                </div>
                <div id="cargaForm">
                    <div id="area">
                        <div class="MarquesinaMedia">
                            <?php
                            echo $ETI['msg_espere'];
                            ?>
                        </div><!-- /Termina la marquesina -->
                    </div><!-- /div_area -->
                </div><!-- /DIV_cargaForm -->
            </div><!-- /DIV_Sector98 -->


            <?php
            if ($sDebug!=''){
                $iSegFin=microtime(true);
                $iSegundos=$iSegFin-$iSegIni;
                echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
            }
            ?>
            <input id="scampobusca" name="scampobusca" type="hidden" value=""/>
            <input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
            <input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
            <input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
        </form>
    </div><!-- /DIV_interna -->
    <div class="flotante">
        <?php
        if ($_REQUEST['cara23estado']!=7){
            ?>
            <input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
            <?php
        }
        ?>
    </div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
?>

    <script language="javascript">
        <!--
        <?php
        if ($iSector!=1){
            echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
        }
        if ($bMueveScroll){
            echo 'setTimeout(function(){retornacontrol();}, 2);
';
        }
        ?>
        -->
    </script>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
    <link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
    <script language="javascript" src="ac_2323.js"></script>
    <script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>