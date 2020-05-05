<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Friday, October 11, 2019
--- 2202 Resumen de estudiantes
*/
function f2202_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2202=$APP->rutacomun.'lg/lg_2202_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2202)){$mensajes_2202=$APP->rutacomun.'lg/lg_2202_es.php';}
	require $mensajes_todas;
	require $mensajes_2202;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$core09id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM core09programa WHERE core09id='.$core09id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($core09id==''){$sLeyenda='<b>No se ha definido un programa a consultar</b>';}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2202" name="paginaf2202" type="hidden" value="'.$pagina.'"/><input id="lppf2202" name="lppf2202" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Programa, Tercero, Id, Estado';
	$aEstado=array();
	$aEtiqueta=array();
	$iCodigos=array();
	$aDatosGrafica=array();
	$aEtiquetasGrafica=array();
	$iCantidad=0;
	$iTotal=0;
	$sSQL='SELECT core02id, core02nombre FROM core02estadoprograma ORDER BY core02id';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$aDatosGrafica[$iCantidad]=0;
		$aEtiquetasGrafica[$iCantidad]=$fila['core02nombre'];
		$iCantidad++;
		$iCod=$fila['core02id'];
		$iCodigos[$iCantidad]=$iCod;
		$aEtiqueta[$iCod]=$fila['core02nombre'];
		$aEstado[$iCod]=0;
		}
	$sPaginador='<input id="paginaf2202" name="paginaf2202" type="hidden" value="'.$pagina.'"/><input id="lppf2202" name="lppf2202" type="hidden" value="'.$lineastabla.'"/>';
	$sSQL='SELECT TB.core01idestado, COUNT(TB.core01id) AS Total 
FROM core01estprograma AS TB 
WHERE TB.core01idprograma='.$core09id.'
GROUP BY TB.core01idestado';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$iCod=$fila['core01idestado'];
		$aEstado[$iCod]=$fila['Total'];
		$iTotal=$iTotal+$fila['Total'];
		}
	$sErrConsulta='<input id="consulta_2202" name="consulta_2202" type="hidden" value=""/>
<input id="titulos_2202" name="titulos_2202" type="hidden" value=""/>';
/*
<tr class="fondoazul">
<td colspan="5" align="center"><b>Estudiantes del programa</b></td>
</tr>
*/
	$res=$sErrConsulta.$sPaginador.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
';
	$tlinea=1;
	for ($k=1;$k<=$iCantidad;$k++){
		$iCod=$iCodigos[$k];
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
		$et_core01idestado=$sPrefijo.cadena_notildes($aEtiqueta[$iCod]).$sSufijo;
		$iEstudiantes=$aEstado[$iCod];
		$aDatosGrafica[$k-1]=$iEstudiantes;
		$et_total=$sPrefijo.formato_numero($iEstudiantes, 0).$sSufijo;
		$et_porcentaje='';
		if ($iEstudiantes>0){
			$et_porcentaje=formato_numero(($iEstudiantes/$iTotal*100), 2).' %';
			}
		$sUltimaColumna='';
		if ($k==1){
			$sGrafica='';
			$sUltimaColumna='<td rowspan="'.$iCantidad.'" width="50%"><canvas id="GraficaTotalAlumnos"></canvas></td>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core01idestado.'</td>
<td align="right">'.$et_total.'</td>
<td align="right">'.$et_porcentaje.'</td>'.$sUltimaColumna.'
</tr>';
		}
	$res=$res.'</table>';
	//Matricula por periodo...
	$aMatricula=array();
	$sIds='-99';
	$sSQL='SELECT core16peraca, core16estado, COUNT(core16tercero) AS Total
FROM core16actamatricula
WHERE core16idprograma='.$core09id.'
GROUP BY core16peraca, core16estado';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$idPeraca=$fila['core16peraca'];
		$iEstado=$fila['core16estado'];
		$sIds=$sIds.','.$idPeraca;
		if (isset($aMatricula[$idPeraca][0])==0){
			$aMatricula[$idPeraca][0]=0;
			$aMatricula[$idPeraca][7]=0;
			$aMatricula[$idPeraca][9]=0;
			}
		$aMatricula[$idPeraca][$iEstado]=$fila['Total'];
		}
	$res=$res.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>Periodo</b></td>
