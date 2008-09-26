<?php

require_once ('ComunicationRes.php');

class Dbackup{
	const SETBACKUP = 'INSERT INTO backup values(?,0)';
   
    public function setbackup($text){
	 	$comunication = new ComunicationRes();
		$params = array($text);
		$PARAMS_TYPES = array (ComunicationRes::$TSTRING);
		$comanda = $comunication->query(self::SETBACKUP,$params,$PARAMS_TYPES);
		return $comanda;
	}
}
?>

