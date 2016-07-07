<!DOCTYPE html>
<!-- 
Template Name: Conquer - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 2.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/conquer-responsive-admin-dashboard-template/3716838?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<!-- Mirrored from www.keenthemes.com/preview/conquer/login.html by HTTrack Website Copier/3.x [XR&CO'2013], Fri, 17 Oct 2014 12:39:00 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
<meta charset="utf-8"/>
<title>Grid App | <?php echo ucfirst($this->uri->segment(2)); ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="MobileOptimized" content="320">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<?php if($this->uri->segment(2) ==''){ ?>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo base_url(); ?>public/assets/css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo base_url(); ?>public/assets/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>public/assets/css/custom.css" rel="stylesheet" type="text/css"/>

<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,400italic,300italic' rel='stylesheet' type='text/css'>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.html"/>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
<!--<script>
   history.pushState(null, null, '');
    window.addEventListener('popstate', function(event) {
    history.pushState(null, null, 'dashboard');
    });
</script>-->
</head>
<?php }else{
?>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
			<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css"/>
			<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/css/easy-responsive-tabs.css" />
			<link href="<?php echo base_url(); ?>public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
			<!-- END GLOBAL MANDATORY STYLES -->
			<!-- BEGIN PAGE LEVEL STYLES -->
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/select2/select2.css"/>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
			<!-- END PAGE LEVEL STYLES -->
			<!-- BEGIN THEME STYLES -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/colorbox.css" />
			<link href="<?php echo base_url(); ?>public/assets/css/style-conquer.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/css/style.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/css/plugins.css" rel="stylesheet" type="text/css"/>
			<link href="<?php echo base_url(); ?>public/assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
			<link href="<?php echo base_url(); ?>public/assets/css/custom.css" rel="stylesheet" type="text/css"/>
			<!-- END THEME STYLES -->
			<link rel="shortcut icon" href="favicon.html"/>
		<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
			<!-- BEGIN CORE PLUGINS -->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
			<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
			<script src="<?php echo base_url(); ?>public/assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
			<script src="<?php echo base_url(); ?>public/assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/jquery.dataTables.min.js"></script>
			<!-- END CORE PLUGINS -->
			<!-- BEGIN PAGE LEVEL PLUGINS -->
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/select2/select2.min.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
			<script src="<?php echo base_url(); ?>public/js/easyResponsiveTabs.js" type="text/javascript"></script>
			<script src='<?php echo base_url(); ?>public/js/jquery.validate.js'></script>
			<script src="<?php echo base_url(); ?>public/js/jquery.colorbox.js"></script>
			<script src="<?php echo base_url(); ?>public/js/admin/ajaxfileupload.js"></script>
			<script src="<?php echo base_url(); ?>public/js/jquery.blockUI.js"></script>
			<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
            
			<!-- END PAGE LEVEL PLUGINS -->
			<!-- BEGIN PAGE LEVEL SCRIPTS -->
			<script src="<?php echo base_url(); ?>public/assets/scripts/app.js"></script>
			<script src="<?php echo base_url(); ?>public/assets/scripts/table-managed.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>ckeditor/ckeditor.js"></script>
		
					<script>
						jQuery(document).ready(function() {       
							App.init();
							TableManaged.init();
						});
			 		</script>
	  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
       <!-- <script src="<?php //echo base_url(); ?>public/js/admin/AdminLTE/dashboard.js" type="text/javascript"></script> -->
		

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
<!-- BEGIN HEADER -->
<body class="page-header-fixed" >
			<div class="header navbar  navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->
	<div class="page-logo">
            <a href="javascript:void(0)" style="text-decoration:none;">
            	<h2 style="margin-top:7px; color:#88cae2;"><strong style="color:#0681B0;">Grid App</strong></h2>
                <!--<img src="assets/img/logo.png" alt="logo"/>-->
            </a>
        </div>
        <!--<form class="search-form search-form-header" role="form" action="http://www.keenthemes.com/preview/conquer/index.html">
            <div class="input-icon right">
                <i class="icon-magnifier"></i>
                <input type="text" class="form-control input-sm" name="query" placeholder="Search...">
            </div>
        </form>-->
	<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<img src="<?php echo base_url();?>public/assets/img/menu-toggler.png" alt=""/>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			<!-- BEGIN NOTIFICATION DROPDOWN -->
			<!--<li class="dropdown" id="header_notification_bar">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<i class="icon-bell"></i>
				<span class="badge badge-success">
				6 </span>
				</a>
				<ul class="dropdown-menu extended notification">
					<li>
						<p>
							 You have 14 new notifications
						</p>
					</li>
					<li>
						<ul class="dropdown-menu-list scroller" style="height: 250px;">
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-success">
								<i class="fa fa-plus"></i>
								</span>
								New user registered. <span class="time">
								Just now </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Server #12 overloaded. <span class="time">
								15 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-warning">
								<i class="fa fa-bell"></i>
								</span>
								Server #2 not responding. <span class="time">
								22 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-info">
								<i class="fa fa-bullhorn"></i>
								</span>
								Application error. <span class="time">
								40 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Database overloaded 68%. <span class="time">
								2 hrs </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								2 user IP blocked. <span class="time">
								5 hrs </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-warning">
								<i class="fa fa-bell"></i>
								</span>
								Storage Server #4 not responding. <span class="time">
								45 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-info">
								<i class="fa fa-bullhorn"></i>
								</span>
								System Error. <span class="time">
								55 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Database overloaded 68%. <span class="time">
								2 hrs </span>
								</a>
							</li>
						</ul>
					</li>
					<li class="external">
						<a href="#">See all notifications <i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
			</li>-->
			<!-- END NOTIFICATION DROPDOWN -->
			<!-- BEGIN INBOX DROPDOWN -->
			<!--<li class="dropdown" id="header_inbox_bar">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<i class="icon-envelope-open"></i>
				<span class="badge badge-info">
				5 </span>
				</a>
				<ul class="dropdown-menu extended inbox">
					<li>
						<p>
							 You have 12 new messages
						</p>
					</li>
					<li>
						<ul class="dropdown-menu-list scroller" style="height: 250px;">
							<li>
								<a href="inbox14c8.html?a=view">
								<span class="photo">
								<img src="assets/img/avatar2.jpg" alt=""/>
								</span>
								<span class="subject">
								<span class="from">
								Lisa Wong </span>
								<span class="time">
								Just Now </span>
								</span>
								<span class="message">
								Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
								</a>
							</li>
							<li>
								<a href="inbox14c8.html?a=view">
								<span class="photo">
								<img src="assets/img/avatar3.jpg" alt=""/>
								</span>
								<span class="subject">
								<span class="from">
								Richard Doe </span>
								<span class="time">
								16 mins </span>
								</span>
								<span class="message">
								Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
								</a>
							</li>
							<li>
								<a href="inbox14c8.html?a=view">
								<span class="photo">
								<img src="assets/img/avatar1.jpg" alt=""/>
								</span>
								<span class="subject">
								<span class="from">
								Bob Nilson </span>
								<span class="time">
								2 hrs </span>
								</span>
								<span class="message">
								Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
								</a>
							</li>
							<li>
								<a href="inbox14c8.html?a=view">
								<span class="photo">
								<img src="assets/img/avatar2.jpg" alt=""/>
								</span>
								<span class="subject">
								<span class="from">
								Lisa Wong </span>
								<span class="time">
								40 mins </span>
								</span>
								<span class="message">
								Vivamus sed auctor 40% nibh congue nibh... </span>
								</a>
							</li>
							<li>
								<a href="inbox14c8.html?a=view">
								<span class="photo">
								<img src="assets/img/avatar3.jpg" alt=""/>
								</span>
								<span class="subject">
								<span class="from">
								Richard Doe </span>
								<span class="time">
								46 mins </span>
								</span>
								<span class="message">
								Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
								</a>
							</li>
						</ul>
					</li>
					<li class="external">
						<a href="inbox.html">See all messages <i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
			</li>-->
			<!-- END INBOX DROPDOWN -->
			<!-- BEGIN TODO DROPDOWN -->
			<!--<li class="dropdown" id="header_task_bar">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<i class="icon-calendar"></i>
				<span class="badge badge-warning">
				5 </span>
				</a>
				<ul class="dropdown-menu extended tasks">
					<li>
						<p>
							 You have 12 pending tasks
						</p>
					</li>
					<li>
						<ul class="dropdown-menu-list scroller" style="height: 250px;">
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								New release v1.2 </span>
								<span class="percent">
								30% </span>
								</span>
								<span class="progress">
								<span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								40% Complete </span>
								</span>
								</span>

								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								Application deployment </span>
								<span class="percent">
								65% </span>
								</span>
								<span class="progress progress-striped">
								<span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								65% Complete </span>
								</span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								Mobile app release </span>
								<span class="percent">
								98% </span>
								</span>
								<span class="progress">
								<span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								98% Complete </span>
								</span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								Database migration </span>
								<span class="percent">
								10% </span>
								</span>
								<span class="progress progress-striped">
								<span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								10% Complete </span>
								</span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								Web server upgrade </span>
								<span class="percent">
								58% </span>
								</span>
								<span class="progress progress-striped">
								<span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								58% Complete </span>
								</span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								Mobile development </span>
								<span class="percent">
								85% </span>
								</span>
								<span class="progress progress-striped">
								<span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								85% Complete </span>
								</span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">
								New UI release </span>
								<span class="percent">
								18% </span>
								</span>
								<span class="progress progress-striped">
								<span style="width: 18%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only">
								18% Complete </span>
								</span>
								</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="external">
						<a href="#">See all tasks <i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
			</li>-->
			<!-- END TODO DROPDOWN -->
			<li class="devider">
				 &nbsp;
			</li>
			<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<?php if($admin_details['image']!=''){
									
									 $image1 = explode('.',$admin_details['image']);
									?>
                                   	<img src="<?php echo base_url(); ?>uploads/admin/thumb/<?php echo $image1[0].'_thumb.'.$image1[1]; ?>" class="img-circle" alt="User Image"  height="29" width="29"/>
									<?php } else{  ?>
												<img src="<?php echo base_url(); ?>public/images/no-image.jpg" class="img-circle" alt="No Image" title="No Image" height="29" width="29">
								     <?php } ?>
				<!--<img alt="" src="assets/img/avatar3_small.jpg"/>-->
				<span class="username" >
				{{fullname}}
				</span>
				<i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo base_url('admin/user/profile'); ?>"><i class="fa fa-user"></i> My Profile</a>
					</li>
					<!--<li>
						<a href="page_calendar.html"><i class="fa fa-calendar"></i> My Calendar</a>
					</li>
					<li>
						<a href="page_inbox.html"><i class="fa fa-envelope"></i> My Inbox <span class="badge badge-danger">
						3 </span>
						</a>
					</li>
					<li>
						<a href="#"><i class="fa fa-tasks"></i> My Tasks <span class="badge badge-success">
						7 </span>
						</a>
					</li>-->
					<li class="divider">
					</li>
					<li>
						<a href="<?php echo base_url('admin/user/logout'); ?>"><i class="fa fa-key"></i> Log Out</a>
					</li>
				</ul>
			</li>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
		<div class="clearfix"> </div>
			
		<?php } ?>
