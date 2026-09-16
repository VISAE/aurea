<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Martes, 21 de julio de 2026
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
*/
$err_level = error_reporting(E_ALL);
error_reporting($err_level);
ini_set("display_errors", 1);
ini_set("error_log", "/var/www/panel/log.php"); // campus colombia
set_time_limit(0);

require './app.php';
if (isset($APP->dbhost) == 0) {
	echo 'No se ha definido el servidor de base de datos';
	die();
}
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'forma_dark.php';
require $APP->rutacomun . 'unad_librerias.php';
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
if (isset($APP->dbmodelo) == 0) {
	$APP->dbmodelo = 'M';
}
$versionejecutable = 10185;
$procesos = 0;
$suspende = 0;
$error = 0;
$sError = '';
$xajax = null;
//FORMA
encabezado($xajax, 'UPD');
cuerpo();
//
echo 'Iniciando proceso de revision de la base de datos <b>[DB : ' . $APP->dbname . ']</b><br>';
$sSQL = $objDB->sSQLListaTablas('unad00config');
$result = $objDB->ejecutasql($sSQL);
$cant = $objDB->nf($result);
if ($cant < 1) {
	echo 'Debe ejecutar el script inicial<br>';
	die();
} else {
	$sSQL = "SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result = $objDB->ejecutasql($sSQL);
	$row = $objDB->sf($result);
	$dbversion = $row['unad00valor'];
	$bbloquea = false;
	if ($dbversion < 10000) {
		$bbloquea = true;
	}
	if ($dbversion > 11000) {
		$bbloquea = true;
	}
	if ($bbloquea) {
		echo '<br>Debe ejecutar el script que corresponda a la version {' . formato_numero($dbversion) . '}...';
		die();
	}
}
$sSQL = '';
echo "Version Actual de la base de datos " . formato_numero($dbversion) . '<br>';
echo '<ul style="margin-top: 10px;">';
if (true) {
	$u01 = "INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion) VALUES ";
	$u01b = "INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion, unad01ruta, unad01orden) VALUES ";
	$u03 = "INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
	$u04 = "INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
	$u05 = "INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
	$u06 = "INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
	$u08 = "INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
	$u09 = "INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
	$u22 = "INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	$u60 = 'INSERT INTO unad60preferencias (unad60idmodulo, unad60codigo, unad60nombre, unad60tipo) VALUES ';
	$unad70 = 'INSERT INTO unad70bloqueoelimina (unad70idtabla, unad70idtablabloquea, unad70origennomtabla, unad70origenidtabla, unad70origencamporev, unad70mensaje, unad70etiqueta) VALUES ';
	$u96 = "INSERT INTO unad96estado (unad96idmodulo, unad96id, unad96nombre, unad96etiqueta) VALUES ";
}
while ($dbversion < $versionejecutable) {
	$sSQL = '';
	if (($dbversion > 10000) && ($dbversion < 10101)) {
		// 21 de Julio de 2026
		if ($dbversion==10001){$sSQL="CREATE TABLE ppto61familiaconf (ppto61idfamilia int NOT NULL, ppto61idvigencia int NOT NULL, ppto61consec int NOT NULL, ppto61id int NOT NULL DEFAULT 0, ppto61formacuenta int NOT NULL DEFAULT 0, ppto61variable int NOT NULL DEFAULT 0, ppto61idcuenta int NOT NULL DEFAULT 0, ppto61recurso int NOT NULL DEFAULT 0, ppto45idcpc int NOT NULL DEFAULT 0, ppto61formavalor int NOT NULL DEFAULT 0, ppto61valor Decimal(15,2) NULL DEFAULT 0)";}
		if ($dbversion==10002){$sSQL="ALTER TABLE ppto61familiaconf ADD PRIMARY KEY(ppto61id)";}
		if ($dbversion==10003){$sSQL=$objDB->sSQLCrearIndice('ppto61familiaconf', 'ppto61familiaconf_id', 'ppto61idfamilia, ppto61idvigencia, ppto61consec', true);}
		if ($dbversion==10004){$sSQL=$objDB->sSQLCrearIndice('ppto61familiaconf', 'ppto61familiaconf_padre', 'ppto61idfamilia');}
		if ($dbversion==10005){$sSQL="add_campos|fact26conceptoegreso|fact26idfamiliappto int NOT NULL DEFAULT 0";}
		if ($dbversion==10006){$sSQL="add_campos|gthv01cargo|gthv01tipocosto int NOT NULL DEFAULT 0";}
		if ($dbversion==10007){$sSQL="add_campos|gafi02desplazamiento|gafi02tipocosto int NOT NULL DEFAULT 0|gafi02cpto_terr_id int NOT NULL DEFAULT 0|gafi02cpto_terr_vr Decimal(15,2) NULL DEFAULT 0|gafi02cpto_aereo_id int NOT NULL DEFAULT 0|gafi02cpto_aereo_vr Decimal(15,2) NULL DEFAULT 0|gafi02cpto_fluvial_id int NOT NULL DEFAULT 0|gafi02cpto_fluvial_vr Decimal(15,2) NULL DEFAULT 0|gafi02cpto_viatico_id int NOT NULL DEFAULT 0|gafi02cpto_viatico_cant int NOT NULL DEFAULT 0|gafi02cpto_viatico_vr Decimal(15,2) NULL DEFAULT 0|gafi02cpto_viaticoint_id int NOT NULL DEFAULT 0|gafi02cpto_viaticoint_cant int NOT NULL DEFAULT 0|gafi02cpto_viaticoint_moneda int NOT NULL DEFAULT 0|gafi02cpto_viaticoint_base Decimal(15,2) NULL DEFAULT 0|gafi02cpto_viaticoint_trm Decimal(15,2) NULL DEFAULT 0|gafi02cpto_viaticoint_vr Decimal(15,2) NULL DEFAULT 0|gafi02idminuta int NOT NULL DEFAULT 0";}
		if ($dbversion==10008){$sSQL="add_campos|fact01resolucion|fact01externalkey varchar(20) NULL";}
		if ($dbversion==10009){$sSQL="add_campos|cart05listaprod|cart05tipo int NOT NULL DEFAULT 0|cart05porcentaje Decimal(15,2) NULL DEFAULT 0|cart05porcdtoprontopago Decimal(15,2) NULL DEFAULT 0";}
		if ($dbversion==10010){$sSQL="add_campos|sine10inscripcion|sine10reciboinscrip_id int NOT NULL DEFAULT 0|sine10reciboinscrip_vigencia int NOT NULL DEFAULT 0|sine10reciboinscrip_lista int NOT NULL DEFAULT 0";}
		// 22 de Julio de 2026
		if ($dbversion==10011){$sSQL="add_campos|gafi02desplazamiento|gafi02jefe_id int NOT NULL DEFAULT 0|gafi02jefe_fechaautoriza int NOT NULL DEFAULT 0|gafi02jefe_codigo varchar(20) NULL|gafi02origen_pais varchar(3) NULL|gafi02origen_depto varchar(5) NULL|gafi02origen_ciudad varchar(8) NULL";}
		if ($dbversion==10012){$sSQL="CREATE TABLE nico74tipogasto (nico74id int NOT NULL, nico74nombre varchar(50) NULL, nico74etiqueta int NOT NULL DEFAULT 0)";}
		if ($dbversion==10013){$sSQL="ALTER TABLE nico74tipogasto ADD PRIMARY KEY(nico74id)";}
		if ($dbversion==10014){$sSQL="INSERT INTO nico74tipogasto (nico74id, nico74nombre, nico74etiqueta) VALUES (0, '{Ninguno}', 0), (1, 'Costo Servicios Educativos', 101), (2, 'Gasto Administrativo', 102)";}
		if ($dbversion==10015){$sSQL="INSERT INTO comp74momentoest (comp74id, comp74nombre) VALUES (11, 'Propuestas')";}
		// OMAR - SC
		if ($dbversion==10016){$sSQL="add_campos|cara01encuesta|cara01fichadiscrimina int NOT NULL DEFAULT 0|cara01niveldiscrimina int NOT NULL DEFAULT 0";}
		if ($dbversion==10017){$sSQL="INSERT INTO cara07bloqueeval (cara07id, cara07nombre) VALUES (9, 'Discriminación y Violencias Basadas en Género')";}
		if ($dbversion==10018){$sSQL="add_campos|cara11tipocaract|cara11ficha9 varchar(1) NULL|cara11ficha9pregbas int NOT NULL DEFAULT 0|cara11ficha9pregprof int NOT NULL DEFAULT 0";}
		if ($dbversion==10019){$sSQL="agregamodulo|2362|23|Preguntas Discriminación y VBG|1|2|3|4|5|6";}
		if ($dbversion==10020){$sSQL=$u09."(2362, 1, 'Preguntas Discriminación y VBG', 'carapregdiscrimina.php', 1, 2362, 'S', '', '')";}
		// ---
		// 27 de Julio de 2026
		if ($dbversion==10021){$sSQL="CREATE TABLE gafi33desplazamientoliquida (gafi33idsolicitud int NOT NULL, gafi33consec int NOT NULL, gafi33id int NOT NULL DEFAULT 0, gafi33fechaini int NOT NULL DEFAULT 0, gafi33fechafin int NOT NULL DEFAULT 0, gafi33tipoperiodo int NOT NULL DEFAULT 0, gafi33cantidad Decimal(15,2) NULL DEFAULT 0, gafi33porcentaje Decimal(15,2) NULL DEFAULT 0, gafi33pais varchar(3) NULL, gafi33depto varchar(5) NULL, gafi33ciudad varchar(8) NULL, gafi33destino int NOT NULL DEFAULT 0, gafi33idescala int NOT NULL DEFAULT 0, gafi33fechaescala int NOT NULL DEFAULT 0, gafi33idrango int NOT NULL DEFAULT 0, gafi33baseliquida Decimal(15,2) NULL DEFAULT 0, gafi33moneda int NOT NULL DEFAULT 0, gafi33vrdiario Decimal(15,2) NULL DEFAULT 0, gafi33vrtotalmoneda Decimal(15,2) NULL DEFAULT 0, gafi33trmproyectada Decimal(15,2) NULL DEFAULT 0, gafi33vrproyectadocop Decimal(15,2) NULL DEFAULT 0, gafi33trmaplicada Decimal(15,2) NULL DEFAULT 0, gafi33fechatrm int NOT NULL DEFAULT 0, gafi33vrtotalcop Decimal(15,2) NULL DEFAULT 0, gafi33idtrayecto int NOT NULL DEFAULT 0, gafi33detalle Text NULL)";}
		if ($dbversion==10022){$sSQL="ALTER TABLE gafi33desplazamientoliquida ADD PRIMARY KEY(gafi33id)";}
		if ($dbversion==10023){$sSQL=$objDB->sSQLCrearIndice('gafi33desplazamientoliquida', 'gafi33desplazamientoliquida_id', 'gafi33idsolicitud, gafi33consec', true);}
		if ($dbversion==10024){$sSQL=$objDB->sSQLCrearIndice('gafi33desplazamientoliquida', 'gafi33desplazamientoliquida_padre', 'gafi33idsolicitud');}
		if ($dbversion==10025){$sSQL="agregamodulo|4633|46|Solicitud desp - Liquidación|1|2|3|4|5|6|8";}
		// 28 de Julio de 2026
		if ($dbversion==10026){$sSQL="CREATE TABLE gafi07tipodocdesp (gafi07idtipodesp int NOT NULL, gafi07consec int NOT NULL, gafi07id int NOT NULL DEFAULT 0, gafi07vigente int NOT NULL DEFAULT 0, gafi07opcional int NOT NULL DEFAULT 0, gafi07orden int NOT NULL DEFAULT 0, gafi07visible int NOT NULL DEFAULT 0, gafi07nombre varchar(100) NULL, gafi07actor int NOT NULL DEFAULT 0, gafi07idtipodocumento int NOT NULL DEFAULT 0, gafi07instrucciones Text NULL)";}
		if ($dbversion==10027){$sSQL="ALTER TABLE gafi07tipodocdesp ADD PRIMARY KEY(gafi07id)";}
		if ($dbversion==10028){$sSQL=$objDB->sSQLCrearIndice('gafi07tipodocdesp', 'gafi07tipodocdesp_id', 'gafi07idtipodesp, gafi07consec', true);}
		if ($dbversion==10029){$sSQL="agregamodulo|4607|46|Documentos para desplazamientos|1|2|3|4|5|6|8";}
		if ($dbversion==10030){$sSQL=$u09."(4607, 1, 'Documentos para desplazamientos', 'gafidocdesplaza.php', 2, 4607, 'S', '', '')";}
		if ($dbversion==10031){$sSQL="INSERT INTO gafi01tipodesplaza(gafi01consec ,gafi01id ,gafi01activo ,gafi01requiereaval, gafi01nombre) VALUES (0 ,0 ,0 ,0, '{Ninguno}')";}
		// 29 de Julio de 2026
		if ($dbversion==10032){$sSQL="CREATE TABLE gafi34anexosolicitud (gafi34idsolicitud int NOT NULL, gafi34iddocumento int NOT NULL, gafi34id int NOT NULL DEFAULT 0, gafi34idorigen int NOT NULL DEFAULT 0, gafi34idarchivo int NOT NULL DEFAULT 0, gafi34fechadoc int NOT NULL DEFAULT 0, gafi34idaprueba int NOT NULL DEFAULT 0, gafi34fechaaprueba int NOT NULL DEFAULT 0, gafi34idcarga int NOT NULL DEFAULT 0, gafi34fechacarga int NOT NULL DEFAULT 0)";}
		if ($dbversion==10033){$sSQL="ALTER TABLE gafi34anexosolicitud ADD PRIMARY KEY(gafi34id)";}
		if ($dbversion==10034){$sSQL=$objDB->sSQLCrearIndice('gafi34anexosolicitud', 'gafi34anexosolicitud_id', 'gafi34idsolicitud, gafi34iddocumento', true);}
		if ($dbversion==10035){$sSQL=$objDB->sSQLCrearIndice('gafi34anexosolicitud', 'gafi34anexosolicitud_padre', 'gafi34idsolicitud');}
		if ($dbversion==10036){$sSQL="agregamodulo|4634|46|Anexos de la solicitud de desplazamiento|1|2|3|4|5|6";}

		if ($dbversion==10037){$sSQL="CREATE TABLE corg41integracurso (corg41idintegracion int NOT NULL, corg41idperiodo int NOT NULL, corg41idcurso int NOT NULL, corg41id int NOT NULL DEFAULT 0, corg41contenedor1 int NOT NULL DEFAULT 0, corg41idcore041 int NOT NULL DEFAULT 0, corg41idacta1 int NOT NULL DEFAULT 0, corg41existe1 int NOT NULL DEFAULT 0, corg41estado1 int NOT NULL DEFAULT 0, corg41puntaje1 Decimal(15,2) NULL DEFAULT 0, corg41resultado1 Decimal(15,2) NULL DEFAULT 0, corg41contenedor2 int NOT NULL DEFAULT 0, corg41idcore042 int NOT NULL DEFAULT 0, corg41idacta2 int NOT NULL DEFAULT 0, corg41existe2 int NOT NULL DEFAULT 0, corg41estado2 int NOT NULL DEFAULT 0, corg41puntaje2 Decimal(15,2) NULL DEFAULT 0, corg41resultado2 Decimal(15,2) NULL DEFAULT 0, corg41contenedordest int NOT NULL DEFAULT 0, corg41idcore04dest int NOT NULL DEFAULT 0, corg41fechacierre int NOT NULL DEFAULT 0, corg41estado int NOT NULL DEFAULT 0, corg41idusuario int NOT NULL DEFAULT 0, corg41fecha int NOT NULL DEFAULT 0, corg41hora int NOT NULL DEFAULT 0, corg41minuto int NOT NULL DEFAULT 0)";}
		if ($dbversion==10038){$sSQL="ALTER TABLE corg41integracurso ADD PRIMARY KEY(corg41id)";}
		if ($dbversion==10039){$sSQL=$objDB->sSQLCrearIndice('corg41integracurso', 'corg41integracurso_id', 'corg41idintegracion, corg41idperiodo, corg41idcurso', true);}
		if ($dbversion==10040){$sSQL=$objDB->sSQLCrearIndice('corg41integracurso', 'corg41integracurso_padre', 'corg41idintegracion');}
		if ($dbversion==10041){$sSQL="agregamodulo|4741|47|Integrar terceros - Cursos|1|3|5|6";}
		if ($dbversion==10042){$sSQL="CREATE TABLE corg42integraactividad (corg42idintegracion int NOT NULL, corg42idintegracurso int NOT NULL, corg42idactividad int NOT NULL, corg42id int NOT NULL DEFAULT 0, corg42idcore051 int NOT NULL DEFAULT 0, corg42existe1 int NOT NULL DEFAULT 0, corg42estado1 int NOT NULL DEFAULT 0, corg42nota1 Decimal(15,2) NULL DEFAULT 0, corg42puntaje751 int NOT NULL DEFAULT 0, corg42puntaje251 int NOT NULL DEFAULT 0, corg42acumula751 int NOT NULL DEFAULT 0, corg42acumula251 int NOT NULL DEFAULT 0, corg42calificado1 int NOT NULL DEFAULT 0, corg42idcore052 int NOT NULL DEFAULT 0, corg42existe2 int NOT NULL DEFAULT 0, corg42estado2 int NOT NULL DEFAULT 0, corg42nota2 Decimal(15,2) NULL DEFAULT 0, corg42puntaje752 int NOT NULL DEFAULT 0, corg42puntaje252 int NOT NULL DEFAULT 0, corg42acumula752 int NOT NULL DEFAULT 0, corg42acumula252 int NOT NULL DEFAULT 0, corg42calificado2 int NOT NULL DEFAULT 0, corg42seleccion int NOT NULL DEFAULT 0, corg42idcore05dest int NOT NULL DEFAULT 0, corg42idusuario int NOT NULL DEFAULT 0, corg42fecha int NOT NULL DEFAULT 0, corg42hora int NOT NULL DEFAULT 0, corg42minuto int NOT NULL DEFAULT 0)";}
		if ($dbversion==10043){$sSQL="ALTER TABLE corg42integraactividad ADD PRIMARY KEY(corg42id)";}
		if ($dbversion==10044){$sSQL=$objDB->sSQLCrearIndice('corg42integraactividad', 'corg42integraactividad_id', 'corg42idintegracion, corg42idintegracurso, corg42idactividad', true);}
		if ($dbversion==10045){$sSQL=$objDB->sSQLCrearIndice('corg42integraactividad', 'corg42integraactividad_padre', 'corg42idintegracion');}
		if ($dbversion==10046){$sSQL="agregamodulo|4742|47|Integrar terceros - Actividades|1|3|5|6";}

		if ($dbversion==10047){$sSQL="agregamodulo|720|7|Nota credito|1|2|3|4|5|6";}
		if ($dbversion==10048){$sSQL=$u09."(720, 1, 'Nota credito', 'factnotacredito.php', 701, 720, 'S', '', '')";}
		if ($dbversion==10049){$sSQL="agregamodulo|721|7|Nota debito|1|2|3|4|5|6";}
		if ($dbversion==10050){$sSQL=$u09."(721, 1, 'Nota debito', 'factnotadebito.php', 701, 721, 'S', '', '')";}
		// 30 de Julio de 2026
		if ($dbversion==10051){$sSQL="ALTER TABLE grad59proyajustes CHANGE grad59titulo grad59titulo VARCHAR(300)";}
		if ($dbversion==10052){$sSQL="agregamodulo|5601|11|Consolidado de tramites|1|5|6";}
		if ($dbversion==10053){$sSQL=$u09."(5601, 1, 'Consolidado de tramites', 'sairptcontram.php', 11, 5601, 'S', '', '')";}

		if ($dbversion==10054){$sSQL="add_campos|unad88opciones|unad88loginthemis int NOT NULL DEFAULT 0";}
		// 03 de Agosto de 2026
    	if ($dbversion==10055){$sSQL="add_campos|fact01resolucion|fact01aplicanc int NOT NULL DEFAULT 0|fact01aplicand int NOT NULL DEFAULT 0|fact01entorno int NOT NULL DEFAULT 0";}
		if ($dbversion==10056){$sSQL="ALTER TABLE cttc55requisitos CHANGE cttc55nombre cttc55nombre VARCHAR(200)";}
		// 5 DE Agosto de 2026
		//cara23idtercero
		if ($dbversion==10057){$sSQL=$objDB->sSQLCrearIndice('cara23acompanamento', 'cara23acompanamento_tercero', 'cara23idtercero');}
		// 11 de Agosto de 2026
		if ($dbversion==10058){$sSQL="agregamodulo|2767|27|Verificación de cartera|1|5|6";}
		if ($dbversion==10059){$sSQL=$u09."(2767, 1, 'Verificación de cartera', 'gradpostuladocart.php', 2201, 2767, 'S', '', '')";}
		// 13 de Agosto de 2026
		if ($dbversion==10060){$sSQL="UPDATE unad02modulos SET unad02idsistema=45 WHERE unad02id=4500";}
		// 14 de Agosto de 2026
		if ($dbversion==10061){$sSQL="add_campos|core00params|core00numestaula int NOT NULL DEFAULT 0|core00numestgrupo int NOT NULL DEFAULT 0";}
		if ($dbversion==10062){$sSQL="add_campos|comp12procesocompra|comp12estadoppto int NOT NULL DEFAULT 0|comp12idjefeoficina int NOT NULL DEFAULT 0|comp12jefe_codigo varchar(6) NULL|comp12jefe_fecha int NOT NULL DEFAULT 0|comp12jefe_hora int NOT NULL DEFAULT 0|comp12jefe_minuto int NOT NULL DEFAULT 0|comp12ppto_hora int NOT NULL DEFAULT 0|comp12ppto_minuto int NOT NULL DEFAULT 0";}
		if ($dbversion==10063){$sSQL=$u96."(3912, 0, 'En elaboración', 100), 
		(3912, 1, 'Devuelta para ajustes', 101), 
		(3912, 3, 'Solicitud de verificación presupuestal', 103), 
		(3912, 5, 'Solicitud de expedición', 105), 
		(3912, 7, 'Solicitud radicada', 107), 
		(3912, 8, 'Negada', 108), 
		(3912, 9, 'Anulada', 109), 
		(3912, 11, 'Avalada por comité financiero', 111), 
		(3912, 17, 'CDP expedido', 117)";}
		// 18 de agosto de 2026
		if ($dbversion==10064){$sSQL=$u96."(4740, 0, 'Gestión de matricula', 100), 
		(4740, 5, 'Calificaciones', 105), 
		(4740, 7, 'Terminado', 107)";}
		if ($dbversion==10065){$sSQL="add_campos|corg40articulacion|corg40estado int NOT NULL DEFAULT 0";}

		if ($dbversion==10066){$sSQL="add_campos|core00params|core00art_idzona int NOT NULL DEFAULT 0|core00art_idcentro int NOT NULL DEFAULT 0|core00art_idescuela int NOT NULL DEFAULT 0|core00art_idprograma int NOT NULL DEFAULT 0";}
		if ($dbversion==10067){$sSQL="INSERT INTO core17origenmatricula (core17id, core17nombre) VALUES (2, 'Articulación')";}
		if ($dbversion==10068){$sSQL="add_campos|unad48cursoaula|unad48numestgrupo int NOT NULL DEFAULT 0|unad48grupobase int NOT NULL DEFAULT 0|unad48grupotope int NOT NULL DEFAULT 0";}
		// 19 de Agosto de 2026
		if ($dbversion==10069){$sSQL=$u96."(3912, 13, 'En Gestión de vistos buenos', 113), 
		(3912, 15, 'Aprobado', 115)";}
		// 25 de Agosto de 2026
		if ($dbversion==10070){$sSQL="CREATE TABLE inve78docanotaciones (inve78iddocumento int NOT NULL, inve78consec int NOT NULL, inve78id int NOT NULL DEFAULT 0, inve78nota Text NULL, inve78idusuario int NOT NULL DEFAULT 0, inve78fecha int NOT NULL DEFAULT 0, inve78hora int NOT NULL DEFAULT 0, inve78min int NOT NULL DEFAULT 0, inve78idorigen int NOT NULL DEFAULT 0, inve78idarchivo int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10071){$sSQL="ALTER TABLE inve78docanotaciones ADD PRIMARY KEY(inve78id)";}
		if ($dbversion==10072){$sSQL=$objDB->sSQLCrearIndice('inve78docanotaciones', 'inve78docanotaciones_id', 'inve78iddocumento, inve78consec', true);}
		if ($dbversion==10073){$sSQL="agregamodulo|4078|40|Anotaciones|1|2|3|4|5|6|8";}

		if ($dbversion==10074){$sSQL="CREATE TABLE visa76estadoaspirante (visa76id int NOT NULL, visa76nombre varchar(50) NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10075){$sSQL="ALTER TABLE visa76estadoaspirante ADD PRIMARY KEY(visa76id)";}
		if ($dbversion==10076){$sSQL="INSERT INTO visa76estadoaspirante (visa76id, visa76nombre) VALUES (0, 'Borrador'), (1, 'Interesado'), (2, 'En gestion'), (3, 'Finalizado');";}		
		// 27 de Agosto de 2026
		if ($dbversion==10077){$sSQL=$u96."(1205, 5, 'En proceso', 105)";}
		// 28 de Agosto de 2026
		if ($dbversion==10078){$sSQL = $u04 . "(3912, 12, 'S')";}
		if ($dbversion==10079){$sSQL="agregamodulo|3991|39|Perfiles|1";}
		if ($dbversion==10080){$sSQL=$u09."(3991, 1, 'Perfiles', 'unadperfil.php', 2, 3991, 'S', '', '')";}
		if ($dbversion==10081){$sSQL="agregamodulo|3992|39|Usuarios|1";}
		if ($dbversion==10082){$sSQL=$u09."(3992, 1, 'Usuarios', 'unadusuarios.php', 1, 3992, 'S', '', '')";}
		// 31 de Agosto de 2026
		if ($dbversion==10083){$sSQL="CREATE TABLE ppto44vistos (ppto44consec int NOT NULL, ppto44id int NOT NULL DEFAULT 0, ppto44nombre varchar(150) NULL, ppto44forma int NOT NULL DEFAULT 0, ppto44idtercero int NOT NULL DEFAULT 0, ppto44idunidad int NOT NULL DEFAULT 0, ppto44idequipotrab int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10084){$sSQL="ALTER TABLE ppto44vistos ADD PRIMARY KEY(ppto44id)";}
		if ($dbversion==10085){$sSQL=$objDB->sSQLCrearIndice('ppto44vistos', 'ppto44vistos_id', 'ppto44consec', true);}
		if ($dbversion==10086){$sSQL="agregamodulo|544|5|Vistos buenos presupuestales|1|2|3|4|5|6|8";}
		if ($dbversion==10087){$sSQL=$u09."(544, 1, 'Vistos buenos presupuestales', 'pptovistos.php', 2, 544, 'S', '', '')";}

		if ($dbversion==10088){$sSQL="CREATE TABLE ppto45procesovistos (ppto45idproceso int NOT NULL, ppto45idvisto int NOT NULL, ppto45id int NOT NULL DEFAULT 0, ppto45orden int NOT NULL DEFAULT 0, ppto45forma int NOT NULL DEFAULT 0, ppto45estado int NOT NULL DEFAULT 0, ppto45idtercero int NOT NULL DEFAULT 0, ppto45idunidad int NOT NULL DEFAULT 0, ppto45idequipotrab int NOT NULL DEFAULT 0, ppto45idfirmante int NOT NULL DEFAULT 0, ppto45fechafirma int NOT NULL DEFAULT 0, ppto45horafirma int NOT NULL DEFAULT 0, ppto45minutofirma int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10089){$sSQL="ALTER TABLE ppto45procesovistos ADD PRIMARY KEY(ppto45id)";}
		if ($dbversion==10090){$sSQL=$objDB->sSQLCrearIndice('ppto45procesovistos', 'ppto45procesovistos_id', 'ppto45idproceso, ppto45idvisto', true);}
		if ($dbversion==10091){$sSQL=$objDB->sSQLCrearIndice('ppto45procesovistos', 'ppto45procesovistos_padre', 'ppto45idproceso');}
		if ($dbversion==10092){$sSQL="agregamodulo|545|5|Proceso de compra - Vistos buenos|1|2|3|4|5|6";}
		// 2 de Agosto de 2026
		if ($dbversion==10093){$sSQL="add_campos|ppto45procesovistos|ppto45idusuarioaplicafirma int NOT NULL DEFAULT 0";}
		if ($dbversion==10094){$sSQL="add_campos|comp12procesocompra|comp12jefe_idusaplicafirma int NOT NULL DEFAULT 0";}
		// 3 de Agosto de 2026
		if ($dbversion==10095){$sSQL="mod_quitar|551";}
		if ($dbversion==10096){$sSQL="mod_quitar|552";}
		if ($dbversion==10097){$sSQL="mod_quitar|553";}
		if ($dbversion==10098){$sSQL="mod_quitar|554";}
		if ($dbversion==10099){$sSQL="mod_quitar|555";}
		if ($dbversion==10100){$sSQL="add_campos|comp12procesocompra|comp12cf_id int NOT NULL DEFAULT 0|comp12cf_idusuario int NOT NULL DEFAULT 0|comp12cf_fecha int NOT NULL DEFAULT 0|comp12cf_hora int NOT NULL DEFAULT 0|comp12cf_minuto int NOT NULL DEFAULT 0";}
		}
	if (($dbversion > 10100) && ($dbversion < 10201)) {
		if ($dbversion==10101){$sSQL="CREATE TABLE ppto46comitefinanciero (ppto46vigencia int NOT NULL, ppto46consec int NOT NULL, ppto46id int NOT NULL DEFAULT 0, ppto46fecha int NOT NULL DEFAULT 0, ppto46estado int NOT NULL DEFAULT 0, ppto46detalle Text NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10102){$sSQL="ALTER TABLE ppto46comitefinanciero ADD PRIMARY KEY(ppto46id)";}
		if ($dbversion==10103){$sSQL=$objDB->sSQLCrearIndice('ppto46comitefinanciero', 'ppto46comitefinanciero_id', 'ppto46vigencia, ppto46consec', true);}
		if ($dbversion==10104){$sSQL="agregamodulo|546|5|Comités financieros|1|2|3|4|5|6|8";}
		if ($dbversion==10105){$sSQL=$u09."(546, 1, 'Comités financieros', 'pptocomitefin.php', 503, 546, 'S', '', '')";}
		if ($dbversion==10106){$sSQL="CREATE TABLE ppto47comiteproceso (ppto47idcomite int NOT NULL, ppto47idproceso int NOT NULL, ppto47id int NOT NULL DEFAULT 0, ppto47resultado int NOT NULL DEFAULT 0, ppto47detalle Text NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";}
		if ($dbversion==10107){$sSQL="ALTER TABLE ppto47comiteproceso ADD PRIMARY KEY(ppto47id)";}
		if ($dbversion==10108){$sSQL=$objDB->sSQLCrearIndice('ppto47comiteproceso', 'ppto47comiteproceso_id', 'ppto47idcomite, ppto47idproceso', true);}
		if ($dbversion==10109){$sSQL=$objDB->sSQLCrearIndice('ppto47comiteproceso', 'ppto47comiteproceso_padre', 'ppto47idcomite');}
		if ($dbversion==10110){$sSQL="agregamodulo|547|5|Procesos del Comité financiero|1|2|3|4|5|6";}

		if ($dbversion==10111){$sSQL="CREATE TABLE visa20programacion (visa20consec int NOT NULL, visa20id int NOT NULL DEFAULT 0, visa20estado int NOT NULL DEFAULT 0, visa20nombre varchar(250) NULL, visa20numsemana int NOT NULL DEFAULT 0, visa20fechaini int NOT NULL DEFAULT 0, visa20fechafin int NOT NULL DEFAULT 0, visa20destino int NOT NULL DEFAULT 0)";}
		if ($dbversion==10112){$sSQL="ALTER TABLE visa20programacion ADD PRIMARY KEY(visa20id)";}
		if ($dbversion==10113){$sSQL=$objDB->sSQLCrearIndice('visa20programacion', 'visa20programacion_id', 'visa20consec', true);}
		if ($dbversion==10114){$sSQL="agregamodulo|5020|29|Programación|1|2|3|4|5|6|8";}
		if ($dbversion==10115){$sSQL=$u09."(5020, 1, 'Programación', 'visaprogramacion.php', 5020, 5020, 'S', '', '')";}
		if ($dbversion==10116){$sSQL="CREATE TABLE visa21items (visa20idprogramacion int NOT NULL, visa21idprograma int NOT NULL, visa21consec int NOT NULL, visa21id int NOT NULL DEFAULT 0, visa21fecha int NOT NULL DEFAULT 0, visa21horaini int NOT NULL DEFAULT 0, visa21minini int NOT NULL DEFAULT 0, visa21horafin int NOT NULL DEFAULT 0, visa21minfin int NOT NULL DEFAULT 0)";}
		if ($dbversion==10117){$sSQL="ALTER TABLE visa21items ADD PRIMARY KEY(visa21id)";}
		if ($dbversion==10118){$sSQL=$objDB->sSQLCrearIndice('visa21items', 'visa21items_id', 'visa20idprogramacion, visa21idprograma, visa21consec', true);}
		if ($dbversion==10119){$sSQL=$objDB->sSQLCrearIndice('visa21items', 'visa21items_padre', 'visa20idprogramacion');}
		if ($dbversion==10120){$sSQL="agregamodulo|5021|29|Items|1|2|3|4|5|6|8";}
		if ($dbversion==10121){$sSQL="CREATE TABLE visa22estado (visa22id int NOT NULL DEFAULT 0, visa22nombre varchar(50) NULL)";}
		if ($dbversion==10122){$sSQL="ALTER TABLE visa22estado ADD PRIMARY KEY(visa22id)";}
		if ($dbversion==10123){$sSQL="CREATE TABLE visa23programas (visa23consec int NOT NULL, visa23id int NOT NULL DEFAULT 0, visa23nombre varchar(250) NULL, visa23activo int NOT NULL DEFAULT 0, visa23descripcion Text NULL)";}
		if ($dbversion==10124){$sSQL="ALTER TABLE visa23programas ADD PRIMARY KEY(visa23id)";}
		if ($dbversion==10125){$sSQL=$objDB->sSQLCrearIndice('visa23programas', 'visa23programas_id', 'visa23consec', true);}
		if ($dbversion==10126){$sSQL="agregamodulo|5023|29|Programas|1|2|3|4|5|6|8";}
		if ($dbversion==10127){$sSQL=$u09."(5023, 1, 'Programas', 'visaprogramas.php', 5020, 5023, 'S', '', '')";}
		if ($dbversion==10128){$sSQL=$u08."(5020, 'Radio Unad Virtual', 'gm.php?id=5020', 'Radio Unad Virtual', 'Radio Unad Virtual', 'Radio Unad Virtual')";}

		if ($dbversion==10129){$sSQL=$objDB->sSQLCrearIndice('comp12procesocompra', 'comp12procesocompra_comite', 'comp12cf_id');}
		if ($dbversion==10130){$sSQL="INSERT INTO comp21plancompra (comp21vigencia, comp21tipo, comp21consec, comp21id, comp21estado, comp21fecha, comp21resolucion, comp21origenresol, comp21idresolucion, comp21detalle) VALUES (0, 0, 0, 0, 0, 0, '', 0, 0, '')";}
		
		// 9 de Septiembre de 2026
		if ($dbversion==10131){$sSQL="add_campos|grad25tipoanexoproyecto|grad25idescuela int NOT NULL DEFAULT 0|grad25obligatorio int NOT NULL DEFAULT 0|grad25momento int NOT NULL DEFAULT 0";}
		// 10 de Septiembre de 2026
		if ($dbversion==10132){$sSQL="add_campos|masi09firma|masi09idzona int NOT NULL DEFAULT 0|masi09idcentro int NOT NULL DEFAULT 0";}
		// 15 de Septiembre de 2026 - OMAR
		if ($dbversion==10133){$sSQL="CREATE TABLE visa55sistema (visa55idproyecto int NOT NULL, visa55codigo varchar(10) NOT NULL, visa55id int NOT NULL DEFAULT 0, visa55nombre varchar(100) NULL, visa55descripcion Text NULL, visa55idresponsable int NOT NULL DEFAULT 0, visa55vigente int NOT NULL DEFAULT 0, visa55color int NOT NULL DEFAULT 0, visa55fechacreacion int NOT NULL DEFAULT 0, visa55fechaactualiza int NOT NULL DEFAULT 0)";}
		if ($dbversion==10134){$sSQL="ALTER TABLE visa55sistema ADD PRIMARY KEY(visa55id)";}
		if ($dbversion==10135){$sSQL=$objDB->sSQLCrearIndice('visa55sistema', 'visa55sistema_id', 'visa55idproyecto, visa55codigo', true);}
		if ($dbversion==10136){$sSQL="agregamodulo|2955|29|Sistemas VISAE|1|2|3|4|5|6|8";}
		if ($dbversion==10137){$sSQL=$u09."(2955, 1, 'Sistemas VISAE', 'visaesistemas.php', 2, 2955, 'S', '', '')";}
		if ($dbversion==10138){$sSQL="CREATE TABLE visa56persemanal (visa56consec int NOT NULL, visa56id int NOT NULL DEFAULT 0, visa56fechaini int NOT NULL DEFAULT 0, visa56fechafin int NOT NULL DEFAULT 0, visa56estado int NOT NULL DEFAULT 0, visa56fechacrea int NOT NULL DEFAULT 0, visa56fechacierre int NOT NULL DEFAULT 0, visa56observacion Text NULL, visa56activo int NOT NULL DEFAULT 0)";}
		if ($dbversion==10139){$sSQL="ALTER TABLE visa56persemanal ADD PRIMARY KEY(visa56id)";}
		if ($dbversion==10140){$sSQL=$objDB->sSQLCrearIndice('visa56persemanal', 'visa56persemanal_id', 'visa56consec', true);}
		if ($dbversion==10141){$sSQL="agregamodulo|2956|29|Periodos semanales|1|2|3|4|5|6|8";}
		if ($dbversion==10142){$sSQL=$u09."(2956, 1, 'Periodos semanales', 'visaepersemanal.php', 1, 2956, 'S', '', '')";}
		if ($dbversion==10143){$sSQL="CREATE TABLE visa57actividad (visa57idpersemanal int NOT NULL, visa57idsistema int NOT NULL, visa57consec int NOT NULL, visa57id int NOT NULL DEFAULT 0, visa57titulo varchar(200) NULL, visa57descripcion Text NULL, visa57tipoactividad int NOT NULL DEFAULT 0, visa57estado int NOT NULL DEFAULT 0, visa57prioridad int NOT NULL DEFAULT 0, visa57fechaprogini int NOT NULL DEFAULT 0, visa57fechaprogfin int NOT NULL DEFAULT 0, visa57fechaejecini int NOT NULL DEFAULT 0, visa57fechaejecfin int NOT NULL DEFAULT 0, visa57porcavance Decimal(15,2) NULL DEFAULT 0, visa57fechacrea int NOT NULL DEFAULT 0, visa57fechaactualiza int NOT NULL DEFAULT 0)";}
		if ($dbversion==10144){$sSQL="ALTER TABLE visa57actividad ADD PRIMARY KEY(visa57id)";}
		if ($dbversion==10145){$sSQL=$objDB->sSQLCrearIndice('visa57actividad', 'visa57actividad_id', 'visa57idpersemanal, visa57idsistema, visa57consec', true);}
		if ($dbversion==10146){$sSQL="agregamodulo|2957|29|Actividades VISAE|1|2|3|4|5|6|8";}
		if ($dbversion==10147){$sSQL=$u09."(2957, 1, 'Actividades VISAE', 'visaeactividad.php', 2909, 2957, 'S', '', '')";}
		if ($dbversion==10148){$sSQL="CREATE TABLE visa58resultado (visa58idactividad int NOT NULL, visa58consec int NOT NULL, visa58id int NOT NULL DEFAULT 0, visa58descripcion Text NULL, visa58cumplimiento int NOT NULL DEFAULT 0, visa58fecharegistro int NOT NULL DEFAULT 0)";}
		if ($dbversion==10149){$sSQL="ALTER TABLE visa58resultado ADD PRIMARY KEY(visa58id)";}
		if ($dbversion==10150){$sSQL=$objDB->sSQLCrearIndice('visa58resultado', 'visa58resultado_id', 'visa58idactividad, visa58consec', true);}
		if ($dbversion==10151){$sSQL=$objDB->sSQLCrearIndice('visa58resultado', 'visa58resultado_padre', 'visa58idactividad');}
		if ($dbversion==10152){$sSQL="agregamodulo|2958|29|Resultados|1|2|3|4|5|6|8";}
		if ($dbversion==10153){$sSQL="CREATE TABLE visa59evidencia (visa59idactividad int NOT NULL, visa59consec int NOT NULL, visa59id int NOT NULL DEFAULT 0, visa59titulo varchar(255) NULL, visa59idorigen int NOT NULL DEFAULT 0, visa59idarchivo int NOT NULL DEFAULT 0, visa59tipoarchivo int NOT NULL DEFAULT 0, visa59descripcion Text NULL, visa59fechacarga int NOT NULL DEFAULT 0, visa59idusuario int NOT NULL DEFAULT 0)";}
		if ($dbversion==10154){$sSQL="ALTER TABLE visa59evidencia ADD PRIMARY KEY(visa59id)";}
		if ($dbversion==10155){$sSQL=$objDB->sSQLCrearIndice('visa59evidencia', 'visa59evidencia_id', 'visa59idactividad, visa59consec', true);}
		if ($dbversion==10156){$sSQL=$objDB->sSQLCrearIndice('visa59evidencia', 'visa59evidencia_padre', 'visa59idactividad');}
		if ($dbversion==10157){$sSQL="agregamodulo|2959|29|Evidencias|1|2|3|4|5|6|8";}
		if ($dbversion==10158){$sSQL="CREATE TABLE visa60reprograma (visa60idactividad int NOT NULL, visa60consec int NOT NULL, visa60id int NOT NULL DEFAULT 0, visa60fechareproini int NOT NULL DEFAULT 0, visa60fechareprofin int NOT NULL DEFAULT 0, visa60motivo Text NULL, visa60fecharegistro int NOT NULL DEFAULT 0, visa60idusuario int NOT NULL DEFAULT 0)";}
		if ($dbversion==10159){$sSQL="ALTER TABLE visa60reprograma ADD PRIMARY KEY(visa60id)";}
		if ($dbversion==10160){$sSQL=$objDB->sSQLCrearIndice('visa60reprograma', 'visa60reprograma_id', 'visa60idactividad, visa60consec', true);}
		if ($dbversion==10161){$sSQL=$objDB->sSQLCrearIndice('visa60reprograma', 'visa60reprograma_padre', 'visa60idactividad');}
		if ($dbversion==10162){$sSQL="agregamodulo|2960|29|Reprogramación|1|2|3|4|5|6|8";}
		if ($dbversion==10163){$sSQL="CREATE TABLE visa61dificultad (visa61idactividad int NOT NULL, visa61consec int NOT NULL, visa61id int NOT NULL DEFAULT 0, visa61descripcion Text NULL, visa61impacto int NOT NULL DEFAULT 0, visa61requiereapoyo int NOT NULL DEFAULT 0, visa61fecharegistro int NOT NULL DEFAULT 0)";}
		if ($dbversion==10164){$sSQL="ALTER TABLE visa61dificultad ADD PRIMARY KEY(visa61id)";}
		if ($dbversion==10165){$sSQL=$objDB->sSQLCrearIndice('visa61dificultad', 'visa61dificultad_id', 'visa61idactividad, visa61consec', true);}
		if ($dbversion==10166){$sSQL=$objDB->sSQLCrearIndice('visa61dificultad', 'visa61dificultad_padre', 'visa61idactividad');}
		if ($dbversion==10167){$sSQL="agregamodulo|2961|29|Dificultades|1|2|3|4|5|6|8";}
		if ($dbversion==10168){$sSQL="CREATE TABLE visa62compromiso (visa62idactividad int NOT NULL, visa62consec int NOT NULL, visa62id int NOT NULL DEFAULT 0, visa62descripcion Text NULL)";}
		if ($dbversion==10169){$sSQL="ALTER TABLE visa62compromiso ADD PRIMARY KEY(visa62id)";}
		if ($dbversion==10170){$sSQL=$objDB->sSQLCrearIndice('visa62compromiso', 'visa62compromiso_id', 'visa62idactividad, visa62consec', true);}
		if ($dbversion==10171){$sSQL=$objDB->sSQLCrearIndice('visa62compromiso', 'visa62compromiso_padre', 'visa62idactividad');}
		if ($dbversion==10172){$sSQL="agregamodulo|2962|29|Compromisos|1|2|3|4|5|6|8";}
		// 16 de Septiembre de 2026 - OMAR
		if ($dbversion==10173){$sSQL="DROP TABLE visa62compromiso";}
		if ($dbversion==10174){$sSQL="CREATE TABLE visa62compromiso (visa62idactividad int NOT NULL, visa62consec int NOT NULL, visa62id int NOT NULL DEFAULT 0, visa62descripcion Text NULL, visa62idresponsable int NOT NULL DEFAULT 0, visa62fechalimite int NOT NULL DEFAULT 0, visa62estado int NOT NULL DEFAULT 0, visa62fechacumple int NOT NULL DEFAULT 0, visa62observaciones Text NULL, visa62fecharegistro int NOT NULL DEFAULT 0)";}
		if ($dbversion==10175){$sSQL="ALTER TABLE visa62compromiso ADD PRIMARY KEY(visa62id)";}
		if ($dbversion==10176){$sSQL=$objDB->sSQLCrearIndice('visa62compromiso', 'visa62compromiso_id', 'visa62idactividad, visa62consec', true);}
		if ($dbversion==10177){$sSQL=$objDB->sSQLCrearIndice('visa62compromiso', 'visa62compromiso_padre', 'visa62idactividad');}
		if ($dbversion==10178){$sSQL="agregamodulo|2962|29|Compromisos|1|2|3|4|5|6|8";}
		if ($dbversion==10179){$sSQL="CREATE TABLE visa63solicitaapoyo (visa63idactividad int NOT NULL, visa63consec int NOT NULL, visa63id int NOT NULL DEFAULT 0, visa63descripcion Text NULL, visa63idcolaborador int NOT NULL DEFAULT 0, visa63estado int NOT NULL DEFAULT 0, visa63fechasolicitud int NOT NULL DEFAULT 0, visa63fecharespuesta int NOT NULL DEFAULT 0, visa63observaciones Text NULL)";}
		if ($dbversion==10180){$sSQL="ALTER TABLE visa63solicitaapoyo ADD PRIMARY KEY(visa63id)";}
		if ($dbversion==10181){$sSQL=$objDB->sSQLCrearIndice('visa63solicitaapoyo', 'visa63solicitaapoyo_id', 'visa63idactividad, visa63consec', true);}
		if ($dbversion==10182){$sSQL=$objDB->sSQLCrearIndice('visa63solicitaapoyo', 'visa63solicitaapoyo_padre', 'visa63idactividad');}
		if ($dbversion==10183){$sSQL="agregamodulo|2963|29|Solicitud de apoyo|1|2|3|4|5|6|8";}
		if ($dbversion==10184){$sSQL=$u08."(2909, 'Gestión semanal', 'gm.php?id=2909', 'Gestión semanal', 'Gestión semanal', 'Gestión semanal')";}





		}
	if (($dbversion > 10200) && ($dbversion < 10301)) {
	}
	if (false) {
		// DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
		if ($dbversion == 99999) {
			$sSQL = "";
		}
		if ($dbversion == 9999) {
			$sSQL = $u04 . "(3646, 10, 'S')";
		}
		//if ($dbversion==6781){$sSQL=$u09."(12280, 1, 'Cupos preoferta', 'corepreofcupos.php', 2206, 12280, 'S', '', '')";}
		//(3220, 'Conceptos para nómina', ''), (3221, 'Provisiones de nómina', '')
		//if ($dbversion==6604){$sSQL="INSERT INTO nico11momento (nico11id, nico11nombre, nico11ayuda) VALUES (3201, 'Liquidación Nomina', '')";}
		//, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version
		//if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
		//if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
		//if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
		//if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
		if ($dbversion == 9201) {
			$sSQL = "INSERT INTO ofes09estadorec (ofes09id, ofes09nombre) VALUES (0, 'Borrador'), (3, 'Devuelto'), (7, 'En firme')";
		}
		// unae26unidadesfun
		// 2711 Proyectos de grado -- Consultar datos de otros usuarios 
		// 2282 Homologaciones por convenio - Abrir - 
		// 2200 Panel SAI - Consultar datos de otros usuarios.
		//if ($dbversion==5999){$sSQL=$u04."(2711, 12, 'S'), (2282, 17, 'S'), (2200, 12, 'S')";}
		/*
	if ($dbversion==4888){$sSQL="INSERT INTO corf09novedadtipo (corf09id, corf09nombre) VALUES (7, 'Aplazamiento Extemporaneo')";}
	if ($dbversion==4690){$sSQL="DROP VIEW unad11personas";}
	if ($dbversion==4690){$sSQL="CREATE VIEW unad11personas AS SELECT unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, unad11direccion, unad11nacionalidad, unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11idmoodle, unad11idcampus, unad11correoinstitucional, unad11idzona, unad11idcead, unad11idescuela, unad11idprograma, unad11presentacion, unad11necesidadesp, unad11idioma, unad11autenticador, unad11fechaclave, unad11debeactualizarclave, unad11formaclave
	FROM unad11terceros";}
	*/
	}

	//if ($dbversion==3099){$sSQL="INSERT INTO unae16cronaccion (unae16id, unae16accion) VALUES (000, 'xxx')";}
	//if ($dbversion==494){$sSQL=$u03."(1702, 'Ofertar Curso'), (1703, 'Cancelar Oferta'), (1704, 'Carga Masiva de Oferta')";}
	//if ($dbversion==510){$sSQL=$u04."(1716, 1711, 'S'), (1716, 1712, 'S'), (1716, 1713, 'S')";}
	//$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	echo '<li>[ ' . $dbversion . ' ] - ' . $sSQL . '</li>';
	switch (substr($sSQL, 0, 10)) {
		case 'versionado':
			$sper = explode("|", $sSQL);
			$stemp = "UPDATE unad01sistema SET unad01mayor=" . $sper[2] . ", unad01menor=" . $sper[3] . ", unad01correccion=" . $sper[4] . " WHERE unad01id=" . $sper[1];
			$result = $objDB->ejecutasql($stemp);
			break;
		case 'agregamodu':
			$sper = explode("|", $sSQL);
			$stemp = "INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (" . $sper[1] . ", '" . $sper[3] . "', " . $sper[2] . ")";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			for ($k = 4; $k < count($sper); $k++) {
				$stemp = $u04 . "(" . $sper[1] . ", " . $sper[$k] . ", 'S')";
				$result = $objDB->ejecutasql($stemp);
				echo " .";
				$stemp = $u06 . "(1, " . $sper[1] . ", " . $sper[$k] . ", 'S')";
				$result = $objDB->ejecutasql($stemp);
				echo ".";
			}
			break;
		case "crearmodul":
			$sper = explode("|", $sSQL);
			$stemp = "INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (" . $sper[1] . ", '" . $sper[3] . "', " . $sper[2] . ")";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			for ($k = 4; $k < count($sper); $k++) {
				$stemp = $u04 . "(" . $sper[1] . ", " . $sper[$k] . ", 'S')";
				$result = $objDB->ejecutasql($stemp);
				echo " .";
			}
			break;
		case "modulogrup":
			$sper = explode("|", $sSQL);
			for ($k = 3; $k < count($sper); $k++) {
				$stemp = $u06 . "(" . $sper[2] . ", " . $sper[1] . ", " . $sper[$k] . ", 'S')";
				$result = $objDB->ejecutasql($stemp);
				echo ".";
			}
			break;
		case 'add_campos':
			$aCampos = explode('|', $sSQL);
			$sTabla = $aCampos[1];
			$iCampos = count($aCampos);
			for ($k = 2; $k < $iCampos; $k++) {
				$sTemp = 'ALTER TABLE ' . $sTabla . ' ADD ' . $aCampos[$k];
				$result = $objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>' . $sTemp . '</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende = 1;
				}
			}
			break;
		case 'drop_campo':
			$aCampos = explode('|', $sSQL);
			$sTabla = $aCampos[1];
			$iCampos = count($aCampos);
			for ($k = 2; $k < $iCampos; $k++) {
				$sTemp = 'ALTER TABLE ' . $sTabla . ' DROP COLUMN ' . $aCampos[$k];
				$result = $objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>' . $sTemp . '</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende = 1;
				}
			}
			break;
		case 'DROP TABLE':
			$nomtabla = substr($sSQL, 11);
			if ($objDB->bexistetabla($nomtabla)) {
				$result = $objDB->ejecutasql($sSQL);
			} else {
				echo '<br> -- La tabla <b>' . $nomtabla . '</b> no existe.';
			}
			break;
		case "mod_cod_ca":
			$sper = explode("|", $sSQL);
			$stemp = "UPDATE unad02modulos SET unad02id=" . $sper[2] . " WHERE unad02id=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "UPDATE unad04modulopermisos SET unad04idmodulo=" . $sper[2] . " WHERE unad04idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "UPDATE unad06perfilmodpermiso SET unad06idmodulo=" . $sper[2] . " WHERE unad06idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "UPDATE unad09modulomenu SET unad09idmodulo=" . $sper[2] . " WHERE unad09idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			break;
		case "mod_quitar":
			$sper = explode("|", $sSQL);
			$stemp = "DELETE FROM unad02modulos WHERE unad02id=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "DELETE FROM unad04modulopermisos WHERE unad04idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "DELETE FROM unad06perfilmodpermiso WHERE unad06idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			$stemp = "DELETE FROM unad09modulomenu WHERE unad09idmodulo=" . $sper[1] . ";";
			$result = $objDB->ejecutasql($stemp);
			echo " .";
			break;
		case '':
			break;
		default:
			$bHayError = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$bHayError = true;
				//Si viene un DROP INDEX no hay error.
				if (strpos($sSQL, 'DROP INDEX') > 0) {
					$bHayError = false;
				}
				if (strpos($sSQL, 'DROP PRIMARY KEY') > 0) {
					$bHayError = false;
				}
			}
			if ($bHayError) {
				//$sError = '<li>[ ' . $dbversion . ' ] <font color="#FF0000"><b>Error </b>'.$objDB->serror.'</font></li>';
				echo '<li> -- <font color="#FF0000">' . $objDB->serror . '</font></li>';
				$error++;
				$suspende = 1;
			}
	} //fin del switch
	$sSQL = "UPDATE unad00config SET unad00valor=" . ($dbversion + 1) . " WHERE unad00codigo='dbversion';";
	$result = $objDB->ejecutasql($sSQL);
	$dbversion++;
	$procesos++;
	if ($procesos > 14) {
		$suspende = 1;
		break;
	}
} //termina de ejecutar sentencia por sentenca.
echo '</ul>';
if ($sError) {
	echo $sError . '<br>';
}
$objDB->CerrarConexion();
?>
<br>Base de Datos Actualizada <?php echo formato_numero($dbversion); ?>;
<?php if ($suspende == 1) { ?><br>
<form id="form1" name="form1" method="post" action="">
El Proceso A&uacute;n No Ha Concluido
<?php
if (false) { 
	//$notablas
?>
<input name="notablas" type="hidden" id="notablas" value="1" />
<?php
}
?>
<div class="salto5px"></div>
<input class="btn-success" type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($error == 0) {
?>
<script language="javascript">
function recargar() {
	form1.submit();
}
setInterval("recargar();", 1000);
</script>
<?php
	} //fin de si no hay errores...
}
piedepagina();
