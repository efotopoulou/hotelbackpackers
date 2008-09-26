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
tr{background:#FFF;text-align:right;height:20px;background: #FFFED2}
table{background:#DDD}
.btnunpress{background:#e0edfe}
.redtext{color:red}
.verde{background: #ACF0A4}
.saved{background:#B9FAC4}
.changedisplay{display:none}
.sr,.sb,.name,.codigo,.unidad{font-weight:bold;font-size:11px}
</style>
	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/guate.js"></script>
	<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
//al iniciar la pagina se cargan las bebidas de la bd
recargaEstadoCaja();
});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
//--------------------------------------------------------PREPARE STOCK--------------------------------------------------------//
function preparestock(aux){
  var idbebida=$("#stockTable .verde").attr("id");
  if(idbebida){	
  //recoger los valores que nos interesan	
  var codigobebida=$("#stockTable .verde .codigo").html();
   var namebebida=$("#stockTable .verde .name").html();	
   var bar=$("#stockTable .verde .sb").html();	
   var restaurante=$("#stockTable .verde .sr").html();	
   var total=parseInt(bar)+parseInt(restaurante);
   var unidadventa=$("#stockTable .verde .unidad").html();	
  
   $("#numbebida").val(codigobebida);
   $("#namebebida").val(namebebida);
   $("#barstock").val(bar);
   $("#restaurantestock").val(restaurante);
   $("#totalstock").val(total);
   $("#unidadventa").val(unidadventa);
   $("#numbebida,#namebebida,#totalstock").attr({disabled:true});
  
   changedisplay('b5');
   changedisplay('addstock');
   changedisplay(aux);
  }else alert("Por favor elige un producto!"); 
}
//--------------------------------------------------------COMPRAR STOCK--------------------------------------------------------//
function addstock(aux){
	var idbebida=$("#stockTable .verde").attr("id");
  if (idbebida){
	var stockbar = $("#barstock").val();
	var stockrestaurante = $("#restaurantestock").val();
	var unidadventa = $("#unidadventa").val();
	  if(idbebida && stockbar && stockrestaurante && unidadventa){
         if(confirm('�Estas seguro que quieres a�adir estas cantidades del producto?')){
             $.getJSONGuate("Presentacion/jsongestionstock.php",{aux:aux,idbebida:idbebida,stockbar:stockbar,stockrestaurante:stockrestaurante,unidadventa:unidadventa}, function(json){
             json = verificaJSON(json);
             loadbebidas(json);
             $("#"+idbebida).addClass("saved");	
             });
         }
		 $("#numbebida").val("");
		 $("#namebebida").val("");
		 $("#barstock").val("");
		 $("#restaurantestock").val("");
	
		 changedisplay('b5');
		 changedisplay('addstock');
		 changedisplay(aux);		
	  }else alert("Por favor rellena correctamente los campos!");
  }else ("Por favor elige un producto!"); 
}

//pedir de la bd el stock de las bebidas y ponerlos a la tabla de la pantalla
function recargaEstadoCaja(){
 $.getJSONGuate("Presentacion/jsongestionstock.php", function(json){
  json = verificaJSON(json);
  loadbebidas(json); 
  });	
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadbebidas(json){
     
  if (json.stockInfo){
  	  
  	  $("#stockTable").html(" ");
      for(i=0;i<json.stockInfo.length;i++) {
        idStock="B"+json.stockInfo[i].idBebida;
        total=parseInt(json.stockInfo[i].stockrestaurante)+parseInt(json.stockInfo[i].stockbar);
        $("#stockTable").append("<tr id="+idStock+"><td class='checkbox' width=2%><input type='checkbox' onmousedown='changeClass(\""+idStock+"\");'></td><td width=10% class='codigo'>"+json.stockInfo[i].numBebida+"</td><td width=41% class='name'>"+json.stockInfo[i].nombre+"</h6></td><td width=10% class='sb'>"+json.stockInfo[i].stockbar+"</h6></td><td width=10% class='sr'>"+json.stockInfo[i].stockrestaurante+"</td><td><h6>"+total+"</td><td width=10% class='unidad'>"+json.stockInfo[i].unidadventa+"</td></tr>");	
        }
        }
}
//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClass(id){
  $("#stockTable input:checked").each(function (){
   this.checked=0;
  });
$(".saved").toggleClass("saved");
$(".verde").toggleClass("verde");
$(".redtext").toggleClass("redtext");
$("#"+id).toggleClass("verde");
$("#"+id).toggleClass("redtext");
}
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>

<div id="stockbar">
 <h5 class="titulos"><center>Control de Stock del bar</center></h5>
 <div style="margin-left:10%;margin-top:2%;width:80%">
	
	<div>
	<table cellpadding=0 cellspacing=1>
    <tr class="titulos1"><td width=12%><h6>Codigo</h6></td><td width=40%><h6><center>Nombre Producto</center></h6></td><td width=10%><h6><center>Stock Bar</center></h6></td><td width=10%><h6>Stock Recepcion</h6></td><td><h6>Stock Total</h6></td><td width=13%><h6><center>Unidad Venta</center></h6></td></tr>
    </table>
    </div>
  
   <div style="height:65%;overflow:auto">
    <table id="stockTable" width=99% cellpadding=0 cellspacing=1>
    </table>
   </div>
   
   <div id="b5" style="height:10%;width:100%;overflow:auto">
    <div class="row" align="left">
     <div style="margin-top:20px;margin-left:300px;float:left;width:20%"><span><input type="button" value="Comprar Producto" id="add" onClick="preparestock('b6');"/></span></div>
     <div style="margin-top:20px;width:20%;float:left"><span><input type="button" value="Modificar Estado del Stock" id="mod" onClick="preparestock('b7');"/></span></div>
    </div>
  </div>
  
  <div id="addstock" style="margin-top:5px" class="changedisplay">			
    <table width=97%>
	<tr><td width=3% bgcolor="#ecf8cb"><img src="../img/flecha_dcha.jpg"></td>
	<td width=10%><input style="width: 100%" id="numbebida" value="" type="text"/></td>
	<td width=40%><input style="width: 100%" id="namebebida" value="" type="text"/></td>
	<td width=10%><input style="width: 100%" id="barstock" value="" type="text"/></td>
	<td width=10%><input style="width: 100%" id="restaurantestock" value="" type="text"/></td>
	<td><input style="width: 100%" id="totalstock" value="" type="text"/></td>
	<td width=10%><input style="width: 100%" id="unidadventa" value="" type="text"/></td>
	</tr>
	</table>
  </div>
		
	<div id="b6" style="float:left; margin-top:5px;" class="changedisplay">			
	 <input type="button" value="Guardar" style="width:100px;margin-left:300px;" onClick="addstock('b6');"/>
      <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('addstock');changedisplay('b6');"/>	
	</div>
	
	<div id="b7" style="float:left; margin-top:5px;" class="changedisplay">			
	 <input type="button" value="Guardar" style="width:100px;margin-left:300px;" onClick="addstock('b7');"/>
      <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('addstock');changedisplay('b7');"/>	
	</div>
   
 </div>
</div>


</body>
</html>


