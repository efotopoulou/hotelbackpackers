<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dturno.php');

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
		
}
?>

