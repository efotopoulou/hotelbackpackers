<?php

require_once ('Comunication.php');

class Dfra{
	
	const GET_FRA_BY_ID = 'SELECT * from factura where Id_fra=?';

	const GET_FRAS_OPENED = 'SELECT * from factura where Num_fra IS NULL';
	
	const ALT_FRA = 'INSERT INTO factura VALUES(?,?,?,?,?,?,?,?,?,?,?)';
	
	const ALT_LINEA = 'INSERT INTO factura_lineas VALUES(?,?,?,?,?,?,?,?)';
	
	const ADD_LINEA_FRA= 'UPDATE factura SET num_lineas=num_lineas+1 where Id_fra=?';
	
	const MOD_FRA ='UPDATE factura SET total=? WHERE Id_fra=?';
	
	const PAGADO_FRA='UPDATE factura SET importe_pagado=importe_pagado+? WHERE Id_fra=?';
	
	const CERRAR_FRA='UPDATE factura SET nombre=?,nit=?,num_fra=?,impuesto=?,total=?,importe_pagado=?, fecha_fra=? WHERE Id_fra=?';
	
	const GET_LINEAS ='SELECT * from factura_lineas WHERE Id_fra=? ORDER BY Id_linea';
	
	const GET_LINEA ='SELECT * from factura_lineas WHERE Id_fra=? AND Id_linea=?';
	
	const MODIF_LINEA = 'UPDATE factura_lineas SET cantidad=?,descripcion=?,valor=?,recargo=?,descuento=?, Id_checkin=? WHERE Id_fra=? AND Id_linea=?';
	
	const ELIM_LINEA = 'DELETE from factura_lineas WHERE Id_fra=? AND Id_linea=?';
	
		public function get_fra_by_id ($idfra){
		$comunication = new Comunication();
		$PARAMS = array($idfra);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_FRA_BY_ID,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}
		
		public function get_fras_opened(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_FRAS_OPENED,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}
		
		public function insert_fra ($Id_cliente,$pendiente,$pagado,$fecha,$total,$nombre,$impuesto,$nit,$numfra){
		$id="";
		$comunication = new Comunication();
		$params = array(0,$Id_cliente,$pendiente,$pagado,$fecha,0,$total,$nombre,$impuesto,$nit,$numfra);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TFLOAT,Comunication::$TBOOLEAN,Comunication::$TDATE,Comunication::$TINT,Comunication::$TFLOAT,Comunication::$TSTRING,Comunication::$TFLOAT,Comunication::$TSTRING,Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::ALT_FRA,$params,$PARAMS_INSERT,$id);
	
		return $id;
		}	
		
		
		public function modif_factura ($total,$idfra){
		$id="";
		$comunication = new Comunication();
		$params = array($total,$idfra);
		$PARAMS_INSERT = array(Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::MOD_FRA,$params,$PARAMS_INSERT,$id);
	
		return $id;
		}
		

	public function cerrar_factura ($nombre,$nit,$numfra,$impuesto,$total,$pendiente,$fecha,$idfra){
		$comunication = new Comunication();

		$params = array($nombre,$nit,$numfra,$impuesto,$total,$pendiente,$fecha,$idfra);
		$PARAMS_INSERT = array(Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TINT,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TDATE,Comunication::$TINT);
		$result = $comunication->update(self::CERRAR_FRA,$params,$PARAMS_INSERT);
		
		return $result;
		}


		public function insert_linea ($Id_fra,$lineas,$cantidad,$descripcion,$valor,$recargo,$descuento, $Id_checkin){
		$comunication = new Comunication();
		$params = array($Id_fra,$lineas,$cantidad,$descripcion,$valor,$recargo,$descuento, $Id_checkin);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TINT,Comunication::$TSTRING,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::ALT_LINEA,$params,$PARAMS_INSERT);
	
		return $result;
		}	
		
		
		public function add_linea_fra ($Id_fra){
		$comunication = new Comunication();
		$params = array($Id_fra);
		$PARAMS_INSERT = array(Comunication::$TINT);
		$result = $comunication->update(self::ADD_LINEA_FRA,$params,$PARAMS_INSERT);
	
		return $result;
		}	

		public function actualizar_pagado ($pagado,$Id_fra){
		$comunication = new Comunication();
		$params = array($pagado,$Id_fra);
		$PARAMS_INSERT = array(Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::PAGADO_FRA,$params,$PARAMS_INSERT);
	
		return $result;
		}	


		public function get_lineas ($idfra){
		$comunication = new Comunication();
		$PARAMS = array($idfra);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_LINEAS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}
		
		public function get_linea($idfra, $idlinea){
		$comunication = new Comunication();
		$PARAMS = array($idfra, $idlinea);
		$PARAMS_TYPES = array (Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->query(self::GET_LINEA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
		}
		
		public function modif_linea ($cantidad,$descripcion,$valor,$recargo,$descuento,$idcheckin, $idfra,$idlinea){
		$comunication = new Comunication();
		$params = array($cantidad,$descripcion,$valor,$recargo,$descuento,$idcheckin,$idfra,$idlinea);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TFLOAT,Comunication::$TINT,Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::MODIF_LINEA,$params,$PARAMS_INSERT);
	
		return $result;
		}	


		public function elim_linea($idfra,$idlinea){
		$comunication = new Comunication();
		$params = array($idfra,$idlinea);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT,);
		$result = $comunication->update(self::ELIM_LINEA,$params,$PARAMS_INSERT);
	
		return $result;
		}	
		


}
	
?>