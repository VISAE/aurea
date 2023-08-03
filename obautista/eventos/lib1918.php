<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- Modelo Versión 2.12.5 miércoles, 23 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- Modelo Versión 2.18.4 domingo, 16 de julio de 2017 - Se hacen ajustes a UTF8
--- Modelo Versión 2.22.6b miércoles, 28 de noviembre de 2018
--- 1918 Preguntas
*/
function html_combo_even18idgrupo($objDB, $valor, $idEncuesta){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if ($idEncuesta==''){$idEncuesta=-99;}
	$res=html_combo('even18idgrupo', 'even19id', 'even19nombre', 'even19encuestagrupo', 'even19idencuesta='.$idEncuesta, 'even19nombre', $valor, $objDB, '', true, '{'.$ETI['msg_ninguno'].'}', 0);
	return cadena_codificar($res);
	}
function html_combo_even18tiporespuesta($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('even18tiporespuesta', 'even20id', 'even20nombre', 'even20tiporespuesta', '', 'even20id', $valor, $objDB, '', false, '{'.$ETI['msg_seleccione'].'}', '');
	return cadena_codificar($res);
	}
function html_combo_even18idpregcondiciona($objDB, $valor, $idEncuesta){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if ($idEncuesta==''){$idEncuesta=0;}
	$res=html_combo('even18idpregcondiciona', 'even18id', 'CONCAT(even18consec, " ", SUBSTR(even18pregunta, 1, 50))', 'even18encuestapregunta', 'even18idencuesta='.$idEncuesta.' AND even18divergente="S"', 'even18consec', $valor, $objDB, 'carga_combo_even18valorcondiciona();', true, '{'.$ETI['msg_ninguna'].'}', '0');
	return cadena_codificar($res);
	}
function html_combo_even18valorcondiciona($objDB, $valor, $idPregunta){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if ($idPregunta==''){$idPregunta=0;}
	$iTipoPreg=1;
	if ($idPregunta!=0){
		$sSQL='SELECT even18tiporespuesta FROM even18encuestapregunta WHERE even18id='.$idPregunta;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iTipoPreg=$fila['even18tiporespuesta'];
			}
		}
	switch($iTipoPreg){
		case 0:
		$res=html_sino('even18valorcondiciona', $valor,false, '', '', '', 'Si', 'No', '1', '0');
		break;
		default:
		$res=html_combo('even18valorcondiciona', 'even29consec', 'even29etiqueta', 'even29encpregresp', 'even29idpregunta='.$idPregunta, 'even29etiqueta', $valor, $objDB, '', true, '{'.$ETI['msg_ninguna'].'}', '0');
		break;
		}
	return cadena_codificar($res);
	}
