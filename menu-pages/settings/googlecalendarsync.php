<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<?php
if(isset($_GET['show']) == 'googlecalendarsyncbody' && isset($_GET['create']) != 'yes') {
    $BlogUrl = get_bloginfo('url')."/wp-admin/admin.php?page=app-calendar-settings&show=googlecalendarsyncbody"; ?>
<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><?php _e('Google Calendar','appointzilla');?></h3> 
</div>
    <?php $CalData = get_option('google_caelndar_settings_details'); ?>
    <form name="setting_form" method="post" action="">
        <table width="100%" class="table">
            <tbody>
                <tr>
                    <td valign="middle"><em><strong><?php _e('Google Email','appointzilla'); ?> </strong></em></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td valign="middle"><input name="google_calendar_client_email" type="text" id="google_calendar_client_email" placeholder="Test@gmail.com" value="<?php echo $CalData['google_calendar_client_email']; ?>" size="50" style="width:50%;"/></td>
                </tr>
                <tr>
                    <td valign="middle"><em><strong><?php _e('Google Calendar Client ID','appointzilla'); ?> </strong></em></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td valign="middle"><input name="google_calendar_client_id" type="text" id="google_calendar_client_id" value="<?php echo $CalData['google_calendar_client_id']; ?>" size="50" style="width:50%;"/></td>
                </tr>
                <tr>
                    <td valign="middle"><em><strong><?php _e('Google Calendar Secret Key','appointzilla'); ?></strong></em></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td valign="middle"><input name="google_calendar_secret_key" type="text" id="google_calendar_secret_key" value="<?php echo $CalData['google_calendar_secret_key']; ?>" style="width:50%;"/></td>
                </tr>
                <tr>
                    <td valign="middle"><em><strong><?php _e('Redirect URIs','appointzilla'); ?></strong></em></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td valign="middle"><input type="hidden" name="google_calendar_redirect_uri" id="google_calendar_redirect_uri" value="<?php echo $BlogUrl;  ?>"/><?php echo $BlogUrl;  ?></td>
                </tr>
                <tr>
                    <td valign="middle"><em><strong><?php _e('2 Way Sync','appointzilla'); ?></strong></em></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td valign="middle">
                        <?php $google_calendar_twoway_sync = get_option('google_calendar_twoway_sync'); ?>
                        <select name="google_calendar_twoway_sync" id="google_calendar_twoway_sync">
                            <option value="no" <?php if($google_calendar_twoway_sync == 'no') echo "selected"; ?>><?php _e('No','appointzilla'); ?></option>
                            <option value="yes" <?php if($google_calendar_twoway_sync == 'yes') echo "selected"; ?>><?php _e('Yes','appointzilla'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <!--<input type="submit" name="gcalsetingsave" id="gcalsetingsave" value="Save" class="btn btn-primary"/>-->
                        <button name="gcalsetingsave" class="btn btn-primary" type="button" id="gcalsetingsave" onclick="return connect()" data-loading-text="Saving Settings" ><i class="icon-ok icon-white"></i> <?php _e('Save Settings' ,'appointzilla'); ?></button>
                    </td>
                </tr>
                <?php if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') { ?>
                <tr>
                    <td width="17%" valign="middle" ><strong><em><?php _e('Allow Google Calendar Sync', 'appointzilla');?></em></strong></td>
                    <td valign="middle"><strong>:</strong></td>
                    <td width="83%" valign="middle">

                        <div id="connecting" style="display:none;"><?php _e('Connecting...', 'appointzilla'); ?><img src="<?php echo plugins_url('/appointment-calendar-premium/images/loading.gif'); ?>" /></div>
                        <div id="disconnecting" style="display:none;"><?php _e('Disconnecting...', 'appointzilla'); ?><img src="<?php echo plugins_url('/appointment-calendar-premium/images/loading.gif'); ?>" /></div>

                        <?php
                        require_once('google-api-php-client/src/apiClient.php');
                        require_once('google-api-php-client/src/contrib/apiCalendarService.php');
                        $client = new apiClient();
                        $client->setApplicationName("appointzilla");
                        $setRedirectUri = $CalData['google_calendar_redirect_uri'];
                        $client->setClientId($CalData['google_calendar_client_id']);
                        $client->setClientSecret($CalData['google_calendar_secret_key']);
                        $client->setRedirectUri($setRedirectUri);

                        $cal = new apiCalendarService($client);
                        //print_r($client->authenticate());
                        if (isset($_GET['code'])) {
                            try{
                                $client->authenticate();
                                //$_SESSION['token'] = $client->getAccessToken();

                                //save token details
                                $Return = json_decode($client->getAccessToken());
                                update_option('google_caelndar_token_details',$Return);

                                echo "<script>location.href='".$setRedirectUri."';</script>";
                            }
                            catch(Exception $e){
                                echo "<div class='alert alert-danger'>Invalid client details.</div>";
                            }
                        }

                        $TokenData = get_option('google_caelndar_token_details');
                        if($TokenData) {
                            if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                                $client->setAccessToken(json_encode($TokenData));
                            }
                        } else {
                            unset($TokenData->access_token); // = '';
                            unset($TokenData->refresh_token); // = '';
                        }

                        if ($client->getAccessToken()) {
                            try {
                                $calList = $cal->calendarList->listCalendarList();
                            } catch(Exception $e) {
                                $authUrl = $client->createAuthUrl();
                            }
                        } else {
                            $authUrl = $client->createAuthUrl();
                            echo "<a class='btn btn-success' href='$authUrl' id='connectbutton' onclick='displayconnect()'>Connect Me</a>";
                        }

                        //disconnect button
                        if(isset($TokenData->access_token) && isset($TokenData->refresh_token)) {
                            echo "<a class='btn btn-primary btn-danger' id='disconnectingbutton' name='disconnectingbutton' href='?page=app-calendar-settings&show=googlecalendarsyncbody&destroy=yes' onclick='displaydisconnect()'>Discconct Me</a>";
                        }


                        if(isset($_GET['destroy']) == 'yes') {
                            // unset token data
                            $TokenData = array('access_token' => '', 'refresh_token' => '');
                            update_option('google_caelndar_token_details', $TokenData);
                            echo "<script>location.href='?page=app-calendar-settings&show=googlecalendarsyncbody';</script>";
                        } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form><?php
}

