<?php
$_SESSION['pseudo']="mesero";
$_SESSION['last_chat_message_id']="0";
?>
<html>
<head>
<link href="/common/css/chat.css" rel="stylesheet" type="text/css" />
		<script src="/common/js/jquery-1.2.3.pack.js"></script>
		<script src="/common/js/jquery.chat.js"></script>

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
<script type="text/javascript">
$(function(){
	$('#myChat').ajaxChat();
});
</script>
</div>
</body>
</html>