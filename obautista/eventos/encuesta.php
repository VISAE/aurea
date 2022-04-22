<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 martes, 15 de marzo de 2016
*/
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require_once '../config.php';
require 'app.php';
//if (!file_exists('opts.php')){require 'opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPasa=true;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPasa=false;}}
if ($bPasa){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
if ((!$bPasa)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
require_login();
$grupo_id=1;//Necesita ajustarlo...
$icodmodulo=1926;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
/*
if (!seg_revisa_permiso($icodmodulo, 1, $objdb)){
	header('Location:nopermiso.php');
	die();
	}
if (noticias_pendientes($objdb)){
	header('Location:noticia.php?ret=encuesta.php');
	die();
	}
*/
//PROCESOS DE LA PAGINA
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1926='lg/lg_1926_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1926)){$mensajes_1926='lg/lg_1926_es.php';}
require $mensajes_todas;
require $mensajes_1926;
$xajax=NULL;
// -- 1926 
require 'lib1926.php';
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($icodmodulo, $_SESSION['unad_id_tercero'], $objdb);}
	}
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21depto');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21ciudad');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21ciudad');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21fechanace');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21idcead');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21idcead');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21perfil');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f1926_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1926_CargarCuerpo');
$xajax->register(XAJAX_FUNCTION,'f1926_GuardaRpta');
$xajax->register(XAJAX_FUNCTION,'f1926_MarcarOpcion');
$xajax->register(XAJAX_FUNCTION,'f1926_GuadaAbierta');
$xajax->processRequest();
if (!$bPasa){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$bLimpiaHijos=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['paginaf1926'])==0){$_REQUEST['paginaf1926']=1;}
if (isset($_REQUEST['lppf1926'])==0){$_REQUEST['lppf1926']=20;}
if (isset($_REQUEST['boculta1926'])==0){$_REQUEST['boculta1926']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even21pais'])==0){$_REQUEST['even21pais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['even21depto'])==0){$_REQUEST['even21depto']='';}
if (isset($_REQUEST['even21ciudad'])==0){$_REQUEST['even21ciudad']='';}
if (isset($_REQUEST['even21fechanace'])==0){$_REQUEST['even21fechanace']='';}//{fecha_hoy();}
if (isset($_REQUEST['even21perfil'])==0){$_REQUEST['even21perfil']=0;}
if (isset($_REQUEST['even21idzona'])==0){$_REQUEST['even21idzona']='';}
if (isset($_REQUEST['even21idcead'])==0){$_REQUEST['even21idcead']='';}
if (isset($_REQUEST['even21idprograma'])==0){$_REQUEST['even21idprograma']='';}
if (isset($_REQUEST['unad11genero'])==0){$_REQUEST['unad11genero']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['idencuesta'])==0){$_REQUEST['idencuesta']=0;}
if (isset($_REQUEST['id21'])==0){$_REQUEST['id21']=0;}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Cerrar la encuesta.
if ($_REQUEST['paso']==2){
	$id21=$_REQUEST['id21'];
	$bTermino=true;
	$sql='SELECT even22id FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22irespuesta=-1 AND even22opcional<>"S" AND even22idpregcondiciona=0 AND even22tiporespuesta IN (0,1)';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$bTermino=false;
		}else{
		//Ver que las respuestas condicionales esten resueltas.
		}
	$sFechaNace='';
	if ($bTermino){
		//Ver que las repuestas de la 21 esten completas.
		$sql='SELECT even21pais, even21depto, even21ciudad, even21fechanace, even21perfil, even21idzona, even21idcead, even21idprograma FROM even21encuestaaplica WHERE even21id='.$id21.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			if ($fila['even21perfil']==0){
				if ($fila['even21idprograma']==0){
					$bTermino=false;
					$sError='Por favor indique el programa en que esta matriculado.';
					}
				}
			if ($fila['even21idcead']==0){$bTermino=false;$sError='No ha diligenciado el CEAD al que esta inscrito';}
			if ($fila['even21idzona']==0){$bTermino=false;$sError='No ha diligenciado la zona a la que pertenece';}
			if ($_REQUEST['unad11genero']==''){$bTermino=false;$sError='No ha diligenciado el sexo al que pertenece';}
			if (!fecha_esvalida($fila['even21fechanace'])){
				$bTermino=false;
				$sError='La fecha de nacimiento no es v&aacute;lida.';
				}else{
				$sFechaNace=$fila['even21fechanace'];
				}
			if ($fila['even21ciudad']==''){$bTermino=false;$sError='No ha diligenciado la ciudad de residencia';}
			if ($fila['even21depto']==''){$bTermino=false;$sError='No ha diligenciado el departamento de residencia';}
			if ($sError!=''){
				//Ver que no tenga menos de 10 años.
				$aFecha=explode('/',$sFechaNace);
				$iMax=fecha_agno()-10;
				if ($aFecha[2]>$iMax){
					$bTermino=false;
					$sError='Su a&ntilde;o de nacimiento no puede ser superior a '.$iMax;
					}
				}
			}
		}
	if (!$bTermino){
		$_REQUEST['paso']=0;
		if ($sError==''){
			$sError='A&uacute;n no ha diligenciado todas las respuestas, por favor termine sus respuestas y vuelva a intentar.';
			}
		}else{
		$_REQUEST['paso']=-1;
		//Actaulizamos la fecha de nacimiento del tercero.
		$sql='SELECT unad11fechanace, unad11genero FROM unad11terceros WHERE unad11id='.$_SESSION['unad_id_tercero'];
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$sUpdate='';
			if ($fila['unad11fechanace']!=$sFechaNace){$sUpdate='unad11fechanace="'.$sFechaNace.'"';}
			if ($fila['unad11genero']!=$_REQUEST['unad11genero']){
				if ($sUpdate!=''){$sUpdate=$sUpdate.', ';}
				$sUpdate='unad11genero="'.$_REQUEST['unad11genero'].'"';
				}
			if ($sUpdate!=''){
				$sUpdate='UPDATE unad11terceros SET '.$sUpdate.' WHERE unad11id='.$_SESSION['unad_id_tercero'];
				$tabla=$objdb->ejecutasql($sUpdate);
				}
			}
		//Cerrar la encuesta.
		$sql='UPDATE even21encuestaaplica SET even21fechapresenta="'.fecha_hoy().'", even21terminada="S" WHERE even21id='.$id21.'';
		$tabla=$objdb->ejecutasql($sql);
		//Totalizar las preguntas.
		$sql='SELECT even21idencuesta FROM even21encuestaaplica WHERE even21id='.$id21.'';
		$tabla=$objdb->ejecutasql($sql);
		$fila=$objdb->sf($tabla);
		$id16=$fila['even21idencuesta'];
		$sql='SELECT even18id, even18tiporespuesta FROM even18encuestapregunta WHERE even18idencuesta='.$id16.' AND even18tiporespuesta IN (0,1)';
		$tabla18=$objdb->ejecutasql($sql);
		while($fila18=$objdb->sf($tabla18)){
			$iTotal=0;
			for ($p=0;$p<=9;$p++){
				$rpta[$p]=0;
				}
			$sql='SELECT T2.even22irespuesta, COUNT(T2.even22id) AS total
FROM even21encuestaaplica AS TB, even22encuestarpta AS T2 
WHERE TB.even21idencuesta='.$id16.' AND TB.even21terminada="S" AND TB.even21id=T2.even22idaplica AND T2.even22idpregunta='.$fila18['even18id'].'
GROUP BY T2.even22irespuesta';
			$tabla21=$objdb->ejecutasql($sql);
			while($fila21=$objdb->sf($tabla21)){
				$iTotal=$iTotal+$fila21['total'];
				if ($fila21['even22irespuesta']<>-1){
					switch($fila18['even18tiporespuesta']){
						case 0:
						$rpta[$fila21['even22irespuesta']]=$fila21['total'];
						break;
						case 1:
						$rpta[$fila21['even22irespuesta']]=$fila21['total'];
						break;
						}
					}
				}
			$sql='UPDATE even18encuestapregunta SET even18rptatotal='.$iTotal.', even18rpta0='.$rpta[0].' ,even18rpta1='.$rpta[1].' ,even18rpta2='.$rpta[2].' ,even18rpta3='.$rpta[3].' ,even18rpta4='.$rpta[4].' ,even18rpta5='.$rpta[5].' ,even18rpta6='.$rpta[6].' ,even18rpta7='.$rpta[7].' ,even18rpta8='.$rpta[8].' ,even18rpta9='.$rpta[9].' WHERE even18id='.$fila18['even18id'].'';
			$result=$objdb->ejecutasql($sql);
			//echo $sql.'<br>';
			}
		$sql='SELECT even18id FROM even18encuestapregunta WHERE even18idencuesta='.$id16.' AND even18tiporespuesta=2';
		$tabla18=$objdb->ejecutasql($sql);
		while($fila18=$objdb->sf($tabla18)){
			$sql='SELECT COUNT(T2.even22id) AS total, SUM(T2.even22rpta1) AS r1, SUM(T2.even22rpta2) AS r2, SUM(T2.even22rpta3) AS r3, SUM(T2.even22rpta4) AS r4, SUM(T2.even22rpta5) AS r5, SUM(T2.even22rpta6) AS r6, SUM(T2.even22rpta7) AS r7, SUM(T2.even22rpta8) AS r8, SUM(T2.even22rpta9) AS r9
FROM even21encuestaaplica AS TB, even22encuestarpta AS T2 
WHERE TB.even21idencuesta='.$id16.' AND TB.even21terminada="S" AND TB.even21id=T2.even22idaplica AND T2.even22idpregunta='.$fila18['even18id'].'
GROUP BY T2.even22irespuesta';
			$tabla21=$objdb->ejecutasql($sql);
			$fila21=$objdb->sf($tabla21);
			$iTotal=$fila21['total'];
			$rpta[1]=$fila21['r1'];
			$rpta[2]=$fila21['r2'];
			$rpta[3]=$fila21['r3'];
			$rpta[4]=$fila21['r4'];
			$rpta[5]=$fila21['r5'];
			$rpta[6]=$fila21['r6'];
			$rpta[7]=$fila21['r7'];
			$rpta[8]=$fila21['r8'];
			$rpta[9]=$fila21['r9'];
			$sql='UPDATE even18encuestapregunta SET even18rptatotal='.$iTotal.', even18rpta0=0 ,even18rpta1='.$rpta[1].' ,even18rpta2='.$rpta[2].' ,even18rpta3='.$rpta[3].' ,even18rpta4='.$rpta[4].' ,even18rpta5='.$rpta[5].' ,even18rpta6='.$rpta[6].' ,even18rpta7='.$rpta[7].' ,even18rpta8='.$rpta[8].' ,even18rpta9='.$rpta[9].' WHERE even18id='.$fila18['even18id'].'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even21pais']=$_SESSION['unad_pais'];
	$_REQUEST['even21depto']='';
	$_REQUEST['even21ciudad']='';
	$_REQUEST['even21fechanace']='';//fecha_hoy();
	$_REQUEST['even21perfil']=0;
	$_REQUEST['even21idzona']='';
	$_REQUEST['even21idcead']='';
	$_REQUEST['even21idprograma']='';
	$_REQUEST['paso']=0;
	$_REQUEST['idencuesta']=0;
	$_REQUEST['id21']=0;
	$sql='SELECT unad11fechanace, unad11genero FROM unad11terceros WHERE unad11id='.$_SESSION['unad_id_tercero'];
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$_REQUEST['unad11genero']=$fila['unad11genero'];
		}else{
		$_REQUEST['unad11genero']='';
		}
	//Revisar que encuestas debe hacer....
	f1926_CargarEncuestas($_SESSION['unad_id_tercero'], $objdb);
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
$iNumEncuestas=0;
$iCaracter=-1;
if ($_REQUEST['idencuesta']!=0){
	$iNumEncuestas=1;
	}else{
	list($_REQUEST['idencuesta'], $_REQUEST['id21'])=f1926_Siguiente($_SESSION['unad_id_tercero'], $objdb);
	if ($_REQUEST['idencuesta']!=0){$iNumEncuestas=1;}
	}
