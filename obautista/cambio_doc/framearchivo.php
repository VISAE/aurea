<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 16 de agosto de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
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
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=251;
$audita[1]=false;
$audita[2]=false;
$audita[3]=false;
$audita[4]=true;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
require $mensajes_todas;
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
if (isset($_REQUEST['ref'])==0){
	echo 'No se ha definido la referencia que hace el llamado.';
	die();
	}
if (isset($_REQUEST['id'])==0){
	echo 'No se ha definido el registro de origen.';
	die();
	}
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$bhayref=false;
$bimage=false;
switch($_REQUEST['ref']){
	default:
	$bHayAnexo=false;
	$sdeclara='libs/defanexo'.$_REQUEST['ref'].'.php';
	if (file_exists($sdeclara)){
		$bHayAnexo=true;
		}else{
		$sdeclara=$APP->rutacomun.'libs/defanexo'.$_REQUEST['ref'].'.php';
		if (file_exists($sdeclara)){$bHayAnexo=true;}
		}
	if ($bHayAnexo){
		include($sdeclara);
		}else{
		echo 'No se ha definido la informaci&oacute;n del anexo '.$_REQUEST['ref'];
		die();
		}
	}
$sql='';
if (isset($_REQUEST['paso'])==0){
	//traer el id del archivo y el origen....
	$sql='SELECT '.$borigen.', '.$bidarchivo.' FROM '.$btabla.' WHERE '.$bidreg.'='.$_REQUEST['id'];
	$result=$objDB->ejecutasql($sql);
	if ($objDB->nf($result)>0){
		$fila=$objDB->sf($result);
		$bhayref=true;
		if ($fila[$bidarchivo]!=0){
			$_REQUEST['dborigen']=$fila[$borigen];
			$_REQUEST['unad51id']=$fila[$bidarchivo];
			$_REQUEST['paso']=6;
			}else{
			//definir la dborigen.
			$sql='SELECT unad50id FROM unad50dbalterna WHERE unad50actual="S"';
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)>0){
				$fila=$objDB->sf($result);
				$_REQUEST['dborigen']=$fila['unad50id'];
				}
			}
		}
	}else{
	$bhayref=true;
	}
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	}
if (!$bhayref){
	echo 'No es posible hallar el v&iacute;nculo solicitado. '.$sql;
	die();
	}
