<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Deventos.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');

class eventos{
	
	
	public static $ID=4;	
		
	//errores Message_box
	public static $OK=1;
	public static $OK_IMP=2;
	public static $ERR_CLI=-1;
	public static $ERR_ALOJ=-2;
	public static $ERR=-3;
	public static $ERR_FECH=-4;
	public static $ERR_DEL_RES=-5;
	
	public static $ERR_CHG_RES_ALOJ=-6;
	public static $ERR_CHG_RES_ALOJ_NODISP=-7;
	
	//tipos de evento para el box del calendario
	public static $RESERVA= 0;
	public static $CHECKIN= 1;
	public static $CHECKOUT= 2;
	public static $CHECK_LATE_RES= 3;
	public static $CHECK_LATE_CHK= 4;
	
	private $event;
	private $id_cliente;
	private $id_aloj;
	private $fec_ini;
	private $fec_fin;
	private $fec_in;
	private $fec_out;
	private $fec_res;
	private $id_res;			
	private $res_noches;
	private $ocup_noches;
	private $imp_pagado;
	private $imp_total;

	private $allow_checkin;
	private $allow_cambio_aloj;
	
		function __construct(){
			//constructor con todos los eventos entre dos fechas
			if (func_num_args()>0){
				$cal_ini_date=func_get_arg(0);
				$cal_end_date=func_get_arg(1);
			
			$datos = new Deventos();
			
			if (func_num_args()>2){	//solo de un alojamiento
				$id_aloj=func_get_arg(2);
				$rs = $datos->get_by_dates_id_aloj ($cal_ini_date, $cal_end_date, $id_aloj);
			}
			else
				$rs = $datos->get_by_dates ($cal_ini_date, $cal_end_date);
			
				$id_habit_ant=-1; $ev_cnt=0; $d2_ant=0;
				while($rs->next()) {
					$id_habit=$rs->getString('Id_aloj');
					
					if($rs->getInt('Id_checkin')){		//checkin hecho
						$d1=strtotime($rs->getDate('fec_in'));
						$id_ev=$rs->getInt('Id_checkin');
						if($rs->getDate('fec_out')){	//checkin + checkout hechos
							$tipo=eventos::$CHECKOUT;
							$d2=strtotime($rs->getDate('fec_out'));		//un dia menos para pintar en el calendario
							$d2=mktime(0, 0, 0, date("m",$d2), (date("d",$d2)-1), date("Y",$d2));
						}
						else{							//solo checkin
													
							$dres_ini=strtotime($rs->getDate('fec_ini'));
							$dcheck_ini=strtotime($rs->getDate('fec_in'));
							if($dcheck_ini>$dres_ini){
								
								if($id_habit==$id_habit_ant){
									$this->event[$id_habit]["ev"][$ev_cnt]["dias_entre"]=$this->diff_days($d2_ant,$dres_ini)-1;
									$ev_cnt++;
								}
								else{ 
									$ev_cnt=0;
									$this->event[$id_habit]["ini_date"]=$dres_ini;	
								}
								$tipo=eventos::$CHECK_LATE_RES;
								$this->event[$id_habit]["ev"][$ev_cnt]=array("id"=>$rs->getInt('Id_res'), "dias"=>$this->diff_days($dres_ini,$dcheck_ini), "dias_entre"=>0, "tipo"=>$tipo);	
								$id_habit_ant=$id_habit;
								$d2_ant=mktime(0, 0, 0, date("m",$d1), date("d",$d1)-1, date("Y",$d1));
								$tipo=eventos::$CHECK_LATE_CHK;	
							}
							else{
								$tipo=eventos::$CHECKIN;
							}
							
							$d2=strtotime($rs->getDate('fec_fin'));
							//$today=mktime(0, 0, 0, date("m"), date("d"), date("Y"));
							//if($d2<$today) 
							//	$d2=$today;
						}
					}
					else{								//solo es reserva
						$d1=strtotime($rs->getDate('fec_ini'));
						$d2=strtotime($rs->getDate('fec_fin'));
						$id_ev=$rs->getInt('Id_res');
						$tipo=eventos::$RESERVA;
					} 
									
					//si es el siguiente ev. de la misma habitacion se calculan los dias entre éste y el anterior
					if($id_habit==$id_habit_ant){
						$this->event[$id_habit]["ev"][$ev_cnt]["dias_entre"]=$this->diff_days($d2_ant,$d1)-1;
						$ev_cnt++;
					}
					else{ // si es el 1º ev de la habitacion se asigna "ini_date", núm. de dias, tipo, id_ev.
						  // "dias_entre" se calcula en la sig. vuelta si hay más eventos en la misma habitacion.
						$ev_cnt=0;
						$this->event[$id_habit]["ini_date"]=$d1;	
					}
					$this->event[$id_habit]["ev"][$ev_cnt]=array("id"=>$id_ev, "dias"=>$this->diff_days($d1,$d2)+1, "dias_entre"=>0, "tipo"=>$tipo);	
					$id_habit_ant=$id_habit;
					$d2_ant=$d2;
				}
			}

		}

