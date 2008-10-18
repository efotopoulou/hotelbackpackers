<?php

require_once ('ComunicationRestBar.php');

class Dturno{
	
	const GET_TURNO_CAJA = 'SELECT turno from caja where estado=1';
	
	public function get_turno_caja (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_TURNO_CAJA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
