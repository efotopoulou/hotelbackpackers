<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_sugerencia.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

include("class.phpmailer.php");

$texto = $_POST['texto'];
$texto = str_replace("\n","<br />", $texto);
$mensaje = new MensajeJSON();

try {
  $sug=new class_sugerencia();
  $sug->setTexto($texto);
  try{
  enviarPorMailSugerencia($texto);
  }catch (Exception $e){
  	$mensaje->setMensaje("No se ha podido enviar el mail con el texto");
  }
  
// NO devolvemos nada, porque solo guardamos la sugerencia en la BBDD.
//  $mensaje->setDatos($platosInfo);
}catch (SQLException $e){
//TODO: Ya que no se puede guardar en la BBDD, guardar el error en un fichero. /log/error.log. 
// Hacerlo para todos los ficheros jsonXXXXXXXXX.php
$mensaje->setError("Error de la BBDD");
}
echo($mensaje->encode());
?>
<?php
// send email with $attachments backup files to $email email using $sitename for sender and subject
function enviarPorMailSugerencia($texto) {
    global $CONF;
    $out=FALSE;

    $mail             = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "hotelbackpackers@gmail.com";  // GMAIL username
    $mail->Password   = "casaguatemala";            // GMAIL password
    $mail->From       = "hotelbackpackers@gmail.com";
    $mail->FromName   = "First Last";
    $mail->IsHTML(true); // send as HTML
    $mail->AddAddress("willezumleben@gmail.com", "Isaac Muro");
    
 	$mailtext="Este mail sirve para recibir las sugerencias del hotel Backpackers.\n".$texto;   

	$subject="Sugerencias del Backpackers!";

    $mail->Subject = $subject;
    $mail->MsgHTML($mailtext);


    // send mail
    if($mail->Send()) {
 	 $out.="<div class=\"green\">Mail enviado</div>\n";
	} else {
	 $out.="<div class=\"red\">Problema enviando el mail!</div>\n";
	}
    
    return $out;
}

?>
