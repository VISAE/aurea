<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 martes, 15 de marzo de 2016
--- Modelo Versión 2.17.0 domingo, 26 de marzo de 2017
--- Modelo Versión 2.23.7 domingo, 8 de octubre de 2019 - Se agrega valiacion a respuestas opcionales.
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
if (isset($_REQUEST['deb_idter'])!=0){
	$_REQUEST['debug']=1;
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$bEnSesion=false;
if ((int)$_SESSION['unad_id_tercero']!=0){$bEnSesion=true;}
if (!$bEnSesion){
	header('Location:index.php');
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=1926;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
require $mensajes_todas;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
/*
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=encuesta.php');
		die();
		}
	}
*/
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 1926 
require 'lib1926.php';
$mensajes_1926='lg/lg_1926_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1926)){$mensajes_1926='lg/lg_1926_es.php';}
require $mensajes_todas;
require $mensajes_1926;
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21depto');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21ciudad');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21ciudad');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21fechanace');
$xajax->register(XAJAX_FUNCTION,'Cargar_even21idcead');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21idcead');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21perfil');
$xajax->register(XAJAX_FUNCTION,'f1621_Guardar_even21idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f1926_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1926_CargarCuerpo');
$xajax->register(XAJAX_FUNCTION,'f1926_GuardaRpta');
$xajax->register(XAJAX_FUNCTION,'f1926_MarcarOpcion');
$xajax->register(XAJAX_FUNCTION,'f1926_GuadaAbierta');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bOtroUsuario=false;
$idTercero=$_SESSION['unad_id_tercero'];
if (isset($_REQUEST['deb_idter'])!=0){
	if (seg_revisa_permiso(17, 1707, $objDB)){
		$idTercero=$_REQUEST['deb_idter'];
		$bOtroUsuario=true;
		}
	}
