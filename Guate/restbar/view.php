<?php
include($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');
ini_set('display_errors','1');


function error_handler($errno, $errstr, $errfile, $errline) { 
  if (4096 == $errno) throw new Exception($errstr); 
  return false; 
} 

function gestor_excepciones($excepcion) {
  echo "Excepción no capturada: " , $excepcion->getMessage(), "\n";
	$log = new log();
	$log->guardarErrorHotel($excepcion, 2);
}

set_error_handler('error_handler');
set_exception_handler('gestor_excepciones');

ob_start();
$sesion = new session();
ob_end_clean();

$page='';

if($_GET!=null){
	$p_req=$_GET['page'];
	
	if($sesion->is_allowed_p_req($p_req)){
		$page='Presentacion/'.$p_req.'.php';
		try{
			include($page);
		}
		catch (Exception $sqle) {
 	 		$texto=$excepcion->getMessage().$excepcion->getFile()."Line:".$excepcion->getLine().$excepcion->getTraceAsString();
 	 		$log = new log();
 	 		$log->guardarErrorHotel($texto,2);
		}
	}else
		header('Location:/hotel/view.php'); 	
}


?>