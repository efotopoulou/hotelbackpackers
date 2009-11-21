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
}
function cancelarCliente(){
	$.unblockUI();
	hotkeys();
	clienteScreen.setClienteName(" ");
	main.id_cliente=undefined;
	clienteScreen.setCorrectColor(4);
	activarEfectivo();
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
  //calcularTotal();
  $("#total").val(calcularTotal());
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
     $("#total").val(calcularTotal());
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
 var auxComanda = eval (main.comanda().toSource());
 alert(auxComanda.isAbierta);
 var myJsonMain = JSON.stringify(auxComanda);
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