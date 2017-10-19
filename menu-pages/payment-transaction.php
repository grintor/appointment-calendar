<?php
    global $wpdb;
    $AppointmentTableName = $wpdb->prefix . 'ap_appointments';
    $ServiceTableName = $wpdb->prefix . 'ap_services';
    $ClientTableName = $wpdb->prefix . "ap_clients";
    $PaymentTransactionTable = $wpdb->prefix . "ap_payment_transaction";
    $CurrencyTableName = $wpdb->prefix . "ap_currency";

    $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";

    if($DateFormat == 'd-m-Y') $DateFormat = "jS M. Y";
    if($DateFormat == 'm-d-Y') $DateFormat = "M. jS Y";
    if($DateFormat == 'Y-m-d') $DateFormat = "Y M. jS";

    if($TimeFormat == 'h:i') $TimeFormat = "g:ia"; else $TimeFormat = "G:i";

    $NoOfRow = 10;
    $Offset = 0;
    // pagination start with page no = 1 when filter
    if(!isset($_POST['filter'])) {
        if(!empty($_GET['pageno'])) {
            $PageNo = $_GET['pageno'];
            $Offset = ($PageNo-1)*$NoOfRow;
        }
    }
    //all transaction with pagination
    $AllTransaction = $wpdb->get_results("select * from `$PaymentTransactionTable` ORDER BY `date` DESC limit $Offset, $NoOfRow");
    $cat = $wpdb->get_results("select * from `$PaymentTransactionTable` ORDER BY `date` DESC");
