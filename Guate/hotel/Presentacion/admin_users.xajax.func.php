<?php
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_session.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
	
	function loadUsers(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$usr=new usuario();
		$ses=new session();
		$ses->get_perfiles();
		
		$html= '<table class="t_general fondo_tabla">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:70px">Nombre</td>' .
					'<td class="t_col" style="width:100px; text-align:center">Perfil</td></tr>';
		
		$usr->get_usuarios();
		if($usr->get_count()){
			do{
				$html.= '<tr class="t_row" id="user_'.$usr->get_id().'" style="cursor:pointer" onclick="selectUser(this.id)">' .
					'<td class="t_col">'.$usr->get_nombre().'</td>' .
					'<td class="t_col" id="'.$usr->get_id_perfil().'" style="text-align:center">'.$ses->get_nombre($usr->get_id_perfil()).'</td></tr>';
			}while($usr->movenext());
		}
		$html.='</table>';
			
		$objResponse->addAssign("EditUsers","innerHTML",$html);
		return $objResponse;
	}
	
	function changeUsers($usrdata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$tempo = new usuario();
		
		if($modo==1){
			$resbox=$tempo->insertar_usuario($usrdata);
		}
		elseif($modo==2){
			$resbox=$tempo->modificar_usuario($usrdata);
		}
		elseif($modo==3){
			$resbox=$tempo->eliminar_usuario($usrdata['user_id']);
			if($resbox>0)
				$objResponse->addScript("idUserLast=''");
		}
		if($resbox>0){
			$objResponse->addScript("xajax_loadUsers(); showButtons('b2', 'b1')");						
		}
		
		return $objResponse;
	}
	function loadPerfilesRest(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$ses=new session();
		$perfiles = $ses->get_perfiles_rest();
		$paginas = $ses->get_paginas_rest();
		$html= '<table class="t_general fondo_tabla" style="width:500px">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:80px">Nombre</td>'.
					'<td class="t_col" style="width:60px">Password</td>';
					
		foreach ($paginas as $value) {
			$html.= '<td class="t_col" style="width:60px; text-align:center">'.$value.'</td>';
		}			
/*		for ($i=0;sizeof($paginas);$i++){
			$html.= '<td class="t_col" style="width:60px; text-align:center">Nombre</td>';
		}
/*					'<td class="t_col" style="width:60px; text-align:center">TPV</td>'.
					'<td class="t_col" style="width:60px; text-align:center">Caja</td>'.
					'<td class="t_col" style="width:60px; text-align:center">Factura</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_Platillos</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_Backup</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_estadisticas</td>'.
					'<td class="t_col" style="width:60px; text-align:center">HistoricoCajas</td>'.
					'<td class="t_col" style="width:60px; text-align:center">PantallaCocina</td>'.
					'<td class="t_col" style="width:60px; text-align:center">CuentaUsuarios</td>'.*/
		$html.='</tr>';

		$sel='';
		if($ses->get_count_rest()){
			do{
				$sel.='<option value="'.$ses->get_id_rest().'">'.$ses->get_nombre_rest().'</option>';	
				if($ses->es_admin_rest()==false)
					$selectable='style="cursor:pointer" onClick="selectPerfilRest(this.id)"';
				else
					$selectable='';
				
				$html.= '<tr class="t_row" id="perfil_rest_'.$ses->get_id_rest().'" '.$selectable.'>' .
						'<td class="t_col" style="width:80px">'.$ses->get_nombre_rest().'</td>' .
						'<td class="t_col" style="width:60px">*****</td>';
				foreach ($paginas as $value) {
					$allowed=($ses->rest_allowed($value))?'checked="yes"':'';
					$html.='<td class="t_col" style=" text-align:center"  style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				}

/*				$allowed=($ses->tpv_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"  style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->caja_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"  style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->factura_rest_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"  style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->platillos_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"  style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->ad_backup_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center" style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->ad_estadisticas_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center" style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->historico_cajas_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center" style="width:60px"><input type="checkbox" '.$allowed.' disabled /></td>';
				*/
				$html.='</tr>';	
					
			}while($ses->movenext_rest());
		}
		$html.='<tr class="t_row" id="perfil_edit_rest" style="background:#fff;display:none">';
			foreach ($paginas as $value) {
				$html.='<td class="t_col" style="width:80px"><input style="width:90px" id="perfil_'.$value.'" name="perfil_'.$value.'" type="text"  value=""/></td>';
			}

/*'			<td class="t_col" style="width:80px"><input style="width:90px" id="perfil_nombre" name="perfil_nombre" type="text"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_password" name="perfil_password" type="text"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_TPV" name="perfil_TPV" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_Caja" name="perfil_Caja" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_Factura" name="perfil_Factura" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_Platillos" name="perfil_ad_Platillos" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_Backup" name="perfil_ad_Backup" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_estadisticas" name="perfil_ad_estadisticas" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_historico_cajas" name="perfil_historico_cajas" type="checkbox"  value=""/></td>*/
			$html.='</tr></table>';


		//$objResponse->addAssign("user_perfiles","innerHTML",$sel);
		$objResponse->addAssign("EditPerfilRest","innerHTML",$html);
		return $objResponse;
	}

	function loadPerfiles(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		
		$ses=new session();
		$ses->get_perfiles();
		
		$html= '<table class="t_general fondo_tabla" style="width:830px">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:80px">Nombre</td>'.
					'<td class="t_col" style="width:60px">Password</td>'.
					'<td class="t_col" style="width:60px; text-align:center">calendario</td>'.
					'<td class="t_col" style="width:60px; text-align:center">reserva</td>'.
					'<td class="t_col" style="width:60px; text-align:center">checkin</td>'.
					'<td class="t_col" style="width:60px; text-align:center">checkout</td>'.
					'<td class="t_col" style="width:60px; text-align:center">cliente</td>'.
					'<td class="t_col" style="width:60px; text-align:center">factura</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_menu</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_precios</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_usuarios</td>'.
					'<td class="t_col" style="width:60px; text-align:center">ad_listados</td>'.
		$html.='</tr>';
	
		$sel='';
		if($ses->get_count()){
			do{
				$sel.='<option value="'.$ses->get_id().'">'.$ses->get_nombre().'</option>';	
				if($ses->es_admin()==false)
					$selectable='style="cursor:pointer" onClick="selectPerfil(this.id)"';
				else
					$selectable='';
				
				$html.= '<tr class="t_row" id="perfil_'.$ses->get_id().'" '.$selectable.'>' .
						'<td class="t_col" style="width:90px">'.$ses->get_nombre().'</td>' .
						'<td class="t_col" style="width:66px">*****</td>';					

				$allowed=($ses->calendario_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->reserva_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->checkinres_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->checkout_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->cliente_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->factura_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->admenu_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->adprecios_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->adusers_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$allowed=($ses->adlistados_allowed())?'checked="yes"':'';
				$html.='<td class="t_col" style=" text-align:center"><input type="checkbox" '.$allowed.' disabled /></td>';
				$html.='</tr>';	
					
			}while($ses->movenext());
		}
	
		$html.='<tr class="t_row" id="perfil_edit" style="background:#fff;display:none">
			<td class="t_col" style="width:90px"><input style="width:90px" id="perfil_nombre" name="perfil_nombre" type="text"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_password" name="perfil_password" type="text"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_calendario" name="perfil_calendario" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_reserva" name="perfil_reserva" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_checkin" name="perfil_checkin" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_checkout" name="perfil_checkout" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_cliente" name="perfil_cliente" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_factura" name="perfil_caja" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_menu" name="perfil_ad_menu" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_precios" name="perfil_ad_precios" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_usuarios" name="perfil_ad_usuarios" type="checkbox"  value=""/></td>
			<td class="t_col" style="width:60px"><input style="width:100%" id="perfil_ad_listados" name="perfil_ad_listados" type="checkbox"  value=""/></td>
			</tr>';
		$html.='</table>';
			
		$objResponse->addAssign("user_perfiles","innerHTML",$sel);
		$objResponse->addAssign("EditPerfil","innerHTML",$html);
		return $objResponse;
	}
	
	function changePerfiles($data, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$ses = new session();
		
		if($modo==1){
			$resbox=$ses->insertar_perfil($data);
		}
		elseif($modo==2){
			$resbox=$ses->modificar_perfil($data);
		}
		elseif($modo==3){
			$resbox=$ses->eliminar_perfil($data['perfil_id']);
			if($resbox>0)
				$objResponse->addScript("idPerfilLast=''");
		}
		if($resbox>0){
			$objResponse->addScript("xajax_loadPerfiles(); showButtons('b4', 'b3')");						
		}
		return $objResponse;
	}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/admin_users.xajax.req.php');
$xajax->processRequests();
?>