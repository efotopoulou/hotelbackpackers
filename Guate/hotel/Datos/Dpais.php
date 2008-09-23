<?php

require_once ('Comunication.php');

class Dpais{
	
	const GET_PAISES = 'SELECT * FROM pais order by nom_pais';
	
	const GET_ID_PAIS = 'Select Id_pais from pais where nom_pais=?';
	
	
	public function get_paises (){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_PAISES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_id_pais ($nompais){
		$comunication = new Comunication();
		$PARAMS = array($nompais);
		$PARAMS_TYPES = array (Comunication::$TSTRING);
		$result = $comunication->query(self::GET_ID_PAIS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}

}

?>