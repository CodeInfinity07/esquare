<?php
/**
 * Template Name: woocommerce pages
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
								'delimiter' => '/</li>',
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

<div class="woocommerce--order--setup">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'loop-templates/content', 'woopage' ); ?>

						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- #content -->

</div><!-- #full-width-page-wrapper -->

<?php get_footer(); ?>
