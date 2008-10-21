// CLASES DE PRESENTACION
//Clase MesaScreen
function MesaScreen(){
 this.setCorrectColor = function(num){
  $(".mesa").removeClass("blueFuerte").removeClass("redFuerte");
  if (main.mesa()) $("#mesa"+num).addClass("redFuerte");
  else $("#mesa"+num).addClass("blueFuerte"); 
 }
 this.setRedColor = function(){
   $("#mesa"+main.currentMesa).removeClass("blueFuerte").removeClass("btnunpress").addClass("orange").addClass("redFuerte");
 }
 this.setBlueColor = function(){
   $("#mesa"+main.currentMesa).addClass("blueFuerte").addClass("btnunpress").removeClass("orange").removeClass("redFuerte");
 }
}

//Clase ClienteScreen
function ClienteScreen(){
 this.setClienteName = function(name){
  if (!name) name ="";
  $("#clienteTypeInfo").html(name);
 }
 this.setCorrectColor = function(num){
	//borrar todos los colores rojos y azules de los botones client
	this.setNoColor();
	
	//Activar o desactivar el efectivo
	if (num==5 || num==1)desactivarEfectivo();
	else activarEfectivo();
		
	//si existe la mesa o comanda, poner el boton rojo y el current client se pone a num, sino azul 
	if (main.comandaAbierta()) {
		this.setRed(num);
		main.comanda().currentClientType=num;
	}
	else this.setBlue(num);
    main.currentClient = num;
 }
 this.setClientName = function(nombre){
   $("#clienteTypeInfo").html(nombre);
 }
 this.getClienteName = function(){
  return $("#clienteTypeInfo").html();
 }
 this.setRedColor = function(){
  	$("#clientpressed"+main.currentClient).removeClass("blueFuerte").addClass("redFuerte");
 }
 this.setRed = function(num){
  	$("#clientpressed"+num).addClass("redFuerte");
 }
 this.setBlue = function(num){
   $("#clientpressed"+num).addClass("blueFuerte");
 }
 this.setBlueColor = function(){
   $("#clientpressed"+main.currentClient).addClass("blueFuerte").removeClass("redFuerte");
 }
 this.setNoColor = function(){
 $(".client").removeClass("blueFuerte").removeClass("redFuerte");
 }
}