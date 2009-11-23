<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
<style>
tr{background:#FFF;text-align:right}
table{background:#DDD}
.btnunpress{background:#e0edfe}
.btnpress{background:#A6F8B4}
.redtext{color:red}
.verde{color:#4AD411}
.changedisplay{display:none}
.yellow{background: #FAF2BE}
</style>
	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/ui.datepicker.js"></script>
	<script src="/common/js/ui.datepicker-es.js"></script>
	<script src="/common/js/guate.js"></script>
	<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
$("#fechas").val("");
  $("#fechas").datepicker({  
   dateFormat: "yy-mm-dd", 
   rangeSelect: true,
   showOn: "both",
   buttonImage: "/common/img/calendar.gif", 
   buttonImageOnly: true 
  });
});
</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/common/css/flora.datepicker.css" type="text/css" media="screen" title="Flora (Default)" />
</head>
<script>
//--------------------------------------------------------buscaCajas----------------------------------------------------------------------//
function buscaPlatillos(){
	fechasArray = $("#fechas").val().split(" - ");
	if (fechasArray!=""){
		$("#principalesCj").css('display', 'none');
		$("#secundarioesCj").css('display', 'none');
		$("#header-bar").css('display', 'none');
		$.blockUI({ message: '<h1> Cargando los datos...</h1><h1>Espere por favor</h1><input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>' }); 
		$.getJSONGuate("Presentacion/jsonplatillosmasvendidos.php",{inicio:fechasArray[0],fin:fechasArray[1]}, function(json){
     		$.unblockUI();
     		json = verificaJSON(json);
     		loadPage(json);     
   		});
	} else alert("Introduzca las fechas!");
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
  $("#platillos").css("display","block");

  if (json != null){
  	var plato;
	for (plato in json){
		if (!json[plato][1])json[plato][1]="0";
		if (!json[plato][2])json[plato][2]="0";
		if (!json[plato][4])json[plato][4]="0";
		if (!json[plato][5])json[plato][5]="0";
		$("#platillosTable").append("<tr><td><h6>"+plato+"</h6></td><td width=10%><h6><center>"+json[plato][4]+"</center></h6></td><td width=10%><h6><center>"+json[plato][5]+"</center></h6></td><td width=10%><h6><center>"+json[plato][1]+"</center></h6></td><td width=10%><h6><center>"+json[plato][2]+"</center></h6></td><td width=10%><h6><center>"+json[plato]["total"]+"</center></h6></td></tr>");
	}
    	
  }	else alert ("No hay datos para las fechas seleccionadas");
}


</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principalesCj">
	<h5 class="titulos">Platillos mas vendidos</h5>
	
	<div class="box_amarillo" style="width:90%;height:30%;margin-top:15px;margin-left:20px">
	<span class="label"><b><h3>Buscador de Platillos mas vendidos:</h3></b></span>
			<div class="row" align="left">
      		  <div style="margin-top:10px"><span>Por favor elige las fechas que te interesan:</span></div>
      		  <div style="margin-top:10px"><span>Fecha Inicio - Fin:</span><input type="text" size="25" value="" id="fechas"/></div>
      		  <div style="width:150px;margin-left:100px;margin-top:10px"><span><input type="button" value="Buscar" onClick="buscaPlatillos()"/></span></div>
      		</div>

	</div>
		
</div>

<div id="secundarioesCj">

</div>
<div style="display:none" id="platillos">
  <table  width=100% cellpadding=0 cellspacing=1>
    <tr><td><h6>Nombre Platillo</h6></td><td width=10%><h6><center>Normal</center></h6></td><td width=10%><h6><center>Credito</center></h6></td><td width=10%><h6><center>Gratis</center></h6></td><td width=10%><h6><center>Cupon</center></h6></td><td width=10%><h6><center>Total</center></h6></td></tr>
    </table>
    <table id="platillosTable" width=100% cellpadding=0 cellspacing=1>
    </table>
</div>
<br/>


</body>
</html>

