<script type="text/javascript" src="<?php echo plugins_url('js/date.js', __FILE__); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.min.js', __FILE__); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.8.0.js', __FILE__); ?>"></script>
<style type='text/css'>
.error{ 
    color:#FF0000;
}
</style>
<script type="text/javascript">
function SavedStaffHour(stid) {
    var stid = stid;
    if(!isNaN(stid)) {
        var StaffId = stid;
    } else {
        var StaffId = jQuery('#selectstaff').val();
    }
    var url = "?page=app-calendar-settings&show=staffhours";
    var UrlData = "&staff_id=" + StaffId;
    jQuery('#loading-staff').show();
    jQuery.ajax({
        dataType : 'html',
        type: 'POST',
        url : url,
        cache: false,
        data : UrlData,
        complete : function() {  },
        success: function(data) {
                data = jQuery(data).find('div#loadstaffhours');
                jQuery('#loading-staff').hide();
                jQuery('#Showstaffhours').show();
                jQuery('#Showstaffhours').html(data);
            }
    });
}

function mondaystarttime() {
    var mflag = 1;
    <!--Monday-->
    //monday start-time
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
}

function mondayendtime() {
    var mflag = 1;
    //monday end-time
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

    if(met !=null) {
        if(mst > met) {
            alert("<?php echo __("Monday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); mflag = 0;
        }else  mflag = 1;
    }
}


function tuesdaystarttime() {
    var tflag = 1;
    <!--Tuesday-->
    //Tuesday start-time
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
}

function tuesdayendtime() {
    var tflag = 1;
    //Tuesday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Tuesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  tflag = 0;
        }else  tflag = 1;
    }
}


function wednesdaystarttime() {
    var wflag = 1;
    <!--Wednesday-->
    //Wednesday start-time
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
}
function wednesdayendtime()
{
    var wflag = 1;
    //Wednesday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Wednesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  wflag = 0;
        }else  wflag = 1;
    }
}

function thursdaystarttime() {
    var thflag = 1;
    <!--Thursday-->
    //Thursday start-time
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
}
function thursdayendtime() {
    var thflag = 1;
    //Thursday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Thursday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  thflag = 0;
        }else  thflag = 1;
    }
}

function fridaystarttime() {
    var fflag = 1;
    <!--Friday-->
    //Friday start-time
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
}

function fridayendtime() {
    var fflag = 1;
    //Friday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Friday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  fflag = 0;
        }else  fflag = 1;
    }
}

function saturdaystarttime() {
    var satflag = 1;
    <!--Saturday-->
    //Saturday start-time
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
}

function saturdayendtime() {
    var satflag = 1;
    //Saturday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Saturday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  satflag = 0;
        }else satflag = 1;
    }
}

function sundaystarttime() {
    var sunflag = 1;
    <!--Sunday-->
    //Sunday start-time
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
}

