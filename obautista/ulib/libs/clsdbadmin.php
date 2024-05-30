<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 -2022
- Los datos de conexion deben pasarse como parametros al momento de iniciar la clase, esos parametros pueden venir del archivo de configuracion o de una base de datos
--- Octubre 2 de 2014 se empieza el uso de MySQLi en lugar de mysql_coneect
--- Junio 25 de 2016 - Se ajusta la funcion conectar.
--- Diciembre 3 de 2016 - Se agrega el CerrarConexion
--- Marzo 15 de 2019 - Se agrega el manejo de postgres
--- Julio 7 de 2020 - Se agrega la variable iFilasAfectadas
--- Modelo Versión 2.28.4b viernes, 14 de octubre de 2022 - Se pone en marcha el motor SQLServer
*/
function clsdb_warning_handler($errno, $errstr, $errfile, $errline, $errcontext)
{
	throw new Exception($errstr);
}
/**
 *  
 * sSQLEliminarIndice
 */
class clsdbadmin
{
	var $objMYSQL = NULL;
	var $objPG = NULL;
	var $bconectada = false;
	var $bMantenerComillaDoble = false;
	var $bPorNumero = false;
	var $bxajax = false;
	var $dbclave = '';
	var $dblink = NULL;
	var $dbmodelo = 'M';
	var $dbnombre = '';
	var $dbservidor = '';
	var $dbusuario = '';
	var $dbPuerto = '';
	var $iFilasAfectadas = 0;
	var $idsistema = 0; //aqui poner el sistema en el que se esta trabajando
	var $iNumCampos = -1;
	var $iPunteroCampo = 0;
	var $iVerSqlServer = 10;
	var $serror = '';
	var $bUTF8 = false;

