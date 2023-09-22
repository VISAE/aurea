<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
*/
/** Archivo saiuchat.php.
* Modulo 3020 saiu20correo.
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
$iCodModulo=3020;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
require $mensajes_todas;
require $mensajes_3020;
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
		header('Location:noticia.php?ret=saiuchat.php');
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
require $mensajes_3000;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3020 saiu20correos
require 'lib3020.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20idcentro');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20coddepto');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20codciudad');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3020_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3020_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3020_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3020_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
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
if (isset($_REQUEST['paginaf3020'])==0){$_REQUEST['paginaf3020']=1;}
if (isset($_REQUEST['lppf3020'])==0){$_REQUEST['lppf3020']=20;}
if (isset($_REQUEST['boculta3020'])==0){$_REQUEST['boculta3020']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu20agno'])==0){$_REQUEST['saiu20agno']='';}
if (isset($_REQUEST['saiu20mes'])==0){$_REQUEST['saiu20mes']='';}
if (isset($_REQUEST['saiu20tiporadicado'])==0){$_REQUEST['saiu20tiporadicado']=1;}
if (isset($_REQUEST['saiu20consec'])==0){$_REQUEST['saiu20consec']='';}
if (isset($_REQUEST['saiu20consec_nuevo'])==0){$_REQUEST['saiu20consec_nuevo']='';}
if (isset($_REQUEST['saiu20id'])==0){$_REQUEST['saiu20id']='';}
if (isset($_REQUEST['saiu20dia'])==0){$_REQUEST['saiu20dia']=fecha_dia();}
if (isset($_REQUEST['saiu20hora'])==0){$_REQUEST['saiu20hora']=fecha_hora();}
if (isset($_REQUEST['saiu20minuto'])==0){$_REQUEST['saiu20minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu20estado'])==0){$_REQUEST['saiu20estado']=0;}
if (isset($_REQUEST['saiu20idcorreo'])==0){$_REQUEST['saiu20idcorreo']='';}
if (isset($_REQUEST['saiu20idsolicitante'])==0){$_REQUEST['saiu20idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu20idsolicitante_td'])==0){$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu20idsolicitante_doc'])==0){$_REQUEST['saiu20idsolicitante_doc']='';}
if (isset($_REQUEST['saiu20tipointeresado'])==0){$_REQUEST['saiu20tipointeresado']='';}
if (isset($_REQUEST['saiu20clasesolicitud'])==0){$_REQUEST['saiu20clasesolicitud']='';}
if (isset($_REQUEST['saiu20tiposolicitud'])==0){$_REQUEST['saiu20tiposolicitud']='';}
if (isset($_REQUEST['saiu20temasolicitud'])==0){$_REQUEST['saiu20temasolicitud']='';}
if (isset($_REQUEST['saiu20idzona'])==0){$_REQUEST['saiu20idzona']='';}
if (isset($_REQUEST['saiu20idcentro'])==0){$_REQUEST['saiu20idcentro']='';}
if (isset($_REQUEST['saiu20codpais'])==0){$_REQUEST['saiu20codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu20coddepto'])==0){$_REQUEST['saiu20coddepto']='';}
if (isset($_REQUEST['saiu20codciudad'])==0){$_REQUEST['saiu20codciudad']='';}
if (isset($_REQUEST['saiu20idescuela'])==0){$_REQUEST['saiu20idescuela']='';}
if (isset($_REQUEST['saiu20idprograma'])==0){$_REQUEST['saiu20idprograma']='';}
if (isset($_REQUEST['saiu20idperiodo'])==0){$_REQUEST['saiu20idperiodo']='';}
if (isset($_REQUEST['saiu20idpqrs'])==0){$_REQUEST['saiu20idpqrs']=0;}
if (isset($_REQUEST['saiu20detalle'])==0){$_REQUEST['saiu20detalle']='';}
if (isset($_REQUEST['saiu20horafin'])==0){$_REQUEST['saiu20horafin']='';}
if (isset($_REQUEST['saiu20minutofin'])==0){$_REQUEST['saiu20minutofin']='';}
if (isset($_REQUEST['saiu20paramercadeo'])==0){$_REQUEST['saiu20paramercadeo']='';}
if (isset($_REQUEST['saiu20idresponsable'])==0){$_REQUEST['saiu20idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu20idresponsable_td'])==0){$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu20idresponsable_doc'])==0){$_REQUEST['saiu20idresponsable_doc']='';}
if (isset($_REQUEST['saiu20tiemprespdias'])==0){$_REQUEST['saiu20tiemprespdias']='';}
if (isset($_REQUEST['saiu20tiempresphoras'])==0){$_REQUEST['saiu20tiempresphoras']='';}
if (isset($_REQUEST['saiu20tiemprespminutos'])==0){$_REQUEST['saiu20tiemprespminutos']='';}
if (isset($_REQUEST['saiu20solucion'])==0){$_REQUEST['saiu20solucion']='';}
if (isset($_REQUEST['saiu20idcaso'])==0){$_REQUEST['saiu20idcaso']=0;}
if (isset($_REQUEST['saiu20respuesta'])==0){$_REQUEST['saiu20respuesta']='';}
if (isset($_REQUEST['saiu20correoorigen'])==0){$_REQUEST['saiu20correoorigen']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=fecha_agno();}
if (isset($_REQUEST['blistar2'])==0){$_REQUEST['blistar2']=1;}
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
if (isset($_REQUEST['vdidcorreo'])==0){
	$sVr='';
	$sSQL='SELECT saiu27id FROM saiu57correos WHERE saiu57vigente=1 ORDER BY saiu57orden, saiu57consec';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['saiu57id'];
		}
	$_REQUEST['vdidcorreo']=$sVr;
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idsolicitante_doc']='';
	$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsable_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu20agno='.$_REQUEST['saiu20agno'].' AND saiu20mes='.$_REQUEST['saiu20mes'].' AND saiu20tiporadicado='.$_REQUEST['saiu20tiporadicado'].' AND saiu20consec='.$_REQUEST['saiu20consec'].'';
		}else{
		$sSQLcondi='saiu20id='.$_REQUEST['saiu20id'].'';
		}
	$sSQL='SELECT * FROM saiu20correo_'.$_REQUEST['saiu20agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu20agno']=$fila['saiu20agno'];
		$_REQUEST['saiu20mes']=$fila['saiu20mes'];
		$_REQUEST['saiu20tiporadicado']=$fila['saiu20tiporadicado'];
		$_REQUEST['saiu20consec']=$fila['saiu20consec'];
		$_REQUEST['saiu20id']=$fila['saiu20id'];
		$_REQUEST['saiu20dia']=$fila['saiu20dia'];
		$_REQUEST['saiu20hora']=$fila['saiu20hora'];
		$_REQUEST['saiu20minuto']=$fila['saiu20minuto'];
		$_REQUEST['saiu20estado']=$fila['saiu20estado'];
		$_REQUEST['saiu20idcorreo']=$fila['saiu20idcorreo'];
		$_REQUEST['saiu20idsolicitante']=$fila['saiu20idsolicitante'];
		$_REQUEST['saiu20tipointeresado']=$fila['saiu20tipointeresado'];
		$_REQUEST['saiu20clasesolicitud']=$fila['saiu20clasesolicitud'];
		$_REQUEST['saiu20tiposolicitud']=$fila['saiu20tiposolicitud'];
		$_REQUEST['saiu20temasolicitud']=$fila['saiu20temasolicitud'];
		$_REQUEST['saiu20idzona']=$fila['saiu20idzona'];
		$_REQUEST['saiu20idcentro']=$fila['saiu20idcentro'];
		$_REQUEST['saiu20codpais']=$fila['saiu20codpais'];
		$_REQUEST['saiu20coddepto']=$fila['saiu20coddepto'];
		$_REQUEST['saiu20codciudad']=$fila['saiu20codciudad'];
		$_REQUEST['saiu20idescuela']=$fila['saiu20idescuela'];
		$_REQUEST['saiu20idprograma']=$fila['saiu20idprograma'];
		$_REQUEST['saiu20idperiodo']=$fila['saiu20idperiodo'];
		$_REQUEST['saiu20idpqrs']=$fila['saiu20idpqrs'];
		$_REQUEST['saiu20detalle']=$fila['saiu20detalle'];
		$_REQUEST['saiu20horafin']=$fila['saiu20horafin'];
		$_REQUEST['saiu20minutofin']=$fila['saiu20minutofin'];
		$_REQUEST['saiu20paramercadeo']=$fila['saiu20paramercadeo'];
		$_REQUEST['saiu20idresponsable']=$fila['saiu20idresponsable'];
		$_REQUEST['saiu20tiemprespdias']=$fila['saiu20tiemprespdias'];
		$_REQUEST['saiu20tiempresphoras']=$fila['saiu20tiempresphoras'];
		$_REQUEST['saiu20tiemprespminutos']=$fila['saiu20tiemprespminutos'];
		$_REQUEST['saiu20solucion']=$fila['saiu20solucion'];
		$_REQUEST['saiu20idcaso']=$fila['saiu20idcaso'];
		$_REQUEST['saiu20respuesta']=$fila['saiu20respuesta'];
		$_REQUEST['saiu20correoorigen']=$fila['saiu20correoorigen'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3020']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=9;
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
		$_REQUEST['saiu20estado']=7;
		}else{
		$sSQL='UPDATE saiu20correo_'.$_REQUEST['saiu20agno'].' SET saiu20estado=2 WHERE saiu20id='.$_REQUEST['saiu20id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu20id'], 'Abre Registro de conversacion chat', $objDB);
		$_REQUEST['saiu20estado']=2;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3020_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
	$_REQUEST['saiu20consec_nuevo']=numeros_validar($_REQUEST['saiu20consec_nuevo']);
	if ($_REQUEST['saiu20consec_nuevo']==''){$sError=$ERR['saiu20consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu20id FROM saiu20correo_'.$_REQUEST['saiu20agno'].' WHERE saiu20consec='.$_REQUEST['saiu20consec_nuevo'].' AND saiu20tiporadicado='.$_REQUEST['saiu20tiporadicado'].' AND saiu20mes='.$_REQUEST['saiu20mes'].' AND saiu20agno='.$_REQUEST['saiu20agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu20consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu20correo_'.$_REQUEST['saiu20agno'].' SET saiu20consec='.$_REQUEST['saiu20consec_nuevo'].' WHERE saiu20id='.$_REQUEST['saiu20id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu20consec'].' a '.$_REQUEST['saiu20consec_nuevo'].'';
		$_REQUEST['saiu20consec']=$_REQUEST['saiu20consec_nuevo'];
		$_REQUEST['saiu20consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu20id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3020_db_Eliminar($_REQUEST['saiu20agno'], $_REQUEST['saiu20id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu20agno']=fecha_agno();
	$_REQUEST['saiu20mes']=fecha_mes();
	$_REQUEST['saiu20tiporadicado']=1;
	$_REQUEST['saiu20consec']='';
	$_REQUEST['saiu20consec_nuevo']='';
	$_REQUEST['saiu20id']='';
	$_REQUEST['saiu20dia']=fecha_dia();
	$_REQUEST['saiu20hora']='';
	$_REQUEST['saiu20minuto']='';
	$_REQUEST['saiu20estado']=4;
	if ($_REQUEST['saiu20idcorreo']==''){
		$_REQUEST['saiu20idcorreo']=$_REQUEST['vdidcorreo'];
		}
	$_REQUEST['saiu20idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idsolicitante_doc']='';
	$_REQUEST['saiu20tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu20clasesolicitud']='';
	$_REQUEST['saiu20tiposolicitud']='';
	$_REQUEST['saiu20temasolicitud']='';
	$_REQUEST['saiu20idzona']='';
	$_REQUEST['saiu20idcentro']='';
	$_REQUEST['saiu20codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu20coddepto']='';
	$_REQUEST['saiu20codciudad']='';
	$_REQUEST['saiu20idescuela']='';
	$_REQUEST['saiu20idprograma']='';
	$_REQUEST['saiu20idperiodo']='';
	$_REQUEST['saiu20idpqrs']=0;
	$_REQUEST['saiu20detalle']='';
	$_REQUEST['saiu20horafin']='';
	$_REQUEST['saiu20minutofin']='';
	$_REQUEST['saiu20paramercadeo']=0;
	$_REQUEST['saiu20idresponsable']=$idTercero;
	$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsable_doc']='';
	$_REQUEST['saiu20tiemprespdias']='';
	$_REQUEST['saiu20tiempresphoras']='';
	$_REQUEST['saiu20tiemprespminutos']='';
	$_REQUEST['saiu20solucion']=0;
	$_REQUEST['saiu20idcaso']=0;
	$_REQUEST['saiu20respuesta']='';
	$_REQUEST['saiu20correoorigen']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$iAgno=fecha_agno();
$sTabla='saiu20correo_'.$iAgno;
if (!$objDB->bexistetabla($sTabla)){
	list($sErrorT, $sDebugT)=f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
	}
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
list($saiu20estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu20estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu20estado=html_oculto('saiu20estado', $_REQUEST['saiu20estado'], $saiu20estado_nombre);
$objCombos->nuevo('saiu20idcorreo', $_REQUEST['saiu20idcorreo'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu57id AS id, saiu57titulo AS nombre FROM saiu57correos WHERE saiu57vigente=1 ORDER BY saiu57orden';
$html_saiu20idcorreo=$objCombos->html($sSQL, $objDB);
list($saiu20idsolicitante_rs, $_REQUEST['saiu20idsolicitante'], $_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'])=html_tercero($_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'], $_REQUEST['saiu20idsolicitante'], 0, $objDB);
$objCombos->nuevo('saiu20tipointeresado', $_REQUEST['saiu20tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
$html_saiu20tipointeresado=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu20temasolicitud', $_REQUEST['saiu20temasolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol WHERE saiu03id>0 AND saiu03ordenchat<9 ORDER BY saiu03ordenchat, saiu03titulo';
$html_saiu20temasolicitud=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu20idzona', $_REQUEST['saiu20idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu20idcentro();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_saiu20idzona=$objCombos->html($sSQL, $objDB);
$html_saiu20idcentro=f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, $_REQUEST['saiu20idcentro'], $_REQUEST['saiu20idzona']);
$objCombos->nuevo('saiu20codpais', $_REQUEST['saiu20codpais'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu20coddepto();';
$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_saiu20codpais=$objCombos->html($sSQL, $objDB);
$html_saiu20coddepto=f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, $_REQUEST['saiu20coddepto'], $_REQUEST['saiu20codpais']);
$html_saiu20codciudad=f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, $_REQUEST['saiu20codciudad'], $_REQUEST['saiu20coddepto']);
$objCombos->nuevo('saiu20idescuela', $_REQUEST['saiu20idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu20idprograma();';
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 ORDER BY core12tieneestudiantes DESC, core12nombre';
$html_saiu20idescuela=$objCombos->html($sSQL, $objDB);
$html_saiu20idprograma=f3020_HTMLComboV2_saiu20idprograma($objDB, $objCombos, $_REQUEST['saiu20idprograma'], $_REQUEST['saiu20idescuela']);
$objCombos->nuevo('saiu20idperiodo', $_REQUEST['saiu20idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL=f146_ConsultaCombo('exte02id>0');
$html_saiu20idperiodo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu20paramercadeo', $_REQUEST['saiu20paramercadeo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu20paramercadeo, $isaiu20paramercadeo);
$html_saiu20paramercadeo=$objCombos->html('', $objDB);
list($saiu20idresponsable_rs, $_REQUEST['saiu20idresponsable'], $_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'])=html_tercero($_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'], $_REQUEST['saiu20idresponsable'], 0, $objDB);
$objCombos->nuevo('saiu20solucion', $_REQUEST['saiu20solucion'], true, $asaiu20solucion[0], 0);
//$objCombos->addItem(1, $ETI['si']);
$objCombos->addArreglo($asaiu20solucion, $isaiu20solucion);
$html_saiu20solucion=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_saiu20agno=f3020_HTMLComboV2_saiu20agno($objDB, $objCombos, $_REQUEST['saiu20agno']);
	$html_saiu20mes=f3020_HTMLComboV2_saiu20mes($objDB, $objCombos, $_REQUEST['saiu20mes']);
	$html_saiu20dia=html_ComboDia('saiu20dia', $_REQUEST['saiu20dia'], false);
	//$html_saiu20tiporadicado=f3020_HTMLComboV2_saiu20tiporadicado($objDB, $objCombos, $_REQUEST['saiu20tiporadicado']);
	}else{
	$saiu20agno_nombre=$_REQUEST['saiu20agno'];
	//$saiu20agno_nombre=$asaiu20agno[$_REQUEST['saiu20agno']];
	//list($saiu20agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu20agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20agno=html_oculto('saiu20agno', $_REQUEST['saiu20agno'], $saiu20agno_nombre);
	$saiu20mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu20mes']));
	//$saiu20mes_nombre=$asaiu20mes[$_REQUEST['saiu20mes']];
	//list($saiu20mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu20mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20mes=html_oculto('saiu20mes', $_REQUEST['saiu20mes'], $saiu20mes_nombre);
	$saiu20dia_nombre=$_REQUEST['saiu20dia'];
	$html_saiu20dia=html_oculto('saiu20dia', $_REQUEST['saiu20dia'], $saiu20dia_nombre);
	//list($saiu20tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu20tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_saiu20tiporadicado=html_oculto('saiu20tiporadicado', $_REQUEST['saiu20tiporadicado'], $saiu20tiporadicado_nombre);
	}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu20estado']>6){$bEnProceso=false;}
	}
if (true){
	$objCombos->nuevo('saiu20tiposolicitud', $_REQUEST['saiu20tiposolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
FROM saiu02tiposol AS TB, saiu01claseser AS T1 
WHERE TB.saiu02id>0 AND TB.saiu02ordenchat<9 AND TB.saiu02clasesol=T1.saiu01id 
ORDER BY TB.saiu02ordenchat, TB.saiu02titulo';
	$html_saiu20tiposolicitud=$objCombos->html($sSQL, $objDB);
	}else{
	list($saiu20tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu20tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20tiposolicitud=html_oculto('saiu20tiposolicitud', $_REQUEST['saiu20tiposolicitud'], $saiu20tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu20estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], false, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3020()';
$sSQL='SHOW TABLES LIKE "saiu20correo%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 13);
	$objCombos->addItem($sAgno, $sAgno);
	}
$html_blistar=$objCombos->html('', $objDB);
$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='paginarf3020()';
$aListar2=array();
$iListar2=0;
$objCombos->addArreglo($aListar2, $iListar2);
$html_blistar2=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3020, 1, $objDB, 'paginarf3020()');
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3020;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu20estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3020'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3020'];
$aParametros[102]=$_REQUEST['lppf3020'];
//$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['blistar2'];
list($sTabla3020, $sDebugTabla)=f3020_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu20agno'];
$aParametros3000[3]=$_REQUEST['saiu20id'];
$aParametros3000[100]=$_REQUEST['saiu20idsolicitante'];
$aParametros3000[101]=$_REQUEST['paginaf3000'];
$aParametros3000[102]=$_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla)=f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3020']);
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
	if (window.document.frmedita.saiu20estado.value<7){
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
		if (idcampo=='saiu20idsolicitante'){
			params[6]=3020;
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
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3020;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3020.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3020.value;
		window.document.frmlista.nombrearchivo.value='Sesiones de chat';
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
		window.document.frmimpp.action='e3020.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3020.php';
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
	datos[1]=window.document.frmedita.saiu20agno.value;
	datos[2]=window.document.frmedita.saiu20mes.value;
	datos[3]=window.document.frmedita.saiu20tiporadicado.value;
	datos[4]=window.document.frmedita.saiu20consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3020_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu20agno.value=String(llave1);
	window.document.frmedita.saiu20mes.value=String(llave2);
	window.document.frmedita.saiu20tiporadicado.value=String(llave3);
	window.document.frmedita.saiu20consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3020(llave1){
	window.document.frmedita.saiu20id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu20idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20idzona.value;
	document.getElementById('div_saiu20idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20idcentro" name="saiu20idcentro" type="hidden" value="" />';
	xajax_f3020_Combosaiu20idcentro(params);
	}
function carga_combo_saiu20coddepto(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20codpais.value;
	document.getElementById('div_saiu20coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20coddepto" name="saiu20coddepto" type="hidden" value="" />';
	xajax_f3020_Combosaiu20coddepto(params);
	}
function carga_combo_saiu20codciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20coddepto.value;
	document.getElementById('div_saiu20codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20codciudad" name="saiu20codciudad" type="hidden" value="" />';
	xajax_f3020_Combosaiu20codciudad(params);
	}
function carga_combo_saiu20idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20idescuela.value;
	document.getElementById('div_saiu20idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20idprograma" name="saiu20idprograma" type="hidden" value="" />';
	xajax_f3020_Combosaiu20idprograma(params);
	}
function paginarf3020(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3020.value;
	params[102]=window.document.frmedita.lppf3020.value;
	//params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.blistar2.value;
	//document.getElementById('div_f3020detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3020" name="paginaf3020" type="hidden" value="'+params[101]+'" /><input id="lppf3020" name="lppf3020" type="hidden" value="'+params[102]+'" />';
	xajax_f3020_HtmlTabla(params);
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
	document.getElementById("saiu20agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3020_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu20idsolicitante'){
		ter_traerxid('saiu20idsolicitante', sValor);
		}
	if (sCampo=='saiu20idresponsable'){
		ter_traerxid('saiu20idresponsable', sValor);
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
function paginarf3000(){
	var params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3020;
	params[2]=window.document.frmedita.saiu20agno.value;
	params[3]=window.document.frmedita.saiu20id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu20idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3020.php" target="_blank">
<input id="r" name="r" type="hidden" value="3020" />
<input id="id3020" name="id3020" type="hidden" value="<?php echo $_REQUEST['saiu20id']; ?>" />
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
<input id="vdidcorreo" name="vdidcorreo" type="hidden" value="<?php echo $_REQUEST['vdidcorreo']; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu20estado']<7){
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
		if ($_REQUEST['saiu20estado']>6){
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
if ($_REQUEST['saiu20estado']<7){
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
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
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
<input id="boculta3020" name="boculta3020" type="hidden" value="<?php echo $_REQUEST['boculta3020']; ?>" />
<label class="Label30">
<input id="btexpande3020" name="btexpande3020" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3020,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3020" name="btrecoge3020" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3020,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3020" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'block'; }else{echo 'none';} ?>;">
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
echo $html_saiu20dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu20mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu20agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu20dia.'/'.$html_saiu20mes.'/'.$html_saiu20agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu20hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu20hora">
<?php
echo html_HoraMin('saiu20hora', $_REQUEST['saiu20hora'], 'saiu20minuto', $_REQUEST['saiu20minuto']);
?>
</div>
<input id="saiu20tiporadicado" name="saiu20tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu20tiporadicado']; ?>"/>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu20consec" name="saiu20consec" type="text" value="<?php echo $_REQUEST['saiu20consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu20consec', $_REQUEST['saiu20consec'], formato_numero($_REQUEST['saiu20consec']));
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
echo $ETI['saiu20id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu20id', $_REQUEST['saiu20id'], formato_numero($_REQUEST['saiu20id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu20estado'];
?>
</label>
<label>
<div id="div_saiu20estado">
<?php
echo $html_saiu20estado;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idcorreo'];
?>
</label>
<label>
<?php
echo $html_saiu20idcorreo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu20idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu20idsolicitante" name="saiu20idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu20idsolicitante']; ?>"/>
<div id="div_saiu20idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu20idsolicitante', $_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu20idsolicitante" class="L"><?php echo $saiu20idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu20tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu20tipointeresado;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu20correoorigen'];
?>
<input id="saiu20correoorigen" name="saiu20correoorigen" type="text" value="<?php echo $_REQUEST['saiu20correoorigen']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20correoorigen']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu20idzona'];
?>
</label>
<label>
<?php
echo $html_saiu20idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idcentro'];
?>
</label>
<label>
<div id="div_saiu20idcentro">
<?php
echo $html_saiu20idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20codpais'];
?>
</label>
<label>
<?php
echo $html_saiu20codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20coddepto'];
?>
</label>
<label>
<div id="div_saiu20coddepto">
<?php
echo $html_saiu20coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20codciudad'];
?>
</label>
<label>
<div id="div_saiu20codciudad">
<?php
echo $html_saiu20codciudad;
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
<input id="saiu20clasesolicitud" name="saiu20clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu20clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu20tiposolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu20tiposolicitud;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20temasolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu20temasolicitud;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu20idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idprograma'];
?>
</label>
<label>
<div id="div_saiu20idprograma">
<?php
echo $html_saiu20idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu20idperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['saiu20detalle'];
?>
<textarea id="saiu20detalle" name="saiu20detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20detalle']; ?>"><?php echo $_REQUEST['saiu20detalle']; ?></textarea>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu20solucion'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu20solucion;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu20paramercadeo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu20paramercadeo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idcaso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu20idcaso', $_REQUEST['saiu20idcaso']);
?>
</label>
<div class="salto1px"></div>
<label class="Labe250">
<?php
echo $ETI['saiu20idpqrs'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu20idpqrs', $_REQUEST['saiu20idpqrs']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu20idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu20idresponsable" name="saiu20idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu20idresponsable']; ?>"/>
<div id="div_saiu20idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu20idresponsable', $_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu20idresponsable" class="L"><?php echo $saiu20idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu20horafin">
<?php
echo html_HoraMin('saiu20horafin', $_REQUEST['saiu20horafin'], 'saiu20minutofin', $_REQUEST['saiu20minutofin']);
?>
</div>
<?php
if ($_REQUEST['saiu20estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu20tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu20tiemprespdias'], $_REQUEST['saiu20tiempresphoras'], $_REQUEST['saiu20tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu20tiemprespdias" name="saiu20tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu20tiemprespdias']; ?>"/>
<input id="saiu20tiempresphoras" name="saiu20tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu20tiempresphoras']; ?>"/>
<input id="saiu20tiemprespminutos" name="saiu20tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu20tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu20estado']<7){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="<?php echo $ETI['saiu20cerrar']; ?>" class="BotonAzul" onclick="enviacerrar()" title="<?php echo $ETI['saiu20cerrar']; ?>"/>
</label>
<?php
		}
	}
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3020
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
<?php
if (false){
?>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3020()" autocomplete="off"/>
</label>
<?php
	}
?>
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
echo $ETI['saiu20agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3020detalle">
<?php
echo $sTabla3020;
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
echo $ETI['msg_saiu20consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu20consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu20consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu20consec_nuevo" name="saiu20consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu20consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3020" name="titulo_3020" type="hidden" value="<?php echo $ETI['titulo_3020']; ?>" />
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
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
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
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
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
if ($_REQUEST['saiu20estado']<7){
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
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function(){
$("#saiu20idcentro").chosen();
$("#saiu20coddepto").chosen();
$("#saiu20codciudad").chosen();
<?php
if ($bEnProceso){
?>
$("#saiu20tiposolicitud").chosen();
<?php
	}
?>
$("#saiu20temasolicitud").chosen();
$("#saiu20idprograma").chosen();
$("#saiu20idperiodo").chosen();
});
</script>
<script language="javascript" src="ac_3020.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>