<?php

require_once ('ComunicationRecep.php');

class Dstock{
	
	const GET_STOCK = 'select t1.idBebida,t2.numBebida,t3.nombre as familia,t2.nombre,t1.stockbar,t1.stockrestaurante,t1.unidadventa from stockbebidas t1,bebida t2,familiabebida t3 where t2.idBebida=t1.idBebida and t2.id_familia=t3.id_familia order by stockrestaurante desc,t3.nombre';
    const GET_BEBIDA = 'select stockbar,stockrestaurante,unidadventa from stockbebidas where idBebida=?';
	const ADD_STOCK_RECEPCION = 'UPDATE stockbebidas SET stockrestaurante=?,unidadventa=? where idBebida=?';
    const ADD_STOCK_RESTBAR = 'UPDATE stockbebidas SET stockbar=?,unidadventa=? where idBebida=?';
    const INFORM_STOCK_BAR = 'UPDATE stockbebidas SET stockbar=? where idBebida=?';
	const INFORM_STOCK_REST = 'UPDATE stockbebidas SET stockrestaurante=? where idBebida=?';
	const GET_STOCK_RECEPTION = 'select t2.nombre, t2.idBebida,t2.precioNormal,t2.precioLimitado from stockbebidas t1,bebida t2 where t2.idBebida=t1.idBebida and stockrestaurante!=0 order by numBebida';
	const VENTA_TURNO = 'select t1.idBebida,t5.numBebida,t5.nombre,sum(t4.cantidad)as suma from stockbebidas t1,caja t2,comanda t3,lineacomanda t4,bebida t5 where t1.stockrestaurante != 0 and t1.idBebida=t4.idPlatillo and t2.estado=1 and t2.id_caja=t3.id_caja and t3.idComanda=t4.idComanda and t1.idBebida=t5.idBebida and t3.estado!="anulado" group by t1.idBebida,t3.tipoCliente';
	const VENTA_TURNO_BAR = 'select t1.idBebida,t5.numBebida,t5.nombre,sum(t4.cantidad)as suma from stockbebidas t1,restbar_bd.caja t2,restbar_bd.comanda t3,restbar_bd.lineacomanda t4,bebida t5 where t1.stockrestaurante != 0 and t1.idBebida=t4.idPlatillo and t2.estado=1 and t2.id_caja=t3.id_caja and t3.idComanda=t4.idComanda and t1.idBebida=t5.idBebida and t3.estado!="anulado" group by t1.idBebida,t3.tipoCliente';
	const RECUPERAR_VENTA_CAJA = 'select id_caja,recuperarventa from caja where estado=1';
	const RECUPERAR_VENTA_CAJA_1 = 'select recuperarventa from caja where estado=1';
	const UPDATE_RECUPERAR_VENTA_CAJA = 'UPDATE caja SET recuperarventa=1 where id_caja=?';
	
	
	public function get_stock (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_STOCK,$PARAMS,$PARAMS_TYPES);
			
		return $result;
	}	
	public function add_stock($addornew,$idbebida,$stockbar,$stockrestaurante,$unidadventa){
	   $comunication = new ComunicationRecep();
		if ($stockrestaurante==""){
			$PARAMS = array($stockbar,$unidadventa,$idbebida);	
			$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		    $result = $comunication->query(self::ADD_STOCK_RESTBAR,$PARAMS,$PARAMS_TYPES);
		}else{ 
			$PARAMS = array($stockrestaurante,$unidadventa,$idbebida);	
			$PARAMS_TYPES = array (ComunicationRecep::$TFLOAT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		    $result = $comunication->query(self::ADD_STOCK_RECEPCION,$PARAMS,$PARAMS_TYPES);
		}
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
	
	public function venta_turno(){
	$comunication = new ComunicationRecep();
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$result = $comunication->query(self::VENTA_TURNO,$PARAMS,$PARAMS_TYPES);
	return $result;
		
	}
	
	public function recuperar_venta(){
	$comunication = new ComunicationRecep();
	//primero de todo veremos si en el stock se han recuperado los productos
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$rs = $comunication->query(self::RECUPERAR_VENTA_CAJA,$PARAMS,$PARAMS_TYPES);
	
   while($rs->next()){
       $result=$rs->getRow();
	   $id_caja=$result["id_caja"];
	   $recuperarventa=$result["recuperarventa"];
   }
   //si se han recuperado al pasado no hacemos nada else hacemos tres actos 
   //1.pedimos la informacion de las ventas 2.añadimos estas ventas a la nevera 3.y informamos la caja  
   if($recuperarventa==0){  
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$rs = $comunication->query(self::VENTA_TURNO,$PARAMS,$PARAMS_TYPES);
	   if ($rs->getRecordCount()>0){
	   $n=0;
	       while($rs->next()){
              $result=$rs->getRow();
	          $idbebida=$result["idBebida"];
	          $suma=$result["suma"];
	          $this->add_stock('b6',$idbebida,0,$suma,1);
	          $n++;
		   }														
       }
    $PARAMS = array($id_caja);
	$PARAMS_TYPES = array (ComunicationRecep::$TINT);
	$rs = $comunication->query(self::UPDATE_RECUPERAR_VENTA_CAJA,$PARAMS,$PARAMS_TYPES);
       
	}
	
 }	
 
 public function venta_turno_bar(){
 	$comunication = new ComunicationRecep();
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$rs = $comunication->query(self::VENTA_TURNO_BAR,$PARAMS,$PARAMS_TYPES);
 }
 
 public function recuperar_venta_caja(){
    $comunication = new ComunicationRecep();
	$PARAMS = array();
	$PARAMS_TYPES = array ();
	$recuperarventa = $comunication->query(self::RECUPERAR_VENTA_CAJA_1,$PARAMS,$PARAMS_TYPES);
	if ($recuperarventa->getRecordCount()>0){
			while($recuperarventa->next()){
				$resultc=$recuperarventa->getRow();
				$a=$resultc["recuperarventa"];
				}}
	return $a;	
 }
	
}
?>


