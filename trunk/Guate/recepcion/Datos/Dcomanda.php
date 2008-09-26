<?php

require_once ('ComunicationRecep.php');

class Dcomanda{
	//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,total,id_cliente,id_caja
	const SET_PLATILLOS = 'INSERT INTO comanda values(?,\'abierta\',NOW(),?,?,?,?,?,?,?)';
	const SET_COMANDA = 'INSERT INTO comanda values(?,?,NOW(),?,?,?,?,?,?,?)';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const ERASE_LISTA_PLATILLOS = 'DELETE FROM lineacomanda WHERE idComanda=?';
	const SET_LISTA_PLATILLOS = 'INSERT INTO lineacomanda values (0,?,?,?,?)';
	const UPDATE_EFECTIVO_COMANDA = 'UPDATE comanda SET efectivo=?,estado=\'cobrado\' WHERE idComanda=?';
	const GET_IDCAJA = 'SELECT * FROM caja WHERE estado=1';
	const GET_LAST_ID_COMANDA =	'SELECT idComanda FROM comanda c order by fechaHora desc limit 1';
    const ES_COCINA ='select cocina from platillo where idPlatillo=?';
    const SET_COCINA ='insert into cocina values (?,true,null)';
    const GET_LAST_ID='select auto_increment -1 as id from information_schema.tables where table_schema = \'restaurante_bd\' and table_name = \'lineacomanda\';';
    const EXISTE_COMANDA='select idComanda from comanda where idComanda= ?';
    const RESTORE_COMANDA='SELECT idComanda, efectivo, mesa, tipoCliente, total,id_cliente, free, NULL as clienteName FROM comanda WHERE estado=\'backup\' and (tipoCliente=1 or tipoCliente=4) UNION SELECT c.idComanda, c.efectivo, c.mesa, c.tipoCliente, c.total,c.id_cliente, c.free, CONCAT(cli.nombre, \' \', cli.apellido1, \' \', cli.apellido2) FROM comanda c, guate_bd.cliente cli WHERE estado=\'backup\' and c.tipoCliente=3 and cli.id_cliente = c.id_cliente UNION SELECT c.idComanda, c.efectivo, c.mesa, c.tipoCliente, c.total,c.id_cliente, c.free, u.nombre FROM comanda c, guate_bd.usuario u WHERE estado=\'backup\' and c.tipoCliente=2 and u.Id_usuario = c.id_cliente';
    const GETLINIAS='SELECT lc.idPlatillo,lc.cantidad,lc.precio as precioN, (lc.precio/lc.cantidad) as precioUnidad, p.precioNormal, p.precioLimitado, p.nombre as producto FROM lineacomanda lc, platillo p where lc.idComanda=? and p.idPlatillo=lc.idPlatillo';
    const BORRAR_COMANDA='DELETE FROM comanda WHERE estado=?';
    const BORRAR_LINEA_COMANDA='DELETE FROM lineacomanda WHERE idComanda IN (SELECT idComanda FROM comanda WHERE estado=?)';
   
    public function borrarComanda($estado){
	 	$comunication = new ComunicationRecep();
		$params = array($estado);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$comanda = $comunication->query(self::BORRAR_LINEA_COMANDA,$params,$PARAMS_TYPES);
		$comanda = $comunication->query(self::BORRAR_COMANDA,$params,$PARAMS_TYPES);
		return $comanda;
	}
	public function comandasRestore(){
	 	$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$comanda = $comunication->query(self::RESTORE_COMANDA,$params,$PARAMS_TYPES);
		return $comanda;
	}
	public function getLiniasComanda($idComanda){
	 	$comunication = new ComunicationRecep();
		$params = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$liniaComanda = $comunication->query(self::GETLINIAS,$params,$PARAMS_TYPES);
		return $liniaComanda;
	}

	public function existeIdComanda($idComanda){
	 	$comunication = new ComunicationRecep();
		$params = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$idcomanda = $comunication->query(self::EXISTE_COMANDA,$params,$PARAMS_TYPES);
		$return = false;
        if ($idcomanda->getRecordCount()>0){
			$return =true;
		}	
		return $return;
	}
		
	public function getNextMaxIdComanda(){
	 	$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_LAST_ID_COMANDA,$params,$PARAMS_TYPES);
        if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$a=$resultc["idComanda"];
				}
		}	
		return $a;
	}

	public function getIdCaja(){
	 	$comunication = new ComunicationRecep();
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
	
	public function set_comandaAbierta($comandaID,$efectivo,$numMesa, $tipoCliente, $total, $idcliente,$free){
		$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$idCaja=$resultc["id_caja"];
				}}	
		$PARAMS = array($comandaID,$efectivo,$numMesa, $tipoCliente, $total, $idcliente, $idCaja, $free);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::SET_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
		public function setComanda($comandaID,$estado,$efectivo,$numMesa, $tipoCliente, $total, $idcliente,$free){
		$comunication = new ComunicationRecep();
		$params = array();
		$PARAMS_TYPES = array ();
		$idcaja = $comunication->query(self::GET_ID_CAJA,$params,$PARAMS_TYPES);
		
		
      if ($idcaja->getRecordCount()>0){
			while($idcaja->next()){
				$resultc=$idcaja->getRow();
				$idCaja=$resultc["id_caja"];
				}}	
		$PARAMS = array($comandaID,$estado,$efectivo,$numMesa, $tipoCliente, $total, $idcliente, $idCaja, $free);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::SET_COMANDA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function borrarLineasComanda($comandaID){
		$comunication = new ComunicationRecep();
		$PARAMS = array($comandaID);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::ERASE_LISTA_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function setLineaComanda($comandaID,$platoId,$cantidad,$precio){
		$comunication = new ComunicationRecep();
		$PARAMS = array($comandaID,$platoId,$cantidad,$precio);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT);
		$result = $comunication->update(self::SET_LISTA_PLATILLOS,$PARAMS,$PARAMS_TYPES);
		$id = $comunication->query(self::GET_LAST_ID,array(),array());
	    $id->next();
		$id=$id->getRow();
		$aux = $id["id"];
		return $aux;
		
	}
	public function updateComandaAbierta($comandaID,$efectivo){
		$comunication = new ComunicationRecep();
		$PARAMS = array($efectivo,$comandaID);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::UPDATE_EFECTIVO_COMANDA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function esCocina($idPlatillo){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idPlatillo);
		$PARAMS_TYPES = array (ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::ES_COCINA,$PARAMS,$PARAMS_TYPES);
		$result->next();
		$result=$result->getRow();
		$aux = $result["cocina"];
		return $aux;
	}
	public function setCocina($idLineaComanda){
		$comunication = new ComunicationRecep();
		$PARAMS = array($idLineaComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::SET_COCINA,$PARAMS,$PARAMS_TYPES);
		return $result;
	}
}
?>

