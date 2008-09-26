<?php
require_once ('ComunicationRecep.php');

class Dmesas{
	
	const GET_NUMERO_MESAS = 'SELECT numeroDeMesas FROM mesa';
	const MODIFICAR_MESAS = 'UPDATE mesa SET numeroDeMesas=?';
	
	
	public function get_no_mesas(){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_NUMERO_MESAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function modificar_mesas ($numeroDeMesas){
		$comunication = new ComunicationRecep();
		$PARAMS = array($numeroDeMesas);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->update(self::MODIFICAR_MESAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
