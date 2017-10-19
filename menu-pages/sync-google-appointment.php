<?php // get appointment list from Google-calendar and save in ap_appointments table
$CalData = get_option('google_caelndar_settings_details');
if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
    $ClientEmail = $CalData['google_calendar_client_email'];
    $ClientId = $CalData['google_calendar_client_id'];
    $ClientSecretId = $CalData['google_calendar_secret_key'];
    $RedirectUri = $CalData['google_calendar_redirect_uri'];

    require_once('google-appointment-sync-class.php');
    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
    $EventList = $GoogleAppointmentSync->GetAppointmentList($ClientEmail);
    if(isset($EventList['items'])) {
        foreach($EventList['items'] as $EventList) {
            //check Appointment OR TimeOff
            //if summary contain 'Appointment with:' OR 'TimeOff:' then skip
            $AppointmentMatch = stristr($EventList['summary'], 'Appointment with:');
            $TimeoffMatch = stristr($EventList['summary'], 'TimeOff:');
            if(!$AppointmentMatch && !$TimeoffMatch) {
                if(isset($EventList['attendees'][0]['email'])) {
                    $AttendeesEmail = $EventList['attendees'][0]['email'];
                } else {
                    $AttendeesEmail = "no@email.com";
                }
                //attendee name
                if(isset($EventList['attendees'][0]['displayName'])) {
                    $AttendeesName = $EventList['attendees'][0]['displayName'];
                } else {
                    $AttendeesName = $EventList['summary'];
                }

                $Client_name = $AttendeesName;
                $Client_email = $AttendeesEmail;
                if(isset($EventList['description'])) {
                    $Client_Note = $EventList['description'];
                } else {
                    $Client_Note = "";
                }
                $ServiceId = 1;
                $StaffId = 1;
                $Client_Phone = '0000000000';
                $CreatorEmail = $EventList['creator']['email'];

                // Recurring + All-day Appointments
                if(isset($EventList['start']['date'])) {
                    $StartTime = "12:00 AM";
                    $EndTime = "11:59 PM";
                    //single all-day day appointment
                    if(!isset($EventList['recurrence'][0])) {
                        //its a single all-day appointment
                        $AppDate =  $EventList['start']['date'];
                        $RecurringStartDate = $AppDate;
                        $RecurringEndDate = $AppDate;
                        $Recurring = 'no';
                        $RecurringType = 'none';
                    } else {
                        //all-day day recurring appointment
                        $EqualFirstPos = strpos($EventList['recurrence'][0], "=");
                        $SemiColnFirstPos = strpos($EventList['recurrence'][0], ";");
                        $RecurringType = strtolower(substr($EventList['recurrence'][0], $EqualFirstPos+1, ($SemiColnFirstPos - $EqualFirstPos)-1));
                        //check which type of all-day recurrence
                        //daily
                        if($RecurringType == 'daily') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatDays = substr($EventList['recurrence'][0], $EqualLastPos+1) - 1;

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatDays days", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $EqualLastPos+1);

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }

                        //weekly
                        if($RecurringType == 'weekly') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $TEqualFirstPos = strrpos($EventList['recurrence'][0], "T=");
                                $SemiColLastPos = strrpos($EventList['recurrence'][0], ";");
                                $RepeatWeeks = substr($EventList['recurrence'][0], $TEqualFirstPos+2, ($SemiColLastPos - $TEqualFirstPos)-2) - 1;

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatWeeks week", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $LEqualFirstPos = strrpos($EventList['recurrence'][0], "L=");
                                $SemiColLastPos = strrpos($EventList['recurrence'][0], ";");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $LEqualFirstPos + 2, ($SemiColLastPos - $LEqualFirstPos)-2);

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }

                        //monthly
                        if($RecurringType == 'monthly') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "T=");
                                $RepeatMonths = substr($EventList['recurrence'][0], $EqualLastPos+2)-1;

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatMonths months", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $EqualFirstPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $EqualFirstPos + 1);

                                $AppDate =  $EventList['start']['date'];
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }
                    }//end of all-day recurring else
                } else {
                    // Only Recurring Appointment
                    $StartTime = substr($EventList['start']['dateTime'], 11, 8);
                    $EndTime = substr($EventList['end']['dateTime'], 11, 8);
                    $StartTime = date("h:i A", strtotime($StartTime));
                    $EndTime = date("h:i A", strtotime($EndTime));
                    //single day appointment
                    if(!$EventList['recurrence'][0]) {
                        //its a single all-day appointment
                        $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                        $RecurringStartDate = $AppDate;
                        $RecurringEndDate = $AppDate;
                        $Recurring = 'no';
                        $RecurringType = 'none';
                    } else {
                        //check which type of all-day recurrence
                        $EqualFirstPos = strpos($EventList['recurrence'][0], "=");
                        $SemiColnFirstPos = strpos($EventList['recurrence'][0], ";");
                        $RecurringType = strtolower(substr($EventList['recurrence'][0], $EqualFirstPos+1, ($SemiColnFirstPos - $EqualFirstPos)-1));
                        //check which type of recurrence
                        //daily
                        if($RecurringType == 'daily') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatDays = substr($EventList['recurrence'][0], $EqualLastPos+1) - 1;

                                $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatDays days", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $EqualLastPos+1);

                                $AppDate = date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }// end of if daily

                        //weekly
                        if($RecurringType == 'weekly') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $TEqualFirstPos = strrpos($EventList['recurrence'][0], "T=");
                                $SemiColLastPos = strrpos($EventList['recurrence'][0], ";");
                                $RepeatWeeks = substr($EventList['recurrence'][0], $TEqualFirstPos+2, ($SemiColLastPos - $TEqualFirstPos)-2) - 1;

                                $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatWeeks week", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $LEqualFirstPos = strrpos($EventList['recurrence'][0], "L=");
                                $SemiColLastPos = strrpos($EventList['recurrence'][0], ";");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $LEqualFirstPos + 2, ($SemiColLastPos - $LEqualFirstPos)-2);

                                $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }// end of if weekly

                        //monthly
                        if($RecurringType == 'monthly') {
                            $Recurring = 'yes';
                            //now check recurring in count or in unit
                            if(strstr($EventList['recurrence'][0], 'COUNT=')) {
                                //the recurring in the form of number of repeat days like 2,3,5,10
                                $EqualLastPos = strrpos($EventList['recurrence'][0], "T=");
                                $RepeatMonths = substr($EventList['recurrence'][0], $EqualLastPos+2)-1;

                                $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatMonths months", strtotime($RecurringStartDate)));
                            }

                            if(strstr($EventList['recurrence'][0], 'UNTIL=')) {
                                //the recurring in the form of end date
                                $EqualFirstPos = strrpos($EventList['recurrence'][0], "=");
                                $RepeatEndDate = substr($EventList['recurrence'][0], $EqualFirstPos + 1);

                                $AppDate =  date("Y-m-d", strtotime($EventList['start']['dateTime']));
                                $RecurringStartDate = $AppDate;
                                $RecurringEndDate = date("Y-m-d", strtotime($RepeatEndDate));
                            }
                        }// end of if monthly
                    }
                }

                $Status = 'approved';
                $Appointment_by = 'google cal';
                $AppointmentKey = $EventList['id'];
                $PaymentStatus = 'unpaid';

                global $wpdb;
                $AppointmentTable = $wpdb->prefix . "ap_appointments";
                $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";

                //check this appointment is already synced or not
                $AppointmentExisting = $wpdb->get_row("SELECT * FROM `$AppointmentTable` WHERE `appointment_key` = '$AppointmentKey' AND `appointment_by` = '$Appointment_by' ");
                if($AppointmentExisting) {
                    $Update_Appointments = "UPDATE  `$AppointmentTable` SET  `name` = '$Client_name',`email` ='$Client_email',`service_id` = '$ServiceId',`staff_id` ='$StaffId',`start_time` = '$StartTime',`end_time` ='$EndTime',`date` = '$AppDate', `note` = '$Client_Note', `recurring`= '$Recurring' ,`recurring_type` = '$RecurringType',`recurring_st_date` = '$RecurringStartDate' ,`recurring_ed_date` ='$RecurringEndDate' ,`appointment_by` ='$Appointment_by', `payment_status`='$PaymentStatus' where `appointment_key` = '$AppointmentKey'";
                    if($wpdb->query($Update_Appointments)) {
                        $AppointmentId = $wpdb->insert_id;
                        //update appointment sync details
                        global $wpdb;
                        $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
                        $SyncDetails = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTable` WHERE `app_id` = '$AppointmentId'");
                        $SyncTableRowId = $SyncDetails->id;

                        $OAuth = array();
                        $OAuth['creator']['email'] = $CreatorEmail;
                        $OAuth['id'] = $AppointmentKey;
                        $OAuth = serialize($OAuth);
                        $wpdb->query("UPDATE `$AppointmentSyncTable` SET `app_sync_details` = '$OAuth' WHERE `id` = '$SyncTableRowId'");
                    }
                } else {
                    //insert into appointment table
                    $Insert_Appointments = "INSERT INTO `$AppointmentTable` (`id` ,`name` ,`email` ,`service_id` ,`staff_id` ,`phone` ,`start_time` ,`end_time` ,`date` ,`note` , `appointment_key` ,`status` ,`recurring` ,`recurring_type` ,`recurring_st_date` ,`recurring_ed_date` ,`appointment_by`, `payment_status`) VALUES ('NULL', '$Client_name', '$Client_email', '$ServiceId', '$StaffId', '$Client_Phone', '$StartTime', '$EndTime', '$AppDate', '$Client_Note', '$AppointmentKey', '$Status', '$Recurring', '$RecurringType', '$RecurringStartDate', '$RecurringEndDate', '$Appointment_by', '$PaymentStatus');";
                    if($wpdb->query($Insert_Appointments)) {
                        $AppointmentId = $wpdb->insert_id;
                        //insert same appointment id with sync key AppointmentKey
                        $OAuth = array();
                        $OAuth['creator']['email'] = $CreatorEmail;
                        $OAuth['id'] = $AppointmentKey;
                        $OAuth = serialize($OAuth);
                        $wpdb->query("INSERT INTO `$AppointmentSyncTable` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$AppointmentId', '$OAuth' );");
                    }
                }
            }//end of skip if
        }//end of foreach
    }// end of no google event
}// end of calendar credential check