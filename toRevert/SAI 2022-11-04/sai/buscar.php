<?php
require_once '../config.php';
require_once 'app.php';
require_once $APP->rutacomun.'unad_todas.php';
if ($_SESSION['unad_id_tercero']<1){
	header("Location:nopermiso.php");
	die();
	}
require_once $APP->rutacomun.'libs/clsdbadmin.php';
require_once $APP->rutacomun.'unad_librerias.php';
$campos=0;
$tabla='';
$swherebase='';
$sgroupbase='';
$sorden='1';
$lineastabla=50;
$retorna=0;
$anchos[0]=40;
$iorden=1;
//$_SESSION['u_ultimominuto']=iminutoavance();
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']=0;}
if (isset($_REQUEST['pagina'])==0){$_REQUEST['pagina']=1;}
switch ($_REQUEST['id']){
	case 111:
		$campos=5;
		$muestra=4;
		$etiqueta[1]="Tipo";
		$etiqueta[2]="Documento";
		$etiqueta[3]="Razon social";
		$etiqueta[4]="Direcci&oacute;n";
		$campo[1]="unad11tipodoc";
		$campo[2]="unad11doc";
		$campo[3]="unad11razonsocial";
		$campo[4]="unad11direccion";
		$campo[5]="unad11id";
		$tabla="unad11terceros";
		$swherebase='';
		$iorden=3;
		$sorden='2';
		for ($i=1;$i<=$campos;$i++){
			$param[$i]=0;
			}
		$param[2]=1;
		$param[3]=1;
		$retorna=3;
		$ret[1]="unad11tipodoc";
		$ret[2]="unad11doc";
		$ret[3]="unad11id";
		$anchos[1]=10;
		$anchos[2]=30;
		$anchos[3]=100;
		$anchos[4]=45;
		break;
	default:
		$sdeclara="libs/defbuscar".$_REQUEST['id'].".php";
		if (file_exists($sdeclara)){
			include($sdeclara);
			}else{
			echo 'No se ha definido la busqueda '.$_REQUEST['id'];
			die();
			}
	}
