<?php
global $wpdb;
// Update Admin settings Form
if(isset($_GET['manageadminsettings']) == 'yes') {
    include('manage-admin-settings.php');
} else {
    // Saving Admin Settings
    if(isset($_POST['saveadminsettings']))
    {
        //update admin settings option values
        update_option('cal_admin_country',$_POST['cal_admin_country']);
        update_option('cal_admin_language',$_POST['cal_admin_language']);
        update_option('cal_admin_timezone',$_POST['cal_admin_timezone']);
        update_option('cal_admin_currency',$_POST['cal_admin_currency']);
        echo "<script>alert('" . __('Admin settings sucessfully saved.' ,'appointzilla') . "');</script>";
        echo "<script>location.href='?page=app-calendar-settings&show=adminsettings'</script>";
    }
// display current settings
?>
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Admin Settings' ,'appointzilla'); ?></h3>
    </div>

    <table width="100%" class="table">
        <tr>
            <th width="14%" scope="row"><?php _e('Country' ,'appointzilla'); ?></th>
            <td width="5%"><strong>:</strong></td>
            <td width="81%">
                <em>
                <?php $cal_admin_country =  get_option('cal_admin_country');
                    if($cal_admin_country != '') {
                        $CountryTableName = $wpdb->prefix . "ap_country";
                        $Name = $wpdb->get_row("SELECT `short_name` FROM `$CountryTableName` WHERE `country_id` = '$cal_admin_country'", OBJECT);				echo $Name->short_name;
                    }
                    else echo _e("Not Available." ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Language' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php $cal_admin_language =  get_option('cal_admin_language');
                    if($cal_admin_language  != '') {
                        $LanguageTableName = $wpdb->prefix . "ap_languages";
                        $Name = $wpdb->get_row("SELECT `name` FROM `$LanguageTableName` WHERE `language_id` = '$cal_admin_language'", OBJECT);
                        echo $Name->name;
                    }
                    else echo _e("Not Available." ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Time Zone' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php $cal_admin_timezone =  get_option('cal_admin_timezone');
                    if($cal_admin_timezone  != '') {
                        $TimeZoneTableName = $wpdb->prefix . "ap_timezones";
                        $Name = $wpdb->get_row("SELECT `name` FROM `$TimeZoneTableName` WHERE `id` = '$cal_admin_timezone'", OBJECT);
                        echo $Name->name;

                    }
                    else echo _e("Not Available." ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Currency' ,'appointzilla'); ?></th>
            <td><strong>:</strong></td>
            <td>
                <em>
                <?php $cal_admin_currency =  get_option('cal_admin_currency');
                    if($cal_admin_currency  != '') {
                        $CurrencyTableName = $wpdb->prefix . "ap_currency";
                        $Name = $wpdb->get_row("SELECT `currency_name`, `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency'", OBJECT);
                        echo "($Name->symbol) $Name->currency_name";
                    }
                    else echo _e("Not Available." ,'appointzilla');
                ?>
                </em>
            </td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td>&nbsp;</td>
            <td><a href="?page=app-calendar-settings&show=adminsettings&manageadminsettings=yes" class="btn btn-primary"><?php _e('Manage Settings' ,'appointzilla'); ?></a></td>
        </tr>
    </table>
<?php
} //ens of display settings
?>