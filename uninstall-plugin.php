<?php
// Uninstall Appointment Calendar
if(isset($_POST['uninstallapcal'])) {
    global $wpdb;
    //1. drop ap_appointments table
    $table_appointments = $wpdb->prefix . "ap_appointments";
    $appointments = "DROP TABLE `$table_appointments`";
    $wpdb->query($appointments);

    //2. drop ap_business table
    $table_business = $wpdb->prefix . "ap_business";
    $business = "DROP TABLE `$table_business`";
    $wpdb->query($business);

    //3. drop ap_business_hours table
    $ap_business_hours_table = $wpdb->prefix . "ap_business_hours";
    $business_hours_sql = "DROP TABLE `$ap_business_hours_table`";
    $wpdb->query($business_hours_sql);

    //4. drop ap_calendar_settings table
    $table_calendar_settings = $wpdb->prefix . "ap_calendar_settings";
    $calendar_settings = "DROP TABLE `$table_calendar_settings`";
    $wpdb->query($calendar_settings);

    //5. drop ap_clients table
    $ap_clients_table = $wpdb->prefix . "ap_clients";
    $ap_clients_sql = "DROP TABLE `$ap_clients_table`";
    $wpdb->query($ap_clients_sql);

    //6. drop ap_country table
    $ap_country_table = $wpdb->prefix . "ap_country";
    $ap_country_sql = "DROP TABLE `$ap_country_table`";
    $wpdb->query($ap_country_sql);

    //7. drop ap_currency table
    $ap_currency_table = $wpdb->prefix . "ap_currency";
    $ap_currency_sql = "DROP TABLE `$ap_currency_table`";
    $wpdb->query($ap_currency_sql);

    //8. drop ap_events table
    $ap_events_table = $wpdb->prefix . "ap_events";
    $ap_events_sql = "DROP TABLE `$ap_events_table`";
    $wpdb->query($ap_events_sql);

    //9. drop ap_languages table
    $ap_languages_table = $wpdb->prefix . "ap_languages";
    $ap_languages_sql = "DROP TABLE `$ap_languages_table`";
    $wpdb->query($ap_languages_sql);

    //10. drop ap_services table
    $table_services = $wpdb->prefix . "ap_services";
    $services = "DROP TABLE `$table_services`";
    $wpdb->query($services);

    //11. drop ap_service_category table
    $table_service_category = $wpdb->prefix . "ap_service_category";
    $service_category = "DROP TABLE `$table_service_category`";
    $wpdb->query($service_category);

    //12. drop ap_staff table
    $ap_staff_table = $wpdb->prefix . "ap_staff";
    $ap_staff_sql = "DROP TABLE `$ap_staff_table`";
    $wpdb->query($ap_staff_sql);

    //13. drop ap_staff_groups table
    $ap_staff_groups_table = $wpdb->prefix . "ap_staff_groups";
    $ap_staff_groups_sql = "DROP TABLE `$ap_staff_groups_table`";
    $wpdb->query($ap_staff_groups_sql);

    //14. drop ap_timezones table
    $ap_timezones_table = $wpdb->prefix . "ap_timezones";
    $ap_timezones_sql = "DROP TABLE `$ap_timezones_table`";
    $wpdb->query($ap_timezones_sql);

    //15. drop ap_payment_transaction table
    $ap_payment_transaction_table = $wpdb->prefix . "ap_payment_transaction";
    $ap_payment_transaction_sql = "DROP TABLE `$ap_payment_transaction_table`";
    $wpdb->query($ap_payment_transaction_sql);

    //16. drop Appointment Sync Table
    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
    $AppointmentSyncTableName_sql = "DROP TABLE `$AppointmentSyncTableName`";
    $wpdb->query($AppointmentSyncTableName_sql);

    //17. drop reminder table
    $ReminderTable = $wpdb->prefix . "ap_reminders";
    $ReminderTable_sql = "DROP TABLE `$ReminderTable`";
    $wpdb->query($ReminderTable_sql);

    //18. drop coupon code tables
    $CouponsCodesTable = $wpdb->prefix . "apcal_pre_coupons_codes";
    $CouponsCodesTable_sql = "DROP TABLE `$CouponsCodesTable`";
    $wpdb->query($CouponsCodesTable_sql);

    //19. drop staff appointment sync tables
    $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
    $StaffAppointmentSyncTable_sql = "DROP TABLE `$StaffAppointmentSyncTable`";
    $wpdb->query($StaffAppointmentSyncTable_sql);

    //delete calendar options & settings
    delete_option('apcal_calendar_settings');
    //time string
    delete_option('apcal_time_format');
    //date string
    delete_option('apcal_date_format');

    //delete email settings
    delete_option('emailstatus');
    delete_option('emailtype');
    delete_option('emaildetails');
    delete_option('staff_notification_status');

    //delete client messages
    delete_option('booking_client_subject');
    delete_option('booking_client_body');
    delete_option('approve_client_subject');
    delete_option('approve_client_body');
    delete_option('cancel_client_subject');
    delete_option('cancel_client_body');

    //delete admin messages
    delete_option('booking_admin_subject');
    delete_option('booking_admin_body');
    delete_option('cancel_admin_subject');
    delete_option('cancel_admin_body');

    //delete staff messages
    delete_option('booking_staff_subject');
    delete_option('booking_staff_body');
    delete_option('approve_staff_subject');
    delete_option('approve_staff_body');
    delete_option('cancel_staff_subject');
    delete_option('cancel_staff_body');

    //delete admin settings
    delete_option('cal_admin_country');
    delete_option('cal_admin_language');
    delete_option('cal_admin_timezone');
    delete_option('cal_admin_currency');

    //delete google calendar sync details
    delete_option('google_caelndar_settings_details');
    delete_option('google_calendar_twoway_sync');

    //delete payment settings
    delete_option('ap_payment_gateway_status');
    delete_option('ap_payment_gateway_name');
    delete_option('ap_payment_email');

    //delete remonder options
    delete_option('ap_reminder_details');

    //clear reminder hook
    wp_clear_scheduled_hook('apcal_reminder_event');

    //delete all staff google sync details
    for($i=1; $i<=25; $i++ ){
        delete_option('staff_google_calendar_sync_settings_'.$i);
        delete_option('google_calendar_token_details_'.$i);
    }


    // DEACTIVATE APPOINTMENT CALENDAR PLUGIN
    deactivate_plugins($PluginName = plugin_basename(__DIR__)."/appointment-calendar.php");
    ?>
    <div class="alert" style="width:95%; margin-top:10px;">
        <p><?php _e('Appointment Calendar Premium Plugin has been successfully removed. It can be re-activated from the', 'appointzilla'); ?> </strong><a href="plugins.php"><?php _e('Plugins Page', 'appointzilla'); ?></a></strong>.</p>
    </div>
    <?php
    return;
}

if(isset($_GET['page']) == 'uninstall-plugin') { ?>
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><i class="fa fa-trash-o"></i> <?php _e('Remove Plugin', 'appointzilla'); ?></h3>
    </div>

    <div class="alert alert-error" style="width:95%;">
        <form method="post">
            <h3><?php _e('Remove Appointment Calendar Premium Plugin', 'appointzilla'); ?></h3>
            <p><?php _e('This operation wiil delete all Appointment Calendar data & settings. If you continue, You will not be able to retrieve or restore your appointments entries.', 'appointzilla'); ?></p>
            <p><button id="uninstallapcal" type="submit" class="btn btn-danger" name="uninstallapcal" value="" onclick="return confirm('<?php _e('Warning! Appointment Calendar data & settings, including appointment entries will be deleted. This cannot be undone. OK to delete, CANCEL to stop', 'appointzilla'); ?>')" ><i class="icon-trash icon-white"></i> <?php _e('REMOVE PLUGIN', 'appointzilla'); ?></button></p>
        </form>
    </div><?php
} ?>