if ($_REQUEST['idencuesta']!=0){
	$html_unad11genero=html_combo('unad11genero', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11genero'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	$sTituloEncuesta='NO SE ENCUENTRA LA ENCUESTA {REF '.$_REQUEST['idencuesta'].'}';
	$html_preguntas='';
	$html_cierre='';
	$bVuelve=true;
	$sql='SELECT * FROM even16encuesta WHERE even16id='.$_REQUEST['idencuesta'].'';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sTituloEncuesta=cadena_notildes($fila['even16encabezado']);
		$iCaracter=$fila['even16caracter'];
		$html_cierre='';
		$sql='SELECT T1.even21idperaca, T1.even21idcurso, T1.even21idbloquedo, T1.even21terminada, T1.even21pais, T1.even21depto, T1.even21ciudad, T1.even21fechanace, T1.even21idzona, T1.even21idcead, T1.even21perfil, T1.even21idprograma, T1.even21obligatoria 
FROM even21encuestaaplica AS T1 
WHERE T1.even21id='.$_REQUEST['id21'].'';
		$tabla21=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla21)>0){
			$bVuelve=false;
			$fila21=$objdb->sf($tabla21);
			$_REQUEST['even21pais']=$fila21['even21pais'];
			$_REQUEST['even21depto']=$fila21['even21depto'];
			$_REQUEST['even21ciudad']=$fila21['even21ciudad'];
			$_REQUEST['even21fechanace']=$fila21['even21fechanace'];
			$_REQUEST['even21idzona']=$fila21['even21idzona'];
			$_REQUEST['even21idcead']=$fila21['even21idcead'];
			$_REQUEST['even21perfil']=$fila21['even21perfil'];
			$_REQUEST['even21idprograma']=$fila21['even21idprograma'];
			if ($fila21['even21terminada']=='S'){
				$html_cierre='';
				$bVuelve=true;
				}else{
				if ($fila21['even21obligatoria']!='S'){$bVuelve=true;}
				$html_cierre='<div class="salto1px"></div><div class="ir_derecha">
<input id="cmdTermina" name="cmdTermina" type="button" value="Terminar" class="btSoloProceso" onclick="terminar()" title="Terminar"/>
</div>';
				}
			}
		$html_preguntas=f1926_Html_Respuestas($_REQUEST['id21'], $objdb);
		}
	if ($bVuelve){
		$html_cierre=$html_cierre.$ETI['msg_vuelveindex'];
		}
	$html_even21pais=html_combo('even21pais', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['even21pais'], $objdb, 'carga_combo_even21depto();', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21depto=html_combo_even21depto($objdb, $_REQUEST['even21depto'], $_REQUEST['even21pais']);
	$html_even21ciudad=html_combo_even21ciudad($objdb, $_REQUEST['even21ciudad'], $_REQUEST['even21depto']);
	$html_even21perfil=html_combo('even21perfil', 'even30id', 'even30nombre', 'even30perfilencuesta', '', 'even30id', $_REQUEST['even21perfil'], $objdb, 'guardar_even21perfil()', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21idzona=html_combo('even21idzona', 'unad23id', 'unad23nombre', 'unad23zona', '', 'unad23nombre', $_REQUEST['even21idzona'], $objdb, 'carga_combo_even21idcead();', true, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21idcead=html_combo_even21idcead($objdb, $_REQUEST['even21idcead'], $_REQUEST['even21idzona']);
	$html_even21idprograma=html_combo('even21idprograma', 'exte03id', 'exte03nombre', 'exte03programa', '', 'exte03nombre', $_REQUEST['even21idprograma'], $objdb, 'guardar_even21idprograma()', true, '{'.$ETI['msg_seleccione'].'}', '');
	}
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objdb);
//$html_blistar=html_combo('blistar', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1926 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar'], $objdb, 'paginarf1926()', true, '{'.$ETI['msg_todos'].'}', '');
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($icodmodulo, 6, $objdb)){$seg_6=1;}
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($icodmodulo, 5, $objdb)){$seg_5=1;}
	}
