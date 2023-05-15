var $lcs = jQuery.noConflict();

$lcs(function(){

    // Get the modal
    var lcs_modal = $lcs("#leopards-courier-modal"),
    lcs_modal_book_packet = $lcs("#leopards-courier-modal-book-packet"),
    lcs_modal_book_packet_btn = $lcs(".leopards-courier-book-packet_btn"),
    lcs_select_city = $lcs('#lcs_select_city'),
    lcs_select_shipment_type = $lcs('#lcs_select_shipment_type'),
    lcs_submit_book_packet_btn = $lcs('#lcs_submit_book_packet_btn'),
    lcs_default_price = $lcs('#lcs_default_price'),
    lcs_submit_book_packet_preview_btn = $lcs('#lcs_submit_book_packet_preview_btn'),
    leopards_courier_cancel_packet_btn = $lcs(".leopards-courier-cancel-packet-btn"),
    lcs_submit_book_packet_back_btn = $lcs("#lcs_submit_book_packet_back_btn"),
    lcs_bulk_preview_btn = $lcs(".lcs_bulk_preview_btn"),

    lcs_modal_load_sheet = $lcs(".lcs_load_sheet_btn"),
    lcs_modal_ls = $lcs("#leopards-courier-modal-load-sheet"),
    lcs_submit_load_sheet_btn = $lcs("#lcs_submit_load_sheet_btn"),
    lcs_sync_cities = $lcs("#lcs_sync_all_cities"),
    lcs_bulk_shipment = $lcs("#posts-filter"),
    lcs_bulk_shipment_btn = $lcs(".lcs_bulk_shipment_btn"),
    lcs_bulk_city_modal_dropdown = $lcs("#lcs_city_select"),
    lcs_bulk_shipment_modal_dropdown = $lcs(".shipment_input"),
    lcs_bulk_shipment_modal = $lcs("#lcs_bulk_shipment_modal");

    lcs_sync_cities.on('click',function(e){
        e.preventDefault();

        jQuery.ajax({
            dataType: 'html',
            url: ajaxurl,
            data: {
                'action': 'lcs_sync_all_cities'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
                $lcs('#leopards-courier-settings-sync_cities').closest('.forminp.forminp-text').append('<p id="lcs-syncing">Syncing..</p>');
            },
            success: function(data){
                jQuery( "#leopards-courier-loader" ).remove();
                $lcs( "#lcs-syncing" ).remove();
                $lcs('#leopards-courier-settings-sync_cities').closest('.forminp.forminp-text').append('<p id="lcs-synced">Cities are Synced.</p>');
            },
        });

    });

    lcs_modal_load_sheet.on('click',function(e){
        e.preventDefault();


        jQuery.ajax({
            dataType: 'html',
            url: ajaxurl,
            data: {
                'action': 'lcs_tracking_number_load_sheet'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                  jQuery( "#leopards-courier-loader" ).remove();
                  jQuery(".lcs_main_loadsheet_table").html(data);
                  lcs_modal_ls.show();
            
            },
        });

    });


    lcs_bulk_shipment.on('submit',function(e){
            
            var selected_option = $lcs("#bulk-action-selector-top").val();
            if(selected_option == 'lcs_bulk_shipment') {
            	var i=0;
                var array_order_ids = [];
                 $lcs('.post-type-shop_order .wp-list-table tbody input[type="checkbox"]:checked').each(function(){
                        array_order_ids.push(this.value);
                });
                $lcs('.leopards-bulk-track').show();
                $lcs('.preview-lcsdata').hide();
                $lcs('.bulk-preview-back-btn').hide();
                jQuery.ajax({
                    dataType: 'html',
                    url: ajaxurl,
                    data: {
                        'action': 'leopards_bulk_check_cities',
                        'array_order_ids':array_order_ids
                    },
                    beforeSend: function( xhr ) {
                        jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
                    },
                    success: function(data){
                        var json_stringify = JSON.parse(data);
                        //Check if user has selected the checkbox when clicking on apply button
                        if(json_stringify['cities_arr'] != '' || json_stringify['order_arr'] != '') {
                        var option_html = "";
                        var option_html_shipment = "";
                        var html_append = "";
                
                       
                        //By Default Option Value
                        option_html = '<option value="-1">Select City</option>';
                        
                        //Get all cities from array 
                        $lcs(json_stringify['cities_arr']).each(function(key, value){
                            option_html += '<option data-shipment-type = "'+ value.shipment_type.join() +'" value="'+value.city_id +'">'+value.city_value+'</option>';
                             
                        });
                        var shipment_option_html = '';

                            $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table')

                            if($lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table thead th.lcs_cities').length < 1){
                                $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table thead tr').append('<th class="lcs_cities">LCS City</th>');
                                $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table thead tr').append('<th class="lcs_shipment">Shipment Type</th>');
                                $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table thead tr').append('<th class="lcs_status">Status</th>');

                            }

                         //Get all shipment from array 
                        var i = 0;
                        
                        //Get order data from array to show in modal
                        $lcs(json_stringify['order_arr']).each(function(key, value){

                            var order_id = value.order_id;
                            var order_url = value.order_url;
                            var customer_city_name = value.city_name;
                            var is_cancelled = value.is_cancelled;
                            var actual_price = value.price;
                            var customer_name = value.customer_name;
                            $lcs('th.lcs_cities').show();
                            $lcs('th.customer_cities').show();
                            $lcs('th.lcs_status').show();
                            //If city does not match then add class
                            if(value.is_match == 0) {
                                var bg_class = 'bg-error';
                                var status = '<mark class="order-status status-unmatched tips"><span>Cities UnMatched</span></mark>';

                             $lcs('th.lcs_cities').show();
                             $lcs('th.customer_cities').show();
                             $lcs('th.lcs_status').show();
                             $lcs('th.lcs_shipment').text("Shipment Type");

                            }else {
                                var bg_class = '';
                                var status = '<mark class="order-status status-matched tips"><span>Cities Matched</span></mark>';
                            }


                            if(is_cancelled == 1) {
                            
                                var html_append = $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table tbody').append('<tr class="table-row"><td><a href="'+ order_url +'" target="_blank"><strong class="lcs_order_id">'+ order_id +'</strong></a></td><td>'+ customer_city_name.toUpperCase() +'</td><td>'+customer_name.toUpperCase()+'</td><td><select name="lcs_city_action" class= "city_input" id="lcs_city_select_'+ i +'">'+option_html.toUpperCase()+'</select></td><td><select name="lcs_shipment_action" class= "shipment_input" id="lcs_shipment_select_'+ i +'"><option>Select Shipment</option></select></td><td><input type="number" name="actual_price" value="'+actual_price+'" min="1" max="999999"></td><td class="column-order_total">'+status+'</td><td><input class="lcs_bulk_preview_btn" type="button" value="Preview" data-orderid="'+order_id+'"></td></tr>');

                            

                            }else{
                                var html_append = $lcs('#lcs_bulk_shipment_modal .leopards-bulk-track table tbody').append('<tr class="table-row"><td><a href="'+ order_url +'" target="_blank"><strong class="lcs_order_id">'+ order_id +'</strong></a></td><td class="ship_created">Shipment already created</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');


                            $lcs('.select_msg').remove();

                     

                            $lcs(".lcs_bulk_shipment_btn").hide();

                           }

                            jQuery("#lcs_city_select_"+ i +" option").each(function () {

                                if(customer_city_name.toLowerCase() == jQuery(this).text().toLowerCase()){
                                     $lcs(this).attr('selected', true);
                                     $lcs(this).parent().attr('disabled', true);
                                     
                             //change when city already matched
                                var lcs_shipment_type_text = "";
                                var lcs_shipment_type_text = $lcs(this).attr('data-shipment-type');
                                var lcs_shipment_type = lcs_shipment_type_text.split(',');
                                var lcs_shipment_options = '';
                                $lcs.each(lcs_shipment_type, function(key,val){
                                    var isSelected =  (val == 'OVERLAND') ? 'selected' : '';
                                    lcs_shipment_options += '<option value="'+val+'"  '+isSelected+' >'+val+'</option>';                                    
                                });
                                lcs_bulk_shipment_modal_dropdown.find('option').remove();
                                $lcs(this).parents('tr').find(".shipment_input").append(lcs_shipment_options); 
                                
                                
        //end here   
                                     
                                }
                            });
                            

                            i++;
                        });
                        
                          
                         $lcs('.city_input').on('change',function(){
                                var lcs_shipment_type_text = "";
                                var lcs_shipment_type_text = $lcs(this).children("option:selected").attr('data-shipment-type');

                               if(typeof lcs_shipment_type_text !== typeof undefined && lcs_shipment_type_text !== false){
                                // jQuery('.select_msg').show();
                                var lcs_shipment_type = lcs_shipment_type_text.split(',');
                                var lcs_shipment_options = '';
                                $lcs.each(lcs_shipment_type, function(key,val){
                                    lcs_shipment_options += '<option value="'+val+'">'+val+'</option>';
                                    
                                });
                                lcs_bulk_shipment_modal_dropdown.find('option:first-child').remove();
                                // $lcs(this).parent().find(lcs_bulk_shipment_modal_dropdown).html(lcs_shipment_options);
                                $lcs(this).parents('tr').find(".shipment_input").html(lcs_shipment_options);
                                $lcs(".shipment_input option[value=OVERNIGHT] ").prop('selected', true);
                               }
                                
                        });


                        $lcs(".city_input").on('change',function(){
                                mytesting();
                                actual_price_validation();
                        }); 
                        $lcs("input[name='actual_price']").on('keyup',function(){
                            actual_price_validation();
                        });
                        // $lcs("#lcs_default_price").on('keyup',function(){
                        //     if(this.value.length > 6) {
                        //         alert("Price doesn't exceed 6 digits");
                        //     }
                        // });

                       
                        
                     $lcs(".lcs_bulk_shipment_btn").show().attr('disabled', true);
                      $lcs('.select_msg').show();
                       // $lcs(".lcs_bulk_shipment_btn").show();
                        jQuery( "#leopards-courier-loader" ).remove();
                        lcs_bulk_shipment_modal.show();
                        
                         jQuery(".city_input").trigger('change');
                         

                         
                    } else {
                        alert("Please select the checkbox to create bulk shipment");
                        jQuery( "#leopards-courier-loader" ).remove();
                    }
                    },
                });
                return false;
            } else{
               return true;
            }
    });

    function actual_price_validation(){
        $lcs("input[name='actual_price']").each(function() {
            if($lcs(this).val() == '' || $lcs(this).val() < 0) {
                $lcs(".lcs_bulk_shipment_btn").attr('disabled', true);
                return false;
            } else if($lcs(this).val().toString().split(".")[0].length > 6) {
                $lcs(".lcs_bulk_shipment_btn").attr('disabled', true);
                alert("Price should not exceed 6 digits");
                return false;
            }else {
                $lcs(".lcs_bulk_shipment_btn").attr('disabled', false);
                $lcs(".lcs_bulk_shipment_btn").addClass('create-btn');
                $lcs('.select_msg').hide();
                $lcs(".lcs_bulk_shipment_btn").show();
            }
        });

    }

function mytesting(){
    $lcs(".city_input").each(function() {
        if($lcs(this).val() == -1 ) {
            $lcs(".lcs_bulk_shipment_btn").attr('disabled', true);
            return false;
        }else{
            $lcs(".lcs_bulk_shipment_btn").attr('disabled', false);
            $lcs(".lcs_bulk_shipment_btn").addClass('create-btn');
            $lcs('.select_msg').hide();
            $lcs(".lcs_bulk_shipment_btn").show();

        }
    });

}

     var table_data_array = [];
     var table_row_value = [];
    jQuery('.lcs_bulk_shipment_btn').click(function(){
        table_data_array = [];
        $lcs(".table-row").each(function(){
            var each_order_id = $lcs(this).find('.lcs_order_id').text();
            var each_city_value = $lcs(this).find('.city_input option:selected').val();
            var each_shipment_value = $lcs(this).find('.shipment_input option:selected').val();
            var each_shipment_price = parseFloat($lcs(this).find('input[name="actual_price"]').val()).toFixed(2);
            // if(each_shipment_price == ''){
            //     $lcs(this).find('input[name="actual_price"]').closest('td').append('<p>Please add the price, it should not be empty</p>');
            //     return false;
            // }
            var table_row_value = [
                {order_id: each_order_id , city_value: each_city_value, shipment_value: each_shipment_value, shipment_price: each_shipment_price }
            ];

            table_data_array.push(table_row_value);
        });
    jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                'action': 'leopards_book_packet_modal',
                'table_data_array' : table_data_array
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                jQuery( "#leopards-courier-loader" ).remove();
                var json_stringify = JSON.parse(data);
                $lcs("#lcs_bulk_shipment_modal .leopards-bulk-track .table-row").remove();
                if(jQuery.isArray(json_stringify)) {
                    $lcs(json_stringify).each(function(key,value){
                        var response_order_id = value.order_id;
                        var response_message = value.message;
                        var response_success = value.success;
                    $lcs("#lcs_bulk_shipment_modal .leopards-bulk-track tbody").append('<tr class="table-row"><td><strong class="lcs_order_id">'+ response_order_id +'</strong></td><td>'+ response_message +'</td></tr>');
    

                    
                     $lcs('.select_msg').remove();
                     $lcs('th.customer_cities').text("Shipment Status");
                     $lcs('th.lcs_cities').remove();
                     $lcs('th.lcs_shipment').remove();
                     $lcs('th.lcs_status').remove();

                });





                } else {
                    if(json_stringify['success'] == 0) {
                      $lcs("#lcs_bulk_shipment_modal .leopards-bulk-track").append('<div><p>'+json_stringify['message']+'</p></div>');
                       $lcs('.leopards-bulk-track table thead tr').append('<tr><th></th></tr>');
                    }

                }
                
                $lcs(".lcs_bulk_shipment_btn").hide();
            },
        });
    });


            lcs_bulk_shipment_modal.find('.close').click(function(){
                lcs_bulk_shipment_modal.hide();
         

                $lcs(".lcs_bulk_shipment_btn").hide();
                $lcs(".table-row").remove();
                $lcs(".lcs_response").remove();
            });

            lcs_modal_ls.find('.close').click(function(){
                lcs_modal_ls.hide();
             });


            lcs_modal_book_packet_btn.on('click',function(e){
                e.preventDefault();
                $lcs('.preview-lcsdata').hide();
                $lcs('.step-1').show();
                $lcs('#lcs_submit_book_packet_back_btn').hide();
                $lcs('#lcs_submit_book_packet_btn').hide();
                $lcs('#lcs_submit_book_packet_preview_btn').show();
                lcs_modal_book_packet.show();
                jQuery("#lcs_select_city").trigger('change');
            });

            lcs_modal_book_packet.find('.close').click(function(){
                lcs_modal_book_packet.hide();
    });


            lcs_modal.find('.close').click(function(){
            lcs_modal.hide();
    });



        $lcs('body').on('change','#'+lcs_select_city.attr('id'),function(){
            var lcs_shipment_type_text = lcs_select_city.children("option:selected").attr('data-ship-type');
            var lcs_shipment_type = lcs_shipment_type_text.split(',');
            var lcs_shipment_options = '';
            $lcs.each(lcs_shipment_type, function(key,val){
                lcs_shipment_options += '<option value="'+val+'">'+val+'</option>';
            });
            lcs_select_shipment_type.find('option').remove();
            lcs_select_shipment_type.html(lcs_shipment_options);
    });

        $lcs('body').on('click','#'+lcs_submit_book_packet_btn.attr('id'),function(e){
                
                var selected_city = lcs_select_city.val();
                var selected_shipment_type = lcs_select_shipment_type.val();
                var current_order_id =  lcs_submit_book_packet_btn.attr('data-orderid');
                var actual_price = parseFloat(lcs_default_price.val()).toFixed(2);
        jQuery.ajax({
            dataType: 'html',
            url: ajaxurl,
            data: {
                'leopards_order_id' : current_order_id, 
                'leopards_destination_city' : selected_city, 
                'selected_shipment_type' : selected_shipment_type, 
                'price' :  actual_price,
                'action': 'leopards_book_packet'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                var json_stringify = JSON.parse(data);
                if(json_stringify['success'] == 0){
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal_book_packet.find('.error-show').html(json_stringify['message']);
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                }else if (json_stringify['success'] == 1) {
                    window.location.reload();
                }else {
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal_book_packet.find('.error-show').html('Please Contact Leopards Support');
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                }

            },
        });
        
    });

    // On clicking preview button
    $lcs('body').on('click','#'+lcs_submit_book_packet_preview_btn.attr('id'),function(e){
        var selected_city = lcs_select_city.val();
        var selected_shipment_type = lcs_select_shipment_type.val();
        var current_order_id =  lcs_submit_book_packet_preview_btn.attr('data-orderid');
        var order_price = lcs_default_price.val();
        var actual_price = '';
        if(order_price != '') {
            var actual_price = parseFloat(lcs_default_price.val()).toFixed(2);
        }
        if(selected_city != 'Select City' && selected_shipment_type != '' && actual_price != '' && actual_price.toString().split(".")[0].length < 7 && order_price > 0) {
            jQuery.ajax({
                dataType: 'html',
                url: ajaxurl,
                data: {
                    'leopards_order_id' : current_order_id, 
                    'leopards_destination_city' : selected_city, 
                    'selected_shipment_type' : selected_shipment_type,
                    'price' :  actual_price,
                    'action': 'leopards_book_preview_packet'
                },
                beforeSend: function( xhr ) {
                    jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
                },
                success: function(data){
                    var json_stringify = JSON.parse(data);
                    console.log(json_stringify);
                    var items = '';
                    $lcs(json_stringify['data']['items']['item_name']).each(function(key, item_name){
                        items += item_name+'<br>';
                    })
                    if(json_stringify['success']){
                        jQuery( "#leopards-courier-loader" ).remove();
                        jQuery('#'+lcs_submit_book_packet_back_btn.attr('id')).show();
                        jQuery('#leopards-courier-modal-book-packet .step-1').hide();
                        jQuery(".preview-lcsdata").remove();
                        jQuery('#'+lcs_submit_book_packet_preview_btn.attr('id')).hide();
                        jQuery("#"+lcs_submit_book_packet_btn.attr('id')).show();
                        jQuery('#leopards-courier-modal-book-packet .modal-content .lcs-modal-inner-content').prepend('<table class="preview-lcsdata" style="width:100%"><tr><th>Order ID:</th><td>'+json_stringify['data']['booked_packet_order_id']+'</td></tr><tr><th>Name:</th><td>'+json_stringify['data']['consignment_name_eng']+'</td></tr><tr><th>Email:</th><td>'+json_stringify['data']['consignment_email']+'</td></tr><tr><th>Address:</th><td>'+json_stringify['data']['consignment_address']+'</td></tr><tr><th>Phone:</th><td>'+json_stringify['data']['consignment_phone']+'</td></tr><tr><th>Packet Amount:</th><td>'+json_stringify['data']['booked_packet_collect_amount']+'</td></tr><tr><th>Packet No of Piece:</th><td>'+json_stringify['data']['booked_packet_no_piece']+'</td></tr><tr><th>Packet Weight:</th><td>'+json_stringify['data']['booked_packet_weight']+'</td></tr><tr><th>Shipment Address:</th><td>'+json_stringify['data']['shipment_address']+'</td></tr><tr><th>Shipment Email:</th><td>'+json_stringify['data']['shipment_email']+'</td></tr><tr><th>Shipment Name:</th><td>'+json_stringify['data']['shipment_name_eng']+'</td></tr><tr><th>Shipment Phone:</th><td>'+json_stringify['data']['shipment_phone']+'</td></tr><tr><th>Shipment Type:</th><td>'+json_stringify['data']['shipment_type']+'</td></tr><tr><th>Special Instructions:</th><td>'+json_stringify['data']['special_instructions']+'</td></tr><tr><th>Items:</th><td>'+items+'</td></tr></table>');
                    }
                },
            });
        } else if(selected_city == 'Select City'){
            alert("Select Destination City");
        } else if(actual_price.toString().split(".")[0].length > 6 || actual_price == '' ){
            alert("Price should not exceed 6 digits and should not be empty");
        } else if(order_price < 0) {
            alert("Price should not be negative");
        }else {
            alert("Something went wrong");
        }
    });

    //Bulk Preview Button
    $lcs('body').on('click','.lcs_bulk_preview_btn',function(e){
        var current_order_id =  $lcs(this).attr('data-orderid');
        var lcs_city = $lcs(this).closest('tr.table-row').find('.city_input option:selected').val();
        var shipment_type = $lcs(this).closest('tr.table-row').find('.shipment_input option:selected').val();
        var shipment_price = parseFloat($lcs(this).closest('tr.table-row').find('input[name="actual_price"]').val()).toFixed(2);

        if(current_order_id != '' && lcs_city != '-1' && shipment_type !='' && shipment_price != '') {

            jQuery.ajax({
                dataType: 'html',
                url: ajaxurl,
                data: {
                    'leopards_order_id' : current_order_id, 
                    'leopards_destination_city' : lcs_city, 
                    'selected_shipment_type' : shipment_type,
                    'price' :  shipment_price,
                    'action': 'leopards_bulk_preview_packet'
                },
                beforeSend: function( xhr ) {
                    jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
                },
                success: function(data){
                    var json_stringify = JSON.parse(data);
                    var items = '';
                    $lcs(json_stringify['data']['items']['item_name']).each(function(key, item_name){
                        items += item_name+'<br>';
                    })
                    if(json_stringify['success']){
                        jQuery( "#leopards-courier-loader" ).remove();
                        jQuery('.leopards-bulk-track').hide();
                        jQuery('#lcs_bulk_shipment_modal .modal-content').append('<table class="preview-lcsdata" style="width:100%"><tr><th>Order ID:</th><td>'+json_stringify['data']['booked_packet_order_id']+'</td></tr><tr><th>Name:</th><td>'+json_stringify['data']['consignment_name_eng']+'</td></tr><tr><th>Email:</th><td>'+json_stringify['data']['consignment_email']+'</td></tr><tr><th>Address:</th><td>'+json_stringify['data']['consignment_address']+'</td></tr><tr><th>Phone:</th><td>'+json_stringify['data']['consignment_phone']+'</td></tr><tr><th>Packet Amount:</th><td>'+json_stringify['data']['booked_packet_collect_amount']+'</td></tr><tr><th>Packet No of Piece:</th><td>'+json_stringify['data']['booked_packet_no_piece']+'</td></tr><tr><th>Packet Weight:</th><td>'+json_stringify['data']['booked_packet_weight']+'</td></tr><tr><th>Shipment Address:</th><td>'+json_stringify['data']['shipment_address']+'</td></tr><tr><th>Shipment Email:</th><td>'+json_stringify['data']['shipment_email']+'</td></tr><tr><th>Shipment Name:</th><td>'+json_stringify['data']['shipment_name_eng']+'</td></tr><tr><th>Shipment Phone:</th><td>'+json_stringify['data']['shipment_phone']+'</td></tr><tr><th>Shipment Type:</th><td>'+json_stringify['data']['shipment_type']+'</td></tr><tr><th>Special Instructions:</th><td>'+json_stringify['data']['special_instructions']+'</td></tr><tr><th>Items:</th><td>'+items+'</td></tr></table><input type="button" class="button bulk-preview-back-btn" value="Back">');
                    }
                },
            });
        } else {
            alert('All fields are required before previewing the order details');
        }
    });

    $lcs('body').on('click','.bulk-preview-back-btn',function(){
        jQuery('.preview-lcsdata').hide();
        jQuery('.leopards-bulk-track').show();
        jQuery(this).hide();
    });
    
    $lcs('body').on('click','#'+lcs_submit_book_packet_back_btn.attr('id'),function(e){
        jQuery('#leopards-courier-modal-book-packet .step-1').show();
        jQuery('#lcs_submit_book_packet_back_btn').hide();
        jQuery('#lcs_submit_book_packet_btn').hide();
        jQuery('#lcs_submit_book_packet_preview_btn').show();
        jQuery(".preview-lcsdata").hide();
    });

    $lcs('body').on('click','.leopards-courier-cancel-packet-btn',function(e){
        
        var tracking_number = $lcs(this).attr('data-lcscancel');
        jQuery.ajax({
            dataType: 'html',
            url: ajaxurl,
            data: {
                'leopards_tracking_no' : tracking_number,
                'action': 'leopards_cancel_packet'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                var json_stringify = JSON.parse(data);
                if(json_stringify['success'] == 0){
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert(json_stringify['message'][tracking_number]);
                }else if (json_stringify['success'] == 1) {
                    window.location.reload();
                }else {
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert("Please Contact Leopards Support");
                }

            },
        });
        
    });



    $lcs('body').on('click','.leopards-courier-track-packet-btn',function(e){
        
        var tracking_number = $lcs(this).attr('data-lcstrack');
        jQuery.ajax({
            dataType: 'html',
            url: ajaxurl,
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


})


	 jQuery(document).ready(function(){
     jQuery('body').on('click','#lcs_submit_load_sheet_btn',function(e){
		  
				var active_track_no = jQuery(".track-id").val();
		        var leopards_courier_code = jQuery("#courier_code").val();
		        var leopards_courier_name = jQuery("#courier_name").val();
		        var lcs_modal_ls = jQuery("#leopards-courier-modal-load-sheet"); 
                
               var tracking_ids='';

                jQuery('input.track-id:checked').each(function () {
                       var sThisVal = (this.checked ? jQuery(this).val() : "");
                        if(tracking_ids == ''){
                        tracking_ids = tracking_ids + sThisVal;
                        }else{
                        tracking_ids = tracking_ids + ','+ sThisVal;
                        }
                });   
                if(leopards_courier_code == '' || leopards_courier_name == ''){
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal_ls.find('.error-show').html('please Enter "Courier Name" and  "Courier Code"');
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                    return;
                }
                var OneIsChecked = tracking_ids.length > 0;    


        if(OneIsChecked != 0) {
       
       jQuery.ajax({
            dataType: 'json',
            url: ajaxurl,
            data: {
            	'leopards_track_id' : tracking_ids, 
                'leopards_courier_code' : leopards_courier_code, 
                'leopards_courier_name' : leopards_courier_name,
                'action': 'leopards_lcs_create_sheet'
            },


          beforeSend: function( xhr ) {
      		jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },

            success: function(data){
                var json_stringify = data;
                if(json_stringify['success'] == 0){
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal_ls.find('.error-show').html(json_stringify['message']);
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                }
                else if (json_stringify['success'] == 1) {
             
                    jQuery(".lcs-modal-inner-content ul").remove();
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                      lcs_modal_ls.find('.error-show').html(json_stringify['message']);
                     jQuery( "#lcs_submit_load_sheet_btn" ).remove();
                     jQuery( "#leopards-courier-loader" ).hide();
                     window.location.reload();

                }
                else {
                    jQuery( "#leopards-courier-loader" ).remove();
                    lcs_modal_ls.find('.error-show').html('Please Contact Leopards Support');
                    $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
                }

            },
        });

 }else {
        jQuery( "#leopards-courier-loader" ).remove();
        lcs_modal_ls.find('.error-show').html('Please select tracking number');
        $lcs('.leopards-courier-modal .lcs-modal-inner-content .error-show').show();
 }
		  });
		});


         jQuery(document).ready(function () {
          
            jQuery('#show').on('click', function () {
                jQuery('.center').show();
                jQuery$(this).hide();
            })

            jQuery('#close').on('click', function () {
                jQuery('.center').hide();
                jQuery$('#show').show();
        })
        
    });



        jQuery(document).ready(function(){

       $lcs('body').on('click','.lcs-download-btn',function(e){
        
        var load_sheet_id = $lcs(this).attr('data-load-id');

        jQuery.ajax({
            dataType: 'json',
            url: ajaxurl,
            data: {
            'leopards_load_sheet_id' : load_sheet_id,
            'action': 'lcs_download_pdf'
            },
            beforeSend: function( xhr ) {
                jQuery('body').prepend('<div id="leopards-courier-loader"><center><img  src="'+leopards_courier_vars.pluginurl+'/assets/images/loading_img.gif"></center></div>');
            },
            success: function(data){
                var json_stringify = data;

               
                if(json_stringify['success'] == 0){
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert(json_stringify['message']);
                }else if (json_stringify['success'] == 1) {
                    
                    jQuery("#leopards-courier-loader").remove();
                    
                    url = json_stringify['data']['url'];
                    window.open(url);

                }else {
                    jQuery( "#leopards-courier-loader" ).remove();
                    alert("Please Contact Leopards Support");
                }

            },
        });
        
    });


})

        
        jQuery(document).ready(function(){


           jQuery('.lcs-load-table table', '.lcs-load-table table').each(function(i) {
          jQuery(this).text(i+1);
    });


    jQuery('.lcs-load-table.paginated').each(function() {
        var currentPage = 0;
        var numPerPage = 9;
        var $table = jQuery(this);
        $table.bind('repaginate', function() {
            $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
        });
        $table.trigger('repaginate');
        var numRows = $table.find('tbody tr').length;
        var numPages = Math.ceil(numRows / numPerPage);
        var $pager = jQuery('<div class="lcs-pager"></div>');
        for (var page = 0; page < numPages; page++) {
            jQuery('<span class="page-number"></span>').text(page + 1).bind('click', {
                newPage: page
            }, function(event) {
                currentPage = event.data['newPage'];
                $table.trigger('repaginate');
                jQuery(this).addClass('active').siblings().removeClass('active');
            }).appendTo($pager).addClass('clickable');
        }
        $pager.insertBefore($table).find('span.page-number:first').addClass('active');
});


})


      jQuery(document).ready(function(){


        // Change the selector if needed
var $table = jQuery('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
jQuery(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return jQuery(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        jQuery(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler

})