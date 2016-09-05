<?php $this->load->view('admin/common/header.php'); ?>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script>
   history.pushState(null, null, 'dashboard');
    window.addEventListener('popstate', function(event) {
    history.pushState(null, null, 'dashboard');
    });
</script>-->
<script>
	$(document).ready(function() {

   $(".btn-group").css("display","none");
					
	$("#formAdminProfile").validate({
		rules: {
			username: "required",
			fullname: "required"/*,
			confirm_password:{
			    required: true,
				equalTo: "#passwords"
		    }		*/
			  },
		messages: {
			username: "Please provide a Username.",
			fullname: "Please provide a Full Name."/*,
			confirm_password: {
								required: "You must confirm your password",
								equalTo: "Your Passwords Must Match" // custom message for mismatched passwords
								}*/
		           }
		
		  });
		  
		  	$('#cancel').click(function(){
					  //alert( "Handler for .click() called." );
					  //window.location.href='<?php echo base_url('admin/dashboard'); ?>'
					  //window.formCore.submit();
					});
					
			$('#old_password').keyup(function(){
			
					//alert('aaaaa');
					var old_password = $('#old_password').val();
					$.ajax({
							type:'POST',
							url:'<?php echo base_url(); ?>admin/user/chkoldpassword',
							data:{old_password:old_password},
							dataType:'json',
							success:function(data){
							   
							    if(data==1){
										$('#passerrormsg').html('<font style="color:green;">Old password matched successfully !!</font>');
										$("#passwords").attr("readonly", false);
										$("#confirm_password").attr("readonly", false);
								}else{
										$('#passerrormsg').html('<font style="color:red;">Please enter your proper old password !!</font>');
										$("#passwords").attr("readonly", true);
										$("#confirm_password").attr("readonly", true);
										//$('#old_password').val('');
										//$('#old_password').focus();
								}
										
							   //$("#example1_wrapper").block({message:"Active Status Successfully Changed."});                  
							   //setTimeout(function(){ $("#example1_wrapper").unblock({message:"Active Status Successfully Changed."}) }, 1000);
								
							}
     				});
			})
					
	
	////////////////////// FILE UPLOAD JAVASCRIPT START ///////////////////////////////	
				
	
	
	////////////////////// FILE UPLOAD JAVASCRIPT END ///////////////////////////////		
	
	});
	
	function validatePassword(){
				
				var newpassword = $('#passwords').val();
				var confirm_password = $('#confirm_password').val();
				
				if(newpassword!=confirm_password){
						
						$('#confpasserrormsg').html('<font style="color:red;">Password didnt match !!</font>');
						return false;
						
				}
	}
	
		/*$(function() {
		
				$('#upload').on('click',function(e) {	
				//alert($('#username').val());
				   var profile_id = $('#profile_id').val();
				  e.preventDefault();
        		  $.ajaxFileUpload({
					url             :'<?php echo base_url(); ?>admin/user/upload_file?profile_id='+profile_id,
					secureuri       :false,
					fileElementId   :'image',
					dataType        : 'json',
					data            : {
						'username'    : $('#username').val()
					},
					success : function (data, status)
							{
								if(data.status != 'error')
								{
									$('#files').html('<p>Uploading image...</p>');
									
									
									var data1 = new Array();
									data1 = data.msg.split('*');
									
									refresh_files(data1[0]);
									$('#imagename').val(data1[1]);
									//alert($('#imagename').val());
									if($('#imagename').val()!=''){
										document.formAdminProfile.submit();
									}
									//$('#username').val('');
								}
								//alert(data.msg);
							 }
						});
				return false;
				 });
				 
				$('#fullname').on('keyup',function(e) { 
							var name = $('#fullname').val();
							var profile_id = $('#profile_id').val();
							$('#fullname1').html(name);
							$('#fullname2').html(name);
							$.ajax({
									type: 'POST',
									url: '<?php echo base_url(); ?>admin/user/update_admin_name',
									data: {full_name:name,profile_id:profile_id},
									beforeSend: function(){},
									success: function (data) {
									
									if(data=='success'){
										$("#name").html('Full Name updated successfully');
										//window.location='<?php echo base_url(); ?>admin/profile';
									}
									//console.log(data);
									//alert(data);return false;
									//$('#pageloaddiv').hide();
									//$("#showhistory").html(data);
									
									//$('#info1').html(data.msg);
									}
						 });
					//alert('alert');
				}) 
				
				$('#passwords').on('keyup',function(e) { 
							var passwords = $('#passwords').val();
							var profile_id = $('#profile_id').val();
							$.ajax({
									type: 'POST',
									url: '<?php echo base_url(); ?>admin/user/update_admin_password',
									data: {password:passwords,profile_id:profile_id},
									beforeSend: function(){},
									success: function (data) {
									
									if(data=='success'){
										$("#password").html('Password updated successfully');
										
									}
									//console.log(data);
									//alert(data);return false;
									//$('#pageloaddiv').hide();
									//$("#showhistory").html(data);
									
									//$('#info1').html(data.msg);
									}
						 });
					//alert('alert');
				}) 
	
		});*/
	
		function refresh_files(msg)
		{
			/*$.get('<?php echo base_url(); ?>uploads/')
			.success(function (data){*/
				$('#files').html(msg);
			/*});*/
		}

