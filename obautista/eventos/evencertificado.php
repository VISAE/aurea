<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.8 viernes, 03 de octubre de 2014
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require_once '../config.php';
require 'app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require_login();
$grupo_id=1;//Necesita ajustarlo...
$icodmodulo=1906;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
if (!seg_revisa_permiso($icodmodulo, 1, $objdb)){
	header("Location:nopermiso.php");
	die();
	}
//PROCESOS DE LA PAGINA
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1906='lg/lg_1906_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1906)){$mensajes_1906='lg/lg_1906_es.php';}
$mensajes_1907='lg/lg_1907_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1907)){$mensajes_1907='lg/lg_1907_es.php';}
require $mensajes_todas;
require $mensajes_1906;
require $mensajes_1907;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($icodmodulo, $_SESSION['unad_id_tercero'], $objdb);}
	}
$bcargo=false;
$sError='';
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['paginaf1906'])==0){$_REQUEST['paginaf1906']=1;}
if (isset($_REQUEST['lppf1906'])==0){$_REQUEST['lppf1906']=20;}
if (isset($_REQUEST['boculta1906'])==0){$_REQUEST['boculta1906']=0;}
if (isset($_REQUEST['paginaf1907'])==0){$_REQUEST['paginaf1907']=1;}
if (isset($_REQUEST['lppf1907'])==0){$_REQUEST['lppf1907']=20;}
if (isset($_REQUEST['boculta1907'])==0){$_REQUEST['boculta1907']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even06consec'])==0){$_REQUEST['even06consec']='';}
if (isset($_REQUEST['even06id'])==0){$_REQUEST['even06id']='';}
if (isset($_REQUEST['even06titulo'])==0){$_REQUEST['even06titulo']='';}
if (isset($_REQUEST['even06origenimagen'])==0){$_REQUEST['even06origenimagen']=0;}
if (isset($_REQUEST['even06idimagen'])==0){$_REQUEST['even06idimagen']=0;}
if ((int)$_REQUEST['paso']>0){
	//Variables
	if (isset($_REQUEST['even07consec'])==0){$_REQUEST['even07consec']='';}
	if (isset($_REQUEST['even07id'])==0){$_REQUEST['even07id']='';}
	if (isset($_REQUEST['even07idvariable'])==0){$_REQUEST['even07idvariable']='';}
	if (isset($_REQUEST['even07izquierda'])==0){$_REQUEST['even07izquierda']='';}
	if (isset($_REQUEST['even07arriba'])==0){$_REQUEST['even07arriba']='';}
	if (isset($_REQUEST['even07ancho'])==0){$_REQUEST['even07ancho']='';}
	if (isset($_REQUEST['even07fuente'])==0){$_REQUEST['even07fuente']='';}
	if (isset($_REQUEST['even07tipofuente'])==0){$_REQUEST['even07tipofuente']='';}
	if (isset($_REQUEST['even07fuentetam'])==0){$_REQUEST['even07fuentetam']='';}
	if (isset($_REQUEST['even07fuentecolor'])==0){$_REQUEST['even07fuentecolor']='';}
	if (isset($_REQUEST['even07alineacion'])==0){$_REQUEST['even07alineacion']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Variables
	//if (isset($_REQUEST['bnombre1907'])==0){$_REQUEST['bnombre1907']='';}
	//if (isset($_REQUEST['blistar1907'])==0){$_REQUEST['blistar1907']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sqlcondi='even06consec='.$_REQUEST['even06consec'].'';
		}else{
		$sqlcondi='even06id='.$_REQUEST['even06id'].'';
		}
	$sql='SELECT * FROM even06certificados WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$_REQUEST['even06consec']=$fila['even06consec'];
		$_REQUEST['even06id']=$fila['even06id'];
		$_REQUEST['even06titulo']=$fila['even06titulo'];
		$_REQUEST['even06origenimagen']=$fila['even06origenimagen'];
		$_REQUEST['even06idimagen']=$fila['even06idimagen'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta1906']=0;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	// -- Se inicia validando todas las posibles entradas de usuario.
	$_REQUEST['even06consec']=numeros_validar($_REQUEST['even06consec']);
	$_REQUEST['even06titulo']=htmlspecialchars($_REQUEST['even06titulo']);
	$_REQUEST['even06origenimagen']=numeros_validar($_REQUEST['even06origenimagen']);
	$_REQUEST['even06idimagen']=numeros_validar($_REQUEST['even06idimagen']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($_REQUEST['even06origenimagen']==''){$_REQUEST['even06origenimagen']=0;}
	if ($_REQUEST['even06idimagen']==''){$_REQUEST['even06idimagen']=0;}
	// -- Seccion para validar los posibles causales de error.
	if ($_REQUEST['even06titulo']==''){$sError=$ERR['even06titulo'];}
	//if ($_REQUEST['even06id']==''){$sError=$ERR['even06id'];}//CONSECUTIVO
	//if ($_REQUEST['even06consec']==''){$sError=$ERR['even06consec'];}//CONSECUTIVO
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($_REQUEST['paso']==10){
			if ($_REQUEST['even06consec']==''){
				$_REQUEST['even06consec']=tabla_consecutivo('even06certificados', 'even06consec', '', $objdb);
				if ($_REQUEST['even06consec']==-1){$sError=$objdb->serror;}
				}
			$sql='SELECT even06consec FROM even06certificados WHERE even06consec='.$_REQUEST['even06consec'].'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($_REQUEST['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$_REQUEST['even06id']=tabla_consecutivo('even06certificados','even06id', '', $objdb);
			if ($_REQUEST['even06id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($_REQUEST['paso']==10){
			$scampos='even06consec, even06id, even06titulo, even06origenimagen, even06idimagen';
			$svalores=''.$_REQUEST['even06consec'].', '.$_REQUEST['even06id'].', "'.$_REQUEST['even06titulo'].'", 0, 0';
			$sql='INSERT INTO even06certificados ('.$scampos.') VALUES ('.$svalores.');';
			$idaccion=2;
			$sdetalle=$scampos.'['.$svalores.']';
			$bpasa=true;
			}else{
			$scampo[1]='even06titulo';
			$sdato[1]=$_REQUEST['even06titulo'];
			$numcmod=1;
			$sWhere='even06consec='.$_REQUEST['even06consec'].'';
			$sql='SELECT * FROM even06certificados WHERE '.$sWhere;
			$sdatos='';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			$sql='UPDATE even06certificados SET '.$sdatos.' WHERE '.$sWhere.';';
			$idaccion=3;
			$sdetalle=$sdatos.'['.$sWhere.']';
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' ..<!-- '.$sql.' -->';
				$_REQUEST['paso']=0;
				}else{
				if ($audita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, 0, $sdetalle, $objdb);}
				$_REQUEST['paso']=2;
				}
			}else{
			$_REQUEST['paso']=2;
			}
		}else{
		$_REQUEST['paso']=$_REQUEST['paso']-10;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	$_REQUEST['even06consec']=numeros_validar($_REQUEST['even06consec']);
	$_REQUEST['even06id']=numeros_validar($_REQUEST['even06id']);
	if ($sError==''){
		$sql='SELECT even07idcertificado FROM even07certvariable WHERE even07idcertificado='.$_REQUEST['even06id'].'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$sError='El reporte contiene Variables, no es posible eliminar';
			}
		}
/*
	$sql='SELECT * FROM tablaexterna WHERE idexterno='.$_REQUEST['CampoRevisa'].' LIMIT 0, 1';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$sError=$ERR['p1'];//Incluya la explicacion al error en el archivo de idioma
		}
*/
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sWhere='even06id='.$_REQUEST['even06id'];
		//$sWhere='even06consec='.$_REQUEST['even06consec'].'';
		$sql='DELETE FROM even06certificados WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sql.' -->';
			}else{
			if ($audita[4]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objdb);}
			$_REQUEST['paso']=-1;
			}
		}
	}
