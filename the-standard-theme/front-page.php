<?php
/**
 * Front page — THE STANDARD homepage.
 *
 * @package the-standard
 */

get_header();
ts_the_home_data();
get_template_part( 'template-parts/home-app' );
get_footer();
