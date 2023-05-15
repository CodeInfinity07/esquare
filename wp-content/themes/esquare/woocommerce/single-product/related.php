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

?>

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
                    <div class="woocommerce--custom product--slider">
                            <?php

                global $post;
                $terms = get_the_terms( $post->ID, 'product_cat' );
                $nterms = get_the_terms( $post->ID, 'product_tag'  );
                foreach ($terms  as $term  ) {
                    $product_cat_id = $term->term_id;
                    $product_cat_name = $term->name;
                    
                    break;
                }
                $category_link = get_category_link( $product_cat_id );


                                $args =array( 'post_type' => 'product', 
                                // 'stock' => 1, 
                                'posts_per_page' => 15,
                                'product_cat' =>  $product_cat_name, 
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
                'stock' => 1, 
                'posts_per_page' => 15,
                //'product_cat' => 'smart-watches', 
                'orderby' =>'date',
                'order' => 'DESC' 
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

