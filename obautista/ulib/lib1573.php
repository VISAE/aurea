<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2015 ---
--- mauro@avellaneda.co - http://www.mauroavellaneda.com
--- Modelo Versión 2.8.0 sábado, 27 de junio de 2015
--- 1573 Archivos enviados
*/
function f1573_db_Guardar($valores, $objdb){
	$icodmodulo=1573;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1573=$APP->rutacomun.'lg/lg_1573_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1573)){$mensajes_1573=$APP->rutacomun.'lg/lg_1573_es.php';}
	require $mensajes_todas;
	require $mensajes_1573;
	$sError='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unad73idftp=numeros_validar($valores[2]);
	$unad73consec=numeros_validar($valores[3]);
	$unad73id=numeros_validar($valores[4], true);
	$unad73carpeta=htmlspecialchars($valores[5]);
	$unad73archivo=htmlspecialchars($valores[6]);
	$unad73fecha=$valores[7];
	$unad73horaini=numeros_validar($valores[8]);
	$unad73minini=numeros_validar($valores[9]);
	$unad73horafin=numeros_validar($valores[10]);
	$unad73minfin=numeros_validar($valores[11]);
	$unad73detalle=htmlspecialchars($valores[12]);
	//if ($unad73horaini==''){$unad73horaini=0;}
	//if ($unad73minini==''){$unad73minini=0;}
	//if ($unad73horafin==''){$unad73horafin=0;}
	//if ($unad73minfin==''){$unad73minfin=0;}
	if ($unad73detalle==''){$sError=$ERR['unad73detalle'];}
	if ($unad73minfin==''){$sError=$ERR['unad73minfin'];}
	if ($unad73horafin==''){$sError=$ERR['unad73horafin'];}
	if ($unad73minini==''){$sError=$ERR['unad73minini'];}
	if ($unad73horaini==''){$sError=$ERR['unad73horaini'];}
	if (!fecha_esvalida($unad73fecha)){
		//$unad73fecha='00/00/0000';
		$sError=$ERR['unad73fecha'];
		}
	if ($unad73archivo==''){$sError=$ERR['unad73archivo'];}
	if ($unad73carpeta==''){$sError=$ERR['unad73carpeta'];}
	//if ($unad73id==''){$sError=$ERR['unad73id'];}//CONSECUTIVO
	//if ($unad73consec==''){$sError=$ERR['unad73consec'];}//CONSECUTIVO
	if ($unad73idftp==''){$sError=$ERR['unad73idftp'];}
	if ($sError==''){
		if ((int)$unad73id==0){
			if ((int)$unad73consec==0){
				$unad73consec=tabla_consecutivo('unad73ftpenvio', 'unad73consec', 'unad73idftp='.$unad73idftp.'', $objdb);
				if ($unad73consec==-1){$sError=$objdb->serror;}
				}
			$sql='SELECT unad73id FROM unad73ftpenvio WHERE unad73idftp='.$unad73idftp.' AND unad73consec='.$unad73consec.'';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$unad73id=tabla_consecutivo('unad73ftpenvio', 'unad73id', '', $objdb);
				if ($unad73id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Si el campo unad73detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unad73detalle=str_replace('"', '\"', $unad73detalle);
		$unad73detalle=str_replace('&quot;', '\"', $unad73detalle);
		if ($binserta){
			$scampos='unad73idftp, unad73consec, unad73id, unad73carpeta, unad73archivo, unad73fecha, unad73horaini, unad73minini, unad73horafin, unad73minfin, unad73detalle';
			$svalores=''.$unad73idftp.', '.$unad73consec.', '.$unad73id.', "'.$unad73carpeta.'", "'.$unad73archivo.'", "'.$unad73fecha.'", '.$unad73horaini.', '.$unad73minini.', '.$unad73horafin.', '.$unad73minfin.', "'.$unad73detalle.'"';
			$sql='INSERT INTO unad73ftpenvio ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Archivos enviados}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objdb);
					}
				}
			}else{
			$scampo1573[1]='unad73detalle';
			$svr1573[1]=$unad73detalle;
			$inumcampos=1;
			$sWhere='unad73idftp='.$unad73idftp.' AND unad73consec='.$unad73consec.'';
			$sql='SELECT * FROM unad73ftpenvio WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1573[$k]]!=$svr1573[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1573[$k].'="'.$svr1573[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE unad73ftpenvio SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Archivos enviados}. <!-- '.$sql.' -->';
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
function f1573_db_Eliminar($params, $objdb){
	$icodmodulo=1573;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1573=$APP->rutacomun.'lg/lg_1573_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1573)){$mensajes_1573=$APP->rutacomun.'lg/lg_1573_es.php';}
	require $mensajes_todas;
	require $mensajes_1573;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$unad73idftp=numeros_validar($params[2]);
	$unad73consec=numeros_validar($params[3]);
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
		$sWhere='unad73idftp='.$unad73idftp.' AND unad73consec='.$unad73consec.'';
		$sql='DELETE FROM unad73ftpenvio WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1573 Archivos enviados}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1573_TablaDetalle($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1573=$APP->rutacomun.'lg/lg_1573_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1573)){$mensajes_1573=$APP->rutacomun.'lg/lg_1573_es.php';}
	require $mensajes_todas;
	require $mensajes_1573;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$gen22id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Ftp, Consec, Id, Carpeta, Archivo, Fecha, Horaini, Minini, Horafin, Minfin, Detalle';
	$sql='SELECT TB.unad73idftp, TB.unad73consec, TB.unad73id, TB.unad73carpeta, TB.unad73archivo, TB.unad73fecha, TB.unad73horaini, TB.unad73minini, TB.unad73horafin, TB.unad73minfin, TB.unad73detalle 
FROM unad73ftpenvio AS TB 
WHERE TB.unad73idftp='.$gen22id.' '.$sqladd.'';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1573" name="consulta_1573" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1573" name="titulos_1573" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf1573" name="paginaf1573" type="hidden" value="'.$pagina.'"/><input id="lppf1573" name="lppf1573" type="hidden" value="'.$lineastabla.'"/>');
			//break;
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
<td><b>'.$ETI['unad73consec'].'</b></td>
<td><b>'.$ETI['unad73carpeta'].'</b></td>
<td><b>'.$ETI['unad73archivo'].'</b></td>
<td><b>'.$ETI['unad73fecha'].'</b></td>
<td><b>'.$ETI['unad73horaini'].'</b></td>
<td><b>'.$ETI['unad73horafin'].'</b></td>
<td><b>'.$ETI['unad73detalle'].'</b></td>
<td align="right">
'.html_paginador('paginaf1573', $registros, $lineastabla, $pagina, 'paginarf1573()').'
'.html_lpp('lppf1573', $lineastabla, 'paginarf1573()').'
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
		$et_unad73fecha='';
		if ($filadet['unad73fecha']!='00/00/0000'){$et_unad73fecha=$filadet['unad73fecha'];}
		$et_unad73horaini=html_TablaHoraMin($filadet['unad73horaini'], $filadet['unad73minini']);
		$et_unad73horafin=html_TablaHoraMin($filadet['unad73horafin'], $filadet['unad73minfin']);
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1573('."'".$filadet['unad73id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad73consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad73carpeta']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad73archivo']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad73fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad73horaini.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad73horafin.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad73detalle']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1573_Clonar($unad73idftp, $unad73idftpPadre, $objdb){
	$sError='';
	$unad73consec=tabla_consecutivo('unad73ftpenvio', 'unad73consec', 'unad73idftp='.$unad73idftp.'', $objdb);
	if ($unad73consec==-1){$sError=$objdb->serror;}
	$unad73id=tabla_consecutivo('unad73ftpenvio', 'unad73id', '', $objdb);
	if ($unad73id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1573='unad73idftp, unad73consec, unad73id, unad73carpeta, unad73archivo, unad73fecha, unad73horaini, unad73minini, unad73horafin, unad73minfin, unad73detalle';
		$sValores1573='';
		$sql='SELECT * FROM unad73ftpenvio WHERE unad73idftp='.$unad73idftpPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1573!=''){$sValores1573=$sValores1573.', ';}
			$sValores1573=$sValores1573.'('.$unad73idftp.', '.$unad73consec.', '.$unad73id.', "'.$fila['unad73carpeta'].'", "'.$fila['unad73archivo'].'", "'.$fila['unad73fecha'].'", '.$fila['unad73horaini'].', '.$fila['unad73minini'].', '.$fila['unad73horafin'].', '.$fila['unad73minfin'].', "'.$fila['unad73detalle'].'")';
			$unad73consec++;
			$unad73id++;
			}
		if ($sValores1573!=''){
			$sql='INSERT INTO unad73ftpenvio('.$sCampos1573.') VALUES '.$sValores1573.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1573 Archivos enviados XAJAX 
function f1573_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require 'app.php';
		$objdb=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
		if ($APP->db_puerto!=''){$objdb->dbPuerto=$APP->db_puerto;}
		$objdb->xajax();
		list($sError)=f1573_db_Guardar($valores, $objdb);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1573_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1573detalle', 'innerHTML', $sdetalle);
		$objResponse->call('limpiaf1573');
		$objResponse->assign('alarma', 'innerHTML', 'item guardado');
		}else{
		$objResponse->assign('alarma', 'innerHTML', $sError);
		}
	return $objResponse;
	}
