<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_estadisticas.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Cuenta de usuarios</title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="/common/css/ui.core.css" rel="stylesheet" />
    <link type="text/css" href="/common/css/ui.tabs.css" rel="stylesheet" />
    <link type="text/css" href="/common/css/ui.theme.css" rel="stylesheet" />
    <link type="text/css" href="/common/css/ui.dialog.css" rel="stylesheet" />
   	<link rel="stylesheet" href="/common/css/flora.datepicker.css" type="text/css" media="screen" title="Flora (Default)" />
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
div.growlUI { background: url(/common/img/check48.png) no-repeat 10px 10px }
div.growlUI h1, div.growlUI h2 {
	color: white; padding: 5px 5px 5px 75px; text-align: left
}
</style>
	<script src="/common/js/jquery.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/guate.js"></script>
	<script src="/recepcion/js/cuentausuarios.js"></script>
	<script src="/common/js/ui.datepicker.js"></script>
	<script src="/common/js/ui.datepicker-es.js"></script>
	<script src="/common/js/ui.core.js"></script>
	<script src="/common/js/ui.tabs.js"></script>
	<script type="text/javascript">
	
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
var timeoutHnd;
$(document).ready(function(){
	$(function() {
		$("#tabs").tabs({
			event: 'mouseover'
		});
	});
	$("#fechas").val("");

	$("#fechas").datepicker({  
		dateFormat: "yy-mm-dd", 
		rangeSelect: true,
		showOn: "both",
		buttonImage: "/common/img/calendar.gif", 
		buttonImageOnly: true 
	});

	$("#searchNombre").focus();
	$.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"begin"}, function(json){
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
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principalesCj" style="width:29%;height:91%" >
	
	<div class="box_amarillo" style="margin-top:10px;margin-right:10px">
	<div><span class="label, titleboxes"><b>Gesti&oacute;n Empleados:</b></span>
	  
	   <div class="row" align="left">
       <table class="green">
       <tr class="green"><td><h6>Buscar Nombre:</h6></td><td><input id="searchNombre" type="text" size="10" value="" onkeydown="doSearch()"/></td></tr>
   	   </table>
   	   </div>
   	   <br/>
	  
	  <div style="height:30%;overflow:auto">
      <table id="usuariosTable" width=97% cellpadding=0 cellspacing=1>
      </table>
      </div>
      
         <div style="margin-top:5px;">			
	 <span><input type="button" value="Crear Cuenta" id="reporte" onClick="modalCrearCuenta();"/></span>
     <span><input type="button" value="Eliminar Cuenta" onClick="eliminar_cuenta();"/></span>
    </div>
	     <div id="b6" style="background:#ECF8CB;padding-bottom:3px" class="changedisplay">
			   <div class="row" align="left">
			    <div style="width:100px;float:left;"><span>Nombre:</span></div>
      		    <div><span><input id="nombreEmpleado" type="text" size="10" value=""/></span></div>
		       </div>
			   <div class="row" align="left">
			    <div style="width:100px;float:left;"><span>Tipo:</span></div>
      		    <div><span><select id="tipo"><option value="0">Empleado</option><option value="1">Cliente</option></select></span></div>
		       </div>
   						   
   			     <input type="button" value="Guardar" style="margin-top:5px;margin-left:20px" onClick="crear_cuenta();"/>
                 <input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>	
		 </div>      
	</div>
	</div>
	<div style="clear:both;margin-bottom:15px"></div> 
	
    <div class="box_amarillo" style="margin:10px">
		<div><span class="label, titleboxes"><b>Gesti&oacute;n Cr&eacute;dito:</b></span>
    
   <div id="tabs">
	<ul>
		<li><a href="#tabs-1">A&ntilde;adir Cr&eacute;dito</a></li>
		<li><a href="#tabs-2">Cobrar Cr&eacute;dito</a></li>
	</ul>
	<div id="tabs-1">
			<div class="row" align="left">
      		 <div style="width:100px;float:left;"><span>Entrada:</span></div>
      		 <div><span><input id="input_money" name="inputmoney" type="text" size="10" value=""/></span></div>
   			</div>
   			<div class="row" align="left">
      		 <div style="width:100px;float:left;"><span>Categoria:</span></div>
      		 <div><span><select id="categoria"></select></span></div>
   			</div>
   			<div class="row" align="left">
   				<div id="noreception">
   				<div style="width:100px;float:left"><span>Descripci&oacute;n:</span></div>
   				<div><textarea id="description"></textarea></div>
   				<div style="clear:both"></div>
      		    <div><span><input type="button" value="Aceptar" id="accM" onClick="insertMovimiento(input_money.value,description.value,categoria.value)"/></span></div>
   				</div>
   				
   			</div>
   			<div style="clear:both"></div>
	</div>
	<div id="tabs-2">
	  <div class="row" align="left">
	    <div style="width:100px;float:left;"><span>Cantidad:<span></div>
	    <div><span><input id="money" type="text"/></span></div>
	    <div style="margin-top:10px"><span><input type="button" value="Aceptar" id="accM" onClick="pagarcredito();"/></span></span></div>
	    </div>
	  </div>  
	</div>
   </div><!--tabs-->
		
		</div>
	<div class="box_amarillo" style="margin:10px">
		<div><span class="label, titleboxes"><b>Imprimir Cuentas:</b></span></div>
			<div style="float:right"><input type="button" value="Imprimir Cuenta" onClick="imprimircuenta();"/></div>
			<input type="text" size="25" value="" id="fechas"/>
		</div>
	</div>

</div>
</div></div>

<div id="secundarioesCj" style="width:70%;height:91%">
	<h5 class="titulos">Comandas a Cr&eacute;dito</h5>
	<table  width=97% cellpadding=0 cellspacing=1>
    <tr><td width=2%>&nbsp;</td><td width=5%><h6>ID</h6></td><td width=25%><h6><center>Fecha Hora</center></h6></td><td width=6%><h6>Total</h6></td><td><h6><center>Nombre de Cliente</center></h6></td></tr>
    </table>
    <div style="height:50%;overflow:auto">
    <table id="ticketsTable" width=97% cellpadding=0 cellspacing=1>
    </table>
    </div>
   	
   	
   	<h5 class="titulos">Movimientos a Cr&eacute;dito</h5>
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
   		<div style="clear:both"></div>
   	</div>
   

</div>
<br/>


</body>
</html>


