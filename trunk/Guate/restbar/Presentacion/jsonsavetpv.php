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
 
$comandasList = json_decode($json, true);


//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
try {
for($k=0;$k<sizeof($comandasList);$k++){
 $mesa=$comandasList[$k];
  $comandaId = $comanda->setComanda($mesa["comandaID"],$mesa["efectivo"],$mesaNum,$mesa["currentClientType"],$mesa["totalPropina"],$mesa["id_cliente"],$mesa["free"]);
 $lineas = $mesa["liniasComanda"];
 for ($i=0;$i<=$mesa["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
	if($comanda->esPlatillo($lineas[$i]["platoId"])) $comanda->setLineaComanda($comandaId,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
	else {
		$comanda->setLineaComandaBebida($comandaId,$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
		$stock->informar_controlstock($lineas[$i]["platoId"],$cantidad);
	}
 }
 if ($mesa["currentClientType"]==5)$credito->setComandaCreditoComida($comandaId,"RB");
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>
