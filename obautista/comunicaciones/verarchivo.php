<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 16 de agosto de 2018
*/
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
if (isset($_SESSION['unad_id_tercero'])==0){
	echo '<!-- No tiene permiso para estar aqui -->';
	exit;
	}
// Recuperamos el parámetro GET con el id único de la foto que queremos mostrar
$iddb=0;
$bDebug=false;
$sDebug='';
if (isset($_REQUEST["u"])!=0){
	$sRaiz=url_decode_simple($_REQUEST["u"]);
	$aRaiz=explode('|', $sRaiz);
	if (isset($aRaiz[0])!=0){$_REQUEST["cont"]=$aRaiz[0];}
	if (isset($aRaiz[1])!=0){$_REQUEST["id"]=$aRaiz[1];}
	if (isset($aRaiz[2])!=0){$_REQUEST["maxx"]=$aRaiz[2];}
	if (isset($aRaiz[3])!=0){$_REQUEST["maxy"]=$aRaiz[3];}
	$bDebug=true;
	if ($bDebug){$sDebug=$sDebug.' Raiz='.$sRaiz.'';}
	}
if (isset($_REQUEST["cont"])!=0){
	$iddb=(int)$_REQUEST["cont"];
	}
$idfoto=0;
if (isset($_REQUEST["id"])==0){
	echo '<!-- No ha definido una imagen para mostrar -->';
	exit();
	}else{
	$idfoto=(int)$_REQUEST["id"];
	}
$maxx=0;
if (isset($_REQUEST["maxx"])!=0){
	$maxx=(int)$_REQUEST["maxx"];
	}
$maxy=0;
if (isset($_REQUEST["maxy"])!=0){
	$maxy=(int)$_REQUEST["maxy"];
	}
$besta=false;
$sError='';
if ($idfoto==0){
	echo '<!-- No se reconoce el valor solicitado -->';
	die();
	}
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if ($objDB->serror!=''){
	$sError=$objDB->serror;
	}
if ($iddb!=0){
	$sql='SELECT unad50server, unad50puerto, unad50usuario, unad50pwd, unad50db, unad50modelo FROM unad50dbalterna WHERE unad50id='.$iddb.'';
	$result=$objDB->ejecutasql($sql);
	if ($objDB->nf($result)>0){
		$row=$objDB->sf($result);
		$objDB=new clsdbadmin($row['unad50server'], $row['unad50usuario'], $row['unad50pwd'], $row['unad50db'], $row['unad50modelo']);
		if ($row['unad50puerto']!=''){$objDB->dbPuerto=$row['unad50puerto'];}
		$objDB->conectar();
		if ($objDB->serror!=''){
			$sError=$objDB->serror;
			}
		}
	}
if ($sError!=''){
	$objDB->CerrarConexion();
	echo '<!-- '.$sError.' -->';
	die();
	}
$sql='SELECT unad51archivo, unad51mime, unad51nombre, unad51fechavista, unad51peso FROM unad51archivos WHERE unad51id='.$idfoto.';';
$result=$objDB->ejecutasql($sql);
if ($objDB->nf($result)>0){
	$datos=$objDB->sf($result);
	$besta=true;
	//Marcar la visualizacion..
	$sHoy=fecha_hoy();
	if ($datos['unad51fechavista']!=$sHoy){
		$sql='UPDATE unad51archivos SET unad51fechavista="'.$sHoy.'" WHERE unad51id='.$idfoto.'';
		$tabla=$objDB->ejecutasql($sql);
		}
	}else{
	$objDB->CerrarConexion();
	echo '<!-- Posiblemente esta accediendo a un archivo que no le pertenece -->';
	die();
	}
$objDB->CerrarConexion();
if ($besta){
	// La imagen
	$imagen=$datos['unad51archivo'];
	// El mime type de la imagen
	$mime=$datos['unad51mime'];
	$nombre=$datos['unad51nombre'];
	$iPeso=-1;
	if ($datos['unad51peso']>0){
		$iPeso=$datos['unad51peso'];
		}
	$bimagen=false;
	switch ($mime){
		case "image/gif":
		case "image/jpeg":
		case "image/jpg":
		case "image/png":
			if (($maxx!=0)||($maxy!=0)){
				//$idfile=tmpfile();
				//fwrite($idfile,$imagen);
				$nombre_archivo_tmp = tempnam('', 'image_'.$idfoto);
				$gestor = fopen($nombre_archivo_tmp, 'w');
				fwrite($gestor, $imagen);
				$tamanos=getimagesize($nombre_archivo_tmp);
				$iancho=$tamanos[0];
				$ialto=$tamanos[1];
				$ifactor=$iancho/$ialto;
				if ($maxx==0){$maxx=(int)(($iancho*$maxy)/$ialto);}
				if ($maxy==0){$maxy=(int)(($ialto*$maxx)/$iancho);}
				//echo $iancho.'>'.$maxx.' - '.$ialto.'>'.$maxy;
				if (($iancho>$maxx)||($ialto>$maxy)){
					include $APP->rutacomun.'libs/class.image-resize.php';
					$obj = new img_opt();
					$obj->max_width($maxx);
					$obj->max_height($maxy);
					$obj->image_path($nombre_archivo_tmp);
					$obj->image_resize();
					$imagen=file_get_contents($nombre_archivo_tmp);
					}
				fclose($gestor);
				}
			$bimagen=true;
			break;
		}
	if ($bimagen){
		// Gracias a esta cabecera, podemos ver la imagen 
		// que acabamos de recuperar del campo blob
		// Muestra la imagen
		header("Content-Type: ".$mime);
		echo $imagen;
		}else{
		header('Content-Description: File Transfer');
		header('Content-Type: '.$mime);//application/force-download
		if ($iPeso>0){
			header('Content-Length: '.$iPeso);
			}
		header('Content-Disposition: attachment; filename="'.$nombre.'"');
		echo $imagen;
		}
	}else{
	echo $sDebug;
	}
?>