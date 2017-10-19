<?php
global $wpdb;
global $current_user;
//get current user email by get option
get_currentuserinfo();
$useremail = $current_user->user_email;
// find current user staff id in staff table
$StaffTableName = $wpdb->prefix . "ap_staff";
$StaffDetails = $wpdb->get_row("SELECT * FROM `$StaffTableName` WHERE `email` = '$useremail'");
if(count($StaffDetails)) {
    $StaffId = $StaffDetails->id;

	$DateFormat = get_option('apcal_date_format');
	if($DateFormat == '') $DateFormat = "d-m-Y";
	$TimeFormat = get_option('apcal_time_format');
	if($TimeFormat == '') $TimeFormat = "h:i"; ?>
    <div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Manage Appointment(s)','appointzilla'); ?></h3>
    </div>

    <form action="" method="post" name="manage-appointments">
    <table width="100%" border="0" class="table">
      <tr>
        <td colspan="8" scope="col">
        <div style="float:left;">
        <select name="filtername" onchange="changeurl()" >
            <option value="All" <?php if(isset($_POST['filtername']) == 'All') echo "selected"; ?> <?php if(isset($_GET['filtername']) == 'All') echo "selected"; ?> ><?php _e('All Appointments','appointzilla'); ?></option>

            <option value="pending" <?php if(isset($_POST['filtername'])){ if($_POST['filtername'] == 'pending') echo "selected"; } ?> <?php if(isset($_GET['filtername'])){ if($_GET['filtername'] == 'pending') echo "selected"; } ?> > <?php _e('Pending Appointments','appointzilla'); ?></option>

            <option value="approved"  <?php if(isset($_POST['filtername'])){ if($_POST['filtername'] == 'approved') echo "selected"; } ?> <?php if(isset($_GET['filtername'])){ if($_GET['filtername'] == 'approved') echo "selected"; } ?>> <?php _e('Approved Appointments','appointzilla'); ?> </option>

            <option value="cancelled" <?php if(isset($_POST['filtername'])){ if($_POST['filtername'] == 'cancelled') echo "selected"; } ?> <?php if(isset($_GET['filtername'])){ if($_GET['filtername'] == 'cancelled') echo "selected"; } ?> ><?php _e('Cancelled Appointments','appointzilla'); ?></option>

            <option value="done"  <?php if(isset($_POST['filtername'])){ if($_POST['filtername'] == 'done') echo "selected"; } ?> <?php if(isset($_GET['filtername'])){ if($_GET['filtername'] == 'done') echo "selected"; } ?> ><?php _e('Done Appointments','appointzilla'); ?></option>

            <option value="today" <?php if(isset($_POST['filtername'])){ if($_POST['filtername'] == 'today') echo "selected"; } ?> <?php if(isset($_GET['filtername'])){ if($_GET['filtername'] == 'today') echo "selected"; } ?> ><?php _e('Today`s  Appointments','appointzilla'); ?></option>
        </select>

        </div>&nbsp;<button id="filter" class="btn btn-small btn-danger" type="submit" name="filter" ><?php _e('Filter Appointments','appointzilla'); ?></button>
        &nbsp;<a href="#" rel="tooltip" title="<?php _e("Filter Appointments",'appointzilla')?>" ><i  class="icon-question-sign"></i> </a>
      </tr>
      </table>
      </form>
      <?php
        global $wpdb;
        $noofrow = 10;
        $offset = 0;
        $filterdata = 'All';
        $pageno = 1;
        // pagination start with page no = 1 when filter
        if(!isset($_POST['filter'])) {
            if(!empty($_GET['pageno'])){
                $pageno = $_GET['pageno'];
                $offset = ($pageno-1)*$noofrow;
            }
        }

    if(isset($_POST['filter'])) {
        $filterdata = $_POST['filtername'];
        if($filterdata =='today') {
            $filterappointments = date('Y-m-d');  // frist time pagiantion data and total page like 1 2 3 only today
            $AppointmentTableName = $wpdb->prefix . "ap_appointments";
            $all_appointments = $wpdb->get_results("select * from `$AppointmentTableName` WHERE `date` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset, $noofrow ");
            $cat = $wpdb->get_results("select * from `$AppointmentTableName` WHERE `date` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
        } else {
            //pending , apporved cancelled ,Done filter :frist time pagiantion data and total page like 1 2 3
            $filterappointments =$filterdata;
            $table_name = $wpdb->prefix . "ap_appointments";
            if($filterappointments == 'unpaid' || $filterappointments == 'paid') {
                $all_appointments = $wpdb->get_results("select * from `$table_name` WHERE `payment_status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                $cat=$wpdb->get_results("SELECT * FROM `$table_name` WHERE `payment_status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
            } else {
                $all_appointments = $wpdb->get_results("select * from `$table_name` WHERE `status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                $cat=$wpdb->get_results("SELECT * FROM `$table_name` WHERE `status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
            }
        }

        if($filterdata =='All') {	//frist time pagiantion data and total page like 1 2 3 only All appointment filter
            $table_name = $wpdb->prefix . "ap_appointments";
            $all_appointments = $wpdb->get_results("select *  from `$table_name` WHERE `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
            $cat=$wpdb->get_results("select *  from `$table_name` WHERE `staff_id` = '$StaffId' ORDER BY `date` DESC");
        }
    } else {	// filter pagination  with get filter
            if(isset($_GET['filtername'])) {
                $filterdata=$_GET['filtername'];
                if($filterdata=='today') {
                    $filterappointments = date('Y-m-d');
                    $table_name = $wpdb->prefix . "ap_appointments";
                    $all_appointments = $wpdb->get_results("select * from `$table_name` WHERE `date` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                    $cat = $wpdb->get_results("select *  from `$table_name` WHERE `date` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
                } else {
                    $filterappointments =$filterdata;
                    $table_name = $wpdb->prefix . "ap_appointments";
                    if($filterappointments == 'paid' && $filterappointments == 'paid') {
                        $all_appointments = $wpdb->get_results("select *  from `$table_name` WHERE `payment_status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                        $cat=$wpdb->get_results("SELECT * FROM `$table_name` WHERE `payment_status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
                    }else {
                        $all_appointments = $wpdb->get_results("select * from `$table_name` WHERE `status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                        $cat = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `status` ='$filterappointments' AND `staff_id` = '$StaffId' ORDER BY `date` DESC");
                    }
                }

            //pagination get value
            if($filterdata =='All') {
                $table_name = $wpdb->prefix . "ap_appointments";
                $all_appointments = $wpdb->get_results("select * from `$table_name` AND `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset,$noofrow");
                $cat = $wpdb->get_results("select *  from `$table_name` ORDER BY `date` DESC");
            }
        } else {
            //all appointment with pagination
            $table_name = $wpdb->prefix . "ap_appointments";
            $all_appointments = $wpdb->get_results("select * from `$table_name` WHERE `staff_id` = '$StaffId' ORDER BY `date` DESC limit $offset, $noofrow");
            $cat = $wpdb->get_results("select *  from `$table_name` WHERE `staff_id` = '$StaffId' ORDER BY `date` DESC");
        }
    }?>

     <form action="" method="post" name="manage-appointments">
      <table width="100%" border="0" class="table table-hover">
      <tr>
        <th align="left" scope="col"><?php _e('No.','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Name','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Service','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Staff','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Date','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Time','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Repeat','appointzilla'); ?></th>
        <th align="left" scope="col"><?php _e('Status','appointzilla'); ?></th>
        <!-- <th align="left" scope="col"><?php /*_e('Action','appointzilla'); */?></th>-->
        <!--<th align="left" scope="col"><a href="#" rel="tooltip" title="<?php /*_e('Select All','appointzilla'); */?>">
          <input type="checkbox" id="checkbox" name="checkbox[]" value="0" /></a>
        </th>-->
        </tr>
      <?php
       //get all appointments list
       $i=1;
    if($all_appointments) {
         foreach($all_appointments as $appointment) { ?>
      <tr>
        <td><em><?php echo $i."."; ?></em></td>
        <td><em><?php echo ucwords($appointment->name)." ($appointment->email)"; ?></em></td>
        <td>
          <em><?php $ServiceId = $appointment->service_id;
              $ServiceTable = $wpdb->prefix . "ap_services";
              $ServiceData = $wpdb->get_row("SELECT * FROM `$ServiceTable` WHERE `id` = '$ServiceId'");
              echo ucfirst($ServiceData->name); ?>
          </em>
        </td>
        <td>
            <em><?php $staffid = $appointment->staff_id;
            $StaffTable = $wpdb->prefix . "ap_staff";
            $staff_details= $wpdb->get_row("SELECT * FROM `$StaffTable` WHERE `id` ='$staffid'");
            echo ucfirst($staff_details->name); ?>
            </em>
        </td>
        <td><em><?php
        $DateFormat = get_option('apcal_date_format');
        if($appointment->recurring == 'yes')
        echo date($DateFormat, strtotime($appointment->recurring_st_date))." - ".date($DateFormat, strtotime($appointment->recurring_ed_date));
        else
        echo date($DateFormat, strtotime($appointment->date)); ?></em></td>
        <td>
            <em><?php
            if($TimeFormat == 'h:i') $ATimeFormat = "g:ia"; else $ATimeFormat = "G:i";
            echo date($ATimeFormat, strtotime($appointment->start_time))." - ".date($ATimeFormat, strtotime($appointment->end_time)); ?>
            </em>
        </td>
        <td>
            <em><?php echo _e(ucfirst($appointment->recurring_type), 'appointzilla');//echo ucfirst($appointment->recurring_type); ?></em>
        </td>
        <td>
            <div id="Appstatus<?php echo $appointment->id; ?>"  onclick="Changestatus('<?php echo ucfirst($appointment->id); ?>')" >
                <a><?php  echo _e(ucfirst($appointment->status), 'appointzilla'); ?></a>
            </div>
            <div id="Appstatuslist<?php echo $appointment->id; ?>" style="display:none;">
                <select name="appchangestatus<?php echo $appointment->id; ?>" id="appchangestatus<?php echo $appointment->id; ?>" onchange="appchangestatus('<?php echo $appointment->id; ?>')" style="width:100px;" >
                    <option value="-1"><?php _e('Select Status', 'appointzilla'); ?></option>
                    <option value="pending"><?php _e('Pending', 'appointzilla'); ?></option>
                    <option value="approved"><?php _e('Approved', 'appointzilla'); ?></option>
                    <option value="cancelled"><?php _e('Cancelled', 'appointzilla'); ?></option>
                    <option value="done"><?php _e('Done', 'appointzilla'); ?></option>
                  </select>
            </div>
            <div id="retunappstatusdiv<?php echo $appointment->id; ?>" style="display:none" onclick="Changestatus('<?php echo ucfirst($appointment->id); ?>')" >

            </div>
            <div id="lodingimagediv<?php echo $appointment->id; ?>" style="display:none;">
                <?php _e('Updating...', 'appointzilla'); ?><img src="<?php echo plugins_url()."/appointment-calendar-premium/images/loading.gif"; ?>" />
            </div>
        </td>
        <!--<td>
            <a href="?page=update-appointment&viewid=<?php /*echo $appointment->id; */?>" title="<?php /*_e('View','appointzilla'); */?>" rel="tooltip"><i class="icon-eye-open"></i></a>&nbsp;
            <a href="?page=update-appointment&updateid=<?php /*echo $appointment->id; */?>" title="<?php /*_e('Update','appointzilla'); */?>" rel="tooltip"><i class="icon-pencil"></i></a>&nbsp;
            <a href="?page=manage-appointments&delete=<?php /*echo $appointment->id; */?>" rel="tooltip" title="<?php /*_e('Delete','appointzilla'); */?>"
            onclick="return confirm('<?php /*_e('Do you want to delete this appointment?','appointzilla'); */?>')"><i class="icon-remove" ></i></a>
        </td>-->
        <!--<td align="left"><a rel="tooltip" title="<?php /*_e('Select','appointzilla'); */?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php /*echo $appointment->id; */?>" /></a></td>-->
        </tr>
      <?php $i++; }   ?>
      <tr>
        <td colspan="7">
        <ul id="pagination-flickr" style="border:1px #CCCCCC;">
                <li><a href="?page=manage-appointments&pageno=1&filtername=<?php echo $filterdata; ?>" ><?php _e('First','appointzilla'); ?> </a> </li>
                <?php
                    // pagination list items
                    if(isset($_GET['pageno'])) { $pgno = $_GET['pageno']; } else { $pgno = 1; }
                    $catrow = count($cat);
                    $page = ceil($catrow/$noofrow);
                    for($i=1; $i<=$page; $i++)
                    {?><li><a href="?page=manage-appointments&pageno=<?php echo $i?>&filtername=<?php echo $filterdata; ?>" <?php if($pgno == $i ) echo "class='active'"; else echo "class=''"; ?>  ><?php echo $i; ?> </a></li><?php }	 ?>
                    <li ><a href="?page=manage-appointments&pageno=<?php echo $i-1 ?>&filtername=<?php echo $filterdata; ?>" > <?php _e('Last','appointzilla'); ?></a></li>
              </ul>	</td>
        <td><!--<button name="deleteall" class="btn btn-primary" type="submit" id="deleteall" onclick="return confirm('<?php /*_e('Do you want to delete these appointments?','appointzilla'); */?>')" ><?php /* _e('Delete','appointzilla'); */?></button>--></td>
        </tr>
      <?php } else { ?>
      <tr class="alert"><td colspan="10"><strong><?php _e('Sorry No Appointment(s).','appointzilla'); ?></strong></td>
      <td>&nbsp;</td>
      </tr>
       <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <!--<td>&nbsp;</td>-->
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <!--<td>&nbsp;</td>-->
        </tr>
      <?php } ?>
    </table>
    </form>
    <script>
    function Changestatus(appid) {
        var Appstatus ="#Appstatus"+appid;
        var Appstatuslist ="#Appstatuslist"+appid;
        var retunappstatusdiv ="#retunappstatusdiv"+appid;
        jQuery(Appstatus).hide();
        jQuery(retunappstatusdiv).hide();
        jQuery(Appstatuslist).show();
    }

    function appchangestatus(appid) {
        var Appstatuslist = "#Appstatuslist"+appid;
        var appchangestatus = "#appchangestatus"+appid;
        var retunappstatusdiv = "#retunappstatusdiv"+appid;
        var lodingimagediv = "#lodingimagediv"+appid;
        var status =jQuery(appchangestatus).val();

        jQuery(Appstatuslist).hide();
        jQuery(retunappstatusdiv).show();
        jQuery(lodingimagediv).show();

        var url = location.href;
        var datastring = "appid="+appid+"&appstatus="+status;
        jQuery.ajax({
            type     : "POST",
            cache    : false,
            url      : url,
            data     : datastring,
            success  : function(data) {
                        jQuery(retunappstatusdiv).html("<a href='#'>" + status[0].toUpperCase()+ status.slice(1) + "</a>");
                        jQuery(lodingimagediv).hide();
                        var currenturl = jQuery(location).attr('href');
            }
        });
    }
    </script>
    <?php // update appointment status
    if(isset($_POST['appid']) && isset($_POST['appstatus'])) {
        global $wpdb;
        $appointmentstable_name = $wpdb->prefix."ap_appointments";
        $status = $_POST['appstatus'];
        $up_app_id = $_POST['appid'];
        $update_appointment ="UPDATE `$appointmentstable_name` SET `status` = '$status' WHERE `id` = '$up_app_id'";
        if($wpdb->query($update_appointment)) {
            $Appointment_details = $wpdb->get_row("SELECT * FROM `$appointmentstable_name` WHERE `id` = '$up_app_id' ", OBJECT);
            $name = strip_tags($Appointment_details->name);
            $email = $Appointment_details->email;
            $serviceid = $Appointment_details->service_id;
            $staffid = $Appointment_details->staff_id;
            $phone = $Appointment_details->phone;
            $start_time = date("h:i A", strtotime($Appointment_details->start_time));
            $end_time = date("h:i A", strtotime($Appointment_details->end_time));
            $appointmentdate = date("Y-m-d", strtotime($Appointment_details->date));
            $note = strip_tags($Appointment_details->note);
            //$payment_status = strip_tags($_POST['payment_status']);

            $status = $Appointment_details->status;
            $recurring = $Appointment_details->recurring;
            $recurring_type = $Appointment_details->recurring_type;
            $recurring_st_date = date("Y-m-d", strtotime($Appointment_details->recurring_st_date));
            $recurring_ed_date = date("Y-m-d", strtotime($Appointment_details->recurring_ed_date));
            $appointment_by = $Appointment_details->appointment_by;


            //send notification to client if appointment approved or cancelled
            if($status == 'approved' || $status == 'cancelled' ) {
                $GetAppKey = $wpdb->get_row("SELECT * FROM `$appointmentstable_name` WHERE `id` = '$up_app_id' ", OBJECT);

                $BlogName =  get_bloginfo();
                $ClientTable = $wpdb->prefix . "ap_clients";
                $GetClient = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$email' ", OBJECT);
                if($up_app_id && $GetClient->id) {
                    $AppId = $up_app_id;
                    $ServiceId = $serviceid;
                    $StaffId = $staffid;
                    $ClientId = $GetClient->id;
                    //include notification class
                    require_once('notification-class.php');
                    $Notification = new Notification();
                    if($status == 'approved') $On = "approved";
                    if($status == 'cancelled') $On = "cancelled";
                    $Notification->notifyclient($On, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
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
                $CalData = get_option('google_caelndar_settings_details');

                if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                    $ClientId = $CalData['google_calendar_client_id'];
                    $ClientSecretId = $CalData['google_calendar_secret_key'];
                    $RedirectUri = $CalData['google_calendar_redirect_uri'];
                    require_once('google-appointment-sync-class.php');

                    global $wpdb;
                    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                    $AnySycnId = $wpdb->get_row("SELECT `id` FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                    //check appointment already synced or first time approved
                    if(count($AnySycnId)) {
                        // update this appointment event on calendar
                        global $wpdb;
                        $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                        $SyncDetails = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                        $SyncTableRowId = $SyncDetails->id;
                        $SyncDetailsData = unserialize($SyncDetails->app_sync_details);
                        $json  = json_encode($SyncDetailsData);
                        $SyncDetailsData = json_decode($json, true);
                        $sync_id = $SyncDetailsData['id'];
                        $sync_email = $SyncDetailsData['creator']['email'];

                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $tag = "Appointment with: ";
                        if($repeat == 'none') {
                            $OAuth = $GoogleAppointmentSync->UpdateNormalSync($sync_id, $sync_email, $name, $appointmentdate, $start_time, $end_time, $note, $tag);
                        }
                        if($repeat != 'none') {
                            $OAuth = $GoogleAppointmentSync->UpdateRecurringSync($sync_id, $sync_email, $name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                        }

                        //update appintment sync details
                        $OAuth = serialize($OAuth);
                        $wpdb->query("UPDATE `$AppointmentSyncTableName` SET `app_sync_details` = '$OAuth' WHERE `id` = '$SyncTableRowId'");
                    } else {
                        // insert this appointment event on calendar
                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $tag = "Appointment with: ";
                        if($recurring_type == 'none')
                        {
                            $OAuth = $GoogleAppointmentSync->NormalSync($name, $appointmentdate, $start_time, $end_time, $note, $tag);
                        }
                        if($recurring_type != 'none')
                        {
                            $OAuth = $GoogleAppointmentSync->RecurringSync($name, $recurring_st_date, $recurring_ed_date, $start_time, $end_time, $recurring_type, $note, $tag);
                        }

                        //insert appintment sync details
                        $OAuth = serialize($OAuth);
                        $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` )
                        VALUES ( NULL , '$up_app_id', '$OAuth' );");
                    }
                }//end of if cal settings check
            }//end of if approved*/


            //if status is cancelled then delete sync appointment
            if($status == 'cancelled' || $status == 'pending') {
                global $wpdb;
                $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                if($Row) {
                    $Row = unserialize($Row->app_sync_details);
                    $json  = json_encode($Row);
                    $Row = json_decode($json, true);
                    $SyncId = $Row['id'];
                    if($SyncId) {
                        $CalData = get_option('google_caelndar_settings_details');
                        $ClientId = $CalData['google_calendar_client_id'];
                        $ClientSecretId = $CalData['google_calendar_secret_key'];
                        $RedirectUri = $CalData['google_calendar_redirect_uri'];

                        require_once('google-appointment-sync-class.php');
                        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                        $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);

                        // delete sync details
                        $wpdb->query("DELETE FROM `$AppointmentSyncTableName` WHERE `app_id` = '$up_app_id'");
                    }
                }
            }//end of cancel status check
        } // end of update query
    }// endd of isset appointment
    ?>


    <!--validation js lib-->
    <script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#checkbox').click(function(){
            if(jQuery('#checkbox').is(':checked')) {
                jQuery(":checkbox").prop("checked", true);
            } else {
                jQuery(":checkbox").prop("checked", false);
            }
        });
    });
    </script>

    <?php // Delete single appointment
    if(isset($_GET['delete'])) {
        $deleteid= $_GET['delete'];
        $table_name = $wpdb->prefix . "ap_appointments";
        $delete_app_query="DELETE FROM `$table_name` WHERE `id` = '$deleteid';";
        if($wpdb->query($delete_app_query)) {
            global $wpdb;
            $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
            $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$deleteid'");
            $Row = unserialize($Row->app_sync_details);
            $json  = json_encode($Row);
            $Row = json_decode($json, true);
            $SyncId = $Row['id'];
            if($SyncId) {
                $CalData = get_option('google_caelndar_settings_details');
                $ClientId = $CalData['google_calendar_client_id'];
                $ClientSecretId = $CalData['google_calendar_secret_key'];
                $RedirectUri = $CalData['google_calendar_redirect_uri'];

                require_once('google-appointment-sync-class.php');
                $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);

                // delete sync details
                $wpdb->query("DELETE FROM `$AppointmentSyncTableName` WHERE `app_id` = '$deleteid'");
            }
            echo "<script>alert('".__('Appointment successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='?page=manage-staff-appointments';</script>";
        }
    }

    // Delete multiple appointment with checkbox
    if(isset($_POST['deleteall'])) {
        global $wpdb;
        $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
        $table_name = $wpdb->prefix . "ap_appointments";

        $CalData = get_option('google_caelndar_settings_details');
        $ClientId = $CalData['google_calendar_client_id'];
        $ClientSecretId = $CalData['google_calendar_secret_key'];
        $RedirectUri = $CalData['google_calendar_redirect_uri'];

        require_once('google-appointment-sync-class.php');
        $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);

        for($i=0; $i <= count($_POST['checkbox'])-1; $i++) {
            $res = $_POST['checkbox'][$i];
            $deleteid = $res;
            $delete_app_query="DELETE FROM `$table_name` WHERE `id` = '$deleteid';";
            $wpdb->query($delete_app_query);

            $Row = $wpdb->get_row("SELECT * FROM `$AppointmentSyncTableName` WHERE `app_id` = '$deleteid'");
            $Row = unserialize($Row->app_sync_details);
            $json  = json_encode($Row);
            $Row = json_decode($json, true);
            $SyncId = $Row['id'];
            if($SyncId) {
                $OAuth = $GoogleAppointmentSync->DeleteSync($SyncId);
                // delete sync details
                $wpdb->query("DELETE FROM `$AppointmentSyncTableName` WHERE `app_id` = '$deleteid'");
            }
        }
        if(count($_POST['checkbox'])) {
            echo "<script>alert('".__('Selected Appointment(s) successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='?page=manage-staff-appointments';</script>";
        } else {
            echo "<script>alert('".__('No Appointment(s) selected to delete.','appointzilla')."');</script>";
            echo "<script>location.href='?page=manage-staff-appointments';</script>";
        }
    }?>
    </div>

    <style type="text/css">
    .error{  color:#FF0000; }
    <style type="text/css">
    ul{border:0; margin:0; padding:0;}

    #pagination-flickr li{
        border:0; margin:0; padding:0;
        font-size:11px;
        list-style:none;
    }
    #pagination-flickr a{
        border:solid 1px #C3D9FF;
        margin-right:5px;
    }
    #pagination-flickr .previous-off,
    #pagination-flickr .next-off {
        color:#666666;
        display:block;
        float:left;
        font-weight:bold;
        padding:3px 4px;
    }
    #pagination-flickr .next a,
    #pagination-flickr .previous a {
        font-weight:bold;
        border:solid 1px #FFFFFF;
    }
    #pagination-flickr .active{
        color:#ff0084;
        background:#C3D9FF;
        font-weight:bold;
        display:block;
        float:left;
        padding:4px 6px;
    }
    #pagination-flickr a:link,
    #pagination-flickr a:visited {
        color:#0063e3;
        display:block;
        float:left;
        padding:3px 6px;
        text-decoration:none;
    }
    #pagination-flickr a:hover{
        border:solid 1px #666666;
    }
    </style>
<?php } else {
    echo "<br><div class='alert alert-block fade in' style='margin-right: 16px;'><strong>Notice</strong> This page contents are available only for authorised users.</div>";
} ?>