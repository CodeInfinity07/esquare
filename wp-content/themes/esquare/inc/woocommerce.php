<?php
/**
 * Add WooCommerce support
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'after_setup_theme', 'esquare_woocommerce_support' );
if ( ! function_exists( 'esquare_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function esquare_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add New Woocommerce 3.0.0 Product Gallery support.
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'esquare_wc_form_field_args', 10, 3 );
	}
}

/**
* First unhook the WooCommerce wrappers
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
* Then hook in your own functions to display the wrappers your theme requires
*/
// add_action( 'woocommerce_before_main_content', 'esquare_woocommerce_wrapper_start', 10 );
// add_action( 'woocommerce_after_main_content', 'esquare_woocommerce_wrapper_end', 10 );
// if ( ! function_exists( 'esquare_woocommerce_wrapper_start' ) ) {
// 	function esquare_woocommerce_wrapper_start() {
// 		$container = get_theme_mod( 'esquare_container_type' );
// 		echo '<div class="wrapper" id="woocommerce-wrapper">';
// 		echo '<div class="' . esc_attr( $container ) . '" id="content" tabindex="-1">';
// 		echo '<div class="row">';
// 		//get_template_part( 'global-templates/left-sidebar-check' );
// 		echo '<main class="site-main" id="main">';
// 	}
// }
// if ( ! function_exists( 'esquare_woocommerce_wrapper_end' ) ) {
// 	function esquare_woocommerce_wrapper_end() {
// 		echo '</main><!-- #main -->';
// 		//get_template_part( 'global-templates/right-sidebar-check' );
// 		echo '</div><!-- .row -->';
// 		echo '</div><!-- Container end -->';
// 		echo '</div><!-- Wrapper end -->';
// 	}
// }


// add image size
add_image_size( 'thumbnail-products', 300, 300 );

/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
if ( ! function_exists( 'esquare_wc_form_field_args' ) ) {
	function esquare_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			/* Targets all select input type elements, except the country and state select input types */
			case 'select':
				// Add a class to the field's html element wrapper - woocommerce
				// input types (fields) are often wrapped within a <p></p> tag.
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class']       = array( 'form-control', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
					// Add custom data attributes to the form input itself.
				);
				break;
			// By default WooCommerce will populate a select with the country names - $args
			// defined for this specific input type targets only the country select element.
			case 'country':
				$args['class'][]     = 'form-group single-country';
				$args['label_class'] = array( 'control-label' );
				break;
			// By default WooCommerce will populate a select with state names - $args defined
			// for this specific input type targets only the country select element.
			case 'state':
				// Add class to the field's html element wrapper.
				$args['class'][] = 'form-group';
				// add class to the form input itself.
				$args['input_class']       = array( '', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password':
			case 'text':
			case 'email':
			case 'tel':
			case 'number':
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'textarea':
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'checkbox':
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			case 'radio':
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			default:
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
		} // end switch ($args).
		return $args;
	}
}

// add wrapper to woocommerce product thumbnails

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {   
    function woocommerce_get_product_thumbnail( $size = 'thumbnail-products', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;
		
        echo '<div class="imagewrapper">';

        if ( has_post_thumbnail() ) {               
            echo get_the_post_thumbnail( $post->ID, 'thumbnail-products' ,["class" => "primary-image","alt"=>"some"]);  
			            
        } 

		// $image_id = wc_get_product()->get_gallery_image_ids()[0] ; 
        // if ( $image_id ) {

		// 	//echo wp_get_attachment_image( $image_id ) ;


		// 	echo wp_get_attachment_image( $image_id, 'thumbnail-products','', ["class" => "secendory-image","alt"=>"some"]); 
	
		// } else {  //assuming not all products have galleries set
		// 	$id = wc_get_product()->get_image_id();
		// 	echo wp_get_attachment_image($id ,'thumbnail-products','',["class" => "secendory-image","alt"=>"some"]) ; 
	
		// }               
		echo  '</div>';
        
    }
}


// ajaxify count on cart
add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );

function iconic_cart_count_fragments( $fragments ) {
    
    $fragments['div.header-cart-count'] = '<div class="count-items">' . WC()->cart->get_cart_contents_count() . '</div>';
    
    return $fragments;
    
}

// change woocommerce add to cart button


add_action( 'woocommerce_sale_flash', 'sale_badge_percentage', 25 );
 
function sale_badge_percentage() {
   global $product;
   if ( ! $product->is_on_sale() ) return;
   if ( $product->is_type( 'simple' ) ) {
      $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
   } elseif ( $product->is_type( 'variable' ) ) {
      $max_percentage = 0;
      foreach ( $product->get_children() as $child_id ) {
         $variation = wc_get_product( $child_id );
         $price = $variation->get_regular_price();
         $sale = $variation->get_sale_price();
         if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;
         if ( $percentage > $max_percentage ) {
            $max_percentage = $percentage;
         }
      }
   }
   if ( $max_percentage > 0 ) echo "<span class='onsale'>-" . round($max_percentage) . "%</span>"; // If you would like to show -40% off then add text after % sign
}


// conditionally display woocommece product ratings

add_filter('woocommerce_product_get_rating_html',function ( $html, $rating, $count){
	$label = sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $rating );
	$html  ='<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . wc_get_star_rating_html( $rating, $count ) . '</div>';
	return $html;
},9999,3);


// cuztomizing the woocommerce rating star icons
add_filter('woocommerce_get_star_rating_html', 'replace_star_ratings', 10, 2);
function replace_star_ratings($html, $rating) {
    $html = ""; // Erase default HTML
    for($i = 0; $i < 5; $i++) {
        $html .= $i < $rating ? '<i class="icon-star-dark"></i>' : '<i class="icon-star"></i>';
    }
    return $html;
}

/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );