<?php

require_once ('ComunicationRecep.php');

class Dcaja{
	
	const IS_CAJA_OPEN = 'SELECT estado from caja WHERE estado=true';
	const OPEN_CAJA = 'INSERT INTO caja VALUES(?,1,NOW(),null,?,null,0)';
	const GET_FONDO_CAJA = 'SELECT fondoInicial from caja where estado=1';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const CLOSE_CAJA = 'UPDATE caja SET estado=0,fechaHoraCierre=NOW(), EfectivoCerrar=? where caja.estado=1';
	const INS_MOV = 'INSERT INTO movimiento VALUES(0,NOW(),?,?,?,?,?,?)';
	const NAME_USER = 'select nombre from trabajador where idTrabajador=?';
	const INS_MOV_CREDITO = 'INSERT INTO movimientocredito VALUES(?,?,?,0)';
	const TOTAL_MONEY_MOV = 'SELECT t1.tipo,sum(t1.dinero) as suma from movimiento t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 group by tipo';
	const TOTAL_TICKETS = 'SELECT sum(t1.total) as totalTickets from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 and t1.estado!="anulado" ';
	const COBRAR_TICKET = 'UPDATE comanda SET estado="cobrado" where idComanda=?';
	const ESTADO_COMANDA = 'select estado from comanda where idComanda=?';
	const ANULAR_TICKET = 'UPDATE comanda SET estado="anulado" where idComanda=?';
	const ANULAR_MOVIMIENTO = 'UPDATE movimiento SET tipo="anulado" where id_movimiento=?';
	const FACTURAR_TICKET = 'UPDATE comanda SET estado="facturado" where idComanda=?';
	const FIND_CAJA = 'select id_caja,fechaHoraApertura,fechaHoraCierre,fondoInicial,EfectivoCerrar from caja where fechaHoraApertura <= ? and  fechaHoraApertura >= ?';
	const FIND_ONE_CAJA = 'select id_caja,fechaHoraApertura,fechaHoraCierre,fondoInicial,EfectivoCerrar from caja where id_caja=?';
	const LOAD_TICKETS = 'select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,concat(t3.nombre," ",t3.apellido1," ", t3.apellido2) as nombre,null as free from comanda t1,caja t2, guate_bd.cliente t3,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente=t3.Id_cliente and t4.idTipoCliente=t1.tipoCliente and t1.tipoCliente=3 union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,t3.nombre,null as free from comanda t1,caja t2,trabajador t3,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente=t3.idTrabajador and t4.idTipoCliente=t1.tipoCliente and (t1.tipoCliente=2 or t1.tipoCliente=5) union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,null,t1.free from comanda t1,caja t2,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente is null and  t1.tipoCliente=t4.idTipoCliente order by fechaHora desc';
	const LOAD_MOV = 'SELECT t1.id_movimiento,t1.fechaHora,t1.tipo,t1.dinero,t1.descripcion,t3.nombre as categoria,t4.nombre as encargado from movimiento t1,caja t2,categoria t3,guate_bd.usuario t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=t1.id_categoria and t1.idencargado=t4.Id_usuario  order by t1.fechaHora desc';
	const TOTAL_TICKETS_OLD = 'SELECT sum(t1.total) as totalTickets from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.estado!="anulado" and t1.numComanda is not null';
	const TOTAL_MONEY_MOV_OLD = 'SELECT t1.tipo,sum(t1.dinero) as suma from movimiento t1,caja t2 where t1.id_caja=t2.id_caja and t2.id_caja=? group by tipo';
	const GET_FONDO_CAJA_OLD = 'SELECT fondoInicial from caja where id_caja=?';
	const ARE_TIKETS_COBRADOS = 'SELECT t1.idComanda from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 and (t1.estado="cerrado" or t1.estado="abierta")';
	const GET_USUARIOS = 'select idTrabajador,nombre from trabajador';
	const USUARIOS_COMANDAS = 'select t1.idComanda,t1.numComanda,t1.estado,t1.fechaHora,t5.total,t4.clientType,t3.nombre from comanda t1,caja t2,trabajador t3,tipocliente t4,comandacredito t5 where t1.id_caja=t2.id_caja and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t1.tipoCliente=t4.idTipoCliente and t5.idComanda=t1.idComanda and t3.idTrabajador=? and month(t2.fechaHoraApertura)=? and year(t2.fechaHoraApertura)=?';
	const USUARIOS_MOV = 'select t1.id_movimiento,t1.fechaHora,t1.tipo,t2.dinero,t1.descripcion,t4.nombre as categoria,t5.nombre as encargado from movimiento t1,movimientocredito t2,trabajador t3,categoria t4,guate_bd.usuario t5 where t1.id_movimiento=t2.id_movimiento and t3.idTrabajador=t2.id_usuario and t4.id_categoria=t1.id_categoria and t5.Id_usuario=t1.idencargado and t2.id_usuario=? and month(t1.fechaHora)=? and year(t1.fechaHora)=?';
	const SET_USUARIO = 'INSERT INTO trabajador VALUES(0,?)';
	//const USUARIOS_COMANDAS = 'select t1.idComanda,t1.idComanda as numComanda,t1.estado,t1.fechaHora,t1.total,t1.efectivo,t4.clientType,t3.nombre from comanda t1,caja t2, guate_bd.usuario t3,tipocliente t4 where t1.id_caja=t2.id_caja and t2.estado=0 and t1.id_cliente=t3.Id_usuario and t1.tipoCliente=2 and t1.tipoCliente=t4.idTipoCliente and t3.Id_usuario=? and month(t2.fechaHoraApertura)=? and year(t2.fechaHoraApertura)=? union select concat("B",t1.idComanda),concat("B",t1.numComanda),t1.estado,t1.fechaHora,t1.total,t1.efectivo,t4.clientType,t3.nombre from bar_bd.comanda t1,bar_bd.caja t2, guate_bd.usuario t3,bar_bd.tipocliente t4 where t1.id_caja=t2.id_caja and t2.estado=0 and t1.id_cliente=t3.Id_usuario and t1.tipoCliente=2 and t1.tipoCliente=t4.idTipoCliente and t3.Id_usuario=? and month(t2.fechaHoraApertura)=? and year(t2.fechaHoraApertura)=?';
	const TOTAL_CUENTA = 'select sum(t4.total) as total from comanda t1,caja t2,trabajador t3,comandacredito t4 where t1.id_caja=t2.id_caja and t2.estado=0  and t1.idComanda=t4.idComanda and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t3.idTrabajador=? and t1.estado="credito" and month(t2.fechaHoraApertura)=? and year(t2.fechaHoraApertura)=? group by t3.idTrabajador';
	const GET_PEDIDO = 'select t1.idPlatillo,t1.cantidad,t2.nombre,t1.precio from lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo';
	const GET_PEDIDO_BAR = 'select t2.numBebida,t1.cantidad,t2.nombre,t1.precio from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo union select t2.idPlatillo,t1.cantidad,t2.nombre,t1.precio from lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo';
	const GET_MOV_CATEGORIES = 'select * from categoria where showcaja=1';
	const GET_PRECIOS = 'select precioLimitado,precioNormal from bar_bd.bebida where idBebida=?';
	const TOTAL_COMANDA_CREDITO = 'select total from comandacredito where idComanda=?';
	
