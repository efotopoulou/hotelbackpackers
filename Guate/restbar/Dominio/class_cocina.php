<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Dcocina.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/pedidoscocina.php');

class cocina{

 function select_pedidos(){
   $pc = new Dcocina();
   $rs = $pc->select_pedidos();
		if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new PedidosCocina($result["idCocina"],$result["comanda"],$result["platoId"],$result["nombre"],$result["cantidad"],$result["hora"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;
  }	
  
function delete_pedidos(){
   $dp = new Dcocina();
   $rs = $dp->delete_pedidos();
  return $rs;
  }	
function eliminar_pedido($eliminarpedidoid){
   $el = new Dcocina();
   $rs = $el->eliminar_pedido($eliminarpedidoid);
  return $rs;
}
function recuperar_pedido (){
   $rl = new Dcocina();
   $rs = $rl->recuperar_pedido();
  return $rs;
}
  
}
?>

