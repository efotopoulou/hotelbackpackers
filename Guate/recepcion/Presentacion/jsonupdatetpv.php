<?php
require ('../Dominio/class_comanda.php');
require ('../Dominio/MensajeJSON.php');
//Recoge el parametro y se limpia de contrabarras
 $efectivo = $_POST['efectivo'];
 $comandaID = $_POST['id'];
 
//Creacion del objeto que inserta en la BD
$comanda = new Comanda();
$mensaje = new MensajeJSON();
try {
$comanda->updateComandaAbierta($comandaID,$efectivo);
}catch (SQLException $e){
$mensaje->setMensaje("Error de la BBDD");
}
echo($mensaje->encode());
?>
