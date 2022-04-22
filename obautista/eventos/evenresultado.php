<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5b miércoles, 30 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
*/
if (file_exists('err_control.php')){require 'err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
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
require_once '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
//if (!file_exists('opts.php')){require 'opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
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
require_login();
$grupo_id=1;//Necesita ajustarlo...
$icodmodulo=1921;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1921='lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1921)){$mensajes_1921='lg/lg_1921_es.php';}
require $mensajes_todas;
require $mensajes_1921;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (!seg_revisa_permiso($icodmodulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		header('Location:noticia.php?ret=evenresultado.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($icodmodulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
require $mensajes_1916;
require $mensajes_1918;
// -- 1916 even16encuesta
require 'lib1916.php';
// -- 1918 Preguntas
require 'lib1918res.php';
// -- 1921 
require 'lib1921.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f1916_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f1916_HtmlTablaResultado');
$xajax->register(XAJAX_FUNCTION,'f1918_HtmlTablaRes');
$xajax->register(XAJAX_FUNCTION,'f1921_ExisteDato');
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
if (isset($_REQUEST['paginaf1916'])==0){$_REQUEST['paginaf1916']=1;}
if (isset($_REQUEST['lppf1916'])==0){$_REQUEST['lppf1916']=20;}
if (isset($_REQUEST['paginaf1918'])==0){$_REQUEST['paginaf1918']=1;}
if (isset($_REQUEST['lppf1918'])==0){$_REQUEST['lppf1918']=20;}
if (isset($_REQUEST['paginaf1921'])==0){$_REQUEST['paginaf1921']=1;}
if (isset($_REQUEST['lppf1921'])==0){$_REQUEST['lppf1921']=20;}
if (isset($_REQUEST['boculta1921'])==0){$_REQUEST['boculta1921']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even16consec'])==0){$_REQUEST['even16consec']='';}
if (isset($_REQUEST['even16id'])==0){$_REQUEST['even16id']='';}
if (isset($_REQUEST['even16encabezado'])==0){$_REQUEST['even16encabezado']='';}
if (isset($_REQUEST['even16porperiodo'])==0){$_REQUEST['even16porperiodo']='N';}
if (isset($_REQUEST['even16porcurso'])==0){$_REQUEST['even16porcurso']='N';}
if (isset($_REQUEST['even16tienepropietario'])==0){$_REQUEST['even16tienepropietario']='N';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bciclo'])==0){$_REQUEST['bciclo']='';}
if (isset($_REQUEST['bcurso'])==0){$_REQUEST['bcurso']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['even16idusuario_td']=$APP->tipo_doc;
	$_REQUEST['even16idusuario_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='even16consec='.$_REQUEST['even16consec'].'';
		}else{
		$sSQLcondi='even16id='.$_REQUEST['even16id'].'';
		}
	$sSQL='SELECT * FROM even16encuesta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['even16consec']=$fila['even16consec'];
		$_REQUEST['even16id']=$fila['even16id'];
		$_REQUEST['even16encabezado']=$fila['even16encabezado'];
		$_REQUEST['even16porperiodo']=$fila['even16porperiodo'];
		$_REQUEST['even16porcurso']=$fila['even16porcurso'];
		$_REQUEST['even16tienepropietario']=$fila['even16tienepropietario'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta1916']=0;
		$bLimpiaHijos=true;
		if ($_REQUEST['even16tienepropietario']=='S'){
			//Mirar que sea propietario.
			$sSQL='SELECT even40id FROM even40encuestaprop WHERE even40idencuesta='.$_REQUEST['even16id'].' AND even40idpropietario='.$_SESSION['unad_id_tercero'].' AND even40activo="S"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sError='Usted no es propietario de esta encuesta, no se permite ver resultados';
				$_REQUEST['paso']=-1;
				}
			}
		}else{
		$_REQUEST['paso']=0;
		}
	}
if ($_REQUEST['paso']==21){
	set_time_limit(0);
	$_REQUEST['paso']=2;
	$sTablaTotal='even31total_'.$_REQUEST['even16id'];
	if (!$objDB->bexistetabla($sTablaTotal)){
		$sError=f1921_ArmarTemporal($_REQUEST['even16id'], $objDB);
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reconstruyendo tabla temporal. <br>';}
		list($sError, $sDebugT)=f1921_VerificarTemporal($_REQUEST['even16id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugT;
		}
	if ($sError==''){
		f1921_ReconstruirTotales($_REQUEST['even16id'], $objDB);
		$sError='Se han reconstruido los totales para la encuesta.';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even16consec']='';
	$_REQUEST['even16id']='';
	$_REQUEST['even16encabezado']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$bHayCiclo=false;
$bHayCurso=false;
if ((int)$_REQUEST['paso']==0){
	}else{
	if ($_REQUEST['even16porperiodo']=='S'){
		$sIds='-99';
		$sSQL='SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['even24idperaca'];
			}
		$objCombos->nuevo('bciclo', $_REQUEST['bciclo'], true, '{'.$ETI['msg_todos'].'}');
		$objCombos->sAccion='paginarf1918()';
		$sSQL=f146_ConsultaCombo('exte02id IN ('.$sIds.')');
		$html_bciclo=$objCombos->html($sSQL, $objDB);
		$bHayCiclo=true;
		}
	if ($_REQUEST['even16porcurso']=='S'){
		$sIds='-99';
		$sSQL='SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta='.$_REQUEST['even16id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['even25idcurso'];
			}
		$objCombos->nuevo('bcurso', $_REQUEST['bcurso'], true, '{'.$ETI['msg_todos'].'}');
		$objCombos->sAccion='paginarf1918()';
		$html_bcurso=$objCombos->html('SELECT unad40id AS id, CONCAT(unad40id, " ", unad40nombre) AS nombre FROM unad40curso WHERE unad40id IN ('.$sIds.') ORDER BY unad40nombre DESC', $objDB);
		$bHayCurso=true;
		}
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar=$objCombos->comboSistema(1921, 1, $objDB, 'paginarf1921()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($icodmodulo, 6, $objDB)){$seg_6=1;}
	if (seg_revisa_permiso($icodmodulo, 5, $objDB)){$seg_5=1;}
	}
//Cargar las tablas de datos
$params[0]='';//$_REQUEST['p1_1921'];
$params[101]=$_REQUEST['paginaf1916'];
$params[102]=$_REQUEST['lppf1916'];
//$params[103]=$_REQUEST['bnombre'];
//$params[104]=$_REQUEST['blistar'];
list($sTabla1916, $sDebugTabla)=f1916_TablaDetalleV2Resultado($params, $objDB, $bDebug);
if ($_REQUEST['paso']!=0){
	//Preguntas
	$params1918[0]=$_REQUEST['even16id'];
	$params1918[101]=$_REQUEST['paginaf1918'];
	$params1918[102]=$_REQUEST['lppf1918'];
	$params1918[103]=1;
	$params1918[104]=$_REQUEST['bciclo'];
	$params1918[105]=$_REQUEST['bcurso'];
	list($sTabla1918, $sDebugT)=f1918_TablaDetalleV2Res($params1918, $objDB, $bDebug);
	}
$et_menu=html_menu($APP->idsistema, $objDB);
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
forma_cabeceraV2($CFG, $SITE, $xajax, $ETI['titulo_1921resultado'], $ETI['app_nombre'].'|index.php@'.$ETI['grupo_nombre'].'|gm.php?id='.$grupo_id.'@'.$ETI['titulo_1921resultado'].'|');
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<?php
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=7"></script>
<script language="javascript" type="text/javascript" charset="UTF-8">
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
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function paginarf1921(){
	var params=new Array();
	params[101]=window.document.frmedita.paginaf1921.value;
	params[102]=window.document.frmedita.lppf1921.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f1921_HtmlTabla(params);
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
function mantener_sesion(){xajax_sesion_mantener();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.even16consec.value;
	if ((datos[1]!='')){
		xajax_f1916_ExisteDato(datos);
		}
	}
function cargaridf1916(llave1){
	window.document.frmedita.even16id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf1916(){
	var params=new Array();
	params[101]=window.document.frmedita.paginaf1916.value;
	params[102]=window.document.frmedita.lppf1916.value;
	params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f1916_HtmlTablaResultado(params);
	}
function paginarf1918(){
	var params=new Array();
	params[0]=window.document.frmedita.even16id.value;
	params[101]=window.document.frmedita.paginaf1918.value;
	params[102]=window.document.frmedita.lppf1918.value;
	params[103]=1;
	params[104]=window.document.frmedita.bciclo.value;
	params[105]=window.document.frmedita.bcurso.value;
	document.getElementById('div_f1918detalle').innerHTML='<h2>Actualizando datos, por favor espere...</h2>';
	xajax_f1918_HtmlTablaRes(params);
	}
function excel1921(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmexportaxls.separa.value=window.document.frmedita.csv_separa.value;
<?php
if ($bHayCiclo){
?>
		window.document.frmexportaxls.v3.value=window.document.frmedita.bciclo.value;
<?php
	}
?>
		window.document.frmexportaxls.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function actualizatotales(){
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
// -->
</script>
<?php
if ($_REQUEST['paso']>0){
?>
<form id="frmexportaxls" name="frmexportaxls" method="post" action="o1921.php" target="_blank">
<input id="separa" name="separa" type="hidden" value="," />
<input id="id1916" name="id1916" type="hidden" value="<?php echo $_REQUEST['even16id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="clave" name="clave" type="hidden" value="" />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
<?php
	}
?>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1921resultado'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=false;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta1921" name="boculta1921" type="hidden" value="<?php echo $_REQUEST['boculta1921']; ?>" />
<label class="Label30">
<input id="btexpande1921" name="btexpande1921" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1921,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1921" name="btrecoge1921" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1921,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1921" style="display:<?php if ($_REQUEST['boculta1921']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['even16consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="even16consec" name="even16consec" type="text" value="<?php echo $_REQUEST['even16consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even16consec', $_REQUEST['even16consec']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['even16id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('even16id', $_REQUEST['even16id']);
?>
</label>
<?php
echo '<input id="even16porperiodo" name="even16porperiodo" type="hidden" value="'.$_REQUEST['even16porperiodo'].'" />';
echo '<input id="even16porcurso" name="even16porcurso" type="hidden" value="'.$_REQUEST['even16porcurso'].'" />';
if ($_REQUEST['paso']>0){
?>
<label class="Label90">
<?php
echo $ETI['msg_separador'];
?>
</label>
<label class="Label130">
<?php
echo html_combo('csv_separa', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=99 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['csv_separa'], $objDB, '', false, '{'.$ETI['msg_todos'].'}', '');
?>
</label>
<label class="Label30">
<input id="cmdExportar" name="cmdExportar" type="button" class="btMiniExcel" value="Exportar" onclick="excel1921()" title="Exportar" />
</label>
<label class="Label30"></label>
<label class="Label30">
<input id="cmdTotales" name="cmdTotales" type="button" class="btMiniActualizar" value="Actualizar Totales" onclick="actualizatotales()" title="Actualizar Totales" />
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo '<b>'.cadena_notildes($_REQUEST['even16encabezado']).'</b>';
?>
</label>
<?php
if ($_REQUEST['paso']!=0){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoactualiza'];
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<?php
if ($_REQUEST['even16porperiodo']=='S'){
?>
<label class="Label60">
<?php
echo $ETI['even21idperaca'];
?>
</label>
<label>
<?php
echo $html_bciclo;
?>
</label>
<?php
	}
if ($_REQUEST['even16porcurso']=='S'){
?>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['even21idcurso'];
?>
</label>
<label>
<?php
echo $html_bcurso;
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_f1918detalle">
<?php
echo $sTabla1918;
?>
</div>
<?php
	}else{
	echo '<input id="csv_separa" name="csv_separa" type="hidden" value="'.$_REQUEST['csv_separa'].'" />';
	}
if (!$bHayCiclo){echo '<input id="bciclo" name="bciclo" type="hidden" value="'.$_REQUEST['bciclo'].'" />';}
if (!$bHayCurso){echo '<input id="bcurso" name="bcurso" type="hidden" value="'.$_REQUEST['bcurso'].'" />';}
?>
<div class="salto1px"></div>
<?php
if ($bconexpande){
	//Este es el cierre del div_p1921
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
echo '<h3>'.$ETI['bloque1resultados'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
Encabezado
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1916()"/>
<!--
Listar
<?php
//echo $html_blistar;
?>
-->
</div>
<div class="salto1px"></div>
<div id="div_f1916detalle">
<?php
echo $sTabla1916;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1921resultado'].'</h2>';
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
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript" type="text/javascript" charset="UTF-8">
<!--
<?php
if ($iSector!=1){
	echo 'expandesector('.$iSector.');
';
	}
if ($bMueveScroll){
	echo 'retornacontrol();
';
	}
?>
-->
</script>
<?php
forma_piedepagina();
?>