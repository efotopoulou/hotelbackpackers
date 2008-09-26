<?php

require_once ('ComunicationRecep.php');

class Dplatillos{
	
	const GET_PLATILLOS = 'SELECT p.idPlatillo,p.nombre,p.precioLimitado,p.precioNormal FROM platillo p, familia f WHERE f.id_familia=p.id_familia and f.nombre=?';
	const GET_INFO_PLATOS = 'SELECT p.idPlatillo,p.nombre,p.precioLimitado,p.precioNormal,p.cocina,f.color,f.nombre as familia FROM platillo p, familia f WHERE f.id_familia=p.id_familia order by f.nombre';
    const IS_FAMILY_FREE = 'select idPlatillo from platillo where id_familia=?';
	const DELETE_PLATILLO = 'delete from platillo where idPlatillo=?';
	const INSERT_PLATILLO = 'insert into platillo values (?,?,?,?,?,?)';
	const MODIFICAR_PLATILLO = 'UPDATE platillo SET precioLimitado=?,precioNormal=?,cocina=? where idPlatillo=?';
	
	
	public function get_platillos ($familia){
		$comunication = new ComunicationRes();
		$PARAMS = array($familia);
		$PARAMS_TYPES = array (ComunicationRes::$TSTRING);
		$result = $comunication->query(self::GET_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_info_platos (){
		$comunication = new ComunicationRes();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_INFO_PLATOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function is_family_free ($idfamilia){
		$comunication = new ComunicationRes();
		$PARAMS = array($idfamilia);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->query(self::IS_FAMILY_FREE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function delete_platillo ($idPlatillo){
		$comunication = new ComunicationRes();
		$PARAMS = array($idPlatillo);
		$PARAMS_TYPES = array (ComunicationRes::$TSTRING);
		$result = $comunication->query(self::DELETE_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function insert_platillo ($idPlato,$nameplato,$precioN,$precioL,$platoFamily,$cocina){
		$comunication = new ComunicationRes();
		$PARAMS = array($idPlato,$nameplato,$precioN,$precioL,$cocina,$platoFamily);
		$PARAMS_TYPES = array (ComunicationRes::$TSTRING,ComunicationRes::$TSTRING,ComunicationRes::$TFLOAT,ComunicationRes::$TFLOAT,ComunicationRes::$TINT,ComunicationRes::$TINT);
		$result = $comunication->update(self::INSERT_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function modificar_platillo ($precioLmod,$precioNmod,$cocina,$idplatomod){
		$comunication = new ComunicationRes();
		$PARAMS = array($precioLmod,$precioNmod,$cocina,$idplatomod);
		$PARAMS_TYPES = array (ComunicationRes::$TFLOAT,ComunicationRes::$TFLOAT,ComunicationRes::$TINT,ComunicationRes::$TSTRING);
		$result = $comunication->update(self::MODIFICAR_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
}
?>