function f1573_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$unad73idftp=numeros_validar($params[2]);
		$unad73consec=numeros_validar($params[3]);
		if (($unad73idftp!='')&&($unad73consec!='')){$besta=true;}
		}else{
		$unad73id=$params[103];
		if ((int)$unad73id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require 'app.php';
		$objdb=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
		if ($APP->db_puerto!=''){$objdb->dbPuerto=$APP->db_puerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'unad73idftp='.$unad73idftp.' AND unad73consec='.$unad73consec.'';
			}else{
			$sqlcondi=$sqlcondi.'unad73id='.$unad73id.'';
			}
		$sql='SELECT * FROM unad73ftpenvio WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$unad73consec_nombre='';
		$html_unad73consec=html_oculto('unad73consec', $fila['unad73consec'], $unad73consec_nombre);
		$objResponse->assign('div_unad73consec', 'innerHTML', $html_unad73consec);
		$unad73id_nombre='';
		$html_unad73id=html_oculto('unad73id', $fila['unad73id'], $unad73id_nombre);
		$objResponse->assign('div_unad73id', 'innerHTML', $html_unad73id);
		$unad73carpeta_eti=$fila['unad73carpeta'];
		$html_unad73carpeta=html_oculto('unad73carpeta', $fila['unad73carpeta'], $unad73carpeta_eti);
		$objResponse->assign('div_unad73carpeta', 'innerHTML', $html_unad73carpeta);
		$unad73archivo_eti=$fila['unad73archivo'];
		$html_unad73archivo=html_oculto('unad73archivo', $fila['unad73archivo'], $unad73archivo_eti);
		$objResponse->assign('div_unad73archivo', 'innerHTML', $html_unad73archivo);
		$html_unad73fecha=html_oculto('unad73fecha', $fila['unad73fecha']);
		$objResponse->assign('div_unad73fecha', 'innerHTML', $html_unad73fecha);
		$html_unad73horaini=html_HoraMin('unad73horaini', $fila['unad73horaini'], 'unad73minini', $fila['unad73minini'], true);
		$objResponse->assign('div_unad73horaini', 'innerHTML', $html_unad73horaini);
		$html_unad73horafin=html_HoraMin('unad73horafin', $fila['unad73horafin'], 'unad73minfin', $fila['unad73minfin'], true);
		$objResponse->assign('div_unad73horafin', 'innerHTML', $html_unad73horafin);
		$objResponse->assign('unad73detalle', 'value', $fila['unad73detalle']);
		$objResponse->assign('alarma', 'innerHTML', '');
		$objResponse->call("verboton('belimina1573','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unad73consec', 'value', $unad73consec);
			}else{
			$objResponse->assign('alarma', 'innerHTML', 'No se encontro el registro de referencia:'.$unad73id);
			}
		}
	return $objResponse;
	}
