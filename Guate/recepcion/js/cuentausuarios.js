//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClassUsuario(id){
$(".amarillo").toggleClass("amarillo");
btncolor(id);
}
function btncolor(id){
$("#"+id).toggleClass("amarillo");
}

function redondea(num){
	parseFloat(num);
	return (Math.round(num*100)/100);
}
//-------------------------------------------LOAD USUARIOS----------------------------------------------------------//
function loadusuarios(json){
	if (json && json.UsuariosInfo){
		$("#usuariosTable").html(" ");
		for(i=0;i<json.UsuariosInfo.length;i++) {
			$("#usuariosTable").append("<tr id=T"+json.UsuariosInfo[i].idTrabajador+" onmousedown='changeClassUsuario(this.id);loadcuenta(this.id);'><td><h4><center class='onomataki'>"+json.UsuariosInfo[i].nombre+"</center></h4></td></tr>");		
			$("#T"+json.UsuariosInfo[i].idTrabajador).addClass("green");
		}
	}
}
//-------------------------------------------LOAD CUENTA----------------------------------------------------------//
function loadcuenta(id){
 $.blockUI({ message: '<h1> Cargando los datos...</h1><h1>Espere por favor</h1><input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>' }); 
 $(".total").html("0");
 $(".pagado").html("0");
 $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"loadcuenta", idusuario:id}, function(json){
   $.unblockUI();
   json = verificaJSON(json);
   loadPage(json);
 });	
}
function loadPage(json){
	if (json.TicketsInfo)	loadTickets(json);
	else  $("#ticketsTable").html(" ");
	
	if (json.MovimientosInfo) loadmovimientos(json);
	else $("#movimientosTable").html(" ");
	
	if (!json.MovimientosInfo && !json.TicketsInfo) $("#ticketsTable").append("<tr id='no'><td colspan='7'style='text-align:center'><div style='margin-top:20px;margin-bottom:20px;'><h2> El usuario no ha consumido nada a&uacute;n...</h2></div></td></tr>");
	if (json.TotalTickets)  $(".total").html(Math.round(json.TotalTickets*100)/100);
}

function loadTickets(json){
	var mesActual;
	$("#ticketsTable").html(" ");
	for(i=0;i<json.TicketsInfo.length;i++) {
		numComanda=showid(json.TicketsInfo[i].numComanda);
		idCom=json.TicketsInfo[i].procedencia+json.TicketsInfo[i].idComanda;
		var mesDado = getMonth(json.TicketsInfo[i].fechaHora)+" "+getYear(json.TicketsInfo[i].fechaHora);
		if (mesDado != mesActual) $("#ticketsTable").append("<tr><td colspan='5' align='center'><h5>"+mesDado+"</h5></td></tr>"); 
		mesActual = mesDado;
		$("#ticketsTable").append("<tr id="+idCom+"><td class='checkbox' width=2%><input type='checkbox'  onclick='btncolor(\""+idCom+"\");'></td><td width=5%><h6 class='numcomand'>"+numComanda+"</h6></td><td width=25%><h6>"+json.TicketsInfo[i].fechaHora+"</h6></td><td width=6%><h6>"+json.TicketsInfo[i].total+"</h6></td><td><h6>"+json.TicketsInfo[i].nombre+"</h6></td></tr>");
		$("#"+idCom+" td:not(.checkbox)").mousedown(function(e){
			var num=$("#"+this.parentNode.id+" .numcomand").html();
			showpedido(this.parentNode.id,num);
		});
	}
}
function loadmovimientos(json){
	var pagado=0;
	$("#movimientosTable").html(" ");
	for(i=0;i<json.MovimientosInfo.length;i++) {
		idMov="M"+json.MovimientosInfo[i].id_movimiento;
		$("#movimientosTable").append("<tr id="+idMov+"><td class='checkbox' width=2%><input type='checkbox'  onclick='btncolor(\""+idMov+"\");'></td><td width=18%><h6>"+json.MovimientosInfo[i].fechaHora+"</h6></td><td width=8%><h6 class='tipoh6'>"+json.MovimientosInfo[i].tipo+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].dinero+"</h6></td><td><h6>"+json.MovimientosInfo[i].descripcion+"</h6></td><td><h6>"+json.MovimientosInfo[i].categoria+"</h6></td><td width=10%><h6>"+json.MovimientosInfo[i].encargado+"</h6></td></tr>");
		if (json.MovimientosInfo[i].dinero<0) pagado+=parseFloat(json.MovimientosInfo[i].dinero);
		if(json.MovimientosInfo[i].tipo=="anulado"){
			$("#"+idMov).css({ textDecoration:"line-through"});
			$("#"+idMov).addClass("redtext");
		}	
		if(json.MovimientosInfo[i].tipo=="cobrado") $("#"+idMov).addClass("verde");
	}
	$(".pagado").html(Math.abs(pagado));
}
//-------------------------------------------DECIDIR SI VA A APARECER EL NUMCOMANDA-------------------------------------------------//
function showid(numComanda){
if (numComanda!= null)	return numComanda;
else return "";
}
function getMonth(date){
var monthArray = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
return monthArray[new Number(date.substring(5,7))-1];
}
function getYear(date){
return new Number(date.substring(0,4));
}