//Cargar las tablas de datos
//$et_menu=html_menu($APP->idsistema, $objdb);
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
$navigation=build_navigation(array(array('name'=>$ETI['app_nombre'], 'link'=>'index.php', 'type'=>'misc'),array('name'=>$ETI['grupo_nombre'], 'link'=>'gm.php?id='.$grupo_id, 'type'=>'misc'),array('name'=>$ETI['titulo_1926'], 'link'=>'', 'type'=>'misc')));
forma_cabecera($CFG, $SITE, $ETI['titulo_1926'], $navigation, $xajax);
//echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css?v=3" type="text/css"/>
<?php
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=5"></script>
<script language="javascript" type="text/javascript" charset="UTF-8">
<!--
function limpiapagina(){
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	var dpaso=window.document.frmedita.paso;
	dpaso.value=parseInt(dpaso.value)+10;
	window.document.frmedita.submit();
	}
function cambiapagina(){
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
function MensajeAlarma(sHTML){
	document.getElementById('alarma').innerHTML=sHTML;
	}
function carga_combo_even21depto(){
	var params=new Array();
	params[0]=window.document.frmedita.even21pais.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_Cargar_even21depto(params);
	}
function carga_combo_even21ciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.even21depto.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_Cargar_even21ciudad(params);
	}
