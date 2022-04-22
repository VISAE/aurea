<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 martes, 15 de marzo de 2016
--- 1926 
*/
function html_combo_even21depto($objdb, $valor, $vreven21pais){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad19codpais="'.$vreven21pais.'"';
	$res=html_combo('even21depto', 'unad19codigo', 'unad19nombre', 'unad19depto', $scondi, 'unad19nombre', $valor, $objdb, 'carga_combo_even21ciudad()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_even21ciudad($objdb, $valor, $vreven21depto){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad20coddepto="'.$vreven21depto.'"';
	$res=html_combo('even21ciudad', 'unad20codigo', 'unad20nombre', 'unad20ciudad', $scondi, 'unad20nombre', $valor, $objdb, 'guardar_even21ciudad()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_even21idcead($objdb, $valor, $vreven21idzona){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='unad24idzona="'.$vreven21idzona.'"';
	$res=html_combo('even21idcead', 'unad24id', 'unad24nombre', 'unad24sede', $scondi, 'unad24nombre', $valor, $objdb, 'guardar_even21idcead()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function TraerBusqueda_db_even21idcurso($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta));
	}
function TraerBusqueda_even21idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $respuesta)=TraerBusqueda_db_even21idcurso($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	return $objResponse;
	}
function Cargar_even21depto($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	//Guardar la el pais
	$sql='UPDATE even21encuestaaplica SET even21pais="'.$params[0].'", even21depto="", even21ciudad="" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	$html_even21depto=html_combo_even21depto($objdb, '', $params[0]);
	$html_even21ciudad=html_combo_even21ciudad($objdb, '', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even21depto', 'innerHTML', $html_even21depto);
	$objResponse->assign('div_even21ciudad', 'innerHTML', $html_even21ciudad);
	return $objResponse;
	}
function Cargar_even21ciudad($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	//Guardar la el pais
	$sql='UPDATE even21encuestaaplica SET even21depto="'.$params[0].'", even21ciudad="" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	$html_even21ciudad=html_combo_even21ciudad($objdb, '', $params[0]);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even21ciudad', 'innerHTML', $html_even21ciudad);
	return $objResponse;
	}
function f1621_Guardar_even21ciudad($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='UPDATE even21encuestaaplica SET even21ciudad="'.$params[0].'" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	}
function f1621_Guardar_even21fechanace($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sFecha='00/00/0000';
	$iAgnos=0;
	if (fecha_esvalida($params[0])){
		$sFecha=$params[0];
		list($iAgnos, $iMedida)=fecha_edad($sFecha);
		}
	$sql='UPDATE even21encuestaaplica SET even21fechanace="'.$sFecha.'", even21agnos='.$iAgnos.' WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	}
function Cargar_even21idcead($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='UPDATE even21encuestaaplica SET even21idzona="'.$params[0].'" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	$html_even21idcead=html_combo_even21idcead($objdb, '', $params[0]);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even21idcead', 'innerHTML', $html_even21idcead);
	return $objResponse;
	}
function f1621_Guardar_even21idcead($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='UPDATE even21encuestaaplica SET even21idcead="'.$params[0].'" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	}
function f1621_Guardar_even21perfil($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='UPDATE even21encuestaaplica SET even21perfil="'.$params[0].'" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	}
function f1621_Guardar_even21idprograma($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sql='UPDATE even21encuestaaplica SET even21idprograma="'.$params[0].'" WHERE even21id='.$params[1].'';
	$tabla=$objdb->ejecutasql($sql);
	}
function f1926_ExisteDato(){}
function f1926_TablaDetalle($params, $objdb){}
function f1926_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f1926_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1926detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
// -- Espacio para incluir funciones xajax personalizadas.
function f1926_ArmarTabla22($idEncuesta, $even21id, $objdb, $iIntento=1){
	$bRes=false;
	if ($iIntento<4){
		//En caso de que se hagan mas de 3 intentos hay un error grave...';
		$bRes=true;
		$campos22='INSERT INTO even22encuestarpta (even22idaplica, even22idpregunta, even22id, even22tiporespuesta, even22opcional, even22irespuesta, even22nota, even22relevante, even22rpta0, even22rpta1, even22rpta2, even22rpta3, even22rpta4, even22rpta5, even22rpta6, even22rpta7, even22rpta8, even22rpta9, even22divergente, even22idpregcondiciona, even22valorcondiciona) VALUES ';
		$svalores22='';
		$even22id=tabla_consecutivo('even22encuestarpta', 'even22id', '', $objdb);
		//Traer las respuestas.
		$sql18='SELECT even18id, even18tiporespuesta, even18opcional, even18divergente, even18idpregcondiciona, even18valorcondiciona FROM even18encuestapregunta WHERE even18idencuesta='.$idEncuesta.'';
		$tabla18=$objdb->ejecutasql($sql18);
		while ($fila18=$objdb->sf($tabla18)){
			if ($svalores22!=''){$svalores22=$svalores22.', ';}
			$svalores22=$svalores22.'('.$even21id.', '.$fila18['even18id'].', '.$even22id.', '.$fila18['even18tiporespuesta'].', "'.$fila18['even18opcional'].'", -1, "", "N", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "'.$fila18['even18divergente'].'", '.$fila18['even18idpregcondiciona'].', '.$fila18['even18valorcondiciona'].')';
			$even22id++;
			}
		if ($svalores22!=''){
			$tabla=$objdb->ejecutasql($campos22.$svalores22);
			if ($tabla==false){
				$iIntento++;
				$bRes=f1926_ArmarTabla22($idEncuesta, $even21id, $objdb, $iIntento);
				}
			}
		}
	return $bRes;
	}
function f1926_CargarEncuestas($idTercero, $objdb){
	//Ver que encuestas estas activas.
	$even21pais='';
	$even21depto='';
	$even21ciudad='';
	$even21fechanace='';
	$even21agnos=0;
	$even21perfil=0;
	$even21idzona=0;
	$even21idcead=0;
	$even21idprograma=0;
	$sObligatoria='S';
	$sql='SELECT even16id, even16idproceso, even16porperiodo, even16porcurso, even16porbloqueo, even16fechainicio, even16fechafin, even16caracter, even16idbloqueo FROM even16encuesta WHERE even16publicada="S"';
	$tabla16=$objdb->ejecutasql($sql);
	while ($fila16=$objdb->sf($tabla16)){
		//Si aplica para la encuesta insertarla.
		$bBloqueoInsertar=false;
		$bBloqueoQuitar=false;
		$idBloqueo=0;
		$idEncuesta=$fila16['even16id'];
		$sObligatoria='S';
		if ($fila16['even16caracter']==0){$sObligatoria='N';}
		//Caracter 3 es una muestra.
		$bPasa=true;
		$iCiclos=0;
		$aCiclo=array();
		switch($fila16['even16idproceso']){
			case 0:
			//Encuestas generales.
			//Ver si es por bloqueo.
			if ($fila16['even16porbloqueo']=='S'){
				$idBloqueo=$fila16['even16idbloqueo'];
				//Ver si el bloqueo es por que existe o porque no existe.
				$bPasa=false;
				$sql='SELECT unad63tiporesultado, unad63origendatos FROM unad63bloqueo WHERE unad63id='.$idBloqueo.'';
				$tabla63=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla63)>0){
					$fila63=$objdb->sf($tabla63);
					if ($fila63['unad63origendatos']==1){
						$sql='SELECT unad65id FROM unad65bloqueados WHERE unad65idbloqueo='.$idBloqueo.' AND unad65idtercero='.$idTercero.'';
						$tabla65=$objdb->ejecutasql($sql);
						if ($objdb->nf($tabla65)>0){
							//ya esta...
							if ($fila63['unad63tiporesultado']==1){
								$bPasa=true;
								$bBloqueoQuitar=true;
								}
							}else{
							if ($fila63['unad63tiporesultado']==0){
								$bPasa=true;
								$bBloqueoInsertar=true;
								}
							}
						}else{
						//@@ tiene otro origen de datos.
						}
					}
				if ($bPasa){
					$iCiclos=1;
					$aCiclo[1]['idperaca']=0;
					$aCiclo[1]['idcurso']=0;
					$aCiclo[1]['idbloqueo']=$idBloqueo;
					}
				}else{
				//No es por bloqueo... ver si es por peraca y por curso...
				if ($fila16['even16porperiodo']=='S'){
					$sql='SELECT even24idperaca FROM even24encuestaperaca WHERE even24idencuesta='.$idEncuesta.' AND STR_TO_DATE(even24fechainicial, "%d/%m/%Y")<=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") AND STR_TO_DATE(even24fechafinal, "%d/%m/%Y")>=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y")';
					$tabla24=$objdb->ejecutasql($sql);
					while ($fila24=$objdb->sf($tabla24)){
						$idPeraca=$fila24['even24idperaca'];
						if ($fila16['even16porcurso']=='S'){
							//Empieza por curso peraca..
							$sql='SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta='.$idEncuesta.' AND even25activo="S"';
							$tabla25=$objdb->ejecutasql($sql);
							while ($fila25=$objdb->sf($tabla25)){
								$bTieneCurso=false;
								$sql='SELECT unad47numaula FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47idcurso='.$fila25['even25idcurso'].' AND unad47peraca='.$idPeraca.' AND unad47activo="S"';
								$result=$objdb->ejecutasql($sql);
								if ($objdb->nf($result)>0){$bTieneCurso=true;}
								if ($bTieneCurso){
									$iCiclos++;
									$aCiclo[$iCiclos]['idperaca']=$idPeraca;
									$aCiclo[$iCiclos]['idcurso']=$fila25['even25idcurso'];
									$aCiclo[$iCiclos]['idbloqueo']=0;
									}
								}
							//Termina si es por curso peraca
							}else{
							$bTieneCurso=false;
							$sql='SELECT unad47numaula FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47peraca='.$idPeraca.' AND unad47activo="S" LIMIT 0,1';
							$result=$objdb->ejecutasql($sql);
							if ($objdb->nf($result)>0){$bTieneCurso=true;}
							if ($bTieneCurso){
								$iCiclos++;
								$aCiclo[$iCiclos]['idperaca']=$idPeraca;
								$aCiclo[$iCiclos]['idcurso']=0;
								$aCiclo[$iCiclos]['idbloqueo']=0;
								}
							}
						}
					}else{
					//por curso sin peraca...
					if ($fila16['even16porcurso']=='S'){
						$sql='SELECT even25idcurso FROM even25encuestacurso WHERE even25idencuesta='.$idEncuesta.' AND even25activo="S"';
						$tabla25=$objdb->ejecutasql($sql);
						//echo 'Es por curso y sin peraca '.$sql.'<br>';
						while ($fila25=$objdb->sf($tabla25)){
							$bTieneCurso=false;
							$sql='SELECT unad47numaula FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47idcurso='.$fila25['even25idcurso'].' AND unad47activo="S"';
							$result=$objdb->ejecutasql($sql);
							if ($objdb->nf($result)>0){$bTieneCurso=true;}
							if ($bTieneCurso){
								$iCiclos++;
								$aCiclo[$iCiclos]['idperaca']=0;
								$aCiclo[$iCiclos]['idcurso']=$fila25['even25idcurso'];
								$aCiclo[$iCiclos]['idbloqueo']=0;
								}
							}
						}
					//Fin de si es por curso.
					}
				//Sin de si es o no por periodo.
				}
			break;
			default:
			$bPasa=false;
			break;
			}
		if ($bPasa){
			$even21id=tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objdb);
			if ($even21pais==''){
				//Traemos la información que tengamos del tercero
				$sql='SELECT * FROM even21encuestaaplica WHERE even21idtercero='.$idTercero.' ORDER BY even21terminada DESC, even21id DESC LIMIT 0,1';
				$tabla=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla)>0){
					$fila=$objdb->sf($tabla);
					$even21pais=$fila['even21pais'];
					$even21depto=$fila['even21depto'];
					$even21ciudad=$fila['even21ciudad'];
					$even21fechanace=$fila['even21fechanace'];
					$even21agnos=$fila['even21agnos'];
					$even21perfil=$fila['even21perfil'];
					$even21idzona=$fila['even21idzona'];
					$even21idcead=$fila['even21idcead'];
					$even21idprograma=$fila['even21idprograma'];
					}else{
					$even21pais='057';
					}
				}
			//Insertar los ciclos solicitados.
			for ($k=1;$k<=$iCiclos;$k++){
				$idPeraca=$aCiclo[$k]['idperaca'];
				$idCurso=$aCiclo[$k]['idcurso'];
				$idBloqueo=$aCiclo[$k]['idbloqueo'];
				//Ver que no este antes de insertarla.
				$sql='SELECT even21id FROM even21encuestaaplica WHERE even21idencuesta='.$idEncuesta.' AND even21idtercero='.$idTercero.' AND even21idperaca='.$idPeraca.' AND even21idcurso='.$idCurso.' AND even21idbloquedo='.$idBloqueo.'';
				$tabla=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla)==0){
					$sql21='INSERT INTO even21encuestaaplica (even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, even21id, even21fechapresenta, even21terminada, even21pais, even21depto, even21ciudad, even21fechanace, even21agnos, even21perfil, even21idzona, even21idcead, even21idprograma, even21obligatoria) VALUES ('.$idEncuesta.', '.$idTercero.', '.$idPeraca.', '.$idCurso.', '.$idBloqueo.', '.$even21id.', "00/00/0000", "N", "'.$even21pais.'", "'.$even21depto.'", "'.$even21ciudad.'", "'.$even21fechanace.'", '.$even21agnos.', '.$even21perfil.', '.$even21idzona.', '.$even21idcead.', '.$even21idprograma.', "'.$sObligatoria.'")';
					$tabla=$objdb->ejecutasql($sql21);
					f1926_ArmarTabla22($idEncuesta, $even21id, $objdb);
					$even21id++;
					}
				//Termina el ciclo.
				}
			}
		}
	}