function Cargar_even18valorcondiciona($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_even18valorcondiciona=html_combo_even18valorcondiciona($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even18valorcondiciona', 'innerHTML', $html_even18valorcondiciona);
	return $objResponse;
	}
function f1918_Comboeven18valorcondiciona($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_even18valorcondiciona=html_combo_even18valorcondiciona($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even18valorcondiciona', 'innerHTML', $html_even18valorcondiciona);
	return $objResponse;
	}
function f1918_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1918;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
	require $mensajes_todas;
	require $mensajes_1918;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even18idencuesta=numeros_validar($valores[1]);
	$even18consec=numeros_validar($valores[2]);
	$even18id=numeros_validar($valores[3], true);
	$even18idgrupo=numeros_validar($valores[4]);
	$even18pregunta=(trim($valores[5]));
	$even18tiporespuesta=numeros_validar($valores[6]);
	$even18opcional=htmlspecialchars(trim($valores[7]));
	$even18concomentario=htmlspecialchars(trim($valores[8]));
	/*
	$even18rpta0=numeros_validar($valores[9]);
	$even18rpta1=numeros_validar($valores[10]);
	$even18rpta2=numeros_validar($valores[11]);
	$even18rpta3=numeros_validar($valores[12]);
	$even18rpta4=numeros_validar($valores[13]);
	$even18rpta5=numeros_validar($valores[14]);
	$even18rpta6=numeros_validar($valores[15]);
	$even18rpta7=numeros_validar($valores[16]);
	$even18rpta8=numeros_validar($valores[17]);
	$even18rpta9=numeros_validar($valores[18]);
	*/
	$even18orden=numeros_validar($valores[19]);
	$even18divergente=htmlspecialchars(trim($valores[20]));
	$even18rptatotal=numeros_validar($valores[21]);
	$even18idpregcondiciona=numeros_validar($valores[22]);
	$even18valorcondiciona=numeros_validar($valores[23]);
	$even18url=htmlspecialchars(trim($valores[24]));
	if ($even18idgrupo==''){$even18idgrupo=0;}
	/*
	//if ($even18tiporespuesta==''){$even18tiporespuesta=0;}
	//if ($even18rpta0==''){$even18rpta0=0;}
	//if ($even18rpta1==''){$even18rpta1=0;}
	//if ($even18rpta2==''){$even18rpta2=0;}
	//if ($even18rpta3==''){$even18rpta3=0;}
	//if ($even18rpta4==''){$even18rpta4=0;}
	//if ($even18rpta5==''){$even18rpta5=0;}
	//if ($even18rpta6==''){$even18rpta6=0;}
	//if ($even18rpta7==''){$even18rpta7=0;}
	//if ($even18rpta8==''){$even18rpta8=0;}
	//if ($even18rpta9==''){$even18rpta9=0;}
	//if ($even18orden==''){$even18orden=0;}
	//if ($even18rptatotal==''){$even18rptatotal=0;}
	//if ($even18idpregcondiciona==''){$even18idpregcondiciona=0;}
	//if ($even18valorcondiciona==''){$even18valorcondiciona=0;}
	$sSepara=', ';
	if ($even18valorcondiciona==''){$sError=$ERR['even18valorcondiciona'];}
	if ($even18idpregcondiciona==''){$sError=$ERR['even18idpregcondiciona'];}
	if ($even18rptatotal==''){$sError=$ERR['even18rptatotal'];}
	if ($even18divergente==''){$sError=$ERR['even18divergente'];}
	if ($even18rpta9==''){$sError=$ERR['even18rpta9'];}
	if ($even18rpta8==''){$sError=$ERR['even18rpta8'];}
	if ($even18rpta7==''){$sError=$ERR['even18rpta7'];}
	if ($even18rpta6==''){$sError=$ERR['even18rpta6'];}
	if ($even18rpta5==''){$sError=$ERR['even18rpta5'];}
	if ($even18rpta4==''){$sError=$ERR['even18rpta4'];}
	if ($even18rpta3==''){$sError=$ERR['even18rpta3'];}
	if ($even18rpta2==''){$sError=$ERR['even18rpta2'];}
	if ($even18rpta1==''){$sError=$ERR['even18rpta1'];}
	if ($even18rpta0==''){$sError=$ERR['even18rpta0'];}
	*/
	if ($even18concomentario==''){$sError=$ERR['even18concomentario'];}
	if ($even18opcional==''){$sError=$ERR['even18opcional'];}
	if ($even18tiporespuesta==''){$sError=$ERR['even18tiporespuesta'];}
	if ($even18pregunta==''){$sError=$ERR['even18pregunta'];}
	//if ($even18idgrupo==''){$sError=$ERR['even18idgrupo'];}
	//if ($even18id==''){$sError=$ERR['even18id'];}//CONSECUTIVO
	//if ($even18consec==''){$sError=$ERR['even18consec'];}//CONSECUTIVO
	if ($even18idencuesta==''){$sError=$ERR['even18idencuesta'];}
	if ($sError==''){
		switch($even18tiporespuesta){
			case 0:
			case 1:
			break;
			default:
			$even18divergente='N';
			break;
			}
		}
	if ($sError==''){
		if ((int)$even18id==0){
			if ((int)$even18consec==0){
				$even18consec=tabla_consecutivo('even18encuestapregunta', 'even18consec', 'even18idencuesta='.$even18idencuesta.'', $objDB);
				if ($even18consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT even18idencuesta FROM even18encuestapregunta WHERE even18idencuesta='.$even18idencuesta.' AND even18consec='.$even18consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even18id=tabla_consecutivo('even18encuestapregunta', 'even18id', '', $objDB);
				if ($even18id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($even18orden==''){$even18orden=$even18consec;}
		//Si el campo even18pregunta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$even18pregunta=str_replace('"', '\"', $even18pregunta);
		//$even18pregunta=str_replace('&quot;', '\"', $even18pregunta);
		if ($binserta){
			$even18rpta0=0;
			$even18rpta1=0;
			$even18rpta2=0;
			$even18rpta3=0;
			$even18rpta4=0;
			$even18rpta5=0;
			$even18rpta6=0;
			$even18rpta7=0;
			$even18rpta8=0;
			$even18rpta9=0;
			$scampos='even18idencuesta, even18consec, even18id, even18idgrupo, even18pregunta, 
even18tiporespuesta, even18opcional, even18concomentario, even18rpta0, even18rpta1, 
even18rpta2, even18rpta3, even18rpta4, even18rpta5, even18rpta6, 
even18rpta7, even18rpta8, even18rpta9, even18orden, even18divergente, 
even18rptatotal, even18idpregcondiciona, even18valorcondiciona, even18url';
			$svalores=''.$even18idencuesta.', '.$even18consec.', '.$even18id.', '.$even18idgrupo.', "'.$even18pregunta.'", 
'.$even18tiporespuesta.', "'.$even18opcional.'", "'.$even18concomentario.'", '.$even18rpta0.', '.$even18rpta1.', 
'.$even18rpta2.', '.$even18rpta3.', '.$even18rpta4.', '.$even18rpta5.', '.$even18rpta6.', 
'.$even18rpta7.', '.$even18rpta8.', '.$even18rpta9.', '.$even18orden.', "'.$even18divergente.'", 
'.$even18rptatotal.', '.$even18idpregcondiciona.', '.$even18valorcondiciona.', "'.$even18url.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even18encuestapregunta ('.$scampos.') VALUES ('.cadena_codificar($svalores).');';
				}else{
				$sSQL='INSERT INTO even18encuestapregunta ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Preguntas}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $even18id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1918[1]='even18idgrupo';
			$scampo1918[2]='even18pregunta';
			$scampo1918[3]='even18opcional';
			$scampo1918[4]='even18concomentario';
			$scampo1918[5]='even18orden';
			$scampo1918[6]='even18divergente';
			$scampo1918[7]='even18idpregcondiciona';
			$scampo1918[8]='even18valorcondiciona';
			$scampo1918[9]='even18url';
			$svr1918[1]=$even18idgrupo;
			$svr1918[2]=$even18pregunta;
			$svr1918[3]=$even18opcional;
			$svr1918[4]=$even18concomentario;
			$svr1918[5]=$even18orden;
			$svr1918[6]=$even18divergente;
			$svr1918[7]=$even18idpregcondiciona;
			$svr1918[8]=$even18valorcondiciona;
			$svr1918[9]=$even18url;
			$inumcampos=9;
			$sWhere='even18id='.$even18id.'';
			//$sWhere='even18idencuesta='.$even18idencuesta.' AND even18consec='.$even18consec.'';
			$sSQL='SELECT * FROM even18encuestapregunta WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1918[$k]]!=$svr1918[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1918[$k].'="'.$svr1918[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE even18encuestapregunta SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE even18encuestapregunta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Preguntas}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $even18id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even18id, $sDebug);
	}
function f1918_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1918;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
	require $mensajes_todas;
	require $mensajes_1918;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$even18idencuesta=numeros_validar($aParametros[1]);
	$even18consec=numeros_validar($aParametros[2]);
	$even18id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1918';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even18id.' LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//acciones previas
		//Matar las respuestas.
		$sSQL='DELETE FROM even29encpregresp WHERE even29idpregunta='.$even18id.'';
		$result=$objDB->ejecutasql($sSQL);
		$sWhere='even18id='.$even18id.'';
		//$sWhere='even18idencuesta='.$even18idencuesta.' AND even18consec='.$even18consec.'';
		$sSQL='DELETE FROM even18encuestapregunta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1918 Preguntas}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $even18id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1918_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
	require $mensajes_todas;
	require $mensajes_1918;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]=0;}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	$aParametros[104]=numeros_validar($aParametros[104]);
	$aParametros[105]=numeros_validar($aParametros[105]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$even16id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$sCondi='';
	if ($aParametros[104]!=''){
		$sCondi=' AND TB.even21idperaca='.$aParametros[104].'';
		}
	if ($aParametros[105]!=''){
		$sCondi=' AND TB.even21idcurso='.$aParametros[105].'';
		}
	$bConResultados=false;
	$iPendientes=0;
	$iUniverso=0;
	if ($aParametros[103]==1){
		$bConResultados=true;
		$sSQL='SELECT COUNT(TB.even21id) AS total, TB.even21terminada FROM even21encuestaaplica AS TB WHERE TB.even21idencuesta='.$even16id.$sCondi.' GROUP BY TB.even21terminada';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			if ($fila['even21terminada']=='S'){
				$iUniverso=$iUniverso+$fila['total'];
				}else{
				$iPendientes=$iPendientes+$fila['total'];
				}
			}
		}
	$babierta=false;
	if (!$bConResultados){
		$sSQL='SELECT even16publicada FROM even16encuesta WHERE even16id='.$even16id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['even16publicada']!='S'){$babierta=true;}
			}
		}
	$sSQLadd='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	$sTitulos='Encuesta, Consec, Id, Grupo, Pregunta, Tiporespuesta, Opcional, Concomentario, Rpta0, Rpta1, Rpta2, Rpta3, Rpta4, Rpta5';
	$sSQL='SELECT TB.even18idencuesta, TB.even18consec, TB.even18id, T4.even19nombre, TB.even18pregunta, T6.even20nombre, TB.even18opcional, TB.even18concomentario, TB.even18rpta0, TB.even18rpta1, TB.even18rpta2, TB.even18rpta3, TB.even18rpta4, TB.even18rpta5, TB.even18rpta6, TB.even18rpta7, TB.even18rpta8, TB.even18rpta9, TB.even18idgrupo, TB.even18tiporespuesta, TB.even18orden, TB.even18divergente, TB.even18idpregcondiciona, TB.even18valorcondiciona 
FROM even18encuestapregunta AS TB, even19encuestagrupo AS T4, even20tiporespuesta AS T6 
WHERE TB.even18idencuesta='.$even16id.' AND TB.even18idgrupo=T4.even19id AND TB.even18tiporespuesta=T6.even20id '.$sSQLadd.'
ORDER BY TB.even18orden, TB.even18consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1918" name="consulta_1918" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1918" name="titulos_1918" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1918: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf1918" name="paginaf1918" type="hidden" value="'.$pagina.'"/><input id="lppf1918" name="lppf1918" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$iTope=50;
	$sTituloUniverso='';
	if ($bConResultados){
		$iTope=200;
		$iTotalAplicadas=$iPendientes+$iUniverso;
		if ($iTotalAplicadas>0){
			$sTituloUniverso='<tr class="fondoazul">
<td colspan="9" align="center">Resueltas <b>'.formato_numero($iUniverso).' ('.formato_numero(($iUniverso*100)/$iTotalAplicadas, 2).' %)</b> - Pendientes <b>'.formato_numero($iPendientes).' ('.formato_numero(($iPendientes*100)/$iTotalAplicadas, 2).' %)</b> Total <b>'.formato_numero($iTotalAplicadas).'</b></td>
</tr>';
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">'.$sTituloUniverso.'
<tr class="fondoazul">
<td><b>'.$ETI['even18consec'].'</b></td>
<td><b>'.$ETI['even18pregunta'].'</b></td>
<td><b>'.$ETI['even18tiporespuesta'].'</b></td>
<td><b>'.$ETI['even18orden'].'</b></td>
<td><b>'.$ETI['even18opcional'].'</b></td>
<td><b>'.$ETI['even18divergente'].'</b></td>
<td colspan="2"></td>
<td align="right">
'.html_paginador('paginaf1918', $registros, $lineastabla, $pagina, 'paginarf1918()').'
'.html_lpp('lppf1918', $lineastabla, 'paginarf1918()', $iTope).'
</td>
</tr>';
	$tlinea=1;
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
		$et_even18opcional='&nbsp;';
		if ($filadet['even18opcional']=='S'){$et_even18opcional=$ETI['si'];}
		$et_even18divergente='&nbsp;';
		if ($filadet['even18divergente']=='S'){$et_even18divergente=$ETI['si'];}
		$et_even18idpregcondiciona='';
		$et_even18valorcondiciona='';
		if ($filadet['even18idpregcondiciona']!=0){
			$et_even18idpregcondiciona=$filadet['even18idpregcondiciona'];
			$et_even18valorcondiciona=$filadet['even18valorcondiciona'];
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1918('.$filadet['even18id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even18consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even18pregunta']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even20nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even18orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18opcional.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18divergente.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18idpregcondiciona.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18valorcondiciona.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		if ($bConResultados){
			$sRespuestas='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr><td width="71%"></td><td width="2%"></td><td width="10%"></td><td width="2%"></td><td width="15%"></td></tr>';
			switch($filadet['even18tiporespuesta']){
				case 0:
				$iPorc0='';
				$iPorc1='';
				if ($iUniverso>0){
					$iPorc0=round(($filadet['even18rpta0']*100)/$iUniverso,4).' %';
					$iPorc1=round(($filadet['even18rpta1']*100)/$iUniverso,4).' %';
					}
				$sRespuestas=$sRespuestas.'<tr><td align="right">No</td><td></td><td><b>'.formato_numero($filadet['even18rpta0']).'</b></td><td></td><td><b>'.$iPorc0.'</b></td></tr>
<tr><td align="right">Si</td><td></td><td><b>'.formato_numero($filadet['even18rpta1']).'</b></td><td></td><td><b>'.$iPorc1.'</b></td></tr>';
				break;
				case 1:
				case 2:
				$sSQL='SELECT even29consec, even29etiqueta FROM even29encpregresp WHERE even29idpregunta='.$filadet['even18id'];
				$tabla29=$objDB->ejecutasql($sSQL);
				while ($fila29=$objDB->sf($tabla29)){
					$iConseRespuesta=$fila29['even29consec'];
					$iPorc='';
					if ($iUniverso>0){
						$iPorc=round(($filadet['even18rpta'.$iConseRespuesta]*100)/$iUniverso,4).' %';
						}
					$sRespuestas=$sRespuestas.'<tr><td align="right">'.cadena_notildes($fila29['even29etiqueta']).'</td><td></td><td><b>'.formato_numero($filadet['even18rpta'.$iConseRespuesta]).'</b></td><td></td><td><b>'.$iPorc.'</b></td></tr>';
					}
				break;
				default:
				$sRespuestas=$sRespuestas.'<tr><td colspan="5">{Respuesta abierta - No cuantificable.}</td></tr>';
				break;
				}
			$sRespuestas=$sRespuestas.'</table>';
			$res=$res.'<tr'.$sClass.'>
<td colspan="2"></td>
<td colspan="7">'.$sRespuestas.'</td>
</tr>';
			}
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f1918_Clonar($even18idencuesta, $even18idencuestaPadre, $objDB){
	$sError='';
	$even18consec=tabla_consecutivo('even18encuestapregunta', 'even18consec', 'even18idencuesta='.$even18idencuesta.'', $objDB);
	if ($even18consec==-1){$sError=$objDB->serror;}
	$even18id=tabla_consecutivo('even18encuestapregunta', 'even18id', '', $objDB);
	if ($even18id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1918='even18idencuesta, even18consec, even18id, even18idgrupo, even18pregunta, even18tiporespuesta, even18opcional, even18concomentario, even18rpta0, even18rpta1, even18rpta2, even18rpta3, even18rpta4, even18rpta5, even18rpta6, even18rpta7, even18rpta8, even18rpta9, even18orden, even18divergente, even18rptatotal, even18idpregcondiciona, even18valorcondiciona, even18url';
		$sValores1918='';
		$sSQL='SELECT * FROM even18encuestapregunta WHERE even18idencuesta='.$even18idencuestaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1918!=''){$sValores1918=$sValores1918.', ';}
			$sValores1918=$sValores1918.'('.$even18idencuesta.', '.$even18consec.', '.$even18id.', '.$fila['even18idgrupo'].', "'.$fila['even18pregunta'].'", '.$fila['even18tiporespuesta'].', "'.$fila['even18opcional'].'", "'.$fila['even18concomentario'].'", '.$fila['even18rpta0'].', '.$fila['even18rpta1'].', '.$fila['even18rpta2'].', '.$fila['even18rpta3'].', '.$fila['even18rpta4'].', '.$fila['even18rpta5'].', '.$fila['even18rpta6'].', '.$fila['even18rpta7'].', '.$fila['even18rpta8'].', '.$fila['even18rpta9'].', '.$fila['even18orden'].', "'.$fila['even18divergente'].'", '.$fila['even18rptatotal'].', '.$fila['even18idpregcondiciona'].', '.$fila['even18valorcondiciona'].', "'.$fila['even18url'].'")';
			$even18consec++;
			$even18id++;
			}
		if ($sValores1918!=''){
			$sSQL='INSERT INTO even18encuestapregunta('.$sCampos1918.') VALUES '.$sValores1918.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1918 Preguntas XAJAX 
function f1918_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $even18id, $sDebugGuardar)=f1918_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1918_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1918detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1918('.$even18id.')');
			//}else{
			$objResponse->call('limpiaf1918');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1918_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$even18idencuesta=numeros_validar($aParametros[1]);
		$even18consec=numeros_validar($aParametros[2]);
		if (($even18idencuesta!='')&&($even18consec!='')){$besta=true;}
		}else{
		$even18id=$aParametros[103];
		if ((int)$even18id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'even18idencuesta='.$even18idencuesta.' AND even18consec='.$even18consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even18id='.$even18id.'';
			}
		$sSQL='SET CHARACTER SET utf8';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='SELECT * FROM even18encuestapregunta WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		$even18consec_nombre='';
		$html_even18consec=html_oculto('even18consec', $fila['even18consec'], $even18consec_nombre);
		$objResponse->assign('div_even18consec', 'innerHTML', $html_even18consec);
		$html_even18id=html_oculto('even18id', $fila['even18id']);
		$objResponse->assign('div_even18id', 'innerHTML', $html_even18id);
		$html_even18idgrupo=html_combo_even18idgrupo($objDB, $fila['even18idgrupo'], $fila['even18idencuesta']);
		$objResponse->assign('div_even18idgrupo', 'innerHTML', $html_even18idgrupo);
		$objResponse->assign('even18pregunta', 'value', ($fila['even18pregunta']));
		//list($even18tiporespuesta_nombre, $serror_det)=tabla_campoxid('even20tiporespuesta','even20nombre','even20id', $fila['even18tiporespuesta'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$even18tiporespuesta_nombre='{'.$fila['even18tiporespuesta'].'}';
		$sSQL='SELECT even20nombre FROM even20tiporespuesta WHERE even20id='.$fila['even18tiporespuesta'].'';
		$tabla20=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla20)>0){
			$fila20=$objDB->sf($tabla20);
			$even18tiporespuesta_nombre=cadena_notildes($fila20['even20nombre']);
			}
		$html_even18tiporespuesta=html_oculto('even18tiporespuesta', $fila['even18tiporespuesta'], $even18tiporespuesta_nombre);
		//echo $html_even18tiporespuesta;
		$objResponse->assign('div_even18tiporespuesta', 'innerHTML', $html_even18tiporespuesta);

		$objResponse->assign('even18opcional', 'value', $fila['even18opcional']);
		$objResponse->assign('even18concomentario', 'value', $fila['even18concomentario']);
		$objResponse->assign('even18rpta0', 'value', $fila['even18rpta0']);
		$html_respuestas=f1918_GrupoRespuestas($fila['even18id'], $objDB);
		$objResponse->assign('div_1918Respuestas', 'innerHTML', $html_respuestas);
		$objResponse->assign('even18orden', 'value', $fila['even18orden']);
		$objResponse->assign('even18divergente', 'value', $fila['even18divergente']);
		$even18rptatotal_eti=$fila['even18rptatotal'];
		$html_even18rptatotal=html_oculto('even18rptatotal', $fila['even18rptatotal'], $even18rptatotal_eti);
		$objResponse->assign('div_even18rptatotal', 'innerHTML', $html_even18rptatotal);
		$objResponse->assign('even18idpregcondiciona', 'value', $fila['even18idpregcondiciona']);
		$html_even18valorcondiciona=html_combo_even18valorcondiciona($objDB, $fila['even18valorcondiciona'], $fila['even18idpregcondiciona']);
		$objResponse->assign('div_even18valorcondiciona', 'innerHTML', $html_even18valorcondiciona);
		$objResponse->assign('even18url', 'value', $fila['even18url']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1918','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even18consec', 'value', $even18consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even18id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1918_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f1918_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1918_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1918detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1918');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f1918_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1918_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1918detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1918_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	$aParametros[1]=numeros_validar($aParametros[1]);
	if ($aParametros[1]==''){$aParametros[1]=-99;}
	$even16id=$aParametros[1];
	$html_even18consec='<input id="even18consec" name="even18consec" type="text" value="" onchange="revisaf1918()" class="cuatro"/>';
	$html_even18id='<input id="even18id" name="even18id" type="hidden" value=""/>';
	$html_even18idgrupo=html_combo_even18idgrupo($objDB, 0, $even16id);
	$html_even18tiporespuesta=html_combo_even18tiporespuesta($objDB, 0);
	$html_respuestas=f1918_GrupoRespuestas(0, $objDB);
	$html_even18idpregcondiciona=html_combo_even18idpregcondiciona($objDB, 0, $even16id);
	$html_even18valorcondiciona=html_combo_even18valorcondiciona($objDB, 0, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even18consec','innerHTML', $html_even18consec);
	$objResponse->assign('div_even18id','innerHTML', $html_even18id);
	$objResponse->assign('div_even18idgrupo','innerHTML', $html_even18idgrupo);
	$objResponse->assign('div_even18tiporespuesta','innerHTML', $html_even18tiporespuesta);
	$objResponse->assign('even18rpta0','value', 0);
	$objResponse->assign('div_even18idpregcondiciona', 'innerHTML', $html_even18idpregcondiciona);
	$objResponse->assign('div_even18valorcondiciona', 'innerHTML', $html_even18valorcondiciona);
	$objResponse->assign('div_1918Respuestas','innerHTML', $html_respuestas);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

function f1918_GrupoRespuestas($id18, $objDB){
	if ($id18==''){$id18=0;}
	$res='';
	$iTipoRespuesta=0;
	$bHayResuestas=false;
	$bConBotones=false;
	if ($id18!=0){
		//Sacar el tipo de respuesta... si es 1 mostrar...
		$sSQL='SELECT even18tiporespuesta, even18rpta0 FROM even18encuestapregunta WHERE even18id='.$id18;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iTipoRespuesta=$fila['even18tiporespuesta'];
			if ($iTipoRespuesta==1){$bHayResuestas=true;}
			if ($iTipoRespuesta==2){$bHayResuestas=true;}
			if ($bHayResuestas){
				$bConBotones=true;
				//Si ha sido usada no puede llevar botones.
				$sSQL='SELECT even22id FROM even22encuestarpta WHERE even22idpregunta='.$id18.' LIMIT 0, 1';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)!=0){$bConBotones=false;}
				//Pintar las respustas...
				$sSQL='SELECT even29consec, even29id, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta='.$id18.' ORDER BY even29consec';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)==0){
					//Insertamos las dos primeras...
					$id29=tabla_consecutivo('even29encpregresp', 'even29id', '', $objDB);
					$sSQL2='INSERT INTO even29encpregresp(even29idpregunta, even29consec, even29id, even29etiqueta, even29detalle) VALUES ('.$id18.', 1, '.$id29.', "", ""), ('.$id18.', 2, '.($id29+1).', "", "")';
					$objDB->ejecutasql($sSQL2);
					$tabla=$objDB->ejecutasql($sSQL);
					}
				}
			$iFila=1;
			$res=$res.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
			$iOpcionFin=$objDB->nf($tabla);
			while ($fila=$objDB->sf($tabla)){
				$id29=$fila['even29id'];
				$sRem='';
				if ($bConBotones){
				if ($iFila==$iOpcionFin){
					if ($iFila>2){
						$sRem='<input id="cmdRemOpcion" name="cmdRemOpcion" type="button" class="btMiniMenos" onclick="f1918quitaropcion('.$id29.')" value="Quitar opci&oacute;n" title="Quitar opci&oacute;n" />';
						}
					}
					}
				$res=$res.'<tr>
<td>'.$fila['even29consec'].'</td>
<td><input id="et_29_'.$id29.'" name="et_29_'.$id29.'" type="text" value="'.$fila['even29etiqueta'].'" onchange="f1929etiqueta('.$id29.', this.value)" class="Label450" placeholder="Etiqueta"/></td>
<td><input id="de_29_'.$id29.'" name="de_29_'.$id29.'" type="text" value="'.$fila['even29detalle'].'" onchange="f1929detalle('.$id29.', this.value)" class="Label450" placeholder="Descripci&oacute;n"/></td>
<td>'.$sRem.'</td>
</tr>';
				$iFila++;
				}
			if ($bConBotones){
			if ($iFila<10){
				if ($bHayResuestas){
					$res=$res.'<tr>
<td><input id="cmdAdOpcion" name="cmdAdOpcion" type="button" class="btMiniMas" onclick="f1918adicionaropcion()" value="Agregar opci&oacute;n" title="Agregar opci&oacute;n" /></td>
<td colspan="3"></td>
</tr>';
					}
				}
				}
			$res=$res.'</table>';
			for ($k=$iFila;$k<=9;$k++){
				$res=$res.'<input id="even18rpta'.$k.'" name="even18rpta'.$k.'" type="hidden" value="0" />';
				}
			}
		}
	if ($res==''){
		$res='<input id="even18rpta1" name="even18rpta1" type="hidden" value="0" />
<input id="even18rpta2" name="even18rpta2" type="hidden" value="0" />
<input id="even18rpta3" name="even18rpta3" type="hidden" value="0" />
<input id="even18rpta4" name="even18rpta4" type="hidden" value="0" />
<input id="even18rpta5" name="even18rpta5" type="hidden" value="0" />
<input id="even18rpta6" name="even18rpta6" type="hidden" value="0" />
<input id="even18rpta7" name="even18rpta7" type="hidden" value="0" />
<input id="even18rpta8" name="even18rpta8" type="hidden" value="0" />
<input id="even18rpta9" name="even18rpta9" type="hidden" value="0" />';
		}
	return $res;
	}
