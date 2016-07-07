<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
<script>
function checkSubDomain()
{

			var txt_subinterest = $('#txt_subinterest').val();
			
			//alert(txt_domain);	
			var arr = [];
			var newarr = [];
			
			arr = txt_subinterest.split(',');
			k=0;
			//alert(arr.length);
			for(var i=0;i<arr.length;i++){
			
					//newarr[i] = arr[i].split('*');
					//alert(newarr[i][0])
					if(arr[i]!=0){
						k++;
					}
					//alert(newarr[i][1].length);
			
			}
			//alert(k);false
			if(k==0){
				alert('You have to select atleast 1 Sub Interest !!');
				return false;
			}
			//alert(k);
			//alert(txt_domain)
			

}
var subInterestArray = [];
$(".chk_subinterest").click(function(event){
				
				var selectedNo = $(event.target).val();
				var index = $.inArray(selectedNo, subInterestArray); 
				if (index != -1) {// means found so pop it 
					subInterestArray.splice(index, 1);
				} else {
					subInterestArray.push(selectedNo);
				}
				console.log(subInterestArray);
				$('#txt_subinterest').val(subInterestArray);
		

})

</script>
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
.form-control-new {
    background-color: #FFFFFF;
    background-image: none;
    border: 1px solid #CCCCCC;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555555;
    display: block;
    font-size: 14px;
    height: 26px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 100%;
}

</style>
<?php //echo $domainID; ?>
<div class="" id="example1_wrapper">
	<div  class="cms">
		<div class="cms_con">
			<div style="text-align:center;">
				<h4>List of Sub Interest</h4>
				<div class="clr20"></div>
<form name="frmStatus" action="<?php echo base_url('admin/interest/deleteSubinterest'); ?>" method="post" onSubmit="return checkSubDomain()">
<table width="100%" id="sample_2" class="table table-bordered table-striped gradient_row" >
<tr >
  <td width="5%" style="font-size:14px; "><strong>Sr. No</strong></td>
  <td width="34%" style="font-size:14px; " align="left"><strong>Sub Interest</strong></td>
  <td width="10%" style="font-size:14px; " align="center"><strong>Action</strong></td>
  </tr>

  	<?php 
	$count=0;
	//echo '<pre>';
	//print_r($subinterest);
	if(!empty($subinterest)){
	$arr = array();
	foreach($subinterest as $key=>$val){ $count++;
	
	$arr[$count-1] = $val['id'].'*'.$val['subinterest_name'];
	?>
<tr>
	<td style="font-size:12px;"><?php echo $count; ?></td>
    <td style="font-size:12px;" align="left"><?php echo $val['subinterest_name']; ?></td>
	<td style="font-size:12px;" align="center"><input type="checkbox" value="<?php echo $val['id']; ?>" id="sub<?php echo $val['id']; ?>" name="update_sub" class="chk_subinterest" <?php //echo ($val['is_triad']=='1')?'checked="checked"':''?>>
	<!--<input type="text" id="txt_subdomain<?php //echo $val['id']; ?>" name="txt_subdomain[]" value="<?php //echo ($val['is_triad']=='1')?$val['is_triad']:'0'?>">-->
	</td>
    
</tr>
   <?php } ?>
   <tr><td >&nbsp;</td><td>&nbsp;</td><td align="center"><button id="" class="btn btn-success">Remove<!--<i class="fa fa-plus"></i>--></button></td></tr>
   <?php } else { ?>
   <tr><td colspan="3" align="center">No record found</td></tr>
   <?php } ?>
</table>
<input type="hidden" id="txt_subinterest" name="txt_subinterest" value="<?php //if(!empty($arr)){ echo implode(',',$arr); }?>">
<input type="hidden" id="interestID" name="interestID" value="<?php if(isset($interestID)){ echo $interestID; }?>">
<!--<input type="text" id="count" name="count" value="<?php echo $count; ?>">-->
</form>
<div class="clr20"></div>
<p><!--Inorder to delete this Domain you have to remove items from Statements,Functions,Disciplines,SubDomains <strong>(Bottom Up Approach)</strong>--></p>
		</div>
	  </div>
	</div>
</div>

