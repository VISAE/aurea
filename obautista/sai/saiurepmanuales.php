<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
*/
/** Archivo saiurepmanuales.php.
* Modulo 3053 saiu53manuales.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date lunes, 5 de abril de 2021
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
//require $APP->rutacomun.'libdatos.php';
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
$iCodModulo=3053;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
require $mensajes_todas;
require $mensajes_3053;
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
		header('Location:noticia.php?ret=saiurepmanuales.php');
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
$mensajes_3054='lg/lg_3054_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3054)){$mensajes_3054='lg/lg_3054_es.php';}
$mensajes_3055='lg/lg_3055_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3055)){$mensajes_3055='lg/lg_3055_es.php';}
require $mensajes_3054;
require $mensajes_3055;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3053 saiu53manuales
require 'lib3053.php';
// -- 3054 Perfiles
require 'lib3054.php';
// -- 3055 Manuales
require 'lib3055.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3053_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3053_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3054_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3054_Traer');
$xajax->register(XAJAX_FUNCTION,'f3054_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3054_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3054_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu55idarchivo');
$xajax->register(XAJAX_FUNCTION,'f3055_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3055_Traer');
$xajax->register(XAJAX_FUNCTION,'f3055_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3055_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3055_PintarLlaves');
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
if (isset($_REQUEST['paginaf3053'])==0){$_REQUEST['paginaf3053']=1;}
if (isset($_REQUEST['lppf3053'])==0){$_REQUEST['lppf3053']=20;}
if (isset($_REQUEST['boculta3053'])==0){$_REQUEST['boculta3053']=0;}
if (isset($_REQUEST['paginaf3054'])==0){$_REQUEST['paginaf3054']=1;}
if (isset($_REQUEST['lppf3054'])==0){$_REQUEST['lppf3054']=20;}
if (isset($_REQUEST['boculta3054'])==0){$_REQUEST['boculta3054']=0;}
if (isset($_REQUEST['paginaf3055'])==0){$_REQUEST['paginaf3055']=1;}
if (isset($_REQUEST['lppf3055'])==0){$_REQUEST['lppf3055']=20;}
if (isset($_REQUEST['boculta3055'])==0){$_REQUEST['boculta3055']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu53consec'])==0){$_REQUEST['saiu53consec']='';}
if (isset($_REQUEST['saiu53consec_nuevo'])==0){$_REQUEST['saiu53consec_nuevo']='';}
if (isset($_REQUEST['saiu53id'])==0){$_REQUEST['saiu53id']='';}
if (isset($_REQUEST['saiu53vigente'])==0){$_REQUEST['saiu53vigente']='S';}
if (isset($_REQUEST['saiu53publico'])==0){$_REQUEST['saiu53publico']='';}
if (isset($_REQUEST['saiu53titulo'])==0){$_REQUEST['saiu53titulo']='';}
if (isset($_REQUEST['saiu53descripcion'])==0){$_REQUEST['saiu53descripcion']='';}
if ((int)$_REQUEST['paso']>0){
	//Perfiles
	if (isset($_REQUEST['saiu54idperfil'])==0){$_REQUEST['saiu54idperfil']='';}
	if (isset($_REQUEST['saiu54id'])==0){$_REQUEST['saiu54id']='';}
	if (isset($_REQUEST['saiu54vigente'])==0){$_REQUEST['saiu54vigente']='S';}
	//Manuales
	if (isset($_REQUEST['saiu55consec'])==0){$_REQUEST['saiu55consec']='';}
	if (isset($_REQUEST['saiu55id'])==0){$_REQUEST['saiu55id']='';}
	if (isset($_REQUEST['saiu55fecha'])==0){$_REQUEST['saiu55fecha']='';}//{fecha_hoy();}
	if (isset($_REQUEST['saiu55infoversion'])==0){$_REQUEST['saiu55infoversion']='';}
	if (isset($_REQUEST['saiu55formaenlace'])==0){$_REQUEST['saiu55formaenlace']='';}
	if (isset($_REQUEST['saiu55ruta'])==0){$_REQUEST['saiu55ruta']='';}
	if (isset($_REQUEST['saiu55idorigen'])==0){$_REQUEST['saiu55idorigen']=0;}
	if (isset($_REQUEST['saiu55idarchivo'])==0){$_REQUEST['saiu55idarchivo']=0;}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Perfiles
	if (isset($_REQUEST['bnombre3054'])==0){$_REQUEST['bnombre3054']='';}
	//if (isset($_REQUEST['blistar3054'])==0){$_REQUEST['blistar3054']='';}
	//Manuales
	if (isset($_REQUEST['bnombre3055'])==0){$_REQUEST['bnombre3055']='';}
	//if (isset($_REQUEST['blistar3055'])==0){$_REQUEST['blistar3055']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu53consec='.$_REQUEST['saiu53consec'].'';
		}else{
		$sSQLcondi='saiu53id='.$_REQUEST['saiu53id'].'';
		}
	$sSQL='SELECT * FROM saiu53manuales WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu53consec']=$fila['saiu53consec'];
		$_REQUEST['saiu53id']=$fila['saiu53id'];
		$_REQUEST['saiu53vigente']=$fila['saiu53vigente'];
		$_REQUEST['saiu53publico']=$fila['saiu53publico'];
		$_REQUEST['saiu53titulo']=$fila['saiu53titulo'];
		$_REQUEST['saiu53descripcion']=$fila['saiu53descripcion'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3053']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f3053_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu53consec_nuevo']=numeros_validar($_REQUEST['saiu53consec_nuevo']);
	if ($_REQUEST['saiu53consec_nuevo']==''){$sError=$ERR['saiu53consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu53id FROM saiu53manuales WHERE saiu53consec='.$_REQUEST['saiu53consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu53consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu53manuales SET saiu53consec='.$_REQUEST['saiu53consec_nuevo'].' WHERE saiu53id='.$_REQUEST['saiu53id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu53consec'].' a '.$_REQUEST['saiu53consec_nuevo'].'';
		$_REQUEST['saiu53consec']=$_REQUEST['saiu53consec_nuevo'];
		$_REQUEST['saiu53consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu53id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3053_db_Eliminar($_REQUEST['saiu53id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu53consec']='';
	$_REQUEST['saiu53consec_nuevo']='';
	$_REQUEST['saiu53id']='';
	$_REQUEST['saiu53vigente']=1;
	$_REQUEST['saiu53publico']=0;
	$_REQUEST['saiu53titulo']='';
	$_REQUEST['saiu53descripcion']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['saiu54idmanual']='';
	$_REQUEST['saiu54idperfil']='';
	$_REQUEST['saiu54id']='';
	$_REQUEST['saiu54vigente']=1;
	$_REQUEST['saiu55idmanual']='';
	$_REQUEST['saiu55consec']='';
	$_REQUEST['saiu55id']='';
	$_REQUEST['saiu55fecha']='';//fecha_hoy();
	$_REQUEST['saiu55infoversion']='';
	$_REQUEST['saiu55formaenlace']=0;
	$_REQUEST['saiu55ruta']='';
	$_REQUEST['saiu55idorigen']=0;
	$_REQUEST['saiu55idarchivo']=0;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$bIncluyePerfiles=false;
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu53publico']==2){$bIncluyePerfiles=true;}
	}
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objCombos->nuevo('saiu53vigente', $_REQUEST['saiu53vigente'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu53vigente, $isaiu53vigente);
$html_saiu53vigente=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu53publico', $_REQUEST['saiu53publico'], true, $asaiu53publico[0], 0);
$objCombos->addArreglo($asaiu53publico, $isaiu53publico);
$html_saiu53publico=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	if ($bIncluyePerfiles){
		$html_saiu54idperfil=f3054_HTMLComboV2_saiu54idperfil($objDB, $objCombos, $_REQUEST['saiu54idperfil']);
		$objCombos->nuevo('saiu54vigente', $_REQUEST['saiu54vigente'], true, $ETI['no'], 0);
		$objCombos->addItem(1, $ETI['si']);
		//$objCombos->addArreglo($asaiu54vigente, $isaiu54vigente);
		$html_saiu54vigente=$objCombos->html('', $objDB);
		}
	$objCombos->nuevo('saiu55formaenlace', $_REQUEST['saiu55formaenlace'], true, $asaiu55formaenlace[0], 0);
	$objCombos->addItem(1, $asaiu55formaenlace[1]);
	//$objCombos->addArreglo($asaiu55formaenlace, $isaiu55formaenlace);
	$html_saiu55formaenlace=$objCombos->html('', $objDB);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3053()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3053, 1, $objDB, 'paginarf3053()');
$objCombos->nuevo('blistar3054', $_REQUEST['blistar3054'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3054=$objCombos->comboSistema(3054, 1, $objDB, 'paginarf3054()');
$objCombos->nuevo('blistar3055', $_REQUEST['blistar3055'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3055=$objCombos->comboSistema(3055, 1, $objDB, 'paginarf3055()');
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
$iModeloReporte=3053;
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
$aParametros[0]='';//$_REQUEST['p1_3053'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3053'];
$aParametros[102]=$_REQUEST['lppf3053'];
$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla3053, $sDebugTabla)=f3053_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3054='';
$sTabla3055='';
if ($_REQUEST['paso']!=0){
	if ($bIncluyePerfiles){
		//Perfiles
		$aParametros3054[0]=$_REQUEST['saiu53id'];
		$aParametros3054[100]=$idTercero;
		$aParametros3054[101]=$_REQUEST['paginaf3054'];
		$aParametros3054[102]=$_REQUEST['lppf3054'];
		//$aParametros3054[103]=$_REQUEST['bnombre3054'];
		//$aParametros3054[104]=$_REQUEST['blistar3054'];
		list($sTabla3054, $sDebugTabla)=f3054_TablaDetalleV2($aParametros3054, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		}
	//Manuales
	$aParametros3055[0]=$_REQUEST['saiu53id'];
	$aParametros3055[100]=$idTercero;
	$aParametros3055[101]=$_REQUEST['paginaf3055'];
	$aParametros3055[102]=$_REQUEST['lppf3055'];
	//$aParametros3055[103]=$_REQUEST['bnombre3055'];
	//$aParametros3055[104]=$_REQUEST['blistar3055'];
	list($sTabla3055, $sDebugTabla)=f3055_TablaDetalleV2($aParametros3055, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3053']);
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
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3053.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3053.value;
		window.document.frmlista.nombrearchivo.value='Repositorio de manuales';
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
		window.document.frmimpp.action='e3053.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3053.php';
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
	datos[1]=window.document.frmedita.saiu53consec.value;
	if ((datos[1]!='')){
		xajax_f3053_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.saiu53consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3053(llave1){
	window.document.frmedita.saiu53id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf3053(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3053.value;
	params[102]=window.document.frmedita.lppf3053.value;
	params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f3053detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3053" name="paginaf3053" type="hidden" value="'+params[101]+'" /><input id="lppf3053" name="lppf3053" type="hidden" value="'+params[102]+'" />';
	xajax_f3053_HtmlTabla(params);
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
	document.getElementById("saiu53consec").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
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
	if (ref==3055){
		if (sRetorna!=''){
			window.document.frmedita.saiu55idorigen.value=window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu55idarchivo.value=sRetorna;
			verboton('beliminasaiu55idarchivo','block');
			}
		archivo_lnk(window.document.frmedita.saiu55idorigen.value, window.document.frmedita.saiu55idarchivo.value, 'div_saiu55idarchivo');
		paginarf3055();
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
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js3054.js"></script>
<script language="javascript" src="jsi/js3055.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3053.php" target="_blank">
<input id="r" name="r" type="hidden" value="3053" />
<input id="id3053" name="id3053" type="hidden" value="<?php echo $_REQUEST['saiu53id']; ?>" />
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
echo '<h2>'.$ETI['titulo_3053'].'</h2>';
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
<input id="boculta3053" name="boculta3053" type="hidden" value="<?php echo $_REQUEST['boculta3053']; ?>" />
<label class="Label30">
<input id="btexpande3053" name="btexpande3053" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3053,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3053']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3053" name="btrecoge3053" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3053,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3053']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3053" style="display:<?php if ($_REQUEST['boculta3053']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu53consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu53consec" name="saiu53consec" type="text" value="<?php echo $_REQUEST['saiu53consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu53consec', $_REQUEST['saiu53consec'], formato_numero($_REQUEST['saiu53consec']));
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
echo $ETI['saiu53id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu53id', $_REQUEST['saiu53id'], formato_numero($_REQUEST['saiu53id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu53vigente'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu53vigente;
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu53publico'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu53publico;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu53titulo'];
?>

<input id="saiu53titulo" name="saiu53titulo" type="text" value="<?php echo $_REQUEST['saiu53titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu53titulo']; ?>"/>
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu53descripcion'];
?>
<textarea id="saiu53descripcion" name="saiu53descripcion" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu53descripcion']; ?>"><?php echo $_REQUEST['saiu53descripcion']; ?></textarea>
</label>
<?php
// -- Inicia Grupo campos 3054 Perfiles
if ($bIncluyePerfiles){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3054'];
?>
</label>
<input id="boculta3054" name="boculta3054" type="hidden" value="<?php echo $_REQUEST['boculta3054']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3054" name="btexcel3054" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3054();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3054" name="btexpande3054" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3054,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3054']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3054" name="btrecoge3054" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3054,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3054']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3054" style="display:<?php if ($_REQUEST['boculta3054']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['saiu54idperfil'];
?>
</label>
<label>
<div id="div_saiu54idperfil">
<?php
echo $html_saiu54idperfil;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu54vigente'];
?>
</label>
<label class="Label90">
<?php
echo $html_saiu54vigente;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu54id'];
?>
</label>
<label class="Label60"><div id="div_saiu54id">
<?php
	echo html_oculto('saiu54id', $_REQUEST['saiu54id'], formato_numero($_REQUEST['saiu54id']));
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3054" name="bguarda3054" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3054()" title="<?php echo $ETI['bt_mini_guardar_3054']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3054" name="blimpia3054" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3054()" title="<?php echo $ETI['bt_mini_limpiar_3054']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3054" name="belimina3054" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3054()" title="<?php echo $ETI['bt_mini_eliminar_3054']; ?>" style="display:<?php if ((int)$_REQUEST['saiu54id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3054
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
<input id="bnombre3054" name="bnombre3054" type="text" value="<?php echo $_REQUEST['bnombre3054']; ?>" onchange="paginarf3054()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3054;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f3054detalle">
<?php
echo $sTabla3054;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}
// -- Termina Grupo campos 3054 Perfiles
?>
<?php
// -- Inicia Grupo campos 3055 Manuales
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3055'];
?>
</label>
<input id="boculta3055" name="boculta3055" type="hidden" value="<?php echo $_REQUEST['boculta3055']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3055" name="btexcel3055" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3055();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3055" name="btexpande3055" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3055,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3055']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3055" name="btrecoge3055" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3055,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3055']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3055" style="display:<?php if ($_REQUEST['boculta3055']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu55consec'];
?>
</label>
<label class="Label130"><div id="div_saiu55consec">
<?php
if ((int)$_REQUEST['saiu55id']==0){
?>
<input id="saiu55consec" name="saiu55consec" type="text" value="<?php echo $_REQUEST['saiu55consec']; ?>" onchange="revisaf3055()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu55consec', $_REQUEST['saiu55consec'], formato_numero($_REQUEST['saiu55consec']));
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['saiu55id'];
?>
</label>
<label class="Label60"><div id="div_saiu55id">
<?php
	echo html_oculto('saiu55id', $_REQUEST['saiu55id'], formato_numero($_REQUEST['saiu55id']));
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu55fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu55fecha', $_REQUEST['saiu55fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu55fecha_hoy" name="bsaiu55fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu55fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="L">
<?php
echo $ETI['saiu55infoversion'];
?>

<input id="saiu55infoversion" name="saiu55infoversion" type="text" value="<?php echo $_REQUEST['saiu55infoversion']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu55infoversion']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu55formaenlace'];
?>
</label>
<label>
<?php
echo $html_saiu55formaenlace;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu55ruta'];
?>

<input id="saiu55ruta" name="saiu55ruta" type="text" value="<?php echo $_REQUEST['saiu55ruta']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu55ruta']; ?>"/>
</label>
<input id="saiu55idorigen" name="saiu55idorigen" type="hidden" value="<?php echo $_REQUEST['saiu55idorigen']; ?>"/>
<input id="saiu55idarchivo" name="saiu55idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu55idarchivo']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu55idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu55idorigen'], (int)$_REQUEST['saiu55idarchivo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexasaiu55idarchivo" name="banexasaiu55idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu55idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu55id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="beliminasaiu55idarchivo" name="beliminasaiu55idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu55idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu55idarchivo']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3055" name="bguarda3055" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3055()" title="<?php echo $ETI['bt_mini_guardar_3055']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3055" name="blimpia3055" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3055()" title="<?php echo $ETI['bt_mini_limpiar_3055']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3055" name="belimina3055" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3055()" title="<?php echo $ETI['bt_mini_eliminar_3055']; ?>" style="display:<?php if ((int)$_REQUEST['saiu55id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3055
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
<input id="bnombre3055" name="bnombre3055" type="text" value="<?php echo $_REQUEST['bnombre3055']; ?>" onchange="paginarf3055()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3055;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f3055detalle">
<?php
echo $sTabla3055;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3055 Manuales
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3053
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3053()" autocomplete="off"/>
</label>
<?php
if (false){
?>
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
<?php
	}
?>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3053detalle">
<?php
echo $sTabla3053;
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
echo $ETI['msg_saiu53consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu53consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu53consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu53consec_nuevo" name="saiu53consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu53consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3053" name="titulo_3053" type="hidden" value="<?php echo $ETI['titulo_3053']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3053'].'</h2>';
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<?php
if ($bIncluyePerfiles){
?>
<script language="javascript">
<!--
$().ready(function(){
<?php
if ($bIncluyePerfiles){
?>
$("#saiu54idperfil").chosen();
<?php
	}
?>
});
-->
</script>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>