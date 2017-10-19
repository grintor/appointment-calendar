<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
        <h3><i class="fa fa-group"></i> <?php _e('Clients','appointzilla'); ?><a style="float:right; margin-top:5px; margin-right:5px;" class="btn btn-primary" href="?page=client-manage&updateclient=new"><i class="icon-plus icon-white"></i> <?php _e('Add New Client','appointzilla'); ?></a></h3>
    </div>
    <br>
    <form name="searchclient" method="post">
        <table>
            <tr valign="top">
                <td><?php _e('Client Name :','appointzilla'); ?></td>
                <td><input  type="text" name="searchname" id="searchname" /></td>
                <td><button name="searchbutton" class="btn btn-danger" type="submit" id="searchbutton"><i class="icon-search icon-white"></i> <?php _e('Search','appointzilla'); ?></button></td>
            </tr>
        </table>
    </form>
    <?php global $wpdb;
        $i = 1;
        $NoOfRow = 15;
        $offset = 0;
        $table_name = $wpdb->prefix . "ap_clients";
        $FindBy = '';
        $FilterData = NULL;
        require_once('SearchClient.php');
        // pagination start with page no = 1 when filter
        if(!isset($_POST['searchname'])) {
            if(!empty($_GET['pageno'])) {
                $PageNo = $_GET['pageno'];
                $offset = ($PageNo-1)*$NoOfRow;
            }
        }

        if(isset($_POST['searchname']))  {
            // Get all Client By Search  and  Count all Client Row
            $FindBy = $_POST['searchname'];
            $FilterData = $FindBy;
            $SearchClient = new SearchClient();
            $all_client = $SearchClient->AllClinet($NoOfRow, $offset, $table_name, $FindBy);
            $cat = $SearchClient->CountClienttable($table_name,$FindBy);
        } else {
            //paging code  in php with plugin
            if(isset($_GET['filtername'])) {
                // Get all Client By GetURL Client name  and  Count all Client Row
                $FilterData = $_GET['filtername'];
                $FindBy = $FilterData;
                $SearchClient = new SearchClient();
                $all_client = $SearchClient->AllClinet($NoOfRow, $offset, $table_name, $FindBy);
                $cat = $SearchClient->CountClienttable($table_name,$FindBy);
            } else {
                $SearchClient = new SearchClient();
                $all_client = $SearchClient->AllClinet($NoOfRow, $offset, $table_name, $FindBy);
                $cat = $SearchClient->CountClienttable($table_name,$FindBy);
            }
        } ?>

    <form action="" method="post" name="manage-clients">
        <table width="100%" class="table table-hover">
            <thead>
                <tr style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
                    <th><?php _e('No.','appointzilla'); ?></th>
                    <th><?php _e('Name','appointzilla'); ?></th>
                    <th><?php _e('Email','appointzilla'); ?></th>
                    <th><?php _e('Phone','appointzilla'); ?></th>
                    <th><?php _e('Special Note','appointzilla'); ?></th>
                    <th><?php _e('Appointment History','appointzilla'); ?></th>
                    <th><?php _e('Action','appointzilla'); ?> </th>
                    <th><a href="#" rel="tooltip" title="<?php _e('Select All','appointzilla'); ?>" ><input type="checkbox" id="checkbox" name="checkbox[]" value="0" /></a></th>
                </th>
            </thead>
                <?php if($all_client) {
                    foreach($all_client as $client) { ?>
            <tr>
                <td><em><?php echo $i."."; ?></em></td>
                <td><em><?php echo ucwords($client->name); ?></em></td>
                <td><em><?php echo strtolower($client->email); ?></em></td>
                <td><em><?php echo $client->phone; ?></em></td>
                <td><em><?php echo ucfirst($client->note); ?></em></td>
                <td><?php $findapp = $client->email;
                    $appointment_table_name= $wpdb->prefix . "ap_appointments";
                    $toat_app_query = "SELECT * FROM `$appointment_table_name` WHERE `email` = '$findapp';";
                    $Allappointment = $wpdb->get_results($toat_app_query); ?>
                    <a data-placement="right" href="?page=client-manage&viewid=<?php echo $client->id; ?>" title="<?php _e('View','appointzilla'); ?>" rel="tooltip"  class="btn btn-small btn-info"><i class="icon-eye-open icon-white"></i> <?php _e('View','appointzilla'); echo "&nbsp;&nbsp;(".count($Allappointment).")"; ?> </a>
                </td>
                <td>
                    <a href="?page=client-manage&viewid=<?php echo $client->id; ?>" title="<?php _e('View','appointzilla'); ?>" rel="tooltip"><i class="icon-eye-open"></i></a>&nbsp;
                    <a rel="tooltip" href="?page=client-manage&updateclient=<?php echo $client->id; ?>" title="<?php _e('Update','appointzilla'); ?>"><i class="icon-pencil"></i></a>&nbsp;
                    <a href="?page=client&clientdid=<?php echo $client->id; ?>" rel="tooltip" onclick="return confirm('<?php _e('Do you want to delete this client','appointzilla'); ?>')" title="<?php _e('Delete','appointzilla'); ?>"><i class="icon-remove"></i></a>
                </td>
                <td>
                    <a rel="tooltip" title="<?php _e('Select','appointzilla'); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php echo $client->id; ?>" /></a>
                </td>
            </tr>
              <?php $i++;  } ?>

            <tr>
                <td colspan="4">
                    <ul id="pagination-flickr" style="border:1px #CCCCCC;">
                    <li><a href="?page=client&pageno=1&filtername=<?php echo $FilterData; ?>" > <?php _e('First','appointzilla'); ?> </a> </li>
                    <?php // pagination list items
                        if(isset($_GET['pageno'])) { $pgno = $_GET['pageno'];  } else { $pgno = 1; }
                        $catrow = count($cat);
                        $page = ceil($catrow/$NoOfRow);
                        for($i=1; $i <= $page; $i++) { ?>
                            <li><a href="?page=client&pageno=<?php echo $i?>&filtername=<?php echo $FilterData; ?>" <?php if($pgno == $i ) { echo "class='active'"; } else { echo "class=''"; } ?> ><?php echo $i; ?></a></li>
                        <?php }	 ?>
                            <li><a href="?page=client&pageno=<?php echo $i-1 ?>&filtername=<?php echo $FilterData; ?>"> <?php _e('Last','appointzilla'); ?></a></li>
                    </ul>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><button name="deleteall" class="btn btn-primary btn-small" type="submit" id="deleteall" onclick="return confirm('<?php _e('Do you want to delete selected clients?','appointzilla');?>')"><i class="icon-trash icon-white"></i>  <?php _e('Delete','appointzilla'); ?></button></td>
            </tr>
                <?php } else { ?>
            <tr class="alert"><td colspan="8"><strong> <?php _e('Sorry no client(s).','appointzilla'); ?></strong></td></tr>

                <?php } ?>
        </table>
    </form>

    <?php // Delete client
    if(isset($_GET['clientdid'])) {
        $deleteid = $_GET['clientdid'];
        $table_name = $wpdb->prefix . "ap_clients";
        $delete_app_query="DELETE FROM `$table_name` WHERE `id` = '$deleteid';";
        if($wpdb->query($delete_app_query)) {
            echo "<script>alert('".__('Client successfully deleted.','appointzilla')."');</script>";
            echo "<script>location.href='?page=client';</script>";
        }
    }

    // Delete all appointment with checkbox
    if(isset($_POST['deleteall'])) {
        $table_name = $wpdb->prefix . "ap_clients";
        for($i=0; $i<=count($_POST['checkbox'])-1; $i++) {
            $res = $_POST['checkbox'][$i];
            $deleteid = $res;
            $delete_app_query = "DELETE FROM `$table_name` WHERE `id` = '$deleteid';";
            $wpdb->query($delete_app_query);
        }
        if(count($_POST['checkbox'])) {
            echo "<script>alert('".__('Selected client(s) successfully deleted.','appointzilla')."');</script>";
        } else {
            echo "<script>alert('".__('No client(s) selected to delete.','appointzilla')."');</script>";
        }
        echo "<script>location.href='?page=client';</script>";
    } ?>