function f1906_TablaDetalle($params, $objdb){
	$mensajes_1906='lg/lg_1906_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1906)){$mensajes_1906='lg/lg_1906_es.php';}
	require $mensajes_1906;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$objdb->xajax();
	$sqladd='1';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Consec, Id, Titulo, Origenimagen, Imagen';
	$sql='SELECT TB.even06consec, TB.even06id, TB.even06titulo, TB.even06origenimagen, TB.even06idimagen 
FROM even06certificados AS TB 
WHERE  '.$sqladd.'';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1906" name="consulta_1906" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1906" name="titulos_1906" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf1906" name="paginaf1906" type="hidden" value="'.$pagina.'"/><input id="lppf1906" name="lppf1906" type="hidden" value="'.$lineastabla.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even06consec'].'</b></td>
<td><b>'.$ETI['even06titulo'].'</b></td>
<td><b>'.$ETI['even06idimagen'].'</b></td>
<td align="right">
'.html_paginador("paginaf1906", $registros, $lineastabla, $pagina, "paginarf1906()").'
'.html_lpp("lppf1906", $lineastabla, "paginarf1906()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_even06idimagen=html_lnkarchivo((int)$filadet['even06origenimagen'], (int)$filadet['even06idimagen']);
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1906('."'".$filadet['even06id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even06consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even06titulo']).$sSufijo.'</td>
<td>'.$et_even06idimagen.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even06consec']='';
	$_REQUEST['even06id']='';
	$_REQUEST['even06titulo']='';
	$_REQUEST['even06origenimagen']=0;
	$_REQUEST['even06idimagen']=0;
	$_REQUEST['paso']=0;
	}
