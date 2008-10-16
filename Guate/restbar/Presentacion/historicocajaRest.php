<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_caja.php');
$date =  $_GET['date'];
$idcaja =  $_GET['idcaja'];
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
$(".fondo").html("0");
$("#fechas").val("");

  $("#fechas").datepicker({  
   dateFormat: "yy-mm-dd", 
   rangeSelect: true,
   showOn: "both",
   buttonImage: "/common/img/calendar.gif", 
   buttonImageOnly: true 
  });
  //el parametro de la fecha se manta de las estadisticas-month para que vea el usuario las cajas que han sido abiertas esta fecha
 date = "<?php echo($date); ?>";
 if (date != ""){
  $("#fechas").val(date+" - "+date);
  buscaCajas();
 }
 
//el parametro de la id_caja se manta de las estadisticas-week para que vea el usuario detalladamente las comandas y los movimientos de las dicha caja
 idcaja = "<?php echo($idcaja); ?>";
 if (idcaja != ""){
 $.getJSONGuate("Presentacion/jsongestioncajaAntigua.php",{idcaja:idcaja}, function(json){
  json = verificaJSON(json);
  cajainfo(json);
  loadPage(json); 
  });	
 }
});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/common/css/flora.datepicker.css" type="text/css" media="screen" title="Flora (Default)" />
</head>
<script>
//--------------------------------------------------------buscaCajas----------------------------------------------------------------------//
function buscaCajas(){
	fechasArray = $("#fechas").val().split(" - ");
	$.getJSONGuate("Presentacion/jsongestioncajaAntigua.php",{inicio:fechasArray[0],fin:fechasArray[1]}, function(json){
     json = verificaJSON(json);
     cajainfo(json);     
   });
}
function cajainfo(json){
	  $("#cajasTable").html(" ");
	  $("#ticketsTable").html(" ");
	  $("#movimientosTable").html(" ");   
	  if (json.CajasInfo){
  	  $("#CajasEncontradas").removeClass("changedisplay");
      for(i=0;i<json.CajasInfo.length;i++) {
        $("#cajasTable").append("<tr id="+json.CajasInfo[i].id_caja+" onmousedown='changeClass(this.id);recargaEstadoCaja(id);'><td width=30%><h6>"+json.CajasInfo[i].fechaHoraApertura+"</h6></td><td width=30%><h6>"+json.CajasInfo[i].fechaHoraCierre+"</h6></td><td><h6>"+json.CajasInfo[i].fondoInicial+"</h6></td><td><h6>"+json.CajasInfo[i].EfectivoCerrar+"</h6></td></tr>");		
        }
        }else alert("No se ha encontrado ninguna caja!La caja no se ha abierto durante estas fechas!");
	
}
//-------------------------------------------SUPUESTO EFECTIVO-------------------------------------------------//
function supuestoEfectivo(){
	var totalEntradas=0;
	var totalSalidas=0;
	var totalTickets=0;
	
	var fondo=parseFloat($(".fondo").html());
	if($(".entrymov").html()) totalEntradas=parseFloat($(".entrymov").html());
	if($(".exitmov").html()) totalSalidas=parseFloat($(".exitmov").html());
	if($(".totalTickets").html()) totalTickets=parseFloat($(".totalTickets").html());
	 
	var supEfectivo =(fondo+totalEntradas+totalTickets-totalSalidas);
	$(".supEfectivo").html(supEfectivo);
	aux = $(".totalTickets").html();
}
//-------------------------------------------RECARGA ESTADO CAJA----------------------------------------------------------//
//pedir de la bd los datos de los movimientos y Tickets y ponerlos a la tabla de la pantalla
function recargaEstadoCaja(id){
 $.getJSONGuate("Presentacion/jsongestioncajaAntigua.php",{id_caja:id}, function(json){
  json = verificaJSON(json);
  loadPage(json); 
  });	
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 $(".entrymov").html(json.TotalEntradas);
 $(".exitmov").html(json.TotalSalidas); 
 $(".fondo").html(json.fondo);
 
 var totTickets=redondea(json.TotalTickets);
 if (json.TotalTickets) $(".totalTickets").html(totTickets); else $(".totalTickets").html("0");
     
     supuestoEfectivo();
  
  $("#movimientosTable").html(" ");   
  if (json.MovimientosInfo){
      for(i=0;i<json.MovimientosInfo.length;i++) {
        $("#movimientosTable").append("<tr id=M"+i+" onmousedown='changeClass(this.id)><td width=18%><h6>"+json.MovimientosInfo[i].fechaHora+"</h6></td><td width=8%><h6>"+json.MovimientosInfo[i].tipo+"</h6></td><td width=7%><h6>"+json.MovimientosInfo[i].dinero+"</h6></td><td><h6>"+json.MovimientosInfo[i].descripcion+"</h6></td><td width=16%><h6>"+json.MovimientosInfo[i].categoria+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].encargado+"</h6></td></tr>");		
           if(json.MovimientosInfo[i].tipo=="anulado"){
        	$("#M"+i).css({ textDecoration:"line-through"});
        	$("#M"+i).addClass("redtext");
           }
        }
  }
  $("#ticketsTable").html(" ");
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
        if(json.TicketsInfo[i].estado=="anulado"){
        	$("#"+idCom).css({ textDecoration:"line-through"});
        	$("#"+idCom).addClass("redtext");
        	}
        if (json.TicketsInfo[i].estado=="cobrado"){$("#"+idCom).addClass("verde");}	
        }
   }	
}
//-------------------------------------------CALCULAR CAMBIO-------------------------------------------------//
function cambio(efectivo,total){
var camb1 =(parseFloat(efectivo)-parseFloat(total));
var camb =(Math.round(camb1*100)/100);
return camb;
}
//-------------------------------------------DECIDIR SI VA A APARECER EL NUMCOMANDA-------------------------------------------------//
function showid(numComanda){
if (numComanda!= null)	return numComanda;
else return "";
}
//-------------------------------------------DESCRIPCION OF COMANDA-------------------------------------------------//
function descripcion(free,nombre){
   if (free) return free;
   else if (nombre) return nombre;
   else return "";  	
}
//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClass(id){
$(".btnunpress").toggleClass("btnunpress");
$(".redtext").toggleClass("redtext");
$("#"+id).toggleClass("btnunpress");
$("#"+id).toggleClass("redtext");
}

