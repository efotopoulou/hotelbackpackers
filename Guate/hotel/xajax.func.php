<?php

require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_cliente.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_calendario.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_checkinres.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_pais.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');
require($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/calendario.func.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_caja.php');
require($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_alojamiento.php');

	class AjaxException extends Exception
	{
	    private $id_class;
	
	    public function __construct($id, $code) {
			$this->id_class=$id;
	        parent::__construct("", $code);
	    }
	
	    public function getId() {
	        return $this->id_class;
	    }
	}


	function loadBox($idEv, $tipo){	
		
		$objResponse = new xajaxResponse('ISO-8859-1');	
		$ev=new eventos();	
		if($tipo==eventos::$RESERVA or $tipo==eventos::$CHECK_LATE_RES or $tipo==-2){
			$result=$ev->get_reserva($idEv);
			$idcl = $ev->get_res_id_cliente();
			
			$imp_pag=$ev-> get_res_imp_pagado();
			
			$titulo='Reservaci�n de '.$ev->get_res_noches().' noche';
			$titulo.=($ev->get_res_noches()>1)?'s':'';
			$titulo.=' (Pagado: '.$imp_pag.'Q)';			
			$cl=new cliente();
			$cl->get_cliente($idcl);
			$nombre=$cl->getNombre()." ".$cl->getApellido1()." ".$cl->getApellido2().".        Tel: ".$cl->getTelefono1();
			if($tipo==eventos::$CHECK_LATE_RES){
				$info='<div class="cal_box_info">Check-in realizado con retraso</div>';
			}
			elseif($tipo==eventos::$RESERVA){
				if($ev->get_res_allow_checkin()){
					$info='<div class="cal_box_info"><a onClick="makeCheck(\'in\', '.$idEv.')">Hacer Check In</a></div>';	
				}
				$info.='<div class="cal_box_info"><a onClick="xajax_loadBox('.$idEv.', -2)">Modificar Reserva</a></div>';
				$info.='<div class="cal_box_info" id="cancelarRes"><a onClick="showBox(event,'.$idEv.', -1)">Cancelar Reserva</a></div>';
			}
			elseif($tipo==-2){	// MODIFICAR RESERVA
				$info='<div class="cal_box_info" style="float:left;">Dia Inicio:<input type="button" onClick="changeEvData('.$idEv.', -1, 1)" value="<" />&nbsp;<input type="button" onClick="changeEvData('.$idEv.', 1, 1)" value=">" /></div>';	
				$info.='<div class="cal_box_info" style="float:left;">Dia Fin:<input type="button" onClick="changeEvData('.$idEv.', -1, 2)" value="<" />&nbsp;<input type="button" onClick="changeEvData('.$idEv.', 1, 2)" value=">" /></div>';				
				$info.='<div class="cal_box_info" style="float:left;">Alojamiento:<input id="newAloj" type="text" size="3"/><input type="button" onClick="changeEvData('.$idEv.', document.getElementById(\'newAloj\').value, 3)" value="Cambiar" /></div>';				
				
			}
		}
		elseif($tipo==eventos::$CHECKIN or $tipo==eventos::$CHECK_LATE_CHK or $tipo==-3 or $tipo==-4){
			$data=$ev->get_ocupacion($idEv);
			$titulo='Ocupaci&oacute;n de '.$ev->get_ocup_noches().' noche';
			$titulo.=($ev->get_ocup_noches()>1)?'s':'';
					
			$check = new checkinres();
			$ocup = $check->get_ocupantes($idEv);
			$clie = new cliente();
			$clie->get_cliente($ocup[0]);
			$nombreclie = $clie->getNombre();
			
			$check->get_checkin($idEv);
			$imp_pag_check=$check->get_imp_pagado();
			$imp_pag_res=$ev->get_res_imp_pagado();
			$imp_pag=$imp_pag_check+$imp_pag_res;
			$titulo.=' (Pagado: '.$imp_pag.'Q)';
			
			$noches=$ev->get_ocup_noches();
			$info='<div class="cal_box_info">'.$noches.' noches de '.$ev->get_res_noches().' reservadas</div>';
			
			if($tipo==eventos::$CHECKIN or $tipo==eventos::$CHECK_LATE_CHK){
			
				$info.='<form name="ocup">';
				$info.='<select name="oc" size="1" style="width:200px">';
				$i = 0;
				if(count($ocup))
				do{
					$clie->get_cliente($ocup[$i]);
					$nombreclie=$clie->getNombre();
					$ap1=$clie->getApellido1();
					$ap2=$clie->getApellido2();
					$info.='<option>'.$nombreclie.''."  ".''.$ap1.''."  ".''.$ap2.'</option>';
					$i++;
				}while(next($ocup));	
				$info.='</select>';
				$info.='</form>';
				
				
				$info.='<div style="width:250px"><div style="float: left">';
				//$info.='<div class="cal_box_info"><a onClick="xajax_loadBox('.$idEv.', -3)">Modificar Ocupaci�n</a></div>';	
				$info.='<div class="cal_box_info"><a onClick="makeCheck(\'out\', '.$idEv.')">Hacer Check-out</a></div>';		
				$info.='</div>';
				$info.='<div style="float: right">';
				$info.='<div class="cal_box_info"><a onClick="xajax_loadBox('.$idEv.', -4)">Cambiar Importe</a></div>';			
				$info.='</div></div>';
				}
			elseif($tipo==-3){ //MODIFICAR CHECKIN
				$info.='<div class="cal_box_info" style="float:left;">Dia Inicio:<input type="button" onClick="changeEvData('.$idEv.', -1, 6)" value="<" />&nbsp;<input type="button" onClick="changeEvData('.$idEv.', 1, 6)" value=">" /></div>';
				$info.='<div class="cal_box_info" style="float:left;">Dia Fin:<input type="button" onClick="changeEvData('.$idEv.', -1, 4)" value="<" />&nbsp;<input type="button" onClick="changeEvData('.$idEv.', 1, 4)" value=">" /></div>';				
				$info.='<div class="cal_box_info">Alojamiento:<input id="newAloj" type="text" size="3"/><input type="button" onClick="changeEvData('.$idEv.', document.getElementById(\'newAloj\').value, 5)" value="Cambiar" /></div>';				
				}
			elseif($tipo==-4){ //MODIFICAR IMPORTE PAGADO
				$info.='<div class="cal_box_info">Importe:<input id="imp_pag" type="text" size="3" value="'.$imp_pag.'" /><input type="button" onClick="changeEvData('.$idEv.', document.getElementById(\'imp_pag\').value, 7)" value="Cambiar" /></div>';				
				}	
		}	
		elseif($tipo==eventos::$CHECKOUT){
			$data=$ev->get_ocupacion($idEv);
			$check = new checkinres();
			$ocup = $check->get_ocupantes($idEv);
			$clie = new cliente();
			$clie->get_cliente($ocup[0]);
			$nombreclie = $clie->getNombre();
			
			$titulo='Check-out';
			$noches=$ev->get_ocup_noches();
			$info='<div class="cal_box_info">'.$noches.' noches de '.$ev->get_res_noches().' reservadas</div>';						
			
			$info.='<form name="ocup">';
				$info.='<select name="oc" size="1" style="width:200px">';
				$i = 0;
				if(count($ocup))
				do{
					$clie->get_cliente($ocup[$i]);
					$nombreclie=$clie->getNombre();
					$ap1=$clie->getApellido1();
					$ap2=$clie->getApellido2();
					$info.='<option>'.$nombreclie.''."  ".''.$ap1.''."  ".''.$ap2.'</option>';
					$i++;
				}while(next($ocup));	
				$info.='</select>';
				$info.='</form>';
		
		
		}
		elseif($tipo==-1){	// CANCELAR RESERVA
			$link='&iquest;Seguro que desea cancelar?&nbsp;<a onClick="xajax_delRes('.$idEv.')">Si</a>&nbsp;<a onClick="hideBox()">No</a>';
			$objResponse = new xajaxResponse();
			$objResponse->addAssign("cancelarRes","innerHTML",$link);
			$objResponse->addScript('document.getElementById("BoxDiv").style.display=""');
			return $objResponse;
		}
	
		$html='
		<img style="position: absolute; left: 33px; top: -16px;" src="/hotel/img/cal_box_border_arrow.gif" onClick="hideBox()"/>
		<img style="position: absolute; left: 265px; top: 10px; cursor: pointer" src="/hotel/img/cal_box_close.gif" onClick="hideBox()"/>
		<div style="position: absolute; left: 15px; top: 10px; z-index:2000">
			<div class="cal_box_title">'.$titulo.'</div> 
			<div class="cal_box_info">'.$nombre.'</div>' .
			$info.		
		'</div>';
	
		$objResponse->addAssign("BoxDiv","innerHTML",$html);
		$objResponse->addScript('document.getElementById("BoxDiv").style.display=""');
		return $objResponse;
	}

	function delRes($idRes){
		try{
		$ev=new eventos();
		$objResponse = new xajaxResponse();
		
		$result=$ev->get_reserva($idRes);
		$idAloj=$ev->get_res_id_aloj();
		
		$return=$ev->del_reserva($idRes);

		if($return>0)
			$objResponse->addScript('hideBox();xajax_refreshRow('.$idAloj.',d,m,y);');
		else
			throw new AjaxException(eventos::$ID, $return);
				
		}catch (Exception $e) {     
	   		$objResponse->addScript("hideBox();GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".$e->getId()."&result=".$e->getCode()."',100,300)");
		}
		return $objResponse;	
	}
	
	function changeEvData($idEv, $data, $opc){
		try{
		$ev=new eventos();
		$check = new checkinres();
		$objResponse = new xajaxResponse();
		
		if($opc==1)		//fec_ini
			$result=$ev->inc_res_fecini($idEv, $data);
		elseif($opc==2)	//fec_fin
			$result=$ev->inc_res_fecfin($idEv, $data);
		elseif($opc==3){ //id_aloj reserva
			$result=$ev->get_reserva($idEv);
			$idAlojOld=$ev->get_res_id_aloj();
						
			$aloj=new alojamiento();
			$result=$aloj->get_aloj_by_nombre($data);
			
			if(!$result)	// si el nombre de aloj. no existe
				throw new AjaxException(eventos::$ID, eventos::$ERR_ALOJ);
			$result=$ev->change_res_aloj($idEv, $aloj->get_id());
			if($result>0){ // si se pudo cambiar el alojamiento
				$objResponse->addScript('hideBox();xajax_refreshRow('.$idAlojOld.',d,m,y);');
				$result=$ev->get_reserva($idEv);
			}
			else		
				throw new AjaxException(eventos::$ID, $result);
		}	
		elseif($opc==4){ //fec_fin de checkin, idEv es el id del checkin			
			$result=$ev->inc_ocup_fecfin($idEv, $data);
		}
		elseif($opc==5){ //id_aloj checkin
			$check->get_checkin($idEv);
			$idres = $check->get_idres();
			$result = $ev->get_reserva($idres);
			
			
			$idAlojOld=$ev->get_res_id_aloj();
						
			$aloj=new alojamiento();
			$result=$aloj->get_aloj_by_nombre($data);
						
			if(!$result)	// si el nombre de aloj. no existe
				throw new AjaxException(eventos::$ID, eventos::$ERR_ALOJ);
			$idAloj=$aloj->get_id();
			$result=$check->change_check_aloj($idEv, $aloj->get_id(),$idres);
			if($result) // si se pudo cambiar el alojamiento
				$objResponse->addScript('hideBox();xajax_refreshRow('.$idAlojOld.',d,m,y);');		
			else		
				throw new AjaxException(eventos::$ID, $result);
		}
		elseif($opc==6){ //fec_inicio de checkin, idEv es el id del checkin			
			$result=$ev->inc_ocup_fecini($idEv, $data);
		}
		elseif($opc==7){ //cambiar importe pagado
			$check->get_checkin($idEv);
			$importe_old=$check->get_imp_pagado();
			$result=$check->modif_imp_pagado($data,$idEv);
			if($result>0){
				$caja=new caja();
				$caja->insert_movimiento(-$importe_old,caja::$CONTADO,$idEv,0,"cambio importe pagado");
				$caja->insert_movimiento($data,caja::$CONTADO,$idEv,0,"cambio importe pagado");
				$objResponse->addScript('hideBox();');
			}
		}
		
		if($opc!=7){ 	
			if($opc!=5){
				$idAloj=$ev->get_res_id_aloj();
			}
			$objResponse->addScript('xajax_refreshRow('.$idAloj.',d,m,y);');
		}
		$objResponse->addScript('xajax_lock=false;');
		
		}catch (AjaxException $e) {     
	   		$objResponse->addScript("xajax_lock=false; hideBox();GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".$e->getId()."&result=".$e->getCode()."',100,300)");
		}
		catch (Exception $e) {     
	   		$objResponse->addScript("xajax_lock=false; hideBox();GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".eventos::$ID."&result=".$e->getCode()."',100,300)");
		}
		return $objResponse;
	}
	
	function loadCli($idCli, $gbHide=false){	
		$cl=new cliente();
		$res=$cl->get_cliente($idCli);
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("cli_data_id","value",$cl->getIdCliente());
		$objResponse->addAssign("cli_data_pasaporte","value",$cl->getPasaporte());
		$objResponse->addAssign("cli_data_nombre","value",$cl->getNombre());
		$objResponse->addAssign("cli_data_apellido1","value",$cl->getApellido1());
		$objResponse->addAssign("cli_data_apellido2","value",$cl->getApellido2());
		$objResponse->addAssign("cli_data_direc","value",$cl->getDirecion());
		$objResponse->addAssign("cli_data_pob","value",$cl->getPoblacion());
		$objResponse->addAssign("cli_data_pais","value",$cl->getPais());
		$objResponse->addAssign("cli_data_tel1","value",$cl->getTelefono1());
		$objResponse->addAssign("cli_data_tel2","value",$cl->getTelefono2());
		$objResponse->addAssign("cli_data_mail","value",$cl->getEmail());
		$objResponse->addAssign("cli_data_observ","value",$cl->getObservaciones());
		if($gbHide)
			$objResponse->addScript('GB_hide();');
		return $objResponse;
	}


	function insertarCli($clidata){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$cl = new cliente();
		$resbox=$cl->alta_cliente($clidata);
		$idcli=$cl->getIdCliente();
		
		if($resbox>0){
			//$objResponse->addAssign("id_cliente","value",$idcli);
			$objResponse->addScript('call_back_buscarCli('.$idcli.',0); clienteNuevo(0)');
			$objResponse->addScript("GB_showCenter('Clientes', '/hotel/view.php?page=message_box&opc=".cliente::$ID."&result=".$resbox."',100,300)");						
		}
		
		return $objResponse;
	}
	
	function loadLit($idParent, $d, $m, $y){
		$objResponse = new xajaxResponse('ISO-8859-1');
				
		$cal=new calendario($d, $m, $y);
		$habit=new alojamiento();
		$habit->get_all_aloj($idParent);
		//??????? cambiar eventos para que carque solo los de literas
		$evento=new eventos($cal->get_day_ini(), $cal->get_day_end());

		$htmlLeft='<table  class="t_general">';
		$htmlMid='<table  class="t_general" id="tableContainerMid_'.$idParent.'">';
		if($habit->get_count())
		do{	
			$htmlLeft.='<tr class="t_row"><td  class="t_col_habit" style="background-color:'.$habit->get_color().'" title="'.$habit->get_tipo().'"><div align="right">'.$habit->get_nombre().'</div></td></tr>';
			$htmlMid.=genera_row($evento, $cal, $habit->get_id(), true);
		}while($habit->movenext());
		$htmlLeft.='</table>';
		$htmlMid.='</table>';
		
		$objResponse->addAssign("containerLeftTD_".$idParent,"innerHTML",$htmlLeft);
		$objResponse->addAssign("containerMidTD_".$idParent,"innerHTML",$htmlMid);		
		
		$objResponse->addScript('showColDayAct("tableContainerMid_'.$idParent.'")');
		
		$objResponse->addScript('document.getElementById("alojLeft_'.$idParent.'").style.backgroundImage="url(/hotel/img/arrow_t.gif)";');
		$objResponse->addScript('document.getElementById("containerLeft_'.$idParent.'").style.display="";');
		$objResponse->addScript('document.getElementById("containerMid_'.$idParent.'").style.display="";');						
		
		return $objResponse;
	}
	
	function loadFreeRooms($fecIni, $fecFin, $idParent=0, $idFromCalend=0, $ListElegidas=array()){
		try{
		$objResponse = new xajaxResponse('ISO-8859-1');
		
		if(checkFecha($fecIni)==false || checkFecha($fecFin)==false){		//comprobar si las fechas son correctas
			throw new AjaxException(eventos::$ID, eventos::$ERR_FECH);
		}	
		
		$habit=new alojamiento();	
		if($idFromCalend>0){	//alojamiento elegido desde calendario
			$ev=new eventos();
			
			if($ev->is_ocup_id_aloj($fecIni, $fecFin, $idFromCalend)==false){
				$habit->get_aloj($idFromCalend);
				
				$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
				$mouseEvent='loadDesc(\''.$habit->get_tipo().'\',\''.$camas.'\')';
				$objResponse->addAssign("listaElegidas","innerHTML",'<li id="'.$habit->get_id().'" class="t_col_habit" style="background-color:'.$habit->get_color().'; margin:1px; cursor:pointer; width:95%;"onmouseover="'.$mouseEvent.'">'.$habit->get_nombre().'</li>');			
			}
		}
		$habit->get_free_aloj($idParent, $fecIni, $fecFin);		
		
		if($habit->get_count())
		do{			
			$mostrar=true;
			if($idFromCalend==$habit->get_id())
				$mostrar=false;
			foreach($ListElegidas as $idAloj){	//si no esta ya elegida se a�ade en disponibles
				if($idAloj==$habit->get_id()){
					$mostrar=false;
					break;
				}
			}
			if($mostrar){				
				$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
				
				$mouseEvent='loadDesc(\''.$habit->get_tipo().'\',\''.$camas.'\')';
				
				if($habit->get_id()==$habit->get_id_parent()) //aloj con literas
					$html.= '<li id="'.$habit->get_id().'" class="t_col_habit hab_literas2" style="background-color:'.$habit->get_color().'; margin:1px; cursor:pointer; width:95%;"onmouseover="'.$mouseEvent.'" onclick="showLiteras('.$habit->get_id().');xajax_loadFreeRooms(f1.date_a.value, f1.date_b.value, '.$habit->get_id().',0,getIdAlojList())"><span class="handle" style="display:none"></span>'.$habit->get_nombre().'</li>';		
				else
					$html.= '<li id="'.$habit->get_id().'" class="t_col_habit" style="background-color:'.$habit->get_color().'; margin:1px; cursor:pointer; width:95%;"onmouseover="'.$mouseEvent.'">'.$habit->get_nombre().'</li>';
			}
		}while($habit->movenext());
	
		if($idParent){
			$objResponse->addAssign("listaCamasDisp","innerHTML",$html);				
		}
		else{
			$objResponse->addAssign("listaAlojDisp","innerHTML",$html);
		}	
		$objResponse->addScript('Sortable.create("listaCamasDisp",
 								{dropOnEmpty:true,handle:"handle",containment:["listaCamasDisp","listaElegidas"],constraint:false});
							Sortable.create("listaAlojDisp",
 								{dropOnEmpty:true,handle:"handle",containment:["listaAlojDisp","listaElegidas"],constraint:false});
	   						Sortable.create("listaElegidas",
 								{dropOnEmpty:true,handle:"handle",containment:["listaAlojDisp","listaCamasDisp","listaElegidas"],constraint:false});');
	
		}catch (Exception $e) {     
	   		$objResponse->addAssign("listaCamasDisp","innerHTML","");
	   		$objResponse->addAssign("listaAlojDisp","innerHTML","");
	   		//$objResponse->addScript("GB_showCenter('Error', '/hotel/view.php?page=message_box&opc=".$e->getId()."&result=".$e->getCode()."',100,300)");
		}
		return $objResponse;			
	}
	
	function checkFecha($date){
	    if (!isset($date) || $date=="")
	    	return false;
	    	  
	    list($dd,$mm,$yy)=explode("/",$date);
	   
	    if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd))
			return checkdate($mm,$dd,$yy);
	    return false;
	}
	
	function load_chepr($idres){
		
		$ev = new eventos();
		$ev->get_reserva($idres);
		$feclleg= date("d/"."m/"."Y");
		$fec1 = split("/",$feclleg);
		
		
		$fecfin = $ev->get_res_fecfin();
		$fec2= split("/",$fecfin);
		
		$fec_out=mktime(0,0,0,(int)$fec2[1],(int)$fec2[0]+1,(int)$fec2[2]);
		$fec_out=date("d/m/Y",$fec_out);
		
		$idcliente = $ev->get_res_id_cliente();

		$idaloj = $ev->get_res_id_aloj();
		$habit = new alojamiento();
		$habit->get_aloj($idaloj);
		
		$hab = $habit->get_nombre();
		$tipohab = $habit->get_tipo();
		
		$imp_pagado=$ev->get_res_imp_pagado();
		 
		$preu = new temporada();
		$total = $preu->calculo_precio($idaloj,mktime(0,0,0,(int)$fec1[1],(int)$fec1[0],(int)$fec1[2]), mktime(0,0,0,(int)$fec2[1],(int)$fec2[0],(int)$fec2[2]));
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		
		$objResponse->addAssign("id_res","value",$idres);
		$objResponse->addAssign("f_date_c1","value",$fec_out);
		$objResponse->addAssign("hab","value",$hab." ".$tipohab);
		$objResponse->addAssign("importe","value",number_format($total,2));
		$objResponse->addAssign("pag_res","value",number_format($imp_pagado,2));
		$objResponse->addScript("multiclient[1]=$idcliente;posi_act=0;moverCursorCli(1);");
		
		return $objResponse;					
	}
	
	
	function load_ocupantes($idcheck=0){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$ocup = new checkinres();
		$res=$ocup->get_checkin($idcheck);
		
		
		if($res>0){
			$idfra=$ocup->get_idfra();
			
			$ocupantes = $ocup->get_ocupantes($idcheck);
				
				
			$fecini=$ocup->get_fecin();
		
			$fecini = split("-",$fecini);
			$mes=$fecini[1];
			$dia=$fecini[2];
			$any=$fecini[0];
			$fecini=date("$dia/$mes/$any");
			$feclleg = mktime(0,0,0,$mes,$dia,$any);
			
			$fechasalida=date("d/"."m/"."Y");
			//fecha salida, hoy o fin reserva
			$fecfin=$ocup->get_fecfin();
			
			$fecout=mktime(0,0,0,date("m"),date("d")-1,date("Y"));
						
			if($fecfin<date("Y-m-d",$fecout)){
			$fechasalida=$fecfin;
			$fechasalida=split("-",$fechasalida);
			$mes2=$fechasalida[1];
			$dia2=$fechasalida[2];
			$any2=$fechasalida[0];
			$fechasalida=mktime(0,0,0,$mes2,$dia2+1,$any2);
			$fechasalida=date("d/m/Y",$fechasalida);
			
			$fecout=mktime(0,0,0,$mes2,$dia2,$any2);
			}
			
			$hab=new alojamiento();
			$hab->get_aloj($ocup->get_idaloj());
			$idaloj=$hab->get_id();
			$nombre=$hab->get_nombre();
			$tipo=$hab->get_tipo();
			
			$temp=new temporada();
			
			if($feclleg>$fecout){
				$noches=0;
			}
			else{
				$noches=$temp->diff_days($feclleg,$fecout)+1;
			}
			
			$preu=$temp->calculo_precio($idaloj,mktime(0,0,0,$mes,$dia,$any),$fecout);
			$preu=round($preu,2);
			$pag_res=$ocup->get_res_imp_pagado();
			$pag_checkin=$ocup->get_imp_pagado();
			$pagado=$pag_res+$pag_checkin;
					
			$cliente = new cliente();
			
			if(sizeof($ocupantes)>0)					
			foreach($ocupantes as $value){
				
				$cliente->get_cliente($value);
				$idcli=$cliente->getIdCliente();
				$nombre = $cliente->getNombre();
				$apellido1 = $cliente->getApellido1();
				$apellido2 = $cliente->getApellido2();
				
				$html.='<option value='.$idcli.'>'.$nombre.''."  ".''.$apellido1.''."  ".''.$apellido2.'</option>';
			}
			
			if($idfra>0){
				$objResponse->addScript("xajax_loadLineasFra($idfra)");
			}
			
		}
		
		$objResponse->addAssign("ocupantes","innerHTML",$html);
		$objResponse->addAssign("f_date_c","value",$fecini);
		$objResponse->addAssign("f_date_c1","value",$fechasalida);
		$objResponse->addAssign("alojam","value",$preu);
		$objResponse->addAssign("pagado","value",$pagado);
		$objResponse->addAssign("cant","value",$noches);
	
		$objResponse->addScript("calcularsubt()");
		//$objResponse->addScript("calcularfra()");
		return $objResponse;					
	}
	
	
	
	function load_preu($idaloj,$fecha1,$fecha2){
		
		$fec1split=split("/",$fecha1);
		$dia1=(int)$fec1split[0];
		$mes1=(int)$fec1split[1];
		$any1=(int)$fec1split[2];
	
	
		$fec2split=split("/",$fecha2);
		$dia2=(int)$fec2split[0];
		$mes2=(int)$fec2split[1];
		$any2=(int)$fec2split[2];
	
	
		$temp = new temporada();
		$preu= $temp->calculo_precio($idaloj,mktime(0,0,0,$mes1,$dia1,$any1),mktime(0,0,0,$mes2,$dia2,$any2));
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("alojam","value",$preu);
		return $objResponse;	
	}
	
	
	function load_autopaises(){
		
		$pais = new pais();
		$pais->get_paises();
		
		$pais->movefirst();
		do{
			$listapaises.="'".$pais->get_nombre()."',";
		}while($pais->movenext());
		 	
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addScript("new Autocompleter.Local('cli_data_pais', 'lista_paises',[$listapaises''], {});");
		return $objResponse;		
	}
	
	function refreshRow($idHabit, $d, $m, $y){
		$cal=new calendario($d, $m, $y);
		$evento=new eventos($cal->get_day_ini(), $cal->get_day_end(), $idHabit);
		
		$html=genera_row($evento, $cal, $idHabit, true, true);
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("trMid_".$idHabit,"innerHTML",$html);
		$objResponse->addScript('showColDayAct("tableMid");');	//????? para literas no va
		return $objResponse;
	}
	
	
	function changeUsuario($id){
		$sesion=new session();
		
		$sesion->set_id_usuario($id);
		
		$log=new log();
		$log->insertar_log($sesion->get_id_usuario(), log::$USR_LOGIN, 0);
		
	}
	
	
	function loadLineasFra($idFra){
	
		$fra = new factura();
		
		$res=$fra->get_factura($idFra);
		
		if ($res>0){
		$pagado=$fra->get_pagado();
		}
		
		$res=$fra->get_lineas($idFra);
		$total=0;
				
		$html= '<table class="t_general fondo_tabla" style="width:100%">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:60px; text-align:center">Noches</td>' .
					'<td class="t_col" style="width:150px">Descripci�n</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Recargo</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Descuento</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Importe</td>' .
					'</tr>';
		if($res>0)
		do{
				$html.= '<tr class="t_row" style="cursor:pointer" id="linea_'.$fra->get_id_linea().'" onClick="selectLinea(this.id)">' .
					'<td class="t_col" style="width:60px; text-align:center">'.$fra->get_noches().'</td>' .
					'<td class="t_col" style="width:150px">'.$fra->get_descripcion().'</td>' .
					'<td class="t_col" style="width:60px; text-align:center">'.$fra->get_recargo().'</td>' .
					'<td class="t_col" style="width:60px; text-align:center">'.$fra->get_descuento().'</td>' .
					'<td class="t_col" style="width:60px; text-align:center">'.$fra->get_importe().'</td>' .
					'</tr>';
				$total=$total+$fra->get_importe();		
		}while($fra->movenext());
		
		$html.= '</table>';
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		
		if($res>0){
			$objResponse->addScript("showButtons('b6','b5')");
		}
		$objResponse->addAssign("linea_id_fra","value",$idFra);	
		$objResponse->addAssign("imptotal","value",$total);	
		$objResponse->addAssign("editLineas","innerHTML",$html);	
		$objResponse->addAssign("acuent","value",$pagado);
		return $objResponse;
	}
	
	
		function changeLineas($lineadata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$fra = new factura();
		
		$idfra=$lineadata['linea_id_fra'];
			if($modo==1){
				$resbox=$fra->insert_linea($lineadata['linea_id_fra'],$lineadata['linea_noches'],$lineadata['linea_descripcion'],0,$lineadata['linea_recargo'],$lineadata['linea_descuento']);
			}
			elseif($modo==2){
				$resbox=$fra->modificar_linea($lineadata['linea_noches'],$lineadata['linea_descripcion'],$lineadata['linea_importe'],$lineadata['linea_recargo'],$lineadata['linea_descuento'],$lineadata['linea_id_fra'],$lineadata['linea_id']);
			}
			elseif($modo==3){
				$resbox=$fra->eliminar_linea($lineadata['linea_id_fra'],$lineadata['linea_id']);
				if($resbox>0)
					$objResponse->addScript("idLineaLast=''");
			}
			//if($resbox>0){
				$objResponse->addScript("xajax_loadLineasFra($idfra); showButtons('b6', 'b5')");						
			//}
			
			return $objResponse;
		}
	
	function makecheckout($data){
		$checkprev= new checkinres();
		
		$idcheck=$data['id_checkin'];
		$checkprev->get_checkin($data['id_checkin']);
		$pagado=$checkprev->get_imp_pagado()+$checkprev->get_res_imp_pagado();
		$data["acuent"]=$pagado;
		$resbox=$checkprev->make_checkout($data);
		
		if($resbox>0){
			$caja = new caja();
			if($_POST['modopago']==1){
				$modo=caja::$CONTADO;
			}
			else{
				$modo=caja::$VISA;
			}
			$caja->insert_movimiento($data['valor'],$modo,$idcheck,0,"checkout");

			$sesion=new session();
			$log=new log();
			$log->insertar_log($sesion->get_id_usuario(), log::$INS_CHECKOUT, $idcheck);
			//$data['valor']
			//insertar el movimiento del check-in en la bd del restaurante
			$idencargado=$sesion->get_id_usuario();
			$alojamiento = new alojamientoRes();
			$alojamiento->insert_checkoutmov($idcheck,$data['valor'],$idencargado);

		}	
		$idfra=$checkprev->get_last_idfra();
		$checkprev->get_checkin($data[id_checkin]);
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("id_fra","value",$idfra);	
		$objResponse->addScript('xajax_loadLineasFra('.$idfra.')');
		$objResponse->addScript('xajax_load_checkoutpre()');
		$objResponse->addScript('xajax_load_ocupantes()');
		$objResponse->addAssign("elect","value","");
		$objResponse->addAssign("id_checkin","value",0);
		$objResponse->addScript("GB_showCenter('Check Out', '/hotel/view.php?page=message_box&opc=".checkinres::$IDOUT."&result=".$resbox."',100,300)");
			
		return $objResponse;	
	}
	
	function cerrarfactura($data){
		$fra=new factura();
		$fra->get_factura($data['id_fra']);
		$idfra=$data['id_fra'];
		$pagado=$fra->get_pagado();
		$caja = new caja();
		
		if($data['apag']>0)
			$caja->insert_movimiento($data['apag'],$data['modopago'],0,$idfra,'factura');
		
		$sesion=new session();
		$log=new log();
		$log->insertar_log($sesion->get_id_usuario(), log::$CERRAR_FRA, $idfra);
		
		
		$nit=$data['nit'];
		$nombre=$data['nombre'];
		$numfra=$data['numfra'];
		$impuesto=$data['impuesto'];
		$total=$data['total'];
		$apag=$data['apag'];
		
		$nuevopagado=$pagado+$apag;
								
		$fra->cerrar_fra($nombre,$nit,$numfra,$impuesto,$total,$nuevopagado,$idfra);
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addScript("location.href='/hotel/view.php?page=checkout'");
		return $objResponse;
	}
	
	 function load_checkoutpre($idCheck=0){
  		$ch = new checkinres();
		
  		$elect="";
  		
  		if($idCheck){
  			$ch->get_checkin($idCheck);
  			$hab = new alojamiento();
  			$hab->get_aloj($ch->get_idaloj());
  			$nom=$hab->get_nombre();	
  			$tipo=$hab->get_tipo();
  			$elect=htmlentities($nom. " . ".$tipo);
  		}
  		$ch->get_checkoutprev();
  		
  		$html='<form id="formcheckprv">';
  		$html.='<select name="chprv" size=7 style="width:200px" onchange="elegido(this.options[this.selectedIndex].value,this.options[this.selectedIndex].text)">';
  		
  		if($ch->get_count())
  		do{
  			$hab = new alojamiento();
  			$hab->get_aloj($ch->get_idaloj());
  			$nom=$hab->get_nombre();	
  			$tipo=$hab->get_tipo();
  			if($idCheck==$ch->get_idcheckin()){
  				$sel="selected";	
  			}
  			else{
  			$sel="";		
  			}
  		$html.= "<option value='".$ch->get_idcheckin()."' ".$sel.">".htmlentities($nom. " . ".$tipo)."</option>";
  		}while($ch->movenext());
		$html.= "</select>";
		$html.= "</form>";
		
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("checkoutpre","innerHTML",$html);	
		$objResponse->addAssign("elect","value",$elect);
		$objResponse->addAssign("descripcion","value",$elect);
		return $objResponse;
  	}
  	
  	
  	function changeCli($clidata, $modo){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$cli = new cliente();
		
		if($modo==1){
			$resbox=$cli->alta_cliente($clidata);
		}
		elseif($modo==2){
			$resbox=$cli->modif_cliente($clidata);
		}
		elseif($modo==3){
			$resbox=$cli->eliminar_cliente($clidata['cli_data_id']);
		}
		if($resbox>0){
			if($modo==3){
				$objResponse->addScript("document.getElementById('FormCliente').reset()");
			}
			else{
				$objResponse->addScript("document.getElementById('FormCliente').cli_data_id.value=".$cli->getIdCliente());	
				if($modo==1){	
				$objResponse->addScript("call_back_buscarCli(".$cli->getIdCliente().",0)");
				}
			}
			$objResponse->addScript("showButtons('b6', 'b5')");
			$objResponse->addScript("formCliDisabled(true)");					
		}
		$objResponse->addScript("GB_showCenter('Cliente', '/hotel/view.php?page=message_box&opc=".cliente::$ID."&result=".$resbox."',100,300)");
		return $objResponse;
	}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax.req.php');
$xajax->processRequests();
?>