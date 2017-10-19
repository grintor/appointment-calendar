<?php
$DateFormat = get_option('apcal_date_format');
if($DateFormat == '') $DateFormat = "d-m-Y";
$TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $TimeFormat = "h:i";

global $wpdb;
$AppointmentTableName = $wpdb->prefix."ap_appointments";
$EventTableName = $wpdb->prefix."ap_events";
$StaffTable = $wpdb->prefix."ap_staff";
$Select = 0;

//check 2 way sync enable
$GoogleCalendarTwoWaySync = get_option('google_calendar_twoway_sync');
if($GoogleCalendarTwoWaySync == 'yes') {
    // fetch google appointment
    require_once('sync-google-appointment.php');
}

// load appointment n event from last past month
$Current_Month_First_Date = strtotime(date("Y-m-01"));
$Load_From_Last_Month = date("Y-m-d", strtotime("-1 month", $Current_Month_First_Date));
//only for recurring app
$Load_Recurring_From_Last_Month = date("Y-m-d", strtotime("-6 month", $Current_Month_First_Date));

if(isset($_POST['filterbystaff']) && isset($_POST['bystaff'])) {
    $StaffId = $_POST['bystaff'];
    $Select = $StaffId;
    //appointment sql
    $FetchAllApps_sql = "select `id`, `name`, `service_id`, `staff_id`, `start_time`, `end_time`, `date` FROM `$AppointmentTableName` WHERE `recurring` = 'no' AND `staff_id` = '$StaffId' AND `date` >= '$Load_From_Last_Month'";

    //recurring app sql
    $FetchAllRApps_sql = "select * FROM `$AppointmentTableName` WHERE `recurring` = 'yes' AND `staff_id` = '$StaffId' AND `recurring_st_date` >= '$Load_Recurring_From_Last_Month'";
} else {
    $FetchAllApps_sql = "select `id`, `name`, `service_id`, `staff_id`, `start_time`, `end_time`, `date` FROM `$AppointmentTableName` WHERE `recurring` = 'no' AND `date` >= '$Load_From_Last_Month'";

    $FetchAllRApps_sql = "select * FROM `$AppointmentTableName` WHERE `recurring` = 'yes' AND `recurring_st_date` >= '$Load_Recurring_From_Last_Month'";
}

//Normal Event
$FetchAllEvent_sql = "SELECT `id`, `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` WHERE `repeat` = 'N' AND `start_date` >= '$Load_From_Last_Month' ";

// Recurring Event
$FetchAllREvent_sql = "select `id`, `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` where `repeat` != 'N' AND `start_date` >= '$Load_Recurring_From_Last_Month' "; ?>

