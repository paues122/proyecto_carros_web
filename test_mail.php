<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

$mail = new PHPMailer();

$mail->isSMTP();

$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->Host = 'smtp.gmail.com';

$mail->Port = 465;

$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;


$mail->SMTPAuth = true;
$mail->Username = '21030846@itcelaya.edu.mx';

$mail->Password = 'kpxc bscy njsj bkbk';
$mail->setFrom('21030846@itcelaya.edu.mx', 'Paulina Escalante (Remitente)');

$mail->addAddress('21030846@itcelaya.edu.mx', 'Paulina Escalante (Destinatario)');

$mail->Subject = 'Prueba de PHPMailer desde PHP';

$mail->msgHTML('Este es un mensaje de prueba enviado con PHPMailer.');

$mail->AltBody = 'Este es un mensaje de prueba en texto plano.';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent! (Mensaje enviado)';

}

function save_mail($mail)
{
    $path = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
    $imapStream = imap_open($path, $mail->Username, $mail->Password);
    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);
    return $result;
}