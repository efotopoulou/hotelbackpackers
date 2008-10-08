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
    if ((main.currentClient ==1 || main.currentClient ==5) && main.comandaAbierta()){
     main.comanda().id_cliente=main.id_cliente;
     main.efectivo=1;
    }
	if (main.efectivo && main.mesa() && main.comanda() && main.comanda().isAbierta()) {
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


function calcularTotal(){
	var precioTotal=0;
	if(main.mesa()){
	 jQuery.each(main.comanda().liniasComanda,function() {
       precioTotal+=this.precioN;
     });
     precioTotal=redondea(precioTotal);
     main.comanda().total=precioTotal;
     $("#precioTotal"+main.currentCom()).html(""+precioTotal);
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
    	//alert(comandaAux["tipoCliente"]);
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
 
