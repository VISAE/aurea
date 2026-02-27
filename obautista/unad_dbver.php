<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Jueves, 25 de agosto de 2025
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
*/
$err_level = error_reporting(E_ALL);
error_reporting($err_level);
ini_set("display_errors", 1);
//ini_set("error_log", "/var/www/aurea_www/campus/panel/log.htm"); // unad florida
ini_set("error_log", "/var/www/panel/log.htm"); // campus colombia
set_time_limit(0);

require './app.php';
if (isset($APP->dbhost)==0){
	echo 'No se ha definido el servidor de base de datos';
	die();
	}
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'forma_dark.php';
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->dbmodelo) == 0){
	$APP->dbmodelo = 'M';
}
$versionejecutable = 9463;
$procesos=0;
$suspende=0;
$error=0;
$sError = '';
$xajax = null;
//FORMA
encabezado($xajax, 'UPD');
cuerpo();
//
echo 'Iniciando proceso de revision de la base de datos <b>[DB : '.$APP->dbname.']</b><br>';
$sSQL=$objDB->sSQLListaTablas('unad00config');
$result=$objDB->ejecutasql($sSQL);
$cant=$objDB->nf($result);
if ($cant<1){
	echo 'Debe ejecutar el script inicial<br>';
	die();		
} else {
	$sSQL="SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$row=$objDB->sf($result);
	$dbversion=$row['unad00valor'];
	$bbloquea=false;
	if ($dbversion<9000){$bbloquea=true;}
	if ($dbversion>10000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe ejecutar el script que corresponda a la version {'.$dbversion.'}...';
		die();		
	}
}
$sSQL = '';	
echo "Version Actual de la base de datos ".$dbversion.'<br>';
echo '<ul style="margin-top: 10px;">';
if (true){
	$u01="INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion) VALUES ";
	$u03="INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
	$u04="INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
	$u05="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
	$u06="INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
	$u08="INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
	$u09="INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
	$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	$u60='INSERT INTO unad60preferencias (unad60idmodulo, unad60codigo, unad60nombre, unad60tipo) VALUES ';
	$unad70='INSERT INTO unad70bloqueoelimina (unad70idtabla, unad70idtablabloquea, unad70origennomtabla, unad70origenidtabla, unad70origencamporev, unad70mensaje, unad70etiqueta) VALUES ';
	$u96="INSERT INTO unad96estado (unad96idmodulo, unad96id, unad96nombre, unad96etiqueta) VALUES ";
	}
