<?php

require_once ('ComunicationRecep.php');

class Dturno{
	
	const GET_TURNO_CAJA = 'SELECT turno from caja where estado=1';
	
	public function get_turno_caja (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_TURNO_CAJA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