// Save Google calendar sync setting
if(isset($_POST['google_calendar_client_email'])) {
    $setClientEmail = $_POST['google_calendar_client_email'];
    $setClientId = $_POST['google_calendar_client_id'];
    $setClientSecret = $_POST['google_calendar_secret_key'];
    $setRedirectUri = $_POST['google_calendar_redirect_uri'];
    $TwoWaySync = $_POST['google_calendar_twoway_sync'];
    $CalenderAarry = array( 'google_calendar_client_email' => $setClientEmail,
                          'google_calendar_client_id' => $setClientId,
                          'google_calendar_secret_key' => $setClientSecret,
                          'google_calendar_redirect_uri' => $setRedirectUri,);
    update_option('google_calendar_twoway_sync', $TwoWaySync);
    update_option('google_caelndar_settings_details',$CalenderAarry);
    echo "<script>location.href='?page=app-calendar-settings&show=googlecalendarsyncbody';</script>";
} ?>

<style type="text/css">
    .error{  color:#FF0000; }
</style>
<script type="text/javascript">
    function displayconnect() {
        jQuery('#connectbutton').hide();
        jQuery('#connecting').show();
    }

    function displaydisconnect() {
        jQuery('#disconnectingbutton').hide();
        jQuery('#disconnecting').show();
    }
	
	function connect()
	{
		jQuery('.error').hide();
		var ClientEmail = jQuery('#google_calendar_client_email').val();
		var ClientId = jQuery('#google_calendar_client_id').val();
		var ClientSecret = jQuery('#google_calendar_secret_key').val();
		
	
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (ClientEmail == "") {
		
			jQuery('#google_calendar_client_email').after("<span class='error'>&nbsp;<br><strong><?php _e('Email Required.', 'appointzilla'); ?></strong></span>");
			
			
		} 
		else if(regex.test(ClientEmail) == false ) {
			jQuery('#google_calendar_client_email').after("<span class='error'>&nbsp;<br><strong><?php _e('Please Enter a valid email address. Eg: name@domain.com', 'appointzilla'); ?></strong></span>");
		

		}
		else if(ClientId == ""){
			jQuery('#google_calendar_client_id').after("<span class='error'>&nbsp;<br><strong><?php _e(' Client ID Required .', 'appointzilla'); ?></strong></span>");
			
		}
		else if(ClientSecret == ""){
			jQuery('#google_calendar_secret_key').after("<span class='error'>&nbsp;<br><strong><?php _e(' Secret Key Required .', 'appointzilla'); ?></strong></span>");
			
		}
		else{
			
			setting_form.submit();
		}

	}
</script>