<td align="right"><b>En proceso</b></td>
<td align="right"><b>Matriculados</b></td>
<td align="right"><b>Cancelaciones</b></td>
</tr>';
	$sSQL='SELECT exte02id, exte02nombre FROM exte02per_aca WHERE exte02id IN ('.$sIds.') ORDER BY exte02id DESC';
	$tabladet=$objDB->ejecutasql($sSQL);
	while ($filadet=$objDB->sf($tabladet)){
		$sPrefijo='';
		$sSufijo='';
		$et_procesados='';
		$et_matriculados='';
		$et_cancelados='';
		if ($aMatricula[$filadet['exte02id']][0]!=0){$et_procesados=formato_numero($aMatricula[$filadet['exte02id']][0]);}
		if ($aMatricula[$filadet['exte02id']][7]!=0){$et_matriculados=formato_numero($aMatricula[$filadet['exte02id']][7]);}
		if ($aMatricula[$filadet['exte02id']][9]!=0){$et_cancelados=formato_numero($aMatricula[$filadet['exte02id']][9]);}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['exte02id'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_procesados.$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_matriculados.$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_cancelados.$sSufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	//Termina la matricula por periodo.
	$objGraf=new clsHTMLGrafica('Estudiantes', 'GraficaTotalAlumnos', 'pie');
	$sJS=$objGraf->sJS($aDatosGrafica, $aEtiquetasGrafica);
	$res=$res.'<script language="javascript">'.$sJS.'</script>';
	return array(utf8_encode($res), $sDebug);
	}
function f2202_HtmlTabla($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f2202_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2202detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2202_GraficaMatriculaXZona($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2202=$APP->rutacomun.'lg/lg_2202_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2202)){$mensajes_2202=$APP->rutacomun.'lg/lg_2202_es.php';}
	require $mensajes_todas;
	require $mensajes_2202;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	$aParametros[0]=numeros_validar($aParametros[0]);
	$aParametros[1]=numeros_validar($aParametros[1]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	if ($aParametros[1]==''){$aParametros[1]=0;}
	$sDebug='';
	$core09id=$aParametros[0];
	$idPeriodo=$aParametros[1];
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($idPeriodo==''){$sLeyenda='<b>No se ha definido el periodo a consultar</b>';}
	if ($core09id==''){$sLeyenda='<b>No se ha definido un programa a consultar</b>';}
	$sVrVacio='<script language="javascript">function grafica_GraficaAlumnosZona(){}</script>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.$sVrVacio, $sDebug);
		die();
		}
	$aEstado=array();
	$aEtiqueta=array();
	$iCodigos=array();
	$aDatosGrafica=array();
	$aEtiquetasGrafica=array();
	$iCantidad=0;
	$iTotal=0;
	$sSQL='SELECT unad23id, unad23nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$aDatosGrafica[$iCantidad]=0;
		$aEtiquetasGrafica[$iCantidad]=$fila['unad23nombre'];
		$iCantidad++;
		$iCod=$fila['unad23id'];
		$iCodigos[$iCantidad]=$iCod;
		$aEtiqueta[$iCod]=$fila['unad23nombre'];
		$aEstado[$iCod]=0;
		}
	$sPaginador='';
	$sSQL='SELECT TB.core16idzona, COUNT(TB.core16id) AS Total 
FROM core16actamatricula AS TB 
WHERE TB.core16idprograma='.$core09id.' AND core16peraca='.$idPeriodo.'
GROUP BY TB.core16idzona';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$iCod=$fila['core16idzona'];
		$aEstado[$iCod]=$fila['Total'];
		$iTotal=$iTotal+$fila['Total'];
		}
	$sErrConsulta='';
	$res=$sErrConsulta.$sPaginador.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="4" align="center"><b>Estudiantes matriculados en el programa por zona en el periodo</b></td>
</tr>';
	$tlinea=1;
	for ($k=1;$k<=$iCantidad;$k++){
		$iCod=$iCodigos[$k];
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
		$et_core01idestado=$sPrefijo.cadena_notildes($aEtiqueta[$iCod]).$sSufijo;
		$iEstudiantes=$aEstado[$iCod];
		$aDatosGrafica[$k-1]=$iEstudiantes;
		$et_total=$sPrefijo.formato_numero($iEstudiantes, 0).$sSufijo;
		$et_porcentaje='';
		if ($iEstudiantes>0){
			$et_porcentaje=formato_numero(($iEstudiantes/$iTotal*100), 2).' %';
			}
		$sUltimaColumna='';
		if ($k==1){
			$sGrafica='';
			$sUltimaColumna='<td rowspan="'.($iCantidad+1).'" width="50%"><canvas id="GraficaAlumnosZona"></canvas></td>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core01idestado.'</td>
<td align="right">'.$et_total.'</td>
<td align="right">'.$et_porcentaje.'</td>'.$sUltimaColumna.'
</tr>';
		}
	$sClass='';
	if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
	$et_total=formato_numero($iTotal, 0);
	$res=$res.'<tr'.$sClass.'>
<td>Total Matriculados</td>
<td align="right">'.$et_total.'</td>
<td align="right"></td>
</tr>
</table>';
	$objGraf=new clsHTMLGrafica('Estudiantes', 'GraficaAlumnosZona', 'pie');
	$sJS=$objGraf->sJS($aDatosGrafica, $aEtiquetasGrafica);
	$res=$res.'<script language="javascript">'.$sJS.'</script>';
	return array(utf8_encode($res), $sDebug);
	}
function f2202_GraficaMatriculaXCead($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2202=$APP->rutacomun.'lg/lg_2202_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2202)){$mensajes_2202=$APP->rutacomun.'lg/lg_2202_es.php';}
	require $mensajes_todas;
	require $mensajes_2202;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	if (isset($aParametros[2])==0){$aParametros[2]='';}
	$aParametros[0]=numeros_validar($aParametros[0]);
	$aParametros[1]=numeros_validar($aParametros[1]);
	$aParametros[2]=numeros_validar($aParametros[2]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	if ($aParametros[1]==''){$aParametros[1]=0;}
	if ($aParametros[2]==''){$aParametros[2]='';}
	$sDebug='';
	$core09id=$aParametros[0];
	$idPeriodo=$aParametros[1];
	$idZona=$aParametros[2];
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($idPeriodo==''){$sLeyenda='<b>No se ha definido el periodo a consultar</b>';}
	if ($core09id==''){$sLeyenda='<b>No se ha definido un programa a consultar</b>';}
	$sVrVacio='<script language="javascript">function grafica_GraficaAlumnosCead(){}</script>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.$sVrVacio, $sDebug);
		die();
		}
	$aEstado=array();
	$aEtiqueta=array();
	$iCodigos=array();
	$aDatosGrafica=array();
	$aEtiquetasGrafica=array();
	$sIds='-99';
	$sAddZona='';
	if ($idZona!=''){
		$sAddZona=' AND core16idzona='.$idZona.'';
		}
	$sSQL='SELECT TB.core16idcead 
FROM core16actamatricula AS TB 
WHERE TB.core16idprograma='.$core09id.' AND core16peraca='.$idPeriodo.$sAddZona.'
GROUP BY TB.core16idcead';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		return array($sVrVacio, $sDebug);
		}
	while ($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['core16idcead'];
		}
	$iCantidad=0;
	$iTotal=0;
	$sSQL='SELECT TB.unad24id, TB.unad24nombre 
FROM unad24sede AS TB, unad23zona AS T2 
WHERE TB.unad24id IN ('.$sIds.') AND TB.unad24idzona=T2.unad23id AND T2.unad23conestudiantes="S" 
ORDER BY TB.unad24idzona, TB.unad24nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$aDatosGrafica[$iCantidad]=0;
		$aEtiquetasGrafica[$iCantidad]=($fila['unad24nombre']);
		$iCantidad++;
		$iCod=$fila['unad24id'];
		$iCodigos[$iCantidad]=$iCod;
		$aEtiqueta[$iCod]=$fila['unad24nombre'];
		$aEstado[$iCod]=0;
		}
	$sPaginador='';
	$sSQL='SELECT TB.core16idcead, COUNT(TB.core16id) AS Total 
