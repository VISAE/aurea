<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 12 de abril de 2021
*/
/** Archivo saicorreosop.php.
* Modulo 3057 saiu57correos.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date lunes, 12 de abril de 2021
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
$iCodModulo=3057;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
require $mensajes_todas;
require $mensajes_3057;
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
		header('Location:noticia.php?ret=saicorreosop.php');
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
// -- 3057 saiu57correos
require 'lib3057.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3057_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3057_ExisteDato');
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
if (isset($_REQUEST['paginaf3057'])==0){$_REQUEST['paginaf3057']=1;}
if (isset($_REQUEST['lppf3057'])==0){$_REQUEST['lppf3057']=20;}
if (isset($_REQUEST['boculta3057'])==0){$_REQUEST['boculta3057']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu57consec'])==0){$_REQUEST['saiu57consec']='';}
if (isset($_REQUEST['saiu57consec_nuevo'])==0){$_REQUEST['saiu57consec_nuevo']='';}
if (isset($_REQUEST['saiu57id'])==0){$_REQUEST['saiu57id']='';}
if (isset($_REQUEST['saiu57vigente'])==0){$_REQUEST['saiu57vigente']='S';}
if (isset($_REQUEST['saiu57orden'])==0){$_REQUEST['saiu57orden']='';}
if (isset($_REQUEST['saiu57titulo'])==0){$_REQUEST['saiu57titulo']='';}
if (isset($_REQUEST['saiu57servidorsmtp'])==0){$_REQUEST['saiu57servidorsmtp']='';}
if (isset($_REQUEST['saiu57puertomail'])==0){$_REQUEST['saiu57puertomail']='';}
if (isset($_REQUEST['saiu57autenticacion'])==0){$_REQUEST['saiu57autenticacion']='';}
if (isset($_REQUEST['saiu57usuariomail'])==0){$_REQUEST['saiu57usuariomail']='';}
if (isset($_REQUEST['saiu57pwdmail'])==0){$_REQUEST['saiu57pwdmail']='';}
if (isset($_REQUEST['saiu57confirmado'])==0){$_REQUEST['saiu57confirmado']=0;}
if (isset($_REQUEST['saiu57servidorimpa'])==0){$_REQUEST['saiu57servidorimpa']='';}
if (isset($_REQUEST['saiu57puertoimpap'])==0){$_REQUEST['saiu57puertoimpap']='';}
if (isset($_REQUEST['saiu57autentimap'])==0){$_REQUEST['saiu57autentimap']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu57consec='.$_REQUEST['saiu57consec'].'';
		}else{
		$sSQLcondi='saiu57id='.$_REQUEST['saiu57id'].'';
		}
	$sSQL='SELECT * FROM saiu57correos WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu57consec']=$fila['saiu57consec'];
		$_REQUEST['saiu57id']=$fila['saiu57id'];
		$_REQUEST['saiu57vigente']=$fila['saiu57vigente'];
		$_REQUEST['saiu57orden']=$fila['saiu57orden'];
		$_REQUEST['saiu57titulo']=$fila['saiu57titulo'];
		$_REQUEST['saiu57servidorsmtp']=$fila['saiu57servidorsmtp'];
		$_REQUEST['saiu57puertomail']=$fila['saiu57puertomail'];
		$_REQUEST['saiu57autenticacion']=$fila['saiu57autenticacion'];
		$_REQUEST['saiu57usuariomail']=$fila['saiu57usuariomail'];
		$_REQUEST['saiu57pwdmail']=$fila['saiu57pwdmail'];
		$_REQUEST['saiu57confirmado']=$fila['saiu57confirmado'];
		$_REQUEST['saiu57servidorimpa']=$fila['saiu57servidorimpa'];
		$_REQUEST['saiu57puertoimpap']=$fila['saiu57puertoimpap'];
		$_REQUEST['saiu57autentimap']=$fila['saiu57autentimap'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3057']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f3057_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu57consec_nuevo']=numeros_validar($_REQUEST['saiu57consec_nuevo']);
	if ($_REQUEST['saiu57consec_nuevo']==''){$sError=$ERR['saiu57consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu57id FROM saiu57correos WHERE saiu57consec='.$_REQUEST['saiu57consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu57consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu57correos SET saiu57consec='.$_REQUEST['saiu57consec_nuevo'].' WHERE saiu57id='.$_REQUEST['saiu57id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu57consec'].' a '.$_REQUEST['saiu57consec_nuevo'].'';
		$_REQUEST['saiu57consec']=$_REQUEST['saiu57consec_nuevo'];
		$_REQUEST['saiu57consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu57id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Verificar la cuenta
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=2;
	$iResultado=0;
	require $APP->rutacomun.'PHPmailer/class.phpmailer.php';
	require $APP->rutacomun.'PHPmailer/class.smtp.php';
	$mail=new PHPMailer();
	$mail->IsSMTP();
	if ($_REQUEST['saiu57autenticacion']!=''){
		$mail->SMTPAuth=true;
		$mail->SMTPSecure=strtolower($_REQUEST['saiu57autenticacion']);
		}else{
		$mail->SMTPAuth=false;
		}
	$mail->Port=$_REQUEST['saiu57puertomail'];
	$mail->Username=$_REQUEST['saiu57usuariomail']; // Cuenta de e-mail
	$mail->Password=$_REQUEST['saiu57pwdmail']; // Password
	$mail->Host=$_REQUEST['saiu57servidorsmtp'];
	$mail->From=$_REQUEST['saiu57usuariomail'];
	$mail->FromName=$_REQUEST['saiu57titulo'];
	$mail->Subject=utf8_decode('Test de verificación de correo');
	$mail->AddAddress('amauro76@gmail.com','Soporte UNAD');
	$mail->WordWrap = 50;
	$body='Esta es una Prueba de Correo desde el campus virtual.';
	$mail->Body = $body;
	if(!$mail->Send()){
		$sError='No fue posible verificar la cuenta. {'.$mail->ErrorInfo.'}';
		//Puede perder la conexion a la base de datos porque pasa mucho tiempo.
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		}else{
		$iResultado=1;
		$sError='Proceso completado con exito';
		$iTipoError=1;
		}
	$sSQL='UPDATE saiu57correos SET saiu57confirmado='.$iResultado.' WHERE saiu57id='.$_REQUEST['saiu57id'].';';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Confirmando: '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	$_REQUEST['saiu57confirmado']=$iResultado;
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3057_db_Eliminar($_REQUEST['saiu57id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu57consec']='';
	$_REQUEST['saiu57consec_nuevo']='';
	$_REQUEST['saiu57id']='';
	$_REQUEST['saiu57vigente']=1;
	$_REQUEST['saiu57orden']='';
	$_REQUEST['saiu57titulo']='';
	$_REQUEST['saiu57servidorsmtp']='';
	$_REQUEST['saiu57puertomail']='';
	$_REQUEST['saiu57autenticacion']='';
	$_REQUEST['saiu57usuariomail']='';
	$_REQUEST['saiu57pwdmail']='';
	$_REQUEST['saiu57confirmado']=0;
	$_REQUEST['saiu57servidorimpa']='';
	$_REQUEST['saiu57puertoimpap']='';
	$_REQUEST['saiu57autentimap']='';
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
$objCombos->nuevo('saiu57vigente', $_REQUEST['saiu57vigente'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu57vigente, $isaiu57vigente);
$html_saiu57vigente=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu57autenticacion', $_REQUEST['saiu57autenticacion'], true, '{'.$ETI['msg_ninguna'].'}');
$sSQL='SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=169 AND unad22consec=2 AND unad22activa="S" ORDER BY unad22orden';
$html_saiu57autenticacion=$objCombos->html($sSQL, $objDB);
$saiu57confirmado_nombre=$ETI['no'];
if ($_REQUEST['saiu57confirmado']!=0){$saiu57confirmado_nombre=$ETI['si'];}
$html_saiu57confirmado=html_oculto('saiu57confirmado', $_REQUEST['saiu57confirmado'], $saiu57confirmado_nombre);
$objCombos->nuevo('saiu57autentimap', $_REQUEST['saiu57autentimap'], true, '{'.$ETI['msg_ninguna'].'}');
$sSQL='SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=169 AND unad22consec=2 AND unad22activa="S" ORDER BY unad22orden';
$html_saiu57autentimap=$objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	if ($_REQUEST['saiu57confirmado']!=0){}
	if (false){
		if ($sError==''){
			$sFechaRevisa=fecha_mmddaaaa(fecha_hoy());
			require $APP->rutacomun.'libgmail.php';
			list($sError, $iCorreos, $aRes)=fgmail_leer($_REQUEST['saiu57usuariomail'], $_REQUEST['saiu57pwdmail'], $sFechaRevisa);
			if ($sError==''){
				$sError='Correos en la bandeja de entrada de fecha '.$sFechaRevisa.': '.$iCorreos.'';
				$iTipoError=2;
				if (true){
					for ($k=1;$k<=$iCorreos;$k++){
						$sError=$sError.'<br>'.$aRes[$k]['origen'].' - '.$aRes[$k]['uid'].' - ['.$aRes[$k]['remitente'].'] - '.cadena_notildes($aRes[$k]['asunto']);
						//print_r($aRes[$k]['over']).'<br>------------------<br>';
						}
					}
				}
			}
		}
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3057()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3057, 1, $objDB, 'paginarf3057()');
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
$iModeloReporte=3057;
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
$aParametros[0]='';//$_REQUEST['p1_3057'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3057'];
$aParametros[102]=$_REQUEST['lppf3057'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla3057, $sDebugTabla)=f3057_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3057']);
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
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3057.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3057.value;
		window.document.frmlista.nombrearchivo.value='Correos de soporte';
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
		window.document.frmimpp.action='e3057.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3057.php';
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
	datos[1]=window.document.frmedita.saiu57consec.value;
	if ((datos[1]!='')){
		xajax_f3057_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.saiu57consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3057(llave1){
	window.document.frmedita.saiu57id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf3057(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3057.value;
	params[102]=window.document.frmedita.lppf3057.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f3057detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3057" name="paginaf3057" type="hidden" value="'+params[101]+'" /><input id="lppf3057" name="lppf3057" type="hidden" value="'+params[102]+'" />';
	xajax_f3057_HtmlTabla(params);
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
	document.getElementById("saiu57consec").focus();
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
<?php
if ($_REQUEST['paso']!=0){
?>
function verificar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	MensajeAlarmaV2('Verificando correo...', 2);
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
<?php
	}
?>
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3057.php" target="_blank">
<input id="r" name="r" type="hidden" value="3057" />
<input id="id3057" name="id3057" type="hidden" value="<?php echo $_REQUEST['saiu57id']; ?>" />
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
echo '<h2>'.$ETI['titulo_3057'].'</h2>';
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
<input id="boculta3057" name="boculta3057" type="hidden" value="<?php echo $_REQUEST['boculta3057']; ?>" />
<label class="Label30">
<input id="btexpande3057" name="btexpande3057" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3057,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3057']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3057" name="btrecoge3057" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3057,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3057']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3057" style="display:<?php if ($_REQUEST['boculta3057']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu57consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu57consec" name="saiu57consec" type="text" value="<?php echo $_REQUEST['saiu57consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu57consec', $_REQUEST['saiu57consec'], formato_numero($_REQUEST['saiu57consec']));
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
echo $ETI['saiu57id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu57id', $_REQUEST['saiu57id'], formato_numero($_REQUEST['saiu57id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu57vigente'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu57vigente;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu57orden'];
?>
</label>
<label class="Label60">
<input id="saiu57orden" name="saiu57orden" type="text" value="<?php echo $_REQUEST['saiu57orden']; ?>" class="cuatro" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['saiu57titulo'];
?>
<input id="saiu57titulo" name="saiu57titulo" type="text" value="<?php echo $_REQUEST['saiu57titulo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu57titulo']; ?>" class="L"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu57servidorsmtp'];
?>
</label>
<label>
<input id="saiu57servidorsmtp" name="saiu57servidorsmtp" type="text" value="<?php echo $_REQUEST['saiu57servidorsmtp']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu57servidorsmtp']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['saiu57puertomail'];
?>
</label>
<label>
<input id="saiu57puertomail" name="saiu57puertomail" type="text" value="<?php echo $_REQUEST['saiu57puertomail']; ?>" maxlength="10" placeholder="465"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu57autenticacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu57autenticacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu57servidorimpa'];
?>
</label>
<label>
<input id="saiu57servidorimpa" name="saiu57servidorimpa" type="text" value="<?php echo $_REQUEST['saiu57servidorimpa']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu57servidorimpa']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['saiu57puertoimpap'];
?>
</label>
<label>
<input id="saiu57puertoimpap" name="saiu57puertoimpap" type="text" value="<?php echo $_REQUEST['saiu57puertoimpap']; ?>" maxlength="10" placeholder="993"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu57autentimap'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu57autentimap;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu57usuariomail'];
?>
</label>
<label>
<input id="saiu57usuariomail" name="saiu57usuariomail" type="text" value="<?php echo $_REQUEST['saiu57usuariomail']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu57usuariomail']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['saiu57pwdmail'];
?>
</label>
<label>
<input id="saiu57pwdmail" name="saiu57pwdmail" type="password" value="<?php echo $_REQUEST['saiu57pwdmail']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu57pwdmail']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu57confirmado'];
?>
</label>
<label class="Label60">
<div id="div_saiu57confirmado">
<?php
echo $html_saiu57confirmado;
?>
</div>
</label>
<?php
if ($_REQUEST['paso']!=0){
?>
<label class="Label130">
<input id="cmdVerificar" name="cmdVerificar" type="button" class="BotonAzul" value="Verificar" onclick="javascript:verificar()" title="Verificar"/>
</label>
<?php
	}
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3057
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
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3057()" autocomplete="off"/>
</label>
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
	}
?>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3057detalle">
<?php
echo $sTabla3057;
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
echo $ETI['msg_saiu57consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu57consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu57consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu57consec_nuevo" name="saiu57consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu57consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3057" name="titulo_3057" type="hidden" value="<?php echo $ETI['titulo_3057']; ?>" />
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
echo '<h2>'.$ETI['titulo_3057'].'</h2>';
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
<script language="javascript" src="ac_3057.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>