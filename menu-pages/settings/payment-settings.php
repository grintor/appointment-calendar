<?php
// Update payment settings Form
if(isset($_GET['managepaymentsettings']) == 'yes')  {
    require_once('manage-payment-settings.php');
} else {
    //save settings
    if(isset($_POST['savepaymentsettings'])) {
        update_option('ap_payment_gateway_status',$_POST['ap_payment_gateway_status']);
        update_option('ap_payment_gateway_name',$_POST['ap_payment_gateway_name']);
        update_option('ap_payment_email',$_POST['ap_payment_email']);
        echo "<script>alert('".__('Payment settings successfully saved','appointzilla')."')</script>";
        echo "<script>location.href='?page=app-calendar-settings&show=paymentsettings'</script>";
    }

    //update  settings
    if(isset($_POST['updatepaymentsettings'])) {
        update_option('ap_payment_gateway_status',$_POST['ap_payment_gateway_status']);
        update_option('ap_payment_gateway_name',$_POST['ap_payment_gateway_name']);
        update_option('ap_payment_email',$_POST['ap_payment_email']);
        echo "<script>alert('".__('Payment settings successfully updated','appointzilla')."')</script>";
        echo "<script>location.href='?page=app-calendar-settings&show=paymentsettings'</script>";
    }
?>


    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Payment Settings','appointzilla');?></h3>
    </div>
    <table width="100%" class="table">
        <tr>
            <th width="17%" scope="row"><?php _e('Accept Payment','appointzilla');?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="78%">
                <em>
                    <?php
                        $ap_payment_gateway_status =  get_option('ap_payment_gateway_status');
                        if($ap_payment_gateway_status) {
                            echo ucfirst($ap_payment_gateway_status);
                        } else {
                            echo _e('Not Available','appointzilla');
                        }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th width="17%" scope="row"><?php _e('Payment Gateway','appointzilla');?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="78%">
                <em>
                    <?php
                        $ap_payment_gateway_name =  get_option('ap_payment_gateway_name');
                        if($ap_payment_gateway_name)
                        {
                            echo ucfirst($ap_payment_gateway_name);
                        } else {
                            echo _e('Not Available','appointzilla');
                        }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
        <th width="17%" scope="row"><?php _e('Payment Email','appointzilla'); ?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="78%">
                <em>
                    <?php
                        $ap_payment_email =  get_option('ap_payment_email');
                        if($ap_payment_email)
                        {
                            echo ucfirst($ap_payment_email);
                        } else {
                            echo _e('Not Available','appointzilla');
                        }
                    ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td>&nbsp;</td>
            <td><a href="?page=app-calendar-settings&show=paymentsettings&managepaymentsettings=yes" class="btn btn-primary"><?php _e('Manage Settings','appointzilla');?></a></td>
        </tr>
    </table>
<?php
}
?>