function f1926_Siguiente($idTercero, $objdb){
	$res=0;
	$idAplica=0;
	$sql='SELECT even21id, even21idencuesta FROM even21encuestaaplica WHERE even21idtercero='.$idTercero.' AND even21terminada="N" ORDER BY even21obligatoria DESC, even21id';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$res=$fila['even21idencuesta'];
		$idAplica=$fila['even21id'];
		}
	return array($res, $idAplica);
	}
function f1926_Html_Respuestas($id21, $objdb, $iPregCondi=0, $iOpcionCondi=0){
	$res='';
	$bPrincipal=true;
	$sCondi=' AND TB.even22idpregcondiciona=0';
	if ($iPregCondi!=0){
		$bPrincipal=false;
		$sCondi=' AND TB.even22idpregcondiciona='.$iPregCondi.' AND TB.even22valorcondiciona='.$iOpcionCondi.'';
		}
	$sql='SELECT TB.even22id, TB.even22idpregunta, T18.even18pregunta, TB.even22tiporespuesta, TB.even22opcional, TB.even22irespuesta, T18.even18concomentario, TB.even22nota, TB.even22divergente, TB.even22rpta1, TB.even22rpta2, TB.even22rpta3, TB.even22rpta4, TB.even22rpta5, TB.even22rpta6, TB.even22rpta7, TB.even22rpta8, TB.even22rpta9
FROM even22encuestarpta AS TB, even18encuestapregunta AS T18
WHERE TB.even22idaplica='.$id21.$sCondi.' AND TB.even22idpregunta=T18.even18id
ORDER BY T18.even18orden, T18.even18consec';
	$tabla=$objdb->ejecutasql($sql);
	if ($iPregCondi==0){
		//(Abril 10 de 2016) - Verificar que no sea una encuesta vacia
		if ($objdb->nf($tabla)==0){
			$sql21=$sql;
			$sql='SELECT even21idencuesta FROM even21encuestaaplica WHERE even21id='.$id21;
			$tabla21=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla21)>0){
				$fila21=$objdb->sf($tabla21);
				$idEncuesta=$fila21['even21idencuesta'];
				f1926_ArmarTabla22($idEncuesta, $id21, $objdb);
				$tabla=$objdb->ejecutasql($sql21);
				}
			}
		}
	if ($bPrincipal){
		$res='
<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
		}
	$tlinea=0;
	while ($fila=$objdb->sf($tabla)){
		$sClass='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$res=$res.'
<tr'.$sClass.'>
<td>'.cadena_notildes($fila['even18pregunta']).'</td>
<td>';
		$iDiverge=0;
		if ($fila['even22divergente']=='S'){$iDiverge=1;}
		switch($fila['even22tiporespuesta']){
			case 0:
				$sValor='';
				if ($fila['even22irespuesta']==0){$sValor='N';}
				if ($fila['even22irespuesta']==1){$sValor='S';}
				//$res=$res.html_sino('preg_'.$fila['even18id'], $sValor, true, 'Seleccione');
				$res=$res.html_Radio('preg_'.$fila['even22id'], $sValor, 'S|N', 'Si|No', 'selrpta('.$fila['even22id'].',this.value, 0, '.$iDiverge.')');
			break;
			case 1: //Múltiple Opción
				$sValor=$fila['even22irespuesta'];
				$sValores='';
				$sEtiquetas='';
				$sql='SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta='.$fila['even22idpregunta'].' ORDER BY even29consec';
				$tabla29=$objdb->ejecutasql($sql);
				$iFila=0;
				while ($fila29=$objdb->sf($tabla29)){
					$sSepara='|';
					if ($iFila==0){$sSepara='';}
					$sValores=$sValores.$sSepara.$fila29['even29consec'];
					$sEtiquetas=$sEtiquetas.$sSepara.cadena_notildes($fila29['even29etiqueta']);
					$iFila++;
					}
				$res=$res.html_Radio('preg_'.$fila['even22id'], $sValor, $sValores, $sEtiquetas, 'selrpta('.$fila['even22id'].',this.value, 1, '.$iDiverge.')');
			break;
			case 2: //Selección Múltiple
				$sValor=$fila['even22irespuesta'];
				$sValores='';
				$sEtiquetas='';
				$sql='SELECT even29consec, even29etiqueta, even29detalle FROM even29encpregresp WHERE even29idpregunta='.$fila['even22idpregunta'].' ORDER BY even29consec';
				$tabla29=$objdb->ejecutasql($sql);
				while ($fila29=$objdb->sf($tabla29)){
					$bMarcado=false;
					if ($fila['even22rpta'.$fila29['even29consec']]!=0){$bMarcado=true;}
					$res=$res.html_check('preg_'.$fila['even22id'].'_'.$fila29['even29consec'], cadena_notildes($fila29['even29etiqueta']), $fila29['even29consec'], $bMarcado, 'marcaropcion('.$fila['even22id'].','.$fila29['even29consec'].',this.checked)');
					}
			break;
			case 3: //Respuesta abierta
			$res=$res.'<input id="rpta_'.$fila['even22id'].'" name="rpta_'.$fila['even22id'].'" type="text" value="'.$fila['even22nota'].'" onchange="rptabierta('.$fila['even22id'].', this.value)" style="width:98%;" placeholder="Ingrese aqu&iacute; su respuesta"/>';
			break;
			}
		$res=$res.'</td>
</tr>';
		if ($iDiverge){
			$res=$res.f1926_Html_Respuestas($id21, $objdb, $fila['even22idpregunta'], $fila['even22irespuesta']);
			}
		}
	if ($bPrincipal){
		$res=$res.'
</table>';
		}
	return $res;
	}
