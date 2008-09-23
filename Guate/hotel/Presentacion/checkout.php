<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_cliente.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_checkinres.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;

$idcliente=0; $num_pax=0; $messagebox=""; $loadocup=""; $idfra=0;
$idCheck=0;
$habitacion="";


if($_POST!=null){
	
	
	switch ($_POST['modo']){
							// procedencia: checkin.php
		case "crear":		// crear un checkout 
			$idCheck=$_POST['id_ev'];
	
			$check = new checkinres();
			$check->get_checkin($idCheck);
						
			$imp_res=$check->get_res_imp_pagado();
			$imp_pag=$check->get_imp_pagado()+$imp_res;
		
			$idfra=$check->get_idfra();		
			
			$loadocup="xajax_load_ocupantes($idCheck);";
		
			$habitacion=htmlentities($nombre. " . ".$tipo);
			
			/*$temp=new temporada();
			$noches=$temp->diff_days($feclleg,$fecout)+1;
			$preu=$temp->calculo_precio($idaloj,mktime(0,0,0,$mes,$dia,$any),$fecout);*/
						
		break;
							
		}
}


$mostrarlineas="xajax_loadLineasFra($idfra);";





require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax.req.php');
$xajax->printJavascript('xajax/'); 


$pos = "<script>document.write(posi_act)</script>";

?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
		<title></title><!-- Meta Information -->
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="MSSmartTagsPreventParsing" content="true">
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	
		<link href="css/estilo.css" rel="stylesheet" type="text/css" />
		
		<script src="scripts/nav.js"></script>	

	<link href="scripts/calendar-green.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/calendar.js"></script>
	<script type="text/javascript" src="scripts/calendar-sp.js"></script>
	<script type="text/javascript" src="scripts/calendar-setup.js"></script>
	
	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	
	
	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	
	<script>
	var	idaloj=<?php print max(0,$idaloj) ?>;
	var err_fra=<?php print checkinres::$ERR_FRA ?>;
	var class_id=<?php print checkinres::$IDOUT ?>;
	
		function confirmarCheckOut(){
			calcularsubt();
			
			document.FormDataCheckOut.fec_fin.value=document.datoscheckout.date_b.value;
			document.FormDataCheckOut.valor.value=document.datoscheckout.subt.value;
			document.FormDataCheckOut.descuento.value=document.datoscheckout.dto.value;
			document.FormDataCheckOut.recargo.value=document.datoscheckout.recargo.value;
			// document.FormDataCheckOut.importe_pagado.value=f1.imp_pag.value;
			if(modo_pago.pago[0].checked)
				document.FormDataCheckOut.modopago.value=1;
			else
				document.FormDataCheckOut.modopago.value=2;

			xajax_makecheckout(xajax.getFormValues("FormDataCheckOut"));
			
		//	GB_showCenter('Error', '/view.php?page=message_box&opc='+class_id+'&result='+err_fra,100,300);			
		}
			
			
		function formCliDisabled(value) {
	    var limit = document.FormCliente.elements.length;
	    for (i=0;i<limit;i++) {
	      document.FormCliente.elements[i].disabled = value;
	      if(value){
	      		document.FormCliente.elements[i].style.background="#D3D3D3";
	      }
	      else
	      		document.FormCliente.elements[i].style.background="#ffffff";
	    	}
	    }	
	  	   	  
	   function clienteNuevo(opc){
	  	   	if(opc){
	   			document.getElementById("botones1").style.display = "none";
	   			document.getElementById("botones2").style.display = "";
	   	
	   			document.FormCliente.reset(); 
	   			formCliDisabled(false); 
	   			document.FormCliente.cli_data_nombre.focus(); 
	   	
	  		 }
	   		else{
	   			document.getElementById("botones1").style.display = "";
	   			document.getElementById("botones2").style.display = "none";
	   			formCliDisabled(true); 
	   			xajax_loadCli(multiclient[posi_act]);
	   		}
	   }
	   
	   
	   function elegido(valor,texto){
	    
	    	document.getElementById("elect").value=texto;
  	   		document.FormDataCheckOut.id_checkin.value=valor;
  	   		document.FormDataCheckOut.descripcion.value=texto;
	   		xajax_load_ocupantes(valor);
	   }
	      
	   function calcularsubt(){
	   		datos=document.datoscheckout;
	   		aloj=parseFloat(datos.aloj.value);
			recargo=parseFloat(datos.recargo.value);
			dto=parseFloat(datos.dto.value);
			pagado=parseFloat(datos.pagado.value);
			
	   		subt=aloj+recargo-dto-pagado;
	   		
	   		if(isNaN(subt)){
	   			datos.subt.value=0;
	   		}
	   		else{
	   		datos.subt.value=subt.toFixed(2);
	   		}
	   		calcVisa();
	   }
	   
