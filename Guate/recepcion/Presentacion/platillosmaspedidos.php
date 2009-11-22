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
		$.getJSONGuate("Presentacion/jsonplatillosmasvendidos.php",{inicio:fechasArray[0],fin:fechasArray[1]}, function(json){
     		json = verificaJSON(json);
     		loadPage(json);     
   		});
	} else alert("Introduzca las fechas!");
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 $("#platillos").css("display","block");
 
  if (json.TicketsInfo){
    for(i=0;i<json.TicketsInfo.length;i++) {
        camb = cambio(json.TicketsInfo[i].efectivo,json.TicketsInfo[i].total);
     	numComanda=showid(json.TicketsInfo[i].numComanda);
        idCom=json.TicketsInfo[i].idComanda;
     	nombre = descripcion(json.TicketsInfo[i].free,json.TicketsInfo[i].nombre)
    
     $("#ticketsTable").append("<tr  id="+idCom+"><td width=2%></td><td width=10%><h6>"+numComanda+"</h6></td><td width=10%><h6>"+json.TicketsInfo[i].estado+"</h6></td><td width=21%><h6>"+json.TicketsInfo[i].fechaHora+"</h6></td><td width=6%><h6>"+json.TicketsInfo[i].total+"</h6></td><td width=8%><h6>"+json.TicketsInfo[i].efectivo+"</h6></td><td width=8%><h6>"+camb+"</h6></td><td width=7%><h6>"+json.TicketsInfo[i].tipoCliente+"</h6></td><td><h6>"+nombre+"</h6></td></tr>");
      $("#"+idCom+" td:not(.checkbox)").mousedown(function(e){
           showpedido(this.parentNode.id);
        });
     if (json.TicketsInfo[i].estado=="cobrado"){$("#"+json.TicketsInfo[i].idComanda).addClass("verde");}
     if (json.TicketsInfo[i].estado=="anulado"){$("#"+json.TicketsInfo[i].idComanda).addClass("redtext");}
        //alert(json.TicketsInfo[i].idComanda);		
        }
   }	
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

	<h5 class="titulos">Comandas realizadas</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=10%><h6>ID</h6></td><td width=10%><h6>Estado</h6></td><td width=21%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6><h6>Total</h6></h6></td><td width=8%><h6>efectivo</h6></td><td width=8%><h6>cambio</h6></td><td width=7%><h6>Cliente</h6></td><td><h6><center>Descripcion</center></h6></td></tr>
    </table>
  <div style="height:40%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
   </div>
   <div id="reporte" style="width:100%;overflow:auto">
    <div class="row" align="left">
     <div style="margin-left:60px;float:left"><span><a id="reportehtml" onClick="reportecaja('html');">Reporte Caja HTML</a></span></div>
     <div style="width:50%;float:right"><span><a id="reportexcel" onClick="reportecaja('excel');">Reporte Caja EXCEL</a></span></div>
   </div>
  </div>
<br/>


     <h5 class="titulos">Movimientos realizados</h5>
     <table  width=96% cellpadding=0 cellspacing=1>
      <tr><td width=18%><h6><center>Fecha Hora</center></h6></td><td width=8%><h6>tipo</h6></td><td width=7%><h6>dinero</h6></td><td><h6><center>descripcion</center></h6></td><td width=16%><h6><center>categoria</center></h6></td><td width=10%><h6><center>encargado</center></h6></td></tr>
     </table>
    <div style="height:40%;overflow:auto">
     <table id="movimientosTable" width=98% cellpadding=0 cellspacing=1></table>
    </div>
</div>
<div style="display:none" id="platillos">
  <table  width=100% cellpadding=0 cellspacing=1>
    <tr><td><h6>Nombre Platillo</h6></td><td width=10%><h6><center>Normal</center></h6></td><td width=10%><h6><center>Credito</center></h6></td><td width=10%><h6><center>Gratis</center></h6></td><td width=10%><h6><center>Cupon</center></h6></td></tr>
    </table>
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
</div>
<br/>


</body>
</html>

