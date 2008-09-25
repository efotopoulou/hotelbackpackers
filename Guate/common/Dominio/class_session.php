<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/common/Datos/Dsession.php');

 class session {

	public static $ID_ADMIN=1;
	
	private $perfil;
	private $perfilRest;
	
	private $id_perfil;
	private $id_usuario;
	
	//NOMBRES DE LAS VBLES DE SESION
	const START_TIME ='starTime';
	const LAST_ACCESS = 'lastAccess';


 	public function __construct (){
 		session_start ();
		ini_set("session.gc_maxlifetime", "18000");	//5 horas
		
		$this->id_perfil=$this->getSessionVar('id_perfil');
		$this->id_usuario=$this->getSessionVar('id_usuario');
 	}
 	
 	function __destruct(){}

	function endSession(){
		session_destroy();
	}
 	
 	function isRestoredSession(){
 		return $_SESSION[self::START_TIME] != $_SESSION[self::LAST_ACCESS];
 	}
 	
 	private function getSessionVar ($varName){
		return	$_SESSION[$varName];
 	}

 	private function setSessionVar ($varName, $varValue){
		$_SESSION[$varName]=$varValue;
 	}
 	
 	
 	function get_perfiles(){
 		$datos=new Dsession();
 		
 		$rs=$datos->get_perfil_all();
 		$this->perfil=null;
 		while($rs->next()) {
			$this->perfil[$rs->getInt('Id_perfil')] = $rs->getRow(); //array("nombre"=>$rs->getString('nombre'));	
		}
		return $rs->getRecordCount();
 	}

 	function get_perfiles_rest(){
 		$datos=new Dsession();
 		
 		$rs=$datos->get_perfil_rest_all();
 		$this->perfilRest=null;
 		$i=0;
		$antPerfil=0;
 		while($rs->next()) {
			if ($antPerfil!=$rs->getInt('id_perfil')){
				$this->perfilRest[$rs->getInt('id_perfil')]['id_perfil'] = $rs->getInt('id_perfil');
				$this->perfilRest[$rs->getInt('id_perfil')]['nombre'] = $rs->getString('nombre');
			}
			$i++;
			$antPerfil = $rs->getInt('id_perfil');
			$this->perfilRest[$rs->getInt('id_perfil')][$rs->getString('nombrepagina')] = $rs->getInt('permiso'); //array("nombre"=>$rs->getString('nombre'));	
		}

		return sizeof($this->perfilRest);
 	}

 	
 	function get_paginas_rest(){
 		$datos=new Dsession();
 		
 		$rs=$datos->get_paginas_rest_all();
		$i=0;
 		while($rs->next()) {
 			$result[$i] = $rs->getString('nombre');
 			$i++; 
		}
		return $result;
 	}
 	

 	function get_perfil($id){
 		$datos=new Dsession();
 		
 		$rs=$datos->get_perfil($id);
 		if ($rs->getRecordCount()>0){
			$rs->next();
			$this->perfil[$rs->getInt('Id_perfil')] = array("nombre"=>$rs->getString('nombre'));	
		}
		return $rs->getRecordCount();
 	}
 	
 	function insertar_perfil($data){
		$datos=new Dsession();
		
		$pass=md5($data['perfil_password']);
		$rs = $datos->insert_perfil($data['perfil_nombre'],$pass,$data['perfil_calendario'],$data['perfil_reserva'],$data['perfil_checkin'],$data['perfil_checkout'],$data['perfil_cliente'],$data['perfil_factura'],$data['perfil_ad_menu'],$data['perfil_ad_precios'],$data['perfil_ad_usuarios'],$data['perfil_ad_listados']);
		return $rs;
	}
	
	function modificar_perfil($data){
		$datos=new Dsession();
		
		$pass=md5($data['perfil_password']);
		$rs = $datos->update_perfil($data['perfil_id'], $data['perfil_nombre'],$pass,$data['perfil_calendario'],$data['perfil_reserva'],$data['perfil_checkin'],$data['perfil_checkout'],$data['perfil_cliente'],$data['perfil_factura'],$data['perfil_ad_menu'],$data['perfil_ad_precios'],$data['perfil_ad_usuarios'],$data['perfil_ad_listados']);
		return $rs;
	}
	
	function eliminar_perfil($id_perfil){
		$datos=new Dsession();
		
		$rs = $datos->delete_perfil($id_perfil);
		return $rs;
	}
	
 	function validar_perfil($id, $pass){
 		$datos=new Dsession();

 		$rs=$datos->get_perfil($id);
 		
 		if ($rs->getRecordCount()>0){
			$rs->next();
			$pass_bd=$rs->getString('password');
			
			if(md5($pass)==$pass_bd){	
				$this->setSessionVar('id_perfil', $id);
				return true;
			}
		}
		return false;
 	}
 	
 	
	function calendario_allowed(){
			$a=current($this->perfil);
		return $a['calendario'];		
	}
	function reserva_allowed(){
			$a=current($this->perfil);
		return $a['reserva'];		
	}
 	function checkinres_allowed(){
			$a=current($this->perfil);
		return $a['checkinres'];		
	}
 	function checkout_allowed(){
			$a=current($this->perfil);
		return $a['checkout'];		
	}
	function cliente_allowed(){
			$a=current($this->perfil);
		return $a['cliente'];		
	}
	function factura_allowed(){
			$a=current($this->perfil);
		return $a['factura'];		
	}
	function admenu_allowed(){
			$a=current($this->perfil);
		return $a['admin_menu'];		
	}
	function adprecios_allowed(){
			$a=current($this->perfil);
		return $a['admin_precios'];		
	}
	function adusers_allowed(){
			$a=current($this->perfil);
		return $a['admin_users'];		
	}
	function adlistados_allowed(){
			$a=current($this->perfil);
		return $a['admin_listados'];		
	}
/*	function tpv_allowed(){
			$a=current($this->perfilRest);
		return $a['tpv'];
	}
	function caja_allowed(){
			$a=current($this->perfilRest);
		return $a['gestioncaja'];
	}
	function platillos_allowed(){
			$a=current($this->perfilRest);
		return $a['gestionplatillos'];
	}
	function factura_rest_allowed(){
			$a=current($this->perfilRest);
		return $a['facturas'];
	}
	function ad_backup_allowed(){
			$a=current($this->perfilRest);
		return $a['backup'];
	}
	function ad_estadisticas_allowed(){
			$a=current($this->perfilRest);
		return $a['estadisticas'];
	}
	function historico_cajas_allowed(){
		$a=current($this->perfilRest);
		return $a['historicocaja'];		
	}*/
	function rest_allowed($pagina){
		$a=current($this->perfilRest);
		return $a[$pagina];		
	}
	
	

 	function get_id(){
		return key($this->perfil);
	}

 	function get_id_rest(){
		return key($this->perfilRest);		
	}
	
	function get_nombre($id=0){
		if($id)
			$a=$this->perfil[$id];
		else
			$a=current($this->perfil);
		return $a['nombre'];		
	}
	function get_nombre_rest(){
		$a=current($this->perfilRest);
		return $a['nombre'];		
	}

 	
 	function es_admin(){
		return ( key($this->perfil) == session::$ID_ADMIN );		
	}

 	function es_admin_rest(){
		return ( key($this->perfilRest) == session::$ID_ADMIN );		
	}
	
 	function get_count(){
		return count($this->perfil);
	}
 	function get_count_rest(){
		return sizeof($this->perfilRest);
	}
	
	function movenext(){
		return next($this->perfil);		
	}	
	
	function movenext_rest(){
		return next($this->perfilRest);
	}	
	
	function movefirst(){
		reset($this->perfil);
	}	
	
	function current(){
		return current($this->perfil);
	}
	
	
	function get_id_perfil(){
		return $this->id_perfil;
	}
	
	function get_id_usuario(){
		return $this->id_usuario;
	}
	
	function set_id_usuario($id){
		$this->setSessionVar('id_usuario', $id);
		$this->id_usuario=$id;
	}

 	function is_allowed($page){
 	 	$datos=new Dsession();

		if($page=='slgrid' || $page=='message_box'|| $page=='print_factura')
			return true; 			
 		
 		$rs=$datos->get_perfil($this->id_perfil);
 		if ($rs->getRecordCount()>0){
			$rs->next();
 			$row=$rs->getRow();
 			//si es una p�gina v�lida y se est� autorizado
 			if( (array_key_exists($page, $row) && $row[$page]))
 				return true;
 		}
 		return false;
 	}
 	function is_allowed_rest(){
 		$datos=new Dsession();
 		
 		$rs=$datos->get_perfil_rest($this->id_perfil);
 		$aux=null;
 		while($rs->next()) {
			$aux[$rs->getString('nombrepagina')] = $rs->getInt('permiso');
		}
		return $aux;
 	}
 }
?>