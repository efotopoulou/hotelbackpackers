<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

//Cambiar caja por nueva clase que se llame cuentausuarios
$caja=new caja();
$mensaje = new MensajeJSON();
//try{

$service = $_POST['service'];
  switch ($service){
	case "begin":		$response = loadusuarios($caja);	
						break;
	case "crearcuenta": $response = crearcuenta($_POST['nombreEmpleado'], $_POST['tipo'], $caja, $mensaje);
						break;
	case "buscarnombre":$response = load_buscador_usuarios($caja,$_POST['mask']);
						break;
	case "eliminar":	$response = eliminarcuenta($_POST['cuentadelete'], $caja, $mensaje);
						break;
	case "loadcuenta":	$response = loadcuenta($_POST['idusuario'], $caja);
						break;
	case "insmov":		$response = insertarmovimiento($_POST['idempleado'],$_POST['description'],$_POST['categoria'],$_POST['idencargado'], $_POST['dinero'], $caja);
						break;
	case "pagarcred":	$response = pagarcredito($_POST['idempleado'],$_POST['money'],$_POST['idencargado'], $caja);
						break;
	case "imprcuenta":	$response = imprcuenta($_POST['idusuario'],$_POST['fechaStart'],$_POST['fechaStop'], $caja);
						break;
  }
//}catch (SQLException $e){
//	$aux = $e ->getNativeError();
 //   $mensaje->setMensaje("Error Desconocido: $aux!");
 //}

$mensaje->setDatos($response);
echo($mensaje->encode());
?>	

<?php

//----------------   CREAR CUENTA   ----------------------

//añadimos un nuevo usuario y cargamos de nuevo la lista de los usuarios, si el nombre no esta repetido--OK
function crearcuenta($nombreEmpleado,$tipo,$cuenta,$mensaje){
	if (!$cuenta->set_usuario($nombreEmpleado,$tipo))  $mensaje->setMensaje("El nombre que pusiste ya tiene una cuenta abierta");
	else $response = loadusuarios($cuenta);
	return $response;		
}

//----------------  LOAD USUARIOS   -----------------------
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

//--------------   ELIMINAR CUENTA  -----------------------
function eliminarcuenta($cuentadelete, $caja, $mensaje){
	$cuentaDel = substr($cuentadelete, 1);
	$debt = $caja->exist_debt($cuentaDel);
	if ($debt) $mensaje->setMensaje("No puedes eliminar esta cuenta porque el cliente debe dinero al hotel!");
	else{
		$caja->cuenta_delete($cuentaDel);
		$mensaje->setMensaje("La cuenta que elegiste se eliminó!");
	}
	return loadusuarios($caja);
}
//--------------  IMPRIMIR CUENTA   -----------------------------
function imprcuenta($idusuario,$fechaStart,$fechaStop, $caja){
	if ($fechaStart && $fechaStop){
		$iduser = substr($idusuario, 1);
		$response = imprtickets($caja,$iduser,$fechaStart,$fechaStop);	
		$response += imprmovimientos($caja,$iduser,$fechaStart,$fechaStop);	
		$totalTickets=$caja->total_cuenta($iduser);
		$response["TotalTickets"]=$totalTickets;
	} else $response = loadcuenta($idusuario, $caja);
	return $response;
}
//--------------  LOAD CUENTA   -----------------------------
//cargamos de nuevo los tiquets y los movimientos para el usuario elegido--OK
function loadcuenta($idusuario, $caja){
	if($idusuario){	
		$iduser = substr($idusuario, 1);
		$response = loadtickets($caja,$iduser);	
		$response += loadmovimientos($caja,$iduser);	
		$totalTickets=$caja->total_cuenta($iduser);
		$response["TotalTickets"]=$totalTickets;
	}
	return $response;
}

