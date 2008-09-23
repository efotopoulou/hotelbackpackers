<?php

require_once ('Comunication.php');

class Dlog{
	
	const INSERT_LOG = 'INSERT INTO logs VALUES (?,?,?,?,NOW())';


	public function insert_log($id_usuario, $id_accion, $id_evento){
		$params = array(0, $id_usuario, $id_accion, $id_evento);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT, Comunication::$TINT, Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INSERT_LOG,$params,$PARAMS_TYPES);
		return $result;
	}

}
?>