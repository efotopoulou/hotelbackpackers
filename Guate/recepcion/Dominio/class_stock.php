<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dstock.php');
require ('stockbar.php');
require ('stockreception.php');

class stock{
		private $pla;
		
        function get_stock(){
        $dtp = new Dstock();
        $rs = $dtp->get_stock();
			
          if ($rs->getRecordCount()>0){
	       $n=0;
	     while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new StockBar($result["idBebida"],$result["numBebida"],$result["nombre"],$result["stockbar"],$result["stockrestaurante"],$result["unidadventa"]);
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $ors;				
		}
		
		function add_stock($addornew,$idbebida,$stockbar,$stockrestaurante,$unidadventa){
		$as = new Dstock();
		$rs = $as->add_stock($addornew,$idbebida,$stockbar,$stockrestaurante,$unidadventa);
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
		$ors[$n] = new StockReception($result["idBebida"],$result["numBebida"],$result["nombre"]);
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $ors;	
		}
		
		
}
?>

