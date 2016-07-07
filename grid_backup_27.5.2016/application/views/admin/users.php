<?php $this->load->view('admin/common/header.php'); ?>
<script question="text/javascript">
           /* $(function() {
                $("#example1").dataTable();
                
            });*/
			$(document).ready(function() {
			
				var extensions = {
					"sLengthSelect": "form-control"
				}
				// Used when bJQueryUI is false
				$.extend($.fn.dataTableExt.oStdClasses, extensions);
				// Used when bJQueryUI is true
				$.extend($.fn.dataTableExt.oJUIClasses, extensions);
				$('#example1').dataTable();
				
				$('#sub').click(function(){
					  //alert( "Handler for .click() called." );
					//  window.location="<?php //echo base_url('admin/addraters'); ?>"
					});
					
					var oTable=$('#sample_2').dataTable( {
					//"processing": true,
					"bDestroy": true,
					"bServerSide": true,
					"bProcessing": true,                            
            		"bScrollInfinite": true, //using this takes away ddl of selection   
					'bPaginate': true,
				    "bDeferRender": true,
					"sAjaxSource": "<?php echo base_url('admin/users/pagingusers/'); ?>",
					"language": {            
                        //"sSearch": "",
						"search": '<div style="position: relative;"><span class="glyphicon glyphicon-search" style="left: 10px;  position: absolute;   top: 10px;z-index: 999;"></span></div>',
                        "sLoadingRecords": "Please wait - loading...",
                        "sProcessing": "<img src='<?php echo base_url(''); ?>public/images/loader.gif'/>"
                                                 },
					"sServerMethod": "POST",
					"iDisplayLength": 10,
					"bLengthChange": false,
					"bScrollCollapse": true,
					"fnInitComplete": function() {
						 $(".ajax", oTable.fnGetNodes()).colorbox({width:"760", height:"440", iframe:true});
					 },
					"aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,2,3,4,6 ] } ],
					
					
				   			 });
							 
					
					
					$('.dataTables_filter input[type="search"]').removeClass('input-small');	
					$('.dataTables_filter input[type="search"]').addClass('input-large');
					$('.dataTables_filter input[type="search"]').css('padding-left','25px');
					$('.dataTables_filter input[type="search"]').css('background','#0681B0');
					$(".btn-group").css("display","none");
					
					//$("#sample_2_length").empty('<label> <div class="select2-container form-control input-xsmall input-inline" id="s2id_autogen1"><a tabindex="-1" class="select2-choice" href="javascript:void(0)">   <span class="select2-chosen" id="select2-chosen-2">5</span><abbr class="select2-search-choice-close"></abbr>   <span role="presentation" class="select2-arrow"><b role="presentation"></b></span></a><label class="select2-offscreen" for="s2id_autogen2"></label><input type="text" role="button" aria-haspopup="true" class="select2-focusser select2-offscreen" aria-labelledby="select2-chosen-2" id="s2id_autogen2"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label class="select2-offscreen" for="s2id_autogen2_search"></label>       <input type="text" aria-autocomplete="list" aria-expanded="true" role="combobox" class="select2-input" spellcheck="false" autocapitalize="off" autocorrect="off" autocomplete="off" aria-owns="select2-results-2" id="s2id_autogen2_search" placeholder="">   </div>   <ul role="listbox" class="select2-results" id="select2-results-2">   </ul></div></div><select name="sample_2_length" aria-controls="sample_2" class="form-control input-xsmall input-inline select2-offscreen" tabindex="-1" title=""><option value="5">5</option><option value="15">15</option><option value="20">20</option><option value="-1">All</option></select> records</label>');
					//$("#sample_2_wrapper .col-md-6").first().html('<button class="btn btn-success" id="sample_editable_1_new" >Add Raters <i class="fa fa-plus"></i></button>');
					
					if($('#add').val()==0){
					//$("#sample_2_wrapper .col-md-6").first().html('<button class="btn btn-success" id="sample_editable_1_new" >Add Interest <i class="fa fa-plus"></i></button>');
					}else{
					$("#sample_2_wrapper .col-md-6").first().html('');
					}

				    //$("#sample_2_wrapper .row").css('margin-left', '0');
					//$("#sample_2_wrapper .row").css('margin-right', '0');
					$("#sample_2_wrapper .row").addClass('gradient55');
					$('#sample_editable_1_new').click(function(){
								
										//alert('aaaaaaa');
										window.location="<?php echo base_url('admin/interest/addinterest'); ?>"
										
								});
		/*	table.on( 'order.dt search.dt', function () {
       						table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            				cell.innerHTML = i+1+' .';
        } );
    } ).draw();	*/	

});

