<?php

require_once ('ComunicationRecep.php');

class Dfamilia{
	
	const GET_FAMILIAS = 'SELECT * FROM familia';
	const DELETE_FAMILY = 'delete from familia where id_familia=?';
	const INSERT_FAMILY = 'insert into familia values (0,?,?)';
	const MODIFICAR_FAMILY = 'UPDATE familia SET nombre=? where id_familia=?';
	
	public function get_familias (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_FAMILIAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function delete_family ($idfamily){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idfamily);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::DELETE_FAMILY,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function insert_family ($nombre,$color){
		$comunication = new ComunicationRecep();
		$PARAMS = array($nombre,$color);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING);
		$result = $comunication->update(self::INSERT_FAMILY,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function modificar_family ($namefamilymod,$famidmod){
		$comunication = new ComunicationRecep();
		$PARAMS = array($namefamilymod,$famidmod);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TINT);
		$result = $comunication->update(self::MODIFICAR_FAMILY,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
