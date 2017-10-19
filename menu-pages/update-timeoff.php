<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><i class="fa fa-clock-o"></i> <?php _e('Update Time Off','appointzilla'); ?></h3>
</div>
<!--load TimeOff modal for update -->
<?php $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";

    if(isset($_GET['update_timeoff'])) {
        $update_id = $_GET['update_timeoff'];
        global $wpdb;
        $AllStaffIds[] = NULL;
        $EventTable = $wpdb->prefix."ap_events";
        $TimeOffSql = "select * from `$EventTable` where `id` = '$update_id'";
        $TimeOffData = $wpdb->get_row($TimeOffSql, OBJECT);
        if($TimeOffData->staff_id) {
            //if no staff assigned then it return null
            if(count(unserialize($TimeOffData->staff_id))) {
                $AllStaffIds = unserialize($TimeOffData->staff_id);
            }
        } else {
         $AllStaffIds[] = NULL;
        } ?>
        <form action="" method="post" name="AddNewTimeOff-From" id="AddNewTimeOff-From">
            <input name="update_id" id="update_id" type="hidden" value="<?php echo $update_id; ?>" />
            <input name="fromback" id="fromback" type="hidden" value="<?php if(isset($_GET['from'])) { echo $_GET['from']; } ?>" />
            <table width="100%" class="table">
                <tr>
                    <th width="21%" scope="row"><?php _e('All Day Time Off','appointzilla'); ?></th>
                    <td width="4%"><strong>:</strong></td>
                    <td width="75%"><input name="allday" id="allday" type="checkbox" value="1" <?php if($TimeOffData->allday) echo "checked=checked"; ?> /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Name','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="name" id="name" type="text" value="<?php echo $TimeOffData->name; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Start Time','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <?php if($TimeFormat == 'h:i') $TOTimeFormat = "h:i A"; else $TOTimeFormat = "H:i"; ?>
                    <td><input name="start_time" id="start_time" type="text" value="<?php if(!$TimeOffData->allday) echo date($TOTimeFormat, strtotime($TimeOffData->start_time)); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('End Time','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="end_time" id="end_time" type="text" value="<?php if($TimeOffData->allday == 0) echo  date($TOTimeFormat, strtotime($TimeOffData->end_time)); ?>" /></td>
                </tr>
                <tr id="event_date_tr" style="display:<?php if($TimeOffData->repeat == 'PD') echo "none"; else echo ""; ?> ">
                    <th scope="row"><?php _e('Date','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="event_date" id="event_date" type="text"  value="<?php echo $TimeOffData->start_date; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Repeat','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select name="repeat" id="repeat" onchange="repeatday()">
                            <option onclick="hideAll()" value="N" <?php if($TimeOffData->repeat == 'N') echo "selected=selected"; ?> ><?php _e('No','appointzilla'); ?></option>
                            <option onclick="showPD()" value="PD" <?php if($TimeOffData->repeat == 'PD') echo "selected=selected"; ?> ><?php _e('Particular Date(s)','appointzilla'); ?></option>
                            <option onclick="showDaily()" value="D" <?php if($TimeOffData->repeat == 'D') echo "selected=selected"; ?> ><?php _e('Daily','appointzilla'); ?></option>
                            <option onclick="showWeekly()" value="W" <?php if($TimeOffData->repeat == 'W') echo "selected=selected"; ?> ><?php _e('Weekly','appointzilla'); ?></option>
                            <option onclick="showBiWeekly()" value="BW" <?php if($TimeOffData->repeat == 'BW') echo "selected=selected"; ?> ><?php _e('Bi-Weekly','appointzilla'); ?></option>
                            <option onclick="showMonthly()" value="M" <?php if($TimeOffData->repeat == 'M') echo "selected=selected"; ?> ><?php _e('Monthly','appointzilla'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr id="re_days_tr" style="display:none;">
                    <th scope="row"><?php _e('Repeat Day(s)','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <?php
                        $diff = NULL;
                        if($TimeOffData->repeat == 'PD' || $TimeOffData->repeat == 'D' ) {
                            $diff = ( strtotime($TimeOffData->end_date) - strtotime($TimeOffData->start_date)  ) /60/60/24;
                            $diff = $diff + 1;
                        }
                        if($TimeOffData->repeat == 'W') {
                            $diff = ( strtotime($TimeOffData->end_date) - strtotime($TimeOffData->start_date)  ) /60/60/24/7;
                            $diff = floor($diff); // + 1;
                        }
                        if($TimeOffData->repeat == 'BW') {
                            $diff = ( strtotime($TimeOffData->end_date) - strtotime($TimeOffData->start_date)  ) /60/60/24/7;
                        }
                        if($TimeOffData->repeat == 'M') {
                            $diff = ( strtotime($TimeOffData->end_date) - strtotime($TimeOffData->start_date)  ) /60/60/24/31;
                        }
                    ?>
                    <td><input name="re_days" id="re_days" type="text" value="<?php echo $diff; ?>" /></td>
                </tr>

                <tr id="re_weeks_tr" style="display:none;">
                    <th scope="row"><?php _e('Repeat Weeks(s)','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="re_weeks" id="re_weeks" type="text" value="<?php echo $diff; ?>" /></td>
                </tr>

                <tr id="re_biweeks_tr" style="display:none;">
                    <th scope="row"><?php _e('Repeat Bi-Weeks(s)','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="re_biweeks" id="re_biweeks" type="text" value="<?php echo $diff/2; ?>" /></td>
                </tr>

                <tr id="re_months_tr" style="display:none;">
                    <th scope="row"><?php _e('Repeat Month(s)','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="re_months" id="re_months" type="text" value="<?php echo ceil($diff); ?>" /></td>
                </tr>

                <tr id="start_date_tr" style="display:none;">
                    <th scope="row"><?php _e('Start Date','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="start_date" id="start_date" type="text" value="<?php echo $TimeOffData->start_date; ?>" /></td>
                </tr>

                <tr id="end_date_tr" style="display:none;">
                    <th scope="row"><?php _e('End Date','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><input name="end_date" id="end_date" type="text" value="<?php if($TimeOffData->repeat == 'PD')echo $TimeOffData->end_date; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Note','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td><textarea name="note" id="note"><?php echo $TimeOffData->note; ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Assign Staff(s)','appointzilla');?></th>
                    <td><strong>:</strong></td>
                    <td>
                        <label><?php _e('Use CTRL to Select Multiple Staff(s)','appointzilla');?></label>
                        <select id="staff" name="staff[]" multiple="multiple" size="7" style="width:300px;">
                        <?php
                            global $wpdb;
                            $StaffTableName = $wpdb->prefix . "ap_staff";
                            $AllStaff = $wpdb->get_results("SELECT `id`, `name` FROM `$StaffTableName`", OBJECT);
                            foreach($AllStaff as $Staff) {
                                if( in_array($Staff->id, $AllStaffIds) )  {
                                    $selected = "Selected";
                                } else {
                                    $selected = "";
                                }
                                echo "<option value='$Staff->id' $selected >".ucwords($Staff->name)."</option>";
                            }
                        ?>
                        </select><!--&nbsp;<a href="#" rel="tooltip" title="<?php //_e('Assign Staff(s) To This TimeOff. Use CTRL To Select Multiple Staffs','appointzilla');?>" ><i  class="icon-question-sign"></i></a>-->
                    </td>
                </tr>

                <tr>
                    <th scope="row">&nbsp;</th>
                    <td>&nbsp;</td>
                    <td><button name="create" id="create" class="btn" type="submit"><i class="icon-pencil"></i> <?php _e('Update','appointzilla'); ?></button>
                    <a href="?page=timeoff" class="btn"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla'); ?></a>
                    </td>
                </tr>
            </table>
        </form><?php
    } ?>

<style type='text/css'>
.error{ 
    color:#FF0000;
}
</style>

<!---Insert/update Time-off in db--->
<?php 
    if(isset($_POST['create'])) {
        $update_id = $_POST['update_id'];
        $fromback = $_POST['fromback'];

        // all day event
        if($_POST['allday']) {
            $allday = 1;
            $start_time = '12:00 AM';
            $end_time = '11:59 PM';
        } else {
            $allday = 0;
            $start_time = date("h:i A", strtotime($_POST['start_time']));
            $end_time = date("h:i A", strtotime($_POST['end_time']));
        }
        $name = $_POST['name'];
        $repeat = $_POST['repeat'];
        $start_date = $_POST['event_date'];
        $start_date = date("Y-m-d", strtotime($start_date)); //convert format

        //not repeat
        if($repeat == 'N') {
            $end_date =  date("Y-m-d", strtotime($start_date)); //convert format
        }

        //particular day
        if($repeat == 'PD') {
            $start_date = $_POST['start_date'];
            $start_date = date("Y-m-d", strtotime($start_date)); //convert format

            $end_date = $_POST['end_date'];
            $end_date =  date("Y-m-d", strtotime($end_date)); //convert format
        }

        //daily event will be  90 days
        if($repeat == 'D') {
            $repeat_days = $_POST['re_days'];
            $repeat_days = $repeat_days - 1;
            $end_date = strtotime($start_date);
            $end_date = date("Y-m-d", strtotime("+$repeat_days days", $end_date)); 	//add 3 month
        }

        //weekly event add 1 week
        if($repeat == 'W') {
            $repeat_weeks = $_POST['re_weeks'];
            $end_date = strtotime($start_date);
            $end_date = date("Y-m-d", strtotime("+$repeat_weeks week", $end_date)); 	//add 1 week
        }

        //bi-weekly event multiply 2
        if($repeat == 'BW') {
            $repeat_weeks = $_POST['re_biweeks'];
            $end_date = strtotime($start_date);
            $repeat_weeks = $repeat_weeks * 2;
            $end_date = date("Y-m-d", strtotime("+$repeat_weeks week", $end_date)); 	//add 1 week
        }

        //monthly event add 1 month
        if($repeat == 'M') {
            $repeat_months = $_POST['re_months'];
            $end_date = strtotime($start_date);
            $end_date = date("Y-m-d", strtotime("+$repeat_months months", $end_date)); 	//add 1 month
        }
        $note = $_POST['note'];
        $status = "Up-Coming";
        $staff = serialize($_POST['staff']);

        global $wpdb;
        $EventTable = $wpdb->prefix."ap_events";
        $event_update_sql = "UPDATE `$EventTable` SET `name` = '$name', `allday` = '$allday', `start_time` = '$start_time', `end_time` = '$end_time', `repeat` = '$repeat', `start_date` = '$start_date', `end_date` = '$end_date', `note` = '$note', `status` = '$status', `staff_id` = '$staff' WHERE `id` ='$update_id';";
        if($wpdb->query($event_update_sql)) {
            // sync timeoff if google calendar settings avialble
            $CalData = get_option('google_caelndar_settings_details');
            if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                $ClientId = $CalData['google_calendar_client_id'];
                $ClientSecretId = $CalData['google_calendar_secret_key'];
                $RedirectUri = $CalData['google_calendar_redirect_uri'];
                include('google-appointment-sync-class.php');

                // get sync id and email for update event on google calendar
                global $wpdb;
                $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                $SyncDetails = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `timeoff_id` = '$update_id'");
                //check appointment already synced or first time approved
                if($SyncDetails) {
                    $SyncTableRowId = $SyncDetails->id;
                    $SyncDetailsData = unserialize($SyncDetails->app_sync_details);
                    $json  = json_encode($SyncDetailsData);
                    $SyncDetailsData = json_decode($json, true);
                    $sync_id = $SyncDetailsData['id'];
                    $sync_email = $SyncDetailsData['creator']['email'];

                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $tag = "TimeOff: ";
                    if($repeat == 'N') {
                        $OAuth = $GoogleAppointmentSync->UpdateNormalSync($sync_id, $sync_email, $name, $start_date, $start_time, $end_time, $note, $tag);
                    }

                    if($repeat != 'N') {
                        $OAuth = $GoogleAppointmentSync->UpdateRecurringSync($sync_id, $sync_email, $name, $start_date, $end_date, $start_time, $end_time, $repeat, $note, $tag);
                    }

                    //update appointment sync details
                    $OAuth = serialize($OAuth);
                    $wpdb->query("UPDATE `$AppointmentSyncTableName` SET `app_sync_details` = '$OAuth' WHERE `id` = '$SyncTableRowId' AND `timeoff_id` = '$update_id'");
                } else {
                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $tag = "TimeOff: ";
                    if($repeat == 'N') {
                        $OAuth = $GoogleAppointmentSync->NormalSync($name, $start_date, $start_time, $end_time, $note, $tag);
                    }

                    if($repeat != 'N') {
                        $OAuth = $GoogleAppointmentSync->RecurringSync($name, $start_date, $end_date, $start_time, $end_time, $repeat, $note, $tag);
                    }
                    //insert timeoff sync details
                    $OAuth = serialize($OAuth);
                    $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `timeoff_id` , `app_sync_details` )
                    VALUES ( NULL , '$update_id', '$OAuth' );");
                }

            }//end of cal settings check
        }

        if($fromback) {
            echo "<script>alert('".__('Time-off successfully updated.','appointzilla')."')</script>";
            echo "<script>location.href='?page=appointment-calendar';</script>";
        } else {
            echo "<script>alert('".__('Time-off successfully updated.','appointzilla')."')</script>";
            echo "<script>location.href='?page=timeoff';</script>";
        }

    }
?>

<!---Delete single time off--->
<?php 
    if(isset($_GET['delete_timeoff'])) {
        global $wpdb;
        $EventTable = $wpdb->prefix."ap_events";
        $del_id = $_GET['delete_timeoff'];
        $timeoffdelete_sql = "delete from `$EventTable` where `id` = '$del_id'";
        if($wpdb->query($timeoffdelete_sql)) {
            echo "<script>alert('".__('Time Off deleted successfully.','appointzilla')."')</script>";
            echo "<script>location.href='?page=timeoff';</script>";
        } else {
            echo "<script>location.href='?page=timeoff';</script>";
        }
    }
?>

<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function () {

    //select all checkbox for multiple delete
    jQuery('#checkbox').click(function(){
        if(jQuery('#checkbox').is(':checked')) {
            jQuery(":checkbox").prop("checked", true);
        } else {
            jQuery(":checkbox").prop("checked", false);
        }
    });

    //Launch Modal Form - hide modal
    jQuery('#close').click(function(){

        jQuery('#TimeOffModal').hide();
    });

    //show modal
    jQuery('#addnewtimeoff').click(function(){
        jQuery('#TimeOffModal').show();
    });

    //Validation
    //when load for update time-off
    if (jQuery('#allday').is(':checked')) {
        jQuery('#start_time').attr("disabled", true);
        jQuery('#end_time').attr("disabled", true);
    }

    //repeat
    var repeat = jQuery("#repeat").val();

    if (repeat == 'N') {
        jQuery('#start_date_tr').hide();
        jQuery('#end_date_tr').hide();
        jQuery('#re_days_tr').hide();
        jQuery('#re_weeks_tr').hide();
        jQuery('#re_biweeks_tr').hide();
        jQuery('#re_months_tr').hide();
        jQuery('#event_date_tr').show();
    }

    if (repeat == 'PD') {
        jQuery('#start_date_tr').show();
        jQuery('#end_date_tr').show();
        jQuery('#re_days_tr').hide();
        jQuery('#re_weeks_tr').hide();
        jQuery('#re_biweeks_tr').hide();
        jQuery('#re_months_tr').hide();
        jQuery('#event_date_tr').hide();
    }

    if (repeat == 'D') {
        jQuery('#event_date_tr').show();
        jQuery('#start_date_tr').hide();
        jQuery('#end_date_tr').hide();
        jQuery('#re_days_tr').show();
        jQuery('#re_weeks_tr').hide();
        jQuery('#re_biweeks_tr').hide();
        jQuery('#re_months_tr').hide();
    }

    if (repeat == 'W') {
        jQuery('#start_date_tr').hide();
        jQuery('#end_date_tr').hide();
        jQuery('#re_days_tr').hide();
        jQuery('#re_weeks_tr').show();
        jQuery('#re_biweeks_tr').hide();
        jQuery('#re_months_tr').hide();
        jQuery('#event_date_tr').show();
    }

    if (repeat == 'BW') {
        jQuery('#start_date_tr').hide();
        jQuery('#end_date_tr').hide();
        jQuery('#re_days_tr').hide();
        jQuery('#re_weeks_tr').hide();
        jQuery('#re_biweeks_tr').show();
        jQuery('#re_months_tr').hide();
        jQuery('#event_date_tr').show();
    }

    if (repeat == 'M') {
        jQuery('#start_date_tr').hide();
        jQuery('#end_date_tr').hide();
        jQuery('#re_days_tr').hide();
        jQuery('#re_weeks_tr').hide();
        jQuery('#re_biweeks_tr').hide();
        jQuery('#re_months_tr').show();
        jQuery('#event_date_tr').show();
    }


    // all day event check
    jQuery('#allday').click(function(){
        if(jQuery(this).is(':checked')) {
            jQuery('#start_time').attr("disabled", true);
             hasST = 1;
            jQuery('#end_time').attr("disabled", true);
             hasET = 1;
        } else {
            jQuery('#start_time').attr("disabled", false);
             hasST = 0;
            jQuery('#end_time').attr("disabled", false);
             hasET = 0;
        }
    });

    //ON-FORM SUBMIT - start/end times and dates
    jQuery('#create').click(function() {
        jQuery(".error").hide();
        //name
        var nameVal = jQuery("#name").val();
        if(nameVal == '') {
            jQuery("#name").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
            return false;
        }

        //if allday is not checked then check start/ent time required
        if (!jQuery('#allday').is(':checked')) {
            //start time
            var start_time = jQuery("#start_time").val();
            if(start_time == '') {
                jQuery("#start_time").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }

            //end time
            var end_time = jQuery("#end_time").val();
            if(end_time == '') {
                jQuery("#end_time").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }

            if(start_time == end_time) {
                jQuery("#start_time").after('<span class="error"><br><strong><?php _e("Start-time and End-time cant be equal.",'appointzilla'); ?></strong></span>');
                jQuery("#end_time").after('<span class="error"><br><strong><?php _e("Start-time and End-time cant be equal.",'appointzilla'); ?></strong></span>');
                return false;
            }

            //convert both time into timestamp
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
        }


        //date
        var event_date = jQuery("#event_date").val();
        if(event_date == '') {
            jQuery("#event_date").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
            return false;
        }

        //repeat
        var repeat = jQuery("#repeat").val();

        // PARTICULAR DATES
        if(repeat == 'PD'){
            var start_date = jQuery("#start_date").val();
            if(start_date == '') {
                jQuery("#start_date").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
            var end_date = jQuery("#end_date").val();
            if(end_date == '') {
                jQuery("#end_date").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //DAILY
        if(repeat == 'D'){
            var re_days = jQuery("#re_days").val();
            if(re_days == '') {
                jQuery("#re_days").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //WEEKLY
        if(repeat == 'W'){
            var re_weeks = jQuery("#re_weeks").val();
            if(re_weeks == '')
            {
                jQuery("#re_weeks").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //BiWEEKLY
        if(repeat == 'BW'){
            var re_biweeks = jQuery("#re_biweeks").val();
            if(re_biweeks == '') {
                jQuery("#re_biweeks").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //MONTHLY
        if(repeat == 'M'){
            var re_months = jQuery("#re_months").val();
            if(re_months == '') {
                jQuery("#re_months").after('<span class="error"><br><strong><?php _e('This field required.','appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //assign staff
        var staff = jQuery("#staff").val();
        if(!staff) {
            jQuery("#staff").after('<span class="error"><br><strong><?php _e('Assign any staff.','appointzilla'); ?></strong></span>');
            return false;
        }

    });

});

<?php
if($TimeFormat == 'h:i') { $TOTimePickerFormat = "hh:mm TT"; $Tflag = 'true'; }
if($TimeFormat == 'H:i') { $TOTimePickerFormat = "hh:mm"; $Tflag = 'false'; }
?>

jQuery(function() {

    //load date and time picker
    jQuery('#start_time').timepicker({
        ampm:  <?php echo $Tflag; ?>,
        timeFormat: '<?php echo $TOTimePickerFormat; ?>',
        stepMinute: 5,
    });

    jQuery('#end_time').timepicker({
        ampm:  <?php echo $Tflag; ?>,
        timeFormat: '<?php echo $TOTimePickerFormat; ?>',
        stepMinute: 5,
    });

    jQuery('#start_date').datepicker({
        minDate: 0,
        dateFormat: 'dd-mm-yy',
    });

    jQuery('#end_date').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });

    jQuery('#event_date').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });

});

function repeatday() {
    var repete = jQuery("#repeat").val();
    if(repete=="PD"){ showPD();	}
    if(repete=="N")	{ hideAll(); }
    if(repete=="D")	{ showDaily();}
    if(repete=="W")	{ showWeekly();	}
    if(repete=="BW"){ showBiWeekly();	}
    if(repete=="M")	{ showMonthly(); }
}


function hideAll() {
    jQuery('#start_date_tr').hide();
    jQuery('#end_date_tr').hide();
    jQuery('#re_days_tr').hide();
    jQuery('#re_weeks_tr').hide();
    jQuery('#re_biweeks_tr').hide();
    jQuery('#re_months_tr').hide();
    jQuery('#event_date_tr').show();
}

function showPD() {
    jQuery('#start_date_tr').show();
    jQuery('#end_date_tr').show();
    jQuery('#re_days_tr').hide();
    jQuery('#re_weeks_tr').hide();
    jQuery('#re_biweeks_tr').hide();
    jQuery('#re_months_tr').hide();
    jQuery('#event_date_tr').hide();
}

function showWeekly() {
    jQuery('#start_date_tr').hide();
    jQuery('#end_date_tr').hide();
    jQuery('#re_days_tr').hide();
    jQuery('#re_weeks_tr').show();
    jQuery('#re_biweeks_tr').hide();
    jQuery('#re_months_tr').hide();
    jQuery('#event_date_tr').show();
}

function showBiWeekly() {
    jQuery('#start_date_tr').hide();
    jQuery('#end_date_tr').hide();
    jQuery('#re_days_tr').hide();
    jQuery('#re_weeks_tr').hide();
    jQuery('#re_biweeks_tr').show();
    jQuery('#re_months_tr').hide();
    jQuery('#event_date_tr').show();
}

function showMonthly() {
    jQuery('#start_date_tr').hide();
    jQuery('#end_date_tr').hide();
    jQuery('#re_days_tr').hide();
    jQuery('#re_weeks_tr').hide();
    jQuery('#re_biweeks_tr').hide();
    jQuery('#re_months_tr').show();
    jQuery('#event_date_tr').show();
}

function showDaily() {
    jQuery('#start_date_tr').hide();
    jQuery('#end_date_tr').hide();
    jQuery('#re_days_tr').show();
    jQuery('#re_weeks_tr').hide();
    jQuery('#re_biweeks_tr').hide();
    jQuery('#re_months_tr').hide();
    jQuery('#event_date_tr').show();
}
</script>

<!--time-picker js -->
<script src="<?php echo plugins_url('/timepicker-assets/js/jquery-1.7.2.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui-timepicker-addon.js', __FILE__); ?>" type="text/javascript"></script>