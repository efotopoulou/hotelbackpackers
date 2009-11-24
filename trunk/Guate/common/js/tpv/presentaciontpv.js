//----------------------------------------------PLATOMOUSEDOWN--------------------------------------------//
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
	//if (!(main.comanda() && main.comanda().isAbierta()) ){	
	if (!main.comandaAbierta()){
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
     calcularTotal();
 }else alert('Por favor, elegid primero la mesa que os interesa');
}


//-------------------------------------------EFECTIVOMOUSEDOWN----------------------------------------//
function efectivo(){
  if (main.mesa() && main.comanda() && main.comandaCocina()){
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
	}
  }
   changeClass('Efectivo');
}

//-------------------------------------------CERRARTIQUETMOUSEDOWN----------------------------------------//
function cerrarTiquetMouseDown(){
//Si los botones de cliente son Credito o Gratis, el cajero no puede apretar el efectivo. Hacemos como si lo hubiese apretado. 
    if ((main.currentClient ==1 || main.currentClient ==5) && main.comandaCocina()){
     main.comanda().id_cliente=main.id_cliente;
     main.efectivo=1;
    }
	if (main.efectivo && main.comandaCocina()) {
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
    $("#idComanda").val(main.numDefaultID);
    listaPedidos.fijarComanda();
    for (i=main.mesa().comanda.length-1;i>=0 && !main.mesa().comanda[i].isCerrada();i--) {
       listaPedidos.mensajeCocinaAnt("Comanda Cerrada",i);
       main.mesa().comanda[i].estado="cerrado";
     }
    clienteScreen.setClienteName("");
    main.free=undefined;
    //main.comanda().estado="cerrado";
	}
	changeClass('CerrarTicket');
}
function sendComanda(){

    var comandas=new Array();
     var i;
     for (i=main.mesa().comanda.length-1;i>=0 && !main.mesa().comanda[i].isCerrada();i--) {
  		var comandaAux = jQuery.extend(true, {}, main.mesa().comanda[i]);
  		comandaAux.isAbierta = "";
  		comandaAux.isCocina = "";
  		comandaAux.isCerrada = "";
  		comandas.push(comandaAux); 
       //comandas.push(main.mesa().comanda[i]);
     }

 var myJsonMain = JSON.stringify(comandas);
  $.getJSONGuate("Presentacion/jsonsavetpv.php",{ json: myJsonMain,mesa:main.currentMesa}, function(json){
    if (json["Mensaje"]) {
    	changeClass('Efectivo');
    	efectivo();
    }
    json = verificaJSON(json);
  });
}
function sendCocina(){
  var comandaAux = jQuery.extend(true, {}, main.comanda());
  comandaAux.isAbierta = "";
  comandaAux.isCocina = "";
  comandaAux.isCerrada = "";
  var myJsonMain = JSON.stringify(comandaAux); 
  $.getJSONGuate("Presentacion/jsonsendCocina.php",{ json: myJsonMain}, function(json){
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
    }else if (main.comanda().isCocina()){
      if(confirm('Existe una comanda enviada en la cocina, quieres borrarla?')) mesaLibre();
    }else mesaLibre();
   }
   changeClass(id);
}
//-------------------------------------------COCINA----------------------------------------//
function cocina(id){
  if (main.comandaAbierta()){
   guardarComandaId();
   listaPedidos.mensajeCocina("Pedido en Cocina");
   listaPedidos.fijarComanda();
   main.comanda().estado="cocina";
   sendCocina();
   //Enviar comanda a la cocina.
  }
  changeClass(id);
}