$sError='';
$iTipoError=0;
if (isset($_REQUEST['dborigen'])==0){$_REQUEST['dborigen']=0;}
if (isset($_REQUEST['unad51consec'])==0){$_REQUEST['unad51consec']="";}
if (isset($_REQUEST['unad51id'])==0){$_REQUEST['unad51id']="";}
if (isset($_REQUEST['unad51mime'])==0){$_REQUEST['unad51mime']="";}
if (isset($_REQUEST['unad51nombre'])==0){$_REQUEST['unad51nombre']="";}
if (isset($_REQUEST['unad51detalle'])==0){$_REQUEST['unad51detalle']="";}
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==6)){
	$bpasa=true;
	if ($_REQUEST['paso']==6){
		$swhere='unad51id='.$_REQUEST['unad51id'].'';
		}else{
		$swhere='unad51consec='.$_REQUEST['unad51consec'].'';
		if ((int)$_REQUEST['unad51consec']==0){$bpasa=false;}
		}
	if ($bpasa){
		$objArchivos=DBalterna_Traer($_REQUEST['dborigen'], $objDB);
		}
	if ($bpasa){
		$sql='SELECT * FROM unad51archivos WHERE '.$swhere;
		$result=$objArchivos->ejecutasql($sql);
		if ($objArchivos->nf($result)>0){
			$fila=$objArchivos->sf($result);
			$_REQUEST['unad51consec']=$fila['unad51consec'];
			$_REQUEST['unad51id']=$fila['unad51id'];
			$_REQUEST['unad51nombre']=$fila['unad51nombre'];
			$_REQUEST['unad51detalle']=$fila['unad51detalle'];
			$_REQUEST['unad51mime']=$fila['unad51mime'];
			//$_REQUEST['unad51archivo']=$fila['unad51archivo'];
			$bcargo=true;
			$_REQUEST['paso']=2;
			}else{
			$_REQUEST['paso']=0;
			}
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	//if ($_REQUEST['paso']==10){
	$_REQUEST['unad51consec']=numeros_validar($_REQUEST['unad51consec']);
	//	}
	//$_REQUEST['unad51nombre']=htmlspecialchars($_REQUEST['unad51nombre']);
	$_REQUEST['unad51detalle']=htmlspecialchars($_REQUEST['unad51detalle']);
	//$_REQUEST['unad51mime']=htmlspecialchars($_REQUEST['unad51mime']);
	//$_REQUEST['unad51archivo']=htmlspecialchars($_REQUEST['unad51archivo']);
	//if ($_REQUEST['unad51archivo']==''){$sError='Necesita el dato Archivo';}
	//if ($_REQUEST['unad51mime']==''){$sError='Necesita el dato Mime';}
	//if ($_REQUEST['unad51detalle']==''){$sError='Necesita el dato Detalle';}
	//if ($_REQUEST['unad51nombre']==''){$sError='Necesita el dato Nombre';}
	//if ($_REQUEST['unad51id']==''){$sError='Necesita el dato Id';}//CONSECUTIVO
	//if ($_REQUEST['unad51consec']==''){$sError='Necesita el dato Consec';}//CONSECUTIVO
	if ($sError==''){
		$objArchivos=DBalterna_Traer($_REQUEST['dborigen'], $objDB);
		if ($sError!=''){
			echo $sError;
			die();
			}
		}
	if ($sError==''){
		$objDB->bxajax=true;
		if ($_REQUEST['paso']==10){
			if ($_REQUEST['unad51consec']==''){
				$_REQUEST['unad51consec']=tabla_consecutivo('unad51archivos', 'unad51consec', '', $objArchivos);
				}
			$sql='SELECT unad51id FROM unad51archivos WHERE unad51consec='.$_REQUEST['unad51consec'].'';
			$result=$objArchivos->ejecutasql($sql);
			if ($objArchivos->nf($result)!=0){
				$sError='El codigo de archivo ya existe.';
				}else{
				//if (!$objDB->bhaypermiso($_SESSION['u_idtercero'],$iCodModulo,2)){$sError='No tiene permisos para insertar';}
				}
			}else{
			//if (!$objDB->bhaypermiso($_SESSION['u_idtercero'],$iCodModulo,3)){$sError='No tiene permisos para guardar';}
			}
		}
	$bsubiendo=false;
	if ($sError==''){
		$ext='';
		$prevnombre=$_REQUEST['unad51nombre'];
		//$prevmime=$_REQUEST['unad51mime'];
		$_REQUEST['unad51nombre']=$_FILES['unad51archivo']['name'];
		//$tmp_name = $_FILES["unad51archivo"]["tmp_name"];
		$_REQUEST['unad51mime']=$_FILES['unad51archivo']['type'];
		if ($_REQUEST['unad51nombre']!=''){
			if ($_REQUEST['unad51detalle']==''){$_REQUEST['unad51detalle']=$_REQUEST['unad51nombre'];}
			if ($_REQUEST['unad51mime']==""){
				//NO SE RECONOCE EL MIME.
				$punto=strpos(".",$_REQUEST['unad51nombre']);
				echo $punto;
				}
			switch ($_REQUEST['unad51mime']){
				case "image/bmp":
					$ext='.bmp';
					$bsubiendo=true;
					break;
				case "image/gif":
					$ext='.gif';
					$bsubiendo=true;
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$ext='.jpg';
					$bsubiendo=true;
					break;
				case "image/tiff":
					$ext='.tif';
					$bsubiendo=true;
					break;
				case "image/x-png":
				case "image/png":
					$ext='.png';
					$bsubiendo=true;
					break;
				case "application/pdf":
					$ext='.pdf';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "application/msword":
					$ext='.doc';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "text/plain":
					$ext='.txt';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					$ext='.docx';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "application/excel";
				case "application/vnd.ms-excel";
					$ext='.xls';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "application/vnd.ms-excel.";
				case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					$ext='.xlsx';
					if (!$bimage){$bsubiendo=true;}
					break;
				case "application/vnd.openxmlformats-officedocument.presentationml.slideshow";
					$ext='.ppsx';
					if (!$bimage){$bsubiendo=true;}
					break;
				case 'audio/mpeg';
					$ext='.mp3';
					if (!$bimage){$bsubiendo=true;}
					break;
				case 'application/x-download';
					$arrext=explode('.',$_FILES['unad51archivo']['name']);
					$ext='.'.end($arrext);
					switch (strtolower($ext)){
						case '.bmp': $_REQUEST['unad51mime']='image/bmp'; break;
						case '.gif': $_REQUEST['unad51mime']='image/gif'; break;
						case '.tif': $_REQUEST['unad51mime']='image/tiff'; break;
						case '.jpg': $_REQUEST['unad51mime']='image/jpg'; break;
						case '.png': $_REQUEST['unad51mime']='image/png'; break;
						case '.pdf': $_REQUEST['unad51mime']='application/pdf'; break;
						case '.doc': $_REQUEST['unad51mime']='application/msword'; break;
						case '.txt': $_REQUEST['unad51mime']='text/plain'; break;
						case '.docx': $_REQUEST['unad51mime']='application/vnd.openxmlformats-officedocument.wordprocessingml.document'; break;
						case '.xls': $_REQUEST['unad51mime']='application/excel'; break;
						case '.xlsx': $_REQUEST['unad51mime']='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; break;
						case '.ppsx': $_REQUEST['unad51mime']='application/vnd.openxmlformats-officedocument.presentationml.slideshow'; break;
						//case '.': $_REQUEST['unad51mime']=''; break;
						default:
						$ext='';
						}
					if ((!$bimage)&&($ext!='')){$bsubiendo=true;}
					break;
				}
			//echo 'esta cargando un archivo...';
			if (!$bsubiendo){
				$sError='El tipo de archivo que intenta subir no es admitido {'.$_REQUEST['unad51mime'].'}.';
				}
			}else{
			//verificar que este actualizando el detalle 
			if ($_REQUEST['paso']==10){
				//
				$sError='No ha seleccionado un archivo a subir.';
				}
			}
		}	
	if ($sError==''){
		$bpasa=false;
		$sHoy=fecha_hoy();
		$iHoy=fecha_DiaMod();
		if ($_REQUEST['paso']==10){
			$_REQUEST['unad51id']=tabla_consecutivo('unad51archivos', 'unad51id', '', $objArchivos);
			$scampos='unad51consec, unad51id, unad51detalle, unad51fechacreado';
			$svalores=''.$_REQUEST['unad51consec'].', '.$_REQUEST['unad51id'].', "'.$_REQUEST['unad51detalle'].'", '.$iHoy.'';
			$sql='INSERT INTO unad51archivos ('.$scampos.') VALUES ('.$svalores.');';
			$idaccion=2;
			$sdetalle=$scampos.' ¬ '.$svalores;
			$bpasa=true;
			}else{
			$swhere='unad51id='.$_REQUEST['unad51id'].'';
			$sql='SELECT * FROM unad51archivos WHERE '.$swhere;
			$sdatos='';
			$scampo[1]='unad51detalle';
			$inumcampos=1;
			$result=$objArchivos->ejecutasql($sql);
			if ($objArchivos->nf($result)>0){
				$fila=$objArchivos->sf($result);
				$bsepara=false;
				for ($k=1;$k<=$inumcampos;$k++){
					if ($fila[$scampo[$k]]!=$_REQUEST[$scampo[$k]]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$_REQUEST[$scampo[$k]].'"';
						$bpasa=true;
						}
					}
				}
			$sql='UPDATE unad51archivos SET '.$sdatos.' WHERE '.$swhere.';';
			$idaccion=3;
			$sdetalle=$sdatos.' ¬ '.$swhere;
			}
		//echo $sql;
		if ($bpasa){
			$result=$objArchivos->ejecutasql($sql);
			if ($result==false){
				$sError='Error critico al tratar de guardar, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
				$_REQUEST['paso']=0;
				$bsubiendo=false;
				}else{
				if ($_REQUEST['paso']==10){
					if ($bunico){
						//actualizamos el id en el registro origen.
						$sql='UPDATE '.$btabla.' SET '.$borigen.'='.$_REQUEST['dborigen'].', '.$bidarchivo.'='.$_REQUEST['unad51id'].' WHERE '.$bidreg.'='.$_REQUEST['id'];
						$result=$objDB->ejecutasql($sql);
						if ($result==false){
							$sError=$sError.' ..<!-- '.$objDB->serror.' -->';
							}
						}
					}
				if ($audita[$idaccion]){$objDB->audita($iCodModulo,$_SESSION['unad_id_tercero'],$idaccion,$_REQUEST['unad51id'],$sdetalle);}
				$_REQUEST['paso']=2;
				}
			}else{
			$_REQUEST['paso']=2;
			}
		if ($bsubiendo){
			//si es una imagen redimencionarla....
			/*
			if (($ext=='.jpg')or($ext=='.gif')){
				require($APP->rutacomun.'libs/class.image-resize.php');
				$obj = new img_opt();
				$obj->max_width(800);
				$obj->max_height(800);
				$obj->image_path($final);
				$obj->image_resize();
				}
				*/
			//guardar el archivo ahora si...
			$tmp_name = $_FILES['unad51archivo']['tmp_name'];
			$fp = fopen($tmp_name, 'rb');
			$iTamano=filesize($tmp_name);
			$tarchivo=fread($fp, $iTamano);
			$tarchivo=addslashes($tarchivo);
			fclose($fp);
			$_REQUEST['unad51nombre']=cadena_LimpiarNombreArchivo($_REQUEST['unad51nombre']);
			$sql = "UPDATE unad51archivos SET unad51nombre='".$_REQUEST['unad51nombre']."', unad51mime='".$_REQUEST['unad51mime']."', unad51archivo='".$tarchivo."', unad51fechaupd=".$iHoy.", unad51peso=".$iTamano." WHERE unad51id=".$_REQUEST['unad51id'];					
			$result=$objArchivos->ejecutasql($sql);
			if ($result==false){
				$sError='Se ha producido un error subiendo el contenido del archivo, no fue posible guardar. {'.$iTamano.'}<!-- 
'.$objArchivos->serror.'
 -->';
				}else{
				if ($sError==''){
					$sError='Archivo cargado correctamente.';
					$iTipoError=1;
					}
				}
			}
		}else{
		$_REQUEST['paso']=2;
		}//fin de si no hay error.
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
	if ($sError==''){
		if (!$objDB->bhaypermiso($_SESSION['u_idtercero'],$iCodModulo,4)){
			$sError='No tiene permiso para eliminar';
			$_REQUEST['paso']=2;
			}else{
			$swhere='unad51consec='.$_REQUEST['unad51consec'].'';
			$sql='DELETE FROM unad51archivos WHERE '.$swhere.';';
			$result=$objDB->ejecutasql($sql);
			if ($audita[4]){$objDB->audita($iCodModulo,$_SESSION['u_idtercero'],4,0,$swhere);}
			$_REQUEST['paso']=-1;
			}
		}else{
		$_REQUEST['paso']=2;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	/*
	$_REQUEST['unad51consec']='';
	$_REQUEST['unad51id']='';
	$_REQUEST['unad51nombre']='';
	$_REQUEST['unad51detalle']='';
	$_REQUEST['unad51mime']='';
	*/
	$_REQUEST['paso']=0;
	if ($_REQUEST['unad51id']!=''){$_REQUEST['paso']=2;}
	}
?>
<!DOCTYPE html>
<head>
<title>Carga de archivos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>
<script language="javascript">
<!--
function limpiapagina(){
	window.document.frmarchivo.paso.value=-1;
	window.document.frmarchivo.submit();
	}
function enviaguardar(){
	expandesector(2);
	var dpaso=window.document.frmarchivo.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmarchivo.submit();
	}
function cambiapagina(){
	window.document.frmarchivo.submit();
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function terminar(){
	var dorigen=window.document.frmarchivo.dborigen;
	var did=window.document.frmarchivo.unad51id;
	var ddet=window.document.frmarchivo.unad51detalle;
	if (did.value!=''){
		window.parent.document.frmedita.div96v1.value=window.document.frmarchivo.dborigen.value;
		window.parent.document.frmedita.div96v2.value=window.document.frmarchivo.unad51id.value;
		window.parent.document.frmedita.div96v3.value=window.document.frmarchivo.unad51detalle.value;
		}
	window.parent.cierraDiv96(<?php echo $_REQUEST['ref']; ?>);
	}
// -->
</script>
<form id="frmarchivo" name="frmarchivo" method="post" enctype="multipart/form-data" action="">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input name="unad51consec" type="hidden" id="unad51consec" value="<?php echo $_REQUEST['unad51consec']; ?>" />
<input name="unad51nombre" type="hidden" id="unad51nombre" value="<?php echo $_REQUEST['unad51nombre']; ?>" />
<div class="salto5px"></div>
<div id="div_sector1">
<div class="areaform">
<div class="areatrabajo">
<label class="Label60">
DB : 
</label>
<label class="Label60">
<?php 
echo html_oculto('dborigen', $_REQUEST['dborigen']); 
?>
</label>
<label class="Label60">
Ref : 
</label>
<label class="Label130">
<?php 
echo html_oculto('unad51id', $_REQUEST['unad51id']); 
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Archivo
</label>
<label class="Label380">
<input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="64200000" />
<input type="file" id="unad51archivo" name="unad51archivo"/>
</label>
<label class="Label60">
<input id="enviar" name="enviar" type="button" value="<?php echo $ETI['bt_guardar']; ?>" class="btUpAnexar" onClick="enviaguardar()" title="<?php echo $ETI['bt_guardar']; ?>"/>
</label>
<label class="Label60">&nbsp;</label>
<label class="Label60">
<input id="terminado" name="terminado" type="button" value="<?php echo $ETI['bt_volver']; ?>" class="botonAprobado" onClick="terminar()" title="<?php echo $ETI['bt_volver']; ?>"/>
</label>
<?php
if ($_REQUEST['unad51id']!=''){
	//mostrar la imagen...
	$sRuta=url_encode($_REQUEST['dborigen'].'|'.$_REQUEST['unad51id']);
	echo '<div class="salto1px"></div><label class="Label130">Link del archivo:</label><label><input id="unad51detalle" name="unad51detalle" type="text" value="&lt;img src=&quot;verarchivo.php?u='.$sRuta.'&quot;/&gt;"/></label>';
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto1px"></div>
Tenga en cuesta que el sistema permite subir <b>m&aacute;ximo <?php echo archivos_MaxSubida(); ?> Megabytes</b>, si intenta subir archivos mas grandes el proceso va a fallar, en caso de necesitar subir archivos de mayor tama&ntilde;o por favor comuniquese con el administrador del sistema.
<div class="salto1px"></div>
</div>
<label class="L">
Detalle
<input id="unad51detalle" name="unad51detalle" type="text" value="<?php echo $_REQUEST['unad51detalle']; ?>" maxlength="250" class="L"/>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['unad51id']!=''){
	echo '<hr>
<div class="salto1px"></div>';
	$besimg=false;
	switch ($_REQUEST['unad51mime']){
		case "image/bmp":
		case "image/gif":
		case "image/jpeg":
		case "image/jpg":
		case "image/png":
			$besimg=true;
			break;
		}
	if ($besimg){
		echo '<img src="verarchivo.php?u='.$sRuta.'" />';
		}else{
		echo '<a href="verarchivo.php?u='.$sRuta.'" target="_blank" class="lnkresalte">'.$_REQUEST['unad51nombre'].'</a>';
		}
	}
?>
<div class="salto1px"></div>
</div>
</div>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
?>
<div id="cargaForm">
<div id="area">
<br />
<h2>Por favor espere mientras el sistema carga el archivo.</h2>
<br />
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->

<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
<script language="javascript">
<!--
function ajustaralto(){
	var iAlto=0;
	iAlto=window.document.body.scrollHeight;
	if (iAlto<100){iAlto=100;}
	iAlto=iAlto+10;
	if (!window.opera && document.all && document.getElementById){
		window.parent.document.getElementById('iframe96').style.height=iAlto;
		}else{
		window.parent.document.getElementById('iframe96').style.height=iAlto+'px';
		}
	}
window.parent.MensajeAlarmaV2('<?php echo $sError; ?>', <?php echo $iTipoError; ?>);
setTimeout('ajustaralto()', 1);
-->
</script>
</body>
</html>
<?php
?>