 <?php
require_once ('ComunicationRes.php');

class Dreporte{
	
	const GET_CATEGORIES = 'select nombre from categoria';
	const GET_ID_CAJA = 'select id_caja from caja where estado=1';
	const GET_RESUMEN_REPORTE = 'select t1.tipo,t3.nombre as categoria,sum(t1.dinero) as suma from movimiento t1,caja t2,categoria t3 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=t1.id_categoria group by categoria,tipo';
	const GET_FECHAHORAAPERTURA = 'select fechaHoraApertura from caja where id_caja=?';
	const GET_FECHAHORACIERRE = 'select fechaHoraCierre from caja where id_caja=?';
	const GET_REPORTE = 'select t3.id_categoria,date(t1.fechaHora) as date,time(t1.fechaHora) as time,t1.descripcion,t1.dinero as entrada,0 as salida,t3.nombre as categoria from movimiento t1,caja t2,categoria t3 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=t1.id_categoria and t1.tipo="entrada" union select t3.id_categoria,date(t1.fechaHora) as date,time(t1.fechaHora) as time,t1.descripcion,0 as entrada,t1.dinero as salida,t3.nombre as categoria from movimiento t1,caja t2,categoria t3 where t1.id_caja=t2.id_caja and t2.id_caja=? and t3.id_categoria=t1.id_categoria and t1.tipo="salida" order by id_categoria';
	const GET_TIQUETS = 'select date(fechaHora) as fecha,time(fechaHora) as time,idComanda,total from comanda t1,caja t2 where t1.id_caja=t2.id_caja and t2.id_caja=?';
	
	public function get_categories(){
		$comunication = new ComunicationRes();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_CATEGORIES,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	public function get_resumen($idcaja){
		$comunication = new ComunicationRes();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->query(self::GET_RESUMEN_REPORTE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	public function get_fechaHoraApertura($idcaja){
		$comunication = new ComunicationRes();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->query(self::GET_FECHAHORAAPERTURA,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	public function get_fechaHoraCierre($idcaja){
		$comunication = new ComunicationRes();
		$PARAMS = array($idcaja);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->query(self::GET_FECHAHORACIERRE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	public function get_reporte($id_caja){
		$comunication = new ComunicationRes();	
		$PARAMS = array($id_caja,$id_caja);
		$PARAMS_TYPES = array (ComunicationRes::$TINT,ComunicationRes::$TINT);
		$result = $comunication->query(self::GET_REPORTE,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
	public function get_tiquets($id_caja){
		$comunication = new ComunicationRes();	
		$PARAMS = array($id_caja);
		$PARAMS_TYPES = array (ComunicationRes::$TINT);
		$result = $comunication->query(self::GET_TIQUETS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
}
?>
