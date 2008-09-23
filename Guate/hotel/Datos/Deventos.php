<?php
require_once ('Comunication.php');

class Deventos {

	/*************************************************************************/
	/***********	 COMANDOS SQL Y TIPO DE PARAMETROS REQUERIDOS		******/
	/*************************************************************************/

	//**********************	CONSULTAS	*******************//
	const GET_BY_DATES = 'SELECT reserva.Id_res as Id_res, reserva.Id_aloj as Id_aloj, 
			 reserva.fec_ini, reserva.fec_fin, fec_res, reserva.Id_cliente,
			 checkin.fec_in, checkin.fec_out,
			 checkin.Id_checkin as Id_checkin FROM reserva
			 LEFT JOIN checkin ON reserva.Id_res=checkin.Id_res
			 WHERE (reserva.fec_ini >= ? and reserva.fec_ini <= ? 
			 OR reserva.fec_fin >= ? and reserva.fec_fin <= ?)
			 AND (checkin.fec_in IS NULL or checkin.fec_out IS NULL or checkin.fec_in<>checkin.fec_out) ORDER BY reserva.Id_aloj, reserva.fec_ini';
	
	const GET_BY_DATES_ID_ALOJ = 'SELECT reserva.Id_res as Id_res, reserva.Id_aloj as Id_aloj, 
			 reserva.fec_ini, reserva.fec_fin, fec_res, reserva.Id_cliente,
			 checkin.fec_in, checkin.fec_out, checkin.Id_checkin as Id_checkin FROM reserva
			 LEFT JOIN checkin ON reserva.Id_res=checkin.Id_res
 			 WHERE (reserva.fec_ini >= ? AND reserva.fec_ini <= ? 
			 OR reserva.fec_fin >= ? AND reserva.fec_fin <= ?)
			 AND Id_aloj = ?
			 AND (checkin.fec_in IS NULL or checkin.fec_out IS NULL or checkin.fec_in<>checkin.fec_out) ORDER BY reserva.Id_aloj, reserva.fec_ini';
 		
	const GET_RES_DATA = 'SELECT * FROM reserva
			LEFT JOIN checkin ON reserva.Id_res=checkin.Id_res
			WHERE reserva.Id_res=?';
	
	const GET_OCUP_DATA = 'SELECT * FROM reserva, checkin
			WHERE reserva.Id_res=checkin.Id_res AND checkin.Id_checkin=?';

	const INSERT_RES = 'INSERT INTO reserva VALUES (?,?,?,?,?,?,?)';

	const DEL_RES = 'DELETE FROM reserva WHERE Id_res = ?';
	
	/*
	 * ? >= fec_ini AND ? <= fec_fin:	fecha inicio dentro de otra reserva
	 * ? >= fec_ini AND ? <= fec_fin:	fecha fin dentro de otra reserva
	 * ? < fec_ini AND ? > fec_fin:		hay otra reserva entre fecha inicio y fin
	 * AND fec_in IS NULL				es una reserva
	 * 
	 * ( (fec_in IS NOT NULL AND fec_out IS NOT NULL AND fec_in<>fec_out) 	checkin y checkout habiendo dormido 1 dia al menos
	 * OR (fec_in IS NOT NULL AND fec_out IS NULL) ))						checkin sin checkout
	 */
	const GET_OCUP_ROOMS='SELECT distinct(Id_aloj) FROM reserva 
			LEFT JOIN checkin ON reserva.Id_res=checkin.Id_res
			WHERE
				((	(? >= fec_ini AND ? <= fec_fin ) OR (? >= fec_ini AND ? <= fec_fin ) OR (? < fec_ini AND ? > fec_fin))
				AND fec_in IS NULL)
			OR
				((	(? >= fec_ini AND ? <= fec_fin ) OR (? >= fec_ini AND ? <= fec_fin ) OR (? < fec_ini AND ? > fec_fin))
				AND ( (fec_in IS NOT NULL AND fec_out IS NOT NULL AND fec_in<>fec_out) OR (fec_in IS NOT NULL AND fec_out IS NULL) ))
			ORDER BY Id_aloj';

	const IS_OCUP_ROOM='SELECT count(*) as ocup FROM reserva 
			LEFT JOIN checkin ON reserva.Id_res=checkin.Id_res
			WHERE Id_aloj = ? AND
			(
				((	(? >= fec_ini AND ? <= fec_fin ) OR (? >= fec_ini AND ? <= fec_fin ) OR (? < fec_ini AND ? > fec_fin))
				AND fec_in IS NULL)
			OR
				((	(? >= fec_ini AND ? <= fec_fin ) OR (? >= fec_ini AND ? <= fec_fin ) OR (? < fec_ini AND ? > fec_fin))
				AND ( (fec_in IS NOT NULL AND fec_out IS NOT NULL AND fec_in<>fec_out) OR (fec_in IS NOT NULL AND fec_out IS NULL) ))	)';
	
