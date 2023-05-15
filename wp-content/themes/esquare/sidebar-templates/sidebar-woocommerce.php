<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! is_active_sidebar( 'left-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'esquare_sidebar_position' );
?>


<?php dynamic_sidebar( 'left-sidebar' ); ?>

