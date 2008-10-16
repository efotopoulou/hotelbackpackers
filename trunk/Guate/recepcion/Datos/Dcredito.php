<?php
require_once ('ComunicationRecep.php');

class Dcredito{


    const GET_USUARIO_SUMA = 'select sum(t2.precioLimitado*t1.cantidad) as suma from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo group by idComanda';
	const GET_CLIENTE_SUMA = 'select sum(t2.precioNormal*t1.cantidad) as suma from lineacomanda t1,bebida t2 where idComanda=? and t2.idBebida=t1.idPlatillo group by idComanda';
	const SET_COMANDA_USUARIO = 'INSERT INTO comandacredito values(?,?,false,?)';
	
	const EMP_OR_CLIENT = 'select t3.cliente from comanda t1,trabajador t3 where  t1.id_cliente=t3.idTrabajador and t1.idComanda=?';
	const EMP_OR_CLIENT_RESTBAR = 'select t3.cliente from restbar_bd.comanda t1,trabajador t3 where  t1.id_cliente=t3.idTrabajador and t1.idComanda=?';
	
	const GET_USUARIO_SUMA_PLATILLO = 'select sum(t2.precioLimitado*t1.cantidad) as suma from lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo group by idComanda';
	const GET_CLIENTE_SUMA_PLATILLO = 'select sum(t2.precioNormal*t1.cantidad) as suma from lineacomanda t1,platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo group by idComanda';
	const GET_USUARIO_SUMA_RESTBAR = 'select sum(t2.precioLimitado*t1.cantidad) as suma from restbar_bd.lineacomanda t1,recepcion_bd.platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo group by idComanda';
	const GET_CLIENTE_SUMA_RESTBAR = 'select sum(t2.precioNormal*t1.cantidad) as suma from restbar_bd.lineacomanda t1,recepcion_bd.platillo t2 where idComanda=? and t2.idPlatillo=t1.idPlatillo group by idComanda';
	
	
public function setComandaCredito($idComanda,$procedencia){
	    $aux=$this->emp_or_client($idComanda,$procedencia);
        $comunication = new ComunicationRecep();
	    $PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		if ($aux==1) $total = $comunication->query(self::GET_CLIENTE_SUMA,$PARAMS,$PARAMS_TYPES);
		else if ($aux==0) $total = $comunication->query(self::GET_USUARIO_SUMA,$PARAMS,$PARAMS_TYPES);
		
		if ($total->getRecordCount()>0){
			while($total->next()){
				$resultc=$total->getRow();
				$suma=$resultc["suma"];
				}}	
		$PARAMS = array($idComanda,$suma,$procedencia);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING);
		$result = $comunication->update(self::SET_COMANDA_USUARIO,$PARAMS,$PARAMS_TYPES);
	}
	public function setComandaCreditoComida($idComanda,$procedencia){
	    $comunication = new ComunicationRecep();
	    $cliente=$this->emp_or_client($idComanda,$procedencia);
	    $costecredito=$this->coste_credito($procedencia,$cliente,$idComanda);
		$PARAMS = array($idComanda,$costecredito,$procedencia);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT,ComunicationRecep::$TFLOAT,ComunicationRecep::$TSTRING);
		$result = $comunication->query(self::SET_COMANDA_USUARIO,$PARAMS,$PARAMS_TYPES);
	}

//----------------------------FUNCIONES QUE USA LA FUNCION(setComandaCreditoComida)----------------------------------
public function emp_or_client($idComanda,$procedencia){
	$comunication = new ComunicationRecep();
	    $PARAMS = array($idComanda);
		$PARAMS_TYPES = array (ComunicationRecep::$TINT);
		if($procedencia=="HR") $eorc = $comunication->query(self::EMP_OR_CLIENT,$PARAMS,$PARAMS_TYPES);
	    else $eorc = $comunication->query(self::EMP_OR_CLIENT_RESTBAR,$PARAMS,$PARAMS_TYPES);
	 
	    if ($eorc->getRecordCount()>0){
			while($eorc->next()){
				$resultc=$eorc->getRow();
				$aux=$resultc["cliente"];
				}}
	return $aux;
}	
public function coste_credito($procedencia,$cliente,$idComanda){
	$comunication = new ComunicationRecep();
    $PARAMS = array($idComanda);
	$PARAMS_TYPES = array (ComunicationRecep::$TINT);
	if($procedencia=="HR" && $cliente==0) $total = $comunication->query(self::GET_USUARIO_SUMA_PLATILLO,$PARAMS,$PARAMS_TYPES);
	else if($procedencia=="HR" && $cliente==1) $total = $comunication->query(self::GET_CLIENTE_SUMA_PLATILLO,$PARAMS,$PARAMS_TYPES);
	else if($procedencia=="RB" && $cliente==0) $total = $comunication->query(self::GET_USUARIO_SUMA_RESTBAR,$PARAMS,$PARAMS_TYPES);
	else if($procedencia=="RB" && $cliente==1) $total = $comunication->query(self::GET_CLIENTE_SUMA_RESTBAR,$PARAMS,$PARAMS_TYPES);
	
	if ($total->getRecordCount()>0){
			while($total->next()){
				$resultc=$total->getRow();
				$suma=$resultc["suma"];
				}}		
	return $suma;
	
}
//------------------------------------------------------------------------------------------------------------       
}
?>