//COMPONENTE AJAX
$xajax=NULL;
//Funciones de las tablas hijas que posiblemente deban ir a una libreria.
// -- 1907 Variables
require 'lib1907.php';
function elimina_archivo_even06idimagen($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	archivo_eliminar('even06certificados', 'even06id', 'even06origenimagen', 'even06idimagen', $idpadre, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_even06idimagen");
	return $objResponse;
	}
function f1906_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$babierta=true;
	$sDetalle=f1906_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1906detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function bExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='SELECT even06consec FROM even06certificados WHERE even06consec='.$datos[1].'';
	$res=$objdb->ejecutasql($sql);
	if ($objdb->nf($res)!=0){
		$objResponse=new xajaxResponse();
		$objResponse->call("cambiapaginaV2");
		return $objResponse;
		}
	}
// -- Espacio para incluir funciones xajax personalizadas.
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_even06idimagen');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona');
$xajax->register(XAJAX_FUNCTION,'f1906_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'bExisteDato');
$xajax->register(XAJAX_FUNCTION,'f1907_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1907_Traer');
$xajax->register(XAJAX_FUNCTION,'f1907_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1907_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1907_PintarLlaves');
$xajax->processRequest();
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
/*
*/
if ((int)$_REQUEST['paso']>0){
/*
	list($even07idvariable_nombre, $sErrorDet)=tabla_campoxid('even15vars','even15nombre','even15id',$_REQUEST['even07idvariable'],'{'.$ETI['msg_sindato'].'}', $objdb);
	list($even07fuente_nombre, $sErrorDet)=tabla_campoxid('unad33reportefuente','unad33nombre','unad33id',$_REQUEST['even07fuente'],'{'.$ETI['msg_sindato'].'}', $objdb);
	list($even07fuentetam_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['even07fuentetam'],'{'.$ETI['msg_sindato'].'}', $objdb);
*/
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objdb);
$params[0]='';//$_REQUEST['p1_1906'];
$params[101]=$_REQUEST['paginaf1906'];
$params[102]=$_REQUEST['lppf1906'];
//$params[103]=$_REQUEST['bnombre'];
//$params[104]=$_REQUEST['blistar'];
$sTabla1906=f1906_TablaDetalle($params, $objdb);
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
$navigation=build_navigation(array(array('name'=>$ETI['app_nombre'], 'link'=>'index.php', 'type'=>'misc'),array('name'=>$ETI['grupo_nombre'], 'link'=>'gm.php?id='.$grupo_id, 'type'=>'misc'),array('name'=>$ETI['titulo_1906'], 'link'=>'', 'type'=>'misc')));
forma_cabecera($CFG, $SITE, $ETI['titulo_1906'], $navigation, $xajax);
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
echo html_menu($APP->idsistema, $objdb);
forma_mitad();
if (false){
	}
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>unad_todas.js"></script>
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
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1906.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1906.value;
	window.document.frmlista.submit();
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.even06consec.value;
	if ((datos[1]!='')){
		xajax_bExisteDato(datos);
		}
	}
