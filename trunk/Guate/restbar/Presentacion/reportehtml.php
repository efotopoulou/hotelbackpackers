<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_reporte.php');

$turno =  $_GET['turno'];
$encargado =  $_GET['encargado'];
$idcaja =  $_GET['idcaja'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title><!-- Meta Information -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="MSSmartTagsPreventParsing" content="true">
<style>
tr{background:#FFF;text-align:left}
table{background:#DDD}
</style>
<script src="/common/js/jquery-1.2.3.pack.js"></script>
<script src="/common/js/guate.js"></script>
<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
$.getJSONGuate("gestionreporte.php",{idcaja:'<?php echo($idcaja); ?>'}, function(json){
 // alert('<?php echo($idcaja); ?>');
  json = verificaJSON(json);
  loadPage(json); 
  });
$("#turno").html('<?php echo($turno); ?>');
$("#encargado").html('<?php echo($encargado); ?>');
hoy = new Date();
$("#impresion").html(hoy.toLocaleString());
});
</script>
</head>
<script>
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
  a = new Array();
 if(json.ReportDetails){
 for(i=0;i<json.ReportDetails.length;i++) {
   a[json.ReportDetails[i].categoria]=(json.ReportDetails[i].id_categoria);
 }
 a["Adicion Bar Restaurante"]="BR";
 
   // crear las tablas para cada categoria
   for (var k in json.Info){
    if (k=="Adicion Bar Restaurante") idcat="BR";
    else idcat=a[k];
    $("#informetotal").before("<table id="+idcat+" width=100%><tr><td width=80% colspan=3>"+k+"</td><td width=10%>Ingreso</td><td width=10%>Egreso</td></tr></table></br></br>");
   } 
  // imprimir detalladamente los movimientos todos juntos y por categoria  
  $("#reportdetail").html(" ");
  for(i=0;i<json.ReportDetails.length;i++) {
  idCategoria=json.ReportDetails[i].id_categoria; 
  //imprimir los movimientos todos juntos (informe de caja por turno)
  $("#reportdetail").append("<tr><td width=10%>"+json.ReportDetails[i].date+"</td><td width=15%>"+json.ReportDetails[i].categoria+"</td><td>efectivo</td><td width=50%>"+json.ReportDetails[i].descripcion+"</td><td width=10%>"+json.ReportDetails[i].entrada+"</td><td width=10%>"+json.ReportDetails[i].salida+"</td><td width=10%> - </td></tr>");
  //y por categoria
  $("#"+idCategoria).append("<tr><td width=10%>"+json.ReportDetails[i].time+"</td><td width=50%>"+json.ReportDetails[i].descripcion+"</td><td width=10%>efectivo</td><td width=10%>"+json.ReportDetails[i].entrada+"</td><td width=10%>"+json.ReportDetails[i].salida+"</td></tr>");
  }	
}else{
 $("#reportdetail").html(" ");
 $("#informetotal").before('<table id="BR" width=100%><tr><td width=80% colspan=3>"Adicion Bar Restaurante"</td><td width=10%>Ingreso</td><td width=10%>Egreso</td></tr></table></br></br>');
}
// imprimir detalladamente las comandas realizadas  
if (json.Tiquets){
  for(i=0;i<json.Tiquets.length;i++) {
  $("#reportdetail").append("<tr><td width=10%>"+json.Tiquets[i].fecha+"</td><td width=15%>Adicion Bar Restaurante</td><td>efectivo</td><td width=50%>"+json.Tiquets[i].idComanda+"</td><td width=10%>"+json.Tiquets[i].total+"</td><td width=10%>0</td><td width=10%> - </td></tr>");	
  $("#BR").append("<tr><td width=10%>"+json.Tiquets[i].time+"</td><td width=50%>"+json.Tiquets[i].idComanda+"</td><td width=10%>efectivo</td><td width=10%>"+json.Tiquets[i].total+"</td><td width=10%>0</td></tr>");
  }    
}

// manejar la informacion de los movimientos en total.Es decir coger la resumen
if (json.Info){
 $("#movimientosTable").html(" ");
 
 var efectivo =json.TotalEntradas - json.TotalSalidas;
 for (var k in json.Info){
	var saldo = Math.abs(json.Info[k].entrada - json.Info[k].salida);
	$("#movimientosTable").append("<tr><td width=30%>"+k+"</td><td width=25%>Q"+json.Info[k].entrada+"</td><td width=25%>Q"+json.Info[k].salida+"</td><td width=20%>Q"+saldo+"</td></tr>");		
    if(a[k]) $("#"+a[k]).append("<tr><td colspan=2></td><td width=10%>TOTAL</td><td width=10%>Q"+json.Info[k].entrada+"</td><td width=10%>Q"+json.Info[k].salida+"</td></tr>");		
    }
} 
$("#movimientosTable").append("<tr><td width=30%>TOTALES</td><td width=25%>"+json.TotalEntradas+"Q</td><td width=25%>"+json.TotalSalidas+"Q</td><td width=20%>"+efectivo+"Q</td></tr>");		
 
 $("#entrymov").html(json.TotalEntradas);
 $("#exitmov").html(json.TotalSalidas); 
 $("#efectivo").html(efectivo); 
 $("#desde").html(json.HoraApertura); 
   if(json.HoraCierre) $("#hasta").html(json.HoraCierre);
}
</script>
<body>
<div>
	<h2>HOTEL BACKPAPERS BAR-RESTAURANTE</h2>
    <h4>Resumen de caja <br/></h4>
	<table>
	<tr><td><b>desde:</b></td><td id="desde"></td></tr>
	<tr><td><b>hasta:</b></td><td id="hasta"></td></tr>
	<tr><td><b>Turno:</b></td><td id="turno"></td><tr>
	<tr><td><b>Encargado:</b></td><td id="encargado"></td><tr>
	<tr><td><b>Fecha y hora de Impresion:</b></td><td id="impresion"></td><tr>   
	</table>
	<br/><br/>
	<table  width=50% border=1 cellpadding=0 cellspacing=1>
    <tr><td width=30%><h4>Cuenta</h4></td><td width=25%><h4><center>Ingresos</center></h4></td width=25%><td><h4><center>Egresos</center></h4></td><td width=20%><h4>Saldo</h4></td></tr>
    </table>
    <table id="movimientosTable" width=50% border=1 cellpadding=0 cellspacing=1></table>
    <br/><br/>
    <table style="margin-left:250px">
    <tr><td>TOTAL ENTRADA:</td><td id="entrymov"></td><tr/>
    <tr><td>TOTAL SALIDA:</td><td id="exitmov"></td><tr/>
    <tr><td>TOTAL EFECTIVO:</td><td id="efectivo"></td><tr/>
    </table>
    <br/><br/>
    <div id="informetotal"><h4>INFORME DE CAJA POR TURNO<br/></h4></div>
    <table width=100% border=1>
    <tr><td width=10%>Fecha</td><td width=15%><center>Cuenta</center></td><td><center>Valores</center></td><td width=50%>Detalle</td><td width=10%>Entrada</td><td width=10%>Salida</td><td width=10%>Factura</td></tr>
    </table>
    <table id="reportdetail" width=100% border=1></table>
</div>
</body>
</html>
