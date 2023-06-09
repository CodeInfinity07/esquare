	
	jQuery(document).ready(function(){

    // Get the modal
    var lcs_modal = jQuery("#leopards-courier-modal");
   

		jQuery('body').on('click','.leopards-courier-track-packet-btn',function(e){
        var tracking_number = jQuery(this).attr('data-lcstrack');
        jQuery.ajax({
            dataType: 'html',
            url: leopards_courier_vars.ajaxurl,
            data: {
                'leopards_tracking_no' : tracking_number,
                'action': 'leopards_track_packet'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                var json_stringify = JSON.parse(data);
                if(json_stringify['success'] == 0){
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert(json_stringify['message']);
                }else if (json_stringify['success'] == 1) {
                    var table_html = json_stringify['message']
                    lcs_modal.find('.leopards-tracking-details').html(table_html);
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal.show();
                }else {
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert("Please Contact Leopards Support");
                }

            },
        });
        
    });

		lcs_modal.find('.close').click(function(){
            lcs_modal.hide();
    });

});