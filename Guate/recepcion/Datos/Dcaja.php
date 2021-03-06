<?php

require_once ('ComunicationRecep.php');

class Dcaja{
	
	const IS_CAJA_OPEN = 'SELECT estado from caja WHERE estado=true';
	const OPEN_CAJA = 'INSERT INTO caja VALUES(?,1,NOW(),null,?,null,0,?)';
	const GET_FONDO_CAJA = 'SELECT fondoInicial from caja where estado=1';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const GET_ID_CAJA_REST = 'select id_caja from restbar_bd.caja where estado=1';
	const CLOSE_CAJA = 'UPDATE caja SET estado=0,fechaHoraCierre=NOW(), EfectivoCerrar=? where caja.estado=1';
	const INS_MOV = 'INSERT INTO movimiento VALUES(0,NOW(),?,?,?,?,?,?)';
	const INS_MOV_REST = 'INSERT INTO restbar_bd.movimiento VALUES(0,NOW(),?,?,?,?,?,?)';
	const NAME_USER = 'select nombre from trabajador where idTrabajador=?';
	const NAME_ENCARGADO = 'select nombre from guate_bd.usuario where Id_usuario=?';
	const INS_MOVCREDITO = 'INSERT INTO movimientocredito VALUES(?,?,?,?,?)';
	//const TOTAL_MONEY_MOV = 'SELECT t1.tipo,sum(t1.dinero) as suma from movimiento t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 group by tipo';
	const TOTAL_TICKETS = 'SELECT sum(t1.total) as totalTickets from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 and t1.estado!="anulado" ';
	const COBRAR_TICKET = 'UPDATE comandacredito SET cobrado=1 where idComanda=?';
	const COBRAR_MOVIMENTO = 'UPDATE movimientocredito SET cobrado=1 where id_movimiento=?';
	const ESTADO_COMANDA = 'select estado from comanda where idComanda=?';
    const ESTADO_TIQUET = 'select estado from comanda where idComanda=?';
	const DELETE_CREDITO_TIQUET = 'delete from comandacredito where idComanda=?';
	const MOV_CREDITOS_DELETE = 'delete from movimientocredito where id_usuario=?';
	const WHAT_COM_CREDITOS_DELETE = 'select t1.idComanda , t2.procedencia from comanda t1,comandacredito t2 where t1.idComanda=t2.idComanda and t1.tipoCliente=5 and t1.id_cliente=? and t2.procedencia="HR" union select t1.idComanda , t2.procedencia from restbar_bd.comanda t1,comandacredito t2 where t1.tipoCliente=5 and t1.idComanda=t2.idComanda and t1.id_cliente=?  and t2.procedencia="RB"';
	const COM_CREDITOS_DELETE = 'delete from comandacredito where idComanda=? and procedencia=?';
	const DEL_USUARIO_CUENTA ='UPDATE trabajador SET deleted=1 where idTrabajador=?';
	
	const ANULAR_TICKET = 'UPDATE comanda SET estado="anulado" where idComanda=?';
	const LINEACOMANDA= 'select idPlatillo,cantidad from lineacomanda where idComanda=?';
	const GET_BEBIDA = 'select stockbar,stockrestaurante,unidadventa from stockbebidas where idBebida=?';
	const INFORM_STOCK_RECEPCION = 'UPDATE stockbebidas SET stockrestaurante=? where idBebida=?';
	
