<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.19.0 sábado, 28 de octubre de 2017
*/
if (file_exists('./err_control.php')){require './err_control.php';}
//require '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
$bPasa=false;
switch($idEntidad){
	case 1:
	//session_start();
	if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
	//if ($_SESSION['unad_id_tercero']==0){$_SESSION['unad_id_tercero']=1;}
	$bPasa=true;
	break;
	default:
	//require $APP->rutacomun.'unad_sesion.php';
	if($_SESSION['unad_id_tercero']==2){$bPasa=true;}
	if($_SESSION['unad_id_tercero']==4){$bPasa=true;} // Mauro Avellaneda
	if($_SESSION['unad_id_tercero']==6){$bPasa=true;} // Miguel Pinto
	if($_SESSION['unad_id_tercero']==139405){$bPasa=true;} //Saul Hernandez
	if($_SESSION['unad_id_tercero']==18454){$bPasa=true;} //Edwin Ortega
	if($_SESSION['unad_id_tercero']==22977){$bPasa=true;} //William Rico
	if($_SESSION['unad_id_tercero']==216182){$bPasa=true;} //Omar Bautista
	}
if($bPasa){
	if(isset($_POST['consulta'])==0){$_POST['consulta']='';}
	if(isset($_POST['idorigen'])==0){$_POST['idorigen']=0;}
	if(isset($_POST['limite'])==0){$_POST['limite']=500;}
	$sSQL=str_replace("\'", "'", $_POST['consulta']);
	$sSQL=str_replace('\"', '"', $_POST['consulta']);

	$aNombres=array('UnadSys', 'Talento Humano', 'Administrativo', 'Tablero', 'Campus', 'Centralizador', 'RyC', 'RyC Pruebas', 'Grados', 'ML Lab', 'Calificaciones Campus');
	$aParametro=array('', 'th', 'admin', 'Tablero', 'campus', 'central', 'ryc', 'ryc_p', 'grados', 'mllab', 'califica');
	$iDBs=10;
	$sSel=array();
	for ($k=1;$k<=$iDBs;$k++){
		$sSel[$k]='';
		}
	$sSel[$_POST['idorigen']]=' Selected';
?>
<!DOCTYPE html>
<html>
<head>
<title>Consultas directas</title>
</head>
<body>
<script language="javascript">
<!--
function pintarconsulta(sConsulta){
	window.document.frmedita.consulta.value=sConsulta;
	}
-->
</script>
<form id="frmedita" name="frmedita" action="" method="post">
<select id="idorigen" name="idorigen">
<?php
if ($idEntidad==0){
	$sOpc='<option value="0">unadsys</option>';
	for ($k=1;$k<=$iDBs;$k++){
		$sOpc=$sOpc.'<option value="'.$k.'"'.$sSel[$k].'>'.$aNombres[$k].'</option>';
		}
	echo $sOpc;
	}else{
?>
<option value="0">aurea</option>
<option value="1"<?php echo $sSel2; ?>>ISys</option>
<?php
	}
?>
</select>
<br />
<textarea id="consulta" name="consulta" cols="120" rows="10">
<?php 
echo $sSQL; 
?>
</textarea>
<br>
<input id="cmdEnviar" name="cmdEnviar" type="submit" value="Ejecutar"/>
Limite
<input id="limite" name="limite" type="text" value="<?php echo $_POST['limite']; ?>" />
</form>
<?php
	if ($_POST['consulta']!=''){
		$sDebug='';
		$iSegIni=microtime(true);
		$iSegundos=floor($iSegIni);
		$sMili=floor(($iSegIni-$iSegundos)*1000);
		if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
		$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
		$ifila=0;
		$iNumCampos=0;
		$bHayDB=false;
		$sCompl=$aParametro[$_POST['idorigen']];
		//switch($_POST['idorigen']){
			//default:
			$sOptModelo='';
			eval('if (isset($APP->dbmodelo'.$sCompl.')!=0){$sOptModelo=$APP->dbmodelo'.$sCompl.';}');
			$sModelo='M';
			if ($sOptModelo=='O'){$sModelo='O';}
			if ($sOptModelo=='P'){$sModelo='P';}
			eval('$sNombreDB=$APP->dbname'.$sCompl.';');
			eval('$objDB=new clsdbadmin($APP->dbhost'.$sCompl.', $APP->dbuser'.$sCompl.', $APP->dbpass'.$sCompl.', $APP->dbname'.$sCompl.', $sModelo);');
			eval('if ($APP->dbpuerto'.$sCompl.'!=""){$objDB->dbPuerto=$APP->dbpuerto'.$sCompl.';}');
			$bHayDB=true;
			$sDebug=$sDebug.''.fecha_microtiempo().' Usando la db <b>'.$sNombreDB.'</b> ['.$_POST['idorigen'].' - '.$sCompl.']<br>';
			//break;
			//}
		if (!$bHayDB){
			$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
			if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
			}
		//echo 'Intenta conectar..';
		$bConecta=$objDB->conectar();
		if (!$bConecta){
			echo $objDB->serror;
			$sCodigo="echo '<br>Datos de conexi&oacute;n: Servidor '.$"."APP->dbhost".$sCompl.".' - Usuario '.$"."APP->dbuser".$sCompl.".' - DB '.$"."APP->dbname".$sCompl.".' - Puerto '.$"."APP->dbpuerto".$sCompl.";";
			eval($sCodigo);
			//echo '<input id="nada" name="nada" type="text" value="'.$sCodigo.'"/>';
			}else{
			//echo 'Conectado..';
			$tabla=$objDB->ejecutasql($sSQL);
			$iSegFin=microtime(true);
			$iSegundos=$iSegFin-$iSegIni;
			$iSegIni=$iSegFin;
			$sDebug=$sDebug.''.fecha_microtiempo().' Ejecuta la consulta, Tiempo: '.$iSegundos.' (Segundos)<br>';
			if ($tabla==false){
				echo '..<font color="ff0000">'.$sSQL.'</font><br>'.$objDB->serror;
				if ($_POST['idorigen']==1){
					//echo '<br>Servidor: '.$APP->dbhostth.' Usuario '.$APP->dbuserth.' Clave '.$APP->dbpassth.' Nombre '.$APP->dbnameth.' Puerto '.$APP->dbpuertoth;
					}
				}else{
				$iRegistros=$objDB->nf($tabla);
				echo '<table>';
				$iNumCampos=0;
				while ($finfo=$objDB->objSiguienteCampo($tabla)){
					$iNumCampos++;
					$aCampo[$iNumCampos]=$finfo;
					}
				$bsalta=false;
				while ($fila=$objDB->sf($tabla)){
					if ($ifila==0){
						for ($k=$iNumCampos;$k>=1;$k--){
							if (isset($fila[$k-1])!=0){
								if (!$bsalta){$iNumCampos=$k;}
								$bsalta=true;
								}
							}
						echo '<tr><td colspan="'.$iNumCampos.'">Campos '.$iNumCampos.' Registros '.$iRegistros.'</td></tr>';
						echo '<tr>';
						for ($k=1;$k<=$iNumCampos;$k++){
							echo '<td><b>'.$aCampo[$k].'</b>&nbsp;</td>';
							}
						echo '</tr>';
						}
					echo '<tr>';
					for ($k=1;$k<=$iNumCampos;$k++){
						echo '<td>'.$fila[$k-1].'</td>';
						}
					echo '</tr>';
					$ifila++;
					if ($ifila>=$_POST['limite']){break;}
					}
				$objDB->CerrarConexion();
				if ($iNumCampos==0){
					$sResultante='Consulta ejecutada <b>'.$sSQL.'</b>';
					if (cadena_contiene(strtoupper($sSQL), 'INSERT')){
						$sResultante='<b>Registros insertados '.$objDB->iFilasAfectadas.'</b>';
						}else{
						if (cadena_contiene(strtoupper($sSQL), 'DELETE')){
							$sResultante='<b>Registros eliminados '.$objDB->iFilasAfectadas.'</b>';
							}else{
							if (cadena_contiene(strtoupper($sSQL), 'UPDATE')){
								$sResultante='<b>Registros actualizados '.$objDB->iFilasAfectadas.'</b>';
								}
							}
						}
					echo '<tr><td>'.$sResultante.'</td></tr>';
					}
				echo '</table>';
				}
			}
		//Fin de que se pudo conectar.
		$iSegFin=microtime(true);
		$iSegundos=$iSegFin-$iSegIni;
		$sDebug=$sDebug.''.fecha_microtiempo().' Muestra la pagina, Tiempo: '.$iSegundos.' (Segundos)<br>';
		echo '<br>'.$sDebug;
		}else{
?>
Consultas de mantenimiento:<br />
Ver conexiones dormidas: <a href="javascript:pintarconsulta('SELECT ID, STATE FROM information_schema.processlist AS PL WHERE PL.COMMAND=\'Sleep\' AND PL.TIME > 30;')">Mostrar</a><br />
Listado de tablas: <a href="javascript:pintarconsulta('SHOW TABLES LIKE \'%\'')">Mostrar</a><br />
</body>
</html>
<?php
		}
	}else{
	header('Location:index.php');
	die();
	}
?>