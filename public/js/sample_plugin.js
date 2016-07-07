(function($){
	/*$.fn.sayHallo=function(option){
		
		var settings=$.extend({text:"welcome",color:"#oFF"},option)
		
		return this.each(function(){
			$(this).text("Hello.."+settings.text);
			$(this).css('background-color',settings.color);
		});
	}*/
	$.fn.initSlider=function(option){
		
		var settings=$.extend({imgPathArr:['images/example-slide-1.jpg','images/example-slide-2.jpg','images/example-slide-3.jpg','images/example-slide-4.jpg']},option);
		
		var imagePathArr=settings.imgPathArr;
		var bigImg='<div class="BigImageCon"><images src='+imagePathArr[0]+' alt="aaaa" width="400" height="400"></div>';
		var str='';
		for(var i=0;i<imagePathArr.length;i++){
			str+='<li class="thumbList" id='+i+'><images src='+imagePathArr[i]+' alt="aaaa" width="50" height="50"></li>'
		}		
		var thumbContainer='<div class="thumbContiner"><ul>'+str+'</ul></div>';
		this.append(bigImg);
		this.append(thumbContainer);
		$('.thumbContiner ul li').on('click',function(e){
				//$('.BigImageCon img').attr('src',imagePathArr[$(this).attr('id')]);
				var ind=$(this).attr('id');
				$('.BigImageCon img').fadeOut(200,changepath);
				
				function changepath(){
					$('.BigImageCon img').attr('src',imagePathArr[ind]);
				}
				$('.BigImageCon img').fadeIn(200);
		});
		
		
		var autoSlideIndex=0;
		function autoSlide(){
			autoSlideIndex++;
			$('.BigImageCon img').attr('src',imagePathArr[autoSlideIndex]);
			if(autoSlideIndex==imagePathArr.length){
				autoSlideIndex=-1;
			}
		}
		var autoChange= setInterval(autoSlide,3000);
		
		
		return this;
		
	}
	$.fn.initCheckBox=function(){
		return this.each(function(){
			//$(this).css('background-color', '#F00');
			var checkBase='<div class="checkBg"></div>';
			var tickBase='<div class="tick"></div>';
			$(this).css('position','relative');
			$(this).append(checkBase);
			$(this).append(tickBase);
			$(this).attr('isChecked','false');
			$(this).on('click',function(e){
				$(this).find('.tick').toggleClass("tickBg");
				if($(this).find('.tick').hasClass('tickBg')){
					$(this).attr('isChecked','true');
				}else{
					$(this).attr('isChecked','false');
				}
			});
			
		});
	}
	
})(jQuery)