	const ESTADO_MOVIMIENTO = 'select tipo from movimiento where id_movimiento=?';
	const DELETE_CREDITO = 'delete from movimientocredito where id_movimiento=?';
	const ANULAR_MOVIMIENTO = 'UPDATE movimiento SET tipo="anulado" where id_movimiento=?';
	const FACTURAR_TICKET = 'UPDATE comanda SET estado="facturado" where idComanda=?';
	const FIND_CAJA = 'select id_caja,fechaHoraApertura,fechaHoraCierre,fondoInicial,EfectivoCerrar from caja where fechaHoraApertura <= ? and  fechaHoraApertura >= ?';
	const FIND_ONE_CAJA = 'select id_caja,fechaHoraApertura,fechaHoraCierre,fondoInicial,EfectivoCerrar from caja where id_caja=?';
	const LOAD_TICKETS = 'select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,concat(t3.nombre," ",t3.apellido1," ", t3.apellido2) as nombre,null as free from comanda t1,caja t2, guate_bd.cliente t3,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente=t3.Id_cliente and t4.idTipoCliente=t1.tipoCliente and t1.tipoCliente=3 union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,t3.nombre,null as free from comanda t1,caja t2,trabajador t3,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente=t3.idTrabajador and t4.idTipoCliente=t1.tipoCliente and (t1.tipoCliente=2 or t1.tipoCliente=5) union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m")," ",TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType,null,t1.free from comanda t1,caja t2,tipocliente t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.id_cliente is null and  t1.tipoCliente=t4.idTipoCliente order by fechaHora desc';
	const LOAD_TICKET = 'select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m/%y")," ", TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType, concat(t3.nombre," ",t3.apellido1," ", t3.apellido2) as nombre,null as free from restbar_bd.comanda t1, guate_bd.cliente t3,tipocliente t4 where t1.numComanda=? and t1.id_cliente=t3.Id_cliente and t4.idTipoCliente=t1.tipoCliente and t1.tipoCliente=3 union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m/%y")," ", TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType, t3.nombre,null as free from restbar_bd.comanda t1,trabajador t3,tipocliente t4 where t1.numComanda=? and t1.id_cliente=t3.idTrabajador and t4.idTipoCliente=t1.tipoCliente and (t1.tipoCliente=2 or t1.tipoCliente=5) union select t1.idComanda,t1.numComanda,t1.estado,concat(DATE_FORMAT(t1.fechaHora,"%d/%m/%y")," ", TIME_FORMAT(t1.fechaHora,"%H:%i:%s")) as fechaHora,t1.total,t1.efectivo,t4.clientType, null,t1.free from restbar_bd.comanda t1, tipocliente t4 where t1.numComanda=? and t1.id_cliente is null and  t1.tipoCliente=t4.idTipoCliente order by fechaHora desc';	
	const LOAD_MOV = 'SELECT t1.id_movimiento,t1.fechaHora,t1.tipo,t1.dinero,t1.descripcion,t3.nombre as categoria,t4.nombre as encargado from movimiento t1,caja t2,categoria t3,guate_bd.usuario t4 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=t1.id_categoria and t1.idencargado=t4.Id_usuario  order by t1.fechaHora desc';
	const TOTAL_TICKETS_OLD = 'SELECT sum(t1.total) as totalTickets from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.id_caja=? and t1.estado!="anulado" and t1.numComanda is not null';
	const TOTAL_MONEY_MOV_OLD = 'SELECT t1.tipo,sum(t1.dinero) as suma from movimiento t1,caja t2 where t1.id_caja=t2.id_caja and t2.id_caja=? group by tipo union SELECT "ventaR" as tipo,sum(t1.total) as suma from comanda t1,caja t2,categoria t3 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=8 and t1.estado!="anulado" and t1.numComanda is null group by t3.nombre';
	const GET_FONDO_CAJA_OLD = 'SELECT fondoInicial from caja where id_caja=?';
	const ARE_TIKETS_COBRADOS = 'SELECT t1.idComanda from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.estado=1 and (t1.estado="cerrado" or t1.estado="abierta")';
	
