<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<header class="woosingle-product-page">
<div class="breadcrumb--wrapper d-flex justify-content-end">
				  <div class="container">
					  <div class="row">
						  <div class="col-md-12">
						  <?php
						$args = array(
								'delimiter' => ' \</li>',
								'before' => ' <li class="d-flex align-items-center"><span class="breadcrumb-title"></span>'
								//'after' => '</ul>'	
						);
					?>
					<?php woocommerce_breadcrumb( $args ); ?>
						  </div>
					  </div>
				  </div>
				  </div>
					</div>
					</header>

					<div class="woocommerce--single--productwrapper">
						<div class="container">
							<div class="row">
								
								<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'col-md-12 mb-5', $product ); ?>>

<?php
/**
 * Hook: woocommerce_before_single_product_summary.
 *
 * @hooked woocommerce_show_product_sale_flash - 10
 * @hooked woocommerce_show_product_images - 20
 */
do_action( 'woocommerce_before_single_product_summary' );
?>

<div class="summary entry-summary">
	<?php
	/**
	 * Hook: woocommerce_single_product_summary.
	 *
	 * @hooked woocommerce_template_single_title - 5
	 * @hooked woocommerce_template_single_rating - 10
	 * @hooked woocommerce_template_single_price - 10
	 * @hooked woocommerce_template_single_excerpt - 20
	 * @hooked woocommerce_template_single_add_to_cart - 30
	 * @hooked woocommerce_template_single_meta - 40
	 * @hooked woocommerce_template_single_sharing - 50
	 * @hooked WC_Structured_Data::generate_product_data() - 60
	 */
	do_action( 'woocommerce_single_product_summary' );


	?>
</div>
					</div>
							</div>
						</div>
						<section class="woocommerce--product-content">
						<div class="container">
							<div class="row">
<div class="col-md-12 mt-3">
<?php
/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
do_action( 'woocommerce_after_single_product_summary' );
?>
</div>

								</div>
							</div>
						</div>
						</section>
						<section class="the-slider hot-sells-products woocommerce mt-4">
   <div class="container">

    <div class="row">
        <div class=" col-md-12">
            <div class="section-header">
                <h2 class="widget-title">Related Products</h2>
                <a href="$category_link" class="btn btn-brand"> View all</a>
            </div>
        </div> 
    </div>
    <div class="sales-slider">
    <div class="row">
                <div class="col-md-12">
                <div class="swiper ">
                    <div class="woocommerce--custom product--slider-full-width">
                            <?php

                global $post;
               
                $terms = get_the_terms( $post->ID, 'product_cat' );
                $nterms = get_the_terms( $post->ID, 'product_tag'  );
                foreach ($terms  as $term  ) {
                    $product_cat_id = $term->term_id;
                    $product_cat_name = $term->name;
                    $product_slug_name = $term->slug;
                    break;
                }
                $category_link = get_category_link( $product_cat_id );


                                $args =array( 'post_type' => 'product', 
                                // 'stock' => 1, 
                                'posts_per_page' => 15,
                                'product_cat' =>  $product_slug_name, 
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
<div class="col-md-12">
<div class="swiper ">
    <div class="woocommerce--custom product--slider-full-width">
            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
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
					</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
