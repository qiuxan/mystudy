jQuery( document ).ready( function( $ ) 
{	
	// create namespace to avoid any possible conflicts
	$.sp_fw = {		
		init: function() 
		{
			// tabs/panels
			$( '#content, .panel' ).tabs({ fx: { 
				opacity:'toggle', 
				duration: 200 }, 
				cache: false 
			});
			
			// colorpicker
			$( '.colorInput' ).live( 'blur', function() 
			{
				var temp = $( this );
				if( temp.val() == '' )
				{
					var color = "#ffffff";	
				} 
				else 
				{
					var color = "#" + temp.val().replace( "#", "" );	
				}
				temp.parent().find( "span.selectColor" ).ColorPickerSetColor( temp.val() ).css( 'background-color', color );
			});
			
			$( 'span.selectColor' ).ColorPicker({
				onBeforeShow: function( hsb, hex, rgb )
				{
					current_obj = this;
					$( this ).ColorPickerSetColor( $( this ).parent().find( ".colorInput" ).val());
				},
				onShow: function ( colpkr ) 
				{
					$( colpkr ).fadeIn( 200 );
					return false;
				},
				onHide: function ( colpkr ) 
				{
					var temp = $( current_obj );
					var val = $( colpkr ).find( ".colorpicker_hex input[type=text]" ).val();
					temp.css( { 'background-color':"#" + val} );
					temp.parent().find( "input[type=text]:first" ).val( "#" + val );
					$( colpkr ).fadeOut( 200 );
					init_preview();
					return false;
				}
			});
					
			// hide update notifications
			jQuery( ".hide_msg" ).live( 'click', function() 
			{
				var reply = confirm( sp_theme.hide_notification_msg );
				if ( reply ) 
				{	
					jQuery( "#checkbox-hide-update-notification" ).attr( 'checked', 'checked' );
					show_alert();
					var form = $( '#sp-panel' ).serialize();
					var $data = {
					action: "sp_theme_save",
					form_items: form,
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post( sp_theme.ajaxurl, $data, function( response ) 
					{
						jQuery( 'p.update' ).fadeOut( 300 );
						hide_alert( 'done' );
					});  
				}
					
				
			});
		
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
				
			// save	
			 jQuery('#sp-panel').submit(function(e){
					e.preventDefault();
					show_alert();
					var form = $('#sp-panel').serialize();
					var $data = {
					action: "sp_theme_save",
					form_items: form,
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post(sp_theme.ajaxurl, $data, function(response) {
		
						hide_alert(response);
					});  
			});
		
			// media upload
			var url_input, tbframe_interval, image_wrapper;
			jQuery('.upload-link').live('click', function()	
			{
				image_wrapper = jQuery(this).parents('.image-wrapper');
				var title = image_wrapper.find('label').html();
				url_input = image_wrapper.find(".image-path");
				tb_show(title, 'media-upload.php?type=image&amp;TB_iframe=true');
						tbframe_interval = setInterval(function() {
						var contents = jQuery('#TB_iframeContent').contents();
						contents.find('.savesend .button').val(sp_theme.insert_media);
						contents.find('tr.post_title').hide();
						contents.find('tr.image_alt').hide();
						contents.find('tr.post_excerpt').hide();
						contents.find('tr.post_content').hide();
						contents.find('tr.url').hide();
						contents.find('tr.align').hide();
						contents.find('tr.image-size').find("input:radio[value=full]").attr('checked', true);
						contents.find('tr.image-size').hide();
						}, 100);
				
				return false;
			});
					
			window.original_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function( html ) 
			{
				
				if ( url_input )
				{
					var imgurl = jQuery(html).attr('src') || jQuery(html).find('img').attr('src') || jQuery(html).attr('href');
					var responseVal;
					// runs the image url string through a check to see if it is from a multisite
					var $data = {
					action: "sp_check_ms_image_ajax",
					image: imgurl,
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.ajax({ 
					url: sp_theme.ajaxurl, 
					data: $data, 
					async: false,
					type: 'POST',
					success: function(response) {
						responseVal = response;
					}
					});
					url_input.val(responseVal); 					
					image_wrapper.find('.cur-image').html(sp_theme.media_uploaded_msg).css("display","block").css("color","red");
					image_wrapper.find('.remove-image.button').css("display","block");
					image_wrapper.find('.image-preview').hide();
					update_preview();
					tb_remove();
					
					// resets the variable to empty string	
					url_input = '';
					
					// clears the interval and resets
					clearInterval( tbframe_interval );
				}
				else 
				{
					window.original_send_to_editor( html );	
				}
			}
		
			// media delete	
			jQuery(".remove-image").live('click',function(e) {
				e.preventDefault();
				var wrapper = jQuery(this).parents('.image-wrapper')
				wrapper.find('.image-path').attr('value', '');
				wrapper.find('.image-preview').hide();
				wrapper.find('.remove-image.button').hide();
				wrapper.find('.cur-image').hide();
				update_preview();
			});
			
			// reset settings
			jQuery('#sp-panel .reset').click(function(e) {
				e.preventDefault();
				var reply = confirm( sp_theme.reset_settings_msg );
				if (reply) {
					show_alert();
					var $data = {
					action: "sp_theme_reset",
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post(sp_theme.ajaxurl, $data, function(response) {
						if (response == 'done') {
							hide_alert('done');
							window.location = "admin.php?page=sp";
						}
					}); 
				}
			});
		
			// clear product star ratings
			jQuery('#sp-panel .clear_star_ratings').click(function(e) {
				e.preventDefault();
				var reply = confirm( sp_theme.clear_stars_ratings_msg );
				if (reply) {
					show_alert();
					var $data = {
					action: "sp_clear_star_ratings_ajax",
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post(sp_theme.ajaxurl, $data, function(response) {
						if (response == 'done') {
							hide_alert('done');
						} else {
							hide_alert('done');
						}
					}); 
				}
			});

			// exports theme settings

			jQuery('#sp-panel .export-theme-settings').click(function(e) {
				e.preventDefault();
				show_alert();
				var $data = {
				action: "sp_export_theme_settings_ajax",
				ajaxCustomNonce : sp_theme.ajaxCustomNonce
				};
				jQuery.post(sp_theme.ajaxurl, $data, function(response) {
					if (response == 'done') {
						hide_alert('done');
					} else {
						hide_alert('errors');
					}
				}); 
			});

			// import theme settings
			jQuery('#sp-panel .import-theme-settings').click(function(e) {
				e.preventDefault();
				var reply = confirm(sp_theme.settings_import_msg);
				if (reply) {				
					show_alert();
					var $data = {
					action: "sp_import_theme_settings_ajax",
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post(sp_theme.ajaxurl, $data, function(response) {
						if (response == 'done') {
							hide_alert('done');
							window.location = "admin.php?page=sp";
						} else {
							hide_alert('errors');
						}
					}); 
				}
			});

			// create page
			 jQuery('#sp-panel .create-page').click(function(e){
					e.preventDefault();
					show_alert();
					var page_title = $('#sp-panel .page-title').val();
					var page_template = $('#sp-panel .page-template').val();
					var $data = {
					action: "sp_create_page_ajax",
					page_title : page_title,
					page_template : page_template,
					ajaxCustomNonce : sp_theme.ajaxCustomNonce
					};
					jQuery.post(sp_theme.ajaxurl, $data, function(response) {
						hide_alert(response);
						window.location = "admin.php?page=sp";
					});  
			});
		
			// footer widget switch
			jQuery('#sp-panel .footer_widgets').click(function(e) {
				e.preventDefault();
				var selected = jQuery(this).attr('id');
				jQuery('#sp-panel .footer_widgets img').removeClass('selected');
				jQuery(this).find('img').addClass('selected');
				jQuery('#sp-panel #footer_widget_hidden_value').val(selected);
			});
		
			// skins switch
			jQuery('#sp-panel .skins').click(function(e) {
				e.preventDefault();
				var selected = jQuery(this).attr('id');
				jQuery('#sp-panel .skins').removeClass('selected');
				jQuery(this).addClass('selected');
				jQuery('#sp-panel #hidden_skins_value').val(selected);
			});
		
			// homepage custom slider
			jQuery("#homepage_custom_slide_count").change(function() {
				var currSelection = jQuery("option:selected",this).val();
				jQuery("div[class^='homepage_featured_slide_']").show().filter(":gt("+(parseInt(jQuery(this).val()) -1 )+")").hide();
			}).change();
		
			// add custom page widgets
			jQuery("#sp-panel .custom_page_widget a.add").live('click', function(e) {
				e.preventDefault();

				// stop the propagation 
				e.stopPropagation();

				var element = jQuery(this).parent(".custom_page_widget");

				// duplicate the element
				cloneElement = element.clone( true );

				cloneElement.find( 'select.chzn-select' ).removeClass( 'chzn-done' ).css( 'display', 'block' ).removeAttr( 'id' ).next( '.chzn-container' ).remove();

				cloneElement.slideDown( 'fast' ).insertAfter( element ).find( 'select option[value=0]' ).attr( 'selected', 'selected' );

				cloneElement.find( 'select.chzn-select' ).chosen();				
			});
			
			// delete custom page widgets
			jQuery("#sp-panel .custom_page_widget a.delete").click(function(e) {
				e.preventDefault();
				var thisElement = jQuery(this).parent(".custom_page_widget");
				var allElement = jQuery("#sp-panel .custom_page_widget");
				if (allElement.length > 1) {
					thisElement.slideUp('fast', function() { thisElement.remove(); })
				}
			});
			// add custom blog category widgets
			jQuery("#sp-panel .custom_blog_category_widget a.add").live('click', function(e) {
				e.preventDefault();

				// stop the propagation 
				e.stopPropagation();

				var element = jQuery(this).parent(".custom_blog_category_widget");
				
				// duplicate the element
				cloneElement = element.clone( true );

				cloneElement.find( 'select.chzn-select' ).removeClass( 'chzn-done' ).css( 'display', 'block' ).removeAttr( 'id' ).next( '.chzn-container' ).remove();

				cloneElement.slideDown( 'fast' ).insertAfter( element ).find( 'select option[value=0]' ).attr( 'selected', 'selected' );

				cloneElement.find( 'select.chzn-select' ).chosen();	
			});
			
			// delete custom blog category widgets
			jQuery("#sp-panel .custom_blog_category_widget a.delete").click(function(e) {
				e.preventDefault();
				var thisElement = jQuery(this).parent(".custom_blog_category_widget");
				var allElement = jQuery("#sp-panel .custom_blog_category_widget");
				if (allElement.length > 1) {
					thisElement.slideUp('fast', function() { thisElement.remove(); })
				}
			});
			// add custom product category widgets
			jQuery("#sp-panel .custom_product_category_widget a.add").live('click', function(e) {
				e.preventDefault();

				// stop the propagation 
				e.stopPropagation();
								
				var element = jQuery(this).parent(".custom_product_category_widget");
				// duplicate the element
				cloneElement = element.clone( true );

				cloneElement.find( 'select.chzn-select' ).removeClass( 'chzn-done' ).css( 'display', 'block' ).removeAttr( 'id' ).next( '.chzn-container' ).remove();

				cloneElement.slideDown( 'fast' ).insertAfter( element ).find( 'select option[value=0]' ).attr( 'selected', 'selected' );

				cloneElement.find( 'select.chzn-select' ).chosen();	
			});
			
			// delete custom product category widgets
			jQuery("#sp-panel .custom_product_category_widget a.delete").click(function(e) {
				e.preventDefault();
				var thisElement = jQuery(this).parent(".custom_product_category_widget");
				var allElement = jQuery("#sp-panel .custom_product_category_widget");
				if (allElement.length > 1) {
					thisElement.slideUp('fast', function() { thisElement.remove(); })
				}
			});

			// show loading
			function show_alert() {
				jQuery(".alert").addClass("loading").fadeIn(600);	
			}
		
			// hide loading	
			function hide_alert(status) {
				if(status == 'errors') {
					status = 'errors';	
				} else {
					status = 'done';	
				}
				jQuery(".alert").removeClass("loading").addClass(status).delay(800).fadeOut(600, function(){
					jQuery(this).removeClass(status);											   
				});	
			}
			
			// toggle display of homepage featured products and featured categories
			var products = jQuery("#radio-display-products-or-categories-products").attr('checked');
			var categories = jQuery("#radio-display-products-or-categories-categories").attr('checked');
			if (products == 'checked') { 
				jQuery("." + sp_theme.theme_name + "homepage_carousel_categories").hide();
			} else {
				jQuery("." + sp_theme.theme_name + "homepage_carousel_category").hide();
			}
			
			jQuery("input[name=" + sp_theme.theme_name + "homepage_carousel_display_type]").click(function() {
				jQuery("." + sp_theme.theme_name + "homepage_carousel_category").slideUp('fast');
				jQuery("." + sp_theme.theme_name + "homepage_carousel_categories").slideUp('fast');	
				
				if (jQuery(this).val() == 'products') {
					jQuery("." + sp_theme.theme_name + "homepage_carousel_category").slideDown(800);
				} else if (jQuery(this).val() == 'categories') {
					jQuery("." + sp_theme.theme_name + "homepage_carousel_categories").slideDown(800);
				}
			});

			// toggle display of footer featured products and featured categories
			var products = jQuery("#radio-display-products-or-categories-products").attr('checked');
			var categories = jQuery("#radio-display-products-or-categories-categories").attr('checked');
			if (products == 'checked') { 
				jQuery("." + sp_theme.theme_name + "footer_carousel_categories").hide();
			} else {
				jQuery("." + sp_theme.theme_name + "footer_carousel_category").hide();
			}
			
			jQuery("input[name=" + sp_theme.theme_name + "footer_carousel_display_type]").click(function() {
				jQuery("." + sp_theme.theme_name + "footer_carousel_category").slideUp('fast');
				jQuery("." + sp_theme.theme_name + "footer_carousel_categories").slideUp('fast');	
				
				if (jQuery(this).val() == 'products') {
					jQuery("." + sp_theme.theme_name + "footer_carousel_category").slideDown(800);
				} else if (jQuery(this).val() == 'categories') {
					jQuery("." + sp_theme.theme_name + "footer_carousel_categories").slideDown(800);
				}
			});
			
			// bind datepicker
			jQuery("input.datepicker").datetimepicker();
			jQuery("#ui-datepicker-div").wrap('<div class="jquery-ui-style" />');
			
			// custom backgrounds
			var custom_bg_selected = jQuery("#custom_background option:selected").val();
			
			if (custom_bg_selected.length == 0 ) {
				jQuery("p." + sp_theme.theme_name + "background_image").hide();	
			} else if (custom_bg_selected  == 'custom' ) {
				jQuery("p." + sp_theme.theme_name + "background_image").show();		
			} else {
				jQuery("p." + sp_theme.theme_name + "background_image").hide();
			}
			
			jQuery("#custom_background").change(function() {
				var custom_bg_selected = jQuery("#custom_background option:selected").val();

				if (custom_bg_selected.length == 0 ) {
					jQuery("p." + sp_theme.theme_name + "background_image").slideUp('fast');	
				
					// updates preview
					update_preview();
				} else if (custom_bg_selected  == 'custom' ) {
					jQuery("p." + sp_theme.theme_name + "background_image").slideDown('fast');	
				
					// updates preview
					update_preview();						
				} else {
					jQuery("p." + sp_theme.theme_name + "background_image").slideUp('fast');
				}
				
				// update site preview on change
				if ( custom_bg_selected.length != 0 && custom_bg_selected != 'custom' ) { 
					jQuery(".site-preview .preview-bg").css('background-image', 'url(' + custom_bg_selected + ')');
				}
			});
			
			// listen for changes in the custom background upload input field
			jQuery("." + sp_theme.theme_name + "background_image input.image-path").live('change', function() {
				update_preview();
			});

			function update_preview() {
				var file = jQuery("." + sp_theme.theme_name + "background_image input.image-path").val();
				
				if (file.length != 0) {
					jQuery(".site-preview .preview-bg").css('background-image', 'url(' + file + ')');	
				} else {
					jQuery(".site-preview .preview-bg").css('background-image', 'none');
				}		
			}
			
			jQuery("#styling-typography select").live('change', function() { 
				init_preview();
			});

			function init_preview() { 
				var custom_bg_selected = jQuery("#custom_background option:selected").val();
				var heading_link = jQuery("p." + sp_theme.theme_name + "heading_size input").val();
				// background
				if (custom_bg_selected.length != 0 && custom_bg_selected != 'custom') {
					jQuery(".site-preview .preview-bg").css('background-image', 'url(' + custom_bg_selected + ')');	
				} else {
					jQuery(".site-preview .preview-bg").css('background-image', 'none');
				}

				// if background color is empty
				jQuery('#colorpicker-background-color').change(function() { 
					jQuery('.site-preview .preview-bg').css('background-color', '');		
				});
				
				var backgroundColor = jQuery('#colorpicker-background-color').val();

				if (backgroundColor.length)
					jQuery('.site-preview .preview-bg').css('background-color', backgroundColor);
				else
					jQuery('.site-preview .preview-bg').css('background-color', '');

				jQuery("#styling-typography input.colorInput").each(function() {
					var colorInput = jQuery(this);
					var element = colorInput.attr('name');
					var elementValue = colorInput.val();

					if (elementValue.length != 0) {
						switch(element) 
					    {
							case sp_theme.theme_name + 'heading_color':
								jQuery(".site-preview .preview-heading").css('color', elementValue);		
							break;
							
							case sp_theme.theme_name + 'heading_link_color':
								jQuery(".site-preview .preview-heading-link").css('color', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_link_hover_color':
								jQuery(".site-preview .preview-heading-link-hover").css('color', elementValue);		
							break;

							case sp_theme.theme_name + 'text_color':
								jQuery(".site-preview .preview-text").css('color', elementValue);		
							break;

							case sp_theme.theme_name + 'link_color':
								jQuery(".site-preview .preview-link").css('color', elementValue);		
							break;

							case sp_theme.theme_name + 'link_hover_color':
								jQuery(".site-preview .preview-link-hover").css('color', elementValue);		
							break;
							
							default:
						}
					}
				});

				jQuery("#styling-typography select").each(function() {
					var selectInput = jQuery(this);
					var element = selectInput.attr('name');
					var elementValue = jQuery("option:selected", this).val();
					
					if (elementValue != 0) {
						switch(element) 
					    {
							case sp_theme.theme_name + 'heading_font_family':
								var fontString = jQuery('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+elementValue+'" />');
								jQuery(".site-preview .preview-heading").before(fontString).css('font-family', elementValue + ', sans-serif');		
							break;
							
							case sp_theme.theme_name + 'heading_font_weight':
								jQuery(".site-preview .preview-heading").css('font-weight', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_style':
								jQuery(".site-preview .preview-heading").css('font-style', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_link_font_family':
								var fontString = jQuery('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+elementValue+'" />');
								jQuery(".site-preview .preview-heading-link, .site-preview .preview-heading-link-hover").before(fontString).css('font-family', elementValue + ', sans-serif');		
							break;

							case sp_theme.theme_name + 'heading_link_font_weight':
								jQuery(".site-preview .preview-heading-link, .site-preview .preview-heading-link-hover").css('font-weight', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_link_style':
								jQuery(".site-preview .preview-heading-link, .site-preview .preview-heading-link-hover").css('font-style', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_link_decoration':
								jQuery(".site-preview .preview-heading-link").css('text-decoration', elementValue);		
							break;

							case sp_theme.theme_name + 'heading_link_hover_decoration':
								jQuery(".site-preview .preview-heading-link-hover").css('text-decoration', elementValue);		
							break;

							case sp_theme.theme_name + 'text_font_family':
								var fontString = jQuery('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+elementValue+'" />');
								jQuery(".site-preview .preview-text").before(fontString).css('font-family', elementValue + ', sans-serif');		
							break;

							case sp_theme.theme_name + 'text_font_weight':
								jQuery(".site-preview .preview-text").css('font-weight', elementValue);		
							break;

							case sp_theme.theme_name + 'text_style':
								jQuery(".site-preview .preview-text").css('font-style', elementValue);		
							break;

							case sp_theme.theme_name + 'link_font_family':
								var fontString = jQuery('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+elementValue+'" />');
								jQuery(".site-preview .preview-link, .site-preview .preview-link-hover").before(fontString).css('font-family', elementValue + ', sans-serif');		
							break;

							case sp_theme.theme_name + 'link_font_weight':
								jQuery(".site-preview .preview-link, .site-preview .preview-link-hover").css('font-weight', elementValue);		
							break;

							case sp_theme.theme_name + 'link_style':
								jQuery(".site-preview .preview-link, .site-preview .preview-link-hover").css('font-style', elementValue);		
							break;

							case sp_theme.theme_name + 'link_decoration':
								jQuery(".site-preview .preview-link-hover").css('text-decoration', elementValue);		
							break;

							case sp_theme.theme_name + 'link_hover_decoration':
								jQuery(".site-preview .preview-link-hover").css('text-decoration', elementValue);		
							break;

							
							default:
						}
					}
				});
				
			}
			jQuery(".chzn-select").chosen();
			init_preview();
		}  // close init
	} // close namespace
	
	$.sp_fw.init();
// end document ready
});