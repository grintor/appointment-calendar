<style type='text/css'> .error{ color:#FF0000; </style>
<div class="bs-docs-example tooltip-demo">
    <?php $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";
    if(isset($_GET['from'])) {
        $FromBack = $_GET['from'];
    } else {
        $FromBack = NULL;
    }

    global $wpdb;
    if(isset($_GET['updateid'])) {
        $AppointmentId = $_GET['updateid'];
        $table_name = $wpdb->prefix . "ap_appointments";
        $AppointmentDetail_SQL = "SELECT * FROM `$table_name` WHERE `id` ='$AppointmentId'";
        $AppointmentDetails = $wpdb->get_row($AppointmentDetail_SQL); ?>
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><?php _e('Update Appointment', 'appointzilla'); ?></h3></div>

    <form action="" method="post" name="manageservice"><!---update appointment form--->
        <table width="100%" class="table" >
            <input type="hidden" name="fromback" id="fromback" value="<?php if(isset($_GET['from'])) echo $_GET['from']; ?>" />
            <tr>
                <th scope="row"><?php _e('Appointment Created', 'appointzilla'); ?> </th>
                <td><strong>:</strong></td>
                <td><?php echo date($DateFormat." ".$TimeFormat.":s", strtotime("$AppointmentDetails->book_date")); ?></td>
            </tr>
            <tr>
                <th width="16%" scope="row"><?php _e('Name', 'appointzilla', 'appointzilla'); ?></th>
                <td width="5%"><strong>:</strong></td>
                <td width="79%"><input name="appname" type="text" id="appname"  value="<?php echo $AppointmentDetails->name; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Name.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Email', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><input name="appemail" type="text" id="appemail" readonly="" value="<?php echo $AppointmentDetails->email; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Email. You cannot modify this.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Service', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td>
                    <select id="serviceid" name="serviceid">
                        <?php
                            //get all service list
                            global $wpdb;
                            $table_name = $wpdb->prefix . "ap_services";
                            $service_list = $wpdb->get_results("select * from $table_name");
                            foreach($service_list as $service) { ?>
                                <option value="<?php echo $service->id; ?>" <?php if($AppointmentDetails->service_id == $service->id) echo "selected";  ?>><?php echo $service->name; ?></option>
                        <?php } ?>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Name.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Staff', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td>
                    <select id="staffid" name="staffid">
                    <?php //get all service list
                        global $wpdb;
                        $staff_table_name = $wpdb->prefix . "ap_staff";
                        $staff_list = $wpdb->get_results("select * from $staff_table_name");
                        foreach($staff_list as $staff) { ?>
                            <option value="<?php echo $staff->id; ?>" <?php if($AppointmentDetails->staff_id == $staff->id ) echo "selected";  ?> ><?php echo $staff->name; ?></option>
                    <?php } ?>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('staff Name.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
                </td>
            </tr>

            <tr>
                <th scope="row"><strong><?php _e('Phone', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><input name="appphone" type="text" id="appphone"  value="<?php echo $AppointmentDetails->phone; ?>" class="inputheight"/ maxlength="12">&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Phone Number.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i> </a>				</td>
            </tr>
            <tr>
                <?php if($TimeFormat == 'h:i') $ATimeFormat = "h:i A"; else $ATimeFormat = "H:i"; ?>
                <th scope="row"><strong><?php _e('Start Time', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><input name="start_time" type="text" id="start_time"  value="<?php echo date($ATimeFormat, strtotime($AppointmentDetails->start_time)); ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Start Time.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i> </a>				</td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('End Time', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><input name="end_time" type="text" id="end_time"  value="<?php echo date($ATimeFormat, strtotime($AppointmentDetails->end_time)); ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment End Time.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i> </a>				</td>
            </tr>


            <tr>
                <th scope="row"><strong><?php _e('Date', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><input name="start_date" type="text" id="start_date" value="<?php echo $AppointmentDetails->date; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Date.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i> </a>				</td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Description', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td><textarea name="app_desc" id="app_desc"><?php echo $AppointmentDetails->note; ?></textarea>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Description.', 'appointzilla'); ?>" ><i class="icon-question-sign"></i> </a>				</td>
            </tr>
            <tr style="display: none;">
                <th scope="row"><?php _e('Repeat', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td>
                    <select name="recurring" id="recurring">
                       <option value="yes" <?php if($AppointmentDetails->recurring == 'yes') echo "selected"; ?>><?php _e('Yes', 'appointzilla'); ?></option>
                       <option value="no" <?php if($AppointmentDetails->recurring == 'no') echo "selected"; ?>><?php _e('No', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title=" <?php _e('Appointment Repeat On/OFF.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i>
                </td>
            </tr>
            <tr style="display: none;">
                <th scope="row"><?php _e('Repeat Type', 'appointzilla'); ?> </th>
                <td><strong>:</strong></td>
                <td>
                    <select id="recurring_type" name="recurring_type">
                        <option value="none" <?php if($AppointmentDetails->recurring_type == 'none') echo "selected"; ?>> <?php _e('None', 'appointzilla'); ?></option>
                        <option value="daily" <?php if($AppointmentDetails->recurring_type == 'daily') echo "selected"; ?>> <?php _e('Daily', 'appointzilla'); ?></option>
                        <option value="weekly" <?php if($AppointmentDetails->recurring_type == 'weekly') echo "selected"; ?>> <?php _e('Weekly', 'appointzilla'); ?></option>
                        <option value="monthly" <?php if($AppointmentDetails->recurring_type == 'monthly') echo "selected"; ?>> <?php _e('Monthly', 'appointzilla'); ?></option>
                        <option value="PD" <?php if($AppointmentDetails->recurring_type == 'PD') echo "selected"; ?>><?php _e('Particular Day', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Repeat Type.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i>
                </td>
            </tr>
            <tr style="display: none;">
                <th scope="row"><?php _e('Repeat Start Date', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input name="recurring_st_date" type="text" id="recurring_st_date" value="<?php echo $AppointmentDetails->recurring_st_date; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Repeat Start Date.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></td>
            </tr>
            <tr style="display: none;">
                <th scope="row"><?php _e('Repeat End Date', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input name="recurring_ed_date" type="text" id="recurring_ed_date" value="<?php echo $AppointmentDetails->recurring_ed_date; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Repeat End Date.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></td>
            </tr>

            <tr style="display: none;">
                <th scope="row"><strong><?php _e('Appointment By', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td>
                    <select id="app_appointment_by" name="app_appointment_by">
                        <option value="admin" <?php if($AppointmentDetails->appointment_by == 'admin') echo "selected"; ?> ><?php _e('Admin', 'appointzilla'); ?></option>
                        <option value="user" <?php if($AppointmentDetails->appointment_by == 'user') echo "selected"; ?> > <?php _e('User', 'appointzilla'); ?></option>
                        <option value="google calendar" <?php if($AppointmentDetails->appointment_by == 'google calendar') echo "selected"; ?> > <?php _e('Google Calendar', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Booked By User/Admin.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
                </td>
            </tr>

            <tr>
                <th scope="row"><strong><?php _e('Status', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td>
                    <select id="app_status" name="app_status">
                        <option value="pending" <?php if($AppointmentDetails->status == 'pending') echo "selected"; ?>><?php _e('Pending', 'appointzilla'); ?></option>
                        <option value="cancelled" <?php if($AppointmentDetails->status == 'cancelled') echo "selected"; ?>><?php _e('Cancelled', 'appointzilla'); ?></option>
                        <!--<option value="approved" <?php /*if($AppointmentDetails->status == 'approved') echo "selected"; */?> ><?php /*_e('Approved', 'appointzilla'); */?></option>
                        <option value="done" <?php /*if($AppointmentDetails->status == 'done') echo "selected"; */?> > <?php /*_e('Done', 'appointzilla'); */?></option>-->
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Status. Rescheduling will change this appointment status pending.', 'appointzilla'); ?>"><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr style="display: none;">
                <th scope="row"><strong><?php _e('Payment Status', 'appointzilla'); ?></strong></th>
                <td><strong>:</strong></td>
                <td>
                    <select id="payment_status" name="payment_status">
                        <option value="unpaid" <?php if($AppointmentDetails->payment_status == 'unpaid') echo "selected"; ?>><?php _e('Unpaid', 'appointzilla'); ?></option>
                        <option value="paid" <?php if($AppointmentDetails->payment_status == 'paid') echo "selected"; ?>><?php _e('Paid', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Appointment Status.', 'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td> <?php if(isset($_GET['updateid'])) { ?>
                    <button id="updateppointments" type="submit" class="btn" name="updateppointments" value="<?php echo $AppointmentDetails->id; ?>"><i class="icon-pencil"></i> <?php _e('Update', 'appointzilla'); ?></button>
                    <?php } else {?>
                    <!--<button id="saveservice" type="submit" class="btn btn-primary" name="saveservice">Create</button>-->
                    <?php } ?>
                    <?php if(isset($_GET['from'])) { ?>
                        <a href="?page=appointment-calendar" class="btn"><i class="icon-remove"></i> <?php _e('Cancel', 'appointzilla'); ?></a>
                    <?php } else {  ?>
                        <a href="?page=manage-client-appointments" class="btn"><i class="icon-remove"></i> <?php _e('Cancel', 'appointzilla'); ?></a>
                    <?php }?>
                </td>
            </tr>
        </table>
    </form>
    <?php } ?>

    <!--validation js lib-->
    <script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>

    <script type="text/javascript">
    <?php if($TimeFormat == 'h:i') {
        $ATimePickerFormat = "hh:mm TT"; $Tflag = 'true';
        }
        if($TimeFormat == 'H:i') {
        $ATimePickerFormat = "hh:mm"; $Tflag = 'false';
    } ?>

    jQuery(document).ready(function () {

        jQuery(function(){
            //load date and time picker
            jQuery('#start_time').timepicker({
                ampm: <?php echo $Tflag; ?>,
                timeFormat: '<?php echo $ATimePickerFormat; ?>',
            });

            jQuery('#end_time').timepicker({
                ampm: <?php echo $Tflag; ?>,
                timeFormat: '<?php echo $ATimePickerFormat; ?>',
            });

            jQuery('#start_date').datepicker({
                minDate: 0,
                dateFormat: 'dd-mm-yy',
            });

            jQuery('#recurring_st_date').datepicker({
                //minDate: 0,
                dateFormat: 'dd-mm-yy',
            });

            jQuery('#recurring_ed_date').datepicker({
                //minDate: 0,
                dateFormat: 'dd-mm-yy',
            });

        });

        // update appointment validation
        jQuery('#updateppointments').click(function() {

            jQuery(".error").hide();
            //start-date appname appemail serviceid appphone start_time end_time start_date
            var appname = jQuery("#appname").val();
            if(appname == '') {
                jQuery("#appname").after('<span class="error"><br><strong><?php _e('Name cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }
            var appemail = jQuery("input#appemail").val();
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (appemail == "") {
                jQuery("#appemail").after('<span class="error"><br><strong><?php _e('Email field cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            } else {
                if(regex.test(appemail) == false ) {
                    jQuery("#appemail").after('<span class="error"><br><strong><?php _e('Invalid email address', 'appointzilla'); ?></strong></span>');
                    return false;
                }
            }
            //start-date
            var appphone = jQuery("#appphone").val();
            if(appphone == '') {
                jQuery("#appphone").after('<span class="error"><br><strong><?php _e('Phone field cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            } else {
                var appphone = isNaN(appphone);
                if(appphone == true) {
                    jQuery("#appphone").after('<span class="error"><br><strong><?php _e('Invalid Phone number.', 'appointzilla'); ?></strong></span>');
                    return false;
                }
            }


            var start_time = jQuery("#start_time").val();
            if(start_time == '') {
                jQuery("#start_time").after('<span class="error"><br><strong><?php _e('Start Time  cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }
            var end_time = jQuery("#end_time").val();
            if(end_time == '') {
                jQuery("#end_time").after('<span class="error"><br><strong><?php _e('End Time cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }

            if(start_time == end_time) {
                jQuery("#start_time").after('<span class="error"><br><strong><?php _e("Start-time and End-time cant be equal.",'appointzilla'); ?></strong></span>');
                jQuery("#end_time").after('<span class="error"><br><strong><?php _e("Start-time and End-time cant be equal.",'appointzilla'); ?></strong></span>');
                return false;
            }

            //Time compression + convert both time into timestamp
            var stt = new Date("October 13, 2013 " + start_time);
            stt = stt.getTime();

            var endt = new Date("October 13, 2013 " + end_time);
            endt = endt.getTime();
            console.log("Time1: "+ stt + " Time2: " + endt);

            if(stt > endt) {
                jQuery("#start_time").after('<span class="error"><br><strong><?php _e("Start-time must be smaller then End-time.",'appointzilla'); ?></strong></span>');
                jQuery("#end_time").after('<span class="error"><br><strong><?php _e("End-time must be bigger then Start-time.",'appointzilla'); ?></strong></span>');
                return false;
            }

            // appointment date
            var start_date = jQuery("#start_date").val();
            if(start_date == '') {
                jQuery("#start_date").after('<span class="error"><br><strong><?php _e('Start Date cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }

            var recurring = jQuery("#recurring").val();
            if(recurring == 'yes') {
                if(jQuery("#recurring_type").val() == 'none') {
                    jQuery("#recurring_type").after('<span class="error"><br><strong><?php _e('Select valid repeat type.', 'appointzilla'); ?></strong></span>');
                    return false;
                }
            }

            var recurring_st_date = jQuery("#recurring_st_date").val();
            if(recurring_st_date == '') {
                jQuery("#recurring_st_date").after('<span class="error"><br><strong><?php _e('Repeat start date cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }

            var recurring_ed_date = jQuery("#recurring_ed_date").val();
            if(recurring_ed_date == '') {
                jQuery("#recurring_ed_date").after('<span class="error"><br><strong><?php _e('Repeat end date cannot be blank.', 'appointzilla'); ?></strong></span>');
                return false;
            }
        });
    });
    </script>


    <?php //update appointment details
    if(isset($_POST['updateppointments'])) {
        global $wpdb;
        $up_app_id = $_POST['updateppointments'];
        $name = strip_tags($_POST['appname']);
        $email = $_POST['appemail'];
        $serviceid = $_POST['serviceid'];
        $staffid = $_POST['staffid'];
        $phone = $_POST['appphone'];
        $start_time = date("h:i A", strtotime($_POST['start_time']));
        $end_time = date("h:i A", strtotime($_POST['end_time']));
        $appointmentdate = date("Y-m-d", strtotime($_POST['start_date']));
        $note = strip_tags($_POST['app_desc']);
        $payment_status = strip_tags($_POST['payment_status']);
        $status =  $_POST['app_status'];
        $recurring = $_POST['recurring'];
        $recurring_type = $_POST['recurring_type'];
        $recurring_st_date = date("Y-m-d", strtotime($_POST['recurring_st_date'])); //$_POST['recurring_st_date'];
        $recurring_ed_date = date("Y-m-d", strtotime($_POST['recurring_ed_date'])); //$_POST['recurring_ed_date'];
        $appointment_by = $_POST['app_appointment_by'];

        $AppointmentTable = $wpdb->prefix . "ap_appointments";
        $update_appointment="UPDATE `$AppointmentTable` SET `name` = '$name', `email` = '$email', `service_id` = '$serviceid', `staff_id` = '$staffid', `phone` = '$phone', `start_time` = '$start_time', `end_time` = '$end_time', `date` = '$appointmentdate', `note` = '$note', `status` = '$status', `recurring` = '$recurring', `recurring_type` = '$recurring_type', `recurring_st_date` = '$recurring_st_date', `recurring_ed_date` = '$recurring_ed_date', `appointment_by` = '$appointment_by', `payment_status` = '$payment_status' WHERE `id` = '$up_app_id';";
            if($wpdb->query($update_appointment)) {
                //send notification to client if appointment approved or cancelled
                if($status == 'approved' || $status == 'cancelled' ) {
                    $BlogName =  get_bloginfo();
                    $ClientTable = $wpdb->prefix . "ap_clients";
                    $GetClient = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$email' ", OBJECT);
                    // dont notify no@email.com coz its without attendee
                    if($up_app_id && $GetClient->id && $email != "no@email.com") {
                        $AppId = $up_app_id;
                        $ServiceId = $serviceid;
                        $StaffId = $staffid;
                        $ClientId = $GetClient->id;
                        //include notification class
                        require_once('notification-class.php');
                        $Notification = new Notification();
                        if($status == 'approved') $On = "approved";
                        if($status == 'cancelled') $On = "cancelled";
                        $Notification->notifyclient($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                        if(get_option('staff_notification_status') == 'on') {
                            $Notification->notifystaff($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                        }
                    }
                }// end send notification to client if appointment approved or cancelled check

                //if status is approved then sync appointment
                if($status == 'approved') {
                    $CalData = get_option('google_caelndar_settings_details');
                    if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                        $ClientId = $CalData['google_calendar_client_id'];
                        $ClientSecretId = $CalData['google_calendar_secret_key'];
                        $RedirectUri = $CalData['google_calendar_redirect_uri'];
                        require_once('google-appointment-sync-class.php');

                        global $wpdb;
                        $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                        $AnySycnId = $wpdb->get_row("SELECT `id` FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                        //check appointment already synced or first time approved
                        if(count($AnySycnId)) {
                            // update this appointment event on calendar
                            global $wpdb;
                            $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                            $SyncDetails = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                            $SyncTableRowId = $SyncDetails->id;
                            $SyncDetailsData = unserialize($SyncDetails->app_sync_details);
                            $json  = json_encode($SyncDetailsData);
                            $SyncDetailsData = json_decode($json, true);
                            $sync_id = $SyncDetailsData['id'];
                            $sync_email = $SyncDetailsData['creator']['email'];

                            $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                            $tag = "Appointment with: ";
                            if($repeat == 'none') {
                                $OAuth = $GoogleAppointmentSync->UpdateNormalSync($sync_id, $sync_email, $name, $appointmentdate, $start_time, $end_time, $note, $tag);
                            }
                            if($repeat != 'none') {
                                $OAuth = $GoogleAppointmentSync->UpdateRecurringSync($sync_id, $sync_email, $name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                            }

                            //update appointment sync details
                            $OAuth = serialize($OAuth);
                            $wpdb->query("UPDATE `$AppointmentSyncTableName` SET `app_sync_details` = '$OAuth' WHERE `id` = '$SyncTableRowId'");
                        } else {
                            // insert this appointment event on calendar
                            $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                            $tag = "Appointment with: ";
                            if($recurring_type == 'none') {
                                $OAuth = $GoogleAppointmentSync->NormalSync($name, $appointmentdate, $start_time, $end_time, $note, $tag);
                            }
                            if($recurring_type != 'none') {
                                $OAuth = $GoogleAppointmentSync->RecurringSync($name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                            }

                            //insert appointment sync details
                            $OAuth = serialize($OAuth);
                            $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$up_app_id', '$OAuth' );");
                        }
                    }//end of if cal settings check
                }//end of if approved


                //if status is cancelled then delete sync appointment
                if($status == 'cancelled' || $status == 'pending') {
                    global $wpdb;
                    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                    $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                    $Row = unserialize($Row->app_sync_details);
                    $json  = json_encode($Row);
                    $Row = json_decode($json, true);
                    $SyncId = $Row['id'];
                    if($SyncId) {
                        $CalData = get_option('google_caelndar_settings_details');
                        $ClientId = $CalData['google_calendar_client_id'];
                        $ClientSecretId = $CalData['google_calendar_secret_key'];
                        $RedirectUri = $CalData['google_calendar_redirect_uri'];

                        require_once('google-appointment-sync-class.php');
                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);

                        // delete sync details
                        $wpdb->query("DELETE FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                    }
                }//end of cancel status check


                //redirect to updated appointment details page
                if($_POST['fromback']) {
                    echo "<script>alert('".__('Appointment successfully updated', 'appointzilla')."');</script>";
                    echo "<script>location.href='?page=update-client-appointment&viewid=$up_app_id&from=calendar';</script>";
                } else {
                    echo "<script>alert('".__('Appointment successfully updated', 'appointzilla')."');</script>";
                    echo "<script>location.href='?page=update-client-appointment&viewid=$up_app_id';</script>";
                }
            } else {
                if($_POST['fromback']) {
                    echo "<script>alert('".__('Appointment successfully updated', 'appointzilla')."');</script>";
                    echo "<script>location.href='?page=update-client-appointment&viewid=$up_app_id&from=calendar';</script>";
                } else {
                    echo "<script>alert('".__('Appointment successfully updated', 'appointzilla')."');</script>";
                    echo "<script>location.href='?page=update-client-appointment&viewid=$up_app_id';</script>";
                }
            }
    }// end of isset

    //appointment view page
    if(isset($_GET['viewid'])) {
        $appid=$_GET['viewid'];
        $table_name = $wpdb->prefix . "ap_appointments";
        $AppDetail_SQL ="SELECT * FROM $table_name WHERE `id` ='$appid'";
        $AppDetails = $wpdb->get_row($AppDetail_SQL); ?>
        <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
            <h3><?php _e('View Appointment(s): ', 'appointzilla'); ?>: <?php echo ucwords($AppDetails->name); ?></h3>
        </div>
        <!---update appointment form--->
        <table width="100%" class="table" >
            <tr>
                <th scope="row"><?php _e('Appointment Creation Date', 'appointzilla'); ?>
                </th>
                <td><strong>:</strong></td>
                <td><?php echo date($DateFormat." ".$TimeFormat.":s", strtotime("$AppDetails->book_date")); ?></td>
            </tr>
            <tr>
                <th width="16%" scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                <td width="5%"><strong>:</strong></td>
                <td width="79%"><em><?php echo ucwords($AppDetails->name); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo $AppDetails->email; ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Service', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td>
                    <em>
                        <?php
                            $table_name = $wpdb->prefix . "ap_services";
                            $servicedetails= $wpdb->get_row("SELECT * FROM $table_name WHERE `id` ='$AppDetails->service_id'");
                            echo ucwords($servicedetails->name);
                        ?>
                    </em>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Staff', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td>
                    <em>
                    <?php
                        $staff_table_name = $wpdb->prefix . "ap_staff";
                        $staffdetails= $wpdb->get_row("SELECT * FROM $staff_table_name WHERE `id` ='$AppDetails->staff_id'");
                        echo ucwords($staffdetails->name);
                    ?>
                    </em>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo $AppDetails->phone; ?></em></td>
            </tr>
            <tr>
                <?php if($TimeFormat == 'h:i') $ATimeFormat = "h:i A"; else $ATimeFormat = "H:i"; ?>
                <th scope="row"><?php _e('Start Time', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo date($ATimeFormat, strtotime($AppDetails->start_time)); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('End Time', 'appointzilla'); ?> </th>
                <td><strong>:</strong></td>
                <td><em><?php echo date($ATimeFormat, strtotime($AppDetails->end_time)); ?></em></td>
            </tr>
            <tr>
                <?php
                    if($DateFormat == 'd-m-Y') $VDateFormat = "jS F Y";
                    if($DateFormat == 'm-d-Y') $VDateFormat = "F jS Y";
                    if($DateFormat == 'Y-m-d') $VDateFormat = "Y F jS";
                ?>
                <th scope="row"><?php _e('Date', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo date($DateFormat, strtotime($AppDetails->date)); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Description', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo ucfirst($AppDetails->note); ?></em></td>
            </tr>
                <tr>
                <th scope="row"><?php _e('Appointment Key', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo $AppDetails->appointment_key; ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Status', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo _e(ucfirst($AppDetails->status), 'appointzilla'); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Repeat', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo _e(ucfirst($AppDetails->recurring), 'appointzilla'); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Repeat Type', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo _e(ucfirst($AppDetails->recurring_type), 'appointzilla'); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Repeat Start Date', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo date($DateFormat, strtotime($AppDetails->recurring_st_date)); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Repeat End Date', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo date($DateFormat, strtotime($AppDetails->recurring_ed_date)); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Appointment By', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo _e(ucfirst($AppDetails->appointment_by), 'appointzilla'); ?></em></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Payment Status', 'appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><em><?php echo _e(ucfirst($AppDetails->payment_status), 'appointzilla');?></em></td>
            </tr>
            <tr>
            <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>
                    <?php if($FromBack) { ?>
                    <a href="?page=appointment-calendar" class="btn"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></a>
                    <a href="?page=update-client-appointment&updateid=<?php echo $appid; ?>&from=calendar" class="btn btn-primary"><?php _e('Edit', 'appointzilla'); ?></a>
                    <?php } else { ?>
                    <a href="?page=manage-client-appointments" class="btn"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></a>
                    <?php } ?>
                </td>
            </tr>
        </table><?php
    } ?>
    <!--time-picker js -->
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-1.7.2.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui-timepicker-addon.js', __FILE__); ?>" type="text/javascript"></script>
</div>