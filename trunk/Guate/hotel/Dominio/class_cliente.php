<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dcliente.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dpais.php');

class cliente{
		private $Id_cliente;
		private $pasaporte;
		private $nombre;
		private $apellido1;
		private $apellido2;
		private $direccion;
		private $poblacion;
		private $Id_pais;
		private $nom_pais;
		private $telefono1;
		private $telefono2;
		private $email;
		private $observaciones;
		
		//error Message_Box
		public static $ID=3;
		
		public static $OK=1;
		public static $ELIM=2;
		public static $MODIF=3;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR=-3;
		
	
		function getIdCliente()
		{
			return $this->Id_cliente;
		}

		function getPasaporte()
		{
			return $this->pasaporte;
		}

		function getNombre()
		{
			return $this->nombre;
		}
		
		function getApellido1()
		{
			return $this->apellido1;
		}

		function getApellido2()
		{
			return $this->apellido2;
		}

		function getDirecion()
		{
			return $this->direccion;
		}
		
		function getPoblacion()
		{
			return $this->poblacion;
		}

		function getIdPais()
		{
			return $this->Id_pais;
		}
		
		function getPais()
		{
			return $this->nom_pais;
		}
		
		function getTelefono1()
		{
			return $this->telefono1;
		}
		
		function getTelefono2()
		{
			return $this->telefono2;
		}
		
		function getEmail()
		{
			return $this->email;
		}

		function getObservaciones()
		{
			return $this->observaciones;
		}
		

		function alta_cliente($infocl){
			
			$pais = new Dpais();
			$nompais = $infocl['cli_data_pais'];
			$res= $pais->get_id_pais($nompais);
			if($res->getRecordCount()==1){
			$res->next();
			$id_pais = $res->getInt('Id_pais');
			}
			
			$altcl = new Dcliente();
			$res=$altcl->insert_client($infocl['cli_data_pasaporte'],ucfirst(strtolower($infocl['cli_data_nombre'])),ucfirst(strtolower($infocl['cli_data_apellido1'])),ucfirst(strtolower($infocl['cli_data_apellido2'])),$infocl['cli_data_direc'],$id_pais,$infocl['cli_data_tel1'],$infocl['cli_data_tel2'],$infocl['cli_data_mail'],$infocl['cli_data_observ'],$infocl['cli_data_pob']);
			$this->Id_cliente=$res;
			
			if ($res>0){
				$result=cliente::$OK;
			}
			else{
				$result=cliente::$ERR;
			}
			return $result;
		}
		
			
		function modif_cliente($infocl){
			$pais = new Dpais();
			$nompais = $infocl['cli_data_pais'];
			$res= $pais->get_id_pais($nompais);
			if($res->getRecordCount()==1){
			$res->next();
			$id_pais = $res->getInt('Id_pais');
			}
			
			$datos = new Dcliente();
			$res = $datos->modificar_client($infocl['cli_data_pasaporte'],ucfirst(strtolower($infocl['cli_data_nombre'])),ucfirst(strtolower($infocl['cli_data_apellido1'])),ucfirst(strtolower($infocl['cli_data_apellido2'])),$infocl['cli_data_direc'],$id_pais,$infocl['cli_data_tel1'],$infocl['cli_data_tel2'],$infocl['cli_data_mail'],$infocl['cli_data_observ'],$infocl['cli_data_pob'],$infocl['cli_data_id']);
			
			if ($res>0){
				$result=cliente::$MODIF;
			}
			else{
				$result=cliente::$ERR;
			}
			return $result;	
		}
		
		function eliminar_cliente($idcli){
			$datos = new Dcliente();
			$res= $datos->eliminar_client($idcli);
			if ($res>0){
				$result=cliente::$ELIM;
			}
			else{
				$result=cliente::$ERR;
			}
			return $result;	
		}
			
			
		function get_cliente($id_client){
			$dtcl = new Dcliente();
			$rs = $dtcl->get_dat_cliente($id_client);
			
			if ($rs->getRecordCount()>0){
				$rs->next();
				$result=$rs->getRow();
				
				foreach($result as $clau => $valor){
					$this->$clau = $result[$clau];
				}
																		
			}else{
				$result=null;
			}
			return $result;
		}

}
?>