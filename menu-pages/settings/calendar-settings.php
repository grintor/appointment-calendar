<?php // Load update calendar settings form
if(isset($_GET['managecalendarsettings']) == 'yes') {
    require_once('manage-calendar-settings.php');
} else {
    //Saving Calendar Settings
    if(isset($_POST['savecalendarsettings'])) {
        //update email settings option values
        $CalendarSettingsArray = array(
            'small_calendar_type' => $_POST['small_calendar_type'],
            'calendar_slot_time' => $_POST['calendar_slot_time'],
            'day_start_time' => $_POST['day_start_time'],
            'day_end_time' => $_POST['day_end_time'],
            'calendar_view' => $_POST['calendar_view'],
            'calendar_start_day' => $_POST['calendar_start_day'],
            'booking_button_text' => $_POST['booking_button_text'],
            'booking_user_timeslot' => $_POST['booking_user_timeslot'],
            'show_service_cost' => $_POST['show_service_cost'],
            'show_service_duration' => $_POST['show_service_duration'],
            'apcal_user_registration' => $_POST['user_registration'],
            'apcal_new_appointment_status' => $_POST['new_appointment_status'],
            'apcal_booking_instructions' => $_POST['apcal_booking_instructions']
         );
        update_option('apcal_calendar_settings', serialize($CalendarSettingsArray));

        //time string
        update_option('apcal_time_format', $_POST['apcal_time_format']);
        //date string
        update_option('apcal_date_format', $_POST['apcal_date_format']);

        echo "<script>alert('" . __('Calendar settings sucessfully saved.' ,'appointzilla') . "');</script>";
        echo "<script>location.href='?page=app-calendar-settings&show=calendarsettings'</script>";
    }

    // display current calendar settings
    $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings')); ?>
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Calendar Settings' ,'appointzilla'); ?></h3>
    </div>

    <table width="100%" class="table">
        <tr>
            <th scope="row"><?php _e('Small Calendar Type' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                    <?php
                    if($AllCalendarSettings['small_calendar_type']) {
                        echo ucfirst($AllCalendarSettings['small_calendar_type']. " Calendar");
                    }
                    else _e('Not Available.' ,'appointzilla');
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Date Format' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
              <em>
                  <?php
                    $DateFormat = get_option('apcal_date_format');
                    if($DateFormat != '') {
                        if($DateFormat == 'd-m-Y') echo  _e('DD-MM-YYYY', 'appointzilla');
                        if($DateFormat == 'm-d-Y') echo  _e('MM-DD-YYYY', 'appointzilla');
                        if($DateFormat == 'Y-m-d') echo  _e('YYYY-MM-DD', 'appointzilla');
                    }
                    else _e('Not Available.' ,'appointzilla');
                  ?>
              </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Time Format' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php
                    $TimeFormat = get_option('apcal_time_format');
                    if($TimeFormat != '') {
                        if($TimeFormat == 'h:i') echo _e('12 Hour Time', 'appointzilla');
                        if($TimeFormat == 'H:i') echo _e('24 Hour Time', 'appointzilla');
                    }
                    else _e('Not Available.' ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th width="20%" scope="row"><?php _e('Calendar Slot Time' ,'appointzilla'); ?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="75%">
                <em>
                <?php
                    if($AllCalendarSettings['calendar_slot_time'])
                    {
                        echo $AllCalendarSettings['calendar_slot_time']." ";
                        echo _e('Minute' ,'appointzilla');
                    }
                    else _e('Not Available.' ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Day Start Time' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php
                    if($AllCalendarSettings['day_start_time'])
                    {
                        echo $AllCalendarSettings['day_start_time'];
                    }
                    else echo _e('Not Available.' ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Day End Time' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php
                    if($AllCalendarSettings['day_end_time'])
                    {
                        echo $AllCalendarSettings['day_end_time'];
                    }
                    else echo _e('Not Available.' ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Calendar View' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php $calendar_view =  get_option('calendar_view' ,'appointzilla');
                    if($AllCalendarSettings['calendar_view'])
                    {
                        if($AllCalendarSettings['calendar_view'] == 'agendaDay') echo _e("Day" ,'appointzilla');
                        if($AllCalendarSettings['calendar_view'] == 'agendaWeek') echo _e("Week" ,'appointzilla');
                        if($AllCalendarSettings['calendar_view'] == 'month') echo _e("Month" ,'appointzilla');
                    }
                    else echo _e('Not Available.' ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Calendar Start Day' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php $calendar_start_day =  $AllCalendarSettings['calendar_start_day'];
                    if($calendar_start_day >= 0 )
                    {
                        if($calendar_start_day == 1)
                            echo _e("Monday" ,'appointzilla');
                        if($calendar_start_day == 2)
                            echo _e("Tuesday" ,'appointzilla');
                        if($calendar_start_day == 3)
                            echo _e("Wednesday" ,'appointzilla');
                        if($calendar_start_day == 4)
                            echo _e("Thursday" ,'appointzilla');
                        if($calendar_start_day == 5)
                            echo _e("Friday" ,'appointzilla');
                        if($calendar_start_day == 6)
                            echo _e("Saturday" ,'appointzilla');
                        if($calendar_start_day == 0)
                            echo _e("Sunday" ,'appointzilla');
                    }
                    else echo _e('Not Available.' ,'appointzilla');
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("Booking Button Text", "appointzilla"); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                    <?php if($AllCalendarSettings['booking_button_text']) {
                            echo $AllCalendarSettings['booking_button_text'];
                        } else {
                            echo _e('Not Available.' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("Booking Time Slot", "appointzilla"); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if($AllCalendarSettings['booking_user_timeslot'])
                    { echo $AllCalendarSettings['booking_user_timeslot'] ." " ."Minutes"; }
                    else
                    { echo _e('Not Available.' ,'appointzilla');  }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("Display Service Cost", 'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if(isset($AllCalendarSettings['show_service_cost']) == 'yes') {
                        if($AllCalendarSettings['show_service_cost'] == 'yes') echo _e('Yes' ,'appointzilla');
                        if($AllCalendarSettings['show_service_cost'] == 'no') echo _e('No' ,'appointzilla');
                    } else {
                        echo _e('Not Available' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("Display Service Duration", 'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if(isset($AllCalendarSettings['show_service_duration'])) {
                        if($AllCalendarSettings['show_service_duration'] == 'yes') echo _e('Yes' ,'appointzilla');
                        if($AllCalendarSettings['show_service_duration'] == 'no') echo _e('No' ,'appointzilla');
                    } else {
                        echo _e('Not Available' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("User/Client Registration", 'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if(isset($AllCalendarSettings['apcal_user_registration'])) {
                        if($AllCalendarSettings['apcal_user_registration'] == 'yes') echo _e('Yes' ,'appointzilla');
                        if($AllCalendarSettings['apcal_user_registration'] == 'no') echo _e('No' ,'appointzilla');
                    } else {
                        echo _e('Not Available' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e("New Appointment Status", 'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if(isset($AllCalendarSettings['apcal_new_appointment_status'])) {
                        if($AllCalendarSettings['apcal_new_appointment_status'] == 'pending') echo _e('Pending' ,'appointzilla');
                        if($AllCalendarSettings['apcal_new_appointment_status'] == 'approved') echo _e('Approved' ,'appointzilla');
                    } else {
                        echo _e('Not Available' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
        <th scope="row"><?php _e("Booking Instructions", 'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td><em>
                    <?php if(isset($AllCalendarSettings['apcal_booking_instructions'])) $BookingInstructions = $AllCalendarSettings['apcal_booking_instructions'];

                    if($BookingInstructions != NULL) {
                        echo $AllCalendarSettings['apcal_booking_instructions'];
                    } else {
                        echo _e('Not Available' ,'appointzilla');
                    } ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td>&nbsp;</td>
            <td><a href="?page=app-calendar-settings&show=calendarsettings&managecalendarsettings=yes" class="btn btn-primary"><?php _e('Manage Settings' ,'appointzilla'); ?></a></td>
        </tr>
    </table><?php
} //ens of display settings ?>