<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package esquare
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$container = get_theme_mod( 'esquare_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="theme-color" content="#ea8787e0">
	<link rel="shortcut icon" href="/wp-content/uploads/2023/03/esquare_favicon.png" />
	
	<meta name="google-site-verification" content="E4ztC-b4YkL8ifU78o2bVYF-rlHQ_YAVu3xyEYRVJDo" />

	
    <?php wp_head(); ?>
   
    <meta name="google-site-verification" content="dIcrr3L32CdVOCshd0fMB6-r1nVU_v2gvHTMxMOMRJ8" />
    
    <!-- Meta Pixel Code -->
        <script>
          !function(f,b,e,v,n,t,s)
          {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
          'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '1326244971553627');
          fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
          src="https://www.facebook.com/tr?id=1326244971553627&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->

    
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MWHR9ND');</script>
<!-- End Google Tag Manager -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9ET218NS7F"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-9ET218NS7F');
</script>
<style>
    .term-description a{
        color: red!important;
    }
    /* Gaming Category Style */
    .gaming ul.dropdown-menu {
        display: block!important;
        width: 200px!important;
        padding: 0px!important;
        left: 53%!important;
    }
    
@media only screen and (min-width: 320px) and (max-width: 767px)
{ 
    .quantity{
        width:100%!important;
    }
    .woocommerce div.product form.cart {
        display: block!important;
    }
    .woocommerce .quantity .qty {
        width: 100%!important;
        text-align: center!important;
        margin-bottom: 20px!important;
    }
    .woocommerce div.product form.cart .btn-brand {
        display: flex!important;
        width: 100%!important;
    }
    .woocommerce div.product form.cart .btn-buy {
        margin-top: 10px!important;
        margin-left: 0px!important;
    }
    
}
</style>
</head>

<body <?php body_class(); ?>>
    <header class="header" itemscope>
        <div class="container">
            <div class="header-top mb-15">
                <div class="row">
                    <div class="col-md-6 d-none d-md-block d-lg-block"><a href="mailto:info@esquare.store"
                            class="mr-2"><span class="icon-f-72"></span>
                            info@esquare.store</a>|<a href="tel:+923260099111" class="ml-2"><span
                                class="icon-f-93"></span> +92 326 0099 111</a></div>
                    <div
                        class="col-md-6 d-flex justify-content-center justify-content-md-end  align-items-center justify-content-lg-end text-center text-md-right text-lg-right">
                        <a title="My account" class="my-account mr-2" href="/my-account/"><span
                                class="icon-f-94"></span> My
                            account</a> | <a href="/contact/" class="location ml-2"><span class="icon-f-54"></span>
                            Store
                            Location</a></div>
                </div>
            </div>
        </div>
        <div class="navbar--nav">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-6 col-md-4 col-lg-3 d-flex align-items-center">
                        <div class="humburger">
                            <span class="l1"></span>
                            <span class="l2"></span>
                            <span class="l3"></span>
                        </div>
                        <div class="store--name">
                            <!-- Your site title as branding in the menu -->
                            <?php if ( ! has_custom_logo() ) { ?>

                            <?php if ( is_front_page() && is_home() ) : ?>

                            <h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                    title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
                                    itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

                            <?php else : ?>

                            <a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
                                itemprop="url"><?php bloginfo( 'name' ); ?></a>

                            <?php endif; ?>


                            <?php } else {
                                the_custom_logo();
                                } ?>
                            <!-- end custom logo -->
                        </div>
                    </div>
                    <div class="navbar">
                        <div class="navbar--main">
                            <?php wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'navbar--main-inner',
						'container_id'    => 'navbarNavDropdown',
						'menu_class'      => 'navbar--ul',
						'fallback_cb'     => '',
						'menu_id'         => 'main-menu',
						'depth'           => 3,
						'walker'          => new esquare_WP_Bootstrap_Navwalker(),
					)
				); ?>
                        </div>
                        <div class="search--box">

                            <form name="myform" method="GET" action="<?php echo esc_url(home_url('/')); ?>">

                                <?php if (class_exists('WooCommerce')) : ?>
                                <?php 
                                if(isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat']))
                                {
                                $optsetlect=$_REQUEST['product_cat'];
                                }
                                else{
                                $optsetlect=0;  
                                }
                                    $args = array(
                                                'show_option_all' => esc_html__( 'All Categories', 'woocommerce' ),
                                                'hierarchical' => 1,
                                                'class' => 'cat',
                                                'echo' => 1,
                                                'value_field' => 'slug',
                                                'selected' => $optsetlect
                                            );
                                    $args['taxonomy'] = 'product_cat';
                                    $args['name'] = 'product_cat';              
                                    $args['class'] = 'cate-dropdown hidden-xs';
                                    wp_dropdown_categories($args);

                                ?>
                                <input type="hidden" value="product" name="post_type">
                                <?php endif; ?>
                                <div class="search"> <input type="text" name="s" class="searchTerm" maxlength="128"
                                        value="<?php echo get_search_query(); ?>"
                                        placeholder="<?php esc_attr_e('Search entire store here...', 'woocommerce'); ?>">

                                    <button type="submit" title="<?php esc_attr_e('Search', 'woocommerce'); ?>"
                                        class="searchButton"> <i class="icon-f-85"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-3 col-md-4 col-lg-3 d-flex justify-content-end align-items-center">
                        <div class="navbar--cart">
                            <div class="nav--commerce d-flex">
                                <div class="search-toggle">
                                    <span class="btn--header">
                                        <i class="icon-f-84 close"></i>
                                        <i class="icon-f-85 open"></i>
                                    </span>
                                </div>
                                <!-- <i class="icon-uniE66D mr-2"></i><span>  Free shipping all over the world!</span>  -->
                                <div class="mini--cart">
                                    <a class="cart-contents btn--header" href="<?php echo get_home_url(); ?>/cart/"
                                        title="View your shopping cart"
                                        data-totalitems="<?php echo WC()->cart->get_cart_contents_count(); ?>">

                                        <i class="icon-f-40"></i>
                                        <!-- <div class="mini--cart--count">
                                             <span class="count-items"><?php echo WC()->cart->get_cart_contents_count(); ?> </span>
                                        </div> -->

                                        <div id="mini-cart-count">
                                            <div class="count-items">
                                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                                            </div>
                                        </div>

                                    </a>

                                </div>
                                <div class="navbar-collapsan">
                                    <div class="hamburger-icon" id="icon">
                                        <div class="icon-1" id="a"></div>
                                        <div class="icon-2" id="b"></div>
                                        <div class="icon-3" id="c"></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="navigation--bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                    

                        <div class="navbar-vertical">
                            <div class="vertical-menu-toggle">
                                <i class="icon-h-18"></i><span>ALL DEPARTMENTS</span> 
                            </div>
							<div class="navbar-vertical-nav">
								<?php
									wp_nav_menu(
										array(
											'theme_location'  => 'home',
											'container' => false,
											'menu_class'      => 'home--menu',
											'fallback_cb'     => '',
										)
									);
								?>
							</div>
                            

                        </div>



						
                       

                      
                    </div>
                </div>
            </div>
            <div class="dark-blue" id="blue"></div>
        </div> -->
    </header>
    <div class="modal-cart woocommerce">
        <div class="modal-cart-wrapper modal-cart-transition">

            <div class="modal-cart-header">
                <h2>Cart</h2>
                <button class="modal-cart-close modal-cart-toggle"><i class="icon-f-84 close"></i></button>

            </div>
            <div class="container modal-cart-body">
                <div class="row modal-cart-content">
                    <div class="col-md-12 text-center">
                        <div class="cart-heading">
                            <h4 class="btn-brand-underlined">Shopping Cart
                                <span><?php echo WC()->cart->get_cart_contents_count(); ?></span></h4>
                        </div>
                    </div>
                    <div class="col-md-12 mini--cart">
                        <div class="woocommerce-cart-form-wrap">
                            <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>