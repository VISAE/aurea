<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.1 lunes, 22 de abril de 2019
--- 2204 Matricula
*/
function f2204_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2204=$APP->rutacomun.'lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2204)){$mensajes_2204=$APP->rutacomun.'lg/lg_2204_es.php';}
	require $mensajes_todas;
	require $mensajes_2204;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	if (isset($aParametros[2])==0){$aParametros[2]='';}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$core06id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM core06grupos WHERE core06id='.$core06id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
	$idPeraca=$aParametros[1];
	$sCursoGrupo=$aParametros[2];
	$sTitulos='Grupo, Peraca, Tercero, Curso, Id, Aula, Rol, Nav, Estadoengrupo';
	$sSQL='SHOW TABLES LIKE "core04%"';
	$sSQLBase='';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' 
UNION 
';}
		$sSQLBase=$sSQLBase.'SELECT TB.core04idcurso, T3.unad11razonsocial AS C3_nombre, TB.core04id, TB.core04idaula, TB.core04peraca, TB.core04tercero, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc, TB.core04idcurso, TB.core04idrol, TB.core04idnav, TB.core04estadoengrupo, ('.$iContenedor.') AS Contenedor, TB.core04aplicoagenda  
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T3 
WHERE '.$sSQLadd1.' TB.core04idgrupo='.$core06id.' AND TB.core04peraca='.$idPeraca.' AND TB.core04tercero=T3.unad11id '.$sSQLadd.'';
		}
	$sSQL=$sSQLBase.' ORDER BY C3_doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2204" name="consulta_2204" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2204" name="titulos_2204" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2204: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2204" name="paginaf2204" type="hidden" value="'.$pagina.'"/><input id="lppf2204" name="lppf2204" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['core04tercero'].'</b></td>
<td><b>'.$ETI['core04idaula'].'</b></td>
<td><b>'.$ETI['msg_actividades'].'</b></td>
<td align="right">
'.html_paginador('paginaf2204', $registros, $lineastabla, $pagina, 'paginarf2204()').'
'.html_lpp('lppf2204', $lineastabla, 'paginarf2204()').'
</td>
</tr>';
	$tlinea=1;
	$aAula=array('','A','B','C','D','E');
	while($filadet=$objDB->sf($tabladetalle)){
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
		$et_core04tercero=$sPrefijo.$filadet['core04tercero'].$sSufijo;
		$et_core04idaula=$sPrefijo.$aAula[$filadet['core04idaula']].$sSufijo;
		if ($babierta){
			if ($sCursoGrupo!=$filadet['core04idcurso']){
				$sLink='['.$filadet['core04idcurso'].']['.$filadet['Contenedor'].'] <a href="javascript:buscargrupo('.$filadet['core04id'].', '.$filadet['Contenedor'].')" class="lnkresalte">Ajustar</a>';
				}else{
				//Las actividades.
				$sActividades='{No se ha procesado la agenda}';
				if ($filadet['core04aplicoagenda']!=0){
					$sActividades='{Sin actividades.}';
					$sSQL='SELECT core05estado, COUNT(core05id) AS Total FROM core05actividades_'.$filadet['Contenedor'].' WHERE core05tercero='.$filadet['core04tercero'].' AND core05idcurso='.$filadet['core04idcurso'].' AND core05peraca='.$idPeraca.' GROUP BY core05estado';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$sActividades='';
						while ($fila=$objDB->sf($tabla)){
							switch($fila['core05estado']){
								case 0:
								$sTituloEstado='Pendientes';
								break;
								case 1:
								$sTituloEstado='Iniciadas';
								break;
								case 3:
								$sTituloEstado='Presentadas';
								break;
								case 5:
								$sTituloEstado='No presentadas';
								break;
								case 7:
								$sTituloEstado='Calificadas';
								break;
								default:
								$sTituloEstado='[Estado '.$fila['core05estado'].']';
								break;
								}
							$sActividades=$sActividades.' '.$fila['Total'].' '.$sTituloEstado;
							}
						}
					}
				$sLink=$sActividades;
				//Fin de las actividades.
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C3_td'].' '.$filadet['C3_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C3_nombre']).$sSufijo.'</td>
<td>'.$et_core04idaula.'</td>
<td colspan="2">'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2204_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2204_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2204detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>