<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_checkinres.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_cliente.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
include($_SERVER['DOCUMENT_ROOT'] . '/common/Dominio/class_session.php');
	
	function loadCheckins(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$ch=new checkinres();
		$cl=new cliente();
		$aloj=new alojamiento();
		$tempo=new temporada();	
			
		$ch->get_checksinfra();
		
		$html= '<table class="t_general fondo_tabla" style="width:100%">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:85px">Alojamiento</td>' .
					'<td class="t_col" style="width:90px; text-align:center">Fecha Entrada</td>' .
					'<td class="t_col" style="width:120px; text-align:center">Fecha Salida</td>' .			
					'<td class="t_col" style="width:40px; text-align:center">Noches</td>' .
					'<td class="t_col" style="width:40px; text-align:center">Importe</td>' .
					'<td class="t_col" style="width:100px; text-align:center">Nombre</td>' .
					'<td class="t_col" style="width:110px; text-align:center">Apellidos</td>' .			
					'</tr>'; 
		if($ch->get_count()){
			do{
				$aloj->get_aloj($ch->get_idaloj());
				$cl->get_cliente($ch->get_idcli());
				$noches=$ch->get_noches();
				
				if($ch->get_fecout_date()==null)
					$f2=$ch->get_fecfin_date();		
				else{
					$f2=$ch->get_fecout_date();
					$f2=mktime(0,0,0,date("m",$f2),date("d",$f2)-1,date("Y",$f2));
				}	
				$importe=$tempo->calculo_precio($ch->get_idaloj(),$ch->get_fecin_date(),$f2);
				
				$html.= '<tr class="t_row" id="'.$ch->get_idcheckin().'" style="cursor:pointer" onclick="xajax_addCheckinToFra(this.id, idFra)"  onMouseOver="this.className=\'selected\'" onMouseOut="this.className=\'\'">' .
					'<td class="t_col">'.$aloj->get_nombre().'</td>' .
					'<td class="t_col" style="text-align:center">'.$ch->get_fecin().'</td>' .
					'<td class="t_col" style="text-align:center">'.$ch->get_fecout().'</td>' .				
					'<td class="t_col" style="text-align:right">'.$noches.'</td>' .
					'<td class="t_col" style="text-align:right">'.$importe.'</td>' .
					'<td class="t_col" style="text-align:center">'.$cl->getNombre().'</td>' .
					'<td class="t_col" style="text-align:center">'.$cl->getApellido1().' '.$cl->getApellido2().'</td>' .			
					'</tr>';
			}while($ch->movenext());
		}
		$html.='</table>';
			
		$objResponse->addAssign("ListaCheckins","innerHTML",$html);
		
		return $objResponse;
	}
	
	function loadFrasOpened(){
		$objResponse = new xajaxResponse('ISO-8859-1');
		$fra = new factura();
			
		$fra->get_facturas_abiertas();
		
		$html= '<table class="t_general fondo_tabla" style="width:100%">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:85px">&nbsp;</td>' .
					'</tr>'; 
		if($fra->fra_get_count()){
			do{
								
				$html.= '<tr class="t_row" id="fra_'.$fra->get_id_fra().'" style="cursor:pointer" onclick="selectFra(this.id)" >' .
					'<td class="t_col">'.$fra->get_id_fra().'</td>' .
					'</tr>';
			}while($fra->fra_movenext());
		}
		$html.='</table>';
			
		$objResponse->addAssign("ListaFras","innerHTML",$html);
		
		return $objResponse;
	}
	
	function addCheckinToFra($idCheck, $idFra){
		$fra = new factura();
		$ch=new checkinres();
		$aloj=new alojamiento();
		$tempo=new temporada();
		$objResponse = new xajaxResponse('ISO-8859-1');	
		
		$ch->get_checkin($idCheck);
		$aloj->get_aloj($ch->get_idaloj());
		
		if($idFra==0){
				$idFra=$fra->insert_factura($ch->get_idcli(),$pagado,true,$fecfra,0,$nombre_completo,0,$checkout['nit'],$checkout['numfra']);
		}
		else{
				//$fra->actualizar_pagado($pagado,$this->last_idfra);
		}
		$ch->set_idfra($idFra,$idCheck);
		
		$descrip=$aloj->get_nombre().'. '.$aloj->get_tipo();

		if($ch->get_fecout_date()==null)
			$f2=$ch->get_fecfin_date();		
		else{
			$f2=$ch->get_fecout_date();
			$f2=mktime(0,0,0,date("m",$f2),date("d",$f2)-1,date("Y",$f2));
		}	
		$importe=$tempo->calculo_precio($ch->get_idaloj(),$ch->get_fecin_date(),$f2);
		$noches=$ch->get_noches();
		
		$fra->insert_linea($idFra,$noches,$descrip,$importe,0,0,$idCheck);
		
		$objResponse->addScript("xajax_loadCheckins(); xajax_loadLineasFra($idFra)");
		return $objResponse;
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
					'<td class="t_col" style="width:150px">Descripción</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Recargo</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Descuento</td>' .
					'<td class="t_col" style="width:60px; text-align:center">Importe</td>' .
					'</tr>';
		if($res>0)
		do{
				$html.= '<tr class="t_row" style="cursor:pointer" id="'.$fra->get_id_linea().'" onClick="xajax_elimLineaFromFra(idFra, this.id)">' .
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
		
		$objResponse->addScript("idFra=$idFra;");
		
		$objResponse->addAssign("linea_id_fra","value",$idFra);	
		$objResponse->addAssign("imptotal","value",$total);	
		$objResponse->addAssign("editLineas","innerHTML",$html);	
		$objResponse->addAssign("acuent","value",$pagado);
		return $objResponse;
	}

	function elimLineaFromFra($idFra, $idLinea){
		$fra = new factura();
		$ch=new checkinres();

		$objResponse = new xajaxResponse('ISO-8859-1');	
		
		$fra->get_linea($idFra, $idLinea);
	
		$idCheck=$fra->get_id_checkin();
		$res=$ch->set_idfra(0,$idCheck);
		if($res>0)
			$fra->eliminar_linea($idFra,$idLinea);
		
		$objResponse->addScript("xajax_loadCheckins(); xajax_loadLineasFra($idFra)");
		return $objResponse;
	}
	
	function closeFactura($data){
		$fra=new factura();
				
		$idfra=$data['id_fra'];
		
		$sesion=new session();
		$log=new log();
		$log->insertar_log($sesion->get_id_usuario(), log::$CERRAR_FRA, $idfra);
		
		$nit=$data['nit'];
		$nombre=$data['nombre'];
		$numfra=$data['numfra'];
		$impuesto=$data['impuesto'];
		$total=$data['importe_total'];
		$apag=$data['apag'];
		$fecha=$data['fechafra'];
		
		$nuevopagado=0;
		$res=$fra->cerrar_fra($nombre,$nit,$numfra,$impuesto,$total,$nuevopagado,$idfra,$fecha);
		
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addScript("GB_showCenter('Factura', '/view.php?page=message_box&opc=".factura::$ID."&result=".factura::$OK."',100,300)");
		return $objResponse;
	}
	
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/factura.xajax.req.php');
$xajax->processRequests();
?>