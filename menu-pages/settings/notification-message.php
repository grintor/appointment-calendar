<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><?php _e('Notification Message','appointzilla'); ?></h3> 
</div>
<?php $LoadingImageUrl = plugins_url('appointment-calendar-premium/images/loading.gif'); ?>

<script src="<?php echo plugins_url('/js/jquery-1.8.0.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap-tab.js', __FILE__); ?>" type="text/javascript"></script>

<div class="tabbable" id="myTabs">
    <ul class="nav nav-tabs tabs-left">
        <li class="active"><a href="#tab1" data-toggle="tab"><strong><?php _e("Client Notifications","appointzilla"); ?></a></strong></li>
        <li><a href="#tab2" data-toggle="tab"><strong><?php _e("Admin Notifications","appointzilla"); ?></strong></a></li>
        <li><a href="#tab3" data-toggle="tab"><strong><?php _e("Staff Notifications","appointzilla"); ?></strong></a></li>
    </ul>

    <div class="tab-content" style="border-radius: 4px 0 4px 0;">
        <!--notification message for client-->
        <div class="tab-pane active" id="tab1">
            <!--vertical tabs for client-->
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs nav-pills nav-stacked">
                    <li class="active"><a data-toggle="tab" href="#lA"><i class="icon-chevron-right"></i> <?php _e("New Appointment","appointzilla"); ?></a></li>
                    <li><a data-toggle="tab" href="#lB"><i class="icon-chevron-right"></i> <?php _e("Approve Appointment","appointzilla"); ?></a></li>
                    <li><a data-toggle="tab" href="#lC"><i class="icon-chevron-right"></i> <?php _e("Cancel Appointment","appointzilla"); ?></a></li>
                    <li>
                    <br>
                    <div style="padding: 5px;">
                        <button class="btn btn-small btn-inverse" type="button"><?php _e('Use Below Tags in Message','appointzilla'); ?></button><br>
                        <?php _e('Client Name','appointzilla'); ?> - [client-name]<br>
                        <?php _e('Client Email','appointzilla'); ?> - [client-email]<br>
                        <?php _e('Client Phone','appointzilla'); ?> - [client-phone]<br>
                        <?php _e('Client Special Instruction','appointzilla'); ?> - [client-si]<br>
                        <?php _e('Service Name','appointzilla'); ?> - [service-name]<br>
                        <?php _e('Staff Name','appointzilla'); ?> - [staff-name]<br>
                        <?php _e('Appointment Date','appointzilla'); ?> - [app-date]<br>
                        <?php _e('Appointment Status','appointzilla'); ?> - [app-status]<br>
                        <?php _e('Appointment Time','appointzilla'); ?> - [app-time]<br>
                        <?php _e('Appointment Key','appointzilla'); ?> - [app-key]<br>
                        <?php /*_e('Appointment Note','appointzilla'); */?><!-- - [app-note]<br>-->
                        <?php _e('Blog Name','appointzilla'); ?> - [blog-name]
                    </div>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="lA" class="tab-pane active">
                        <!--notify client on booking appointment-->
                        <h4><?php _e('Notify Client On New Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="booking_client_subject" type="text" id="booking_client_subject" style="width: 600px;" value="<?php echo get_option('booking_client_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td>
                                    <textarea name="booking_client_body" id="booking_client_body" style="height: 320px; width: 600px;"><?php echo get_option('booking_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" value="" id="booking_client_message" name="booking_client_message" onclick="return ClickOnSave('booking_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-booking_client" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="lB" class="tab-pane">
                        <!--notify client on approve appointment-->
                        <h4><?php _e('Notify Client On Approve Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="approve_client_subject" type="text" id="approve_client_subject" value="<?php echo get_option('approve_client_subject'); ?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="approve_client_body" id="approve_client_body" style="height: 320px; width: 600px;"><?php echo get_option('approve_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td><button type="button" class="btn btn-primary" id="approve_client_message" name="approve_client_message" onclick="return ClickOnSave('approve_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-approve_client" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="lC" class="tab-pane">
                        <!--notify client on cancel appointment-->
                        <h4><?php _e('Notify Client On Cancel Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="cancel_client_subject" type="text" id="cancel_client_subject" value="<?php echo get_option('cancel_client_subject'); ?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="cancel_client_body" id="cancel_client_body" style="height: 320px; width: 600px;"><?php echo get_option('cancel_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="cancel_client_message" name="cancel_client_message" onclick="return ClickOnSave('cancel_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-cancel_client" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--End of client notification tab-->


        <!--notification message for admin-->
        <div class="tab-pane" id="tab2">
            <!--vertical tabs for client-->
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs nav-pills nav-stacked">
                    <li class="active"><a data-toggle="tab" href="#2A"><i class="icon-chevron-right"></i> <?php _e("New Appointment","appointzilla"); ?></a></li>
                    <li><a data-toggle="tab" href="#2C"><i class="icon-chevron-right"></i> <?php _e("Cancel Appointment","appointzilla"); ?></a></li>
                    <li>
                        <br>
                        <div style="padding: 5px;">
                            <button class="btn btn-small btn-inverse" type="button"><?php _e('Use Below Tags in Message','appointzilla'); ?></button><br>
                            <?php _e('Client Name','appointzilla'); ?> - [client-name]<br>
                            <?php _e('Client Email','appointzilla'); ?> - [client-email]<br>
                            <?php _e('Client Phone','appointzilla'); ?> - [client-phone]<br>
                            <?php _e('Client Special Instruction','appointzilla'); ?> - [client-si]<br>
                            <?php _e('Service Name','appointzilla'); ?> - [service-name]<br>
                            <?php _e('Staff Name','appointzilla'); ?> - [staff-name]<br>
                            <?php _e('Appointment Date','appointzilla'); ?> - [app-date]<br>
                            <?php _e('Appointment Status','appointzilla'); ?> - [app-status]<br>
                            <?php _e('Appointment Time','appointzilla'); ?> - [app-time]<br>
                            <?php _e('Appointment Key','appointzilla'); ?> - [app-key]<br>
                            <?php /*_e('Appointment Note','appointzilla'); */?><!-- - [app-note]<br>-->
                            <?php _e('Blog Name','appointzilla'); ?> - [blog-name]
                        </div>
                    </li>
                </ul>
                <div class="tab-content">

                    <div id="2A" class="tab-pane active">
                        <!--notify client on booking appointment-->
                        <h4><?php _e('Notify Admin On New Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="booking_admin_subject" type="text" id="booking_admin_subject" style="width: 600px;" value="<?php echo get_option('booking_admin_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td>
                                    <textarea name="booking_admin_body" id="booking_admin_body" style="height: 320px; width: 600px;"><?php echo get_option('booking_admin_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" value="" id="booking_admin_message" name="booking_admin_message" onclick="return ClickOnSave('booking_admin');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-booking_admin" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--notify admin on approve appointment-->
                    <!--<div id="2B" class="tab-pane">
                        <h4><?php /*_e('Notify Admin On Approve Appointment','appointzilla'); */?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php /*_e('Subject','appointzilla'); */?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="approve_admin_subject" type="text" id="approve_admin_subject" value="<?php /*echo get_option('approve_admin_subject'); */?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php /*_e('Body','appointzilla'); */?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="approve_admin_body" id="approve_admin_body" style="height: 320px; width: 600px;"><?php /*echo get_option('approve_admin_body'); */?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td><button type="button" class="btn btn-primary" id="approve_admin_message" name="approve_admin_message" onclick="return ClickOnSave('approve_admin');"><i class="icon-ok icon-white"></i> <?php /*_e('Save','appointzilla'); */?></button>
                                    <div id="loading-approve_admin" style="display: none;"><?php /*_e('Saving message...', 'appointzilla'); */?><img src="<?php /*echo $LoadingImageUrl; */?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>-->

                    <div id="2C" class="tab-pane">
                        <!--notify admin on cancel appointment-->
                        <h4><?php _e('Notify Admin On Cancel Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="cancel_admin_subject" type="text" id="cancel_admin_subject" value="<?php echo get_option('cancel_admin_subject'); ?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="cancel_admin_body" id="cancel_admin_body" style="height: 320px; width: 600px;"><?php echo get_option('cancel_admin_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="cancel_admin_message" name="cancel_admin_message" onclick="return ClickOnSave('cancel_admin');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-cancel_admin" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--End of admin notification tab-->



        <!--notification message for staff-->
        <div class="tab-pane" id="tab3">
            <!--vertical tabs for staff-->
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs nav-pills nav-stacked">
                    <li class="active"><a data-toggle="tab" href="#3A"><i class="icon-chevron-right"></i> <?php _e("New Appointment","appointzilla"); ?></a></li>
                    <li class=""><a data-toggle="tab" href="#3B"><i class="icon-chevron-right"></i> <?php _e("Approve Appointment","appointzilla"); ?></a></li>
                    <li class=""><a data-toggle="tab" href="#3C"><i class="icon-chevron-right"></i> <?php _e("Cancel Appointment","appointzilla"); ?></a></li>
                    <li>
                        <br>
                        <div style="padding: 5px;">
                            <button class="btn btn-small btn-inverse" type="button"><?php _e('Use Below Tags in Message','appointzilla'); ?></button><br>
                        <?php _e('Client Name','appointzilla'); ?> - [client-name]<br>
                        <?php _e('Client Email','appointzilla'); ?> - [client-email]<br>
                        <?php _e('Client Phone','appointzilla'); ?> - [client-phone]<br>
                        <?php _e('Client Special Instruction','appointzilla'); ?> - [client-si]<br>
                        <?php _e('Service Name','appointzilla'); ?> - [service-name]<br>
                        <?php _e('Staff Name','appointzilla'); ?> - [staff-name]<br>
                        <?php _e('Appointment Date','appointzilla'); ?> - [app-date]<br>
                        <?php _e('Appointment Status','appointzilla'); ?> - [app-status]<br>
                        <?php _e('Appointment Time','appointzilla'); ?> - [app-time]<br>
                        <?php _e('Appointment Key','appointzilla'); ?> - [app-key]<br>
                        <?php /*_e('Appointment Note','appointzilla'); */?><!-- - [app-note]<br>-->
                        <?php _e('Blog Name','appointzilla'); ?> - [blog-name]
                    </li>
                </ul>

                <div class="tab-content">

                    <div id="3A" class="tab-pane active">
                        <!--notify staff on new appointment-->
                        <h4><?php _e('Notify Staff On New Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="booking_staff_subject" type="text" id="booking_staff_subject" style="width: 600px;" value="<?php echo get_option('booking_staff_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td>
                                    <textarea name="booking_staff_body" id="booking_staff_body" style="height: 320px; width: 600px;"><?php echo get_option('booking_staff_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" value="" id="booking_staff_message" name="booking_staff_message" onclick="return ClickOnSave('booking_staff');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-booking_staff" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="3B" class="tab-pane">
                        <!--notify staff on approve appointment-->
                        <h4><?php _e('Notify Staff On Approve Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="approve_staff_subject" type="text" id="approve_staff_subject" style="width: 600px;" value="<?php echo get_option('approve_staff_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td>
                                    <textarea name="approve_staff_body" id="approve_staff_body" style="height: 320px; width: 600px;"><?php echo get_option('approve_staff_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="approve_staff_message" name="approve_staff_message" onclick="return ClickOnSave('approve_staff');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-approve_staff" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="3C" class="tab-pane">
                        <!--notify staff on cancel appointment-->
                        <h4><?php _e('Notify Staff On Cancel Appointment','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="cancel_staff_subject" type="text" id="cancel_staff_subject" style="width: 600px;" value="<?php echo get_option('cancel_staff_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="cancel_staff_body" id="cancel_staff_body" style="height: 320px; width: 600px;"><?php echo get_option('cancel_staff_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="cancel_staff_message" name="cancel_staff_message" onclick="return ClickOnSave('cancel_staff');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-cancel_staff" style="display: none;"><?php _e('Saving message...', 'appointzilla'); ?><img src="<?php echo $LoadingImageUrl; ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
            <!--End of vertical tabs for staff-->
        </div>
        <!--End of client notification tab-->
    </div>
</div>
<!--End of tabs-->

<?php
// client on booking
if(isset($_POST['action'])) {
    $Action = $_POST['action'];
    $Subject = $_POST['Subject'];
    $Body = $_POST['Body']; // wp_specialchars( $_POST['Body'], 0 );
    if( $Action == "booking_client") {
        update_option('booking_client_subject', $Subject);
        update_option('booking_client_body', $Body);
    }

    // client on approve
    if( $Action == "approve_client" ) {
        update_option('approve_client_subject', $Subject);
        update_option('approve_client_body', $Body);
    }

    // client on cancel
    if( $Action == "cancel_client" ) {
        update_option('cancel_client_subject', $Subject);
        update_option('cancel_client_body', $Body);

    }

    // admin on booking
    if( $Action == "booking_admin" ) {
        update_option('booking_admin_subject', $Subject);
        update_option('booking_admin_body', $Body);
    }

    // admin on cancel
    if( $Action == "cancel_admin" ) {
        update_option('cancel_admin_subject', $Subject);
        update_option('cancel_admin_body', $Body);
    }

    // staff on booking
    if( $Action == "booking_staff" ) {
        update_option('booking_staff_subject', $Subject);
        update_option('booking_staff_body', $Body);
    }

    // staff on approve
    if( $Action ==  "approve_staff" ) {
        update_option('approve_staff_subject', $Subject);
        update_option('approve_staff_body', $Body);
    }

    // staff on cancel
    if( $Action ==  "cancel_staff" ) {
        update_option('cancel_staff_subject', $Subject);
        update_option('cancel_staff_body', $Body);
    }
}
?>

<script>
//tabs js code
jQuery('#tabAll').click(function(){
    jQuery('#tabAll').addClass('active');
    jQuery('.tab-pane').each(function(i,t){
        jQuery('#myTabs li').removeClass('active');
        jQuery(this).addClass('active');
    });
});

//save notification messages
function ClickOnSave(MessageToSave) {
    var Subject;
    var Body;
    Subject = jQuery("#" + MessageToSave + "_subject" ).val();
    Body = jQuery("#" + MessageToSave + "_body" ).val();
    jQuery('#loading-' + MessageToSave).show();
    jQuery.ajax({
        dataType : 'html',
        type: 'POST',
        url : location.href,
        cache: false,
        data : { 'action': MessageToSave, 'Subject': Subject, 'Body': Body },
        complete : function() { },
        success: function() {
            jQuery('#loading-' + MessageToSave).hide();
            alert("<?php _e('Message successfully saved.','appointzilla'); ?>");
        }
    });
}
</script>

