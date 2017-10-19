<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; width:100%; padding-left:10px;"><h3><i class="fa fa-cogs"></i> <?php _e('Settings Panel','appointzilla'); ?></h3>
        <ul class="nav nav-pills">
            <?php
                if(isset($_GET['show']))
                    $ShowNow = $_GET['show'];
                else
                    $ShowNow = '';
            ?>
            <!--<li <?php if($ShowNow == 'businessprofile' ) echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=businessprofile"><?php _e('Business Profile' ,'appointzilla'); ?></a></li>-->

            <li <?php if($ShowNow == 'businesshours') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=businesshours"><?php _e('Business Hours' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'staffhours') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=staffhours"><?php _e('Staff Hours' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'calendarsettings') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=calendarsettings"><?php _e('Calendar Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'adminsettings') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=adminsettings"><?php _e('Admin Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'notificationsettings') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=notificationsettings"><?php _e('Notification Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'notification-message') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=notification-message"><?php _e('Notification Message' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'googlecalendarsyncbody') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=googlecalendarsyncbody"><?php _e('Google Calendar' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'paymentsettings') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=paymentsettings"><?php _e('Payment Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowNow == 'remindersettings') echo "Class='active'"; ?>><a href="?page=app-calendar-settings&show=remindersettings"><?php _e('Reminder Settings' ,'appointzilla'); ?></a></li>
        </ul>
    </div>
    <?php if(isset($_GET['page']) == 'page' && !isset($_GET['show']) ) { ?>
    <div class="alert alert-info">
        <h4><?php _e('Settings Panel Allows Admin To Manage Following Settings' ,'appointzilla'); ?></h4>
        <!--<h5><span class="badge badge-info">1</span> <?php _e("Setup Business Profile" ,'appointzilla'); ?></h5>-->
        <h5><span class="badge badge-info">1</span> <?php _e("Manage Business Working Hours" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">2</span> <?php _e("Manage Staff Hours" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">3</span> <?php _e("Manage Calendar Settings" ,'appointzilla' ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">4</span> <?php _e("Manage Aministrator Settings" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">5</span> <?php _e("Manage Notification Settings" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">6</span> <?php _e("Customize Notification Message" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">7</span> <?php _e("Sync Appointments With Google Calendar" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">8</span> <?php _e("Payment Settings" ,'appointzilla'); ?></h5>
        <h5><span class="badge badge-info">9</span> <?php _e("Reminder Settings" ,'appointzilla'); ?></h5>
    </div>
    <?php
    }

    if(isset($_GET['show'])) {
        if($_GET['show'] == 'businessprofile') {
            include('settings/business-profile.php');
        }

        if($_GET['show'] == 'businesshours') {
            include('settings/business-hours.php');
        }

        if($_GET['show'] == 'staffhours') {
            include('settings/staff-hours.php');
        }

        if($_GET['show'] == 'adminsettings') {
            include('settings/admin-settings.php');
        }

        if($_GET['show'] == 'calendarsettings') {
            include('settings/calendar-settings.php');
        }

        if($_GET['show'] == 'notificationsettings') {
            include('settings/notification-settings.php');
        }

        if($_GET['show'] == 'notification-message') {
            include('settings/notification-message.php');
        }

        if($_GET['show'] == 'googlecalendarsyncbody') {
            include('settings/googlecalendarsync.php');
        }

        if($_GET['show'] == 'paymentsettings') {
            include('settings/payment-settings.php');
        }

        if($_GET['show'] == 'remindersettings') {
            include('settings/reminder-settings.php');
        }
    }
?>
</div>
<!--tooltip div-->