	function xajax()
	{
		$this->bxajax = true;
		$this->bconectada = false;
	}
	function conectar()
	{
		$res = false;
		$this->serror = '';
		switch ($this->dbmodelo) {
			case 'O':
				if (!function_exists('odbc_connect')) {
					$this->serror = 'No se ha detectado la libreria ODBC, por favor informe al administrador del sistema.';
				}
				if ($this->serror == '') {
					if (!($this->dblink = @odbc_connect($this->dbservidor, $this->dbusuario, $this->dbclave))) {
						$this->serror = 'Error conectando al servidor de datos {' . odbc_error() . '}';
					} else {
						if (!$this->bxajax) {
							$this->bconectada = true;
						}
						$res = true;
					}
				}
				break;
			case 'P':
				$this->serror = '';
				@$this->objPG = pg_connect('host=' . $this->dbservidor . ' dbname=' . $this->dbnombre . ' user=' . $this->dbusuario . ' password=' . $this->dbclave . '') or ($this->serror = 'Error conectando al servidor de datos [' . $this->dbservidor . '] {' . @pg_last_error() . '}');
				if ($this->objPG == false) {
				} else {
					if (!$this->bxajax) {
						$this->bconectada = true;
					}
					$res = true;
				}
				break;
			default:
				$bHayServicio = false;
				try {
					$this->objMYSQL = new mysqli();
					$bHayServicio = true;
				} catch (Exception $e) {
					$this->serror = 'Error al intentar conectar el servicio MySQL';
				}
				if ($bHayServicio) {
					if ($this->dbPuerto == '') {
						@$this->objMYSQL->connect($this->dbservidor, $this->dbusuario, $this->dbclave, $this->dbnombre);
					} else {
						@$this->objMYSQL->connect($this->dbservidor, $this->dbusuario, $this->dbclave, $this->dbnombre, $this->dbPuerto);
					}
					if ($this->objMYSQL->connect_error) {
						$this->serror = 'Error conectando al servidor de datos <br><b>' . $this->objMYSQL->connect_error . '</b>';
					} else {
						$res = true;
						//if ($this->bUTF8){$this->ejecutasql("SET NAMES 'utf8'");}
					}
				}
				if (!$this->bxajax) {
					$this->bconectada = true;
				}
		}
		return $res;
	}
	function CerrarConexion()
	{
		switch ($this->dbmodelo) {
			case 'P':
				if ($this->objPG != NULL) {
					@pg_close($this->objPG);
				}
				break;
			case 'O':
				break;
			default:
				try {
					//@mysqli_close($this->objMYSQL);
				} catch (Exception $e) {
				}
				break;
		}
	}
	function ejecutasql($sSentenciaSQL, $bAjustar = true)
	{
		$res = false;
		$this->serror = '';
		$this->iFilasAfectadas = 0;
		if (trim($sSentenciaSQL) == '') {
			$this->serror = 'No se ha definido una consulta a ejecutar';
		}
		if (!$this->bconectada) {
			$this->conectar();
		}
		if ($this->serror == '') {
			switch ($this->dbmodelo) {
				case 'O':
					if ($bAjustar) {
						$sSentenciaSQL = cadena_Reemplazar($sSentenciaSQL, '"', "'");
					}
					$res = @odbc_exec($this->dblink, $sSentenciaSQL);
					if ($res == false) {
						$this->serror = odbc_error($this->dblink) . ' ' . $sSentenciaSQL;
					}
					break;
				case 'P';
					if ($this->bMantenerComillaDoble) {
						$sSQL = $sSentenciaSQL;
					} else {
						$sSQL = str_replace('"', "'", $sSentenciaSQL);
					}
					$res = pg_query($sSQL);
					break;
				default:
					//echo $sSentenciaSQL;
					$sMensajeError = '';
					try {
						if (is_object($this->objMYSQL)) {
							$res = $this->objMYSQL->query($sSentenciaSQL);
						} else {
							$sMensajeError = 'la base de datos no esta abierta.';
						}
					} catch (Exception $e) {
						$sMensajeError = $e->getMessage();
						//$sMensajeError = $this->objMYSQL->error;
						$res = false;
					}
					if ($res == false) {
						if ($sMensajeError == '') {
							$sMensajeError = $this->objMYSQL->error;
						}
						$this->serror = 'Error al ejecutar sentencia: ' . $sMensajeError;
						//$sMensajeError = 'Error al ejecutar sentencia: ' . $sSentenciaSQL . '<br>' . $sMensajeError;
					} else {
						//Veamos si fue un delete o un insert cuantas filas se afectaron.
						$sInicioSentencia = strtoupper(substr($sSentenciaSQL, 0, 6));
						switch ($sInicioSentencia) {
							case 'INSERT':
							case 'DELETE':
							case 'UPDATE':
								$this->iFilasAfectadas = $this->objMYSQL->affected_rows;
								break;
						}
					}
			}
		}
		return $res;
	}
	function liberar($tabla)
	{
		switch ($this->dbmodelo) {
			case 'O':
				break;
			default:
				//@mysqli_free_result($tabla);
				break;
		}
	}
	function nf($tabla)
	{
		$res = 0;
		if ($tabla != false) {
			switch ($this->dbmodelo) {
				case 'O':
					$res = odbc_num_rows($tabla);
					break;
				case 'P':
					$res = pg_num_rows($tabla);
					break;
				default:
					if (is_object($tabla)) {
						$res = $tabla->num_rows;
					}
			}
		}
		return $res;
	}
	function sf($tabla)
	{
		$res = false;
		if ($tabla != false) {
			switch ($this->dbmodelo) {
				case 'O':
					$res = @odbc_fetch_array($tabla);
					break;
				case 'P':
					if ($this->bPorNumero) {
						$res = pg_fetch_row($tabla);
					} else {
						$res = pg_fetch_array($tabla);
					}
					break;
				default:
					if (is_object($tabla)) {
						$res = $tabla->fetch_array();
					}
					break;
			}
		}
		return $res;
	}
	function objSiguienteCampo($tabla)
	{
		$res = false;
		if ($tabla != false) {
			switch ($this->dbmodelo) {
				case 'O':
					if ($this->iNumCampos == -1) {
						$this->iNumCampos = odbc_num_fields($tabla);
						//echo '----'.$this->iNumCampos.'-------';
					}
					if ($this->iPunteroCampo < $this->iNumCampos) {
						//$res=new stdclass();
						//En odbc el orden de los campos empieza en 1 y no en 0
						$this->iPunteroCampo++;
						$res = odbc_field_name($tabla, $this->iPunteroCampo);
					}
					break;
				case 'P':
					if ($this->iNumCampos == -1) {
						$this->iNumCampos = pg_num_fields($tabla);
					}
					if ($this->iPunteroCampo < $this->iNumCampos) {
						//$res=new stdclass();
						$res = pg_field_name($tabla, $this->iPunteroCampo);
						$this->iPunteroCampo++;
					}
					break;
				default:
					if ($this->iNumCampos == -1) {
						if (is_object($tabla)) {
							$this->iNumCampos = mysqli_num_fields($tabla);
						}
					}
					if ($this->iPunteroCampo < $this->iNumCampos) {
						$info_campo = $tabla->fetch_field_direct($this->iPunteroCampo);
						$res = $info_campo->name;
						$this->iPunteroCampo++;
					}
					/*
					try {
						$data = @mysqli_fetch_field($tabla);
						if (is_object($data)) {
							$res = $data->name;
						}
					} catch (Exception $ex) {
					}
					*/
					break;
			}
		}
		return $res;
	}
	//A FUTURO SE DEPRECIARAN...
	//funciones de trabajo de datos
	//funciones de trabajo sobre la estructura de la base de datos
	function bexistetabla($tablanombre)
	{
		$res = false;
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE \'' . $tablanombre . '\'';
				$tabla = $this->ejecutasql($sSQL);
				if ($this->nf($tabla) > 0) {
					$res = true;
				}
				break;
			default:
				$sSQL = 'SHOW TABLES LIKE "' . $tablanombre . '"';
				$tabla = $this->ejecutasql($sSQL);
				if ($this->nf($tabla) > 0) {
					$res = true;
				}
		}
		return $res;
	}
	//Funciones de agregado que pueden ser diferentes entre los motores.
	function sSQLCrearIndice($sTabla, $sNombreIndice, $sCampos, $bUnico = false)
	{
		$sUnico = '';
		if ($bUnico) {
			$sUnico = ' UNIQUE';
		}
		$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD' . $sUnico . ' INDEX ' . $sNombreIndice . '(' . $sCampos . ')';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = 'CREATE' . $sUnico . ' INDEX ' . $sNombreIndice . ' ON ' . $sTabla . '(' . $sCampos . ')';
				break;
		}
		return $sSQL;
	}
	function sSQLEliminarColuman($sTabla, $sColumna)
	{
		$sSQL = 'ALTER TABLE ' . $sTabla . ' DROP COLUMN ' . $sColumna . '';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = 'DROP INDEX ' . $sTabla . '.' . $sColumna . '';
				break;
		}
		return $sSQL;
	}
	function sSQLEliminarIndice($sTabla, $sNombreIndice)
	{
		$sSQL = 'ALTER TABLE ' . $sTabla . ' DROP INDEX ' . $sNombreIndice . '';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = 'DROP INDEX ' . $sTabla . '.' . $sNombreIndice . '';
				break;
		}
		return $sSQL;
	}
	function sSQLEliminarPK($sTabla)
	{
		$sSQL = 'ALTER TABLE ' . $sTabla . ' DROP PRIMARY KEY';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = '';
				$sVER = 'SELECT CONSTRAINT_NAME
				FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
				WHERE TABLE_NAME=\'' . $sTabla . '\' AND CONSTRAINT_TYPE=\'PRIMARY KEY\'';
				$tabla = $this->ejecutasql($sVER);
				if ($this->nf($tabla) > 0) {
					$fila = $this->sf($tabla);
					$sSQL = 'ALTER TABLE ' . $sTabla . ' DROP CONSTRAINT ' . $fila['CONSTRAINT_NAME'];
				}
				break;
		}
		return $sSQL;
	}
	function sSQLEliminaTabla($sNombreTabla)
	{
		$sSQL = 'DROP TABLE ' . $sNombreTabla . '';
		/*
		switch ($this->dbmodelo){
			case 'O':
			$sSQL = '';
			break;
		}
		*/
		return $sSQL;
	}
	// ----
	function sSQLListaTablas($sNombreABuscar)
	{
		$sSQL = 'SHOW TABLES LIKE "' . $sNombreABuscar . '";';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE \'' . $sNombreABuscar . '\'';
				break;
		}
		return $sSQL;
	}
	function sSQLPaginar($sCampos, $sTablas, $sOrden, $iBase, $iElementos)
	{
		$sRes = $sCampos . ' ' . $sTablas . ' ' . $sOrden . ' LIMIT ' . $iBase . ', ' . $iElementos;
		switch ($this->dbmodelo) {
			case 'O':
				if ($iBase == 0) {
					// Con el TOP y sale.
					$sCamposFin = substr($sCampos, 7);
					$sRes = 'SELECT TOP ' . $iElementos . ' ' . $sCamposFin . ' ' . $sTablas . ' ' . $sOrden;
				} else {
					if ($this->iVerSqlServer > 11) {
						//La version 12 de SQLServer ya trae paginador
						$sRes = '' . $sCampos . ' ' . $sTablas . ' ' . $sOrden . ' OFFSET ' . $iBase . ' ROWS FETCH NEXT ' . $iElementos . ' ROWS ONLY';
					} else {
						$iRegFin = $iBase + $iElementos + 1;
						$sRes = 'SELECT * FROM (' . $sCampos . ', ROW_NUMBER() OVER(' . $sOrden . ') AS FilaNum ' . $sTablas . ') AS VistaRes WHERE FilaNum>' . $iBase . ' AND FilaNum<' . $iRegFin . '';
					}
				}
				break;
		}
		return $sRes;
	}
	// --- Renombrer tabla.
	function sSQLRenombrarTabla($sNombreTabla, $sNuevoNombre)
	{
		$sSQL = 'RENAME TABLE ' . $sNombreTabla . ' TO ' . $sNuevoNombre . '';
		switch ($this->dbmodelo) {
			case 'O':
				$sSQL = "EXEC sp_rename '" . $this->dbnombre . "." . $sNombreTabla . "', '" . $sNuevoNombre . "';";
				break;
		}
		return $sSQL;
	}
	//Definiciones de tipos que pueden ser diferentes
	function sTipoCampoBinario()
	{
		$sRes = 'LONGBLOB';
		switch ($this->dbmodelo) {
			case 'O':
				$sRes = 'VARBINARY(max)';
				break;
		}
		return $sRes;
	}
	//funciones que trabajan sobre campos
	function scomparafecha($scampo, $scomparacion, $sfecha)
	{
		$res = '';
		switch ($this->dbmodelo) {
			case 'M':
				$res = '(STR_TO_DATE(' . $scampo . ',"%d/%m/%Y")' . $scomparacion . 'STR_TO_DATE("' . $sfecha . '","%d/%m/%Y"))';
		}
		return $res;
	}
	function stextoafecha($sfecha)
	{
		$res = '';
		switch ($this->dbmodelo) {
			case 'M':
				$res = 'STR_TO_DATE("' . $sfecha . '","%d/%m/%Y")';
		}
		return $res;
	}
	function scampoafecha($scampo)
	{
		$res = '';
		switch ($this->dbmodelo) {
			case 'M':
				$res = 'STR_TO_DATE(' . $scampo . ',"%d/%m/%Y")';
		}
		return $res;
	}

	function __construct($servidor, $usuario, $clave, $db, $modelo = 'M')
	{
		$this->dbclave = $clave;
		$this->dbnombre = $db;
		$this->dbmodelo = $modelo;
		$this->dbservidor = $servidor;
		$this->dbusuario = $usuario;
		if ($servidor == '') {
			$this->serror = 'No se ha definido el servidor de base de datos';
		}
	}
	function __destruct()
	{
		if ($this->objMYSQL != NULL) {
			//$this->objMYSQL->close();
			// $this->CerrarConexion();
			unset($this->objMYSQL);
		}
		if ($this->objPG != NULL) {
			//$this->objMYSQL->close();
			// $this->CerrarConexion();
			unset($this->objMYSQL);
		}
	}
}
