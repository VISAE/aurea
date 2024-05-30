<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.9c viernes, 25 de diciembre de 2020
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
$xajax = NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$iPiel=iDefinirPiel($APP, 1);
@session_start();
//Cerramos la sesion 
if (isset($_SESSION['unad_sesion_id'])==0){$_SESSION['unad_sesion_id']=0;}
if ($_SESSION['unad_sesion_id']!=0){
	login_cerrarsesion_v2($idsesion, $objDB, $bDebug);
	}
//
unset($_SESSION['u_ultimominuto']);
unset($_SESSION['unad_id_tercero']);
unset($_SESSION['USER']);
$_SESSION['unad_id_tercero']=0;
$idTercero=0;
$bDebugMenu=false;
$et_menu='';
//list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
//$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
$ETI['titulo_0']='Plataforma AUREA';
$ETI['msg_nosesion']='Se ha cerrado la sesi&oacute;n.';
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_0']);
echo $et_menu;
forma_mitad();
if (false) {
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
<script language="javascript">
function cambiapagina(){
	window.document.frmedita.action='./';
	window.document.frmedita.submit();
	}
</script>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="./" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_0'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_nosesion'];
?>
</div><!-- /Termina la marquesina -->
<div class="salto1px"></div>
<label></label>
<label class="Label130"></label>
<label class="Label130">
<input dir="cmdEnviarLocal" name="cmdEnviarLocal" type="button" value="Iniciar Sesi&oacute;n" class="BotonAzul160" onclick="javascript:cambiapagina()" title="Iniciar Sesi&oacute;n" />
</label>
<?php
// CIERRA EL DIV areatrabajo
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector1 -->
</form>
</div><!-- /DIV_interna -->
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();
