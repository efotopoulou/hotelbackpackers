<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_comanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_credito.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
//Recoge el parametro y se limpia de contrabarras
  $json = $_POST['json'];
 $mesaNum = $_POST['mesa'];
 $json = str_replace("\\", "",$json);

//Creacion del objeto que inserta en la BD
$comanda = new Comanda();
$credito = new Credito();
$mensaje = new MensajeJSON();
$stock = new stock();
 
$mesa = json_decode($json, true);


$mesa["numRow"];
//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
try {
$comandaId = $comanda->setComanda($mesa["comandaID"],$mesa["efectivo"],$mesaNum,$mesa["currentClientType"],$mesa["total"],$mesa["id_cliente"],$mesa["free"]);
 //Se borra por si acaso ha desactivado el efectivo y lo vuelve a apretar.
 //$comanda->borrarLineasComanda($mesa["comandaID"]);
 $lineas = $mesa["liniasComanda"];
 for ($i=0;$i<=$mesa["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
 	$comanda->setLineaComandaBebida($comandaId,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
    $stock->informar_controlstock($lineas[$i]["platoId"],$cantidad);
 }
if ($mesa["currentClientType"]==5)$credito->setComandaCreditoBebida($comandaId,"RB");
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>
