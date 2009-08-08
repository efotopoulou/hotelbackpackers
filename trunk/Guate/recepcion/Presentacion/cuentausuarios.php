<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_estadisticas.php');
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
.redtext{color:red}
.verde{color:#4AD411}
.green{background: #c1d673}
.amarillo{background: #F1F873}
.yellow{background: #FAF2BE}
.changedisplay{display:none}
</style>
	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/guate.js"></script>
	<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
var timeoutHnd;
$(document).ready(function(){
  $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{usuario:"yes"}, function(json){
     json = verificaJSON(json);
   loadusuarios(json);
   });
   
 <?php
$caja=new caja();
$categoria=$caja-> get_categories();
for($i=0;$i<count($categoria);$i++) {
?>
	$("#categoria").append("<option value='<?php echo($categoria[$i]->id_categoria); ?>'><?php echo($categoria[$i]->nombre); ?></option>");

<?php }  ?>  
});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClassUsuario(id){
$(".amarillo").toggleClass("amarillo");
btncolor(id);
}
function btncolor(id){
$("#"+id).toggleClass("amarillo");
}

function redondea(num){
	parseFloat(num);
	return (Math.round(num*100)/100);
}
//-------------------------------------------LOAD USUARIOS----------------------------------------------------------//
function loadusuarios(json){
	if (json.UsuariosInfo){
  	$("#usuariosTable").html(" ");
        for(i=0;i<json.UsuariosInfo.length;i++) {
        $("#usuariosTable").append("<tr id=T"+json.UsuariosInfo[i].idTrabajador+" onmousedown='changeClassUsuario(this.id);loadcuenta(this.id);'><td><h4><center class='onomataki'>"+json.UsuariosInfo[i].nombre+"</center></h4></td></tr>");		
        $("#T"+json.UsuariosInfo[i].idTrabajador).addClass("green");
        }
     }
}
//-------------------------------------------LOAD CUENTA----------------------------------------------------------//
function loadcuenta(id){
$(".total").html("0");
$(".pagado").html("0");
$.getJSONGuate("Presentacion/jsoncuentausuarios.php",{idusuario:id}, function(json){
     json = verificaJSON(json);
     loadPage(json);
       });	
}
function loadPage(json){
if (json.TicketsInfo){
  	  $("#ticketsTable").html(" ");
     for(i=0;i<json.TicketsInfo.length;i++) {
     	numComanda=showid(json.TicketsInfo[i].numComanda);
     	idCom=json.TicketsInfo[i].procedencia+json.TicketsInfo[i].idComanda;
        $("#ticketsTable").append("<tr id="+idCom+"><td class='checkbox' width=2%><input type='checkbox'  onclick='btncolor(\""+idCom+"\");'></td><td width=5%><h6 class='numcomand'>"+numComanda+"</h6></td><td width=9% class='estado'><h6 class='estadoh6'>"+json.TicketsInfo[i].estado+"</h6></td><td width=25%><h6>"+json.TicketsInfo[i].fechaHora+"</h6></td><td width=6%><h6>"+json.TicketsInfo[i].total+"</h6></td><td width=10%><h6>Credito</h6></td><td><h6>"+json.TicketsInfo[i].nombre+"</h6></td></tr>");
        $("#"+idCom+" td:not(.checkbox)").mousedown(function(e){
           var num=$("#"+this.parentNode.id+" .numcomand").html();
           showpedido(this.parentNode.id,num);
        });
        }
        }else{
        $("#ticketsTable").html(" ");
        }
        
        if (json.MovimientosInfo){
  	  var pagado=0;
  	  $("#movimientosTable").html(" ");
      for(i=0;i<json.MovimientosInfo.length;i++) {
     	idMov="M"+json.MovimientosInfo[i].id_movimiento;
        $("#movimientosTable").append("<tr id="+idMov+"><td class='checkbox' width=2%><input type='checkbox'  onclick='btncolor(\""+idMov+"\");'></td><td width=18%><h6>"+json.MovimientosInfo[i].fechaHora+"</h6></td><td width=8%><h6 class='tipoh6'>"+json.MovimientosInfo[i].tipo+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].dinero+"</h6></td><td><h6>"+json.MovimientosInfo[i].descripcion+"</h6></td><td><h6>"+json.MovimientosInfo[i].categoria+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].encargado+"</h6></td></tr>");
          if (json.MovimientosInfo[i].dinero<0) pagado+=parseFloat(json.MovimientosInfo[i].dinero);
          if(json.MovimientosInfo[i].tipo=="anulado"){
        	$("#"+idMov).css({ textDecoration:"line-through"});
        	$("#"+idMov).addClass("redtext");
          }	
          if(json.MovimientosInfo[i].tipo=="cobrado") $("#"+idMov).addClass("verde");
          	
        }
         $(".pagado").html(Math.abs(pagado));
        }else{
        $("#movimientosTable").html(" ");
        }
       
        if (!json.MovimientosInfo && !json.TicketsInfo) alert("El usuario no ha consumido nada!");
        
        if (json.TotalTickets)  $(".total").html(Math.round(json.TotalTickets*100)/100);
}
//-------------------------------------------DECIDIR SI VA A APARECER EL NUMCOMANDA-------------------------------------------------//
function showid(numComanda){
if (numComanda!= null)	return numComanda;
else return "";
}
//-------------------------------------------SHOW PEDIDO---------------------------------------------------//
function showpedido(id,numcomanda){
    if ($("#ticketsTable tr").hasClass("detail"+id)){
       	$(".detail"+id).remove();
    }else {
	// var idComDetail=id.substring(1);
	 $.getJSONGuate("Presentacion/jsongestioncaja.php",{idComDetailcuenta:id,numcomanda:numcomanda}, function(json){
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
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
//-------------------------------------------CREAR CUENTA-------------------------------------------------//
function crear_cuenta(){
var nombreEmpleado= $("#nombreEmpleado").val();
var tipo= $("#tipo").val();
  if(confirm('Estas seguro que quieres crear una nueva cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{nombreEmpleado:nombreEmpleado,tipo:tipo}, function(json){
      json = verificaJSON(json);
      loadusuarios(json);
      $("#ticketsTable").html(" ");
      $("#total").html("0");
      $("#nombreEmpleado").val("");
      changedisplay('b5');changedisplay('b6');
     });
  }
}
//-------------------------------------------ELIMINAR CUENTA-------------------------------------------------//
function eliminar_cuenta(){
var cuentadelete=$("#usuariosTable .amarillo").attr("id");
//alert (cuentadelete);
 if (cuentadelete){
  if(confirm('Estas seguro que quieres eliminar esta cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{cuentadelete:cuentadelete}, function(json){
      json = verificaJSON(json);
       //if (json["Mensaje"]){ 	alert("Hola"); }
      loadusuarios(json);
      $("#ticketsTable").html(" ");
      $("#movimientosTable").html(" ");
      $(".total").html("0");
      $(".pagado").html("0");
     });
  }
 } else alert ("Elije primero la cuenta que quieres eliminar.");
}
//--------------------------------------------------------INSERT MOVIMIENTO A CREDITO--------------------------------------------------------//
function insertMovimiento(entrada,description,categoria){
var idempleado=$("#usuariosTable .amarillo").attr("id");
if (description && categoria){
 if (parseFloat(entrada) && idempleado ) {
  var dinero=parseFloat(entrada);
  var description=description;
  var categoria=categoria;
  insertmovcredito(dinero,description,categoria,idempleado);
 }else alert("Introduce correctamente el movimiento!Elige el empleado que te interesa!");
} else  alert("Tienes que introducir una descripcion!");


//vaciar los campos usados
$("#input_money").val("");
$("#description").val("");
$("#categoria").val(1);
}
//--------------------------------------------------------CALL GESTION CAJA--------------------------------------------------------//
function insertmovcredito(dinero,description,categoria,idempleado){
  if(confirm('�Estas seguro que quieres realizar este credito?')){
  var idencargado =$("#selUsers").val();
  $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{dinero:dinero,description:description,categoria:categoria,idempleado:idempleado,idencargado:idencargado}, function(json){
     json = verificaJSON(json);
     loadPage(json);
   });
  alert("La caja esta informada sobre este moviemiento!");
  }
}
//--------------------------------------------------------IMPRIMIR CUENTA--------------------------------------------------------//
function imprimircuenta(){
	nameempleado=$("#usuariosTable .amarillo .onomataki").html();
	pagado=$(".pagado").html();
	idemp=$("#usuariosTable .amarillo").attr("id");
    document.location="Presentacion/imprimircuenta.php?name="+nameempleado+"&id="+idemp+"&pagado="+pagado;
}
//--------------------------------------------------------PAGAR CREDITO--------------------------------------------------------//
function pagarcredito(){
 idempleado=$("#usuariosTable .amarillo").attr("id");
 var money= $("#money").val();	
 var idencargado =$("#selUsers").val();
 if(confirm('�Estas seguro que quieres realizar este movimiento?')){
  $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{money:money,idempleado:idempleado,idencargado:idencargado}, function(json){
     json = verificaJSON(json);
     loadPage(json);
   });
 }
}
//--------------------------------------------------------DO SEARCH NOMBRE EMPLEADO--------------------------------------------------------//
function doSearch(){
 if(timeoutHnd) clearTimeout(timeoutHnd);
 timeoutHnd = setTimeout(nombreReload,500);
}
function nombreReload(){
 var mask = jQuery("#searchNombre").val();
 //Presentacion/jsongrid.php?q=trabajador&nd='+new Date().getTime()
 //jQuery("#list3").setGridParam({url:"/recepcion/Presentacion/jsongrid.php?q=trabajador&nm_mask="+nm_mask,page:1}).trigger("reloadGrid");
 $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{mask:mask}, function(json){
     json = verificaJSON(json);
   loadusuarios(json);
   $("#ticketsTable").html(" ")
   $("#movimientosTable").html(" ");
   });
}
</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principalesCj" style="width:39%" >
	
	<div class="box_amarillo" style="width:50%; margin-top:5px;float:right">
	<div><span class="label"><b><h3>Empleados:</h3></b></span>
	  
	   <div class="row" align="left">
       <table class="green">
       <tr class="green"><td><h6>Buscar Nombre:</h6></td><td><input id="searchNombre" type="text" size="10" value="" onkeydown="doSearch()"/></td></tr>
   	   </table>
   	   </div>
   	   <br/>
	  
	  <div style="height:40%;overflow:auto">
      <table id="usuariosTable" width=97% cellpadding=0 cellspacing=1>
      </table>
      </div>
	</div>
	</div>
		
	<div class="box_amarillo" style="width:36%; margin-top:15px;float:left">
	 <div><span class="label"><b><h3>Gestion Empleados:</h3></b></span>
	     
   <div id="b5" style="float:left; margin-top:5px;">			
	 <div style="margin-top:5px;margin-left:20px;width:50%;float:left"><span><input type="button" value="Crear Cuenta" id="reporte" onClick="changedisplay('b6');changedisplay('b5');"/></span></div>
     <div style="margin-top:5px;margin-left:20px;width:50%;float:left"><span><input type="button" value="Eliminar Cuenta" onClick="eliminar_cuenta();"/></span></div>
    </div>
	     
	     
	     <div id="b6" style="float:left; margin-top:5px;" class="changedisplay">			
			   <div class="row" align="left">
      		     <table class="green">
      		     <tr class="green"><td><h6>Nombre:</h6></td><td><input id="nombreEmpleado" type="text" size="10" value=""/></td></tr>
   			     <tr class="green"><td><h6>Tipo:</h6></td><td><select id="tipo"><option value="0">Empleado</option><option value="1">Cliente</option></select></td></tr>
   			     </table>
   			     <input type="button" value="Guardar" style="margin-top:5px;margin-left:20px" onClick="crear_cuenta();"/>
                 <input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="changedisplay('b5');changedisplay('b6');"/>	
		       </div>
		 </div>
		 
		 <div id="buemp" style="float:left; margin-top:5px;" class="changedisplay">			
			   <div class="row" align="left">
      		     <table class="green">
      		     <tr class="green"><td><h6>Nombre:</h6></td><td><input id="nombrebuscar" type="text" size="10" value=""/></td></tr>
   			     </table>
   			    </div>
		 </div>
      
      
	
	 <div style="clear:both"></div> 
	 </div>
	</div>
	<div style="clear:both"></div> 
	

    <div class="box_amarillo" style="margin-top:10px;margin-left:10px">
		<div><span class="label"><b><h3>Anadir Credito:</h3></b></span>
		<form name="cajaInSac">
			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:10px"><span>Entrada:</span></div>
      		<div style="margin-top:10px"><span><input id="input_money" name="inputmoney" type="text" size="25" value=""/></span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:10px"><span>categoria:</span></div>
      		<div style="margin-top:10px"><span><select id="categoria"></select></span></div>
   			</div>
   			<div class="row" align="left">
   				<div id="noreception">
   				<div style="width:120px;float:left;margin-top:10px"><span>Description:</span></div>
   				<textarea id="description" style="float:left;margin-top:10px"></textarea>
   				
   				<div style="clear:both"></div>
      		    <div style="width:120px;float:left;margin-left:150px;margin-top:10px"><span><input type="button" value="Acceptar" id="accM" onClick="insertMovimiento(input_money.value,description.value,categoria.value)"/></span></div>
   				</div>
   				
   			</div>
   			<div style="clear:both"></div>
		</form> 
		</div>
		</div>
		
		<div class="box_amarillo" style="margin-top:15px;">
	    <div><span class="label"><h3>Cobrar Credito:</h3><span><input id="money" type="text"/></span><span style="margin-left:20px"><input type="button" value="Acceptar" id="accM" onClick="pagarcredito();"/></span></span>
	    </div>
	    </div>
	<div style="clear:both"></div> 
		
		