/*	   	function calcularfra(){
			datos=document.DatosFra;
			imptotal=parseFloat(datos.imptotal.value);
			acuent=parseFloat(datos.acuent.value);
			totalfra=imptotal;
			
				if(isNaN(totalfra)){
					datos.totalfra.value=0;
				}
				else{
					datos.totalfra.value=totalfra;
				}
				totalapag=parseFloat(datos.totalfra.value)-acuent;
				
				if(isNaN(totalapag)){
					datos.apag.value=0;
				}
				else{
					datos.apag.value=totalapag;
				}
			
		}
*/  
  		var modo=0;
	  	function optionLineas(opc){
			if(opc==1 || opc==2){	//añadir-modificar
				if(opc==1)
					document.getElementById('FormLineas').reset();	
				document.getElementById('linea_edit').style.display="";
				modo=opc;  
				showButtons('b5', 'b6');
			}
			else if(opc==3){	//eliminar
				modo=3; 
				xajax_changeLineas(xajax.getFormValues('FormLineas'), modo);
			}	
		}
		
		function showButtons(b1, b2){
	   		document.getElementById(b1).style.display = "none";
			document.getElementById(b2).style.display = "";
		}
		
		function copyRowToEdit(idrow, idEditRow){
			row=document.getElementById(idrow);
			for(j=0; j<row.childNodes.length; j++){
				document.getElementById(idEditRow).cells[j].firstChild.value=row.cells[j].innerHTML;
			}	
		}
		
		var idLineaLast="";
		function selectLinea(idLinea){
			if(idLineaLast.length>0)
			document.getElementById(idLineaLast).className="";
			document.getElementById(idLinea).className="selected";
			idLineaLast=idLinea;
			id=idLinea.split("_");
			document.getElementById("linea_id").value=id[1];	
			copyRowToEdit(idLinea,'linea_edit' );
		}
		
		
		function importe(c1,c2){
		imp=c2*(c1/100);
		imp=imp.toFixed(2);
		return imp;
		}
		
		function porcentaje(c1,c2){
		imp=(c1/c2)*100;
		imp=imp.toFixed(2);
		return imp;
		}
			
		function calcVisa(){
	   		var importe=document.getElementById('subt').value;
	   		document.getElementById('recargoVisa').value=(importe*0.1).toFixed(2);
	   		document.getElementById('total').value=(importe*1.1).toFixed(2);
	   }
	   
	   function showVisa(show){
	   		if(show){
	   			calcVisa();
	   			document.getElementById('visaform').style.display='';
	   		}
	   		else
	   			document.getElementById('visaform').style.display='none';
	   }	
		
		
</script>

</head>

<body onLoad="xajax_load_checkoutpre(<?php print $idCheck ?>);<?php print $messagebox; print $mostrarlineas; print $loadocup;?>">
<div id="base">


<?php include('menu.php'); ?>


