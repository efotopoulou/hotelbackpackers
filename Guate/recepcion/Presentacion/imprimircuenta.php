<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_reporte.php');

$nameempleado =  $_GET['name'];
$idemp =  $_GET['id'];
$pagado =  $_GET['pagado'];
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
$.getJSONGuate("jsoncuentausuarios.php",{idusuario:'<?php echo($idemp); ?>'}, function(json){
  json = verificaJSON(json);
  loadPage(json); 
  });
$("#cuentadequien").html("Cuenta del usuario "+'<?php echo($nameempleado); ?>');
$("#pagado").html("Pagado: "+'<?php echo($pagado); ?>');
hoy = new Date();
$("#fechahoy").html(hoy.toLocaleString());
});
</script>
</head>
<script>
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 $("#total").html("Total: "+json.TotalTickets);
if (json.TicketsInfo){ 
  	  $("#ticketsTable").html(" ");
     for(i=0;i<json.TicketsInfo.length;i++) {
     	numComanda=showid(json.TicketsInfo[i].numComanda);
        $("#ticketsTable").append("<tr><td width=5%>"+numComanda+"</td><td width=9%>"+json.TicketsInfo[i].estado+"</td><td width=25%>"+json.TicketsInfo[i].fechaHora+"</td><td width=6%>"+json.TicketsInfo[i].total+"</td><td width=10%>Credito</td><td>"+json.TicketsInfo[i].nombre+"</td></tr>");	
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
	<h2>HOTEL BACKPAPERS</h2>
    <h4 id="cuentadequien"><br/></h4>
    <h4 id="fechahoy"><br/></h4>
	<h3 id="total"></h3><h3 id="pagado"></h3>
	
    <div id="comandas"><h4>Comandas a Credito realizadas<br/></h4></div>
    <table width=75% border=1>
    <tr><td width=5%><h4>ID</h4></td><td width=9%><h4><center>estado</center></h4></td><td width=25%><h4><center>Fecha Hora</center></h4></td><td width=6%><h4>Total</h4></td><td width=10%><h4>Cliente</h4></td><td><h4><center>Nombre de Cliente</center></h4></td></tr>
    </table>
    <table id="ticketsTable" width=75% border=1></table>
    <br/><br/>
    
    <div id="comandas"><h4>Movimientos a Credito realizados<br/></h4></div>
    <table width=75% border=1>
    <tr><td width=18%><h4><center>Fecha Hora</center></h4></td><td width=8%><h4><center>estado</center></h4></td><td width=7%><h4>Dinero</h4></td><td><h4>Descripcion</h4></td><td width=10%><h4><center>Categoria</center></h4></td><td width=10%><h4><center>Encargado</center></h4></td></tr>
    </table>
    <table id="movimientosTable" width=75% border=1></table>
    
    
</div>
</body>
</html>


