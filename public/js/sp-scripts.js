jQuery(function( $ ){

    $.localScroll({
    	duration: 750
    });
    
    
    /* --- Mobile Nav --- */
    
    $('#mobile-nav .icon-menu').click( function(){
	    
	    $('.site-header .genesis-nav-menu').slideToggle();
	    
    } );
		
	/* --- Testimonials --- */
	
	/* Show random testimonials by default */
	playTestimonials();
	
	/* Clear click action on links */
	$('.testimonials .photos ul li a').click( function(){ return false; } );
	
	/* Mouseover action on testimonial photos */
	$('.testimonials .photos ul li').hover( function(){
		
		// clear timer
		window.clearTimeout(timeout);
		
		$('.testimonials .photos ul li.active').removeClass('active');
		
		quoteclass = $(this).attr('class');
		
		author = $(this).find('img').attr('alt');
		author = author.split('-');
		author = author[0] + '<span> - ' + author[1] + '</span>';
		
		$('.testimonials .quotes ul li.active').fadeOut('slow', function(){
			$(this).removeClass('active');
			$('.testimonials .quotes ul li.' + quoteclass).fadeIn().addClass('active');
			$('.testimonials .photos .author').html(author);
		});
		
		$(this).addClass('active');
		
	}, function(){
		
		// go back to random testimonials
		timeout = window.setTimeout( playTestimonials, 5000 );
		
		return false;
	});
	
	/* Play random testimonials */
	function playTestimonials() {
		
		$('.testimonials .photos ul li.active').removeClass('active');
		
		randomnumber = Math.floor( (Math.random()*10) + 1 );
		
		author = $('.testimonials .photos ul li.quote-' + randomnumber).find('img').attr('alt');
		author = author.split('-');
		author = author[0] + '<span> - ' + author[1] + '</span>';
		
		$('.testimonials .quotes ul li.active').fadeOut('slow', function(){
			$(this).removeClass('active');
			$('.testimonials .quotes ul li.quote-' + randomnumber).fadeIn().addClass('active');
			$('.testimonials .photos .author').html(author);
		});
		
		$('.testimonials .photos ul li.quote-' + randomnumber).addClass('active');
		
		timeout = window.setTimeout( playTestimonials, 5000 );
	}
	
});