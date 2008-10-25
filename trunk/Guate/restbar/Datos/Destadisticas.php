 <?php

require_once ('ComunicationRestBar.php');

class Destadisticas{
	
	const GET_YEARS = 'select year(fechaHoraApertura) as anyo from caja where estado=0 group by year(fechaHoraApertura)  order by year(fechaHoraApertura) desc';
	const CAJA_MOVIMIENTOS_YEAR ='select mes,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas from ( select month(fechaHora) as mes,sum(total) as suma,0 as entradas,0 as salidas from comanda where year(fechaHora)=? group by month(fechaHora) union select month(fechaHora) as mes,0 as suma,sum(dinero) as entradas,0 as salidas from movimiento where year(fechaHora)=? and tipo="entrada" group by month(fechaHora) union select month(fechaHora) as mes,0 as suma,0 as entradas,sum(dinero) as salidas from movimiento where year(fechaHora)=? and tipo="salida" group by month(fechaHora) ) as tmptable group by mes order by mes asc';
	const CAJA_MOVIMIENTOS_MONTH = 'select dia,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas from (select day(t2.fechaHoraApertura) as dia,sum(t1.total) as suma,0 as entradas,0 as salidas from comanda t1, caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and (t1.estado="cobrado" or t1.estado="facturado") and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) union select day(t2.fechaHoraApertura) as dia,0 as suma,sum(t1.dinero) as entradas,0 as salidas from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and t1.tipo="entrada" and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) union select day(t2.fechaHoraApertura) as dia,0 as suma,0 as entradas,sum(t1.dinero) as entradas from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and t1.tipo="salida" and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) ) as tmptable group by dia order by dia asc';
	const CAJA_MOVIMIENTOS_WEEK = 'select id_caja,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas,fechaHoraApertura,numday,fecha,mes,anyo from (select t2.id_caja,sum(t1.total) as suma,0 as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from comanda t1,caja t2 where year(t2.fechaHoraApertura)=? and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and (t1.estado="cobrado" or t1.estado="facturado") and t1.id_caja=t2.id_caja group by id_caja union select t2.id_caja,0 as suma,sum(t1.dinero) as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo="entrada" and t1.id_caja=t2.id_caja group by id_caja union select t2.id_caja,0 as suma,0 as entradas,sum(t1.dinero) as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo="salida" and t1.id_caja=t2.id_caja group by id_caja) as tmptable group by id_caja order by fechaHoraApertura asc';
	
	const TOP_BEBIDAS_MONTH = 'select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=? group by t2.nombre order by freq desc limit ?';
	const TOP_BEBIDAS_WEEK ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=? and weekofyear(t3.fechaHoraApertura)=weekofyear(?) group by t2.nombre order by freq desc limit ?';	
    const TOP_BEBIDAS_YEAR ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? group by t2.nombre order by freq desc limit ?';	
	
	const TOP_PLATILLOS_MONTH = 'select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.platillo t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idPlatillo and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=? group by t2.nombre order by freq desc limit ?';
	const TOP_PLATILLOS_WEEK ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.platillo t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idPlatillo and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=? and weekofyear(t3.fechaHoraApertura)=weekofyear(?) group by t2.nombre order by freq desc limit ?';	
    const TOP_PLATILLOS_YEAR ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,recepcion_bd.platillo t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idPlatillo and year(t3.fechaHoraApertura)=? group by t2.nombre order by freq desc limit ?';	

/*	$CAJA_MOVIMIENTOS_WEEK = 'select id_caja,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas,fechaHoraApertura,numday,fecha,mes,anyo from ('
          .'select t2.id_caja,sum(t1.total) as suma,0 as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo'.
          'from comanda t1,caja t2 where year(t2.fechaHoraApertura)=? and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.estado=cobrado and t1.id_caja=t2.id_caja group by id_caja'.
          'union select t2.id_caja,0 as suma,sum(t1.dinero) as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo=entrada and t1.id_caja=t2.id_caja group by id_caja'.
          'union select t2.id_caja,0 as suma,0 as entradas,sum(t1.dinero) as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo=salida and t1.id_caja=t2.id_caja group by id_caja'.
	      ') as tmptable group by id_caja order by fechaHoraApertura asc';
	*/	
	
		public function get_year (){
		$comunication = new ComunicationRestBar();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_YEARS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function caja_movimientos_year($currentyear){
		$comunication = new ComunicationRestBar();
		$params = array($currentyear,$currentyear,$currentyear);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_YEAR,$params,$PARAMS_INSERT);
		
		return $result;
	}
	
	public function caja_month($currentyear,$currentmes){
		$comunication = new ComunicationRestBar();
		$params = array($currentyear,$currentmes,$currentyear,$currentmes,$currentyear,$currentmes);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_MONTH,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function caja_movimientos_week($year,$date){		
		$comunication = new ComunicationRestBar();
		$params = array($year,$date,$year,$date,$year,$date);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_WEEK,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function topPlatillosWeek($tipoEstadistica,$year,$month,$date,$limit){
	$comunication = new ComunicationRestBar();
		$params = array($year,$month,$date,$limit);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TSTRING,ComunicationRestBar::$TINT);
		if($tipoEstadistica=="P") $result = $comunication->query(self::TOP_PLATILLOS_WEEK,$params,$PARAMS_INSERT);
		else if($tipoEstadistica=="B") $result = $comunication->query(self::TOP_BEBIDAS_WEEK,$params,$PARAMS_INSERT);
		return $result;	
	}
	public function topPlatillosMonth($tipoEstadistica,$year,$month,$limit){
	$comunication = new ComunicationRestBar();
		$params = array($year,$month,$limit);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TINT,ComunicationRestBar::$TINT);
		if($tipoEstadistica=="P") $result = $comunication->query(self::TOP_PLATILLOS_MONTH,$params,$PARAMS_INSERT);
		else if($tipoEstadistica=="B") $result = $comunication->query(self::TOP_BEBIDAS_MONTH,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	public function topPlatillosYear($tipoEstadistica,$year,$limit){
	$comunication = new ComunicationRestBar();
		$params = array($year,$limit);
		$PARAMS_INSERT = array(ComunicationRestBar::$TINT,ComunicationRestBar::$TINT);
		if($tipoEstadistica=="P") $result = $comunication->query(self::TOP_PLATILLOS_YEAR,$params,$PARAMS_INSERT);
		else if($tipoEstadistica=="B") $result = $comunication->query(self::TOP_BEBIDAS_YEAR,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	
}
?>
