<?php $this->load->view('admin/common/header.php'); ?>
<script>
	$(document).ready(function() {
	
	/*$("#formInterest").validate({
		rules: {
			name: "required",
			short_description :  "required",
			dob: "required",
			location: "required",
			occupation: "required",
			sponsors: "required",
			
			  },
		messages: {
		    name : "Please provide a athlete name",
			short_description : "Please provide some brief",
			dob: "Please select your date of birth",
			location: "Please provide your location",
			occupation: "Please provide your occupation",
			sponsors: "Please provide your Sponsors",
		         },
		
		
		  });*/
		  $('#free').click(function(){
		 		//alert('aaaaaaa'); 
				$('#mindiv').show()
				$('#maxdiv').show()
		  })
		  $('#paid').click(function(){
		 		//alert($('#opt_interest_id').val()); 
				$('#mindiv').show()
				$('#maxdiv').show()
		  })
		  	$('#cancel').click(function(){
					  //alert( "Handler for .click() called." );
					  	window.location.href='<?php echo base_url('admin/radius'); ?>'
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
			Radius <small>Control Panel </small><!--<small>managed table samples</small>-->
			</h3>
			<div class="page-bar braf_shadow">
				<ul class="page-breadcrumb">
					<li>

						<i class="fa fa-home"></i>
						<a href="index-2.html">Home</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Radius</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Edit Radius</a>
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
							<div class="caption">Edit Radius
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
							       <form name="formInterest" class="form-horizontal" id="formInterest" method="post" action="<?php  echo base_url('admin/radius/editradius'); ?>/<?php echo $id; ?>"  onsubmit="return validate()" >
								   	<div class="form-body">
									<div class="form-group">
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Select  User Type</label>
                                           	 	<div class="col-md-4">
													<div class="radio-list">
														<label class="radio-inline">
															<input type="radio" name="user_type" value="free" id="free" <?php echo ($editradius['user_type']=='free')?'checked="checked"': '' ?>>&nbsp;Free
														</label>
														<label class="radio-inline">
															<input type="radio" name="user_type" value="paid"  id="paid" <?php echo ($editradius['user_type']=='paid')?'checked="checked"': '' ?>>&nbsp;Paid
														</label>
											   		</div>
												</div>
                                            
                                      </div>
									  <div class="form-group" style="" id="mindiv">
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Edit min value</label>
                                            <div class="col-md-4">
											<input type="text" name="min" id="min"  class="form-control"  value="<?php if(isset($editradius['min'])){ echo $editradius['min']; }?>"/>
											<!--<input type="text" name="subinterest_name" id="subinterest_name"  class="form-control"  value=""/>-->
                                            </div>
                                       </div>
									   <div class="form-group" style="" id="maxdiv">
                                            <label class="col-md-2 control-label" for="exampleInputEmail1">Edit max value</label>
                                            <div class="col-md-4">
											<input type="text" name="max" id="max"  class="form-control"  value="<?php if(isset($editradius['max'])){ echo $editradius['max']; }?>"/>
                                            </div>
                                       </div>
									   <div class="form-group" >
                                            <label class="col-md-2 control-label" for="exampleInputEmail1"></label>
                                            <div class="col-md-4">
												<?php if($this->session->flashdata('errormsg')!=''){?>	
													<div >
													<p style="color:#990000;font-style: italic;margin-top:16px; font-size:10px;" align="left"><strong><?php echo $this->session->flashdata('errormsg'); ?></strong></p>
													</div>
											<?php } ?> 
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



