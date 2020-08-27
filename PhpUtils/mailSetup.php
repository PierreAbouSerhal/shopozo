<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/PhpMailer/src/Exception.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/PhpMailer/src/PHPMailer.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/PhpMailer/src/SMTP.php");

if(!class_exists("JMOMailer"))
{
    class JMOMailer 
    {
        public $mail;

        public function __construct($exceptions = false, $smtp = array())
        {
            //NEW PHPMailer instance
            $this->mail = new PHPMailer($exceptions);

            //SMTP setup
            if(!empty($smtp))
            {
                $this->mail->SMTPDebug = $smtp["debug"];
                $this->mail->isSMTP() ;
                $this->mail->Host = $smtp["host"];
                $this->mail->SMTPAuth = $smtp["auth"];
                $this->mail->Username = $smtp["username"];
                $this->mail->Password = $smtp["password"];
                $this->mail->SMTPSecure = $smtp["secure"];
                $this->mail->Port = $smtp["port"];
            }
        }

        public function mail($to = array(), $subject, $html, $from = array(), $plainText = false, $cc = array(), $bcc = array(), $attachments = array())
        {
            if(empty($to) || empty($from) || empty($subject) || empty($html))
            {
                die("Missing a parameter");
            }

            //SENDER
            $this->mail->setFrom($from["email"], $from["name"]);
            $this->mail->addReplyTo($from["email"], $from["name"]);

            //RECIPIENTS
            if(!empty($to))
            {
                foreach($to as $recipient)
                {
                    $this->mail->addAddress($recipient["email"], $recipient["name"]);
                }
            }

            //CC
            if(!empty($cc))
            {
                foreach($cc as $recipient)
                {
                    $this->mail->addCC($recipient);
                }
            }

            //BCC
            if(!empty($bcc))
            {
                foreach($bcc as $recipient)
                {
                    $this->mail->addBCC($recipient);
                }
            }

            //ATTACHMENTS
            if(!empty($attachments))
            {
                foreach($attachments as $recipient)
                {
                    $this->mail->addAttachment($recipient);
                }
            }

            //HTML email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $html;

            //PLAIN TEXT VERSION
            if(false !== $plainText)
            {
                $this->mail->AltBody = $plainText;
            }

            //SEND THE MAIL
            try
            {
                $this->mail->send();
                return true;
            }
            catch(Exception $e)
            {
                return false;
            }
        }
    }
}

?>