</script>
<style>
#frmLogin label.error {
	/*margin-left: 10px;*/
	width: auto;
	display: inline;
}
input.error {
    border: 1px dotted red;
}
form.frmLogin label.error, label.error {
    color: red;
    font-type: italic;
}
#files { font-family: Verdana, sans-serif; font-size: 11px; color:#993300}
#files strong { font-size: 13px; }
#files a { float: right; margin: 0 0 5px 10px; }
#files ul { list-style: none; padding-left: 0; }
#files li { width: 280px; font-size: 12px; padding: 5px 0; border-bottom: 1px solid #CCC; }

#name { font-family: Verdana, sans-serif; font-size: 11px; color:#993300}
#name strong { font-size: 13px; }
#name a { float: right; margin: 0 0 5px 10px; }
#name ul { list-style: none; padding-left: 0; }
#name li { width: 280px; font-size: 12px; padding: 5px 0; border-bottom: 1px solid #CCC; }

#password { font-family: Verdana, sans-serif; font-size: 11px; color:#993300}
#password strong { font-size: 13px; }
#password a { float: right; margin: 0 0 5px 10px; }
#password ul { list-style: none; padding-left: 0; }
#password li { width: 280px; font-size: 12px; padding: 5px 0; border-bottom: 1px solid #CCC; }
</style>
        <!-- header logo: type can be found in header.less -->
        
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
		   <h3 class="page-title">Admin Profile <small> Control Panel </small><!--<small>managed table samples</small>-->
			</h3>
			<div class="page-bar braf_shadow">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index-2.html">Home</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Dashboard</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="#">Admin Profile</a>
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
							<div class="caption">Update Admin Profile
							  <i class="fa fa-reorder"></i> 
								<?php if($this->session->flashdata('msg')!=''){?>	
									<div >
									<p style="color:#009900;font-style: italic;margin-top:16px; font-size:10px;" align="center"><strong><?php echo $this->session->flashdata('msg'); ?></strong></p>
									</div>
									<?php } ?>
							</div>
							<div class="tools">
								<a href="#" class="collapse"></a>
								<!--<a href="#portlet-config" data-toggle="modal" class="config"></a>
								<a href="#" class="reload"></a>
								<a href="#" class="remove"></a>-->
							</div>
						</div>
						<div class="portlet-body form">
							      <form name="formAdminProfile" id="formAdminProfile" method="post" action="<?php  echo base_url('admin/user/updateadminprofile') ?>" enctype="multipart/form-data" class="form-horizontal" onsubmit="return validatePassword()">
								<input type="hidden" name="profile_id" id="profile_id" value="<?php if(isset($admin_details['id'])){echo $admin_details['id']; }?>" />
                              <div class="form-body">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Username</label>
											<div class="col-md-4">
                                            <input type="text" placeholder="Enter username...." id="username" name="username" class="form-control" value="<?php if(isset($admin_details['username'])){echo $admin_details['username']; }?>" readonly>
                                           </div>
										</div>
                                </div><!-- /.box-body -->
									 <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Full Name</label>
											<div class="col-md-4" >
                                            <input type="text" ng-model="fullname" placeholder="Enter fullname...." id="fullname" name="fullname" class="form-control" value="<?php if(isset($admin_details['fullname'])){echo $admin_details['fullname']; }?>" ><div id="name"></div>
											</div>
                                        </div>
                                     </div>
									 <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Old Password</label>
											<div class="col-md-4">
                                            <input type="password" placeholder="Enter old password...." id="old_password" name="old_password" class="form-control" value="">
												</div>
											<span id="passerrormsg" style="float:right;padding-right:218px;margin-top:8px;"></span>
                                        </div>
									</div>
									 <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">New Password</label>
											<div class="col-md-4">
                                            <input type="password" placeholder="Enter new password...." id="passwords" name="passwords" class="form-control" value="" readonly><div id="password"></div>
											</div>
                                        </div>
                                     </div>
									 <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Confirm Password</label>
											<div class="col-md-4">
                                            <input type="password" placeholder="Enter confirm password...." id="confirm_password" name="confirm_password" class="form-control" value="" readonly><div id="password"></div>
											</div>
											<span id="confpasserrormsg" style="float:right;padding-right:296px;margin-top:8px;"></span>
                                        </div>
								    </div>
									<!--<div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Upload Profile Image</label>
											<div class="col-md-4">
                                            <input type="file"  id="image" name="image"  value="">
											<input type="hidden"  id="imagename" name="imagename"  value=""><span style="float:right; padding-left:120px;padding-right:715px;margin:-30px">
											<button class="btn btn-danger" type="" id='upload'>Upload</button></span><br /><div id="files"></div>
											</div>
                                        </div>
                                     </div>-->
									  <div class="form-group">
                                            <label for="exampleInputEmail1" class="col-md-3 control-label">Upload Admin Profile Logo</label>
                                            <div class="col-md-4">
												<div class="fileinput fileinput-new" data-provides="fileinput">
													<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px;height:150px;"> 
													
													<?php if(isset($admin_details['image']) && $admin_details['image']!=''){ ?>
													
													<img alt="" src="<?php echo base_url('') ?>uploads/admin/<?php echo $admin_details['image']; ?>">
													<?php }else{ ?>
													<img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image">
													<?php } ?>
													</div>
													<div>
													<span class="btn btn-default btn-file btn btn-info">
													<span class="btn btn-info" style="padding:0;"> Select Image </span>
													<span class="fileinput-exists"> Change </span>
													<input type="file"  id="image" name="image"  value="">
													<!--<input type="hidden"  id="imagename" name="imagename"  value="">--><!--<span style="float:right; padding-left:120px;padding-right:715px;margin:-30px">
													</span>-->
													</span>
													</div>
													</div>
                                            </div>
                                          </div>
										<div class="form-group">
                                         <label for="exampleInputEmail1" class="col-md-3 control-label">&nbsp;</label>
                                         <div class="col-md-4">
										<!--<button class="btn btn-success" type="" id='upload'>Upload</button>-->
                                        </div>
										</div>
										<div class="form-group">
										<div class="col-md-4">
                                         <div id="files" align="center"></div>
										 </div>
										</div>
									</div>
                                    <div class="form-actions">
                                       <button class="btn btn-success" type="submit">Update<?php  //if(isset($userlist['id'])){echo 'Update';}else{echo 'Submit';} ?></button>&nbsp;
										 <button class="btn btn-danger"  type="submit" id="cancel">Cancel</button>
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