<?php

require_once ('Comunication.php');

class Dhabitaciones{

	const GET_TIPOS = 'SELECT * from alojamiento_tipo';
	
	const GET_ALOJ_ALL = 'SELECT *
				FROM alojamiento, alojamiento_tipo 
				WHERE alojamiento.Id_tipo=alojamiento_tipo.Id_tipo
						AND (alojamiento.Id_parent=0 OR alojamiento.Id_parent=alojamiento.Id_aloj) 
				ORDER BY orden, nombre';

	const GET_ALOJ = 'SELECT * 
				FROM alojamiento, alojamiento_tipo 
				WHERE alojamiento.Id_tipo=alojamiento_tipo.Id_tipo
						AND alojamiento.Id_aloj=?'; 
	
	const GET_ALOJ_BY_NAME = 'SELECT * 
				FROM alojamiento, alojamiento_tipo 
				WHERE alojamiento.Id_tipo=alojamiento_tipo.Id_tipo
						AND alojamiento.nombre=?'; 
									
	const GET_LITERAS = 'SELECT * 
				FROM alojamiento, alojamiento_tipo 
				WHERE alojamiento.Id_tipo=alojamiento_tipo.Id_tipo
						AND alojamiento.Id_parent=?
						AND alojamiento.Id_parent<>alojamiento.Id_aloj
				ORDER BY orden, nombre';	
	
	const DELETE_LITERAS = 'DELETE FROM alojamiento WHERE Id_parent = ?';
	
	const INSERT_ALOJ = 'INSERT INTO alojamiento VALUES (?,?,?,?,?,?,?)';
	const UPDATE_ALOJ = 'UPDATE alojamiento SET Id_tipo=?, nombre=?, num_matrim=?, num_indiv=?, Id_parent=?, orden=? WHERE Id_aloj=?';			
	const DELETE_ALOJ = 'DELETE FROM alojamiento WHERE Id_aloj = ?';
	
	const INSERT_TIPO = 'INSERT INTO alojamiento_tipo VALUES (?,?,?)';
	const UPDATE_TIPO = 'UPDATE alojamiento_tipo SET descripcion=?, color=? WHERE Id_tipo=?';
	const DELETE_TIPO = 'DELETE FROM alojamiento_tipo WHERE Id_tipo = ?';
	
	public function insert_aloj ($Id_tipo, $nombre,$num_matrim,$num_indiv,$Id_parent,$orden){
		$comunication = new Comunication();
		$id=0;
		$params = array(0,$Id_tipo, $nombre,$num_matrim,$num_indiv,$Id_parent,$orden);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TSTRING,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::INSERT_ALOJ,$params,$PARAMS_INSERT,$id);
		return $id;
	}		
	
	public function update_aloj ($Id_aloj, $Id_tipo, $nombre,$num_matrim,$num_indiv,$Id_parent,$orden){
		$comunication = new Comunication();
		$params = array($Id_tipo, $nombre,$num_matrim,$num_indiv,$Id_parent,$orden, $Id_aloj);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_ALOJ,$params,$PARAMS_INSERT);
		return $result;
	}	
	
	public function delete_aloj ($Id_aloj){
		$comunication = new Comunication();
		$params = array($Id_aloj);
		$PARAMS_INSERT = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_ALOJ,$params,$PARAMS_INSERT);
		return $result;
	}
	
	public function delete_literas ($Id_parent){
		$comunication = new Comunication();
		$params = array($Id_parent);
		$PARAMS_INSERT = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_LITERAS,$params,$PARAMS_INSERT);
		return $result;
	}
			
	public function get_tipos (){
		$comunication = new Comunication();
		$PARAMS = array ();		
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_TIPOS,$PARAMS,$PARAMS_TYPES);
		return $result;	
	}
	
	public function insert_tipo ($descripcion, $color){
		$comunication = new Comunication();
		$params = array(0,$descripcion, $color);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TSTRING);
		$result = $comunication->update(self::INSERT_TIPO,$params,$PARAMS_INSERT);
		return $result;
	}
	
	public function update_tipo ($Id_tipo, $descripcion, $color){
		$comunication = new Comunication();
		$params = array($descripcion, $color, $Id_tipo);
		$PARAMS_INSERT = array(Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_TIPO,$params,$PARAMS_INSERT);
		return $result;
	}
	
	public function delete_tipo ($Id_tipo){
		$comunication = new Comunication();
		$params = array($Id_tipo);
		$PARAMS_INSERT = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_TIPO,$params,$PARAMS_INSERT);
		return $result;
	}
	
	public function get_aloj_all(){
		$comunication = new Comunication();
		$PARAMS = array ();		
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_ALOJ_ALL,$PARAMS,$PARAMS_TYPES);
		return $result;
	}

	public function get_literas($id_parent){
		$comunication = new Comunication();
		$PARAMS = array ($id_parent);		
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_LITERAS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_aloj($id_aloj){
		$comunication = new Comunication();
		$PARAMS = array ($id_aloj);		
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_ALOJ,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_aloj_by_name($nombre){
		$comunication = new Comunication();
		$PARAMS = array ($nombre);		
		$PARAMS_TYPES = array (Comunication::$TSTRING);
		$result = $comunication->query(self::GET_ALOJ_BY_NAME,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
}	
?>