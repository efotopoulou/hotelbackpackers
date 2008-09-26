        //PRESENTACION
//Al iniciar la pagina.... ONREADY!!!!!!!
$(document).ready(function(){
   //Se borran algunos campos que se quedan por defecto con el valor que tenian
   $("#total").val("0");
   $("#efectivo").val("");
   $("#cambio").val("");
   hotkeys();
   $.ajaxSetup({type:"POST"});
   getPlatillosVentaRecepcion("Presentacion/jsonplatventarec.php");
   listaPedidos.iniciar();
});

function guardarComandaId(){
//  var defaultID = $("#idComanda").val();
//  var numDefaultID = parseInt(defaultID.substring(1));
//  main.numDefaultID = numDefaultID;
main.numDefaultID = $("#idComanda").val();
//alert(main.numDefaultID);
if (main.comanda())main.comanda().comandaID=main.numDefaultID.substring(1);

}

 
//-------------------------------------------CLIENTEMOUSEDOWN----------------------------------------//
//Hay que llamar a esta funcion despues de asignarle el currentMesa
function clientemousedown(num){
	clienteScreen.setCorrectColor(num);
	//if (main.comanda()) actualizarListaProductos(num);
    //Si es cliente mostrar la lista de clientes
    if (num==3) mostrarListaClientes();
    if (num==2) mostrarListaTrabajadores();
    if (num==1) askForVolName();
    if (num==4) guardarDatosCliente(undefined,""); 
   
}
//-------------------------------------------ASK FOR THE NAME OF THE VOLUNTEER--------------------//
//se llama cuando se realiza una cortesia.pide del camarero que ponga el nombre del cliente que se le da comida gratis
function askForVolName(){
 $.blockUI({ message: $('#free')});
}
//-------------------------------------------PUT FREE DESCRIPTION--------------------//
function putvoluntario(free){
main.comanda().free = free;
clienteScreen.setClienteName(free);
//$("#clienteTypeInfo").html(free);
$.unblockUI();
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
 }else if (main.comanda() && main.linia()){
   listaPedidos.calcCantidad(id);
   main.linia().cantidad += texto;
  //Calculando el nuevo precio a mostrar
   calcularPrecio();
  //Calculando TOTAL
  $("#total").val(calcularTotal());
 }else $("#idComanda").val($("#idComanda").val()+texto);
 changeClass(id);
}

//-------------------------------------------PLATOMOUSEDOWN----------------------------------------//
function platomousedown(plato,platoid,precioN,precioLim,id){
	//si esta mesa elegida y no existe, hay Borrarque crearla
	if (!main.comanda() || 	!main.comanda().isAbierta()){	
	 var aux = main.currentComanda;
	 main.currentComanda+=1;
	 main.comandaArray[main.currentComanda]= new Comanda();
	 main.comanda().currentClientType = main.currentClient;
	 guardarDatosCliente(main.idCliente,clienteScreen.getClienteName());
	 if (main.currentComanda) {
	  listaPedidos.addComanda();	
	  main.comanda().currentClientType=main.comandaArray[aux].currentClientType;
	 }
	guardarComandaId();
    clientemousedown(4);
	}
	else clienteScreen.setCorrectColor(main.comanda().currentClientType);
   main.comanda().numRow+=1;
   var precio=escogePrecio(precioN,precioLim);
   main.comanda().liniasComanda[main.comanda().numRow] = new LiniaComanda(platoid,precio,precio,precioN,precioLim,plato);
   listaPedidos.addPlatillo(main.linia(),"row"+new String(main.currentComanda+new String(main.comanda().numRow)));
   $("#total").val(calcularTotal());
  // changeClass(id);
}

