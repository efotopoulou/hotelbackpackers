<?php

require_once ('ComunicationRestBar.php');

class Dcocina{
	
	const SELECT_PEDIDOS = 'select t1.idLineaComanda,t4.idComanda,t2.idPlatillo,t3.nombre,t2.cantidad,time(t4.fechaHora) as hora from cocina t1,lineacomanda t2,recepcion_bd.platillo t3,comanda t4 where t4.idComanda=t2.idComanda and t1.idLineaComanda=t2.idLineaComanda and t3.idPlatillo=t2.idPlatillo and t1.presentado=1 order by t4.fechaHora asc';
	const DELETE_PEDIDOS = 'delete from cocina where 1=1';
	const ELIMINAR_LINEA = 'UPDATE cocina SET presentado=0,horadelete=NOW() where idLineaComanda=?';
	const RECUPERAR_LINEAID = 'select t1.idLineaComanda from cocina t1,lineacomanda t2,recepcion_bd.platillo t3,comanda t4 where t4.idComanda=t2.idComanda and t1.idLineaComanda=t2.idLineaComanda and t3.idPlatillo=t2.idPlatillo order by t1.horadelete desc limit 1';
	const RECUPERAR_LINEA = 'UPDATE cocina SET presentado=1,horadelete=null where idLineaComanda=?';
		
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
	public function eliminar_pedido ($idLineaComanda){
		$comunication = new ComunicationRestBar();
		$PARAMS = array($idLineaComanda);
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
				$a=$resultl["idLineaComanda"];
				}}		

		$params = array($a);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT);
		$result = $comunication->update(self::RECUPERAR_LINEA,$params,$PARAMS_INSERT);
		return $result;
	
	}
}
?>