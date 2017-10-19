<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<!--js work-->					
<style type="text/css">
.error{  color:#FF0000; }
input.inputheight
{
	height:30px;
}
#editgruop
{
	margin-bottom:10px;
}
#editgruopcancel
{
	margin-bottom:10px;
}
</style>
<div class="bs-docs-example tooltip-demo">
<?php 
global $wpdb;
if(!isset($_GET['bupid']))
{
	
	$table_name = $wpdb->prefix . "ap_business";
	$ap_business = $wpdb->get_row("select * from `$table_name` WHERE `id` = '1' "); 
	if($ap_business) // get busniss profile details 
	{
?>	<div style="background:#C3D9FF; padding-left:10px;">
		<h3><?php echo _e('Business Profile' ,'appointzilla'); ?><a style="float:right; margin-top:5px; margin-right:5px;" class="btn btn-primary" href="?page=app-calendar-settings&show=businessprofile&bupid=<?php echo $ap_business->id; ?>"><?php _e('Edit' ,'appointzilla'); ?></a></h3>
	 </div>
		<table width="100%" class="detail-view table table-striped table-condensed" >
			 <tr><th width="11%"><?php _e('Business Name' ,'appointzilla'); ?></th> <td width="7%"><strong>:</strong></td> 
			 <td width="82%"><em><?php if($ap_business) echo $ap_business->name; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Owner' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if($ap_business) echo $ap_business->owener; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Address' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if($ap_business) echo $ap_business->address; ?></em></td>
			 </tr>
			 <tr><th><?php _e('City' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if($ap_business) echo $ap_business->city; ?></em></td>
			 </tr>
			 <tr><th><?php _e('State' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if($ap_business) echo $ap_business->state; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Phone' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if($ap_business) echo $ap_business->phone; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Fax' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td><em><?php if(!$ap_business->fax) echo $ap_business->fax; else echo "Not Available"; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Postal Code' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td> <em><?php if($ap_business) echo strtoupper($ap_business->zipcode); ?></em></td>
			 </tr>
			 <tr><th><?php _e('Email' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td> <em><?php if($ap_business) echo $ap_business->email; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Website' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
			 <td> <em><?php if($ap_business) echo $ap_business->website; ?></em></td>
			 </tr>
			 <tr><th><?php _e('Blog Url' ,'appointzilla'); ?></th><td><strong>:</strong></td>
			 <td><em><?php if($ap_business) echo $ap_business->blog_url; else echo (__('Not Available' ,'appointzilla')); ?></em></td>
			 </tr>
  </table>
   
  <?php } // Create new business profile
  		else 
  		{ ?>
		<div style="background:#C3D9FF; padding-left:10px;">
			<h3><?php _e('Business Profile', 'appointzilla'); ?><a style="margin-top:5px; margin-right:5px; float:right;" class="btn btn-primary" href="?page=app-calendar-settings&show=businessprofile&bupid=new"><?php _e('Setup Business Profile' ,'appointzilla'); ?></a></h3>
		</div>
 <?php } // end of business profile
 }
 else
 {     // get upid than show this code with data and without data
		if(isset($_GET['bupid']))
		{
			$table_name = $wpdb->prefix . "ap_business";
			$ap_business = $wpdb->get_row("select * from `$table_name` WHERE `id` = '1' "); 
  		}
  	?>

	<div style="background:#C3D9FF; padding-left:10px;"><h3><?php echo _e('Business Profile' ,'appointzilla'); ?></h3></div>
 
		<form action="" method="post" name="business">
		<table width="100%" class="detail-view table table-striped table-condensed" >
		 <tr><th width="11%"><?php _e('Business Name' ,'appointzilla'); ?></th> 
		 <td width="7%"><strong> :</strong></td> 
		 <td width="81%"><input name="bname" id="bname" type="text"  value="<?php if($ap_business) echo $ap_business->name; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Business  Name.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
		 <td width="1%"></td></tr>
		 <tr><th><?php _e('Owner' ,'appointzilla'); ?></th> <td><strong> :</strong></td> 
		 <td><input name="bowener" id="bowener" type="text"  value="<?php if($ap_business) echo $ap_business->owener; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Owner Name.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
		 </td></tr>
		 <tr><th><?php _e('Address' ,'appointzilla'); ?></th> <td><strong> :</strong></td> 
		 <td><input name="baddress" id="baddress" type="text"  value="<?php if($ap_business) echo $ap_business->address; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Address.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
		 </td></tr>
		 <tr><th><?php _e('City' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td><input name="bcity" id="bcity" type="text"  value="<?php if($ap_business) echo $ap_business->city; ?>" />&nbsp;<a href="#" rel="tooltip"  title="<?php _e('City.' ,'appointzilla'); ?>"><i class="icon-question-sign"></i> </a></td>
		  </td></tr>
		 <tr><th ><?php _e('State' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td><input name="bstate" id="bstate" type="text"  value="<?php if($ap_business) echo $ap_business->state; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('State.' ,'appointzilla'); ?>"><i class="icon-question-sign"></i></a></td>
		 </td></tr>
		 <tr><th><?php _e('Phone' ,'appointzilla'); ?></th> <td><strong> :</strong></td> 
		 <td><input name="bphone" id="bphone" type="text"  value="<?php if($ap_business) echo $ap_business->phone; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Phone Number.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td>
		  </td></tr>
		 <tr><th ><?php _e('Fax' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td><input name="bfax" id="bfax" type="text"  value="<?php if($ap_business) echo $ap_business->fax; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Fax Number.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td></tr>
		 <tr><th><?php _e('Postal Code' ,'appointzilla'); ?></th> <td><strong>:</strong></td> 
		 <td><input name="bzipcode" id="bzipcode" type="text"  value="<?php if($ap_business) echo $ap_business->zipcode; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Postal Code.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td></tr>
		 <tr><th><?php _e('Email' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td><input name="bemail" id="bemail" type="text"  value="<?php if($ap_business) echo $ap_business->email; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Email.' ,'appointzilla'); ?>" ><i class="icon-question-sign"></i></a></td></tr>
		 <tr><th><?php _e('Website' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td><input name="bwebsite" id="bwebsite" type="text"  value="<?php if($ap_business) echo $ap_business->website; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Business Website URL.' ,'appointzilla'); ?> Ex. http://www.appointzilla.com/"><i class="icon-question-sign"></i> </a>		 </td></tr>
		 <tr>
		   <th><?php _e('Blog Url' ,'appointzilla'); ?></th><td><strong>:</strong></td> 
		 <td> <input name="bblog" id="bblog" type="text"  value="<?php if($ap_business) echo $ap_business->blog_url; ?>" />&nbsp;<a href="#" rel="tooltip" title="<?php _e('Blog URL.' ,'appointzilla'); ?> Ex. http://www.appointzilla.com/blog"><i class="icon-question-sign"></i></a>		 </td></tr>
		 <tr><td></td><td><!--<input type="submit" value="Update" name="bisupdate" id="bisupdate"  class="btn btn-primary" />--></td>
		   <td><?php if($_GET['bupid'] != 'new') { ?>
             <button name="bisupdate" class="btn btn-primary" type="submit" value="<?php if($ap_business) echo $ap_business->id; ?>" id="bisupdate"><?php _e('Update' ,'appointzilla'); ?></button>
             <?php } else { ?>
             <button name="biscreate" class="btn btn-primary" type="submit" id="biscreate"><?php _e('Create' ,'appointzilla'); ?></button>
             <?php } ?>
			 <a href="?page=app-calendar-settings&show=businessprofile" class="btn btn-primary"><?php _e('Cancel' ,'appointzilla'); ?></a>
		   </td>
		 </tr>
	</table>
		</form>
		<!---Tooltip js ---------->
<?php
 } //end of upid condintions else
 
// Create business profile
 if(isset($_POST['biscreate'])) 
	{	
			global $wpdb;
			$bname = strip_tags($_POST['bname']);
			$bowener = strip_tags($_POST['bowener']);
			$baddress = strip_tags($_POST['baddress']);
			$bcity = strip_tags($_POST['bcity']);
			$bstate = strip_tags($_POST['bstate']);
			$bphone = $_POST['bphone'];
			$bfax = $_POST['bfax'];
			$bzipcode = $_POST['bzipcode'];
			$bemail = $_POST['bemail'];
			$bwebsite = $_POST['bwebsite'];
			$bblog = $_POST['bblog'];
			
			$table_name = $wpdb->prefix . "ap_business";
			$insert_business = "INSERT INTO `$table_name` (`id` ,`name` ,`owener` ,`address` ,`city` ,`state` ,`zipcode` ,`phone` ,`fax` ,`email` ,`website` ,`blog_url`)	VALUES ('1', '$bname', '$bowener', '$baddress', '$bcity', '$bstate', '$bzipcode', '$bphone', '$bfax', '$bemail', '$bwebsite', '$bblog');";
			if($wpdb->query($insert_business))
			{
				echo "<script>alert('" . __('Business profile successfully created.' ,'appointzilla') . "');</script>";
				echo "<script>location.href='?page=app-calendar-settings&show=businessprofile';</script>";	
			}
	}
// update business profile 
 if(isset($_POST['bisupdate']))
	{
		global $wpdb;
		$up_bis_id = strip_tags($_POST['bisupdate']);
		$bname = strip_tags($_POST['bname']);
		$bowener = strip_tags($_POST['bowener']);
		$baddress = strip_tags($_POST['baddress']);
		$bcity = strip_tags($_POST['bcity']);
		$bstate = strip_tags($_POST['bstate']);
		$bphone = $_POST['bphone'];
		$bfax = $_POST['bfax'];
		$bzipcode = $_POST['bzipcode'];
		$bemail = $_POST['bemail'];
		$bwebsite = $_POST['bwebsite'];
		$bblog = $_POST['bblog'];
			
		$table_name = $wpdb->prefix . "ap_business";
		
		$update_business="UPDATE `$table_name` SET `name` = '$bname',
												`owener` = '$bowener',
												`address` = '$baddress',
												`city` = '$bcity',
												`state` = '$bstate',
												`zipcode` = '$bzipcode',
												`phone` = '$bphone',
												`fax` = '$bfax',
												`email` = '$bemail',
												`website` = '$bwebsite',
												`blog_url` = '$bblog' WHERE `id` ='$up_bis_id';";
		if($wpdb->query($update_business))
		{
			echo "<script>alert('" . __('Business profile successfully updated.' ,'appointzilla') . "');</script>";
			echo "<script>location.href='?page=app-calendar-settings&show=businessprofile';</script>";	
		}
		else
			echo "<script>alert('". __('No updates made.' ,'appointzilla') . "');</script>";
}
?> 


<script type="text/javascript">
jQuery(document).ready(function () {
	// form submit validation js 
	jQuery('form').submit(function() 
		{
			jQuery('.error').hide();  
			var bname = jQuery("input#bname").val();  
			if (bname =="")
			{  	jQuery("#bname").after('<span class="error">&nbsp;<br><strong><?php _e('Business name cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	var bname = isNaN(bname);
				if(bname == false) 
				{ 	
				jQuery("#bname").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			
			var bowener = jQuery("input#bowener").val();  
			if (bowener =="")
			{  	jQuery("#bowener").after('<span class="error">&nbsp;<br><strong><?php _e('Owner name cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	var bowener = isNaN(bowener);
				if(bowener == false) 
				{ 	
				jQuery("#bowener").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			
			var baddress = jQuery("input#baddress").val();  
			if (baddress =="")
			{  	jQuery("#baddress").after('<span class="error">&nbsp;<br><strong><?php _e('Address cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			
			var bcity = jQuery("input#bcity").val();  
			if (bcity =="")
			{  	jQuery("#bcity").after('<span class="error">&nbsp;<br><strong><?php _e('City cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	var bcity = isNaN(bcity);
				if(bcity == false) 
				{ 	
				jQuery("#bcity").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			
			var bstate = jQuery("input#bstate").val();  
			if (bstate =="")
			{  	jQuery("#bstate").after('<span class="error">&nbsp;<br><strong><?php _e('State cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	var bstate = isNaN(bstate);
				if(bstate == false) 
				{ 	
				jQuery("#bstate").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			var bphone = jQuery("input#bphone").val();  
			if (bphone =="")
			{  	jQuery("#bphone").after('<span class="error">&nbsp;<br><strong><?php _e('Phone cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	var bphone = isNaN(bphone);
				if(bphone == true) 
				{ 	
				jQuery("#bphone").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			
			
			/*var bfax = jQuery("input#bfax").val();  
			if (bfax =="")
			{  	jQuery("#bfax").after('<span class="error">&nbsp;<br><strong>Fax cannot be blank.</strong></span>');
				return false; 
			}
			else
			{	var bfax = isNaN(bfax);
				if(bfax == true) 
				{ 	
				jQuery("#bfax").after('<span class="error">&nbsp;<br><strong>Invalid value.</strong></span>');
				return false; 
				
				}
			}*/
			
			var bzipcode = jQuery("input#bzipcode").val();  
			if (bzipcode =="")
			{  	jQuery("#bzipcode").after('<span class="error">&nbsp;<br><strong><?php _e('Postal code cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}

			
			var bemail = jQuery("input#bemail").val();  
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (bemail =="")
			{  	jQuery("#bemail").after('<span class="error">&nbsp;<br><strong><?php _e('Email cannot be blank.' ,'appointzilla'); ?></strong></span>');
				return false; 
			}
			else
			{	
				if(regex.test(bemail) == false )
				{ 	
				jQuery("#bemail").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid value.' ,'appointzilla'); ?></strong></span>');
				return false; 
				
				}
			}
			
			
			var bwebsite = jQuery("input#bwebsite").val(); 
			var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");

			if (bwebsite !="")
			{	
				if(urlregex.test(bwebsite) == false)
				{ 	
				jQuery("#bwebsite").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid url. Eg. http://www.appointzilla.com' ,'appointzilla'); ?></strong></span>');
				return false;
				}
			}
			
			var bblog = jQuery("input#bblog").val(); 
			if (bblog)
			{	
				if(urlregex.test(bblog) == false)
				{ 	
				jQuery("#bblog").after('<span class="error">&nbsp;<br><strong><?php _e('Invalid blog url. Eg. http://www.appointzilla.com/blog' ,'appointzilla'); ?></strong></span>');
				return false; 
				}
			}

			
		});

});
</script>
</div>