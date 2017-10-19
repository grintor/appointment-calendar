<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
    <h3><?php _e('Staff Google Calendar Sync','appointzilla');?></h3>
</div>

<?php
if(isset($_GET['staff-id'])) {
    $StaffId = $_GET['staff-id'];
    $RedirectUrl = get_bloginfo('url')."/wp-admin/admin.php?page=staff-google-calendar-sync&staff-id=".$StaffId;

    $StaffSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
    $StaffGoogleEmail = $StaffSyncSettings['StaffGoogleEmail'];
    $StaffGoogleCalendarClientId = $StaffSyncSettings['StaffGoogleCalendarClientId'];
    $StaffGoogleCalendarSecret = $StaffSyncSettings['StaffGoogleCalendarSecret'];

    ?>
    <table width="100%" class="table">
        <tr>
            <th colspan="3">
                <?php _e("Google Calendar Settings", "appointzilla"); ?>
                <span style="float: right;">
                    <a class="btn btn-primary" href="?page=staff"><i class="icon-arrow-left icon-white"></i> <?php _e('Go Back To Staff' ,'appointzilla'); ?></a>
                </span>
            </th>
        </tr>
        <tr>
            <td><?php _e("Google Email", "appointzilla"); ?></td>
            <td><strong>:</strong></td>
            <td><input type="text" id="staff-google-email" name="staff-google-email" value="<?php echo $StaffGoogleEmail; ?>"></td>
        </tr>
        <tr>
            <td><?php _e("Google Calendar Client ID ", "appointzilla"); ?></td>
            <td><strong>:</strong></td>
            <td><input type="text" id="staff-google-calendar-client-id" name="staff-google-calendar-client-id" value="<?php echo $StaffGoogleCalendarClientId; ?>" style="width: 500px;"></td>
        </tr>
        <tr>
            <td><?php _e("Google Calendar Secret", "appointzilla"); ?></td>
            <td><strong>:</strong></td>
            <td><input type="text" id="staff-google-calendar-client-secret" name="staff-google-calendar-client-secret" value="<?php echo $StaffGoogleCalendarSecret; ?>" style="width: 500px;"></td>
        </tr>
        <tr>
            <td><?php _e("Redirect URIs", "appointzilla"); ?></td>
            <td><strong>:</strong></td>
            <td><input type="hidden" id="staff-google-calendar-redirect-uris" name="staff-google-calendar-redirect-uris" value="<?php echo $RedirectUrl; ?>" style="width: 500px;"><?php echo $RedirectUrl; ?></td>
        </tr>
        <tr>
            <td><?php _e("Connect To Google", "appointzilla"); ?></td>
            <td><strong>:</strong></td>
            <td>
                <?php
                if($StaffGoogleEmail == "" && $StaffGoogleCalendarClientId == "" && $StaffGoogleCalendarSecret == "") {
                ?>
                <button id="save-settings-btn" name="save-settings-btn" onclick="return SaveStaffCalendarSettings(<?php echo $StaffId; ?>);" class="btn" type="submit" data-loading-text="Saving Settings" ><i class="fa fa-save"></i> <?php _e('Save Settings' ,'appointzilla'); ?></button>
                <?php
                } else {
                ?>
                <button id="save-settings-btn" name="save-settings-btn" onclick="return SaveStaffCalendarSettings(<?php echo $StaffId; ?>);" class="btn" type="submit" data-loading-text="Saving Settings" ><i class="fa fa-save"></i> <?php _e('Update Settings' ,'appointzilla'); ?></button>
                <?php
                }
                ?>
                <span id="loading-img" style="display: none;"><i class="fa fa-spinner fa-spin fa-lg"></i></span>
                <?php

                require_once('settings/google-api-php-client/src/apiClient.php');
                require_once('settings/google-api-php-client/src/contrib/apiCalendarService.php');
                $client = new apiClient();
                $client->setApplicationName("appointzilla");
                $client->setClientId($StaffGoogleCalendarClientId);
                $client->setClientSecret($StaffGoogleCalendarSecret);
                $client->setRedirectUri($RedirectUrl);
                $cal = new apiCalendarService($client);
                //print_r($client->authenticate());
                if (isset($_GET['code'])) {
                    try{
                        $client->authenticate();
                        //$_SESSION['token'] = $client->getAccessToken();

                        //save token details
                        $Return = json_decode($client->getAccessToken());
                        update_option("google_calendar_token_details_".$StaffId, $Return);

                        echo "<script>location.href='".$RedirectUrl."';</script>";
                    }
                    catch(Exception $e){
                        echo "<div class='alert alert-danger'>Invalid client details.</div>";
                    }
                }

                $TokenData = get_option("google_calendar_token_details_".$StaffId);
                if($TokenData) {
                    //if($TokenData->access_token != '' && $TokenData->refresh_token != '') {
                        $client->setAccessToken(json_encode($TokenData));
                    //}
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
                    ?>
                    <a class="btn btn-success" id="connect-btn" href="<?php  echo $authUrl; ?>" onclick="return LoadConnect();"><i class="fa fa-link"></i>  <?php _e("Connect" ,"appointzilla"); ?></a>
                    <span id="connecting-img" style="display: none;"><?php _e("Connecting" ,"appointzilla"); ?>...<i class="fa fa-spinner fa-spin fa-lg"></i></span>
                    <?php
                }

                //disconnect button
                if(isset($TokenData->access_token) && isset($TokenData->refresh_token)) {
                    ?>
                    <!--disconnect button-->
                    <a class="btn btn-danger" id="disconnect-btn" onclick="LoadDisconnect('<?php echo $StaffId; ?>')"><i class="fa fa-unlink"></i> <?php _e("Disconnect" ,"appointzilla"); ?></a>
                    <span id="disconnecting-img" style="display: none;"><?php _e("Disconnecting" ,"appointzilla"); ?>...<i class="fa fa-spinner fa-spin fa-lg"></i></span>
                    <!--reset button-->
                    <a class="btn btn-danger" id="remove-btn" onclick="LoadRemove('<?php echo $StaffId; ?>')"><i class="fa fa-times"></i> <?php _e("Remove Settings" ,"appointzilla"); ?></a>
                    <span id="removing-img" style="display: none;"><?php _e("Removing all settings" ,"appointzilla"); ?>...<i class="fa fa-spinner fa-spin fa-lg"></i></span>
                    <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
} else {
    ?>
    <div class="alert alert-danger">
        <p><?php _e("Invalid Staff Section", "appointzilla"); ?></p>
        <a class="btn btn-small" href="?page=staff"><i class="icon-arrow-left"></i> <?php _e('Go Back' ,'appointzilla'); ?></a>
    </div>
    <?php
}
?>

<script>
    function SaveStaffCalendarSettings(StaffId){
        if(StaffId) {
            var StaffGogoleEmail = jQuery("#staff-google-email").val();
            var StaffGoogleCalendarClientId = jQuery("#staff-google-calendar-client-id").val();
            var StaffGoogleCalendarSecret = jQuery("#staff-google-calendar-client-secret").val();
            var StaffGoogleCalendarRedirectUris = jQuery("#staff-google-calendar-redirect-uris").val();

            var PostData1 = "Action=SaveStaffGoogleCalendarSettings" + "&StaffGoogleEmail=" + StaffGogoleEmail + "&StaffId=" + StaffId + "&StaffGoogleCalendarClientId=" + StaffGoogleCalendarClientId;
            var PostData2 = "&StaffGoogleCalendarSecret=" + StaffGoogleCalendarSecret + "&StaffGoogleCalendarRedirectUris=" + StaffGoogleCalendarRedirectUris;
            var PostData = PostData1 + PostData2;
            jQuery("#save-settings-btn").hide();
            jQuery("#loading-img").show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                data : PostData,
                complete : function() { },
                success: function() {
                    jQuery("#loading-img").hide();
                    alert("<?php _e("Staff Google Calendar Sync Settings saved successfully.", "appointzilla")?>");
                    location.href = location.href;
                }
            });
        }
    }

    //show conection image
    function LoadConnect() {
        jQuery("#connect-btn").hide();
        jQuery("#connecting-img").show();
    }

    //remove staff google calendar settings ajax
    function LoadRemove(StaffId) {
        jQuery("#remove-btn").hide();
        jQuery("#removing-img").show();
        var PostData = "Action=RemoveStaffGoogleCalendarSync" + "&StaffId=" +  StaffId;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            data : PostData,
            complete : function() { },
            success: function() {
                jQuery("#removing-img").hide();
                alert("<?php _e("Staff Google Calendar Sync settings removed successfully.", "appointzilla")?>");
                location.href = location.href;
            }
        });
    }



    //show dis-conection image & unset tokens
    function LoadDisconnect(StaffId) {
        var PostData = "Action=DisconnectStaffGoogleCalendarSync" + "&StaffId=" +  StaffId;
        jQuery("#disconnect-btn").hide();
        jQuery("#disconnecting-img").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            data : PostData,
            complete : function() { },
            success: function() {
                jQuery("#loading-img").hide();
                alert("<?php _e("Staff Google Calendar Sync disconnected successfully.", "appointzilla")?>");
                location.href = location.href;
            }
        });
    }