function guardar_even21ciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.even21ciudad.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_f1621_Guardar_even21ciudad(params);
	}
function guardar_even21fechanace(){
	var params=new Array();
	params[0]=window.document.frmedita.even21fechanace.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_f1621_Guardar_even21fechanace(params);
	}
function carga_combo_even21idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idzona.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_Cargar_even21idcead(params);
	}
function guardar_even21idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idcead.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_f1621_Guardar_even21idcead(params);
	}
function guardar_even21perfil(){
	var params=new Array();
	params[0]=window.document.frmedita.even21perfil.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_f1621_Guardar_even21perfil(params);
	}
function guardar_even21idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idprograma.value;
	params[1]=window.document.frmedita.id21.value;
	xajax_f1621_Guardar_even21idprograma(params);
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
function CargarCuerpo(){
	var params=new Array();
	params[0]=window.document.frmedita.id21.value;
	xajax_f1926_CargarCuerpo(params);
	}
function selrpta(idRpta, valor, itipo, iDiverge){
	var params=new Array();
	params[1]=idRpta;
	params[2]=valor;
	params[3]=itipo;
	params[4]=iDiverge;
	params[5]=window.document.frmedita.id21.value;
	xajax_f1926_GuardaRpta(params);
	}
function marcaropcion(idRpta, iConsec, bChequeada){
	var iValor=0;
	if (bChequeada){iValor=1;}
	var params=new Array();
	params[1]=idRpta;
	params[2]=iConsec;
	params[3]=iValor;
	xajax_f1926_MarcarOpcion(params);
	}