if (isset($_REQUEST['deb_doc'])!=0){
	if (seg_revisa_permiso(17, 1707, $objDB)){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			//Si el otro usuario tambien tiene el permiso... no se debe permitir.
			if (seg_revisa_permisoV2(17, 1707, $idTercero, $objDB)){
				//Reversamos el permiso
				$bOtroUsuario=false;
				$idTercero=$_SESSION['unad_id_tercero'];
				$sError='No es permitido consultar al usuario '.$_REQUEST['deb_doc'].'';
				}else{
				$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';
				}
			//Termina de revisar si se tiene que revocar el permiso.
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		$_REQUEST['deb_doc']='';
		$sDebug=$sDebug.fecha_microtiempo().' No tiene permiso para consultar datos de otros usuarios [Modulo 17 Permiso 1707]';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf1926'])==0){$_REQUEST['paginaf1926']=1;}
if (isset($_REQUEST['lppf1926'])==0){$_REQUEST['lppf1926']=20;}
if (isset($_REQUEST['boculta1926'])==0){$_REQUEST['boculta1926']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even21pais'])==0){$_REQUEST['even21pais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['even21depto'])==0){$_REQUEST['even21depto']='';}
if (isset($_REQUEST['even21ciudad'])==0){$_REQUEST['even21ciudad']='';}
if (isset($_REQUEST['even21fechanace'])==0){$_REQUEST['even21fechanace']='';}//{fecha_hoy();}
if (isset($_REQUEST['even21perfil'])==0){$_REQUEST['even21perfil']=0;}
if (isset($_REQUEST['even21idzona'])==0){$_REQUEST['even21idzona']='';}
if (isset($_REQUEST['even21idcead'])==0){$_REQUEST['even21idcead']='';}
if (isset($_REQUEST['even21idprograma'])==0){$_REQUEST['even21idprograma']='';}
if (isset($_REQUEST['unad11genero'])==0){$_REQUEST['unad11genero']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['idencuesta'])==0){$_REQUEST['idencuesta']=0;}
if (isset($_REQUEST['id21'])==0){$_REQUEST['id21']=0;}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Cerrar la encuesta.
$bResaltarPendientes=true;
if ($_REQUEST['paso']==2){
	$id21=$_REQUEST['id21'];
	$bTermino=true;
	$sSQL='SELECT 1 FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22irespuesta=-1 AND even22opcional<>"S" AND even22idpregcondiciona=0 AND even22tiporespuesta IN (0,1)';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$bTermino=false;
		}
	if ($bTermino){
		//Agosto 9 de 2019 - Se ajusta para revisar que las preguntas del tipo abierta y que no sean opcionales tengan una respuesta.
		$sSQL='SELECT 1 FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22nota="" AND even22opcional<>"S" AND even22idpregcondiciona=0 AND even22tiporespuesta=3';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$bTermino=false;
			}
		}
	if ($bTermino){
		//Vamos a revisar las condiconales..
		$sSQL='SELECT even22idpregcondiciona, even22valorcondiciona FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22idpregcondiciona<>0 GROUP BY even22idpregcondiciona, even22valorcondiciona';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sSQL='SELECT even22irespuesta FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22idpregunta='.$fila['even22idpregcondiciona'].'';
			$tabla22=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla22)>0){
				$fila22=$objDB->sf($tabla22);
				if ($fila22['even22irespuesta']==$fila['even22valorcondiciona']){
					$sSQL='SELECT 1 FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22irespuesta=-1 AND even22opcional<>"S" AND even22idpregcondiciona='.$fila['even22idpregcondiciona'].' AND AND even22valorcondiciona='.$fila['even22valorcondiciona'].' even22tiporespuesta IN (0,1)';
					$tablar=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablar)>0){
						$bTermino=false;
						}
					if ($bTermino){
						$sSQL='SELECT 1 FROM even22encuestarpta WHERE even22idaplica='.$id21.' AND even22nota="" AND even22opcional<>"S" AND even22idpregcondiciona='.$fila['even22idpregcondiciona'].' AND AND even22valorcondiciona='.$fila['even22valorcondiciona'].' AND even22tiporespuesta=3';
						$tablar=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablar)>0){
							$bTermino=false;
							}
						}
					}
				}
			}
		}
	$sFechaNace='';
	if ($bTermino){
		//Ver que las repuestas de la 21 esten completas.
		$sSQL='SELECT even21pais, even21depto, even21ciudad, even21fechanace, even21perfil, even21idzona, even21idcead, even21idprograma FROM even21encuestaaplica WHERE even21id='.$id21.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['even21perfil']==0){
				if ($fila['even21idprograma']==0){
					$bTermino=false;
					$sError='Por favor indique el programa en que esta matriculado.';
					}
				}
			if ($fila['even21idcead']==0){$bTermino=false;$sError='No ha diligenciado el CEAD al que esta inscrito';}
			if ($fila['even21idzona']==0){$bTermino=false;$sError='No ha diligenciado la zona a la que pertenece';}
			if ($_REQUEST['unad11genero']==''){$bTermino=false;$sError='No ha diligenciado el sexo al que pertenece';}
			if (!fecha_esvalida($fila['even21fechanace'])){
				$bTermino=false;
				$sError='La fecha de nacimiento no es v&aacute;lida.';
				}else{
				$sFechaNace=$fila['even21fechanace'];
				}
			if ($fila['even21ciudad']==''){$bTermino=false;$sError='No ha diligenciado la ciudad de residencia';}
			if ($fila['even21depto']==''){$bTermino=false;$sError='No ha diligenciado el departamento de residencia';}
			if ($sError==''){
				//Ver que no tenga menos de 10 años.
				$aFecha=explode('/',$sFechaNace);
				$iMax=fecha_agno()-10;
				if ($aFecha[2]>$iMax){
					$bTermino=false;
					$sError='Su a&ntilde;o de nacimiento no puede ser superior a '.$iMax;
					}
				}
			}
		}
	if ($bTermino){
		if ($bOtroUsuario){
			$bTermino=false;
			$sError='Todas las condiciones para el cierre de la encuesta estan completas.. [Usted no puede cerrar la encuesta.]';
			}
		}
	if (!$bTermino){
		$_REQUEST['paso']=0;
		if ($sError==''){
			$sError='A&uacute;n no ha diligenciado todas las respuestas, por favor termine sus respuestas y vuelva a intentar.';
			$bResaltarPendientes=true;
			}
		}else{
		$_REQUEST['paso']=-1;
		//Actaulizamos la fecha de nacimiento del tercero.
		$sSQL='SELECT unad11fechanace, unad11genero FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sUpdate='';
			if ($fila['unad11fechanace']!=$sFechaNace){$sUpdate='unad11fechanace="'.$sFechaNace.'"';}
			if ($fila['unad11genero']!=$_REQUEST['unad11genero']){
				if ($sUpdate!=''){$sUpdate=$sUpdate.', ';}
				$sUpdate='unad11genero="'.$_REQUEST['unad11genero'].'"';
				}
			if ($sUpdate!=''){
				$sUpdate='UPDATE unad11terceros SET '.$sUpdate.' WHERE unad11id='.$idTercero.'';
				$tabla=$objDB->ejecutasql($sUpdate);
				}
			}
		//Cerrar la encuesta.
		$sSQL='UPDATE even21encuestaaplica SET even21fechapresenta="'.fecha_hoy().'", even21terminada="S" WHERE even21id='.$id21.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if (false){
			//Totalizar las preguntas.
			$sSQL='SELECT even21idencuesta FROM even21encuestaaplica WHERE even21id='.$id21.'';
			$tabla=$objDB->ejecutasql($sSQL);
			$fila=$objDB->sf($tabla);
			$id16=$fila['even21idencuesta'];
			//Termina de totalizar las preguntas.
			}
		}
	}
