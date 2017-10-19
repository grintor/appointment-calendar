<?php
/*
$mail->Username   = "smtptest.hi@gmail.com";  // GMAIL username
$mail->Password   = "scientech";            // GMAIL password
$mail->SetFrom('smtptest.hi@gmail.com', 'Easy Reminder');	//sender's mail
$mail->AddReplyTo("smtptest.hi@gmail.com","Easy Reminder");	// sender's mail
*/
require_once('class.phpmailer.php');
if(!class_exists("SendEmail")) {
    class SendEmail
    {


        // notification to admin when new appointment arrived
        public function notifyadmin($hostname, $portno, $smtpemail, $password, $admin_email, $subject_to_admin, $body_for_admin, $BlogName ) {
            $Subject = $subject_to_admin;
            $To = $admin_email;
            $Body = $body_for_admin;

            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = $hostname;      // SMTP server
            $mail->SMTPDebug  = 1;              // enables SMTP debug information (for testing)// 1 = errors and messages , // 2 = messages only
            $mail->SMTPAuth   = true;           // enable SMTP authentication
            $mail->SMTPSecure = "ssl";          // sets the prefix to the servier
            $mail->Host       = $hostname;      // sets GMAIL as the SMTP server
            $mail->Port       = $portno;        // set the SMTP port for the GMAIL server
            $mail->Username   = $smtpemail;     // GMAIL username
            $mail->Password   = $password;              // GMAIL password
            $mail->SetFrom($admin_email, $BlogName);    // admin mail
            $mail->AddReplyTo($admin_email, $BlogName); // admin mail
            //$mail->Subject    = $Subject;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = mb_convert_encoding($Subject, "UTF-8", "auto");
            $mail->MsgHTML($Body);
            $mail->AddAddress($admin_email);    // sending email to
            $mail->Send();
        }


        // notifiy to client after booked an appointment
        public function notifyclient($hostname, $portno, $smtpemail, $password, $admin_email, $recipent_email, $subject_to_recipent, $body_for_recipent, $BlogName) {
            $Subject = $subject_to_recipent;
            $To = $recipent_email;
            $Body = $body_for_recipent;

            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = $hostname;          // SMTP server
            $mail->SMTPDebug  = 1;                  // enables SMTP debug information (for testing)// 1 = errors and messages , // 2 = messages only
            $mail->SMTPAuth   = true;               // enable SMTP authentication
            $mail->SMTPSecure = "ssl";              // sets the prefix to the servier
            $mail->Host       = $hostname;          // sets GMAIL as the SMTP server
            $mail->Port       = $portno;            // set the SMTP port for the GMAIL server
            $mail->Username   = $smtpemail;         // GMAIL username
            $mail->Password   = $password;          // GMAIL password
            $mail->SetFrom($admin_email, $BlogName);    //admin mail
            $mail->AddReplyTo($admin_email, $BlogName); // admin mail
            //$mail->Subject    = $Subject;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = mb_convert_encoding($Subject, "UTF-8", "auto");
            $mail->MsgHTML($Body);
            $mail->AddAddress($To);                     //client email here
            $mail->Send();
        }
    }
}