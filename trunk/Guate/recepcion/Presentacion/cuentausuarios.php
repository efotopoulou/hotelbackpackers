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
$(document).ready(function(){
  $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{usuario:"yes"}, function(json){
     json = verificaJSON(json);
   loadusuarios(json);
   });
   
  <?php
  $anyos=new estadisticas();
  $year=$anyos->yearsCaja();
  for($i=0;$i<count($year);$i++) {
  ?>
	$("#years").append("<option value='<?php echo($year[$i]); ?>'><?php echo($year[$i]); ?></option>");

  <?php } 
  $hoy = getdate();
  $year = $hoy['year'];
  $month = $hoy['mon'];
  ?>
$("#years").val("<?php echo($year); ?>");
$("#month").val("<?php echo($month); ?>");
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
//$("#"+id).toggleClass("redtext");	
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
        $("#usuariosTable").append("<tr id=T"+json.UsuariosInfo[i].idTrabajador+" onmousedown='changeClassUsuario(this.id);loadcuenta(this.id);'><td><h4><center>"+json.UsuariosInfo[i].nombre+"</center></h4></td></tr>");		
        $("#T"+json.UsuariosInfo[i].idTrabajador).addClass("green");
        }
     }
}
//-------------------------------------------LOAD CUENTA----------------------------------------------------------//
function loadcuenta(id){
year = $("#years").val();
month = $("#month").val();
$(".total").html("0");
$.getJSONGuate("Presentacion/jsoncuentausuarios.php",{idusuario:id,year:year,month:month}, function(json){
     json = verificaJSON(json);
     loadPage(json);
       });	
}
function loadPage(json){
if (json.TicketsInfo){
  	  $("#ticketsTable").html(" ");
     for(i=0;i<json.TicketsInfo.length;i++) {
     	numComanda=showid(json.TicketsInfo[i].numComanda);
     	idCom=json.TicketsInfo[i].idComanda;
        $("#ticketsTable").append("<tr id="+idCom+"><td class='checkbox' width=2%><input type='checkbox'  onclick='btncolor(\""+idCom+"\");'></td><td width=5%><h6>"+numComanda+"</h6></td><td width=9% class='estado'><h6 class='estadoh6'>"+json.TicketsInfo[i].estado+"</h6></td><td width=25%><h6>"+json.TicketsInfo[i].fechaHora+"</h6></td><td width=6%><h6>"+json.TicketsInfo[i].total+"</h6></td><td width=10%><h6>"+json.TicketsInfo[i].clientType+"</h6></td><td><h6>"+json.TicketsInfo[i].nombre+"</h6></td></tr>");
        $("#"+idCom+" td:not(.checkbox)").mousedown(function(e){
           showpedido(this.parentNode.id);
        });
        if (json.TicketsInfo[i].estado=="cobrado"){$("#"+json.TicketsInfo[i].idComanda).addClass("verde");}
        if (json.TicketsInfo[i].estado=="anulado"){$("#"+json.TicketsInfo[i].idComanda).addClass("redtext");}
        //alert(json.TicketsInfo[i].idComanda);		
        }
        }else{
        $("#ticketsTable").html(" ");
        alert("Este mes el usuario no ha consumido nada!");
        }
        if (json.TotalTickets)  $(".total").html(json.TotalTickets);	
	
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
//-------------------------------------------COBRAR TICKET-------------------------------------------------//
function cobrarTicket(){
var comandas;
var cobradas=false;
$("#ticketsTable .amarillo").each(function (){
  if (!comandas) comandas = this.id;
  else comandas+=","+this.id;
  btncolor(this.id);
  if($("#"+this.id+" .estadoh6").html()=="cobrado") cobradas=true;
})

 if (cobradas)alert("Algun ticket que has elegido esta ya cobrado!");
 else {
  var idusuario=$("#usuariosTable .amarillo").attr("id");
   if(comandas){ 
     year = $("#years").val();
     month = $("#month").val();
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{comandas:comandas,idusuario:idusuario,year:year,month:month}, function(json){
      json = verificaJSON(json);
      loadPage(json);
     });
   }else alert("Por favor elige la comanda que desea cobrar!");
  }
}
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
//-------------------------------------------CREAR CUENTA-------------------------------------------------//
function crear_cuenta(){
var nombreEmpleado= $("#nombreEmpleado").val();
  if(confirm('Estas seguro que quieres crear una nueva cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{nombreEmpleado:nombreEmpleado}, function(json){
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
var id_delete=$("#usuariosTable .amarillo").attr("id");
  if(confirm('Estas seguro que quieres eleminar esta cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{id_delete:id_delete}, function(json){
      json = verificaJSON(json);
      loadusuarios(json);
      $("#ticketsTable").html(" ");
      $("#total").html("0");
     });
  }
}
</script>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principalesCj" style="width:39%" >
	
	<div class="box_amarillo" style="width:50%; margin-top:5px;float:right">
	<div><span class="label"><b><h3>Empleados:</h3></b></span>
	  <div style="height:40%;overflow:auto">
      <table id="usuariosTable" width=97% cellpadding=0 cellspacing=1>
      </table>
      </div>
	</div>
	</div>
	
	<div class="box_amarillo" style="width:35%;margin-top:40px;float:left">
	<table style="margin-left:20px;"><tr>
	<td class="green"><h6><center>Anos</center></h6><center><select id="years"></select></center></td></tr>
    <tr><td class="green"><h6><center>Meses</center></h6>
     <center><select id='month'>
     <option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option>	
	 <option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option>
	 </select></center>
     </td>
     </tr></table>
	</div>
		
	<div class="box_amarillo" style="width:35%; margin-top:15px;float:left">
	 <div><span class="label"><b><h3>Gestion de Empleados:</h3></b></span>
	     
	     <div id="b5" style="float:left; margin-top:5px;">			
	           <div style="margin-top:5px;width:50%;float:left"><span><input type="button" value="crear cuenta" id="reporte" onClick="changedisplay('b6');changedisplay('b5');"/></span></div>
               <!--<div style="margin-top:5px;width:50%;float:left"><span><input type="button" value="Eliminar cuenta" id="reporteexcel" onClick="eliminar_cuenta();"/></span></div> -->
         </div>
	     
	     
	     <div id="b6" style="float:left; margin-top:5px;" class="changedisplay">			
			   <div class="row" align="left">
      		     <div style="margin-top:5px"><span>Nombre:</span><span><input id="nombreEmpleado" type="text" size="20" value=""/></span></div>
   			     <input type="button" value="Guardar" style="margin-top:5px;margin-left:20px" onClick="crear_cuenta();"/>
                 <input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="changedisplay('b5');changedisplay('b6');"/>	
		       </div>
      </div>
	
	 <div style="clear:both"></div> 
	 </div>
	</div>
	<div style="clear:both"></div> 
	

    
		
</div>

<div id="secundarioesCj" style="width:60%">

	<h5 class="titulos">Comandas a Credito realizadas</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=5%><h6>ID</h6></td><td width=9%><h6><center>estado</center></h6></td><td width=25%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6><h6>Total</h6></h6></td><td width=10%><h6>Cliente</h6></td><td><h6><center>Nombre de Cliente</center></h6></td></tr>
    </table>
    <div style="height:30%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
    </div>
   	
   	
   	<h5 class="titulos">Movimientos a Credito realizados</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=5%><h6>ID</h6></td><td width=9%><h6><center>estado</center></h6></td><td width=25%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6><h6>Total</h6></h6></td><td width=10%><h6>Cliente</h6></td><td><h6><center>Nombre de Cliente</center></h6></td></tr>
    </table>
    <div style="height:40%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
    </div>
    <div class="row" align="left" style="height:10%;overflow:auto">
      		<div style="width:120px;float:left;margin-left:100px"><span><h1>Total:</h1></span></div>
      		<div><span class="total" style="font-weight:bold;font-size: 12pt">0</span><span style="margin-left:100px"><input type="button" value="Cobrar Tiquet" id="an" onClick="cobrarTicket();"/></span></div>
   		<div style="clear:both"></div>
   	</div>
   

</div>
<br/>


</body>
</html>


