/**
 * @author vincent voyer
 * vincent.voyer@gmail.com
 */
(function($){
	$.fn.ajaxChat = function(params){
		
		var params = $.extend({
			refresh:5
		},params);
		
		var chat = function (jElt) {
			//jElt is the jQuery object where the function starts
			var chatContainer=jElt.find('.chat');
			var chat=chatContainer.find('div'); // this is div containing the messages
			var writeInput=jElt.find('.writeInput');
			var chooseNickname=jElt.find('.chooseNickname');
			var ajaxStatus=jElt.find('.ajaxStatus');
//ISAAC
				$.post("/restbar/Presentacion/serverchat.php", {nickname: "cocina"}, 
				  function(data){
					if (data) {
						chatContainer.show();
						readMessages();
						
						writeInput.find(':input').val('Hola, estoy dentro del chat!').parent().trigger('submit');
						
					} else {
						alert('bad nickname, try something else !');
					}
				   }, 'json');
				    $("#eliminar").mousedown(function(){
				       var cantidad=$("#pedidosTable .white .cantidad").html();
				       var nombre=$("#pedidosTable .white .nombre").html();
				       var numComanda=$("#pedidosTable .white .numComanda").html();
      					if (cantidad != null)writeInput.find(':input').val('Listo '+cantidad+' '+nombre+' de '+numComanda).parent().trigger('submit');
				    });
//END ISAAC
			
			// handle the submit message function
			var activateKeyboard = function(){
				writeInput.submit(function(){
					var input = $(this).find(':input');
					var message = input.val();
					
					if ($.trim(message).length > 0) { // need to have something to say !
						ajaxStatus.show();
						
						input.val('');
						input.blur();
						input.attr("disabled", "disabled"); // we have to this so there'll be less spam messages
						$.post("/restbar/Presentacion/serverchat.php", { //this is the url of your server side script that will handle write function
							msg: message
						}, function(data){
							input.removeAttr("disabled");
							input.focus();
							if (data) 
								chat.append('<p><small>('+ data.time +')</small> ' + data.nickname + ' &gt; <strong>' + data.msg + '</strong></p>');
								var objDiv = document.getElementById("chatCocina");
    							objDiv.scrollTop = objDiv.scrollHeight;
								
							ajaxStatus.hide();
						}, 'json');
					}
					return false;
				});
			}
			
			// handle the read messages function
			var readMessages = function(){
				$.getJSON("/restbar/Presentacion/serverchat.php", function(data){
					$.each(data, function(i,msg){
						chat.append('<p><small>('+ msg.time +')</small> '+msg.nickname+' &gt; <strong>'+msg.msg+'</strong></p>');
					});
					var objDiv = document.getElementById("chatCocina");
    				objDiv.scrollTop = objDiv.scrollHeight;
    				sound2Play();
					setTimeout(readMessages,params.refresh*1000);
				});
			}
			
			chooseNickname.submit(function(){
				var tryNickname=$(this).find(':input:first').val();
				
				$.post("/restbar/Presentacion/serverchat.php", { //this is the url of your server side script that will handle write function
					nickname: tryNickname
				}, function(data){
					if (data) {
						chooseNickname.remove();
						
						chatContainer.show();
						writeInput.show();
						readMessages();
						
						writeInput.find(':input').val('hello i\'m there !').parent().trigger('submit');
						
					} else {
						alert('bad nickname, try something else !');
					}
				}, 'json');
				
				return false;
			});
			
			chatContainer.hide();
			writeInput.hide();
			ajaxStatus.hide();
			
			activateKeyboard();
		}
		
		return this.each(function(){
			chat($(this));
		});		
	};
})(jQuery)