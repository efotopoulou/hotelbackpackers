<?php

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;
     
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_cliente.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_checkinres.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_caja.php');
require($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_alojamiento.php');


$idcliente=0; $num_pax=0; $messagebox="";

$checkprev = new checkinres();
$checkprev->get_checkprev();

if($_POST!=null){
	
	
	switch ($_POST['modo']){
							// procedencia: calendario.php
		case "crear":		// crear un checkin habiendo hecho click en una reserva
			$res=$_POST['id_ev'];
			$ev = new eventos();
			$ev->get_reserva($res);
			$fecfin = $ev->get_res_fecfin();
			$fec2= split("/",$fecfin);
			$fecfin = split("/",$fecfin);
			$fecfin = mktime(0,0,0,(int)$fecfin[1],(int)$fecfin[0]+1,(int)$fecfin[2]);
			$fecfin = date("d/m/Y", $fecfin);
			$pag_res=$ev->get_res_imp_pagado();
			
			$aloj = $ev->get_res_id_aloj();			
			$habit = new alojamiento();
			$habit->get_aloj($aloj);
			$hab = $habit->get_nombre();
			$tipo = $habit->get_tipo();
						
			$num_pax = ($habit->get_num_matrim()*2)+$habit->get_num_indiv();
			$idcliente = $ev->get_res_id_cliente();
			
						
			$preu = new temporada();
		
			$total = $preu->calculo_precio($aloj,mktime(0,0,0,date("m"),date("d"),date("Y")), mktime(0,0,0,(int)$fec2[1],(int)$fec2[0],(int)$fec2[2]));

		break;
							// procedencia: checkinres.php
		case "insertar":	// insertar los datos de un checkin nuevo en la bd
			$resbox=$checkprev->make_checkinres($_POST);
		
			if($resbox>0){
				$idcheck=$checkprev->get_idcheckin();
				$caja = new caja();
				if($_POST['modopago']==1){
					$modo=caja::$CONTADO;
				}
				else{
					$modo=caja::$VISA;
				}
				$caja->insert_movimiento($_POST['importe_pagado'],$modo,$idcheck,0,"checkin");
				$log=new log();
				$log->insertar_log($sesion->get_id_usuario(), log::$INS_CHECKIN, $idcheck);
			}
				//insertar el movimiento del check-in en la bd del restaurante
				$idencargado=$sesion->get_id_usuario();
				$alojamiento = new alojamientoRes();
			  $alojamiento->insert_checkinmov($idcheck,$idencargado);
			
			$messagebox="GB_showCenter('Check-in', '/hotel/view.php?page=message_box&opc=".checkinres::$ID."&result=".$resbox."',100,300)";
			$checkprev->get_checkprev();
			break;
	}
}

  	function checkpre($ch,$idcli){
  		print "<form name='sel'>";
  		
  		print "<select name='chprv' size='10' style='width:97%' onchange='elegido()'>";
  		$hab = new alojamiento();
  		  		
  		$ch->movefirst();
  		if($ch->get_count())
  		do{
  			$cli = new cliente();
			$cli->get_cliente($ch->get_idcli());
			$nom_cli=$cli->getNombre()." ".$cli->getApellido1();
			if(strlen($cli->getApellido2())>0)
				$nom_cli.=" ".$cli->getApellido2();
			
			$idaloj=$ch->get_idaloj();
			$hab->get_aloj($idaloj);
  			
  			$nom_aloj=str_pad($hab->get_nombre(),10,"-",STR_PAD_LEFT) ." . ". $hab->get_tipo();
  			
  			$option_text=str_pad($nom_cli,20,"-").$nom_aloj;
  			//$option_text=str_replace(" ","&nbsp;    ",$option_text);
  			
			if($idcli==$ch->get_idcli()){
  				$sel="selected";	
  			}
  			else{
  			$sel="";		
  			}
			print "<option value='".$ch->get_idres()."' ".$sel.">".htmlentities($option_text)."</option>";
       	}while($ch->movenext());
		print "</select>";
		print "</form>";
  	}
	


require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax.req.php');
$xajax->printJavascript('xajax/'); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<meta name="MSSmartTagsPreventParsing" content="true">
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
	
	<script src="scripts/nav.js"></script>	

	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	
	
	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	
	<script>
	
	
		function confirmarCheck(){
		grupo = multiclient.join();
		document.FormDataCheckRes.grupoclient.value=grupo;
		document.FormDataCheckRes.importe_pagado.value=f1.imp_pag.value;
		if(modo_pago.pago[0].checked)
			document.FormDataCheckRes.modopago.value=1;
		else
			document.FormDataCheckRes.modopago.value=2;
		document.FormDataCheckRes.submit();
		}
		
		multiclient = new Array();
		posi_act=0;
		n_pers=<?php print max($num_pax,1) ?>;
		multiclient[1]=<?php print max($idcliente,0) ?>;	
			
		function moverCursorCli(inc){	
			
			posi_act = posi_act+inc;
			document.getElementById('visor').innerHTML=posi_act+"/"+n_pers;
			document.getElementById("FormCliente").reset();
			   
		   	document.getElementById('ant').disabled = false;
	   		document.getElementById('sig').disabled = false;	
			if(posi_act==1){
	   			document.getElementById('ant').disabled = true;
	   		}
	   		if(posi_act==n_pers){
	   			document.getElementById('sig').disabled = true;
	   		}
	   		if(multiclient[posi_act]!=undefined){
				xajax_loadCli(multiclient[posi_act]);
			}	
		}
		
		function call_back_buscarCli(idCli, gbHide){	
	       	document.getElementById('FormCliente').cli_data_id.value=idCli;
	       	multiclient[posi_act]=idCli;
	       	xajax_loadCli(idCli,gbHide);
	   	}
	   
	   function elegido(){
	   		var elect=document.sel.chprv.selectedIndex; 
	   		var valor=parseInt(document.sel.chprv.options[elect].value);
	   		xajax_load_chepr(valor);
	   }
	   
	   function calcVisa(){
	   		var importe=document.getElementById('imp_pag').value;
	   		document.getElementById('recargo').value=(importe*0.1).toFixed(2);
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
	   
	   function bodyOnload(){
			formCliDisabled(true);
		<?php 
			if(strlen($messagebox))
				print $messagebox;
			else
				print 'moverCursorCli(1);';	
		?>
		}
</script>

</head>

<body onLoad="bodyOnload()">
<div id="base">


<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principal">
<h5 class="titulos">Realizar Check-in</h5>		
					
		<div class="box_amarillo" style="height:185px; width: 230px; margin-top:20px">
		<div><span class="label"><b>Datos Check-In</b></span>
		<p>&nbsp;</p></div>
	 		<form name="f1">
  			<div class="row" align="left">
      			<div style="width:120px;float:left"><span>Fecha Llegada:</span></div>
				<div style="float:right"><input type="text" name="date_a" id="f_date_c" size="9" value="<?php print date("d/"."m/"."Y"); ?>" disabled="true"/></div> 
			</div>
    		
			<div class="row" align="left">
     			<div style="width:120px;float:left"><span>Fecha Salida:</span></div>
				<div style="float:right"><input type="text" name="date_b" id="f_date_c1" size="9" value="<?php print $fecfin;?>" disabled="true"/></div>
			</div>
			
			<div class="row" align="left">
      		<div style="width:80px;float:left"><span>Habitaci�n:</span></div>
      		<div style="float:right"><span><input type="text" size="20" id="hab" value="<?php print $hab." ".$tipo;?>"/ disabled="true"></span></div>
   			</div>
   			
   		
			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Importe:</span></div>
      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="importe" value="<?php printf("%.2f",$total);?>" disabled="true"/></span></div>
   			</div>
   			
   			
   			<div class="row" align="left">
      		<div style="width:120px;float:left">Deposito Reserva:</span></div>
      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="pag_res" name="pag_res" value="<?php printf("%.2f",$pag_res); ?>" disabled="true"/></span></div>
   			</div>
    		<div class="row" align="left">
      		<div style="width:120px;float:left">Pagado ahora:</span></div>
      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="imp_pag" name="imp_pag" value="0" onChange="calcVisa()"/></span></div>
   			</div>
   			
   			<div id="visaform" style="display:none">
	   			<div class="row" align="left">
	      		<div style="width:120px;float:left"><span>Recargo Visa:</span></div>
	      		<div style="float:right"><span><input type="text" size="9" style="text-align:right" id="recargo" value="" disabled="true"/></span></div>
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
				<div style="cursor: pointer; float:left; margin-left:20px;" onClick="this.firstChild.checked=true; showVisa(false)"><input type="radio" id="pago" name="pago" value="contado" onClick="showVisa(false)" checked />&nbsp;Contado&nbsp;&nbsp;</div>
				<div style="cursor: pointer; float:right; margin-right:20px;" onClick="this.firstChild.checked=true; showVisa(true)"><input type="radio" id="pago" name="pago" value="tarjeta" onClick="showVisa(true)" />&nbsp;Tarjeta</div>
			</form>
			</div>
		
		
		<div class="box_amarillo" style="width: 350px; margin-top:20px">
		
		<div><span class="label"><b>Checkin Previstos</b></span>
		<p>&nbsp</p></div>
		<?php checkpre($checkprev,$idcliente)?>
		
		</div>
			
</div>

<div id="secundario">
<h5 class="titulos">&nbsp;</h5>
		
<?php include('cliente_form.php'); ?>
	 
	<div style="width:80px; float:right;margin-right:90px; margin-top:5px">
		<div style="float:left">
		<input id="ant" type="button" value="<" onclick="moverCursorCli(-1)" disabled/>
		<input id="sig" type="button" value=">" onclick="moverCursorCli(+1)"/>
		</div>
	
		<div id="visor" style="float:right">			
		1/<?php print $num_pax ?>
		</div>
	</div>
	
	<div style="width:200px; position: relative; float:left; margin-top:30px">
	<input type="button" id="mainbutton" style="height: 25px; width:145px" value="Check In" onClick="confirmarCheck()"/>
	</div>
	
	<form name="FormDataCheckRes" action="view.php?page=checkinres" method="post">		
			<input type="hidden" name="modo" value="insertar"/>
    		<input type="hidden" id="id_res" name="id_res" value="<?php print $res ?>"/>
    		<input type="hidden" id="id_cliente" name="id_cliente" value="<?php print $idcliente ?>"/>	
       		<input type="hidden" name="importe_pagado" value=""/>
       		<input type="hidden" name="grupoclient" value=""/>
       		<input type="hidden" id="modopago" name="modopago" value=""/>
    </form>
    
</div>
</div>
</body>
</html>

