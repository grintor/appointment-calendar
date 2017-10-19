<?php
$DateFormat = get_option('apcal_date_format');
if($DateFormat == '') $DateFormat = "d-m-Y";
$TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $TimeFormat = "h:i";
if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";
global $wpdb;
$ServiceTable = $wpdb->prefix ."ap_services";
$StaffTable = $wpdb->prefix ."ap_staff";

if(isset($_POST['ListType'])) {

    $ListType = $_POST['ListType'];
    // generate appointments list
    if($ListType == "appointments-list") {

        $Range = $_POST['Range'];
        $StartDate = date("Y-m-d", strtotime($_POST['StartDate']));
        $EndDate = date("Y-m-d", strtotime($_POST['EndDate']));

        global $wpdb;
        $AppointmentTable = $wpdb->prefix . "ap_appointments";
        //today list
        if($Range == "T") {
            $TodayDate = date("Y-m-d");
            $ListQuery = "SELECT * FROM `$AppointmentTable` WHERE `date` = '$TodayDate'";
        }

        //this week list
        if($Range == "W") {
            $ListQuery = "SELECT * FROM `$AppointmentTable` WHERE `date` BETWEEN '$StartDate' AND '$EndDate'";
        }

        //this month list
        if($Range == "M") {
            $ListQuery = "SELECT * FROM `$AppointmentTable` WHERE `date` BETWEEN '$StartDate' AND '$EndDate'";
        }

        //custom range list
        if($Range == "CR") {
            $ListQuery = "SELECT * FROM `$AppointmentTable` WHERE `date` BETWEEN '$StartDate' AND '$EndDate'";
        }

        //all appointment list
        if($Range == "A") {
            $ListQuery = "SELECT * FROM `$AppointmentTable`";
        }

        $FileName = $_POST['FileName'];

        $QueryResults = $wpdb->get_results($ListQuery);
        if(count($QueryResults)) {
            $DirName = "appointments-lists";
            $DirPath = "../wp-content/".$DirName;

            if(!file_exists($DirPath)) {
                mkdir($DirPath, 0777);
            }

            $FileName =  $DirPath."/".$FileName;
            $df = fopen($FileName , "x+");

            //write data into file
            $FirstRow = "#, Name, Email, Phone, Note, Service, Staff, Date, Time, Repeat, Status, Payment Status, Created By\n";
            fwrite($df, $FirstRow);
            $id = 1;
            foreach($QueryResults as $Data) {
                $name = $Data->name;
                $email = $Data->email;
                $phone = $Data->phone;
                $note = $Data->note;
                //get service name
                $serviceId = $Data->service_id;
                $serviceData = $wpdb->get_row("SELECT `name` FROM `$ServiceTable` WHERE `id` ='$serviceId'");
                $service = $serviceData->name;
                // get staff name
                $staffId = $Data->staff_id;
                $staffData = $wpdb->get_row("SELECT `name` FROM `$StaffTable` WHERE `id` ='$staffId'");
                $staff = $staffData->name;
                $date = date($DateFormat, strtotime($Data->date));
                $starttime = $Data->start_time;
                $endtime = $Data->end_time;
                $time = date($TimeFormat, strtotime($starttime))." - ".date($TimeFormat, strtotime($endtime));
                $repeat = $Data->recurring_type;
                if($repeat != 'none') {
                    $date = date($DateFormat, strtotime($Data->recurring_st_date))." - ". date($DateFormat, strtotime($Data->recurring_ed_date));
                }
                $status = $Data->status;
                $payment = $Data->payment_status;
                $appointmentby = $Data->appointment_by;

                $Rows = $id. "," .ucwords($name). "," .$email. "," .$phone. "," .ucfirst($note). "," .ucwords($service). "," .ucwords($staff). "," .$date. "," .$time. "," .ucfirst($repeat). "," .ucfirst($status). "," .ucfirst($payment). ","  .ucfirst($appointmentby)."\n";
                fwrite($df, $Rows);
                $id++;
            }
            fclose($df);

        } else {
            $DirName = "appointments-lists";
            $DirPath = "../wp-content/".$DirName;

            if(!file_exists($DirPath)) {
                mkdir($DirPath, 0777);
            }

            $FileName =  $DirPath."/".$FileName;
            $df = fopen($FileName , "x+");

            //write data into file
            $FirstRow = "#, Name, Email, Phone, Note, Service, Staff, Date, Time, Repeat, Status, Payment Status, Created By\n";
            fwrite($df, $FirstRow);
            fwrite($df, "Sorry! No Appointment(s) Found");
            fclose($df);
        }

    }

    //generating clients lists
    if($ListType == "clients-list") {

        $Range = $_POST['Range'];
        $StartDate = date("Y-m-d", strtotime($_POST['StartDate']));
        $EndDate = date("Y-m-d", strtotime($_POST['EndDate']));

        global $wpdb;
        $ClientsTable = $wpdb->prefix . "ap_clients";
        //custom range list
        if($Range == "CC") {
            $ListQuery = "SELECT * FROM `$ClientsTable` WHERE `date` BETWEEN '$StartDate' AND '$EndDate'";
        }

        //all clients  list
        if($Range == "AC") {
            $ListQuery = "SELECT * FROM `$ClientsTable`";
        }

        $FileName = $_POST['FileName'];

        $QueryResults = $wpdb->get_results($ListQuery);
        if(count($QueryResults)) {
            $DirName = "clients-lists";
            $DirPath = "../wp-content/".$DirName;

            if(!file_exists($DirPath)) {
                mkdir($DirPath, 0777);
            }

            $FileName =  $DirPath."/".$FileName;
            $df = fopen($FileName , "x+");

            //write data into file
            $FirstRow = "#, Name, Email, Phone, Note\n";
            fwrite($df, $FirstRow);
            $id = 1;
            foreach($QueryResults as $Data) {
                $name = $Data->name;
                $email = $Data->email;
                $phone = $Data->phone;
                $note = $Data->note;
                $Rows = $id. "," .ucwords($name). "," .$email. "," .$phone. "," .ucfirst($note)."\n";
                fwrite($df, $Rows);
                $id++;
            }
            fclose($df);

        } else {
            $DirName = "clients-lists";
            $DirPath = "../wp-content/".$DirName;

            if(!file_exists($DirPath)) {
                mkdir($DirPath, 0777);
            }

            $FileName =  $DirPath."/".$FileName;
            $df = fopen($FileName , "x+");

            //write data into file
            $FirstRow = "#, Name, Email, Phone, Note\n";
            fwrite($df, $FirstRow);
            fwrite($df, "Sorry! No Clients(s) Found");
            fclose($df);
        }
    }
}// end of isset if

