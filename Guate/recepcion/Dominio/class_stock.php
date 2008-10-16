<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dstock.php');
require ('stockbar.php');
require ('stockreception.php');
require ('ventaturno.php');

class stock{
		private $pla;
		
        function get_stock(){
        $dtp = new Dstock();
        $rs = $dtp->get_stock();
			
          if ($rs->getRecordCount()>0){
	       $n=0;
	     while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new StockBar($result["idBebida"],$result["numBebida"],$result["familia"],$result["nombre"],$result["stockbar"],$result["stockrestaurante"],$result["unidadventa"]);
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $ors;				
		}
		
		function add_stock($idbebida,$stockbar,$stockrestaurante,$unidadventa){
		$as = new Dstock();
		$rs = $as->add_stock($idbebida,$stockbar,$stockrestaurante,$unidadventa);
		}
		
		function get_stock_bebida($idbebida){
		$gsb = new Dstock();
		$rs = $gsb->get_stock_bebida($idbebida);	
		return $rs;
		}
		
		function informar_controlstock($idBebida,$cantidad){
		$ics = new Dstock();
		$rs = $ics->informar_controlstock($idBebida,$cantidad);		
		}
		
		function informar_stock_rest($idbebida,$cantidad){
		$ics = new Dstock();
		$rs = $ics->informar_stock_rest($idbebida,$cantidad);		
		}
		
		function get_stockreception(){
		$gsr = new Dstock();
        $rs = $gsr->get_stockreception();
        	
          if ($rs->getRecordCount()>0){
	       $n=0;
	     while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new StockReception($result["nombre"],$result["idBebida"],$result["precioNormal"],$result["precioLimitado"]);
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $ors;	
		}
		
		function venta_turno(){
		$gsr = new Dstock();
        $rs = $gsr->venta_turno();
        	
          if ($rs->getRecordCount()>0){
	       $n=0;
	     while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new VentaTurno($result["numBebida"],$result["nombre"],$result["suma"]);
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $ors;	
		}
		
		function recuperar_venta(){
		$gsb = new Dstock();
		$rs = $gsb->recuperar_venta();		
		}
		
		function recuperar_venta_caja(){
		$gsb = new Dstock();
		$rs = $gsb->recuperar_venta_caja();		
		return $rs;
		}
		
		function venta_turno_bar(){
		$gsb = new Dstock();
		$rs = $gsb->venta_turno_bar();		
		return $rs;
		}
		
		
}
?>

