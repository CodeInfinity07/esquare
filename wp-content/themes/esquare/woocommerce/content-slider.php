
<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
// if ( empty( $product ) || ! $product->is_visible() ) {
// 	return;
// }
?>
<div class="product-carousel">
	<div <?php wc_product_class( '', $product ); ?>>
	<?php

	echo "<div class='product--thumbnail'>";
	$result = woocommerce_template_loop_product_link_open();
	$result = woocommerce_template_loop_product_thumbnail(); 
	$result = woocommerce_template_loop_product_link_close(); 
	$result = woocommerce_show_product_loop_sale_flash(); 
	
	echo "</div>";
	
	echo "<div class='product--title'>";

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	//do_action( 'woocommerce_shop_loop_item_title' );
	echo "<div class='woocommerce-metaa'>";
	echo "<div class='categories d-flex'>";
	
	$product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );
		foreach ($product_cats  as $product_cat  ) {    
		echo '<span itemprop="name" class="product_category_title"><a href="'. get_home_url(). '/product-category/' .$product_cat->slug .'">' . $product_cat->name  . '</a>  </span>';
	
	}
	
	echo "</div>";
	$result = woocommerce_template_loop_rating(); 
	echo "</div>"; // woocommerce metaa enbd
	$result = woocommerce_template_loop_product_link_open();
    $result = woocommerce_template_loop_product_title();
	$result = woocommerce_template_loop_product_link_close(); 

	

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	//do_action( 'woocommerce_after_shop_loop_item_title' );
	echo "<div class='woocommerce-basketify'>";
    echo "<div class='item-price'>";
	$result = woocommerce_template_loop_price();
	echo "</div>";
	$result  = woocommerce_template_loop_add_to_cart( $args );
	echo "</div></div>";

	
	?>
	
</div>
</div>