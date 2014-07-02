/*!
 * Admin js
 */

jQuery(document).ready(function() {

var uploadparent = 0;
 function media_upload( button_class) {
    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;
    jQuery('body').on('click',button_class, function(e) {
	uploadparent = jQuery(this).closest('div');
        var button_id ='#'+jQuery(this).attr('id');
        /* console.log(button_id); */
        var self = jQuery(button_id);
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(button_id);
       // var id = button.attr('id').replace('_button', '');
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
            if ( _custom_media  ) {
              // jQuery('.custom_media_id').val(attachment.id); 		  
               uploadparent.find('.slideimages').val(attachment.url);
			   uploadparent.find('.imagebox').attr('src',attachment.url);
              // jQuery('.custom_media_image').attr('src',attachment.url).css('display','block');   
            } else {
                return _orig_send_attachment.apply( button_id, [props, attachment] );
            }
        }
        wp.media.editor.open(button);
        return false;
    });
}
media_upload( '.upload_image_button');		
			
			
// Show/hide slides
		jQuery('.slidetitle').click(function() {
				jQuery(this).next().toggle("fast");
				});


// Jquery confim window on reset to defaults
jQuery('#parabola_defaults').click (function() {
		if (!confirm(reset_confirmation)) { return false;}
	});

// Jquery confim window on loading a color scheme
jQuery('#load-color-scheme').click (function() {
		if (!confirm(scheme_confirmation)) { return false;}
	});


// Hide or show slider settings
jQuery('#parabola_slideType').change(function() {
	jQuery('.slideDivs').hide();
	switch (jQuery('#parabola_slideType option:selected').val()) {

		case "Custom Slides" :
 		jQuery('#sliderCustomSlides').show("normal");
		break;

		case "Latest Posts" :
 		jQuery('#sliderLatestPosts').show("normal");
		break;

		case "Random Posts" :
 		jQuery('#sliderRandomPosts').show("normal");
		break;

		case "Sticky Posts" :
 		jQuery('#sliderStickyPosts').show("normal");
		break;

		case "Latest Posts from Category" :
 		jQuery('#sliderLatestCateg').show("normal");
		break;

		case "Random Posts from Category" :
 		jQuery('#sliderRandomCateg').show("normal");
		break;

		case "Specific Posts" :
 		jQuery('#sliderSpecificPosts').show("normal");
		break;

	}//switch
	
	sliderNr=jQuery('#parabola_slideType').val();
	//Show category if a category type is selected
	if (sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
			jQuery('#slider-category').show();
	else 	jQuery('#slider-category').hide();
	//Show number of slides if that's the case
	if (sliderNr=="Latest Posts" || sliderNr =="Random Posts" || sliderNr =="Sticky Posts" || sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
			jQuery('#slider-post-number').show();
	else 	jQuery('#slider-post-number').hide();

});//function

jQuery('.slideDivs').hide();
jQuery('#parabola_slideType').trigger('change');

//var parabola_customcss = CodeMirror.fromTextArea(document.getElementById("parabola_customcss"), { lineNumbers: true });
//var parabola_customjs = CodeMirror.fromTextArea(document.getElementById("parabola_customjs"), { lineNumbers: true });

// Create accordion from existing settings table
	jQuery('.form-table').wrap('<div>');
	jQuery(function() {
			jQuery( "#accordion" ).accordion({
				header: 'h3',
				autoHeight: false, // for jQueryUI <1.10
				heightStyle: "content", // required in jQueryUI 1.10
				collapsible: true,
				navigation: true,
				active: false
				});
	});

	jQuery("#parabola_nrcolumns").bind('change', function() {
		column_image_width_hint(jQuery("#totalsize").html(),jQuery("#parabola_nrcolumns").val());
	});										
	jQuery("#parabola_nrcolumns").trigger('change');
		
});// ready
  
// Columns image width hint
function column_image_width_hint(total, colcount) {
if (colcount==0) var size = 0;
else 
	var size = parseInt((total-(colcount*7*2)-(total*2*(colcount-1)/100))/colcount-14);

jQuery('#parabola_colimagewidth').html(size);

}

  // Change border for selecte inputs
function changeBorder (idName, className) {
	jQuery('.'+className).removeClass( 'checkedClass' );
	jQuery('.'+className).removeClass( 'borderful' );
	jQuery('#'+idName).addClass( 'borderful' );

return 0;
}