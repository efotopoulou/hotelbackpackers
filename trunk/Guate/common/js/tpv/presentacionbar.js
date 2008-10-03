//PRESENTACION
//Al iniciar la pagina.... ONREADY!!!!!!!
var timeoutHnd;
$(document).ready(function(){
   $.blockUI({ message: '<h1>Cargando...</h1>' });
   //Se borran algunos campos que se quedan por defecto con el valor que tenian
   $("#total").val("0");
   $("#efectivo").val("");
   $("#cambio").val("");
   hotkeys();
   $.ajaxSetup({type:"POST"});
   getPlatillosVentaRecepcion("Presentacion/jsonplatventarec.php");
   listaPedidos.iniciar();
   //restoreHibernar();
   $.unblockUI();
});
 
//-------------------------------------------CLIENTEMOUSEDOWN----------------------------------------//
//Hay que llamar a esta funcion despues de asignarle el currentMesa
function clientemousedown(num){
    main.id_cliente=undefined;
	clienteScreen.setCorrectColor(num);
	if (main.comanda() && main.comanda().isAbierta()) actualizarListaProductos(num);
    //Si es cliente mostrar la lista de clientes
    if (num==5) {desHotkeys();mostrarListaTrabajadores();desactivarEfectivo();}
    if (num==2) {desHotkeys();mostrarListaTrabajadores();activarEfectivo();}
    if (num==1) {askForVolName();desactivarEfectivo();}
    if (num==4) {guardarDatosCliente(undefined,"");activarEfectivo();} 
   
}
//-------------------------------------------ASK FOR THE NAME OF THE VOLUNTEER--------------------//
//se llama cuando se realiza una cortesia.pide del camarero que ponga el nombre del cliente que se le da comida gratis
function askForVolName(){
 desHotkeys();
 $.blockUI({ message: $('#free')});
}
//-------------------------------------------PUT FREE DESCRIPTION--------------------//
function putvoluntario(free){
if (main.comanda() && main.comanda().isAbierta()) main.comanda().free = free;
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
	activarEfectivo();
	if ( main.comanda() && main.comanda().isAbierta()){
		main.comanda().id_cliente=undefined;
		main.comanda().clienteName= undefined;
		actualizarListaProductos(0);
	}
}

//-------------------------------------------CALMOUSEDOWN----------------------------------------//
function calmousedown(texto,id){
 //esta la pantalla bloqueada. 
 if (main.efectivo) {
  if (!main.comanda().efectivo) {
   main.comanda().efectivo = texto;
  }else{
   main.comanda().efectivo=main.comanda().efectivo+texto;
  }
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }else if (main.comanda() && main.linia() && main.comanda().isAbierta()){
   listaPedidos.calcCantidad(id);
   main.linia().cantidad += texto;
  //Calculando el nuevo precio a mostrar
   calcularPrecio();
  //Calculando TOTAL
  $("#total").val(calcularTotal());
 }
 changeClass(id);
}

//-------------------------------------------PLATOMOUSEDOWN----------------------------------------//
function platomousedown(plato,platoid,precioN,precioLim,id){
  //si hay un clienttype elegido
  if(main.currentClient){
	if (!main.comanda() || !main.comanda().isAbierta()){	
	 var aux = main.currentComanda;
	 main.currentComanda+=1;
	 main.comandaArray[main.currentComanda]=new Comanda();
	 main.comanda().currentClientType = main.currentClient;
	 guardarDatosCliente(main.id_cliente,clienteScreen.getClienteName());
	 if (main.currentComanda) {
	  listaPedidos.addComanda();	
	  //main.comanda().currentClientType=main.comandaArray[aux].currentClientType;
	 }//else  clientemousedown(4);
	}
	clienteScreen.setCorrectColor(main.comanda().currentClientType);
   main.comanda().numRow+=1;
   var precio=escogePrecio(precioN,precioLim);
   main.comanda().liniasComanda[main.comanda().numRow] = new LiniaComanda(platoid,precio,precio,precioN,precioLim,plato);
   listaPedidos.addPlatillo(main.linia(),"row"+new String(main.currentComanda+new String(main.comanda().numRow)));
   $("#total").val(calcularTotal());
  } else alert('Por favor, elegid primero la el tipo de cliente');
}

