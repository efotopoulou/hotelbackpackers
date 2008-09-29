<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familia.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_comanda.php');
//require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_mesas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');
?>
<html lang="en" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>TPV</title>
		<link rel="stylesheet" type="text/css" media="screen" href="../css/grid.css" />
		<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />

		<script src="/common/js/jquery-1.2.3.pack.js"></script>
		<script src="/common/js/jquery.blockUI.js"></script>
		<script src="/common/js/jquery.jqGrid.js"></script>
		<script src="/common/js/jquery.hotkeys.js"></script>
		<script src="/common/js/jquery.mousewheel.js"></script>
		<script src="/common/js/jquery.scrollable.js"></script>
		<script src="/common/js/jquery.corner.js"></script>
		<script src="/common/js/json.js"></script>
		<script src="/common/js/guate.js"></script>
		<script src="/common/js/tpv/lineacomandascreen.js"></script>
		<script src="/common/js/tpv/boxizquierdaarriba.js"></script>
		<script src="/common/js/tpv/familiasplatillos.js"></script>
		<script src="/common/js/tpv/hotkeys.js"></script>
		
		
<script>
// CLASES DE DOMINIO  
//Clase Main
function Main(numMesas){

//Atributos	
 this.numMesas=numMesas;
 this.mesas = new Array();
 this.currentMesa;
 this.currentClient;
 this.numDefaultID;
 this.efectivo;
 this.id_cliente;
 this.calPressedId;

//CREA EFECTO: Pone los valores a 0.
 this.creaEfecto = function (numMesa) {
  $("#total").val("0");
  if (!main.numDefaultID) main.numDefaultID=parseInt($("#idComanda").val().substring(1));
  $("#idComanda").val("R"+main.numDefaultID);
  listaPedidos.reiniciar();
  }
  
// CARGA
 this.carga = function (numMesa) {
  //Pone en la linia de comandas, la linia de comandas de mesas[numMesa]
  this.pushLiniaComanda(numMesa);  
 }
//PUSHLINIACOMANDA
 this.pushLiniaComanda = function(numMesa){
 	listaPedidos.vaciar();
 	for (var j=0;j<this.mesas[numMesa].comanda.length;j++){

// 	 if (this.mesas[numMesa].comanda[j].liniasComanda.length){
      listaPedidos.addComanda();
	  for (var i=0;i<this.mesas[numMesa].comanda[j].liniasComanda.length;i++){
//	 	if (j<(this.mesas[numMesa].comanda.length-1)) listaPedidos.addPlatilloFijo(this.mesas[numMesa].comanda[j].liniasComanda[i]);
	 	if (!this.mesas[numMesa].comanda[j].isAbierta()) listaPedidos.addPlatilloFijo(this.mesas[numMesa].comanda[j].liniasComanda[i]);
	 	else listaPedidos.addPlatillo(this.mesas[numMesa].comanda[j].liniasComanda[i], "row"+new String(j)+new String(i));			
 	  }
      listaPedidos.modifyTotal(this.mesas[numMesa].comanda[j].total);
 //    }     
 	}
   $("#total").val(calcularTotal());
   $("#idComanda").val(main.comanda().comandaID);
   clienteScreen.setClienteName(main.comanda().clienteName);
 }
 this.mesa = function(){
  return this.mesas[this.currentMesa];
 }
 this.comanda = function(){
 	return this.mesa().comanda[this.mesa().currentComanda];
 }
 this.linia = function(){
  return this.comanda().liniasComanda[this.comanda().numRow];
 }
}

function Comanda(){
  this.liniasComanda = new Array();
  this.numRow=-1;
  this.currentClientType=-1;
  this.comandaID="";
  this.efectivo="";
  this.total="";
  this.clienteName="";
  this.id_cliente="";
  this.free="";
  this.estado="abierta";
  this.isAbierta = function(){
  	return (this.estado =="abierta");
  }
}

