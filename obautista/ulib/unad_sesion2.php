<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 martes, 12 de marzo de 2019
--- A diferencia del unad_sesion, este no redirige la pagina...
*/
session_start();
if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}

