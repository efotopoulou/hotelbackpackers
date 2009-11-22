<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
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
	<script src="/common/js/guate.js"></script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
//--------------------------------------------------------buscaCajas----------------------------------------------------------------------//
function buscaComanda(){
	name = $("#comanda").val();
	if (name !=""){
		$.getJSONGuate("Presentacion/jsonbuscarporcomanda.php",{name:name}, function(json){
     		json = verificaJSON(json);
     		loadPage(json);     
   		});
	} else alert("Introduce el numero de comanda!");
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 
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
     if (json.TicketsInfo[i].estado=="cobrado"){$("#"+json.TicketsInfo[i].idComanda).addClass("verde");}
     if (json.TicketsInfo[i].estado=="anulado"){$("#"+json.TicketsInfo[i].idComanda).addClass("redtext");}
        }
   } else alert("No se ha encontrado ninguna comanda con ese numero");	
}
function redondea(num){
	parseFloat(num);
	return (Math.round(num*100)/100);
}
//-------------------------------------------CALCULAR CAMBIO-------------------------------------------------//
function cambio(efectivo,total){
var camb1 =(parseFloat(efectivo)-parseFloat(total));
var camb =(Math.round(camb1*100)/100);
return camb;
}
//-------------------------------------------DESCRIPCION OF COMANDA-------------------------------------------------//
function descripcion(free,nombre){
   if (free) return free;
   else if (nombre) return nombre;
   else return "";  	
}
//-------------------------------------------DECIDIR SI VA A APARECER EL NUMCOMANDA-------------------------------------------------//
function showid(numComanda){
if (numComanda!= null)	return numComanda;
else return "";
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

</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principalesCj">
	<h5 class="titulos">Buscar por Comanda</h5>
	
	<div class="box_amarillo" style="width:90%;height:20%;margin-top:15px;margin-left:20px">
	  <div style="margin-top:10px"><span>Introduzca la comanda que quiere buscar:</span></div>
	  <div style="margin-top:10px">Numero de comanda:<input type="text" size="25" value="" id="comanda"/></div>
	  <div style="width:150px;margin-left:100px;margin-top:10px"><span><input type="button" value="Buscar" onClick="buscaComanda()"/></span></div>

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
<br/>


</div>
<br/>


</body>
</html>

