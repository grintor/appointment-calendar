<?php

/**
 * Class: Appointzilla
 * Ver: 3.2
 * Author: FARAZ KHAN
 * Description: Appointzilla class
 */
if(!class_exists("Appointzilla")) {
    class Appointzilla
    {
        //public global $wpdb;
        public $ToadyBusinessClose = 1; //open
        public $ToadyStaffNotAvailable = 1; //available


        // This function accept appointment date and staff Id and return current date business hours or close day info.
        function GetBusiness($AppointmentDate, $StaffId) {
            // for admin flexibility
            return $BusinessHours = array('Biz_start_time' => "12:00 AM", 'Biz_end_time' => "11:59 PM");
        }


        // This function accept appointment date & staffId and check whether selected staff has any allday time off on selected date
        function CheckAllDayEvent($AppointmentDate, $StaffId) {
            global $wpdb;
            $TimeOffTableName = $wpdb->prefix."ap_events";
            //if today is any allday timeoff then show msg no time avilable today
            $TodaysAllTimeOff = $wpdb->get_results("SELECT `start_time`, `end_time`, `staff_id`, `repeat`, `start_date`, `end_date` FROM `$TimeOffTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '1'", OBJECT);

            foreach($TodaysAllTimeOff as $SingleTimeOff) {
                $FetchStaffIds = unserialize($SingleTimeOff->staff_id);

                //check selected staff assigned this timeoff or not
                if(in_array($StaffId, $FetchStaffIds)) {
                    // none check
                    if($SingleTimeOff->repeat == 'N') {
                        return $TodaysAllDayEvent = 1;
                    }

                    // daily check
                    if($SingleTimeOff->repeat == 'D') {
                        return $TodaysAllDayEvent = 1;
                    }

                    // weekly check
                    if($SingleTimeOff->repeat == 'W') {
                        $EventStartDate = $SingleTimeOff->start_date;
                        $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                        if(($diff % 7) == 0) {
                            return $TodaysAllDayEvent = 1;
                        }
                    }

                    //bi-weekly check
                    if($SingleTimeOff->repeat == 'BW') {
                        $EventStartDate = $SingleTimeOff->start_date;
                        $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                        if(($diff % 14) == 0) {
                            return $TodaysAllDayEvent = 1;
                        }
                    }

                    //monthly check
                    if($SingleTimeOff->repeat == 'M') {
                        // calculate all monthly dates
                        $EventStartDate = $SingleTimeOff->start_date;
                        $EventEndDate = $SingleTimeOff->end_date;
                        $i = 0;
                        do {
                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($EventStartDate)));
                                $AllEventMonthlyDates[] = $NextDate;
                                $i = $i+1;
                        } while(strtotime($EventEndDate) != strtotime($NextDate));

                        //check appointment's date in $AllEventMonthlyDates
                        if(in_array($AppointmentDate, $AllEventMonthlyDates)) {
                            return $TodaysAllDayEvent = 1;
                        }
                    }
                } else {
                    return $TodaysAllDayEvent = 0;
                }
            }//end of event fetching foreach
        }

        //This function calculate available appointment time slot on selected appointment day and service with selected staff and
        //return disable time slot array
        function TimeSlotCalculation($AppointmentDate, $StaffId, $ServiceId, $Biz_start_time, $Biz_end_time) {
            global $wpdb;
            //calculate all time slots in 5 min
            $SlotTimes = 5; //$ServiceDuration;
            $AllSlotTimesList = array();
            $AppPreviousTimes = array();
            $AppNextTimes = array();
            $AppBetweenTimes = array();
            $RecurAppStartTime = array();
            $EventPreviousTimes = array();
            $EventBetweenTimes = array();
            $EventNextTimes = array();
            $DisableSlotsTimes = array();
            $AppointmentDay = date("l", strtotime($AppointmentDate));

            //get service details
            $ServiceTableName = $wpdb->prefix."ap_services";
            $FindService_sql = "SELECT `name`, `duration` FROM `$ServiceTableName` WHERE `id` = '$ServiceId'";
            $ServiceData = $wpdb->get_row($FindService_sql, OBJECT);
            $ServiceDuration = $ServiceData->duration;

            //get staff details
            $StaffTableName = $wpdb->prefix."ap_staff";
            $StaffData = $wpdb->get_row("SELECT `name` , `staff_hours` FROM `$StaffTableName` WHERE `id` = '$StaffId'", OBJECT);

            //Calculate all time slots according to today's biz hours
            $start = strtotime($Biz_start_time);
            $end = strtotime($Biz_end_time);

            //for( $i = $start; $i < $end; $i += (60*$ServiceDuration))
            for( $i = $start; $i < $end; $i += (60*5)) {
                $AllSlotTimesList[] = date('h:i A', $i);
            }

            //Fetch All today's appointments and calculate disable slots
            $AppointmentTableName = $wpdb->prefix."ap_appointments";
            $AllAppointments_sql = "SELECT * FROM `$AppointmentTableName` WHERE `staff_id` = '$StaffId' AND `date` = '$AppointmentDate' AND `recurring` LIKE 'no' AND `status` != 'cancelled' ";
            $AllAppointmentsData = $wpdb->get_results($AllAppointments_sql);
            if($AllAppointmentsData) {
                foreach($AllAppointmentsData as $Appointment) {
                    $AppStartTimes[] = date('h:i A', strtotime( $Appointment->start_time ) );
                    $AppEndTimes[] = date('h:i A', strtotime( $Appointment->end_time ) );

                    //now calculate 5min slots between appointment's start_time & end_time
                    $start_et = strtotime($Appointment->start_time);
                    $end_et = strtotime($Appointment->end_time);
                    //make 15-10=5min slot
                    for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                        $AppBetweenTimes[] = date('h:i A', $i);
                    }
                }
                    //calculating  Next & Previous time of booked appointments
                    foreach($AllSlotTimesList as $single) {
                        if(in_array($single, $AppStartTimes)) {
                            //get previous time
                            $time1 = $single;
                            $event_length1 = $ServiceDuration-$SlotTimes;       // 60min Service duration time - 15 slot time
                            $timestamp1 = strtotime("$time1");
                            $endtime1 = strtotime("-$event_length1 minutes", $timestamp1);
                            $next_time1 = date('h:i A', $endtime1);
                            //calculate previous time
                            $start1 = strtotime($next_time1);
                            $end1 = strtotime($single);
                            //making 5min diff slot
                            for( $i = $start1; $i <= $end1; $i += (60*(5))) {
                                $AppPreviousTimes[] = date('h:i A', $i);
                            }
                        }
                    }//end of foreach
                    //end calculating Next & Previous time of booked appointments
            } // end if $AllAppointmentsData


            //Fetch All today's daily and purticular days recurring appointments and calculate disable slots
            $AppointmentTableName = $wpdb->prefix."ap_appointments";
            $AllRecurringsAppointments_sql = "SELECT * FROM `$AppointmentTableName` WHERE `recurring` LIKE 'yes' AND date('$AppointmentDate') between `recurring_st_date` AND `recurring_ed_date` AND `staff_id` = '$StaffId' AND `recurring_type` != 'weekly' AND `recurring_type` != 'monthly' AND `status` != 'cancelled' ";
            $AllRecurringsAppointmentsData = $wpdb->get_results($AllRecurringsAppointments_sql, OBJECT);
            if($AllRecurringsAppointmentsData) {
                foreach($AllRecurringsAppointmentsData as $booked) {
                    $RecurAppStartTime[] = date('h:i A', strtotime( $booked->start_time ) );
                    //now calculate 5min slots between appointment's start_time & end_time
                    $start_et = strtotime($booked->start_time);
                    $end_et = strtotime($booked->end_time);
                    //make 15-10=5min slot
                    for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                        $AppBetweenTimes[] = date('h:i A', $i);
                    }
                }

                //calculating Next & Previous time of booked in daily recurring appointments
                foreach($AllSlotTimesList as $single) {
                    if(in_array($single, $RecurAppStartTime)) {
                        //get previous time
                        $time1 = $single;
                        $event_length1 = $ServiceDuration-$SlotTimes;       // 60min Service duration time - 15 slot time
                        $timestamp1 = strtotime("$time1");
                        $endtime1 = strtotime("-$event_length1 minutes", $timestamp1);
                        $next_time1 = date('h:i A', $endtime1);

                        //calculate StartTime-previousTime between slots (start-end time)
                        $start1 = strtotime($next_time1);
                        $end1 = strtotime($single);
                        //making 5min diff slot
                        for( $i = $start1; $i <= $end1; $i += (60*(5))) {
                            $AppPreviousTimes[] = date('h:i A', $i);
                        }

                        /*//calculate EndTime-nexttime between slots (start-end time)
                        $start = strtotime($single);
                        $end = strtotime($next_time);
                        for( $i = $start; $i <= $end; $i += (60*(5))) //making 5min difference slot
                        {
                            $AppNextTimes[] = date('h:i A', $i);
                        }*/
                    }
                }// end of foreach calculating Next & Previous
            } //end of recurring appointment if


            //Fetch All today's Weekly recurring appointments and calculate disable slots
            $AppointmentTableName = $wpdb->prefix."ap_appointments";
            $AllRecurringsAppointments_sql = "SELECT * FROM `$AppointmentTableName` WHERE `recurring` LIKE 'yes' AND `staff_id` = '$StaffId' AND `recurring_type` = 'weekly' AND `status` != 'cancelled' AND date('$AppointmentDate') between `recurring_st_date` AND `recurring_ed_date` ";
            $AllRecurringsAppointmentsData = $wpdb->get_results($AllRecurringsAppointments_sql, OBJECT);
            // block booked time slots
            if($AllRecurringsAppointmentsData) {
                foreach($AllRecurringsAppointmentsData as $RAppointment) {
                    //filter same day weekly recurring appointment as appointment date day
                    if($AppointmentDay == date("l", strtotime($RAppointment->recurring_st_date))) {
                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                        $Current_Re_Start_Date = $RAppointment->recurring_st_date;
                        $Current_Re_End_Date = $RAppointment->recurring_ed_date;

                        $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                        $Current_Re_End_Date = strtotime($Current_Re_End_Date);
                        //make weekly dates
                        for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 7)) {
                            $AllWeeklyDates[] = date('Y-m-d', $i);
                        }

                        if($AllWeeklyDates) {
                            if(in_array($AppointmentDate, $AllWeeklyDates)) {
                                //calculate previous time (weekly recurring start time to back serviceduration-5)
                                $minustime = $ServiceDuration - 5;
                                $start = date('h:i A', strtotime("-$minustime minutes", strtotime($RAppointment->start_time)));
                                $start = strtotime($start);
                                $end =  $RAppointment->start_time;
                                $end = strtotime($end);
                                //making 5min difference slot
                                for( $i = $start; $i <= $end; $i += (60*(5))) {
                                    $AppPreviousTimes[] = date('h:i A', $i);
                                }

                                //now calculate 5min slots between appointment's start_time & end_time
                                $start_et = strtotime($RAppointment->start_time);
                                $end_et = strtotime($RAppointment->end_time);
                                //make 15-10=5min slot
                                for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                    $AppBetweenTimes[] = date('h:i A', $i);
                                }
                            }//end of in array if
                        }
                    }
                }// end of foreach RAppointments

            } //end of recurring appointment if weekly


            //Fetch All today's Monthly recurring appointments and calculate disable slots
            $AppointmentTableName = $wpdb->prefix."ap_appointments";
            $AllRecurringsAppointments_sql = "SELECT * FROM `$AppointmentTableName` WHERE `recurring` LIKE 'yes' AND `staff_id` = '$StaffId' AND `recurring_type` = 'monthly' AND `status` != 'cancelled' ";
            $AllRecurringsAppointmentsData = $wpdb->get_results($AllRecurringsAppointments_sql, OBJECT);
            // block booked time slots
            if($AllRecurringsAppointmentsData) {
                foreach($AllRecurringsAppointmentsData as $RAppointment) {
                    //calculate all Monthly dates between recurring_start_date - recurring_end_date
                    $Current_Re_Start_Date = $RAppointment->recurring_st_date;
                    $Current_Re_End_Date = $RAppointment->recurring_ed_date;
                    $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                    $Current_Re_End_Date = strtotime($Current_Re_End_Date);

                    //make weekly dates
                    for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 31)) {
                        $AllMonthlyDates[] = date('Y-m-d', $i);
                    }
                    if(in_array($AppointmentDate, $AllMonthlyDates)) {
                        //calculate previous time (Monthly recurring start time to back serviceduration-5)
                        $minustime = $ServiceDuration - 5;
                        $start = date('h:i A', strtotime("-$minustime minutes", strtotime($RAppointment->start_time)));
                        $start = strtotime($start);
                        $end =  $RAppointment->start_time;
                        $end = strtotime($end);
                        //making 5min difference slot
                        for( $i = $start; $i <= $end; $i += (60*(5))) {
                            $AppPreviousTimes[] = date('h:i A', $i);
                        }

                        //now calculate 5min slots between appointment's start_time & end_time
                        $start_et = strtotime($RAppointment->start_time);
                        $end_et = strtotime($RAppointment->end_time);
                        //make 15-10=5min slot
                        for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                            $AppBetweenTimes[] = date('h:i A', $i);
                        }
                    }//end of in array if
                }// end of foreach RAppointments
            } //end of recurring appointment if monthly


            //Fetch All today's time-off and calculate disable slots
            $EventTableName = $wpdb->prefix."ap_events";
            $AllEventts_sql = "SELECT `start_time`, `end_time`, `staff_id` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` != 'W' AND `repeat` != 'BW' AND `repeat` != 'M'";
            $AllEventsData = $wpdb->get_results($AllEventts_sql, OBJECT);
            if($AllEventsData) {
                foreach($AllEventsData as $Event) {
                    $StaffIds = unserialize($Event->staff_id);
                    if($StaffIds) {
                        if(in_array($StaffId, $StaffIds)) {
                            //calculate previous time (event start time to back serviceduration-5)
                            $minustime = $ServiceDuration - 5;
                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                            $start = strtotime($start);
                            $end =  $Event->start_time;
                            $end = strtotime($end);
                            //making 5min difference slot
                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                $EventPreviousTimes[] = date('h:i A', $i);
                            }

                            //calculating between time (start - end)
                            $start_et = strtotime($Event->start_time);
                            $end_et = strtotime($Event->end_time);
                            //making 5min slot
                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                $EventBetweenTimes[] = date('h:i A', $i);
                            }
                        }
                    }
                }// end of foreach
            }

            //Fetch All 'WEEKLY' time-off and calculate disable slots
            $EventTableName = $wpdb->prefix."ap_events";
            $AllEventts_sql = "SELECT `start_time`, `end_time`, `start_date`, `end_date`, `staff_id` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = 'W'";
            $AllEventsData = $wpdb->get_results($AllEventts_sql, OBJECT);
            if($AllEventsData) {
                foreach($AllEventsData as $Event) {
                    $EventStartDate =  $Event->start_date;
                    $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                    if(($diff % 7) == 0) {
                        //blocking only current staff time slots
                        $StaffIds = unserialize($Event->staff_id);
                        if(in_array($StaffId, $StaffIds)) {
                            //calculate all weekly dates between recurring_start_date - recurring_end_date
                            $Current_Re_Start_Date = $Event->start_date;
                            $Current_Re_End_Date = $Event->end_date;
                            $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                            $Current_Re_End_Date = strtotime($Current_Re_End_Date);

                            //make weekly dates
                            for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 7)) {
                                $AllEventWeelylyDates[] = date('Y-m-d', $i);
                            }

                            if(in_array($AppointmentDate, $AllEventWeelylyDates)) {
                                //calculate previous time (event start time to back serviceduration-5)
                                $minustime = $ServiceDuration - 5;
                                $Event->start_time;
                                $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                $start = strtotime($start);
                                $end =  $Event->start_time;
                                $end = strtotime($end);
                                //making 5min difference slot
                                for( $i = $start; $i <= $end; $i += (60*(5))) {
                                    $EventPreviousTimes[] = date('h:i A', $i);
                                }

                                //calculating between time (start - end)
                                $start_et = strtotime($Event->start_time);
                                $end_et = strtotime($Event->end_time);
                                //making 5min slot
                                for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                    $EventBetweenTimes[] = date('h:i A', $i);
                                }
                            }
                        }
                    }// end of divisible if
                }
            }


            //Fetch All 'BI-WEEKLY' time-off and calculate disable slots
            $EventTableName = $wpdb->prefix."ap_events";
            $AllEventts_sql = "SELECT `start_time`, `end_time`, `start_date`, `end_date`, `staff_id` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = 'BW'";
            $AllEventsData = $wpdb->get_results($AllEventts_sql, OBJECT);
            if($AllEventsData) {
                foreach($AllEventsData as $Event) {
                    $EventStartDate =  $Event->start_date;
                    $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                    if(($diff % 14) == 0) {
                        //blocking only current staff time slots
                        $StaffIds = unserialize($Event->staff_id);
                        if(in_array($StaffId, $StaffIds)) {
                            //calculate all weekly dates between recurring_start_date - recurring_end_date
                            $Current_Re_Start_Date = $Event->start_date;
                            $Current_Re_End_Date = $Event->end_date;
                            $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                            $Current_Re_End_Date = strtotime($Current_Re_End_Date);

                            //make bi-weekly date
                            for($i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 14)) {
                                $AllEventWeelylyDates[] = date('Y-m-d', $i);
                            }

                            if(in_array($AppointmentDate, $AllEventWeelylyDates)) {
                                //calculate previous time (event start time to back serviceduration-5)
                                $minustime = $ServiceDuration - 5;
                                $Event->start_time;
                                $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                $start = strtotime($start);
                                $end =  $Event->start_time;
                                $end = strtotime($end);
                                //making 5min difference slot
                                for( $i = $start; $i <= $end; $i += (60*(5))) {
                                    $EventPreviousTimes[] = date('h:i A', $i);
                                }

                                //calculating between time (start - end)
                                $start_et = strtotime($Event->start_time);
                                $end_et = strtotime($Event->end_time);
                                //making 5min slot
                                for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                    $EventBetweenTimes[] = date('h:i A', $i);
                                }
                            }
                        }
                    }// end of divisible if

                }
            }

            //Fetch All 'MONTHLY' time-off and calculate disable slots
            $EventTableName = $wpdb->prefix."ap_events";
            $AllEventts_sql = "SELECT `start_time`, `end_time`, `start_date`, `end_date`, `staff_id` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = 'M'";
            $AllEventsData = $wpdb->get_results($AllEventts_sql, OBJECT);
            if($AllEventsData) {
                foreach($AllEventsData as $Event)  {
                    //blocking only current staff time slots
                    $StaffIds = unserialize($Event->staff_id);
                    if(in_array($StaffId, $StaffIds)) {
                        //calculate all monthly dates between recurring_start_date - recurring_end_date
                        $Current_Re_Start_Date = $Event->start_date;
                        $Current_Re_End_Date = $Event->end_date;

                        $i = 0;
                        do {
                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($Current_Re_Start_Date)));
                                $AllEventMonthlyDates[] = $NextDate;
                                $i = $i+1;
                        } while(strtotime($Current_Re_End_Date) != strtotime($NextDate));

                        if(in_array($AppointmentDate, $AllEventMonthlyDates)) {
                            //calculate previous time (event start time to back serviceduration-5)
                            $minustime = $ServiceDuration - 5;
                            $Event->start_time;
                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                            $start = strtotime($start);
                            $end =  $Event->start_time;
                            $end = strtotime($end);
                            //making 5min diffrance slot
                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                $EventPreviousTimes[] = date('h:i A', $i);
                            }

                            //calculating between time (start - end)
                            $start_et = strtotime($Event->start_time);
                            $end_et = strtotime($Event->end_time);
                            //making 5min slot
                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                $EventBetweenTimes[] = date('h:i A', $i);
                            }
                        }
                    }
                }
            }

            // calculate previous time form business end time like(if 5:00 PM, 5:00 PM - ($ServiceDuration-5 minutes) )
            $minustime = $ServiceDuration - 5;
            $start = $Biz_end_time;
            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($start)));
            $start = strtotime($start);
            $end =  $Biz_end_time;
            $end = strtotime($end);
            //making 5min difference slot
            for( $i = $start; $i <= $end; $i += (60*(5)))  {
                $DisableSlotsTimes[] = date('h:i A', $i);
            }
            $DisableSlotsTimes = array_merge($DisableSlotsTimes, $AppPreviousTimes, $AppBetweenTimes, $AppNextTimes, $EventPreviousTimes, $EventBetweenTimes, $EventNextTimes);
            unset($AppBetweenTimes);
            unset($AppPreviousTimes);
            unset($AppNextTimes);
            unset($EventPreviousTimes);
            unset($EventBetweenTimes);
            unset($EventNextTimes);
            unset($AllSlotTimesList);
            return $DisableSlotsTimes;
        }
    }
}