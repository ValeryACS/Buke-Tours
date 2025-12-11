<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Funcion usada para enviar emails via SMTP
 * @see https://www.php.net/manual/en/function.fsockopen.php
 * @see https://stackoverflow.com/questions/4097529/sending-mail-via-fsockopen
 * @param $emailReciever - Email del que recibe
 * @param $subject - El asunto del email 
 * @param $body - Cuerpo del mensaje
 */
function sendEmail($emailReciever, $subject, $body)
{
    $smtp_server = "";//SMTP servidor
    $smtp_port   = 587;//SMTP puerto
    $username    = "";// Correo del emisor
    $password    = "";// Password del correo del emisor

    
    $socket = fsockopen($smtp_server, $smtp_port, $errno, $errstr, 10);
    if (!$socket) {
        return "Error SMTP, el mensaje no pudo ser enviado: $errstr ($errno)";
    }
    
    fgets($socket);
    fputs($socket, "EHLO localhost\r\n");
    fgets($socket);
    fputs($socket, "STARTTLS\r\n");
    fgets($socket);
    stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
    fputs($socket, "EHLO localhost\r\n");
    fgets($socket);
    fputs($socket, "AUTH LOGIN\r\n");
    fgets($socket);
    fputs($socket, base64_encode($username) . "\r\n");
    fgets($socket);
    fputs($socket, base64_encode($password) . "\r\n");
    fgets($socket);
    fputs($socket, "MAIL FROM: <$username>\r\n");
    fgets($socket);
    fputs($socket, "RCPT TO: <$emailReciever>\r\n");
    fgets($socket);
    fputs($socket, "DATA\r\n");
    fgets($socket);
    $msg =
        "Subject: $subject\r\n" .
        "From: $username\r\n" .
        "To: $emailReciever\r\n" .
        "MIME-Version: 1.0\r\n" .
        "Content-Type: text/html; charset=UTF-8\r\n\r\n" .
        $body . "\r\n.\r\n";

    fputs($socket, $msg);
    fgets($socket);
    fputs($socket, "QUIT\r\n");
    fclose($socket);

    return true;
}

