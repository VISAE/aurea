<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2016 ---
--- mauro@avellaneda.co - http://www.ideasw.com
--- Modelo Versión 2.13.3 miércoles, 13 de julio de 2016
*/
class clsHtmlCombos{
	const VERSION_MAYOR=1;
	const VERSION_MENOR=1;
	const VERSION_CORRECCION=0;
	var $bConVacio=true;
	var $bConDebug=false;
	var $aItem=array();
	var $iItems=0;
	// Los origenes es de donde tomamos los datos 0= manual, 1=sql  la consulta debe ser enviada.
	var $iOrigen=0;
	var $iAncho=0;
	var $sAccion='';
	var $sClaseCombo='';
	var $sEtiVacio='';
	var $sNombre='';
	var $sVrVacio='';
	var $sValorCombo='';
	function addArreglo($aDatos, $iCantidad, $sEstilo=''){
		for ($k=1;$k<=$iCantidad;$k++){
			$bAdiciona=true;
			if (isset($aDatos[$k])==0){$aDatos[$k]='';}
			if ($aDatos[$k]==''){$bAdiciona=false;}
			if ($bAdiciona){
				$this->iItems++;
				$i=$this->iItems;
				$this->aItem[$i]['v']=$k;
				$this->aItem[$i]['e']=cadena_notildes($aDatos[$k]);
				$this->aItem[$i]['c']=$sEstilo;
				}
			}
		}
	function addItem($sValor, $sEtiqueta, $sEstilo=''){
		$this->iItems++;
		$i=$this->iItems;
		$this->aItem[$i]['v']=$sValor;
		$this->aItem[$i]['e']=$sEtiqueta;
		$this->aItem[$i]['c']=$sEstilo;
		}
	function comboSistema($idModulo, $iConsec, $objdb, $sAccion=''){
		$this->sAccion=$sAccion;
		$sSQL='SELECT unad22codopcion, unad22nombre FROM unad22combos WHERE unad22idmodulo='.$idModulo.' AND unad22consec='.$iConsec.' AND unad22activa="S" ORDER BY unad22orden, unad22nombre';
		$tablac=$objdb->ejecutasql($sSQL);
		while ($fila=$objdb->sf($tablac)){
			$this->addItem($fila['unad22codopcion'], $fila['unad22nombre']);
			}
		return $this->html('');
		}
	function comun($idTabla, $objdb){
		$sSQL='';
		switch($idTabla){
			case 1101:$sSQL='SELECT unad24id AS id, CONCAT(CASE unad24activa WHEN "S" THEN "" ELSE "[INACTIVA] " END, unad24nombre) AS nombre FROM unad24sede ORDER BY unad24activa DESC, unad24nombre';break;
			case 1102:$sSQL='SELECT unad10codigo AS id, unad10codigo AS nombre FROM unad10vigencia ORDER BY unad10codigo DESC';break;
			}
		if ($sSQL!=''){
			$tablac=$objdb->ejecutasql($sSQL);
			while ($fila=$objdb->sf($tablac)){
				$this->addItem($fila['id'], $fila['nombre']);
				}
			}
		}
	function debug($bDebug=true){
		$this->bConDebug=$bDebug;
		}
	function html($sConsulta='', $objdb=NULL, $iComun=0){
		if ($iComun!=0){
			$this->comun($iComun, $objdb);
			$sConsulta='';
			}
		$sDebug='';
		$sRes='';
		$sAccion='';
		$sEstilos='';
		$sClaseC='';
		if ($this->sAccion!=''){$sAccion=' onChange="'.$this->sAccion.'"';}
		if ($this->sClaseCombo!=''){$sClaseC=' class="'.$this->sClaseCombo.'"';}
		if ($this->iAncho!=0){$sEstilos='width:'.$this->iAncho.'px;';}
		if ($sEstilos!=''){$sEstilos=' style="'.$sEstilos.'"';}
		$sRes='<select id="'.$this->sNombre.'" name="'.$this->sNombre.'"'.$sAccion.$sClaseC.$sEstilos.'>';
		if ($this->bConVacio){
			$sEstilo='';
			if ($this->sVrVacio===''){$sEstilo=' style="color:#FF0000"';}
			$sRes=$sRes.'<option value="'.$this->sVrVacio.'"'.$sEstilo.'>'.$this->sEtiVacio.'</option>';
			}
		for ($k=1;$k<=$this->iItems;$k++){
			$sSel='';
			$sEstilo='';
			if ($this->aItem[$k]['v']==$this->sValorCombo){$sSel=' selected';}
			if ($this->aItem[$k]['c']!=''){$sEstilo=' style="'.$this->aItem[$k]['c'].'"';}
			$sRes=$sRes.'<option value="'.$this->aItem[$k]['v'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($this->aItem[$k]['e']).'</option>';
			}
		if ($sConsulta!=''){
			$sEstilo='';
			$tablac=$objdb->ejecutasql($sConsulta);
			if ($this->bConDebug){
				if ($tablac==false){$sDebug=$sConsulta;}
				}
			while ($fila=$objdb->sf($tablac)){
				$sSel='';
				if ($fila['id']==$this->sValorCombo){$sSel=' selected';}
				$sRes=$sRes.'<option value="'.$fila['id'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($fila['nombre']).'</option>';
				}
			}
		$sRes=$sRes.'</select>'.$sDebug;
		return utf8_encode($sRes);
		}
	function meses($sEstilo=''){
		$sMeses=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		for ($k=1;$k<=12;$k++){
			$this->addItem($k, $sMeses[$k], $sEstilo);
			}
		}
	function nuevo($sNombre, $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->bConVacio=$bConVacio;
		$this->aItem=array();
		$this->iAncho=0;
		$this->iItems=0;
		$this->sAccion='';
		$this->sClaseCombo='';
		$this->sEtiVacio=$sEtiVacio;
		$this->sNombre=$sNombre;
		$this->sVrVacio=$sVrVacio;
		$this->sValorCombo=$sValorCombo;
		}
	function numeros($iNumIni, $iNumFin, $iOrden=0, $sEstilo=''){
		if ($iOrden==0){
			for ($k=$iNumIni;$k<=$iNumFin;$k++){
				$this->addItem($k, $k, $sEstilo);
				}
			}else{
			for ($k=$iNumFin;$k>=$iNumIni;$k--){
				$this->addItem($k, $k, $sEstilo);
				}
			}
		}
	function sino($sEtiquetaSi='Si', $sEtiquetaNo='No', $sValorSi='S', $sValorNo='N', $sEstiloSi='', $sEstiloNo=''){
		$this->addItem($sValorSi, $sEtiquetaSi, $sEstiloSi);
		$this->addItem($sValorNo, $sEtiquetaNo, $sEstiloNo);
		}
	function __construct($sNombre='', $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->nuevo($sNombre, $sValorCombo, $bConVacio, $sEtiVacio);
		}
	}
class clsHtmlCuerpoItem{
var $iTipo=0; 
/*
-- Tipos Aceptados: 0 HTML puro. 1 - Etiqueta. 100 - Salto de linea; 
-- 1000 Un bloque HTML (Es decir un grupo campos.)
*/
var $bNegrilla=false;
var $bAlerta=false;
var $sCuerpo='';
var $sEstilo='';
var $objBloque=NULL;
function __construct($iTipo, $sCuerpo=''){
	$this->iTipo=$iTipo;
	$this->sCuerpo=$sCuerpo;
	}
}
class clsHtmlCuerpo{
var $iEstilo=1;
var $aItems=array();
var $iItems=0;
function addBloque($objBloque, $sEstilo=''){
	$objItem=new clsHtmlCuerpoItem(1000);
	$objItem->objBloque=$objBloque;
	$objItem->sEstilo=$sEstilo;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addEtiqueta($sContenido, $sEstilo='', $bNegrilla=false){
	$objItem=new clsHtmlCuerpoItem(1, $sContenido);
	$objItem->sEstilo=$sEstilo;
	$objItem->bNegrilla=$bNegrilla;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addHTML($sCuerpo){
	$objItem=new clsHtmlCuerpoItem(0);
	$objItem->sCuerpo=$sCuerpo;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addSalto(){
	$objItem=new clsHtmlCuerpoItem(100);
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function armarBoton($sNombre, $sAccion='', $sTitulo='', $sClase='', $sDescripcion=''){
	$sRes='';
	switch ($this->iEstilo){
		case 2:
		break;
		default:
		$hTitulo='';
		if ($sTitulo!=''){$hTitulo=' value="'.$sTitulo.'"';}
		$hClase='';
		switch($sClase){
			case 'proceso':$hClase=' class="botonProceso"';break;
			}
		$hAccion='';
		if ($sAccion!=''){$hAccion=' onclick="'.$sAccion.'"';}
		$hDesc='';
		if ($sDescripcion!=''){$hDesc=' title="'.$sDescripcion.'"';}
		$sRes='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button"'.$hTitulo.$hClase.$hAccion.$hDesc.'/>';
		break;
		}
	return $sRes;
	}
function html(){
	$sRes='';
	for ($k=1;$k<=$this->iItems;$k++){
		$objItem=$this->aItems[$k];
		switch($objItem->iTipo){
			case 0: //HTML puro.
			$sRes=$sRes.$objItem->sCuerpo;
			break;
			case 1: //Etiqueta.
			switch ($this->iEstilo){
				case 2:
				break;
				default:
				$sComp='';
				switch($objItem->sEstilo){
					case '30': $sComp=' class="Label30"';break;
					case '60': $sComp=' class="Label60"';break;
					case '90': $sComp=' class="Label90"';break;
					case '130': $sComp=' class="Label130"';break;
					case '160': $sComp=' class="Label160"';break;
					case '200': $sComp=' class="Label200"';break;
					case 'AreaS': $sComp=' class="txtAreaS"';break;
					case 'L': $sComp=' class="L"';break;
					}
				$sPrev='';
				$sPost='';
				if ($objItem->bNegrilla){
					$sPrev='<b>';
					$sPost='</b>';
					}
				if ($objItem->bAlerta){
					$sPrev='<span class="rojo">'.$sPrev;
					$sPost=$sPost.'</span>';
					}
				$sRes=$sRes.'<label'.$sComp.'>'.$sPrev.$objItem->sCuerpo.$sPost.'</label>';
				break;
				}
			break;
			case 100: //Salto de linea.
			switch ($this->iEstilo){
				case 2:
				break;
				default:
				$sRes=$sRes.'<div class="salto1px"></div>';
				break;
				}
			break;
			case 1000: //Grupo campos.
			$sPrev='<div class="GrupoCampos">'.$this->iEstilo;
			$sPost='<div class="salto1px"></div></div>';
			switch ($objItem->sEstilo){
				case 450:
				$sPrev='<div class="GrupoCampos450">';
				break;
				default:
				break;
				}
			$sRes=$sRes.$sPrev.$objItem->objBloque->html().$sPost;
			break;
			}
		//Termina de recorrer cada item.
		}
	return $sRes;
	}
function __construct($iEstilo=1){
	$this->iEstilo=$iEstilo;
	}
}
class clsHtmlFecha{
	var $sNombre='';
	var $sValor='';
	function __construct($sNombre, $sValor){
		$this->sNombre=$sNombre;
		$this->sValor=$sValor;
		}
	}
class clsHtmlForma{
var $iPiel=0;
var $sAddEstiloTitulo='';
var $aBotones=array();
var $iBotones=0;
function addBoton($sNombre, $sClase, $sAccion, $sTitulo){
	$this->iBotones++;
	$k=$this->iBotones;
	$this->aBotones[$k]['nombre']=$sNombre;
	$this->aBotones[$k]['clase']=$sClase;
	$this->aBotones[$k]['accion']=$sAccion;
	$this->aBotones[$k]['titulo']=$sTitulo;
	}
function htmlBotonSolo($sNombre, $sClase, $sAccion, $sTitulo, $iLabel=0, $sAdicional=''){
	$res='';
	$sAddB='';
	if ($sAdicional!=''){$sAddB=' '.$sAdicional;}
	switch($this->iPiel){
		case 0:
		$sClaseFin=$sClase;
		switch ($sClase){
			case 'btSoloReasignar':
			$sClaseFin='botonProceso';
			break;
			}
		$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="'.$sClaseFin.'" onclick="'.$sAccion.'" title="'.$sTitulo.'"'.$sAddB.'/>';
		break;
		default:
		$bEntra=true;
		$bLargo=false;
		$sImgLnk='../ulib/img/btUpAyuda.jpg';
		$sClaseFin='BotonAzul';
		$sAdd='';
		switch($sClase){
			case 'botonProceso':
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="image" data-icono="../../img/pinon.png" onclick="'.$sAccion.'" title="'.$sTitulo.'"'.$sAddB.'/>';
			//$res='<a id="'.$sNombre.'" name="'.$sNombre.'" href="'.$sAccion.'" class="image" data-icono="pinon.png">'.$sTitulo.'</a>';
			$bEntra=false;
			break;
			case 'btEnviarExcel':
			case 'btMiniExcel':
			$sImgLnk='../ulib/img/excel.png';
			break;
			case 'btEnviarPDF':
			$sImgLnk='../ulib/img/pdf.png';
			break;
			case 'btGuardarHoja':
			$sImgLnk='../ulib/img/hoja-guardar.png';
			break;
			case 'btGuardarS';
			case 'btMiniGuardar':
			$sImgLnk='../ulib/img/guardar18.png';
			break;
			case 'btMiniActualizar':
			$sImgLnk='../ulib/img/recarga18.png';
			break;
			case 'btMiniBuscar':
			$sImgLnk='../ulib/img/lupa18.png';
			break;
			case 'btMiniEliminar':
			$sImgLnk='../ulib/img/x18.png';
			break;
			case 'btMiniHoy':
			$sImgLnk='../ulib/img/h18.png';
			break;
			case 'btMiniLimpiar':
			$sImgLnk='../ulib/img/hoja18.png';
			break;
			case 'btMiniPersona':
			$sImgLnk='../ulib/img/persona18.png';
			break;
			case 'btSupCerrar':
			$sImgLnk='../ulib/img/hoja-check.png';
			break;
			case 'btSupVolver':
			$sImgLnk='../ulib/img/btSupVolver.jpg';
			break;
			case 'btUpEliminar':
			$sImgLnk='../ulib/img/x.png';
			break;
			case 'btUpGuardar':
			case 'btSoloGuardar':
			$sImgLnk='../ulib/img/guardar.png';
			break;
			case 'btUpLimpiar':
			$sImgLnk='../ulib/img/hoja.png';
			break;
			}
		if ($bLargo){
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="image" onclick="'.$sAccion.'" title="'.$sTitulo.'" data-icono="'.$sImgNombre.'" style="background-image: url(\''.$sImgLnk.'\');"'.$sAddB.'/>';
			}else{
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'"  onClick="'.$sAccion.'" class="'.$sClaseFin.'" title="'.$sTitulo.'"'.$sAddB.'/>';
			}
		break;
		}
	switch($iLabel){
		case 30:
		$res='<label class="Label30">'.$res.'</label>';
		break;
		case 90:
		$res='<label class="Label90">'.$res.'</label>';
		break;
		case 130:
		$res='<label class="Label130">'.$res.'</label>';
		break;
		case 160:
		$res='<label class="Label160">'.$res.'</label>';
		case 200:
		$res='<label class="Label200">'.$res.'</label>';
		case 250:
		$res='<label class="Label250">'.$res.'</label>';
		break;
		}
	return $res;
	}
function htmlInicioMarco($sTitulo=''){
	$res='';
	$sHtmlTitulo='';
	switch($this->iPiel){
		default:
		if ($sTitulo!=''){$sHtmlTitulo='<div id="titulo"><h3>'.$sTitulo.'</h3></div>';}
		$res='<div class="areaform">'.$sHtmlTitulo.'
		<div class="areatrabajo">';
		break;
		}
	return $res;
	}
function htmlInicioMarcoSimple(){
	$res='';
	switch($this->iPiel){
		case 0:
		$res='<div id="cargaForm">';
		break;
		default:
		$res='';
		break;
		}
	return $res;
	}
function htmlFinMarco(){
	$res='';
	switch($this->iPiel){
		default:
		$res='</div>
		</div>';
		break;
		}
	return $res;
	}
function htmlFinMarcoSimple(){
	$res='';
	switch($this->iPiel){
		case 0:
		$res='</div>';
		break;
		default:
		$res='';
		break;
		}
	return $res;
	}
function htmlEspere($sMsgEspere='Este proceso puede tomar algunos momentos, por favor espere...'){
	$res='<div class="MarquesinaMedia">'.$sMsgEspere.'</div>';
	return $res;
	}
function htmlExpande($sCodigo, $iValor, $sTituloMostrar='Mostrar', $sTituloOcultar='Ocultar'){
	$res='<input id="boculta'.$sCodigo.'" name="boculta'.$sCodigo.'" type="hidden" value="'.$iValor.'" />';
	$sEstado1='none';
	$sEstado2='block';
	if ($iValor!=0){
		$sEstado1='block';
		$sEstado2='none';
		}
	switch($this->iPiel){
		case 0:
		$res=$res.'<label class="Label30">
		<input id="btexpande'.$sCodigo.'" name="btexpande'.$sCodigo.'" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel('.$sCodigo.',\'block\',0);" title="'.$sTituloMostrar.'" style="display:'.$sEstado1.';"/>
		</label>
		<label class="Label30">
		<input id="btrecoge'.$sCodigo.'" name="btrecoge'.$sCodigo.'" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel('.$sCodigo.',\'none\',1);" title="'.$sTituloOcultar.'" style="display:'.$sEstado2.';"/>
		</label>';
		break;
		default:
		$res=$res.'<label class="Label30">
		<button id="btexpande'.$sCodigo.'" name="btexpande'.$sCodigo.'" type="button" onClick="javascript:expandepanel('.$sCodigo.',\'block\',0);" title="'.$sTituloMostrar.'" style="display:'.$sEstado1.';"><img src="../ulib/img/fl-abajo18.png"/></button>
		</label>
		<label class="Label30">
		<button id="btrecoge'.$sCodigo.'" name="btrecoge'.$sCodigo.'" type="button" onClick="javascript:expandepanel('.$sCodigo.',\'none\',1);" title="'.$sTituloOcultar.'" style="display:'.$sEstado2.';"><img src="../ulib/img/fl-arriba18.png"/></button>
		</label>';
		break;
		}
	return $res;
	}
function htmlTitulo($sTitulo, $iCodModulo, $sId=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=''.$sTitulo.'';
	$sAddE='';
	$sAddId='';
	if ($this->sAddEstiloTitulo!=''){
		$sAddE=' style="'.$this->sAddEstiloTitulo.'"';
		}
	if ($sId!=''){
		$sAddId=' id="'.$sId.'"';
		}
	switch ($this->iPiel){
		default:
		$res='<div class="titulos">
		<div class="titulosD">';
		for ($k=1;$k<=$this->iBotones;$k++){
			$sBoton=$this->htmlBotonSolo($this->aBotones[$k]['nombre'], $this->aBotones[$k]['clase'], $this->aBotones[$k]['accion'], $this->aBotones[$k]['titulo']);
			$res=$res.$sBoton;
			}
		$sComp1='';
		$sComp2='';
		if ($sAddE==''){
			$sComp1='<h2>';
			$sComp2='</h2>';
			}
		$res=$res.'</div>
		<div class="titulosI"'.$sAddE.$sAddId.'>'.$sComp1.$sTitulo.$sComp2.'</div>
		</div>';
		break;
		}
	return $res;
	}
function __construct($iPiel){
	$this->iPiel=$iPiel;
	}
}
class clsHTMLGrafica{
var $sTitulo='';
var $sTipo='bar';
var $sIdCanvas='';
function sJS($aDatos, $aEtiquetas){
	if (!is_array($aDatos)){
		return '';
		}
	if (!is_array($aEtiquetas)){
		return '';
		}
	$iCantidad=count($aDatos);
	$sListaVal=$aDatos[0];
	if ($sListaVal==''){$sListaVal=0;}
	$sListaEti="'".$aEtiquetas[0]."'";
	for ($k=1;$k<$iCantidad;$k++){
		$sVal=0;
		if (is_numeric($aDatos[$k])){$sVal=$aDatos[$k];}
		$sListaVal=$sListaVal.', '.$sVal;
		$sEtiqueta='';
		if (isset($aEtiquetas[$k])!=0){$sEtiqueta=cadena_NoTildesJS($aEtiquetas[$k]);}
		$sListaEti=$sListaEti.", '".$sEtiqueta."'";
		}
	$sJS="
function grafica_".$this->sIdCanvas."(){
var ctx=document.getElementById('".$this->sIdCanvas."').getContext('2d');
var myChart=new Chart(ctx, {
type:'".$this->sTipo."',
data:{
labels:[".$sListaEti."],
datasets:[{
label:'".$this->sTitulo."',
data:[".$sListaVal."],
backgroundColor: [
'rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(255, 159, 64, 0.2)'
],
borderColor:[
'rgba(255, 99, 132, 1)',
'rgba(54, 162, 235, 1)',
'rgba(255, 206, 86, 1)',
'rgba(75, 192, 192, 1)',
'rgba(153, 102, 255, 1)',
'rgba(255, 159, 64, 1)'
],
borderWidth:1
}]
},
options:{scales:{yAxes:[{ticks:{beginAtZero:true}}]}}
});
	}
";
	return $sJS;
	}
function __construct($sTitulo, $sIdCanvas, $sTipo='bar'){
	$this->sTitulo=$sTitulo;
	$this->sIdCanvas=$sIdCanvas;
	switch($sTipo){
		case 'horizontalBar':
		$this->sTipo='horizontalBar';
		break;
		case 'pie':
		$this->sTipo='pie';
		break;
		case 'line':
		$this->sTipo='line';
		break;
		}
	}
}
class clsHtmlMenu{
var $iPiel=1;
var $iSuperior=0;
var $aMenu=array();
var $iMenu=0;
function addItem($sTitulo, $sDestino){
	$this->iMenu++;
	$k=$this->iMenu;
	$this->aMenu[$k]['titulo']=$sTitulo;
	$this->aMenu[$k]['destino']=$sDestino;
	}
function htmlMenu(){
	$sPrimera='';
	$sClaseMSup='';
	$sPrevSup='';
	$sPostSup='';
	if ($this->iPiel==0){
		$sPrimera='<li class="ini"></li>';
		if ($this->iSuperior==1){
			$sClaseMSup=' class="ppal"';
			}
		$sPrevSup='<span>';
		$sPostSup='</span>';
		}
	$res='';
	for ($k=1;$k<=$this->iMenu;$k++){
		$etiqueta=$this->aMenu[$k]['titulo'];
		$sDestino=$this->aMenu[$k]['destino'];
		$res=$res.'<li><a href="'.$sDestino.'"'.$sClaseMSup.'>'.$sPrevSup.''.$etiqueta.''.$sPostSup.'</a></li>';
		}
	return $res;
	}
function __construct($iPiel=1, $iSuperior=0){
	$this->iPiel=$iPiel;
	$this->iSuperior=$iSuperior;
	}
}
class clsHtmlTercero{
var $bConCorreo=false;
var $bConDireccion=false;
var $bConGrupoCampos=true;
var $bConTelefono=false;
var $bExiste=false;
var $bOculto=false;
var $bSoloDatos=false;
var $idTercero=0;
var $iForma=0;
var $iMarcador=0;
var $iPiel=0;
var $sDoc='';
var $sCorreo='';
var $sCorreoInstitucional='';
var $sDireccion='';
var $sNombreCampo='unad11id';
var $sRazonSocial='&nbsp;';
var $sTelefono='';
var $sTipoDoc='CC';
var $sTituloCampo='Tercero';
// datos constantes que se toman de las librerias de idioma o del app.php
var $STIPODOC='CC';
var $ETI_INGDOC='';
var $ETI_CORREO='';
var $ETI_DIRECCION='';
var $ETI_TELEFONO='';
function Cargar($objDB){
	$this->Traer('', '', $this->idTercero, $objDB);
	}
function html(){
	$sRes='';
	$sGC='';
	$sGCc='';
	if ($this->bConGrupoCampos){
		$sGC='<div class="GrupoCampos450">';
		$sGCc='<div class="salto1px"></div>
		</div>';
		}
	$sRes=$this->HtmlTitulo();
	$sRes=$sRes.html_salto();
	if ($this->bSoloDatos){
		$sRes=$sRes.$this->HtmlDatos();
		}else{
		$sRes=$sRes.$this->HtmlCuerpo();
		}
	return $sGC.$sRes.$sGCc;
	}
function Limpiar(){
	$this->sDoc='';
	$this->sCorreo='';
	$this->sCorreoInstitucional='';
	$this->sDireccion='';
	$this->sRazonSocial='&nbsp;';
	$this->sTelefono='';
	$this->sTipoDoc=$this->STIPODOC;
	$this->bExiste=false;
	}
function HtmlCuerpo(){
	$sPref='<b>';
	$sSuf='</b>';
	if (!$this->bExiste){
		$sPref='';
		$sSuf='';
		}
	$sRes='<input id="'.$this->sNombreCampo.'" name="'.$this->sNombreCampo.'" type="hidden" value="'.$this->idTercero.'"/>
	<div id="div_'.$this->sNombreCampo.'_llaves">'.html_DivTerceroV3($this->sNombreCampo, $this->sTipoDoc, $this->sDoc, $this->bOculto, $this->iPiel, $this->iMarcador, $this->ETI_INGDOC).'</div>'
	.html_salto().'
	<div id="div_'.$this->sNombreCampo.'" class="L">'.$sPref.cadena_notildes($this->sRazonSocial).$sSuf.'</div>';
	if ($this->bConDireccion){
		$sRes=$sRes.html_salto().$this->HtmlDireccion();
		}
	if ($this->bConTelefono){
		$sRes=$sRes.html_salto().$this->HtmlTelefono();
		}
	if ($this->bConCorreo){
		$sRes=$sRes.html_salto().$this->HtmlCorreo();
		}
	return $sRes;
	}
function HtmlCorreo(){
	$sRes='<label class="L">'.$this->ETI_CORREO.' <b>'.$this->sCorreoInstitucional.'</b></label>';
	return $sRes;
	}
function HtmlDireccion(){
	$sRes='<label class="L">'.$this->ETI_DIRECCION.' <b>'.$this->sDireccion.'</b></label>';
	return $sRes;
	}
function HtmlTelefono(){
	$sRes='<label class="L">'.$this->ETI_TELEFONO.' <b>'.$this->sTelefono.'</b></label>';
	return $sRes;
	}
function HtmlDatos(){
	$sRes='<label class="Label350"><b>'.$this->sTipoDoc.' '.$this->sDoc.'</b></label>'
	.html_salto().'
	<label class="L"><b>'.cadena_notildes($this->sRazonSocial).'</b></label>';
	if ($this->bConDireccion){
		$sRes=$sRes.html_salto().$this->HtmlDireccion();
		}
	if ($this->bConTelefono){
		$sRes=$sRes.html_salto().$this->HtmlTelefono();
		}
	if ($this->bConCorreo){
		$sRes=$sRes.html_salto().$this->HtmlCorreo();
		}
	return $sRes;
	}
function HtmlTitulo(){
	$sRes='<label class="TituloGrupo">'.$this->sTituloCampo.'</label>';
	return $sRes;
	}
function Traer($sTipoDoc, $sDoc, $id, $objDB){
	$this->bExiste=false;
	$this->Limpiar();
	$this->idTercero=0;
	$sError='';
	$sCondi='unad11id='.$id.'';
	if ($sDoc!=''){
		$sVerifica=htmlspecialchars($sTipoDoc.$sDoc);
		if ($sVerifica!=$sTipoDoc.$sDoc){
			$sError='Quieto veneno... que estan intentando hacer...';
			}
		if ($sError==''){
			$sCondi='unad11tipodoc="'.$sTipoDoc.'" AND unad11doc="'.$sDoc.'"';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad11id, unad11tipodoc, unad11doc, unad11razonsocial, unad11telefono, unad11direccion, unad11correo, unad11correonotifica, unad11correoinstitucional, unad11correofuncionario FROM unad11terceros WHERE '.$sCondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$this->idTercero=$fila['unad11id'];
			$this->sDoc=$fila['unad11doc'];
			$this->sCorreo=$fila['unad11correo'];
			$this->sCorreoInstitucional=$fila['unad11correoinstitucional'];
			if (correo_VerificarDireccion($fila['unad11correofuncionario'])){
				$this->sCorreoInstitucional=$fila['unad11correofuncionario'];
				}
			$this->sDireccion=$fila['unad11direccion'];
			$this->sRazonSocial=$fila['unad11razonsocial'];
			$this->sTelefono=$fila['unad11telefono'];
			$this->sTipoDoc=$fila['unad11tipodoc'];
			$this->bExiste=true;
			}else{
			$this->sRazonSocial='{'.'No encontrado'.'}';
			}
		}
	return array($this->sTipoDoc, $this->sDoc, $this->idTercero);
	}
function __construct($idTercero=0, $sNombreCampo='unad11id', $sTituloCampo='Tercero', $iMarcador=0){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_111;

	$this->STIPODOC=$APP->tipo_doc;
	$this->ETI_INGDOC=$ETI['ing_doc'];
	$this->ETI_CORREO=$ETI['unad11correo'];
	$this->ETI_DIRECCION=$ETI['unad11direccion'];
	$this->ETI_TELEFONO=$ETI['unad11telefono'];
	
	$this->Limpiar();
	$this->idTercero=$idTercero;
	$this->iMarcador=$iMarcador;
	$this->sNombreCampo=$sNombreCampo;
	$this->sTituloCampo=$sTituloCampo;
	}
}
function html_BotonAyuda($sNombreCampo, $sTituloCampo='Informaci&oacute;n relevante'){
	$res='<label class="Label30">
	<input id="cmdAyuda_'.$sNombreCampo.'" name="cmdAyuda_'.$sNombreCampo.'" type="button" class="btMiniAyuda" onclick="AyudaLocal(\''.$sNombreCampo.'\');" title="'.$sTituloCampo.'" />
	</label>';
	return $res;
	}
function html_BotonVerde($sNombreCampo, $sValor, $sAccion='', $sEtiqueta=''){
	$sIdBt='';
	$sEtiquetaBt='';
	$sAccionBt='';
	if ($sEtiqueta!=''){
		$sEtiquetaBt=' title="'.$sEtiqueta.'"';
		}
	if ($sAccion!=''){
		$sAccionBt=' onclick="'.$sAccion.'"';
		}
	if ($sNombreCampo==''){
		$sIdBt=' id="'.$sNombreCampo.'" name="'.$sNombreCampo.'"';
		}
	$res='<div><button'.$sIdBt.' type="button"'.$sAccionBt.$sEtiquetaBt.'><i class="fa fa-check-square"></i> '.$sValor.'</button></div>';
	return $res;
	}
function html_BotonRojo($sNombreCampo, $sValor, $sAccion='', $sEtiqueta=''){
	$sIdBt='';
	$sEtiquetaBt='';
	$sAccionBt='';
	if ($sEtiqueta!=''){
		$sEtiquetaBt=' title="'.$sEtiqueta.'"';
		}
	if ($sAccion!=''){
		$sAccionBt=' onclick="'.$sAccion.'"';
		}
	if ($sNombreCampo!=''){
		$sIdBt=' id="'.$sNombreCampo.'" name="'.$sNombreCampo.'"';
		}
	$res='<div><button'.$sIdBt.' type="button" class="__error"'.$sAccionBt.$sEtiquetaBt.'><i class="fa fa-times-circle"></i> '.$sValor.'</button></div>';
	return $res;
	}
function html_DivAlarmaV2($sError, $iTipoError, $bDebug=false){
	$sClase='';
	$iMomento=0;
	if ($bDebug){$sError=$sError.' -- '.$iTipoError.'';}
	if ($iTipoError==''){$iTipoError=0;}
	if ($sError!=''){
		$sClase=' class="alarma_roja"';
		$bPasa=false;
		if ($iTipoError==1){$bPasa=true;$iMomento=1;}
		if ($iTipoError==='verde'){$bPasa=true;$iMomento=2;}
		if ($bPasa){
			$sClase=' class="alarma_verde"';
			$iTipoError=1;
			}
		$bPasa=false;
		if ($iTipoError==2){$bPasa=true;$iMomento=3;}
		if ($iTipoError==='azul'){$bPasa=true;$iMomento=4;}
		if ($bPasa){
			$sClase=' class="alarma_azul"';
			$iTipoError=2;
			}
		if (strlen($sError)>1000){$sError='<div class="divScroll200">'.$sError.'</div>';}
		}
	if ($bDebug){$sError=$sError.' -- '.$iMomento.'';}
	$sRes='<div id="div_alarma"'.$sClase.'>'.$sError.'</div>';
	return $sRes;
	}
function html_DivAyudaLocal($sNombreCampo){
	$res='<div class="salto1px"></div>
	<div id="div_ayuda_'.$sNombreCampo.'" style="display:none" class="GrupoCamposAyuda"></div>
	<div class="salto1px"></div>';
	return $res;
	}
function html_DivTerceroV2($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $idAccion=0, $sPlaceHolder='', $iBotones=3){
	return html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, 0, $idAccion, $sPlaceHolder, $iBotones);
	}
function html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $iPiel, $idAccion=0, $sPlaceHolder='', $iBotones=3){
	$sRes='';
	if ($bOculto){
		$sRes=''.html_oculto($sNombreCampo.'_td',$sTipoDoc).' '.html_oculto($sNombreCampo.'_doc',$sDoc).'';
		}else{
		$sAdd='';
		if ($sPlaceHolder!=''){$sAdd=' placeholder="'.$sPlaceHolder.'"';}
		$sRes=html_tipodocV2($sNombreCampo.'_td', $sTipoDoc, "ter_muestra('".$sNombreCampo."', ".$idAccion.")", false).'
		<input id="'.$sNombreCampo.'_doc" name="'.$sNombreCampo.'_doc" type="text" value="'.$sDoc.'" onchange="ter_muestra(\''.$sNombreCampo.'\','.$idAccion.')" maxlength="20" onclick="revfoco(this);"'.$sAdd.'/>';
		$bConbuscar=false;
		$bConCrear=false;
		switch($iBotones){
			case 1:
			$bConbuscar=true;
			break;
			case 3:
			$bConbuscar=true;
			break;
			}
		if ($bConbuscar){
			$sRes=$sRes.'</label>
			<label class="Label30">
			<input type="button" name="b'.$sNombreCampo.'" value="Buscar" class="btMiniBuscar" onclick="buscarV2016(\''.$sNombreCampo.'\')" title="Buscar Tercero"/>';
			}
		if ($bConCrear){
			$sRes=$sRes.'</label>
			<label class="Label30">
			<input type="button" name="c'.$sNombreCampo.'" value="Crear" class="btMiniPersona" onclick="ter_crea(\''.$sNombreCampo.'\','.$idAccion.')" title="Crear Tercero"/>';
			}
		}
	return '<label class="Label350">'.$sRes.'</label>';
	}
function html_FechaEnNumero($nomcampo, $valor=0, $bvacio=false, $accion='' ,$iagnoini=0,$iagnofin=0,$idiafijo=0,$imesfijo=0){
	if (!$bvacio){
		if ((int)$valor==0){$valor=fecha_DiaMod();}
		}
	list($_dia, $_mes, $_agno)=fecha_DividirNumero($valor);
	if ($iagnoini==0){$iagnoini=2000;}
	if ($iagnofin==0){
		if ($_agno==0){
			$iagnofin=date('Y')+5;
			}else{
			$iagnofin=$_agno+5;
			}
		}
	$res='';
	if ($idiafijo==0){
		$res=html_ComboDia($nomcampo.'_dia', $_dia, $bvacio, 'fecha_AjustaNum(\''.$nomcampo."','".$accion.'\');');
		}else{
		$svr=$idiafijo;
		if ($idiafijo<10){$svr='0'.$svr;}
		$res=$res.'<input id="'.$nomcampo.'_dia" name="'.$nomcampo.'_dia" type="hidden" value="'.$svr.'"/>&nbsp;<b>'.$svr.'/</b>';
		}
	$res=$res.' '.html_ComboMes($nomcampo.'_mes', $_mes, $bvacio, 'fecha_AjustaNum('."'".$nomcampo."','".$accion."'".')').' ';
	if ($iagnofin<$iagnoini){$iagnofin=$iagnoini;}
	$bconagno=true;
	if ($iagnofin==$iagnoini){$bconagno=false;}
	if ($bconagno){
		$res=$res.'<select id="'.$nomcampo.'_agno" name="'.$nomcampo.'_agno" onchange="fecha_AjustaNum('."'".$nomcampo."','".$accion."'".')" class="cbo_agno">';
		if ($bvacio){$res=$res.'<option value="0"></option>';}
		for ($size=$iagnofin;$size>=$iagnoini;$size--){
			$ssel='';
			if ($size==$_agno){$ssel=' selected';}
			$res=$res.'<option'.$ssel.' value="'.$size.'">'.$size.'</option>';
			}
		$res=$res.'</select>';
		}else{
		$res=$res.'<input id="'.$nomcampo.'_agno" name="'.$nomcampo.'_agno" type="hidden" value="'.$iagnoini.'"/>&nbsp;<b>/'.$iagnoini.'</b>';
		}
	if (trim($valor)==''){$valor='0';}
	$res=$res.'<input id="'.$nomcampo.'" name="'.$nomcampo.'" type="hidden" value="'.$valor.'"/>';
	return $res;
	}

	function html_NoPermiso($iCodModulo, $sTituloModulo, $iPiel=0){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCambiaSesion=$ETI['msg_nopermiso'];
	if ($_SESSION['u_idtercero']==0){
		$sCambiaSesion=''.$ETI['msg_nosesion'];
		}
	switch($iPiel){
		default:
		$rRes='<div id="interna">
		<form id="frmedita" name="frmedita" method="post" action="">
		<div id="titulacion">
		<div id="titulacionD">
		<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda('.$iCodModulo.');" title="'.$ETI['bt_ayuda'].'" value="'.$ETI['bt_ayuda'].'"/>
		</div>
		<div id="titulacionI">
		<h2>'.$sTituloModulo.'</h2>
		</div>
		</div>
		<div id="cargaForm">
		<div id="area">
		<div class="MarquesinaMedia">
		'.$sCambiaSesion.'
		</div>
		</div>
		</div>
		</form>
		</div>';
		break;
		}
	return $rRes;
	}
function html_LnkArchivoV2($origen, $id, $titulo='Descargar', $sRaiz='verarchivo.php', $sClase='class="lnkresalte"'){
	$res='&nbsp;';
	if ($id!=0){
		$res='<a href="'.$sRaiz.'?u='.url_encode($origen.'|'.$id).'" target="_blank" '.$sClase.'>'.$titulo.'</a>';
		}
	return $res;
	}
function html_menuCampus($idsistema, $objDB, $iPiel=0, $bDebug=false, $idTercero=0){
	//if (isset($_SESSION['ent_chat'])==0){$_SESSION['ent_chat']='N';}
	$bpasa=true;
	$sDebug='';
	require './app.php';
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	$_SESSION['u_ultimominuto']=iminutoavance();
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	$sDebug=sesion_actualizar_v2($objDB, $bDebug);
	$sHTML='';
	$sClaseLinkBase='';
	$sClaseLinkItem='';
	$sClaseLiBase='';
	$sClaseLiItem='';
	$sInicioBloque='';
	$sFinBloque='';
	$sInicioItem='';
	$sFinItem='';
	$et_inicio='Inicio';
	$et_miscursos='Mis cursos';
	$et_clave='Contrase&ntilde;a';
	$et_contacto='Datos de contacto';
	$et_ayuda='Ayuda';
	$et_acerca='Acerca de...';
	$et_miperfil='Mi perfil';
	$et_salir='Salir';
	$et_banner='Banner';
	switch($_SESSION['unad_idioma']){
		case 'en':
		$et_ayuda='Help';
		$et_acerca='About...';
		$et_miperfil='My profile';
		$et_salir='Exit';
		break;
		case 'pt':
		$et_ayuda='Ajuda';
		$et_acerca='Sobre...';
		$et_miperfil='Meu perfil';
		$et_salir='Sair';
		break;
		}
	if ($iPiel==0){
		$sHTML='
		<div class="menuapp">
		<ul id="navmenu-h">';
		$sClaseLinkBase=' class="ppal"';
		$sInicioBloque='<ul><li class="ini"></li>';
		$sFinBloque='</ul>';
		$sInicioItem='<li>';
		$sFinItem='</li>';
		}
	if ($iPiel==1){
		$sHTML='<ul class="nav nav-tabs">';
		$sClaseLinkBase=' class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
		$sClaseLinkItem=' class="dropdown-item"';
		$sClaseLiBase=' class="nav-item dropdown"';
		$sInicioBloque='<div class="dropdown-menu">';
		$sFinBloque='</div>';
		}
	$sHTML=$sHTML.'<li'.$sClaseLiBase.'><a href="#"'.$sClaseLinkBase.'><span>ACCESIT</span></a>'.$sInicioBloque;
	$sHTML=$sHTML.$sInicioItem.'<a href="accesit.php"'.$sClaseLinkItem.'><span>'.$et_inicio.'</span></a>'.$sFinItem;
	if ((int)$idTercero>0){
		$sHTML=$sHTML.$sInicioItem.'<a href="miscursos.php"'.$sClaseLinkItem.'><span>'.$et_miscursos.'</span></a>'.$sFinItem;
		$sHTML=$sHTML.$sInicioItem.'<a href="miperfil.php"'.$sClaseLinkItem.'><span>'.$et_miperfil.'</span></a>'.$sFinItem;
		$sHTML=$sHTML.$sInicioItem.'<a href="contrasegna.php"'.$sClaseLinkItem.'><span>'.$et_clave.'</span></a>'.$sFinItem;
		$sHTML=$sHTML.$sInicioItem.'<a href="contacto.php"'.$sClaseLinkItem.'><span>'.$et_contacto.'</span></a>'.$sFinItem;
		$sHTML=$sHTML.$sInicioItem.'<a href="salir.php"'.$sClaseLinkItem.'><span>'.$et_salir.'</span></a>'.$sFinItem;
		}
	$sHTML=$sHTML.$sFinBloque.'';
	//Ver si tiene funciones administrativas.
	$bDevuelve=false;
	if ((int)$idTercero>0){
		if (is_object($objDB)){
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3(202, 1, $idTercero, $objDB);
			}
		}
	if ($bDevuelve){
		$sHTML=$sHTML.'<li'.$sClaseLiBase.'><a href="#"'.$sClaseLinkBase.'><span>Soporte</span></a>'.$sInicioBloque;
		$sHTML=$sHTML.$sInicioItem.'<a href="adminbanner.php"'.$sClaseLinkItem.'><span>'.$et_banner.'</span></a>'.$sFinItem;
		$sHTML=$sHTML.$sFinBloque.'';
		}
	//Termina las funciones administrativas
	$sHTML=$sHTML.'<li'.$sClaseLiBase.'><a href="#"'.$sClaseLinkBase.'><span>'.$et_ayuda.'</span></a>'.$sInicioBloque;
	//$sHTML=$sHTML.$sInicioItem.'<a href="sai.php"'.$sClaseLinkItem.'><span>SAI</span></a>'.$sFinItem;
	$sHTML=$sHTML.$sInicioItem.'<a href="acercade.php"'.$sClaseLinkItem.'><span>'.$et_acerca.'</span></a>'.$sFinItem;
	$sHTML=$sHTML.$sFinBloque.'</li>';
	if ($iPiel==0){
		$sHTML=$sHTML.'</ul>
		</div>';
		}
	if ($iPiel==1){
		$sHTML=$sHTML.'</ul>';
		}
	return array($sHTML, $sDebug);
	}
function html_notaV3($nota,$bocultacero=true, $iVrAprueba=3, $iVrMaximo=5, $iDecimales=1){
	$res='';
	if ($nota<=0){
		if (!$bocultacero){$res='<font class="rojo">'.formato_numero(0, $iDecimales).'</font>';}
		}else{
		$sMuestra=formato_numero($nota, $iDecimales);
		if ($nota<$iVrAprueba){
			$res='<font class="rojo">'.$sMuestra.'</font>';
			}else{
			if ($nota<=$iVrMaximo){
				$res='<font class="verde">'.$sMuestra.'</font>';
				}
			}
		}
	return $res;
	}
function html_salto(){
	return '<div class="salto1px"></div>';
	}

?>