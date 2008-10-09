<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
//require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_cocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$usuario = $_POST['usuario'];
$idusuario = $_POST['idusuario'];
$year = $_POST['year'];
$month = $_POST['month'];
$comandas = $_POST['comandas'];
$nombreEmpleado = $_POST['nombreEmpleado'];


$caja=new caja();
$mensaje = new MensajeJSON();

try{
if ($usuario){
$response = loadusuarios($caja);	
}else if($comandas && $idusuario){
$comandasList = split( ",",$comandas);
$iduser = substr($idusuario, 1);
$a=0;
foreach ($comandasList as $value){
 $a+=$caja->cobrar_ticket($value);
}
$caja->insert_movimiento("entrada",$a,"Cobrado Credito".nameUser($iduser),$categoria,$idencargado);
$response = loadtickets($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}else if($idusuario){	
$iduser = substr($idusuario, 1);
$response = loadtickets($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}else if($nombreEmpleado){
$caja->set_usuario($nombreEmpleado);
$response = loadusuarios($caja);		
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }

$response["TotalTickets"]=$totalTickets;

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	
<?php
function loadtickets($caja,$idusuario,$month,$year){
$iduser = substr($idusuario, 1);
$tikets=$caja->get_usuarios_comandas($iduser,$month,$year);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$tikets[$i]->estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"clientType"=>$tikets[$i]->clientType,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);	
}
function loadusuarios($caja){
$usuarios=$caja->get_usuarios();
if ((sizeof($usuarios))>0){
	  for($i=0;$i<count($usuarios);$i++) {
	  $UsuariosInfo[$i]=array("idTrabajador"=>$usuarios[$i]->idTrabajador,"nombre"=>$usuarios[$i]->nombre);
	  }
 }
 $response["UsuariosInfo"]=$UsuariosInfo;
 return($response);		
}
?>
