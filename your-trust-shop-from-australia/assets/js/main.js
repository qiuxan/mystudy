$(function(){
	// cache the window obj
	var $window=$(window);
	// parallax bg effecty
	
	$('section[data-type="background"]').each(function(){
		
		var $bgobj=$(this);//assign the obj
		$(window).scroll(function(){
			//scroll the bg at the var speed
			// yposition is a negative value 
			var yPos=-($window.scrollTop()/$bgobj.data('speed'));
			var coords='50% '+ yPos + 'px';
			
			$bgobj.css({backgroundPosition:coords});
			
		});
	});
	
	
});