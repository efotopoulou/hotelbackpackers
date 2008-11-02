<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Dturno.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/ventabar.php');


class turno{

		function get_turno_caja(){
			$fcj = new Dturno();
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
		
function venta_turno_bar($idcaja){
	$uc = new Dturno();
    $rs = $uc->venta_turno_bar($idcaja);
	if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] =new VentaBar($result["numBebida"],$result["nombre"],$result["suma"],$result["precio"]);
		$n++;			
		}
    }else{
		$result=null;
	    }
	return $ors;
		}
		
}
?>

