<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>

<div class="bs-docs-example tooltip-demo">
    <?php global $wpdb;
    $DateFormat = get_option('apcal_date_format');
    // add new cliens and update clients
    if(isset($_GET['updateclient'])) {
        $updateclient=$_GET['updateclient'];
        $table_name = $wpdb->prefix . "ap_clients";
        $UpdateClientDetail= $wpdb->get_row("SELECT * FROM `$table_name` WHERE `id` = '$updateclient'"); ?>
        <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
            <h3><i class="fa fa-group"></i> <?php _e('Manage Client','appointzilla'); ?></h3>
        </div>

        <form action="" method="post" name="client-manage">  <!--clint left box -->
            <table width="100%" class="detail-view table table-striped table-condensed">
                <tr><th width="15%"><?php _e('Name','appointzilla'); ?> </th>
                <td width="4%"><strong>:</strong></td>
                <td width="81%"><input type="text" name="client_name" id="client_name" value="<?php if($UpdateClientDetail) echo $UpdateClientDetail->name; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Name.','appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>			</td></tr>
                <tr><th><?php _e('Email','appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input type="text" name="client_email" id="client_email" value="<?php if($UpdateClientDetail) echo $UpdateClientDetail->email; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Email.','appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>			</td></tr>
                <tr><th><?php _e('Phone','appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input type="text" name="client_phone" id="client_phone" value="<?php if($UpdateClientDetail) echo $UpdateClientDetail->phone; ?>" maxlength="12"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Phone Number.','appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>			</td></tr>
                <tr>
                  <th><?php _e('Special Note','appointzilla'); ?> </th>
                  <td><strong>:</strong></td>
                <td><textarea type="text" name="client_desc" id="client_desc"><?php if($UpdateClientDetail) echo $UpdateClientDetail->note; ?></textarea>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Client Note.','appointzilla'); ?>" ><i  class="icon-question-sign"></i></a></td></tr>
                <tr><td></td><td></td><td>
                <?php if($updateclient=='new')
                    { ?>
                <button name="clientcreate" class="btn" type="submit" id="clientcreate"><i class="icon-ok"></i> <?php _e('Create','appointzilla'); ?> </button>
                <?php } else { ?>
                <button name="clientupdate" class="btn" type="submit" value="<?php if($UpdateClientDetail) echo $UpdateClientDetail->id; ?>" id="clientupdate"><i class="icon-pencil"></i> <?php _e('Update','appointzilla'); ?></button>
                <?php } ?>
                <a href="?page=client" class="btn"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla'); ?></a>
              </td></tr>
          </table>
        </form><?php
     } // end of update if


    // view of clients details
    if(isset($_GET['viewid'])) {
        $clientid = $_GET['viewid'];
        $table_name = $wpdb->prefix . "ap_clients";
        $ClientDetails = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` ='$clientid'"); ?>
        <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><i class="fa fa-group"></i> <?php _e('View Client','appointzilla');?> - <?php echo $ClientDetails->name; ?></h3></div>
            <div style="float:left; width:28%; height:auto; border:0px solid #000000;" id="left_client_box">
                <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"> <h3> <?php _e('Client Details','appointzilla'); ?>  </h3> </div>
                <table width="100%" class="detail-view table table-striped table-condensed">
                    <tr>
                        <th width="25%"><?php _e('Name','appointzilla'); ?> </th>
                        <td width="5%"><strong>:</strong></td>
                        <td width="70%"><?php echo ucwords($ClientDetails->name); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Email','appointzilla'); ?> </th><td><strong>:</strong></td>
                        <td><?php echo strtolower($ClientDetails->email); ?></td></tr>
                    <tr><th><?php _e('Phone','appointzilla'); ?></th><td><strong>:</strong></td>
                        <td><?php echo $ClientDetails->phone; ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Special Note','appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><?php echo ucfirst($ClientDetails->note); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td><a class="btn" href="?page=client"><i class="icon-arrow-left"></i> <?php _e('Back','appointzilla'); ?></a></td>
                    </tr>
                </table>
            </div>

            <div style="float:right; width:70%; height:auto; border:0px solid #000000;" id="right_client_box">
                <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"> <h3>
                   <?php if(isset($_GET['appviewid']))  _e('Client Detailed Appointment History','appointzilla'); else  _e('All Appointment History','appointzilla'); ?> </h3>
                </div>
                 <?php
                 //View appointment details
                 if(isset($_GET['appviewid'])) {
                     $appid = $_GET['appviewid'];
                     if(isset($_GET['updateclient'])) $fromback =$_GET['updateclient'];
                     $table_name = $wpdb->prefix . "ap_appointments";
                      $appdetails = "SELECT * FROM $table_name WHERE `id` ='$appid'";
                     $appdetails = $wpdb->get_row($appdetails); ?>
                    <table width="100%" class="detail-view table table-striped table-condensed">
                        <tr>
                             <th scope="row"><?php _e('Appointment Creation Date', 'appointzilla'); ?></th>
                             <td><strong>:</strong></td>
                             <td><?php echo date($DateFormat." h:i:s", strtotime("$appdetails->book_date")); ?></td>
                        </tr>
                        <tr>
                            <th width="28%" scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                            <td width="4%"><strong>:</strong></td>
                            <td width="68%"><em><?php echo ucwords($appdetails->name); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo $appdetails->email; ?></em></td>
                        </tr>
                        <tr><th scope="row"><?php _e('Service', 'appointzilla'); ?></th><td><strong>:</strong></td>
                            <td>
                                <em>
                                <?php $table_name = $wpdb->prefix . "ap_services";
                                $ServiceDetails = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` ='$appdetails->service_id'");
                                echo ucwords($ServiceDetails->name);
                                ?>
                                </em>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Staff', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td>
                                <em>
                                <?php $staff_table_name = $wpdb->prefix . "ap_staff";
                                $staffdetails= $wpdb->get_row("SELECT * FROM $staff_table_name WHERE `id` ='$appdetails->staff_id'");
                                echo ucwords($staffdetails->name);
                                ?>
                                </em>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo $appdetails->phone; ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Start Time', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo $appdetails->start_time; ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('End Time', 'appointzilla'); ?> </th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo $appdetails->end_time; ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Date', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo date($DateFormat, strtotime($appdetails->date)); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Description', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo ucfirst($appdetails->note); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Appointment Key', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo $appdetails->appointment_key; ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Status', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo _e(ucfirst($appdetails->status), 'appointzilla');?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Repeat', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo _e(ucfirst($appdetails->recurring), 'appointzilla'); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Repeat Type', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo _e(ucfirst($appdetails->recurring_type), 'appointzilla'); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Repeat Start Date', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo date($DateFormat, strtotime($appdetails->recurring_st_date)); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Repeat End Date', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo date($DateFormat, strtotime($appdetails->recurring_ed_date)); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Appointment By', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo _e(ucfirst($appdetails->appointment_by), 'appointzilla'); ?></em></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Payment Status', 'appointzilla'); ?></th>
                            <td><strong>:</strong></td>
                            <td><em><?php echo _e(ucfirst($appdetails->payment_status), 'appointzilla');?></em></td>
                        </tr>
                        <tr>
                            <th scope="row">&nbsp;</th><td>&nbsp;</td>
                            <td><a href="?page=client-manage&viewid=<?php echo $_GET['viewid']; ?>" class="btn"><i class="icon-arrow-left"></i> <?php _e('Back', 'appointzilla'); ?></a></td>
                        </tr>
                </table> <!--end of view appointment -->
                <?php
                } else { ?>
                    <table width="100%" class="detail-view table table-striped table-condensed">
                        <tr>
                            <th width="10%"><?php _e('No.','appointzilla'); ?> </th>
                            <th width="13%"><?php _e('Name','appointzilla'); ?> </th>
                            <th width="15%"><?php _e('Staff','appointzilla'); ?> </th>
                            <th width="19%"><?php _e('Date','appointzilla'); ?> </th>
                            <th width="21%"><?php _e('Time','appointzilla'); ?> </th>
                            <th width="15%"><?php _e('Service','appointzilla'); ?> </th>
                            <th width="7%"><?php _e('Action','appointzilla'); ?> </th>
                        </tr>
                        <?php $findapp = $ClientDetails->email;
                          $appointment_table_name= $wpdb->prefix . "ap_appointments";
                          $toat_app_query = "SELECT * FROM `$appointment_table_name` WHERE `email` = '$findapp';";
                          $AllAppointments = $wpdb->get_results($toat_app_query);
                        if($AllAppointments) {
                            $i=1;
                            foreach($AllAppointments as $appointment) { ?>
                                <tr>
                                    <td>
                                        <em>
                                            <?php echo $i."."; ?></em></td>	<td><em><?php echo ucfirst($appointment->name); ?>
                                        </em>
                                    </td>
                                    <td>
                                        <em>
                                        <?php $staffid = $appointment->staff_id;
                                            $staff_table_name = $wpdb->prefix . "ap_staff";
                                            $staff_details= $wpdb->get_row("SELECT * FROM $staff_table_name WHERE `id` ='$staffid'");
                                            echo ucfirst($staff_details->name);
                                        ?>
                                        </em>
                                    </td>
                                    <td>
                                        <em>
                                            <?php
                                            if($appointment->recurring == 'yes') {
                                                echo date($DateFormat, strtotime($appointment->recurring_st_date))."-".date($DateFormat, strtotime($appointment->recurring_ed_date));
                                            } else {
                                                echo date($DateFormat, strtotime($appointment->date));
                                            }?>
                                        </em>
                                    </td>

                                    <td>
                                        <em><?php echo date("g:ia", strtotime($appointment->start_time))."-".date("g:ia", strtotime($appointment->end_time)); ?></em>
                                    </td>
                                    <td>
                                        <em>
                                            <?php $apppid=$appointment->service_id;
                                                $table_name = $wpdb->prefix . "ap_services";
                                                $ServiceDetails = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` ='$apppid'");
                                                echo ucfirst($ServiceDetails->name); ?>
                                        </em>
                                    </td>
                                    <td>
                                        <a title="<?php _e('View','appointzilla'); ?>" rel="tooltip" href="?page=client-manage&appviewid=<?php echo $appointment->id; ?>&viewid=<?php echo $clientid; ?>"><i class="icon-eye-open"></i></a>
                                    </td>
                                </tr>
                                <?php $i++;
                            } //end of foreach appointment
                        } else { ?>
                                <tr>
                                    <td colspan="7"><?php _e('Sorry! No appointment(s) available for this client.','appointzilla'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                </tr><?php
                        } ?><!--end of appointment if -->
                    </table><?php
                } ?>
            </div><?php
    }

    // Add new client
    if(isset($_POST['clientcreate'])) {
        global $wpdb;
        $client_name = strip_tags($_POST['client_name']);
        $client_email = $_POST['client_email'];
        $client_phone = $_POST['client_phone'];
        $client_desc = strip_tags($_POST['client_desc']);

        $client_table = $wpdb->prefix."ap_clients";
        $ExitsClientDetails = $wpdb->get_row("SELECT * FROM `$client_table` WHERE `email` = '$client_email' ");
        if($ExitsClientDetails) {
            echo "<script>alert('$client_email ".__('is already in the database.','appointzilla')."')</script>";
        } else {
            // insert new client deatils
            $insert_client = "INSERT INTO `$client_table` (`id` ,`name` ,`email` ,`phone` ,`note`) VALUES ('NULL', '$client_name', '$client_email', '$client_phone', '$client_desc');";
            if($wpdb->query($insert_client)) {
                //$clientmessage = "New Client $client_name:($client_email) successfully added.";
                echo "<script>alert('".__('New client successfully added.','appointzilla')."')</script>";
                echo "<script>location.href='?page=client';</script>";
            }
        }
    }


    //update client
    if(isset($_POST['clientupdate'])) {
        global $wpdb;
        $client_name = strip_tags($_POST['client_name']);
        $client_email = $_POST['client_email'];
        $client_phone = $_POST['client_phone'];
        $client_desc = strip_tags($_POST['client_desc']);
        $client_up_id = $_POST['clientupdate'];
        $table_name = $wpdb->prefix . "ap_clients";
        $ExitsClientDetails = $wpdb->get_row("SELECT * FROM `$table_name` WHERE `email` = '$client_email' ");
        if($ExitsClientDetails) {
            if($ExitsClientDetails->id == $client_up_id) {
                $update_client="UPDATE `$table_name` SET `name` = '$client_name', `email` = '$client_email', `phone` = '$client_phone', `note` = '$client_desc' WHERE `id` ='$client_up_id';";
                if($wpdb->query($update_client)) {
                    echo "<script>alert('".__('Client details successfully updated.','appointzilla')."');</script>";
                    echo "<script>location.href='?page=client-manage&viewid=$client_up_id';</script>";
                } else {
                    echo "<script>alert('".__('Client details successfully updated.','appointzilla')."');</script>";
                    echo "<script>location.href='?page=client-manage&viewid=$client_up_id';</script>";
                }
            } else {
                echo "<script>alert('$client_email ".__('is alredy in the database.','appointzilla')."');</script>";
            }
        } else {
            $update_client="UPDATE `$table_name` SET `name` = '$client_name', `email` = '$client_email', `phone` = '$client_phone', `note` = '$client_desc' WHERE `id` ='$client_up_id';";
            if($wpdb->query($update_client)) {
                echo "<script>alert('".__('Client details successfully updated.','appointzilla')."');</script>";
                echo "<script>location.href='?page=client-manage&viewid=$client_up_id';</script>";
            } else {
                echo "<script>alert('".__('Client details successfully updated.','appointzilla')."');</script>";
                echo "<script>location.href='?page=client-manage&viewid=$client_up_id';</script>";
            }
        }
    } ?>

    <style type="text/css"> .error{  color:#FF0000; } </style>
    <script type="text/javascript">
    jQuery(document).ready(function () {
        // form submit validation js
        jQuery('form').submit(function() {

            jQuery('.error').hide();
            var client_name = jQuery("input#client_name").val();
            if (client_name== "") {
                jQuery("#client_name").after('<span class="error">&nbsp;<br><strong><?php _e('Client name cannot be blank.','appointzilla'); ?></strong></span>');
                return false;
            } else {
                var client_name = isNaN(client_name);
                if(client_name== false) {
                    jQuery("#client_name").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.','appointzilla'); ?></strong></span>');
                    return false;
                }
            }

            var client_email = jQuery("input#client_email").val();
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (client_email== "") {
                jQuery("#client_email").after('<span class="error">&nbsp;<br><strong> <?php _e('Email cannot be blank.','appointzilla'); ?></strong></span>');
                return false;
            } else {
                if(regex.test(client_email) == false ) {
                    jQuery("#client_email").after('<span class="error">&nbsp;<br><strong><?php _e('invalid  value.','appointzilla'); ?></strong></span>');
                    return false;
                }
            }

            var client_phone = jQuery("input#client_phone").val();
            if (client_phone== "") {
                jQuery("#client_phone").after('<span class="error">&nbsp;<br><strong> <?php _e('Phone Number cannot be blank.','appointzilla'); ?></strong></span>');
                return false;
            } else {
                var client_phone = isNaN(client_phone);
                if(client_phone== true) {
                    jQuery("#client_phone").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.','appointzilla'); ?></strong></span>');
                    return false;
                }
            }
        });
    });
    </script>
</div>