function propina(id){
   main.propina=!main.propina;
   changeClass(id);
   if (main.mesa() && main.comanda())main.pushLiniaComanda(main.currentMesa);
}
function mesaLibre(){
  listaPedidos.reiniciar();
  //borra la mesa y cambia el color a azul(o sea que libre)
  //Ponemos el clienttype, el clienteNormal
  clienteScreen.setBlueColor();
  mesaScreen.setBlueColor();
  //$("#mesa"+main.currentMesa).toggleClass("blueFuerte").toggleClass("btnunpress").toggleClass("orange").toggleClass("redFuerte");
  main.mesa().currentComanda=-1;
  listaPedidos.modifyTotal("0");
  main.mesas[main.currentMesa]= undefined;
    //Ponemos el clienttype, el clienteNormal
  clienteScreen.setCorrectColor(4);
  clienteScreen.setClienteName("");
  main.id_cliente=undefined;
}


function calcularTotal(){
	var precioTotal=0;
	if(main.mesa()){
	 jQuery.each(main.comanda().liniasComanda,function() {
       precioTotal+=this.precioN;
     });
     precioTotal=redondea(precioTotal);
     main.comanda().total=precioTotal;
     main.comanda().totalPropina=listaPedidos.modifyTotal(precioTotal);
     //Calculando precio de comanda en cocina anteriores
     var totAnt=0;
     var i;
     for (i=main.mesa().comanda.length-1;i>=0 && !main.mesa().comanda[i].isCerrada();i--) {
       totAnt+=main.mesa().comanda[i].totalPropina;
     }
     $("#total").val(totAnt);
	}
    return precioTotal;
}
function calcularTotalFijo(num){
	var precioTotal=0;
	if(main.mesa()){
	 jQuery.each(main.mesa().comanda[num].liniasComanda,function() {
       precioTotal+=this.precioN;
     });
     precioTotal=redondea(precioTotal);
     main.comanda().total=precioTotal;
     main.comanda().totalPropina=listaPedidos.modifyTotalFijo(precioTotal,num);
	}
    return precioTotal;
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
    }
  },false);
}
 function actualizarListaProductos(num){
 	var linia = main.comanda().liniasComanda;
 	for (i=0;i<linia.length;i++){
 		precio=escogePrecio(linia[i].precioNormal,linia[i].precioLimitado);
 		linia[i].precioUnidad=precio;
 		var cantidad = linia[i].cantidad;
 		if (!cantidad) cantidad=1;
 		linia[i].precioN=precio*cantidad;
 	}
 	calcularTotal();
  	main.pushLiniaComanda(main.currentMesa);
 }
 
 function calcularCambio(){
	$("#cambio").val(redondea(main.comanda().efectivo-$("#total").val()));
}
function guardarComandaId(){
	 var defaultID = "";
    defaultID=$("#idComanda").val();
    var numDefaultID=parseInt(defaultID);
    //alert('change:'+numDefaultID);
    if (main.mesa()){
     main.comanda().comandaID=defaultID;
     main.numDefaultID=numDefaultID+1;
    }else main.numDefaultID=numDefaultID;
}

//-------------------------------------------MESAMOUSEDOWN----------------------------------------//
function mesamousedown(id){
  var num=id.substring(4);
  var aux=main.currentMesa;
  //Guarda el valor de IdComanda en la mesa que es ahora current (AUN NO SE HA CANVIADO DE CURRENT)
  guardarComandaId();
  
  //asigna la mesa que se ha apretado como current
  main.currentMesa=parseInt(num);
  
  //Cambiar el color de los botones de Mesa
  mesaScreen.setCorrectColor(num);
  
  //Cambiar el color de los botones de ClientType
  if (main.mesa()) clienteScreen.setCorrectColor(main.comanda().currentClientType);
  else clientemousedown(4); 
  if(main.mesas[num]) main.carga(num);
  else main.creaEfecto(num);
 }       

 