//Recarga la encuesta por pregunta divertente
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=0;
	$bMueveScroll=true;
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even21pais']=$_SESSION['unad_pais'];
	$_REQUEST['even21depto']='';
	$_REQUEST['even21ciudad']='';
	$_REQUEST['even21fechanace']='';//fecha_hoy();
	$_REQUEST['even21perfil']=0;
	$_REQUEST['even21idzona']='';
	$_REQUEST['even21idcead']='';
	$_REQUEST['even21idprograma']='';
	$_REQUEST['paso']=0;
	$_REQUEST['idencuesta']=0;
	$_REQUEST['id21']=0;
	$sSQL='SELECT unad11fechanace, unad11genero FROM unad11terceros WHERE unad11id='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad11genero']=$fila['unad11genero'];
		}else{
		$_REQUEST['unad11genero']='';
		}
	//Revisar que encuestas debe hacer....
	f1926_CargarEncuestas($idTercero, $objDB);
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
$iNumEncuestas=0;
$iCaracter=-1;
if ($_REQUEST['idencuesta']!=0){
	$iNumEncuestas=1;
	}else{
	list($_REQUEST['idencuesta'], $_REQUEST['id21'])=f1926_Siguiente($idTercero, $objDB);
	if ($_REQUEST['idencuesta']!=0){$iNumEncuestas=1;}
	}
