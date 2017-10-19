<?php
/**
 * Plugin Name: Appointment Calendar Premium
 * Version: 3.6.5
 * Description: Appointment Calendar Premium is a simple yet powerful plugin for accepting online appointments on your WordPress blog site.
 * Author: Scientech It Solutions
 * Author URI: http://www.appointzilla.com
 * Plugin URI: http://www.appointzilla.com
 */

//ini_set('error_reporting', !E_NOTICE & !E_WARNING);

// Run 'Install' script on plugin activation ###
register_activation_hook( __FILE__, 'InstallScript' );
function InstallScript() {
    require_once('install-script.php');
}

// Translate all text & labels of plugin ###
add_action('plugins_loaded', 'LoadPluginLanguage');
 
function LoadPluginLanguage() {
 load_plugin_textdomain('appointzilla', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

// Admin dashboard Menu Pages For Booking Calendar Plugin
add_action('admin_menu','appointment_calendar_menu');

function appointment_calendar_menu() {

    //create new top-level menu 'appointment-calendar'
    $Menu = add_menu_page( 'Appointment Calendar', __('Appointment Calendar', 'appointzilla'), 'administrator', 'appointment-calendar', '', 'dashicons-calendar');

    // Calendar Page
    $SubMenu1 = add_submenu_page( 'appointment-calendar', __('Admin Calendar', 'appointzilla'), __('Admin Calendar', 'appointzilla'), 'administrator', 'appointment-calendar', 'display_calendar_page' );
    // Time stoat Page
    $SubMenu2 = add_submenu_page( '', 'Manage Time Slot', '', 'administrator', 'time_sloat', 'display_time_slot_page' );
    // Data Save Page
    $SubMenu3 = add_submenu_page( '', 'Data Save', '', 'administrator', 'data_save', 'display_data_save_page' );

    // Service Page
    $SubMenu4 = add_submenu_page( 'appointment-calendar', __('Services', 'appointzilla'), __('Services', 'appointzilla'), 'administrator', 'service', 'display_service_page' );
    // manage Service Page
    $SubMenu5 = add_submenu_page( '', 'Manage Service', '', 'administrator', 'manage-service', 'display_manage_service_page' );

    // Staff Page
    $SubMenu6 = add_submenu_page( 'appointment-calendar', 'Staffs', __('Staffs', 'appointzilla'), 'administrator', 'staff', 'display_staff_page' );
    // manage Staff Page
    $SubMenu7 = add_submenu_page( '', 'Manage Staff', '', 'administrator', 'manage-staff', 'display_manage_staff_page' );
    $SubMenu7_1 = add_submenu_page( '', 'Staff Google Calendar Sync', '', 'administrator', 'staff-google-calendar-sync', 'display_staff_google_calendar_sync_page' );

    //staff-calendar
    $SubMenu8 = add_submenu_page( 'appointment-calendar', __('Staff Calendar', 'appointzilla'), __('Staff Calendar', 'appointzilla'), 'contributor', 'staff-appointment-calendar', 'display_staff_appointment_calendar_page' );
    $SubMenu20 = add_submenu_page( 'appointment-calendar', __('Manage Appointments', 'appointzilla'), __('Manage Appointments', 'appointzilla'), 'contributor', 'manage-staff-appointments', 'display_staff_appointments_page' );

    // Time-Off Page
    $SubMenu9 = add_submenu_page( 'appointment-calendar', 'Time Off', __('Time Off', 'appointzilla'), 'administrator', 'timeoff', 'display_timeoff_page' );
    // Update Time-Off Page
    $SubMenu10 = add_submenu_page( '', 'Update TimeOff', '', 'administrator', 'update-timeoff', 'display_update_timeoff_page' );

    // Client Page
    $SubMenu11 = add_submenu_page( 'appointment-calendar', __('Clients', 'appointzilla'), __('Clients', 'appointzilla'), 'administrator', 'client', 'display_client_page' );
    $SubMenu12 = add_submenu_page( '', 'Client Manage', '','administrator', 'client-manage', 'display_manage_client_page' );

    // Manage Appointment Page
    $SubMenu13 = add_submenu_page( 'appointment-calendar', __('Admin Appointments', 'appointzilla'), __('Appointments', 'appointzilla'), 'administrator', 'manage-appointments', 'display_manage_appointment_page' );
    // Update Appointments Page
    $SubMenu14 = add_submenu_page( '', 'Update Appointment', '', 'administrator', 'update-appointment', 'display_update_appointment_page' );

    // Payment Transaction Page
    $SubMenu18 = add_submenu_page( 'appointment-calendar', __('Payment Transaction', 'appointzilla'), __('Payment Transaction', 'appointzilla'), 'administrator', 'manage-payment-transaction', 'display_payment_transaction_page' );

    //Export Appointments & Client List
    $SubMenu19 = add_submenu_page('appointment-calendar', __('Export Lists', 'appointzilla'), __('Export Lists', 'appointzilla'), 'administrator', 'export-lists', 'display_export_lists_page' );

    //Coupon Codes
    $SubMenu21 = add_submenu_page('appointment-calendar', __('Coupons Codes', 'appointzilla'), __('Coupons Codes', 'appointzilla'), 'administrator', 'apcal-coupons-codes', 'display_coupons_codes_page' );

    // Settings Page
    $SubMenu15 = add_submenu_page( 'appointment-calendar', __('Settings', 'appointzilla'), __('Settings', 'appointzilla'), 'administrator', 'app-calendar-settings', 'display_settings_page' );

    // Remove Plugin
    $SubMenu16 = add_submenu_page( 'appointment-calendar', __('Remove Plugin', 'appointzilla'), __('Remove Plugin', 'appointzilla'), 'administrator', 'uninstall-plugin', 'display_uninstall_plugin_page' );

    // Support & Help
    $SubMenu17 = add_submenu_page( 'appointment-calendar', __('Help & Support', 'appointzilla'), __('Help & Support', 'appointzilla'), 'administrator', 'support-n-help', 'display_support_n_help_page' );

    //client-calendar
    $SubMenu22 = add_submenu_page( 'appointment-calendar', __('Appointment Calendar', 'appointzilla'), __('Appointment Calendar', 'appointzilla'), 'subscriber', 'client-appointment-calendar', 'display_client_appointment_calendar_page' );
    $SubMenu23 = add_submenu_page( 'appointment-calendar', __('Your Appointments', 'appointzilla'), __('Your Appointments', 'appointzilla'), 'subscriber', 'manage-client-appointments', 'display_client_mange_appointments_page' );
    $SubMenu24 = add_submenu_page( '', 'Update Appointment', '', 'subscriber', 'update-client-appointment', 'display_update_client_appointment_page' );

    add_action( 'admin_print_styles-' . $Menu, 'calendar_css_js' );
    //calendar
    add_action( 'admin_print_styles-' . $SubMenu1, 'calendar_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu2, 'calendar_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu3, 'calendar_css_js' );
    //service
    add_action( 'admin_print_styles-' . $SubMenu4, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu5, 'other_pages_css_js' );
    //staff
    add_action( 'admin_print_styles-' . $SubMenu6, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu7, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu7_1, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu8, 'calendar_css_js' );
    //time-off
    add_action( 'admin_print_styles-' . $SubMenu9, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu10, 'other_pages_css_js' );
    //client
    add_action( 'admin_print_styles-' . $SubMenu11, 'other_pages_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu12, 'other_pages_css_js' );
    //manage app
    add_action( 'admin_print_styles-' . $SubMenu13, 'manage_appointments_css_js' );
    add_action( 'admin_print_styles-' . $SubMenu14, 'other_pages_css_js' );
    //settings
    add_action( 'admin_print_styles-' . $SubMenu15, 'other_pages_css_js' );
    //remove plugin
    add_action( 'admin_print_styles-' . $SubMenu16, 'other_pages_css_js' );
    //support n help
    add_action( 'admin_print_styles-' . $SubMenu17, 'other_pages_css_js' );
    //payment txn
    add_action( 'admin_print_styles-' . $SubMenu18, 'other_pages_css_js' );
    //export lists
    add_action( 'admin_print_styles-' . $SubMenu19, 'other_pages_css_js' );
    //staff manage appointment
    add_action( 'admin_print_styles-' . $SubMenu20, 'other_pages_css_js' );
    //coupons codes
    add_action( 'admin_print_styles-' . $SubMenu21, 'other_pages_css_js' );
    //client calendar
    add_action( 'admin_print_styles-' . $SubMenu22, 'calendar_css_js' );
    //client appointments
    add_action( 'admin_print_styles-' . $SubMenu23, 'other_pages_css_js' );
    //client update appointment
    add_action( 'admin_print_styles-' . $SubMenu24, 'other_pages_css_js' );
}// end of menu function


// manage appointments styles
function manage_appointments_css_js(){
	
	wp_enqueue_style('font-awesome-css',plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__));
	
	wp_enqueue_style('bootstrap',plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__));
	wp_enqueue_style('bootstrap-css',plugins_url('/bootstrap_table_master/docs/assets/bootstrap/css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap-theme-min',plugins_url('/bootstrap_table_master/docs/assets/bootstrap/css/bootstrap-theme.min.css', __FILE__));
	wp_enqueue_style('bootstrap-table-css',plugins_url('/bootstrap_table_master/dist/bootstrap-table.css', __FILE__));
	wp_enqueue_style('bootstrap-table-min',plugins_url('/bootstrap_table_master/dist/bootstrap-table.min.css', __FILE__));
	wp_enqueue_style('bootstrap-editable',plugins_url('/bootstrap3-editable/css/bootstrap-editable.css', __FILE__));

	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap-js',plugins_url('/bootstrap_table_master/docs/assets/bootstrap/js/bootstrap.min.js', __FILE__));
	wp_enqueue_script('bootstrap-table-js',plugins_url('/bootstrap_table_master/dist/bootstrap-table.js', __FILE__));
	wp_enqueue_style('bootstrap-table-filter',plugins_url('/bootstrap_filter/src/bootstrap-table-filter.css', __FILE__));
	wp_enqueue_script('bootstrap-table-filter-js',plugins_url('/bootstrap_filter/src/bootstrap-table-filter.js', __FILE__));
	wp_enqueue_script('bootstrap-table-filter-filter-js',plugins_url('/bootstrap_table_master/src/extensions/filter/bootstrap-table-filter.js', __FILE__));
	wp_enqueue_script('bootstrap-table-filter-ext-js',plugins_url('/bootstrap_filter/src/ext/bs-table.js', __FILE__));
	wp_enqueue_script('bootstrap3-editable-js',plugins_url('/bootstrap3-editable/js/bootstrap-editable.min.js', __FILE__));
}



function calendar_css_js() {
    wp_register_script(
        'jquery-custom',
        plugins_url('menu-pages/fullcalendar-assets-new/js/jquery-ui-1.8.23.custom.min.js', __FILE__),
        array('jquery'),
        true
    );

    wp_enqueue_script('full-calendar',plugins_url('/menu-pages/fullcalendar-assets-new/js/fullcalendar.min.js', __FILE__),array('jquery','jquery-custom'));
    wp_enqueue_script('datepicker-js',plugins_url('/menu-pages/datepicker-assets/js/jquery.ui.datepicker.js', __FILE__),array('jquery','jquery-custom'));
    wp_register_style('bootstrap-css',plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('bootstrap-css');
    wp_enqueue_style('fullcalendar-css',plugins_url('/menu-pages/fullcalendar-assets-new/css/fullcalendar.css', __FILE__));
    wp_enqueue_style('datepicker-css',plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));
    wp_enqueue_style('apcal-css',plugins_url('/menu-pages/css/apcal-css.css', __FILE__));
}

function other_pages_css_js() {
    wp_register_style('bootstrap-css',plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('bootstrap-css');
    wp_enqueue_style('datepicker-css',plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));
    wp_enqueue_script('tooltip',plugins_url('/bootstrap-assets/js/bootstrap-tooltip.js', __FILE__),array('jquery'));
    wp_enqueue_script('bootstrap-affix',plugins_url('/bootstrap-assets/js/bootstrap-affix.js', __FILE__));
    wp_enqueue_script('bootstrap-application',plugins_url('/bootstrap-assets/js/application.js', __FILE__));

    //font-awesome js n css
    wp_enqueue_style(
        'font-awesome-css',
        plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__)
    );
    wp_enqueue_style('apcal-css',plugins_url('/menu-pages/css/apcal-css.css', __FILE__));
}

//short-code detect
function shortcode_detect() {
    global $wp_query;
    $posts = $wp_query->posts;
    $pattern = get_shortcode_regex();
    
    foreach ($posts as $post) {
        if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'APCAL_BTN', $matches[2] ) || in_array( 'APCAL_MOBILE', $matches[2] ) || in_array( 'APCAL', $matches[2] ) ) {
            //wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
            //de-register script hook
            //remove_action('wp_enqueue_scripts', 'wp_foundation_js');
            //remove_action('wp_enqueue_scripts', 'modernize_it');

            wp_register_script( 'jquery-custom', plugins_url('menu-pages/fullcalendar-assets-new/js/jquery-ui-1.8.23.custom.min.js', __FILE__), 10, array('jquery'), false, true );
            wp_enqueue_script('apcal-full-calendar',plugins_url('/menu-pages/fullcalendar-assets-new/js/fullcalendar.min.js', __FILE__),array('jquery','jquery-custom'));
            wp_enqueue_script('apcal-calendar',plugins_url('calendar/calendar.js', __FILE__));
            wp_enqueue_script('apcal-moment-min',plugins_url('calendar/moment.min.js', __FILE__));
            wp_enqueue_style('apcal-bootstrap-apcal',plugins_url('bootstrap-assets/css/bootstrap-apcal.css', __FILE__));
            wp_enqueue_style('apcal-fullcalendar-css',plugins_url('/menu-pages/fullcalendar-assets-new/css/fullcalendar.css', __FILE__));
            wp_enqueue_style('apcal-datepicker-css',plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));
            //font-awesome js n css
            wp_enqueue_style(
                'font-awesome-css',
                plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__)
            );
			
			// responsive css file
			wp_enqueue_style('responsive',plugins_url('/bootstrap-assets/css/responsive.css', __FILE__));
			
            //localization js
            //wp_enqueue_script('apcal-datepicker-css',plugins_url('/menu-pages/datepicker-assets/jquery-ui-localization/jquery.ui.datepicker-ar.js', __FILE__));

            //jetpack tweak remove open graph tag if jetpack plugin activated
            remove_action('wp_head', 'jetpack_og_tags');
            break;
        }
    }
}
add_action( 'wp', 'shortcode_detect' );

