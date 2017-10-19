<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><?php _e('Manage Admin Settings' ,'appointzilla'); ?></h3> 
</div>

<form method="post" action="?page=app-calendar-settings&show=adminsettings">
    <table width="100%" class="table">
        <tr>
            <th width="13%" scope="row"><?php _e('Country' ,'appointzilla'); ?></th>
            <td width="6%" align="center"><strong>:</strong></td>
            <td width="81%">
                <select name="cal_admin_country" id="cal_admin_country">
                <?php
                    global $wpdb;
                    $CountryTableName = $wpdb->prefix . "ap_country";
                    $AllCountry = $wpdb->get_results("SELECT `country_id`, `short_name` FROM `$CountryTableName`", OBJECT);
                    $cal_admin_country =  get_option('cal_admin_country');
                    foreach($AllCountry as $Country)
                    {
                        if($cal_admin_country == $Country->country_id ) $selected = 'selected'; else $selected ='';
                        echo "<option value='$Country->country_id' $selected >$Country->short_name</option>";
                    }
                ?>
                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Your Living Country.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Language' ,'appointzilla'); ?></th>
            <td align="center"><strong>:</strong></td>
            <td>
                <select name="cal_admin_language" id="cal_admin_language">
                <?php
                    $LanguageTableName = $wpdb->prefix . "ap_languages";
                    $AllLanguage = $wpdb->get_results("SELECT `language_id`, `name` FROM `$LanguageTableName`", OBJECT);
                    $cal_admin_language =  get_option('cal_admin_language');
                    foreach($AllLanguage as $Language)
                    {
                        if($cal_admin_language == $Language->language_id ) $selected = 'selected'; else $selected ='';
                        echo "<option value='$Language->language_id' $selected>$Language->name</option>";
                    }
                ?>
                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Your Country's Language." ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Time Zone' ,'appointzilla'); ?></th>
            <td align="center"><strong>:</strong></td>
            <td>
                <select name="cal_admin_timezone" id="cal_admin_timezone">
                    <!--option value="0">Select Time Zone</option>-->
                <?php
                    $TimeZoneTableName = $wpdb->prefix . "ap_timezones";
                    $AllTimeZone = $wpdb->get_results("SELECT `id`, `name` FROM `$TimeZoneTableName`", OBJECT);
                    $cal_admin_timezone =  get_option('cal_admin_timezone');
                    foreach($AllTimeZone as $TimeZone)
                    {
                        if($cal_admin_timezone == $TimeZone->id ) $selected = 'selected'; else $selected ='';
                        echo "<option value='$TimeZone->id' $selected>$TimeZone->name</option>";
                    }
                ?>
                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Your Country's Time Zone." ,'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Currency' ,'appointzilla'); ?></th>
            <td align="center"><strong>:</strong></td>
            <td>
                <?php $calendar_view = get_option('calendar_view' ,'appointzilla'); ?>
                <select id="cal_admin_currency" name="cal_admin_currency">
                <?php
                    $CurrencyTableName = $wpdb->prefix . "ap_currency";
                    $AllCurrency = $wpdb->get_results("SELECT `id`, `currency_name`, `symbol` FROM `$CurrencyTableName`", OBJECT);
                    $cal_admin_currency =  get_option('cal_admin_currency');
                    foreach($AllCurrency as $Currency)
                    {
                        if($cal_admin_currency == $Currency->id ) $selected = 'selected'; else $selected ='';
                        echo "<option value='$Currency->id' $selected>($Currency->symbol) $Currency->currency_name</option>";
                    }
                ?>
                </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e("Your Country's Currency." ,'appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
            </td>
        </tr>
        <tr>
            <th scope="row">&nbsp;</th>
            <td>&nbsp;</td>
            <td>
                <?php if($cal_admin_country && $cal_admin_language && $cal_admin_timezone) { ?>
                <button name="saveadminsettings" class="btn" type="submit" id="saveadminsettings" data-loading-text="Saving Settings" ><i class="icon-pencil"></i> <?php _e('Update Settings' ,'appointzilla'); ?></button>
                <?php } else { ?>
                <button name="saveadminsettings" class="btn" type="submit" id="saveadminsettings" data-loading-text="Saving Settings" ><i class="icon-ok"></i> <?php _e('Save Settings' ,'appointzilla'); ?></button>
                <?php } ?>
                <a href="?page=app-calendar-settings&show=adminsettings" class="btn"><i class="icon-remove"></i> <?php _e('Cancel' ,'appointzilla'); ?></a>
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
    jQuery('#savesettings').click(function(){
        jQuery(".error").hide();

        //slot time
        var calendar_slot_time = jQuery('#calendar_slot_time').val();
        if(calendar_slot_time == 0) {
            jQuery("#calendar_slot_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select Slot Time.' ,'appointzilla');?></strong></span>');
            return false;
        }

        var day_start_time = jQuery('#day_start_time').val();
        if(day_start_time == 0) {
            jQuery("#day_start_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select Start Time.' ,'appointzilla'); ?></strong></span>');
            return false;
        }

        var day_end_time = jQuery('#day_end_time').val();
        if(day_end_time == 0) {
            jQuery("#day_end_time").after('<span class="error">&nbsp;<br><strong><?php _e('Select End Time.' ,'appointzilla'); ?> </strong></span>');
            return false;
        }

        var calendar_view = jQuery('#calendar_view').val();
        if(calendar_view == 0) {
            jQuery("#calendar_view").after('<span class="error">&nbsp;<br><strong><?php _e('Select Calendar View.' ,'appointzilla'); ?></strong></span>');
            return false;
        }

        var calendar_start_day = jQuery('#calendar_start_day').val();
        if(calendar_start_day == -1) {
            jQuery("#calendar_start_day").after('<span class="error">&nbsp;<br><strong><?php _e('Select Calendar View.' ,'appointzilla'); ?></strong></span>');
            return false;
        }

    });
});
</script>