<!---render fullcalendar--->
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
        firstDay: <?php if($AllCalendarSettings['calendar_start_day']) echo $AllCalendarSettings['calendar_start_day']; else echo "0";  ?>,
        slotMinutes: <?php echo $AllCalendarSettings['calendar_slot_time']; ?>,
        minTime: 0,
        defaultView: '<?php echo $AllCalendarSettings['calendar_view']; ?>',
        maxTime: 24,
        <?php if($DateFormat == 'd-m-Y') $DPFormat = 'dd MM yy';
        if($DateFormat == 'm-d-Y') $DPFormat = 'MM dd yy';
        if($DateFormat == 'Y-m-d') $DPFormat = 'MM dd yy'; //coz yy-mm-dd not parsing in a correct date ?>
        selectable: true,
        selectHelper: false,
        select: function(start, end, allDay) {
            var appdate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(start));
            var appdate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(start));
            jQuery('#appdate').val(appdate);
            jQuery('#AppFirstModal').show();
            var cnvtdate = jQuery.datepicker.formatDate('yy, mm, dd', new Date(start));
            jQuery('#datepicker').datepicker("setDate", new Date(cnvtdate) );
        },
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
        events: [
            <?php //Loading Appointments On Calendar Start
            $AllAppointments = $wpdb->get_results($FetchAllApps_sql, OBJECT);
            if($AllAppointments) {
                foreach($AllAppointments as $single) {
                    $title = $single->name;
                    $start = date("H, i", strtotime($single->start_time));
                    $end= date("H, i", strtotime($single->end_time));

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
                    $date = str_replace("-",", ", $date);
                    $url = "?page=update-appointment&viewid=".$single->id."&from=calendar"; ?>
                    {
                        id :'<?php echo $single->id; ?>',
                        title: "<?php  _e("Booked By",'appointzilla'); echo ": ".ucwords($title); ?>",
                        start: new Date(<?php echo "$date, $start"; ?>),
                        end: new Date(<?php echo "$date, $end"; ?>),
                        url: "<?php echo $url; ?>",
                        allDay: false,
                        backgroundColor : "<?php echo $StaffAppointmentColor; ?>",
                        textColor: "black",
                        description: "appointment",
                    }, <?php
                }
            }
            //Loading Appointments On Calendar End-

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

                    $title = $single->name;
                    if($single->recurring_type != 'monthly') {
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

                            $url = "?page=update-appointment&viewid=".$single->id."&from=calendar";
                            // subtract 1 from month digit coz calendar work on month 0-11
                            $y = date ( 'Y' , strtotime( $currentDateStr ) );
                            $m = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                            $d = date ( 'd' , strtotime( $currentDateStr ) );
                            $eachdate = "$y-$m-$d";
                            //change format
                            $eachdate = str_replace("-",", ", $eachdate); ?>
                            {
                                id :'<?php echo $single->id; ?>',
                                title: "<?php _e('Booked By: ','appointzilla'); echo ucwords($title); ?>",
                                start: new Date(<?php echo "$eachdate, $start_time"; ?>),
                                end: new Date(<?php echo "$eachdate, $end_time"; ?>),
                                url: "<?php echo $url; ?>",
                                allDay: false,
                                backgroundColor : "<?php echo $StaffAppointmentColor; ?>",
                                textColor: "",
                                description: "appointment",
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
                                    id :'<?php echo $single->id; ?>',
                                    title: "<?php _e('Booked By: ','appointzilla'); echo ucwords($title); ?>",
                                    start: new Date(<?php echo "$start_date2, $start_time"; ?>),
                                    end: new Date(<?php echo "$end_date2, $end_time"; ?>),
                                    url: "<?php echo $url; ?>",
                                    allDay: false,
                                    backgroundColor : "<?php echo $StaffAppointmentColor; ?>",
                                    textColor: "",
                                    description: "appointment",
                                }, <?php
                                $i = $i+1;
                            } while(strtotime($end_date) != strtotime($NextDate));
                    }// end of else

                } // end of fetching single appointment foreach
            } // end of if
            //Loading Recurring Appointments On Calendar End

            // Loading Events On Calendar Start
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
                    $startdate = str_replace("-",", ", $startdate);     //changing date format

                    $enddate = $Event->end_date;
                    // subtract 1 from $startdate month digit coz calendar work on month 0-11
                    $y2 = date ( 'Y' , strtotime( $enddate ) );
                    $m2 = date ( 'n' , strtotime( $enddate ) ) - 1;
                    $d2 = date ( 'd' , strtotime( $enddate ) );
                    $enddate = "$y2-$m2-$d2";

                    $enddate = str_replace("-",", ", $enddate);         //changing date format
                    $url = "?page=update-timeoff&update_timeoff=".$Event->id."&from=calendar"; ?>
                    {
                        id :'<?php echo $Event->id; ?>',
                        title: "<?php echo ucwords($Event->name); ?>",
                        start: new Date(<?php echo "$startdate, $starttime"; ?>),
                        end: new Date(<?php echo "$enddate, $endtime"; ?>),
                        url: "<?php echo $url; ?>",
                        allDay: false,
                        backgroundColor : "#FF7575",
                        textColor: "black",
                        description: "timeoff",
                    }, <?php
                }
            }
            //Loading Events On Calendar End

            //Loading Recurring Events On Calendar Start
            $AllREvents = $wpdb->get_results($FetchAllREvent_sql, OBJECT);
            //dont show event on filtering
            if($AllREvents) {
                foreach($AllREvents as $Event) {
                    if($Event->repeat != 'M') {
                        //convert time format H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        //change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);

                        $startdate = $Event->start_date;
                        $enddate = $Event->end_date;

                        //if appointment type then calculate RTC(recurring date calculation)
                        if($Event->repeat == 'PD')
                        $RDC = 1;
                        if($Event->repeat == 'D')
                        $RDC = 1;
                        if($Event->repeat == 'W')
                        $RDC = 7;
                        if($Event->repeat == 'BW')
                        $RDC = 14;
                        /*if($Event->repeat == 'M')
                        $RDC = 31;*/

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
                            $startdate = str_replace("-",", ", $startdate);     //changing date format

                            // subtract 1 from $startdate month digit coz calendar work on month 0-11
                            $y2 = date ( 'Y' , strtotime( $currentDateStr ) );
                            $m2 = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                            $d2 = date ( 'd' , strtotime( $currentDateStr ) );
                            $enddate = "$y2-$m2-$d2";

                            $enddate = str_replace("-",", ", $enddate);     //changing date format
                            $url = "?page=update-timeoff&update_timeoff=".$Event->id."&from=calendar"; ?>
                            {
                                id :'<?php echo $Event->id; ?>',
                                title: "<?php echo ucwords($Event->name); ?>",
                                start: new Date(<?php echo "$startdate, $starttime"; ?>),
                                end: new Date(<?php echo "$enddate, $endtime"; ?>),
                                url: "<?php echo $url; ?>",
                                allDay: false,
                                backgroundColor : "#FF7575",
                                textColor: "black",
                                description: "timeoff",
                            }, <?php
                        }
                    } else { // end of if
                        //convert time format H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        //change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);
                        $startdate = $Event->start_date;
                        $enddate = $Event->end_date;
                        $i = 0;
                        do {
                            $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($startdate)));
                            // subtract 1 from $startdate month digit coz calendar work on month 0-11
                            $y = date ( 'Y' , strtotime( $NextDate ) );
                            $m = date ( 'n' , strtotime( $NextDate ) ) - 1;
                            $d = date ( 'd' , strtotime( $NextDate ) );
                            $startdate2 = "$y-$m-$d";
                            $startdate2 = str_replace("-",", ", $startdate2);       //changing date format
                            $enddate2 = str_replace("-",", ", $startdate2); ?>
                            {
                                id :'<?php echo $Event->id; ?>',
                                title: "<?php echo ucwords($Event->name); ?>",
                                start: new Date(<?php echo "$startdate2, $starttime"; ?>),
                                end: new Date(<?php echo "$enddate2, $endtime"; ?>),
                                url: "<?php echo $url; ?>",
                                allDay: false,
                                backgroundColor : "#FF7575",
                                textColor: "black",
                                description: "timeoff",
                            }, <?php
                            $i = $i+1;
                        } while(strtotime($enddate) != strtotime($NextDate));
                    }// enf of else
                }// end of foreach
            }// end of if check
            // Loading Recurring Events On Calendar End ?>

        ],
        eventClick: function(event) {
            if (event.id) {
                var AppId  = event.id;
                var description = event.description;
                var searchIn = 'ac_appointment';
                if(description == 'timeoff') {
                    searchIn = 'timeoff'; } else  { searchIn = 'appointment';
                }
                var dataStringfirst = "AppId=" + AppId + "&searchIn=" + searchIn;
                var url = "?page=appointment-calendar";
                jQuery.ajax({
                    dataType : 'html',
                    type: 'POST',
                    url : url,
                    data : dataStringfirst,
                    complete : function() { },
                    success: function(data) {
                        data = jQuery(data).find('div#UpdateModalFinal');
                        jQuery("#AppUpdateModalFinal").show();
                        jQuery('#AppUpdateModalFinal').html(data);
                        showdate();
                    }
                });
                return true;
            }
        },
    }); // end of full-calendar js code


    // Launch Modal Form
    //show first modal
    jQuery('#addappointment').click(function(){
        jQuery('#AppFirstModal').show();
    });

    //hide modal
    jQuery('#close').click(function(){
        jQuery('#AppFirstModal').hide();
    });

    //load date picekr on modal for
    document.firstmodal.appdate.value = jQuery.datepicker.formatDate("<?php echo $DPFormat; ?>", new Date());
    jQuery(function(){
        jQuery("#datepicker").datepicker({
            inline: true,
            minDate: 0,
            altField: '#alternate',
            firstDay: <?php if($AllCalendarSettings['calendar_start_day']) echo $AllCalendarSettings['calendar_start_day']; else echo "0";  ?>,
            onSelect: function(dateText, inst) {
                var dateAsString = dateText;
                var seleteddate = jQuery.datepicker.formatDate("<?php echo $DPFormat; ?>", new Date(dateAsString));
                document.firstmodal.appdate.value = seleteddate;
            }
        });
    });
});
// end of document.ready()

