<?php
include($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');
ini_set('display_errors','1');

ob_start();
$sesion = new session();
ob_end_clean();

$page='';

if($_GET!=null){
	$p_req=$_GET['page'];
	
	if($sesion->is_allowed_rest($p_req)){
		$page='Presentacion/'.$p_req.'.php';
		include($page);
	}else
		header('Location:/hotel/view.php'); 	
}


?>