		function get_ocupacion($id_ocup){
			$datos = new Deventos();
			$rs = $datos->get_ocup_data($id_ocup);
			
			if ($rs->getRecordCount()>0){
				$rs->next();
				$result=$rs->getRow();
				$this->fec_in=strtotime($rs->getDate('fec_in'));
				$this->fec_out=strtotime($rs->getDate('fec_out'));
				$this->fec_ini=strtotime($rs->getDate('fec_ini'));
				$this->fec_fin=strtotime($rs->getDate('fec_fin'));
				$this->id_aloj=$rs->getInt('Id_aloj');
				$this->id_res=$rs->getInt('Id_res');
				$this->imp_pagado=$rs->getFloat('imp_pagado');
				$today=mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				if($this->fec_out)
					$this->ocup_noches=$this->diff_days($this->fec_in,$this->fec_out);				
				else{
					$this->ocup_noches=$this->diff_days($this->fec_in,$this->fec_fin)+1;
				/*	if($this->fec_fin<$today){
						$this->allow_cambio_aloj=false;
					}
					else{
						$this->allow_cambio_aloj=true;
					} */
				}
						
				$this->res_noches=$this->diff_days($this->fec_ini,$this->fec_fin)+1;
			}
			return $rs->getRecordCount();
		}
			
		function get_reserva($id_res){
			$datos = new Deventos();
			$rs = $datos->get_res_data($id_res);
			
			if ($rs->getRecordCount()>0){
				$rs->next();
				
				$this->fec_in=strtotime($rs->getDate('fec_in'));
				$this->fec_ini=strtotime($rs->getDate('fec_ini'));
				$this->fec_fin=strtotime($rs->getDate('fec_fin'));
				$this->fec_res=strtotime($rs->getDate('fec_res'));
				$this->id_cliente=$rs->getInt('Id_cliente');
				$this->id_aloj=$rs->getInt('Id_aloj');
				$this->res_noches=$this->diff_days($this->fec_ini,$this->fec_fin)+1;
				$this->imp_pagado=$rs->getFloat('imp_pagado');
	
				//????????? mas exatitud mirando la hora?
				$today=mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				if($today>=$this->fec_ini && $today<=$this->fec_fin){
					$this->allow_checkin=true;
				}
				else{
					$this->allow_checkin=false;
				}
			}
			return $rs->getRecordCount();
		}
		
		function make_reserva($res){
			$datos = new Deventos();
			$temp = new temporada();
			
			
			
			$date = explode("/",$res['fec_ini']);
			$res['fec_ini']=mktime(0,0,0,$date[1],$date[0],$date[2]);
			$date = explode("/",$res['fec_fin']);
			$res['fec_fin']=mktime(0,0,0,$date[1],$date[0],$date[2]);
			$date = explode("/",$res['fec_res']);
			$res['fec_res']=mktime(0,0,0,$date[1],$date[0],$date[2]);
			
			$aloj_list = split (",", $res['id_alojs']);
			$this->imp_total=0;
			foreach($aloj_list as $id_aloj){
				if($id_aloj!=0 && $id_aloj!=NULL){
					$rs = $datos->insert_res($id_aloj,$res['fec_ini'],$res['id_cliente'],$res['fec_fin'],$res['fec_res'],$res['imp_pagado']);
					$this->imp_total+=$temp->calculo_precio($id_aloj,$res['fec_ini'],$res['fec_fin']);
				}
			}
			$this->id_res=$rs;
			if($rs>0){
				
				$result=eventos::$OK;
			}
			else{
				$result=eventos::$ERR;
			}
			return $result;			
			
		}