function redondea(num){
	parseFloat(num);
	return (Math.round(num*100)/100);
}
//-------------------------------------------SHOW PEDIDO---------------------------------------------------//
function showpedido(id){
    if ($("#ticketsTable tr").hasClass("detail"+id)){
       	$(".detail"+id).remove();
    }else {
	 $.getJSONGuate("Presentacion/jsongestioncaja.php",{idComDetail:id}, function(json){
      json = verificaJSON(json);
      if (json.pedidosInfo){		
       for(i=0;i<json.pedidosInfo.length;i++) {
        $("#"+id).after("<tr class='detail"+id+"'><td colspan=9><table cellspacing=0 cellpadding=0 width=100%><tr><td width=7% class='yellow'><h6>"+json.pedidosInfo[i].idPlatillo+"</h6></td><td width=6% class='yellow'><h6>"+json.pedidosInfo[i].cantidad+"</h6></td><td width=28% class='yellow'><h6>"+json.pedidosInfo[i].nombre+"</h6></td><td width=7% class='yellow'><h6>"+json.pedidosInfo[i].precio+"</h6></td><td>&nbsp;</td></tr></table></td></tr>");		
        }
        $("#"+id).after("<tr class='detail"+id+"'><td colspan=9><table cellspacing=0 cellpadding=0 width=100%><tr><td width=7% class='yellow'><h6>idPlatillo</h6></td><td width=6% class='yellow'><h6>can.</h6></td><td width=28% class='yellow'><h6>nombre</h6></td><td width=7% class='yellow'><h6>precio</h6></td><td>&nbsp;</td></tr></table></td></tr>");
        //$(".detail"+id).addClass("yellow");
       }
     });
   }
}
//-------------------------------------------REPORTE CAJA (HTML - EXCEL)-------------------------------------------------//
function reportecaja(type){
	turno =$("#turno").val();
	user =$("#selUsers option:selected").html();
	var id_caja=$("#cajasTable .btnunpress").attr("id");
   if(type=="html") document.location="Presentacion/reportehtml.php?turno="+turno+"&encargado="+user+"&idcaja="+id_caja;
   if(type=="excel") document.location="Presentacion/reportexcel.php?turno="+turno+"&encargado="+user+"&idcaja="+id_caja;
}
</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menuRestBar.php'); ?>
<div id="principalesCj">
	<h5 class="titulos">Resumen de Caja</h5>
	
	<div class="box_amarillo" style="width:90%;height:20%;margin-top:15px;margin-left:20px">
	<div><span class="label"><b><h3>Estado de caja</h3></b></span>
		<form name="cajaresumen">
			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:20px"><span>Fondo Inicial:</span></div>
      		<div style="margin-top:20px"><span class="fondo">0</span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span >Total Tiquets:</span></div>
      		<div><span class="totalTickets">0</span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Total Endradas:</span></div>
      		<div><span class="entrymov">0</span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Total Salidas:</span></div>
      		<div><span class="exitmov">0</span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Supuesto Efectivo:</span></div>
      		<div><span class="supEfectivo">0</span></div>
   			</div>
		</form> 
	</div>
	</div>
		
  <div class="box_amarillo" style="width:90%;height:65%;margin-top:15px;margin-left:20px">
   <div><span class="label"><b><h3>Buscador de cajas antiguas:</h3></b></span>
			<div class="row" align="left">
      		  <div style="width:500px;float:left;margin-top:20px"><span>Por favor elige las fechas que te interesan:</span></div>
      		  <div style="width:100%;float:left;margin-top:20px"><span>Fecha Inicio - Fin:</span><input type="text" size="25" value="" id="fechas"/></div>
      		  <div style="width:150px;float:left;margin-left:100px;margin-top:10px"><span><input type="button" value="Buscar" onClick="buscaCajas()"/></span></div>
      		</div>
   </div>
   <div style="clear:both"></div>
   <br><br>
   <div id="CajasEncontradas" class="changedisplay"><span class="label"><b><h3>Cajas encontradas:</h3></b></span>
   <br><br>
            <table  width=97% cellpadding=0 cellspacing=1>
              <tr><td width=30%><h6><center>Apertura</center></h6></td><td width=30%><h6><center>Cierre</center></h6></td><td width=20%><h6><center>fondo</center></h6></td><td><h6><center>Efectivo</center></h6></td></tr>
             </table>
           <div style="overflow:auto;height:40%">
              <table id="cajasTable" width=97% cellpadding=0 cellspacing=1></table>
           </div>
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
<br/>


</body>
</html>


