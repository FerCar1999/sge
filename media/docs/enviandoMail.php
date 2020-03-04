<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

//Load Composer's autoloader
require 'vendor/autoload.php';


function enviandoCorreoCuenta($destinatario, $nombreDestinatario, $codigo)
{
	$mail = new PHPMailer(true); // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 1; // Enable verbose debug output
		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->Username = 'soporte_it@ricaldone.edu.sv'; // SMTP username
		$mail->Password = 'Allofme_22$07'; // SMTP password
		$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;
		$mail->CharSet = 'UTF-8';
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			),
		); // TCP port to connect to

		//Recipients
		$mail->setFrom('soporte_it@ricaldone.edu.sv', 'Soporte IT Ricaldone');
		$mail->addAddress($destinatario, $nombreDestinatario); // Add a recipient
		//Content
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = 'Seguro de Accidentes Personales ASESUISA';
		$mail->Body = '<center><h1>Seguro de Accidentes Personales</h1><center>
		</br>
		<center><p>Saludos ' . $nombreDestinatario . ', por medio del presente correo se le hace entrega de su Seguro de Accidentes Personales.</p><center>';
		$mail->AltBody = 'Saludos ' . $nombreDestinatario . ', por medio del presente correo se le hace entrega de su Seguro De Accidentes Personales.';
		$mail->addAttachment($codigo.'.PDF', "Seguro de Accidentes ASESUISA ".$nombreDestinatario);
		if ($mail->send()) {
			return true;
		} else {
			return false;
		}
	} catch (Exception $e) {
		return false;
	}
}
