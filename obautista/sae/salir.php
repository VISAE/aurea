<?php
require '../config.php';
@session_start();
unset($_SESSION['u_ultimominuto']);
header('Location:../login/logout.php?sesskey='.$_SESSION['USER']->sesskey);
?>