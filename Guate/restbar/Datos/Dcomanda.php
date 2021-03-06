<?php
require_once ('ComunicationRestBar.php');

class Dcomanda{
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,total,id_cliente,id_caja
	const SET_PLATILLOS = 'INSERT INTO comanda values(0, ?,?,NOW(),?,?,?,?,?,?,?)';
	const SET_LISTA_PLATILLOS = 'INSERT INTO lineacomanda values (0,?,?,?,?)';
    const ES_COCINA ='select cocina from recepcion_bd.platillo where idPlatillo=?';
    const SET_COCINA ='insert into cocina values (0,?,?,?,NOW(),true,null)';    
	const GET_USUARIO_SUMA_PLATILLO = 'select sum(t2.precioLimitado*t1.cantidad) as suma from lineacomanda t1,recepcion_bd.platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo group by idComanda';
	const SET_COMANDA_USUARIO = 'INSERT INTO recepcion_bd.comandacredito values(?,?,false,?)';	
	const ES_PLATILLO ='select idPlatillo from recepcion_bd.platillo where idPlatillo=?';

	
	const SET_COMANDA = 'INSERT INTO comanda values(?,?,NOW(),?,?,?,?,?,?,?)';
	const SET_COMANDA_VENTA = 'INSERT INTO comanda values(0,null,?,NOW(),?,?,?,?,?,?,null)';
	const GET_USUARIO_SUMA = 'select sum(t2.precioLimitado*t1.cantidad) as suma from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo group by idComanda';
	const GET_CLIENTE_SUMA = 'select sum(t2.precioNormal*t1.cantidad) as suma from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo group by idComanda';
	const EMP_OR_CLIENT = 'select t3.cliente from comanda t1,trabajador t3 where  t1.id_cliente=t3.idTrabajador and t1.idComanda=?';
	const ERASE_LISTA_PLATILLOS = 'DELETE FROM lineacomanda WHERE idComanda=?';
	const UPDATE_EFECTIVO_COMANDA = 'UPDATE comanda SET efectivo=?,estado=\'cobrado\' WHERE numComanda=? and estado=\'abierta\'';
	const GET_LAST_ID_COMANDA =	'SELECT numComanda FROM comanda c, caja ca where ca.id_caja=c.id_caja and ca.estado=1 and numComanda!="" order by fechaHora desc limit 1';
    const EXISTE_COMANDA='select idComanda from comanda where idComanda= ?';
    const RESTORE_COMANDA='SELECT idComanda, efectivo, mesa, tipoCliente, total,id_cliente, free, NULL as clienteName FROM comanda WHERE estado=\'backup\' and (tipoCliente=1 or tipoCliente=4) UNION SELECT c.idComanda, c.efectivo, c.mesa, c.tipoCliente, c.total,c.id_cliente, c.free, CONCAT(cli.nombre, \' \', cli.apellido1, \' \', cli.apellido2) FROM comanda c, guate_bd.cliente cli WHERE estado=\'backup\' and c.tipoCliente=3 and cli.id_cliente = c.id_cliente UNION SELECT c.idComanda, c.efectivo, c.mesa, c.tipoCliente, c.total,c.id_cliente, c.free, u.nombre FROM comanda c, guate_bd.usuario u WHERE estado=\'backup\' and c.tipoCliente=2 and u.Id_usuario = c.id_cliente';
    const GETLINIAS='SELECT lc.idPlatillo,lc.cantidad,lc.precio as precioN, (lc.precio/lc.cantidad) as precioUnidad, p.precioNormal, p.precioLimitado, p.nombre as producto FROM lineacomanda lc, platillo p where lc.idComanda=? and p.idPlatillo=lc.idPlatillo';
    const BORRAR_COMANDA='DELETE FROM comanda WHERE estado=?';
    const BORRAR_LINEA_COMANDA='DELETE FROM lineacomanda WHERE idComanda IN (SELECT idComanda FROM comanda WHERE estado=?)';