//-------------------------------------------CLIENTEMOUSEDOWN----------------------------------------//
//Hay que llamar a esta funcion despues de asignarle el currentMesa
function clientemousedown(num){
	main.id_cliente=undefined;
	clienteScreen.setCorrectColor(num);
	if (main.comandaAbierta()) actualizarListaProductos(num);
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
if (main.comandaAbierta()) main.comanda().free = free;
main.free = free;
clienteScreen.setClienteName(free);
$.unblockUI();
hotkeys();
$("#freevol").val("");
}
function cancelarCliente(){
	$.unblockUI();
	hotkeys();
	clienteScreen.setClienteName(" ");
	main.id_cliente=undefined;
	clienteScreen.setCorrectColor(4);
	activarEfectivo();
	$("#freevol").val("");
	if (main.comandaAbierta()){
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
 }else if (main.comandaAbierta()){
   listaPedidos.calcCantidad(id);
   main.linia().cantidad += texto;
  //Calculando el nuevo precio a mostrar
   calcularPrecio();
  //Calculando TOTAL
  calcularTotal();
 }
 changeClass(id);
}
//-------------------------------------------BORRARMOUSEDOWN----------------------------------------//
function borrar(id){
 if (main.efectivo) {
  main.comanda().efectivo=parseInt(main.comanda().efectivo /10);
  $("#efectivo").val(main.comanda().efectivo);
  calcularCambio();
 }
  else if(main.comandaAbierta()){
  	if(main.linia().cantidad && main.linia().cantidad.length>1){
     var quantity = main.linia().cantidad.substring(0,main.linia().cantidad.length-1);
     listaPedidos.setCantidad(quantity);
     main.linia().cantidad = quantity;
     calcularPrecio();
  	}else borrarLinia();
  	//Calculando TOTAL
     calcularTotal();
  }
 changeClass(id);
}

function borrarLinia(){
	listaPedidos.removeLine();
  if (main.comanda().numRow>=0){
  	 main.comanda().liniasComanda.pop();
  	main.comanda().numRow--;
  }  
  if (main.comanda().numRow==-1 && main.currentCom()==0) mesaLibre();
}
function calcularPrecio(){
  var candity=parseFloat(main.linia().cantidad);
  var newprecio= redondea(candity*(main.linia().precioUnidad));
  listaPedidos.setPrecio(newprecio);
  main.linia().precioN=newprecio;	
}
function redondea(num){
	return (Math.round(num*100)/100);
}
function changeClass(id){
// $("#"+id).toggleClass("btnpress");
// $("#"+id).toggleClass("btnunpress");
// $("#"+id).toggleClass("redtext");
 if (main.calPressedId != id) main.calPressedId = id;
 else main.calPressedId = undefined;
}
function comprobarOut(id){
 if (main.calPressedId==id){
// 	$("#"+id).removeClass("btnpress");
// 	$("#"+id).addClass("btnunpress");
// 	$("#"+id).removeClass("redtext");
 	main.calPressedId = undefined;
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
 jQuery("#list3").setGridParam({url:"/recepcion/Presentacion/jsongrid.php?q=trabajador&nm_mask="+nm_mask,page:1}).trigger("reloadGrid");
}
function doSearch(){
 if(timeoutHnd) clearTimeout(timeoutHnd);
 timeoutHnd = setTimeout(gridReload,500);
}
 function guardarDatosCliente(id, nombre){
 	clienteScreen.setClienteName(nombre);
 	main.id_cliente=id;
 	if (main.comandaAbierta()){
 		main.comanda().clienteName=nombre;
 		main.comanda().id_cliente=id;
 	}
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
 function cajaCerrada(){
   $("#tablageneraldiv").block({ message: $('#cajaCerrada')}); 	
 }
 //-------------------------------------------MOSTRAR LISTA DE CLIENTES Y TRABAJADORES------------------//
function mostrarListaClientes(){
  jQuery("#list2").jqGrid({
    url:'/recepcion/Presentacion/jsongrid.php?q=cliente&nd='+new Date().getTime(),
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
    url:'/recepcion/Presentacion/jsongrid.php?q=trabajador&nd='+new Date().getTime(),
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