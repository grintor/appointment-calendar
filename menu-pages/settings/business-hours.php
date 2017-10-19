<?php $TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $BHTimeFormat = "g:i A";
if($TimeFormat == 'h:i') $BHTimeFormat = "g:i A";
if($TimeFormat == 'H:i') $BHTimeFormat = "G:i";

//saving business hours
if(isset($_POST['saveservice'])){
    global $wpdb;
    $BusinessHoursTableName = $wpdb->prefix."ap_business_hours";

    //Monday
    if(isset($_POST['mcheck']) == 'mclose') {
        $MondaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 1;";
        $wpdb->query($MondaySql);
    } else {
        $mst = $_POST['mst'];
        $met = $_POST['met'];
        $MondaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$mst', `end_time` = '$met', `close` = 'no' WHERE `id` = 1;";
        $wpdb->query($MondaySql);
    }

    //Tuesday
    if(isset($_POST['tucheck']) == 'tuclose') {
        $TuesdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 2;";
        $wpdb->query($TuesdaySql);
    } else {
        $tst = $_POST['tst'];
        $tet = $_POST['tet'];
        $TuesdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$tst', `end_time` = '$tet', `close` = 'no' WHERE `id` = 2;";
        $wpdb->query($TuesdaySql);
    }

    //Wednesday
    if(isset($_POST['wcheck']) == 'wclose') {
        $WednesdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 3;";
        $wpdb->query($WednesdaySql);
    } else {
        $wst = $_POST['wst'];
        $wet = $_POST['wet'];
        $WednesdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$wst', `end_time` = '$wet', `close` = 'no' WHERE `id` = 3;";
        $wpdb->query($WednesdaySql);
    }

    //Thursday
    if(isset($_POST['thcheck']) == 'thclose') {
        $ThursdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 4;";
        $wpdb->query($ThursdaySql);
    } else {
        $thst = $_POST['thst'];
        $thet = $_POST['thet'];
        $ThursdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$thst', `end_time` = '$thet', `close` = 'no' WHERE `id` = 4;";
        $wpdb->query($ThursdaySql);
    }

    //Friday
    if(isset($_POST['fcheck']) == 'fclose') {
        $FridaydaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 5;";
        $wpdb->query($FridaydaySql);
    } else {
        $fst = $_POST['fst'];
        $fet = $_POST['fet'];
        $FridaydaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$fst', `end_time` = '$fet', `close` = 'no' WHERE `id` = 5;";
        $wpdb->query($FridaydaySql);
    }


    //Saturday
    if(isset($_POST['satcheck']) == 'satclose') {
        $SaturdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 6;";
        $wpdb->query($SaturdaySql);
    } else {
        $satst = $_POST['satst'];
        $satet = $_POST['satet'];
        $SaturdaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$satst', `end_time` = '$satet', `close` = 'no' WHERE `id` = 6;";
        $wpdb->query($SaturdaySql);
    }


    //Sunday
    if(isset($_POST['suncheck']) == 'sunclose') {
        $SundaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = 'none', `end_time` = 'none', `close` = 'yes' WHERE `id` = 7;";
        $wpdb->query($SundaySql);
    } else {
        $sunst = $_POST['sunst'];
        $sunet = $_POST['sunet'];
        $SundaySql = "UPDATE `$BusinessHoursTableName` SET `start_time` = '$sunst', `end_time` = '$sunet', `close` = 'no' WHERE `id` = 7;";
        $wpdb->query($SundaySql);
    }
    echo "<script>alert('" . __('Business Hours successfully updated.' ,'appointzilla') . "');</script>";
} // end of isset
?>