// Rendering All appointment-calendar Menu Page

 //calendar page
 function display_calendar_page() {
     require_once('menu-pages/calendar.php');
 }
 
 //time slot page
 function display_time_slot_page() {
     require_once("menu-pages/appointment-form2.php");
 }
 
 //appointment save page
 function display_data_save_page() {
     require_once("menu-pages/data_save.php");
 }
 
 //service page
 function display_service_page() {
     require_once("menu-pages/service.php");
 }
 //manage service page
 function display_manage_service_page() {
     require_once("menu-pages/manage-service.php");
 }
 
 //staff page
 function display_staff_page() {
     require_once("menu-pages/staff.php");
 }
 function display_manage_staff_page() {
     require_once("menu-pages/manage-staff.php");
 }
function display_staff_google_calendar_sync_page() {
    require_once("menu-pages/staff-google-calendar-sync.php");
}


 
 //time-off page
 function display_timeoff_page() {
     require_once("menu-pages/timeoff.php");
 }
 //update-time-off page
 function display_update_timeoff_page() {
     require_once("menu-pages/update-timeoff.php");
 }
 
 function display_staff_profile_page() {
     require_once("menu-pages/staff-profile.php");
 }
 
 //client page
 function display_client_page() {
     require_once("menu-pages/client.php");
 }
 function display_manage_client_page() {
     require_once("menu-pages/client_manage.php");
 }
 
 //manage-appointment page
 function display_manage_appointment_page() {
     require_once("menu-pages/manage-appointments.php");
 }
