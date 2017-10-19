<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Manage Calendar Settings' ,'appointzilla'); ?></h3>
    </div>

    <form method="post" action="?page=app-calendar-settings&show=calendarsettings">
        <table width="100%" class="table">
            <tr>
                <th align="right" scope="row"><?php _e('Small Calendar Type' ,'appointzilla'); ?></th>
                <td width="4%" align="center"><strong>:</strong></td>
                <td>
                    <?php
                    $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
                    $small_calendar_type = $AllCalendarSettings['small_calendar_type']; ?>
                    <select name="small_calendar_type" id="small_calendar_type">
                        <option value="php" <?php if($small_calendar_type == 'php') echo "selected"; ?>><?php echo  _e('PHP Calendar', 'appointzilla'); ?></option>
                        <option value="jquery" <?php if($small_calendar_type == 'jquery') echo "selected"; ?>><?php echo _e('Jquery Calendar', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Small Calendar Type (date picker on first popup window at client interface)", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Date Format' ,'appointzilla'); ?></th>
                <td width="4%" align="center"><strong>:</strong></td>
                <td>
                    <?php $apcal_date_format = get_option('apcal_date_format'); ?>
                    <select name="apcal_date_format" id="apcal_date_format">
                      <option value="d-m-Y" <?php if($apcal_date_format == 'd-m-Y') echo "selected"; ?>><?php echo  _e('DD-MM-YYYY', 'appointzilla'); ?></option>
                      <option value="m-d-Y" <?php if($apcal_date_format == 'm-d-Y') echo "selected"; ?>><?php echo _e('MM-DD-YYYY', 'appointzilla'); ?></option>
                      <option value="Y-m-d"<?php if($apcal_date_format == 'Y-m-d') echo "selected"; ?>><?php echo  _e('YYYY-MM-DD', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Date Format. Ex:<br>DD-MM-YYYY = 01-12-2013<br>MM-DD-YYYY = 12-01-2013<br>YYYY-MM-DD = 2013-12-01' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Time Format', 'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <?php $apcal_time_format = get_option('apcal_time_format'); ?>
                    <select name="apcal_time_format" id="apcal_time_format">
                      <option value="h:i" <?php if($apcal_time_format == 'h:i') echo "selected"; ?>><?php _e('12 Hour Time', 'appointzilla'); ?></option>
                      <option value="H:i" <?php if($apcal_time_format == 'H:i') echo "selected"; ?>><?php _e('24 Hour Time', 'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Time Format. Ex<br>12 Hour = 01:30 PM<br>24 Hour = 13:30' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th width="19%" align="right" scope="row"><?php _e('Calendar Slot Time' ,'appointzilla'); ?></th>
                <td width="4%" align="center"><strong>:</strong></td>
                <td width="77%">
                    <?php

                    $calendar_slot_time = $AllCalendarSettings['calendar_slot_time']; ?>
                      <select name="calendar_slot_time" id="calendar_slot_time">
                        <option value="5" <?php if($calendar_slot_time && $calendar_slot_time == '5') echo "selected"; ?>><?php _e('5 Minute' ,'appointzilla'); ?></option>
                        <option value="10" <?php if($calendar_slot_time && $calendar_slot_time == '10') echo "selected"; ?>><?php _e('10 Minute' ,'appointzilla'); ?></option>
                        <option value="15" <?php if($calendar_slot_time && $calendar_slot_time == '15') echo "selected"; ?>><?php _e('15 Minute' ,'appointzilla'); ?></option>
                        <option value="30" <?php if($calendar_slot_time && $calendar_slot_time == '30') echo "selected"; ?>><?php _e('30 Minute' ,'appointzilla'); ?></option>
                        <option value="60" <?php if($calendar_slot_time && $calendar_slot_time == '60') echo "selected"; ?>><?php _e('60 Minute' ,'appointzilla'); ?></option>
                      </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Calendar Time Slot' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Day Start Time' ,'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <?php $day_start_time = $AllCalendarSettings['day_start_time']; ?>
                    <select name="day_start_time" id="day_start_time">
                        <?php
                            $biz_start_time = strtotime("01:00 AM");
                            $biz_end_time = strtotime("11:00 PM");
                            //making 60min slots
                            for( $i = $biz_start_time; $i <= $biz_end_time; $i += (60*(60))) {
                                if( $day_start_time && $day_start_time == date('g:i A', $i) ) $selected = 'selected'; else $selected='';
                                echo "<option $selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                            }
                        ?>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Calendar Day Start Time' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Day End Time' ,'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <?php $day_end_time = $AllCalendarSettings['day_end_time']; ?>
                    <select name="day_end_time" id="day_end_time">
                        <?php
                            //making 60min slots
                            for( $i = $biz_start_time; $i <= $biz_end_time; $i += (60*(60))) {
                                if( $day_end_time && $day_end_time == date('g:i A', $i) ) $selected = 'selected'; else $selected='';
                                echo "<option $selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                            }
                        ?>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Calendar Day End Time' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Calendar View' ,'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <?php $calendar_view = $AllCalendarSettings['calendar_view']; ?>
                    <select id="calendar_view" name="calendar_view">
                        <option value="agendaDay" <?php if($calendar_view && $calendar_view == 'agendaDay') echo "selected"; ?>><?php _e('Day' ,'appointzilla'); ?></option>
                        <option value="agendaWeek" <?php if($calendar_view && $calendar_view == 'agendaWeek') echo "selected"; ?>><?php _e('Week' ,'appointzilla'); ?></option>
                        <option value="month" <?php if($calendar_view && $calendar_view == 'month') echo "selected"; ?>><?php _e('Month' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Calendar View' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e('Calendar First Day' ,'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <?php $calendar_start_day = $AllCalendarSettings['calendar_start_day']; ?>
                    <select name="calendar_start_day" id="calendar_start_day">
                      <option value="1" <?php if($calendar_start_day == 1) echo "selected"; ?>><?php _e('Monday' ,'appointzilla'); ?></option>
                      <option value="2" <?php if($calendar_start_day == 2) echo "selected"; ?>><?php _e('Tuesday' ,'appointzilla'); ?></option>
                      <option value="3" <?php if($calendar_start_day == 3) echo "selected"; ?>><?php _e('Wednesday' ,'appointzilla'); ?></option>
                      <option value="4" <?php if($calendar_start_day == 4) echo "selected"; ?>><?php _e('Thursday' ,'appointzilla'); ?></option>
                      <option value="5" <?php if($calendar_start_day == 5) echo "selected"; ?>><?php _e('Friday' ,'appointzilla'); ?></option>
                      <option value="6" <?php if($calendar_start_day == 6) echo "selected"; ?>><?php _e('Saturday' ,'appointzilla'); ?></option>
                      <option value="0" <?php if($calendar_start_day == 0) echo "selected"; ?>><?php _e('Sunday' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Calendar First Day' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("Booking Button Text", "appointzilla"); ?></th>
                <td align="center"><strong>:</strong></td>
                <td><?php $booking_button_text = $AllCalendarSettings['booking_button_text']; ?>
                <input name="booking_button_text" type="text" id="booking_button_text" value="<?php if($booking_button_text) echo $booking_button_text; else echo "Schedule New Appointment"; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Booking Button Text' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr><th align="right" scope="row"><?php _e("Booking Time Slot", 'appointzilla'); ?></th> <td align="center"><strong>:</strong></td>
                <td><?php $booking_user_timeslot = $AllCalendarSettings['booking_user_timeslot']; ?>
                   <select name="booking_user_timeslot" id="booking_user_timeslot">
                       <option <?php if($booking_user_timeslot == 5) echo "selected"; ?> value="5"><?php _e("5 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 10) echo "selected"; ?> value="10"><?php _e("10 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 15) echo "selected"; ?> value="15"><?php _e("15 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 20) echo "selected"; ?> value="20"><?php _e("20 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 25) echo "selected"; ?> value="25"><?php _e("25 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 30) echo "selected"; ?> value="30"><?php _e("30 Minutes - Half Hour", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 35) echo "selected"; ?> value="35"><?php _e("35 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 40) echo "selected"; ?> value="40"><?php _e("40 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 45) echo "selected"; ?> value="45"><?php _e("45 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 60) echo "selected"; ?> value="60"><?php _e("60 Minutes - One Hour", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 75) echo "selected"; ?> value="75"><?php _e("75 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 90) echo "selected"; ?> value="90"><?php _e("90 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 120) echo "selected"; ?> value="120"><?php _e("120 Minutes - Two Hours", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 150) echo "selected"; ?> value="150"><?php _e("150 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 180) echo "selected"; ?> value="180"><?php _e("180 Minutes - Three Hours", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 210) echo "selected"; ?> value="210"><?php _e("210 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 240) echo "selected"; ?> value="240"><?php _e("240 Minutes - Four Hours", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 270) echo "selected"; ?> value="270"><?php _e("270 Minutes", 'appointzilla'); ?></option>
                       <option <?php if($booking_user_timeslot == 300) echo "selected"; ?> value="300"><?php _e("300 Minutes - Five Hours", 'appointzilla'); ?></option>
                  </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Booking Time Slot' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                 </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("Display Service Cost", "appointzilla")?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                  <select name="show_service_cost" id="show_service_cost">
                      <option value="yes" <?php if($AllCalendarSettings['show_service_cost'] == 'yes') echo "selected"; ?>><?php echo _e('Yes' ,'appointzilla'); ?></option>
                      <option value="no" <?php if($AllCalendarSettings['show_service_cost'] == 'no') echo "selected"; ?>><?php echo _e('No' ,'appointzilla'); ?></option>
                  </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Show or hide service cost at client booking form.", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("Display Service Duration", "appointzilla")?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                  <select name="show_service_duration" id="show_service_duration">
                      <option value="yes" <?php if($AllCalendarSettings['show_service_duration'] == 'yes') echo "selected"; ?>><?php echo _e('Yes' ,'appointzilla'); ?></option>
                      <option value="no" <?php if($AllCalendarSettings['show_service_duration'] == 'no') echo "selected"; ?>><?php echo _e('No' ,'appointzilla'); ?></option>
                  </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Show or hide service duration at client booking form.", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("User/Client Registration", "appointzilla")?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <select name="user_registration" id="user_registration">
                        <option value="yes" <?php if($AllCalendarSettings['apcal_user_registration'] == 'yes') echo "selected"; ?>><?php echo _e('Yes' ,'appointzilla'); ?></option>
                        <option value="no" <?php if($AllCalendarSettings['apcal_user_registration'] == 'no') echo "selected"; ?>><?php echo _e('No' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("User/Client registration at time of booking appointment.", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("New Appointment Status", 'appointzilla'); ?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <select name="new_appointment_status" id="new_appointment_status">
                        <option value="pending" <?php if($AllCalendarSettings['apcal_new_appointment_status'] == 'pending') echo "selected"; ?>><?php echo _e('Pending' ,'appointzilla'); ?></option>
                        <option value="approved" <?php if($AllCalendarSettings['apcal_new_appointment_status'] == 'approved') echo "selected"; ?>><?php echo _e('Approved' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Set default status for new appointments.", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th align="right" scope="row"><?php _e("Booking Instructions", "appointzilla")?></th>
                <td align="center"><strong>:</strong></td>
                <td>
                    <b><?php _e("You can use only these HTML tags like:", "appointzilla"); ?></b><br>&lt;p&gt;&lt;/p&gt;, &lt;h1&gt;&lt;/h1&gt;, &lt;h2&gt&lt;/h2&gt;, &lt;h3&gt;&lt;/h3&gt;, &lt;h4&gt;&lt;/h4&gt;, <br>&lt;h5&gt;&lt;/h5&gt;, &lt;h6&gt;&lt;/h6&gt;, &lt;strong&gt;&lt;/strong&gt;, &lt;em&gt;&lt;/em&gt; &lt;br&gt;&lt;/br&gt;<br>
                    <textarea id="apcal_booking_instructions" name="apcal_booking_instructions" style="width: 500px; height: 150px;"><?php if($AllCalendarSettings['apcal_booking_instructions']) echo $AllCalendarSettings['apcal_booking_instructions']; ?></textarea>
                    &nbsp;<a href="#" rel="tooltip" title="<?php _e("Booking instruction will be appears on client interface before booking button.<br> You can use only these HTML tags like p, h1, h2, h3, h4, h5, h6, b, em to make more visualize instructions.", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>
            <tr>
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>
                    <?php if($calendar_slot_time && $day_start_time && $day_end_time && $calendar_view ) { ?>
                    <button name="savecalendarsettings" class="btn" type="submit" id="savecalendarsettings" data-loading-text="Saving Settings"><i class="icon-pencil"></i> <?php _e('Update Settings' ,'appointzilla'); ?></button>
                    <?php } else { ?>
                    <button name="savecalendarsettings" class="btn" type="submit" id="savecalendarsettings" data-loading-text="Saving Settings"><i class="icon-ok"></i> <?php _e('Save Settings' ,'appointzilla'); ?></button>
                    <?php } ?>
                    <a href="?page=app-calendar-settings&show=calendarsettings" class="btn"><i class="icon-remove"></i> <?php _e('Cancel' ,'appointzilla'); ?></a>
                </td>
            </tr>
        </table>
    </form>

    <style type="text/css">
    .error {
        color:#FF0000;
    }
    </style>
    <!--validation js lib-->
    <script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#savesettings').click(function(){
            jQuery(".error").hide();
            //slot time
            var calendar_slot_time = jQuery('#calendar_slot_time').val();
            if(calendar_slot_time == 0) {
                jQuery("#calendar_slot_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select Slot Time.' ,'appointzilla'); ?></strong></span>');
                return false;
            }

            var day_start_time = jQuery('#day_start_time').val();
            if(day_start_time == 0) {
                jQuery("#day_start_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select Start Time.' ,'appointzilla'); ?></strong></span>');
                return false;
            }

            var day_end_time = jQuery('#day_end_time').val();
            if(day_end_time == 0) {
                jQuery("#day_end_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select End Time.' ,'appointzilla') ;?> </strong></span>');
                return false;
            }

            var calendar_view = jQuery('#calendar_view').val();
            if(calendar_view == 0) {
                jQuery("#calendar_view").after('<span class="error">&nbsp;<br><strong><?php _e('Select Calendar View.' ,'appointzilla'); ?></strong></span>');
                return false;
            }

            var calendar_start_day = jQuery('#calendar_start_day').val();
            if(calendar_start_day == -1) {
                jQuery("#calendar_start_day").after('<span class="error">&nbsp;<br><strong><?php _e('Select Calendar View.' ,'appointzilla'); ?></strong></span>');
                return false;
            }

            var booking_button_text = jQuery('#booking_button_text').val();
            if(!booking_button_text) {
                jQuery("#booking_button_text").after('<span class="error">&nbsp;<br><strong><?php _e('Booking Button Text Required.' ,'appointzilla'); ?></strong></span>');
                return false;
            }
        });
    });
    </script>
</div>