<?php

require_once ('ComunicationRestBar.php');

class Dcocina{
	
	const SELECT_PEDIDOS = 'select t1.idCocina,t1.comanda,t1.platoId,t2.nombre,t1.cantidad,time(t1.hora) as hora from cocina t1,recepcion_bd.platillo t2 where  t1.platoId=t2.idPlatillo and t1.presentado=1 order by hora asc';
	const DELETE_PEDIDOS = 'delete from cocina where 1=1';
	const ELIMINAR_LINEA = 'UPDATE cocina SET presentado=0,horadelete=NOW() where idCocina=?';
	const RECUPERAR_LINEAID = 'select idCocina from cocina  where horadelete is not null  order by horadelete desc limit 1';
	const RECUPERAR_LINEA = 'UPDATE cocina SET presentado=1,horadelete=null where idCocina=?';
		
	public function select_pedidos (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::SELECT_PEDIDOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function delete_pedidos (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::DELETE_PEDIDOS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function eliminar_pedido ($eliminarpedidoid){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($eliminarpedidoid);
		$PARAMS_TYPES = array (ComunicationRestBar::$TINT);
		$result = $comunication->update(self::ELIMINAR_LINEA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	
	}
	
	public function recuperar_pedido (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$lineaid = $comunication->query(self::RECUPERAR_LINEAID,$PARAMS,$PARAMS_TYPES);
		
		if ($lineaid->getRecordCount()>0){
			while($lineaid->next()){
				$resultl=$lineaid->getRow();
				$a=$resultl["idCocina"];
			}
			$params = array($a);
		    $PARAMS_INSERT = array(ComunicationRestBar::$TINT);
		    $result = $comunication->update(self::RECUPERAR_LINEA,$params,$PARAMS_INSERT);
		}		
		return $result;
	
	}
}
?>