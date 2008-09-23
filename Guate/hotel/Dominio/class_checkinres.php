<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dcheckres.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');


class checkinres{
	
		//array con los datos de un checkin
		private $chpr;
		private $last_idfra=0;
	
		public static $ID=1;	
		public static $IDOUT=2;	
		
		//errores Message_box
		public static $OK=1;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR_FRA=-4;
		public static $ERR=-3;
		
		
		//carga en $chpr los datos de un checkin segun su id
		function get_checkin($id_checkin){
			$dtcl = new Dcheckres();
			$rs = $dtcl->get_check_by_id($id_checkin);
			
			$this->chpr=null;
			if ($rs->getRecordCount()>0){
			$rs->next();
				$resultat=$rs->getRow();
				$this->chpr[0] = $resultat;
			}
			return $rs->getRecordCount();
		}
			
		
	
			
		//inserta los datos en tabla checkin y ocupantes		
		function make_checkinres($checkin){
			$datos = new Dcheckres();
			$rs=$datos->get_check_by_res($checkin['id_res']);
			
			if($rs->getRecordCount()==0){
			$fec_check = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				if($checkin['id_res']>0){
					$rs = $datos->insert_checkres($checkin['id_res'],$fec_check,$checkin['importe_pagado'],NULL,0,0);
					$this->chpr[0]["Id_checkin"]=$rs;	
					$grupo = split (",", $checkin['grupoclient']);
					foreach($grupo as $value){
						if($value!=0 & $value!=NULL){
						$res = $datos->insert_ocup($rs,$value);
						}
					}
					$result=checkinres::$OK;
				}
				else
					$result=checkinres::$ERR_RES;
				}
			else{
				$result=checkinres::$ERR_CHECK;
			}
			return $result;
		}
		
		
		//inserta los datos del checkout en la tabla checkin
		function make_checkout($checkout){
			$ev = new eventos();		
			$datos = new Dcheckres();
			$fra = new factura();
						
			$pagado=$checkout['acuent'];
					
			$ev->get_ocupacion($checkout['id_checkin']);
			$idres =$ev->get_res_id();
			
			$fecfinres = $ev->get_res_fecfin();	
			$fecfinres = split("/",$fecfinres);
			
			$fecfinres = mktime(0,0,0,(int)$fecfinres[1],(int)$fecfinres[0],(int)$fecfinres[2]);
			$hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
			
			if ($fecfinres>=$hoy){				
				$dias = $ev->diff_days($fecfinres,$hoy);
				$inc=$ev->inc_res_fecfin($idres, -$dias);
			}
			
			$check=$checkout['id_checkin'];
			$nombre_completo=$checkout['nombre']." ".$checkout['apellido1']." ".$checkout['apellido2'];
																
			$fechafin=$checkout['fec_fin'];
			$fechafin=split("/",$fechafin);
			$fechafin = mktime(0, 0, 0, (int)$fechafin[1], (int)$fechafin[0],  (int)$fechafin[2]);
			
			$res = $datos->insert_checkout($fechafin,$checkout['total'],$checkout['id_checkin']);
						
			if($res==1){
				$result=checkinres::$OK;
			}
			else{
				$result=checkinres::$ERR_CHECK;
			}
			return $result;
		}
		
		function set_idfra($idFra,$idCheck){
			$datos = new Dcheckres();
			$res=$datos->insert_idfra($idFra,$idCheck);
			return $res;
		}
		
		function get_idfra(){
			$a=current($this->chpr);
			return $a["Id_fra"];	
			
		}
		
		function get_last_idfra(){
			return $this->last_idfra;	
			
		}
		
		
		// CHECKINS PREVISTOS
		function get_checkprev(){
			
			$checkpre = new Dcheckres();
			$result = $checkpre->get_checkprev(date("Y-m-d"));
			$i=0;
			$this->chpr=NULL;
			while ($result->next()){
				$this->chpr[$i]=$result->getRow();
				$i++;
			}
		}			
		
		// CHECKINS SIN FACTURAR
		function get_checksinfra(){
			$datos = new Dcheckres();
			$result = $datos->get_checksinfra();
			$i=0;
			$this->chpr=NULL;
			while ($result->next()){
				$this->chpr[$i]=$result->getRow();
				$i++;
			}
		}	
		
