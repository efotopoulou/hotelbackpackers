<?php

require_once ('Comunication.php');

class Dcheckres{
	
	const GET_CHECK_BY_ID = 'SELECT * from checkin, reserva where Id_checkin=? AND checkin.Id_res=reserva.Id_res';
	
	const GET_CHECK_BY_RES= 'SELECT * from checkin where Id_res=?';
	
	const ALT_CHECKRES = 'INSERT INTO checkin VALUES (?,?,?,?,?,?,?)';
	
	const ALT_OCUP = 'INSERT INTO ocupantes VALUES(?,?)';
	
	const GET_OCUPANTES = 'SELECT * FROM ocupantes, checkin 
			WHERE checkin.Id_checkin=ocupantes.Id_checkin AND checkin.Id_checkin=?';
	
	const GET_CHECKPREV = 'SELECT Id_res, Id_aloj, Id_cliente from reserva where fec_ini<=? AND fec_fin>=? and not exists (Select * from checkin where checkin.Id_res=reserva.Id_res)';

	const GET_CHECKSINFRA = 'SELECT * from checkin, reserva WHERE checkin.Id_res=reserva.Id_res AND Id_fra=0';
		
	const GET_CHECKOUTPREV = 'Select * from checkin, reserva where checkin.Id_res=reserva.Id_res AND checkin.fec_out IS NULL AND reserva.fec_fin<=?';
	
	const INS_CHECKOUT = 'UPDATE checkin SET fec_out=?, importe_total=? where checkin.Id_checkin=?';
	
	const INS_ID_FRA = 'UPDATE checkin SET Id_fra=? where checkin.Id_checkin=?';
	
	const MOD_IMP_PAG ='UPDATE checkin SET importe_pagado=? where checkin.Id_checkin=?';
		
	public function insert_checkres ($Id_res,$fec_in,$importe_pagado,$fec_out,$importe_total,$idfra){
		$comunication = new Comunication();
		$id="";
		$params = array(0,$Id_res,$fec_in,$importe_pagado,$fec_out,$importe_total,$idfra);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT,Comunication::$TDATE,Comunication::$TFLOAT,Comunication::$TDATE,Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::ALT_CHECKRES,$params,$PARAMS_INSERT,$id);
		
		return $id;
	}	
	
	public function insert_ocup ($Id_checkin,$Id_cliente){
		$comunication = new Comunication();
		$params = array($Id_checkin,$Id_cliente);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::ALT_OCUP,$params,$PARAMS_INSERT);
		return $result;
	}		
		
	public function get_ocupantes ($id_checkin){
		$comunication = new Comunication();
		$PARAMS = array($id_checkin);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_OCUPANTES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_checkprev ($fecha1){
		$comunication = new Comunication();
		$PARAMS = array($fecha1,$fecha1);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TDATE);
		$result = $comunication->query(self::GET_CHECKPREV,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_checksinfra (){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_CHECKSINFRA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_checkoutprev ($fecha1){
		$comunication = new Comunication();
		$PARAMS = array($fecha1);
		$PARAMS_TYPES = array (Comunication::$TDATE);
		$result = $comunication->query(self::GET_CHECKOUTPREV,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
	public function get_check_by_id ($idcheck){
		$comunication = new Comunication();
		$PARAMS = array($idcheck);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_CHECK_BY_ID,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	
		
	public function get_check_by_res ($idres){
		$comunication = new Comunication();
		$PARAMS = array($idres);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_CHECK_BY_RES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
		public function insert_checkout ($fechafin,$total,$idcheckin){
		$comunication = new Comunication();
		$PARAMS = array($fechafin,$total,$idcheckin);
		$PARAMS_TYPES = array (Comunication::$TDATE,Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::INS_CHECKOUT,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}
	
	//insertar id factura en el checkout correspondiente
		public function insert_idfra ($idfra,$idcheckin){
	
		$comunication = new Comunication();
		$PARAMS = array($idfra,$idcheckin);
		$PARAMS_TYPES = array (Comunication::$TINT,Comunication::$TINT);
		$result = $comunication->update(self::INS_ID_FRA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}

		public function modif_imp_pag($importe,$idcheckin){
	
		$comunication = new Comunication();
		$PARAMS = array($importe,$idcheckin);
		$PARAMS_TYPES = array (Comunication::$TFLOAT,Comunication::$TINT);
		$result = $comunication->update(self::MOD_IMP_PAG,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}
	
}
?>