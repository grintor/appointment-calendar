<?php
if(isset($_GET['ServiceID'])) { ?>
    <div id="mydiv">
        <h5> Select Staff: </h5>
        <?php
            global $wpdb;
            //get all staff id list by service id
            $ServiceID = $_GET['ServiceID'];
            $ServiceTableName = $wpdb->prefix . "ap_services";
            $AllStaffIdList = $wpdb->get_row("SELECT `staff_id` FROM `$ServiceTableName` WHERE `ID` = '$ServiceID'");
        ?>
    </div>
    <?php
} ?>