?>
<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
        <h3><i class="fa fa-money"></i> <?php _e('Payment Transaction','appointzilla'); ?></h3>
    </div>
 
    <form action="" method="post" name="manage-appointments">
          <table width="100%" border="0" class="table table-hover">
              <tr>
                  <th align="left" scope="col"><?php _e('No.','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Txn Id','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Client','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Service','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Amount Billed','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Amount Paid','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Time & Date','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Status','appointzilla'); ?></th>
                  <th align="left" scope="col"><?php _e('Action','appointzilla'); ?></th>
                  <th align="left" scope="col" style="text-align: center;">
                      <a href="#" rel="tooltip" title="<?php _e('Select All','appointzilla'); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="0" /></a>
                  </th>
              </tr>
              <?php //get all transaction list
              $i = 1;
              if(count($AllTransaction)) {
                  foreach($AllTransaction as $Transaction) { ?>
              <tr>
                  <td><em><?php echo $i."."; ?></em></td>
                  <td><em><?php echo strtoupper($Transaction->txn_id); ?></em></td>
                  <td>
                     <em>
                         <?php
                            $staffid = $Transaction->client_id;
                                $ClientDetails = $wpdb->get_row("SELECT * FROM `$ClientTableName` WHERE `id` = '$staffid'");
                            if(count($ClientDetails)){
                                echo ucfirst($ClientDetails->name);
                            } else {
                                echo _e("Entry Deleted", "appointzilla");
                            }
                         ?>
                     </em>
                  </td>
                  <td>
                     <em><?php
                            //currency symbol
                            $cal_admin_currency_id = get_option('cal_admin_currency');
                            if($cal_admin_currency_id) {
                                $CurrencyTableName = $wpdb->prefix . "ap_currency";
                                $AdminCurrency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$AdminCurrency_id'");
                                $AdminCurrency = $AdminCurrency->symbol;
                            } else {
                                $AdminCurrency = "&#36;";
                            }
                            $ServiceId = $wpdb->get_row("SELECT `service_id` FROM `$AppointmentTableName` WHERE `id` = '$Transaction->app_id'");
                            if(count($ServiceId)) {
                                $ServiceDetails = $wpdb->get_row("SELECT `name`, `cost` FROM `$ServiceTableName` WHERE `id` = '$ServiceId->service_id'");
                                echo ucwords($ServiceDetails->name);
                            } else {
                                echo _e("Entry Deleted", "appointzilla");
                            }
                         ?>
                     </em>
                  </td>
                  <td>
                      <em>
                          <?php
                            if(isset($ServiceDetails->cost)) {
                                echo $AdminCurrency.ucwords($ServiceDetails->cost);
                            } else {
                                echo _e("Entry Deleted", "appointzilla");
                            }
                          ?>
                      </em>
                  </td>
                  <td><em><?php echo $AdminCurrency.$Transaction->ammount; ?></em></td>
                  <td><em><?php echo $Transaction->date; ?></em></td>
                  <td><em><?php echo ucfirst($Transaction->status); ?></em></td>
                  <td style="text-align: center;"><a href="?page=manage-payment-transaction&delete=<?php echo $Transaction->id; ?>" rel="tooltip" title="<?php _e('Delete','appointzilla'); ?>" onclick="return confirm('<?php _e('Do you want to delete this transaction?','appointzilla'); ?>')"><i class="icon-remove"></i></a></td>
                  <td style="text-align: center;"><a rel="tooltip" title="<?php _e('Select','appointzilla'); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php echo $Transaction->id; ?>" /></a></td>
              </tr>
                 <?php $i++; }   ?>
              <tr>
                  <td colspan="9">
                      <ul id="pagination-flickr" style="border:1px #CCCCCC;">
                          <li><a href="?page=manage-payment-transaction&pageno=1" ><?php _e('First','appointzilla'); ?></a></li>
                          <?php // pagination list items
                            if(isset($_GET['pageno'])) {
                                $pgno = $_GET['pageno'];
                            } else {
                                $pgno = 1;
                            }
                            $catrow = count($cat);
                            $page = ceil($catrow/$NoOfRow);
                            for($i=1;$i<=$page;$i++) { ?>
                                <li><a href="?page=manage-payment-transaction&pageno=<?php echo $i; ?>" <?php if($pgno == $i ) echo "class='active'"; else echo "class=''"; ?>  ><?php echo $i; ?> </a></li>
                          <?php } ?>
                                <li><a href="?page=manage-payment-transaction&pageno=<?php echo $i-1; ?>"> <?php _e('Last','appointzilla'); ?></a></li>
                      </ul>
                  </td>
                  <td style="text-align: center;"><button name="deleteall" class="btn btn-primary" type="submit" id="deleteall" onclick="return confirm('<?php _e('Do you want to delete these transactions?','appointzilla'); ?>')" ><?php  _e('Delete','appointzilla'); ?></button></td>
              </tr>
                  <?php } else { ?>
              <tr class="alert"><td colspan="9"><strong><?php _e('Sorry No Transaction(s).','appointzilla'); ?></strong></td>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
              </tr>
                 <?php } ?>
        </table>
    </form>
</div>

<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery('#checkbox').click(function(){
        if(jQuery('#checkbox').is(':checked')) {
            jQuery(":checkbox").prop("checked", true);
        } else {
            jQuery(":checkbox").prop("checked", false);
        }
    });
});
</script>

 <?php
// Delete single appointment
if(isset($_GET['delete'])) {
    $deleteid= $_GET['delete'];
    if($wpdb->query("DELETE FROM `$PaymentTransactionTable` WHERE `id` = '$deleteid'")) {
        echo "<script>alert('".__('Transaction successfully deleted.','appointzilla')."');</script>";
        echo "<script>location.href='?page=manage-payment-transaction';</script>";
    }
}

// Delete multiple appointment with checkbox
if(isset($_POST['deleteall'])) {
    $AllIds = $_POST['checkbox'];
    for($i=0; $i <= count($AllIds)-1; $i++) {
        $res = $AllIds[$i];
        $deleteid = $res;
        $wpdb->query("DELETE FROM `$PaymentTransactionTable` WHERE `id` = '$deleteid';");
    }
    if(count($AllIds)) {
        echo "<script>alert('".__('Selected transaction successfully deleted.','appointzilla')."');</script>";
        echo "<script>location.href='?page=manage-payment-transaction';</script>";
    } else {
        echo "<script>alert('".__('No transaction(s) selected to delete.','appointzilla')."');</script>";
        echo "<script>location.href='?page=manage-payment-transaction';</script>";
    }
}
?>

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