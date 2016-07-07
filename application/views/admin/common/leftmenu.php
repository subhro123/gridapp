<script>
$(document).ready(function() {

		/*$('.submenuclass').click(function(){
		
		     	var id = $(this).attr('id')
			 
			 	//alert('#main_'+id);
				// $('#main_'+id).removeClass('open');
				$('#main_'+id).addClass('active');
				$('.sub-menu').attr("style", "display: block");
				
				submenuhighlight(id)
				//$('#main_'+id).addClass('open');
					 
			});*/
		
		
		
			

})


</script>
<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu">
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<div class="clearfix">
					</div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li class="sidebar-search-wrapper">
					<!--<form class="search-form" role="form" action="http://www.keenthemes.com/preview/conquer/index.html" method="get">
						<div class="input-icon right">
							<i class="fa fa-search"></i>
							<input type="text" class="form-control input-sm" name="query" placeholder="Search...">
						</div>
					</form>-->
				</li>
                <?php 
				            
				 $menutree = $this->muser->getMenuSubMenuTree($session_data['id']);
				 //echo $this->uri->segment(2);
				 //echo '<pre>';
				 //print_r($menutree);
				 //echo $this->uri->segment(3);
				 foreach($menutree as $key=>$val){
				
				?>
				<li id='main_<?php echo $val['id']; ?>' <?php if($this->uri->segment(2)==$val['menu_short_name']){echo 'class="active" ';}else{ echo 'class="start " ';} ?>>
					<a href="<?php echo base_url(); ?>admin/<?php echo $val['menu_short_name']; ?>">
						<i class="<?php echo $val['image']; ?>"></i>
							<span class="title"><?php echo $val['menu']; ?></span>
								<!--<span class="arrow "></span>-->
								</a>
								<?php if(!empty($val['submenu'])){?>
									<ul class="sub-menu">
										 <?php 
													foreach($val['submenu'] as $key=>$val_sub){
												    	$submenu = $this->uri->segment(2).'/'.$this->uri->segment(3);
														
														$submenuarr=explode('/',$val_sub['sub_menu_short_name']);
														
														$addsubmenu = $this->uri->segment(2).'/'.$this->uri->segment(3);
														$dbaddsubmenu = $submenuarr[0].'/add'.$submenuarr[1];
														
														$editsubmenu = $this->uri->segment(2).'/'.$this->uri->segment(3);
														$dbeditsubmenu = $submenuarr[0].'/edit'.$submenuarr[1];
											?>
													<li <?php if(($submenu==$val_sub['sub_menu_short_name']) || ($addsubmenu==$dbaddsubmenu) || ($editsubmenu==$dbeditsubmenu)){echo 'class="active" ';}else{ echo 'class="start " ';} ?>>
															<a  href="<?php echo base_url(); ?>admin/<?php echo $val_sub['sub_menu_short_name']; ?>" id="<?php echo $val['id']; ?>" >
																<i class="fa fa-angle-double-right"></i><?php echo $val_sub['submenu']; ?>
															</a>
													</li>
										<?php } ?>
								</ul>
							<?php } ?>
                		</li>
                <?php } ?>
				</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>

			
			
