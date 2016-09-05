<?php $this->load->view('admin/common/header.php'); ?>
<script>
	$(document).ready(function() {
	
	$("#formAd").validate({
		rules: {
			state_id: "required",
			text :  "required",
			charging_model: "required",
			amount: "required",
			start_date: "required",
			end_date: "required",
			
			  },
		messages: {
		    state_id  : "Please select your State ",
			text : "Please provide some Text",
			charging_model: "Please select Charging Model",
			amount: "Please add some amount",
			start_date: "Please add a Start Date",
			end_date: "Please add a End Date",
		         },
		
		
		  });
		  
		  $("#start_date").datepicker({
						format: 'yyyy-mm-dd',
						autoclose: true,
					}).on('changeDate', function (selected) {
						var startDate = new Date(selected.date.valueOf());
						$('#end_date').datepicker('setstart_date', startDate);
					}).on('clearDate', function (selected) {
						$('#end_date').datepicker('setstart_date', null);
					});
			
					$("#end_date").datepicker({
						format: 'yyyy-mm-dd',
						autoclose: true,
					}).on('changeDate', function (selected) {
						var endDate = new Date(selected.date.valueOf());
						$('#start_date').datepicker('setend_date', endDate);
					}).on('clearDate', function (selected) {
						$('#start_date').datepicker('setend_date', null);
			});		
		  
		  	$('#cancel').click(function(){
					  //alert( "Handler for .click() called." );
					  	window.location.href='<?php echo base_url('admin/admanage'); ?>'
					  //window.formCore.submit();
					});
					
		 
	});
</script>
<style>
#formAthlete label.error {
	/*margin-left: 10px;*/
	width: auto;
	display: inline;
}
input.error {
    border: 1px dotted red;
}
form.formAthlete label.error, label.error {
    color: red;
    font-question: italic;
}
</style>
        <!-- header logo: question can be found in header.less -->
        
<div class="page-container">
<?php $this->load->view('admin/common/leftmenu.php'); ?>
<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success">Save changes</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			<div class="theme-panel hidden-xs hidden-sm">
				<!--<div class="toggler">
					<i class="fa fa-gear"></i>
				</div>-->
				<div class="theme-options">
					<div class="theme-option theme-colors clearfix">
						<span>
						Theme Color </span>
						<ul>
							<li class="color-black current color-default tooltips" data-style="default" data-original-title="Default">
							</li>
							<li class="color-grey tooltips" data-style="grey" data-original-title="Grey">
							</li>
							<li class="color-blue tooltips" data-style="blue" data-original-title="Blue">
							</li>
							<li class="color-red tooltips" data-style="red" data-original-title="Red">
							</li>
							<li class="color-light tooltips" data-style="light" data-original-title="Light">
							</li>
						</ul>
					</div>
					<div class="theme-option">
						<span>
						Layout </span>
						<select class="layout-option form-control input-small">
							<option value="fluid" selected="selected">Fluid</option>
							<option value="boxed">Boxed</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Header </span>
						<select class="header-option form-control input-small">
							<option value="fixed" selected="selected">Fixed</option>
							<option value="default">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar </span>
						<select class="sidebar-option form-control input-small">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar Position </span>
						<select class="sidebar-pos-option form-control input-small">
							<option value="left" selected="selected">Left</option>
							<option value="right">Right</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Footer </span>
						<select class="footer-option form-control input-small">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
				</div>
			</div>
			<!-- END BEGIN STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
		   <h3 class="page-title">
			Ads <small>Control Panel </small><!--<small>managed table samples</small>-->
			</h3>
			<div class="page-bar braf_shadow">
				<ul class="page-breadcrumb">
					<li>

						<i class="fa fa-home"></i>
						<a href="index-2.html">Home</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Ad Manage</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Create Ads</a>
						<!--<i class="fa fa-angle-right"></i>-->					</li>
				</ul>
				<div class="page-toolbar">
					<!--<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						Actions <i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="#">Action</a>
							</li>
							<li>
								<a href="#">Another action</a>
							</li>
							<li>
								<a href="#">Something else here</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#">Separated link</a>
							</li>
						</ul>
					</div>-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 ">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">Add Ads
								<i class="fa fa-reorder"></i>
                               </div>
							<div class="tools">
								<a href="#" class="collapse"></a>
								<!--<a href="#portlet-config" data-toggle="modal" class="config"></a>
								<a href="#" class="reload"></a>
								<a href="#" class="remove"></a>-->
							</div>
						</div>
						<div class="portlet-body form">
							       <form name="formAd" class="form-horizontal" id="formAd" method="post" action="<?php  echo base_url('admin/admanage/addadmanage'); ?>"  enctype="multipart/form-data">
								   	<div class="form-body">
									<div class="form-group">
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Country</label>
                                           	 	<div class="col-md-4">
													<div class="radio-list">
														<label class="radio-inline">
															<input type="text" name="country" id="country"  class="form-control"  value="USA" disabled="disabled"/>
														</label>
												   </div>
											</div>
                                            
                                      </div>
									  <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add State</label>
                                            <div class="col-md-4">
											<select class="form-control"  id="state_id" name="state_id">
											<option value="">--Select State--</option>
											<?php foreach($state as $key=>$val) { ?>
											<option value="<?php echo $val['code']; ?>"><?php echo $val['name']; ?></option>
											<?php } ?>
											
											</select>
											<!--<input type="text" name="min" id="min"  class="form-control"  value=""/>-->
											<!--<input type="text" name="subinterest_name" id="subinterest_name"  class="form-control"  value=""/>-->
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Ad Text</label>
                                            <div class="col-md-4">
											<input type="text" name="text" id="text"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Select Charging Model</label>
                                            <div class="col-md-4">
											<select class="form-control"  name="charging_model" id="charging_model" >
											<option value="">--Select Charging Model--</option>
											<option value="free">Free</option>
											<option value="paid">Paid</option>
											</select>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Amount</label>
                                            <div class="col-md-4">
											<input type="text" name="amount" id="amount"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Image</label>
                                            <div class="col-md-4">
											<span class="btn btn-default btn-file btn btn-info">
													<span class="btn btn-info" style="padding:0;"> Select image </span>
													<span class="fileinput-exists"> <!--Change--> </span>
													<input type="file"  id="image" name="image"  value="">
													<!--<input type="hidden"  id="imagename" name="imagename"  value="">--><!--<span style="float:right; padding-left:120px;padding-right:715px;margin:-30px">
													</span>-->
													</span>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Start Date</label>
                                            <div class="col-md-4">
											<input type="text" name="start_date" id="start_date"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add End Date</label>
                                            <div class="col-md-4">
											<input type="text" name="end_date" id="end_date"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Link</label>
                                            <div class="col-md-4">
											<input type="text" name="link" id="link"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Client</label>
                                            <div class="col-md-4">
											<input type="text" name="client" id="client"  class="form-control"  value=""/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Add Place</label>
                                            <div class="col-md-4">
											<input type="text" name="place" id="place"  class="form-control"  value=""/>
                                            </div>
                                       </div>
                                       </div>
									 
									<div class="form-actions">
									<button type="submit" class="btn btn-success" id="submit">Submit</button>&nbsp;
									<button type="button" class="btn btn-danger" id="cancel">Cancel</button>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->
				
				</div>
				
				
			</div>
			
			<!-- END PAGE CONTENT-->
		</div>
	</div>
</div>

            


<?php $this->load->view('admin/common/footer.php'); ?>


