<!---loading second modal form ajax return code and time slot calculation--->
<?php 
// time-slots calculation
global $wpdb;
$ToadyBusinessClose = 1; //open
$ToadyStaffNotAvailable = 1; //available
$AppointmentDate = date("Y-m-d", strtotime($_GET['AppDate']));
$ServiceId = $_GET['ServiceId'];
$StaffId =  $_GET['StaffId'];

// include appointzilla class file
require_once('appointzilla-class.php');
$AppointZilla = new Appointzilla();

$BusinessHours = $AppointZilla->GetBusiness($AppointmentDate, $StaffId);
if($BusinessHours['Biz_start_time'] == 'none' || $BusinessHours['Biz_end_time'] == 'none') {
    $ToadyBusinessClose = 0;
} else {
    $Biz_start_time = $BusinessHours['Biz_start_time'];
    $Biz_end_time = $BusinessHours['Biz_end_time'];
}

if($ToadyBusinessClose) {
    $TodaysAllDayEvent = $AppointZilla->CheckAllDayEvent($AppointmentDate, $StaffId);

    if($TodaysAllDayEvent) {
        $AllDayEvent = 1;
    } else {
        $AllDayEableSlots = 0;
        $AllDayDisableSlots = 0;
        $AllDayEvent = 0;

        //get service details
        $ServiceTableName = $wpdb->prefix."ap_services";
        $FindService_sql = "SELECT `name`, `duration`, `capacity` FROM `$ServiceTableName` WHERE `id` = '$ServiceId'";
        $ServiceData = $wpdb->get_row($FindService_sql, OBJECT);
        $ServiceDuration = $ServiceData->duration;

        //get staff details
        $StaffTableName = $wpdb->prefix."ap_staff";
        $StaffData = $wpdb->get_row("SELECT `name` , `staff_hours` FROM `$StaffTableName` WHERE `id` = '$StaffId'", OBJECT);

        echo "<div class='alert alert-info'>".__('Select Time For', 'appointzilla')."<strong> '".ucwords($ServiceData->name)."' </strong>".__('On', 'appointzilla')." <strong> ".date("F jS, l", strtotime($AppointmentDate))."</strong> ".__('With', 'appointzilla')." <strong>'".ucwords($StaffData->name)."'</strong></div><hr/>";

        $DisableSlotsTimes = $AppointZilla->TimeSlotCalculation($AppointmentDate, $StaffId, $ServiceId, $Biz_start_time, $Biz_end_time);

        $start = strtotime($Biz_start_time);
        $end = strtotime($Biz_end_time);

        //if($ServiceDuration < 30) $ServiceDuration = $ServiceDuration; else $ServiceDuration = 30;
        //for( $i = $start; $i < $end; $i += (60*$ServiceDuration))
        for( $i = $start; $i < $end; $i += (60*15)) {
            $AllSlotTimesList[] = date('h:i A', $i);
        } ?>
            <!--start time list-->
            <div class="alert alert-block" id="time_slot_box">
            <?php

            /*//check capacity booking on this service
            if($ServiceData->capacity) {
                echo "<p align=center><strong>";
                //_e('Capacity Per Time Slot:', 'appointzilla'); echo "</strong> ".$ServiceData->capacity;
                _e('Capacity Per Time Slot:', 'appointzilla'); echo "</strong> ";
                _e('Unlimited For Admin', 'appointzilla');
                echo "</p>";
                $CapecityEnable = 'yes';
            }*/

            if($TimeFormat == "h:i") $SlotTimeFormat = "h:i A";
            else $SlotTimeFormat = "H:i";

            echo "<strong>".__('Start Time:', 'appointzilla')." </strong><br />&nbsp;<select id='start_time' name='start_time' onchange='starttimechange()' style='width:140px;'>";
                echo "<option value='-1' selected>" . __('Select Start Time', 'appointzilla') . "</option>";
            foreach($AllSlotTimesList as $Single) {
                if(in_array($Single, $DisableSlotsTimes)) {
                    // disable slots
                    /*//check in appointment table if this time occupied equla to capacity
                    //(use $AppointmentDate $ServiceId $StaffId $Single(as start_time)
                    global $wpdb;
                    $AppointmentTable = $wpdb->prefix . "ap_appointments";
                    $StartTime = date("h:i A", strtotime($Single));
                    $GetTotalBooking = $wpdb->get_results("SELECT * FROM `$AppointmentTable` WHERE `service_id` ='$ServiceId' AND `staff_id` ='$StaffId' AND `start_time` LIKE '$StartTime' AND `date` = '$AppointmentDate'");
                    if(count($GetTotalBooking) < 9999)
                    {
                        //keep enable capacity not occupied
                        echo "<option value='$Single' >".date($SlotTimeFormat, strtotime($Single))."</option>";
                        $Enable[] = $Single;
                    }
                    else
                    {*/
                        // disable it capacity occupied
                        echo "<option value='$Single' disabled>".date($SlotTimeFormat, strtotime($Single))."</option>";
                        $Disable[] = $Single;
                    //}
                } else {
                    // enable slots
                    echo "<option value='$Single' >".date($SlotTimeFormat, strtotime($Single))."</option>";
                    $Enable[] = $Single;
                }
            }// end foreach
            echo "</select>&nbsp;&nbsp;";
            ?>
            <div style=" display:none" id="start_time_error"><span class="error"><strong><?php _e('Invalid Start Time. Start Time should be less than End Time.', 'appointzilla'); ?></strong></sapn></div>
            <br />

            <?php
            // end time list

            echo "<strong>".__('End Time: ', 'appointzilla')."</strong><br />&nbsp;<select id='end_time' name='end_time' onchange='endtimechange()' style='width:140px;'>";
                echo "<option value='-1' selected>" . __('Select End Time', 'appointzilla') . "</option>";
            foreach($AllSlotTimesList as $Single) {
                if(in_array($Single, $DisableSlotsTimes)) {
                    // disable slots
                    //ckeck in appointment table if this time occupied equla to capacity
                    //(use $AppointmentDate $ServiceId $StaffId $Single(as start_time)
                    /*global $wpdb;
                    $AppointmentTable = $wpdb->prefix . "ap_appointments";
                    $StartTime = date("h:i A", strtotime($Single));
                    $GetTotalBooking = $wpdb->get_results("SELECT * FROM `$AppointmentTable` WHERE `service_id` ='$ServiceId' AND `staff_id` ='$StaffId' AND `start_time` LIKE '$StartTime' AND `date` = '$AppointmentDate' AND `status` = 'approved' ");
                    //echo count($GetTotalBooking);
                    if(count($GetTotalBooking) < 9999) {
                        //keep enable capacity not occupied
                        echo "<option value='$Single' >".date($SlotTimeFormat, strtotime($Single))."</option>";
                        $Enable[] = $Single;
                    } else {*/
                        // disable it capacity occupied
                        echo "<option value='$Single' disabled>".date($SlotTimeFormat, strtotime($Single))."</option>";
                        $Disable[] = $Single;
                    //}
                } else {
                    // enable slots
                    echo "<option value='$Single'>".date($SlotTimeFormat, strtotime($Single))."</option>";
                    $Enable[] = $Single;
                    $AllDayEnableSlots = 1;
                }
            }// end foreach
            echo "</select>";
            ?>
            <div style=" display:none" id="end_time_error"><sapn class="error"><strong><?php _e('Invalid End Time. End Time should be greater than Start Time.', 'appointzilla'); ?></strong></sapn></div>
            <?php
            unset($DisableSlotsTimes);
            unset($AllSlotTimesList);
    } // end else
} ?>