<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_log.php');

//Si este parametro no es nulo, significa que el error es que no se encuentra
//la pagina de destino en una llamada getJSONGuate.
$url = $_GET['url'];
//Si este parametro no es nulo, significa que el error es que la respuesta a la llamada getJSONGuate
// esta mal formada y no contiene la estructura que se espera. 
$texto = $_GET['text'];
$texto = str_replace("\n","<br />", $texto);

$mensaje = new MensajeJSON();
$log = new log();
try {

if ($url){
	if ($texto) $log ->guardarErrorRecepcion($texto, 4);
	else $log ->guardarErrorRecepcion($texto, 5);
	
}else throw new Excepcion("no han pasado el parametro 'url' en jsonlog.php");  

}catch (Exception $e){
//TODO: Ya que no se puede guardar en la BBDD, guardar el error en un fichero. /log/error.log. 
// Hacerlo para todos los ficheros jsonXXXXXXXXX.php
	$aux = $e ->getNativeError();
	$mensaje->setMensaje("Error Desconocido: $aux!!!!".$e->getMessage());
	$log ->guardarErrorRecepcion("Error Desconocido: $aux!!!!".$e->getMessage(), 5);
}
echo($mensaje->encode());
?>
