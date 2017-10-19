<?php // shoet code file for mobile
add_shortcode( 'APCAL_MOBILE', 'appointment_calendar_mobile_shortcode' );
function appointment_calendar_mobile_shortcode() {
    //ob_start();
    $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";
    $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
    $small_calendar_type = $AllCalendarSettings['small_calendar_type'];
    if($AllCalendarSettings['calendar_start_day'] != '') {
        $CalStartDay = $AllCalendarSettings['calendar_start_day'];
    } else {
        $CalStartDay = 1;
    } ?>

    <!---load bootstrap css--->
    <script type='text/javascript'>
    jQuery(document).ready(function() {
        // Modal Form Works - show first modal
        jQuery('#addappointment').click(function(){
            jQuery('#addappointment').hide();
            jQuery('#bkbtndiv').hide();
            jQuery('#AppForthModal').hide();
            jQuery('#AppFirstModal').show();
        });

        //hide modal
        jQuery('#close').click(function(){
            jQuery('#AppFirstModal').hide();
        });
        <?php if($DateFormat == 'd-m-Y') $DPFormat = 'dd MM yy';
        if($DateFormat == 'm-d-Y') $DPFormat = 'MM dd yy';
        if($DateFormat == 'Y-m-d') $DPFormat = 'dd MM yy'; //coz yy-mm-dd not parsing in a correct date

        if($small_calendar_type == "jquery") { ?>
        //jQuery UI date picker on modal for
        document.addnewappointment.appdate.value = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date());
        jQuery(function(){
            jQuery("#datepicker").datepicker({
                inline: true,
                minDate: 0,
                altField: '#alternate',
                firstDay: <?php if($AllCalendarSettings['calendar_start_day'] != '') echo $AllCalendarSettings['calendar_start_day']; else echo "0";  ?>,
                //beforeShowDay: unavailable,
                onSelect: function(dateText, inst) {
                    var dateAsString = dateText;
                    var seleteddate = jQuery.datepicker.formatDate('<?php echo $DPFormat; ?>', new Date(dateAsString));
                    var seleteddate2 = jQuery.datepicker.formatDate('dd-mm-yy', new Date(dateAsString));
                    document.addnewappointment.appdate.value = seleteddate;
                    document.addnewappointment.appdate2.value = seleteddate2;
                },
            });
            //jQuery( "#datepicker" ).datepicker( jQuery.datepicker.regional[ "af" ] );
        });
        <?php  } ?>

        //AppFirstModal Validation
        jQuery('#next1').click(function(){
            jQuery(".error").hide();
            if(jQuery('#service').val() == 0) {
                jQuery("#service").after("<span class='error'><br><strong><?php _e('Select Any Service.', 'appointzilla'); ?></strong><br></span>");
                return false;
            }
        });

    });


   // Modal Form Works
    function loadseconmodal() {
        var ServiceId = jQuery('#servicelist').val();
        var AppDate = jQuery('#appdate2').val();
        var StaffId = jQuery('#stafflist').val();
        var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId;
        jQuery('#loading1').show();     // loading button onclick next1 at first modal
        jQuery('#next1').hide();        //hide next button
        jQuery.ajax({
            dataType : 'html',
            type: 'GET',
            url : location.href,
            cache: false,
            data : SecondData,
            complete : function() {  },
            success: function(data) {
                data = jQuery(data).find('div#AppSecondModalData');
                jQuery('#loading1').hide();
                jQuery('#AppFirstModal').hide();
                jQuery('#AppSecondModal').show();
                jQuery('#AppSecondModal').html(data);
            }
        });
    }

    //load first modal on click back1
    function loadfirstmodal() {
        jQuery('#AppSecondModal').hide()
        jQuery('#AppFirstModal').show();
        jQuery('#next1').show();
    }

    //load second modal on back2 click
    function LoadSecondModal2() {
        jQuery('#AppThirdModal').hide();
        jQuery('#buttondiv').show();
        jQuery('#AppSecondModal').show();
    }

    //on new user button click
    function NewUserBtn() {
        jQuery('#new-user-div').show();
        jQuery('#existing-user-div').hide();
        jQuery('#check-email-result-div').hide();
    }

    //on existing user button click
    function ExistingUserBtn() {
        jQuery('#new-user-div').hide();
        jQuery('#existing-user-div').show();
        jQuery('#check-email-div-form').show();
    }

    //load third modal on-click next2
    function loadthirdmodal() {
        //validation on second modal form
        jQuery('.error').hide();
        var ServiceId = jQuery('#ServiceId').val();
        var AppDate = jQuery('#AppDate').val();
        var StaffId = jQuery('#StaffId').val();
        var Start_Time = jQuery('input[name=start_time]:radio:checked').val();
        if(!Start_Time) {
            jQuery("#time_slot_box").after("<span class='error'><strong><?php _e('Select any time.', 'appointzilla'); ?></strong></span>");
            return false;
        }
        var ThirdData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + Start_Time ;
        jQuery('#buttondiv').hide();
        jQuery('#loading').show();
        jQuery.ajax({
            dataType : 'html',
            type: 'GET',
            url : location.href,
            cache: false,
            data : ThirdData,
            complete : function() {  },
            success: function(data) {
                data = jQuery(data).find('div#AppThirdModalData');
                jQuery('#loading').hide();
                jQuery('#AppSecondModal').hide();
                jQuery('#AppThirdModal').show();
                //jQuery('#formbtndiv').show();
                jQuery('#AppThirdModal').html(data);
            }
        });
    }

    //load forth final modal for confirm appointment
    function CheckValidation(UserType) {

        jQuery(".error").hide();
        var ServiceId = jQuery('#ServiceId').val();
        var AppDate = jQuery('#AppDate').val();
        var StaffId = jQuery('#StaffId').val();
        var StartTime =  jQuery('#StartTime').val();

        /**
         * new user booking case
         */
        if(UserType == "NewUser") {
            <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
            var ClientUserName = jQuery("#client-username").val();
            var ClientPassword = jQuery("#client-password").val();
            var ClientConfirmPassword = jQuery("#client-confirm-password").val();
            <?php } ?>
            var ClientEmail = jQuery("#client-email").val();
            var ClientFirstName = jQuery("#client-first-name").val();
            var ClientLastName = jQuery("#client-last-name").val();
            var ClientPhone = jQuery("#client-phone").val();
            var ClientSi = jQuery("#client-si").val();

            <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
            //client username
            if (ClientUserName == "") {
                jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Userame required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(ClientUserName.length < 6) {
                    jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Choose a strong username.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var Res = isNaN(ClientUserName);
                if(Res == false) {
                    jQuery("#client-username").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid username.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client password
            if (ClientPassword == "") {
                jQuery("#client-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Password required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(ClientPassword.length < 6) {
                    jQuery("#client-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Choose a strong password.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client confirm password
            if (ClientConfirmPassword == "") {
                jQuery("#client-confirm-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Confirm password required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(ClientConfirmPassword != ClientPassword) {
                    jQuery("#client-confirm-password").after("<span class='error'>&nbsp;<br><strong><?php _e('Confirm password do not match', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }
            <?php } ?>

            //client email
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (ClientEmail == "") {
                jQuery("#client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(regex.test(ClientEmail) == false ) {
                    jQuery("#client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client first name
            if (ClientFirstName == "") {
                jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('First name required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                var Res = isNaN(ClientFirstName);
                if(Res == false) {
                    jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid first name.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var NameRegx = /^[a-zA-Z0-9- ]*$/;
                if(NameRegx.test(ClientFirstName) == false) {
                    jQuery("#client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client last name
            if (ClientLastName == "") {
                jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Last name required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                var Res = isNaN(ClientLastName);
                if(Res == false) {
                    jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid last name.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var NameRegx = /^[a-zA-Z0-9- ]*$/;
                if(NameRegx.test(ClientLastName) == false) {
                    jQuery("#client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client phone
            if (ClientPhone == "") {
                jQuery("#client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Phone required. <br>Only Numbers 1234567890.", "appointzilla"); ?></strong></span>");
                return false;
            } else {
                var ClientPhoneRes = isNaN(ClientPhone);
                if(ClientPhoneRes == true) {
                    jQuery("#client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Invalid phone. <br>Numbers only: 1234567890.", "appointzilla"); ?></strong></span>");
                    return false;
                }
            }

            var PostData1 = "Action=BookAppointment"+ "&ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + StartTime;
            <?php if($AllCalendarSettings['apcal_user_registration'] == "yes"){ ?>
            var PostData2 = "&UserType=" + UserType + "&ClientUserName="+ ClientUserName + "&ClientPassword=" +ClientPassword + "&ClientEmail=" + ClientEmail;
            <?php } else { ?>
            var PostData2 = "&UserType=" + UserType + "&ClientEmail=" + ClientEmail;
            <?php } ?>
            var PostData3 =  "&ClientFirstName=" + ClientFirstName + "&ClientLastName=" + ClientLastName + "&ClientPhone=" + ClientPhone + "&ClientNote=" + ClientSi;
            var PostData = PostData1 + PostData2 + PostData3;

            jQuery('#new-user-form-btn-div').hide();
            jQuery('#new-user-form-loading-img').show();
        }

        /**
         * existing user booking case
         */
        if(UserType == "ExUser") {
            var ClientEmail = jQuery("#ex-client-email").val();
            var ClientFirstName = jQuery("#ex-client-first-name").val();
            var ClientLastName = jQuery("#ex-client-last-name").val();
            var ClientPhone = jQuery("#ex-client-phone").val();
            var ClientSi = jQuery("#ex-client-si").val();

            //client email
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (ClientEmail == "") {
                jQuery("#ex-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(regex.test(ClientEmail) == false ) {
                    jQuery("#ex-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client first name
            if (ClientFirstName == "") {
                jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('First name required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                var Res = isNaN(ClientFirstName);
                if(Res == false) {
                    jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid first name.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var NameRegx = /^[a-zA-Z0-9- ]*$/;
                if(NameRegx.test(ClientFirstName) == false) {
                    jQuery("#ex-client-first-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client last name
            if (ClientLastName == "") {
                jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Last name required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                var Res = isNaN(ClientLastName);
                if(Res == false) {
                    jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid last name.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var NameRegx = /^[a-zA-Z0-9- ]*$/;
                if(NameRegx.test(ClientLastName) == false) {
                    jQuery("#ex-client-last-name").after("<span class='error'>&nbsp;<br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            //client phone
            if (ClientPhone == "") {
                jQuery("#ex-client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Phone required. <br>Only Numbers 1234567890.", "appointzilla"); ?></strong></span>");
                return false;
            } else {
                var ClientPhoneRes = isNaN(ClientPhone);
                if(ClientPhoneRes == true) {
                    jQuery("#ex-client-phone").after("<span class='error'>&nbsp;<br><strong><?php _e("Invalid phone. <br>Numbers only: 1234567890.", "appointzilla"); ?></strong></span>");
                    return false;
                }
            }

            var PostData1 = "Action=BookAppointment"+ "&ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StaffId=" + StaffId + "&StartTime=" + StartTime;
            var PostData2 = "&UserType=" + UserType + "&ClientEmail=" + ClientEmail;
            var PostData3 =  "&ClientFirstName=" + ClientFirstName + "&ClientLastName=" + ClientLastName + "&ClientPhone=" + ClientPhone + "&ClientNote=" + ClientSi;
            var PostData = PostData1 + PostData2 + PostData3;
            jQuery('#ex-user-form-btn-div').hide();
            jQuery('#ex-user-form-loading-img').show();
        }

        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                data = jQuery(data).find('div#AppForthModalData');
                jQuery("#new-user-form-loading-img").hide();
                jQuery("#check-email-div-form").hide();
                jQuery("#AppThirdModal").hide();
                jQuery("#AppForthModalFinal").show();
                jQuery("#AppForthModalFinal").html(data);
            }
        });
    }

    //check existing user
    function CheckExistingUser() {
        jQuery(".error").hide();
        var ClientEmail = jQuery("#check-client-email").val();
        //client email
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (ClientEmail == "") {
            jQuery("#check-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            if(regex.test(ClientEmail) == false ) {
                jQuery("#check-client-email").after("<span class='error'>&nbsp;<br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        var PostData = "Action=CheckExistingUser" + "&ClientEmail=" + ClientEmail;
        jQuery("#existing-user-form-btn").hide();
        jQuery("#existing-user-loading-img").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(Data) {
                Data = jQuery(Data).find('div#check-email-result');
                jQuery("#existing-user-loading-img").hide();
                jQuery("#check-user").hide();
                jQuery('#check-email-div-form').hide();
                jQuery("#check-email-result-div").show();
                jQuery("#check-email-result-div").html(Data);
            }
        });

    }

    function CloseModelform() {
        jQuery('#AppForthModalFinal').hide();
        jQuery('#AppSecondModalData').hide();
        jQuery('#AppThirdModalData').hide();
        location.href = location.href;
    }

    function highlightsradio(timeslotspanid) {
        jQuery('span').removeClass('selected');
        var spanid = "#" + timeslotspanid;
        jQuery(spanid).addClass("selected");
    }

    // failed appointment
    function failedappointment() {
        var appid = jQuery('#appid').val();
        var Datastring = "appstatus=cancel" + "&appid="+ appid;
        var currenturl = jQuery(location).attr('href');
        var url = currenturl;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : url,
            cache: false,
            data : Datastring,
            complete : function() {  },
            success: function(data) {
                jQuery('#AppForthModalFinal').hide();
                jQuery('#AppSecondModalData').hide();
                jQuery('#AppThirdModalData').hide();
                var currenturl = jQuery(location).attr('href');
                currenturl=currenturl.replace('failed=failed&appointId='+appid, '');
                location.href = currenturl;
            }
        });
    }

    function CancelAppointment() {
        var appid = jQuery('#appid').val();
        var DataString = "appstatus=cancel" + "&appid="+ appid;
        jQuery("#paybuttondiv").hide();
        jQuery("#pay-canceling-img").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : DataString,
            complete : function() {  },
            success: function() {
                jQuery('#AppForthModalFinal').hide();
                jQuery('#AppSecondModalData').hide();
                jQuery('#AppThirdModalData').hide();
                window.location.reload();
            }
        });
    }

    //apply coupon code
    function ApplyCoupon() {
        var CouponCode = jQuery("#coupon-code").val();
        if(CouponCode == "") {
            alert("<?php _e("Enter any coupon code.", "appointzilla"); ?>");
            jQuery("#coupon-code").focus();
            return false;
        } else {
            var PostData = "Action=apply-coupon" + "&CouponCode=" + CouponCode;
            jQuery("#loading-img").show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function(ReturnedData) {
                    ReturnedData = jQuery(ReturnedData).find("div#coupon-result");
                    jQuery("#loading-img").hide();
                    jQuery("#apply-coupon-div").hide();
                    jQuery("#show-coupon-result").html(ReturnedData);
                    jQuery("#show-coupon-result").show();
                    var CouponCodeValue = jQuery("#coupon-code-div").text();
                    var DicountRateValue = jQuery("#discount-rate-div").text();
                    //alert(CouponCodeValue + DicountRateValue);
                    jQuery("input[name=custom]").val(CouponCodeValue);
                    jQuery("input[name=discount_rate]").val(DicountRateValue);
                }
            });
        }
    }

    //try another coupon code
    function TryAgain() {
        jQuery("#apply-coupon-div").show();
        jQuery("#show-coupon-result").hide();
    }

    //try again booking
    function TryAgainBooking() {
        jQuery("#check-email-result-div").hide();
        jQuery("#check-user").show();
        jQuery('#check-email-div-form').show();
        jQuery("#existing-user-form-btn").show();
    }

    //cancel appointment
    function Canceling() {
        jQuery("#ex-user-form-btn-div").hide();
        jQuery("#ex-canceling-img").show();
        location.reload();
    }

    // on paypal pay button click
    function PayWithPaypal(){
        jQuery('#show-redirecting-msg').show();
        jQuery('#paybuttondiv').hide();
    }
    </script>

    <style type='text/css'>
    .selected {
        outline:1px solid #FF0000; /* Firefox, Opera, Chrome, IE8+ */
        background-color:#FFFF99;
    }
    .error{
        color: #FF0000;
    }
    /*first modal- 2nd div confilicts css*/
    .entry form {
        text-align: left;
    }
    </style>

    <!---Display Booking Instruction--->
    <?php if($AllCalendarSettings['apcal_booking_instructions']) { ?>
    <div id="bookinginstructions" align="center">
        <?php echo $AllCalendarSettings['apcal_booking_instructions']; ?>
    </div>
    <?php } ?>

    <!--- Schedule An Appointment Button--->
    <div id=bkbtndiv align="center" style="padding:10px;">
        <button name="addappointment" class="apcal_btn apcal_btn-large apcal_btn-primary" type="submit" id="addappointment">
            <i class="icon-calendar icon-white"></i> <strong>
            <?php $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
                if(isset($AllCalendarSettings['booking_button_text'])) {
                    echo $AllCalendarSettings['booking_button_text'];
                } else {
                    _e('Schedule New Appointment', 'appointzilla');
                } ?>
            </strong>
        </button>
        <!---Show powered By--->
        <div style="text-align: center; font-size: xx-small;"><br>Appointment Calendar Premium Powered By: <a href="http://appointzilla.com/" title="Online Appointment Scheduling Plugin For WordPress" target="_blank">AppointZilla</a></div>
    </div>

    <!---AppFirstModal For Schedule New Appointment--->
    <div id="AppFirstModal" style="float:right; display:none; width:100%; height:auto; padding-bottom:5px;">
        <div id="apcal_myModal" style="z-index:10000;">
            <form action="" method="post" name="addnewappointment" id="addnewappointment">
                <div>
                    <div class="apcal_alert apcal_alert-info">
                        <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p>
                        <div><?php _e('Step 1. Select Time & Service', 'appointzilla'); ?></div>
                    </div>
                </div>

                <div>
                    <div class="col-md-6" id="firdiv" style="border: 0px solid red; display: inline-block;">
                        <!--JS Datepicker -->
                        <?php if($small_calendar_type == "jquery") { ?>
                            <div id="datepicker"></div>
                        <?php } ?>

                        <!--PHP Datepicker-->
                        <?php
                        if($DateFormat == 'd-m-Y') $CalFormat = 'DD-MM-YYYY';
                        if($DateFormat == 'm-d-Y') $CalFormat = 'MM-DD-YYYY';
                        if($DateFormat == 'Y-m-d') $CalFormat = 'YYYY-MM-DD'; //coz yy-mm-dd not

                        if(get_locale()) {
                            $language = get_locale();
                            if($language) { define('L_LANG',$language); }
                        }

                        //disable closed business days on small datepicker
                        global $wpdb;
                        $ClosedDays = array();
                        $StartDate = date("Y-m-d");
						$StartDate = date('Y-m-d',strtotime($StartDate . "+1 days"));
                        $EndDate = "2035-01-01";
                        /*$BusinessHoursTable = $wpdb->prefix . "ap_business_hours";
                        $ClosedBusinessDays = $wpdb->get_results("SELECT * FROM `$BusinessHoursTable` WHERE `close` LIKE 'yes'");
                        if(count($ClosedBusinessDays)) {
                            foreach($ClosedBusinessDays as $ClosedBusinessDay) {
                                $ClosedDays[] = ucfirst(substr($ClosedBusinessDay->day, 0, 3));
                            }
                        }*/
                        require_once( plugin_dir_path( __FILE__ ) . 'calendar/tc_calendar.php');
                        $curr_date = date("Y-m-d", time());
                        $DatePicker2 = plugins_url('calendar/', __FILE__);
                        $myCalendar = new tc_calendar("date1");
                        foreach($ClosedDays as $Day) {
                            $myCalendar->disabledDay("$Day");
                        }
                        $myCalendar->startDate($CalStartDay);
                        $myCalendar->setIcon($DatePicker2."images/iconCalendar.gif");
                        $myCalendar->setDate(date("d", strtotime($StartDate)), date("m", strtotime($StartDate)), date("Y", strtotime($StartDate)));
                        $myCalendar->setPath($DatePicker2);
                        $myCalendar->setYearInterval(2035,date('Y'));
                        $StartCalendarFrom = date("Y-m-d", strtotime("0 day", strtotime($StartDate)));
                        $myCalendar->dateAllow($StartCalendarFrom, $EndDate, false);
                        $myCalendar->setOnChange("myChanged()");

                        if($small_calendar_type == "php") {
                            $myCalendar->writeScript();
                        }
                        ?>

                        <script language="javascript">
                            function myChanged() {
                                var x = document.getElementById('date1').value;
                                var x2 = document.getElementById('date1').value;
                                x = moment(x).format('<?php echo $CalFormat; ?>');
                                x2 = moment(x2).format('DD-MM-YYYY');
                                document.getElementById('appdate').value = x;
                                document.getElementById('appdate2').value = x2;
                            }
                        </script>
                    </div>

                    <div class="col-md-6" id="secdiv" style="border: 0px solid red; display: inline-block; vertical-align: top; padding: 5px;">
                        <!--float:right; margin-right:5%; width:40%;-->
                        <strong><?php _e('Your Appointment Date:', 'appointzilla'); ?> </strong><br>
                        <input name="appdate" id="appdate" type="text" readonly="" style="height:30px; width:100%; padding-left: 15px;" value="<?php echo date($DateFormat, strtotime($StartDate)); ?>" /><br>
                        <input name="appdate2" id="appdate2" type="hidden" readonly="" style="height:30px; width:100%" value="<?php echo date("Y-m-d", strtotime($StartDate)); ?>" />
                        <?php global $wpdb;

                        $CategoryTable = $wpdb->prefix."ap_service_category";
                        $FindCategorySQL = "SELECT * FROM `$CategoryTable`  order by `name` ASC";
                        $Allcategory = $wpdb->get_results($FindCategorySQL, OBJECT); ?>

                        <style type="text/css"> .mycss { font-weight:bold; } </style>
                        <strong><?php _e('Select Service:', 'appointzilla'); ?></strong><br>
                        <select name="servicelist" id="servicelist">
                            <option value="0"><?php _e('Select Service', 'appointzilla'); ?></option>
                            <?php
                            $cal_admin_currency_id = get_option('cal_admin_currency');
                            if($cal_admin_currency_id) {
                                $CurrencyTableName = $wpdb->prefix . "ap_currency";
                                $cal_admin_currency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency_id'");
                                $cal_admin_currency = $cal_admin_currency->symbol;
                            } else {
                                $cal_admin_currency = "&#36;";
                            }

                            if($AllCalendarSettings['show_service_cost'] == 'yes') $ShowCost = 1; else  $ShowCost = 0;
                            if($AllCalendarSettings['show_service_duration'] == 'yes') $ShowDuration = 1; else  $ShowDuration = 0;
                            $ServiceTable = $wpdb->prefix."ap_services";
                            foreach($Allcategory as $Category) {
                                echo "<option value='$Category->id' disabled class='mycss'>".ucwords($Category->name)."</option>";

                                $FindServiceSql = "SELECT * FROM `$ServiceTable` WHERE `availability` = 'yes' and `category_id` = '$Category->id' order by `name` ASC";
                                $AllServices = $wpdb->get_results($FindServiceSql, OBJECT);
                                if(count($AllServices)) {
                                    foreach($AllServices as $Service) { ?>
                                        <option value="<?php echo $Service->id?>">&nbsp;&nbsp;
                                        <?php echo ucwords($Service->name);
                                        if($ShowDuration || $ShowCost)  {
                                            echo " (";
                                        }
                                        if($ShowDuration) {
                                            echo $Service->duration."min";
                                        }
                                        if($ShowDuration && $ShowCost && $Service->cost) {
                                            echo "/";
                                        }
                                        if($ShowCost && $Service->cost) {
                                            echo $cal_admin_currency. $Service->cost;
                                        }
                                        if($ShowDuration || $ShowCost) {
                                            echo ")";
                                        } ?>
                                        </option><?php
                                    }
                                } else {
                                    echo "<option disabled>&nbsp;&nbsp;".ucwords(__('No service in this category', 'appointzilla'))."</option>";
                                }
                            } ?>
                        </select>
                        <br>
                        <script type="text/javascript">
                        //load staff according to service - start
                        jQuery('#servicelist').change(function(){

                            var ServiceId = jQuery("select#servicelist").val();
                            if(ServiceId > 0) {
                                jQuery('#loading-staff').show();
                                jQuery('#staff').hide();
                                var FirstData = "ServiceId=" + ServiceId;
                                jQuery.ajax({
                                    dataType : 'html',
                                    type: 'GET',
                                    url : location.href,
                                    data : FirstData,
                                    complete : function() { },
                                    success: function(data) {
                                        data=jQuery(data).find('div#stfflistdiv');
                                        jQuery('#staff').show();
                                        jQuery('#loading-staff').hide();
                                        jQuery('#staff').html(data);
                                    }
                                });
                            } else {
                                jQuery('#staff').hide();
                            }
                        });
                        </script>
                        <div id="loading-staff" style="display:none;"><?php _e('Loading Staff...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                        <div id="staff"></div>
                    </div><!--#secdiv-->
                </div><!--#modal-body-->
            </form>
      </div>
    </div>
    <!---AppSecondModal For Schedule New Appointment--->

    <!---AppSecondModal For Schedule New Appointment--->
    <div id="AppSecondModal" style="display:none;"></div>
    <!---AppSecondModal For Schedule New Appointment End--->

    <!---AppThirdModal For Schedule New Appointment--->
    <div id="AppThirdModal" style="display:none;"></div>
    <!---AppThirdModal For Schedule New Appointment End--->
    <div id="AppForthModalFinal" style="display:none;">

    </div>
    <!---AppThirdModal For Schedule New Appointment End--->

    <!--date-picker js -->
    <script src="<?php echo plugins_url('/menu-pages/datepicker-assets/js/jquery.ui.datepicker.js', __FILE__); ?>" type="text/javascript"></script>

    <!---Loading staff ajax return code--->
    <?php if(isset($_GET['ServiceId'])) { ?>
        <!---load bootstrap css--->
        <link rel='stylesheet' type='text/css' href='<?php echo plugins_url('/bootstrap-assets/css/bootstrap.css', __FILE__); ?>' />

        <div id="stfflistdiv">
        <?php
        $ServiceID = $_GET['ServiceId'];
        if($ServiceID) {
            $ServiceTable = $wpdb->prefix . "ap_services";
            $StaffTable = $wpdb->prefix . "ap_staff";
            $AllStaffIdList = $wpdb->get_row("SELECT `staff_id` FROM `$ServiceTable` WHERE `id` = '$ServiceID'", OBJECT);
            $AllStaffIdList = unserialize($AllStaffIdList->staff_id);
            if(count($AllStaffIdList) > 1) {
                ?>
                <strong><?php _e('Select Staff:', 'appointzilla'); ?></strong><br>
                <select name='stafflist' id='stafflist' style="width:100%">
                    <?php //get all staff id list by service id

                    if(count($AllStaffIdList)) {
                        foreach($AllStaffIdList as $StaffId) {
                            $StaffDetails = $wpdb->get_row("SELECT `id`, `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);
                            echo "<option value='".$StaffDetails->id."'>&nbsp;&nbsp;".ucwords($StaffDetails->name)."</option>";
                        }
                    } else {
                        echo "<option value='1'>".__("No Staff Assigned")."</option>";
                    }
                    ?>
                </select>
                <br>
            <?php
            } else {    // end of count > 1
                if(count($AllStaffIdList)) {
                    foreach($AllStaffIdList as $StaffId) {
                        $StaffDetails = $wpdb->get_row("SELECT `id`, `name` FROM `$StaffTable` WHERE `id` = '$StaffId'", OBJECT);
                        echo "<input type='hidden' name='stafflist' id='stafflist' value='$StaffDetails->id'>";
                    }
                }
            } // end of count > 1 else
            ?>
            <button type="button" class="apcal_btn" id="next1" name="next1" onclick="loadseconmodal()"><?php _e('Next', 'appointzilla'); ?> <i class="icon-arrow-right"></i></button>
            <div id="loading1" style="display:none;"><?php _e('Loading...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" /></div>
        <?php } //end of service id if ?>
        </div><?php
    } ?>


    <!---loading second modal form ajax return code--->
    <?php require_once('shortcode-time-slot-calculation-mobile.php'); ?>
    <!---loading second modal form ajax return code--->


    <!---loading third modal form ajax return code--->
    <?php if( isset($_GET['StartTime']) && isset($_GET['StaffId']) ) { ?>
        <div id="AppThirdModalData">
            <div id="AppThirdModal" style="z-index:10000;">
                <input name="ServiceId" id="ServiceId" type="hidden" value="<?php if(isset($_GET['ServiceId'])) { echo $_GET['ServiceId']; } ?>" />
                <input name="StaffId" id="StaffId" type="hidden" value="<?php if(isset($_GET['StaffId'])) { echo $_GET['StaffId']; } ?>" />
                <input name="AppDate" id="AppDate" type="hidden" value="<?php if(isset($_GET['AppDate'])) { echo $_GET['AppDate']; } ?>" />
                <input name="StartTime" id="StartTime" type="hidden" value="<?php if(isset($_GET['StartTime'])) { echo $_GET['StartTime']; } ?>" />
                <input name="EndTime" id="EndTime" type="hidden" value="<?php if(isset($_GET['EndTime'])) { echo $_GET['EndTime']; } ?>" />
                <input name="RecurringType" id="RecurringType" type="hidden" value="<?php if(isset($_GET['RecurringType'])) { echo $_GET['RecurringType']; } ?>" />
                <input name="RecurringStartDate" id="RecurringStartDate" type="hidden" value="<?php if(isset($_GET['recurring_start_date'])) { echo $_GET['recurring_start_date']; } ?>" />
                <input name="RecurringEndDate" id="RecurringEndDate" type="hidden" value="<?php if(isset($_GET['recurring_end_date'])) { echo $_GET['recurring_end_date']; } ?>" />

                <div>
                    <div class="apcal_alert apcal_alert-info">
                        <p><strong><?php _e('Schedule New Appointment', 'appointzilla'); ?></strong></p>
                        <?php _e('Step 3. Complete Your Booking', 'appointzilla'); ?>
                    </div>
                </div>

                <div class="apcal_modal-body" style="max-height: 100%;">
                    <?php if($AllCalendarSettings['apcal_user_registration'] == "yes") { ?>
                    <!--check user div-->
                    <div id="check-user">
                        <table width="100%" class="table">
                            <tr>
                                <td colspan="3">
                                    <button id="new-user" name="new-user" class="apcal_btn apcal_btn-info" onclick="return NewUserBtn();"><i class="icon-user icon-white"></i> <?php _e("New User", "appointzilla"); ?></button>
                                    <button id="existing-user" name="existing-user" class="apcal_btn apcal_btn-info" onclick="return ExistingUserBtn();"><i class="fa fa-sign-in"></i> <?php _e("Existing User", "appointzilla"); ?></button>
                                    <button type="button" class="apcal_btn" id="back2" name="back2" onclick="LoadSecondModal2()" style="float: right;"><i class="icon-arrow-left"></i>  <?php _e('Back', 'appointzilla'); ?></button>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--new user div-->
                    <div id="new-user-div" style="display: none;">
                        <table width="100%" class="table">
                            <tr>
                                <th scope="row"><?php _e('Username', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-username" type="text" id="client-username" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Password', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-password" type="password" id="client-password" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Confirm Password', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-confirm-password" type="password" id="client-confirm-password" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-email" type="text" id="client-email" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-first-name" type="text" id="client-first-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-last-name" type="text" id="client-last-name" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><input name="client-phone" type="text" id="client-phone" style="height:30px;"  maxlength="14"/></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="client-si" id="client-si"></textarea></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <div id="new-user-form-btn-div">
                                        <button type="button" class="apcal_btn apcal_btn-success" id="book-now" name="book-now" onclick="return CheckValidation('NewUser')"><i class="icon-ok icon-white"></i>  <?php _e('Book Now', 'appointzilla'); ?></button>
                                    </div>
                                    <div id="new-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--existing user div-->
                    <div id="existing-user-div" style="display: none;">

                        <!--div for display existing user search details-->
                        <div id="check-email-div-form" style="display: none;">
                            <table width="100%" class="table">
                                <tr>
                                    <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="check-client-email" type="text" id="check-client-email" style="height:30px;" /></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div id="existing-user-form-btn">
                                            <button type="button" class="apcal_btn apcal_btn-success" id="check-existing-user" name="check-existing-user" onclick="return CheckExistingUser();"><i class="icon-search icon-white"></i> <?php _e('Search Email', 'appointzilla'); ?></button>
                                        </div>
                                        <div id="existing-user-loading-img" style="display:none;"><?php _e('Searching, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!--div for display existing user search details-->
                        <div id="check-email-result-div" style="display: none;">
                        </div>
                    </div>
                    <?php } else { // end of if registration enable?>
                        <!--user registration not enable-->
                        <div id="no-user-registration">
                            <table width="100%" class="table">
                                <tr>
                                    <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="client-email" type="text" id="client-email" style="height:30px;" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="client-first-name" type="text" id="client-first-name" style="height:30px;" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="client-last-name" type="text" id="client-last-name" style="height:30px;" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><input name="client-phone" type="text" id="client-phone" style="height:30px;"  maxlength="14"/></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                                    <td><strong>:</strong></td>
                                    <td><textarea name="client-si" id="client-si"></textarea></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div id="new-user-form-btn-div">
                                            <button type="button" class="apcal_btn" id="back2" name="back2" onclick="LoadSecondModal2()"><i class="icon-arrow-left"></i>  <?php _e('Back', 'appointzilla'); ?></button>
                                            <button type="button" class="apcal_btn apcal_btn-success" id="book-now" name="book-now" onclick="return CheckValidation('NewUser')"><i class="icon-ok icon-white"></i>  <?php _e('Book Now', 'appointzilla'); ?></button>
                                        </div>
                                        <div id="new-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                </div><!--end modal-body-->
            </div>
        </div><?php
    } ?>


    <!---saving appointments--->
    <?php if( isset($_POST['Action'])) {
    $Action = $_POST['Action'];
    $UserType = $_POST['UserType'];
    if($Action == "BookAppointment") {
        //print_r($_POST); die;
        $ServiceId = $_POST['ServiceId'];
        $StaffId = $_POST['StaffId'];
        $AppDateNo = $_POST['AppDate'];

        $ClientEmail = $_POST['ClientEmail'];
        $ClientFirstName = sanitize_text_field($_POST['ClientFirstName']);
        $ClientLastName = sanitize_text_field($_POST['ClientLastName']);
        $ClientName = $ClientFirstName ." ". $ClientLastName;
        $ClientPhone = $_POST['ClientPhone'];
        $ClientNote = $_POST['ClientNote'];
        $AppointmentKey = md5(date("F j, Y, g:i a"));
        $AppDate = date("Y-m-d", strtotime($AppDateNo));
        $StartTime = $_POST['StartTime'];

        //check user registration yes/no
        if($AllCalendarSettings['apcal_user_registration'] == "yes"){
            if($UserType == "NewUser") {
                $ClientUserName = $_POST['ClientUserName'];
                $ClientPassword = $_POST['ClientPassword'];

                //create new user profile as subscriber
                echo $UserId = username_exists( $ClientUserName );
                if ( !$UserId and email_exists($ClientEmail) == false ) {
                    $UserId = wp_create_user( $ClientUserName, $ClientPassword, $ClientEmail );
                    if($UserId){
                        add_user_meta( $UserId, 'first_name', $ClientFirstName);
                        add_user_meta( $UserId, 'last_name', $ClientLastName);
                        add_user_meta( $UserId, 'client_phone', $ClientPhone);
                        add_user_meta( $UserId, 'client_note', $ClientNote);
                    }
                } else {
                    _e("User already exists", "appointzilla");
                }
            }

            if($UserType == "ExUser") {
                //update existing user profile as subscriber
                echo $UserId = email_exists($ClientEmail);
                if($UserId){
                    update_user_meta( $UserId, 'first_name', $ClientFirstName);
                    update_user_meta( $UserId, 'last_name', $ClientLastName);
                    update_user_meta( $UserId, 'client_phone', $ClientPhone);
                    update_user_meta( $UserId, 'client_note', $ClientNote);
                } else {
                    _e("User already exists", "appointzilla");
                }
            }
        } // end of check user registration

        //fetch service detail calculation EndTime and Service name
        $ServiceTableName = $wpdb->prefix . "ap_services";
        $ServiceName = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId' ");
        $ServiceDuration = $ServiceName->duration;

        $StartTimeTimestamp = strtotime($StartTime);
        //calculate end time according to service duration
        $CalculateTime = strtotime("+$ServiceDuration minutes", $StartTimeTimestamp);
        $EndTime =  date('h:i A', $CalculateTime );

        if(isset($AllCalendarSettings['apcal_new_appointment_status'])) {
            $Status = $AllCalendarSettings['apcal_new_appointment_status'];
        } else {
            $Status = "pending";
        }
        $AppointmentBy = "user";
        $Recurring = "no";
        $RecurringType = "none";
        $RecurringStartDate = $AppDate;
        $RecurringEndDate = $AppDate;
        $PaymentStatus = "unpaid";

        global $wpdb;
        $AppointmentsTable = $wpdb->prefix ."ap_appointments";
        $CreateAppointments = "INSERT INTO `$AppointmentsTable` (`id` ,`name` ,`email` ,`service_id` ,`staff_id` ,`phone` ,`start_time` ,`end_time` ,`date` ,`note` , `appointment_key` ,`status` ,`recurring` ,`recurring_type` ,`recurring_st_date` ,`recurring_ed_date` ,`appointment_by`, `payment_status`) VALUES ('NULL', '$ClientName', '$ClientEmail', '$ServiceId', '$StaffId', '$ClientPhone', '$StartTime', '$EndTime', '$AppDate', '$ClientNote', '$AppointmentKey', '$Status', '$Recurring', '$RecurringType', '$RecurringStartDate', '$RecurringEndDate', '$AppointmentBy', '$PaymentStatus');";

        if($wpdb->query($CreateAppointments)) {
            $LastAppointmentId = $wpdb->insert_id; ?>
            <div id="AppForthModalData">
            <?php global $wpdb;
            $ClientTable = $wpdb->prefix."ap_clients";
            $ExistClientDetails = $wpdb->get_row("SELECT * FROM `$ClientTable` WHERE `email` = '$ClientEmail' ");
            if(count($ExistClientDetails)) {
                // update  exiting client deatils
                $ExistClientId = $ExistClientDetails->id;
                $update_client = "UPDATE `$ClientTable` SET `name` = '$ClientName', `email` = '$ClientEmail', `phone` = '$ClientPhone', `note` = '$ClientNote' WHERE `id` = '$ExistClientId' ;";
                if($wpdb->query($update_client)) {
                    $LastClientId = $ExistClientId;
                } else {
                    // if now data filed modified then
                    $LastClientId = $ExistClientId;
                }
            } else {
                // insert new client deatils
                $InsertClient = "INSERT INTO `$ClientTable` (`id` ,`name` ,`email` ,`phone` ,`note`) VALUES ('NULL', '$ClientName', '$ClientEmail', '$ClientPhone', '$ClientNote');";
                if($wpdb->query($InsertClient)) {
                    $LastClientId = $wpdb->insert_id;
                }
            } ?>

            <div id="AppForthModal" style="z-index:10000;">
                <div class="apcal_modal-info">
                    <div style="float:right; margin-top:5px; margin-right:10px;"></div>
                    <div class="apcal_alert apcal_alert-info">
                        <p><?php _e('Thank You. Your appointment has been scheduled.', 'appointzilla'); ?></p>
                    </div>

                    <div class="apcal_modal-body">
                        <style>
                            .table th, .table td {
                                padding: 4px;;
                            }
                        </style>
                        <strong><?php _e('Your Appointment Details', 'appointzilla'); ?></strong>
                        <input type="hidden" id="appid" name="appid" value="<?php echo $LastAppointmentId; ?>" />
                        <table width="100%" class="table">
                            <tr>
                                <th width="26%" scope="row"><?php _e('Name', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo ucwords($ClientName);  ?></td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo $ClientEmail; ?></td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo $ClientPhone;  ?></td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Service', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php	echo ucwords($ServiceName->name);  ?></td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Staff', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%">
                                    <?php $StaffTableName = $wpdb->prefix . "ap_staff";
                                    $StaffName = $wpdb->get_row("SELECT `name` FROM `$StaffTableName` WHERE `id` = '$StaffId' ");
                                    echo ucwords($StaffName->name); ?>
                                </td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Date', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo date($DateFormat, strtotime($AppDate));	?></td>
                            </tr>
                            <tr>
                                <?php if($TimeFormat == "h:i") $InfoTimeFormat = "h:i A"; else $InfoTimeFormat = "H:i"; ?>
                                <th width="26%" scope="row"><?php _e('Time', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo date($InfoTimeFormat, strtotime($StartTime))." - ".date($InfoTimeFormat, strtotime($EndTime));  ?></td>
                            </tr>
                            <tr>
                                <th width="26%" scope="row"><?php _e('Status', 'appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="73%"><?php echo _e(ucfirst($Status),'appointzilla'); ?></td>
                            </tr>
                            <?php
                            //get service details for payment purpose & also check payment settings
                            global $wpdb;
                            $ServiceTableName = $wpdb->prefix . 'ap_services';
                            $ServiceDetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId'");
                            $AcceptPayment = $ServiceDetails->accept_payment;
                            $PaymentType = $ServiceDetails->payment_type;
                            $ap_payment_email = get_option('ap_payment_email');
                            $ap_payment_gateway_status = get_option('ap_payment_gateway_status');
                            if($AcceptPayment == "yes" && $PaymentType == "full" && $ap_payment_email && $ap_payment_gateway_status == "yes") {
                                ?>
                                <tr>
                                    <th width="26%" scope="row"><?php _e('Coupon Code', 'appointzilla'); ?></th>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="73%">
                                        <div id="apply-coupon-div">
                                            <input type="text" id="coupon-code" name="coupon-code" maxlength="15" style="width: 120px;">
                                            <button id="apply-coupon" name="apply-coupon" class="apcal_btn apcal_btn-small apcal_btn-info" onclick="return ApplyCoupon();" style="margin-top: -10px;"><i class="icon-tags icon-white"></i> <?php _e('Apply', 'appointzilla'); ?></button>
                                        </div>

                                        <div id="loading-img" style="display:none;"><?php _e('Applying...', 'appointzilla'); ?><img src="<?php echo plugins_url("images/loading.gif", __FILE__); ?>" /></div>
                                        <div id="show-coupon-result" style="display:none;"></div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3">
                                    <?php $Check1 = 0; $Check2 = 0;  $Check3 = 0;
                                    /**
                                     * Paypal Payment Process
                                     **/
                                    //get service details for payment purpose
                                    global $wpdb;
                                    $ServiceTableName = $wpdb->prefix . 'ap_services';
                                    $ServiceDetails = $wpdb->get_row("SELECT * FROM `$ServiceTableName` WHERE `id` = '$ServiceId'");

                                    //get currency code
                                    $CurrencyId = get_option('cal_admin_currency');
                                    if($CurrencyId != '') {
                                        $CurrencyTableName = $wpdb->prefix."ap_currency";
                                        $CurrencyDetails = $wpdb->get_row("SELECT `code` FROM `$CurrencyTableName` WHERE `id` = '$CurrencyId'");
                                        $CurrencyCode =  $CurrencyDetails->code;
                                    } else {
                                        $CurrencyCode =  'USD';
                                    }

                                    $Protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                                    $SuccessCurrentUrl = $Protocol.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                                    $FailedCurrentUrl = $SuccessCurrentUrl."?&failed=failed&appointId=".$LastAppointmentId;

                                    //check payment is 'yes'
                                    if($ap_payment_gateway_status == 'yes') {

                                        //check service is paid
                                        if($ServiceDetails->accept_payment == 'yes') {
                                            //check payment type
                                            if($ServiceDetails->payment_type == 'percentage') {
                                                $PayCost = $ServiceDetails->cost;
                                                $percentage = $ServiceDetails->percentage_ammount;
                                                $PayCost = ($PayCost * $percentage) /100;
                                            } else {
                                                $PayCost = $ServiceDetails->cost;
                                            }

                                            $ApPaymentEmail = get_option('ap_payment_email');
                                            if($ApPaymentEmail) {
                                                // default discount value
                                                $DiscountRate = 0;

                                                // Include the paypal library
                                                require_once ('menu-pages/paypal-api/Scientechpaypal.php');
                                                $ScientechPaypal = new Scientechpaypal();
                                                $ScientechPaypal->TakePayment($ApPaymentEmail, $CurrencyCode, $SuccessCurrentUrl, $FailedCurrentUrl, $ServiceDetails->name, $PayCost, $LastAppointmentId, $DiscountRate);
                                            } else {
                                                $Check3 = 1; ?>
                                                <!--<button type="submit" class="apcal_btn apcal_btn-primary" onclick="CloseModelform()" style="margin-left:80%"><i class="icon-ok icon-white"></i> <?php /*_e('Done', 'appointzilla'); */?></button>--><?php
                                            }
                                        } else {
                                            $Check2 = 1;
                                            ?><!--<button type="button" class="apcal_btn apcal_btn-primary" onclick="CloseModelform()" ><i class="icon-ok icon-white"></i> --><?php /*_e('Done', 'appointzilla'); */?></button>
                                        <?php
                                        }
                                    } else {
                                        $Check1 = 1;
                                    }

                                    // send notification if any of check == 1
                                    if($Check1 || $Check2 || $Check3) { ?>
                                        <button type="submit" class="apcal_btn apcal_btn" onclick="CloseModelform()" style="margin-left:80%"><i class="icon-ok"></i> <?php _e('Done', 'appointzilla'); ?></button>
                                        <?php $BlogName =  get_bloginfo('name');
                                        if($LastAppointmentId && $LastClientId) {
                                            $AppId = $LastAppointmentId;
                                            $ServiceId = $ServiceId;
                                            $StaffId = $StaffId;
                                            $ClientId = $LastClientId;
                                            //include notification class
                                            require_once('menu-pages/notification-class.php');
                                            $Notification = new Notification();
                                            $Notification->notifyadmin($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                            $Notification->notifyclient($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                            if(get_option('staff_notification_status') == 'on') {
                                                $Notification->notifystaff($Status, $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                                            }
                                        }
                                    }

                                    //if status is approved then sync appointment
                                    if($Status == 'approved') {

                                        //add service name with event title($name)
                                        //$ServiceTable = $wpdb->prefix . "ap_services";
                                        //$ServiceData = $wpdb->get_row("SELECT * FROM `$ServiceTable` WHERE `id` = '$ServiceId'");
                                        //$name = $name."(".$ServiceData->name.")";

                                        /***
                                         * admin appointment sync
                                         */
                                        $CalData = get_option('google_caelndar_settings_details');
                                        if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                                            $StartTime = date("H:i", strtotime($StartTime));
                                            $EndTime = date("H:i", strtotime($EndTime));
                                            $AppDate = date("Y-m-d", strtotime($AppDate));
                                            $ClientNote = strip_tags($ClientNote);

                                            $ClientId = $CalData['google_calendar_client_id'];
                                            $ClientSecretId = $CalData['google_calendar_secret_key'];
                                            $RedirectUri = $CalData['google_calendar_redirect_uri'];
                                            require_once('menu-pages/google-appointment-sync-class.php');

                                            //global $wpdb;
                                            $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                                            // insert this appointment event on calendar
                                            $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                                            $tag = "Appointment with: ";
                                            $OAuth = $GoogleAppointmentSync->NormalSync($ClientName, $AppDate, $StartTime, $EndTime, $ClientNote, $tag);
                                            //insert appintment sync details
                                            $OAuth = serialize($OAuth);
                                            $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$AppId', '$OAuth' );");
                                        } // end of google calendar setting

                                        /***
                                         * staff appointment sync
                                         */
                                        $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                                        if($StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'] != "" && $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret']) {
                                            $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                                            $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                                            $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                                            $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];
                                            require_once('menu-pages/google-staff-appointment-sync-class.php');

                                            // add this staff appointment event on his google calendar
                                            $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                                            $Tag = __("Appointment with: ", "appointzilla");
                                            $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($StaffId, $ClientName, $AppDate, $StartTime, $EndTime, $ClientNote, $Tag);

                                            //insert staff appointment sync details
                                            global $wpdb;
                                            $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                                            $StaffOAuth = serialize($StaffOAuth);
                                            $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$AppId', '$StaffOAuth' )");
                                        }//end of staff appointment sync

                                        //unset payment post variables
                                        unset($_POST['address_status']); unset($_POST['payer_id']);

                                    } // end of sync appointment is approved
                                    ?>
                                    <div id="loading-staff" style="display:none;">
                                        <?php _e('Loading Staff...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" />
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        } //query if
    } //if action
} // saving appointment if

    // Payment success
    if( isset($_POST['address_status']) && isset($_POST['payer_id']) ) {
        global $wpdb;
        $AppointmentTableName = $wpdb->prefix . "ap_appointments";
        $PaymentTableName = $wpdb->prefix . 'ap_payment_transaction';
        $ClientTableName = $wpdb->prefix . 'ap_clients';
        $CouponsCodesTable = $wpdb->prefix ."apcal_pre_coupons_codes";
        $PaymentDetails = serialize($_POST);
        $appId = $_POST['item_number'];

        //get client id by appointment id
        $ClientEmail = $wpdb->get_row("SELECT `email` FROM `$AppointmentTableName` WHERE `id` = '$appId'");
        $FetchClientEmail = $wpdb->get_row("SELECT `id` FROM `$ClientTableName` WHERE `email` = '$ClientEmail->email'");
        $clientId = $FetchClientEmail->id;

        $ammount = $_POST['mc_gross'];
        $date = $_POST['payment_date'];
        $status = $_POST['address_status'];
        $txn_id = $_POST['txn_id'];
        $gateway = 'paypal';

        $wpdb->query("INSERT INTO `$PaymentTableName` (`id`, `app_id`, `client_id`, `ammount`, `date`, `status`, `txn_id`, `gateway`, `other_fields`) VALUES (NULL, '$appId', '$clientId', '$ammount', '$date', '$status', '$txn_id', '$gateway', '$PaymentDetails');");
        $AppointmentId = $_POST['item_number'];
        $AppointmentRow = $wpdb->get_row("SELECT * FROM `$AppointmentTableName` WHERE `id` = '$AppointmentId'");
        if($AppointmentRow) {
            $name = $AppointmentRow->name;
            $ServiceId = $AppointmentRow->service_id;
            $StaffId = $AppointmentRow->staff_id;
            $StartTime = $AppointmentRow->start_time;
            $EndTime = $AppointmentRow->end_time;
            $Client_name = $AppointmentRow->name;
            $Client_Email = $AppointmentRow->email;
            $Client_Phone = $AppointmentRow->phone;
            $Client_Note = $AppointmentRow->note;
            $AppDate = $AppointmentRow->date;
            $Status = 'approved';
            $AppointmentKey = $AppointmentRow->appointment_key;

            //update appointment status n payment status
            $PaymentStatus = $_POST['payment_status'];
            $AppointmentRow = $wpdb->query("UPDATE `$AppointmentTableName` SET `status` = '$Status', `payment_status` = 'paid' WHERE `id` = '$AppointmentId' ");

            $BlogName =  get_bloginfo();
            if($AppointmentId && $clientId) {
                $AppId = $AppointmentId;
                $ServiceId = $ServiceId;
                $StaffId = $StaffId;
                $ClientId = $clientId;
                //include notification class
                require_once('menu-pages/notification-class.php');
                $Notification = new Notification();
                $Notification->notifyadmin('approved', $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                $Notification->notifyclient('approved', $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                if(get_option('staff_notification_status') == 'on') {
                    $Notification->notifystaff('approved', $AppId, $ServiceId, $StaffId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                }
            }

            //if status is approved then sync appointment
            if($Status == 'approved') {

                /***
                 * admin appointment sync
                 */
                $CalData = get_option('google_caelndar_settings_details');
                if($CalData['google_calendar_client_id'] != '' && $CalData['google_calendar_secret_key']  != '') {
                    $start_time = date("H:i", strtotime($StartTime));
                    $end_time = date("H:i", strtotime($EndTime));
                    $appointmentdate = date("Y-m-d", strtotime($AppDate));
                    $note = strip_tags($Client_Note);

                    $ClientId = $CalData['google_calendar_client_id'];
                    $ClientSecretId = $CalData['google_calendar_secret_key'];
                    $RedirectUri = $CalData['google_calendar_redirect_uri'];
                    require_once('menu-pages/google-appointment-sync-class.php');

                    //global $wpdb;
                    $AppointmentSyncTableName = $wpdb->prefix . "ap_appointment_sync";
                    // insert this appointment event on calendar
                    $GoogleAppointmentSync = new GoogleAppointmentSync($ClientId, $ClientSecretId, $RedirectUri);
                    $tag = "Appointment with: ";
                    $OAuth = $GoogleAppointmentSync->NormalSync($name, $appointmentdate, $start_time, $end_time, $note, $tag);
                    //insert appointment sync details
                    $OAuth = serialize($OAuth);
                    $wpdb->query("INSERT INTO `$AppointmentSyncTableName` ( `id` , `app_id` , `app_sync_details` ) VALUES ( NULL , '$AppointmentId', '$OAuth' );");
                } // end of google calendar setting

                /***
                 * staff appointment sync
                 */
                $StaffAppointmentSyncSettings = unserialize(get_option("staff_google_calendar_sync_settings_".$StaffId));
                if($StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'] != "" && $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret']) {
                    $StaffGoogleEmail = $StaffAppointmentSyncSettings['StaffGoogleEmail'];
                    $StaffGoogleCalendarClientId = $StaffAppointmentSyncSettings['StaffGoogleCalendarClientId'];
                    $StaffGoogleCalendarSecret = $StaffAppointmentSyncSettings['StaffGoogleCalendarSecret'];
                    $StaffGoogleCalendarRedirectUris = $StaffAppointmentSyncSettings['StaffGoogleCalendarRedirectUris'];
                    require_once('menu-pages/google-staff-appointment-sync-class.php');

                    // add this staff appointment event on his google calendar
                    $StaffGoogleAppointmentSync = new StaffGoogleAppointmentSync($StaffGoogleCalendarClientId, $StaffGoogleCalendarSecret, $StaffGoogleCalendarRedirectUris);
                    $Tag = __("Appointment with: ", "appointzilla");
                    $StaffOAuth = $StaffGoogleAppointmentSync->NormalStaffAppointmentSync($StaffId, $ClientName, $AppDate, $StartTime, $EndTime, $ClientNote, $Tag);

                    //insert staff appointment sync details
                    global $wpdb;
                    $StaffAppointmentSyncTable = $wpdb->prefix . "ap_staff_appointment_sync";
                    $StaffOAuth = serialize($StaffOAuth);
                    $wpdb->query("INSERT INTO `$StaffAppointmentSyncTable` ( `id` , `app_id` , `staff_sync_details` ) VALUES ( NULL , '$AppId', '$StaffOAuth' )");
                }//end of staff appointment sync

                //unset payment post variables
                unset($_POST['address_status']); unset($_POST['payer_id']);
            } // end of appointment is approved ?>
            <div id="AppForthModal" style="z-index:10000;">
                <div class="apcal_modal-info">
                    <div class="apcal_alert apcal_alert-info">
                            <h4 ><?php _e('Payment Successfully Processed', 'appointzilla'); ?></h4>
                    </div>
                    <div>
                        <div class="apcal_alert apcal_alert-success">
                            <strong><?php _e('Payment received and your appointment has been confirmed.', 'appointzilla'); ?></strong><br />
                            <strong><?php _e('Thank you for scheduling appointment with us.', 'appointzilla'); ?></strong>
                            <?php
                                //if payment confirmed(done) then increment coupon used count value
                                if($status == "confirmed") {
                                    $PaymentDetails = unserialize($PaymentDetails);
                                    if(isset($PaymentDetails['custom'])) {
                                        $CouponCode = $PaymentDetails['custom'];
                                        //check coupon exist or not
                                        $CouponsData = $wpdb->get_row("SELECT * FROM `$CouponsCodesTable` WHERE `coupon_code` LIKE '$CouponCode'");
                                        if(count($CouponsData)) {
                                            //increment total used count
                                            $CouponId = $CouponsData->id;
                                            $UsedCount = $CouponsData->used_count + 1;
                                            $wpdb->query("UPDATE `$CouponsCodesTable` SET `used_count` = '$UsedCount' WHERE `id` = '$CouponId' ");
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <button type='button' onclick='CloseModelform()' name='close' id='close' value='Done' class='apcal_btn'><i class="icon-ok"></i> <?php _e('Done', 'appointzilla'); ?></button>
                    </div>
                </div>
            </div><?php
        } // end of AppointmentRow
    } // end of Payment success

    // Payment process faild
    if( isset($_GET['failed']) && isset($_GET['appointId'])) { ?>
        <div id="AppForthModal" style="z-index:10000;">
            <div style="padding-bottom:20px;">
                <input type="hidden" name="appid" id="appid" value="<?php echo $_GET['appointId']; ?>" />
                <div class="apcal_alert apcal_alert-info">
                    <h4><?php _e('Payment Failed', 'appointzilla'); ?></h4>
                </div>
                <div style=" margin-left:20px; padding-right:20px;">
                    <div class="apcal_alert apcal_alert-error">
                        <?php _e('Sorry! Appointment Booking was not successful.', 'appointzilla'); ?>
                    </div>
                    <button type='button' onclick='failedappointment()' name='close' id='close' value='close' class='apcal_btn'><i class="icon-repeat"></i> <?php _e('Try Again', 'appointzilla'); ?> </button>
                </div>
             </div>
        </div><?php
    }

    // cancel  appointment
    if(isset($_POST['appid']) && isset($_POST['appstatus']) ) {
        $AppId = $_POST['appid'];
        global $wpdb;
        $AppointmentTable = $wpdb->prefix."ap_appointments";
        $wpdb->query("UPDATE `$AppointmentTable` SET `status` = 'cancelled' WHERE `id` = '$AppId' ;");
    }

    //applying coupon code
    if(isset($_POST['Action']) == "apply-coupon") {
        if(isset($_POST['CouponCode'])) {
            $CouponCode = strtolower($_POST['CouponCode']);
        } else {
            $CouponCode = "";
        }
        if($CouponCode){
            global $wpdb;
            $Discount = 0;
            $CouponsCodesTable = $wpdb->prefix . "apcal_pre_coupons_codes";
            //Search Coupon
            $CouponDetails = $wpdb->get_row("SELECT * FROM `$CouponsCodesTable` WHERE `coupon_code` LIKE '$CouponCode'");
            if(count($CouponDetails)) {
                //check coupon expire
                $DateTodayTs = strtotime(date("Y-m-d"));
                $ExpireDateTs = strtotime(date("Y-m-d", strtotime($CouponDetails->expire)));
                $TotalUses = $CouponDetails->total_uses;
                $UsedCount = $CouponDetails->used_count;
                $Discount = $CouponDetails->discount;
                if($DateTodayTs > $ExpireDateTs) {
                    //coupon expired
                    ?><div id="coupon-result"><div id="discount-rate-div" style="display: none;"><?php echo $Discount = 0; ?></div><?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code expired.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a></div><?php

                } else if($UsedCount >= $TotalUses) {
                    //ckeck used count

                    ?><div id="coupon-result"><div id="discount-rate-div" style="display: none;"><?php echo $Discount = 0; ?></div><?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code expired.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a></div><?php

                } else {
                    //coupon valid and appied
                    ?><div id="coupon-result">
                    <div id="coupon-code-div" style="display: none;"><?php echo $CouponCode; ?></div>
                    <div id="discount-rate-div" style="display: none;"><?php echo $Discount; ?></div>
                    <?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code applied.", "appointzilla"); ?> <a id="try-another" onclick="return TryAgain();"><?php _e("Change", "appointzilla"); ?></a>
                    </div><?php
                }
            } else {
                ?>
                <div id="coupon-result">
                    <div id="discount-rate-div" style="display: none;"><?php echo $Discount; ?></div>
                    <?php echo strtoupper("<strong>$CouponCode</strong> "); _e("coupon code is invalid.", "appointzilla"); ?>
                    <a id="try-another" onclick="return TryAgain();"><?php _e("Try Another", "appointzilla"); ?></a>
                </div>
                <?php
            }
        }
    }

    //check existing user
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action == "CheckExistingUser") {
            ?><div id="check-email-result"><?php
            $ClientEmail = $_POST['ClientEmail'];
            $ClientId = email_exists( $ClientEmail );
            if( $ClientId = email_exists( $ClientEmail )) {
                //fetch user details
                $ClientDetails = get_userdata( $ClientId );
                //print_r($ClientDetails);
                $FirstName = "";
                $LastName = "";
                $Phone = "";
                $ClientNote = "";
                $UserMetaData = get_user_meta( $ClientId );
                if(count($UserMetaData)) {
                    if(isset($UserMetaData['first_name'][0])) {
                        $FirstName = ucwords($UserMetaData['first_name'][0]);
                    }
                    if(isset($UserMetaData['last_name'][0])) {
                        $LastName = ucwords($UserMetaData['last_name'][0]);
                    }
                    if(isset($UserMetaData['client_phone'][0])) {
                        $Phone = $UserMetaData['client_phone'][0];
                    }
                    if(isset($UserMetaData['client_note'][0])) {
                        $ClientNote = ucfirst($UserMetaData['client_note'][0]);
                    }
                }
                ?>
                <input type="hidden" id="client-id" name="client-id" value="<?php echo $ClientId; ?>">
                <table width="100%" class="table">
                    <tr>
                        <th scope="row"><?php _e('Email', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-email" type="text" id="ex-client-email" style="height:30px;" value="<?php echo $ClientEmail; ?>" readonly="" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('First Name', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-first-name" type="text" id="ex-client-first-name" style="height:30px;" value="<?php echo $FirstName; ?>"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Last Name', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-last-name" type="text" id="ex-client-last-name" style="height:30px;" value="<?php echo $LastName; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Phone', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><input name="ex-client-phone" type="text" id="ex-client-phone" style="height:30px;" value="<?php echo $Phone; ?>"  maxlength="14"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Special Instruction', 'appointzilla'); ?></th>
                        <td><strong>:</strong></td>
                        <td><textarea name="ex-client-si" id="ex-client-si"><?php echo $ClientNote; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <div id="ex-user-form-btn-div">
                                <button type="button" class="apcal_btn apcal_btn-success" id="ex-book-now" name="ex-book-now" onclick="return CheckValidation('ExUser');"><i class="icon-ok icon-white"></i> <?php _e('Book Now', 'appointzilla'); ?></button>
                                <button type="button" class="apcal_btn apcal_btn-danger" id="ex-cancel-app" name="ex-cancel-app" onclick="return Canceling();"><i class="icon-remove icon-white"></i> <?php _e('Cancel', 'appointzilla'); ?></button>
                            </div>
                            <div id="ex-user-form-loading-img" style="display:none;"><?php _e('Scheduling appointment, please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                            <div id="ex-canceling-img" style="display:none;"><?php _e('Refreshing, Please wait...', 'appointzilla'); ?><img src="<?php echo plugins_url('images/loading.gif', __FILE__); ?>" /></div>
                        </td>
                    </tr>
                </table>
            <?php
            } else { ?>
                <table width="100%" class="table">
                    <tr>
                        <td colspan="3">
                            <?php  _e("Sorry! No record found.","appointzilla"); ?>
                            <button type="button" onclick="return TryAgainBooking();" class="apcal_btn apcal_btn-danger"><i class="fa fa-mail-reply"></i> Try Again</button>
                        </td>
                    </tr>
                </table>
            <?php
            }
            ?></div><?php
        }
    }
    /*$output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;*/
}//end of short code function
?>