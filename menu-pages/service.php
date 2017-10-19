<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><i class="fa fa-wrench"></i> <?php _e('Services','appointzilla');?></h3></div>
<div class="bs-docs-example tooltip-demo">
<?php
    global $wpdb;
    //get all category list
    $ServiceCategoryTable = $wpdb->prefix . "ap_service_category";
    $service_category = $wpdb->get_results("select * from `$ServiceCategoryTable`");
    foreach($service_category as $gruopname) { ?>
        <table width="100%" class="table table-hover">
            <thead>
                <tr style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <th colspan="3">
                         <div id="gruopnamedivbox<?php echo $gruopname->id; ?>"><?php if($gruopname->name == 'Default') echo _e('Default', 'appointzilla'); else echo $gruopname->name; ?></div>
                         <div id="gruopnameedit<?php echo $gruopname->id; ?>" style="display:none; height:25px;">
                             <form method="post">
                                <input type="text" id="editgruopname" class="inputheight" name="editgruopname" value="<?php echo $gruopname->name; ?>"/>
                                <button  id="editgruop" value="<?php echo $gruopname->id; ?>" name="editgruop" type="submit" class="btn"><i class="icon-ok"></i> <?php _e('Save','appointzilla');?> </button>
                                <button  id="editgruopcancel" type="button" class="btn"  onclick="canceleditgrup(<?php echo $gruopname->id; ?>)"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla');?> </button>
                             </form>
                        </div>
                    </th>
                    <th colspan="7"> <!--- header rename and delete button right box-->
                        <div align="right" style="margin-right:35px;">
                            <?php if($gruopname->id !='0') { ?>
                                <a rel="tooltip" class="btn btn-danger btn-small" href="#" id="<?php echo $gruopname->id; ?>" onclick="editgruop(<?php echo $gruopname->id; ?>)" title="<?php _e('Rename Category','appointzilla');?>"><?php _e('Rename','appointzilla');}?></a>	  <?php if($gruopname->id !='1') {echo" |"; ?>
                                <a rel="tooltip" href="?page=service&gid=<?php echo $gruopname->id; ?>"  onclick="return confirm('<?php _e('Do you want to delete this category?','appointzilla');?>')" title="<?php _e('Delete Category','appointzilla');?>" ><?php _e('Delete','appointzilla');?></a>
                            <?php } ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <td><strong><?php _e('Name','appointzilla');?></strong></td>
                    <td><strong><?php _e('Description','appointzilla');?></strong></td>
                    <td><strong> <?php _e('Duration','appointzilla');?></strong></td>
                    <td><strong><?php _e('Padding Time','appointzilla');?></strong></td>
                    <td><strong><?php _e('Cost','appointzilla');?></strong></td>
                    <!--<td><strong><?php// _e('Capacity','appointzilla');?></strong></td>-->
                    <td><strong><?php _e('Accept Payment','appointzilla');?></strong></td>
                    <td><strong><?php _e('Payment Type','appointzilla');?></strong></td>
                    <td><strong><?php _e('Amount','appointzilla');?></strong></td>
                    <td><strong><?php _e('Availability','appointzilla');?></strong></td>
                    <td><strong> <?php _e('Action','appointzilla');?></strong></td>
                </tr>
            </thead>
            <tbody><?php // get service list group wise
                        $table_name = $wpdb->prefix . "ap_services";
                        $ServiceDetails = $wpdb->get_results("SELECT * FROM $table_name WHERE `category_id` ='$gruopname->id'");
                        foreach($ServiceDetails as $service) { ?>
                <tr class="odd" style="border-bottom:1px;">
                    <td><em><?php echo ucwords($service->name); ?></em></td>
                    <td> <em><?php echo ucfirst($service->desc); ?></em> </td>
                    <td><em><?php echo $service->duration. " "; if($service->unit == 'minute') echo _e('Minute', 'appointzilla'); else echo _e('Minute', 'appointzilla');?></em></td>
                    <td><em><?php echo $service->paddingtime. " "; if($service->unit == 'minute') echo _e('Minute', 'appointzilla'); else echo _e('Minute', 'appointzilla'); ?></em></td>
                    <td>
                        <em>
                            <?php $cal_admin_currency_id = get_option('cal_admin_currency');
                                if($cal_admin_currency_id) {
                                    $CurrencyTableName = $wpdb->prefix . "ap_currency";
                                    $cal_admin_currency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency_id'");
                                    $cal_admin_currency = $cal_admin_currency->symbol;
                                } else {
                                    $cal_admin_currency = "&#36;";
                                }
                                echo $cal_admin_currency.$service->cost;
                            ?>
                        </em>
                    </td>
                    <td><em><?php if($service->accept_payment == 'yes') echo _e('Yes', 'appointzilla'); else echo _e('No', 'appointzilla'); ?></em></td>
                    <td>
                        <em>
                            <?php
                                if($service->payment_type == 'percentage') echo _e('In Percentage', 'appointzilla');
                                if($service->payment_type == 'full') echo _e('Full Payment', 'appointzilla');
                                if($service->accept_payment == 'no' || $service->accept_payment == '') echo _e('None', 'appointzilla');
                            ?>
                        </em>
                    </td>

                    <td>
                        <em>
                            <?php
                                if($service->payment_type == 'percentage') echo $service->percentage_ammount."%";
                                if($service->payment_type == 'full') echo _e('Full', 'appointzilla');
                            if($service->accept_payment == 'no' || $service->accept_payment == '') echo _e('None', 'appointzilla'); ?>
                        </em>
                    </td>
                    <td><em><?php if($service->availability == 'yes') echo _e('Yes', 'appointzilla'); else echo _e('No', 'appointzilla'); ?></em></td>
                    <td class="button-column">
                        <a rel="tooltip" href="?page=manage-service&viewid=<?php echo $service->id; ?>" data-original-title="<?php _e('View','appointzilla');?>"><i class="icon-eye-open"></i></a>&nbsp;
                        <a rel="tooltip" href="?page=manage-service&sid=<?php echo $service->id; ?>" title="<?php _e('Update','appointzilla');?>"><i class="icon-pencil"></i></a> &nbsp;
                        <?php if($service->id != 1 )  { ?>
                        <a rel="tooltip" href="?page=service&sid=<?php echo $service->id; ?>" onclick="return confirm('<?php _e('Do you want to delete this service?','appointzilla');?>')" title="<?php _e('Delete','appointzilla');?>" ><i class="icon-remove"></i><?php } ?></td>
                </tr>
                    <?php } ?>
                <tr>
                    <td colspan="10">
                        <strong><a href="?page=manage-service&gid=<?php echo $gruopname->id; ?>" rel="tooltip" title="<?php _e('Add New Service to this Category','appointzilla');?>"><i class="icon-plus"></i> <?php _e('Add New Service to this Category','appointzilla');?></a></strong>
                    </td>
                </tr>
          </tbody>
        </table><?php
    } ?>

    <!---New category div box--->
    <div id="gruopbuttonbox">
        <a class="btn btn-primary" href="#" rel="tooltip" class="Create Gruop" onclick="creategruopname()"><i class="icon-plus icon-white"></i> <?php _e('Create New Service Category','appointzilla');?></a></u>
    </div>

    <div style="display:none;" id="gruopnamebox">
    <form method="post">
        <?php _e('Service Category name','appointzilla');?> : <input type="text" id="gruopname" name="gruopname" class="inputheight" />
        <button style="margin-bottom:10px;" id="CreateGruop" type="submit" class="btn" name="CreateGruop"><i class="icon-ok"></i> <?php _e('Create Category','appointzilla');?></button>
        <button style="margin-bottom:10px;" id="CancelGruop" type="button" class="btn" name="CancelGruop" onclick="cancelgrup()"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla');?></button>
    </form>
    </div>
    <!---New category div box end--->

    <?php //insert new service category
    if(isset($_POST['CreateGruop'])) {
        global $wpdb;
        $groupename = strip_tags($_POST['gruopname']);
        $table_name = $wpdb->prefix . "ap_service_category";
        $service_category = "INSERT INTO `$table_name` ( `name` )VALUES ('$groupename');";
        if($wpdb->query($service_category))	{
            echo "<script>alert('". __('New service category successfully created.','appointzilla') ."');</script>";
            echo "<script>location.href='?page=service';</script>";
        }
    }

    //rename service category
    if(isset($_POST['editgruop'])) {
        $table_name = $wpdb->prefix . "ap_service_category";
        $update_id = $_POST['editgruop'];
        $update_name = strip_tags($_POST['editgruopname']);
        $tt = !is_numeric($update_name);
        if($update_name) {
            if(!is_numeric($update_name)) {
                $update_app_query = "UPDATE $table_name SET `name` = '$update_name' WHERE `id` ='$update_id';";
                if($wpdb->query($update_app_query)) {
                    echo "<script>alert('".__('Service category successfully renamed','appointzilla')."');</script>";
                    echo "<script>location.href='?page=service';</script>";
                }
            } else {
                echo "<script>alert('".__('Invalid group name.','appointzilla')."');</script>";
            }
        } else {
            echo "<script>alert('".__('Group name cannot be blank.','appointzilla')."');</script>";
        }
    }

    //Delete service category
    if(isset($_GET['gid'])) {
        $deleteid = $_GET['gid'];
        $table_name = $wpdb->prefix . "ap_service_category";
        $delete_app_query="DELETE FROM $table_name WHERE `id` = '$deleteid';";
        if($wpdb->query($delete_app_query)) {
            //Update all service category id
            $table_name = $wpdb->prefix . "ap_services";
            $update_app_query_service = "UPDATE $table_name SET `category_id` = '1' WHERE `category_id` ='$deleteid';";
            $wpdb->query($update_app_query_service);

            echo "<script>alert('".__('Service category successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='?page=service';</script>";
        }
    }

    //Delete service
    if(isset($_GET['sid'])) {
        $DeletesId = $_GET['sid'];
        $table_name = $wpdb->prefix . "ap_services";
        $delete_app_query="DELETE FROM $table_name WHERE `id` = '$DeletesId';";
        if($wpdb->query($delete_app_query)) {
            //update all service_id  in appointment data base
            $app_table_name = $wpdb->prefix . "ap_appointments";
            $update_app_query_appointment = "UPDATE $app_table_name SET `service_id` = '1' WHERE `service_id` ='$DeletesId';";
            $wpdb->query($update_app_query_appointment);
            echo "<script>alert('".__('Service successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='?page=service';</script>";
        }
    }
     ?>
    <!--js work-->
    <style type="text/css">
    .error{  color:#FF0000; }
    input.inputheight
    {
        height:30px;
    }
    #editgruop
    {
        margin-bottom:10px;
    }
    #editgruopcancel
    {
        margin-bottom:10px;
    }
    </style>

    <script type="text/javascript">
    // edit group hide and show div box
    function editgruop(id) {
        var gneb='#gruopnamedivbox'+id;
        var gne='#gruopnameedit'+id;
        jQuery(gneb).hide();
        jQuery(gne).show();
    }

    function canceleditgrup(id) {
        var gneb='#gruopnamedivbox'+id;
        var gne='#gruopnameedit'+id;
        jQuery(gneb).show();
        jQuery(gne).hide();
    }

    // group create and  hide  or show div box ajax post data
    function creategruopname() {
        jQuery('#gruopnamebox').show();
        jQuery('#gruopbuttonbox').hide();
    }

    function cancelgrup() {
        jQuery('#gruopnamebox').hide();
        jQuery('#gruopbuttonbox').show();
    }
    </script>
    <script type="text/javascript">
    jQuery(document).ready(function () {
        // create new group js
        jQuery('#CreateGruop').click(function() {
            jQuery('.error').hide();
            var gruopname = jQuery("input#gruopname").val();
            if (gruopname == "") {
                jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('Category name cannot be blank.','appointzilla'); ?></strong></span>');
                return false;
            } else {
                var gruopname = isNaN(gruopname);
                if(gruopname == false) {
                    jQuery("#CancelGruop").after('<span class="error">&nbsp;<br><strong><?php _e('invalid Category name.','appointzilla'); ?></strong></span>');
                    return false;
                }
            }
            jQuery('#gruopnamebox').hide();
            jQuery('#gruopbuttonbox').show();
        });
    });
    </script>
</div>