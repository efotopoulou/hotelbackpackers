<?php

require_once ('ComunicationRestBar.php');

class Dturno{
	
	const GET_TURNO_CAJA = 'SELECT turno from caja where estado=1';
	const VENTA_TURNO_BAR = 'select t5.numBebida,t5.nombre,sum(t4.cantidad)as suma,sum(t4.precio) as precio from recepcion_bd.stockbebidas t1,caja t2,comanda t3,lineacomanda t4,recepcion_bd.bebida t5 where t1.idBebida=t4.idPlatillo and t2.id_caja=? and t2.id_caja=t3.id_caja and t3.idComanda=t4.idComanda and t1.idBebida=t5.idBebida and t3.estado!="anulado" group by t1.idBebida,t3.tipoCliente';
	
	public function get_turno_caja (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_TURNO_CAJA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function venta_turno_bar ($idcaja){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		$result = $comunication->query(self::VENTA_TURNO_BAR,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
}
?>
