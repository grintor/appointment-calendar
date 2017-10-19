<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Manage Payment Settings' ,'appointzilla'); ?></h3>
    </div>
    <form method="post" action="?page=app-calendar-settings&show=paymentsettings">
        <table width="100%" class="table">
            <tr>
                <th width="13%" align="right" scope="row"><?php _e('Accept Payment' ,'appointzilla'); ?></th>
                <td width="3%" align="center"><strong>:</strong></td>
                <td width="84%">
                    <?php $ap_payment_gateway_status = get_option('ap_payment_gateway_status' ,'appointzilla'); ?>
                    <select name="ap_payment_gateway_status" id="ap_payment_gateway_status">
                        <option value="no" <?php if($ap_payment_gateway_status == 'no') echo "selected"; ?>><?php _e('No' ,'appointzilla'); ?></option>
                        <option value="yes" <?php if($ap_payment_gateway_status == 'yes') echo "selected"; ?>><?php _e('Yes' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Accept Payment' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>

            <tr>
                <th width="13%" align="right" scope="row"><?php _e('Select Payment Gateway' ,'appointzilla'); ?></th>
                <td width="3%" align="center"><strong>:</strong></td>
                <td width="84%">
                    <?php $ap_payment_gateway_name = get_option('ap_payment_gateway_name' ,'appointzilla'); ?>
                    <select name="ap_payment_gateway_name" id="ap_payment_gateway_name">
                        <option value="0"><?php _e('Select Payment Gateway' ,'appointzilla'); ?></option>
                        <option value="paypal" <?php if($ap_payment_gateway_name == 'paypal') echo "selected"; ?>><?php _e('Paypal' ,'appointzilla'); ?></option>
                    </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Payment Gateway' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
            </tr>

            <tr>
                <th width="13%" align="right" scope="row"><?php _e('Payment Email' ,'appointzilla'); ?></th>
                <td width="3%" align="center"><strong>:</strong></td>
                <td width="84%"><?php $ap_payment_email = get_option('ap_payment_email' ,'appointzilla'); ?><input name="ap_payment_email" id="ap_payment_email" type="text" value="<?php echo get_option('ap_payment_email'); ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Payment Processing Email' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
            </tr>

            <tr>
                <th scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>
                    <?php if($ap_payment_gateway_name && $ap_payment_email) { ?>
                    <button name="updatepaymentsettings" class="btn" type="submit" id="updatepaymentsettings" data-loading-text="Saving Settings"><i class="icon-pencil"></i> <?php _e('Update Settings' ,'appointzilla'); ?></button>
                    <?php } else { ?>
                    <button name="savepaymentsettings" class="btn" type="submit" id="savepaymentsettings" data-loading-text="Saving Settings"><i class="icon-ok"></i> <?php _e('Save Settings' ,'appointzilla'); ?></button>
                    <?php } ?>
                    <a href="?page=app-calendar-settings&show=paymentsettings" class="btn"><i class="icon-remove"></i> <?php _e('Cancel' ,'appointzilla'); ?></a>
                </td>
            </tr>
    </table>
    </form>



    <style type="text/css">
    .error{  color:#FF0000; }
    </style>

    <!--validation js lib-->
    <script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>

    <script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#savepaymentsettings').click(function(){
        jQuery(".error").hide();

            //slot time
            var ap_payment_gateway_status = jQuery('#ap_payment_gateway_status').val();
            if(ap_payment_gateway_status == 'yes') {
                var ap_payment_gateway_name = jQuery('#ap_payment_gateway_name').val();
                if(ap_payment_gateway_name == 0) {
                    jQuery("#ap_payment_gateway_name").after('<span class="error">&nbsp;<br><strong><?php _e('Select payment gateway.' ,'appointzilla'); ?></strong></span>');
                    return false;
                }

                if(ap_payment_gateway_name != 0) {
                    var ap_payment_email = jQuery('#ap_payment_email').val();
                    if(ap_payment_email == 0) {
                        jQuery("#ap_payment_email").after('<span class="error">&nbsp;<br><strong><?php _e('Payment email required.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }
                }
            }
        });

        jQuery('#updatepaymentsettings').click(function(){
        jQuery(".error").hide();

            //slot time
            var ap_payment_gateway_status = jQuery('#ap_payment_gateway_status').val();

            if(ap_payment_gateway_status == 'yes') {
                var ap_payment_gateway_name = jQuery('#ap_payment_gateway_name').val();
                if(ap_payment_gateway_name == 0) {
                    jQuery("#ap_payment_gateway_name").after('<span class="error">&nbsp;<br><strong><?php _e('Select payment gateway.' ,'appointzilla'); ?></strong></span>');
                    return false;
                }

                if(ap_payment_gateway_name != 0) {
                    var ap_payment_email = jQuery('#ap_payment_email').val();
                    if(ap_payment_email == 0) {
                        jQuery("#ap_payment_email").after('<span class="error">&nbsp;<br><strong><?php _e('Payment email required.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    } else {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(regex.test(ap_payment_email) == false ) {
                            jQuery("#ap_payment_email").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                            return false;
                        }
                    }
                }
            }
        });
    });
    </script>
</div>