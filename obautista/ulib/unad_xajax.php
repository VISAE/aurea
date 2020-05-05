<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Esta libreria es para funciones comunes xajax
--- Modelo Versión 2.18.4 domingo, 16 de julio de 2017 - Se hacen ajustes a UTF8
--- Modelo Versión 2.21.0 lunes, 20 de enero de 2020 - la importacion de usuarios se hace desde la db campus
*/
function login_ingresa($usuario, $clave){
	$sError='';
	require './app.php';
	include $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError=login_valida_usuario_v3($usuario,$clave,51,$objDB);
	$objResponse=new xajaxResponse();
	if ($sError=='pasa'){
		$sbloque41='(<a href="salir.php">Salir</a>)</div>';
		$objResponse->assign('login_div','innerHTML', $sbloque41);
		$objResponse->assign('login_alerta','innerHTML', '');
		}else{
		$objResponse->assign('login_alerta','innerHTML', $sError);
		}
	return $objResponse;
	}
	
//Abril 12 de 2015
function Moneda_Sumar($id,$value, $suma, $bResta=false, $bMayorQueCero=false){
	$value=numeros_validar($value, true);
	$suma=numeros_validar($suma, true);
	if ($value==''){$value=0;}
	if ($suma==''){$suma=0;}
	if ($bResta){
		$value=$value-$suma;
		}else{
		$value=$value+$suma;
		}
	if ($bMayorQueCero){
		if ($value<0){$value=0;}
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign($id, 'value',formato_numero($value,2));
	return $objResponse;
	}
//Mayro 29 de 2015
function Moneda_TotalizarOculto($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$value=numeros_validar($params[2], true);
	$suma=numeros_validar($params[3], true);
	if ($value==''){$value=0;}
	if ($suma==''){$suma=0;}
	switch($params[1]){
		case '+':
		$value=round($value+$suma,2);
		break;
		case '-':
		$value=round($value-$suma,2);
		break;
		case '*':
		case 'x':
		$value=round($value*$suma,2);
		break;
		case 'd':
		if ($suma==0){
			$value=0;
			}else{
			$value=round($value/$suma,2);
			}
		break;
		case '%':
		$value=round($value*$suma/100,2);
		break;
		}
	$sCampoDestino=$params[0];
	$html_data=html_oculto($sCampoDestino, $value, formato_moneda($value));
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_'.$sCampoDestino, 'innerHTML', $html_data);
	return $objResponse;
	}
	
function sesion_abandona(){
	return sesion_abandona_V2();
	}
function sesion_abandona_maidi($aDatos){
	return sesion_abandona_V2(false, true, $aDatos);
	}
function sesion_abandona_V2($bDebug=false, $bConCorreo=false, $aDatos=NULL){
	if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
	$ifalta=-1;
	$sMsg='';
	$sDebug='';
	if ($_SESSION['unad_id_tercero']!=0){
		require './app.php';
		//la funcion original esta en todas.php, se saca el minuto de la semana.
		$iavance=(date("W")*1440)+(date("H")*60)+date("i");
		if (isset($_SESSION['unad_tiempo_sesion'])==0){$_SESSION['unad_tiempo_sesion']=20;}
		if (isset($APP->tiempolimite)!=0){
			if ($APP->tiempolimite>$_SESSION['unad_tiempo_sesion']){$_SESSION['unad_tiempo_sesion']=$APP->tiempolimite;}
			}
		$itiempomax=$_SESSION['unad_tiempo_sesion'];
		$ibase=-$itiempomax;
		if (isset($_SESSION['u_ultimominuto'])!=0){$ibase=$_SESSION['u_ultimominuto'];}
		$ifalta=($ibase+$itiempomax)-$iavance;
		if ($ifalta>0){
			//si falta menos de un minuto mover la sesion... 
			//Esto no mueve variables de sesion, solo informa a la db que hasta esta hora se mantuvo el usuario.
			$sDebug=sesion_actualizar_v2(NULL, $bDebug);
			//marcar el tiempo que falta...
			if ($ifalta<4){
				$sScript=', <a href="javascript:mantener_sesion();" class="resalte"><span style="color:#FFFF99">o haga clic aqu&iacute; para mantenerse en el sistema</span></a>';
				$sMsg=$ifalta.' minutos para cerrar la sesi&oacute;n'.$sScript;
				}
			}
		}
	if ($ifalta<1){
		$objResponse=new xajaxResponse();
		$objResponse->redirect('salir.php');
		return $objResponse;
		}else{
		//$sMsg='a ver.. '.$ifalta;
		$objResponse=new xajaxResponse();
		if ($sMsg!=''){
			$objResponse->call("MensajeAlarmaV2('".$sMsg."', 2)");
			}
		$objResponse->assign('div_tiempo','innerHTML', 'tl='.$ifalta.''.$sDebug);
		return $objResponse;
		}
	}

// Marzo 6 de 2015 en lugar de sacar de la pagina de login.
function sesion_retomar($std,$sid,$spw){
	$sid=htmlspecialchars($sid);
	require './app.php';
	if (isset($_SESSION['u_doctercero'])==0){$_SESSION['u_doctercero']='';}
	if ($_SESSION['u_doctercero']==$sid){
		$respuesta='pasa';
		}else{
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		if (isset($APP->idsistema)==0){$APP->idsistema=10;}
		$respuesta=login_validar_v3($std, $sid, $spw, $APP->idsistema, $objDB);
		}
	$objResponse = new xajaxResponse();
	if ($respuesta=='pasa'){
		$objResponse->call('expandesector(1)');
		$objResponse->assign('ensesion','value', '1');
		$objResponse->assign('alarma','innerHTML', '');
		$objResponse->assign('txtclave','value', '');
		$objResponse->call('mantener_sesion()');
		}else{
		$objResponse->assign('alarma','innerHTML', $respuesta);
		}
	return $objResponse;
	}
function sesion_retomarV4($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$std=$datos[1];
	$sid=htmlspecialchars($datos[2]);
	$spw=$datos[3];
	$idSistema=$datos[4];
	require './app.php';
	if (isset($_SESSION['u_doctercero'])==0){$_SESSION['u_doctercero']='';}
	if ($_SESSION['u_doctercero']==$sid){
		$respuesta='pasa';
		}else{
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$respuesta=login_validar_v3($std, $sid, $spw, $idSistema, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($respuesta=='pasa'){
		$_SESSION['u_ultimominuto']=iminutoavance();
		$objResponse->assign('ensesion','value', '1');
		$objResponse->assign('txtclave','value', '');
		$objResponse->assign('div_tiempo', 'innerHTML', '');
		$objResponse->call('expandesector(1)');
		$objResponse->call('MensajeAlarmaV2("", 0)');
		}else{
		$objResponse->call('MensajeAlarmaV2("'.$respuesta.'", 0)');
		}
	return $objResponse;
	}
// Enero 31 de 2015 
function sesion_mantener(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$objResponse = new xajaxResponse();
	$objResponse->assign('alarma', 'innerHTML', '');
	$objResponse->assign('div_tiempo', 'innerHTML', '');
	return $objResponse;
	}
function sesion_mantenerV4(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$objResponse=new xajaxResponse();
	$objResponse->call('MensajeAlarmaV2("", 0)');
	$objResponse->assign('div_tiempo', 'innerHTML', '');
	return $objResponse;
	}
// Junio 8 de 2015 se delega a la sys11_Mostrar_v2
function unad11_mostrar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if ($tipodoc!='CE'){$doc=solonumeros($doc);}
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$tipodoc=$params[0];
	$doc=$params[1];
	$objid=$params[2];
	$sdiv=$params[3];
	$respuesta='';
	$id=0;
	if ($doc!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($respuesta,$id)=tabla_terceros_info($tipodoc, $doc, $objDB);
		if ($respuesta==''){
			//IMPORTACION AUTOMATICA DE TERCEROS - MARZO 19 DE 2014
			// Ver si esta en la tabla mdl_user
			// -- Junion 10 de 2014 - Se agrega el campo unad11idncontents
			if ($tipodoc=='CC'){
				unad11_importar_V2($doc, '', $objDB);
				list($respuesta,$id)=tabla_terceros_info($tipodoc, $doc, $objDB);
				}
			//-- FIN DE LA IMPORTACION AUTOMATICA
			if ($respuesta==''){
				$respuesta='<font class="rojo">{'.$tipodoc.' '.$doc.' No encontrado}</font>';
				}
			}
		$objDB->CerrarConexion();
		}
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid.'_doc', 'value', $doc);
	$objResponse->assign($objid, 'value', $id);
	return $objResponse;
	}
function unad11_importar_V2($sDoc, $sUserCampus, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sRutaApp='./app.php';
	if (!file_exists($sRutaApp)){
		$sDirBase=__DIR__.'/';
		$sRutaApp=$sDirBase.'app.php';
		}
	require $sRutaApp;
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	$idRolUnad=0;
	$bDBExterna=false;
	$sSQL='SET CHARACTER SET utf8';
	$tabla=$objDB->ejecutasql($sSQL);
	switch($idEntidad){
		case 1: //UNAD Florida.
		$sModelo='M';
		if (isset($APP->dbmodelocampus)!=0){
			if ($APP->dbmodelocampus=='O'){$sModelo='O';}
			if ($APP->dbmodelocampus=='P'){$sModelo='P';}
			}
		$objDBOrigen=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus, $sModelo);
		if ($APP->dbpuertocampus!=''){$objDBOrigen->dbPuerto=$APP->dbpuertocampus;}
		$bDBExterna=true;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Conectando con base de datos externa ['.$APP->dbnamecampus.']<br>';}
		$sWhere='susername="'.$sUserCampus.'"';
		$sSQL='SELECT nid AS id, susername AS username, nid AS idnumber, sname AS firstname, snlast AS lastname, semail AS email, spassword AS password, sphone AS phone1 FROM users WHERE '.$sWhere;
		break;
		default: //UNAD Colombia.
		//$objDBOrigen=$objDB;
		$objDBOrigen=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus);
		if ($APP->dbpuertocampus!=''){$objDBOrigen->dbPuerto=$APP->dbpuertocampus;}
		$bDBExterna=true;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Conectando con base de datos externa ['.$APP->dbnamecampus.']<br>';}
		if ($sDoc!=''){
			$sWhere='idnumber="'.$sDoc.'"';
			}else{
			$sWhere='username="'.$sUserCampus.'"';
			}
		$sSQL='SELECT id, username, idnumber, firstname, lastname, email, password, phone1 FROM mdl_user WHERE '.$sWhere;
		break;
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de origen: '.$sSQL.'<br>';}
	$tabla=$objDBOrigen->ejecutasql($sSQL);
	if ($objDBOrigen->nf($tabla)>0){
		$fila=$objDBOrigen->sf($tabla);
		$idUsuario=$fila['username'];
		$sDoc=$fila['idnumber'];
		$sSQLConsulta=$sSQL;
		$sSQL='SELECT unad11id, unad11doc FROM unad11terceros WHERE unad11usuario="'.$idUsuario.'"';
		$tabla11=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11)>0){
			$fila11=$objDB->sf($tabla11);
			$sError='El usuario '.$idUsuario.' ya existe.';
			if ($fila11['unad11doc']!=$sDoc){
				$sWhere='Se cambia el documento  de '.$fila11['unad11doc'].' a '.$sDoc.' con base en '.$sSQLConsulta.' desde '.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
				// Se le cambia el documento.
				$sSQL='UPDATE unad11terceros SET unad11doc="'.$sDoc.'" WHERE unad11id='.$fila11['unad11id'];
				$tabla11=$objDB->ejecutasql($sSQL);
				seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $fila11['unad11id'], $sWhere, $objDB);
				}
			}
		}else{
		$sError='No encontrado.';
		}
	if ($sError==''){
		$scampos='unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, 
unad11dv, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, 
unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, 
unad11direccion, unad11telefono, unad11correo, unad11sitioweb, unad11nacionalidad, 
unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11clave, 
unad11idmoodle, unad11idncontents, unad11iddatateca, unad11idcampus, unad11claveapps, 
unad11fechacrea, unad11mincrea, unad11rolunad, unad11fechaclave';
		$nombres=substr($fila['firstname'],0,30);
		$apellidos=substr($fila['lastname'],0,30);
		$razonsocial=substr($fila['firstname'].' '.$fila['lastname'],0,100);
		$correo=substr($fila['email'],0,50);
		$sSQL='SELECT MAX(unad11id) FROM unad11terceros';
		$tablaid=$objDB->ejecutasql($sSQL);
		$filaid=$objDB->sf($tablaid);
		$id11=$filaid[0]+1;					
		$unad11fechacrea=fecha_DiaMod();
		$unad11mincrea=fecha_MinutoMod();
		$sPais='057';
		if ($idEntidad==1){$sPais='001';}
		$svalores='"CC", "'.$fila['idnumber'].'", '.$id11.', "'.$sPais.'", "'.trim($fila['username']).'", 
"", "'.$nombres.'", "", "'.$apellidos.'", "", 
"", "00/00/0000", "", "", "'.$razonsocial.'", 
".", "'.$fila['phone1'].'", "'.$correo.'", "", "'.$sPais.'", 
"", "", "", "", "'.$fila['password'].'", 
'.$fila['id'].', '.$fila['id'].', '.$fila['id'].', '.$fila['id'].', "", 
'.$unad11fechacrea.', '.$unad11mincrea.', '.$idRolUnad.', '.$unad11fechacrea.'';
		$sSQL='INSERT INTO unad11terceros ('.$scampos.') VALUES ('.$svalores.');';
		$res=$objDB->ejecutasql($sSQL);
		if ($res==false){
			//Fallo la insersion, posiblemente los nombres vienen mal...
			$nombres=cadena_letrasynumeros(substr($fila['firstname'],0,30));
			$apellidos=cadena_letrasynumeros(substr($fila['lastname'],0,30));
			$razonsocial=$nombres.' '.$apellidos;
			$svalores='"CC", "'.$fila['idnumber'].'", '.$id11.', "'.$sPais.'", "'.trim($fila['username']).'", 
"", "'.$nombres.'", "", "'.$apellidos.'", "", 
"", "00/00/0000", "", "", "'.$razonsocial.'", 
".", "'.$fila['phone1'].'", "'.$correo.'", "", "'.$sPais.'", 
"", "", "", "", "'.$fila['password'].'", 
'.$fila['id'].', '.$fila['id'].', '.$fila['id'].', '.$fila['id'].', "", 
'.$unad11fechacrea.', '.$unad11mincrea.', '.$idRolUnad.', '.$unad11fechacrea.'';
			$sSQL='INSERT INTO unad11terceros ('.$scampos.') VALUES ('.$svalores.');';
			$res=$objDB->ejecutasql($sSQL);
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de insersion: '.$sSQL.'<br>';}
		}
	if ($bDBExterna){
		$objDBOrigen->CerrarConexion();
		}
	return array($sError, $sDebug);
	}
