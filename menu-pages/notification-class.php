<?php
 /**
  * Class: Notification
  * Version: 1.0
  * Author: FRANK FARAZ
  * Pakage: Appointment Calendar Premium 3.2
  * Description: This class send notification
  * massage to admin and client when new appointment
  * booked. And if any appointment approved/cancelled
  * by admin then notify the client.
  **/
if(!class_exists("Notification")) {
    class Notification
    {
         //notify admin
         function notifyadmin($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat) {
            global $wpdb;
            //appointment details
            $AppointmentTable = $wpdb->prefix."ap_appointments";
            $AppointmentData = $wpdb->get_row("SELECT * FROM `$AppointmentTable` WHERE `id` = '$AppId'", OBJECT);

            //service details
            $ServiceidArray = explode(",", $ServiceId);
            $ServiceTableName = $wpdb->prefix . "ap_services";
            foreach($ServiceidArray as $SerId) {
                $ServiceDetails = $wpdb->get_row("SELECT `name` FROM `$ServiceTableName` WHERE `id` = '$SerId' ", OBJECT);
                $ServiceData[] = $ServiceDetails->name;
            }
            $AllServices = implode(", ", $ServiceData);

            //staff details
            $StaffTable = $wpdb->prefix."ap_staff";
            $StaffData = $wpdb->get_row("SELECT `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);

            //staff details
            $ClientTable = $wpdb->prefix."ap_clients";
            $ClientData = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `id` = '$ClientId'", OBJECT);

            if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";

            $AppDate = date($DateFormat, strtotime($AppointmentData->date));
            $AppTime = date($TimeFormat, strtotime($AppointmentData->start_time))." - ".date($TimeFormat, strtotime($AppointmentData->end_time));

            update_option("status_in_your_language",__(ucwords($AppointmentData->status), 'appointzilla'));
            $StatusLang = get_option("status_in_your_language");
            if($StatusLang) $AppointmentData->status = $StatusLang;

             //admin subject
             if($Status == "pending") $admin_subject = get_option('booking_admin_subject');
             if($Status == "approved") $admin_subject = get_option('booking_admin_subject');
             if($Status == "cancelled") $admin_subject = get_option('cancel_admin_subject');

            $admin_subject = str_replace("[blog-name]", ucwords($BlogName), $admin_subject);
            $admin_subject = str_replace("[client-name]", ucwords($ClientData->name), $admin_subject);
            $admin_subject = str_replace("[client-email]", ucwords($ClientData->email), $admin_subject);
            $admin_subject = str_replace("[client-phone]", ucwords($ClientData->phone), $admin_subject);
            $admin_subject = str_replace("[client-si]", ucfirst($AppointmentData->note), $admin_subject);
            $admin_subject = str_replace("[service-name]", ucwords($AllServices), $admin_subject);
            $admin_subject = str_replace("[staff-name]", ucwords($StaffData->name), $admin_subject);
            $admin_subject = str_replace("[app-date]", $AppDate, $admin_subject);
            $admin_subject = str_replace("[app-status]", ucwords($AppointmentData->status), $admin_subject);
            $admin_subject = str_replace("[app-time]", $AppTime, $admin_subject);
            $admin_subject = str_replace("[app-key]", $AppointmentData->appointment_key, $admin_subject);

            //admin body
            if($Status == "pending") $admin_body = get_option('booking_admin_body');
            if($Status == "approved") $admin_body = get_option('booking_admin_body');
            if($Status == "cancelled") $admin_body = get_option('cancel_admin_body');

            $admin_body = str_replace("[blog-name]", ucwords($BlogName), $admin_body);
            $admin_body = str_replace("[client-name]", ucwords($ClientData->name), $admin_body);
            $admin_body = str_replace("[client-email]", ucwords($ClientData->email), $admin_body);
            $admin_body = str_replace("[client-phone]", ucwords($ClientData->phone), $admin_body);
            $admin_body = str_replace("[client-si]", ucfirst($AppointmentData->note), $admin_body);
            $admin_body = str_replace("[service-name]", ucwords($AllServices), $admin_body);
            $admin_body = str_replace("[staff-name]", ucwords($StaffData->name), $admin_body);
            $admin_body = str_replace("[app-date]", $AppDate, $admin_body);
            $admin_body = str_replace("[app-status]", ucwords($AppointmentData->status), $admin_body);
            $admin_body = str_replace("[app-time]", $AppTime, $admin_body);
            $admin_body = str_replace("[app-key]", $AppointmentData->appointment_key, $admin_body);
            $this->sendnotification('admin', $admin_subject, $admin_body, $ClientData->email);
         }


         //notify client
         function notifyclient($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat) {
            global $wpdb;
            //appointment details
            $AppointmentTable = $wpdb->prefix."ap_appointments";
            $AppointmentData = $wpdb->get_row("SELECT * FROM `$AppointmentTable` WHERE `id` = '$AppId'", OBJECT);

            //service details
            $ServiceidArray = explode(",", $ServiceId);
            $ServiceTableName = $wpdb->prefix . "ap_services";
            foreach($ServiceidArray as $SerId) {
                $ServiceDetails = $wpdb->get_row("SELECT `name` FROM `$ServiceTableName` WHERE `id` = '$SerId' ", OBJECT);
                $ServiceData[] = $ServiceDetails->name;
            }
            $AllServices = implode(", ", $ServiceData);

            //staff details
            $StaffTable = $wpdb->prefix."ap_staff";
            $StaffData = $wpdb->get_row("SELECT `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);

            //staff details
            $ClientTable = $wpdb->prefix."ap_clients";
            $ClientData = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `id` = '$ClientId'", OBJECT);

             if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";

            $AppDate = date($DateFormat, strtotime($AppointmentData->date));
            $AppTime = date($TimeFormat, strtotime($AppointmentData->start_time))." - ".date($TimeFormat, strtotime($AppointmentData->end_time));

            update_option("status_in_your_language",__(ucwords($AppointmentData->status), 'appointzilla'));
            $StatusLang = get_option("status_in_your_language");
            if($StatusLang) $AppointmentData->status = $StatusLang;

            //client subject
            if($Status == "pending") $client_subject = get_option('booking_client_subject');
            if($Status == 'approved') $client_subject = get_option('approve_client_subject');
            if($Status == 'cancelled') $client_subject = get_option('cancel_client_subject');

            $client_subject = str_replace("[blog-name]", ucwords($BlogName), $client_subject);
            $client_subject = str_replace("[client-name]", ucwords($ClientData->name), $client_subject);
            $client_subject = str_replace("[client-email]", ucwords($ClientData->email), $client_subject);
            $client_subject = str_replace("[client-phone]", ucwords($ClientData->phone), $client_subject);
            $client_subject = str_replace("[client-si]", ucfirst($AppointmentData->note), $client_subject);
            $client_subject = str_replace("[service-name]", ucwords($AllServices), $client_subject);
            $client_subject = str_replace("[staff-name]", ucwords($StaffData->name), $client_subject);
            $client_subject = str_replace("[app-date]", $AppDate, $client_subject);
            $client_subject = str_replace("[app-status]", ucwords($AppointmentData->status), $client_subject);
            $client_subject = str_replace("[app-time]", $AppTime, $client_subject);
            $client_subject = str_replace("[app-key]", $AppointmentData->appointment_key, $client_subject);

            //client body
            if($Status == 'pending') $client_body = get_option('booking_client_body');
            if($Status == 'approved') $client_body = get_option('approve_client_body');
            if($Status == 'cancelled') $client_body = get_option('cancel_client_body');

            $client_body = str_replace("[blog-name]", ucwords($BlogName), $client_body);
            $client_body = str_replace("[client-name]", ucwords($ClientData->name), $client_body);
            $client_body = str_replace("[client-email]", ucwords($ClientData->email), $client_body);
            $client_body = str_replace("[client-phone]", ucwords($ClientData->phone), $client_body);
            $client_body = str_replace("[client-si]", ucfirst($AppointmentData->note), $client_body);
            $client_body = str_replace("[service-name]", ucwords($AllServices), $client_body);
            $client_body = str_replace("[staff-name]", ucwords($StaffData->name), $client_body);
            $client_body = str_replace("[app-date]", $AppDate, $client_body);
            $client_body = str_replace("[app-status]", ucwords($AppointmentData->status), $client_body);
            $client_body = str_replace("[app-time]", $AppTime, $client_body);
            $client_body = str_replace("[app-key]", $AppointmentData->appointment_key, $client_body);

            $this->sendnotification('client', $client_subject, $client_body, $ClientData->email);
         }


         //notify staff
         function notifystaff($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat) {
            global $wpdb;
            //appointment details
            $AppointmentTable = $wpdb->prefix."ap_appointments";
            $AppointmentData = $wpdb->get_row("SELECT * FROM `$AppointmentTable` WHERE `id` = '$AppId'", OBJECT);
            //service details
            $ServiceidArray = explode(",", $ServiceId);
            $ServiceTableName = $wpdb->prefix . "ap_services";
            foreach($ServiceidArray as $SerId) {
                $ServiceDetails = $wpdb->get_row("SELECT `name` FROM `$ServiceTableName` WHERE `id` = '$SerId' ");
                $ServiceData[] = $ServiceDetails->name;
            }
            $AllServices = implode(", ", $ServiceData);

            //staff details
            $StaffTable = $wpdb->prefix."ap_staff";
            $StaffData = $wpdb->get_row("SELECT `name`, `email` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);

            //staff details
            $ClientTable = $wpdb->prefix."ap_clients";
            $ClientData = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `id` = '$ClientId'", OBJECT);

             if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";

            $AppDate = date($DateFormat, strtotime($AppointmentData->date));;
            $AppTime = date($TimeFormat, strtotime($AppointmentData->start_time))." - ".date($TimeFormat, strtotime($AppointmentData->end_time));

            update_option("status_in_your_language",__(ucwords($AppointmentData->status), 'appointzilla'));
            $StatusLang = get_option("status_in_your_language");
            if($StatusLang) $AppointmentData->status = $StatusLang;

            if($Status == "pending") $staff_subject = get_option('booking_staff_subject');
            if($Status == 'approved') $staff_subject = get_option('approve_staff_subject');
            if($Status == 'cancelled') $staff_subject = get_option('cancel_staff_subject');

            $staff_subject = str_replace("[blog-name]", ucwords($BlogName), $staff_subject);
            $staff_subject = str_replace("[client-name]", ucwords($ClientData->name), $staff_subject);
            $staff_subject = str_replace("[client-email]", ucwords($ClientData->email), $staff_subject);
            $staff_subject = str_replace("[client-phone]", ucwords($ClientData->phone), $staff_subject);
            $staff_subject = str_replace("[client-si]", ucwords($AppointmentData->note), $staff_subject);
            $staff_subject = str_replace("[service-name]", ucwords($AllServices), $staff_subject);
            $staff_subject = str_replace("[staff-name]", ucwords($StaffData->name), $staff_subject);
            $staff_subject = str_replace("[app-date]", $AppDate, $staff_subject);
            $staff_subject = str_replace("[app-status]", ucwords($AppointmentData->status), $staff_subject);
            $staff_subject = str_replace("[app-time]", $AppTime, $staff_subject);
            $staff_subject = str_replace("[app-key]", $AppointmentData->appointment_key, $staff_subject);
            $staff_subject = str_replace("[app-note]", ucfirst($AppointmentData->note), $staff_subject);

            //$staff_subject = utf8($staff_subject);

            if($Status == "pending") $staff_body = get_option('booking_staff_body');
            if($Status == 'approved') $staff_body = get_option('approve_staff_body');
            if($Status == 'cancelled') $staff_body = get_option('cancel_staff_body');

            $staff_body = str_replace("[blog-name]", ucwords($BlogName), $staff_body);
            $staff_body = str_replace("[client-name]", ucwords($ClientData->name), $staff_body);
            $staff_body = str_replace("[client-email]", ucwords($ClientData->email), $staff_body);
            $staff_body = str_replace("[client-phone]", ucwords($ClientData->phone), $staff_body);
            $staff_body = str_replace("[client-si]", ucwords($AppointmentData->note), $staff_body);
            $staff_body = str_replace("[service-name]", ucwords($AllServices), $staff_body);
            $staff_body = str_replace("[staff-name]", ucwords($StaffData->name), $staff_body);
            $staff_body = str_replace("[app-date]", $AppDate, $staff_body);
            $staff_body = str_replace("[app-status]", ucwords($AppointmentData->status), $staff_body);
            $staff_body = str_replace("[app-time]", $AppTime, $staff_body);
            $staff_body = str_replace("[app-key]", $AppointmentData->appointment_key, $staff_body);
            $staff_body = str_replace("[app-note]", ucfirst($AppointmentData->note), $staff_body);
            $this->sendnotification('staff', $staff_subject, $staff_body, $StaffData->email);
         }


         //send notification
         function sendnotification($to, $subject, $body, $recipent_email) {
            $BlogName =  get_bloginfo('name');
            //check email notification ON/OFF
            if(get_option('emailstatus') == 'on') {
                // get notification details
                $NotificationDetails = get_option('emaildetails');
                //wpemail
                if(get_option('emailtype') == 'wpmail') {
                    $admin_email = $NotificationDetails['wpemail'];
                    $headers[] = "From: $BlogName <$admin_email>";
                    //send admin mail
                    if($to == 'admin') {
                        wp_mail( $admin_email, $subject, $body, $headers, $attachments = '' );
                    }
                    //send client mail
                    if($to == 'client') {
                        wp_mail( $recipent_email, $subject, $body, $headers, $attachments = '' );
                    }
                    //send client mail
                    if($to == 'staff') {
                        wp_mail( $recipent_email, $subject, $body, $headers, $attachments = '' );
                    }

                }// end of wp mail

                //php mail
                if(get_option('emailtype') == 'phpmail') {
                    $admin_email = $NotificationDetails['phpemail'];
                    $headers = "From: $BlogName <$admin_email>";
                    //send admin mail
                    if($to == 'admin') {
                        mail($admin_email, $subject, $body, $headers);
                    }
                    //send client mail
                    if($to == 'client') {
                        mail($recipent_email, $subject, $body, $headers);
                    }
                    //send client mail
                    if($to == 'staff') {
                        mail($recipent_email, $subject, $body, $headers);
                    }
                }// end of php mail

                //smtp mail
                if(get_option('emailtype') == 'smtp') {
                    $admin_email    = $NotificationDetails['smtpemail'];
                    $hostname       = $NotificationDetails['hostname'];
                    $portno         = $NotificationDetails['portno'];
                    $smtpemail      = $NotificationDetails['smtpemail'];
                    $password       = $NotificationDetails['password'];
                    $recipent_email = $recipent_email; //'farazfrank777@gmail.com';
                    include_once('notification/SendEmail.php');
                    $SendEmail = new SendEmail();
                    //send mail to admin
                    if($to == 'admin') {
                        $body = "<pre>$body</pre>";
                        $SendEmail->notifyadmin($hostname, $portno, $smtpemail, $password, $admin_email, $subject, $body, $BlogName);
                    }
                    //send mail to client
                    if($to == 'client') {
                        $body = "<pre>$body</pre>";
                        $SendEmail->notifyclient($hostname, $portno, $smtpemail, $password, $admin_email, $recipent_email, $subject, $body, $BlogName);
                    }
                    if($to == 'staff') {
                        $body = "<pre>$body</pre>";
                        $SendEmail->notifyclient($hostname, $portno, $smtpemail, $password, $admin_email, $recipent_email, $subject, $body, $BlogName);
                    }
                }// end of smtp mail
            }// end of email enable check
         }// end of send notification
    }// end of class
}