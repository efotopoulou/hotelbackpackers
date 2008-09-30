<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familia.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_comanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
?>
<html lang="en" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>TPV</title>
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/grid.css" />
		<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />

		<script src="/common/js/jquery-1.2.3.pack.js"></script>
		<script src="/common/js/jquery.blockUI.js"></script>
		<script src="/common/js/jquery.jqGrid.js"></script>
		<script src="/common/js/jquery.hotkeys.js"></script>
		<!--<script src="/common/js/jquery.mousewheel.js"></script>
		<script src="/common/js/jquery.scrollable.js"></script>-->
		<script src="/common/js/jquery.corner.js"></script>
		<script src="/common/js/json.js"></script>
		<script src="/common/js/guate.js"></script>
		<script src="/common/js/tpv/lineacomandascreenbar.js"></script>
		<script src="/common/js/tpv/boxizquierdaarribabar.js"></script>
		<script src="/common/js/tpv/familiasplatillosbar.js"></script>
		<script src="/common/js/tpv/presentacionbar.js"></script>
		<script src="/common/js/tpv/hotkeysbar.js"></script>
		
<script>
$(document).ready(function(){
  //$("#idComanda").val("B<?php $comanda=new comanda();echo $estadocaja=$comanda->getNextMaxIdComanda();?>");
});
</script>		
<script>
// CLASES DE DOMINIO  
//Clase Main
function Main(){

//Atributos	
 this.comandaArray = new Array();
 this.currentComanda = -1;
 this.currentClient;
 this.efectivo;
 this.id_cliente;
 this.calPressedId;

//CREA EFECTO: Pone los valores a 0.
 this.creaEfecto = function () {
  $("#total").val("0");
  listaPedidos.reiniciar();
  }
  //PUSHLINIACOMANDA
 this.pushLiniaComanda = function(){
 	listaPedidos.vaciar();
 	for (var j=0;j<this.comandaArray.length;j++){
      listaPedidos.addComanda();
	  for (var i=0;i<this.comandaArray[j].liniasComanda.length;i++){
	 	if (!this.comandaArray[j].isAbierta()) listaPedidos.addPlatilloFijo(this.comandaArray[j].liniasComanda[i]);
	 	else listaPedidos.addPlatillo(this.comandaArray[j].liniasComanda[i], "row"+new String(j)+new String(i));			
 	  }
      listaPedidos.modifyTotal(this.comandaArray[j].total);
 	}
   $("#total").val(calcularTotal());
   clienteScreen.setClienteName(main.comanda().clienteName);
 }
  
 this.comanda = function(){
 	return this.comandaArray[this.currentComanda];
 }
 this.linia = function(){
  return this.comanda().liniasComanda[this.comanda().numRow];
 }
}

function Comanda(){
  this.liniasComanda = new Array();
  this.numRow=-1;
  this.currentClientType=-1;
  this.efectivo="";
  this.total="";
  this.clienteName="";
  this.id_cliente="";
  this.estado="abierta";
  this.free="";
  this.isAbierta = function(){
  	return (this.estado =="abierta");
  }
}

function LiniaComanda(platoid,precioN,precioUni,precioNormal,precioLimitado,producto){
 this.platoId = platoid;
 this.precioN = parseFloat(precioN);
 this.precioUnidad =parseFloat(precioUni);
 this.precioNormal = parseFloat(precioNormal);
 this.precioLimitado = parseFloat(precioLimitado);
 this.producto= producto;
 this.cantidad;
}
</script>
</head>
<body 
<?php
$openOrClose=new caja();
$estadocaja=$openOrClose->estado_caja();
if ($estadocaja==0){
?>
onload="cajaCerrada()"
<?php }?>
>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div style="height:94%;margin:0pt;padding:0pt">
<div id="cajaCerrada" style="display:none">la caja esta cerrada<br /><a href="view.php?page=caja">Abrir caja</a></div>

<div  id="free" style="display:none;margin:0 auto;text-align:center;">
Introduzca el razon de la cortesia:<br />
<input type="text" id="freevol"/><br />
<input type="button" value="Aceptar" onClick="putvoluntario(freevol.value)" />	
<input type="button" value="Cancelar" onClick="cancelarCliente()" />
</div>

<div id="tablageneraldiv">
<table width="100%" height="100%" id="tablageneral" border=1 cellpadding="2" cellspacing="0" >

<!--CUADRO IZQUIERDA ARRIBA-->

