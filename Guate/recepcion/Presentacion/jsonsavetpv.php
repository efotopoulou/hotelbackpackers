<?php
require ('../Dominio/class_comanda.php');
require ('../Dominio/MensajeJSON.php');
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
 $comanda->setComandaAbierta($mesa["comandaID"],$mesa["efectivo"],$mesaNum,$mesa["currentClientType"],$mesa["total"],$mesa["id_cliente"],$mesa["free"]);
 //Se borra por si acaso ha desactivado el efectivo y lo vuelve a apretar.
 //$comanda->borrarLineasComanda($mesa["comandaID"]);
 $lineas = $mesa["liniasComanda"];
 for ($i=0;$i<=$mesa["numRow"];$i++){
 	$cantidad = (int)$lineas[$i]["cantidad"];
 	if($cantidad==0) $cantidad=1;
 	$comanda->setLineaComanda($mesa["comandaID"],$lineas[$i]["platoId"],$cantidad, $lineas[$i]["precioN"]);
 }
}catch (SQLException $e){
	$aux = $e ->getNativeError();
 if (stripos($e ->getNativeError(),"uplicate") != 0){
   $mensaje->setMensaje("La IdComanda ja existe en la Base de Datos!");
 }
 else $mensaje->setMensaje("Error Desconocido: $aux!");
}
echo($mensaje->encode());
?>