if (isset($_REQUEST['orden'])==0){$_REQUEST['orden']=$iorden;}
if (isset($_REQUEST['sorden'])==0){$_REQUEST['sorden']=$sorden;}
if ($campos!=0){
	for ($i=1;$i<=$muestra;$i++){
		if (isset($aliascampo[$i])==0){$aliascampo[$i]=$campo[$i];}		
		if (isset($basecampo[$i])==0){$basecampo[$i]=$campo[$i];}		
		}
	$sqlwhere=$swherebase;
	for ($i=1;$i<=$campos;$i++){
		if ($param[$i]==1){
			if (isset($_REQUEST[$aliascampo[$i]])==0){$_REQUEST[$aliascampo[$i]]='';}
			if ($_REQUEST[$aliascampo[$i]]!=''){
				if ($sqlwhere!=''){$sqlwhere=$sqlwhere." AND ";}
				$sqlwhere=$sqlwhere.$basecampo[$i].' LIKE "%'.strtoupper($_REQUEST[$aliascampo[$i]]).'%"';
				}
			}
		}
	if ($sqlwhere!=''){$sqlwhere=' WHERE '.$sqlwhere;}
	$sql='SELECT '.$campo[1];
	for ($i=2;$i<=$campos;$i++){
		$sql=$sql.', '.$campo[$i];
		}
	$sqlgroup='';
	if ($sgroupbase!=''){
		$sqlgroup=' GROUP BY '.$sgroupbase;
		}
	$sorden='';
	if (isset($campo[$_REQUEST['orden']])!=0){
		$sorden=' ORDER BY '.$basecampo[$_REQUEST['orden']];
		if (isset($_REQUEST['sorden'])!=0){
			if ($_REQUEST['sorden']==1){$sorden=$sorden.' DESC';}
			}
		}
	$sql=$sql.' FROM '.$tabla.$sqlwhere.$sqlgroup.$sorden;
	$limite='';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	if ($_REQUEST['paso']==0){
		$rbase=($_REQUEST['pagina']-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			echo 'Error al tratar de ejecutar la consulta <br>'.$sql;
			die();
			}
		$registros=$objdb->nf($result);
		}
	if ($registros>$lineastabla){
		$result=$objdb->ejecutasql($sql.$limite);
		}
	if ($_REQUEST['paso']==51){
		//ENVIAR A EXCEL
		include 'excel/PHPExcel.php';
		include 'excel/PHPExcel/Writer/Excel2007.php';
		function ajustaretiquetas($texto){
			$nuevo=str_replace("&oacute;","o",$texto);
			return $nuevo;
			}
		$objReader = PHPExcel_IOFactory::createReader("Excel2007");
		$objPHPExcel = $objReader->load("buscar.xlsx");
		$objPHPExcel->getProperties()->setCreator("Mauro Avellaneda - http://www.unad.edu.co/");
		$objPHPExcel->getProperties()->setLastModifiedBy("Mauro Avellaneda - http://www.unad.edu.co/");
		$objPHPExcel->getProperties()->setTitle("Resultados de la busqueda");
		$objPHPExcel->getProperties()->setSubject("Resultados de la busqueda");
		$objPHPExcel->getProperties()->setDescription("Resultados de la busqueda en http://www.unad.edu.co/");
		$ifila=6;
		for ($i=1;$i<=$campos;$i++){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i-1,5, ajustaretiquetas($etiqueta[$i]));
			}
		while($row = mysql_fetch_array($result)) {
			for ($i=1;$i<=$campos;$i++){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i-1,$ifila, $row[$campo[$i]]);
				}
			$ifila++;
			}
		mysql_free_result($result);
		//descargar el resultado
		header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
		header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
		header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="busqueda.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
		}
	if ($_REQUEST['paso']==52){
		//ENVIAR A PDF
		include 'pdf.php';
		$pdf=pdf_crear(1,$campos,$etiqueta,$sql,$anchos);
		$pdf->Output('Busqueda.pdf','D');
		die();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="css/ckEstilo.css" media="screen"/>
<base target="_self" />
<title>Busquedas</title>
<?php
if ($_SESSION['cfg_movil']==1){
?>
<link rel="stylesheet" type="text/css" media="screen" href="css/movil.css" />
<?php
	}else{
?>
<link rel="stylesheet" type="text/css" media="screen" href="css/estilo.css" />
<?php
	}
?>
</head>
<body class="contbusqueda">
<script language="javascript" type="text/javascript">
function Devuelve(retorno){
	var datos=retorno.split('|');
	returnValue = datos;
	window.close();
	}
function cambiapagina(){
	window.document.frm.submit();
	}
function cambiacolor_over(celda) { celda.style.backgroundColor = "#A2F178" }
function cambiacolor_out(celda) { celda.style.backgroundColor = "#ffffff" }
function enviarbusqueda(){
	window.document.frm.paso.value=0;
	window.document.frm.submit();
	}
function enviarexcel(){
	window.document.frm.paso.value=51;
	window.document.frm.submit();
	}
function enviarpdf(){
	window.document.frm.paso.value=52;
	window.document.frm.submit();
	}
</script>
<div id="titulo"><h3>Busquedas</h3></div>
<div id="area">
<form id="frm" name="frm" method="post" action="">
<?php
if ($campos!=0){
	for ($i=1;$i<=$campos;$i++){
		if ($param[$i]==1){
			echo $etiqueta[$i].'&nbsp;<input name="'.$aliascampo[$i].'" type="text" id="'.$aliascampo[$i].'" value="'.$_REQUEST[$aliascampo[$i]].'" />&nbsp;';
			}
		}
	}
?>
<div class="salto1px"></div>
Orden
<?php
$arrorder[1]='Descendente';
$arrorder[2]='Ascendente';
echo html_combo_arreglo("orden",$_REQUEST['orden'],$etiqueta,true,"cambiapagina()");
echo html_combo_arreglo("sorden",$_REQUEST['sorden'],$arrorder,false,"cambiapagina()");
?>
<input name="id" type="hidden" id="id" value="<?php echo $_REQUEST['id']; ?>" />
<?php
echo html_paginador("pagina",$registros,$lineastabla,$_REQUEST['pagina'],"cambiapagina()");
?>
<input name="paso" type="hidden" id="paso" value="0" />
<input name="btnexcel" type="button" id="btnexcel" value="Enviar a Excel" onclick="enviarexcel();" class="btEnviarExcel" />
<input name="btnpdf" type="button" id="btnpdf" value="Enviar a PDF" onclick="enviarpdf();" class="btEnviarPDF" />
<input type="button" name="Submit" value="buscar" onclick="enviarbusqueda();" class="btUpBuscarSolo"/>
</form>
<hr />
<?php
if ($campos!=0){
	}
?>
<table width="0" border="0" cellspacing="2" cellpadding="0">
<?php
if ($campos!=0){
	//poner encabezados
	if (isset($muestra)==0){$muestra=$campos;}
	if ($muestra>$campos){$muestra=$campos;}
	echo '<tr class="fondoazul">';
	for ($i=1;$i<=$muestra;$i++){
		echo "<td><strong>".$etiqueta[$i]."</strong></td>";
		}
	echo "</tr>";
	for ($i=1;$i<=$muestra;$i++){
		if (isset($aliascampo[$i])==0){$aliascampo[$i]=$campo[$i];}		
		}
	while($row=$objdb->sf($result)) {
		echo '<tr  onmouseover="cambiacolor_over(this);" onmouseout="cambiacolor_out(this);" >';
		$llave='';
		for ($i=1;$i<=$retorna;$i++){
			if ($llave!=''){$llave=$llave.'|';}
			$llave=$llave.$row[$ret[$i]];
			}
		for ($i=1;$i<=$muestra;$i++){
			echo '<td><a href="javascript:Devuelve(\''.$llave.'\');">'.utf8_decode($row[$aliascampo[$i]]).'</a></td>';
			}
		echo '</tr>';
		}
	}
?>
</table>
</div>
</div>
</body>
</html>