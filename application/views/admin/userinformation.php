<link href="<?php echo base_url(); ?>public/css/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>public/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<style>
.cms ul{ text-align:left; list-style:disc inside none;}
.cms ul li{ font-size:12px; min-height:20px; }
.login_header .logo { margin-top: 10px;}
.footer_mid{ padding:13px 0;}
.login_header_carv { background-position:bottom center;  height:50px; width: 100%;}
.cms_con{ padding:5px; color:#222222;}
.cms_con strong{ color:#00afef; color: #1e77c1; float: left; width: 100%; font-size:13px;}
.cms_con h1 strong{ font-size:20px; font-weight:normal; width:100%; text-align:center;}
</style>
<div  class="cms">
<div class="cms_con">
<div style="text-align:center;">
<h4>User Information </h4>
<div class="clr20"></div>
<table width="100%" id="sample_2" class="table table-bordered table-striped gradient_row" >
<tr ><td style="font-size:14px; width:50%;"><strong>Title</strong></td><td align="justify" style="font-size:14px;width:50%;"><strong>Details</strong></td></tr>
<tr><td style="font-size:12px;">Full Name</td><td style="font-size:12px;"  align="justify"><?php echo isset($userinformation['fullname'])?$userinformation['fullname']:''; ?></td></tr>
<tr><td style="font-size:12px;">Email</td><td style="font-size:12px;"  align="justify"><?php echo  isset($userinformation['email'])?$userinformation['email']:''; ?></td></tr>
<tr>
<tr>
  <td style="font-size:12px;">Gender</td><td style="font-size:12px;"  align="justify"><?php echo isset($userinformation['gender'])?$userinformation['gender']:''; ?></td></tr>
  <td style="font-size:12px;">Phone</td><td style="font-size:12px;"  align="justify"><?php echo isset($userinformation['phone'])?$userinformation['phone']:'';  ?></td></tr>
<tr>
  <td style="font-size:12px;">Date of Birth</td><td style="font-size:12px;"  align="justify"><?php if(isset($userinformation['dob']) && $userinformation['dob']!='0000-00-00'){echo date('d/m/Y',strtotime($userinformation['dob'])); }else{ echo '';} ?></td></tr>
<tr>
  <td style="font-size:12px;">Occupation</td><td style="font-size:12px;"  align="justify"><?php echo isset($userinformation['occupation'])?$userinformation['occupation']:''; ?></td></tr>
<tr><td style="font-size:12px;">Role</td><td style="font-size:12px;"  align="justify"><?php echo isset($userinformation['role'])?ucfirst($userinformation['role']):''; ?></td></tr>
</table>
<div class="clr20"></div>
<p></p>
</div>
</div>
</div>