function Mesa(){
	this.comanda = new Array();
	this.currentComanda = -1;
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
        

<script>
        //PRESENTACION
//Al iniciar la pagina.... ONREADY!!!!!!!
$(document).ready(function(){
   $.blockUI({ message: '<h1>Cargando...</h1>' });
   //Se borran algunos campos que se quedan por defecto con el valor que tenian
   $("#total").val("0");
   $("#efectivo").val("");
   $("#cambio").val("");
   $("#idComanda").val("<?php $comanda=new comanda();echo $estadocaja=$comanda->getNextMaxIdComanda();?>");
   hotkeys();
   getFamilias();
   listaPedidos.iniciar();
   restoreHibernar();
   $.unblockUI();
});

function guardarComandaId(){
	 var defaultID = "";
    defaultID=$("#idComanda").val();
    var numDefaultID=parseInt(defaultID.substring(1));
    //alert('change:'+numDefaultID);
    if (main.mesa()){
     main.comanda().comandaID=defaultID;
     main.numDefaultID=numDefaultID+1;
    }else main.numDefaultID=numDefaultID;
}

//-------------------------------------------MESAMOUSEDOWN----------------------------------------//
function mesamousedown(id){
  var num=id.substring(4);
  
  //Guarda el valor de IdComanda en la mesa que es ahora current (AUN NO SE HA CANVIADO DE CURRENT)
  guardarComandaId();
  
  //asigna la mesa que se ha apretado como current
  main.currentMesa=parseInt(num);
  
  //Cambiar el color de los botones de Mesa
  mesaScreen.setCorrectColor(num);
  
  //Cambiar el color de los botones de ClientType
  if (main.mesa())clienteScreen.setCorrectColor(main.comanda().currentClientType);
  else {
  clientemousedown(4);
  }
  if(main.mesas[num])main.carga(num);
  else main.creaEfecto(num);
 }       

 
//-------------------------------------------CLIENTEMOUSEDOWN----------------------------------------//
//Hay que llamar a esta funcion despues de asignarle el currentMesa
function clientemousedown(num){
	clienteScreen.setCorrectColor(num);
	if (main.mesa()) actualizarListaProductos(num);
    //Si es cliente mostrar la lista de clientes
    if (num==3) mostrarListaClientes();
    if (num==2) mostrarListaTrabajadores();
    if (num==1) askForVolName();
    if (num==4) guardarDatosCliente(undefined,""); 
}

//-------------------------------------------ASK FOR THE NAME OF THE VOLUNTEER--------------------//
//se llama cuando se realiza una cortesia.pide del camarero que ponga el nombre del cliente que se le da comida gratis
function askForVolName(){
 desHotkeys();
 $.blockUI({ message: $('#free')});
}
//-------------------------------------------PUT FREE DESCRIPTION--------------------//
function putvoluntario(free){
main.comanda().free = free;
clienteScreen.setClienteName(free);
$.unblockUI();
hotkeys();
}
function cancelarCliente(){
	$.unblockUI();
	hotkeys();
	clienteScreen.setClienteName(" ");
	main.id_cliente=undefined;
	clienteScreen.setCorrectColor(4);
	if (main.mesa() && main.comanda() && main.comanda().isAbierta()){
		main.comanda().id_cliente=undefined;
		main.comanda().clienteName= undefined;
		actualizarListaProductos(0);
	}
}
//-------------------------------------------CALMOUSEDOWN----------------------------------------//
function calmousedown(texto,id){
 //esta la pantalla bloqueada
 if (main.efectivo) {
  if (!main.comanda().efectivo) {
   main.comanda().efectivo = texto;
  }else{
   main.comanda().efectivo=main.comanda().efectivo+texto;
  }
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }else if (main.mesa() && main.comanda() && main.linia() && main.comanda().isAbierta()){
   listaPedidos.calcCantidad(id);
   main.linia().cantidad += texto;
  //Calculando el nuevo precio a mostrar
   calcularPrecio();
  //Calculando TOTAL
  $("#total").val(calcularTotal());
 }//else $("#idComanda").val($("#idComanda").val()+texto);
 changeClass(id);
}

//-------------------------------------------PLATOMOUSEDOWN----------------------------------------//
function platomousedown(plato,platoid,precioN,precioLim,id){
	//si hay una mesa elegida
  if(main.currentMesa){
	//si esta mesa elegida y no existe, hay que crearla
	if(!main.mesa()){
	 //crea la mesa y cambia el color a rojo(o sea que currentocupado), tambien al cliente
	 mesaScreen.setRedColor();
	 clienteScreen.setRedColor();  
	 main.mesas[main.currentMesa]= new Mesa();
	}
	if (!main.comanda() || 	!main.comanda().isAbierta()){	
	 var aux = main.mesa().currentComanda;
	 main.mesa().currentComanda+=1;
	 main.mesa().comanda[main.mesa().currentComanda]= new Comanda();
	 main.comanda().currentClientType = main.currentClient;
	 guardarDatosCliente(main.id_cliente,clienteScreen.getClienteName());
	 if (main.mesa().currentComanda) {
	  listaPedidos.addComanda();	
	  main.comanda().currentClientType=main.mesa().comanda[aux].currentClientType;
	 }
	guardarComandaId();
	}
	
   main.comanda().numRow+=1;
   var precio=escogePrecio(precioN,precioLim);
   main.comanda().liniasComanda[main.comanda().numRow] = new LiniaComanda(platoid,precio,precio,precioN,precioLim,plato);
   listaPedidos.addPlatillo(main.linia(),"row"+new String(main.mesa().currentComanda+new String(main.comanda().numRow)));
   $("#total").val(calcularTotal());
 }else alert('Por favor, elegid primero la mesa que os interesa');

}

//-------------------------------------------BORRARMOUSEDOWN----------------------------------------//
function borrar(id){
 if (main.efectivo) {
  main.comanda().efectivo=parseInt(main.comanda().efectivo /10);
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }
  else if(main.mesa() && main.comanda() && main.linia()&& main.comanda().isAbierta() && $("#idComanda").focus()){
  	if(main.linia().cantidad && main.linia().cantidad.length>1){
     var quantity = main.linia().cantidad.substring(0,main.linia().cantidad.length-1);
     listaPedidos.setCantidad(quantity);
     main.linia().cantidad = quantity;
     calcularPrecio();
  	}else borrarLinia();
  	//Calculando TOTAL
     $("#total").val(calcularTotal());
  }
//  else { 
//  	var str = $("#idComanda").val();
//    $("#idComanda").val(str.substring(0,str.length-1));
 // }
 changeClass(id);
}

//-------------------------------------------EFECTIVOMOUSEDOWN----------------------------------------//
function efectivo(){
  if (main.mesa() && main.comanda().isAbierta()){
/*	if(main.efectivo){
	 //activa toda la pantalla
	 $('#arriba_izquierda').unblock(); 
     $('#arriba_derecha').unblock(); 
     $('#abajo_izquierda').unblock();
     $("#CerrarTicket").removeClass("actionbtn");
     $("#CerrarTicket").addClass("closebtn");
     //$("#efectivo").attr({disabled:true}).val("");
     $("#efectivo").css({background:"#808080"});
     $("#cambio").val("");
     
     
	 main.efectivo=undefined;
	}else {*/
	if(!main.efectivo){
	 //hace focus al input efectivo
	//$("#efectivo").attr({disabled:false}).focus();
	$("#efectivo").css({background:"#FFFFFF"});
	 //desactiva toda la pantalla menos el input efectivo, la calculadora y el boton borrar
    $('#arriba_izquierda').block({ message: null });
    $('#arriba_derecha').block({ message: null });
    $('#abajo_izquierda').block({ message: null });
     guardarComandaId();
     main.comanda().id_cliente=main.id_cliente;

	 //activa el boton cerrarticket
	 $("#CerrarTicket").removeClass("closebtn");
	 $("#CerrarTicket").addClass("actionbtn");

	 main.efectivo=1;
	 //insert la nueva comanda abierta
	 sendComandaAbierta();
	}
  }
   changeClass('Efectivo');
}

//-------------------------------------------CERRARTIQUETMOUSEDOWN----------------------------------------//
function cerrarTiquetMouseDown(){
	if (main.efectivo) {
    $.getJSONGuate("jsonupdatetpv.php",{ efectivo: main.comanda().efectivo,id:main.comanda().comandaID}, function(json){
      json = verificaJSON(json);
    });
    
	//activa la pantalla
	$('#arriba_izquierda').unblock(); 
    $('#arriba_derecha').unblock(); 
    $('#abajo_izquierda').unblock();
    //vaciar el campo de efectivo,total y augmentar el numero de comandaID
    main.efectivo=undefined;
    $("#efectivo").val("");
    $("#cambio").val("");
    $("#total").val("0");
    $("#efectivo").css({background:"  "});
     $("#idComanda").val("R"+main.numDefaultID);
    listaPedidos.fijarComanda();
    clienteScreen.setClienteName("");
    main.comanda().estado="cerrado";
	}
	changeClass('CerrarTicket');
}
//HACER: Mensaje de confirmacion si aun existen comandas abiertas. 
//-------------------------------------------LIBERAMESAMOUSEDOWN----------------------------------------//
function liberaMesaMouseDown(id){
   if (main.mesa() && main.comanda()){
	 if (main.comanda().isAbierta()){
	  if(confirm('Existe una comanda aun abierta, quieres borrarla?')){
 //     vaciar las lineas de la comanda y liberar la mesa
      mesaLibre();
	  }
    }else mesaLibre();
   //changeClass(id);
   }
}

function borrarLinia(){
	listaPedidos.removeLine();
  if (main.comanda().numRow>=0){
  	 main.comanda().liniasComanda.pop();
  	main.comanda().numRow--;
  }  
  if (main.comanda().numRow==-1 && main.mesa().currentComanda==0) mesaLibre();
}

function mesaLibre(){
  listaPedidos.reiniciar();
  //borra la mesa y cambia el color a azul(o sea que libre)
  //$("#clientpressed"+main.currentClient).toggleClass("blueFuerte").toggleClass("redFuerte");
  clienteScreen.setBlueColor();
  mesaScreen.setBlueColor();
  //$("#mesa"+main.currentMesa).toggleClass("blueFuerte").toggleClass("btnunpress").toggleClass("orange").toggleClass("redFuerte");
  main.mesas[main.currentMesa]= undefined;
    //Ponemos el clienttype, el clienteNormal
  clienteScreen.setCorrectColor(4);
  clienteScreen.setClienteName("");
  main.id_cliente=undefined;
}

function calcularPrecio(){
  var candity=parseFloat(main.linia().cantidad);
  var newprecio= redondea(candity*(main.linia().precioUnidad));
  listaPedidos.setPrecio(newprecio);
  main.linia().precioN=newprecio;	
}

function calcularTotal(){
	var precioTotal=0;
	if(main.mesa()){
	 jQuery.each(main.comanda().liniasComanda,function() {
       precioTotal+=this.precioN;
     });
     precioTotal=redondea(precioTotal);
     main.comanda().total=precioTotal;
     $("#precioTotal"+main.mesa().currentComanda).html(""+precioTotal);
	}
    return precioTotal;
}

function redondea(num){
	return (Math.round(num*100)/100);
}
function calcularCambio(){
	$("#cambio").val(redondea(main.comanda().efectivo-main.comanda().total));
}
function changeClass(id){
 $("#"+id).toggleClass("btnpress");
 $("#"+id).toggleClass("btnunpress");
 $("#"+id).toggleClass("redtext");
 if (main.calPressedId != id) main.calPressedId = id;
 else main.calPressedId = undefined;
}
function comprobarOut(id){
 if (main.calPressedId==id){
 	$("#"+id).removeClass("btnpress");
 	$("#"+id).addClass("btnunpress");
 	$("#"+id).removeClass("redtext");
 	main.calPressedId = undefined;
 	//alert("hola");
 }	
}
//-------------------------------------------sendComandaAbierta----------------------------------------//
function sendComandaAbierta(){
 var myJsonMain = JSON.stringify(main.comanda());
  $.getJSONGuate("jsonsavetpv.php",{ json: myJsonMain,mesa:main.currentMesa}, function(json){
    if (json["Mensaje"]) {
    	changeClass('Efectivo');
    	efectivo();
    }
    json = verificaJSON(json);
  });
}
function sendMain(){
 var myJsonMain = new Array();	
 for (i in main.mesas){
  if (main.mesas[i]){
   for (j in main.mesas[i]["comanda"]){
  	var comandaJ = main.mesas[i]["comanda"][j];
  	comandaJ["mesa"] = i;
 	myJsonMain.push(comandaJ);
   }
  }
 }
 
  myJsonMain = JSON.stringify(myJsonMain);
  $.getJSONGuate("jsonhibernar.php",{ json: myJsonMain}, function(json){
    json = verificaJSON(json);
  });
}
function restoreHibernar(){
  $.getJSONGuate("jsonhibernar.php",{ restore: "yes"}, function(json){
    json = verificaJSON(json);
    for (var i=0;i<json.length;i++){
    	var comandaAux = json[i];
    	mesamousedown("mesa"+comandaAux["mesa"]);
    	for (var j=0;j<comandaAux["liniasComanda"].length;j++){
    		var liniaAux = comandaAux["liniasComanda"][j];
    		platomousedown(liniaAux["producto"],liniaAux["idPlatillo"],liniaAux["precioNormal"],liniaAux["precioLimitado"],0);
    		if (j==0) {
    		 clienteScreen.setCorrectColor(parseInt(comandaAux["tipoCliente"]));
    		 actualizarListaProductos(0);
    		 guardarDatosCliente(comandaAux["id_cliente"],comandaAux["clienteName"]);
//    		 main.currentClient=parseInt(comandaAux["tipoCliente"]);
//    		 main.comanda().currentClientType=parseInt(comandaAux["tipoCliente"]);
			 main.comanda().comandaID=comandaAux["idComanda"];
			 $("#idComanda").val(comandaAux["idComanda"]);
    		}
    		listaPedidos.setCantidad(liniaAux["cantidad"]);
    		calmousedown(liniaAux["cantidad"]);
    	}
    	//alert(comandaAux["tipoCliente"]);
    }
  },false);
}
//-------------------------------------------MOSTRAR LISTA DE CLIENTES Y TRABAJADORES------------------//
function mostrarListaClientes(){
  jQuery("#list2").jqGrid({
    url:'jsongrid.php?q=cliente&nd='+new Date().getTime(),
    datatype: "xml",
    colNames:['Id','Nombre', 'Apellido1', 'Apellido2','Pasaporte'],
    colModel:[
        {name:'Id_Cliente',index:'Id_Cliente', width:100},
        {name:'nombre',index:'nombre', width:100},
        {name:'apellido1',index:'apellido1', width:100},
        {name:'apellido2',index:'apellido2', width:100},
        {name:'pasaporte',index:'pasaporte', width:100}       
    ],
    pager: jQuery('#pager2'),
    rowNum:10,
    rowList:[10,20,30],
    imgpath: '../css/images',
    sortname: 'Id_Cliente',
    viewrecords: true,
    sortorder: "desc",
    caption: "Lista de Clientes",
    hidegrid: false,
    onSelectRow: function(ids) {
        var id = jQuery("#list2").getSelectedRow();
        var ret = jQuery("#list2").getRowData(id);
        guardarDatosCliente(ret.Id_Cliente,ret.nombre+" "+ret.apellido1+" "+ ret.apellido2);
        $.unblockUI();
    }
  });
  $.blockUI({ message: $('#clientesForm'), css:{width:$("#list2").css("width"),height:$("#list2").css("height")} });
 }
function mostrarListaTrabajadores(){
  jQuery("#list3").jqGrid({
    url:'jsongrid.php?q=trabajador&nd='+new Date().getTime(),
    datatype: "xml",
    colNames:['Id_usuario','Id_perfil', 'nombre'],
    colModel:[
        {name:'Id_usuario',index:'Id_usuario', width:100},
        {name:'Id_perfil',index:'Id_perfil', width:100},
        {name:'nombre',index:'nombre', width:100},
    ],
    pager: jQuery('#pager3'),
    rowNum:10,
    rowList:[10,20,30],
    imgpath: '../css/images',
    sortname: 'Id_usuario',
    viewrecords: true,
    sortorder: "desc",
    caption: "Lista de Trabajadores",
    hidegrid: false,
    onSelectRow: function(ids) {
        var id = jQuery("#list3").getSelectedRow();
        var ret = jQuery("#list3").getRowData(id);
        guardarDatosCliente(ret.Id_usuario,ret.nombre);
        $.unblockUI();
    }
  });
  $.blockUI({ message: $('#TrabajadoresForm'), css:{width:$("#list3").css("width"),height:$("#list3").css("height")} });
 }
 function cajaCerrada(){
   $("#tablageneraldiv").block({ message: $('#cajaCerrada')}); 	
 }
 function escogePrecio(precioN,precioLim){
 	var precio=0;
    switch(main.comanda().currentClientType){
 		case 2: precio=precioLim; break;
 		case 3: precio=precioN; break;
 		case 4: precio=precioN; break;
 	}
 	return precio;
 }
 function guardarDatosCliente(id, nombre){
 	clienteScreen.setClienteName(nombre);
 	main.id_cliente=id;
 	if (main.mesa()){
 		main.comanda().clienteName=nombre;
 		main.comanda().id_cliente=id;
 	}
 }
 function actualizarListaProductos(num){
 	var linia = main.comanda().liniasComanda;
 	for (i=0;i<linia.length;i++){
 		//plat=platillos[linia[i].platoId];
 		precio=escogePrecio(linia[i].precioNormal,linia[i].precioLimitado);
 		linia[i].precioUnidad=precio;
 		var cantidad = linia[i].cantidad;
 		if (!cantidad) cantidad=1;
 		linia[i].precioN=precio*cantidad;
 	}
 	calcularTotal();
  	main.pushLiniaComanda(main.currentMesa);
 }
</script>
</head>
<body onresize="resize()" onUnload="sendMain()"
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
<!-- <div id="freedescription" style="display:none;text-align:center"></div> -->
<div id="tablageneraldiv">
<table width="100%" height="100%" id="tablageneral" border=1 cellpadding="2" cellspacing="0" >

<!--CUADRO IZQUIERDA ARRIBA-->

<tr><td width="50%" height="50%">
 <div id="arriba_izquierda" style="width:100%;height:100%">
 <div style="border-bottom:1px solid #AAAAAA;">
  <div id="clienteTypeInfo" style="float:left;padding:7px"></div>
        <table>
        <tr><td><div style="width:120px;"><span>Cortesia:</span></div></td>
      	<td><div><span><input id="cortdesc" type="text" size="80" value=""/></span></div></td></tr>
        </table>
  <!-- <div id="clientpressed1" class="client" style="float:right;cursor:pointer;padding:7px" onmousedown="clientemousedown(1)">Gratis</div> -->
   
  <div style="clear:both"></div>
 </div>
 <div style="width:100%;height:80%">
 <table width="100%" height="100%" border=0 cellpadding="1" cellspacing="1">	
 <!-- < ?php
$table=new mesas();
$noMesas=$table->get_mesas();
?> -->
<script>
var listaPedidos = new LineaComandaScreen();
//var mesaScreen = new MesaScreen();
var clienteScreen = new ClienteScreen();
var main = new Main();
</script>	
<tr height="50%">
<?php
	for($i=0;$i<$noMesas;$i++) {
		if ($i==round($noMesas/2)){
		?>
		</tr>
		<tr height="50%">		
<?php		}
?>
<td align="center">

<div class="btn" style="height:80px;width:80px">
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<table class="tablebtn" cellspacing=0>
<tr><td id="mesa<?php echo($i); ?>" class="mesa actionbtn btnunpress" align="center" onmousedown="mesamousedown(this.id)">
<?php echo("Mesa".$i); ?>
</td></tr></table>
<div class="h1r"><img width="1px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="1px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
<div class="h1r"><img width="2px" height="1px" src="images/blankdot.gif"/></div>
<div class="h1f"><img width="2px" height="1px" src="images/blankdot.gif"/></div><div class="btnbck"><img height="1px" src="images/blankdot.gif"/></div>
</div>

</td>
<?php } ?>
</tr>		


</table>
 
<script>
var listaPedidos = new LineaComandaScreen();
//var mesaScreen = new MesaScreen();
var clienteScreen = new ClienteScreen();
var main = new Main();
</script>
 
 </div>
</div>
 

</td>

<!--CUADRO DERECHA ARRIBA-->

<td width="50%" height="50%">

<div id="arriba_derecha" style="height:100%;width:100%;overflow: auto">
</div>	

</td></tr>

<!--CUADRO IZQUIERDA ABAJO-->

<tr><td width="50%" height="50%">
<div id="abajo_izquierda" style="height:100%;width:100%;overflow: hidden">
<table  width="100%" height="100%" border=0 cellpadding="1" cellspacing="1">	
<tr>
<td id="platillosTd" height='50%' width="100%"><div id="platillos" style="position:relative;width:100%;height:100%"></div>
</td>
</tr>
<tr>
<td id="familias" height='50%' width="100%"></td>
</tr>
</table>
</div>
</td>
<!--CUADRO DERECHA ABAJO-->

<td width="50%" height="50%" id="abajoderecha">
<table  width="100%" height="100%" border=0 cellpadding="0" cellspacing="0">
   <!--TITULOS DE LOS INPUTS-->
<tr class="title">
<td width="25%" align="center">Comanda</td><td width="25%" align="center">Total</td><td align="center" width="25%">Efectivo</td><td width="25%" align="center">Cambio</td></tr>
   <!--INPUTS IDCOMANDA,TOTAL,EFECTIVO,CAMBIO-->
<tr height="5%"><td><input id="idComanda" type="text" style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px" /></td>
<td><input id="total" type="text" border=0 disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td>
<td><input id="efectivo" type="text" disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td>
<td><input id="cambio" type="text" disabled=true style="width:100%;text-align:center;font-family:Arial,sans-serif;font-size:30px"/></td></tr>
    <!--BOTON BORRAR-->
<tr><td height="50%">
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
<td colspan="2" rowspan="2">
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
<table class="tablebtn" cellspacing=0><tr><td  id="LiberarMesa" class="actionbtn btnunpress" align="center" onmousedown="liberaMesaMouseDown(this.id)" onmouseup="changeClass(this.id)" onmouseout="comprobarOut(this.id)">Liberar Mesa</td></tr></table>
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