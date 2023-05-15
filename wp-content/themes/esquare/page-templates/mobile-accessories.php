<?php
/**
 * Template Name: Mobile Accessories
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package esquare
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
$container = get_theme_mod( 'esquare_container_type' );
?>
<header class="woosingle-product-page woocommerce woopage">
    <div class="breadcrumb--wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12  d-flex flex-column justify-content-center text-center">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <?php
						$args = array(
								'delimiter' => '<i class="icon-g-09"></i></li>',
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
<section class="the-slider hot-sells-products woocommerce mt-5">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Mobile Accessories</h2>
                    <a href="/product-category/mobile-accessories/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/banner-mobile-accessories.jpg"
                            alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 30,
                'product_cat' => 'Mobile Accessories', 
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
<section class="the-slider hot-sells-products woocommerce ">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Chargers</h2>
                    <a href="/product-category/chargers/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-chargers.jpg"
                            alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 15,
                'product_cat' => 'Earbuds', 
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

<section class="the-slider hot-sells-products woocommerce">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Headphones</h2>
                    <a href="/product-category/earbuds/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-banner-headphones.jpg"
                            alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 15,
                'product_cat' => 'headphones', 
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


<!-- <section class="the-slider hot-sells-products woocommerce">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Mobile Accessories</h2>
                    <a href="/product-category/mobile-accessories/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/banner-mobile-accessories.jpg"
                            alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 15,
                'product_cat' => 'Mobile Accessories', 
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
</section> -->

<!-- <section class="products--listing--home">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Latest Products</h2>
                </div>
            </div>
            <div class="col-md-12">
                <div class="woocommerce--custom">
                    <?php echo do_shortcode('[products limit="18" category="mobile-accessories" ]') //cetagory="name"  ?>
                </div>
            </div>
        </div>
    </div>
</section> -->
<?php get_footer(); ?>