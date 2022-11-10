<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
*/
/** Archivo saiubaseconocimiento.php.
* Modulo 3031 saiu31baseconocimiento.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date martes, 4 de agosto de 2020
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=3031;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
require $mensajes_todas;
require $mensajes_3031;
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
		header('Location:noticia.php?ret=saiubaseconocimiento.php');
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
$mensajes_3032='lg/lg_3032_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3032)){$mensajes_3032='lg/lg_3032_es.php';}
$mensajes_3033='lg/lg_3033_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3033)){$mensajes_3033='lg/lg_3033_es.php';}
$mensajes_3038='lg/lg_3038_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3038)){$mensajes_3038='lg/lg_3038_es.php';}
$mensajes_3035='lg/lg_3035_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3035)){$mensajes_3035='lg/lg_3035_es.php';}
require $mensajes_3032;
require $mensajes_3033;
require $mensajes_3038;
require $mensajes_3035;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3031 saiu31baseconocimiento
require 'lib3031.php';
// -- 3032 Temas asociados
require 'lib3032.php';
// -- 3033 Palabras claves
require 'lib3033.php';
// -- 3038 Cambios de estado
require 'lib3038.php';
// -- 3035 Palabras clave
require 'lib3035.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f3031_Combosaiu31idtemageneral');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3031_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3031_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3031_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3031_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3032_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3032_Traer');
$xajax->register(XAJAX_FUNCTION,'f3032_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3032_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3032_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3035_Combosaiu35idcentro');
$xajax->register(XAJAX_FUNCTION,'f3035_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3035_Traer');
$xajax->register(XAJAX_FUNCTION,'f3035_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3035_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3035_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3038_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3038_Traer');
$xajax->register(XAJAX_FUNCTION,'f3038_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3038_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3038_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3033_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3033_Traer');
$xajax->register(XAJAX_FUNCTION,'f3033_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3033_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3033_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f3033_MasUno');
$xajax->register(XAJAX_FUNCTION,'f3033_MenosUno');
$xajax->register(XAJAX_FUNCTION,'f3033_GuardarCuadro');
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
if (isset($_REQUEST['paginaf3031'])==0){$_REQUEST['paginaf3031']=1;}
if (isset($_REQUEST['lppf3031'])==0){$_REQUEST['lppf3031']=20;}
if (isset($_REQUEST['boculta3031'])==0){$_REQUEST['boculta3031']=0;}
if (isset($_REQUEST['paginaf3032'])==0){$_REQUEST['paginaf3032']=1;}
if (isset($_REQUEST['lppf3032'])==0){$_REQUEST['lppf3032']=20;}
if (isset($_REQUEST['boculta3032'])==0){$_REQUEST['boculta3032']=0;}
if (isset($_REQUEST['paginaf3033'])==0){$_REQUEST['paginaf3033']=1;}
if (isset($_REQUEST['lppf3033'])==0){$_REQUEST['lppf3033']=20;}
if (isset($_REQUEST['boculta3033'])==0){$_REQUEST['boculta3033']=0;}
if (isset($_REQUEST['paginaf3038'])==0){$_REQUEST['paginaf3038']=1;}
if (isset($_REQUEST['lppf3038'])==0){$_REQUEST['lppf3038']=20;}
if (isset($_REQUEST['boculta3038'])==0){$_REQUEST['boculta3038']=0;}
if (isset($_REQUEST['paginaf3035'])==0){$_REQUEST['paginaf3035']=1;}
if (isset($_REQUEST['lppf3035'])==0){$_REQUEST['lppf3035']=20;}
if (isset($_REQUEST['boculta3035'])==0){$_REQUEST['boculta3035']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu31consec'])==0){$_REQUEST['saiu31consec']='';}
if (isset($_REQUEST['saiu31consec_nuevo'])==0){$_REQUEST['saiu31consec_nuevo']='';}
if (isset($_REQUEST['saiu31version'])==0){$_REQUEST['saiu31version']=0;}
if (isset($_REQUEST['saiu31id'])==0){$_REQUEST['saiu31id']='';}
if (isset($_REQUEST['saiu31estado'])==0){$_REQUEST['saiu31estado']='N';}
if (isset($_REQUEST['saiu31idtemageneral'])==0){$_REQUEST['saiu31idtemageneral']='';}
if (isset($_REQUEST['saiu31titulo'])==0){$_REQUEST['saiu31titulo']='';}
if (isset($_REQUEST['saiu31resumen'])==0){$_REQUEST['saiu31resumen']='';}
if (isset($_REQUEST['saiu31contenido'])==0){$_REQUEST['saiu31contenido']='';}
if (isset($_REQUEST['saiu31idunidadresp'])==0){$_REQUEST['saiu31idunidadresp']='';}
if (isset($_REQUEST['saiu31temporal'])==0){$_REQUEST['saiu31temporal']='';}
if (isset($_REQUEST['saiu31fechaini'])==0){$_REQUEST['saiu31fechaini']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu31fechafin'])==0){$_REQUEST['saiu31fechafin']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu31cobertura'])==0){$_REQUEST['saiu31cobertura']=1;}
if (isset($_REQUEST['saiu31entornodeuso'])==0){$_REQUEST['saiu31entornodeuso']='';}
if (isset($_REQUEST['saiu31aplicaaspirante'])==0){$_REQUEST['saiu31aplicaaspirante']='';}
if (isset($_REQUEST['saiu31aplicaestudiante'])==0){$_REQUEST['saiu31aplicaestudiante']='';}
if (isset($_REQUEST['saiu31aplicaegresado'])==0){$_REQUEST['saiu31aplicaegresado']='';}
if (isset($_REQUEST['saiu31aplicadocentes'])==0){$_REQUEST['saiu31aplicadocentes']='';}
if (isset($_REQUEST['saiu31aplicaadministra'])==0){$_REQUEST['saiu31aplicaadministra']='';}
if (isset($_REQUEST['saiu31aplicaotros'])==0){$_REQUEST['saiu31aplicaotros']='';}
if (isset($_REQUEST['saiu31enlaceinfo'])==0){$_REQUEST['saiu31enlaceinfo']='';}
if (isset($_REQUEST['saiu31enlaceproceso'])==0){$_REQUEST['saiu31enlaceproceso']='';}
if (isset($_REQUEST['saiu31aplicanotificacion'])==0){$_REQUEST['saiu31aplicanotificacion']='';}
if (isset($_REQUEST['saiu31fechaparanotificar'])==0){$_REQUEST['saiu31fechaparanotificar']=0;}//{fecha_hoy();}
if (isset($_REQUEST['saiu31prioridadnotifica'])==0){$_REQUEST['saiu31prioridadnotifica']=1;}
if (isset($_REQUEST['saiu31usuario'])==0){$_REQUEST['saiu31usuario']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu31usuario_td'])==0){$_REQUEST['saiu31usuario_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu31usuario_doc'])==0){$_REQUEST['saiu31usuario_doc']='';}
if (isset($_REQUEST['saiu31fecha'])==0){$_REQUEST['saiu31fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu31usuarioaprueba'])==0){$_REQUEST['saiu31usuarioaprueba']=$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu31usuarioaprueba_td'])==0){$_REQUEST['saiu31usuarioaprueba_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu31usuarioaprueba_doc'])==0){$_REQUEST['saiu31usuarioaprueba_doc']='';}
if (isset($_REQUEST['saiu31fechaaprueba'])==0){$_REQUEST['saiu31fechaaprueba']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu31terminos'])==0){$_REQUEST['saiu31terminos']='';}
if ((int)$_REQUEST['paso']>0){
	//Temas asociados
	if (isset($_REQUEST['saiu32idtema'])==0){$_REQUEST['saiu32idtema']='';}
	if (isset($_REQUEST['saiu32id'])==0){$_REQUEST['saiu32id']='';}
	if (isset($_REQUEST['saiu32activo'])==0){$_REQUEST['saiu32activo']='S';}
	//Palabras claves
	if (isset($_REQUEST['saiu33idpalabra'])==0){$_REQUEST['saiu33idpalabra']='';}
	if (isset($_REQUEST['saiu33id'])==0){$_REQUEST['saiu33id']='';}
	if (isset($_REQUEST['saiu33activo'])==0){$_REQUEST['saiu33activo']='S';}
	//Cambios de estado
	if (isset($_REQUEST['saiu38consec'])==0){$_REQUEST['saiu38consec']='';}
	if (isset($_REQUEST['saiu38id'])==0){$_REQUEST['saiu38id']='';}
	if (isset($_REQUEST['saiu38idestadorigen'])==0){$_REQUEST['saiu38idestadorigen']='';}
	if (isset($_REQUEST['saiu38idestadofin'])==0){$_REQUEST['saiu38idestadofin']='';}
	if (isset($_REQUEST['saiu38detalle'])==0){$_REQUEST['saiu38detalle']='';}
	if (isset($_REQUEST['saiu38usuario'])==0){$_REQUEST['saiu38usuario']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu38usuario_td'])==0){$_REQUEST['saiu38usuario_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['saiu38usuario_doc'])==0){$_REQUEST['saiu38usuario_doc']='';}
	if (isset($_REQUEST['saiu38fecha'])==0){$_REQUEST['saiu38fecha']='';}//{fecha_hoy();}
	//Palabras clave
	if (isset($_REQUEST['saiu35idzona'])==0){$_REQUEST['saiu35idzona']='';}
	if (isset($_REQUEST['saiu35idcentro'])==0){$_REQUEST['saiu35idcentro']='';}
	if (isset($_REQUEST['saiu35id'])==0){$_REQUEST['saiu35id']='';}
	if (isset($_REQUEST['saiu35activo'])==0){$_REQUEST['saiu35activo']='S';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=1;}
if (isset($_REQUEST['bestado'])==0){$_REQUEST['bestado']='';}
if ((int)$_REQUEST['paso']>0){
	//Temas asociados
	if (isset($_REQUEST['bnombre3032'])==0){$_REQUEST['bnombre3032']='';}
	//if (isset($_REQUEST['blistar3032'])==0){$_REQUEST['blistar3032']='';}
	//Palabras claves
	if (isset($_REQUEST['bnombre3033'])==0){$_REQUEST['bnombre3033']='';}
	//if (isset($_REQUEST['blistar3033'])==0){$_REQUEST['blistar3033']='';}
	//Cambios de estado
	if (isset($_REQUEST['bnombre3038'])==0){$_REQUEST['bnombre3038']='';}
	//if (isset($_REQUEST['blistar3038'])==0){$_REQUEST['blistar3038']='';}
	//Palabras clave
	if (isset($_REQUEST['bnombre3035'])==0){$_REQUEST['bnombre3035']='';}
	//if (isset($_REQUEST['blistar3035'])==0){$_REQUEST['blistar3035']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu31usuario_td']=$APP->tipo_doc;
	$_REQUEST['saiu31usuario_doc']='';
	$_REQUEST['saiu31usuarioaprueba_td']=$APP->tipo_doc;
	$_REQUEST['saiu31usuarioaprueba_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu31consec='.$_REQUEST['saiu31consec'].' AND saiu31version='.$_REQUEST['saiu31version'].'';
		}else{
		$sSQLcondi='saiu31id='.$_REQUEST['saiu31id'].'';
		}
	$sSQL='SELECT * FROM saiu31baseconocimiento WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu31consec']=$fila['saiu31consec'];
		$_REQUEST['saiu31version']=$fila['saiu31version'];
		$_REQUEST['saiu31id']=$fila['saiu31id'];
		$_REQUEST['saiu31estado']=$fila['saiu31estado'];
		$_REQUEST['saiu31idtemageneral']=$fila['saiu31idtemageneral'];
		$_REQUEST['saiu31titulo']=$fila['saiu31titulo'];
		$_REQUEST['saiu31resumen']=$fila['saiu31resumen'];
		$_REQUEST['saiu31contenido']=$fila['saiu31contenido'];
		$_REQUEST['saiu31idunidadresp']=$fila['saiu31idunidadresp'];
		$_REQUEST['saiu31temporal']=$fila['saiu31temporal'];
		$_REQUEST['saiu31fechaini']=$fila['saiu31fechaini'];
		$_REQUEST['saiu31fechafin']=$fila['saiu31fechafin'];
		$_REQUEST['saiu31cobertura']=$fila['saiu31cobertura'];
		$_REQUEST['saiu31entornodeuso']=$fila['saiu31entornodeuso'];
		$_REQUEST['saiu31aplicaaspirante']=$fila['saiu31aplicaaspirante'];
		$_REQUEST['saiu31aplicaestudiante']=$fila['saiu31aplicaestudiante'];
		$_REQUEST['saiu31aplicaegresado']=$fila['saiu31aplicaegresado'];
		$_REQUEST['saiu31aplicadocentes']=$fila['saiu31aplicadocentes'];
		$_REQUEST['saiu31aplicaadministra']=$fila['saiu31aplicaadministra'];
		$_REQUEST['saiu31aplicaotros']=$fila['saiu31aplicaotros'];
		$_REQUEST['saiu31enlaceinfo']=$fila['saiu31enlaceinfo'];
		$_REQUEST['saiu31enlaceproceso']=$fila['saiu31enlaceproceso'];
		$_REQUEST['saiu31aplicanotificacion']=$fila['saiu31aplicanotificacion'];
		$_REQUEST['saiu31fechaparanotificar']=$fila['saiu31fechaparanotificar'];
		$_REQUEST['saiu31prioridadnotifica']=$fila['saiu31prioridadnotifica'];
		$_REQUEST['saiu31usuario']=$fila['saiu31usuario'];
		$_REQUEST['saiu31fecha']=$fila['saiu31fecha'];
		$_REQUEST['saiu31usuarioaprueba']=$fila['saiu31usuarioaprueba'];
		$_REQUEST['saiu31fechaaprueba']=$fila['saiu31fechaaprueba'];
		$_REQUEST['saiu31terminos']=$fila['saiu31terminos'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3031']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu31estado']=1;
	$bCerrando=true;
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==18){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu31estado']=7;
	$_REQUEST['saiu31usuarioaprueba']=$_SESSION['unad_id_tercero'];
	$_REQUEST['saiu31fechaaprueba']=fecha_DiaMod();
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
		$_REQUEST['saiu31estado']=1;
		}else{
		$sSQL='UPDATE saiu31baseconocimiento SET saiu31estado=0 WHERE saiu31id='.$_REQUEST['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu31id'], 'Abre Base de conocimiento', $objDB);
		$_REQUEST['saiu31estado']=0;
		$sError='<b>El documento ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3031_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
	$_REQUEST['saiu31consec_nuevo']=numeros_validar($_REQUEST['saiu31consec_nuevo']);
	if ($_REQUEST['saiu31consec_nuevo']==''){$sError=$ERR['saiu31consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu31id FROM saiu31baseconocimiento WHERE saiu31version='.$_REQUEST['saiu31version'].' AND saiu31consec='.$_REQUEST['saiu31consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu31consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu31baseconocimiento SET saiu31consec='.$_REQUEST['saiu31consec_nuevo'].' WHERE saiu31id='.$_REQUEST['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu31consec'].' a '.$_REQUEST['saiu31consec_nuevo'].'';
		$_REQUEST['saiu31consec']=$_REQUEST['saiu31consec_nuevo'];
		$_REQUEST['saiu31consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu31id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3031_db_Eliminar($_REQUEST['saiu31id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu31consec']='';
	$_REQUEST['saiu31consec_nuevo']='';
	$_REQUEST['saiu31version']=0;
	$_REQUEST['saiu31id']='';
	$_REQUEST['saiu31estado']=0;
	$_REQUEST['saiu31idtemageneral']='';
	$_REQUEST['saiu31titulo']='';
	$_REQUEST['saiu31resumen']='';
	$_REQUEST['saiu31contenido']='';
	$_REQUEST['saiu31idunidadresp']='';
	$_REQUEST['saiu31temporal']=0;
	$_REQUEST['saiu31fechaini']='';//fecha_hoy();
	$_REQUEST['saiu31fechafin']='';//fecha_hoy();
	$_REQUEST['saiu31cobertura']=1;
	$_REQUEST['saiu31entornodeuso']=0;
	$_REQUEST['saiu31aplicaaspirante']='';
	$_REQUEST['saiu31aplicaestudiante']='';
	$_REQUEST['saiu31aplicaegresado']='';
	$_REQUEST['saiu31aplicadocentes']='';
	$_REQUEST['saiu31aplicaadministra']='';
	$_REQUEST['saiu31aplicaotros']='';
	$_REQUEST['saiu31enlaceinfo']='';
	$_REQUEST['saiu31enlaceproceso']='';
	$_REQUEST['saiu31aplicanotificacion']=0;
	$_REQUEST['saiu31fechaparanotificar']=0;//fecha_hoy();
	$_REQUEST['saiu31prioridadnotifica']=1;
	$_REQUEST['saiu31usuario']=$idTercero;
	$_REQUEST['saiu31usuario_td']=$APP->tipo_doc;
	$_REQUEST['saiu31usuario_doc']='';
	$_REQUEST['saiu31fecha']='';//fecha_hoy();
	$_REQUEST['saiu31usuarioaprueba']=0;
	$_REQUEST['saiu31usuarioaprueba_td']=$APP->tipo_doc;
	$_REQUEST['saiu31usuarioaprueba_doc']='';
	$_REQUEST['saiu31fechaaprueba']='';//fecha_hoy();
	$_REQUEST['saiu31terminos']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['saiu32idbasecon']='';
	$_REQUEST['saiu32idtema']='';
	$_REQUEST['saiu32id']='';
	$_REQUEST['saiu32activo']=1;
	$_REQUEST['saiu32porcentaje']='';
	$_REQUEST['saiu32porcpalclave']=0;
	$_REQUEST['saiu35idbasecon']='';
	$_REQUEST['saiu35idzona']='';
	$_REQUEST['saiu35idcentro']='';
	$_REQUEST['saiu35id']='';
	$_REQUEST['saiu35activo']=1;
	$_REQUEST['saiu38idbasecon']='';
	$_REQUEST['saiu38consec']='';
	$_REQUEST['saiu38id']='';
	$_REQUEST['saiu38idestadorigen']='';
	$_REQUEST['saiu38idestadofin']='';
	$_REQUEST['saiu38detalle']='';
	$_REQUEST['saiu38usuario']=0;//$idTercero;
	$_REQUEST['saiu38usuario_td']=$APP->tipo_doc;
	$_REQUEST['saiu38usuario_doc']='';
	$_REQUEST['saiu38fecha']='';//fecha_hoy();
	$_REQUEST['saiu33idbasecon']='';
	$_REQUEST['saiu33idpalabra']='';
	$_REQUEST['saiu33id']='';
	$_REQUEST['saiu33activo']=1;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
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
list($saiu31estado_nombre, $sErrorDet)=tabla_campoxid('saiu36estadobase','saiu36nombre','saiu36id',$_REQUEST['saiu31estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu31estado=html_oculto('saiu31estado', $_REQUEST['saiu31estado'], $saiu31estado_nombre);
/*
$objCombos->nuevo('saiu31idtemageneral', $_REQUEST['saiu31idtemageneral'], true, '{'.$ETI['msg_ninguno'].'}', 0);
$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol WHERE saiu03id>0 ORDER BY saiu03titulo';
$html_saiu31idtemageneral=$objCombos->html($sSQL, $objDB);
*/
$objCombos->nuevo('saiu31idunidadresp', $_REQUEST['saiu31idunidadresp'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('0', '[Sin asignar a una unidad]');
$objCombos->sAccion='carga_combo_saiu31idtemageneral();';
$sSQL26=f226_ConsultaCombo();
$html_saiu31idunidadresp=$objCombos->html($sSQL26, $objDB);
$html_saiu31idtemageneral=f3031_HTMLComboV2_saiu31idtemageneral($objDB, $objCombos, $_REQUEST['saiu31idtemageneral'], $_REQUEST['saiu31idunidadresp']);
$objCombos->nuevo('saiu31temporal', $_REQUEST['saiu31temporal'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31temporal, $isaiu31temporal);
$html_saiu31temporal=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31cobertura', $_REQUEST['saiu31cobertura'], false, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu17id AS id, saiu17nombre AS nombre FROM saiu17nivelatencion WHERE saiu17id>0 ORDER BY saiu17id';
$html_saiu31cobertura=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu31entornodeuso', $_REQUEST['saiu31entornodeuso'], true, $asaiu31entornodeuso[0], 0);
//$objCombos->addItem(1, $ETI['si']);
$objCombos->addArreglo($asaiu31entornodeuso, $isaiu31entornodeuso);
$html_saiu31entornodeuso=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicaaspirante', $_REQUEST['saiu31aplicaaspirante'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicaaspirante, $isaiu31aplicaaspirante);
$html_saiu31aplicaaspirante=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicaestudiante', $_REQUEST['saiu31aplicaestudiante'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicaestudiante, $isaiu31aplicaestudiante);
$html_saiu31aplicaestudiante=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicaegresado', $_REQUEST['saiu31aplicaegresado'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicaegresado, $isaiu31aplicaegresado);
$html_saiu31aplicaegresado=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicadocentes', $_REQUEST['saiu31aplicadocentes'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicadocentes, $isaiu31aplicadocentes);
$html_saiu31aplicadocentes=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicaadministra', $_REQUEST['saiu31aplicaadministra'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicaadministra, $isaiu31aplicaadministra);
$html_saiu31aplicaadministra=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicaotros', $_REQUEST['saiu31aplicaotros'], true, '', '');
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicaotros, $isaiu31aplicaotros);
$html_saiu31aplicaotros=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31aplicanotificacion', $_REQUEST['saiu31aplicanotificacion'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu31aplicanotificacion, $isaiu31aplicanotificacion);
$html_saiu31aplicanotificacion=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu31prioridadnotifica', $_REQUEST['saiu31prioridadnotifica'], true, $asaiu31prioridadnotifica[0], 0);
//$objCombos->addItem(1, $ETI['si']);
$objCombos->addArreglo($asaiu31prioridadnotifica, $isaiu31prioridadnotifica);
$html_saiu31prioridadnotifica=$objCombos->html('', $objDB);
list($saiu31usuario_rs, $_REQUEST['saiu31usuario'], $_REQUEST['saiu31usuario_td'], $_REQUEST['saiu31usuario_doc'])=html_tercero($_REQUEST['saiu31usuario_td'], $_REQUEST['saiu31usuario_doc'], $_REQUEST['saiu31usuario'], 0, $objDB);
list($saiu31usuarioaprueba_rs, $_REQUEST['saiu31usuarioaprueba'], $_REQUEST['saiu31usuarioaprueba_td'], $_REQUEST['saiu31usuarioaprueba_doc'])=html_tercero($_REQUEST['saiu31usuarioaprueba_td'], $_REQUEST['saiu31usuarioaprueba_doc'], $_REQUEST['saiu31usuarioaprueba'], 0, $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	$html_saiu32idtema=f3032_HTMLComboV2_saiu32idtema($objDB, $objCombos, $_REQUEST['saiu32idtema']);
	$objCombos->nuevo('saiu32activo', $_REQUEST['saiu32activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu32activo, $isaiu32activo);
	$html_saiu32activo=$objCombos->html('', $objDB);
	$html_saiu35idzona=f3035_HTMLComboV2_saiu35idzona($objDB, $objCombos, $_REQUEST['saiu35idzona']);
	$html_saiu35idcentro=f3035_HTMLComboV2_saiu35idcentro($objDB, $objCombos, $_REQUEST['saiu35idcentro'], $_REQUEST['saiu35idzona']);
	$objCombos->nuevo('saiu35activo', $_REQUEST['saiu35activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu35activo, $isaiu35activo);
	$html_saiu35activo=$objCombos->html('', $objDB);
	$objCombos->nuevo('saiu38idestadorigen', $_REQUEST['saiu38idestadorigen'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT saiu36id AS id, saiu36nombre AS nombre FROM saiu36estadobase ORDER BY saiu36nombre';
	$html_saiu38idestadorigen=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu38idestadofin', $_REQUEST['saiu38idestadofin'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT saiu36id AS id, saiu36nombre AS nombre FROM saiu36estadobase ORDER BY saiu36nombre';
	$html_saiu38idestadofin=$objCombos->html($sSQL, $objDB);
	list($saiu38usuario_rs, $_REQUEST['saiu38usuario'], $_REQUEST['saiu38usuario_td'], $_REQUEST['saiu38usuario_doc'])=html_tercero($_REQUEST['saiu38usuario_td'], $_REQUEST['saiu38usuario_doc'], $_REQUEST['saiu38usuario'], 0, $objDB);
	$html_saiu33idpalabra=f3033_HTMLComboV2_saiu33idpalabra($objDB, $objCombos, $_REQUEST['saiu33idpalabra']);
	$objCombos->nuevo('saiu33activo', $_REQUEST['saiu33activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu33activo, $isaiu33activo);
	$html_saiu33activo=$objCombos->html('', $objDB);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu31estado']==7){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);

$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3031()';
$objCombos->addItem(1, 'Mis registros');
$html_blistar=$objCombos->html('', $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3031()';
$sSQL='SELECT saiu36id AS id, saiu36nombre AS nombre FROM saiu36estadobase ORDER BY saiu36id';
$html_bestado=$objCombos->html($sSQL, $objDB);
/*
//$html_blistar=$objCombos->comboSistema(3031, 1, $objDB, 'paginarf3031()');
$objCombos->nuevo('blistar3032', $_REQUEST['blistar3032'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3032=$objCombos->comboSistema(3032, 1, $objDB, 'paginarf3032()');
$objCombos->nuevo('blistar3033', $_REQUEST['blistar3033'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3033=$objCombos->comboSistema(3033, 1, $objDB, 'paginarf3033()');
$objCombos->nuevo('blistar3038', $_REQUEST['blistar3038'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3038=$objCombos->comboSistema(3038, 1, $objDB, 'paginarf3038()');
$objCombos->nuevo('blistar3035', $_REQUEST['blistar3035'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3035=$objCombos->comboSistema(3035, 1, $objDB, 'paginarf3035()');
$objCombos->nuevo('blistar3037', $_REQUEST['blistar3037'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3037=$objCombos->comboSistema(3037, 1, $objDB, 'paginarf3037()');
*/
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3031;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu31estado']==0){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3031'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3031'];
$aParametros[102]=$_REQUEST['lppf3031'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['bestado'];
list($sTabla3031, $sDebugTabla)=f3031_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3032='';
$sTabla3033='';
$sTabla3038='';
$sTabla3035='';
if ($_REQUEST['paso']!=0){
	//Temas asociados
	$aParametros3032[0]=$_REQUEST['saiu31id'];
	$aParametros3032[100]=$idTercero;
	$aParametros3032[101]=$_REQUEST['paginaf3032'];
	$aParametros3032[102]=$_REQUEST['lppf3032'];
	//$aParametros3032[103]=$_REQUEST['bnombre3032'];
	//$aParametros3032[104]=$_REQUEST['blistar3032'];
	list($sTabla3032, $sDebugTabla)=f3032_TablaDetalleV2($aParametros3032, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Palabras claves
	$aParametros3033[0]=$_REQUEST['saiu31id'];
	$aParametros3033[100]=$idTercero;
	$aParametros3033[101]=$_REQUEST['paginaf3033'];
	$aParametros3033[102]=$_REQUEST['lppf3033'];
	//$aParametros3033[103]=$_REQUEST['bnombre3033'];
	//$aParametros3033[104]=$_REQUEST['blistar3033'];
	list($sTabla3033, $sDebugTabla)=f3033_TablaDetalleV2($aParametros3033, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Cambios de estado
	$aParametros3038[0]=$_REQUEST['saiu31id'];
	$aParametros3038[100]=$idTercero;
	$aParametros3038[101]=$_REQUEST['paginaf3038'];
	$aParametros3038[102]=$_REQUEST['lppf3038'];
	//$aParametros3038[103]=$_REQUEST['bnombre3038'];
	//$aParametros3038[104]=$_REQUEST['blistar3038'];
	list($sTabla3038, $sDebugTabla)=f3038_TablaDetalleV2($aParametros3038, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Palabras clave
	$aParametros3035[0]=$_REQUEST['saiu31id'];
	$aParametros3035[100]=$idTercero;
	$aParametros3035[101]=$_REQUEST['paginaf3035'];
	$aParametros3035[102]=$_REQUEST['lppf3035'];
	//$aParametros3035[103]=$_REQUEST['bnombre3035'];
	//$aParametros3035[104]=$_REQUEST['blistar3035'];
	list($sTabla3035, $sDebugTabla)=f3035_TablaDetalleV2($aParametros3035, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3031']);
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
	if (window.document.frmedita.saiu31estado.value==0){
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
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3031.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3031.value;
		window.document.frmlista.nombrearchivo.value='Base de conocimiento';
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
		window.document.frmimpp.action='e3031.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3031.php';
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
	datos[1]=window.document.frmedita.saiu31consec.value;
	datos[2]=window.document.frmedita.saiu31version.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f3031_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.saiu31consec.value=String(llave1);
	window.document.frmedita.saiu31version.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3031(llave1){
	window.document.frmedita.saiu31id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu31idtemageneral(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu31idunidadresp.value;
	document.getElementById('div_saiu31idtemageneral').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu31idtemageneral" name="saiu31idtemageneral" type="hidden" value="" />';
	xajax_f3031_Combosaiu31idtemageneral(params);
	}
function paginarf3031(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3031.value;
	params[102]=window.document.frmedita.lppf3031.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.bestado.value;
	//document.getElementById('div_f3031detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3031" name="paginaf3031" type="hidden" value="'+params[101]+'" /><input id="lppf3031" name="lppf3031" type="hidden" value="'+params[102]+'" />';
	xajax_f3031_HtmlTabla(params);
	}
function enviacerrar(){
	if (confirm('Esta seguro de enviar el registro para revision?\nluego de publicado no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
		}
	}
function enviaabrir(){
	if (confirm('Esta seguro de despublicar el registro?\nesto le permite volver a modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
		}
	}
function enviapublicar(){
	if (confirm('Esta seguro de publicar el registro?\nluego de publicado no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=18;
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
	document.getElementById("saiu31consec").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3031_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu31usuario'){
		ter_traerxid('saiu31usuario', sValor);
		}
	if (sCampo=='saiu31usuarioaprueba'){
		ter_traerxid('saiu31usuarioaprueba', sValor);
		}
	if (sCampo=='saiu38usuario'){
		ter_traerxid('saiu38usuario', sValor);
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
// -->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>ckeditor5/ckeditor.js"></script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js3032.js"></script>
<script language="javascript" src="jsi/js3033.js"></script>
<script language="javascript" src="jsi/js3038.js"></script>
<script language="javascript" src="jsi/js3035.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3031.php" target="_blank">
<input id="r" name="r" type="hidden" value="3031" />
<input id="id3031" name="id3031" type="hidden" value="<?php echo $_REQUEST['saiu31id']; ?>" />
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
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu31estado']==0){
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
		if ($_REQUEST['saiu31estado']==1){
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
switch ($_REQUEST['saiu31estado']){
	case 0:
	case 1:
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	break;
	}
switch ($_REQUEST['saiu31estado']){
	case 0:
	if ($_REQUEST['paso']>0){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Pasar a revisi&oacute;n" value="Pasar a revisi&oacute;n"/>
<?php
		}
	break;
	case 1:
	?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviapublicar();" title="Publicar" value="Publicar"/>
<?php
	break;
	case 7:
	if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Despublicar" value="Despublicar"/>
<?php
		}
	break;
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
echo '<h2>'.$ETI['titulo_3031'].'</h2>';
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
<input id="boculta3031" name="boculta3031" type="hidden" value="<?php echo $_REQUEST['boculta3031']; ?>" />
<label class="Label30">
<input id="btexpande3031" name="btexpande3031" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3031,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3031']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3031" name="btrecoge3031" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3031,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3031']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3031" style="display:<?php if ($_REQUEST['boculta3031']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label160">
<?php
echo $ETI['saiu31consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu31consec" name="saiu31consec" type="text" value="<?php echo $_REQUEST['saiu31consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu31consec', $_REQUEST['saiu31consec'], formato_numero($_REQUEST['saiu31consec']));
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
<input id="saiu31version" name="saiu31version" type="hidden" value="<?php echo $_REQUEST['saiu31version']; ?>"/>
<label class="Label60">
<?php
echo $ETI['saiu31id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu31id', $_REQUEST['saiu31id'], formato_numero($_REQUEST['saiu31id']));
?>
</label>
<label>
<div id="div_saiu31estado">
<?php
echo $html_saiu31estado;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu31idunidadresp'];
?>
</label>
<label>
<?php
echo $html_saiu31idunidadresp;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu31idtemageneral'];
?>
</label>
<label class="Label600">
<div id="div_saiu31idtemageneral">
<?php
echo $html_saiu31idtemageneral;
?>
</div>
</label>
<label class="L">
<?php
echo $ETI['saiu31titulo'];
?>

<input id="saiu31titulo" name="saiu31titulo" type="text" value="<?php echo $_REQUEST['saiu31titulo']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31titulo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['saiu31resumen'];
?>

<input id="saiu31resumen" name="saiu31resumen" type="text" value="<?php echo $_REQUEST['saiu31resumen']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31resumen']; ?>"/>
</label>
<label class="txtAreaL">
<?php
echo $ETI['saiu31contenido'];
?>
<div id="ckeditor">
<textarea id="saiu31contenido" name="saiu31contenido" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31contenido']; ?>"><?php echo $_REQUEST['saiu31contenido']; ?></textarea>
</div>
</label>
<div class="salto1px"></div>
<label class="txtAreaL">
<?php
echo $ETI['saiu31terminos'];
?>
<div id="ckeditor2">
<textarea id="saiu31terminos" name="saiu31terminos" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31terminos']; ?>"><?php echo $_REQUEST['saiu31terminos']; ?></textarea>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu31temporal'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31temporal;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu31fechaini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu31fechaini', $_REQUEST['saiu31fechaini'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu31fechaini_hoy" name="bsaiu31fechaini_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu31fechaini','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu31fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu31fechafin', $_REQUEST['saiu31fechafin'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu31fechafin_hoy" name="bsaiu31fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu31fechafin','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu31cobertura'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu31cobertura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu31entornodeuso'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu31entornodeuso;
?>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_aplica'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu31aplicaaspirante'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicaaspirante;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu31aplicaestudiante'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicaestudiante;
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu31aplicaegresado'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicaegresado;
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu31aplicadocentes'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicadocentes;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu31aplicaadministra'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicaadministra;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu31aplicaotros'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicaotros;
?>
</label>
<div class="salto1px"></div>
</div>

<label class="L">
<?php
echo $ETI['saiu31enlaceinfo'];
?>

<input id="saiu31enlaceinfo" name="saiu31enlaceinfo" type="text" value="<?php echo $_REQUEST['saiu31enlaceinfo']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31enlaceinfo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['saiu31enlaceproceso'];
?>

<input id="saiu31enlaceproceso" name="saiu31enlaceproceso" type="text" value="<?php echo $_REQUEST['saiu31enlaceproceso']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu31enlaceproceso']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['saiu31aplicanotificacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu31aplicanotificacion;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu31fechaparanotificar'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu31fechaparanotificar', $_REQUEST['saiu31fechaparanotificar'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu31fechaparanotificar_hoy" name="bsaiu31fechaparanotificar_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu31fechaparanotificar','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label200">
<?php
echo $ETI['saiu31prioridadnotifica'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu31prioridadnotifica;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu31usuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu31usuario" name="saiu31usuario" type="hidden" value="<?php echo $_REQUEST['saiu31usuario']; ?>"/>
<div id="div_saiu31usuario_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu31usuario', $_REQUEST['saiu31usuario_td'], $_REQUEST['saiu31usuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu31usuario" class="L"><?php echo $saiu31usuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu31fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu31fecha', $_REQUEST['saiu31fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu31fecha_hoy" name="bsaiu31fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu31fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu31usuarioaprueba'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu31usuarioaprueba" name="saiu31usuarioaprueba" type="hidden" value="<?php echo $_REQUEST['saiu31usuarioaprueba']; ?>"/>
<div id="div_saiu31usuarioaprueba_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu31usuarioaprueba', $_REQUEST['saiu31usuarioaprueba_td'], $_REQUEST['saiu31usuarioaprueba_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu31usuarioaprueba" class="L"><?php echo $saiu31usuarioaprueba_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu31fechaaprueba'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('saiu31fechaaprueba', $_REQUEST['saiu31fechaaprueba'], fecha_DesdeNumero($_REQUEST['saiu31fechaaprueba']));
//echo html_FechaEnNumero('saiu31fechaaprueba', $_REQUEST['saiu31fechaaprueba']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 3032 Temas asociados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3032'];
?>
</label>
<input id="boculta3032" name="boculta3032" type="hidden" value="<?php echo $_REQUEST['boculta3032']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3032" name="btexcel3032" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3032();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3032" name="btexpande3032" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3032,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3032']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3032" name="btrecoge3032" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3032,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3032']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3032" style="display:<?php if ($_REQUEST['boculta3032']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu32idtema'];
?>
</label>
<label>
<div id="div_saiu32idtema">
<?php
echo $html_saiu32idtema;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['saiu32id'];
?>
</label>
<label class="Label60"><div id="div_saiu32id">
<?php
	echo html_oculto('saiu32id', $_REQUEST['saiu32id'], formato_numero($_REQUEST['saiu32id']));
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu32activo'];
?>
</label>
<label>
<?php
echo $html_saiu32activo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu32porcentaje'];
?>
</label>
<label class="Label130">
<input id="saiu32porcentaje" name="saiu32porcentaje" type="text" value="<?php echo $_REQUEST['saiu32porcentaje']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu32porcpalclave'];
?>
</label>
<label class="Label130"><div id="div_saiu32porcpalclave">
<?php
echo html_oculto('saiu32porcpalclave', $_REQUEST['saiu32porcpalclave']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3032" name="bguarda3032" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3032()" title="<?php echo $ETI['bt_mini_guardar_3032']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3032" name="blimpia3032" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3032()" title="<?php echo $ETI['bt_mini_limpiar_3032']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3032" name="belimina3032" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3032()" title="<?php echo $ETI['bt_mini_eliminar_3032']; ?>" style="display:<?php if ((int)$_REQUEST['saiu32id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3032
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f3032detalle">
<?php
echo $sTabla3032;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3032 Temas asociados
?>
<?php
// -- Inicia Grupo campos 3033 Palabras claves
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3033'];
?>
</label>
<input id="boculta3033" name="boculta3033" type="hidden" value="<?php echo $_REQUEST['boculta3033']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3033" name="btexcel3033" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3033();" title="Exportar"/>
</label>
<label class="Label30"></label>
-->
<label class="Label30">
<input id="btMas3033" name="btMas3033" type="button" value="Aumentar uno" class="btMiniMas" onclick="masuno3033();" title="Aumentar una fila"/>
</label>
</div>
<div class="salto1px"></div>
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
<input id="bnombre3033" name="bnombre3033" type="text" value="<?php echo $_REQUEST['bnombre3033']; ?>" onchange="paginarf3033()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3033;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f3033detalle">
<?php
echo $sTabla3033;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3033 Palabras claves
?>
<?php
// -- Inicia Grupo campos 3035 Cobertura
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3035'];
?>
</label>
<input id="boculta3035" name="boculta3035" type="hidden" value="<?php echo $_REQUEST['boculta3035']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3035" name="btexcel3035" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3035();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3035" name="btexpande3035" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3035,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3035']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3035" name="btrecoge3035" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3035,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3035']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3035" style="display:<?php if ($_REQUEST['boculta3035']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu35idzona'];
?>
</label>
<label>
<div id="div_saiu35idzona">
<?php
echo $html_saiu35idzona;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu35idcentro'];
?>
</label>
<label>
<div id="div_saiu35idcentro">
<?php
echo $html_saiu35idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu35activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu35activo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu35id'];
?>
</label>
<label class="Label60"><div id="div_saiu35id">
<?php
	echo html_oculto('saiu35id', $_REQUEST['saiu35id'], formato_numero($_REQUEST['saiu35id']));
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3035" name="bguarda3035" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3035()" title="<?php echo $ETI['bt_mini_guardar_3035']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3035" name="blimpia3035" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3035()" title="<?php echo $ETI['bt_mini_limpiar_3035']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3035" name="belimina3035" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3035()" title="<?php echo $ETI['bt_mini_eliminar_3035']; ?>" style="display:<?php if ((int)$_REQUEST['saiu35id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3035
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f3035detalle">
<?php
echo $sTabla3035;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3035 Cobertura
?>
<?php
// -- Inicia Grupo campos 3038 Cambios de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3038'];
?>
</label>
<input id="boculta3038" name="boculta3038" type="hidden" value="<?php echo $_REQUEST['boculta3038']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div id="div_f3038detalle">
<?php
echo $sTabla3038;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3038 Cambios de estado
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3031
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
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3031()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['saiu31estado'];
?>
</label>
<label class="Label160">
<?php
echo $html_bestado;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3031detalle">
<?php
echo $sTabla3031;
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
echo $ETI['msg_saiu31consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu31consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu31consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu31consec_nuevo" name="saiu31consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu31consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3031" name="titulo_3031" type="hidden" value="<?php echo $ETI['titulo_3031']; ?>" />
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
echo '<h2>'.$ETI['titulo_3031'].'</h2>';
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
echo '<h2>'.$ETI['titulo_3031'].'</h2>';
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
if ($_REQUEST['saiu31estado']==0){
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
ClassicEditor.create(document.querySelector('#ckeditor'));
ClassicEditor.create(document.querySelector('#ckeditor2'));
$().ready(function(){
$("#saiu31idtemageneral").chosen();
$("#saiu31idunidadresp").chosen();
<?php
if ($_REQUEST['paso']!=0){
?>
$("#saiu32idtema").chosen();
<?php
	}
?>
});
-->
</script>
<script language="javascript" src="ac_3031.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>