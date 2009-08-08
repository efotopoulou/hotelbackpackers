function salir(){
	location.href='/view.php';
}
function verificaJSON(json){
	if (json["Mensaje"]) alert(json["Mensaje"]);
	if (json["Error"]) salir();
	return json["Datos"];
}

(function($){ 
$.getJSONGuate = function(url, data, callback, isSyncr) {  
  if ( data )
			// If it's a function
			if ( jQuery.isFunction( data ) ) {
				// We assume that it's the callback
				isSyncr=callback;
				callback = data;
				data = null;
			}
			if (jQuery.isFunction( isSyncr ) || isSyncr==undefined) isSyncr=true;
     jQuery.ajax({
              data:data,
              dataType:"json",
              error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "error"){
                  $.getJSON("Presentacion/jsonlog.php",{url:this.url});
                  alert("La pagina: "+this.url +" que intentas acceder no existe");
                }
                if (textStatus == "parsererror"){
                  $.getJSON("Presentacion/jsonlog.php",{url:this.url,text:XMLHttpRequest.responseText});
                  alert("La pagina: "+this.url +" que intentas acceder no devuelve un JSON correcto. Respuesta: "+XMLHttpRequest.responseText);
                }
                this; // the options for this ajax request
               },
              success:callback,
  			  type:"POST",
  			  url:url,
  			  async:isSyncr
  });
}
})(jQuery); 

