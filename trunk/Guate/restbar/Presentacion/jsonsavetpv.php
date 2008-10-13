<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_comanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
//Recoge el parametro y se limpia de contrabarras
  $json = $_POST['json'];
 $mesaNum = $_POST['mesa'];
 $json = str_replace("\\", "",$json);

//Creacion del objeto que inserta en la BD
$comanda = new Comanda();
$mensaje = new MensajeJSON();
 
$mesa = json_decode($json, true);


$mesa["numRow"];
//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
try {
 		$comandaId = $comanda->setComanda($mesa["comandaID"],$mesa["efectivo"],$mesaNum,$mesa["currentClientType"],$mesa["totalPropina"],$mesa["id_cliente"],$mesa["free"]);
 //Se borra por si acaso ha desactivado el efectivo y lo vuelve a apretar.
 //$comanda->borrarLineasComanda($mesa["comandaID"]);
 $lineas = $mesa["liniasComanda"];
 for ($i=0;$i<=$mesa["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
 	$comanda->setLineaComanda($comandaId,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
 }
if ($mesa["currentClientType"]==5)$comanda->setComandaCreditoComida($comandaId);
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>
