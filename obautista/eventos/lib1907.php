<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.8 viernes, 03 de octubre de 2014
--- 1907 Variables
*/
function f1907_db_Guardar($valores, $objdb){
	$icodmodulo=1907;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1907='lg/lg_1907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1907)){$mensajes_1907='lg/lg_1907_es.php';}
	require $mensajes_todas;
	require $mensajes_1907;
	$sError='';
	$objdb->xajax();
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even07idcertificado=numeros_validar($valores[1]);
	$even07consec=numeros_validar($valores[2]);
	$even07id=numeros_validar($valores[3], true);
	$even07idvariable=numeros_validar($valores[4]);
	$even07izquierda=numeros_validar($valores[5]);
	$even07arriba=numeros_validar($valores[6]);
	$even07ancho=numeros_validar($valores[7]);
	$even07fuente=htmlspecialchars($valores[8]);
	$even07tipofuente=htmlspecialchars($valores[9]);
	$even07fuentetam=numeros_validar($valores[10]);
	$even07fuentecolor=htmlspecialchars($valores[11]);
	$even07alineacion=htmlspecialchars($valores[12]);
	//if ($even07idvariable==''){$even07idvariable=0;}
	//if ($even07izquierda==''){$even07izquierda=0;}
	//if ($even07arriba==''){$even07arriba=0;}
	//if ($even07ancho==''){$even07ancho=0;}
	//if ($even07fuentetam==''){$even07fuentetam=0;}
	if ($even07alineacion==''){$sError=$ERR['even07alineacion'];}
	if ($even07fuentecolor==''){$sError=$ERR['even07fuentecolor'];}
	if ($even07fuentetam==''){$sError=$ERR['even07fuentetam'];}
	if ($even07tipofuente==''){$sError=$ERR['even07tipofuente'];}
	if ($even07fuente==''){$sError=$ERR['even07fuente'];}
	if ($even07ancho==''){$sError=$ERR['even07ancho'];}
	if ($even07arriba==''){$sError=$ERR['even07arriba'];}
	if ($even07izquierda==''){$sError=$ERR['even07izquierda'];}
	if ($even07idvariable==''){$sError=$ERR['even07idvariable'];}
	//if ($even07id==''){$sError=$ERR['even07id'];}//CONSECUTIVO
	//if ($even07consec==''){$sError=$ERR['even07consec'];}//CONSECUTIVO
	if ($even07idcertificado==''){$sError=$ERR['even07idcertificado'];}
	if ($sError==''){
		if ((int)$even07id==0){
			if ((int)$even07consec==0){
				$even07consec=tabla_consecutivo('even07certvariable', 'even07consec', 'even07idcertificado='.$even07idcertificado.'', $objdb);
				if ($even07consec==-1){$sError=$objdb->serror;}
				}
			$sql='SELECT even07idcertificado FROM even07certvariable WHERE even07idcertificado='.$even07idcertificado.' AND even07consec='.$even07consec.'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$even07id=tabla_consecutivo('even07certvariable', 'even07id', '', $objdb);
				if ($even07id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='even07idcertificado, even07consec, even07id, even07idvariable, even07izquierda, even07arriba, even07ancho, even07fuente, even07tipofuente, even07fuentetam, even07fuentecolor, even07alineacion';
			$svalores=''.$even07idcertificado.', '.$even07consec.', '.$even07id.', '.$even07idvariable.', '.$even07izquierda.', '.$even07arriba.', '.$even07ancho.', "'.$even07fuente.'", "'.$even07tipofuente.'", '.$even07fuentetam.', "'.$even07fuentecolor.'", "'.$even07alineacion.'"';
			$sql='INSERT INTO even07certvariable ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError='Error critico al tratar de guardar Variables, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objdb);
					}
				}
			}else{
			$scampo1907[1]='even07idvariable';
			$scampo1907[2]='even07izquierda';
			$scampo1907[3]='even07arriba';
			$scampo1907[4]='even07ancho';
			$scampo1907[5]='even07fuente';
			$scampo1907[6]='even07tipofuente';
			$scampo1907[7]='even07fuentetam';
			$scampo1907[8]='even07fuentecolor';
			$scampo1907[9]='even07alineacion';
			$svr1907[1]=$even07idvariable;
			$svr1907[2]=$even07izquierda;
			$svr1907[3]=$even07arriba;
			$svr1907[4]=$even07ancho;
			$svr1907[5]=$even07fuente;
			$svr1907[6]=$even07tipofuente;
			$svr1907[7]=$even07fuentetam;
			$svr1907[8]=$even07fuentecolor;
			$svr1907[9]=$even07alineacion;
			$inumcampos=9;
			$sWhere='even07idcertificado='.$even07idcertificado.' AND even07consec='.$even07consec.'';
			$sql='SELECT * FROM even07certvariable WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1907[$k]]!=$svr1907[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1907[$k].'="'.$svr1907[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE even07certvariable SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Variables}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, 0, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f1907_db_Eliminar($params, $objdb){
	$icodmodulo=1907;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1907='lg/lg_1907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1907)){$mensajes_1907='lg/lg_1907_es.php';}
	require $mensajes_todas;
	require $mensajes_1907;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even07idcertificado=numeros_validar($params[1]);
	$even07consec=numeros_validar($params[2]);
/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even07idcertificado='.$even07idcertificado.' AND even07consec='.$even07consec.'';
		$sql='DELETE FROM even07certvariable WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1907 Variables}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1907_TablaDetalle($params, $objdb){
	$mensajes_1907='lg/lg_1907_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1907)){$mensajes_1907='lg/lg_1907_es.php';}
	require $mensajes_1907;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$even06id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Certificado, Consec, Id, Variable, Izquierda, Arriba, Ancho, Fuente, Tipofuente, Fuentetam, Fuentecolor, Alineacion';
	$sql='SELECT TB.even07idcertificado, TB.even07consec, TB.even07id, T4.even15nombre, TB.even07izquierda, TB.even07arriba, TB.even07ancho, T8.unad33nombre, T9.unad34nombre, TB.even07fuentetam, TB.even07fuentecolor, T12.unad32nombre, TB.even07idvariable, TB.even07fuente, TB.even07tipofuente, TB.even07alineacion 
FROM even07certvariable AS TB, even15vars AS T4, unad33reportefuente AS T8, unad34reportetipofuente AS T9, unad32reportealineacion AS T12 
WHERE TB.even07idcertificado='.$even06id.' AND TB.even07idvariable=T4.even15id AND TB.even07fuente=T8.unad33id AND TB.even07tipofuente=T9.unad34id AND TB.even07alineacion=T12.unad32id '.$sqladd.'';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1907" name="consulta_1907" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1907" name="titulos_1907" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	$sErrConsulta='';
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta='..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return utf8_encode($sErrConsulta.'<input id="paginaf1907" name="paginaf1907" type="hidden" value="'.$pagina.'"/><input id="lppf1907" name="lppf1907" type="hidden" value="'.$lineastabla.'"/>');
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even07consec'].'</b></td>
<td><b>'.$ETI['even07idvariable'].'</b></td>
<td><b>'.$ETI['even07izquierda'].'</b></td>
<td><b>'.$ETI['even07arriba'].'</b></td>
<td><b>'.$ETI['even07ancho'].'</b></td>
<td><b>'.$ETI['even07fuente'].'</b></td>
<td><b>'.$ETI['even07tipofuente'].'</b></td>
<td><b>'.$ETI['even07fuentetam'].'</b></td>
<td><b>'.$ETI['even07fuentecolor'].'</b></td>
<td><b>'.$ETI['even07alineacion'].'</b></td>
<td align="right">
'.html_paginador("paginaf1907", $registros, $lineastabla, $pagina, "paginarf1907()").'
'.html_lpp("lppf1907", $lineastabla, "paginarf1907()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1907('."'".$filadet['even07id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even07consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even15nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even07izquierda'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even07arriba'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even07ancho'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad33nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad34nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even07fuentetam.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even07fuentecolor']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad32nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
// -- 1907 Variables XAJAX 
function f1907_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($sError)=f1907_db_Guardar($valores, $objdb);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1907_TablaDetalle($params, $objdb);
		$objResponse->assign("div_f1907detalle","innerHTML",$sdetalle);
		$objResponse->call("limpiaf1907");
		$objResponse->assign("alarma","innerHTML",'item guardado');
		}else{
		$objResponse->assign("alarma","innerHTML",$sError);
		}
	return $objResponse;
	}