//--------------- INSERTAR MOVIMIENTO   ----------------------
//insertar un nuevo movimiento como credito.en la caja y en la cuenta de usuarios--OK
function insertarmovimiento($idempleado,$description,$categoria,$idencargado, $dinero, $caja){
	$idemp = substr($idempleado, 1);
	$onoma=$caja->nameUser($idemp);
	$idMov=$caja->insert_movimiento("credito",0,$onoma.": ".$description,$categoria,$idencargado);
	$caja->insert_mov_credito($idMov,$dinero,$idemp,0,"HR");
	//$response = loadtickets($caja,$idemp);	
	$response = loadmovimientos($caja,$idemp);
	$totalTickets=$caja->total_cuenta($idemp);
	$response["TotalTickets"]=$totalTickets;
	return $response;	
}

//cobrar una parte del credito.despues lo insertamos como movimiento en la caja y el la cuenta de usuarios--OK
//--------------  PAGAR CREDITO   -----------------------------	
function pagarcredito($idempleado,$money,$idencargado, $caja){
	$iduser = substr($idempleado, 1);
	$onoma=$caja->nameUser($iduser);
	$idMov=$caja->insert_movimiento("entrada",$money,"Cobrado Credito ".$onoma,9,$idencargado);
	$caja->insert_mov_credito($idMov,-$money,$iduser,1,"HR");
	//$response = loadtickets($caja,$iduser);	
	$response = loadmovimientos($caja,$iduser);	
	$totalTickets=$caja->total_cuenta($iduser);
	$response["TotalTickets"]=$totalTickets;
	return $response;	
}

function loadtickets($caja, $iduser){
$tikets=$caja->get_usuarios_comandas($iduser);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"procedencia"=>$tikets[$i]->procedencia,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);	
}
function imprtickets($caja,$iduser,$fechaStart,$fechaStop){
$tikets=$caja->get_usuarios_comandas_fechas($iduser,$fechaStart,$fechaStop);
if ((sizeof($tikets))>0){
	  for($i=0;$i<count($tikets);$i++) {
	  $TicketsInfo[$i]=array("idComanda"=>$tikets[$i]->idComanda,"numComanda"=>$tikets[$i]->numComanda,"procedencia"=>$tikets[$i]->procedencia,"fechaHora"=>$tikets[$i]->fechaHora,"total"=>$tikets[$i]->total,"nombre"=>$tikets[$i]->nombre);
	  }
 }	
 $response["TicketsInfo"]=$TicketsInfo;
 return($response);		
}
function loadmovimientos($caja,$iduser){
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
function imprmovimientos($caja,$iduser,$fechaStart,$fechaStop){
$movimientos=$caja->get_usuarios_movimientos_fechas($iduser,$fechaStart,$fechaStop);
if ((sizeof($movimientos))>0){
	  for($i=0;$i<count($movimientos);$i++) {
	  if ($movimientos[$i]->tipo) $tipo="cobrado"; else $tipo="credito";
	  $MovimientosInfo[$i]=array("id_movimiento"=>$movimientos[$i]->id_movimiento,"fechaHora"=>$movimientos[$i]->fechaHora,"tipo"=>$tipo,"dinero"=>$movimientos[$i]->dinero,"descripcion"=>$movimientos[$i]->descripcion,"categoria"=>$movimientos[$i]->categoria,"encargado"=>$movimientos[$i]->encargado);
	  }
 }	
 $response["MovimientosInfo"]=$MovimientosInfo;
 return($response);	
	
}
function load_buscador_usuarios($caja,$mask){
$usuarios=$caja->buscador_usuarios($mask);
if ((sizeof($usuarios))>0){
	  for($i=0;$i<count($usuarios);$i++) {
	  $UsuariosInfo[$i]=array("idTrabajador"=>$usuarios[$i]->idTrabajador,"nombre"=>$usuarios[$i]->nombre);
	  }
 }
 $response["UsuariosInfo"]=$UsuariosInfo;
 return($response);		
}
?>