function loadseconmodal() {
    var ServiceId = jQuery('#servicelist').val();
    var AppDate = jQuery('#appdate').val();
    var StaffId = jQuery('#stafflist').val();
    var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId;
    var url = "?page=appointment-calendar";
    jQuery('#loading1').show();     // loading button onclick next1 at first modal
    jQuery('#next1').hide();        //hide next button
    jQuery.ajax({
        dataType : 'html',
        type: 'GET',
        url : url,
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
function loadfirstmodal() {
    jQuery('#AppSecondModal').hide()
    jQuery('#AppFirstModal').show();
    jQuery('#next1').show();
}

//load second modal on back2 click
function loadsecondmodal() {
    jQuery('#AppThirdModal').hide();
    jQuery('#AppSecondModal').show();
    jQuery('#buttondiv').show();
}

// hide all
function hideAll() {
    jQuery('#Nclr').css('color', '#FE0000');
    jQuery('#Dclr').css('color', '#000000');
    jQuery('#Wclr').css('color', '#000000');
    jQuery('#Mclr').css('color', '#000000');
    jQuery('#PDclr').css('color', '#000000');

    jQuery('#dailydiv').hide();
    jQuery('#weeklydiv').hide();
    jQuery('#monthlydiv').hide();
    jQuery('#PDdiv').hide();
}


//show daily div
function showdaily() {
    jQuery('#Nclr').css('color', '#000000');
    jQuery('#Dclr').css('color', '#FE0000');
    jQuery('#Wclr').css('color', '#000000');
    jQuery('#Mclr').css('color', '#000000');
    jQuery('#PDclr').css('color', '#000000');

    jQuery('#dailydiv').show();
    jQuery('#weeklydiv').hide();
    jQuery('#monthlydiv').hide();
    jQuery('#PDdiv').hide();
}


//show weekly div
function showweekly() {
    jQuery('#Nclr').css('color', '#000000');
    jQuery('#Dclr').css('color', '#000000');
    jQuery('#Wclr').css('color', '#FE0000');
    jQuery('#Mclr').css('color', '#000000');
    jQuery('#PDclr').css('color', '#000000');
    jQuery('#dailydiv').hide();

    jQuery('#weeklydiv').show();
    jQuery('#monthlydiv').hide();
    jQuery('#PDdiv').hide();
}


//show month div
function showmonthly() {
    jQuery('#Nclr').css('color', '#000000');
    jQuery('#Dclr').css('color', '#000000');
    jQuery('#Wclr').css('color', '#000000');
    jQuery('#Mclr').css('color', '#FE0000');
    jQuery('#PDclr').css('color', '#000000');

    jQuery('#dailydiv').hide();
    jQuery('#weeklydiv').hide();
    jQuery('#monthlydiv').show();
    jQuery('#PDdiv').hide();
}


function showParticularDate() {
    jQuery('#Nclr').css('color', '#000000');
    jQuery('#Dclr').css('color', '#000000');
    jQuery('#Wclr').css('color', '#000000');
    jQuery('#Mclr').css('color', '#000000');
    jQuery('#PDclr').css('color', '#FE0000');

    jQuery('#dailydiv').hide();
    jQuery('#weeklydiv').hide();
    jQuery('#monthlydiv').hide()
    jQuery('#PDdiv').show();
}

// check valid select start time and End time 
function endtimechange() {
    var start_time = jQuery("select#start_time").val();
    var end_time =   jQuery("select#end_time").val();
    var startDate = new Date("1/1/1900 " + start_time);
    var endDate = new Date("1/1/1900 " + end_time);
    var difftime=endDate - startDate;
    if (difftime < 1) { alert("<?php echo __('Invalid time range.', 'appointzilla'); ?>");
        jQuery('#end_time_error').show();
        return false;
    } else {
        jQuery('#start_time_error').hide();
        jQuery('#end_time_error').hide();
    }
}

// check valid select start time and End time
function starttimechange() {
    var start_time = jQuery("select#start_time").val();
    var end_time =   jQuery("select#end_time").val();
    var startDate = new Date("1/1/1900 " + start_time);
    var endDate = new Date("1/1/1900 " + end_time);
    var difftime=endDate - startDate;

    if (difftime < 1) {
        alert("<?php echo __('Invalid time range.', 'appointzilla'); ?>");
        jQuery('#start_time_error').show();
          return false;
    } else {
         jQuery('#start_time_error').hide();
         jQuery('#end_time_error').hide();
    }
}

//recurring start date load date-picker
function loadstartdate() {
    jQuery(function(){
        jQuery('#re_start_date').datepicker({
            minDate: 0,
            dateFormat: "<?php echo $DPFormat; ?>",
        });
    });
 }

function loadenddate() {
    jQuery(function(){
        jQuery('#re_end_date').datepicker({
            minDate: 0,
            dateFormat: "<?php echo $DPFormat; ?>",
        });
    });
}

//load third modal on-click next2
function loadthirdmodal() {
    //validation on second modal form
    var ServiceId = jQuery('#ServiceId').val();
    var AppDate = jQuery('#AppDate').val();
    var StaffId = jQuery('#StaffId').val();
    var Start_Time =  jQuery('#start_time').val();
    var End_Time =  jQuery('#end_time').val();
    var RecurringType = jQuery('input:radio[name=repeat]:checked').val();
    var RepeatUnit = 'NULL';

    if(RecurringType == 'daily') {
        RepeatUnit = jQuery('#re_days').val();
        if(!RepeatUnit) {
            alert("<?php echo __('Repeat Days required', 'appointzilla'); ?>.");
            return false;
        }
    }

    if(RecurringType == 'weekly') {
        RepeatUnit = jQuery('#re_weeks').val();
        if(!RepeatUnit) {
            alert("<?php echo __('Repeat Weeks required.', 'appointzilla'); ?>");
            return false;
        }
    }

    if(RecurringType == 'monthly') {
        RepeatUnit = jQuery('#re_months').val();
        if(!RepeatUnit) {
            alert("<?php echo __('Repeat Months required.', 'appointzilla'); ?>");
            return false;
        }
    }

    // check validation for send request time  in valid or not valid
    var startDate = new Date("1/1/1900 " + Start_Time);
    var endDate = new Date("1/1/1900 " + End_Time);
    var difftime=endDate - startDate;
    if (difftime < 1) {
        jQuery('#end_time_error').show();
        jQuery('#start_time_error').show();
        return false;
    }

    if (End_Time =='-1') {
        jQuery('#end_time_error').show();
        return false;
    }

    //recurring particular date
    if(RecurringType=='PD') {
        var recurring_start_date = jQuery("input#re_start_date").val();
        var recurring_end_date = jQuery("input#re_end_date").val();
        jQuery('.error').hide();
        if (recurring_start_date == "") {
            jQuery("label#recurring_start_date_error").show();
            jQuery("label#recurring_start_date_error_date").hide();
            jQuery("input#recurring_start_date").focus();
                return false;
        }

        if (recurring_end_date == "") {
            jQuery("label#recurring_end_date_error").show();
            jQuery("input#recurring_end_date").focus();
            return false;
        }
        var recurringstartdate = new Date(recurring_start_date);
        var recurringenddate = new Date(recurring_end_date);
        var diff = new Date(recurringenddate - recurringstartdate);
        var days = ((diff)/1000/60/60/24);

        if(days >= 0) {
            var ThirdData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + Start_Time +  "&EndTime=" + End_Time + "&RecurringType=" + RecurringType +'&recurring_start_date=' + recurring_start_date + '&recurring_end_date=' + recurring_end_date + "&RepeatUnit="+ RepeatUnit;
        } else {
            jQuery("label#recurring_start_date_error_date").show();
            alert("<?php __('Invalid Date! Start-Date should be less than End-Date.', 'appointzilla'); ?>");
            return false;
        }
    } else {
        var ThirdData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + Start_Time +  "&EndTime=" + End_Time + "&RecurringType=" + RecurringType + "&RepeatUnit="+ RepeatUnit;
    }

    var url = "?page=appointment-calendar";
    jQuery('#buttondiv').hide();
    jQuery('#loading').show();
    jQuery.ajax({
        dataType : 'html',
        type: 'GET',
        url : url,
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
function checkvalidation() {
    jQuery('.error').hide();
    var client_name = jQuery("input#client_name").val();
    if (client_name == "") {
        jQuery("#client_name").after("<span class='error'>&nbsp;<br><strong><?php _e('Name required.', 'appointzilla'); ?></strong></span>");
        return false;
    } else {
        var NRes = isNaN(client_name);
        if(NRes == false) {
            jQuery("#client_name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid name.', 'appointzilla'); ?></strong></span>");
            return false;
        }
        var NameRegx = /^[a-zA-Z0-9- ]*$/;
        if(NameRegx.test(client_name) == false) {
            jQuery("#client_name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
            return false;
        }
    }

    var client_email = jQuery("input#client_email").val();
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (client_email == "") {
        jQuery("#client_email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
        return false;
    } else {
        if(regex.test(client_email) == false ) {
            jQuery("#client_email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
            return false;
        }
    }

    var client_phone = jQuery("input#client_phone").val();
    if (client_phone == "") {
        jQuery("#client_phone").after("<span class='error'>&nbsp;<br><strong><?php _e('Phone required.', 'appointzilla'); ?></strong></span>");
        return false;
    } else {
        var client_phone = isNaN(client_phone);
        if(client_phone == true) {
            jQuery("#client_phone").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid value.', 'appointzilla'); ?></strong></span>");
            return false;
        }
    }

    var ServiceId = jQuery('#ServiceId').val();
    var AppDate = jQuery('#AppDate').val();
    var StaffId = jQuery('#StaffId').val();
    var StartTime =  jQuery('#StartTime').val();
    var EndTime =  jQuery('#EndTime').val();
    var RecurringType =  jQuery('#RecurringType').val();
    var RepeatUnit  = jQuery('#RepeatUnit').val();
    var RecurringStartDate  =  jQuery('#RecurringStartDate').val();
    var RecurringEndDate =  jQuery('#RecurringEndDate').val();
    var Client_Name =  jQuery('#client_name').val();
    var Client_Email =  jQuery('#client_email').val();
    var Client_Phone =  jQuery('#client_phone').val();
    var Client_Note =  jQuery('#client_note').val();
    var ForthData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + StartTime +  "&EndTime=" + EndTime + "&RecurringType=" + RecurringType +'&RecurringStartDate=' + RecurringStartDate + '&RecurringEndDate=' + RecurringEndDate +'&Client_Name=' + Client_Name +'&Client_Email=' + Client_Email +'&Client_Phone=' + Client_Phone +'&Client_Note=' + Client_Note + "&RepeatUnit="+ RepeatUnit;
    var url = "?page=appointment-calendar";
    jQuery('#formbtndiv').hide();
    jQuery('#sheduling').show();

    jQuery.ajax({
        dataType : 'html',
        type: 'GET',
        url : url,
        cache: false,
        data : ForthData,
        complete : function() {  },
        success: function(data) {
            jQuery('#AppThirdModal').hide();
            data = jQuery(data).find('div#AppForthModalData');
            jQuery('#AppForthModalFinal').show();
            jQuery('#AppForthModalFinal').html(data);
        }
    });
}

function CloseModelform() {
    jQuery('#AppForthModalFinal').hide();
    jQuery('#AppSecondModalData').hide();
    jQuery('#AppThirdModalData').hide();
    location.href = location.href;
}
</script>

<style type='text/css'>
.error{ 
    color:#FF0000;
}

#calendar {
    width: auto;
    margin: 4px 4px;;
}
#bkbtndiv{
    margin: 5px;
}
tr th 
{
    text-align:left;
}
.inputwidth
{
    width:300px;
}
</style>

<div style=" border:#000000 solid 0px;" >
    <form action="" method="post" name="filter-form">
        <table width="100%" border="0">
            <tr>
                <th width="16%" valign="top" scope="row">&nbsp;</th>
                <td width="11%">&nbsp;</td>
                <td width="14%">&nbsp;</td>
                <td width="21%"></td>
                <td width="10%">&nbsp;</td>
                <td width="28%"></td>
            </tr>
            <tr>
                <th valign="top" scope="row"><strong><?php _e('Filter Appointments By Staff', 'appointzilla'); ?></strong></th>
                <td>&nbsp;</td>
                <td colspan="2"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th scope="row">
                    <select name="bystaff" id="bystaff">
                        <option value="NULL"><?php _e('Select Staff', 'appointzilla'); ?></option>
                        <?php
                        global $wpdb;
                        $StaffTableName = $wpdb->prefix . "ap_staff";
                        $AllStaff = $wpdb->get_results("SELECT * FROM `$StaffTableName`", OBJECT);
                        if($AllStaff) {
                            foreach($AllStaff as $Staff) {
                                $StaffColor = $Staff->color;
                            ?>
                            <option style="color: <?php echo $StaffColor; ?>;" value="<?php echo $Staff->id; ?>" <?php if($Select == $Staff->id) echo "selected"; ?>>&nbsp;&nbsp;<strong><?php echo ucwords($Staff->name); ?></strong></option><?php
                            }
                        } ?>
                    </select>
                </th>
                <td>&nbsp;</td>
                <td colspan="2" align="center">
                    <button name="addappointment" class="btn btn-primary" type="button" id="addappointment">
                        <i class="icon-calendar icon-white"></i> <?php if(isset($AllCalendarSettings['booking_button_text'])) {
                            echo $AllCalendarSettings['booking_button_text'];
                        } else {
                            _e('Add New Appointment', 'appointzilla');
                        }?>
                    </button>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th scope="row"><button name="filterbystaff" id="filterbystaff" class="btn btn-small btn-danger" type="submit"><i class="icon-ok icon-white"></i> <?php _e('Apply', 'appointzilla'); ?></button></th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </form>
</div>


<!---show full-calendar--->
<div id='calendar' style="margin:10px;"></div>

<!---AppFirstModal Start--->
<div id="AppFirstModal" style="display:none;">
    <div class="modal" id="FirstModal" >
    <form action="" method="post" name="firstmodal" id="firstmodal">
        <div class="modal-info">
            <div style="float:right; margin-top:18px; margin-right:40px;">
                <a id="close" ><i class="icon-remove"></i></a>
            </div>
            <div class="alert alert-info">
                <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p><?php _e('Step-1. Select Date & Service', 'appointzilla'); ?>
            </div>
        </div>

        <div class="modal-body">
            <div id="firdiv" style="float:left;">
                <div id="datepicker"></div>
            </div>

            <div id="secdiv" style="float:right; margin-right:5%; width:40%" >
                <strong><?php _e('Your Appointment Date', 'appointzilla'); ?></strong>
                <input name="appdate" id="appdate" type="text" disabled="disabled" style="width:100%"/>
                <?php	global $wpdb;
                $CategoryTable = $wpdb->prefix."ap_service_category";
                $findcategory_sql="SELECT * FROM `$CategoryTable`  order by `name` ASC";
                $Allcategory = $wpdb->get_results($findcategory_sql, OBJECT); ?>
                    
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

                    foreach($Allcategory as $Category) {
                        echo "<option value='$Category->id' disabled class='mycss'>".ucwords($Category->name)."</option>";
                            $ServiceTable = $wpdb->prefix."ap_services";
                        $findservice_sql = "SELECT * FROM `$ServiceTable` WHERE `availability` = 'yes' and `category_id` = '$Category->id' order by `name` ASC";
                        $AllService = $wpdb->get_results($findservice_sql, OBJECT);
                        foreach($AllService as $Service) {
                            echo "<option value='$Service->id'>&nbsp;&nbsp;&nbsp;".ucwords($Service->name)." (".$Service->duration."min/$cal_admin_currency$Service->cost)</option>";
                        }
                    } ?>
                </select><br>

                <script type="text/javascript">
                    //load staff according to service -  start
                    jQuery('#servicelist').change(function(){
                        var ServiceId = jQuery("select#servicelist").val();
                        if(ServiceId > 0) {
                            jQuery('#loading-staff').show();
                            jQuery('#staff').hide();
                            var FirstData = "ServiceId=" + ServiceId;
                            var url = "?page=appointment-calendar";
                            jQuery.ajax({
                                 dataType : 'html',
                                 type: 'GET',
                                 url : url,
                                 data : FirstData,
                                 complete : function() { },
                                 success: function(data) {
                                    data=jQuery(data).find('div#stfflistdiv');
                                    jQuery('#loading-staff').hide();
                                    jQuery('#staff').show();
                                    jQuery('#staff').html(data);
                                }
                            });
                        } else {
                            jQuery('#staff').hide();
                        }
                    });
                </script>
                <div id="loading-staff" style="display:none;"><?php _e('Loading Staff...', 'appointzilla'); ?><img src="<?php echo plugins_url()."/appointment-calendar-premium/images/loading.gif"; ?>" /></div>
                <div id="staff"></div>
            </div> <!--end secdiv-->
        </div><!--end modal-body-->
    </form>
    </div>
</div>
<!---AppFirstModal End--->

<!---AppSecondModal Start--->
<div id="AppSecondModal" style="display:none;"></div>
<!---AppSecondModal End--->


<!---AppThirdModal Start--->
<div id="AppThirdModal" style="display:none;"></div>
<!---AppThirdModal End--->


<!---AppForthModal Start--->
<div id="AppForthModalFinal" style="display:none;"></div>
<!---AppThirdModal ENd-->

<!---staff loading ajax return code--->
<?php if(isset($_GET['ServiceId'])) { ?>
    <!---load bootstrap css--->
    <link rel='stylesheet' type='text/css' href='<?php echo plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__); ?>' />
    <div id="stfflistdiv">
        <strong><?php _e("Select Staff", 'appointzilla'); ?></strong>
        <select name='stafflist' id='stafflist' style="width:100%">
            <?php //get all staff id list by service id
                $ServiceID = $_GET['ServiceId'];
                if($ServiceID) {
                    $ServiceTableName = $wpdb->prefix . "ap_services";
                    $AllStaffIdList = $wpdb->get_row("SELECT `staff_id` FROM `$ServiceTableName` WHERE `ID` = '$ServiceID'", OBJECT);
                    $AllStaffIdList = unserialize($AllStaffIdList->staff_id);
                    foreach($AllStaffIdList as $StaffId) {
                        $StaffTableName = $wpdb->prefix . "ap_staff";
                        $Staff = $wpdb->get_results("SELECT `id`, `name` FROM `$StaffTableName` WHERE `id` = '$StaffId'");
                        echo "<option value='".$Staff[0]->id."'>&nbsp;&nbsp;&nbsp;".$Staff[0]->name."</option>";
                    }
                } ?>
        </select>
        <br>
        <button type="button" class="btn" value="" id="next1" name="next1" onclick="loadseconmodal()"> <?php _e('Next', 'appointzilla'); ?> <i class="icon-arrow-right"></i></button>
        <div id="loading1" style="display:none;"><?php _e('Loading...', 'appointzilla'); ?><img src="<?php echo plugins_url()."/appointment-calendar-premium/images/loading.gif"; ?>" /></div>
    </div><?php
} ?>


<!---loading second modal form ajax return code--->
<?php if( isset($_GET['ServiceId']) && isset($_GET['AppDate']) && isset($_GET['StaffId']) ) { ?>
    <div id="AppSecondModalData">
        <div class="modal" id="SecondModal" >
        <form action="" method="post" name="secondmodal" id="secondmodal">
            <input name="ServiceId" id="ServiceId" type="hidden" value="<?php echo $_GET['ServiceId']; ?>" />
            <input name="StaffId" id="StaffId" type="hidden" value="<?php echo $_GET['StaffId']; ?>" />
            <input name="AppDate" id="AppDate" type="hidden" value="<?php echo $_GET['AppDate']; ?>" />

            <div class="modal-info">
                <div style="float:right; margin-top:18px; margin-right:40px;">
                    <a href="" onclick="CloseModelform()" id="close" ><i class="icon-remove"></i></a>
                </div>
                <div class="alert alert-info">
                    <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p><?php _e('Step-2. Select Service Time & Repeat', 'appointzilla'); ?>
                </div>
            </div><!--end modal-info-->

            <div class="modal-body">
                <div id="time-slot-list">
                <?php require_once('admin-time-slot-calculation.php'); ?>
                </div>
            </div>
            <?php if($AllDayEvent) {
                echo "<div align='center' class='alert alert-error'><strong>".__('Sorry! No Time Available Today.', 'appointzilla')."</strong></div>";
                echo "<button type='button' class='btn' id='back1' name='back1' onclick='loadfirstmodal()'><i class='icon-arrow-left'></i> ".__('Back', 'appointzilla')."</button>";
            }

            if($AllDayEvent == 0 && $AllDayDisableSlots == 1 && $AllDayEnableSlots == 0) {
                echo "<br><p align=center class='alert alert-error'><strong>".__("Sorry! Today's all appointments has been booked.", 'appointzilla')."</strong></p>";
                echo "<button type='button' class='btn' id='back1' name='back1' onclick='loadfirstmodal()'><i class='icon-arrow-left'></i> ".__("Back", 'appointzilla')."</button>";
            }

            if($AllDayEvent == 0 && $AllDayEnableSlots == 1) { ?>
                <strong><?php _e('Repeat', 'appointzilla'); ?>:</strong><br />&nbsp;
                <input name="repeat" id="repeat" type="radio" value="N" onclick="hideAll()" checked="checked" /> <span id="Nclr" style="color:#FE0000;"><?php _e('None', 'appointzilla'); ?></span>&nbsp;
                <input name="repeat" id="repeat" type="radio" value="daily" onclick="showdaily()" /> <span id="Dclr"><?php _e('Daily', 'appointzilla'); ?></span>&nbsp;
                <input name="repeat" id="repeat" type="radio" value="weekly" onclick="showweekly()" /> <span id="Wclr"><?php _e('Weekly', 'appointzilla'); ?></span>&nbsp;
                <input name="repeat" id="repeat" type="radio" value="monthly" onclick="showmonthly()" /> <span id="Mclr"><?php _e('Monthly', 'appointzilla'); ?></span>&nbsp;
                <input name="repeat" id="repeat" type="radio" value="PD" onclick="showParticularDate()" /> <span id="PDclr"><?php _e('Particular Dates', 'appointzilla'); ?></span>

                <div id="dailydiv" name="dailydiv" style="display:none;">
                    <br />
                    <strong><?php _e('Repeat Day(s)', 'appointzilla'); ?>:</strong>
                    <input name="re_days" id="re_days" type="text" style="width:40px;" value="1" maxlength="2" /> <?php _e("(Min = 1, Max = 99 Days)", 'appointzilla'); ?>
                </div>

                <div id="weeklydiv" name="weeklydiv" style="display:none;">
                    <br />
                    <strong><?php _e('Repeat Week(s)', 'appointzilla'); ?>:</strong>
                    <input name="re_weeks" id="re_weeks" type="text" style="width:40px;" value="1" maxlength="2"/>
                    <?php _e('(Min = 1, Max = 99 Weeks)', 'appointzilla'); ?>
                </div>

                <div id="monthlydiv" style="display:none;">
                    <br />
                    <strong><?php _e('Repeat Month(s)', 'appointzilla'); ?>:</strong>
                    <input name="re_months" id="re_months" type="text" style="width:40px;" value="1" maxlength="2"/>
                    <?php _e('(Min = 1, Max = 99 Months)', 'appointzilla'); ?>
                </div>

                <div id="PDdiv" style="display:none;">
                    <br />
                    <strong><?php _e('Start Date', 'appointzilla'); ?>:</strong>
                    <input name="re_start_date" id="re_start_date" type="text" onmousedown="loadstartdate()" style="width:160px;" />
                    <label class="error" id="recurring_start_date_error" style="margin-left:80px;"><strong><?php _e('This field is required.', 'appointzilla'); ?></strong></label><br>
                    <strong><?php _e('End Date', 'appointzilla'); ?>:</strong> <input name="re_end_date" id="re_end_date" type="text"  onmousedown="loadenddate()"style="width:160px;" />
                    <label class="error" for="phone" id="recurring_end_date_error" style="margin-left:80px;"><strong><?php _e('This field is required.', 'appointzilla'); ?></strong></label>
                    <label class="error" for="phone" id="recurring_start_date_error_date" style="display:none;"><strong><?php _e('Invalid Date', 'appointzilla'); ?></strong></label>
                </div>

                <br><br>
                <div id="buttondiv" align="center">
                    <button type="button" class="btn" value="" id="back1" name="back1" onclick="loadfirstmodal()"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></button>
                    <button type="button" class="btn" value="" id="next2" name="next2" onclick="loadthirdmodal()"><?php _e('Next', 'appointzilla');?> <i class="icon-arrow-right"></i></button>
                </div>
                <div id="loading" align="center" style="display:none;"><?php _e('Loading...', 'appointzilla'); ?><img src="<?php echo plugins_url()."/appointment-calendar-premium/images/loading.gif"; ?>" /></div><?php
            } // end of else ?>
            <br>
            <div class='alert alert-error' align="center"><?php _e('Admin can create appointments at any time.', 'appointzilla'); ?></div>
         </form>
        </div>
    </div><?php
} ?>


<!---loading third modal form ajax return code--->
<?php if( isset($_GET['StartTime']) && isset($_GET['EndTime']) ) { ?>
    <div id="AppThirdModalData">
        <div class="modal" id="AppThirdModal" >
        <form action="" method="post" name="thirdmodal" id="thirdmodal" onsubmit="checkvalidation()">
            <input name="ServiceId" id="ServiceId" type="hidden" value="<?php if(isset($_GET['ServiceId'])) { echo $_GET['ServiceId']; } ?>" />
            <input name="StaffId" id="StaffId" type="hidden" value="<?php if(isset($_GET['StaffId'])) { echo $_GET['StaffId']; } ?>" />
            <input name="AppDate" id="AppDate" type="hidden" value="<?php if(isset($_GET['AppDate'])) { echo $_GET['AppDate']; } ?>" />
            <input name="StartTime" id="StartTime" type="hidden" value="<?php if(isset($_GET['StartTime'])) { echo $_GET['StartTime']; } ?>" />
            <input name="EndTime" id="EndTime" type="hidden" value="<?php if(isset($_GET['EndTime'])) { echo $_GET['EndTime']; } ?>" />
            <input name="RepeatUnit" id="RepeatUnit" type="hidden" value="<?php if(isset($_GET['RepeatUnit'])) { echo $_GET['RepeatUnit']; } ?>" />
            <input name="RecurringType" id="RecurringType" type="hidden" value="<?php if(isset($_GET['RecurringType'])) { echo $_GET['RecurringType']; } ?>" />
            <input name="RecurringStartDate" id="RecurringStartDate" type="hidden" value="<?php if(isset($_GET['recurring_start_date'])) { echo $_GET['recurring_start_date']; } ?>" />
            <input name="RecurringEndDate" id="RecurringEndDate" type="hidden" value="<?php if(isset($_GET['recurring_end_date'])) { echo $_GET['recurring_end_date']; } ?>" />

            <div class="modal-info">
                <div style="float:right; margin-top:18px; margin-right:40px;">
                    <a href="" onclick="CloseModelform()" id="close" ><i class="icon-remove"></i></a>
                </div>
                <div class="alert alert-info">
                    <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p><?php _e('Step-3. Fill Up Client Details', 'appointzilla'); ?>
                </div>
            </div><!--end modal-info-->

            <div class="modal-body">
                <table width="100%" class="table">
                    <tr>
                        <th scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="client_name" type="text" id="client_name" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="client_email" type="text" id="client_email" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="client_phone" type="text" id="client_phone"  maxlength="20"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><textarea name="client_note" id="client_note"></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>&nbsp;</td>
                        <td>
                        <div id="formbtndiv">
                        <button type="button" class="btn" value="" id="back2" name="back2" onclick="loadsecondmodal()"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></button>
                        <button type="button" class="btn btn-success" value="" id="booknow" name="booknow" onclick="checkvalidation()"><i class="icon-ok icon-white"></i> <?php _e('Book Now', 'appointzilla'); ?></button>
                        </div>
                        <div id="sheduling" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url()."/appointment-calendar-premium/images/loading.gif"; ?>" /></div>
                        </td>
                    </tr>
                </table>
            </div><!--end modal-body-->
        </form>
        </div>
</div><?php
} ?>


<!---saving appointments--->
<?php if( isset($_GET['Client_Name']) && isset($_GET['Client_Email']) ) {
    $ServiceId= $_GET['ServiceId'];
    $StaffId = $_GET['StaffId'];
    $StartTime = date("h:i A", strtotime($_GET['StartTime']));
    $EndTime = date("h:i A", strtotime($_GET['EndTime']));
    $Client_name = strip_tags($_GET['Client_Name']);
    $Client_Email = $_GET['Client_Email'];
    $Client_Phone = $_GET['Client_Phone'];
    $Client_Note = strip_tags($_GET['Client_Note']);
    $AppointmentKey = md5(date("F j, Y, g:i a"));
    //$Status = "pending";

    if(isset($AllCalendarSettings['apcal_new_appointment_status'])) {
        $Status = $AllCalendarSettings['apcal_new_appointment_status'];
    } else {
        $Status = "pending";
    }

    $appointment_by = "admin";
    $RepeatUnit = $_GET['RepeatUnit'];
    $RecurringType = $_GET['RecurringType'];
    $payment_status = "unpaid";

    //check if recurring appointment
    if($RecurringType != 'N') {
        $Recurring = 'yes';
    } else {
        $AppDateNo = $_GET['AppDate'];
        $AppDate = date("Y-m-d", strtotime($AppDateNo));

        $Recurring = 'no';
        $RecurringType = 'none';
        $RecurringStartDate = date("Y-m-d", strtotime($AppDateNo));
        $RecurringEndDate = date("Y-m-d", strtotime($AppDateNo));
    }

    //calculate recurring date, if recurring is enable
    if($RecurringType == 'daily') {
        $AppDateNo = $_GET['AppDate'];
        $AppDate = date("Y-m-d", strtotime($AppDateNo));

        $RecurringStartDate = date("Y-m-d", strtotime($AppDateNo));
        $RecurringSDate = strtotime($RecurringStartDate);
        $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatUnit days", $RecurringSDate));     //add 3 month
    }

    if($RecurringType == 'weekly') {
        $AppDateNo = $_GET['AppDate'];
        $AppDate = date("Y-m-d", strtotime($AppDateNo));

        $RecurringStartDate = date("Y-m-d", strtotime($AppDateNo));
        $RecurringSDate = strtotime($RecurringStartDate);
        $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatUnit week", $RecurringSDate));     //add week
    }

    if($RecurringType == 'monthly') {
        //repeat for 1 month
        $AppDateNo = $_GET['AppDate'];
        $AppDate = date("Y-m-d", strtotime($AppDateNo));

        $RecurringStartDate = date("Y-m-d", strtotime($AppDateNo));
        $RecurringSDate = strtotime($RecurringStartDate);
        $RecurringEndDate = date("Y-m-d", strtotime("+$RepeatUnit months", $RecurringSDate));   //add 1 month
    }

    if($RecurringType == 'PD') {
        $AppDateNo = $_GET['AppDate'];
        $AppDate = date("Y-m-d", strtotime($AppDateNo));

        $RecurringStartDate = $_GET['RecurringStartDate'];
        $RecurringEndDate = $_GET['RecurringEndDate'];
        $RecurringStartDate = date("Y-m-d", strtotime("$RecurringStartDate"));
        $RecurringEndDate = date("Y-m-d", strtotime("$RecurringEndDate"));
    }
	
	// insert new client in client table
	global $wpdb;
	$clientstab = $wpdb->prefix . "ap_clients";
	$GetdeltClient = $wpdb->get_row("SELECT * FROM `$clientstab` WHERE `email` = '$Client_Email' ", OBJECT);
	if($GetdeltClient == "")
		{
		$insert_new_client = "INSERT INTO `$clientstab` (`id` ,`name` ,`email` ,`phone` ,`note`) VALUES ('NULL', '$Client_name', '$Client_Email', '$Client_Phone', '$Client_Note');";
		$wpdb->query($insert_new_client);
		}  
		
    global $wpdb;
    $AppointmentTableNAme = $wpdb->prefix ."ap_appointments";
    $Insert_Appointments = "INSERT INTO `$AppointmentTableNAme` (`id` ,`name` ,`email` ,`service_id` ,`staff_id` ,`phone` ,`start_time` ,`end_time` ,`date` ,`note` ,	`appointment_key` ,`status` ,`recurring` ,`recurring_type` ,`recurring_st_date` ,`recurring_ed_date` ,`appointment_by`,`payment_status`)	VALUES ('NULL', '$Client_name', '$Client_Email', '$ServiceId', '$StaffId', '$Client_Phone', '$StartTime', '$EndTime', '$AppDate', '$Client_Note', '$AppointmentKey', '$Status', '$Recurring', '$RecurringType', '$RecurringStartDate', '$RecurringEndDate', '$appointment_by', '$payment_status');";
    //save appointment send notification
    if($wpdb->query($Insert_Appointments)) {
        //$LastAppointmentId = mysql_insert_id();
		$LastAppointmentId = $wpdb->insert_id;
		
       
        $BlogName =  get_bloginfo();
        $ClientTable = $wpdb->prefix . "ap_clients";
        $GetClient = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$Client_Email' ", OBJECT);
        if($LastAppointmentId && $GetClient->id) {
            $AppId = $LastAppointmentId;
            $ServiceId = $ServiceId;
            $StaffId = $StaffId;
            $ClientId = $GetClient->id;

            //include notification class
            require_once('notification-class.php');
            $Notification = new Notification();
            $Notification->notifyadmin($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
            $Notification->notifyclient('booking', $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
            if(get_option('staff_notification_status') == 'on') {
                $Notification->notifystaff($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
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
                    require_once('google-appointment-sync-class.php');

                    //global $wpdb;
                    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                    // insert this appointment event on calendar
                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $tag = "Appointment with: ";
                    $OAuth = $GoogleAppointmentSync->NormalSync($Client_name, $AppDate, $StartTime, $EndTime, $ClientNote, $tag);
                    //insert appointment sync details
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
                    require_once('google-staff-appointment-sync-class.php');

                    // add this staff appointment event on his google calendar
                    $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                    $Tag = __("Appointment with: ", "appointzilla");
                    $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($StaffId, $Client_name, $AppDate, $StartTime, $EndTime, $ClientNote, $Tag);

                    //insert staff appointment sync details
                    global $wpdb;
                    $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                    $StaffOAuth = serialize($StaffOAuth);
                    $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$AppId', '$StaffOAuth' )");
                }//end of staff appointment sync

                //unset payment post variables
                //unset($_POST['address_status']); unset($_POST['payer_id']);

            } // end of sync appointment is approved
        }
    }//end of QUERY if ?>

    <div id="AppForthModalData">
        <div class="modal" id="AppForthModal">
            <div class="modal-info">
                <div class="alert alert-info">
                    <h4><?php _e('Schedule New Appointment', 'appointzilla'); ?></h4><?php _e('Step-4. Appointment has been scheduled.', 'appointzilla'); ?>
                </div>
            </div><!--end modal-info-->

            <div class="modal-body"><?php
                global $wpdb;
                $client_table = $wpdb->prefix."ap_clients";
                $ExitsClientdetails = $wpdb->get_row("SELECT * FROM `$client_table` WHERE `email` = '$Client_Email' ");
                if($ExitsClientdetails) {
                    // update  exiting client deatils
                    $update_client="UPDATE `$client_table` SET `name` = '$Client_name', `email` = '$Client_Email', `phone` = '$Client_Phone', `note` = ' $Client_Note' WHERE `id` =$ExitsClientdetails->id;";
                    $wpdb->query($update_client);
                    $ClientMessage ="Appointment for exiting client $Client_name: ($Client_Email) created.";
                } else {
                    // insert new client deatils
                    $insert_client="INSERT INTO `$client_table` (`id` ,`name` ,`email` ,`phone` ,`note`) VALUES ('NULL', '$Client_name', '$Client_Email', '$Client_Phone', '$Client_Note');";
                    $wpdb->query($insert_client);
                    $ClientMessage = "New Client $Client_name:($Client_Email) added to Database";
                }?>
                <div class="alert alert-info">
                    <?php echo $ClientMessage; ?>
                </div>
                <table width="100%" class="table">
                    <tr>
                        <th width="28%" scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%"><?php echo ucwords($Client_name); ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%"><?php echo $Client_Email;  ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%"><?php echo $Client_Phone;  ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Service', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%">
                        <?php
                            $ServiceTableName = $wpdb->prefix . "ap_services";
                            $ServiceName = $wpdb->get_row("SELECT `name` FROM `$ServiceTableName` WHERE `id` = '$ServiceId' ");
                            echo ucwords($ServiceName->name);
                        ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Staff', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%">
                            <?php $StaffTableName = $wpdb->prefix . "ap_staff";
                                $StaffName = $wpdb->get_row("SELECT `name` FROM `$StaffTableName` WHERE `id` = '$StaffId' ");
                                echo ucwords($StaffName->name); ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Date', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%">
                        <?php
                        if($Recurring == 'yes') {
                            echo date($DateFormat, strtotime($RecurringStartDate))." To ".date($DateFormat, strtotime($RecurringEndDate));
                        } else echo date($DateFormat, strtotime($AppDate));
                        ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Time', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%">
                            <?php  if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";
                                    echo date($TimeFormat, strtotime($StartTime))." - ".date($TimeFormat, strtotime($EndTime));  ?>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th width="22%" scope="row">Recurring Type</th>
                        <td width="15%">:</td>
                        <td width="63%"><?php //echo $RecurringType; ?></td>
                    </tr>-->
                    <tr>
                        <th width="28%" scope="row"><?php _e('Status', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%"><?php echo ucwords($Status);  ?></td>
                    </tr>
                    <tr>
                        <th width="28%" scope="row"><?php _e('Appointment Key', 'appointzilla'); ?></th>
                        <td width="1%"><strong>:</strong></td>
                        <td width="71%"><?php echo $AppointmentKey;  ?></td>
                    </tr>
                </table>
                <div style="float:right;">
                    <button type="button" onclick="CloseModelform()" name="close" id="close" value="Done" class="btn btn-primary"><?php _e('Done', 'appointzilla'); ?></button>
                </div>
            </div>
        </div>
    </div><?php
} ?>