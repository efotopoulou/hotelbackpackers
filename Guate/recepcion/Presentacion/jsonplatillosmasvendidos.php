<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_estadisticas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$fechaInicio =  $_POST['fechaInicio'];
$fechaFin =  $_POST['fechaFin'];

$estadisticas = new estadisticas();
$mensaje = new MensajeJSON();

try{
$output = $estadisticas ->topPlatillos($fechaInicio,$fechaFin);
$mensaje->setDatos($output);	
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
 echo($mensaje->encode());
?>

