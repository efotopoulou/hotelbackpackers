<?php

require_once ('ComunicationRecep.php');

class Dsugerencia{
	
	const SET_TEXTO = 'INSERT INTO sugerencia VALUES (0,?,NOW())';
	
	public function setTexto ($texto){
		$comunication = new ComunicationRecep();
		$PARAMS = array($texto);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::SET_TEXTO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
}
?>
