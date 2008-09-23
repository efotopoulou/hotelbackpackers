<?php

require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_precios.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
	
	function loadTipoAlojPrecios(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tipos=new precios();
		
		$tipos->get_all_tipos();
				
		$html= '<table class="t_general fondo_tabla" >';
		
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:70px">Nombre</td></tr>';
		//$select='<ul style="cursor:pointer">';			
		if($tipos->t_get_count())
		do{
			if($tipos->t_es_tipo_normal()==false)
				$onclick='';
			else
				$onclick='';
			$html.= '<tr class="t_row" id="tprecio_'.$tipos->t_get_id().'" style="cursor:pointer" onClick="selectTipoPrecio(this.id)">' .
					'<td class="t_col">'.$tipos->t_get_nombre().'</td></tr>';
			//$select.='<li id='.$tipos->t_get_id().' style="width:100%; background-color:'.$tipos->t_get_color().'" onClick="copyTipo(this.id, this.style.background)">&nbsp;</li>';
		}while($tipos->t_movenext());
		
		//$select.='</ul>';
		$html.= '</table>';
		
		$objResponse->addAssign("EditTipoPrecios","innerHTML",$html);	
		//$objResponse->addAssign("SelectTipo","innerHTML",$select);	
		
		return $objResponse;
	}
	
	function loadAlojPrecios($id_tipo=0){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$preu=new precios();
		$habit=new alojamiento();
		
		$habit->get_all_aloj();	
		if($id_tipo>0)
			$res=$preu->get_precios($id_tipo);
		
		$html= '<table class="t_general">';
		if($habit->get_count())
		do{
			$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
			$title='tipo: '.$habit->get_tipo().', camas: '.$camas;
			if($id_tipo>0)
				$precio=$preu->get_precio($habit->get_id());
			else
				$precio='';
			$html.= '<tr class="t_row"><td id="alojLeft_'.$habit->get_id().'" class="t_col_habit" style="background-color:'.$habit->get_color().'" title="'.$title.'">'.$habit->get_nombre().'</td>' .
					'<td><input id="precio_'.$habit->get_id().'" name="precio_'.$habit->get_id().'" style="text-align:right" type="text" size="2" maxlength="4" value="'.$precio.'" /></td></tr>';
		}while($habit->movenext());
		$html.= '</table>';
		$html.= '<input id="id_tprecio" name="id_tprecio" type="hidden"  value="'.$id_tipo.'"/>';
			
		$objResponse->addAssign("FormPrecios","innerHTML",$html);					
		return $objResponse;		
	}
	
	
	
	function insertarPrecios($data){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$preu=new precios();
		$id_tprecio=$data['id_tprecio'];
		if($id_tprecio>0){	
			$preu->insertar_precios($data, $id_tprecio);
			$objResponse->addScript("xajax_loadAlojPrecios(".$id_tprecio.")");
			$objResponse->addScript("GB_showCenter('Precios', '/hotel/view.php?page=message_box&opc=".precios::$ID."&result=".precios::$OK."',100,300)");			
		}
		else{
			$objResponse->addScript("GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".precios::$ID."&result=".precios::$ERR_NO_TEMP."',100,300)");	
		}
		return $objResponse;		
	}
	
	function loadTemporadas($selectFirst=false){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tempo=new temporada();
						
		$html= '<form id="FormTempo"><table class="t_general fondo_tabla" style="width:100%">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:70px">Nombre</td>' .
					'<td class="t_col" colspan="2" style="width:50px; text-align:center">Inicio</td>' .
					'<td class="t_col" colspan="2" style="width:50px; text-align:center">Fin</td>' .
					'</tr>';
		if($tempo->get_count()){
			$id_first=$tempo->get_id_temp();
			do{
				$html.= '<tr class="t_row" id="tempo_'.$tempo->get_id_temp().'" style="cursor:pointer" onclick="selectTempo(this.id)">' .
					'<td class="t_col">'.$tempo->getnombretemp().'</td>' .
					'<td class="t_col" style="text-align:center">'.$tempo->get_d_ini().'</td><td class="t_col" style="text-align:center">'.$tempo->get_m_ini().'</td>' .
					'<td class="t_col" style="text-align:center">'.$tempo->get_d_fin().'</td><td class="t_col" style="text-align:center">'.$tempo->get_m_fin().'</td>' .
					'</tr>';
			}while($tempo->movenext());
		}
		$html.= '<tr class="t_row" id="tempo_edit" style="background:#fff;display:none">' .
			'<td class="t_col" style="width:70px"><input style="width:100%" id="nombre" name="nombre" type="text"  value=""/></td>' .
			'<td class="t_col" style="width:26px"><input style="width:100%" id="d_ini" name="d_ini" type="text"  value=""/></td>' .
			'<td class="t_col" style="width:26px"><input style="width:100%" id="m_ini" name="m_ini" type="text"  value=""/></td>' .
			'<td class="t_col" style="width:26px"><input style="width:100%" id="d_fin" name="d_fin" type="text"  value=""/></td>' .
			'<td class="t_col" style="width:26px"><input style="width:100%" id="m_fin" name="m_fin" type="text"  value=""/></td>' .
			'</tr>';
		$html.='</table><input id="id_tempo" name="id_tempo" type="hidden"  value=""/></form>';
			
		$objResponse->addAssign("EditTempos","innerHTML",$html);
		if($selectFirst && $tempo->get_count()){
			$objResponse->addScript("selectTempo('tempo_".$id_first."')");
		}
		return $objResponse;
	}
	
	function changeTempos($tempodata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tempo = new temporada();
		
		if($modo==1){
			$resbox=$tempo->insertar_tempo($tempodata);
		}
		elseif($modo==2){
			$resbox=$tempo->modificar_tempo($tempodata);
		}
		elseif($modo==3){
			$resbox=$tempo->eliminar_tempo($tempodata['id_tempo']);
			if($resbox>0)
				$objResponse->addScript("idLastSelected=''");
		}
		if($resbox>0){
			$objResponse->addScript("xajax_loadTemporadas(); showButtons('b2', 'b1')");						
		}
		
		return $objResponse;
	}
	
	function loadEditAlojs($id_parent=0){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$habit=new alojamiento();
		
		$habit->get_all_aloj($id_parent);
				
		$html= '<table class="t_general fondo_tabla">';
		
		if($id_parent==0)
			$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:70px">Nombre</td>' .
					'<td class="t_col" style="width:80px; text-align:center">Camas matr.</td>' .
					'<td class="t_col" style="width:80px; text-align:center">Camas ind.</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Común</td>' .
					'<td class="t_col" style="width:40px; text-align:center">Orden</td></tr>';
		
		if($habit->get_count())
		do{
			if($habit->es_comun()){
				$comun='checked="yes"';
				$containerRow='<tr id="container_'.$habit->get_id().'" style="display:none"><td id="containerTD_'.$habit->get_id().'" class="t_container" colspan="6"></td></tr>';
				$class_str=' hab_literas';
				$ev_str='onClick="showLiteras('.$habit->get_id().')"';
			}
			else{
				$comun='';
				$containerRow='';
				$class_str='';
				$ev_str='';
			}
			$html.= '<tr class="t_row" id="aloj_'.$habit->get_id().'" style="cursor:pointer" onClick="selectAloj(this.id)">' .
					'<td class="t_col'.$class_str.'" id="'.$habit->get_id_tipo().'" '.$ev_str.' style="width:70px;border-color:#000;background-color:'.$habit->get_color().'">'.$habit->get_nombre().'</td>' .
					'<td class="t_col" style="width:80px;text-align:right">'.$habit->get_num_matrim().'</td>' .
					'<td class="t_col" style="width:80px;text-align:right">'.$habit->get_num_indiv().'</td>' .
					'<td class="t_col" style="width:60px;text-align:center"><input type="checkbox" '.$comun.' disabled /></td>' .
					'<td class="t_col" style="width:40px;text-align:right">'.$habit->get_orden().'</td></tr>';
			$html.=$containerRow;
		
		}while($habit->movenext());
		
		$html.='</table>';

		if($id_parent==0)		
			$objResponse->addAssign("EditAlojs","innerHTML",$html);	
		else{
			$objResponse->addAssign("containerTD_".$id_parent,"innerHTML",$html);
			$objResponse->addScript('document.getElementById("aloj_'.$id_parent.'").firstChild.style.backgroundImage="url(/img/arrow_t.gif)";');
			$objResponse->addScript('document.getElementById("container_'.$id_parent.'").style.display="";');		
		}	
		return $objResponse;
	}
	
	function changeAlojs($alojdata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tempo = new alojamiento();
		
		if(strlen($alojdata['aloj_nombre'])>0 && 
			($alojdata['aloj_matrim']>0 || $alojdata['aloj_indiv']>0)){ 
		
			if($modo==1){
				$resbox=$tempo->insertar_aloj($alojdata);
			}
			elseif($modo==2){
				$resbox=$tempo->modificar_aloj($alojdata);
			}
			elseif($modo==3){
				$resbox=$tempo->eliminar_aloj($alojdata['id_aloj']);
				if($resbox>0)
					$objResponse->addScript("idAlojLast=''");
			}
		}
		else
			$resbox=alojamiento::$ERR_INS_ALOJ;
		
		if($resbox>0){
			$objResponse->addScript("document.getElementById('aloj_edit').style.display='none'; document.getElementById('SelectTipo').style.display='none'; xajax_loadEditAlojs(); showButtons('b4', 'b3'); selectTempo('')");
			$objResponse->addScript("GB_showCenter('Alojamiento', '/hotel/view.php?page=message_box&opc=".alojamiento::$ID."&result=".$resbox."',100,300)");
		}
		else		
			$objResponse->addScript("GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".alojamiento::$ID."&result=".$resbox."',100,300)");	
		return $objResponse;
	}
	
	function loadEditTipoAlojs(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tipos=new alojamiento();
		
		$tipos->get_all_tipos();
				
		$html= '<table class="t_general fondo_tabla" style="width:210px">';
		
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:60px; text-align:center">Color</td>' .
					'<td class="t_col" style="width:150px">Descripción</td></tr>';
		$select='<ul style="cursor:pointer">';			
		if($tipos->t_get_count())
		do{
			if($tipos->t_es_tipo_cama()==false && $tipos->t_es_tipo_comun()==false)
				$onclick='style="cursor:pointer" onClick="selectTipoAloj(this.id)"';
			else
				$onclick='';
			$html.= '<tr class="t_row" id="taloj_'.$tipos->t_get_id().'" '.$onclick.'>' .
					'<td class="t_col" style="border-color:#000;text-align:center;background-color:'.$tipos->t_get_color().'">'.$tipos->t_get_color().'</td>' .
					'<td class="t_col_habit">'.$tipos->t_get_descripcion().'</td></tr>';
			$select.='<li id='.$tipos->t_get_id().' style="width:100%; background-color:'.$tipos->t_get_color().'" onClick="copyTipo(this.id, this.style.background)">&nbsp;</li>';
		}while($tipos->t_movenext());
		
		$select.='</ul>';
		$html.= '</table>';
		
		$objResponse->addAssign("EditTipoAlojs","innerHTML",$html);	
		$objResponse->addAssign("SelectTipo","innerHTML",$select);	
		return $objResponse;
	}
	
	function changeTipoAlojs($tipodata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tempo = new alojamiento();
		
		if($modo==1){
			$resbox=$tempo->insertar_tipo($tipodata);
		}
		elseif($modo==2){
			$resbox=$tempo->modificar_tipo($tipodata);
		}
		elseif($modo==3){
			$resbox=$tempo->eliminar_tipo($tipodata['id_taloj']);
			if($resbox>0)
				$objResponse->addScript("idTipoAlojLast=''");
		}
		if($resbox>0){
			$objResponse->addScript("document.getElementById('taloj_edit').style.display='none'; xajax_loadEditTipoAlojs(); showButtons('b6', 'b5')");						
		}
		
		return $objResponse;
	}
	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/admin_precios.xajax.req.php');
$xajax->processRequests();
?>