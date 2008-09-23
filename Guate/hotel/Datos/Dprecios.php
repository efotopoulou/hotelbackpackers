<?php

require_once ('Comunication.php');

class Dprecios{
	
	const GET_TIPOS = 'SELECT * from precio_tipo';
	
	const PRECIOS_ALL = 'SELECT precio.Id_aloj, precio FROM precio, alojamiento 
						WHERE alojamiento.Id_aloj=precio.Id_aloj 
						AND (alojamiento.Id_parent=0 OR alojamiento.Id_aloj=Id_parent) 
						AND precio.Id_tipo=? ORDER BY alojamiento.orden, alojamiento.nombre';
	
	const INSERT_PRECIO = 'INSERT INTO precio VALUES (?,?,?)';
	
	const UPDATE_PRECIO = 'UPDATE precio SET precio=? WHERE Id_aloj=? AND Id_tipo=?';

	const DELETE_PRECIO = 'DELETE FROM precio WHERE Id_aloj=? AND Id_tipo=?';
	
	public function get_precios_all($tipo){
		$comunication = new Comunication();
		$PARAMS = array($tipo);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::PRECIOS_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	

	public function insert_precio($precio, $id_aloj, $id_tipo){
		$params = array($id_aloj, $id_tipo, $precio);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT, Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INSERT_PRECIO,$params,$PARAMS_TYPES);
		return $result;
	}

	public function update_precio($precio, $id_aloj, $id_tipo){
		$params = array($precio, $id_aloj, $id_tipo);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT, Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_PRECIO,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function delete_precio($id_aloj, $id_tipo){
		$params = array($id_aloj, $id_tipo);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::DELETE_PRECIO,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_tipos (){
		$comunication = new Comunication();
		$PARAMS = array ();		
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_TIPOS,$PARAMS,$PARAMS_TYPES);
		return $result;	
	}
	
}
?>