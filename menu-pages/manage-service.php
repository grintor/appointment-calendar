<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><i class="fa fa-wrench"></i> <?php _e('Manage Service','appointzilla');?></h3></div>
<!-- manage service form -->	
<div class="bs-docs-example tooltip-demo">
    <?php global $wpdb;
    $AllStaffIds = array();
    if(isset($_GET['sid']) || isset($_GET['gid'])) {
        if(isset($_GET['gid'])) $sid = -1; else $sid = $_GET['sid'];
        $ServiceTableName = $wpdb->prefix . "ap_services";
        $servicedetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` ='$sid'" ,OBJECT);
        if($servicedetails) { $AllStaffIds = unserialize($servicedetails->staff_id); } ?>
        <form action="" method="post" name="manageservice">
            <table width="100%" class="table" >
                <tr>
                    <th width="20%" scope="row"><?php _e('Name','appointzilla'); ?></th>
                    <td width="3%"><strong>:</strong></td>
                    <td width="77%"><input name="name" type="text" id="name"  value="<?php if($servicedetails) echo $servicedetails->name; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Name','appointzilla');?>"><i class="icon-question-sign"></i></a></td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Description','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td><textarea name="desc" id="desc"><?php if($servicedetails) echo $servicedetails->desc; ?></textarea>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Description','appointzilla');?>"><i class="icon-question-sign"></i></a>				</td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Duration','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td><input name="duration" type="text" id="duration" onchange="checkduration()" value="<?php if($servicedetails) echo $servicedetails->duration; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Duration<br>Enter Numeric Value In Multiple Of 5<br>Eg: 15, 30, 60','appointzilla');?>"><i class="icon-question-sign"></i> </a></td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Duration Unit','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select id="durationunit" name="durationunit">
                            <option value="0"><?php _e('Select Duration\'s Unit','appointzilla'); ?></option>
                            <option value="minute" <?php if($servicedetails){ if($servicedetails->unit == 'minute') echo "selected"; } ?> ><?php _e('Minute(s)','appointzilla');?></option>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Duration Unit','appointzilla');?>"><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Padding Time','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td><input name="paddingtime" type="text" id="paddingtime" value="<?php if($servicedetails) { echo $servicedetails->paddingtime; } else echo "0"; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Padding / Break / Refresh Time<br>Enter Numeric Value<br>Eg: 5, 10, 15, 20, 25 OR can be 0','appointzilla');?>" ><i class="icon-question-sign"></i></a></td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Cost','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td><input name="cost" type="text" id="cost" value="<?php if($servicedetails) echo $servicedetails->cost; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Cost<br>Enter Numeric Value<br>Eg: 50, 100, 150','appointzilla');?>" ><i class="icon-question-sign"></i></a>				</td>
                </tr>
                <!--<tr>
                    <th scope="row"><strong>
                      <?php //_e('Capacity','appointzilla');?>
                    </strong></th>
                    <td><strong>:</strong></td>
                    <td><input name="capacity" type="text" id="capacity" value="<?php //if($servicedetails) echo $servicedetails->capacity; ?>" class="inputheight"/>
                      &nbsp;<a href="#" rel="tooltip" title="<?php //_e('Service Capacity<br> Eg: 1, 5, 10, 25, 50, 100<br>Enter Numeric Value','appointzilla');?>"><i class="icon-question-sign"></i></a> </td>
                </tr>-->
                <tr>
                    <th scope="row"><strong><?php _e('Accept Payment','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select name="accept_payment" id="accept_payment" onchange="enablepaymentfields()">
                            <option value="no" <?php if($servicedetails){ if($servicedetails->accept_payment == 'no') echo "selected"; } ?>><?php _e('No','appointzilla');?></option>
                            <option value="yes" <?php if($servicedetails){ if($servicedetails->accept_payment == 'yes') echo "selected"; } ?>><?php _e('Yes','appointzilla');?></option>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Accept Payment On Booking','appointzilla');?>"><i class="icon-question-sign"></i></a>
                    </td>
                 </tr>
                <tr id="PaymentTypeTr">
                    <th scope="row"><strong><?php _e('Payment Type','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select name="payment_type" id="payment_type" onchange="enablepercentagefields()">
                            <option value="percentage" <?php if($servicedetails){ if($servicedetails->payment_type == 'percentage') echo "selected"; } ?>><?php _e('In Percentage','appointzilla');?></option>
                            <option value="full" <?php if($servicedetails){ if($servicedetails->payment_type == 'full') echo "selected"; } ?>><?php _e('Full Payment','appointzilla');?></option>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Payment Type','appointzilla');?>" ><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
                <tr id="PercentageAmtTr">
                    <th scope="row"><strong><?php _e('Percentage Amount','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td><input name="percentage_ammount" type="text" id="percentage_ammount" value="<?php if($servicedetails) echo $servicedetails->percentage_ammount; ?>" class="inputheight"/>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Percentage Amount<br> Eg: 5%, 10%, 20%, 50%<br>Enter Numeric Value','appointzilla');?>"><i class="icon-question-sign"></i></a>				</td>
                </tr>
                <tr>
                    <th scope="row"><strong><?php _e('Availability','appointzilla');?></strong></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select id="availability" name="availability">
                            <option value="0"><?php _e('Select Service Availability','appointzilla');?></option>
                            <option value="yes" <?php if($servicedetails){ if($servicedetails->availability == 'yes') echo "selected"; } ?> ><?php _e('Yes','appointzilla');?></option>
                            <option value="no" <?php if($servicedetails){ if($servicedetails->availability == 'no') echo "selected"; } ?> ><?php _e('No','appointzilla');?></option>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Service Availability','appointzilla');?>"><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Category','appointzilla');?></th>
                    <td><strong>:</strong></td>
                    <td>
                        <select id="category" name="category">
                            <option value="0"><?php _e('Select Category','appointzilla');?></option>
                            <?php //get all category list
                            $table_name = $wpdb->prefix . "ap_service_category";
                            $service_category = $wpdb->get_results("select * from $table_name");
                            foreach($service_category as $gruopname) { ?>
                                <option value="<?php echo $gruopname->id; ?>"
                                <?php if($servicedetails){ if($servicedetails->category_id == $gruopname->id) echo "selected"; } ?><?php if(isset($_GET['gid']) == $gruopname->id) echo "selected"; ?> >
                                <?php if($gruopname->name == 'Default') echo _e('Default', 'appointzilla'); else echo $gruopname->name; ?></option>
                            <?php } ?>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Select Category','appointzilla');?>"><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Assign Staff(s)','appointzilla');?></th>
                    <td><strong>:</strong></td>
                    <td>
                        <label><?php _e('Use CTRL to Select Multiple Staff(s)','appointzilla');?></label>
                        <select id="staff" name="staff[]" multiple="multiple" size="7" style="width:300px;">
                            <?php
                                $StaffTableName = $wpdb->prefix . "ap_staff";
                                $AllStaff = $wpdb->get_results("SELECT `id`, `name` FROM `$StaffTableName`", OBJECT);
                                foreach($AllStaff as $Staff)
                                {
                                    if( in_array($Staff->id, $AllStaffIds) )  { $selected = "Selected"; } else { $selected = ""; }
                                    echo "<option value='$Staff->id' $selected >".ucwords($Staff->name)."</option>";
                                }
                            ?>
                        </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Assign Staff(s) To This Service<br>Use CTRL To Select Multiple Staffs','appointzilla');?>" ><i class="icon-question-sign"></i></a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">&nbsp;</th>
                    <td>&nbsp;</td>
                    <td>
                        <?php if(isset($_GET['sid']))	{	?>
                        <button id="saveservice" type="submit" class="btn" name="updateservice"><i class="icon-pencil"></i> <?php _e('Update','appointzilla');?></button>
                        <?php } else {?>
                        <button id="saveservice" type="submit" class="btn" name="saveservice"><i class="icon-ok"></i> <?php _e('Create','appointzilla');?></button>
                        <?php } ?>
                        <a href="?page=service" class="btn"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla');?></a>
                    </td>
                </tr>
            </table>
        </form><?php
    }

    //service views
    if(isset($_GET['viewid'])) {
        $viewid = $_GET['viewid'];
        $ServiceTableName = $wpdb->prefix . "ap_services";
        $servicedetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` ='$viewid'" ,OBJECT); ?>
        <table width="100%" class="detail-view table table-striped table-condensed">
            <tr>
                <th width="18%" scope="row"><?php _e('Name','appointzilla');?></th>
                <td width="3%"><strong>:</strong></td>
                <td width="79%"><?php echo ucfirst($servicedetails->name); ?></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Description','appointzilla');?></strong></th>
                <td><strong>:</strong></td>
                <td><?php echo ucwords($servicedetails->desc); ?></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Duration','appointzilla');?></strong></th>
                <td><strong>:</strong></td>
                <td><?php echo $servicedetails->duration." "; if($servicedetails->unit == 'minute') echo _e('Minute', 'appointzilla'); else echo _e('Minute', 'appointzilla'); ?></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Padding Time','appointzilla');?></strong></th>
                <td><strong>:</strong></td>
                <td><?php echo $servicedetails->paddingtime." "; if($servicedetails->unit == 'minute') echo _e('Minute', 'appointzilla'); else echo _e('Minute', 'appointzilla'); ?></td>
            </tr>
            <tr>
                <th scope="row"><strong><?php _e('Cost','appointzilla');?> </strong></th>
                <td><strong>:</strong></td>
                <td>
                    <?php $cal_admin_currency_id = get_option('cal_admin_currency');
                        if($cal_admin_currency_id) {
                            $CurrencyTableName = $wpdb->prefix . "ap_currency";
                            $cal_admin_currency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency_id'");
                            $cal_admin_currency = $cal_admin_currency->symbol;
                        } else {
                            $cal_admin_currency = "&#36;";
                        }
                        echo $cal_admin_currency.$servicedetails->cost;
                    ?>
                </td>
            </tr>
            <!--<tr>
                <th scope="row"><strong><?php //_e('Capacity','appointzilla');?> </strong></th>
                <td><strong>:</strong></td>
                <td><?php //echo $servicedetails->capacity; ?></td>
            </tr>-->
            <tr>
                <th scope="row"><strong><?php _e('Availability','appointzilla');?></strong></th>
                <td><strong>:</strong></td>
                <td><?php if($servicedetails->availability == 'yes') echo _e('Yes', 'appointzilla'); else echo _e('No', 'appointzilla'); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Category','appointzilla');?></th>
                <td><strong>:</strong></td>
                <td>
                    <?php
                        $ServiceTableName = $wpdb->prefix . "ap_service_category";
                        $CategoryList = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` ='$servicedetails->category_id'" ,OBJECT);
                        if($CategoryList->name == 'Default') { echo _e('Default', 'appointzilla'); } else { echo $CategoryList->name; }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><a class="btn" href="?page=service"><i class="icon-arrow-left"></i> <?php _e('Back','appointzilla');?></a></td>
            </tr>
        </table><?php
    } // end of service view


    // Creating new service
    if(isset($_POST['saveservice'])) {
        $servicename = strip_tags($_POST['name']);
        $desc = strip_tags($_POST['desc']);
        $duration = $_POST['duration'];
        $durationunit = $_POST['durationunit'];
        $paddingtime = $_POST['paddingtime'];
        $cost = $_POST['cost'];
        $capacity = 0; //$_POST['capacity'];
        $availability = $_POST['availability'];
        $category = $_POST['category'];
        $staff = serialize($_POST['staff']);

        $accept_payment = strip_tags($_POST['accept_payment']);
        $payment_type = strip_tags($_POST['payment_type']);

        if($accept_payment == 'yes' && $payment_type != 'full') {
            $payment_type = strip_tags($_POST['payment_type']);
            $percentage_ammount = strip_tags($_POST['percentage_ammount']);
        }

        if($accept_payment == 'yes' && $payment_type == 'full') {
            $payment_type = strip_tags($_POST['payment_type']);
            $percentage_ammount = 0;
        }

        if($accept_payment == 'no') {
            $accept_payment = 'no';
            $payment_type = 'none';
            $percentage_ammount = 'none';
        }

        $ServiceTable = $wpdb->prefix . "ap_services";
        $CreateService = "INSERT INTO `$ServiceTable` (`id`, `name`, `desc`, `duration`, `unit`, `paddingtime`, `cost`, `capacity`, `availability`, `business_id`, `category_id`, `staff_id`, `accept_payment`, `payment_type`, `percentage_ammount`, `service_hours`) VALUES (NULL, '$servicename', '$desc', '$duration', '$durationunit', '$paddingtime', '$cost', '0', '$availability', '', '$category', '$staff', '$accept_payment', '$payment_type', '$percentage_ammount', '');";
        if($wpdb->query($CreateService)) {
            echo "<script>alert('".__('New service added successfully.','appointzilla')."')</script>";
            echo "<script>location.href='?page=service';</script>";
        }
    }


    //update a service
    if(isset($_POST['updateservice'])) {
        $sid = $_GET['sid'];
        $servicename = strip_tags($_POST['name']);
        $desc = strip_tags($_POST['desc']);
        $duration = $_POST['duration'];
        $durationunit = $_POST['durationunit'];
        $paddingtime = $_POST['paddingtime'];
        $cost = $_POST['cost'];
        $capacity = 0; //$_POST['capacity'];
        $availability = $_POST['availability'];
        $category = $_POST['category'];
        $staff = serialize($_POST['staff']);
        $accept_payment = strip_tags($_POST['accept_payment']);

        if($accept_payment == 'yes' && $payment_type != 'full') {
            $payment_type = strip_tags($_POST['payment_type']);
            $percentage_ammount = strip_tags($_POST['percentage_ammount']);
        }

        if($accept_payment == 'yes' && $payment_type == 'full') {
            $payment_type = strip_tags($_POST['payment_type']);
            $percentage_ammount = 0;
        }

        if($accept_payment == 'no') {
            $accept_payment = NULL;
            $payment_type = NULL;
            $percentage_ammount = NULL;
        }

        $ServiceTable = $wpdb->prefix . "ap_services";
        $update_service ="UPDATE `$ServiceTable` SET `name` = '$servicename',
        `desc` = '$desc',
        `duration` = '$duration',
        `unit` = '$durationunit',
        `paddingtime` = '$paddingtime',
        `cost` = '$cost',
        `capacity` = '$capacity',
        `availability` = '$availability',
        `category_id` = '$category',
        `staff_id` = '$staff',
        `accept_payment` = '$accept_payment',
        `payment_type` = '$payment_type',
        `percentage_ammount` = '$percentage_ammount' WHERE `id` ='$sid';";

        if($wpdb->query($update_service)) {
            echo "<script>alert('".__('Service updated successfully.','appointzilla')."')</script>";
            echo "<script>location.href='?page=manage-service&viewid=$sid';</script>";
        } else {
            echo "<script>alert('".__('Service updated successfully.','appointzilla')."')</script>";
            echo "<script>location.href='?page=manage-service&viewid=$sid';</script>";
        }
    } ?>
    <style type="text/css">
    .error{  color:#FF0000; }
    input.inputheight {
        height:30px;
    }
    </style>
    <!--validation js lib-->
    <script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
    <script type="text/javascript">
    function checkduration() {
        jQuery('.error').hide();
        var duration = jQuery("input#duration").val();
        var duration = duration%5;
        if(duration !=0) {
            jQuery("input#duration").after('<span class="error">&nbsp;<br><strong><?php _e('Duration will be in multiple of 5, like as: 5, 10, 15, 20, 25.','appointzilla');?></strong></span>');
            //jQuery("input#Duration").focus();
            return false;
        }
    }

    //on change payment settings Yes/No
    function enablepaymentfields() {
        var accept_payment = jQuery('#accept_payment').val();
        if(accept_payment == 'yes') {
            jQuery('#PaymentTypeTr').show();
            jQuery('#PercentageAmtTr').show();
        } else {
            jQuery('#PaymentTypeTr').hide();
            jQuery('#PercentageAmtTr').hide();
        }
    }

    //if payment full then hide percentage ammount field
    function enablepercentagefields() {
        var payment_type = jQuery('#payment_type').val();
        if(payment_type == 'full') {
            jQuery('#PercentageAmtTr').hide();
        } else {
            jQuery('#PercentageAmtTr').show();
        }
    }

    jQuery(document).ready(function () {
        var accept_payment = jQuery('#accept_payment').val();
        var payment_type = jQuery('#payment_type').val();

        //onload on creating new service
        if(accept_payment == 'no') {
            jQuery('#PaymentTypeTr').hide();
            jQuery('#PercentageAmtTr').hide();
        }

        //onload service for update
        if(payment_type == 'full' && accept_payment == 'yes') {
            jQuery('#PaymentTypeTr').show();
            jQuery('#PercentageAmtTr').hide();
        }

        if(payment_type == 'percentage' && accept_payment == 'yes')
        {
            jQuery('#PaymentTypeTr').show();
            jQuery('#PercentageAmtTr').show();
        }

        jQuery('#saveservice').click(function() {
            jQuery('.error').hide();
            var name = jQuery("input#name").val();
            if (name == "") {
                jQuery("#name").after('<span class="error">&nbsp;<br><strong><?php _e('Name cannot be blank.','appointzilla');?></strong></span>');
                return false;
            } else {
                var name = isNaN(name);
                if(name == false) {
                    jQuery("#name").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid name.','appointzilla');?></strong></span>');
                    return false;
                }
            }

            var desc = jQuery("textarea#desc").val();
            if (desc == "") {
                jQuery("#desc").after('<span class="error">&nbsp;<br><strong><?php _e('Description cannot be blank.','appointzilla');?></strong></span>');
                return false;
            }

            var duration = jQuery("input#duration").val();
            if (duration == "") {
                jQuery("#duration").after('<span class="error">&nbsp;<br><strong><?php _e('Duration cannot be blank.','appointzilla');?></strong></span>');
                return false;
            } else {
                var duration = isNaN(duration);
                if(duration == true) {
                    jQuery("#duration").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid Duration.','appointzilla');?></strong></span>');
                    return false;
                } else {
                    var duration = jQuery("input#duration").val();
                    var testvalue = duration%5;
                    if(testvalue !=0) {
                        jQuery("#duration").after('<span class="error">&nbsp;<br><strong><?php _e('Duration will be in multiple of 5, like as: 5, 10, 15, 20, 25.','appointzilla');?></strong></span>');
                        //jQuery("input#Duration").focus();
                        return false;
                    }
                }
            }

            //padding time
            var paddingtime = jQuery("input#paddingtime").val();
            if (paddingtime == "") {
                jQuery("#paddingtime").after('<span class="error">&nbsp;<br><strong><?php _e('Padding time cannot be blank.','appointzilla');?></strong></span>');
                return false;
            } else {
                var paddingtimeres = isNaN(paddingtime);
                if(paddingtimeres == true) {
                    jQuery("#paddingtime").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid padding time.','appointzilla');?></strong></span>');
                    return false;
                } else {
                    var paddingtime = jQuery("input#paddingtime").val();
                    var testvalue = paddingtime%5;
                    if(testvalue !=0) {
                        jQuery("#paddingtime").after('<span class="error">&nbsp;<br><strong><?php _e('Padding time will be in multiple of 5, like as: 5, 10, 15, 20, 25 OR can be 0.','appointzilla');?></strong></span>');
                        return false;
                    }
                }
            }


            var durationunit = jQuery('#durationunit').val();
            if(durationunit == 0) {
                jQuery("#durationunit").after('<span class="error">&nbsp;<br><strong>Select Durations Unit.</strong></span>');
                return false;
            }

            /*var capacity = jQuery("input#capacity").val();
            if (capacity == "")
            {  	jQuery("#capacity").after('<span class="error">&nbsp;<br><strong><?php //_e('Capacity cannot be blank.','appointzilla');?></strong></span>');
                return false;
            }
            else
            {	var capacityres = isNaN(capacity);
                if(capacityres == true)
                {
                    jQuery("#capacity").after('<span class="error">&nbsp;<br><strong><?php //_e('Invalid capacity.','appointzilla');?></strong></span>');
                    return false;
                }
                if(capacity <1)
                {
                    jQuery("#capacity").after('<span class="error">&nbsp;<br><strong><?php //_e('Invalid capacity. Minimum capacity 1.','appointzilla');?></strong></span>');
                    return false;
                }
            }*/

            var cost = jQuery("input#cost").val();
            if (cost == "") {
                jQuery("#cost").after('<span class="error">&nbsp;<br><strong><?php _e('Cost cannot be blank.','appointzilla');?></strong></span>');
                return false;
            } else {
                var cost = isNaN(cost);
                if(cost == true) {
                    jQuery("#cost").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid cost.','appointzilla');?></strong></span>');
                    return false;
                }
            }

            //check accept payment yes/no
            var accept_payment = jQuery('#accept_payment').val();
            if(accept_payment == 'yes') {
                var payment_type = jQuery('#payment_type').val();
                if(payment_type != 'full') {
                    var percentage_ammount = jQuery('#percentage_ammount').val();
                    if(percentage_ammount == '') {
                        jQuery("#percentage_ammount").after('<span class="error">&nbsp;<br><strong><?php _e('Percentage amount cannot be blank.','appointzilla');?></strong></span>');
                        return false;
                    } else {
                        var NotNumeric = isNaN(percentage_ammount);
                        if(NotNumeric == true) {
                             jQuery("#percentage_ammount").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid percentage amount. Eg.: 5, 10, 20, 25, 50','appointzilla');?></strong></span>');
                            return false;
                        }

                        if(percentage_ammount <= 0 ) {
                            jQuery("#percentage_ammount").after('<span class="error">&nbsp;<br><strong><?php _e('Amount greater than 0. Eg.: 5, 10, 20, 25, 50','appointzilla');?></strong></span>');
                            return false;
                        }

                        if(percentage_ammount >= 100 ) {
                            jQuery("#percentage_ammount").after('<span class="error">&nbsp;<br><strong><?php _e('Amount less then 100. Eg.: 5, 10, 20, 25, 50','appointzilla');?></strong></span>');
                            return false;
                        }
                    }
                }
            }

            var availability = jQuery('#availability').val();
            if(availability == 0) {
                jQuery("#availability").after('<span class="error">&nbsp;<br><strong><?php _e('Select availability.','appointzilla'); ?></strong></span>');
                return false;
            }

            var category = jQuery('#category').val();
            if(category == 0) {
                jQuery("#category").after('<span class="error">&nbsp;<br><strong><?php _e('Select category.','appointzilla');?></strong></span>');
                return false;
            }

            var staff = jQuery('#staff').val();
            if(!staff) {
                jQuery("#staff").after('<span class="error">&nbsp;<br><strong><?php _e('Assign any staff.','appointzilla');?></strong></span>');
                return false;
            }
        });
    });
    </script>
</div>