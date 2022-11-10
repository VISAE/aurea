<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
--- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
--- Modelo Versión 2.25.10b jueves, 14 de enero de 2021
*/
/** Archivo saiuchat.php.
* Modulo 3028 saiu28mesaayuda.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date domingo, 19 de julio de 2020
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
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libsai.php';
require $APP->rutacomun.'libtiempo.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=3028;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
require $mensajes_todas;
require $mensajes_3028;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$sAnchoExpandeContrae=' style="width:62px;"';
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
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saiumesaayuda.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	//$sDebug=$sDebug.$sDebugP;
	if ($bDevuelve){
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
$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3000)){$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';}
$mensajes_3029='lg/lg_3029_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3029)){$mensajes_3029='lg/lg_3029_es.php';}
$mensajes_3030='lg/lg_3030_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3030)){$mensajes_3030='lg/lg_3030_es.php';}
$mensajes_3039='lg/lg_3039_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3039)){$mensajes_3039='lg/lg_3039_es.php';}
require $mensajes_3000;
require $mensajes_3029;
require $mensajes_3030;
require $mensajes_3039;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3028 saiu28mesaayuda
require 'lib3028.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
// -- 3029 Anexos Mesa de ayuda
require 'lib3029.php';
// -- 3030 Anotaciones
require 'lib3030.php';
// -- 3039 Cambios de estado
require 'lib3039.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28tiposolicitud');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28temasolicitud');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28idcentro');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28coddepto');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28codciudad');
$xajax->register(XAJAX_FUNCTION,'f3028_Combosaiu28idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3028_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3028_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3028_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3028_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu29idarchivo');
$xajax->register(XAJAX_FUNCTION,'f3029_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3029_Traer');
$xajax->register(XAJAX_FUNCTION,'f3029_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3029_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3029_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3030_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3030_Traer');
$xajax->register(XAJAX_FUNCTION,'f3030_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3030_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3030_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3039_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3039_Traer');
$xajax->register(XAJAX_FUNCTION,'f3039_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3039_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3039_PintarLlaves');
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
if (isset($_REQUEST['paginaf3028'])==0){$_REQUEST['paginaf3028']=1;}
if (isset($_REQUEST['lppf3028'])==0){$_REQUEST['lppf3028']=20;}
if (isset($_REQUEST['boculta3028'])==0){$_REQUEST['boculta3028']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
if (isset($_REQUEST['paginaf3029'])==0){$_REQUEST['paginaf3029']=1;}
if (isset($_REQUEST['lppf3029'])==0){$_REQUEST['lppf3029']=20;}
if (isset($_REQUEST['boculta3029'])==0){$_REQUEST['boculta3029']=0;}
if (isset($_REQUEST['paginaf3030'])==0){$_REQUEST['paginaf3030']=1;}
if (isset($_REQUEST['lppf3030'])==0){$_REQUEST['lppf3030']=20;}
if (isset($_REQUEST['boculta3030'])==0){$_REQUEST['boculta3030']=0;}
if (isset($_REQUEST['paginaf3039'])==0){$_REQUEST['paginaf3039']=1;}
if (isset($_REQUEST['lppf3039'])==0){$_REQUEST['lppf3039']=20;}
if (isset($_REQUEST['boculta3039'])==0){$_REQUEST['boculta3039']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu28agno'])==0){$_REQUEST['saiu28agno']='';}
if (isset($_REQUEST['saiu28mes'])==0){$_REQUEST['saiu28mes']='';}
if (isset($_REQUEST['saiu28tiporadicado'])==0){$_REQUEST['saiu28tiporadicado']=1;}
if (isset($_REQUEST['saiu28consec'])==0){$_REQUEST['saiu28consec']='';}
if (isset($_REQUEST['saiu28consec_nuevo'])==0){$_REQUEST['saiu28consec_nuevo']='';}
if (isset($_REQUEST['saiu28id'])==0){$_REQUEST['saiu28id']='';}
if (isset($_REQUEST['saiu28dia'])==0){$_REQUEST['saiu28dia']=fecha_dia();}
if (isset($_REQUEST['saiu28hora'])==0){$_REQUEST['saiu28hora']=fecha_hora();}
if (isset($_REQUEST['saiu28minuto'])==0){$_REQUEST['saiu28minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu28estado'])==0){$_REQUEST['saiu28estado']=0;}
if (isset($_REQUEST['saiu28idsolicitante'])==0){$_REQUEST['saiu28idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idsolicitante_td'])==0){$_REQUEST['saiu28idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idsolicitante_doc'])==0){$_REQUEST['saiu28idsolicitante_doc']='';}
if (isset($_REQUEST['saiu28tipointeresado'])==0){$_REQUEST['saiu28tipointeresado']='';}
if (isset($_REQUEST['saiu28clasesolicitud'])==0){$_REQUEST['saiu28clasesolicitud']='';}
if (isset($_REQUEST['saiu28tiposolicitud'])==0){$_REQUEST['saiu28tiposolicitud']='';}
if (isset($_REQUEST['saiu28temasolicitud'])==0){$_REQUEST['saiu28temasolicitud']='';}
if (isset($_REQUEST['saiu28idzona'])==0){$_REQUEST['saiu28idzona']='';}
if (isset($_REQUEST['saiu28idcentro'])==0){$_REQUEST['saiu28idcentro']='';}
if (isset($_REQUEST['saiu28codpais'])==0){$_REQUEST['saiu28codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu28coddepto'])==0){$_REQUEST['saiu28coddepto']='';}
if (isset($_REQUEST['saiu28codciudad'])==0){$_REQUEST['saiu28codciudad']='';}
if (isset($_REQUEST['saiu28idescuela'])==0){$_REQUEST['saiu28idescuela']='';}
if (isset($_REQUEST['saiu28idprograma'])==0){$_REQUEST['saiu28idprograma']='';}
if (isset($_REQUEST['saiu28idperiodo'])==0){$_REQUEST['saiu28idperiodo']='';}
if (isset($_REQUEST['saiu28idpqrs'])==0){$_REQUEST['saiu28idpqrs']=0;}
if (isset($_REQUEST['saiu28detalle'])==0){$_REQUEST['saiu28detalle']='';}
if (isset($_REQUEST['saiu28horafin'])==0){$_REQUEST['saiu28horafin']='';}
if (isset($_REQUEST['saiu28minutofin'])==0){$_REQUEST['saiu28minutofin']='';}
if (isset($_REQUEST['saiu28idresponsable'])==0){$_REQUEST['saiu28idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idresponsable_td'])==0){$_REQUEST['saiu28idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idresponsable_doc'])==0){$_REQUEST['saiu28idresponsable_doc']='';}
if (isset($_REQUEST['saiu28tiemprespdias'])==0){$_REQUEST['saiu28tiemprespdias']='';}
if (isset($_REQUEST['saiu28tiempresphoras'])==0){$_REQUEST['saiu28tiempresphoras']='';}
if (isset($_REQUEST['saiu28tiemprespminutos'])==0){$_REQUEST['saiu28tiemprespminutos']='';}
if (isset($_REQUEST['saiu28solucion'])==0){$_REQUEST['saiu28solucion']='';}
if (isset($_REQUEST['saiu28numetapas'])==0){$_REQUEST['saiu28numetapas']=0;}
if (isset($_REQUEST['saiu28idunidadresp1'])==0){$_REQUEST['saiu28idunidadresp1']=0;}
if (isset($_REQUEST['saiu28idequiporesp1'])==0){$_REQUEST['saiu28idequiporesp1']=0;}
if (isset($_REQUEST['saiu28idliderrespon1'])==0){$_REQUEST['saiu28idliderrespon1']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idliderrespon1_td'])==0){$_REQUEST['saiu28idliderrespon1_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idliderrespon1_doc'])==0){$_REQUEST['saiu28idliderrespon1_doc']='';}
if (isset($_REQUEST['saiu28tiemprespdias1'])==0){$_REQUEST['saiu28tiemprespdias1']=0;}
if (isset($_REQUEST['saiu28tiempresphoras1'])==0){$_REQUEST['saiu28tiempresphoras1']=0;}
if (isset($_REQUEST['saiu28centrotarea1'])==0){$_REQUEST['saiu28centrotarea1']=0;}
if (isset($_REQUEST['saiu28tiempousado1'])==0){$_REQUEST['saiu28tiempousado1']=0;}
if (isset($_REQUEST['saiu28tiempocalusado1'])==0){$_REQUEST['saiu28tiempocalusado1']=0;}
if (isset($_REQUEST['saiu28idunidadresp2'])==0){$_REQUEST['saiu28idunidadresp2']=0;}
if (isset($_REQUEST['saiu28idequiporesp2'])==0){$_REQUEST['saiu28idequiporesp2']=0;}
if (isset($_REQUEST['saiu28idliderrespon2'])==0){$_REQUEST['saiu28idliderrespon2']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idliderrespon2_td'])==0){$_REQUEST['saiu28idliderrespon2_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idliderrespon2_doc'])==0){$_REQUEST['saiu28idliderrespon2_doc']='';}
if (isset($_REQUEST['saiu28tiemprespdias2'])==0){$_REQUEST['saiu28tiemprespdias2']=0;}
if (isset($_REQUEST['saiu28tiempresphoras2'])==0){$_REQUEST['saiu28tiempresphoras2']=0;}
if (isset($_REQUEST['saiu28centrotarea2'])==0){$_REQUEST['saiu28centrotarea2']=0;}
if (isset($_REQUEST['saiu28tiempousado2'])==0){$_REQUEST['saiu28tiempousado2']=0;}
if (isset($_REQUEST['saiu28tiempocalusado2'])==0){$_REQUEST['saiu28tiempocalusado2']=0;}
if (isset($_REQUEST['saiu28idunidadresp3'])==0){$_REQUEST['saiu28idunidadresp3']=0;}
if (isset($_REQUEST['saiu28idequiporesp3'])==0){$_REQUEST['saiu28idequiporesp3']=0;}
if (isset($_REQUEST['saiu28idliderrespon3'])==0){$_REQUEST['saiu28idliderrespon3']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idliderrespon3_td'])==0){$_REQUEST['saiu28idliderrespon3_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idliderrespon3_doc'])==0){$_REQUEST['saiu28idliderrespon3_doc']='';}
if (isset($_REQUEST['saiu28tiemprespdias3'])==0){$_REQUEST['saiu28tiemprespdias3']=0;}
if (isset($_REQUEST['saiu28tiempresphoras3'])==0){$_REQUEST['saiu28tiempresphoras3']=0;}
if (isset($_REQUEST['saiu28centrotarea3'])==0){$_REQUEST['saiu28centrotarea3']=0;}
if (isset($_REQUEST['saiu28tiempousado3'])==0){$_REQUEST['saiu28tiempousado3']=0;}
if (isset($_REQUEST['saiu28tiempocalusado3'])==0){$_REQUEST['saiu28tiempocalusado3']=0;}
if (isset($_REQUEST['saiu28idsupervisor'])==0){$_REQUEST['saiu28idsupervisor']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu28idsupervisor_td'])==0){$_REQUEST['saiu28idsupervisor_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu28idsupervisor_doc'])==0){$_REQUEST['saiu28idsupervisor_doc']='';}
if (isset($_REQUEST['saiu28moduloasociado'])==0){$_REQUEST['saiu28moduloasociado']=0;}
if (isset($_REQUEST['saiu28etapaactual'])==0){$_REQUEST['saiu28etapaactual']=0;}
if ((int)$_REQUEST['paso']>0){
	//Anexos Mesa de ayuda
	if (isset($_REQUEST['saiu29idanexo'])==0){$_REQUEST['saiu29idanexo']='';}
	if (isset($_REQUEST['saiu29consec'])==0){$_REQUEST['saiu29consec']='';}
	if (isset($_REQUEST['saiu29id'])==0){$_REQUEST['saiu29id']='';}
	if (isset($_REQUEST['saiu29idorigen'])==0){$_REQUEST['saiu29idorigen']=0;}
	if (isset($_REQUEST['saiu29idarchivo'])==0){$_REQUEST['saiu29idarchivo']=0;}
	if (isset($_REQUEST['saiu29detalle'])==0){$_REQUEST['saiu29detalle']='';}
	//Anotaciones
	if (isset($_REQUEST['saiu30consec'])==0){$_REQUEST['saiu30consec']='';}
	if (isset($_REQUEST['saiu30id'])==0){$_REQUEST['saiu30id']='';}
	if (isset($_REQUEST['saiu30visiblealinteresado'])==0){$_REQUEST['saiu30visiblealinteresado']='';}
	if (isset($_REQUEST['saiu30anotacion'])==0){$_REQUEST['saiu30anotacion']='';}
	if (isset($_REQUEST['saiu30idusuario'])==0){$_REQUEST['saiu30idusuario']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu30idusuario_td'])==0){$_REQUEST['saiu30idusuario_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['saiu30idusuario_doc'])==0){$_REQUEST['saiu30idusuario_doc']='';}
	if (isset($_REQUEST['saiu30fecha'])==0){$_REQUEST['saiu30fecha']='';}//{fecha_hoy();}
	if (isset($_REQUEST['saiu30hora'])==0){$_REQUEST['saiu30hora']='';}
	if (isset($_REQUEST['saiu30minuto'])==0){$_REQUEST['saiu30minuto']='';}
	//Cambios de estado
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=fecha_agno();}
if (isset($_REQUEST['blistar2'])==0){$_REQUEST['blistar2']=1;}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['btipo'])==0){$_REQUEST['btipo']='';}
if (isset($_REQUEST['bestado'])==0){$_REQUEST['bestado']='';}
if (isset($_REQUEST['bfechaini'])==0){$_REQUEST['bfechaini']='';}
if (isset($_REQUEST['bfechafin'])==0){$_REQUEST['bfechafin']='';}
if (isset($_REQUEST['bdetalle'])==0){$_REQUEST['bdetalle']='';}
if (isset($_REQUEST['vdtipointeresado'])==0){
	$sVr='';
	$sSQL='SELECT bita07id FROM bita07tiposolicitante WHERE bita07predet="S" ORDER BY bita07orden, bita07nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['bita07id'];
		}
	$_REQUEST['vdtipointeresado']=$sVr;
	}
//Variable vdidchat
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu28idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idsolicitante_doc']='';
	$_REQUEST['saiu28idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idresponsable_doc']='';
	$_REQUEST['saiu28idliderrespon1_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon1_doc']='';
	$_REQUEST['saiu28idliderrespon2_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon2_doc']='';
	$_REQUEST['saiu28idliderrespon3_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon3_doc']='';
	$_REQUEST['saiu28idsupervisor_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idsupervisor_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu28agno='.$_REQUEST['saiu28agno'].' AND saiu28mes='.$_REQUEST['saiu28mes'].' AND saiu28tiporadicado='.$_REQUEST['saiu28tiporadicado'].' AND saiu28consec='.$_REQUEST['saiu28consec'].'';
		}else{
		$sSQLcondi='saiu28id='.$_REQUEST['saiu28id'].'';
		}
	$sSQL='SELECT * FROM saiu28mesaayuda_'.$_REQUEST['blistar'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu28agno']=$fila['saiu28agno'];
		$_REQUEST['saiu28mes']=$fila['saiu28mes'];
		$_REQUEST['saiu28tiporadicado']=$fila['saiu28tiporadicado'];
		$_REQUEST['saiu28consec']=$fila['saiu28consec'];
		$_REQUEST['saiu28id']=$fila['saiu28id'];
		$_REQUEST['saiu28dia']=$fila['saiu28dia'];
		$_REQUEST['saiu28hora']=$fila['saiu28hora'];
		$_REQUEST['saiu28minuto']=$fila['saiu28minuto'];
		$_REQUEST['saiu28estado']=$fila['saiu28estado'];
		$_REQUEST['saiu28idsolicitante']=$fila['saiu28idsolicitante'];
		$_REQUEST['saiu28tipointeresado']=$fila['saiu28tipointeresado'];
		$_REQUEST['saiu28clasesolicitud']=$fila['saiu28clasesolicitud'];
		$_REQUEST['saiu28tiposolicitud']=$fila['saiu28tiposolicitud'];
		$_REQUEST['saiu28temasolicitud']=$fila['saiu28temasolicitud'];
		$_REQUEST['saiu28idzona']=$fila['saiu28idzona'];
		$_REQUEST['saiu28idcentro']=$fila['saiu28idcentro'];
		$_REQUEST['saiu28codpais']=$fila['saiu28codpais'];
		$_REQUEST['saiu28coddepto']=$fila['saiu28coddepto'];
		$_REQUEST['saiu28codciudad']=$fila['saiu28codciudad'];
		$_REQUEST['saiu28idescuela']=$fila['saiu28idescuela'];
		$_REQUEST['saiu28idprograma']=$fila['saiu28idprograma'];
		$_REQUEST['saiu28idperiodo']=$fila['saiu28idperiodo'];
		$_REQUEST['saiu28idpqrs']=$fila['saiu28idpqrs'];
		$_REQUEST['saiu28detalle']=$fila['saiu28detalle'];
		$_REQUEST['saiu28horafin']=$fila['saiu28horafin'];
		$_REQUEST['saiu28minutofin']=$fila['saiu28minutofin'];
		$_REQUEST['saiu28idresponsable']=$fila['saiu28idresponsable'];
		$_REQUEST['saiu28tiemprespdias']=$fila['saiu28tiemprespdias'];
		$_REQUEST['saiu28tiempresphoras']=$fila['saiu28tiempresphoras'];
		$_REQUEST['saiu28tiemprespminutos']=$fila['saiu28tiemprespminutos'];
		$_REQUEST['saiu28solucion']=$fila['saiu28solucion'];
		$_REQUEST['saiu28numetapas']=$fila['saiu28numetapas'];
		$_REQUEST['saiu28idunidadresp1']=$fila['saiu28idunidadresp1'];
		$_REQUEST['saiu28idequiporesp1']=$fila['saiu28idequiporesp1'];
		$_REQUEST['saiu28idliderrespon1']=$fila['saiu28idliderrespon1'];
		$_REQUEST['saiu28tiemprespdias1']=$fila['saiu28tiemprespdias1'];
		$_REQUEST['saiu28tiempresphoras1']=$fila['saiu28tiempresphoras1'];
		$_REQUEST['saiu28centrotarea1']=$fila['saiu28centrotarea1'];
		$_REQUEST['saiu28tiempousado1']=$fila['saiu28tiempousado1'];
		$_REQUEST['saiu28tiempocalusado1']=$fila['saiu28tiempocalusado1'];
		$_REQUEST['saiu28idunidadresp2']=$fila['saiu28idunidadresp2'];
		$_REQUEST['saiu28idequiporesp2']=$fila['saiu28idequiporesp2'];
		$_REQUEST['saiu28idliderrespon2']=$fila['saiu28idliderrespon2'];
		$_REQUEST['saiu28tiemprespdias2']=$fila['saiu28tiemprespdias2'];
		$_REQUEST['saiu28tiempresphoras2']=$fila['saiu28tiempresphoras2'];
		$_REQUEST['saiu28centrotarea2']=$fila['saiu28centrotarea2'];
		$_REQUEST['saiu28tiempousado2']=$fila['saiu28tiempousado2'];
		$_REQUEST['saiu28tiempocalusado2']=$fila['saiu28tiempocalusado2'];
		$_REQUEST['saiu28idunidadresp3']=$fila['saiu28idunidadresp3'];
		$_REQUEST['saiu28idequiporesp3']=$fila['saiu28idequiporesp3'];
		$_REQUEST['saiu28idliderrespon3']=$fila['saiu28idliderrespon3'];
		$_REQUEST['saiu28tiemprespdias3']=$fila['saiu28tiemprespdias3'];
		$_REQUEST['saiu28tiempresphoras3']=$fila['saiu28tiempresphoras3'];
		$_REQUEST['saiu28centrotarea3']=$fila['saiu28centrotarea3'];
		$_REQUEST['saiu28tiempousado3']=$fila['saiu28tiempousado3'];
		$_REQUEST['saiu28tiempocalusado3']=$fila['saiu28tiempocalusado3'];
		$_REQUEST['saiu28idsupervisor']=$fila['saiu28idsupervisor'];
		$_REQUEST['saiu28moduloasociado']=$fila['saiu28moduloasociado'];
		$_REQUEST['saiu28etapaactual']=$fila['saiu28etapaactual'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3028']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu28estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu28estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu28estado']=9;
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
		$_REQUEST['saiu28estado']=7;
		}else{
		$sSQL='UPDATE saiu28mesaayuda_'.$_REQUEST['saiu28agno'].' SET saiu28estado=2 WHERE saiu28id='.$_REQUEST['saiu28id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu28id'], 'Abre Registro de conversacion chat', $objDB);
		$_REQUEST['saiu28estado']=2;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3028_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if ($sErrorCerrando!=''){
			$iTipoError=0;
			$sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
			}
		if ($bCerrando){
			$sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
			}
		}
	}
if ($bCerrando){
	//acciones del cerrado
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu28consec_nuevo']=numeros_validar($_REQUEST['saiu28consec_nuevo']);
	if ($_REQUEST['saiu28consec_nuevo']==''){$sError=$ERR['saiu28consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu28id FROM saiu28mesaayuda_'.$_REQUEST['saiu28agno'].' WHERE saiu28consec='.$_REQUEST['saiu28consec_nuevo'].' AND saiu28tiporadicado='.$_REQUEST['saiu28tiporadicado'].' AND saiu28mes='.$_REQUEST['saiu28mes'].' AND saiu28agno='.$_REQUEST['saiu28agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu28consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu28mesaayuda_'.$_REQUEST['saiu28agno'].' SET saiu28consec='.$_REQUEST['saiu28consec_nuevo'].' WHERE saiu28id='.$_REQUEST['saiu28id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu28consec'].' a '.$_REQUEST['saiu28consec_nuevo'].'';
		$_REQUEST['saiu28consec']=$_REQUEST['saiu28consec_nuevo'];
		$_REQUEST['saiu28consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu28id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3028_db_Eliminar($_REQUEST['saiu28agno'], $_REQUEST['saiu28id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu28agno']=fecha_agno();
	$_REQUEST['saiu28mes']=fecha_mes();
	$_REQUEST['saiu28tiporadicado']=1;
	$_REQUEST['saiu28consec']='';
	$_REQUEST['saiu28consec_nuevo']='';
	$_REQUEST['saiu28id']='';
	$_REQUEST['saiu28dia']=fecha_dia();
	$_REQUEST['saiu28hora']='';
	$_REQUEST['saiu28minuto']='';
	$_REQUEST['saiu28estado']=0;
	$_REQUEST['saiu28idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu28idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idsolicitante_doc']='';
	$_REQUEST['saiu28tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu28clasesolicitud']='';
	$_REQUEST['saiu28tiposolicitud']='';
	$_REQUEST['saiu28temasolicitud']='';
	$_REQUEST['saiu28idzona']='';
	$_REQUEST['saiu28idcentro']='';
	$_REQUEST['saiu28codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu28coddepto']='';
	$_REQUEST['saiu28codciudad']='';
	$_REQUEST['saiu28idescuela']='';
	$_REQUEST['saiu28idprograma']='';
	$_REQUEST['saiu28idperiodo']='';
	$_REQUEST['saiu28idpqrs']=0;
	$_REQUEST['saiu28detalle']='';
	$_REQUEST['saiu28horafin']='';
	$_REQUEST['saiu28minutofin']='';
	$_REQUEST['saiu28idresponsable']=0;//$idTercero
	$_REQUEST['saiu28idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idresponsable_doc']='';
	$_REQUEST['saiu28tiemprespdias']='';
	$_REQUEST['saiu28tiempresphoras']='';
	$_REQUEST['saiu28tiemprespminutos']='';
	$_REQUEST['saiu28solucion']=0;
	$_REQUEST['saiu28numetapas']=0;
	$_REQUEST['saiu28idunidadresp1']=0;
	$_REQUEST['saiu28idequiporesp1']=0;
	$_REQUEST['saiu28idliderrespon1']=0;
	$_REQUEST['saiu28idliderrespon1_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon1_doc']='';
	$_REQUEST['saiu28tiemprespdias1']=0;
	$_REQUEST['saiu28tiempresphoras1']=0;
	$_REQUEST['saiu28centrotarea1']=0;
	$_REQUEST['saiu28tiempousado1']=0;
	$_REQUEST['saiu28tiempocalusado1']=0;
	$_REQUEST['saiu28idunidadresp2']=0;
	$_REQUEST['saiu28idequiporesp2']=0;
	$_REQUEST['saiu28idliderrespon2']=0;
	$_REQUEST['saiu28idliderrespon2_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon2_doc']='';
	$_REQUEST['saiu28tiemprespdias2']=0;
	$_REQUEST['saiu28tiempresphoras2']=0;
	$_REQUEST['saiu28centrotarea2']=0;
	$_REQUEST['saiu28tiempousado2']=0;
	$_REQUEST['saiu28tiempocalusado2']=0;
	$_REQUEST['saiu28idunidadresp3']=0;
	$_REQUEST['saiu28idequiporesp3']=0;
	$_REQUEST['saiu28idliderrespon3']=0;
	$_REQUEST['saiu28idliderrespon3_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idliderrespon3_doc']='';
	$_REQUEST['saiu28tiemprespdias3']=0;
	$_REQUEST['saiu28tiempresphoras3']=0;
	$_REQUEST['saiu28centrotarea3']=0;
	$_REQUEST['saiu28tiempousado3']=0;
	$_REQUEST['saiu28tiempocalusado3']=0;
	$_REQUEST['saiu28idsupervisor']=0;
	$_REQUEST['saiu28idsupervisor_td']=$APP->tipo_doc;
	$_REQUEST['saiu28idsupervisor_doc']='';
	$_REQUEST['saiu28moduloasociado']=0;
	$_REQUEST['saiu28etapaactual']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['saiu29idsolicitud']='';
	$_REQUEST['saiu29idanexo']='';
	$_REQUEST['saiu29consec']='';
	$_REQUEST['saiu29id']='';
	$_REQUEST['saiu29idorigen']=0;
	$_REQUEST['saiu29idarchivo']=0;
	$_REQUEST['saiu29detalle']='';
	$_REQUEST['saiu30idsolicitud']='';
	$_REQUEST['saiu30consec']='';
	$_REQUEST['saiu30id']='';
	$_REQUEST['saiu30visiblealinteresado']=0;
	$_REQUEST['saiu30anotacion']='';
	$_REQUEST['saiu30idusuario']=$idTercero;
	$_REQUEST['saiu30idusuario_td']=$APP->tipo_doc;
	$_REQUEST['saiu30idusuario_doc']='';
	$_REQUEST['saiu30fecha']=fecha_DiaMod();
	$_REQUEST['saiu30hora']=fecha_hora();
	$_REQUEST['saiu30minuto']=fecha_minuto();
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$iAgno=fecha_agno();
$sTabla='saiu29anexos_'.$iAgno;
if (!$objDB->bexistetabla($sTabla)){
	list($sErrorT, $sDebugT)=f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
	}
if ($_REQUEST['paso']!=0){
	$iAgnoIni=$iAgno;
	$iAgnoFin=$iAgno;
	}else{
	$iAgnoIni=2020;
	$iAgnoFin=fecha_agno();
	}
//DATOS PARA COMPLETAR EL FORMULARIO
$idEtapa=$_REQUEST['saiu28etapaactual'];
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
//list($saiu28estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu28estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$saiu28estado_nombre=$asaiu11[$_REQUEST['saiu28estado']];
$html_saiu28estado=html_oculto('saiu28estado', $_REQUEST['saiu28estado'], $saiu28estado_nombre);
list($saiu28idsolicitante_rs, $_REQUEST['saiu28idsolicitante'], $_REQUEST['saiu28idsolicitante_td'], $_REQUEST['saiu28idsolicitante_doc'])=html_tercero($_REQUEST['saiu28idsolicitante_td'], $_REQUEST['saiu28idsolicitante_doc'], $_REQUEST['saiu28idsolicitante'], 0, $objDB);
$objCombos->nuevo('saiu28tipointeresado', $_REQUEST['saiu28tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
$html_saiu28tipointeresado=$objCombos->html($sSQL, $objDB);
/*
$objCombos->nuevo('saiu28tiposolicitud', $_REQUEST['saiu28tiposolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu28temasolicitud();';
$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
FROM saiu02tiposol AS TB, saiu01claseser AS T1 
WHERE TB.saiu02id>0 AND TB.saiu02ordensoporte<9 AND TB.saiu02clasesol=T1.saiu01id 
ORDER BY TB.saiu02ordensoporte, TB.saiu02titulo';
$html_saiu28tiposolicitud=$objCombos->html($sSQL, $objDB);
*/
$html_saiu28tiposolicitud=f3028_HTMLComboV2_saiu28tiposolicitud($objDB, $objCombos, $_REQUEST['saiu28tiposolicitud']);
/*
$objCombos->nuevo('saiu28temasolicitud', $_REQUEST['saiu28temasolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol WHERE saiu03id>0 AND saiu03ordensoporte<9 ORDER BY saiu03ordensoporte, saiu03titulo';
$html_saiu28temasolicitud=$objCombos->html($sSQL, $objDB);
*/
$html_saiu28temasolicitud=f3028_HTMLComboV2_saiu28temasolicitud($objDB, $objCombos, $_REQUEST['saiu28temasolicitud'], $_REQUEST['saiu28tiposolicitud']);
$objCombos->nuevo('saiu28idzona', $_REQUEST['saiu28idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu28idcentro();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_saiu28idzona=$objCombos->html($sSQL, $objDB);
$html_saiu28idcentro=f3028_HTMLComboV2_saiu28idcentro($objDB, $objCombos, $_REQUEST['saiu28idcentro'], $_REQUEST['saiu28idzona']);
$objCombos->nuevo('saiu28codpais', $_REQUEST['saiu28codpais'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu28coddepto();';
$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_saiu28codpais=$objCombos->html($sSQL, $objDB);
$html_saiu28coddepto=f3028_HTMLComboV2_saiu28coddepto($objDB, $objCombos, $_REQUEST['saiu28coddepto'], $_REQUEST['saiu28codpais']);
$html_saiu28codciudad=f3028_HTMLComboV2_saiu28codciudad($objDB, $objCombos, $_REQUEST['saiu28codciudad'], $_REQUEST['saiu28coddepto']);
$objCombos->nuevo('saiu28idescuela', $_REQUEST['saiu28idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu28idprograma();';
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 ORDER BY core12tieneestudiantes DESC, core12nombre';
$html_saiu28idescuela=$objCombos->html($sSQL, $objDB);
$html_saiu28idprograma=f3028_HTMLComboV2_saiu28idprograma($objDB, $objCombos, $_REQUEST['saiu28idprograma'], $_REQUEST['saiu28idescuela']);
$objCombos->nuevo('saiu28idperiodo', $_REQUEST['saiu28idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL=f146_ConsultaCombo('exte02id>0');
$html_saiu28idperiodo=$objCombos->html($sSQL, $objDB);
list($saiu28idresponsable_rs, $_REQUEST['saiu28idresponsable'], $_REQUEST['saiu28idresponsable_td'], $_REQUEST['saiu28idresponsable_doc'])=html_tercero($_REQUEST['saiu28idresponsable_td'], $_REQUEST['saiu28idresponsable_doc'], $_REQUEST['saiu28idresponsable'], 0, $objDB);
$objCombos->nuevo('saiu28solucion', $_REQUEST['saiu28solucion'], true, $asaiu28solucion[0], 0);
//$objCombos->addItem(1, $ETI['si']);
$objCombos->addArreglo($asaiu28solucion, $isaiu28solucion);
$html_saiu28solucion=$objCombos->html('', $objDB);
$saiu28numetapas_nombre=$_REQUEST['saiu28numetapas'];
//list($saiu28numetapas_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu28numetapas'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu28numetapas=html_oculto('saiu28numetapas', $_REQUEST['saiu28numetapas'], $saiu28numetapas_nombre);
if ($idEtapa>0){
	list($saiu28idunidadresp1_nombre, $sErrorDet)=tabla_campoxid('unae26unidadesfun','unae26nombre','unae26id',$_REQUEST['saiu28idunidadresp1'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idunidadresp1=html_oculto('saiu28idunidadresp1', $_REQUEST['saiu28idunidadresp1'], $saiu28idunidadresp1_nombre);
	list($saiu28idequiporesp1_nombre, $sErrorDet)=tabla_campoxid('bita27equipotrabajo','bita27nombre','bita27id',$_REQUEST['saiu28idequiporesp1'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idequiporesp1=html_oculto('saiu28idequiporesp1', $_REQUEST['saiu28idequiporesp1'], $saiu28idequiporesp1_nombre);
	list($saiu28idliderrespon1_rs, $_REQUEST['saiu28idliderrespon1'], $_REQUEST['saiu28idliderrespon1_td'], $_REQUEST['saiu28idliderrespon1_doc'])=html_tercero($_REQUEST['saiu28idliderrespon1_td'], $_REQUEST['saiu28idliderrespon1_doc'], $_REQUEST['saiu28idliderrespon1'], 0, $objDB);
	list($saiu28centrotarea1_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['saiu28centrotarea1'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28centrotarea1=html_oculto('saiu28centrotarea1', $_REQUEST['saiu28centrotarea1'], $saiu28centrotarea1_nombre);
}
if ($idEtapa>1){
	list($saiu28idunidadresp2_nombre, $sErrorDet)=tabla_campoxid('unae26unidadesfun','unae26nombre','unae26id',$_REQUEST['saiu28idunidadresp2'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idunidadresp2=html_oculto('saiu28idunidadresp2', $_REQUEST['saiu28idunidadresp2'], $saiu28idunidadresp2_nombre);
	list($saiu28idequiporesp2_nombre, $sErrorDet)=tabla_campoxid('bita27equipotrabajo','bita27nombre','bita27id',$_REQUEST['saiu28idequiporesp2'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idequiporesp2=html_oculto('saiu28idequiporesp2', $_REQUEST['saiu28idequiporesp2'], $saiu28idequiporesp2_nombre);
	list($saiu28idliderrespon2_rs, $_REQUEST['saiu28idliderrespon2'], $_REQUEST['saiu28idliderrespon2_td'], $_REQUEST['saiu28idliderrespon2_doc'])=html_tercero($_REQUEST['saiu28idliderrespon2_td'], $_REQUEST['saiu28idliderrespon2_doc'], $_REQUEST['saiu28idliderrespon2'], 0, $objDB);
	list($saiu28centrotarea2_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['saiu28centrotarea2'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28centrotarea2=html_oculto('saiu28centrotarea2', $_REQUEST['saiu28centrotarea2'], $saiu28centrotarea2_nombre);
}
if ($idEtapa>2){
	list($saiu28idunidadresp3_nombre, $sErrorDet)=tabla_campoxid('unae26unidadesfun','unae26nombre','unae26id',$_REQUEST['saiu28idunidadresp3'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idunidadresp3=html_oculto('saiu28idunidadresp3', $_REQUEST['saiu28idunidadresp3'], $saiu28idunidadresp3_nombre);
	list($saiu28idequiporesp3_nombre, $sErrorDet)=tabla_campoxid('bita27equipotrabajo','bita27nombre','bita27id',$_REQUEST['saiu28idequiporesp3'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28idequiporesp3=html_oculto('saiu28idequiporesp3', $_REQUEST['saiu28idequiporesp3'], $saiu28idequiporesp3_nombre);
	list($saiu28idliderrespon3_rs, $_REQUEST['saiu28idliderrespon3'], $_REQUEST['saiu28idliderrespon3_td'], $_REQUEST['saiu28idliderrespon3_doc'])=html_tercero($_REQUEST['saiu28idliderrespon3_td'], $_REQUEST['saiu28idliderrespon3_doc'], $_REQUEST['saiu28idliderrespon3'], 0, $objDB);
	list($saiu28centrotarea3_nombre, $sErrorDet)=tabla_campoxid('unad24sede','unad24nombre','unad24id',$_REQUEST['saiu28centrotarea3'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28centrotarea3=html_oculto('saiu28centrotarea3', $_REQUEST['saiu28centrotarea3'], $saiu28centrotarea3_nombre);
}
list($saiu28idsupervisor_rs, $_REQUEST['saiu28idsupervisor'], $_REQUEST['saiu28idsupervisor_td'], $_REQUEST['saiu28idsupervisor_doc'])=html_tercero($_REQUEST['saiu28idsupervisor_td'], $_REQUEST['saiu28idsupervisor_doc'], $_REQUEST['saiu28idsupervisor'], 0, $objDB);
if ($_REQUEST['saiu28moduloasociado']>0){
	$saiu28moduloasociado_nombre='{'.$_REQUEST['saiu28moduloasociado'].'}';
	//list($saiu28moduloasociado_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu28moduloasociado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28moduloasociado=html_oculto('saiu28moduloasociado', $_REQUEST['saiu28moduloasociado'], $saiu28moduloasociado_nombre);
	}
if ((int)$_REQUEST['paso']==0){
	$html_saiu28agno=f3028_HTMLComboV2_saiu28agno($objDB, $objCombos, $_REQUEST['saiu28agno']);
	$html_saiu28mes=f3028_HTMLComboV2_saiu28mes($objDB, $objCombos, $_REQUEST['saiu28mes']);
	$html_saiu28dia=html_ComboDia('saiu28dia', $_REQUEST['saiu28dia'], false);
	//$html_saiu28tiporadicado=f3028_HTMLComboV2_saiu28tiporadicado($objDB, $objCombos, $_REQUEST['saiu28tiporadicado']);
	}else{
	$saiu28agno_nombre=$_REQUEST['saiu28agno'];
	//$saiu28agno_nombre=$asaiu28agno[$_REQUEST['saiu28agno']];
	//list($saiu28agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu28agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28agno=html_oculto('saiu28agno', $_REQUEST['saiu28agno'], $saiu28agno_nombre);
	$saiu28mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu28mes']));
	//$saiu28mes_nombre=$asaiu28mes[$_REQUEST['saiu28mes']];
	//list($saiu28mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu28mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28mes=html_oculto('saiu28mes', $_REQUEST['saiu28mes'], $saiu28mes_nombre);
	$saiu28dia_nombre=$_REQUEST['saiu28dia'];
	$html_saiu28dia=html_oculto('saiu28dia', $_REQUEST['saiu28dia'], $saiu28dia_nombre);
	//list($saiu28tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu28tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_saiu28tiporadicado=html_oculto('saiu28tiporadicado', $_REQUEST['saiu28tiporadicado'], $saiu28tiporadicado_nombre);
	$html_saiu29idanexo=f3029_HTMLComboV2_saiu29idanexo($objDB, $objCombos, $_REQUEST['saiu29idanexo']);
	$objCombos->nuevo('saiu30visiblealinteresado', $_REQUEST['saiu30visiblealinteresado'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu30visiblealinteresado, $isaiu30visiblealinteresado);
	$html_saiu30visiblealinteresado=$objCombos->html('', $objDB);
	list($saiu30idusuario_rs, $_REQUEST['saiu30idusuario'], $_REQUEST['saiu30idusuario_td'], $_REQUEST['saiu30idusuario_doc'])=html_tercero($_REQUEST['saiu30idusuario_td'], $_REQUEST['saiu30idusuario_doc'], $_REQUEST['saiu30idusuario'], 0, $objDB);
	}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu28estado']>6){$bEnProceso=false;}
	}
if (true){
	}else{
	list($saiu28tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu28tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu28tiposolicitud=html_oculto('saiu28tiposolicitud', $_REQUEST['saiu28tiposolicitud'], $saiu28tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
$bConBotonAbandona=false;
$bConBotonCancela=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu28estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		$bConBotonAbandona=false;
		$bConBotonCancela=true;
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if ($_REQUEST['paso']!=0){
	$html_blistar=html_oculto('blistar', $_REQUEST['blistar']);
}else{
	$objCombos->nuevo('blistar', $_REQUEST['blistar'], false, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf3028()';
	$sSQL='SHOW TABLES LIKE "saiu28mesaayuda%"';
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 16);
		$objCombos->addItem($sAgno, $sAgno);
		}
	$html_blistar=$objCombos->html('', $objDB);
}
$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='paginarf3028()';
$objCombos->addArreglo($aListar2, $iListar2);
$html_blistar2=$objCombos->html('', $objDB);
$objCombos->nuevo('btipo', $_REQUEST['btipo'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->iAncho=600;
$objCombos->sAccion='paginarf3028()';
$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
FROM saiu03temasol AS TB, saiu02tiposol AS T2 
WHERE TB.saiu03id>0 AND TB.saiu03ordensoporte<9 AND TB.saiu03tiposol=T2.saiu02id
ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
$html_btipo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3028()';
//$sSQL='SELECT saiu11id AS id, saiu11nombre AS nombre FROM saiu11estadosol ORDER BY saiu11id';
$objCombos->addItem('0', $asaiu11[0]);
$objCombos->addArreglo($asaiu11, $isaiu11);
$html_bestado=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3028, 1, $objDB, 'paginarf3028()');
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3028;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu28estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3028'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3028'];
$aParametros[102]=$_REQUEST['lppf3028'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['blistar2'];
$aParametros[106]=$_REQUEST['btipo'];
$aParametros[107]=$_REQUEST['bdoc'];
$aParametros[108]=$_REQUEST['bestado'];
$aParametros[109]=$_REQUEST['bfechaini'];
$aParametros[110]=$_REQUEST['bfechafin'];
$aParametros[111]=$_REQUEST['bdetalle'];
list($sTabla3028, $sDebugTabla)=f3028_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000='';
$sTabla3029='';
$sTabla3030='';
$sTabla3039='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu28agno'];
$aParametros3000[3]=$_REQUEST['saiu28id'];
$aParametros3000[100]=$_REQUEST['saiu28idsolicitante'];
$aParametros3000[101]=$_REQUEST['paginaf3000'];
$aParametros3000[102]=$_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla)=f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
if ($_REQUEST['paso']!=0){
	//Anexos Mesa de ayuda
	$aParametros3029[0]=$_REQUEST['saiu28id'];
	$aParametros3029[98]=$_REQUEST['saiu28agno'];
	$aParametros3029[100]=$idTercero;
	$aParametros3029[101]=$_REQUEST['paginaf3029'];
	$aParametros3029[102]=$_REQUEST['lppf3029'];
	//$aParametros3029[103]=$_REQUEST['bnombre3029'];
	//$aParametros3029[104]=$_REQUEST['blistar3029'];
	list($sTabla3029, $sDebugTabla)=f3029_TablaDetalleV2($aParametros3029, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Anotaciones
	$aParametros3030[0]=$_REQUEST['saiu28id'];
	$aParametros3030[98]=$_REQUEST['saiu28agno'];
	$aParametros3030[100]=$idTercero;
	$aParametros3030[101]=$_REQUEST['paginaf3030'];
	$aParametros3030[102]=$_REQUEST['lppf3030'];
	//$aParametros3030[103]=$_REQUEST['bnombre3030'];
	//$aParametros3030[104]=$_REQUEST['blistar3030'];
	list($sTabla3030, $sDebugTabla)=f3030_TablaDetalleV2($aParametros3030, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Cambios de estado
	$aParametros3039[0]=$_REQUEST['saiu28id'];
	$aParametros3039[98]=$_REQUEST['saiu28agno'];
	$aParametros3039[100]=$idTercero;
	$aParametros3039[101]=$_REQUEST['paginaf3039'];
	$aParametros3039[102]=$_REQUEST['lppf3039'];
	//$aParametros3039[103]=$_REQUEST['bnombre3039'];
	//$aParametros3039[104]=$_REQUEST['blistar3039'];
	list($sTabla3039, $sDebugTabla)=f3039_TablaDetalleV2($aParametros3039, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3028']);
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
	if (window.document.frmedita.saiu28estado.value<7){
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
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		if (idcampo=='saiu28idsolicitante'){
			params[6]=3028;
			xajax_unad11_Mostrar_v2SAI(params);
			}else{
			xajax_unad11_Mostrar_v2(params);
			}
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		paginarf3000();
		}
	}
function ter_traerxidSAI(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3028;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3028;
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3028.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3028.value;
		window.document.frmlista.nombrearchivo.value='Mesa_de_ayuda';
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
		window.document.frmimpp.action='e3028.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3028.php';
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
	datos[1]=window.document.frmedita.saiu28agno.value;
	datos[2]=window.document.frmedita.saiu28mes.value;
	datos[3]=window.document.frmedita.saiu28tiporadicado.value;
	datos[4]=window.document.frmedita.saiu28consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3028_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu28agno.value=String(llave1);
	window.document.frmedita.saiu28mes.value=String(llave2);
	window.document.frmedita.saiu28tiporadicado.value=String(llave3);
	window.document.frmedita.saiu28consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3028(llave1){
	window.document.frmedita.saiu28id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu28tiposolicitud(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28temasolicitud.value;
	document.getElementById('div_saiu28tiposolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28tiposolicitud" name="saiu28tiposolicitud" type="hidden" value="" />';
	document.getElementById('div_saiu28temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28temasolicitud" name="saiu28temasolicitud" type="hidden" value="" />';
	xajax_f3028_Combosaiu28tiposolicitud(params);
	}
function carga_combo_saiu28temasolicitud(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28tiposolicitud.value;
	document.getElementById('div_saiu28temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28temasolicitud" name="saiu28temasolicitud" type="hidden" value="" />';
	xajax_f3028_Combosaiu28temasolicitud(params);
	}
function carga_combo_saiu28idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28idzona.value;
	document.getElementById('div_saiu28idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28idcentro" name="saiu28idcentro" type="hidden" value="" />';
	xajax_f3028_Combosaiu28idcentro(params);
	}
function carga_combo_saiu28coddepto(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28codpais.value;
	document.getElementById('div_saiu28coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28coddepto" name="saiu28coddepto" type="hidden" value="" />';
	xajax_f3028_Combosaiu28coddepto(params);
	}
function carga_combo_saiu28codciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28coddepto.value;
	document.getElementById('div_saiu28codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28codciudad" name="saiu28codciudad" type="hidden" value="" />';
	xajax_f3028_Combosaiu28codciudad(params);
	}
function carga_combo_saiu28idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu28idescuela.value;
	document.getElementById('div_saiu28idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu28idprograma" name="saiu28idprograma" type="hidden" value="" />';
	xajax_f3028_Combosaiu28idprograma(params);
	}
function paginarf3028(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3028.value;
	params[102]=window.document.frmedita.lppf3028.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.blistar2.value;
	params[106]=window.document.frmedita.btipo.value;
	params[107]=window.document.frmedita.bdoc.value;
	params[108]=window.document.frmedita.bestado.value;
	params[109]=window.document.frmedita.bfechaini.value;
	params[110]=window.document.frmedita.bfechafin.value;
	params[111]=window.document.frmedita.bdetalle.value;
	//document.getElementById('div_f3028detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3028" name="paginaf3028" type="hidden" value="'+params[101]+'" /><input id="lppf3028" name="lppf3028" type="hidden" value="'+params[102]+'" />';
	xajax_f3028_HtmlTabla(params);
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
	document.getElementById("saiu28agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3028_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu28idsolicitante'){
		ter_traerxid('saiu28idsolicitante', sValor);
		}
	if (sCampo=='saiu28idresponsable'){
		ter_traerxid('saiu28idresponsable', sValor);
		}
	if (sCampo=='saiu28idliderrespon1'){
		ter_traerxid('saiu28idliderrespon1', sValor);
		}
	if (sCampo=='saiu28idliderrespon2'){
		ter_traerxid('saiu28idliderrespon2', sValor);
		}
	if (sCampo=='saiu28idliderrespon3'){
		ter_traerxid('saiu28idliderrespon3', sValor);
		}
	if (sCampo=='saiu28idsupervisor'){
		ter_traerxid('saiu28idsupervisor', sValor);
		}
	if (sCampo=='saiu30idusuario'){
		ter_traerxid('saiu30idusuario', sValor);
		}
	if (sCampo=='saiu39idresponsable'){
		ter_traerxid('saiu39idresponsable', sValor);
		}
	if (sCampo=='saiu39usuario'){
		ter_traerxid('saiu39usuario', sValor);
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
	if (ref==3029){
		if (sRetorna!=''){
			window.document.frmedita.saiu29idorigen.value=window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu29idarchivo.value=sRetorna;
			verboton('beliminasaiu29idarchivo','block');
			}
		archivo_lnk(window.document.frmedita.saiu29idorigen.value, window.document.frmedita.saiu29idarchivo.value, 'div_saiu29idarchivo');
		paginarf3029();
		}
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
function abandonar(){
	if (confirm("Confirma que el solicitante abandono la llamada?")){
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}
	}
function cancelar(){
	if (confirm("Confirma que la llamada fue cancelada?")){
		expandesector(98);
		window.document.frmedita.paso.value=22;
		window.document.frmedita.submit();
		}
	}
function paginarf3000(){
	var params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3028;
	params[2]=window.document.frmedita.saiu28agno.value;
	params[3]=window.document.frmedita.saiu28id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu28idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
function limpiarresponsable(){
	document.getElementById('div_saiu28idresponsable').innerHTML='&nbsp;';
	ter_traerxid('saiu28idresponsable', 0);
	}
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js3029.js"></script>
<script language="javascript" src="jsi/js3030.js?v=1"></script>
<script language="javascript" src="jsi/js3039.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p3028.php" target="_blank">
<input id="r" name="r" type="hidden" value="3028" />
<input id="id3028" name="id3028" type="hidden" value="<?php echo $_REQUEST['saiu28id']; ?>" />
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
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="vdtipointeresado" name="vdtipointeresado" type="hidden" value="<?php echo $_REQUEST['vdtipointeresado']; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu28estado']<7){
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
		if ($_REQUEST['saiu28estado']>6){
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
if ($_REQUEST['saiu28estado']<7){
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
echo '<h2>'.$ETI['titulo_3028'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande=true;
if ($bConExpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta3028" name="boculta3028" type="hidden" value="<?php echo $_REQUEST['boculta3028']; ?>" />
<label class="Label30">
<input id="btexpande3028" name="btexpande3028" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3028,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3028']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3028" name="btrecoge3028" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3028,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3028']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3028" style="display:<?php if ($_REQUEST['boculta3028']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['msg_fecha'];
?>
</label>
<?php
if ($_REQUEST['paso']==0){
?>
<label class="Label60">
<?php
echo $html_saiu28dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu28mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu28agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu28dia.'/'.$html_saiu28mes.'/'.$html_saiu28agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu28hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu28hora">
<?php
echo html_HoraMin('saiu28hora', $_REQUEST['saiu28hora'], 'saiu28minuto', $_REQUEST['saiu28minuto']);
?>
</div>
<input id="saiu28tiporadicado" name="saiu28tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu28tiporadicado']; ?>"/>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu28consec" name="saiu28consec" type="text" value="<?php echo $_REQUEST['saiu28consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu28consec', $_REQUEST['saiu28consec'], formato_numero($_REQUEST['saiu28consec']));
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['saiu28id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu28id', $_REQUEST['saiu28id'], formato_numero($_REQUEST['saiu28id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28estado'];
?>
</label>
<label>
<div id="div_saiu28estado">
<?php
echo $html_saiu28estado;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idsolicitante" name="saiu28idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu28idsolicitante']; ?>"/>
<div id="div_saiu28idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu28idsolicitante', $_REQUEST['saiu28idsolicitante_td'], $_REQUEST['saiu28idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idsolicitante" class="L"><?php echo $saiu28idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu28tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu28tipointeresado;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu28idzona'];
?>
</label>
<label>
<?php
echo $html_saiu28idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28idcentro'];
?>
</label>
<label>
<div id="div_saiu28idcentro">
<?php
echo $html_saiu28idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28codpais'];
?>
</label>
<label>
<?php
echo $html_saiu28codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28coddepto'];
?>
</label>
<label>
<div id="div_saiu28coddepto">
<?php
echo $html_saiu28coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28codciudad'];
?>
</label>
<label>
<div id="div_saiu28codciudad">
<?php
echo $html_saiu28codciudad;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3000'];
?>
</label>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div class="salto1px"></div>
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<input id="saiu28clasesolicitud" name="saiu28clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu28clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu28tiposolicitud'];
?>
</label>
<label>
<div id="div_saiu28tiposolicitud">
<?php
echo $html_saiu28tiposolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28temasolicitud'];
?>
</label>
<label>
<div id="div_saiu28temasolicitud">
<?php
echo $html_saiu28temasolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu28idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28idprograma'];
?>
</label>
<label>
<div id="div_saiu28idprograma">
<?php
echo $html_saiu28idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu28idperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['saiu28detalle'];
?>
<textarea id="saiu28detalle" name="saiu28detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu28detalle']; ?>"><?php echo $_REQUEST['saiu28detalle']; ?></textarea>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu28solucion'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu28solucion;
?>
</label>
<div class="salto1px"></div>
<label class="Labe250">
<?php
echo $ETI['saiu28idpqrs'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu28idpqrs', $_REQUEST['saiu28idpqrs']);
?>
</label>
<?php
if ($_REQUEST['saiu28moduloasociado']!=0){
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28moduloasociado'];
?>
</label>
<label>
<div id="div_saiu28moduloasociado">
<?php
echo $html_saiu28moduloasociado;
?>
</div>
</label>
<?php
}else{
?>
<input id="saiu28moduloasociado" name="saiu28moduloasociado" type="hidden" value="<?php echo $_REQUEST['saiu28moduloasociado']; ?>"/>
<?php	
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28numetapas'];
?>
</label>
<label class="Label30"><div id="div_saiu28etapaactual">
<?php
echo html_oculto('saiu28etapaactual', $_REQUEST['saiu28etapaactual']);
?>
</div></label>
<label class="Label30">
<div id="div_saiu28numetapas">
<?php
echo $html_saiu28numetapas;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idresponsable" name="saiu28idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu28idresponsable']; ?>"/>
<div id="div_saiu28idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu28idresponsable', $_REQUEST['saiu28idresponsable_td'], $_REQUEST['saiu28idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idresponsable" class="L"><?php echo $saiu28idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu28horafin">
<?php
echo html_HoraMin('saiu28horafin', $_REQUEST['saiu28horafin'], 'saiu28minutofin', $_REQUEST['saiu28minutofin']);
?>
</div>
<?php
if ($_REQUEST['saiu28estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu28tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu28tiemprespdias'], $_REQUEST['saiu28tiempresphoras'], $_REQUEST['saiu28tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu28tiemprespdias" name="saiu28tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu28tiemprespdias']; ?>"/>
<input id="saiu28tiempresphoras" name="saiu28tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu28tiempresphoras']; ?>"/>
<input id="saiu28tiemprespminutos" name="saiu28tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu28tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<div class="salto5px"></div>
<?php
if ($bConBotonAbandona){
?>
<label class="Label130">
<input id="cmdAbandona" name="cmdAbandona" type="button" value="Abandonada" class="BotonAzul" onclick="abandonar()" title="Llamada abandonada"/>
</label>
<label class="Label30"></label>
<?php
	}
if ($bConBotonCancela){
?>
<label class="Label130">
<input id="cmdCancela" name="cmdCancela" type="button" value="Cancelada" class="BotonAzul" onclick="cancelar()" title="Llamada cancelada"/>
</label>
<label class="Label30"></label>
<?php
	}
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu28estado']<7){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="Terminar" class="BotonAzul" onclick="enviacerrar()" title="Terminar Llamada"/>
</label>
<?php
		}
	}
?>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idsupervisor'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idsupervisor" name="saiu28idsupervisor" type="hidden" value="<?php echo $_REQUEST['saiu28idsupervisor']; ?>"/>
<div id="div_saiu28idsupervisor_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu28idsupervisor', $_REQUEST['saiu28idsupervisor_td'], $_REQUEST['saiu28idsupervisor_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idsupervisor" class="L"><?php echo $saiu28idsupervisor_rs; ?></div>
<div class="salto1px"></div>
</div>

<?php
if ($idEtapa>0){
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu28idunidadresp1'];
?>
</label>
<label>
<div id="div_saiu28idunidadresp1">
<?php
echo $html_saiu28idunidadresp1;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28idequiporesp1'];
?>
</label>
<label>
<div id="div_saiu28idequiporesp1">
<?php
echo $html_saiu28idequiporesp1;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idliderrespon1'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idliderrespon1" name="saiu28idliderrespon1" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon1']; ?>"/>
<div id="div_saiu28idliderrespon1_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu28idliderrespon1', $_REQUEST['saiu28idliderrespon1_td'], $_REQUEST['saiu28idliderrespon1_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idliderrespon1" class="L"><?php echo $saiu28idliderrespon1_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu28tiemprespdias1'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiemprespdias1">
<?php
echo html_oculto('saiu28tiemprespdias1', $_REQUEST['saiu28tiemprespdias1']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempresphoras1'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempresphoras1">
<?php
echo html_oculto('saiu28tiempresphoras1', $_REQUEST['saiu28tiempresphoras1']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28centrotarea1'];
?>
</label>
<label>
<div id="div_saiu28centrotarea1">
<?php
echo $html_saiu28centrotarea1;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempousado1'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempousado1">
<?php
echo html_oculto('saiu28tiempousado1', $_REQUEST['saiu28tiempousado1']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempocalusado1'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempocalusado1">
<?php
echo html_oculto('saiu28tiempocalusado1', $_REQUEST['saiu28tiempocalusado1']);
?>
</div></label>
<?php
}else{
?>
<input id="saiu28idunidadresp1" name="saiu28idunidadresp1" type="hidden" value="<?php echo $_REQUEST['saiu28idunidadresp1']; ?>"/>
<input id="saiu28idequiporesp1" name="saiu28idequiporesp1" type="hidden" value="<?php echo $_REQUEST['saiu28idequiporesp1']; ?>"/>
<input id="saiu28idliderrespon1_td" name="saiu28idliderrespon1_td" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon1_td']; ?>"/>
<input id="saiu28idliderrespon1_doc" name="saiu28idliderrespon1_doc" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon1_doc']; ?>"/>
<input id="saiu28tiemprespdias1" name="saiu28tiemprespdias1" type="hidden" value="<?php echo $_REQUEST['saiu28tiemprespdias1']; ?>"/>
<input id="saiu28tiempresphoras1" name="saiu28tiempresphoras1" type="hidden" value="<?php echo $_REQUEST['saiu28tiempresphoras1']; ?>"/>
<input id="saiu28centrotarea1" name="saiu28centrotarea1" type="hidden" value="<?php echo $_REQUEST['saiu28centrotarea1']; ?>"/>
<input id="saiu28tiempousado1" name="saiu28tiempousado1" type="hidden" value="<?php echo $_REQUEST['saiu28tiempousado1']; ?>"/>
<input id="saiu28tiempocalusado1" name="saiu28tiempocalusado1" type="hidden" value="<?php echo $_REQUEST['saiu28tiempocalusado1']; ?>"/>
<?php
}
if ($idEtapa>1){
?>
<label class="Label130">
<?php
echo $ETI['saiu28idunidadresp2'];
?>
</label>
<label>
<div id="div_saiu28idunidadresp2">
<?php
echo $html_saiu28idunidadresp2;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28idequiporesp2'];
?>
</label>
<label>
<div id="div_saiu28idequiporesp2">
<?php
echo $html_saiu28idequiporesp2;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idliderrespon2'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idliderrespon2" name="saiu28idliderrespon2" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon2']; ?>"/>
<div id="div_saiu28idliderrespon2_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu28idliderrespon2', $_REQUEST['saiu28idliderrespon2_td'], $_REQUEST['saiu28idliderrespon2_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idliderrespon2" class="L"><?php echo $saiu28idliderrespon2_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu28tiemprespdias2'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiemprespdias2">
<?php
echo html_oculto('saiu28tiemprespdias2', $_REQUEST['saiu28tiemprespdias2']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempresphoras2'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempresphoras2">
<?php
echo html_oculto('saiu28tiempresphoras2', $_REQUEST['saiu28tiempresphoras2']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28centrotarea2'];
?>
</label>
<label>
<div id="div_saiu28centrotarea2">
<?php
echo $html_saiu28centrotarea2;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempousado2'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempousado2">
<?php
echo html_oculto('saiu28tiempousado2', $_REQUEST['saiu28tiempousado2']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempocalusado2'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempocalusado2">
<?php
echo html_oculto('saiu28tiempocalusado2', $_REQUEST['saiu28tiempocalusado2']);
?>
</div></label>
<?php
}else{
?>
<input id="saiu28idunidadresp2" name="saiu28idunidadresp2" type="hidden" value="<?php echo $_REQUEST['saiu28idunidadresp2']; ?>"/>
<input id="saiu28idequiporesp2" name="saiu28idequiporesp2" type="hidden" value="<?php echo $_REQUEST['saiu28idequiporesp2']; ?>"/>
<input id="saiu28idliderrespon2_td" name="saiu28idliderrespon2_td" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon2_td']; ?>"/>
<input id="saiu28idliderrespon2_doc" name="saiu28idliderrespon2_doc" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon2_doc']; ?>"/>
<input id="saiu28tiemprespdias2" name="saiu28tiemprespdias2" type="hidden" value="<?php echo $_REQUEST['saiu28tiemprespdias2']; ?>"/>
<input id="saiu28tiempresphoras2" name="saiu28tiempresphoras2" type="hidden" value="<?php echo $_REQUEST['saiu28tiempresphoras2']; ?>"/>
<input id="saiu28centrotarea2" name="saiu28centrotarea2" type="hidden" value="<?php echo $_REQUEST['saiu28centrotarea2']; ?>"/>
<input id="saiu28tiempousado2" name="saiu28tiempousado2" type="hidden" value="<?php echo $_REQUEST['saiu28tiempousado2']; ?>"/>
<input id="saiu28tiempocalusado2" name="saiu28tiempocalusado2" type="hidden" value="<?php echo $_REQUEST['saiu28tiempocalusado2']; ?>"/>
<?php
}
if ($idEtapa>2){
?>
<label class="Label130">
<?php
echo $ETI['saiu28idunidadresp3'];
?>
</label>
<label>
<div id="div_saiu28idunidadresp3">
<?php
echo $html_saiu28idunidadresp3;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28idequiporesp3'];
?>
</label>
<label>
<div id="div_saiu28idequiporesp3">
<?php
echo $html_saiu28idequiporesp3;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu28idliderrespon3'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu28idliderrespon3" name="saiu28idliderrespon3" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon3']; ?>"/>
<div id="div_saiu28idliderrespon3_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu28idliderrespon3', $_REQUEST['saiu28idliderrespon3_td'], $_REQUEST['saiu28idliderrespon3_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu28idliderrespon3" class="L"><?php echo $saiu28idliderrespon3_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu28tiemprespdias3'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiemprespdias3">
<?php
echo html_oculto('saiu28tiemprespdias3', $_REQUEST['saiu28tiemprespdias3']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempresphoras3'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempresphoras3">
<?php
echo html_oculto('saiu28tiempresphoras3', $_REQUEST['saiu28tiempresphoras3']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28centrotarea3'];
?>
</label>
<label>
<div id="div_saiu28centrotarea3">
<?php
echo $html_saiu28centrotarea3;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempousado3'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempousado3">
<?php
echo html_oculto('saiu28tiempousado3', $_REQUEST['saiu28tiempousado3']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu28tiempocalusado3'];
?>
</label>
<label class="Label130"><div id="div_saiu28tiempocalusado3">
<?php
echo html_oculto('saiu28tiempocalusado3', $_REQUEST['saiu28tiempocalusado3']);
?>
</div></label>
<?php
}else{
?>
<input id="saiu28idunidadresp3" name="saiu28idunidadresp3" type="hidden" value="<?php echo $_REQUEST['saiu28idunidadresp3']; ?>"/>
<input id="saiu28idequiporesp3" name="saiu28idequiporesp3" type="hidden" value="<?php echo $_REQUEST['saiu28idequiporesp3']; ?>"/>
<input id="saiu28idliderrespon3_td" name="saiu28idliderrespon3_td" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon3_td']; ?>"/>
<input id="saiu28idliderrespon3_doc" name="saiu28idliderrespon3_doc" type="hidden" value="<?php echo $_REQUEST['saiu28idliderrespon3_doc']; ?>"/>
<input id="saiu28tiemprespdias3" name="saiu28tiemprespdias3" type="hidden" value="<?php echo $_REQUEST['saiu28tiemprespdias3']; ?>"/>
<input id="saiu28tiempresphoras3" name="saiu28tiempresphoras3" type="hidden" value="<?php echo $_REQUEST['saiu28tiempresphoras3']; ?>"/>
<input id="saiu28centrotarea3" name="saiu28centrotarea3" type="hidden" value="<?php echo $_REQUEST['saiu28centrotarea3']; ?>"/>
<input id="saiu28tiempousado3" name="saiu28tiempousado3" type="hidden" value="<?php echo $_REQUEST['saiu28tiempousado3']; ?>"/>
<input id="saiu28tiempocalusado3" name="saiu28tiempocalusado3" type="hidden" value="<?php echo $_REQUEST['saiu28tiempocalusado3']; ?>"/>
<?php
}
?>
<?php
// -- Inicia Grupo campos 3029 Anexos Mesa de ayuda
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3029'];
?>
</label>
<input id="boculta3029" name="boculta3029" type="hidden" value="<?php echo $_REQUEST['boculta3029']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3029" name="btexcel3029" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3029();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3029" name="btexpande3029" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3029,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3029']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3029" name="btrecoge3029" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3029,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3029']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3029" style="display:<?php if ($_REQUEST['boculta3029']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu29idanexo'];
?>
</label>
<label class="Label500">
<div id="div_saiu29idanexo">
<?php
echo $html_saiu29idanexo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu29consec'];
?>
</label>
<label class="Label130"><div id="div_saiu29consec">
<?php
if ((int)$_REQUEST['saiu29id']==0){
?>
<input id="saiu29consec" name="saiu29consec" type="text" value="<?php echo $_REQUEST['saiu29consec']; ?>" onchange="revisaf3029()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu29consec', $_REQUEST['saiu29consec'], formato_numero($_REQUEST['saiu29consec']));
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['saiu29id'];
?>
</label>
<label class="Label60"><div id="div_saiu29id">
<?php
	echo html_oculto('saiu29id', $_REQUEST['saiu29id'], formato_numero($_REQUEST['saiu29id']));
?>
</div></label>

<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu29detalle'];
?>
<textarea id="saiu29detalle" name="saiu29detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu29detalle']; ?>"><?php echo $_REQUEST['saiu29detalle']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<input id="saiu29idorigen" name="saiu29idorigen" type="hidden" value="<?php echo $_REQUEST['saiu29idorigen']; ?>"/>
<input id="saiu29idarchivo" name="saiu29idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu29idarchivo']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu29idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu29idorigen'], (int)$_REQUEST['saiu29idarchivo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexasaiu29idarchivo" name="banexasaiu29idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu29idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu29id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="beliminasaiu29idarchivo" name="beliminasaiu29idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu29idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu29idarchivo']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3029" name="bguarda3029" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3029()" title="<?php echo $ETI['bt_mini_guardar_3029']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3029" name="blimpia3029" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3029()" title="<?php echo $ETI['bt_mini_limpiar_3029']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3029" name="belimina3029" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3029()" title="<?php echo $ETI['bt_mini_eliminar_3029']; ?>" style="display:<?php if ((int)$_REQUEST['saiu29id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3029
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3029" name="bnombre3029" type="text" value="<?php echo $_REQUEST['bnombre3029']; ?>" onchange="paginarf3029()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3029;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f3029detalle">
<?php
echo $sTabla3029;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3029 Anexos Mesa de ayuda
?>
<?php
// -- Inicia Grupo campos 3030 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3030'];
?>
</label>
<input id="boculta3030" name="boculta3030" type="hidden" value="<?php echo $_REQUEST['boculta3030']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3030" name="btexcel3030" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3030();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3030" name="btexpande3030" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3030,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3030']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3030" name="btrecoge3030" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3030,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3030']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3030" style="display:<?php if ($_REQUEST['boculta3030']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu30consec'];
?>
</label>
<label class="Label130"><div id="div_saiu30consec">
<?php
if ((int)$_REQUEST['saiu30id']==0){
?>
<input id="saiu30consec" name="saiu30consec" type="text" value="<?php echo $_REQUEST['saiu30consec']; ?>" onchange="revisaf3030()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu30consec', $_REQUEST['saiu30consec'], formato_numero($_REQUEST['saiu30consec']));
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['saiu30id'];
?>
</label>
<label class="Label60"><div id="div_saiu30id">
<?php
	echo html_oculto('saiu30id', $_REQUEST['saiu30id'], formato_numero($_REQUEST['saiu30id']));
?>
</div></label>
<label class="Label200">
<?php
echo $ETI['saiu30visiblealinteresado'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu30visiblealinteresado;
?>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['saiu30anotacion'];
?>
<textarea id="saiu30anotacion" name="saiu30anotacion" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu30anotacion']; ?>"><?php echo $_REQUEST['saiu30anotacion']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu30idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu30idusuario" name="saiu30idusuario" type="hidden" value="<?php echo $_REQUEST['saiu30idusuario']; ?>"/>
<div id="div_saiu30idusuario_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu30idusuario', $_REQUEST['saiu30idusuario_td'], $_REQUEST['saiu30idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu30idusuario" class="L"><?php echo $saiu30idusuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['saiu30fecha'];
?>
</label>
<label class="Label220">
<div id="div_saiu30fecha">
<?php
echo html_oculto('saiu30fecha', $_REQUEST['saiu30fecha'], fecha_desdenumero($_REQUEST['saiu30fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="campo_HoraMin" id="div_saiu30hora">
<?php
echo html_HoraMin('saiu30hora', $_REQUEST['saiu30hora'], 'saiu30minuto', $_REQUEST['saiu30minuto'], true);
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3030" name="bguarda3030" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3030()" title="<?php echo $ETI['bt_mini_guardar_3030']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3030" name="blimpia3030" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3030()" title="<?php echo $ETI['bt_mini_limpiar_3030']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3030" name="belimina3030" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3030()" title="<?php echo $ETI['bt_mini_eliminar_3030']; ?>" style="display:<?php if ((int)$_REQUEST['saiu30id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3030
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3030" name="bnombre3030" type="text" value="<?php echo $_REQUEST['bnombre3030']; ?>" onchange="paginarf3030()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3030;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f3030detalle">
<?php
echo $sTabla3030;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3030 Anotaciones
?>
<?php
// -- Inicia Grupo campos 3039 Cambios de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3039'];
?>
</label>
<input id="boculta3039" name="boculta3039" type="hidden" value="<?php echo $_REQUEST['boculta3039']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div id="div_f3039detalle">
<?php
echo $sTabla3039;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3039 Cambios de estado
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3028
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
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar2;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu28agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_blistar;
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu28estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestado;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3028()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3028()" autocomplete="off"/>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu28temasolicitud'];
?>
</label>
<label class="Label600">
<?php
echo $html_btipo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_desde'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('bfechaini', $_REQUEST['bfechaini'], true, 'paginarf3028()', $iAgnoIni, $iAgnoFin);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label60">
<?php
echo $ETI['msg_hasta'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('bfechafin', $_REQUEST['bfechafin'], true, 'paginarf3028()', $iAgnoIni, $iAgnoFin);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu28detalle'];
?>
<input id="bdetalle" name="bdetalle" type="text" value="<?php echo $_REQUEST['bdetalle']; ?>" onchange="paginarf3028()" autocomplete="off" class="L"/>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3028detalle">
<?php
echo $sTabla3028;
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
echo $ETI['msg_saiu28consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu28consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu28consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu28consec_nuevo" name="saiu28consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu28consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3028" name="titulo_3028" type="hidden" value="<?php echo $ETI['titulo_3028']; ?>" />
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
echo '<h2>'.$ETI['titulo_3028'].'</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /DIV_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3028'].'</h2>';
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
if ($_REQUEST['saiu28estado']<7){
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
<script language="javascript">
<!--
$().ready(function(){
$("#saiu28idcentro").chosen();
$("#saiu28coddepto").chosen();
$("#saiu28codciudad").chosen();
<?php
if ($bEnProceso){
?>
$("#saiu28tiposolicitud").chosen();
<?php
	}
?>
$("#saiu28temasolicitud").chosen();
$("#saiu28idprograma").chosen();
$("#saiu28idperiodo").chosen();
$("#btipo").chosen();
});
-->
</script>
<script language="javascript" src="ac_3028.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>