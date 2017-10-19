<!---loading second modal form ajax return code and time slot calculation--->
<script>
function time_slot_box(ui)
{
	var alltimeslot = document.getElementById("time_slot_box");
	var spans = alltimeslot.getElementsByTagName("span");
	
	for(i=0;i<spans.length;i++)
	{
	  spans[i].removeAttribute("class");
	}
	
	ui.setAttribute("class","selected");
	ui.previousElementSibling.checked=true;
}
</script>
<style>
	#time_slot_box span,#time_slot_box del{
		display:inline;
		font-size:15px;
	}
	#time_slot_box div:hover{
		cursor: pointer;
	}
	#staff{
		margin:15px 0;
	}
</style>
<?php if( isset($_GET['ServiceId']) && isset($_GET['AppDate']) && isset($_GET['StaffId']) ) { ?>
    <div id="AppSecondModalData">
        <div id="SecondModal" style="z-index:10000;">
            <form action="" method="post" name="secondmodal" id="secondmodal">
                <input name="ServiceId" id="ServiceId" type="hidden" value="<?php if(isset($_GET['ServiceId'])) { echo $_GET['ServiceId']; } ?>" />
                <input name="StaffId" id="StaffId" type="hidden" value="<?php if(isset($_GET['StaffId'])) { echo $_GET['StaffId']; } ?>" />
                <input name="AppDate" id="AppDate" type="hidden" value="<?php if(isset($_GET['AppDate'])) { echo date('d-m-Y', strtotime($_GET['AppDate'])); } ?>" />

                <div>
                    <div class="apcal_alert apcal_alert-info">
                        <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p>
                        <?php _e('Step 2. Select Service Time', 'appointzilla'); ?>
                    </div>
                </div><!--end modal-info-->
                <div style="padding: 0px;">
                    <div id="timesloatbox" class="apcal_alert apcal_alert-block" style="float:left; height:auto; width:-moz-available; padding-right: 15px;">
                        <?php // time-slots calculation
                        global $wpdb;
                        $ToadyBusinessClose = 1; //open
                        $ToadyStaffNotAvailable = 1; //available
                        $AppointmentDate = date("Y-m-d", strtotime($_GET['AppDate']));
                        $ServiceId = $_GET['ServiceId'];
                        $StaffId =  $_GET['StaffId'];

                        // include appointzilla class file
                        require_once('appointzilla-class.php');

                        $AppointZilla = new Appointzilla();
                        $BusinessHours = $AppointZilla->GetBusiness($AppointmentDate,$StaffId);
                        if($BusinessHours['Biz_start_time'] == 'none' || $BusinessHours['Biz_end_time'] == 'none') {
                            $ToadyBusinessClose = 0;
                        } else {
                            $Biz_start_time = $BusinessHours['Biz_start_time'];
                            $Biz_end_time = $BusinessHours['Biz_end_time'];
                        }

                        if($ToadyBusinessClose) {
                            $TodaysAllDayEvent = $AppointZilla->CheckAllDayEvent($AppointmentDate,$StaffId);
                            if($TodaysAllDayEvent) {
                                $AllDayEvent = 1;
                            } else {
                                $AllDayEnableSlots = 0;
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

                                $DateFormat = get_option('apcal_date_format');
                                echo "<div class='apcal_alert apcal_alert-info'>".__('Select Time For', 'appointzilla')."<strong> '".ucwords($ServiceData->name)."' </strong>".__('On', 'appointzilla')." <strong> '".date($DateFormat, strtotime($AppointmentDate))."'</strong> ".__('With', 'appointzilla')." <strong>'".ucwords($StaffData->name)."'</strong></div>";

                                $DisableSlotsTimes = $AppointZilla->TimeSlotCalculation($AppointmentDate, $StaffId, $ServiceId, $Biz_start_time, $Biz_end_time);
                                $start = strtotime($Biz_start_time);
                                $end = strtotime($Biz_end_time);

                                //user Define time slot create
                                $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
                                $BookingUserTimeSlot = $AllCalendarSettings['booking_user_timeslot'];

                                if(isset($BookingUserTimeSlot)) {
                                    $UserTimeSlot = $BookingUserTimeSlot;
                                } else {
                                    $UserTimeSlot = 30;
                                }

                                for( $i = $start; $i < $end; $i += (60*$UserTimeSlot)) {
                                    $AllSlotTimesList_User[] = date('h:i A', $i);
                                }

                                //if($ServiceDuration < 30) $ServiceDuration = $ServiceDuration; else $ServiceDuration = 30;
                                for( $i = $start; $i < $end; $i += (60*5)) {
                                    $AllSlotTimesList[] = date('h:i A', $i);
                                } ?>
                                <div style="float: left;" class="apcal_alert apcal_alert-block" id="time_slot_box">
                                <?php
                                //start time list
                                if($TimeFormat == "h:i") $SlotTimeFormat = "h:i A"; else $SlotTimeFormat = "H:i";

                                foreach($AllSlotTimesList as $Single) {
                                    if(in_array($Single, $DisableSlotsTimes)) {
                                        // disable slots
                                        $Disable[] = $Single;   $AllDayDisableSlots = 1;
                                    } else {
                                        // enable slots
                                        $Enable[] = $Single;    $AllDayEnableSlots = 1;
                                    }
                                }// end foreach


                                //check capacity booking on this service
                                /*if($ServiceData->capacity)
                                {
                                    echo "<p align=center><strong>";
                                    _e('Capacity Per Time Slot:', 'appointzilla'); echo "</strong> ".$ServiceData->capacity;
                                    echo "</p>";
                                    $CapecityEnable = 'yes';
                                }*/

                                // after last intersecting - // Show All Enable Time Slot
                                foreach($AllSlotTimesList_User as $Single)  {
                                    //echo $StatTime = date("h:i A", strtotime($Single)); echo "<br>";
                                    if(isset($Enable)) {
                                        if(in_array($Single, $Enable)) {
                                            // enable slots	?>
                                            <div style="width:90px; float:left; padding:2px; display:inline-block; margin:0 10px;">
                                            <?php  $removesp = str_replace(" ", "", "$Single");	$removecln = str_replace(":", "", "$removesp");
                                            $removecln ="H".$removecln; ?>
                                            <input name="start_time" id="start_time" type="radio"  style="margin: 0px 0 0; vertical-align: middle;" onclick="highlightsradio('<?php echo $removecln; ?>')" value="<?php echo $Single; ?>"/>&nbsp;<span id="<?php echo $removecln; ?>" onclick="time_slot_box(this)"><strong><strong><?php echo date($SlotTimeFormat, strtotime("$Single")); ?></strong></strong></span>
                                            </div><?php
                                        } else {
                                            //disable slots
                                            //ckeck in appointment table if this time occupied equla to capacity
                                            //(use $AppointmentDate $ServiceId $StaffId $Single(as start_time)
                                            /*global $wpdb;
                                            $AppointmentTable = $wpdb->prefix . "ap_appointments";
                                            $StartTime = date("h:i A", strtotime($Single));
                                            $GetTotalBooking = $wpdb->get_results("SELECT * FROM `$AppointmentTable` WHERE `service_id` ='$ServiceId' AND `staff_id` ='$StaffId' AND `start_time` LIKE '$StartTime' AND `date` = '$AppointmentDate' AND `status` = 'approved' ");
                                            if(count($GetTotalBooking) < $ServiceData->capacity)
                                            {
                                                //keep enable capacity not occupied
                                                ?>
                                                <div style="width:90px; float:left; padding:2px;">
                                                    <input name="start_time" id="start_time" type="radio" value="<?php echo $Single; ?>" style="margin-left:0px;"/>&nbsp;<strong><?php echo date($SlotTimeFormat, strtotime("$Single")); ?></strong>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {*/
                                            // disable it capacity occupied
                                            ?>
                                            <div style="width:90px; float:left; padding:2px; display:inline-block; margin:0 10px;">
                                                <input name="start_time" id="start_time" type="radio" disabled="disabled" value="<?php echo $Single; ?>" style="margin: 0px 0 0; vertical-align: middle;"/>&nbsp;<strong><del><?php echo date($SlotTimeFormat, strtotime("$Single")); ?></del></strong>
                                            </div>
                                            <?php
                                            //}
                                        }
                                    }// end of enable isset
                                }// end foreach

                                unset($DisableSlotsTimes);
                                unset($AllSlotTimesList);
                            } // end else ?>
                            </div>
                            <!---time slot list end--->

                            <div id="apcalerrormsgs" style="float:left; width:100%; padding-right: 40px;"><!--back button and message box-->
                            <?php
                            if($AllDayEvent) {
                                echo "<p align='center' class='apcal_alert apcal_alert-error'><strong>".__('Sorry! Today selected staff is not available.', 'appointzilla')."</strong></p>";
                                echo "<button type='button' class='apcal_btn' id='back1' name='back1' onclick='loadfirstmodal()'><i class='icon-arrow-left'></i> ".__('Back', 'appointzilla')."</button>";
                            }

                            if($AllDayEvent == 0 && $AllDayDisableSlots == 1 && $AllDayEnableSlots == 0) {
                                echo "<p align=center class='apcal_alert apcal_alert-error'><strong>".__('Sorry! Today all appointments has been booked with selected staff.', 'appointzilla')."</strong></p>";
                                echo "<button type='button' class='apcal_btn' id='back1' name='back1' onclick='loadfirstmodal()'><i class='icon-arrow-left'></i>  ".__('Back', 'appointzilla')."</button>";
                            }

                            if($AllDayEvent == 0 && $AllDayEnableSlots == 1) { ?>	<br/>
                                <div id="buttondiv" align="center">
                                    <button type="button" class="apcal_btn" id="back1" name="back1" onclick="loadfirstmodal()"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></button>
                                    <button type="button" class="apcal_btn" id="next2" name="next2" onclick="loadthirdmodal()"><?php _e('Next', 'appointzilla'); ?> <i class="icon-arrow-right"></i></button>
                                </div>

                                <div id="loading" align="center" style="display:none;"><?php _e('Loading...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div><?php
                            }// end of else ?>
                            </div><?php
                        } else {
                            if($ToadyBusinessClose == 0)
                            {
                                echo "<p><strong>".__('Sorry! Today selected staff is not available.', 'appointzilla')."</strong></p><br>";
                            }
                            if($ToadyStaffNotAvailable == 0)
                            {
                                echo "<p><strong>".__('Sorry! Today this staff is not available for booking.', 'appointzilla')."</strong></p><br>";
                            }
                            echo "<button type='button' class='apcal_btn' value='' id='back1' name='back1' onclick='loadfirstmodal()'><i class='icon-arrow-left'></i> ".__('Back', 'appointzilla')."</button>";
                        } ?>
                    </div>
                </div>
            </form>
        </div>
    </div><?php //end modal-body
} ?>