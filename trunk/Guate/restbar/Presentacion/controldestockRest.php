<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');

$admin = "disabled='true'";
if ($sesion){
	if ($sesion->is_allowed('admin_menu')) $admin=" ";
}
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
.sr,.sb,.name,.codigo,.unidad,.familia{font-weight:bold;font-size:11px}
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
function preparestock(){
  var idbebida=$("#stockTable .verde").attr("id");
  if(idbebida){	
  //recoger los valores que nos interesan	
   var codigobebida=$("#stockTable .verde .codigo").html();
   var familia=$("#stockTable .verde .familia").html();
   var namebebida=$("#stockTable .verde .name").html();	
   var bar=$("#stockTable .verde .sb").html();	
   //var restaurante=$("#stockTable .verde .sr").html();	
   //var total=parseInt(bar)+parseInt(restaurante);
   var unidadventa=$("#stockTable .verde .unidad").html();	
  
   $("#numbebida").val(codigobebida);
   $("#namebebida").val(namebebida);
   $("#familia").val(familia);
   $("#barstock").val(bar);
   //$("#restaurantestock").val(restaurante);
   //$("#totalstock").val(total);
   $("#unidadventa").val(unidadventa);
   $("#numbebida,#namebebida,#totalstock,#familia").attr({disabled:true});
  
   changedisplay('b5');
   changedisplay('addstock');
   changedisplay('b7');
  }else alert("Por favor elige un producto!"); 
}
//--------------------------------------------------------COMPRAR STOCK--------------------------------------------------------//
function addstock(){
	var idbebida=$("#stockTable .verde").attr("id");
  if (idbebida){
	var stockbar = $("#barstock").val();
	//var stockrestaurante = $("#restaurantestock").val();
	var unidadventa = $("#unidadventa").val();
	  if(idbebida && stockbar && unidadventa){
         if(confirm('�Estas seguro que quieres a�adir estas cantidades del producto?')){
             $.getJSONGuate("Presentacion/jsongestionstock.php",{idbebida:idbebida,stockbar:stockbar,stockrestaurante:"",unidadventa:unidadventa}, function(json){
             json = verificaJSON(json);
             loadbebidas(json);
             $("#"+idbebida).addClass("saved");	
             });
         }
		 $("#numbebida").val("");
		 $("#namebebida").val("");
		 $("#barstock").val("");
		 //$("#restaurantestock").val("");
	
		 changedisplay('b5');
		 changedisplay('addstock');
		 changedisplay('b7');		
	  }else alert("Por favor rellena correctamente los campos!");
  }else ("Por favor elige un producto!"); 
}

