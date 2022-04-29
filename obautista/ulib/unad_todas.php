<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
$dm_usergent = array(
'AvantGo' => 'AvantGo',
'BlackBerry' => 'BlackBerry',
'iPhone' => 'iPhone',
'Minimo' => 'Minimo',
'Mobiles' => 'Mobile',
'Motorola' => 'mot-',
'MozillaMobile' => 'Mobile',
'NetFront' => 'NetFront',
'Nokia' => 'Nokia',
'OperaMini' => 'Minimo',
'Palm' => 'Palm',
'PDA' => 'PDA',
'PIE4' => 'compatible; MSIE 4.01; Windows CE; PPC; 240x320',
'PIE4_Smartphone' => 'compatible; MSIE 4.01; Windows CE; Smartphone;',
'PIE6' => 'compatible; MSIE 6.0; Windows CE;',
'PPC' => 'PPC',
'Plucker' => 'Plucker',
'Smartphone' => 'Smartphone',
'SonyEricsson' => 'SonyEricsson',
'WindowsMobile' => 'Windows CE'
	);	
function obtenerNavegador($useragents, $useragent){
	foreach($useragents as $nav=>$ua){
		if(strstr($useragent, $ua)!=false){
			return $nav;
			}
		}
	return 'Desconocido';
	}
function iDefinirPiel($APP, $iMaxima=1){
	$iPiel=1;
	if (isset($APP->piel)!=0){$iPiel=$APP->piel;}
	if ($iPiel>$iMaxima){$iPiel=1;}
	return $iPiel;
	}
function iminutoavance(){
	return (date('W')*1440)+(date('H')*60)+date('i');
	}
if (isset($_SERVER['HTTP_USER_AGENT'])!=0){
	if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE" )){
		@session_cache_limiter('public');
		}
	}
@session_start();
if (isset($_SESSION['u_ultimominuto'])==0){$_SESSION['u_ultimominuto']=iminutoavance();}
if (isset($_SESSION['unad_id_tercero'])==0){
	$_SESSION['unad_id_tercero']=0;
	}
if ($_SESSION['unad_id_tercero']!=0){
	//Esto hace que se salga de la sesion si lleva mucho tiempo inactivo e intentan acceder a la pagina.
	$iavance=iminutoavance();
	if (isset($_SESSION['unad_tiempo_sesion'])==0){$_SESSION['unad_tiempo_sesion']=20;}
	if (isset($APP->tiempolimite)!=0){
		if ($APP->tiempolimite>$_SESSION['unad_tiempo_sesion']){$_SESSION['unad_tiempo_sesion']=$APP->tiempolimite;}
		}
	$itiempomax=$_SESSION['unad_tiempo_sesion'];
	if ($iavance>($_SESSION['u_ultimominuto']+$itiempomax)){
		header('Location:salir.php');
		die();
		}
	}
if (isset($_SESSION['unad_sesion_id'])==0){$_SESSION['unad_sesion_id']=0;}
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
switch($idEntidad){
	case 1:
	if (isset($_SESSION['unad_pais'])==0){$_SESSION['unad_pais']='001';}
	if (isset($_SESSION['unad_idioma'])==0){$_SESSION['unad_idioma']='en';}
	break;
	default:
	if (isset($_SESSION['unad_pais'])==0){$_SESSION['unad_pais']='057';}
	if (isset($_SESSION['unad_idioma'])==0){$_SESSION['unad_idioma']='es';}
	break;
	}
if (isset($_SESSION['cfg_movil'])==0){
	if (isset($_SERVER['HTTP_USER_AGENT'])!=0){
		$navegador= obtenerNavegador($dm_usergent,$_SERVER['HTTP_USER_AGENT']);
		}else{
		$navegador='Desconocido';
		}
	if($navegador!='Desconocido'){
		$_SESSION['cfg_movil']=1;
		}else{
		$_SESSION['cfg_movil']=0;
		}
	}
?>