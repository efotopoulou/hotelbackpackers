<?php

require_once ('Comunication.php');

class Dlistado{
	
	const GET_CLIENTES = 'SELECT * FROM cliente ORDER BY nombre';


	public function get_clientes(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_CLIENTES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	

}
?>