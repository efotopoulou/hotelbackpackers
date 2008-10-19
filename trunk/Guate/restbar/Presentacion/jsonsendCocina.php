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

//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
try {
// 		$comandaId = $comanda->setComanda($mesa["comandaID"],$mesa["efectivo"],$mesaNum,$mesa["currentClientType"],$mesa["totalPropina"],$mesa["id_cliente"],$mesa["free"]);
$lineas = $mesa["liniasComanda"];
 for ($i=0;$i<=$mesa["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
 	if ($comanda->esCocina($lineas[$i]["platoId"]))
 	  $comanda->setCocina($mesa["comandaID"], $lineas[$i]["platoId"], $cantidad);
 	  //$comanda->setLineaComanda($comandaId,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
 }
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>