</div>

<div id="secundarioesCj" style="width:60%">

	<h5 class="titulos">Comandas a Credito realizadas</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=5%><h6>ID</h6></td><td width=9%><h6><center>estado</center></h6></td><td width=25%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6>Total</h6></td><td width=10%><h6>Cliente</h6></td><td><h6><center>Nombre de Cliente</center></h6></td></tr>
    </table>
    <div style="height:50%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
    </div>
   	
   	
   	<h5 class="titulos">Movimientos a Credito realizados</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=20%><h6><center>Fecha Hora</center></h6></td><td width=8%><h6>tipo</h6></td><td width=9%><h6>dinero</h6></td><td><h6><center>descripcion</center></h6></td><td width=14%><h6>categoria</h6></td><td width=10%><h6>encargado</h6></td></tr>
    </table>
    <div style="height:26%;overflow:auto">
    <table id="movimientosTable" width=97% cellpadding=0 cellspacing=1>
    </table>
    </div>
    <div class="row" align="left" style="height:5%;overflow:auto">
      <div style="margin-left:50px;width:120px;float:left;"><span style="font-weight:bold;font-size: 13pt">Total:</span><span class="total" style="font-weight:bold;font-size: 12pt">0</span></div>
      <div style="margin-left:50px;width:120px;float:left;"><span style="font-weight:bold;font-size: 13pt">Pagado:</span><span class="pagado" style="font-weight:bold;font-size: 12pt">0</span></div>
      <div style="margin-left:50px;width:120px;float:left;"><input type="button" value="Imprimir Cuenta" id="an" onClick="imprimircuenta();"/></span></div>
      		
      		
   		<div style="clear:both"></div>
   	</div>
   

</div>
<br/>


</body>
</html>


