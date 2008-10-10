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
$description=$_POST['description'];
$categoria=$_POST['categoria'];
$idempleado=$_POST['idempleado'];
$idencargado=$_POST['idencargado'];

$caja=new caja();
$mensaje = new MensajeJSON();

try{
//al cargar la pagina cargamos la lista de los usuarios
if ($usuario){
$response = loadusuarios($caja);	
}
//cobrar un tiquet.despues lo insertamos como movumiento en la caja.despues cargamos los tiquets y los movimientos
else if($comandas || $movs){
$iduser = substr($idusuario, 1);
$comandasList = split( ",",$comandas);
$a=0;
foreach ($comandasList as $value){
 $val= substr($value, 1);
 $a+=$caja->cobrar_ticket($val);
}
$movsList = split( ",",$movs);
foreach ($movsList as $value){
 $val= substr($value, 1);
 $a+=$caja->cobrar_movimiento_credito($val);
}
$onoma=$caja->nameUser($iduser);
$caja->insert_movimiento("entrada",$a,"Cobrado Credito ".$onoma,9,$idencargado);
$response = loadtickets($caja,$idusuario,$month,$year);	
$response+=loadmovimientos($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}
//cargamos de nuevo los tiquets y los movimientos del mes corespondiente para el usuario elegido
else if($idusuario){	
$iduser = substr($idusuario, 1);
$response = loadtickets($caja,$idusuario,$month,$year);	
$response+=loadmovimientos($caja,$idusuario,$month,$year);	
$totalTickets=$caja->total_cuenta($iduser,$month,$year);
}
//añadimos un nuevo usuario y cargamos de nuevo la lista de los usuarios
else if($nombreEmpleado){
$caja->set_usuario($nombreEmpleado);
$response = loadusuarios($caja);		
}
//insertar un nuevo movimiento como credito.en la caja y en la cuenta de usuarios
else if($categoria){
$idemp = substr($idempleado, 1);
$caja->insertmovcredito($dinero,$description,$categoria,$idemp,$idencargado);
$response = loadtickets($caja,$idempleado,$month,$year);	
$response+= loadmovimientos($caja,$idempleado,$month,$year);
$totalTickets=$caja->total_cuenta($idemp,$month,$year);	
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
	  if ($tikets[$i]->cobrado) $estado="cobrado"; else $estado="credito";
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"estado"=>$estado,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"clientType"=>$tikets[$i]->clientType,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);	
}
function loadmovimientos($caja,$idusuario,$month,$year){
$iduser = substr($idusuario, 1);
$movimientos=$caja->get_usuarios_movimientos($iduser,$month,$year);
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
?>