function cargadato(llave1){
	var dpaso=window.document.frmedita.paso;
	var deven06consec=window.document.frmedita.even06consec;
	deven06consec.value=String(llave1);
	dpaso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf1906(llave1){
	var dpaso=window.document.frmedita.paso;
	var did=window.document.frmedita.even06id;
	did.value=String(llave1);
	dpaso.value=3;
	window.document.frmedita.submit();
	}
function buscar(){
	var datos = new Array();
	datos = window.showModalDialog("buscar.php?id=1906", datos, ',,dialogHeight:550px; dialogWidth:800px');
	if (datos[0]!=''){
		cargadato(String(datos[0]));
		}
	}
function limpia_even06idimagen(){
	window.document.frmedita.even06origenimagen.value=0;
	window.document.frmedita.even06idimagen.value=0;
	var da_Imagen=document.getElementById('div_even06idimagen');
	da_Imagen.innerHTML='&nbsp;';
	verboton('beliminaeven06idimagen','none');
	//paginarf0000();
	}
function anexaeven06idimagen(){
	var did=window.document.frmedita.even06id;
	var didorigen=window.document.frmedita.even06origenimagen;
	var didarchivo=window.document.frmedita.even06idimagen;
	var datos = new Array();
	datos=window.showModalDialog('archivopop.php?ref=1906&id='+did.value, datos, ',,dialogHeight:400px; dialogWidth:800px');
	if (datos[1]!='0'){
		didorigen.value=String(datos[0]);
		didarchivo.value=String(datos[1]);
		verboton('beliminaeven06idimagen','block');
		}
	archivo_lnk(didorigen.value, didarchivo.value, 'div_even06idimagen');
	//paginarf0000();
	}
function eliminaeven06idimagen(){
	var did=window.document.frmedita.even06id;
	if (confirm("Esta seguro de eliminar el archivo?")){
		xajax_elimina_archivo_even06idimagen(did.value);
		//paginarf0000();
		}
	}
function paginarf1906(){
	var params=new Array();
	params[101]=window.document.frmedita.paginaf1906.value;
	params[102]=window.document.frmedita.lppf1906.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f1906_HtmlTabla(params);
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
	document.getElementById("even06consec").focus();
	}
function guardaf1907(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even06id.value;
	valores[2]=window.document.frmedita.even07consec.value;
	valores[3]=window.document.frmedita.even07id.value;
	valores[4]=window.document.frmedita.even07idvariable.value;
	valores[5]=window.document.frmedita.even07izquierda.value;
	valores[6]=window.document.frmedita.even07arriba.value;
	valores[7]=window.document.frmedita.even07ancho.value;
	valores[8]=window.document.frmedita.even07fuente.value;
	valores[9]=window.document.frmedita.even07tipofuente.value;
	valores[10]=window.document.frmedita.even07fuentetam.value;
	valores[11]=window.document.frmedita.even07fuentecolor.value;
	valores[12]=window.document.frmedita.even07alineacion.value;
	params[0]=window.document.frmedita.even06id.value;
	//params[1]=window.document.frmedita.p1_1907.value;
	params[101]=window.document.frmedita.paginaf1907.value;
	params[102]=window.document.frmedita.lppf1907.value;
	xajax_f1907_Guardar(valores, params);
	}
function limpiaf1907(){
	var dalarma=document.getElementById('alarma');
	dalarma.innerHTML='';
	xajax_f1907_PintarLlaves();
	window.document.frmedita.even07idvariable.value='';
	window.document.frmedita.even07izquierda.value='';
	window.document.frmedita.even07arriba.value='';
	window.document.frmedita.even07ancho.value='';
	window.document.frmedita.even07fuente.value='';
	window.document.frmedita.even07tipofuente.value='';
	window.document.frmedita.even07fuentetam.value='';
	window.document.frmedita.even07fuentecolor.value='';
	window.document.frmedita.even07alineacion.value='';
	verboton('belimina1907','none');
	}
function eliminaf1907(){
	var params=new Array();
	params[0]=window.document.frmedita.even06id.value;
	params[1]=window.document.frmedita.even06id.value;
	params[2]=window.document.frmedita.even07consec.value;
	//params[14]=window.document.frmedita.p1_1907.value;
	params[101]=window.document.frmedita.paginaf1907.value;
	params[102]=window.document.frmedita.lppf1907.value;
	if (window.document.frmedita.even07id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1907_Eliminar(params);
			}
		}
	}
function revisaf1907(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even06id.value;
	params[2]=window.document.frmedita.even07consec.value;
	if ((params[2]!='')){
		xajax_f1907_Traer(params);
		}
	}
function cargadatof1907(llave1){
	window.document.frmedita.even07consec.value=String(llave1);
	revisaf1907();
	}
