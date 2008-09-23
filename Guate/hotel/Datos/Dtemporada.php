<?php

require_once ('Comunication.php');

class Dtemporada{
	
	const tempo = 'Select * from temporada ORDER BY fecha_ini';
	
	const preu = 'Select precio from precio where Id_aloj=? and Id_tipo=?';
	
	const PRECIOS_ALL = 'SELECT precio.Id_aloj, precio FROM precio, alojamiento 
						WHERE alojamiento.Id_aloj=precio.Id_aloj 
						AND (alojamiento.Id_parent=0 OR alojamiento.Id_aloj=Id_parent) 
						AND precio.Id_temp=? ORDER BY alojamiento.orden, alojamiento.nombre';
	
	const INSERT_TEMPO = 'INSERT INTO temporada VALUES (?,?,?,?)';
	
	const UPDATE_TEMPO = 'UPDATE temporada SET nombre_temp = ?, fecha_ini = ?, fecha_fin = ? WHERE Id_temp=?';
			
	const DELETE_TEMPO = 'DELETE FROM temporada WHERE Id_temp = ?';
		
	public function get_tempo (){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::tempo,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
	public function get_preu($aloj,$tipo){
		$comunication = new Comunication();
		$PARAMS = array($aloj,$tipo);
		$PARAMS_TYPES = array (Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->query(self::preu,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}

	public function get_precios_all($temp){
		$comunication = new Comunication();
		$PARAMS = array($temp);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::PRECIOS_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	
	public function insert_tempo($nombre, $fec_ini, $fec_fin){
		$params = array(0, $nombre, $fec_ini, $fec_fin);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->update(self::INSERT_TEMPO,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function update_tempo($id_tempo, $nombre, $fec_ini, $fec_fin){
		$params = array($nombre, $fec_ini, $fec_fin, $id_tempo);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TSTRING,Comunication::$TDATE,Comunication::$TDATE, Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_TEMPO,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function delete_tempo($id_tempo){
		$params = array($id_tempo);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_TEMPO,$params,$PARAMS_TYPES);
		return $result;
	}
}
?>