//-------------------------------------------BORRARMOUSEDOWN----------------------------------------//
function borrar(id){
 if (main.efectivo) {
  main.comanda().efectivo=parseInt(main.comanda().efectivo /10);
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }
  else if(main.comanda() && main.linia()){
  	if(main.linia().cantidad && main.linia().cantidad.length>1){
     var quantity = main.linia().cantidad.substring(0,main.linia().cantidad.length-1);
     listaPedidos.setCantidad(quantity);
     main.linia().cantidad = quantity;
     calcularPrecio();
  	}else borrarLinia();
  	//Calculando TOTAL
     $("#total").val(calcularTotal());
  }
  else { 
  	var str = $("#idComanda").val();
    $("#idComanda").val(str.substring(0,str.length-1));
  }
 changeClass(id);
}
//-------------------------------------------EFECTIVOMOUSEDOWN----------------------------------------//
function efectivo(){
	if(main.efectivo){
	 //activa toda la pantalla
	 $('#arriba_izquierda').unblock(); 
     $('#arriba_derecha').unblock(); 
     $('#abajo_izquierda').unblock();
     $("#CerrarTicket").removeClass("actionbtn");
     $("#CerrarTicket").addClass("closebtn");
     $("#efectivo").attr({disabled:true}).val("");
     $("#cambio").val("");
     
     
	 main.efectivo=undefined;
	}else {
	 //hace focus al input efectivo
	$("#efectivo").attr({disabled:false}).focus();
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
	}
   changeClass('Efectivo');
}
//-------------------------------------------CERRARTIQUETMOUSEDOWN----------------------------------------//
function cerrarTiquetMouseDown(){
	//main.comanda().efectivo=$("#efectivo").val();
	//if($("#efectivo").val()){
	if (main.efectivo) { 
	 
	 //insert la nueva comanda abierta
	 sendComandaAbierta();
	 
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
    $("#idComanda").val("");
    var numero = parseInt(main.comanda().comandaID)+1;
    $("#idComanda").val("B"+numero);
    listaPedidos.fijarComanda();
    clienteScreen.setClienteName("");
    main.comanda().estado="cerrado";
	}else alert("Por favor indroduzca el efectivo!");
	changeClass('CerrarTicket');
}
//HACER: Mensaje de confirmacion si aun existen comandas abiertas. 
//-------------------------------------------LIBERAMESAMOUSEDOWN----------------------------------------//
function liberaMesaMouseDown(id){
//	if (main.comanda().isAbierta()){
	// if(confirm('Existe una comanda aun abierta, quieres borrarla?')){
     //vaciar las lineas de la comanda y liberar la mesa
     mesaLibre();
//	 }
 //  }
   changeClass(id);
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
  clienteScreen.setBlueColor();
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
function hotkeys(){
	/*jQuery.hotkeys.add('0',function (){ calmousedown("0","0");changeClass("0")});
	jQuery.hotkeys.add('1',function (){ calmousedown("1","1");changeClass("1")});
	jQuery.hotkeys.add('2',function (){ calmousedown("2","2");changeClass("2")});
	jQuery.hotkeys.add('3',function (){ calmousedown("3","3");changeClass("3")});
	jQuery.hotkeys.add('4',function (){ calmousedown("4","4");changeClass("4")});
	jQuery.hotkeys.add('5',function (){ calmousedown("5","5");changeClass("5")});
	jQuery.hotkeys.add('6',function (){ calmousedown("6","6");changeClass("6")});
	jQuery.hotkeys.add('7',function (){ calmousedown("7","7");changeClass("7")});
	jQuery.hotkeys.add('8',function (){ calmousedown("8","8");changeClass("8")});
	jQuery.hotkeys.add('9',function (){ calmousedown("9","9");changeClass("9")});*/
	jQuery.hotkeys.add('n0',function (){ calmousedown("0","0");changeClass("0")});
	jQuery.hotkeys.add('n1',function (){ calmousedown("1","1");changeClass("1")});
	jQuery.hotkeys.add('n2',function (){ calmousedown("2","2");changeClass("2")});
	jQuery.hotkeys.add('n3',function (){ calmousedown("3","3");changeClass("3")});
	jQuery.hotkeys.add('n4',function (){ calmousedown("4","4");changeClass("4")});
	jQuery.hotkeys.add('n5',function (){ calmousedown("5","5");changeClass("5")});
	jQuery.hotkeys.add('n6',function (){ calmousedown("6","6");changeClass("6")});
	jQuery.hotkeys.add('n7',function (){ calmousedown("7","7");changeClass("7")});
	jQuery.hotkeys.add('n8',function (){ calmousedown("8","8");changeClass("8")});
	jQuery.hotkeys.add('n9',function (){ calmousedown("9","9");changeClass("9")});

	jQuery.hotkeys.add('backspace',function (){ borrar("Borrar");changeClass("Borrar")});
	jQuery.hotkeys.add('e',function (){ efectivo();changeClass("Efectivo")});
	
	
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
    imgpath: '/Restaurante/css/images',
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
    imgpath: '/Restaurante/css/images',
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
 	//$("#clienteTypeInfo").html(nombre);
 	clienteScreen.setClienteName(nombre);
 	main.id_cliente=id;
 	if (main.comanda()){
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
