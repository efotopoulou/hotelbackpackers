//Clase ClienteScreen
function ClienteScreen(){
 this.setClienteName = function(name){
   if (!name) name ="";
   $("#clienteTypeInfo").html(name);
 }
 this.setCorrectColor = function(num){
	//borrar todos los colores rojos y azules de los botones client
	this.setNoColor();
	
	//si existe la comanda, poner el boton rojo y el current client se pone a num, sino azul 
	if (main.comanda() && main.comanda().isAbierta()) {
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