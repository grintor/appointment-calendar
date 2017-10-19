<?php global $wpdb;
$wpdb->query('SET @@global.sql_mode = "";');
$AdminEmail = get_bloginfo('admin_email');

//default calendar options & settings
$CalendarSettingsArray = array(
    'small_calendar_type' => 'php',                           // default php calendar
    'calendar_slot_time' => '30',                           // 30 min slots
    'day_start_time' => '10:00 AM',                         // 10:00 AM
    'day_end_time' => '5:00 PM',                            // 5:00 PM
    'calendar_view' => 'month',                             // month
    'calendar_start_day' => '1',                            // monday
    'booking_button_text' => 'Schedule An Appointment',     // Schedule An Appointment
    'booking_user_timeslot' => '30',                        // user booking time slots
    'show_service_cost' => 'yes',                           // for service cost hide or show
    'show_service_duration' => 'yes',                       // for service duration hide or show
    'apcal_user_registration' => 'yes',                     // enable client registration on booking appointments
    'apcal_new_appointment_status' => 'pending',            // default status on booking an appointment
    'apcal_booking_instructions' => 'Put your booking instructions here.<br>Or you can save It blank in case of nothing want to display.' // booking instruction before booking button
);
add_option('apcal_calendar_settings', serialize($CalendarSettingsArray));
//time string
add_option('apcal_time_format', 'h:i');
//date string
add_option('apcal_date_format', 'm-d-Y');

//notification settings
add_option('emailstatus', 'on');                        //on
add_option('emailtype', 'wpmail');                      //wp-mail
$EmailDetails =  array ( 'wpemail' => $AdminEmail );
add_option( 'emaildetails', $EmailDetails);             // current admin email
//staff notification enable
add_option('staff_notification_status', 'on');


//google calendar sync details
add_option('google_calendar_twoway_sync', 'no');

//payment settings
add_option('ap_accept_payment', 'no');                  // yes/no
add_option('ap_payment_type', 'full');                  // full/partial
add_option('ap_payment_percentage_ammount', '0');       // 50%, 20%
add_option('ap_payment_currency_type', 'USD');          // USD, IN, ER

/**
 * Client Notification Message
 */
//Notify Client On New Appointment
$booking_client_subject = "[blog-name]: Your appointment status is [app-status].";
$booking_client_body = "
Hi [client-name],

Thank you for scheduling appointment with [blog-name].

Your appointment for [service-name] with [staff-name] on [app-date] at [app-time].

Currently, your appointment status is [app-status].

You will get a confirmation mail once admin accepts the appointment.

Best Regards 
[blog-name].
";
add_option('booking_client_subject', $booking_client_subject);
add_option('booking_client_body', $booking_client_body);


//Notify Client On Approve Appointment
$approve_client_subject = "[blog-name]: Your appointment status is [app-status].";
$approve_client_body = "
Hi [client-name],

Your appointment has been [app-status] by admin.

Now, your appointment for [service-name] with [staff-name] on [app-date] at [app-time].

Thank you for scheduling appointment with [blog-name].

Best Regards
[blog-name].
";
add_option('approve_client_subject', $approve_client_subject);
add_option('approve_client_body', $approve_client_body);


//Notify Client On Cancel Appointment
$cancel_client_subject = "[blog-name]: Your appointment status is [app-status].";
$cancel_client_body = "
Hi [client-name],

Sorry! Due to some reason we are unable to complete your appointment request.

Now, your appointment for [service-name] with [staff-name] on [app-date] at [app-time] has been [app-status] by admin.

Thank you for scheduling appointment with [blog-name].

Best Regards
[blog-name].
";
add_option('cancel_client_subject', $cancel_client_subject);
add_option('cancel_client_body', $cancel_client_body);


/**
 *  Admin Notification Messages
 */

//Notify Admin On New Appointment
$booking_admin_subject = "[blog-name]: New appointment scheduled by [client-name].";
$booking_admin_body = "
Hi Admin,

Appointment details are:

Client Name: [client-name]
Client Email:[client-email]
Client Phone: [client-phone]
Client Special Instruction: [client-si]

Appointment For: [service-name]
Appointment With: [staff-name]
Appointment Date: [app-date]
Appointment Time: [app-time]
Appointment Status: [app-status]

View this appointment at admin dashboard.

Best Regards 
[blog-name].
";
add_option('booking_admin_subject', $booking_admin_subject);
add_option('booking_admin_body', $booking_admin_body);

//Notify Admin On Cancel Appointment
$cancel_admin_subject = "[blog-name]: Appointment scheduled by [client-name] has been [app-status].";
$cancel_admin_body = "
Hi Admin,

Appointment scheduled by [client-name] has been [app-status].

Appointment details are:

Client Name: [client-name]
Client Email:[client-email]
Client Phone: [client-phone]
Client Special Instruction: [client-si]

Appointment For: [service-name]
Appointment With: [staff-name]
Appointment Date: [app-date]
Appointment Time: [app-time]
Appointment Status: [app-status]

Review this appointment at admin dashboard.

Best Regards
[blog-name].
";
add_option('cancel_admin_subject', $cancel_admin_subject);
add_option('cancel_admin_body', $cancel_admin_body);


/**
 * Staff Notification Message
 */
//Notify Staff On New Appointment
$booking_staff_subject = "[blog-name]: New appointment scheduled by [client-name] with you.";
$booking_staff_body = "
Hi [staff-name],

An appointment for [service-name] on [app-date] at [app-time] scheduled by [client-name] with you.

Currently appointment status is [app-status].

Once admin approve/cancel this appointment, you will receive appointment confirmation mail.

Best Regards
[blog-name].
";
add_option('booking_staff_subject', $booking_staff_subject);
add_option('booking_staff_body', $booking_staff_body);


//Notify Staff On Approve Appointment
$approve_staff_subject = "[blog-name]: Scheduled appointment with you has been [app-status].";
$approve_staff_body = "
Hi [staff-name],

An appointment scheduled with you has been [app-status] by admin.

Appointment details are:

Client Name: [client-name]
Client Email:[client-email]
Client Phone: [client-phone]
Client Special Instruction: [client-si]

Appointment For: [service-name]
Appointment With: [staff-name]
Appointment Date: [app-date]
Appointment Time: [app-time]
Appointment Status: [app-status]

View this appointment at your dashboard.

Do not forgot this appointment.

Best Regards
[blog-name].
";
add_option('approve_staff_subject', $approve_staff_subject);
add_option('approve_staff_body', $approve_staff_body);


//Notify Staff On Cancel Appointment
$cancel_staff_subject = "[blog-name]: Scheduled appointment with you has been [app-status].";
$cancel_staff_body = "
Hi [staff-name],

Sorry! Due to some reason admin [app-status] appointment with you.

Your appointment with [client-name] for [service-name] on [app-date] at [app-time] has been [app-status] by admin.

Best Regards
[blog-name].
";
add_option('cancel_staff_subject', $cancel_staff_subject);
add_option('cancel_staff_body', $cancel_staff_body);



/**
 * Reminder Notification Messages
 */
//Client Reminder Message
$ap_reminder_subject = "[blog-name]: Appointment Reminder";
$ap_reminder_body = "
Hi [client-name],

Your appointment for [service-name] with [staff-name] on [app-date] at [app-time].

Your appointment status is [app-status].

We look forward to seeing you!

Best Regards 
[blog-name].
";

//Staff Reminder Message
$ap_staff_reminder_subject = "[blog-name]: Appointment Reminder";
$ap_staff_reminder_body = "
Hi [staff-name],

Your appointment with [client-name] for [service-name] on [app-date] at [app-time].

Best Regards
[blog-name].
";

$ReminderDetails = array( 
    'ap_reminder_status' => 'yes',
    'ap_reminder_type' => 'email',
    'ap_client_reminder' => 'yes',
    'ap_staff_reminder' => 'yes',
    'ap_reminder_frequency' => 1,
    'ap_reminder_subject' => $ap_reminder_subject,
    'ap_reminder_body' => $ap_reminder_body,
    'ap_staff_reminder_subject' => $ap_staff_reminder_subject,
    'ap_staff_reminder_body' => $ap_staff_reminder_body
);
add_option('ap_reminder_details',$ReminderDetails);