if ($_REQUEST['idencuesta']!=0){
	$html_unad11genero=html_combo('unad11genero', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11genero'], $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	$sTituloEncuesta='NO SE ENCUENTRA LA ENCUESTA {REF '.$_REQUEST['idencuesta'].'}';
	$html_preguntas='';
	$html_cierre='';
	$bVuelve=true;
	$bAjustarTitulo=false;
	$sSQL='SELECT * FROM even16encuesta WHERE even16id='.$_REQUEST['idencuesta'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sTituloEncuesta=cadena_notildes($fila['even16encabezado']);
		$iCaracter=$fila['even16caracter'];
		$html_cierre='';
		$sSQL='SELECT T1.even21idperaca, T1.even21idcurso, T1.even21idbloquedo, T1.even21terminada, T1.even21pais, T1.even21depto, T1.even21ciudad, T1.even21fechanace, T1.even21idzona, T1.even21idcead, T1.even21perfil, T1.even21idprograma, T1.even21obligatoria 
FROM even21encuestaaplica AS T1 
WHERE T1.even21id='.$_REQUEST['id21'].'';
		$tabla21=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla21)>0){
			$bVuelve=false;
			$fila21=$objDB->sf($tabla21);
			$_REQUEST['even21pais']=$fila21['even21pais'];
			$_REQUEST['even21depto']=$fila21['even21depto'];
			$_REQUEST['even21ciudad']=$fila21['even21ciudad'];
			$_REQUEST['even21fechanace']=$fila21['even21fechanace'];
			$_REQUEST['even21idzona']=$fila21['even21idzona'];
			$_REQUEST['even21idcead']=$fila21['even21idcead'];
			$_REQUEST['even21perfil']=$fila21['even21perfil'];
			$_REQUEST['even21idprograma']=$fila21['even21idprograma'];
			if ($fila21['even21terminada']=='S'){
				$html_cierre='';
				$bVuelve=true;
				}else{
				if ($fila21['even21obligatoria']!='S'){$bVuelve=true;}
				$html_cierre='<div class="salto1px"></div><div class="ir_derecha">
<input id="cmdTermina" name="cmdTermina" type="button" value="Terminar" class="btSoloProceso" onclick="terminar()" title="Terminar"/>
</div>';
				}
			if ($fila21['even21idcurso']!=0){$bAjustarTitulo=true;}
			if ($bAjustarTitulo){
				$idPeraca=$fila21['even21idperaca'];
				$idCurso=$fila21['even21idcurso'];
				//Sacar el nombre del periodo y del curso.
				$sSQL='SELECT unad40nombre FROM unad40curso WHERE unad40id='.$idCurso.'';
				$tabla40=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla40)>0){
					$fila40=$objDB->sf($tabla40);
					//$sTituloEncuesta=$sTituloEncuesta.'<br>Curso: '.$idCurso.' - '.cadena_notildes($fila40['unad40nombre']).'';
					$sTituloEncuesta='<h3>Curso: '.$idCurso.' - '.cadena_notildes($fila40['unad40nombre']).'</h3>'.$sTituloEncuesta;
					}
				}
			}
		$html_preguntas=f1926_Html_Respuestas($_REQUEST['id21'], $objDB, 0, 0, $bResaltarPendientes);
		}
	if ($bVuelve){
		$html_cierre=$html_cierre.$ETI['msg_vuelveindex'];
		}
	$html_even21pais=html_combo('even21pais', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['even21pais'], $objDB, 'carga_combo_even21depto();', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21depto=html_combo_even21depto($objDB, $_REQUEST['even21depto'], $_REQUEST['even21pais']);
	$html_even21ciudad=html_combo_even21ciudad($objDB, $_REQUEST['even21ciudad'], $_REQUEST['even21depto']);
	$html_even21perfil=html_combo('even21perfil', 'even30id', 'even30nombre', 'even30perfilencuesta', '', 'even30id', $_REQUEST['even21perfil'], $objDB, 'guardar_even21perfil()', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21idzona=html_combo('even21idzona', 'unad23id', 'unad23nombre', 'unad23zona', '', 'unad23nombre', $_REQUEST['even21idzona'], $objDB, 'carga_combo_even21idcead();', true, '{'.$ETI['msg_seleccione'].'}', '');
	$html_even21idcead=html_combo_even21idcead($objDB, $_REQUEST['even21idcead'], $_REQUEST['even21idzona']);
	$html_even21idprograma=html_combo('even21idprograma', 'core09id', 'core09nombre', 'core09programa', '', 'core09nombre', $_REQUEST['even21idprograma'], $objDB, 'guardar_even21idprograma()', true, '{'.$ETI['msg_seleccione'].'}', '');
	}
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
//$html_blistar=html_combo('blistar', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=1926 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['blistar'], $objDB, 'paginarf1926()', true, '{'.$ETI['msg_todos'].'}', '');
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){$seg_5=1;}
	}
//Cargar las tablas de datos
//$et_menu=html_menu($APP->idsistema, $objDB);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_1926']);
//echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<?php
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=5"></script>
<script language="javascript">
<!--
function limpiapagina(){
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	window.document.frmedita.submit();
	}
function mueveencuesta(){
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function MensajeAlarma(sHTML){
	document.getElementById('alarma').innerHTML=sHTML;
	}
function carga_combo_even21depto(){
	var params=new Array();
	params[0]=window.document.frmedita.even21pais.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_Cargar_even21depto(params);
	}
function carga_combo_even21ciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.even21depto.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_Cargar_even21ciudad(params);
	}
function guardar_even21ciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.even21ciudad.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_f1621_Guardar_even21ciudad(params);
	}
function guardar_even21fechanace(){
	var params=new Array();
	params[0]=window.document.frmedita.even21fechanace.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_f1621_Guardar_even21fechanace(params);
	}
function carga_combo_even21idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idzona.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_Cargar_even21idcead(params);
	}
function guardar_even21idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idcead.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_f1621_Guardar_even21idcead(params);
	}
function guardar_even21perfil(){
	var params=new Array();
	params[0]=window.document.frmedita.even21perfil.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_f1621_Guardar_even21perfil(params);
	}
function guardar_even21idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.even21idprograma.value;
	params[1]=window.document.frmedita.id21.value;
	params[9]=window.document.frmedita.debug.value;
	xajax_f1621_Guardar_even21idprograma(params);
	}