	public function set_comanda($comandaID,$efectivo,$numMesa, $tipoCliente, $total, $idcliente,$free){
		$comunication = new ComunicationRestBar();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$idCaja=$resultc["id_caja"];
				}}	
		$estado="cobrado";
		if($tipoCliente==5)	$estado="credito"; 
		$PARAMS = array($comandaID,$estado,$efectivo,$tipoCliente, $total, $idcliente, $idCaja, $free,$numMesa);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TINT,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TINT);
		$result = $comunication->update(self::SET_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function setLineaComanda($comandaID,$platoId,$cantidad,$precio){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($comandaID,$platoId,$cantidad,$precio);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TINT,ComunicationRestBar::$TFLOAT);
		$result = $comunication->update(self::SET_LISTA_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		return $result;
		
	}
	public function esCocina($idPlatillo){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($idPlatillo);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING);
		$result = $comunication->query(self::ES_COCINA,$PARAMS,$PARAMS_TYPES);
		$result->next();
		$result=$result->getRow();
		$aux = $result["cocina"];
		return $aux;
	}
	public function setCocina($comandaID,$platoId, $cantidad){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($comandaID,$platoId, $cantidad);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING, ComunicationRestBar::$TINT,ComunicationRestBar::$TINT);
		$result = $comunication->update(self::SET_COCINA,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
	public function setComandaCreditoComida($idComanda,$procedencia){
	    $comunication = new ComunicationRestBar();
	    $PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		$total = $comunication->query(self::GET_USUARIO_SUMA_PLATILLO,$PARAMS,$PARAMS_TYPES);
		
		if ($total->getRecordCount()>0){
			while($total->next()){
				$resultc=$total->getRow();
				$suma=$resultc["suma"];
				}}	
		$PARAMS = array($idComanda,$suma,$procedencia);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TSTRING);
		$result = $comunication->update(self::SET_COMANDA_USUARIO,$PARAMS,$PARAMS_TYPES);
	}
	public function esPlatillo($platoId){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($platoId);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		$result = $comunication->query(self::ES_PLATILLO,$PARAMS,$PARAMS_TYPES);
		return $result;
	}



   
    public function borrarComanda($estado){
	 	$comunication = new ComunicationRestBar();
		$params = array($estado);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING);
		$comanda = $comunication->query(self::BORRAR_LINEA_COMANDA,$params,$PARAMS_TYPES);
		$comanda = $comunication->query(self::BORRAR_COMANDA,$params,$PARAMS_TYPES);
		return $comanda;
	}
	public function comandasRestore(){
	 	$comunication = new ComunicationRestBar();
		$params = array();
		$PARAMS_TYPES = array ();
		$comanda = $comunication->query(self::RESTORE_COMANDA,$params,$PARAMS_TYPES);
		return $comanda;
	}
	public function getLiniasComanda($idComanda){
	 	$comunication = new ComunicationRestBar();
		$params = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING);
		$liniaComanda = $comunication->query(self::GETLINIAS,$params,$PARAMS_TYPES);
		return $liniaComanda;
	}

	public function existeIdComanda($idComanda){
	 	$comunication = new ComunicationRestBar();
		$params = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING);
		$idcomanda = $comunication->query(self::EXISTE_COMANDA,$params,$PARAMS_TYPES);
		$return = false;
        if ($idcomanda->getRecordCount()>0){
			$return =true;
		}	
		return $return;
	}
		
	public function getNextMaxIdComanda(){
	 	$comunication = new ComunicationRestBar();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_LAST_ID_COMANDA,$params,$PARAMS_TYPES);
        if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["numComanda"];
				}
		}	
		return $a;
	}

	public function getIdCaja(){
	 	$comunication = new ComunicationRestBar();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["id_caja"];
				}}	
		return $a;	
	}
	
		public function setComandaVenta($efectivo, $tipoCliente, $total, $idcliente ,$free){
		$comunication = new ComunicationRestBar();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$idCaja=$resultc["id_caja"];
				}}
		$estado="cobrado";
		if($tipoCliente==5)	$estado="credito"; 
		$PARAMS = array($estado,$efectivo, $tipoCliente, $total, $idcliente, $idCaja, $free);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TINT,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING);
		$idCom = $comunication->update(self::SET_COMANDA_VENTA,$PARAMS,$PARAMS_TYPES);
	
	return $idCom;
	}
	
	public function setComandaCredito($idComanda,$procedencia){
	    $comunication = new ComunicationRestBar();
	    $PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		$eorc = $comunication->query(self::EMP_OR_CLIENT,$PARAMS,$PARAMS_TYPES);
	    if ($eorc->getRecordCount()>0){
			while($eorc->next()){
				$resultc=$eorc->getRow();
				$aux=$resultc["cliente"];
				}}	
	    $PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		if ($aux==1) $total = $comunication->query(self::GET_CLIENTE_SUMA,$PARAMS,$PARAMS_TYPES);
		else if ($aux==0) $total = $comunication->query(self::GET_USUARIO_SUMA,$PARAMS,$PARAMS_TYPES);
		
		if ($total->getRecordCount()>0){
			while($total->next()){
				$resultc=$total->getRow();
				$suma=$resultc["suma"];
				}}	
		$PARAMS = array($idComanda,$suma,$procedencia);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT,ComunicationRestBar::$TFLOAT,ComunicationRestBar::$TSTRING);
		$result = $comunication->update(self::SET_COMANDA_USUARIO,$PARAMS,$PARAMS_TYPES);
	}


	
	public function borrarLineasComanda($comandaID){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($comandaID);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING);
		$result = $comunication->query(self::ERASE_LISTA_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function updateComandaAbierta($comandaID,$efectivo){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($efectivo,$comandaID);
		$PARAMS_TYPES = array (ComunicationRestBar::$TSTRING,ComunicationRestBar::$TSTRING);
		$result = $comunication->query(self::UPDATE_EFECTIVO_COMANDA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
}
?>