<div id="principal">
	<h5 class="titulos">Realizar Check-Out </h5>
	
		<div class="box_amarillo" style="height:225px; width: 230px; margin-top:20px">
		<div><span class="label"><b>Datos Check-Out</b></span>
		<p>&nbsp;</p></div>
	 		<form id="datoscheckout" name="datoscheckout">
	 		
	 		<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Fecha Llegada:</span></div>
			<div style="float:right"><span><input type="text" name="date_a" id="f_date_c" size="9" value="" disabled="true"/> 
			</span></div>
   			</div>
    		
			<div class="row" align="left">
     		<div style="width:120px;float:left"><span>Fecha Salida:</span></div>
     		<div style="float:right"><span><input type="text" name="date_b" id="f_date_c1" size="9" value="<?php print $fechasalida?>" disabled="true"/>
			</span></div>
   			</div>

    		<div class="row" align="left">
      		<div style="width:90px;float:left"><span>Alojamiento:</span></div>
      		<div style="float:right"><span><input type="text" id="elect" size="20" value="<?php print $habitacion ?>" disabled="true"/></span></div>
   			</div>
   			   			
    		<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Importe:</span></div>
      		<div style="float:right"><span><input type="text"  name="aloj" id="alojam" style="text-align:right" value="" size="9" disabled="true"></span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Importe Pagado:</span></div>
      		<div style="float:right"><span><input type="text"  name="pagado" id="pagado" style="text-align:right" value="" size="9" disabled="true"></span></div>
   			</div>
  			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Recargo:</span></div>
      		<div style="float:right"><span><input type="text" name="recargo" value="0" style="text-align:right" size="9" onChange="calcularsubt()"></span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Descuento:</span></div>
      		<div style="float:right"><span><input type="text" name="dto" value="0" size="9" style="text-align:right" onChange="calcularsubt()"></span></div>
   			</div>
			<div class="row" align="left">
			<div style="width:120px;float:left"><span>Importe a Pagar:</span></div>
			<div style="float:right"><span><input type="text" name="subt" id="subt" style="text-align:right" value="" size="9" disabled="true"></span></div>
   			</div>
	 		
	 		<div id="visaform" style="display:none">
	   			<div class="row" align="left">
	      		<div style="width:120px;float:left"><span>Recargo Visa:</span></div>
	      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="recargoVisa" value="" disabled="true"/></span></div>
	   			</div>
	   			<div class="row" align="left">
	      		<div style="width:120px;float:left;color:red"><span>Total a Pagar:</span></div>
	      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="total" value="" disabled="true"/></span></div>
	   			</div>
   			</div>
   			
	 		</form>
	 		
		</div>

		<div class="box_amarillo" style="width:230px; height:40px; margin-top:5px;">
			<div><span class="label"><b>Forma de Pago:</b></span><p>&nbsp;</p></div>
			<form id="modo_pago" name="modo_pago">
				<div style="cursor: pointer; float:left; margin-left:20px;" onClick="showVisa(false); this.firstChild.checked=true"><input type="radio" id="pago" name="pago" value="contado" onClick="showVisa(false)" checked />&nbsp;Contado&nbsp;&nbsp;</div>
				<div style="cursor: pointer; float:right; margin-right:20px;" onClick="showVisa(true); this.firstChild.checked=true"><input type="radio" id="pago" name="pago" value="tarjeta" onClick="showVisa(true)" />&nbsp;Tarjeta</div>
			</form>
		</div>
		
		<div class="box_amarillo" style="width: 400px;height:140px; margin-top:20px">
				
			<div style="float:left">	
			<div><span class="label"><b>Check Out Previstos</b></span></div>
			<div id="checkoutpre"></div>
			</div>
			
			<div style="float:right; margin-right:10px">
			<div><span class="label"><b>Huespedes</b></span></div>
	 				<div>
			 		<form name="ocup">
			 		<select name="listocup" id="ocupantes" size="7" style="width:150px" disabled="true">
			 		</select>
			 		</form>
	 				</div>
			</div>
		
		</div>


		<div class="row">
			<div style="width:200px; position: relative; float:left; margin-top:10px">
			<div style="float:left"><span><input type="button" style="height: 25px; width:145px" value="Check-Out" onClick="confirmarCheckOut()"/></span></div>
			</div>
		</div>
</div>




<div id="secundario">
	<h5 class="titulos">&nbsp</h5>
	<div id="datos_pers">
			
				
	<form id="FormDataCheckOut" name="FormDataCheckOut" action="/view.php?page=checkout" method="post">		
		
			<input type="hidden" id="id_checkin" name="id_checkin" value="<?php print $idCheck ?>"/>
    		<input type="hidden" id="fec_fin" name="fec_fin" value=""/>
    		<input type="hidden" name="recargo" value=""/>	
    		<input type="hidden" name="descuento" value=""/>	
    		<input type="hidden" name="impuesto" value=""/>	
    		
    		
    		<input type="hidden" id="modopago" name="modopago" value=""/>
    		<input type="hidden" id="apag" name="apag" value=""/>
    		
    		
    		<input type="hidden" id="acuent" name="pagado" value=""/>
    		<input type="hidden" id="id_fra" name="id_fra" value="<?php print $idfra ?>"/>
    		
    		<input type="hidden" id="descripcion" name="descripcion" value="<?php print $nombre.".".$tipo ?>"/>
    		<input id="cant" type="hidden" name="cantidad" value=""/>
    		<input type="hidden" name="valor" value=""/>
    		<input type="hidden" name="total" value=""/>
      </form>


</div>
</div>
</body>
</html>