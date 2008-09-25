<?php
require($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');
require($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
$mensaje = new MensajeJSON();
		$id = $_POST['id'];
		$sesion=new session();
		$sesion->set_id_usuario($id);
echo($mensaje->encode());
?>
