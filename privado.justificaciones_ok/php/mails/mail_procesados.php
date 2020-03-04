<?php
	//$htmlContent = file_get_contents("email_template.html");
	require_once($_SERVER['DOCUMENT_ROOT']."/utils/phpmailer/PHPMailerAutoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/libs/database.php");
    
    $data = Database::getRow("SELECT id_personal FROM estudiantes WHERE codigo=?", array($pk_alumno));
    $id_guia = $data[0];
    $dataC = Database::getRow("SELECT correo FROM personal WHERE id_personal=?", array($id_guia));

    $correo = $dataC[0];

    if (!isset($correo))
        return;

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
    $mail->Subject = 'CONTROL DISCIPLINARIO';
    $html = '
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
                                                            <p style="font-family:HelveticaNeue-Light,arial,sans-serif;font-size:25px;color:#404040;line-height:25px;font-weight:bold;margin:0;padding:0">ALERTA DE ESTUDIANTES POR PROCESAR<p/>
                                                        </td>
                                                        <td width="25"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" height="40"></td></tr><tr><td colspan="5" align="justify">
                                                            <p style="color:#404040;font-size:16px;line-height:24px;font-weight:lighter;padding:0;margin:0">Estimado docente guía, se le notifica que los siguientes alumnos han acumulado varios códigos diciplinarios negativos, por lo que se necesita sean procesados.</p><br>
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
                                                    <table style="width: 650px;">
                                                        <tbody>
                                                            <tr align="center">
                                                                <td style="height:47px;"><p style="font-weight:bold;font-size:22px;line-height:22px;color:#404040;margin-left:100px;">Estudiantes</p></td>
                                                            </tr>
                                                            <tr><td style="width: 476px;height:47px;">';
                                                            $sql = "SELECT CONCAT(nombres,' ',apellidos) AS nombre FROM estudiantes WHERE procesado=? AND id_personal=? ORDER BY apellidos";
                                                            $params = array(1,$id_guia);
                                                            $data = Database::getRows($sql, $params);
                                                            foreach($data as $row)
                                                            {
                                                                $html.='
                                                                    <p style="font-weight:lighter;font-size:18px;color:#404040;margin-left:57px!important;line-height:25px;">'.$row["nombre"].'</p>';
                                                            }
                                                            
                                                            $html.='</td></tr>
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
                                    <br>
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
    $mail->Body   = $html;
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
        //echo "success";
        require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php"); 
        notificaciones::insertNotificacion($id_guia,"ESTUDIANTES POR PROCESAR","Saludos, recibe esta notificación debido a que uno de sus estudiantes ha acumulado demasiadas faltas disciplinarias, por lo que debe iniciar el proceso de suspención.");
    }
?>