<tr height="50%" ><td rowspan=2>
<div id="TrabajadoresForm" style="display:none">
<table id="list3" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager3" class="scroll" style="text-align:center;"></div>
<div onclick="cancelarCliente()" style="background:#AAA;cursor:pointer">cancelar</div>
</div>
 <div id="arriba_izquierda" style="width:100%;height:100%">
 <div style="border-bottom:1px solid #AAAAAA;">
  <div id="clienteTypeInfo" style="float:left;padding:7px"></div>
  <div id="clientpressed1" class="client" style="float:right;cursor:pointer;padding:7px" onmousedown="clientemousedown(1)">Gratis</div> 
  <div id="clientpressed5" class="client" style="float:right;cursor:pointer;padding:7px" onmousedown="clientemousedown(5)">Cr&eacute;dito</div>
  <div id="clientpressed2" class="client" style="float:right;cursor:pointer;padding:7px" onmousedown="clientemousedown(2)">Cup&oacute;n</div> 
  <div id="clientpressed4" class="client" style="float:right;cursor:pointer;padding:7px" onmousedown="clientemousedown(4)">Normal</div>
  <div style="clear:both"></div>
 </div>
<table  width="100%"  border=0 cellpadding="1" cellspacing="1">	
<tr>
<td id="platillosTd"><div id="platillos"></div>
</td>
</tr>
</table>
</div>

 
<script>
var listaPedidos = new LineaComandaScreen();
var clienteScreen = new ClienteScreen();
var main = new Main();
</script>	
</td>

<!--CUADRO DERECHA ARRIBA-->

<td width="50%" height="50%">

<div id="arriba_derecha" style="height:100%;width:100%;overflow: auto">
</div>	

</td></tr>

<!--CUADRO IZQUIERDA ABAJO-->

<tr  height="50%">
<!--CUADRO DERECHA ABAJO-->

<td id="abajoderecha">
<table width="100%" height="100%" border=0 cellpadding="0" cellspacing="0">
   <!--TITULOS DE LOS INPUTS-->
<tr class="title">
<!--INPUTS IDCOMANDA,TOTAL,EFECTIVO,CAMBIO-->
<td width="25%" align="center">Comanda</td><td width="25%" align="center">Total</td><td align="center" width="25%">Efectivo</td><td width="25%" align="center">Cambio</td></tr>
<tr height="5%"><td><input id="idComanda" type="text" style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px" /></td>
<td><input id="total" type="text" border=0 disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td>
<td><input id="efectivo" type="text" disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td>
<!-- disabled=true -->
<td ><input id="cambio" type="text" disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td></tr>
<!--BOTON BORRAR-->
<tr width="100%"><td height="50%" width="25%">
<div class="btn notcalcbtntop">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="Borrar" class="actionbtn btnunpress" align="center" onmousedown="borrar(this.id)" onmouseup="changeClass(this.id)">Borrar</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="im ages/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
</div></td>
    <!--CALCULADORA-->
<td colspan="2" rowspan="2" width="50%">
<div style="margin:0 auto;height:100%">
  <table width="100%" height="100%" border=0 cellpadding="0" cellspacing="2">
  <tr height="25%">
    <!--NUMERO 7-->
   <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="7" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">7</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
</div>
    </td>
<!--NUMERO 8-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="8" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">8</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
   <!--NUMERO 9--> 
   <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="9" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">9</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
  </tr>
  <tr>
   <!--NUMERO 4-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="4" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">4</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
</div>
    </td>
    <!--NUMERO 5-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="5" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">5</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
    <!--NUMERO 6-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="6" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">6</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
  </tr>
  <tr height="25%">
    <!--NUMERO 1-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="1" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">1</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
   <!--NUMERO 2-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="2" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">2</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
<!--NUMERO 3-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="3" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">3</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
  </tr>
  <tr height="25%">
<!--NUMERO 00-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="00" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">00</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
<!--NUMERO 0-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="0" class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">0</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
<!--NUMERO .-->
    <td width="33%" height="25%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="." class="tdbtn btnunpress" align="center" onmousedown="calmousedown(this.id,this.id)" onmouseup="changeClass(this.id)">.</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
    </td>
  </tr>
 </table>
</div></td>
<!--BOTON EFECTIVO-->
<td height="50%"><div class="btn notcalcbtntop">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="Efectivo" class="actionbtn btnunpress" align="center" onmousedown="efectivo()" onmouseup="changeClass(this.id)">Efectivo</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
</td></tr>
<tr>
<!--BOTON LIBERAR MESA-->
<td height="50%"><div class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="LiberarMesa" class="actionbtn btnunpress" align="center" onmousedown="liberaMesaMouseDown(this.id)" onmouseup="changeClass(this.id)" onmouseout="comprobarOut(this.id)">Liberar Pantalla</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div></td>
<!--BOTON CERRAR TICKET-->
<td height="50%"><div id="divCerrar" class="btn">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0><tr><td  id="CerrarTicket" class="closebtn btnunpress" align="center" onmousedown="cerrarTiquetMouseDown();" onmouseup="changeClass(this.id)" ommouseout="comprobarOut(this.id)">Cerrar Ticket</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div></td></tr> </table>



</td></tr>
</table>
</div><!--tablageneraldiv-->
</div>
</body>
</html>