function f1926_CargarCuerpo($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$html_cuerpo=f1926_Html_Respuestas($params[0], $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_respuestas', 'innerHTML', $html_cuerpo);
	return $objResponse;
	}
function f1926_GuardaRpta($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$id22=numeros_validar($params[1]);
	$sRpta=htmlspecialchars(trim($params[2]));
	$itipo=numeros_validar($params[3]);
	$iDiverge=numeros_validar($params[4]);
	$id21=numeros_validar($params[5]);
	if ($id22==''){$id22=0;}
	if ($id22>0){
		$ivalor=-1;
		switch($itipo){
			case 0:
			if ($sRpta=='N'){$ivalor=0;}
			if ($sRpta=='S'){$ivalor=1;}
			break;
			case 1:
			$ivalor=$sRpta;
			break;
			}
		$sql='UPDATE even22encuestarpta SET even22irespuesta='.$ivalor.' WHERE even22id='.$id22;
		$tabla=$objdb->ejecutasql($sql);
		if ($iDiverge==1){
			//Marcar todas las repuestas divergentes como no resueltas.
			$sql='UPDATE even22encuestarpta SET even22irespuesta=-1, even22rpta1=0, even22rpta2=0, even22rpta3=0, even22rpta4=0, even22rpta5=0, even22rpta6=0, even22rpta7=0, even22rpta8=0, even22rpta9=0 WHERE even22idaplica='.$id21.' AND even22idpregcondiciona='.$id22.' AND even22valorcondiciona<>'.$ivalor.'';
			$tabla=$objdb->ejecutasql($sql);
			//Mostrar el cuerpo nuevamente.
			$html_cuerpo=f1926_Html_Respuestas($id21, $objdb);
			$objResponse=new xajaxResponse();
			$objResponse->assign('div_respuestas', 'innerHTML', $html_cuerpo);
			//$objResponse->assign('alarma', 'innerHTML', $sql);
			return $objResponse;
			}
		}
	}
function f1926_MarcarOpcion($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$id22=numeros_validar($params[1]);
	$iConsec=numeros_validar($params[2]);
	$iValor=numeros_validar($params[3]);
	if ($id22==''){$id22=0;}
	if ($id22>0){
		$sql='UPDATE even22encuestarpta SET even22rpta'.$iConsec.'='.$iValor.' WHERE even22id='.$id22;
		$tabla=$objdb->ejecutasql($sql);
		}
	}
function f1926_GuadaAbierta($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$id22=numeros_validar($params[1]);
	$sValor=htmlspecialchars($params[2]);
	if ($id22==''){$id22=0;}
	if ($id22>0){
		if (get_magic_quotes_gpc()==1){$sValor=stripslashes($sValor);}
		$even16encabezado=str_replace('&quot;', '"', $sValor);
		$sql='UPDATE even22encuestarpta SET even22nota="'.$sValor.'" WHERE even22id='.$id22;
		$tabla=$objdb->ejecutasql($sql);
		}
	}
function f1926_EncuestasPendientes($idTercero, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1926='lg/lg_1926_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1926)){$mensajes_1926='lg/lg_1926_es.php';}
	require $mensajes_todas;
	require $mensajes_1926;
	$res='';
	$sql='SELECT TB.even21id, TB.even21idencuesta, TB.even21obligatoria, T1.even16encabezado 
FROM even21encuestaaplica AS TB, even16encuesta AS T1 
WHERE TB.even21idtercero='.$idTercero.' AND TB.even21terminada="N" AND TB.even21idencuesta=T1.even16id';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr style="background-color:#00314A;color:#FFFFFF">
<td colspan="2" align="center"><div class="MarquesinaMedia" style="margin:2px 10px;">'.$ETI['msg_pendientes'].'</div></td>
</tr>';
		$tlinea=1;
		while ($fila=$objdb->sf($tabla)){
			$sClass='';
			$sLink='';
			if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
			$tlinea++;
			$sLink='<a href="javascript:resencuesta('.$fila['even21id'].')" class="lnktablero">'.$ETI['msg_responder'].'</a>';
			$res=$res.'<tr'.$sClass.'>
<td>'.cadena_notildes($fila['even16encabezado']).'</td>
<td>'.$sLink.'</td>
</tr>';
			}
		$res=$res.'</table>';
		}
	return $res;
	}
?>