//-------------------------------------------SHOW PEDIDO---------------------------------------------------//
function showpedido(id,numcomanda){
    if ($("#ticketsTable tr").hasClass("detail"+id)){
       	$(".detail"+id).remove();
    }else {
	// var idComDetail=id.substring(1);
	 $.getJSONGuate("Presentacion/jsongestioncaja.php",{idComDetailcuenta:id,numcomanda:numcomanda}, function(json){
      json = verificaJSON(json);
      if (json.pedidosInfo){		
       for(i=0;i<json.pedidosInfo.length;i++) {
        $("#"+id).after("<tr class='detail"+id+"'><td colspan=9><table cellspacing=0 cellpadding=0 width=100%><tr><td width=7% class='yellow'><h6>"+json.pedidosInfo[i].idPlatillo+"</h6></td><td width=6% class='yellow'><h6>"+json.pedidosInfo[i].cantidad+"</h6></td><td width=28% class='yellow'><h6>"+json.pedidosInfo[i].nombre+"</h6></td><td width=7% class='yellow'><h6>"+json.pedidosInfo[i].precio+"</h6></td><td>&nbsp;</td></tr></table></td></tr>");		
        }
        $("#"+id).after("<tr class='detail"+id+"'><td colspan=9><table cellspacing=0 cellpadding=0 width=100%><tr><td width=7% class='yellow'><h6>idPlatillo</h6></td><td width=6% class='yellow'><h6>can.</h6></td><td width=28% class='yellow'><h6>nombre</h6></td><td width=7% class='yellow'><h6>precio</h6></td><td>&nbsp;</td></tr></table></td></tr>");
        //$(".detail"+id).addClass("yellow");
       }
     });
   }
}
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
//-------------------------------------------MODALCREARCUENTA-------------------------------------------------//
function modalCrearCuenta(){
$.blockUI({
	theme:     true, 
    title:    'Crear nueva Cuenta', 
    message: $('#b6'),
    }); 
}
//-------------------------------------------CREAR CUENTA-------------------------------------------------//
function crear_cuenta(){
var nombreEmpleado= $("#nombreEmpleado").val();
var tipo= $("#tipo").val();
  if(confirm('Estas seguro que quieres crear una nueva cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"crearcuenta",nombreEmpleado:nombreEmpleado,tipo:tipo}, function(json){
      json = verificaJSON(json);
      //Se ha creado la cuenta
      loadusuarios(json);
      $("#ticketsTable").html(" ");
      $("#total").html("0");
      $("#nombreEmpleado").val("");
      $.unblockUI();
      if (json && json.UsuariosInfo) $.growlUI('Nueva cuenta creada', 'de '+nombreEmpleado+'!');
     });
  }
}
//-------------------------------------------ELIMINAR CUENTA-------------------------------------------------//
function eliminar_cuenta(){
var cuentadelete=$("#usuariosTable .amarillo").attr("id");
 if (cuentadelete){
  if(confirm('Estas seguro que quieres eliminar esta cuenta?')){
     $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"eliminar",cuentadelete:cuentadelete}, function(json){
      var mensaje;
      if (json.Mensaje) mensaje=1;
      json = verificaJSON(json);
      loadusuarios(json);
      $("#ticketsTable").html(" ");
      $("#movimientosTable").html(" ");
      $(".total").html("0");
      $(".pagado").html("0");
	  if (!mensaje) $.growlUI('Cuenta', ' eliminada!!');
     });
  }
 } else alert ("Elije primero la cuenta que quieres eliminar.");
}
//--------------------------------------------------------INSERT MOVIMIENTO A CREDITO--------------------------------------------------------//
function insertMovimiento(entrada,description,categoria){
	var idempleado=$("#usuariosTable .amarillo").attr("id");
 	if (description && categoria){
 		if (parseFloat(entrada) && idempleado ) {
			var dinero=parseFloat(entrada);
			insertmovcredito(dinero,description,categoria,idempleado);
		}else alert("Introduce correctamente el movimiento! Elige el empleado que te interesa!");
	} else alert("Tienes que introducir una descripcion!");

	//vaciar los campos usados
	$("#input_money").val("");
	$("#description").val("");
	$("#categoria").val(1);
}
//--------------------------------------------------------CALL GESTION CAJA--------------------------------------------------------//
function insertmovcredito(dinero,description,categoria,idempleado){
	if(confirm('�Estas seguro que quieres realizar este credito?')){
		var idencargado =$("#selUsers").val();
		$.blockUI({ message: '<h1> Cargando los datos...</h1><h1>Espere por favor</h1><input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>' });
		$.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"insmov",dinero:dinero,description:description,categoria:categoria,idempleado:idempleado,idencargado:idencargado}, function(json){
			$.unblockUI();
			json = verificaJSON(json);
			loadmovimientos(json);
			if (json.TotalTickets)  $(".total").html(Math.round(json.TotalTickets*100)/100);
			$.growlUI('Nuevo movimiento insertado', 'de '+dinero+' Quetzales!');
		});
	}
}
//--------------------------------------------------------IMPRIMIR CUENTA--------------------------------------------------------//
function imprimircuenta(){
	nameempleado=$("#usuariosTable .amarillo .onomataki").html();
	if (nameempleado) {
		fechas = $("#fechas").val();
		var regex = / - /;
		fecha = fechas.split(regex);
		var fechatext = "&fechaStart="+fecha[0]+"&fechaStop="+fecha[1]; 
		pagado=$(".pagado").html();
		idemp=$("#usuariosTable .amarillo").attr("id");
		if ($("#printall").is(":checked"))	document.location="Presentacion/imprimircuenta.php?name="+nameempleado+"&id="+idemp+"&pagado="+pagado;
		else if (fechas) document.location="Presentacion/imprimircuenta.php?name="+nameempleado+"&id="+idemp+"&pagado="+pagado+fechatext;
   		else alert ("Antes de imprimir, introduce entre que fechas quieres imprimir la cuenta");
    } else alert ("Hay que elegir una persona antes de imprimir la cuenta");
}
//--------------------------------------------------------PAGAR CREDITO--------------------------------------------------------//
function pagarcredito(){
	idempleado=$("#usuariosTable .amarillo").attr("id");
	var money= $("#money").val();	
	var idencargado =$("#selUsers").val();
	if(confirm('�Estas seguro que quieres realizar este movimiento?')){
		$.blockUI({ message: '<h1> Cargando los datos...</h1><h1>Espere por favor</h1><input type="button" value="Cancelar" style="margin-top:5px;margin-left:10px" onClick="$.unblockUI();"/>' });
		$.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"pagarcred",money:money,idempleado:idempleado,idencargado:idencargado}, function(json){
			$.unblockUI();
			json = verificaJSON(json);
			loadmovimientos(json);
			$(".total").html(Math.round(json.TotalTickets*100)/100);
			$.growlUI('Nuevo credito cobrado', 'de '+money+' Quetzales!');
		});
 	}
}
//--------------------------------------------------------DO SEARCH NOMBRE EMPLEADO--------------------------------------------------------//
function doSearch(){
 if(timeoutHnd) clearTimeout(timeoutHnd);
 timeoutHnd = setTimeout(nombreReload,500);
}
function nombreReload(){
 var mask = jQuery("#searchNombre").val();
 $.getJSONGuate("Presentacion/jsoncuentausuarios.php",{service:"buscarnombre",mask:mask}, function(json){
   json = verificaJSON(json);
   loadusuarios(json);
   $("#ticketsTable").html(" ")
   $("#movimientosTable").html(" ");
   $(".total").html("0");
   $(".pagado").html("0");
   });
}