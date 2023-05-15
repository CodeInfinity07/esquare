<?php
/**
 * Template Name: Homepage
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
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "WebSite",
  "name": "Esquare Store",
  "url": "https://esquare.store/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://esquare.store/shop/{search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Esquare Store",
  "alternateName": "Esquare Technology",
  "url": "https://esquare.store/",
  "logo": "https://esquare.store/wp-content/uploads/2022/10/logo-esquare-electronic-store.png.webp",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "03260099111",
    "contactType": "bill payment",
    "areaServed": "PK",
    "availableLanguage": "en"
  },
  "sameAs": [
    "https://www.facebook.com/EsquareTechnology",
    "https://www.instagram.com/esquaretechnology1/",
    "https://twitter.com/esquaretechnolo",
    "https://esquare.store/"
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org/", 
  "@type": "BreadcrumbList", 
  "itemListElement": [{
    "@type": "ListItem", 
    "position": 1, 
    "name": "Shop",
    "item": "https://esquare.store/shop/"  
  },{
    "@type": "ListItem", 
    "position": 2, 
    "name": "Earbuds",
    "item": "https://esquare.store/product-category/earbuds-price-in-pakistan/"  
  },{
    "@type": "ListItem", 
    "position": 3, 
    "name": "Contact",
    "item": "https://esquare.store/contact/"  
  }]
}
</script>


<!-- home slider section goes here -->
<section class="slider--home position-relative">
    <div class="slider--wraper">
        <div class="product--promos">
        <div class="swiper-slide"
                style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/t100-plus.webp)">
                <a class="banner-link"
                    href="https://esquare.store/product/t100-plus-smart-watch/"></a>
            </div>
        <div class="swiper-slide"
                style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/pro6-banner.webp)">
                <a class="banner-link"
                    href="https://esquare.store/product/mini-pro-6s-bluetooth-earphone-tws-wireless-headphones-sport-gaming-headset-earbuds/"></a>
            </div>
            <div class="swiper-slide"
                style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/banner-mosquito-repelent.webp)">
                <a class="banner-link"
                    href="https://esquare.store/product/electric-ultrasonic-pest-repeller-mosquito-repellent/"></a>
            </div>
            <div class="swiper-slide"
                style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/banner-m10-airbuds.webp)">
                <!-- <div class="slider--inside">
                        <div class="slider--content">
                            <div class="animate">
                                <div class="title">
                                    <h1>Mobile<br> Accessories</h1>
                                </div>
                            </div>
                            <div class="animate">
                                <div class="description">Buy exclusive collection of mobile  accessories on discount prices. </div>
                            </div>
                            <div class="animate">
                                <div class="action--click"><a href="/product-category/mobile-accessories/" class="btn-brand">Shop Now</a></div>
                            </div>
                        </div>
                    </div> -->
                <a class="banner-link"
                    href="https://esquare.store/product/m10-tws-wireless-earbuds-with-microphone-power-bank/"></a>
            </div>
           
        </div>
    </div>
</section>
<section class="carousel--products woocommerce d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 position-relative">
                <div class="banner">
					<a href="/mobile-accessories/">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/accessories-banner.webp"
                                    alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">Mobile<br>Accessories</h3>
                                <a href="/mobile-accessories/" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="banner">
                    <a href="/product-category/smart-watches/">
					<div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/gadgets-banner.webp"
                                    alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">Gadgets<br>for smart life </h3>
                                <a href="/product-category/smart-watches/" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="banner">
					<a href="/product-category/gamings/">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/gaming-banner.webp"
                                    alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">Gaming<br>Accessories</h3>
                                <a href="/product-category/gamings/"
                                    class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
					</a>
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
                    <h2 class="widget-title">Hot Selling Products</h2>
                    <a href="/product-category/earbuds/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-banner-sale.webp" alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 15,
                'product_cat' => 'Hot Selling Products', 
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

<section class="the-slider hot-sells-products woocommerce">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Earbuds</h2>
                    <a href="/product-category/earbuds-price-in-pakistan/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-banner-earbuds.webp" alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                'posts_per_page' => 15,
                'product_cat' => 'earbuds-price-in-pakistan', 
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
                    <h2 class="widget-title">Smart Watches</h2>
                    <a href="/product-category/smart-watches/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-banner-watches.webp"
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
                'product_cat' => 'smart-watches', 
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
                    <h2 class="widget-title">PC Peripherals & Others</h2>
                    <a href="/product-category/computer-accessories/" class="btn btn-brand"> View all</a>
                </div>
            </div>
        </div>
        <div class="sales-slider">
            <div class="row">
                <div class="col-md-2 pr-md-0 mr-md-0 d-none d-md-block">
                    <div class="slider-banner">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/slider-computer.webp"
                            alt="">
                    </div>
                </div>
                <div class="col-md-10 pl-md-0 ml-md-0">
                    <div class="swiper ">
                        <div class="woocommerce--custom product--slider">
                            <?php
                // $args =array( 'post_type' => 'product', 
                // 'stock' => 1, 
                // 'posts_per_page' => 15,
                // 'product_cat' => 'computer-accessories', 
                // 'orderby' =>'date',
                // 'order' => 'ASC' 
                // );
                // $the_query = new WP_Query( $args );
                // if ( $the_query->have_posts() ) {
                //     while ( $the_query->have_posts() ) : $the_query->the_post();
                //         // Get default product template
                //         wc_get_template_part( 'content', 'slider' );
                //     endwhile;
                // } else {
                //     echo __( 'No products found' );
                // }
                // wp_reset_postdata();
            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section> -->
<!-- <section>
    <div class="container">
        <div class="banner dark align-center medium bordered x1">
            <div class="banner-content">
                <div class="banner-content-wrapper">
                    <h6 class="entry-subtitle style-2">Weekend Discount</h6>
                    <h3 class="entry-title">Home Speaker</h3>
                    <div class="entry-description">
                        <p>Don't miss the last opportunity.</p>
                    </div>
                    <div class="entry-button"><a href="" class="overlay-link"></a></div>
                </div>
</section> -->
<section class="products--listing--home">
    <div class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Latest Products</h2>
                </div>
            </div>
            <div class="col-md-12">
                <div class="woocommerce--custom">
                    <?php echo do_shortcode('[products limit="12" order="DESC" ]')  //cetagory="name"  ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="slider-brands-wrapper">
    <section class="container">
        <div class="row">
            <div class=" col-md-12">
                <div class="section-header">
                    <h2 class="widget-title">Our Brands</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="slider-brands">
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/apple.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/mi.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/samsung.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/nokia.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/lg.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/sony.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/lenovo.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/motorola.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/huawei.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/htc.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/toshiba.webp" alt="" srcset="">
                    </div>
                    <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/motorola.webp" alt="" srcset="">
                    </div>
                    <!-- <div class="item-brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/app/images/brands/huawei.webp" alt="" srcset="">
                    </div> -->
                </div>

            </div>
        </div>
    </section>
</section>


<?php get_footer(); ?>