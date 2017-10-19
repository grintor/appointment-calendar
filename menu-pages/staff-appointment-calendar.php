<?php
    global $wpdb;
    global $current_user;
    //get current user email by get option
    get_currentuserinfo();
    $UserEmail = $current_user->user_email;

    $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";
  
    // find current user staff id in staff table
    $StaffTableName = $wpdb->prefix . "ap_staff";
    $StaffDetails = $wpdb->get_row("SELECT * FROM `$StaffTableName` WHERE `email` = '$UserEmail'");
    if(count($StaffDetails)) {
        $StaffId = $StaffDetails->id;

        // Get all appointment Current staff
        $AppointmentTableName = $wpdb->prefix . "ap_appointments";
        $FetchAllApps_sql = "select `id`, `name`, `start_time`, `end_time`, `date` FROM `$AppointmentTableName` WHERE `recurring` = 'no' AND `staff_id` = '$StaffId'";

        //recurring app sql
        $FetchAllRApps_sql = "select * FROM `$AppointmentTableName` WHERE `recurring` = 'yes' AND `staff_id` = '$StaffId'";

        $EventTableName = $wpdb->prefix."ap_events";

        // Loading Events On Calendar Start
        $FetchAllEvent_sql = "SELECT `id`, `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` WHERE `repeat` = 'N'";
        $AllEvents = $wpdb->get_results($FetchAllEvent_sql, OBJECT);

        // Loading Recurring Events On Calendar Start
        $FetchAllREvent_sql = "SELECT `id`, `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` WHERE `repeat` != 'N'";
        $AllREvents = $wpdb->get_results($FetchAllREvent_sql, OBJECT);
    ?>
    <!---render fullcalendar----->
    <script type='text/javascript'>
    jQuery(document).ready(function() {
        jQuery('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },

            editable: false,
            weekends: true,
            timeFormat: <?php if($TimeFormat == 'h:i') echo "'h:mmtt{-h:mmtt }'"; else echo "'H:mm{-H:mm }'"; ?>,
            axisFormat: <?php if($TimeFormat == 'h:i') echo "'hh:mm'"; else echo "'HH:mm'"; ?>,
            <?php $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings')); ?>
            firstDay: <?php if($AllCalendarSettings['calendar_start_day'] != '') echo $AllCalendarSettings['calendar_start_day']; else echo "1";  ?>,
            slotMinutes: 5,
            defaultView: '<?php if($AllCalendarSettings['calendar_view'] != '') echo $AllCalendarSettings['calendar_view']; else echo "month"; ?>',
            minTime: <?php if($AllCalendarSettings['day_start_time'] != '') echo date("G", strtotime($AllCalendarSettings['day_start_time'])); else echo "8"; ?>,
            maxTime: 24,
            monthNames: ["<?php _e("January", "appointzilla"); ?>","<?php _e("February", "appointzilla"); ?>","<?php _e("March", "appointzilla"); ?>","<?php _e("April", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("June", "appointzilla"); ?>","<?php _e("July", "appointzilla"); ?>", "<?php _e("August", "appointzilla"); ?>", "<?php _e("September", "appointzilla"); ?>", "<?php _e("October", "appointzilla"); ?>", "<?php _e("November", "appointzilla"); ?>", "<?php _e("December", "appointzilla"); ?>" ],
            monthNamesShort: ["<?php _e("Jan", "appointzilla"); ?>","<?php _e("Feb", "appointzilla"); ?>","<?php _e("Mar", "appointzilla"); ?>","<?php _e("Apr", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("Jun", "appointzilla"); ?>","<?php _e("Jul", "appointzilla"); ?>","<?php _e("Aug", "appointzilla"); ?>","<?php _e("Sept", "appointzilla"); ?>","<?php _e("Oct", "appointzilla"); ?>","<?php _e("nov", "appointzilla"); ?>","<?php _e("Dec", "appointzilla"); ?>"],
            dayNames: ["<?php _e("Sunday", "appointzilla"); ?>","<?php _e("Monday", "appointzilla"); ?>","<?php _e("Tuesday", "appointzilla"); ?>","<?php _e("Wednesday", "appointzilla"); ?>","<?php _e("Thursday", "appointzilla"); ?>","<?php _e("Friday", "appointzilla"); ?>","<?php _e("Saturday", "appointzilla"); ?>"],
            dayNamesShort: ["<?php _e("Sun", "appointzilla"); ?>","<?php _e("Mon", "appointzilla"); ?>", "<?php _e("Tue", "appointzilla"); ?>", "<?php _e("Wed", "appointzilla"); ?>", "<?php _e("Thus", "appointzilla"); ?>", "<?php _e("Fri", "appointzilla"); ?>", "<?php _e("Sat", "appointzilla"); ?>"],
            buttonText: {
                today: "<?php _e("Today", "appointzilla"); ?>",
                day: "<?php _e("Day", "appointzilla"); ?>",
                week:"<?php _e("Week", "appointzilla"); ?>",
                month:"<?php _e("Month", "appointzilla"); ?>"
            },

            <?php if($DateFormat == 'd-m-Y') $DPFormat = 'dd-mm-yy';
            if($DateFormat == 'm-d-Y') $DPFormat = 'mm-dd-yy';
            if($DateFormat == 'Y-m-d') $DPFormat = 'yy-mm-dd'; //coz yy-mm-dd not parsing in a correct date  ?>
            selectable: false,
            selectHelper: false,
            select: function(start, end, allDay) {

                var appdate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(start));
                var appdate2 = jQuery.datepicker.formatDate('dd-mm-yy', new Date(start));
                var check = jQuery.fullCalendar.formatDate(start,'yyyy-MM-dd');
                var today = jQuery.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
                if(check < today) {
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
                <?php
                //Loading Normal Appointments On Calendar Start
                $AllAppointments = $wpdb->get_results($FetchAllApps_sql, OBJECT);
                if($AllAppointments) {
                    foreach($AllAppointments as $single) {
                        $title = $single->name;
                        $start = date("H, i", strtotime($single->start_time));
                        $end= date("H, i", strtotime($single->end_time));
                        // subtract 1 from month digit coz calendar work on month 0-11
                        $y = date ( 'Y' , strtotime( $single->date ) );
                        $m = date ( 'n' , strtotime( $single->date ) ) - 1;
                        $d = date ( 'd' , strtotime( $single->date ) );
                        $date = "$y-$m-$d";
                        $date = str_replace("-",", ", $date); ?>
                    {
                        title: "<?php _e('Appointment with', 'appointzilla'); echo ": ".ucwords($title); ?>",
                        start: new Date(<?php echo "$date, $start"; ?>),
                        end: new Date(<?php echo "$date, $end"; ?>),
                        allDay: false,
                        backgroundColor : '#1FCB4A',
                        textColor: 'black',
                    },
                <?php
            }
        }
    //Loading Appointments On Calendar End

    //Loading Recurring Appointments On Calendar Start
        $AllRecurringAppointments = $wpdb->get_results($FetchAllRApps_sql, OBJECT);
        if($AllRecurringAppointments) {
            foreach($AllRecurringAppointments as $single) {
                if($single->recurring_type != 'monthly') {
                    $title = $single->name;
                    $start_time = date("H, i", strtotime($single->start_time));
                    $end_time= date("H, i", strtotime($single->end_time));
                    $start_date = $single->recurring_st_date;
                    $end_date =  $single->recurring_ed_date;
                    //if appointment type then calculate RTC(recurring date calculation)
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
                            title: "<?php _e('Appointment with', 'appointzilla'); echo ": ".ucwords($title); ?>",
                            start: new Date(<?php echo "$eachdate, $start_time"; ?>),
                            end: new Date(<?php echo "$eachdate, $end_time"; ?>),
                            allDay: false,
                            backgroundColor : "<?php if($single->staff_id =='1') echo "#DD75DD"; else echo "#6A6AFF"; ?>",
                            textColor: "",
                        },
                <?php
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
                    $start_date2 = str_replace("-",", ", $start_date2); //changing date format
                    $end_date2 = str_replace("-",", ", $start_date2); ?>
                    {
                        title: "<?php _e('Appointment with', 'appointzilla'); echo ": ".ucwords($title); ?>",
                        start: new Date(<?php echo "$start_date2, $start_time"; ?>),
                        end: new Date(<?php echo "$end_date2, $end_time"; ?>),
                        allDay: false,
                        backgroundColor : "<?php if($single->staff_id =='1') echo "#DD75DD"; else echo "#6A6AFF"; ?>",
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
            //convert time format H:i:s
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
            $enddate = str_replace("-",", ", $enddate);  ?>
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
        //dont show event on filtering
        if($AllREvents)	{
            foreach($AllREvents as $Event) {
                //convert time format H:i:s
                $starttime = date("H:i", strtotime($Event->start_time));
                $endtime = date("H:i", strtotime($Event->end_time));
                //change time format according to calendar
                $starttime = str_replace(":",", ", $starttime);
                $endtime = str_replace(":", ", ", $endtime);

                $startdate = $Event->start_date;
                $enddate = $Event->end_date;

                if($Event->repeat != 'M') {
                    //if event type then calculate RTC(recurring date calculation)
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
    });
    </script>
    <div id='calendar' style="margin:15px;"></div>
<?php } else {
        ?><br><div class="alert alert-block" style="margin-right: 16px;"><strong><?php _e("Notice", "appointzilla"); ?></strong> <?php _e("This page contents are available only for authorised users.", "appointzilla"); ?></div><?php
} ?>