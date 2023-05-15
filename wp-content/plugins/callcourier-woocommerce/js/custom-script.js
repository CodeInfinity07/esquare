jQuery(function(){
   jQuery("#fullfil-cc-order-link").click(function(){
       var order_id = jQuery(this).data('orderid');
       var jqxhr = jQuery.ajax( "http://localhost/woocommerce/wp-admin/options-general.php?page=callcourier-plugin&fullfilment=1&orderid="+order_id )
           .done(function(response) {
               console.log( response );
           })
           .fail(function() {
               //alert( "error" );
           })
           .always(function() {
               //alert( "complete" );
           });

// Perform other work here ...

// Set another completion function for the request above
       jqxhr.always(function() {
           //alert( "second complete" );
       });
   })
});