	public function is_caja_open (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::IS_CAJA_OPEN,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function get_id_caja (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_ID_CAJA,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function open_caja($fondo){
		$comunication = new ComunicationRecep();
		$params = array(0,$fondo);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT);
		$result = $comunication->update(self::OPEN_CAJA,$params,$PARAMS_INSERT);
		
		return $result;
	}
	
	public function get_fondo_caja (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_FONDO_CAJA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function close_caja ($efectivoCerrar){
		$comunication = new ComunicationRecep();
		$PARAMS = array($efectivoCerrar);
		$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT);
		$result = $comunication->update(self::CLOSE_CAJA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}
	public function insert_movimiento($tipo,$dinero,$descripcion,$categoria,$idencargado){
		$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}		
        $params = array($a,$tipo,$dinero,$descripcion,$categoria,$idencargado);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT);
		$result = $comunication->update(self::INS_MOV,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function insertmovcredito($dinero,$description,$categoria,$idempleado,$idencargado){
	$comunication = new ComunicationRecep();
	$name=$this->nameUser($idempleado);
	$idMov=$this->insert_movimiento("credito",0,$name.": ".$description,$categoria,$idencargado);	
	
	    $params = array($idMov,$dinero,$idempleado);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT);
		$idcaja = $comunication->query(self::INS_MOV_CREDITO,$params,$PARAMS_TYPES);
		
	}
	
	public function nameUser($iduser){
	    $comunication = new ComunicationRecep();
		$params = array($iduser);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$name = $comunication->query(self::NAME_USER,$params,$PARAMS_TYPES);
		if ($name->getRecordCount()>0){
			while($name->next()){
				$resultc=$name->getRow();
				$a=$resultc["nombre"];
				}}
		return $a;	
	}
	
	public function insert_venta_recepcion($idproducto,$cantity,$checked,$description,$idencargado){
		$comunication = new ComunicationRecep();
		//encontramos el id de la caja abierta
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}
		//buscamos el precio del dicho pedido		
		$params = array($idproducto);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$precios = $comunication->query(self::GET_PRECIOS,$params,$PARAMS_TYPES);
		if ($precios->getRecordCount()>0){
			while($precios->next()){
				$resultp=$precios->getRow();
				$precioL=$resultp["precioLimitado"];
				$precioN=$resultp["precioNormal"];
				}}
		if($checked==0) $preciofinal=$precioN*$cantity;
		else	$preciofinal=$precioL*$cantity;

		$params = array($a,"entrada",$preciofinal,$cantity." de ".$description,8,$idencargado);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT);
		$result = $comunication->update(self::INS_MOV,$params,$PARAMS_INSERT);
		
