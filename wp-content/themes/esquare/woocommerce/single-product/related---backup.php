<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related products woocommerce--custom  ">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );

		if ( $heading ) :
			?>
		
			<h2 class="widget-title"><span class="subs"><i class="icon-f-56"></i></span><span><?php echo esc_html( $heading ); ?></span></h2>
		<?php endif; ?>
		
		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

					<?php
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
					?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

	<section class="the-slider hot-sells-products woocommerce mt-4">
   <div class="container">

   <div class="row">
    <div class=" col-md-12">
            <div class="section-header">
                <h2 class="widget-title">Latest Products</h2>

                <a href="/shop/" class="btn btn-brand"> View all</a>
            </div>
    </div> 
    
  
    </div>
    <div class="sales-slider">
    <div class="row">

<!-- <div class="col-md-2 pr-0 mr-0">
  <div class="slider-banner">
    <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-banner-watches.jpg" alt="">
  </div>
</div> -->
<div class="col-md-12">
<div class="swiper ">
    <div class="woocommerce--custom product--slider">
            <?php
                $args =array( 'post_type' => 'product', 
                'stock' => 1, 
                'posts_per_page' => 15,
                //'product_cat' => 'smart-watches', 
                'orderby' =>'date',
                'order' => 'ASC' 
                );

                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) {
                    while ( $the_query->have_posts() ) : $the_query->the_post();

                        // Get default product template
                        wc_get_template_part( 'content', 'slider' );

                    endwhile;
                } else {
                    echo __( 'No products found' );
                }
                wp_reset_postdata();
            ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
 </div>
</div>
    </div>

   </div>



</section>
	<?php
endif;

wp_reset_postdata();
