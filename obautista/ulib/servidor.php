<?php
echo '<br>Datos del sistema: <br>
Fecha <b>'.date('d/m/Y').'</b> Hora <b>'.date('H:i').'</b><br>
Zona Horaria <b>'.date_default_timezone_get().'</b><br>';
$espacio=false;
$dev=$_SERVER['DOCUMENT_ROOT'];
$espacio=@disk_free_space($dev);
if ($espacio==false){
	$uso=-1;
	}else{
	$capacidad=disk_total_space($dev);
	$uso=(1-($espacio/$capacidad))*100;
	}
if ($uso==-1){
	echo 'No fue posible obtener informaci&oacute;n del disco duro'.'<br>';
	}else{
	echo 'Uso del disco <b>'.number_format($uso,2,".",",").'</b><br>';
	}
	
echo 'Limite de subida de archivos:<b>'.ini_get('upload_max_filesize').'</b><br>';
echo 'Limite de memoria:<b>'.ini_get('memory_limit').'</b><br>';
echo 'Limite en el post:<b>'.ini_get('post_max_size').'</b><br>';
echo '<br>Datos del usuario: <br>';
echo 'Navegador = <b>'.$_SERVER['HTTP_USER_AGENT'].'</b><br>';
?>