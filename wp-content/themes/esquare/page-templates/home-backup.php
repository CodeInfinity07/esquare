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
<!-- home slider section goes here -->
<section class="slider--home position-relative">
    <div class="slider--wraper">
        <div class="swiper product--promos">
            <div class=" swiper-wrapper">
                <div class="swiper-slide" style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/banner-website.png)">
                    <div class="slider--inside">
                        <div class="slider--content">
                            <div class="animate">
                                <div class="title"><span swiper-animate-effect="fadeInDown">Audio & Music</span>
                                    <h1>Bluetooth Speaker</h1>
                                </div>
                            </div>
                            <div class="animate">
                                <div class="description">New Modern Stylist Fashionable Men's Wearholder bag maxcan
                                    weather holder.</div>
                            </div>
                            <div class="animate">
                                <div class="action--click"><a href="#" class="btn-brand">Shop Now</a></div>
                            </div>
                        </div>
                        <div class="slider--product-thumbnail"><img
                                src="https://www.radiustheme.com/demo/wordpress/themes/metro/wp-content/uploads/2020/02/home_03_slide_1.png"
                                alt=""></div>
                    </div>
                </div>
                <div class="swiper-slide" style="background-image:url(<?php echo get_template_directory_uri(); ?>/app/images/slide002.jpg)">
                    <div class="slider--inside">
                        <div class="slider--content">
                            <div class="animate">
                                <div class="title"><span swiper-animate-effect="fadeInDown">Audio & Music</span>
                                    <h1>Bluetooth Speaker</h1>
                                </div>
                            </div>
                            <div class="animate">
                                <div class="description">New Modern Stylist Fashionable Men's Wearholder bag maxcan
                                    weather holder.</div>
                            </div>
                            <div class="animate">
                                <div class="action--click"><a href="#" class="btn-brand">Shop Now</a></div>
                            </div>
                        </div>
                        <div class="slider--product-thumbnail"><img
                                src="http://localhost/esquare/wp-content/uploads/2022/04/el_img8_hover-500x500-1.jpg"
                                alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="carousel--products woocommerce">
      <div class="container">
          <div class="row">
              <div class="col-md-4 position-relative">
                <div class="banner">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                            <img src="<?php echo get_template_directory_uri(); ?>/app/images/accessories-banner.jpg" alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">Mobile <br>Accessories</h3>
                               
                                <a href="#" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="banner">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/gadgets-banner.jpg" alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">25% off on</br>Clothings </h3>
                            
                                <a href="#" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="banner">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/banner-bags.jpg" alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <h3 class="title">Modern </br>Laddies Bags</h3>
                             
                                <a href="#" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
          </div>
      </div>
    </section>
    


    <section class="the-slider woocommerce">
   <div class="container">
      <div class="swiper product--slider">
      <h2 class="widget-title"><span class="subs"><i class="icon-e-89"></i></span><span>Accessories & Electronics</span></h2>

         <div class="woocommerce--custom swiper-wrapper ">
                <?php
                    $args =array( 'post_type' => 'product', 
                    'stock' => 1, 
                    'posts_per_page' => 15,
                    'product_cat' => 'electronics', 
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
</section>
<section class="the-slider woocommerce">
   <div class="container">
      <div class="swiper product--slider">
      <h2 class="widget-title"><span class="subs"><i class="icon-f-09"></i></span><span>Clothings</span></h2>

         <div class="woocommerce--custom swiper-wrapper ">
                <?php
                    $args =array( 'post_type' => 'product', 
                    'stock' => 1, 
                    'posts_per_page' => 15,
                    'product_cat' => 'electronics', 
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
</section>

<section class="the-slider woocommerce">
   <div class="container">
      <div class="swiper product--slider">
      <h2 class="widget-title"><span class="subs"><i class="icon-f-59"></i></span><span>Mobile Accessories</span></h2>

         <div class="woocommerce--custom swiper-wrapper ">
                <?php
                    $args =array( 'post_type' => 'product', 
                    'stock' => 1, 
                    'posts_per_page' => 15,
                    'product_cat' => 'electronics', 
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
</section>

<section class="carousel--products">
      <div class="container">
          <div class="row">
              <div class="col-md-6 position-relative">
                <div class="banner">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/banner-accessories.png" alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                               <div class="banner-text">
                               <h3 class="title">Mobile <br>Accessories</h3>
                                <div class="description">
                                    The best choose for the life, discovery it!
                                </div>
                               </div>
                                <a href="#" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="banner">
                    <div class="item-banner">
                        <div class="figure">
                            <figure>
                                <img src="<?php echo get_template_directory_uri(); ?>/app/images/banner-clothing.png" alt="">
                            </figure>
                        </div>
                        <div class="inner">
                            <div class="banner-content">
                                <div class="banner-text">
                                <h3 class="title">25% off on</br>Clothings </h3>
                                <div class="description">
                                    The best choose for the life, discovery it!
                                </div>
                                </div>
                               
                                <a href="#" class="btn-brand-underlined">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
          
          </div>
      </div>
    </section>
<section class="products--listing--home">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h2 class="widget-title"><span class="subs"><i class="icon-f-56"></i></span><span>Latest in Store</span></h2>
            </div>
            <div class="col-md-12 woocommerce--custom">
            <?php
            echo do_shortcode('[products limit="24"]') //cetagory="name"

            ?>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>
