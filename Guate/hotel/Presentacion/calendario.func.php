<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_precios.php');
	
	function calc_scrollLeft($tipo, $cal){
		if($tipo==1){
			return 10000;
		}
		else if($tipo==2){
			return 0;
		}
		else
			return $cal->get_days_before_act();
	}
	
/*-------------------------------------- BOTTOM ---------------------------------------*/

	function genera_bottom_hiddens($cal, $tipo){
		
		if($tipo==1){
			$d=date("d",$cal->get_day_back());
			$m=date("m",$cal->get_day_back());
			$y=date("Y",$cal->get_day_back());
		}	
		else{
			$d=date("d",$cal->get_day_next());
			$m=date("m",$cal->get_day_next());
			$y=date("Y",$cal->get_day_next());
		}
		echo '<input type="hidden" name="d" value="'.$d.'"/>';
		echo '<input type="hidden" name="m" value="'.$m.'"/>';
		echo '<input type="hidden" name="y" value="'.$y.'"/>';
		echo '<input type="hidden" name="t" value="'.$tipo.'"/>';
	}
	
/*-------------------------------------- TOP ---------------------------------------*/

	function genera_top($cal){
		$str_d_num='';
		print '<table class="t_general" align="center"><tr class="t_row_header">';
		
		$m_ini=date("m",$cal->get_day_ini());
		$y=date("Y",$cal->get_day_ini());
		$d_mes=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		for($i=0;$i<$cal->get_months_shown();$i++){
			$mes=(($m_ini+$i-1)%12)+1;
			$m_days=cal_days_in_month(CAL_GREGORIAN, $mes, $y);
			print '<td colspan="'.$m_days.'" class="t_col">'.$d_mes[$mes-1].'</td>';
			$str_d_num=$str_d_num.genera_dias_num($m_days);
			if($m_ini+$i>12) $y++;
		}
		print '</tr><tr class="t_row_header">';
		genera_dias_sem($cal->get_day_ini(), $cal->get_sum_till_act(), $cal->get_sum_days());
		print '</tr><tr class="t_row_header">'.$str_d_num.'</tr></table>';
	}
	
	function genera_dias_sem($day_ini, $cant1, $cant2){
		
		$d_sem=array("Do","Lu","Ma","Mi","Ju","Vi","S&aacute;");	
		$day_suposed_act=mktime(0, 0, 0, date("m",$day_ini), date("d",$day_ini)+$cant1, date("Y",$day_ini));
				
		$desplz=date('w',$day_ini);
		$cant1+=$desplz;
		//0(+desplazamiento) hasta dia anterior al actual(+desplazamiento)
		for($a=0+$desplz;$a<$cant1;$a++){
			echo '<td class="t_col">'.$d_sem[$a%7].'</td>';
		}
/*		//si se muestra el dia actual
		if(date("d/"."m/"."Y",$day_suposed_act)==date("d/"."m/"."Y",time())){
			$add_style='style="color:white;background-color:#444444"';
		}
		else{
			$add_style='';
		}*/
		
		echo '<td class="t_col">'.$d_sem[$a%7].'</td>';	
		$a++; $cant2+=$desplz;
		//dia posterior al actual hasta total de dias(+desplazamiento)
		for(;$a<$cant2;$a++){
			echo '<td class="t_col">'.$d_sem[$a%7].'</td>';
		}
	}	
	
	function genera_dias_num($cant){
		$cad='';
		for($a=1;$a<=$cant;$a++)
				$cad=$cad.'<td class="t_col"><div style="width:23px">'.$a.'</div></td>';
		return $cad;
	}	

/*-------------------------------------- LEFT ---------------------------------------*/

	function genera_left($habit){
		$temp=new temporada();	
		$precio=new precios();
		$id_temp=$temp->get_tempo_fec(mktime(0,0,0,date("m"),date("d"),date("Y")));
		if($id_temp>0)
			$precio->get_precios(precios::$TIPO_ALTO);
		else
			$precio->get_precios(precios::$TIPO_NORMAL);
		
		echo '<table class="t_general">';
		if($habit->get_count())
		do{
			if($habit->es_comun()){	//es una habitacion con literas
				$ev_str='onClick="showLiteras('.$habit->get_id().')"';
				$container_str='<tr id="containerLeft_'.$habit->get_id().'" style="display:none"><td id="containerLeftTD_'.$habit->get_id().'" class="t_container"></td></tr>';
				$class_str='hab_literas';
			}
			else{
				$ev_str='';
				$container_str='';
				$class_str='';
			}
			$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
			
			$title='tipo: '.$habit->get_tipo().', camas: '.$camas.', precio:'.$precio->get_precio($habit->get_id())."Q";
			echo '<tr class="t_row"><td id="alojLeft_'.$habit->get_id().'" '.$ev_str.' class="t_col_habit '.$class_str.'" style="background-color:'.$habit->get_color().'" title="'.$title.'">'.$habit->get_nombre().'</td></tr>';
			echo $container_str;

		}while($habit->movenext());
		echo '</table>';
	}  
		
