<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dtemporada.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_precios.php');

class temporada{
		private $Id_aloj;
		private $precio;
		private $temporada;
		
		public static $ID=7;
		
		public static $OK=1;
		public static $ERR=-1;
			
		
		function __construct(){
			$dtemp = new Dtemporada();
			$rs = $dtemp->get_tempo();
			while($rs->next()) {
			$this->temporada[$rs->getInt('Id_temp')] = array("nombre"=>$rs->getString('nombre_temp'), "fecini"=>strtotime($rs->getDate('fecha_ini')), "fecfin"=>strtotime($rs->getDate('fecha_fin')));	
			}
			return $rs->getRecordCount();
		}
		
		
		function get_id_temp(){
			return key($this->temporada);		
		}
		
		function getnombretemp($id_temp=0){
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return $a["nombre"];	
		}
		
		function get_d_ini($id_temp=0){
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return date("d",$a["fecini"]);	
		}
		
		function get_m_ini($id_temp=0){
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return date("m",$a["fecini"]);	
		}
		
		function get_d_fin($id_temp=0){
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return date("d",$a["fecfin"]);	
		}
		
		function get_m_fin($id_temp=0){
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return date("m",$a["fecfin"]);	
		}
		
		function get_id_tipo($id_temp=0)
		{
			if($id_temp)
				$a=$this->temporada[$id_temp];
			else
				$a=current($this->temporada);
			return $a["id_tipo"];
		}
		
		function getfecini($idtempo)
		{
			$a=$this->temporada[$idtempo];
			return $a["fecini"];
		}

		function getfecfin($idtempo)
		{
			$a=$this->temporada[$idtempo];
			return $a["fecfin"];
		}
				
		function getIdaloj()
		{
			return $this->Id_aloj;
		}
		
		
		function getprecio()
		{
			return $this->precio;
		}
		
		function get_count(){
			return count($this->temporada);
		}
		
		function movenext(){
			return next($this->temporada);		
		}	
		
		function movefirst(){
			reset($this->temporada);
		}

		function insertar_tempo($data){
			$dtemp = new Dtemporada();
			$data['m_ini']=(int)$data['m_ini'];
			$data['m_fin']=(int)$data['m_fin'];
			$data['d_ini']=(int)$data['d_ini'];
			$data['d_fin']=(int)$data['d_fin'];
			if(checkdate($data['m_ini'], $data['d_ini'], date('Y')) && checkdate($data['m_fin'], $data['d_fin'], date('Y'))){		
				$fec_ini=mktime(0,0,0,$data['m_ini'],$data['d_ini'], date('Y'));
				$fec_fin=mktime(0,0,0,$data['m_fin'],$data['d_fin'], date('Y'));
			
				$rs = $dtemp->insert_tempo($data['nombre'], $fec_ini, $fec_fin);
			}
			return $rs;
		}

		function modificar_tempo($data){
			$dtemp = new Dtemporada();
			$data['m_ini']=(int)$data['m_ini'];
			$data['m_fin']=(int)$data['m_fin'];
			$data['d_ini']=(int)$data['d_ini'];
			$data['d_fin']=(int)$data['d_fin'];			
			if(checkdate($data['m_ini'], $data['d_ini'], date('Y')) && checkdate($data['m_fin'], $data['d_fin'], date('Y'))){			
				$fec_ini=mktime(0,0,0,$data['m_ini'],$data['d_ini'], date('Y'));
				$fec_fin=mktime(0,0,0,$data['m_fin'],$data['d_fin'], date('Y'));
			
				$rs = $dtemp->update_tempo($data['id_tempo'], $data['nombre'], $fec_ini, $fec_fin);
			}
			return $rs;
		}
		
		function eliminar_tempo($id_tempo){
			$dtemp = new Dtemporada();
			
			$rs = $dtemp->delete_tempo($id_tempo);
			return $rs;
		}
		
		function calculo_precio($idaloj,$fecini,$fecfin){
			
			$aloj=new alojamiento();
			$aloj->get_aloj($idaloj);
			
			if($fecini>$fecfin){
				$dias=0;
			}
			else{
				$dias=$this->diff_days($fecini,$fecfin)+1;
			}
		
			$temp1 = $this->get_tempo_fec($fecini);
			$temp2 = $this->get_tempo_fec($fecfin);
		
			$tempo = new Dtemporada();
			
			if($aloj->es_cama()){
				$idaloj=$aloj->get_id_parent();
			}
			
			if ($temp1==$temp2){
				if($temp1>0)
					$tipo=precios::$TIPO_ALTO;
				else
					$tipo=precios::$TIPO_NORMAL;
				$rs=$tempo->get_preu($idaloj,$tipo);
				if($rs->getRecordCount()>0){
					$rs->next();
					$preu=$rs->getFloat('precio');
					$total=$dias*$preu;
				}
			}
			
			else{
				if($temp1>0){
					$fin= $this->getfecfin($temp1);
					$dias_altos= $this->diff_days($fecini,$fin)+1;
					$dias_norm= $this->diff_days($fin,$fecfin);
				}
				else{
					$ini= $this->getfecini($temp2);
					$dias_norm= $this->diff_days($fecini,$ini);
					$dias_altos= $this->diff_days($ini,$fecfin)+1;
					
				}		
				// echo "d_norm:".$dias_norm.", d_altos:".$dias_altos;		//??????????		
				$rs=$tempo->get_preu($idaloj,precios::$TIPO_ALTO);
				if($rs->getRecordCount()>0){
					$rs->next();
					$preu1=$rs->getFloat('precio');
					$preu1=$dias_altos*$preu1;
				}
								
				$rs=$tempo->get_preu($idaloj,precios::$TIPO_NORMAL);
				if($rs->getRecordCount()>0){
					$rs->next();
					$preu2=$rs->getFloat('precio');
					$preu2=$dias_norm*$preu2;
				}
				$total=$preu1+$preu2;
				
			}		
			return $total;
			
		}
		
		function get_tempo_fec($fecha){
			$any = date("Y",$fecha);
			$id_tempo=0;
						
			if(isset($this->temporada))
			foreach($this->temporada as $key=>$value){
				if(date("Y",$value["fecini"])!=date("Y",$value["fecfin"])){
						$mes_ini = date("m",$value["fecini"]);
						$mes_fecha = date("m",$fecha);
						
						if($mes_fecha>= $mes_ini && $mes_fecha<=12){
							$fec_fin = mktime( 0, 0, 0, date("m",$value["fecfin"]),date("d",$value["fecfin"]), $any +1 );
							$fec_ini = mktime( 0, 0, 0, date("m",$value["fecini"]),date("d",$value["fecini"]), $any );
				
						}
						else{
							$fec_fin = mktime( 0, 0, 0, date("m",$value["fecfin"]),date("d",$value["fecfin"]), $any);
							$fec_ini = mktime( 0, 0, 0, date("m",$value["fecini"]),date("d",$value["fecini"]), $any -1);
				
						}
					}
					else{
					$fec_ini = mktime( 0, 0, 0, date("m",$value["fecini"]),date("d",$value["fecini"]), $any);
					$fec_fin = mktime( 0, 0, 0, date("m",$value["fecfin"]),date("d",$value["fecfin"]), $any);
					}
					
					if($fecha>=$fec_ini && $fecha<=$fec_fin){
						$id_tempo=$key;
					}
					$this->temporada[$key]["fecini"]=$fec_ini;
					$this->temporada[$key]["fecfin"]=$fec_fin;
			}
			return $id_tempo;
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