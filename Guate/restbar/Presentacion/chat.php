<html>
<head>
<link href="/common/css/chat.css" rel="stylesheet" type="text/css" />
		<script src="/common/js/jquery-1.2.3.pack.js"></script>
		<script src="/common/js/jquery.chat.js"></script>

</head>
<body>
<div id="myChat">
	<div class="chat">
		<div></div>
	</div>

	<form action="" method="post" class="writeInput">
		<input type="text" value="" />
	</form>
	
	<form action="" method="post" class="chooseNickname">
		<p>Choisis un pseudo, choose a nickname : (3-12 chars - plain text)</p>
		<input type="text" value="" maxlength="100" /> <input type="submit" value="ok" />
	</form>
	
	<img src="/img/ajax-loader.gif" class="ajaxStatus" />

</div>

<script type="text/javascript">
$(function(){
	$('#myChat').ajaxChat();
});
</script>
</body>
</html>