FROM core16actamatricula AS TB 
WHERE TB.core16idprograma='.$core09id.' AND core16peraca='.$idPeriodo.'
GROUP BY TB.core16idcead';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$iCod=$fila['core16idcead'];
		$aEstado[$iCod]=$fila['Total'];
		$iTotal=$iTotal+$fila['Total'];
		}
	$sErrConsulta='';
	$res=$sErrConsulta.$sPaginador.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="4" align="center"><b>Estudiantes matriculados en el programa por CEAD en el periodo</b></td>
</tr>';
	$tlinea=1;
	for ($k=1;$k<=$iCantidad;$k++){
		$iCod=$iCodigos[$k];
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
		$et_core01idestado=$sPrefijo.cadena_notildes($aEtiqueta[$iCod]).$sSufijo;
		$iEstudiantes=$aEstado[$iCod];
		$aDatosGrafica[$k-1]=$iEstudiantes;
		$et_total=$sPrefijo.formato_numero($iEstudiantes, 0).$sSufijo;
		$et_porcentaje='';
		if ($iEstudiantes>0){
			$et_porcentaje=formato_numero(($iEstudiantes/$iTotal*100), 2).' %';
			}
		$sUltimaColumna='';
		if ($k==1){
			$sGrafica='';
			$sUltimaColumna='<td rowspan="'.$iCantidad.'" width="50%"><canvas id="GraficaAlumnosCead"></canvas></td>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core01idestado.'</td>
<td align="right">'.$et_total.'</td>
<td align="right">'.$et_porcentaje.'</td>'.$sUltimaColumna.'
</tr>';
		}
	$res=$res.'</table>';
	$objGraf=new clsHTMLGrafica('Estudiantes', 'GraficaAlumnosCead', 'horizontalBar');
	$sJS=$objGraf->sJS($aDatosGrafica, $aEtiquetasGrafica);
	$res=$res.'<script language="javascript">'.$sJS.'</script>';
	return array(utf8_encode($res), $sDebug);
	}

?>