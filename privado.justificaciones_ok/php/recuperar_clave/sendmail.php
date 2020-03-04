<?php
    session_start();
	//$htmlContent = file_get_contents("email_template.html");
	include($_SERVER['DOCUMENT_ROOT']."/utils/phpmailer/PHPMailerAutoload.php");
	$mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';

    $mail->SMTPDebug = 0;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;   
    $mail->Username = 'sge@ricaldone.edu.sv';                 // SMTP username
    $mail->Password = 'Allofme_22$07';                          // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    $mail->setFrom('luxuryandstylesv@gmail.com', 'Diario Pedagógico');
    $mail->addAddress($correo,'');     // Add a recipient
    $mail->Subject = 'Recuperación de contraseña';
    $mail->Body    = '
        <div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
            <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
            <tbody>
                <tr>
                    <td>
                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td width="30"></td>
                                                        <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                            <p style="font-family:HelveticaNeue-Light,arial,sans-serif;font-size:25px;color:#404040;line-height:25px;font-weight:bold;margin:0;padding:0">INSTITUTO TÉCNICO RICALDONE<p/>
                                                        </td>
                                                        <td width="30">
                                                            <img src="http://dp.ricaldone.edu.sv:8080/media/img/logo.png" style="width:90px; height:90px;margin-top:25px;">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </td>
                                             </tr>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <table width="630" align="center" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3" height="60"></td></tr><tr><td width="25"></td>
                                                        <td align="center">
                                                            <p style="font-family:HelveticaNeue-Light,arial,sans-serif;font-size:30px;color:#404040;line-height:30px;font-weight:bold;margin:0;padding:0">Hola, '.$nombre.'<p/>
                                                        </td>
                                                        <td width="25"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" height="40"></td></tr><tr><td colspan="5" align="center">
                                                            <p style="color:#404040;font-size:16px;line-height:24px;font-weight:lighter;padding:0;margin:0">Hemos recibido una solicitud para restablecer tu contraseña. Escribe el siguiente texto como contraseña en tu próximo inicio de sesión: </p><br>
                                                            <p style="color:#404040;font-size:22px;line-height:48px;font-weight:bold;padding:0;margin:0">
                                                              '.$pass.'
                                                            </p><br>
                                                            <p style="color:#404040;font-size:16px;line-height:24px;font-weight:lighter;padding:0;margin:0">Cuando puedas acceder a tu cuenta por favor digita una nueva contraseña.</p><br>
                                                            <p style="color:#404040;font-size:16px;line-height:24px;font-weight:bold;padding:0;margin:0">Recuerda guardarla y no compartirla con nadie.</p><br>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    </tbody>
                                    </table>
                                    <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                <tbody>
                                                    <tr><td colspan="2" height="30"></td></tr>
                                                    <tr>
                                                        <td width="360" valign="top">
                                                            <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0">&copy; 2017 Diario Pedagógico.</div>
                                                    </tr>
                                                    <tr><td colspan="2" height="5"></td></tr>
                                                   
                                                </tbody>
                                                </table>
                                            </td>
                                              </tr>
                                    </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    ';
    $mail->AltBody = 'Recuperación de contraseña';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );                            // Enable SMTP authentication
    if (!$mail->send()) {
        echo $mail->ErrorInfo;
    }else {
        echo "success";
        require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php"); 
        notificaciones::insertNotificacion($pk_personal,"RECUPERACIÓN DE CONTRASEÑA","Saludos, recientemente has solicitado restablecer tu contraseña, por favor asegúrate de cambiarla para que tu acceso sea más sencillo la próxima vez.");
    }
?>