		function update_imp_pagado($imp_pag,$idres){
			$datos = new Deventos();
			$res=$datos->update_imp_pag($imp_pag,$idres);
			
			if($res>0){
				
				$result=eventos::$OK_IMP;
			}
			else{
				$result=eventos::$ERR;
			}
			return $result;
			
		}
			

		function get_ocup_id_aloj($fec_ini, $fec_fin){
			$datos = new Deventos();
			
			$date = explode("/",$fec_ini);
			$fec_ini=mktime(0,0,0,$date[1],$date[0],$date[2]);
			$date = explode("/",$fec_fin);
			$fec_fin=mktime(0,0,0,$date[1],$date[0],$date[2]);		
			
			$rs = $datos->get_ocup_rooms($fec_ini, $fec_fin);
			
			$i=0;
			while ($rs->next()) {
		        $row = $rs->getRow();
		        foreach ($row as $key => $value) {
		       		$result[$i]=$value;
		       		$i++;
		        }
			}
			return $result;
		}
		
		function is_ocup_id_aloj($fec_ini, $fec_fin, $id_aloj){
			$datos = new Deventos();			
			
			$date = explode("/",$fec_ini);
			$fec_ini=mktime(0,0,0,$date[1],$date[0],$date[2]);
			$date = explode("/",$fec_fin);
			$fec_fin=mktime(0,0,0,$date[1],$date[0],$date[2]);	
			
			$rs = $datos->is_ocup_room($fec_ini, $fec_fin, $id_aloj);
			$rs->next();
			
			if($rs->getInt('ocup')>0)
				return true;
			else
				return false;						
		}
		
		function del_reserva($id_res){
			$datos = new Deventos();		
			$result = $datos->del_res($id_res);
			if($result>0)				
				$result=eventos::$OK;
			else
				$result=eventos::$ERR_DEL_RES;
			return $result;
		}
		
		function inc_res_fecini($id_res, $dias){	
			$datos = new Deventos();
			$this->get_reserva($id_res);
			
			$d=mktime(0, 0, 0, date("m",$this->fec_ini), (date("d",$this->fec_ini)-1), date("Y",$this->fec_ini));
			$d=date("d/m/Y", $d);
					
			if($dias<0 && $this->is_ocup_id_aloj($d, $d, $this->id_aloj)==false)
				$result = $datos->inc_fec_ini($id_res, $dias);
			elseif($dias>0 && $this->fec_ini<$this->fec_fin)
				$result = $datos->inc_fec_ini($id_res, $dias);
			
			return $result;
		}
		
		function inc_res_fecfin($id_res, $dias){	
			$datos = new Deventos();
			$this->get_reserva($id_res);
			
			$d=mktime(0, 0, 0, date("m",$this->fec_fin), (date("d",$this->fec_fin)+1), date("Y",$this->fec_fin));
			$d=date("d/m/Y", $d);
			
			if($dias>0 && $this->is_ocup_id_aloj($d, $d, $this->id_aloj)==false)
				$result = $datos->inc_fec_fin($id_res, $dias);			
			elseif($dias<0 &&$this->fec_fin>$this->fec_ini)		
				$result = $datos->inc_fec_fin($id_res, $dias);
			return $result;
		}
		
		function change_res_aloj($id_res, $id_aloj){	
			$datos = new Deventos();					
			$aloj= new alojamiento();
			
			$aloj->get_aloj($id_aloj);

			if($aloj->es_comun($id_aloj))
				$result=eventos::$ERR_CHG_RES_ALOJ;
			elseif($this->is_ocup_id_aloj(date("d/m/Y",$this->fec_ini), date("d/m/Y",$this->fec_fin), $id_aloj))			
				$result=eventos::$ERR_CHG_RES_ALOJ_NODISP;
			else{
					$result = $datos->change_id_aloj($id_res, $id_aloj);
					if($result>0)
						$result=eventos::$OK;
					else
						$result=eventos::$ERR_CHG_RES_ALOJ;
				}
			return $result;
		}	
	
