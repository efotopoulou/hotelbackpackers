<?php

require_once ('Comunication.php');

class Dlog{
	
	const INSERT_LOG = 'INSERT INTO logs VALUES (?,?,?,?,NOW())';
	const INSERT_LOG_GUATE_BD = 'INSERT INTO guate_bd.error values (?,?,NOW())';
	const INSERT_LOG_RECEPCION_BD = 'INSERT INTO recepcion_bd.error values (?,?,NOW())';
	const INSERT_LOG_RESTBAR_BD = 'INSERT INTO restbar_bd.error values (?,?,NOW())';

	public function insert_log($id_usuario, $id_accion, $id_evento){
		$params = array(0, $id_usuario, $id_accion, $id_evento);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT, Comunication::$TINT, Comunication::$TINT, Comunication::$TINT);
		$result = $comunication->update(self::INSERT_LOG,$params,$PARAMS_TYPES);
		return $result;
	}
//Funcion que guarda las excepciones de php no controladas en el hotel  
    public function guardarErrorHotel($texto, $errortype){
        $comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TSTRING,Comunication::$TINT);
		$params = array($texto, $errortype);
		
          try{
			$result = $comunication->update(self::INSERT_LOG_GUATE_BD,$params,$PARAMS_TYPES);
          }catch(Exception $e){
          	echo("ERROR EN LA BASE DE DATOS!!!!!!!!!!");
          }
    } 
//Funcion que guarda las excepciones de php no controladas en el la recepcion  
    public function guardarErrorRecepcion($texto, $errortype){
        $comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TSTRING,Comunication::$TINT);
		$params = array($texto, $errortype);
		
          try{
			$result = $comunication->update(self::INSERT_LOG_RECEPCION_BD,$params,$PARAMS_TYPES);
          }catch(Exception $e){
          	echo("ERROR EN LA BASE DE DATOS!!!!!!!!!!");
          }
    } 
//Funcion que guarda las excepciones de php no controladas en el restaurante  
    public function guardarErrorRestBar($texto, $errortype){
        $comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TSTRING,Comunication::$TINT);
		$params = array($texto, $errortype);
		
          try{
			$result = $comunication->update(self::INSERT_LOG_RESTBAR_BD,$params,$PARAMS_TYPES);
          }catch(Exception $e){
          	echo("ERROR EN LA BASE DE DATOS!!!!!!!!!!");
          }
    } 


}
?>