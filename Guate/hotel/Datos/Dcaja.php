<?php

require_once ('Comunication.php');

class Dcaja{
	
	const GET_CAJA_BY_ID = 'SELECT * from caja WHERE Id_movimiento=?';
	
	const GET_CAJA_BY_DATES = 'SELECT * from caja where fecha>=? AND fecha<=?';

	const INS_MOV = 'INSERT INTO caja VALUES(?,NOW(),?,?,?,?,?)';
	
	
	public function get_caja_by_id ($idcaja){
		$comunication = new Comunication();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_CAJA_BY_ID,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_caja_by_dates ($fecha1,$fecha2){
		$comunication = new Comunication();
		$PARAMS = array($fecha1,$fecha2);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->query(self::GET_CAJA_BY_DATES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function insert_movimiento($importe,$modopago,$id_checkin,$id_factura,$descripcion){
		$comunication = new Comunication();
		$params = array(0,$importe,$modopago,$id_checkin,$id_factura,$descripcion);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TFLOAT,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT,Comunication::$TSTRING);
		$result = $comunication->update(self::INS_MOV,$params,$PARAMS_INSERT);
		
		return $result;
	}
	
}
?>