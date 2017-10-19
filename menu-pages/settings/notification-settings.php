<?php
//Update calendar settings Form
if(isset($_GET['managenotification']) == 'yes') {
    require_once('manage-notification-settings.php');
} else {

     //Saving Notification Settings
     if(isset($_POST['savenotificationsettings'])) {
        if(isset($_POST['enable'])) {
            //wp-mail
            if($_POST['emailtype'] == 'wpmail') {
                update_option('emailstatus', $_POST['enable']);
                update_option('emailtype', $_POST['emailtype']);

                $EmailDetails =  array ( 'wpemail' => $_POST['wpemail'] );
                update_option( 'emaildetails', $EmailDetails);
            }

            //php-mail
            if($_POST['emailtype'] == 'phpmail') {
                update_option('emailstatus', $_POST['enable']);
                update_option('emailtype', $_POST['emailtype']);
                $EmailDetails =  array ( 'phpemail' => $_POST['phpemail']);
                update_option('emaildetails', $EmailDetails);
            }

            //smtp mail
            if($_POST['emailtype'] == 'smtp') {
                update_option('emailstatus', $_POST['enable']);
                update_option('emailtype', $_POST['emailtype']);
                $EmailDetails =  array ( 'hostname' => $_POST['hostname'],
                                         'portno' => 	$_POST['portno'],
                                         'smtpemail' => $_POST['smtpemail'],
                                         'password' => $_POST['password'],
                                );
                update_option('emaildetails', $EmailDetails);
            }
            if(isset($_POST['staffnotificationstatus'])) {
                //staff notification 'ON'
                update_option('staff_notification_status', $_POST['staffnotificationstatus']);
            } else {
                update_option('staff_notification_status', 'off');
            }

        } else {
            update_option('emailstatus', 'off');
            update_option('staff_notification_status', 'off');
            update_option('emailtype', 'none');
            $EmailDetails =  array ( );
            update_option('emaildetails', $EmailDetails);
            delete_option('emaildetails');
        }
        echo "<script>alert('". __('Notification settings successfully updated.','appointzilla') . "');</script>";
        echo "<script> location.href='?page=app-calendar-settings&show=notificationsettings'</script>";
     }?>

    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Notification Settings','appointzilla'); ?></h3>
    </div>
    <table width="100%" class="table">
        <tr>
            <th colspan="2" scope="row"><?php _e('Notification','appointzilla'); ?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="81%">
                <em>
                    <?php
                    if(get_option('emailstatus') == 'on') {
                        echo _e("ON",'appointzilla');
                    } else {
                        echo _e("OFF",'appointzilla');
                    }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th colspan="2" scope="row"><?php _e('Staff Notification','appointzilla'); ?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="81%">
                <em>
                    <?php
                    if(get_option('staff_notification_status') == 'on') {
                        echo _e("ON",'appointzilla');
                    } else {
                        echo _e("OFF",'appointzilla');
                    }
                    ?>
                </em>
            </td>
        </tr>

        <tr>
            <th colspan="2" scope="row"><?php _e('Notification Type','appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                    <?php $emailtype =  get_option('emailtype');
                        if($emailtype) {
                            echo strtoupper($emailtype);
                        } else {
                            echo _e("Not Available.",'appointzilla');
                        }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th colspan="2" scope="row"><?php _e('Details','appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                    <?php $emaildetails =  get_option('emaildetails');
                        if($emaildetails) {
                            $emaildetails = $emaildetails;
                        } else {
                            echo _e("Not Available.",'appointzilla');
                        }
                    ?>
                </em>
            </td>
        </tr>
        <?php if($emailtype == 'wpmail') {?>
        <tr>
            <th scope="row">&nbsp;</th>
            <td scope="row"><?php _e('WP Email','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['wpemail']) echo $emaildetails['wpemail']; else echo _e("Not Available.",'appointzilla'); ?></em></td>
        </tr>
        <?php } ?>

        <?php if($emailtype == 'phpmail') {?>
        <tr>
            <th scope="row">&nbsp;</th>
            <td scope="row"><?php _e('PHP Email','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['phpemail']) echo $emaildetails['phpemail']; else echo _e("Not Available.",'appointzilla');  ?></em></td>
        </tr>
        <?php } ?>

        <?php if($emailtype == 'smtp') {?>
        <tr>
            <th width="6%" scope="row">&nbsp;</th>
            <td width="8%" scope="row"><?php _e('Host Name','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['hostname']) echo $emaildetails['hostname']; else echo _e("Not Available.",'appointzilla');  ?></em></td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td scope="row"><?php _e('Port Number','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['portno']) echo $emaildetails['portno']; else echo _e("Not Available.",'appointzilla'); ?></em></td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td scope="row"><?php _e('Email','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['smtpemail']) echo $emaildetails['smtpemail']; else echo _e("Not Available.",'appointzilla'); ?></em></td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td scope="row"><?php _e('Password','appointzilla'); ?></td>
            <td><strong>:</strong></td>
            <td><em><?php if($emaildetails['password']) echo "******"; else echo _e("Not Available.",'appointzilla'); ?></em></td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="2" scope="row">&nbsp;</th>
            <td>&nbsp;</td>
            <td><a href="?page=app-calendar-settings&show=notificationsettings&managenotification=yes" class="btn btn-primary"><?php _e('Manage Settings','appointzilla'); ?></a></td>
        </tr>
    </table>
<?php
}// end og display current notification settings
?>