//update appointment page
 function display_update_appointment_page() {
     require_once("menu-pages/update-appointments.php");
 }

 //payment transaction page
 function display_payment_transaction_page() {
     require_once("menu-pages/payment-transaction.php");
 }

//export appointments & clients lists
function display_export_lists_page() {
    require_once("menu-pages/export-lists.php");
}

//coupons codes page
function display_coupons_codes_page() {
    require_once("menu-pages/coupons-codes.php");
}
 
 //settings page
 function display_settings_page() {
     require_once("menu-pages/settings.php");
 }

 // Uninstall plugin
 function display_uninstall_plugin_page() {
     require_once("uninstall-plugin.php");
 }
 
 // Support & Help
 function display_support_n_help_page() {
     require_once("menu-pages/supportnhelp.php");
 }
 
 //staff calendar page
 function display_staff_appointment_calendar_page() {
     require_once("menu-pages/staff-appointment-calendar.php");
 }
//staff appointments page
function display_staff_appointments_page() {
    require_once("menu-pages/manage-staff-appointments.php");
}

//client calendar page
function display_client_appointment_calendar_page() {
    require_once("menu-pages/client-appointment-calendar.php");
}
//client appointments page
function display_client_mange_appointments_page() {
    require_once("menu-pages/manage-client-appointments.php");
}
//client update appointment page
function display_update_client_appointment_page() {
    require_once("menu-pages/update-client-appointments.php");
}