function cargaridf1907(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1907_Traer(params);
	expandepanel(1907,'block',0);
	}
function paginarf1907(){
	var params=new Array();
	params[0]=window.document.frmedita.even06id.value;
	params[101]=window.document.frmedita.paginaf1907.value;
	params[102]=window.document.frmedita.lppf1907.value;
	//params[103]=window.document.frmedita.bnombre1907.value;
	//params[104]=window.document.frmedita.blistar1907.value;
	xajax_f1907_HtmlTabla(params);
	}
setInterval ("xajax_sesion_abandona();", 60000);
// -->
</script>
<form id="frmimprime" name="frmimprime" method="post" action="unadverrpt.php" target="_blank">
<input id="paso_rpt" name="paso_rpt" type="hidden" value="1" />
<input id="id_rpt" name="id_rpt" type="hidden" value="<?php echo $id_rpt; ?>" />
<input id="alias" name="alias" type="hidden" value="reporte" />
<input id="variable" name="variable" type="hidden" value="<?php echo 'valor_variable'; ?>" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$icodmodulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}else{
?>
<input id="cmdBuscar" name="cmdBuscar" type="button" class="btUpBuscar" onclick="buscar();" title="<?php echo $ETI['bt_buscar']; ?>" value="<?php echo $ETI['bt_buscar']; ?>"/>
<?php
	}
