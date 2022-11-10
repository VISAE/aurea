<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
@session_start();
unset($_SESSION['u_ultimominuto']);
unset($_SESSION['unad_id_tercero']);
unset($_SESSION['USER']);
header('Location:http://campus.unad.edu.co');
?>