<?php
/**
 * The main template file.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
	echo '<main id="main-content" class="site-main"><p>' . esc_html__( 'Headless WordPress theme ready for React consumption.', 'ashaduzzaman-portfolio' ) . '</p></main>';

get_footer();
