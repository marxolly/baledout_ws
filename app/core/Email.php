<?php

 /**
  * Email Class
  *
  * Sending emails via SMTP.
  * It uses PHPMailer library to send emails.
  *
  
  * @author     Mark Solly <mark@baledout.com.au>
  */

 class Email{

     /**
      * This is the constructor for Email object.
      *
      * @access private
      */
    private function __construct(){}


    public static function sendNewUserEmail($name, $email)
    {
        $mail = new PHPMailer();
        $body = file_get_contents(Config::get('EMAIL_TEMPLATES_PATH')."new_user.html");
        $replace_array = array("{NAME}");
		$replace_with_array = array($name);
        $body = str_replace($replace_array, $replace_with_array, $body);
        $mail->AddEmbeddedImage(IMAGES."email_logo.png", "emailfoot", "email_logo.png");
		$mail->SetFrom(Config::get('EMAIL_FROM'), Config::get('EMAIL_FROM_NAME'));
		$mail->Subject = "Access Instructions For 3PLPlus WMS";
		$mail->MsgHTML($body);
        $mail->addAttachment(Config::get('EMAIL_ATTACHMENTS_PATH')."Portal Instructions.docx", 'portal_instructions.docx');
        $mail->AddAddress($email, $name);
        $mail->AddBCC('mark@baledout.com.au', 'Mark Solly');
        if(!$mail->Send())
        {
            Logger::log("Mail Error", print_r($mail->ErrorInfo, true), __FILE__, __LINE__);
            //throw new Exception("Email couldn't be sent ");
        }
    }

 }