function sundayendtime() {
    var sunflag = 1;
    //Sunday end-time
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

    if(et !=null) {
        if(st > et) {
            alert("<?php echo __("Sunday's End-time must be bigger then Start-time" ,'appointzilla'); ?>");  sunflag = 0;
            <?php $jQuerysunflag = "0"; ?>
        } else sunflag = 1;
    }
}

function mondaycheck() {
    //disable monday times
    if(jQuery('#mcheck').is(':checked')) {
        jQuery('#mst').attr("disabled", true);
        jQuery('#met').attr("disabled", true);
    } else {
        jQuery('#mst').attr("disabled", false);
        jQuery('#met').attr("disabled", false);
    }
}

function tuesdaycheck() {
    //disable tuesday times
    if(jQuery('#tucheck').is(':checked')) {
        jQuery('#tst').attr("disabled", true);
        jQuery('#tet').attr("disabled", true);
    } else {
        jQuery('#tst').attr("disabled", false);
        jQuery('#tet').attr("disabled", false);
    }
}

function wednesdaycheck() {
    //disable wednesday times
    if(jQuery('#wcheck').is(':checked')) {
        jQuery('#wst').attr("disabled", true);
        jQuery('#wet').attr("disabled", true);
    } else {
        jQuery('#wst').attr("disabled", false);
        jQuery('#wet').attr("disabled", false);
    }
}

function thursdaycheck() {
    //disable thusday times
    if(jQuery('#thcheck').is(':checked')) {
        jQuery('#thst').attr("disabled", true);
        jQuery('#thet').attr("disabled", true);
    } else {
        jQuery('#thst').attr("disabled", false);
        jQuery('#thet').attr("disabled", false);
    }
}

function fridaycheck() {
    //disable friday times
    if(jQuery('#fcheck').is(':checked')) {
        jQuery('#fst').attr("disabled", true);
        jQuery('#fet').attr("disabled", true);
    } else {
        jQuery('#fst').attr("disabled", false);
        jQuery('#fet').attr("disabled", false);
    }
}


function saturdaycheck() {
    //disable saturday times
    if(jQuery('#satcheck').is(':checked')) {
        jQuery('#satst').attr("disabled", true);
        jQuery('#satet').attr("disabled", true);
    } else {
        jQuery('#satst').attr("disabled", false);
        jQuery('#satet').attr("disabled", false);
    }
}

function sundaycheck() {
    //disable sunday times
    if(jQuery('#suncheck').is(':checked')) {
        jQuery('#sunst').attr("disabled", true);
        jQuery('#sunet').attr("disabled", true);
    } else {
        jQuery('#sunst').attr("disabled", false);
        jQuery('#sunet').attr("disabled", false);
    }
}


function checkonsubmit() {
    jQuery(".error").hide();
    var mflag = 1;
    var tflag = 1;
    var wflag = 1;
    var thflag = 1;
    var fflag = 1;
    var satflag = 1;
    var sunflag = 1;

    <!--Monday-->
    if(jQuery('#mcheck').is(':checked')) var mcheck = "mclose"; else var mcheck = "";
    if(!mcheck) {
       var mst = jQuery('#mst').val();
       var met = jQuery('#met').val();
        //equal check
        if(mst == met) {
            alert("<?php echo __("Monday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //monday start-time
        //convert both time into timestamp
        var mst = new Date("November 3, 2013 " + mst);
        mst = mst.getTime();
        var met = new Date("November 3, 2013 " + met);
        met = met.getTime();

        if(mst > met) {
            alert("<?php echo __("Monday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //monday end-time
        //convert both time into timestamp
        var mst = new Date("November 3, 2013 " + mst);
        mst = mst.getTime();
        var met = new Date("November 3, 2013 " + met);
        met = met.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ mst + " Time2: " + met);

        if(met !=null){
            if(mst > met) {
                alert("<?php echo __("Monday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }



    //tuesday
    if(jQuery('#tucheck').is(':checked')) var tucheck = "tuclose"; else var tucheck = "";
    if(!tucheck) {
       var tst = jQuery('#tst').val();
       var tet = jQuery('#tet').val();
       //equal check
        if(tst == tet) {
            alert("<?php echo __("Tuesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //tuesday start-time
        //convert both time into timestamp
        var tst = new Date("November 3, 2013 " + tst);
        tst = tst.getTime();
        var tet = new Date("November 3, 2013 " + tet);
        tet = tet.getTime();

        if(tst > tet) {
            alert("<?php echo __("Tuesday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //tuesday end-time
        //convert both time into timestamp
        var tst = new Date("November 3, 2013 " + tst);
        tst = tst.getTime();
        var tet = new Date("November 3, 2013 " + tet);
        tet = tet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ tst + " Time2: " + tet);
        if(tet !=null)
        {
            if(tst > tet) {
                alert("<?php echo __("Tuesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }




    //wednesday
    if(jQuery('#wcheck').is(':checked')) var wcheck = "wclose"; else var wcheck = "";
    if(!wcheck) {
       var wst = jQuery('#wst').val();
       var wet = jQuery('#wet').val();
       //equal check
        if(wst == wet) {
            alert("<?php echo __("Wednesday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //wednesday start-time
        //convert both time into timestamp
        var wst = new Date("November 3, 2013 " + wst);
        wst = wst.getTime();
        var wet = new Date("November 3, 2013 " + wet);
        wet = wet.getTime();

        if(wst > wet) {
            alert("<?php echo __("Wednesday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //wednesday end-time
        //convert both time into timestamp
        var wst = new Date("November 3, 2013 " + wst);
        wst = wst.getTime();
        var wet = new Date("November 3, 2013 " + wet);
        wet = wet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ wst + " Time2: " + wet);

        if(wet !=null)
        {
            if(wst > wet) {
                alert("<?php echo __("Wednesday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }




    //thursday
    if(jQuery('#thcheck').is(':checked')) var thcheck = "thclose"; else var thcheck = "";
    if(!thcheck) {
       var thst = jQuery('#thst').val();
       var thet = jQuery('#thet').val();
       //equal check
        if(thst == thet) {
            alert("<?php echo __("Thursday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //thursday start-time

        //convert both time into timestamp
        var thst = new Date("November 3, 2013 " + thst);
        thst = thst.getTime();
        var thet = new Date("November 3, 2013 " + thet);
        thet = thet.getTime();

        if(thst > thet) {
            alert("<?php echo __("Thursday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //thursday end-time
        //convert both time into timestamp
        var thst = new Date("November 3, 2013 " + thst);
        thst = thst.getTime();
        var thet = new Date("November 3, 2013 " + thet);
        thet = thet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ thst + " Time2: " + thet);
        if(thet !=null)
        {
            if(thst > thet) {
                alert("<?php echo __("Thursday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }


    //friday
    if(jQuery('#fcheck').is(':checked')) var fcheck = "fclose"; else var fcheck = "";
    if(!fcheck) {
       var fst = jQuery('#fst').val();
       var fet = jQuery('#fet').val();
        //equal check
        if(fst == fet) {
            alert("<?php echo __("Friday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //friday start-time
        //convert both time into timestamp
        var fst = new Date("November 3, 2013 " + fst);
        fst = fst.getTime();
        var fet = new Date("November 3, 2013 " + fet);
        fet = fet.getTime();

        if(fst > fet) {
            alert("<?php echo __("Friday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //friday end-time
        var fst = new Date("November 3, 2013 " + fst);
        fst = fst.getTime();
        var fet = new Date("November 3, 2013 " + fet);
        fet = fet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ fst + " Time2: " + fet);

        if(fet !=null)
        {
            if(fst > fet) {
                alert("<?php echo __("Friday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }

    //saturday
    if(jQuery('#satcheck').is(':checked')) var satcheck = "satclose"; else var satcheck = "";
    if(!satcheck) {
       var satst = jQuery('#satst').val();
       var satet = jQuery('#satet').val();
       //equal check
        if(satst == satet) {
            alert("<?php echo __("Saturday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //saturday start-time
        //convert both time into timestamp
        var satst = new Date("November 3, 2013 " + satst);
        satst = satst.getTime();
        var satet = new Date("November 3, 2013 " + satet);
        satet = satet.getTime();

        if(satst > satet) {
            alert("<?php echo __("Saturday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //saturday end-time
        //convert both time into timestamp
        var satst = new Date("November 3, 2013 " + satst);
        satst = satst.getTime();
        var satet = new Date("November 3, 2013 " + satet);
        satet = satet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ satst + " Time2: " + satet);

        if(satet !=null)
        {
            if(satst > satet) {
                alert("<?php echo __("Saturday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }


    //sunday
    if(jQuery('#suncheck').is(':checked')) var suncheck = "sunclose"; else var suncheck = "";
    if(!suncheck) {
       var sunst = jQuery('#sunst').val();
       var sunet = jQuery('#sunet').val();
       //equal check
        if(sunst == sunet) {
            alert("<?php echo __("Sunday's Start-time and End-time can't be equal" ,'appointzilla'); ?>");	return false;
        }
        //sunday start-time
        //convert both time into timestamp
        var sunst = new Date("November 3, 2013 " + sunst);
        sunst = sunst.getTime();
        var sunet = new Date("November 3, 2013 " + sunet);
        sunet = sunet.getTime();
        if(sunst > sunet) {
            alert("<?php echo __("Sunday's Start-time must be smaller then End-time" ,'appointzilla'); ?>");	return false;
        }
        //sunday end-time
        //convert both time into timestamp
        var sunst = new Date("November 3, 2013 " + sunst);
        sunst = sunst.getTime();
        var sunet = new Date("November 3, 2013 " + sunet);
        sunet = sunet.getTime();
        //by this you can see time stamp value in console via firebug
        console.log("Time1: "+ sunst + " Time2: " + sunet);

        if(sunet !=null)
        {
            if(sunst > sunet) {
                alert("<?php echo __("Sunday's End-time must be bigger then Start-time" ,'appointzilla'); ?>"); return false;
            }
        }
    }

    var mst = jQuery('#mst').val();
    var met = jQuery('#met').val();
    var tst = jQuery('#tst').val();
    var tet = jQuery('#tet').val();
    var wst = jQuery('#wst').val();
    var wet = jQuery('#wet').val();
    var thst = jQuery('#thst').val();
    var thet = jQuery('#thet').val();
    var fst = jQuery('#fst').val();
    var fet = jQuery('#fet').val();
    var satst = jQuery('#satst').val();
    var satet = jQuery('#satet').val();
    var sunst = jQuery('#sunst').val();
    var sunet = jQuery('#sunet').val();
    var selectstaff = jQuery('#selectstaff').val();
    var DataString = "savestaffhours=" + 'yes' + "&mcheck=" + mcheck + "&mst=" + mst + "&met=" + met + "&tucheck=" + tucheck + "&tst=" + tst + "&tet=" + tet + "&wcheck=" + wcheck + "&wst=" + wst + "&wet=" + wet + "&thcheck=" + thcheck + "&thst=" + thst + "&thet=" + thet + "&fcheck=" + fcheck + "&fst=" + fst + "&fet=" + fet + "&satcheck=" + satcheck + "&satst=" + satst + "&satet=" + satet + "&suncheck=" + suncheck + "&sunst=" + sunst + "&sunet=" + sunet + "&selectstaff=" + selectstaff;
    jQuery.ajax({
        dataType : 'html',
        type: 'POST',
        url : location.href,
        cache: false,
        data : DataString,
        complete : function() {  },
        success: function(data) {
            alert("<?php echo __('Staff hours successfully saved.', 'appointzilla'); ?>");
            location.href = url+"&staff_id=" + selectstaff;
        }
    });
}
</script>


<?php $TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $SHTimeFormat = "g:i A";
if($TimeFormat == 'h:i') $SHTimeFormat = "g:i A";
if($TimeFormat == 'H:i') $SHTimeFormat = "G:i"; ?>
    <form action="" method="post" name="save-staff-hours">
        <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;"><h3><?php _e('Staff Hours' ,'appointzilla'); ?></h3></div>
        <div style="width:60%;">
            <?php global $wpdb;
            $StaffTableName = $wpdb->prefix . "ap_staff";
            $AllStaff = $wpdb->get_results("SELECT * FROM `$StaffTableName`"); ?>
            <table width="100%" class="items table table-bordered">
                <tr>
                    <th align="center"><?php _e('Select Staff' ,'appointzilla'); ?></th>
                    <td colspan="3"><select name="selectstaff" id="selectstaff" onchange="SavedStaffHour('abc')">
                        <option value="0"><?php _e('Select any staff' ,'appointzilla'); ?></option>
                          <?php
                          $SavedID = NULL;
                          if(isset($_GET['staff_id'])) {
                            $SavedID = $_GET['staff_id'];
                          }
                          foreach($AllStaff as $SingleStaff) {
                            if($SavedID == $SingleStaff->id)
                                echo $selected = "selected";
                            else
                                echo $selected = "";

                            echo "<option value=$SingleStaff->id $selected>$SingleStaff->name</option>";
                          } ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div id="loading-staff" style="display:none;"><?php _e('Loading staff hours...', 'appointzilla'); ?><img src="<?php echo plugins_url('appointment-calendar-premium/images/loading.gif'); ?>" /></div>
    </form>

<?php if(isset($_GET['staff_id'])) { ?>
    <script>
        var stid = '<?php echo $_GET['staff_id']; ?>';
        SavedStaffHour(stid);
     </script> <?php
}


// loading current staff hours settings
if(isset($_POST['staff_id'])) {
    if($_POST['staff_id']) { ?>
        <div id="loadstaffhours" style="width:60%;">
            <form action="" method="post" name="save-staff-hours-2">
                <input name="selectstaff" id="selectstaff" type="hidden" value="<?php echo $_POST['staff_id']; ?>" />
                <?php $StaffId = $_POST['staff_id'];
                $message = "";
                global $wpdb;
                $StaffTableName = $wpdb->prefix . "ap_staff";
                $FetchStaffHours = $wpdb->get_row("SELECT `staff_hours` FROM `$StaffTableName` WHERE `id` = '$StaffId'");
                $StaffHours = unserialize($FetchStaffHours->staff_hours);
                if($StaffHours) {
                    $Mst = $StaffHours['monday_start_time'];
                    $Met = $StaffHours['monday_end_time'];
                    $Mclose = $StaffHours['monday_close'];

                    $Tust = $StaffHours['tuesday_start_time'];
                    $Tuet = $StaffHours['tuesday_end_time'];
                    $Tuclose = $StaffHours['tuesday_close'];

                    $Wst = $StaffHours['wednesday_start_time'];
                    $Wet = $StaffHours['wednesday_end_time'];
                    $Wclose = $StaffHours['wednesday_close'];

                    $Thst = $StaffHours['thursday_start_time'];
                    $Thet = $StaffHours['thursday_end_time'];
                    $Thclose = $StaffHours['thursday_close'];

                    $Fst = $StaffHours['friday_start_time'];
                    $Fet = $StaffHours['friday_end_time'];
                    $Fclose = $StaffHours['friday_close'];

                    $Satst = $StaffHours['saturday_start_time'];
                    $Satet = $StaffHours['saturday_end_time'];
                    $Satclose = $StaffHours['saturday_close'];

                    $Sunst = $StaffHours['sunday_start_time'];
                    $Sunet = $StaffHours['sunday_end_time'];
                    $Sunclose = $StaffHours['sunday_close'];
                } else {
                    $BusinessHoursTableName = $wpdb->prefix . "ap_business_hours";
                    $FetchBusinessHours_sql = "SELECT * FROM `$BusinessHoursTableName`";
                    $GetBusinessHours = $wpdb->get_results($FetchBusinessHours_sql, OBJECT);

                    if($GetBusinessHours) {
                        foreach($GetBusinessHours as $Singleday) {
                            if($Singleday->day == 'monday') {
                                $Mst = $Singleday->start_time;
                                $Met = $Singleday->end_time;
                                $Mclose = $Singleday->close;
                            }

                            if($Singleday->day == 'tuesday') {
                                $Tust = $Singleday->start_time;
                                $Tuet = $Singleday->end_time;
                                $Tuclose = $Singleday->close;
                            }

                            if($Singleday->day == 'wednesday') {
                                $Wst = $Singleday->start_time;
                                $Wet = $Singleday->end_time;
                                $Wclose = $Singleday->close;
                            }

                            if($Singleday->day == 'thursday') {
                                $Thst = $Singleday->start_time;
                                $Thet = $Singleday->end_time;
                                $Thclose = $Singleday->close;
                            }

                            if($Singleday->day == 'friday') {
                                $Fst = $Singleday->start_time;
                                $Fet = $Singleday->end_time;
                                $Fclose = $Singleday->close;
                            }

                            if($Singleday->day == 'saturday') {
                                $Satst = $Singleday->start_time;
                                $Satet = $Singleday->end_time;
                                $Satclose = $Singleday->close;
                            }

                            if($Singleday->day == 'sunday') {
                                $Sunst = $Singleday->start_time;
                                $Sunet = $Singleday->end_time;
                                $Sunclose = $Singleday->close;
                            }
                        }// end of ifGetBusinessHours
                        echo $message = "<p class='alert alert-error'><i class='icon-warning-sign'></i> ".__('Current staff not assigned its staff hours. Please assign its staff hours', 'appointzilla').".</p>";
                    }// end of else
                }// end of StaffHours if ?>

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
                                    if($Mclose == 'yes') $mdisable="disabled";  else $mdisable ="";
                                    $time = time();
                                    $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                    echo "<select id=mst name=mst ".$mdisable." onchange=mondaystarttime()>";
                                    $start = strtotime('12:00am');
                                    $end = strtotime('11:59pm');
                                    for( $i = $start; $i <= $end; $i += 1800)  {
                                        if($Mst != 'none')  $default = $Mst;  else $default = '10:00 AM';
                                        //made 10:00 AM selected
                                        if(date('g:i A', $i) == $default) {
                                            echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                        } else {
                                            //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                            echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                        }
                                    }
                                    echo '</select>'; ?>
                            </td>
                            <td>
                                <?php
                                    $time = time();
                                    $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                    echo "<select id=met name=met ".$mdisable." onchange=mondayendtime()>";
                                    $start = strtotime('12:00am');
                                    $end = strtotime('11:59pm');
                                    for( $i = $start; $i <= $end; $i += 1800)
                                    {
                                        if($Met != 'none')  $default = $Met;  else $default = "5:00 PM";
                                        if(date('g:i A', $i) == $default )	//made 5:00 PM selected
                                        {
                                            echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                        }
                                        else
                                        {
                                            //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                            echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?></td>
                            <td align="center">
                            <input name="mcheck" type="checkbox" id="mcheck" value="mclose" onclick="mondaycheck()" <?php if($Mclose == 'yes'){ echo "checked=checked"; } ?> />		</td>
                      </tr>
                      <?php //} // end of day creating loop ?>


                      <tr>
                        <td align="center"><?php _e('Tuesday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Tuclose == 'yes') $tdisable="disabled";  else $tdisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=tst name=tst '.$tdisable.' onchange=tuesdaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Tust != 'none')  $default = $Tust;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=tet name=tet '.$tdisable.' onchange=tuesdayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Tuet != 'none')  $default = $Tuet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center">
                          <input name="tucheck" type="checkbox" id="tucheck" value="tuclose" onclick="tuesdaycheck()" <?php if($Tuclose == 'yes'){ echo "checked=checked"; } ?> />		</td>
                      </tr>

                      <tr>
                        <td align="center"><?php _e('Wednesday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Wclose == 'yes') $wdisable="disabled";  else $wdisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=wst name=wst '.$wdisable.' onchange=wednesdaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Wst != 'none')  $default = $Wst;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=wet name=wet '.$wdisable.' onchange=wednesdayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Wet != 'none')  $default = $Wet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center"><input name="wcheck" type="checkbox" id="wcheck" value="wclose" onclick="wednesdaycheck()" <?php if($Wclose == 'yes'){ echo "checked=checked"; } ?> /></td>
                      </tr>

                      <tr>
                        <td align="center"><?php _e('Thursday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Thclose == 'yes') $thdisable="disabled";  else $thdisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=thst name=thst '.$thdisable.' onchange=thursdaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Thst != 'none')  $default = $Thst;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=thet name=thet '.$thdisable.' onchange=thursdayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Thet != 'none')  $default = $Thet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center"><input name="thcheck" type="checkbox" id="thcheck" value="thclose" onclick="thursdaycheck()" <?php if($Thclose == 'yes'){ echo "checked=checked"; } ?> /></td>
                      </tr>

                      <tr>
                        <td align="center"><?php _e('Friday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Fclose == 'yes') $fdisable="disabled";  else $fdisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=fst name=fst '.$fdisable.' onchange=fridaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Fst != 'none')  $default = $Fst;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        //$selected = ( $rounded_time == $i) ? ' selected="selected"' : '';
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=fet name=fet '.$fdisable.' onchange=fridayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Fet != 'none')  $default = $Fet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center"><input name="fcheck" type="checkbox" id="fcheck" value="fclose" onclick="fridaycheck()" <?php if($Fclose == 'yes'){ echo "checked=checked"; } ?>  /></td>
                      </tr>

                      <tr>
                        <td align="center"><?php _e('Saturday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Satclose == 'yes') $satdisable="disabled";  else $satdisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=satst name=satst '.$satdisable.' onchange=saturdaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Satst != 'none')  $default = $Satst;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=satet name=satet '.$satdisable.' onchange=saturdayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Satet != 'none')  $default = $Satet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center"><input name="satcheck" type="checkbox" id="satcheck" value="satclose" onclick="saturdaycheck()" <?php if($Satclose == 'yes'){ echo "checked=checked"; } ?> /></td>
                      </tr>

                      <tr>
                        <td align="center"><?php _e('Sunday' ,'appointzilla'); ?></td>
                        <td><?php
                                if($Sunclose == 'yes') $sundisable="disabled"; else $sundisable ="";
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=sunst name=sunst '.$sundisable.' onchange=sundaystarttime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Sunst  != 'none')  $default = $Sunst;  else $default = '10:00 AM';
                                    if(date('g:i A', $i) == $default)	//made 10:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>

                        <td><?php
                                $time = time();
                                $rounded_time = $time % 900 > 450 ? $time += (900 - $time % 900):  $time -= $time % 900;
                                echo '<select id=sunet name=sunet '.$sundisable.' onchange=sundayendtime()>';
                                $start = strtotime('12:00am');
                                $end = strtotime('11:59pm');
                                for( $i = $start; $i <= $end; $i += 1800)
                                {
                                    if($Sunet != 'none') $default = $Sunet;  else $default = "5:00 PM";
                                    if(date('g:i A', $i) == $default )	//made 5:00 AM selected
                                    {
                                        echo "<option value='" .date('g:i A', $i). "' selected='selected'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" .date('g:i A', $i). "'>" . date($SHTimeFormat, $i) . "</option>";
                                    }
                                }
                                echo '</select>';
                            ?>        </td>
                        <td align="center"><input name="suncheck" type="checkbox" id="suncheck" value="sunclose" onclick="sundaycheck()" <?php if($Sunclose == 'yes'){ echo "checked=checked"; } ?> /></td>
                      </tr>
                </table>

                    <button name="savestaffhours" class="btn btn-primary" onclick="checkonsubmit()" type="button" id="savestaffhours"><i class="icon-ok icon-white"></i> <?php _e('Save' ,'appointzilla'); ?></button>
                </div>
            </form>
    <?php
    }// end of inner if
} ?>


<!--DIV TO LOAD STAFF HOURS-->
<div id="Showstaffhours" style="display:none;">
</div>


<?php //saving business hours
if(isset($_POST['savestaffhours'])) {
    //monday
    if($_POST['mcheck'] == 'mclose') {
        $mst = "none";
        $met = "none";
        $Mclose = "yes";
    } else {
        $mst = $_POST['mst'];
        $met = $_POST['met'];
        $Mclose = "no";
    }

    //tuesday
    if($_POST['tucheck'] == 'tuclose') {
        $tst = "none";
        $tet = "none";
        $Tuclose = "yes";
    } else {
        $tst = $_POST['tst'];
        $tet = $_POST['tet'];
        $Tuclose = "no";
    }

    //Wednesday
    if($_POST['wcheck'] == 'wclose') {
        $wst = "none";
        $wet = "none";
        $Wclose = "yes";
    } else {
        $wst = $_POST['wst'];
        $wet = $_POST['wet'];
        $Wclose = "no";
    }

    //Thusday
    if($_POST['thcheck'] == 'thclose') {
        $thst = "none";
        $thet = "none";
        $Thclose = "yes";
    } else {
        $thst = $_POST['thst'];
        $thet = $_POST['thet'];
        $Thclose = "no";
    }

    //Friday
    if($_POST['fcheck'] == 'fclose') {
        $fst = "none";
        $fet = "none";
        $Fclose = "yes";
    } else {
        $fst = $_POST['fst'];
        $fet = $_POST['fet'];
        $Fclose = "no";
    }


    //Saturday
    if($_POST['satcheck'] == 'satclose') {
        $satst = "none";
        $satet = "none";
        $Satclose = "yes";
    } else {
        $satst = $_POST['satst'];
        $satet = $_POST['satet'];
        $Satclose = "no";
    }


    //Sunday
    if($_POST['suncheck'] == 'sunclose') {
        $sunst = "none";
        $sunet = "none";
        $Sunclose = "yes";
    } else {
        $sunst = $_POST['sunst'];
        $sunet = $_POST['sunet'];
        $Sunclose = "no";
    }

    $StaffAllHoursArray = array(
        'monday_start_time' => $mst,            //monday time
        'monday_end_time' => $met,
        'monday_close' => $Mclose,

        'tuesday_start_time' => $tst,           //tuesday time
        'tuesday_end_time' => $tet,
        'tuesday_close' => $Tuclose,

        'wednesday_start_time' => $wst,         //wednesday time
        'wednesday_end_time' => $wet,
        'wednesday_close' => $Wclose,

        'thursday_start_time' => $thst,         //thursday time
        'thursday_end_time' => $thet,
        'thursday_close' => $Thclose,

        'friday_start_time' => $fst,            //friday time
        'friday_end_time' => $fet,
        'friday_close' => $Fclose,


        'saturday_start_time' => $satst,        //saturday time
        'saturday_end_time' => $satet,
        'saturday_close' => $Satclose,

        'sunday_start_time' => $sunst,          //sunday time
        'sunday_end_time' => $sunet,
        'sunday_close' => $Sunclose,
    );
    $StaffAllHours = serialize($StaffAllHoursArray);
    global $wpdb;
    $StaffId = $_POST['selectstaff'];
    $StaffTableName = $wpdb->prefix."ap_staff";

    //update if already saved
    $Fetched = $wpdb->get_row("SELECT `staff_hours`, FROM `$StaffTableName` WHERE `id` = '$StaffId' ");
    if($Fetched->staff_hours) {
        if($wpdb->query("UPDATE `$StaffTableName` SET `staff_hours` = '$StaffAllHours' WHERE `id` = '$StaffId' "))
        {  ?> <script>alert("<?php echo $Fetched->name.' '. __('Staff hours successfully updated.', 'appointzilla'); ?>");</script> <?php }
        else
        { ?> <script>alert("<?php echo __('No updates made.', 'appointzilla'); ?>");</script> <?php }
    } else {
        if($wpdb->query("UPDATE `$StaffTableName` SET `staff_hours` = '$StaffAllHours' WHERE `id` = '$StaffId' "))
        { ?> <script>alert("<?php echo $Fetched->name.' '. __('Staff hours successfully saved.', 'appointzilla'); ?>");</script> <?php }
        else
        { ?> <script>alert("<?php echo __('No updates made.', 'appointzilla'); ?>");</script> <?php }
    }
} // end of isset ?>