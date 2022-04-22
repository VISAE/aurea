<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.15.8 martes, 01 de noviembre de 2016
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
$icodmodulo=1900;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1900='lg/lg_1900_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1900)){$mensajes_1900='lg/lg_1900_es.php';}
require $mensajes_todas;
require $mensajes_1900;
$xajax=NULL;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
if ($_SESSION['unad_id_tercero']==0){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objdb)){
		header('Location:noticia.php?ret=sys.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($icodmodulo, $_SESSION['unad_id_tercero'], $objdb);}
	}
// -- 1900 
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f1900_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1900_ExisteDato');
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
if (isset($_REQUEST['paginaf1900'])==0){$_REQUEST['paginaf1900']=1;}
if (isset($_REQUEST['lppf1900'])==0){$_REQUEST['lppf1900']=20;}
if (isset($_REQUEST['boculta1900'])==0){$_REQUEST['boculta1900']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even16consec'])==0){$_REQUEST['even16consec']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
/*
	if ($sError==''){
		$sql='SELECT * FROM tablaexterna WHERE idexterno='.$_REQUEST['CampoRevisa'].' LIMIT 0, 1';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$sError=$ERR['p1'];//Incluya la explicacion al error en el archivo de idioma
			}
		}
*/
	if ($sError==''){
		//accion a ejecutar
		if ($sError==''){
			if ($audita[4]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objdb);}
			$_REQUEST['paso']=-1;
			$sError=$ETI['msg_itemeliminado'];
			$iTipoError=1;
			}
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=0;
	$_REQUEST['even16consec']=numeros_validar($_REQUEST['even16consec']);
	if ($_REQUEST['even16consec']==''){
		$sError='No ha ingresado numero de encuesta.';
		}
	if ($sError==''){
		$bPorPeriodo=false;
		$bPorCurso=false;
		$sql='SELECT even16id, even16porperiodo, even16porcurso FROM even16encuesta WHERE even16consec='.$_REQUEST['even16consec'];
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$even16id=$fila['even16id'];
			if ($fila['even16porperiodo']=='S'){$bPorPeriodo=true;}
			if ($fila['even16porcurso']=='S'){$bPorCurso=true;}
			}else{
			$sError='No se encuentra la encuenta con consecutivo '.$_REQUEST['even16consec'];
			}
		}
	if ($sError==''){
		$bPrimero=false;
		set_time_limit(0);
		$sIdsPeracas='-99';
		$sql='SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta='.$even16id.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			$sIdsPeracas=$sIdsPeracas.','.$fila['even24idperaca'];
			}
		$sIdsCursos='-99';
		$sql='SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta='.$even16id.' AND even25activo="S"';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			$sIdsCursos=$sIdsCursos.','.$fila['even25idcurso'];
			}
		$iProcesadas=0;
		$iAbiertas=0;
		$sql='SELECT even21idtercero, even21idperaca, even21idcurso, even21id FROM even21encuestaaplica WHERE even21idencuesta='.$even16id.' AND ((even21idperaca=0) AND (even21idcurso=0)) AND even21terminada="S" LIMIT 0, 1000';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta general aplicada '.$sql.'<br>';}
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			$idTercero=$fila['even21idtercero'];
			$id21=$fila['even21id'];
			$sql='SELECT unad47peraca, unad47idcurso FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47peraca IN ('.$sIdsPeracas.') AND unad47idcurso IN ('.$sIdsCursos.') AND unad47activo="S"';
			if (!$bPrimero){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta al tercero '.$sql.'<br>';}
				}
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$fila47=$objdb->sf($result);
				//Le actuaalizamos el peraca y curso.
				$sql='UPDATE even21encuestaaplica SET even21idperaca='.$fila47['unad47peraca'].', even21idcurso='.$fila47['unad47idcurso'].' WHERE even21id='.$id21.' AND even21idencuesta='.$even16id.'';
				$result=$objdb->ejecutasql($sql);
				if (!$bPrimero){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se marca la encuesta del tercero '.$idTercero.'. '.$sql.'<br>';}
					}
				$iProcesadas++;
				}else{
				$sql='UPDATE even21encuestaaplica SET even21idperaca=-1 WHERE even21id='.$id21.' AND even21idencuesta='.$even16id.'';
				$result=$objdb->ejecutasql($sql);
				if (!$bPrimero){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se retira la encuesta. '.$sql.'<br>';}
					}
				}
			$bPrimero=true;
			}
		$sError='Registros procesados '.$iProcesadas;
		$iTipoError=1;
		}
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objdb);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar=$objCombos->comboSistema(1900, 1, $objdb, 'paginarf1900()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($icodmodulo, 6, $objdb)){$seg_6=1;}
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($icodmodulo, 5, $objdb)){$seg_5=1;}
	}
//Cargar las tablas de datos
$et_menu=html_menu($APP->idsistema, $objdb);
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
forma_cabeceraV2($CFG, $SITE, $xajax, $ETI['titulo_1900'], $ETI['app_nombre'].'|index.php@'.$ETI['grupo_nombre'].'|gm.php?id='.$grupo_id.'@'.$ETI['titulo_1900'].'|');
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
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
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
function ajustarenc(){
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
// -->
</script>
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
echo '<h2>'.$ETI['titulo_1900'].'</h2>';
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
<input id="boculta1900" name="boculta1900" type="hidden" value="<?php echo $_REQUEST['boculta1900']; ?>" />
<label class="Label30">
<input id="btexpande1900" name="btexpande1900" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1900,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1900']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1900" name="btrecoge1900" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1900,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1900']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1900" style="display:<?php if ($_REQUEST['boculta1900']==0){echo 'block'; }else{echo 'none';} ?>;">
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
<input id="even16consec" name="even16consec" type="text" value="<?php echo $_REQUEST['even16consec']; ?>"  class="cuatro"/>
</label>
<label class="Label130">
<input id="cmdAjusta" name="cmdAjusta" type="button" class="btSoloProceso" value="Ajustar" onclick="ajustarenc();" />
</label>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p1900
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
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


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<input id="titulo_1900" name="titulo_1900" type="hidden" value="<?php echo $ETI['titulo_1900']; ?>" />
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1900'].'</h2>';
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