function updateStatus(id){
		
	var val = "0";	
	if($("#active"+id).prop("checked") == true)
	{
		val = "1";
	}
	$("#example1_wrapper").block({message:null});
	$.ajax({
                type:'POST',
                url:'<?php echo base_url(); ?>admin/users/isactivecheck',
		        data:{id:id,val:val},
                dataType:'json',
                success:function(data){
                   
                   $("#example1_wrapper").block({message:"Active Status Successfully Changed."});                  
                   setTimeout(function(){ $("#example1_wrapper").unblock({message:"Active Status Successfully Changed."}) }, 1000);
                    
                }
     });
}
        
        </script>
        <!-- header logo: question can be found in header.less -->
        <style>
		td.details-control {
			background: url('../resources/details_open.png') no-repeat center center;
			cursor: pointer;
		}
		tr.shown td.details-control {
			background: url('../resources/details_close.png') no-repeat center center;
		}
		</style>
      <div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		 <?php $this->load->view('admin/common/leftmenu.php'); ?>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
	<div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">
		<div class="page-content" style="box-shadow:#666 0 2px 5px inset;">
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
			Users <small>Control Panel </small><!--<small>managed table samples</small>-->
			</h3>
			<div class="page-bar braf_shadow">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo base_url(); ?>admin/dashboard">Home</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin/users">Users</a>
						<i class="fa fa-angle-right"></i>					</li>
					<li>

						<a href="#">List Users </a>
						<!--<i class="fa fa-angle-right"></i>-->					</li>
				</ul>
				<div class="page-toolbar">
					<div class="btn-group pull-right">
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
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-globe"></i>List Users 
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<!--<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>-->
							</div>
						</div>
                        
                        
                        
                        
						<div class="portlet-body">
							<div class="table-toolbar">
							<div class="clr"><?php if($this->session->flashdata('msg')!=''){?>	
									<div >
									<p style="color:#336600;font-style: italic;" align="center"><strong><?php echo $this->session->flashdata('msg'); ?></strong></p>
									</div>
									<?php } ?>
									
							</div>
								<!--<div class="row">
                                	
									<div class="col-md-6">
                                    
										<div class="btn-group">
											<button id="sample_editable_1_new" class="btn btn-success">
											Add News <i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
									<div class="col-md-6">
                                    
                                    
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#">
													Print </a>
												</li>
												<li>
													<a href="#">
													Save as PDF </a>
												</li>
												<li>
													<a href="#">
													Export to Excel </a>
												</li>
											</ul>
										</div>
									</div>
                                   
								</div>-->
							</div>
							<input type="hidden" id="add" name="add" value="<?php if(isset($accessrights['add']) && $accessrights['add']=='1'){ echo $val='1';}else{echo $val='0';} ?>" />
							<table id="sample_2" class="table table-bordered table-striped gradient_row" width="100%">
                                     <thead>
                                          <tr>
                                            <td width="3%"><strong>ID</strong></td>
                                            <td width="27%" ><strong>Full name</strong></td>
										    <td width="30%" ><strong>Email</strong></td>
											<td width="11%" align="center"><strong>Login Type</strong></td>
                                            <td width="10%" align="center"><strong>Active</strong></td>
											<td width="9%"  align="center" ><strong>Date</strong></td>
                                            <td width="10%" align="center"><strong>Action</strong></td>
                                          </tr>
                                      </thead>
                                    <tbody>
										
                                          </tbody>
                                        <!--<tfoot>
                                             <tr>
                                                <td><strong>ID</strong></td>
                                                <td width="25%" ><strong>Athlete name</strong></td>
                                                <td width="51%" ><strong>Description</strong></td>
												<td width="12%"  align="center" ><strong>Date</strong></td>
                                                <td width="10%" align="center"><strong>Action</strong></td>
                                            </tr>
                                        </tfoot>-->
                                    </table>
							
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			
			<!-- END PAGE CONTENT-->
		</div>
		</div>
	</div>
	<!-- END CONTENT -->
</div>

            


<?php $this->load->view('admin/common/footer.php'); ?>
