 <?php

require_once ('ComunicationRecep.php');

class Destadisticas{
	
	const GET_YEARS = 'select year(fechaHoraApertura) as anyo from caja where estado=0 group by year(fechaHoraApertura)  order by year(fechaHoraApertura) desc';
	const CAJA_MOVIMIENTOS_YEAR ='select mes,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas from ( select month(fechaHora) as mes,sum(total) as suma,0 as entradas,0 as salidas from comanda where year(fechaHora)=? group by month(fechaHora) union select month(fechaHora) as mes,0 as suma,sum(dinero) as entradas,0 as salidas from movimiento where year(fechaHora)=? and tipo="entrada" group by month(fechaHora) union select month(fechaHora) as mes,0 as suma,0 as entradas,sum(dinero) as salidas from movimiento where year(fechaHora)=? and tipo="salida" group by month(fechaHora) ) as tmptable group by mes order by mes asc';
	const CAJA_MOVIMIENTOS_MONTH = 'select dia,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas from (select day(t2.fechaHoraApertura) as dia,sum(t1.total) as suma,0 as entradas,0 as salidas from comanda t1, caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and (t1.estado="cobrado" or t1.estado="facturado") and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) union select day(t2.fechaHoraApertura) as dia,0 as suma,sum(t1.dinero) as entradas,0 as salidas from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and t1.tipo="entrada" and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) union select day(t2.fechaHoraApertura) as dia,0 as suma,0 as entradas,sum(t1.dinero) as entradas from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=? and month(t2.fechaHoraApertura)=? and t1.tipo="salida" and t1.id_caja=t2.id_caja group by day(t2.fechaHoraApertura) ) as tmptable group by dia order by dia asc';
	const CAJA_MOVIMIENTOS_WEEK = 'select id_caja,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas,fechaHoraApertura,numday,fecha,mes,anyo from (select t2.id_caja,sum(t1.total) as suma,0 as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from comanda t1,caja t2 where year(t2.fechaHoraApertura)=? and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and (t1.estado="cobrado" or t1.estado="facturado") and t1.id_caja=t2.id_caja group by id_caja union select t2.id_caja,0 as suma,sum(t1.dinero) as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo="entrada" and t1.id_caja=t2.id_caja group by id_caja union select t2.id_caja,0 as suma,0 as entradas,sum(t1.dinero) as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo="salida" and t1.id_caja=t2.id_caja group by id_caja) as tmptable group by id_caja order by fechaHoraApertura asc';
	const INSERT_TEMPORARY = 'INSERT INTO temporarytable select t2.nombre,sum(t1.cantidad) as freq from restbar_bd.lineacomanda t1,platillo t2,restbar_bd.comanda t4 where t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idPlatillo and t4.estado !="anulado" and fechaHora <= ? and  fechaHora >= ? group by t2.nombre order by freq desc';
	const TOP_PLATILLOS = 'select t2.nombre,sum(t1.cantidad) as freq,tipoCliente, t5.freq as freq2 from restbar_bd.lineacomanda t1,platillo t2, restbar_bd.comanda t4, temporarytable t5 where t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idPlatillo and t4.estado !="anulado" and t5.nombre = t2.nombre and fechaHora <= ? and  fechaHora >= ? group by t2.nombre,tipoCliente order by freq2 desc, t2.nombre,tipoCliente desc';
	const DELETE_TEMPORARY = 'delete from temporarytable where 1=1';
	const TOP_PLATILLOS_MONTH = 'select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=?  group by t2.nombre order by freq desc limit ?';
	const TOP_PLATILLOS_WEEK ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? and month(t3.fechaHoraApertura)=? and weekofyear(t3.fechaHoraApertura)=weekofyear(?) group by t2.nombre order by freq desc limit ?';	
    const TOP_PLATILLOS_YEAR ='select t2.nombre,sum(t1.cantidad) as freq from lineacomanda t1,bebida t2,caja t3,comanda t4 where t3.id_caja=t4.id_caja and t4.idComanda=t1.idComanda and t1.idPlatillo=t2.idBebida and year(t3.fechaHoraApertura)=? group by t2.nombre order by freq desc limit ?';	

/*	$CAJA_MOVIMIENTOS_WEEK = 'select id_caja,sum(suma) as suma,sum(entradas) as entradas,sum(salidas) as salidas,fechaHoraApertura,numday,fecha,mes,anyo from ('
          .'select t2.id_caja,sum(t1.total) as suma,0 as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo'.
          'from comanda t1,caja t2 where year(t2.fechaHoraApertura)=? and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.estado=cobrado and t1.id_caja=t2.id_caja group by id_caja'.
          'union select t2.id_caja,0 as suma,sum(t1.dinero) as entradas,0 as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo=entrada and t1.id_caja=t2.id_caja group by id_caja'.
          'union select t2.id_caja,0 as suma,0 as entradas,sum(t1.dinero) as salidas,t2.fechaHoraApertura, WEEKDAY(t2.fechaHoraApertura) as numday,day(t2.fechaHoraApertura) as fecha,month(t2.fechaHoraApertura) as mes,year(t2.fechaHoraApertura) as anyo from movimiento t1,caja t2 where year(t2.fechaHoraApertura)=?  and weekofyear(t2.fechaHoraApertura)=weekofyear(?) and t1.tipo=salida and t1.id_caja=t2.id_caja group by id_caja'.
	      ') as tmptable group by id_caja order by fechaHoraApertura asc';
	*/	
	
		public function get_year (){
		$comunication = new ComunicationRecep();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_YEARS,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	
	public function caja_movimientos_year($currentyear){
		$comunication = new ComunicationRecep();
		$params = array($currentyear,$currentyear,$currentyear);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_YEAR,$params,$PARAMS_INSERT);
		
		return $result;
	}
	
	public function caja_month($currentyear,$currentmes){
		$comunication = new ComunicationRecep();
		$params = array($currentyear,$currentmes,$currentyear,$currentmes,$currentyear,$currentmes);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_MONTH,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function caja_movimientos_week($year,$date){		
		$comunication = new ComunicationRecep();
		$params = array($year,$date,$year,$date,$year,$date);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::CAJA_MOVIMIENTOS_WEEK,$params,$PARAMS_INSERT);
		
		return $result;
	}
	public function topPlatillos($fechaInicio,$fechaFin){
	$comunication = new ComunicationRecep();
		$params = array($fechaFin,$fechaInicio);
		$PARAMS_INSERT = array(ComunicationRecep::$TDATE,ComunicationRecep::$TDATE);
		$comunication->query(self::INSERT_TEMPORARY,$params,$PARAMS_INSERT);
		$result = $comunication->query(self::TOP_PLATILLOS,$params,$PARAMS_INSERT);
		$params = array();
		$PARAMS_INSERT = array();
		$comunication->update(self::DELETE_TEMPORARY,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	public function topPlatillosWeek($year,$month,$date,$limit){
	$comunication = new ComunicationRecep();
		$params = array($year,$month,$date,$limit);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TSTRING,ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOP_PLATILLOS_WEEK,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	public function topPlatillosMonth($year,$month,$limit){
	$comunication = new ComunicationRecep();
		$params = array($year,$month,$limit);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOP_PLATILLOS_MONTH,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	public function topPlatillosYear($year,$limit){
	$comunication = new ComunicationRecep();
		$params = array($year,$limit);
		$PARAMS_INSERT = array(ComunicationRecep::$TINT,ComunicationRecep::$TINT);
		$result = $comunication->query(self::TOP_PLATILLOS_YEAR,$params,$PARAMS_INSERT);
		
		return $result;	
	}
	
}
?>