	//reserva			
	const INC_FEC_INI = 'UPDATE reserva	SET fec_ini = ADDDATE(fec_ini,?) WHERE Id_res=?';
	const INC_FEC_FIN = 'UPDATE reserva	SET fec_fin = ADDDATE(fec_fin,?) WHERE Id_res=?';
	
	//checkin
	const INC_FEC_IN = 'UPDATE checkin SET fec_in = ADDDATE(fec_in,?) WHERE Id_checkin=?';
	
	const CHG_ID_ALOJ = 'UPDATE reserva	SET Id_aloj = ? WHERE Id_res=?';
	
	const CHG_IMP_PAG = 'UPDATE reserva SET imp_pagado = ? WHERE Id_res=?';
	
	public function inc_fec_ini ($id_res, $dias){
		$comunication = new Comunication();
		$PARAMS = array($dias, $id_res);
		$PARAMS_TYPES = array (Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INC_FEC_INI,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	
	public function inc_fec_fin ($id_res, $dias){
		$comunication = new Comunication();
		$PARAMS = array($dias, $id_res);
		$PARAMS_TYPES = array (Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INC_FEC_FIN,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function inc_fec_in ($id_checkin, $dias){
		$comunication = new Comunication();
		$PARAMS = array($dias, $id_checkin);
		$PARAMS_TYPES = array (Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INC_FEC_IN,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function change_id_aloj ($id_res, $id_aloj){
		$comunication = new Comunication();
		$PARAMS = array($id_aloj, $id_res);
		$PARAMS_TYPES = array (Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::CHG_ID_ALOJ,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
					
	public function get_by_dates ($dini, $dend){
		$comunication = new Comunication();
		$PARAMS = array($dini, $dend, $dini, $dend);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TDATE, Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->query(self::GET_BY_DATES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_by_dates_id_aloj ($dini, $dend, $id_aloj){
		$comunication = new Comunication();
		$PARAMS = array($dini, $dend, $dini, $dend, $id_aloj);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE, Comunication::$TINT);
		$result = $comunication->query(self::GET_BY_DATES_ID_ALOJ,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_res_data ($id_res){
		$comunication = new Comunication();
		$PARAMS = array($id_res);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_RES_DATA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_ocup_data ($id_ocup){
		$comunication = new Comunication();
		$PARAMS = array($id_ocup);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_OCUP_DATA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function insert_res ($id_aloj, $fec_ini, $id_cliente, $fec_fin, $fec_res, $imp_pag){
		$params = array(0, $id_aloj, $fec_ini, $id_cliente, $fec_fin, $fec_res, $imp_pag);
		$id=0;
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TDATE,Comunication::$TINT,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TFLOAT);
		$result = $comunication->update(self::INSERT_RES,$params,$PARAMS_TYPES,$id);
		return $id;
	}
	
	public function del_res ($id_res){
		$params = array($id_res);
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT);
		$result = $comunication->update(self::DEL_RES,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function update_imp_pag ($imp_pag, $idres){
		$comunication = new Comunication();
		$PARAMS = array($imp_pag, $idres);
		$PARAMS_TYPES = array (Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::CHG_IMP_PAG,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_ocup_rooms($fec_ini, $fec_fin){
		$comunication = new Comunication();
		$PARAMS = array($fec_ini, $fec_ini, $fec_fin, $fec_fin,$fec_ini,$fec_fin, $fec_ini, $fec_ini, $fec_fin, $fec_fin,$fec_ini,$fec_fin);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->query(self::GET_OCUP_ROOMS,$PARAMS,$PARAMS_TYPES);	
		return $result;
	}
	
	public function is_ocup_room($fec_ini, $fec_fin, $id_aloj){
		$comunication = new Comunication();
		$PARAMS = array($id_aloj, $fec_ini, $fec_ini, $fec_fin, $fec_fin,$fec_ini,$fec_fin, $fec_ini, $fec_ini, $fec_fin, $fec_fin,$fec_ini,$fec_fin);
		$PARAMS_TYPES = array (Comunication::$TINT,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->query(self::IS_OCUP_ROOM,$PARAMS,$PARAMS_TYPES);	
		return $result;
	}
}

?>