<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
//require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_cocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$usuario = $_POST['usuario'];
$idusuario = $_POST['idusuario'];
$year = $_POST['year'];
$month = $_POST['month'];
$comandas = $_POST['comandas'];
$movs = $_POST['movs'];
$nombreEmpleado = $_POST['nombreEmpleado'];
$dinero=$_POST['dinero'];
$money=$_POST['money'];
$description=$_POST['description'];
$categoria=$_POST['categoria'];
$idempleado=$_POST['idempleado'];
$idencargado=$_POST['idencargado'];

$caja=new caja();
$mensaje = new MensajeJSON();

try{
//al cargar la pagina cargamos la lista de los usuarios--OK
if ($usuario){
$response = loadusuarios($caja);	
}
//cobrar una parte del credito.despues lo insertamos como movimiento en la caja y el la cuenta de usuarios--OK
else if($money){
$iduser = substr($idempleado, 1);
$onoma=$caja->nameUser($iduser);
$idMov=$caja->insert_movimiento("entrada",$money,"Cobrado Credito ".$onoma,9,$idencargado);
$caja->insert_mov_credito($idMov,-$money,$iduser,1);
$response = loadtickets($caja,$idempleado);	
$response+=loadmovimientos($caja,$idempleado);	
$totalTickets=$caja->total_cuenta($iduser);
}
//cargamos de nuevo los tiquets y los movimientos para el usuario elegido--OK
else if($idusuario){	
$iduser = substr($idusuario, 1);
$response = loadtickets($caja,$idusuario);	
$response+=loadmovimientos($caja,$idusuario);	
$totalTickets=$caja->total_cuenta($iduser);
}
//añadimos un nuevo usuario y cargamos de nuevo la lista de los usuarios--OK
else if($nombreEmpleado){
$caja->set_usuario($nombreEmpleado);
$response = loadusuarios($caja);		
}
//insertar un nuevo movimiento como credito.en la caja y en la cuenta de usuarios--OK
else if($categoria){
$idemp = substr($idempleado, 1);
$onoma=$caja->nameUser($idemp);
$idMov=$caja->insert_movimiento("credito",0,$onoma.": ".$description,$categoria,$idencargado);
$caja->insert_mov_credito($idMov,$dinero,$idemp,0);
$response = loadtickets($caja,$idempleado);	
$response+= loadmovimientos($caja,$idempleado);
$totalTickets=$caja->total_cuenta($idemp);	
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
function loadtickets($caja,$idusuario){
$iduser = substr($idusuario, 1);
$tikets=$caja->get_usuarios_comandas($iduser);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  if ($tikets[$i]->cobrado) $estado="cobrado"; else $estado="credito";
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"clientType"=>$tikets[$i]->clientType,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);	
}
function loadmovimientos($caja,$idusuario){
$iduser = substr($idusuario, 1);
$movimientos=$caja->get_usuarios_movimientos($iduser);
if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	  if ($movimientos[$i]->tipo) $tipo="cobrado"; else $tipo="credito";
	  $MovimientosInfo[$i]=array("id_movimiento"=>$movimientos[$i]->id_movimiento,"fechaHora"=>$movimientos[$i]->fechaHora,"tipo"=>$tipo,"dinero"=>$movimientos[$i]->dinero,"descripcion"=>$movimientos[$i]->descripcion,"categoria"=>$movimientos[$i]->categoria,"encargado"=>$movimientos[$i]->encargado);
	  }
 }	
 $response["MovimientosInfo"]=$MovimientosInfo;
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



//cobrar un tiquet.despues lo insertamos como movumiento en la caja.despues cargamos los tiquets y los movimientos
//else if($comandas || $movs){
//$iduser = substr($idusuario, 1);
//$comandasList = split( ",",$comandas);
//$a=0;
//foreach ($comandasList as $value){
// $val= substr($value, 1);
// $a+=$caja->cobrar_ticket($val);
//}
//$movsList = split( ",",$movs);
//foreach ($movsList as $value){
// $val= substr($value, 1);
// $a+=$caja->cobrar_movimiento_credito($val);
//}
//$onoma=$caja->nameUser($iduser);
//$caja->insert_movimiento("entrada",$a,"Cobrado Credito ".$onoma,9,$idencargado);
//$response = loadtickets($caja,$idusuario,$month,$year);	
//$response+=loadmovimientos($caja,$idusuario,$month,$year);	
//$totalTickets=$caja->total_cuenta($iduser,$month,$year);
//}
?>






