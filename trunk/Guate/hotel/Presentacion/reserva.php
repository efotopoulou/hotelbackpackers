<?php

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_caja.php');

$aloj_elect=''; $idres=0;
	
if($_POST!=null){
	switch ($_POST['modo']){
							// procedencia: calendario.php
		case "crear":		// crear una reserva habiendo hecho click en un dia libre del calendario
			$id_aloj=$_POST['id_habit'];
			$fecini=$_POST['fecha_ini'];
			$fecfin=$_POST['fecha_fin'];
			
			list ($d,$m,$y)=split("/",$fecini);
			$fecini_date=mktime(0,0,0,$m,$d,$y);				
			list ($d,$m,$y)=split("/",$fecfin);
			$fecout_date=mktime(0,0,0,$m,$d+1,$y);
			$fecout=date("d/m/Y",$fecout_date);
			
			$ev = new eventos();
			$num_noches=$ev->diff_days($fecini_date, $fecout_date);
		
				
		break;
							// procedencia: reserva.php
		case "insertar":	// insertar los datos de una reserva nueva en la bd
			$ev=new eventos();
			if($_POST['id_res']>0){		//anticipo deposito reserva 50%
				$resbox = $ev->update_imp_pagado($_POST['imp_pagado'],$_POST['id_res']);
				$caja = new caja();
				$caja->insert_movimiento($_POST['imp_pagado'],caja::$CONTADO,$_POST['id_res'],0,"deposito reserva");
			}
			else{
				$resbox=$ev->make_reserva($_POST);
				if($resbox>0){
					$idres=$ev->get_res_id();
					$precio_res=$ev->get_res_precio();
					$log=new log();
					$log->insertar_log($sesion->get_id_usuario(), log::$INS_RES, $idres);
				}
			}
			$messagebox="GB_showCenter('Clientes', '/hotel/view.php?page=message_box&opc=".eventos::$ID."&result=".$resbox."&precio=".$precio_res."',100,300)";
			
			
		break;
							// procedencia: reserva.php (Buscar Reservaciones)
		case "modificar": 	// actualizar datos de una reserva (insertar el importe pagado a cuenta)
			$idres= $_POST['id_res'];
			$ev=new eventos();
			$temp= new temporada();
			$ev->get_reserva($idres);
			$fecini=$ev->get_res_fecini();
			$fecfin=$ev->get_res_fecfin();
			$id_aloj=$ev->get_res_id_aloj();
			$id_cli=$ev->get_res_id_cliente();
			$imp_pagado=$ev->get_res_imp_pagado();
			
			
			
			list ($d,$m,$y)=split("/",$fecini);
			$fecini_date=mktime(0,0,0,$m,$d,$y);				
			list ($d,$m,$y)=split("/",$fecfin);
			$fecfin_date=mktime(0,0,0,$m,$d,$y);
			$fecout_date=mktime(0,0,0,$m,$d+1,$y);
			$fecout=date("d/m/Y",$fecout_date);
			
			$importe=$temp->calculo_precio($id_aloj,$fecini_date,$fecfin_date);
			
			$ev = new eventos();
			$num_noches=$ev->diff_days($fecini_date, $fecout_date);
			
			$habit=new alojamiento();
			$habit->get_aloj($id_aloj);
				
			$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
			$mouseEvent='loadDesc(\''.$habit->get_tipo().'\',\''.$camas.'\')';
			$aloj_elect='<li id="'.$habit->get_id().'" class="t_col_habit" style="background-color:'.$habit->get_color().'; margin:1px; cursor:pointer; width:95%;"onmouseover="'.$mouseEvent.'">'.$habit->get_nombre().'</li>';			
			$id_aloj=0;			
			
					
		break;
		
	}
	
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

	<link href="scripts/calendar-green.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/calendar.js"></script>
	<script type="text/javascript" src="scripts/calendar-sp.js"></script>
	<script type="text/javascript" src="scripts/calendar-setup.js"></script>

	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	<script>
		
	function change_fechas(wachi){
		fecha1=document.getElementById('f_date_c').value;
		fecha2=document.getElementById('f_date_c1').value;		

		v=fecha1.split("/");
		d=new Date();
		d.setDate(v[0]);	d.setMonth(v[1]-1);		d.setFullYear(v[2]);
		
		v=fecha2.split("/");
		d2=new Date();
		d2.setDate(v[0]);	d2.setMonth(v[1]-1);		d2.setFullYear(v[2]);

		m1=Date.parse(d.toUTCString());
		m2=Date.parse(d2.toUTCString());
		noches=(m2-m1)/(24*60*60*1000);
		if(isNaN(noches))
			noches='';		
		document.getElementById('noches').value=noches;
		
		if(d>=d2 && wachi==1){
			document.getElementById('f_date_c1').value="";
			document.getElementById('noches').value="";
		}
		else if(d>=d2 && wachi==2){
			document.getElementById('f_date_c').value="";
			document.getElementById('noches').value="";
		}

		v=fecha2.split("/");
		d2=new Date();
		d2.setMonth(v[1]-1);		d2.setFullYear(v[2]);	d2.setDate(v[0]-1);
		document.getElementById('fecfinres').value=d2.getDate()+'/'+(d2.getMonth()+1)+'/'+d2.getFullYear();
		
		<?php if($_POST['modo']!="modificar")
				echo "buscarDisponibilidad();";
		?>
	}
	
	function getIdAlojList(){
		var liList = document.getElementById("listaElegidas").getElementsByTagName("li");
		var alojList=new Array();
		
		for (var i=0; i<liList.length; i++) {
			alojList[i]=liList[i].id;
		}
		return alojList;
	}
	
	function confirmarRes(){
		
		var liList = document.getElementById("listaElegidas").getElementsByTagName("li");
		
		var alojList="";
		for (var i=0; i<liList.length; i++) {
			alojList+=liList[i].id+",";
		}
		document.FormDataRes.id_cliente.value=document.getElementById('FormCliente').cli_data_id.value;
		document.FormDataRes.id_alojs.value=alojList;
		document.FormDataRes.fec_ini.value=f1.date_a.value;
		document.FormDataRes.fec_fin.value=document.getElementById('fecfinres').value;
		
		if(f1.imp_pag)
		document.FormDataRes.imp_pagado.value=f1.imp_pag.value;
		if(control_errores()){
		document.FormDataRes.submit();
		}
	}
	
	var idParentOld=0;
	function showLiteras(idParent){	
		if(document.getElementById('listaCamasDiv').style.display == ""){			
			if(idParentOld!=idParent){
				document.getElementById(idParentOld).style.backgroundImage="url(/img/arrow_r.gif)";
				document.getElementById(idParent).style.backgroundImage="url(/img/arrow_l.gif)";
			}
			else{
				document.getElementById('listaCamasDiv').style.display="none";
				document.getElementById('listaCamasDisp').innerHTML='';
				document.getElementById(idParent).style.backgroundImage="url(/img/arrow_r.gif)";
			}
		}
		else{
			document.getElementById('listaCamasDiv').style.display="";
			document.getElementById(idParent).style.backgroundImage="url(/img/arrow_l.gif)";	
		}
		idParentOld=idParent;
	}
	
	function loadDesc(descHabit,descCamas){
		document.getElementById('descHabit').innerHTML="tipo de habitación: "+descHabit;
		document.getElementById('descCamas').innerHTML="número de camas: "+descCamas;
	}
	
	function call_back_buscarCli(idCli,gbHide){	
	    document.getElementById('FormCliente').cli_data_id.value=idCli;
	    xajax_loadCli(idCli,gbHide);	    
	}

	function call_back_buscarRes(idRes,gbHide){	
	    document.getElementById('FormDataRes').modo.value='modificar';	 
	    document.getElementById('FormDataRes').id_res.value=idRes;
	    document.getElementById('FormDataRes').submit();   
	}
	
	function buscarDisponibilidad(){
		document.getElementById('listaCamasDiv').style.display='none';
		document.getElementById('listaElegidas').innerHTML='';
		xajax_loadFreeRooms(document.getElementById('f_date_c').value, document.getElementById('fecfinres').value);
	}
	
	
	var id_clase=<?php echo eventos::$ID ?>;
	var err_cliente=<?php echo eventos::$ERR_CLI ?>; 
	var err_fecha=<?php echo eventos::$ERR_FECH ?>; 
	var err_aloj=<?php echo eventos::$ERR_ALOJ ?>; 

	
	function control_errores(){
		fecini=document.getElementById('fec_ini').value;
		fecfin=document.getElementById('f_date_c1').value;
		idcliente=document.getElementById('id_cliente').value;
		idalojs=document.getElementById('id_alojs').value;
		
		if(fecini.length<10 || fecfin.length<10){
			id_error=err_fecha;
		}		
		else if(idcliente==0){
			id_error=err_cliente;
		}
		else if(idalojs==""){
			id_error=err_aloj;
		}
		else{
			return true;
		}
		
		GB_showCenter('Error', '/hotel/view.php?page=message_box&opc='+id_clase+'&result='+id_error,100,300);
	
		return false;	
	}
	
	
	function bodyOnload(){
		formCliDisabled(true);
		<?php if($id_aloj) echo 'xajax_loadFreeRooms(f1.date_a.value, document.getElementById("fecfinres").value,0 ,'.$id_aloj.');'; ?>
		<?php print $messagebox; ?>
		<?php if($id_cli) echo 'xajax_loadCli('.$id_cli.',false)'; ?>
	
	}
	</script>
</head>
<body onLoad="bodyOnload()">

<div id="base">
<?php include('menu.php'); ?>

<div id="principal">
	<h5 class="titulos">Realizar Reservación</h5>
	 	<div class="box_amarillo" style="width: 350px; margin-top:20px">
  			<form name="f1">
  			
  			
  			<div class="row" align="left"  style="height:25px;width:235px">
      			<div style="width:120px;float:left"><span>Fecha Llegada:</span></div>
				<div style="float:left"><input type="text" name="date_a" id="f_date_c" maxlength="10" size="9" onchange="change_fechas(1)" value="<?php print $fecini; ?>" disabled="true"/></div> 
			
			<?php if($_POST['modo']!="modificar"){
  			echo '	<div style="float:right"><img src="img/calendar.gif" align="absbottom"; id="f_trigger_c"; title="Date Selector"; style="cursor:pointer; border:1px solid green">
				<script type="text/javascript">	
				Calendar.setup({
				inputField : "f_date_c",
				ifFormat : "%d/%m/%Y",
				daFormat : "%d/%m/%y",
				firstDay: 1,
				button : "f_trigger_c",
				align : "Tr",
				singleClick : true
				});
				</script>
				</div>';
   			}
   			?>
  			</div>
  			
			<div class="row" align="left" style="height:25px;width:235px">
     			<div style="width:120px;float:left"><span>Fecha Salida:</span></div>
				<div style="float:left"><input type="text" name="date_b" id="f_date_c1" maxlength="10" size="9" onchange="change_fechas(2)" value="<?php print $fecout; ?>" disabled="true"/></div>
  			
  			<?php if($_POST['modo']!="modificar"){
  			echo'  <div style="float:right"><img src="img/calendar.gif" align="absbottom"; id="f_trigger_c1"; title="Date Selector"; style="cursor:pointer; border:1px solid green">			
				<script type="text/javascript">	
				Calendar.setup({
				date: document.f1.date_a.value,
				inputField : "f_date_c1",
				ifFormat : "%d/%m/%Y",
				daFormat : "%d/%m/%y",
				firstDay: 1,
				button : "f_trigger_c1",
				align : "Tr",
				singleClick : true
				});
				</script>			
				</div>';
  			}
   			?>
   					
   					
   			<div style="float:left"><input type="hidden" name="fecfinres" id="fecfinres" maxlength="10" size="9" value="<?php print $fecfin;?>"/></div>
   			</div> 

			<div class="row" align="left" style="height:25px;width:235px">
     			<div style="width:120px;float:left"><span>Noches:</span></div>
				<div style="float:left"><input type="text" name="noches" id="noches" maxlength="10" size="9" value="<?php print $num_noches;?>" disabled="true"/></div>
			</div>		
			
			<?php if($_POST['modo']=="modificar")
						echo '
						<div class="row" align="left" style="height:25px;width:235px">
			     			<div style="width:120px;float:left"><span>Importe:</span></div>
							<div style="float:left"><input type="text" name="importe" id="importe" maxlength="10" size="9" value="'.$importe.'" disabled="true"/></div>
						</div>
						
						<div class="row" align="left">
			      		<div style="width:120px;float:left">Importe Pagado:</span></div>
			      		<div><span><input type="text" size="9" name="imp_pag" value="'.$imp_pagado.'"/></span></div>
			   			</div>';
			?>
				
			
			
    		</form>
		</div>
		<input type="button" value="Buscar Reservaciones" style="margin-left:10px" onclick="return GB_showCenter('Reservaciones', '/hotel/view.php?page=slgrid&src=reservas' ,200, 600)"/>	
	
		 
	 <div class="box_amarillo" style="width:400px; margin-top:20px">
		
			<div style="height:250px;">
				<div style="float:left;border-style:solid;border-width:1px;overflow-y:auto;">
					<div align="center">Disponibles</div>
					<ul id="listaAlojDisp" style="height:235px;width:85px;">
				 	</ul>
				</div>
			
				<div id="listaCamasDiv" style="float:left;border-style:solid;border-width:1px;overflow-y:auto;display:none">
					<div align="center">Camas</div>
					<ul id="listaCamasDisp" style="height:235px;width:85px">
				 	</ul>
				</div>
			
				<div style="float:left;border-style:solid;border-width:1px;">
			 		<div align="center">Elegidas</div>
			 		<ul id="listaElegidas" style="height:235px;width:85px;">
				 	<?php print $aloj_elect ?>
				 	</ul>
				</div>
			</div>

			<div id="descHabit">tipo de habitación:</div>
			<div id="descCamas">número de camas:</div>

	 </div>
	 
</div>

<div id="secundario">
<h5 class="titulos">&nbsp;</h5>
	
<?php include('cliente_form.php'); ?>
			
		<form name="FormDataRes" id="FormDataRes" action="view.php?page=reserva" method="post">		
			<input type="hidden" id="modo" name="modo" value="insertar"/>
			<input type="hidden" id="id_res" name="id_res" value="<?php print $idres ?>"/>
    		<input type="hidden" id="id_cliente" name="id_cliente" value="0"/>
    		<input type="hidden" id="id_alojs" name="id_alojs" value=""/>
    		<input type="hidden" id="fec_ini" name="fec_ini" value=""/>		
  			<input type="hidden" id="fec_fin" name="fec_fin" value=""/>
  			<input type="hidden" name="fec_res" value="<?php print date("d/"."m/"."Y"); ?>"/>
  			<input type="hidden" name="observaciones" value=""/>
  			<input type="hidden" id="imp_pagado" name="imp_pagado" value="0"/>
		</form>

<div style="width:200px; position: relative; float:left; margin-top:30px">
<input style="height: 25px; width:145px" type="button" id="mainbutton" value="Confirmar Reserva" onClick="confirmarRes()" style="margin-left:10px; margin-top:20px;"/>
</div>

</div>
</div>
