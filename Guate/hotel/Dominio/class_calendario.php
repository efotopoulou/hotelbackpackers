<?php 



class calendario {
			
	function __construct($d, $m, $y){
										//--parámetros de configuración del calendario--
		$this->cal_cfg["m_shown"]=4;	//nº de meses que se muestran
		$this->cal_cfg["m_act"]=1;		//nº de meses anteriores al actual
		$this->cal_cfg["d_bef_act"]=10;	//nº de días anteriores al actual
		
		if(!checkdate($m, $d, $y)){
			$d=date("d");
			$m=date("m");
			$y=date("Y");
		}
		$this->cal_cfg["today"]=mktime(0, 0, 0, date("m"), date("d"), date("Y"));	
		$this->cal_cfg["day_act"]=mktime(0, 0, 0, $m, $d, $y);	//dia en el que se centra el calendario
		$this->cal_cfg["day_ini"]=mktime(0, 0, 0, ($m - $this->cal_cfg["m_act"]), 1, $y);
		$this->cal_cfg["day_back"]=mktime(0, 0, 0, ($m - $this->cal_cfg["m_shown"]), $d, $y);
		$this->cal_cfg["day_next"]=mktime(0, 0, 0, ($m + $this->cal_cfg["m_shown"]), $d, $y);
		
		$m=(date("m",$this->cal_cfg['day_ini']) + $this->cal_cfg["m_shown"]);
		$this->cal_cfg["day_end"]=mktime(0, 0, 0, $m, 0, date("Y",$this->cal_cfg['day_ini']));	

		$this->cal_cfg["sum_till_act"]=$this->diff_days($this->cal_cfg['day_ini'],$this->cal_cfg['day_act']);				
		$this->cal_cfg["sum_days"]=$this->diff_days($this->cal_cfg['day_ini'],$this->cal_cfg['day_end'])+1;			
	}
	

	public function get_today(){
		return $this->cal_cfg['today'];
	}
	
	public function get_day_act(){
		return $this->cal_cfg['day_act'];
	}
		
	public function get_day_ini(){
		return $this->cal_cfg['day_ini'];
	}
	
	public function get_day_end(){
		return $this->cal_cfg['day_end'];
	}	
	
	public function get_day_back(){
		return $this->cal_cfg['day_back'];
	}
	
	public function get_day_next(){
		return $this->cal_cfg['day_next'];
	}	
	
	public function get_sum_till_act(){
		return $this->cal_cfg['sum_till_act'];
	}
	
	public function get_days_before_act(){
		return $this->cal_cfg['sum_till_act'] - $this->cal_cfg['d_bef_act'];
	}
	
	public function get_sum_days(){
		return $this->cal_cfg['sum_days'];
	}
	
	public function get_months_shown(){
		return $this->cal_cfg['m_shown'];
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