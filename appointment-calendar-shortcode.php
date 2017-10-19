<?php // short-code file with button and big calendar(full-calendar)

add_shortcode( 'APCAL', 'appointment_calendar_shortcode' );
function appointment_calendar_shortcode() {
    //ob_start();
    if(get_locale()) {
        $language = get_locale();
        if($language) { define('L_LANG',$language); }
    }

    $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
    $small_calendar_type = $AllCalendarSettings['small_calendar_type'];
    if($AllCalendarSettings['calendar_start_day'] != '') {
        $CalStartDay = $AllCalendarSettings['calendar_start_day'];
    } else {
        $CalStartDay = 1;
    }

    //disable closed business days on small datepicker
    global $wpdb;
    $ClosedDays = array();
    $StartDate = date("Y-m-d");
	
	$StartDate = date('Y-m-d',strtotime($StartDate . "+1 days"));
    $EndDate = "2035-01-01";

    /*$BusinessHoursTable = $wpdb->prefix . "ap_business_hours";
    $ClosedBusinessDays = $wpdb->get_results("SELECT * FROM `$BusinessHoursTable` WHERE `close` LIKE 'yes'");
    if(count($ClosedBusinessDays)) {
        foreach($ClosedBusinessDays as $ClosedBusinessDay) {
            $ClosedDays[] = ucfirst(substr($ClosedBusinessDay->day, 0, 3));
        }
    }*/

    require_once( plugin_dir_path( __FILE__ ) . 'calendar/tc_calendar.php');
    $CurrentDate = date("Y-m-d", time());
    $DatePicker2 = plugins_url('calendar/', __FILE__);
    $myCalendar = new tc_calendar("date1");
    foreach($ClosedDays as $Day) {
        $myCalendar->disabledDay("$Day");
    }
    $myCalendar->startDate($CalStartDay);
    $myCalendar->setIcon($DatePicker2."images/iconCalendar.gif");
    $myCalendar->setDate(date("d", strtotime($StartDate)), date("m", strtotime($StartDate)), date("Y", strtotime($StartDate)));
    $myCalendar->setPath($DatePicker2);
    $myCalendar->setYearInterval(2035,date('Y'));
    $StartCalendarFrom = date("Y-m-d", strtotime("0 day", strtotime($StartDate)));
    $myCalendar->dateAllow($StartCalendarFrom, $EndDate, false);
    $myCalendar->setOnChange("myChanged()");

    $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";

    global $wpdb;
    $AppointmentTableName = $wpdb->prefix . "ap_appointments";
    $EventTableName = $wpdb->prefix."ap_events";
    $StaffTable =  $wpdb->prefix."ap_staff";

    $current_month_first_date = date("Y-m-01");
    $laod_recurring_from = date("Y-m-d", strtotime("-3 month", strtotime($current_month_first_date)));  //only for recurring app

    //fetch all normal appointments
    $FetchAllApps_sql = "select `name`, `staff_id`, `start_time`, `end_time`, `date` FROM `$AppointmentTableName` WHERE `recurring` = 'no' AND `date` >= '$current_month_first_date' AND `status` != 'cancelled'";

    //fetch all recurring appointments
    $FetchAllRApps_sql = "select * FROM `$AppointmentTableName` WHERE `recurring` = 'yes' AND `date` >= '$current_month_first_date' AND `recurring_st_date` >= '$laod_recurring_from' AND `status` != 'cancelled'";

    //fetch all normal events
    $FetchAllEvent_sql = "select `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` where `repeat` = 'N' AND `start_date` >= '$current_month_first_date' ";

    //fetch all recurring events
    $FetchAllREvent_sql = "select `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` where `repeat` != 'N' AND `start_date` >= '$laod_recurring_from' ";

    if($DateFormat == 'd-m-Y') $CalFormat = 'dd';
    if($DateFormat == 'm-d-Y') $CalFormat = 'dd';
    if($DateFormat == 'Y-m-d') $CalFormat = 'dd'; //coz yy-mm-dd not parsing in a correct date

    //Set Colors: Get Business Hours and open & close days
    $BusinessHoursTable = $wpdb->prefix . "ap_business_hours";
    $AllBusinessHours = $wpdb->get_results("SELECT * FROM `$BusinessHoursTable`");

    $TodayColor = ""; //"#FFFFCC";
    $HeaderColor = ""; //"#E3E3E3";
    $OpenDayColor  = ""; //"#72FE95";
    $CloseDayColor = ""; //"#FF4848";

    $MonColor = $OpenDayColor;
    $TueColor = $OpenDayColor;
    $WedColor = $OpenDayColor;
    $ThrColor = $OpenDayColor;
    $FriColor = $OpenDayColor;
    $SatColor = $OpenDayColor;
    $SunColor = $OpenDayColor;
    foreach($AllBusinessHours as $TodayHours) {
        if($TodayHours->id == 1 & $TodayHours->close == 'yes') { $MonColor = $CloseDayColor; } else { $MonColor = "";}
        if($TodayHours->id == 2 & $TodayHours->close == 'yes') { $TueColor = $CloseDayColor; } else { $TueColor = "";}
        if($TodayHours->id == 3 & $TodayHours->close == 'yes') { $WedColor = $CloseDayColor; } else { $WedColor = "";}
        if($TodayHours->id == 4 & $TodayHours->close == 'yes') { $ThrColor = $CloseDayColor; } else { $ThrColor = "";}
        if($TodayHours->id == 5 & $TodayHours->close == 'yes') { $FriColor = $CloseDayColor; } else { $FriColor = "";}
        if($TodayHours->id == 6 & $TodayHours->close == 'yes') { $SatColor = $CloseDayColor; } else { $SatColor = "";}
        if($TodayHours->id == 7 & $TodayHours->close == 'yes') { $SunColor = $CloseDayColor; } else { $SunColor = "";}
    } ?>

    <style>
     .fc-mon {
        background-color: <?php echo $MonColor; ?>;
     }
     .fc-tue {
        background-color: <?php echo $TueColor; ?>;
     }
     .fc-wed {
        background-color: <?php echo $WedColor; ?>;
     }
     .fc-thu {
        background-color: <?php echo $ThrColor; ?>;
     }
     .fc-fri {
        background-color: <?php echo $FriColor; ?>;
     }
     .fc-sat {
        background-color: <?php echo $SatColor; ?>;
     }
     .fc-sun {
        background-color: <?php echo $SunColor; ?>;
     }
     .fc-today
     {
        background-color: <?php echo $TodayColor; ?>;
     }
     .fc-widget-header{
        background-color:<?php echo $HeaderColor; ?>;
     }

     /* .fc-other-month .fc-day-number { display:none;} */

     .selected {
         outline:1px solid #FF0000; /* Firefox, Opera, Chrome, IE8+ */
         background-color:#FFFF99;
     }

     .error{
         color: #FF0000;
     }

         /*first modal- 2nd div conflicts css*/
     .entry form {
         text-align: left;
     }
    </style>

    <script type='text/javascript'>
    jQuery(document).ready(function() {
        jQuery('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            columnFormat: {
                //month: 'dd/MM/yyyy',
                //week: 'ddd dd/MM/yyyy',
                //day: 'dddd dd/MM/yyyy'
            },
            titleFormat: {
                //month: 'dd-MMM-yyyy',
                //week: "dd-MM-yyyy [ yyyy]{ '&#8212;'[ dd]-MM-yyyy}",
                //day: 'dddd dd-MM-yyyy'
            },
            editable: false,
            weekends: true,
            timeFormat: <?php if($TimeFormat == 'h:i') echo "'h:mmtt{-h:mmtt }'"; else echo "'H:mm{-H:mm }'"; ?>,
            axisFormat: <?php if($TimeFormat == 'h:i') echo "'hh:mm'"; else echo "'HH:mm'"; ?>,
            firstDay: <?php echo $CalStartDay;  ?>,
            slotMinutes: <?php if($AllCalendarSettings['calendar_slot_time'] != '') echo $AllCalendarSettings['calendar_slot_time']; else echo "15"; ?>,
            defaultView: '<?php if($AllCalendarSettings['calendar_view'] != '') echo $AllCalendarSettings['calendar_view']; else echo "month"; ?>',
            minTime: <?php if($AllCalendarSettings['day_start_time'] != '') echo date("G", strtotime($AllCalendarSettings['day_start_time'])); else echo "8"; ?>,
            maxTime: <?php  if($AllCalendarSettings['day_end_time'] != '') echo date("G", strtotime($AllCalendarSettings['day_end_time'])); else echo "20"; ?>,
            monthNames: ["<?php _e("January", "appointzilla"); ?>","<?php _e("February", "appointzilla"); ?>","<?php _e("March", "appointzilla"); ?>","<?php _e("April", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("June", "appointzilla"); ?>","<?php _e("July", "appointzilla"); ?>", "<?php _e("August", "appointzilla"); ?>", "<?php _e("September", "appointzilla"); ?>", "<?php _e("October", "appointzilla"); ?>", "<?php _e("November", "appointzilla"); ?>", "<?php _e("December", "appointzilla"); ?>" ],
            monthNamesShort: ["<?php _e("Jan", "appointzilla"); ?>","<?php _e("Feb", "appointzilla"); ?>","<?php _e("Mar", "appointzilla"); ?>","<?php _e("Apr", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("Jun", "appointzilla"); ?>","<?php _e("Jul", "appointzilla"); ?>","<?php _e("Aug", "appointzilla"); ?>","<?php _e("Sept", "appointzilla"); ?>","<?php _e("Oct", "appointzilla"); ?>","<?php _e("nov", "appointzilla"); ?>","<?php _e("Dec", "appointzilla"); ?>"],
            dayNames: ["<?php _e("Sunday", "appointzilla"); ?>","<?php _e("Monday", "appointzilla"); ?>","<?php _e("Tuesday", "appointzilla"); ?>","<?php _e("Wednesday", "appointzilla"); ?>","<?php _e("Thursday", "appointzilla"); ?>","<?php _e("Friday", "appointzilla"); ?>","<?php _e("Saturday", "appointzilla"); ?>"],
            dayNamesShort: ["<?php _e("Sun", "appointzilla"); ?>","<?php _e("Mon", "appointzilla"); ?>", "<?php _e("Tue", "appointzilla"); ?>", "<?php _e("Wed", "appointzilla"); ?>", "<?php _e("Thur", "appointzilla"); ?>", "<?php _e("Fri", "appointzilla"); ?>", "<?php _e("Sat", "appointzilla"); ?>"],
            buttonText: {
                today: "<?php _e("Today", "appointzilla"); ?>",
                day: "<?php _e("Day", "appointzilla"); ?>",
                week:"<?php _e("Week", "appointzilla"); ?>",
                month:"<?php _e("Month", "appointzilla"); ?>"
            }, <?php
            if($DateFormat == 'd-m-Y') $DPFormat = 'dd-mm-yy';
            if($DateFormat == 'm-d-Y') $DPFormat = 'mm-dd-yy';
            if($DateFormat == 'Y-m-d') $DPFormat = 'yy-mm-dd'; //coz yy-mm-dd not parsing in a correct date ?>
            selectable: true,
            selectHelper: false,
            select: function(start, end, allDay) {

                    var appdate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(start));
                    var appdate2 = jQuery.datepicker.formatDate('dd-mm-yy', new Date(start));
                    var check = jQuery.fullCalendar.formatDate(start,'yyyy-MM-dd');
                    var today = jQuery.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
                    if(check <= today) {
                        // Its a past date
                        alert("<?php _e("Sorry! Appointment cannot be booked for past dates.", "appointzilla"); ?>");
                    } else {
                        // Its a right date
                        jQuery('#appdate').val(appdate);
                        jQuery('#appdate2').val(appdate2);
                        jQuery('#AppFirstModal').show();

                        // date-picker tweaks
                        var i;
                        var startdate = jQuery.datepicker.formatDate('yymm', new Date(start));
                        for(i=1; i<=31; i++) {
                            if(i < 10) i = '0' + i;
                            var nextdate = startdate + i;
                            jQuery('#date1_frame').contents().find('#' + nextdate).removeClass('today select');
                        }
                        var todaydate = jQuery.datepicker.formatDate('yymmdd', new Date());
                        jQuery('#date1_frame').contents().find('#' + todaydate).removeClass('select');
                        var cnvtdate = jQuery.datepicker.formatDate('yymmdd', new Date(start));
                        jQuery('#date1_frame').contents().find('#' + cnvtdate).addClass('today select');
                    }
                },

            events: [
            <?php //Loading Normal Appointments On Calendar Start
                $AllAppointments = $wpdb->get_results($FetchAllApps_sql, OBJECT);
                if($AllAppointments) {
                    foreach($AllAppointments as $single) {
                        $start = date("H, i", strtotime($single->start_time));
                        $end = date("H, i", strtotime($single->end_time));

                        //get staff appointment color code
                        $StaffId = $single->staff_id;
                        $StaffData = $wpdb->get_row("SELECT `color` FROM `$StaffTable` WHERE `id` = '$StaffId'");
                        if(count($StaffData)) {
                            $StaffAppointmentColor = $StaffData->color;
                        } else {
                            $StaffAppointmentColor = "#1fcb4a";
                        }

                        // subtract 1 from month digit coz calendar work on month 0-11
                        $y = date ( 'Y' , strtotime( $single->date ) );
                        $m = date ( 'n' , strtotime( $single->date ) ) - 1;
                        $d = date ( 'd' , strtotime( $single->date ) );
                        $date = "$y-$m-$d";
                        $date = str_replace("-",", ", $date); ?>
                        {
                            title: "<?php _e('Booked', 'appointzilla'); ?>",
                            start: new Date(<?php echo "$date, $start"; ?>),
                            end: new Date(<?php echo "$date, $end"; ?>),
                            allDay: false,
                            backgroundColor : '<?php echo $StaffAppointmentColor; ?>',
                            textColor: 'black',
                        }, <?php
                    }
                }
                //Loading Appointments On Calendar End

                //Loading Recurring Appointments On Calendar Start
                $AllRecurringAppointments = $wpdb->get_results($FetchAllRApps_sql, OBJECT);
                if($AllRecurringAppointments) {
                    foreach($AllRecurringAppointments as $single) {

                        //get staff appointment color code
                        $StaffId = $single->staff_id;
                        $StaffData = $wpdb->get_row("SELECT `color` FROM `$StaffTable` WHERE `id` = '$StaffId'");
                        if(count($StaffData)) {
                            $StaffAppointmentColor = $StaffData->color;
                        } else {
                            $StaffAppointmentColor = "#1fcb4a";
                        }

                        if($single->recurring_type != 'monthly') {
                            $start_time = date("H, i", strtotime($single->start_time));
                            $end_time= date("H, i", strtotime($single->end_time));
                            $start_date = $single->recurring_st_date;
                            $end_date =  $single->recurring_ed_date;

                            //if appointment type then calculate RTC(recutting date calulation)
                            if($single->recurring_type == 'PD')
                            $RDC = 1;
                            if($single->recurring_type == 'daily')
                            $RDC = 1;
                            if($single->recurring_type == 'weekly')
                            $RDC = 7;

                            //calculate all dates
                            $Alldates = array();
                            $st_dateTS = strtotime($start_date);
                            $ed_dateTS = strtotime($end_date);
                            for ($currentDateTS = $st_dateTS; $currentDateTS <= $ed_dateTS; $currentDateTS += (60 * 60 * 24 * $RDC)) {
                                $currentDateStr = date("Y-m-d",$currentDateTS);
                                $AlldatesArr[] = $currentDateStr;

                                // subtract 1 from month digit coz calendar work on month 0-11
                                $y = date ( 'Y' , strtotime( $currentDateStr ) );
                                $m = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                                $d = date ( 'd' , strtotime( $currentDateStr ) );
                                $eachdate = "$y-$m-$d";

                                //change format
                                $eachdate = str_replace("-",", ", $eachdate); ?>
                                {
                                    title: "<?php _e('Booked', 'appointzilla'); ?>",
                                    start: new Date(<?php echo "$eachdate, $start_time"; ?>),
                                    end: new Date(<?php echo "$eachdate, $end_time"; ?>),
                                    allDay: false,
                                    backgroundColor : "<?php echo $StaffAppointmentColor; ?>",
                                    textColor: "",
                                }, <?php
                            }// end of date calculation for
                        } else {
                            $start_time = date("H, i", strtotime($single->start_time));
                            $end_time= date("H, i", strtotime($single->end_time));

                            $start_date = $single->recurring_st_date;
                            $end_date =  $single->recurring_ed_date;

                            $i = 0;
                            do {
                                    $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($start_date)));
                                    // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                    $y = date ( 'Y' , strtotime( $NextDate ) );
                                    $m = date ( 'n' , strtotime( $NextDate ) ) - 1;
                                    $d = date ( 'd' , strtotime( $NextDate ) );
                                    $start_date2 = "$y-$m-$d";
                                    $start_date2 = str_replace("-",", ", $start_date2);     //changing date format
                                    $end_date2 = str_replace("-",", ", $start_date2); ?>
                                    {
                                        title: "<?php _e('Booked', 'appointzilla'); ?>",
                                        start: new Date(<?php echo "$start_date2, $start_time"; ?>),
                                        end: new Date(<?php echo "$end_date2, $end_time"; ?>),
                                        allDay: false,
                                        backgroundColor : "<?php echo $StaffAppointmentColor; ?>",
                                        textColor: "",
                                    }, <?php
                                    $i = $i+1;
                                } while(strtotime($end_date) != strtotime($NextDate));
                        }// end of else

                    } // end of fetching single appointment foreach
                } // end of if
                //Loading Recurring Appointments On Calendar End


                //Loading Events On Calendar Start
                $AllEvents = $wpdb->get_results($FetchAllEvent_sql, OBJECT);
                if($AllEvents) {
                    foreach($AllEvents as $Event) {
                        //convert time foramt H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        //change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);

                        $startdate = $Event->start_date;
                        // subtract 1 from $startdate month digit coz calendar work on month 0-11
                        $y = date ( 'Y' , strtotime( $startdate ) );
                        $m = date ( 'n' , strtotime( $startdate ) ) - 1;
                        $d = date ( 'd' , strtotime( $startdate ) );
                        $startdate = "$y-$m-$d";
                        $startdate = str_replace("-",", ", $startdate); //changing date format

                        $enddate = $Event->end_date;
                        // subtract 1 from $startdate month digit coz calendar work on month 0-11
                        $y2 = date ( 'Y' , strtotime( $enddate ) );
                        $m2 = date ( 'n' , strtotime( $enddate ) ) - 1;
                        $d2 = date ( 'd' , strtotime( $enddate ) );
                        $enddate = "$y2-$m2-$d2";
                        //changing date format
                        $enddate = str_replace("-",", ", $enddate); ?>
                        {
                            title: "<?php echo ucwords($Event->name); ?>",
                            start: new Date(<?php echo "$startdate, $starttime"; ?>),
                            end: new Date(<?php echo "$enddate, $endtime"; ?>),
                            allDay: false,
                            backgroundColor : "#FF7575",
                            textColor: "black",
                        }, <?php
                    }
                }
                //Loading Events On Calendar End

                //Loading Recurring Events On Calendar Start
                $AllREvents = $wpdb->get_results($FetchAllREvent_sql, OBJECT);
                //don't show event on filtering
                if($AllREvents)	{
                    foreach($AllREvents as $Event) {
                        //convert time foramt H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        //change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);
                        $startdate = $Event->start_date;
                        $enddate = $Event->end_date;

                        if($Event->repeat != 'M') {
                            //if event type then calculate RTC(recutting date calulation)
                            if($Event->repeat == 'PD')
                            $RDC = 1;
                            if($Event->repeat == 'D')
                            $RDC = 1;
                            if($Event->repeat == 'W')
                            $RDC = 7;
                            if($Event->repeat == 'BW')
                            $RDC = 14;

                            $Alldates = array();
                            $st_dateTS = strtotime($startdate);
                            $ed_dateTS = strtotime($enddate);
                            for ($currentDateTS = $st_dateTS; $currentDateTS <= $ed_dateTS; $currentDateTS += (60 * 60 * 24 * $RDC)) {
                                $currentDateStr = date("Y-m-d",$currentDateTS);
                                $AlldatesArr[] = $currentDateStr;

                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y = date ( 'Y' , strtotime( $currentDateStr ) );
                                $m = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                                $d = date ( 'd' , strtotime( $currentDateStr ) );
                                $startdate = "$y-$m-$d";
                                $startdate = str_replace("-",", ", $startdate); //changing date format

                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y2 = date ( 'Y' , strtotime( $currentDateStr ) );
                                $m2 = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                                $d2 = date ( 'd' , strtotime( $currentDateStr ) );
                                $enddate = "$y2-$m2-$d2";

                                $enddate = str_replace("-",", ", $enddate);     //changing date format
                                ?>
                                {
                                    title: "<?php echo ucwords($Event->name); ?>",
                                    start: new Date(<?php echo "$startdate, $starttime"; ?>),
                                    end: new Date(<?php echo "$enddate, $endtime"; ?>),
                                    allDay: false,
                                    backgroundColor : "#FF7575",
                                    textColor: "black",
                                }, <?php
                            }
                        } else {
                            $i = 0;
                            do {
                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($startdate)));
                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y = date ( 'Y' , strtotime( $NextDate ) );
                                $m = date ( 'n' , strtotime( $NextDate ) ) - 1;
                                $d = date ( 'd' , strtotime( $NextDate ) );
                                $startdate2 = "$y-$m-$d";
                                $startdate2 = str_replace("-",", ", $startdate2);   //changing date format
                                $enddate2 = str_replace("-",", ", $startdate2); ?>
                                    {
                                        title: "<?php echo ucwords($Event->name); ?>",
                                        start: new Date(<?php echo "$startdate2, $starttime"; ?>),
                                        end: new Date(<?php echo "$enddate2, $endtime"; ?>),
                                        allDay: false,
                                        backgroundColor : "#FF7575",
                                        textColor: "black",
                                    }, <?php
                                $i = $i+1;
                            } while(strtotime($enddate) != strtotime($NextDate));
                        }// enf of else
                    }// end of foreach
                }// end of if check
                //Loading Recurring Events On Calendar End
                ?>
                                    {
                                    }
                ]
        });
		
		jQuery('.fc-button-next span').click(function(){
		var date = jQuery("#calendar").fullCalendar('getDate');
		var month_int = date.getMonth();
		var d = new Date();
		var n = d.getMonth();
			n = n+3;
			if(n == month_int){
		   //alert('Hello');
			jQuery(".fc-corner-right").addClass('fc-state-disabled');
			}
		});
		
		
		jQuery('.fc-button-prev span').click(function(){
		
			jQuery(".fc-corner-right").removeClass('fc-state-disabled');
			
		});
        //Modal Form Works
        //show first modal
        jQuery('#addappointment').click(function(){
            jQuery('#AppFirstModal').show();
        });
        //hide modal
        jQuery('#close').click(function(){
            jQuery('#AppFirstModal').hide();
        });

        <?php if($DateFormat == 'd-m-Y') $DPFormat = 'dd-mm-yy';
        if($DateFormat == 'm-d-Y') $DPFormat = 'mm-dd-yy';
        if($DateFormat == 'Y-m-d') $DPFormat = 'yy-mm-dd'; //coz yy-mm-dd not parsing in a correct date

        if($small_calendar_type == "jquery") {
        ?>
            //jQuery UI date picker on modal for
            document.addnewappointment.appdate.value = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date());
            jQuery(function(){
                jQuery("#datepicker").datepicker({
                    inline: true,
                    minDate: 0,
                    altField: '#alternate',
                    firstDay: <?php if($AllCalendarSettings['calendar_start_day'] != '') echo $AllCalendarSettings['calendar_start_day']; else echo "0";  ?>,
                    //beforeShowDay: unavailable,
                    onSelect: function(dateText, inst) {
                        var dateAsString = dateText;
                        var seleteddate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(dateAsString));
                        var seleteddate2 = jQuery.datepicker.formatDate('dd-mm-yy', new Date(dateAsString));
                        document.addnewappointment.appdate.value = seleteddate;
                        document.addnewappointment.appdate2.value = seleteddate2;
                    },
                });
                //jQuery( "#datepicker" ).datepicker( jQuery.datepicker.regional[ "af" ] );
            });
        <?php } ?>

        //AppFirstModal Validation
        jQuery('#next1').click(function(){
            jQuery(".error").hide();
            if(jQuery('#service').val() == 0)
            {
                jQuery("#service").after("<span class='error'><br><strong><?php _e('Select Any Service.', 'appointzilla'); ?></strong><br></span>");
                return false;
            }
        });

        //back button show first modal
        jQuery('#back').click(function(){
            jQuery('#AppFirstModal').show();
            jQuery('#AppSecondModal').hide();
        });

    });

    //Modal Form Works
    function LoadSecondModal() {
        var ServiceId = jQuery('#servicelist').val();
        var AppDate = jQuery('#appdate2').val();
        var StaffId = jQuery('#stafflist').val();
        var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId;
        jQuery('#loading1').show(); // loading button onclick next1 at first modal
        jQuery('#next1').hide();    //hide next button
        jQuery.ajax({
            dataType : 'html',
            type: 'GET',
            url : location.href,
            cache: false,
            data : SecondData,
            complete : function() {  },
            success: function(data) {
                data = jQuery(data).find('div#AppSecondModalData');
                jQuery('#loading1').hide();
                jQuery('#AppFirstModal').hide();
                jQuery('#AppSecondModal').show();
                jQuery('#AppSecondModal').html(data);
            }
        });
    }

    //load first modal on click back1
    function LoadFirstModal() {
        jQuery('#AppSecondModal').hide()
        jQuery('#AppFirstModal').show();
        jQuery('#next1').show();
    }

    //load second modal on back2 click
    function LoadSecondModal2() {
        jQuery('#AppThirdModal').hide();
        jQuery('#buttondiv').show();
        jQuery('#AppSecondModal').show();
    }

   //on new user button click
   function NewUserBtn() {
        jQuery('#new-user-div').show();
        jQuery('#existing-user-div').hide();
       jQuery('#check-email-result-div').hide();
   }

   //on existing user button click
   function ExistingUserBtn() {
       jQuery('#new-user-div').hide();
       jQuery('#existing-user-div').show();
       jQuery('#check-email-div-form').show();
   }

   //load third modal on-click next2
   function LoadThirdModal() {
       //validation on second modal form
       jQuery('.error').hide();
       var ServiceId = jQuery('#ServiceId').val();
       var AppDate = jQuery('#AppDate').val();
       var StaffId = jQuery('#StaffId').val();
       var Start_Time = jQuery('input[name=start_time]:radio:checked').val();
       if(!Start_Time) {
           jQuery("#time_slot_box").after("<span style='width:auto; margin-left:5%;' class='error'><strong><?php _e('Select any time.', 'appointzilla'); ?></strong></span>");
           return false;
       }
       var ThirdData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + Start_Time ;
       jQuery('#buttondiv').hide();
       jQuery('#loading').show();
       jQuery.ajax({
           dataType : 'html',
           type: 'GET',
           url : location.href,
           cache: false,
           data : ThirdData,
           complete : function() {  },
           success: function(data) {
               data = jQuery(data).find('div#AppThirdModalData');
               jQuery('#loading').hide();
               jQuery('#AppSecondModal').hide();
               jQuery('#AppThirdModal').show();
               jQuery('#AppThirdModal').html(data);
           }
       });
   }


   //load forth final modal for confirm appointment
   function CheckValidation(UserType) {

       jQuery('.error').hide();
       var ServiceId = jQuery('#ServiceId').val();
       var AppDate = jQuery('#AppDate').val();
       var StaffId = jQuery('#StaffId').val();
       var StartTime =  jQuery('#StartTime').val();

       /**
        * new user booking case
        */
       if(UserType == "NewUser") {
           <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
           var ClientUserName = jQuery("#client-username").val();
           var ClientPassword = jQuery("#client-password").val();
           var ClientConfirmPassword = jQuery("#client-confirm-password").val();
           <?php } ?>
           var ClientEmail = jQuery("#client-email").val();
           var ClientFirstName = jQuery("#client-first-name").val();
           var ClientLastName = jQuery("#client-last-name").val();
           var ClientPhone = jQuery("#client-phone").val();
           var ClientSi = jQuery("#client-si").val();

           <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
           //client username
           if (ClientUserName == "") {
               jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Username required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {

               if(ClientUserName.length < 6) {
                   jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Choose a strong username.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
               var Res = isNaN(ClientUserName);
               if(Res == false) {
                   jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid username.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client password
           if (ClientPassword == "") {
               jQuery("#client-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Password required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {

               if(ClientPassword.length < 6) {
                   jQuery("#client-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Choose a strong password.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client confirm password
           if (ClientConfirmPassword == "") {
               jQuery("#client-confirm-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Confirm password required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               if(ClientConfirmPassword != ClientPassword) {
                   jQuery("#client-confirm-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Confirm password do not match', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }
           <?php } ?>

           //client email
           var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
           if (ClientEmail == "") {
               jQuery("#client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               if(regex.test(ClientEmail) == false ) {
                   jQuery("#client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client first name
           if (ClientFirstName == "") {
               jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('First name required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               var Res = isNaN(ClientFirstName);
               if(Res == false) {
                   jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid first name.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
               var NameRegx = /^[a-zA-Z0-9- ]*$/;
               if(NameRegx.test(ClientFirstName) == false) {
                   jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client last name
           if (ClientLastName == "") {
               jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Last name required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               var Res = isNaN(ClientLastName);
               if(Res == false) {
                   jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid last name.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
               var NameRegx = /^[a-zA-Z0-9- ]*$/;
               if(NameRegx.test(ClientLastName) == false) {
                   jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client phone
           if (ClientPhone == "") {
               jQuery("#client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Phone required. <br>Only Numbers 1234567890.", "appointzilla"); ?></strong></span>");
               return false;
           } else {
               var ClientPhoneRes = isNaN(ClientPhone);
               if(ClientPhoneRes == true) {
                   jQuery("#client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Invalid phone. <br>Numbers only: 1234567890.", "appointzilla"); ?></strong></span>");
                   return false;
               }
           }

           var PostData1 = "Action=BookAppointment"+ "&ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + StartTime;
           <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
           var PostData2 = "&UserType=" + UserType + "&ClientUserName="+ ClientUserName + "&ClientPassword=" +ClientPassword + "&ClientEmail=" + ClientEmail;
           <?php } else { ?>
           var PostData2 = "&UserType=" + UserType + "&ClientEmail=" + ClientEmail;
           <?php } ?>
           var PostData3 =  "&ClientFirstName=" + ClientFirstName + "&ClientLastName=" + ClientLastName + "&ClientPhone=" + ClientPhone + "&ClientNote=" + ClientSi;
           var PostData = PostData1 + PostData2 + PostData3;

           jQuery('#new-user-form-btn-div').hide();
           jQuery('#new-user-form-loading-img').show();
       }

       /**
        * existing user booking case
        */
       if(UserType == "ExUser") {

           var ClientEmail = jQuery("#ex-client-email").val();
           var ClientFirstName = jQuery("#ex-client-first-name").val();
           var ClientLastName = jQuery("#ex-client-last-name").val();
           var ClientPhone = jQuery("#ex-client-phone").val();
           var ClientSi = jQuery("#ex-client-si").val();

           //client email
           var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
           if (ClientEmail == "") {
               jQuery("#ex-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               if(regex.test(ClientEmail) == false ) {
                   jQuery("#ex-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client first name
           if (ClientFirstName == "") {
               jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('First name required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               var Res = isNaN(ClientFirstName);
               if(Res == false) {
                   jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid first name.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
               var NameRegx = /^[a-zA-Z0-9- ]*$/;
               if(NameRegx.test(ClientFirstName) == false) {
                   jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client last name
           if (ClientLastName == "") {
               jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Last name required.', 'appointzilla'); ?></strong></span>");
               return false;
           } else {
               var Res = isNaN(ClientLastName);
               if(Res == false) {
                   jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid last name.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
               var NameRegx = /^[a-zA-Z0-9- ]*$/;
               if(NameRegx.test(ClientLastName) == false) {
                   jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                   return false;
               }
           }

           //client phone
           if (ClientPhone == "") {
               jQuery("#ex-client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Phone required. <br>Only Numbers 1234567890.", "appointzilla"); ?></strong></span>");
               return false;
           } else {
               var ClientPhoneRes = isNaN(ClientPhone);
               if(ClientPhoneRes == true) {
                   jQuery("#ex-client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Invalid phone. <br>Numbers only: 1234567890.", "appointzilla"); ?></strong></span>");
                   return false;
               }
           }

           var PostData1 = "Action=BookAppointment"+ "&ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + StartTime;
           var PostData2 = "&UserType=" + UserType + "&ClientEmail=" + ClientEmail;
           var PostData3 =  "&ClientFirstName=" + ClientFirstName + "&ClientLastName=" + ClientLastName + "&ClientPhone=" + ClientPhone + "&ClientNote=" + ClientSi;
           var PostData = PostData1 + PostData2 + PostData3;

           jQuery('#ex-user-form-btn-div').hide();
           jQuery('#ex-user-form-loading-img').show();
       }

       jQuery.ajax({
           dataType : 'html',
           type: 'POST',
           url : location.href,
           cache: false,
           data : PostData,
           complete : function() {  },
           success: function(data) {
               data = jQuery(data).find('div#AppForthModalData');
               jQuery("#new-user-form-loading-img").hide();
               jQuery("#check-email-div-form").hide();
               jQuery("#AppThirdModal").hide();
               jQuery("#AppForthModalFinal").show();
               jQuery("#AppForthModalFinal").html(data);
           }
       });
   }

   //check existing user
   function CheckExistingUser() {
       jQuery(".error").hide();
       var ClientEmail = jQuery("#check-client-email").val();
       //client email
       var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
       if (ClientEmail == "") {
           jQuery("#check-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
           return false;
       } else {
           if(regex.test(ClientEmail) == false ) {
               jQuery("#check-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
               return false;
           }
       }

       var PostData = "Action=CheckExistingUser" + "&ClientEmail=" + ClientEmail;
       jQuery("#existing-user-form-btn").hide();
       jQuery("#existing-user-loading-img").show();
       jQuery.ajax({
           dataType : 'html',
           type: 'POST',
           url : location.href,
           cache: false,
           data : PostData,
           complete : function() {  },
           success: function(Data) {
               Data = jQuery(Data).find('div#check-email-result');
               jQuery("#existing-user-loading-img").hide();
               jQuery("#check-user").hide();
               jQuery('#check-email-div-form').hide();
               jQuery("#check-email-result-div").show();
               jQuery("#check-email-result-div").html(Data);
           }
       });
   }

   function CloseModelform() {
       jQuery("#AppForthModalFinal").hide();
       jQuery("#AppSecondModalData").hide();
       jQuery("#AppThirdModalData").hide();
       jQuery("#ex-pay-canceling-img").show();
       location.href = location.href;
   }

   function highlightsradio(timeslotspanid) {
       jQuery('span').removeClass('selected');
       var spanid = "#" + timeslotspanid;
       jQuery(spanid).addClass("selected");
   }

    // failed appointment
    function failedappointment() {
        var appid = jQuery('#appid').val();
        var Datastring = "appstatus=cancel" + "&appid="+ appid;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : Datastring,
            complete : function() {  },
            success: function(data) {
                jQuery('#AppForthModalFinal').hide();
                jQuery('#AppSecondModalData').hide();
                jQuery('#AppThirdModalData').hide();
                var CurrentUrl = location.href;
                CurrentUrl=CurrentUrl.replace('failed=failed&appointId='+appid, '');
                location.href = CurrentUrl;
            }
        });
    }

    //cancel appointment
    function CancelAppointment() {
        var appid = jQuery('#appid').val();
        var DataString = "appstatus=cancel" + "&appid="+ appid;
        jQuery("#paybuttondiv").hide();
        jQuery("#pay-canceling-img").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : DataString,
            complete : function() {  },
            success: function() {
                jQuery('#AppForthModalFinal').hide();
                jQuery('#AppSecondModalData').hide();
                jQuery('#AppThirdModalData').hide();
                window.location.reload();
            }
        });
    }

    //apply coupon code
    function ApplyCoupon() {
        var CouponCode = jQuery("#coupon-code").val();
        if(CouponCode == "") {
            alert("<?php _e("Enter any coupon code.", "appointzilla"); ?>");
            jQuery("#coupon-code").focus();
            return false;
        } else {
            var PostData = "Action=apply-coupon" + "&CouponCode=" + CouponCode;
            jQuery("#loading-img").show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function(ReturnedData) {
                    ReturnedData = jQuery(ReturnedData).find("div#coupon-result");
                    jQuery("#loading-img").hide();
                    jQuery("#apply-coupon-div").hide();
                    jQuery("#show-coupon-result").html(ReturnedData);
                    jQuery("#show-coupon-result").show();
                    var CouponCodeValue = jQuery("#coupon-code-div").text();
                    var DicountRateValue = jQuery("#discount-rate-div").text();
                    jQuery("input[name=custom]").val(CouponCodeValue);
                    jQuery("input[name=discount_rate]").val(DicountRateValue);
                }
            });
        }
    }

    //try another coupon code
    function TryAgain() {
        jQuery("#apply-coupon-div").show();
        jQuery("#show-coupon-result").hide();
    }

    //try again booking
    function TryAgainBooking() {
        jQuery("#check-email-result-div").hide();
        jQuery("#check-user").show();
        jQuery('#check-email-div-form').show();
        jQuery("#existing-user-form-btn").show();
    }

    //cancel appointment
    function Canceling() {
        jQuery("#ex-user-form-btn-div").hide();
        jQuery("#ex-canceling-img").show();
        location.reload();
    }

    // on paypal pay button click
    function PayWithPaypal(){
        jQuery('#show-redirecting-msg').show();
        jQuery('#paybuttondiv').hide();
    }
    </script>

    <!---Display Booking Instruction--->
    <?php if($AllCalendarSettings['apcal_booking_instructions']) { ?>
    <div id="bookinginstructions" align="center">
        <?php echo $AllCalendarSettings['apcal_booking_instructions']; ?>
    </div>
    <?php } ?>

    <!---Schedule An Appointment Button--->
    <div id="bkbtndiv" align="center" style="padding:10px;">
        <button name="addappointment" class="apcal_btn apcal_btn-large apcal_btn-primary" type="submit" id="addappointment">
            <strong><i class="icon-calendar icon-white"></i> <?php if(isset($AllCalendarSettings['booking_button_text'])) {
                    echo $AllCalendarSettings['booking_button_text'];
                } else {
                    _e("Schedule New Appointment", 'appointzilla');
                } ?>
            </strong>
        </button>
        </button>
    </div>

    <!---Show appointment calendar--->
    <div id='calendar'>
        <div style="text-align: right; font-size: small;">Appointment Calendar Premium Powered By: <a href="http://appointzilla.com/" title="Online Appointment Scheduling Plugin For WordPress" target="_blank">AppointZilla</a></div>
    </div>

    <!---AppFirstModal For Schedule New Appointment--->
    <div id="AppFirstModal" style="display:none;">
        <div class="apcal_modal" id="myModal" style="z-index:10000;">
            <form action="" method="post" name="addnewappointment" id="addnewappointment">
                <div class="apcal_modal-info">
                    <div class="apcal_alert apcal_alert-info">
                        <a href="#bookinginstructions" style="float:right; margin-right:4px; margin-top:12px;" id="close"><i class="icon-remove"></i></a>
                        <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p>
                        <div><?php _e('Step 1. Select Date & Service', 'appointzilla'); ?></div>
                    </div>
                </div>

                <div class="apcal_modal-body">
                    <div id="firdiv" style="float:left; height:210px; width:260px; padding-bottom:30px;">
                        <!--JS Datepicker -->
                            <?php if($small_calendar_type == "jquery") { ?>
                            <div id="datepicker"></div>
                            <?php } ?>
                        <!--PHP Datepicker-->
                        <?php
                            if($DateFormat == 'd-m-Y') $CalFormat = 'DD-MM-YYYY';
                            if($DateFormat == 'm-d-Y') $CalFormat = 'MM-DD-YYYY';
                            if($DateFormat == 'Y-m-d') $CalFormat = 'YYYY-MM-DD'; //coz yy-mm-dd not
                            if($small_calendar_type == "php") {
                                $myCalendar->writeScript();
                            }
                        ?>
                    <script>
                      function myChanged() {
                        var x = document.getElementById('date1').value;
                        var x2 = document.getElementById('date1').value;
                        x = moment(x).format('<?php echo $CalFormat; ?>');
                        x2 = moment(x2).format('DD-MM-YYYY');
                        document.getElementById('appdate').value = x;
                        document.getElementById('appdate2').value = x2;
                      }
                    </script>
                    </div>

                    <div id="secdiv" style="float:right; margin-right:5%; width:40%" >
                        <strong><?php _e('Your Appointment Date:', 'appointzilla'); ?> </strong><br>
                        <input name="appdate" id="appdate" type="text" readonly="" style="height:30px; width:100%; padding-left: 15px;" value="<?php echo date($DateFormat, strtotime($StartDate)); ?>" /><br>
                        <input name="appdate2" id="appdate2" type="hidden" readonly="" style="height:30px; width:100%" value="<?php echo date("Y-m-d", strtotime($StartDate)); ?>" />
                        <?php global $wpdb;
                        $CategoryTable = $wpdb->prefix."ap_service_category";
                        $FindCategorySQL ="SELECT * FROM `$CategoryTable`  order by `name` ASC";
                        $AllCategory = $wpdb->get_results($FindCategorySQL, OBJECT); ?>

                        <style type="text/css"> .mycss { font-weight:bold; } </style>
                        <strong><?php _e('Select Service:', 'appointzilla'); ?></strong><br>
                        <select name="servicelist" id="servicelist" style="width:100%">
                            <option value="0"><?php _e('Select Service', 'appointzilla'); ?></option>
                        <?php $cal_admin_currency_id = get_option('cal_admin_currency');
                        if($cal_admin_currency_id) {
                            $CurrencyTableName = $wpdb->prefix . "ap_currency";
                            $cal_admin_currency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency_id'");
                            $cal_admin_currency = $cal_admin_currency->symbol;
                        } else {
                            $cal_admin_currency = "&#36;";
                        }

                        if($AllCalendarSettings['show_service_cost'] == 'yes') $ShowCost = 1; else  $ShowCost = 0;
                        if($AllCalendarSettings['show_service_duration'] == 'yes') $ShowDuration = 1; else  $ShowDuration = 0;
                        $ServiceTable = $wpdb->prefix."ap_services";
                        foreach($AllCategory as $Category) {
                            echo "<option value='$Category->id' disabled class='mycss'>".ucwords($Category->name)."</option>";
                            $FindServiceSQL = "SELECT * FROM `$ServiceTable` WHERE `availability` = 'yes' and `category_id` = '$Category->id' order by `name` ASC";
                            $AllService = $wpdb->get_results($FindServiceSQL, OBJECT);
                            if(count($AllService)) {
                                foreach($AllService as $Service) { ?>
                                    <option value="<?php echo $Service->id; ?>">&nbsp;&nbsp;
                                        <?php echo ucwords($Service->name);
                                        if($ShowDuration || $ShowCost) echo " (";
                                        if($ShowDuration) { echo $Service->duration."min"; } if($ShowDuration && $ShowCost) echo "/";
                                        if($ShowCost) { echo $cal_admin_currency. $Service->cost; }
                                        if($ShowDuration || $ShowCost) echo ")"; ?>
                                    </option><?php
                                }
                            } else {
                                echo "<option disabled>&nbsp;&nbsp;&nbsp;".ucwords(__('No service in this category', 'appointzilla'))."</option>";
                            }
                        } ?>
                        </select>
                        <br>
                        <script type="text/javascript">
                            //load staff according to service -  start
                            jQuery('#servicelist').change(function(){

                                var ServiceId = jQuery("select#servicelist").val();
                                if(ServiceId > 0) {
                                    jQuery('#loading-staff').show();
                                    jQuery('#staff').hide();
                                    var FirstData = "ServiceId=" + ServiceId;
                                    jQuery.ajax({
                                        dataType : 'html',
                                        type: 'GET',
                                        url : location.href,
                                        data : FirstData,
                                        complete : function() { },
                                        success: function(data) {
                                            data=jQuery(data).find('div#stfflistdiv');
                                            jQuery('#staff').show();
                                            jQuery('#loading-staff').hide();
                                            jQuery('#staff').html(data);
                                        }
                                    });
                                } else {
                                    jQuery('#staff').hide();
                                }
                            });
                        </script>
                    <div id="loading-staff" style="display:none;"><?php _e('Loading Staff...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                    <div id="staff"></div>
                    </div><!--#secdiv-->
                </div><!--#modal-body-->
            </form>
      </div>
    </div>
    <!---AppSecondModal For Schedule New Appointment--->

    <!---AppSecondModal For Schedule New Appointment--->
    <div id="AppSecondModal" style="display:none;"></div>
    <!---AppSecondModal For Schedule New Appointment End--->

    <!---AppThirdModal For Schedule New Appointment--->
    <div id="AppThirdModal" style="display:none;"></div>
    <!---AppThirdModal For Schedule New Appointment End--->
    <div id="AppForthModalFinal" style="display:none;">

    </div>
    <!---AppThirdModal For Schedule New Appointment End--->

    <!--date-picker js -->
    <script src="<?php echo plugins_url('/menu-pages/datepicker-assets/js/jquery.ui.datepicker.js', __FILE__); ?>" type="text/javascript"></script>

    <!---Loading staff ajax return code--->
    <?php if(isset($_GET['ServiceId'])) { ?>
        <!---load bootstrap css--->
        <link rel='stylesheet' type='text/css' href='<?php echo plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__); ?>' />
        <div id="stfflistdiv">
            <?php
                $ServiceID = $_GET['ServiceId'];
                if($ServiceID) {
                    $ServiceTable = $wpdb->prefix . "ap_services";
                    $StaffTable = $wpdb->prefix . "ap_staff";
                    $AllStaffIdList = $wpdb->get_row("SELECT `staff_id` FROM `$ServiceTable` WHERE `id` = '$ServiceID'", OBJECT);
                    $AllStaffIdList = unserialize($AllStaffIdList->staff_id);
                    if(count($AllStaffIdList) > 1) {
                        ?>
                        <strong><?php _e('Select Staff:', 'appointzilla'); ?></strong><br>
                        <select name='stafflist' id='stafflist' style="width:100%">
                            <?php //get all staff id list by service id

                                if(count($AllStaffIdList)) {
                                    foreach($AllStaffIdList as $StaffId) {
                                        $StaffDetails = $wpdb->get_row("SELECT `id`, `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);
                                        echo "<option value='".$StaffDetails->id."'>&nbsp;&nbsp;".ucwords($StaffDetails->name)."</option>";
                                    }
                                } else {
                                    echo "<option value='1'>".__("No Staff Assigned")."</option>";
                                }
                            ?>
                        </select>
                        <br>
                        <?php
                    } else {    // end of count > 1
                        if(count($AllStaffIdList)) {
                            foreach($AllStaffIdList as $StaffId) {
                                $StaffDetails = $wpdb->get_row("SELECT `id`, `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);
                                echo "<input type='hidden' name='stafflist' id='stafflist' value='$StaffDetails->id'>";
                            }
                        }
                    } // end of count > 1 else
                ?>
                <button type="button" class="apcal_btn" id="next1" name="next1" onclick="LoadSecondModal()"><?php _e('Next', 'appointzilla'); ?> <i class="icon-arrow-right"></i></button>
                <div id="loading1" style="display:none;"><?php _e('Loading...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" /></div>
            <?php } //end of service id if ?>
        </div><?php
    } ?>


    <!---loading second modal form ajax return code--->
    <?php require_once('shortcode-time-slot-calculation.php'); ?>
    <!---loading second modal form ajax return code--->


    <!---loading third modal form ajax return code--->
    <?php
    if( isset($_GET['StartTime']) && isset($_GET['StaffId']) ) { ?>
        <div id="AppThirdModalData">
            <div class="apcal_modal" id="AppThirdModal" style="z-index:10000;">
                <input name="ServiceId" id="ServiceId" type="hidden" value="<?php if(isset($_GET['ServiceId'])) { echo $_GET['ServiceId']; } ?>" />
                <input name="StaffId" id="StaffId" type="hidden" value="<?php if(isset($_GET['StaffId'])) { echo $_GET['StaffId']; } ?>" />
                <input name="AppDate" id="AppDate" type="hidden" value="<?php if(isset($_GET['AppDate'])) { echo $_GET['AppDate']; } ?>" />
                <input name="StartTime" id="StartTime" type="hidden" value="<?php if(isset($_GET['StartTime'])) { echo $_GET['StartTime']; } ?>" />
                <input name="EndTime" id="EndTime" type="hidden" value="<?php if(isset($_GET['EndTime'])) { echo $_GET['EndTime']; } ?>" />
                <input name="RecurringType" id="RecurringType" type="hidden" value="<?php if(isset($_GET['RecurringType'])) { echo $_GET['RecurringType']; } ?>" />
                <input name="RecurringStartDate" id="RecurringStartDate" type="hidden" value="<?php if(isset($_GET['recurring_start_date'])) { echo $_GET['recurring_start_date']; } ?>" />
                <input name="RecurringEndDate" id="RecurringEndDate" type="hidden" value="<?php if(isset($_GET['recurring_end_date'])) { echo $_GET['recurring_end_date']; } ?>" />

                <div class="apcal_modal-info">
                    <a href="" onclick="CloseModelform()" style="float:right; margin-right:40px; margin-top:21px;" id="close" ><i class="icon-remove"></i></a>
                    <div class="apcal_alert apcal_alert-info">
                        <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p>
                        <?php _e('Step 3. Complete Your Booking', 'appointzilla'); ?>
                    </div>
                </div>

                <div class="apcal_modal-body">
                    <?php if($AllCalendarSettings['apcal_user_registration'] == "yes") { ?>
                    <!--check user div-->
                    <div id="check-user">
                        <table width="100%" class="table">
                            <tr>
                                <td colspan="3">
                                    <button id="new-user" name="new-user" class="apcal_btn apcal_btn-info" onclick="return NewUserBtn();"><i class="fa fa-user"></i> <?php _e("New User", "appointzilla"); ?></button>
                                    <button id="existing-user" name="existing-user" class="apcal_btn apcal_btn-info" onclick="return ExistingUserBtn();"><i class="fa fa-sign-in"></i> <?php _e("Existing User", "appointzilla"); ?></button>
                                    <button type="button" class="apcal_btn" id="back2" name="back2" onclick="LoadSecondModal2()" style="float: right;"><i class="icon-arrow-left"></i>  <?php _e('Back', 'appointzilla'); ?></button>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--new user div-->
                    <div id="new-user-div" style="display: none;">
                        <table width="100%" class="table">
                            <tr>
                                <th scope="row"><?php _e('Username', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-username" type="text" id="client-username" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Password', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-password" type="password" id="client-password" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Confirm Password', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-confirm-password" type="password" id="client-confirm-password" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-email" type="text" id="client-email" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-first-name" type="text" id="client-first-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-last-name" type="text" id="client-last-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-phone" type="text" id="client-phone" style="height:30px;"  maxlength="14"/></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="client-si" id="client-si"></textarea></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <div id="new-user-form-btn-div">
                                        <button type="button" class="apcal_btn apcal_btn-success" id="book-now" name="book-now" onclick="return CheckValidation('NewUser')"><i class="icon-ok icon-white"></i>  <?php _e('Book Now', 'appointzilla'); ?></button>
                                    </div>
                                    <div id="new-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--existing user div-->
                    <div id="existing-user-div" style="display: none;">

                        <!--div for display existing user search details-->
                        <div id="check-email-div-form" style="display: none;">
                            <table width="100%" class="table">
                                <tr>
                                    <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="check-client-email" type="text" id="check-client-email" style="height:30px;" /></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div id="existing-user-form-btn">
                                            <button type="button" class="apcal_btn apcal_btn-success" id="check-existing-user" name="check-existing-user" onclick="return CheckExistingUser();"><i class="icon-search icon-white"></i> <?php _e('Search Email', 'appointzilla'); ?></button>
                                        </div>
                                        <div id="existing-user-loading-img" style="display:none;"><?php _e('Searching, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!--div for display existing user search details-->
                        <div id="check-email-result-div" style="display: none;">

                        </div>
                    </div>
                    <?php } else { // end of if registration enable ?>
                    <!--user registration not enable-->
                    <div id="no-user-registration">
                        <table width="100%" class="table">
                            <tr>
                                <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-email" type="text" id="client-email" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-first-name" type="text" id="client-first-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-last-name" type="text" id="client-last-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-phone" type="text" id="client-phone" style="height:30px;"  maxlength="14"/></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="client-si" id="client-si"></textarea></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <div id="new-user-form-btn-div">
                                        <button type="button" class="apcal_btn" id="back2" name="back2" onclick="LoadSecondModal2()"><i class="icon-arrow-left"></i>  <?php _e('Back', 'appointzilla'); ?></button>
                                        <button type="button" class="apcal_btn apcal_btn-success" id="book-now" name="book-now" onclick="return CheckValidation('NewUser')"><i class="icon-ok icon-white"></i>  <?php _e('Book Now', 'appointzilla'); ?></button>
                                    </div>
                                    <div id="new-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                </div><!--end modal-body-->
            </div>
        </div><?php
    } ?>

    <!---saving appointments--->
    <?php if( isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        $UserType = $_POST['UserType'];
        if($Action == "BookAppointment") {
            //print_r($_POST); die;
            $ServiceId = $_POST['ServiceId'];
            $StaffId = $_POST['StaffId'];
            $AppDateNo = $_POST['AppDate'];

            $ClientEmail = $_POST['ClientEmail'];
            $ClientFirstName = sanitize_text_field($_POST['ClientFirstName']);
            $ClientLastName = sanitize_text_field($_POST['ClientLastName']);
            $ClientName = $ClientFirstName ." ". $ClientLastName;
            $ClientPhone = $_POST['ClientPhone'];
            $ClientNote = $_POST['ClientNote'];
            $AppointmentKey = md5(date("F j, Y, g:i a"));
            $AppDate = date("Y-m-d", strtotime($AppDateNo));
            $StartTime = $_POST['StartTime'];

            //check user registration yes/no
            if($AllCalendarSettings['apcal_user_registration'] == "yes"){
                if($UserType == "NewUser") {
                    $ClientUserName = $_POST['ClientUserName'];
                    $ClientPassword = $_POST['ClientPassword'];

                    //create new user profile as subscriber
                    $UserId = username_exists( $ClientUserName );
                    if ( !$UserId and email_exists($ClientEmail) == false ) {
                        $UserId = wp_create_user( $ClientUserName, $ClientPassword, $ClientEmail );
                        if($UserId) {
                            update_user_meta( $UserId, 'first_name', $_POST['ClientFirstName']);
                            update_user_meta( $UserId, 'last_name', $_POST['ClientLastName']);
                            add_user_meta( $UserId, 'client_phone', $ClientPhone);
                            add_user_meta( $UserId, 'client_note', $ClientNote);
                        }
                    } else {
                        _e("User already exists", "appointzilla");
                    }
                }

                if($UserType == "ExUser") {
                    //update existing user profile as subscriber
                    $UserId = email_exists($ClientEmail);
                    if($UserId) {
                        update_user_meta( $UserId, 'first_name', $ClientFirstName);
                        update_user_meta( $UserId, 'last_name', $ClientLastName);
                        update_user_meta( $UserId, 'client_phone', $ClientPhone);
                        update_user_meta( $UserId, 'client_note', $ClientNote);
                    } else {
                        _e("User already exists", "appointzilla");
                    }
                }
            } // end of check user registration

            //fetch service detail calculation EndTime and Service name
            $ServiceTableName = $wpdb->prefix . "ap_services";
            $ServiceName = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId' ");
            $ServiceDuration = $ServiceName->duration;

            $StartTimeTimestamp = strtotime($StartTime);
            //calculate end time according to service duration
            $CalculateTime = strtotime("+$ServiceDuration minutes", $StartTimeTimestamp);
            $EndTime =  date('h:i A', $CalculateTime );

            if(isset($AllCalendarSettings['apcal_new_appointment_status'])) {
                $Status = $AllCalendarSettings['apcal_new_appointment_status'];
            } else {
                $Status = "pending";
            }
            $AppointmentBy = "user";
            $Recurring = "no";
            $RecurringType = "none";
            $RecurringStartDate = $AppDate;
            $RecurringEndDate = $AppDate;
            $PaymentStatus = "unpaid";

            global $wpdb;
            $AppointmentsTable = $wpdb->prefix ."ap_appointments";
            $CreateAppointments = "INSERT INTO `$AppointmentsTable` (`id` ,`name` ,`email` ,`service_id` ,`staff_id` ,`phone` ,`start_time` ,`end_time` ,`date` ,`note` , `appointment_key` ,`status` ,`recurring` ,`recurring_type` ,`recurring_st_date` ,`recurring_ed_date` ,`appointment_by`, `payment_status`) VALUES ('NULL', '$ClientName', '$ClientEmail', '$ServiceId', '$StaffId', '$ClientPhone', '$StartTime', '$EndTime', '$AppDate', '$ClientNote', '$AppointmentKey', '$Status', '$Recurring', '$RecurringType', '$RecurringStartDate', '$RecurringEndDate', '$AppointmentBy', '$PaymentStatus');";
            if($wpdb->query($CreateAppointments)) {
                $LastAppointmentId = $wpdb->insert_id;; ?>
                <div id="AppForthModalData">
                <?php global $wpdb;
                $ClientTable = $wpdb->prefix."ap_clients";
                $ExistClientDetails = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$ClientEmail' ");
                if(count($ExistClientDetails)) {
                    // update  exiting client deatils
                    $ExistClientId = $ExistClientDetails->id;
                    $update_client = "UPDATE `$ClientTable` SET `name` = '$ClientName', `email` = '$ClientEmail', `phone` = '$ClientPhone', `note` = '$ClientNote' WHERE `id` = '$ExistClientId' ;";
                    if($wpdb->query($update_client)) {
                        $LastClientId = $ExistClientId;
                    } else {
                        // if now data filed modified then
                        $LastClientId = $ExistClientId;
                    }
                } else {
                    // insert new client deatils
                    $InsertClient = "INSERT INTO `$ClientTable` (`id` ,`name` ,`email` ,`phone` ,`note`) VALUES ('NULL', '$ClientName', '$ClientEmail', '$ClientPhone', '$ClientNote');";
                    if($wpdb->query($InsertClient)) {
                        //$LastClientId = mysql_insert_id();
						$LastClientId = $wpdb->insert_id;
						 
                    }
                } ?>

                <div class="apcal_modal" id="AppForthModal" style="z-index:10000;">
                <div class="apcal_modal-info">
                    <div style="float:right; margin-top:5px; margin-right:10px;"></div>
                    <div class="apcal_alert apcal_alert-info">
                        <p><?php _e('Thank You. Your appointment has been scheduled.', 'appointzilla'); ?></p>
                </div><!--end modal-info-->

                <div class="apcal_modal-body">
                    <style>
                        .table th, .table td {
                            padding: 4px;;
                        }
                    </style>
                    <strong><?php _e('Your Appointment Details', 'appointzilla'); ?></strong>
                    <input type="hidden" id="appid" name="appid" value="<?php echo $LastAppointmentId; ?>" />
                    <table width="100%" class="table">
                        <tr>
                            <th width="26%" scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo ucwords($ClientName);  ?></td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo $ClientEmail; ?></td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo $ClientPhone;  ?></td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Service', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php	echo ucwords($ServiceName->name);  ?></td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Staff', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%">
                                <?php $StaffTableName = $wpdb->prefix . "ap_staff";
                                $StaffName = $wpdb->get_row("SELECT `name` FROM `$StaffTableName` WHERE `id` = '$StaffId' ");
                                echo ucwords($StaffName->name); ?>
                            </td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Date', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo date($DateFormat, strtotime($AppDate));	?></td>
                        </tr>
                        <tr>
                            <?php if($TimeFormat == "h:i") $InfoTimeFormat = "h:i A"; else $InfoTimeFormat = "H:i"; ?>
                            <th width="26%" scope="row"><?php _e('Time', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo date($InfoTimeFormat, strtotime($StartTime))." - ".date($InfoTimeFormat, strtotime($EndTime));  ?></td>
                        </tr>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Status', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%"><?php echo _e(ucfirst($Status),'appointzilla'); ?></td>
                        </tr>
                            <?php
                            //get service details for payment purpose & also check payment settings
                            global $wpdb;
                            $ServiceTableName = $wpdb->prefix . 'ap_services';
                            $ServiceDetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId'");
                            $AcceptPayment = $ServiceDetails->accept_payment;
                            $PaymentType = $ServiceDetails->payment_type;
                            $ap_payment_email = get_option('ap_payment_email');
                            $ap_payment_gateway_status = get_option('ap_payment_gateway_status');
                            if($AcceptPayment == "yes" && $PaymentType == "full" && $ap_payment_email && $ap_payment_gateway_status == "yes") {
                            ?>
                        <tr>
                            <th width="26%" scope="row"><?php _e('Coupon Code', 'appointzilla'); ?></th>
                            <td width="1%"><strong>:</strong></td>
                            <td width="73%">
                                <div id="apply-coupon-div">
                                    <input type="text" id="coupon-code" name="coupon-code" maxlength="15" style="width: 120px;">
                                    <button id="apply-coupon" name="apply-coupon" class="apcal_btn apcal_btn-small apcal_btn-info" onclick="return ApplyCoupon();" style="margin-top: -10px;"><i class="icon-tags icon-white"></i> <?php _e('Apply', 'appointzilla'); ?></button>
                                </div>

                                <div id="loading-img" style="display:none;"><?php _e('Applying...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" /></div>
                                <div id="show-coupon-result" style="display:none;"></div>
                            </td>
                        </tr>
                            <?php } ?>
                        <tr>
                            <td colspan="3">
                                <?php $Check1 = 0; $Check2 = 0;  $Check3 = 0;
                                /**
                                 * Paypal Payment Process
                                 **/
                                //get service details for payment purpose
                                global $wpdb;
                                $ServiceTableName = $wpdb->prefix . 'ap_services';
                                $ServiceDetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId'");

                                //get currency code
                                $CurrencyId = get_option('cal_admin_currency');
                                if($CurrencyId != '') {
                                    $CurrencyTableName = $wpdb->prefix."ap_currency";
                                    $CurrencyDetails = $wpdb->get_row("SELECT `code` FROM `$CurrencyTableName` WHERE `id` = '$CurrencyId'");
                                    $CurrencyCode =  $CurrencyDetails->code;
                                } else {
                                    $CurrencyCode =  'USD';
                                }

                                $Protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                                $SuccessCurrentUrl = $Protocol.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                                $FailedCurrentUrl = $SuccessCurrentUrl."?&failed=failed&appointId=".$LastAppointmentId;

                                //check payment is 'yes'
                                if($ap_payment_gateway_status == 'yes') {

                                    //check service is paid
                                    if($ServiceDetails->accept_payment == 'yes') {
                                        //check payment type
                                        if($ServiceDetails->payment_type == 'percentage') {
                                            $PayCost = $ServiceDetails->cost;
                                            $percentage = $ServiceDetails->percentage_ammount;
                                            $PayCost = ($PayCost * $percentage) /100;
                                        } else {
                                            $PayCost = $ServiceDetails->cost;
                                        }

                                        $ApPaymentEmail = get_option('ap_payment_email');
                                        if($ApPaymentEmail) {
                                            // default discount value
                                            $DiscountRate = 0;

                                            // Include the paypal library
                                            require_once ('menu-pages/paypal-api/Scientechpaypal.php');
                                            $ScientechPaypal = new Scientechpaypal();
                                            $ScientechPaypal->TakePayment($ApPaymentEmail, $CurrencyCode, $SuccessCurrentUrl, $FailedCurrentUrl, $ServiceDetails->name, $PayCost, $LastAppointmentId, $DiscountRate);
                                        } else {
                                            $Check3 = 1;
                                        }
                                    } else {
                                        $Check2 = 1;
                                    }
                                } else {
                                    $Check1 = 1;
                                }

                                // send notification if any of check == 1
                                if($Check1 || $Check2 || $Check3) { ?>
                                    <button type="submit" class="apcal_btn apcal_btn" onclick="CloseModelform()" style="margin-left:80%"><i class="icon-ok"></i> <?php _e('Done', 'appointzilla'); ?></button>
                                    <?php $BlogName =  get_bloginfo('name');
                                    if($LastAppointmentId && $LastClientId) {
                                        $AppId = $LastAppointmentId;
                                        $ServiceId = $ServiceId;
                                        $StaffId = $StaffId;
                                        $ClientId = $LastClientId;
                                        //include notification class
                                        require_once('menu-pages/notification-class.php');
                                        $Notification = new Notification();
                                        $Notification->notifyadmin($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                        $Notification->notifyclient($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                        if(get_option('staff_notification_status') == 'on') {
                                            $Notification->notifystaff($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                        }
                                    }
                                }

                                //if status is approved then sync appointment
                                if($Status == 'approved') {

                                    //add service name with event title($name)
                                    //$ServiceTable = $wpdb->prefix . "ap_services";
                                    //$ServiceData = $wpdb->get_row("SELECT * FROM `$ServiceTable` WHERE `id` = '$ServiceId'");
                                    //$name = $name."(".$ServiceData->name.")";

                                    /***
                                     * admin appointment sync
                                     */
                                    $CalData = get_option('google_caelndar_settings_details');
                                    if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                                        $StartTime = date("H:i", strtotime($StartTime));
                                        $EndTime = date("H:i", strtotime($EndTime));
                                        $AppDate = date("Y-m-d", strtotime($AppDate));
                                        $ClientNote = strip_tags($ClientNote);

                                        $ClientId = $CalData['google_calendar_client_id'];
                                        $ClientSecretId = $CalData['google_calendar_secret_key'];
                                        $RedirectUri = $CalData['google_calendar_redirect_uri'];
                                        require_once('menu-pages/google-appointment-sync-class.php');

                                        //global $wpdb;
                                        $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                                        // insert this appointment event on calendar
                                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                                        $tag = "Appointment with: ";
                                        $OAuth = $GoogleAppointmentSync->NormalSync($ClientName, $AppDate, $StartTime, $EndTime, $ClientNote, $tag);
                                        //insert appintment sync details
                                        $OAuth = serialize($OAuth);
                                        $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$AppId', '$OAuth' )");
                                    } // end of google calendar setting

                                    /***
                                     * staff appointment sync
                                     */
                                    $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                                    if($StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'] != "" && $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret']) {
                                        $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                                        $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                                        $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                                        $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];
                                        require_once('menu-pages/google-staff-appointment-sync-class.php');

                                        // add this staff appointment event on his google calendar
                                        $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                                        $Tag = __("Appointment with: ", "appointzilla");
                                        $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($StaffId, $ClientName, $AppDate, $StartTime, $EndTime, $ClientNote, $Tag);

                                        //insert staff appointment sync details
                                        global $wpdb;
                                        $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                                        $StaffOAuth = serialize($StaffOAuth);
                                        $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$AppId', '$StaffOAuth' )");
                                    }//end of staff appointment sync

                                    //unset payment post variables
                                    //unset($_POST['address_status']); unset($_POST['payer_id']);

                                } // end of sync appointment is approved
                                ?>
                                <div id="ex-pay-canceling-img" style="display:none;"><?php _e('Refreshing, Please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                <div id="loading-staff" style="display:none;">
                                    <?php _e('Loading Staff...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </div>
                </div>
                <?php
            }//query if
        }
    }// saving appointment if


    // Payment success
    if( isset($_POST['address_status']) && isset($_POST['payer_id']) ) {
        global $wpdb;
        $AppointmentTableName = $wpdb->prefix . "ap_appointments";
        $PaymentTableName = $wpdb->prefix . 'ap_payment_transaction';
        $ClientTableName = $wpdb->prefix . 'ap_clients';
        $CouponsCodesTable = $wpdb->prefix ."apcal_pre_coupons_codes";
        //print_r($_POST);
        $PaymentDetails = serialize($_POST);
        $appId = $_POST['item_number'];

        //get client id by appointment id
        $ClientEmail = $wpdb->get_row("SELECT `email` FROM `$AppointmentTableName` WHERE `id` = '$appId'");
        $FetchClientEmail = $wpdb->get_row("SELECT `id` FROM `$ClientTableName` WHERE `email` = '$ClientEmail->email'");
        $clientId = $FetchClientEmail->id;

        $amount = $_POST['mc_gross'];
        $date = $_POST['payment_date'];
        $status = $_POST['address_status'];
        $txn_id = $_POST['txn_id'];
        $gateway = 'paypal';

        $wpdb->query("INSERT INTO `$PaymentTableName` (`id`, `app_id`, `client_id`, `ammount`, `date`, `status`, `txn_id`, `gateway`, `other_fields`) VALUES (NULL, '$appId', '$clientId', '$amount', '$date', '$status', '$txn_id', '$gateway', '$PaymentDetails');");
        $AppointmentId = $_POST['item_number'];
        $AppointmentRow = $wpdb->get_row("SELECT * FROM `$AppointmentTableName` WHERE `id` = '$AppointmentId'");
        if(count($AppointmentRow)) {
            $name = $AppointmentRow->name;
            $ServiceId = $AppointmentRow->service_id;
            $StaffId = $AppointmentRow->staff_id;
            $StartTime = $AppointmentRow->start_time;
            $EndTime = $AppointmentRow->end_time;
            $Client_name = $AppointmentRow->name;
            $Client_Email = $AppointmentRow->email;
            $Client_Phone = $AppointmentRow->phone;
            $Client_Note = $AppointmentRow->note;
            $AppDate = $AppointmentRow->date;
            $Status = 'approved';
            $AppointmentKey = $AppointmentRow->appointment_key;

            //update appointment status n payment status
            $PaymentStatus = $_POST['payment_status'];
            $AppointmentRow = $wpdb->query("UPDATE `$AppointmentTableName` SET `status` = '$Status', `payment_status` = 'paid' WHERE `id` = '$AppointmentId' ");

            $BlogName =  get_bloginfo();
            if($AppointmentId && $clientId) {
                $AppId = $AppointmentId;
                $ServiceId = $ServiceId;
                $StaffId = $StaffId;
                $ClientId = $clientId;
                //include notification class
                require_once('menu-pages/notification-class.php');
                $Notification = new Notification();
                $Notification->notifyadmin($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                $Notification->notifyclient($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                if(get_option('staff_notification_status') == "on") {
                    $Notification->notifystaff($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                }
            }

            //if status is approved then sync appointment
            if($Status == 'approved') {

                //add service name with event title($name)
                //$ServiceTable = $wpdb->prefix . "ap_services";
                //$ServiceData = $wpdb->get_row("SELECT * FROM `$ServiceTable` WHERE `id` = '$ServiceId'");
                //$name = $name."(".$ServiceData->name.")";

                /**
                 * Admin appointment google sync
                 */
                $CalData = get_option('google_caelndar_settings_details');
                if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                    $start_time = date("H:i", strtotime($StartTime));
                    $end_time = date("H:i", strtotime($EndTime));
                    $appointmentdate = date("Y-m-d", strtotime($AppDate));
                    $note = strip_tags($Client_Note);

                    $ClientId = $CalData['google_calendar_client_id'];
                    $ClientSecretId = $CalData['google_calendar_secret_key'];
                    $RedirectUri = $CalData['google_calendar_redirect_uri'];
                    require_once('menu-pages/google-appointment-sync-class.php');

                    //global $wpdb;
                    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                    // insert this appointment event on calendar
                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $tag = "Appointment with: ";
                    $OAuth = $GoogleAppointmentSync->NormalSync($name, $appointmentdate, $start_time, $end_time, $note, $tag);
                    //insert appintment sync details
                    $OAuth = serialize($OAuth);
                    $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` )
                    VALUES ( NULL , '$AppointmentId', '$OAuth' );");
                } // end of google calendar setting

                /**
                 * Staff appointment google sync
                 */
                $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                if($StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'] != "" && $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret']) {
                    $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                    $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                    $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                    $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];
                    require_once('menu-pages/google-staff-appointment-sync-class.php');

                    $start_time = date("H:i", strtotime($StartTime));
                    $end_time = date("H:i", strtotime($EndTime));
                    $appointmentdate = date("Y-m-d", strtotime($AppDate));
                    $note = strip_tags($Client_Note);
                    // add this staff appointment event on his google calendar
                    $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                    $Tag = __("Appointment with: ", "appointzilla");
                    $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($StaffId, $name, $appointmentdate, $start_time, $end_time, $note, $Tag);

                    //insert staff appointment sync details
                    global $wpdb;
                    $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                    $StaffOAuth = serialize($StaffOAuth);
                    $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$AppId', '$StaffOAuth' )");
                }//end of staff appointment sync

                //unset payment post variables
                unset($_POST['address_status']); unset($_POST['payer_id']);

            } // end of sync appointment is approved ?>
            <div class="apcal_modal" id="AppForthModal" style="z-index:10000;">
                <div class="apcal_modal-info">
                    <div style="float:right; margin-top:5px; margin-right:10px;">
                        <div align="center"><a href="" onclick="CloseModelform()" id="close" ><i class="icon-remove"></i></a></div>
                    </div>
                    <div class="apcal_alert apcal_alert-info">
                        <h4 ><?php _e('Payment Successfully Processed', 'appointzilla'); ?></h4>
                    </div>
                    <div class="apcal_modal-body">
                        <div class="apcal_alert apcal_alert-success">
                            <strong><?php _e('Payment received and your appointment has been confirmed.', 'appointzilla'); ?></strong><br />
                            <strong><?php _e('Thank you for scheduling appointment with us.', 'appointzilla'); ?></strong>
                            <?php
                            //if payment confirmed(done) then increment coupon used count value
                            if($status == "confirmed") {
                                $PaymentDetails = unserialize($PaymentDetails);
                                if(isset($PaymentDetails['custom'])) {
                                    $CouponCode = $PaymentDetails['custom'];
                                    //check coupon exist or not
                                    $CouponsData = $wpdb->get_row("SELECT * FROM `$CouponsCodesTable` WHERE `coupon_code` LIKE '$CouponCode'");
                                    if(count($CouponsData)) {
                                        //increment total used count
                                        $CouponId = $CouponsData->id;
                                        $UsedCount = $CouponsData->used_count + 1;
                                        $wpdb->query("UPDATE `$CouponsCodesTable` SET `used_count` = '$UsedCount' WHERE `id` = '$CouponId' ");
                                    }
                                }
                            }
                            ?>
                        </div>
                        <button type='button' onclick='CloseModelform()' name='close' id='close' value='Done' class='apcal_btn'><i class="icon-ok"></i> <?php _e('Done', 'appointzilla'); ?></button>
                    </div>
                    <div>
                </div>
            </div>
            <?php
        } // end of AppointmentRow
    } // end of Payment success

    // Payment process failed
    if( isset($_GET['failed']) && isset($_GET['appointId'])) { ?>
        <div class="apcal_modal" id="AppForthModal" style="z-index:10000;">
            <div class="apcal_modal-info" style="padding-bottom:20px;">
                <div style="float:right; margin-top:5px; margin-right:10px;">
                    <a href="#" onclick="CloseModelformfailed()" id="close" ><i class="icon-remove"></i></a>
                </div>
                <input type="hidden" name="appid" id="appid" value="<?php echo $_GET['appointId']; ?>" />
                <div class="apcal_alert apcal_alert-info">
                    <h4><?php _e('Payment Failed', 'appointzilla'); ?></h4>
                </div>
                <div style=" margin-left:20px; padding-right:20px;">
                    <div class="apcal_alert apcal_alert-error">
                        <?php _e('Sorry! Appointment booking was not successful.', 'appointzilla'); ?>
                    </div>
                    <button type='button' onclick='return failedappointment();' name='close' id='close' value='close' class='apcal_btn'><i class="icon-repeat"></i> <?php _e('Try Again', 'appointzilla'); ?></button>
                </div>
             </div>
        </div><?php
    }

    // cancel  appointment
    if(isset($_POST['appid']) && isset($_POST['appstatus']) ) {
        $appid = $_POST['appid'];
        global $wpdb;
        $apptabname = $wpdb->prefix."ap_appointments";
        $wpdb->query("UPDATE `$apptabname` SET `status` = 'cancelled' WHERE `id` = '$appid' ;");
    }

    //applying coupon code
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action == "apply-coupon") {
            if(isset($_POST['CouponCode'])) {
                $CouponCode = strtolower($_POST['CouponCode']);
            } else {
                $CouponCode = "";
            }
            if($CouponCode){
                global $wpdb;
                $Discount = 0;
                $CouponsCodesTable = $wpdb->prefix . "apcal_pre_coupons_codes";
                //Search Coupon
                $CouponDetails = $wpdb->get_row("SELECT * FROM `$CouponsCodesTable` WHERE `coupon_code` LIKE '$CouponCode'");
                if(count($CouponDetails)) {
                    //check coupon expire
                    $DateTodayTs = strtotime(date("Y-m-d"));
                    $ExpireDateTs = strtotime(date("Y-m-d", strtotime($CouponDetails->expire)));
                    $TotalUses = $CouponDetails->total_uses;
                    $UsedCount = $CouponDetails->used_count;
                    $Discount = $CouponDetails->discount;
                    if($DateTodayTs > $ExpireDateTs) {
                        //coupon expired
                        ?><div id="coupon-result"><div id="discount-rate-div" style="display: none;"><?php echo $Discount = 0; ?></div><?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code expired.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a></div><?php
                    } else if($UsedCount >= $TotalUses) {
                        //ckeck used count
                        ?><div id="coupon-result"><div id="discount-rate-div" style="display: none;"><?php echo $Discount = 0; ?></div><?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code expired.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a></div><?php
                    } else {
                        //coupon valid and appied
                        ?><div id="coupon-result">
                            <div id="coupon-code-div" style="display: none;"><?php echo $CouponCode; ?></div>
                            <div id="discount-rate-div" style="display: none;"><?php echo $Discount; ?></div>
                            <?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code applied.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Change", "appointzilla"); ?></a>
                        </div><?php
                    }
                } else {
                  ?>
                  <div id="coupon-result">
                      <div id="discount-rate-div" style="display: none;"><?php echo $Discount; ?></div>
                      <?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code is invalid.", "appointzilla"); ?>
                      <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a>
                  </div>
                  <?php
                }
            }
        }//end of if action
    }

    //check existing user
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action == "CheckExistingUser") {
            ?><div id="check-email-result"><?php
            $ClientEmail = $_POST['ClientEmail'];
            $ClientId = email_exists( $ClientEmail );
            if( $ClientId = email_exists( $ClientEmail )) {
                //fetch user details
                $ClientDetails = get_userdata( $ClientId );
                //print_r($ClientDetails);
                $FirstName = "";
                $LastName = "";
                $Phone = "";
                $ClientNote = "";
                $UserMetaData = get_user_meta( $ClientId );
                if(count($UserMetaData)) {
                    if(isset($UserMetaData['first_name'][0])) {
                        $FirstName = ucwords($UserMetaData['first_name'][0]);
                    }
                    if(isset($UserMetaData['last_name'][0])) {
                        $LastName = ucwords($UserMetaData['last_name'][0]);
                    }
                    if(isset($UserMetaData['client_phone'][0])) {
                        $Phone = $UserMetaData['client_phone'][0];
                    }
                    if(isset($UserMetaData['client_note'][0])) {
                        $ClientNote = ucfirst($UserMetaData['client_note'][0]);
                    }
                }
                ?>
                <input type="hidden" id="client-id" name="client-id" value="<?php echo $ClientId; ?>">
                <table width="100%" class="table">
                    <tr>
                        <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-email" type="text" id="ex-client-email" style="height:30px;" value="<?php echo $ClientEmail; ?>" readonly="" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-first-name" type="text" id="ex-client-first-name" style="height:30px;" value="<?php echo $FirstName; ?>"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-last-name" type="text" id="ex-client-last-name" style="height:30px;" value="<?php echo $LastName; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-phone" type="text" id="ex-client-phone" style="height:30px;" value="<?php echo $Phone; ?>"  maxlength="14"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><textarea name="ex-client-si" id="ex-client-si"><?php echo $ClientNote; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <div id="ex-user-form-btn-div">
                                <button type="button" class="apcal_btn apcal_btn-success" id="ex-book-now" name="ex-book-now" onclick="return CheckValidation('ExUser');"><i class="icon-ok icon-white"></i> <?php _e('Book Now', 'appointzilla'); ?></button>
                                <button type="button" class="apcal_btn apcal_btn-danger" id="ex-cancel-app" name="ex-cancel-app" onclick="return Canceling();"><i class="icon-remove icon-white"></i> <?php _e('Cancel', 'appointzilla'); ?></button>
                            </div>
                            <div id="ex-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                            <div id="ex-canceling-img" style="display:none;"><?php _e('Refreshing, Please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                        </td>
                    </tr>
                </table>
            <?php
            } else { ?>
                <table width="100%" class="table">
                    <tr>
                        <td colspan="3">
                            <?php  _e("Sorry! No record found.","appointzilla"); ?>
                            <button type="button" onclick="return TryAgainBooking();" class="apcal_btn apcal_btn-danger"><i class="fa fa-mail-reply"></i> Try Again</button>
                        </td>
                    </tr>
                </table>
            <?php
            }
            ?></div><?php
        }
    }
    /*$output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;*/
}//end of short code function 
?>