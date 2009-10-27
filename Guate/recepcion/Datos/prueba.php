<?php

require_once ('c:/www/Guate/common/Datos/ComunicationRecep.php');


	 	$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$comanda = $comunication->query('INSERT INTO prueba values("hola",NOW())',$params,$PARAMS_TYPES);
?>

