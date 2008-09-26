<?php

require_once ('ComunicationBar.php');

class Dbebidas{
	
	const GET_BEBIDAS = 'SELECT b.idBebida,b.numBebida,b.nombre,b.precioLimitado,b.precioNormal FROM bebida b, familia f WHERE f.id_familia=b.id_familia and f.nombre=?';
	const GET_INFO_BEBIDAS = 'SELECT b.idBebida,b.numBebida,b.nombre,b.precioLimitado,b.precioNormal,f.color,f.nombre as familia FROM bebida b, familia f WHERE f.id_familia=b.id_familia order by f.nombre';
    const IS_FAMILY_FREE = 'select idBebida from bebida where id_familia=?';
	const DELETE_BEBIDA = 'delete from bebida where idBebida=?';
	const INSERT_BEBIDA = 'insert into bebida values (0,?,?,?,?,?)';
	const INSERT_BEBIDA_STOCK = 'insert into stockbebidas values (?,0,0)';
	const DELETE_BEBIDA_STOCK = 'delete from stockbebidas where idBebida=?';
	const MODIFICAR_BEBIDA = 'UPDATE bebida SET precioLimitado=?,precioNormal=? where idBebida=?';
	
	
	public function get_bebidas ($familia){
		$comunication = new ComunicationBar();
		$PARAMS = array($familia);
		$PARAMS_TYPES = array (ComunicationBar::$TSTRING);
		$result = $comunication->query(self::GET_BEBIDAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_info_bebidas (){
		$comunication = new ComunicationBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_INFO_BEBIDAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function is_family_free ($idfamilia){
		$comunication = new ComunicationBar();
		$PARAMS = array($idfamilia);
		$PARAMS_TYPES = array (ComunicationBar::$TINT);
		$result = $comunication->query(self::IS_FAMILY_FREE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function delete_bebida($idbebida){
		$comunication = new ComunicationBar();
		$PARAMS = array($idbebida);
		$PARAMS_TYPES = array (ComunicationBar::$TSTRING);
		$id = $comunication->update(self::DELETE_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		$PARAMS = array($id);
		$PARAMS_TYPES = array (ComunicationBar::$TINT);
		$result = $comunication->update(self::DELETE_BEBIDA_STOCK,$PARAMS,$PARAMS_TYPES);
		
	}
	public function  insert_bebida($idbebida,$namebebida,$precioL,$precioN,$bebidaFamily){
		$comunication = new ComunicationBar();
		$PARAMS = array($idbebida,$namebebida,$precioL,$precioN,$bebidaFamily);
		$PARAMS_TYPES = array (ComunicationBar::$TINT,ComunicationBar::$TSTRING,ComunicationBar::$TFLOAT,ComunicationBar::$TFLOAT,ComunicationBar::$TINT);
		$id = $comunication->update(self::INSERT_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		$PARAMS = array($id);
		$PARAMS_TYPES = array (ComunicationBar::$TINT);
		$result = $comunication->update(self::INSERT_BEBIDA_STOCK,$PARAMS,$PARAMS_TYPES);
		
		
	}
	public function modificar_bebida ($precioLmod,$precioNmod,$idbebida){
		$comunication = new ComunicationBar();
		$PARAMS = array($precioLmod,$precioNmod,$idbebida);
		$PARAMS_TYPES = array (ComunicationBar::$TFLOAT,ComunicationBar::$TFLOAT,ComunicationBar::$TSTRING);
		$result = $comunication->update(self::MODIFICAR_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
}
?>