</script>
<?php
//print_r($_POST);
if(isset($_POST['Action'])) {

    $Action = $_POST['Action'];

    //save google calendar sync settings
    if($Action == "SaveStaffGoogleCalendarSettings" ) {
        $OptionName = "staff_google_calendar_sync_settings_".$StaffId;
        $StaffGoogleCalendarSettingsArray = array(
            'StaffId' => $_POST['StaffId'],
            'StaffGoogleEmail' => $_POST['StaffGoogleEmail'],
            'StaffGoogleCalendarClientId' => $_POST['StaffGoogleCalendarClientId'],
            'StaffGoogleCalendarSecret' => $_POST['StaffGoogleCalendarSecret'],
            'StaffGoogleCalendarRedirectUris' => $_POST['StaffGoogleCalendarRedirectUris'],
        );
        update_option($OptionName, serialize($StaffGoogleCalendarSettingsArray));
    }


    // unset token data
    if($Action == "DisconnectStaffGoogleCalendarSync") {
        $StaffId = $_POST['StaffId'];
        $TokenData = array('access_token' => '', 'refresh_token' => '');
        update_option("google_calendar_token_details_".$StaffId, $TokenData);
        echo "<script>location.href = location.href;</script>";
    }

    //delete all settings
    if($Action == "RemoveStaffGoogleCalendarSync") {
        $StaffId = $_POST['StaffId'];
        delete_option("google_calendar_token_details_".$StaffId);
        delete_option("staff_google_calendar_sync_settings_".$StaffId);
        echo "<script>location.href = location.href;</script>";
    }
}
?>