//1. create ap_appointments table
$AppointmentsTableName = $wpdb->prefix . "ap_appointments";
$AppointmentsTable_sql = "CREATE TABLE IF NOT EXISTS `$AppointmentsTableName` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(256) NOT NULL,
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `start_time` varchar(10) NOT NULL,
  `end_time` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `note` text NOT NULL,
  `appointment_key` text NOT NULL,
  `status` varchar(10) NOT NULL,
  `recurring` varchar(3) NOT NULL,
  `recurring_type` varchar(10) NOT NULL,
  `recurring_st_date` date NOT NULL,
  `recurring_ed_date` date NOT NULL,
  `appointment_by` varchar(10) NOT NULL,
  `payment_status` varchar(20),
  `book_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `extra_fields` TEXT NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($AppointmentsTable_sql); 


//2. create ap_events table
$EventTableName = $wpdb->prefix . "ap_events";
$EventTable_sql = "CREATE TABLE IF NOT EXISTS `$EventTableName` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR( 30 ) NOT NULL ,
  `allday` VARCHAR( 10 ) NOT NULL ,
  `start_time` VARCHAR( 10 ) NOT NULL ,
  `end_time` VARCHAR( 10 ) NOT NULL ,
  `repeat` VARCHAR( 10 ) NOT NULL ,
  `start_date` DATE NOT NULL ,
  `end_date` DATE NOT NULL ,
  `note` TEXT NOT NULL ,
  `status` VARCHAR( 10 ) NOT NULL,
  `staff_id` TEXT NOT NULL
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($EventTable_sql);



//3. create ap_services table
$ServiceTableName = $wpdb->prefix . "ap_services";
$ServiceTable_sql = "CREATE TABLE IF NOT EXISTS `$ServiceTableName` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR( 50 ) NOT NULL ,
  `desc` TEXT NOT NULL ,
  `duration` INT( 11 ) NOT NULL ,
  `unit` VARCHAR( 10 ) NOT NULL ,
  `paddingtime` INT( 11 ) NOT NULL ,
  `cost` FLOAT NOT NULL ,
  `capacity` INT( 11 ) NOT NULL ,
  `availability` VARCHAR( 10 ) NOT NULL ,
  `business_id` INT( 11 ) NOT NULL ,
  `category_id` INT( 11 ) NOT NULL ,
  `staff_id` TEXT NOT NULL ,
  `accept_payment` varchar(10),
  `payment_type` varchar(10),
  `percentage_ammount` float,
  `service_hours` TEXT NOT NULL
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ServiceTable_sql); 

    $StaffId = serialize(array('1' => '1'));    //default service staff
    //3.1 inserting 'Default' service
    $InsertService_sql = "INSERT INTO `$ServiceTableName` (
    `id` ,
    `name` ,
    `desc` ,
    `duration` ,
    `unit` ,
    `paddingtime`,
    `cost` ,
    `capacity`,
    `availability`,
    `business_id`,
    `category_id`,
    `staff_id`,
    `accept_payment`,
    `payment_type`,
    `percentage_ammount`
    )
    VALUES ('1', 'Default', 'This is default service. You can edit this service.', '30', 'minute', '10', '15', '5', 'yes', '1', '1', '$StaffId', 'no', '', '');";
    $wpdb->query($InsertService_sql);


//4. create ap_service_category table
$ServiceCategoryTableName = $wpdb->prefix . "ap_service_category";
$ServiceCategoryTable_sql = "CREATE TABLE IF NOT EXISTS `$ServiceCategoryTableName` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY , `name` VARCHAR( 100 ) NOT NULL )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ServiceCategoryTable_sql);

    //4.1 inserting a 'Default' service category
    $InsertServiceCategory_sql = "INSERT INTO `$ServiceCategoryTableName` ( `id` , `name` ) VALUES ( '1', 'Default' );";
    $wpdb->query($InsertServiceCategory_sql);


//5. create ap_calendar_settings table
$CalendarSettingsTableName = $wpdb->prefix . "ap_calendar_settings";
$CalendarSettingsTable_sql = "CREATE TABLE IF NOT EXISTS `$CalendarSettingsTableName` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY , `view` VARCHAR( 20 ) NOT NULL , `timeslots` INT( 11 ) NOT NULL )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($CalendarSettingsTable_sql);


//6. create ap_business table
$BusinessTableName = $wpdb->prefix . "ap_business";
$BusinessTable_sql = "CREATE TABLE IF NOT EXISTS `$BusinessTableName` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR( 50 ) NOT NULL ,
  `owener` VARCHAR( 50 ) NOT NULL ,
  `address` VARCHAR( 100 ) NOT NULL ,
  `city` VARCHAR( 50 ) NOT NULL ,
  `state` VARCHAR( 50 ) NOT NULL ,
  `zipcode` VARCHAR( 12 ) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `fax` VARCHAR( 30 ) NOT NULL ,
  `email` VARCHAR( 256 ) NOT NULL ,
  `website` VARCHAR( 256 ) NOT NULL ,
  `blog_url` VARCHAR( 256 ) NOT NULL
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($BusinessTable_sql); 


//7. create ap_business_hours table
$BusinessHoursTableName = $wpdb->prefix . "ap_business_hours";
$BusinessHoursTable_sql = "CREATE TABLE IF NOT EXISTS `$BusinessHoursTableName` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` varchar(10) NOT NULL,
  `start_time` varchar(8) NOT NULL,
  `end_time` varchar(8) NOT NULL,
  `total_hours` float NOT NULL,
  `close` varchar(3) NOT NULL,
  `halfday` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($BusinessHoursTable_sql);

    //7.1 insert default business_hours in ap_business_hours table
    $BusinessHoursTableInsert_sql = "INSERT INTO `$BusinessHoursTableName` (`id`, `day`, `start_time`, `end_time`, `total_hours`, `close`, `halfday`) VALUES
    (1, 'monday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (2, 'tuesday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (3, 'wednesday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (4, 'thursday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (5, 'friday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (6, 'saturday', '10:00 AM', '5:00 PM', 0, 'no', 'no'),
    (7, 'sunday', 'none', 'none', 0, 'yes', 'no');";
    $wpdb->query($BusinessHoursTableInsert_sql);


//8. create ap_clients table
$ClientsTableName = $wpdb->prefix . "ap_clients";
$ClientsTable_sql = "CREATE TABLE IF NOT EXISTS `$ClientsTableName` (
  `id` int( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar( 30 ) NOT NULL ,
  `email` varchar( 256 ) NOT NULL ,
  `phone` varchar(128) NOT NULL,
  `note` varchar( 160 ) NOT NULL 
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ClientsTable_sql);


//9. create staff table
$StaffTableName = $wpdb->prefix . "ap_staff";
$StaffTable_sql = "CREATE TABLE IF NOT EXISTS `$StaffTableName` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `experience` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `address` varchar(256) NOT NULL,
  `city` varchar(128) NOT NULL,
  `staff_hours` TEXT NOT NULL,
  `color` VARCHAR( 20 ) NOT NULL,
   `extra_fields` TEXT NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($StaffTable_sql);


    //9.1 insert default staff 'no preferance' in staff table
    $StaffTableInsert_sql = "INSERT INTO `$StaffTableName` (`id`, `name`, `email`, `phone`, `experience`, `group_id`, `address`, `city`, `color`) VALUES (1, 'No Preference', '$AdminEmail', 1111111111, 4, 1, 'my address', 'my city', '#1fcb4a');";
    $wpdb->query($StaffTableInsert_sql);


//10. create ap_staff_groups table
$StaffGroupTableName = $wpdb->prefix . "ap_staff_groups";
$StaffGroupTable_sql = "CREATE TABLE IF NOT EXISTS `$StaffGroupTableName` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`))DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($StaffGroupTable_sql);

    //10.1 insert default group in ap_staff_groups table
    $StaffGrouptInsert_sql = "INSERT INTO `$StaffGroupTableName` (`id`, `name`) VALUES (1, 'Default');";
    $wpdb->query($StaffGrouptInsert_sql);


//11. create  table ap_country
$CountryTableName = $wpdb->prefix . "ap_country";
$CountryTable_sql = "CREATE TABLE IF NOT EXISTS `$CountryTableName` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($CountryTable_sql);

    //11.1 insert country list in ap_country table
    $CountryTableInsert_sql = "INSERT INTO `$CountryTableName` (`country_id`, `iso2`, `short_name`, `long_name`, `iso3`, `numcode`, `un_member`, `calling_code`, `cctld`) VALUES
(1, 'AF', 'Afghanistan', 'Islamic Republic of Afghanistan', 'AFG', '004', 'yes', '93', '.af'),
(2, 'AX', 'Aland Islands', '&Aring;land Islands', 'ALA', '248', 'no', '358', '.ax'),
(3, 'AL', 'Albania', 'Republic of Albania', 'ALB', '008', 'yes', '355', '.al'),
(4, 'DZ', 'Algeria', 'People''s Democratic Republic of Algeria', 'DZA', '012', 'yes', '213', '.dz'),
(5, 'AS', 'American Samoa', 'American Samoa', 'ASM', '016', 'no', '1+684', '.as'),
(6, 'AD', 'Andorra', 'Principality of Andorra', 'AND', '020', 'yes', '376', '.ad'),
(7, 'AO', 'Angola', 'Republic of Angola', 'AGO', '024', 'yes', '244', '.ao'),
(8, 'AI', 'Anguilla', 'Anguilla', 'AIA', '660', 'no', '1+264', '.ai'),
(9, 'AQ', 'Antarctica', 'Antarctica', 'ATA', '010', 'no', '672', '.aq'),
(10, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', 'ATG', '028', 'yes', '1+268', '.ag'),
(11, 'AR', 'Argentina', 'Argentine Republic', 'ARG', '032', 'yes', '54', '.ar'),
(12, 'AM', 'Armenia', 'Republic of Armenia', 'ARM', '051', 'yes', '374', '.am'),
(13, 'AW', 'Aruba', 'Aruba', 'ABW', '533', 'no', '297', '.aw'),
(14, 'AU', 'Australia', 'Commonwealth of Australia', 'AUS', '036', 'yes', '61', '.au'),
(15, 'AT', 'Austria', 'Republic of Austria', 'AUT', '040', 'yes', '43', '.at'),
(16, 'AZ', 'Azerbaijan', 'Republic of Azerbaijan', 'AZE', '031', 'yes', '994', '.az'),
(17, 'BS', 'Bahamas', 'Commonwealth of The Bahamas', 'BHS', '044', 'yes', '1+242', '.bs'),
(18, 'BH', 'Bahrain', 'Kingdom of Bahrain', 'BHR', '048', 'yes', '973', '.bh'),
(19, 'BD', 'Bangladesh', 'People''s Republic of Bangladesh', 'BGD', '050', 'yes', '880', '.bd'),
(20, 'BB', 'Barbados', 'Barbados', 'BRB', '052', 'yes', '1+246', '.bb'),
(21, 'BY', 'Belarus', 'Republic of Belarus', 'BLR', '112', 'yes', '375', '.by'),
(22, 'BE', 'Belgium', 'Kingdom of Belgium', 'BEL', '056', 'yes', '32', '.be'),
(23, 'BZ', 'Belize', 'Belize', 'BLZ', '084', 'yes', '501', '.bz'),
(24, 'BJ', 'Benin', 'Republic of Benin', 'BEN', '204', 'yes', '229', '.bj'),
(25, 'BM', 'Bermuda', 'Bermuda Islands', 'BMU', '060', 'no', '1+441', '.bm'),
(26, 'BT', 'Bhutan', 'Kingdom of Bhutan', 'BTN', '064', 'yes', '975', '.bt'),
(27, 'BO', 'Bolivia', 'Plurinational State of Bolivia', 'BOL', '068', 'yes', '591', '.bo'),
(28, 'BQ', 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Sint Eustatius and Saba', 'BES', '535', 'no', '599', '.bq'),
(29, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', 'BIH', '070', 'yes', '387', '.ba'),
(30, 'BW', 'Botswana', 'Republic of Botswana', 'BWA', '072', 'yes', '267', '.bw'),
(31, 'BV', 'Bouvet Island', 'Bouvet Island', 'BVT', '074', 'no', 'NONE', '.bv'),
(32, 'BR', 'Brazil', 'Federative Republic of Brazil', 'BRA', '076', 'yes', '55', '.br'),
(33, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'IOT', '086', 'no', '246', '.io'),
(34, 'BN', 'Brunei', 'Brunei Darussalam', 'BRN', '096', 'yes', '673', '.bn'),
(35, 'BG', 'Bulgaria', 'Republic of Bulgaria', 'BGR', '100', 'yes', '359', '.bg'),
(36, 'BF', 'Burkina Faso', 'Burkina Faso', 'BFA', '854', 'yes', '226', '.bf'),
(37, 'BI', 'Burundi', 'Republic of Burundi', 'BDI', '108', 'yes', '257', '.bi'),
(38, 'KH', 'Cambodia', 'Kingdom of Cambodia', 'KHM', '116', 'yes', '855', '.kh'),
(39, 'CM', 'Cameroon', 'Republic of Cameroon', 'CMR', '120', 'yes', '237', '.cm'),
(40, 'CA', 'Canada', 'Canada', 'CAN', '124', 'yes', '1', '.ca'),
(41, 'CV', 'Cape Verde', 'Republic of Cape Verde', 'CPV', '132', 'yes', '238', '.cv'),
(42, 'KY', 'Cayman Islands', 'The Cayman Islands', 'CYM', '136', 'no', '1+345', '.ky'),
(43, 'CF', 'Central African Republic', 'Central African Republic', 'CAF', '140', 'yes', '236', '.cf'),
(44, 'TD', 'Chad', 'Republic of Chad', 'TCD', '148', 'yes', '235', '.td'),
(45, 'CL', 'Chile', 'Republic of Chile', 'CHL', '152', 'yes', '56', '.cl'),
(46, 'CN', 'China', 'People''s Republic of China', 'CHN', '156', 'yes', '86', '.cn'),
(47, 'CX', 'Christmas Island', 'Christmas Island', 'CXR', '162', 'no', '61', '.cx'),
(48, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CCK', '166', 'no', '61', '.cc'),
(49, 'CO', 'Colombia', 'Republic of Colombia', 'COL', '170', 'yes', '57', '.co'),
(50, 'KM', 'Comoros', 'Union of the Comoros', 'COM', '174', 'yes', '269', '.km'),
(51, 'CG', 'Congo', 'Republic of the Congo', 'COG', '178', 'yes', '242', '.cg'),
(52, 'CK', 'Cook Islands', 'Cook Islands', 'COK', '184', 'some', '682', '.ck'),
(53, 'CR', 'Costa Rica', 'Republic of Costa Rica', 'CRI', '188', 'yes', '506', '.cr'),
(54, 'CI', 'Cote d''ivoire (Ivory Coast)', 'Republic of C&ocirc;te D''Ivoire (Ivory Coast)', 'CIV', '384', 'yes', '225', '.ci'),
(55, 'HR', 'Croatia', 'Republic of Croatia', 'HRV', '191', 'yes', '385', '.hr'),
(56, 'CU', 'Cuba', 'Republic of Cuba', 'CUB', '192', 'yes', '53', '.cu'),
(57, 'CW', 'Curacao', 'Cura&ccedil;ao', 'CUW', '531', 'no', '599', '.cw'),
(58, 'CY', 'Cyprus', 'Republic of Cyprus', 'CYP', '196', 'yes', '357', '.cy'),
(59, 'CZ', 'Czech Republic', 'Czech Republic', 'CZE', '203', 'yes', '420', '.cz'),
(60, 'CD', 'Democratic Republic of the Congo', 'Democratic Republic of the Congo', 'COD', '180', 'yes', '243', '.cd'),
(61, 'DK', 'Denmark', 'Kingdom of Denmark', 'DNK', '208', 'yes', '45', '.dk'),
(62, 'DJ', 'Djibouti', 'Republic of Djibouti', 'DJI', '262', 'yes', '253', '.dj'),
(63, 'DM', 'Dominica', 'Commonwealth of Dominica', 'DMA', '212', 'yes', '1+767', '.dm'),
(64, 'DO', 'Dominican Republic', 'Dominican Republic', 'DOM', '214', 'yes', '1+809, 8', '.do'),
(65, 'EC', 'Ecuador', 'Republic of Ecuador', 'ECU', '218', 'yes', '593', '.ec'),
(66, 'EG', 'Egypt', 'Arab Republic of Egypt', 'EGY', '818', 'yes', '20', '.eg'),
(67, 'SV', 'El Salvador', 'Republic of El Salvador', 'SLV', '222', 'yes', '503', '.sv'),
(68, 'GQ', 'Equatorial Guinea', 'Republic of Equatorial Guinea', 'GNQ', '226', 'yes', '240', '.gq'),
(69, 'ER', 'Eritrea', 'State of Eritrea', 'ERI', '232', 'yes', '291', '.er'),
(70, 'EE', 'Estonia', 'Republic of Estonia', 'EST', '233', 'yes', '372', '.ee'),
(71, 'ET', 'Ethiopia', 'Federal Democratic Republic of Ethiopia', 'ETH', '231', 'yes', '251', '.et'),
(72, 'FK', 'Falkland Islands (Malvinas)', 'The Falkland Islands (Malvinas)', 'FLK', '238', 'no', '500', '.fk'),
(73, 'FO', 'Faroe Islands', 'The Faroe Islands', 'FRO', '234', 'no', '298', '.fo'),
(74, 'FJ', 'Fiji', 'Republic of Fiji', 'FJI', '242', 'yes', '679', '.fj'),
(75, 'FI', 'Finland', 'Republic of Finland', 'FIN', '246', 'yes', '358', '.fi'),
(76, 'FR', 'France', 'French Republic', 'FRA', '250', 'yes', '33', '.fr'),
(77, 'GF', 'French Guiana', 'French Guiana', 'GUF', '254', 'no', '594', '.gf'),
(78, 'PF', 'French Polynesia', 'French Polynesia', 'PYF', '258', 'no', '689', '.pf'),
(79, 'TF', 'French Southern Territories', 'French Southern Territories', 'ATF', '260', 'no', NULL, '.tf'),
(80, 'GA', 'Gabon', 'Gabonese Republic', 'GAB', '266', 'yes', '241', '.ga'),
(81, 'GM', 'Gambia', 'Republic of The Gambia', 'GMB', '270', 'yes', '220', '.gm'),
(82, 'GE', 'Georgia', 'Georgia', 'GEO', '268', 'yes', '995', '.ge'),
(83, 'DE', 'Germany', 'Federal Republic of Germany', 'DEU', '276', 'yes', '49', '.de'),
(84, 'GH', 'Ghana', 'Republic of Ghana', 'GHA', '288', 'yes', '233', '.gh'),
(85, 'GI', 'Gibraltar', 'Gibraltar', 'GIB', '292', 'no', '350', '.gi'),
(86, 'GR', 'Greece', 'Hellenic Republic', 'GRC', '300', 'yes', '30', '.gr'),
(87, 'GL', 'Greenland', 'Greenland', 'GRL', '304', 'no', '299', '.gl'),
(88, 'GD', 'Grenada', 'Grenada', 'GRD', '308', 'yes', '1+473', '.gd'),
(89, 'GP', 'Guadaloupe', 'Guadeloupe', 'GLP', '312', 'no', '590', '.gp'),
(90, 'GU', 'Guam', 'Guam', 'GUM', '316', 'no', '1+671', '.gu'),
(91, 'GT', 'Guatemala', 'Republic of Guatemala', 'GTM', '320', 'yes', '502', '.gt'),
(92, 'GG', 'Guernsey', 'Guernsey', 'GGY', '831', 'no', '44', '.gg'),
(93, 'GN', 'Guinea', 'Republic of Guinea', 'GIN', '324', 'yes', '224', '.gn'),
(94, 'GW', 'Guinea-Bissau', 'Republic of Guinea-Bissau', 'GNB', '624', 'yes', '245', '.gw'),
(95, 'GY', 'Guyana', 'Co-operative Republic of Guyana', 'GUY', '328', 'yes', '592', '.gy'),
(96, 'HT', 'Haiti', 'Republic of Haiti', 'HTI', '332', 'yes', '509', '.ht'),
(97, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', 'HMD', '334', 'no', 'NONE', '.hm'),
(98, 'HN', 'Honduras', 'Republic of Honduras', 'HND', '340', 'yes', '504', '.hn'),
(99, 'HK', 'Hong Kong', 'Hong Kong', 'HKG', '344', 'no', '852', '.hk'),
(100, 'HU', 'Hungary', 'Hungary', 'HUN', '348', 'yes', '36', '.hu'),
(101, 'IS', 'Iceland', 'Republic of Iceland', 'ISL', '352', 'yes', '354', '.is'),
(102, 'IN', 'India', 'Republic of India', 'IND', '356', 'yes', '91', '.in'),
(103, 'ID', 'Indonesia', 'Republic of Indonesia', 'IDN', '360', 'yes', '62', '.id'),
(104, 'IR', 'Iran', 'Islamic Republic of Iran', 'IRN', '364', 'yes', '98', '.ir'),
(105, 'IQ', 'Iraq', 'Republic of Iraq', 'IRQ', '368', 'yes', '964', '.iq'),
(106, 'IE', 'Ireland', 'Ireland', 'IRL', '372', 'yes', '353', '.ie'),
(107, 'IM', 'Isle of Man', 'Isle of Man', 'IMN', '833', 'no', '44', '.im'),
(108, 'IL', 'Israel', 'State of Israel', 'ISR', '376', 'yes', '972', '.il'),
(109, 'IT', 'Italy', 'Italian Republic', 'ITA', '380', 'yes', '39', '.jm'),
(110, 'JM', 'Jamaica', 'Jamaica', 'JAM', '388', 'yes', '1+876', '.jm'),
(111, 'JP', 'Japan', 'Japan', 'JPN', '392', 'yes', '81', '.jp'),
(112, 'JE', 'Jersey', 'The Bailiwick of Jersey', 'JEY', '832', 'no', '44', '.je'),
(113, 'JO', 'Jordan', 'Hashemite Kingdom of Jordan', 'JOR', '400', 'yes', '962', '.jo'),
(114, 'KZ', 'Kazakhstan', 'Republic of Kazakhstan', 'KAZ', '398', 'yes', '7', '.kz'),
(115, 'KE', 'Kenya', 'Republic of Kenya', 'KEN', '404', 'yes', '254', '.ke'),
(116, 'KI', 'Kiribati', 'Republic of Kiribati', 'KIR', '296', 'yes', '686', '.ki'),
(117, 'XK', 'Kosovo', 'Republic of Kosovo', '---', '---', 'some', '381', ''),
(118, 'KW', 'Kuwait', 'State of Kuwait', 'KWT', '414', 'yes', '965', '.kw'),
(119, 'KG', 'Kyrgyzstan', 'Kyrgyz Republic', 'KGZ', '417', 'yes', '996', '.kg'),
(120, 'LA', 'Laos', 'Lao People''s Democratic Republic', 'LAO', '418', 'yes', '856', '.la'),
(121, 'LV', 'Latvia', 'Republic of Latvia', 'LVA', '428', 'yes', '371', '.lv'),
(122, 'LB', 'Lebanon', 'Republic of Lebanon', 'LBN', '422', 'yes', '961', '.lb'),
(123, 'LS', 'Lesotho', 'Kingdom of Lesotho', 'LSO', '426', 'yes', '266', '.ls'),
(124, 'LR', 'Liberia', 'Republic of Liberia', 'LBR', '430', 'yes', '231', '.lr'),
(125, 'LY', 'Libya', 'Libya', 'LBY', '434', 'yes', '218', '.ly'),
(126, 'LI', 'Liechtenstein', 'Principality of Liechtenstein', 'LIE', '438', 'yes', '423', '.li'),
(127, 'LT', 'Lithuania', 'Republic of Lithuania', 'LTU', '440', 'yes', '370', '.lt'),
(128, 'LU', 'Luxembourg', 'Grand Duchy of Luxembourg', 'LUX', '442', 'yes', '352', '.lu'),
(129, 'MO', 'Macao', 'The Macao Special Administrative Region', 'MAC', '446', 'no', '853', '.mo'),
(130, 'MK', 'Macedonia', 'The Former Yugoslav Republic of Macedonia', 'MKD', '807', 'yes', '389', '.mk'),
(131, 'MG', 'Madagascar', 'Republic of Madagascar', 'MDG', '450', 'yes', '261', '.mg'),
(132, 'MW', 'Malawi', 'Republic of Malawi', 'MWI', '454', 'yes', '265', '.mw'),
(133, 'MY', 'Malaysia', 'Malaysia', 'MYS', '458', 'yes', '60', '.my'),
(134, 'MV', 'Maldives', 'Republic of Maldives', 'MDV', '462', 'yes', '960', '.mv'),
(135, 'ML', 'Mali', 'Republic of Mali', 'MLI', '466', 'yes', '223', '.ml'),
(136, 'MT', 'Malta', 'Republic of Malta', 'MLT', '470', 'yes', '356', '.mt'),
(137, 'MH', 'Marshall Islands', 'Republic of the Marshall Islands', 'MHL', '584', 'yes', '692', '.mh'),
(138, 'MQ', 'Martinique', 'Martinique', 'MTQ', '474', 'no', '596', '.mq'),
(139, 'MR', 'Mauritania', 'Islamic Republic of Mauritania', 'MRT', '478', 'yes', '222', '.mr'),
(140, 'MU', 'Mauritius', 'Republic of Mauritius', 'MUS', '480', 'yes', '230', '.mu'),
(141, 'YT', 'Mayotte', 'Mayotte', 'MYT', '175', 'no', '262', '.yt'),
(142, 'MX', 'Mexico', 'United Mexican States', 'MEX', '484', 'yes', '52', '.mx'),
(143, 'FM', 'Micronesia', 'Federated States of Micronesia', 'FSM', '583', 'yes', '691', '.fm'),
(144, 'MD', 'Moldava', 'Republic of Moldova', 'MDA', '498', 'yes', '373', '.md'),
(145, 'MC', 'Monaco', 'Principality of Monaco', 'MCO', '492', 'yes', '377', '.mc'),
(146, 'MN', 'Mongolia', 'Mongolia', 'MNG', '496', 'yes', '976', '.mn'),
(147, 'ME', 'Montenegro', 'Montenegro', 'MNE', '499', 'yes', '382', '.me'),
(148, 'MS', 'Montserrat', 'Montserrat', 'MSR', '500', 'no', '1+664', '.ms'),
(149, 'MA', 'Morocco', 'Kingdom of Morocco', 'MAR', '504', 'yes', '212', '.ma'),
(150, 'MZ', 'Mozambique', 'Republic of Mozambique', 'MOZ', '508', 'yes', '258', '.mz'),
(151, 'MM', 'Myanmar (Burma)', 'Republic of the Union of Myanmar', 'MMR', '104', 'yes', '95', '.mm'),
(152, 'NA', 'Namibia', 'Republic of Namibia', 'NAM', '516', 'yes', '264', '.na'),
(153, 'NR', 'Nauru', 'Republic of Nauru', 'NRU', '520', 'yes', '674', '.nr'),
(154, 'NP', 'Nepal', 'Federal Democratic Republic of Nepal', 'NPL', '524', 'yes', '977', '.np'),
(155, 'NL', 'Netherlands', 'Kingdom of the Netherlands', 'NLD', '528', 'yes', '31', '.nl'),
(156, 'NC', 'New Caledonia', 'New Caledonia', 'NCL', '540', 'no', '687', '.nc'),
(157, 'NZ', 'New Zealand', 'New Zealand', 'NZL', '554', 'yes', '64', '.nz'),
(158, 'NI', 'Nicaragua', 'Republic of Nicaragua', 'NIC', '558', 'yes', '505', '.ni'),
(159, 'NE', 'Niger', 'Republic of Niger', 'NER', '562', 'yes', '227', '.ne'),
(160, 'NG', 'Nigeria', 'Federal Republic of Nigeria', 'NGA', '566', 'yes', '234', '.ng'),
(161, 'NU', 'Niue', 'Niue', 'NIU', '570', 'some', '683', '.nu'),
(162, 'NF', 'Norfolk Island', 'Norfolk Island', 'NFK', '574', 'no', '672', '.nf'),
(163, 'KP', 'North Korea', 'Democratic People''s Republic of Korea', 'PRK', '408', 'yes', '850', '.kp'),
(164, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', 'MNP', '580', 'no', '1+670', '.mp'),
(165, 'NO', 'Norway', 'Kingdom of Norway', 'NOR', '578', 'yes', '47', '.no'),
(166, 'OM', 'Oman', 'Sultanate of Oman', 'OMN', '512', 'yes', '968', '.om'),
(167, 'PK', 'Pakistan', 'Islamic Republic of Pakistan', 'PAK', '586', 'yes', '92', '.pk'),
(168, 'PW', 'Palau', 'Republic of Palau', 'PLW', '585', 'yes', '680', '.pw'),
(169, 'PS', 'Palestine', 'State of Palestine (or Occupied Palestinian Territory)', 'PSE', '275', 'some', '970', '.ps'),
(170, 'PA', 'Panama', 'Republic of Panama', 'PAN', '591', 'yes', '507', '.pa'),
(171, 'PG', 'Papua New Guinea', 'Independent State of Papua New Guinea', 'PNG', '598', 'yes', '675', '.pg'),
(172, 'PY', 'Paraguay', 'Republic of Paraguay', 'PRY', '600', 'yes', '595', '.py'),
(173, 'PE', 'Peru', 'Republic of Peru', 'PER', '604', 'yes', '51', '.pe'),
(174, 'PH', 'Phillipines', 'Republic of the Philippines', 'PHL', '608', 'yes', '63', '.ph'),
(175, 'PN', 'Pitcairn', 'Pitcairn', 'PCN', '612', 'no', 'NONE', '.pn'),
(176, 'PL', 'Poland', 'Republic of Poland', 'POL', '616', 'yes', '48', '.pl'),
(177, 'PT', 'Portugal', 'Portuguese Republic', 'PRT', '620', 'yes', '351', '.pt'),
(178, 'PR', 'Puerto Rico', 'Commonwealth of Puerto Rico', 'PRI', '630', 'no', '1+939', '.pr'),
(179, 'QA', 'Qatar', 'State of Qatar', 'QAT', '634', 'yes', '974', '.qa'),
(180, 'RE', 'Reunion', 'R&eacute;union', 'REU', '638', 'no', '262', '.re'),
(181, 'RO', 'Romania', 'Romania', 'ROU', '642', 'yes', '40', '.ro'),
(182, 'RU', 'Russia', 'Russian Federation', 'RUS', '643', 'yes', '7', '.ru'),
(183, 'RW', 'Rwanda', 'Republic of Rwanda', 'RWA', '646', 'yes', '250', '.rw'),
(184, 'BL', 'Saint Barthelemy', 'Saint Barth&eacute;lemy', 'BLM', '652', 'no', '590', '.bl'),
(185, 'SH', 'Saint Helena', 'Saint Helena, Ascension and Tristan da Cunha', 'SHN', '654', 'no', '290', '.sh'),
(186, 'KN', 'Saint Kitts and Nevis', 'Federation of Saint Christopher and Nevis', 'KNA', '659', 'yes', '1+869', '.kn'),
(187, 'LC', 'Saint Lucia', 'Saint Lucia', 'LCA', '662', 'yes', '1+758', '.lc'),
(188, 'MF', 'Saint Martin', 'Saint Martin', 'MAF', '663', 'no', '590', '.mf'),
(189, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'SPM', '666', 'no', '508', '.pm'),
(190, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'VCT', '670', 'yes', '1+784', '.vc'),
(191, 'WS', 'Samoa', 'Independent State of Samoa', 'WSM', '882', 'yes', '685', '.ws'),
(192, 'SM', 'San Marino', 'Republic of San Marino', 'SMR', '674', 'yes', '378', '.sm'),
(193, 'ST', 'Sao Tome and Principe', 'Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'STP', '678', 'yes', '239', '.st'),
(194, 'SA', 'Saudi Arabia', 'Kingdom of Saudi Arabia', 'SAU', '682', 'yes', '966', '.sa'),
(195, 'SN', 'Senegal', 'Republic of Senegal', 'SEN', '686', 'yes', '221', '.sn'),
(196, 'RS', 'Serbia', 'Republic of Serbia', 'SRB', '688', 'yes', '381', '.rs'),
(197, 'SC', 'Seychelles', 'Republic of Seychelles', 'SYC', '690', 'yes', '248', '.sc'),
(198, 'SL', 'Sierra Leone', 'Republic of Sierra Leone', 'SLE', '694', 'yes', '232', '.sl'),
(199, 'SG', 'Singapore', 'Republic of Singapore', 'SGP', '702', 'yes', '65', '.sg'),
(200, 'SX', 'Sint Maarten', 'Sint Maarten', 'SXM', '534', 'no', '1+721', '.sx'),
(201, 'SK', 'Slovakia', 'Slovak Republic', 'SVK', '703', 'yes', '421', '.sk'),
(202, 'SI', 'Slovenia', 'Republic of Slovenia', 'SVN', '705', 'yes', '386', '.si'),
(203, 'SB', 'Solomon Islands', 'Solomon Islands', 'SLB', '090', 'yes', '677', '.sb'),
(204, 'SO', 'Somalia', 'Somali Republic', 'SOM', '706', 'yes', '252', '.so'),
(205, 'ZA', 'South Africa', 'Republic of South Africa', 'ZAF', '710', 'yes', '27', '.za'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'SGS', '239', 'no', '500', '.gs'),
(207, 'KR', 'South Korea', 'Republic of Korea', 'KOR', '410', 'yes', '82', '.kr'),
(208, 'SS', 'South Sudan', 'Republic of South Sudan', 'SSD', '728', 'yes', '211', '.ss'),
(209, 'ES', 'Spain', 'Kingdom of Spain', 'ESP', '724', 'yes', '34', '.es'),
(210, 'LK', 'Sri Lanka', 'Democratic Socialist Republic of Sri Lanka', 'LKA', '144', 'yes', '94', '.lk'),
(211, 'SD', 'Sudan', 'Republic of the Sudan', 'SDN', '729', 'yes', '249', '.sd'),
(212, 'SR', 'Suriname', 'Republic of Suriname', 'SUR', '740', 'yes', '597', '.sr'),
(213, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'SJM', '744', 'no', '47', '.sj'),
(214, 'SZ', 'Swaziland', 'Kingdom of Swaziland', 'SWZ', '748', 'yes', '268', '.sz'),
(215, 'SE', 'Sweden', 'Kingdom of Sweden', 'SWE', '752', 'yes', '46', '.se'),
(216, 'CH', 'Switzerland', 'Swiss Confederation', 'CHE', '756', 'yes', '41', '.ch'),
(217, 'SY', 'Syria', 'Syrian Arab Republic', 'SYR', '760', 'yes', '963', '.sy'),
(218, 'TW', 'Taiwan', 'Republic of China (Taiwan)', 'TWN', '158', 'former', '886', '.tw'),
(219, 'TJ', 'Tajikistan', 'Republic of Tajikistan', 'TJK', '762', 'yes', '992', '.tj'),
(220, 'TZ', 'Tanzania', 'United Republic of Tanzania', 'TZA', '834', 'yes', '255', '.tz'),
(221, 'TH', 'Thailand', 'Kingdom of Thailand', 'THA', '764', 'yes', '66', '.th'),
(222, 'TL', 'Timor-Leste (East Timor)', 'Democratic Republic of Timor-Leste', 'TLS', '626', 'yes', '670', '.tl'),
(223, 'TG', 'Togo', 'Togolese Republic', 'TGO', '768', 'yes', '228', '.tg'),
(224, 'TK', 'Tokelau', 'Tokelau', 'TKL', '772', 'no', '690', '.tk'),
(225, 'TO', 'Tonga', 'Kingdom of Tonga', 'TON', '776', 'yes', '676', '.to'),
(226, 'TT', 'Trinidad and Tobago', 'Republic of Trinidad and Tobago', 'TTO', '780', 'yes', '1+868', '.tt'),
(227, 'TN', 'Tunisia', 'Republic of Tunisia', 'TUN', '788', 'yes', '216', '.tn'),
(228, 'TR', 'Turkey', 'Republic of Turkey', 'TUR', '792', 'yes', '90', '.tr'),
(229, 'TM', 'Turkmenistan', 'Turkmenistan', 'TKM', '795', 'yes', '993', '.tm'),
(230, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'TCA', '796', 'no', '1+649', '.tc'),
(231, 'TV', 'Tuvalu', 'Tuvalu', 'TUV', '798', 'yes', '688', '.tv'),
(232, 'UG', 'Uganda', 'Republic of Uganda', 'UGA', '800', 'yes', '256', '.ug'),
(233, 'UA', 'Ukraine', 'Ukraine', 'UKR', '804', 'yes', '380', '.ua'),
(234, 'AE', 'United Arab Emirates', 'United Arab Emirates', 'ARE', '784', 'yes', '971', '.ae'),
(235, 'GB', 'United Kingdom', 'United Kingdom of Great Britain and Nothern Ireland', 'GBR', '826', 'yes', '44', '.uk'),
(236, 'US', 'United States', 'United States of America', 'USA', '840', 'yes', '1', '.us'),
(237, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UMI', '581', 'no', 'NONE', 'NONE'),
(238, 'UY', 'Uruguay', 'Eastern Republic of Uruguay', 'URY', '858', 'yes', '598', '.uy'),
(239, 'UZ', 'Uzbekistan', 'Republic of Uzbekistan', 'UZB', '860', 'yes', '998', '.uz'),
(240, 'VU', 'Vanuatu', 'Republic of Vanuatu', 'VUT', '548', 'yes', '678', '.vu'),
(241, 'VA', 'Vatican City', 'State of the Vatican City', 'VAT', '336', 'no', '39', '.va'),
(242, 'VE', 'Venezuela', 'Bolivarian Republic of Venezuela', 'VEN', '862', 'yes', '58', '.ve'),
(243, 'VN', 'Vietnam', 'Socialist Republic of Vietnam', 'VNM', '704', 'yes', '84', '.vn'),
(244, 'VG', 'Virgin Islands, British', 'British Virgin Islands', 'VGB', '092', 'no', '1+284', '.vg'),
(245, 'VI', 'Virgin Islands, US', 'Virgin Islands of the United States', 'VIR', '850', 'no', '1+340', '.vi'),
(246, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', 'WLF', '876', 'no', '681', '.wf'),
(247, 'EH', 'Western Sahara', 'Western Sahara', 'ESH', '732', 'no', '212', '.eh'),
(248, 'YE', 'Yemen', 'Republic of Yemen', 'YEM', '887', 'yes', '967', '.ye'),
(249, 'ZM', 'Zambia', 'Republic of Zambia', 'ZMB', '894', 'yes', '260', '.zm'),
(250, 'ZW', 'Zimbabwe', 'Republic of Zimbabwe', 'ZWE', '716', 'yes', '263', '.zw');
";
    $wpdb->query($CountryTableInsert_sql);



//12. create  table ap_currency
$CurrencyTableName = $wpdb->prefix . "ap_currency";
$CurrencyTable_sql = "CREATE TABLE IF NOT EXISTS `$CurrencyTableName` ( `id` int(11) NOT NULL AUTO_INCREMENT, `currency_name` varchar(30) NOT NULL, `code` varchar(3) NOT NULL, `symbol` varchar(20) NOT NULL, PRIMARY KEY (`id`) )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($CurrencyTable_sql);

    //12.1 insert currency list in ap_currency table
    $CurrencyTableInsert_sql = "INSERT INTO `$CurrencyTableName` (`id`, `currency_name`, `code`, `symbol`) VALUES
(1, 'United States dollar', 'USD', '&#36;'),
(2, 'Euro', 'EUR', '&euro;'),
(3, 'Japanese yen', 'JPY', '&yen;'),
(4, 'British pound', 'GBP', '&#163;'),
(5, 'Australian dollar', 'AUD', '&#36;'),
(6, 'Swiss franc', 'CHF', 'Fr'),
(7, 'Canadian dollar', 'CAD', '&#36;'),
(8, 'Hong Kong dollar', 'HKD', '&#36;'),
(9, 'Swedish krona', 'SEK', 'Kr'),
(10, 'New Zealand dollar', 'NZD', '&#36;'),
(11, 'Singapore dollar', 'SGD', '&#36;'),
(12, 'Norwegian krone', 'NOK', 'kr'),
(13, 'Mexican peso', 'MXN', '&#36;'),
(14, 'Indian rupee', 'INR', 'INR'),
(15, 'Brazilian real', 'BRL', 'R$'),
(16, 'Israeli new shekel', 'NIS', 'NIS'),
(17, 'Czech koruna', 'CZK', 'Kc'),
(18, 'Malaysian ringgit', 'MYR', 'RM'),
(19, 'Philippine peso', 'PHP', 'PHP'),
(20, 'New Taiwan dollar', 'TWD', 'NT$'),
(21, 'Thai baht', 'THB', 'THB'),
(22, 'Turkish lira', 'TL', 't');
";
    $wpdb->query($CurrencyTableInsert_sql);


//13. create  table ap_timezones
$TimeZoneTableName = $wpdb->prefix . "ap_timezones";
$TimeZoneTable_sql = "CREATE TABLE IF NOT EXISTS `$TimeZoneTableName` ( `id` int(11) NOT NULL AUTO_INCREMENT, `GMT` varchar(5) COLLATE utf8_bin NOT NULL, `name` varchar(120) COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`) )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($TimeZoneTable_sql);

    //13.1 insert timezone list in ap_timezones table
    $TimeZoneTableInsert_sql = "INSERT INTO `$TimeZoneTableName` (`id`, `GMT`, `name`) VALUES
(1, '-12.0', '(GMT-12:00)-International Date Line West'),
(2, '-11.0', '(GMT-11:00)-Midway Island, Samoa'),
(3, '-10.0', '(GMT-10:00)-Hawaii'),
(4, '-9.0', '(GMT-09:00)-Alaska'),
(5, '-8.0', '(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),
(6, '-7.0', '(GMT-07:00)-Arizona'),
(7, '-7.0', '(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),
(8, '-7.0', '(GMT-07:00)-Mountain Time (US & Canada)'),
(9, '-6.0', '(GMT-06:00)-Central America'),
(10, '-6.0', '(GMT-06:00)-Central Time (US & Canada)'),
(11, '-6.0', '(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),
(12, '-6.0', '(GMT-06:00)-Saskatchewan'),
(13, '-5.0', '(GMT-05:00)-Bogota, Lima, Quito'),
(14, '-5.0', '(GMT-05:00)-Eastern Time (US & Canada)'),
(15, '-5.0', '(GMT-05:00)-Indiana (East)'),
(16, '-4.0', '(GMT-04:00)-Atlantic Time (Canada)'),
(17, '-4.0', '(GMT-04:00)-Caracas, La Paz'),
(18, '-4.0', '(GMT-04:00)-Santiago'),
(19, '-3.5', '(GMT-03:30)-Newfoundland'),
(20, '-3.0', '(GMT-03:00)-Brasilia'),
(21, '-3.0', '(GMT-03:00)-Buenos Aires, Georgetown'),
(22, '-3.0', '(GMT-03:00)-Greenland'),
(23, '-2.0', '(GMT-02:00)-Mid-Atlantic'),
(24, '-1.0', '(GMT-01:00)-Azores'),
(25, '-1.0', '(GMT-01:00)-Cape Verde Is.'),
(26, '0.0', '(GMT)-Casablanca, Monrovia'),
(27, '0.0', '(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),
(28, '1.0', '(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(29, '1.0', '(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(30, '1.0', '(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),
(31, '1.0', '(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),
(32, '1.0', '(GMT+01:00)-West Central Africa'),
(33, '2.0', '(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),
(34, '2.0', '(GMT+02:00)-Bucharest'),
(35, '2.0', '(GMT+02:00)-Cairo'),
(36, '2.0', '(GMT+02:00)-Harare, Pretoria'),
(37, '2.0', '(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
(38, '2.0', '(GMT+02:00)-Jerusalem'),
(39, '3.0', '(GMT+03:00)-Baghdad'),
(40, '3.0', '(GMT+03:00)-Kuwait, Riyadh'),
(41, '3.0', '(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),
(42, '3.0', '(GMT+03:00)-Nairobi'),
(43, '3.5', '(GMT+03:30)-Tehran'),
(44, '4.0', '(GMT+04:00)-Abu Dhabi, Muscat'),
(45, '4.0', '(GMT+04:00)-Baku, Tbilisi, Yerevan'),
(46, '4.5', '(GMT+04:30)-Kabul'),
(47, '5.0', '(GMT+05:00)-Ekaterinburg'),
(48, '5.0', '(GMT+05:00)-Islamabad, Karachi, Tashkent'),
(49, '5.5', '(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),
(50, '5.75', '(GMT+05:45)-Kathmandu'),
(51, '6.0', '(GMT+06:00)-Almaty, Novosibirsk'),
(52, '6.0', '(GMT+06:00)-Astana, Dhaka'),
(53, '6.0', '(GMT+06:00)-Sri Jayawardenepura'),
(54, '6.5', '(GMT+06:30)-Rangoon'),
(55, '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta'),
(56, '7.0', '(GMT+07:00)-Krasnoyarsk'),
(57, '8.0', '(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),
(58, '8.0', '(GMT+08:00)-Irkutsk, Ulaan Bataar'),
(59, '8.0', '(GMT+08:00)-Kuala Lumpur, Singapore'),
(60, '8.0', '(GMT+08:00)-Perth'),
(61, '8.0', '(GMT+08:00)-Taipei'),
(62, '9.0', '(GMT+09:00)-Osaka, Sapporo, Tokyo'),
(63, '9.0', '(GMT+09:00)-Seoul'),
(64, '9.0', '(GMT+09:00)-Vakutsk'),
(65, '9.5', '(GMT+09:30)-Adelaide'),
(66, '9.5', '(GMT+09:30)-Darwin'),
(67, '10.0', '(GMT+10:00)-Brisbane'),
(68, '10.0', '(GMT+10:00)-Canberra, Melbourne, Sydney'),
(69, '10.0', '(GMT+10:00)-Guam, Port Moresby'),
(70, '10.0', '(GMT+10:00)-Hobart'),
(71, '10.0', '(GMT+10:00)-Vladivostok'),
(72, '11.0', '(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),
(73, '12.0', '(GMT+12:00)-Auckland, Wellington'),
(74, '12.0', '(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),
(75, '-12.0', '(GMT-12:00)-International Date Line West'),
(76, '-11.0', '(GMT-11:00)-Midway Island, Samoa'),
(77, '-10.0', '(GMT-10:00)-Hawaii'),
(78, '-9.0', '(GMT-09:00)-Alaska'),
(79, '-8.0', '(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),
(80, '-7.0', '(GMT-07:00)-Arizona'),
(81, '-7.0', '(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),
(82, '-7.0', '(GMT-07:00)-Mountain Time (US & Canada)'),
(83, '-6.0', '(GMT-06:00)-Central America'),
(84, '-6.0', '(GMT-06:00)-Central Time (US & Canada)'),
(85, '-6.0', '(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),
(86, '-6.0', '(GMT-06:00)-Saskatchewan'),
(87, '-5.0', '(GMT-05:00)-Bogota, Lima, Quito'),
(88, '-5.0', '(GMT-05:00)-Eastern Time (US & Canada)'),
(89, '-5.0', '(GMT-05:00)-Indiana (East)'),
(90, '-4.0', '(GMT-04:00)-Atlantic Time (Canada)'),
(91, '-4.0', '(GMT-04:00)-Caracas, La Paz'),
(92, '-4.0', '(GMT-04:00)-Santiago'),
(93, '-3.5', '(GMT-03:30)-Newfoundland'),
(94, '-3.0', '(GMT-03:00)-Brasilia'),
(95, '-3.0', '(GMT-03:00)-Buenos Aires, Georgetown'),
(96, '-3.0', '(GMT-03:00)-Greenland'),
(97, '-2.0', '(GMT-02:00)-Mid-Atlantic'),
(98, '-1.0', '(GMT-01:00)-Azores'),
(99, '-1.0', '(GMT-01:00)-Cape Verde Is.'),
(100, '0.0', '(GMT)-Casablanca, Monrovia'),
(101, '0.0', '(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),
(102, '1.0', '(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(103, '1.0', '(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(104, '1.0', '(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),
(105, '1.0', '(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),
(106, '1.0', '(GMT+01:00)-West Central Africa'),
(107, '2.0', '(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),
(108, '2.0', '(GMT+02:00)-Bucharest'),
(109, '2.0', '(GMT+02:00)-Cairo'),
(110, '2.0', '(GMT+02:00)-Harare, Pretoria'),
(111, '2.0', '(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
(112, '2.0', '(GMT+02:00)-Jerusalem'),
(113, '3.0', '(GMT+03:00)-Baghdad'),
(114, '3.0', '(GMT+03:00)-Kuwait, Riyadh'),
(115, '3.0', '(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),
(116, '3.0', '(GMT+03:00)-Nairobi'),
(117, '3.5', '(GMT+03:30)-Tehran'),
(118, '4.0', '(GMT+04:00)-Abu Dhabi, Muscat'),
(119, '4.0', '(GMT+04:00)-Baku, Tbilisi, Yerevan'),
(120, '4.5', '(GMT+04:30)-Kabul'),
(121, '5.0', '(GMT+05:00)-Ekaterinburg'),
(122, '5.0', '(GMT+05:00)-Islamabad, Karachi, Tashkent'),
(123, '5.5', '(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),
(124, '5.75', '(GMT+05:45)-Kathmandu'),
(125, '6.0', '(GMT+06:00)-Almaty, Novosibirsk'),
(126, '6.0', '(GMT+06:00)-Astana, Dhaka'),
(127, '6.0', '(GMT+06:00)-Sri Jayawardenepura'),
(128, '6.5', '(GMT+06:30)-Rangoon'),
(129, '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta'),
(130, '7.0', '(GMT+07:00)-Krasnoyarsk'),
(131, '8.0', '(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),
(132, '8.0', '(GMT+08:00)-Irkutsk, Ulaan Bataar'),
(133, '8.0', '(GMT+08:00)-Kuala Lumpur, Singapore'),
(134, '8.0', '(GMT+08:00)-Perth'),
(135, '8.0', '(GMT+08:00)-Taipei'),
(136, '9.0', '(GMT+09:00)-Osaka, Sapporo, Tokyo'),
(137, '9.0', '(GMT+09:00)-Seoul'),
(138, '9.0', '(GMT+09:00)-Vakutsk'),
(139, '9.5', '(GMT+09:30)-Adelaide'),
(140, '9.5', '(GMT+09:30)-Darwin'),
(141, '10.0', '(GMT+10:00)-Brisbane'),
(142, '10.0', '(GMT+10:00)-Canberra, Melbourne, Sydney'),
(143, '10.0', '(GMT+10:00)-Guam, Port Moresby'),
(144, '10.0', '(GMT+10:00)-Hobart'),
(145, '10.0', '(GMT+10:00)-Vladivostok'),
(146, '11.0', '(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),
(147, '12.0', '(GMT+12:00)-Auckland, Wellington'),
(148, '12.0', '(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),
(149, '13.0', '(GMT+13:00)-Nuku''alofa ');
";
$wpdb->query($TimeZoneTableInsert_sql);



//14. create  table ap_languages
$LanguagesTableName = $wpdb->prefix . "ap_languages";
$LanguagesTable_sql = "CREATE TABLE IF NOT EXISTS `$LanguagesTableName` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `code` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `image` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `directory` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `filename` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($LanguagesTable_sql);

    //14.1 insert currency list in ap_currency table
    $LanguagesTableInsert_sql = "INSERT INTO `wp_ap_languages` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) VALUES (1, 'English', 'en', 'en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 'english', 1, 1);";
    $wpdb->query($LanguagesTableInsert_sql);



//15. create  table ap_payment_transaction
$PaymentTableName = $wpdb->prefix . "ap_payment_transaction";
$PaymentTable_sql = "CREATE TABLE IF NOT EXISTS `$PaymentTableName` (
  `id` int(128) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `ammount` float NOT NULL,
  `date` text NOT NULL,
  `status` varchar(10) NOT NULL,
  `txn_id` varchar(128) NOT NULL,
  `gateway` varchar(30) NOT NULL,
  `other_fields` text NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($PaymentTable_sql);

//16. create table ap_appointment_sync
$AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
$AppointmentSyncTableName_sql = "CREATE TABLE IF NOT EXISTS `$AppointmentSyncTableName` ( `id` int(11) NOT NULL AUTO_INCREMENT, `app_id` int(11) NOT NULL, `timeoff_id` int(11) NOT NULL, `app_sync_details` text NOT NULL, PRIMARY KEY (`id`) )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($AppointmentSyncTableName_sql);


$ReminderTable = $wpdb->prefix . "ap_reminders";
$ReminderTable_sql = "CREATE TABLE `$ReminderTable` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`app_id` INT NOT NULL ,
`reminder_type` VARCHAR( 10 ) NOT NULL ,
`status` VARCHAR( 10 ) NOT NULL ,
`retries` INT NOT NULL ,
`error` TEXT NOT NULL,
`time_date` TIMESTAMP NOT NULL
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ReminderTable_sql);

//17. coupon code table
$CouponsCodesTable = $wpdb->prefix . "apcal_pre_coupons_codes";
$CouponsCodesTableSQL = "CREATE TABLE IF NOT EXISTS `$CouponsCodesTable` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`coupon_code` varchar(15) NOT NULL,
`description` text NOT NULL,
`discount` int(11) NOT NULL,
`expire` datetime NOT NULL,
`total_uses` int(11) NOT NULL,
`used_count` int(11) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($CouponsCodesTableSQL);

//18. Staff Appointment Sync Table
$StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
$StaffAppointmentSyncTableSQL = "CREATE TABLE IF NOT EXISTS `$StaffAppointmentSyncTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `staff_sync_details` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($StaffAppointmentSyncTableSQL);