function unad11_Mostrar_v2($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if ($tipodoc!='CE'){$doc=solonumeros($doc);}
	$respuesta='';
	$id=0;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$tipodoc=$params[0];
	$doc=trim($params[1]);
	$objid=$params[2];
	$sdiv=$params[3];
	$bXajax=true;
	if (isset($params[4])==0){$params[4]='';}
	if (isset($params[5])==0){$params[5]='';}
	if ($doc!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($respuesta, $id, $tipodoc, $doc)=html_tercero($tipodoc, $doc, 0, 1, $objDB);
		if ($respuesta==''){
			//IMPORTACION AUTOMATICA DE TERCEROS - MARZO 19 DE 2014
			// Ver si esta en la tabla mdl_user
			if ($tipodoc=='CC'){
				unad11_importar_V2($doc, '', $objDB);
				list($respuesta,$id)=tabla_terceros_info($tipodoc, $doc, $objDB);
				}
			//-- FIN DE LA IMPORTACION AUTOMATICA
			if ($respuesta==''){
				$respuesta='<font class="rojo">{'.$tipodoc.' '.$doc.' No encontrado}</font>';
				}
			}
		}else{
		$respuesta='&nbsp;';
		}
	$objResponse = new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', utf8_encode($respuesta));
	$objResponse->assign($objid.'_doc', 'value', $doc);
	$objResponse->assign($objid, 'value', $id);
	if ($id==0){
		if ($params[5]!=''){$objResponse->call($params[5]);}
		}else{
		if ($params[4]!=''){$objResponse->call($params[4]);}
		}
	return $objResponse;
	}