//-------------------------------------------BORRARMOUSEDOWN----------------------------------------//
function borrar(id){
 if (main.efectivo) {
  main.comanda().efectivo=parseInt(main.comanda().efectivo /10);
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }
  else if(main.comanda() && main.linia()&& main.comanda().isAbierta()){
  	if(main.linia().cantidad && main.linia().cantidad.length>1){
     var quantity = main.linia().cantidad.substring(0,main.linia().cantidad.length-1);
     listaPedidos.setCantidad(quantity);
     main.linia().cantidad = quantity;
     calcularPrecio();
  	}else borrarLinia();
  	//Calculando TOTAL
     $("#total").val(calcularTotal());
  }
 changeClass(id);
}
//-------------------------------------------EFECTIVOMOUSEDOWN----------------------------------------//
function efectivo(){
  if (main.comanda() && main.comanda().isAbierta()){
/*	if(main.efectivo){
	 //activa toda la pantalla
	 $('#arriba_izquierda').unblock(); 
     $('#arriba_derecha').unblock(); 
     $('#abajo_izquierda').unblock();
     $("#CerrarTicket").removeClass("actionbtn");
     $("#CerrarTicket").addClass("closebtn");
     $("#cambio").val("");
	 main.efectivo=undefined;
	}else {*/
	if(!main.efectivo && main.currentClient!=1 && main.currentClient!=5){
	 //hace focus al input efectivo
	 //desactiva toda la pantalla menos el input efectivo, la calculadora y el boton borrar
    $('#arriba_izquierda').block({ message: null });
    $('#arriba_derecha').block({ message: null });
    $('#abajo_izquierda').block({ message: null });
     main.comanda().id_cliente=main.id_cliente;

	 //activa el boton cerrarticket
	 $("#CerrarTicket").removeClass("closebtn");
	 $("#CerrarTicket").addClass("actionbtn");

	 main.efectivo=1;
	} 
  }
  changeClass('Efectivo');
}
//-------------------------------------------CERRARTIQUETMOUSEDOWN----------------------------------------//
function cerrarTiquetMouseDown(){
//Si los botones de cliente son Credito o Gratis, el cajero no puede apretar el efectivo. Hacemos como si lo hubiese apretado. 
    if (main.currentClient ==1 || main.currentClient ==5){
     main.comanda().id_cliente=main.id_cliente;
     main.efectivo=1;
    }
	if (main.efectivo && main.comanda() && main.comanda().isAbierta()) { 
	 //insert la nueva comanda 
	 sendComanda();
	 
    //activa la pantalla
	$('#arriba_izquierda').unblock(); 
    $('#arriba_derecha').unblock(); 
    $('#abajo_izquierda').unblock();
    
    //vaciar el campo de efectivo,total y augmentar el numero de comandaID
    main.efectivo=undefined;
    $("#efectivo").val("");
    $("#efectivo").attr({disabled:true}).val("");
    $("#cambio").val("");
    $("#total").val("0");
    listaPedidos.fijarComanda();
    //clienteScreen.setClienteName(" ");
    main.comanda().estado="cerrado";
	}
	changeClass('CerrarTicket');
}
//-------------------------------------------LIBERAMESAMOUSEDOWN----------------------------------------//
function liberaMesaMouseDown(id){
  if (main.comanda()){
    if (main.comanda().isAbierta()){
	  if(confirm('Existe una comanda aun abierta, quieres borrarla?'))  mesaLibre();
    } else mesaLibre();
  }
}



function borrarLinia(){
	listaPedidos.removeLine();
  if (main.comanda().numRow>=0){
  	 main.comanda().liniasComanda.pop();
  	main.comanda().numRow--;
  }  
  if (main.comanda().numRow==-1 && main.currentComanda==0) mesaLibre();
}

function mesaLibre(){
  listaPedidos.reiniciar();
  //borra la mesa y cambia el color a azul(o sea que libre)
  //Ponemos el clienttype, el clienteNormal
  clienteScreen.setClienteName("");
  main.id_cliente=undefined;
  main.comandaArray[main.currentComanda]= undefined;
  main.currentComanda=-1;
  clienteScreen.setCorrectColor(4);
  clienteScreen.setBlueColor();
}

