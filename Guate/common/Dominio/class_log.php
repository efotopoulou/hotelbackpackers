<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Datos/Dlog.php');

class log{
	
	private $err_file;
	public static $INS_RES=1;
	public static $MOD_RES=2;
	public static $CANC_RES=3;
	public static $INS_CHECKIN=4;
	public static $MOD_CHECKIN=5;
	public static $IMP_PAG=6;
	public static $INS_CHECKOUT=7;
	public static $ADD_CLIENTE=8;
	public static $MOD_CLIENTE=9;
	public static $DEL_CLIENTE=10;
	public static $CERRAR_FRA=11;
	public static $USR_LOGIN=12;
	
	
	function __construct(){
		$this->err_file=$_SERVER['DOCUMENT_ROOT'] . '/hotel/log/error.log';	
	}
	
	function insertar_log($id_usuario, $id_accion, $id_evento){
		$datos=new Dlog();
		
		$datos->insert_log($id_usuario, $id_accion, $id_evento);
	}
	
//	function insertar_error($msg){
	//	error_log(date("d-m-Y H:i:s ").$msg."\r\n", 3, $this->err_file);	
//	}
//Funcion que guarda las excepciones de php no controladas en el hotel  
    public function guardarErrorHotel($texto, $errortype){
    	error_log(date("Y-m-d,H:i:s").":".$texto."Errortype:".$errortype."\r\n", 3, $this->err_file);
    	$datos=new Dlog();
    	$datos->guardarErrorHotel($texto, $errortype);
    } 
//Funcion que guarda las excepciones de php no controladas en el la recepcion  
    public function guardarErrorRecepcion($texto, $errortype){
		error_log(date("Y-m-d,H:i:s").":".$texto."Errortype:".$errortype."\r\n", 3, $this->err_file);
    	$datos=new Dlog();
    	$datos->guardarErrorRecepcion($texto, $errortype);
    } 
//Funcion que guarda las excepciones de php no controladas en el hotel  
    public function guardarErrorRestBar($texto, $errortype){
    	error_log(date("Y-m-d,H:i:s").":".$texto."Errortype:".$errortype."\r\n", 3, $this->err_file);
    	$datos=new Dlog();
    	$datos->guardarErrorRestBar($texto, $errortype);
    } 
}