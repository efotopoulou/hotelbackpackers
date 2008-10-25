<html>
<head>
<title>Chat</title>
<link href="/common/css/chat.css" rel="stylesheet" type="text/css" />
		<script src="/common/js/jquery-1.2.3.pack.js"></script>
		<script src="/common/js/guate.js"></script>
		<script src="/common/js/testsound.js"></script>
<script type="text/javascript">
var nick="mesero";
var lastchatid=0;
var refresh=5;
$(document).ready(function(){
 read();
 $(".writeInput").submit(function(){
   write();
   return false;
 });
});

// handle the read messages function
function read(){
$.getJSONGuate("/restbar/Presentacion/serverchat.php",{serv:1,nick:nick, lastchatid:lastchatid} ,function(json){
	json = verificaJSON(json);
	if (json){
	 if (json.Mensajes){
	 	for(i=0;i<json.Mensajes.length;i++) {
	 	var msg = json.Mensajes[i];
	 	  $(".chat").append('<p><small>('+ msg.time +')</small> '+msg.nickname+' &gt; <strong>'+msg.msg+'</strong></p>');
	 	}
	 	sound2Play();
	 }
	 if (json.lastchatid) lastchatid=json.lastchatid;
	}
	var objDiv = document.getElementById("chatMesero");
	objDiv.scrollTop = objDiv.scrollHeight;
	$("#lastchat").html(lastchatid);
	setTimeout(read,refresh*1000);
	});
}
function write(){
	var input = $(".writeInput").find(':input');
	var message = input.val();
	if ($.trim(message).length > 0) { // need to have something to say !
		input.val('');
		input.blur();
		input.attr("disabled", "disabled"); // we have to this so there'll be less spam messages
		$.getJSONGuate("/restbar/Presentacion/serverchat.php",{serv:2,nick:nick, msg: message} ,function(json){
			json = verificaJSON(json);
			input.removeAttr("disabled");
			input.focus();
			if (json && json.Mensajes){
				var data =  json.Mensajes;
				$(".chat").append('<p><small>('+ data.time +')</small> ' + data.nickname + ' &gt; <strong>' + data.msg + '</strong></p>');
			}
			var objDiv = document.getElementById("chatMesero");
    		objDiv.scrollTop = objDiv.scrollHeight;					
		});
	}
}
</script>
</head>
<body>
<div id="secundario" style="margin-top:0px;width:100%;height:100%;">

<div id="myChat" style="height:80%">
	<div class="chat" id="chatMesero">
		<div></div>
	</div>
	<div>
	  <form action="" method="post" class="writeInput">
		<input type="text" value="" />
	  </form>
    </div>
</div>
<div id="lastchat"></div>

<script type="text/javascript">
//$(function(){
//	$('#myChat').ajaxChat();
//});
</script>
</div>
</body>
</html>