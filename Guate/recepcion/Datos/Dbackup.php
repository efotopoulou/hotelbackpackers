<?php

require_once ('ComunicationRecep.php');

class Dbackup{
	const SETBACKUP = 'INSERT INTO backup values(?,0)';
   
    public function setbackup($text){
	 	$comunication = new ComunicationRecep();
		$params = array($text);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$comanda = $comunication->query(self::SETBACKUP,$params,$PARAMS_TYPES);
		return $comanda;
	}
}
?>