function f1907_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even07idcertificado=numeros_validar($params[1]);
		$even07consec=numeros_validar($params[2]);
		if (($even07idcertificado!='')&&($even07consec!='')){$besta=true;}
		}else{
		$even07id=$params[103];
		if ((int)$even07id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even07idcertificado='.$even07idcertificado.' AND even07consec='.$even07consec.'';
			}else{
			$sqlcondi=$sqlcondi.'even07id='.$even07id.'';
			}
		$sql='SELECT * FROM even07certvariable WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
		require $mensajes_todas;
		$even07consec_nombre='';
		$html_even07consec=html_oculto('even07consec', $fila['even07consec'], $even07consec_nombre);
		$objResponse->assign('div_even07consec', 'innerHTML', $html_even07consec);
		$even07id_nombre='';
		$html_even07id=html_oculto('even07id', $fila['even07id'], $even07id_nombre);
		$objResponse->assign('div_even07id', 'innerHTML', $html_even07id);
		$objResponse->assign('even07idvariable', 'value', $fila['even07idvariable']);
		$objResponse->assign('even07izquierda', 'value', $fila['even07izquierda']);
		$objResponse->assign('even07arriba', 'value', $fila['even07arriba']);
		$objResponse->assign('even07ancho', 'value', $fila['even07ancho']);
		$objResponse->assign('even07fuente', 'value', $fila['even07fuente']);
		$objResponse->assign('even07tipofuente', 'value', $fila['even07tipofuente']);
		$objResponse->assign('even07fuentetam', 'value', $fila['even07fuentetam']);
		$objResponse->assign('even07fuentecolor', 'value', $fila['even07fuentecolor']);
		$objResponse->assign('even07alineacion', 'value', $fila['even07alineacion']);
		$objResponse->assign("alarma","innerHTML",'');
		$objResponse->call("verboton('belimina1907','block')");
		}else{
		if ($paso==1){
			$objResponse->assign("even07consec","value",$even07consec);
			}else{
			$objResponse->assign("alarma","innerHTML",'No se encontro el registro de referencia:'.$even07id);
			}
		}
	return $objResponse;
	}
function f1907_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sError=f1907_db_Eliminar($params, $objdb);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1907_TablaDetalle($params, $objdb);
		$objResponse->assign("div_f1907detalle","innerHTML",$sDetalle);
		$objResponse->call("limpiaf1907");
		$sError='Item eliminado';
		}
	$objResponse->assign("alarma","innerHTML",$sError);
	return $objResponse;
	}
function f1907_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f1907_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1907detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1907_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$html_even07consec='<input id="even07consec" name="even07consec" type="text" value="" onchange="revisaf1907()" class="cuatro"/>';
	$html_even07id='<input id="even07id" name="even07id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even07consec','innerHTML', $html_even07consec);
	$objResponse->assign('div_even07id','innerHTML', $html_even07id);
	return $objResponse;
	}
?>