<?php

require_once ('ComunicationRecep.php');

class Dusers{
	
		const GET_COUNT_USERS = 'SELECT count(*) as count FROM guate_bd.usuario WHERE deleted=0 ';
	const GET_USERS = 'SELECT u.nombre, p.nombre as perfil FROM guate_bd.usuario u, guate_bd.perfil p WHERE u.Id_perfil=p.Id_perfil AND u.deleted=0 ORDER BY ? ? LIMIT ? , ?';
	
	//Devuelve la informacion de los usuarios y a que perfiles 
	public function getUsers($sidx, $sord, $start, $limit){
		$comunication = new ComunicationRecep();
		$PARAMS = array($sidx, $sord, $start, $limit);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_USERS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function getCountUsers(){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_COUNT_USERS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}

}
?>
