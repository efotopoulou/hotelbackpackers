<?php

require_once ('ComunicationRecep.php');

class Dplatillos{
	
	const GET_PLATILLOS = 'SELECT p.idPlatillo,p.nombre,p.precioLimitado,p.precioNormal FROM platillo p, familia f WHERE f.id_familia=p.id_familia and f.nombre=? and p.deleted=0';
	const GET_INFO_PLATOS = 'SELECT p.idPlatillo,p.nombre,p.precioLimitado,p.precioNormal,p.cocina,f.color,f.nombre as familia FROM platillo p, familia f WHERE f.id_familia=p.id_familia and p.deleted=0 order by f.nombre';
    const IS_FAMILY_FREE = 'select idPlatillo from platillo where id_familia=? and deleted=0';
	const HAS_PLATILLO_COMANDAS = 'select count(t1.idComanda) as numcomandas from comanda t1,lineacomanda t2 where t1.idComanda=t2.idComanda and t2.idPlatillo=?';
	const DELETE_PLATILLO = 'delete from platillo where idPlatillo=?';
	const NO_SHOW_PLATILLO = 'UPDATE platillo SET deleted=1 where idPlatillo=?';
	const INSERT_PLATILLO = 'insert into platillo values (?,?,?,?,?,?,0)';
	const MODIFICAR_PLATILLO = 'UPDATE platillo SET precioLimitado=?,precioNormal=?,cocina=? where idPlatillo=?';
	
	
	public function get_platillos ($familia){
		$comunication = new ComunicationRecep();
		$PARAMS = array($familia);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::GET_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_info_platos (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_INFO_PLATOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function is_family_free ($idfamilia){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idfamilia);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::IS_FAMILY_FREE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function delete_platillo ($idPlatillo){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idPlatillo);
		$PARAMS_TYPES = array(ComunicationRecep::$TINT);
		$numcomandas = $comunication->query(self::HAS_PLATILLO_COMANDAS,$PARAMS,$PARAMS_TYPES);
	     
	     if ($numcomandas->getRecordCount()>0){
			while($numcomandas->next()){
				$resultc=$numcomandas->getRow();
				$a=$resultc["numcomandas"];
				}}	
		
		$PARAMS = array($idPlatillo);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
        
        //en la base de datos deleted=1 significa que el platillo no aparece en la gestionplatillos pero tiene comandas
		//si el platillo no tiene comandas,lo eliminamos
		//else no eliminamos el platillo pero el platillo no aparece mas en los platillos	
		
		if ($a==0) $result = $comunication->query(self::DELETE_PLATILLO,$PARAMS,$PARAMS_TYPES);
		else $result = $comunication->query(self::NO_SHOW_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function insert_platillo ($idPlato,$nameplato,$precioN,$precioL,$platoFamily,$cocina){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idPlato,$nameplato,$precioN,$precioL,$cocina,$platoFamily);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->update(self::INSERT_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function modificar_platillo ($precioLmod,$precioNmod,$cocina,$idplatomod){
		$comunication = new ComunicationRecep();
		$PARAMS = array($precioLmod,$precioNmod,$cocina,$idplatomod);
		$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->update(self::MODIFICAR_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
}
?>

