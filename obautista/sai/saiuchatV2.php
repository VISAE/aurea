<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
*/
/** Archivo saiuchat.php.
* Modulo 3019 saiu19chat.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date domingo, 19 de julio de 2020
*/
$iCodModulo=3019;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
require $mensajes_todas;
require $mensajes_3019;
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
// -- 3019 saiu19chat
require 'lib3019.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19idcentro');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19coddepto');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19codciudad');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3019_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3019_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3019_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3019_HtmlBusqueda');
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
if (isset($_REQUEST['paginaf3019'])==0){$_REQUEST['paginaf3019']=1;}
if (isset($_REQUEST['lppf3019'])==0){$_REQUEST['lppf3019']=20;}
if (isset($_REQUEST['boculta3019'])==0){$_REQUEST['boculta3019']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu19agno'])==0){$_REQUEST['saiu19agno']='';}
if (isset($_REQUEST['saiu19mes'])==0){$_REQUEST['saiu19mes']='';}
if (isset($_REQUEST['saiu19tiporadicado'])==0){$_REQUEST['saiu19tiporadicado']=1;}
if (isset($_REQUEST['saiu19consec'])==0){$_REQUEST['saiu19consec']='';}
if (isset($_REQUEST['saiu19consec_nuevo'])==0){$_REQUEST['saiu19consec_nuevo']='';}
if (isset($_REQUEST['saiu19id'])==0){$_REQUEST['saiu19id']='';}
if (isset($_REQUEST['saiu19dia'])==0){$_REQUEST['saiu19dia']=fecha_dia();}
if (isset($_REQUEST['saiu19hora'])==0){$_REQUEST['saiu19hora']=fecha_hora();}
if (isset($_REQUEST['saiu19minuto'])==0){$_REQUEST['saiu19minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu19estado'])==0){$_REQUEST['saiu19estado']=0;}
if (isset($_REQUEST['saiu19idchat'])==0){$_REQUEST['saiu19idchat']='';}
if (isset($_REQUEST['saiu19idsolicitante'])==0){$_REQUEST['saiu19idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu19idsolicitante_td'])==0){$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu19idsolicitante_doc'])==0){$_REQUEST['saiu19idsolicitante_doc']='';}
if (isset($_REQUEST['saiu19tipointeresado'])==0){$_REQUEST['saiu19tipointeresado']='';}
if (isset($_REQUEST['saiu19clasesolicitud'])==0){$_REQUEST['saiu19clasesolicitud']='';}
if (isset($_REQUEST['saiu19tiposolicitud'])==0){$_REQUEST['saiu19tiposolicitud']='';}
if (isset($_REQUEST['saiu19temasolicitud'])==0){$_REQUEST['saiu19temasolicitud']='';}
if (isset($_REQUEST['saiu19idzona'])==0){$_REQUEST['saiu19idzona']='';}
if (isset($_REQUEST['saiu19idcentro'])==0){$_REQUEST['saiu19idcentro']='';}
if (isset($_REQUEST['saiu19codpais'])==0){$_REQUEST['saiu19codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu19coddepto'])==0){$_REQUEST['saiu19coddepto']='';}
if (isset($_REQUEST['saiu19codciudad'])==0){$_REQUEST['saiu19codciudad']='';}
if (isset($_REQUEST['saiu19idescuela'])==0){$_REQUEST['saiu19idescuela']='';}
if (isset($_REQUEST['saiu19idprograma'])==0){$_REQUEST['saiu19idprograma']='';}
if (isset($_REQUEST['saiu19idperiodo'])==0){$_REQUEST['saiu19idperiodo']='';}
if (isset($_REQUEST['saiu19numorigen'])==0){$_REQUEST['saiu19numorigen']='';}
if (isset($_REQUEST['saiu19idpqrs'])==0){$_REQUEST['saiu19idpqrs']=0;}
if (isset($_REQUEST['saiu19detalle'])==0){$_REQUEST['saiu19detalle']='';}
if (isset($_REQUEST['saiu19horafin'])==0){$_REQUEST['saiu19horafin']='';}
if (isset($_REQUEST['saiu19minutofin'])==0){$_REQUEST['saiu19minutofin']='';}
if (isset($_REQUEST['saiu19paramercadeo'])==0){$_REQUEST['saiu19paramercadeo']='';}
if (isset($_REQUEST['saiu19idresponsable'])==0){$_REQUEST['saiu19idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu19idresponsable_td'])==0){$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu19idresponsable_doc'])==0){$_REQUEST['saiu19idresponsable_doc']='';}
if (isset($_REQUEST['saiu19tiemprespdias'])==0){$_REQUEST['saiu19tiemprespdias']='';}
if (isset($_REQUEST['saiu19tiempresphoras'])==0){$_REQUEST['saiu19tiempresphoras']='';}
if (isset($_REQUEST['saiu19tiemprespminutos'])==0){$_REQUEST['saiu19tiemprespminutos']='';}
if (isset($_REQUEST['saiu19solucion'])==0){$_REQUEST['saiu19solucion']='';}
if (isset($_REQUEST['saiu19idcaso'])==0){$_REQUEST['saiu19idcaso']=0;}
if (isset($_REQUEST['saiu19numsesionchat'])==0){$_REQUEST['saiu19numsesionchat']='';}
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
if (isset($_REQUEST['vdidchat'])==0){
	$sVr='';
	$sSQL='SELECT saiu27id FROM saiu27chats WHERE saiu27predet=1 ORDER BY saiu27orden, saiu27consec';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['saiu27id'];
		}
	$_REQUEST['vdidchat']=$sVr;
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idsolicitante_doc']='';
	$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsable_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu19agno='.$_REQUEST['saiu19agno'].' AND saiu19mes='.$_REQUEST['saiu19mes'].' AND saiu19tiporadicado='.$_REQUEST['saiu19tiporadicado'].' AND saiu19consec='.$_REQUEST['saiu19consec'].'';
		}else{
		$sSQLcondi='saiu19id='.$_REQUEST['saiu19id'].'';
		}
	$sSQL='SELECT * FROM saiu19chat_'.$_REQUEST['saiu19agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu19agno']=$fila['saiu19agno'];
		$_REQUEST['saiu19mes']=$fila['saiu19mes'];
		$_REQUEST['saiu19tiporadicado']=$fila['saiu19tiporadicado'];
		$_REQUEST['saiu19consec']=$fila['saiu19consec'];
		$_REQUEST['saiu19id']=$fila['saiu19id'];
		$_REQUEST['saiu19dia']=$fila['saiu19dia'];
		$_REQUEST['saiu19hora']=$fila['saiu19hora'];
		$_REQUEST['saiu19minuto']=$fila['saiu19minuto'];
		$_REQUEST['saiu19estado']=$fila['saiu19estado'];
		$_REQUEST['saiu19idchat']=$fila['saiu19idchat'];
		$_REQUEST['saiu19idsolicitante']=$fila['saiu19idsolicitante'];
		$_REQUEST['saiu19tipointeresado']=$fila['saiu19tipointeresado'];
		$_REQUEST['saiu19clasesolicitud']=$fila['saiu19clasesolicitud'];
		$_REQUEST['saiu19tiposolicitud']=$fila['saiu19tiposolicitud'];
		$_REQUEST['saiu19temasolicitud']=$fila['saiu19temasolicitud'];
		$_REQUEST['saiu19idzona']=$fila['saiu19idzona'];
		$_REQUEST['saiu19idcentro']=$fila['saiu19idcentro'];
		$_REQUEST['saiu19codpais']=$fila['saiu19codpais'];
		$_REQUEST['saiu19coddepto']=$fila['saiu19coddepto'];
		$_REQUEST['saiu19codciudad']=$fila['saiu19codciudad'];
		$_REQUEST['saiu19idescuela']=$fila['saiu19idescuela'];
		$_REQUEST['saiu19idprograma']=$fila['saiu19idprograma'];
		$_REQUEST['saiu19idperiodo']=$fila['saiu19idperiodo'];
		$_REQUEST['saiu19numorigen']=$fila['saiu19numorigen'];
		$_REQUEST['saiu19idpqrs']=$fila['saiu19idpqrs'];
		$_REQUEST['saiu19detalle']=$fila['saiu19detalle'];
		$_REQUEST['saiu19horafin']=$fila['saiu19horafin'];
		$_REQUEST['saiu19minutofin']=$fila['saiu19minutofin'];
		$_REQUEST['saiu19paramercadeo']=$fila['saiu19paramercadeo'];
		$_REQUEST['saiu19idresponsable']=$fila['saiu19idresponsable'];
		$_REQUEST['saiu19tiemprespdias']=$fila['saiu19tiemprespdias'];
		$_REQUEST['saiu19tiempresphoras']=$fila['saiu19tiempresphoras'];
		$_REQUEST['saiu19tiemprespminutos']=$fila['saiu19tiemprespminutos'];
		$_REQUEST['saiu19solucion']=$fila['saiu19solucion'];
		$_REQUEST['saiu19idcaso']=$fila['saiu19idcaso'];
		$_REQUEST['saiu19numsesionchat']=$fila['saiu19numsesionchat'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3019']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=9;
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
		$_REQUEST['saiu19estado']=7;
		}else{
		$sSQL='UPDATE saiu19chat_'.$_REQUEST['saiu19agno'].' SET saiu19estado=2 WHERE saiu19id='.$_REQUEST['saiu19id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu19id'], 'Abre Registro de conversacion chat', $objDB);
		$_REQUEST['saiu19estado']=2;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3019_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
	$_REQUEST['saiu19consec_nuevo']=numeros_validar($_REQUEST['saiu19consec_nuevo']);
	if ($_REQUEST['saiu19consec_nuevo']==''){$sError=$ERR['saiu19consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu19id FROM saiu19chat_'.$_REQUEST['saiu19agno'].' WHERE saiu19consec='.$_REQUEST['saiu19consec_nuevo'].' AND saiu19tiporadicado='.$_REQUEST['saiu19tiporadicado'].' AND saiu19mes='.$_REQUEST['saiu19mes'].' AND saiu19agno='.$_REQUEST['saiu19agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu19consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu19chat_'.$_REQUEST['saiu19agno'].' SET saiu19consec='.$_REQUEST['saiu19consec_nuevo'].' WHERE saiu19id='.$_REQUEST['saiu19id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu19consec'].' a '.$_REQUEST['saiu19consec_nuevo'].'';
		$_REQUEST['saiu19consec']=$_REQUEST['saiu19consec_nuevo'];
		$_REQUEST['saiu19consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu19id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3019_db_Eliminar($_REQUEST['saiu19agno'], $_REQUEST['saiu19id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu19agno']=fecha_agno();
	$_REQUEST['saiu19mes']=fecha_mes();
	$_REQUEST['saiu19tiporadicado']=1;
	$_REQUEST['saiu19consec']='';
	$_REQUEST['saiu19consec_nuevo']='';
	$_REQUEST['saiu19id']='';
	$_REQUEST['saiu19dia']=fecha_dia();
	$_REQUEST['saiu19hora']='';
	$_REQUEST['saiu19minuto']='';
	$_REQUEST['saiu19estado']=4;
	if ($_REQUEST['saiu19idchat']==''){
		$_REQUEST['saiu19idchat']=$_REQUEST['vdidchat'];
		}
	$_REQUEST['saiu19idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idsolicitante_doc']='';
	$_REQUEST['saiu19tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu19clasesolicitud']='';
	$_REQUEST['saiu19tiposolicitud']='';
	$_REQUEST['saiu19temasolicitud']='';
	$_REQUEST['saiu19idzona']='';
	$_REQUEST['saiu19idcentro']='';
	$_REQUEST['saiu19codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu19coddepto']='';
	$_REQUEST['saiu19codciudad']='';
	$_REQUEST['saiu19idescuela']='';
	$_REQUEST['saiu19idprograma']='';
	$_REQUEST['saiu19idperiodo']='';
	$_REQUEST['saiu19numorigen']='';
	$_REQUEST['saiu19idpqrs']=0;
	$_REQUEST['saiu19detalle']='';
	$_REQUEST['saiu19horafin']='';
	$_REQUEST['saiu19minutofin']='';
	$_REQUEST['saiu19paramercadeo']=0;
	$_REQUEST['saiu19idresponsable']=$idTercero;
	$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsable_doc']='';
	$_REQUEST['saiu19tiemprespdias']='';
	$_REQUEST['saiu19tiempresphoras']='';
	$_REQUEST['saiu19tiemprespminutos']='';
	$_REQUEST['saiu19solucion']=0;
	$_REQUEST['saiu19idcaso']=0;
	$_REQUEST['saiu19numsesionchat']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$iAgno=fecha_agno();
$sTabla='saiu19chat_'.$iAgno;
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
list($saiu19estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu19estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu19estado=html_oculto('saiu19estado', $_REQUEST['saiu19estado'], $saiu19estado_nombre);
$objCombos->nuevo('saiu19idchat', $_REQUEST['saiu19idchat'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu27id AS id, saiu27nombre AS nombre FROM saiu27chats ORDER BY saiu27nombre';
$html_saiu19idchat=$objCombos->html($sSQL, $objDB);
list($saiu19idsolicitante_rs, $_REQUEST['saiu19idsolicitante'], $_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'])=html_tercero($_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'], $_REQUEST['saiu19idsolicitante'], 0, $objDB);
$objCombos->nuevo('saiu19tipointeresado', $_REQUEST['saiu19tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
$html_saiu19tipointeresado=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu19temasolicitud', $_REQUEST['saiu19temasolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol WHERE saiu03id>0 AND saiu03ordenchat<9 ORDER BY saiu03ordenchat, saiu03titulo';
$html_saiu19temasolicitud=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu19idzona', $_REQUEST['saiu19idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu19idcentro();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_saiu19idzona=$objCombos->html($sSQL, $objDB);
$html_saiu19idcentro=f3019_HTMLComboV2_saiu19idcentro($objDB, $objCombos, $_REQUEST['saiu19idcentro'], $_REQUEST['saiu19idzona']);
$objCombos->nuevo('saiu19codpais', $_REQUEST['saiu19codpais'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu19coddepto();';
$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_saiu19codpais=$objCombos->html($sSQL, $objDB);
$html_saiu19coddepto=f3019_HTMLComboV2_saiu19coddepto($objDB, $objCombos, $_REQUEST['saiu19coddepto'], $_REQUEST['saiu19codpais']);
$html_saiu19codciudad=f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, $_REQUEST['saiu19codciudad'], $_REQUEST['saiu19coddepto']);
$objCombos->nuevo('saiu19idescuela', $_REQUEST['saiu19idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_saiu19idprograma();';
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 ORDER BY core12tieneestudiantes DESC, core12nombre';
$html_saiu19idescuela=$objCombos->html($sSQL, $objDB);
$html_saiu19idprograma=f3019_HTMLComboV2_saiu19idprograma($objDB, $objCombos, $_REQUEST['saiu19idprograma'], $_REQUEST['saiu19idescuela']);
$objCombos->nuevo('saiu19idperiodo', $_REQUEST['saiu19idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('0', $ETI['msg_na']);
$sSQL=f146_ConsultaCombo('exte02id>0');
$html_saiu19idperiodo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu19paramercadeo', $_REQUEST['saiu19paramercadeo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu19paramercadeo, $isaiu19paramercadeo);
$html_saiu19paramercadeo=$objCombos->html('', $objDB);
list($saiu19idresponsable_rs, $_REQUEST['saiu19idresponsable'], $_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'])=html_tercero($_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'], $_REQUEST['saiu19idresponsable'], 0, $objDB);
$objCombos->nuevo('saiu19solucion', $_REQUEST['saiu19solucion'], true, $asaiu19solucion[0], 0);
//$objCombos->addItem(1, $ETI['si']);
$objCombos->addArreglo($asaiu19solucion, $isaiu19solucion);
$html_saiu19solucion=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_saiu19agno=f3019_HTMLComboV2_saiu19agno($objDB, $objCombos, $_REQUEST['saiu19agno']);
	$html_saiu19mes=f3019_HTMLComboV2_saiu19mes($objDB, $objCombos, $_REQUEST['saiu19mes']);
	$html_saiu19dia=html_ComboDia('saiu19dia', $_REQUEST['saiu19dia'], false);
	//$html_saiu19tiporadicado=f3019_HTMLComboV2_saiu19tiporadicado($objDB, $objCombos, $_REQUEST['saiu19tiporadicado']);
	}else{
	$saiu19agno_nombre=$_REQUEST['saiu19agno'];
	//$saiu19agno_nombre=$asaiu19agno[$_REQUEST['saiu19agno']];
	//list($saiu19agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu19agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19agno=html_oculto('saiu19agno', $_REQUEST['saiu19agno'], $saiu19agno_nombre);
	$saiu19mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu19mes']));
	//$saiu19mes_nombre=$asaiu19mes[$_REQUEST['saiu19mes']];
	//list($saiu19mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu19mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19mes=html_oculto('saiu19mes', $_REQUEST['saiu19mes'], $saiu19mes_nombre);
	$saiu19dia_nombre=$_REQUEST['saiu19dia'];
	$html_saiu19dia=html_oculto('saiu19dia', $_REQUEST['saiu19dia'], $saiu19dia_nombre);
	//list($saiu19tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu19tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_saiu19tiporadicado=html_oculto('saiu19tiporadicado', $_REQUEST['saiu19tiporadicado'], $saiu19tiporadicado_nombre);
	}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu19estado']>6){$bEnProceso=false;}
	}
if (true){
	$objCombos->nuevo('saiu19tiposolicitud', $_REQUEST['saiu19tiposolicitud'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
FROM saiu02tiposol AS TB, saiu01claseser AS T1 
WHERE TB.saiu02id>0 AND TB.saiu02ordenchat<9 AND TB.saiu02clasesol=T1.saiu01id 
ORDER BY TB.saiu02ordenchat, TB.saiu02titulo';
	$html_saiu19tiposolicitud=$objCombos->html($sSQL, $objDB);
	}else{
	list($saiu19tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu19tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19tiposolicitud=html_oculto('saiu19tiposolicitud', $_REQUEST['saiu19tiposolicitud'], $saiu19tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
$bConBotonAbandona=false;
$bConBotonCancela=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu19estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		$bConBotonAbandona=true;
		$bConBotonCancela=true;
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], false, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3019()';
$sSQL='SHOW TABLES LIKE "saiu19chat%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 11);
	$objCombos->addItem($sAgno, $sAgno);
	}
$html_blistar=$objCombos->html('', $objDB);
$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='paginarf3019()';
$objCombos->addArreglo($aListar2, $iListar2);
$html_blistar2=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3019, 1, $objDB, 'paginarf3019()');
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3019;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
// TODO
$objCombos->nuevo('canales', $_REQUEST['saiucanal'], false, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='cambiacanal()';
$objCombos->addArreglo($aCanal, $iCanal);
$html_saiucanal=$objCombos->html('', $objDB);
// TODO
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu19estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3019'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3019'];
$aParametros[102]=$_REQUEST['lppf3019'];
//$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['blistar2'];
list($sTabla3019, $sDebugTabla)=f3019_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu19agno'];
$aParametros3000[3]=$_REQUEST['saiu19id'];
$aParametros3000[100]=$_REQUEST['saiu19idsolicitante'];
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
forma_cabeceraV3($xajax, $ETI['titulo_3019']);
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
	if (window.document.frmedita.saiu19estado.value<7){
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
		if (idcampo=='saiu19idsolicitante'){
			params[6]=3019;
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
		params[6]=3019;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3019.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3019.value;
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
		window.document.frmimpp.action='e3019.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3019.php';
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
	datos[1]=window.document.frmedita.saiu19agno.value;
	datos[2]=window.document.frmedita.saiu19mes.value;
	datos[3]=window.document.frmedita.saiu19tiporadicado.value;
	datos[4]=window.document.frmedita.saiu19consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3019_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu19agno.value=String(llave1);
	window.document.frmedita.saiu19mes.value=String(llave2);
	window.document.frmedita.saiu19tiporadicado.value=String(llave3);
	window.document.frmedita.saiu19consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3019(llave1){
	window.document.frmedita.saiu19id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu19idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu19idzona.value;
	document.getElementById('div_saiu19idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19idcentro" name="saiu19idcentro" type="hidden" value="" />';
	xajax_f3019_Combosaiu19idcentro(params);
	}
function carga_combo_saiu19coddepto(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu19codpais.value;
	document.getElementById('div_saiu19coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19coddepto" name="saiu19coddepto" type="hidden" value="" />';
	xajax_f3019_Combosaiu19coddepto(params);
	}
function carga_combo_saiu19codciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu19coddepto.value;
	document.getElementById('div_saiu19codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19codciudad" name="saiu19codciudad" type="hidden" value="" />';
	xajax_f3019_Combosaiu19codciudad(params);
	}
function carga_combo_saiu19idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu19idescuela.value;
	document.getElementById('div_saiu19idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19idprograma" name="saiu19idprograma" type="hidden" value="" />';
	xajax_f3019_Combosaiu19idprograma(params);
	}
function paginarf3019(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3019.value;
	params[102]=window.document.frmedita.lppf3019.value;
	//params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.blistar2.value;
	//document.getElementById('div_f3019detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3019" name="paginaf3019" type="hidden" value="'+params[101]+'" /><input id="lppf3019" name="lppf3019" type="hidden" value="'+params[102]+'" />';
	xajax_f3019_HtmlTabla(params);
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
	document.getElementById("saiu19agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3019_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu19idsolicitante'){
		ter_traerxid('saiu19idsolicitante', sValor);
		}
	if (sCampo=='saiu19idresponsable'){
		ter_traerxid('saiu19idresponsable', sValor);
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
	params[1]=3019;
	params[2]=window.document.frmedita.saiu19agno.value;
	params[3]=window.document.frmedita.saiu19id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu19idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
// TODO
function cambiacanal(){
	window.document.frmedita.saiucanal.value = document.getElementById('canales').value;
	window.document.frmedita.submit();
}
// TODO
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3019.php" target="_blank">
<input id="r" name="r" type="hidden" value="3019" />
<input id="id3019" name="id3019" type="hidden" value="<?php echo $_REQUEST['saiu19id']; ?>" />
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
<input id="vdidchat" name="vdidchat" type="hidden" value="<?php echo $_REQUEST['vdidchat']; ?>" />
<div id="div_sector1">
<!-- TODO -->
<div class="areaform"> 
<div class="areatrabajo">
<div class="GrupoCamposAyuda">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiucanal'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiucanal;
?>
<input id="saiucanal" name="saiucanal" type="hidden" value="<?php echo $_REQUEST['saiucanal']; ?>" />
</label>
<div class="salto5px"></div>
</div>
</div>
</div>
<!-- TODO -->
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu19estado']<7){
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
		if ($_REQUEST['saiu19estado']>6){
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
if ($_REQUEST['saiu19estado']<7){
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
echo '<h2>'.$ETI['titulo_3019'].'</h2>';
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
<input id="boculta3019" name="boculta3019" type="hidden" value="<?php echo $_REQUEST['boculta3019']; ?>" />
<label class="Label30">
<input id="btexpande3019" name="btexpande3019" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3019,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3019']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3019" name="btrecoge3019" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3019,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3019']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3019" style="display:<?php if ($_REQUEST['boculta3019']==0){echo 'block'; }else{echo 'none';} ?>;">
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
echo $html_saiu19dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu19mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu19agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu19dia.'/'.$html_saiu19mes.'/'.$html_saiu19agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu19hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu19hora">
<?php
echo html_HoraMin('saiu19hora', $_REQUEST['saiu19hora'], 'saiu19minuto', $_REQUEST['saiu19minuto']);
?>
</div>
<input id="saiu19tiporadicado" name="saiu19tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu19tiporadicado']; ?>"/>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu19consec" name="saiu19consec" type="text" value="<?php echo $_REQUEST['saiu19consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu19consec', $_REQUEST['saiu19consec'], formato_numero($_REQUEST['saiu19consec']));
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
echo $ETI['saiu19id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu19id', $_REQUEST['saiu19id'], formato_numero($_REQUEST['saiu19id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu19estado'];
?>
</label>
<label>
<div id="div_saiu19estado">
<?php
echo $html_saiu19estado;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idchat'];
?>
</label>
<label>
<?php
echo $html_saiu19idchat;
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu19numsesionchat'];
?>
</label>
<label>
<input id="saiu19numsesionchat" name="saiu19numsesionchat" type="text" value="<?php echo $_REQUEST['saiu19numsesionchat']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19numsesionchat']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu19idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu19idsolicitante" name="saiu19idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu19idsolicitante']; ?>"/>
<div id="div_saiu19idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu19idsolicitante', $_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu19idsolicitante" class="L"><?php echo $saiu19idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu19tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu19tipointeresado;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu19numorigen'];
?>
<input id="saiu19numorigen" name="saiu19numorigen" type="text" value="<?php echo $_REQUEST['saiu19numorigen']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19numorigen']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu19idzona'];
?>
</label>
<label>
<?php
echo $html_saiu19idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idcentro'];
?>
</label>
<label>
<div id="div_saiu19idcentro">
<?php
echo $html_saiu19idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19codpais'];
?>
</label>
<label>
<?php
echo $html_saiu19codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19coddepto'];
?>
</label>
<label>
<div id="div_saiu19coddepto">
<?php
echo $html_saiu19coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19codciudad'];
?>
</label>
<label>
<div id="div_saiu19codciudad">
<?php
echo $html_saiu19codciudad;
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
<input id="saiu19clasesolicitud" name="saiu19clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu19clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu19tiposolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu19tiposolicitud;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19temasolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu19temasolicitud;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu19idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idprograma'];
?>
</label>
<label>
<div id="div_saiu19idprograma">
<?php
echo $html_saiu19idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu19idperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['saiu19detalle'];
?>
<textarea id="saiu19detalle" name="saiu19detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19detalle']; ?>"><?php echo $_REQUEST['saiu19detalle']; ?></textarea>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu19solucion'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu19solucion;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu19paramercadeo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu19paramercadeo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idcaso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu19idcaso', $_REQUEST['saiu19idcaso']);
?>
</label>
<div class="salto1px"></div>
<label class="Labe250">
<?php
echo $ETI['saiu19idpqrs'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu19idpqrs', $_REQUEST['saiu19idpqrs']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu19idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu19idresponsable" name="saiu19idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu19idresponsable']; ?>"/>
<div id="div_saiu19idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu19idresponsable', $_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu19idresponsable" class="L"><?php echo $saiu19idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu19horafin">
<?php
echo html_HoraMin('saiu19horafin', $_REQUEST['saiu19horafin'], 'saiu19minutofin', $_REQUEST['saiu19minutofin']);
?>
</div>
<?php
if ($_REQUEST['saiu19estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu19tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu19tiemprespdias'], $_REQUEST['saiu19tiempresphoras'], $_REQUEST['saiu19tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu19tiemprespdias" name="saiu19tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu19tiemprespdias']; ?>"/>
<input id="saiu19tiempresphoras" name="saiu19tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu19tiempresphoras']; ?>"/>
<input id="saiu19tiemprespminutos" name="saiu19tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu19tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
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
	if ($_REQUEST['saiu19estado']<7){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="Terminar" class="BotonAzul" onclick="enviacerrar()" title="Terminar Llamada"/>
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
	//Este es el cierre del div_p3019
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3019()" autocomplete="off"/>
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
echo $ETI['saiu19agno'];
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
<div id="div_f3019detalle">
<?php
echo $sTabla3019;
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
echo $ETI['msg_saiu19consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu19consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu19consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu19consec_nuevo" name="saiu19consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu19consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3019" name="titulo_3019" type="hidden" value="<?php echo $ETI['titulo_3019']; ?>" />
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
echo '<h2>'.$ETI['titulo_3019'].'</h2>';
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
echo '<h2>'.$ETI['titulo_3019'].'</h2>';
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
if ($_REQUEST['saiu19estado']<7){
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
$("#saiu19idcentro").chosen();
$("#saiu19coddepto").chosen();
$("#saiu19codciudad").chosen();
<?php
if ($bEnProceso){
?>
$("#saiu19tiposolicitud").chosen();
<?php
	}
?>
$("#saiu19temasolicitud").chosen();
$("#saiu19idprograma").chosen();
$("#saiu19idperiodo").chosen();
});
-->
</script>
<script language="javascript" src="ac_3019.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>