function rptabierta(idRpta, sValor){
	var params=new Array();
	params[1]=idRpta;
	params[2]=sValor;
	xajax_f1926_GuadaAbierta(params);
	}
function terminar(){
	if (confirm('Gracias por su tiempo, confirma que ha respondido todas las preguntas?')){
		expandesector(98);
		window.document.frmedita.paso.value=2;
		window.document.frmedita.submit();
		}
	}
// -->
</script>
<form id="frmimprime" name="frmimprime" method="post" action="unadverrpt.php" target="_blank">
<input id="paso_rpt" name="paso_rpt" type="hidden" value="1" />
<input id="id_rpt" name="id_rpt" type="hidden" value="<?php echo $id_rpt; ?>" />
<input id="alias" name="alias" type="hidden" value="reporte" />
<input id="variable" name="variable" type="hidden" value="<?php echo 'valor_variable'; ?>" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="idencuesta" name="idencuesta" type="hidden" value="<?php echo $_REQUEST['idencuesta']; ?>" />
<input id="id21" name="id21" type="hidden" value="<?php echo $_REQUEST['id21']; ?>" />
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['idencuesta']==0){
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '&nbsp;&nbsp;<h2>'.$ETI['titulo_1926'].'</h2>';
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
<input id="boculta1926" name="boculta1926" type="hidden" value="<?php echo $_REQUEST['boculta1926']; ?>" />
<label class="Label30">
<input id="btexpande1926" name="btexpande1926" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1926,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1926']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1926" name="btrecoge1926" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1926,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1926']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1926" style="display:<?php if ($_REQUEST['boculta1926']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
if ($iNumEncuestas==0){
	echo $ETI['msg_noencuestas'].'';
	}else{
	if ($iCaracter>0){
?>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
Apreciado usuario, la siguiente encuesta es de caracter obligatorio, por favor diligenciela para poder volver al acceso de cursos virtuales.
</div>
<div class="salto1px"></div>
</div>
<?php
		}
?>
<div class="MarquesinaMedia">
<?php
	//Deberiamos tener un numero de encuesta...
	echo $sTituloEncuesta;
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['msg_lugar'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21pais'];
?>
</label>
<label>
<?php
echo $html_even21pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21depto'];
?>
</label>
<label>
<div id="div_even21depto">
<?php
echo $html_even21depto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21ciudad'];
?>
</label>
<label>
<div id="div_even21ciudad">
<?php
echo $html_even21ciudad;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21fechanace'];
?>
</label>
<div class="Campo300">
<?php
echo html_fecha('even21fechanace', $_REQUEST['even21fechanace'], true, 'guardar_even21fechanace()', 1900, fecha_agno());//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_genero'];
?>
</label>
<label>
<?php
echo $html_unad11genero;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['msg_vinculo'];
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idzona'];
?>
</label>
<label>
<?php
echo $html_even21idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idcead'];
?>
</label>
<label>
<div id="div_even21idcead">
<?php
echo $html_even21idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21perfil'];
?>
</label>
<label>
<?php
echo $html_even21perfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idprograma'];
?>
</label>
<label>
<?php
echo $html_even21idprograma;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div id="div_respuestas">
<?php
	echo $html_preguntas;
?>
</div>
<div class="salto1px"></div>
<?php
	echo $html_cierre;
	}
if ($bconexpande){
	//Este es el cierre del div_p1926
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
echo '<h2>'.$ETI['titulo_1926'].'</h2>';
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


</form>
</div><!-- /DIV_interna -->
<div class="flotante">
</div>
<div id="alarma" class="alarma" align="center"><?php echo $sError; ?></div>
<?php
if ($iSector!=1){
	//El script que cambia el sector que se muestra
?>
<script language="javascript" type="text/javascript" charset="UTF-8">
<!--
expandesector(<?php echo $iSector; ?>);
-->
</script>
<?php
	}
forma_piedepagina();
?>