function unad11_TraerXid($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$idtercero=$params[0];
	$tipodoc='CC';
	$doc='';
	$objid=$params[1];
	// El parametro 4 es la funciona que se llamará si esta el tercero y la 5 la se llamara en caso de que no.
	if (isset($params[4])==0){$params[4]='';}
	if (isset($params[5])==0){$params[5]='';}
	if (isset($params[6])==0){$params[6]=0;}
	if ((int)$idtercero!=0){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $tipodoc, $doc, $respuesta)=tabla_terceros_traer($idtercero, '', '', $objDB);
		if ($respuesta==''){
			$idtercero=0;
			$tipodoc=$APP->tipo_doc;
			$respuesta='<font class="rojo">{Ref: '.$idtercero.' No encontrado}</font>';
			}
		}
	$objResponse = new xajaxResponse();
	$objResponse->assign($objid, 'value', $idtercero);
	$objResponse->assign($objid.'_td', 'value', $tipodoc);
	$objResponse->assign($objid.'_doc', 'value', $doc);
	$objResponse->assign('div_'.$objid, 'innerHTML', $respuesta);
	if ($id==0){
		if ($params[5]!=''){$objResponse->call($params[5]);}
		}else{
		if ($params[4]!=''){$objResponse->call($params[4]);}
		}
	return $objResponse;
	}
