<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dmesas.php');
 
class mesas{
		private $numeroMesas;
		
		//error Message_Box
		public static $ID=6;
		
		public static $OK=1;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR=-3;

		function get_mesas(){
			$dtm = new Dmesas();
			$rs = $dtm->get_no_mesas();
			
			$this->numeroMesas=null;
			
			
		  if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["numeroDeMesas"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
						
		}
		function modificar_mesas($numeroDeMesas){
		$mdm = new Dmesas();
		$rs = $mdm->modificar_mesas($numeroDeMesas);
		}
		
}
?>
