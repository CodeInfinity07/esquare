<?php
/**
 * Template Name: Contact page
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

                    <section class="contact-page-section pt-5 mt-5">
      <div class="container">
         
            <div class="inner-container">
              <div class="row clearfix">
                
                  <!--Form Column-->
                    <div class="form-column col-md-8 col-sm-12 col-xs-12">
                      <div class="inner-column">
                          
                           <?php echo do_shortcode('[contact-form-7 id="511" title="Contact form 1"]'); ?>
                            
                        </div>
                    </div>
                    
                    <!--Info Column-->
                    <div class="info-column col-md-4 col-sm-12 col-xs-12">
                      <div class="inner-column">
                          <h2>Contact Info</h2>
                            <ul class="list-info list-unstyled">
                              <li><i class="icon-f-54"></i>Office # 309 Al Hafeez Shopping Mall, Main Boulevard, Gulberg III, Lahore</li>
                                <li><i class="icon-f-72"></i>info@esquare.store</li>
                                <li><i class="icon-f-93"></i>PK: +92 326 0099 111</li>
                            </ul>
                            <ul class="social-icon-four">
                                <li class="follow">Follow on: </li>
                                <li><a href="https://www.facebook.com/eesquarestore"><i class="icon-g-64"></i></a></li>
                                <li><a href="https://www.instagram.com/e.squarestore/"><i class="icon-g-67"></i></a></li>
                              
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>