function f1573_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objdb=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
	if ($APP->db_puerto!=''){$objdb->dbPuerto=$APP->db_puerto;}
	$objdb->xajax();
	$sError=f1573_db_Eliminar($params, $objdb);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1573_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1573detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1573');
		$sError='Item eliminado';
		}
	$objResponse->assign('alarma', 'innerHTML', $sError);
	return $objResponse;
	}
function f1573_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
	if ($APP->db_puerto!=''){$objdb->dbPuerto=$APP->db_puerto;}
	$objdb->xajax();
	$sDetalle=f1573_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1573detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1573_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_unad73consec='<input id="unad73consec" name="unad73consec" type="text" value="" onchange="revisaf1573()" class="cuatro"/>';
	$html_unad73id='<input id="unad73id" name="unad73id" type="hidden" value=""/>';
	$html_unad73carpeta=html_oculto('unad73carpeta', '');
	$html_unad73archivo=html_oculto('unad73archivo', '');
	$sunad73fecha=fecha_hoy();
	$html_unad73fecha=html_oculto('unad73fecha', $sunad73fecha, formato_fechalarga($sunad73fecha));
	$html_unad73horaini=html_HoraMin('unad73horaini', fecha_hora(), 'unad73minini', fecha_minuto(), true);
	$html_unad73horafin=html_HoraMin('unad73horafin', fecha_hora(), 'unad73minfin', fecha_minuto(), true);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad73consec','innerHTML', $html_unad73consec);
	$objResponse->assign('div_unad73id','innerHTML', $html_unad73id);
	$objResponse->assign('div_unad73carpeta','innerHTML', $html_unad73carpeta);
	$objResponse->assign('div_unad73archivo','innerHTML', $html_unad73archivo);
	$objResponse->assign('div_unad73fecha','innerHTML', $html_unad73fecha);
	$objResponse->assign('div_unad73horaini','innerHTML', $html_unad73horaini);
	$objResponse->assign('div_unad73horafin','innerHTML', $html_unad73horafin);
	return $objResponse;
	}
?>