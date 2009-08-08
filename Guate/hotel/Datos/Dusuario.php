<?php

require_once ('Comunication.php');

class Dusuario{
	
	const GET_USER_ALL = 'SELECT * FROM usuario ORDER BY nombre';

	const GET_USER_BY_PERFIL = 'SELECT * FROM usuario WHERE Id_perfil=? ORDER BY nombre';

	const GET_USER_BY_ID = 'SELECT Id_usuario, Id_perfil, nombre, email FROM usuario WHERE Id_usuario=?';
	
	const INSERT_USER = 'INSERT INTO usuario VALUES (?,?,?)';
	const UPDATE_USER = 'UPDATE usuario SET Id_perfil=?, nombre=? WHERE Id_usuario=?';	
	const DELETE_USER = 'DELETE FROM usuario WHERE Id_usuario=?';
		
	public function get_usuario_all(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_USER_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	

	public function get_usuario_by_perfil($id_perfil){
		$comunication = new Comunication();
		$PARAMS = array($id_perfil);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_USER_BY_PERFIL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function existe_usuario($id){
		$comunication = new Comunication();
		$PARAMS = array($id);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_USER_BY_ID,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}

	public function insert_usuario($id_perfil, $nombre){
		$params = array(0, $id_perfil, $nombre);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TSTRING);
		$result = $comunication->update(self::INSERT_USER,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function update_usuario($id_usuario, $id_perfil, $nombre){
		$params = array($id_perfil, $nombre, $id_usuario);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_USER,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function delete_usuario($id_usuario){
		$params = array($id_usuario);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_USER,$params,$PARAMS_TYPES);
		return $result;
	}

}
?>