		//CHECKOUTS PREVISTOS
		function get_checkoutprev(){
			
			$checkoutpre = new Dcheckres();
			$result = $checkoutpre->get_checkoutprev(mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
			$i=0;
			$this->chpr=NULL;
			while ($result->next()){
				$this->chpr[$i]=$result->getRow();
				$i++;
			}
		}	
		
		//LISTA DE ID'S DE LOS CLIENTES QUE PERTENECEN A UN CHECKIN
		function get_ocupantes($idcheckin){
			
			$ocup = new Dcheckres();
			$result = $ocup->get_ocupantes($idcheckin);
			$i=0;
			$resultat=array();
			while($result->next()){
				$resultat[$i]=$result->getInt('Id_cliente');
				$i++;
			}
			return $resultat;
		}
		
		
		
		//CAMBIO DE HABITACION
		function change_check_aloj($id_check, $id_aloj, $idres){	
			
			$dat = new Dcheckres();
			$datos = new Deventos();					
			$aloj= new alojamiento();
			$fra = new factura();
			$temp = new temporada();
			$ev = new eventos();
			$reserva = $ev->get_reserva($idres);
			$fecfinres = $ev->get_res_fecfin();
			$idcliente = $ev->get_res_id_cliente();
			$idalojant=$ev->get_res_id_aloj();
			
			
			$aloj->get_aloj($idalojant);
			$descripcion=$aloj->get_nombre()." . ".$aloj->get_tipo();
			
			$this->get_checkin($id_check);
			$idfra=$this->get_idfra();
			$pagado=$this->get_imp_pagado();
			$fecinicheck = $this->get_fecin();
			$fecinicheck = split("-",$fecinicheck);
			$fecinicheck = mktime(0,0,0,(int)$fecinicheck[1],(int)$fecinicheck[2],(int)$fecinicheck[0]);
			
			$arrayres=array("fec_fin"=> $fecfinres,"id_cliente" => $idcliente,"id_alojs" => $id_aloj.',', "fec_ini"=>date("d/m/Y"), "fec_res"=>date("d/m/Y"));
			$datoscheck = new Dcheckres();
			$hoy=date("d/m/Y");
			$fec_check = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$fec_out = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
			
			if($fecinicheck>$fec_out){
				$noches=0;
			}
			else{
				$noches=$temp->diff_days($fecinicheck, $fec_out)+1;
			}
			
			$preu = $temp->calculo_precio($idalojant,$fecinicheck,$fec_out);
			
			//ocupantes del checkin
			$ocup = $this->get_ocupantes($id_check);
			$num_ocup = count($ocup);

			$aloj->get_aloj($id_aloj);
			
			$arraycheckout=array("id_checkin"=>$id_check,"fec_fin"=>date("d/m/Y"),"id_fra"=>$idfra,"total"=>0,"descuento"=>0,"recargo"=>0,"impuesto"=>0,"id_cliente"=>$idcliente,"fec_fra"=>date("d/m/Y"),"cantidad"=>$noches,"descripcion"=>$descripcion,"valor"=>$preu,"idres"=>$idres,"acuent"=>$pagado);
			
			if($aloj->es_comun($id_aloj)==false && $ev->is_ocup_id_aloj($hoy, $fecfinres, $id_aloj)==false){
							
				$this->make_checkout($arraycheckout);
			
				
				$res=$ev->make_reserva($arrayres);
				$idres_nueva=$ev->get_res_id();
				
				//cambio del checkin
				$newcheck =$datoscheck->insert_checkres($idres_nueva,$fec_check,0,NULL,0,$this->last_idfra);
						//cambio de los ocupantes
						for($i=0; $i<$num_ocup; $i++){
							$resocup = $datoscheck->insert_ocup($newcheck,$ocup[$i]);
						}
			}
			return $idres_nueva;
		}
		
		function modif_imp_pagado($importe,$idcheckin){
			
			$data = new Dcheckres();
			$res=$data->modif_imp_pag($importe,$idcheckin);
			return $res;
			
		}
			
		
		
		//LISTA DE GETS
		
		function get_count(){
			return count($this->chpr);
		}
	
		function movenext(){
			return next($this->chpr);		
		}	
	
		function movefirst(){
			reset($this->chpr);
		}	
	
		function current(){
			return current($this->chpr);
		}
		
		function get_idres(){
			$a=current($this->chpr);
			return $a["Id_res"];		
		}

		function get_idcheckin(){
			$a=current($this->chpr);
			return $a["Id_checkin"];	
		}	

		function get_idcli(){
			$a=current($this->chpr);
			return $a["Id_cliente"];		
		}
		
		function get_idaloj(){
			$a=current($this->chpr);
			return $a["Id_aloj"];
		}

		function get_fecin(){
			$a=current($this->chpr);
			return $a["fec_in"];
		}
		
		function get_fecout(){
			$a=current($this->chpr);
			return $a["fec_out"];
		}
				
		function get_fecfin(){
			$a=current($this->chpr);
			return $a["fec_fin"];
		}
		
		function get_fecin_date(){
			$a=current($this->chpr);
			$d = split("-",$a["fec_in"]);
			$d = mktime(0,0,0,(int)$d[1],(int)$d[2],(int)$d[0]);
			return $d;
		}
		
		function get_fecout_date(){
			$a=current($this->chpr);
			$fecout=$a["fec_out"];
			if(strlen($fecout)>0){
				$d = split("-",$fecout);
				$d = mktime(0,0,0,(int)$d[1],(int)$d[2],(int)$d[0]);
			}
			else
				$d=null;
			return $d;
		}
				
		function get_fecfin_date(){
			$a=current($this->chpr);
			$d = split("-",$a["fec_fin"]);
			$d = mktime(0,0,0,(int)$d[1],(int)$d[2],(int)$d[0]);
			return $d;
		}
		
		function get_noches(){
			$fecin=$this->get_fecin_date();
			$fecfin=$this->get_fecfin_date();
			$fecout=$this->get_fecout_date();
			
			if($fecout==null)	//checkin
					$noches=$this->diff_days($fecin,$fecfin)+1;
			else				//checkout hecho
					$noches=$this->diff_days($fecin,$fecout);
			return $noches;
		}
		
		function get_imp_pagado(){
			$a=current($this->chpr);
			return $a["importe_pagado"];
		}
		
		function get_res_imp_pagado(){
			$a=current($this->chpr);
			return $a["imp_pagado"];
		}


		public function diff_days($a, $b){
	    // First we need to break these dates into their constituent parts:
	    $gd_a = getdate( $a );
	    $gd_b = getdate( $b );
	
	    // Now recreate these timestamps, based upon noon on each day
	    // The specific time doesn't matter but it must be the same each day
	    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
	    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
	
	    // Subtract these two numbers and divide by the number of seconds in a
	    //  day. Round the result since crossing over a daylight savings time
	    //  barrier will cause this time to be off by an hour or two.
	    return round( abs( $a_new - $b_new ) / 86400 );
		}
			
}
	
?>