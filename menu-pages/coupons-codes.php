<?php
$DateFormat = get_option('apcal_date_format');
if($DateFormat == '') $DateFormat = "d-m-Y";
$TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $TimeFormat = "h:i";
if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";

global $wpdb;
$CouponsCodesTable = $wpdb->prefix ."apcal_pre_coupons_codes";

//Coupon Code Data Operation
if(isset($_POST['Action'])) {

    $Action = $_POST['Action'];
    if($Action == 'add-coupon') {
        $CouponCode = strtolower($_POST['CouponCode']);
        $Description = $_POST['Desc'];
        $Discount = $_POST['Discount'];
        $Expire = date("Y-m-d", strtotime($_POST['ExpireDate']))." ".date("H:i:s");
        $TotalUsage = $_POST['TotalUsage'];
        echo $AddCouponSQL = "INSERT INTO `$CouponsCodesTable` (`id`, `coupon_code`, `description`, `discount`, `expire`, `total_uses`) VALUES (NULL, '$CouponCode', '$Description', '$Discount', '$Expire', '$TotalUsage');";
        if($wpdb->query($AddCouponSQL)) {
            return true;
        } else {
            return false;
        }
    }

    if($Action == 'delete-coupon') {
        $CouponId = $_POST['Id'];
        $DeleteCouponSQL = "DELETE FROM `$CouponsCodesTable` WHERE `id` = '$CouponId'";
        if($wpdb->query($DeleteCouponSQL)) {
            return true;
        } else {
            return false;
        }
    }
}
$AllCouponsCodes = $wpdb->get_results("SELECT * FROM `$CouponsCodesTable` ORDER BY `discount` ASC");
//echo count($AllCouponsCodes);
//print_r($AllCouponsCodes);
?>
    <div class="bs-docs-example tooltip-demo">
        <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
            <h3><i class="fa fa-tags"></i> <?php _e("Coupons Codes", "appointzilla"); ?></h3>
        </div>

        <div class="bs-docs-example">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-toggle="tab" href="#coupons-codes"><strong><?php _e("Coupons Codes", "appointzilla"); ?></strong></a></li>
                <li class=""><a data-toggle="tab" href="#add-new-coupon-code"><strong><?php _e("Add New Coupon Code", "appointzilla"); ?></strong></a></li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!--coupons codes tab-->
                <div id="coupons-codes" class="tab-pane fade active in">
                    <div class="row-fluid">
                        <div class="span12">
                            <table class="table table-bordered table-projects table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="alert alert-info">
                                            <div id="category-<?php echo "id"; ?>">
                                                <strong><?php _e("Coupons Codes List", "appointzilla"); ?></strong>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th style="text-align: center;"><?php _e("Coupon Code", "appointzilla"); ?></th>
                                        <th><?php _e("Description", "appointzilla"); ?></th>
                                        <th><?php _e("Discount", "appointzilla"); ?></th>
                                        <th style="text-align: center;"><?php _e("Expire Date", "appointzilla"); ?></th>
                                        <th style="text-align: center;">
                                            <span class="badge badge-warning"><?php _e("Total"); ?></span>
                                            <span class="badge badge-success"><?php _e("Used", "appointzilla"); ?></span>
                                            <span class="badge badge-info"><?php _e("Remain", "appointzilla"); ?></span>
                                        </th>
                                        <th style="text-align: center;"><?php _e("Action", "appointzilla"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(count($AllCouponsCodes)) {
                                    $SN = 1;
                                    foreach($AllCouponsCodes as $CouponCode) {
                                        $Id = $CouponCode->id;
                                        $Coupon = $CouponCode->coupon_code;
                                        $Description = $CouponCode->description;
                                        $Discount = $CouponCode->discount;
                                        $ExpireDate = $CouponCode->expire;
                                        if(strtotime(date("Y-m-d")) > strtotime($ExpireDate)) {
                                            $ExpireDate = date($DateFormat, strtotime($ExpireDate))."<br>(<strong>Expired</strong>)";
                                        } else {
                                            $ExpireDate = date($DateFormat, strtotime($ExpireDate));
                                        }
                                        $TotalUses = $CouponCode->total_uses;
                                        $UsedCount = $CouponCode->used_count;
                                        $Remaining = $TotalUses - $UsedCount;
                                        //totally used
                                        if($UsedCount >= $TotalUses){
                                            $ExpireDate = date($DateFormat, strtotime($ExpireDate))."<br>(<strong>Totally Used</strong>)";
                                        }
                                        ?>
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $SN; ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><span class="label label-important" style="padding: 6px;"><?php echo strtoupper($Coupon); ?></span></td>
                                            <td style="vertical-align: middle;"><?php echo ucfirst($Description); ?></td>
                                            <td style="vertical-align: middle;"><?php echo $Discount."%"; ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $ExpireDate; ?></td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <span class="badge badge-warning">&nbsp;<?php echo $TotalUses; ?>&nbsp;</span>
                                                <span class="badge badge-success">&nbsp;<?php echo $UsedCount; ?>&nbsp;</span>
                                                <span class="badge badge-info">&nbsp;<?php echo $Remaining; ?>&nbsp;</span>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <div data-toggle="radio" class="btn-category sharp">
                                                    <button class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'delete-coupon');"><i class="icon-white icon-remove"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        $SN++;
                                    } //end of foreach
                                } else { ?>
                                        <tr class="alert alert-block">
                                            <td style="text-align: center;">&nbsp;</td>
                                            <td colspan="6"><?php echo _e("No coupon codes are available.", "appointzilla"); ?></td>
                                        </tr><?php
                                }// end of if count
                                ?>
                                </tbody>
                                <thead>
                                    <th colspan="7" class="alert alert-info">&nbsp;</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="alert alert-danger">
                        <strong><?php _e("Note", "appointzilla"); ?>:</strong> <?php _e("Coupons Code can be used only for those services which accept Full Payment at time of booking.", "appointzilla"); ?>
                    </div>
                </div>

                <!--add new coupons codes tab-->
                <div id="add-new-coupon-code" class="tab-pane fade <?php echo ""; ?>">
                    <div id="loading-img" style="display: none;"><i class="fa fa-spinner fa-spin fa-5x"></i></div>
                    <div id="add-coupon-div" class="borBox form-horizontal">
                        <h3 style="margin-left: 20px;"><i class="fa fa-plus"></i> <u><?php _e("Add New Coupon", "appointzilla"); ?></u></h3><br>
                        <div class="control-group">
                            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Coupon Code", "appointzilla"); ?></label>
                            <div class="control pull-left">
                                <input type="text" placeholder="Type Coupon Code Here" id="coupon-code" value="" maxlength="15" style="height: 32px; margin-left: 15px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Description", "appointzilla"); ?></label>
                            <div class="control pull-left">
                                <textarea type="text" id="desc" rows="5" style="margin-left: 15px;"></textarea>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Discount", "appointzilla"); ?></label>
                            <div class="control pull-left">
                                <input type="text" placeholder="Type Discount Value Here" id="discount" value="" style="height: 32px; margin-left: 15px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Expire Date", "appointzilla"); ?></label>
                            <div class="control pull-left">
                                <input type="text" placeholder="Select Expire Date Here" id="expire-date" value="" style="height: 32px; margin-left: 15px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Total Usages", "appointzilla"); ?></label>
                            <div class="control pull-left">
                                <input type="text" placeholder="Type Total Usages Value Here" id="total-usage" value="" style="height: 32px; margin-left: 15px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
                            <div class="control pull-left">
                            <span style="margin-left: 15px;">
                                <button class="btn btn-sharp btn-success" onclick="return PostAction(-1, 'add-coupon');" id="add-new-coupon"><strong><i class="fa fa-save"></i> Add Coupon</strong></button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .apcal_error {  color:#FF0000; }
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
            jQuery("#loading-img").hide();
            jQuery('#expire-date').datepicker({
                dateFormat: 'dd-mm-yy',
            });
        });

        function PostAction(Id, Action) {
            //check action
            if(Action == "add-coupon") {
                //validation
                jQuery(".apcal_error").hide();
                var CouponCode = jQuery("#coupon-code").val();
                var Desc = jQuery("#desc").val();
                var Discount = jQuery("#discount").val();
                var ExpireDate = jQuery("#expire-date").val();
                var TotalUsage = jQuery("#total-usage").val();
                var PostData = "Action=add-coupon" + "&CouponCode=" + CouponCode + "&Desc=" + Desc + "&Discount=" + Discount + "&ExpireDate=" + ExpireDate + "&TotalUsage=" +TotalUsage;

                if(CouponCode == ""){
                    jQuery("#coupon-code").after('<span class="apcal_error">&nbsp;<strong><?php _e('Coupon code required.','appointzilla'); ?></strong></span>');
                    return false;
                }

                //discount
                if(Discount == ""){
                    jQuery("#discount").after("<span class='apcal_error'>&nbsp;<strong><?php _e("Discount value required.","appointzilla"); ?></strong></span>");
                    return false;
                } else {
                    //if not numbers
                    if(isNaN(Discount)) {
                        jQuery("#discount").after("<span class='apcal_error'>&nbsp;<strong><?php _e("Enter numeric value between 1 to 99.","appointzilla"); ?></strong></span>");
                        return false;
                    }

                    //if less then 0 and greater then 100
                    if(Discount < 1 || Discount > 99) {
                        jQuery("#discount").after("<span class='apcal_error'>&nbsp;<strong><?php _e("Discount value should be between 1 to 99.","appointzilla"); ?></strong></span>");
                        return false;
                    }
                }

                //expire date
                if(ExpireDate == ""){
                    jQuery("#expire-date").after("<span class='apcal_error'>&nbsp;<strong><?php _e("Expire date required.","appointzilla"); ?></strong></span>");
                    return false;
                }

                //total usage
                if(TotalUsage == ""){
                    jQuery("#total-usage").after('<span class="apcal_error"><br>&nbsp;&nbsp;&nbsp;<strong><?php _e('Enter negative value for unlimited usage.','appointzilla'); ?></strong></span>');
                    jQuery("#total-usage").after('<span class="apcal_error"><br>&nbsp;&nbsp;&nbsp;<strong><?php _e('Total usage value required.','appointzilla'); ?></strong></span>');
                    return false;
                } else {
                    if(TotalUsage == 0) {
                        jQuery("#total-usage").after('<span class="apcal_error"><br>&nbsp;&nbsp;&nbsp;<strong><?php _e("Total usage should be greater then 0 OR in (-)negative for unlimited usage.","appointzilla"); ?></strong></span>');
                        return false;
                    }
                }
                jQuery("#add-coupon-div").hide();
                jQuery("#loading-img").show();
            }

            if(Action == "delete-coupon") {
                if (confirm("<?php _e("Do you want to delete this coupon code?","appointzilla"); ?>")) {
                    var PostData = "Action=" + Action + "&Id=" + Id;
                } else {
                    Action = "";
                    var PostData = "Action=" + Action + "&Id=" + "";
                }
            }

            // post data
            jQuery.ajax({
                type: "POST",
                url: location.href,
                data: PostData,
                success: function(ReturendData) {
                },
                complete: function() {
                    if(Action == 'add-coupon') {
                        alert("<?php _e("New coupon code added successfully.", "appointzilla"); ?>");
                        location.href = location.href;
                    }
                    if(Action == 'delete-coupon') {
                        alert("<?php _e("Coupon code deleted successfully.", "appointzilla"); ?>");
                        location.href = location.href;
                    }
                },
                error: function(error) {
                    alert(error);
                }
            });
        }
    </script>