		return $result;
	}
			
	public function total_money_mov (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::TOTAL_MONEY_MOV,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function total_tickets (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::TOTAL_TICKETS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function total_tickets_old ($idcaja){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOTAL_TICKETS_OLD,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function cobrar_ticket ($idComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$estado = $comunication->query(self::TOTAL_COMANDA_CREDITO,$PARAMS,$PARAMS_TYPES);
		if ($estado->getRecordCount()>0){
			while($estado->next()){
				$resulte=$estado->getRow();
				$a=$resulte["total"];
				}}		
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->update(self::COBRAR_TICKET,$PARAMS,$PARAMS_TYPES);
		return $a;
	}
	public function anular_ticket ($idComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->update(self::ANULAR_TICKET,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}
	public function anular_movimiento ($idMovimiento){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idMovimiento);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->update(self::ANULAR_MOVIMIENTO,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function facturar_ticket($idComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->update(self::FACTURAR_TICKET,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function find_caja($inicio,$fin){		
		$comunication = new ComunicationRecep();
		$params = array($fin.' 23:59:59',$inicio);
		$PARAMS_INSERT = array(ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::FIND_CAJA,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function find_one_caja($idcaja){		
		$comunication = new ComunicationRecep();
		$params = array($idcaja);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT);
		$result = $comunication->query(self::FIND_ONE_CAJA,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function load_tickets ($idcaja){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcaja,$idcaja,$idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::LOAD_TICKETS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function load_movimientos ($idcaja){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::LOAD_MOV,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function total_money_mov_old ($idcaja){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOTAL_MONEY_MOV_OLD,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_fondo_caja_old ($idcaja){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_FONDO_CAJA_OLD,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function are_tiquets_cobrados (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::ARE_TIKETS_COBRADOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_usuarios (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_USUARIOS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}	
	public function get_usuarios_comandas ($idusuario,$month,$year){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$month,$year,$idusuario,$month,$year);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::USUARIOS_COMANDAS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_usuarios_movimientos ($idusuario,$month,$year){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$month,$year);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::USUARIOS_MOV,$PARAMS,$PARAMS_TYPES);
		return $result;
	}

	public function set_usuario($nombreEmpleado){
		$comunication = new ComunicationRecep();
		$PARAMS = array($nombreEmpleado);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::SET_USUARIO,$PARAMS,$PARAMS_TYPES);
	}
	
	public function total_cuenta($idusuario,$month,$year){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$month,$year);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOTAL_CUENTA,$PARAMS,$PARAMS_TYPES);
		return $result;
	}	
	public function get_pedido($idComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::GET_PEDIDO,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function get_pedido_bar($idComanda){
	    $comunication = new ComunicationRecep();
		$PARAMS = array($idComanda,$idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_PEDIDO_BAR,$PARAMS,$PARAMS_TYPES);
		return $result;	
	 }
	public function get_mov_categories(){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_MOV_CATEGORIES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
}
?>