function paginarf1921(){
	var params=new Array();
	params[101]=window.document.frmedita.paginaf1921.value;
	params[102]=window.document.frmedita.lppf1921.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f1921_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function mantener_sesion(){xajax_sesion_mantener();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function CargarCuerpo(){
	var params=new Array();
	params[0]=window.document.frmedita.id21.value;
	xajax_f1926_CargarCuerpo(params);
	}
function selrpta(idRpta, valor, itipo, iDiverge){
	var params=new Array();
	params[1]=idRpta;
	params[2]=valor;
	params[3]=itipo;
	params[4]=iDiverge;
	params[5]=window.document.frmedita.id21.value;
	if (iDiverge==1){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		}
	xajax_f1926_GuardaRpta(params);
	}
function marcaropcion(idRpta, iConsec, bChequeada){
	var iValor=0;
	if (bChequeada){iValor=1;}
	var params=new Array();
	params[1]=idRpta;
	params[2]=iConsec;
	params[3]=iValor;
	xajax_f1926_MarcarOpcion(params);
	}
function rptabierta(idRpta, sValor){
	var params=new Array();
	params[1]=idRpta;
	params[2]=sValor;
	xajax_f1926_GuadaAbierta(params);
	}
function terminar(){
	if (confirm('Gracias por su tiempo, confirma que ha respondido todas las preguntas?')){
		expandesector(98);
		window.document.frmedita.paso.value=2;
		window.document.frmedita.submit();
		}
	}
// -->
</script>
<form id="frmimprime" name="frmimprime" method="post" action="unadverrpt.php" target="_blank">
<input id="paso_rpt" name="paso_rpt" type="hidden" value="1" />
<input id="id_rpt" name="id_rpt" type="hidden" value="<?php echo $id_rpt; ?>" />
<input id="alias" name="alias" type="hidden" value="reporte" />
<input id="variable" name="variable" type="hidden" value="<?php echo 'valor_variable'; ?>" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="idencuesta" name="idencuesta" type="hidden" value="<?php echo $_REQUEST['idencuesta']; ?>" />
<input id="id21" name="id21" type="hidden" value="<?php echo $_REQUEST['id21']; ?>" />
<?php
if ($bOtroUsuario){
	echo '<input id="deb_idter" name="deb_idter" type="hidden" value="'.$idTercero.'" />';
	}
?>
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['idencuesta']==0){
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '&nbsp;&nbsp;<h2>'.$ETI['titulo_1926'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=false;
//Mostrar formulario para editar
if ($iNumEncuestas==0){
	echo f1926_texto_noencuesta();
	}else{
	if ($iCaracter>0){
?>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_obligatoria'];
?>
</div>
<div class="salto1px"></div>
</div>
<?php
		}
?>
<div class="MarquesinaMedia">
<?php
	//Deberiamos tener un numero de encuesta...
	echo $sTituloEncuesta;
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['msg_lugar'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21pais'];
?>
</label>
<label>
<?php
echo $html_even21pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21depto'];
?>
</label>
<label>
<div id="div_even21depto">
<?php
echo $html_even21depto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21ciudad'];
?>
</label>
<label>
<div id="div_even21ciudad">
<?php
echo $html_even21ciudad;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['even21fechanace'];
?>
</label>
<div class="Campo300">
<?php
echo html_fecha('even21fechanace', $_REQUEST['even21fechanace'], true, 'guardar_even21fechanace()', 1900, fecha_agno());
?>
</div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_genero'];
?>
</label>
<label>
<?php
echo $html_unad11genero;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['msg_vinculo'];
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idzona'];
?>
</label>
<label>
<?php
echo $html_even21idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idcead'];
?>
</label>
<label>
<div id="div_even21idcead">
<?php
echo $html_even21idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21perfil'];
?>
</label>
<label>
<?php
echo $html_even21perfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['even21idprograma'];
?>
</label>
<label>
<?php
echo $html_even21idprograma;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div id="div_respuestas">
<?php
	echo $html_preguntas;
?>
</div>
<div class="salto1px"></div>
<?php
	echo $html_cierre;
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<input id="titulo_1921" name="titulo_1921" type="hidden" value="<?php echo $ETI['titulo_1926']; ?>" />
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_1926'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1926'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
?>
<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<?php
forma_piedepagina();
?>