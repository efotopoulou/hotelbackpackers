<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_caja.php');

$admin = "disabled='true'";
//$admin = " ";
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
tr{background:#FFF;text-align:right}
table{background:#DDD}
.btnunpress{background:#e0edfe}
.redtext{color:red}
.verde{color:#4AD411}
.yellow{background: #FAF2BE}
.orange{color: #EB7800}
.changedisplay{display:none}
</style>
	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/guate.js"></script>
	<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
   //al iniciar la pagina si la caja esta abierta se carga el fondoinicial que esta en la bd 
   //y informa el cajero sobre el estado actual de la caja
<?php
$caja=new caja();
$fondoc=$caja->get_fondo_caja();

$categoria=$caja-> get_categories();
for($i=0;$i<count($categoria);$i++) {
?>
    if(<?php echo($categoria[$i]->id_categoria); ?>==7)  $("#categoria").append("<option <?php echo $admin ?> value='<?php echo($categoria[$i]->id_categoria); ?>'><?php echo($categoria[$i]->nombre); ?></option>");   
	else $("#categoria").append("<option value='<?php echo($categoria[$i]->id_categoria); ?>'><?php echo($categoria[$i]->nombre); ?></option>");

<?php } ?>
$(".fondo").html(<?php echo($fondoc);?>);
recargaEstadoCaja();
$("#efectivo_cerrar").val("0").attr({disabled:false});
$("#output_money").attr({disabled:false});
$("#input_money").attr({disabled:false});
$("#input_money").val("");
$("#output_money").val("");
$("#description").val("");
$("#input_money").keyup(function () {
	if($("#input_money").val().length==0)$("#output_money").attr({disabled:false});
	else $("#output_money").attr({disabled:true});
})
$("#output_money").keyup(function () {
	if($("#output_money").val().length==0)$("#input_money").attr({disabled:false});
	else $("#input_money").attr({disabled:true});
})

//$("#categoria").change(function () {
 //         if(this.value==8) {
 //         	changedisplay('reception');
 //         	changedisplay('noreception');
 //         	$("#input_money,#output_money").attr({disabled:true});	
 //         }else{
//          $("#reception").addClass("changedisplay");
//         $("#noreception").removeClass("changedisplay");
 //         $("#input_money,#output_money").attr({disabled:false});	
 //         }
//        })

});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
//--------------------------------------------------------introducir fondo----------------------------------------------------------------------//
//se llama cuando la caja esta cerrada.informa el cajero que la caja esta cerrada y le pide un fondo para abrirla.
function abrirCaja(){
 $.blockUI({ message: $('#cajaCerrada')});
}
//--------------------------------------------------------ABRIR CAJA----------------------------------------------------------------------//
function openCaja(fondo,turno){
    $.getJSONGuate("Presentacion/jsongestioncaja.php",{fondo:fondo,turno:turno}, function(json){
      json = verificaJSON(json);
      loadPage(json);  
    });
   $.unblockUI();
   $("input,textarea,#categoria").attr({disabled:false});
   $("#Abr").attr({disabled:true});
   $(".fondo").html(fondo);
   $(".supEfectivo").html(fondo);
}
function desactivar(){
$("#efectivo_cerrar,#input_money,#output_money,#categoria,#description,#cob,#an,#fact,#accM,#accV,#reporteexcel,#reporte,#Cerr").attr({disabled:true});
$("#Abr").attr({disabled:false});
}

//--------------------------------------------------------CERRAR CAJA----------------------------------------------------------------------//
function closeCaja(efectivoCerrar){

 if(confirm('ï¿½Estas seguro que quieres cerrar la caja?')){
    $.blockUI({ message: $('#closingcash')});	
    $.getJSONGuate("Presentacion/jsongestioncaja.php",{efectivo:efectivoCerrar}, function(json){
      if (!json["Mensaje"]){
       alert("Error,la caja no esta cerrada."); 
	   } else {alert (json["Mensaje"]);
              //$("#efectivo_cerrar,#input_money,#output_money,#categoria,#description,#cob,#an,#fact,#accM,#accV,#reporteexcel,#reporte").attr({disabled:true});
              desactivar();
              }
     $.unblockUI();
    });
    }else {$("#efectivo_cerrar").val("");}

}
//--------------------------------------------------------INSERT MOVIMIENTO(o sea ingresar o sacar dinero)--------------------------------------------------------//
function insertMovimiento(entrada,salida,description,categoria){
if (description && categoria){
 if (parseFloat(entrada)) {
  var tipo="entrada";
  var dinero=redondea(entrada);
  var description=description;
  var categoria=categoria;
  callGestionCaja(tipo,dinero,description,categoria);
 }
 else if (parseFloat(salida)){
  var tipo="salida";
  var dinero=redondea(salida);
  var description=description;
  var categoria=categoria;
  callGestionCaja(tipo,dinero,description,categoria);
 }else alert("Introduce correctamente el movimiento!");
} else  alert("Tienes que introducir una descripcion!");


//vaciar los campos usados
$("#input_money").val("");
$("#output_money").val("");
$("#description").val("");
$("#categoria").val(1);
$("#output_money").attr({disabled:false});
$("#input_money").attr({disabled:false});
}
//--------------------------------------------------------INSERT VENTA DE RECEPTION(como movimiento)--------------------------------------------------------//
function insertVentaReception(cantity,idproductoreception){
 aux=$("#productoreception option:selected").html();
 n=$("#preciolimitado:checked").length;
 idencargado =$("#selUsers").val();
 
 //insertar la venta como movimiento y informar el control del stock
 if(confirm('ï¿½Estas seguro que quieres realizar este movimiento?')){
  $.getJSONGuate("Presentacion/jsongestioncaja.php",{idproducto:idproductoreception,cantity:cantity,checked:n,description:aux,idencargado:idencargado}, function(json){
     json = verificaJSON(json);
     $(".entrymov").html(json.TotalEntradas);
     $(".exitmov").html(json.TotalSalidas); 
     supuestoEfectivo();
     loadPage(json);
   });
  alert("La caja esta informada sobre este moviemiento!");
  }
 
 //callGestionCaja("entrada",inputmoney,cantity+" de "+aux,8);
 //vaciar los campos usados
 $("#input_money").val("");
 $("#cantity").val("1");
 $("#productoreception").val("");
 $("#output_money").attr({disabled:false});
 $("#input_money").attr({disabled:false});
 //informar el control del stock
 // $.getJSONGuate("Presentacion/jsongestioncaja.php",{idproducto:idproductoreception,cantity:cantity}, function(json){json = verificaJSON(json);});
}

//--------------------------------------------------------CALL GESTION CAJA--------------------------------------------------------//
function callGestionCaja(tipo,dinero,description,categoria){
//if (categoria==8) {
//$.blockUI({ message: $('#reception')});	
//}else{
  if(confirm('ï¿½Estas seguro que quieres realizar este movimiento?')){
  var idencargado =$("#selUsers").val();
  $.getJSONGuate("Presentacion/jsongestioncaja.php",{tipo:tipo,dinero:dinero,description:description,categoria:categoria,idencargado:idencargado}, function(json){
     json = verificaJSON(json);
     $(".entrymov").html(redondea(json.TotalEntradas));
     $(".exitmov").html(redondea(json.TotalSalidas)); 
     supuestoEfectivo();
     loadPage(json);
   });
  alert("La caja esta informada sobre este moviemiento!");
  }
//}
}
//-------------------------------------------SUPUESTO EFECTIVO Y CORTE-------------------------------------------------//
function supuestoEfectivo(){
	var totalEntradas=0;
	var totalSalidas=0;
	var totalTickets=0;
	var ventaR=0;
	
	var fondo=parseFloat($(".fondo").html());
	if($(".entrymov").html()) totalEntradas=parseFloat($(".entrymov").html());
	if($(".exitmov").html()) totalSalidas=parseFloat($(".exitmov").html());
	if($(".totalTickets").html()) totalTickets=parseFloat($(".totalTickets").html());
	if($(".totventarecepcion").html()) ventaR=parseFloat($(".totventarecepcion").html());
	 
	var supEfectivo =redondea(fondo+totalEntradas+totalTickets-totalSalidas);
	$(".supEfectivo").html(supEfectivo);
	//aux = $(".totalTickets").html();
}
//-------------------------------------------recargaEstadoCaja-------------------------------------------
//pedir de la bd los datos de los movimientos y Tickets y ponerlos a la tabla de la pantalla
function recargaEstadoCaja(){
 $.getJSONGuate("Presentacion/jsongestioncaja.php", function(json){
  json = verificaJSON(json);
  loadPage(json); 
  });	
}
//-------------------------------------------LOAD PAGE----------------------------------------------------------//
function loadPage(json){
 $(".entrymov").html(redondea(json.TotalEntradas));
 $(".exitmov").html(redondea(json.TotalSalidas)); 
 var totTickets=redondea(json.TotalTickets);
 $(".totalTickets").html(totTickets);    
     
     supuestoEfectivo();
     
  if (json.MovimientosInfo){
  	  
  	  $("#movimientosTable").html(" ");
      for(i=0;i<json.MovimientosInfo.length;i++) {
        idMov=json.MovimientosInfo[i].idmovimiento;
        $("#movimientosTable").append("<tr id=M"+idMov+"><td class='checkbox' width=2%><input type='checkbox'  onclick='changeClass(\"M"+idMov+"\");'></td><td width=18%><h6>"+json.MovimientosInfo[i].fechaHora+"</h6></td><td width=8%><h6>"+json.MovimientosInfo[i].tipo+"</h6></td><td width=8%><h6>"+json.MovimientosInfo[i].dinero+"</h6></td><td><h6>"+json.MovimientosInfo[i].descripcion+"</h6></td><td width=16%><h6>"+json.MovimientosInfo[i].categoria+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].encargado+"</h6></td></tr>");
        if(json.MovimientosInfo[i].tipo=="anulado"){
        	$("#M"+idMov).css({ textDecoration:"line-through"});
        	$("#M"+idMov).addClass("redtext");
        }		
      }
  }
   if (json.TicketsInfo){
  	  var aux="";
  	  $("#ticketsTable").html(" ");
     for(i=0;i<json.TicketsInfo.length;i++) {
     	camb = cambio(json.TicketsInfo[i].efectivo,json.TicketsInfo[i].total);
     	numComanda=showid(json.TicketsInfo[i].numComanda);
        idCom="T"+json.TicketsInfo[i].idComanda;
     	nombre = descripcion(json.TicketsInfo[i].free,json.TicketsInfo[i].nombre)

        $("#ticketsTable").append("<tr id="+idCom+"><td class='checkbox' width=2%><input type='checkbox'  onclick='changeClass(\""+idCom+"\");'></td><td width=10%><h6 class='numcomand'>"+numComanda+"</h6></td><td width=8%><h6 class='estadoh6'>"+json.TicketsInfo[i].estado+"</h6></td><td width=17%><h6>"+json.TicketsInfo[i].fechaHora+"</h6></td><td width=6%><h6>"+json.TicketsInfo[i].total+"</h6></td><td width=8%><h6>"+json.TicketsInfo[i].efectivo+"</h6></td><td width=8%><h6>"+camb+"</h6></td><td width=10%><h6>"+json.TicketsInfo[i].tipoCliente+"</h6></td><td><h6>"+nombre+"</h6></td></tr>");
    
        $("#"+idCom+" td:not(.checkbox)").mousedown(function(e){
           var num=$("#"+this.parentNode.id+" .numcomand").html();
           showpedido(this.parentNode.id,num);
        });
         if(json.TicketsInfo[i].estado=="anulado")$("#"+idCom).css({ textDecoration:"line-through"});
         if (json.TicketsInfo[i].estado=="cobrado"){$("#T"+json.TicketsInfo[i].idComanda).addClass("verde");}
        if (json.TicketsInfo[i].estado=="anulado"){$("#T"+json.TicketsInfo[i].idComanda).addClass("redtext");}	
       }
   }	
}
//-------------------------------------------CALCULAR CAMBIO-------------------------------------------------//
function cambio(efectivo,total){
var camb1 = (parseFloat(efectivo)-parseFloat(total));
var camb = 	(Math.round(camb1*100)/100);
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
$("#"+id).toggleClass("btnunpress");
$("#"+id).toggleClass("redtext");
}

function redondea(num){
	parseFloat(num);
	return (Math.round(num*100)/100);
}
//-------------------------------------------SHOW PEDIDO---------------------------------------------------//
function showpedido(id,numcomanda){
    //alert(numcomanda);
    if ($("#ticketsTable tr").hasClass("detail"+id)){
       	$(".detail"+id).remove();
    }else {
	  var id1=id.substring(1);  
	 $.getJSONGuate("Presentacion/jsongestioncaja.php",{idComDetail:id1,numcomanda:numcomanda}, function(json){
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
//-------------------------------------------ANULAR TICKET-------------------------------------------------//
function anularTicket(){
 if(confirm('¿Estas seguro que quieres anular estos tiquets?')){
  var comandas;
  var numcomandas;
  $("#ticketsTable .btnunpress").each(function (){
   var id = this.id;
   if (!comandas) comandas = id;
   else comandas+=","+id;
   changeClass(id);
   var num=$("#ticketsTable .btnunpress .numcomand").html();
   if (!num)num="null";
   if (!numcomandas) numcomandas = num; 
   else  numcomandas+=","+num;
  });

  if(comandas){ 
   $.getJSONGuate("Presentacion/jsongestioncaja.php",{comandasAnuladas:comandas,numcomandasAnuladas:numcomandas}, function(json){
    json = verificaJSON(json);
    loadPage(json);
   });
  }else alert("Por favor elige la comanda que desea anular!");
 }
}
//-------------------------------------------ANULAR MOVIMIENTO-------------------------------------------------//
function anularMovimiento(){
 if(confirm('¿Estas seguro que quieres anular estos tiquets?')){
  var movimientos;
  $("#movimientosTable .btnunpress").each(function (){
   var id = this.id;
   if (!movimientos) movimientos = id;
   else movimientos+=","+id;
   changeClass(id);
  });
  if(movimientos){ 
   $.getJSONGuate("Presentacion/jsongestioncaja.php",{movimientosAnulados:movimientos}, function(json){
    json = verificaJSON(json);
    loadPage(json);
   });
  }else alert("Por favor elige el movimiento que desea anular!");
 }
}

function imprimir(){
 var nom = $("#nombre").val();
 var ap1 = $("#ap1").val();
 var ap2 = $("#ap2").val();
 var nit = $("#nit").val();
 var nofact = $("#nofact").val();  
 //falta la hora
if(nom && ap1 && nit && nofact){
  $.unblockUI();
  $(".btnunpress").each(function () {
        $.getJSONGuate("Presentacion/jsongestioncaja.php",{idComandafacturada:this.id}, function(json){
        json = verificaJSON(json);
        loadPage(json);
        });
        changeClass(this.id);
        alert(this.id);
  });
}else alert("Por favor rellena correctamente los campos!");
}
function cancelarapertura(){
$.unblockUI();
$("#efectivo_cerrar,#input_money,#output_money,#categoria,#description,#cob,#an,#fact,#accM,#accV,#reporteexcel,#reporte").attr({disabled:true});	
}
//-------------------------------------------REPORTE CAJA (HTML - EXCEL)-------------------------------------------------//
function reportecaja(type){
	turno =$(".turnico").html();
	user =$("#selUsers option:selected").html();
   if(type=="html") document.location="Presentacion/reportehtml.php?turno="+turno+"&encargado="+user;
   if(type=="excel") document.location="Presentacion/reportexcel.php?turno="+turno+"&encargado="+user;
}
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
</script>
<body
<?php
$openOrClose=new caja();
$estadocaja=$openOrClose->estado_caja();
if ($estadocaja==0){
?>
onload="abrirCaja()"
<?php }?>
>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menuRestBar.php'); ?>
<div  id="cajaCerrada" style="display:none;margin:0 auto;text-align:center;">
Introduzca el fondo que va a poner en la caja<br />
Fondo: <input type="text" id="fondo" name="fondo"/>
Turno:<select id="turno1"><option  value='Manana'>Manana</option><option value='Tarde'>Tarde</option><option value='Noche'>Noche</option></select>
<input type="button" value="Aceptar" onClick="openCaja(fondo.value,turno1.value)" />	
<input type="button" value="Cancelar" onClick="cancelarapertura();" />
</div>

<div  id="closingcash" style="display:none;margin:0 auto;text-align:center;">
La caja se esta cerrando.Por favor espere.<br />
</div>

<div  id="factura" style="display:none;margin:0 auto;text-align:center;" class="box_amarillo">
<center><table class="box_amarillo">
<tr><td><h6>Nombre:</h6></td><td><input type="text" id="nombre"/></td></tr>
<tr><td><h6>Apellido1:</h6></td><td><input type="text" id="ap1"/></td></tr>
<tr><td><h6>Apellido2:</h6></td><td><input type="text" id="ap2"/></td></tr>
<tr><td><h6>N.I.T:</h6></td><td><input type="text" id="nit"/></td></tr>
<tr><td><h6>Num.factura:</h6></td><td><input type="text" id="nofact"/></td></tr>
</table></center>
<input type="button" value="Imprimir factura" onClick="imprimir()" />
<input type="button" value="Cancelar" onClick="$.unblockUI();" />
</div>
<div  id="facturaPaImprmir" style="display:none;margin:0 auto;text-align:center;" class="box_amarillo">
<center><table class="box_amarillo" id="print">
</table></center>
</div>

<div id="principalesCj">
	<h5 class="titulos">Resumen de Caja</h5>
	
		<div class="box_amarillo" style="margin-top:10px;margin-left:10px">
		<div><span class="label"><b><h3>Estado de caja</h3></b></span>
		<form name="cajaresumen">
			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:5px"><span>Fondo Inicial:</span></div>
      		<div style="margin-top:5px"><span class="fondo">0</span></div>
   			</div>
   		
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Supuesto Efectivo:</span></div>
      		<div><span class="supEfectivo">0</span></div>
   			</div>
		</form> 
		</div>
		</div>
		
			<div class="box_amarillo" style="margin-top:10px;margin-left:10px">
		<div><span class="label"><b><h3>Ingresar o sacar dinero de la caja:</h3></b></span>
		<form name="cajaInSac">
			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:10px"><span>Nueva Entrada:</span></div>
      		<div style="margin-top:10px"><span><input id="input_money" name="inputmoney" type="text" size="25" value=""/></span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:10px"><span>Nueva Salida:</span></div>
      		<div style="margin-top:10px"><span><input id="output_money" name="outputmoney" type="text" size="25" value=""/></span></div>
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
      		    <div style="width:120px;float:left;margin-left:150px;margin-top:10px"><span><input type="button" value="Acceptar" id="accM" onClick="insertMovimiento(input_money.value,output_money.value,description.value,categoria.value)"/></span></div>
   				</div>
   				
   			</div>
   			<div style="clear:both"></div>
		</form> 
		</div>
		</div>

			<div class="box_amarillo" style="margin-top:10px;margin-left:10px">
		<div><span class="label"><b><h3>Cerrar Caja:</h3></b></span>
		<form name="cajaInSac">
			<div class="row" align="left">
      		<div style="width:120px;float:left;margin-top:5px"><span>Efectivo:</span></div>
      		<div style="margin-top:5px"><span><input id="efectivo_cerrar" name="efectivo_cerrar" type="text" size="25" value=""/></span></div>
   			</div>
   			<div class="row" align="left">
      		<div style="width:120px;float:left"><span><input type="button" value="Cerrar" id="Cerr" onClick="closeCaja(efectivo_cerrar.value)"/></span></div>
   			</div>
   			<div style="clear:both"></div>
		</form> 
		</div>
		</div>
		
		
		<div class="box_amarillo" class="row" style="margin-top:10px;margin-left:10px">
      		<div style="width:120px;float:left;margin-top:5px"><span><h3>Abrir Caja:</h3></span></div>
      		<div style="width:120px;float:left"><span><input type="button" value="Abrir Caja" id="Abr" onClick="window.location.reload();"/></span></div>
   		    <div style="clear:both"></div>
   		</div>

			
</div>

<div id="secundarioesCj">

	<h5 class="titulos">Comandas realizadas en el Restaurante</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=10%><h6>ID</h6></td><td width=8%><h6>Estado</h6></td><td width=17%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6><h6>Total</h6></h6></td><td width=8%><h6>efectivo</h6></td><td width=8%><h6>cambio</h6></td><td width=10%><h6>Cliente</h6></td><td><h6><center>Descripcion</center></h6></td></tr>
    </table>
  <div style="height:30%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
   </div>
  <div style="height:5%;width:100%;overflow:auto">
    <div class="row" align="left">
     <!--<div style="margin-top:20px;margin-left:60px;float:left;width:20%"><span><input type="button" value="Cobrar Tiquet" id="cob" onClick="cobrarTicket();"/></span></div> -->
     
	     <div style="margin-left:60px;margin-top:5px;width:20%;float:left"><span><input <?php echo $admin ?> type="button" value="Anular Tiquet" id="an" onClick="anularTicket();"/></span></div>
	     
     <div style="margin-top:5px;width:20%;float:left"><span><input type="button" value="Reporte Caja HTML" id="reporte" onClick="reportecaja('html')"/></span></div>
     <div style="margin-top:5px;width:20%;float:left"><span><input type="button" value="Reporte Caja EXCEL" id="reporteexcel" onClick="reportecaja('excel')"/></span></div>
     

      		<div style="margin-top:5px;float:left"><span>Total Comandas:</span><span class="totalTickets">0</span></div>
      	

   
   </div>
  </div>
<br/>

   


<!-- <div><span><input type="button" value="Cobrar Tiquet" onClick="cobrarTicket();"/></span></div> -->


     <h5 class="titulos">Movimientos realizados</h5>
     <table  width=96% cellpadding=0 cellspacing=1>
      <tr><td width=2%>&nbsp;</td><td width=18%><h6><center>Fecha Hora</center></h6></td><td width=8%><h6>tipo</h6></td><td width=8%><h6>dinero</h6></td><td><h6><center>descripcion</center></h6></td><td width=16%><h6>categoria</h6></td><td width=10%><h6>encargado</h6></td></tr>
     </table>
    <div style="height:35%;overflow:auto">
     <table id="movimientosTable" width=98% cellpadding=0 cellspacing=1></table>
    </div>
    <div style="margin-left:60px;margin-top:5px;width:20%;float:left"><span><input <?php echo $admin ?>  type="button" value="Anular Movimiento" id="am" onClick="anularMovimiento();"/></span></div>
    
    <div style="margin-top:5px;float:left"><span >Total Entradas:</span><span class="entrymov">0</span></div>  		
    <div style="margin-top:5px;margin-left:25px;float:left"><span>Total Salidas:</span><span class="exitmov">0</span></div>
      		
</div>
<br/>


</body>
</html>


