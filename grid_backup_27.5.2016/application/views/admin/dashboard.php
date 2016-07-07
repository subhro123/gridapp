<?php $this->load->view('admin/common/header.php'); ?>
	<script>
			   history.pushState(null, null, '');
				window.addEventListener('popstate', function(event) {
				history.pushState(null, null, '');
				});
			</script>
	<div class="clearfix" style="height:40px;"> </div>
        <!-- header logo: question can be found in header.less -->
        
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php $this->load->view('admin/common/leftmenu.php'); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
             

                <!-- Main content -->
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
			
			<!-- END BEGIN STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			Dashboard</h3>
			<div class="page-bar braf_shadow">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo base_url(); ?>admin/dashboard">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Dashboard</a>
					</li>
				</ul>
				
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN OVERVIEW STATISTIC BARS-->
			<div class="row">
			<div class="col-md-3 col-sm-6">
					
				</div>
				<div class="col-md-3 col-sm-6">
					
				</div>
				<!--<div class="col-md-12">-->
					 <!--<h2>Welcome To Admin Panel</h2>-->
								 <div style=" width:100%; text-align:center; padding:30px 0;"><a href="http://192.168.0.1/leadership/"><!--<img  alt="logo" src="<?php echo base_url(); ?>public/images/logo.png">--></a></div>
								 
				<!--</div>-->
			</div>
			<!-- END OVERVIEW STATISTIC BARS-->
			<div class="clearfix">
			
			</div>
			
			<div class="clearfix">
			</div>
			
			<div class="clearfix">
			</div>
			
			<div class="clearfix">
			</div>
			<!-- BEGIN OVERVIEW STATISTIC BLOCKS-->
			
			<!-- END OVERVIEW STATISTIC BLOCKS-->
			<div class="clearfix">
			</div>
			
		</div>
	</div><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- add new calendar event modal -->


            


<?php $this->load->view('admin/common/footer.php'); ?>
