<?php
require_once ('ComunicationRes.php');

class Dmesas{
	
	const GET_NUMERO_MESAS = 'SELECT numeroDeMesas FROM mesa';
	const MODIFICAR_MESAS = 'UPDATE mesa SET numeroDeMesas=?';
	
	
	public function get_no_mesas(){
		$comunication = new ComunicationRes();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_NUMERO_MESAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function modificar_mesas ($numeroDeMesas){
		$comunication = new ComunicationRes();
		$PARAMS = array($numeroDeMesas);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->update(self::MODIFICAR_MESAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
