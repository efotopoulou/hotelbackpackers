<?php

require_once ('ComunicationRecep.php');

class Dstock{
	
	const GET_STOCK = 'select t1.idBebida,t2.numBebida,t2.nombre,t1.stockbar,t1.stockrestaurante,t1.unidadventa from stockbebidas t1,bebida t2 where t2.idBebida=t1.idBebida order by numBebida';
    const GET_BEBIDA = 'select stockbar,stockrestaurante,unidadventa from stockbebidas where idBebida=?';
	const ADD_STOCK = 'UPDATE stockbebidas SET stockbar=?,stockrestaurante=?,unidadventa=? where idBebida=?';
    const INFORM_STOCK_BAR = 'UPDATE stockbebidas SET stockbar=? where idBebida=?';
	const INFORM_STOCK_REST = 'UPDATE stockbebidas SET stockrestaurante=? where idBebida=?';
	const GET_STOCK_RECEPTION = 'select t2.nombre, t2.idBebida,t2.precioNormal,t2.precioLimitado from stockbebidas t1,bebida t2 where t2.idBebida=t1.idBebida and stockrestaurante!=0 order by numBebida';
	
	const SET_PLATILLOS = 'INSERT INTO comanda values(0,?,\'cobrado\',NOW(),?,?,?,?,?,?)';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const ERASE_LISTA_PLATILLOS = 'DELETE FROM lineacomanda WHERE idComanda=?';
	const SET_LISTA_PLATILLOS = 'INSERT INTO lineacomanda values (0,?,?,?,?)';
	const GET_IDCAJA = 'SELECT * FROM caja WHERE estado=1';
	const GET_LAST_ID_COMANDA =	'SELECT numComanda FROM comanda c order by fechaHora desc limit 1';

	
	public function get_stock (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_STOCK,$PARAMS,$PARAMS_TYPES);
			
		return $result;
	}	
	public function add_stock($addornew,$idbebida,$stockbar,$stockrestaurante,$unidadventa){
	   $comunication = new ComunicationRecep();
	    if ($addornew == 'b6'){
		$PARAMS = array($idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		if ($result->getRecordCount()>0){
			while($result->next()){
				$rs=$result->getRow();
				$sb=$rs["stockbar"];
				$sr=$rs["stockrestaurante"];
				}}	
		$stockdebar=$sb+$stockbar;
		$stockderestaurante=$sr+$stockrestaurante;
	
		$PARAMS = array($stockdebar,$stockderestaurante,$unidadventa,$idbebida);
		
	    }else if ($addornew == 'b7'){
	    $PARAMS = array($stockbar,$stockrestaurante,$unidadventa,$idbebida);	
	    }
		$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::ADD_STOCK,$PARAMS,$PARAMS_TYPES);
		
	}
	
	public function get_stock_bebida($idbebida){
	    $comunication = new ComunicationRecep();
		$PARAMS = array($idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		if ($result->getRecordCount()>0){
			while($result->next()){
				$rs=$result->getRow();
				$sb=$rs["stockbar"];
				$sr=$rs["stockrestaurante"];
				}}	
		$stock=$sb+$sr;	
		return $stock;
	}
	
	public function informar_controlstock($idbebida,$cantidad){
	$comunication = new ComunicationRecep();
		$PARAMS = array($idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		if ($result->getRecordCount()>0){
			while($result->next()){
				$rs=$result->getRow();
				$sb=$rs["stockbar"];
				$unidad=$rs["unidadventa"];
				}}	
		$stockbar=$sb-(1/$unidad)*$cantidad;	
	    $PARAMS = array($stockbar,$idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::INFORM_STOCK_BAR,$PARAMS,$PARAMS_TYPES);
	}
	
	public function informar_stock_rest($idbebida,$cantidad){
	$comunication = new ComunicationRecep();
		$PARAMS = array($idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		$result = $comunication->query(self::GET_BEBIDA,$PARAMS,$PARAMS_TYPES);
		
		if ($result->getRecordCount()>0){
			while($result->next()){
				$rs=$result->getRow();
				$sr=$rs["stockrestaurante"];
				$unidad=$rs["unidadventa"];
				}}	
		$stockrestaurante=$sr-(1/$unidad)*$cantidad;	
	    $PARAMS = array($stockrestaurante,$idbebida);
		$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::INFORM_STOCK_REST,$PARAMS,$PARAMS_TYPES);
	}
	
	public function get_stockreception(){
	$comunication = new ComunicationRecep();
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$result = $comunication->query(self::GET_STOCK_RECEPTION,$PARAMS,$PARAMS_TYPES);
	return $result;
			
	}
	
}
?>


