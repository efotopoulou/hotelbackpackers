//-------------------------------------------PLATOMOUSEDOWN----------------------------------------//
function platomousedown(plato,platoid,precioN,precioLim,id){
  //si hay un clienttype elegido
  if(main.currentClient){
	if (!main.comanda() || !main.comanda().isAbierta()){	
	 var aux = main.currentComanda;
	 main.currentComanda+=1;
	 main.comandaArray[main.currentComanda]=new Comanda();
	 main.comanda().currentClientType = main.currentClient;
	 main.comanda().free = main.free;
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
    if ((main.currentClient ==1 || main.currentClient ==5)&& main.comanda() && main.comanda().isAbierta()){
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


//-------------------------------------------sendComanda----------------------------------------//
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
function calcularCambio(){
	$("#cambio").val(redondea(main.comanda().efectivo-main.comanda().totalPropina));
}
 