function f1918_AgregarOpcion($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$id18=numeros_validar($aParametros[1]);
	if ($id18==''){$id18=0;}
	if ($id18>0){
		$id29=tabla_consecutivo('even29encpregresp', 'even29id', '', $objDB);
		$consec29=tabla_consecutivo('even29encpregresp', 'even29consec', 'even29idpregunta='.$id18.'', $objDB);
		$sSQL2='INSERT INTO even29encpregresp(even29idpregunta, even29consec, even29id, even29etiqueta, even29detalle) VALUES ('.$id18.', '.$consec29.', '.$id29.', "", "")';
		$objDB->ejecutasql($sSQL2);
		$html_respuestas=f1918_GrupoRespuestas($id18, $objDB);
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_1918Respuestas','innerHTML', $html_respuestas);
		return $objResponse;
		}
	}
function f1918_QuitarOpcion($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$id18=numeros_validar($aParametros[1]);
	$id29=numeros_validar($aParametros[2]);
	if ($id18==''){$id18=0;}
	if ($id18>0){
		$sSQL2='DELETE FROM even29encpregresp WHERE even29id='.$id29.'';
		$objDB->ejecutasql($sSQL2);
		$html_respuestas=f1918_GrupoRespuestas($id18, $objDB);
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_1918Respuestas','innerHTML', $html_respuestas);
		//$objResponse->assign('alarma', 'innerHTML', $sSQL2);
		return $objResponse;
		}
	}
?>