$sScript='imprimelista()';
if ($_REQUEST['paso']!=0){
	if ($id_rpt!=0){$sScript='verrpt()';}
	}
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="btUpPrint" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
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
echo '<h2>'.$ETI['titulo_1906'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
 ?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta1906" name="boculta1906" type="hidden" value="<?php echo $_REQUEST['boculta1906']; ?>" />
<label class="Label30">
<input id="btexpande1906" name="btexpande1906" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1906,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1906']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1906" name="btrecoge1906" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1906,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1906']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1906" style="display:<?php if ($_REQUEST['boculta1906']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
 ?>
<label class="Label90">
<?php
echo $ETI['even06consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="even06consec" name="even06consec" type="text" value="<?php echo $_REQUEST['even06consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even06consec', $_REQUEST['even06consec']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['even06id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('even06id', $_REQUEST['even06id']);
?>
</label>
<label class="L">
<?php
echo $ETI['even06titulo'];
?>

<input id="even06titulo" name="even06titulo" type="text" value="<?php echo $_REQUEST['even06titulo']; ?>" maxlength="100" class="L"/>
</label>
<input id="even06origenimagen" name="even06origenimagen" type="hidden" value="<?php echo $_REQUEST['even06origenimagen']; ?>"/>
<input id="even06idimagen" name="even06idimagen" type="hidden" value="<?php echo $_REQUEST['even06idimagen']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_even06idimagen" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['even06origenimagen'], (int)$_REQUEST['even06idimagen']);
?>
</div>
<label class="Label30">
<input type="button" id="banexaeven06idimagen" name="banexaeven06idimagen" value="Anexar" class="btAnexarS" onclick="anexaeven06idimagen()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['even06id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="beliminaeven06idimagen" name="beliminaeven06idimagen" value="Eliminar" class="btBorrarS" onclick="eliminaeven06idimagen()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['even06idimagen']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 1907 Variables
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1907'];
?>
</label>
<input id="boculta1907" name="boculta1907" type="hidden" value="<?php echo $_REQUEST['boculta1907']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande1907" name="btexpande1907" type="button" value="Buscar" class="btMiniExpandir" onclick="expandepanel(1907,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1907']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1907" name="btrecoge1907" type="button" value="Buscar" class="btMiniRecoger" onclick="expandepanel(1907,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1907']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1907" style="display:<?php if ($_REQUEST['boculta1907']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['even07consec'];
?>
</label>
<label class="Label90"><div id="div_even07consec">
<?php
if ((int)$_REQUEST['even07id']==0){
?>
<input id="even07consec" name="even07consec" type="text" value="<?php echo $_REQUEST['even07consec']; ?>" onchange="revisaf1907()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even07consec', $_REQUEST['even07consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['even07id'];
?>
</label>
<label class="Label60"><div id="div_even07id">
<?php
	echo html_oculto('even07id', $_REQUEST['even07id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['even07idvariable'];
?>
</label>
<label>
<?php
	echo html_combo('even07idvariable', 'even15id', 'even15nombre', 'even15vars', '', 'even15nombre', $_REQUEST['even07idvariable'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
?>
</label>
<label class="Label130">
<?php
echo $ETI['even07izquierda'];
?>
</label>
<label class="Label130">
<input id="even07izquierda" name="even07izquierda" type="text" value="<?php echo $_REQUEST['even07izquierda']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label130">
<?php
echo $ETI['even07arriba'];
?>
</label>
<label class="Label130">
<input id="even07arriba" name="even07arriba" type="text" value="<?php echo $_REQUEST['even07arriba']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label130">
<?php
echo $ETI['even07ancho'];
?>
</label>
<label class="Label130">
<input id="even07ancho" name="even07ancho" type="text" value="<?php echo $_REQUEST['even07ancho']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label130">
<?php
echo $ETI['even07fuente'];
?>
</label>
<label>
<?php
	echo html_combo('even07fuente', 'unad33id', 'unad33nombre', 'unad33reportefuente', '', 'unad33nombre', $_REQUEST['even07fuente'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
?>
</label>
<label class="Label130">
<?php
echo $ETI['even07tipofuente'];
?>
</label>
<label>
<?php
	echo html_combo('even07tipofuente', 'unad34id', 'unad34nombre', 'unad34reportetipofuente', '', 'unad34nombre', $_REQUEST['even07tipofuente'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
?>
</label>
<label class="Label130">
<?php
echo $ETI['even07fuentetam'];
?>
</label>
<label>
<?php
	echo html_combo('even07fuentetam', '', '', '', '', '', $_REQUEST['even07fuentetam'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
?>
</label>
<label class="Label130">
<?php
echo $ETI['even07fuentecolor'];
?>
</label>
<label>
<input id="even07fuentecolor" name="even07fuentecolor" type="text" value="<?php echo $_REQUEST['even07fuentecolor']; ?>" maxlength="10"/>
</label>
<label class="Label130">
<?php
echo $ETI['even07alineacion'];
?>
</label>
<label>
<?php
	echo html_combo('even07alineacion', 'unad32id', 'unad32nombre', 'unad32reportealineacion', '', 'unad32nombre', $_REQUEST['even07alineacion'], $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
?>
</label>
<label class="Label30">
<input type="button" id="bguarda1907" name="bguarda1907" value="Guardar" class="btMiniGuardar" onclick="guardaf1907()" title="<?php echo $ETI['bt_mini_guardar_1907']; ?>"/>
</label>
<label class="Label30">
<input type="button" id="blimpia1907" name="blimpia1907" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1907()" title="<?php echo $ETI['bt_mini_limpiar_1907']; ?>"/>
</label>
<label class="Label30">
<input type="button" id="belimina1907" name="belimina1907" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1907()" title="<?php echo $ETI['bt_mini_eliminar_1907']; ?>" style="display:<?php if ((int)$_REQUEST['even07id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1907
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre1907" name="bnombre1907" type="text" value="<?php echo $_REQUEST['bnombre1907']; ?>" onchange="paginarf1907()"/>
Listar
<?php
//echo html_combo('blistar1907', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1906 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar1907'], $objdb, 'paginarf1907()', true, '{'.$ETI['msg_todos'].'}', '');
?>
<div class="salto1px"></div>
</div>
-->
<div id="div_f1907detalle">
<?php
	$params1907[0]=$_REQUEST['even06id'];
	$params1907[101]=$_REQUEST['paginaf1907'];
	$params1907[102]=$_REQUEST['lppf1907'];
	//$params1907[103]=$_REQUEST['bnombre1907'];
	//$params1907[104]=$_REQUEST['blistar1907'];
	echo f1907_TablaDetalle($params1907, $objdb);
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1907 Variables
?>
<?php
if ($bconexpande){
	//Este es el cierre del div_p1906
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
<!--
<div class="ir_derecha">
Nombre
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1906()"/>
Listar
<?php
//echo html_combo('blistar', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1906 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar'], $objdb, 'paginarf1906()', true, '{'.$ETI['msg_todos'].'}', '');
?>
</div>
<div class="salto1px"></div>
-->
<div id="div_f1906detalle">
<?php
echo $sTabla1906;
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


</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div id="alarma" class="alarma" align="center"><?php echo $sError; ?></div>
<?php
forma_piedepagina();
?>