//pedir de la bd el stock de las bebidas y ponerlos a la tabla de la pantalla
function recargaEstadoCaja(){
 $.getJSONGuate("Presentacion/jsongestionstock.php",{recarga:"yes"}, function(json){
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
        $("#stockTable").append("<tr id="+idStock+"><td class='checkbox' width=2%><input type='checkbox' onmousedown='changeClass(\""+idStock+"\");'></td><td width=10% class='codigo'>"+json.stockInfo[i].numBebida+"</td><td width=18% class='familia'>"+json.stockInfo[i].familia+"</td><td class='name'>"+json.stockInfo[i].nombre+"</h6></td><td width=11% class='sr'>"+json.stockInfo[i].stockbar+"</td><td width=14% class='unidad'>"+json.stockInfo[i].unidadventa+"</td></tr>");	
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
//------------------------------------------VENTA DE HOY--------------------------------------------------------//
function ventadeturno(){ 
  $("#rlv").attr({disabled:true});   
  $.getJSONGuate("Presentacion/jsongestionstock.php",{ventaturno:1}, function(json){
    json = verificaJSON(json);
    //if (json.recuperarVentas==1) 
    if (json.ventasInfo){
  	   $("#VentasTable").html(" ");
  	   $("#VentasTable").append("<tr><td width=20%><h5>id</h5></td><td><h5>nombre</h5></td><td width=20%><h5>cant.</h5></td></tr>");	
       for(i=0;i<json.ventasInfo.length;i++) {
        $("#VentasTable").append("<tr><td width=20%><h6>"+json.ventasInfo[i].numBebida+"</h6></td><td><h6>"+json.ventasInfo[i].nombre+"</h6></td><td width=20%><h6>"+json.ventasInfo[i].suma+"</h6></td></tr>");	
        }
    }
  });
    $.blockUI({ message: $('#ventaturno')});
    
}
//------------------------------------------RECUPERAR VENTA DE HOY--------------------------------------------------------//
function recuperarventa(){ 
      
  $.getJSONGuate("Presentacion/jsongestionstock.php",{recuperarventa:1}, function(json){
  json = verificaJSON(json);
  loadbebidas(json); 
  });
  $.unblockUI();
    
}
</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menuRestBar.php'); ?>

<div  id="ventaturno" style="display:none;margin:0 auto;text-align:center;" class="box_amarillo">
<center><table class="box_amarillo" id="VentasTable" style="height:80%;width:80%">
</table></center>
<input type="button" value="Recuperar la venta" id="rlv" onClick="recuperarventa();" />
<input type="button" style="margin-left:10%;" value="Volver" onClick="$.unblockUI();" />
</div>

<div id="stockbar">
 <h5 class="titulos"><center>Control de Stock del bar</center></h5>
 <div style="margin-left:10%;margin-top:2%;width:80%">
	
	<div>
	<table cellpadding=0 cellspacing=1>
    <tr class="titulos1"><td width=12%><h6>Codigo</h6></td><td width=15%><h6>Familia</h6></td><td width=40%><h6><center>Nombre Producto</center></h6></td><td width=10%><h6>Stock Bar</h6></td><td width=13%><h6><center>Unidad Venta</center></h6></td></tr>
    </table>
    </div>
  
   <div style="height:65%;overflow:auto">
    <table id="stockTable" width=95% cellpadding=0 cellspacing=1>
    </table>
   </div>
   
   <div id="b5" style="height:10%;width:100%;overflow:auto">
    <div class="row" align="left">
     <div style="margin-top:20px;margin-left:100px;float:left;width:20%"><span><input type="button" value="Venta de Turno" onClick="ventadeturno();"/></span></div>
    <!-- <div style="margin-top:20px;float:left;width:20%"><span><input type="button" value="Comprar Producto" id="add" onClick="preparestock('b6');"/></span></div>-->
     <div style="margin-top:20px;width:20%;float:left"><span><input <?php echo $admin ?> type="button" value="Modificar Estado del Stock" id="mod" onClick="preparestock();"/></span></div>
    </div>
  </div>
  
  <div id="addstock" style="margin-top:5px" class="changedisplay">			
    <table width=97%>
	<tr><td width=3% bgcolor="#ecf8cb"><img src="../img/flecha_dcha.jpg"></td>
	<td width=10%><input style="width: 100%" id="numbebida" value="" type="text"/></td>
	<td width=14%><input style="width: 100%" id="familia" value="" type="text"/></td>
	<td width=40%><input style="width: 100%" id="namebebida" value="" type="text"/></td>
	 <td width=10%><input style="width: 100%" id="barstock" value="" type="text"/></td> 
	<!--<td width=10%><input style="width: 100%" id="restaurantestock" value="" type="text"/></td>-->
	<!-- <td><input style="width: 100%" id="totalstock" value="" type="text"/></td> -->
	<td width=10%><input style="width: 100%" id="unidadventa" value="" type="text"/></td>
	</tr>
	</table>
  </div>
		
	<div id="b7" style="float:left; margin-top:5px;" class="changedisplay">			
	 <input type="button" value="Guardar" style="width:100px;margin-left:300px;" onClick="addstock();"/>
      <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('addstock');changedisplay('b7');"/>	
	</div>
   
 </div>
</div>


</body>
</html>




