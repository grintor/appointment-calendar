<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
      <h3><?php _e('Manage Notification Settings','appointzilla'); ?></h3>
    </div>
    <form name="emailsettings" action="?page=app-calendar-settings&show=notificationsettings" method="post">
        <table width="100%" class="table">
            <tr>
                <th colspan="2" scope="row"><?php _e('Enable','appointzilla'); ?></th>
                <td width="3%"><strong>:</strong></td>
                <td width="69%"><input name="enable" type="checkbox" id="enable" <?php if(get_option('emailstatus') == 'on') echo 'checked'; ?> />
                &nbsp;<a href="#" rel="tooltip" title="<?php _e('Enable Notification','appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
                <td width="3%">&nbsp;</td>
                <td width="3%">&nbsp;</td>
                <td width="3%">&nbsp;</td>
            </tr>
            <tr id="staffnotification">
                <th colspan="2" scope="row"><?php _e('Enable Staff Notification','appointzilla'); ?></th>
                <td width="3%"><strong>:</strong></td>
                <td width="69%"><input name="staffnotificationstatus" type="checkbox" id="staffnotificationstatus" <?php if(get_option('staff_notification_status') == 'on') echo 'checked'; ?> />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Enable Staff Notification','appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
                <td width="3%">&nbsp;</td>
                <td width="3%">&nbsp;</td>
                <td width="3%">&nbsp;</td>
            </tr>
            <?php $emailtype = get_option('emailtype'); ?>
            <tr>
                <th colspan="2" scope="row"><?php _e('Email Type','appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td>
                  <select name="emailtype" id="emailtype">
                    <option value="0"><?php _e('Select Type','appointzilla'); ?></option>
                    <option value="wpmail" <?php if($emailtype == 'wpmail') echo 'selected';?>>WP Mail</option>
                    <option value="phpmail" <?php if($emailtype == 'phpmail') echo 'selected';?>>PHP Mail</option>
                    <option value="smtp" <?php if($emailtype == 'smtp') echo 'selected';?>>SMTP Mail</option>
                  </select>&nbsp;<a href="#" rel="tooltip" title="<?php _e('Notification Type','appointzilla'); ?>"><i class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
                $emaildetails =  get_option('emaildetails');
                if($emaildetails) {
                    $emaildetails = $emaildetails;
                }
            ?>
            <!--wp mail-->
            <tr id="wpmaildetails1" style="display:none;">
                <th colspan="2" scope="row"><?php _e('WP Mail Details','appointzilla'); ?></th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="wpmaildetails2" style="display:none;">
                <th scope="row">&nbsp;</th>
                <th scope="row"><?php _e('Email','appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input name="wpemail" type="text" id="wpemail"  value="<?php if(isset($emaildetails['wpemail'])) echo $emaildetails['wpemail']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin Email','appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <!--php mail-->
            <tr id="phpmaildetails1" style="display:none;">
                <th colspan="2" scope="row"><?php _e('PHPMail Details','appointzilla'); ?></th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="phpmaildetails2" style="display:none;">
                <th scope="row">&nbsp;</th>
                <th scope="row"><?php _e('Email','appointzilla'); ?></th>
                <td><strong>:</strong></td>
                <td><input name="phpemail" type="text" id="phpemail" value="<?php if(isset($emaildetails['phpemail'])) echo $emaildetails['phpemail']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin Email','appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>


            <!--smtp-->
            <tr id="smtpdetails1" style="display:none;">
                <th colspan="2" scope="row"><?php _e('SMTP Mail Details','appointzilla'); ?></th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="smtpdetails2" style="display:none;">
                <th width="9%" scope="row">&nbsp;</th>
                <td width="10%" scope="row"><?php _e('Host Name','appointzilla'); ?></td>
                <td><strong>:</strong></td>
                <td><input name="hostname" type="text" id="hostname" class="inputhieght" value="<?php if(isset($emaildetails['hostname'])) echo $emaildetails['hostname']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Host Name Like eg: smtp.gmail.com, smtp.yahoo.com','appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="smtpdetails3" style="display:none;">
                <th scope="row">&nbsp;</th>
                <td scope="row"><?php _e('Port Number','appointzilla'); ?></td>
                <td><strong>:</strong></td>
                <td><input name="portno" type="text" id="portno" value="<?php if(isset($emaildetails['portno'])) echo $emaildetails['portno']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Smtp Port Number Like eg: Gmail & Yahoo Port Number = 465','appointzilla'); ?>" ><i class="icon-question-sign"></i> </a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="smtpdetails4" style="display:none;">
                <th scope="row">&nbsp;</th>
                <td scope="row"><?php _e('Email','appointzilla'); ?></td>
                <td><strong>:</strong></td>
                <td><input name="smtpemail" type="text" id="smtpemail" value="<?php if(isset($emaildetails['smtpemail'])) echo $emaildetails['smtpemail']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin SMTP Email','appointzilla'); ?>" ><i class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr id="smtpdetails5" style="display:none;">
                <th scope="row">&nbsp;</th>
                <td scope="row"><?php _e('Password','appointzilla'); ?></td>
                <td><strong>:</strong></td>
                <td><input name="password" type="password" id="password" value="<?php if(isset($emaildetails['password'])) echo $emaildetails['password']; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Admin SMTP Email Password','appointzilla'); ?>" ><i  class="icon-question-sign"></i></a>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <th colspan="2" scope="row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>
                    <?php if($emailtype && $emaildetails ) { ?>
                    <button name="savenotificationsettings" class="btn" type="submit" id="savenotificationsettings"><i class="icon-pencil"></i> <?php _e('Update Settings','appointzilla'); ?></button>
                    <?php } else { ?>
                    <button name="savenotificationsettings" class="btn" type="submit" id="savenotificationsettings"><i class="icon-ok"></i> <?php _e('Save Settings','appointzilla'); ?></button>
                    <?php } ?>
                    <a href="?page=app-calendar-settings&show=notificationsettings" class="btn"><i class="icon-remove"></i> <?php _e('Cancel','appointzilla'); ?></a>
                    </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </form>
</div>

<style type="text/css">
.error{  color:#FF0000; 
}
</style>

<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    // on-load if check enable
    var emailtype = jQuery('#emailtype').val();
    if(jQuery('#enable').is(':checked')) {
        jQuery('#staffnotificationstatus').attr("disabled", false);     //enable staff notification checkbox
        jQuery('#emailtype').attr("disabled", false);                   //make enable selectable email type
        if(emailtype == 'wpmail') {
            jQuery('#smtpdetails1').hide();
            jQuery('#smtpdetails2').hide();
            jQuery('#smtpdetails3').hide();
            jQuery('#smtpdetails4').hide();
            jQuery('#smtpdetails5').hide();

            jQuery('#phpmaildetails1').hide();
            jQuery('#phpmaildetails2').hide();

            jQuery('#wpmaildetails1').show();
            jQuery('#wpmaildetails2').show();
        }

        if(emailtype == 'phpmail') {
            jQuery('#smtpdetails1').hide();
            jQuery('#smtpdetails2').hide();
            jQuery('#smtpdetails3').hide();
            jQuery('#smtpdetails4').hide();
            jQuery('#smtpdetails5').hide();

            jQuery('#phpmaildetails1').show();
            jQuery('#phpmaildetails2').show();

            jQuery('#wpmaildetails1').hide();
            jQuery('#wpmaildetails2').hide();
        }
        if(emailtype == 'smtp') {
            jQuery('#smtpdetails1').show();
            jQuery('#smtpdetails2').show();
            jQuery('#smtpdetails3').show();
            jQuery('#smtpdetails4').show();
            jQuery('#smtpdetails5').show();

            jQuery('#phpmaildetails1').hide();
            jQuery('#phpmaildetails2').hide();

            jQuery('#wpmaildetails1').hide();
            jQuery('#wpmaildetails2').hide();
        }
    } else {
        jQuery('#staffnotificationstatus').attr("disabled", false);     //disable staff notification checkbox
        jQuery('#emailtype').attr("disabled", true);                    //make disable selectable email type
    }


    //on-click
    jQuery('#enable').click(function(){

        jQuery(".error").hide();

        if (jQuery(this).is(':checked')) {
            jQuery('#emailtype').attr("disabled", false);
            jQuery('#staffnotificationstatus').attr("disabled", false);
        } else {
            jQuery('#emailtype').attr("disabled", true);
            jQuery('#staffnotificationstatus').attr("disabled", true);
        }
    });

    // onchange email type
    jQuery('#emailtype').change(function(){
        var emailtype = jQuery('#emailtype').val();
        if(jQuery('#enable').is(':checked') && emailtype) {
            if(emailtype=='wpmail') {
                jQuery('#smtpdetails1').hide();
                jQuery('#smtpdetails2').hide();
                jQuery('#smtpdetails3').hide();
                jQuery('#smtpdetails4').hide();
                jQuery('#smtpdetails5').hide();

                jQuery('#phpmaildetails1').hide();
                jQuery('#phpmaildetails2').hide();

                jQuery('#wpmaildetails1').show();
                jQuery('#wpmaildetails2').show();
            }

            if(emailtype == 'phpmail') {
                jQuery('#smtpdetails1').hide();
                jQuery('#smtpdetails2').hide();
                jQuery('#smtpdetails3').hide();
                jQuery('#smtpdetails4').hide();
                jQuery('#smtpdetails5').hide();

                jQuery('#phpmaildetails1').show();
                jQuery('#phpmaildetails2').show();

                jQuery('#wpmaildetails1').hide();
                jQuery('#wpmaildetails2').hide();
            }
            if(emailtype == 'smtp') {
                jQuery('#smtpdetails1').show();
                jQuery('#smtpdetails2').show();
                jQuery('#smtpdetails3').show();
                jQuery('#smtpdetails4').show();
                jQuery('#smtpdetails5').show();

                jQuery('#phpmaildetails1').hide();
                jQuery('#phpmaildetails2').hide();

                jQuery('#wpmaildetails1').hide();
                jQuery('#wpmaildetails2').hide();
            }
        }
    });

    jQuery('#savenotificationsettings').click(function() {

        jQuery(".error").hide();
        //enable
        if (jQuery('#enable').is(':checked')) {
            var emailtype = jQuery('#emailtype').val();
            if(emailtype == 0) {
                jQuery("#emailtype").after('<span class="error">&nbsp;<br><strong><?php _e('Select email type' ,'appointzilla'); ?></strong></span>');
                return false;
            }

            //wp-email
            if(emailtype == 'wpmail') {
                var wpemail = jQuery('#wpemail').val();
                if(wpemail == '') {
                    jQuery("#wpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Enter wp email' ,'appointzilla'); ?></strong></span>');
                    return false;
                } else {
                    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(regex.test(wpemail) == false ) {
                        jQuery("#wpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }
                }
            }

            //php-email
            if(emailtype == 'phpmail') {
                var phpemail = jQuery('#phpemail').val();
                if(phpemail == '') {
                    jQuery("#phpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Enter php email' ,'appointzilla'); ?></strong></span>');
                    return false;
                } else {
                    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(regex.test(phpemail) == false ) {
                        jQuery("#phpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }
                }
            }

            //smtp
            if(emailtype == 'smtp') {
                var hostname = jQuery('#hostname').val();
                if(hostname == '') {
                    jQuery("#hostname").after('<span class="error">&nbsp;<br><strong><?php _e('Enter host name' ,'appointzilla'); ?></strong></span>');
                    return false;
                }

                var portno = jQuery('#portno').val();
                if(portno == '') {
                    jQuery("#portno").after('<span class="error">&nbsp;<br><strong><?php _e('Enter port number' ,'appointzilla'); ?></strong></span>');
                    return false;
                }
                var portno = isNaN(portno);
                if(portno == true) {
                    jQuery("#portno").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid port number.' ,'appointzilla'); ?></strong></span>');
                    return false;
                }

                var smtpemail = jQuery('#smtpemail').val();
                if(smtpemail == '') {
                    jQuery("#smtpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Enter email' ,'appointzilla'); ?></strong></span>');
                    return false;
                } else {
                    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if(regex.test(smtpemail) == false )
                    {
                        jQuery("#smtpemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }
                }

                var password = jQuery('#password').val();
                if(password == '') {
                    jQuery("#password").after('<span class="error">&nbsp;<br><strong><?php _e('Enter password' ,'appointzilla'); ?></strong></span>');
                    return false;
                }
            }
        }
    });
});
</script>