jQuery( document ).ready( function( $ ) 
{
	$.sp_general_admin = {
		init: function()
		{
			// tooltip for platform icons
			var tip;
			jQuery( ".tooltip" ).hover( function()
			{
		
				//Caching the tooltip and removing it from container; then appending it to the body
				tip = jQuery( this ).find( '.tip' ).remove();
				jQuery( 'body' ).append( tip );
		
				tip.fadeTo( 400, 0.8 ); //Show tooltip
		
			}, 
			function() 
			{
				tip.hide().remove(); //Hide and remove tooltip appended to the body
				jQuery( this ).append( tip ); //Return the tooltip to its original position
		
			}).mousemove(function(e) {
			//console.log(e.pageX)
				if( typeof(tip) !== "undefined") {
				  var mousex = e.pageX + 20; //Get X coodrinates
				  var mousey = e.pageY + 20; //Get Y coordinates
				  var tipWidth = tip.width(); //Find width of tooltip
				  var tipHeight = tip.height(); //Find height of tooltip
		
				 //Distance of element from the right edge of viewport
				  var tipVisX = jQuery(window).width() - (mousex + tipWidth);
				  var tipVisY = jQuery(window).height() - (mousey + tipHeight);
		
				if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
					mousex = e.pageX - tipWidth - 20;
					jQuery(this).find('.tip').css({  top: mousey, left: mousex });
				} if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
					mousey = e.pageY - tipHeight - 20;
					tip.css({  top: mousey, left: mousex });
				} else {
					tip.css({  top: mousey, left: mousex });
				}
			  }
			});
			
			// display portfolio template specific page options
			jQuery( "#page_template" ).change( function() 
			{
				if ( jQuery( this ).val() === 'content-portfolio.php' ) 
				{
					// message is set in the localized variable 
					alert( sp_admin_general.portfolio_template_msg );	
				}
			});

			// tooltip for platform icons
			var tip;
			jQuery( ".sptooltip" ).hover( function()
			{
		
				//Caching the tooltip and removing it from container; then appending it to the body
				tip = jQuery( this ).find( '.tip' ).remove();
				jQuery( 'body' ).append( tip );
		
				tip.fadeTo( 400, 0.9 ); //Show tooltip
		
			}, 
			function() 
			{
				tip.hide().remove(); //Hide and remove tooltip appended to the body
				jQuery( this ).append( tip ); //Return the tooltip to its original position
		
			}).mousemove(function(e) {
			//console.log(e.pageX)
				if( typeof(tip) !== "undefined") {
				  var mousex = e.pageX + 20; //Get X coodrinates
				  var mousey = e.pageY + 20; //Get Y coordinates
				  var tipWidth = tip.width(); //Find width of tooltip
				  var tipHeight = tip.height(); //Find height of tooltip
		
				 //Distance of element from the right edge of viewport
				  var tipVisX = jQuery(window).width() - (mousex + tipWidth);
				  var tipVisY = jQuery(window).height() - (mousey + tipHeight);
		
				if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
					mousex = e.pageX - tipWidth - 20;
					jQuery(this).find('.tip').css({  top: mousey, left: mousex });
				} if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
					mousey = e.pageY - tipHeight - 20;
					tip.css({  top: mousey, left: mousex });
				} else {
					tip.css({  top: mousey, left: mousex });
				}
			  }
			});

		  jQuery( "#sp_truncate_enable" ).click( function() 
		  {
		  		var container = jQuery( this ).parents( "p" ).next( ".settings-container" );
				container.slideToggle( 'fast' );	
		  });
		  
		  // disable the move template files button on WPEC plugin
		  jQuery( "input[name='wpsc_move_themes']" ).live( 'click', function( e )  
		  {
			 e.preventDefault();
			 alert( sp_admin_general.wpec_move_themes_msg );
		  });

			// bind datepicker
			jQuery("input.datepicker").live('focus', function() {
				jQuery(this).datetimepicker();
				jQuery("#ui-datepicker-div").wrap('<div class="jquery-ui-style" />');
			});
		  
		} // close init
	} // close namespace
	$.sp_general_admin.init();
// end document ready
});