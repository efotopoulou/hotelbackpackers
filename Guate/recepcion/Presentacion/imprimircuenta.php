<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_reporte.php');

$nameempleado =  $_GET['name'];
$idemp =  $_GET['id'];
$pagado =  $_GET['pagado'];
$fechaStart =  $_GET['fechaStart'];
$fechaStop =  $_GET['fechaStop'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title><!-- Meta Information -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="MSSmartTagsPreventParsing" content="true">
	<script src="/common/js/jquery.js"></script>
	<script src="/common/js/guate.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
$.blockUI({ message: '<h1> Cargando los datos...</h1><h1>Espere por favor</h1><input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>' });
$.getJSONGuate("jsoncuentausuarios.php",{service:"imprcuenta",idusuario:'<?php echo($idemp); ?>',fechaStart:'<?php echo($fechaStart); ?>',fechaStop:'<?php echo($fechaStop); ?>'}, function(json){
	json = verificaJSON(json);
	loadPage(json);
	$.unblockUI();
	window.print(); 
  });
$("#cuentadequien").html('<?php echo($nameempleado); ?>');
$("#pagado").html('<?php echo($pagado); ?>');
$("#fechaStart").html('<?php echo($fechaStart); ?>');
$("#fechaStop").html('<?php echo($fechaStop); ?>');
hoy = new Date();
$("#fechahoy").html(hoy.toLocaleString());
});
</script>
</head>
<script>
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 $("#total").html(json.TotalTickets);
if (json.TicketsInfo){ 
  	  $("#ticketsTable").html(" ");
     for(i=0;i<json.TicketsInfo.length;i++) {
     	numComanda=showid(json.TicketsInfo[i].numComanda);
        $("#ticketsTable").append("<tr><td width=5%>"+numComanda+"</td><td width=25%>"+json.TicketsInfo[i].fechaHora+"</td><td width=6%>"+json.TicketsInfo[i].total+"</td><td>"+json.TicketsInfo[i].nombre+"</td></tr>");	
        }
        }else{
        $("#ticketsTable").html(" ");
        }
        
        if (json.MovimientosInfo){
  	  $("#movimientosTable").html(" ");
      for(i=0;i<json.MovimientosInfo.length;i++) {
        $("#movimientosTable").append("<tr><td width=18%>"+json.MovimientosInfo[i].fechaHora+"</td><td width=8%>"+json.MovimientosInfo[i].tipo+"</td><td width=7%>"+json.MovimientosInfo[i].dinero+"</td><td>"+json.MovimientosInfo[i].descripcion+"</td><td width=10%>"+json.MovimientosInfo[i].categoria+"</td><td width=10%>"+json.MovimientosInfo[i].encargado+"</td></tr>");          	
        }
        }else{
        $("#movimientosTable").html(" ");
        }
}
//-------------------------------------------DECIDIR SI VA A APARECER EL NUMCOMANDA-------------------------------------------------//
function showid(numComanda){
if (numComanda!= null)	return numComanda;
else return "";
}
</script>
<body>
<div>
	<div style="text-align:center"><h2>HOTEL BACKPACKERS - CUENTAS</h2></div>
    <table bgcolor="#000000" border="0" cellspacing="1">
    <tr bgcolor="#FFFFFF"><td width=200px><b>Nombre del usuario<b></td><td><b id="cuentadequien"></b></td></tr>
    <tr bgcolor="#FFFFFF"><td><b>Fecha de hoy</b></td><td><b id="fechahoy"></b></td></tr>
    <tr bgcolor="#FFFFFF"><td><b>Pendiente de pagar</b></td><td><b id="total"></b></td></tr>
    <tr bgcolor="#FFFFFF"><td><b>Ya pagado</b></td><td><b id="pagado"></b></td></tr>
    <tr bgcolor="#FFFFFF"><td><b>Fecha Inicio</b></td><td><b id="fechaStart"></b></td></tr>
    <tr bgcolor="#FFFFFF"><td><b>Fecha Final</b></td><td><b id="fechaStop"></b></td></tr>
    </table>
    	
    <div id="comandas"><h4>Comandas a Cr&eacute;dito realizadas<br/></h4></div>
    <table width=75% border=1>
    <tr><td width=5%><h4>ID</h4></td><td width=25%><h4><center>Fecha Hora</center></h4></td><td width=6%><h4>Total</h4></td><td><h4><center>Nombre de Cliente</center></h4></td></tr>
    </table>
    <table id="ticketsTable" width=75% border=1></table>
    <br/><br/>
    
    <div id="comandas"><h4>Movimientos a Cr&eacute;dito realizados<br/></h4></div>
    <table width=75% border=1>
    <tr><td width=18%><h4><center>Fecha Hora</center></h4></td><td width=8%><h4><center>Estado</center></h4></td><td width=7%><h4>Dinero</h4></td><td><h4>Descripci&oacute;n</h4></td><td width=10%><h4><center>Categoria</center></h4></td><td width=10%><h4><center>Encargado</center></h4></td></tr>
    </table>
    <table id="movimientosTable" width=75% border=1></table>
    
    
</div>
</body>
</html>


