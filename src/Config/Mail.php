<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    //    this error----------------------------------------------------------------------------------------------------------------|
    //    Mailer Error: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting                              |
    //    because less secure app permission required on google --------------------------------------------------------------------|

    public $from = "dummy@gmail.com";
    public $frompass = "dummy";

    function sendMail($to, $subject, $body, $cc = "")
    {


        $mail = new PHPMailer;

        $mail->isSMTP();                            // Set mailer to use SMTP
        // $mail->SMTPDebug = 0;
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.hostinger.com';

        // $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = $this->from;              // SMTP username
        $mail->Password = $this->frompass;            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom($this->from, 'Phoneraksha Admin');

        $mail->addAddress($to);

        if ($cc == "" || $cc == NULL || $cc == " ") {
        } else {
            $ccarr = explode(",", $cc);
            for ($i = 0; $i < count($ccarr); $i++) {
                $mail->addCC($ccarr[$i]);
            }
        }

        //        $mail->addBCC('runwalforestofficial@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            //            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return "1";
        }
    }
}