function calcularPrecio(){
  var candity=parseFloat(main.linia().cantidad);
  var newprecio= redondea(candity*(main.linia().precioUnidad));
  listaPedidos.setPrecio(newprecio);
  main.linia().precioN=newprecio;	
}

function calcularTotal(){
	var precioTotal=0;
	if(main.comanda()){
	 jQuery.each(main.comanda().liniasComanda,function() {
       precioTotal+=this.precioN;
     });
     precioTotal=redondea(precioTotal);
     main.comanda().total=precioTotal;
     $("#precioTotal"+main.currentComanda).html(""+precioTotal);
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
function sendComanda(){
 var myJsonMain = JSON.stringify(main.comanda());
  $.getJSONGuate("Presentacion/jsonsaveventa.php",{ json: myJsonMain}, function(json){
    if (json["Mensaje"]) {
    	changeClass('Efectivo');
    	efectivo();
    }
    json = verificaJSON(json);
  });
}
function sendMain(){
 var myJsonMain = JSON.stringify(main);
  $.getJSONGuate("jsonsavetpv.php",{ json: myJsonMain}, function(json){
    if (json["Mensaje"]) {
    	changeClass('Efectivo');
    	efectivo();
    }
    json = verificaJSON(json);
  });
}

//-------------------------------------------MOSTRAR LISTA DE CLIENTES Y TRABAJADORES------------------//
function mostrarListaClientes(){
  jQuery("#list2").jqGrid({
    url:'Presentacion/jsongrid.php?q=cliente&nd='+new Date().getTime(),
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
    imgpath: '/Restaurante/css/images',
    sortname: 'Id_Cliente',
    viewrecords: true,
    sortorder: "desc",
    caption: "Lista de Clientes",
    hidegrid: false,
    onSelectRow: function(ids) {
        var id = jQuery("#list2").getGridParam('selrow'); 
        var ret = jQuery("#list2").getRowData(id);
        guardarDatosCliente(ret.Id_Cliente,ret.nombre+" "+ret.apellido1+" "+ ret.apellido2);
        hotkeys();
        $.unblockUI();
    }
  });
  $.blockUI({ message: $('#clientesForm'), css:{width:$("#list2").css("width"),height:$("#list2").css("height")} });
 }
function mostrarListaTrabajadores(){
  jQuery("#list3").jqGrid({
    url:'Presentacion/jsongrid.php?q=trabajador&nd='+new Date().getTime(),
    datatype: "xml",
    colNames:['id', 'nombre'],
    colModel:[
        {name:'id',index:'idTrabajador', width:50},
        {name:'nombre',index:'nombre', width:150},
    ],
    pager: jQuery('#pager3'),
    rowNum:10,
    rowList:[10,20,30],
    imgpath: '/common/css/images',
    sortname: 'nombre',
    viewrecords: true,
    sortorder: "desc",
    caption: "Lista de Trabajadores",
    hidegrid: false,
    height: "100%",
    onSelectRow: function(ids) {
        var id = jQuery("#list3").getGridParam('selrow'); 
        var ret = jQuery("#list3").getRowData(id);
        guardarDatosCliente(ret.id,ret.nombre);
        hotkeys();
        $.unblockUI();
    }
  });
  $.blockUI({ message: $('#TrabajadoresForm'), css:{width:$("#list3").css("width"),height:$("#list3").css("height"),top:"10%"} });
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
 	if (main.comanda() && main.comanda().isAbierta()){
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
  	main.pushLiniaComanda();
 }

function desactivarEfectivo(){
 if ($('#Efectivo').hasClass("btnunpress")){
  $('#Efectivo').removeClass("btnunpress").addClass("btncancelled");
 } 
}
 
function activarEfectivo(){
 if (!$('#Efectivo').hasClass("btnunpress")){
  $('#Efectivo').addClass("btnunpress").removeClass("btncancelled");
 }
}
function gridReload(){
 var nm_mask = jQuery("#searchNombre").val();
 //Presentacion/jsongrid.php?q=trabajador&nd='+new Date().getTime()
 jQuery("#list3").setGridParam({url:"Presentacion/jsongrid.php?q=trabajador&nm_mask="+nm_mask,page:1}).trigger("reloadGrid");
}
function doSearch(){
 if(timeoutHnd) clearTimeout(timeoutHnd);
 timeoutHnd = setTimeout(gridReload,500);
}