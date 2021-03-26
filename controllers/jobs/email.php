<?php

use PHPMailer\PHPMailer\PHPMailer;

function enviarEmail($emailDest, $nomeDest, $assuntoEmail, $corpoEmail, $tipo){
    include_once '../../configs/database.php';

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    include '../../configs/emailAcount.php';
    require '../../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();                                          
        $mail->Host       = $smtp;                     
        $mail->SMTPAuth   = true;   
        $mail->SMTPSecure = "tls";                     
        $mail->Username   = $email;                    
        $mail->Password   = $senha;                    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $porta;   
        $mail->CharSet = "UTF-8";
        //Recipients
        $mail->setFrom($email, 'Cotação APP');
        $mail->addAddress($emailDest, $nomeDest);

        /* 
        Anexos
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        */

        $mail->isHTML(true);                                  
        $mail->Subject = $assuntoEmail;
        $mail->Body    = $corpoEmail;
        $mail->AltBody = $corpoEmail;

        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); possibilidade de encaminhar um arquivo HTML

        $mail->send();
        $sql = "INSERT INTO log_email(tipo, destinatario, mensagem, data) values('$tipo', '$emailDest', 'Sucesso', now())";
        $query = $conexao->prepare($sql);
        $query->execute();
    } catch (Exception $e) {
        $sql = "INSERT INTO log_email(tipo, destinatario, mensagem, data) values('$tipo', '$emailDest', '$mail->ErrorInfo' , now())";
        $query = $conexao->prepare($sql);
        $query->execute();
    }
}
?>