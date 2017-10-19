<?php
/**
 * Class: Staff Google Appointment Syncing
 * Ver: 1.0
 * package: Appointment Calendar Premium 3.5
 * Author: Faraz Frank
 * Description: This class used to sync approved
 * appointment on client google calendar. And
 * un-sync all cancelled or deleted appointment
 * through admin.
 */
if(!class_exists("StaffGoogleAppointmentSync")) {
   class StaffGoogleAppointmentSync {
        public $client;
        public $cal;

        //Constructor
        function __construct($StaffClientId, $StaffClientSecretId, $StaffRedirectUri) {
            require_once('settings/google-api-php-client/src/apiClient.php');
            require_once('settings/google-api-php-client/src/contrib/apiCalendarService.php');
            $this->client = new apiClient();
            $this->client->setApplicationName("appointzilla");
            $this->client->setClientId($StaffClientId);
            $this->client->setClientSecret($StaffClientSecretId);
            $this->client->setRedirectUri($StaffRedirectUri);
            $this->cal = new apiCalendarService($this->client);
        }

        // [1]-Staff Approved Appointment
        // sync staff approved normal appointment
        function NormalStaffAppointmentSync($StaffId, $ClientName, $AppDate, $StartTime, $EndTime, $Note, $Tag) {
            //$TokenData = get_option('google_caelndar_token_details');
            $TokenData = get_option("google_calendar_token_details_".$StaffId);
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calList = $this->cal->calendarList->listCalendarList();
                    $Timezone = $calList['items'][0]['timeZone'];
                    $event = new Event();
                    $event->setSummary($Tag.$ClientName);
                    $event->setDescription($Note);
                    $start = new EventDateTime();
                    $start->setTimeZone($Timezone);
                    $start->setDateTime($AppDate.'T'.date('H:i:s', strtotime($StartTime)).'.000');
                    $event->setStart($start);
                    $end = new EventDateTime();
                    $end->setTimeZone($Timezone);
                    $end->setDateTime($AppDate.'T'.date('H:i:s', strtotime($EndTime)).'.000');
                    $event->setEnd($end);
                    $createdEvent = $this->cal->events->insert('primary', $event);
                    return $createdEvent;
                } catch(Exception $e) {
                    return false;
                }
            }
        }

        // sync staff approved recurring appointment [daily], [weekly], [monthly], [particular dates]
        function RecurringStaffSync($StaffId, $ClientName, $StartDate, $EndDate, $StartTime, $EndTime, $Type, $Note, $Tag) {
            if($Type == 'daily' || $Type == 'D' || $Type == 'PD') {
                $diff= ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24;
                $diff  = $diff + 1;
                $String = 'RRULE:FREQ=DAILY;COUNT='.$diff;
            }
            if($Type == 'weekly' || $Type == 'W') {
                $diff = ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24/7;
                $diff  = $diff + 1;
                $String = 'RRULE:FREQ=WEEKLY;COUNT='.$diff;
            }
            if($Type == 'BW') {
                $diff = ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24/14;
                $diff  = $diff + 1;
                $String = 'RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT='.$diff;
            }
            if($Type == 'monthly' || $Type == 'M') {
                $datetime1 = date_create($EndDate);
                $datetime2 = date_create($StartDate);
                $interval = date_diff($datetime1, $datetime2);
                $mdiff = $interval->format('%m');
                $ydiff = $interval->format('%Y');
                $diff  = $mdiff + 1 + ($ydiff * 12);
                $String = 'RRULE:FREQ=MONTHLY;COUNT='.$diff.';BYMONTHDAY='.date('d', strtotime($StartDate));
            }

            $TokenData = get_option("google_calendar_token_details_".$StaffId);
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calList = $this->cal->calendarList->listCalendarList();
                    $Timezone = $calList['items'][0]['timeZone'];
                    $event = new Event();
                    $event->setSummary($Tag.$ClientName);
                    $event->setDescription($Note);
                    $start = new EventDateTime();
                    $start->setTimeZone($Timezone);
                    $start->setDateTime($StartDate.'T'.date("H:i:s", strtotime($StartTime)).'.000');
                    $event->setStart($start);
                    $end = new EventDateTime();
                    $end->setTimeZone($Timezone);
                    $end->setDateTime($StartDate.'T'.date("H:i:s", strtotime($EndTime)).'.000');
                    $event->setEnd($end);
                    $event->setRecurrence(array($String));
                    $recurringEvent = $this->cal->events->insert('primary', $event);
                    return $recurringEvent;
                } catch(Exception $e) {
                    return false;
                }
            }
        }

        // update sync approved normal appointment/time-off
        function UpdateNormalStaffSync($StaffId, $SyncId, $SyncEmail, $ClientName, $AppDate, $StartTime, $EndTime, $Note, $Tag) {
            $TokenData = get_option("google_calendar_token_details_".$StaffId);
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calList = $this->cal->calendarList->listCalendarList();
                    $Timezone = $calList['items'][0]['timeZone'];
                    $event = new Event($this->cal->events->get($SyncEmail,$SyncId));
                    $event->setSummary($Tag.$ClientName);
                    $event->setDescription($Note);
                    $start = new EventDateTime();
                    $start->setTimeZone($Timezone);
                    $start->setDateTime($AppDate.'T'.date('H:i:s', strtotime($StartTime)).'.000');
                    $event->setStart($start);
                    $end = new EventDateTime();
                    $end->setTimeZone($Timezone);
                    $end->setDateTime($AppDate.'T'.date('H:i:s', strtotime($EndTime)).'.000');
                    $event->setEnd($end);

                    $updated = new Event($this->cal->events->update($SyncEmail, $event->getId(), $event));
                    return $updated;
                } catch(Exception $e) {
                    return false;
                }
            }
        }

        function UpdateRecurringStaffSync($StaffId, $SyncId, $SyncEmail, $ClientName, $StartDate, $EndDate, $StartTime, $EndTime, $Type, $Note, $Tag) {
            if($Type == 'daily' || $Type == 'D' || $Type == 'PD') {
                $diff= ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24;
                $diff  = $diff + 1;
                $String = 'RRULE:FREQ=DAILY;COUNT='.$diff;
            }
            if($Type == 'weekly' || $Type == 'W') {
                $diff = ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24/7;
                $diff  = $diff + 1;
                $String = 'RRULE:FREQ=WEEKLY;COUNT='.$diff;
            }
            if($Type == 'BW') {
                $diff = ( strtotime($EndDate) - strtotime($StartDate)  ) /60/60/24/14;
                $String = 'RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT='.$diff;
            }
            if($Type == 'monthly' || $Type == 'M') {
                $datetime1 = date_create($EndDate);
                $datetime2 = date_create($StartDate);
                $interval = date_diff($datetime1, $datetime2);
                $mdiff = $interval->format('%m');
                $ydiff = $interval->format('%Y');
                $diff  = $mdiff + 1 + ($ydiff * 12);
                $String = 'RRULE:FREQ=MONTHLY;COUNT='.$diff.';BYMONTHDAY='.date('d', strtotime($StartDate));
            }

            $TokenData = get_option("google_calendar_token_details_".$StaffId);
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calList = $this->cal->calendarList->listCalendarList();
                    $Timezone = $calList['items'][0]['timeZone'];
                    $event = new Event($this->cal->events->get($SyncEmail,$SyncId));
                    $event->setSummary($Tag.$ClientName);
                    $event->setDescription($Note);
                    $start = new EventDateTime();
                    $start->setTimeZone($Timezone);
                    $start->setDateTime($StartDate.'T'.date("H:i:s", strtotime($StartTime)).'.000');
                    $event->setStart($start);
                    $end = new EventDateTime();
                    $end->setTimeZone($Timezone);
                    $end->setDateTime($StartDate.'T'.date("H:i:s", strtotime($EndTime)).'.000');
                    $event->setEnd($end);
                    $event->setRecurrence(array($String));
                    $updated = new Event($this->cal->events->update($SyncEmail, $event->getId(), $event));
                    return $updated;
                } catch(Exception $e) {
                    return false;
                }
            }
        }

        // [3]-Cancel/Delete Staff Appointment
        function DeleteStaffSync($StaffId, $SyncId) {
            $TokenData = get_option("google_calendar_token_details_".$StaffId);
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calList = $this->cal->calendarList->listCalendarList();
                    $event = new Event();
                    $OAuth = $this->cal->events->delete('primary',$SyncId);
                    return $OAuth;
                } catch(Exception $e) {
                    return false;
                }
            }
        }

        // Fetch list of event/appointment from users Google calendar
        function GetStaffAppointmentList($StaffId, $ClientEmail) {
            //$TokenData = get_option('google_caelndar_token_details');
            $TokenData = get_option("google_calendar_token_details_".$StaffId);
			
			// In future we will add UI for set Google event starting and ending days settings to fatch Google events 
			$Date = date('Y-m-d');
			$GeventMinDate =date("Y-m-d", strtotime("-60 days", strtotime($Date)));
			$GeventMaxDate =date("Y-m-d", strtotime("+60 days", strtotime($Date)));
			$GeventTime = array('timeMin'=>$GeventMinDate.'T00:00:00-04:00','timeMax'=>$GeventMaxDate.'T00:00:00-04:00');
			
            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                $this->client->setAccessToken(json_encode($TokenData));
            }

            if ($this->client->getAccessToken()) {
                try {
                    $calendarList = $this->cal->calendarList->listCalendarList();
                    return $EventsList = $this->cal->events->listEvents( $ClientEmail,$GeventTime);
                } catch(Exception $e) {
                    return false;
                }
            }
        }
   }
}