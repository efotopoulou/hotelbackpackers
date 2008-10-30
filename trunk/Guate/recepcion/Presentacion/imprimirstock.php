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
$.getJSONGuate("jsongestionstock.php",{recarga:1}, function(json){
  json = verificaJSON(json);
  loadPage(json); 
  });
hoy = new Date();
$("#fechahoy").html(hoy.toLocaleString());
});
</script>
</head>
<script>
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
if (json.stockInfo){ 
  	  $("#stockTable").html(" ");
    for(i=0;i<json.stockInfo.length;i++) {
     if(json.stockInfo[i].stockrestaurante!=0) $("#stockTable").append("<tr><td width=10%>"+json.stockInfo[i].numBebida+"</td><td width=10%>"+json.stockInfo[i].familia+"</td><td>"+json.stockInfo[i].nombre+"</h6></td><td width=10%>"+json.stockInfo[i].stockrestaurante+"</td><td width=10%>"+json.stockInfo[i].unidadventa+"</td></tr>");	
    }
 }else{
 $("#stockTable").html(" ");
      }
}
</script>
<body>
<div>
	<h4>HOTEL BACKPAPERS</h4><br/>
	<h4 id="fechahoy"><br/></h4>
	<h4>Control de Stock de la Venta de Recepcion</h4><br/>
	
    <div id="stock"></div>
    <table width=75% border=1>
    <tr><td width=10%><h4>Codigo</h4></td><td width=10%><h4><center>Familia</center></h4></td><td><h4><center>Nombre Producto</center></h4></td><td width=10%><h4>Stock</h4></td><td width=10%><h4>Unidad</h4></td></tr>
    </table>
    <table id="stockTable" width=75% border=1></table>
        
</div>
</body>
</html>