	//Selects que tienen que hacer con las cuentas de credito
	const GET_USUARIOS = 'select idTrabajador,nombre from trabajador where deleted=0';
	const BUSCADOR_USUARIOS = 'select idTrabajador, nombre from trabajador where nombre LIKE ? and deleted=0';
	const USUARIOS_COMANDAS = 'select t1.idComanda,t1.numComanda,t2.procedencia,t1.fechaHora,t2.total,t3.nombre from comanda t1,comandacredito t2,trabajador t3 where t1.tipoCliente=5 and procedencia="HR" and t1.idComanda=t2.idComanda and t1.id_cliente=t3.idTrabajador and t1.id_cliente=? union select t1.idComanda,t1.numComanda,t2.procedencia,t1.fechaHora,t2.total,t3.nombre from restbar_bd.comanda t1,comandacredito t2,trabajador t3 where tipoCliente=5 and procedencia="RB" and t1.idComanda=t2.idComanda and t1.id_cliente=t3.idTrabajador and t1.id_cliente=? order by fechaHora desc';
	const USUARIOS_COMANDAS_FECHAS = 'select t1.idComanda,t1.numComanda,t2.procedencia,t1.fechaHora,t2.total,t3.nombre from comanda t1,comandacredito t2,trabajador t3 where t1.tipoCliente=5 and procedencia="HR" and t1.idComanda=t2.idComanda and t1.id_cliente=t3.idTrabajador and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? union select t1.idComanda,t1.numComanda,t2.procedencia,t1.fechaHora,t2.total,t3.nombre from restbar_bd.comanda t1,comandacredito t2,trabajador t3 where tipoCliente=5 and procedencia="RB" and t1.idComanda=t2.idComanda and t1.id_cliente=t3.idTrabajador and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? order by fechaHora desc';
	const USUARIOS_COMANDAS_FECHAS_IMPR = 'select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from comanda t1,lineacomanda t2, platillo t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idPlatillo = t2.idPlatillo and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? and procedencia="HR" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from comanda t1,lineacomanda t2, bebida t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idBebida = t2.idPlatillo and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? and procedencia="HR" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from restbar_bd.comanda t1,restbar_bd.lineacomanda t2, platillo t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idPlatillo = t2.idPlatillo and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? and procedencia="RB" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from restbar_bd.comanda t1,restbar_bd.lineacomanda t2, bebida t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idBebida = t2.idPlatillo and t1.id_cliente=? and fechaHora <= ? and fechaHora >= ? and procedencia="RB" order by fechaHora desc';
	const USUARIOS_COMANDAS_FECHAS_IMPR_ALL = 'select t1.numComanda,t1.fechaHora, t2.idLineaComanda, t4.total, t3.nombre,t2.cantidad from comanda t1,lineacomanda t2, platillo t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idPlatillo = t2.idPlatillo and t1.id_cliente=? and procedencia="HR" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from comanda t1,lineacomanda t2, bebida t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idBebida = t2.idPlatillo and t1.id_cliente=? and procedencia="HR" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from restbar_bd.comanda t1,restbar_bd.lineacomanda t2, platillo t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idPlatillo = t2.idPlatillo and t1.id_cliente=?  and procedencia="RB" union select t1.numComanda,t1.fechaHora, t2.idLineaComanda,  t4.total, t3.nombre,t2.cantidad from restbar_bd.comanda t1,restbar_bd.lineacomanda t2, bebida t3, comandacredito t4 where t1.idComanda = t4.idComanda and t2.idComanda = t1.idComanda and t3.idBebida = t2.idPlatillo and t1.id_cliente=? and procedencia="RB" order by fechaHora desc';
	const USUARIOS_MOV = 'select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,movimiento t2,guate_bd.usuario t3,categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="HR" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria union select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,restbar_bd.movimiento t2,guate_bd.usuario t3,restbar_bd.categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="RB" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria order by fechaHora desc';
	const USUARIOS_MOV_FECHAS = 'select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,movimiento t2,guate_bd.usuario t3,categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="HR" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria and fechaHora <= ? and fechaHora >= ? union select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,restbar_bd.movimiento t2,guate_bd.usuario t3,restbar_bd.categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="RB" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria and fechaHora <= ? and fechaHora >= ? order by fechaHora desc';
	const TOTAL_CUENTA = 'select sum(total) as total from(select sum(t2.total) as total from comanda t1,comandacredito t2 where t1.idComanda=t2.idComanda and t1.tipoCliente=5 and t1.id_cliente=? and t2.procedencia="HR" union select sum(t2.total) as total from restbar_bd.comanda t1,comandacredito t2 where t1.tipoCliente=5 and t1.idComanda=t2.idComanda and t1.id_cliente=?  and t2.procedencia="RB" union select sum(t1.dinero) as total from movimientocredito t1,movimiento t2 where t1.id_movimiento=t2.id_movimiento and t1.id_usuario=? and t1.procedencia="HR" union select sum(t1.dinero) as total from movimientocredito t1,restbar_bd.movimiento t2 where t1.id_movimiento=t2.id_movimiento and t1.id_usuario=? and t1.procedencia="RB") as total';
	//const USUARIOS_COMANDAS = 'select t1.idComanda,t1.numComanda,t5.procedencia,t5.cobrado,t1.fechaHora,t5.total,t4.clientType,t3.nombre from comanda t1,caja t2,trabajador t3,tipocliente t4,comandacredito t5 where t1.id_caja=t2.id_caja and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t1.tipoCliente=t4.idTipoCliente and t5.idComanda=t1.idComanda and t3.idTrabajador=? and t5.procedencia="HR" union select t1.idComanda,t1.numComanda,t5.procedencia,t5.cobrado,t1.fechaHora,t5.total,t4.clientType,t3.nombre from restbar_bd.comanda t1,restbar_bd.caja t2,trabajador t3,tipocliente t4,comandacredito t5 where t1.id_caja=t2.id_caja and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t1.tipoCliente=t4.idTipoCliente and t5.idComanda=t1.idComanda and t3.idTrabajador=? and t5.procedencia="RB" order by fechaHora desc';
	//const USUARIOS_MOV = 'select t1.id_movimiento,t1.fechaHora,t2.cobrado as tipo,t2.dinero,t1.descripcion,t4.nombre as categoria,t5.nombre as encargado from movimiento t1,movimientocredito t2,trabajador t3,categoria t4,guate_bd.usuario t5 where t1.id_movimiento=t2.id_movimiento and t3.idTrabajador=t2.id_usuario and t4.id_categoria=t1.id_categoria and t5.Id_usuario=t1.idencargado and t2.id_usuario=? and t2.procedencia="HR" union select t1.id_movimiento,t1.fechaHora,t2.cobrado as tipo,t2.dinero,t1.descripcion,t4.nombre as categoria,t5.nombre as encargado from restbar_bd.movimiento t1,movimientocredito t2,trabajador t3,restbar_bd.categoria t4,guate_bd.usuario t5 where t1.id_movimiento=t2.id_movimiento and t3.idTrabajador=t2.id_usuario and t4.id_categoria=t1.id_categoria and t5.Id_usuario=t1.idencargado and t2.id_usuario=? and t2.procedencia="RB" order by fechaHora desc';
	//new const USUARIOS_MOV = 'select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,movimiento t2,guate_bd.usuario t3,categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="HR" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria union select t1.id_movimiento,t2.fechaHora,t1.cobrado as tipo,t1.dinero,t2.descripcion,t4.nombre as categoria,t3.nombre as encargado from movimientocredito t1,restbar_bd.movimiento t2,guate_bd.usuario t3,restbar_bd.categoria t4 where t1.id_movimiento=t2.id_movimiento and t1.procedencia="RB" and t1.id_usuario=? and t3.Id_usuario=t2.idencargado and t2.id_categoria=t4.id_categoria order by fechaHora desc';
	//const TOTAL_CUENTA = 'select sum(total) as total from( select sum(t4.total) as total from comanda t1,caja t2,trabajador t3,comandacredito t4 where t1.id_caja=t2.id_caja  and t1.idComanda=t4.idComanda and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t3.idTrabajador=? and t4.cobrado=0 group by t3.idTrabajador union select sum(t4.total) as total from restbar_bd.comanda t1,restbar_bd.caja t2,trabajador t3,comandacredito t4 where t1.id_caja=t2.id_caja  and t1.idComanda=t4.idComanda and t1.id_cliente=t3.idTrabajador and t1.tipoCliente=5 and t3.idTrabajador=? and t4.cobrado=0 group by t3.idTrabajador union select sum(t1.dinero) as total from movimientocredito t1,movimiento t2 where t1.id_movimiento=t2.id_movimiento and t1.id_usuario=?)as total';
	const SET_USUARIO = 'INSERT INTO trabajador VALUES(0,?,?,0)';
	const IS_USUARIO = 'SELECT idTrabajador from trabajador WHERE nombre = ?';
	const GET_PEDIDO = 'select t1.idLineaComanda,t2.idPlatillo,t1.cantidad,t2.nombre,t1.precio from lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo';
	const GET_PEDIDO_RESTBAR= 'select t1.idLineaComanda,t2.idPlatillo,t1.cantidad,t2.nombre,t1.precio from restbar_bd.lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo';
	const GET_PEDIDO_BAR = 'select t1.idLineaComanda,t2.numBebida,t1.cantidad,t2.nombre,t1.precio from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo ';
	const GET_PEDIDO_BAR_RESTBAR = 'select t1.idLineaComanda,t2.numBebida,t1.cantidad,t2.nombre,t1.precio from restbar_bd.lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo ';
	const GET_MOV_CATEGORIES = 'select * from categoria where showcaja=1';
	const GET_MOV_CATEGORIES_REST = 'select * from restbar_bd.categoria where showcaja=1';
	const GET_PRECIOS = 'select precioLimitado,precioNormal from bar_bd.bebida where idBebida=?';
	const TOTAL_COMANDA_CREDITO = 'select total from comandacredito where idComanda=?';
	const TOTAL_MOV_CREDITO ='select dinero from movimientocredito where id_movimiento=?';
	
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
	public function open_caja($fondo,$turno){
		$comunication = new ComunicationRecep();
		$params = array(0,$fondo,$turno);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING);
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
	public function insert_movimiento_rest($tipo,$dinero,$descripcion,$categoria,$idencargado){
		$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA_REST,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}		
        $params = array($a,$tipo,$dinero,$descripcion,$categoria,$idencargado);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT);
		$result = $comunication->update(self::INS_MOV_REST,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function insert_mov_credito($idMov,$money,$iduser,$cobrado,$procedencia){
	$comunication = new ComunicationRecep();
	$params = array($idMov,$money,$iduser,$cobrado,$procedencia);
	$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING);
	$idcaja = $comunication->query(self::INS_MOVCREDITO,$params,$PARAMS_TYPES);
			
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
	
	public function nameEncargado($iduser){
	    $comunication = new ComunicationRecep();
		$params = array($iduser);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$name = $comunication->query(self::NAME_ENCARGADO,$params,$PARAMS_TYPES);
		if ($name->getRecordCount()>0){
			while($name->next()){
				$resultc=$name->getRow();
				$a=$resultc["nombre"];
				}}
		return $a;	
	}
	
	

			
	//public function total_money_mov (){
	//	$comunication = new ComunicationRecep();
	//	$PARAMS = array();
	//	$PARAMS_TYPES = array ();
	//	$result = $comunication->query(self::TOTAL_MONEY_MOV,$PARAMS,$PARAMS_TYPES);
		
	//	return $result;
	//}
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
	public function	cobrar_movimiento_credito($idmov){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idmov);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$estado = $comunication->query(self::TOTAL_MOV_CREDITO,$PARAMS,$PARAMS_TYPES);
		if ($estado->getRecordCount()>0){
			while($estado->next()){
				$resulte=$estado->getRow();
				$a=$resulte["dinero"];
				}}		
		$PARAMS = array($idmov);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->update(self::COBRAR_MOVIMENTO,$PARAMS,$PARAMS_TYPES);
		return $a;
		
	}
	public function anular_ticket ($idComanda,$numComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::ESTADO_TIQUET,$PARAMS,$PARAMS_TYPES);
		if ($result->getRecordCount()>0){
			while($result->next()){
				$resulte=$result->getRow();
				$a=$resulte["estado"];
				}}		
		if ($a=="credito"){
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$comunication->update(self::DELETE_CREDITO_TIQUET,$PARAMS,$PARAMS_TYPES);	
		}
		//esta funcion se llama para a�adir al control de stock los productos que su venta fue anulada
		if($numComanda=="null") {$this->anular_recuperar_stock($idComanda);}
		
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$rs = $comunication->update(self::ANULAR_TICKET,$PARAMS,$PARAMS_TYPES);
		
		return $rs;
	
	}
	
	public function anular_recuperar_stock($idComanda){
	$comunication = new ComunicationRecep();	
    $PARAMS = array($idComanda);
	$PARAMS_TYPES = array (ComunicationRecep::$TINT);
	$rs = $comunication->query(self::LINEACOMANDA,$PARAMS,$PARAMS_TYPES);
	if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$idPlatillo=$result["idPlatillo"];
				$cantidad=$result["cantidad"];
				$this->add_venta_anulada($idPlatillo,$cantidad);
				}}		
	}
	
	public function add_venta_anulada($idbebida,$cantidad){
 	$comunication = new ComunicationRecep();
	$PARAMS = array($idbebida);
	$PARAMS_TYPES = array (ComunicationRecep::$TINT);
	$rs = $comunication->query(self::GET_BEBIDA,$PARAMS,$PARAMS_TYPES);
    if ($rs->getRecordCount()>0){
	       while($rs->next()){
              $result=$rs->getRow();
	          $stockrestaurante=$result["stockrestaurante"];
		   }														
       }
    $stock=$stockrestaurante+$cantidad;
    $PARAMS = array($stock,$idbebida);
	$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT);
	$rs = $comunication->query(self::INFORM_STOCK_RECEPCION,$PARAMS,$PARAMS_TYPES);
 }
	
	public function anular_movimiento ($idMovimiento){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idMovimiento);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::ESTADO_MOVIMIENTO,$PARAMS,$PARAMS_TYPES);
		if ($result->getRecordCount()>0){
			while($result->next()){
				$resulte=$result->getRow();
				$a=$resulte["tipo"];
				}}		
		
		$PARAMS = array($idMovimiento);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$comunication->update(self::DELETE_CREDITO,$PARAMS,$PARAMS_TYPES);	
	
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
	public function load_ticket ($name){
		$comunication = new ComunicationRecep();
		$PARAMS = array($name,$name,$name);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::LOAD_TICKET,$PARAMS,$PARAMS_TYPES);
		
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
		$PARAMS = array($idcaja,$idcaja);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT);
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
	
	public function buscador_usuarios($mask){
		$comunication = new ComunicationRecep();
		$PARAMS = array($mask."%");
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::BUSCADOR_USUARIOS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function get_usuarios_comandas ($idusuario){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$idusuario);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::USUARIOS_COMANDAS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	//and fechaHora <= '2009-10-01' and fechaHora >='2009-09-01'
	public function get_usuarios_comandas_fechas ($idusuario, $fechaStart, $fechaStop){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$fechaStop, $fechaStart,$idusuario,$fechaStop, $fechaStart);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE,ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE);
		$result = $comunication->query(self::USUARIOS_COMANDAS_FECHAS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_usuarios_comandas_impr_fechas_all ($idusuario){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$idusuario,$idusuario,$idusuario);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::USUARIOS_COMANDAS_FECHAS_IMPR_ALL,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	//and fechaHora <= '2009-10-01' and fechaHora >='2009-09-01'
	public function get_usuarios_comandas_impr_fechas ($idusuario, $fechaStart, $fechaStop){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario, $fechaStop, $fechaStart, $idusuario, $fechaStop, $fechaStart, $idusuario, $fechaStop, $fechaStart,$idusuario, $fechaStop, $fechaStart);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE,ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE,ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE,ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE);
		$result = $comunication->query(self::USUARIOS_COMANDAS_FECHAS_IMPR,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	
	public function get_usuarios_movimientos ($idusuario){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$idusuario);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::USUARIOS_MOV,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function get_usuarios_movimientos_fechas ($idusuario, $fechaStart, $fechaStop){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$fechaStop, $fechaStart,$idusuario,$fechaStop, $fechaStart);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE,ComunicationRecep::$TINT,ComunicationRecep::$TDATE,ComunicationRecep::$TDATE);
		$result = $comunication->query(self::USUARIOS_MOV_FECHAS,$PARAMS,$PARAMS_TYPES);
		return $result;
	}

	public function set_usuario($nombreEmpleado,$cliente){
		$result = false;
		$comunication = new ComunicationRecep();
		//Comprobar que no existe un usuario con el mismo nombre
		$PARAMS = array($nombreEmpleado);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$rs = $comunication->query(self::IS_USUARIO,$PARAMS,$PARAMS_TYPES);
		if ($rs->getRecordCount()==0){
		 //Insertar el nuevo usuario
		 $PARAMS = array($nombreEmpleado,$cliente);
		 $PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TBOOLEAN);
		 $rs = $comunication->query(self::SET_USUARIO,$PARAMS,$PARAMS_TYPES);
		 $result=true;
		}
		return $result;
	}
	
    public function exist_debt($cuentadelete){
        $comunication = new ComunicationRecep();
		$PARAMS = array($cuentadelete,$cuentadelete,$cuentadelete,$cuentadelete);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$rs = $comunication->query(self::TOTAL_CUENTA,$PARAMS,$PARAMS_TYPES);	
		
		 if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["total"];
				}}
			if ($a == NULL || $a == 0 ) return 0;
			else return 1;
}

	public function	cuenta_delete($cuentadelete){
	    $comunication = new ComunicationRecep();
		$PARAMS = array($cuentadelete);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::MOV_CREDITOS_DELETE,$PARAMS,$PARAMS_TYPES);
		
		$PARAMS = array($cuentadelete,$cuentadelete);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$rs = $comunication->query(self::WHAT_COM_CREDITOS_DELETE,$PARAMS,$PARAMS_TYPES);
		
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["idComanda"];
				$b=$result["procedencia"];
				
				$PARAMS = array($a,$b);
				$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TSTRING);
				$result = $comunication->query(self::COM_CREDITOS_DELETE,$PARAMS,$PARAMS_TYPES);
				}}
				
				$PARAMS = array($cuentadelete);
				$PARAMS_TYPES = array (ComunicationRecep::$TINT);
				$result = $comunication->query(self::DEL_USUARIO_CUENTA,$PARAMS,$PARAMS_TYPES);
	}
	
	public function total_cuenta($idusuario){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idusuario,$idusuario,$idusuario,$idusuario);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
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
	public function get_pedido_cuenta($idComanda){
		$idcom = substr($idComanda,2);
	    $procedencia = substr($idComanda,0,2);
		$comunication = new ComunicationRecep();
		$PARAMS = array($idcom);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		if($procedencia=="HR") $result = $comunication->query(self::GET_PEDIDO,$PARAMS,$PARAMS_TYPES);
		else if($procedencia=="RB") $result = $comunication->query(self::GET_PEDIDO_RESTBAR,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function get_pedido_bar($idComanda){
	    $comunication = new ComunicationRecep();
		$PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_PEDIDO_BAR,$PARAMS,$PARAMS_TYPES);
		return $result;	
	 }
	 public function get_pedido_bar_cuenta($idComanda){
	    $idcom = substr($idComanda,2);
	    $procedencia = substr($idComanda,0,2);
	    $comunication = new ComunicationRecep();
		$PARAMS = array($idcom);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		if($procedencia=="HR") $result = $comunication->query(self::GET_PEDIDO_BAR,$PARAMS,$PARAMS_TYPES);
		else if($procedencia=="RB") $result = $comunication->query(self::GET_PEDIDO_BAR_RESTBAR,$PARAMS,$PARAMS_TYPES);
		return $result;	
	 }
	 
	public function get_mov_categories(){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_MOV_CATEGORIES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	
	public function get_mov_categories_rest(){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_MOV_CATEGORIES_REST,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
}
?>