//check last active tab
if(isset($_GET['activetab']) == "clients") {
    $ClientsActive = "active";
    $ClientsActiveIn = "in active";
    $AppointmentsActive = "";
    $AppointmentsActiveIn = "";
} else {
    $ClientsActive = "";
    $ClientsActiveIn = "";
    $AppointmentsActive = "active";
    $AppointmentsActiveIn = "in active";
} ?>
<style>
    .tab-content > .active, .pill-content > .active {
        display: block !important;
    }
    .tab-content > .tab-pane, .pill-content > .pill-pane {
        display: block !important;
    }
</style>
<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
        <h3><i class="fa fa-list-alt"></i> <?php _e("Export Lists", "appointzilla"); ?></h3>
    </div>

    <div class="bs-docs-example">
        <ul class="nav nav-tabs" id="myTab">
            <li class="<?php echo $AppointmentsActive; ?>"><a data-toggle="tab" href="#appointments-lists"><i class="fa fa-calendar"></i> <strong>Export Appointments List</strong></a></li>
            <li class="<?php echo $ClientsActive; ?>"><a data-toggle="tab" href="#clients-lists"><i class="fa fa-group"></i> <strong>Export Clients List</strong></a></li>
        </ul>
        <div class="tab-content" id="myTabContent" style="padding-left: 5px;">
            <!--export appointments tab-->
            <div id="appointments-lists" class="tab-pane fade <?php echo $AppointmentsActiveIn; ?>" >
                <table width="100%" class="table">
                    <tr id="select-range-tr">
                        <th width="18%" align="right" scope="row"><?php _e("Select Range", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <select name="select_range" id="select_range">
                                <option value="0"><?php _e("Select Range", "appointzilla"); ?></option>
                                <option value="T"><?php _e("Today's Appointment", "appointzilla"); ?></option>
                                <option value="W"><?php _e("This Week Appointment", "appointzilla"); ?></option>
                                <option value="M"><?php _e("This Month Appointment", "appointzilla"); ?></option>
                                <option value="CR"><?php _e("Custom Appointment Range", "appointzilla"); ?></option>
                                <option value="A"><?php _e("All Appointment", "appointzilla"); ?></option>
                            </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Appointment Selection Range", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                        </td>
                    </tr>
                    <tr id="custom-dates1" style="display:none;">
                        <th width="18%" align="right" scope="row"><?php _e("Select Start Date", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <input type="text" id="start_date" name="start_date">
                        </td>
                    </tr>
                    <tr id="custom-dates2" style="display:none;">
                        <th width="18%" align="right" scope="row"><?php _e("Select End Date", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <input type="text" id="end_date" name="end_date">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>&nbsp;</td>
                        <td>
                            <button name="export-appointment-lists" class="btn btn-primary" type="submit" id="export-appointment-lists" data-loading-text="Saving Settings" ><i class="icon-list-alt icon-white"></i> <?php _e("Export Appointment List", "appointzilla"); ?></button>
                            <br><br>
                            <div id="loading-img" style="display: none;">
                                <?php _e('Generating appointments list, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" />
                            </div>
                        </td>
                    </tr>
                </table>

                <!--table for listing appointment-->
                <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <h3><?php _e("Appointments List(s)", "appointzilla"); ?></h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e("Lists", "appointzilla"); ?></th>
                        <th><?php _e("Date", "appointzilla"); ?></th>
                        <th><?php _e("Time", "appointzilla"); ?></th>
                        <th><?php _e("Size", "appointzilla"); ?></th>
                        <th><?php _e("Action", "appointzilla"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $DirName = "appointments-lists";
                    $DirPath = "../wp-content/".$DirName;
                    // check if appointment list dir exist and not empty
                    $TotalFiles =count(glob("$DirPath/*"));
                    if( file_exists($DirPath) &&  $TotalFiles > 0) {

                        $AllFiles = scandir($DirPath, 1);
                        $i = 1;
                        foreach($AllFiles as $File) {
                            if ($File != "." && $File != "..") {
                                ?>
                                <tr>
                                    <td><?php echo $i."."; ?></td>
                                    <td><?php echo ucfirst($File); ?></td>
                                    <td><?php
                                        $FilePath = $DirPath."/".$File;
                                        if (file_exists($FilePath)) {
                                            echo date ("d-m-Y", filemtime($FilePath));
                                        } ?></td>
                                    <td><?php if (file_exists($FilePath)) {
                                            echo date ("H:i:s", filemtime($FilePath));
                                        } ?></td>
                                    <td><?php echo round( ( filesize($FilePath)/1024 ), 2) . ' KB'; ?></td>
                                    <td>
                                        <a class="btn btn-mini btn-success" href="<?php echo $FilePath; ?>" target="_blank"><i class="icon-download-alt icon-white"></i> <strong><?php _e("Download", "appointzilla"); ?></strong></a>
                                        <a class="btn btn-mini btn-danger" href="?page=export-lists&delete-appointment-file=<?php echo $File; ?>"><i class="icon-remove icon-white"></i> <strong><?php _e("Delete", "appointzilla"); ?></strong></a>
                                    </td>
                                </tr>
                            <?php

                            }//end of foreach inner if
                            $i++;
                        }//end of foreach
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><a class="btn btn-small btn-danger" href="?page=export-lists&delete-appointment-file=all"><i class="icon-trash icon-white"></i> <strong><?php _e("Delete All", "appointzilla"); ?></strong></a></td>
                        </tr>
                    <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="alert"><strong><?php _e("Sorry! No appointment lists are available.", "appointzilla"); ?></strong></td>
                        </tr>
                    <?php
                    }//end of else
                    ?>
                    </tbody>
                </table>
            </div>
            <!--export appointments tab end-->



            <!--export clients tab-->
            <div id="clients-lists" class="tab-pane fade <?php echo $ClientsActiveIn; ?>">
                <table width="100%" class="table">
                    <tr id="select-range-tr">
                        <th width="18%" align="right" scope="row"><?php _e("Select Range", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <select name="select_clients_range" id="select_clients_range">
                                <option value="0"><?php _e("Select Range", "appointzilla"); ?></option>
                                <option value="AC"><?php _e("All Clients", "appointzilla"); ?></option>
                                <!--<option value="CC"><?php /*_e("Custom Clients Range", "appointzilla"); */?></option>-->
                            </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Clients Selection Range", "appointzilla"); ?>" ><i class="icon-question-sign"></i></a>
                        </td>
                    </tr>
                    <tr id="clients-custom-dates1" style="display:none;">
                        <th width="18%" align="right" scope="row"><?php _e("Select Start Date", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <input type="text" id="clients-start-date" name="clients-start-date">
                        </td>
                    </tr>
                    <tr id="clients-custom-dates2" style="display:none;">
                        <th width="18%" align="right" scope="row"><?php _e("Select End Date", "appointzilla"); ?></th>
                        <td width="3%" align="center"><strong>:</strong></td>
                        <td width="79%">
                            <input type="text" id="clients-end-date" name="clients-end-date">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>&nbsp;</td>
                        <td>
                            <button name="export-clients-lists" class="btn btn-primary" type="submit" id="export-clients-lists" data-loading-text="Saving Settings" ><i class="icon-list-alt icon-white"></i> <?php _e("Export Clients List", "appointzilla"); ?></button>
                            <br><br>
                            <div id="client-loading-img" style="display: none;">
                                <?php _e('Generating clients list, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" />
                            </div>
                        </td>
                    </tr>
                </table>


                <!--table for listing clients-->
                <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <h3><?php _e("Clients List(s)", "appointzilla"); ?></h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e("Lists", "appointzilla"); ?></th>
                        <th><?php _e("Date", "appointzilla"); ?></th>
                        <th><?php _e("Time", "appointzilla"); ?></th>
                        <th><?php _e("Size", "appointzilla"); ?></th>
                        <th><?php _e("Action", "appointzilla"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $DirName = "clients-lists";
                    $DirPath = "../wp-content/".$DirName;
                    // check if appointment list dir exist and not empty
                    $TotalFiles =count(glob("$DirPath/*"));
                    if( file_exists($DirPath) &&  $TotalFiles > 0) {

                        $AllFiles = scandir($DirPath, 1);
                        $i = 1;
                        foreach($AllFiles as $File) {
                            if ($File != "." && $File != "..") {
                                ?>
                                <tr>
                                    <td><?php echo $i."."; ?></td>
                                    <td><?php echo ucfirst($File); ?></td>
                                    <td><?php
                                        $FilePath = $DirPath."/".$File;
                                        if (file_exists($FilePath)) {
                                            echo date ("d-m-Y", filemtime($FilePath));
                                        } ?></td>
                                    <td><?php if (file_exists($FilePath)) {
                                            echo date ("H:i:s", filemtime($FilePath));
                                        } ?></td>
                                    <td><?php echo round( ( filesize($FilePath)/1024 ), 2) . ' KB'; ?></td>
                                    <td>
                                        <a class="btn btn-mini btn-success" href="<?php echo $FilePath; ?>" target="_blank"><i class="icon-download-alt icon-white"></i> <strong><?php _e("Download", "appointzilla"); ?></strong></a>
                                        <a class="btn btn-mini btn-danger" href="?page=export-lists&delete-client-file=<?php echo $File; ?>"><i class="icon-remove icon-white"></i> <strong><?php _e("Delete", "appointzilla"); ?></strong></a>
                                    </td>
                                </tr>
                            <?php

                            }//end of foreach inner if
                            $i++;
                        }//end of foreach
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><a class="btn btn-small btn-danger" href="?page=export-lists&delete-client-file=all"><i class="icon-trash icon-white"></i> <strong><?php _e("Delete All", "appointzilla"); ?></strong></a></td>
                        </tr>
                    <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="alert"><strong><?php _e("Sorry! No client lists are available.", "appointzilla"); ?></strong></td>
                        </tr>
                    <?php
                    }//end of else
                    ?>
                    </tbody>
                </table>
            </div>
            <!--export clients tab-->
        </div>
    </div>

    <style type="text/css">
        .apcal_error{  color:#FF0000; }
    </style>
    <!--validation js lib-->
    <script src="<?php echo plugins_url('/settings/js/bootstrap.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/settings/js/bootstrap.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/settings/js/bootstrap-tab.js', __FILE__); ?>" type="text/javascript"></script>

    <!--time-picker js -->
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-1.7.2.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script src="<?php echo plugins_url('/timepicker-assets/js/jquery-ui-timepicker-addon.js', __FILE__); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo plugins_url('js/date.js', __FILE__); ?>"></script>
    <script type="text/javascript">
    jQuery(document).ready(function () {

        //appointment lists validations & js work
        jQuery('#select_range').change(function() {
            var Range = jQuery('#select_range').val();
            if(Range == "CR") {
                jQuery("#custom-dates1").show();
                jQuery("#custom-dates2").show();
            } else {
                jQuery("#custom-dates1").hide();
                jQuery("#custom-dates2").hide();
            }
        });

        jQuery('#start_date').datepicker({
            dateFormat: 'dd-mm-yy',
        });

        jQuery('#end_date').datepicker({
            dateFormat: 'dd-mm-yy',
        });

        jQuery('#export-appointment-lists').click(function(){
            jQuery(".apcal_error").hide();
            var StartDate, EndDate;
            var Range = jQuery('#select_range').val();
            var RangeName = "";
            if(Range == 0) {
                jQuery("#select_range").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any range.", "appointzilla"); ?></strong></span>');
                return false;
            }

           if(Range == "T") {
               StartDate = "<?php echo date("Y-m-d"); ?>";
               EndDate = "<?php echo date("Y-m-d"); ?>";
               RangeName = "today";
            }
           if(Range == "W") {
                <?php
                    $week = date("W") - 1;
                    $year = date("Y");
                    $time = strtotime("1 January $year", time());
                    $day = date('w', $time);
                    $time += ((7*$week)+1-$day)*24*3600;
                    $StartDate = date('Y-n-j', $time);
                    $time += 6*24*3600;
                    $EndDate = date('Y-n-j', $time);
                ?>
                StartDate = "<?php echo  date("Y-m-d", strtotime($StartDate)); ?>";
                EndDate = "<?php echo date("Y-m-d", strtotime($EndDate)); ?>";
                RangeName = "weekly";
           }

           if(Range == "M") {
                StartDate = "<?php echo  date("Y-m-1"); ?>";
                EndDate = "<?php echo  date("Y-m-t"); ?>";
                RangeName = "monthly";
           }

           if(Range == "CR") {
                StartDate = jQuery("#start_date").val();
                EndDate = jQuery("#end_date").val();
                if(StartDate == "") {
                    jQuery("#start_date").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any start date.", "appointzilla"); ?></strong></span>');
                    return false;
                }
                if(EndDate == "") {
                    jQuery("#end_date").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any end date.", "appointzilla"); ?></strong></span>');
                    return false;
                }
                RangeName = "custom-range";
           }
            if(RangeName == "") RangeName = "all";
            var Url = location.href;
            var FileName = RangeName + "-" + "<?php echo 'appointments-list_'.date('d-m-Y_h-i-s').'.csv'; ?>";
            jQuery("#loading-img").show();
            jQuery.ajax({
                type: "POST",
                url: Url,
                data: "ListType=appointments-list"  +  "&Range=" + Range + "&StartDate=" + StartDate + "&EndDate=" + EndDate + "&FileName=" + FileName,
                success: function(ReturendData) {
                    jQuery("#download-btn-div").show();
                    location.href = location.href;
                },
                complete: function() {
                    jQuery("#select_range").prop('disabled', true);
                    jQuery("#custom-dates1").hide();
                    jQuery("#custom-dates2").hide();
                },
                error: function(error) {
                    alert(error);
                }
            });

        });


       //client list validation and js works
        jQuery('#select_clients_range').change(function() {
            var ClientsRange = jQuery('#select_clients_range').val();
            if(ClientsRange == "CC") {
                jQuery("#clients-custom-dates1").show();
                jQuery("#clients-custom-dates2").show();
            } else {
                jQuery("#clients-custom-dates1").hide();
                jQuery("#clients-custom-dates2").hide();
            }
        });

        jQuery('#clients-start-date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        jQuery('#clients-end-date').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        jQuery('#export-clients-lists').click(function(){

            jQuery(".apcal_error").hide();
            var StartDate, EndDate;
            var RangeName = "";

            var ClientsRange = jQuery('#select_clients_range').val();
            if(ClientsRange == 0) {
                jQuery("#select_clients_range").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any range.", "appointzilla"); ?></strong></span>');
                return false;
            }

            if(ClientsRange == "AC") {
                StartDate = "<?php echo date("Y-m-d"); ?>";
                EndDate = "<?php echo date("Y-m-d"); ?>";
                RangeName = "all";
            }

            if(ClientsRange == "CC") {
                StartDate = jQuery("#clients-start-date").val();
                EndDate = jQuery("#clients-end-date").val();
                if(StartDate == "") {
                    jQuery("#clients-start-date").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any start date.", "appointzilla"); ?></strong></span>');
                    return false;
                }
                if(EndDate == "") {
                    jQuery("#clients-end-date").after('<span class="apcal_error">&nbsp;<br><strong><?php _e("Select any end date.", "appointzilla"); ?></strong></span>');
                    return false;
                }
                RangeName = "custom-range";
            }

            if(RangeName == "") RangeName = "all";
            var Url = location.href;
            var FileName = RangeName + "-" + "<?php echo 'clients-list_'.date('d-m-Y_h-i-s').'.csv'; ?>";
            jQuery("#client-loading-img").show();
            jQuery.ajax({
                type: "POST",
                url: Url,
                data: "ListType=clients-list"  +  "&Range=" + ClientsRange + "&StartDate=" + StartDate + "&EndDate=" + EndDate + "&FileName=" + FileName,
                success: function(ReturendData) {
                    jQuery("#download-btn-div").show();
                    location.href = location.href + "&activetab=clients";
                },
                complete: function() {

                    jQuery("#select_clients_range").prop('disabled', true);
                    jQuery("#clients-custom-dates1").hide();
                    jQuery("#clients-custom-dates2").hide();
                },
                error: function(error) {
                    alert(error);
                }
            });

        });

    });
    </script>
</div>

<?php
// delete all appointments file
if(isset($_GET["delete-appointment-file"])) {
    $DirName = "appointments-lists";
    $DirPath = "../wp-content/".$DirName;
    $File = $_GET["delete-appointment-file"];

    if($File != 'all') {
        $FilePath = $DirPath."/".$File;
        if( file_exists($FilePath) ) {
            if(unlink($FilePath)) {
                ?><script> alert("<?php _e('List file successfully deleted.', 'appointzilla'); ?>"); location.href = '?page=export-lists'; </script><?php

            } else {
                ?><script> alert("<?php _e('Unable to delete list or list file not exist.', 'appointzilla'); ?>"); location.href = '?page=export-lists'; </script><?php
            }
        }
    } else {
        //delete all files from appointments-lists directory
        if( file_exists($DirPath) ) {
            $AllFiles = scandir($DirPath, 1);
            foreach($AllFiles as $File) {
                if ($File != "." && $File != "..") {
                    $FilePath = $DirPath."/".$File;
                    // delete all files
                    if( file_exists($FilePath) ) { unlink($FilePath); }
                }
            }
            ?><script> alert("<?php _e('All list file(s) successfully deleted.', 'appointzilla'); ?>"); location.href = '?page=export-lists'; </script><?php
        }
    }
}

//delete client file
if(isset($_GET["delete-client-file"])) {
    $DirName = "clients-lists";
    $DirPath = "../wp-content/".$DirName;
    $File = $_GET["delete-client-file"];

    if($File != 'all') {
        $FilePath = $DirPath."/".$File;
        if( file_exists($FilePath) ) {
            if(unlink($FilePath)) {
                ?><script> alert("<?php _e('List file successfully deleted.', 'appointzilla'); ?>"); location.href = '?page=export-lists'; </script><?php
            } else {
                ?><script> alert("<?php _e('Unable to delete list or list file not exist.', 'appointzilla'); ?>"); location.href = '?page=export-lists&activetab=clients'; </script><?php
            }
        }
    } else {
            //delete all files from appointments-lists directory
        if( file_exists($DirPath) ) {
            $AllFiles = scandir($DirPath, 1);
            foreach($AllFiles as $File) {
                if ($File != "." && $File != "..") {
                    $FilePath = $DirPath."/".$File;
                    // delete all files
                    if( file_exists($FilePath) ) { unlink($FilePath); }
                }
            }
            ?><script> alert("<?php _e('All list file(s) successfully deleted.', 'appointzilla'); ?>"); location.href = '?page=export-lists&activetab=clients'; </script><?php
        }
    }
}?>