while ($dbversion<$versionejecutable){
$sSQL='';
if (($dbversion>9000)&&($dbversion<9101)){
	if ($dbversion==9001){$sSQL="agregamodulo|5018|29|Jornada - Escuela|1|2|3|4|5|6";}
	if ($dbversion==9002){$sSQL="UPDATE unad02modulos SET unad02idsistema=8 WHERE unad02id IN (708,709,712)";}
	if ($dbversion==9003){$sSQL="CREATE TABLE teso05bancoconfig (teso05idbanco int NOT NULL, teso05digitoscta int NOT NULL, teso05id int NOT NULL DEFAULT 0, teso05tipocuenta varchar(1) NULL)";}
	if ($dbversion==9004){$sSQL="ALTER TABLE teso05bancoconfig ADD PRIMARY KEY(teso05id)";}
	if ($dbversion==9005){$sSQL=$objDB->sSQLCrearIndice('teso05bancoconfig', 'teso05bancoconfig_id', 'teso05idbanco, teso05digitoscta', true);}
	if ($dbversion==9006){$sSQL=$objDB->sSQLCrearIndice('teso05bancoconfig', 'teso05bancoconfig_padre', 'teso05idbanco');}
	if ($dbversion==9007){$sSQL="agregamodulo|815|7|Bancos - Configuracion de cuentas|1|2|3|4|5|6";}
	if ($dbversion==9008){$sSQL="add_campos|unae02bannerimg|unae02fechainicio int NOT NULL DEFAULT 0|unae02fechafinal int NOT NULL DEFAULT 0|unae02alcance int NOT NULL DEFAULT 0";}

	if ($dbversion==9009){$sSQL="CREATE TABLE unaf22bannerperfil (unaf22idbanner int NOT NULL, unaf22idperfil int NOT NULL, unaf22id int NOT NULL DEFAULT 0, unaf22vigente int NOT NULL DEFAULT 0)";}
	if ($dbversion==9010){$sSQL="ALTER TABLE unaf22bannerperfil ADD PRIMARY KEY(unaf22idbanner, unaf22idperfil)";}
	if ($dbversion==9011){$sSQL="agregamodulo|4322|1|Imagen banner - perfil|1|2|3|4|5";}

	if ($dbversion==9012){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s1', 'unaf17p1');}
	if ($dbversion==9013){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s2', 'unaf17p2');}
	if ($dbversion==9014){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s3', 'unaf17p3');}
	if ($dbversion==9015){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s4', 'unaf17p4');}
	if ($dbversion==9016){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s5', 'unaf17p5');}
	if ($dbversion==9017){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s6', 'unaf17p6');}
	if ($dbversion==9018){$sSQL=$objDB->sSQLCrearIndice('unaf17series', 'unaf17series_s7', 'unaf17p7');}

	if ($dbversion==9019){$sSQL="CREATE TABLE gafi29viaticovalor (gafi29consec int NOT NULL, gafi29id int NOT NULL DEFAULT 0, gafi29titulo varchar(250) NULL, gafi29fecha int NOT NULL DEFAULT 0, gafi29destino int NOT NULL DEFAULT 0)";}
	if ($dbversion==9020){$sSQL="ALTER TABLE gafi29viaticovalor ADD PRIMARY KEY(gafi29id)";}
	if ($dbversion==9021){$sSQL=$objDB->sSQLCrearIndice('gafi29viaticovalor', 'gafi29viaticovalor_id', 'gafi29consec', true);}
	if ($dbversion==9022){$sSQL="agregamodulo|4629|46|Escalas de viaticos|1|2|3|4|5|6";}
	if ($dbversion==9023){$sSQL=$u09."(4629, 1, 'Escalas de viaticos', 'gafiescalaviatico.php', 1, 4629, 'S', '', '')";}
	if ($dbversion==9024){$sSQL=$unad70."(2061,4630,'gafi30viaticovrrango','gafi30id','gafi30moneda','El dato esta incluido en Escalas viatico - rangos', '')";}
	if ($dbversion==9025){$sSQL="CREATE TABLE gafi30viaticovrrango (gafi30idviaticovr int NOT NULL, gafi30consec int NOT NULL, gafi30id int NOT NULL DEFAULT 0, gafi30ingdesde Decimal(15,2) NULL DEFAULT 0, gafi30inghasta Decimal(15,2) NULL DEFAULT 0, gafi30moneda int NOT NULL DEFAULT 0, gafi30vrdiario Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9026){$sSQL="ALTER TABLE gafi30viaticovrrango ADD PRIMARY KEY(gafi30id)";}
	if ($dbversion==9027){$sSQL=$objDB->sSQLCrearIndice('gafi30viaticovrrango', 'gafi30viaticovrrango_id', 'gafi30idviaticovr, gafi30consec', true);}
	if ($dbversion==9028){$sSQL=$objDB->sSQLCrearIndice('gafi30viaticovrrango', 'gafi30viaticovrrango_padre', 'gafi30idviaticovr');}
	if ($dbversion==9029){$sSQL="agregamodulo|4630|46|Escalas viatico - rangos|1|2|3|4|5|6";}
	if ($dbversion==9030){$sSQL="CREATE TABLE gafi31destino (gafi31consec int NOT NULL, gafi31id int NOT NULL DEFAULT 0, gafi31activo int NOT NULL DEFAULT 0, gafi31orden int NOT NULL DEFAULT 0, gafi31nombre varchar(250) NULL)";}
	if ($dbversion==9031){$sSQL="ALTER TABLE gafi31destino ADD PRIMARY KEY(gafi31id)";}
	if ($dbversion==9032){$sSQL=$objDB->sSQLCrearIndice('gafi31destino', 'gafi31destino_id', 'gafi31consec', true);}
	if ($dbversion==9033){$sSQL="agregamodulo|4631|46|Destinos para viaticos|1|2|3|4|5|6";}
	if ($dbversion==9034){$sSQL=$u09."(4631, 1, 'Destinos para viaticos', 'gafidestinoviatico.php', 2, 4631, 'S', '', '')";}

	if ($dbversion==9035){$sSQL="add_campos|gafi02desplazamiento|gafi02idsolic_ingbase Decimal(15,2) NULL DEFAULT 0|gafi02idsolic_gastosrep Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9036){$sSQL="mod_quitar|761";}
	if ($dbversion==9037){$sSQL="DROP TABLE fact61monedas";}
	if ($dbversion==9038){$sSQL="add_campos|gthu54hvlaboral|gthu54asig_gastosrep Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9039){$sSQL="add_campos|gafi01tipodesplaza|gafi01tipodesplaza int NOT NULL DEFAULT 0";}
	if ($dbversion==9040){$sSQL="INSERT INTO corf09novedadtipo (corf09id, corf09nombre) VALUES (8, 'Solicitud de reingreso')";}
	if ($dbversion==9041){$sSQL="add_campos|unad24sede|unad24condicion int NOT NULL DEFAULT 0";}
	if ($dbversion==9042){$sSQL="CREATE TABLE unaf23prediocondicion (unaf23id int NOT NULL, unaf23nombre varchar(200) NULL)";}
	if ($dbversion==9043){$sSQL="ALTER TABLE unaf23prediocondicion ADD PRIMARY KEY(unaf23id)";}
	if ($dbversion==9044){$sSQL="INSERT INTO unaf23prediocondicion (unaf23id, unaf23nombre) VALUES (0, 'Propia'), (3, 'Arriendo'), (5, 'Comodato'), (9, 'Externo')";}

	// 2025-10-02 Grupos de investigación
	if ($dbversion==9045){$sSQL=$u01."(54, 'INVESTIGACION', 'Sistema de Gestion de Investigacion', 'N', 'S', 1, 0, 0)";}
	if ($dbversion==9046){$sSQL="CREATE TABLE rese50grupoinvestigacion (rese50consec int NOT NULL, rese50id int NOT NULL DEFAULT 0, rese50orden int NOT NULL DEFAULT 0, rese50activo int NOT NULL DEFAULT 0, rese50nombre varchar(200) NULL)";}
	if ($dbversion==9047){$sSQL="ALTER TABLE rese50grupoinvestigacion ADD PRIMARY KEY(rese50id)";}
	if ($dbversion==9048){$sSQL=$objDB->sSQLCrearIndice('rese50grupoinvestigacion', 'rese50grupoinvestigacion_id', 'rese50consec', true);}
	if ($dbversion==9049){$sSQL="agregamodulo|5450|54|Grupos de investigacion|1|2|3|4|5|6";}
	if ($dbversion==9050){$sSQL=$u09."(5450, 1, 'Grupos de investigacion', 'resegruposinvest.php', 2, 5450, 'S', '', '')";}

	// 2025-10-02 Campos para trabajos de grado
	if ($dbversion==9051){$sSQL="add_campos|grad11proyecto|grad11t_5 int NOT NULL DEFAULT 0|grad11cvlac varchar(25) NULL|grad11orcid varchar(25) NULL|grad11pertenecegrupoinv int NOT NULL DEFAULT 0|grad11idgrupoinvestigacion int NOT NULL DEFAULT 0|grad11iddirectorgaiipu int NOT NULL DEFAULT 0|grad11fechadirectorgaiipu int NOT NULL DEFAULT 0";}	
	if ($dbversion==9052){$sSQL="CREATE TABLE grad39historialcambios (grad39idproyecto int NOT NULL, grad39idtipocambio int NOT NULL, grad39consec int NOT NULL, grad39id int NOT NULL DEFAULT 0, grad39detalle varchar(200) NULL, grad39idusuariosolicita int NOT NULL DEFAULT 0, grad39fechasolicita int NOT NULL DEFAULT 0, grad39usuarioaprueba int NOT NULL DEFAULT 0, grad39fechacambio int NOT NULL DEFAULT 0, grad39estado int NOT NULL DEFAULT 0, grad39titulo_origen varchar(200) NULL, grad39titulo_destino varchar(200) NULL, grad39linkrepositorio_origen varchar(250) NULL, grad39linkrepositorio_destino varchar(250) NULL, grad39susfecha_origen int NOT NULL DEFAULT 0, grad39susfecha_destino int NOT NULL DEFAULT 0, grad39susthora_origen int NOT NULL DEFAULT 0, grad39sustmin_origen int NOT NULL DEFAULT 0, grad39susthora_destino int NOT NULL DEFAULT 0, grad39sustmin_destino int NOT NULL DEFAULT 0, grad39sustlugar_origen varchar(200) NULL, grad39sustlugar_destino varchar(200) NULL, grad39sustmedio_origen varchar(200) NULL, grad39sustmedio_destino varchar(200) NULL)";}
	if ($dbversion==9053){$sSQL="ALTER TABLE grad39historialcambios ADD PRIMARY KEY(grad39id)";}
	if ($dbversion==9054){$sSQL=$objDB->sSQLCrearIndice('grad39historialcambios', 'grad39historialcambios_id', 'grad39idproyecto, grad39idtipocambio, grad39consec', true);}
	if ($dbversion==9055){$sSQL=$objDB->sSQLCrearIndice('grad39historialcambios', 'grad39historialcambios_padre', 'grad39idproyecto');}
	if ($dbversion==9056){$sSQL="agregamodulo|2739|27|Historial de cambios|1|2|3|4|5|6";}
	if ($dbversion==9057){$sSQL="add_campos|grad24proyectoanexo|grad24fechaaprobado2 int NOT NULL DEFAULT 0|grad24idaprobado2 int NOT NULL DEFAULT 0";}
	if ($dbversion==9058){$sSQL="add_campos|grad25tipoanexoproyecto|grad25tipoproyaplica int NOT NULL DEFAULT 0";}
	if ($dbversion==9059){$sSQL=$u04."(4602, 10, 'S'), (4602, 12, 'S'), (4602, 1707, 'S')";}

	if ($dbversion==9060){$sSQL=$u08."(4611, 'Financiero', 'gm.php?id=4611', 'Financiero', 'Financial', 'Financiero')";}
	if ($dbversion==9061){$sSQL="agregamodulo|3166|31|Solicitud de desplazamiento|1";}
	if ($dbversion==9062){$sSQL=$u09."(3166, 1, 'Solicitud de desplazamiento', 'gafsoldesplaza.php', 4611, 4602, 'S', '', '')";}

	if ($dbversion==9063){$sSQL="agregamodulo|4632|46|Gestión de desplazamiento|1|3|4|5|6|10|12|1707";}
	if ($dbversion==9064){$sSQL=$u09."(4632, 1, 'Gestión de desplazamiento', 'gafgestdesplaza.php', 4601, 4632, 'S', '', '')";}
	if ($dbversion==9065){$sSQL="add_campos|gafi03desptrayecto|gafi03mediotransporte int NOT NULL DEFAULT 0|gafi03entregadopor int NOT NULL DEFAULT 0|gafi03cont_idaprueba int NOT NULL DEFAULT 0|gafi03cont_idcuenta int NOT NULL DEFAULT 0|gafi03cont_fechaent int NOT NULL DEFAULT 0";}

	if ($dbversion==9066){$sSQL="CREATE TABLE gafi06otrosconceptos (gafi06idsolicitud int NOT NULL, gafi06idconcepto int NOT NULL, gafi06id int NOT NULL DEFAULT 0, gafi06detalle Text NULL, gafi06vrsolicitado Decimal(15,2) NULL DEFAULT 0, gafi06estado int NOT NULL DEFAULT 0, gafi06vraprobado Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9067){$sSQL="ALTER TABLE gafi06otrosconceptos ADD PRIMARY KEY(gafi06id)";}
	if ($dbversion==9068){$sSQL=$objDB->sSQLCrearIndice('gafi06otrosconceptos', 'gafi06otrosconceptos_id', 'gafi06idsolicitud, gafi06idconcepto', true);}
	if ($dbversion==9069){$sSQL=$objDB->sSQLCrearIndice('gafi06otrosconceptos', 'gafi06otrosconceptos_padre', 'gafi06idsolicitud');}
	if ($dbversion==9070){$sSQL="agregamodulo|4606|46|Solicitud desp - otros conceptos|1|2|3|4|5|6|8";}
	if ($dbversion==9071){$sSQL="add_campos|gafi05solcambioest|gafi05idusuario int NOT NULL DEFAULT 0";}

	if ($dbversion==9072){$sSQL="add_campos|unad18pais|unad18destino int NOT NULL DEFAULT 0";}
	if ($dbversion==9073){$sSQL="add_campos|cara01encuesta|cara01fichaciudad int NOT NULL DEFAULT 0";}
	if ($dbversion==9074){$sSQL="INSERT INTO cara07bloqueeval (cara07id, cara07nombre) VALUES (8, 'Competencias Ciudadanas')";}

	if ($dbversion==9075){$sSQL="CREATE TABLE gafi63festivos (gafi63consec int NOT NULL, gafi63id int NOT NULL DEFAULT 0, gafi63nombre varchar(250) NULL, gafi63tipofestivo int NOT NULL DEFAULT 0, gafi63mes int NOT NULL DEFAULT 0, gafi63dia int NOT NULL DEFAULT 0, gafi63desplazamiento int NOT NULL DEFAULT 0, gafi63agnoini int NOT NULL DEFAULT 0, gafi63agnofin int NOT NULL DEFAULT 0)";}
	if ($dbversion==9076){$sSQL="ALTER TABLE gafi63festivos ADD PRIMARY KEY(gafi63id)";}
	if ($dbversion==9077){$sSQL=$objDB->sSQLCrearIndice('gafi63festivos', 'gafi63festivos_id', 'gafi63consec', true);}
	if ($dbversion==9078){$sSQL="agregamodulo|4663|46|Festivos|1|2|3|4|5|6|8";}
	if ($dbversion==9079){$sSQL=$u09."(4663, 1, 'Festivos', 'gafifestivo.php', 2, 4663, 'S', '', '')";}
	if ($dbversion==9080){$sSQL="CREATE TABLE gafi64diashabiles (gafi64vigencia int NOT NULL, gafi64numerodia int NOT NULL, gafi64diasemana int NOT NULL DEFAULT 0, gafi64orden int NOT NULL DEFAULT 0, gafi64idfestivo int NOT NULL DEFAULT 0, gafi64habil int NOT NULL DEFAULT 0, gafi64ordenhabil int NOT NULL DEFAULT 0, bvigencia int NOT NULL DEFAULT 0)";}
	if ($dbversion==9081){$sSQL="ALTER TABLE gafi64diashabiles ADD PRIMARY KEY(gafi64vigencia, gafi64numerodia)";}
	if ($dbversion==9082){$sSQL="agregamodulo|4664|46|Dias habiles|1|2|3|4|5";}
	if ($dbversion==9083){$sSQL=$u09."(4664, 1, 'Dias hábiles', 'gafihabiles.php', 2, 4664, 'S', '', '')";}
	if ($dbversion==9084){$sSQL="add_campos|cara01encuesta|cara01nivelciudad int NOT NULL DEFAULT 0";}
	if ($dbversion==9085){$sSQL="add_campos|fact02factura|fact02cufe Text NULL|fact02urlfe Text NULL|fact02idfactorigen int NOT NULL DEFAULT 0|fact02idformanota int NOT NULL DEFAULT 0";}

	if ($dbversion==9086){$sSQL="add_campos|gafi02desplazamiento|gafi02idgestor int NOT NULL DEFAULT 0";}
	if ($dbversion==9087){$sSQL="add_campos|unad10vigencia|unad10pascua int NOT NULL DEFAULT 0";}
	if ($dbversion==9088){$sSQL="INSERT INTO gafi63festivos (gafi63consec, gafi63id, gafi63nombre, gafi63tipofestivo, gafi63mes, gafi63dia, gafi63desplazamiento, gafi63agnoini, gafi63agnofin) VALUES (0, 0, '{Ninguno}', 0, 0, 0, 0, 0, 0)";}

	if ($dbversion==9089){$sSQL="agregamodulo|2175|21|Acceso a Taller de Artes Visuales|1|1707";}
	if ($dbversion==9090){$sSQL=$u09."(2175, 1, 'Acceso a Taller de Artes Visuales', 'tav.php', 2106, 2175, 'S', '', '')";}
	if ($dbversion==9091){$sSQL="add_campos|grad41postulaciones|grad41idliderprograma int NOT NULL DEFAULT 0|grad41fechaverificacion int NOT NULL DEFAULT 0";}
	if ($dbversion==9092){$sSQL="mod_quitar|3701";}
	if ($dbversion==9093){$sSQL="mod_quitar|3702";}
	if ($dbversion==9094){$sSQL="mod_quitar|3703";}
	if ($dbversion==9095){$sSQL="DROP TABLE gcmo01indicador";}
	if ($dbversion==9096){$sSQL="DROP TABLE gcmo02periodo";}
	if ($dbversion==9097){$sSQL="DROP TABLE gcmo03reporte";}

	if ($dbversion==9098){$sSQL="CREATE TABLE gcmo01proceso (gcmo01codigo varchar(10) NOT NULL, gcmo01id int NOT NULL DEFAULT 0, gcmo01publico int NOT NULL DEFAULT 0, gcmo01nombre varchar(100) NULL, gcmo01tipo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9098){$sSQL="CREATE TABLE gcmo01proceso (gcmo01codigo varchar(10) NOT NULL, gcmo01id int NOT NULL DEFAULT 0, gcmo01publico int NOT NULL DEFAULT 0, gcmo01nombre varchar(100) NULL, gcmo01tipo int NOT NULL DEFAULT 0, gcmo01categoria int NOT NULL DEFAULT 0, gcmo01sistema int NOT NULL DEFAULT 0)";}
	if ($dbversion==9099){$sSQL="ALTER TABLE gcmo01proceso ADD PRIMARY KEY(gcmo01id)";}
	if ($dbversion==9100){$sSQL=$objDB->sSQLCrearIndice('gcmo01proceso', 'gcmo01proceso_id', 'gcmo01codigo', true);}
}
if (($dbversion>9100)&&($dbversion<9201)){
	if ($dbversion==9101){$sSQL="agregamodulo|3701|37|Procesos|1|2|3|4|5|6";}
	if ($dbversion==9102){$sSQL=$u09."(3701, 1, 'Procesos', 'gcmoproceso.php', 1, 3701, 'S', '', '')";}

	if ($dbversion==9103){$sSQL="CREATE TABLE gcmo02sistema (gcmo02codigo varchar(10) NOT NULL, gcmo02id int NOT NULL DEFAULT 0, gcmo02vigente int NOT NULL DEFAULT 0, gcmo02nombre varchar(100) NULL)";}
	if ($dbversion==9104){$sSQL="ALTER TABLE gcmo02sistema ADD PRIMARY KEY(gcmo02id)";}
	if ($dbversion==9105){$sSQL=$objDB->sSQLCrearIndice('gcmo02sistema', 'gcmo02sistema_id', 'gcmo02codigo', true);}
	if ($dbversion==9106){$sSQL="agregamodulo|3702|37|Sistema|1|2|3|4|5|6";}
	if ($dbversion==9107){$sSQL=$u09."(3702, 1, 'Sistema', 'gcmosistema.php', 2, 3702, 'S', '', '')";}
	// 9108 - 9111 Quedan libres
	if ($dbversion==9112){$sSQL="agregamodulo|3703|37|Indicadores|1|2|3|4|5|6";}
	if ($dbversion==9113){$sSQL="add_campos|cara08pregunta|cara08retroalimenta Text NULL";}

	if ($dbversion==9114){$sSQL="CREATE TABLE gcmo70unidadmedida (gcmo70consec int NOT NULL, gcmo70id int NOT NULL DEFAULT 0, gcmo70orden int NOT NULL DEFAULT 0, gcmo70activo int NOT NULL DEFAULT 0, gcmo70nombre varchar(100) NULL)";}
	if ($dbversion==9115){$sSQL="ALTER TABLE gcmo70unidadmedida ADD PRIMARY KEY(gcmo70id)";}
	if ($dbversion==9116){$sSQL=$objDB->sSQLCrearIndice('gcmo70unidadmedida', 'gcmo70unidadmedida_id', 'gcmo70consec', true);}
	if ($dbversion==9117){$sSQL="agregamodulo|3770|37|Unidades de medida|1|2|3|4|5|6";}
	if ($dbversion==9118){$sSQL=$u09."(3770, 1, 'Unidades de medida', 'gcmounidadmedia.php', 2, 3770, 'S', '', '')";}
	if ($dbversion==9119){$sSQL="CREATE TABLE gcmo71periodicidad (gcmo71id int NOT NULL, gcmo71nombre varchar(50) NULL)";}
	if ($dbversion==9120){$sSQL="ALTER TABLE gcmo71periodicidad ADD PRIMARY KEY(gcmo71id)";}
	if ($dbversion==9121){$sSQL="INSERT INTO gcmo71periodicidad (gcmo71id, gcmo71nombre) VALUES (1, 'Mensual'), (2, 'Bimensual'), (3, 'Trimestral'), (4, 'Cuatrimestral'), (6, 'Semestral - Por Ciclo'), (12, 'Anual - Vigencia'), (13, 'Por bloque de periodos'), (14, 'Por periodo académico'),  (24, 'Bianual'),  (48, 'Cuatrienal')";}

	if ($dbversion==9122){$sSQL="add_campos|saiu00config|saiu00estadoservicio int NOT NULL DEFAULT 1|saiu00mensajeestado Text NULL|saiu00correocopia varchar(50) NULL";}
	if ($dbversion==9123){$sSQL="INSERT INTO saiu46tipotramite (saiu46id, saiu46nombre) VALUES (702, 'Solicitud de facturas')";}

	if ($dbversion==9124){$sSQL="agregamodulo|889|8|Solicitudes de factura [3047]|1";}
	if ($dbversion==9125){$sSQL=$u09."(889, 1, 'Solicitudes de factura', 'saisolfactura.php', 3001, 891, 'S', '', '')";}
	if ($dbversion==9126){$sSQL="CREATE TABLE cara70tipoacompana (cara70id int NOT NULL, cara70nombre varchar(100) NULL, cara70version int NOT NULL DEFAULT 0)";}
	if ($dbversion==9127){$sSQL="ALTER TABLE cara70tipoacompana ADD PRIMARY KEY(cara70id)";}
	if ($dbversion==9128){$sSQL="INSERT INTO cara70tipoacompana (cara70id, cara70nombre, cara70version) VALUES 
		(0, '{Ninguno}', 0),
		(1, 'Inicial', 1),
		(2, 'Intermedia', 1),
		(3, 'Final', 1),
		(11, 'Administrativos', 2),
		(12, 'Metodología Unadista', 2),
		(13, 'Vida Académica y Vida Universitaria', 2),
		(14, 'Diversidad e inclusión', 2),
		(15, 'Atención Psicosocial', 2),
		(16, 'Egreso y Fidelización', 2)";}

	if ($dbversion==9129){$sSQL="CREATE TABLE unaf24botonera (unaf24consec int NOT NULL, unaf24id int NOT NULL DEFAULT 0, unaf24nombre varchar(100) NULL, unaf24activo int NOT NULL DEFAULT 0, unaf24orden int NOT NULL DEFAULT 0, unaf24descripcion varchar(200) NULL, unaf24urldestino varchar(100) NULL, unaf24idlogo int NOT NULL DEFAULT 0, unaf24idorigen int NOT NULL DEFAULT 0)";}
	if ($dbversion==9130){$sSQL="ALTER TABLE unaf24botonera ADD PRIMARY KEY(unaf24consec)";}
	if ($dbversion==9131){$sSQL="agregamodulo|4324|1|Botonera|1|2|3|4|5|6";}
	if ($dbversion==9132){$sSQL=$u09."(4324, 1, 'Botonera', 'unadbotonera.php', 1501, 4324, 'S', '', '')";}

	if ($dbversion==9133){$sSQL="INSERT INTO grad45estadosolgrad (grad45id, grad45nombre) VALUES (9, 'Anulada')";}

	if ($dbversion==9134){$sSQL="DROP TABLE unad11terceros_md";}
	if ($dbversion==9135){$sSQL="CREATE TABLE unad11terceros_md (unad11id int NOT NULL, unad11doc_pais varchar(3) NULL, unad11doc_depto varchar(5) NULL, unad11doc_ciudad varchar(8) NULL, unad11doc_fecha int NOT NULL DEFAULT 0, unad11reside_pais varchar(3) NULL, unad11reside_depto varchar(5) NULL, unad11reside_ciudad varchar(8) NULL, unad11reside_direccion varchar(100) NULL, unad11foto_idorigen int NOT NULL DEFAULT 0, unad11foto_idarchivo int NOT NULL DEFAULT 0, unad11idavatar int NOT NULL DEFAULT 0, unad11notifica_aplica int NOT NULL DEFAULT 0, unad11notifica_periodicidad int NOT NULL DEFAULT 0, unad11notifica_ultimavez int NOT NULL DEFAULT 0)";}
	if ($dbversion==9136){$sSQL="ALTER TABLE unad11terceros_md ADD PRIMARY KEY(unad11id)";}

	if ($dbversion==9137){$sSQL="CREATE TABLE grad40trabajogradoalterno (grad40consec int NOT NULL, grad40id int NOT NULL DEFAULT 0, grad40tipoproy int NOT NULL DEFAULT 0, grad40estado int NOT NULL DEFAULT 0, grad40titulo varchar(200) NULL, grad40idregistro int NOT NULL DEFAULT 0, grad40fecharegistro int NOT NULL DEFAULT 0, grad40detalle Text NULL, grad40idaprueba int NOT NULL DEFAULT 0, grad40fechaaprueba int NOT NULL DEFAULT 0, grad40notaplantrabajo int NOT NULL DEFAULT 0, grad40desemempresa int NOT NULL DEFAULT 0, grad40notadocumento int NOT NULL DEFAULT 0, grad40obraartistica int NOT NULL DEFAULT 0, grad40notasustenta int NOT NULL DEFAULT 0, grad40notafinal int NOT NULL DEFAULT 0, grad40idproyecto int NOT NULL DEFAULT 0, grad40idestudiante int NOT NULL DEFAULT 0, grad40idpei int NOT NULL DEFAULT 0)";}
	if ($dbversion==9138){$sSQL="ALTER TABLE grad40trabajogradoalterno ADD PRIMARY KEY(grad40id)";}
	if ($dbversion==9139){$sSQL=$objDB->sSQLCrearIndice('grad40trabajogradoalterno', 'grad40trabajogradoalterno_id', 'grad40consec', true);}
	if ($dbversion==9140){$sSQL="agregamodulo|2740|27|Trabajo de grado alterno|1|2|3|4|5|6";}
	if ($dbversion==9141){$sSQL=$u09."(2740, 1, 'Trabajo de grado alterno', 'gradproygradoalterno.php', 1, 2740, 'S', '', '')";}
	if ($dbversion==9142){$sSQL="add_campos|cara23acompanamento|cara23idmatricula int NOT NULL DEFAULT 0|cara23idpei int NOT NULL DEFAULT 0";}

	if ($dbversion==9143){$sSQL="agregamodulo|4194|41|Calculadora de fechas|1";}
	if ($dbversion==9144){$sSQL=$u09."(4194, 1, 'Calculadora de fechas', 'calcularfechas.php', 0, 4100, 'S', '', '')";}
	if ($dbversion==9145){$sSQL="add_campos|unad11terceros|unad11contab_idaprueba int NOT NULL DEFAULT 0|unad11contab_fechaaprueba int NOT NULL DEFAULT 0";}
	if ($dbversion==9146){$sSQL=$objDB->sSQLCrearIndice('unad11terceros', 'unad11terceros_contable', 'unad11contab_idaprueba');}

	if ($dbversion==9147){$sSQL="agregamodulo|791|7|Perfiles [105]|1";}
	if ($dbversion==9148){$sSQL=$u09."(791, 1, 'Perfiles', 'unadperfil.php', 2, 791, 'S', '', '')";}
	if ($dbversion==9149){$sSQL="agregamodulo|792|7|Usuarios [107]|1";}
	if ($dbversion==9150){$sSQL=$u09."(792, 1, 'Usuarios', 'unadusuarios.php', 1, 792, 'S', '', '')";}
	if ($dbversion==9151){$sSQL="agregamodulo|793|7|Terceros [111]|1";}
	if ($dbversion==9152){$sSQL=$u09."(793, 1, 'Terceros', 'unadterceros.php', 1, 793, 'S', '', '')";}
	if ($dbversion==9153){$sSQL="agregamodulo|794|7|Equipos de trabajo [1527 -1528]|1";}
	if ($dbversion==9154){$sSQL=$u09."(794, 1, 'Equipos de trabajo', 'misequipos.php', 1, 794, 'S', '', '')";}

	if ($dbversion==9155){$sSQL="agregamodulo|5369|53|Solicitudes de facturas [3047]|1";}
	if ($dbversion==9156){$sSQL=$u09."(5369, 1, 'Solicitudes de facturas [3047]', 'saisolfactura.php', 3001, 5369, 'S', '', '')";}
	if ($dbversion==9157){$sSQL="add_campos|unad11terceros|unad11contab_rutfecha int NOT NULL DEFAULT 0|unad11contab_rutidorigen int NOT NULL DEFAULT 0|unad11contab_rutidarchivo int NOT NULL DEFAULT 0";}

	if ($dbversion==9158){$sSQL="DROP TABLE corf63progconv";}
	if ($dbversion==9159){$sSQL="CREATE TABLE corf63progconv (corf63idescuela int NOT NULL, corf63idprograma int NOT NULL, corf63idperiodo int NOT NULL, corf63idplanest int NOT NULL, corf63consec int NOT NULL, corf63id int NOT NULL DEFAULT 0, corf63estado int NOT NULL DEFAULT 0, corf63numcupos int NOT NULL DEFAULT 0, corf63num_cupolista int NOT NULL DEFAULT 0, corf63forma_evaluar int NOT NULL DEFAULT 0, corf63puntaje_prueba int NOT NULL DEFAULT 0, corf63puntaje_entrevista int NOT NULL DEFAULT 0, corf63fecha_apertura int NOT NULL DEFAULT 0, corf63fecha_liminscrip int NOT NULL DEFAULT 0, corf63fecha_limrevdoc int NOT NULL DEFAULT 0, corf63fecha_pagos int NOT NULL DEFAULT 0, corf63fecha_examenes int NOT NULL DEFAULT 0, corf63fecha_seleccion int NOT NULL DEFAULT 0, corf63fecha_ratificacion int NOT NULL DEFAULT 0, corf63fecha_cierra int NOT NULL DEFAULT 0, corf63presentacion Text NULL, corf63idnav int NOT NULL DEFAULT 0, corf63idmoodle int NOT NULL DEFAULT 0, corf63total_insc int NOT NULL DEFAULT 0, corf63total_autoriza int NOT NULL DEFAULT 0, corf63total_presentaex int NOT NULL DEFAULT 0, corf63total_aprobados int NOT NULL DEFAULT 0, corf63total_admitidos int NOT NULL DEFAULT 0, corf63controlaadmision int NOT NULL DEFAULT 0, corf63porzonas int NOT NULL DEFAULT 0)";}
	if ($dbversion==9160){$sSQL="ALTER TABLE corf63progconv ADD PRIMARY KEY(corf63id)";}
	if ($dbversion==9161){$sSQL=$objDB->sSQLCrearIndice('corf63progconv', 'corf63progconv_id', 'corf63idescuela, corf63idprograma, corf63idperiodo, corf63idplanest, corf63consec', true);}
	if ($dbversion==9162){$sSQL="agregamodulo|2176|21|Acceso a Ecosistema Jur&iacute;dico|1|1707";}
	if ($dbversion==9163){$sSQL=$u09."(2176, 1, 'Acceso a Ecosistema Jur&iacute;dico', 'ecoecjp.php', 2106, 2176, 'S', '', '')";}

	if ($dbversion==9164){$sSQL="add_campos|aure69versionado|aure69proveedor int NOT NULL DEFAULT 0|aure69servicio int NOT NULL DEFAULT 0|aure69datos int NOT NULL DEFAULT 0";}
	if ($dbversion==9165){$sSQL="add_campos|corg19progdocumentos|corf19idorigen int NOT NULL DEFAULT 0|corf19idformato int NOT NULL DEFAULT 0|corf19instrucciones Text NULL";}
	if ($dbversion==9166){$sSQL="add_campos|sine10inscripcion|sine10recibo_ref varchar(50) NULL";}
	if ($dbversion==9167){$sSQL="add_campos|even02evento|even02pais varchar(3) NULL|even02depto varchar(5) NULL|even02ciudad varchar(8) NULL|even02modalidadvirt int NOT NULL DEFAULT 0|even02insfechainivirt varchar(10) NULL|even02insfechafinvirt varchar(10) NULL|even02idnav int NOT NULL DEFAULT 0|even02identornovirtual int NOT NULL DEFAULT 0|even02idunidadfuncionalorg int NOT NULL DEFAULT 0|even02idadministrador int NOT NULL DEFAULT 0|even02idprodpresencial_prev int NOT NULL DEFAULT 0|even02idprodpresencial_ord int NOT NULL DEFAULT 0|even02idprodpresencial_ext int NOT NULL DEFAULT 0|even02idprodvirtual_prev int NOT NULL DEFAULT 0|even02idprodvirtual_ord int NOT NULL DEFAULT 0|even02idprodvirtual_ext int NOT NULL DEFAULT 0|even02desplazamientos int NOT NULL DEFAULT 0|even02controlaasistencia int NOT NULL DEFAULT 0";}
	if ($dbversion==9168){$sSQL="CREATE TABLE even54eventoescenario (even54idevento int NOT NULL, even54consec int NOT NULL, even54id int NOT NULL DEFAULT 0, even54nombre varchar(250) NULL, even54numcupos int NOT NULL DEFAULT 0, even54permitevirtual int NOT NULL DEFAULT 0)";}
	if ($dbversion==9169){$sSQL="ALTER TABLE even54eventoescenario ADD PRIMARY KEY(even54id)";}
	if ($dbversion==9170){$sSQL=$objDB->sSQLCrearIndice('even54eventoescenario', 'even54eventoescenario_id', 'even54idevento, even54consec', true);}
	if ($dbversion==9171){$sSQL=$objDB->sSQLCrearIndice('even54eventoescenario', 'even54eventoescenario_padre', 'even54idevento');}
	if ($dbversion==9172){$sSQL="agregamodulo|1954|19|Escenarios|1|2|3|4|5|6|8";}
	if ($dbversion==9173){$sSQL="CREATE TABLE even53eventojornada (even53idevento int NOT NULL, even53consec int NOT NULL, even53id int NOT NULL DEFAULT 0, even53idescenario int NOT NULL DEFAULT 0, even53nombre varchar(250) NULL, even53fecha int NOT NULL DEFAULT 0, even53horaini int NOT NULL DEFAULT 0, even53minini int NOT NULL DEFAULT 0, even53horafin int NOT NULL DEFAULT 0, even53minfin int NOT NULL DEFAULT 0, even53tematica Text NULL, even53permitevirtual int NOT NULL DEFAULT 0, even53urlvirtual Text NULL)";}
	if ($dbversion==9174){$sSQL="ALTER TABLE even53eventojornada ADD PRIMARY KEY(even53id)";}
	if ($dbversion==9175){$sSQL=$objDB->sSQLCrearIndice('even53eventojornada', 'even53eventojornada_id', 'even53idevento, even53consec', true);}
	if ($dbversion==9176){$sSQL=$objDB->sSQLCrearIndice('even53eventojornada', 'even53eventojornada_padre', 'even53idevento');}
	if ($dbversion==9177){$sSQL="agregamodulo|1953|19|Jornadas|1|2|3|4|5|6|8";}
	if ($dbversion==9178){$sSQL="CREATE TABLE even55eventocomite (even55idevento int NOT NULL, even55consec int NOT NULL, even55id int NOT NULL DEFAULT 0, even55nombre varchar(150) NULL)";}
	if ($dbversion==9179){$sSQL="ALTER TABLE even55eventocomite ADD PRIMARY KEY(even55id)";}
	if ($dbversion==9180){$sSQL=$objDB->sSQLCrearIndice('even55eventocomite', 'even55eventocomite_id', 'even55idevento, even55consec', true);}
	if ($dbversion==9181){$sSQL=$objDB->sSQLCrearIndice('even55eventocomite', 'even55eventocomite_padre', 'even55idevento');}
	if ($dbversion==9182){$sSQL="agregamodulo|1955|19|Evento - Comité|1|2|3|4|5|6|8";}
	if ($dbversion==9183){$sSQL="CREATE TABLE even56eventocomiteparticipa (even56idevento int NOT NULL, even56idcomite int NOT NULL, even56idparticipante int NOT NULL, even56id int NOT NULL DEFAULT 0, even56idzona int NOT NULL DEFAULT 0, even56idcentro int NOT NULL DEFAULT 0, even56idescuela int NOT NULL DEFAULT 0, even56idprograma int NOT NULL DEFAULT 0)";}
	if ($dbversion==9184){$sSQL="ALTER TABLE even56eventocomiteparticipa ADD PRIMARY KEY(even56id)";}
	if ($dbversion==9185){$sSQL=$objDB->sSQLCrearIndice('even56eventocomiteparticipa', 'even56eventocomiteparticipa_id', 'even56idevento, even56idcomite, even56idparticipante', true);}
	if ($dbversion==9186){$sSQL=$objDB->sSQLCrearIndice('even56eventocomiteparticipa', 'even56eventocomiteparticipa_padre', 'even56idevento');}
	if ($dbversion==9187){$sSQL="agregamodulo|1956|19|Evento - Organizadores|1|2|3|4|5|6|8";}
	if ($dbversion==9188){$sSQL="CREATE TABLE even57eventootrosproductos (even57idevento int NOT NULL, even57idproducto int NOT NULL, even57id int NOT NULL DEFAULT 0, even57fechafin int NOT NULL DEFAULT 0, even57activo int NOT NULL DEFAULT 0, even57descripcion Text NULL)";}
	if ($dbversion==9189){$sSQL="ALTER TABLE even57eventootrosproductos ADD PRIMARY KEY(even57id)";}
	if ($dbversion==9190){$sSQL=$objDB->sSQLCrearIndice('even57eventootrosproductos', 'even57eventootrosproductos_id', 'even57idevento, even57idproducto', true);}
	if ($dbversion==9191){$sSQL=$objDB->sSQLCrearIndice('even57eventootrosproductos', 'even57eventootrosproductos_padre', 'even57idevento');}
	if ($dbversion==9192){$sSQL="agregamodulo|1957|19|Otros productos|1|2|3|4|5|6|8";}
	if ($dbversion==9193){$sSQL="add_campos|even04eventoparticipante|even04relacionunad int NOT NULL DEFAULT 0|even04estamentounad int NOT NULL DEFAULT 0";}
	if ($dbversion==9194){$sSQL="CREATE TABLE even58relacionunad (even58id int NOT NULL, even58nombre varchar(50) NULL, even58funcionario int NOT NULL DEFAULT 0, even58estudiante int NOT NULL DEFAULT 0, even58egresado int NOT NULL DEFAULT 0)";}
	if ($dbversion==9195){$sSQL="ALTER TABLE even58relacionunad ADD PRIMARY KEY(even58id)";}
	if ($dbversion==9196){$sSQL="CREATE TABLE even59estamentounad (even59id int NOT NULL, even59nombre varchar(50) NULL)";}
	if ($dbversion==9197){$sSQL="ALTER TABLE even59estamentounad ADD PRIMARY KEY(even59id)";}
	if ($dbversion==9198){$sSQL="CREATE TABLE even60eventoasistencia (even60idevento int NOT NULL, even60idjornada int NOT NULL, even60idtercero int NOT NULL, even60id int NOT NULL DEFAULT 0, even60estadoasiste int NOT NULL DEFAULT 0)";}
	if ($dbversion==9199){$sSQL="ALTER TABLE even60eventoasistencia ADD PRIMARY KEY(even60id)";}
	if ($dbversion==9200){$sSQL=$objDB->sSQLCrearIndice('even60eventoasistencia', 'even60eventoasistencia_id', 'even60idevento, even60idjornada, even60idtercero', true);}
}
if (($dbversion>9200)&&($dbversion<9301)){
	if ($dbversion==9201){$sSQL=$objDB->sSQLCrearIndice('even60eventoasistencia', 'even60eventoasistencia_padre', 'even60idevento');}
	if ($dbversion==9202){$sSQL="agregamodulo|1960|19|Asistencias por jornada|1|2|3|4|5|6|8";}
	//9203 - 9206 quedan libres
	if ($dbversion==9207){$sSQL=$u09."(3703, 1, 'Indicadores', 'gcmoindicador.php', 3701, 3703, 'S', '', '')";}
	if ($dbversion==9208){$sSQL="CREATE TABLE gcmo22fuentedatos (gcmo22id int NOT NULL, gcmo22nombre varchar(100) NULL)";}
	if ($dbversion==9209){$sSQL="ALTER TABLE gcmo22fuentedatos ADD PRIMARY KEY(gcmo22id)";}
	if ($dbversion==9210){$sSQL="INSERT INTO gcmo22fuentedatos (gcmo22id, gcmo22nombre) VALUES (0, 'Sistema Integrado de Información'), (99, 'Digitados')";}

	if ($dbversion==9211){$sSQL="CREATE TABLE gcmo20variable (gcmo20consec int NOT NULL, gcmo20id int NOT NULL DEFAULT 0, gcmo20activa int NOT NULL DEFAULT 0, gcmo20publicar int NOT NULL DEFAULT 0, gcmo20nombre varchar(250) NULL, gcmo20fuente int NOT NULL DEFAULT 0, gcmo20periodicidad int NOT NULL DEFAULT 0, gcmo20unidadacargo int NOT NULL DEFAULT 0, gcmo20dig_agno int NOT NULL DEFAULT 0, gcmo20dig_cohorte int NOT NULL DEFAULT 0, gcmo20dig_bloque int NOT NULL DEFAULT 0, gcmo20dig_zona int NOT NULL DEFAULT 0, gcmo20dig_centro int NOT NULL DEFAULT 0, gcmo20dig_unidadf int NOT NULL DEFAULT 0, gcmo20dig_escuela int NOT NULL DEFAULT 0, gcmo20dig_programa int NOT NULL DEFAULT 0, gcmo20dig_estamento int NOT NULL DEFAULT 0)";}
	if ($dbversion==9212){$sSQL="ALTER TABLE gcmo20variable ADD PRIMARY KEY(gcmo20id)";}
	if ($dbversion==9213){$sSQL=$objDB->sSQLCrearIndice('gcmo20variable', 'gcmo20variable_id', 'gcmo20consec', true);}
	if ($dbversion==9214){$sSQL="agregamodulo|3720|37|Variables|1|2|3|4|5|6|8";}
	if ($dbversion==9215){$sSQL=$u09."(3720, 1, 'Variables', 'gcmovariables.php', 3701, 3720, 'S', '', '')";}
	if ($dbversion==9216){$sSQL="add_campos|fact14producto|fact14uso_compras int NOT NULL DEFAULT 0";}

	if ($dbversion==9217){$sSQL="add_campos|olab60simulador|olab60perfiladmin int NOT NULL DEFAULT 0";}
	if ($dbversion==9218){$sSQL="CREATE TABLE core47admitidos (core47idtercero int NOT NULL, core47idsnies int NOT NULL, core47id int NOT NULL DEFAULT 0, core47idplandeestudios int NOT NULL DEFAULT 0, core47prefijo varchar(20) NULL, core47consec int NOT NULL DEFAULT 0, core47idescuela int NOT NULL DEFAULT 0, core47idprograma int NOT NULL DEFAULT 0, core47idzona int NOT NULL DEFAULT 0, core47idcentro int NOT NULL DEFAULT 0, core47formaadmision int NOT NULL DEFAULT 0, core47agno int NOT NULL DEFAULT 0, core47cicloinicial int NOT NULL DEFAULT 0, core47fechaadmision int NOT NULL DEFAULT 0, core47periodoadmision int NOT NULL DEFAULT 0, core47idtransicion int NOT NULL DEFAULT 0, core47trans_idsnies int NOT NULL DEFAULT 0, core47trans_idplan int NOT NULL DEFAULT 0, core47perm_ciclo1 int NOT NULL DEFAULT 0, core47perm_ciclo2 int NOT NULL DEFAULT 0, core47perm_ciclo3 int NOT NULL DEFAULT 0, core47perm_ciclo4 int NOT NULL DEFAULT 0, core47perm_ciclo5 int NOT NULL DEFAULT 0, core47perm_ciclo6 int NOT NULL DEFAULT 0, core47perm_ciclo7 int NOT NULL DEFAULT 0, core47perm_ciclo8 int NOT NULL DEFAULT 0, core47perm_ciclo9 int NOT NULL DEFAULT 0, core47perm_ciclo10 int NOT NULL DEFAULT 0, core47perm_ciclo11 int NOT NULL DEFAULT 0, core47perm_ciclo12 int NOT NULL DEFAULT 0, core47perm_ciclo13 int NOT NULL DEFAULT 0, core47perm_ciclo14 int NOT NULL DEFAULT 0, core47perm_ciclo15 int NOT NULL DEFAULT 0, core47perm_ciclo16 int NOT NULL DEFAULT 0, core47perm_ciclo17 int NOT NULL DEFAULT 0, core47perm_ciclo18 int NOT NULL DEFAULT 0, core47perm_ciclo19 int NOT NULL DEFAULT 0, core47perm_ciclo20 int NOT NULL DEFAULT 0, core47perm_estado int NOT NULL DEFAULT 0, core47sem_proyectados int NOT NULL DEFAULT 0, core47sem_relativo int NOT NULL DEFAULT 0, core47sem_total int NOT NULL DEFAULT 0)";}
	if ($dbversion==9219){$sSQL="ALTER TABLE core47admitidos ADD PRIMARY KEY(core47id)";}
	if ($dbversion==9220){$sSQL=$objDB->sSQLCrearIndice('core47admitidos', 'core47admitidos_id', 'core47idtercero, core47idsnies', true);}
	if ($dbversion==9221){$sSQL="agregamodulo|2247|22|Admisiones|1|3|5|6|10|21|1701";}
	if ($dbversion==9222){$sSQL=$u09."(2247, 1, 'Admisiones', 'coreadmision.php', 2200, 2247, 'S', '', '')";}
	if ($dbversion==9223){$sSQL="add_campos|core01estprograma|core01idadmision int NOT NULL DEFAULT 0";}
	if ($dbversion==9224){$sSQL="add_campos|core47admitidos|core47idestado int NOT NULL DEFAULT 0";}

	if ($dbversion==9225){$sSQL="add_campos|sine10inscripcion|sine10recibo_estado int NOT NULL DEFAULT 0";}
	if ($dbversion==9226){$sSQL="DROP TABLE sine11estadoinscrip";}
	if ($dbversion==9227){$sSQL="mod_quitar|4911";}
	if ($dbversion==9228){$sSQL="CREATE TABLE sine11inscripcambiaestado (sine11idinscripcion int NOT NULL, sine11consec int NOT NULL, sine11id int NOT NULL DEFAULT 0, sine11estadoorigen int NOT NULL DEFAULT 0, sine11estadodestino int NOT NULL DEFAULT 0, sine11idusuario int NOT NULL DEFAULT 0, sine11detalle Text NULL, sine11fecha int NOT NULL DEFAULT 0, sine11hora int NOT NULL DEFAULT 0, sine11min int NOT NULL DEFAULT 0)";}
	if ($dbversion==9229){$sSQL="ALTER TABLE sine11inscripcambiaestado ADD PRIMARY KEY(sine11id)";}
	if ($dbversion==9230){$sSQL=$objDB->sSQLCrearIndice('sine11inscripcambiaestado', 'sine11inscripcambiaestado_id', 'sine11idinscripcion, sine11consec', true);}
	if ($dbversion==9231){$sSQL=$objDB->sSQLCrearIndice('sine11inscripcambiaestado', 'sine11inscripcambiaestado_padre', 'sine11idinscripcion');}
	if ($dbversion==9232){$sSQL="agregamodulo|4911|49|Cambios de estado|1|2|3|4|5|6|8";}

	if ($dbversion==9233){$sSQL="CREATE TABLE cara60competenciasead (cara60idtercero int NOT NULL, cara60fecha int NOT NULL, cara60id int NOT NULL DEFAULT 0, cara60estado int NOT NULL DEFAULT 0, cara60preg1 int NOT NULL DEFAULT 0, cara60preg2 int NOT NULL DEFAULT 0, cara60preg3 int NOT NULL DEFAULT 0, cara60preg4 int NOT NULL DEFAULT 0, cara60preg5 int NOT NULL DEFAULT 0, cara60preg6 int NOT NULL DEFAULT 0, cara60preg7 int NOT NULL DEFAULT 0, cara60preg8 int NOT NULL DEFAULT 0, cara60preg9 int NOT NULL DEFAULT 0, cara60preg10 int NOT NULL DEFAULT 0, cara60preg11 int NOT NULL DEFAULT 0, cara60preg12 int NOT NULL DEFAULT 0, cara60preg13 int NOT NULL DEFAULT 0, cara60preg14 int NOT NULL DEFAULT 0, cara60preg15 Text NULL, cara60preg15idtexto int NOT NULL DEFAULT 0, cara60puntosmax int NOT NULL DEFAULT 0, cara60puntaje int NOT NULL DEFAULT 0)";}
	if ($dbversion==9234){$sSQL="ALTER TABLE cara60competenciasead ADD PRIMARY KEY(cara60id)";}
	if ($dbversion==9235){$sSQL=$objDB->sSQLCrearIndice('cara60competenciasead', 'cara60competenciasead_id', 'cara60idtercero, cara60fecha', true);}
	if ($dbversion==9236){$sSQL="agregamodulo|2360|23|Prueba de competencias básicas|1|2|3|4|5|6";}
	if ($dbversion==9237){$sSQL=$u09."(2360, 1, 'Prueba de competencias básicas', 'carapruebabase.php', 2301, 2360, 'S', '', '')";}
	if ($dbversion==9238){$sSQL="CREATE TABLE cara61compbasepreg (core61consec int NOT NULL, core61id int NOT NULL DEFAULT 0, core61activo int NOT NULL DEFAULT 0, core61orden int NOT NULL DEFAULT 0, core61pregunta varchar(250) NULL, core61usos int NOT NULL DEFAULT 0)";}
	if ($dbversion==9239){$sSQL="ALTER TABLE cara61compbasepreg ADD PRIMARY KEY(core61id)";}
	if ($dbversion==9240){$sSQL=$objDB->sSQLCrearIndice('cara61compbasepreg', 'cara61compbasepreg_id', 'core61consec', true);}
	if ($dbversion==9241){$sSQL="agregamodulo|2361|23|Competencias base - preguntas|1|2|3|4|5|6|8";}
	if ($dbversion==9242){$sSQL=$u09."(2361, 1, 'Competencias base - preguntas', 'carapruebapreg.php', 2, 2361, 'S', '', '')";}
	if ($dbversion==9243){$sSQL=$u04."(111, 6, 'S'), (111, 14, 'S')";}

	if ($dbversion==9244){$sSQL="add_campos|even53eventojornada|even53horaasistmin int NOT NULL DEFAULT 0|even53minasistmin int NOT NULL DEFAULT 0|even53horaasistmax int NOT NULL DEFAULT 0|even53minasistmax int NOT NULL DEFAULT 0";}
	if ($dbversion==9245){$sSQL="add_campos|even04eventoparticipante|even04idrol int NOT NULL DEFAULT 0|even04grupo int NOT NULL DEFAULT 0";}
	if ($dbversion==9246){$sSQL="add_campos|even02evento|even02codava int NOT NULL DEFAULT 0";}	

	if ($dbversion==9247){$sSQL="add_campos|even02evento|even02mostrarorganizador int NOT NULL DEFAULT 0";}
	if ($dbversion==9248){$sSQL="add_campos|even60eventoasistencia|even60horareg int NOT NULL DEFAULT 0|even60minreg int NOT NULL DEFAULT 0|even60idsesion int NOT NULL DEFAULT 0";}

	if ($dbversion==9249){$sSQL="agregamodulo|4813|1|Usuarios Themis|1|2|3|4|5|6";}
	if ($dbversion==9250){$sSQL=$u09."(4813, 1, 'Usuarios Themis', 'aurethemis.php', 6, 4813, 'S', '', '')";}
	if ($dbversion==9251){$sSQL="add_campos|even60eventoasistencia|even60fechareg int NOT NULL DEFAULT 0";}

	if ($dbversion==9252){$sSQL="CREATE TABLE saiu45rpttotal (saiu45modulo int NOT NULL, saiu45contenedor int NOT NULL, saiu45vigencia int NOT NULL, saiu45mes int NOT NULL, saiu45ciclo int NOT NULL, saiu45bloque int NOT NULL, saiu45escuela int NOT NULL, saiu45programa int NOT NULL, saiu45zona int NOT NULL, saiu45centro int NOT NULL, saiu45tipoconsejeria int NOT NULL, saiu45tipoacad int NOT NULL, saiu45estado int NOT NULL, saiu45estudiante int NOT NULL, saiu45cantidad int NOT NULL DEFAULT 0)";}
	if ($dbversion==9253){$sSQL="ALTER TABLE saiu45rpttotal ADD PRIMARY KEY(saiu45modulo, saiu45contenedor, saiu45vigencia, saiu45mes, saiu45ciclo, saiu45bloque, saiu45escuela, saiu45programa, saiu45zona, saiu45centro, saiu45tipoconsejeria, saiu45tipoacad, saiu45estado, saiu45estudiante)";}
	if ($dbversion==9254){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_estado', 'saiu45estado');}
	if ($dbversion==9255){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_estudiante', 'saiu45estudiante');}
	if ($dbversion==9256){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_modulo', 'saiu45modulo');}
	if ($dbversion==9257){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_vigencia', 'saiu45vigencia');}
	if ($dbversion==9258){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_mes', 'saiu45mes');}
	if ($dbversion==9259){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_ciclo', 'saiu45ciclo');}
	if ($dbversion==9260){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_bloque', 'saiu45bloque');}
	if ($dbversion==9261){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_escuela', 'saiu45escuela');}
	if ($dbversion==9262){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_programa', 'saiu45programa');}
	if ($dbversion==9263){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_zona', 'saiu45zona');}
	if ($dbversion==9264){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_centro', 'saiu45centro');}
	if ($dbversion==9265){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_tipocons', 'saiu45tipoconsejeria');}
	if ($dbversion==9266){$sSQL=$objDB->sSQLCrearIndice('saiu45rpttotal', 'saiu45rpttotal_tipoacad', 'saiu45tipoacad');}

	if ($dbversion==9267){$sSQL="add_campos|comp12procesocompra|comp12forma int NOT NULL DEFAULT 0";}
	if ($dbversion==9268){$sSQL="CREATE TABLE comp26proceso (comp26idproceso int NOT NULL, comp26idunspsc int NOT NULL, comp26id int NOT NULL DEFAULT 0, comp26cantidad Decimal(15,2) NULL DEFAULT 0, comp26moneda int NOT NULL DEFAULT 0, comp26vrunitario Decimal(15,2) NULL DEFAULT 0, comp26porciva int NOT NULL DEFAULT 0, comp26vriva Decimal(15,2) NULL DEFAULT 0, comp26subtotal Decimal(15,2) NULL DEFAULT 0, comp26detalle Text NULL)";}
	if ($dbversion==9269){$sSQL="ALTER TABLE comp26proceso ADD PRIMARY KEY(comp26id)";}
	if ($dbversion==9270){$sSQL=$objDB->sSQLCrearIndice('comp26proceso', 'comp26proceso_id', 'comp26idproceso, comp26idunspsc', true);}
	if ($dbversion==9271){$sSQL=$objDB->sSQLCrearIndice('comp26proceso', 'comp26proceso_padre', 'comp26idproceso');}
	if ($dbversion==9272){$sSQL="agregamodulo|3926|39|PC - UNSPSC|1|2|3|4|5|6|8";}

	if ($dbversion==9273){$sSQL="DROP TABLE teso33planpago";}
	if ($dbversion==9274){$sSQL="DROP TABLE teso34planpagodet";}
	if ($dbversion==9275){$sSQL="CREATE TABLE teso33planpago (teso33vigencia int NOT NULL, teso33consec int NOT NULL, teso33version int NOT NULL, teso33id int NOT NULL DEFAULT 0, teso33estado int NOT NULL DEFAULT 0, teso33origen int NOT NULL DEFAULT 0, teso33idrp int NOT NULL DEFAULT 0, teso33idprocesocctc int NOT NULL DEFAULT 0, teso33idminuta int NOT NULL DEFAULT 0, teso33idresol int NOT NULL DEFAULT 0, teso33idcdp int NOT NULL DEFAULT 0, teso33idbenef int NOT NULL DEFAULT 0, teso33forma int NOT NULL DEFAULT 0, teso33valor Decimal(15,2) NULL DEFAULT 0, teso33vrejec Decimal(15,2) NULL DEFAULT 0, teso33vrpago Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9276){$sSQL="ALTER TABLE teso33planpago ADD PRIMARY KEY(teso33id)";}
	if ($dbversion==9277){$sSQL=$objDB->sSQLCrearIndice('teso33planpago', 'teso33planpago_id', 'teso33vigencia, teso33consec, teso33version', true);}
	if ($dbversion==9278){$sSQL="CREATE TABLE teso34planpagodet (teso34idplanpago int NOT NULL, teso34numero int NOT NULL, teso34id int NOT NULL DEFAULT 0, teso34fechapac int NOT NULL DEFAULT 0, teso34estado int NOT NULL DEFAULT 0, teso34anticipo int NOT NULL DEFAULT 0, teso34porcentaje Decimal(15,2) NULL DEFAULT 0, teso34valorbase Decimal(15,2) NULL DEFAULT 0, teso34valoriva Decimal(15,2) NULL DEFAULT 0, teso34valortotal Decimal(15,2) NULL DEFAULT 0, teso34vrejec Decimal(15,2) NULL DEFAULT 0, teso34fecharadicado int NOT NULL DEFAULT 0, teso34fechaautoriza int NOT NULL DEFAULT 0, teso34vrordenpago Decimal(15,2) NULL DEFAULT 0, teso34vrpago Decimal(15,2) NULL DEFAULT 0, teso34idorden int NOT NULL DEFAULT 0, teso34idegreso int NOT NULL DEFAULT 0)";}
	if ($dbversion==9279){$sSQL="ALTER TABLE teso34planpagodet ADD PRIMARY KEY(teso34id)";}
	if ($dbversion==9280){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_id', 'teso34idplanpago, teso34numero', true);}
	if ($dbversion==9281){$sSQL=$objDB->sSQLCrearIndice('teso34planpagodet', 'teso34planpagodet_padre', 'teso34idplanpago');}
	if ($dbversion==9282){$sSQL="add_campos|unad19depto|unad19matricula int NOT NULL DEFAULT 1|unad19sigla varchar(5) NULL";}
	if ($dbversion==9283){$sSQL=$u04."(2360, 17, 'S'), (2360, 1707, 'S')";}
	if ($dbversion==9284){$sSQL="add_campos|core00params|core00admisionpruebabase int NOT NULL DEFAULT 0|core00admisionxdepto int NOT NULL DEFAULT 0|core00admision_nodepto_es Text NULL|core00admision_nodepto_en Text NULL";}

	if ($dbversion==9285){$sSQL="CREATE TABLE unad96estado (unad96idmodulo int NOT NULL, unad96id int NOT NULL, unad96nombre varchar(50) NULL, unad96etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9286){$sSQL="ALTER TABLE unad96estado ADD PRIMARY KEY(unad96idmodulo, unad96id)";}
	if ($dbversion==9287){$sSQL="CREATE TABLE unad97etiquetas (unad97idmodulo int NOT NULL, unad97idetiqueta int NOT NULL, unad97idioma varchar(2) NOT NULL, unad97valor Text NULL)";}
	if ($dbversion==9288){$sSQL="ALTER TABLE unad97etiquetas ADD PRIMARY KEY(unad97idmodulo, unad97idetiqueta, unad97idioma)";}
	if ($dbversion==9289){$sSQL="agregamodulo|297|2|Etiquetas|1|2|3|4|5|6";}
	if ($dbversion==9290){$sSQL=$u09."(297, 1, 'Etiquetas', 'aureaetiquetas.php', 1511, 297, 'S', '', '')";}
	if ($dbversion==9291){$sSQL="CREATE TABLE corg33idiomas (corg33consec int NOT NULL, corg33id int NOT NULL DEFAULT 0, corg33activa int NOT NULL DEFAULT 0, corg33orden int NOT NULL DEFAULT 0, corg33nombre varchar(50) NULL, corg33aplicacertificados int NOT NULL DEFAULT 0)";}
	if ($dbversion==9292){$sSQL="ALTER TABLE corg33idiomas ADD PRIMARY KEY(corg33id)";}
	if ($dbversion==9293){$sSQL=$objDB->sSQLCrearIndice('corg33idiomas', 'corg33idiomas_id', 'corg33consec', true);}
	if ($dbversion==9294){$sSQL="agregamodulo|4733|22|Idiomas|1|2|3|4|5|6|8";}
	if ($dbversion==9295){$sSQL=$u09."(4733, 1, 'Idiomas', 'coreidiomas.php', 3, 4733, 'S', '', '')";}
	if ($dbversion==9296){$sSQL="CREATE TABLE corg34pruebaidioma (corg34consec int NOT NULL, corg34id int NOT NULL DEFAULT 0, corg34idioma int NOT NULL DEFAULT 0, corg34tipo int NOT NULL DEFAULT 0, corg34activa int NOT NULL DEFAULT 0, corg34nombre varchar(250) NULL, corg34idnav int NOT NULL DEFAULT 0, corg34idaula int NOT NULL DEFAULT 0, corg34codcurso varchar(20) NULL, corg34ext_url varchar(250) NULL, corg34idproducto int NOT NULL DEFAULT 0, corg34idadministrador int NOT NULL DEFAULT 0)";}
	if ($dbversion==9297){$sSQL="ALTER TABLE corg34pruebaidioma ADD PRIMARY KEY(corg34id)";}
	if ($dbversion==9298){$sSQL=$objDB->sSQLCrearIndice('corg34pruebaidioma', 'corg34pruebaidioma_id', 'corg34consec', true);}
	if ($dbversion==9299){$sSQL="agregamodulo|4734|22|Pruebas de idioma|1|2|3|4|5|6|8";}
	if ($dbversion==9300){$sSQL=$u09."(4734, 1, 'Pruebas de idioma', 'corepruebasidioma.php', 2207, 4734, 'S', '', '')";}
}
if (($dbversion>9300)&&($dbversion<9401)){
	if ($dbversion==9301){$sSQL="CREATE TABLE corg35certidioma (corg35idtercero int NOT NULL, corg35ididioma int NOT NULL, corg35consec int NOT NULL, corg35id int NOT NULL DEFAULT 0, corg35estado int NOT NULL DEFAULT 0, corg35idprueba int NOT NULL DEFAULT 0, corg35emisor varchar(250) NULL, corg35idnav int NOT NULL DEFAULT 0, corg35idaula int NOT NULL DEFAULT 0, corg35grupo varchar(20) NULL, corg35fechaapertura int NOT NULL DEFAULT 0, corg35fechacierre int NOT NULL DEFAULT 0, corg35origencert int NOT NULL DEFAULT 0, corg35anexocert int NOT NULL DEFAULT 0, corg35fechacertificado int NOT NULL DEFAULT 0, corg35fechavencimiento int NOT NULL DEFAULT 0, corg35puntaje int NOT NULL DEFAULT 0, corg35resultado int NOT NULL DEFAULT 0, corg35idusuario int NOT NULL DEFAULT 0)";}
	if ($dbversion==9302){$sSQL="ALTER TABLE corg35certidioma ADD PRIMARY KEY(corg35id)";}
	if ($dbversion==9303){$sSQL=$objDB->sSQLCrearIndice('corg35certidioma', 'corg35certidioma_id', 'corg35idtercero, corg35ididioma, corg35consec', true);}
	if ($dbversion==9304){$sSQL="agregamodulo|4735|22|Certificados de idioma|1|2|3|4|5|6|8";}
	if ($dbversion==9305){$sSQL=$u09."(4735, 1, 'Certificados de idioma', 'corecertificadoidioma.php', 2207, 4735, 'S', '', '')";}
	if ($dbversion==9306){$sSQL="CREATE TABLE corg36certidiomanota (corg36idprueba int NOT NULL, corg36consec int NOT NULL, corg36id int NOT NULL DEFAULT 0, corg36publica int NOT NULL DEFAULT 0, corg36nota Text NULL, corg36fecha int NOT NULL DEFAULT 0, corg36hora int NOT NULL DEFAULT 0, corg36minuto int NOT NULL DEFAULT 0, corg36idusuario int NOT NULL DEFAULT 0, corg36idrespuesta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9307){$sSQL="ALTER TABLE corg36certidiomanota ADD PRIMARY KEY(corg36id)";}
	if ($dbversion==9308){$sSQL=$objDB->sSQLCrearIndice('corg36certidiomanota', 'corg36certidiomanota_id', 'corg36idprueba, corg36consec', true);}
	if ($dbversion==9309){$sSQL=$objDB->sSQLCrearIndice('corg36certidiomanota', 'corg36certidiomanota_padre', 'corg36idprueba');}
	if ($dbversion==9310){$sSQL="agregamodulo|4736|22|Certificados idioma-anotacion|1|2|3|4|5|6|8";}
	if ($dbversion==9311){$sSQL=$u96."(4735, 0, 'Borrador', 100), (4735, 1, 'Radicado', 101), 
		(4735, 3, 'Solicitado', 103), (4735, 5, 'Pagado', 105), 
		(4735, 7, 'Habilitado', 107), (4735, 9, 'Presentado', 109), 
		(4735, 17, 'Finalizado', 117), (4735, 19, 'No aceptado', 119)";}
	if ($dbversion==9312){$sSQL=$u08."(2207, 'Idiomas', 'gm.php?id=2207', 'Idiomas', 'Languages', 'L&iacute;nguas')";}

	if ($dbversion==9313){$sSQL="INSERT INTO corg33idiomas (corg33consec, corg33id, corg33activa, corg33orden, corg33nombre, corg33aplicacertificados) VALUES (0, 0, 0, 0, '{Ninguno}', 0)";}
	if ($dbversion==9314){$sSQL="INSERT INTO corg34pruebaidioma (corg34consec, corg34id, corg34idioma, corg34tipo, corg34activa, corg34nombre, corg34idnav, corg34idaula, corg34codcurso, corg34ext_url, corg34idproducto, corg34idadministrador) VALUES (0, 0, 0, 0, 0, '{Ninguna}', 0, 0, '', '', 0, 0)";}

	if ($dbversion==9315){$sSQL="mod_quitar|147";}
	//if ($dbversion==9316){$sSQL=$u96."(4735, 0, 'Borrador', 101), (4735, 1, 'Radicado', 102), (4735, 3, 'Solicitado', 103), (4735, 5, 'Pagado', 104), (4735, 7, 'Habilitado', 105), (4735, 9, 'Presentado', 106), (4735, 17, 'Finalizado', 107), (4735, 19, 'No aceptado', 108)";}
	if ($dbversion==9317){$sSQL="CREATE TABLE corg73nivelidioma (corg73id int NOT NULL, corg73nombre varchar(50) NULL)";}
	if ($dbversion==9318){$sSQL="ALTER TABLE corg73nivelidioma ADD PRIMARY KEY(corg73id)";}
	if ($dbversion==9319){$sSQL="INSERT INTO corg73nivelidioma (corg73id, corg73nombre) VALUES (0, '-'), (11, 'A1'), (16, 'A2'), (21, 'B1'), (23, 'B1+'), (26, 'B2'), (31, 'C1')";}

	if ($dbversion==9320){$sSQL="CREATE TABLE sine26rubrica (sine26consec int NOT NULL, sine26id int NOT NULL DEFAULT 0, sine26vigente int NOT NULL DEFAULT 0, sine26titulo varchar(100) NULL, sine26pesototal int NOT NULL DEFAULT 0, sine26puntajemax int NOT NULL DEFAULT 0)";}
	if ($dbversion==9321){$sSQL="ALTER TABLE sine26rubrica ADD PRIMARY KEY(sine26id)";}
	if ($dbversion==9322){$sSQL=$objDB->sSQLCrearIndice('sine26rubrica', 'sine26rubrica_id', 'sine26consec', true);}
	if ($dbversion==9323){$sSQL="agregamodulo|4926|22|Admisiones - Rubricas|1|2|3|4|5|6|8";}
	if ($dbversion==9324){$sSQL=$u09."(4926, 1, 'Admisiones - Rubricas', 'sinerubrica.php', 3, 4926, 'S', '', '')";}
	if ($dbversion==9325){$sSQL="CREATE TABLE sine27rubpreg (sine27idrubrica int NOT NULL, sine27consec int NOT NULL, sine27id int NOT NULL DEFAULT 0, sine27orden int NOT NULL DEFAULT 0, sine27peso int NOT NULL DEFAULT 0, sine27activa int NOT NULL DEFAULT 0, sine27titulo varchar(100) NULL, sine27detalle Text NULL)";}
	if ($dbversion==9326){$sSQL="ALTER TABLE sine27rubpreg ADD PRIMARY KEY(sine27id)";}
	if ($dbversion==9327){$sSQL=$objDB->sSQLCrearIndice('sine27rubpreg', 'sine27rubpreg_id', 'sine27idrubrica, sine27consec', true);}
	if ($dbversion==9328){$sSQL=$objDB->sSQLCrearIndice('sine27rubpreg', 'sine27rubpreg_padre', 'sine27idrubrica');}
	if ($dbversion==9329){$sSQL="agregamodulo|4927|22|Preguntas|1|2|3|4|5|6|8";}

	if ($dbversion==9330){$sSQL=$u04."(1757, 12, 'S'), (1757, 1701, 'S'), (1757, 1707, 'S')";}
	if ($dbversion==9331){$sSQL="add_campos|unad11terceros_md|unad11zipcode varchar(20) NULL";}
	if ($dbversion==9332){$sSQL="UPDATE unad09modulomenu SET unad09pagina='unadperiodo.php' WHERE unad09idmodulo=146";}
	if ($dbversion==9333){$sSQL="UPDATE unad02modulos SET unad02idsistema=22 WHERE unad02id=140";}
	if ($dbversion==9334){$sSQL="UPDATE unad09modulomenu SET unad09grupo=2200 WHERE unad09idmodulo=2209";}
	if ($dbversion==9335){$sSQL="UPDATE unad09modulomenu SET unad09nombre='Planes de estudio', unad09pagina='coreplanest.php' WHERE unad09idmodulo=2210";}
	if ($dbversion==9336){$sSQL="add_campos|core26espejos|core26idescuela int NOT NULL";}
	if ($dbversion==9337){$sSQL="ALTER TABLE core26espejos DROP INDEX core26espejos_id";}
	if ($dbversion==9338){$sSQL="ALTER TABLE core26espejos ADD UNIQUE INDEX core26espejos_id(core26idzona, core26idescuela, core26idtipoespejo, core26idtercero)";}
	if ($dbversion==9339){$sSQL=$u04."(2209, 12, 'S'), (2209, 14, 'S'), (2209, 1707, 'S')";}
	if ($dbversion==9340){$sSQL=$u04."(1756, 12, 'S'), (1756, 111, 'S'), (1756, 1701, 'S'), (1756, 1707, 'S')";}
	// 13 de Enero de 2026
	if ($dbversion==9341){$sSQL="add_campos|grad25tipoanexoproyecto|grad25nivelaplica int NOT NULL DEFAULT 0";}
	if ($dbversion==9342){$sSQL="agregamodulo|2232|27|Asignación de director GAIIPU|1|2|3|4|5|6";}
	if ($dbversion==9343){$sSQL=$u09."(2232, 1, 'Asignación de director GAIIPU', 'coreactadirectorgaiipu.php', 2203, 2232, 'S', '', '')";}
	if ($dbversion==9344){$sSQL="agregamodulo|2762|27|Gestion Académica Doctorado|1|2|3|4|5|6|8";}
	if ($dbversion==9345){$sSQL=$u09."(2762, 1, 'Gestión Académica Doctorado', 'gradproydoctorado.php', 2203, 2762, 'S', '', '')";}
	if ($dbversion==9346){$sSQL="INSERT INTO grad16estadoproy (grad16id, grad16nombre) VALUES (-15, 'Iniciado'), (-10, 'En progreso'), (-5, 'GAIIPU Terminado')";}

	if ($dbversion==9347){$sSQL="CREATE TABLE cart08recaudoaplica (cart08idrecaudo int NOT NULL, cart08idfactura int NOT NULL, cart08id int NOT NULL DEFAULT 0, cart08valoraplica Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9348){$sSQL="ALTER TABLE cart08recaudoaplica ADD PRIMARY KEY(cart08id)";}
	if ($dbversion==9349){$sSQL=$objDB->sSQLCrearIndice('cart08recaudoaplica', 'cart08recaudoaplica_id', 'cart08idrecaudo, cart08idfactura', true);}
	if ($dbversion==9350){$sSQL="add_campos|unae26unidadesfun|unae26compras_estricta int NOT NULL DEFAULT 0";}
	if ($dbversion==9351){$sSQL="add_campos|saiu60estadotramite|saiu60idetiqueta int NOT NULL DEFAULT 0";}
	if ($dbversion==9352){$sSQL="add_campos|unad21estadocivil|unad21etiqueta int NOT NULL DEFAULT 0";}
	if ($dbversion==9353){$sSQL=$u04."(3621, 10, 'S'), (3621, 17, 'S')";}
	if ($dbversion==9354){$sSQL="add_campos|unad10vigencia|unad10pptvigencia int NOT NULL DEFAULT 0";}

	if ($dbversion==9355){$sSQL="DROP TABLE cttc37minutas";}
	if ($dbversion==9356){$sSQL="CREATE TABLE cttc37minutas (cttc37vigencia int NOT NULL, cttc37prefijo int NOT NULL, cttc37consec int NOT NULL, cttc37version int NOT NULL, cttc37id int NOT NULL DEFAULT 0, cttc37tipominuta int NOT NULL DEFAULT 0, cttc37fechaminuta int NOT NULL DEFAULT 0, cttc37estado int NOT NULL DEFAULT 0, cttc37etiqueta varchar(30) NULL, cttc37idproceso int NOT NULL DEFAULT 0, cttc37idcontratista int NOT NULL DEFAULT 0, cttc37idjuridico int NOT NULL DEFAULT 0, cttc37objeto Text NULL, cttc37idaprueba int NOT NULL DEFAULT 0, cttc37idanula int NOT NULL DEFAULT 0, cttc37motivoanula Text NULL, cttc37fechainicio int NOT NULL DEFAULT 0, cttc37fechatermina_ant int NOT NULL DEFAULT 0, cttc37fechatermina_nueva int NOT NULL DEFAULT 0, cttc37diasduracion int NOT NULL DEFAULT 0, cttc37vranterior Decimal(15,2) NULL DEFAULT 0, cttc37vrminuta Decimal(15,2) NULL DEFAULT 0, cttc37vrfinal Decimal(15,2) NULL DEFAULT 0, cttc37idcdp int NOT NULL DEFAULT 0, cttc37idplanpago int NOT NULL DEFAULT 0, cttc37idrp int NOT NULL DEFAULT 0)";}
	if ($dbversion==9357){$sSQL="ALTER TABLE cttc37minutas ADD PRIMARY KEY(cttc37id)";}
	if ($dbversion==9358){$sSQL=$objDB->sSQLCrearIndice('cttc37minutas', 'cttc37minutas_id', 'cttc37vigencia, cttc37prefijo, cttc37consec, cttc37version', true);}
	if ($dbversion==9359){$sSQL="CREATE TABLE cttc86tipominuta (cttc86id int NOT NULL, cttc86nombre varchar(50) NULL, cttc86sumatiempo int NOT NULL DEFAULT 0, cttc86sumadinero int NOT NULL DEFAULT 0, cttc86etiqueta int NOT NULL DEFAULT 0)";}
	if ($dbversion==9360){$sSQL="ALTER TABLE cttc86tipominuta ADD PRIMARY KEY(cttc86id)";}
	if ($dbversion==9361){$sSQL="INSERT INTO cttc86tipominuta (cttc86id, cttc86nombre, cttc86sumatiempo, cttc86sumadinero, cttc86etiqueta) VALUES 
		(-1, 'Resolución Interna', 0, 0, 99), 
		(0, 'Contrato base', 0, 0, 100), 
		(1, 'Adición en valor', 0, 1, 101), 
		(2, 'Adición en tiempo', 1, 0, 102), 
		(11, 'Adición en valor y tiempo', 1, 1, 111), 
		(21, 'Otro sí que no adiciona tiempo ni valor', 0, 0, 121)";}
	if ($dbversion==9362){$sSQL="add_campos|grad41postulaciones|grad41prog_idzona int NOT NULL DEFAULT 0|grad41prog_idcentro int NOT NULL DEFAULT 0";}
	if ($dbversion==9363){$sSQL="add_campos|grad11proyecto|grad11t_85 int NOT NULL DEFAULT 0|grad11t_90 int NOT NULL DEFAULT 0";}

	if ($dbversion==9364){$sSQL="add_campos|cttc37minutas|cttc37numobliga int NOT NULL DEFAULT 0|cttc37avance Decimal(15,2) NULL DEFAULT 0|cttc37pago_porc Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==9365){$sSQL="CREATE TABLE cttc41obligaciones (cttc41idminuta int NOT NULL, cttc41idminorigen int NOT NULL, cttc41numero int NOT NULL, cttc41version int NOT NULL, cttc41id int NOT NULL DEFAULT 0, cttc41descripcion Text NULL, cttc41tipoobligacion int NOT NULL DEFAULT 0, cttc41tipoentregable int NOT NULL DEFAULT 0, cttc41cantidad Decimal(15,2) NULL DEFAULT 0, cttc41fechainclusion int NOT NULL DEFAULT 0, cttc41fecharetiro int NOT NULL DEFAULT 0, cttc41peso int NOT NULL DEFAULT 0, cttc41peso_porc Decimal(15,2) NULL DEFAULT 0, cttc41avance Decimal(15,2) NULL DEFAULT 0, cttc41aporte Decimal(15,2) NULL DEFAULT 0, cttc41estimado1 Decimal(15,2) NULL DEFAULT 0, cttc41estimado2 Decimal(15,2) NULL DEFAULT 0, cttc41estimado3 Decimal(15,2) NULL DEFAULT 0, cttc41estimado4 Decimal(15,2) NULL DEFAULT 0, cttc41estimado5 Decimal(15,2) NULL DEFAULT 0, cttc41estimado6 Decimal(15,2) NULL DEFAULT 0, cttc41estimado7 Decimal(15,2) NULL DEFAULT 0, cttc41estimado8 Decimal(15,2) NULL DEFAULT 0, cttc41estimado9 Decimal(15,2) NULL DEFAULT 0, cttc41estimado10 Decimal(15,2) NULL DEFAULT 0, cttc41estimado11 Decimal(15,2) NULL DEFAULT 0, cttc41estimado12 Decimal(15,2) NULL DEFAULT 0, cttc41estimado13 Decimal(15,2) NULL DEFAULT 0, cttc41estimado14 Decimal(15,2) NULL DEFAULT 0, cttc41estimado15 Decimal(15,2) NULL DEFAULT 0, cttc41reportado1 Decimal(15,2) NULL DEFAULT 0, cttc41reportado2 Decimal(15,2) NULL DEFAULT 0, cttc41reportado3 Decimal(15,2) NULL DEFAULT 0, cttc41reportado4 Decimal(15,2) NULL DEFAULT 0, cttc41reportado5 Decimal(15,2) NULL DEFAULT 0, cttc41reportado6 Decimal(15,2) NULL DEFAULT 0, cttc41reportado7 Decimal(15,2) NULL DEFAULT 0, cttc41reportado8 Decimal(15,2) NULL DEFAULT 0, cttc41reportado9 Decimal(15,2) NULL DEFAULT 0, cttc41reportado10 Decimal(15,2) NULL DEFAULT 0, cttc41reportado11 Decimal(15,2) NULL DEFAULT 0, cttc41reportado12 Decimal(15,2) NULL DEFAULT 0, cttc41reportado13 Decimal(15,2) NULL DEFAULT 0, cttc41reportado14 Decimal(15,2) NULL DEFAULT 0, cttc41reportado15 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9366){$sSQL="ALTER TABLE cttc41obligaciones ADD PRIMARY KEY(cttc41idminuta, cttc41idminorigen, cttc41numero, cttc41version)";}
	if ($dbversion==9367){$sSQL=$objDB->sSQLCrearIndice('cttc41obligaciones', 'cttc41obligaciones_padre', 'cttc41idminuta');}
	if ($dbversion==9368){$sSQL="agregamodulo|4141|41|Minutas - Obligaciones|1|2|3|4|5|6";}

	if ($dbversion==9369){$sSQL="UPDATE saiu60estadotramite SET saiu60nombre='Oficio remisorio y Formato Resumen de Recaudos' WHERE saiu60id=6";}
	if ($dbversion==9370){$sSQL="CREATE TABLE olab77consolidadocp (olab77idmatricula int NOT NULL, olab77id int NOT NULL DEFAULT 0, olab77estado int NOT NULL DEFAULT 0, olab77proc1_curso int NOT NULL DEFAULT 0, olab77proc1_insc int NOT NULL DEFAULT 0, olab77proc1_sesiones int NOT NULL DEFAULT 0, olab77proc1_asistencias int NOT NULL DEFAULT 0, olab77proc2_curso int NOT NULL DEFAULT 0, olab77proc2_insc int NOT NULL DEFAULT 0, olab77proc2_sesiones int NOT NULL DEFAULT 0, olab77proc2_asistencias int NOT NULL DEFAULT 0, olab77proc3_curso int NOT NULL DEFAULT 0, olab77proc3_insc int NOT NULL DEFAULT 0, olab77proc3_sesiones int NOT NULL DEFAULT 0, olab77proc3_asistencias int NOT NULL DEFAULT 0, olab77proc4_curso int NOT NULL DEFAULT 0, olab77proc4_insc int NOT NULL DEFAULT 0, olab77proc4_sesiones int NOT NULL DEFAULT 0, olab77proc4_asistencias int NOT NULL DEFAULT 0, olab77proc5_curso int NOT NULL DEFAULT 0, olab77proc5_insc int NOT NULL DEFAULT 0, olab77proc5_sesiones int NOT NULL DEFAULT 0, olab77proc5_asistencias int NOT NULL DEFAULT 0, olab77proc6_curso int NOT NULL DEFAULT 0, olab77proc6_insc int NOT NULL DEFAULT 0, olab77proc6_sesiones int NOT NULL DEFAULT 0, olab77proc6_asistencias int NOT NULL DEFAULT 0, olab77proc7_curso int NOT NULL DEFAULT 0, olab77proc7_insc int NOT NULL DEFAULT 0, olab77proc7_sesiones int NOT NULL DEFAULT 0, olab77proc7_asistencias int NOT NULL DEFAULT 0, olab77proc8_curso int NOT NULL DEFAULT 0, olab77proc8_insc int NOT NULL DEFAULT 0, olab77proc8_sesiones int NOT NULL DEFAULT 0, olab77proc8_asistencias int NOT NULL DEFAULT 0)";}
	if ($dbversion==9371){$sSQL="ALTER TABLE olab77consolidadocp ADD PRIMARY KEY(olab77idmatricula)";}
	if ($dbversion==9372){$sSQL="agregamodulo|2177|21|Consolidado de comp práctico|1|5|6";}
	if ($dbversion==9373){$sSQL=$u09."(2177, 1, 'Consolidado componente práctico', 'olabconsolidadocp.php', 11, 2177, 'S', '', '')";}
	//9374 -9376 quedan libres.

	if ($dbversion==9377){$sSQL="agregamodulo|2650|26|Resoluciones|1|2|3|4|5|6";}
	if ($dbversion==9378){$sSQL=$u09."(2650, 1, 'Resoluciones', 'gedoresoluciones.php', 2601, 2650, 'S', '', '')";}
	if ($dbversion==9379){$sSQL=$u96."(2650, 0, 'Borrador', 100), 
		(2650, 3, 'Solicitada', 103), 
		(2650, 5, 'Radicada', 105), 
		(2650, 7, 'Completa', 107), 
		(2650, 8, 'No aceptada', 108), 
		(2650, 9, 'Anulada', 109)";}
	
	if ($dbversion==9380){$sSQL="DROP TABLE gedo50resoluciones";}
	if ($dbversion==9381){$sSQL="CREATE TABLE gedo50resoluciones (gedo50vigencia int NOT NULL, gedo50numsol int NOT NULL, gedo50id int NOT NULL DEFAULT 0, gedo50origen_proceso int NOT NULL DEFAULT 0, gedo50origen_comp varchar(20) NULL, gedo50origen_id int NOT NULL DEFAULT 0, gedo50estado int NOT NULL DEFAULT 0, gedo50unidad int NOT NULL DEFAULT 0, gedo50escuela int NOT NULL DEFAULT 0, gedo50zona int NOT NULL DEFAULT 0, gedo50centro int NOT NULL DEFAULT 0, gedo50asunto Text NULL, gedo50fechasolicitada int NOT NULL DEFAULT 0, gedo50salida_id int NOT NULL DEFAULT 0, gedo50salida_fecha int NOT NULL DEFAULT 0, gedo50salida_numero int NOT NULL DEFAULT 0, gedo50beneficiario_id int NOT NULL DEFAULT 0, gedo50beneficiario_vr Decimal(15,2) NULL DEFAULT 0, gedo50beneficiario_4x1000 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==9382){$sSQL="ALTER TABLE gedo50resoluciones ADD PRIMARY KEY(gedo50id)";}
	if ($dbversion==9383){$sSQL=$objDB->sSQLCrearIndice('gedo50resoluciones', 'gedo50resoluciones_id', 'gedo50vigencia, gedo50numsol', true);}

	if ($dbversion==9384){$sSQL="CREATE TABLE unaf25seccional (unaf25codigo int NOT NULL, unaf25id int NOT NULL DEFAULT 0, unaf25sigla varchar(20) NULL, unaf25activa int NOT NULL DEFAULT 0, unaf25nombre varchar(250) NULL, unaf25dominio varchar(250) NULL, unaf25rutacampus varchar(250) NULL, unaf25rutaws varchar(250) NULL, unaf25llave varchar(50) NULL, unaf25director int NOT NULL DEFAULT 0, unaf25administrador int NOT NULL DEFAULT 0)";}
	if ($dbversion==9385){$sSQL="ALTER TABLE unaf25seccional ADD PRIMARY KEY(unaf25id)";}
	if ($dbversion==9386){$sSQL=$objDB->sSQLCrearIndice('unaf25seccional', 'unaf25seccional_id', 'unaf25codigo', true);}
	if ($dbversion==9387){$sSQL="agregamodulo|4325|1|Seccionales|1|2|3|4|5|6|8";}
	if ($dbversion==9388){$sSQL=$u09."(4325, 1, 'Seccionales', 'unadseccional.php', 3, 4325, 'S', '', '')";}

	if ($dbversion==9389){$sSQL=$objDB->sSQLCrearIndice('core01estprograma', 'core01estprograma_continuidad', 'core01contestado');}
	if ($dbversion==9390){$sSQL=$objDB->sSQLCrearIndice('core01estprograma', 'core01estprograma_importa', 'core01idimporta');}
	//24 - feb - 2026
	if ($dbversion==9391){$sSQL="DROP TABLE gcmo03indicador";}
	if ($dbversion==9392){$sSQL="CREATE TABLE gcmo03indicador (gcmo03idproceso int NOT NULL, gcmo03codigo varchar(20) NOT NULL, gcmo03version int NOT NULL, gcmo03id int NOT NULL DEFAULT 0, gcmo03activo int NOT NULL DEFAULT 0, gcmo03nombre varchar(200) NULL, gcmo03tipo int NOT NULL DEFAULT 0, gcmo03numvariables int NOT NULL DEFAULT 0, gcmo03proposito Text NULL, gcmo03aporte Text NULL, gcmo03estructura int NOT NULL DEFAULT 0, gcmo03fuente int NOT NULL DEFAULT 0, gcmo03var1_id int NOT NULL DEFAULT 0, gcmo03var1_nombre varchar(250) NULL, gcmo03var2_id int NOT NULL DEFAULT 0, gcmo03var2_nombre varchar(250) NULL, gcmo03interpretacion Text NULL, gcmo03unidadmedida int NOT NULL DEFAULT 0, gcmo03tendencia int NOT NULL DEFAULT 0, gcmo03periodicidad int NOT NULL DEFAULT 0, gcmo03periodicidad_analisis int NOT NULL DEFAULT 0, gcmo03unidresponsable int NOT NULL DEFAULT 0, gcmo03gruporesponsable int NOT NULL DEFAULT 0, gcmo03idesponsable int NOT NULL DEFAULT 0, gcmo03fechacreacion int NOT NULL DEFAULT 0, gcmo03fechacierre int NOT NULL DEFAULT 0, gcmo03nivelreporte int NOT NULL DEFAULT 0, gcmo03dig_agno int NOT NULL DEFAULT 0, gcmo03dig_cohorte int NOT NULL DEFAULT 0, gcmo03dig_bloque int NOT NULL DEFAULT 0, gcmo03dig_zona int NOT NULL DEFAULT 0, gcmo03dig_centro int NOT NULL DEFAULT 0, gcmo03dig_unidadf int NOT NULL DEFAULT 0, gcmo03dig_escuela int NOT NULL DEFAULT 0, gcmo03dig_programa int NOT NULL DEFAULT 0, gcmo03dig_personal int NOT NULL DEFAULT 0)";}
	if ($dbversion==9393){$sSQL="ALTER TABLE gcmo03indicador ADD PRIMARY KEY(gcmo03id)";}
	if ($dbversion==9394){$sSQL=$objDB->sSQLCrearIndice('gcmo03indicador', 'gcmo03indicador_id', 'gcmo03idproceso, gcmo03codigo, gcmo03version', true);}
	//25 - feb - 2026
	if ($dbversion==9395){$sSQL="add_campos|even02evento|even02idorigeninforme int NOT NULL DEFAULT 0|even02idinforme int NOT NULL DEFAULT 0";}
	if ($dbversion==9396){$sSQL="add_campos|even53eventojornada|even53idorigen int NOT NULL DEFAULT 0|even53idplanificacion int NOT NULL DEFAULT 0|even53publicaplanifica int NOT NULL DEFAULT 0";}
	if ($dbversion==9397){$sSQL=$u96."(4137, 0, 'En elaboración', 100), (4137, 5, 'En Aprobación', 105), (4137, 7, 'Aprobada', 107), (4137, 9, 'Anulada', 109), (4137, 11, 'En firmas', 111), (4137, 17, 'Finalizada', 117)";}
	if ($dbversion==9398){$sSQL="DROP TABLE cttc84estadominuta";}
	if ($dbversion==9399){$sSQL="CREATE TABLE visa34convtipo (visa34consec int NOT NULL, visa34id int NOT NULL DEFAULT 0, visa34nombre varchar(100) NULL, visa34rolestudiante int NOT NULL DEFAULT 0, visa34roladministrativo int NOT NULL DEFAULT 0, visa34rolacademico int NOT NULL DEFAULT 0, visa34rolaspirante int NOT NULL DEFAULT 0, visa34rolegresado int NOT NULL DEFAULT 0, visa34rolexterno int NOT NULL DEFAULT 0, visa34grupotipologia int NOT NULL DEFAULT 0, visa34activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9400){$sSQL="ALTER TABLE visa34convtipo ADD PRIMARY KEY(visa34id)";}
	}
if (($dbversion>9400)&&($dbversion<9501)){
	if ($dbversion==9401){$sSQL=$objDB->sSQLCrearIndice('visa34convtipo', 'visa34convtipo_id', 'visa34consec', true);}
	if ($dbversion==9402){$sSQL="agregamodulo|2934|29|Tipo de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9403){$sSQL=$u09."(2934, 1, 'Tipo de convocatoria', 'visaeconvtipo.php', 2908, 2934, 'S', '', '')";}
	if ($dbversion==9404){$sSQL=$u08."(2908, 'Convocatorias', 'gm.php?id=2908', 'Convocatorias', 'Convocation', 'Editais')";}
	if ($dbversion==9405){$sSQL="CREATE TABLE visa35convocatoria (visa35consec int NOT NULL, visa35id int NOT NULL DEFAULT 0, visa35idtipo int NOT NULL DEFAULT 0, visa35nombre varchar(250) NULL, visa35idzona int NOT NULL DEFAULT 0, visa35idcentro int NOT NULL DEFAULT 0, visa35idescuela int NOT NULL DEFAULT 0, visa35idprograma int NOT NULL DEFAULT 0, visa35estado int NOT NULL DEFAULT 0, visa35numcupos int NOT NULL DEFAULT 0, visa35fecha_apertura int NOT NULL DEFAULT 0, visa35fecha_liminscrip int NOT NULL DEFAULT 0, visa35fecha_limrevdoc int NOT NULL DEFAULT 0, visa35fecha_examenes int NOT NULL DEFAULT 0, visa35fecha_seleccion int NOT NULL DEFAULT 0, visa35fecha_ratificacion int NOT NULL DEFAULT 0, visa35fecha_cierra int NOT NULL DEFAULT 0, visa35presentacion Text NULL, visa35total_inscritos int NOT NULL DEFAULT 0, visa35total_autorizados int NOT NULL DEFAULT 0, visa35total_presentaex int NOT NULL DEFAULT 0, visa35total_aprobados int NOT NULL DEFAULT 0, visa35total_admitidos int NOT NULL DEFAULT 0, visa35idconvenio int NOT NULL DEFAULT 0, visa35idresolucion int NOT NULL DEFAULT 0)";}
	if ($dbversion==9406){$sSQL="ALTER TABLE visa35convocatoria ADD PRIMARY KEY(visa35id)";}
	if ($dbversion==9407){$sSQL=$objDB->sSQLCrearIndice('visa35convocatoria', 'visa35convocatoria_id', 'visa35consec', true);}
	if ($dbversion==9408){$sSQL="agregamodulo|2935|29|Convocatorias|1|2|3|4|5|6";}
	if ($dbversion==9409){$sSQL=$u09."(2935, 1, 'Convocatorias', 'visaeconvocatoria.php', 2908, 2935, 'S', '', '')";}
	//26 - feb - 2026
	if ($dbversion==9410){$sSQL="CREATE TABLE gedo00config (gedo00id int NOT NULL, gedo00idexpacad int NOT NULL DEFAULT 0, gedo00idtiporesol int NOT NULL DEFAULT 0)";}
	if ($dbversion==9411){$sSQL="ALTER TABLE gedo00config ADD PRIMARY KEY(gedo00id)";}
	if ($dbversion==9412){$sSQL="agregamodulo|2600|26|Parametros GD|1|3";}
	if ($dbversion==9413){$sSQL=$u09."(2600, 1, 'Parametros', 'gedoparams.php', 2, 2600, 'S', '', '')";}
	if ($dbversion==9414){$sSQL="INSERT INTO gedo00config (gedo00id, gedo00idexpacad, gedo00idtiporesol) VALUES (1, 0, 0)";}
	//27 - feb - 2026
	if ($dbversion==9415){$sSQL="add_campos|visa34convtipo|visa34aplicazona int NOT NULL DEFAULT 0|visa34aplicacentro int NOT NULL DEFAULT 0|visa34aplicaescuela int NOT NULL DEFAULT 0|visa34aplicaprograma int NOT NULL DEFAULT 0";}
	if ($dbversion==9416){$sSQL="CREATE TABLE visa42convanexo (visa42idtipo int NOT NULL, visa42consec int NOT NULL, visa42id int NOT NULL DEFAULT 0, visa42titulo varchar(50) NULL, visa42descripcion varchar(250) NULL, visa42activo int NOT NULL DEFAULT 0, visa42orden int NOT NULL DEFAULT 0, visa42obligatorio int NOT NULL DEFAULT 0, visa42tipodocumento int NOT NULL DEFAULT 0)";}
	if ($dbversion==9417){$sSQL="ALTER TABLE visa42convanexo ADD PRIMARY KEY(visa42id)";}
	if ($dbversion==9418){$sSQL=$objDB->sSQLCrearIndice('visa42convanexo', 'visa42convanexo_id', 'visa42idtipo, visa42consec', true);}
	if ($dbversion==9419){$sSQL=$objDB->sSQLCrearIndice('visa42convanexo', 'visa42convanexo_padre', 'visa42idtipo');}
	if ($dbversion==9420){$sSQL="agregamodulo|2942|29|Tipo convocatoria - Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==9421){$sSQL="CREATE TABLE visa46grupotipologia (visa46consec int NOT NULL, visa46id int NOT NULL DEFAULT 0, visa46nombre varchar(50) NULL)";}
	if ($dbversion==9422){$sSQL="ALTER TABLE visa46grupotipologia ADD PRIMARY KEY(visa46id)";}
	if ($dbversion==9423){$sSQL=$objDB->sSQLCrearIndice('visa46grupotipologia', 'visa46grupotipologia_id', 'visa46consec', true);}
	if ($dbversion==9424){$sSQL="agregamodulo|2946|29|Grupos de tipologias|1|2|3|4|5|6|8";}
	if ($dbversion==9425){$sSQL=$u09."(2946, 1, 'Grupos de tipologias', 'visaegrupotipologia.php', 2, 2946, 'S', '', '')";}
	if ($dbversion==9426){$sSQL="CREATE TABLE visa36convtipologia (visa36idgrupo int NOT NULL, visa36consec int NOT NULL, visa36id int NOT NULL DEFAULT 0, visa36nombre varchar(50) NULL, visa36activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9427){$sSQL="ALTER TABLE visa36convtipologia ADD PRIMARY KEY(visa36id)";}
	if ($dbversion==9428){$sSQL=$objDB->sSQLCrearIndice('visa36convtipologia', 'visa36convtipologia_id', 'visa36idgrupo, visa36consec', true);}
	if ($dbversion==9429){$sSQL="agregamodulo|2936|29|Tipologias de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9430){$sSQL=$u09."(2936, 1, 'Tipologias de convocatoria', 'visaetipologia.php', 2908, 2936, 'S', '', '')";}
	if ($dbversion==9431){$sSQL="CREATE TABLE visa37convsubtipo (visa37idtipologia int NOT NULL, visa37id int NOT NULL DEFAULT 0, visa37nombre varchar(50) NULL, visa37activo int NOT NULL DEFAULT 0)";}
	if ($dbversion==9432){$sSQL="ALTER TABLE visa37convsubtipo ADD PRIMARY KEY(visa37id)";}
	if ($dbversion==9433){$sSQL=$objDB->sSQLCrearIndice('visa37convsubtipo', 'visa37convsubtipo_id', 'visa37idtipologia', true);}
	if ($dbversion==9434){$sSQL=$objDB->sSQLCrearIndice('visa37convsubtipo', 'visa37convsubtipo_padre', 'visa37idtipologia');}
	if ($dbversion==9435){$sSQL="agregamodulo|2937|29|Subtipologias de convocatorias|1|2|3|4|5|6|8";}
	if ($dbversion==9436){$sSQL="add_campos|visa35convocatoria|visa35gruponivel int NOT NULL DEFAULT 0|visa35nivelforma int NOT NULL DEFAULT 0|visa35idproducto int NOT NULL DEFAULT 0";}
	if ($dbversion==9437){$sSQL="CREATE TABLE visa38convpruebas (visa38idtipo int NOT NULL, visa38consec int NOT NULL, visa38id int NOT NULL DEFAULT 0, visa38nombre varchar(50) NULL, visa38tipoprueba int NOT NULL DEFAULT 0, visa38puntajemaximo int NOT NULL DEFAULT 0, visa38puntajeaproba int NOT NULL DEFAULT 0, visa38activo int NOT NULL DEFAULT 0, visa38idnav int NOT NULL DEFAULT 0, visa38idmoodle int NOT NULL DEFAULT 0)";}
	if ($dbversion==9438){$sSQL="ALTER TABLE visa38convpruebas ADD PRIMARY KEY(visa38id)";}
	if ($dbversion==9439){$sSQL=$objDB->sSQLCrearIndice('visa38convpruebas', 'visa38convpruebas_id', 'visa38idtipo, visa38consec', true);}
	if ($dbversion==9440){$sSQL="agregamodulo|2938|29|Pruebas de convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9441){$sSQL=$u09."(2938, 1, 'Pruebas de convocatoria', 'visaepruebas.php', 2908, 2938, 'S', '', '')";}
	if ($dbversion==9442){$sSQL="CREATE TABLE visa40inscripcion (visa40idconvocatoria int NOT NULL, visa40idtercero int NOT NULL, visa40id int NOT NULL DEFAULT 0, visa40estado int NOT NULL DEFAULT 0, visa40idperiodo int NOT NULL DEFAULT 0, visa40idescuela int NOT NULL DEFAULT 0, visa40idprograma int NOT NULL DEFAULT 0, visa40idzona int NOT NULL DEFAULT 0, visa40idcentro int NOT NULL DEFAULT 0, visa40fechainsc int NOT NULL DEFAULT 0, visa40fechaadmision int NOT NULL DEFAULT 0, visa40numcupo int NOT NULL DEFAULT 0, visa40idtipologia int NOT NULL DEFAULT 0, visa40idsubtipo int NOT NULL DEFAULT 0, visa40idminuta int NOT NULL DEFAULT 0, visa40idresolucion int NOT NULL DEFAULT 0)";}
	if ($dbversion==9443){$sSQL="ALTER TABLE visa40inscripcion ADD PRIMARY KEY(visa40id)";}
	if ($dbversion==9444){$sSQL=$objDB->sSQLCrearIndice('visa40inscripcion', 'visa40inscripcion_id', 'visa40idconvocatoria, visa40idtercero', true);}
	if ($dbversion==9445){$sSQL="agregamodulo|2940|29|Inscripcion convocatoria|1|2|3|4|5|6|8";}
	if ($dbversion==9446){$sSQL=$u09."(2940, 1, 'Inscripcion convocatoria', 'visaeinscripcion.php', 2908, 2940, 'S', '', '')";}
	if ($dbversion==9447){$sSQL="CREATE TABLE visa43inscripdocs (visa43idinscripcion int NOT NULL, visa43iddocumento int NOT NULL, visa43id int NOT NULL DEFAULT 0, visa43idorigen int NOT NULL DEFAULT 0, visa43idarchivo int NOT NULL DEFAULT 0, visa43fechaaprob int NOT NULL DEFAULT 0, visa43usuarioaprueba int NOT NULL DEFAULT 0)";}
	if ($dbversion==9448){$sSQL="ALTER TABLE visa43inscripdocs ADD PRIMARY KEY(visa43id)";}
	if ($dbversion==9449){$sSQL=$objDB->sSQLCrearIndice('visa43inscripdocs', 'visa43inscripdocs_id', 'visa43idinscripcion, visa43iddocumento', true);}
	if ($dbversion==9450){$sSQL=$objDB->sSQLCrearIndice('visa43inscripdocs', 'visa43inscripdocs_padre', 'visa43idinscripcion');}
	if ($dbversion==9451){$sSQL="agregamodulo|2943|29|Anexos|1|2|3|4|5|6|8";}
	if ($dbversion==9452){$sSQL="CREATE TABLE visa44anotaciones (visa44idinscripcion int NOT NULL, visa44consec int NOT NULL, visa44id int NOT NULL DEFAULT 0, visa44alcance int NOT NULL DEFAULT 0, visa44nota Text NULL, visa44usuario int NOT NULL DEFAULT 0, visa44fecha int NOT NULL DEFAULT 0, visa44hora int NOT NULL DEFAULT 0, visa44minuto int NOT NULL DEFAULT 0)";}
	if ($dbversion==9453){$sSQL="ALTER TABLE visa44anotaciones ADD PRIMARY KEY(visa44id)";}
	if ($dbversion==9454){$sSQL=$objDB->sSQLCrearIndice('visa44anotaciones', 'visa44anotaciones_id', 'visa44idinscripcion, visa44consec', true);}
	if ($dbversion==9455){$sSQL=$objDB->sSQLCrearIndice('visa44anotaciones', 'visa44anotaciones_padre', 'visa44idinscripcion');}
	if ($dbversion==9456){$sSQL="agregamodulo|2944|29|Anotaciones|1|2|3|4|5|6|8";}
	if ($dbversion==9457){$sSQL="CREATE TABLE visa45convpruebares (visa45idinscripcion int NOT NULL, visa45idprueba int NOT NULL, visa45id int NOT NULL DEFAULT 0, visa45puntaje int NOT NULL DEFAULT 0)";}
	if ($dbversion==9458){$sSQL="ALTER TABLE visa45convpruebares ADD PRIMARY KEY(visa45id)";}
	if ($dbversion==9459){$sSQL=$objDB->sSQLCrearIndice('visa45convpruebares', 'visa45convpruebares_id', 'visa45idinscripcion, visa45idprueba', true);}
	if ($dbversion==9460){$sSQL=$objDB->sSQLCrearIndice('visa45convpruebares', 'visa45convpruebares_padre', 'visa45idinscripcion');}
	if ($dbversion==9461){$sSQL="agregamodulo|2945|29|Resultados pruebas|1|2|3|4|5|6|8";}
	if ($dbversion==9462){$sSQL=$u09."(2945, 1, 'Resultados pruebas', 'visaepruebasres.php', 2908, 2945, 'S', '', '')";}

}
if (($dbversion>9500)&&($dbversion<9601)){
}
if (($dbversion>9600)&&($dbversion<9701)){
}
if (($dbversion>9700)&&($dbversion<9801)){
}
if (($dbversion>9800)&&($dbversion<9901)){
}
if (($dbversion>9900)&&($dbversion<10001)){
	if ($dbversion==99999){$sSQL="";}
}
if (false) {
	if ($dbversion==9999){$sSQL=$u04."(3646, 10, 'S')";}
	//if ($dbversion==6781){$sSQL=$u09."(12280, 1, 'Cupos preoferta', 'corepreofcupos.php', 2206, 12280, 'S', '', '')";}
	//(3220, 'Conceptos para nómina', ''), (3221, 'Provisiones de nómina', '')
	//if ($dbversion==6604){$sSQL="INSERT INTO nico11momento (nico11id, nico11nombre, nico11ayuda) VALUES (3201, 'Liquidación Nomina', '')";}
	//, cttc11activo, cttc11anexo, cttc11observaciones, cttc11aprobacion, cttc11version
	//if ($dbversion==5330){$sSQL="agregamodulo|4071|40|CPC|1|2|3|4|5|6";}
	//if ($dbversion==5331){$sSQL=$u09."(4071, 1, 'CPC', 'heracpc.php', 1, 4071, 'S', '', '')";}
	//if ($dbversion==5334){$sSQL="agregamodulo|4072|40|Unidades de medida|1|2|3|4|5|6";}
	//if ($dbversion==5335){$sSQL=$u09."(4072, 1, 'Unidades de medida', 'heraunidadmedida.php', 2, 4072, 'S', '', '')";}
	if ($dbversion==9201) {$sSQL ="INSERT INTO ofes09estadorec (ofes09id, ofes09nombre) VALUES (0, 'Borrador'), (3, 'Devuelto'), (7, 'En firme')";}	
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
	switch (substr($sSQL,0,10)){
		case 'versionado':
			$sper=explode("|",$sSQL);
			$stemp="UPDATE unad01sistema SET unad01mayor=".$sper[2].", unad01menor=".$sper[3].", unad01correccion=".$sper[4]." WHERE unad01id=".$sper[1];
			$result=$objDB->ejecutasql($stemp);
		break;
		case 'agregamodu':
			$sper=explode("|",$sSQL);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo " .";
				$stemp=$u06."(1, ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo ".";
				}
			break;
		case "crearmodul":
			$sper=explode("|",$sSQL);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo " .";
				}
			break;
		case "modulogrup":
			$sper=explode("|",$sSQL);
			for ($k=3;$k<count($sper);$k++){
				$stemp=$u06."(".$sper[2].", ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objDB->ejecutasql($stemp);
				echo ".";
				}
			break;
		case 'add_campos':
			$aCampos=explode('|',$sSQL);
			$sTabla= $aCampos[1];
			$iCampos = count($aCampos);
			for ($k=2;$k<$iCampos;$k++){
				$sTemp = 'ALTER TABLE ' . $sTabla . ' ADD ' . $aCampos[$k];
				$result=$objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>'.$sTemp.'</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende=1;
				}
			}
			break;
		case 'drop_campo':
			$aCampos=explode('|',$sSQL);
			$sTabla= $aCampos[1];
			$iCampos = count($aCampos);
			for ($k=2;$k<$iCampos;$k++){
				$sTemp = 'ALTER TABLE ' . $sTabla . ' DROP COLUMN ' . $aCampos[$k];
				$result=$objDB->ejecutasql($sTemp);
				if ($result == false) {
					echo '<li> -- Error ejecutando <font color="#FF0000"><b>'.$sTemp.'</b></font> <b>' . $objDB->serror . '</b></li>';
					$error++;
					$suspende=1;
				}
			}
			break;	
		case 'DROP TABLE':
			$nomtabla=substr($sSQL,11);
			if ($objDB->bexistetabla($nomtabla)){
				$result=$objDB->ejecutasql($sSQL);
			} else {
				echo '<br> -- La tabla <b>'.$nomtabla.'</b> no existe.';
			}
			break;
		case "mod_cod_ca":
			$sper=explode("|",$sSQL);
			$stemp="UPDATE unad02modulos SET unad02id=".$sper[2]." WHERE unad02id=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad04modulopermisos SET unad04idmodulo=".$sper[2]." WHERE unad04idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad06perfilmodpermiso SET unad06idmodulo=".$sper[2]." WHERE unad06idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad09modulomenu SET unad09idmodulo=".$sper[2]." WHERE unad09idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			break;
		case "mod_quitar":
			$sper=explode("|",$sSQL);
			$stemp="DELETE FROM unad02modulos WHERE unad02id=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad04modulopermisos WHERE unad04idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad06perfilmodpermiso WHERE unad06idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad09modulomenu WHERE unad09idmodulo=".$sper[1].";";
			$result=$objDB->ejecutasql($stemp);
			echo " .";
			break;
		case '':
			break;
		default:
		$bHayError=false;
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$bHayError=true;
			//Si viene un DROP INDEX no hay error.
			if (strpos($sSQL, 'DROP INDEX')>0){$bHayError=false;}
			if (strpos($sSQL, 'DROP PRIMARY KEY')>0){$bHayError=false;}
			}
		if ($bHayError){
			//$sError = '<li>[ ' . $dbversion . ' ] <font color="#FF0000"><b>Error </b>'.$objDB->serror.'</font></li>';
			echo '<li> -- <font color="#FF0000">'.$objDB->serror.'</font></li>';
			$error++;
			$suspende=1;
			}
		}//fin del switch
	$sSQL="UPDATE unad00config SET unad00valor=".($dbversion+1)." WHERE unad00codigo='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$dbversion++;
	$procesos++;
	if ($procesos>14){
		$suspende=1;
		break;
		}
	}//termina de ejecutar sentencia por sentenca.
echo '</ul>';
if ($sError) {
	echo $sError . '<br>';
}
$objDB->CerrarConexion();
?>
<br>Base de Datos Actualizada <?php echo $dbversion; ?>;
<?php if($suspende==1){?><br>
<form id="form1" name="form1" method="post" action="">
El Proceso A&uacute;n No Ha Concluido
<?php
if (false){//$notablas
?>
<input name="notablas" type="hidden" id="notablas" value="1" />
<?php
	}
?>
<div class="salto5px"></div>
<input class="btn-success" type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($error==0){
?>
<script language="javascript">
function recargar(){
	form1.submit();
	}
setInterval ("recargar();", 1000); 
</script>
<?php 
}//fin de si no hay errores...
}
piedepagina();