// Including Calendar Short-Code Page
require_once("appointment-calendar-shortcode.php");

// Including Calendar Button Short-Code Page
require_once("appointment-calendar-button-shortcode.php");

//Including Calendar Mobile Shortcode
require_once("appointment-calendar-mobile-shortcode.php");

//run cron reminder
add_action( 'plugins_loaded', 'load_apcal_reminder', 10 ); // this hook for wp_mail use after all plugins_loaded
function load_apcal_reminder() {

    // insert row every second 5 second on recurring visit by any user on site
    add_action('wp', 'apcal_reminder_activation');
    function apcal_reminder_activation() {
        if ( !wp_next_scheduled( 'apcal_reminder_event' ) ) {
            wp_schedule_event( time(), 'customrecurrence', 'apcal_reminder_event');
        }
    }

    add_action('apcal_reminder_event', 'send_apcal_reminders');
    function send_apcal_reminders() {
        // Including Email Reminder Class
        require_once('menu-pages/EmailReminder.php');
    }

    //custom recurrence
    function custom_recurrence_time( $schedules ) {
        $schedules['customrecurrence'] = array(
            'interval' => 60*60,
            'display' => __('Every Hour')
        );
        return $schedules;
    }
    add_filter( 'cron_schedules', 'custom_recurrence_time' );
}//end of load_apcal_reminder



/*
*
*
* THIS CODE FOR MANAGE APPOINMENTS SHOW ALL APPOINTMENTS, CHANGE APPOINTMENT STATUS ,SINGLE DELETE APPOINTMENT , MULTIPLE DELETE APPOINMENTS ------------------------------------------------------
*/

//delete all appointment with ajax
add_action("wp_ajax_appoints_deleteall","_delete_appointmentall");
function _delete_appointmentall()
{	
	$ids = array();
	$ids = explode(',',$_GET['delete_all']);
	
	if(count($ids)<1)
	{
		$ids[] = $_GET['delete_all'];
	}
	
	global $wpdb;

	
	foreach($ids as $id){
		
		$AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
        $AppointmentsTable = $wpdb->prefix . "ap_appointments";

        require_once('menu-pages/google-appointment-sync-class.php');
        require_once('menu-pages/google-staff-appointment-sync-class.php');

        //admin sync details
        $CalData = get_option('google_caelndar_settings_details');
        $ClientId = $CalData['google_calendar_client_id'];
        $ClientSecretId = $CalData['google_calendar_secret_key'];
        $RedirectUri = $CalData['google_calendar_redirect_uri'];
        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);

        // staff sync details
        $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
		
            $DeleteId = $id;
            /**
             * Delete all selected admin sync appointment from his google calendar
             */
            $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
            if(count($Row)) {
                $Row = unserialize($Row->app_sync_details);
                $json  = json_encode($Row);
                $Row = json_decode($json, true);
                $SyncId = $Row['id'];
                if($SyncId) {
                    $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);
                    // delete sync details
                    $wpdb->query("DELETE FROM `$AppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
                }
            }

            /**
             * Delete Staff sync appointment from his google calendar
             */
            $StaffDetails = $wpdb->get_row("SELECT `staff_id` FROM `$AppointmentsTable` WHERE `id` = '$DeleteId'");
            if(count($StaffDetails)){
                $StaffId = $StaffDetails->staff_id;
                $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];
                $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                $StaffRow = $wpdb->get_row("SELECT * FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
                if(count($StaffRow)) {
                    $StaffRow = unserialize($StaffRow->staff_sync_details);
                    $json  = json_encode($StaffRow);
                    $StaffRow = json_decode($json, true);
                    $SyncId = $StaffRow['id'];
                    if($SyncId) {
                        $StaffOAuth = $StaffGoogleAppointmentSync->DeleteStaffSync($StaffId, $SyncId);
                        // delete sync details
                        $wpdb->query("DELETE FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
                    }
                }
            }

            /**
             * Delete all selected appointment records
             */
            $DeleteAppointmentQuery = "DELETE FROM `$AppointmentsTable` WHERE `id` = '$DeleteId';";
            $wpdb->query($DeleteAppointmentQuery);
	}
	echo 'success';
	exit;
}


