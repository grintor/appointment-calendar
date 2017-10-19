<?php
define('path',plugin_dir_url( __FILE__ ));
$nonce = wp_create_nonce("my_user_vote_nonce");

	global $wpdb;

    $service_table_name = $wpdb->prefix . "ap_services";
    $all_services = $wpdb->get_results("SELECT `id`, `name` FROM `$service_table_name`");
	foreach($all_services as $service)
	{
		$data_service[] = array('id'=>'service-'.$service->id,'label'=>$service->name);
	}
	$all_services = json_encode($data_service);
	
    $staff_table_name = $wpdb->prefix . "ap_staff";
    $all_staffs = $wpdb->get_results("SELECT `id`, `name` FROM `$staff_table_name`");
	foreach($all_staffs as $staff)
	{
		$data_staff[] = array('id'=>'staff-'.$staff->id,'label'=>$staff->name);
	}
	$all_staffs = json_encode($data_staff);
?>
<div style="background:#C3D9FF; margin:10px 0; padding:5px 10px;">
    <h3 style="margin-top:10px;"><i class="fa fa-edit"></i> <?php _e('Manage Appointments','appointzilla'); ?></h3>
</div>

<div class="demo-bootstrap-table">

		<div id="filter-bar">
        </div>
		<form action="" method="post" name="manage-appointments">
        <table
           id="tbl"
           data-search="true"
		   data-show-refresh="true"
		   data-detail-formatter="detailFormatter"
		   data-minimum-count-columns="2"
		   data-pagination="true"
		   data-id-field="id"
		   data-side-pagination="server"
		   data-sort-order="desc"
		   data-url="<?php echo admin_url('admin-ajax.php?action=manage_appointments&nonce='.$nonce); ?>"
		   data-page-number="1"
		   data-page-size="20"
		   data-page-list="[10, 20, 50, 100, ALL]"
		   data-show-footer="false"
		   data-filter-control="true"
            >
            <thead>
                <tr>
                    <th data-field="id" data-align="center">S. No.</th>
                    <th data-field="name" data-align="left" data-sortable="true">Name</th>
					<th data-field="staff_id" data-align="left" data-sortable="true">Staff</th>
                    <th data-field="date" data-align="center" data-sortable="true">Date</th>
					<th data-field="time" data-align="center">Time</th>
					<th data-field="service_id" data-align="center" data-sortable="true">Service</th>
					<th data-field="recurring_type" data-align="center">Repeat</th>
					<th data-field="status" data-align="center" data-sortable="true">Status</th>
					<th data-field="payment_status" data-align="center" data-sortable="true">Payment</th>
					<th data-field="action"  data-align="center">Action</th>
					<th data-field="state" data-checkbox="true"></th>
                </tr>
            </thead>
        </table>
		
		<div class="row" style="margin:0;">
			<a id="deleteall" class="btn btn-primary btn-small"><i class="fa fa-trash-o"></i></a>
		</div>
		</form>
		
        <script type="text/javascript">
            jQuery(document).ready(function($){
				var services = <?php echo $all_services; ?>;
				var staff = <?php echo $all_staffs; ?>;
				var tbl =$('#tbl');
				tbl.bootstrapTable();
				$('#filter-bar').bootstrapTableFilter({
					connectTo: '#tbl',
					filters:[
					{
							field: 'date',    // field identifier
							label: 'Date',    // filter label
							type:'select',
							values: [
									{id: 'today', label: 'Today`s  Appointments'}
								]
						},
						{
							field: 'status',    // field identifier
							label: 'Status',    // filter label
							type:'select',
							values: [
									{id: 'pending', label: 'Pending Appointments'},
									{id: 'approved', label: 'Approved Appointments'},
									{id: 'cancelled', label: 'Cancelled Appointments'},
									{id: 'done', label: 'Done Appointments'}
								]
						},
						{
							field: 'payment_status',    // field identifier
							label: 'Filter Appointments By Payment',    // filter label
							type:'select',
							values: [
									{id: 'paid', label: 'Paid Appointments'},
									{id: 'unpaid', label: 'Unpaid Appointments'}
								]
						},
						{
							field: 'service_id',    // field identifier
							label: 'Filter Appointments By Service',    // filter label
							type:'select',
							values: (typeof services != 'undefined' )?services:''
						},
						{
							field: 'staff_id',    // field identifier
							label: 'Filter Appointments By Staff',    // filter label
							type:'select',
							values: (typeof staff != 'undefined' )?staff:''
						}
					],
					onSubmit: function(data) {
						    var data = $('#filter-bar').bootstrapTableFilter('getData');
					},
					onAll: function() {

					}
				});
				
					
				var set_editable = function () {
					var source = [{'id': '1', 'text': 'pending'},{'id': '2', 'text': 'approved'},{'id': '3', 'text': 'cancelled'},{'id': '4', 'text': 'done'}];
					$("a.change_status").each(function (index) {
						$(this).editable({
							type: 'select',
							url:"<?php echo admin_url('admin-ajax.php?action=appointments_with_status'); ?>",
							source:source,
							success: function (response, newValue) {						
								var id = $(this).data('pk');
								var update_row='<a href="javascript:void(0)" data-pk="'+id+'" data-title="Select status" data-name="status" data-value="'+newValue+'" class="change_status editable editable-click">'+newValue+'</a>';
							
								tbl.bootstrapTable('updateRow', {index: index, row: {'status' : update_row }});
							}
						});
					});
				};
				set_editable();

				$('#tbl').on('all.bs.table', function (e, name, args) {
					set_editable();
				});
				
				$("#deleteall").on('click',function(){
					
					var check = confirm('Do you want to delete these appointments?');
					
					if(check==true)
					{
						var index = [];
						$('input[name="btSelectItem"]:checked').each(function (e,element) {
							index.push($(this).attr("value"));
						});
						var ids = index.join(',');
						
						if(ids.length < 1)
						{
							alert("Oops! No Appointments Selected.");
							return false;
						}
						
						jQuery.ajax({
							type     : "POST",
							cache    : false,
							url      : "<?php echo admin_url('admin-ajax.php?action=appoints_deleteall&delete_all='); ?>"+ids,
							success  : function(data) {
								 if(data=='success')
								 {
									alert("Appointment successfully deleted.");
									location.href="<?php echo admin_url('admin.php?page=manage-appointments'); ?>";
								 }
							},
							error : function(response){
								console.log(response);
							}
						});
					}
					else{
						return false;
					}
					
				});
				
				
			});
        </script>

</div>
<style>
.search{
	background-color:transparent !important;
}
.search input{
	height:auto !important;
	margin-bottom:0 !important;
}
.fa{
	padding:5px;
}
#deleteall{
	float:right;
	margin:5px 20px;
}
.pagination li a{
	border-left-width:1px !important;
}
</style>