<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Dcaja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/findcaja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cajacomanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/usuariocomanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cajamovimiento.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cajamovsuma.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cuentausuario.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/pedido.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/movimientocategoria.php');


class caja{
	
		public static $CONTADO=1;
		public static $VISA=2;
		private $caja;
		private $totalTipo;
		private $totalmov;
		
		function estado_caja(){
			$dtcj = new Dcaja();
			$rs = $dtcj->is_caja_open();
		
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["estado"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		function get_id_caja (){
		 $idcj = new Dcaja();
		 $idcaja = $idcj->get_id_caja ();
		 if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}	
		return $a;		
		}
		
		function open_caja($fondo,$turno){
		$opcj = new Dcaja();
		$rs = $opcj->open_caja($fondo,$turno);
		
		return $rs;
		}
		
		function get_fondo_caja(){
			$fcj = new Dcaja();
			$rs = $fcj->get_fondo_caja();
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["fondoInicial"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
		function get_turno_caja(){
			$fcj = new Dcaja();
			$rs = $fcj->get_turno_caja();
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["turno"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
		function close_caja($efectivoCerrar){
		$clcj = new Dcaja();
		$rs = $clcj->close_caja($efectivoCerrar);
		
		return $rs;
		}
		function cobrar_ticket($idComanda){
		$cbtc = new Dcaja();
		$rs = $cbtc->cobrar_ticket($idComanda);
		
		return $rs;
		}
		function cobrar_movimiento_credito($idmov){
		$cbtc = new Dcaja();
		$rs = $cbtc->cobrar_movimiento_credito($idmov);
		return $rs;
		}
		function anular_ticket($idComanda){
		$id = substr($idComanda, 1);
		$antc = new Dcaja();
		$rs = $antc->anular_ticket($id );
		return $rs;
		}
		function anular_movimiento($idMovimiento){
		$antc = new Dcaja();
		$rs = $antc->anular_movimiento($idMovimiento);
		
		return $rs;
		}

		function facturar_ticket($idComanda){
		$ftc = new Dcaja();
		$rs = $ftc->facturar_ticket($idComanda);
		return $rs;	
		}
		//ti einai auto???den iparxei i function get_caja_by_id sti Dcaja...
		function get_caja($idcaja){
			$dtcl = new Dcaja();
			$rs = $dtcl->get_caja_by_id($idcaja);
			
			$this->caja=null;
			if ($rs->getRecordCount()>0){
			$rs->next();
				$resultat=$rs->getRow();
				$this->caja[0] = $resultat;
			}
			return $rs->getRecordCount();
		}
		
		function insert_movimiento($tipo,$dinero,$descripcion,$categoria,$idencargado){
			$datos = new Dcaja();
			$rs = $datos->insert_movimiento($tipo,$dinero,$descripcion,$categoria,$idencargado);
			return $rs;
		}
		
		function insert_mov_credito($idMov,$money,$iduser,$cobrado){
			$datos = new Dcaja();
			$rs = $datos->insert_mov_credito($idMov,$money,$iduser,$cobrado);
			return $rs;
		}
		
		function nameUser($iduser){
		    $datos = new Dcaja();
			$rs = $datos->nameUser($iduser);
			return $rs;
		}
		
		function nameEncargado($iduser){
		    $datos = new Dcaja();
			$rs = $datos->nameEncargado($iduser);
			return $rs;	
		}
		function total_tickets(){
			$fcj = new Dcaja();
			$rs = $fcj->total_tickets ();
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["totalTickets"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
	function find_caja($inicio,$fin){
	  $fc = new Dcaja();
	  $rs = $fc->find_caja($inicio,$fin);
	  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$n] = new FindCaja($result["id_caja"],$result["fechaHoraApertura"],$result["fechaHoraCierre"],$result["fondoInicial"],$result["EfectivoCerrar"]);
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $ors;	
	}
	function find_one_caja($idcaja){
	  $fc = new Dcaja();
	  $rs = $fc->find_one_caja($idcaja);
	  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$n] = new FindCaja($result["id_caja"],$result["fechaHoraApertura"],$result["fechaHoraCierre"],$result["fondoInicial"],$result["EfectivoCerrar"]);
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $ors;	
	}
	function ld_tickets_old($id_caja){
	$ldtckold = new Dcaja();
	$rs = $ldtckold->load_tickets($id_caja);
	//echo($rs->getRecordCount());
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new CajaComanda($result["idComanda"],$result["numComanda"],$result["estado"],$result["fechaHora"],$result["total"],$result["efectivo"],$result["clientType"],$result["nombre"],$result["free"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;	
}
 function load_movimientos_old($idcaja){
	$ldmkold = new Dcaja();
	$rs = $ldmkold->load_movimientos($idcaja);
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new CajaMovimiento($result["id_movimiento"],$result["fechaHora"],$result["tipo"],$result["dinero"],$result["descripcion"],$result["categoria"],$result["encargado"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;	
 }
 function total_tickets_old($idcaja){
			$tto = new Dcaja();
			$rs = $tto->total_tickets_old ($idcaja);
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["totalTickets"];
				}
		}else{$a="0";}
			if ($a==null)$a="0";
			return $a;
		}
function total_mov_old($idcaja){
	$mvcj = new Dcaja();
	$rs = $mvcj->total_money_mov_old($idcaja);
	if($rs->getRecordCount()>0){ 
		$cms=new CajaMovSuma();
		while($rs->next()){
		$result=$rs->getRow();
		if ($result["tipo"]=="entrada") $cms->setEntrada($result["suma"]); 
		else if ($result["tipo"]=="salida") $cms->setSalida($result["suma"]);	
		else if ($result["tipo"]=="ventaR")	$cms->setVentaR($result["suma"]);
		}
    }return $cms;	
}	

function get_fondo_caja_old($idcaja){
			$fcj = new Dcaja();
			$rs = $fcj->get_fondo_caja_old($idcaja);
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["fondoInicial"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
function are_tiquets_cobrados(){
	$tc = new Dcaja();
	$rs = $tc->are_tiquets_cobrados();
    $i=0;
	if($rs>0){
		if($rs->getRecordCount()>0){
		  $rs->next();
		  do{
		  $result=$rs->getRow();
		  $idComanda[$i]=$result["idComanda"];
		  $i++;
		  }while($rs->next());					
		}
	}
	return $idComanda;
}
function get_usuarios(){
$u = new Dcaja();
$rs = $u->get_usuarios();
//echo($rs->getRecordCount());
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new CuentaUsuario($result["idTrabajador"],$result["nombre"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;		
}

function set_usuario($nombreEmpleado,$cliente){
  $u = new Dcaja();
  $rs = $u->set_usuario($nombreEmpleado,$cliente);
}

function get_usuarios_comandas($idusuario){
$uc = new Dcaja();
$rs = $uc->get_usuarios_comandas ($idusuario);
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new usuariocomanda($result["idComanda"],$result["numComanda"],$result["cobrado"],$result["fechaHora"],$result["total"],$result["clientType"],$result["nombre"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;			
}
function get_usuarios_movimientos ($idusuario){
$uc = new Dcaja();
$rs = $uc->get_usuarios_movimientos ($idusuario);
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new CajaMovimiento($result["id_movimiento"],$result["fechaHora"],$result["tipo"],$result["dinero"],$result["descripcion"],$result["categoria"],$result["encargado"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;	
}
function total_cuenta($idusuario){
$tot = new Dcaja();
$rs = $tot->total_cuenta($idusuario);
if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["total"];
				}													
		  }else{
				$a="0";
			}
			return $a;	
}
function get_pedido($idComanda){
$gp = new Dcaja();
$rs = $gp->get_pedido($idComanda);
//echo($rs->getRecordCount());
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new Pedido($result["idPlatillo"],$result["cantidad"],$result["nombre"],$result["precio"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;			
}	

function get_pedido_bar($id){
$gpb = new Dcaja();
$rs = $gpb->get_pedido_bar($id);
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new Pedido($result["numBebida"],$result["cantidad"],$result["nombre"],$result["precio"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;	
}

function get_categories(){
$gc = new Dcaja();
$rs = $gc->get_mov_categories();
   if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new MovimientoCategoria($result["id_categoria"],$result["nombre"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;
						
}
		
	function get_entrada(){
	for($i=0;$i<count($this->totalTipo);$i++){
		if ($this->totalTipo[$i]=="entrada") $result=$this->totalmov[$i];
	}
	return $result; 
	}
	function get_salida(){
	for($i=0;$i<count($this->totalTipo);$i++){
		if ($this->totalTipo[$i]=="salida") $result=$this->totalmov[$i];
	}
	return $result; 
	}
	   
	function movenext(){
		return next($this->caja);		
	}
	
	function get_count(){
		return count($this->caja);
	}
	
	function get_id_linea(){
		$a=current($this->caja);
		return $a['Id_movimiento'];
	}
	function get_descripcion(){
		$a=current($this->caja);
		return $a['descripcion']; 
	}	


}

?>
