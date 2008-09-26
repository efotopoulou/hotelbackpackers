<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$idbebida = $_POST['idbebida'];
$stockbar =  $_POST['stockbar'];
$stockrestaurante =  $_POST['stockrestaurante'];
$unidadventa = $_POST['unidadventa'];
$addornew = $_POST['aux'];
$categoria =  $_POST['categoria'];
$idencargado =  $_POST['idencargado'];
$idComanda = $_POST['idComanda']; 
$idComDetail = $_POST['idComDetail']; 

$stock=new stock();
$mensaje = new MensajeJSON();

try{
if($idbebida){
$id = substr($idbebida, 1);
$stock->add_stock($addornew,$id,$stockbar,$stockrestaurante,$unidadventa);		
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
 
 
$stockbar = $stock->get_stock();
if ((sizeof($stockbar))>0){
	  for($i=0;$i<count($stockbar);$i++) {
	  $stockInfo[$i]=array("idBebida"=>$stockbar[$i]->idBebida,"numBebida"=>$stockbar[$i]->numBebida,"nombre"=>$stockbar[$i]->nombre,"stockbar"=>$stockbar[$i]->stockbar,"stockrestaurante"=>$stockbar[$i]->stockrestaurante,"unidadventa"=>$stockbar[$i]->unidadventa);
	  }
 }	

$response["stockInfo"]=$stockInfo;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
