<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_comanda.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
//Recoge el parametro y se limpia de contrabarras
 $json = $_POST['json'];
 $json = str_replace("\\", "",$json);

//Creacion del objeto que inserta en la BD
$comanda = new Comanda();
$mensaje = new MensajeJSON();
$stock = new stock();
 
$comandaJson = json_decode($json, true);
//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
try {
  $idComanda = $comanda->setComandaVenta($comandaJson["efectivo"],$comandaJson["currentClientType"],$comandaJson["total"],$comandaJson["id_cliente"],$comandaJson["free"]);
 //Se borra por si acaso ha desactivado el efectivo y lo vuelve a apretar.
 //$comanda->borrarLineasComanda($mesa["comandaID"]);
 $lineas = $comandaJson["liniasComanda"];
 
 for ($i=0;$i<=$comandaJson["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
 	$comanda->setLineaComanda($idComanda,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
    $stock->informar_stock_rest($lineas[$i]["platoId"],$cantidad);
 }
if ($comandaJson["currentClientType"]==5)$comanda->setComandaCredito($idComanda); 
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>
