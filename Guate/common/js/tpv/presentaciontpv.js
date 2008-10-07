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
  else if (!main.currentClient)  clientemousedown(4); 
  if(main.mesas[num]) main.carga(num);
  else main.creaEfecto(num);
 }       

 
//-------------------------------------------CLIENTEMOUSEDOWN----------------------------------------//
//Hay que llamar a esta funcion despues de asignarle el currentMesa
function clientemousedown(num){
	main.id_cliente=undefined;
	clienteScreen.setCorrectColor(num);
	if (main.mesa() && main.comanda() && main.comanda().isAbierta()) actualizarListaProductos(num);
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
if (main.mesa() && main.comanda() && main.comanda().isAbierta()) main.comanda().free = free;
main.free = free;
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
 }
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
	if (!main.comanda() || !main.comanda().isAbierta()){	
	 var aux = main.mesa().currentComanda;
	 main.mesa().currentComanda+=1;
	 main.mesa().comanda[main.mesa().currentComanda]= new Comanda();
	 main.comanda().currentClientType = main.currentClient;
	 main.comanda().free = main.free;
	 guardarDatosCliente(main.id_cliente,clienteScreen.getClienteName());
	 if (main.mesa().currentComanda) {
	  listaPedidos.addComanda();	
	  //main.comanda().currentClientType=main.mesa().comanda[aux].currentClientType;
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
  else if(main.mesa() && main.comanda() && main.linia()&& main.comanda().isAbierta()){
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
  if (main.mesa() && main.comanda() && main.comanda().isAbierta()){
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
     guardarComandaId();
     main.comanda().id_cliente=main.id_cliente;

	 //activa el boton cerrarticket
	 $("#CerrarTicket").removeClass("closebtn");
	 $("#CerrarTicket").addClass("actionbtn");

	 main.efectivo=1;
	 //insert la nueva comanda abierta
	 //sendComandaAbierta();
	}
  }
   changeClass('Efectivo');
}

//-------------------------------------------CERRARTIQUETMOUSEDOWN----------------------------------------//
function cerrarTiquetMouseDown(){
//Si los botones de cliente son Credito o Gratis, el cajero no puede apretar el efectivo. Hacemos como si lo hubiese apretado. 
    if ((main.currentClient ==1 || main.currentClient ==5) && main.mesa() && main.comanda() && main.comanda().isAbierta()){
     main.comanda().id_cliente=main.id_cliente;
     main.efectivo=1;
    }
	if (main.efectivo && main.mesa() && main.comanda() && main.comanda().isAbierta()) {
    //$.getJSONGuate("Presentacion/jsonupdatetpv.php",{ efectivo: main.comanda().efectivo,id:main.comanda().comandaID}, function(json){
     // json = verificaJSON(json);
    //});
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
    $("#idComanda").val("R"+main.numDefaultID);
    listaPedidos.fijarComanda();
    clienteScreen.setClienteName("");
    main.comanda().estado="cerrado";
	}
	changeClass('CerrarTicket');
}
function sendComanda(){
 var myJsonMain = JSON.stringify(main.comanda());
  $.getJSONGuate("Presentacion/jsonsavetpv.php",{ json: myJsonMain}, function(json){
    if (json["Mensaje"]) {
    	changeClass('Efectivo');
    	efectivo();
    }
    json = verificaJSON(json);
  });
}

//-------------------------------------------LIBERAMESAMOUSEDOWN----------------------------------------//
function liberaMesaMouseDown(id){
   if (main.mesa() && main.comanda()){
	 if (main.comanda().isAbierta()){
	  if(confirm('Existe una comanda aun abierta, quieres borrarla?')) mesaLibre();
    }else mesaLibre();
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
  //Ponemos el clienttype, el clienteNormal
  clienteScreen.setBlueColor();
  mesaScreen.setBlueColor();
  //$("#mesa"+main.currentMesa).toggleClass("blueFuerte").toggleClass("btnunpress").toggleClass("orange").toggleClass("redFuerte");
  main.mesa().currentComanda=-1;
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
  $.getJSONGuate("Presentacion/jsonsavetpv.php",{ json: myJsonMain,mesa:main.currentMesa}, function(json){
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
  $.getJSONGuate("Presentacion/jsonhibernar.php",{ json: myJsonMain}, function(json){
    json = verificaJSON(json);
  });
}
function restoreHibernar(){
  $.getJSONGuate("Presentacion/jsonhibernar.php",{ restore: "yes"}, function(json){
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
 	if (main.mesa() && main.comanda() && main.comanda().isAbierta()){
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