</div>

<!--validation js lib-->
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#checkbox').click(function() {
            if(jQuery('#checkbox').is(':checked')) {
                jQuery(":checkbox").prop("checked", true);
            } else {
                jQuery(":checkbox").prop("checked", false);
            }
        });
    });
</script>

<style type="text/css">
ul{border:0; margin:0; padding:0;}

#pagination-flickr li{
    border:0; margin:0; padding:0;
    font-size:11px;
    list-style:none;
}

#pagination-flickr a{
    border:solid 1px #C3D9FF;
    margin-right:5px;
}

#pagination-flickr .previous-off,
#pagination-flickr .next-off {
    color:#666666;
    display:block;
    float:left;
    font-weight:bold;
    padding:3px 4px;
}

#pagination-flickr .next a,
#pagination-flickr .previous a {
    font-weight:bold;
    border:solid 1px #FFFFFF;
}

#pagination-flickr .active{
    color:#ff0084;
    background:#C3D9FF;
    font-weight:bold;
    display:block;
    float:left;
    padding:4px 6px;
}

#pagination-flickr a:link,
#pagination-flickr a:visited {
    color:#0063e3;
    display:block;
    float:left;
    padding:3px 6px;
    text-decoration:none;
}

#pagination-flickr a:hover{
    border:solid 1px #666666;
}
</style>