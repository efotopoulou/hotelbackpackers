<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sugerencias</title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="/common/js/guate.js"></script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
	<script>
	function enviarSugerencia(){
		texto = $("#textSugerencia").val();
		$.getJSONGuate("Presentacion/jsongestionsugerencia.php",{texto:texto}, function(json){
           	  json = verificaJSON(json);
        });
	}
	</script>
</head>

<body>

<div id="base">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>

	<div id="principal">
	<h5 class="titulos">Sugerencias</h5>		
	 	<div class="box_amarillo" align="center" style="overflow:auto;float: left; height:75%; width: 92%; margin-top:20px;align:center">
			Escribe aqui tu comentario o sugerencia, y nosotros la atenderemos lo antes posible.
			<textarea style=" margin-top: 10px;width:90%;height:50%" id="textSugerencia"/></textarea>
			<input type="button" value="Enviar" style="width:100px; margin-top:20px;" onClick="enviarSugerencia();"/>
		</div>	
		
		
			
	</div>

	<div id="secundario">
	<h5 class="titulos">&nbsp;</h5>
	
	
	
	
	
	</div>

</div>
</body>
</html>