//delete appointment with ajax
add_action("wp_ajax_appointment_delete", "_delete_appointment");
function _delete_appointment()
{
	if(isset($_GET['delete'])) {
		global $wpdb;
        $AppointmentsTable = $wpdb->prefix . "ap_appointments";
        $DeleteId = $_GET['delete'];
        $StaffDetails = $wpdb->get_row("SELECT `staff_id` FROM `$AppointmentsTable` WHERE `id` = '$DeleteId'");
		
        $StaffId = $StaffDetails->staff_id;
        $DeleteAppointmentQuery = "DELETE FROM `$AppointmentsTable` WHERE `id` = '$DeleteId'";
        if($wpdb->query($DeleteAppointmentQuery)) {

            /**
             * Delete admin sync appointment from his google calendar
             */
            global $wpdb;
            $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
            $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
            if(count($Row)) {
                $Row = unserialize($Row->app_sync_details);
                $json  = json_encode($Row);
                $Row = json_decode($json, true);
                $SyncId = $Row['id'];
                if($SyncId) {
                    $CalData = get_option('google_caelndar_settings_details');
                    $ClientId = $CalData['google_calendar_client_id'];
                    $ClientSecretId = $CalData['google_calendar_secret_key'];
                    $RedirectUri = $CalData['google_calendar_redirect_uri'];

                    require_once('menu-pages/google-appointment-sync-class.php');
                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);

                    // delete sync details
                    $wpdb->query("DELETE FROM `$AppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
                }
            }

            /**
             * Delete Staff sync appointment from his google calendar
             */
            $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
            $StaffRow = $wpdb->get_row("SELECT * FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
            if(count($StaffRow)) {
                $StaffRow = unserialize($StaffRow->staff_sync_details);
                $json  = json_encode($StaffRow);
                $StaffRow = json_decode($json, true);
                $SyncId = $StaffRow['id'];
                if($SyncId) {
                    $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                    $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                    $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                    $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                    $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];

                    require_once('menu-pages/google-staff-appointment-sync-class.php');
                    $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                    $StaffOAuth = $StaffGoogleAppointmentSync->DeleteStaffSync($StaffId, $SyncId);

                    // delete sync details
                    $wpdb->query("DELETE FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$DeleteId'");
                }
            }

            echo "<script>alert('".__('Appointment successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='".admin_url('admin.php?page=manage-appointments')."';</script>";
        }
    }
	exit;
}


// status changing ajax
add_action("wp_ajax_appointments_with_status", "_change_status");
function _change_status()
{
	$name  = $_POST['name'];
	$value = $_POST['value'];
	$id = $_POST['pk'];
	
	if(isset($name))
	{
		$obj = new manage_appointment();
		$obj->update_status($id,$name,$value);
	}
}

add_action("wp_ajax_manage_appointments", "manage_appointments_function");
function manage_appointments_function()
{	
	$sort      = ( isset($_GET['sort']   )  ? $_GET['sort']  : 'id' );
	$sort_val  = ( isset($_GET['order']  )  ? $_GET['order']  : 'desc' );
	$limit     = ( isset($_GET['limit']  )  ? $_GET['limit']  : 10 );
	$offset    = ( isset($_GET['offset'] )  ? $_GET['offset'] : 0 );
	$search    = ( isset($_GET['search'] )  ? $_GET['search'] : '' );
	$filter    = ( isset($_GET['filter'] )  ? $_GET['filter'] : 'All' );
	
	global $wpdb;
	$AppointmentTableName = $wpdb->prefix . "ap_appointments";
	$query = "select * from `$AppointmentTableName` where ";
	$query .="`name` like '%".$search."%' ";
		
	$obj = new manage_appointment();
	
	if($filter!='All' && $_GET['filter']!='{}')
	{
		$filter = json_decode(str_replace("\\","",$_GET['filter']));
		
		foreach($filter as $key=>$val)
		{
			 $arr=array();
             $name=$key;  
			 if($name=='status'){
                  foreach ($val as $key=>$vals) {
					  $in = implode("','", $vals);
					  $query .=" and `$name` IN ('$in') ";
                  }
             }
			 elseif($name=='date')
			 {
				$today_date = date('Y-m-d');
				$query .=" and `$name` ='$today_date' ";
			 }
			 elseif($name=='payment_status')
			 {
				 foreach ($val as $key=>$vals) {
                      $in = implode("','", $vals);
					  $query .=" and `$name` IN ('$in') ";
                  }
			 }
			 elseif($name=='service_id')
			 {
				 foreach ($val as $key=>$vals) {
                     $in = implode("','", $vals);
					 $in = str_replace('service-','',$in);
					 $query .=" and `$name` IN ('$in') ";
                  }
			 }
			 elseif($name=='staff_id')
			 {
				 foreach ($val as $key=>$vals) {
					 $in = implode("','", $vals);
					 $in = str_replace('staff-','',$in);
					 $query .=" and `$name` IN ('$in') ";
                  }
			 }
		}
		
		$query2 = $query." ORDER BY $sort $sort_val";
		$query .=" ORDER BY $sort $sort_val limit $offset, $limit";
		
		$obj->get_appointment_data($query,$query2);
	}
	else
	{
		$obj->show_all_appointments($offset,$limit,$sort_val,$sort,$search);
	}
	exit;
}

// manage appointment class 
class manage_appointment{
	
	protected $AppointmentTableName;
	
	public function show_all_appointments($offset,$limit,$sort_val,$sort,$search)
	{
		global $wpdb;
	    $AppointmentTableName = $wpdb->prefix . "ap_appointments";
		$all_appointments = $wpdb->get_results("select * from `$AppointmentTableName` where `name` like '%".$search."%' ORDER BY $sort $sort_val limit $offset, $limit");
		
		$all_appointments1 = $wpdb->get_results("select * from `$AppointmentTableName` where `name` like '%".$search."%' ORDER BY $sort $sort_val");
		$count = count($all_appointments1);
		
		foreach($all_appointments as $appointment)
		{
			$obj = new manage_appointment();
			$staff_name = $obj->get_staff_name($appointment->staff_id);
			$service_name = $obj->get_service_name($appointment->service_id);
			$time = $obj->get_time($appointment->start_time,$appointment->end_time);
			$date = $obj->get_date($appointment->recurring,$appointment->recurring_st_date,$appointment->recurring_ed_date,$appointment->date);
			
			$msg="'Do you want to delete this appointment?'";
			$data[] = array('id'=>$appointment->id,'name'=>$appointment->name,'staff_id'=>$staff_name,'date'=>$date,'recurring_type'=>$appointment->recurring_type,'payment_status'=>$appointment->payment_status,'service_id'=>$service_name,'time'=>$time,'status'=>'<a href="javascript:void(0)" data-pk="'.$appointment->id.'" data-title="Select status" data-name="status" data-value="'.$appointment->status.'" class="change_status">'.$appointment->status.'</a>','action'=>'<a href="'.admin_url('admin.php?page=update-appointment&viewid='.$appointment->id).'"><i class="fa fa-eye"></i></a><a href="'.admin_url('admin.php?page=update-appointment&updateid='.$appointment->id).'"><i class="fa fa-edit"></i></a><a href="'.admin_url('admin-ajax.php?action=appointment_delete&delete='.$appointment->id).'" onclick="return confirm('.$msg.')"><i class="fa fa-times"></i></a>');
		}
		
		if($count!=0)
		{
			$output = array('rows'=>$data,'total'=>$count);
			echo json_encode($output); // all appointments
		}
		else
		{
			$output = array('rows'=>array(),'total'=>$count);
			echo json_encode($output);
		}
	}
	
	
	public function get_appointment_data($query,$query2)
	{
		global $wpdb;
		$all_appointments = $wpdb->get_results($query);
		$all_appointments1 = $wpdb->get_results($query2);
		
		$count = count($all_appointments1);
		
		foreach($all_appointments as $appointment)
		{
			$staff_name = self::get_staff_name($appointment->staff_id);
			$service_name = self::get_service_name($appointment->service_id);
			$time = self::get_time($appointment->start_time,$appointment->end_time);
			$date = self::get_date($appointment->recurring,$appointment->recurring_st_date,$appointment->recurring_ed_date,$appointment->date);
			
			$msg="'Do you want to delete this appointment?'";
			$data[] = array('id'=>$appointment->id,'name'=>$appointment->name,'staff_id'=>$staff_name,'date'=>$date,'recurring_type'=>$appointment->recurring_type,'payment_status'=>$appointment->payment_status,'service_id'=>$service_name,'time'=>$time,'status'=>'<a href="javascript:void(0)" data-pk="'.$appointment->id.'" data-title="Select status" data-name="status" data-value="'.$appointment->status.'" class="change_status">'.$appointment->status.'</a>','action'=>'<a href="'.admin_url('admin.php?page=update-appointment&viewid='.$appointment->id).'"><i class="fa fa-eye"></i></a><a href="'.admin_url('admin.php?page=update-appointment&updateid='.$appointment->id).'"><i class="fa fa-edit"></i></a><a href="'.admin_url('admin-ajax.php?action=appointment_delete&delete='.$appointment->id).'" onclick="return confirm('.$msg.')"><i class="fa fa-times"></i></a>');
		}
		
		if($count!=0)
		{
			$output = array('rows'=>$data,'total'=>$count);
			echo json_encode($output); 
		}
		else
		{
			$output = array('rows'=>array(),'total'=>$count);
			echo json_encode($output);
		}
	}
	
	public function get_staff_name($staff_id)
	{
		global $wpdb;
		$staff_table_name = $wpdb->prefix . "ap_staff";
		$staff_details = $wpdb->get_row("SELECT * FROM $staff_table_name WHERE `id` = '$staff_id'");
        return ucfirst($staff_details->name); // staff name
	}
	
	public function get_service_name($service_id)
	{
		global $wpdb;
		$service_table_name = $wpdb->prefix . "ap_services";
		$servicedetails= $wpdb->get_row("SELECT * FROM $service_table_name WHERE `id` = '$service_id'");
        return ucfirst($servicedetails->name); // service name
	}
	
	public function get_date($recurring,$recurring_st_date,$recurring_ed_date,$date)
	{
		$DateFormat = get_option('apcal_date_format');
		if($DateFormat == '') $DateFormat = "d-m-Y";
		
        if($recurring == 'yes')
             return date($DateFormat, strtotime($recurring_st_date))." - ".date($DateFormat, strtotime($recurring_ed_date));
        else
             return date($DateFormat, strtotime($date)); // date format
	}
	
	public function get_time($start_time,$end_time)
	{
		$TimeFormat = get_option('apcal_time_format');
		if($TimeFormat == '') $TimeFormat = "h:i";
		if($TimeFormat == 'h:i') $ATimeFormat = "g:ia"; else $ATimeFormat = "G:i";
		
        return date($ATimeFormat, strtotime($start_time))." - ".date($ATimeFormat, strtotime($end_time));
		// time format
	}
	
	public function update_status($id,$name,$value)
	{
		global $wpdb;
		$AppointmentTableName = $wpdb->prefix . "ap_appointments";
		$DateFormat = get_option('apcal_date_format');
		if($DateFormat == '') $DateFormat = "d-m-Y";
		$TimeFormat = get_option('apcal_time_format');
		if($TimeFormat == '') $TimeFormat = "h:i";
		$status = $value;
        $up_app_id = $id;
        $update_appointment ="UPDATE `$AppointmentTableName` SET `status` = '$status' WHERE `id` = '$up_app_id'";
		
        if($wpdb->query($update_appointment)) {
            $Appointment_details = $wpdb->get_row("SELECT * FROM `$AppointmentTableName` WHERE `id` = '$up_app_id' ", OBJECT);
            $name = strip_tags($Appointment_details->name);
            $email = $Appointment_details->email;
            $serviceid = $Appointment_details->service_id;
            $staffid = $Appointment_details->staff_id;
            $phone = $Appointment_details->phone;
            $start_time = date("h:i A", strtotime($Appointment_details->start_time));
            $end_time = date("h:i A", strtotime($Appointment_details->end_time));
            $appointmentdate = date("Y-m-d", strtotime($Appointment_details->date));
            $note = strip_tags($Appointment_details->note);
            $status = $Appointment_details->status;
            $recurring = $Appointment_details->recurring;
            $recurring_type = $Appointment_details->recurring_type;
            $recurring_st_date = date("Y-m-d", strtotime($Appointment_details->recurring_st_date));
            $recurring_ed_date = date("Y-m-d", strtotime($Appointment_details->recurring_ed_date));
            $appointment_by = $Appointment_details->appointment_by;


            //send notification to client if appointment approved or cancelled
            if($status == 'approved' || $status == 'cancelled' ) {
                $GetAppKey = $wpdb->get_row("SELECT * FROM `$AppointmentTableName` WHERE `id` = '$up_app_id' ", OBJECT);

                $BlogName =  get_bloginfo();
                $ClientTable = $wpdb->prefix . "ap_clients";
                $GetClient = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$email' ", OBJECT);
                if($up_app_id && $GetClient->id) {
                    $AppId = $up_app_id;
                    $ServiceId = $serviceid;
                    $StaffId = $staffid;
                    $ClientId = $GetClient->id;
                    //include notification class
                    require_once('menu-pages/notification-class.php');
                    $Notification = new Notification();
                    if($status == 'approved') $On = "approved";
                    if($status == 'cancelled') {
                        $On = "cancelled";
                        //notify admin only on cancel any appointment
                        $Notification->notifyadmin($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }
                    //notify client
                    $Notification->notifyclient($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);

                    //notify staff
                    if(get_option('staff_notification_status') == 'on') {
                        $Notification->notifystaff($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }
                }

            }// end send notification to client if appointment approved or cancelled ckech

            $appointmentdate = date("Y-m-d",strtotime($appointmentdate));
            $recurring_st_date = date("Y-m-d",strtotime($recurring_st_date));
            $recurring_ed_date = date("Y-m-d",strtotime($recurring_ed_date));

            //if status is approved then sync appointment
            if($status == 'approved') {

                //add service name with event title($name)
                //$ServiceTable = $wpdb->prefix . "ap_services";
                //$ServiceData = $wpdb->get_row("SELECT * FROM `$ServiceTable` WHERE `id` = '$serviceid'");
                //$name = $name."(".$ServiceData->name.")";

                /***
                 * admin appointment sync
                 */
                $CalData = get_option('google_caelndar_settings_details');
                if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                    $ClientId = $CalData['google_calendar_client_id'];
                    $ClientSecretId = $CalData['google_calendar_secret_key'];
                    $RedirectUri = $CalData['google_calendar_redirect_uri'];
                    require_once('menu-pages/google-appointment-sync-class.php');

                    global $wpdb;
                    $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
                    $AnySycnId = $wpdb->get_row("SELECT `id` FROM `$AppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                    //check appointment already synced or first time approved
                    if(count($AnySycnId)) {
                        // update this appointment event on calendar
                        global $wpdb;
                        $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
                        $SyncDetails = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                        $SyncTableRowId = $SyncDetails->id;
                        $SyncDetailsData = unserialize($SyncDetails->app_sync_details);
                        $json  = json_encode($SyncDetailsData);
                        $SyncDetailsData = json_decode($json, true);
                        $sync_id = $SyncDetailsData['id'];
                        $sync_email = $SyncDetailsData['creator']['email'];

                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $tag = "Appointment with: ";
                        if($recurring_type == 'none') {
                            $OAuth = $GoogleAppointmentSync->UpdateNormalSync($sync_id, $sync_email, $name, $appointmentdate, $start_time, $end_time, $note, $tag);
                        }
                        if($recurring_type != 'none') {
                            $OAuth = $GoogleAppointmentSync->UpdateRecurringSync($sync_id, $sync_email, $name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                        }

                        //update appointment sync details
                        $OAuth = serialize($OAuth);
                        $wpdb->query("UPDATE `$AppointmentSyncTable` SET `app_sync_details` = '$OAuth' WHERE `id` = '$SyncTableRowId'");
                    } else {
                        // insert this appointment event on calendar
                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $tag = "Appointment with: ";
                        if($recurring_type == 'none') {
                            $OAuth = $GoogleAppointmentSync->NormalSync($name, $appointmentdate, $start_time, $end_time, $note, $tag);
                        }
                        if($recurring_type != 'none') {
                            $OAuth = $GoogleAppointmentSync->RecurringSync($name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                        }

                        //insert appointment sync details
                        $OAuth = serialize($OAuth);
                        $wpdb->query("INSERT INTO `$AppointmentSyncTable` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$up_app_id', '$OAuth' );");
                    }
                }//end of if cal settings check


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

                    //check staff appointment already synced or first time approved
                    global $wpdb;
                    $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                    $AnyStaffSycnId = $wpdb->get_row("SELECT `id` FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                    if(count($AnyStaffSycnId)) {
                        // update this staff appointment event on his google calendar
                        global $wpdb;
                        $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                        $SyncDetails = $wpdb->get_row("SELECT * FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                        $SyncTableRowId = $SyncDetails->id;
                        $SyncDetailsData = unserialize($SyncDetails->staff_sync_details);
                        $json  = json_encode($SyncDetailsData);
                        $SyncDetailsData = json_decode($json, true);
                        $sync_id = $SyncDetailsData['id'];
                        $sync_email = $SyncDetailsData['creator']['email'];

                        $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                        $tag = __("Appointment with: ", "appointzilla");
                        if($recurring_type == 'none') {
                            $StaffOAuth = $StaffGoogleAppointmentSync->UpdateNormalStaffSync($staffid, $sync_id, $sync_email, $name, $appointmentdate, $start_time, $end_time, $note, $tag);
                        }
                        if($recurring_type != 'none') {
                            $StaffOAuth = $StaffGoogleAppointmentSync->UpdateRecurringStaffSync($staffid ,$sync_id, $sync_email, $name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                        }

                    } else {
                        // add this staff appointment event on his google calendar
                        $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                        $Tag = __("Appointment with: ", "appointzilla");
                        if($recurring_type == 'none') {
                            $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($staffid, $name, $appointmentdate, $start_time, $end_time, $note, $Tag);
                        }
                        if($recurring_type != 'none') {
                            $StaffOAuth = $StaffGoogleAppointmentSync->RecurringStaffSync($staffid, $name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $Tag);
                        }

                        //insert staff appointment sync details
                        $StaffOAuth = serialize($StaffOAuth);
                        $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$up_app_id', '$StaffOAuth' )");
                    }
                }//end of staff appointment sync
            }//end of if approved*/




            /**
             * if status is cancelled then delete sync appointment
             */
            if($status == 'cancelled' || $status == 'pending') {
                global $wpdb;
                $AppointmentSyncTable = $wpdb->prefix . "ap_appointment_sync";
                $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                if(count($Row)) {
                    $Row = unserialize($Row->app_sync_details);
                    $json  = json_encode($Row);
                    $Row = json_decode($json, true);
                    $SyncId = $Row['id'];
                    if($SyncId) {
                        $CalData = get_option('google_caelndar_settings_details');
                        $ClientId = $CalData['google_calendar_client_id'];
                        $ClientSecretId = $CalData['google_calendar_secret_key'];
                        $RedirectUri = $CalData['google_calendar_redirect_uri'];

                        require_once('menu-pages/google-appointment-sync-class.php');
                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);

                        // delete sync details
                        $wpdb->query("DELETE FROM `$AppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                    }
                }
            }//end of cancel status check



            /**
             * if status is cancelled then delete staff sync appointment
             */
            if($status == 'cancelled' || $status == 'pending') {
                global $wpdb;
                $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                $StaffRow = $wpdb->get_row("SELECT * FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                if(count($StaffRow)) {
                    $StaffRow = unserialize($StaffRow->staff_sync_details);
                    $json  = json_encode($StaffRow);
                    $StaffRow = json_decode($json, true);
                    $SyncId = $StaffRow['id'];
                    if($SyncId) {
                        $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                        $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                        $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                        $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                        $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];

                        require_once('menu-pages/google-staff-appointment-sync-class.php');
                        $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                        $StaffOAuth = $StaffGoogleAppointmentSync->DeleteStaffSync($staffid, $SyncId);

                        // delete sync details
                        $wpdb->query("DELETE FROM `$StaffAppointmentSyncTable` WHERE `app_id` = '$up_app_id'");
                    }
                }
            }//end of cancel status check
        } // end of update query
		echo $value;
		exit;
	}
}

/*
* MANAGE APPOINMENTS ------------------------------------------------------
*/