<form method="post" name="save-business-settings">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><?php _e('Business Hours' ,'appointzilla'); ?></h3></div>
    <div style="width:60%;">
        <?php global $wpdb;
        $BusinessHoursTableName = $wpdb->prefix . "ap_business_hours";
        $FetchBusinessHours_sql = "SELECT * FROM `$BusinessHoursTableName`";
        $GetBusinessHours = $wpdb->get_results($FetchBusinessHours_sql, OBJECT);
        //$GetBusinessHours = AcBussinessHours::model()->findAll();
        if($GetBusinessHours) {
            //print_r($GetBusinessHours);
            $i = 1;
            foreach($GetBusinessHours as $Singleday) {
                $setst[$i] = $Singleday->start_time;
                $setet[$i] = $Singleday->end_time;
                $setclose[$i] = $Singleday->close;
                $i++;
            }
        } else {
            $i = 1;
            $GetBusinessHours = array('1','2','3','4','5','6','7');
            foreach($GetBusinessHours as $Singleday) {
                $setst[$i] = '';
                $setet[$i] = '';
                $setclose[$i] = '';
                $i++;
            }
        } ?>
        <table width="100%" class="items table table-bordered">
            <tr>
                <th scope="col"><?php _e('Day' ,'appointzilla'); ?></th>
                <th scope="col"><?php _e('Start Time' ,'appointzilla'); ?></th>
                <th scope="col"><?php _e('End Time' ,'appointzilla'); ?></th>
                <th scope="col"><?php _e('Close Day' ,'appointzilla'); ?></th>
                </tr>
            <tr>
                <td align="center"><?php _e('Monday' ,'appointzilla'); ?></td>
                <td>
                    <?php
                        if($setclose[1] == 'yes') $mdisable="disabled";  else $mdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=mst name=mst '.$mdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[1])  $default = $setst[1];  else $default = '10:00 AM';
                            if($setst[1] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            }  else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>'; ?>
                </td>
                <td>
                    <?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=met name=met '.$mdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[1])  $default = $setet[1];  else $default = "5:00 PM";
                            if($setst[1] == 'none')  $default = '5:00 PM';
                            //made 5:00 PM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';  ?>
                </td>
                <td align="center"><input name="mcheck" type="checkbox" id="mcheck" value="mclose" <?php if($setclose[1] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Tuesday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[2] == 'yes') $tdisable="disabled";  else $tdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=tst name=tst '.$tdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[2])  $default = $setst[2];  else $default = '10:00 AM';
                            if($setst[2] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>'; ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=tet name=tet '.$tdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[2])  $default = $setet[2];  else $default = "5:00 PM";
                            if($setst[2] == 'none')  $default = '5:00 PM';
                            //made 5:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="tucheck" type="checkbox" id="tucheck" value="tuclose" <?php if($setclose[2] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Wednesday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[3] == 'yes') $wdisable="disabled";  else $wdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=wst name=wst '.$wdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[3])  $default = $setst[3];  else $default = '10:00 AM';
                            if($setst[3] == 'none')  $default = '10:00 AM';
                            //made 5:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=wet name=wet '.$wdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[3])  $default = $setet[3];  else $default = "5:00 PM";
                            if($setst[3] == 'none')  $default = '5:00 PM';
                            //made 5:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="wcheck" type="checkbox" id="wcheck" value="wclose" <?php if($setclose[3] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Thursday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[4] == 'yes') $thdisable="disabled";  else $thdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=thst name=thst '.$thdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[4])  $default = $setst[4];  else $default = '10:00 AM';
                            if($setst[4] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            }  else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=thet name=thet '.$thdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[4])  $default = $setet[4];  else $default = "5:00 PM";
                            if($setst[4] == 'none')  $default = '5:00 PM';
                            //made 5:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="thcheck" type="checkbox" id="thcheck" value="thclose" <?php if($setclose[4] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Friday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[5] == 'yes') $fdisable="disabled";  else $fdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=fst name=fst '.$fdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[5])  $default = $setst[5];  else $default = '10:00 AM';
                            if($setst[5] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=fet name=fet '.$fdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[5])  $default = $setet[5];  else $default = "5:00 PM";
                            if($setst[5] == 'none')  $default = '5:00 PM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="fcheck" type="checkbox" id="fcheck" value="fclose" <?php if($setclose[5] == 'yes'){ echo "checked=checked"; } ?>  /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Saturday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[6] == 'yes') $satdisable="disabled";  else $satdisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=satst name=satst '.$satdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[6])  $default = $setst[6];  else $default = '10:00 AM';
                            if($setst[6] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=satet name=satet '.$satdisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[6])  $default = $setet[6];  else $default = "5:00 PM";
                            if($setst[6] == 'none')  $default = '5:00 PM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="satcheck" type="checkbox" id="satcheck" value="satclose" <?php if($setclose[6] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>

            <tr>
                <td align="center"><?php _e('Sunday' ,'appointzilla'); ?></td>
                <td><?php
                        if($setclose[7] == 'yes') $sundisable="disabled"; else $sundisable ="";
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=sunst name=sunst '.$sundisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setst[7])  $default = $setst[7];  else $default = '10:00 AM';
                            if($setst[7] == 'none')  $default = '10:00 AM';
                            //made 10:00 AM selected
                            if(date('g:i A', $i) == $default) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td><?php
                        $time = time();
                        $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                        echo '<select id=sunet name=sunet '.$sundisable.'>';
                        $start = strtotime('12:00am');
                        $end = strtotime('11:59pm');
                        for( $i = $start; $i <= $end; $i += 1800) {
                            if($setet[7])  $default = $setet[7];  else $default = "5:00 PM";
                            if($setst[7] == 'none')  $default = '5:00 PM';
                            //made 5:00 AM selected
                            if(date('g:i A', $i) == $default ) {
                                echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($BHTimeFormat, $i) . "</option>";
                            } else {
                                echo "<option value='" .date('g:i A', $i). "'>" . date($BHTimeFormat, $i) . "</option>";
                            }
                        }
                        echo '</select>';
                    ?>
                </td>
                <td align="center"><input name="suncheck" type="checkbox" id="suncheck" value="sunclose" <?php if($setclose[7] == 'yes'){ echo "checked=checked"; } ?> /></td>
            </tr>
        </table>
    </div>
<button name="saveservice" class="btn btn-primary" type="submit" id="save"><i class="icon-ok icon-white"></i> <?php _e('Save' ,'appointzilla'); ?></button>
</form>

<script type="text/javascript" src="<?php echo plugins_url('js/date.js', __FILE__); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.min.js', __FILE__); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.8.0.js', __FILE__); ?>"></script>

<script type="text/javascript">
jQuery(document).ready(function() {

    var mflag = 1, tflag = 1, wflag = 1, thflag = 1, fflag = 1, satflag = 1, sunflag = 1;

    <!--Monday-->
    //monday start-time
    jQuery('#mst').change(function(){
        var mst = jQuery('#mst').val();
        var met = jQuery('#met').val();
        //equal check
        if(mst == met) {
            alert("<?php echo __("Monday's Start-time and End-time can't be equal" ,'appointzilla'); ?>"); mflag = 0;
        }else  mflag = 1;

        //convert both time into timestamp
        var mst = new Date("November 3, 2013 " + mst);
        mst = mst.getTime();
        var met = new Date("November 3, 2013 " + met);
        met = met.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ mst + " Time2: " + met);

        if(mst > met) {
            alert("<?php echo __("Monday's Start-time must be smaller then End-time" ,'appointzilla'); ?>"); mflag = 0;
        }else  mflag = 1;
    });
    //monday end-time
    jQuery('#met').change(function(){
        var mst = jQuery('#mst').val();
        var met = jQuery('#met').val();
        //equal check
        if(mst == met) {
            alert("<?php echo __("Monday's Start-time and End-time can't be equal" ,'appointzilla'); ?>"); mflag = 0;
        }else  mflag = 1;

        //convert both time into timestamp
        var mst = new Date("November 3, 2013 " + mst);
        mst = mst.getTime();
        var met = new Date("November 3, 2013 " + met);
        met = met.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ mst + " Time2: " + met);

        if(met !=null)
        {
            if(mst > met) {
                alert("<?php echo __("Monday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); mflag = 0;
            }else  mflag = 1;
        }
    });


    <!--Tuesday-->
    //Tuesday start-time
    jQuery('#tst').change(function(){
        var st = jQuery('#tst').val();
        var et = jQuery('#tet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Tuesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>"); tflag = 0;
        }else  tflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Tuesday's Start-time must be smaller then End-time" ,'appointzilla'); ?>"); tflag = 0;
        }else  tflag = 1;
    });
    //Tuesday end-time
    jQuery('#tet').change(function(){
        var st = jQuery('#tst').val();
        var et = jQuery('#tet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Tuesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  tflag = 0;
        }else  tflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Tuesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  tflag = 0;
            }else  tflag = 1;
        }
    });


    <!--Wednesday-->
    //Wednesday start-time
    jQuery('#wst').change(function(){
        var st = jQuery('#wst').val();
        var et = jQuery('#wet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Wednesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  wflag = 0;
        }else  wflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Wednesday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");  wflag = 0;
        }else  wflag = 1;
    });
    //Wednesday end-time
    jQuery('#wet').change(function(){
        var st = jQuery('#wst').val();
        var et = jQuery('#wet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Wednesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  wflag = 0;
        }else  wflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Wednesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  wflag = 0;
            }else  wflag = 1;
        }
    });


    <!--Thursday-->
    //Thursday start-time
    jQuery('#thst').change(function(){
        var st = jQuery('#thst').val();
        var et = jQuery('#thet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Thursday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  thflag = 0;
        }else  thflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Thursday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");  thflag = 0;
        }else  thflag = 1;
    });
    //Thursday end-time
    jQuery('#thet').change(function(){
        var st = jQuery('#thst').val();
        var et = jQuery('#thet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Thursday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  thflag = 0;
        }else  thflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Thursday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  thflag = 0;
            }else  thflag = 1;
        }
    });


    <!--Friday-->
    //Friday start-time
    jQuery('#fst').change(function(){
        var st = jQuery('#fst').val();
        var et = jQuery('#fet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Friday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  fflag = 0;
        }else  fflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Friday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");  fflag = 0;
        }else  fflag = 1;
    });
    //Friday end-time
    jQuery('#fet').change(function(){
        var st = jQuery('#fst').val();
        var et = jQuery('#fet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Friday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  fflag = 0;
        }else  fflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Friday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  fflag = 0;
            }else  fflag = 1;
        }
    });


    <!--Saturday-->
    //Saturday start-time
    jQuery('#satst').change(function(){
        var st = jQuery('#satst').val();
        var et = jQuery('#satet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Saturday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  satflag = 0;
        }else  satflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Saturday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");  satflag = 0;
        }else  satflag = 1;
    });
    //Saturday end-time
    jQuery('#satet').change(function(){
        var st = jQuery('#satst').val();
        var et = jQuery('#satet').val();
        //equal check
        if(st == et) {
            alert("<?php echo __("Saturday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  satflag = 0;
        }else satflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Saturday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  satflag = 0;
            }else satflag = 1;
        }
    });


    <!--Sunday-->
    //Sunday start-time
    jQuery('#sunst').change(function(){
        var st = jQuery('#sunst').val();
        var et = jQuery('#sunet').val();

        //equal check
        if(st == et) {
            alert("<?php echo __("Sunday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  sunflag = 0;
        }else sunflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(st > et) {
            alert("<?php echo __("Sunday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");  sunflag = 0;
        }else sunflag = 1;
    });
    //Sunday end-time
    jQuery('#sunet').change(function(){
        var st = jQuery('#sunst').val();
        var et = jQuery('#sunet').val();

        //equal check
        if(st == et) {
            alert("<?php echo __("Sunday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");  sunflag = 0;
        }else sunflag = 1;

        //convert both time into timestamp
        var st = new Date("November 3, 2013 " + st);
        st = st.getTime();
        var et = new Date("November 3, 2013 " + et);
        et = et.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ st + " Time2: " + et);

        if(et !=null)
        {
            if(st > et) {
                alert("<?php echo __("Sunday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  sunflag = 0;
            }else sunflag = 1;
        }
    });

    jQuery("form").submit( function () {

        <!--Monday-->
       if(jQuery('#mcheck').is(':checked')) var mcheck = "mclose"; else var mcheck = "";
       if(!mcheck){
           var mst = jQuery('#mst').val();
           var met = jQuery('#met').val();
            //equal check
            if(mst == met) {
                alert("<?php echo __("Monday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

        //tuesday
       if(jQuery('#tucheck').is(':checked')) var tucheck = "tuclose"; else var tucheck = "";
       if(!tucheck){
           var tst = jQuery('#tst').val();
           var tet = jQuery('#tet').val();
           //equal check
            if(tst == tet) {
                alert("<?php echo __("Tuesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

        //wednesday
       if(jQuery('#wcheck').is(':checked')) var wcheck = "wclose"; else var wcheck = "";
       if(!wcheck)
       {
           var wst = jQuery('#wst').val();
           var wet = jQuery('#wet').val();
           //equal check
            if(wst == wet) {
                alert("<?php echo __("Wednesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

        //thursday
       if(jQuery('#thcheck').is(':checked')) var thcheck = "thclose"; else var thcheck = "";
       if(!thcheck)
       {
           var thst = jQuery('#thst').val();
           var thet = jQuery('#thet').val();
           //equal check
            if(thst == thet) {
                alert("<?php echo __("Thursday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

       //friday
       if(jQuery('#fcheck').is(':checked')) var fcheck = "fclose"; else var fcheck = "";
       if(!fcheck)
       {
           var fst = jQuery('#fst').val();
           var fet = jQuery('#fet').val();
            //equal check
            if(fst == fet) {
                alert("<?php echo __("Friday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

        //saturday
       if(jQuery('#satcheck').is(':checked')) var satcheck = "satclose"; else var satcheck = "";
       if(!satcheck)
       {
           var satst = jQuery('#satst').val();
           var satet = jQuery('#satet').val();
           //equal check
            if(satst == satet) {
                alert("<?php echo __("Saturday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }

      //sunday
       if(jQuery('#suncheck').is(':checked')) var suncheck = "sunclose"; else var suncheck = "";
       if(!suncheck)
       {
           var sunst = jQuery('#sunst').val();
           var sunet = jQuery('#sunet').val();
           //equal check
            if(sunst == sunet) {
                alert("<?php echo __("Sunday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
            }
        }


       if(mflag != 1) { alert("<?php echo __("ERROR! Check Monday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(tflag != 1) { alert("<?php echo __("ERROR! Check Tuesday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(wflag != 1) { alert("<?php echo __("ERROR! Check Wednesday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(thflag != 1) { alert("<?php echo __("ERROR! Check Thursday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(fflag != 1) { alert("<?php echo __("ERROR! Check Friday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(satflag != 1) { alert("<?php echo __("ERROR! Check Saturday's Working Hours" ,'appointzilla'); ?>"); return false; }
       if(sunflag != 1) { alert("<?php echo __("ERROR! Check Sunday's Working Hours" ,'appointzilla'); ?>"); return false; }

       //if(mflag  && tflag && wflag && thflag && fflag && satflag && sunflag) { alert("Success! Working Hours Saved"); return true; }
    });

    //disable monday times
    jQuery('#mcheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#mst').attr("disabled", true);
        jQuery('#met').attr("disabled", true);
      } else {
        jQuery('#mst').attr("disabled", false);
        jQuery('#met').attr("disabled", false);
      }
    });

    //disable tuesday times
    jQuery('#tucheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#tst').attr("disabled", true);
        jQuery('#tet').attr("disabled", true);
      } else {
        jQuery('#tst').attr("disabled", false);
        jQuery('#tet').attr("disabled", false);
      }
    });


    //disable wednesday times
    jQuery('#wcheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#wst').attr("disabled", true);
        jQuery('#wet').attr("disabled", true);
      } else {
        jQuery('#wst').attr("disabled", false);
        jQuery('#wet').attr("disabled", false);
      }
    });

    //disable thusday times
    jQuery('#thcheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#thst').attr("disabled", true);
        jQuery('#thet').attr("disabled", true);
      } else {
        jQuery('#thst').attr("disabled", false);
        jQuery('#thet').attr("disabled", false);
      }
    });

    //disable friday times
    jQuery('#fcheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#fst').attr("disabled", true);
        jQuery('#fet').attr("disabled", true);
      } else {
        jQuery('#fst').attr("disabled", false);
        jQuery('#fet').attr("disabled", false);
      }
    });

    //disable saturday times
    jQuery('#satcheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#satst').attr("disabled", true);
        jQuery('#satet').attr("disabled", true);
      } else {
        jQuery('#satst').attr("disabled", false);
        jQuery('#satet').attr("disabled", false);
      }
    });

    //disable sunday times
    jQuery('#suncheck').change(function(){
      if(jQuery(this).is(':checked')){
        jQuery('#sunst').attr("disabled", true);
        jQuery('#sunet').attr("disabled", true);
      } else {
        jQuery('#sunst').attr("disabled", false);
        jQuery('#sunet').attr("disabled", false);
      }
    });
});
</script>