function unad11_ActualizarOculto($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$idtercero=$params[0];
	$tipodoc='CC';
	$doc='';
	$objid=$params[1];
	if (isset($params[6])==0){$params[6]=0;}
	if ((int)$idtercero!=0){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $tipodoc, $doc, $respuesta)=tabla_terceros_traer($idtercero, '', '', $objDB);
		if ($respuesta==''){
			$idtercero=0;
			$tipodoc=$APP->tipo_doc;
			$respuesta='<font class="rojo">{Ref: '.$idtercero.' No encontrado}</font>';
			}
		}
	$sRes=html_oculto($objid.'_td',$tipodoc).' '.html_oculto($doc.'_doc',$doc);
	$sRes='<label class="Label350">'.$sRes.'</label>';
	$objResponse=new xajaxResponse();
	$objResponse->assign($objid, 'value', $idtercero);
	$objResponse->assign('div_'.$objid.'_llaves', 'innerHTML', $sRes);
	$objResponse->assign('div_'.$objid, 'innerHTML', $respuesta);
	return $objResponse;
	}
function usuario_OpcionGuardar($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	if (isset($datos[2])==0){$datos[2]=0;}
	if (isset($datos[3])==0){$datos[3]='';}
	if (isset($datos[4])==0){$datos[4]='';}
	$cod_modulo=$datos[0];
	$cod_opcion=$datos[1];
	$ivr=$datos[2];
	$svr=$datos[3];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sSQLinsert='';
	$sSQL='SELECT unad59svalor, unad59ivalor FROM unad59params WHERE unad59idtercero='.$_SESSION['unad_id_tercero'].' AND unad59idmodulo='.$cod_modulo.' AND unad59idpreferencia='.$cod_opcion.'';
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)>0){
		$fila=$objDB->sf($result);
		if (!(($fila['unad59svalor']==$svr)&&($fila['unad59ivalor']==$ivr))){
			$sSQLinsert='UPDATE unad59params SET unad59svalor="'.$svr.'", unad59ivalor='.$ivr.' WHERE unad59idtercero='.$_SESSION['unad_id_tercero'].' AND unad59idmodulo='.$cod_modulo.' AND unad59idpreferencia='.$cod_opcion.';';
			}
		}else{
		$sSQLinsert='INSERT INTO unad59params (unad59idtercero, unad59idmodulo, unad59idpreferencia, unad59svalor, unad59ivalor) VALUES ('.$_SESSION['unad_id_tercero'].', '.$cod_modulo.', '.$cod_opcion.', "'.$svr.'", '.$ivr.');';
		}
	if ($sSQLinsert!=''){
		$hhhh=$objDB->ejecutasql($sSQLinsert);
		}
	if ($datos[4]!=''){
		//Hacer el llamado a la funcion javascript
		$objResponse=new xajaxResponse();
		$objResponse->call($datos[4]);
		return $objResponse;
		}
	}
?>