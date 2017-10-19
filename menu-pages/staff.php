<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><i class="fa fa-male"></i> <i class="fa fa-female"></i> <?php _e('Staff','appointzilla');?></h3></div>
    <?php global $wpdb;
    //get all group list
    $StaffGroupsTable = $wpdb->prefix . "ap_staff_groups";
    $StaffGoups = $wpdb->get_results("SELECT * FROM `$StaffGroupsTable`");
    foreach($StaffGoups as $GroupName) { ?>
        <table width="100%" class="table table-hover">
            <thead>
                <tr style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <th colspan="4">
                         <div id="gruopnamedivbox<?php echo $GroupName->id; ?>"><?php if($GroupName->name == 'Default') echo _e('Default', 'appointzilla'); else echo ucfirst($GroupName->name); ?></div>
                         <div id="gruopnameedit<?php echo $GroupName->id; ?>" style="display:none; height:25px;">
                            <form method="post">
                                <input type="text" id="editgruopname" class="inputheight" name="editgruopname" value="<?php echo $GroupName->name; ?>"/>
                                <button id="editgruop" value="<?php echo $GroupName->id; ?>" name="editgruop" type="submit" class="btn"  ><i class="icon-ok"></i> <?php _e('Save','appointzilla'); ?></button>
                                <button id="editgruopcancel" type="button" class="btn" onclick="canceleditgrup(<?php echo $GroupName->id; ?>)"><i class="icon-remove"></i> <?php  _e('Cancel','appointzilla'); ?></button>
                            </form>
                         </div>
                    </th>
                    <th colspan="3">
                        <!--- header rename and delete button right box-->
                        <div align="right" style="margin-right:25px;">
                            <?php if($GroupName->id !='0') { ?>
                            <a rel="tooltip" class="btn btn-small btn-danger" href="#" id="<?php echo $GroupName->id; ?>" onclick="editgruop(<?php echo $GroupName->id; ?>)" title="<?php _e('Rename Group','appointzilla'); ?>"><?php _e('Rename','appointzilla'); }?></a>
                            <?php if($GroupName->id !='1') { echo"|"; ?>
                            <a rel="tooltip" href="?page=staff&gid=<?php echo $GroupName->id; ?>"  onclick="return confirm('<?php _e('Do you want to delete this Group?','appointzilla'); ?>')" title="<?php _e('Delete Group','appointzilla'); ?>" ><?php _e('Delete','appointzilla'); ?></a>
                            <?php } ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th><strong><?php _e('Staff Name','appointzilla'); ?></strong></th>
                    <th><strong><?php _e('Email','appointzilla'); ?></strong></th>
                    <th><strong><?php _e('Phone','appointzilla'); ?></strong></th>
                    <th><strong><?php _e('Experience','appointzilla'); ?></strong></th>
                    <th><strong><?php _e('Appointment Color','appointzilla'); ?></strong></th>
                    <th style="text-align: center;"><strong><?php _e('Google Calendar Sync','appointzilla'); ?></strong></th>
                    <th><strong><?php _e('Action','appointzilla'); ?></strong></th>
                </tr>
            </thead>
            <tbody>
                    <?php // get service list group wise
                    $table_name = $wpdb->prefix . "ap_staff";
                    $StaffDetails = $wpdb->get_results("SELECT * FROM $table_name WHERE `group_id` ='$GroupName->id'");
                    foreach($StaffDetails as $staff) {
                        $StaffId = $staff->id;
                    ?>
                <tr class="odd" style="border-bottom:1px;">
                    <td><em><?php echo ucwords($staff->name); ?></em></td>
                    <td> <em><?php echo $staff->email ; ?></em> </td>
                    <td><em><?php echo $staff->phone; ?></em></td>
                    <td><em><?php echo $staff->experience." "; echo _e("Year(s)",'appointzilla'); ?></em></td>
                    <td><span style="background-color: <?php echo $staff->color; ?>; padding: 6px; border-radius: 2px;"><em><?php echo strtoupper($staff->color); ?></em></span></td>
                    <td style="text-align: center;">
                        <?php $TokenData = get_option("google_calendar_token_details_".$StaffId);
                        count($TokenData);
                        if(isset($TokenData->access_token) && isset($TokenData->refresh_token)) {
                            ?>
                            <em>
                                <a class="btn btn-small btn-success" href="?page=staff-google-calendar-sync&staff-id=<?php echo $StaffId; ?>"><i class="fa fa-link"></i> <?php _e("Connected To Google","appointzilla") ?></a><?php //echo $staff->phone; ?>
                            </em>
                            <?php
                        } else {
                        ?>
                            <em>
                                <a class="btn btn-small btn-info" href="?page=staff-google-calendar-sync&staff-id=<?php echo $StaffId; ?>"><i class="fa fa-hand-o-right"></i> <?php _e("Setup Google Sync","appointzilla") ?></a><?php //echo $staff->phone; ?>
                            </em>
                        <?php }?>
                    </td>
                    <td class="button-column">
                        <a rel="tooltip" href="?page=manage-staff&viewid=<?php echo $staff->id; ?>" title="<?php _e('View','appointzilla'); ?>"><i class="icon-eye-open"></i></a>&nbsp;
                        <a rel="tooltip" href="?page=manage-staff&staffupdateid=<?php echo $staff->id; ?>" title="<?php _e('Update','appointzilla'); ?>"><i class="icon-pencil"></i></a>&nbsp;
                        <?php if($staff->id!= 1)  { ?>
                        <a rel="tooltip" href="?page=staff&sid=<?php echo $staff->id; ?>" onclick="return confirm('<?php _e('Do you want to delete this Staff?','appointzilla'); ?>')" title="<?php _e('Delete','appointzilla'); ?>" >
                        <i class="icon-remove"></i>	<?php } ?>
                    </td>
                </tr>
                        <?php } ?>
                <tr>
                    <td colspan="8">
                        <strong><a href="?page=manage-staff&staffupdateid=new&grouid=<?php echo $GroupName->id; ?>" rel="tooltip" title="<?php _e("Add New Staff to this Group",'appointzilla'); ?>"><i class="icon-plus"></i> <?php _e("Add New Staff to this Group",'appointzilla'); ?></a></strong>
                    </td>
                </tr>
            </tbody>
        </table><?php
    } ?>

    <!---New group div box--->
    <div id="gruopbuttonbox">
        <a class="btn btn-primary" href="#" rel="tooltip" class="Create Group" onclick="creategruopname()"><i class="icon-plus icon-white"></i> <?php _e('Create New Staff Group','appointzilla'); ?> </a></u>
    </div>

    <div style="display:none;" id="gruopnamebox">
        <form method="post">
            <?php _e('Staff Group name','appointzilla'); ?> : <input type="text" id="gruopname" name="gruopname" class="inputheight" />
            <button style="margin-bottom:10px;" id="CreateGruop" type="submit" class="btn" name="CreateGruop"><i class="icon-ok"></i> <?php _e('Create Group','appointzilla'); ?></button>
            <button style="margin-bottom:10px;" id="CancelGruop" type="button" class="btn" name="CancelGruop" onclick="cancelgrup()"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla'); ?></button>
        </form>
    </div>
    <!---New group div box end--->

    <?php //insert new staff group
    if(isset($_POST['CreateGruop'])) {
        global $wpdb;
        $GroupeName = strip_tags($_POST['gruopname']);
        $table_name = $wpdb->prefix . "ap_staff_groups";
        $staff_group = "INSERT INTO $table_name ( `name` ) VALUES ('$GroupeName');";
        if($wpdb->query($staff_group)) {
            echo "<script>alert('".__('New staff group successfully created.','appointzilla')."')</script>";
            echo "<script>location.href='?page=staff';</script>";
        }
    }

    // Rename staff group
    if(isset($_POST['editgruop'])) {
        $table_name = $wpdb->prefix . "ap_staff_groups";
        $update_id = $_POST['editgruop'];
        $update_name = strip_tags($_POST['editgruopname']);
        if($update_name) {
            if(!is_numeric($update_name)) {
                $update_app_query = "UPDATE $table_name SET `name` = '$update_name' WHERE `id` ='$update_id';";
                if($wpdb->query($update_app_query)) {
                    echo "<script>alert('".__('Staff group successfully renamed.','appointzilla')."')</script>";
                    echo "<script>location.href='?page=staff';</script>";
                }
            } else {
                echo "<script>alert('".__('Invalid group name','appointzilla')."');</script>";
            }
        } else {
            echo "<script>alert('".__('Group name cannot be blank.','appointzilla')."');</script>";
        }
    }

    // Delete staff group
    if(isset($_GET['gid'])) {
        $DeleteId = $_GET['gid'];
        $table_name = $wpdb->prefix . "ap_staff_groups";
        $delete_app_query="DELETE FROM `$table_name` WHERE `id` = '$DeleteId';";
        if($wpdb->query($delete_app_query)) {
            // Update all staff group id
            $table_name = $wpdb->prefix . "ap_staff";
            $update_app_query_staff = "UPDATE `$table_name` SET `group_id` = '1' WHERE `group_id` ='$DeleteId';";
            $wpdb->query($update_app_query_staff); // update group
            echo "<script>alert('".__('Staff group successfully deleted.','appointzilla')."')</script>";
            echo "<script>location.href='?page=staff';</script>";
        }
    }

    // Delete staff
    if(isset($_GET['sid'])) {
        global $wpdb;
        $deletesid = $_GET['sid'];
        $StaffTable = $wpdb->prefix . "ap_staff";
        $ServiceTable = $wpdb->prefix . "ap_services";
        // StaffDetail use when update each service staff_id
        $StaffDetail = $wpdb->get_row("SELECT `name` FROM `$StaffTable` WHERE `id` = '$deletesid'");
        $delete_app_query = "DELETE FROM `$StaffTable` WHERE `id` = '$deletesid';";
        if($wpdb->query($delete_app_query)) {
            // fetch all service staff_ids
            $AllService = $wpdb->get_results("SELECT * FROM `$ServiceTable`", OBJECT);
            foreach($AllService as $Service) {
                $pk = $Service->id;
                $unserlized = unserialize($Service->staff_id);
                $pos = array_search($deletesid , $unserlized);
                if($pos) {
                    unset($unserlized[$pos]);
                    // again serlized staff_id and store
                    $serlized = serialize($unserlized);
                    $wpdb->query("UPDATE `$ServiceTable` SET `staff_id` = '$serlized' WHERE `id` ='$pk'");
                }
            }

            // Update all staff id  in appointment data base n assign default staff id
            $app_table_name = $wpdb->prefix . "ap_appointments";
            $update_app_query_appointment = "UPDATE $app_table_name SET `staff_id` = '1' WHERE `staff_id` ='$deletesid';";
            $wpdb->query($update_app_query_appointment); // update all appointment
            echo "<script>alert('".__('Staff successfully deleted.','appointzilla')."')</script>";
            echo "<script>location.href='?page=staff';</script>";
        }
    } ?>
    <!--css & js  work-->
    <style type="text/css">
    .error{
        color:#FF0000;
    }
    input.inputheight {
        height:30px;
    }
    #editgruop {
        margin-bottom:10px;
    }
    #editgruopcancel {
        margin-bottom:10px;
    }
    </style>

    <script type="text/javascript">
    //edit gruop hide and show div box
    function editgruop(id) {
        var gneb = '#gruopnamedivbox'+id;
        var gne = '#gruopnameedit'+id;
        jQuery(gneb).hide();
        jQuery(gne).show();
    }

    function canceleditgrup(id) {
        var gneb='#gruopnamedivbox'+id;
        var gne='#gruopnameedit'+id;
        jQuery(gneb).show();
        jQuery(gne).hide();
    }

    //gruop create and  hide  or show div box ajax post data
    function creategruopname() {
        jQuery('#gruopnamebox').show();
        jQuery('#gruopbuttonbox').hide();
    }
    function cancelgrup() {
        jQuery('#gruopnamebox').hide();
        jQuery('#gruopbuttonbox').show();
    }

    jQuery(document).ready(function () {
        // create new gruop js
        jQuery('#CreateGruop').click(function() {

            jQuery('.error').hide();

            var gruopname = jQuery("input#gruopname").val();
            if (gruopname == "") {
                jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('Group name cannot be blank.','appointzilla'); ?></strong></span>');
                return false;
            } else {
                var gruopname = isNaN(gruopname);
                if(gruopname == false) {
                    jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid group name.','appointzilla'); ?></strong></span>');
                    return false;
                }
            }
            jQuery('#gruopnamebox').hide();
            jQuery('#gruopbuttonbox').show();
        });
    });
    </script>
</div>