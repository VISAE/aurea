<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.24.1 lunes, 17 de febrero de 2020
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
*/
/** Archivo saiutiposerv.php.
* Modulo 3002 saiu02tiposol.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date martes, 11 de febrero de 2020
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
$iCodModulo=3002;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
require $mensajes_todas;
require $mensajes_3002;
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
		header('Location:noticia.php?ret=saiutiposerv.php');
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3002 saiu02tiposol
require 'lib3002.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f3002_Combosaiu02idequiporesp');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3002_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3002_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3002_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3002_HtmlBusqueda');
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
if (isset($_REQUEST['paginaf3002'])==0){$_REQUEST['paginaf3002']=1;}
if (isset($_REQUEST['lppf3002'])==0){$_REQUEST['lppf3002']=20;}
if (isset($_REQUEST['boculta3002'])==0){$_REQUEST['boculta3002']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu02consec'])==0){$_REQUEST['saiu02consec']='';}
if (isset($_REQUEST['saiu02consec_nuevo'])==0){$_REQUEST['saiu02consec_nuevo']='';}
if (isset($_REQUEST['saiu02id'])==0){$_REQUEST['saiu02id']='';}
if (isset($_REQUEST['saiu02titulo'])==0){$_REQUEST['saiu02titulo']='';}
if (isset($_REQUEST['saiu02clasesol'])==0){$_REQUEST['saiu02clasesol']='';}
if (isset($_REQUEST['saiu02detalle'])==0){$_REQUEST['saiu02detalle']='';}
if (isset($_REQUEST['saiu02idunidadresp'])==0){$_REQUEST['saiu02idunidadresp']='';}
if (isset($_REQUEST['saiu02idequiporesp'])==0){$_REQUEST['saiu02idequiporesp']='';}
if (isset($_REQUEST['saiu02idliderrespon'])==0){$_REQUEST['saiu02idliderrespon']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu02idliderrespon_td'])==0){$_REQUEST['saiu02idliderrespon_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu02idliderrespon_doc'])==0){$_REQUEST['saiu02idliderrespon_doc']='';}
if (isset($_REQUEST['saiu02ordenllamada'])==0){$_REQUEST['saiu02ordenllamada']='';}
if (isset($_REQUEST['saiu02ordenchat'])==0){$_REQUEST['saiu02ordenchat']='';}
if (isset($_REQUEST['saiu02ordencorreo'])==0){$_REQUEST['saiu02ordencorreo']='';}
if (isset($_REQUEST['saiu02ordenpresencial'])==0){$_REQUEST['saiu02ordenpresencial']='';}
if (isset($_REQUEST['saiu02ordensoporte'])==0){$_REQUEST['saiu02ordensoporte']='';}
if (isset($_REQUEST['saiu02ordenpqrs'])==0){$_REQUEST['saiu02ordenpqrs']='';}
if (isset($_REQUEST['saiu02ordentramites'])==0){$_REQUEST['saiu02ordentramites']='';}
if (isset($_REQUEST['saiu02ordencorresp'])==0){$_REQUEST['saiu02ordencorresp']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu02idliderrespon_td']=$APP->tipo_doc;
	$_REQUEST['saiu02idliderrespon_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu02consec='.$_REQUEST['saiu02consec'].'';
		}else{
		$sSQLcondi='saiu02id='.$_REQUEST['saiu02id'].'';
		}
	$sSQL='SELECT * FROM saiu02tiposol WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu02consec']=$fila['saiu02consec'];
		$_REQUEST['saiu02id']=$fila['saiu02id'];
		$_REQUEST['saiu02titulo']=$fila['saiu02titulo'];
		$_REQUEST['saiu02clasesol']=$fila['saiu02clasesol'];
		$_REQUEST['saiu02detalle']=$fila['saiu02detalle'];
		$_REQUEST['saiu02idunidadresp']=$fila['saiu02idunidadresp'];
		$_REQUEST['saiu02idequiporesp']=$fila['saiu02idequiporesp'];
		$_REQUEST['saiu02idliderrespon']=$fila['saiu02idliderrespon'];
		$_REQUEST['saiu02ordenllamada']=$fila['saiu02ordenllamada'];
		$_REQUEST['saiu02ordenchat']=$fila['saiu02ordenchat'];
		$_REQUEST['saiu02ordencorreo']=$fila['saiu02ordencorreo'];
		$_REQUEST['saiu02ordenpresencial']=$fila['saiu02ordenpresencial'];
		$_REQUEST['saiu02ordensoporte']=$fila['saiu02ordensoporte'];
		$_REQUEST['saiu02ordenpqrs']=$fila['saiu02ordenpqrs'];
		$_REQUEST['saiu02ordentramites']=$fila['saiu02ordentramites'];
		$_REQUEST['saiu02ordencorresp']=$fila['saiu02ordencorresp'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3002']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f3002_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu02consec_nuevo']=numeros_validar($_REQUEST['saiu02consec_nuevo']);
	if ($_REQUEST['saiu02consec_nuevo']==''){$sError=$ERR['saiu02consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu02id FROM saiu02tiposol WHERE saiu02consec='.$_REQUEST['saiu02consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu02consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu02tiposol SET saiu02consec='.$_REQUEST['saiu02consec_nuevo'].' WHERE saiu02id='.$_REQUEST['saiu02id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu02consec'].' a '.$_REQUEST['saiu02consec_nuevo'].'';
		$_REQUEST['saiu02consec']=$_REQUEST['saiu02consec_nuevo'];
		$_REQUEST['saiu02consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu02id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3002_db_Eliminar($_REQUEST['saiu02id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu02consec']='';
	$_REQUEST['saiu02consec_nuevo']='';
	$_REQUEST['saiu02id']='';
	$_REQUEST['saiu02titulo']='';
	$_REQUEST['saiu02clasesol']='';
	$_REQUEST['saiu02detalle']='';
	$_REQUEST['saiu02idunidadresp']='';
	$_REQUEST['saiu02idequiporesp']='';
	$_REQUEST['saiu02idliderrespon']=0;//$idTercero;
	$_REQUEST['saiu02idliderrespon_td']=$APP->tipo_doc;
	$_REQUEST['saiu02idliderrespon_doc']='';
	$_REQUEST['saiu02ordenllamada']=0;
	$_REQUEST['saiu02ordenchat']=0;
	$_REQUEST['saiu02ordencorreo']=0;
	$_REQUEST['saiu02ordenpresencial']=0;
	$_REQUEST['saiu02ordensoporte']=0;
	$_REQUEST['saiu02ordenpqrs']=0;
	$_REQUEST['saiu02ordentramites']=0;
	$_REQUEST['saiu02ordencorresp']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
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
$objCombos->nuevo('saiu02clasesol', $_REQUEST['saiu02clasesol'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu01id AS id, saiu01titulo AS nombre FROM saiu01claseser ORDER BY saiu01titulo';
$html_saiu02clasesol=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu02idunidadresp', $_REQUEST['saiu02idunidadresp'], true, '{'.$ETI['msg_ninguna'].'}', 0);
$objCombos->iAncho=300;
$sSQL26=f226_ConsultaCombo();
$html_saiu02idunidadresp=$objCombos->html($sSQL26, $objDB);
$html_saiu02idequiporesp=f3002_HTMLComboV2_saiu02idequiporesp($objDB, $objCombos, $_REQUEST['saiu02idequiporesp'], $_REQUEST['saiu02idunidadresp']);
list($saiu02idliderrespon_rs, $_REQUEST['saiu02idliderrespon'], $_REQUEST['saiu02idliderrespon_td'], $_REQUEST['saiu02idliderrespon_doc'])=html_tercero($_REQUEST['saiu02idliderrespon_td'], $_REQUEST['saiu02idliderrespon_doc'], $_REQUEST['saiu02idliderrespon'], 0, $objDB);
$objCombos->nuevo('saiu02ordenllamada', $_REQUEST['saiu02ordenllamada'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordenllamada=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordenchat', $_REQUEST['saiu02ordenchat'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordenchat=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordencorreo', $_REQUEST['saiu02ordencorreo'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordencorreo=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordenpresencial', $_REQUEST['saiu02ordenpresencial'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordenpresencial=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordensoporte', $_REQUEST['saiu02ordensoporte'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordensoporte=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordenpqrs', $_REQUEST['saiu02ordenpqrs'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordenpqrs=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordentramites', $_REQUEST['saiu02ordentramites'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordentramites=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu02ordencorresp', $_REQUEST['saiu02ordencorresp'], true, $asaiu02orden[0], 0);
$objCombos->addArreglo($asaiu02orden, $isaiu02orden);
$html_saiu02ordencorresp=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->iAncho=300;
$objCombos->sAccion='paginarf3002()';
$objCombos->addItem('0', '{'.$ETI['msg_ninguno'].'}');
$html_blistar=$objCombos->html($sSQL26, $objDB);
//$html_blistar=$objCombos->comboSistema(3002, 1, $objDB, 'paginarf3002()');
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3002;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3002'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3002'];
$aParametros[102]=$_REQUEST['lppf3002'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
list($sTabla3002, $sDebugTabla)=f3002_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3002']);
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
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
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
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3002.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3002.value;
		window.document.frmlista.nombrearchivo.value='Tipos de servicios';
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
		window.document.frmimpp.action='e3002.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3002.php';
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
	datos[1]=window.document.frmedita.saiu02consec.value;
	if ((datos[1]!='')){
		xajax_f3002_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.saiu02consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3002(llave1){
	window.document.frmedita.saiu02id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu02idequiporesp(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu02idunidadresp.value;
	document.getElementById('div_saiu02idequiporesp').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu02idequiporesp" name="saiu02idequiporesp" type="hidden" value="" />';
	xajax_f3002_Combosaiu02idequiporesp(params);
	}
function paginarf3002(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3002.value;
	params[102]=window.document.frmedita.lppf3002.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f3002detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3002" name="paginaf3002" type="hidden" value="'+params[101]+'" /><input id="lppf3002" name="lppf3002" type="hidden" value="'+params[102]+'" />';
	xajax_f3002_HtmlTabla(params);
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
	document.getElementById("saiu02consec").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3002_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu02idliderrespon'){
		ter_traerxid('saiu02idliderrespon', sValor);
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
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3002.php" target="_blank">
<input id="r" name="r" type="hidden" value="3002" />
<input id="id3002" name="id3002" type="hidden" value="<?php echo $_REQUEST['saiu02id']; ?>" />
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
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
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
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3002'].'</h2>';
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
<input id="boculta3002" name="boculta3002" type="hidden" value="<?php echo $_REQUEST['boculta3002']; ?>" />
<label class="Label30">
<input id="btexpande3002" name="btexpande3002" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3002,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3002']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3002" name="btrecoge3002" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3002,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3002']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3002" style="display:<?php if ($_REQUEST['boculta3002']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu02consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu02consec" name="saiu02consec" type="text" value="<?php echo $_REQUEST['saiu02consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu02consec', $_REQUEST['saiu02consec'], formato_numero($_REQUEST['saiu02consec']));
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
echo $ETI['saiu02id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu02id', $_REQUEST['saiu02id'], formato_numero($_REQUEST['saiu02id']));
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu02clasesol'];
?>
</label>
<label>
<?php
echo $html_saiu02clasesol;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu02titulo'];
?>

<input id="saiu02titulo" name="saiu02titulo" type="text" value="<?php echo $_REQUEST['saiu02titulo']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu02titulo']; ?>"/>
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu02detalle'];
?>
<textarea id="saiu02detalle" name="saiu02detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu02detalle']; ?>"><?php echo $_REQUEST['saiu02detalle']; ?></textarea>
</label>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiu02idunidadresp'];
?>
</label>
<label>
<?php
echo $html_saiu02idunidadresp;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu02idequiporesp'];
?>
</label>
<label>
<div id="div_saiu02idequiporesp">
<?php
echo $html_saiu02idequiporesp;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu02idliderrespon'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu02idliderrespon" name="saiu02idliderrespon" type="hidden" value="<?php echo $_REQUEST['saiu02idliderrespon']; ?>"/>
<div id="div_saiu02idliderrespon_llaves">
<?php
$bOculto=false;
echo html_DivTerceroV2('saiu02idliderrespon', $_REQUEST['saiu02idliderrespon_td'], $_REQUEST['saiu02idliderrespon_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu02idliderrespon" class="L"><?php echo $saiu02idliderrespon_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_usomodulos'];
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu02ordenllamada'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordenllamada;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu02ordenchat'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordenchat;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu02ordencorreo'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordencorreo;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu02ordenpresencial'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordenpresencial;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu02ordensoporte'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordensoporte;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu02ordenpqrs'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordenpqrs;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu02ordentramites'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordentramites;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu02ordencorresp'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu02ordencorresp;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3002
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
echo $ETI['saiu02titulo'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3002()" autocomplete="off"/>
</label>
<label class="Label160">
<?php
echo $ETI['saiu02idunidadresp'];
?>
</label>
<label>
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3002detalle">
<?php
echo $sTabla3002;
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
echo $ETI['msg_saiu02consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu02consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu02consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu02consec_nuevo" name="saiu02consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu02consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3002" name="titulo_3002" type="hidden" value="<?php echo $ETI['titulo_3002']; ?>" />
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
echo '<h2>'.$ETI['titulo_3002'].'</h2>';
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
echo '<h2>'.$ETI['titulo_3002'].'</h2>';
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
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
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
<?php
if ($_REQUEST['paso']==0){
?>
<script language="javascript">
<!--
$().ready(function(){
//$("#bperiodo").chosen();
});
-->
</script>
<?php
	}
?>
<script language="javascript" src="ac_3002.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>