		function inc_ocup_fecfin($id_chek, $dias){	
			$datos = new Deventos();
			$this->get_ocupacion($id_chek);
			
			$d=mktime(0, 0, 0, date("m",$this->fec_fin), (date("d",$this->fec_fin)+1), date("Y",$this->fec_fin));
			$d=date("d/m/Y", $d);
			
			if($dias>0 && $this->is_ocup_id_aloj($d, $d, $this->id_aloj)==false)
				$result = $datos->inc_fec_fin($this->id_res, $dias);			
			elseif($dias<0 &&$this->fec_fin>$this->fec_in)		
				$result = $datos->inc_fec_fin($this->id_res, $dias);
			return $result;
		}
		
		function inc_ocup_fecini($id_chek, $dias){	
			$datos = new Deventos();
			$this->get_ocupacion($id_chek);

			if($dias<0){
				if($this->fec_ini<$this->fec_in)
					$result = $datos->inc_fec_in($id_chek, $dias);
				else{
					$d=mktime(0, 0, 0, date("m",$this->fec_ini), (date("d",$this->fec_ini)-1), date("Y",$this->fec_fin));
					$d=date("d/m/Y", $d);
					if($this->is_ocup_id_aloj($d, $d, $this->id_aloj)==false){
						$result = $datos->inc_fec_ini($this->id_res, $dias);
						if($result>0)
							$result = $datos->inc_fec_in($id_chek, $dias);
					}
				}
			}
			elseif($dias>0 && $this->fec_in<$this->fec_fin)
				$result = $datos->inc_fec_in($id_chek, $dias);
						
			return $result;
		}
		
		function get_res_fecini(){	
			return date("d/"."m/"."Y",$this->fec_ini);
		}	
		
		function get_res_fecfin(){	
			return date("d/"."m/"."Y",$this->fec_fin);
		}	
		
		function get_res_fecres(){	
			return $this->fec_res;
		}	
		
		
		function get_res_id_cliente(){	
			return $this->id_cliente;
		}
		
		function get_res_id(){	
			return $this->id_res;
		}
		
		
		function get_res_id_aloj(){	
			return $this->id_aloj;
		}
		

		function get_res_noches(){	
			return $this->res_noches;
		}	
		
		function get_res_imp_pagado(){	
			return $this->imp_pagado;
		}	

		function get_res_precio(){	
			return $this->imp_total;
		}

		function get_ocup_noches(){	
			return $this->ocup_noches;
		}
				
		function get_res_allow_checkin(){	
			return $this->allow_checkin;
		}	
		
		function get_allow_cambio_aloj(){	
			return $this->allow_cambio_aloj;
		}	
		
		
		//FUNCIONES PARA CREAR EL CALENDARIO
		function get_id_ev($id_habit){
			$a=current($this->event[$id_habit]["ev"]);
			return $a["id"];
		}

		function get_dia_ini($id_habit){
			return $this->event[$id_habit]["ini_date"];
		}
		
		function get_dias($id_habit){
			$a=current($this->event[$id_habit]["ev"]);
			return $a["dias"];
		}
		
		function get_dias_entre($id_habit){
			$a=current($this->event[$id_habit]["ev"]);
			return $a["dias_entre"];
		}
		
		function get_tipo($id_habit){
			$a=current($this->event[$id_habit]["ev"]);
			return $a["tipo"];
		}
		
		function get_count($id_habit){
			return count($this->event[$id_habit]["ev"]);
		}
	
		function movenext($id_habit){
			return next($this->event[$id_habit]["ev"]);
		}
		
		function current($id_habit){
			return current($this->event[$id_habit]["ev"]);
		}
				

		// Will return the number of days between the two dates passed in
		function diff_days($a, $b)
		{
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