/*-------------------------------------- MID ---------------------------------------*/	

	function genera_mid($habit, $ev, $cal){
		echo '<table class="t_general" align="center" id="tableMid">';
  		$habit->movefirst();
		if($habit->get_count())
		do{
			if($habit->es_comun()){	//es una habitacion con literas
				echo genera_row($ev, $cal, $habit->get_id(), false);
				echo '<tr id="containerMid_'.$habit->get_id().'" style="display:none"><td id="containerMidTD_'.$habit->get_id().'" class="t_container" colspan="'.$cal->get_sum_days().'"></td></tr>';
			}
			else{
				echo genera_row($ev, $cal, $habit->get_id(), true);
			}			
		}while($habit->movenext());
		echo '</table>';
	}

	function genera_row($ev, $cal, $idHabit, $clickable, $onlyInner=false){
		$d_total=$cal->get_sum_days();	
		
		$finde_cont=date('w',$cal->get_day_ini());
		
		if($onlyInner)
			$html='';
		else
			$html= '<tr id="trMid_'.$idHabit.'" class="t_row" onmouseover="this.className=\'t_row over\';" onmouseout="this.className=\'t_row\';">';
		
		if($ev->get_count($idHabit)){
			$d_left=$d_total;		
			
			$d=$cal->diff_days($cal->get_day_ini(),$ev->get_dia_ini($idHabit));			
			if($ev->get_dia_ini($idHabit) >= $cal->get_day_ini()){
				$html.=genera_cells($d, $finde_cont, $idHabit, $d_total-$d_left);
				$d_left-=$d;
				$d=$ev->get_dias($idHabit);
				$extremos="i";
			}
			else{
				$d=$ev->get_dias($idHabit) - $d;		
			}
			if($d>$d_left)
				$d=$d_left;
			else
				$extremos.="f";
			$html.=genera_ev($d,$ev->get_tipo($idHabit), $ev->get_id_ev($idHabit), $extremos, $finde_cont);
			$d_left-=$d;	
			if($d_left>0){
				$d=$ev->get_dias_entre($idHabit);
				$html.=genera_cells($d, $finde_cont, $idHabit, $d_total-$d_left);
				$d_left-=$d;
				while($ev->movenext($idHabit)){
					$d=$ev->get_dias($idHabit);
					if($d>$d_left){
						$d=$d_left;
						$extremos="i";
					}
					else
						$extremos="if";
					$html.=genera_ev($d, $ev->get_tipo($idHabit), $ev->get_id_ev($idHabit), $extremos, $finde_cont);
					$d_left-=$d;
					
					if($d_left>0){
						$d=$ev->get_dias_entre($idHabit);
						$html.=genera_cells($d, $finde_cont, $idHabit, $d_total-$d_left);
						$d_left-=$d;
					}
				}
				$html.=genera_cells($d_left, $finde_cont, $idHabit, $d_total-$d_left);
			}			
		}
			
		else
			$html.=genera_cells($d_total,$finde_cont, $idHabit, 0, $clickable);
		
		if(!$onlyInner)
			$html.='</tr>';
		
		return $html;
	}
	
	function genera_ev($dias, $tipo, $id, $extremos, &$finde){
		if ($extremos=="i" or $extremos=="if" and $tipo!=eventos::$CHECK_LATE_CHK)
			$ini='style="background-position:left;background-repeat:no-repeat;background-image:url(/hotel/img/ev_ini.gif)"';
		if ($extremos=="f" or $extremos=="if" and $tipo!=eventos::$CHECK_LATE_RES)
			$fin='style="background-position:right;background-repeat:no-repeat;background-image:url(/hotel/img/ev_fin.gif)"';
		
		$ancho='width:'.(23*$dias).'px;';

		$html='<td id="ev'.$id.'" class="t_col" colspan="'.$dias.'" style="'.$ancho.' margin:0px;padding:0px;border-collapse:collapse">';
		if($tipo==eventos::$RESERVA or $tipo==eventos::$CHECK_LATE_RES) $class="evento reserva";
		else if($tipo==eventos::$CHECKIN or $tipo==eventos::$CHECK_LATE_CHK) $class="evento checkin";
		else if($tipo==eventos::$CHECKOUT) $class="evento checkout";
		
		$html.= '<table style="border-collapse:collapse;" height="14px" width="100%" onClick="showBox(event,'.$id.','.$tipo.');"><tr class="'.$class.'">' .
			 '<td '.$ini.'></td>' .
			 '<td '.$fin.'></td>' .
			 '</tr></table></td>';
		
		$finde=($finde+$dias)%7;
		return $html;
	}
	
	function genera_cells($dias, &$finde, $id_habit, $day_id, $clickable=true){
		
		if($clickable)
			$click_str='onmouseout="rangeReserva(event)" onmouseover="rangeReserva(event)" onclick="rangeReserva(event)" style="cursor:pointer;"';	
		
		$dias+=$day_id;
		for($d_ini=$day_id; $d_ini<$dias; $d_ini++){		
			if($finde==0 || $finde==6){
				$finde_class='finde';
			}
			else{
				$finde_class='';
			}
			$out.= '<td class="t_col '.$finde_class.'"><div id="c_'.$id_habit.'_'.$d_ini.'" class="t_cell_div" '.$click_str.'>&nbsp;</div></td>';
			$finde=($finde+1)%7;				
		}
		return $out;
	}
	
?>