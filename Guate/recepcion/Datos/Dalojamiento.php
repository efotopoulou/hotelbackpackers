<?php

require_once ('ComunicationRes.php');

class Dalojamiento{
	
	const SELECT_CHECKIN = 'select t1.importe_pagado,t3.nombre,t3.apellido1,t3.apellido2,(DATEDIFF(date(t2.fec_fin),date(t2.fec_ini))+1) as noches,t5.descripcion from guate_bd.checkin t1,guate_bd.reserva t2,guate_bd.cliente t3,guate_bd.alojamiento t4,guate_bd.alojamiento_tipo t5 where t1.Id_res=t2.Id_res and t1.Id_checkin=? and t2.Id_cliente=t3.Id_cliente  and t2.Id_aloj=t4.Id_aloj and t4.Id_tipo=t5.Id_tipo';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const INS_MOV = 'INSERT INTO movimiento VALUES(NOW(),?,?,?,?,?,?)';
	const ERROR = 'INSERT INTO error VALUES(?,2,NOW())';
	
	function insert_checkinmov($idcheck,$idencargado){
	$comunication = new ComunicationRes();
		
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}		
		
		
		$PARAMS = array($idcheck);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$checkin = $comunication->query(self::SELECT_CHECKIN,$PARAMS,$PARAMS_TYPES);
		
		if ($checkin->getRecordCount()>0){
			while($checkin->next()){
				$result=$checkin->getRow();
				$importe_pagado=$result["importe_pagado"];
				$nombre=$result["nombre"];
				$apellido1=$result["apellido1"];
				$apellido2=$result["apellido2"];
				$noches=$result["noches"];
				$descripcion=$result["descripcion"];
				}}		
		
	    if($importe_pagado!=0){
		 $PARAMS = array($a,"entrada",$importe_pagado,$nombre." ".$apellido1." ".$apellido2." noches(".$noches.") ".$descripcion." chekin",2,$idencargado);
	     $PARAMS_TYPES = array (ComunicationRes::$TINT,ComunicationRes::$TSTRING,ComunicationRes::$TFLOAT,ComunicationRes::$TSTRING,ComunicationRes::$TINT,ComunicationRes::$TINT);
		 $checkin = $comunication->query(self::INS_MOV,$PARAMS,$PARAMS_TYPES);		
		}	
	}
	function insert_checkoutmov($idcheck,$valor,$idencargado){
	$comunication = new ComunicationRes();
	if ($valor != 0){	
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
        if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
			}
		}		
		
		
		$PARAMS = array($idcheck);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$checkin = $comunication->query(self::SELECT_CHECKIN,$PARAMS,$PARAMS_TYPES);
		
		if ($checkin->getRecordCount()>0){
			while($checkin->next()){
				$result=$checkin->getRow();
				$nombre=$result["nombre"];
				$apellido1=$result["apellido1"];
				$apellido2=$result["apellido2"];
				$noches=$result["noches"];
				$descripcion=$result["descripcion"];
			}
		}		
		
		$PARAMS = array($valor);
		$PARAMS_TYPES = array (ComunicationRes::$TFLOAT);
		$checkin = $comunication->query(self::ERROR,$PARAMS,$PARAMS_TYPES);		
		
		
		if ($valor<0) $PARAMS = array($a,"salida",abs($valor),$nombre." ".$apellido1." ".$apellido2." noches(".$noches.") ".$descripcion." chekin",2,$idencargado);
		else if($valor!=0) $PARAMS = array($a,"entrada",$valor,$nombre." ".$apellido1." ".$apellido2." noches(".$noches.") ".$descripcion." chekout",2,$idencargado);
		
		
		$PARAMS_TYPES = array (ComunicationRes::$TINT,ComunicationRes::$TSTRING,ComunicationRes::$TFLOAT,ComunicationRes::$TSTRING,ComunicationRes::$TINT,ComunicationRes::$TINT);
		$checkin = $comunication->query(self::INS_MOV,$PARAMS,$PARAMS_TYPES);		
	}		
  }
	
}
?>